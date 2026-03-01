<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\ImmunizationRecord;
use App\Models\Facility;
use App\Exports\ImmunizationExport;
use App\Constants\AppConstants;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Lazy;
use Illuminate\Support\Facades\Cache;

new #[Lazy] class extends Component {
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $paginate = 10;
    public $search = '';
    public $paginationOptions = [];

    public $facility_id;
    public $date;
    public $facilities = [];

    protected $listeners = [
        'export-excel' => 'exportExcel',
        'export-pdf' => 'exportPdf',
    ];

    public function mount()
    {
        $this->paginationOptions = AppConstants::PAGINATIONS;
        $this->facilities = Cache::remember('facilities_list', 3600, function () {
            return Facility::orderBy('name')->pluck('name', 'id')->toArray();
        });

        $this->facility_id = 1;
        $this->date = now()->format('Y-m-d');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPaginate(): void
    {
        $this->resetPage();
    }

    public function updatedFacilityId(): void
    {
        $this->resetPage();
    }

    public function updatedDate(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function immunizationRecords()
    {
        $childIdsPaginated = ImmunizationRecord::query()
            ->select('child_id')
            ->where('health_facility_id', $this->facility_id)
            ->whereDate('date_given', $this->date)
            ->when($this->search, function ($query) {
                $query->whereHas('child', fn($q) => $q->where('name', 'like', "%{$this->search}%"));
            })
            ->groupBy('child_id')
            ->orderBy('child_id')
            ->paginate($this->paginate);

        if ($childIdsPaginated->isEmpty()) {
            return $childIdsPaginated;
        }

        $records = ImmunizationRecord::with(['child', 'vaccine', 'facility'])
            ->whereIn('child_id', $childIdsPaginated->pluck('child_id'))
            ->where('health_facility_id', $this->facility_id)
            ->whereDate('date_given', $this->date)
            ->get();

        $formatted = $records
            ->groupBy('child_id')
            ->map(
                fn($items) => (object) [
                    'child' => $items->first()->child->name,
                    'facility' => $items->first()->facility->name,
                    'date' => $items->first()->date_given,
                    'officer' => $items->first()->officer_name,
                    'vaccines' => $items->pluck('vaccine.name')->toArray(),
                ],
            )
            ->values();

        $childIdsPaginated->setCollection($formatted);

        return $childIdsPaginated;
    }

    /**
     * Ambil semua data untuk export (tanpa pagination)
     */
    private function getAllRecords()
    {
        $childIds = ImmunizationRecord::query()
            ->select('child_id')
            ->where('health_facility_id', $this->facility_id)
            ->whereDate('date_given', $this->date)
            ->when($this->search, function ($query) {
                $query->whereHas('child', fn($q) => $q->where('name', 'like', "%{$this->search}%"));
            })
            ->groupBy('child_id')
            ->orderBy('child_id')
            ->pluck('child_id');

        if ($childIds->isEmpty()) {
            return collect([]);
        }

        $records = ImmunizationRecord::with(['child', 'vaccine', 'facility'])
            ->whereIn('child_id', $childIds)
            ->where('health_facility_id', $this->facility_id)
            ->whereDate('date_given', $this->date)
            ->get();

        return $records
            ->groupBy('child_id')
            ->map(
                fn($items) => (object) [
                    'child' => $items->first()->child->name,
                    'facility' => $items->first()->facility->name,
                    'date' => $items->first()->date_given,
                    'officer' => $items->first()->officer_name,
                    'vaccines' => $items->pluck('vaccine.name')->toArray(),
                ],
            )
            ->values();
    }

    public function exportExcel()
    {
        $fileName = "immunization-{$this->date}.xlsx";
        return Excel::download(new ImmunizationExport($this->facility_id, $this->date, $this->search), $fileName);
    }

    public function exportPdf()
    {
        $records = $this->getAllRecords();
        $facilityName = $this->facilities[$this->facility_id] ?? 'Unknown Facility';

        $pdf = Pdf::loadView('pdf.immunization-table', [
            'records' => $records,
            'facility' => $facilityName,
            'date' => $this->date,
            'search' => $this->search,
            'generatedAt' => now()->format('d-m-Y H:i:s'),
        ]);

        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption('isHtml5ParserEnabled', true);

        return response()->streamDownload(fn() => print $pdf->output(), "immunization-{$this->date}-" . now()->format('His') . '.pdf');
    }
};
?>

@placeholder
    <livewire:loading-general />
@endplaceholder

<div>
    <div class="mb-3 d-flex justify-content-between">
        {{-- PAGINATE --}}
        <div class="col-2">
            <select wire:model.live="paginate" class="form-control">
                @foreach ($this->paginationOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        {{-- DATE GIVEN --}}
        <div class="form-group">
            <input type="date" wire:model.live="date" class="form-control">
        </div>

        {{-- FACILITY --}}
        <div class="form-group" wire:ignore>
            <select id="select_facility" class="form-control select2bs4" wire:model.live="facility_id">
                @foreach ($facilities as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        {{-- SEARCH --}}
        <div class="col-3">
            <input type="text" class="form-control" placeholder="Search..." wire:model.live.debounce.300ms="search">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name Child</th>
                    <th>Name Facility</th>
                    <th>Name Vaccine</th>
                    <th>Date Given</th>
                    <th>Officer Name</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($this->immunizationRecords as $item)
                    <tr>
                        <td>{{ $this->immunizationRecords->firstItem() + $loop->index }}</td>
                        <td>{{ $item->child }}</td>
                        <td>{{ $item->facility }}</td>
                        <td>
                            @foreach ($item->vaccines as $v)
                                <span class="badge badge-primary">{{ $v }}</span>
                            @endforeach
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
                        <td>{{ $item->officer }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-file-medical fa-2x mb-2 d-block"></i>
                            No immunization records available
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $this->immunizationRecords->links() }}
    </div>
</div>

@script
    <script>
        function initSelect2() {
            if ($('#select_facility').hasClass("select2-hidden-accessible")) {
                $('#select_facility').select2('destroy');
            }
            $('#select_facility').select2({
                theme: 'bootstrap4'
            });
            $('#select_facility').on('change', function() {
                $wire.set('facility_id', $(this).val());
            });
        }
        setTimeout(initSelect2, 200);
        document.addEventListener('livewire:update', initSelect2);
        document.addEventListener('livewire:navigated', initSelect2);
    </script>
@endscript

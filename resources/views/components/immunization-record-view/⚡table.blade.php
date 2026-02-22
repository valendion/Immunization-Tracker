<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\ImmunizationRecord;
use App\Models\Facility;
use App\Exports\ImmunizationExport;
use App\Constants\AppConstants;
use Maatwebsite\Excel\Facades\Excel;
new class extends Component {
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $paginate = 10;
    public $search = '';
    public $paginationOptions = [];

    // filter
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

        // Ambil fasilitas
        $this->facilities = Facility::orderBy('name')->get();

        // Default facility = Lagaligo 1 (ID=1)
        $this->facility_id = 1;

        // Default tanggal = hari ini
        $this->date = date('Y-m-d');
    }

    #[Computed]
    public function immunizationRecords()
    {
        /*
        |--------------------------------------------------------------------------
        | 1) Ambil daftar anak (child_id) berdasar fasilitas & tanggal
        |--------------------------------------------------------------------------
        */
        $childQuery = ImmunizationRecord::query()
            ->select('child_id')
            ->where('health_facility_id', $this->facility_id)
            ->whereDate('date_given', $this->date)
            ->when($this->search, function ($query) {
                $query->whereHas('child', fn($q) => $q->where('name', 'like', "%{$this->search}%"));
            })
            ->groupBy('child_id')
            ->orderBy('child_id');

        // paginate per anak
        $childIdsPaginated = $childQuery->paginate($this->paginate);

        /*
        |--------------------------------------------------------------------------
        | 2) Ambil imunisasi hanya untuk anak yang ada di halaman ini
        |--------------------------------------------------------------------------
        */
        $records = ImmunizationRecord::with(['child', 'vaccine', 'facility'])
            ->whereIn('child_id', $childIdsPaginated->pluck('child_id'))
            ->where('health_facility_id', $this->facility_id)
            ->whereDate('date_given', $this->date)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 3) Group per anak
        |--------------------------------------------------------------------------
        */
        $grouped = $records->groupBy('child_id');

        /*
        |--------------------------------------------------------------------------
        | 4) Bentuk object final (1 anak = 1 row)
        |--------------------------------------------------------------------------
        */
        $final = $grouped
            ->map(function ($items) {
                return (object) [
                    'child' => $items->first()->child->name,
                    'facility' => $items->first()->facility->name,
                    'date' => $items->first()->date_given,
                    'officer' => $items->first()->officer_name,
                    'vaccines' => $items->pluck('vaccine.name')->toArray(),
                ];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | 5) Replace collection pada paginator
        |--------------------------------------------------------------------------
        */
        $childIdsPaginated->setCollection($final);

        return $childIdsPaginated;
    }

    public function exportExcel()
    {
        $fileName = "immunization-{$this->date}.xlsx";

        return Excel::download(new ImmunizationExport($this->facility_id, $this->date, $this->search), $fileName);
    }
};
?>
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

        {{-- FACILITY (Select2) --}}
        <div class="form-group" wire:ignore>

            <select id="select_facility" class="form-control select2bs4" wire:model.live="facility_id">
                @foreach ($facilities as $facility)
                    <option value="{{ $facility->id }}">
                        {{ $facility->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- SEARCH --}}
        <div class="col-3">
            <input type="text" class="form-control" placeholder="Pencarian..." wire:model.live="search">
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
                @foreach ($this->immunizationRecords() as $item)
                    <tr>
                        <td>{{ $this->immunizationRecords->firstItem() + $loop->index }}</td>
                        <td>{{ $item->child }}</td>
                        <td>{{ $item->facility }}</td>

                        <td>
                            @foreach ($item->vaccines as $v)
                                <span class="badge badge-primary">{{ $v }}</span>
                            @endforeach
                        </td>

                        <td>{{ $item->date }}</td>
                        <td>{{ $item->officer }}</td>


                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $this->immunizationRecords()->links() }}
    </div>
</div>


@script
    <script>
        function initSelect2() {
            // Pastikan select2 dihancurkan dulu agar tidak double binding
            if ($('#select_facility').hasClass("select2-hidden-accessible")) {
                $('#select_facility').select2('destroy');
            }

            $('#select_facility').select2({
                theme: 'bootstrap4'
            });

            // Event select2 -> update livewire
            $('#select_facility').on('change', function() {
                let val = $(this).val();
                $wire.set('facility_id', val);
            });
        }

        // Page load
        setTimeout(initSelect2, 200);

        // Ini sangat penting: re-init setelah livewire update DOM
        document.addEventListener('livewire:update', initSelect2);
        document.addEventListener('livewire:navigated', initSelect2);
    </script>
@endscript

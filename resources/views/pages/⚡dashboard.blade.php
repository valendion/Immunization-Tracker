<?php

use Livewire\Component;
use App\Constants\AppConstants;
use App\Models\ImmunizationRecord;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;

new class extends Component {
    public $title = AppConstants::DASHBOARD;
    public $sub_title = AppConstants::TOTAL_IMMUNIZATION_RECORD;
    public $icon = 'home';
    public $vaccines_data_list = AppConstants::VACCINES_DATA_LIST;
    public $date;

    // Gunakan computed property untuk auto-caching
    #[Computed]
    public function counts(): array
    {
        $date = Carbon::parse($this->date);

        // Single query dengan grouping - mengurangi N+1 problem
        $results = ImmunizationRecord::query()
            ->select('vaccines.name', DB::raw('COUNT(*) as total'))
            ->join('vaccines', 'immunization_records.vaccine_id', '=', 'vaccines.id')
            ->whereDate('immunization_records.date_given', $date)
            ->where(function ($query) {
                foreach ($this->vaccines_data_list as $key => $vaccine) {
                    $query->orWhere('vaccines.name', 'like', $vaccine['like']);
                }
            })
            ->groupBy('vaccines.name')
            ->pluck('total', 'vaccines.name')
            ->toArray();

        // Map hasil ke format yang diinginkan
        $counts = [];
        foreach ($this->vaccines_data_list as $key => $vaccine) {
            $counts[$key] = collect($results)->filter(fn($total, $name) => str_starts_with($name, rtrim($vaccine['like'], '%')))->sum();
        }

        return $counts;
    }

    #[Computed]
    public function vaccineList(): array
    {
        return $this->vaccines_data_list;
    }

    public function mount(): void
    {
        $this->date = Carbon::today()->format('Y-m-d');
    }
};
?>
<livewire:content-card-page :title="$title" :icon="$icon">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-lg-8 col-12 mb-2 mb-lg-0">
                <h4 class="mb-0">{{ $sub_title }}</h4>
            </div>
            <div class="col-lg-4 col-12">
                <input type="date" class="form-control" wire:model.live="date" wire:loading.attr="disabled">
            </div>
        </div>
    </div>

    <div class="card-body">
        <div wire:loading.remove wire:target="date">
            <div class="row">
                @foreach (array_chunk($this->vaccineList, 4, true) as $chunk)
                    @foreach ($chunk as $key => $vaccine)
                        <div class="col-lg-3 col-6 mb-3">
                            <div class="info-box h-100">
                                <span class="info-box-icon bg-{{ $vaccine['color'] }}">
                                    <i class="fas fa-{{ $vaccine['icon'] }}"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $vaccine['name'] }}</span>
                                    <span class="info-box-number">
                                        {{ $this->counts[$key] ?? 0 }} babies
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if (!$loop->last)
            </div>
            <div class="row">
                @endif
                @endforeach
            </div>
        </div>

        <div class="w-100" wire:loading wire:target="date">
            <livewire:loading-general />
        </div>
    </div>
</livewire:content-card-page>

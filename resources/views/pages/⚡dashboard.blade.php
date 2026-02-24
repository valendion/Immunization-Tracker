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

    public $date;

    public $counts = [
        'BCG' => 0,
        'POLIO' => 0,
        'DPT' => 0,
        'PCV' => 0,
        'ROTA' => 0,
        'MR' => 0,
    ];

    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
        $this->calculateCounts();
    }

    public function updatedDate()
    {
        $this->calculateCounts();
    }

    #[Computed]
    public function calculateCounts()
    {
        $date = Carbon::parse($this->date);

        $this->counts['BCG'] = ImmunizationRecord::whereDate('date_given', $date)->whereHas('vaccine', fn($q) => $q->where('name', 'like', 'BCG%'))->count();

        $this->counts['POLIO'] = ImmunizationRecord::whereDate('date_given', $date)->whereHas('vaccine', fn($q) => $q->where('name', 'like', 'Polio%'))->count();

        $this->counts['DPT'] = ImmunizationRecord::whereDate('date_given', $date)->whereHas('vaccine', fn($q) => $q->where('name', 'like', 'DPT%'))->count();

        $this->counts['PCV'] = ImmunizationRecord::whereDate('date_given', $date)->whereHas('vaccine', fn($q) => $q->where('name', 'like', 'PCV%'))->count();

        $this->counts['ROTA'] = ImmunizationRecord::whereDate('date_given', $date)->whereHas('vaccine', fn($q) => $q->where('name', 'like', 'Rotavirus%'))->count();

        $this->counts['MR'] = ImmunizationRecord::whereDate('date_given', $date)->whereHas('vaccine', fn($q) => $q->where('name', 'like', 'Measles-Rubella%'))->count();
    }
};
?>





<livewire:content-card-page :title="$title" :icon="$icon">

    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-lg-8 col-12 mb-2 mb-lg-0">
                <h4 class="mb-0">{{ $this->sub_title }}</h4>
            </div>

            <div class="col-lg-4 col-12">
                <input type="date" class="form-control" wire:model.live="date">
            </div>
        </div>
    </div>


    <div class="card-body">
        <div wire:loading.remove wire:target="date">

            <div class="row">

                <!-- BCG -->
                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-dark"><i class="fas fa-syringe"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">BCG</span>
                            <span class="info-box-number">{{ $counts['BCG'] }} babies</span>
                        </div>
                    </div>
                </div>

                <!-- POLIO -->
                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary"><i class="fas fa-syringe"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">POLIO</span>
                            <span class="info-box-number">{{ $counts['POLIO'] }} babies</span>
                        </div>
                    </div>
                </div>

                <!-- DPT -->
                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fas fa-syringe"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">DPT</span>
                            <span class="info-box-number">{{ $counts['DPT'] }} babies</span>
                        </div>
                    </div>
                </div>

                <!-- PCV -->
                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-syringe"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">PCV</span>
                            <span class="info-box-number">{{ $counts['PCV'] }} babies</span>
                        </div>
                    </div>
                </div>

            </div>


            <div class="row">

                <!-- ROTA -->
                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fas fa-syringe"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">ROTA</span>
                            <span class="info-box-number">{{ $counts['ROTA'] }} babies</span>
                        </div>
                    </div>
                </div>

                <!-- MR -->
                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-syringe"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">CAMPAK/MR</span>
                            <span class="info-box-number">{{ $counts['MR'] }} babies</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="w-100" wire:loading wire:target="date">
            <livewire:loading-general />
        </div>
    </div>


</livewire:content-card-page>

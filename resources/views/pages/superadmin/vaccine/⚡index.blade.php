<?php

use Livewire\Component;
use App\Constants\AppConstants;

new class extends Component {
    public $title = AppConstants::DATA_VACCINE;

    public function moveToCreate()
    {
        return $this->redirect(url: '/superadmin/vaccine/create', navigate: true);
    }
};
?>

<livewire:content-card-page :title="$title">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div> <button wire:click="moveToCreate" class="btn btn-primary mr-1">
                    <i class="fas fa-plus mr-1"></i>
                    Add data</button>
            </div>
            <livewire:print-button />
        </div>
    </div>

    <div class="card-body">
        <livewire:vaccine.table />
    </div>

</livewire:content-card-page>

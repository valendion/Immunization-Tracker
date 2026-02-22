<?php

use Livewire\Component;
use App\Constants\AppConstants;
use Livewire\Attributes\Title;
new #[Title('All Vaccines')] class extends Component {
    public $title = AppConstants::DATA_VACCINE;
    public $icon = 'syringe';
    public function moveToCreate()
    {
        return $this->redirect(url: '/admin/vaccine/create', navigate: true);
    }
};
?>

<livewire:content-card-page :title="$title" :icon="$icon">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div> <button wire:click="moveToCreate" class="btn btn-primary mr-1">
                    <i class="fas fa-plus mr-1"></i>
                    Add data</button>
            </div>

        </div>
    </div>

    <div class="card-body">
        <livewire:vaccine.table />
    </div>

</livewire:content-card-page>

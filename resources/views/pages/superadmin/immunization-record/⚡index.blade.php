<?php

use Livewire\Component;
use App\Constants\AppConstants;
use Livewire\Attributes\Title;
new #[Title('All Immunization Records')] class extends Component {
    public $title = AppConstants::DATA_IMMUNIZATION_RECORD;
    public $icon = 'file';
    public function moveToCreate()
    {
        return $this->redirect(url: '/superadmin/immunization-record/create', navigate: true);
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
            <livewire:print-button />
        </div>
    </div>

    <div class="card-body">
        <livewire:immunization-record.table />
    </div>

</livewire:content-card-page>

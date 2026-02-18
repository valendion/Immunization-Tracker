<?php

use Livewire\Component;
use App\Constants\AppConstants;

new class extends Component {
    public $title = AppConstants::DATA_CHILD;

    public function moveToCreate()
    {
        return $this->redirect(url: '/superadmin/child/create', navigate: true);
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
        <livewire:child.table />
    </div>

</livewire:content-card-page>

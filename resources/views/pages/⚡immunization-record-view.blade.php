<?php

use Livewire\Component;
use App\Constants\AppConstants;
use Livewire\Attributes\Title;
new #[Title('Immunization Records View')] class extends Component {
    public $title = AppConstants::DATA_IMMUNIZATION_RECORD_VIEW;
    public $icon = 'eye';
};
?>

<livewire:content-card-page :title="$title" :icon="$icon">
    <div class="card-header">
        <div class="d-flex ">

            <livewire:print-button />
        </div>
    </div>

    <div class="card-body">
        <livewire:immunization-record-view.table />
    </div>

</livewire:content-card-page>

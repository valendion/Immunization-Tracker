<?php

use Livewire\Component;
use App\Constants\AppConstants;
new class extends Component {
    public $title = AppConstants::DASHBOARD;
    // public $sub_title = AppConstants::UPDATE;
};
?>

<livewire:content-card-page :title="$this->title">
    <div class="card-header">
        <h4>{{ $this->title }}</h4>
    </div>

    <div class="card-body"></div>
</livewire:content-card-page>

<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    <div class="btn-group dropleft">
        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Cetak
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item text-success" href="#">
                <i class="fas fa-file-excel mr-1"></i>
                EXCEL</a>
            <a class="dropdown-item text-danger" href="#">
                <i class="fas fa-file-pdf mr-1"></i>
                PDF</a>
        </div>
    </div>
</div>

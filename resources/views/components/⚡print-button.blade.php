<?php

use Livewire\Component;

new class extends Component {
    public function exportExcel()
    {
        $this->dispatch('export-excel'); // kirim event ke parent/table
    }

    public function exportPdf()
    {
        $this->dispatch('export-pdf');
    }
};
?>

<div>
    <div class="btn-group dropright">
        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
            Cetak
        </button>

        <div class="dropdown-menu">

            <a class="dropdown-item text-success" wire:click="exportExcel" style="cursor:pointer;">
                <i class="fas fa-file-excel mr-1"></i> EXCEL
            </a>

            <a class="dropdown-item text-danger" wire:click="exportPdf" style="cursor:pointer;">
                <i class="fas fa-file-pdf mr-1"></i> PDF
            </a>

        </div>
    </div>
</div>

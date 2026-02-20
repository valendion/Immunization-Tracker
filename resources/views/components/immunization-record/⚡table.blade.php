<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\ImmunizationRecord;
use Livewire\Attributes\On;
use App\Constants\AppConstants;
new class extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $paginate = 10;
    public $search = '';

    public $paginationOptions = [];

    public function mount()
    {
        $this->paginationOptions = AppConstants::PAGINATIONS;
    }

    #[Computed]
    public function immunizationRecords()
    {
        return ImmunizationRecord::with(['child', 'vaccine', 'facility'])
            ->where(function ($query) {
                $query
                    ->whereHas('child', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhereHas('vaccine', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhereHas('facility', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhere('officer_name', 'like', '%' . $this->search . '%'); // <-- ditambahkan di sini
            })
            ->paginate($this->paginate);
    }

    public function moveToEdit($id)
    {
        $this->redirectRoute('superadmin.immunization-record.edit', ['id' => $id]);
    }

    public function delete($id)
    {
        $immunization_record = ImmunizationRecord::findOrFail($id);
        $this->dispatch('confirm-delete', ['id' => $id, 'title' => 'Delete Facilty', 'text' => "Do you really want to delete {$immunization_record->name} ImmunizationRecord?", 'icon' => 'warning']);
    }

    #[On('confirmed-delete')]
    public function confirmedDelete($id)
    {
        ImmunizationRecord::findOrFail($id)->delete();

        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Deleted!', 'message' => 'Facilty successfully deleted', 'timer' => 2000]);
    }
};
?>

<div>
    <div class="mb-3 d-flex justify-content-between">
        <div class="col-2">
            <select wire:model.live="paginate" class="form-control">
                @foreach ($this->paginationOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6">
            <input type="text" class="form-control" placeholder="Pencarian..." wire:model.live="search">
        </div>
    </div>


    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name Child</th>
                    <th>Name Facility</th>
                    <th>Name Vaccine</th>
                    <th>Date Given</th>
                    <th>Officer Name</th>
                    <th><i class="fas fa-cog"></i></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->immunizationRecords() ?? [] as $item)
                    <tr>
                        <td>{{ $this->immunizationRecords->firstItem() + $loop->index }}</td>
                        <td>{{ $item->child->name }}</td>
                        <td>{{ $item->vaccine->name }}</td>
                        <td>{{ $item->facility->name }}</td>
                        <td>{{ $item->date_given }}</td>
                        <td>{{ $item->officer_name }}</td>

                        <td>
                            <button class="btn btn-sm btn-info mr-1">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button wire:click="moveToEdit({{ $item->id }})" class="btn btn-sm btn-warning mr-1">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="delete({{ $item->id }})" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $this->immunizationRecords()->links() }}
    </div>
</div>

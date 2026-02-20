<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\Vaccine;
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
    public function vaccines()
    {
        return Vaccine::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('description', 'like', '%' . $this->search . '%')
            ->orWhere('type', 'like', '%' . $this->search . '%')
            ->orderBy('min_age_months')
            ->paginate($this->paginate);
    }

    public function moveToEdit($id)
    {
        $this->redirectRoute('superadmin.vaccine.edit', ['id' => $id]);
    }

    public function delete($id)
    {
        $vaccine = Vaccine::findOrFail($id);
        $this->dispatch('confirm-delete', ['id' => $id, 'title' => 'Delete Vaccine', 'text' => "Do you really want to delete {$vaccine->name} vaccine?", 'icon' => 'warning']);
    }

    #[On('confirmed-delete')]
    public function confirmedDelete($id)
    {
        Vaccine::findOrFail($id)->delete();

        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Deleted!', 'message' => 'Vaccine successfully deleted', 'timer' => 2000]);
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
                    <th>Name</th>
                    <th>Description</th>
                    <th>type</th>
                    <th>Min Age Months</th>
                    <th><i class="fas fa-cog"></i></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->vaccines() ?? [] as $item)
                    <tr>
                        <td>{{ $this->vaccines->firstItem() + $loop->index }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->type }}</td>
                        <td>{{ $item->min_age_months }}</td>
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

        {{ $this->vaccines()->links() }}
    </div>
</div>

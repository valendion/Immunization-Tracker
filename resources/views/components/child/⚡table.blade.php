<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\Child;
use Livewire\Attributes\On;
use App\Constants\AppConstants;
use Livewire\Attributes\Lazy;
new #[Lazy] class extends Component {
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
    public function children()
    {
        return Child::where('nik', 'like', '%' . $this->search . '%')
            ->orWhere('name', 'like', '%' . $this->search . '%')
            ->orWhere('gender', 'like', '%' . $this->search . '%')
            ->orWhere('address', 'like', '%' . $this->search . '%')
            ->orWhere('parent_name', 'like', '%' . $this->search . '%')
            ->paginate($this->paginate);
    }

    public function moveToEdit($id)
    {
        $this->redirectRoute('admin.child.edit', ['id' => $id]);
    }

    public function delete($id)
    {
        $child = Child::findOrFail($id);
        $this->dispatch('confirm-delete', ['id' => $id, 'title' => 'Delete Child', 'text' => "Do you really want to delete {$child->name} child?", 'icon' => 'warning']);
    }

    #[On('confirmed-delete')]
    public function confirmedDelete($id)
    {
        Child::findOrFail($id)->delete();

        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Deleted!', 'message' => 'Child successfully deleted', 'timer' => 2000]);
    }
};
?>

@placeholder
    <livewire:loading-general />
@endplaceholder

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
                    <th>Nik</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Date of birth</th>
                    <th>Address</th>
                    <th>Parent Name</th>
                    <th><i class="fas fa-cog"></i></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->children() ?? [] as $item)
                    <tr>
                        <td>{{ $this->children->firstItem() + $loop->index }}</td>
                        <td>{{ $item->nik }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->gender }}</td>
                        <td>{{ $item->date_of_birth }}</td>
                        <td>{{ $item->address }}</td>
                        <td>{{ $item->parent_name }}</td>
                        <td>

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

        {{ $this->children()->links() }}
    </div>
</div>

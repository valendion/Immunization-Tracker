<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\Facility;
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
    public function facilities()
    {
        return Facility::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('address', 'like', '%' . $this->search . '%')
            ->paginate($this->paginate);
    }

    public function moveToEdit($id)
    {
        $this->redirectRoute('admin.facility.edit', ['id' => $id], navigate: true);
    }

    public function delete($id)
    {
        $facility = Facility::findOrFail($id);
        $this->dispatch('confirm-delete', ['id' => $id, 'title' => 'Delete Facilty', 'text' => "Do you really want to delete {$facility->name} facility?", 'icon' => 'warning']);
    }

    #[On('confirmed-delete')]
    public function confirmedDelete($id)
    {
        Facility::findOrFail($id)->delete();

        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Deleted!', 'message' => 'Facilty successfully deleted', 'timer' => 2000]);
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
            <input type="text" class="form-control" placeholder="Search..." wire:model.live="search">
        </div>
    </div>


    <div class="table-responsive">
        <table class="table  table-hover ">
            <thead>
                <tr>

                    <th>No</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th><i class="fas fa-cog"></i></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->facilities ?? [] as $item)
                    <tr>
                        <td>{{ $this->facilities->firstItem() + $loop->index }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->address }}</td>

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

        {{ $this->facilities->links() }}
    </div>
</div>

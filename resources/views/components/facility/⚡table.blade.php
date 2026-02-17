<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\Facility;
use Livewire\Attributes\On;
new class extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $paginate = 10;
    public $search = '';

    #[Computed]
    public function facilities()
    {
        return Facility::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('address', 'like', '%' . $this->search . '%')
            ->paginate($this->paginate);
    }

    public function moveToEdit($id)
    {
        $this->redirectRoute('superadmin.facility.edit', ['id' => $id]);
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

        $this->dispatch('show-alert', ['icon' => 'success', 'title' => 'Deleted!', 'message' => 'Facilty successfully deleted']);
    }
};
?>

<div>
    <div class="mb-3 d-flex justify-content-between">
        <div class="col-2">
            <select class="form-control" wire:model.live="paginate">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
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
                    <th>Address</th>
                    <th><i class="fas fa-cog"></i></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->facilities() ?? [] as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->address }}</td>

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

        {{ $this->facilities()->links() }}
    </div>
</div>

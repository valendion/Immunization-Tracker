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

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPaginate()
    {
        $this->resetPage();
    }

    #[Computed]
    public function children()
    {
        return Child::search($this->search)->paginate($this->paginate);
    }

    public function moveToEdit($id)
    {
        $this->redirectRoute('admin.child.edit', ['id' => $id], navigate: true);
    }

    public function moveToRead($id)
    {
        $this->redirectRoute('admin.child.read', ['id' => $id], navigate: true);
    }

    public function delete($id)
    {
        $child = Child::findOrFail($id);
        $this->dispatch('confirm-delete', [
            'id' => $id,
            'title' => 'Delete Child',
            'text' => "Do you really want to delete {$child->name}?",
            'icon' => 'warning',
        ]);
    }

    #[On('confirmed-delete')]
    public function confirmedDelete($id)
    {
        $child = Child::findOrFail($id);
        $child->delete();

        $total = Child::search($this->search)->count();
        $lastPage = (int) ceil($total / $this->paginate);

        if ($this->getPage() > $lastPage && $lastPage > 0) {
            $this->setPage($lastPage);
        }

        $this->dispatch('show-alert', [
            'icon' => 'success',
            'title' => 'Deleted!',
            'message' => 'Child successfully deleted',
            'timer' => 2000,
        ]);
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

        <div class="col-6 ">
            <input type="text" class="form-control" placeholder="Search..." wire:model.live.debounce.500ms="search">
        </div>
    </div>

    {{-- ✅ Loading overlay untuk table --}}
    <div wire:loading.delay wire:target="search,paginate" class="text-center py-3">
        <livewire:loading-general />
    </div>

    <div wire:loading.remove wire:target="search,paginate" class="table-responsive">
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
                    <th>Contact</th>
                    <th><i class="fas fa-cog"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($this->children ?? [] as $item)
                    <tr wire:key="child-{{ $item->id }}">
                        <td>{{ $this->children->firstItem() + $loop->index }}</td>
                        <td>{{ $item->nik }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->gender }}</td>
                        <td>{{ date('d-m-Y', strtotime($item->date_of_birth)) }}</td>
                        <td>{{ $item->address }}</td>
                        <td>{{ $item->parent_name }}</td>
                        <td>{{ $item->contact }}</td>
                        <td>
                            <button wire:click="moveToRead({{ $item->id }})" class="btn btn-sm btn-info me-1"
                                title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button wire:click="moveToEdit({{ $item->id }})" class="btn btn-sm btn-warning me-1"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="delete({{ $item->id }})" class="btn btn-sm btn-danger"
                                title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-child fa-2x mb-2 d-block"></i>
                            @if ($this->search)
                                No children found for "{{ $this->search }}"
                            @else
                                No children available
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $this->children->links() }}
    </div>
</div>

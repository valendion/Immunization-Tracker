<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\ImmunizationRecord;
use Livewire\Attributes\On;
use App\Constants\AppConstants;
use Livewire\Attributes\Lazy;

new #[Lazy] class extends Component {
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $paginate = 10;
    public $search = '';
    public $paginationOptions = [];

    public function mount(): void
    {
        $this->paginationOptions = AppConstants::PAGINATIONS;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPaginate(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function immunizationRecords()
    {
        return ImmunizationRecord::with(['child', 'vaccine', 'facility'])
            ->searchPaginated($this->search)
            ->paginate($this->paginate);
    }

    public function moveToEdit($id): void
    {
        $this->redirectRoute('admin.immunization-record.edit', ['id' => $id], navigate: true);
    }

    public function delete($id): void
    {
        $record = ImmunizationRecord::with('child')->findOrFail($id);

        $this->dispatch('confirm-delete', [
            'id' => $id,
            'title' => 'Delete Immunization Record',
            'text' => "Do you really want to delete immunization record for {$record->child->name}?",
            'icon' => 'warning',
        ]);
    }

    #[On('confirmed-delete')]
    public function confirmedDelete($id): void
    {
        ImmunizationRecord::findOrFail($id)->delete();

        // ✅ Pagination fix seperti Child component
        $total = ImmunizationRecord::search($this->search)->count();
        $lastPage = (int) ceil($total / $this->paginate);

        if ($this->getPage() > $lastPage && $lastPage > 0) {
            $this->setPage($lastPage);
        }

        $this->dispatch('show-alert', [
            'icon' => 'success',
            'title' => 'Deleted!',
            'message' => 'Immunization record successfully deleted',
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

        <div class="col-6">
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
                    <th>Child Name</th>
                    <th>Vaccine</th>
                    <th>Facility</th>
                    <th>Date Given</th>
                    <th>Officer Name</th>
                    <th><i class="fas fa-cog"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($this->immunizationRecords ?? [] as $item)
                    <tr wire:key="record-{{ $item->id }}">
                        <td>{{ $this->immunizationRecords->firstItem() + $loop->index }}</td>
                        <td>{{ $item->child->name }}</td>
                        <td>{{ $item->vaccine->name }}</td>
                        <td>{{ $item->facility->name }}</td>
                        <td>{{ $item->date_given->format('d-m-Y') }}</td>
                        <td>{{ $item->officer_name }}</td>
                        <td>
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
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-file-medical fa-2x mb-2 d-block"></i>
                            @if ($this->search)
                                No immunization records found for "{{ $this->search }}"
                            @else
                                No immunization records available
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $this->immunizationRecords->links() }}
    </div>
</div>

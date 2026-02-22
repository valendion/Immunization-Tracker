<?php

use Livewire\Component;
use App\Constants\AppConstants;
use App\Models\Facility;
use Livewire\Attributes\Url;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
new #[Title('Edit Facility')] class extends Component {
    public $title = AppConstants::DATA_FACILITY;
    public $sub_title = AppConstants::UPDATE;

    public $name = '';

    public $address = '';

    public $icon = 'building';

    #[Url]
    public $id;

    public Facility $facility;

    public function mount()
    {
        $this->facility = Facility::findOrFail($this->id);

        $this->name = $this->facility->name;
        $this->address = $this->facility->address;
    }

    public function update()
    {
        try {
            $this->validate([
                'name' => 'required|min:3|unique:facilities,name,' . $this->id,
                'address' => 'required|min:5',
            ]);

            $this->facility->update([
                'name' => $this->name,
                'address' => $this->address,
            ]);

            $this->resetValidation();
            $this->reset();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Success!',
                'message' => 'Facility Successfuly Updated',
                'timer' => 2000,
            ]);

            $this->redirect('/superadmin/facility');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Failed!',
                'message' => $e->getMessage(),
                'timer' => 2000,
            ]);
        }
    }
};
?>


<livewire:content-card-page :title="$title" :icon="$icon">
    <div class="card-header">
        <h4>{{ $this->sub_title }}</h4>
    </div>

    <div class="card-body">
        <form wire:submit.prevent="update">

            {{-- NAME --}}
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" wire:model.live.blur="name" class="form-control @error('name') is-invalid @enderror"
                    placeholder="Enter name">

                @error('name')
                    <small class="text-danger mt-1">{{ $message }}</small>
                @enderror
            </div>

            {{-- ADDRESS --}}
            <div class="form-group">
                <label for="address">Address <span class="text-danger">*</span></label>
                <textarea wire:model.live.blur="address" class="form-control @error('address') is-invalid @enderror"
                    placeholder="Enter address"></textarea>

                @error('address')
                    <small class="text-danger mt-1">{{ $message }}</small>
                @enderror
            </div>


            <button type="submit" class="btn btn-warning">
                <i class="fas fa-edit"></i>
                Update</button>

        </form>
    </div>

</livewire:content-card-page>

<?php

use Livewire\Component;
use App\Constants\AppConstants;
use App\Models\Facility;
use Livewire\Attributes\Validate;

new class extends Component {
    public $title = AppConstants::DATA_FACILITY;
    public $sub_title = AppConstants::CREATE;

    #[Validate('required|string|min:3|max:255')]
    public $name = '';

    #[Validate('required|string|min:5|max:255')]
    public $address = '';

    public function create()
    {
        try {
            $this->validate();

            $facility = new Facility();
            $facility->name = $this->name;
            $facility->address = $this->address;

            $facility->save();

            $this->resetValidation();
            $this->reset();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Success!',
                'message' => 'Facility Successfuly Added',
                'timer' => 2000,
            ]);

            $this->redirect('/superadmin/facility');
        } catch (\Exception $e) {
            // ALERT - Error
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

<livewire:content-card-page :title="$title">

    <div class="card-header">
        <h4>{{ $this->sub_title }}</h4>
    </div>

    <div class="card-body">
        <form wire:submit.prevent="create">
            <div class="form-group">
                <label for="name">Name
                    <span class="text-danger">*</span>
                </label>
                <input type="text" wire:model.live.blur="name" class="form-control " id="name"
                    placeholder="Enter name">

                @error('name')
                    <small class="text-danger mt-1">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">Address
                    <span class="text-danger">*</span>
                </label>
                <input type="text" wire:model.live.blur="address" class="form-control " id="description"
                    placeholder="Enter address">

                @error('address')
                    <small class="text-danger mt-1">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i>
                Save</button>

        </form>
    </div>

</livewire:content-card-page>

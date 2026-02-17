<?php

use Livewire\Component;
use App\Constants\AppConstants;
use App\Models\Facility;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Url;
new class extends Component {
    public $title = AppConstants::DATA_VACCINE;
    public $sub_title = AppConstants::UPDATE;

    #[Validate('required|string|min:3|max:255')]
    public $name = '';

    #[Validate('required|string|min:5')]
    public $address = '';

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
            $this->validate();

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

<div>
    <livewire:content-card-page :title="$title">

        <div class="card-header">
            <h4>{{ $this->sub_title }}</h4>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="update">
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



                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-edit"></i>
                    Update</button>

            </form>
        </div>

    </livewire:content-card-page>
</div>

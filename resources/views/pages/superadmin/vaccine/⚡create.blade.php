<?php

use Livewire\Component;
use App\Constants\AppConstants;
use App\Models\Vaccine;
use Livewire\Attributes\Validate;

new class extends Component {
    public $title = AppConstants::DATA_VACCINE;
    public $sub_title = AppConstants::CREATE;

    #[Validate('required|string|min:3|max:255')]
    public $name = '';

    #[Validate('required|string|min:5')]
    public $description = '';

    #[Validate('required|string|max:50')]
    public $type = '';

    #[Validate('required|integer|min:0|max:60')]
    public $min_age_months = 0;

    public function create()
    {
        try {
            $this->validate();

            $vaccine = new Vaccine();
            $vaccine->name = $this->name;
            $vaccine->description = $this->description;
            $vaccine->type = $this->type;
            $vaccine->min_age_months = $this->min_age_months;
            $vaccine->save();

            $this->resetValidation();
            $this->reset();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Success!',
                'message' => 'Vaccine Successfuly Added',
                'timer' => 2000,
            ]);

            $this->redirect('/superadmin/vaccine');
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
                <label for="name">Description
                    <span class="text-danger">*</span>
                </label>
                <input type="text" wire:model.live.blur="description" class="form-control " id="description"
                    placeholder="Masukkan description">

                @error('description')
                    <small class="text-danger mt-1">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">Type
                    <span class="text-danger">*</span>
                </label>
                <input type="text" wire:model.live.blur="type" class="form-control " id="type"
                    placeholder="Enter type">

                @error('type')
                    <small class="text-danger mt-1">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">Min Age Months
                    <span class="text-danger">*</span>
                </label>
                <input type="number" inputmode="numeric" wire:model.live.blur="min_age_months" class="form-control "
                    id="min_age_months" placeholder="Enter min age months">

                @error('min_age_months')
                    <small class="text-danger mt-1">{{ $message }}</small>
                @enderror
            </div>


            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i>
                Save</button>

        </form>
    </div>

</livewire:content-card-page>

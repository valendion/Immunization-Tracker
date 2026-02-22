<?php

use Livewire\Component;
use App\Constants\AppConstants;
use App\Models\Vaccine;
use Livewire\Attributes\Title;
use Illuminate\Validation\ValidationException;
new #[Title('Create Vaccine')] class extends Component {
    public $title = AppConstants::DATA_VACCINE;
    public $sub_title = AppConstants::CREATE;

    public $name = '';

    public $description = '';

    public $type = '';
    public $icon = 'syringe';
    public $min_age_months = 0;

    public function create()
    {
        try {
            $this->validate([
                'name' => 'required|string|min:3|max:255|unique:vaccines,name',
                'description' => 'required|string|min:5',
                'type' => 'required|string|max:50',
                'min_age_months' => 'required|integer|min:0|max:60',
            ]);

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
        } catch (ValidationException $e) {
            throw $e; // biarkan Livewire menangani validasinya
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

<livewire:content-card-page :title="$title" :icon="$icon">

    <div class="card-header">
        <h4>{{ $this->sub_title }}</h4>
    </div>

    <div class="card-body">
        <form wire:submit.prevent="create">

            {{-- NAME --}}
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" wire:model="name" class="form-control" id="name" placeholder="Enter name">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- DESCRIPTION --}}
            <div class="form-group">
                <label for="description">Description <span class="text-danger">*</span></label>
                <input type="text" wire:model="description" class="form-control" id="description"
                    placeholder="Masukkan description">
                @error('description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- TYPE --}}
            <div class="form-group">
                <label for="type">Type <span class="text-danger">*</span></label>
                <input type="text" wire:model="type" class="form-control" id="type" placeholder="Enter type">
                @error('type')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- MIN AGE MONTHS --}}
            <div class="form-group">
                <label for="min_age_months">Min Age Months <span class="text-danger">*</span></label>
                <input type="number" wire:model="min_age_months" class="form-control" id="min_age_months"
                    placeholder="Enter min age months">
                @error('min_age_months')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Save
            </button>

        </form>
    </div>

</livewire:content-card-page>

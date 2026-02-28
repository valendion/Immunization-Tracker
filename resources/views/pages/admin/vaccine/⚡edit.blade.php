<?php

use Livewire\Component;
use App\Constants\AppConstants;
use App\Models\Vaccine;
use Livewire\Attributes\Url;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
new #[Title('Edit Vaccine')] class extends Component {
    public $title = AppConstants::DATA_VACCINE;
    public $sub_title = AppConstants::UPDATE;

    public $name = '';

    public $description = '';

    public $type = '';

    public $min_age_months = 0;
    public $icon = 'syringe';

    #[Url]
    public $id;

    public Vaccine $vaccine;

    public function mount()
    {
        $this->vaccine = Vaccine::findOrFail($this->id);

        $this->name = $this->vaccine->name;
        $this->description = $this->vaccine->description;
        $this->type = $this->vaccine->type;
        $this->min_age_months = $this->vaccine->min_age_months;
    }

    public function update()
    {
        try {
            $this->validate([
                'name' => 'required|string|min:3|max:255|unique:vaccines,name,' . $this->id,
                'description' => 'required|string|min:5',
                'type' => 'required|string|max:50',
                'min_age_months' => 'required|integer|min:0|max:60',
            ]);

            $this->vaccine->update([
                'name' => $this->name,
                'description' => $this->description,
                'type' => $this->type,
                'min_age_months' => $this->min_age_months,
            ]);

            $this->resetValidation();
            $this->reset();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Success!',
                'message' => 'Vaccine Successfuly Updated',
                'timer' => 2000,
            ]);

            $this->redirect('/admin/vaccine');
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


            {{-- SUBMIT --}}
            <button type="submit" class="btn btn-warning" wire:loading.attr="disabled" wire:target="update">
                <span wire:loading.remove wire:target="update">
                    <i class="fas fa-edit mr-1"></i> Update
                </span>
                <span wire:loading wire:target="update">
                    <livewire:loading-general />
                </span>
            </button>

        </form>
    </div>

</livewire:content-card-page>

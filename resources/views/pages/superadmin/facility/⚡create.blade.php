<?php

use Livewire\Component;
use App\Constants\AppConstants;
use App\Models\Facility;

use Illuminate\Validation\ValidationException;
new class extends Component {
    public $title = AppConstants::DATA_FACILITY;
    public $sub_title = AppConstants::CREATE;

    public $name = '';

    public $address = '';

    public function create()
    {
        try {
            $this->validate([
                'name' => 'required|min:3|unique:facilities,name',
                'address' => 'required|min:5',
            ]);

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
        } catch (ValidationException $e) {
            throw $e;
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

            {{-- NAME --}}
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror"
                    placeholder="Enter name">

                @error('name')
                    <small class="text-danger mt-1">{{ $message }}</small>
                @enderror
            </div>

            {{-- ADDRESS --}}
            <div class="form-group">
                <label for="address">Address <span class="text-danger">*</span></label>
                <textarea wire:model="address" class="form-control @error('address') is-invalid @enderror" placeholder="Enter address"></textarea>

                @error('address')
                    <small class="text-danger mt-1">{{ $message }}</small>
                @enderror
            </div>

            {{-- SUBMIT --}}
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Save
            </button>

        </form>
    </div>

</livewire:content-card-page>

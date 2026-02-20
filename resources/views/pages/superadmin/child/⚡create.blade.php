<?php

use Livewire\Component;
use App\Constants\AppConstants;
use App\Models\Child;
use Illuminate\Validation\ValidationException;

new class extends Component {
    public $title = AppConstants::DATA_CHILD;
    public $sub_title = AppConstants::CREATE;

    public $nik = '';

    public $name = '';

    public $gender = '';

    public $date_of_birth = '';

    public $address = '';

    public $parent_name = '';

    public $genderOptions = [];

    public function mount()
    {
        $this->genderOptions = AppConstants::GENDERS;
    }

    public function create()
    {
        try {
            $this->validate([
                'nik' => 'digits:16|unique:children,nik',
                'name' => 'required|string|min:3|max:255',
                'gender' => 'required|in:male,female',
                'date_of_birth' => 'required|date|before_or_equal:today',
                'address' => 'required|string|min:5|max:255',
                'parent_name' => 'required|string|min:3|max:255',
            ]);

            $child = new Child();
            $child->nik = $this->nik;
            $child->name = $this->name;
            $child->gender = $this->gender;
            $child->date_of_birth = $this->date_of_birth;
            $child->address = $this->address;
            $child->parent_name = $this->parent_name;
            $child->save();

            $this->resetValidation();
            $this->reset();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Success!',
                'message' => 'Child Successfuly Added',
                'timer' => 2000,
            ]);

            $this->redirect('/superadmin/child');
        } catch (ValidationException $e) {
            throw $e; // biarkan Livewire menangani validasinya
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


<livewire:content-card-page :title="$title">

    <div class="card-header">
        <h4>{{ $this->sub_title }}</h4>
    </div>

    <div class="card-body">
        <form wire:submit.prevent="create">

            {{-- NIK --}}
            <div class="form-group">
                <label for="nik">NIK</label>
                <input type="number" wire:model="nik" class="form-control @error('nik') is-invalid @enderror"
                    id="nik" placeholder="Enter NIK">
                @error('nik')
                    <small class="text-danger mt-1">{{ $message }}</small>
                @enderror
            </div>

            {{-- NAME --}}
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror"
                    id="name" placeholder="Enter name">
                @error('name')
                    <small class="text-danger mt-1">{{ $message }}</small>
                @enderror
            </div>

            {{-- GENDER --}}
            <div class="form-group">
                <label for="gender">Gender <span class="text-danger">*</span></label>
                <select wire:model="gender" class="form-control @error('gender') is-invalid @enderror">
                    <option disabled value="">-- Select Gender --</option>
                    @foreach ($this->genderOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('gender')
                    <small class="text-danger mt-1">{{ $message }}</small>
                @enderror
            </div>

            {{-- DATE OF BIRTH --}}
            <div class="form-group">
                <label for="date_of_birth">Date of birth <span class="text-danger">*</span></label>
                <input type="date" wire:model="date_of_birth"
                    class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth">
                @error('date_of_birth')
                    <small class="text-danger mt-1">{{ $message }}</small>
                @enderror
            </div>

            {{-- ADDRESS --}}
            <div class="form-group">
                <label for="address">Address <span class="text-danger">*</span></label>
                <textarea wire:model="address" class="form-control @error('address') is-invalid @enderror" id="address"
                    placeholder="Enter address"></textarea>
                @error('address')
                    <small class="text-danger mt-1">{{ $message }}</small>
                @enderror
            </div>

            {{-- PARENT NAME --}}
            <div class="form-group">
                <label for="parent_name">Parent Name <span class="text-danger">*</span></label>
                <input type="text" wire:model="parent_name"
                    class="form-control @error('parent_name') is-invalid @enderror" id="parent_name"
                    placeholder="Enter parent name">
                @error('parent_name')
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

<?php

use Livewire\Component;
use App\Constants\AppConstants;
use App\Models\Child;
use Livewire\Attributes\Url;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
new #[Title('Edit Child')] class extends Component {
    public $title = AppConstants::DATA_VACCINE;
    public $sub_title = AppConstants::UPDATE;
    public $nik = '';

    public $name = '';

    public $gender = '';

    public $date_of_birth = '';

    public $address = '';

    public $parent_name = '';

    public $genderOptions = [];

    public Child $child;

    public $icon = 'users';

    #[Url]
    public $id;

    public function mount()
    {
        $this->genderOptions = AppConstants::GENDERS;

        $this->child = Child::findOrFail($this->id);

        $this->nik = $this->child->nik;
        $this->name = $this->child->name;
        $this->gender = $this->child->gender;
        $this->address = $this->child->address;
        $this->date_of_birth = $this->child->date_of_birth->toDateString();
        $this->parent_name = $this->child->parent_name;
    }

    public function update()
    {
        try {
            $this->validate([
                'nik' => 'digits:16|unique:children,nik,' . $this->id,
                'name' => 'required|string|min:3|max:255',
                'gender' => 'required|in:male,female',
                'date_of_birth' => 'required|date|before_or_equal:today',
                'address' => 'required|string|min:5|max:255',
                'parent_name' => 'required|string|min:3|max:255',
            ]);

            $this->child->update([
                'nik' => $this->nik,
                'name' => $this->name,
                'gender' => $this->gender,
                'date_of_birth' => $this->date_of_birth,
                'address' => $this->address,
                'parent_name' => $this->parent_name,
            ]);

            $this->resetValidation();
            $this->reset();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Success!',
                'message' => 'Child Successfuly Updated',
                'timer' => 2000,
            ]);

            $this->redirect('/admin/child');
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

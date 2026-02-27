<?php

use Livewire\Component;
use App\Constants\AppConstants;
use App\Models\ImmunizationRecord;
use App\Models\Facility;
use App\Models\Child;
use App\Models\Vaccine;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
new #[Title('Create Immunization Record')] class extends Component {
    public $title = AppConstants::DATA_IMMUNIZATION_RECORD;
    public $sub_title = AppConstants::CREATE;

    public $child_id = '';
    public $vaccine_id = '';
    public $date_given = '';
    public $health_facility_id = '';
    public $officer_name = '';

    // DATA LOAD AWAL
    public $children = [];
    public $vaccines = [];
    public $facilities = [];
    public $icon = 'file';
    // MOUNT (LOAD DATA PERTAMA KALI)
    public function mount()
    {
        $this->children = Child::select('id', 'name')->get();
        $this->vaccines = Vaccine::select('id', 'name')->get();
        $this->facilities = Facility::select('id', 'name')->get();
    }

    public function create()
    {
        try {
            $this->validate(
                [
                    'child_id' => 'required|exists:children,id',

                    // CEGAH CHILD AMBIL VAKSIN YANG SAMA
                    'vaccine_id' => ['required', Rule::unique('immunization_records')->where('child_id', $this->child_id)],

                    'date_given' => 'required|date',
                    'health_facility_id' => 'required|exists:facilities,id',
                    'officer_name' => 'required|min:3',
                ],
                ['child_id.required' => 'Child field is required.', 'child_id.exists' => 'Selected child is invalid.', 'vaccine_id.required' => 'Vaccine field is required.', 'vaccine_id.unique' => 'This child has already received this vaccine.', 'vaccine_id.exists' => 'Selected vaccine is invalid.', 'health_facility_id.required' => 'Health Facility field is required.', 'health_facility_id.exists' => 'Selected facility is invalid.', 'date_given.required' => 'Date Given field is required.', 'date_given.date' => 'Date Given must be a valid date.', 'officer_name.required' => 'Officer Name field is required.', 'officer_name.min' => 'Officer Name must be at least 3 characters.'],
            );

            $record = new ImmunizationRecord();
            $record->child_id = $this->child_id;
            $record->vaccine_id = $this->vaccine_id;
            $record->date_given = $this->date_given;
            $record->health_facility_id = $this->health_facility_id;
            $record->officer_name = $this->officer_name;
            $record->save();

            // RESET
            $this->resetValidation();
            $this->reset();

            // SUCCESS ALERT
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Success!',
                'message' => 'Immunization record successfully added',
                'timer' => 2000,
            ]);

            return $this->redirect('/admin/immunization-record');
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
        <form wire:submit.prevent="create">

            {{-- CHILD --}}
            <div class="form-group">
                <label>Child <span class="text-danger">*</span></label>

                {{-- Bungkus select dengan wire:ignore --}}
                <div wire:ignore>
                    <select id="select_child" data-wire-model="child_id"
                        class="select2bs4 form-control @error('child_id') is-invalid @enderror">
                        <option value="">-- Select Child --</option>
                        @foreach ($children as $child)
                            <option value="{{ $child->id }}">{{ $child->name }}</option>
                        @endforeach
                    </select>
                </div>

                @error('child_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>


            {{-- CHILD --}}
            <div class="form-group">
                <label>Vaccine <span class="text-danger">*</span></label>

                {{-- Bungkus select dengan wire:ignore --}}
                <div wire:ignore>
                    <select id="select_vaccine" data-wire-model="vaccine_id"
                        class="select2bs4 form-control @error('vaccine_id') is-invalid @enderror">
                        <option value="">-- Select Vaccine --</option>
                        @foreach ($vaccines as $vaccine)
                            <option value="{{ $vaccine->id }}">{{ $vaccine->name }}</option>
                        @endforeach
                    </select>
                </div>

                @error('vaccine_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>



            {{-- DATE GIVEN --}}
            <div class="form-group">
                <label>Date Given <span class="text-danger">*</span></label>
                <input type="date" wire:model="date_given"
                    class="form-control @error('date_given') is-invalid @enderror">
                @error('date_given')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- CHILD --}}
            <div class="form-group">
                <label>Health Facility <span class="text-danger">*</span></label>

                {{-- Bungkus select dengan wire:ignore --}}
                <div wire:ignore>
                    <select id="select_facility" data-wire-model="health_facility_id"
                        class="select2bs4 form-control @error('health_facility_id') is-invalid @enderror">
                        <option value="">-- Select Facility --</option>
                        @foreach ($facilities as $facility)
                            <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                        @endforeach
                    </select>
                </div>

                @error('health_facility_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>


            {{-- OFFICER NAME --}}
            <div class="form-group">
                <label>Officer Name <span class="text-danger">*</span></label>
                <input type="text" wire:model="officer_name"
                    class="form-control @error('officer_name') is-invalid @enderror" placeholder="Enter officer name">
                @error('officer_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- SUBMIT --}}
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Save
            </button>

        </form>
    </div>

</livewire:content-card-page>

@script
    <script>
        function initSelect2() {
            $('.select2bs4').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }
            });

            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            // KUNCI: gunakan event select2:select dan select2:unselect
            $('.select2bs4').off('select2:select select2:unselect').on('select2:select select2:unselect', function() {
                const modelName = $(this).data('wire-model');
                const val = $(this).val();
                console.log('Model:', modelName, '| Value:', val); // debug
                if (modelName) {
                    $wire.set(modelName, val);
                }
            });
        }

        setTimeout(() => {
            initSelect2();
        }, 200);
    </script>
@endscript

<?php

use Livewire\Component;
use App\Models\Child;
use App\Models\ImmunizationRecord;
use App\Models\Vaccine;
use Livewire\Attributes\Title;

new #[Title('Child Details')] class extends Component {
    public $childId;
    public $child = null;
    public $vaccineStatus = [];

    public function mount($id)
    {
        $this->childId = $id;
        $this->loadChildData();
    }

    public function loadChildData()
    {
        $this->child = Child::find($this->childId);

        if ($this->child) {
            $this->loadVaccineStatus();
        }
    }

    public function loadVaccineStatus()
    {
        // Ambil semua vaksin
        $allVaccines = Vaccine::select('id', 'name', 'type', 'description')->get();

        // Ambil record imunisasi anak ini
        $childRecords = ImmunizationRecord::where('child_id', $this->childId)
            ->with(['vaccine', 'facility'])
            ->get()
            ->keyBy('vaccine_id');

        // Build status untuk setiap vaksin
        $this->vaccineStatus = $allVaccines
            ->map(function ($vaccine) use ($childRecords) {
                $record = $childRecords->get($vaccine->id);

                return [
                    'vaccine_id' => $vaccine->id,
                    'vaccine_name' => $vaccine->name,
                    'vaccine_type' => $vaccine->type,
                    'vaccine_description' => $vaccine->description,
                    'is_given' => $record ? true : false,
                    'date_given' => $record ? $record->date_given->format('d/m/Y') : null,
                    'facility_name' => $record ? $record->facility->name : null,
                    'officer_name' => $record ? $record->officer_name : null,
                    'record_id' => $record ? $record->id : null,
                ];
            })
            ->toArray();
    }
};
?>

<livewire:content-card-page title="Child Details" icon="user">

    @if ($child)
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ $child->name }}</h4>

            </div>
        </div>

        <div class="card-body">
            <!-- Child Information Card -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card border-info">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-user mr-2"></i>Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td width="30%"><strong>NIK</strong></td>
                                            <td>: {{ $child->nik }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Name</strong></td>
                                            <td>: {{ $child->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Gender</strong></td>
                                            <td>: {{ $child->gender }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date of Birth</strong></td>
                                            <td>: {{ date('d-m-Y', strtotime($child->date_of_birth)) }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td width="30%"><strong>Parent Name</strong></td>
                                            <td>: {{ $child->parent_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Address</strong></td>
                                            <td>: {{ $child->address }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Contact</strong></td>
                                            <td>: {{ $child->contact ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vaccine Status Card -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-syringe mr-2"></i>Immunization Status</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th width="25%">Vaccine Name</th>
                                            <th width="15%">Type</th>
                                            <th width="12%" class="text-center">Status</th>
                                            <th width="15%">Date Given</th>
                                            <th width="18%">Facility</th>
                                            <th width="15%">Officer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($vaccineStatus as $index => $vaccine)
                                            <tr class="{{ $vaccine['is_given'] ? 'table-success' : '' }}">
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $vaccine['vaccine_name'] }}</strong>
                                                    @if ($vaccine['vaccine_description'])
                                                        <br><small
                                                            class="text-muted">{{ Str::limit($vaccine['vaccine_description'], 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $vaccine['vaccine_type'] }}</td>
                                                <td class="text-center">
                                                    @if ($vaccine['is_given'])
                                                        <span class="badge badge-success px-3 py-2">
                                                            <i class="fas fa-check mr-1"></i> Given
                                                        </span>
                                                    @else
                                                        <span class="badge badge-warning px-3 py-2">
                                                            <i class="fas fa-clock mr-1"></i> Pending
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ $vaccine['date_given'] ?? '-' }}</td>
                                                <td>{{ $vaccine['facility_name'] ?? '-' }}</td>
                                                <td>{{ $vaccine['officer_name'] ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                                        <p>No vaccine data available</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        Total Vaccines: <strong>{{ count($vaccineStatus) }}</strong> |
                                        Given: <strong
                                            class="text-success">{{ collect($vaccineStatus)->where('is_given', true)->count() }}</strong>
                                        |
                                        Pending: <strong
                                            class="text-warning">{{ collect($vaccineStatus)->where('is_given', false)->count() }}</strong>
                                    </small>
                                </div>
                                <div class="col-md-6 text-right">
                                    <small class="text-muted">
                                        <span class="badge badge-success mr-1">&nbsp;</span> Given
                                        <span class="badge badge-warning ml-2 mr-1">&nbsp;</span> Pending
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card-body">
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Child not found.
            </div>
            <button wire:click="goBack" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to List
            </button>
        </div>
    @endif

</livewire:content-card-page>

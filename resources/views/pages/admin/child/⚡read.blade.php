<?php

use Livewire\Component;
use App\Models\Child;
use App\Models\ImmunizationRecord;
use App\Models\Vaccine;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Cache;

new #[Title('Child Details')] class extends Component {
    public $childId;
    public $child = null;
    public $vaccineStatus = [];
    public $stats = [];

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
        $vaccines = Cache::remember('vaccines_list', 3600, function () {
            return Vaccine::all(['id', 'name', 'type', 'description']);
        });

        $records = ImmunizationRecord::where('child_id', $this->childId)->get()->keyBy('vaccine_id');

        $this->vaccineStatus = $vaccines
            ->map(function ($v) use ($records) {
                $record = $records->get($v->id);

                return [
                    'vaccine_id' => $v->id,
                    'vaccine_name' => $v->name,
                    'vaccine_type' => $v->type,
                    'vaccine_description' => $v->description,
                    'is_given' => $record ? true : false,
                    'date_given' => $record && $record->date_given ? $record->date_given->format('d/m/Y') : null,
                    'officer_name' => $record ? $record->officer_name : null,
                    'record_id' => $record ? $record->id : null,
                ];
            })
            ->toArray();

        $given = collect($this->vaccineStatus)->where('is_given', true)->count();

        $this->stats = [
            'total' => count($this->vaccineStatus),
            'given' => $given,
            'pending' => count($this->vaccineStatus) - $given,
        ];
    }

    public function goBack()
    {
        return redirect()->route('children.index');
    }
};
?>

<livewire:content-card-page title="Child Details" icon="user">

    @if ($child)
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">{{ $child->name }}</h4>
        </div>

        <div class="card-body">
            <!-- Personal Info -->
            <div class="card border-info mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-user mr-2"></i>Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td width="35%">NIK</td>
                                    <td>: {{ $child->nik }}</td>
                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <td>: {{ $child->name }}</td>
                                </tr>
                                <tr>
                                    <td>Gender</td>
                                    <td>: {{ $child->gender }}</td>
                                </tr>
                                <tr>
                                    <td>Birth Date</td>
                                    <td>: {{ $child->date_of_birth?->format('d-m-Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td width="35%">Parent</td>
                                    <td>: {{ $child->parent_name }}</td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>: {{ $child->address }}</td>
                                </tr>
                                <tr>
                                    <td>Contact</td>
                                    <td>: {{ $child->contact ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vaccine Status -->
            <div class="card border-primary">
                <div class="card-header bg-primary text-white d-flex justify-content-between">
                    <h5 class="mb-0"><i class="fas fa-syringe mr-2"></i>Immunization Status</h5>
                    <span class="badge badge-light text-primary">{{ $stats['given'] }}/{{ $stats['total'] }}</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" width="5%">#</th>
                                <th width="30%">Vaccine</th>
                                <th width="15%">Type</th>
                                <th class="text-center" width="12%">Status</th>
                                <th width="18%">Date</th>
                                <th width="20%">Officer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vaccineStatus as $index => $v)
                                <tr class="{{ $v['is_given'] ? 'table-success' : '' }}">
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $v['vaccine_name'] }}</strong>
                                        @if ($v['vaccine_description'])
                                            <br><small
                                                class="text-muted">{{ Str::limit($v['vaccine_description'], 40) }}</small>
                                        @endif
                                    </td>
                                    <td><span class="badge badge-info">{{ $v['vaccine_type'] }}</span></td>
                                    <td class="text-center">
                                        @if ($v['is_given'])
                                            <span class="badge badge-success"><i
                                                    class="fas fa-check mr-1"></i>Given</span>
                                        @else
                                            <span class="badge badge-warning"><i
                                                    class="fas fa-clock mr-1"></i>Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $v['date_given'] ?? '-' }}</td>
                                    <td>{{ $v['officer_name'] ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2"></i><br>No vaccine data
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                Total: <strong>{{ $stats['total'] }}</strong> |
                                Given: <strong class="text-success">{{ $stats['given'] }}</strong> |
                                Pending: <strong class="text-warning">{{ $stats['pending'] }}</strong>
                            </small>
                        </div>
                        <div class="col-md-6 text-right">
                            <small class="text-muted">
                                <span class="badge badge-success mr-1">&nbsp;</span>Given
                                <span class="badge badge-warning ml-2 mr-1">&nbsp;</span>Pending
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            @if ($stats['total'] > 0)
                @php $pct = ($stats['given'] / $stats['total']) * 100; @endphp
                <div class="progress mt-3" style="height: 25px;">
                    <div class="progress-bar bg-success" style="width: {{ $pct }}%">
                        {{ round($pct) }}%
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="card-body text-center py-5">
            <div class="alert alert-danger d-inline-block">
                <i class="fas fa-exclamation-triangle mr-2"></i>Child not found.
            </div>
            <div class="mt-3">
                <button wire:click="goBack" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i>Back to List
                </button>
            </div>
        </div>
    @endif

</livewire:content-card-page>

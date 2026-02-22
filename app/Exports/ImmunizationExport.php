<?php
namespace App\Exports;

use App\Models\ImmunizationRecord;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ImmunizationExport implements FromArray, WithHeadings
{
    protected $facility_id;
    protected $date;
    protected $search;

    public function __construct($facility_id, $date, $search)
    {
        $this->facility_id = $facility_id;
        $this->date        = $date;
        $this->search      = $search;
    }

    public function headings(): array
    {
        return [
            'No',
            'Child Name',
            'Facility Name',
            'Vaccines',
            'Date Given',
            'Officer Name',
        ];
    }

    public function array(): array
    {
        // Ambil child_id sesuai filter table Livewire
        $childIds = ImmunizationRecord::query()
            ->select('child_id')
            ->where('health_facility_id', $this->facility_id)
            ->whereDate('date_given', $this->date)
            ->when($this->search, function ($query) {
                $query->whereHas('child', function ($q) {
                    $q->where('name', 'like', "%{$this->search}%");
                });
            })
            ->groupBy('child_id')
            ->pluck('child_id');

        // Ambil data lengkap sesuai child_id
        $records = ImmunizationRecord::with(['child', 'vaccine', 'facility'])
            ->whereIn('child_id', $childIds)
            ->where('health_facility_id', $this->facility_id)
            ->whereDate('date_given', $this->date)
            ->get()
            ->groupBy('child_id');

        // Format final 1 anak = 1 baris
        $final = [];
        $no    = 1;

        foreach ($records as $items) {
            $final[] = [
                $no++,
                $items->first()->child->name,
                $items->first()->facility->name,
                implode(', ', $items->pluck('vaccine.name')->toArray()),
                $items->first()->date_given,
                $items->first()->officer_name,
            ];
        }

        return $final;
    }
}

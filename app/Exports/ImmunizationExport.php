<?php
namespace App\Exports;

use App\Models\ImmunizationRecord;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ImmunizationExport implements FromArray, WithHeadings
{
    protected $facility_id;
    protected $date;
    protected $search;
    protected $cacheKey;
    protected $cacheDuration = 300; // 5 menit

    public function __construct($facility_id, $date, $search)
    {
        $this->facility_id = $facility_id;
        $this->date        = $date;
        $this->search      = $search;
        $this->cacheKey    = $this->generateCacheKey();
    }

    protected function generateCacheKey(): string
    {
        return sprintf(
            'immunization_export:%s:%s:%s',
            $this->facility_id,
            $this->date,
            md5($this->search ?? '')
        );
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
        return Cache::remember($this->cacheKey, $this->cacheDuration, function () {
            return $this->getData();
        });
    }

    protected function getData(): array
    {
        // Gunakan Eloquent dengan specific columns untuk mengurangi memory
        $records = ImmunizationRecord::query()
            ->with([
                'child:id,name',
                'vaccine:id,name',
                'facility:id,name', // atau nama relasi yang benar
            ])
            ->select([
                'id',
                'child_id',
                'vaccine_id',
                'health_facility_id',
                'date_given',
                'officer_name',
            ])
            ->where('health_facility_id', $this->facility_id)
            ->whereDate('date_given', $this->date)
            ->when($this->search, function ($query) {
                $query->whereHas('child', function ($q) {
                    $q->where('name', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('child_id')
            ->get();

        // Group by child_id untuk menggabungkan vaksin
        $grouped = $records->groupBy('child_id');

        $final = [];
        $no    = 1;

        foreach ($grouped as $items) {
            $first = $items->first();

            // Hindari error jika relasi null
            if (! $first->child) {
                continue;
            }

            $final[] = [
                $no++,
                $first->child->name ?? 'N/A',
                $first->facility->name ?? 'N/A',
                $items->pluck('vaccine.name')
                    ->filter()
                    ->unique()
                    ->implode(', '),
                date('d-m-Y', strtotime($first->date_given)),
                $first->officer_name,
            ];
        }

        return $final;
    }

    public function clearCache(): void
    {
        Cache::forget($this->cacheKey);
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class ImmunizationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'vaccine_id',
        'date_given',
        'health_facility_id',
        'officer_name',
    ];

    protected $casts = [
        'date_given' => 'date',
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class, 'child_id');
    }

    public function vaccine(): BelongsTo
    {
        return $this->belongsTo(Vaccine::class, 'vaccine_id');
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'health_facility_id');
    }

    public function scopeSearch($query, ?string $search)
    {
        if (empty($search)) {
            return $query;
        }

        $searchTerm = $search . '%';
        $ftsTerm    = $this->escapeFtsBoolean($search);

        // FTS untuk children
        $childIds = DB::table('children')
            ->select('id')
            ->whereRaw("MATCH(nik, name, address, parent_name, contact) AGAINST(? IN BOOLEAN MODE)", [$ftsTerm])
            ->pluck('id');

        // Index untuk vaccines
        $vaccineIds = DB::table('vaccines')
            ->where('name', 'like', $searchTerm)
            ->pluck('id');

        // Index untuk facilities
        $facilityIds = DB::table('facilities')
            ->where('name', 'like', $searchTerm)
            ->pluck('id');

        return $query->where(function ($q) use ($childIds, $vaccineIds, $facilityIds, $search, $searchTerm) {
            if ($childIds->isNotEmpty()) {
                $q->orWhereIn('child_id', $childIds);
            }
            if ($vaccineIds->isNotEmpty()) {
                $q->orWhereIn('vaccine_id', $vaccineIds);
            }
            if ($facilityIds->isNotEmpty()) {
                $q->orWhereIn('health_facility_id', $facilityIds);
            }
            $q->orWhere('officer_name', 'like', $searchTerm)
                ->orWhere('officer_name', 'like', '%' . $search . '%');
        });
    }

    /**
     * Scope untuk paginated results dengan ordering
     */
    public function scopeSearchPaginated($query, ?string $search)
    {
        return $query->search($search)->latest('date_given');
    }

    private function escapeFtsBoolean(string $search): string
    {
        $words   = array_filter(explode(' ', trim($search)));
        $escaped = array_map(function ($word) {
            $clean = preg_replace('/[+\-><\(\)~*\"@]+/', '', $word);
            return strlen($clean) >= 2 ? '+' . $clean : '';
        }, $words);

        return implode(' ', array_filter($escaped));
    }
}

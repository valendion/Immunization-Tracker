<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

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
    public function getDateGivenAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }
}

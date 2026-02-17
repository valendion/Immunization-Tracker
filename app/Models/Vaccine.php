<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'min_age_months',
    ];

    public function immunizationRecords()
    {
        return $this->hasMany(ImmunizationRecord::class);
    }

}

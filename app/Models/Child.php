<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'name',
        'gender',
        'date_of_birth',
        'address',
        'parent_name',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    public function immunizationRecords()
    {
        return $this->hasMany(ImmunizationRecord::class);
    }

    public function getDateOfBirthAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }
}

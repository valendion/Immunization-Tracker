<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'contact',
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

    public function scopeSearch($query, $term)
    {
        if (empty($term)) {
            return $query;
        }

        return $query->whereFullText(
            ['nik', 'name', 'address', 'parent_name', 'contact'],
            $term
        );
    }
}

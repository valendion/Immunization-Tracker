<?php
namespace Database\Seeders;

use App\Models\ImmunizationRecord;
use Illuminate\Database\Seeder;

class ImmunizationRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ImmunizationRecord::factory(100)->create();
    }
}

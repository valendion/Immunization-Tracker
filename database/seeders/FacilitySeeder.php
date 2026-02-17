<?php
namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lagaligo 1–12
        for ($i = 1; $i <= 12; $i++) {
            Facility::create([
                'name'    => "Lagaligo $i",
                'address' => "Alamat Lagaligo $i",
            ]);
        }

        // Mattirodeceng 1–7
        for ($i = 1; $i <= 7; $i++) {
            Facility::create([
                'name'    => "Mattirodeceng $i",
                'address' => "Alamat Mattirodeceng $i",
            ]);
        }

        // Sumber Kasih 1–5
        for ($i = 1; $i <= 5; $i++) {
            Facility::create([
                'name'    => "Sumber Kasih $i",
                'address' => "Alamat Sumber Kasih $i",
            ]);
        }
    }
}

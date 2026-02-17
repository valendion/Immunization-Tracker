<?php
namespace Database\Seeders;

use App\Models\Vaccine;
use Illuminate\Database\Seeder;

class VaccineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vaccines = [
            ['BSG', 'Vaksin untuk mencegah tuberkulosis (TBC), diberikan pada bayi baru lahir.', 0],
            ['HBO', 'Vaksin Hepatitis B dosis awal yang diberikan dalam 24 jam setelah lahir.', 0],

            ['Polio 1', 'Dosis pertama vaksin polio untuk mencegah poliomyelitis.', 2],
            ['Polio 2', 'Dosis kedua vaksin polio.', 3],
            ['Polio 3', 'Dosis ketiga vaksin polio.', 4],
            ['Polio 4', 'Dosis penguatan vaksin polio.', 5],

            ['DPT 1', 'Dosis pertama vaksin untuk mencegah difteri, pertusis, dan tetanus.', 2],
            ['DPT 2', 'Dosis kedua vaksin DPT.', 3],
            ['DPT 3', 'Dosis ketiga vaksin DPT.', 4],
            ['DPT 4', 'Dosis lanjutan vaksin DPT (booster).', 18],

            ['PCV 1', 'Dosis pertama vaksin Pneumokokus untuk mencegah radang paru dan meningitis.', 2],
            ['PCV 2', 'Dosis kedua vaksin Pneumokokus.', 3],
            ['PCV 3', 'Dosis ketiga vaksin Pneumokokus.', 12],

            ['Rotavirus 1', 'Dosis pertama vaksin Rotavirus untuk mencegah diare berat.', 2],
            ['Rotavirus 2', 'Dosis kedua vaksin Rotavirus.', 3],
            ['Rotavirus 3', 'Dosis ketiga vaksin Rotavirus.', 4],

            ['Measles-Rubella 1', 'Dosis pertama vaksin campak dan rubella.', 9],
            ['Measles-Rubella 2', 'Dosis kedua vaksin campak dan rubella.', 18],
        ];

        foreach ($vaccines as [$name, $description, $minAge]) {
            Vaccine::create([
                'name'           => $name,
                'description'    => $description,
                'type'           => $name,
                'min_age_months' => $minAge,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}

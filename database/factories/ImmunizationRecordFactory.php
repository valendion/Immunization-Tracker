<?php
namespace Database\Factories;

use App\Models\Child;
use App\Models\Facility;
use App\Models\Vaccine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ImmunizationRecord>
 */
class ImmunizationRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'child_id'           => Child::inRandomOrder()->first()->id ?? Child::factory(),
            'vaccine_id'         => Vaccine::inRandomOrder()->first()->id ?? Vaccine::factory(),
            'health_facility_id' => Facility::inRandomOrder()->first()->id ?? Facility::factory(),
            'date_given'         => fake()->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'officer_name'       => fake()->name(),
        ];
    }
}

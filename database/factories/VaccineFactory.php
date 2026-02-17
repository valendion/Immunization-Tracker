<?php

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vaccine>
 */
class VaccineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'           => fake()->word(),
            'description'    => fake()->sentence(),
            'type'           => fake()->word(),
            'min_age_months' => fake()->numberBetween(0, 24),
        ];
    }
}

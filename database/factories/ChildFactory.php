<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Child>
 */
class ChildFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nik'           => fake()->unique()->numerify('################'),
            'name'          => fake()->name(),
            'gender'        => fake()->randomElement(['male', 'female']),
            'date_of_birth' => fake()->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'address'       => fake()->address(),
            'parent_name'   => fake()->name(),
            'contact'       => fake()->phoneNumber(),
        ];
    }
}

<?php

namespace Database\Factories;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bus>
 */
class BusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'operator_id' => User::factory()->state(['role' => 'operator']),
            'bus_number' => strtoupper($this->faker->bothify('BUS-####')),
            'bus_type' => $this->faker->randomElement(['Mini', 'Coach', 'Luxury']),
            'capacity' => $this->faker->numberBetween(20, 60),
            'status' => 'active',
        ];
    }
}

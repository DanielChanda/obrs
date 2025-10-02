<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Route>
 */
class RouteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'origin' => $this->faker->city(),
            'destination' => $this->faker->city(),
            'operator_id' => User::factory()->state(['role' => 'operator']),
            'distance' => $this->faker->numberBetween(50, 1000),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\Route;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $departure = $this->faker->dateTimeBetween('+1 day', '+1 week');
        return [
            'bus_id' => Bus::factory(),
            'route_id' => Route::factory(),
            'departure_time' => $departure,
            'arrival_time' => (clone $departure)->modify('+'.rand(2,12).' hours'),
            'fare' => $this->faker->randomFloat(2, 50, 500),
            'available_seats' => $this->faker->numberBetween(10, 60),
            'status' => 'scheduled',
        ];
    }
}

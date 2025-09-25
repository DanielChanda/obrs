<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'user_id' => User::factory()->state(['role' => 'passenger']),
            'schedule_id' => Schedule::factory(),
            'seat_number' => $this->faker->numberBetween(1, 60),
            'status' => $this->faker->randomElement(['pending', 'confirmed']),
            'payment_status' => 'unpaid',
        ];
    }
}

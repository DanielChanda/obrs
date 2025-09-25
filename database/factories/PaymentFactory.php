<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'booking_id' => Booking::factory(),
            'amount' => $this->faker->randomFloat(2, 50, 500),
            'method' => $this->faker->randomElement(['credit_card', 'mobile_money', 'paypal']),
            'status' => $this->faker->randomElement(['successful', 'failed']),
            'transaction_id' => strtoupper($this->faker->bothify('TXN-####-????')),
        ];
    }
}

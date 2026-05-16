<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'status' => $this->faker->randomElement([
                Appointment::STATUS_REQUESTED,
                Appointment::STATUS_CONFIRMED,
            ]),
            'notification_type' => $this->faker->randomElement(['email', 'sms']),
            'description' => $this->faker->sentence(10),
        ];
    }
}

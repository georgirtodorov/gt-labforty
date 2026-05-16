<?php
declare(strict_types=1);

namespace Database\Factories;

use App\Models\TimeSlot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TimeSlot>
 */
class TimeSlotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start_at' => now(),
            'end_at' => function (array $attributes) {
                return \Carbon\Carbon::parse($attributes['start_at'])->addMinutes(60);
            },
            'appointment_id' => null,
        ];
    }
}

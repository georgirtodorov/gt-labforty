<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    private const string SLOTS_START = '05:00';
    private const string SLOTS_END = '14:00';
    private const int SLOTS_STEP = 30;
    private const int SLOTS_MONTHS = 1;
    private const int CLIENTS_COUNT = 30;
    private const int APPOINTMENTS_COUNT = 50;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $startDate = now()->format('Y-m-d');
        $endDate = now()->addMonths(self::SLOTS_MONTHS)->format('Y-m-d');

        $this->command->call('app:generate-time-slots', [
            '--start-date' => $startDate,
            '--end-date' => $endDate,
            '--start' => self::SLOTS_START,
            '--end' => self::SLOTS_END,
            '--interval' => self::SLOTS_STEP
        ]);

        $clients = Models\Client::factory(self::CLIENTS_COUNT)->create();
        $availableSlots = Models\TimeSlot::available()->get()->shuffle();

        for ($i = 0; $i < self::APPOINTMENTS_COUNT; $i++) {
            $slot = $availableSlots->pop();

            if (!$slot) break;

            $appointment = Models\Appointment::factory()->create([
                'client_id' => $clients->random()->id,
                'status' => fake()->randomElement([
                    Models\Appointment::STATUS_REQUESTED,
                    Models\Appointment::STATUS_CONFIRMED
                ]),
            ]);

            $slot->assignTo($appointment);
        }

        $this->command->info('Database seeding completed successfully!');
    }
}

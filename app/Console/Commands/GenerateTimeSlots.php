<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:generate-time-slots {--start-date=} {--end-date=} {--start=05:00} {--end=14:00} {--interval=30}')]
#[Description('Generate UTC time slots sequentially by interval between specific start and end dates')]
class GenerateTimeSlots extends Command
{
    public function handle(): int
    {
        $startDateOption = $this->option('start-date') ?? now()->format('Y-m-d');
        $endDateOption = $this->option('end-date') ?? now()->addMonth()->format('Y-m-d');

        $startTime = $this->option('start');
        $endTime = $this->option('end');
        $interval = (int)$this->option('interval');
        if ($interval <= 0) {
            $interval = 30;
        }

        try {
            $startDay = Carbon::parse($startDateOption)->startOfDay();
            $endDay = Carbon::parse($endDateOption)->endOfDay();

            if ($startDay > $endDay) {
                $this->error('The start-date cannot be after the end-date.');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('Invalid date format provided. Please use Y-m-d.');
            return 1;
        }

        $this->info("Generating UTC schedule from {$startDay->format('Y-m-d')} to {$endDay->format('Y-m-d')}");
        $this->info("Working hours: {$startTime} - {$endTime}Z with interval {$interval} min.");

        try {
            while ($startDay <= $endDay) {
                if (!$startDay->isWeekend()) {
                    $currentSlot = Carbon::createFromFormat('Y-m-d H:i', $startDay->format('Y-m-d') . " {$startTime}", 'UTC');
                    $closingTime = Carbon::createFromFormat('Y-m-d H:i', $startDay->format('Y-m-d') . " {$endTime}", 'UTC');

                    while ($currentSlot < $closingTime) {
                        $slotStart = $currentSlot->copy();
                        $slotEnd = $currentSlot->copy()->addMinutes($interval);

                        TimeSlot::firstOrCreate([
                            'start_at' => $slotStart->toDateTimeString(),
                            'end_at' => $slotEnd->toDateTimeString(),
                        ]);

                        $currentSlot->addMinutes($interval);
                    }
                }
                $startDay->addDay();
            }
        } catch (\Exception $e) {
            $this->error('Database error: ' . $e->getMessage());
            return 1;
        }

        $this->info("Slots generated successfully.");
        return 0;
    }
}

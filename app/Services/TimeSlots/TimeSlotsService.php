<?php
declare(strict_types=1);

namespace App\Services\TimeSlots;

use App\Exceptions\AppointmentsException;
use App\Models;

class TimeSlotsService
{
    public function getAvailableByDate(string $date)
    {
        return Models\TimeSlot::available()
            ->whereDate('start_at', $date)
            ->when($date === now()->toDateString(), function ($query) {
                $query->where('start_at', '>', now()->toDateTimeString());
            })
            ->orderBy('start_at', 'asc')
            ->get(['id', 'start_at', 'end_at']);
    }

    /**
     * @throws AppointmentsException
     */
    public function getSlotByDateRange(string $startAt, string $endAt): Models\TimeSlot
    {
        $slot = Models\TimeSlot::where('start_at', $startAt)
            ->where('end_at', $endAt)
            ->first();

        if (!$slot) {
            throw AppointmentsException::slotWithDatesDoNotExist($startAt, $endAt);
        }
        return $slot;
    }
}

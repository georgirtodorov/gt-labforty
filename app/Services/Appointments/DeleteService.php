<?php
declare(strict_types=1);

namespace App\Services\Appointments;

use App\Exceptions\AppointmentsException;
use App\Models;
use Illuminate\Support\Facades;

readonly class DeleteService
{
    public function delete(int $id): bool
    {
        return Facades\DB::transaction(function () use ($id) {
            $appointment = Models\Appointment::find($id);

            if (!$appointment) {
                throw AppointmentsException::bookingNotFound($id);
            }

            if ($appointment->timeSlot && $appointment->timeSlot->start_at->isPast()) {
                throw AppointmentsException::pastTimeDelete();
            }

            if ($appointment->timeSlot) {
                $appointment->timeSlot->release();
            }
            return $appointment->delete();
        });
    }
}

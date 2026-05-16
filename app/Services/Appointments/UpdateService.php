<?php
declare(strict_types=1);

namespace App\Services\Appointments;


use App\Exceptions\AppointmentsException;
use App\Models;
use App\Services\Clients\ClientsService;
use Carbon\Carbon;
use Illuminate\Support\Facades;

readonly class UpdateService
{
    public function __construct(private ClientsService $clientsService)
    {
    }

    public function update(
        int     $id,
        int     $slotId,
        string  $status,
        string  $notificationType,
        string  $firstName,
        string  $lastName,
        string  $identifier,
        ?string $description = null
    ): Models\Appointment
    {
        return Facades\DB::transaction(function () use ($id, $slotId, $status, $notificationType, $firstName, $lastName, $identifier, $description) {

            $appointment = Models\Appointment::with('timeSlot')->find($id);
            if (!$appointment) {
                throw AppointmentsException::bookingNotFound($id);
            }

            $oldSlot = $appointment->timeSlot;
            if ($oldSlot->isPast()) {
                throw AppointmentsException::pastTimeUpdate();
            }

            $client = $this->clientsService->getOrCreateClient(
                identifier: $identifier,
                firstName: $firstName,
                lastName: $lastName
            );

            $newSlot = Models\TimeSlot::where('id', $slotId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($oldSlot->id !== $newSlot->id) {
                if ($newSlot->appointment_id !== null) {
                    throw AppointmentsException::slotAlreadyBooked();
                }

                if ($newSlot->isPast()) {
                    throw AppointmentsException::bookInPast();
                }

                $oldSlot->release();
                $newSlot->assignTo($appointment);
            }

            $appointment->update([
                'client_id' => $client->id,
                'status' => $status,
                'notification_type' => $notificationType,
                'description' => $description,
            ]);

            return $appointment->refresh()->load('timeSlot', 'client');
        }, 3);
    }
}

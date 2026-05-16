<?php
declare(strict_types=1);

namespace App\Services\Appointments;

use App\Exceptions\AppointmentsException;
use App\Models;
use App\Services\Clients\ClientsService;
use Throwable;

readonly class CreateService
{
    public function __construct(private ClientsService $clientsService)
    {
    }

    /**
     * @throws Throwable
     */
    public function create(
        string  $firstName,
        string  $lastName,
        string  $identifier,
        string  $notificationType,
        int     $timeSlotId,
        ?string $description = null
    ): Models\Appointment
    {
        return \DB::transaction(function () use ($timeSlotId, $firstName, $lastName, $identifier, $notificationType, $description) {
            $client = $this->clientsService->getOrCreateClient(
                identifier: $identifier,
                firstName: $firstName,
                lastName: $lastName
            );

            $slot = Models\TimeSlot::available()
                ->where('id', $timeSlotId)
                ->lockForUpdate()
                ->first();

            if (!$slot) {
                throw AppointmentsException::slotNotAvailable();
            }

            if ($slot->start_at->isPast()) {
                throw AppointmentsException::pastTimeBook();
            }

            $appointment = Models\Appointment::create([
                'client_id' => $client->id,
                'notification_type' => $notificationType,
                'description' => $description,
            ]);

            $slot->assignTo($appointment);

            return $appointment->refresh()->load(['timeSlot', 'client']);
        }, 3);
    }
}

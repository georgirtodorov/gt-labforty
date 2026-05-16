<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\AppointmentsException;
use App\Http\Requests\Api\ApiCreateAppointmentRequest;
use App\Services\Appointments\CreateService;
use App\Services\TimeSlots\TimeSlotsService;
use Illuminate\Http;

class ApiCreateAppointmentBaseController extends ApiBaseController
{
    public function __invoke(
        ApiCreateAppointmentRequest $request,
        CreateService               $service,
        TimeSlotsService            $timeslotsService,
    ): Http\JsonResponse
    {
        try {
            $slot = $timeslotsService->getSlotByDateRange($request->start_at, $request->end_at);

            if (!is_null($slot->appointment_id)) {
                throw AppointmentsException::slotAlreadyBooked();
            }

            $appointment = $service->create(
                firstName: $request->first_name,
                lastName: $request->last_name,
                identifier: $request->identifier,
                notificationType: $request->notification_type,
                timeSlotId: $slot->id,
                description: $request->description
            );

            return response()->json(['appointment' => $appointment], 201);
        } catch (\Throwable $e) {
            return $this->handleThrowable($e);
        }
    }
}

<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Api\ApiUpdateAppointmentRequest;
use App\Services\Appointments;
use App\Services\TimeSlots\TimeSlotsService;
use Illuminate\Http;
use Throwable;


class ApiUpdateAppointmentBaseController extends ApiBaseController
{
    public function __invoke(
        ApiUpdateAppointmentRequest $request,
        Appointments\UpdateService  $updateService,
        TimeSlotsService            $timeSlotsService,
        int                         $id
    ): Http\JsonResponse
    {
        try {
            $slot = $timeSlotsService->getSlotByDateRange($request->start_at, $request->end_at);
            $appointment = $updateService->update(
                id: $id,
                slotId: $slot->id,
                status: $request->status,
                notificationType: $request->notification_type,
                firstName: $request->first_name,
                lastName: $request->last_name,
                identifier: $request->identifier,
                description: $request->description
            );
            return response()->json(['appointment' => $appointment,]);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}

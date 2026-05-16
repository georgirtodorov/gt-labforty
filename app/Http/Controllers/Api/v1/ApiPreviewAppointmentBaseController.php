<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Services\Appointments;
use Illuminate\Http;
use Throwable;

class ApiPreviewAppointmentBaseController extends ApiBaseController
{
    public function __invoke(
        Appointments\ListingService $bookingService,
        int                         $id): Http\JsonResponse
    {
        try {
            $appointment = $bookingService->previewById($id);
            return response()->json($appointment);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}

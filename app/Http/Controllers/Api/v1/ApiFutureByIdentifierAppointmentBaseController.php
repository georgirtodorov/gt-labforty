<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Services\Appointments;
use Illuminate\Http;
use Throwable;

class ApiFutureByIdentifierAppointmentBaseController extends ApiBaseController
{
    public function __invoke(
        Appointments\ListingService $service,
        string                      $identifier
    ): Http\JsonResponse
    {
        try {
            $ids = $service->listByIdentifier($identifier);
            $result = $service->listByIds($ids);
            return response()->json($result);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}

<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Services\TimeSlots\TimeSlotsService;
use Illuminate\Http;
use Throwable;

class ApiAvailableSlotBaseController extends ApiBaseController
{
    public function __invoke(Http\Request $request, TimeSlotsService $service): Http\JsonResponse
    {
        try {
            $validated = $request->validate([
                'date' => 'required|date_format:Y-m-d',
            ]);

            $date = $validated['date'];
            return response()->json($service->getAvailableByDate($date));
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}

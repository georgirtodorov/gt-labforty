<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Services\Appointments;
use Illuminate\Http;
use Throwable;

class ApiDeleteAppointmentBaseController extends ApiBaseController
{
    public function __invoke(
        Appointments\DeleteService $service,
        int                        $id
    ): Http\JsonResponse
    {
        try {
            $service->delete($id);
            return response()->json([
                'success' => true,
            ]);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}

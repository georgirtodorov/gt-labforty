<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Api\ApiListsAppointmentsRequest;
use App\Services\Appointments\ListingService;
use Illuminate\Http;
use Throwable;

class ApiListAppointmentsController extends ApiBaseController
{
    public function __invoke(
        ApiListsAppointmentsRequest $request,
        ListingService              $service
    ): Http\JsonResponse
    {
        try {
            $paginator = $service->list(
                identifier: $request->identifier,
                startDate: $request->date_from,
                endDate: $request->date_to,
                perPage: (int)$request->query('perPage', 15)
            );

            return response()->json([
                'current_page' => $paginator->currentPage(),
                'data' => $paginator->items(),
                'next_page_url' => $paginator->nextPageUrl(),
                'prev_page_url' => $paginator->previousPageUrl(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ]);
        } catch (Throwable $e) {
            return $this->handleThrowable($e);
        }
    }
}

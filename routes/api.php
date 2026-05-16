<?php
declare(strict_types=1);

use App\Http\Controllers\Api\v1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::post('appointments', v1\ApiCreateAppointmentBaseController::class);
    Route::get('appointments', v1\ApiListAppointmentsController::class);
    Route::get('appointments/{id}', v1\ApiPreviewAppointmentBaseController::class);
    Route::delete('appointments/{id}', v1\ApiDeleteAppointmentBaseController::class);
    Route::put('appointments/{id}', v1\ApiUpdateAppointmentBaseController::class);
    Route::get('available-slots', v1\ApiAvailableSlotBaseController::class);
    Route::get('appointments/future/{id}', v1\ApiFutureByIdentifierAppointmentBaseController::class);
});

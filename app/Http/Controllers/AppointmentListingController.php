<?php
declare(strict_types=1);

namespace App\Http\Controllers;


use App\Http\Requests\Appointments\AppointmentListingRequest;
use App\Services\Appointments\ListingService;
use Carbon\Carbon;
use Throwable;

class AppointmentListingController
{

    public function index(AppointmentListingRequest $request, ListingService $service)
    {
        try {

            $startDate = null;
            $endDate = null;

            if ($request->filled('start_date')) {
                $hour = $request->input('start_hour', '00');
                $minute = $request->input('start_minute', '00');
                $startDate = Carbon::createFromFormat(
                    'Y-m-d H:i:s', "{$request->start_date} {$hour}:{$minute}:00",
                    config('app.business_timezone')
                )->utc();
            }

            if ($request->filled('end_date')) {
                $hour = $request->input('end_hour', '23');
                $minute = $request->input('end_minute', '59');
                $endDate = Carbon::createFromFormat(
                    'Y-m-d H:i:s', "{$request->end_date} {$hour}:{$minute}:59",
                    config('app.business_timezone')
                )->utc();
            }

            if ($startDate && $endDate && $endDate->isBefore($startDate)) {
                return back()->withErrors(['error' => 'Крайната дата не може да бъде преди началната']);
            }

            $appointments = $service->list(
                identifier: $request->identifier,
                startDate: $startDate?->toDateTimeString(),
                endDate: $endDate?->toDateTimeString(),
                perPage: 15
            );

            return view('appointments.listing', compact('appointments'));
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}

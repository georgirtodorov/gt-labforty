<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Services\Appointments;


class AppointmentController
{
    public function index()
    {

        return view('appointments.booking');
    }

    public function store(
        Requests\Appointments\CreateAppointmentRequest $request,
        Appointments\CreateService                     $service
    )
    {
        try {
            $appointment = $service->create(
                firstName: $request->first_name,
                lastName: $request->last_name,
                identifier: $request->identifier,
                notificationType: $request->notification_type,
                timeSlotId: (int)$request->time_slot_id,
                description: $request->description
            );
            return back()->with('success', true)
                ->with('notification_type', $appointment->notification_type);
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update(
        Requests\Appointments\UpdateAppointmentRequest $request,
        Appointments\UpdateService                     $service,
        int                                            $id)
    {
        try {
            $service->update(
                id: $id,
                slotId: (int)$request->time_slot_id,
                status: $request->status,
                notificationType: $request->notification_type,
                firstName: $request->first_name,
                lastName: $request->last_name,
                identifier: $request->identifier,
                description: $request->description
            );

            return redirect()
                ->route('booking.show', $id)
                ->with('success', 'Часът е обновен успешно!');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Appointments\ListingService $service, int $id)
    {
        try {
            $appointment = $service->previewById($id);
            $nextAppointments = $service->getUpcomingByClient(
                clientId: $appointment->client_id,
                excludeId: $id
            );

            return view('appointments.show', [
                'appointment' => $appointment,
                'nextAppointments' => $nextAppointments
            ]);

        } catch (\Throwable $e) {
            return redirect()
                ->route('booking.index')
                ->withErrors(['error' => 'Часът не е намерен или възникна грешка: ' . $e->getMessage()]);
        }
    }

    public function edit(int $id, Appointments\ListingService $service)
    {
        $appointment = $service->previewById($id);
        return view('appointments.edit', compact('appointment'));
    }

    public function destroy(Appointments\DeleteService $service, int $id)
    {
        try {
            $service->delete($id);

            return redirect()
                ->route('booking.index')
                ->with('success', 'Часът беше изтрит успешно.');

        } catch (\Throwable $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}

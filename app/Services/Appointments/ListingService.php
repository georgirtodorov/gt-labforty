<?php
declare(strict_types=1);

namespace App\Services\Appointments;

use App\Exceptions\AppointmentsException;
use App\Models;
use Illuminate\Pagination;
use Illuminate\Support\Facades;

readonly class ListingService
{
    public function list(
        ?string $identifier = null,
        ?string $startDate = null,
        ?string $endDate = null,
        ?int    $perPage = 10,
    ): Pagination\LengthAwarePaginator
    {
        return Facades\DB::table('appointments')
            ->join('time_slots', 'appointments.id', '=', 'time_slots.appointment_id')
            ->join('clients', 'appointments.client_id', '=', 'clients.id')
            ->select([
                'appointments.id',
                'appointments.status',
                Facades\DB::raw("DATE_FORMAT(time_slots.start_at, '%Y-%m-%dT%H:%i:%sZ') as start_at"),
                Facades\DB::raw("DATE_FORMAT(time_slots.end_at, '%Y-%m-%dT%H:%i:%sZ') as end_at"),
                'clients.first_name',
                'clients.last_name',
                'clients.identifier',
                'appointments.notification_type'
            ])
            ->when($identifier, fn($q) => $q->where('clients.identifier', $identifier))
            ->when($startDate, fn($q) => $q->where('time_slots.start_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('time_slots.end_at', '<=', $endDate))
            ->orderBy('time_slots.start_at', 'asc')
            ->paginate($perPage);
    }

    /**
     * @throws AppointmentsException
     */
    public function previewById(int $id): Models\Appointment
    {
        $appointment = Models\Appointment::find($id);

        if (!$appointment) {
            throw AppointmentsException::bookingNotFound($id);
        }
        return $appointment;
    }

    public function listByIds(array $ids): \Illuminate\Database\Eloquent\Collection
    {
        return Models\Appointment::with(['timeSlot', 'client'])
            ->join('time_slots', 'appointments.id', '=', 'time_slots.appointment_id')
            ->whereIn('appointments.id', $ids)
            ->orderBy('time_slots.start_at', 'asc')
            ->select('appointments.*')
            ->get();
    }

    public function listByIdentifier(string $identifier): array
    {
        return Facades\DB::table('appointments')
            ->join('time_slots', 'appointments.id', '=', 'time_slots.appointment_id')
            ->join('clients', 'appointments.client_id', '=', 'clients.id')
            ->where('clients.identifier', $identifier)
            ->where('time_slots.start_at', '>=', now()->setTimezone('UTC')->toDateTimeString())
            ->orderBy('time_slots.start_at', 'asc')
            ->pluck('appointments.id')
            ->toArray();
    }

    public function getUpcomingByClient(
        int $clientId,
        int $excludeId,
        int $perPage = 5
    ): Pagination\LengthAwarePaginator
    {
        return Facades\DB::table('appointments')
            ->join('time_slots', 'appointments.id', '=', 'time_slots.appointment_id')
            ->select([
                'appointments.id',
                'time_slots.start_at',
                'time_slots.end_at'
            ])
            ->where('appointments.client_id', $clientId)
            ->where('appointments.id', '!=', $excludeId)
            ->where('time_slots.start_at', '>=', now())
            ->orderBy('time_slots.start_at', 'asc')
            ->paginate($perPage);
    }
}

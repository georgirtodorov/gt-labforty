@extends('index')

@section('content')
    <div class="container p-4">

        <div class="card shadow-sm border-0 mb-5">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-info-circle me-2"></i>Детайли за час #{{ $appointment->id }}</h4>
                <div class="d-flex gap-2">

                    <a href="{{ route('booking.edit', $appointment->id) }}"
                       class="btn btn-light btn-sm text-primary fw-bold">
                        <i class="bi bi-pencil-square"></i> РЕДАКТИРАЙ
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">

                    <div class="col-md-6 border-end">
                        <h5 class="text-secondary mb-3"><i class="bi bi-calendar3 me-2"></i>График</h5>
                        <div class="mb-2">
                            <strong>Дата:</strong> {{ $appointment->timeSlot->businessStart()->format('d.m.Y') }} г.
                        </div>
                        <div class="mb-2">
                            <strong>Час:</strong> {{ $appointment->timeSlot->businessStart()->format('H:i') }}
                            - {{ $appointment->timeSlot->businessEnd()->format('H:i') }} ч.
                        </div>
                        <div class="mb-2"><strong>Известие:</strong>
                            <span>{{ strtoupper($appointment->notification_type) }}</span>
                        </div>
                        <div class="mb-2"><strong>Статус:</strong>
                            <span>{{ strtoupper($appointment->status) }}</span>
                        </div>
                    </div>

                    <div class="col-md-6 ps-md-4">
                        <h5 class="text-secondary mb-3"><i class="bi bi-person-badge me-2"></i>Данни за клиента</h5>
                        <div class="mb-2">
                            <strong>Име:</strong> {{ $appointment->client->first_name }} {{ $appointment->client->last_name }}
                        </div>
                        <div class="mb-2"><strong>ЕГН:</strong> <code>{{ $appointment->client->identifier }}</code>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <h5 class="text-secondary mb-2"><i class="bi bi-chat-left-text me-2"></i>Описание</h5>
                        <div class="p-3 bg-light rounded border">
                            {{ $appointment->description ?: 'Няма въведено описание.' }}
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-between">
                    <a href="{{ route('listing') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left"></i> Назад към списъка
                    </a>

                    <form action="{{ route('booking.destroy', $appointment->id) }}" method="POST"
                          onsubmit="return confirm('Наистина ли искате да изтриете този час?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="bi bi-trash"></i> Изтрий записа
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <h5 class="mb-3 text-secondary border-bottom pb-2">
                <i class="bi bi-clock-history me-2"></i>Всички предстоящи часове за същия клиент
            </h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle border shadow-sm">
                    <thead class="table-light">
                    <tr>
                        <th>Дата и час</th>
                        <th class="text-end" style="width: 250px;">Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($nextAppointments as $item)
                        <tr>
                            @php
                                $bizTz = config('app.business_timezone');
                                $start = \Carbon\Carbon::parse($item->start_at)->timezone($bizTz);
                                $end = \Carbon\Carbon::parse($item->end_at)->timezone($bizTz);
                            @endphp
                            <td class="fw-bold">
                                {{ $start->format('d.m.Y') }}
                                <span class="text-muted mx-1">|</span>
                                {{ $start->format('H:i') }} - {{ $end->format('H:i') }} ч.
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('booking.show', $item->id) }}"
                                       class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i> Преглед
                                    </a>
                                    <form action="{{ route('booking.destroy', $item->id) }}" method="POST"
                                          onsubmit="return confirm('Сигурни ли сте?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Изтрий
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center py-4 text-muted">Няма други предстоящи часове за този
                                клиент.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($nextAppointments->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $nextAppointments->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection

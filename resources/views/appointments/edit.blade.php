@extends('index')

@section('content')
    <div class="container p-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Редакция на час #{{ $appointment->id }}</h4>
                <a href="{{ route('booking.show', $appointment->id) }}" class="btn btn-sm btn-outline-dark">
                    <i class="bi bi-x-lg"></i> Отказ
                </a>
            </div>
            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger shadow-sm border-start border-4 border-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('booking.update', $appointment->id) }}" method="POST" class="row g-3">
                    @csrf
                    @method('PUT')

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-secondary">Дата</label>
                        @php
                            $bizTz = config('app.business_timezone');
                            $start = \Carbon\Carbon::parse($appointment->timeSlot->start_at)->timezone($bizTz);
                            $end = \Carbon\Carbon::parse($appointment->timeSlot->end_at)->timezone($bizTz);
                        @endphp
                        <input type="date" id="appointment_date" name="date" class="form-control"
                               value="{{ old('date', $start->format('Y-m-d')) }}"
                               min="{{ date('Y-m-d') }}"
                               data-current-slot-id="{{ $appointment->timeSlot->id }}"
                               data-current-slot-label="{{ $start->format('H:i') }} - {{ $end->format('H:i') }}"
                               required>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-bold text-secondary">Избор на час</label>
                        <select name="time_slot_id" id="time_slot_select" class="form-select border-primary" required>
                            <option value="{{ $appointment->timeSlot->id }}" selected>
                                {{ $start->format('H:i') }} - {{ $end->format('H:i') }} (Текущ)
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted">Име</label>
                        <input type="text" name="first_name" class="form-control" value="{{ $appointment->client->first_name }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted">Фамилия</label>
                        <input type="text" name="last_name" class="form-control" value="{{ $appointment->client->last_name }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted">ЕГН</label>
                        <input type="text" name="identifier" class="form-control" value="{{ $appointment->client->identifier }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold text-secondary">Начин на известяване</label>
                        <select name="notification_type" class="form-select">
                            <option
                                value="email" {{ old('notification_type', $appointment->notification_type) == 'email' ? 'selected' : '' }}>
                                Имейл
                            </option>
                            <option
                                value="sms" {{ old('notification_type', $appointment->notification_type) == 'sms' ? 'selected' : '' }}>
                                СМС
                            </option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold text-secondary">Статус на резервацията</label>
                        <select name="status" class="form-select border-info">
                            <option value="{{ \App\Models\Appointment::STATUS_REQUESTED }}"
                                {{ old('status', $appointment->status) == \App\Models\Appointment::STATUS_REQUESTED ? 'selected' : '' }}>
                                Requested
                            </option>
                            <option value="{{ \App\Models\Appointment::STATUS_CONFIRMED }}"
                                {{ old('status', $appointment->status) == \App\Models\Appointment::STATUS_CONFIRMED ? 'selected' : '' }}>
                                Confirmed
                            </option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold text-secondary">Описание</label>
                        <textarea name="description" class="form-control"
                                  rows="4">{{ old('description', $appointment->description) }}</textarea>
                    </div>

                    <div class="col-12 mt-4 border-top pt-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success px-5 shadow-sm">
                                <i class="bi bi-check-lg"></i> Запази промените
                            </button>
                            <a href="{{ route('booking.show', $appointment->id) }}"
                               class="btn btn-outline-secondary px-4">Отказ</a>
                        </div>

                        <button type="button" class="btn btn-outline-danger"
                                onclick="if(confirm('Сигурни ли сте, че искате да изтриете този час?')) document.getElementById('delete-form').submit();">
                            <i class="bi bi-trash"></i> Изтрий
                        </button>
                    </div>
                </form>

                <form id="delete-form" action="{{ route('booking.destroy', $appointment->id) }}" method="POST"
                      class="d-none">
                    @csrf
                    @method('DELETE')
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dateInput = document.getElementById('appointment_date');
            const slotSelect = document.getElementById('time_slot_select');

            function loadSlots(selectedDate) {
                const currentSlotId = dateInput.getAttribute('data-current-slot-id');
                const currentSlotLabel = dateInput.getAttribute('data-current-slot-label');
                const originalDate = "{{ \Carbon\Carbon::parse($appointment->timeSlot->start_at)->timezone($appointment->timeSlot->business_timezone)->format('Y-m-d') }}";

                const previousSelectedValue = slotSelect.value;

                slotSelect.innerHTML = '<option value="">Зареждане...</option>';

                fetch(`/api/v1/available-slots?date=${selectedDate}`)
                    .then(response => response.json())
                    .then(data => {
                        slotSelect.innerHTML = '<option value="">-- Избери свободен час --</option>';

                        if (selectedDate === originalDate) {
                            const option = document.createElement('option');
                            option.value = currentSlotId;
                            option.textContent = `${currentSlotLabel} (Текущ)`;
                            if (!previousSelectedValue || previousSelectedValue == currentSlotId) {
                                option.selected = true;
                            }
                            slotSelect.appendChild(option);
                        }

                        data.forEach(slot => {
                            if (slot.id != currentSlotId) {
                                const options = {
                                    timeZone: slot.busines_timezone,
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false
                                };

                                const start = new Date(slot.start_at).toLocaleTimeString('en-GB', options);
                                const end = new Date(slot.end_at).toLocaleTimeString('en-GB', options);

                                const option = document.createElement('option');
                                option.value = slot.id;
                                option.textContent = `${start} - ${end}`;

                                if (previousSelectedValue == slot.id) {
                                    option.selected = true;
                                }

                                slotSelect.appendChild(option);
                            }
                        });

                        if (slotSelect.options.length <= 1 && selectedDate !== originalDate) {
                            slotSelect.innerHTML = '<option value="">Няма свободни часове за тази дата</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching slots:', error);
                        slotSelect.innerHTML = '<option value="">Грешка при зареждане</option>';
                    });
            }

            dateInput.addEventListener('change', function () {
                loadSlots(this.value);
            });

            loadSlots(dateInput.value);
        });
    </script>
@endsection

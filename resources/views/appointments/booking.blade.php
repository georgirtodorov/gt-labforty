@extends('index')

@section('content')

    <div class="p-3">
        <h4>Запазване на час</h4>
        <hr>
        @if(session('success'))
            <script>
                alert("Успешно запазихте час! Клиентът ще бъде уведомен чрез {{ strtoupper(session('notification_type')) }}.");
            </script>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger"
                 style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px;">
                <strong>Error:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('booking.store') }}" method="POST" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label class="form-label">Дата</label>
                <input type="date" id="appointment_date" name="date" class="form-control"
                       value="{{ old('date', date('Y-m-d')) }}"
                       min="{{ date('Y-m-d') }}" required>
            </div>

            <div class="col-md-8">
                <label class="form-label">Свободни часове</label>
                <select name="time_slot_id" id="time_slot_select" class="form-select" required>
                    <option value="" disabled></option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">ЕГН</label>
                <input type="text" name="identifier" class="form-control" maxlength="10"
                       value="{{ old('identifier') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Нотификация</label>
                <select name="notification_type" class="form-select">
                    <option value="email" {{ old('notification_type') == 'email' ? 'selected' : '' }}>Имейл</option>
                    <option value="sms" {{ old('notification_type') == 'sms' ? 'selected' : '' }}>СМС</option>
                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label">Име</label>
                <input type="text" name="first_name" class="form-control" maxlength="64"
                       value="{{ old('first_name') }}" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Фамилия</label>
                <input type="text" name="last_name" class="form-control" maxlength="64"
                       value="{{ old('last_name') }}" required>
            </div>

            <div class="col-12">
                <label class="form-label">Описание</label>
                <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
            </div>

            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-success px-5">ЗАПАЗИ</button>
                <a href="{{ route('booking.index') }}" class="btn btn-outline-secondary px-4">НУЛИРАЙ</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('appointment_date').addEventListener('change', function () {
            let selectedDate = this.value;
            let slotSelect = document.getElementById('time_slot_select');

            slotSelect.innerHTML = '';

            fetch(`/api/v1/available-slots?date=${selectedDate}`)
                .then(response => response.json())
                .then(data => {
                    slotSelect.innerHTML = '';
                    data.forEach(slot => {
                        const options = {
                            timeZone: slot.business_timezone,
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        };

                        let startTime = new Date(slot.start_at).toLocaleTimeString('en-GB', options);
                        let endTime = new Date(slot.end_at).toLocaleTimeString('en-GB', options);

                        let interval = `${startTime} - ${endTime}`;

                        slotSelect.innerHTML += `<option value="${slot.id}">${interval} ч.</option>`;
                    });
                    console.log(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    slotSelect.innerHTML = '<option value="">Грешка при зареждане на свободните часове</option>';
                });
        });

        document.getElementById('appointment_date').dispatchEvent(new Event('change'));
    </script>

@endsection



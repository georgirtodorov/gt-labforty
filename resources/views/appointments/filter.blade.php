<div>
    <h4>Филтриране на часове</h4>
    <hr>
    <form action="{{ route('listing') }}" method="GET" class="p-3 border rounded bg-light">
        <input type="hidden" name="search" value="1">

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">ЕГН</label>
                <input type="text" name="identifier" class="form-control"
                       value="{{ old('identifier', request('identifier')) }}" placeholder="Търсене по ЕГН...">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label text-primary fw-bold">От дата / час</label>
                <div class="d-flex gap-2">
                    <input type="date" name="start_date" class="form-control"
                           value="{{ old('start_date', request('start_date')) }}" style="max-width: 160px;">

                    <select name="start_hour" class="form-select" style="width: 90px;">
                        <option value="">Час</option>
                        @for ($i = 0; $i < 24; $i++)
                            @php
                                $formattedHour = sprintf('%02d', $i);
                                $currentStartHour = old('start_hour', request('start_hour'));

                                $isStartHourSelected = request()->has('search') || old('start_hour') !== null
                                    ? $currentStartHour === $formattedHour
                                    : $formattedHour === '00';
                            @endphp
                            <option value="{{ $formattedHour }}" {{ $isStartHourSelected ? 'selected' : '' }}>
                                {{ $formattedHour }}
                            </option>
                        @endfor
                    </select>

                    <select name="start_minute" class="form-select" style="width: 90px;">
                        <option value="">Мин</option>
                        @foreach(['00', '15', '30', '45'] as $min)
                            @php
                                $currentStartMin = old('start_minute', request('start_minute'));

                                $isStartMinSelected = request()->has('search') || old('start_minute') !== null
                                    ? $currentStartMin === $min
                                    : $min === '00';
                            @endphp
                            <option value="{{ $min }}" {{ $isStartMinSelected ? 'selected' : '' }}>
                                {{ $min }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label text-danger fw-bold">До дата / час</label>
                <div class="d-flex gap-2">

                    <input type="date" name="end_date" class="form-control"
                           value="{{ old('end_date', request('end_date')) }}" style="max-width: 160px;">

                    <select name="end_hour" class="form-select" style="width: 90px;">
                        <option value="">Час</option>
                        @for ($i = 0; $i < 24; $i++)
                            @php
                                $formattedHour = sprintf('%02d', $i);
                                $currentEndHour = old('end_hour', request('end_hour'));

                                $isEndHourSelected = request()->has('search') || old('end_hour') !== null
                                    ? $currentEndHour === $formattedHour
                                    : $formattedHour === '23';
                            @endphp
                            <option value="{{ $formattedHour }}" {{ $isEndHourSelected ? 'selected' : '' }}>
                                {{ $formattedHour }}
                            </option>
                        @endfor
                    </select>

                    <select name="end_minute" class="form-select" style="width: 90px;">
                        <option value="">Мин</option>
                        @foreach(['00', '15', '30', '45'] as $min)
                            @php
                                $currentEndMin = old('end_minute', request('end_minute'));

                                $isEndMinSelected = request()->has('search') || old('end_minute') !== null
                                    ? $currentEndMin === $min
                                    : $min === '30';
                            @endphp
                            <option value="{{ $min }}" {{ $isEndMinSelected ? 'selected' : '' }}>
                                {{ $min }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row pt-2">
            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('listing') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-clockwise"></i> НУЛИРАЙ
                </a>
                <button type="submit" class="btn btn-primary px-5">
                    <i class="bi bi-search"></i> ТЪРСИ
                </button>
            </div>
        </div>
    </form>
</div>

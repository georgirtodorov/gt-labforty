@extends('index')

@section('content')
    <div class="p-4">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
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

        @include('appointments.filter')

        <div class="table-responsive mt-3">
            <table class="table table-hover align-middle border">
                <thead class="table-light">
                <tr>
                    <th>Дата и час</th>
                    <th>Статус</th>
                    <th>Име</th>
                    <th>Фамилия</th>
                    <th>ЕГН</th>
                    <th class="text-end" style="width: 200px;">Действия</th>
                </tr>
                </thead>
                <tbody>
                @forelse($appointments as $item)
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

                        <td>{{ $item->status ?? 'N/A' }}</td>

                        <td>{{ $item->first_name ?? 'N/A' }}</td>
                        <td>{{ $item->last_name ?? 'N/A' }}</td>

                        <td><code>{{ $item->identifier ?? 'N/A' }}</code></td>

                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('booking.show', $item->id) }}"
                                   class="btn btn-sm btn-info text-white"
                                   title="Преглед">
                                    <i class="bi bi-eye"></i> Преглед
                                </a>

                                <form action="{{ route('booking.destroy', $item->id) }}" method="POST"
                                      onsubmit="return confirm('Сигурни ли сте?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Изтрий">
                                        <i class="bi bi-trash"></i> Изтрий
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <span class="text-muted">Няма открити записи.</span>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $appointments->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>

    </div>
@endsection

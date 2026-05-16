<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Appointment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            padding: 50px 0;
        }

        .content-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container" style="max-width: 800px;">
    <div class="text-center mb-4">
        <div class="btn-group btn-group-lg">
            <a href="{{ route('booking.index') }}"
               class="btn {{ request()->routeIs('booking.index') ? 'btn-primary' : 'btn-outline-primary' }}">
                ЗАПАЗИ
            </a>
            <a href="{{ route('listing') }}"
               class="btn {{ request()->routeIs('listing') ? 'btn-primary' : 'btn-outline-primary' }}">
                СПИСЪК
            </a>
        </div>
    </div>

    <div class="content-card">
        @yield('content')
    </div>
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title') | {{ config('app.name') }}</title>

    <meta name="title" content="Bus Timings | {{ config('app.name') }}">
    <meta name="description" content="Bus Timings">
    <meta name="keywords" content="Bus Timings">
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="robots" content="index, follow">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Bus Timings">
    <meta property="og:description" content="Bus Timings">
    <meta property="og:image" content="{{ asset('img/logo.png') }}">
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="Bus Timings">
    <meta property="twitter:description" content="Bus Timings">
    <meta property="twitter:image" content="{{ asset('img/logo.png') }}">

    <link rel="canonical" href="{{ url()->current() }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('img/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('img/android-chrome-512x512.png') }}">
    <link rel="manifest" href="{{ asset('img/site.webmanifest') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <link href="{{ asset('operator/style.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>
    @yield('content')

    <nav class="bottom-nav">
        <a href="{{ route('operator.dashboard') }}"
            class="nav-item {{ Str::is('operator.dashboard', Route::currentRouteName()) ? 'active' : '' }}">
            <i class="bi bi-house-door-fill"></i>
            Home
        </a>
        <a href="{{ route('operator.bus.index') }}"
            class="nav-item {{ Str::is('operator.bus.*', Route::currentRouteName()) ? 'active' : '' }}">
            <i class="bi bi-bus-front"></i>
            Bus
        </a>
        <a href="schedules.html" class="nav-item">
            <i class="bi bi-calendar-check"></i>
            Schedules
        </a>

        <form method="POST" action="{{ route('operator.logout') }}" id="logout-form">
            @csrf

            <a href="#" class="nav-item" onclick="confirmLogout(event)">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </a>
        </form>
    </nav>

    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-body text-center p-4">
                    <div class="success-icon-wrapper mb-3">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>

                    <h4 class="fw-bold text-dark">Success!</h4>
                    <p class="text-muted small px-3">
                        {{ session('success') }}
                    </p>

                    <button type="button" class="btn btn-success-custom w-100 py-3 mt-2 rounded-3"
                        data-bs-dismiss="modal">
                        Great, thanks!
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function confirmLogout(event) {
            event.preventDefault();

            if (confirm("Are you sure you want to logout?")) {
                document.getElementById('logout-form').submit();
            }
        }
    </script>

    @session('success')
        <script>
            const successPopup = new bootstrap.Modal(document.getElementById('successModal'));
            successPopup.show();
        </script>
    @endsession

    @stack('scripts')
</body>

</html>

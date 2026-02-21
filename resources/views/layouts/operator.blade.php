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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

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
        <a href="{{ route('operator.trip.index') }}"
            class="nav-item {{ Str::is('operator.trip.*', Route::currentRouteName()) ? 'active' : '' }}">
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

    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-body text-center p-4">
                    <div class="error-icon-wrapper mb-3">
                        <i class="bi bi-exclamation-circle-fill"></i>
                    </div>

                    <h4 class="fw-bold text-dark">Oops!</h4>
                    <p class="text-muted small px-3">
                        {{ session('error') ?? 'Something went wrong. Please try again.' }}
                    </p>

                    <button type="button" class="btn btn-error-custom w-100 py-3 mt-2 rounded-3"
                        data-bs-dismiss="modal">
                        Try Again
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .error-icon-wrapper {
            font-size: 4rem;
            color: #dc3545;
            /* Bootstrap Danger Red */
            display: inline-block;
            animation: shake 0.5s cubic-bezier(.36, .07, .19, .97) both;
        }

        .btn-error-custom {
            background: #f8d7da;
            color: #dc3545;
            border: 1px solid #f5c2c7;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-error-custom:hover {
            background: #dc3545;
            color: white;
        }

        /* Attention-grabbing shake animation */
        @keyframes shake {

            10%,
            90% {
                transform: translate3d(-1px, 0, 0);
            }

            20%,
            80% {
                transform: translate3d(2px, 0, 0);
            }

            30%,
            50%,
            70% {
                transform: translate3d(-4px, 0, 0);
            }

            40%,
            60% {
                transform: translate3d(4px, 0, 0);
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

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
            const popup = new bootstrap.Modal(document.getElementById('successModal'));
            popup.show();
        </script>
    @endsession

    @session('error')
        <script>
            const popup = new bootstrap.Modal(document.getElementById('errorModal'));
            popup.show();
        </script>
    @endsession

    @stack('scripts')
</body>

</html>

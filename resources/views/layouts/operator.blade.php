<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title') | {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <link href="{{ asset('operator/style.css') }}" rel="stylesheet">

    @stack('styles')
</head>


<body>
    @yield('content')

    <nav class="bottom-nav">
        <a href="dashboard.html" class="nav-item active">
            <i class="bi bi-house-door-fill"></i>
            Home
        </a>
        <a href="fleet.html" class="nav-item">
            <i class="bi bi-bus-front"></i>
            Fleet
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function confirmLogout(event) {
            event.preventDefault();

            if (confirm("Are you sure you want to logout?")) {
                document.getElementById('logout-form').submit();
            }
        }
    </script>

    @stack('scripts')
</body>

</html>

<!doctype html>
<html lang="en">

<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
        id="main-font-link" />
    <link rel="stylesheet" href="{{ asset('assets-admin/fonts/phosphor/duotone/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets-admin/fonts/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets-admin/fonts/feather.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets-admin/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets-admin/fonts/material.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets-admin/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('assets-admin/css/style-preset.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets-admin/css/custom.css') }}" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

    <style>
        .choices__list--dropdown,
        .choices__list[aria-expanded] {
            z-index: 9999 !important;
        }

        .choices.is-focused .choices__inner,
        .choices.is-open .choices__inner {
            box-shadow: none !important;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header d-flex justify-content-center">
                <a href="{{ route('backend.dashboard') }}" class="b-brand text-primary">
                    <div class="fw-bold fs-2">
                        <span class="text-success">{{ config('app.name') }}</span>
                    </div>
                </a>
            </div>

            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item">
                        <a href="{{ route('backend.dashboard') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li
                        class="pc-item pc-hasmenu
                        {{ Str::is('district.*', Route::currentRouteName()) ||
                        Str::is('city.*', Route::currentRouteName()) ||
                        Str::is('stop.*', Route::currentRouteName())
                            ? 'active pc-trigger'
                            : '' }}">
                        <a href="javascript:void(0)" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-package"></i></span>
                            <span class="pc-mtext">Stops</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item {{ Str::is('district.*', Route::currentRouteName()) ? 'active' : '' }}">
                                <a class="pc-link" href="{{ route('district.index') }}">Districts</a>
                            </li>
                            <li class="pc-item {{ Str::is('city.*', Route::currentRouteName()) ? 'active' : '' }}">
                                <a class="pc-link" href="{{ route('city.index') }}">Cities</a>
                            </li>
                            <li class="pc-item {{ Str::is('stop.*', Route::currentRouteName()) ? 'active' : '' }}">
                                <a class="pc-link" href="{{ route('stop.index') }}">Stops</a>
                            </li>
                        </ul>
                    </li>

                    <li
                        class="pc-item pc-hasmenu
                        {{ Str::is('route-pattern.*', Route::currentRouteName()) ||
                        Str::is('route-direction.*', Route::currentRouteName()) ||
                        Str::is('route-direction-stop.*', Route::currentRouteName())
                            ? 'active pc-trigger'
                            : '' }}">
                        <a href="javascript:void(0)" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-package"></i></span>
                            <span class="pc-mtext">Routes</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            <li
                                class="pc-item {{ Str::is('route-pattern.*', Route::currentRouteName()) ? 'active' : '' }}">
                                <a class="pc-link" href="{{ route('route-pattern.index') }}">Routes</a>
                            </li>
                            <li
                                class="pc-item {{ Str::is('route-direction.*', Route::currentRouteName()) || Str::is('route-direction-stop.*', Route::currentRouteName()) ? 'active' : '' }}">
                                <a class="pc-link" href="{{ route('route-direction.index') }}">Directions</a>
                            </li>
                        </ul>
                    </li>

                    <li
                        class="pc-item pc-hasmenu
                        {{ Str::is('operator.*', Route::currentRouteName()) ||
                        Str::is('bus.*', Route::currentRouteName()) ||
                        Str::is('trip-schedule.*', Route::currentRouteName())
                            ? 'active pc-trigger'
                            : '' }}">
                        <a href="javascript:void(0)" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-package"></i></span>
                            <span class="pc-mtext">Buses</span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item {{ Str::is('operator.*', Route::currentRouteName()) ? 'active' : '' }}">
                                <a class="pc-link" href="{{ route('operator.index') }}">Operators</a>
                            </li>
                            <li class="pc-item {{ Str::is('bus.*', Route::currentRouteName()) ? 'active' : '' }}">
                                <a class="pc-link" href="{{ route('bus.index') }}">Bus</a>
                            </li>
                            <li
                                class="pc-item {{ Str::is('trip-schedule.*', Route::currentRouteName()) ? 'active' : '' }}">
                                <a class="pc-link" href="{{ route('trip-schedule.index') }}">Trips</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="pc-header d-none">
        <div class="header-wrapper">
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <li class="pc-h-item header-mobile-collapse">
                        <a href="#" class="pc-head-link head-link-secondary ms-0" id="sidebar-hide">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                    <li class="pc-h-item pc-sidebar-popup">
                        <a href="#" class="pc-head-link head-link-secondary ms-0" id="mobile-collapse">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                    <li class="dropdown pc-h-item d-inline-flex d-md-none">
                        <a class="pc-head-link head-link-secondary dropdown-toggle arrow-none m-0"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <i class="ti ti-search"></i>
                        </a>
                        <div class="dropdown-menu pc-h-dropdown drp-search">
                            <form class="px-3">
                                <div class="mb-0 d-flex align-items-center">
                                    <i data-feather="search"></i>
                                    <input type="search" class="form-control border-0 shadow-none"
                                        placeholder="Search here. . ." />
                                </div>
                            </form>
                        </div>
                    </li>
                    <li class="pc-h-item d-none d-md-inline-flex">
                        <form class="header-search">
                            <i data-feather="search" class="icon-search"></i>
                            <input type="search" class="form-control" placeholder="Search here. . ." />
                            <button class="btn btn-light-secondary btn-search">
                                <i class="ti ti-adjustments-horizontal"></i>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item header-user-profile">
                        <a class="pc-head-link head-link-primary dropdown-toggle arrow-none me-0"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <img src="{{ asset('img/user.png') }}" alt="user-image" class="user-avtar" />
                            <span><i class="ti ti-settings"></i></span>
                        </a>

                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header">
                                <h4>Hi, <span class="small text-muted">{{ Auth::user()->name }}</span></h4>
                                <p class="text-muted text-capitalize">Admin</p>
                                <hr />

                                <div class="profile-notification-scroll position-relative"
                                    style="max-height: calc(100vh - 280px)">
                                    <a href="{{ route('backend.profile.edit') }}" class="dropdown-item">
                                        <i class="ti ti-settings"></i>
                                        <span>Account Settings</span>
                                    </a>

                                    <form method="POST" action="{{ route('backend.logout') }}">
                                        @csrf

                                        <a href="javascript:void(0);" class="dropdown-item"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                            <i class="ti ti-logout"></i>
                                            <span>Logout</span>
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <div class="pc-container">
        <div class="pc-content">
            @yield('content')
        </div>
    </div>

    <div id="backdrop-loader" class="backdrop-loader">
        <div class="spinner"></div>
    </div>

    {{-- Global CRUD Modal --}}
    <div class="modal fade" id="crudModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 id="crudTitle"></h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="crudForm">
                    <div class="modal-body" id="crudBody"></div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Required Js -->
    <script src="{{ asset('assets-admin/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets-admin/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets-admin/js/plugins/bootstrap.min.js') }}"></script>
    {{-- <script src="{{ asset('assets-admin/js/fonts/custom-font.js') }}"></script> --}}
    <script src="{{ asset('assets-admin/js/script.js') }}"></script>
    <script src="{{ asset('assets-admin/js/theme.js') }}"></script>
    <script src="{{ asset('assets-admin/js/plugins/feather.min.js') }}"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="{{ asset('assets-admin/js/custom.js') }}"></script>
    <script src="{{ asset('assets-admin/js/crud.js') }}"></script>

    <script>
        window.logoPaths = {
            dark: "{{ asset('img/logo.png') }}",
            light: "{{ asset('img/logo.png') }}"
        };

        preset_change('preset-1');
    </script>

    @stack('scripts')

    <script>
        $(document).ready(function() {
            const dataTableWrapper = $('#dataTable_wrapper .dataTables_filter');
            $('.selectFilter').insertBefore(dataTableWrapper).css('display', 'inline-block');
            if ($('.add-btn').length) {
                $('.add-btn').insertAfter(dataTableWrapper).css('display', 'inline-block');
            }

            $('#dataTable_wrapper .dataTables_filter').parent().css({
                display: 'flex',
                justifyContent: 'flex-end',
                gap: '10px',
                alignItems: 'center'
            });
        });

        function toggleActive(url) {
            const token = "{{ csrf_token() }}";

            updateStatus(url, token);
        }

        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    </script>
</body>

</html>

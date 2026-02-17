@extends('layouts.web')

@push('styles')
    <style>
        :root {
            --primary-grad: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
        }

        body {
            background: #f0f2f5;
            font-family: 'Inter', sans-serif;
        }

        /* --- THE FIX: Dropdown Layering --- */
        .search-section-wrapper {
            position: relative;
            z-index: 1050;
        }

        .search-header {
            background: var(--primary-grad);
            padding: 40px 0 80px;
            margin-bottom: -60px;
            color: white;
            border-radius: 0 0 2rem 2rem;
        }

        .search-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: visible !important;
            /* Critical for dropdowns */
        }

        .search-card .card-body {
            overflow: visible !important;
        }

        /* --- Choices.js Layering --- */
        .choices {
            z-index: 1000 !important;
            overflow: visible !important;
        }

        .choices__list--dropdown {
            z-index: 9999 !important;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2) !important;
            background-color: #fff !important;
        }

        /* --- Bus Cards (Mobile Optimized) --- */
        .results-section {
            position: relative;
            z-index: 1;
        }

        .bus-card {
            border: none;
            border-radius: 1rem;
            transition: transform 0.2s;
            background: white;
            margin-bottom: 12px;
        }

        .bus-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        }

        .time-display {
            font-size: 1.1rem;
            font-weight: 800;
        }

        .route-line {
            position: relative;
            height: 2px;
            background: #e9ecef;
            flex-grow: 1;
            margin: 0 10px;
        }

        .route-line::before,
        .route-line::after {
            content: '';
            position: absolute;
            top: -4px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .route-line::before {
            left: 0;
            background: #0d6efd;
        }

        .route-line::after {
            right: 0;
            background: #198754;
        }

        /* --- Buttons --- */
        .btn-swap-creative {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: white;
            border: 1px solid #e0e0e0;
            color: #0d6efd;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-swap-creative:hover {
            transform: rotate(180deg);
        }

        @media (max-width: 767.98px) {
            .journey-inputs-container {
                padding-left: 20px;
                position: relative;
            }

            .mobile-connector {
                position: absolute;
                left: 4px;
                top: 20px;
                bottom: 20px;
                width: 2px;
                background: #dee2e6;
            }

            .stop-dot {
                position: absolute;
                left: -20px;
                top: 50%;
                transform: translateY(-50%);
                width: 10px;
                height: 10px;
                border-radius: 50%;
                background: white;
                border: 2px solid #0d6efd;
            }

            .stop-dot.end {
                border-color: #198754;
            }

            .btn-swap-floating {
                position: absolute;
                right: 5px;
                top: 50%;
                transform: translateY(-50%);
                width: 38px;
                height: 38px;
                border-radius: 50%;
                background: white;
                border: 1px solid #eee;
                color: #0d6efd;
                z-index: 10000;
            }
        }
    </style>
@endpush

@section('content')
    <div class="search-header text-center">
        <div class="container">
            <h2 class="fw-bold">Bus Timings</h2>
            <p class="opacity-75">Find your next journey</p>
        </div>
    </div>

    <div class="container pb-5">
        <div class="search-section-wrapper">
            <form method="GET" action="{{ route('home') }}" id="bus-search-form">
                <div class="card search-card mb-4">
                    <div class="card-body p-3 p-md-4">

                        {{-- DESKTOP VIEW --}}
                        <div class="d-none d-md-flex row g-3 align-items-center" id="desktop-inputs">
                            <div class="col">
                                <label class="small fw-bold text-muted mb-1">From</label>
                                <select name="from_stop_id" class="choice-select" id="from-desktop"
                                    data-selected-id="{{ $fromStop->id ?? '' }}"
                                    data-selected-name="{{ $fromStop->name ?? '' }}"
                                    data-selected-code="{{ $fromStop->code ?? '' }}"></select>
                            </div>
                            <div class="col-auto pt-4">
                                <button type="button" class="btn btn-swap-creative shadow-sm" data-view="desktop">
                                    <i class="bi bi-arrow-left-right"></i>
                                </button>
                            </div>
                            <div class="col">
                                <label class="small fw-bold text-muted mb-1">To</label>
                                <select name="to_stop_id" class="choice-select" id="to-desktop"
                                    data-selected-id="{{ $toStop->id ?? '' }}"
                                    data-selected-name="{{ $toStop->name ?? '' }}"
                                    data-selected-code="{{ $toStop->code ?? '' }}"></select>
                            </div>
                            <div class="col-auto pt-4">
                                <button type="submit" class="btn btn-primary px-4 fw-bold h-100">SEARCH</button>
                            </div>
                        </div>

                        {{-- MOBILE VIEW --}}
                        <div class="d-block d-md-none" id="mobile-inputs">
                            <div class="journey-inputs-container">
                                <div class="d-flex flex-column gap-2">
                                    <div class="position-relative">
                                        <span class="stop-dot start"></span>
                                        <select name="from_stop_id" class="choice-select" id="from-mobile"
                                            data-selected-id="{{ $fromStop->id ?? '' }}"
                                            data-selected-name="{{ $fromStop->name ?? '' }}"
                                            data-selected-code="{{ $fromStop->code ?? '' }}"></select>
                                    </div>
                                    <div class="mobile-connector"></div>
                                    <div class="position-relative">
                                        <span class="stop-dot end"></span>
                                        <select name="to_stop_id" class="choice-select" id="to-mobile"
                                            data-selected-id="{{ $toStop->id ?? '' }}"
                                            data-selected-name="{{ $toStop->name ?? '' }}"
                                            data-selected-code="{{ $toStop->code ?? '' }}"></select>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-swap-floating shadow-sm" data-view="mobile">
                                    <i class="bi bi-arrow-down-up"></i>
                                </button>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-3 mt-3 fw-bold shadow-sm">FIND
                                BUS</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- RESULTS --}}
        @if (request()->filled(['from_stop_id', 'to_stop_id']))
            <div class="results-section">
                <div class="row g-1">
                    @forelse ($buses as $bus)
                        <div class="col-12">
                            <a href="{{ route('trip.show', ['id' => $bus->trip_id, 'from_stop_id' => request('from_stop_id'), 'to_stop_id' => request('to_stop_id')]) }}"
                                class="text-decoration-none text-dark">
                                <div class="bus-card card shadow-sm">
                                    <div class="card-body p-3">
                                        {{-- Mobile Bus Header --}}
                                        <div class="d-flex align-items-center mb-2 d-md-none border-bottom pb-2">
                                            <div class="bg-primary bg-opacity-10 p-2 rounded me-2 text-primary">
                                                <i class="bi bi-bus-front"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $bus->bus_name }}</div>
                                                <small class="text-muted">{{ $bus->bus_number }}</small>
                                            </div>
                                        </div>

                                        <div class="row align-items-center">
                                            {{-- Desktop Bus Info --}}
                                            <div class="col-md-3 d-none d-md-block">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 p-2 rounded me-3 text-primary"><i
                                                            class="bi bi-bus-front fs-4"></i></div>
                                                    <div>
                                                        <div class="fw-bold lh-1 mb-1">{{ $bus->bus_name }}</div>
                                                        <small class="text-muted">{{ $bus->bus_number }}</small>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Times Timeline --}}
                                            <div class="col-12 col-md-7">
                                                <div class="d-flex align-items-center justify-content-between px-md-4">
                                                    <div class="text-primary time-display">
                                                        {{ \Carbon\Carbon::parse($bus->departure_time)->format('h:i A') }}
                                                    </div>
                                                    <div class="route-line"></div>
                                                    <div class="text-success time-display">
                                                        {{ \Carbon\Carbon::parse($bus->arrival_time)->format('h:i A') }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2 text-end d-none d-md-block">
                                                <span
                                                    class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">Details</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-emoji-frown fs-1 d-block mb-3"></i>
                            <h5>No buses found for this route.</h5>
                            <p>Try swapping the directions or searching for another stop.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        const choiceInstances = {};

        // 1. Initialize Choices with Search
        document.querySelectorAll('.choice-select').forEach(select => {
            const choices = new Choices(select, {
                searchEnabled: true,
                shouldSort: false,
                removeItemButton: true
            });
            choiceInstances[select.id] = choices;

            if (select.dataset.selectedId) {
                choices.setChoices([{
                    value: select.dataset.selectedId,
                    label: `${select.dataset.selectedName} (${select.dataset.selectedCode})`,
                    selected: true
                }], 'value', 'label', true);
            }

            let debounceTimer;
            select.addEventListener('search', function(e) {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(async () => {
                    const val = e.detail.value;
                    if (val.length < 2) return;
                    const res = await fetch(`/stops?q=${val}`);
                    const data = await res.json();
                    choices.clearChoices();
                    choices.setChoices(data.map(i => ({
                        value: i.id,
                        label: `${i.name} (${i.code})`
                    })), 'value', 'label', true);
                }, 300);
            });
        });

        // 2. Prevent Duplicate Params on Submit
        const form = document.getElementById('bus-search-form');
        form.addEventListener('submit', function(e) {
            const isMobile = window.innerWidth < 768;
            if (isMobile) {
                document.getElementById('from-desktop').removeAttribute('name');
                document.getElementById('to-desktop').removeAttribute('name');
            } else {
                document.getElementById('from-mobile').removeAttribute('name');
                document.getElementById('to-mobile').removeAttribute('name');
            }
        });

        // 3. Swap Functionality
        document.querySelectorAll('[data-view]').forEach(btn => {
            btn.addEventListener('click', function() {
                const view = this.dataset.view;
                const f = choiceInstances[`from-${view}`];
                const t = choiceInstances[`to-${view}`];
                const fVal = f.getValue();
                const tVal = t.getValue();

                f.removeActiveItems();
                t.removeActiveItems();
                if (tVal) f.setChoices([{
                    value: tVal.value,
                    label: tVal.label,
                    selected: true
                }], 'value', 'label', true);
                if (fVal) t.setChoices([{
                    value: fVal.value,
                    label: fVal.label,
                    selected: true
                }], 'value', 'label', true);
            });
        });
    </script>
@endpush

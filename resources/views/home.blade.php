@extends('layouts.web')

@push('styles')
    <style>
        body {
            background: #f5f7fa;
        }

        .bus-card {
            border-left: 5px solid #0d6efd;
            transition: .2s;
        }

        .bus-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, .08);
        }

        .time-badge {
            font-size: 1rem;
            font-weight: 600;
        }
    </style>

    <style>
        .btn-swap-creative {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #e0e0e0;
            color: #0d6efd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            /* Centers the circle on mobile */
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            cursor: pointer;
        }

        .btn-swap-creative:hover {
            background-color: #f8f9fa;
            color: #0a58ca;
            /* transform: rotate(180deg); */
            /* Creative spin */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-color: #0d6efd;
        }

        .btn-swap-creative:active {
            transform: rotate(180deg);
            /* Squish effect when clicked */
        }

        .choice-select,
        .choices {
            width: 100% !important;
            margin-bottom: 0 !important;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">

        <div class="text-center mb-4">
            <h3 class="fw-bold">Bus Timings</h3>
            <small class="text-muted">Find buses between stops</small>
        </div>

        {{-- SEARCH FORM --}}
        <form method="GET" action="{{ route('home') }}">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-2">

                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-md">
                            <select id="from-stop" name="from_stop_id" class="form-select choice-select"
                                data-selected-id="{{ $fromStop->id ?? '' }}"
                                data-selected-name="{{ $fromStop->name ?? '' }}"
                                data-selected-code="{{ $fromStop->code ?? '' }}" required></select>
                        </div>

                        <div class="col-12 col-md-auto text-center d-grid">
                            <button type="button" class="btn btn-swap-creative" id="swap-stops" title="Swap Directions">
                                <i class="bi bi-arrow-left-right"></i>
                            </button>
                        </div>

                        <div class="col-12 col-md">
                            <select id="to-stop" name="to_stop_id" class="form-select choice-select"
                                data-selected-id="{{ $toStop->id ?? '' }}" data-selected-name="{{ $toStop->name ?? '' }}"
                                data-selected-code="{{ $toStop->code ?? '' }}" required></select>
                        </div>

                        <div class="col-12 col-md-auto d-grid">
                            <button class="btn btn-primary">
                                <i class="bi bi-search"></i> Find Bus
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </form>

        {{-- RESULTS --}}
        @if (request()->filled(['from_stop_id', 'to_stop_id']))
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Available Buses</h5>
                <small class="text-muted">Sorted by ETA</small>
            </div>

            @forelse ($buses as $bus)
                <a href="{{ route('trip.show', [
                    'id' => $bus->trip_id,
                    'from_stop_id' => request('from_stop_id') ?? 1,
                    'to_stop_id' => request('to_stop_id') ?? 28,
                ]) }}"
                    class="text-decoration-none text-dark">

                    <div class="bus-card card mb-2">
                        <div class="card-body" style="padding: 7px;">

                            <div class="row align-items-center">

                                <div class="d-flex flex-wrap justify-content-between align-items-center w-100">

                                    <div class="flex-grow-1 me-3">
                                        <h6 class="fw-bold mb-0 d-inline-block">{{ $bus->bus_name }}</h6>
                                        <small class="text-muted ms-1">{{ $bus->bus_number }}</small>
                                    </div>

                                    {{-- <div class="text-end">
                                        <span class="badge bg-secondary text-white">
                                            Ordinary
                                        </span>
                                    </div> --}}

                                </div>

                                <div class="text-nowrap mt-1">
                                    <span class="time-badge text-primary fw-bold">
                                        {{ \Carbon\Carbon::parse($bus->departure_time)->format('h:i A') }}
                                    </span>
                                    <span class="mx-2 text-muted">→</span>
                                    <span class="time-badge text-success fw-bold">
                                        {{ \Carbon\Carbon::parse($bus->arrival_time)->format('h:i A') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty

                <div class="text-center py-5 text-muted">
                    <h6>No buses found</h6>
                    <small>Try different stops</small>
                </div>
            @endforelse
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        const choiceInstances = {};
        const selects = {};

        document.querySelectorAll('.choice-select').forEach(select => {

            const choices = new Choices(select, {
                searchEnabled: true,
                searchChoices: false,
                placeholder: true,
                placeholderValue: 'Type to search stop...',
                shouldSort: false,
                removeItemButton: true
            });

            choiceInstances[select.name] = choices;

            // Store reference by ID
            if (select.id) {
                selects[select.id] = choices;
            }

            /* ---------------- PRESELECT VALUE ---------------- */

            const selectedId = select.dataset.selectedId;
            const selectedName = select.dataset.selectedName;
            const selectedCode = select.dataset.selectedCode;

            if (selectedId) {
                choices.setChoices([{
                    value: selectedId,
                    label: `${selectedName} (${selectedCode})`,
                    selected: true
                }], 'value', 'label', true);
            }

            /* ---------- AUTO-FOCUS TO STOP ---------- */

            if (select.id === 'from-stop') {
                select.addEventListener('choice', function() {
                    const toChoices = selects['to-stop'];

                    if (!toChoices) return;

                    // Check if "To Stop" already has a value
                    const toValue = toChoices.getValue(true); // returns value or null

                    if (toValue) {
                        // Do nothing if already selected
                        return;
                    }

                    // Otherwise focus and open dropdown
                    toChoices.showDropdown();
                    toChoices.input.element.focus();
                });
            }


            /* ---------------- AJAX SEARCH ---------------- */

            let debounceTimer;

            select.addEventListener('search', function(e) {

                clearTimeout(debounceTimer);

                debounceTimer = setTimeout(async () => {

                    const value = e.detail.value;

                    if (value.length < 2) return;

                    const response = await fetch(`/stops?q=${value}`);
                    const data = await response.json();

                    choices.clearChoices();

                    choices.setChoices(
                        data.map(item => ({
                            value: item.id,
                            label: `${item.name} ${item.locality ? `(${item.locality})` : ''} (${item.code}) - ${item.city.name}`
                        })),
                        'value',
                        'label',
                        true
                    );

                }, 400); // debounce
            });

        });

        document.getElementById('swap-stops').addEventListener('click', function() {
            const fromInstance = choiceInstances['from_stop_id'];
            const toInstance = choiceInstances['to_stop_id'];

            const fromData = fromInstance.getValue();
            const toData = toInstance.getValue();

            if (!fromData && !toData) return;

            fromInstance.removeActiveItems();
            toInstance.removeActiveItems();

            if (toData) {
                fromInstance.setChoices([{
                    value: toData.value,
                    label: toData.label,
                    selected: true
                }], 'value', 'label', true);
            }

            if (fromData) {
                toInstance.setChoices([{
                    value: fromData.value,
                    label: fromData.label,
                    selected: true
                }], 'value', 'label', true);
            }

            // Auto-submit form after short delay
            // setTimeout(() => {
            //     document.querySelector('form').submit();
            // }, 500);
        });
    </script>
@endpush

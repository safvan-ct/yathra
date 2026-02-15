<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Bus Timings</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

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
</head>

<body>

    <div class="container py-4">

        <div class="text-center mb-4">
            <h3 class="fw-bold">Bus Timings</h3>
            <small class="text-muted">Find buses between stops</small>
        </div>

        {{-- SEARCH FORM --}}
        <form method="GET" action="{{ route('home') }}">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-2">

                    <div class="row g-2">
                        <div class="col-md-5">
                            <select name="from_stop_id" class="form-select choice-select"
                                data-selected-id="{{ $fromStop->id ?? '' }}"
                                data-selected-name="{{ $fromStop->name ?? '' }}"
                                data-selected-code="{{ $fromStop->code ?? '' }}" required></select>
                        </div>

                        <div class="col-md-5">
                            <select name="to_stop_id" class="form-select choice-select"
                                data-selected-id="{{ $toStop->id ?? '' }}"
                                data-selected-name="{{ $toStop->name ?? '' }}"
                                data-selected-code="{{ $toStop->code ?? '' }}" required></select>
                        </div>

                        <div class="col-md-2 d-grid">
                            <button class="btn btn-primary fw-semibold">Search</button>
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

    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <script>
        document.querySelectorAll('.choice-select').forEach(select => {

            const choices = new Choices(select, {
                searchEnabled: true,
                searchChoices: false,
                placeholder: true,
                placeholderValue: 'Type to search stop...',
                shouldSort: false,
                removeItemButton: true
            });

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
    </script>

</body>

</html>

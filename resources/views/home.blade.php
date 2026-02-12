<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Bus Timings</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
            font-size: 1.2rem;
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
                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-5">
                            <label class="form-label">From Stop</label>
                            <select class="form-select" name="from_stop_id" required>
                                <option value="">Select Stop</option>
                                @foreach ($stops as $stop)
                                    <option value="{{ $stop->id }}"
                                        {{ request('from_stop_id') == $stop->id ? 'selected' : '' }}>
                                        {{ $stop->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label">To Stop</label>
                            <select class="form-select" name="to_stop_id" required>
                                <option value="">Select Stop</option>
                                @foreach ($stops as $stop)
                                    <option value="{{ $stop->id }}"
                                        {{ request('to_stop_id') == $stop->id ? 'selected' : '' }}>
                                        {{ $stop->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 d-grid">
                            <label class="form-label invisible">Search</label>
                            <button class="btn btn-primary fw-semibold">
                                Search
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

                    <div class="bus-card card mb-3">
                        <div class="card-body">

                            <div class="row align-items-center">

                                <div class="col-md-4">
                                    <h6 class="fw-bold mb-1">{{ $bus->bus_name }}</h6>
                                    <small class="text-muted">
                                        {{ $bus->bus_number }} • {{ $bus->operator }}
                                    </small>
                                </div>

                                <div class="col-md-6 text-md-center my-3 my-md-0">
                                    <span class="time-badge text-primary">
                                        {{ \Carbon\Carbon::parse($bus->departure_time)->format('h:i A') }}
                                    </span>
                                    <span class="mx-2 text-muted">→</span>
                                    <span class="time-badge text-success">
                                        {{ \Carbon\Carbon::parse($bus->arrival_time)->format('h:i A') }}
                                    </span>
                                </div>

                                <div class="col-md-2 text-md-end">
                                    <span class="badge bg-info text-dark">
                                        {{ ucfirst($bus->service_type) }}
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

</body>

</html>

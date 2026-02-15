<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-blue: #0d6efd;
            --past-grey: #adb5bd;
            --active-green: #198754;
        }

        body {
            background-color: #f4f7f6;
            font-family: 'Inter', system-ui;
            padding-bottom: 50px;
        }

        /* Header Card */
        .trip-summary {
            background: #fff;
            padding: 20px;
            border-bottom: 2px solid #eee;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Timeline Logic */
        .timeline-wrapper {
            position: relative;
            padding: 20px 15px;
        }

        /* The Main Vertical Line */
        .timeline-line {
            position: absolute;
            left: 31px;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #dee2e6;
            z-index: 1;
        }

        /* Progress Fill (Changes based on bus location) */
        .timeline-progress {
            position: absolute;
            left: 31px;
            top: 0;
            width: 4px;
            background: var(--primary-blue);
            z-index: 2;
            height: 35%;
            /* This height should be dynamic based on current stop index */
        }

        .stop-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            position: relative;
            z-index: 3;
        }

        /* The Node (Dot) */
        .stop-node {
            width: 35px;
            height: 35px;
            background: white;
            border: 4px solid #dee2e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        /* Completed Stop */
        .stop-row.completed .stop-node {
            border-color: var(--primary-blue);
            color: var(--primary-blue);
        }

        /* Active/Current Stop */
        .stop-row.active .stop-node {
            border-color: rgb(228 5 5);
            /* background: var(--active-green); */
            color: rgb(228 5 5);
            /* box-shadow: 0 0 0 5px rgba(25, 135, 84, 0.2); */
        }

        /* Info Card */
        /* .stop-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            width: 100%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid transparent;
        } */

        .stop-row.active .stop-card {
            border-color: rgb(228 5 5);
        }

        .stop-name {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0px;
            display: flex;
            /* justify-content: space-between; */
        }

        .distance-label {
            font-size: 0.75rem;
            color: #6c757d;
            font-weight: 500;
        }

        /* Time Display */
        .time-group {
            border-top: 1px solid #f8f9fa;
            margin-top: 0px;
            padding-top: 0px;
            display: flex;
            gap: 20px;
        }

        .time-item {
            line-height: 1;
        }

        .time-label {
            font-size: 0.6rem;
            text-transform: uppercase;
            color: #999;
            display: none;
        }

        /* Actions */
        .stop-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-action {
            padding: 4px 12px;
            font-size: 0.8rem;
            border-radius: 20px;
            text-decoration: none;
            border: 1px solid #eee;
            color: #555;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: #198754;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 6px rgba(25, 135, 84, 0);
            }

            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(25, 135, 84, 0);
            }
        }

        .letter-spacing-1 {
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body>

    <div class="trip-summary shadow-sm py-3 px-3 bg-white border-bottom sticky-top">
        <div class="d-flex align-items-center justify-content-between">

            <div class="header-left" style="flex: 1;">
                <form method="GET" action="{{ route('home') }}" class="m-0">
                    <input type="hidden" name="from_stop_id" value="{{ request('from_stop_id') }}">
                    <input type="hidden" name="to_stop_id" value="{{ request('to_stop_id') }}">
                    <button class="btn btn-secondary btn-sm border fw-semibold d-flex align-items-center gap-1">
                        <i class="bi bi-chevron-left"></i> Back
                    </button>
                </form>
            </div>

            <div class="header-center text-center" style="flex: 2;">
                <h6 class="mb-0 fw-bold text-dark text-uppercase letter-spacing-1">
                    {{ $trip->bus->bus_name ?? 'Downtown Express' }}
                </h6>
                <div class="d-flex justify-content-center align-items-center gap-2 mt-1">
                    <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-3">
                        {{ $trip->bus->bus_number ?? 'A1' }}
                    </span>
                </div>
            </div>

            <div class="header-right text-end" style="flex: 1;">
                <small class="text-muted d-block" style="font-size: 0.7rem; text-transform: uppercase;">Status</small>
                <span class="text-success fw-bold d-flex align-items-center justify-content-end gap-1"
                    style="font-size: 0.85rem;">
                    <span class="pulse-dot"></span> Live
                </span>
            </div>

        </div>

        <div class="text-danger small d-block m-0 text-center">
            *Times are estimated and subject to change
        </div>
    </div>

    <div class="timeline-wrapper">
        <div class="timeline-line"></div>
        <div class="timeline-progress" style="height: 100%;"></div>

        @foreach ($stops as $index => $stop)
            @if (!$stop->is_in_between)
                @continue;
            @endif

            <div class="stop-row {{ $stop->is_selected_segment ? 'active' : 'completed' }}">
                <div class="stop-node">
                    @if ($stop->is_selected_segment)
                        <i class="bi bi-bus-front"></i>
                    @elseif($stop->is_in_between)
                        <i class="bi bi-check-lg"></i>
                    @else
                        <small class="text-muted">{{ $index + 1 }}</small>
                    @endif
                </div>

                <div class="stop-card">
                    <div class="stop-name">
                        {{ $stop->code }}:&nbsp;<small class="text-muted">({{ $stop->name }})</small>
                    </div>
                    {{-- <div class="distance-label">
                        <i class="bi bi-geo-alt-fill"></i> {{ $index * 4.2 }} km from origin
                    </div> --}}

                    <div class="time-group">
                        @if (!$loop->first)
                            <div class="time-item">
                                <span class="time-label small">Arrival (ETA)</span>
                                <span class="fw-bold small text-success">{{ $stop->arrival_time }}</span>
                            </div>
                        @endif

                        @if (!$loop->last)
                            <div class="time-item">
                                <span class="time-label small">Departure (ETD)</span>
                                <span class="fw-bold small text-danger">
                                    {{ $loop->first ? $stop->trip_start_time : $stop->departure_time }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- <div class="stop-actions">
                        <a href="#" class="btn-action"><i class="bi bi-map"></i> View Map</a>
                        <a href="#" class="btn-action"><i class="bi bi-telephone"></i> Station</a>
                    </div> --}}
                </div>
            </div>
        @endforeach
    </div>

</body>

</html>

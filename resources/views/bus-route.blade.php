@extends('layouts.web')

@push('styles')
    <style>
        :root {
            --bus-primary: #0d6efd;
            --bus-success: #198754;
            --bus-danger: #dc3545;
            --bg-body: #f4f6f9;
        }

        body {
            background-color: var(--bg-body);
            font-family: 'Inter', sans-serif;
            padding-bottom: 40px;
        }

        /* --- Header --- */
        .trip-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
            color: white;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* --- Timeline Structure --- */
        .timeline-wrapper {
            position: relative;
            padding: 25px 15px;
            max-width: 600px;
            margin: 0 auto;
        }

        .timeline-line {
            position: absolute;
            left: 31px;
            /* Centered with the 32px node */
            top: 0;
            bottom: 0;
            width: 3px;
            background: #dee2e6;
            z-index: 1;
        }

        .timeline-progress {
            position: absolute;
            left: 31px;
            top: 0;
            width: 3px;
            background: var(--bus-primary);
            z-index: 2;
            transition: height 1s linear;
        }

        /* --- Stop Block --- */
        .stop-block {
            display: flex;
            align-items: flex-start;
            /* Aligns dot with the first line of text */
            gap: 15px;
            margin-bottom: 20px;
            position: relative;
            z-index: 5;
        }

        .node {
            width: 32px;
            height: 32px;
            background: white;
            border: 2px solid #ced4da;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 5px;
            /* Centers dot with the card top */
        }

        .stop-block.active .node {
            background: var(--bus-danger);
            border-color: var(--bus-danger);
            color: white;
            box-shadow: 0 0 10px rgba(220, 53, 69, 0.4);
        }

        /* --- Improved Stop Card --- */
        .stop-card {
            background: white;
            border-radius: 12px;
            padding: 12px 15px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-grow: 1;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .stop-info {
            flex: 1;
            /* Takes all available space */
            padding-right: 10px;
        }

        .stop-info h6 {
            font-weight: 700;
            margin: 0;
            font-size: 0.9rem;
            color: #222;
            /* Fix: Allow text to wrap */
            word-wrap: break-word;
            line-height: 1.4;
        }

        .stop-info small {
            font-size: 0.7rem;
            color: #888;
            display: block;
            margin-top: 2px;
        }

        .time-box {
            text-align: right;
            min-width: 70px;
            /* Ensures times don't jump around */
            border-left: 1px solid #f0f0f0;
            padding-left: 12px;
        }

        .time-entry {
            font-family: 'Monaco', monospace;
            font-weight: 800;
            font-size: 0.75rem;
            line-height: 1.5;
        }

        .t-arr {
            color: var(--bus-success);
        }

        .t-dep {
            color: var(--bus-danger);
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background: #28a745;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4);
            }

            70% {
                box-shadow: 0 0 0 8px rgba(40, 167, 69, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
            }
        }
    </style>
@endpush

@section('content')
    <div class="trip-header shadow-sm">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="{{ route('home', ['from_stop_id' => $fromStopId, 'to_stop_id' => $toStopId]) }}" class="text-white">
                <i class="bi bi-chevron-left fs-5"></i>
            </a>
            <div class="text-center">
                <div class="fw-bold small text-uppercase">{{ $trip->bus->bus_name }}</div>
                <div class="badge bg-white text-primary rounded-pill" style="font-size: 0.6rem;">{{ $trip->bus->bus_number }}
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 text-white small fw-bold">
                <span class="pulse-dot"></span> LIVE
            </div>
        </div>
    </div>

    <div class="container">
        <div class="timeline-wrapper">
            <div class="timeline-line"></div>
            <div class="timeline-progress" id="progress-bar" data-start="{{ $stops->first()->trip_start_time }}"
                data-end="{{ $stops->last()->arrival_time }}"></div>

            @foreach ($stops as $index => $stop)
                @if (!$stop->is_in_between)
                    @continue
                @endif

                @php
                    $isActive = $stop->is_selected_segment;
                    $isPassed = !$isActive && \Carbon\Carbon::parse($stop->arrival_time)->isPast();
                @endphp

                <div class="stop-block {{ $isActive ? 'active' : ($isPassed ? 'completed' : 'upcoming') }}">
                    <div class="node">
                        @if ($isActive)
                            <i class="bi bi-bus-front-fill"></i>
                        @elseif($isPassed)
                            <i class="bi bi-check-lg text-primary"></i>
                        @else
                            <div style="width: 6px; height: 6px; background: #ccc; border-radius: 50%;"></div>
                        @endif
                    </div>

                    <div class="stop-card">
                        <div class="stop-info">
                            <h6>{{ $stop->name }}</h6>
                            <small>#{{ $stop->code }}</small>
                        </div>

                        <div class="time-box">
                            @if (!$loop->first)
                                <div class="time-entry t-arr">{{ $stop->arrival_time }}</div>
                            @endif
                            @if (!$loop->last)
                                <div class="time-entry t-dep">
                                    {{ $loop->first ? $stop->trip_start_time : $stop->departure_time }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Progress bar logic
        function syncTimeline() {
            const bar = document.getElementById('progress-bar');
            if (!bar) return;

            const parse = (s) => {
                const [t, m] = s.split(' ');
                let [h, min] = t.split(':');
                if (h === '12') h = '00';
                if (m === 'PM') h = parseInt(h) + 12;
                const d = new Date();
                d.setHours(h, min, 0, 0);
                return d;
            };

            const start = parse(bar.dataset.start);
            const end = parse(bar.dataset.end);
            const now = new Date();

            let pct = ((now - start) / (end - start)) * 100;
            bar.style.height = Math.max(0, Math.min(100, pct)) + '%';
        }

        setInterval(syncTimeline, 30000);
        syncTimeline();

        // Choices.js Configuration (If you are using it for search on this page too)
        // Ensure that Choices is initialized with the dropdown attached to body if possible
        // but the CSS above usually solves it.
    </script>
@endpush

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
            /* Matches node center */
            top: 0;
            bottom: 0;
            width: 3px;
            background: #dee2e6;
            z-index: 1;
        }

        .timeline-progress {
            position: absolute;
            left: 31px;
            width: 3px;
            z-index: 2;
            border-radius: 4px;
            transition: height 0.4s ease-out;
            display: none;
            /* Shown via JS */
        }

        /* --- Stop Block --- */
        .stop-block {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 25px;
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
            transition: all 0.3s ease;
        }

        /* States */
        .stop-block.active .node {
            background: var(--bus-danger);
            border-color: var(--bus-danger);
            color: white;
            box-shadow: 0 0 10px rgba(220, 53, 69, 0.4);
        }

        .stop-block.completed .node {
            border-color: var(--bus-success);
            color: var(--bus-success);
        }

        .stop-block.completed .stop-card {
            background: #f8fff9;
            border-color: rgba(25, 135, 84, 0.1);
        }

        /* --- Card Details --- */
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

        .stop-info h6 {
            font-weight: 700;
            margin: 0;
            font-size: 0.9rem;
            color: #222;
        }

        .time-box {
            text-align: right;
            min-width: 80px;
            border-left: 1px solid #f0f0f0;
            padding-left: 12px;
        }

        .time-entry {
            font-family: 'Monaco', monospace;
            font-weight: 800;
            font-size: 0.75rem;
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
            <a href="{{ route('home', ['from' => $from, 'to' => $to]) }}" class="text-white">
                <i class="bi bi-chevron-left fs-5"></i>
            </a>
            <div class="text-center">
                <div class="fw-bold small text-uppercase">{{ $trip->bus->bus_name }}</div>
                <div class="badge bg-white text-primary rounded-pill" style="font-size: 0.6rem;">
                    {{ $trip->bus->bus_number }}
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
            <div class="timeline-progress" id="progress-bar"></div>

            @foreach ($stops as $index => $stop)
                @if (!$stop->is_in_between)
                    @continue
                @endif

                @php
                    $now = \Carbon\Carbon::now();
                    $arrivalTime = \Carbon\Carbon::today()->setTimeFromTimeString($stop->arrival_time);
                    $isPassed = $now->greaterThan($arrivalTime);
                    $isActive = $stop->is_selected_segment;
                @endphp

                <div class="stop-block {{ $isActive ? 'active' : ($isPassed ? 'completed' : 'upcoming') }}">
                    <div class="node">
                        @if ($isActive)
                            <i class="bi bi-bus-front-fill"></i>
                        @elseif($isPassed)
                            <i class="bi bi-check-circle-fill"></i>
                        @else
                            <div style="width: 6px; height: 6px; background: #ccc; border-radius: 50%;"></div>
                        @endif
                    </div>

                    <div class="stop-card">
                        <div class="stop-info">
                            <h6 class="{{ $isPassed ? 'text-success' : '' }}">{{ $stop->name }}</h6>
                            <small class="text-muted">#{{ $stop->code }}</small>
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
        function syncTimeline() {
            const bar = document.getElementById('progress-bar');
            const wrapper = document.querySelector('.timeline-wrapper');
            const stops = document.querySelectorAll('.stop-block');

            if (!bar || !wrapper || stops.length === 0) return;

            const parseTime = (timeStr) => {
                if (!timeStr || timeStr.trim() === "") return null;
                const now = new Date();
                const parts = timeStr.trim().split(' ');
                let [hours, minutes] = parts[0].split(':');
                const modifier = parts[1];
                hours = parseInt(hours, 10);
                minutes = parseInt(minutes, 10);
                if (modifier === 'PM' && hours !== 12) hours += 12;
                if (modifier === 'AM' && hours === 12) hours = 0;
                return new Date(now.getFullYear(), now.getMonth(), now.getDate(), hours, minutes, 0).getTime();
            };

            const nowTime = new Date().getTime();
            const wrapperRect = wrapper.getBoundingClientRect();

            const getNodeCenter = (stopElement) => {
                const node = stopElement.querySelector('.node');
                const rect = node.getBoundingClientRect();
                return (rect.top + rect.height / 2) - wrapperRect.top;
            };

            const firstNodeY = getNodeCenter(stops[0]);
            let lastCompletedIdx = -1;

            stops.forEach((stop, index) => {
                const arrTime = parseTime(stop.querySelector('.t-arr')?.innerText);
                const depTime = parseTime(stop.querySelector('.t-dep')?.innerText);
                if (nowTime >= (arrTime || depTime)) lastCompletedIdx = index;
            });

            let finalHeight = 0;
            let gradientPct = 0;

            if (lastCompletedIdx === -1) {
                finalHeight = 0;
            } else if (lastCompletedIdx === stops.length - 1) {
                finalHeight = getNodeCenter(stops[stops.length - 1]) - firstNodeY;
                bar.style.background = 'var(--bus-success)';
            } else {
                const currentIdx = lastCompletedIdx;
                const nextIdx = lastCompletedIdx + 1;

                const startY = getNodeCenter(stops[currentIdx]);
                const endY = getNodeCenter(stops[nextIdx]);

                const startTime = parseTime(stops[currentIdx].querySelector('.t-dep')?.innerText || stops[currentIdx]
                    .querySelector('.t-arr')?.innerText);
                const endTime = parseTime(stops[nextIdx].querySelector('.t-arr')?.innerText);

                let progress = (nowTime - startTime) / (endTime - startTime);
                progress = Math.max(0, Math.min(1, progress));

                const currentTipY = startY + (endY - startY) * progress;
                finalHeight = currentTipY - firstNodeY;
                const greenEnd = startY - firstNodeY;

                gradientPct = finalHeight > 0 ? (greenEnd / finalHeight) * 100 : 0;

                bar.style.background = `linear-gradient(to bottom,
                    var(--bus-success) 0%,
                    var(--bus-success) ${gradientPct}%,
                    var(--bus-primary) ${gradientPct}%,
                    var(--bus-primary) 100%)`;
            }

            bar.style.top = firstNodeY + 'px';
            bar.style.height = Math.max(0, finalHeight) + 'px';
            bar.style.display = 'block';
        }

        window.addEventListener('load', () => {
            setTimeout(syncTimeline, 200); // Wait for fonts/layout
            setInterval(syncTimeline, 30000);
        });

        window.addEventListener('resize', syncTimeline);
    </script>
@endpush

@extends('layouts.operator')

@section('title', 'Bus')

@push('styles')
    <link href="{{ asset('operator/css/schedules.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="top-nav-tabs d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Manage Bus Schedules</h5>

        <button class="btn btn-primary btn-sm rounded-pill px-3" style="background: var(--primary-gradient);"
            data-bs-toggle="modal" data-bs-target="#addScheduleModal">
            <i class="bi bi-plus-lg"></i> Add Trip
        </button>
    </div>

    <div class="container mt-4 mb-5 pb-4">
        <div class="d-flex justify-content-between align-items-center mb-4 border border-primary">
            <select class="form-select bus-select" id="route-select">
                <option value="">Search or Select Bus...</option>
                @foreach ($buses as $item)
                    <option value="{{ $item->id }}" @selected($item->id == $busId || count($buses) == 1)>
                        {{ $item->bus_number }} - {{ $item->bus_name }}
                    </option>
                @endforeach
            </select>
        </div>

        @php
            $days = [
                'mon' => 'M',
                'tue' => 'T',
                'wed' => 'W',
                'thu' => 'T',
                'fri' => 'F',
                'sat' => 'S',
                'sun' => 'S',
            ];
        @endphp

        <div class="row">
            @foreach ($trips as $item)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="schedule-card p-3">
                        <h6 class="mb-1 text-uppercase text-black" style="font-size: 15px">
                            {{ $item->routeDirection->name }}
                            <i class="small text-muted">
                                #F{{ $item->routeDirection->route_pattern_id }}T{{ $item->routeDirection->id }}
                            </i>
                        </h6>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="time-badge">
                                {{ \Carbon\Carbon::parse($item->departure_time)->format('g:i A') }} -
                                {{ \Carbon\Carbon::parse($item->departure_time)->format('g:i A') }}
                            </span>
                            <p class="small text-muted mb-2">Bus: {{ $item->bus->bus_number }}</p>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small d-block mb-2 d-none">Running Days</label>
                            <div class="d-flex justify-content-between">

                                @foreach ($days as $key => $day)
                                    <div class="day-selector">
                                        <input type="checkbox" class="btn-check"
                                            {{ in_array($key, $item->days_of_week) ? 'checked' : '' }} readonly>
                                        <label
                                            class="btn btn-outline-success day-btn {{ in_array($key, ['sun', 'sat']) ? 'text-danger' : '' }}">
                                            {{ $day }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-primary btn-sm flex-grow-1 border">Edit</button>
                            <button class="btn btn-light btn-sm text-danger border"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="modal fade" id="addScheduleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0">
                    <h5 class="fw-bold">New Trip Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('operator.trip.store') }}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Assign Bus</label>
                            <select class="form-select bus-select" name="bus">
                                <option value="">Search or Select Bus...</option>
                                @foreach ($buses as $item)
                                    <option value="{{ $item->id }}" @selected($item->id == $busId || count($buses) == 1)>
                                        {{ $item->bus_number }} - {{ $item->bus_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Starting Location</label>
                            <select name="from" class="choice-select" required></select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Ending Location</label>
                            <select name="end" class="choice-select" required></select>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Start Time</label>
                                <input type="time" class="form-control rounded-3" name="start_time" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">End Time</label>
                                <input type="time" class="form-control rounded-3" name="end_time" required>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold d-block mb-2">Running Days</label>
                            <div class="d-flex justify-content-between">
                                @foreach ($days as $key => $day)
                                    <div class="day-selector">
                                        <input type="checkbox" class="btn-check" id="day-{{ $day }}"
                                            value="{{ $key }}" name="day_of_weeks[]"
                                            {{ in_array($key, ['mon', 'tue', 'wed', 'thu', 'fri', 'sat']) ? 'checked' : '' }}
                                            readonly>
                                        <label for="day-{{ $day }}"
                                            class="btn btn-outline-success day-btn {{ in_array($key, ['sun', 'sat']) ? 'text-danger' : '' }}">
                                            {{ $day }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted mt-2 d-block" style="font-size: 0.75rem;">
                                Selected days will repeat weekly.
                            </small>
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button class="btn btn-primary w-100 py-3 rounded-3 fw-bold"
                            style="background: var(--primary-gradient);">
                            SAVE TRIP SCHEDULE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.bus-select').forEach(element => {
                const choices = new Choices(element, {
                    searchEnabled: true,
                    itemSelectText: '',
                    shouldSort: false,
                    placeholder: true,
                });
            });

            const element = document.getElementById('route-select');

            element.addEventListener('change', function() {
                const routeId = this.value;

                if (routeId) {
                    window.location.href = '?bus=' + routeId;
                }
            });

            document.querySelectorAll('.choice-select').forEach(select => {
                const choices = new Choices(select, {
                    searchEnabled: true,
                    shouldSort: false,
                    removeItemButton: true,
                    placeholderValue: 'Search stops...',
                    allowHTML: true
                });

                let debounceTimer;

                select.addEventListener('search', function(e) {
                    clearTimeout(debounceTimer);

                    debounceTimer = setTimeout(async () => {
                        const val = e.detail.value;
                        if (val.length < 2) return;
                        const res = await fetch(`/stops?q=${val}`);
                        const data = await res.json();

                        choices.clearStore();

                        choices.setChoices(data.map(i => ({
                            value: i.id,
                            label: `<span class="badge bg-info">${i.code}</span> ${i.name} <small class="">(${i.city.code}, ${i.city.district.code})</small>`,
                        })), 'value', 'label', true);
                    }, 300);
                });
            });
        });
    </script>
@endpush

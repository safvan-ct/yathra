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

        @if ($errors->any())
            @foreach ($errors as $error)
                <x-admin.alert type="error" :message="$error" />
            @endforeach
        @endif

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
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="time-badge">
                                {{ \Carbon\Carbon::parse($item->departure_time)->format('g:i A') }} -
                                {{ \Carbon\Carbon::parse($item->arrival_time)->format('g:i A') }}
                            </span>
                            <i class="small text-muted">
                                #TR{{ $item->id }}D{{ $item->route_direction_id }}
                            </i>
                        </div>

                        <h6 class="mb-2 text-uppercase text-black" style="font-size: 14px">
                            {{ $item->routeDirection->name }}
                        </h6>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex justify-content-between">
                                @foreach ($days as $key => $day)
                                    <span
                                        class="badge rounded-pill me-1 {{ in_array($key, $item->days_of_week) ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }}">
                                        {{ $day }}
                                    </span>
                                @endforeach
                            </div>

                            <p class="small text-muted mb-2">{{ $item->bus->bus_number }}</p>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            @if ($item->auth_status == \App\Enums\AuthStatus::PENDING)
                                <button class="btn btn-warning btn-sm border text-uppercase">Pending</button>
                            @elseif ($item->auth_status == \App\Enums\AuthStatus::APPROVED)
                                <button class="btn btn-success btn-sm border text-uppercase">Approved</button>
                            @else
                                <button class="btn btn-danger btn-sm border text-uppercase">Rejected</button>
                            @endif

                            <button class="btn btn-primary btn-sm flex-grow-1 border" data-bus_id="{{ $item->bus_id }}"
                                data-departure_time="{{ $item->departure_time }}"
                                data-arrival_time="{{ $item->arrival_time }}"
                                data-days_of_week="{{ implode(',', $item->days_of_week) }}"
                                data-time_between_stops_sec="{{ $item->time_between_stops_sec }}"
                                data-id="{{ $item->id }}"
                                data-origin_stop_id="{{ $item->routeDirection->origin_stop_id }}"
                                data-destination_stop_id="{{ $item->routeDirection->destination_stop_id }}"
                                data-route_name="{{ $item->routeDirection->name }}" data-bs-toggle="modal"
                                data-bs-target="#addScheduleModal">
                                Edit
                            </button>

                            @if ($item->is_active)
                                <button class="btn btn-light text-success btn-sm  border">ACTIVE</button>
                            @else
                                <button class="btn btn-light text-danger btn-sm  border">INACTIVE</button>
                            @endif
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

                <form action="{{ route('operator.trip.store') }}" method="POST" id="form">
                    @csrf

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Assign Bus</label>
                            <select class="form-select bus-select" name="bus_id" id="bus_id">
                                <option value="">Search or Select Bus...</option>
                                @foreach ($buses as $item)
                                    <option value="{{ $item->id }}" @selected(old('bus_id', $item->id) || count($buses) == 1)>
                                        {{ $item->bus_number }} - {{ $item->bus_name }}
                                    </option>
                                @endforeach
                            </select>

                            @if ($errors->has('bus_id'))
                                <x-admin.form-error :messages="$errors->get('bus_id')" class="mt-2" />
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Starting Location</label>
                            <select name="origin_stop_id" class="choice-select" id="origin_stop_id"></select>

                            @if ($errors->has('origin_stop_id'))
                                <x-admin.form-error :messages="$errors->get('origin_stop_id')" class="mt-2" />
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Ending Location</label>
                            <select name="destination_stop_id" id="destination_stop_id" class="choice-select"></select>

                            @if ($errors->has('destination_stop_id'))
                                <x-admin.form-error :messages="$errors->get('destination_stop_id')" class="mt-2" />
                            @endif
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Start Time</label>
                                <input type="time" class="form-control rounded-3" name="departure_time"
                                    id="departure_time" required value="{{ old('departure_time', '12:00') }}">

                                @if ($errors->has('departure_time'))
                                    <x-admin.form-error :messages="$errors->get('departure_time')" class="mt-2" />
                                @endif
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">End Time</label>
                                <input type="time" class="form-control rounded-3" name="arrival_time" id="arrival_time"
                                    required value="{{ old('arrival_time', '13:00') }}">

                                @if ($errors->has('arrival_time'))
                                    <x-admin.form-error :messages="$errors->get('arrival_time')" class="mt-2" />
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">
                                Approximate Time Between Stops (Seconds)
                            </label>

                            <input type="number" class="form-control form-control-lg border-2 shadow-sm" min="1"
                                placeholder="0" required name="time_between_stops_sec" id="time_between_stops_sec"
                                value="{{ old('time_between_stops_sec') }}">

                            @if ($errors->register->has('time_between_stops_sec'))
                                <x-admin.form-error :messages="$errors->register->get('time_between_stops_sec')" class="mt-2" />
                            @endif
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold d-block mb-2">Running Days</label>
                            <div class="d-flex justify-content-between">
                                @foreach ($days as $key => $day)
                                    <div class="day-selector">
                                        <input type="checkbox" class="btn-check" id="day-{{ $key }}"
                                            value="{{ $key }}" name="days_of_week[]"
                                            {{ in_array($key, old('days_of_week', ['mon', 'tue', 'wed', 'thu', 'fri', 'sat'])) ? 'checked' : '' }}
                                            readonly>
                                        <label for="day-{{ $key }}"
                                            class="btn btn-outline-success day-btn {{ in_array($key, ['sun', 'sat']) ? 'text-danger' : '' }}">
                                            {{ $day }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            @if ($errors->has('days_of_week'))
                                <x-admin.form-error :messages="$errors->get('days_of_week')" class="mt-2" />
                            @endif

                            <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">
                                Selected days will repeat weekly.
                            </small>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary text-uppercase">Trip Status</label>
                            <select class="form-select border-2 shadow-sm" style="border-radius: 12px; padding: 12px;"
                                required name="status" id="status">
                                <option value="1" @selected(old('status') == '1')>Active</option>
                                <option value="0" @selected(old('status') == '0')>Inactive</option>
                            </select>
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
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('addScheduleModal'));
                modal.show();
            });
        </script>
    @endif

    <script>
        let SELECTED_ROUTES = [];
        const choicesInstances = {};

        document.addEventListener('DOMContentLoaded', function() {

            var addScheduleModal = document.getElementById('addScheduleModal');

            addScheduleModal.addEventListener('show.bs.modal', function(event) {

                var button = event.relatedTarget;
                var tripId = button.getAttribute('data-id') ?? 0;

                if (tripId != 0) {
                    var busId = button.getAttribute('data-bus_id') ?? 0;
                    var departure = button.getAttribute('data-departure_time') ?? '';
                    var arrival = button.getAttribute('data-arrival_time') ?? '';
                    var days = button.getAttribute('data-days_of_week') ?? '';
                    var time = button.getAttribute('data-time_between_stops_sec') ?? 1;
                    var originStopId = button.getAttribute('data-origin_stop_id') ?? 0;
                    var destinationStopId = button.getAttribute('data-destination_stop_id') ?? 0;
                    var routeName = button.getAttribute('data-route_name') ?? '';
                    routeName = routeName.split('-');

                    if (originStopId) {
                        const originInstance = choicesInstances['origin_stop_id'];

                        originInstance.setChoices([{
                            value: originStopId,
                            label: routeName[0],
                            selected: true
                        }], 'value', 'label', true);
                    }

                    if (destinationStopId) {
                        const destInstance = choicesInstances['destination_stop_id'];

                        destInstance.setChoices([{
                            value: destinationStopId,
                            label: routeName[1],
                            selected: true
                        }], 'value', 'label', true);
                    }

                    let url = "{{ route('operator.trip.update', ':id') }}".replace(':id', tripId);

                    var form = document.getElementById('form');

                    form.action = url;
                    form.method = 'POST';

                    var methodInput = document.getElementById('method_field');

                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.id = 'method_field';
                        form.appendChild(methodInput);
                    }

                    methodInput.value = 'PUT';

                    document.getElementById('bus_id').value = busId;
                    document.getElementById('departure_time').value = departure;
                    document.getElementById('arrival_time').value = arrival;
                    document.getElementById('time_between_stops_sec').value = time;

                    if (days) {
                        var selectedDays = days.split(',');

                        // uncheck all first
                        document.querySelectorAll('input[name="days_of_week[]"]').forEach(function(cb) {
                            cb.checked = false;
                        });

                        // check matching ones
                        selectedDays.forEach(function(day) {
                            var checkbox = document.querySelector(
                                'input[name="days_of_week[]"][value="' + day.trim() + '"]'
                            );

                            if (checkbox) {
                                checkbox.checked = true;
                            }
                        });
                    }
                }
            });

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

                choicesInstances[select.id] = choices;

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

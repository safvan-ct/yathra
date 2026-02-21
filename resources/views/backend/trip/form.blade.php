<div class="row">
    <input type="hidden" name="id" value="{{ $data->id ?? 0 }}">

    {{-- Bus --}}
    <div class="mb-2 col-12 col-lg-6">
        @php
            $name = $data ? "{$data->bus->bus_name} ({$data->bus->bus_number})" : '';
        @endphp
        <label>Bus</label>
        <select name="bus_id" class="form-select choice-select" data-url="{{ route('buses.search') }}"
            data-placeholder="Type to search Bus..." data-selected-id="{{ $data->bus_id ?? '' }}"
            data-selected-name="{{ $name }}" required>
        </select>
    </div>

    {{-- Route Direction --}}
    @php
        $rdName = $data?->routeDirection ? explode('-', $data->routeDirection->name) : [];

        $fromName = !empty($rdName) ? $rdName[0] : '';
        $toname = !empty($rdName) ? $rdName[1] : '';

        $from = $data?->routeDirection ? $data->routeDirection->origin_stop_id : '';
        $to = $data?->routeDirection ? $data->routeDirection->destination_stop_id : '';
    @endphp

    <div class="mb-2 col-12 col-lg-6">
        <label>Starting Point</label>
        <select name="origin_stop_id" class="form-select choice-select" data-url="{{ route('stops.search') }}"
            data-placeholder="Type to search Stop..." data-selected-id="{{ $from }}"
            data-selected-name="{{ $fromName }}" required>
        </select>
    </div>

    <div class="mb-2 col-12 col-lg-4">
        <label>Ending Point</label>
        <select name="destination_stop_id" class="form-select choice-select" data-url="{{ route('stops.search') }}"
            data-placeholder="Type to search Stop..." data-selected-id="{{ $to }}"
            data-selected-name="{{ $toname }}" required>
        </select>
    </div>

    <x-admin.input name="departure_time" label="Start Time" type="time" class="col-6 col-lg-4"
        value="{{ isset($data->departure_time) ? \Carbon\Carbon::parse($data->departure_time)->format('H:i') : '' }}" />

    <x-admin.input name="arrival_time" label="End Time" type="time" class="col-6 col-lg-4"
        value="{{ isset($data->arrival_time) ? \Carbon\Carbon::parse($data->arrival_time)->format('H:i') : '' }}" />

    <div class="col-12 col-lg-6 mb-3">
        <label class="form-label">Days of Week</label>
        @php
            $days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
            $selectedDays = old('days_of_week', $data->days_of_week ?? []);
        @endphp

        <div class="d-flex flex-wrap gap-3">
            @foreach ($days as $day)
                <div class="form-check">
                    <input type="checkbox" name="days_of_week[]" value="{{ $day }}" class="form-check-input"
                        {{ in_array($day, $selectedDays ?? []) ? 'checked' : '' }}
                        id="days_of_week_{{ $day }}">
                    <label class="form-check-label" for="days_of_week_{{ $day }}">
                        {{ strtoupper($day) }}
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    <x-admin.input name="effective_from" type="date" label="Effective From" class="col-6 col-lg-3"
        value="{{ $data->effective_from ?? '' }}" />

    <x-admin.input name="effective_to" type="date" label="Effective To" class="col-6 col-lg-3"
        value="{{ $data->effective_to ?? '' }}" />

    <x-admin.input name="time_between_stops_sec" type="number" label="Approximate Time Between Stops (Seconds)"
        class="col-12 col-lg-4" value="{{ $data->time_between_stops_sec ?? '' }}" min="1" />

    @if ($data)
        <div class="col-12 col-lg-4">
            <label>Auth Status</label>

            <select name="auth_status" class="form-select">
                <option value="">Select Auth Status</option>

                @foreach (\App\Enums\AuthStatus::cases() as $type)
                    <option value="{{ $type->value }}"
                        {{ $data?->auth_status?->value == $type->value ? 'selected' : '' }}>
                        {{ ucfirst(strtolower($type->name)) }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif
</div>

<script>
    document.querySelectorAll('.choice-select11').forEach(select => {
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

    document.querySelectorAll('.choice-select').forEach(select => {

        const url = select.dataset.url;
        const placeholder = select.dataset.placeholder || 'Search...';

        const choices = new Choices(select, {
            searchEnabled: true,
            searchChoices: false,
            placeholder: true,
            placeholderValue: placeholder,
            shouldSort: false,
            removeItemButton: true
        });

        /* ---------- PRESELECT ---------- */

        const selectedId = select.dataset.selectedId;
        const selectedName = select.dataset.selectedName;

        if (selectedId && selectedName) {
            choices.setChoices([{
                value: selectedId,
                label: selectedName,
                selected: true
            }], 'value', 'label', true);
        }

        /* ---------- AJAX SEARCH ---------- */

        let debounceTimer;

        select.addEventListener('search', function(e) {

            clearTimeout(debounceTimer);

            debounceTimer = setTimeout(async () => {

                const value = e.detail.value;

                if (value.length < 2) return;

                const response = await fetch(`${url}?q=${encodeURIComponent(value)}`);
                const data = await response.json();

                choices.clearChoices();

                choices.setChoices(
                    data.map(item => ({
                        value: item.id,
                        label: item.name
                    })),
                    'value',
                    'label',
                    true
                );

            }, 400);

        });

    });
</script>

<div class="row">
    <input type="hidden" name="id" value="{{ $data->id ?? 0 }}">

    {{-- Route Direction --}}
    <div class="mb-2 col-12 col-lg-6">
        @php
            $name = $data
                ? "{$data->routeDirection->routePattern->name} ({$data->routeDirection->routePattern->code}) {$data->routeDirection->name} {$data->routeDirection->direction}"
                : '';
        @endphp
        <label>Route Direction</label>
        <select name="route_direction_id" class="form-select choice-select"
            data-url="{{ route('route-directions.search') }}" data-placeholder="Type to search Route Direction..."
            data-selected-id="{{ $data->route_direction_id ?? '' }}" data-selected-name="{{ $name }}" required>
        </select>
    </div>

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

    <x-admin.input name="departure_time" label="Departure Time" type="time" class="col-12 col-lg-4"
        value="{{ isset($data->departure_time) ? \Carbon\Carbon::parse($data->departure_time)->format('H:i') : '' }}" />


    <div class="col-12 col-lg-8 mb-3">
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

    <x-admin.input name="effective_from" type="date" label="Effective From" class="col-12 col-lg-4"
        value="{{ $data->effective_from ?? '' }}" />

    <x-admin.input name="effective_to" type="date" label="Effective To" class="col-12 col-lg-4"
        value="{{ $data->effective_to ?? '' }}" />
</div>

<script>
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

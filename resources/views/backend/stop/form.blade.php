<div class="row">
    <input type="hidden" name="id" value="{{ $data->id ?? 0 }}">

    @if (!$data)
        <div class="mb-2 col-12 col-lg-4">
            <select name="city_id" class="form-select city-select" required></select>
        </div>
    @endif

    <x-admin.input name="name" label="Name" class="col-12 {{ !$data ? 'col-lg-8' : '' }}"
        value="{{ $data->name ?? '' }}" />
</div>

<hr>
<div class="row">
    <x-admin.input name="locality" label="Locality" class="col-12 col-lg-4" value="{{ $data->locality ?? '' }}" />
    <x-admin.input name="latitude" label="Latitude" class="col-12 col-lg-4" value="{{ $data->latitude ?? '' }}" />
    <x-admin.input name="longitude" label="Longitude" class="col-12 col-lg-4" value="{{ $data->longitude ?? '' }}" />

    <div class="col-12 col-lg-4">
        <div class="form-check mb-3">
            <input type="checkbox" name="is_bus_terminal" class="form-check-input" id="is_bus_terminal"
                {{ old('is_bus_terminal', $stop->is_bus_terminal ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_bus_terminal">Is Bus Terminal</label>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.city-select').forEach(select => {

        const choices = new Choices(select, {
            searchEnabled: true,
            searchChoices: false,
            placeholder: true,
            placeholderValue: 'Type to select City...',
            shouldSort: false,
            removeItemButton: true
        });

        /* ---------------- AJAX SEARCH ---------------- */

        let debounceTimer;

        select.addEventListener('search', function(e) {

            clearTimeout(debounceTimer);

            debounceTimer = setTimeout(async () => {

                const value = e.detail.value;

                if (value.length < 2) return;

                const response = await fetch(`/cities?q=${value}`);
                const data = await response.json();

                choices.clearChoices();

                choices.setChoices(
                    data.map(item => ({
                        value: item.id,
                        label: `${item.name}`
                    })),
                    'value',
                    'label',
                    true
                );

            }, 400); // debounce
        });

    });
</script>

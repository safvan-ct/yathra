<div class="row">
    <input type="hidden" name="id" value="{{ $data->id ?? 0 }}">

    <x-admin.input name="name" label="Name" class="col-12 col-md-4" value="{{ $data->name ?? '' }}" />
    <x-admin.input name="info" label="Info" class="col-12 col-md-8" value="{{ $data->info ?? '' }}" />
</div>

<hr>
<div class="row">
    <div class="mb-2 col-12 col-md-6">
        <label>Route Start Stop</label>
        <select id="start-stop" name="origin_stop_id" class="form-select stop-select"
            data-selected-id="{{ $data->origin_stop_id ?? '' }}" data-selected-name="{{ $data->origin->name ?? '' }}"
            data-selected-code="{{ $data->origin->code ?? '' }}" required>
        </select>
    </div>

    <div class="mb-2 col-12 col-md-6">
        <label>Route End Stop</label>
        <select id="end-stop" name="destination_stop_id" class="form-select stop-select"
            data-selected-id="{{ $data->destination_stop_id ?? '' }}"
            data-selected-name="{{ $data->destination->name ?? '' }}"
            data-selected-code="{{ $data->destination->code ?? '' }}" required>
        </select>
    </div>
</div>

<script>
    document.querySelectorAll('.stop-select').forEach(select => {

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

                const response = await fetch(`/backend/stops?q=${value}`);
                const data = await response.json();

                choices.clearChoices();

                choices.setChoices(
                    data.map(item => ({
                        value: item.id,
                        label: `${item.name} (${item.code})`
                    })),
                    'value',
                    'label',
                    true
                );

            }, 400); // debounce
        });

    });
</script>

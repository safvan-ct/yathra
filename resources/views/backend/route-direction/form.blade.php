<div class="row">
    <input type="hidden" name="id" value="{{ $data->id ?? 0 }}">

    @if (!$data)
        <div class="mb-2 col-12 col-lg-4">
            <label>Route</label>
            <select name="route_pattern_id" class="form-select choice-select"
                data-selected-id="{{ $data->route_pattern_id ?? '' }}"
                data-selected-name="{{ $data->routePattern->name ?? '' }}"
                data-selected-code="{{ $data->routePattern->code ?? '' }}" required>
            </select>
        </div>
    @endif

    <x-admin.input name="name" label="Name" class="col-12 col-lg-4" value="{{ $data->name ?? '' }}" />

    <x-admin.input name="direction" label="Direction" class="col-12 col-lg-4" value="{{ $data->direction ?? '' }}" />
</div>

<script>
    document.querySelectorAll('.choice-select').forEach(select => {

        const choices = new Choices(select, {
            searchEnabled: true,
            searchChoices: false,
            placeholder: true,
            placeholderValue: 'Type to search route...',
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

                const response = await fetch(`/route-patterns?q=${value}`);
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

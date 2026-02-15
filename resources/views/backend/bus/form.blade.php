<div class="row">
    <input type="hidden" name="id" value="{{ $data->id ?? 0 }}">

    <div class="mb-2 col-12 col-lg-4">
        <label>Operator</label>
        <select name="operator_id" class="form-select operator-select" data-selected-id="{{ $data->operator_id ?? '' }}"
            data-selected-name="{{ $data->operator->name ?? '' }}" required></select>
    </div>

    <x-admin.input name="bus_name" label="Bus Name" class="col-12 col-lg-4" value="{{ $data->bus_name ?? '' }}" />

    <x-admin.input name="bus_number" label="Bus Number" class="col-12 col-lg-4" value="{{ $data->bus_number ?? '' }}" />
</div>

<script>
    document.querySelectorAll('.operator-select').forEach(select => {

        const choices = new Choices(select, {
            searchEnabled: true,
            searchChoices: false,
            placeholder: true,
            placeholderValue: 'Type to select Operator...',
            shouldSort: false,
            removeItemButton: true
        });

        /* ---------------- PRESELECT VALUE ---------------- */

        const selectedId = select.dataset.selectedId;
        const selectedName = select.dataset.selectedName;

        if (selectedId) {
            choices.setChoices([{
                value: selectedId,
                label: `${selectedName}`,
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

                const response = await fetch(`/operators?q=${value}`);
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

<div class="row">
    <input type="hidden" name="id" value="{{ $data->id ?? 0 }}">

    @if (!$data)
        <div class="mb-2 col-12 col-lg-4">
            <label>District</label>
            <select name="district_id" class="form-select district-select" required></select>
        </div>
    @endif

    <x-admin.input name="name" label="Name" class="col-12 {{ !$data ? 'col-lg-4' : '' }}"
        value="{{ $data->name ?? '' }}" />

    @if (!$data)
        <x-admin.input name="code" label="Code" class="col-12 {{ !$data ? 'col-lg-4' : '' }}"
            value="{{ $data->code ?? '' }}" />
    @endif
</div>

<script>
    document.querySelectorAll('.district-select').forEach(select => {

        const choices = new Choices(select, {
            searchEnabled: true,
            searchChoices: false,
            placeholder: true,
            placeholderValue: 'Type to select District...',
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

                const response = await fetch(`/districts?q=${value}`);
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

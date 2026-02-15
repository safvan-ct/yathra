<div class="row">
    <input type="hidden" name="id" value="{{ $data->id ?? 0 }}">

    <x-admin.input name="name" label="Name" class="col-12 col-lg-8" value="{{ $data->name ?? '' }}" />

    <div class="col-12 col-lg-4">
        <label>Type</label>

        <select name="type" class="form-select">
            <option value="">Select Type</option>

            @foreach (\App\Enums\OperatorType::cases() as $type)
                <option value="{{ $type->value }}" {{ $data->type?->value == $type->value ? 'selected' : '' }}>
                    {{ ucfirst(strtolower($type->name)) }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <input type="hidden" name="id" value="{{ $data->id ?? 0 }}">

    <x-admin.input name="name" label="Name" class="col-12 col-lg-6" value="{{ $data->name ?? '' }}" />

    <x-admin.input name="phone" label="Phone" class="col-12 col-lg-6" value="{{ $data->phone ?? '' }}" />

    @if (!$data)
        <x-admin.input name="register_pin" label="PIN" class="col-12 col-lg-4"
            value="{{ $data->register_pin ?? '' }}" />
    @endif

    <div class="col-12 col-lg-4 mb-2">
        <label>Type</label>

        <select name="type" class="form-select">
            <option value="">Select Type</option>

            @foreach (\App\Enums\OperatorType::cases() as $type)
                <option value="{{ $type->value }}" {{ $data?->type?->value == $type->value ? 'selected' : '' }}>
                    {{ ucfirst(strtolower($type->name)) }}
                </option>
            @endforeach
        </select>
    </div>

    @if ($data)
        <div class="col-12 col-lg-4">
            <label>Auth Status</label>

            <select name="auth_status" class="form-select">
                <option value="">Select Auth Status</option>

                @foreach (\App\Enums\OperatorAuthStatus::cases() as $type)
                    <option value="{{ $type->value }}" {{ $data?->auth_status?->value == $type->value ? 'selected' : '' }}>
                        {{ ucfirst(strtolower($type->name)) }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif
</div>

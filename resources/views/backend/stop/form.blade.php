<div class="row">
    <input type="hidden" name="id" value="{{ $data->id ?? 0 }}">

    <x-admin.input name="name" label="Name" class="col-12 col-md-8" value="{{ $data->name ?? '' }}" />
    <x-admin.input name="code" label="Abbreviation" class="col-12 col-md-4"
        value="{{ $data->code ?? '' }}" />
</div>

<hr>
<div class="row">
    <x-admin.input name="local_body" label="Local Body" class="col-12 col-md-6" value="{{ $data->local_body ?? '' }}" />
    <x-admin.input name="assembly" label="Assembly" class="col-12 col-md-6" value="{{ $data->assembly ?? '' }}" />
    <x-admin.input name="district" label="District" class="col-12 col-md-4" value="{{ $data->district ?? '' }}" />
    <x-admin.input name="state" label="State" class="col-12 col-md-4" value="{{ $data->state ?? '' }}" />
    <x-admin.input name="pincode" label="Pincode" class="col-12 col-md-4" value="{{ $data->pincode ?? '' }}" />
</div>

<hr>
<div class="row">
    <x-admin.input name="latitude" label="Latitude" class="col-12 col-md-4" value="{{ $data->latitude ?? '' }}" />
    <x-admin.input name="longitude" label="Longitude" class="col-12 col-md-4" value="{{ $data->longitude ?? '' }}" />

    <div class="col-12 col-md-4">
        <div class="form-check mb-3">
            <input type="checkbox" name="is_bus_terminal" class="form-check-input" id="is_bus_terminal"
                {{ old('is_bus_terminal', $stop->is_bus_terminal ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_bus_terminal">Is Bus Terminal</label>
        </div>
    </div>
</div>

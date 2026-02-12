<div class="row">
    <input type="hidden" name="id" value="{{ $data->id ?? 0 }}">

    <x-admin.input name="name" label="Name" class="col-12 col-md-6" value="{{ $data->name ?? '' }}" />
    <x-admin.input name="code" label="Code" class="col-12 col-md-6" value="{{ $data->code ?? '' }}" />
</div>

<hr>
<div class="row">
    <x-admin.input name="local_governing_body" label="Local Body" class="col-12 col-md-6"
        value="{{ $data->local_governing_body ?? '' }}" />
    <x-admin.input name="legislative_assembly" label="Assembly" class="col-12 col-md-6"
        value="{{ $data->legislative_assembly ?? '' }}" />
    <x-admin.input name="district" label="District" class="col-12 col-md-6" value="{{ $data->district ?? '' }}" />
    <x-admin.input name="state" label="State" class="col-12 col-md-6" value="{{ $data->state ?? '' }}" />
    <x-admin.input name="pincode" label="Pincode" class="col-12 col-md-6" value="{{ $data->pincode ?? '' }}" />
</div>

<hr>
<div class="row">
    <x-admin.input name="latitude" label="Latitude" class="col-12 col-md-6" value="{{ $data->latitude ?? '' }}" />
    <x-admin.input name="longitude" label="Longitude" class="col-12 col-md-6" value="{{ $data->longitude ?? '' }}" />
</div>

<div class="row">
    <input type="hidden" name="id" value="{{ $data->id ?? 0 }}">

    <x-admin.input name="name" label="Name" class="col-12 col-md-8" value="{{ $data->name ?? '' }}" />
    <x-admin.input name="code" label="Code" class="col-12 col-md-4" value="{{ $data->code ?? '' }}" />
</div>

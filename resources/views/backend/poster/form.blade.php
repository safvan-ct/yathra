<input type="hidden" name="id" value="{{ $data->id ?? 0 }}">

<x-admin.input name="name" label="Poster Name" value="{{ $data?->name ?? '' }}" />

<x-admin.input type="file" name="image" label="Poster Image (420x420)" accept="image/*" />

<img id="imagePreview" src="{{ isset($data?->image_src) ? $data?->image_src : '' }}" class="img-thumbnail d-none"
    style="max-width: 120px;">

<input type="hidden" name="id" value="{{ $data->id ?? 0 }}">

<x-admin.input name="name" label="Partner Name" value="{{ $data?->name ?? '' }}" />

<x-admin.input type="file" name="image" label="Partner Image (120x80)" accept="image/*" />

<img id="imagePreview" src="{{ isset($data?->image_src) ? $data?->image_src : '' }}" class="img-thumbnail d-none"
    style="width: 120px; height: 80px; object-fit: cover;">

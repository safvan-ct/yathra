<div class="row g-3 align-items-center">
    <input type="hidden" name="id" value="{{ $data->id ?? 0 }}">

    <div class="col-5 form-floating mb-2">
        <select class="form-select" name="menu_id" id="menu_id">
            <option value="">Select Menu</option>
            @foreach ($menus as $menu)
                <option value="{{ $menu->id }}" {{ $data?->menu_id == $menu->id ? 'selected' : '' }}>
                    {{ $menu->name }}</option>
            @endforeach
        </select>
        <label for="menu_id">Menu</label>
    </div>

    <div class="col-7">
        <x-admin.input name="name" label="Govt. Center Name" value="{{ $data?->name ?? '' }}" />
    </div>

    <div class="col-12 form-floating mb-2">
        <textarea class="form-control" name="tagline" id="tagline" placeholder="Tagline" rows="10"
            style="min-height: 75px">{{ $data?->tagline ?? '' }}</textarea>
        <label for="tagline">Short Description or Tagline</label>
    </div>
    <hr />

    <div class="col-12">
        <x-admin.input name="desc_title" label="Description Title" value="{{ $data?->desc_title ?? '' }}" />
    </div>
    <div class="col-12 form-floating mb-2">
        <textarea class="form-control" name="description" id="description" placeholder="Description" rows="10"
            style="min-height: 130px">{{ $data?->description ?? '' }}</textarea>
        <label for="description">Description</label>
    </div>
    <hr>

    <div class="col-12">
        <x-admin.input type="file" name="ad_image" label="Ad Image" accept="image/*" />

        <img id="imagePreview" src="{{ isset($data?->ad_image_src) ? $data?->ad_image_src : '' }}"
            class="img-thumbnail d-none" style="max-width: 120px;">
    </div>
</div>

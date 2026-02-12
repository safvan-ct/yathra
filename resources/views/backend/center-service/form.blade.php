<div class="row g-3 align-items-center">
    <input type="hidden" name="id" value="{{ $data->id ?? 0 }}">

    <div class="col-12 col-md-3 form-floating mb-2">
        <select class="form-select" name="menu_id" id="menu_id">
            <option value="">Select Menu</option>
            @foreach ($menus as $menu)
                <option value="{{ $menu->id }}" {{ $data?->menu_id == $menu->id ? 'selected' : '' }}>
                    {{ $menu->name }}</option>
            @endforeach
        </select>
        <label for="menu_id">Menu</label>
    </div>

    <div class="col-12 col-md-3 form-floating mb-2">
        <select class="form-select" name="government_center_id" id="government_center_id">
            <option value="">Select Govt. Center</option>
            @foreach ($governmentCenters as $item)
                <option value="{{ $item->id }}" {{ $data?->government_center_id == $item->id ? 'selected' : '' }}>
                    {{ $item->name }}</option>
            @endforeach
        </select>
        <label for="government_center_id">Menu</label>
    </div>

    <div class="col-12 col-md-6">
        <x-admin.input name="name" label="Service Name" value="{{ $data?->name ?? '' }}" />
    </div>

    <div class="col-12 form-floating mb-2">
        <textarea class="form-control" name="tagline" id="tagline" placeholder="Tagline" rows="10"
            style="min-height: 75px">{{ $data?->tagline ?? '' }}</textarea>
        <label for="tagline">Short Description or Tagline</label>
    </div>
    <hr />

    <div class="col-12 form-floating mb-2">
        <textarea class="form-control" name="notes" id="notes" placeholder="Notes" rows="10"
            style="min-height: 75px">{{ $data?->notes ?? '' }}</textarea>
        <label for="notes">Notes</label>
    </div>

    <div class="col-12">
        <x-admin.input type="file" name="ad_image" label="Ad Image" accept="image/*" />

        <img id="imagePreview" src="{{ isset($data?->ad_image_src) ? $data?->ad_image_src : '' }}"
            class="img-thumbnail d-none" style="max-width: 120px;">
    </div>
</div>

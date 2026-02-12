@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Settings" :breadcrumb="[['label' => 'Dashboard', 'link' => route('backend.dashboard')], ['label' => 'Settings']]" />

    <div class="card mt-3">
        <form class="card-body row" method="POST" action="{{ route('backend.settings.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="col-12">
                <h3 class="card-title border-bottom pb-2 border-danger">Contact Details</h3>
            </div>
            <x-admin.input name="email" label="Email" value="{{ old('email', $settings['email'] ?? null) }}"
                :class="'col-12 col-md-3'" />
            <x-admin.input name="primary_phone" type="tel" label="Primary Phone"
                value="{{ old('primary_phone', $settings['primary_phone'] ?? null) }}" :class="'col-12 col-md-3'" />
            <x-admin.input name="secondary_phone" type="tel" label="Secondary Phone"
                value="{{ old('secondary_phone', $settings['secondary_phone'] ?? null) }}" :class="'col-12 col-md-3'" />
            <x-admin.input name="whatsapp" type="tel" label="Whatsapp Phone"
                value="{{ old('whatsapp', $settings['whatsapp'] ?? null) }}" :class="'col-12 col-md-3'" />
            <x-admin.input name="whatsapp_message" label="WhatsApp Message"
                value="{{ old('whatsapp_message', $settings['whatsapp_message'] ?? null) }}" :class="'col-12 col-md-6'" />

            <div class="col-12 mt-3">
                <h3 class="card-title border-bottom pb-2 border-danger">Social Media</h3>
            </div>
            <x-admin.input name="instagram" label="Instagram" value="{{ old('instagram', $settings['instagram'] ?? null) }}"
                :class="'col-12 col-md-6'" />
            <x-admin.input name="facebook" label="Facebook" value="{{ old('facebook', $settings['facebook'] ?? null) }}"
                :class="'col-12 col-md-6'" />
            <x-admin.input name="linkedin" label="Linkedin" value="{{ old('linkedin', $settings['linkedin'] ?? null) }}"
                :class="'col-12 col-md-6'" />
            <x-admin.input name="twitter" label="Twitter" value="{{ old('twitter', $settings['twitter'] ?? null) }}"
                :class="'col-12 col-md-6'" />

            <div class="col-12 mt-3">
                <h3 class="card-title border-bottom pb-2 border-danger">Services</h3>
            </div>
            <div class="form-floating col-12 col-md-6 mb-2">
                <p class="form-label m-0">Our Key Services</p>
                @php $selectedServices = !empty($settings['key_services']) ? explode(',', $settings['key_services']) : []; @endphp
                <select name="key_services[]" id="key_services" class="form-select" multiple>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}" @selected(in_array($service->id, old('key_services', $selectedServices ?? [])))>
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('key_services'))
                    <x-admin.form-error :messages="$errors->get('key_services')" class="mt-2" />
                @endif
            </div>

            <div class="form-floating col-12 col-md-6 mb-2">
                <p class="form-label m-0">Useful Services</p>
                @php $selectedServices = !empty($settings['useful_services']) ? explode(',', $settings['useful_services']) : []; @endphp
                <select name="useful_services[]" id="useful_services" class="form-select" multiple>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}" @selected(in_array($service->id, old('useful_services', $selectedServices ?? [])))>
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('useful_services'))
                    <x-admin.form-error :messages="$errors->get('useful_services')" class="mt-2" />
                @endif
            </div>

            <div class="form-floating col-12 col-md-12 mb-2">
                <textarea class="form-control" name="service_desc" id="service_desc" placeholder="Default Service Description"
                    rows="10">{{ old('service_desc', $settings['service_desc'] ?? null) }}</textarea>
                <label for="service_desc">Default Service Description</label>
            </div>
            <div class="form-floating col-12 col-md-6 mb-2">
                <x-admin.input type="file" name="service_icon" label="Default Service Icon (120x120)" accept="image/*" />
            </div>
            <div class="form-floating col-12 col-md-6 mb-2">
                @if (isset($settings['service_icon']) && !empty($settings['service_icon']))
                    <img src="{{ asset('storage/' . $settings['service_icon']) }}" class="img-thumbnail"
                        style="max-width: 100px;">
                @endif
            </div>

            <div class="col-12 mt-3">
                <h3 class="card-title border-bottom pb-2 border-danger">Footer Details</h3>
            </div>
            <div class="form-floating col-12 col-md-6 mb-2">
                <textarea class="form-control" name="about_us" id="about_us" placeholder="About us" rows="10">{{ old('about_us', $settings['about_us'] ?? null) }}</textarea>
                <label for="about_us">About us</label>
            </div>
            <x-admin.input name="address" label="Address" value="{{ old('address', $settings['address'] ?? null) }}"
                :class="'col-12 col-md-6'" />

            <div class="col-12 mt-3">
                <h3 class="card-title border-bottom pb-2 border-danger">Images</h3>
            </div>
            <div class="form-floating col-12 col-md-6 mb-2">
                <x-admin.input type="file" name="welcome" label="Welcome (600*400)" accept="image/*" />
                @if (isset($settings['welcome']) && !empty($settings['welcome']))
                    <img src="{{ globalFileView($settings['welcome']) }}" class="img-thumbnail" style="max-width: 100px;">
                @endif
            </div>
            <div class="form-floating col-12 col-md-6 mb-2">
                <x-admin.input type="file" name="who_we_are" label="Who We Are (525x350)" accept="image/*" />
                @if (isset($settings['who_we_are']) && !empty($settings['who_we_are']))
                    <img src="{{ globalFileView($settings['who_we_are']) }}" class="img-thumbnail"
                        style="max-width: 100px;">
                @endif
            </div>
            <div class="form-floating col-12 col-md-6 mb-2">
                <x-admin.input type="file" name="about_large" label="About Large (420x520)" accept="image/*" />
                @if (isset($settings['about_large']) && !empty($settings['about_large']))
                    <img src="{{ globalFileView($settings['about_large']) }}" class="img-thumbnail"
                        style="max-width: 100px;">
                @endif
            </div>
            <div class="form-floating col-12 col-md-6 mb-2">
                <x-admin.input type="file" name="about_small_1" label="About Small 1 (200x250)" accept="image/*" />
                @if (isset($settings['about_small_1']) && !empty($settings['about_small_1']))
                    <img src="{{ globalFileView($settings['about_small_1']) }}" class="img-thumbnail"
                        style="max-width: 100px;">
                @endif
            </div>
            <div class="form-floating col-12 col-md-6 mb-2">
                <x-admin.input type="file" name="about_small_2" label="About Small 2 (200x250)" accept="image/*" />
                @if (isset($settings['about_small_2']) && !empty($settings['about_small_2']))
                    <img src="{{ globalFileView($settings['about_small_2']) }}" class="img-thumbnail"
                        style="max-width: 100px;">
                @endif
            </div>

            <div class="col-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif

        document.addEventListener('DOMContentLoaded', function() {
            new Choices('#key_services', {
                removeItemButton: true,
                searchEnabled: true,
                placeholder: true,
                placeholderValue: 'Select key services',
                shouldSort: true,
            });

            new Choices('#useful_services', {
                removeItemButton: true,
                searchEnabled: true,
                placeholder: true,
                placeholderValue: 'Select useful services',
                shouldSort: true,
            });
        });
    </script>
@endpush

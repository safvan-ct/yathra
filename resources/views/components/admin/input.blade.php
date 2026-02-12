@props(['name', 'label', 'value' => null, 'type' => 'text', 'error' => true, 'class' => ''])

@php
    $value = $type === 'password' ? '' : $value ?? old($name);
@endphp

<div class="form-floating mb-2 {{ $class }}">
    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}"
        class="form-control" placeholder="" {{ $attributes }} />
    <label for="{{ $name }}">{{ $label }}</label>

    @if ($error && $errors->has($name))
        <x-admin.form-error :messages="$errors->get($name)" class="mt-2" />
    @endif
</div>

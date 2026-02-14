@props(['name', 'label', 'value' => null, 'type' => 'text', 'error' => true, 'class' => 'form-floating'])

@php
    $value = $type === 'password' ? '' : $value ?? old($name);
@endphp

<div class="mb-2 {{ $class }}">
    <label for="{{ $name }}">{{ $label }}</label>
    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}"
        class="form-control" placeholder="" {{ $attributes }} />

    @if ($error && $errors->has($name))
        <x-admin.form-error :messages="$errors->get($name)" class="mt-2" />
    @endif
</div>

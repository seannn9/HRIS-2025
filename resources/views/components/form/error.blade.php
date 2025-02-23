@props(['name'])

@error($name)
    <p id="{{ $name }}-error" {{ $attributes->merge(['class' => 'mt-2 text-sm text-red-600 peer-invalid:visible'] )}}>
        {{ $message }}
    </p>
@enderror

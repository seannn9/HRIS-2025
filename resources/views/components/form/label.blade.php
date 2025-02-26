@props(['label','name'])

<label
    for="{{ $name }}"
    {{ $attributes->merge([
        'class' => "block text-sm/6 font-medium text-gray-900"
    ]) }}>
    {{ $label }}
</label>

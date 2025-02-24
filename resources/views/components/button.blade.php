@php
    $sizeClass = $sizes[$size] ?? $sizes['sm'];
    $newAttributes = $attributes->merge([
        "class" => "cursor-pointer rounded-$roundness bg-$containerColor font-semibold text-$contentColor shadow-xs hover:bg-$containerColor focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-$containerColor $sizeClass transform hover:-translate-y-1 transition duration-400"
    ])
@endphp

<button {{ $newAttributes }}>{{ $text }}</button>

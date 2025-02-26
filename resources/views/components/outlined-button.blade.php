@php
    $sizeClass = $sizes[$size] ?? $sizes['sm'];
    $newAttributes = $attributes->merge([
        "class" => "cursor-pointer rounded-$roundness font-semibold text-$contentColor ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-primary/5 $sizeClass transition delay-150 duration-300 ease-in-out hover:-translate-y-1 hover:scale-110"
    ])
@endphp

<button {{ $newAttributes }}>{{ $text }}</button>

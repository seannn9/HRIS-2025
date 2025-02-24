@props(['label', 'name', 'options'])


<div>
    <x-form.label :name="$name" :label="$label" />
    <div class="mt-2 grid grid-cols-1">
        <select autocomplete="{{ $name }}"
            id="{{ $name }}"
            name="{{ $name }}"
            {{ 
                $attributes->merge([
                    'class' => "col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-2 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 invalid:outline-red-300  focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                ])
            }}
            >
            <option value="" selected disabled hidden>Choose an option</option>
            @foreach($options as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        <svg aria-hidden="true"
            class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4"
            data-slot="icon" fill="currentColor" viewbox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd"
                d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                fill-rule="evenodd">
            </path>
        </svg>
    </div>
    <x-form.error :name="$name" />
</div>
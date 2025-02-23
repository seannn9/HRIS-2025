@props(['label', 'name'])


<div class="flex items-center justify-center bg-white">
    <div class="mx-auto w-full">
        <div>
            <x-form.label :name="$name" :label="$label" />

            <div class="grid grid-cols-1">
                <input aria-describedby="{{ $name }}-error" aria-invalid="true"
                    id="{{ $name }}" 
                    name="{{ $name }}"
                    {{
                        $attributes->merge([
                            'value' => old($name),
                            'placeholder' => $label,
                            'class' => 'col-start-1 row-start-1 block w-full rounded-md bg-white py-1.5 px-3 text-base text-gray-900 invalid:text-red-900 outline-1 -outline-offset-1 invalid:outline-red-300 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-primary/60 sm:text-sm/6 invalid:border-red-500 out-of-range:border-red-500 read-only:bg-primary/10 peer placeholder-transparent caret-primary/30',
                        ])
                    }}
                    />

                    
                    
            @error($name)
                <svg aria-hidden="true"
                    class="pointer-events-none col-start-1 row-start-1 mr-3 size-5 self-center justify-self-end text-red-500 sm:size-4"
                    data-slot="icon" fill="currentColor" viewbox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd"
                        d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14ZM8 4a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-1.5 0v-3A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                        fill-rule="evenodd">
                    </path>
                </svg>
            @enderror

            </div>
            
            <x-form.error :name="$name" />
        </div>
    </div>
</div>

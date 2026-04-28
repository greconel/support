@php
    $classes = 'form-select ' . ($errors->has($errorFor) ? 'is-invalid' : null);
@endphp

<select
    name="{!! $name !!}"
    id="{{ $id }}"
    autocomplete="off"
    placeholder="{{ __('Start typing to search..') }}"
    {{ $attributes->class($classes) }}
>
    @foreach($options as $value => $label)
        <option
            value="{{ $value }}"
            {{ in_array($value, $values) ? 'selected' : '' }}
        >
            {{ $label }}
        </option>
    @endforeach
</select>

@error($errorFor)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror

<x-push name="scripts">
    <script>
        const profilePhotos = @json($photos);

        const {{ $id }} = new TomSelect("#{{ $id }}", {
            allowEmptyOption: true,
            maxOptions: 100,
            render: {
                option: (data, escape) => {
                    return `<div>
                                <x-ui.profile-photo url="${profilePhotos[data.value]}" class="rounded-circle me-1" style="height: 30px; width: 30px" />
                                ${escape(data.text)}
                            </div>`;
                },

                item: (data, escape) => {
                    return `<div>
                                <x-ui.profile-photo url="${profilePhotos[data.value]}" class="rounded-circle me-1" style="height: 30px; width: 30px" />
                                ${escape(data.text)}
                            </div>`;
                }
            }
        });
    </script>
</x-push>

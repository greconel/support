<x-forms.select
    :options="$users"
    id="{{ $id }}"
    {{ $attributes->merge(['multiple' => $multiple]) }}
/>

<x-push name="scripts">
    <script>
        const profilePhotos = @json($userProfilePhotos);

        const {{ $id }} = new TomSelect("#{{ $id }}", {
            @if($multiple)
                plugins: ['checkbox_options', 'remove_button'],
            @endif
            allowEmptyOption: true,
            hidePlaceholder: true,
            render: {
                option: (data, escape) => {
                    return `<div>
                                <x-ui.profile-photo url="${profilePhotos[data.value]}" class="rounded-circle me-1" style="height: 30px; width: 30px" />
                                ${escape(data.text)}
                            </div>`;
                },

                item: (data, escape) => {
                    return `<div>
                                <x-ui.profile-photo url="${profilePhotos[data.value]}" class="rounded-circle me-1" style="height: 25px; width: 25px" />
                                ${escape(data.text)}
                            </div>`;
                }
            }
        });
    </script>
</x-push>

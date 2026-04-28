<div>
    @if(!$editMode)
        <div class="d-flex justify-content-end">
            <button class="btn btn-link" wire:click="$dispatch('enableEdit')"><i class="far fa-edit"></i></button>
        </div>

        <div class="mb-3">
            {!! $project->description !!}
        </div>
    @endif

    @if($editMode)
        <div class="mb-3">
            <textarea id="projectDescription">
                {!! $project->description !!}
            </textarea>
        </div>

        <div class="d-flex justify-content-end" x-data>
            <button class="btn btn-primary" @click="$wire.edit(tinymce.get('projectDescription').getContent())">{{ __('Edit') }}</button>
        </div>
    @endif
</div>

<x-push name="scripts">
    <script>
        Livewire.on('initTinyMce', () => {
            tinymce.init({
                selector: '#projectDescription',
                ...TINYMCE_FULL_CONFIG
            });
        });

        Livewire.on('destroyTinyMce', () => {
            tinymce.remove('#projectDescription');
        });
    </script>
</x-push>

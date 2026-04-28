<div
    x-data="{
        editor: null,
        content: $wire.get('{{ $attributes->wire('model')->value() }}'),
        debounceTimer: null
    }"

    x-init="
        editor = new Quill($refs.editor, {
            theme: 'snow',
            modules: {
                toolbar: DEFAULT_TOOLBAR_OPTIONS
            }
        });

        if (content) {
            editor.root.innerHTML = content;
        }

        editor.on('text-change', function() {
            const htmlContent = editor.root.innerHTML;

            if (htmlContent === '<p><br></p>') {
                content = null;
            } else {
                content = htmlContent;
            }

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                $wire.set('{{ $attributes->wire('model')->value() }}', content);
            }, 500);
        });

        document.querySelectorAll('.ql-toolbar button').forEach(el => el.setAttribute('tabindex', '-1'));
        document.querySelectorAll(`.ql-toolbar span[tabindex='0']`).forEach(el => el.setAttribute('tabindex', '-1'));

        $wire.on('refreshQuill', () => {
            content = $wire.get('{{ $attributes->wire('model')->value() }}');
            editor.root.innerHTML = content ?? '';
        })
    "

    @refresh-quill.window="editor.root.innerHTML = content ?? ''"

    wire:ignore

    {{ $attributes->whereDoesntStartWith('wire:model') }}
>
    <div x-ref="editor"></div>
</div>

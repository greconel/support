<div
    x-data="{ editor: null, content: {{ json_encode($value) }} }"

    x-init="
        editor = new Quill($refs.editor, {
            theme: 'snow',
            modules: {
                toolbar: DEFAULT_TOOLBAR_OPTIONS
            }
        });

        if (content){
            editor.root.innerHTML = content;
        }

        editor.on('editor-change', function(eventName, ...args) {
            const htmlContent = editor.root.innerHTML;

            if (htmlContent === '<p><br></p>') {
                content = null
                return;
            }

            content = htmlContent;
        });
    "
>
    <input
        type="hidden"
        name="{{ $name }}"
        x-model="content"
    >

    <div x-ref="editor"></div>
</div>

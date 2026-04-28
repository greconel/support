import { QuillDeltaToHtmlConverter } from 'quill-delta-to-html';

window.QuillConverter = QuillDeltaToHtmlConverter;

window.DEFAULT_TOOLBAR_OPTIONS = [
    ['bold', 'italic', 'underline', 'strike'],
    ['blockquote', 'code-block'],

    [{ 'header': 1 }, { 'header': 2 }],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    ['link'],

    /*
    [{ 'indent': '-1'}, { 'indent': '+1' }],
    [{ 'size': ['small', false, 'large', 'huge'] }],
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
    */

    [{ 'color': [] }, { 'background': [] }],
    [{ 'align': [] }],

    ['clean'],
];

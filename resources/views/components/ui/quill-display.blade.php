@props(['content' => null])

<div {{ $attributes->class(['ql-editor ql-display']) }}>
    {!! $content !!}
</div>

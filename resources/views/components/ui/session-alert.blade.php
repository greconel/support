@if(session($session))
    <div {{ $attributes->merge(['class' => 'alert']) }} role="alert">
        {!! session($session) !!}
    </div>
@endif

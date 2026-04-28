<img
    src="{{ $url ?? Auth::user()->profile_photo_url }}"
    alt="Profile photo"
    {{ $attributes->merge(['class' => 'avatar img-fluid', 'style' => 'object-fit: cover']) }}
/>

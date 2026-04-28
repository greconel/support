<div class="row">
    <div {{ $attributes->class(['col-md-6 px-0 mb-4', 'offset-md-6' => ($side === 'right')])->merge(['data-aos' => $side === 'left' ? 'slide-right' : 'slide-left', 'data-aos-once' => 'true']) }}>
        <h4 class="timeline-subject">
            <span class="ms-2">{{ $title }}</span>
        </h4>

        {{ $slot }}
    </div>
</div>

@if($side == 'left')
    <div class="d-flex justify-content-between py-1 ps-1 pe-2 mb-2">
        <div class="d-flex justify-content-between flex-grow-1">
            <div class="me-3">
                <img src="{{ $user?->profile_photo_url }}" alt="Profile photo" class="rounded-circle" width="50" height="50">
            </div>

            <div class="flex-grow-1">
                <span class="fs-4 fw-bolder d-block">
                    {!! __($activity->description, ['user' => $user?->name]) !!}

                    @if($activity->getExtraProperty('name'))
                        - <span class="timeline-text-color">{{ $activity->getExtraProperty('name') }}</span>
                    @endif
                </span>

                <span>
                    {!! $activity->getExtraProperty('extra_description') !!}
                </span>
            </div>
        </div>

        <span class="small text-muted ms-4 pt-1">
            {{ $activity->created_at->format('H:i') }}
        </span>
    </div>
@endif

@if($side == 'right')
    <div class="d-flex justify-content-between py-1 ps-1 pe-2 mb-2">
        <span class="small text-muted me-4 pt-1">
            {{ $activity->created_at->format('H:i') }}
        </span>

        <div class="d-flex justify-content-between flex-grow-1">
            <div class="me-3">
                <img src="{{ $user?->profile_photo_url }}" alt="Profile photo" class="rounded-circle" width="50" height="50">
            </div>

            <div class="flex-grow-1">
                <span class="fs-4 fw-bolder d-block">
                    {!! __($activity->description, ['user' => $user?->name]) !!}

                    @if($activity->getExtraProperty('name'))
                        - <span class="timeline-text-color">{{ $activity->getExtraProperty('name') }}</span>
                    @endif
                </span>

                <span>
                    {!! $activity->getExtraProperty('extra_description') !!}
                </span>
            </div>
        </div>
    </div>
@endif

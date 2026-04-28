<a href="{{ $href() }}">
    @if($getId != 'team')
        <img
            @class([
                'rounded-circle', 'mb-1',
                'border', 'border-2', 'border-primary' => $isActive()
            ])
            src="{{ $getAvatarUrl() }}"
            alt="avatar"
            style="width: 30px; height: 30px; object-fit: cover;"
            title="{{ $getName }}"
        >
    @endif
    @if($getId == 'team')
        <i
            @class([
                'fas', 'fa-users', 'mx-1',
                'text-primary' => $isActive(),
                'text-gray-600' => !$isActive()
            ])
            style="width:30px; height:30px"></i>
    @endif
</a>

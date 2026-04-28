@if($user->last_active_at)
    @if($user->last_active_at < now()->subDays(7) && $user->last_active_at > now()->subDays(14))
        <span class="text-warning">{{ $user->last_active_at->diffForHumans() }}</span>
    @elseif($user->last_active_at < now()->subDays(14))
        <span class="text-danger">{{ $user->last_active_at->diffForHumans() }}</span>
    @else
        <span class="text-success">{{ $user->last_active_at->diffForHumans() }}</span>
    @endif
@endif

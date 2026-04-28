<x-layouts.ampp :title="__('Login log overview')" :breadcrumbs="Breadcrumbs::render('showLoginLog', $loginLog)">
    <div class="container">
        <x-ui.page-title>{{ __('Login log overview') }}</x-ui.page-title>

        <div class="card">
            <div class="card-body">
                <div class="data-row">
                    <span>{{ __('User') }}</span>

                    <a href="{{ action(\App\Http\Controllers\Admin\Users\ShowUserController::class, $loginLog->user) }}">
                        {{ $loginLog->user->name }}
                    </a>
                </div>

                <div class="data-row">
                    <span>{{ __('Platform') }}</span>

                    <div class="d-flex align-items-center">
                        @if ($agent->isDesktop())
                            <i class="fas fa-desktop fa-2x me-3"></i>
                        @else
                            <i class="fas fa-mobile-alt fa-2x me-3"></i>
                        @endif

                        {{ $agent->platform() }} - {{ $agent->browser() }}
                    </div>
                </div>

                <div class="data-row">
                    <span>{{ __('IP address') }}</span>
                    {{ $loginLog->ip_address }}
                </div>

                <div class="data-row">
                    <span>{{ __('Via remember me') }}</span>

                    @if($loginLog->via_remember_me)
                        <div class="text-info">{{ __('Yes') }}</div>
                    @else
                        <div class="text-secondary">{{ __('No') }}</div>
                    @endif
                </div>

                <div class="data-row">
                    <span>{{ __('Created at') }}</span>
                    {{ $loginLog->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.ampp>

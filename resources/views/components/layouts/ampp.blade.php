<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('images/logos/bmk-logo_black.png') }}">

    {{-- STYLES --}}
    <link rel="stylesheet" href="{{ mix('css/ampp.css') }}">
    <livewire:styles />
    @stack('styles')

    {{-- SCRIPTS --}}
    <script src="{{ mix('js/ampp.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script defer src="{{ mix('js/alpine.js') }}"></script>
</head>
<body>

    @if(app()->environment('local'))
        <div
            x-data="{ show: true }"
            @click="show = false"
        >
            <template x-if="show">
                <div class="ribbon-dev ribbon-sticky cursor-pointer">
                    <span class="fw-bolder">{{ __('DEV') }}</span>
                </div>
            </template>
        </div>
    @endif

{{-- Sidebar --}}
<div class="wrapper">
    <x-ampp.navigation />

    <div class="main">
        @if(app()->environment('local'))
        <p class="text-white fw-bolder" style="text-align: center; background-color: #ef4444; position: fixed; width: 1260px; z-index: 1000;">DEV</p>
        @endif
        <nav class="navbar navbar-expand navbar-light navbar-bg">
            <a class="sidebar-toggle js-sidebar-toggle d-flex">
                <i class="hamburger align-self-center"></i>
            </a>

            @isset($breadcrumbs)
                <div class="d-none d-xl-flex navbar-align ms-3">
                    {!! $breadcrumbs !!}
                </div>
            @endisset

            <div class="navbar-collapse collapse">
                <ul class="navbar-nav navbar-align">
                    <livewire:ampp.time-registrations.icon />

                    <x-ui.notifications />

                    <li class="nav-item dropdown">
                        <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-cog align-middle"></i>
                        </a>

                        <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                            <x-ui.profile-photo class="rounded me-1" />
                            <span class="text-dark">{{ Auth::user()->name }}</span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{ action(\App\Http\Controllers\Ampp\Profile\EditProfileController::class) }}">
                                <i class="fas fa-user align-middle"></i> {{ __('Profile') }}
                            </a>

                            <a class="dropdown-item" href="{{ action(\App\Http\Controllers\Ampp\HelpMessages\IndexHelpMessageController::class) }}">
                                <i class="fas fa-question-circle align-middle"></i> {{ __('Help center') }}
                            </a>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="#" onclick="document.getElementById('logoutForm').submit()">{{ __('Logout') }}</a>
                            <x-forms.form action="{{ route('logout') }}" method="post" class="d-none" id="logoutForm" />
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="content">
            <div class="container-fluid p-0">
                @isset($breadcrumbs)
                    <div class="container d-xl-none mb-4">
                        {!! $breadcrumbs !!}
                    </div>
                @endisset

                {{ $slot }}
            </div>
        </main>
    </div>
</div>

<div aria-live="polite" aria-atomic="true" class="position-relative">
    <div class="toast-container position-absolute start-50 bottom-0 translate-middle-x p-3">
        <x-ui.session-sweet-alert-toast session="success" icon="success" />
        <x-ui.session-sweet-alert-toast session="error" icon="error" />
    </div>
</div>

@stack('modals')

<livewire:scripts />
<x-livewire-alert::scripts />

@stack('scripts')
</body>
</html>

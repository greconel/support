<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('images/logos/bmk-logo_black.png') }}">

    {{-- STYLES --}}
    <link rel="stylesheet" href="{{ mix('css/ampp.css') }}">
    @livewireStyles
    @stack('styles')

    {{-- SCRIPTS --}}
    <script src="{{ mix('js/ampp.js') }}"></script>
    <script defer src="{{ mix('js/alpine.js') }}"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

            </ul>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Log in') }}</a>
                    </li>
                @endguest

                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="document.getElementById('logoutForm').submit()">{{ __('Log out') }}</a>
                    </li>

                    <x-forms.form action="{{ route('logout') }}" method="post" class="d-none" id="logoutForm" />
                @endauth
            </ul>
        </div>
    </div>
</nav>

<div>
    {{ $slot }}
</div>

@livewireScripts
@stack('scripts')
</body>
</html>

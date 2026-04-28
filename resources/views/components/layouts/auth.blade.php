<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('images/logos/bmk-logo_black.png') }}">

    {{-- STYLES --}}
    <link rel="stylesheet" href="{{ mix('css/ampp.css') }}">

    {{-- SCRIPTS --}}
    <script src="{{ mix('js/ampp.js') }}"></script>
    <script defer src="{{ mix('js/alpine.js') }}"></script>
</head>
<body class="bg-white">

<main class="container-fluid h-100">
    <div class="row h-100">
        <div class="col-lg-6 my-lg-auto p-5">
            <div class="d-lg-none mb-3">
                <a href="/">
                    <img src="{{ config('ampp.logos.main_black') }}" alt="AMPP" class="img-fluid col-6 col-sm-4 col-md-3" />
                </a>
            </div>

            <div class="col-xxl-8 mx-xxl-auto">
                {{ $slot }}
            </div>
        </div>

        <div class="col-lg-6 d-none d-lg-block position-relative bg-blue-50">
            <div class="auth-background"></div>
        </div>
    </div>
</main>

@stack('scripts')
</body>
</html>

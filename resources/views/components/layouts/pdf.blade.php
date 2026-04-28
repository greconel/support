<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? null }}</title>

    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">

    <style>
        body {
            background-color: white;
            margin: 0 !important;
        }

        @page {
            /*margin: 10mm;*/
        }

        .page {
            page-break-after: always;
        }

        .table {
            page-break-inside: auto
        }

        .table tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        /*header and footer*/
        .page-header, .page-header-space {
            height: 100px;
        }

        .page-footer, .page-footer-space {
            height: 150px;
        }

        .page-footer {
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .page-header {
            position: fixed;
            top: 0;
            width: 100%;
        }
    </style>

    {{ $style ?? null }}
</head>
<body>
@isset($header)
    <div class="page-header">{{ $header ?? null }}</div>
@endisset

<table>
    @isset($header)
        <thead>
            <tr>
                <td>
                    <div class="page-header-space">
                        <!--place holder for the fixed-position header-->
                    </div>
                </td>
            </tr>
        </thead>
    @endisset
    <tbody>
        <tr>
            <td>
                <div>
                    <!-- CONTENT GOES HERE -->
                    {{ $slot }}
                </div>
            </td>
        </tr>
    </tbody>
    @isset($footer)
        <tfoot>
            <tr>
                <td>
                    <div class="page-footer-space">
                        <!--place holder for the fixed-position footer-->
                    </div>
                </td>
            </tr>
        </tfoot>
    @endisset
</table>

@isset($footer)
    <div class="page-footer">{{ $footer ?? null }}</div>
@endisset
</body>
</html>


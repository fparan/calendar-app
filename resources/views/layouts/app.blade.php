<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="favicon.ico">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <base href="/">
        <title>Calendar App</title>

        <!-- Styles -->
        <link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/jquery-ui.structure.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/jquery-ui.theme.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('toastr/toastr.min.css') }}" rel="stylesheet">
        @yield('style')
    </head>
    <body>
        <nav class="navbar navbar-light bg-light">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">Calendar App</span>
            </div>
        </nav>
        @yield('content')


        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('moment/moment.min.js') }}"></script>
        <script src="{{ asset('toastr/toastr.min.js') }}"></script>
        <script type="text/javascript"></script>
        @yield('javascript')
    </body>
</html>
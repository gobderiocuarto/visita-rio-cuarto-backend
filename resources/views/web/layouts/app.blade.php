<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('css/main.min.css') }}">
</head>
<body>
    @include('web.layouts.partials.nav_bar')
    @yield('content')
    @include('web.layouts.partials.footer')
    <script src="{{ asset('js/main.min.js') }}"></script>
</body>
</html>

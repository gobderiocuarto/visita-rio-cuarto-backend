<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-20054145-10"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-20054145-10');
    </script>
    <link rel="stylesheet" href="{{ asset('css/main.min.css') }}">
</head>
<body>
    @include('web.layouts.partials.nav_bar')
    @yield('content')
    @include('web.layouts.partials.footer')
    <script src="{{ asset('js/main.min.js') }}"></script>
</body>
</html>

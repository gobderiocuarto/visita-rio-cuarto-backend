<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('meta_title')</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.7/dist/css/bootstrap-select.min.css">
    @yield('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-dark">
            @include('admin.layouts.partials.nav_bar')
        </nav>
        <main class="py-4" style="margin-top: 50px; margin-bottom: 40px">
            @yield('content')
        </main>
        <nav class="navbar fixed-bottom navbar-dark bg-danger" style="height: 50px">
          <a class="navbar-brand" href="mailto:incoming+dg-guillermo-rodriguez-servicios-y-eventos-12351361-issue-@incoming.gitlab.com?subject=Servicios%20y%20Eventos:%20reportar%20un%20error" target="_blank" style="position: absolute; width: 100%; left: 0;text-align: center;">Reportar un error</a>
        </nav>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('libs/jquery/jquery.min.js') }}" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.7/dist/js/bootstrap-select.min.js"></script>
    <script src="{{ asset('libs/stringToSlug/jquery.stringToSlug.min.js') }}"></script>
    <script>const base_url = "{{ url('/') }}"</script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    @yield('scripts')
</body>
</html>

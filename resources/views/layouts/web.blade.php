<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                
                    <!-- Left Side Of Navbar -->
                    <div class="navbar-nav mr-auto">
                        <form action="/buscador" method="get" name="top" id="top">
                            <div class="row"> 
                                <div class="col-md-3">   
                                    <input name="search" id="search" type="text" placeholder="&nbsp;¿Qué estás buscando?" value="">
                                </div>
                                <div class="col-md-4">
                                    <select name="rubro" id="rubro">
                                        <option value="" selected="selected">Todas las Categorías</option>
                                        @foreach($categories as $category)
                                        <optgroup label="{{ $category->name }}">
                                            @foreach($category->categories as $option)
                                            <option value="{{ $option->slug }}">{{ $option->name }}</option>
                                            @endforeach                                    
                                        </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="zona">
                                        <option value="" selected="selected">Todo Río Cuarto</option>
                                        @foreach($zones as $zone)
                                        <option value="{{ $zone->slug }}">{{ $zone->name }}</option>
                                        @endforeach        
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="submit" value="Buscar">
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/admin') }}">Admin</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('libs/jquery/jquery.min.js') }}" ></script>
    <script src="{{ asset('libs/bootstrap/js/bootstrap.bundle.min.js') }}" ></script>
    @yield('scripts')
</body>
</html>

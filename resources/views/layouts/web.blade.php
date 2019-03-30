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

                        <form action="/Guia" method="get" name="top" id="top">

                            <input name="search" id="search" type="text" class="inputformCommon2" placeholder="&nbsp;¿Qué estás buscando?" value="">
                            <select name="rubro" id="rubro" class="comboboxCommon2" style="margin-top:5px;">
                                <option value="">TODOS LOS RUBROS</option>

                                <optgroup label="Para tu casa">
                                
                                    <option value="Decoracion-Y-Hogar" selected="">decoracion y hogar</option>
                                    
                                    <option value="Fumigaciones">fumigaciones</option>
                                    
                                    <option value="Jardin-Y-Pileta">jardin y pileta</option>
                                    
                                    <option value="Limpieza">limpieza</option>
                                    
                                    <option value="Mantenimiento-Generales">Mantenimiento generales</option>
                                    
                                </optgroup>

                                <optgroup label="Para vos">
                                
                                    <option value="Colegio">colegio</option>
                                    
                                    <option value="Colonia-De-Vacaciones">Colonia de vacaciones</option>
                                    
                                    <option value="Cursos-Y-Talleres">cursos y talleres</option>
                                    
                                    <option value="Deportes">deportes</option>
                                    
                                    <option value="Empleadas-Domesticas">Empleadas domesticas</option>
                                    
                                    <option value="Merceria">Merceria</option>
                                    
                                    <option value="Moda">Moda</option>
                                    
                                    <option value="Profesionales">profesionales</option>
                                    
                                    <option value="Recreacion">Recreacion y regalos</option>
                                    
                                    <option value="Salud-Y-Belleza">salud y belleza</option>
                                    
                                    <option value="Turismo">turismo</option>
                                    
                                </optgroup>

                            </select>

                            <div class="float: left;" id="nosenose">
                          
                                <select name="guia" id="guia" class="comboboxCommon2" style="width:200px;">
                          
                                  <option value="Nordelta">Nordelta</option>
                                  
                                  <option value="Pilar">Pilar</option>
                                  
                                  <option value="Belgrano">Belgrano</option>
                                  
                                </select>

                                <input type="submit" value="Buscar" class="submitButton2">
                            </div>
                        </form>                       
                    </div>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
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

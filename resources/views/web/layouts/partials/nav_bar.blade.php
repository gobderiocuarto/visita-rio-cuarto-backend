<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="{{ url('') }}">
    <img src="/images/logo-visita-rio-cuarto-white.svg" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto ml-sm-3">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            ¿Qué Hacer?
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            @foreach ($event_tags as $tag )
            <a class="dropdown-item" href="{{ url('eventos/categorias/'.$tag->slug) }}">{{ $tag->name }}</a>
            @endforeach
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            ¿Cuándo?
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ url('eventos/cuando/hoy') }}">Hoy</a>
            <a class="dropdown-item" href="{{ url('eventos/cuando/maniana') }}">Mañana</a>
            <a class="dropdown-item" href="{{ url('eventos/cuando/fin-de-semana') }}">Este fin de semana</a>
            <a class="dropdown-item" href="{{ url('eventos/cuando/mes') }}">Este Mes</a>
          </div>
        </li>
      </ul>
      <form action="{{url('/eventos') }}" class="form-inline my-2 my-lg-0" method="GET">
        <input type="text" class="form-control mr-sm-2" type="search" placeholder="Buscar" name="busqueda"  required="required" minlength=3 >
        <button type="submit" class="btn btn-outline-light my-2 my-sm-0">Buscar</button>
      </form>
    </div>
  </div>
</nav>
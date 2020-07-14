<nav class="navbar navbar-expand-lg navbar-light ">
  <div class="container">
    
    <a class="navbar-brand" href="{{ url('/') }}">
      <img src="/images/logo-visita-rio-cuarto-color.svg">
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fas fa-lg fa-bars"></i>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            QUÉ
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            @foreach ($event_tags as $tag )
            <a class="dropdown-item" href="{{ url('eventos/categorias/'.$tag->slug) }}">{{ $tag->name }}</a>
            @endforeach
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            CUÁNDO
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ url('eventos/cuando/hoy') }}">Hoy</a>
            <a class="dropdown-item" href="{{ url('eventos/cuando/maniana') }}">Mañana</a>
            <a class="dropdown-item" href="{{ url('eventos/cuando/fin-de-semana') }}">Este fin de semana</a>
            <a class="dropdown-item" href="{{ url('eventos/cuando/mes') }}">Este Mes</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            DONDE
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ url('eventos/donde/17/resto-bares') }}">Resto Bares</a>
            <a class="dropdown-item" href="{{ url('eventos/donde/56/discotecas-pubs') }}">Discotecas – Pubs</a>
            <a class="dropdown-item" href="{{ url('eventos/donde/28/museos-y-centros-culturales') }}">Museos y Centros Culturales</a>
            <a class="dropdown-item" href="{{ url('eventos/donde/33/paseos-al-aire-libre') }}">Paseos al aire libre</a>
            <a class="dropdown-item" href="{{ url('eventos/donde/27/teatros') }}">Teatros</a>
          </div>
        </li>
        <li class="nav-item">
          <a href="servicios" class="nav-link">SERVICIOS</a>
        </li>
      </ul>
      <form action="{{url('/eventos') }}" class="form-inline my-2 my-lg-0" method="GET">
        <input type="text" class="form-control mr-sm-1" type="search" placeholder="Que estas buscando?" name="busqueda"  required="required" minlength=3 >
        <button type="submit" class="btn btn-primary my-2 my-sm-0">BUSCAR</button>
      </form>
    </div>
  </div>
</nav>
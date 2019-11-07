<div class="hero">
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col col-md-4">
        <div class="dropdown">
          <button class="btn btn-block btn-lg btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Qué querés hacer?
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
             @foreach ($event_tags as $tag )
            <a class="dropdown-item" href="{{ url('eventos/categorias/'.$tag->slug) }}">{{ $tag->name }}</a>
            @endforeach
          </div>
        </div>
      </div>
      <div class="col col-md-4">
        <div class="dropdown">
          <button class="btn btn-block btn-lg btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Cuando lo queres hacer?
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="{{ url('eventos/cuando/hoy') }}">Hoy</a>
            <a class="dropdown-item" href="{{ url('eventos/cuando/maniana') }}">Mañana</a>
            <a class="dropdown-item" href="{{ url('eventos/cuando/fin-de-semana') }}">Este fin de semana</a>
            <a class="dropdown-item" href="{{ url('eventos/cuando/mes') }}">Este Mes</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

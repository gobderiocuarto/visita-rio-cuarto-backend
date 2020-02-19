@extends('web.layouts.app')
@section('content')
<div class="container event">
    <h1 class="display-4"></h1>
    <div class="row">
      <div class="col-md-7 col-lg-8 col-xl-9">
        @if($event->file)        
        <div style="background-image: url('{{ Storage::url("events/large/{$event->file->file_path}") }}');" class="image"></div>
        @endif      
        <div class="content">
          <h2>{{ $event->title }}</h2>
          <p>{{ $event->summary }}</p>
          @if ($event->description)
          {!! $event->description !!}
          @endif       
        </div>
        <hr>
        <div class="row info">
          <div class="col-lg-6">
            <h4>¿Cuándo?</h4>
            @foreach($event->calendars as $calendar)
            <p>{{ \Carbon\Carbon::parse($calendar->start_date)->format('d/m/Y')}}, {{ \Carbon\Carbon::parse($calendar->start_time)->format('H.i')}} hs.</p>
            @endforeach
          </div>
          <div class="col-lg-6">
            <h4>¿Dónde?</h4>
            @if ($event->place)
              @if ($event->place->placeable_type == 'App\Space')
                @if ($event->place->placeable->address->lat && $event->place->placeable->address->lng)
                <p><a href="https://maps.google.com.ar/?q={{$event->place->placeable->address->lat}},{{$event->place->placeable->address->lng}}" target="_blank" class="font-weight-bold">{{ $event->place->organization->name }}</a></p>
                @else
                <p><b>{{ $event->place->organization->name }}</b>
                @endif
                <p>{{ $event->place->placeable->address->street->name }} {{ $event->place->placeable->address->number }}. Río Cuarto, Córdoba</p>
              @else
                @if ($event->place->placeable->lat && $event->place->placeable->lng)
                <p><a href="https://maps.google.com.ar/?q={{ $event->place->placeable->lat }},{{ $event->place->placeable->lng }}" target="_blank" class="font-weight-bold">{{ $event->place->organization->name }}</a></p>
                @else
                <p><b>{{ $event->place->organization->name }}</b>
                @endif
              <p>{{ $event->place->placeable->street->name }} {{ $event->place->placeable->number }}. Río Cuarto, Córdoba</p>
              @endif
            @else
            <p><b>Lugar a confirmar</b>
            @endif
          </div>
        </div>
        @if ($event->event)
        <div class="frame">
          Este evento se desarrolla en el marco de: <a href="{{ url('eventos/marco/'.$event->event->id ) }}" class="text-uppercase text-frame">{{ $event->event->title }}</a>
        </div>
        <hr>
        @endif
        <div class="tags">
          @foreach($event->tags as $tag)
          <a href="{{ url('eventos/categorias/'.$tag->slug) }}" class="btn btn-sm btn-outline-branding">{{ $tag->name }}</a>
          @endforeach
        </div>
        <hr>
      </div>
      <div class="col-md-5 col-lg-4 col-xl-3">
        <div class="aside">
          <!-- <div class="card">
            <div class="card-body">
                <h3>compartilo</h3>
                @include('web.layouts.partials.share')
            </div>
          </div> -->
          <!-- <hr> -->
          <div class="panel">
            <h3>Próximos eventos</h3>
            @foreach ($events as $event)
                @include('web.layouts.partials.event-card')
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
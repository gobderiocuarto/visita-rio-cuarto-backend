@extends('admin.layouts.app')
@section('meta_title') {{ config('app.name'). " - Admin :: Ver evento" }} @endsection
@section('styles')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{ asset('libs/jquery-tagsinput/css/jquery.tagsinput-revisited.css') }}"/>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Admin</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/events') }}">Eventos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ver</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10 mt-4">
            <h3>Ver detalle de evento:</h3>
            <h1><strong>{{ $event->title }}</strong></h1>
            </br>
            <div class="alert alert-secondary mb-3 text-right" >
                <a href="{{ route('events.index', Session::get('redirect') ) }}" class="btn btn-sm btn-primary ">
                Volver al listado
                </a>
            </div>
            <hr>
            @if ($event->frame)
            <div class="alert alert-warning" role="alert">
                Atención: Estás consultando un 'Evento Marco'
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <hr>
            @endif
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="nav-item">
                    <a href="#event" class="nav-link active" data-toggle="tab" aria-controls="event" role="tab" title="Datos del evento">
                        Datos del evento
                    </a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#image" class="nav-link" data-toggle="tab" aria-controls="image" role="tab" title="Imagen del evento">
                        Imagen del evento 
                    </a>
                </li>
            </ul><!-- nav-tabs -->
            <div class="tab-content p-3 mt-2">
                <div class="tab-pane active" role="tabpanel" id="event">
                    <div class="card">
                        <div class="card-header">
                            <h3><a name="event">Datos de evento</h3>
                        </div>
                        <div class="card-body mt-2">
                            @include('admin.events.partials.show_event_base')
                        </div>
                    </div>
                    @if ($event->frame)
                        @include('admin.events.partials.show_event_frame')
                    @else          
                        @include('admin.events.partials.show_event_no_frame')
                    @endif

                    @if (!$event->frame)
                        @include('admin.events.partials.show_event_place')
                    @endif 

                    @if (!$event->frame)
                        @include('admin.events.partials.show_calendars')
                    @endif 
                </div>
                <div class="tab-pane" role="tabpanel" id="image">
                    <div class="card">
                        <div class="card-header">
                            <h3><a name="image"></a>Imagen del evento</h3>
                        </div>
                        <div class="card-body mt-2">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-md-right">Imagen principal: </label>
                                @if($event->file)
                                <div class="col-md-3 ">
                                    <a target="_blank" href="{{ Storage::url("events/{$event->id}/{$event->file->file_path}") }}">
                                        <img class="img-fluid" src="{{ Storage::url("events/{$event->id}/thumbs/{$event->file->file_path}") }}" alt="{{$event->file->file_alt}}">
                                    </a>
                                </div>
                                @else
                                <div class="col-md-8">
                                    <span class="form-text font-italic mt-2">Aún no se ha cargado ninguna imagen</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script src="{{ asset('libs/jquery-tagsinput/js/jquery.tagsinput-revisited.js') }}"></script>
<script>

    $(document).ready(function(){

        $('#tags_no_category_event').tagsInput({
            interactive: false,
        });
        $('#tags_category_event').tagsInput({
            interactive: false,
        });

    }); // END document.ready
</script>
@endsection
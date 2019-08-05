@extends('admin.layouts.app')
@section('meta_title') {{ config('app.name'). " - Admin :: Editar evento" }} @endsection
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
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>Editar evento:</h3>
                    <h2><strong>"{{ $event->title }}"</strong></h2>
                </div>
                <div class="card-body mt-2">
                    <div class="alert alert-secondary mb-3 text-right" >
                        <a href="{{ route('events.index') }}" class="btn btn-sm btn-primary ">
                        Volver al listado
                        </a>
                    </div>
                    <hr>
                    @include('admin.layouts.partials.errors_messages')
                    <hr>
                    Datos del Evento
                    @include('admin.events.partials.edit_event')
                    <hr>
                    Ubicaciones?
                </div>
            </div>
        </div>
    </div>
</div>
<!-- @include('admin.organizations.partials.modal_custom_place') -->
@endsection
@section('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script src="{{ asset('libs/jquery-tagsinput/js/jquery.tagsinput-revisited.js') }}"></script>
<script>

    // ----------------------------------------------------
    // Functions
    // ----------------------------------------------------

    


    // ----------------------------------------------------
    // END Functions
    // ----------------------------------------------------

    
    $(document).ready(function(){


        // ----------------------------------------------------
        // (Tab) Editar datos del evento
        // ----------------------------------------------------


        // Formatear slug a partir del title
        $("#title").stringToSlug({
            callback: function(text){
                $('#slug').val(text);
            }
        }); 


        // Callback Obtener listado de eventos
        function responseGetData(data){
            var result = [];
            result.push(data);
            console.log(result)
            return (result)

        };

        
        // Habilitar manejo de categorias / tags ( agrupados en eventos)
        // autocomplete: The Autocomplete widgets provides suggestions while you type into the field
        // https://jqueryui.com/autocomplete/
        $('#tags_events').tagsInput({
            'autocomplete': {
                source : function (request, responseGetData) {
                    var term = request.term;
                    $.get(base_url+'/api/events/'+term, function(data){
                        responseGetData(data);
                    });
                }
            } 
        });

        // Habilitar manejo de tags (fuera de grupo eventos)
        $('#tags_no_events').tagsInput({
            'autocomplete': {
                source : function (request, responseGetData) {
                    var term = request.term;
                    $.get(base_url+'/api/tags/'+term, function(data){
                        responseGetData(data);
                    });
                }
            }
        });


    }); // END document.ready
</script>
@endsection
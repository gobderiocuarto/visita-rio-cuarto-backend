@extends('admin.layouts.app')
@section('meta_title') {{ config('app.name'). " - Admin :: Crear Evento" }} @endsection
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
            <li class="breadcrumb-item active" aria-current="page">Crear</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-header">
                    <h2>Crear evento</h2>
                </div>
                <form id="form_organization_event" method="POST" action="{{ url('/admin/events') }}">
                    @csrf
                    <div class="card-body mt-2">
                        <div class="alert alert-secondary mb-3 text-right" >
                            <a href="{{ route('events.index') }}" class="btn btn-sm btn-primary ">
                            Volver al listado
                            </a>
                        </div>
                        <hr>
                        @include('admin.layouts.partials.errors_messages')
                        <div class="form-group row">
                            <label for="title" class="col-md-3 col-form-label text-md-right">Título (*)</label>
                            <div class="col-md-8">
                                <input name="title" id="title" type="text" class="form-control" value="{{ old('title') }}" required minlength=3>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="slug" class="col-md-3 col-form-label text-md-right">Slug</label>
                            <div class="col-md-8">
                                <input name="slug" id="slug" type="text" class="form-control" value="{{ old('slug') }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="summary" class="col-md-3 col-form-label text-md-right">Información principal (*)</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="summary" rows="5" required>{{ old('summary') }}</textarea>
                                <small class="form-text text-muted mt-2">Longitud ideal: 160 caracteres aprox.</small>
                            </div>
                        </div>
                        <hr />
                        <div class="form-group row">
                            <label for="group_id" class="col-md-3 col-form-label text-md-right">Dependencia / Grupo</label>
                            <div class="col-md-8">
                                <input name="group_id" id="group_id" type="text" class="form-control" value="{{ $group->name }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-3">
                                <button type="submit" class="btn btn-success">Crear evento</button>
                            </div>
                            <div class="col-md-4">
                                <button type="reset" class="btn btn-outline-dark">Limpiar campos</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script src="{{ asset('libs/jquery-tagsinput/js/jquery.tagsinput-revisited.js') }}"></script>
<script>

    // ----------------------------------------------------
    // Functions
    // ----------------------------------------------------

    // CallBack btener listado de etiquetas
    function responseGetData(data){

        var result = [];
        result.push(data);
        //console.log(result)
        return (result)

    };

    // ----------------------------------------------------
    // END Functions
    // ----------------------------------------------------


    $(document).ready(function(){

        // Formatear slug a partir del name
        $("#title").stringToSlug({
            callback: function(text){
                $('#slug').val(text);
            }
        });


        // Obtener listado de servicios (typeahead)
        $('#tags').tagsInput({
            'autocomplete': {
                source : function (request, responseGetData) {
                    var term = request.term;
                    console.log(term)
                    $.get(base_url+'/api/services/'+term, function(data){
                        //console.log(data)
                        responseGetData(data);
                    });
                }
            } 
        });

    });
</script>
@endsection
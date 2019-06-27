@extends('admin.layouts.app')
@section('meta_title') {{ config('app.name'). " - Admin :: Editar espacio" }} @endsection
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
            <li class="breadcrumb-item"><a href="{{ url('/admin/places') }}">Espacios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>Editar espacio:</h3>
                    <h2><strong>"{{ $place->name }}"</strong></h2>
                </div>
                <form id="form_create_place" method="POST" action='{{ url("/admin/places/$place->id") }}' method="POST" enctype="multipart/form-data">
                    {{ method_field('PATCH') }}
                    @csrf
                    <div class="card-body mt-2">
                        <div class="alert alert-secondary mb-3 text-right" >
                            <a href="{{ route('places.index') }}" class="btn btn-sm btn-primary ">
                            Volver al listado
                            </a>
                        </div>
                        <hr>
                        @include('admin.layouts.partials.errors_messages')
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Nombre (*)</label>
                            <div class="col-md-8">
                                <input name="name" id="name" type="text" class="form-control" value="{{ $place->name }}" autofocus required minlength=3>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="slug" class="col-md-3 col-form-label text-md-right">Slug</label>
                            <div class="col-md-8">
                                <input name="slug" id="slug" type="text" class="form-control" value="{{ $place->slug }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-md-3 col-form-label text-md-right">Breve descripción </label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="description" rows="8">{{ $place->description }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="street_id" class="col-md-3 col-form-label text-md-right">Calle(*)</label>
                            <div class="col-md-8">
                                <select id="street_id" name="street_id" class="form-control form-control-xl selectpicker" data-live-search="true" data-size="10" required>
                                    <option value="" >Selecciona...</option>
                                    @foreach($streets as $street)
                                    <option value="{{ $street->id }}" @if ($street->id == $place->address->street_id) selected @endif >
                                        {{ $street->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="number" class="col-md-3 col-form-label text-md-right">Número (*)</label>
                            <div class="col-md-3">
                                <input name="number" id="number" type="number" class="form-control" value="{{ $place->address->number }}" required>
                            </div>

                            <label for="floor" class="col-md-2 col-form-label text-md-right">Piso / Dpto</label>
                            <div class="col-md-3">
                                <input name="floor" id="floor" type="text" class="form-control" value="{{ $place->address->floor }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="lat" class="col-md-3 col-form-label text-md-right">Latitud</label>
                            <div class="col-md-3">
                                <input name="lat" id="lat" type="text" class="form-control" value="{{ $place->address->lat }}">
                            </div>
                            <label for="lng" class="col-md-2 col-form-label text-md-right">Longitud</label>
                            <div class="col-md-3">
                                <input name="lng" id="lng" type="text" class="form-control" value="{{ $place->address->lng }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="zone_id" class="col-md-3 col-form-label text-md-right">Zona</label>
                            <div class="col-md-6">
                                <select id="zone_id" name="zone_id" class="form-control form-control-xl selectpicker" data-live-search="true">
                                    <option value="0">Selecciona...</option>
                                    @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}" @if ($zone->id == $place->address->zone_id) selected="selected" @endif>
                                        {{ $zone->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <hr />
                        <div class="form-group row">
                            <label for="tags" class="col-md-3 col-form-label text-md-right">Etiquetas asociadas (separar mediante comas)</label>
                            <div class="col-md-8">
                                <input name="tags" id="tags" type="text" class="form-control" data-role="tagsinput" value="{{ $tags }}" placeholder="Etiquetas">
                            </div>
                        </div>
                        <hr />
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Imagen principal: </label>
                            @if($place->file)
                            <div class="col-md-3">
                                <a target="_blank" href="{{ Storage::url("places/{$place->id}/{$place->file->file_path}") }}">
                                    <img class="img-fluid" src="{{ Storage::url("places/{$place->id}/thumbs/{$place->file->file_path}") }}" alt="{{$place->file->file_alt}}">
                                </a>
                            </div>
                            @else
                            <div class="col-md-8">
                                <span class="form-text font-italic mt-2">Aún no se ha cargado ninguna imagen</span>
                            </div>
                            @endif
                        </div>
                        <hr />
                        <div class="form-group row">
                            <label for="file" class="col-md-3 col-form-label text-md-right">Cargar nueva imagen</label>
                            <div class="col-md-8">
                                <input type="file" id="file" name="file" class="" value="{{ old('file') }}">
                                <small class="form-text text-muted mt-2">Tamaño máximo ideal de imagen: 1000 x 1000 px</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="file_alt" class="col-md-3 col-form-label text-md-right">Texto alternativo</label>
                            <div class="col-md-8">
                                <input type="text" id="file_alt" name="file_alt" class="form-control" @if($place->file) value="{{$place->file->file_alt}}" @else value="" @endif>
                                <small class="form-text text-muted mt-2">Texto asociado a la imagen. Asegura que no se pierda información sea porque las imágenes no están disponibles, el lector ha desactivado las imágenes en su navegador o porqué está utilizando un lector de pantalla debido a que padece una deficiencia visual. </small>
                            </div>
                        </div>
                        <hr />
                    </div>
                    <div class="card-footer">
                        <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-3">
                                <button type="submit" class="btn btn-success">Actualizar espacio</button>
                            </div>
                            <div class="col-md-4">
                                <button id="resetForm" type="reset" class="btn btn-outline-dark">Restaurar datos</button>
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

    // Obtener listado de servicios (tags group servicios) (callback)
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

        $('.selectpicker').selectpicker();

        $("#name, #slug").stringToSlug({
            callback: function(text){
                $('#slug').val(text);
            }
        }); 

        // Obtener listado de servicios
        $('#tags').tagsInput({
            'autocomplete': {
                source : function (request, responseGetData) {
                    var term = request.term;
                    //console.log(term)
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
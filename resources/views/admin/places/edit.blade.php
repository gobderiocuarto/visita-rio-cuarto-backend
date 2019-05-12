@extends('admin.layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
            <li class="breadcrumb-item"><a href="/admin/places">Espacios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            @include('admin.layouts.partials.errors_messages')
            <div class="card">
                <div class="card-header">
                    <h3>Editar espacio:</h3>
                    <h2><strong>"{{ $place->name }}"</strong></h2>
                </div>
                <form id="form_create_place" method="POST" action="/admin/places/{{ $place->id }}" method="POST" enctype="multipart/form-data">
                    {{ method_field('PATCH') }}
                    @csrf
                    <div class="card-body mt-2">
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
                                    <option value="" >Selecciona...</option>
                                    @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}" @if ($zone->id == $place->address->zone_id) selected @endif>
                                        {{ $zone->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if($place->file)
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Imagen principal</label>
                            <div class="col-md-3 ">
                                <a target="_blank" href="{{ Storage::url("places/{$place->id}/{$place->file->file_path}") }}">
                                    <img class="img-fluid" src="{{ Storage::url("places/{$place->id}/thumbs/{$place->file->file_path}") }}" alt="{{$place->file->file_alt}}">
                                </a>
                            </div>
                        </div>
                        @endif
                        <div class="form-group row">
                            <label for="file" class="col-md-3 col-form-label text-md-right">Cargar nueva imagen</label>
                            <div class="col-md-8">
                                <input type="file" id="file" name="file" class="" value="{{ old('file') }}">
                                <small class="form-text text-muted mt-2">El tamaño de la imagen debe ser etc, etc...</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="file_alt" class="col-md-3 col-form-label text-md-right">Texto alternativo</label>
                            <div class="col-md-8">
                                <input type="text" id="file_alt" name="file_alt" class="form-control" value="@if($place->file) {{$place->file->file_alt}} @endif">
                                <small class="form-text text-muted mt-2">Lorem ipsum ...</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-3">
                                <button type="submit" class="btn btn-primary">Actualizar espacio</button>
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
<script>
    $(document).ready(function(){

        $('.selectpicker').selectpicker();

        $("#name, #slug").stringToSlug({
            callback: function(text){
                $('#slug').val(text);
            }
        }); 

    });
</script>
@endsection
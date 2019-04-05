@extends('layouts.app')

@section('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.7/dist/css/bootstrap-select.min.css">
@endsection
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <nav class="col-md-8" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
            <li class="breadcrumb-item"><a href="/admin/places">Espacios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
          </ol>
        </nav>
        <div class="col-md-8">
            <div class="card">
                <form id="form_create_place" method="POST" action="/admin/places/{{ $place->id }}" method="POST">
                {{ method_field('PATCH') }}b
                    <div class="card-header">
                        Editar espacio
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                        <div class="alert alert-warning" role="alert">
                            <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        </div>
                        @elseif (Session::has('message'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('message') }}
                        </div>
                        @endif
                        @csrf
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
                                <textarea class="form-control" name="description" rows="3">{{ $place->description }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="street" class="col-md-3 col-form-label text-md-right">Calle(*)</label>
                            <div class="col-md-8">
                                <select id="street" name="street" class="form-control form-control-xl selectpicker" data-live-search="true"  required>
                                    <option value="" >Selecciona...</option>
                                    @foreach($streets as $street)
                                    <option value="{{ $street->id }}" @if ($street->id == $place->street) selected @endif >
                                        {{ $street->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="number" class="col-md-3 col-form-label text-md-right">Número (*)</label>
                            <div class="col-md-3">
                                <input name="number" id="number" type="number" class="form-control" value="{{ $place->number }}" required>
                            </div>

                            <label for="depto" class="col-md-2 col-form-label text-md-right">Piso / Dpto</label>
                            <div class="col-md-3">
                                <input name="depto" id="depto" type="text" class="form-control" value="{{ $place->depto }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="lat" class="col-md-3 col-form-label text-md-right">Latitud</label>
                            <div class="col-md-3">
                                <input name="lat" id="lat" type="text" class="form-control" value="{{ $place->lat }}">
                            </div>
                            <label for="lng" class="col-md-2 col-form-label text-md-right">Longitud</label>
                            <div class="col-md-3">
                                <input name="lng" id="lng" type="text" class="form-control" value="{{ $place->lng }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="zone" class="col-md-3 col-form-label text-md-right">Zona</label>
                            <div class="col-md-8">
                                <select id="zone" name="zone" class="form-control form-control-xl selectpicker" data-live-search="true">
                                    <option value="" >Selecciona...</option>
                                    @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}" @if ($zone->id == $place->zone) selected @endif>
                                        {{ $zone->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-3">
                                <button type="submit" class="btn btn-primary">Actualizar</button>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.7/dist/js/bootstrap-select.min.js"></script>
<script src="{{ asset('libs/stringToSlug/jquery.stringToSlug.min.js') }}"></script>
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
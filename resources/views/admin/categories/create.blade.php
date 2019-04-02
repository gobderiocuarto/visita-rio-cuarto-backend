@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-md-8" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
            <li class="breadcrumb-item"><a href="/admin/categories">Categorias</a></li>
            <li class="breadcrumb-item active" aria-current="page">Crear Categoría</li>
          </ol>
        </nav>
        <div class="col-md-8">
            <div class="card">
                <form id="form_organization_category" method="POST" action="/admin/categories" method="POST">
                    <div class="card-header">
                        Crear Categoría
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
                            <ul>
                                <li>{{ Session::get('message') }}</li>
                            </ul>
                        </div>
                        @endif
                        @csrf
                        <div class="form-group row">
                            <label for="category_id" class="col-md-3 col-form-label text-md-right">Categoría padre (*)</label>
                            <div class="col-md-8">
                                <select id="category_id" name="category_id" class="form-control form-control-xl" required>
                                    <option value="" >Selecciona...</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @if(1 == old('category_0')) selected @endif>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Nombre (*)</label>
                            <div class="col-md-8">
                                <input name="name" id="name" type="text" class="form-control" value="{{ old('name') }}" autofocus required minlength=3>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="slug" class="col-md-3 col-form-label text-md-right">Slug (*)</label>
                            <div class="col-md-8">
                                <input name="slug" id="slug" type="text" class="form-control" value="{{ old('name') }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-md-3 col-form-label text-md-right">Breve descripción </label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-3">
                                <button type="submit" class="btn btn-primary">Crear Categoría</button>
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
<script src="{{ asset('libs/stringToSlug/jquery.stringToSlug.min.js') }}"></script>
<script>
    $(document).ready(function(){

        $("#name, #slug").stringToSlug({
            callback: function(text){
                $('#slug').val(text);
            }
        });    
    });
</script>
@endsection
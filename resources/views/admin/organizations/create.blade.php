@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-md-8" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
            <li class="breadcrumb-item"><a href="/admin/organizations">Organizaciones</a></li>
            <li class="breadcrumb-item active" aria-current="page">Crear Organización</li>
          </ol>
        </nav>
        <div class="col-md-8">
            <div class="card">
                <form id="form_organization_category" method="POST" action="/admin/organizations" method="POST">
                    <div class="card-header">
                        <h2>Crear Organizacion / Servicio</h2>
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
                            <label for="category_id" class="col-md-3 col-form-label text-md-right">Categoría (*)</label>
                            <div class="col-md-8">
                                <select id="category_id" name="category_id" class="form-control form-control-xl" autofocus required>
                                    <option value="" >Selecciona...</option>
                                    @foreach($categories as $category)
                                        <optgroup label="{{ $category->name }}">
                                            @foreach($category->categories as $option)
                                            <option value="{{ $option->id }}">{{ $option->name }}</option>
                                            @endforeach                                    
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Nombre (*)</label>
                            <div class="col-md-8">
                                <input name="name" id="name" type="text" class="form-control" value="{{ old('name') }}" required minlength=3>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="slug" class="col-md-3 col-form-label text-md-right">Slug</label>
                            <div class="col-md-8">
                                <input name="slug" id="slug" type="text" class="form-control" value="{{ old('slug') }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-md-3 col-form-label text-md-right">Breve descripción </label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-3 col-form-label text-md-right">Email (*)</label>
                            <div class="col-md-8">
                                <input name="email" id="email" type="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone" class="col-md-3 col-form-label text-md-right">Teléfono</label>
                            <div class="col-md-8">
                                <input name="phone" id="phone" type="text" class="form-control" value="{{ old('phone') }}" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="web" class="col-md-3 col-form-label text-md-right">Web</label>
                            <div class="col-md-8">
                                <input name="web" id="web" type="text" class="form-control" value="{{ old('web') }}">
                            </div>
                        </div>                        
                    </div>

                    <div class="card-footer">
                        <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-3">
                                <button type="submit" class="btn btn-primary">Crear Organización</button>
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
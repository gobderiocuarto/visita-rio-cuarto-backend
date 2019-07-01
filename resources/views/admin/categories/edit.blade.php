@extends('admin.layouts.app')
@section('meta_title') {{ config('app.name'). " - Admin :: Editar categorías" }} @endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin/') }}">Admin</a></li>
            <li class="breadcrumb-item"><a href='{{ url("/admin/categories") }}'>Categorias</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>Editar categoría:</h3>
                    <h2><strong>"{{ $category->name }}"</strong></h2>
                </div>
                <form id="form_organization_category" method="POST" action='{{ url("/admin/categories/{$category->id}") }}' method="POST">
                    {{ method_field('PATCH') }}
                    @csrf
                    <div class="card-body mt-2">
                        <div class="alert alert-secondary mb-3 text-right" >
                            <a href="{{ route('categories.index') }}" class="btn btn-sm btn-primary ">
                            Volver al listado
                            </a>
                        </div>
                        <hr>
                        @include('admin.layouts.partials.errors_messages')
                        <div class="form-group row">
                            <label for="category_id" class="col-md-3 col-form-label text-md-right">Categoría superior (*)</label>
                            <div class="col-md-8">
                                <select id="category_id" name="category_id" class="form-control" required>
                                    <option value="">Selecciona...</option>
                                    <option value="0" @if(0 == $category->category_id) selected @endif>(Es categoría principal / Raíz)</option>
                                    @foreach($categories as $option)
                                    <option value="{{ $option->id }}" @if($option->id == $category->category_id) selected @endif>{{ $option->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Nombre (*)</label>
                            <div class="col-md-8">
                                <input name="name" id="name" type="text" class="form-control" value="{{ $category->name }}" autofocus required minlength=3>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="slug" class="col-md-3 col-form-label text-md-right">Slug</label>
                            <div class="col-md-8">
                                <input name="slug" id="slug" type="text" class="form-control" value="{{ $category->slug }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-md-3 col-form-label text-md-right">Breve descripción </label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="description" rows="3">{{ $category->description }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-3">
                                <button type="submit" class="btn btn-success">Actualizar categoría</button>
                            </div>
                            <div class="col-md-4">
                                <button type="reset" class="btn btn-outline-dark ">Restaurar datos</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="list_page" value="{{ $list_page }}">
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
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
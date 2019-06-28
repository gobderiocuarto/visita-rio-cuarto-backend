@extends('admin.layouts.app')
@section('meta_title') {{ config('app.name'). " - Admin :: Crear Organización" }} @endsection
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
            <li class="breadcrumb-item"><a href="{{ url('/admin/organizations') }}">Organizaciones</a></li>
            <li class="breadcrumb-item active" aria-current="page">Crear</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-header">
                    <h2>Crear organización</h2>
                </div>
                <form id="form_organization_category" method="POST" action="{{ url('/admin/organizations') }}">
                    @csrf
                    <div class="card-body mt-2">
                        <div class="alert alert-secondary mb-3 text-right" >
                            <a href="{{ route('organizations.index') }}" class="btn btn-sm btn-primary ">
                            Volver al listado
                            </a>
                        </div>
                        <hr>
                        @include('admin.layouts.partials.errors_messages')
                        <div class="form-group row">
                            <label for="category_id" class="col-md-3 col-form-label text-md-right">Categoría (*)</label>
                            <div class="col-md-8">
                                <select class="selectpicker form-control form-control-xl" id="category_id" name="category_id" autofocus required>
                                    <option value="" >Selecciona...</option>
                                    @foreach($categories as $category)
                                    <option style="font-weight: bold;" value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                    @foreach($category->categories as $subcategory)
                                    <option style="text-indent: 10px;" value="{{ $subcategory->id }}" >
                                        {{ $subcategory->name }}
                                    </option>
                                    @endforeach                             
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
                            <label for="phone" class="col-md-3 col-form-label text-md-right">Teléfono (*)</label>
                            <div class="col-md-8">
                                <input name="phone" id="phone" type="text" class="form-control" value="{{ old('phone') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-3 col-form-label text-md-right">Email</label>
                            <div class="col-md-8">
                                <input name="email" id="email" type="email" class="form-control" value="{{ old('email') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="web" class="col-md-3 col-form-label text-md-right">Web</label>
                            <div class="col-md-8">
                                <input name="web" id="web" type="text" class="form-control" value="{{ old('web') }}">
                            </div>
                        </div>
                        <hr />
                        <div class="form-group row">
                            <label for="tags" class="col-md-3 col-form-label text-md-right">Etiquetas asociadas (separar mediante comas)</label>
                            <div class="col-md-8">
                                <input name="tags" id="tags" type="text" class="form-control" data-role="tagsinput" value=""  placeholder="Etiquetas">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-3">
                                <button type="submit" class="btn btn-success">Crear organización</button>
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

        // Formatear slug a partir del name
        $("#name").stringToSlug({
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
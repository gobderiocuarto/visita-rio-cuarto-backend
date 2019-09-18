@extends('admin.layouts.app')
@section('meta_title') {{ config('app.name'). " - Admin :: Editar Rol" }} @endsection
@section('styles')
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Admin</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/roles') }}">Roles</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-header">
                    <h2>Editar Rol de usuario</h2>
                </div>
                <form id="form_create_role" method="POST" action="{{ route('roles.update', $role->id) }}">
                    {{ method_field('PATCH') }}
                    @csrf  
                    <div class="card-body mt-2">
                        <div class="alert alert-secondary mb-3 text-right" >
                            <a href="{{ route('roles.index') }}" class="btn btn-sm btn-primary ">
                            Volver al listado
                            </a>
                        </div>
                        <hr>
                        @include('admin.layouts.partials.errors_messages')
                        <div class="form-group">
                            <label for="name">Nombre de la etiqueta</label>
                            <input class="form-control" id="name" name="name" type="text" value="{{ $role->name }}" required="required">
                        </div>
                        <div class="form-group">
                            <label for="slug">URL Amigable</label>
                            <input class="form-control" id="slug" name="slug" type="text" value="{{ $role->slug }}" required="required" readonly>
                        </div>
                        <div class="form-group">
                            <label for="description">Descripci&oacute;n</label>
                            <textarea class="form-control" name="description" cols="50" rows="10" id="description">{{ $role->description }}</textarea>
                        </div>
                        <hr>
                        <h3>Permisos especiales</h3>
                        <div class="form-group">
                            <label><input name="special" type="checkbox" value="all-access" @if ($role->special == "all-access") checked="checked" @endif> 
                                Acceso total
                            </label>
                            <label><input name="special" type="checkbox" value="no-access" @if ($role->special == "no-access") checked="checked" @endif> 
                            Ningún acceso
                            </label>
                        </div>
                        <hr>
                        <h3>Lista de permisos</h3>
                        <div class="form-group">
                            <ul class="list-unstyled">
                                @foreach ($permissions as $permission)
                                <li>
                                <label>
                                <input name="permissions[]" type="checkbox" value="{{ $permission->id }}" @if(in_array($permission->id, $actual_roles)) checked="checked" @endif>
                                    {{ $permission->name }} <em>({{ $permission->description }})</em>
                                </label>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-3">
                                <button type="submit" class="btn btn-success">Actualizar Rol</button>
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

        // Formatear slug a partir del name
        $("#name").stringToSlug({
            callback: function(text){
                $('#slug').val(text);
            }
        }); 
    
    });
    </script>
@endsection
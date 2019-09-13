@extends('admin.layouts.app')
@section('meta_title') {{ config('app.name'). " - Admin :: Editar Usuario" }} @endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin/') }}">Admin</a></li>
            <li class="breadcrumb-item"><a href='{{ url("/admin/users") }}'>Usuarios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>Editar datos de usuario:</h3>
                    <h2><strong>"{{ $user->name }}"</strong></h2>
                </div>
                <form id="form_organization_category" method="POST" action='{{ route("users.update",$user->id) }}' method="POST">
                    {{ method_field('PUT') }}
                    @csrf
                    <div class="card-body mt-2">
                        <div class="alert alert-secondary mb-3 text-right" >
                            <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary ">
                            Volver al listado
                            </a>
                        </div>
                        <hr>
                        @include('admin.layouts.partials.errors_messages')
                        <hr>
                        <div class="form-group row">
                            <label for="email" class="col-md-3 col-form-label text-md-right">Email</label>
                            <div class="col-md-8">
                                <input name="email" id="email" type="text" class="form-control" value="{{ $user->email }}" readonly disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Nombre (*)</label>
                            <div class="col-md-8">
                                <input name="name" id="name" type="text" class="form-control" value="{{ $user->name }}" autofocus required minlength=3>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="section" class="col-md-3 col-form-label text-md-right">Sección (*)</label>
                            <div class="col-md-8">
                                <select id="category_id" name="category_id" class="form-control" required>
                                    <option value="">Selecciona...</option>
                                    <option value="1">Gobierno Abierto</option>
                                    <option value="2">Turismo</option>
                                    <option value="3">Cultura</option>
                                    <option value="4">Deportes</option>
                                    <option value="5">Otros</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <h3>Lista de roles</h3>
                        </br>
                        <div class="form-group row">
                            <ul class="list-unstyled col-md-10 offset-md-1">
                            @foreach ($roles as $role)
                                <li>
                                    <label>
                                    <input name="roles[]" type="checkbox" value="{{ $role->id }}" @if(in_array($role->id, $array_roles_id)) checked="checked" @endif> 
                                    {{ $role->name }} <em>({{ $role->description }})</em>
                                    </label>
                                </li>
                            @endforeach
                            </ul>
                        </div>

                    </div>
                    <div class="card-footer">
                        <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-3">
                                <button type="submit" class="btn btn-success">Actualizar usuario</button>
                            </div>
                            <div class="col-md-4">
                                <button type="reset" class="btn btn-outline-dark ">Restaurar datos</button>
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
  
    });
</script>
@endsection
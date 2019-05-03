@extends('admin.layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
            <li class="breadcrumb-item"><a href="/admin/services">Servicios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar Servicio</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            @include('admin.layouts.partials.errors_messages')
            <div class="card">
                <div class="card-header">
                    <h3>Editar servicio:</h3>
                    <h2><strong>"{{ $service->name }}"</strong></h2>
                </div>
                <form id="form_edit_service" method="POST" action="/admin/services/{{ $service->id }}" method="POST">
                    {{ method_field('PATCH') }}
                    @csrf
                    <div class="card-body mt-2">
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Nombre (*)</label>
                            <div class="col-md-8">
                                <input name="name" id="name" type="text" class="form-control" value="{{ $service->name }}" autofocus required minlength=3>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-3">
                                <button type="submit" class="btn btn-primary">Actualizar servicio</button>
                            </div>
                            <div class="col-md-4">
                                <button type="reset" class="btn btn-outline-dark">Limpiar campos</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="card-header">
                    Lugares asociados al servicio
                </div>
                <div class="card-body">
                    <form action="services/{{ $service->id }}/addPlace/" method="POST">
                        <div class="form-group row">
                            <label for="place" class="col-md-12 col-form-label">Agregar Lugar...</label>
                            <div class="col-md-8">
                                <select id="place" name="place" class="form-control form-control-xl selectpicker" data-live-search="true" data-default-value="" required>
                                    <option value="">Agregar...</option>
                                    <option value="1"><span style="font-weight:bold">Luis Maria</span>, Sucursal La Ribera, Breana Light 222, La Ribera</option>     
                                    <option value="2"><strong>Luis María</strong>, Sucursal Centro, Colón 654321</option>       
                                    <option value="3"><strong>Luis María</strong>, Sucursal Banda Norte, Calle Tal 654321</option>
                                    <option value="4">
                                        Kiosco El Sapito, sucursal Bv. Roca, Cesar Tunnel 12336
                                    </option>
<<<<<<< Updated upstream
                                </select>@extends('admin.layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
            <li class="breadcrumb-item"><a href="/admin/services">Servicios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar Servicio</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            @include('admin.layouts.partials.errors_messages')
            <div class="card">
                <div class="card-header">
                    <h3>Editar servicio:</h3>
                    <h2><strong>"{{ $service->name }}"</strong></h2>
                </div>
                <form id="form_edit_service" method="POST" action="/admin/services/{{ $service->id }}" method="POST">
                    {{ method_field('PATCH') }}
                    @csrf
                    <div class="card-body mt-2">
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Nombre (*)</label>
                            <div class="col-md-8">
                                <input name="name" id="name" type="text" class="form-control" value="{{ $service->name }}" autofocus required minlength=3>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-3">
                                <button type="submit" class="btn btn-primary">Actualizar servicio</button>
                            </div>
                            <div class="col-md-4">
                                <button type="reset" class="btn btn-outline-dark">Limpiar campos</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="card-header">
                    Lugares asociados al servicio
                </div>
                <div class="card-body">
                    <form action="services/{{ $service->id }}/addPlace/" method="POST">
                        <div class="form-group row">
                            <label for="place" class="col-md-12 col-form-label">Agregar Lugar...</label>
                            <div class="col-md-8">
                                <select id="place" name="place" class="form-control form-control-xl selectpicker" data-live-search="true" data-default-value="" required>
                                    <option value="">Agregar...</option>
                                    <option value="1"><span style="font-weight:bold">Luis Maria</span>, Sucursal La Ribera, Breana Light 222, La Ribera</option>     
                                    <option value="2"><strong>Luis María</strong>, Sucursal Centro, Colón 654321</option>       
                                    <option value="3"><strong>Luis María</strong>, Sucursal Banda Norte, Calle Tal 654321</option>
                                    <option value="4">
                                        Kiosco El Sapito, sucursal Bv. Roca, Cesar Tunnel 12336
                                    </option>
=======
>>>>>>> Stashed changes
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-sm btn-success">Agregar</button>
                            </div>
                        </div>
                    </form>


                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Organización</th>
                                <th>Calle y número</th>
                                <th colspan="2">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Luis Maria</strong></td>
                                <td>Sucursal La Ribera, Breana Light 222, La Ribera</td>
                                <td width="10px">
                                    <button type="button" class="btn btn-sm btn-success">
                                        Editar
                                    </button>
                                </td>
                                <td width="10px">
                                    <form action="/admin/organizations/" method="POST">
                                         @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>

                            <tr>
                                <td><strong>Luis María</strong></td>
                                <td>Sucursal Centro, Colón 654321</td>
                                <td width="10px">
                                    <button type="button" class="btn btn-sm btn-success">
                                        Editar
                                    </button>
                                </td>
                                <td width="10px">
                                    <form action="/admin/organizations/" method="POST">
                                         @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Luis María</strong></td>
                                <td>Sucursal Banda Norte, Calle Tal 654321</td>
                                <td width="10px">
                                    <button type="button" class="btn btn-sm btn-success">
                                        Editar
                                    </button>
                                </td>
                                <td width="10px">
                                    <form action="/admin/organizations/" method="POST">
                                         @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @foreach($organizations as $organization)
                            <tr>
                                <td><strong>{{ $organization->name }}</strong></td>
                                <td>Calle</strong></strong></td>
                                <td width="10px">
                                    <button type="button" class="btn btn-sm btn-success">
                                        Editar
                                    </button>
                                </td>
                                <td width="10px">
                                    <form action="/admin/organizations/{{ $organization->id }}" method="POST">
                                         @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>   
                    </table>
                    
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
<<<<<<< Updated upstream
@endsection
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-sm btn-success">Agregar</button>
                            </div>
                        </div>
                    </form>


                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Organización</th>
                                <th>Calle y número</th>
                                <th colspan="2">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Luis Maria</strong></td>
                                <td>Sucursal La Ribera, Breana Light 222, La Ribera</td>
                                <td width="10px">
                                    <button type="button" class="btn btn-sm btn-success">
                                        Editar
                                    </button>
                                </td>
                                <td width="10px">
                                    <form action="/admin/organizations/" method="POST">
                                         @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>

                            <tr>
                                <td><strong>Luis María</strong></td>
                                <td>Sucursal Centro, Colón 654321</td>
                                <td width="10px">
                                    <button type="button" class="btn btn-sm btn-success">
                                        Editar
                                    </button>
                                </td>
                                <td width="10px">
                                    <form action="/admin/organizations/" method="POST">
                                         @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Luis María</strong></td>
                                <td>Sucursal Banda Norte, Calle Tal 654321</td>
                                <td width="10px">
                                    <button type="button" class="btn btn-sm btn-success">
                                        Editar
                                    </button>
                                </td>
                                <td width="10px">
                                    <form action="/admin/organizations/" method="POST">
                                         @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @foreach($organizations as $organization)
                            <tr>
                                <td><strong>{{ $organization->name }}</strong></td>
                                <td>Calle</strong></strong></td>
                                <td width="10px">
                                    <button type="button" class="btn btn-sm btn-success">
                                        Editar
                                    </button>
                                </td>
                                <td width="10px">
                                    <form action="/admin/organizations/{{ $organization->id }}" method="POST">
                                         @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>   
                    </table>
                    
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
=======
>>>>>>> Stashed changes
@endsection
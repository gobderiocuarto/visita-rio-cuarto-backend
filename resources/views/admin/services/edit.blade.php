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
                <form id="form_edit_service" method="POST" action="/admin/services/{{ $service->id }}">
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
            <div class="card mt-4">
                <div class="card-header">
                    <h3>Lugares asociados al servicio</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0" role="alert">
                        <form method="POST" action="/admin/services/{{ $service->id }}/organizations">
                            @csrf
                            <div class="form-group row">
                                <label for="place" class="col-md-12 col-form-label"><h5 class="alert-heading">Agregar Lugar...</h5></label>
                                <div class="col-md-10">
                                    <select id="organization" name="organization" class="form-control form-control-xl selectpicker" data-live-search="true" data-default-value="" required>
                                        <option value="">Seleccione...</option>
                                        @foreach ($list_orgs as $organization)
                                        <option value="{{ $organization->id }}">{{ $organization->name }}, ({{ $organization->category->name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-sm btn-success">Agregar</button>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <p class="mb-0">Whenever you need to, be sure to use margin utilities to keep things nice and tidy.</p>
                    </div>
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th width="25%">Organización</th>
                                <th>Calle y número</th>
                                <th colspan="2">Opciones</th>
                            </tr>
                        </thead>
                        <tbody class="mt-2">
                            @foreach($service_orgs as $organization)
                                <tr class="table-info">
                                    <td colspan="2"><strong>{{ $organization->name }}</strong></td>
                                    <td width="10px">
                                        <a href="{{ url('admin/organizations/'.$organization->id.'/edit') }}" class="btn btn-sm btn-success">Editar</a>
                                    </td>
                                    <td width="10px">
                                        <form action="{{ url('admin/services/'.$service->id.'/organizations/'.$organization->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                                @foreach($organization->places as $place)
                                <tr>
                                    <td><span class="font-italic">{{ $place->pivot->address_type_name }}</span></td>
                                    <td colspan="3">{{ $place->address->street->name }} {{ $place->address->number }}@if($place->address->floor)- {{ $place->address->floor }}@endif- <strong>{{$place->name}}</strong></td>
                                </tr>                                
                                @endforeach
                                @foreach($organization->addresses as $address)
                                <tr>
                                    <td><span class="font-italic">{{ $address->pivot->address_type_name }}</span></td>
                                    <td colspan="3">
                                        {{$address->street->name}} {{$address->number}}
                                        @if($address->floor)- {{ $address->floor }}@endif
                                        @if($address->zone)- <strong>{{$address->zone->name}}</strong>@endif
                                    </td>
                                </tr>                                
                                @endforeach
                                </br>
                            @endforeach
                        </tbody>   
                    </table>
                </div>
                {{ $service_orgs->render() }}
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
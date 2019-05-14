@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href='{{ url("/admin")}}'>Admin</a></li>
            <li class="breadcrumb-item active">Servicios</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            @include('admin.layouts.partials.errors_messages')
            <div class="card">
                <div class="card-header">
                    <h2>Listado de servicios</h2> 
                    <a href="{{ route('services.create') }}" class="pull-right btn btn-sm btn-primary">
                        Crear
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="10px">ID</th>
                                <th>Nombre</th>
                                <th colspan="2">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $service)
                            <tr>
                                <td>{{ $service->id }}</td>
                                <td>{{ $service->name }}</td>
                                <td width="10px">
                                    <a href="{{ route('services.edit', $service->id) }}" class="btn btn-sm btn-success">Editar</a>
                                </td>
                                <td width="10px">
                                    <form action='{{ url("/admin/services/$service->id") }}' method="POST">
                                        {{ method_field('DELETE') }}
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>   
                    </table>
                    {{ $services->render() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

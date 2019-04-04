@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-md-8" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
            <li class="breadcrumb-item active">Lugares</li>
          </ol>
        </nav>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Listado de Lugares</h2> 
                    <a href="{{ route('places.create') }}" class="pull-right btn btn-sm btn-primary">
                        Crear
                    </a>
                </div>
                <div class="card-body">

                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="10px">ID</th>
                                <th>Nombre</th>
                                <th colspan="3">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($places as $place)
                            <tr>
                                <td>{{ $place->id }}</td>
                                <td>{{ $place->name }}</td>
                                <td width="10px">
                                    <a href="{{ route('places.show', $place->id) }}" class="btn btn-sm btn-default">Ver</a>
                                </td>
                                <td width="10px">
                                    <a href="{{ route('places.edit', $place->id) }}" class="btn btn-sm btn-default">Editar</a>
                                </td>
                                <td width="10px">
                                    
                                    <button class="btn btn-sm btn-danger">
                                        Eliminar
                                    </button>                           
                                    
                                </td>
                            </tr>
                            @endforeach
                        </tbody>   
                    </table>
                        {{ $places->render() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

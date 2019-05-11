@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
            <li class="breadcrumb-item active">Organizaciones</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            @include('admin.layouts.partials.errors_messages')
            <div class="card">
                <div class="card-header">
                    <h2>Listado de organizaciones</h2> 
                    <a href="{{ route('organizations.create') }}" class="pull-right btn btn-sm btn-primary">
                        Crear
                    </a>
                </div>
                <div class="card-body">

                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="10px">ID</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th colspan="2">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($organizations as $organization)
                            <tr>
                                <td>{{ $organization->id }}</td>
                                <td>{{ $organization->name }}</td>
                                <td>@if ($organization->category->category) {{ $organization->category->category->name }} :: @endif {{ $organization->category->name }}</td>
                                <td width="10px">
                                    <a href="{{ route('organizations.edit', $organization->id) }}" class="btn btn-sm btn-success">Editar</a>
                                </td>
                                <td width="10px">
                                    <form action="/admin/organizations/{{ $organization->id }}" method="POST">
                                        {{ method_field('DELETE') }}
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>   
                    </table>
                    {{ $organizations->render() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

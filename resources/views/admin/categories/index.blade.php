@extends('admin.layouts.app')
@section('meta_title') {{ config('app.name'). " - Admin :: Listar categorías" }} @endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Admin</a></li>
            <li class="breadcrumb-item active">Categorias</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            @include('admin.layouts.partials.errors_messages')
            <div class="card">
                <div class="card-header">
                    <h2>Listado de categorías</h2>
                </div>
                <div class="card-body">
                    <div class="alert alert-secondary text-right mb-3" >
                        <a href="{{ route('categories.create') }}" class="btn btn-md btn-primary">
                            Crear categoría
                        </a>
                    </div>
                    <hr>   
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Nombre</th>
                                <th width="20px">ID</th>
                                <th colspan="2">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr class="table-info">
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->id }}</td>
                                <td width="10px">
                                    <a href="{{ route('categories.edit', [ 'id' => $category->id, 'pag' => $categories->currentPage()] ) }}" class="btn btn-sm btn-success">Editar</a>
                                </td>
                                <td width="10px">
                                    <form action='{{ url("/admin/categories/$category->id") }}' method="POST">
                                        {{ method_field('DELETE') }}
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                                @forelse($category->categories as $subcategory)
                                <tr>
                                    <td><i class="fas fa-angle-double-right"></i> {{ $subcategory->name }}</td>
                                    <td>{{ $subcategory->id }}</td>
                                    <td width="10px">
                                        <a href="{{ route('categories.edit', [ 'id' => $subcategory->id, 'pag' => $categories->currentPage()]) }}" class="btn btn-sm btn-success">Editar</a>
                                    </td>
                                    <td width="10px">
                                        <form action='{{ url("/admin/categories/$subcategory->id") }}' method="POST">
                                            {{ method_field('DELETE') }}
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4"><i class="fas fa-asterisk"></i> (No contiene subcategorías)</td></tr>
                                @endforelse
                            @endforeach
                        </tbody>   
                    </table>     	
                    
                </div>
                <div class="card-footer text-center">
                   <div>{{ $categories->links() }}</div> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('admin.layouts.app')
@section('meta_title') {{ config('app.name'). " - Admin :: Listar Organizaciones" }} @endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/admin') }}">Admin</a></li>
            <li class="breadcrumb-item active">Organizaciones</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-header">
                    <h2>Listado de organizaciones</h2> 
                </div>
                <div class="card-body mt-2">
                    @include('admin.layouts.partials.errors_messages')
                    <div class="alert alert-secondary text-right mb-3 row" >
                        <div class="col-9">
                            <form class="form-inline" action="{{url('/admin/organizations') }}" method="GET">
                                <input style="width: 200px;" class="form-control form-control-sm mr-sm-2" type="search" placeholder="Buscar" aria-label="Search" name="search" value="{{ $filter->search }}">
                                <select style="max-width: 200px;" class="form-control form-control-sm mr-sm-2" id="category" name="category" autofocus>
                                    <option value="0">Todas las categorías...</option>
                                    @foreach($categories as $category)
                                    <option style="font-weight: bold;" value="{{ $category->id }}" @if($category->id == $filter->category) selected @endif>{{ $category->name }}</option>
                                        @foreach($category->categories as $subcategory)
                                        <option style="text-indent: 10px;" value="{{ $subcategory->id }}" @if($subcategory->id == $filter->category) selected @endif>
                                            &nbsp;{{ $subcategory->name }}
                                        </option>
                                        @endforeach  
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-success my-sm-0" type="submit">Aplicar...</button>
                            </form>
                        </div>
                        <div class="col-3">
                            <a href="{{ route('organizations.create') }}" class="btn btn-sm btn-primary ">
                        Crear organización
                        </a>
                        </div>
                    </div>
                    <hr>                    
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th colspan="2">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($organizations as $organization)
                            <tr>
                                <td>{{ $organization->name }}</td>
                                <td>@if ($organization->category->category) {{ $organization->category->category->name }} :: @endif {{ $organization->category->name }}</td>                                
                                <td width="10px">
                                    <a href="{{ route('organizations.edit', $organization->id) }}" class="btn btn-sm btn-success">Editar</a>
                                </td>
                                <td width="10px">
                                    <form id="form_delete_org_{{ $organization->id }}" class="form_delete_org" action='{{ url("/admin/organizations/$organization->id") }}' method="POST" >
                                        {{ method_field('DELETE') }}
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger delete_org" data-id-org="{{ $organization->id }}">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4"><i class="fas fa-exclamation-triangle"></i> No se han encontrado Organizaciones</td></tr>
                            @endforelse
                        </tbody>   
                    </table>
                    {{ $organizations->render() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>

    // ----------------------------------------------------
    // Document Ready
    // ----------------------------------------------------

    $(document).ready(function(){

        // Confirmación de borrado mediante SweetAlert
        $('.delete_org').click(function() {
        // $( ".form_delete_org" ).submit(function( event ) {

            event.preventDefault();

            swal({
              title: "¡Atención!",
              text: "Se dispone a eliminar una organización...",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {

                let form = "#form_delete_org_"+$(this).data("id-org");
                $(form).submit();

              } else {
                swal("La acción fue cancelada");
              }
            });
        });
    }); //  END document ready
</script>
@endsection
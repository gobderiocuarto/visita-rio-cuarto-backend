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
                    <div class="alert alert-secondary text-right mb-3" >
                        <a href="{{ route('organizations.create') }}" class="btn btn-sm btn-primary ">
                        Crear organización
                        </a>
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
                            @foreach($organizations as $organization)
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
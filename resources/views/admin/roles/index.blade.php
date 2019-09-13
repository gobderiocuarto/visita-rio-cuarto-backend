@extends('admin.layouts.app')
@section('meta_title') {{ config('app.name'). " - Admin :: Listar Roles" }} @endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Admin</a></li>
            <li class="breadcrumb-item active">Roles</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-header">
                    <h2>Listado de roles de usuarios</h2>
                </div>
                <div class="card-body mt-2">
                    @include('admin.layouts.partials.errors_messages')   
                    <div class="alert alert-secondary text-right mb-3" >
                        @can('roles.create')
                        <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary">
                        Crear Rol
                        </a>
                        @endcan
                    </div>
                    <hr>
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th width="10px">ID</th>
                                <th>Título</th>
                                <th colspan="2">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                            <tr class="table-info">
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                @can('roles.edit')
                                <td width="10px">
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-success">Editar</a>
                                </td>
                                @endcan
                                @can('roles.destroy')
                                <td width="10px">
                                    <form id="form_delete_role_{{ $role->id }}" action='{{ route("roles.destroy", $role->id ) }}' method="POST">
                                        {{ method_field('DELETE') }}
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger delete_role" data-id-event="{{ $role->id }}">Eliminar</button>
                                    </form>
                                </td>
                                @endcan
                            </tr>
                            @empty
                            <tr><td colspan="4"><i class="fas fa-exclamation-triangle"></i> Aún no se han definido eventos</td></tr>
                            @endforelse
                        </tbody>   
                    </table>     	
                </div>
                <div class="card-footer text-center">
                   <div>{{ $roles->render() }}</div> 
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
        $('.delete_role').click(function() {

            event.preventDefault();

            swal({
              title: "¡Atención!",
              text: "Se dispone a eliminar un rol...",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {

                let form = "#form_delete_role_"+$(this).data("id-event");
                $(form).submit();

              } else {
                swal("La acción fue cancelada");
              }
            });
        });
    }); //  END document ready
</script>
@endsection
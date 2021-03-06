@extends('admin.layouts.app')
@section('meta_title') {{ config('app.name'). " - Admin :: Listar Usuarios" }} @endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Admin</a></li>
            <li class="breadcrumb-item active">Usuarios</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-header">
                    <h2>Listado de Usuarios</h2>
                </div>
                <div class="card-body mt-2">
                    @include('admin.layouts.partials.errors_messages')   
                    <hr>
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th width="10px">ID</th>
                                <th>Nombre</th>
                                <th>Portal / Grupo</th>
                                <th colspan="2">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr class="table-info">
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                @if ($user->group)
                                <td>{{ $user->group->name }}</td>
                                @else
                                <td>No asignado</td>
                                @endif
                                @can('users.edit')
                                <td width="10px">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-success">Editar</a>
                                </td>
                                @endcan
                                @can('users.destroy')
                                <td width="10px">
                                    <form id="form_delete_user_{{ $user->id }}" action='{{ route("users.destroy", $user->id ) }}' method="POST">
                                        {{ method_field('DELETE') }}
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger delete_user" data-id-event="{{ $user->id }}">Eliminar</button>
                                    </form>
                                </td>
                                @endcan
                            </tr>
                            @empty
                            <tr><td colspan="4"><i class="fas fa-exclamation-triangle"></i> Aún no se han definido usuarios</td></tr>
                            @endforelse
                        </tbody>   
                    </table>     	
                </div>
                <div class="card-footer text-center">
                   <div>{{ $users->render() }}</div> 
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
        $('.delete_user').click(function() {

            event.preventDefault();

            swal({
              title: "¡Atención!",
              text: "Se dispone a eliminar un Usuario...",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {

                let form = "#form_delete_user_"+$(this).data("id-event");
                $(form).submit();

              } else {
                swal("La acción fue cancelada");
              }
            });
        });
    }); //  END document ready
</script>
@endsection
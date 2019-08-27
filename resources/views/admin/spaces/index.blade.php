@extends('admin.layouts.app')
@section('meta_title') {{ config('app.name'). " - Admin :: Listar espacios" }} @endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Admin</a></li>
            <li class="breadcrumb-item active">Espacios</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-header">
                    <h2>Listado de espacios</h2> 
                </div>
                <div class="card-body mt-2">
                    @include('admin.layouts.partials.errors_messages')
                    <div class="alert alert-secondary text-right mb-3" >
                        <a href="{{ route('spaces.create') }}" class="btn btn-sm btn-primary ">
                        Crear espacio
                        </a>
                    </div>
                    <hr>
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Nombre</th>
                                <th colspan="2">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($spaces as $space)
                            <tr>
                                <td>{{ $space->name }}</td>
                                <td width="10px">
                                    <a href="{{ route('spaces.edit', $space->id) }}" class="btn btn-sm btn-success">Editar</a>
                                </td>
                                <td width="10px">
                                    <form id="form_delete_space_{{ $space->id }}" action='{{ url("/admin/spaces/$space->id") }}' method="POST">
                                        {{ method_field('DELETE') }}
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger delete_space" data-id-space="{{ $space->id }}">Eliminar</button>
                                    </form>             
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4"><i class="fas fa-exclamation-triangle"></i> Aún no se han definido espacios</td></tr>
                            @endforelse
                        </tbody>   
                    </table>
                    {{ $spaces->render() }}
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
        $('.delete_space').click(function() {

            event.preventDefault();

            swal({
              title: "¡Atención!",
              text: "Se dispone a eliminar un espacio...",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {

                let form = "#form_delete_space_"+$(this).data("id-space");
                $(form).submit();

              } else {
                swal("La acción fue cancelada");
              }
            });
        });
    }); //  END document ready
</script>
@endsection
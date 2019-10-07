@extends('admin.layouts.app')
@section('meta_title') {{ config('app.name'). " - Admin :: Listar eventos" }} @endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Admin</a></li>
            <li class="breadcrumb-item active">Eventos</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-header">
                    <h2>Listado de eventos</h2>
                </div>
                <div class="card-body mt-2">
                    @include('admin.layouts.partials.errors_messages')
                    <div class="alert alert-secondary text-right m-1 row" >
                        <div class="col-9">
                            <form class="form-inline" action="{{url('/admin/events') }}" method="GET">
                                <input style="width: 200px;" class="form-control form-control-sm mr-sm-2" type="search" placeholder="Buscar" aria-label="Search" name="search" value="{{ $filter->search }}">
                                <select style="max-width: 200px;" class="form-control form-control-sm mr-sm-2" id="category" name="category" autofocus>
                                    <option value="">Todas las categorías...</option>
                                    @foreach($event_tags as $tags)
                                    <option value="{{ $tags['name'] }}" @if( $tags['name'] == $filter->category) selected @endif >{{ $tags['name'] }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-success my-sm-0" type="submit">Aplicar...</button>
                            </form>                        
                        </div>
                        <div class="col-3">
                            <a href="{{ route('events.create') }}" class="btn btn-sm btn-primary">
                            Crear evento
                            </a>
                        </div>
                    </div>
                    <hr>     
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Título</th>
                                <th colspan="2">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                            <tr class="table-info">
                                <td>{{ $event->title }}</td>
                                <td width="10px">
                                    <a href='{{ url("/admin/events/$event->id/edit") }}' class="btn btn-sm btn-success">Editar</a>
                                </td>
                                <td width="10px">
                                    <form id="form_delete_event_{{ $event->id }}" action='{{ url("/admin/events/$event->id") }}' method="POST">
                                        {{ method_field('DELETE') }}
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger delete_event" data-id-event="{{ $event->id }}">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4"><i class="fas fa-exclamation-triangle"></i> Aún no se han definido eventos</td></tr>
                            @endforelse
                        </tbody>   
                    </table>     	
                    
                </div>
                <div class="card-footer text-center">
                   <div>{{ $events->links() }}</div> 
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
        $('.delete_event').click(function() {

            event.preventDefault();

            swal({
              title: "¡Atención!",
              text: "Se dispone a eliminar un evento...",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {

                let form = "#form_delete_event_"+$(this).data("id-event");
                $(form).submit();

              } else {
                swal("La acción fue cancelada");
              }
            });
        });
    }); //  END document ready
</script>
@endsection
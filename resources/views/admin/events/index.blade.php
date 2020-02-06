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
                                <th>Propietario</th>
                                <th colspan="4" width="20px">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                            <tr class="table-info">
                                <td>
                                    {{ $event->title }}
                                    @if ($event->frame == 'is-frame')
                                        <span class="font-italic">(E. marco)</span>
                                    @endif
                                </td>
                                <td>
                                @if ($event->group_id)
                                    {{ $event->group->name }}
                                @else
                                    <span class="font-italic">Sin propietario asignado</span>
                                @endif
                                </td>

                                @if ($event->state == 1)
                                <td style="padding-left: 0; padding-right: 0">
                                    <span class="btn btn-sm btn-default" title="Estado: Publicado">
                                    <i class="fas fa-eye fa-2x"></i></span>
                                </td>
                                @else
                                <td style="padding-left: 0; padding-right: 0">
                                    <span class="btn btn-sm btn-default" title="Estado: Borrador"><i class="fas fa-eye-slash fa-2x"></i></span>
                                </td>
                                @endif
                                @if (Gate::allows('event.edit', $event))
                                    <td style="padding-left: 0; padding-right: 0">
                                        <a href='{{ url("/admin/events/$event->id/edit") }}' class="btn btn-sm btn-default" title="Editar evento"><i class="fas fa-edit fa-2x"></i></a>
                                    </td>                                    
                                @else
                                    <td style="padding-left: 0; padding-right: 0">
                                        <a href='{{ url("/admin/events/$event->id") }}' class="btn btn-sm btn-default" title="Consultar detalle">
                                            <i class="fas fa-search fa-2x"></i>
                                        </a>
                                    </td>
                                @endif
                                @if (Gate::allows('event.associate', $event))
                                    <td style="padding-left: 0; padding-right: 0">
                                        <form id="form_asociate_event_{{ $event->id }}" action='{{ url("/admin/events/$event->id/asociate") }}' method="POST">
                                            {{ method_field('PATCH') }}
                                            @csrf
                                            @if(in_array($event->id, $events_in_group))
                                            <button type="submit" class="btn btn-sm btn-default unlink_event" data-id-event="{{ $event->id }}" title="Evento asociado al portal propio">
                                                <i class="fas fa-check-circle fa-2x"></i>
                                            </button>
                                            @else
                                            <button type="submit" class="btn btn-sm btn-default associate_event" data-id-event="{{ $event->id }}" title="Asociar evento al portal propio">
                                                <i class="fas fa-plus-circle fa-2x" style="color: red"></i>
                                            </button>
                                            @endif
                                        </form>
                                    </td>
                                @else
                                    <td style="padding-left: 0; padding-right: 0">
                                        @if( $event->group_id == auth()->user()->group_id)
                                        <div class="btn btn-sm btn-default"  title="Evento propio">
                                            <i class="fas fa-check-double fa-2x""></i>
                                        </div>
                                        @elseif(in_array($event->id, $events_in_group))
                                        <div class="btn btn-sm disabled" title="Evento asociado al portal ">
                                            <i class="fas fa-minus-circle fa-2x"></i>
                                        </div>
                                        @else
                                        <div class="btn btn-sm disabled" title="Evento no asociado al portal ">
                                            <i class="fas fa-minus-circle fa-2x"></i>
                                        </div>
                                        @endif
                                    </td>
                                @endif
                                @if (Gate::allows('event.delete', $event))
                                    <td style="padding-left: 0; padding-right: 0">
                                        <form id="form_delete_event_{{ $event->id }}" action='{{ url("/admin/events/$event->id") }}' method="POST">
                                            {{ method_field('DELETE') }}
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-default delete_event" data-id-event="{{ $event->id }}" title="Borrar evento">
                                                <i class="fas fa-trash-alt fa-2x"></i>
                                            </button>
                                        </form>
                                    </td>
                                @else
                                <td style="padding-left: 0; padding-right: 0">
                                    <div class="btn btn-sm disabled" title="No puede borrar el evento">
                                        <i class="fas fa-trash-alt fa-2x"></i>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5"><i class="fas fa-exclamation-triangle"></i> Aún no se han definido eventos</td>
                            </tr>
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



        $('.associate_event').click(function() {

            event.preventDefault();
            swal({
              title: "¡Atención!",
              text: "¿Desea vincular el evento al portal?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {

                let form = "#form_asociate_event_"+$(this).data("id-event");
                $(form).submit();

              } else {
                swal("La acción fue cancelada");
              }
            });         

        });



        $('.associate_event').click(function() {

            event.preventDefault();
            swal({
              title: "¡Atención!",
              text: "¿Desea vincular el evento al portal?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {

                let form = "#form_asociate_event_"+$(this).data("id-event");
                $(form).submit();

              } else {
                swal("La acción fue cancelada");
              }
            });         

        });


        $('.unlink_event').click(function() {

            event.preventDefault();
            swal({
              title: "¡Atención!",
              text: "¿Desea desvincular el evento de su portal?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {

                let form = "#form_asociate_event_"+$(this).data("id-event");
                $(form).submit();

              } else {
                swal("La acción fue cancelada");
              }
            });         

        });

    }); //  END document ready
</script>
@endsection
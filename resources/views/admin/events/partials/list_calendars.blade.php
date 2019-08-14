<div class="card">
    <div class="card-header">
        <h2>Calendario de Funciones</h2> 
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Fecha Inicio</th>
                    <th>Hora Inicio</th>
                    <th>Observaciones</th>
                    <th colspan="2" width="20px">&nbsp;</th>
                </tr>
            </thead>
            <tbody id="list_calendar">
                @forelse($event->calendars as $calendar)
                    <tr class="calendars" data-event-id="{{ $event->id }}" data-calendar-id="{{ $calendar->id }}">
                        <td class="start_date">{{ $calendar->start_date }}</td>
                        <td>{{ $calendar->start_time }} hs</td>
                        <td>{{ $calendar->observations }}</td>
                        <td class="calendar_btn_edit">
                            <button type="button" class="btn btn-sm btn-success" >
                                Editar
                            </button>
                        </td>
                        <td>
                            <form class="form_delete_calendar" method="POST" action='{{ url("/admin/events/$event->id/calendars/$calendar->id") }}'>
                                {{ method_field('DELETE') }}
                                @csrf
                                <button type="submit" class="delete_calendar btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="5"><strong>Aún no se han definido funciones</strong></td>
                </tr>
                @endforelse
            </tbody>   
        </table>
        <hr>
        <div class="alert alert-secondary text-right mb-3" >
            <button id="calendar_btn_add" class="pull-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> Agregar función</button>
        </div>
    </div>
</div>
@include('admin.events.partials.modal_create_edit_calendar')
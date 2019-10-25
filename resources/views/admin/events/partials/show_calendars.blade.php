<div id="list_calendars" class="mt-2">
    <div class="card">
        <div class="card-header">
            <h4>Calendario de Funciones</h4> 
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Fecha Inicio</th>
                        <th>Hora Inicio</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody id="list_calendar">
                    @forelse($event->calendars as $calendar)
                        <tr class="calendars" data-event-id="{{ $event->id }}" data-calendar-id="{{ $calendar->id }}">
                            <td class="start_date">{{ $calendar->start_date }}</td>
                            <td>{{ $calendar->start_time }} hs</td>
                            <td>{{ $calendar->observations }}</td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="3"><strong>Aún no se han definido funciones</strong></td>
                    </tr>
                    @endforelse
                </tbody>   
            </table>
        </div>
    </div>
</div>
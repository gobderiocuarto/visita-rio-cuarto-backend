<div class="modal fade" id="modal_create_edit_calendar" tabindex="-1" role="dialog" aria-labelledby="add_edit_place_molda_label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form id="form_add_edit_calendar" method="POST" action='{{ url("/admin/events/$event->id/calendars") }}'>
            @csrf
            <div class="modal-header">
              <h5 id="title_add_edit_calendar" class="modal-title" ></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-6">
                        <label for="start_date" class="col-form-label">Fecha de Inicio (*)</label>
                        <input class="form-control" type="date" id="start_date" name="start_date" required>
                    </div>
                    <div class="col-6">
                        <label for="start_time" class="col-form-label">Hora de Inicio (*)</label>
                        <input class="form-control" type="time" id="start_time" name="start_time" required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-6">
                        <label for="end_date" class="col-form-label">Fecha de finalización</label>
                        <input class="form-control" type="date" id="end_date" name="end_date" min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-6">
                        <label for="end_time" class="col-form-label">Hora de Finalización</label>
                        <input class="form-control" type="time" id="end_time" name="end_time">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <label for="observations" class="col-form-label">Observaciones</label>
                        <input class="form-control" type="text" id="observations" name="observations">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn_save" type="submit" class="btn btn-success">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>            
            </div>
            <input type="hidden" id="event_id" name="event_id" value="{{ $event->id }}">
            <input type="hidden" id="calendar_id" name="calendar_id" value="0">
        </form>
   </div> <!-- modal-content -->
   </div> <!-- modal-dialog -->
</div><!-- modal -->
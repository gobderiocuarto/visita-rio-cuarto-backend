<div class="modal fade" id="modal_load_place" tabindex="-1" role="dialog" aria-labelledby="load_place_modal_label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form id="form_load_place" action=''>
            @csrf
            <div class="modal-header">
              <h5 id="title_load_place" class="modal-title">Asignar ubicación a un evento</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-12">
                        <label for="load_place_name" class="col-form-label">Ubicaciones</label>
                        <input class="form-control" type="text" id="load_place_name" name="load_place_name" required>
                        <div id="list_places"></div>
                        <input type="hidden" name="load_place_id" id="load_place_id">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="load_place_btn_save" type="submit" class="btn btn-success">Asignar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>            
            </div>
            <input type="hidden" id="event_id" name="event_id" value="{{ $event->id }}">
        </form>
   </div> <!-- modal-content -->
   </div> <!-- modal-dialog -->
</div><!-- modal -->
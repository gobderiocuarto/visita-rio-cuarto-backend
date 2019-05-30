<div class="modal fade" id="create_custom_address" tabindex="-1" role="dialog" aria-labelledby="customAddressModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="form_create_custom_address" method="POST">
        <div class="modal-header">
          <h5 id="customAddressModalLabel" class="modal-title" >Crear opción personalizada</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="address_custom_name">Nombre <span class="text-danger">(*)</span></label>
                <input type="text" name="address_custom_name" id="address_custom_name" value="" class="form-control" placeholder="Ingrese un nombre" title="Ingrese un nombre" required>
                <div id="dni_alert"></div>
            </div>
        </div>
        <div class="modal-footer">
          <button id="button_cancel" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button id="btn_person_form" name="button_create" value="" type="submit" class="btn btn-success" >Aceptar</button>
        </div>
      </form>
    </div>
  </div>
</div>
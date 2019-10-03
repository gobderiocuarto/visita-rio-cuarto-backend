<div class="card">
    <div class="card-header">
        <h3><a name="space">Lugar del evento</h3>
    </div>
    <div class="card-body mt-2">
        <hr />
        <div class="form-group row">
            <label for="space" class="col-md-3 col-form-label text-md-right">Ubicación del Evento</label>
            <div class="col-md-8">
                <input name="place_name" id="place_name" type="text" class="form-control" value="{{ $actual_place }}" placeholder="Aún no se ha seleccionado ubicación"  readonly>
                <input type="hidden" name="place_id" id="place_id" value="{{ $actual_place_id }}">
            </div>
        </div>
        <div class="form-group row">
            <label for="file" class="col-md-3 col-form-label text-md-right">Asignar nueva ubicación</label>
            <div class="col-md-8">
                <button id="load_place" class="btn btn btn-outline-primary">Buscar...</button>
                <small class="form-text text-muted mt-2">Algun texto aca</small>
            </div>
        </div>
    </div>
</div>
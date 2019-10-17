<div class="card mt-3">
    <div class="card-body">
        <div class="form-group row">
		    <label for="space" class="col-md-12 col-form-label">
		    <h5>Relación con Evento Marco</h5>
		    </label>
		    <div class="offset-1 col-md-10">
		        <input name="place_name" id="place_name" type="text" class="form-control" value="{{ (isset($frame_event)) ? $frame_event->title : '' }}" placeholder="No se ha relacionado a Evento Marco"  readonly>
		    </div>
		</div>
    </div>
</div>

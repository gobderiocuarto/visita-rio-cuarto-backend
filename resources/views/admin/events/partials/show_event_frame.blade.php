<div class="card mt-3">
    <div class="card-body">
		<h5>Período que abarca el evento</h5>
		</br>
		<div class="form-group row">
		    <label for="start_date" class="col-md-3 col-form-label text-md-right">Fecha de Inicio</label>
		    <div class="col-md-4">
		        <input class="form-control" type="date" id="start_date" name="start_date" required @if ($calendar) value="{{ $calendar->start_date }}" @endif readonly disabled>
		    </div>
		</div>
		<input type="hidden" name="start_time" value="00:00:00">
		<div class="form-group row">
		    <label for="end_date" class="col-md-3 col-form-label text-md-right">Fecha de finalización</label>
		    <div class="col-md-4">
		        <input class="form-control" type="date" id="end_date" name="end_date" @if ($calendar) value="{{ $calendar->end_date }}" @endif readonly disabled>
		    </div>
		</div>
	</div>
</div>
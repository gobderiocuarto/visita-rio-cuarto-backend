<h5>Período que abarca el evento</h5>
</br>
<div class="form-group row">
    <label for="start_date" class="col-md-3 col-form-label text-md-right">Fecha de Inicio (*)</label>
    <div class="col-md-4">
        <input class="form-control" type="date" id="frame_start_date" name="start_date" @if ($calendar) value="{{ $calendar->start_date }}" @endif required>
    </div>
</div>
<div class="form-group row">
    <label for="end_date" class="col-md-3 col-form-label text-md-right">Fecha de finalización</label>
    <div class="col-md-4">
        <input class="form-control" type="date" id="frame_end_date" name="end_date" @if ($calendar) value="{{ $calendar->end_date }}" min="{{ $calendar->start_date }}" @else min="{{ date('Y-m-d') }}" @endif required>
    </div>
</div>
<input type="hidden" name="start_time" value="00:00:00">
<input type="hidden" name="end_time" value="23:59:59">
<input type="hidden" name="state" value="1">
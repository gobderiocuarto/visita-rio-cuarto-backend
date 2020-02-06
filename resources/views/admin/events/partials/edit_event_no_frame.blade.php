@if (Gate::allows('event.publish', $event))
<div class="form-group row">
    <label for="state" class="col-md-3 col-form-label text-md-right">Estado</label>
    <div class="col-md-4">
        <select id="state" name="state" class="form-control form-control-xl">
            <option value="0" @if ($event->state == 0 ) selected="selected" @endif>Borrador</option>
            <option value="1" @if ($event->state == 1 ) selected="selected" @endif>Publicado</option>
        </select>
    </div>
</div>
<hr />
@else
<input type="hidden" id="state" name="state" value="{{ $event->state }}">
@endif
<h4>Relación con Evento Marco</h4>
</br>
<div class="form-group row">
    <label for="rel_frame" class="col-md-3 col-form-label text-md-right">Relación / Asignado a:</label>
    <div class="col-md-8">
        <select name="rel_frame" class="form-control form-control-xl">
            <option value="">No relacionado a 'Evento Marco'</option>
            <optgroup label="Asignado a un 'Evento Marco': ">
            @forelse ($frame_events as $frame)
            <option value="{{ $frame->id }}" @if ($frame->id == $event->event_id) selected="selected" @endif>
            {{ $frame->title }}
            </option>
            @empty
            <option value="" disabled="disabled">
            No hay eventos marco habilitados
            </option>
            @endforelse
            </optgroup>
        </select>
    </div>
</div>
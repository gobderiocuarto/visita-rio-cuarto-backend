<div class="form-group row">
    <label for="group_id" class="col-md-3 col-form-label text-md-right">Creador del evento</label>
    @if ($event->group)
    <div class="col-md-8">
        <input name="group_id" id="group_id" type="text" class="form-control" value="{{ $event->group->name }}" readonly>
    </div>
    @else
    <div class="col-md-8">
        <input name="group_id" id="group_id" type="text" class="form-control" value="No se ha asignado grupo" readonly>
        <small class="form-text text-muted mt-2 text-danger"><p class="text-danger">
        <i class="fas fa-exclamation-circle"></i> Actualice el Evento para asignar</p>
        </small>
    </div>
    @endif
</div>
<div class="form-group row">
    <label for="title" class="col-md-3 col-form-label text-md-right">Título (*)</label>
    <div class="col-md-8">
        <input name="title" id="title" type="text" class="form-control" value="{{ $event->title }}" required minlength=3>
    </div>
    <input name="slug" id="slug" type="hidden" class="form-control" value="{{ $event->slug }}" readonly>
</div>
<div class="form-group row">
    <label for="organizer" class="col-md-3 col-form-label text-md-right">Organizador</label>
    <div class="col-md-8">
        <input name="organizer" id="organizer" type="text" class="form-control" value="{{ $event->organizer }}">
    </div>
</div> 
<hr />
<h5>Categorías</h5>
<div class="form-group row">
    <div class="offset-3 col-md-8">
        <select multiple="multiple" size="5" id="select_mult" name="select_mult[]" class="form-control form-control-xl" required>
            @foreach ($tags_group_events as $category)
            <option value="{{ $category['name'] }}"  @if (in_array( $category['name'], $tags_in_event))
selected="selected" @endif>
                {{ $category['name'] }}
            </option>
            @endforeach
        </select>
    </div>
</div>
<hr />
<div class="form-group row">
    <label for="summary" class="col-md-3 col-form-label text-md-right">Información principal (*)</label>
    <div class="col-md-8">
        <textarea class="form-control" name="summary" rows="5" required>{{ $event->summary }}</textarea>
        <small class="form-text text-muted mt-2">Longitud ideal: 160 caracteres aprox.</small>
    </div>
</div>
<div class="form-group row">
    <label for="description" class="col-md-3 col-form-label text-md-right">Información adicional</label>
    <div class="col-md-8">
        <textarea class="form-control" name="description" rows="10">{{ $event->description }}</textarea>
    </div>
</div>
<hr/>
<div class="form-group row">
    <label for="tags_no_events" class="col-md-3 col-form-label text-md-right">Etiquetas / Tags</label>
    <div class="col-md-8">
        <input name="tags_no_events" id="tags_no_events" type="text" class="form-control" data-role="tagsinput" value="{{ $tags_no_events }}"  placeholder="Etiquetas">
        <small class="form-text text-muted mt-2">Separar mediante comas</small>
    </div>
</div>
<hr />
<div class="form-group row">
    <label for="group_id" class="col-md-3 col-form-label text-md-right">Propietario</label>
    @if ($event->group)
    <div class="col-md-8">
        <input name="group_id" id="group_id" type="text" class="form-control" value="{{ $event->group->name }}" readonly="readonly" disabled="disabled">
    </div>
    @else
    <div class="col-md-8">
        <input name="group_id" id="group_id" type="text" class="form-control" value="No se ha asignado grupo" readonly="readonly" disabled="disabled">
    </div>
    @endif
</div>
<div class="form-group row">
    <label for="title" class="col-md-3 col-form-label text-md-right">Título (*)</label>
    <div class="col-md-8">
        <input name="title" id="title" type="text" class="form-control" value="{{ $event->title }}" readonly="readonly" disabled="disabled">
    </div>
</div>
<div class="form-group row">
    <label for="organizer" class="col-md-3 col-form-label text-md-right">Organizador</label>
    <div class="col-md-8">
        <input name="organizer" id="organizer" type="text" class="form-control" value="{{ $event->organizer }}" readonly="readonly" disabled="disabled">
    </div>
</div> 
<div class="form-group row">
    <label for="tags_events" class="col-md-3 col-form-label text-md-right">Categorías</label>
    <div class="col-md-8">
        <input name="tags_category_event" id="tags_category_event" type="text" class="form-control" data-role="tagsinput" value="{{ $tags_category_event }}"  placeholder="Categorías" readonly="readonly" disabled="disabled">
    </div>
</div>
<hr />
<div class="form-group row">
    <label for="summary" class="col-md-3 col-form-label text-md-right">Información principal (*)</label>
    <div class="col-md-8">
        <textarea class="form-control" name="summary" rows="5" readonly="readonly" disabled="disabled">{{ $event->summary }}</textarea>
    </div>
</div>
<div class="form-group row">
    <label for="description" class="col-md-3 col-form-label text-md-right">Información adicional</label>
    <div class="col-md-8">
        <textarea class="form-control" name="description" rows="10" readonly="readonly" disabled="disabled">
        {{ $event->description }}
        </textarea>
    </div>
</div>
<hr/>
<div class="form-group row">
    <label for="tags_no_category_event" class="col-md-3 col-form-label text-md-right">Etiquetas / Tags</label>
    <div class="col-md-8">
        <input name="tags_no_category_event" id="tags_no_category_event" type="text" class="form-control" data-role="tagsinput" value="{{ $tags_no_category_event }}" placeholder="No etiquetado" readonly="readonly" disabled="disabled">
    </div>
</div>
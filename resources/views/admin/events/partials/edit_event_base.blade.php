<div class="form-group row">
    <label for="group_id" class="col-md-3 col-form-label text-md-right">Propietario del evento</label>
    <div class="col-md-8">
        <select name="group_id" class="form-control form-control-xl">
        @if (Gate::allows('event.editGroup'))
            @forelse ($list_groups as $each_group)
            <option value="{{ $each_group->id }}" @if ( $event->group->id == $each_group->id ) selected @endif>
                {{ $each_group->name }}
            </option>
            @empty
            <option value="" disabled="disabled">
                No hay Grupos habilitados
            </option>
            @endforelse
        @else
            <option value="{{ $event->group->id }}" readonly aria-readonly="true" >
                {{ $event->group->name }}
            </option>
        @endif
        </select>
    </div>
</div>

<div class="form-group row">
    <label for="title" class="col-md-3 col-form-label text-md-right">Título (*)</label>
    <div class="col-md-8">
        <input name="title" id="title" type="text" class="form-control" value="{{ $event->title }}" required minlength=3>
    </div>
</div>
<div class="form-group row">
    <label for="slug" class="col-md-3 col-form-label text-md-right">Slug</label>
    <div class="col-md-8">
        <input name="slug" id="slug" type="text" class="form-control" value="{{ $event->slug }}" required readonly>
    </div>
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
            @foreach ($tags_category as $category)
            <option value="{{ $category['name'] }}"  @if (in_array( $category['name'], $tags_category_event))
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
<hr/>
<div class="form-group row">
    <label for="description" class="col-md-3 col-form-label text-md-right">Información adicional</label>
    <div class="col-md-8">
        <textarea class="form-control" id="description" name="description" rows="10">{{ $event->description }}</textarea>
    </div>
</div>
<hr/>
<div class="form-group row">
    <label for="tags_no_category_event" class="col-md-3 col-form-label text-md-right">Etiquetas / Tags</label>
    <div class="col-md-8">
        <input name="tags_no_category_event" id="tags_no_category_event" type="text" class="form-control" data-role="tagsinput" value="{{ $tags_no_category_event }}"  placeholder="Etiquetas">
        <small class="form-text text-muted mt-2">Separar mediante comas</small>
    </div>
</div>
<hr />
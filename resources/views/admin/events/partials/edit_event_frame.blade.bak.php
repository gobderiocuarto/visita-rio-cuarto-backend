<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="nav-item">
        <a href="#event" class="nav-link active" data-toggle="tab" aria-controls="event" role="tab" title="Datos del evento">
            Datos del evento
        </a>
    </li>
    <li role="presentation" class="nav-item">
        <a href="#image" class="nav-link" data-toggle="tab" aria-controls="image" role="tab" title="Imagen del evento">
            Imagen del evento 
        </a>
    </li>
</ul><!-- nav-tabs -->
<div class="tab-content p-3 mt-2">
    <div class="tab-pane active" role="tabpanel" id="event">
        <form id="form_event" method="POST" action='{{ url("/admin/events/$event->id#event")}}' enctype="multipart/form-data">
            {{ method_field('PATCH') }}
            @csrf
        <div class="card">
            <div class="card-header">
                <h3><a name="event">Datos de evento</h3>
            </div>
            <div class="card-body mt-2">
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
                <h5>Período que abarca el evento</h5>
                </br>
                <div class="form-group row">
                    <label for="start_date" class="col-md-3 col-form-label text-md-right">Fecha de Inicio (*)</label>
                    <div class="col-md-4">
                        <input class="form-control" type="date" id="start_date" name="start_date" required @if ($calendar) value="{{ $calendar->start_date }}" @endif>
                    </div>
                </div>
                <input type="hidden" name="start_time" value="00:00:00">
                <div class="form-group row">
                    <label for="end_date" class="col-md-3 col-form-label text-md-right">Fecha de finalización</label>
                    <div class="col-md-4">
                        <input class="form-control" type="date" id="end_date" name="end_date" @if ($calendar) value="{{ $calendar->end_date }}" @endif required>
                    </div>
                </div>
                <input type="hidden" name="end_time" value="23:59:59">
                <input type="hidden" name="state" value="1">
            </div>
        </div>
        <div class="card">
            <div class="card-footer ">
                <div class="row pt-2 pb-2">
                    <div class="col-md-4 offset-md-3">
                        <button type="submit" class="btn btn-success">Actualizar Evento</button>
                    </div>
                    <div class="col-md-4">
                        <button type="reset" class="btn btn-outline-dark ">Restaurar datos</button>
                    </div>
                </div>
            </div>
        </div>
        </form> 
    </div>
    <div class="tab-pane" role="tabpanel" id="image">
        
    </div>
</div><!-- tab-content -->


 <form id="form_event" method="POST" action='{{ url("/admin/events/$event->id")}}' enctype="multipart/form-data">
    {{ method_field('PATCH') }}
    @csrf   
    <div class="card">
        <div class="card-header">
            <h3>Datos de evento</h3>
        </div>
        <div class="card-body mt-2">
            <div class="form-group row">
                <label for="title" class="col-md-3 col-form-label text-md-right">Título (*)</label>
                <div class="col-md-8">
                    <input name="title" id="title" type="text" class="form-control" value="{{ $event->title }}" required minlength=3>
                </div>
            </div>
            <div class="form-group row">
                <label for="slug" class="col-md-3 col-form-label text-md-right">Slug</label>
                <div class="col-md-8">
                    <input name="slug" id="slug" type="text" class="form-control" value="{{ $event->slug }}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="organizer" class="col-md-3 col-form-label text-md-right">Organizador (*)</label>
                <div class="col-md-8">
                    <input name="organizer" id="organizer" type="text" class="form-control" value="{{ $event->organizer }}" required >
                </div>
            </div> 
            <div class="form-group row">
                <label for="summary" class="col-md-3 col-form-label text-md-right">Información principal (*)</label>
                <div class="col-md-8">
                    <textarea class="form-control" name="summary" rows="5" required>{{ $event->summary }}</textarea>
                    <small class="form-text text-muted mt-2">Longitud ideal: 160 caracteres aprox.</small>
                </div>
            </div>
            <hr />
            <div class="form-group row">
                <label for="description" class="col-md-3 col-form-label text-md-right"> Información adicional </label>
                <div class="col-md-8">
                    <textarea class="form-control" name="description" rows="10">{{ $event->description }}</textarea>
                </div>
            </div>
            <hr />
            <div class="form-group row">
                <label for="tags_events" class="col-md-3 col-form-label text-md-right">Categorías asociadas</label>
                <div class="col-md-8">
                    <input name="tags_events" id="tags_events" type="text" class="form-control" data-role="tagsinput" value="{{ $tags_events }}"  placeholder="Etiquetas">
                    <small class="form-text text-muted mt-2">Separar mediante comas</small>
                </div>
            </div>
            <hr />
            <div class="form-group row">
                <label for="tags_no_events" class="col-md-3 col-form-label text-md-right">Etiquetas / Tags</label>
                <div class="col-md-8">
                    <input name="tags_no_events" id="tags_no_events" type="text" class="form-control" data-role="tagsinput" value="{{ $tags_no_events }}"  placeholder="Etiquetas">
                    <small class="form-text text-muted mt-2">Separar mediante comas</small>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3>Lugar del evento</h3>
        </div>
        <div class="card-body mt-2">
            <hr />
            <div class="form-group row">
                <label for="place" class="col-md-3 col-form-label text-md-right">Ubicación del Evento</label>
                <div class="col-md-8">
                    @if ($place)
                    <input name="place" id="place" type="text" class="form-control" value="{{ $place }}" readonly>
                    @else
                    <input name="place" id="place" type="text" class="form-control" value="" placeholder="Aún no se ha seleccionado ubicación" readonly>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="place_id" class="col-md-3 col-form-label text-md-right">Asignar nueva ubicación</label>
                <div class="col-md-8">
                    <select id="place_id" name="place_id" class="form-control form-control-xl selectpicker" data-default-value="" data-live-search="true" data-size="8" required>
                        <option value="">Selecciona...</option>
                        @foreach($organizations as $organization)
                            @if (!empty($organization->places))
                            @foreach($organization->places as $place)
                            <option value="{{ $place->pivot->id }}">
                                ({{ $place->pivot->id }})
                                {{ $organization->name }} - {{ $place->name }} - 
                                {{ $place->address->street->name }} {{ $place->address->number }}
                            </option>
                            @endforeach
                            @endif
                            @if (!empty($organization->addresses))
                            @foreach($organization->addresses as $address)
                            <option value="{{ $address->pivot->id }}">
                                ({{ $address->pivot->id }})
                                {{ $organization->name }} - {{ $address->street->name }} {{ $address->number }}
                            </option>
                            @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
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
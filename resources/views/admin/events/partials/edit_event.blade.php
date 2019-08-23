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
            <hr/>
            <div class="form-group row">
                <label for="tags_no_events" class="col-md-3 col-form-label text-md-right">Etiquetas / Tags</label>
                <div class="col-md-8">
                    <input name="tags_no_events" id="tags_no_events" type="text" class="form-control" data-role="tagsinput" value="{{ $tags_no_events }}"  placeholder="Etiquetas">
                    <small class="form-text text-muted mt-2">Separar mediante comas</small>
                </div>
            </div>
        </div>
    </div>
    <hr/>
    <div class="card">
        <div class="card-header">
            <h3>Imagen del evento</h3>
        </div>
        <div class="card-body mt-2">
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">Imagen principal: </label>
                @if($event->file)
                <div class="col-md-3 parent position-relative">
                    <div>
                        <a href="{{ Storage::url("events/{$event->id}/{$event->file->file_path}") }}">
                            <img class="img-fluid" src="{{ Storage::url("events/{$event->id}/thumbs/{$event->file->file_path}") }}" alt="{{$event->file->file_alt}}">
                        </a>
                    </div>
                    <a id="delete_image" href="#">
                        <div class="text-center" style="with: 100%; background-color: Gainsboro; padding: .5em">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                    </a>
                </div>
                @else
                <div class="col-md-8">
                    <span class="form-text font-italic mt-2">Aún no se ha cargado ninguna imagen</span>
                </div>
                @endif
            </div>
            <hr/>
            <div class="form-group row">
                <label for="file" class="col-md-3 col-form-label text-md-right">Cargar nueva imagen</label>
                <div class="col-md-8">
                    <input type="file" id="file" name="file" class="" value="{{ old('file') }}">
                    <small class="form-text text-muted mt-2">Tamaño máximo ideal de imagen: 1000 x 1000 px</small>
                </div>
            </div>
            <div class="form-group row">
                <label for="file_alt" class="col-md-3 col-form-label text-md-right">Texto alternativo</label>
                <div class="col-md-8">
                    <input type="text" id="file_alt" name="file_alt" class="form-control" @if($event->file) value="{{$event->file->file_alt}}" @else value="" @endif>
                    <small class="form-text text-muted mt-2">Texto asociado a la imagen. Asegura que no se pierda información sea porque las imágenes no están disponibles, el lector ha desactivado las imágenes en su navegador o porqué está utilizando un lector de pantalla debido a que padece una deficiencia visual. </small>
                </div>
            </div>
            <hr/>
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
                    <select id="place_id" name="place_id" class="form-control form-control-xl selectpicker" data-default-value="" data-live-search="true" data-size="8">
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
<form id="form_event" method="POST" action='{{ url("/admin/events/$event->id#event")}}' enctype="multipart/form-data">
{{ method_field('PATCH') }}
@csrf   
<div class="card">
    <div class="card-header">
        <h3><a name="event">Datos de evento</h3>
    </div>
    <div class="card-body mt-2">
        <div class="form-group row">
            <label for="group_id" class="col-md-3 col-form-label text-md-right">Creador del Evento</label>
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
        </div>
        <input name="slug" id="slug" type="hidden" value="{{ $event->slug }}" readonly>
        <div class="form-group row">
            <label for="organizer" class="col-md-3 col-form-label text-md-right">Organizador</label>
            <div class="col-md-8">
                <input name="organizer" id="organizer" type="text" class="form-control" value="{{ $event->organizer }}">
            </div>
        </div>
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
        <hr />
        <div class="form-group row">
            <label for="tags_no_events" class="col-md-3 col-form-label text-md-right">Etiquetas / Tags</label>
            <div class="col-md-8">
                <input name="tags_no_events" id="tags_no_events" type="text" class="form-control" data-role="tagsinput" value="{{ $tags_no_events }}"  placeholder="Etiquetas">
                <small class="form-text text-muted mt-2">Separar mediante comas</small>
            </div>
        </div>
        <hr />
        <h5>Categorías</h5>
        <div class="form-group row">
            <div class="offset-md-3 col-md-8">
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
        <hr/>
        
        <div class="form-group row">
            <label for="state" class="col-md-3 col-form-label text-md-right">Estado</label>
            <div class="col-md-4">
                <select id="state" name="state" class="form-control form-control-xl">
                    <option value="0" @if ($event->state == 0 ) selected="selected" @endif>Pendiente</option>
                    <option value="1" @if ($event->state == 1 ) selected="selected" @endif>Activo</option>
                    <option value="2" @if ($event->state == 2 ) selected="selected" @endif>Inactivo</option>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3><a name="space">Lugar del evento</h3>
    </div>
    <div class="card-body mt-2">
        <hr />
        <div class="form-group row">
            <label for="space" class="col-md-3 col-form-label text-md-right">Ubicación del Evento</label>
            <div class="col-md-8">
                @if ($actual_place)
                <input name="place" id="place" type="text" class="form-control" value="{{ $actual_place }}" readonly>
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
                    @foreach($list_orgs as $organization)
                        @foreach($organization->places as $place)
                        <option value="{{ $place->id }}">
                            {{ $organization->name }} - 
                            @if ($place->placeable_type == 'App\Space')
                            {{ $place->placeable->address->street->name }} {{ $place->placeable->address->number }}, {{ $place->placeable->name }}
                            @elseif ($place->placeable_type == 'App\Address')
                            {{ $place->placeable->street->name }} {{ $place->placeable->number }}
                            @endif
                        </option>
                        @endforeach
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
<hr/>
<div class="card">
    <div class="card-header">
        <h3><a name="image"></a>Imagen del evento</h3>
    </div>
    <div class="card-body mt-2">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">Imagen principal: </label>
            @if($event->file)
            <div class="col-md-3 parent position-relative">
                <div>
                    <a target="_blank" href="{{ Storage::url("events/{$event->id}/{$event->file->file_path}") }}">
                        <img class="img-fluid" src="{{ Storage::url("events/{$event->id}/thumbs/{$event->file->file_path}") }}" alt="{{$event->file->file_alt}}">
                    </a>
                </div>
                <!-- <a id="delete_image" href="#">
                    <div class="text-center" style="with: 100%; background-color: Gainsboro; padding: .5em">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                </a> -->
                <div class="text-center" style="with: 100%; background-color: Gainsboro; padding: .5em">
                    <form id="form_delete_image_event" action='{{ url("/admin/events/$event->id/images/delete#image")}}' method="POST">
                        {{ method_field('DELETE') }}
                        @csrf
                        <button id="delete_image" type="submit" class="btn btn-sm btn-primary"><i class="fas fa-trash-alt"></i> Eliminar</button>
                    </form>
                </div>
            </div>
            @else
            <div class="col-md-8">
                <span class="form-text font-italic mt-2">Aún no se ha cargado ninguna imagen</span>
            </div>
            @endif
        </div>
        <hr/>
        <form id="form_load_image_event" method="POST" action='{{ url("/admin/events/$event->id/images#image")}}' enctype="multipart/form-data">
        @csrf
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
        <div class="row pt-2 pb-2">
            <div class="col-md-4 offset-md-3">
                <button type="submit" class="btn btn-success">Cargar imagen</button>
            </div>
            <div class="col-md-4">
                <button type="reset" class="btn btn-outline-dark ">Cancelar</button>
            </div>
        </div>
        </form>
    </div>
</div>
<hr />
<div id="list_calendars" class="mt-2">
    @include('admin.events.partials.list_calendars')
</div>  
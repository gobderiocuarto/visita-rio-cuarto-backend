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
                    <a target="_blank" href="{{ Storage::url("events/large/{$event->file->file_path}") }}">
                        <img class="img-fluid" src="{{ Storage::url("events/small/{$event->file->file_path}") }}" alt="{{$event->file->file_alt}}">
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
                <button type="submit" class="btn btn-success">Aplicar cambios</button>
            </div>
            <div class="col-md-4">
                <button type="reset" class="btn btn-outline-dark ">Cancelar</button>
            </div>
        </div>
        </form>
    </div>
</div>
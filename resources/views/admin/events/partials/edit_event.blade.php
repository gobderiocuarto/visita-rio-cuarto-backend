<form id="form_event" method="POST" action='{{ url("/admin/events/$event->id")}}' enctype="multipart/form-data">
    {{ method_field('PATCH') }}
    @csrf
    <div class="form-group row">
        <label for="category_id" class="col-md-3 col-form-label text-md-right">Categoría (*)</label>
        <div class="col-md-8">
            <select class="form-control form-control-xl" id="category_id" name="category_id"  data-size="8" required>
                <option value="">Selecciona...</option>
                <option style="font-weight: bold;" value="1" selected>Cultura y Espectáculos</option>
                    <option style="text-indent: 10px;" value="5">&nbsp;Teatro</option>
                    <option style="text-indent: 10px;" value="6">&nbsp;Cine</option>
                <option style="font-weight: bold;" value="1">Deporte y Recreación</option>
                <option style="font-weight: bold;" value="2">Congresos y Jornadas</option>
                <option style="font-weight: bold;" value="3">Infantiles</option>
                <option style="font-weight: bold;" value="4">Actividades Gratuitas</option>
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
            <input name="slug" id="slug" type="text" class="form-control" value="{{ $event->slug }}" readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="summary" class="col-md-3 col-form-label text-md-right">Resumen (*)</label>
        <div class="col-md-8">
            <textarea class="form-control" name="summary" rows="5" maxlength="250" required>{{ $event->summary }}</textarea>
            <small class="form-text text-muted mt-2">250 caracteres máximo</small>
        </div>
    </div>
    <hr />
    <div class="form-group row">
        <label for="description" class="col-md-3 col-form-label text-md-right">Breve descripción </label>
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
        <label for="tags_no_events" class="col-md-3 col-form-label text-md-right">Etiquetas asociadas</label>
        <div class="col-md-8">
            <input name="tags_no_events" id="tags_no_events" type="text" class="form-control" data-role="tagsinput" value="{{ $tags_no_events }}"  placeholder="Etiquetas">
            <small class="form-text text-muted mt-2">Separar mediante comas</small>
        </div>
    </div>
    <hr />
    <div class="form-group row mt-5">
        <div class="col-md-4 offset-md-3">
            <button type="submit" class="btn btn-success">Actualizar Evento</button>
        </div>
        <div class="col-md-4">
            <button type="reset" class="btn btn-outline-dark ">Restaurar datos</button>
        </div>
    </div>
</form>
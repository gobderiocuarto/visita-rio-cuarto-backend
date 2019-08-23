
<form id="form_organization_place" method="POST" action='{{ url("/admin/events/organization-place/$event->id")}}' enctype="multipart/form-data">
    @csrf
    <div class="form-group row">
        <label for="organizer" class="col-md-3 col-form-label text-md-right">Organizador (*)</label>
        <div class="col-md-8">
            <input name="organizer" id="organizer" type="text" class="form-control" value="{{ $event->organizer }}" required >
        </div>
    </div>    
    <div class="form-group row">
        <label for="place" class="col-md-3 col-form-label text-md-right">Asociar a espacio:</label>
        <div class="col-md-8">
            <select id="place" name="place" class="form-control form-control-xl selectpicker" data-live-search="true"  data-default-value="" data-size="8">
                <option value="">Seleccione...</option>
                @foreach($places as $place)
                <option value="{{ $place->id }}">
                    {{ $place->name }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <hr />

    <div class="form-group  mt-5">
        <div class="alert alert-secondary text-right">
            <button id="btn_person_form" name="btn_save" value="" type="button" class="btn btn-success" >Actualizar</button>
        </div>
    </div>
</form>
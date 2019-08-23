<div class="alert alert-secondary text-right mb-3" >
    <button id="places_btn_add" class="pull-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> Prueba modal</button>
</div>
<hr>

<form id="form_organization_place" method="POST" action='{{ url("/admin/events/organization-place/$event->id")}}' enctype="multipart/form-data">
    @csrf
    <div class="form-group row">
        <label for="organizer" class="col-md-3 col-form-label text-md-right">Organizador (*)</label>
        <div class="col-md-8">
            <input name="organizer" id="organizer" type="text" class="form-control" value="{{ $event->organizer }}" required >
        </div>
    </div>
    
    <div class="form-group row">
        <label for="organizador" class="col-md-3 col-form-label text-md-right">Organizador (*)</label>
        <div class="col-md-8">
            <input name="organizador" id="organizador" type="text" class="form-control" value="" required minlength=3>
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

    <div class="form-group row">
        <label for="organization" class="col-md-3 col-form-label text-md-right">(Organizaciones)</label>
        <div class="col-md-8">
            <select id="organization" name="organization" class="form-control form-control-xl selectpicker" data-default-value="" data-live-search="true" data-size="8" required>
                <option value="">Selecciona...</option>
                @foreach($organizations as $organization)
                    @foreach($organization->addresses as $address)
                <option value="{{ $address->id }}">
                    {{ $organization->name }} - {{ $address->street->name }} {{ $address->number }}
                    @if ($address->pivot->address_type_id != 1 ) ({{ $address->pivot->address_type_name }})
                    @endif 
                </option>
                    @endforeach
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button id="btn_person_form" name="btn_save" value="" type="button" class="btn btn-success" >Actualizar</button>
    </div>
</form>
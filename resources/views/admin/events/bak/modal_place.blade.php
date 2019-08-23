<div class="modal fade" id="add_edit_place" tabindex="-1" role="dialog" aria-labelledby="customAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form_place" method="POST" action='{{ url("/admin/events/$event->id/place") }}'>
                <div class="modal-header">
                    <h4 id="title_add_edit_place">Nueva ubicación</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    @csrf


                    <div class="form-group row">
                        <label for="organization" class="col-md-12 col-form-label">Organizador(*)</label>
                        <div class="col-md-12">
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



                    <div class="form-group row">
                        <label for="place" class="col-md-12 col-form-label">Asociar a espacio existente:</label>
                        <div class="col-md-12">
                            <select id="place" name="place" class="form-control form-control-xl selectpicker" data-live-search="true"  data-default-value="" data-size="8">
                                <option value="">No asociar</option>
                                @foreach($places as $place)
                                <option value="{{ $place->id }}">
                                    {{ $place->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="street_id" class="col-md-12 col-form-label">Calle(*)</label>
                        <div class="col-md-12">
                            <select id="street_id" name="street_id" class="form-control form-control-xl selectpicker" data-default-value="" data-live-search="true" data-size="8" required>
                                <option value="">Selecciona...</option>
                                @foreach($streets as $street)
                                <option value="{{ $street->id }}">{{ $street->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="row">
                                <label for="number" class="col-md-12 col-form-label">Número (*)</label>
                                <div class="col-md-10">
                                    <input name="number" id="number" type="number" class="form-control col-md-12" value="{{ old('number') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label for="floor" class="col-md-12 col-form-label">Piso / Dpto / Oficina</label>
                                <div class="col-md-10">
                                    <input name="floor" id="floor" type="text" class="form-control" value="{{ old('depto') }}">
                                </div>
                            </div>
                        </div>                
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="row">
                                <label for="lat" class="col-md-12 col-form-label">Latitud</label>
                                <div class="col-md-12">
                                    <input name="lat" id="lat" type="text" class="form-control" value="{{ old('lat') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label for="lng" class="col-md-12 col-form-label">Longitud</label>
                                <div class="col-md-12">
                                    <input name="lng" id="lng" type="text" class="form-control" value="{{ old('lng') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="zone_id" class="col-md-12 col-form-label">Zona</label>
                        <div class="col-md-12">
                            <select id="zone_id" name="zone_id" class="form-control form-control-xl" data-default-value="0" data-size="8">
                                <option value="0">Selecciona...</option>
                                @foreach($zones as $zone)
                                <option value="{{ $zone->id }}">
                                    {{ $zone->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="button_cancel" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button id="btn_person_form" name="btn_save" value="" type="submit" class="btn btn-success" >Guardar cambios</button>
                </div>
                <input type="hidden" id="event" name="event" value="{{ $event->id }}">
                <input type="hidden" id="rel_type" name="prev_rel_type" value="">
                <input type="hidden" id="rel_value" name="prev_rel_value" value="">
            </form>
        </div>
    </div>
</div>
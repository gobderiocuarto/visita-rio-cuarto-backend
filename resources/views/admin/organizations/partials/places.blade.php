<div id="list_places" class="mt-2" @if(Session::has('action')) style="display: none;" @endif>
    <div class="card">
        <div class="card-header">
            <h2>Ubicaciones</h2> 
        </div>
        <div class="card-body">
            <div class="alert alert-secondary text-right mb-3" >
                <button id="places_btn_add" class="pull-right btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> Crear ubicación</button>
            </div>
            <hr>       
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Tipo</th>
                        <th>Calle y número</th>
                        <th colspan="2">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                @if (count($organization->places) === 0 and count($organization->addresses) === 0)
                    <tr>
                        <td colspan="4"><strong>Aún no se han asociado direcciones</strong></td>
                    </tr>
                @else
                    @foreach($organization->places as $place)
                    <tr>
                        <td><strong>{{ $place->pivot->address_type_name }}</strong></td>
                        <td>{{ $place->address->street->name }} {{ $place->address->number }}, <strong>{{ $place->name }}</strong></strong></td>
                        <td width="10px">
                            <button type="button" class="btn btn-sm btn-success places_btn_edit" data-rel-type="place" data-rel-value="{{ $place->id }}">
                                Editar
                            </button>
                        </td>
                        <td width="10px">
                            <form id="form_delete_place_{{ $place->id }}" action='{{ url("/admin/organizations/$organization->id/places/$place->id") }}' method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger delete_location">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @foreach($organization->addresses as $address)
                    <tr>
                        <td><strong>{{ $address->pivot->address_type_name }}</strong></td>
                        <td>{{ $address->street->name }} {{ $address->number }}</td>
                        <td width="10px">
                            <button type="button" class="btn btn-sm btn-success places_btn_edit" data-rel-type="address" data-rel-value="{{ $address->id }}">
                                Editar
                            </button>
                        </td>
                        <td width="10px">
                            <form id="form_delete_address_{{ $address->id }}" action='{{ url("/admin/organizations/$organization->id/addresses/$address->id") }}' method="POST">
                                 @csrf
                                <button type="submit" class="btn btn-sm btn-danger delete_location">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                @endif
                </tbody>   
            </table>
        </div>
    </div>
</div>
<div id="add_edit_place" class="mt-2" @if(!Session::has('action')) style="display: none;" @endif>
    <form id="form_place" method="POST" action='{{ url("/admin/organizations/$organization->id/place") }}'>
        <div class="card-header">
            <h4 id="title_add_edit_place">Nueva ubicación</h4>
        </div>
        <div class="card-body">
            @csrf
            <div class="form-group row">
                <label for="address_type" class="col-md-12 col-form-label">Tipo de ubicación</label>
                <div class="col-md-5">
                    <select id="address_type" name="address_type" class="form-control form-control-xl" data-default-value="1" autofocus required>
                        @foreach($addresses_types as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                        <option value="-1">Personalizar...</option>
                    </select>
                    <input name="address_type_name" id="address_type_name" type="hidden" value="">
                </div>
            </div>
            <div class="form-group row">
                <label for="place" class="col-md-12 col-form-label">Asociar a ubicación existente:</label>
                <div class="col-md-8">
                    <select id="place" name="place" class="form-control form-control-xl selectpicker" data-live-search="true" data-default-value="">
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
                <div class="col-md-8">
                    <select id="street_id" name="street_id" class="form-control form-control-xl selectpicker" data-default-value="" data-live-search="true" data-size="10" required>
                        <option value="">Selecciona...</option>
                        @foreach($streets as $street)
                        <option value="{{ $street->id }}">{{ $street->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3">
                    <div class="row">
                        <label for="number" class="col-md-12 col-form-label">Número (*)</label>
                        <div class="col-md-10">
                            <input name="number" id="number" type="number" class="form-control col-md-12" value="{{ old('number') }}" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <label for="floor" class="col-md-12 col-form-label">Piso / Dpto / Oficina</label>
                        <div class="col-md-10">
                            <input name="floor" id="floor" type="text" class="form-control" value="{{ old('depto') }}">
                        </div>
                    </div>
                </div>                
            </div>
            <div class="form-group row">
                <div class="col-md-3">
                    <div class="row">
                        <label for="lat" class="col-md-12 col-form-label">Latitud</label>
                        <div class="col-md-10">
                            <input name="lat" id="lat" type="text" class="form-control" value="{{ old('lat') }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <label for="lng" class="col-md-12 col-form-label">Longitud</label>
                        <div class="col-md-10">
                            <input name="lng" id="lng" type="text" class="form-control" value="{{ old('lng') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="zone_id" class="col-md-12 col-form-label">Zona</label>
                <div class="col-md-5">
                    <select id="zone_id" name="zone_id" class="form-control form-control-xl" data-default-value="0">
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
        <div class="card-footer">
            <div class="form-group row mb-0">
                <div class="col-md-4 offset-md-3">
                    <button id="btn_save" type="submit" class="btn btn-success" disabled>Guardar cambios</button>
                </div>
                <div class="col-md-4">
                    <button id="places_btn_cancel" type="button" class="btn btn-outline-dark">Listar ubicaciones</button>
                </div>
            </div>
        </div>
        <input type="hidden" id="organization" name="organization" value="{{ $organization->id }}">
        <input type="hidden" id="rel_type" name="prev_rel_type" @if(Session::has('action')) value="{{ Session::get('action.type') }}" @else value="" @endif>
        <input type="hidden" id="rel_value" name="prev_rel_value" @if(Session::has('action')) value="{{ Session::get('action.value') }}" @else value="" @endif>
    </form>
</div>
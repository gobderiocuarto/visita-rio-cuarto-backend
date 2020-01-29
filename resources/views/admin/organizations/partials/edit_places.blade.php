<div id="add_edit_place" class="mt-2" @if(!Session::has('action')) style="display: none;" @endif>
    <form id="form_place" method="POST" action='{{ url("/admin/organizations/$organization->id/place") }}'>
        <div class="card-header">
            <h4 id="title_add_edit_place">Nueva ubicación</h4>
        </div>
        <div class="card-body">
            @csrf
            <div class="form-group row">
                <label for="container" class="col-md-12 col-form-label"><strong>¿La ubicación es un espacio contenedor?</strong> </label>
                <div class="col-md-8">
                    <select id="container" name="container" class="form-control form-control-xl">
                        <option value="">No definido como espacio contenedor</option>
                        <option value="is-container">Definido como espacio contenedor</option>
                    </select>
                </div>
            </div>
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
                <label for="place_id" class="col-md-12 col-form-label">Asociar a Espacio contenedor:</label>
                <div class="col-md-8">
                    <select id="place_id" name="place_id" class="form-control form-control-xl selectpicker" data-live-search="true"  data-default-value="" data-size="8">
                        <option value="">No asociar</option>
                        @foreach($containers as $container)
                        <option value="{{ $container->id }}">
                            {{ $container->organization->name }} - {{ $container->placeable->street->name }}
                            {{ $container->placeable->number }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="street_id" class="col-md-12 col-form-label">Calle</label>
                <div class="col-md-8">
                    <select id="street_id" name="street_id" class="form-control form-control-xl selectpicker" data-default-value="" data-live-search="true" data-size="8">
                        <option value="0" selected="selected">Sin Asignar</option>
                        @foreach($streets as $street)
                        <option value="{{ $street->id }}">{{ $street->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3">
                    <div class="row">
                        <label for="number" class="col-md-12 col-form-label">Número</label>
                        <div class="col-md-10">
                            <input name="number" id="number" type="number" class="form-control col-md-12" value="{{ old('number') }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <label for="apartament" class="col-md-12 col-form-label">Piso / Dpto / Oficina</label>
                        <div class="col-md-10">
                            <input name="apartament" id="apartament" type="text" class="form-control" value="{{ old('apartament') }}">
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
        <input type="hidden" id="organization_id" name="organization_id" value="{{ $organization->id }}">
        <input type="hidden" id="place" name="place" value="0">
        <input type="hidden" id="rel_type" name="prev_rel_type" @if(Session::has('action')) value="{{ Session::get('action.type') }}" @else value="" @endif>
        <input type="hidden" id="rel_value" name="prev_rel_value" @if(Session::has('action')) value="{{ Session::get('action.value') }}" @else value="" @endif>
    </form>
</div>
<div id="list_places" class="mt-2">
    <div class="card">
        <div class="card-header">
            <h2>Direcciones </h2> 
            <a href="{{ route('organizations.create') }}" class="pull-right btn btn-sm btn-primary">
                Crear
            </a>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Calle y número</th>
                        <th colspan="2">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($addresses as $address)
                    <tr>
                        <td><strong>{{ $address->address_type }}</strong></td>
                        <td>{{ $address->street }}, {{ $address->number }}, (Espacio)</td>
                        <td width="10px">
                            <a href="#" class="btn btn-sm btn-default">Editar</a>
                        </td>
                        <td width="10px">
                            <button class="btn btn-sm btn-danger">Eliminar</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>   
            </table>
        </div>
    </div>
</div>

<div id="add_place" class="mt-2">
    <form id="form_add_place" method="POST" action="/admin/organizations/{{ $organization->id }}/place" method="POST">
        <div class="card-header">
            <h4>Nuevo Espacio / Dirección</h4>
        </div>
        <div class="card-body">
            @if($errors->any())
            <div class="alert alert-warning" role="alert">
                <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
            @elseif (Session::has('message_place'))
            <div class="alert alert-success" role="alert">
                {{ Session::get('message') }}
            </div>
            @endif
            @csrf
            <div class="form-group row">
                <label for="place" class="col-md-12 col-form-label">Espacio</label>
                <div class="col-md-8">
                    <select id="place" name="place" class="form-control form-control-xl selectpicker" data-live-search="true" disabled>
                        <option value="" >Selecciona...</option>
                        @foreach($places as $place)
                        <option value="{{ $place->id }}">
                            {{ $place->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="address_type" class="col-md-12 col-form-label">Calle(*)</label>
                <div class="col-md-3">
                    <select id="address_type" name="address_type" class="form-control form-control-xl" autofocus required>
                        <option value="1">Dirección</option>
                        <option value="2">Casa Central</option>
                        <option value="3">Sucursal</option>
                        <option value="4">Oficina</option>
                        <option value="5">Planta Industrial</option>
                        <option value="">Personalizar...</option>
                    </select>
                    <input name="address_type_name" id="address_type_name" type="hidden" value="">
                </div>
                <div class="col-md-8">
                    <select id="street" name="street" class="form-control form-control-xl selectpicker" data-live-search="true" required>
                        <option value="" >Selecciona...</option>
                        @foreach($streets as $street)
                        <option value="{{ $street->id }}">
                            {{ $street->name }}
                        </option>
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
                <label for="zone" class="col-md-12 col-form-label">Zona</label>
                <div class="col-md-5">
                    <select id="zone" name="zone" class="form-control form-control-xl selectpicker" data-live-search="true">
                        <option value="">Selecciona...</option>
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
                    <button type="submit" class="btn btn-primary">Agregar Espacio</button>
                </div>
                <div class="col-md-4">
                    <button type="reset" class="btn btn-outline-dark">Limpiar campos</button>
                </div>
            </div>
        </div>
    </form>
</div>
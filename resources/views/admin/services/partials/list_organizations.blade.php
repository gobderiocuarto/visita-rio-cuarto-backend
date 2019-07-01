<div class="alert alert-info mb-0" role="alert">
    <form method="POST" action='{{ url("/admin/services/$service->id/organizations") }}'>
        @csrf
        <div class="form-group row">
            <label for="place" class="col-md-12 col-form-label"><h5 class="alert-heading">Agregar organización...</h5></label>
            <div class="col-md-10">
                <select id="organization" name="organization" class="form-control form-control-xl selectpicker" data-live-search="true" data-default-value="" data-size="8" required>
                    <option value="">Seleccione...</option>
                    @foreach ($list_orgs as $organization)
                    <option value="{{ $organization->id }}">{{ $organization->name }}, ({{ $organization->category->name }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-success">Agregar</button>
            </div>
        </div>
    </form>
</div>
<table class="table table-striped table-hover">
    <thead class="thead-dark">
        <tr>
            <th width="25%">Organización</th>
            <th>Calle y número</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody class="mt-2">
        @forelse($service_orgs as $organization)
            <tr class="table-info">
                <td colspan="2"><strong>{{ $organization->name }}</strong></td>
                <td width="10px">
                    <form action="{{ url('admin/services/'.$service->id.'/organizations/'.$organization->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Desvincular</button>
                    </form>
                </td>
            </tr>
            @foreach($organization->places as $place)
            <tr>
                <td><span class="font-italic">{{ $place->pivot->address_type_name }}</span></td>
                <td colspan="3">{{ $place->address->street->name }} {{ $place->address->number }}@if($place->address->floor)- {{ $place->address->floor }}@endif- <strong>{{$place->name}}</strong></td>
            </tr>                                
            @endforeach
            @foreach($organization->addresses as $address)
            <tr>
                <td><span class="font-italic">{{ $address->pivot->address_type_name }}</span></td>
                <td colspan="3">
                    {{$address->street->name}} {{$address->number}}
                    @if($address->floor)- {{ $address->floor }}@endif
                    @if($address->zone)- <strong>{{$address->zone->name}}</strong>@endif
                </td>
            </tr>                                
            @endforeach
        @empty
        <tr class="table-info">
            <td colspan="4" align="center"><span class="font-italic">-- Aún no existen ubicaciones asociadas al servicio --</span></td>
        </tr>
        @endforelse
        </br>
    </tbody>   
</table>
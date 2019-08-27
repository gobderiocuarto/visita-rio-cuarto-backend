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
                @if (count($organization->spaces) === 0 and count($organization->addresses) === 0)
                    <tr>
                        <td colspan="4"><strong>Aún no se han asociado direcciones</strong></td>
                    </tr>
                @else
                    @foreach($organization->spaces as $space)
                    <tr>
                        <td><strong>{{ $space->pivot->address_type_name }}</strong></td>
                        <td>{{ $space->address->street->name }} {{ $space->address->number }}, <strong>{{ $space->name }}</strong></strong></td>
                        <td width="10px">
                            <button type="button" class="btn btn-sm btn-success places_btn_edit" data-rel-type="space" data-rel-value="{{ $space->id }}">
                                Editar
                            </button>
                        </td>
                        <td width="10px">
                            <form id="form_delete_space_{{ $space->id }}" action='{{ url("/admin/organizations/$organization->id/spaces/$space->id") }}' method="POST">
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
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
                @if (count($organization->places) === 0)
                    <tr>
                        <td colspan="4"><strong>Aún no se han asociado direcciones</strong></td>
                    </tr>
                @else
                    @foreach($organization->places as $place)
                    <tr>
                        @if (($place->address->street) && ($place->address->number))
                        <td><strong>{{ $place->address_type_name }}</strong></td>
                        <td>{{ $place->address->street->name }} {{ $place->address->number }}</td>
                        @else
                        <td></td>
                        <td>Dirección no definida</td>
                        @endif
                        <td width="10px">
                            <button type="button" class="btn btn-sm btn-success places_btn_edit" data-place-id="{{ $place->id }}" data-org-id="{{ $organization->id }}">
                            Editar
                            </button>
                        </td>
                        <td width="10px">
                            <form id="form_delete_place_{{ $place->id }}" action='{{ url("/admin/organizations/$organization->id/place/$place->id") }}' method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger places_btn_delete">Eliminar</button>
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
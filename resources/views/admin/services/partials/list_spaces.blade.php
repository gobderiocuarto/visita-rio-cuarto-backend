<div class="alert alert-info mb-0" role="alert">
    <form method="POST" action='{{ url("/admin/services/$service->id/spaces") }}'>
        @csrf
        <div class="form-group row">
            <label for="space" class="col-md-12 col-form-label"><h5 class="alert-heading">Agregar espacio...</h5></label>
            <div class="col-md-10">
                <select id="space" name="space" class="form-control form-control-xl selectpicker" data-live-search="true" data-default-value="" data-size="8" required>
                    <option value="">Seleccione...</option>
                    @foreach ($list_spaces as $space)
                    <option value="{{ $space->id }}">{{ $space->name }}</option>
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
            <th width="25%">Espacio</th>
            <th>Calle y número</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody class="mt-2">
        @forelse($service_spaces as $space)
            <tr class="table-info">
                <td><strong>{{ $space->name }}</strong></td>
                <td>{{ $space->address->street->name }} {{ $space->address->number }}</td>
                <td width="10px">
                    <form action="{{ url('admin/services/'.$service->id.'/spaces/'.$space->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Desvincular</button>
                    </form>
                </td>
            </tr>
        @empty
        <tr class="table-info">
            <td colspan="4" align="center"><span class="font-italic">-- Aún no existen ubicaciones asociadas al servicio --</span></td>
        </tr>
        @endforelse
        </br>
    </tbody>   
</table>
<form id="form_add_place" method="POST" action="/admin/organizations/" method="POST">
{{ method_field('PATCH') }}b
    <div class="card-header">
        Agregar espacio
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
        @elseif (Session::has('message'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('message') }}
        </div>
        @endif
        @csrf
        <div class="form-group row">
            <label for="street" class="col-md-3 col-form-label text-md-right">Calle(*)</label>
            <div class="col-md-8">
                <select id="place" name="place" class="form-control form-control-xl selectpicker" data-live-search="true"  required>
                    <option value="" >Selecciona...</option>
                    @foreach($places as $place)
                    <option value="{{ $place->id }}">
                        {{ $place->name }}
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
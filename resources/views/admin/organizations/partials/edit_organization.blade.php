<form id="form_organization_category" method="POST" action='{{ url("/admin/organizations/$organization->id")}}' enctype="multipart/form-data">
    {{ method_field('PATCH') }}
    @csrf
    <div class="form-group row">
        <label for="category_id" class="col-md-3 col-form-label text-md-right">Categoría (*)</label>
        <div class="col-md-8">
            <select class="selectpicker form-control form-control-xl" id="category_id" name="category_id" autofocus required>
                <option value="" >Selecciona...</option>
                @foreach($categories as $category)
                <option style="font-weight: bold;" value="{{ $category->id }}" @if ($category->id == $organization->category_id) selected @endif >
                    {{ $category->name }}
                </option>
                @foreach($category->categories as $subcategory)
                <option style="text-indent: 10px;" value="{{ $subcategory->id }}" @if ($subcategory->id == $organization->category_id) selected @endif >
                    {{ $subcategory->name }}
                </option>
                @endforeach                             
            @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="name" class="col-md-3 col-form-label text-md-right">Nombre (*)</label>
        <div class="col-md-8">
            <input name="name" id="name" type="text" class="form-control" value="{{ $organization->name }}" required minlength=3>
        </div>
    </div>
    <div class="form-group row">
        <label for="slug" class="col-md-3 col-form-label text-md-right">Slug</label>
        <div class="col-md-8">
            <input name="slug" id="slug" type="text" class="form-control" value="{{ $organization->slug }}" readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="description" class="col-md-3 col-form-label text-md-right">Breve descripción </label>
        <div class="col-md-8">
            <textarea class="form-control" name="description" rows="3">{{ $organization->description }}</textarea>
        </div>
    </div>
    <div class="form-group row">
        <label for="email" class="col-md-3 col-form-label text-md-right">Email (*)</label>
        <div class="col-md-8">
            <input name="email" id="email" type="email" class="form-control" value="{{ $organization->email }}" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="phone" class="col-md-3 col-form-label text-md-right">Teléfono</label>
        <div class="col-md-8">
            <input name="phone" id="phone" type="text" class="form-control" value="{{ $organization->phone }}" >
        </div>
    </div>
    <div class="form-group row">
        <label for="web" class="col-md-3 col-form-label text-md-right">Web</label>
        <div class="col-md-8">
            <input name="web" id="web" type="text" class="form-control" value="{{ $organization->web }}">
        </div>
    </div>
    <div class="form-group row">
        <label for="tags" class="col-md-3 col-form-label text-md-right">Listado de Servicios (separar mediante coma)</label>
        <div class="col-md-8">
            <input name="tags" id="tags" type="text" class="form-control" data-role="tagsinput" value="{{ $tags }}" placeholder="Etiquetas">
        </div>
    </div>
    <div class="form-group row">
        <label for="file" class="col-md-3 col-form-label text-md-right">Cargar nueva imagen</label>
        <div class="col-md-8">
            <input type="file" id="file" name="file" class="" value="{{ old('file') }}">
            <small class="form-text text-muted mt-2">El tamaño de la imagen debe ser etc, etc...</small>
        </div>
    </div>
    <div class="form-group row">
        <label for="file_alt" class="col-md-3 col-form-label text-md-right">Texto alternativo</label>
        <div class="col-md-8">
            <input type="text" id="file_alt" name="file_alt" class="form-control" @if($organization->file) value="{{$organization->file->file_alt}}" @else value="" @endif>
            <small class="form-text text-muted mt-2">Lorem ipsum ...</small>
        </div>
    </div>
    @if($organization->file)
    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">Imagen principal (actual)</label>
        <div class="col-md-3 ">
            <a target="_blank" href="{{ Storage::url("organizations/{$organization->id}/{$organization->file->file_path}") }}">
                <img class="img-fluid" src="{{ Storage::url("organizations/{$organization->id}/thumbs/{$organization->file->file_path}") }}" alt="{{$organization->file->file_alt}}">
            </a>
        </div>
    </div>
    @endif
    <div class="form-group row mt-5">
        <div class="col-md-4 offset-md-3">
            <button type="submit" class="btn btn-success">Actualizar organización</button>
        </div>
        <div class="col-md-4">
            <button type="reset" class="btn btn-outline-dark ">Restaurar datos</button>
        </div>
    </div>
</form>
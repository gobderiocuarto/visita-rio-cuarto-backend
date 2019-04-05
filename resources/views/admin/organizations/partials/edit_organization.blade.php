<form id="form_organization_category" method="POST" action="/admin/organizations/{{ $organization->id }}" method="POST">
    {{ method_field('PATCH') }}
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
        <label for="category_id" class="col-md-3 col-form-label text-md-right">Categoría (*)</label>
        <div class="col-md-8">
            <select id="category_id" name="category_id" class="form-control form-control-xl" autofocus required>
                <option value="" >Selecciona...</option>
                @foreach($categories as $category)
                    <optgroup label="{{ $category->name }}">
                        @foreach($category->categories as $option)
                        <option value="{{ $option->id }}" @if ($option->id == $organization->category_id) selected @endif >
                            {{ $option->name }}
                        </option>
                        @endforeach                                    
                    </optgroup>
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

    <div class="form-group row mb-0">
        <div class="col-md-4 offset-md-3">
            <button type="submit" class="btn btn-primary">Actualizar Organización</button>
        </div>
        <div class="col-md-4">
            <button type="reset" class="btn btn-outline-dark ">Limpiar campos</button>
        </div>
    </div>
</form>
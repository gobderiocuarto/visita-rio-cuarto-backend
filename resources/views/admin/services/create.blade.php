@extends('admin.layouts.app')
@section('meta_title') {{ config('app.name'). " - Admin :: Crear servicio" }} @endsection
@section('styles')
<link rel="stylesheet" href="{{ asset('css/typeahead.css') }}"/>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href='{{ url("/admin") }}'>Admin</a></li>
            <li class="breadcrumb-item"><a href='{{ url("/admin/services") }}'>Servicios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Crear Servicio</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            @include('admin.layouts.partials.errors_messages')
            <div class="card">
                <div class="card-header">
                    <h2>Crear Servicio</h2>
                </div>
                <form id="form_create_service" method="POST" action='{{ url("/admin/services") }}' method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Nombre (*)</label>
                            <div class="col-md-8">
                                <input name="name" id="name" type="text" class="typeahead form-control" value="{{ old('name') }}" autofocus required minlength=3>
                            </div>                           
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-3">
                                <button type="submit" class="btn btn-primary">Crear servicio</button>
                            </div>
                            <div class="col-md-4">
                                <a class="btn btn-outline-dark" href="{{ route('services.index') }}">Regresar al Listado</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/bloodhound.js"></script> -->
<script src="{{ asset('libs/typeahead/typeahead.bundle.js') }}"></script>
<script>

    $(document).ready(function(){

        // Typeahead para recuperar listado de Servicios existentes.
        var services = new Bloodhound({
              datumTokenizer: Bloodhound.tokenizers.whitespace,
              queryTokenizer: Bloodhound.tokenizers.whitespace,
              // url points to a json file that contains an array of country names
              prefetch: base_url+'/api/services/'
        });

        $('#name').typeahead({
          hint: true,
          highlight: true,
          minLength: 1
        },
        {
          name: 'services',
          source: services
        });

    }); // END document ready
</script>
@endsection
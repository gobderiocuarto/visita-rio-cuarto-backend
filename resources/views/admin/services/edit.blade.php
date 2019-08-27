@extends('admin.layouts.app')
@section('meta_title') {{ config('app.name'). " - Admin :: Editar etiqueta" }} @endsection
@section('styles')
<link rel="stylesheet" href="{{ asset('css/typeahead.css') }}"/>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href='{{ url("/admin") }}'>Admin</a></li>
            <li class="breadcrumb-item"><a href='{{ url("/admin/services") }}'>Etiquetas</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>Editar etiqueta servicio:</h3>
                    <h2><strong>"{{ $service->name }}"</strong></h2>
                </div>
                <form id="form_edit_service" method="POST" action='{{ url("/admin/services/$service->id") }}'>
                    {{ method_field('PATCH') }}
                    @csrf
                    <div class="card-body mt-2">
                        <div class="alert alert-secondary mb-3 text-right" >
                            <a href="{{ route('services.index') }}" class="btn btn-sm btn-primary ">
                            Volver al listado
                            </a>
                        </div>
                        <hr>
                        @include('admin.layouts.partials.errors_messages')
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Nombre (*)</label>
                            <div class="col-md-8">
                                <input name="name" id="name" type="text" class="typeahead form-control" value="{{ $service->name }}" autofocus required minlength=3>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-3">
                                <button type="submit" class="btn btn-success">Actualizar etiqueta</button>
                            </div>
                            <div class="col-md-4">
                                <button type="reset" class="btn btn-outline-dark">Restaurar datos</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div> <!-- card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Entidades asociadas a la etiqueta</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="nav-item">
                            <a href="#organizations_tab" class="nav-link active" data-toggle="tab" aria-controls="organizations_tab" role="tab" title="Datos de la Organización">
                                Organizaciones
                            </a>
                        </li>
                        <li role="presentation" class="nav-item">
                            <a href="#spaces_tab" class="nav-link" data-toggle="tab" aria-controls="spaces_tab" role="tab" title="Espacios">
                                Espacios
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content p-3 mt-2 border">
                        <div class="tab-pane active" role="tabpanel" id="organizations_tab">
                            @include('admin.services.partials.list_organizations')
                        </div>
                        <div class="tab-pane" role="tabpanel" id="spaces_tab">
                            @include('admin.services.partials.list_spaces')
                        </div>
                    </div> <!-- tab-content -->
                </div>
                {{-- $service_orgs->render() --}}
            </div> <!-- card -->
        </div> <!-- col -->
    </div> <!-- row -->
</div>
@endsection
@section('scripts')
<script src="{{ asset('libs/typeahead/typeahead.bundle.js') }}"></script>
<script>
    $(document).ready(function(){

        // $("#name, #slug").stringToSlug({
        //     callback: function(text){
        //         $('#slug').val(text);
        //     }
        // });

        // Redireccionar a tab según ancla url
        const hash = $(location).attr('hash'); 

        if (hash) {
            $('.tab-pane').removeClass('active')
            $('.nav-item a').removeClass('active')
            $('.nav-item a[href="'+hash+'"]').addClass('active')
            $(hash).tab('show')

        }
        

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

    });
</script>
@endsection
@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.7/dist/css/bootstrap-select.min.css">
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-md-10" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
            <li class="breadcrumb-item"><a href="/admin/organizations">Organizaciones</a></li>
            <li class="breadcrumb-item active" aria-current="page">Crear Organización</li>
          </ol>
        </nav>
        <div class="col-md-10 mx-auto mt-4">
            <h3>Editar organización:</h3>
            <h2><strong>"{{ $organization->name }}"</strong></h2>
            <ul class="nav nav-tabs mt-5" role="tablist">
                <li role="presentation" class="nav-item">
                    <a href="#organization_tab" class="nav-link" data-toggle="tab" aria-controls="organization_tab" role="tab" title="Datos de la Organización">
                    <a href="#step1" class="nav-link active" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1">
                        Datos de la organización
                    </a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#places_tab" class="nav-link active" data-toggle="tab" aria-controls="places_tab" role="tab" title="Espacios">
                        Espacios / Direcciones
                    </a>
                </li>
            </ul>
            <div class="tab-content p-3 mt-2 border">
                <div class="tab-pane" role="tabpanel" id="organization_tab">
                     @include('admin.organizations.partials.edit_organization')
                </div>
                <div class="tab-pane active" role="tabpanel" id="places_tab">
                    @include('admin.organizations.partials.places')
                    <a href="#complete" class="nav-link disabled" data-toggle="tab" aria-controls="complete" role="tab" title="Complete">
                        Espacio / Dirección
                    </a>
                </li>
            </ul>

            <form id="form_organization_category" method="POST" action="/admin/organizations/{{ $organization->id }}" method="POST">
            {{ method_field('PATCH') }}
                <div class="tab-content p-3 mt-2 border">
                    <div class="tab-pane active" role="tabpanel" id="step1">
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
                                <button type="button" class="btn btn-primary next-step">Actualizar Organización</button>
                            </div>
                            <div class="col-md-4">
                                <button type="reset" class="btn btn-outline-dark ">Limpiar campos</button>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button type="button" class="btn btn-primary ml-auto next-step">Save and continue</button>
                        </div>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="complete">
                        @include('admin.organizations.places')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.organizations.partials.modal_custom_place')

@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.7/dist/js/bootstrap-select.min.js"></script>
<script src="{{ asset('libs/stringToSlug/jquery.stringToSlug.min.js') }}"></script>
<script>

    function nextTab(elem) {
        $(elem).parent().next().find('a[data-toggle="tab"]').click();
    }
    function prevTab(elem) {
        $(elem).parent().prev().find('a[data-toggle="tab"]').click();
    }

    function string_to_slug(str) {
      str = str.replace(/^\s+|\s+$/g, ""); // trim
      str = str.toLowerCase();

      // remove accents, swap ñ for n, etc
      var from = "åàáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
      var to = "aaaaaaeeeeiiiioooouuuunc------";

      for (var i = 0, l = from.length; i < l; i++) {
        str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
      }

      str = str
        .replace(/[^a-z0-9 -]/g, "") // remove invalid chars
        .replace(/\s+/g, "-") // collapse whitespace and replace by -
        .replace(/-+/g, "-") // collapse dashes
        .replace(/^-+/, "") // trim - from start of text
        .replace(/-+$/, ""); // trim - from end of text

      return str;
    }
    
    
    $(document).ready(function(){

        $('.selectpicker').selectpicker();


        // Activar el modal custom address
        $("#address_type").change(function() {
            //alert($('#address_type_select option:selected').val())
            if($('#address_type option:selected').val() ==''){
                $('#address_type option[value="-1"]').remove();
                $("#create_custom_address").modal("show");
            } else {
                $('#address_type option[value="-1"]').remove();
                $("#address_type_name").val("");
            }

        });


        $('#form_create_custom_address').submit(function(event){
            event.preventDefault();
            var address_type = $("#address_custom_name").val();
            var address_value = -1;
            $("#address_type_name").val(address_type);

            var option = new Option(address_type, address_value); 
            $('#address_type').prepend($(option));
            $("#address_type option[value="+ address_value +"]").attr("selected",true);
            $("#create_custom_address").modal("hide");

        });


        $("#button_cancel").click(function(){
            $('#address_type option[value="-1"]').remove();
            $("#address_type").val('1')
            $('#address_type option[value="1"]').attr("selected",true)
        });



        $("#name, #slug").stringToSlug({
            callback: function(text){
                $('#slug').val(text);
            }
        }); 


        // tab wizard
        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            var $target = $(e.relatedTarget);
            if ($target.parent().hasClass('disabled')) {
                return false;
            }
        });

        $(".next-step").click(function (e) {
            var $active = $('.nav-tabs .nav-link.active');
            $active.parent().next().find('.nav-link').removeClass('disabled');
            nextTab($active);

        });
        $(".prev-step").click(function (e) {

            var $active = $('.nav-tabs li>a.active');
            prevTab($active);

        });   
    });

</script>
@endsection
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
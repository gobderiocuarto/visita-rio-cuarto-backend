@extends('admin.layouts.app')
@section('styles')
<link rel="stylesheet" href="{{ asset('libs/bootstrap-tagsinput/css/tagsinput.css') }}">
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
            <li class="breadcrumb-item"><a href="/admin/organizations">Organizaciones</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>Editar organización:</h3>
                    <h2><strong>"{{ $organization->name }}"</strong></h2>
                </div>
                <div class="card-body mt-2">
                    @include('admin.layouts.partials.errors_messages')
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="nav-item">
                            <a href="#organization_tab" class="nav-link active" data-toggle="tab" aria-controls="organization_tab" role="tab" title="Datos de la Organización">
                                Datos de la organización
                            </a>
                        </li>
                        <li role="presentation" class="nav-item">
                            <a href="#places_tab" class="nav-link" data-toggle="tab" aria-controls="places_tab" role="tab" title="Espacios">
                                Espacios / Direcciones
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content p-3 mt-2 border">
                        <div class="tab-pane active" role="tabpanel" id="organization_tab">
                             @include('admin.organizations.partials.edit_organization')
                        </div>
                        <div class="tab-pane" role="tabpanel" id="places_tab">
                            @include('admin.organizations.partials.places')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.organizations.partials.modal_custom_place')
@endsection
@section('scripts')
<script src="{{ asset('libs/bootstrap-tagsinput/js/tagsinput.js') }}"></script>
<script>

    // ----------------------------------------------------
    // Funciones
    // ----------------------------------------------------


    //Limpiar datos  de formulario nuevo / editar
    function clear_address(){

        $('#place option:selected').removeAttr('selected');
        $("#place").val($("#place").data("default-value"));
        $('#place').prop('disabled', false);

        $('#street_id option:selected').removeAttr('selected');
        $("#street_id").val($("#street_id").data("default-value"));
        $('#street_id').prop('disabled', false);

        $('#number').val("").prop('disabled', false);
        $('#floor').val("").prop('disabled', false);
        $('#lat').val("").prop('disabled', false);
        $('#lng').val("").prop('disabled', false);

        $('#zone_id option:selected').removeAttr('selected');
        $("#zone_id").val($("#zone_id").data("default-value"));
        $('#zone_id').prop('disabled', false);
        
        $('.selectpicker').selectpicker('refresh');
    }



    // Mostrar / ocultar formulario nuevo-editar / lista
    function showform(flag) {

        $('#address_type option[value="0"]').remove();
        $('#address_type option:selected').removeAttr('selected');
        $("#address_type").val($("#address_type").data("default-value"));
        $("#address_custom_name").val("");

        //$('#place option:selected').removeAttr('selected');
        //$("#place").val($("#place").data("default-value"));

        clear_address();

        if (flag){

            $("#list_places").hide();
            $("#add_edit_place").show();
            $("#btn_save").prop("disabled",false);
            //$("#btn_add").hide();

        } else {

            $("#list_places").show();
            $("#add_edit_place").hide();
            $("#btn_save").prop("disabled",true);
            //$("#btn_add").show();

        }
    }


    function loadPlaceAddress() {

        let rel_type = $("#rel_type").val()
        let rel_value = $("#rel_value").val()

        if (rel_value &&  rel_value) {

            const title_add_edit = 'Editar espacio / dirección'
            let url_get

            let organization = $('#organization').val()

            $('#title_add_edit_place').empty();
            $('#title_add_edit_place').html(title_add_edit)
            
            switch (rel_type) {
                case 'place':
                    url_get = base_url+"/api/organizations/"+organization+"/places/"+rel_value
                    break;

                case 'address':

                    url_get = base_url+"/api/organizations/"+organization+"/addresses/"+rel_value
                    break;
              
                default:
                    break;
            }


            $.get(url_get, function(data){ 

                console.log(data);

                showform(true)

                // Si trae data.address se trata de un espacio
                if (data.address) {

                    if (data.address_type_id == 0) {
                        var option = new Option(data.address_type_name, data.address_type_id); 
                        $('#address_type').prepend($(option));
                    }
                    
                    $('#address_type option[value="'+data.address_type_id+'"]').attr("selected",true);
                    $('#address_type_name').val(data.address_type_name);

                    $('#place option:selected').removeAttr('selected');
                    //$('#place option[value="3"]').attr("selected",true);
                    $("#place").val(data.id);

                    $('#street_id option:selected').removeAttr('selected');
                    //$("#street_id option[value="+data.address.street_id+"]").attr("selected",true);
                    $("#street_id").val(data.address.street_id);
                    $('#street_id').prop('disabled', true);

                    $('#number').val(data.address.number).prop('disabled', true);
                    $('#floor').val(data.address.floor).prop('disabled', true);
                    $('#lat').val(data.address.lat).prop('disabled', true);
                    $('#lng').val(data.address.lng).prop('disabled', true);                    

                    $('#zone_id option:selected').removeAttr('selected');
                    //$("#zone_id option[value="+data.address.zone.id+"]").attr("selected",true);
                    $("#zone_id").val(data.address.zone_id);
                    $('#zone_id').prop('disabled', true);

                    $('.selectpicker').selectpicker('refresh');

                } else { // se trata de una direc

                    if (data.address_type_id == 0) {
                        var option = new Option(data.address_type_name, data.address_type_id); 
                        $('#address_type').prepend($(option));
                    }
                    $('#address_type option[value="'+data.address_type_id+'"]').attr("selected",true);
                    $('#address_type_name').val(data.address_type_name);

                    $('#street_id option:selected').removeAttr('selected');
                    $("#street_id").val(data.street_id);

                    $('#number').val(data.number);
                    $('#floor').val(data.floor);
                    $('#lat').val(data.lat);
                    $('#lng').val(data.lng);                    

                    $('#zone_id option:selected').removeAttr('selected');
                    $("#zone_id").val(data.zone_id);

                    $('.selectpicker').selectpicker('refresh');
                }

                //$("#nombre").val(data.name);
                
            });
            
        }

    }

    
    $(document).ready(function(){


        // ----------------------------------------------------
        // (Tab) Editar datos de la organización
        // ----------------------------------------------------


        // Formatear slug a partir del name
        $("#name").stringToSlug({
            callback: function(text){
                $('#slug').val(text);
            }
        }); 




        // ----------------------------------------------------
        // (Tab) Asociar lugares / direcciones a organización
        // ----------------------------------------------------


        // Habilitar bootstrap-select
        $('.selectpicker').selectpicker();


        // Redireccionar a tab según ancla url
        const hash = $(location).attr('hash'); 

        if (hash) {
            $('.tab-pane').removeClass('active')
            $('.nav-item a').removeClass('active')
            $('.nav-item a[href="'+hash+'"]').addClass('active')
            $(hash).tab('show')

        }

        loadPlaceAddress()

        // NUEVO espacio / dirección: Mostrar form al presionar boton 
        $("#places_btn_add").click(function(){

            const title_add_edit = 'Asociar espacio / dirección'

            $("#title_add_edit_place").empty();
            $('#title_add_edit_place').html(title_add_edit)

            // $("#rel_type").val("")
            // $("#rel_value").val("")
            showform(true) 

        });


        // EDITAR espacio / dirección asociada: Mostrar form al presionar boton
        $(".places_btn_edit").click(function(){

            // let rel_type = $(this).data("rel-type")
            // let rel_value = $(this).data("rel-value")

            $("#rel_type").val($(this).data("rel-type"))
            $("#rel_value").val($(this).data("rel-value"))

            // loadPlaceAddres(rel_type, rel_value)
            loadPlaceAddress()



            /*const title_add_edit = 'Editar espacio / dirección'
            let url_get
            let organization = $('#organization').val()

            let rel_type = $(this).data("rel-type")
            let rel_value = $(this).data("rel-value")
            
            $("#rel_type").val(rel_type)
            $("#rel_value").val(rel_value)

            $('#title_add_edit_place').empty();
            $('#title_add_edit_place').html(title_add_edit)
            

            switch (rel_type) {
                case 'place':
                    url_get = base_url+"/api/organizations/"+organization+"/places/"+rel_value
                    break;

                case 'address':

                    url_get = base_url+"/api/organizations/"+organization+"/addresses/"+rel_value
                    break;
              
                default:
                    break;
            }

            // alert(url_get)

            $.get(url_get, function(data){ 

                console.log(data);

                showform(true)

                // Si existe address se trata de un espacio
                if (data.address) {

                    if (data.address_type_id == 0) {
                        var option = new Option(data.address_type_name, data.address_type_id); 
                        $('#address_type').prepend($(option));
                    }
                    
                    $('#address_type option[value="'+data.address_type_id+'"]').attr("selected",true);
                    $('#address_type_name').val(data.address_type_name);

                    $('#place option:selected').removeAttr('selected');
                    //$('#place option[value="3"]').attr("selected",true);
                    $("#place").val(data.id);


                    $('#street_id option:selected').removeAttr('selected');
                    //$("#street_id option[value="+data.address.street_id+"]").attr("selected",true);
                    $("#street_id").val(data.address.street_id);
                    $('#street_id').prop('disabled', true);

                    $('#number').val(data.address.number).prop('disabled', true);
                    $('#floor').val(data.address.floor).prop('disabled', true);
                    $('#lat').val(data.address.lat).prop('disabled', true);
                    $('#lng').val(data.address.lng).prop('disabled', true);                    

                    $('#zone_id option:selected').removeAttr('selected');
                    //$("#zone_id option[value="+data.address.zone.id+"]").attr("selected",true);
                    $("#zone_id").val(data.address.zone_id);
                    $('#zone_id').prop('disabled', true);

                    $('.selectpicker').selectpicker('refresh');

                } else {

                    if (data.address_type_id == 0) {
                        var option = new Option(data.address_type_name, data.address_type_id); 
                        $('#address_type').prepend($(option));
                    }
                    $('#address_type option[value="'+data.address_type_id+'"]').attr("selected",true);
                    $('#address_type_name').val(data.address_type_name);

                    $('#street_id option:selected').removeAttr('selected');
                    $("#street_id").val(data.street_id);

                    $('#number').val(data.number);
                    $('#floor').val(data.floor);
                    $('#lat').val(data.lat);
                    $('#lng').val(data.lng);                    

                    $('#zone_id option:selected').removeAttr('selected');
                    $("#zone_id").val(data.zone_id);

                    $('.selectpicker').selectpicker('refresh');
                }

                //$("#nombre").val(data.name);
                
            });*/
             
        });


        // CANCELAR nuevo / edicion de espacio / dirección
        $("#places_btn_cancel").click(function(){

            $("#rel_type").val("")
            $("#rel_value").val("")

            showform(false) 

        });

        
        // Activar el modal para crear nuevo tipo de dirección
        $("#address_type").change(function() {

            // Remuevo cualquier option personalizado anterior
            $('#address_type option[value="0"]').remove();

            // "Si se eligio la opción Personalizar..., muestro modal"
            if($('#address_type option:selected').val() =='-1'){ 
                $("#create_custom_address").modal("show");
            } else {
                // Asigno vacio al nombre tipo de dirección peronalizado
                $("#address_type_name").val("");
            }

        });

        

        // Cargar nuevo tipo de dirección desde lo ingresado en el modal
        $('#form_create_custom_address').submit(function(event){
            event.preventDefault();
            var address_type_name = $("#address_custom_name").val();
            var address_value = 0;
            $("#address_type_name").val(address_type_name);

            var option = new Option(address_type_name, address_value); 
            $('#address_type').prepend($(option));
            $("#address_type option[value="+ address_value +"]").attr("selected",true);
            $("#create_custom_address").modal("hide");

        });


        // Cancelar creación de nuevo tipo de dirección desde el modal
        $("#button_cancel").click(function(){
            $('#address_type option[value="0"]').remove();
            $("#address_type").val('1')
            //$('#address_type option[value="1"]').attr("selected",true)
        });



        // Cargar / quitar los datos del espacio en el form
        $("#place").change(function() {

            let place_id = $('#place option:selected').val()

            if (place_id == "") { // No se asocia espacio

                clear_address();

            } else {

                let url_place = base_url+"/api/places/"+place_id

                /*
                $.get(url_place, function(data){ 
                    console.log(data); 
                });
                */

                $.ajax({

                    type: "GET",
                    dataType: "json",
                    url: url_place,

                    success: function(data) {

                        //console.log(data);

                        $('#number').val(data.address.number).prop('disabled', true);
                        $('#floor').val(data.address.floor).prop('disabled', true);
                        $('#lat').val(data.address.lat).prop('disabled', true);
                        $('#lng').val(data.address.lng).prop('disabled', true);

                        $('#street_id option:selected').removeAttr('selected');
                        $("#street_id option[value="+data.address.street_id+"]").attr("selected",true);
                        $('#street_id').prop('disabled', true);

                        $('#zone_id option:selected').removeAttr('selected');
                        $("#zone_id option[value="+data.address.zone_id+"]").attr("selected",true);
                        $('#zone_id').prop('disabled', true);

                        $('.selectpicker').selectpicker('refresh');
                    }, 

                    error: function(e) {
                        alert ("error get")
                    }

                });

            }

        });
        
    });

</script>
@endsection
@extends('admin.layouts.app')
@section('meta_title') {{ config('app.name'). " - Admin :: Editar organización" }} @endsection
@section('styles')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{ asset('libs/jquery-tagsinput/css/jquery.tagsinput-revisited.css') }}"/>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Admin</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/organizations') }}">Organizaciones</a></li>
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
                    <div class="alert alert-secondary mb-3 text-right" >
                        <a href="{{ route('organizations.index') }}" class="btn btn-sm btn-primary ">
                        Volver al listado
                        </a>
                    </div>
                    <hr>
                    @include('admin.layouts.partials.errors_messages')
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="nav-item">
                            <a href="#organization_tab" class="nav-link active" data-toggle="tab" aria-controls="organization_tab" role="tab" title="Datos de la Organización">
                                Datos de la organización
                            </a>
                        </li>
                        <li role="presentation" class="nav-item">
                            <a href="#places_tab" class="nav-link" data-toggle="tab" aria-controls="places_tab" role="tab" title="Espacios">
                                Ubicaciones
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content p-3 mt-2 border">
                        <div class="tab-pane active" role="tabpanel" id="organization_tab">
                             @include('admin.organizations.partials.edit_organization')
                        </div>
                        <div class="tab-pane" role="tabpanel" id="places_tab">
                            @include('admin.organizations.partials.list_places')
                            @include('admin.organizations.partials.edit_places')
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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script src="{{ asset('libs/jquery-tagsinput/js/jquery.tagsinput-revisited.js') }}"></script>
<script>

    // ----------------------------------------------------
    // Functions
    // ----------------------------------------------------

    // Limpiar datos de formulario nuevo / editar
    function clear_address(){
        //setear el valor de select espacio por defecto
        // $('#space option:selected').removeAttr('selected');
        // $("#space").val($("#space").data("default-value"));
        // $('#space').prop('disabled', false);

        //setear el valor de select ubicacion padre por defecto
        $('#place_id option:selected').removeAttr('selected');
        $("#place_id").val($("#place_id").data("default-value"));
        $('#place_id').prop('disabled', false);

        //setear el valor de select street (id) por defecto
        $('#street_id option:selected').removeAttr('selected');
        $("#street_id").val("0");
        $('#street_id').prop('disabled', false);

        //setear valores vacios y habilitar campos
        $('#number').val("").prop('disabled', false);
        $('#lat').val("").prop('disabled', false);
        $('#lng').val("").prop('disabled', false);

        //setear el valor de select zona (id) por defecto
        $('#zone_id option:selected').removeAttr('selected');
        $("#zone_id").val($("#zone_id").data("default-value"));
        $('#zone_id').prop('disabled', false);
        
        $('.selectpicker').selectpicker('refresh');
    }



    // Mostrar / ocultar formulario nuevo-editar lugares
    function showNewEditPlace(flag) {

        // $('#container option[value="0"]').remove();
        // $('#container option:selected').removeAttr('selected');

        // Eliminar opcion tipo de ubicacion personalizada
        $('#address_type_id option[value="0"]').remove();
        $('#address_type_id option:selected').removeAttr('selected');
        $("#address_type_id").val($("#address_type_id").data("default-value"));

        $("#address_custom_name").val("");
        $('#apartament').val("");

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


    // Recuperar datos de ubicación para mostrar en form de Edición de ubicacion
    function loadPlace(org_id, place) {

        const title_add_edit = 'Editar ubicación'
        let url_get

        $('#title_add_edit_place').empty();
        $('#title_add_edit_place').html(title_add_edit)
        
        $("#place").val(place);

        url_get = base_url+"/admin/organizations/"+org_id+"/places/"+place

        $.ajax({
            type: "GET",
            url: url_get,

            success: function(data) {

                // console.log(data)

                showNewEditPlace(true)

                // Agregamos el option del tipo de ubicación personalizada, si esta definida
                if (data.address_type_id == 0) {
                    var option = new Option(data.address_type_name, data.address_type_id); 
                    $('#address_type_id').prepend($(option));
                }

                // Activar option select si/no ubicacion definida como contenedor
                $('#container option:selected').removeAttr('selected');
                $('#container').val(data.container);

                // la Ubicacion actual es contenedor?
                if (data.container == 'is-container') {
                    console.log(data.container)
                    // desactivo el select de ubicaciones contenedoras
                    $("#place_id").prop('disabled', 'disabled');
                } 
                
                
                //option selected: tipo de ubicacion
                $('#address_type_id option[value="'+data.address_type_id+'"]').attr("selected",true);
                $('#address_type_name').val(data.address_type_name);
                $('#apartament').val(data.apartament);

                // Escoder option de ubicacion actual en listado / select de ubicaciones contenedoras
                $("#place_id option").show();
                $('#place_id option[value="'+place+'"]').hide();
                
                //Elegir elemento option selected: ubicacion padre
                $('#place_id option:selected').removeAttr('selected');
                $("#place_id").val(data.place_id);
                
                //option selected: calle id
                $('#street_id option:selected').removeAttr('selected');
                $("#street_id").val(data.address.street_id);

                //Load Datos de address
                $('#number').val(data.address.number);
                $('#lat').val(data.address.lat);
                $('#lng').val(data.address.lng);                    
                
                $('#zone_id option:selected').removeAttr('selected');
                $("#zone_id").val(data.address.zone_id);
                    

                $('.selectpicker').selectpicker('refresh');
                
            },  // success

            error: function(e) {
                alert ("Se produjo un error al recuperar los datos a editar ")
            }

        });
    }

    // ----------------------------------------------------
    // END Functions
    // ----------------------------------------------------


    
    $(document).ready(function(){


        // Habilitar bootstrap-select
        $('.selectpicker').selectpicker();

        // Redireccionar a tab según ancla en url
        const hash = $(location).attr('hash'); 

        if (hash) {
            $('.tab-pane').removeClass('active')
            $('.nav-item a').removeClass('active')
            $('.nav-item a[href="'+hash+'"]').addClass('active')
            $(hash).tab('show')

        }


        // ----------------------------------------------------
        // ----------------------------------------------------
        // (Tab) Editar datos de la organización
        // ----------------------------------------------------
        // ----------------------------------------------------

        // Formatear slug a partir del name
        $("#name").stringToSlug({
            callback: function(text){
                $('#slug').val(text);
            }
        }); 


        // Obtener listado de servicios (tags group servicios) (callback)
        function responseGetData(data){

            var result = [];
            result.push(data);
            //console.log(result)
            return (result)

        };

        
        // Obtener listado de servicios
        $('#tags').tagsInput({
            'autocomplete': {
                source : function (request, responseGetData) {
                    var term = request.term;
                    $.get('/admin/tags/services/'+term, function(data){
                        // console.log(data)
                        responseGetData(data);
                    });
                }
            } 
        });




        // ----------------------------------------------------
        // ----------------------------------------------------
        // (Tab) Asociar lugares / direcciones a organización
        // ----------------------------------------------------
        // ----------------------------------------------------

        // ---------------------------------------------------------------------------
        // NUEVA UBICACION: Mostrar form edit places y ocultar list al presionar boton 
        // ---------------------------------------------------------------------------

        $("#places_btn_add").click(function(){

            const title_add_edit = 'Asociar ubicación'

            $("#title_add_edit_place").empty();
            $('#title_add_edit_place').html(title_add_edit)
            $('#place').val("0");

            // Mostrar formulario nuevo editar
            showNewEditPlace(true) 

        });


        // ----------------------------------------------------------------------------------
        // LISTAR UBICACION: Cargar y mostrar Form EDITAR ubicación al presionar boton EDITAR
        // ----------------------------------------------------------------------------------

        $(".places_btn_edit").click(function(){

            let place_id = $(this).data("place-id")
            let org_id = $(this).data("org-id")

            loadPlace(org_id, place_id)

        });


        // ------------------------------------------------------------
        // CANCELAR y ocultar Form nueva / edicion de UBICACION PUNTUAL
        // ------------------------------------------------------------

        $("#places_btn_cancel").click(function(){

            $('#place').val("0");
            
            $("#rel_type").val("")
            $("#rel_value").val("")

            showNewEditPlace(false) 

        });


        // -------------------------------------------------------------
        // Form nueva / edicion de UBICACION:
        // Si se selecciona tipo de ubicacion = CONTENEDOR, 
        // se desactiva la opción de asociar a otro Espacio CONTENEDOR
        // -------------------------------------------------------------
        $("#container").change(function() {

            if ($(this).val() == 'is-container') {

                $('#place_id option:selected').removeAttr('selected');
                $("#place_id").val("");
                $("#place_id").prop('disabled', 'disabled');
                $('#place_id').selectpicker('refresh');

            } else {
                
                $('#place_id option:selected').removeAttr('selected');
                $("#place_id").val("");
                $("#place_id").removeAttr("disabled");
                $('#place_id').selectpicker('refresh');
            }
    

        });


        // -----------------------------------------------------------------------------
        // NUEVA / EDITAR UBICACION: Activar el modal para crear nuevo tipo de ubicacion
        // -----------------------------------------------------------------------------

        $("#address_type_id").change(function() {

            // Remuevo cualquier option personalizado anterior
            $('#address_type_id option[value="0"]').remove();

            // "Si se eligio la opción Personalizar..., muestro modal"
            if($('#address_type_id option:selected').val() =='-1'){ 
                $("#create_custom_address").modal("show");
            } else {
                // Asigno vacio al nombre tipo de dirección personalizado
                $("#address_type_name").val("");
            }

        });

        
        // -----------------------------------------------------------------------------------------------------
        //  NUEVA / EDITAR UBICACION: Cargar nuevo tipo de ubicacion en el form, desde lo ingresado en el modal
        // -----------------------------------------------------------------------------------------------------
        
        $('#form_create_custom_address').submit(function(event){
            event.preventDefault();
            var address_type_name = $("#address_custom_name").val();
            var address_value = 0;
            $("#address_type_name").val(address_type_name);

            var option = new Option(address_type_name, address_value); 
            $('#address_type_id').prepend($(option));
            $("#address_type_id option[value="+ address_value +"]").attr("selected",true);
            $("#create_custom_address").modal("hide");

        });


        // -------------------------------------------------------------------------------------
        // NUEVA / EDITAR UBICACION: Cancelar creación de nuevo tipo de ubicacion desde el modal
        // -------------------------------------------------------------------------------------

        $("#button_cancel").click(function(){
            $('#address_type_id option[value="0"]').remove();
            $("#address_type_id").val('1')
        });



        // -------------------------------------------------------------------------------------------------------------
        // NUEVA / EDITAR UBICACION: 
        // Al elegir una ubicacion padre desde el select, carga / copia los datos correspondientes en el form de edicion
        // -------------------------------------------------------------------------------------------------------------

        $("#place_id").change(function() {

            // let container_id = $('#place_id option:selected').val()
            let container_id = $(this).val();

            if (container_id == "") { // No se asocia a un espacio -->limpiamos campos

                clear_address();

            } else {

                // Traer datos de la ubicación elegida

                let url_place = base_url+"/admin/places/"+container_id

                $.ajax({

                    type: "GET",
                    dataType: "json",
                    url: url_place,

                    success: function(data) {

                        console.log(data);

                        // cargamos datos desde la ubicacion
                        $('#number').val(data.address.number);
                        $('#lat').val(data.address.lat);
                        $('#lng').val(data.address.lng);

                        // Setear mostrar la calle correspondiente en el select 
                        $('#street_id option:selected').removeAttr('selected');
                        $("#street_id").val(data.address.street_id);
                        
                        // Setear mostrar la zona correspondiente en el select 
                        $('#zone_id option:selected').removeAttr('selected');
                        $("#zone_id option[value="+data.address.zone_id+"]").attr("selected",true);
                        // $('#zone_id').prop('disabled', true);

                        $('.selectpicker').selectpicker('refresh');
                    }, 

                    error: function(e) {
                        // alert ("error get")
                    }

                });

            }

        });


        // Eliminar Ubicación : Confirmación de borrado mediante SweetAlert
        $('.places_btn_delete').click(function() {

            event.preventDefault();

            swal({
                title: "¡Atención!",
                text: "Se dispone a eliminar una ubicación...",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
            .then((willDelete) => {
                if (willDelete) {

                    let form = '#'+$(this).closest("form").attr('id');
                    $(form).submit();

                } else {
                    swal("La acción fue cancelada");
                }
            });
        });


        // Cargar / quitar los datos del espacio en el form al elegir una opcion 
        // en el select "Asociar a ubicación existente" o no asociar a espacio
        $("#space").change(function() {

            let space_id = $('#space option:selected').val()

            if (space_id == "") { // No se asocia espacio -->limpiamos campos

                clear_address();

            } else {

                let url_space = base_url+"/admin/organizations/spaces/"+space_id

                $.ajax({

                    type: "GET",
                    dataType: "json",
                    url: url_space,

                    success: function(data) {

                        console.log(data);
                        // cargamos datos desde el espacio y ponemos los campos como disabled
                        $('#number').val(data.address.number).prop('disabled', true);
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
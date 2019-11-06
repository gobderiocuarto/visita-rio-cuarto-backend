@extends('admin.layouts.app')
@section('meta_title') {{ config('app.name'). " - Admin :: Editar evento" }} @endsection
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
            <li class="breadcrumb-item"><a href="{{ url('/admin/events') }}">Eventos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
          </ol>
        </nav>
        <div class="col-12 col-md-10 mt-4">
            <h3>Editar evento:</h3>
            <h1><strong>{{ $event->title }}</strong></h1>
            </br>
            <div class="card mt-3">
                <div class="card-footer text-left">
                    <a href="{{ route('events.index', Session::get('redirect') ) }}" class="btn btn-md btn-success "><< Volver al listado</a>
                </div>
            </div>
            <hr>
            @if ($event->frame)
            <div class="alert alert-warning" role="alert">
                Atención: Estás editando un 'Evento Marco'
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <hr>
            @endif
            @include('admin.layouts.partials.errors_messages')
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="nav-item">
                    <a href="#event" class="nav-link active" data-toggle="tab" aria-controls="event" role="tab" title="Datos del evento">
                        Datos del evento
                    </a>
                </li>
                <li role="presentation" class="nav-item">
                    <a href="#image" class="nav-link" data-toggle="tab" aria-controls="image" role="tab" title="Imagen del evento">
                        Imagen del evento 
                    </a>
                </li>
            </ul><!-- nav-tabs -->
            <div class="tab-content p-3 mt-2">
                <div class="tab-pane active" role="tabpanel" id="event">
                    <form id="form_event" method="POST" action='{{ url("/admin/events/$event->id#event")}}' enctype="multipart/form-data">
                        {{ method_field('PATCH') }}
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h3><a name="event">Datos de evento</h3>
                            </div>
                            <div class="card-body mt-2">
                                @include('admin.events.partials.edit_event_base')
                                @if ($event->frame)
                                    @include('admin.events.partials.edit_event_frame')
                                @else          
                                    @include('admin.events.partials.edit_event_no_frame')
                                @endif
                            </div>
                            @if (!$event->frame)
                                @include('admin.events.partials.edit_event_place')
                            @endif  
                        </div>
                        <div class="card">
                            <div class="card-footer">
                                <div class="row pt-2 pb-2">
                                    <div class="col-md-4 offset-md-3">
                                        <button type="submit" class="btn btn-success">Actualizar Evento</button>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="reset" class="btn btn-outline-dark ">Restaurar datos</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    @if (!$event->frame)
                        @include('admin.events.partials.list_calendars')
                    @endif
                </div>
                <div class="tab-pane" role="tabpanel" id="image">
                    @include('admin.events.partials.event_image')
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-footer text-left">
                    <a href="{{ route('events.index', Session::get('redirect') ) }}" class="btn btn-md btn-success "><< Volver al listado</a>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.events.partials.modal_create_edit_calendar')
@include('admin.events.partials.modal_load_place')
@endsection
@section('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/15.0.0/classic/ckeditor.js"></script>
<script src="{{ asset('libs/jquery-tagsinput/js/jquery.tagsinput-revisited.js') }}"></script>
<script>
    ClassicEditor
            .create( document.querySelector( '#description' ))

    // ----------------------------------------------------
    // Functions
    // ----------------------------------------------------

    // Callback Obtener listado de tags
    function responseGetData(data){
        var result = [];
        result.push(data);
        // console.log(result)
        return (result)

    };

    // Borrar los datos preexistentes del form nuevo / editar calendario - función 
    function emptyFormCalendar() {

        $("#title_add_edit_function").empty()
        $("#start_date").val('')
        $("#start_time").val('')
        $("#end_date").val('')
        $("#end_time").val('')
        $("#observations").val('')
        $("#calendar_id").val('0')
    }


    // Obtener los datos de un calendario para mostrar en modal edición 
    function loadCalendar(id_event, id_calendar) {

        emptyFormCalendar()
        // const url_api =base_url+'/api/events/'+id_event+'/calendars/'+id_calendar
        const url_api ='/admin/events/'+id_event+'/calendars/'+id_calendar

        $.get(url_api, function(data){
            // console.log(data)
            $('#title_add_edit_calendar').html('Editar Función')
            $("#calendar_id").val(data.id)
            $("#start_date").val(data.start_date)
            $("#start_time").val(data.start_time)
            $("#end_date").val(data.end_date)
            $("#end_time").val(data.end_time)
            $("#observations").val(data.observations)
        });

    }


    // ----------------------------------------------------
    // END Functions
    // ----------------------------------------------------

    
    $(document).ready(function(){


        // Redireccionar a tab según ancla en url
        const hash = $(location).attr('hash'); 

        if (hash) {
            $('.tab-pane').removeClass('active')
            $('.nav-item a').removeClass('active')
            $('.nav-item a[href="'+hash+'"]').addClass('active')
            $(hash).tab('show')

        }


        // ----------------------------------------------------
        // Edicion datos de evento
        // ----------------------------------------------------

        // Formatear slug a partir del title
        $("#title").stringToSlug({
            callback: function(text){
                $('#slug').val(text);
            }
        }); 


        // Mostrar manejo de tags (fuera de grupo eventos)
        $('#tags_no_events').tagsInput({
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


        // Confirmar eliminación de imagen de evento
        $("#delete_image").click(function(){

            event.preventDefault()

            swal({
              title: "¡Atención!",
              text: "¿Confirma que desea eliminar la imagen?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $("#form_delete_image_event").submit();
                } 
            });
        
        }); 


        // ----------------------------------------------------
        // ----------------------------------------------------
        // Evento Marco
        // ----------------------------------------------------
        // ----------------------------------------------------

        // ---------------------------------------------------------------
        // Fecha finalizacion sea igual o sup a la de inicio
        // ---------------------------------------------------------------

        $("#frame_start_date").change(function(){
            
            var date = new Date()
            var today = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate()

            if ($("#frame_start_date").val() <= today) {
                var min_end_date = today
            } else {
                var min_end_date = $("#frame_start_date").val()
            }
                
            $("#frame_end_date").attr({
                "min": min_end_date
            });
        });



        // ----------------------------------------------------
        // ----------------------------------------------------
        // Evento NO Marco
        // ----------------------------------------------------
        // ----------------------------------------------------


        // ----------------------------------------------------
        // Mostrar MODAL form Vincular evento a espacio
        // ----------------------------------------------------

        $("#load_place").click(function(){

            event.preventDefault()            
            $("#modal_load_place").modal("show");

        });


        // ----------------------------------------------------
        // Mostrar listado de ubicaciones bajo el input del modal  
        // ----------------------------------------------------          

        $('#load_place_name').autocomplete({
            minLength : 2,
            autoFocus : true,
            dataType: "json",
            source : function (request, response) {
                var term = request.term;
                $.get('/admin/organizations/places/'+term, function(data){

                    var suggestions = [];
                    $.each(data, function(i, val) {
                        suggestions.push(
                            {
                                value : val.id,
                                label : val.name,
                            }
                        ); 
                    });
                    response(suggestions)
                });
            },
            
            appendTo: $('#list_places'),

            select: function(event, ui) {
                event.preventDefault();
                $("#load_place_name").val(ui.item.label);
                $("#load_place_id").val(ui.item.value);
            }

        });


        // ----------------------------------------------------
        // Cargar ubicación al seleccionarla desde el modal  
        // ---------------------------------------------------- 

        $("#form_load_place").submit(function(event){
            event.preventDefault()
            $("#place_name").val($('#load_place_name').val());
            $("#place_id").val($('#load_place_id').val());

            $("#load_place_name").val('')
            $("#load_place_id").val('')

            $("#modal_load_place").modal("hide");

        });


        // ----------------------------------------------------
        // Calendarios-Funciones asociados a evento
        // ----------------------------------------------------

        function calendarItemTemplate(calendar) {

            if(typeof(calendar.observations) === 'object') {
                var observations = ''
            } else {
                var observations = calendar.observations
            }
            return (
            `<tr class="calendars" data-event-id="${calendar.event_id}" data-calendar-id="${calendar.id}">
                <td class="start_date">${calendar.start_date}</td>
                <td>${calendar.start_time} hs</td>
                <td>${observations}</td>
                <td class="calendar_btn_edit">
                    <button type="button" class="btn btn-sm btn-success" >
                        Editar
                    </button>
                </td>
                <td>
                    <form class="form_delete_calendar" method="POST">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="${calendar.token}">
                        <button type="submit" class="delete_calendar btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>`
            )
        }


        // ----------------------------------------------------
        // Mostrar MODAL form NUEVO calendario(fecha-hora...)
        // ----------------------------------------------------

        $("#calendar_btn_add").click(function(){

            event.preventDefault()

            emptyFormCalendar()
            $('#title_add_edit_calendar').html('Agregar Función')

            // Copiar fecha de inicio de ultimo calendario agregado, si existe
            let $liCalendar = document.querySelectorAll('.start_date')
            if ($liCalendar.length) {
                let $lastCalendar = $liCalendar[$liCalendar.length - 1];
                $("#start_date").val($lastCalendar.innerHTML)
            }
            $("#modal_create_edit_calendar").modal("show");

        });


        // ----------------------------------------------------
        // Mostrar MODAL form EDITAR calendario-función
        // ----------------------------------------------------

        $("#list_calendars").on("click", "tr .calendar_btn_edit", function() { // Llamar asi para que el modal se muestra también con los elementos agregados dinamicamente

            let $calendar = $(this).closest(".calendars")
            let id_event = $($calendar).data("event-id")
            let id_calendar = $($calendar).data("calendar-id")

            loadCalendar(id_event, id_calendar)
            $("#modal_create_edit_calendar").modal("show")

        });

        // ----------------------------------------------------
        // Mostrar MODAL form EDITAR calendario-función
        // ----------------------------------------------------

         $("#start_date").change(function(){

            var date = new Date()
            var today = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate()

            if ($("#start_date").val() <= today) {
                var min_end_date = today
            } else {
                var min_end_date = $("#start_date").val()
            }
            $("#end_date").attr({
                "min": min_end_date
            });
        });


        // ----------------------------------------------------
        // Submit MODAL form NUEVO-EDITAR calendario-función
        // ----------------------------------------------------

        $("#form_add_edit_calendar").submit(function(event){

            event.preventDefault();

            let event_id = $('#event_id').val()
            let calendar_id = $('#calendar_id').val()
            let url_web = "/admin/events/"+event_id+"/calendars"

            if (calendar_id !== 0) {
                 url_web += '/'+ calendar_id;
            }
            
            var formData = new FormData($("#form_add_edit_calendar")[0]);

            $("#modal_create_edit_calendar").modal("hide")

            $.ajax({
                type: 'POST',
                url: url_web,
                processData: false,
                contentType: false,
                dataType: 'json',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: formData,

                success: function (data) {
                    // console.log(data)
                    if (data.new) { //Calendario Nuevo
                        $("#list_calendar").append(calendarItemTemplate(data.calendar));
                    } else { //Editar calendario

                        // Encontrar el tr clase calendar correspondiente, en base al data-calendar-id y su posicion en el listado
                        var $tr_calendar = document.querySelectorAll("[data-calendar-id='"+data.calendar.id+"']");
                        var position = $($tr_calendar).parent().children().index($($tr_calendar))
                        
                        $("#list_calendar tr:eq("+position+")").after(calendarItemTemplate(data.calendar))
                        $("#list_calendar tr:eq("+position+")").remove()
                        // console.log(position)
                    }

                },

                error: function (data) {
                    alert ("error al Crear - Editar calendario")
                }
            });

        });


        // ----------------------------------------------------
        // Eliminar / desvincular funcion - calendario
        // ----------------------------------------------------
        
        $("#list_calendars").on("click", "td .delete_calendar", function() {

            event.preventDefault();

            swal({
              title: "¡Atención!",
              text: "Se dispone a eliminar una función...",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {

                let $calendar = $(this).closest(".calendars")
                let $form = $(this).closest(".form_delete_calendar")
                let id_event = $($calendar).data("event-id")
                let id_calendar = $($calendar).data("calendar-id")

                // console.log(id_event, id_calendar)

                var formData = new FormData($($form)[0]);

                let url_web = "/admin/events/"+id_event+"/calendars/"+id_calendar

                $.ajax({
                    type: 'POST',
                    url: url_web,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: formData,

                    success: function (data) {

                        if (data.result) { //Calendario Nuevo

                            // Encontrar el tr clase calendar correspondiente, en base al data-calendar-id y su posicion en el listado
                            var $tr_calendar = document.querySelectorAll("[data-calendar-id='"+id_calendar+"']");
                            var position = $($tr_calendar).parent().children().index($($tr_calendar))
                            $("#list_calendar tr:eq("+position+")").remove()
                            // console.log(data.message)
                            swal(data.message);
                        } else { //Editar calendario
                            swal(data.message);
                        }

                    },

                    error: function (data) {
                        swal("Se produjo un error al intentar borrar la función");
                    }
                });
    
              } else {
                swal("La acción fue cancelada");
              }
            });

        });

    }); // END document.ready
</script>
@endsection
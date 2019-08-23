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
            <div class="alert alert-secondary mb-3 text-right" >
                <a href="{{ route('events.index') }}" class="btn btn-sm btn-primary ">
                Volver al listado
                </a>
            </div>
            <hr>
            @include('admin.layouts.partials.errors_messages')
            @include('admin.events.partials.edit_event')
            <hr />
            <div id="list_calendars" class="mt-2">
                @include('admin.events.partials.list_calendars')
            </div>           
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script src="{{ asset('libs/jquery-tagsinput/js/jquery.tagsinput-revisited.js') }}"></script>
<script>

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
        // $("#event_id").val(id_event)
        const url_api =base_url+'/api/events/'+id_event+'/calendars/'+id_calendar
        
        $.get(url_api, function(data){
            console.log(data)
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


        // ----------------------------------------------------
        // Edicion datos de evento
        // ----------------------------------------------------

        // Formatear slug a partir del title
        $("#title").stringToSlug({
            callback: function(text){
                $('#slug').val(text);
            }
        }); 

        // Mostrar listado de categorias / tags (agrupados en eventos)
        $('#tags_events').tagsInput({
            // autocomplete: The Autocomplete widgets provides suggestions while you type into the field
            // https://jqueryui.com/autocomplete/
            'autocomplete': {
                source : function (request, responseGetData) {
                    var term = request.term;
                    $.get(base_url+'/api/events/'+term, function(data){
                        responseGetData(data);
                    });
                }
            } 
        });


        // Mostrar manejo de tags (fuera de grupo eventos)
        $('#tags_no_events').tagsInput({
            'autocomplete': {
                source : function (request, responseGetData) {
                    var term = request.term;
                    $.get(base_url+'/api/tags/'+term, function(data){
                        responseGetData(data);
                    });
                }
            }
        });

        // Eliminar imagen de evento
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
                    swal("TO DO: Borrado de imagen de evento");
                } 
            });
        
        }); 


        // ----------------------------------------------------
        // ----------------------------------------------------
        // Calendarios-Funciones asociados a evento
        // ----------------------------------------------------
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

            emptyFormCalendar()
            $('#title_add_edit_calendar').html('Agregar Función')

            let $liCalendar = document.querySelectorAll('.start_date')
            // Copiar fecha de ultimo calendario agregado, si existe
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
        // Submit MODAL form NUEVO-EDITAR calendario-función
        // ----------------------------------------------------

        $("#form_add_edit_calendar").submit(function(event){

            event.preventDefault();

            let event_id = $('#event_id').val()
            let calendar_id = $('#calendar_id').val()
            let url_web = base_url+"/admin/events/"+event_id+"/calendars"

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

                    console.log(data)
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
                    alert ("error list_calendars")
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

                console.log(id_event, id_calendar)

                var formData = new FormData($($form)[0]);

                let url_web = base_url+"/admin/events/"+id_event+"/calendars/"+id_calendar

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
                        alert ("error delete_calendars")
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
<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['cors']], function () {

    # --------------------------------------------------------
	# Eventos
    # --------------------------------------------------------

    # Listado de eventos marco
    Route::get('frames', 'Api\EventController@indexFrames');

    # Detalle de evento marco
    Route::get('frames/{event_frame}', 'Api\EventController@showFrame');

    # Listado de Eventos dentro de un evento marco
    Route::get('frames/{event_frame}/events', 'Api\EventController@showEventsInFrame');


    # Listado de eventos (no marco)
    Route::get('events', 'Api\EventController@index');

    # Detalle de evento
    Route::get('events/{event}', 'Api\EventController@show');

    
    # Listado de eventos que comparten un tag
    Route::get('events/tags/{slug_tag}', 'Api\EventController@getEventsInTag');
    

    # Listado de eventos dentro de una categoria de organizacion - ubicacion
    Route::get('events/categories/{category_slug}', 'Api\EventController@getEventsInCategory');

    

    # --------------------------------------------------------
	# Servicios
    # --------------------------------------------------------
    
    # Listado de servicios dentro de una categoria de organización
    Route::get('services/categories/{category_slug}', 'Api\ServiceController@getServicesInCategory');

    # Listado total de servicios
    Route::get('services', 'Api\ServiceController@index');

    # Detalle de servicio
    Route::get('services/{service_slug}', 'Api\ServiceController@show');


    # --------------------------------------------------------
	# Categorias
	# --------------------------------------------------------

    # Listado de eventos dentro de una categoria de organizacion - ubicacion
    // Route::get('categories/{category_slug}/events', 'Api\EventController@getEventsInCategory');

    # Listado de servicis dentro de una categoria de organizacion
    // Route::get('categories/{category_slug}/services', 'Api\ServiceController@getServicesInCategory');

    # Listado total de categorias
    Route::get('categories', 'Api\CategoryController@index');

    # Detalle de servicio
    Route::get('categories/{category_slug}', 'Api\CategoryController@show');



    # --------------------------------------------------------
	# Tags
	# --------------------------------------------------------
    
    # Listado de eventos que comparten un tag
    // En events/tags/{slug_tag}
    // Route::get('tags/{slug_tag}/events', 'Api\EventController@getEventsInTag');


});


// Route::apiResource('categoriesAll', 'Api\CategoryController');
// Route::apiResource('organizationsAll', 'Api\OrganizationController');
// Route::apiResource('eventsAll', 'Api\EventController');

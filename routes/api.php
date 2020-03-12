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

    # Listado de Eventos dentro de un evento marco
    Route::get('events/frames/{event_frame}', 'Api\EventController@getEventsInFrame');
    
    # Listado de eventos que comparten un tag
    // Route::get('events/tags/{slug_tag}', 'Api\EventController@getTagEvents');
    Route::get('tags/{slug_tag}/events', 'Api\EventController@getEventsInTag');

    # Listado de eventos dentro de una categoria de organizacion - ubicacion
    Route::get('categories/{category_slug}/events', 'Api\EventController@getEventsInCategory');

    # Listado total de eventos
    Route::get('events', 'Api\EventController@index');
});


// Route::apiResource('categoriesAll', 'Api\CategoryController');
// Route::apiResource('organizationsAll', 'Api\OrganizationController');
// Route::apiResource('eventsAll', 'Api\EventController');

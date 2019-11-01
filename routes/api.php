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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// # Categorías :: detalle en base a un id
// Route::get('/categories/{id}', 'ApiController@getCategory')->where('id', '[0-9]+');

// # Categorías :: listado total o el base a termino de busqueda
// Route::get('/categories/{termino?}', 'ApiController@getCategories');


// # Direcciones :: detalle en base a un id
// Route::get('/addresses/{id}', 'ApiController@getAddress')->where('id', '[0-9]+');


// # Espacios :: detalle en base a un id
// Route::get('/spaces/{id}', 'ApiController@getSpace')->where('id', '[0-9]+');

// # Espacios :: listado total o el base a término de búsqueda
// Route::get('/spaces/{termino?}', 'ApiController@getSpaces');



// # Organizaciones :: detalle en base a un id
// Route::get('/organizations/{id}', 'ApiController@getOrganization')->where('id', '[0-9]+');

// # Organizaciones :: listado total o el base a termino de busqueda
// Route::get('/organizations/{termino?}', 'ApiController@getOrganizations');


// # Eventos :: detalle en base a un id
// Route::get('/events/{id}', 'ApiController@getEvent')->where('id', '[0-9]+');

// # Espacios :: listado total o el base a término de búsqueda
// Route::get('/events/{termino?}', 'ApiController@getEvents');

// # Eventos :: recuperar detalle de calendario
// Route::get('events/{event}/calendars/{calendar}','ApiController@getEventCalendar');



// # Tags :: Agrupados bajo "Servicios": listado total o el base a término de busqueda
// Route::get('tags/services/{termino?}', 'ApiController@getServicesTags');

// # Tags :: Listado de tags agrupados bajo categoria "Eventos"
// Route::get('tags/events/{termino?}', 'ApiController@getEventsTags');

// # Tags ::  Listado de tags NO AGRUPADOS bajo "EVENTOS"
// // Route::get('tags/no-events/{termino?}', 'ApiController@getNoEventsTags');




// //---------------------------------------------------------------


// # Organizaciones :: listado total o el base a termino de busqueda
// Route::get('organizations/{organization_id}/places/{place_id}', 'ApiController@getOrganizationPlace');

// Route::get('organizations/{organization_id}/addresses/{address_id}', 'ApiController@getAddressOrganization');


// # Lugares :: listado total o el base a termino de busqueda (y organizaciones)
// Route::get('/places/{termino?}', 'ApiController@getPlacesOrganizations');
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

Route::get('/spaces/{space_id}', 'ApiController@getSpace');

Route::get('/addresses/{address_id}', 'ApiController@getAddress');

# Organizaciones :: listado total o el base a termino de busqueda
Route::get('/organizations/{termino?}', 'ApiController@getOrganizations');

Route::get('organizations/{organization_id}/places/{place_id}', 'ApiController@getOrganizationPlace');

Route::get('organizations/{organization_id}/addresses/{address_id}', 'ApiController@getAddressOrganization');


# Lugares :: listado total o el base a termino de busqueda (y organizaciones)
Route::get('/places/{termino?}', 'ApiController@getPlacesOrganizations');



Route::get('services/{termino?}', 'ApiController@getServicesTags');

#Listado de tags agrupados en "Eventos"
Route::get('events/{termino}', 'ApiController@getEventsTags');

#Listado de tags no agrupados
Route::get('tags/', 'ApiController@getTags');
Route::get('tags/{termino}', 'ApiController@getTags');


# Eventos :: recuperar detalle de calendario
Route::get('events/{event}/calendars/{calendar}','ApiController@getEventCalendar');
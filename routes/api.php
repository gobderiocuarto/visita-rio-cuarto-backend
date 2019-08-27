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

Route::get('organizations/{organization_id}/spaces/{space_id}', 'ApiController@getOrganizationSpace');

Route::get('organizations/{organization_id}/addresses/{address_id}', 'ApiController@getAddressOrganization');

Route::get('services/', 'ApiController@getServicesTags');
Route::get('services/{termino}', 'ApiController@getServiceTags');

#Listado de tags agrupados en "Eventos"
Route::get('events/{termino}', 'ApiController@getEventsTags');

#Listado de tags no agrupados
Route::get('tags/', 'ApiController@getTags');
Route::get('tags/{termino}', 'ApiController@getTags');


# Eventos :: recuperar detalle de calendario
Route::get('events/{event}/calendars/{calendar}','ApiController@getEventCalendar');
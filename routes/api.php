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

Route::get('/places/{place_id}', 'ApiController@getPlace');

Route::get('/addresses/{address_id}', 'ApiController@getAddress');

Route::get('organizations/{organization_id}/places/{place_id}', 'ApiController@getOrganizationPlace');

Route::get('organizations/{organization_id}/addresses/{address_id}', 'ApiController@getAddressOrganization');

Route::get('services/{termino}', 'ApiController@getServiceTags');
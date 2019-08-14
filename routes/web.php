<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/search', 'HomeController@search')->name('search');


Auth::routes();

Route::get('/admin', function () {
    return redirect('admin/home');
})->name('admin');


Route::resource('admin/home','Admin\HomeController');

Route::resource('admin/categories','Admin\CategoryController');


# Organizaciones

# Organizaciones :: recursos

Route::resource('admin/organizations','Admin\OrganizationController');

Route::get('admin/organizations/{search}','Admin\OrganizationController@index');

Route::post('admin/organizations/{organization}/place','Admin\OrganizationController@storePlace');

Route::post('admin/organizations/{organization}/places/{place}','Admin\OrganizationController@destroyPlace');

Route::post('admin/organizations/{organization}/addresses/{address}','Admin\OrganizationController@destroyAddress');

# Espacios

Route::resource('admin/places','Admin\PlaceController');

# Servicios :: recursos
Route::resource('admin/services','Admin\ServiceController');

# Servicios :: Agregar organizaciones
Route::post('admin/services/{service}/organizations','Admin\ServiceController@storeOrganization');

# Servicios :: Desvincular organizaciones
Route::post('admin/services/{service}/organizations/{organization}','Admin\ServiceController@unlinkOrganization');

# Servicios :: Agregar espacios
Route::post('admin/services/{service}/places','Admin\ServiceController@storePlace');

# Servicios :: Desvincular espacio
Route::post('admin/services/{service}/places/{place}','Admin\ServiceController@unlinkPlace');


# Eventos 

# Eventos :: recursos
Route::resource('admin/events','Admin\EventController');

# Eventos :: crear / editar  calendar asociado a evento
Route::post('admin/events/{event}/calendars/{calendar?}','Admin\EventController@saveEventCalendar');

# Eventos :: borrar calendario asociado a evento
Route::delete('admin/events/{event}/calendars/{calendar}','Admin\EventController@destroyEventCalendar');
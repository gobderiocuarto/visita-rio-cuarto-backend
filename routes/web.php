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


# Home
Route::resource('admin/home','Admin\HomeController');

# Categorias
Route::resource('admin/categories','Admin\CategoryController');

# Espacios
Route::resource('admin/spaces','Admin\SpaceController');

# Organizaciones :: recursos
Route::resource('admin/organizations','Admin\OrganizationController');

Route::get('admin/organizations/{search}','Admin\OrganizationController@index');

Route::post('admin/organizations/{organization}/place','Admin\OrganizationController@storePlace');

# Organizaciones :: eliminar place
Route::post('admin/organizations/{organization}/place/{place_id}','Admin\OrganizationController@destroyPlace');

// Route::post('admin/organizations/{organization}/addresses/{address}','Admin\OrganizationController@destroyAddress');

# Places
Route::resource('admin/places','Admin\PlaceController');

# Servicios :: recursos
Route::resource('admin/services','Admin\ServiceController');

# Servicios :: Agregar organizaciones
Route::post('admin/services/{service}/organizations','Admin\ServiceController@storeOrganization');

# Servicios :: Desvincular organizaciones
Route::post('admin/services/{service}/organizations/{organization}','Admin\ServiceController@unlinkOrganization');

# Servicios :: Agregar espacios
Route::post('admin/services/{service}/spaces','Admin\ServiceController@storeSpace');

# Servicios :: Desvincular espacio
Route::post('admin/services/{service}/spaces/{space}','Admin\ServiceController@unlinkSpace');


# Eventos 
# Recursos
Route::get('admin/events','Admin\EventController@index')->name('events.index');

Route::get('admin/events/create','Admin\EventController@create')->name('events.create');

Route::post('admin/events/store','Admin\EventController@create')->name('events.store');

Route::get('admin/events/{edit_id}/edit','Admin\EventController@edit')->name('events.edit');

Route::post('admin/events/{edit_id}','Admin\EventController@update')->name('events.update');

Route::delete('admin/events/{edit_id}','Admin\EventController@destroy')->name('events.destroy');



# Eventos :: cargar - crear imagen asociada a evento
Route::post('admin/events/{event}/images/','Admin\EventController@loadImageEvent')
->name('events.loadImageEvent');

# Eventos :: borrar imagen asociada a evento
Route::delete('admin/events/{event}/images/delete','Admin\EventController@destroyImageEvent')
->name('events.destroyImageEvent');

# Eventos :: crear / editar  calendar asociado a evento
Route::post('admin/events/{event}/calendars/{calendar?}','Admin\EventController@saveEventCalendar')
	->name('events.saveEventCalendar');

# Eventos :: borrar calendario asociado a evento
Route::delete('admin/events/{event}/calendars/{calendar}','Admin\EventController@destroyEventCalendar')
	->name('events.destroyEventCalendar');

Route::middleware(['auth'])->group(function () {

	# Roles
	Route::get('admin/roles', 'Admin\RoleController@index')->name('roles.index')
	->middleware('permission:roles.index');

	Route::get('admin/roles/create', 'Admin\RoleController@create')->name('roles.create')
	->middleware('permission:roles.create');
	
	Route::post('admin/roles/store', 'Admin\RoleController@store')->name('roles.store')
	->middleware('permission:roles.create');

	Route::get('admin/roles/{role}', 'Admin\RoleController@show')->name('roles.show')
	->middleware('permission:roles.show');

	Route::get('admin/roles/{role}/edit', 'Admin\RoleController@edit')->name('roles.edit')
	->middleware('permission:roles.edit');

	Route::put('admin/roles/{role}', 'Admin\RoleController@update')->name('roles.update')->middleware('permission:roles.edit');

	Route::delete('admin/roles/{role}', 'Admin\RoleController@destroy')->name('roles.destroy')->middleware('permission:roles.destroy');


	# Users
	Route::get('admin/users', 'Admin\UserController@index')->name('users.index')->middleware('permission:users.index');

	Route::get('admin/users/{user}', 'Admin\UserController@show')->name('users.show')->middleware('permission:users.show');

	Route::get('admin/users/{user}/edit', 'Admin\UserController@edit')->name('users.edit')->middleware('permission:users.edit');
	
	Route::put('admin/users/{user}', 'Admin\UserController@update')->name('users.update')->middleware('permission:users.edit');

	Route::delete('admin/users/{user}', 'Admin\UserController@destroy')->name('users.destroy')->middleware('permission:users.destroy');


});
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


Route::middleware(['auth'])->group(function () {

	# Home
	Route::resource('admin/home','Admin\HomeController');


	# --------------------------------------------------------
	# Categorias
	# --------------------------------------------------------

	# Listar 
	Route::get('admin/categories','Admin\CategoryController@index')->name('categories.index')
	->middleware('permission:categories.index');

	# Crear 
	Route::get('admin/categories/create','Admin\CategoryController@create')->name('categories.create')
	->middleware('permission:categories.create');

	# Store 
	Route::post('admin/categories','Admin\CategoryController@store')->name('categories.store')
	->middleware('permission:categories.create');

	# Edit 
	Route::get('admin/categories/{category}/edit','Admin\CategoryController@edit')->name('categories.edit')
	->middleware('permission:categories.edit');

	# Update 
	Route::patch('admin/categories/{category}','Admin\CategoryController@update')->name('categories.update')
	->middleware('permission:categories.edit');

	Route::delete('admin/categories/{category}','Admin\CategoryController@destroy')->name('categories.destroy')
	->middleware('permission:categories.destroy');

	# --------------------------------------------------------
	# END Categorias
	# --------------------------------------------------------


	# --------------------------------------------------------
	# Eventos
	# --------------------------------------------------------

	# Listar
	Route::get('admin/events','Admin\EventController@index')->name('events.index')
	->middleware('permission:events.index');
	
	# Crear
	Route::get('admin/events/create','Admin\EventController@create')->name('events.create')
	->middleware('permission:events.create');
	
	# Store
	Route::post('admin/events','Admin\EventController@store')->name('events.store')
	->middleware('permission:events.create');
	
	# Edit
	Route::get('admin/events/{edit_id}/edit','Admin\EventController@edit')->name('events.edit')
	->middleware('permission:events.edit');
	
	# Update
	Route::patch('admin/events/{edit_id}','Admin\EventController@update')->name('events.update')
	->middleware('permission:events.edit');
	
	# Update
	Route::delete('admin/events/{edit_id}','Admin\EventController@destroy')->name('events.destroy')
	->middleware('permission:events.destroy');

	# Cargar imagen asociada a evento
	Route::post('admin/events/{event}/images/','Admin\EventController@loadImageEvent')->name('events.loadImageEvent')
	->middleware('permission:events.loadImageEvent');

	# Borrar imagen asociada a evento
	Route::delete('admin/events/{event}/images/delete','Admin\EventController@destroyImageEvent')->name('events.destroyImageEvent')
	->middleware('permission:events.destroyImageEvent');

	# Crear / editar calendar asociado a evento
	Route::post('admin/events/{event}/calendars/{calendar?}','Admin\EventController@saveEventCalendar')->name('events.saveEventCalendar')
	->middleware('permission:events.saveEventCalendar');

	# Borrar calendario asociado a evento
	Route::delete('admin/events/{event}/calendars/{calendar}','Admin\EventController@destroyEventCalendar')->name('events.destroyEventCalendar')
	->middleware('permission:events.destroyEventCalendar');
	
	# --------------------------------------------------------
	# END Eventos
	# --------------------------------------------------------



	# --------------------------------------------------------
	# Organizaciones
	# --------------------------------------------------------
	
	# Organizaciones :: recursos
	Route::resource('admin/organizations','Admin\OrganizationController');
	
	Route::get('admin/organizations/{search}','Admin\OrganizationController@index');
	
	# Organizaciones :: Crear / editar una ubicación
	Route::post('admin/organizations/{organization}/place','Admin\OrganizationController@storePlace');
	
	# Organizaciones :: eliminar una ubicación
	Route::post('admin/organizations/{organization}/place/{place_id}','Admin\OrganizationController@destroyPlace');
	
	# --------------------------------------------------------
	# END Organizaciones
	# --------------------------------------------------------

	
	
	
	# Espacios
	Route::resource('admin/spaces','Admin\SpaceController');


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
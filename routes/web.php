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

Route::get('/eventos', 'EventController@index'); // Listado total y busqueda
Route::get('/eventos/categorias/{slug}', 'EventController@getCategories');
Route::get('/eventos/cuando/{when}','EventController@getWhen');
Route::get('/eventos/marco/{id}','EventController@getFrame');
Route::get('/eventos/{id}/{slug}', 'EventController@show');



// Route::get('/search', 'HomeController@search')->name('search');


Auth::routes();

Route::get('/admin', function () {
    return redirect('admin/home');
})->name('admin');


Route::middleware(['auth'])->group(function () {

	# Home
	Route::resource('admin/home','Admin\HomeController');

	# --------------------------------------------------------
	# Roles
	# --------------------------------------------------------

	# Listar
	Route::get('admin/roles', 'Admin\RoleController@index')->name('roles.index')
	->middleware('permission:roles.index');

	# Crear
	Route::get('admin/roles/create', 'Admin\RoleController@create')->name('roles.create')
	->middleware('permission:roles.create');
	
	# Almacenar
	Route::post('admin/roles/store', 'Admin\RoleController@store')->name('roles.store')
	->middleware('permission:roles.create');

	# Ver
	Route::get('admin/roles/{role}', 'Admin\RoleController@show')->name('roles.show')
	->middleware('permission:roles.show');

	# Editar
	Route::get('admin/roles/{role}/edit', 'Admin\RoleController@edit')->name('roles.edit')
	->middleware('permission:roles.edit');

	# Actualizar
	Route::patch('admin/roles/{role}', 'Admin\RoleController@update')->name('roles.update')->middleware('permission:roles.edit');

	# Borrar
	Route::delete('admin/roles/{role}', 'Admin\RoleController@destroy')->name('roles.destroy')->middleware('permission:roles.destroy');

	# --------------------------------------------------------
	# END Roles
	# --------------------------------------------------------




	# --------------------------------------------------------
	# Users
	# --------------------------------------------------------

	# Listar
	Route::get('admin/users', 'Admin\UserController@index')->name('users.index')->middleware('permission:users.index');

	# Ver
	Route::get('admin/users/{user}', 'Admin\UserController@show')->name('users.show')->middleware('permission:users.show');

	# Editar
	Route::get('admin/users/{user}/edit', 'Admin\UserController@edit')->name('users.edit')->middleware('permission:users.edit');
	
	# Actualizar
	Route::patch('admin/users/{user}', 'Admin\UserController@update')->name('users.update')->middleware('permission:users.edit');

	# Borrar
	Route::delete('admin/users/{user}', 'Admin\UserController@destroy')->name('users.destroy')->middleware('permission:users.destroy');


	# --------------------------------------------------------
	# END Users
	# --------------------------------------------------------



	# --------------------------------------------------------
	# Spaces
	# --------------------------------------------------------

	# Listar
	Route::get('admin/spaces', 'Admin\SpaceController@index')->name('spaces.index')
	->middleware('permission:spaces.index');

	# Crear
	Route::get('admin/spaces/create', 'Admin\SpaceController@create')->name('spaces.create')
	->middleware('permission:spaces.create');

	# Store
	Route::post('admin/spaces', 'Admin\SpaceController@store')->name('spaces.store')
	->middleware('permission:spaces.create');


	# Ver
	Route::get('admin/spaces/{space}', 'Admin\SpaceController@show')->name('spaces.show')
	->middleware('permission:spaces.show');

	# Editar
	Route::get('admin/spaces/{space}/edit', 'Admin\SpaceController@edit')->name('spaces.edit')
	->middleware('permission:spaces.edit');
	
	# Actualizar
	Route::patch('admin/spaces/{space}', 'Admin\SpaceController@update')->name('spaces.update')
	->middleware('permission:spaces.edit');

	# Borrar
	Route::delete('admin/spaces/{space}', 'Admin\SpaceController@destroy')->name('spaces.destroy')
	->middleware('permission:spaces.destroy');


	# --------------------------------------------------------
	# END Spaces
	# --------------------------------------------------------



	# --------------------------------------------------------
	# Categories
	# --------------------------------------------------------

	# Listar 
	Route::get('admin/categories','Admin\CategoryController@index')->name('categories.index')
	->middleware('permission:categories.index');

	# Crear 
	Route::get('admin/categories/create','Admin\CategoryController@create')->name('categories.create')
	->middleware('permission:categories.create');

	# Almacenar 
	Route::post('admin/categories','Admin\CategoryController@store')->name('categories.store')
	->middleware('permission:categories.create');

	# Editar 
	Route::get('admin/categories/{category}/edit','Admin\CategoryController@edit')->name('categories.edit')
	->middleware('permission:categories.edit');

	# Actualizar 
	Route::patch('admin/categories/{category}','Admin\CategoryController@update')->name('categories.update')
	->middleware('permission:categories.edit');

	# Borrar 
	Route::delete('admin/categories/{category}','Admin\CategoryController@destroy')->name('categories.destroy')
	->middleware('permission:categories.destroy');

	# --------------------------------------------------------
	# END Categorias
	# --------------------------------------------------------


	# --------------------------------------------------------
	# Etiquetas
	# --------------------------------------------------------

	# Listar 
	Route::get('admin/services','Admin\ServiceController@index')->name('services.index')
	->middleware('permission:services.index');

	# Crear 
	Route::get('admin/services/create','Admin\ServiceController@create')->name('services.create')
	->middleware('permission:services.create');

	# Almacenar 
	Route::post('admin/services','Admin\ServiceController@store')->name('services.store')
	->middleware('permission:services.create');

	# Editar 
	Route::get('admin/services/{service}/edit','Admin\ServiceController@edit')->name('services.edit')
	->middleware('permission:services.edit');

	# Actualizar 
	Route::patch('admin/services/{service}','Admin\ServiceController@update')->name('services.update')
	->middleware('permission:services.edit');

	# Borrar 
	Route::delete('admin/services/{service}','Admin\ServiceController@destroy')->name('services.destroy')
	->middleware('permission:services.destroy');


	# Vincular organizaciones
	Route::post('admin/services/{service}/organizations','Admin\ServiceController@storeOrganization')->name('services.storeOrganization')
	->middleware('permission:services.storeOrganization');

	# Desvincular organizaciones
	Route::post('admin/services/{service}/organizations/{organization}','Admin\ServiceController@unlinkOrganization')->name('services.unlinkOrganization')
	->middleware('permission:services.unlinkOrganization');

	# Vincular espacios
	Route::post('admin/services/{service}/spaces','Admin\ServiceController@storeSpace')->name('services.storeSpace')
	->middleware('permission:services.storeSpace');

	# Desvincular espacio
	Route::post('admin/services/{service}/spaces/{space}','Admin\ServiceController@unlinkSpace')->name('services.unlinkSpace')
	->middleware('permission:services.unlinkSpace');


	# --------------------------------------------------------
	# END Etiquetas
	# --------------------------------------------------------


	# --------------------------------------------------------
	# Organizaciones
	# --------------------------------------------------------
	
	# Crear 
	Route::get('admin/organizations/create','Admin\OrganizationController@create')->name('organizations.create')
	->middleware('permission:organizations.create');

	# Listar y búsqueda
	Route::get('admin/organizations/{search?}','Admin\OrganizationController@index')->name('organizations.index')
	->middleware('permission:organizations.index');

	# Buscar 
	// Route::get('admin/organizations/{search?}','Admin\OrganizationController@index')->name('organizations.update');

	# Almacenar 
	Route::post('admin/organizations','Admin\OrganizationController@store')->name('organizations.store')
	->middleware('permission:organizations.create');

	# Editar 
	Route::get('admin/organizations/{organization}/edit','Admin\OrganizationController@edit')->name('organizations.edit')
	->middleware('permission:organizations.edit');

	# Actualizar 
	Route::patch('admin/organizations/{organization}','Admin\OrganizationController@update')->name('organizations.update')
	->middleware('permission:organizations.edit');

	# Borrar 
	Route::delete('admin/organizations/{organization}','Admin\OrganizationController@destroy')->name('organizations.destroy')
	->middleware('permission:organizations.destroy');
	
	
	# Organizaciones :: Crear / editar una ubicación
	Route::post('admin/organizations/{organization}/place','Admin\OrganizationController@storePlace')->name('organizations.storePlace')
	->middleware('permission:organizations.storePlace');
	
	# Organizaciones :: eliminar una ubicación
	Route::post('admin/organizations/{organization}/place/{place_id}','Admin\OrganizationController@destroyPlace')->name('organizations.destroyPlace')
	->middleware('permission:organizations.destroyPlace');
	
	# --------------------------------------------------------
	# END Organizaciones
	# --------------------------------------------------------




	# --------------------------------------------------------
	# Events
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
	
	# Show
	Route::get('admin/events/{event}','Admin\EventController@show')->name('events.show');

	# Edit
	Route::get('admin/events/{event}/edit','Admin\EventController@edit')->name('events.edit')
	->middleware(['permission:events.edit', 'event.owner']);
	
	# Update
	Route::patch('admin/events/{event}','Admin\EventController@update')->name('events.update')
	->middleware(['permission:events.edit', 'event.owner']);
	
	# Delete
	Route::delete('admin/events/{event}','Admin\EventController@destroy')->name('events.destroy')
	->middleware(['permission:events.destroy', 'event.owner']);

	# Asociar evento
	Route::patch('admin/events/{event}/asociate/','Admin\EventController@asociate')->name('events.asociate');

	# Cargar imagen asociada a evento
	Route::post('admin/events/{event}/images/','Admin\EventController@loadImageEvent')->name('events.loadImageEvent')
	->middleware(['permission:events.loadImageEvent', 'event.owner']);

	# Borrar imagen asociada a evento
	Route::delete('admin/events/{event}/images/delete','Admin\EventController@destroyImageEvent')->name('events.destroyImageEvent')
	->middleware(['permission:events.destroyImageEvent', 'event.owner']);


	# Crear / editar calendar asociado a evento
	Route::post('admin/events/{event}/calendars/{calendar?}','Admin\EventController@saveEventCalendar')->name('events.saveEventCalendar')
	->middleware(['permission:events.saveEventCalendar', 'event.owner']);

	# Borrar calendario asociado a evento
	Route::delete('admin/events/{event}/calendars/{calendar}','Admin\EventController@destroyEventCalendar')->name('events.destroyEventCalendar')
	->middleware(['permission:events.destroyEventCalendar', 'event.owner']);

	
	# --------------------------------------------------------
	# END Events
	# --------------------------------------------------------


	# Places
	//Route::resource('admin/places','Admin\PlaceController');


});


# Eventos :: recuperar detalle de calendario asociado
Route::get('admin/events/{event}/calendars/{calendar}','Admin\EventController@getEventCalendar');

# Organizaciones :: Listado separado por lugares - ubicaciones
Route::get('admin/organizations/{organization}/places/{id}', 'Admin\OrganizationController@OrganizationPlace')->where('organization', '[0-9]+');

# Organizaciones :: Listado separado por lugares - ubicaciones
Route::get('admin/organizations/places/{termino?}', 'Admin\OrganizationController@OrganizationsPlaces');


# Espacios :: detalle en base a un id
Route::get('admin/organizations/spaces/{id}', 'Admin\OrganizationController@OrganizationSpace')->where('id', '[0-9]+');



# Tags :: Agrupados bajo "Servicios": listado total o el base a término de busqueda
Route::get('admin/tags/services/{termino?}', 'Admin\TagController@getTagsServices');

# Tags :: Listado de tags agrupados bajo categoria "Eventos"
Route::get('admin/tags/events/{termino?}', 'ApiController@getTagsEvents');

# Tags ::  Listado de tags NO AGRUPADOS bajo "EVENTOS"
// Route::get('admin/tags/no-events/{termino?}', 'ApiController@getTagsNoEvents');

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

Route::apiResource('categoriesAll', 'Api\CategoryController');
Route::apiResource('organizationsAll', 'Api\OrganizationController');
Route::apiResource('eventsAll', 'Api\EventController');


//
// Api Categories
//
use App\Category;
use App\Http\Resources\Category as CategoryResource;
Route::get('/categories/{id}', function ($id) {
    return new CategoryResource(Category::find($id) ?? abort(404));
});
Route::get('/categories', function () {
  return CategoryResource::collection(Category::paginate());
});


//
// Api Organizations
//
use App\Organization;
use App\Http\Resources\Organization as OrganizationResource;
Route::get('/organizations/{id}', function ($id) {
    return new OrganizationResource(Organization::find($id) ?? abort(404));
});
Route::get('/organizations', function () {
  return OrganizationResource::collection(Organization::paginate());
});


//
// Api Events
//
use App\Event;
use App\Http\Resources\Event as EventResource;
Route::get('/events/{id}', function ($id) {
    return new EventResource(Event::find($id) ?? abort(404));
});
Route::get('/events', function () {
  return EventResource::collection(Event::paginate());
});

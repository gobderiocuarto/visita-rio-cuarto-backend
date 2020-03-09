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

Route::resource('events', 'Api\EventController')->only(['index', 'show' ]);

// Route::apiResource('categoriesAll', 'Api\CategoryController');
// Route::apiResource('organizationsAll', 'Api\OrganizationController');
// Route::apiResource('eventsAll', 'Api\EventController');

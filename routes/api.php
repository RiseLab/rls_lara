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

Route::post('/register', 'API\AuthController@register');
Route::post('/login', 'API\AuthController@login');

Route::middleware('auth:api')->group(function () {
	Route::post('/logout', 'API\AuthController@logout');
	Route::get('/user', 'API\AuthController@getUser');

	Route::group(['prefix' => '/v1/categories'], function () {
		Route::get('/', 'CategoryController@index');
		Route::post('/', 'CategoryController@store');
		Route::get('/{id}', 'CategoryController@show');
		Route::put('/{id}', 'CategoryController@update');
		Route::delete('/{id}', 'CategoryController@destroy');
	});
});

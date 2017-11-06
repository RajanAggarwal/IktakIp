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

Route::group(['namespace'=>'api'], function(){

	Route::get('employees', 'EmployeesController@index')->name('api.employees.index');
	Route::get('employees/{id}', 'EmployeesController@show')->name('api.employees.show');
	Route::get('user-info', 'HomeController@index')->name('api.user_info');
	Route::post('employees/{id}/current-locations', 'EmployeeCurrentLocationController@store')->name('api.employees_current_locations.store');
});
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
/*
Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();

Route::group(['middleware'=>'VerifyEmployer'], function(){
	
	Route::get('/' , 'HomeController@index');
	Route::get('/home', 'HomeController@index')->name('home');

	Route::get('/user/activation/{token}', 'Auth\RegisterController@activateUser')->name('user.activate');

	Route::get('/locations', 'LocationController@index');
	Route::get('/locations/{id}', 'LocationController@index');

	Route::any('/save_location', 'LocationController@save_location');
	Route::any('/save_location/{id}', 'LocationController@save_location');

	Route::any('/list_locations', 'LocationController@list_locations');
	Route::delete('/delete_locations/{id}', 'LocationController@delete_locations');

	Route::any('/list_employees/{id}', 'UserController@viewAllEmployeesOfEmployerToEployer'); 
	Route::any('/add_employee/{id}', 'UserController@add_employee_for_employer');
	Route::any('/edit_employee/{id}', 'UserController@edit_employee_for_employer');
	Route::any('/view_employee/{id}', 'UserController@view_employee_for_employer');

	Route::any('/update_employee_status_for_employer/{id}', 'UserController@update_employee_status_for_employer');
	Route::any('/delete_employee_for_employer/{id}', 'UserController@delete_employee_for_employer');

});

Route::any('userlogout', 'HomeController@userlogout');


/*Admin Routes Placed Here*/
Route::any('admin/get_employees_for_employer', 'UserController@get_employees_for_employer');

Route::get('/admin','AdminController@index');
Route::any('/admin/login','AdminController@index');
Route::any('/admin/dashboard','DashboardController@index');

Route::any('/admin/employers','UserController@index');
Route::any('admin/employees/{id}', 'UserController@viewAllEmployeesOfEmployer');

Route::get('admin/update_employer_status/{id}', 'UserController@update_employer_status');
Route::get('admin/update_employee_status/{id}', 'UserController@update_employee_status');

Route::get('admin/delete_employee/{id}', 'UserController@delete_employee');
Route::get('admin/delete_locations/{id}', 'UserController@delete_locations');

Route::any('admin/edit_employer/{id}', 'UserController@edit_employer');
Route::any('admin/edit_employee/{id}', 'UserController@edit_employee');

Route::any('admin/add_employer', 'UserController@add_employer');
Route::any('admin/add_employee/{id}', 'UserController@add_employee');
Route::any('admin/view_employee/{id}', 'UserController@view_employee');

Route::any('admin/get_employees', 'UserController@get_employees');
Route::any('admin/get_employers', 'UserController@get_employers');
Route::any('admin/get_locations', 'UserController@get_locations');

Route::get('admin/logout', 'AdminController@logout');
Route::get('admin/employer_login/{id}', 'UserController@loginAsEmployer');

Route::any('admin/delete_employer/{id}', 'UserController@delete_employer');
/*Admin Routes End Here*/



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
Route::get('/' , 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');


/*Admin Routes Placed Here*/
Route::get('/admin','AdminController@index');
Route::any('/admin/login','AdminController@index');
Route::any('/admin/dashboard','DashboardController@index');

Route::any('/admin/employers','UserController@index');
Route::any('admin/employees/{id}', 'UserController@viewAllEmployeesOfEmployer');

Route::get('admin/update_employer_status/{id}', 'UserController@update_employer_status');
Route::get('admin/update_employee_status/{id}', 'UserController@update_employee_status');

Route::get('admin/delete_employee/{id}', 'UserController@delete_employee');
Route::get('admin/delete_employer/{id}', 'UserController@delete_employer');

Route::any('admin/edit_employer/{id}', 'UserController@edit_employer');
Route::any('admin/edit_employee/{id}', 'UserController@edit_employee');

Route::any('admin/add_employer', 'UserController@add_employer');
Route::any('admin/add_employee/{id}', 'UserController@add_employee');
Route::any('admin/view_employee/{id}', 'UserController@view_employee');

Route::any('admin/get_employees', 'UserController@get_employees');
Route::any('admin/get_employers', 'UserController@get_employers');

Route::get('admin/logout', 'AdminController@logout');
Route::get('admin/employer_login/{id}', 'UserController@loginAsEmployer');

/*Admin Routes End Here*/
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/user/activation/{token}', 'Auth\RegisterController@activateUser')->name('user.activate');
Route::get('/user/activation/{token}', 'Auth\RegisterController@activateUser')->name('user.activate');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/user/activation/{token}', 'Auth\RegisterController@activateUser')->name('user.activate');


Route::any('userlogout', 'HomeController@userlogout');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/user/activation/{token}', 'Auth\RegisterController@activateUser')->name('user.activate');

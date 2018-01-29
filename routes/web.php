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
});52.58.120.50*/

Auth::routes();

Route::get('test', 'TestController@index');

Route::group(['middleware'=>'VerifyEmployer'], function(){

	Route::group(['namespace'=>'frontend'], function(){

		// Dashboard
		Route::get('/home', 'HomeController@index')->name('home');
		Route::get('/' , 'HomeController@index')->name('home');

		// Locations
		Route::get('locations', 'LocationsController@index')->name('locations.index');
		Route::get('locations/add', 'LocationsController@add')->name('locations.add');
		Route::post('locations', 'LocationsController@store')->name('locations.store');
		Route::get('locations/{id}/edit', 'LocationsController@edit')->name('locations.edit');
		Route::patch('locations/{id}', 'LocationsController@update')->name('locations.update');
		Route::delete('locations/{id}', 'LocationsController@destroy')->name('locations.destroy');

		// Employees
		Route::get('employees', 'EmployeesController@index')->name('employees.index');
		Route::get('employees/add', 'EmployeesController@add')->name('employees.add');
		Route::post('employees', 'EmployeesController@store')->name('employees.store');
		Route::get('employees/{id}/edit', 'EmployeesController@edit')->name('employees.edit');
		Route::patch('employees/{id}', 'EmployeesController@update')->name('employees.update');
		Route::delete('employees/{id}', 'EmployeesController@destroy')->name('employees.destroy');
		Route::get('employees/{id}/update-status', 'EmployeesController@updateStatus')->name('employees.update_status');
		Route::get('reports', 'EmployeesController@ajax_reports')->name('ajax.employees.reports');
		Route::get('employees/current-locations', 'EmployeesController@ajax_currentLocations')->name('ajax.employees.current_locations');
		Route::get('employees/working-hours', 'EmployeesController@ajax_workingHours')->name('ajax.employees.working_hours');
	
	});

	Route::get('/user/activation/{token}', 'Auth\RegisterController@activateUser')->name('user.activate');


	/* Descarded Routes */
	/* //////////////////////////////////////// */
	// Route::get('/locations/{id}', 'LocationController@index');
	// Route::any('/list_locations', 'LocationController@list_locations');
	// Route::delete('/delete_locations/{id}', 'LocationController@delete_locations');

	// Route::any('/save_location', 'LocationController@save_location');
	// Route::any('/save_location/{id}', 'LocationController@save_location');

	// Route::any('/list_employees/{id}', 'UserController@viewAllEmployeesOfEmployerToEployer'); 
	// Route::any('/add_employee/{id}', 'UserController@add_employee_for_employer');

	// Route::any('/edit_employee/{id}', 'UserController@edit_employee_for_employer');
	// Route::any('/view_employee/{id}', 'UserController@view_employee_for_employer');

	// Route::any('/update_employee_status_for_employer/{id}', 'UserController@update_employee_status_for_employer');
	// Route::any('/delete_employee_for_employer/{id}', 'UserController@delete_employee_for_employer');

});

Route::any('userlogout', 'frontend\HomeController@userlogout');



/*Admin Routes Placed Here*/

Route::group(['prefix'=>'admin'], function(){
	
	Route::any('get_employees_for_employer', 'frontend\EmployeesController@ajax_getEmployees');

	Route::get('/','AdminController@index')->name('admin.home');
	Route::any('login','AdminController@index');
	Route::any('dashboard','DashboardController@index');

	Route::any('employers','UserController@index');
	Route::any('all-employers','UserController@showallEmployees');
	Route::any('employees/{id}', 'UserController@viewAllEmployeesOfEmployer');

	Route::get('update_employer_status/{id}', 'UserController@update_employer_status');
	Route::get('update_employee_status/{id}', 'UserController@update_employee_status');

	Route::get('delete_employee/{id}', 'UserController@delete_employee');
	Route::get('delete_locations/{id}', 'UserController@delete_locations');

	Route::any('edit_employer/{id}', 'UserController@edit_employer');
	Route::any('edit_employee/{id}', 'UserController@edit_employee');

	Route::any('add_employer', 'UserController@add_employer');
	Route::any('add_employee/{id}', 'UserController@add_employee');
	Route::any('view_employee/{id}', 'UserController@view_employee');

	Route::any('get_employees', 'UserController@get_employees');
	Route::any('get_employers', 'UserController@get_employers');
	Route::any('get_locations', 'UserController@get_locations');

	Route::get('logout', 'AdminController@logout');
	Route::get('employer_login/{id}', 'UserController@loginAsEmployer');

	Route::any('delete_employer/{id}', 'UserController@delete_employer');
});
/*Admin Routes End Here*/



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

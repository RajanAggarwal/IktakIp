<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Employee;

/**
* 
*/
class EmployeesController extends Controller
{
	public function index()
	{
		$response = [
			'success' => null
		];

		$employees = Employee::get();
		$response['success'] = 1;
		$response['message'] = 'Success!';
		$response['data']['employees']    = $employees;
		echo json_encode( $response );
	}

	public function show(Request $request, $id=null)
	{
		$response = [
			'success' => null
		];

		$employee = Employee::find( $id );
		$response['success'] = 1;
		$response['message'] = 'Success!';
		$response['data']['employee'] = $employee;
		$response['data']['employee']['current_location'] = $employee->current_location;
		echo json_encode( $response );
	}
}

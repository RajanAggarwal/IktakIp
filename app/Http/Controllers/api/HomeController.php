<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee;
use App\User;

/**
* 
*/
class HomeController extends Controller
{
	public function index(Request $request)
	{
		$response = [
			'success' => 0,
			'message' => ''
		];

		// validating input data
		if( !$request->phone )
		{
			$response['message'] = 'phone field is required.';
			echo json_encode( $response );
		}

		$employee = Employee::where('mobile_number', $request->phone)->first();
		if( $employee )
		{
			$response['success'] = 1;
			$response['message'] = 'Login Successfull';
			$employee->employer = $employee->employer;
		}
		else
		{
			$employer = User::where('email', 'test@abc.com')->first();
			$employee = new Employee;
			$employee->mobile_number = $request->phone;
			$employee->employer_id   = $employer->id;
			$employee->save();
			$employee->employer = $employee->employer;
		}

		$response['data']['employee'] = $employee;

		echo json_encode( $response );
	}
}

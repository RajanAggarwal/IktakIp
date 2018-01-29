<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee;
use App\User;
use DB;

/**
* 
*/
class HomeController extends Controller
{
	public function index(Request $request)
	{
		$response = [
			'success' => 1,
			'message' => 'Login Successfull'
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
	
	/***Make Employee Logout**/
	public function empLogout(Request $request){
		$empId = isset($request['user_id']) && !empty($request['user_id'])?$request['user_id']:'';
		
		if( !$empId )
		{
			$response['status'] =0;
			$response['message'] = 'Emp id required.';
			 
		}else{
			$response['status'] =1;
			$response['message'] = 'logout successfully.';
			
		}
		//$report = DB::table("reports")->where("employee_id",$empId)->where("status","0")->count();
		//if($report>0){
			
		//$data = array("status"=>1,"updated_at"=>date("Y-m-d H:i:s"));
		//DB::table("reports")->where("employee_id",$empId)->where("status","0")->update($data);
		//}
		
		echo json_encode($response);
		die;
	}
}

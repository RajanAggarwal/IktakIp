<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\EmployeeCurrentLocation;

/**
* 
*/
class EmployeeCurrentLocationController extends Controller
{
	public function store(Request $request)
	{
		$response = [
			'success' => 0,
			'message' => ''
		];

		if( !$request->employee_id )
		{
			$response['message'] = "employee_id field is required.";
			return json_encode( $response );
		}

		if( !$request->lat )
		{
			$response['message'] = "latitude field is required.";
			return json_encode( $response );
		}

		if( !$request->lng )
		{
			$response['message'] = "longitude field is required.";
			return json_encode( $response );
		}

		if( !$request->time )
		{
			$response['message'] = "time field is required.";
			return json_encode( $response );
		}

		$record = new EmployeeCurrentLocation;
		$record->employee_id = $request->employee_id;
		$record->lat         = $request->lat;
		$record->lng         = $request->lng;
		$record->time        = $request->time;

		if( $record->save() )
		{
			$response['success'] = 1;
			$response['message'] = 'Success!';
		}
		else
		{
			$response['message'] = 'Data could not be saved due to some error. Please try again later.';
		}

		return json_encode( $response );
	}
}


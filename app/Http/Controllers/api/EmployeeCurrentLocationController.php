<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\EmployeeCurrentLocation;

use DB;

use App\Employee;
use App\Report;

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

		$employee = Employee::find($request->employee_id);
		if( !$employee )
		{
			$response['message'] = "Employee Not Found.";
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
		$record->created_at  = $request->time;

		if( $record->save() )
		{
			$response['success'] = 1;
			$response['message'] = 'Success!';

			$employer = $employee->employer;
			if( $employer )
			{
				$employer_locations = $employer->locations;
				if( $employer_locations )
				{
					$latFrom = $request->lat;
					$lngFrom = $request->lng;

					$date = date( 'Y-m-d', strtotime($request->time) );
					$employee_id = $employee->id;
					$query = "SELECT * FROM reports WHERE employee_id = $employee_id AND created_at::text LIKE '$date%' LIMIT 1";
					$report = DB::select( $query );
					
					foreach( $employer_locations as $employer_location )
					{
						$latTo = $employer_location->latitude;
						$lngTo = $employer_location->longitude;
						$distance = $this->haversineGreatCircleDistance( $latFrom, $lngFrom, $latTo, $lngTo );
						if( $distance <= 100 )
						{
							if( $report )
							{
								$report = Report::find($report[0]->id);
								$report->clock_out_time = $request->time;
								$report->clock_out_location = $employer_location->location_name;
							}
							else
							{
								$report = new Report;
								$report->employee_id = $employee->id;
								$report->clock_in_time = $request->time;
								$report->clock_in_location = $employer_location->location_name;
								$report->created_at = $request->time;
							}

							$report->updated_at = $request->time;
							$report->save();

							break;
						}
					}
				}
			}
		}
		else
		{
			$response['message'] = 'Data could not be saved due to some error. Please try again later.';
		}

		return json_encode( $response );
	}
}


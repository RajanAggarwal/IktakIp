<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\EmployeeCurrentLocation;
use App\Employee;
use App\User;

/**
* 
*/
class TestController extends Controller
{
	public function index()
	{
		$employee = Employee::find(18);
		dd( $employee->employer );

		/*$record = new EmployeeCurrentLocation;
		$record->employee_id = 18;
		$record->lat = 30.7097667;
		$record->lng = 76.6955597;
		$record->time = '2017-11-01 12:33:00';*/

		$record = new User;
		$record->name     = "Test Company";
		$record->email    = "test@abc.com";
		$record->password = '$2y$10$aHWryV9MUsoR5LQ4XkeDfu9VlpLRxQ3mJlbM4hE6ayvhLPtpDQJfO';
		if( $record->save() )
		{
			echo "Saved";
		}
		else
		{
			echo "Not Saved";	
		}
	}
}

<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Validator;
use Hash;
use Auth;

use App\Employee;
use App\EmployeeCurrentLocation;

/**
* 
*/
class EmployeesController extends Controller
{
	public function index()
	{
		return view('frontend.employees.index');
	}


	public function add()
	{
		return view('frontend.employees.add');
	}


	public function store(Request $request)
	{
		// Start - Validations
		$rules = [
			'name'             => 'required',
			'surname'          => 'required',
			'mobile_number'    => 'required',
			'email'            => 'required|email',
			'password'         => 'required',
			'confirm_password' => 'same:password'
		];

		$validator = Validator::make( $request->all(), $rules );

		// Check for email exists or not
		$GLOBALS['email'] = $request->email;
		$validator->after(function ($validator) {
			$result = Employee::where('email', $GLOBALS['email'])->first();
			unset( $GLOBALS['email'] );
		    if( $result )
		    {
		        $validator->errors()->add('email', 'Email Address already registered. Please try with a different Email Address.');
		    }
		});

		// Check for mobile exists or not
		$GLOBALS['mobile_number'] = $request->mobile_number;
		$validator->after(function($validator){
			$result = Employee::where('mobile_number', $GLOBALS['mobile_number'])->first();
			unset( $GLOBALS['mobile_number'] );
			if( $result )
			{
				$validator->errors()->add('mobile_number', 'Mobile Number already registered. Please try with another Mobile Number.');
			}
		});
		
		if( $validator->fails() )
		{
			return redirect()->back()->withInput()->withErrors($validator);
		}
		// End - Validations

		$employee                 = new Employee;
		$employee->employer_id    = Auth::id();
		$employee->name           = $request->name;
		$employee->surname        = $request->surname;
		$employee->job_title      = $request->job_title;
		$employee->mobile_number  = $request->mobile_number;
		$employee->work_location  = $request->work_location;
		$employee->shift_type     = $request->shift_type;
		$employee->job_start_date = $request->job_start_date;
		$employee->job_end_date   = $request->job_end_date;
		$employee->email          = $request->email;
		$employee->official_id    = $request->official_id;
		$employee->phone1         = $request->phone1;
		$employee->phone2         = $request->phone2;
		$employee->password       = Hash::make($request->password);

		if( $employee->save() )
		{
			return redirect()->route('employees.index')->with('success', 'Record Added Successfully. <a href="' . route('employees.edit', $employee->id) . '" style="color:#FFFFFF; text-decoration:underline;">Edit</a>');
		}
		else
		{
			return redirect()->back()->withInput()->with('error', 'Record could not be saved due to some error. Please try again later.');
		}
	}


	public function edit($id=null)
	{
		$employee = Employee::find( $id );
		if( !$employee )
		{
			return redirect()->route('employees.index')->with('error', 'Record Not Found.');
		}

		return view('frontend.employees.edit', ['employee'=>$employee]);
	}


	public function update(Request $request, $id=null)
	{
		$employee = Employee::find($id);
		if( !$employee )
		{
			return redirect()->route('employees.index')->with('error', 'Record Not Found.');
		}

		// Start - Validations
		$rules = [
			'name'             => 'required',
			'surname'          => 'required',
			'mobile_number'    => 'required',
			'email'            => 'required|email'
		];

		$validator = Validator::make( $request->all(), $rules );

		if( $request->email != $employee->email )
		{
			// Check for email exists or not
			$GLOBALS['email'] = $request->email;
			$validator->after(function ($validator) {
				$result = Employee::where('email', $GLOBALS['email'])->first();
				unset( $GLOBALS['email'] );
			    if( $result )
			    {
			        $validator->errors()->add('email', 'Email Address already registered. Please try with a different Email Address.');
			    }
			});
		}

		if( $request->mobile_number != $employee->mobile_number )
		{
			// Check for mobile exists or not
			$GLOBALS['mobile_number'] = $request->mobile_number;
			$validator->after(function($validator){
				$result = Employee::where('mobile_number', $GLOBALS['mobile_number'])->first();
				unset( $GLOBALS['mobile_number'] );
				if( $result )
				{
					$validator->errors()->add('mobile_number', 'Mobile Number already registered. Please try with another Mobile Number.');
				}
			});
		}
		
		if( $validator->fails() )
		{
			return redirect()->back()->withInput()->withErrors($validator);
		}
		// End - Validations

		$employee->name           = $request->name;
		$employee->surname        = $request->surname;
		$employee->job_title      = $request->job_title;
		$employee->mobile_number  = $request->mobile_number;
		$employee->work_location  = $request->work_location;
		$employee->shift_type     = $request->shift_type;
		$employee->job_start_date = $request->job_start_date;
		$employee->job_end_date   = $request->job_end_date;
		$employee->email          = $request->email;
		$employee->official_id    = $request->official_id;
		$employee->phone1         = $request->phone1;
		$employee->phone2         = $request->phone2;

		if( $employee->save() )
		{
			return redirect()->route('employees.edit', $employee->id)->with('success', 'Record Updated Successfully.');
		}
		else
		{
			return redirect()->back()->with('error', 'Record could not be updated due to some error. Please try again later.');
		}
	}


	public function destroy($id=null)
	{
		$employee = Employee::find($id);
		if( !$employee )
		{
			return redirect()->route('employees.index')->with('error', 'Record Not Found.');
		}

		if( $employee->delete() )
		{
			return redirect()->route('employees.index')->with('success', 'Record Deleted Successfully.');
		}
		else
		{
			return redirect()->route('employees.index')->with('error', 'Record could not be deleted due to some error. Please try again later.');
		}
	}

	public function updateStatus($id=null)
	{
		$employee = Employee::find($id);
		if( !$employee )
		{
			return redirect()->route('employees.index')->with('error', 'Record Not Found.');
		}

		if( $employee->status == "Inactive" )
		{
			$employee->status = "Active";
		}
		else
		{
			$employee->status = "Inactive";
		}

		if( $employee->save() )
		{
			return redirect()->route('employees.index')->with('success', 'Record Updated Successfully.');
		}
		else
		{
			return redirect()->route('employees.index')->with('error', 'Record could not be updated due to some error. Please try again later.');
		}
	}

	//Get all employees data of a particular employee
    public function ajax_getEmployees(){ 
		$_SESSION['active_tab']=2;
        $requestData= $_REQUEST;
        $employerID = $requestData['empId'];
        $columns = array( 
        // datatable column index  => database column name
            0 => 'id', 
            1 => 'name', 
            2 => 'email',
            3 => 'job_title',
            4 => 'mobile_number',
            5 => 'actions'

        );
        
        // getting total number records without any search
        $results = DB::select("SELECT id FROM Employees WHERE deleted !=1 AND employer_id = $employerID ORDER BY id ASC");
        $totalData = count($results);
        
        $sql = "SELECT id,employer_id, name, email,job_title,mobile_number,created_at,work_location,shift_type,status";
        $sql.=" FROM employees WHERE deleted != 1 AND employer_id = $employerID";
        if( !empty($requestData['search']['value']) ) {   
        // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql.=" AND ( name LIKE '%".$requestData['search']['value']."%' ";    
            
            $sql.=" OR email LIKE '%".$requestData['search']['value']."%'";
            $sql.=" OR job_title LIKE '%".$requestData['search']['value']."%'";
            $sql.=" OR mobile_number LIKE '%".$requestData['search']['value']."%'";
         }
        $totalFiltered = DB::select($sql);
        $totalFiltered = count($totalFiltered);
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['length']." OFFSET ".$requestData['start']."   ";

        $filteredResults = DB::select($sql);
        $data = array();

        foreach ($filteredResults as $key => $value) {
            $nestedData=array(); 
            $status = ($value->status=='Active') ? "<a title='make Inactive' href=" . route('employees.update_status', $value->id) . "  class='btn btn-xs btn-success' ><i class='fa fa-power-off'></i></a>" : "<a title='make Active' href=" . route('employees.update_status', $value->id) . "  class='btn btn-xs btn-warning'><i class='fa fa-ban'></i></a>"; 
            $actions = $status."<a title='Edit' href=" . route('employees.edit', $value->id) . " class='btn btn-xs btn-info'><i class='fa fa-edit'></i></a> <a  title='Delete' href=". route('employees.destroy', $value->id) ." class='btn btn-xs btn-danger del-rec' record-id='" . $value->id . "'><i class='fa fa-trash'></i></a> ";
             /*$actions = "<div class='btn-group'> <button data-toggle='dropdown' class='btn btn-xs btn-primary dropdown-toggle' type='button' aria-expanded='false'>Action <span class='caret'></span> </button> <ul role='menu' class='dropdown-menu'> <li><a href=".url('admin/view_employee',base64_encode(convert_uuencode($value->id)))." >View</a> </li> <li class='divider'></li><li>".$status."</li> <li class='divider'></li> <li><a href=".url('admin/edit_employee',base64_encode(convert_uuencode($value->id)))." >Edit</a> </li> <li class='divider'></li> <li><a href=".url('admin/delete_employee',base64_encode(convert_uuencode($value->id))).">Delete</a> </li> </ul> </div>";*/
            $nestedData[] = '#'.$value->employer_id.$value->id;
            $nestedData[] = ucfirst($value->name);
            $nestedData[] = $value->email;
            $nestedData[] = $value->job_title;
            $nestedData[] = $value->mobile_number;
            $nestedData[] = $actions;
            $data[] = $nestedData;
        }
        //debug($data);die;
        $json_data = array(
                    "draw"            => intval( $requestData['draw'] ),   
                    "recordsTotal"    => intval( $totalData ),  // total number of records
                    "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching,
                    "data"            => $data   // total data array
                    );

        echo json_encode($json_data);
    }


    public function ajax_reports( Request $request )
    {
		$userId = Auth::user()->id;
    	$date = $request->date;
    	$fromDate = $request->fromDate;
    	$toDate = $request->toDate;
    	$employee_count = Employee::where("employer_id",$userId)->count();
    	$employees = DB::table("employees")->where("employer_id",$userId)->orderBy('id')->offset($request->start)->limit($request->length)->get();
    	$data = [];
		$index =0;
    	
		/***********************************/
		$query = "SELECT *,employees.id as empId FROM reports left join employees on (employees.id=reports.employee_id) WHERE employees.employer_id =$userId";
			$querySet = $query;
			if(!empty($toDate) && !empty($fromDate)){
				$querySet .=  " AND reports.created_at >='" . $fromDate." 00:00:00" . "' AND reports.created_at <='".$toDate." 23:59:59'";
			} 
			if($date!=''){
				$querySet .= " AND reports.created_at::text LIKE '" . $date . "%'";	
			}
			$querySet1 = $querySet." order by reports.created_at desc ";
			$querySet = $querySet." order by reports.created_at desc LIMIT ".$request->length." OFFSET ".$request->start;
			
			$report  = DB::select($querySet);
			
			$employee_count = count(DB::select($querySet1));
		/***********************************/
		$index =0;
		$OldRows = array();
		 foreach($report as $key=>$val){
				if(in_array($val->clock_in_time,$OldRows)){
					continue;
				}
				$data[$index][] = '<span employee-id="' . $val->empid . '">' . ucfirst($val->name) . ' ' . ucfirst($val->surname) . '</span>';
				$OldRows[]= $val->clock_in_time;
				$data[$index][] = $val->clock_in_time;
				$data[$index][] = $val->clock_in_location;
				$data[$index][] = $val->clock_out_time;
				$data[$index][] = $val->clock_out_location;
				$index++;
			 }
		
	/*
    	for( $i=0; $i<count($employees); $i++ )
    	{
    		$employee_id = $employees[$i]->id;
			/*if(!empty($fromDate)&&!empty($toDate)){
				$query = "SELECT * FROM reports WHERE employee_id = $employee_id AND created_at >='" . $fromDate." 00:00:00'"." AND created_at <='".$toDate." 23:59:59'"." Order by id desc limit 1";
				
			}else{
			 	$query = "SELECT * FROM reports WHERE employee_id = $employee_id AND created_at::text LIKE '$date%' Order by id desc limit 1";
			}
			  
			
			$query = "SELECT * FROM reports WHERE employee_id =$employee_id";
			$querySet = $query;
			if(!empty($toDate) && !empty($fromDate)){
				$querySet .=  " AND created_at >='" . $fromDate." 00:00:00" . "' AND created_at <='".$toDate." 23:59:59'";
			} 
			if($date!=''){
				$querySet .= " AND created_at::text LIKE '" . $date . "%'";	
			}
	 
			$report  = DB::select($querySet);
			 
			 
			 foreach($report as $key=>$val){
				$data[$index][] = '<span employee-id="' . $employees[$i]->id . '">' . ucfirst($employees[$i]->name) . ' ' . ucfirst($employees[$i]->surname) . '</span>';
				$data[$index][] = $val->clock_in_time;
				$data[$index][] = $val->clock_in_location;
				$data[$index][] = $val->clock_out_time;
				$data[$index][] = $val->clock_out_location;
				$index++;
			 }
			 
			  
			 
    		//$report = DB::select( $query );
    		// $employees[$i]->report = $report;
    		
    	}
			foreach ($data as $key => $row) {
				 
					$attack[$key]  = $row[1];
				}
				$sort = constant("SORT_DESC");
				array_multisort($attack, $sort, $data);
	 
		*/
		$dataCount = count($data);
        //$getReportByUsers = $this->getReportByUsers();
        $json_data = array(
                    "draw"            => intval( $request->draw ),   
                    "recordsTotal"    => intval( $dataCount ),  // total number of records
                    "recordsFiltered" => intval( $dataCount ), // total number of records after searching,
                    "data"            => $data  ,
					 
                    );

        echo json_encode($json_data);
    }

    public function ajax_currentLocations(Request $request)
    {
		
    	$response = [
    		'success' => null
    	];

    	$employeeId = $request->employeeId;
    	$query = "SELECT * FROM employees WHERE id = $employeeId LIMIT 1";
    	$result = DB::select( $query );
    	$employee = $result[0];
    	if( $employee )
    	{
    		$minDate = $request->minDate;
    		$maxDate = $request->maxDate;
			//$query = "SELECT * FROM employee_current_locations WHERE employee_id = $employeeId AND created_at::text >= '$minDate' AND created_at::text <= '$maxDate'";
			$query = "SELECT * FROM employee_current_locations WHERE employee_id = $employeeId AND DATE(created_at) >= '$minDate' AND DATE(created_at) <= '$maxDate'";
			
			#checking properly work single employee location
			//$query = "SELECT * FROM employee_current_locations WHERE employee_id = $employeeId ";
			$result = DB::select( $query );
    		$employee->current_locations = $result;
    		$response['success'] = 1;
    		$response['data']['employee'] = $employee;
    	}

    	return json_encode( $response );
    }

    public function ajax_workingHours(Request $request)
    {
    	$response = [
    		"success" => null
    	];
    	// clock_in_time

    	$employee_id = $request->employee_id;
    	$employee = Employee::find($employee_id);
    	if( !$employee )
    	{
    		$response['message'] = "Employee does not exists.";
    	}
		$selected_date = isset($request->selected_date)&& !empty($request->selected_date)?$request->selected_date:date('Y-m-d');
		
		 
		$selectedDate = isset($_GET['selected_date'])&&!empty($_GET['selected_date'])?$_GET['selected_date']:'';//date('Y-m-d');
		
		$reportStartDate  = isset($_GET['start_date'])&&!empty($_GET['start_date'])?$_GET['start_date']:'';
		$reportEndDate  = isset($_GET['end_date'])&&!empty($_GET['end_date'])?$_GET['end_date']:'';
		 $query = "SELECT * FROM reports WHERE employee_id = $employee_id ";
		 $querySet = $query;
		if(!empty($reportEndDate)&&!empty($reportStartDate)){
				
			$querySet .=  " AND created_at >='" . $reportStartDate." 00:00:00" . "' AND created_at <='".$reportEndDate." 23:59:59'";
		} 	
		if($selectedDate!=''){
			
			$querySet .= " AND created_at::text LIKE '" . $selectedDate . "%'";
		}
		 
		$day_report  = DB::select($querySet);
	 
		
		
  		// $day_report  = DB::select($query . " AND created_at::text LIKE '2018-02-21%'");
		//$day_report  = DB::select($query . " AND created_at::text LIKE '" .$selected_date . "%'");
    	$day_hours = 0;
		$daySeconds = 0;
		$empName = $employee->name." ".$employee->surname;
		foreach($day_report as $key=>$report)
    	{
			 $day_report[$key]->empName = $empName;
    		/*$clock_in_time  = date_create( $report->clock_in_time );
			$clock_out_time = date_create( $report->clock_out_time );
			$diff  = date_diff($clock_in_time, $clock_out_time);
	    	$hours = $diff->format('%h');*/
			$seconds = strtotime($report->clock_out_time) - strtotime($report->clock_in_time);
		 	$daySeconds = (int)$daySeconds+ (int)$seconds;
    	}
		$day_hours =  (int)($daySeconds / 60 / 60);

		$week_report = DB::select($query . " AND created_at >= '" . date('Y-m-d', strtotime('-7 days')) . " 00:00:00' AND created_at <= '" . date('Y-m-d') . " 23:59:59'");
    	$week_hours = 0;
		$weekSeconds = 0;
    	foreach($week_report as $report)
    	{
    		/*$clock_in_time  = date_create( $report->clock_in_time );
			$clock_out_time = date_create( $report->clock_out_time );
			$diff  = date_diff($clock_in_time, $clock_out_time);
	    	$hours = $diff->format('%h');*/
			$seconds = strtotime($report->clock_out_time) - strtotime($report->clock_in_time);
			$weekSeconds = (int)$weekSeconds+ (int)$seconds;
		}
		$week_hours =  (int)($weekSeconds / 60 / 60);

    	$month_report = DB::select($query . " AND created_at >= '" . date('Y-m-d', strtotime('-1 month')) . " 00:00:00' AND created_at <= '" . date('Y-m-d') .  " 23:59:59'");
    	$month_hours = 0;
		$monthSeconds =0;
    	foreach($month_report as $report)
    	{
    		/*$clock_in_time  = date_create( $report->clock_in_time );
			$clock_out_time = date_create( $report->clock_out_time );
			$diff  = date_diff($clock_in_time, $clock_out_time);
	    	$hours = $diff->format('%h');*/
			$seconds = strtotime($report->clock_out_time) - strtotime($report->clock_in_time);
		 	
			$monthSeconds = (int)$monthSeconds + (int)$seconds;
    	}
			$month_hours =  (int)($monthSeconds / 60 / 60);
	    
    	$year_report = DB::select($query . " AND created_at >= '" . date('Y-m-d', strtotime('-1 year')) . " 00:00:00' AND created_at <= '" . date('Y-m-d') .  " 23:59:59'");
    	$year_hours = 0;
		$yearSeconds =0;
    	foreach($year_report as $report)
    	{
    		/*$clock_in_time  = date_create( $report->clock_in_time );
			$clock_out_time = date_create( $report->clock_out_time );
			$diff  = date_diff($clock_in_time, $clock_out_time);
	    	$hours = $diff->format('%h');*/
				$seconds = strtotime($report->clock_out_time) - strtotime($report->clock_in_time);
		 	$yearSeconds = (int)$yearSeconds+ (int)$seconds;
    	}
			$year_hours =  (int)($yearSeconds / 60 / 60);
	     
    	$response['data']['todaySlotsRecords']   = count($day_report);
    	$response['data']['todaySlots']   = $day_report;
    	$response['data']['day_hours']   = $day_hours;
    	$response['data']['week_hours']  = $week_hours;
    	$response['data']['month_hours'] = $month_hours;
    	$response['data']['year_hours']  = $year_hours;

    	return json_encode($response);
    }
	
	
	/*****Function to get all user records ******/
	public function getReportByUsers(){
		$employerID  = Auth::user()->id;
		
	 
		$eArray = '';
		$empName= array();
		$empArr = DB::table("employees")->where("employer_id",$employerID)->get();
		foreach($empArr as $key=>$val){
			$eArray .=  $val->id.",";	
			$empName[$val->id] = $val->name." ".$val->surname;
		}
		$eArray = rtrim($eArray,",");
		 $query = "SELECT * FROM reports WHERE employee_id in($eArray)";
  	 	 //$day_report  = DB::select($query . " AND created_at::text LIKE '2018-02-21%'");
    	$day_report  = DB::select($query . " AND created_at::text LIKE '" . date('Y-m-d') . "%'");
	 
    	$day_hours = 0;
		$daySeconds = 0;
		foreach($day_report as $key =>$report)
    	{
			 $day_report[$key]->empName = $empName[$report->employee_id] ;
    		/*$clock_in_time  = date_create( $report->clock_in_time );
			$clock_out_time = date_create( $report->clock_out_time );
			$diff  = date_diff($clock_in_time, $clock_out_time);
	    	$hours = $diff->format('%h');*/
			$seconds = strtotime($report->clock_out_time) - strtotime($report->clock_in_time);
		 	$daySeconds = (int)$daySeconds+ (int)$seconds;
    	}
		$day_hours =  (int)($daySeconds / 60 / 60);

		$week_report = DB::select($query . " AND created_at >= '" . date('Y-m-d', strtotime('-7 days')) . " 00:00:00' AND created_at <= '" . date('Y-m-d') . " 23:59:59'");
    	$week_hours = 0;
		$weekSeconds = 0;
    	foreach($week_report as $report)
    	{
    		/*$clock_in_time  = date_create( $report->clock_in_time );
			$clock_out_time = date_create( $report->clock_out_time );
			$diff  = date_diff($clock_in_time, $clock_out_time);
	    	$hours = $diff->format('%h');*/
			$seconds = strtotime($report->clock_out_time) - strtotime($report->clock_in_time);
			$weekSeconds = (int)$weekSeconds+ (int)$seconds;
		}
		$week_hours =  (int)($weekSeconds / 60 / 60);

    	$month_report = DB::select($query . " AND created_at >= '" . date('Y-m-d', strtotime('-1 month')) . " 00:00:00' AND created_at <= '" . date('Y-m-d') .  " 23:59:59'");
    	$month_hours = 0;
		$monthSeconds =0;
    	foreach($month_report as $report)
    	{
    		/*$clock_in_time  = date_create( $report->clock_in_time );
			$clock_out_time = date_create( $report->clock_out_time );
			$diff  = date_diff($clock_in_time, $clock_out_time);
	    	$hours = $diff->format('%h');*/
			$seconds = strtotime($report->clock_out_time) - strtotime($report->clock_in_time);
		 	
			$monthSeconds = (int)$monthSeconds + (int)$seconds;
    	}
			$month_hours =  (int)($monthSeconds / 60 / 60);
	    
    	$year_report = DB::select($query . " AND created_at >= '" . date('Y-m-d', strtotime('-1 year')) . " 00:00:00' AND created_at <= '" . date('Y-m-d') .  " 23:59:59'");
    	$year_hours = 0;
		$yearSeconds =0;
    	foreach($year_report as $report)
    	{
    		/*$clock_in_time  = date_create( $report->clock_in_time );
			$clock_out_time = date_create( $report->clock_out_time );
			$diff  = date_diff($clock_in_time, $clock_out_time);
	    	$hours = $diff->format('%h');*/
				$seconds = strtotime($report->clock_out_time) - strtotime($report->clock_in_time);
		 	$yearSeconds = (int)$yearSeconds+ (int)$seconds;
    	}
			$year_hours =  (int)($yearSeconds / 60 / 60);
	     
    	$response['data']['todaySlotsRecords']   = count($day_report);
    	$response['data']['todaySlots']   = $day_report;
    	$response['data']['day_hours']   = $day_hours;
    	$response['data']['week_hours']  = $week_hours;
    	$response['data']['month_hours'] = $month_hours;
    	$response['data']['year_hours']  = $year_hours;

		return $response;
	}
	
}
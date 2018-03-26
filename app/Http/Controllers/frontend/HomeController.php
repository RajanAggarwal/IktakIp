<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $Employees = array();
        if(!empty(Auth::user()->employees)){
            foreach( Auth::user()->employees as $key1=> $employee ){
                $Employees[$key1] =  $employee->id;
            }
        }
        $yearHours = array();
        $WeaksHours = array();
        $monthHours = array();
        $currentdayHours = array();
        if(isset($Employees) && !empty($Employees)){
            
            foreach($Employees as $employee_id){

                #Current Day hour for all Employee
                $day_hours = 0;
                $daySeconds = 0;
                $query = "SELECT * FROM reports WHERE employee_id = $employee_id ";
                //$day_report  = DB::select($query . " AND created_at::text LIKE '" . date('Y-m-d') . "%'");
                $day_report  = DB::select($query . " AND created_at::text LIKE '" . date('Y-m-d') . "%'");
                if(isset($day_report) && !empty($day_report)){
                    foreach($day_report as $report)
                    {
                        $seconds = strtotime($report->clock_out_time) - strtotime($report->clock_in_time);
                        $daySeconds = (int)$daySeconds+ (int)$seconds;   
                    } 
                    $day_hours =  (int)($daySeconds / 60 / 60);
                }
                $currentdayHours[$employee_id] = $day_hours;

                # This Weak Hours
                $week_hours = 0;
                $weekSeconds = 0;
                $week_report = DB::select($query . " AND created_at >= '" . date('Y-m-d', strtotime('-7 days')) . " 00:00:00' AND created_at <= '" . date('Y-m-d') . " 23:59:59'");
                if(isset($week_report) && !empty($week_report)){
                    foreach($week_report as $report)
                    {
                        $seconds = strtotime($report->clock_out_time) - strtotime($report->clock_in_time);
                        $weekSeconds = (int)$weekSeconds+ (int)$seconds;  
                    }
                    $week_hours =  (int)($weekSeconds / 60 / 60);
                }
                $WeaksHours[$employee_id] = $week_hours;
                $month_report = DB::select($query . " AND created_at >= '" . date('Y-m-d', strtotime('-1 month')) . " 00:00:00' AND created_at <= '" . date('Y-m-d') .  " 23:59:59'");
                $month_hours = 0;
                $monthSeconds =0;
                if(isset($month_report) && !empty($month_report)){
                    foreach($month_report as $report)
                    {
                        $seconds = strtotime($report->clock_out_time) - strtotime($report->clock_in_time);
                        
                        $monthSeconds = (int)$monthSeconds+ (int)$seconds;   
                    }
                    $month_hours =  (int)($monthSeconds / 60 / 60);
                }
                $monthHours[$employee_id] = $month_hours;
                $year_report = DB::select($query . " AND created_at >= '" . date('Y-m-d', strtotime('-1 year')) . " 00:00:00' AND created_at <= '" . date('Y-m-d') .  " 23:59:59'");
                $year_hours = 0;
                $yearSeconds =0;
                if(isset($year_report) && !empty($year_report)){
                    foreach($year_report as $report)
                    {
                        $seconds = strtotime($report->clock_out_time) - strtotime($report->clock_in_time);
                        $yearSeconds = (int)$yearSeconds+ (int)$seconds;
                    }
                    $year_hours =  (int)($yearSeconds / 60 / 60);
                }
                $yearHours[$employee_id] = $year_hours;
            }
            $total_years_hours = array_sum($yearHours);
            $total_month_hours = array_sum($monthHours);
            $total_weaks_hours = array_sum($WeaksHours);
            $total_days_hours = array_sum($day_report);
        }
		
		$getReportByUsers = $this->getReportByUsers();
		
        return view('frontend.home.index',compact('getReportByUsers','total_years_hours','total_month_hours','total_weaks_hours','total_days_hours'));
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
		$selectedDate = isset($_GET['selected_date'])&&!empty($_GET['selected_date'])?$_GET['selected_date']:date('Y-m-d');
		
		$reportStartDate  = isset($_GET['reportStartDate'])&&!empty($_GET['reportStartDate'])?$_GET['reportStartDate']:'';
		$reportEndDate  = isset($_GET['reportEndDate'])&&!empty($_GET['reportEndDate'])?$_GET['reportEndDate']:'';
		 $query = "SELECT * FROM reports WHERE employee_id in($eArray)";
			 $querySet = $query;
		if(!empty($reportEndDate) && !empty($reportStartDate)){
			$querySet .=  " AND created_at >='" . $reportStartDate." 00:00:00" . "' AND created_at <='".$reportEndDate." 23:59:59'";
		} 
		if($selectedDate!=''){
			$querySet .= " AND created_at::text LIKE '" . $selectedDate . "%'";	
		}
	 
		$day_report  = DB::select($querySet);
		 
  	 	 //$day_report  = DB::select($query . " AND created_at::text LIKE '2018-02-21%'");
	 
    	$day_hours = 0;
		$daySeconds = 0;
		foreach($day_report as $key=>$report)
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
	
	
    public function userlogout(){
        Auth::logout();
        return redirect('/login');
    }
}

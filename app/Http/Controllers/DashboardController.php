<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use DB;


class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataArray = array();
      
        $totalEmployees    = DB::table('employees')->count();
        $activeEmployees   = DB::table('employees')->where('deleted','0')->where('status','Active')->count();
        $inActiveEmployees = DB::table('employees')->where('deleted','0')->where('status','Inactive')->count();
        $deletedEmployees  = DB::table('employees')->where('deleted','1')->count();
        $dataArray['totalEmployees']    = $totalEmployees;
        $dataArray['activeEmployees']   = $activeEmployees;
        $dataArray['inActiveEmployees'] = $inActiveEmployees;
        $dataArray['deletedEmployees']  = $deletedEmployees;

        $totalUsers    = DB::table('users')->count();
        $activeUsers   = DB::table('users')->where('deleted','0')->where('status','Active')->count();
        $inActiveUsers = DB::table('users')->where('deleted','0')->where('status','Inactive')->count();
        $deletedUsers  = DB::table('users')->where('deleted','1')->count();
        $dataArray['totalUsers']    = $totalUsers;
        $dataArray['activeUsers']   = $activeUsers;
        $dataArray['inActiveUsers'] = $inActiveUsers;
        $dataArray['deletedUsers']  = $deletedUsers;
        
	   return view('backend.home',['data_array'=>$dataArray]);
    }
}

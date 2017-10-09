<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Auth;
use Hash;
use Session;
use Cookie;
use DB;
use Illuminate\Support\Facades\Redirect;
class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

     // echo "here";die;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      if(isset($_POST) && !empty($_POST)){
          $data =  DB::table('admin')->where("email",$_POST['email'])->get();
          $dataArray = json_decode( json_encode($data), true);
          if(isset($dataArray) && !empty($dataArray[0])){

            $password = $_POST['password'];
          
            if (Hash::check($password , $data[0]->password)){
              Session::put('adminSession', $dataArray[0]);
              if(isset($_POST['remember']) && $_POST['remember']=='on')
              {
                  unset($data['remember']);
                  Cookie::queue('rememberMe',$dataArray[0],(86400 * 30));
              }
              else
              {
                  \Cookie::queue(\Cookie::forget('rememberMe'));
              }
          
              return redirect('admin/dashboard'); 
            }else{
               Session::flash('flash_message_error', 'Incorrect password');
               return view('backend.login');
            }

          }else{
             Session::flash('flash_message_error', 'Invalid Credentials');
             return view('backend.login');
          }
      }
	  if(Session::has('adminSession')){
		    return redirect('admin/dashboard'); 
	  }
	  
      return view('backend.login');
    }

     public function logout(){
        Session::flush();
        if(Session::has('adminSession')){
            echo "failed";die;
        }
        else{

            return redirect('admin');
        }
    }
	
	
	
	
	

    
}

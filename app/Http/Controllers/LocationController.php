<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use App\Location;
use Illuminate\Http\RedirectResponse;

class LocationController extends Controller
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
    public function index(Request $request,$id=null)
    {
		$_SESSION['active_tab']=1;
		if($id)
		{
            $id = convert_uudecode(base64_decode($id));
			$location = Location::find($id);  
			return view('employer.editlocation',['location_arr'=>$location, 'locId'=>base64_encode(convert_uuencode($id))]);
		}else{
			return view('employer.location',['locId'=>base64_encode(convert_uuencode($id))]);
		}
    }

    public function save_location(Request $request, $id=null)
    {
		$_SESSION['active_tab']=1;
		$userId = Auth::id();
		if($id){
            $id = convert_uudecode(base64_decode($id));
			$location = Location::find($id);  
			$location->location_name = $request->get('location_name');
			$location->location_address = $request->get('address');
			$location->latitude = $request->get('latitude');
			$location->longitude = $request->get('longitude'); 
			$location->employer_id  = $userId; 
			if($location->save()){
				Session::flash('flash_message_success', 'Location has been added successfully');
			}else{
				Session::flash('flash_message_error', 'Some error occured, Please try later.'); 
			} 
			return redirect('locations/'.base64_encode(convert_uuencode($id))); 
		}else{
			$location = new Location;
			$location->location_name = $request->get('location_name');
			$location->location_address = $request->get('address');
			$location->latitude = $request->get('latitude');
			$location->longitude = $request->get('longitude'); 
			$location->employer_id  = $userId; 
			if($location->save()){
				Session::flash('flash_message_success', 'Location has been added successfully');
			}else{
				Session::flash('flash_message_error', 'Some error occured, Please try later.'); 
			}
			return redirect('employer.locations'); 
		}
    }
	
    public function list_locations()
    { 
		   $_SESSION['active_tab']=1;
		   return view('employer.locationlist');
	}
	
    public function list_employees()
    { 
			$_SESSION['active_tab']=2;
		   return view('employer.locationlist');
	}
	
	
    public function delete_locations($id)
    {	  
		if(Location::findOrFail($id)->delete()){
			Session::flash('flash_message_success', 'Deleted successfully');
		}else{
			Session::flash('flash_message_error', 'Some error occured, Please try later.'); 
		}
		return redirect('employer.get_locations'); 
	}
	
    function delete_employee_for_employer(Request $request,$id){ 
            $id = convert_uudecode(base64_decode($id));
            if(!empty($id)){ 
                $user = Employee::find($id);
                $employerID = $user['employer_id'];
                $user->deleted= 1;      
                if($user->save()){
                    Session::flash('flash_message_success', 'Status has been updated successfully');
                    return view('employer.employees',compact('employerID'));
                }
                else{
                    Session::flash('flash_message_error', 'Some error occured, Please try later.');
                    return view('employer.employees',compact('employerID'));
                }
            }
            else{
                Session::flash('flash_message_error', 'Something wents wrong, Please try again later.');
                return view('employer.employees',compact('employerID'));
            } 
    }
	

    
}

<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

use App\Location;

use Session;
use Auth;

class LocationsController extends Controller
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


    public function index()
    {
    	return view('frontend.locations.index');
    }


    public function add(Request $request, $id=null)
    {
    	return view('frontend.locations.add');
    }


    public function store(Request $request)
    {
    	$location = new Location;
		$location->employer_id      = Auth::id();
		$location->location_name    = $request->location_name;
		$location->latitude         = $request->latitude;
		$location->longitude        = $request->longitude;
		$location->location_address = $request->address;
		
		if( $location->save() )
		{
			return redirect()->route('locations.edit', $location->id)->with('success', 'Record Added Successfully.');
		}
		else
		{
			return redirect()->back()->with('error', 'Record could not be added due to some error. Please reload the page and try again.');
		}
    }


    public function edit($id=null)
    {
    	$location = Location::find($id);
		return view('frontend.locations.edit', ['location'=>$location]);
    }


    public function update(Request $request, $id=null)
    {
    	$location = Location::find($id);
    	if( !$location )
    	{
    		return redirect()->route('locations.index')->with('error', 'Record Not Found.');
    	}

		$location->employer_id      = Auth::id(); 
    	$location->location_name    = $request->get('location_name');
		$location->location_address = $request->get('address');
		$location->latitude         = $request->get('latitude');
		$location->longitude        = $request->get('longitude'); 

		if($location->save())
		{
			return redirect()->route('locations.edit', $location->id)->with('success', 'Record Updated Successfully.');
		}
		else
		{
			return redirect()->back()->with('error', 'Record could not be Updated due to some error. Please Reload the page and try again.');
		}
    }


    public function destroy($id=null)
    {
    	$location = Location::find($id);
    	if( !$location )
    	{
    		return redirect()->route('locations.index')->with('error', 'Record Not Found.');
    	}

    	if( $location->delete() )
    	{
    		return redirect()->route('locations.index')->with('success', 'Record Deleted Successfully.');
    	}
    	else
    	{
    		return redirect()->route('locations.index')->with('error', 'Record could not be deleted due to some error. Please reload the page and try again.');
    	}
    }

    /*public function save_location(Request $request, $id=null)
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
			// 
		}
    }*/
	
    /*public function list_locations()
    { 
		   $_SESSION['active_tab']=1;
		   return view('employer.locationlist');
	}*/
	
    /*public function list_employees()
    { 
			$_SESSION['active_tab']=2;
		   return view('employer.locationlist');
	}*/
	
	
    /*public function delete_locations($id)
    {	  
		if(Location::findOrFail($id)->delete()){
			Session::flash('flash_message_success', 'Deleted successfully');
		}else{
			Session::flash('flash_message_error', 'Some error occured, Please try later.'); 
		}
		return redirect('employer.get_locations'); 
	}*/
	
    /*function delete_employee_for_employer(Request $request,$id){ 
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
    }*/
	

    
}

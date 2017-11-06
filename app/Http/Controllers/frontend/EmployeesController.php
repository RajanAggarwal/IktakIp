<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Validator;
use Hash;
use Auth;

use App\Employee;

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
}
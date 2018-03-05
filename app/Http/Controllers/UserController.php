<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Hash;
use Session;
use App\User;
use App\Employee;
use App\Location;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        // 
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function index(Request $request){
        if(Session::has('adminSession')){
            $filter_by = "none";
            if( isset($_GET['active']) && ($_GET['active'] == 1) )
            {
                $filter_by = "active";
            }
            elseif ( isset($_GET['inactive']) && ($_GET['inactive'] == 1) )
            {
                $filter_by = "inactive";
            }
            elseif( isset($_GET['deleted']) && ($_GET['deleted'] == 1) )
            {
                $filter_by = "deleted";
            }

            return view('backend.user.index', compact('filter_by'));
        }
        else{
            Session::flash('flash_message_error', 'You have to login to access this page');
            return redirect('admin');
        }
    }

    public function edit_employer(Request $request,$id=null){
        if(Session::has('adminSession')){
            $_SESSION['active_tab']=2;
            $userId = convert_uudecode(base64_decode($id));
            $userDetails = User::find($userId);
            $type='edit';
            $formAction='edit_employer';
            /*echo $userDetails['name'];*/
           // print_r($userDetails);die;
            $data = $request->input();

            if(!empty($data)){
               //debug($data);die;
                if($this->checkAlreayExistOrNot('users','email',$data['email'],$data['userId'])){
                    $userDetails = $data;
                    Session::flash('flash_message_error', 'Email already exist');
                    return view('backend.user.edit_employer',compact('userDetails','userId','type','formAction'));

                }else{ 
                   $userDetails = User::find($data['userId']);
                   $userDetails->name = $data['name'] ? $data['name'] :''; 
                   $userDetails->email = $data['email'] ? $data['email'] :''; 
                   if($request->hasFile('image')){  
                        if (!empty($_FILES['image'])) 
                        {
                            $info =  list($width, $height) = getimagesize($_FILES['image']['tmp_name']);
                            if($width >=100 && $height >=100)
                            {
                                $file = $request->file('image');
                                $filename = date('YmdHis').$file->getClientOriginalName();
                                $extension = $file->getClientOriginalExtension();
                                if($extension =='jpeg' || $extension =='gif' || $extension =='jpg' || $extension =='png')
                                {
                                     $destinationPath = base_path() . '/public/images/company_logo/'; 
                                    if($file->move($destinationPath, $filename))
                                    {
                                        $userDetails->company_logo=$filename;
                                    }
                                    else
                                    {
                                        $errorMsg = "Your image is not uploaded,please try again later";
                                    }
                                }
                                else
                                {
                                 $errorMsg = "Image must be of type: .jpg, .png, .jpeg, or .gif";
                                }
                            }
                            else
                            {
                                $errorMsg = "Image size should be atleast (100*100)";
                            }
                        }
                    }


                   if($userDetails->save()){
                        Session::flash('flash_message_success', 'User has been updated successfully');
                        return redirect('admin/employers');
                   }else{
                        Session::flash('flash_message_error', 'Something went wrong,please try again');
                        return view('backend.user.edit_employer',compact('userDetails','userId','type','formAction'));
                   } 
                } 
            }
            return view('backend.user.edit_employer',compact('userDetails','userId','type','formAction'));
        }
        else{
            Session::flash('flash_message_error', 'You have to login to access this page');
            return redirect('admin');
        }
    }
    public function add_employer(Request $request){
        if(Session::has('adminSession')){
            $_SESSION['active_tab']=2;
            $type='add';
            $formAction='add_employer';
            $userId = '';
            $userDetails = array();
            $data = $request->input();
            //echo "<pre>";print_r($data);die;
            if(!empty($data)){
                if($this->checkAlreayExistOrNot('users','email',$data['email'],'')){
                    $userDetails = $data;
                    Session::flash('flash_message_error', 'Email already exist');
                    return view('backend.user.edit_employer',compact('type','formAction','userId','userDetails'));

                }else{       
                    $user = new User;
                    $user->name=$data['name'];
                    $user->email=$data['email'];
                    $user->status='Active';
                    $user->deleted=0;
                    $user->activated=TRUE;
                    $user->password=Hash::make($data['password']);
                    $errorMsg = '';
                    if($request->hasFile('image')){  
                        if (!empty($_FILES['image'])) 
                        {
                            $info =  list($width, $height) = getimagesize($_FILES['image']['tmp_name']);
                            if($width >=100 && $height >=100)
                            {
                                $file = $request->file('image');
                                $filename = date('YmdHis').$file->getClientOriginalName();
                                $extension = $file->getClientOriginalExtension();
                                if($extension =='jpeg' || $extension =='gif' || $extension =='jpg' || $extension =='png')
                                {
                                     $destinationPath = base_path() . '/public/images/company_logo/'; 
                                    if($file->move($destinationPath, $filename))
                                    {
                                        $user->company_logo=$filename;
                                    }
                                    else
                                    {
                                        $errorMsg = "Your image is not uploaded,please try again later";
                                    }
                                }
                                else
                                {
                                 $errorMsg = "Image must be of type: .jpg, .png, .jpeg, or .gif";
                                }
                            }
                            else
                            {
                                $errorMsg = "Image size should be atleast (100*100)";
                            }
                        }
                        else
                        {
                            $errorMsg = "Please select Image";
                        }
                    }
                    $userDetails = $data;
                    if($errorMsg !=''){
                        Session::flash('flash_message_error', $errorMsg);
                        return view('backend.user.edit_employer',compact('type','formAction','userId','userDetails'));
                    }else{
                        if($user->save()){
                            Session::flash('flash_message_success', 'Employer has been added successfully');
                            return redirect('admin/employers');
                        }
                        else{
                            Session::flash('flash_message_error', 'Something went wrong,Please try after some time');
                            return view('backend.user.edit_employer',compact('type','formAction','userId','userDetails'));
                        }
                    }
                }
            }else{
                return view('backend.user.edit_employer',compact('type','formAction','userId','userDetails'));
            }
        }
        else{
            return redirect('admin');
        }
    }

    function update_employer_status(Request $request,$id){
        if(Session::has('adminSession')){ 
            $id = convert_uudecode(base64_decode($id));
            if(!empty($id)){ 
                $user = User::find($id);
                if($user['status']== 'Active'){
                    $user->status= 'Inactive';
                }
                else{
                    $user->status= 'Active';      
                }
                if($user->save()){
                    Session::flash('flash_message_success', 'Status has been updated successfully');
                    return redirect('admin/employers');
                }
                else{
                    Session::flash('flash_message_error', 'Some error occured, Please try later.');
                    return redirect('admin/employers');
                }
            }
            else{
                Session::flash('flash_message_error', 'Something wents wrong, Please try again later.');
                return redirect('admin/employers');
            }
        }
        else{
            return redirect('admin');
        }
    }

    function delete_employer(Request $request,$id){
        if(Session::has('adminSession')){ 
            $id = convert_uudecode(base64_decode($id));
            if(!empty($id)){ 
                $user = User::find($id);
                $user->deleted= 1;      
                if($user->save()){
                    Session::flash('flash_message_success', 'Status has been updated successfully');
                    return redirect('admin/employers');
                }
                else{
                    Session::flash('flash_message_error', 'Some error occured, Please try later.');
                    return redirect('admin/employers');
                }
            }
            else{
                Session::flash('flash_message_error', 'Something wents wrong, Please try again later.');
                return redirect('admin/employer');
            }
        }
        else{
            return redirect('admin');
        }
    }
	
    function delete_locations($id)
    {	   
		if(Location::findOrFail($id)->delete()){
			Session::flash('flash_message_success', 'Deleted successfully');
		}else{
			Session::flash('flash_message_error', 'Some error occured, Please try later.'); 
		}
		return redirect('list_locations'); 
	}

    public function get_employers(){ 
	 
        $requestData= $_REQUEST;
		$filter  = isset($_GET['filter'])&&!empty($_GET['filter'])?$_GET['filter']:'';
        $columns = array( 
        // datatable column index  => database column name
            0 =>'company_logo', 
            1 =>'name', 
            2 => 'email',
            3=> 'created_at',
            4=> 'login',
            5=>'actions'

        );
		/**Filter conditions**/
		$where = " WHERE 1=1 ";
		if($filter =='deletedusers'){
			$where .=" and deleted=1";
		}else if($filter =='inactiveusers'){
			$where .=" and status ='Inactive' and deleted !=1";
			
		}else if($filter =='activeusers'){
			$where .=" and status ='Active' and deleted !=1";
		} 
		
      
        // getting total number records without any search
        $results = DB::select("SELECT id FROM users  $where ORDER BY id ASC");
        $totalData = count($results);
        
        $sql = "SELECT id, name, email,created_at,company_logo,status";
        $sql.=" FROM users $where";
        if( !empty($requestData['search']['value']) ) {   
        // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql.=" AND ( name LIKE '".$requestData['search']['value']."%' ";    
            
            $sql.=" OR email LIKE '".$requestData['search']['value']."%' )";
        }
        $totalFiltered = DB::select($sql);
        $totalFiltered = count($totalFiltered);
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['length']." OFFSET ".$requestData['start']."   ";

        $filteredResults = DB::select($sql);
        $data = array();

        foreach ($filteredResults as $key => $value) {
            $nestedData=array(); 
            $status = ($value->status=='Active') ? "<a title='make Inactive' href=".url('admin/update_employer_status',base64_encode(convert_uuencode($value->id)))." class='btn btn-xs btn-success'><i class='fa fa-power-off'></i></a>" : "<a title='make Active' href=".url('admin/update_employer_status',base64_encode(convert_uuencode($value->id)))." class='btn btn-xs btn-warning'><i class='fa fa-ban'></i></a>"; 
            $actions = " <a href=".url('admin/edit_employer',base64_encode(convert_uuencode($value->id)))." class='btn btn-xs btn-info' title='Edit details'><i class='fa fa-edit'></i></a> <a href=".url('admin/delete_employer',base64_encode(convert_uuencode($value->id)))." class='btn btn-xs btn-danger' title='Delete Employer'><i class='fa fa-trash'></i></a>".$status;
            $image = asset('images/company_logo').'/'.($value->company_logo ? $value->company_logo:'no_image.png');
            $nestedData[] = '<img src="'.$image.'" height="50px" width="50px">';
            $nestedData[] = "<a title='View Employees' href=".url('admin/employees',base64_encode(convert_uuencode($value->id))) .">".ucfirst($value->name)."</a>";
            
            $nestedData[] = $value->email;
            $nestedData[] = date('d M Y',strtotime($value->created_at));
            $nestedData[] = "<a href=".url('admin/employer_login',base64_encode(convert_uuencode($value->id)))." target='_blank' title='Logged in as ".ucfirst($value->name)."' class='btn btn-xs btn-info'><i class='fa fa-share-square'></i></a>";
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
	
    public function get_locations(){ 
        $requestData= $_REQUEST;

        $columns = array( 
        // datatable column index  => database column name
            0 =>'id', 
            1 =>'latitude', 
            2 => 'longitude',
            3=> 'location_name',
            4=> 'location_address',
            4=> 'employer_id',
            6=>'actions'

        );
        
        // getting total number records without any search
        $results = DB::select("SELECT id FROM employer_locations ORDER BY id ASC");
        $totalData = count($results);
        
        $sql = "SELECT id, latitude, longitude, location_name, location_address, employer_id";
        $sql.=" FROM employer_locations Where 1=1  and employer_id='".AUTH::user()->id."'";
		
		
        if( !empty($requestData['search']['value']) ) {   
        // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql.=" AND ( name LIKE '".$requestData['search']['value']."%' ";    
            
            $sql.=" OR email LIKE '".$requestData['search']['value']."%' )";
        }
        $totalFiltered = DB::select($sql);
        $totalFiltered = count($totalFiltered);
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['length']." OFFSET ".$requestData['start']."   ";

        $filteredResults = DB::select($sql);
        $data = array();

         foreach ($filteredResults as $key => $value) {
            $nestedData=array(); 
           /*  $status = ($value->status=='Active') ? "<a title='make Inactive' href=".url('admin/update_employer_status',base64_encode(convert_uuencode($value->id)))." class='btn btn-xs btn-success'><i class='fa fa-power-off'></i></a>" : "<a title='make Active' href=".url('admin/update_employer_status',base64_encode(convert_uuencode($value->id)))." class='btn btn-xs btn-warning'><i class='fa fa-ban'></i></a>"; */
            $actions = " <a href=" . route('locations.edit', $value->id) . " class='btn btn-xs btn-info' title='Edit details'><i class='fa fa-edit'></i></a> <a href=" . route('locations.destroy', $value->id) . " class='btn btn-xs btn-danger del-rec' record-id='" . $value->id . "' title='Delete Employer'><i class='fa fa-trash'></i></a>" ; 
            /* $image = asset('images/company_logo').'/'.($value->company_logo ? $value->company_logo:'no_image.png'); 
            $nestedData[] = '<img src="'.$image.'" height="50px" width="50px">';*/
            $nestedData[] = $value->location_name;
            $nestedData[] = $value->longitude;
            $nestedData[] = $value->latitude;
            $nestedData[] = $value->location_address;
            $nestedData[] = $value->employer_id;
          //  $nestedData[] = date('d M Y',strtotime($value->created_at));
       /*      $nestedData[] = "<a href=".url('admin/employer_login',base64_encode(convert_uuencode($value->id)))." target='_blank' title='Logged in as ".ucfirst($value->location_name)."' class='btn btn-xs btn-info'><i class='fa fa-share-square'></i></a>"; */
            $nestedData[] = $actions;
            $data[] = $nestedData;
        } 
       // debug($data);die; 
        $json_data = array(
                    "draw"            => intval( $requestData['draw'] ),   
                    "recordsTotal"    => intval( $totalData ),  // total number of records
                    "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching,
                    "data"            => $data   // total data array
                    );

        echo json_encode($json_data); 
    }

   //Show the page for all employees of Particular Employer
    public function viewAllEmployeesOfEmployer(Request $request,$id=null){
        if(Session::has('adminSession')){
            $employerID = convert_uudecode(base64_decode($id));
            $_SESSION['active_tab']=2;
            return view('backend.user.employees',compact('employerID'));
        }
        else{
            Session::flash('flash_message_error', 'You have to login to access this page');
            return redirect('admin');
        }
    }
	
   // Descarded
    /*public function viewAllEmployeesOfEmployerToEployer(Request $request,$id=null){
            $employerID = convert_uudecode(base64_decode($id));
            $_SESSION['active_tab']=2;
            return view('employer.employees',compact('employerID')); 
    }*/	

     public function loginAsEmployer(Request $request,$id=null){
        if(Session::has('adminSession')){
            $employerID = convert_uudecode(base64_decode($id));
            $user = Auth::loginUsingId($employerID);
            if ( ! $user){
                throw new Exception('Error logging in');
            }else{
                Session::put('adminUserLoggedIn', $employerID);
                return redirect('/');
            }
        }
        else{
            Session::flash('flash_message_error', 'You have to login to access this page');
            return redirect('admin');
        }
    }

    //Get all employees data of a particular employee
    public function get_employees(){ 
        $requestData= $_REQUEST;
        $employerID = $requestData['empId'];
        $columns = array( 
        // datatable column index  => database column name
            0 =>'id', 
            1 =>'name', 
            2 => 'email',
            3 => 'job_title',
            4 => 'mobile_number',
            5=>'actions'

        );
		$filter  = isset($_GET['filter'])&&!empty($_GET['filter'])?$_GET['filter']:'';
		/**Filter conditions**/
		 $where='';
		if($filter =='deleted_emp'){
			 $where .=" and deleted=1 ";
		}else if($filter =='inactive_emp'){
			 $where .=" and status ='Inactive' and deleted !=1 ";
			
		}else if($filter =='active_emp'){
			 $where .=" and status ='Active' and deleted !=1 " ;
		} 
		
		if($employerID!=0){
			$empWhere = " employer_id = $employerID ";
			
		}else{
			$empWhere = " 1=1";
		}
		
        // getting total number records without any search
        $results = DB::select("SELECT id FROM Employees WHERE $empWhere  $where ORDER BY id ASC");
        $totalData = count($results);
        
        $sql = "SELECT id,employer_id, name, email,job_title,mobile_number,created_at,work_location,shift_type,status";
        $sql.=" FROM employees WHERE   $empWhere $where"; 
        if( !empty($requestData['search']['value']) ) {   
        // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $sql.=" AND ( name LIKE '%".$requestData['search']['value']."%' ";    
            
            $sql.=" OR email LIKE '%".$requestData['search']['value']."%'";
            $sql.=" OR job_title LIKE '%".$requestData['search']['value']."%'";
            $sql.=" OR mobile_number LIKE '%".$requestData['search']['value']."%')";
         }
		 
        $totalFiltered = DB::select($sql);
        $totalFiltered = count($totalFiltered);
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['length']." OFFSET ".$requestData['start']."   ";

        $filteredResults = DB::select($sql);
        $data = array();

        foreach ($filteredResults as $key => $value) {
            $nestedData=array(); 
            $status = ($value->status=='Active') ? "<a title='make Inactive' href=".url('admin/update_employee_status',base64_encode(convert_uuencode($value->id)))."  class='btn btn-xs btn-success' ><i class='fa fa-power-off'></i></a>" : "<a title='make Active' href=".url('admin/update_employee_status',base64_encode(convert_uuencode($value->id)))."  class='btn btn-xs btn-warning'><i class='fa fa-ban'></i></a>"; 
            $actions = $status."<a title='Edit' href=".url('admin/edit_employee',base64_encode(convert_uuencode($value->id)))." class='btn btn-xs btn-info'><i class='fa fa-edit'></i></a> <a  title='Delete' href=".url('admin/delete_employee',base64_encode(convert_uuencode($value->id)))." class='btn btn-xs btn-danger'><i class='fa fa-trash'></i></a> <a href=".url('admin/view_employee',base64_encode(convert_uuencode($value->id)))."  class='btn btn-xs btn-warning'><i class='fa fa-eye'  title='View Profile'></i></a> ";
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

    public function edit_employee(Request $request,$id=null){
        if(Session::has('adminSession')){
            $_SESSION['active_tab']=2;
            $employeeId = convert_uudecode(base64_decode($id));
            $userDetails = Employee::find($employeeId);
            $employerID = $userDetails['employer_id'];
            $type='edit';
            $formAction='edit_employee';
            /*echo $userDetails['name'];
            debug($userDetails);die;*/
            $data = $request->input();

            if(!empty($data)){
                //debug($data);die;
                if($this->checkAlreayExistOrNot('employees','email',$data['email'],$data['employeeId'])){
                    $userDetails = $data;
                    Session::flash('flash_message_error', 'Email already exist');
                    return view('backend.user.edit_employee',compact('userDetails','employerID','type','formAction','employeeId'));
                }else if($this->checkAlreayExistOrNot('employees','mobile_number',$data['mobile_number'],$data['employeeId'])){
                    $userDetails = $data;
                    Session::flash('flash_message_error', 'Mobile number already exist');
                    return view('backend.user.edit_employee',compact('userDetails','employerID','type','formAction','employeeId'));
                }else{       
                    $employerID = $data['employerID'];
                    $employeeId = $data['employeeId'];
                    $userDetails = Employee::find($data['employeeId']);
                    $userDetails->name=$data['name'];
                    $userDetails->job_title=$data['job_title'];
                    $userDetails->work_location=$data['work_location'];
                    $userDetails->job_start_date=date('Y-m-d',strtotime($data['job_start_date']));
                    $userDetails->job_end_date=date('Y-m-d',strtotime($data['job_end_date']));
                    $userDetails->phone1=$data['phone1'];
                    $userDetails->surname=$data['surname'];
                    $userDetails->mobile_number=$data['mobile_number'];
                    $userDetails->shift_type=$data['shift_type'];
                    $userDetails->official_id=$data['official_id'];
                    $userDetails->phone2=$data['phone2'];
                    $userDetails->email=$data['email'];
                   if($userDetails->save()){
                        Session::flash('flash_message_success', 'Employee details has been updated successfully');
                        //return redirect('admin/employees/',base64_encode(convert_uuencode($data['employerID'])));
                        return view('backend.user.employees',compact('employerID'));
                   }else{
                        Session::flash('flash_message_error', 'Something went wrong,please try again');
                        return view('backend.user.edit_employee',compact('userDetails','employerID','type','formAction','employeeId'));
                   } 
                }  
            }
            return view('backend.user.edit_employee',compact('userDetails','employerID','type','formAction','employeeId'));
        }
        else{
            Session::flash('flash_message_error', 'You have to login to access this page');
            return redirect('admin');
        }
    }

    /*public function edit_employee_for_employer(Request $request,$id=null){ 
            $_SESSION['active_tab']=2;
            $employeeId = convert_uudecode(base64_decode($id));
            $userDetails = Employee::find($employeeId);
            $employerID = $userDetails['employer_id'];
            $type='edit';
            $formAction='edit_employee';
            /*echo $userDetails['name'];
            debug($userDetails);die;*//*
            $data = $request->input();

            if(!empty($data)){
                //debug($data);die;
                if($this->checkAlreayExistOrNot('employees','email',$data['email'],$data['employeeId'])){
                    $userDetails = $data;
                    Session::flash('flash_message_error', 'Email already exist');
                    return view('employer.edit_employee',compact('userDetails','employerID','type','formAction','employeeId'));
                }else if($this->checkAlreayExistOrNot('employees','mobile_number',$data['mobile_number'],$data['employeeId'])){
                    $userDetails = $data;
                    Session::flash('flash_message_error', 'Mobile number already exist');
                    return view('employer.edit_employee',compact('userDetails','employerID','type','formAction','employeeId'));
                }else{
                    $employerID = $data['employerID'];
                    $employeeId = $data['employeeId'];
                    $userDetails = Employee::find($data['employeeId']);
                    $userDetails->name=$data['name'];
                    $userDetails->job_title=$data['job_title'];
                    $userDetails->work_location=$data['work_location'];
                    $userDetails->job_start_date=date('Y-m-d',strtotime($data['job_start_date']));
                    $userDetails->job_end_date=date('Y-m-d',strtotime($data['job_end_date']));
                    $userDetails->phone1=$data['phone1'];
                    $userDetails->surname=$data['surname'];
                    $userDetails->mobile_number=$data['mobile_number'];
                    $userDetails->shift_type=$data['shift_type'];
                    $userDetails->official_id=$data['official_id'];
                    $userDetails->phone2=$data['phone2'];
                    $userDetails->email=$data['email'];
                   if($userDetails->save()){
                        Session::flash('flash_message_success', 'Employee details has been updated successfully');
                        //return redirect('admin/employees/',base64_encode(convert_uuencode($data['employerID'])));
                        return view('employer.employees',compact('employerID'));
                   }else{
                        Session::flash('flash_message_error', 'Something went wrong,please try again');
                        return view('employer.edit_employee',compact('userDetails','employerID','type','formAction','employeeId'));
                   } 
                }  
            }
            return view('employer.edit_employee',compact('userDetails','employerID','type','formAction','employeeId')); 
    }*/
    public function add_employee(Request $request,$id=null){
        if(Session::has('adminSession')){
            $_SESSION['active_tab']=2;
            $type='add';
            $employerID = convert_uudecode(base64_decode($id));
            $formAction='add_employee';
            $userDetails = array();
            $employeeId = '';
            $data = $request->input();
            
            if(!empty($data)){
                
                if($this->checkAlreayExistOrNot('employees','email',$data['email'],'')){
                   $userDetails = $data;
                   Session::flash('flash_message_error', 'Email already exist');
                   return view('backend.user.edit_employee',compact('userDetails','employerID','type','formAction','employeeId'));
                }else if($this->checkAlreayExistOrNot('employees','mobile_number',$data['mobile_number'],'')){
                    $userDetails = $data;
                    Session::flash('flash_message_error', 'Mobile number already exist');
                    return view('backend.user.edit_employee',compact('userDetails','employerID','type','formAction','employeeId'));

                }else{
                    $employerID = $data['employerID'];
                    $newEmployee = new Employee;
                    $newEmployee->employer_id=$data['employerID'];
                    $newEmployee->name=$data['name'];
                    $newEmployee->job_title=$data['job_title'];
                    $newEmployee->work_location=$data['work_location'];
                    $newEmployee->job_start_date = $data['job_start_date'] ? date('Y-m-d',strtotime($data['job_start_date'])) : null;
                    $newEmployee->job_end_date = $data['job_end_date'] ? date('Y-m-d',strtotime($data['job_end_date'])) : null;
                    $newEmployee->phone1=$data['phone1'];
                    $newEmployee->surname=$data['surname'];
                    $newEmployee->mobile_number=$data['mobile_number'];
                    $newEmployee->shift_type=$data['shift_type'];
                    $newEmployee->official_id=$data['official_id'];
                    $newEmployee->phone2=$data['phone2'];
                    $newEmployee->email=$data['email'];
                    $newEmployee->password=Hash::make($data['password']);
                    if($newEmployee->save()){
                       Session::flash('flash_message_success', 'Employee has been added successfully');
                        //return redirect('admin/employees/',base64_encode(convert_uuencode($data['employerID'])));
                        return view('backend.user.employees',compact('employerID'));
                    }
                    else{
                        return view('backend.user.edit_employee/',compact('type','formAction','employerID','userDetails','employeeId'    ));
                    }
                }
            }else{
                return view('backend.user.edit_employee',compact('type','formAction','employerID','userDetails','employeeId'));
            }
        }
        else{
            return redirect('admin');
        }
    }

    /*public function add_employee_for_employer(Request $request,$id=null){ 
            $_SESSION['active_tab']=2;
            $type='add';
            $employerID = convert_uudecode(base64_decode($id));
            $formAction='add_employee';
            $userDetails = array();
            $employeeId = '';
            $data = $request->input();
            
            if(!empty($data)){
                
                if($this->checkAlreayExistOrNot('employees','email',$data['email'],'')){
                   $userDetails = $data;
                   Session::flash('flash_message_error', 'Email already exist');
                   return view('employer.edit_employee',compact('userDetails','employerID','type','formAction','employeeId'));
                }else if($this->checkAlreayExistOrNot('employees','mobile_number',$data['mobile_number'],'')){
                    $userDetails = $data;
                    Session::flash('flash_message_error', 'Mobile number already exist');
                    return view('employer.edit_employee',compact('userDetails','employerID','type','formAction','employeeId'));

                }else{
                    $employerID = $data['employerID'];
                    $newEmployee = new Employee;
                    $newEmployee->employer_id=$data['employerID'];
                    $newEmployee->name=$data['name'];
                    $newEmployee->job_title=$data['job_title'];
                    $newEmployee->work_location=$data['work_location'];
                    $newEmployee->job_start_date=date('Y-m-d',strtotime($data['job_start_date']));
                    $newEmployee->job_end_date=date('Y-m-d',strtotime($data['job_end_date']));
                    $newEmployee->phone1=$data['phone1'];
                    $newEmployee->surname=$data['surname'];
                    $newEmployee->mobile_number=$data['mobile_number'];
                    $newEmployee->shift_type=$data['shift_type'];
                    $newEmployee->official_id=$data['official_id'];
                    $newEmployee->phone2=$data['phone2'];
                    $newEmployee->email=$data['email'];
                    $newEmployee->password=Hash::make($data['password']);
                    if($newEmployee->save()){
                       Session::flash('flash_message_success', 'Employee has been added successfully');
                        //return redirect('admin/employees/',base64_encode(convert_uuencode($data['employerID'])));
                        return view('employer.employees',compact('employerID'));
                    }
                    else{
                        return view('employer.edit_employee/',compact('type','formAction','employerID','userDetails','employeeId'    ));
                    }
                }
            }else{
                return view('employer.edit_employee',compact('type','formAction','employerID','userDetails','employeeId'));
            } 
    }*/

    function update_employee_status(Request $request,$id){
        if(Session::has('adminSession')){ 
            $id = convert_uudecode(base64_decode($id));
            if(!empty($id)){ 
                $user = Employee::find($id);
                $employerID = $user['employer_id'];
                if($user['status']== 'Active'){
                    $user->status= 'Inactive';
                }
                else{
                    $user->status= 'Active';      
                }
                if($user->save()){
                    Session::flash('flash_message_success', 'Status has been updated successfully');
                    return view('backend.user.employees',compact('employerID'));
                }
                else{
                    Session::flash('flash_message_error', 'Some error occured, Please try later.');
                   return view('backend.user.employees',compact('employerID'));
                }
            }
            else{
                Session::flash('flash_message_error', 'Something wents wrong, Please try again later.');
                return view('backend.user.employees',compact('employerID'));
            }
        }
        else{
            return redirect('admin');
        }
    }
	
    /*function update_employee_status_for_employer(Request $request,$id){
			$_SESSION['active_tab']=2;
            $id = convert_uudecode(base64_decode($id));
            if(!empty($id)){ 
                $user = Employee::find($id);
                $employerID = $user['employer_id'];
                if($user['status']== 'Active'){
                    $user->status= 'Inactive';
                }
                else{
                    $user->status= 'Active';      
                }
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

    function delete_employee(Request $request,$id){
        if(Session::has('adminSession')){ 
            $id = convert_uudecode(base64_decode($id));
            if(!empty($id)){ 
                $user = Employee::find($id);
                $employerID = $user['employer_id'];
                $user->deleted= 1;      
                if($user->save()){
                    Session::flash('flash_message_success', 'Status has been updated successfully');
                    return view('backend.user.employees',compact('employerID'));
                }
                else{
                    Session::flash('flash_message_error', 'Some error occured, Please try later.');
                    return view('backend.user.employees',compact('employerID'));
                }
            }
            else{
                Session::flash('flash_message_error', 'Something wents wrong, Please try again later.');
                return view('backend.user.employees',compact('employerID'));
            }
        }
        else{
            return redirect('admin');
        }
    }
	
    /*function delete_employee_for_employer(Request $request,$id){ 
			$_SESSION['active_tab']=2;
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

    function checkAlreayExistOrNot($table,$field,$value,$id){
        $data = DB::table($table)
                        ->where($field,$value)
                        ->where('deleted','0')
                        ->where(function ($query) use ($id) {
                            if(isset($id) && $id!=''){
                                $query->where('id','!=',$id);
                            }
                        })
                        ->first();
        if(!empty($data)){
            return true;
        }else{
            return false;
        }
    }


     public function view_employee(Request $request,$id=null){
        if(Session::has('adminSession')){
            $_SESSION['active_tab']=2;
            $employerID = convert_uudecode(base64_decode($id));
            $userDetails = Employee::find($employerID);
            return view('backend.user.view_employee',compact('userDetails'));
        }else{
            return redirect('admin');
        }
    }

     /*public function view_employee_for_employer(Request $request,$id=null){
            $_SESSION['active_tab']=2;
            $employerID = convert_uudecode(base64_decode($id));
            $userDetails = Employee::find($employerID);
            return view('employer.view_employee',compact('userDetails'));
    }*/
/**Show all company employees**/
public function showallEmployees(){
 
	 if(Session::has('adminSession')){
          
            $_SESSION['active_tab']=2;
            return view('backend.user.all-employees');
        }
        else{
            Session::flash('flash_message_error', 'You have to login to access this page');
            return redirect('admin');
        }
	
	
}
	
	
	}

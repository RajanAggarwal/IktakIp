@extends('layouts.main')

@section('content')
<div class="right_col" role="main">
  <div class="">
  <div class="page-title">
      <div class="title_left">
        <h3><?php echo ucfirst($type); ?> Employee</h3>
      </div>

     <!--  <div class="title_right">
        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Search for...">
            <span class="input-group-btn">
              <button class="btn btn-default" type="button">Go!</button>
            </span>
          </div>
        </div>
      </div> -->
    </div>

    <div class="clearfix"></div>
     <div class="row">
        @if(Session::has('flash_message_error'))
            <div role="alert" class="alert alert-danger alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true">×</span></button> <strong>Error!</strong> {!! session('flash_message_error') !!} </div>
        @endif
        @if(Session::has('flash_message_success'))
            <div role="alert" class="alert alert-success alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true">×</span></button> <strong>Success!</strong> {!! session('flash_message_success') !!} </div>
        @endif
        <form role="form" id="editUserForm" method="post" action="{!! url('admin/'.$formAction,base64_encode(convert_uuencode($employerID))) !!}" >
                    {{ csrf_field() }}
                    <input type="hidden" name="employerID" value="{{ $employerID }}">
                    <input type="hidden" name="employeeId" value="{{ $employeeId }}">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <input type="hidden" id="page-type" value="{{ $type }}">
                            <label>Name</label><span class="star-required">*</span>
                            <input class="form-control" name="name" placeholder="Enter Name" value="{{ isset($userDetails['name']) ? $userDetails['name'] :''}}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                        <input type="hidden" id="page-type" value="{{ $type }}">
                            <label>Surname</label><span class="star-required">*</span>
                            <input class="form-control" name="surname" placeholder="Enter surname" value="{{ isset($userDetails['surname']) ? $userDetails['surname'] :''}}">
                        </div>

                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                            <label>Job Title</label>
                            <input type="text" class="form-control" value="{{ isset($userDetails['job_title']) ?$userDetails['job_title'] :''}}" name="job_title" placeholder="Enter job title">
                        </div> 
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                            <label>Mobile number</label><span class="star-required">*</span>
                            <input type="text" class="form-control" value="{{ isset($userDetails['mobile_number']) ? $userDetails['mobile_number'] :''}}" name="mobile_number" placeholder="Enter mobile number">
                        </div>
                    </div>
                    <div class="col-lg-6">
                     <div class="form-group">
                            <label>Work Location</label>
                            <input type="text" class="form-control" value="{{ isset($userDetails['work_location']) ? $userDetails['work_location'] :''}}" name="work_location" placeholder="Enter work location">
                        
                        </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                            <label>Shift type</label>
                            <input type="text" class="form-control" value="{{ isset($userDetails['shift_type']) ? $userDetails['shift_type'] :''}}" name="shift_type" placeholder="Enter shift type">
                        </div>

                    </div>
                    <div class="col-lg-6">
                     <div class="form-group">
                            <label>Job Start Date</label>
                            <input type="text" class="form-control datepicker" value="{{ isset($userDetails['job_start_date']) ? $userDetails['job_start_date'] :''}}" name="job_start_date" placeholder="Enter job Start date">
                        </div>
                    </div>
                    <div class="col-lg-6">
                     <div class="form-group">
                            <label>Job End date</label>
                            <input type="text" class="form-control datepicker" value="{{ isset($userDetails['job_end_date']) ? $userDetails['job_end_date'] :''}}" name="job_end_date" placeholder="Enter job end date">
                        </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                            <label>Email Address</label><span class="star-required">*</span>
                            <input type="email" class="form-control" value="{{ isset($userDetails['email']) ? $userDetails['email'] :''}}" name="email" placeholder="Enter email address">
                        </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                            <label>Official ID</label>
                            <input type="text" class="form-control " value="{{ isset($userDetails['official_id']) ? $userDetails['official_id'] :''}}" name="official_id" placeholder="Enter official id">
                        </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                            <label>Phone1</label>
                            <input type="text" class="form-control " value="{{ isset($userDetails['phone1']) ? $userDetails['phone1'] :''}}" name="phone1" placeholder="Enter phone1">
                        </div>

                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                            <label>Phone2</label>
                            <input type="text" class="form-control " value="{{ isset($userDetails['phone2']) ? $userDetails['phone2'] :''}}" name="phone2" placeholder="Enter phone2">
                        </div>

                    </div>
                    <?php if($type =='add'){ ?>
                    <div class="col-lg-6">
                     <div class="form-group">
                            <label>Password</label><span class="star-required">*</span>
                            <input type="password" class="form-control" name="password" placeholder="Enter Password">
                        </div>
                    </div>
                    <div class="col-lg-6">
                     <div class="form-group">
                            <label>Confirm Password</label><span class="star-required">*</span>
                            <input type="password" class="form-control" name="confirm_password" placeholder="Enter Confirm Password">
                        </div>
                    </div>
                     <?php } ?>
                     
                   <div class="col-lg-6">
                       
                    <button type="button" id="submitData" class="btn btn-success">Save</button>
                    <a href="{{ url('admin/employees',base64_encode(convert_uuencode($employerID)))}}" class="btn btn-danger">Cancel</a>
                 </div>
                      
            </form>
        </div>
        <!-- /.row -->
  </div>
</div>

        
@endsection
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery/jquery-1.4.4.min.js"></script>
<script type="text/javascript">

    function  display_error_message(message,name){
      $("input[name="+name+"]").focus();
      $.toaster({ priority : 'danger', title : 'Error', message : message});
    }
    function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
};

   $(document).ready(function(){
       $( ".datepicker" ).datepicker();
    $('#submitData').click(function() {
    var page = $('#page-type').val();
    if($("input[name=name]").val() ==''){
        display_error_message('Name is Required','first_name');
   
    }else if($("input[name=surname]").val() ==''){
        display_error_message('Surname is Required','surname');
   
    /*}else if($("input[name=job_title]").val() ==''){
        display_error_message('Job Title is Required','job_title');*/
    
    }else if($("input[name=mobile_number]").val() ==''){
        display_error_message('Mobile Number is Required','mobile_number');
    
   /* }else if($("input[name=work_location]").val() ==''){
        display_error_message('Work location is Required','work_location');
    
    }else if($("input[name=shift_type]").val() ==''){
        display_error_message('Shift type is Required','shift_type');
    
    }else if($("input[name=job_start_date]").val() ==''){
        display_error_message('Job Start date is Required','job_start_date');
   
    }else if($("input[name=job_end_date]").val() ==''){
        display_error_message('Job end date is Required','job_end_date');*/
    
    }else if( !($("input[name=mobile_number]").val().match(/^\d+$/)) ){
        display_error_message('Invalid Input for Mobile Number','mobile_number');
    }
    else if( $("input[name=mobile_number]").val().length != 10 ){
        display_error_message('Mobile Number must contain 10 digits','mobile_number');
    }
    else if($("input[name=email]").val() ==''){
        display_error_message('Email is Required','email');
   
    } else if( !isValidEmailAddress( $("input[name=email]").val() ) ) { 
        display_error_message('Enter Valid Email','email');
   
   /* }else if($("input[name=official_id]").val() ==''){
        display_error_message('Official Id is Required','official_id');
   
    }else if($("input[name=phone1]").val() ==''){
        display_error_message('Phone1 is Required','phone1');
   
    }else if($("input[name=phone2]").val() ==''){
        display_error_message('Phone2 is Required','phone2');
   */
    }else if($("input[name=password]").val() =='' && page =='add'){
        display_error_message('Password is Required','password');
    
    }else if($("input[name=confirm_password]").val() =='' && page =='add'){
        display_error_message('Confirm Password is Required','confirm_password');
   
    }else if($("input[name=password]").val() !=$("input[name=confirm_password]").val() && page =='add'){
        display_error_message('Password and its Confirm Password must be same','confirm_password');
   
    }else{
        $('#editUserForm').submit();
    }
   }); 
   }); 
</script>

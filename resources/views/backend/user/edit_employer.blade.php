@extends('layouts.main')

@section('content')
<div class="right_col" role="main">
  <div class="">
  <div class="page-title">
      <div class="title_left">
        <h3><?php echo ucfirst($type); ?> Employer</h3>
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
        <form role="form" id="editUserForm" method="post" action="{!! url('admin/'.$formAction,base64_encode(convert_uuencode($userId))) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="userId" value="{{ $userId }}">
                    <div class="col-lg-12">
                        <div class="form-group">
                        <input type="hidden" id="page-type" value="{{ $type }}">
                            <label>Company Name</label><span class="star-required">*</span>
                            <input class="form-control" name="name" placeholder="Enter Full Name" value="{{ isset($userDetails['name']) ? $userDetails['name'] :''}}">
                        </div>

                        <div class="form-group">
                            <label>Email</label><span class="star-required">*</span>
                            <input type="email" class="form-control" value="{{ isset($userDetails['email']) ?$userDetails['email'] :''}}" name="email" placeholder="Enter Email Id">
                        </div>

                        <div class="form-group">
                            <label>Company Logo</label><span class="star-required"></span>
                            <input type="file" class="" name="image">
                        </div>
                        <?php if($type =='add'){ ?>
                        <div class="form-group">
                            <label>Password</label><span class="star-required">*</span>
                            <input type="password" class="form-control" name="password" placeholder="Enter Password">
                        </div>

                        <div class="form-group">
                            <label>Confirm Password</label><span class="star-required">*</span>
                            <input type="password" class="form-control" name="confirm_password" placeholder="Enter Confirm Password">
                        </div>
                        <?php } ?>
                    </div>
                    
                    <div class="col-lg-6">
                       
                    <button type="button" id="submitData" class="btn btn-success">Save</button>
                    <a href="{{ url('admin/employers')}}" class="btn btn-danger">Cancel</a>
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
    $('#submitData').click(function() {
    var page = $('#page-type').val();
    if($("input[name=name]").val() ==''){
        display_error_message('Name is Required','first_name');
    }else if($("input[name=email]").val() ==''){
        display_error_message('Email is Required','email');
    } else if( !isValidEmailAddress( $("input[name=email]").val() ) ) { 
        display_error_message('Enter Valid Email','email');
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
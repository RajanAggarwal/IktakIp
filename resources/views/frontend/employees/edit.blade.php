@extends('layouts.front')

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>Add Employee</h3>
        </div>
        <!-- <div class="title_right">
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
        @include('notifications')

        <!-- Display Server side errors (if any) -->
        @if( isset($errors) && !$errors->isEmpty() )
            @foreach($errors->all() as $error)
                <div role="alert" class="alert alert-danger alert-dismissible fade in">
                    <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true">×</span></button>
                    {!! $error !!}
                </div>
            @endforeach
        @endif
        
        <form method="POST" action="{{ route('employees.update', $employee->id) }}" role="form" id="frmAddEmployee">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Name</label><span class="star-required">*</span>
                    <input class="form-control required" name="name" placeholder="Enter Name" value="{{ $employee->name }}" field-name="Name">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Surname</label><span class="star-required">*</span>
                    <input class="form-control required" name="surname" placeholder="Enter surname" value="{{ $employee->surname }}" field-name="Surname">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Job Title</label>
                    <input type="text" class="form-control" value="{{ $employee->job_title }}" name="job_title" placeholder="Enter job title" field-name="Job Title">
                </div> 
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Mobile number</label><span class="star-required">*</span>
                    <input type="text" class="form-control required" value="{{ $employee->mobile_number }}" name="mobile_number" placeholder="Enter mobile number" field-name="Mobile Number">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Work Location</label>
                    <input type="text" class="form-control" value="{{ $employee->work_location }}" name="work_location" placeholder="Enter work location" field-name="Work Location">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Shift type</label>
                    <input type="text" class="form-control" value="{{ $employee->shift_type }}" name="shift_type" placeholder="Enter shift type" field-name="Shift Type">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Job Start Date</label>
                    <input type="text" class="form-control datepicker" value="{{ $employee->job_start_date }}" name="job_start_date" placeholder="Enter job Start date" field-name="Job Start Date">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Job End date</label>
                    <input type="text" class="form-control datepicker" value="{{ $employee->job_end_date }}" name="job_end_date" placeholder="Enter job end date" field-name="Job End date">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Email Address</label><span class="star-required">*</span>
                    <input type="email" class="form-control required" value="{{ $employee->email }}" name="email" placeholder="Enter email address" field-name="Email Address">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Official ID</label>
                    <input type="text" class="form-control " value="{{ $employee->official_id }}" name="official_id" placeholder="Enter official id" field-name="Official ID">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Phone1</label>
                    <input type="text" class="form-control " value="{{ $employee->phone1 }}" name="phone1" placeholder="Enter phone1" field-name="Phone1">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Phone2</label>
                    <input type="text" class="form-control " value="{{ $employee->phone2 }}" name="phone2" placeholder="Enter phone2" field-name="Phone2">
                </div>
            </div>  
            <div class="col-lg-6">       
                <button type="submit" id="submitData" class="btn btn-success">Save</button>
                <a href="{{ route('employees.index') }}" class="btn btn-danger">Cancel</a>
            </div>      
        </form>
    </div>
    <!-- /.row -->
</div>
@endsection
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery/jquery-1.4.4.min.js"></script>
<script type="text/javascript">

    function validEmail(email)
    {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i; 
        return pattern.test(email);
    };

    $(document).ready(function(){

        $( ".datepicker" ).datepicker({
            format : 'yyyy-mm-dd'
        });

        $("#frmAddEmployee").on('submit', function(e){

            $("input.required").each(function(){
                value = $(this).val();
                value = $.trim( value );

                if( !value )
                {
                    fieldName = $(this).attr('field-name');
                    message   = fieldName + ' Field is Required.';

                    $.toaster({
                        priority : 'danger',
                        title    : 'Error',
                        message  : message
                    });

                    e.preventDefault();
                    return false;   
                }
            });

            email = $('input[email]').val();
            email = $.trim(email);
            if( email && !validEmail(email) )
            {
                $.toaster({
                    priority : 'danger',
                    title    : 'Error',
                    message  : 'Please enter a Valid Email Address.'
                });

                e.preventDefault();
            }
        });
    }); 
</script>


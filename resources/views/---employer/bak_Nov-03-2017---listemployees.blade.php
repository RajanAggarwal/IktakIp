@extends('layouts.front')

@section('content')
<div class="right_col" role="main">
  <div class="">
  <div class="page-title">
      <div class="title_left">
        <h3>View Employee detail</h3>
      </div>
  </div>

    <div class="clearfix"></div>
    <div class="page-title">
      <div class="title_left">
        <h4>Unique Employee Id: {{$userDetails['employer_id'].$userDetails['id']}}</h4>
      </div>
  </div>

    <div class="clearfix"></div>
     <div class="row">
        
        
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" readonly="true" value="{{ isset($userDetails['name']) ? $userDetails['name'] :''}}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                            <label>Surname</label>
                            <input class="form-control" readonly="true" value="{{ isset($userDetails['surname']) ? $userDetails['surname'] :''}}">
                        </div>

                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                            <label>Job Title</label>
                            <input type="text" readonly="true" class="form-control" value="{{ isset($userDetails['job_title']) ?$userDetails['job_title'] :''}}" >
                        </div> 
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                            <label>Mobile number</label>
                            <input readonly="true" class="form-control" value="{{ isset($userDetails['mobile_number']) ? $userDetails['mobile_number'] :''}}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                     <div class="form-group">
                            <label>Work Location</label>
                            <input  readonly="true" class="form-control" value="{{ isset($userDetails['work_location']) ? $userDetails['work_location'] :''}}">
                        
                        </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                            <label>Shift type</label>
                            <input  readonly="true" class="form-control" value="{{ isset($userDetails['shift_type']) ? $userDetails['shift_type'] :''}}">
                        </div>

                    </div>
                    <div class="col-lg-6">
                     <div class="form-group">
                            <label>Job Start Date</label>
                            <input type="text" class="form-control datepicker" value="{{ isset($userDetails['job_start_date']) ? $userDetails['job_start_date'] :''}}"  readonly="true">
                        </div>
                    </div>
                    <div class="col-lg-6">
                     <div class="form-group">
                            <label>Job End date</label>
                            <input type="text" class="form-control datepicker" value="{{ isset($userDetails['job_end_date']) ? $userDetails['job_end_date'] :''}}" readonly="true">
                        </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" class="form-control" value="{{ isset($userDetails['email']) ? $userDetails['email'] :''}}"  readonly="true">
                        </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                            <label>Official ID</label>
                            <input type="text" class="form-control " value="{{ isset($userDetails['official_id']) ? $userDetails['official_id'] :''}}"  readonly="true">
                        </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                            <label>Phone1</label>
                            <input type="text" class="form-control " value="{{ isset($userDetails['phone1']) ? $userDetails['phone1'] :''}}" readonly="true">
                        </div>

                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                            <label>Phone2</label>
                            <input type="text" class="form-control " value="{{ isset($userDetails['phone2']) ? $userDetails['phone2'] :''}}"  readonly="true">
                    </div>

                    </div>
                    
        </div>
        <!-- /.row -->
  </div>
</div>

        
@endsection

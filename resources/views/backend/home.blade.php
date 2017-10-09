@extends('layouts.main')

@section('content')
<div class="right_col" role="main">
  <div class="main-area">
    <div class="row top_tiles"> 
      <!--  <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-users"></i></div>
                  <div class="count">{{ $data_array['totalUsers']}}</div>
                  <h3>Total Employers</h3>
                </div>
              </div> -->
      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
      	<div class="dasboard-icon">
        <div class="icon"><i class="fa fa-users"></i></div>
        <div class="count">
          <h4>Total <br>Employers</h4>
          {{ $data_array['totalUsers']}}</div>
          </div>
      </div>
      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <div class="dasboard-icon">
        <div class="icon"><i class="fa fa-check-square-o"></i></div>
        <div class="count">
          <h4>Active <br>Employers</h4>
          {{ $data_array['activeUsers']}}</div>
          </div>
      </div>
      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <div class="dasboard-icon">
        <div class="icon"><i class="fa fa-user-times"></i></div>
        <div class="count">
          <h4> Inactive <br>Employers</h4>
          {{ $data_array['inActiveUsers']}}</div>
          </div>
      </div>
      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <div class="dasboard-icon">
        <div class="icon"><i class="fa fa-trash"></i></div>
        <div class="count">
          <h4>Deleted <br>Employers</h4>
          {{ $data_array['deletedUsers']}}</div>
      </div>
      </div>
      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <div class="dasboard-icon">
        <div class="icon"><i class="fa fa-users"></i></div>
        <div class="count">
          <h4> Total <br>Employees</h4>
          {{ $data_array['totalEmployees']}}</div>
      </div>
      </div>
      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <div class="dasboard-icon">
        <div class="icon"><i class="fa fa-check-square-o"></i></div>
        <div class="count">
          <h4> Active <br>Employees</h4>
          {{ $data_array['activeEmployees']}}</div>
      </div>
      </div>
      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <div class="dasboard-icon">
        <div class="icon"><i class="fa fa-user-times"></i></div>
        <div class="count">
          <h4>Inactive <br>Employees</h4>
          {{ $data_array['inActiveEmployees']}}</div>
      </div>
      </div>
      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <div class="dasboard-icon">
        <div class="icon"><i class="fa fa-trash"></i></div>
        <div class="count">
          <h4>Deleted <br>Employees</h4>
          {{ $data_array['deletedEmployees']}}</div>
      </div>
      </div>
    </div>
  </div>
</div>
@endsection 
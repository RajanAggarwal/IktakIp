@extends('layouts.front')

@section('content')
 
<style>

    a.top-links.active{
        font-weight: bold;
    }

    #mapCanvas {
        width: 100%;
        height: 550px;
        float: left;
    }

    #locationsMapCanvas {
        width: 100%;
        height: 550px;
        float: left;
        margin-top:10px;
    }

    #mapCanvas1 {
        width: 100%;
        height: 550px;
        float: left;
    }

    #locationsMapCanvas1 {
        width: 132%;
        height: 550px;
        float: left;
        margin-top:10px;
    }

    .widget_tally_box p, .widget_tally_box span{
        text-align: left;
    }

    .panel-body{
        position: relative;
    }

    .month .active{
        font-weight: bold;
    }

    #overlay{
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 2;
        background-color: rgba(255,255,255,0.5);
        display: none;
    }

    #overlay-content {
        position: absolute;
        transform: translateY(-50%);
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        top: 50%;
        left: 0;
        right: 0;
        text-align: center;
        color: #555;
    }

    .list-unstyled li{
        {{ (isset(Auth::user()->employees[0]) && !empty(Auth::user()->employees[0])) ? ( !(Auth::user()->employees[0]->current_location) ? 'display:none;' : '' ) : ''  }}
    }

    #tableReports td:first-child{
    	cursor:pointer;
    }
</style>
<div style="min-height: 707px;" class="right_col" role="main">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Live View</div>
                <div class="panel-body">

                    <div id="overlay"><div id="overlay-content"><h4>loading...</h4></div></div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-right" style="background: #fff; margin-right:10px; margin-bottom: 5px; padding: 5px 10px; border: 1px solid #E6E9ED">
                                <a href="javascript:void(0);" class="top-links" link-to="locations" style="padding: 0 12px 0 10px;">Locations</a> |
                                <a href="javascript:void(0);" class="top-links" link-to="reports" style="padding: 0 10px 0 12px;">Reports</a>
                            </div>
                        </div>
                    </div>

                    <!-- Current Locations -->
                    <div id="divCurrentLocation" class="div-content">
                        <div class="col-md-3">
                            <div class="row">
                                <div class="widget widget_tally_box">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h2>Employees</h2>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content">
                                            <div>
                                                <ul class="list-inline widget_tally">
                                                    @foreach( Auth::user()->employees as $key1=> $employee )
                                                    <li>
                                                        <p>
                                                            <span class="month">
                                                                <i class="fa fa-user" aria-hidden="true"></i>
                                                                &nbsp;&nbsp;
                                                                <a href="javascript:void(0);" class="emp-name" data-emp-id="{{ $employee->id }}">{{ $employee->name . ' ' . $employee->surname }}</a>
                                                            </span>
                                                        </p>
                                                    </li>

                                                    <?php
                                                     $mapData[]  = [@$employee->name . ' ' . @$employee->surname, (double)@$employee['current_location']->lat, @(double)$employee['current_location']->lng, $key1]; ?>
                                                       
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="widget widget_tally_box">
                                    <div class="x_panel">
                                        <div class="x_content">
                                            <ul class="legend list-unstyled">
                                                @if( !( Auth::user()->employees->isEmpty() ) )
									
                                                <li>
                                                    <p>
                                                        <span class="name"><b><span id="EmpName">{{ @Auth::user()->employees[0]->name . ' ' . @Auth::user()->employees[0]->surname }}</span></b></span>
                                                    </p>
                                                </li>
                                                <li>
                                                    <p>
                                                        <span class="name"><b>Work Location : </b><span id="EmpWorkLocation">{{ @Auth::user()->employees[0]->work_location }}</span></span>
                                                    </p>
                                                </li>
                                                <li>
                                                    <p>
                                                        <span class="name"><b>Current Location : </b><span id="EmpCurrentLocation"></span></span>
                                                    </p>
                                                </li>
                                                <li>
                                                    <p>
                                                        <span class="name"><b>Current Time : </b><span id="EmpCurrentTime">{{ @date('F d, Y H:i:s', strtotime(Auth::user()->employees[0]->current_location->time)) }}</span></span>
                                                    </p>
                                                </li>
                                                <li>
                                                    <p>
                                                        <span class="name"><b>Shift :</b><span id="EmpShift">{{ @Auth::user()->employees[0]->shift_type }}</span></span>
                                                    </p>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div id="mapCanvas"></div>
                        </div>
                    </div>

                    <!-- Reports -->
                    <div class="row div-content" id="divReports" style="display: none;">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-5" style="padding-bottom:10px;">
                                    <form class="form-inline">
                                        <div class="form-group">
                                            <label>Search Date : </label>
                                            <input type="text" class="form-control datepicker" id="reportDate" readonly="readonly" style="background-color: #FFFFFF; border-radius: 0;" value="<?php echo  isset($_GET['selected_date'])&&!empty($_GET['selected_date'])?$_GET['selected_date']:((isset($_GET['reportStartDate'])&& !empty($_GET['reportStartDate']))?'':date("Y-m-d"));?>">
                                        </div>
                                    </form>
                                </div>
								  <div class="col-md-" style="padding-bottom:10px;">
                                    <form class="form-inline formSearchTimeframeDates" >
                                        <div class="form-group">
                                            <label>Start Date : </label>
                                            <input type="text" class="form-control " id="fromDate" readonly="readonly" name="reportStartDate" value="<?php echo  isset($_GET['reportStartDate'])&&!empty($_GET['reportStartDate'])?$_GET['reportStartDate']:'';?>"style="background-color: #FFFFFF; border-radius: 0;">
                                        </div>
										 <div class="form-group">
                                            <label>End Date : </label>
                                            <input type="text" class="form-control " id="toDate" readonly="readonly"  name="reportEndDate" value="<?php echo  isset($_GET['reportEndDate'])&&!empty($_GET['reportEndDate'])?$_GET['reportEndDate']:'';?>" style="background-color: #FFFFFF; border-radius: 0;">
                                        </div>
										 <div class="form-group">
                                           <input type="submit" class="form-control" value="Search" id="searchByDateSubmit" >
                                        </div>
                                    </form>
									<a href="{{url('/')}}">Clear Filters</a>
                                </div>
								
                            </div>
                        </div>
                    <!--    <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tableReports" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0">
                                    <thead>  
                                        <tr>    
                                            <th>Employee Name</th>
                                            <th>Clock In Time</th>
                                            <th>Clock In Location</th>
                                            <th>Clock Out Time</th>
                                            <th>Clock Out Location</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>-->
						 <div class="col-md-12" style="margin-top:20px;">
						   
							<table id="tableReports"  cellspacing="0">
                                    <thead>  
                                        <tr>    
                                           <th>Employee</th>
                                           <th>Clock In Time</th>
                                            <th>Clock In Location</th>
                                            <th>Clock Out Time</th>
                                            <th>Clock Out Location</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data_div1">
									@foreach($getReportByUsers['data']['todaySlots'] as $key=>$val)
									<tr>
										<td>{{$val->empName}}</td>
										<td>{{$val->clock_in_time}}</td>
										<td>{{$val->clock_in_location}}</td>
										<td>{{$val->clock_out_time}}</td>
										<td>{{$val->clock_out_location}}</td>
										 
									</tr>
									
									@endforeach
									    </tbody>
                                </table>
								
								
								
								<?php 
								$reportStartDate = isset($_GET['reportStartDate'])&&!empty($_GET['reportStartDate'])?$_GET['reportStartDate']:'';
								$reportEndDate = isset($_GET['reportEndDate'])&&!empty($_GET['reportEndDate'])?$_GET['reportEndDate']:'';
								$selected_date = isset($_GET['selected_date'])&&!empty($_GET['selected_date'])?$_GET['selected_date']:'';
								if($reportStartDate!='' && $reportEndDate!='' ){
									for($i=strtotime($reportStartDate);$i<=strtotime($reportEndDate);$i=$i+86400){
									$link = url('/?reportStartDate='.$reportStartDate."&reportEndDate=".$reportEndDate."&selected_date=".date("Y-m-d", $i));
										if(strtotime($selected_date) == $i){
											$class= 'btn btn-primary';
										}else{ 
											$class='btn btn-info';
										}
										
										echo "<a href='".$link."' class='".$class."'>".date("Y-m-d", $i)."</a>";
									}
								}
								
								?>
							</div>
                        <div class="col-md-12" style="margin-top:20px;">
						     <h4>Total Working Hours</h4>
						    <table class="table table-striped dt-responsive nowrap" cellspacing="0">
						        <tbody><tr>
						            <th>Current Day</th>
						            <td><span id="dayHours"> {{ $total_days_hours }} </span></td>
						        </tr>
						        <tr>
						            <th>This Week</th>
						            <td><span id="weekHours"> {{ $total_weaks_hours }} </span></td>
						        </tr>
						        <tr>
						            <th>This Month</th>
						            <td><span id="monthHours">{{ $total_month_hours }}</span></td>
						        </tr>
						        <tr>
						            <th>This Year</th>
						            <td><span id="yearHours">{{ $total_years_hours }}</span></td>
						        </tr>
						    </tbody></table>
						</div>
                    </div>

                    <!-- Locations -->
                    <div class="row div-content" id="divLocations" style="display: none;">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <form class="form-inline">
                                        <div class="form-group">
                                            <label>Choose Device : </label>
                                            <select class="form-control" id="chooseDevice" onchange="checkingmap()">
                                                <option value="">-- SELECT --</option>
                                                @foreach( Auth::user()->employees as $employee )
                                                    <option value="{{ $employee->id }}">{{ $employee->name . ' ' . $employee->surname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <form class="form-inline">
                                        <div class="form-group">
                                            <label>Set Date Range : </label>
                                            <input type="text" class="form-control daterangepickr" readonly="readonly" style="background-color: #FFFFFF;">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top:15px;">
                        	<div class="row">
                        		<div class="col-md-6">
                        			<button class="btn" title="Play" onclick="playMap()"><i class="fa fa-play"></i></button>
                        			<button class="btn" title="Pause" onclick="pauseMap()"><i class="fa fa-pause"></i> </button>
                        			<button class="btn" title="Stop" onclick="stopMap()"><i class="fa fa-stop"></i> </button>
                        			<button class="btn" title="Step Backward" onclick="backwardMap()"><i class="fa fa-step-backward"></i> </button>
                        			<button class="btn" title="Step Forward" onclick="forwardMap()"><i class="fa fa-step-forward"></i> </button>
                        			<!-- <button class="btn" title="Fast Backward" onclick="fastBackwardMap()"><i class="fa fa-fast-backward"></i> </button> -->
                        			<!-- <button class="btn" title="Fast Forward" onclick="fastForwardMap()"><i class="fa fa-fast-forward"></i> </button> -->
                        		</div>
                        	</div>
                        </div>
                        <div class="col-md-12">
                            <div id="mapCanvas1"></div>
                        </div>
                        <div class="col-md-12">
                            <div id="locationsMapCanvas"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="emp-lat" value="{!! (isset(Auth::user()->employees[0]->current_location) && !empty(Auth::user()->employees[0]->current_location) ) ? Auth::user()->employees[0]->current_location->lat : 'null' !!}">

<input type="hidden" id="emp-lng" value="{!! (isset(Auth::user()->employees[0]->current_location) && !empty(Auth::user()->employees[0]->current_location) ) ? Auth::user()->employees[0]->current_location->lng : 'null' !!}">

<input type="hidden" id="emp-name" value="{!! (isset(Auth::user()->employees[0]->name) && !empty(Auth::user()->employees[0]->name) ) ? Auth::user()->employees[0]->name. ' ' . @Auth::user()->employees[0]->surname : '' !!}">

<input type="hidden" id="emp-work-location" value="{!! (isset(Auth::user()->employees[0]->work_location) && !empty(Auth::user()->employees[0]->work_location) ) ? Auth::user()->employees[0]->work_location : '' !!}">

<input type="hidden" id="emp-current-time" value="{!! (isset(Auth::user()->employees[0]->current_location->time) && !empty(Auth::user()->employees[0]->current_location->time) ) ? date('F d, Y H:i:s', strtotime(Auth::user()->employees[0]->current_location->time)) : '' !!}">

<input type="hidden" id="emp-shift-type" value="{!! (isset(Auth::user()->employees[0]->shift_type) && !empty(Auth::user()->employees[0]->shift_type) ) ? Auth::user()->employees[0]->shift_type : '' !!}">
<span id="mapEmpData" style="display: none"><?php echo json_encode($mapData); ?></span>


<script type="text/javascript">
    checkingmap();
	// Click on top links
    function checkingmap(){
        if($("#chooseDevice").val()){
            $("#mapCanvas1").hide();
            $("#locationsMapCanvas").show();
        }else{
            $("#mapCanvas1").show();
            $("#locationsMapCanvas").hide();
        }
    }
    $(".top-links").on('click', function(e){
        e.preventDefault();

        $(".top-links").removeClass('active');
        $(this).addClass('active');
        $(".div-content").hide();
        linkTo = $(this).attr('link-to');
        switch( linkTo )
        {
            case 'locations' : 
                $("#divLocations").show();
                break;

            case 'reports' :
                $("#divReports").show();
                date = $("#reportDate").val();
                fromDate = $("#fromDate").val();
                toDate = $("#toDate").val();
				fetchReports(date,fromDate,toDate);
                break;
        }
    });
	
</script>

<!-- //////////////////////////////////////////////////////////////////////////////////// -->
<!-- ///////////////////////////////// Current Location ///////////////////////////////// -->
<!-- //////////////////////////////////////////////////////////////////////////////////// -->

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-wkrWVbqBX_eg2YkiUozNhI6zkoHhyoc"
    ></script>
<!--<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script> 
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>--> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.js"></script>

<!-- Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<script type="text/javascript">

    var lat, lng, map, marker, infowindow, geocoder;
    var empName, empWorkLocation, empCurrentLocation, empCurrentTime, empShift;
    var geocoder = new google.maps.Geocoder();
    lat = $('#emp-lat').val();
    lng = $('#emp-lng').val();
    empName = $('#emp-name').val();
    empWorkLocation = $('#emp-work-location').val();
    empCurrentTime = $('#emp-current-time').val();
    empShift = $('#emp-shift-type').val();

    function setCurrentLocation(pos)
    {
        geocoder = new google.maps.Geocoder();
        geocoder.geocode({
            latLng: pos
        },
        function(responses) {
            if (responses && responses.length > 0)
            {
                $("#EmpCurrentLocation").html( responses[0].formatted_address );
                empCurrentLocation = responses[0].formatted_address;
            }
        });
    }

    function renderMap()
    {
        latLng = new google.maps.LatLng( lat, lng );
        setCurrentLocation(latLng);

        map = new google.maps.Map(document.getElementById('mapCanvas'), {
            zoom: 20,
            center: latLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        contentString = '';
        contentString += '<table>';
        contentString += '  <tr>';
        contentString += '      <th>' + empName + '</th>';
        contentString += '  </tr>';
        contentString += '  <tr>';
        contentString += '      <th>Work Location</th>';
        contentString += '      <td> <b>:</b> ' + empWorkLocation + '</td>';
        contentString += '  </tr>';
        contentString += '  <tr>';
        contentString += '      <th>Current Location</th>';
        contentString += '      <td> <b> :</b> <span id="infoCurrentLocation">' + empCurrentLocation + '</span></td>';
        contentString += '  </tr>';
        contentString += '  <tr>';
        contentString += '      <th>Current Time</th>';
        contentString += '      <td> <b>:</b> ' + empCurrentTime + '</td>';
        contentString += '  </tr>';
        contentString += '  <tr>';
        contentString += '      <th>Shift</th>';
        contentString += '      <td> <b>:</b> ' + empShift + '</td>';
        contentString += '  </tr>';
        contentString += '</table>';

        infowindow = new google.maps.InfoWindow({
            content: contentString
        });

        marker = new google.maps.Marker({
            position: latLng,
            title: 'Current Location',
            map: map,
            draggable: false
        });

      //  marker.addListener('click', function() {
            infowindow.open(map, marker);
       // });
    }

    // Onload handler to fire off the app.
    google.maps.event.addDomListener(window, 'load', renderMapMultiple);

    google.maps.event.addDomListener(window, 'load', renderMapMultiple1);

     // show all employees 
    function renderMapMultiple(){
        latLng = new google.maps.LatLng( lat, lng );
        var locations = JSON.parse($('#mapEmpData').html());
        console.log(locations);
        var map = new google.maps.Map(document.getElementById('mapCanvas'), {
          zoom: 20,
          center: latLng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infowindow = new google.maps.InfoWindow();

        var marker, i;

        for (i = 0; i < locations.length; i++) { 
          marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map
          });
          var empId = locations[i][0];
          var infowindowData = $('#infowindowEmp'+empId).html();
          google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
              infowindow.setContent(locations[i][0]);
              infowindow.open(map, marker);
            }
          })(marker, i));
        }
    }

    // another tab location

    function renderMapMultiple1(){
        latLng = new google.maps.LatLng( lat, lng );
        var locations = JSON.parse($('#mapEmpData').html());
        console.log(locations);
        var map = new google.maps.Map(document.getElementById('mapCanvas1'), {
          zoom: 20,
          center: latLng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infowindow = new google.maps.InfoWindow();

        var marker, i;

        for (i = 0; i < locations.length; i++) { 
          marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map
          });
          var empId = locations[i][0];
          var infowindowData = $('#infowindowEmp'+empId).html();
          google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
              infowindow.setContent(locations[i][0]);
              infowindow.open(map, marker);
            }
          })(marker, i));
        }
    }
    // update address
    

    $(".emp-name").on("click", function(){
        var elmnt = $(this);
        empId = $(this).attr("data-emp-id");
        $.ajax({
            url : "{{ route('api.employees.index') }}/" + empId,
            dataType : 'JSON',
            beforeSend : function(){
                $("#overlay").show();
            },
            complete : function(){
                $("#overlay").hide();
            },
            success : function(response){
                if( response.success )
                {
                    employee = response.data.employee;

                    // makes employee name as selected
                    $(".emp-name").removeClass('active');
                    elmnt.addClass('active');

                    if( employee.current_location )
                    {
                        lat = employee.current_location.lat;
                        lng = employee.current_location.lng;

                        // setting description content
                        $("#EmpName").html( employee.name + ' ' + employee.surname );
                        empName = employee.name + ' ' + employee.surname;
                        $("#EmpWorkLocation").html( employee.work_location );
                        empWorkLocation = employee.work_location;
                        $("#EmpCurrentTime").html( moment( employee.current_location.created_at ).format('LLL') );
                        empCurrentTime = moment( employee.current_location.created_at ).format('LLL');
                        $("#EmpShift").html( employee.shift_type );
                        empShift = employee.shift_type;
                        
                        $(".list-unstyled li").show();
                    }
                    else
                    {
                        lat = null;
                        lng = null;
                        $(".list-unstyled li").hide();
                    }
                    renderMap();
                }
            },
            error : function(){
                alert("Some error occurred. Please reload the page and try again.");
            }
        });
    });
    
</script>

<!--<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>-->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<!-- //////////////////////////////////////////////////////////////////////////////////// -->
<!-- //////////////////////////////////// Locations ///////////////////////////////////// -->
<!-- //////////////////////////////////////////////////////////////////////////////////// -->
<script type="text/javascript">
    var dateRangePicker_minDate = moment().subtract(6, 'd').format('YYYY-MM-DD');
    var dateRangePicker_maxDate = moment().format('YYYY-MM-DD');
    var locations_map, locations_marker, locations_employeeId, locations_path, locations_latLng = [];
    var mapPlaySpeed = 500, locations_iteration = 0, paused = false, stopped = false, playing = false;

    $( function(){

    	$(".daterangepickr").daterangepicker({
            // autoUpdateInput: false,
            locale: {
              format: 'YYYY-MM-DD'
            },
            startDate : moment().subtract(6, 'd').format('YYYY-MM-DD'),
            endDate : moment().format('YYYY-MM-DD'),
            minDate : moment().subtract(6, 'd').format('YYYY-MM-DD'),
            maxDate : moment().format('YYYY-MM-DD')
        }, 
        function(start, end, label) {
            // alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });

        $('.daterangepickr').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
            dateRangePicker_minDate = picker.startDate.format('YYYY-MM-DD');
            dateRangePicker_maxDate = picker.endDate.format('YYYY-MM-DD');
            stopMap();
            renderLocationsMap();
        });

        $('.daterangepickr').on('cancel.daterangepicker', function(ev, picker) {
            // $(this).val('');    
        });

    } );

    function renderLocationsMap()
    {
        if( locations_employeeId )
        {
            $.ajax({
                url  : '{{ route("ajax.employees.current_locations") }}',
                data : {
                    employeeId : locations_employeeId,
                    minDate    : dateRangePicker_minDate,
                    maxDate    : dateRangePicker_maxDate
                },
                dataType : 'JSON',
                complete : function(){
                    // 
                },
                success : function(response){
                    // console.log(response);
                    if( response.success )
                    {
                        locations = response.data.employee.current_locations;
                        
                        locations_latLng = [];
                        if( locations.length )
                        {
                            for( i = 0; i < locations.length; i++ )
                            {
                                locations_latLng[i] = {lat : parseFloat(locations[i].lat), lng : parseFloat(locations[i].lng)};
                            }
                        }
                        else
                        {
                            locations_latLng[0] = {lat : 0, lng : 0};
                        }
                            
                        latLng = new google.maps.LatLng( locations_latLng[0].lat, locations_latLng[0].lng );

                        locations_map = new google.maps.Map(document.getElementById('locationsMapCanvas'), {
                            zoom: 14,
                            center: latLng,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        });

                        playMap();
                        
                    }
                },
                error : function(){
                    // 
                }
            });
        }
    }

    function moveMarker(map, marker, lat, lon) {
        marker.setPosition(new google.maps.LatLng(lat, lon));
        map.panTo(new google.maps.LatLng(lat, lon));
    }

    function playMap()
    {
    	if( locations_iteration == (locations_latLng.length-1) )
    	{
    		stopMap();
    	}

    	paused  = false;
    	stopped = false;
    	/*if( locations_latLng.length )
    	{*/
    		if( locations_path == undefined )
    		{
				locations_path = new google.maps.Polyline({
		            path: [],
		            geodesic: true,
		            strokeColor: '#FF0000',
		            strokeOpacity: 1.0,
		            strokeWeight: 2,
		            map:locations_map
		        });
    		}
    		
    		if( locations_marker == undefined )
	    	{
				var icon = new google.maps.MarkerImage("http://maps.google.com/mapfiles/ms/micons/blue.png");
		        locations_marker = new google.maps.Marker({map:locations_map,icon:icon});
	    	}

    		for( var i=locations_iteration; i<(locations_latLng.length-1); i++ )
    		{
		        setTimeout(function (locations_latLng)
                {
	                if( !paused && !stopped )
	                {
	                	locations_iteration++;
	                    locations_path.getPath().push(new google.maps.LatLng(locations_latLng.lat, locations_latLng.lng));
	                    moveMarker(locations_map, locations_marker, locations_latLng.lat, locations_latLng.lng);
	                }

                }, mapPlaySpeed * i, locations_latLng[i]);
    		}
    	// }
    }

    function pauseMap() {
    	paused = true;
    }

    function stopMap() {
    	stopped = true;
    	locations_iteration = 0;

    	locations_path.setMap(null);
    	locations_path = undefined;
    	
    	locations_marker.setMap(null);
    	locations_marker = undefined;
    }

    function backwardMap() {
    	// if( paused || stopped )
    	// {
    		if( locations_iteration > 0 )
    		{
    			locations_path.getPath().pop(new google.maps.LatLng(locations_latLng[locations_iteration].lat, locations_latLng[locations_iteration].lng));
		    	locations_iteration--;
		        moveMarker(locations_map, locations_marker, locations_latLng[locations_iteration-1].lat, locations_latLng[locations_iteration-1].lng);
    		}
    	// }
    }

    function forwardMap() {
    	// if( paused || stopped )
    	// {
	    	if( locations_path == undefined )
			{
				locations_path = new google.maps.Polyline({
		            path: [],
		            geodesic: true,
		            strokeColor: '#FF0000',
		            strokeOpacity: 1.0,
		            strokeWeight: 2,
		            map:locations_map
		        });
			}
			
			if( locations_marker == undefined )
	    	{
				var icon = new google.maps.MarkerImage("http://maps.google.com/mapfiles/ms/micons/blue.png");
		        locations_marker = new google.maps.Marker({map:locations_map,icon:icon});
	    	}

	    	if( locations_iteration < (locations_latLng.length-1) )
	    	{
		        locations_path.getPath().push(new google.maps.LatLng(locations_latLng[locations_iteration].lat, locations_latLng[locations_iteration].lng));
		        moveMarker(locations_map, locations_marker, locations_latLng[locations_iteration].lat, locations_latLng[locations_iteration].lng);
		    	locations_iteration++;
	    	}
    	// }
    }

    function fastBackwardMap() {
    	// body...
    }

    function fastForwardMap() {
    	// body...
    }


    $("#chooseDevice").on('change', function(){
        locations_employeeId = $(this).val();
        locations_employeeId = $.trim(locations_employeeId);
        if( locations_employeeId )
        {
            renderLocationsMap();
        }
        else
        {
            stopMap();
            $("#locationsMapCanvas").html('');
        }
    });
	
</script>


<!-- //////////////////////////////////////////////////////////////////////////////////// -->
<!-- ////////////////////////////////////// Reports ///////////////////////////////////// -->
<!-- //////////////////////////////////////////////////////////////////////////////////// -->

<script type="text/javascript">

    var reports_dataTable;

    $( function() {

	

		$('#tableReports1').DataTable({  "order": [[ 1, "desc" ]],"pageLength": 50,"aoColumnDefs" : [   
{
  'bSortable' : false,  
  'aTargets' : [ 0,1,2,3,4 ]
}]});
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
			setDate:'<?php echo  isset($_GET['selected_date'])&&!empty($_GET['selected_date'])?$_GET['selected_date']:(isset($_GET['reportStartDate']))?'':date("Y-m-d");?>'
        }).on('changeDate', function(e){
            date = $(this).val();
			window.location.href="?selected_date="+date;
           // fetchReports( date );
        });

	$("#fromDate").datepicker({
        format: 'yyyy-mm-dd'	 
    });
    $("#toDate").datepicker({
        format: 'yyyy-mm-dd'
    });
		
		
        //$("#reportDate").val( moment().format('YYYY-MM-DD') );

        $(document).on('click', '#tableReports td:first-child', function(){
        	$("#tableReports td:first-child").css('font-weight', 'normal');
        	$(this).css('font-weight', 'bold');
        	employeeId = $(this).find('span').attr('employee-id');
			var selected_date = $("#reportDate").val();
			var start_date = $("#fromDate").val();
			var end_date = $("#toDate").val();

        	$.ajax({
        		url : '{{ route("ajax.employees.working_hours") }}',
        		data : { employee_id : employeeId,selected_date:selected_date,start_date:start_date,end_date:end_date },
        		dataType : 'JSON',
        		success : function(response){
                    console.log(response);
					var HTML ='';
					if(response.data.todaySlotsRecords>0){
						$.each(response.data.todaySlots,function(key,val){
							HTML += ' <tr><td>'+val.empName+'</td><td>'+val.clock_in_time+'</td><td>'+val.clock_in_location+'</td><td>'+val.clock_out_time+'</td><td>'+val.clock_out_location+'</td></tr>';
							
						});
						$("#data_div").html(HTML);
					}else{
						$("#data_div").html('<tr><td colspan="4">No record found!</td></tr>');
					}
					
                    $("#dayHours").html( response.data.day_hours );
                    $("#weekHours").html( response.data.week_hours );
                    $("#monthHours").html( response.data.month_hours );
        			$("#yearHours").html( response.data.year_hours );
        		}
        	});
        });
    } );
 
 
$("body").on("click","#searchByDateSubmit",function(){
	
	$(".formSearchTimeframeDates").validate({
		rules:{
			reportStartDate:{
				required:true
			},
			reportEndDate:{
				required:true
			}
		},
		messages:{
			reportStartDate:{
				required:"Please select start date"
			},
			reportEndDate:{
				required:"Please select end date"
			}
		} 
	 
		
	})
	
	 
	
	
	
}); 
    function fetchReports(date,fromDate,toDate)
    {
		
        if( reports_dataTable == undefined )
        {
			 
            reports_dataTable = $('#tableReports').DataTable( {
                "searching"  : false,
                "processing" : true,
                "serverSide" : true,
				
				"order": [[ 1, "desc" ]],
                // 'columnDefs' : [ { orderable: false, targets: [5] } ],
                /*'columnDefs' : [ {
                	"targets": [0],//index of column starting from 0
				    // "data": "column_name", //this name should exist in your JSON response
				    "render": function ( data, type, full, meta ) {
				    	console.log(data);
				      return '<span employee-id="">' + data + '</span>';
				    }
                } ],*/
                "ajax":{
                    url   : '{{ route("ajax.employees.reports") }}?date=' + date+"&fromDate="+fromDate+"&toDate="+toDate, // json datasource
                    error : function(res){  // error handling
                        console.log('error');
                        console.log(res);
                        $(".employee-grid-error").html("");
                        $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#employee-grid_processing").css("display","none");
						

     
                    }
					
                } 
            });
        }
        else
        {
		 reports_dataTable.ajax.url('{{ route("ajax.employees.reports") }}?date=' + date).load();
			
        }
    }
	
	$(document).ready(function(){
		var data = '<?php echo  isset($_GET['selected_date'])&&!empty($_GET['selected_date'])?$_GET['selected_date']:''?>';
		var data1 = '<?php echo  isset($_GET['reportStartDate'])&&!empty($_GET['reportStartDate'])?$_GET['reportStartDate']:''?>';
		if(data!=''||data1!=''){
		  
					$(".top-links").removeClass('active');
					
					$(".div-content").hide();
					 $(".top-links[link-to=reports]").addClass('active');
					 
					$("#divReports").show();
					date = $("#reportDate").val();
					fromDate = $("#fromDate").val();
					toDate = $("#toDate").val();
					fetchReports(date,fromDate,toDate);
				 
		}
	});

	
</script>
<style type="text/css">
#divCurrentLocation .col-md-3{
    max-height: 545px;
}

.widget .x_panel{
    max-height: 335px;
    overflow: auto;
}
input:focus,textarea:focus{
outline: none !important;
}

input.error, textarea.error{
outline:red groove thin;
}
table#tableReports1 {
    width: 100%!important;
}
</style>
@endsection

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
        {{ Auth::user()->employees ? ( !(Auth::user()->employees[0]->current_location) ? 'display:none;' : '' ) : ''  }}
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
                                <a href="javascript:void(0);" class="top-links active" style="padding: 0 12px 0 10px;">Locations</a> |
                                <a href="javascript:void(0);" class="top-links" style="padding: 0 10px 0 12px;">Reports</a>
                            </div>
                        </div>
                    </div>

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
                                                @foreach( Auth::user()->employees as $employee )
                                                <li>
                                                    <p>
                                                        <span class="month">
                                                            <i class="fa fa-user" aria-hidden="true"></i>
                                                            &nbsp;&nbsp;
                                                            <a href="javascript:void(0);" class="emp-name {{ $loop->first ? 'active' : '' }}" data-emp-id="{{ $employee->id }}">{{ $employee->name . ' ' . $employee->surname }}</a>
                                                        </span>
                                                    </p>
                                                </li>
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
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.js"></script>

<script type="text/javascript">

    var lat, lng, map, marker, infowindow;
    var empName, empWorkLocation, empCurrentLocation, empCurrentTime, empShift;
    var geocoder = new google.maps.Geocoder();

    lat = {!! Auth::user()->employees[0]->current_location ? Auth::user()->employees[0]->current_location->lat : 'null' !!};
    lng = {!! Auth::user()->employees[0]->current_location ? Auth::user()->employees[0]->current_location->lng : 'null' !!};

    empName         = '{{ @Auth::user()->employees[0]->name . ' ' . @Auth::user()->employees[0]->surname }}';
    empWorkLocation = '{{ @Auth::user()->employees[0]->work_location }}';
    empCurrentTime  = '{{ @date('F d, Y H:i:s', strtotime(Auth::user()->employees[0]->current_location->time)) }}';
    empShift        = '{{ @Auth::user()->employees[0]->shift_type }}';

    function setCurrentLocation(pos)
    {
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
            zoom: 15,
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

        marker.addListener('click', function() {
            infowindow.open(map, marker);
        });
    }

    // Onload handler to fire off the app.
    google.maps.event.addDomListener(window, 'load', renderMap);

    // update address
    setInterval(function(){
        $(document).find("#infoCurrentLocation").html( empCurrentLocation );
    }, 500);
    
</script>

<script type="text/javascript">
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
                console.log(response);
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
                        $("#EmpWorkLocation").html( employee.work_location );
                        $("#EmpCurrentTime").html( moment( employee.current_location.time ).format('LLL') );
                        $("#EmpShift").html( employee.shift_type );
                        
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
@endsection

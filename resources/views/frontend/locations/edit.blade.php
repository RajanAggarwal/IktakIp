@extends('layouts.front')

@section('content')
<style>
    .alert{
        margin-top:57px;
        /*margin-bottom: 0;*/
    }
    #mapCanvas {
        width: 100%;
        height: 550px;
        float: left;
    }
    #infoPanel {
        float: left;
        margin-left: 10px;
    }
    #infoPanel div {
        margin-bottom: 5px;
    }
</style>
<div style="min-height: 707px;" class="right_col" role="main">
    @include('notifications')
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">Dashboard</div>
				<div class="panel-body">
				    <div class="col-md-9">
				        @include('notifications')
					   <div id="mapCanvas">
					   </div>
					</div>
					<div class="col-md-3">
                        <div id="infoPanel">
                            <form id="locationForm" method="post" action="{{ route('locations.update', $location->id) }}">
                                {{ csrf_field() }}
                                {{ method_field('PATCH') }}

                                <b>Marker status:</b>
                                <div id="markerStatus"><i>Click and drag the marker.</i></div>
                                <br />

                                <b>Location Name:</b>
                                <input type="text" name="location_name" placeholder="Enter Location Name" value="{{ $location->location_name }}" class="form-control" id="location_name">
                                <br />

                                <b>Latitude:</b>
                                <input type="text" id="latitude" name="latitude" value="{{ $location->latitude }}"  class="form-control" readonly="true">
                                <br />

                                <b>Longitude:</b>
                                <input type="text" id="longitude" name="longitude" value="{{ $location->longitude }}"  class="form-control" readonly="true">
                                <br />

                                <b>Address:</b>
                                <textarea rows="5" id="address" name="address" value="{{ $location->location_address }}"   class="form-control" readonly="true"></textarea>
                                <br />

                                <input type="button" name="add-location" id="saveLocation" value="Udpate" class="btn btn-success pull-right" onclick="checkLocationForm()">
                            </form>
					   </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
    function checkLocationForm()
    {
        if(jQuery('#location_name').val()=='')
        { 
    		jQuery.toaster({ priority : 'danger', title : 'Error', message : 'Please enter Location name'});
            return false;
        }
        else
        {
            jQuery('#locationForm').submit();
        }	
    }

    var geocoder = new google.maps.Geocoder();

    function geocodePosition(pos)
    {
        geocoder.geocode({
            latLng: pos
        }, function(responses) {
            if (responses && responses.length > 0)
            {
                updateMarkerAddress(responses[0].formatted_address);
            }
            else
            {
                updateMarkerAddress('Cannot determine address at this location.');
            }
        });
    }

    function updateMarkerStatus(str)
    {
        document.getElementById('markerStatus').innerHTML = str;
    }

    function updateMarkerPosition(latLng)
    {
        document.getElementById('latitude').value = latLng.lat();
        document.getElementById('longitude').value = latLng.lng();
    }

    function updateMarkerAddress(str)
    {
        document.getElementById('address').value = str;
    }

    function initialize()
    {
        var latLng = new google.maps.LatLng({{ $location->latitude }}, {{ $location->longitude }});
        var map = new google.maps.Map(document.getElementById('mapCanvas'), {
            zoom: 15,
            center: latLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var marker = new google.maps.Marker({
            position: latLng,
            title: 'Point A',
            map: map,
            draggable: true
        });

        // Update current position info.
        updateMarkerPosition(latLng);
        geocodePosition(latLng);

        // Add dragging event listeners.
        google.maps.event.addListener(marker, 'dragstart', function() {
            updateMarkerAddress('Dragging...');
        });

        google.maps.event.addListener(marker, 'drag', function() {
            updateMarkerStatus('Dragging...');
            updateMarkerPosition(marker.getPosition());
        });

        google.maps.event.addListener(marker, 'dragend', function() {
            updateMarkerStatus('Drag ended');
            geocodePosition(marker.getPosition());
        });
    }

    // Onload handler to fire off the app.
    google.maps.event.addDomListener(window, 'load', initialize);
</script>
@endsection

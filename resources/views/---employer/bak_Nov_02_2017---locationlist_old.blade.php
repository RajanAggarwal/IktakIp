@extends('layouts.front')

@section('content')
 <style>
  #mapCanvas {
    width: 800px;
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
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">Locations List</div>
				<div class="panel-body">
				<div class="col-md-9">
				@if(Session::has('flash_message_error'))
					<div role="alert" class="alert alert-danger alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true">×</span></button> <strong>Error!</strong> {!! session('flash_message_error') !!} </div>
				@endif
				@if(Session::has('flash_message_success'))
					<div role="alert" class="alert alert-success alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" style="text-indent: 0;" class="close" type="button"><span aria-hidden="true">×</span></button> <strong>Success!</strong> {!! session('flash_message_success') !!} </div>
				@endif 
				</div> 
 
				 <table style="width:100%">
				  <tr>
					<th>Sno.</th>
					<th>Location Name</th>
					<th>Latitude</th>
					<th>Longitude</th>
					<th>Address</th>
					<th>Employer ID</th>
					<th></th>
				  </tr>
				@foreach($locations as $location)
				 <tr>
					<td>{{ $loop->iteration }}</td>
					<td>{{ $location['location_name'] }}</td>
					<td>{{ $location['latitude'] }}</td>
					<td>{{ $location['longitude'] }}</td>
					<td>{{ $location['location_address'] }}</td>
					<td>{{ $location['employer_id'] }}</td>
					<td>
						<a href="{{  url('locations')  }}/{{ $location['id'] }}"><button class="btn btn-success pull-right">Edit</button> </a>
							
						<form class="deleteForm" action="{{  url('delete_locations')  }}/{{ $location['id'] }}" method="POST">
							{{ csrf_field() }}
							{{ method_field('DELETE') }}

							<button class="btn btn-success pull-right">Delete</button>
						</form> 
					</td>
				  </tr>        
				@endforeach
				 
				</table> 
				</div>
			</div>
		</div>
	</div>
</div> 
<script type="text/javascript">
jQuery('.deleteForm').submit(function confirmDelete(){ 
	if(confirm('Delete location? ') == true){
		return true;
	}else{
		return false;
	}
});
</script>
@endsection

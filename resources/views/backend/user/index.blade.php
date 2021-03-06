@extends('layouts.main')

@section('content')
<div class="right_col" role="main">
  <div class="">
  <div class="page-title">
      <div class="title_left">
        <h3>Employers Management (
		@if(isset($_GET['filter'])&& $_GET['filter']=='inactiveusers')
			Inactive
		@elseif(isset($_GET['filter'])&& $_GET['filter']=='activeusers')
			Active
		@elseif(isset($_GET['filter'])&& $_GET['filter']=='deletedusers')
		Deleted
		@else
			
			All 	
		@endif
		
		
		)</h3>
      </div>

      <div class="title_right">
        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
            <a href="{{ url('admin/add_employer')}}" class="btn btn-success pull-right">Add</a>
        </div>
      </div>
    </div>

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Employers List<small></small></h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>

          <div class="x_content">

            <!-- <p>Add class <code>bulk_action</code> to table for bulk actions options on row select</p> -->

            <div class="table-responsive">
              <table id="usersTable" class="table table-striped table-bordered" cellspacing="0">
                    <thead>  
                    <tr>   
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created Date</th>
                        <th>LogIn as Employer</th>
                        <th>Action</th>
                    </tr>
                   
                </thead>
                <tbody>
                </tbody>
            </table>
           </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery/jquery-1.4.4.min.js"></script>

 <?php  $filter  = isset($_GET['filter'])&&!empty($_GET['filter'])?$_GET['filter']:'';?> 
<script type="text/javascript" language="javascript" >
    $(document).ready(function() { 
        var proto=window.location.protocol;
        var host=window.location.host;
        var ajax_url=proto+"//"+host+"/public/";
        var dataTable = $('#usersTable').DataTable( {
            "processing": true,
            "serverSide": true,
            'columnDefs': [ { orderable: false, targets: [0,4,5] } ],
            "ajax":{
               // url :ajax_url+'admin/get_employers?{{ $filter_by }}=1', // json datasource
                url :'{{url("admin/get_employers?filter=".$filter )}}', // json datasource
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    console.log('error');
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
 
                }
            }
        } );
    } );
	
	
	var filterVal = '<?php echo isset($_GET['filter'])&& $_GET['filter']?$_GET['filter']:'';?>';
	$(document).ready(function(){
		
			setTimeout(function(){

			$(".leftmenuClass").removeClass("current-page");
			if(filterVal==''){
			$(".listClass").addClass("current-page");

			}else{
			$("."+filterVal).addClass("current-page");

			}
			},100);
		
		
	});
	
	
	
</script>

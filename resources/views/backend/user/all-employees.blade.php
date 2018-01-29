@extends('layouts.main')

@section('content')
<div class="right_col" role="main">
 
  <div class="">
  <div class="page-title">
      <div class="title_left">
        <h3>Employees Management </h3>
      </div>

      
    </div>

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Employees List<small></small></h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
			<label>Search by : </label>
			<select class="form-control" id="searchFilter" >
		  <option value="all">All Employees</option>
		  <option value="active_emp" <?php echo isset($_GET["filter"])&& $_GET["filter"]=="active_emp" ? "selected='selected'":"";?> >Active Employees</option>
		  <option value="inactive_emp" <?php echo isset($_GET["filter"])&& $_GET["filter"]=="inactive_emp" ? "selected='selected'":"";?>>Inactive Employees</option>
		  <option value="deleted_emp" <?php echo isset($_GET["filter"])&& $_GET["filter"]=="deleted_emp" ? "selected='selected'":"";?>>Deleted Employees</option>
		  </select>
          </div>

          <div class="x_content">

		  
		  
		  
            <!-- <p>Add class <code>bulk_action</code> to table for bulk actions options on row select</p> -->

            <div class="table-responsive">
		  
              <table id="employeesTable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0">
                <thead>  
                  <tr>   
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Job title</th>
                    <th>Mobile No</th>
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
        var empId = 0;
        var dataTable = $('#employeesTable').DataTable( {
            "processing": true,
            "serverSide": true,
            'columnDefs': [ { orderable: false, targets: [5] } ],
            "ajax":{
               // url :ajax_url+'admin/get_employees', // json datasource
                url :'{{url("admin/get_employees?filter=".$filter)}}', // json datasource
                data : {empId:empId},
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    console.log('error');
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");
 
                }
            }
        } );
		
		$("body").on("change","#searchFilter",function(){
			var me = $(this);
			var value = me.val();
			window.location.href= '?filter='+value;
			
			
		});
		
    } );
</script>

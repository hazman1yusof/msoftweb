@extends('layouts.main')

@section('title', 'Stock Balance')

@section('style')
	body{
		background: #00808024 !important;
	}
	.container.mycontainer{
		padding-top: 5%;
	}
	.mycontainer .panel-default {
	    border-color: #9bb7b7 !important;
	}
	.mycontainer .panel-default > .panel-heading {
	    background-image: linear-gradient(to bottom, #b4cfcf 0%, #c1dddd 100%) !important;
    	font-weight: bold;
	}
	.mycontainer .mybtnpdf{
		background-image: linear-gradient(to bottom, #ffbbbb 0%, #ffd1d1 100%) !important;
    	color: #af2525;
    	border-color: #af2525;
    	margin-bottom: 5px;
	}
	.mycontainer .mybtnxls{
		background-image: linear-gradient(to bottom, #a0cda0 0%, #b3d1b3 100%) !important;
	    color: darkgreen;
	    border-color: darkgreen;
    	margin-bottom: 20px;
	}
	.mycontainer .btnvl {
	  	border-left: 1px solid #386e6e;
	    width: 0px;
	    padding: 0px;
	    height: 32px;
	    cursor: unset;
	    margin: 0px 7px;
	}
	legend{
		margin-bottom: 5px !important;
		font-size: 12px !important;
		font-weight:bold;
	}
	.btnform .btn{
		width: -webkit-fill-available !important;
	}
@endsection('style')

@section('body')
<div class="container mycontainer">
  <div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">Import Excel</div>
				<div class="panel-body">
					<form class='form-horizontal' style='width:99%' id='formdata' method="post" action="./form" enctype="multipart/form-data">
						<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
						<input type="hidden" name="action" value="import_excel">
						<input type="recno" name="recno" value="{{Request::get('recno')}}">
    				<input type="file" name="file" id="file" accept=".xlsx">
	    			<button type="button" id="submit_" class='btn btn-sm btn-info pull-right'>Upload</button>
	    			<button type="submit" id="submit" class='btn btn-sm btn-info pull-right' style="display:none;"></button>
					</form>
				</div>
			</div> 
		</div>
  </div> 
</div>
		
@endsection


@section('scripts')
<script>
	$('#submit_').click(function(){
		if (confirm('Are you sure to upload this file?') == true) {
	    $('#submit').click();
	  }
	});
</script>

@endsection
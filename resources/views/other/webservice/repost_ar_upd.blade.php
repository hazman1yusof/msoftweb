@extends('layouts.main')

@section('title', 'Repost AR')

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
	<div class="col-md-9">
		<div class="panel panel-default">
			<div class="panel-heading">Repost AR</div>
			<div class="panel-body">
				<form class='form-horizontal' style='width:99%' id='formdata'>
					<input id="action" name="action" type="hidden" value='repost_ar_upd'>
					{{csrf_field()}}
					<div class="form-group">
						<div class="col-md-4">
					  	<label class="control-label" for="Scol">Source</label> 
							<input id="source" name="source" type="text" class="form-control input-sm" autocomplete="off">
			      </div>
						<div class="col-md-4">
					  	<label class="control-label" for="Scol">Trantype</label> 
							<input id="trantype" name="trantype" type="text" class="form-control input-sm" autocomplete="off">
			      </div>
						<div class="col-md-4">
					  	<label class="control-label" for="Scol">Auditno</label> 
							<input id="auditno" name="auditno" type="text" class="form-control input-sm" autocomplete="off">
			      </div>
			    </div>
				</form>
			</div>
		</div> 
	</div>

	<div class="col-md-3">
		<div class="panel panel-default" style="height: 140px;">
			<div class="panel-body">
				<div class='col-md-12 btnform' style="padding:0px">
				 <fieldset>
				  <legend>Repost :</legend>
					<button id="repost" name="repost" type="button" class="mybtn btn btn-sm btn-primary"> Repost </button>
				  </fieldset>
				</div>
			</div>
		</div>
  </div> 
</div>
		
@endsection


@section('scripts')

	<script src="js/other/webservice/repost_ar_upd.js"></script>

@endsection
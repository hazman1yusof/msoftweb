<!DOCTYPE html>

<html lang="en">
<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<link rel="stylesheet" href="plugins/bootstrap-3.3.5-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="plugins/bootstrap-3.3.5-dist/css/bootstrap-theme.css">
	<link rel="stylesheet" href="plugins/bootgrid/css/jquery.bootgrid.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="plugins/font-awesome-4.4.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="plugins/jquery-ui-1.12.1/jquery-ui.min.css">

	<style type="text/css" class="init">
		td.details-control {
			background: url('../../../../assets/img/details_open.png') no-repeat center center;
			cursor: pointer;
		}
		tr.details td.details-control {
			background: url('../../../../assets/img/details_close.png') no-repeat center center;
		}

		.modal-header {
			min-height: 16.42857143px;
			padding: 5px;
			border-bottom: 1px solid #e5e5e5;
		}
		.modal-body {
			position: relative;
			padding: 10px;
		}

		.form-group{
			margin-bottom: 5px;
		}
		
		.form-mandatory{
			background-color: lightyellow;
		}
		
		.form-disabled{
			background-color: #DDD;
			color: #999;
		}
		
		.modal-open {
		  overflow: scroll;
		}
		.justbc{
			background-color: #dff0d8 !important;
		}
		label.error{
			color: rgb(169, 68, 66);
		}
		#mykad_reponse{
			color: rgb(169, 68, 66);
			font-weight: bolder;
		}
		.addressinp{
			margin-bottom: 5px;
		}
	</style>

</head>


<body class="header-fixed">
	<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
    <div class="wrapper">
        	<input name="pattype" id="pattype" type="hidden" value="{{request()->get('pattype')}}">
        	<input name="listtype" id="listtype" type="hidden" value="{{request()->get('listtype')}}">
        <div id="info"></div>

		<div class="panel">
			<!-- <button id="patientBox" type="button" class="btn btn-success btn-md" ><span class="glyphicon glyphicon-inbox" aria-hidden="true"> </span> Register New</button> -->
			&nbsp;&nbsp;
			<button id="btn_upload" type="button" class="btn btn-success btn-md" ><span class="glyphicon glyphicon-credit-card" aria-hidden="true"> </span> Upload</button>
			<button id="btn_preview" type="button" class="btn btn-success btn-md" ><span class="glyphicon glyphicon-credit-card" aria-hidden="true"> </span> Preview</button>
			<!-- &nbsp;&nbsp;
			<button type="button" class="btn btn-success btn-md" disabled ><span class="glyphicon glyphicon-import" aria-hidden="true"> </span> Import File</button> -->
		</div>
		<div class="panel">
			<table id="grid-command-buttons" class="table table-condensed table-hover table-striped" width="100%" data-ajax="true">
                <thead>
                <tr>
                	<th data-column-id="mrn" data-formatter="col_add" data-width="5%">#</th>
                    <th data-column-id="MRN" data-type="numeric" data-formatter="col_mrn" data-width="10%">MRN No</th>
                    <th data-style="dropDownItem" data-column-id="Name" data-formatter="col_name" data-width="30%">Name</th>
                    <th data-column-id="Newic" data-width="15%">New IC</th>
                    <th data-column-id="Oldic" data-width="10%">Old IC</th>
                    <th data-column-id="DOB" data-formatter="col_dob" data-width="12%">Birth Date</th>
                    <th data-column-id="Sex" data-width="6%">Sex</th>
					<th data-column-id="Staffid" data-width="15%">Patient Staff ID</th>
                    <th data-column-id="col_age" data-formatter="col_age" data-sortable="false" data-width="8%">Age</th>

<!--                    <th data-column-id="edit_cmd" data-formatter="edit_cmd" data-sortable="false">Commands</th>-->
<!--                    <th data-column-id="episode_cmd" data-formatter="episode_cmd" data-sortable="false">Commands</th>-->
				</tr>
				</thead>

			</table>


		</div>

		<input id="app_url" name="app_url" type="hidden" value="{{ env('APP_URL') }}">

		<div id="previewModal" title="Data Preview" >

			<table class="table table-hover table-bordered" id='tablePreview'>
				<thead>
					<tr>
						<th>ID</th>
						<th>Date</th>
						<th>File Preview</th>
						<th>MRN</th>
						<th>Add User</th>
						<th>Add Date</th>
						<th>Download</th>
						<th>type</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>

		</div>

		<div id="episodeModal" title="Episode List" >

			<table class="table table-hover table-bordered" id='episodeList'>
				<thead>
					<tr>
						<th>Episode No.</th>
						<th>Episode Type</th>
						<th>Register date</th>
						<th>MRN</th>
						<th>Upload</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>

		</div>


		@include('hisdb.pat_enq.mdl_patient')
		@include('hisdb.pat_enq.mdl_episode')
		@include('hisdb.pat_enq.itemselector')

	<script type="text/ecmascript" src="plugins/jquery-3.2.1.min.js"></script> 
	<script type="text/ecmascript" src="plugins/jquery-migrate-3.0.0.js"></script>
    <script type="text/ecmascript" src="plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <script type="text/javascript">$.fn.modal.Constructor.prototype.enforceFocus = function() {};</script>

    <script type="text/ecmascript" src="plugins/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <script type="text/ecmascript" src="plugins/numeral.min.js"></script>
	<script type="text/ecmascript" src="plugins/moment.js"></script>

	<script type="text/javascript" src="plugins/datatables/js/jquery.datatables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-validator/jquery.validate.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-validator/additional-methods.min.js"></script>

	<script type="text/javascript" src="plugins/bootgrid/js/jquery.bootgrid.js"></script>
	<script type="text/javascript" src="js/myjs/modal-fix.js"></script>
	<script type="text/javascript" src="js/myjs/global.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_enq/landing.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_enq/biodata.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_enq/episode.js"></script>
	
	</div>

</body>
</html>
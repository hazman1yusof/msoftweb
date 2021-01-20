<!DOCTYPE html>

<html lang="en">
<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<link rel="stylesheet" href="plugins/bootstrap-3.3.5-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="plugins/bootstrap-3.3.5-dist/css/bootstrap-theme.css">
	<link rel="stylesheet" href="plugins/jquery-ui-1.12.1/jquery-ui.min.css">
	<link rel="stylesheet" href="plugins/bootgrid/css/jquery.bootgrid.css">
	<!-- <link rel="stylesheet" href="plugins/datatables/css/jquery.dataTables.css"> -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.1.2/css/rowGroup.dataTables.min.css">
	<link rel="stylesheet" href="plugins/font-awesome-4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="plugins/form-validator/theme-default.css" />

    <link rel="stylesheet" href="plugins/css/trirand/ui.jqgrid-bootstrap.css" />

	<style type="text/css" class="init">
		.smallmodal{
			width: 70% !important; margin: auto !important;
		}
		tr.dtrg-group{
			font-size: 15px;
		}
		td.details-control {
			background: url('../../../../assets/img/details_open.png') no-repeat center center;
			cursor: pointer;
		}
		tr.details td.details-control {
			background: url('../../../../assets/img/details_close.png') no-repeat center center;
		}

		.uppercase, .odd, .even{
		  	text-transform:uppercase
		}

		small, input[type=text]{
		  	text-transform:uppercase
		}

		.td_nowhitespace{
			white-space: normal !important;
		}

		.modal {
		  padding: 0 !important;
		}

		.modal .modal-dialog {
			width: 100%;
			height: 100%;
			margin: 0;
			padding: 0;
		}

		.modal .modal-dialog .half {
			width: 50% !important;
			height: 50% !important;
			margin: 0;
			padding: 0;
		}

		.modal .modal-content {
			height: auto;
			min-height: 100%;
			border: 0 none;
			border-radius: 0;
			box-shadow: none;
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

		.modal-backdrop{
			z-index: 99 !important;
			background-color: #fff !important;
			opacity: 1 !important;
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
		.panel-heading.collapsed .fa-angle-double-up,
		.panel-heading .fa-angle-double-down {
			display: none;
		}

		.panel-heading.collapsed .fa-angle-double-down,
		.panel-heading .fa-angle-double-up {
			display: inline-block;
		}

		i.fa {
			cursor: pointer;
			float: right;
			<!--  margin-right: 5px; -->
		}

		.ui-widget-overlay.ui-front{
			z-index: 100 !important;
		}

		.search{
			width: 40% !important;
		}

		.panel-primary {
		    border-color: #5bc0de;
		}

		.panel-primary > .panel-heading {
		    color: #fff;
		    background-color: #5bc0de;
		    border-color: #5BC0DC;
			background-image: linear-gradient(to bottom, #5bc0de 0%, #5bc0de 100%);
		}

	</style>

</head>


<body class="header-fixed">
	<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
    <div class="wrapper">
    	<input type="hidden" id="load_from_addupd" data-info="false" data-oper="edit">
        <input name="epistycode" id="epistycode" type="hidden" value="{{request()->get('epistycode')}}">
        @if (request()->get('epistycode') == 'IP')
        <input name="epistycode2" id="epistycode2" type="hidden" value="DC">
		@else
        <input name="epistycode2" id="epistycode2" type="hidden" value="OTC">
		@endif
        <input name="curpat" id="curpat" type="hidden" value="{{request()->get('curpat')}}">
        <div id="info"></div>

		<div class="panel">
			<button id="patientBox" type="button" class="btn btn-success btn-md" ><span class="glyphicon glyphicon-inbox" aria-hidden="true"> </span> Register New</button>
			&nbsp;&nbsp;
			<button id="btn_mykad" type="button" class="btn btn-success btn-md" ><span class="glyphicon glyphicon-credit-card" aria-hidden="true"> </span> My Kad</button>
		</div>

		@if (request()->get('curpat') == 'true')
		<div class="panel panel-default" style="position: relative;margin: 0 12px 12px 12px">
	        <div class="panel-heading clearfix collapsed" id="toggle_preepis" data-toggle="collapse" data-target="#tabpreepis">

	        <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
	        <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
	        <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
	            <h5><strong>PRE EPISODE</strong></h5>
	        </div> 
	        </div>

	        <div id="tabpreepis" class="panel-collapse collapse">
	        <div class="panel-body form-horizontal">
	            <div id="jqGrid_preepis_c">
	                <div class='col-md-12' style="padding:0 0 15px 0">
	                    <table id="jqGrid_preepis" class="table table-striped"></table>
	                    <div id="jqGridPager_preepis"></div>
	                </div>
	            </div>
	        </div>
            </div>
        </div>
        @endif

		<div class="panel panel-primary" style="position: relative;margin: 0 12px 12px 12px">
			<div  class="panel-heading clearfix">
				<h5><strong>
					@if (request()->get('curpat') == 'true'){{'CURRENT PATIENT'}}
					@else {{'PATIENT LIST'}}
					@endif
				</strong></h5>
			</div>
			<table id="grid-command-buttons" class="table table-condensed table-hover table-striped" width="100%" data-ajax="true">
                <thead>
                <tr>
                	<th data-column-id="mrn" data-formatter="col_add" data-width="5%">#</th>
                    <th data-column-id="MRN" data-type="numeric" data-formatter="col_mrn" data-width="6%">MRN No</th>
                    <th data-style="dropDownItem" data-column-id="Name" data-formatter="col_name" data-width="26%">Name</th>
                    <th data-column-id="q_doctorname" data-width="8%">Doctor</th>
                    <th data-column-id="Newic" data-width="8%">New IC</th>
                    <th data-column-id="telhp" data-width="8%">H/P</th>
                    <th data-column-id="DOB" data-formatter="col_dob" data-width="6%">Birth Date</th>
                    <th data-column-id="Sex" data-width="4%">Sex</th>
					<th data-column-id="Staffid" data-width="6%">Staff ID</th>
                    <th data-column-id="col_age" data-formatter="col_age" data-sortable="false" data-width="5%">Age</th>
					<th data-column-id="commands" data-formatter="commands" data-sortable="false" data-width="8%">Info &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Type</th>
				</tr>
				</thead>

			</table>


		</div>


		@include('hisdb.pat_mgmt.mdl_patient')
		@include('hisdb.pat_mgmt.mdl_episode')
		@include('hisdb.pat_mgmt.itemselector')


		@if (request()->get('epistycode') == 'OP')

		<div class='row' style="position: relative;margin: 0 12px 12px 12px">
			@include('hisdb.nursing.nursing',['page_screen' => "patmast"])
		</div>

		<div class='row' style="position: relative;margin: 0 12px 12px 12px">
			@include('hisdb.doctornote.doctornote')
		</div>

		<div class='row' style="position: relative;margin: 0 12px 12px 12px">
			@include('hisdb.ordcom.ordcom')
		</div>
		@endif

		@if (request()->get('epistycode') == 'IP')
		<div class='row' style="position: relative;margin: 0 12px 12px 12px">
			@include('hisdb.ordcom.ordcom')
		</div>
		@endif
		

	<script type="text/ecmascript" src="plugins/jquery-3.2.1.min.js"></script> 
	<script type="text/ecmascript" src="plugins/jquery-migrate-3.0.0.js"></script>
    <script type="text/ecmascript" src="plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <script type="text/javascript">$.fn.modal.Constructor.prototype.enforceFocus = function() {};</script>
    <script type="text/ecmascript" src="plugins/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script type="text/ecmascript" src="plugins/form-validator/jquery.form-validator.min.js"></script>
    
    <script type="text/ecmascript" src="plugins/trirand/i18n/grid.locale-en.js"></script>
    <script type="text/ecmascript" src="plugins/trirand/jquery.jqGrid.min.js"></script>
    <script type="text/ecmascript" src="plugins/numeral.min.js"></script>
	<script type="text/ecmascript" src="plugins/moment.js"></script>
	<script type="text/javascript" src="js/myjs/utility.js"></script>


	<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-validator/jquery.validate.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-validator/additional-methods.min.js"></script>

	<script type="text/javascript" src="plugins/bootgrid/js/jquery.bootgrid.js"></script>
	<script type="text/javascript" src="js/myjs/modal-fix.js"></script>
	<script type="text/javascript" src="js/myjs/global.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_mgmt/landing.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_mgmt/biodata.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_mgmt/episode.js"></script>

	
	<script type="text/javascript" src="js/hisdb/pat_mgmt/epis_doctor.js"></script>


	@if (request()->get('epistycode') == 'OP')
	<script type="text/javascript" src="js/hisdb/nursing/nursing.js"></script>
	<script type="text/javascript" src="js/hisdb/doctornote/doctornote.js"></script>
	<script type="text/javascript" src="js/hisdb/ordcom/ordcom.js"></script>
	@endif

	@if (request()->get('epistycode') == 'IP')
	<script type="text/javascript" src="js/hisdb/pat_mgmt/epis_bed.js"></script>
	<script type="text/javascript" src="js/hisdb/ordcom/ordcom.js"></script>
	@endif


	<script type="text/javascript" src="js/hisdb/pat_mgmt/epis_nok.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_mgmt/pat_nok.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_mgmt/pat_emr.js"></script>

	</div>

</body>
</html>
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
    <link rel="stylesheet" href="css/pat_enq.css" />

	<style type="text/css" class="init">
	</style>
</head>

<body class="header-fixed">
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
	<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
	<input type="hidden" id="user_billing" value="{{Auth::user()->billing}}">
	<input type="hidden" id="user_nurse" value="{{Auth::user()->nurse}}">
	<input type="hidden" id="user_doctor" value="{{Auth::user()->doctor}}">

    <div class="wrapper">
        <input name="lastrowid" id="lastrowid" type="hidden" value="0">
        <input name="userdeptcode" id="userdeptcode" type="hidden" value="{{$userdeptcode ?? ''}}">
        <input name="userdeptdesc" id="userdeptdesc" type="hidden" value="{{$userdeptdesc ?? ''}}">
		<input type="hidden" name="billtype_def_code" id="billtype_def_code" value="{{$billtype_def_code ?? ''}}">
		<input type="hidden" name="billtype_def_desc" id="billtype_def_desc" value="{{$billtype_def_desc ?? ''}}">
        <input name="lastMrn" id="lastMrn" type="hidden" >
        <input name="lastidno" id="lastidno" type="hidden" >

        <div id="info"></div>

		<div class="panel panel-primary" style="position: relative;margin: 0 12px 12px 12px">
			<div  class="panel-heading clearfix">
				<h5><strong>PATIENT LIST</strong></h5>
			</div>
			<table id="grid-command-buttons" class="table table-condensed table-hover table-striped" width="100%" data-ajax="true">
                <thead>
                <tr>
                	<th data-column-id="mrn" data-formatter="col_add" data-width="4%">#</th>
                    <th data-column-id="MRN" data-type="numeric" data-formatter="col_mrn" data-width="5%">MRN</th>
                    <th data-style="dropDownItem" data-column-id="Name" data-formatter="col_name" data-width="15%">Name</th>
                    <th data-column-id="q_doctorname" data-width="15%">Doctor</th>
                	<th data-column-id="pregnant" data-formatter="col_preg" data-width="5%"></th>
                    <th data-column-id="Newic" data-width="8%">New IC</th>
                    <th data-column-id="telhp" data-width="8%">H/P</th>
                    <th data-column-id="DOB" data-formatter="col_dob" data-width="6%">DOB</th>
                    <th data-column-id="Sex" data-width="2%">Sex</th>
                    <th data-column-id="col_age" data-formatter="col_age" data-sortable="false" data-width="2%">Age</th>
				</tr>
				</thead>

			</table>
		</div>


	<div class='row' style="position: relative;margin: 0 12px 12px 12px" id="episodelist_patenq_row">
			@include('hisdb.pat_enq.episodelist_patenq')
	</div>

	<script type="text/ecmascript" src="plugins/jquery-3.2.1.min.js"></script> 
	<script type="text/ecmascript" src="plugins/jquery-migrate-3.0.0.js"></script>
    <script type="text/ecmascript" src="plugins/trirand/i18n/grid.locale-en.js"></script>
    <script type="text/ecmascript" src="plugins/trirand/jquery.jqGrid.min.js"></script>
    <script type="text/ecmascript" src="plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <script type="text/javascript">$.fn.modal.Constructor.prototype.enforceFocus = function() {};</script>
    <script type="text/ecmascript" src="plugins/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script type="text/ecmascript" src="plugins/form-validator/jquery.form-validator.min.js"></script>
    
    <script type="text/ecmascript" src="plugins/numeral.min.js"></script>
	<script type="text/ecmascript" src="plugins/moment.js"></script>
	<script type="text/javascript" src="js/myjs/utility.js"></script>

	<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-validator/jquery.validate.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-validator/additional-methods.min.js"></script>

	<script type="text/javascript" src="plugins/bootgrid/js/jquery.bootgrid.js"></script>
	<script type="text/javascript" src="js/myjs/modal-fix.js"></script>
	

	<script type="text/javascript">
		$.jgrid.defaults.responsive = true;
		$.jgrid.defaults.styleUI = 'Bootstrap';
	</script>

	<script type="text/javascript" src="js/hisdb/pat_enq/pat_enq_main.js"></script>
	<script type="text/javascript" src="js/hisdb/pat_enq/episodelist_patenq.js"></script>

	</div>

</body>
</html>
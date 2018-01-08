<?php include_once('../../../../assets/php/sschecker.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<title></title>
	<link rel="stylesheet" type="text/css"  href="../../../../assets/plugins/bootstrap-3.3.5-dist/css/bootstrap.css">	
	<link rel="stylesheet" type="text/css"  href="../../../../assets/plugins/bootstrap-3.3.5-dist/css/bootstrap-theme.css">
	<link rel="stylesheet" type="text/css"  href="../../../../assets/plugins/bootgrid/css/jquery.bootgrid.css">
	<link rel="stylesheet" type="text/css"  href="../../../../assets/plugins/datatables/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css"  href="../../../../assets/plugins/font-awesome-4.4.0/css/font-awesome.min.css">


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
    <div class="wrapper">
        	<input name="pattype" id="pattype" type="hidden" value="<?php echo $_GET['pattype']; ?>">
        	<input name="listtype" id="listtype" type="hidden" value="<?php echo $_GET['listtype']; ?>">
        <div id="info"></div>

		<div class="panel">
			<button id="patientBox" type="button" class="btn btn-success btn-md" ><span class="glyphicon glyphicon-inbox" aria-hidden="true"> </span> Register New</button>
			&nbsp;&nbsp;
			<button id="btn_mykad" type="button" class="btn btn-success btn-md" ><span class="glyphicon glyphicon-credit-card" aria-hidden="true"> </span> My Kad</button>
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
                    <th data-column-id="commands" data-formatter="commands" data-sortable="false" data-width="8%">#</th>
                </tr>
                </thead>

            </table>


        </div>

		<?php include_once('mdl_patient.php'); ?>
		<?php include_once('mdl_episode.php'); ?>
		<?php include_once("../../../../itemselector.php"); ?>		
	</div>

	<?php include_once("../../../../implementingjs-hisdb.php"); ?>
	<script type="text/javascript" language="javascript" src="../../../../assets/plugins/bootgrid/js/jquery.bootgrid.js"></script>
	<script type="text/javascript" language="javascript" src="../../../../assets/js/modal-fix.js"></script>






    <!--script type="text/javascript" language="javascript" src="../../../../assets/plugins/jquery-2.2.3.min.js"></script-->
	<!--script type="text/javascript" language="javascript" src="../../../../assets/plugins/jquery/jquery.min.js"></script>
    
	<script type="text/javascript" language="javascript" src="../../../../assets/plugins/datatables/js/jquery.datatables.min.js"></script>
	<script type="text/javascript" language="javascript" src="../../../../assets/plugins/bootstrap-3.3.5-dist/js/bootstrap.js"></script>
	<script type="text/javascript" language="javascript" src="../../../../assets/plugins/moment.js"></script-->
	<script type="text/javascript" language="javascript" src="../../../../assets/js/global.js"></script>
    <script type="text/javascript" language="javascript" src="landing.js"></script>




</body>
</html>
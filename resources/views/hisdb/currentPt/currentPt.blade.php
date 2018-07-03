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
	<link rel="stylesheet" href="plugins/datatables/css/jquery.dataTables.css">
	<link rel="stylesheet" href="plugins/font-awesome-4.4.0/css/font-awesome.min.css">

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
        	<input name="Epistycode" id="Epistycode" type="hidden" value="{{request()->get('epistycode')}}">
        <div id="info"></div>

		<div class="panel">
			<!-- <button id="patientBox" type="button" class="btn btn-success btn-md" ><span class="glyphicon glyphicon-inbox" aria-hidden="true"> </span> Register New</button>
			&nbsp;&nbsp; -->
			<button id="btn_mykad" type="button" class="btn btn-success btn-md" ><span class="glyphicon glyphicon-credit-card" aria-hidden="true"> </span> My Kad</button>
			&nbsp;&nbsp;
			<button id="adjustment_but_currentPt" type="button" class="btn btn-success btn-md" ><span class="glyphicon glyphicon-credit-card" aria-hidden="true"> </span> Adjustment</button>
		</div>
		<div class="panel">
			<table id="grid-command-buttons" class="table table-condensed table-hover table-striped" width="100%" data-ajax="true">
                <thead>
                <tr>
                	<th data-column-id="Mrn" data-formatter="col_add" data-width="5%">#</th>
                    <th data-column-id="mrn" data-type="numeric" data-width="10%">MRN No</th>
                    <th data-column-id="episno" data-width="15%">Episode No</th>
                    <th data-style="dropDownItem" data-column-id="name" data-formatter="col_name" data-width="30%">Name</th>
                    <th data-column-id="newic" data-width="15%">New IC</th>
                    <th data-column-id="oldic" data-width="10%">Old IC</th>
                    <th data-column-id="dob" data-width="12%">Birth Date</th>
                    <th data-column-id="sex" data-width="6%">Sex</th>
					<th data-column-id="telhp" data-width="15%">Hp No</th>
					<th data-column-id="telhp" data-width="15%">Home No</th>
                   <!--  <th data-column-id="col_age" data-formatter="col_age" data-sortable="false" data-width="8%">Age</th> -->

<!--                    <th data-column-id="edit_cmd" data-formatter="edit_cmd" data-sortable="false">Commands</th>-->
<!--                    <th data-column-id="episode_cmd" data-formatter="episode_cmd" data-sortable="false">Commands</th>-->
					<th data-column-id="commands" data-formatter="commands" data-sortable="false" data-width="8%">#</th>
				</tr>
				</thead>

			</table>
           <!--     <div id="adjustmentform" title="Adjustment" >
		<form class='form-horizontal' style='width:99%' id='adjustmentformdata'>
		{{ csrf_field() }}
			<input type="hidden" name="idno">
				<div class="form-group">
				  <label class="col-md-2 control-label" for="episno">Episode No</label>  
                      <div class="col-md-2">
                      <input id="episno" name="episno" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
                      </div>
				</div>

			<div class="form-group">
                  <label class="col-md-2 control-label" for="epistycode">Type</label>  
                      <div class="col-md-2">
                      <input id="epistycode" name="epistycode" type="text" class="form-control input-sm" data-validation="required">
                      </div>
                   <div class="col-md-2">
				      <input id="epistycode" name="epistycode" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				   </div>     
			</div>
                
            <div class="form-group">
                  <label class="col-md-2 control-label" for="bedtype">Bed Type</label>  
                      <div class="col-md-2">
                      <input id="bedtype" name="bedtype" type="text" class="form-control input-sm" data-validation="required">
                      </div>
                   <div class="col-md-2">
					<input id="bedtype" name="bedtype" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				   </div>      
			</div>    
                
            <div class="form-group">
                  <label class="col-md-2 control-label" for="bed">Bed</label>  
                      <div class="col-md-2">
                      <input id="bed" name="bed" type="text" class="form-control input-sm" data-validation="required">
                      </div>
                  <label class="col-md-1 control-label" for="room">Room</label>
			          <div class="col-md-2">
				      <input type="text" name="room" id="room" class="form-control input-sm" maxlength="14" data-validation-optional-if-answered="Oldic" >
			          </div>     
            </div> 
        
        <hr>
            <div class="form-group">
				  <label class="col-md-2 control-label" for="reg_date">Reg Date</label>  
                      <div class="col-md-2">
                      <input id="reg_date" name="reg_date" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
                      </div>
                  <label class="col-md-1 control-label" for="reg_by">Register By</label>
			          <div class="col-md-2">
				      <input type="text" name="reg_by" id="reg_by" class="form-control input-sm" maxlength="14" data-validation-optional-if-answered="Oldic" >
			          </div>
			      <label class="col-md-1 control-label" for="reg_time">Time</label>
			          <div class="col-md-2">
				      <input type="text" name="reg_time" id="reg_time" class="form-control input-sm" maxlength="14" data-validation-optional-if-answered="Oldic" >
			          </div>     
			</div>

			<div class="form-group">
			      <label class="col-md-2 control-label" for="qdate">Discharge Date</label>  
                      <div class="col-md-2">
                      <input id="qdate" name="qdate" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
                      </div>
                  <label class="col-md-1 control-label" for="qdate">Discharge By</label>
			          <div class="col-md-2">
				      <input type="text" name="qdate" id="qdate" class="form-control input-sm" maxlength="14" data-validation-optional-if-answered="Oldic" >
			          </div>
			      <label class="col-md-1 control-label" for="qtime">Time</label>
			          <div class="col-md-2">
				      <input type="text" name="qtime" id="qtime" class="form-control input-sm" maxlength="14" data-validation-optional-if-answered="Oldic" >
			          </div>     
			</div>
                
            <div class="form-group">
                  <label class="col-md-2 control-label" for="description">Destination</label>  
                      <div class="col-md-2">
                      <input id="description" name="description" type="text" class="form-control input-sm" data-validation="required">
                      </div>
                      <div class="col-md-3">
					  <input id="description" name="description" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				      </div>      
			</div>    
          
	  </form>

	</div> -->

		</div>
      <div id="adjustment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header label-success">
				<p align="center"><b>Adjustment</b></p>
			</div>
			<div class="modal-body">
			<!-- 	<div class="row">
					<div class="col-md-12">
						Insert mykad, then press read mykad button and wait for information to appear at the bottom
						<br/><span id="mykad_reponse"></span>
						<br /><br />
					</div>
				</div> -->
        		<form id="frm_mykad_info" class="form-horizontal">
					<div class="form-group">
						<!-- <div class="col-md-2">
							<img id="mykad_photo" src="{{asset('img/defaultprofile.png')}}" width="120" height="140" class="addressinp" />
							
						</div> -->
						<div class="col-md-10">
							<div class="row"><br /></div>
							<div class="row">
								<div class="col-md-6">
									<small for="episno">Episode No</small>
									<input class="form-control has-error form-mandatory" name="Name" id="adjustment_episno" placeholder="" type="text" required>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<small for="type">Type</small>
									<input class="form-control form-mandatory" name="Newic" id="mykad_newic" placeholder="" type="text">
								</div>
								<div class="col-md-3">
									<small for="type">-</small>
									<input class="form-control form-mandatory" name="Oldic" id="mykad_oldic" placeholder="" type="text">
								</div>

							</div>
							<div class="row">
								<div class="col-md-3">
									<small for="bedtype">Bed Type</small>
									<input class="form-control form-mandatory" name="Newic" id="mykad_newic" placeholder="" type="text">
								</div>
								<div class="col-md-3">
									<small for="bedtype">-</small>
									<input class="form-control form-mandatory" name="Oldic" id="mykad_oldic" placeholder="" type="text">
								</div>

							</div>
							<div class="row">
								<div class="col-md-3">
									<small for="Bed">Bed </small>
									<input class="form-control form-mandatory" name="Newic" id="mykad_newic" placeholder="" type="text">
								</div>
								<div class="col-md-3">
									<small for="Room">Room</small>
									<input class="form-control form-mandatory" name="Oldic" id="mykad_oldic" placeholder="" type="text">
								</div>

							</div>
							<div class="row">
								<div class="col-md-3">
									<small for="Newic">Reg Date</small>
									<input class="form-control form-mandatory" name="Newic" id="mykad_newic" placeholder="" type="text">
								</div>
								<div class="col-md-3">
									<small for="Oldic">Reg By</small>
									<input class="form-control form-mandatory" name="Oldic" id="mykad_oldic" placeholder="" type="text">
								</div>
								<div class="col-md-3">
									<small for="Oldic">Reg Time</small>
									<input class="form-control form-mandatory" name="Oldic" id="mykad_oldic" placeholder="" type="text">
								</div>

							</div>
							<div class="row">
								<div class="col-md-3">
									<small for="Newic">Discharge Date</small>
									<input class="form-control form-mandatory" name="Newic" id="mykad_newic" placeholder="" type="text">
								</div>
								<div class="col-md-3">
									<small for="Oldic">Discharge By</small>
									<input class="form-control form-mandatory" name="Oldic" id="mykad_oldic" placeholder="" type="text">
								</div>
								<div class="col-md-3">
									<small for="Oldic">Discharge Time</small>
									<input class="form-control form-mandatory" name="Oldic" id="mykad_oldic" placeholder="" type="text">
								</div>

							</div>
						</div>
					</div>
				</form>
				<!-- <div class="table-responsive table-no-bordered content">
					<table id="tbl_existing_record" class="table-hover cell-border" width="100%">
						<thead>
							<tr>
								<th>New IC</th>
								<th>Birth Place</th>
								<th>Name</th>
								<th>Old IC</th>
								<th>Religion</th>
								<th>Gender</th>
								<th>Race</th>
								<th>Address 1</th>
								<th>Address 2</th>
								<th>Address 3</th>
								<th>Postcode</th>
								<th>City</th>
								<th>State</th>
							</tr>
						</thead>
					</table>
				</div> -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-success" id="btn_reg_proceed">Proceed</button>
				<button type="button" class="btn btn-success" id="btn_reg_discharge">Discharged</button>
			</div>
		</div>
	</div>
</div>

		@include('hisdb.pat_mgmt.mdl_patient')
		@include('hisdb.pat_mgmt.mdl_episode')
		@include('hisdb.pat_mgmt.itemselector')

		

	<script type="text/ecmascript" src="plugins/jquery-3.2.1.min.js"></script> 
	<script type="text/ecmascript" src="plugins/jquery-migrate-3.0.0.js"></script>
    <script type="text/ecmascript" src="plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <script type="text/ecmascript" src="plugins/numeral.min.js"></script>
	<script type="text/ecmascript" src="plugins/moment.js"></script>


	<script type="text/javascript" src="plugins/datatables/js/jquery.datatables.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-validator/jquery.validate.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-validator/additional-methods.min.js"></script>

	<script type="text/javascript" src="plugins/bootgrid/js/jquery.bootgrid.js"></script>
	<script type="text/javascript" src="js/myjs/modal-fix.js"></script>
	<script type="text/javascript" src="js/myjs/global.js"></script>
	<script src="js/hisdb/currentPt/currentPt.js"></script>

	</div>

</body>
</html>
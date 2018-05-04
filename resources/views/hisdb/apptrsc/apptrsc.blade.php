@extends('layouts.main')

@section('css')
	<link rel="stylesheet" href="plugins/datatables/css/jquery.dataTables.css">
@endsection

@section('style')

.fc-bgevent{
	opacity: .8;
}

.fc-event {
    position: relative;
    display: block;
    font-size: .85em;
    line-height: 1.3;
    border-radius: 3px;
    border: 1px solid #3a87ad;
}

.fc-event .selected {
    position: relative;
    display: block;
    font-size: .85em;
    line-height: 1.3;
    border-radius: 3px;
    border: 1px solid #3a87ad;
}

td.fc-event-container a.selected{
	background-color: dimgray !important;
}


@endsection

@section('title', 'Appointment')

@section('body')
	<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
	<input type="hidden" name="ALCOLOR" id="ALCOLOR" value="{{ $ALCOLOR->ALcolor }}">
	
	<div class='row'>
		<input id="Class2" name="Type" type="hidden" value="{{Request::get('TYPE')}}">
		<div id="divform" class="formclass" style='width:99%;padding-bottom: 40px'>
			<form id="searchForm" >
				<fieldset>
					<div class='col-md-12' style='padding: 0 0 5px 0;'>
						<div class='form-group'>
							<div class='col-md-2'>
								<label class="control-label" for="Scol">Search By : </label>
								<input class="form-control input-sm" id="Scol" type="text" readonly>
							</div>
							<div class='col-md-3'>
								<label class="control-label" for="resourcecode">&nbsp</label>
								<div class='input-group'>
									<input class="form-control input-sm" id="resourcecode" name="resourcecode" type="text" maxlength="12" data-validation="required" readonly>
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>								
								</div>
								<span class='help-block'></span>
							</div>
						</div>
					</div>
				 </fieldset>
			</form>

			<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." style="padding-right:15px" >
				<button type="button" class="btn btn-default" id='biodata_but_apptrsc' data-oper='add'>
					<span class='fa fa-user fa-lg'></span> Biodata
				</button>
				<button type="button" class="btn btn-default" id='transfer_doctor_but'>
					<span class='fa fa-user-md fa-lg'></span> Transfer Doctor
				</button>
				<button type="button" class="btn btn-default" id='transfer_date_but'>
					<span class='fa fa-calendar fa-lg'></span> Transfer Date
				</button>
			</div>
		</div>

		<div class="panel panel-default">
    		<div class="panel-body">
    			<div class='col-md-12' style="padding:0 0 15px 0">
    				<div id="calendar"></div>
				</div>
    		</div>
		</div>

		<!-- Dialog -->
		<div id="dialogForm" title="Add Form">
			<div class="panel panel-info">
				<div class="panel-heading">Appointment Header</div>
					<form action={{url('apptrsc/addEvent')}} method="post" class="form-horizontal" style="width: 99%" id="addForm" >
					{{csrf_field()}}
					<input type="hidden" name="idno" id="idno">
						<div class="panel-body" style="position: relative;" >
							<div class="form-group">
								<label for="Doctor" class="col-md-2 control-label">Doctor</label>
								<div class="col-md-3">
									<input type="text" class="form-control input-sm" placeholder="Doctor" id="doctor" name="doctor" maxlength="12" data-validation="required" readonly>
								</div>
							</div>
							<div class="form-group">
								<label for="title" class="col-md-2 control-label">MRN</label>
								<div class="col-md-4">
									<div class="input-group">
										<input type="text" class="form-control input-sm" placeholder="MRN No" id="mrn" name="mrn" maxlength="12" readonly>
										<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class='help-block'></span>
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="patname" name="patname">
								</div>
							</div>
							<div class="form-group">
								<label for="start" class="col-md-2 control-label">Appt Date</label>
								<div class="col-md-3">
									<input type="date" class="form-control input-sm" placeholder="Start Date" id="apptdatefr_day" name="apptdatefr_day" data-validation="required" readonly>	
								</div>

								<label for="start" class="col-md-2 control-label">Appt Time</label>
								<div class="col-md-3">
									<div class="input-group">
										<input type="text" class="form-control input-sm" placeholder="Start Time" id="start_time" name="start_time" maxlength="12" data-validation="required">
										<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class='help-block'></span>
								</div>
								<input type="hidden" id="end_time" name="end_time">
							</div>
							<div class="form-group">
								<label for="telh" class="col-md-2 control-label">Tel No</label>
								<div class="col-md-3">
									<input type="text" class="form-control input-sm" placeholder="Telephone No" id="telh" name="telh" data-validation-optional-if-answered="telhp" data-validation="required">	
								</div>
								<label for="status" class="col-md-2 control-label">Status</label>
								<div class="col-md-3">
									<select name="status" id="status" class="form-control input-sm" data-validation="required">
										<option value="attend">Attend</option>	
										<option value="notattend">Not Attend</option>
									</select>	
								</div>
							</div>
							<div class="form-group">
								<label for="telhp" class="col-md-2 control-label">Tel Hp</label>
								<div class="col-md-3">
									<input type="text" class="form-control input-sm" placeholder="Telephone Hp" id="telhp" name="telhp" data-validation="required" data-validation-optional-if-answered="telh">	
								</div>
								<label for="Doctor" class="col-md-2 control-label">Case</label>
								<div class="col-md-3">
									<div class="input-group">
										<input type="text" class="form-control input-sm" placeholder="Case" id="case" name="case" maxlength="12" data-validation="required">	
										<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class='help-block'></span>
								</div>							
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label" for="remarks">Remarks</label>   
									<div class="col-md-8">
										<textarea rows="5" id='remarks' name='remarks' class="form-control input-sm" ></textarea>
									</div>
							</div>
							<hr>
							<div class="form-group">
								<label for="telh" class="col-md-2 control-label">Last User</label>
								<div class="col-md-3">
									<input type="text" class="form-control input-sm" id="lastuser" name="lastuser" readonly>	
								</div>
								<label for="telh" class="col-md-2 control-label">Last Update</label>
								<div class="col-md-3">
									<input type="text" class="form-control input-sm" id="lastupdate" name="lastupdate" readonly>	
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label" for="lastcomputerid">Computer Id</label>  
								<div class="col-md-3">
									<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" readonly>
								</div>

								<label class="col-md-2 control-label" for="lastipaddress">IP Address</label>  
								<div class="col-md-3">
									<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" readonly>
								</div>
							</div> 
						</div>
						<div class="panel-footer">
							<button type="button" class="btn btn-primary" id="submit">Save changes</button>
							<button type="button" class="btn btn-danger" id="delete_but" style="display: none;">Delete Appointment</button>
						</div>
					</form>
			</div>
		</div>
    </div>

    <div id="start_time_dialog" title="Pick Start Time">
		<div id='grid_start_time_c' style="padding:15px 0 15px 0">
            <table id="grid_start_time" class="table table-striped"></table>
            <div id="grid_start_time_pager"></div>
        </div>
	</div>

	<div id="transfer_date" title="Transfer appointment to different date">

		<div class="row">
        	<div class="col-md-7" style="padding:0px">
        		<form class="form-inline">
        			<label>Date From</label>
	        		<input type="text" class="form-control input-sm" id="td_date_from" placeholder="Date From">
					<input type="text" class="form-control input-sm" id="transfer_doctor_from" readonly>
        		</form>
        	</div>
        </div>

		<div id='grid_transfer_date_from_c' style="padding:0px 0 15px 0">
            <table id="grid_transfer_date_from" class="table table-striped"></table>
            <div id="grid_transfer_date_from_pager"></div>
        </div>

        <div class="row">
        	<div class="col-md-7 pull-left" style="padding:0px">
        		<form class="form-inline">
        			<label>Transfer Date</label>
	        		<input type="text" class="form-control input-sm" placeholder="Transfer Date" id="td_date_to">
	        		<span id="transfer_doctor_div">
		        		<label for="Doctor">Doctor</label>
						<div class="input-group">
							<input type="text" class="form-control input-sm" placeholder="Transfer Doctor" id="transfer_doctor" name="transfer_doctor" readonly>	
							<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
						</div>
					</span>
        		</form>
        	</div>
	        <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." style="padding-right:15px" >
				<button type="button" class="btn btn-default" id='td_but_down'>
					<span class='fa fa-arrow-circle-down fa-lg'></span> Down
				</button>
				<button type="button" class="btn btn-default" id='td_but_up'>
					<span class='fa fa-arrow-circle-up fa-lg'></span> Up
				</button>				
			</div>
        </div>

        <div id='grid_transfer_date_to_c' style="padding:0px 0 10px 0">
            <table id="grid_transfer_date_to" class="table table-striped"></table>
            <div id="grid_transfer_date_to_pager"></div>
        </div>

        <button type="button" class="btn btn-sm btn-primary" id="td_save">
        	Save Changes <span class='fa fa-save fa-lg'></span>
        </button>
	</div>


	@include('hisdb.pat_mgmt.mdl_patient')
	@include('hisdb.pat_mgmt.itemselector')

	@endsection

@section('scripts')

	<script type="text/javascript" src="plugins/datatables/js/jquery.datatables.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-validator/jquery.validate.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-validator/additional-methods.min.js"></script>

	<script type="text/javascript" src="js/myjs/modal-fix.js"></script>
	<script type="text/javascript" src="js/myjs/global.js"></script>
	<script src="js/hisdb/pat_mgmt/biodata.js"></script>

	
	<script src="js/hisdb/apptrsc/apptrsc.js"></script>

@endsection
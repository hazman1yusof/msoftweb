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
    font-size: 1em !important;
    line-height: 1.3;
    border-radius: 3px;
    border: 1px solid #3a87ad;
}

.fc-event .selected {
    position: relative;
    display: block;
    font-size: 1em !important;
    line-height: 1.3;
    border-radius: 3px;
    border: 1px solid #3a87ad;
}

td.fc-event-container a.selected{
	background-color: dimgray !important;
}

.fc-time-grid-event.fc-short .fc-title {
    font-size: 1em !important;
    padding: 0;
}

.fc-day-grid-event .fc-content {
    white-space: pre-line !important;
}

.ui.label {
    display: inline-block;
    line-height: 1;
    vertical-align: baseline;
    margin: 0.2em;
    background-color: #8bc34a5c;
    background-image: none;
    padding: .5833em .833em;
    color: rgba(0,0,0,.6);
    text-transform: none;
    font-weight: 700;
    border: 1px solid #279a2c;
    border-radius: .28571429rem;
    -webkit-transition: background .1s ease;
    transition: background .1s ease;
}

#dialogForm input[type=text] {
    text-transform: uppercase;
}

#mdl_accomodation,#mdl_reference,#mdl_bill_type,#mdl_epis_pay_mode,#bs-guarantor,#mdl_new_gl{
	display: none; z-index: 110;background-color: rgba(0, 0, 0, 0.3);
}
.smallmodal{
	width: 70% !important; margin: auto !important;margin-top:30px;margin-top: 30px !important;
}
.smallmodal > .modal-content{
	border: 1px solid grey;
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
	/*padding: 10px;*/
}

.modal-backdrop{
	z-index: 99 !important;
	background-color: #fff !important;
	opacity: 1 !important;
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

div.myhr_div{
    width: 100%;
    height: 12px;
    border-bottom: 1px solid #eeeeee;
    text-align: center;
    margin-bottom: 30px;
}

span.myhr_span{
	font-size: 15px;
    background-color: #eeeeee;
    padding: 2px 10px;
    border-radius: 8px;
}

button#btn_icd,button#btn_mma{
	bottom: -35px;
    position: absolute;
    right: 10px;
}

@endsection

@section('title', 'Booking')

@section('body')
	<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
	<input type="hidden" name="ALCOLOR" id="ALCOLOR" value="{{ $ALCOLOR->pvalue1 }}">
	
	<div class='row'>
		<input id="Class2" name="Type" type="hidden" value="{{Request::get('TYPE')}}">
		<div id="divform" class="formclass clearfix" style='width:99%;padding-bottom: 12px'>
			<form id="searchForm" >
				<fieldset>
					<div class='col-md-12' style='padding: 0 0 5px 0;'>
						<div class='form-group'>
							<div class='col-md-2'>
								<label class="control-label" for="Scol">Search By : </label>
								<input class="form-control input-sm" id="Scol" type="text" readonly value="Doctor">
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

			<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." style="padding-right:12px" >
				<button type="button" class="btn btn-default" id='transfer_doctor_but'>
					<span class='fa fa-exchange fa-lg'></span> Transfer Resource
				</button>
				<button type="button" class="btn btn-default" id='transfer_date_but'>
					<span class='fa fa-calendar fa-lg'></span> Transfer Date
				</button>
			</div>
			
			@if(!empty($pat_info))
			
			<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." style="padding-right:12px" >
				<button type="button" class="btn btn-default btn-sm" id='biodata_but_apptrsc' data-oper='edit' data-mrn_from_calander="@if(!empty($pat_info)){{$pat_info->MRN}} @endif">
					<span class='fa fa-user fa-lg'></span> Bio
				</button>
				<!-- <button type="button" class="btn btn-default" id='episode_but_apptrsc' data-oper='add'>
					<span class='fa fa-h-square fa-lg'></span> Episode
				</button> -->
			</div>
			<a href="./logout" style="
			    position: absolute;
			    top: 0;
			    right: 50px;
			"> | &nbsp;Log Out |</a>
			<a href="./preview?mrn={{$pat_info->MRN}}" style="
			    position: absolute;
			    top: 0;
			    right: 112px;
			"> | &nbsp;MedicalImage </a>
			<a href="#" style="
			    position: absolute;
			    top: 0;
			    right: 201px;
			"> | &nbsp;MedicalTalk </a>

			@endif
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
				<div class="panel-heading">Booking Header</div>
					<form action={{url('apptrsc/addEvent')}} method="post" class="form-horizontal" style="" id="addForm" >
						{{csrf_field()}}
						<input type="hidden" name="idno" id="idno">
						<input type="hidden" name="computerid" id="computerid">
						<div class="panel-body" style="position: relative;" >
							<div class="form-group">
								<label for="Doctor" class="col-md-2 control-label">Resource</label>
								<div class="col-md-3">
									<input type="text" class="form-control input-sm" placeholder="Doctor" id="doctor" name="doctor" maxlength="12" data-validation="required" readonly>
								</div>
							</div>
							<div class="form-group">
								<label for="title" class="col-md-2 control-label">MRN</label>
								<div class="col-md-4">

								@if(Request::get('TYPE') == 'OT')
									<div class="input-group">
										<input type="text" class="form-control input-sm" placeholder="MRN No" id="mrn" name="mrn" maxlength="12" readonly data-validation="required">
										<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class='help-block'></span>
								@else
									<div class="input-group">
										<input type="text" class="form-control input-sm" placeholder="MRN No" id="mrn" name="mrn" maxlength="12" readonly value="@if(!empty($pat_info)){{$pat_info->MRN}} @endif">
										<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class='help-block'></span>
								@endif

								</div>
								@if(Request::get('TYPE') == 'OT')
								<div class="col-md-4">
									<input type="text" class="form-control input-sm" placeholder="Name" id="patname" name="patname" value="@if(!empty($pat_info)){{$pat_info->Name}} @endif" readonly>
								</div>
								@else
								<div class="col-md-4">
									<input type="text" class="form-control input-sm" data-validation="required" placeholder="Name" id="patname" name="patname" value="@if(!empty($pat_info)){{$pat_info->Name}} @endif" @if(!empty($pat_info)){{'readonly'}} @endif>
								</div>
								@endif
							</div>
							<div class="form-group">
								<label for="title" class="col-md-2 control-label">I/C No.</label>
								<div class="col-md-3">
									<input type="text" class="form-control input-sm" placeholder="I/C No." id="icnum" name="icnum" maxlength="12"  readonly value="@if(!empty($pat_info)){{$pat_info->newic}} @endif">
								</div>
								<label for="iPesakit" class="col-md-2 control-label">iPesakit</label>
								<div class="col-md-3">
									<input type="text" class="form-control input-sm" placeholder="iPesakit" id="iPesakit" name="iPesakit">
								</div>
							</div>
							<div class="form-group">
								<label for="start" class="col-md-2 control-label">Resource Date</label>
								<div class="col-md-3">
									<input type="date" class="form-control input-sm" placeholder="Start Date" id="apptdatefr_day" name="apptdatefr_day" data-validation="required"  min="{{Carbon\Carbon::now()->format('Y-m-d')}}">	
								</div>
							</div>
							<div class="form-group">
								<label for="start_time" class="col-md-2 control-label">Time Start</label>
								<div class="col-md-3">
									<input type="time" class="form-control input-sm" placeholder="Time Start" id="start_time" name="start_time"  data-validation="required">	
								</div>
								<label for="end_time" class="col-md-2 control-label">Time End</label>
								<div class="col-md-3">
									<input type="time" class="form-control input-sm" placeholder="Time End" id="end_time" name="end_time"  data-validation="required">	
								</div>
							</div>
							<div class="form-group">
								<label for="telh" class="col-md-2 control-label">Tel No</label>
								<div class="col-md-3">
									<input type="text" class="form-control input-sm phone-group" placeholder="Telephone No" id="telh" name="telh" value="@if(!empty($pat_info)){{$pat_info->telh}} @endif">	
								</div>
								<label for="status" class="col-md-2 control-label">Status</label>
								<div class="col-md-3">
									<select name="status" id="status" class="form-control input-sm" data-validation="required">
										<option value="attend">Attend</option>	
										<option value="notattend">Not Attend</option>
										<option value="decline">Decline</option>	
										<option value="postponed">Postponed</option>
									</select>	
								</div>
							</div>
							<div class="form-group">
								<label for="telhp" class="col-md-2 control-label">Tel Hp</label>
								<div class="col-md-3">
									<input type="text" class="form-control input-sm phone-group" placeholder="Telephone Hp" id="telhp" name="telhp" value="@if(!empty($pat_info)){{$pat_info->telhp}} @endif">	
								</div>
		
								<label class="col-md-2 control-label" for="cArm">Need C-Arm</label>
								<div class="col-md-3">
									<label class="radio-inline">
										<input type="radio" id="carmY" name="cArm" value="1">Yes
									</label>
									<label class="radio-inline">
										<input type="radio" id="carmN" name="cArm" value="0">No
									</label>
								</div>
								<!-- <label for="Doctor" class="col-md-2 control-label">Case</label>
								<div class="col-md-3">
									<div class="input-group">
										<input type="text" class="form-control input-sm" placeholder="Case" id="case" name="case" maxlength="12" data-validation="required">	
										<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class='help-block'></span>
								</div>		 -->					
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label" for="remarks">Remarks</label>   
									<div class="col-md-8">
										<textarea rows="3" id='remarks' name='remarks' class="form-control input-sm" ></textarea>
									</div>
							</div>

							@if(Request::get('TYPE') == 'OT')
							<div class="myhr_div">
							  <span class="myhr_span">
							    Operation Theater
							  </span>
							</div>

							<div class="form-group">
								<label for="op_unit" class="col-md-2 control-label">Unit</label>
								<div class="col-md-3">
									<div class="input-group">
										<input type="text" class="form-control input-sm" placeholder="OP Unit" id="op_unit" name="op_unit">
										<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class='help-block'></span>
								</div>
								<label for="oper_type" class="col-md-2 control-label">Operation Type</label>
								<div class="col-md-3">
									<select name="oper_type" id="oper_type" class="form-control input-sm">
										<option value=""></option>
										<option value="MAJOR">MAJOR</option>	
										<option value="MINOR">MINOR</option>
									</select>
								</div>
							</div>
							<div class="form-group" style="display: none;">
								<label for="oper_status" class="col-md-2 control-label">Operation Status</label>
								<div class="col-md-3">
									<select name="oper_status" id="oper_status" class="form-control input-sm">
										<option value=""></option>
										@foreach($otstatus as $obj)
											<option value="{{$obj->code}}">{{$obj->description}}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="form-group">
								<label for="procedure" class="col-md-2 control-label" style="position: relative;">Procedure
									<button class="btn btn-primary" type="button" id="btn_mma"><span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span> MMA</button>
								</label>
								<div class="col-md-3">
									<textarea id="procedure" name="procedure" class="form-control input-sm" rows="5"></textarea>
								</div>
								<label for="diagnosis" class="col-md-2 control-label" style="position: relative;">Diagnosis 
									<button class="btn btn-primary" type="button" id="btn_icd"><span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span> ICD</button>
								</label>
								<div class="col-md-3">
									<textarea id="diagnosis" name="diagnosis" class="form-control input-sm" rows="5"></textarea>
								</div>
							</div>
							@endif
							
							<hr>
							<div class="form-group">
								<label for="doctorname" class="col-md-2 control-label">Doctor Name</label>
								<div class="col-md-3">
									<input type="text" class="form-control input-sm" id="doctorname" name="doctorname">	
								</div>
								<label for="anaesthetist" class="col-md-2 control-label">Anaesthetist</label>
								<div class="col-md-3">
									<input type="text" class="form-control input-sm" id="anaesthetist" name="anaesthetist">	
								</div>
							</div>
							<div class="form-group">
								<label for="surgeon" class="col-md-2 control-label">Surgeon</label>
								<div class="col-md-3">
									<input type="text" class="form-control input-sm" id="surgeon" name="surgeon">	
								</div>
							</div>
							<div class="form-group">
								<label for="lastuser" class="col-md-2 control-label">Last User</label>
								<div class="col-md-3">
									<input type="text" class="form-control input-sm" id="lastuser" name="lastuser" readonly>	
								</div>
								<label for="lastupdate" class="col-md-2 control-label">Last Update</label>
								<div class="col-md-3">
									<input type="text" class="form-control input-sm" id="lastupdate" name="lastupdate" readonly>	
								</div>
							</div>
						</div>
						<div class="panel-footer">
							<button type="button" class="btn btn-primary" id="submit">Save changes</button>
							<button type="button" class="btn btn-danger" id="delete_but" style="display: none;">Delete Booking</button>
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

	<div id="transfer_date" title="Transfer Booking to different date">

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
	@include('hisdb.pat_mgmt.mdl_episode')
	@include('hisdb.pat_mgmt.itemselector')

	@endsection

@section('scripts')

	<script type="text/javascript" src="plugins/datatables/js/jquery.datatables.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-validator/jquery.validate.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-validator/additional-methods.min.js"></script>

	<script type="text/javascript" src="js/myjs/modal-fix.js"></script>
	<script type="text/javascript" src="js/myjs/global.js"></script>
	<script src="js/hisdb/pat_mgmt/biodata.js"></script>
	<script src="js/hisdb/pat_mgmt/episode.js"></script>

	
	<script src="js/hisdb/apptrsc/apptrsc2.js"></script>
	<script src="{{ asset('js/myjs/utility.js') }}"></script>

@endsection
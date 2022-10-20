@extends('layouts.main')

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

.fc-time-grid-event.fc-short .fc-title {
    font-size: 1em !important;
    padding: 0;
}

.fc-day-grid-event .fc-content {
    white-space: pre-line !important;
}

#dialogForm input[type=text] {
    text-transform: uppercase;
}
.ui.ribbon.label {
    left: -1.2em !important;
}
.fc-ltr .fc-basic-view .fc-day-top .fc-day-number {
    padding-right: 20px !important;
}


@endsection

@section('content')
<div id="hide_content" style="display: none">
	<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
	<input type="hidden" name="ALCOLOR" id="ALCOLOR" value="{{ $ALCOLOR->pvalue1 }}">
	<input id="Class2" name="Type" type="hidden" value="DOC">

	<div class="ui teal segment" style="padding-bottom: 30px;">
		<div class="ui grid">
			<div class="column">
				<form id="searchForm" >
					<fieldset>
						<div class='col-md-12' >
							<div class='form-group'>
								<div class='col-md-2'>
									<label class="control-label" for="Scol">Search By : </label>
									<input class="form-control input-sm" id="Scol" type="text" readonly value="DOCTOR">
								</div>
								<div class='col-md-3'>
									<label class="control-label" for="resourcecode">&nbsp</label>
									<div class='input-group'>
										<input class="form-control input-sm" id="resourcecode" name="resourcecode" type="text" maxlength="12" data-validation="required" readonly>
										<a class='input-group-addon btn btn-primary'><span class='glyphicon glyphicon-option-horizontal'></span></a>								
									</div>
									<span class='help-block'></span>
								</div>
							</div>
						</div>
					 </fieldset>
				</form>
			</div>
		</div>

	</div>

	<div class="ui orange segment" style="padding-bottom: 30px;">
		<div id="calendar"></div>
	</div>

	<div id="dialogForm" title="Add Form">
		<div class="panel panel-info">
			<div class="panel-heading">Appointment Header</div>
				<form action={{url('appointment/addEvent')}} method="post" class="form-horizontal" style="width: 99%" id="addForm" >
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
									<input type="text" class="form-control input-sm" placeholder="MRN No" id="mrn" name="mrn" maxlength="12" readonly value="@if(!empty($pat_info)){{$pat_info->MRN}}@endif">
									<a class="input-group-addon btn btn-primary"><span class='glyphicon glyphicon-option-horizontal'></span></a>
								</div>
								<span class='help-block'></span>
							</div>
							<div class="col-md-4">
								<input type="text" class="form-control input-sm" data-validation="required" placeholder="Name" id="patname" name="patname" value="@if(!empty($pat_info)){{$pat_info->Name}}@endif" @if(!empty($pat_info)){{'readonly'}}@endif>
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-md-2 control-label">I/C No.</label>
							<div class="col-md-3">
								<input type="text" class="form-control input-sm" placeholder="I/C No." id="icnum" name="icnum" maxlength="12" data-validation="required" readonly value="@if(!empty($pat_info)){{$pat_info->Newic}}@endif">
							</div>
						</div>
						<div class="form-group">
							<label for="start" class="col-md-2 control-label">Appt Date</label>
							<div class="col-md-3">
								<input type="date" class="form-control input-sm" placeholder="Start Date" id="apptdatefr_day" name="apptdatefr_day" data-validation="required"  min="{{Carbon\Carbon::now()->format('Y-m-d')}}">	
							</div>

							<label for="start" class="col-md-2 control-label">Appt Time</label>
							<div class="col-md-3">
								<div class="input-group">
									<input type="text" class="form-control input-sm" placeholder="Start Time" id="start_time" name="start_time" maxlength="12" data-validation="required">
									<a class="input-group-addon btn btn-primary"><span class='glyphicon glyphicon-option-horizontal'></span></a>
								</div>
								<span class='help-block'></span>
							</div>
							<input type="hidden" id="end_time" name="end_time">
						</div>
						<div class="form-group">
							<label for="telh" class="col-md-2 control-label">Tel No</label>
							<div class="col-md-3">
								<input type="text" class="form-control input-sm" placeholder="Telephone No" id="telh" name="telh" data-validation-optional-if-answered="telhp" data-validation="required" value="@if(!empty($pat_info)){{trim($pat_info->telh)}}@endif">	
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
								<input type="text" class="form-control input-sm" placeholder="Telephone Hp" id="telhp" name="telhp" data-validation="required" data-validation-optional-if-answered="telh" value="@if(!empty($pat_info)){{trim($pat_info->telhp)}}@endif">	
							</div>
							<label for="Doctor" class="col-md-2 control-label">Case</label>
							<div class="col-md-3">
								<div class="input-group">
									<input type="text" class="form-control input-sm" placeholder="Case" id="case" name="case" maxlength="12" data-validation="required">	
									<a class="input-group-addon btn btn-primary"><span class='glyphicon glyphicon-option-horizontal'></span></a>
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
					</div>
					<div class="panel-footer">
						<button type="button" class="btn btn-primary" id="submit">Save changes</button>
						<button type="button" class="btn btn-danger" id="delete_but" style="display: none;">Delete Appointment</button>
					</div>
				</form>
		</div>
	</div>

    <div id="start_time_dialog" title="Pick Start Time">
		<div id='grid_start_time_c' style="padding:15px 0 15px 0">
            <table id="grid_start_time" class="table table-striped"></table>
            <div id="grid_start_time_pager"></div>
        </div>
	</div>
</div>
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/form-validator/theme-default.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-3.3.5-dist/css/bootstrap.min.css') }}">
    
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/jquery-ui-1.12.1/jquery-ui.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/trirand/css/trirand/ui.jqgrid-bootstrap.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/fullcalendar-3.7.0/fullcalendar.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/DataTables/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.semanticui.min.css">
@endsection

@section('js')
    <script type="text/ecmascript" src="{{ asset('assets/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>
	<script type="text/ecmascript" src="{{ asset('assets/form-validator/jquery.form-validator.min.js') }}"></script>
    <script type="text/ecmascript" src="{{ asset('assets/trirand/i18n/grid.locale-en.js') }}"></script>
    <script type="text/ecmascript" src="{{ asset('assets/trirand/jquery.jqGrid.min.js') }}"></script>
	<script type="text/ecmascript" src="{{ asset('assets/fullcalendar-3.7.0/fullcalendar.min.js') }}"></script>
	<script type="text/ecmascript" src="{{ asset('assets/DataTables/datatables.min.js') }}"></script>
    <script type="text/ecmascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.semanticui.min.js"></script>
    <script type="text/ecmascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.js"></script>
	<script type="text/ecmascript" src="{{ asset('js/appointment.js') }}"></script>
	<!-- <script type="text/javascript">$.fn.modal.Constructor.prototype.enforceFocus = function() {};</script> -->
@endsection
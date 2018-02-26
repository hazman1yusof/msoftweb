@extends('layouts.main')

@section('title', 'Appointment')

@section('body')
	<div class='row'>
		<input id="Class2" name="Type" type="hidden" value="{{Request::get('TYPE')}}">
		<form id="searchForm" class="formclass" style='width:99%'>
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
								<input class="form-control input-sm" id="resourcecode" name="resourcecode" type="text" maxlength="12" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>								
							</div>
							<span class='help-block'></span>
						</div>
					</div>
				</div>
			 </fieldset> 
		</form>

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
								<div class="col-md-3">
									<div class="input-group">
										<input type="text" class="form-control input-sm" placeholder="MRN No" id="mrn" name="mrn" maxlength="12" data-validation="required">
										<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class='help-block'></span>
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control input-sm" placeholder="" id="patname" name="patname">
								</div>
							</div>
							<div class="form-group">
								<label for="start" class="col-md-2 control-label">Appt Date</label>
								<div class="col-md-2">
									<input type="date" class="form-control input-sm" placeholder="Start Date" id="apptdatefr_day" name="apptdatefr_day" data-validation="required" readonly>	
								</div>

								<label for="start" class="col-md-2 control-label">Appt Time</label>
								<div class="col-md-2">
									<div class="input-group">
										<input type="text" class="form-control input-sm" placeholder="Start Time" id="start_time" name="start_time" maxlength="12" data-validation="required">
										<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class='help-block'></span>
								</div>
								<input type="hidden" id="end_time" name="end_time">
							</div>
							<div class="form-group">
								<label for="telno" class="col-md-2 control-label">Tel No</label>
								<div class="col-md-2">
									<input type="text" class="form-control input-sm" placeholder="Telephone No" id="telno" name="telno" data-validation="required">	
								</div>
								<label for="status" class="col-md-2 control-label">Status</label>
								<div class="col-md-2">
									<select name="status" id="status" class="form-control input-sm" data-validation="required">
										<option value="attend">Attend</option>	
										<option value="notattend">Not Attend</option>
									</select>	
								</div>
							</div>
							<div class="form-group">
								<label for="telhp" class="col-md-2 control-label">Tel Hp</label>
								<div class="col-md-2">
									<input type="text" class="form-control input-sm" placeholder="Telephone Hp" id="telhp" name="telhp" data-validation="required">	
								</div>
								<label for="Doctor" class="col-md-2 control-label">Case</label>
								<div class="col-md-2">
									<div class="input-group">
										<input type="text" class="form-control input-sm" placeholder="Case" id="case" name="case" maxlength="12" data-validation="required">	
										<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class='help-block'></span>
								</div>							
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label" for="remarks">Remarks</label>   
									<div class="col-md-6">
										<textarea rows="5" id='remarks' name='remarks' class="form-control input-sm" ></textarea>
									</div>
							</div>
						</div>
						<div class="panel-footer">
							<button type="button" class="btn btn-primary" id="submit">Save changes</button>
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

	@endsection

@section('scripts')

	<script src="js/hisdb/apptrsc/apptrsc.js"></script>
	
@endsection
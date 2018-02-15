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
						<div class="panel-body" style="position: relative;" >
							<div class="form-group">
								<label for="Title" class="col-md-2 control-label">Title</label>
								<div class="col-md-3">
										<input type="text" class="form-control input-sm" id="title" name="title" placeholder="Title of appointment" data-validation="required" >
								</div>
							</div>
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
								<label for="start" class="col-md-2 control-label">Start Date</label>
								<div class="col-md-2">
									<input type="date" class="form-control input-sm" placeholder="Start Date" id="apptdatefr_day" name="apptdatefr_day" data-validation="required" value="{{Carbon\Carbon::now('Asia/Kuala_Lumpur')->toDateString()}}">	
								</div>
								<label for="end" class="col-md-2 control-label">End Date</label>
								<div class="col-md-2">
									<input type="date" class="form-control input-sm" placeholder="End Date" id="apptdateto_day" name="apptdateto_day" data-validation="required" value="{{Carbon\Carbon::now('Asia/Kuala_Lumpur')->toDateString()}}">	
								</div>
							</div>
							<div class="form-group">
								<label for="start" class="col-md-2 control-label">Start Time</label>
								<div class="col-md-2">
									<select name="apptdatefr_time" id="apptdatefr_time" class="form-control input-sm"></select>
								</div>
								<label for="end" class="col-md-2 control-label">End Time</label>
								<div class="col-md-2">
									<select name="apptdateto_time" id="apptdateto_time" class="form-control input-sm"></select>
								</div>
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

		<!-- Modal -->
		<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			<form class="form-horizontal" method="post" action="{{url('apptrsc/editEvent')}}" id="editForm">
			{{csrf_field()}}
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Edit Event</h4>
			  </div>
			  <div class="modal-body">
				  <div class="form-group">
					<label for="title" class="col-sm-2 control-label">Title</label>
					<div class="col-sm-10">
					  <input type="text" name="title" class="form-control" id="title" placeholder="Title">
					</div>
				  </div>
				  <div class="form-group">
					<label for="color" class="col-sm-2 control-label">Color</label>
					<div class="col-sm-10">
					  <select name="color" class="form-control" id="color">
						  <option value="">Choose</option>
						  <option style="color:#0071c5;" value="#0071c5">&#9724; Dark blue</option>
						  <option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquoise</option>
						  <option style="color:#008000;" value="#008000">&#9724; Green</option>						  
						  <option style="color:#FFD700;" value="#FFD700">&#9724; Yellow</option>
						  <option style="color:#FF8C00;" value="#FF8C00">&#9724; Orange</option>
						  <option style="color:#FF0000;" value="#FF0000">&#9724; Red</option>
						  <option style="color:#000;" value="#000">&#9724; Black</option>
						</select>
					</div>
				  </div>
				    <div class="form-group"> 
						<div class="col-sm-offset-2 col-sm-10">
						  <div class="checkbox">
							<label class="text-danger"><input type="checkbox" name="delete"> Delete event</label>
						  </div>
						</div>
					</div>
				  
				  <input type="hidden" name="id" class="form-control" id="id">
				
				
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button id="submitEdit" type="button" class="btn btn-primary">Save changes</button>
			  </div>
			</form>
			</div>
		  </div>
		</div>

    </div>

	@endsection

@section('scripts')

	<script src="js/hisdb/apptrsc/apptrsc.js"></script>
	
@endsection
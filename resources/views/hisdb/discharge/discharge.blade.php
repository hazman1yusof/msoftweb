<div class="panel panel-default" style="position: relative;" id="jqGrid_discharge_c">
	<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
		id="btn_grp_edit_discharge"
		style="position: absolute;
				padding: 0 0 0 0;
				right: 40px;
				top: 25px;" 

	>
		<!-- <button type="button" class="btn btn-default" id="new_discharge">
			<span class="fa fa-plus-square-o"></span> Order
		</button> -->
	</div>
	<div class="panel-heading clearfix collapsed position" id="toggle_discharge" data-toggle="collapse" data-target="#jqGrid_discharge_panel">
		<b>NAME: <span id="name_show_discharge"></span></b><br>
		MRN: <span id="mrn_show_discharge"></span>
		SEX: <span id="sex_show_discharge"></span>
		DOB: <span id="dob_show_discharge"></span>
		AGE: <span id="age_show_discharge"></span>
		RACE: <span id="race_show_discharge"></span>
		RELIGION: <span id="religion_show_discharge"></span><br>
		OCCUPATION: <span id="occupation_show_discharge"></span>
		CITIZENSHIP: <span id="citizenship_show_discharge"></span>
		AREA: <span id="area_show_discharge"></span>
		
		<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 25px;">
			<h5>Discharge</h5>
		</div>				
	</div>
	<div id="jqGrid_discharge_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<div class='col-md-12'>
					<div class="panel panel-info">
						<div class="panel-heading text-center">Discharge Information</div>
						<div class="panel-body" id="discharge_form">

							<div style="position: absolute;right: 50px;">
								<div class="btn-group">
								  <button type="button" class="btn btn-default" id="cancel_epis_btn">Cancel Episode</button>
								  <button type="button" class="btn btn-default" id="cancel_disc_btn">Cancel Discharge</button>
								</div>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<button type="button" class="btn btn-success" id="discharge_btn">Discharge</button>
							</div>

							<div class="form-group row">
								<label class="col-md-1 control-label" for="mrn_discharge">MRN</label>  
								<div class="col-md-1">
									<input id="mrn_discharge" name="mrn_discharge" type="text" class="form-control input-sm" data-validation="required" readonly>
								</div>

								<label class="col-md-1 control-label" for="episno_discharge">Episno</label>  
								<div class="col-md-1">
									<input id="episno_discharge" name="episno_discharge" type="text" class="form-control input-sm" data-validation="required" readonly>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-1 control-label" for="admwardtime">Type</label>  
								<div class="col-md-1">
									<input id="pattype_discharge" name="pattype_discharge" type="text" class="form-control input-sm" data-validation="required" readonly value="{{$type}}">
								</div>
								<div class="col-md-4">
									<input id="pattype_text_discharge" name="pattype_text_discharge" type="text" class="form-control input-sm" data-validation="required" readonly value="{{$type_desc}}">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-1 control-label" for="bedtype_discharge">Bed Type</label>  
								<div class="col-md-1">
									<input id="bedtype_discharge" name="bedtype_discharge" type="text" class="form-control input-sm" readonly>
								</div>
								<div class="col-md-4">
									<input id="bedtype_text_discharge" name="bedtype_text_discharge" type="text" class="form-control input-sm" readonly>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-1 control-label" for="bed_discharge">Bed</label>  
								<div class="col-md-1">
									<input id="bed_discharge" name="bed_discharge" type="text" class="form-control input-sm"  readonly>
								</div>

								<label class="col-md-1 control-label" for="room_discharge">Room</label>  
								<div class="col-md-1">
									<input id="room_discharge" name="room_discharge" type="text" class="form-control input-sm"  readonly>
								</div>
							</div>


							<div class="panel-body highlight">
								<div class="form-group row">
									<label class="col-md-1 control-label" for="regdate_discharge">Reg Date</label>  
									<div class="col-md-2">
										<input id="regdate_discharge" name="regdate_discharge" type="text" class="form-control input-sm" readonly>
									</div>

									<label class="col-md-1 control-label" for="regby_discharge">Register By</label>  
									<div class="col-md-5">
										<input id="regby_discharge" name="regby_discharge" type="text" class="form-control input-sm" readonly>
									</div>

									<label class="col-md-1 control-label" for="regtime_discharge">Register Time</label>  
									<div class="col-md-1">
										<input id="regtime_discharge" name="regtime_discharge" type="text" class="form-control input-sm" readonly>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-md-1 control-label" for="dischargedate_discharge">Discharge Date</label>  
									<div class="col-md-2">
										<input id="dischargedate_discharge" name="dischargedate_discharge" type="text" class="form-control input-sm" readonly>
									</div>

									<label class="col-md-1 control-label" for="dischargeby_discharge">Discharge By</label>  
									<div class="col-md-5">
										<input id="dischargeby_discharge" name="dischargeby_discharge" type="text" class="form-control input-sm" readonly>
									</div>

									<label class="col-md-1 control-label" for="dischargetime_discharge">Discharge Time</label>  
									<div class="col-md-1">
										<input id="dischargetime_discharge" name="dischargetime_discharge" type="text" class="form-control input-sm" readonly>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-md-1 control-label" for="dest_discharge">Destination</label>	 
									 <div class="col-md-6">
										  <div class='input-group'>
											<input id="dest_discharge" name="dest_discharge" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										  </div>
										  <span class="help-block"></span>
									  </div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>

<!-- mdl_accomodation -->
<div id="mdl_discharge_chgcode" class="modal fade" role="dialog" title="title" style="display: none; z-index: 110;background-color: rgba(0, 0, 0, 0.3);">
	<div class="modal-dialog smallmodal">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Select Chargecode</h4>
			</div>
			<div class="modal-body">
				<div class="table-responsive table-no-bordered content">
					<table id="chgcode_table" class="table-hover cell-border" width="100%">
						<thead>
							<tr>
								<th>Code</th>
								<th>Description</th>
								<th>chggroup</th>
								<th>Group</th>
								<th>Group Desc.</th>
								<th>Amount</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-u btn-u-default" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>
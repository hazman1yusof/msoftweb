<div class="panel panel-default" style="position: relative;" id="jqGrid_discharge_c">
	
	<div class="panel-heading clearfix collapsed position" id="toggle_discharge"  style="position: sticky;top: 0px;z-index: 3;">
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
		
		<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGrid_discharge_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGrid_discharge_panel"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 420px; top: 25px;">
			<h5>Discharge</h5>
		</div>

		<div
			style="position: absolute;
				    padding: 0 0 0 0;
				    right: 40px;
				    top: 18px;" 

		>
			<div class="btn-group">
			  <button type="button" class="btn btn-default" id="cancel_epis_btn">Cancel Episode</button>
			  <button type="button" class="btn btn-default" id="cancel_disc_btn">Cancel Discharge</button>
			</div>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<button type="button" class="btn btn-success" id="discharge_btn">Discharge</button>
		</div>			
	</div>
	<div id="jqGrid_discharge_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<div class='col-md-12'>
					<div class="panel panel-info">
						<div class="panel-heading text-center">Discharge Information</div>
						<div class="panel-body" id="discharge_form">

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

								<!-- <div class="form-group row">
									<label class="col-md-1 control-label" for="dest_discharge">Destination</label>	 
									 <div class="col-md-6">
										  <div class='input-group'>
											<input id="dest_discharge" name="dest_discharge" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required">
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										  </div>
										  <span class="help-block"></span>
									  </div>
								</div> -->
							</div>

							<div class="col-md-8">
                                <label class="control-label" for="clinicnote" style="padding:15px 1px 0px 1px">Provisional Diagnosis</label>
                                <textarea id="clinicnote" name="clinicnote" type="text" class="form-control input-sm" rows="5" readonly=""></textarea>


                                <label class="control-label" for="clinicnote" style="padding:15px 1px 0px 1px">Final Diagnosis</label>
                                <textarea id="clinicnote" name="clinicnote" type="text" class="form-control input-sm" rows="5" readonly=""></textarea>


                                <label class="control-label" for="clinicnote" style="padding:15px 1px 0px 1px">Operation Procedure</label>
                                <textarea id="clinicnote" name="clinicnote" type="text" class="form-control input-sm" rows="5" readonly=""></textarea>


                                <label class="control-label" for="clinicnote" style="padding:15px 1px 0px 1px">Summary Of Treatment</label>
                                <textarea id="clinicnote" name="clinicnote" type="text" class="form-control input-sm" rows="5" readonly=""></textarea>
                            </div>


							<div class="col-md-4">
                                <div class="col-md-12" style="padding:0px">
	                                <div id="jqGrid_doctor_disc_c">
	                                    <div class='col-md-12' style="padding:0 0 15px 0">
	                                        <table id="jqGrid_doctor_disc" class="table table-striped"></table>
	                                        <div id="jqGridPager_doctor_disc"></div>
	                                    </div>
	                                </div>
								</div>


                                <div class="col-md-12" style="padding:0px">
	                                <div class="panel panel-info">
	                                    <div class="panel-heading text-center">Status On Discharge</div>
	                                    <div class="panel-body" style="margin-left: 50px">
	                                    	<div class="form-check">
												<input class="form-check-input" type="radio" name="status_disc" id="status_discWell" value="Well">
												<label class="form-check-label" for="status_discWell">Well</label>
											</div>
	                                    	<div class="form-check">
												<input class="form-check-input" type="radio" name="status_disc" id="status_discImproved" value="Improved">
												<label class="form-check-label" for="status_discImproved">Improved</label>
											</div>
	                                    	<div class="form-check">
												<input class="form-check-input" type="radio" name="status_disc" id="status_discAOR" value="AOR">
												<label class="form-check-label" for="status_discAOR">AOR</label>
											</div>
	                                    	<div class="form-check">
												<input class="form-check-input" type="radio" name="status_disc" id="status_discExpired" value="Expired">
												<label class="form-check-label" for="status_discExpired">Expired</label>
											</div>
	                                    	<div class="form-check">
												<input class="form-check-input" type="radio" name="status_disc" id="status_discAbsconded" value="Absconded">
												<label class="form-check-label" for="status_discAbsconded">Absconded</label>
											</div>
	                                    	<div class="form-check">
												<input class="form-check-input" type="radio" name="status_disc" id="status_discTransferred" value="Transferred">
												<label class="form-check-label" for="status_discTransferred">Transferred</label>
											</div>
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="col-md-12" style="padding:10px">
	                            	<div class="form-check">
										<input class="form-check-input" type="checkbox" name="moa_walkin" id="moa_walkin" value="1">
										<label class="form-check-label" for="moa_walkin">Medication(s) On Discharge</label>
									</div>

									<div class="form-check">
										<input class="form-check-input" type="checkbox" name="moa_walkin" id="moa_walkin" value="1">
										<label class="form-check-label" for="moa_walkin">Medical Certificate Given</label>
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
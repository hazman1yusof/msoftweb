<div class="panel panel-default" style="position: relative;" id="jqGrid_discharge_c">
	
	<div class="panel-heading clearfix collapsed position" id="toggle_discharge" style="position: sticky;top: 0px;z-index: 3;">
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
			<h5>Discharge Summary</h5>
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
		<div class="panel-body paneldiv">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<form class='form-horizontal' style='width:99%' id='form_discharge'>
					<div class='col-md-12'>
						<div class="panel panel-info">
							<div class="panel-heading text-center">DISCHARGE INFORMATION	

								<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
									id="btn_grp_edit_discharge"
									style="position: absolute;
											padding: 0 0 0 0;
											right: 40px;
											top: 5px;">
									<button type="button" class="btn btn-default" id="new_discharge">
										<span class="fa fa-plus-square-o"></span> New
									</button>
									<button type="button" class="btn btn-default" id="edit_discharge">
										<span class="fa fa-edit fa-lg"></span> Edit
									</button>
									<button type="button" class="btn btn-default" data-oper='add' id="save_discharge">
										<span class="fa fa-save fa-lg"></span> Save
									</button>
									<button type="button" class="btn btn-default" id="cancel_discharge">
										<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
									</button>
								</div>

							</div>
							<div class="panel-body">
								<input id="mrn_discharge" name="mrn_discharge" type="hidden">
								<input id="episno_discharge" name="episno_discharge" type="hidden">

								<div class='col-md-12'>
									<!-- <div class="panel panel-info"> -->
										<!-- <div class="panel-heading text-center">Discharge Information</div> -->
										<!-- <div class="panel-body" id="discharge_form"> -->

											<div class="form-group row">
												<label class="col-md-1 control-label" for="mrn_dischargeForm">MRN</label>  
												<div class="col-md-1">
													<input id="mrn_dischargeForm" name="mrn_dischargeForm" type="text" class="form-control input-sm" data-validation="required" rdonly>
												</div>

												<label class="col-md-1 control-label" for="episno_dischargeForm">Episno</label>  
												<div class="col-md-1">
													<input id="episno_dischargeForm" name="episno_dischargeForm" type="text" class="form-control input-sm" data-validation="required" rdonly>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-md-1 control-label" for="pattype_discharge">Type</label>  
												<div class="col-md-1">
													<input id="pattype_discharge" name="pattype_discharge" type="text" class="form-control input-sm" data-validation="required" rdonly value="{{$type}}">
												</div>
												<div class="col-md-4">
													<input id="pattype_text_discharge" name="pattype_text_discharge" type="text" class="form-control input-sm" data-validation="required" rdonly value="{{$type_desc}}">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-md-1 control-label" for="bedtype_discharge">Bed Type</label>  
												<div class="col-md-1">
													<input id="bedtype_discharge" name="bedtype_discharge" type="text" class="form-control input-sm" rdonly>
												</div>
												<div class="col-md-4">
													<input id="bedtype_text_discharge" name="bedtype_text_discharge" type="text" class="form-control input-sm" rdonly>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-md-1 control-label" for="bed_discharge">Bed</label>  
												<div class="col-md-1">
													<input id="bed_discharge" name="bed_discharge" type="text" class="form-control input-sm"  rdonly>
												</div>

												<label class="col-md-1 control-label" for="room_discharge">Room</label>  
												<div class="col-md-1">
													<input id="room_discharge" name="room_discharge" type="text" class="form-control input-sm"  rdonly>
												</div>
											</div>


											<div class="panel-body highlight">
												<div class="form-group row">
													<label class="col-md-1 control-label" for="reg_date">Reg Date</label>  
													<div class="col-md-2">
														<input id="reg_date" name="reg_date" type="date" class="form-control input-sm" rdonly>
													</div>

													<label class="col-md-1 control-label" for="regby_discharge">Register By</label>  
													<div class="col-md-5">
														<input id="regby_discharge" name="regby_discharge" type="text" class="form-control input-sm" rdonly>
													</div>

													<label class="col-md-1 control-label" for="reg_time">Register Time</label>  
													<div class="col-md-2">
														<input id="reg_time" name="reg_time" type="time" class="form-control input-sm" rdonly>
													</div>
												</div>

												<div class="form-group row">
													<label class="col-md-1 control-label" for="dischargedate">Discharge Date</label>  
													<div class="col-md-2">
														<input id="dischargedate" name="dischargedate" type="date" class="form-control input-sm" readonly>
													</div>

													<label class="col-md-1 control-label" for="dischargeuser">Discharge By</label>  
													<div class="col-md-5">
														<input id="dischargeuser" name="dischargeuser" type="text" class="form-control input-sm" readonly>
													</div>

													<label class="col-md-1 control-label" for="dischargetime">Discharge Time</label>  
													<div class="col-md-2">
														<input id="dischargetime" name="dischargetime" type="time" class="form-control input-sm" readonly>
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
												<label class="control-label" for="diagfinal" style="padding:15px 1px 0px 1px">Final Diagnosis</label>
												<textarea id="diagfinal" name="diagfinal" type="text" class="form-control input-sm" rows="5" rdonly></textarea>

												<label class="control-label" for="patologist" style="padding:15px 1px 0px 1px">Patologist (if related)</label>
												<textarea id="patologist" name="patologist" type="text" class="form-control input-sm" rows="5" rdonly></textarea>

												<label class="control-label" for="clinicalnote" style="padding:15px 1px 0px 1px">History of Illness</label>
												<textarea id="clinicalnote" name="clinicalnote" type="text" class="form-control input-sm" rows="5" rdonly></textarea>

												<label class="control-label" for="phyexam" style="padding:15px 1px 0px 1px">Physical Examination</label>
												<textarea id="phyexam" name="phyexam" type="text" class="form-control input-sm" rows="5" rdonly></textarea>

												<label class="control-label" for="diagprov" style="padding:15px 1px 0px 1px">Investigation</label>
												<textarea id="diagprov" name="diagprov" type="text" class="form-control input-sm" rows="5" rdonly></textarea>
												
												<label class="control-label" for="treatment" style="padding:15px 1px 0px 1px">Treatment & Medication</label>
												<textarea id="treatment" name="treatment" type="text" class="form-control input-sm" rows="5" rdonly></textarea>
												
												<label class="control-label" for="summary" style="padding:15px 1px 0px 1px">Summary</label>
												<textarea id="summary" name="summary" type="text" class="form-control input-sm" rows="5" rdonly></textarea>

												<label class="control-label" for="followup" style="padding:15px 1px 0px 1px">Follow Up</label>
												<textarea id="followup" name="followup" type="text" class="form-control input-sm" rows="5" rdonly></textarea>
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
																<input class="form-check-input" type="radio" name="status_discWell" id="status_discWell" value="Well">
																<label class="form-check-label" for="status_discWell">Well</label>
															</div>
															<div class="form-check">
																<input class="form-check-input" type="radio" name="status_discImproved" id="status_discImproved" value="Improved">
																<label class="form-check-label" for="status_discImproved">Improved</label>
															</div>
															<div class="form-check">
																<input class="form-check-input" type="radio" name="status_discAOR" id="status_discAOR" value="AOR">
																<label class="form-check-label" for="status_discAOR">AOR</label>
															</div>
															<div class="form-check">
																<input class="form-check-input" type="radio" name="status_discExpired" id="status_discExpired" value="Expired">
																<label class="form-check-label" for="status_discExpired">Expired</label>
															</div>
															<div class="form-check">
																<input class="form-check-input" type="radio" name="status_discAbsconded" id="status_discAbsconded" value="Absconded">
																<label class="form-check-label" for="status_discAbsconded">Absconded</label>
															</div>
															<div class="form-check">
																<input class="form-check-input" type="radio" name="status_discTransferred" id="status_discTransferred" value="Transferred">
																<label class="form-check-label" for="status_discTransferred">Transferred</label>
															</div>
														</div>
													</div>
												</div>

												<div class="col-md-12" style="padding:10px">
													<div class="form-check">
														<input class="form-check-input" type="checkbox" name="medondischg" id="medondischg" value="1">
														<label class="form-check-label" for="medondischg">Medication(s) On Discharge</label>
													</div>

													<div class="form-check">
														<input class="form-check-input" type="checkbox" name="medcert" id="medcert" value="1">
														<label class="form-check-label" for="medcert">Medical Certificate Given</label>
													</div>
												</div>
											</div>

										<!-- </div> -->
									<!-- </div> -->
								</div>
							</div>
						</div>
					</div>
				</form>
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
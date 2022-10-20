
<div class="panel panel-default" style="position: relative;" id="jqGridDieteticCareNotes_c">
	
	<div class="panel-heading clearfix collapsed position" id="toggle_dieteticCareNotes" style="position: sticky;top: 0px;z-index: 3;">
		<b>NAME: <span id="name_show_dieteticCareNotes"></span></b><br>
		MRN: <span id="mrn_show_dieteticCareNotes"></span>
		SEX: <span id="sex_show_dieteticCareNotes"></span>
		DOB: <span id="dob_show_dieteticCareNotes"></span>
		AGE: <span id="age_show_dieteticCareNotes"></span>
		RACE: <span id="race_show_dieteticCareNotes"></span>
		RELIGION: <span id="religion_show_dieteticCareNotes"></span><br>
		OCCUPATION: <span id="occupation_show_dieteticCareNotes"></span>
		CITIZENSHIP: <span id="citizenship_show_dieteticCareNotes"></span>
		AREA: <span id="area_show_dieteticCareNotes"></span>

		<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridDieteticCareNotes_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridDieteticCareNotes_panel"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 25px;">
			<h5>Dietetic Care Notes</h5>
		</div>	

		<!-- <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
			id="btn_grp_edit_dieteticCareNotes"
			style="position: absolute;
					padding: 0 0 0 0;
					right: 40px;
					top: 25px;" 

		>
		<button type="button" class="btn btn-default" id="new_dieteticCareNotes">
			<span class="fa fa-plus-square-o"></span> New
		</button>
		<button type="button" class="btn btn-default" id="edit_dieteticCareNotes">
			<span class="fa fa-edit fa-lg"></span> Edit
		</button>
		<button type="button" class="btn btn-default" data-oper='add' id="save_dieteticCareNotes">
			<span class="fa fa-save fa-lg"></span> Save
		</button>
		<button type="button" class="btn btn-default" id="cancel_dieteticCareNotes">
			<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
		</button>
	</div>			 -->
	</div>
	<div id="jqGridDieteticCareNotes_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<!-- <table id="jqGridTriageInfo" class="table table-striped"></table>
				<div id="jqGridPagerTriageInfo"></div> -->

				<form class='form-horizontal' style='width:99%' id='formDieteticCareNotes'>

					<div class='col-md-12'>
						<div class="panel panel-default">
							<div class="panel-heading text-center">DIETETIC CARE NOTES	

								<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
									id="btn_grp_edit_dieteticCareNotes"
									style="position: absolute;
											padding: 0 0 0 0;
											right: 40px;
											top: 5px;">
									<button type="button" class="btn btn-default" id="new_dieteticCareNotes">
										<span class="fa fa-plus-square-o"></span> New
									</button>
									<button type="button" class="btn btn-default" id="edit_dieteticCareNotes">
										<span class="fa fa-edit fa-lg"></span> Edit
									</button>
									<button type="button" class="btn btn-default" data-oper='add' id="save_dieteticCareNotes">
										<span class="fa fa-save fa-lg"></span> Save
									</button>
									<button type="button" class="btn btn-default" id="cancel_dieteticCareNotes">
										<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
									</button>
								</div>

							</div>
							<div class="panel-body">

								<input id="mrn_dieteticCareNotes" name="mrn_dieteticCareNotes" type="hidden">
								<input id="episno_dieteticCareNotes" name="episno_dieteticCareNotes" type="hidden">

								<div class='col-md-10'>
									<div class="panel panel-info">
										<div class="panel-body">

											<div class="col-md-12" style="padding-top:10px">
												<div class="panel panel-info">
													<div class="panel-heading text-center">ASSESSMENT</div>
													<div class="panel-body">

														<div class='col-md-12'>
															<div class="form-group">
																<div class="col-md-6">
																	<label class="control-label" for="ncase_medical_his" style="padding-bottom:5px">Medical History</label>
																	<textarea id="ncase_medical_his" name="ncase_medical_his" type="text" class="form-control input-sm" rows="6"></textarea>
																</div>
															
																<div class="col-md-6">
																	<label class="control-label" for="ncase_surgical_his" style="padding-bottom:5px">Surgical History</label>
																	<textarea id="ncase_surgical_his" name="ncase_surgical_his" type="text" class="form-control input-sm" rows="6"></textarea>
																</div>
															</div>
														</div>

														<div class='col-md-12'>
															<div class="form-group">
																<div class="col-md-6">
																	<label class="control-label" for="ncase_fam_medical_his" style="padding-bottom:5px">Family Medical History</label>
																	<textarea id="ncase_fam_medical_his" name="ncase_fam_medical_his" type="text" class="form-control input-sm" rows="6"></textarea>
																</div>
															</div>
														</div>

													</div>
												</div>
											</div>

											<div class='col-md-12'>
												<div class="form-group">
													<div class="col-md-6">
														<label class="control-label" for="ncase_history" style="padding-bottom:5px">Diet History/Summary</label>
														<textarea id="ncase_history" name="ncase_history" type="text" class="form-control input-sm" rows="6"></textarea>
													</div>
												
													<div class="col-md-6">
														<label class="control-label" for="ncase_diagnosis" style="padding-bottom:5px">Nutrition Diagnosis</label>
														<textarea id="ncase_diagnosis" name="ncase_diagnosis" type="text" class="form-control input-sm" rows="6"></textarea>
													</div>
												</div>

												<div class="form-group">
													<div class="col-md-6">
														<label class="control-label" for="ncase_intervention" style="padding-bottom:5px">Nutrition Intervention/Plan</label>
														<textarea id="ncase_intervention" name="ncase_intervention" type="text" class="form-control input-sm" rows="6"></textarea>
													</div>
												</div>
											</div>

										</div>
									</div>
								</div>

								<div class="col-md-2" style="padding:0 0 0 0">
									<div class="panel panel-info">
										<div class="panel-heading text-center">Anthropometric Measurement</div>
										<div class="panel-body">

											<div class="form-group col-md-12">
												<label class="control-label" for="ncase_temperature" style="padding-bottom:5px">Temperature</label>
												<div class="input-group">
													<input id="ncase_temperature" name="ncase_temperature" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
													<span class="input-group-addon">°C</span>
												</div>
											</div>

											<div class="form-group col-md-12">
												<label class="control-label" for="ncase_pulse" style="padding-bottom:5px">Pulse</label>
												<input id="ncase_pulse" name="ncase_pulse" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
											</div>

											<div class="form-group col-md-12">
												<label class="control-label" for="ncase_respiration" style="padding-bottom:5px">Respiration</label>
												<input id="ncase_respiration" name="ncase_respiration" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
											</div>

											<div class="form-group col-md-12">
												<label class="control-label" for="ncase_bp" style="padding-bottom:5px">Blood Pressure</label>
												<div class="input-group">
													<input id="ncase_bp_sys1" name="ncase_bp_sys1" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
													<input id="ncase_bp_dias2" name="ncase_bp_dias2" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
													<span class="input-group-addon">mmHg</span>
												</div>
											</div>

											<div class="form-group col-md-12">
												<label class="control-label" for="ncase_height" style="padding-bottom:5px">Height</label>
												<div class="input-group">
													<input id="ncase_height" name="ncase_height" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
													<span class="input-group-addon">cm</span>
												</div>
											</div>

											<div class="form-group col-md-12">
												<label class="control-label" for="ncase_weight" style="padding-bottom:5px">Weight</label>
												<div class="input-group">
													<input id="ncase_weight" name="ncase_weight" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
													<span class="input-group-addon">kg</span>
												</div>
											</div>

											<div class="form-group col-md-12">
												<label class="control-label" for="ncase_bmi" style="padding-bottom:5px">BMI</label>
												<input id="ncase_bmi" name="ncase_bmi" type="number" class="form-control input-sm" rdonly>
											</div>

											<div class="form-group col-md-12">
												<label class="control-label" for="ncase_gxt" style="padding-bottom:5px">GXT</label>
												<div class="input-group">
													<input id="ncase_gxt" name="ncase_gxt" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
													<span class="input-group-addon">mmOL</span>
												</div>
											</div>

											<div class="form-group col-md-12">
												<label class="control-label" for="ncase_pain_score" style="padding-bottom:5px">Pain Score</label>
												<div class="input-group">
													<input id="ncase_pain_score" name="ncase_pain_score" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
													<span class="input-group-addon">/10</span>
												</div>
											</div>

										</div>
									</div>
								</div>

							</div>
						</div>
					</div>

				</form>

				<form class='form-horizontal' style='width:99%' id='formDieteticCareNotes_fup'>

					<div class='col-md-12'>
						<div class="panel panel-default">
							<div class="panel-heading text-center">FOLLOW UP DIETETIC CARE NOTES	

								<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
									id="btn_grp_edit_dieteticCareNotes_fup"
									style="position: absolute;
											padding: 0 0 0 0;
											right: 40px;
											top: 5px;">
									<button type="button" class="btn btn-default" id="new_dieteticCareNotes_fup">
										<span class="fa fa-plus-square-o"></span> New
									</button>
									<button type="button" class="btn btn-default" id="edit_dieteticCareNotes_fup">
										<span class="fa fa-edit fa-lg"></span> Edit
									</button>
									<button type="button" class="btn btn-default" data-oper='add' id="save_dieteticCareNotes_fup">
										<span class="fa fa-save fa-lg"></span> Save
									</button>
									<button type="button" class="btn btn-default" id="cancel_dieteticCareNotes_fup">
										<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
									</button>
								</div>	
							
							</div>
							<div class="panel-body">

								<div class="col-md-4" style="padding:0 0 0 0">
									<div class="panel panel-info">
										<div class="panel-body">
											<table id="dietetic_date_tbl" class="ui celled table" style="width: 100%;">
												<thead>
													<tr>
														<th class="scope">mrn</th>
														<th class="scope">episno</th>
														<th class="scope">Date</th>
														<th class="scope">adduser</th>
														<th class="scope">Doctor</th>
													</tr>
												</thead>
											</table>
										</div>
									</div>
								</div>

								<div class='col-md-8'>
									<div class='col-md-9'>
										<div class="panel panel-info">
											<div class="panel-body">

												<input id="mrn_dieteticCareNotes_fup" name="mrn_dieteticCareNotes_fup" type="hidden">
												<input id="episno_dieteticCareNotes_fup" name="episno_dieteticCareNotes_fup" type="hidden">
												<input id="fup_recordtime" name="fup_recordtime" type="hidden">

												<div class='col-md-12'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">MONITORING AND EVALUATION</div>
														<div class="panel-body">

															<div class="col-md-12">
																<label class="control-label" for="fup_progress" style="padding-bottom:5px;text-align:left;">Comment on Progress (Anthropometry/Biochemical/Clinical/Dietary)</label>
																<textarea id="fup_progress" name="fup_progress" type="text" class="form-control input-sm" rows="6"></textarea>
															</div>
														
															<div class="col-md-12">
																<label class="control-label" for="fup_diagnosis" style="padding-bottom:5px">Nutrition Diagnosis</label>
																<textarea id="fup_diagnosis" name="fup_diagnosis" type="text" class="form-control input-sm" rows="6"></textarea>
															</div>

														</div>
													</div>
												</div>

												<div class='col-md-12'>
													<div class="form-group">
														<div class="col-md-12">
															<label class="control-label" for="fup_intervention" style="padding-bottom:5px">Nutrition Intervention</label>
															<textarea id="fup_intervention" name="fup_intervention" type="text" class="form-control input-sm" rows="6"></textarea>
														</div>
													</div>
												</div>

											</div>
										</div>
									</div>

									<div class="col-md-3" style="padding:0 0 0 0">
										<div class="panel panel-info">
											<div class="panel-heading text-center">Anthropometric Measurement</div>
											<div class="panel-body">

												<div class="form-group col-md-12">
													<label class="control-label" for="fup_temperature" style="padding-bottom:5px">Temperature</label>
													<div class="input-group">
														<input id="fup_temperature" name="fup_temperature" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
														<span class="input-group-addon">°C</span>
													</div>
												</div>

												<div class="form-group col-md-12">
													<label class="control-label" for="fup_pulse" style="padding-bottom:5px">Pulse</label>
													<input id="fup_pulse" name="fup_pulse" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
												</div>

												<div class="form-group col-md-12">
													<label class="control-label" for="fup_respiration" style="padding-bottom:5px">Respiration</label>
													<input id="fup_respiration" name="fup_respiration" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
												</div>

												<div class="form-group col-md-12">
													<label class="control-label" for="fup_bp" style="padding-bottom:5px">Blood Pressure</label>
													<div class="input-group">
														<input id="fup_bp_sys1" name="fup_bp_sys1" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
														<input id="fup_bp_dias2" name="fup_bp_dias2" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
														<span class="input-group-addon">mmHg</span>
													</div>
												</div>

												<div class="form-group col-md-12">
													<label class="control-label" for="fup_height" style="padding-bottom:5px">Height</label>
													<div class="input-group">
														<input id="fup_height" name="fup_height" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
														<span class="input-group-addon">cm</span>
													</div>
												</div>

												<div class="form-group col-md-12">
													<label class="control-label" for="fup_weight" style="padding-bottom:5px">Weight</label>
													<div class="input-group">
														<input id="fup_weight" name="fup_weight" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
														<span class="input-group-addon">kg</span>
													</div>
												</div>

												<div class="form-group col-md-12">
													<label class="control-label" for="fup_bmi" style="padding-bottom:5px">BMI</label>
													<input id="fup_bmi" name="fup_bmi" type="number" class="form-control input-sm" rdonly>
												</div>

												<div class="form-group col-md-12">
													<label class="control-label" for="fup_gxt" style="padding-bottom:5px">GXT</label>
													<div class="input-group">
														<input id="fup_gxt" name="fup_gxt" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
														<span class="input-group-addon">mmOL</span>
													</div>
												</div>

												<div class="form-group col-md-12">
													<label class="control-label" for="fup_pain_score" style="padding-bottom:5px">Pain Score</label>
													<div class="input-group">
														<input id="fup_pain_score" name="fup_pain_score" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
														<span class="input-group-addon">/10</span>
													</div>
												</div>
												
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>

				</form>

			</div>
		</div>
	</div>	
</div>
	
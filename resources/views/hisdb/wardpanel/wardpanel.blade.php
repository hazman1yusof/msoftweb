
<div class="panel panel-default" style="position: relative;" id="jqGridWard_c">
	
	<div class="panel-heading clearfix collapsed position" id="toggle_ward" style="position: sticky;top: 0px;z-index: 3;">
		<b>NAME: <span id="name_show_ward"></span></b><br>
		MRN: <span id="mrn_show_ward"></span>
		SEX: <span id="sex_show_ward"></span>
		DOB: <span id="dob_show_ward"></span>
		AGE: <span id="age_show_ward"></span>
		RACE: <span id="race_show_ward"></span>
		RELIGION: <span id="religion_show_ward"></span><br>
		OCCUPATION: <span id="occupation_show_ward"></span>
		CITIZENSHIP: <span id="citizenship_show_ward"></span>
		AREA: <span id="area_show_ward"></span>

		<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridWard_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridWard_panel"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 310px; top: 25px;">
			<h5>Nursing Assessment</h5>
		</div>
		<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
			id="btn_grp_edit_ward"
			style="position: absolute;
					padding: 0 0 0 0;
					right: 40px;
					top: 25px;" 

		>
		<button type="button" class="btn btn-default" id="new_ward">
			<span class="fa fa-plus-square-o"></span> New
		</button>
		<button type="button" class="btn btn-default" id="edit_ward">
			<span class="fa fa-edit fa-lg"></span> Edit
		</button>
		<button type="button" class="btn btn-default" data-oper='add' id="save_ward">
			<span class="fa fa-save fa-lg"></span> Save
		</button>
		<button type="button" class="btn btn-default" id="cancel_ward" >
			<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
		</button>
	</div>
	</div>
	<div id="jqGridWard_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<!-- <table id="jqGridTriageInfo" class="table table-striped"></table>
				<div id="jqGridPagerTriageInfo"></div> -->

				<form class='form-horizontal' style='width:99%' id='formWard'>

					<div class='col-md-6'>
						<div class="panel panel-info">
							<div class="panel-heading text-center">INFORMATION</div>
							<div class="panel-body">

								<input id="mrn_ward" name="mrn_ward" type="hidden">
								<input id="episno_ward" name="episno_ward" type="hidden">

								<div class="form-group">
									<label class="col-md-2 control-label" for="admwardtime">Time</label>  
									<div class="col-md-4">
										<input name="admwardtime" type="time" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter time.">
									</div>

									<label class="col-md-3 control-label" for="triagecolor">Triage Color Zone</label>  
									<div class="col-md-3">
										<div class='input-group'>
											<input name="triagecolor" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please select color zone.">
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										</div>
										<span class="help-block"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="reg_date">Date</label>  
									<div class="col-md-5">
										<input name="reg_date" type="date" class="form-control input-sm" rdonly>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="admreason" >Chief Complain</label>  
									<div class="col-md-10">
										<textarea name="admreason" type="text" class="form-control input-sm" rows="4" data-validation="required" data-validation-error-msg-required="Please enter information."></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="medicalhistory">Medical History</label>  
									<div class="col-md-10">
										<textarea name="medicalhistory" type="text" class="form-control input-sm" rows="4" data-validation="required" data-validation-error-msg-required="Please enter information."></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="surgicalhistory">Surgical History</label>  
									<div class="col-md-10">
										<textarea name="surgicalhistory" type="text" class="form-control input-sm" rows="4" data-validation="required" data-validation-error-msg-required="Please enter information."></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="familymedicalhist">Family Medical History</label>  
									<div class="col-md-10">
										<textarea name="familymedicalhist" type="text" class="form-control input-sm" rows="4" data-validation="required" data-validation-error-msg-required="Please enter information."></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="currentmedication">Current Medication</label>  
									<div class="col-md-10">
										<textarea name="currentmedication" type="text" class="form-control input-sm" rows="4" data-validation="required" data-validation-error-msg-required="Please enter information."></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="diagnosis">Diagnosis</label>  
									<div class="col-md-10">
										<textarea name="diagnosis" type="text" class="form-control input-sm" rows="4" data-validation="required" data-validation-error-msg-required="Please enter information."></textarea>
									</div>
								</div>

								<div class="panel panel-info">
									<div class="panel-heading text-center">ALLERGIES</div>
									<div class="panel-body">

										<table class="table table-sm table-hover">
											<tbody>
												<tr>
													<td><input class="form-check-input" type="checkbox" id="allergydrugs" name="allergydrugs" value="1"></td>
													<td><label class="form-check-label" for="allergydrugs">Drugs</label></td>
													<td><textarea id="drugs_remarks" name="drugs_remarks" type="text" class="form-control input-sm" rows="3"></textarea></td>
												</tr>
												<tr>
													<td><input class="form-check-input" type="checkbox" id="allergyplaster" name="allergyplaster" value="1"></td>
													<td><label class="form-check-label" for="allergyplaster">Plaster</label></td>
													<td><textarea id="plaster_remarks" name="plaster_remarks" type="text" class="form-control input-sm" rows="3"></textarea></td>
												</tr>
												<tr>
													<td><input class="form-check-input" type="checkbox" id="allergyfood" name="allergyfood" value="1"></td>
													<td><label class="form-check-label" for="allergyfood">Food</label></td>
													<td><textarea id="food_remarks" name="food_remarks" type="text" class="form-control input-sm" rows="3"></textarea></td>
												</tr>
												<tr>
													<td><input class="form-check-input" type="checkbox" id="allergyenvironment" name="allergyenvironment" value="1"></td>
													<td><label class="form-check-label" for="allergyenvironment">Environment</label></td>
													<td><textarea id="environment_remarks" name="environment_remarks" type="text" class="form-control input-sm" rows="3"></textarea></td>
												</tr>
												<tr>
													<td><input class="form-check-input" type="checkbox" id="allergyothers" name="allergyothers" value="1"></td>
													<td><label class="form-check-label" for="allergyothers">Others</label></td>
													<td><textarea id="others_remarks" name="others_remarks" type="text" class="form-control input-sm" rows="3"></textarea></td>
												</tr>
												<tr>
													<td><input class="form-check-input" type="checkbox" id="allergyunknown" name="allergyunknown" value="1"></td>
													<td><label class="form-check-label" for="allergyunknown">Unknown</label></td>
													<td><textarea id="unknown_remarks" name="unknown_remarks" type="text" class="form-control input-sm" rows="3"></textarea></td>
												</tr>
												<tr>
													<td><input class="form-check-input" type="checkbox" id="allergynone" name="allergynone" value="1"></td>
													<td><label class="form-check-label" for="allergynone">None</label></td>
													<td><textarea id="none_remarks" name="none_remarks" type="text" class="form-control input-sm" rows="3"></textarea></td>
												</tr>
											</tbody>
										</table>

									</div>
								</div>

							</div>
						</div>
					</div>

					<div class='col-md-6'>
						<div class="panel panel-info">
							<div class="panel-heading text-center">CONDITION ON ADMISSION</div>
							<div class="panel-body">

								<div class='col-md-12'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">VITAL SIGN</div>
										<div class="panel-body">

											<div class="form-row">
												<div class="form-group col-md-6" style="margin-left: 2px">
													<label for="vs_temperature">Temperature</label>  
													<div class="input-group">
														<input name="vs_temperature" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter temperature." data-validation-error-msg-container="#error-temperature">
														<span class="input-group-addon">°C</span>
													</div>
													<div class="error-msg" id="error-temperature"></div>
												</div>
												<div class="form-group col-md-6" style="margin-left: 2px">
													<label for="vs_pulse">Pulse</label>  
													<div class="input-group">
														<input name="vs_pulse" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter pulse." data-validation-error-msg-container="#error-pulse">
														<span class="input-group-addon">/min</span>
													</div>
													<div class="error-msg" id="error-pulse"></div>
												</div>
											</div>

											<div class="form-row">
												<div class="form-group col-md-6" style="margin-left: 2px">
													<label for="vs_respiration">Respiration</label>  
													<div class="input-group">
														<input name="vs_respiration" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter respiratory rate." data-validation-error-msg-container="#error-respiration">
														<span class="input-group-addon">/min</span>
													</div>
													<div class="error-msg" id="error-respiration"></div>
												</div>
												<div class="form-group col-md-6" style="margin-left: 2px">
													<label for="vs_bloodpressure">Blood Pressure</label>
													<div class="input-group">
														<input name="vs_bp_sys1" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter systolic reading." data-validation-error-msg-container="#error-bp_sys1">
														<!-- <label class="col-md-1 control-label">/</label>  -->
														<input name="vs_bp_dias2" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter diastolic reading." data-validation-error-msg-container="#error-bp_dias2">
														<span class="input-group-addon">/mmHg</span>
													</div>
													<div class="error-msg" id="error-bp_sys1"></div>
													<div class="error-msg" id="error-bp_dias2"></div>
												</div>
											</div>

											<div class="form-row">
												<div class="form-group col-md-6" style="margin-left: 2px">
													<label for="vs_height">Height</label> 
													<div class="input-group">
														<input name="vs_height" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter height." data-validation-error-msg-container="#error-height">
														<span class="input-group-addon">cm</span>
													</div>
													<div class="error-msg" id="error-height"></div>
												</div>
												<div class="form-group col-md-6" style="margin-left: 2px">
													<label for="vs_weight">Weight</label> 
													<div class="input-group">
														<input name="vs_weight" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter weight." data-validation-error-msg-container="#error-weight">
														<span class="input-group-addon">kg</span>
													</div>
													<div class="error-msg" id="error-weight"></div>
												</div>
											</div>

											<div class="form-row">
												<div class="form-group col-md-6" style="margin-left: 2px">
													<label for="vs_gxt">GXT</label>  
													<div class="input-group">
														<input name="vs_gxt" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter the value of graded exercise test." data-validation-error-msg-container="#error-gxt">
														<span class="input-group-addon">mmOL</span>
													</div>
													<div class="error-msg" id="error-gxt"></div>
												</div>
												<div class="form-group col-md-6" style="margin-left: 2px">
													<label for="vs_painscore">Pain Score</label>  
													<div class="input-group">
														<input name="vs_painscore" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter pain score." data-validation-error-msg-container="#error-painscore">
														<span class="input-group-addon">/10</span>
													</div>
													<div class="error-msg" id="error-painscore"></div>
												</div>
											</div>

										</div>
									</div>
								</div>

								<div class='col-md-6'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">MODE OF ADMISSION</div>
										<div class="panel-body" style="height: 170px;margin-left: 50px">

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="moa_walkin" value="1">
												<label class="form-check-label" for="moa_walkin">Walk In</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="moa_wheelchair" value="1">
												<label class="form-check-label" for="moa_wheelchair">Wheel Chair</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="moa_trolley" value="1">
												<label class="form-check-label" for="moa_trolley">Trolley</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="moa_others" value="1">
												<label class="form-check-label" for="moa_others">Others</label>
											</div>

										</div>
									</div>
								</div>

								<div class='col-md-6'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">LEVEL OF CONSCIOUSNESS</div>
										<div class="panel-body" style="height: 170px;margin-left: 50px">

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="loc_conscious" value="1">
												<label class="form-check-label" for="loc_conscious">Conscious</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="loc_semiconscious" value="1">
												<label class="form-check-label" for="loc_semiconscious">SemiConscious</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="loc_unconscious" value="1">
												<label class="form-check-label" for="loc_unconscious">UnConscious</label>
											</div>

										</div>
									</div>
								</div>

								<div class='col-md-6'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">MENTAL STATUS</div>
										<div class="panel-body" style="height: 170px;margin-left: 50px">

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="ms_orientated" value="1">
												<label class="form-check-label" for="ms_orientated">Orientated</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="ms_confused" value="1">
												<label class="form-check-label" for="ms_confused">Confused</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="ms_restless" value="1">
												<label class="form-check-label" for="ms_restless">Restless</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="ms_aggressive" value="1">
												<label class="form-check-label" for="ms_aggressive">Aggressive</label>
											</div>

										</div>
									</div>
								</div>

								<div class='col-md-6'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">EMOTIONAL STATUS</div>
										<div class="panel-body" style="height: 170px;margin-left: 50px">

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="es_calm" value="1">
												<label class="form-check-label" for="es_calm">Calm</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="es_anxious" value="1">
												<label class="form-check-label" for="es_anxious">Anxious</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="es_distress" value="1">
												<label class="form-check-label" for="es_distress">Distress</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="es_depressed" value="1">
												<label class="form-check-label" for="es_depressed">Depressed</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="es_irritable" value="1">
												<label class="form-check-label" for="es_irritable">Irritable</label>
											</div>

										</div>
									</div>
								</div>

								<div class='col-md-12'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">FALL RISK ASSESSMENT</div>
										<div class="panel-body" style="margin-left: 50px">

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="fra_prevfalls" value="1">
												<label class="form-check-label" for="fra_prevfalls">Previous falls</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="fra_age" value="1">
												<label class="form-check-label" for="fra_age">Age 60 years or older</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="fra_physicalLimitation" value="1">
												<label class="form-check-label" for="fra_physicalLimitation">Physical limitation-visual & mobility</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="fra_neurologicaldeficit" value="1">
												<label class="form-check-label" for="fra_neurologicaldeficit">Neurological deficit-confusion & disorientation</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="fra_dizziness" value="1">
												<label class="form-check-label" for="fra_dizziness">Dizziness associated with drugs</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="fra_cerebralaccident" value="1">
												<label class="form-check-label" for="fra_cerebralaccident">Cerebral Vascular Accident</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="fra_notatrisk" value="1">
												<label class="form-check-label" for="fra_notatrisk">Not at risk</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="fra_atrisk" value="1">
												<label class="form-check-label" for="fra_atrisk">At risk</label>
											</div>

										</div>
									</div>
								</div>

								<div class='col-md-12'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">PRESSURE SORE RISK ASSESSMENT</div>
										<div class="panel-body" style="margin-left: 50px">

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="psra_incontinent" value="1">
												<label class="form-check-label" for="psra_incontinent">Incontinent</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="psra_immobility" value="1">
												<label class="form-check-label" for="psra_immobility">Immobility / Restricted mobility</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="psra_poorskintype" value="1">
												<label class="form-check-label" for="psra_poorskintype">Poor skin type</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="psra_notatrisk" value="1">
												<label class="form-check-label" for="psra_notatrisk">Not at risk</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="psra_atrisk" value="1">
												<label class="form-check-label" for="psra_atrisk">At risk</label>
											</div>

										</div>
									</div>
								</div>
																
							</div>
						</div>
					</div>

					<div class='col-md-12'>
						<div class="panel panel-default">
							<div class="panel-heading text-center panelbgcolor">ACTIVITIES OF DAILY LIVING</div>
							<div class="panel-body">

								<div class='col-md-5'>

									<div class='col-md-12'>
										<div class="panel panel-info">
											<div class="panel-heading text-center">BREATHING</div>
											<div class="panel-body">

												<div class="form-group">
													<label class="col-sm-4 control-label" for="br_breathing">Any Difficulties In Breathing?</label>  
													<label class="radio-inline">
														<input type="radio" name="br_breathing" value="1">Yes
													</label>
													<label class="radio-inline">
														<input type="radio" name="br_breathing" value="0">No
													</label>
												</div>

												<div class="form-group">
													<label class="col-md-4 control-label" for="br_breathingdesc">If Yes, Describe:</label>  
													<div class="col-md-8" style="padding-left: 0px">
														<textarea name="br_breathingdesc" type="text" class="form-control input-sm" rows="3"></textarea>
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-4 control-label" for="br_cough">Have Any Cough?</label>  
													<label class="radio-inline">
														<input type="radio" name="br_cough" value="1">Yes
													</label>
													<label class="radio-inline">
														<input type="radio" name="br_cough" value="0">No
													</label>
												</div>

												<div class="form-group">
													<label class="col-md-4 control-label" for="br_coughdesc">If Yes, Describe:</label>  
													<div class="col-md-8" style="padding-left: 0px">
														<textarea name="br_coughdesc" type="text" class="form-control input-sm" rows="3"></textarea>
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-4 control-label" for="br_smoke">Does He/She Smoke?</label>  
													<label class="radio-inline">
														<input type="radio" name="br_smoke" value="1">Yes
													</label>
													<label class="radio-inline">
														<input type="radio" name="br_smoke" value="0">No
													</label>
												</div>

												<div class="form-group">
													<label class="col-md-4 control-label" for="br_smokedesc">If Yes, Amount:</label>  
													<div class="col-md-8" style="padding-left: 0px">
														<textarea name="br_smokedesc" type="text" class="form-control input-sm" rows="3"></textarea>
													</div>
												</div>

											</div>
										</div>
									</div>

									<div class='col-md-12'>
										<div class="panel panel-info">
											<div class="panel-heading text-center">EATING/DRINKING</div>
											<div class="panel-body">

												<div class="form-group">
													<label class="col-md-4 control-label" for="ed_eatdrink">Any Problem with Eating/Drinking?</label>  
													<label class="radio-inline">
														<input type="radio" name="ed_eatdrink" value="1">Yes
													</label>
													<label class="radio-inline">
														<input type="radio" name="ed_eatdrink" value="0">No
													</label>
												</div>

												<div class="form-group">
													<label class="col-md-4 control-label" for="ed_eatdrinkdesc">If Yes, Describe:</label>  
													<div class="col-md-8" style="padding-left: 0px">
														<textarea name="ed_eatdrinkdesc" type="text" class="form-control input-sm" rows="3"></textarea>
													</div>
												</div>

											</div>
										</div>
									</div>

									<div class='col-md-12'>
										<div class="panel panel-info">
											<div class="panel-heading text-center">ELIMINATION BOWEL</div>
											<div class="panel-body">

												<div class="form-group">
													<label class="col-md-6 control-label" for="eb_bowelhabit">Have Notice Any Changes In Bowel Habits Lately?</label>  
													<label class="radio-inline">
														<input type="radio" name="eb_bowelhabit" value="1">Yes
													</label>
													<label class="radio-inline">
														<input type="radio" name="eb_bowelhabit" value="0">No
													</label>
												</div>

												<div class="form-group">
													<label class="col-md-6 control-label" for="eb_bowelmove">Take Any Medication For Bowel Movement?</label>  
													<label class="radio-inline">
														<input type="radio" name="eb_bowelmove" value="1">Yes
													</label>
													<label class="radio-inline">
														<input type="radio" name="eb_bowelmove" value="0">No
													</label>
												</div>

												<div class="form-group">
													<label class="col-md-4 control-label" for="eb_bowelmovedesc">If Yes, Describe:</label>  
													<div class="col-md-8" style="padding-left: 0px">
														<textarea name="eb_bowelmovedesc" type="text" class="form-control input-sm" rows="3"></textarea>
													</div>
												</div>

											</div>
										</div>
									</div>

								</div>

								<div class='col-md-7'>

									<div class='col-md-12'>
										<div class="panel panel-info">
											<div class="panel-heading text-center">SLEEPING</div>
											<div class="panel-body">

												<div class="form-group">
													<label class="col-md-6 control-label" for="sl_sleep">Required Medication To Sleep?</label>  
													<label class="radio-inline">
														<input type="radio" name="sl_sleep" value="1">Yes
													</label>
													<label class="radio-inline">
														<input type="radio" name="sl_sleep" value="0">No
													</label>
												</div>

											</div>
										</div>
									</div>

									<div class='col-md-12'>
										<div class="panel panel-info">
											<div class="panel-body">

												<div class='col-md-4'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">MOBILITY</div>
														<div class="panel-body" style="height: 120px">
														
															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="mobilityambulan" name="mobilityambulan">
																<label class="form-check-label" for="mobilityambulan">Ambulant</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="mobilityassistaid" name="mobilityassistaid">
																<label class="form-check-label" for="mobilityassistaid">Assist With AIDS</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="mobilitybedridden" name="mobilitybedridden">
																<label class="form-check-label" for="mobilitybedridden">Bedridden</label>
															</div>

														</div>
													</div>
												</div>

												<div class='col-md-4'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">PERSONAL HYGIENE</div>
														<div class="panel-body" style="height: 120px; width: 150px">

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="phygiene_self" name="phygiene_self">
																<label class="form-check-label" for="phygiene_self">Self</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="phygiene_needassist" name="phygiene_needassist">
																<label class="form-check-label" for="phygiene_needassist">Need Assistant</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="phygiene_dependant" name="phygiene_dependant">
																<label class="form-check-label" for="phygiene_dependant">Totally Dependant</label>
															</div>

														</div>
													</div>
												</div>

												<div class='col-md-4'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">SAFE ENVIRONMENT</div>
														<div class="panel-body" style="height: 120px;margin-left: 20px">

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="safeenv_siderail" name="safeenv_siderail">
																<label class="form-check-label" for="safeenv_siderail">Siderail</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="safeenv_restraint" name="safeenv_restraint">
																<label class="form-check-label" for="safeenv_restraint">Restraint</label>
															</div>

														</div>
													</div>
												</div>

											</div>
										</div>
									</div>

									<div class='col-md-12'>
										<div class="panel panel-info">
											<div class="panel-heading text-center">COMMUNICATION</div>
											<div class="panel-body">

												<div class='col-md-4'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">SPEECH</div>
														<div class="panel-body" style="height: 170px;margin-left: 20px">

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="cspeech_normal" name="cspeech_normal">
																<label class="form-check-label" for="cspeech_normal">Normal</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="cspeech_slurred" name="cspeech_slurred">
																<label class="form-check-label" for="cspeech_slurred">Slurred</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="cspeech_impaired" name="cspeech_impaired">
																<label class="form-check-label" for="cspeech_impaired">Impaired</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="cspeech_mute" name="cspeech_mute">
																<label class="form-check-label" for="cspeech_mute">Mute</label>
															</div>

														</div>
													</div>
												</div>

												<div class='col-md-4'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">VISION</div>
														<div class="panel-body" style="height: 170px;margin-left: 20px">

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="cvision_normal" name="cvision_normal">
																<label class="form-check-label" for="cvision_normal">Normal</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="cvision_blurring" name="cvision_blurring">
																<label class="form-check-label" for="cvision_blurring">Blurring</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="cvision_doublev" name="cvision_doublev">
																<label class="form-check-label" for="cvision_doublev">Double Vision</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="cvision_blind" name="cvision_blind">
																<label class="form-check-label" for="cvision_blind">Blind</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="cvision_visualaids" name="cvision_visualaids">
																<label class="form-check-label" for="cvision_visualaids">Visual Aids</label>
															</div>

														</div>
													</div>
												</div>

												<div class='col-md-4'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">HEARING</div>
														<div class="panel-body" style="height: 170px">

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="chearing_normal" name="chearing_normal">
																<label class="form-check-label" for="chearing_normal">Normal</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="chearing_deaf" name="chearing_deaf">
																<label class="form-check-label" for="chearing_deaf">Deaf</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="chearing_hardhear" name="chearing_hardhear">
																<label class="form-check-label" for="chearing_hardhear">Hard of Hearing</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="chearing_hearaids" name="chearing_hearaids">
																<label class="form-check-label" for="chearing_hearaids">Hearing Aids</label>
															</div>

														</div>
													</div>
												</div>

											</div>
										</div>
									</div>

									<div class='col-md-12'>
										<div class="panel panel-info">
											<div class="panel-heading text-center">BLADDER</div>
											<div class="panel-body">

												<div class="form-group">
													<label class="col-md-4 control-label" for="bl_urine">Have Any Problem Passing Urine?</label>  
													<label class="radio-inline">
														<input type="radio" name="bl_urine" value="1">Yes
													</label>
													<label class="radio-inline">
														<input type="radio" name="bl_urine" value="0">No
													</label>
												</div>

												<div class="form-group">
													<label class="col-md-4 control-label" for="bl_urinedesc">If Yes, Describe:</label>  
													<div class="col-md-7" style="padding-left: 0px">
														<textarea name="bl_urinedesc" type="text" class="form-control input-sm" rows="3"></textarea>
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-4 control-label" for="bl_urinefreq">How Often Get Up At Night To Pass Urine?</label>  
													<div class="col-md-7" style="padding-left: 0px">
														<textarea name="bl_urinefreq" type="text" class="form-control input-sm" rows="3"></textarea>
													</div>
												</div>

											</div>
										</div>
									</div>

								</div>

							</div>
						</div>
					</div>

					<div class='col-md-12'>
						<div class="panel panel-default">
							<div class="panel-heading text-center panelbgcolor">TRIAGE PHYSICAL ASSESSMENT</div>
							<div class="panel-body">

								<div class='col-md-12'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">PHYSICAL ASSESSMENT - GENERAL</div>
										<div class="panel-body">

											<div class='col-md-6'>

												<div class='col-md-12'>
													<div class="panel panel-info">
														<div class="panel-body" style="height: 371px">

															<div class='col-md-6'>
																<div class="panel panel-info">
																	<div class="panel-heading text-center">SKIN CONDITION</div>
																	<div class="panel-body" style="height: 160px;margin-left: 40px">

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_skindry" name="pa_skindry">
																			<label class="form-check-label" for="pa_skindry">Dry</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_skinodema" name="pa_skinodema">
																			<label class="form-check-label" for="pa_skinodema">Odema</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_skinjaundice" name="pa_skinjaundice">
																			<label class="form-check-label" for="pa_skinjaundice">Jaundice</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_skinnil" name="pa_skinnil">
																			<label class="form-check-label" for="pa_skinnil">NIL</label>
																		</div>

																	</div>
																</div>
															</div>

															<div class='col-md-6'>
																<div class="panel panel-info">
																	<div class="panel-heading text-center">OTHERS</div>
																	<div class="panel-body" style="height: 160px;margin-left: 20px">

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_othbruises" name="pa_othbruises">
																			<label class="form-check-label" for="pa_othbruises">Bruises</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_othdeculcer" name="pa_othdeculcer">
																			<label class="form-check-label" for="pa_othdeculcer">Decubitues Ulcer</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_othlaceration" name="pa_othlaceration">
																			<label class="form-check-label" for="pa_othlaceration">Laceration</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_othdiscolor" name="pa_othdiscolor">
																			<label class="form-check-label" for="pa_othdiscolor">Discolouration</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_othnil" name="pa_othnil">
																			<label class="form-check-label" for="pa_othnil">NIL</label>
																		</div>

																	</div>
																</div>
															</div>
															
														</div>
													</div>
												</div>

												<!-- <div class='col-md-12'>
													<div class="panel panel-info">
														<div class="panel-heading text-center" style="position: relative;"> EXAMINATION 
															<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 10px; top: 2px;">
																<button type="button" class="btn btn-info" id="ward_exam_plus"><span class="fa fa-plus"></span></button>
															</div>
														</div>
														<div class="panel-body" id="ward_exam_div">
															
														</div>
													</div>
												</div> -->

											</div>

											<div class='col-md-6'>
												<div class="panel panel-info">
													<div class="panel-body">

														<div class="form-group">
															<label class="col-md-1 control-label" for="pa_notes" style="margin-bottom: 10px">Notes:</label>  
															<div class="row" style="padding:30px;">
																<textarea name="pa_notes" type="text" class="form-control input-sm" rows="15" data-validation="required" data-validation-error-msg-required="Please enter notes."></textarea>
															</div>
														</div>														

													</div>
												</div>
											</div>

											<div class='col-md-12'>
												<div class="panel panel-info" id="jqGridExam_c">
													<div class="panel-heading text-center">EXAMINATION</div>
													<div class="panel-body">
														<div class='col-md-12' style="padding:0 0 15px 0">
															<table id="jqGridExam" class="table table-striped"></table>
															<div id="jqGridPagerExam"></div>
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

				</form>

			</div>
		</div>
	</div>	
</div>

<div id="dialognewexamForm" title="New exam" >
	<div class='panel panel-info'>
		<div class="panel-body" style="">
			<form class='form-horizontal' style=''>
				<small>Exam Code</small> 
				<input type="text" name="examcode" id="examcode" class="form-control">
				<small>Description</small> 
				<input type="text" name="description" id="description" class="form-control">
			</form>
		</div>
	</div>
</div>
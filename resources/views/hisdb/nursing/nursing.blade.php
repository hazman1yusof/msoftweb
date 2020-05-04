   
<div class="panel panel-default" style="position: relative;" id="jqGridTriageInfo_c">
	<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
		id="btn_grp_edit_ti"
		style="position: absolute;
				padding: 0 0 0 0;
				right: 30px;
				top: 10px;" 

	>
		<button type="button" class="btn btn-default" id="new_ti">
			<span class="fa fa-plus-square-o"></span> New
		</button>
		<button type="button" class="btn btn-default" id="edit_ti">
			<span class="fa fa-edit fa-lg"></span> Edit
		</button>
		<button type="button" class="btn btn-default" data-oper='add' id="save_ti">
			<span class="fa fa-save fa-lg"></span> Save
		</button>
		<button type="button" class="btn btn-default" id="cancel_ti" >
			<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
		</button>
	</div>
	<div class="panel-heading clearfix collapsed" id="toggle_ti" data-toggle="collapse" data-target="#jqGridTriageInfo_panel">
		<b><span id="name_show_ti"></span></b><br>
		<span id="newic_show_ti"></span>
		<span id="sex_show_ti"></span>
		<span id="age_show_ti"></span>
		<span id="race_show_ti"></span>

		<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
		<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 300px; top: 10px;">
			<h5>Triage Information</h5>
		</div>				
	</div>
	<div id="jqGridTriageInfo_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<!-- <table id="jqGridTriageInfo" class="table table-striped"></table>
				<div id="jqGridPagerTriageInfo"></div> -->

				<form class='form-horizontal' style='width:99%' id='formTriageInfo'>

					<div class='col-md-6'>
						<div class="panel panel-info">
							<div class="panel-heading text-center">INFORMATION</div>
							<div class="panel-body">

								<input id="mrn_edit_ti" name="mrn_edit_ti" type="hidden">
								<input id="episno_ti" name="episno_ti" type="hidden">

								<div class="form-group">
									<label class="col-md-2 control-label" for="admwardtime">Time</label>  
									<div class="col-md-4">
										<input id="admwardtime" name="admwardtime" type="time" class="form-control input-sm uppercase" data-validation="required" data-validation-error-msg-required="Please enter time.">
									</div>

									<label class="col-md-3 control-label" for="triagecolor">Triage Color Zone</label>  
									<div class="col-md-3">
										<div class='input-group'>
											<input id="triagecolor" name="triagecolor" type="text" class="form-control input-sm uppercase" data-validation="required" data-validation-error-msg-required="Please select color zone.">
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										</div>
										<span class="help-block"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="reg_date">Date</label>  
									<div class="col-md-5">
										<input id="reg_date" name="reg_date" type="date" class="form-control input-sm uppercase" rdonly>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="admreason" >Chief Complain</label>  
									<div class="col-md-10">
										<textarea id="admreason" name="admreason" type="text" class="form-control input-sm uppercase" rows="4" data-validation="required" data-validation-error-msg-required="Please enter information."></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="medicalhistory">Medical History</label>  
									<div class="col-md-10">
										<textarea id="medicalhistory" name="medicalhistory" type="text" class="form-control input-sm uppercase" rows="4" data-validation="required" data-validation-error-msg-required="Please enter information."></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="surgicalhistory">Surgical History</label>  
									<div class="col-md-10">
										<textarea id="surgicalhistory" name="surgicalhistory" type="text" class="form-control input-sm uppercase" rows="4" data-validation="required" data-validation-error-msg-required="Please enter information."></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="familymedicalhist">Family Medical History</label>  
									<div class="col-md-10">
										<textarea id="familymedicalhist" name="familymedicalhist" type="text" class="form-control input-sm uppercase" rows="4" data-validation="required" data-validation-error-msg-required="Please enter information."></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="currentmedication">Current Medication</label>  
									<div class="col-md-10">
										<textarea id="currentmedication" name="currentmedication" type="text" class="form-control input-sm uppercase" rows="4" data-validation="required" data-validation-error-msg-required="Please enter information."></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="diagnosis">Diagnosis</label>  
									<div class="col-md-10">
										<textarea id="diagnosis" name="diagnosis" type="text" class="form-control input-sm uppercase" rows="4" data-validation="required" data-validation-error-msg-required="Please enter information."></textarea>
									</div>
								</div>

								<div class="panel panel-info">
									<div class="panel-heading text-center">ALLERGIES</div>
									<div class="panel-body">

										<div class="form-group">
											<div class="form-check form-check-inline checkbox-inline" style="margin-left: 50px">
												<input class="form-check-input" type="checkbox" id="allergydrugs" name="allergydrugs" value="1">
												<label class="form-check-label" for="allergydrugs">Drugs</label>
											</div>

											<div class="form-check form-check-inline checkbox-inline">
												<input class="form-check-input" type="checkbox" id="allergyplaster" name="allergyplaster" value="1">
												<label class="form-check-label" for="allergyplaster">Plaster</label>
											</div>

											<div class="form-check form-check-inline checkbox-inline">
												<input class="form-check-input" type="checkbox" id="allergyfood" name="allergyfood" value="1">
												<label class="form-check-label" for="allergyfood">Food</label>
											</div>

											<div class="form-check form-check-inline checkbox-inline">
												<input class="form-check-input" type="checkbox" id="allergyenviroment" name="allergyenviroment" value="1">
												<label class="form-check-label" for="allergyenviroment">Environment</label>
											</div>

											<div class="form-check form-check-inline checkbox-inline">
												<input class="form-check-input" type="checkbox" id="allergynone" name="allergynone" value="1">
												<label class="form-check-label" for="allergynone">None</label>
											</div>
										</div>

										<div class="form-group">
											<div class="form-check form-check-inline checkbox-inline" style="margin-left: 50px">
												<input class="form-check-input" type="checkbox" id="allergyunknown" name="allergyunknown" value="1">
												<label class="form-check-label" for="allergyunknown">Unknown</label>
											</div>

											<div class="form-check form-check-inline checkbox-inline">
												<input class="form-check-input" type="checkbox" id="allergyothers" name="allergyothers" value="1">
												<label class="form-check-label" for="allergyothers">Others</label>
											</div>
										</div>

										<div class="form-group">
											<label class="col-md-4 control-label" for="allergyremarks">If Others, Please specify:</label>  
											<div class="col-md-8">
												<textarea id="allergyremarks" name="allergyremarks" type="text" class="form-control input-sm uppercase" rows="3"></textarea>
											</div>
										</div>

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

											<div class="form-group">
												<label class="col-md-3 control-label" for="vs_temperature">Temperature</label>  
												<div class="col-md-8 input-group">
													<input id="vs_temperature" name="vs_temperature" type="number" class="form-control input-sm uppercase floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter temperature." data-validation-error-msg-container="#error-vs_temperature">
													<span class="input-group-addon">°C</span>
												</div>
												<div class="error-msg" style="margin-left: 110px" id="error-vs_temperature"></div>
											</div>

											<div class="form-group">
												<label class="col-md-3 control-label" for="vs_pulse">Pulse</label>  
												<div class="col-md-8 input-group">
													<input id="vs_pulse" name="vs_pulse" type="number" class="form-control input-sm uppercase floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter pulse." data-validation-error-msg-container="#error-vs_pulse">
													<span class="input-group-addon">/min</span>
												</div>
												<div class="error-msg" style="margin-left: 110px" id="error-vs_pulse"></div>
											</div>

											<div class="form-group">
												<label class="col-md-3 control-label" for="vs_respiration">Respiration</label>  
												<div class="col-md-8 input-group">
													<input id="vs_respiration" name="vs_respiration" type="number" class="form-control input-sm uppercase floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter respiratory rate." data-validation-error-msg-container="#error-vs_respiration">
													<span class="input-group-addon">/min</span>
												</div>
												<div class="error-msg" style="margin-left: 110px" id="error-vs_respiration"></div>
											</div>

											<div class="form-group">
												<label class="col-md-3 control-label" for="vs_bloodpressure">Blood Pressure</label>
												<div class="col-md-8 input-group">
													<input id="vs_bp_sys1" name="vs_bp_sys1" type="number" class="form-control input-sm uppercase floatNumberField" style="width: 95px" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter systolic reading." data-validation-error-msg-container="#error-vs_bp_sys1">
													<label class="col-md-1 control-label">/</label> 
													<input id="vs_bp_dias2" name="vs_bp_dias2" type="number" class="form-control input-sm uppercase floatNumberField" style="width: 95px" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter diastolic reading." data-validation-error-msg-container="#error-vs_bp_dias2">
													<span class="input-group-addon">/mmHg</span>
												</div>
												<div class="error-msg" style="margin-left: 110px" id="error-vs_bp_sys1"></div>
												<div class="error-msg" style="margin-left: 110px" id="error-vs_bp_dias2"></div>
											</div>

											<div class="form-group">
												<label class="col-md-3 control-label" for="vs_height">Height</label>  
												<div class="col-md-8 input-group">
													<input id="vs_height" name="vs_height" type="number" class="form-control input-sm uppercase floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter height." data-validation-error-msg-container="#error-vs_height">
													<span class="input-group-addon">cm</span>
												</div>
												<div class="error-msg" style="margin-left: 110px" id="error-vs_height"></div>
											</div>

											<div class="form-group">
												<label class="col-md-3 control-label" for="vs_weight">Weight</label>  
												<div class="col-md-8 input-group">
													<input id="vs_weight" name="vs_weight" type="number" class="form-control input-sm uppercase floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter weight." data-validation-error-msg-container="#error-vs_weight">
													<span class="input-group-addon">kg</span>
												</div>
												<div class="error-msg" style="margin-left: 110px" id="error-vs_weight"></div>
											</div>
											
											<div class="form-group">
												<label class="col-md-3 control-label" for="vs_gxt">GXT</label>  
												<div class="col-md-8 input-group">
													<input id="vs_gxt" name="vs_gxt" type="number" class="form-control input-sm uppercase floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter GXT." data-validation-error-msg-container="#error-vs_gxt">
													<span class="input-group-addon">mmOL</span>
												</div>
												<div class="error-msg" style="margin-left: 110px" id="error-vs_gxt"></div>
											</div>

											<div class="form-group">
												<label class="col-md-3 control-label" for="vs_painscore">Pain Score</label>  
												<div class="col-md-8 input-group">
													<input id="vs_painscore" name="vs_painscore" type="number" class="form-control input-sm uppercase floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter pain score." data-validation-error-msg-container="#error-vs_painscore">
													<span class="input-group-addon">/10</span>
												</div>
												<div class="error-msg" style="margin-left: 110px" id="error-vs_painscore"></div>
											</div>

										</div>
									</div>
								</div>

								<div class='col-md-6'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">MODE OF ADMISSION</div>
										<div class="panel-body" style="height: 170px">

											<div class="form-check" style="margin-left: 50px">
												<input class="form-check-input" type="checkbox" name="moa_walkin" id="moa_walkin" value="1">
												<label class="form-check-label" for="moa_walkin">Walk In</label>
											</div>

											<div class="form-check" style="margin-left: 50px">
												<input class="form-check-input" type="checkbox" name="moa_wheelchair" id="moa_wheelchair" value="1">
												<label class="form-check-label" for="moa_wheelchair">Wheel Chair</label>
											</div>

											<div class="form-check" style="margin-left: 50px">
												<input class="form-check-input" type="checkbox" name="moa_trolley" id="moa_trolley" value="1">
												<label class="form-check-label" for="moa_trolley">Trolley</label>
											</div>

											<div class="form-check" style="margin-left: 50px">
												<input class="form-check-input" type="checkbox" name="moa_others" id="moa_others" value="1">
												<label class="form-check-label" for="moa_others">Others</label>
											</div>

										</div>
									</div>
								</div>

								<div class='col-md-6'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">LEVEL OF CONSCIOUSNESS</div>
										<div class="panel-body" style="height: 170px">

											<div class="form-check" style="margin-left: 40px">
												<input class="form-check-input" type="checkbox" id="loc_conscious" name="loc_conscious" value="1">
												<label class="form-check-label" for="loc_conscious">Conscious</label>
											</div>

											<div class="form-check" style="margin-left: 40px">
												<input class="form-check-input" type="checkbox" id="loc_semiconscious" name="loc_semiconscious" value="1">
												<label class="form-check-label" for="loc_semiconscious">SemiConscious</label>
											</div>

											<div class="form-check" style="margin-left: 40px">
												<input class="form-check-input" type="checkbox" id="loc_unconscious" name="loc_unconscious" value="1">
												<label class="form-check-label" for="loc_unconscious">UnConscious</label>
											</div>

										</div>
									</div>
								</div>

								<div class='col-md-6'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">MENTAL STATUS</div>
										<div class="panel-body" style="height: 170px">

											<div class="form-check" style="margin-left: 50px">
												<input class="form-check-input" type="checkbox" name="ms_orientated" id="ms_orientated" value="1">
												<label class="form-check-label" for="ms_orientated">Orientated</label>
											</div>

											<div class="form-check" style="margin-left: 50px">
												<input class="form-check-input" type="checkbox" name="ms_confused" id="ms_confused" value="1">
												<label class="form-check-label" for="ms_confused">Confused</label>
											</div>

											<div class="form-check" style="margin-left: 50px">
												<input class="form-check-input" type="checkbox" name="ms_restless" id="ms_restless" value="1">
												<label class="form-check-label" for="ms_restless">Restless</label>
											</div>

											<div class="form-check" style="margin-left: 50px">
												<input class="form-check-input" type="checkbox" name="ms_aggressive" id="ms_aggressive" value="1">
												<label class="form-check-label" for="ms_aggressive">Aggressive</label>
											</div>

										</div>
									</div>
								</div>

								<div class='col-md-6'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">EMOTIONAL STATUS</div>
										<div class="panel-body" style="height: 170px">

											<div class="form-check" style="margin-left: 40px">
												<input class="form-check-input" type="checkbox" name="es_calm" id="es_calm" value="1">
												<label class="form-check-label" for="es_calm">Calm</label>
											</div>

											<div class="form-check" style="margin-left: 40px">
												<input class="form-check-input" type="checkbox" name="es_anxious" id="es_anxious" value="1">
												<label class="form-check-label" for="es_anxious">Anxious</label>
											</div>

											<div class="form-check" style="margin-left: 40px">
												<input class="form-check-input" type="checkbox" name="es_distress" id="es_distress" value="1">
												<label class="form-check-label" for="es_distress">Distress</label>
											</div>

											<div class="form-check" style="margin-left: 40px">
												<input class="form-check-input" type="checkbox" name="es_depressed" id="es_depressed" value="1">
												<label class="form-check-label" for="es_depressed">Depressed</label>
											</div>

											<div class="form-check" style="margin-left: 40px">
												<input class="form-check-input" type="checkbox" name="es_irritable" id="es_irritable" value="1">
												<label class="form-check-label" for="es_irritable">Irritable</label>
											</div>

										</div>
									</div>
								</div>
																
							</div>
						</div>
					</div>

					<div class='col-md-12'>
						<div class="panel panel-default">
							<div class="panel-heading text-center">ACTIVITIES OF DAILY LIVING</div>
							<div class="panel-body">

								<div class='col-md-5'>

									<div class='col-md-12'>
										<div class="panel panel-info">
											<div class="panel-heading text-center">BREATHING</div>
											<div class="panel-body">

												<div class="form-group">
													<label class="col-sm-4 control-label" for="br_breathing" style="padding-left: 0px">Any Difficulties In Breathing?</label>  
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
														<textarea id="br_breathingdesc" name="br_breathingdesc" type="text" class="form-control input-sm uppercase" rows="3"></textarea>
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
														<textarea id="br_coughdesc" name="br_coughdesc" type="text" class="form-control input-sm uppercase" rows="3"></textarea>
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
														<textarea id="br_smokedesc" name="br_smokedesc" type="text" class="form-control input-sm uppercase" rows="3"></textarea>
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
													<label class="col-md-4 control-label" for="ed_eatdrink" style="padding-left: 0px">Any Problem with Eating/Drinking?</label>  
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
														<textarea id="ed_eatdrinkdesc" name="ed_eatdrinkdesc" type="text" class="form-control input-sm uppercase" rows="3"></textarea>
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
													<label class="col-md-6 control-label" for="eb_bowelhabit" style="padding-left: 0px">Have Notice Any Changes In Bowel Habis Lately?</label>  
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
														<textarea id="eb_bowelmovedesc" name="eb_bowelmovedesc" type="text" class="form-control input-sm uppercase" rows="3"></textarea>
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
													<label class="col-md-5 control-label" for="bl_urine">Have Any Problem Passing Urine?</label>  
													<label class="radio-inline">
														<input type="radio" name="bl_urine" value="1">Yes
													</label>
													<label class="radio-inline">
														<input type="radio" name="bl_urine" value="0">No
													</label>
												</div>

												<div class="form-group">
													<label class="col-md-5 control-label" for="bl_urinedesc">If Yes, Describe:</label>  
													<div class="col-md-7" style="padding-left: 0px">
														<textarea id="bl_urinedesc" name="bl_urinedesc" type="text" class="form-control input-sm uppercase" rows="3"></textarea>
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-5 control-label" for="bl_urinefreq">How Often Get Up At Night To Pass Urine?</label>  
													<div class="col-md-7" style="padding-left: 0px">
														<textarea id="bl_urinefreq" name="bl_urinefreq" type="text" class="form-control input-sm uppercase" rows="3"></textarea>
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
													<label class="col-md-6 control-label" for="sl_sleep" style="padding-left: 0px">Required Medication To Sleep?</label>  
													<label class="radio-inline">
														<input type="radio" name="sl_sleep" value="1">Yes
													</label>
													<label class="radio-inline">
														<input type="radio" name="sl_sleep" value="0">No
													</label>
												</div>

												<div class='col-md-4'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">MOBILITY</div>
														<div class="panel-body" style="padding: 15px 0; height: 120px">
														
															<div class="form-check" style="margin-left: 10px">
																<input class="form-check-input" type="checkbox" value="mobilityambulan" id="mobilityambulan" name="mobilityambulan">
																<label class="form-check-label" for="mobilityambulan">Ambulant</label>
															</div>

															<div class="form-check" style="margin-left: 10px">
																<input class="form-check-input" type="checkbox" value="mobilityassistaid" id="mobilityassistaid" name="mobilityassistaid">
																<label class="form-check-label" for="mobilityassistaid">Assist With AIDS</label>
															</div>

															<div class="form-check" style="margin-left: 10px">
																<input class="form-check-input" type="checkbox" value="mobilitybedridden" id="mobilitybedridden" name="mobilitybedridden">
																<label class="form-check-label" for="mobilitybedridden">Bedridden</label>
															</div>

														</div>
													</div>
												</div>

												<div class='col-md-4'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">PERSONAL HYGIENE</div>
														<div class="panel-body" style="padding: 15px 0; height: 120px">

															<div class="form-check" style="margin-left: 10px">
																<input class="form-check-input" type="checkbox" value="phygiene_self" id="phygiene_self" name="phygiene_self">
																<label class="form-check-label" for="phygiene_self">Self</label>
															</div>

															<div class="form-check" style="margin-left: 10px">
																<input class="form-check-input" type="checkbox" value="phygiene_needassist" id="phygiene_needassist" name="phygiene_needassist">
																<label class="form-check-label" for="phygiene_needassist">Need Assistant</label>
															</div>

															<div class="form-check" style="margin-left: 10px">
																<input class="form-check-input" type="checkbox" value="phygiene_dependant" id="phygiene_dependant" name="phygiene_dependant">
																<label class="form-check-label" for="phygiene_dependant">Totally Dependant</label>
															</div>

														</div>
													</div>
												</div>

												<div class='col-md-4'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">SAFE ENVIRONMENT</div>
														<div class="panel-body" style="height: 120px">

															<div class="form-check" style="margin-left: 20px">
																<input class="form-check-input" type="checkbox" value="safeenv_siderail" id="safeenv_siderail" name="safeenv_siderail">
																<label class="form-check-label" for="safeenv_siderail">Siderail</label>
															</div>

															<div class="form-check" style="margin-left: 20px">
																<input class="form-check-input" type="checkbox" value="safeenv_restraint" id="safeenv_restraint" name="safeenv_restraint">
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
														<div class="panel-body" style="height: 170px">

															<div class="form-check" style="margin-left: 20px">
																<input class="form-check-input" type="checkbox" value="cspeech_normal" id="cspeech_normal" name="cspeech_normal">
																<label class="form-check-label" for="cspeech_normal">Normal</label>
															</div>

															<div class="form-check" style="margin-left: 20px">
																<input class="form-check-input" type="checkbox" value="cspeech_slurred" id="cspeech_slurred" name="cspeech_slurred">
																<label class="form-check-label" for="cspeech_slurred">Slurred</label>
															</div>

															<div class="form-check" style="margin-left: 20px">
																<input class="form-check-input" type="checkbox" value="cspeech_impaired" id="cspeech_impaired" name="cspeech_impaired">
																<label class="form-check-label" for="cspeech_impaired">Impaired</label>
															</div>

															<div class="form-check" style="margin-left: 20px">
																<input class="form-check-input" type="checkbox" value="cspeech_mute" id="cspeech_mute" name="cspeech_mute">
																<label class="form-check-label" for="cspeech_mute">Mute</label>
															</div>

														</div>
													</div>
												</div>

												<div class='col-md-4'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">VISION</div>
														<div class="panel-body" style="padding: 15px 0; height: 170px">

															<div class="form-check" style="margin-left: 20px">
																<input class="form-check-input" type="checkbox" value="cvision_normal" id="cvision_normal" name="cvision_normal">
																<label class="form-check-label" for="cvision_normal">Normal</label>
															</div>

															<div class="form-check" style="margin-left: 20px">
																<input class="form-check-input" type="checkbox" value="cvision_blurring" id="cvision_blurring" name="cvision_blurring">
																<label class="form-check-label" for="cvision_blurring">Blurring</label>
															</div>

															<div class="form-check" style="margin-left: 20px">
																<input class="form-check-input" type="checkbox" value="cvision_doublev" id="cvision_doublev" name="cvision_doublev">
																<label class="form-check-label" for="cvision_doublev">Double Vision</label>
															</div>

															<div class="form-check" style="margin-left: 20px">
																<input class="form-check-input" type="checkbox" value="cvision_blind" id="cvision_blind" name="cvision_blind">
																<label class="form-check-label" for="cvision_blind">Blind</label>
															</div>

															<div class="form-check" style="margin-left: 20px">
																<input class="form-check-input" type="checkbox" value="cvision_visualaids" id="cvision_visualaids" name="cvision_visualaids">
																<label class="form-check-label" for="cvision_visualaids">Visual Aids</label>
															</div>

														</div>
													</div>
												</div>

												<div class='col-md-4'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">HEARING</div>
														<div class="panel-body" style="padding: 15px 0; height: 170px">

															<div class="form-check" style="margin-left: 20px">
																<input class="form-check-input" type="checkbox" value="chearing_normal" id="chearing_normal" name="chearing_normal">
																<label class="form-check-label" for="chearing_normal">Normal</label>
															</div>

															<div class="form-check" style="margin-left: 20px">
																<input class="form-check-input" type="checkbox" value="chearing_deaf" id="chearing_deaf" name="chearing_deaf">
																<label class="form-check-label" for="chearing_deaf">Deaf</label>
															</div>

															<div class="form-check" style="margin-left: 20px">
																<input class="form-check-input" type="checkbox" value="chearing_hardhear" id="chearing_hardhear" name="chearing_hardhear">
																<label class="form-check-label" for="chearing_hardhear">Hard of Hearing</label>
															</div>

															<div class="form-check" style="margin-left: 20px">
																<input class="form-check-input" type="checkbox" value="chearing_hearaids" id="chearing_hearaids" name="chearing_hearaids">
																<label class="form-check-label" for="chearing_hearaids">Hearing Aids</label>
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
					</div>

					<div class='col-md-12'>
						<div class="panel panel-default">
							<div class="panel-heading text-center">TRIAGE PHYSICAL ASSESSMENT</div>
							<div class="panel-body">

								<div class='col-md-12'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">PHYSICAL ASSESSMENT - GENERAL</div>
										<div class="panel-body">

											<div class='col-md-6'>

												<div class='col-md-12'>
													<div class="panel panel-info">
														<div class="panel-body">

															<div class='col-md-6'>
																<div class="panel panel-info">
																	<div class="panel-heading text-center">SKIN CONDITION</div>
																	<div class="panel-body" style="height: 160px">

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_skindry" id="pa_skindry" name="pa_skindry">
																			<label class="form-check-label" for="pa_skindry">Dry</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_skinodema" id="pa_skinodema" name="pa_skinodema">
																			<label class="form-check-label" for="pa_skinodema">Odema</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_skinjaundice" id="pa_skinjaundice" name="pa_skinjaundice">
																			<label class="form-check-label" for="pa_skinjaundice">Jaundice</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_skinnil" id="pa_skinnil" name="pa_skinnil">
																			<label class="form-check-label" for="pa_skinnil">NIL</label>
																		</div>

																	</div>
																</div>
															</div>

															<div class='col-md-6'>
																<div class="panel panel-info">
																	<div class="panel-heading text-center">OTHERS</div>
																	<div class="panel-body" style="height: 160px">

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_othbruises" id="pa_othbruises" name="pa_othbruises">
																			<label class="form-check-label" for="pa_othbruises">Bruises</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_othdeculcer" id="pa_othdeculcer" name="pa_othdeculcer">
																			<label class="form-check-label" for="pa_othdeculcer">Decubitues Ulcer</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_othlaceration" id="pa_othlaceration" name="pa_othlaceration">
																			<label class="form-check-label" for="pa_othlaceration">Laceration</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_othdiscolor" id="pa_othdiscolor" name="pa_othdiscolor">
																			<label class="form-check-label" for="pa_othdiscolor">Discolouration</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_othnil" id="pa_othnil" name="pa_othnil">
																			<label class="form-check-label" for="pa_othnil">NIL</label>
																		</div>

																	</div>
																</div>
															</div>
															
														</div>
													</div>
												</div>

												<div class='col-md-12'>
													<div class="panel panel-info">
														<div class="panel-body">

															<div class="form-group">
																<label class="col-md-1 control-label" for="pa_notes" >Notes:</label>  
																<div class="row">
																	<textarea id="pa_notes" name="pa_notes" type="text" class="form-control input-sm uppercase" rows="6" data-validation="required" data-validation-error-msg-required="Please enter notes."></textarea>
																</div>
															</div>														

														</div>
													</div>
												</div>

											</div>

											<div class='col-md-6'>
												<div class="panel panel-info">
													<div class="panel-heading text-center" style="position: relative;"> EXAMINATION 
														<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 10px; top: 2px;">
															<button type="button" class="btn btn-info" id="exam_plus"><span class="fa fa-plus"></span></button>
														</div>
													</div>
													<div class="panel-body" id="exam_div">
														
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

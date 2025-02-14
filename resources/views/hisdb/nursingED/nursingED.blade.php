
<div class="panel panel-default" style="position: relative;" id="jqGridTriageInfoED_c">
	<div class="panel-heading clearfix collapsed position" id="toggle_tiED" style="position: sticky; top: 0px; z-index: 3;">
		<b>NAME: <span id="name_show_triageED"></span></b><br>
		MRN: <span id="mrn_show_triageED"></span>
		SEX: <span id="sex_show_triageED"></span>
		DOB: <span id="dob_show_triageED"></span>
		AGE: <span id="age_show_triageED"></span>
		RACE: <span id="race_show_triageED"></span>
		RELIGION: <span id="religion_show_triageED"></span><br>
		OCCUPATION: <span id="occupation_show_triageED"></span>
		CITIZENSHIP: <span id="citizenship_show_triageED"></span>
		AREA: <span id="area_show_triageED"></span>
		
		<i class="arrow fa fa-angle-double-up" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridTriageInfoED_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridTriageInfoED_panel"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 310px; top: 25px;">
			<h5>Emergency Nursing Assessment</h5>
		</div>
		
		<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
			id="btn_grp_edit_tiED" 
			style="position: absolute; 
					padding: 0 0 0 0; 
					right: 40px; 
					top: 25px;">
			<button type="button" class="btn btn-default" id="new_tiED">
				<span class="fa fa-plus-square-o"></span> New 
			</button>
			<button type="button" class="btn btn-default" id="edit_tiED">
				<span class="fa fa-edit fa-lg"></span> Edit 
			</button>
			<button type="button" class="btn btn-default" data-oper='add' id="save_tiED">
				<span class="fa fa-save fa-lg"></span> Save 
			</button>
			<button type="button" class="btn btn-default" id="cancel_tiED">
				<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
			</button>
		</div>
	</div>
	
	<div id="jqGridTriageInfoED_panel" class="panel-collapse collapse">
		<div class="panel-body paneldiv" style="overflow-y: auto;">
			<div class='col-md-12' style="padding: 0 0 15px 0;">
				<form class='form-horizontal' style='width: 99%;' id='formTriageInfoED'>
                    <div class='col-md-6'>
                        <div class="panel panel-info">
							<div class="panel-heading text-center">INFORMATION</div>
								<div class="panel-body"  style="height: 700px;">
									<input id="mrn_tiED" name="mrn_tiED" type="hidden">
									<input id="episno_tiED" name="episno_tiED" type="hidden">
									
									<div class="form-group">
										<label class="col-md-2 control-label" for="admwardtime">Time</label>
										<div class="col-md-4">
											<input name="admwardtime" id="admwardtime" type="time" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter time.">
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
										<div class="col-md-4">
											<input id="reg_date" name="reg_date" type="date" class="form-control input-sm" rdonly>
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-md-2 control-label" for="admreason">Presenting History</label>
										<div class="col-md-10">
											<textarea id="admreason" name="admreason" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information."></textarea>
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-md-2 control-label">Medical History</label>  
										<div class="col-md-10">
											<table>
												<tr>
													<td style="margin:0px; padding: 0px 50px 10px 0px;"><label class="checkbox-inline"><input type="checkbox" id="medhis_heartdisease" name="medhis_heartdisease" value="1"> Heart Disease</label></td>
													<td style="margin:0px; padding: 0px 50px 10px 0px;"><label class="checkbox-inline"><input type="checkbox" id="medhis_seizures" name="medhis_seizures" value="1"> Seizures</label></td>
													<td style="margin:0px; padding: 0px 50px 10px 0px;"><label class="checkbox-inline"><input type="checkbox" id="medhis_diabetes" name="medhis_diabetes" value="1"> Diabetes</label></td>
												</tr>
													
												<tr>
													<td style="margin:0px; padding: 0px 50px 10px 0px;"><label class="checkbox-inline"><input type="checkbox" id="medhis_bloodisorder" name="medhis_bloodisorder" value="1"> Blood disorder</label></td>
													<td style="margin:0px; padding: 0px 50px 10px 0px;"><label class="checkbox-inline"><input type="checkbox" id="medhis_hypertension" name="medhis_hypertension" value="1"> Hypertension</label></td>
													<td style="margin:0px; padding: 0px 50px 10px 0px;"><label class="checkbox-inline"><input type="checkbox" id="medhis_asthma" name="medhis_asthma" value="1"> Asthma</label></td>
												</tr>
														
												<tr>
													<td style="margin:0px; padding: 0px 50px 10px 0px;"><label class="checkbox-inline"><input type="checkbox" id="medhis_cva" name="medhis_cva" value="1"> CVA</label></td>
													<td style="margin:0px; padding: 0px 50px 10px 0px;"><label class="checkbox-inline"><input type="checkbox" id="medhis_crf" name="medhis_crf" value="1"> CRF</label></td>
													<td style="margin:0px; padding: 0px 50px 10px 0px;"><label class="checkbox-inline"><input type="checkbox" id="medhis_cancer" name="medhis_cancer" value="1"> Cancer</label></td>
												</tr>

												<tr>
													<td style="margin:0px; padding: 0px 50px 10px 0px;"><label class="checkbox-inline"><input type="checkbox" id="medhis_drugabuse" name="medhis_drugabuse" value="1"> Drug Abuse</label></td>
													<td style="margin:0px; padding: 0px 50px 10px 0px;"><label class="checkbox-inline">
														<input type="checkbox" id="medhis_oth" name="medhis_oth" value="1"> Others:
														<input type="text" id="medhis_oth_note" name="medhis_oth_note">
													</label></td>
													<td><label class="checkbox-inline"></label></td>
												</tr>
											</table>
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-2 control-label" for="diagnosis">Current Medication/Last Dose</label>
										<div class="col-md-10">
											<textarea id="currentmedication" name="currentmedication" type="text" class="form-control input-sm"></textarea>
										</div>
									</div>
									
									<div class="panel panel-info" style="margin-top: 15px;">
										<div class="panel-heading text-center">ALLERGIES</div>
										<div class="panel-body">
											<table class="table table-sm table-hover">
												<tbody>
													<tr>
														<td><input class="form-check-input" type="checkbox" id="allergydrugs" name="allergydrugs" value="1"></td>
														<td><label class="form-check-label" for="allergydrugs">Meds</label></td>
														<td><textarea id="drugs_remarks" name="drugs_remarks" type="text" class="form-control input-sm"></textarea></td>
													</tr>
													<!-- <tr>
														<td><input class="form-check-input" type="checkbox" id="allergyplaster" name="allergyplaster" value="1"></td>
														<td><label class="form-check-label" for="allergyplaster">Plaster</label></td>
														<td><textarea id="plaster_remarks" name="plaster_remarks" type="text" class="form-control input-sm"></textarea></td>
													</tr> -->
													<tr>
														<td><input class="form-check-input" type="checkbox" id="allergyfood" name="allergyfood" value="1"></td>
														<td><label class="form-check-label" for="allergyfood">Food</label></td>
														<td><textarea id="food_remarks" name="food_remarks" type="text" class="form-control input-sm"></textarea></td>
													</tr>
													<!-- <tr>
														<td><input class="form-check-input" type="checkbox" id="allergyenvironment" name="allergyenvironment" value="1"></td>
														<td><label class="form-check-label" for="allergyenvironment">Environment</label></td>
														<td><textarea id="environment_remarks" name="environment_remarks" type="text" class="form-control input-sm"></textarea></td>
													</tr> -->
													<tr>
														<td><input class="form-check-input" type="checkbox" id="allergyothers" name="allergyothers" value="1"></td>
														<td><label class="form-check-label" for="allergyothers">Others</label></td>
														<td><textarea id="others_remarks" name="others_remarks" type="text" class="form-control input-sm"></textarea></td>
													</tr>
													<!-- <tr>
														<td><input class="form-check-input" type="checkbox" id="allergyunknown" name="allergyunknown" value="1"></td>
														<td><label class="form-check-label" for="allergyunknown">Unknown</label></td>
														<td><textarea id="unknown_remarks" name="unknown_remarks" type="text" class="form-control input-sm"></textarea></td>
													</tr>
													<tr>
														<td><input class="form-check-input" type="checkbox" id="allergynone" name="allergynone" value="1"></td>
														<td><label class="form-check-label" for="allergynone">None</label></td>
														<td><textarea id="none_remarks" name="none_remarks" type="text" class="form-control input-sm"></textarea></td>
													</tr> -->
												</tbody>
											</table>
										</div>
									</div>
								</div>
						</div>
                    </div>

                    <div class='col-md-6'>
                        <div class="panel panel-info">
							<div class="panel-heading text-center">CONDITION ON ARRIVAL</div>
								<div class="panel-body">
									<div class='col-md-12'>
										<div class="panel panel-info">
											<div class="panel-heading text-center">VITAL SIGN</div>
											<div class="panel-body">
												<div class="form-row">
													<div class="form-group col-md-6" style="margin-left: 2px;">
														<label for="vs_bloodpressure">BP</label>
														<div class="input-group">
															<input name="vs_bp_sys1" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter systolic reading." data-validation-error-msg-container="#error-vs_bp_sys1" style="width: 50%;">
															<!-- <label class="col-md-1 control-label">/</label> -->
															<input name="vs_bp_dias2" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter diastolic reading." data-validation-error-msg-container="#error-vs_bp_dias2" style="width: 50%;">
															<span class="input-group-addon">mmHg</span>
														</div>
														<div class="error-msg" id="error-vs_bp_sys1"></div>
														<div class="error-msg" id="error-vs_bp_dias2"></div>
													</div>
													
													<div class="form-group col-md-6" style="margin-left: 2px;">
														<label for="vs_spo">SPO2</label>
														<div class="input-group">
															<input name="vs_spo" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter SPO2." data-validation-error-msg-container="#error-vs_spo">
															<span class="input-group-addon">%</span>
														</div>
														<div class="error-msg" id="error-vs_spo"></div>
													</div>
												</div>
												
												<div class="form-row">
													<div class="form-group col-md-6" style="margin-left: 2px;">
														<label for="vs_pulse">Pulse</label>
														<div class="input-group">
															<input name="vs_pulse" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter pulse." data-validation-error-msg-container="#error-vs_pulse">
															<span class="input-group-addon">Bpm</span>
														</div>
														<div class="error-msg" id="error-vs_pulse"></div>
													</div>
													
													<div class="form-group col-md-6" style="margin-left: 2px;">
														<label for="vs_gxt">Glucometer</label>
														<div class="input-group">
															<input name="vs_gxt" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter the value of graded exercise test." data-validation-error-msg-container="#error-vs_gxt">
															<span class="input-group-addon">mmol/L</span>
														</div>
														<div class="error-msg" id="error-vs_gxt"></div>
													</div>
												</div>
												
												<div class="form-row">
													<div class="form-group col-md-6" style="margin-left: 2px;">
														<label for="vs_temperature">Temperature</label>
														<div class="input-group">
															<input name="vs_temperature" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter temperature." data-validation-error-msg-container="#error-vs_temperature">
															<span class="input-group-addon">°C</span>
														</div>
														<div class="error-msg" id="error-vs_temperature"></div>
													</div>
													
													<div class="form-group col-md-6" style="margin-left: 2px;">
														<label for="vs_weight">Weight</label> 
														<div class="input-group">
															<input name="vs_weight" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter weight." data-validation-error-msg-container="#error-vs_weight">
															<span class="input-group-addon">kg</span>
														</div>
														<div class="error-msg" id="error-vs_weight"></div>
													</div>
												</div>
												
												<div class="form-row">
													<div class="form-group col-md-6" style="margin-left: 2px;">
														<label for="vs_respiration">RR</label>
														<div class="input-group">
															<input name="vs_respiration" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter respiratory rate." data-validation-error-msg-container="#error-vs_respiration">
															<span class="input-group-addon">Min</span>
														</div>
														<div class="error-msg" id="error-vs_respiration"></div>
													</div>
													
													<div class="form-group col-md-6" style="margin-left: 2px;">
														<label for="vs_height">Height</label> 
														<div class="input-group">
															<input name="vs_height" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" data-validation="required" data-validation-error-msg-required="Please enter height." data-validation-error-msg-container="#error-vs_height">
															<span class="input-group-addon">cm</span>
														</div>
														<div class="error-msg" id="error-vs_height"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<div class='col-md-6'>
										<div class="panel panel-info">
											<div class="panel-heading text-center">MODE OF ARRIVAL</div>
											<div class="panel-body" style="height: 300px;margin-left: 20px;">
												<div class="form-check">
													<input class="form-check-input" type="checkbox" name="moa_walkin" id="moa_walkin" value="1">
													<label class="form-check-label" for="moa_walkin"> Walk</label>
												</div>

												<div class="form-check">
													<input class="form-check-input" type="checkbox" name="moa_carried" id="moa_carried" value="1">
													<label class="form-check-label" for="moa_carried"> Carried</label>
												</div>

												<div class="form-check">
													<input class="form-check-input" type="checkbox" name="moa_trolley" id="moa_trolley" value="1">
													<label class="form-check-label" for="moa_trolley"> Stretcher</label>
												</div>
												
												<div class="form-check">
													<input class="form-check-input" type="checkbox" name="moa_wheelchair" id="moa_wheelchair" value="1">
													<label class="form-check-label" for="moa_wheelchair"> Wheel Chair</label>
												</div>
												
												<div class="form-check">
													<label class="form-check-label"> Accompanying person:</label>
												</div>

												<div class="form-check">
													<label><input type="checkbox" id="moa_accpera" name="moa_accpera" value="1"> Alone</label>
													<label>
														<input type="checkbox" id="moa_accperna" name="moa_accperna" value="1"> No. of Person
														<input type="text" id="moa_accperna_note" name="moa_accperna_note">
													</label>
												</div>

											</div>
										</div>
									</div>
									
									<div class='col-md-6'>
										<div class="panel panel-info">
											<div class="panel-heading text-center">TREAT PRIOR TO ARRIVAL</div>
											<div class="panel-body" style="height: 300px; margin-left: 20px;">
												<div class="form-check">
													<input class="form-check-input" type="checkbox" name="tpa_oxygen" id="tpa_oxygen" value="1">
													<label class="form-check-label" for="tpa_oxygen"> Oxygen</label>
												</div>
												
												<div class="form-check">
													<input class="form-check-input" type="checkbox" name="tpa_ccollar" id="tpa_ccollar" value="1">
													<label class="form-check-label" for="tpa_ccollar"> C-collar</label>
												</div>
												
												<div class="form-check">
													<input class="form-check-input" type="checkbox" name="tpa_backboard" id="tpa_backboard" value="1">
													<label class="form-check-label" for="tpa_backboard"> Backboard</label>
												</div>

												<div class="form-check">
													<input class="form-check-input" type="checkbox" name="tpa_icepack" id="tpa_icepack" value="1">
													<label class="form-check-label" for="tpa_icepack"> Ice pack</label>
												</div>

												<div class="form-check">
													<input class="form-check-input" type="checkbox" name="tpa_others" id="tpa_others" value="1">
													<label class="form-check-label" for="tpa_others"> Others</label>
												</div>

												<div class="form-check">
													<input class="form-check-input" type="checkbox" name="tpa_medication" id="tpa_medication" value="1">
													<label class="form-check-label" for="tpa_medication"> Medication</label>
													<textarea id="tpa_medication_note" name="tpa_medication_note" type="text" class="form-control input-sm" rows="4"></textarea>

												</div>
											</div>
										</div>
									</div>
								</div>
						</div>
                    </div>

					<div class='col-md-12'>
						<div class="panel panel-info">
							<div class="panel-heading text-center">PHYSICAL ASSESSMENT</div>
							<div class="panel-body">
								<div class='col-md-6'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">GLASGOW COMA SCALE
											<label for="totgsc" style="float:right;">
												Total: <input type="input" name="totgsc" id="totgsc" rdonly>
											</label> 
										</div>
										<div class="panel-body">
											<table class="table table-bordered">
												<tbody>
													<tr>
														<td colspan="2" rowspan="5" class="align-middle">Best Eye Response (E)</td>
													</tr>
													<tr>
														<td>
															<label class="radio-inline">
																<input type="radio" name="gsc_eye" value="4" class="calc">Spontaneous (4)
															</label>
														</td>
													</tr>
													<tr>
														<td>
															<label class="radio-inline">
																<input type="radio" name="gsc_eye" value="3" class="calc">to speech (3)
															</label>
														</td>
													</tr>
													<tr>
														<td>
															<label class="radio-inline">
																	<input type="radio" name="gsc_eye" value="2" class="calc">to pain (2)
															</label>
														</td>
													</tr>
													<tr>
														<td>
															<label class="radio-inline">
																<input type="radio" name="gsc_eye" value="1" class="calc">NIL (1)
															</label>
														</td>
													</tr>

													<tr>
														<td colspan="2" rowspan="6" class="align-middle">Best Verbal Response (V)</td>
													</tr>
													<tr>
														<td>
															<label class="radio-inline">
																<input type="radio" name="gsc_verbal" value="5" class="calc">Orientated (5)
															</label>
														</td>
													</tr>
													<tr>
														<td>
															<label class="radio-inline">
																<input type="radio" name="gsc_verbal" value="4" class="calc">Confused (4)
															</label>
														</td>
													</tr>
													<tr>
														<td>
															<label class="radio-inline">
																	<input type="radio" name="gsc_verbal" value="3" class="calc">Inappropriate (3)
															</label>
														</td>
													</tr>
													<tr>
														<td>
															<label class="radio-inline">
																<input type="radio" name="gsc_verbal" value="2" class="calc">Incomprehensible (2)
															</label>
														</td>
													</tr>
													<tr>
														<td>
															<label class="radio-inline">
																<input type="radio" name="gsc_verbal" value="1" class="calc">NIL (1)
															</label>
														</td>
													</tr>

													<tr>
														<td colspan="2" rowspan="7" class="align-middle">Best Motor Response (M)</td>
													</tr>
													<tr>
														<td>
															<label class="radio-inline">
																<input type="radio" name="gsc_motor" value="6" class="calc">Obey (6)
															</label>
														</td>
													</tr>
													<tr>
														<td>
															<label class="radio-inline">
																<input type="radio" name="gsc_motor" value="5" class="calc">Localize (5)
															</label>
														</td>
													</tr>
													<tr>
														<td>
															<label class="radio-inline">
																	<input type="radio" name="gsc_motor" value="4" class="calc">Withdraws (4)
															</label>
														</td>
													</tr>
													<tr>
														<td>
															<label class="radio-inline">
																<input type="radio" name="gsc_motor" value="3" class="calc">Abnormal flexion (3)
															</label>
														</td>
													</tr>
													<tr>
														<td>
															<label class="radio-inline">
																<input type="radio" name="gsc_motor" value="2" class="calc">Extends (2)
															</label>
														</td>
													</tr>
													<tr>
														<td>
															<label class="radio-inline">
																<input type="radio" name="gsc_motor" value="1" class="calc">NIL (1)
															</label>
														</td>
													</tr>
												</tbody>
											</table>

											<div class="panel panel-info">
												<div class="panel-heading text-center">MENTAL STATUS</div>
												<div class="panel-body" style="margin-left: 20px;">
													<div class="form-check">
														<input class="form-check-input" type="checkbox" name="ms_orientated" id="ms_orientated" value="1">
														<label class="form-check-label" for="ms_orientated"> Orientated</label>
													</div>

													<div class="form-check">
														<input class="form-check-input" type="checkbox" name="ms_confused" id="ms_confused" value="1">
														<label class="form-check-label" for="ms_confused"> Confuse</label>
													</div>

													<div class="form-check">
														<input class="form-check-input" type="checkbox" name="ms_semiconscious" id="ms_semiconscious" value="1">
														<label class="form-check-label" for="ms_semiconscious"> Semiconscious</label>
													</div>
													
													<div class="form-check">
														<input class="form-check-input" type="checkbox" name="ms_unconscious" id="ms_unconscious" value="1">
														<label class="form-check-label" for="ms_unconscious"> Unconscious</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class='col-md-6'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">PAIN SCORE</div>
										<div class="panel-body" for="painscore">
											<table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <td colspan="2" rowspan="2" class="align-middle">No Pain</td>
                                                        <td colspan="2" rowspan="2" class="align-middle">
                                                            <img style="width:80px" src="{{ asset('patientcare/img/painscore/no-pain.png') }}">                                                    
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="painscore" value="0">0
                                                            </label>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2" rowspan="4" class="align-middle">Mild Pain</td>
                                                        <td colspan="2" rowspan="4" class="align-middle">
                                                            <img style="width:80px" src="{{ asset('patientcare/img/painscore/mild-pain.png') }}">                                                    
                                                        </td>                                                
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="painscore" value="1">1
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="painscore" value="2">2
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                    <input type="radio" name="painscore" value="3">3
                                                            </label>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2" rowspan="4" class="align-middle">Moderate Pain</td>
                                                        <td colspan="2" rowspan="4">
                                                            <img style="width:80px" src="{{ asset('patientcare/img/painscore/moderate-pain.png') }}">                                                    
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="painscore" value="4">4
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="painscore" value="5">5
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="painscore" value="6">6
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td colspan="2" rowspan="4" class="align-middle">Severe Pain</td>
                                                        <td colspan="2" rowspan="4">
                                                            <img style="width:80px" src="{{ asset('patientcare/img/painscore/severe-pain.png') }}">                                                    
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="painscore" value="7">7
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="painscore" value="8">8
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                    <input type="radio" name="painscore" value="9">9
                                                            </label>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2" rowspan="2" class="align-middle">Worst Pain</td>
                                                        <td colspan="2" rowspan="2">
                                                            <img style="width:80px" src="{{ asset('patientcare/img/painscore/worst-pain.png') }}">                                                    
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="painscore" value="10">10
                                                            </label>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

											<div class="panel panel-info">
												<div class="panel-heading text-center">DESCRIPTION OF PAIN</div>
												<div class="panel-body" style="margin-left: 20px;">
													<div class="form-check">
														<input class="form-check-input" type="checkbox" name="dop_arching" id="dop_arching" value="1">
														<label class="form-check-label" for="dop_arching"> Arching</label>
													</div>

													<div class="form-check">
														<input class="form-check-input" type="checkbox" name="dop_throbbing" id="dop_throbbing" value="1">
														<label class="form-check-label" for="dop_throbbing"> Throbbing</label>
													</div>

													<div class="form-check">
														<input class="form-check-input" type="checkbox" name="dop_stabbing" id="dop_stabbing" value="1">
														<label class="form-check-label" for="dop_stabbing"> Stabbing</label>
													</div>
													
													<div class="form-check">
														<input class="form-check-input" type="checkbox" name="dop_sharp" id="dop_sharp" value="1">
														<label class="form-check-label" for="dop_sharp"> Sharp</label>
													</div>

													<div class="form-check">
														<input class="form-check-input" type="checkbox" name="dop_burning" id="dop_burning" value="1">
														<label class="form-check-label" for="dop_burning"> Burning</label>
													</div>

													<div class="form-check">
														<input class="form-check-input" type="checkbox" name="dop_numb" id="dop_numb" value="1">
														<label class="form-check-label" for="dop_numb"> Numb</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class='col-md-6'></div>
								<div class='col-md-6' style="padding: 0px 1px 1px 150px;">
									<div class="form-group row">
										<label for="nursesign" class="col-sm-4 col-form-label">Nurse's Signature:</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" id="nursesign" name="nursesign">
										</div>
									</div>								
								</div>
							</div>
						</div>
					</div>

					<div class='col-md-12'>
						<div class="panel panel-info">
							<div class="panel-heading text-center">NURSE'S NOTE FORM</div>
							<div class="panel-body">
								<div class='col-md-6'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">PLAN & INTERVENTION</div>
										<div class="panel-body;overflow-auto" style="height: 300px">
											<table class="table table-sm table-hover">
												<tbody>
													<tr>
														<td><input class="form-check-input" type="checkbox" id="pi_labinv" name="pi_labinv" value="1"></td>
														<td><label class="form-check-label" for="pi_labinv">Laboratory Investigation's</label></td>
														<td><textarea id="pi_labinv_remarks" name="pi_labinv_remarks" type="text" class="form-control input-sm"></textarea></td>
													</tr>
													<tr>
														<td><input class="form-check-input" type="checkbox" id="pi_bloodprod" name="pi_bloodprod" value="1"></td>
														<td><label class="form-check-label" for="pi_bloodprod">Blood Product</label></td>
														<td><textarea id="pi_bloodprod_remarks" name="pi_bloodprod_remarks" type="text" class="form-control input-sm"></textarea></td>
													</tr>
													<tr>
														<td><input class="form-check-input" type="checkbox" id="pi_diaginv" name="pi_diaginv" value="1"></td>
														<td><label class="form-check-label" for="pi_diaginv">Diagnostic Investigation</label></td>
														<td><textarea id="pi_diaginv_remarks" name="pi_diaginv_remarks" type="text" class="form-control input-sm"></textarea></td>
													</tr>
													<tr>
														<td><input class="form-check-input" type="checkbox" id="pi_ecg" name="pi_ecg" value="1"></td>
														<td><label class="form-check-label" for="pi_ecg">ECG</label></td>
														<td></td>
													</tr>
													<tr>
														<td><input class="form-check-input" type="checkbox" id="pi_codeblue" name="pi_codeblue" value="1"></td>
														<td><label class="form-check-label" for="pi_codeblue">Code Blue</label></td>
														<td></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>

								<div class='col-md-6'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">MAINTENANCE OF SUPPORTS</div>
										<div class="panel-body;overflow-auto" style="height: 300px">
											<table class="table table-sm table-hover">
												<tbody>
													<tr>
														<td><input class="form-check-input" type="checkbox" id="mos_ivfluids" name="mos_ivfluids" value="1"></td>
														<td><label class="form-check-label" for="mos_ivfluids">IV Fluids</label></td>
														<td><textarea id="mos_ivfluids_remarks" name="mos_ivfluids_remarks" type="text" class="form-control input-sm"></textarea></td>
													</tr>
													<tr>
														<td><input class="form-check-input" type="checkbox" id="mos_oxygen" name="mos_oxygen" value="1"></td>
														<td><label class="form-check-label" for="mos_oxygen">Oxygen</label></td>
														<td><textarea id="mos_oxygen_remarks" name="mos_oxygen_remarks" type="text" class="form-control input-sm"></textarea></td>
													</tr>
													<tr>
														<td><input class="form-check-input" type="checkbox" id="mos_woundprep" name="mos_woundprep" value="1"></td>
														<td><label class="form-check-label" for="mos_woundprep">Wound Prep</label></td>
														<td><textarea id="mos_woundprep_remarks" name="mos_woundprep_remarks" type="text" class="form-control input-sm"></textarea></td>
													</tr>
													<tr>
														<td><input class="form-check-input" type="checkbox" id="mos_sci" name="mos_sci" value="1"></td>
														<td><label class="form-check-label" for="mos_sci">Splint/Crutches/Ice Pack</label></td>
														<td></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>

								<div class='col-md-6'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">VITAL SIGN ON DISCHARGE</div>
										<div class="panel-body">
											<div class="form-row">
												<div class="form-group col-md-6" style="margin-left: 2px;">
													<label for="vs_bloodpressure">BP</label>
													<div class="input-group">
														<input name="vsd_bp_sys1" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" style="width: 50%;">
														<!-- <label class="col-md-1 control-label">/</label> -->
														<input name="vsd_bp_dias2" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" style="width: 50%;">
														<span class="input-group-addon">mmHg</span>
													</div>
												</div>
												
												<div class="form-group col-md-6" style="margin-left: 2px;">
													<label for="vsd_pulse">PR</label>
													<div class="input-group">
														<input name="vsd_pulse" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
														<span class="input-group-addon">Bpm</span>
													</div>
												</div>
											</div>
												
											<div class="form-row">
												<div class="form-group col-md-6" style="margin-left: 2px;">
													<label for="vsd_temperature">Temperature</label>
													<div class="input-group">
														<input name="vsd_temperature" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
														<span class="input-group-addon">°C</span>
													</div>
												</div>
												
												<div class="form-group col-md-6" style="margin-left: 2px;">
													<label for="vsd_respiration">RR</label>
													<div class="input-group">
														<input name="vsd_respiration" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
														<span class="input-group-addon">Min</span>
													</div>
												</div>
											</div>
												
											<div class="form-row">
												<div class="form-group col-md-6" style="margin-left: 2px;">
													<label for="vsd_spo">SPO2</label>
													<div class="input-group">
														<input name="vsd_spo" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
														<span class="input-group-addon">%</span>
													</div>
												</div>
												
												<div class="form-group col-md-6" style="margin-left: 2px;">
													<label for="vsd_cbs">CBS</label> 
													<div class="input-group">
														<input name="vsd_cbs" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
														<span class="input-group-addon">mmOL/L</span>
													</div>
												</div>
											</div>
												
											<div class="form-row">
												<div class="form-group col-md-6">
													<label for="vsd_pefr" class="col-sm-6 col-form-label">PEFR</label> 
													<div class="col-sm-12">
														<input name="vsd_pefr" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
													</div>
												</div>
												
												<div class="form-group col-md-6" style="margin-left: 2px;">
													<label for="vsd_gcs" class="col-sm-6 col-form-label">GCS</label> 
													<div class="col-sm-12">
														<input name="vsd_gcs" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
													</div>
												</div>
											</div>

											<div class="form-row">
												<div class="form-group col-md-6">
													<label class="col-sm-6 col-form-label">Pain</label> 
													<div class="col-sm-12">
														<label class="radio-inline">
                                                            <input type="radio" name="vsd_pain" value="0">No <br>
                                                            <input type="radio" name="vsd_pain" value="1">Yes, Score
                                                            <input type="text" id="vsd_painscore" name="vsd_painscore"> 
                                                        </label> 													
													</div>
												</div>

												<div class="form-group col-md-6" style="margin-left: 2px;">
													<label class="col-sm-6 col-form-label"></label> 
													<div class="col-sm-12">
														<label class="radio-inline">
                                                            <br><input type="radio" name="vsd_painroomair" value="vsd_painroomair">on Room air <br>
                                                            <input type="radio" name="vsd_painoxygen" value="vsd_painoxygen">on O2
                                                            <div class="input-group">
																<input name="vsd_painoxygen_note" type="text" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
																<span class="input-group-addon">L/min</span>
															</div>
                                                        </label> 													
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class='col-md-6'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">MODE OF DISCHARGE</div>
										<div class="panel-body" style="height: 355px;margin-left: 20px;">
											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="mod_walk" id="mod_walk" value="1">
												<label class="form-check-label" for="mod_walk"> Walk</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="mod_carried" id="mod_carried" value="1">
												<label class="form-check-label" for="mod_carried"> Carried</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="mod_trolley" id="mod_trolley" value="1">
												<label class="form-check-label" for="mod_trolley"> Stretcher</label>
											</div>
											
											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="mod_wheelchair" id="mod_wheelchair" value="1">
												<label class="form-check-label" for="mod_wheelchair"> Wheel Chair</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="mod_ambulance" id="mod_ambulance" value="1">
												<label class="form-check-label" for="mod_ambulance"> Ambulance</label>
											</div>
										</div>
									</div>
								</div>

								<div class='col-md-12'>
									<div class="row justify-content-evenly">
										<div class="col-3"></div>
										<div class="col-3" style="margin-left: 250px;">
											<label for="eduser" class="col-sm-1 col-form-label">A&E Staff:</label>
											<div class="col-sm-3">
												<input type="text" class="form-control" id="eduser" name="eduser">
											</div>
										</div>
										<div class="col-3">
											<label for="warduser" class="col-sm-1 col-form-label">Ward Staff:</label>
											<div class="col-sm-3">
												<input type="text" class="form-control" id="warduser" name="warduser">
											</div>
										</div>
										<div class="col-3"></div>
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
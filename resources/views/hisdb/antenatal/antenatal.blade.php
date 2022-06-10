
<div class="panel panel-default" style="position: relative;" id="jqGridAntenatal_c">
	
	<div class="panel-heading clearfix collapsed position" id="toggle_antenatal" style="position: sticky;top: 0px;z-index: 3;">
		<b>NAME: <span id="name_show_antenatal"></span></b><br>
		MRN: <span id="mrn_show_antenatal"></span>
		SEX: <span id="sex_show_antenatal"></span>
		DOB: <span id="dob_show_antenatal"></span>
		AGE: <span id="age_show_antenatal"></span>
		RACE: <span id="race_show_antenatal"></span>
		RELIGION: <span id="religion_show_antenatal"></span><br>
		OCCUPATION: <span id="occupation_show_antenatal"></span>
		CITIZENSHIP: <span id="citizenship_show_antenatal"></span>
		AREA: <span id="area_show_antenatal"></span>

		<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridAntenatal_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridAntenatal_panel"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 25px;">
			<h5>Antenatal & Pregnancy</h5>
		</div>
	</div>
	<div id="jqGridAntenatal_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">

				<div class='col-md-12'>
					<div class="panel panel-info">
						<div class="panel-heading text-center">ANTENATAL</div>
						<div class="panel-body">

							<div class='col-md-12'>
								<div class="panel panel-info">
									<div class="panel-heading text-center">PREVIOUS OBSTETRICS HISTORY</div>
									<div class="panel-body">
										<div class='col-md-12' style="padding:0 0 15px 0">
											<table id="jqGridPrevObstetrics" class="table table-striped"></table>
											<div id="jqGridPagerPrevObstetrics"></div>
										</div>
									</div>
								</div>
							</div>

							<div class='col-md-12'>
								<div class="panel panel-info">
									<div class="panel-heading text-center" style="height:40px">	

										<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
											id="btn_grp_edit_antenatal"
											style="position: absolute;
													padding: 0 0 0 0;
													right: 40px;
													top: 5px;">
											<button type="button" class="btn btn-default" id="new_antenatal">
												<span class="fa fa-plus-square-o"></span> New
											</button>
											<button type="button" class="btn btn-default" id="edit_antenatal">
												<span class="fa fa-edit fa-lg"></span> Edit
											</button>
											<button type="button" class="btn btn-default" data-oper='add' id="save_antenatal">
												<span class="fa fa-save fa-lg"></span> Save
											</button>
											<button type="button" class="btn btn-default" id="cancel_antenatal">
												<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
											</button>
										</div>

									</div>
									<div class="panel-body">

										<form class='form-horizontal' style='width:99%' id='formAntenatal'>
											<input id="mrn_antenatal" name="mrn_antenatal" type="hidden">
											<input id="episno_antenatal" name="episno_antenatal" type="hidden">

											<div class='col-md-6' style="padding-left:0px">
												<div class='col-md-12'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">ANTE-NATAL RECORD</div>
														<div class="panel-body">

															<div class="form-row col-md-12">
																<div class="form-group col-md-6" style="margin-left: 2px">
																	<label for="blood_grp">Blood Group</label>
																	<div class='input-group'>
																		<input id="blood_grp" name="blood_grp" type="text" class="form-control input-sm">
																		<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
																	</div>
																	<span class="help-block"></span>
																</div>
																<div class="form-group col-md-6" style="margin-left: 2px">
																	<label for="height">Height</label>  
																	<div class="input-group">
																		<input id="height" name="height" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
																		<span class="input-group-addon">cm</span>
																	</div>
																</div>
															</div>

															<div class="form-row col-md-12">
																<div class="form-group col-md-6" style="margin-left: 2px">
																	<label for="rhesus_factor">Rhesus Factor</label>
																	<!-- <select class="form-control col-md-4" id='rhesus_factor' name='rhesus_factor'>
																		<option value="" selected>Rhesus Factor</option>
																		<option value="rh_positive">RH Positive</option>
																		<option value="rh_negative">RH Negative</option>
																	</select> -->
																	<input class="form-control" list="rhesus_factor1" name="rhesus_factor" id="rhesus_factor">
																	<datalist id="rhesus_factor1">
																		<option value="RH Positive">
																		<option value="RH Negative">
																	</datalist>
																</div>
																<div class="form-group col-md-6" style="margin-left: 2px">
																	<label for="rubella">Rubella Status</label>  
																	<div class="input-group">
																		<input id="rubella" name="rubella" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
																		<span class="input-group-addon">IU/mL</span>
																	</div>
																</div>
															</div>

															<div class="form-row col-md-12">
																<div class="form-group col-md-6" style="margin-left: 2px">
																	<label for="VDRL">VDRL</label>  
																	<div class="input-group">
																		<input id="VDRL" name="VDRL" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
																		<span class="input-group-addon">%</span>
																	</div>
																</div>
																<div class="form-group col-md-6" style="margin-left: 2px">
																	<label for="HIV">HIV</label>  
																	<div class="input-group">
																		<input id="HIV" name="HIV" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
																		<span class="input-group-addon">cells/mm3</span>
																	</div>
																</div>
															</div>

															<div class='col-md-12' style="margin-top: 10px;">
																<div class="panel panel-info">
																	<div class="panel-heading text-center">HEPATITIS B STATUS</div>
																	<div class="panel-body">
																	
																		<div class="form-row">
																			<div class="form-group col-md-6" style="margin-left: 2px">
																				<label for="hep_B_Ag">Ag</label>
																				<!-- <select class="form-control col-md-4" id='hep_B_Ag' name='hep_B_Ag'>
																					<option value="" selected>HBsAg</option>
																					<option value="HBsAg_positive">Positive</option>
																					<option value="HBsAg_reactive">Reactive</option>
																				</select> -->
																				<input class="form-control" list="hep_B_Ag1" name="hep_B_Ag" id="hep_B_Ag">
																				<datalist id="hep_B_Ag1">
																					<option value="Positive">
																					<option value="Reactive">
																				</datalist>
																			</div>
																			<div class="form-group col-md-6" style="margin-left: 2px">
																				<label for="hep_B_AB">AB</label>
																				<!-- <select class="form-control col-md-4" id='hep_B_AB' name='hep_B_AB'>
																					<option value="" selected>HBsAb</option>
																					<option value="HBsAb_positive">Positive</option>
																					<option value="HBsAb_reactive">Reactive</option>
																				</select> -->
																				<input class="form-control" list="hep_B_AB1" name="hep_B_AB" id="hep_B_AB">
																				<datalist id="hep_B_AB1">
																					<option value="Positive">
																					<option value="Reactive">
																				</datalist>
																			</div>
																		</div>

																	</div>
																</div>
															</div>

															<div class='col-md-12' style="margin-top: 10px;">
																<div class="panel panel-info">
																	<div class="panel-heading text-center">ATT INJECTION</div>
																	<div class="panel-body">
																	
																		<div class="form-row">
																			<div class="form-group col-md-6" style="margin-left: 2px">
																				<label for="first_dose">1st dose</label>
																				<input id="first_dose" name="first_dose" type="date" class="form-control input-sm">
																			</div>
																			<div class="form-group col-md-6" style="margin-left: 2px">
																				<label for="second_dose">2nd dose</label>
																				<input id="second_dose" name="second_dose" type="date" class="form-control input-sm">
																			</div>
																		</div>

																		<div class="form-row">
																			<div class="form-group col-md-6" style="margin-left: 2px">
																				<label for="booster">Booster</label>
																				<input id="booster" name="booster" type="date" class="form-control input-sm">
																			</div>
																		</div>

																	</div>
																</div>
															</div>

															<!-- <hr style="width: 100%;background-color: #96cdcd;height: 1px"> -->

															<div class="form-group col-md-10" style="margin-top: 10px;margin-left: 35px">
																<label for="blood_trans">Blood Transfusion</label>
																<input class="form-control" list="blood_trans1" name="blood_trans" id="blood_trans">
																<datalist id="blood_trans1">
																	<option value="Red blood cell transfusions">
																	<option value="Platelet transfusions">
																	<option value="Plasma transfusions">
																	<option value="Whole blood transfusion">
																</datalist>
															</div>

															<!-- <hr style="width: 100%;background-color: #96cdcd;height: 1px"> -->

															<div class="form-group col-md-10" style="margin-top: 10px;margin-left: 35px">
																<label for="drug_allergy">Drug Allergies</label>
																<textarea id="drug_allergy" name="drug_allergy" type="text" class="form-control input-sm" rows="6"></textarea>
															</div>

														</div>
													</div>
												</div>

												<div class='col-md-12'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">SYSTEMATIC EXAMINATION</div>
														<div class="panel-body">

															<div class="form-group">
																<div class="col-md-4">
																	<label class="control-label" for="sysexam_date" style="padding-bottom:5px">Date</label>
																	<input id="sysexam_date" name="sysexam_date" type="date" class="form-control input-sm">
																</div>
															</div>

															<div class="form-group">
																<div class="col-md-4">
																	<label class="control-label" for="sysexam_varicose" style="padding-bottom:5px">Varicose Veins</label>
																	<input id="sysexam_varicose" name="sysexam_varicose" type="text" class="form-control input-sm" style="text-transform: none">
																</div>

																<div class="col-md-4">
																	<label class="control-label" for="sysexam_pallor" style="padding-bottom:5px">Pallor</label>
																	<input id="sysexam_pallor" name="sysexam_pallor" type="text" class="form-control input-sm" style="text-transform: none">
																</div>

																<div class="col-md-4">
																	<label class="control-label" for="sysexam_jaundice" style="padding-bottom:5px">Jaundice</label>
																	<input id="sysexam_jaundice" name="sysexam_jaundice" type="text" class="form-control input-sm" style="text-transform: none">
																</div>
															</div>

															<div class="form-group">
																<div class="col-md-4">
																	<label class="control-label" for="sysexam_oral" style="padding-bottom:5px">Oral Cavity</label>
																	<input id="sysexam_oral" name="sysexam_oral" type="text" class="form-control input-sm" style="text-transform: none">
																</div>

																<div class="col-md-4">
																	<label class="control-label" for="sysexam_thyroid" style="padding-bottom:5px">Thyroid</label>
																	<input id="sysexam_thyroid" name="sysexam_thyroid" type="text" class="form-control input-sm" style="text-transform: none">
																</div>

																<div class="col-md-4">
																	<div class="col-md-12" style="padding-left:0px;">
																		<label class="control-label" for="breast" style="padding-bottom:5px;">Breast</label>
																	</div>
																	<label class="col-md-2 control-label" for="sysexam_breastr" style="padding-left:0px;">(R)</label>
																	<div class="col-md-10">
																		<input id="sysexam_breastr" name="sysexam_breastr" type="text" class="form-control input-sm" style="text-transform: none">
																	</div>
																	<label class="col-md-2 control-label" for="sysexam_breastl" style="padding-top:10px;padding-left:0px;">(L)</label>
																	<div class="col-md-10" style="padding-top:5px;">
																		<input id="sysexam_breastl" name="sysexam_breastl" type="text" class="form-control input-sm" style="text-transform: none">
																	</div>
																</div>
															</div>

															<div class="form-group">
																<div class="col-md-4">
																	<label class="control-label" for="sysexam_cvs" style="padding-bottom:5px">CVS</label>
																	<input id="sysexam_cvs" name="sysexam_cvs" type="text" class="form-control input-sm" style="text-transform: none">
																</div>

																<div class="col-md-4">
																	<label class="control-label" for="sysexam_resp" style="padding-bottom:5px">Resp System</label>
																	<input id="sysexam_resp" name="sysexam_resp" type="text" class="form-control input-sm" style="text-transform: none">
																</div>

																<div class="col-md-4">
																	<label class="control-label" for="sysexam_abdomen" style="padding-bottom:5px">Abdomen</label>
																	<input id="sysexam_abdomen" name="sysexam_abdomen" type="text" class="form-control input-sm" style="text-transform: none">
																</div>
															</div>

															<div class="form-group">
																<div class="col-md-12">
																	<label class="control-label" for="sysexam_remark" style="padding-bottom:5px">Special Remarks</label>
																	<textarea id="sysexam_remark" name="sysexam_remark" type="text" class="form-control input-sm" rows="4"></textarea>
																</div>
															</div>

														</div>
													</div>
												</div>
											</div>

											<div class='col-md-6' style="padding-right:0px">
												<div class='col-md-12'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">PAST GYNAECOLOGICAL HISTORY</div>
														<div class="panel-body">

															<div class="form-check">
																<label class="checkbox-inline" for="pgh_myomectomy" style="margin-left: 50px">
																	<input type="checkbox" name="pgh_myomectomy" id="pgh_myomectomy" value="1">Myomectomy
																</label>
																<label class="checkbox-inline" for="pgh_laparoscopy" style="margin-left: 30px">
																	<input type="checkbox" name="pgh_laparoscopy" id="pgh_laparoscopy" value="1">Laparoscopy
																</label>
																<label class="checkbox-inline" for="pgh_endomet" style="margin-left: 30px">
																	<input type="checkbox" name="pgh_endomet" id="pgh_endomet" value="1">Endometriosis
																</label>
															</div>

															<!-- <div class="form-row"> -->
															<div class="form-group col-md-5" style="margin-top: 10px;margin-left: 35px">
																<label for="pgh_lastpapsmear">Last Pap Smear</label>
																<input id="pgh_lastpapsmear" name="pgh_lastpapsmear" type="date" class="form-control input-sm">
															</div>
															<div class="form-group col-md-10" style="margin-top: 10px;margin-left: 35px">
																<label for="pgh_others">Others</label>
																<textarea id="pgh_others" name="pgh_others" type="text" class="form-control input-sm" rows="8"></textarea>
															</div>
															<!-- </div> -->

														</div>
													</div>
												</div>

												<div class='col-md-12'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">PAST MEDICAL HISTORY</div>
														<div class="panel-body">

															<div class="form-check">
																<label class="checkbox-inline" for="pmh_renal" style="margin-left: 50px">
																	<input type="checkbox" name="pmh_renal" id="pmh_renal" value="1">Renal Disease
																</label>
																<label class="checkbox-inline" for="pmh_hypertension" style="margin-left: 30px">
																	<input type="checkbox" name="pmh_hypertension" id="pmh_hypertension" value="1">Hypertension
																</label>
																<label class="checkbox-inline" for="pmh_diabetes" style="margin-left: 30px">
																	<input type="checkbox" name="pmh_diabetes" id="pmh_diabetes" value="1">Diabetes
																</label>
																<label class="checkbox-inline" for="pmh_heart" style="margin-left: 30px">
																	<input type="checkbox" name="pmh_heart" id="pmh_heart" value="1">Heart Disease
																</label>
															</div>

															<div class="form-group col-md-10" style="margin-top: 10px;margin-left: 35px">
																<label for="pmh_others">Others</label>
																<textarea id="pmh_others" name="pmh_others" type="text" class="form-control input-sm" rows="8"></textarea>
															</div>

														</div>
													</div>
												</div>

												<div class='col-md-12'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">PAST SURGICAL HISTORY</div>
														<div class="panel-body">

															<div class="form-check">
																<label class="checkbox-inline" for="psh_appendic" style="margin-left: 50px">
																	<input type="checkbox" name="psh_appendic" id="psh_appendic" value="1">Appendicectomy
																</label>
																<label class="checkbox-inline" for="psh_hypertension" style="margin-left: 30px">
																	<input type="checkbox" name="psh_hypertension" id="psh_hypertension" value="1">Hypertension
																</label>
																<label class="checkbox-inline" for="psh_laparotomy" style="margin-left: 30px">
																	<input type="checkbox" name="psh_laparotomy" id="psh_laparotomy" value="1">Laparotomy
																</label>
																<label class="checkbox-inline" for="psh_thyroid" style="margin-left: 30px">
																	<input type="checkbox" name="psh_thyroid" id="psh_thyroid" value="1">Thyroid Surgery
																</label>
															</div>

															<div class="form-group col-md-10" style="margin-top: 10px;margin-left: 35px">
																<label for="psh_others">Others</label>
																<textarea id="psh_others" name="psh_others" type="text" class="form-control input-sm" rows="8"></textarea>
															</div>

														</div>
													</div>
												</div>

												<div class='col-md-12'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">FAMILY HISTORY</div>
														<div class="panel-body">

															<div class="form-check">
																<label class="checkbox-inline" for="fh_hypertension" style="margin-left: 50px">
																	<input type="checkbox" name="fh_hypertension" id="fh_hypertension" value="1">Hypertension
																</label>
																<label class="checkbox-inline" for="fh_diabetes" style="margin-left: 30px">
																	<input type="checkbox" name="fh_diabetes" id="fh_diabetes" value="1">Diabetes
																</label>
																<label class="checkbox-inline" for="fh_epilepsy" style="margin-left: 30px">
																	<input type="checkbox" name="fh_epilepsy" id="fh_epilepsy" value="1">Epilepsy
																</label>
																<label class="checkbox-inline" for="fh_multipregnan" style="margin-left: 30px">
																	<input type="checkbox" name="fh_multipregnan" id="fh_multipregnan" value="1">Multiple Pregnancy
																</label>
															</div>

															<div class="form-group col-md-10" style="margin-top: 10px;margin-left: 35px">
																<label for="fh_congenital">Congenital Abnormalities (Specify)</label>
																<textarea id="fh_congenital" name="fh_congenital" type="text" class="form-control input-sm" rows="8"></textarea>
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
					</div>
				</div>

				<div class='col-md-12'>
					<div class="panel panel-info">
						<div class="panel-heading text-center">PREGNANCY</div>
						<div class="panel-body">

							<div class='col-md-12'>
								<div class="panel panel-info">
									<div class="panel-heading text-center" style="height:40px">	

										<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
											id="btn_grp_edit_pregnancy"
											style="position: absolute;
													padding: 0 0 0 0;
													right: 40px;
													top: 5px;">
											<button type="button" class="btn btn-default" id="new_pregnancy">
												<span class="fa fa-plus-square-o"></span> New
											</button>
											<button type="button" class="btn btn-default" id="edit_pregnancy">
												<span class="fa fa-edit fa-lg"></span> Edit
											</button>
											<button type="button" class="btn btn-default" data-oper='add' id="save_pregnancy">
												<span class="fa fa-save fa-lg"></span> Save
											</button>
											<button type="button" class="btn btn-default" id="cancel_pregnancy">
												<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
											</button>
										</div>

									</div>
									<div class="panel-body">

										<form class='form-horizontal' style='width:99%' id='formPregnancy'>
											<input id="mrn_pregnancy" name="mrn_pregnancy" type="hidden">
											<input id="episno_pregnancy" name="episno_pregnancy" type="hidden">

											<div class='col-md-4' style="padding-right: 0px;">
												<div class="panel panel-info">
													<div class="panel-body">
														<div class="form-group">
															<label class="col-md-1 control-label" for="gravida">Gr.</label>
															<div class="col-md-3">
																<input id="gravida" name="gravida" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69">
															</div>

															<label class="col-md-1 control-label" for="para">P</label>
															<div class="col-md-3">
																<input id="para" name="para" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69">
															</div>

															<label class="col-md-1 control-label" for="abortus">+</label>
															<div class="col-md-3">
																<input id="abortus" name="abortus" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69">
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class='col-md-8'>
												<div class="panel panel-info">
													<div class="panel-body">
														<div class="form-group">
															<div class='col-md-12'>
																<label class="col-md-3 control-label" for="lmp">LMP</label>
																<div class="col-md-3">
																	<input id="lmp" name="lmp" type="date" class="form-control input-sm">
																</div>

																<label class="col-md-2 control-label" for="edd">EDD</label>
																<div class="col-md-3">
																	<input id="edd" name="edd" type="date" class="form-control input-sm">
																</div>
															</div>

															<div class='col-md-12' style="padding-top: 10px;">
																<label class="col-md-3 control-label" for="corrected_edd">Corrected EDD</label>
																<div class="col-md-3">
																	<input id="corrected_edd" name="corrected_edd" type="date" class="form-control input-sm">
																</div>

																<label class="col-md-2 control-label" for="deliverydate">Delivery date</label>
																<div class="col-md-3">
																	<input id="deliverydate" name="deliverydate" type="date" class="form-control input-sm">
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>

											<!-- <div class="form-group col-md-4" style="margin-left: 45px;padding-right: 0px;">
												<label class="col-md-1 control-label" for="gravida">Gr.</label>
												<div class="col-md-3">
													<input id="gravida" name="gravida" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69">
												</div>

												<label class="col-md-1 control-label" for="para">P</label>
												<div class="col-md-3">
													<input id="para" name="para" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69">
												</div>

												<label class="col-md-1 control-label" for="abortus">+</label>
												<div class="col-md-3">
													<input id="abortus" name="abortus" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69">
												</div>
											</div>

											<div class="form-group col-md-8">
												<div class='col-md-12'>
													<label class="col-md-3 control-label" for="lmp">LMP</label>
													<div class="col-md-3">
														<input id="lmp" name="lmp" type="date" class="form-control input-sm">
													</div>

													<label class="col-md-2 control-label" for="edd">EDD</label>
													<div class="col-md-3">
														<input id="edd" name="edd" type="date" class="form-control input-sm">
													</div>
												</div>

												<div class='col-md-12' style="padding-top: 10px;">
													<label class="col-md-3 control-label" for="corrected_edd">Corrected EDD</label>
													<div class="col-md-3">
														<input id="corrected_edd" name="corrected_edd" type="date" class="form-control input-sm">
													</div>

													<label class="col-md-2 control-label" for="deliverydate">Delivery date</label>
													<div class="col-md-3">
														<input id="deliverydate" name="deliverydate" type="date" class="form-control input-sm">
													</div>
												</div>
											</div> -->

											<!-- <div class="form-group col-md-4">
												<label class="col-md-6 control-label" for="lmp">LMP</label>
												<div class="col-md-6">
													<input id="lmp" name="lmp" type="date" class="form-control input-sm">
												</div>

												<label class="col-md-6 control-label" for="edd" style="padding-top: 17px;">EDD</label>
												<div class="col-md-6" style="padding-top: 10px;">
													<input id="edd" name="edd" type="date" class="form-control input-sm">
												</div>
											</div>

											<div class="form-group col-md-4">
												<label class="col-md-6 control-label" for="corrected_edd">Corrected EDD</label>
												<div class="col-md-6">
													<input id="corrected_edd" name="corrected_edd" type="date" class="form-control input-sm">
												</div>

												<label class="col-md-6 control-label" for="deliverydate" style="padding-top: 17px;">Delivery date</label>
												<div class="col-md-6" style="padding-top: 10px;">
													<input id="deliverydate" name="deliverydate" type="date" class="form-control input-sm">
												</div>
											</div> -->
											
										</form>

									</div>
								</div>
							</div>

							<div class='col-md-12'>
								<div class="panel panel-info">
									<div class="panel-heading text-center">CURRENT PREGNANCY</div>
									<div class="panel-body">
										<div class='col-md-12' style="padding:0 0 15px 0">
											<table id="jqGridCurrPregnancy" class="table table-striped"></table>
											<div id="jqGridPagerCurrPregnancy"></div>
										</div>
									</div>
								</div>
							</div>

							<div class='col-md-12'>
								<div class="panel panel-info">
									<div class="panel-heading text-center">OBSTETRICS ULTRASOUND SCAN</div>
									<div class="panel-body">
										<div class='col-md-12' style="padding:0 0 15px 0">
											<table id="jqGridObstetricsUltrasound" class="table table-striped"></table>
											<div id="jqGridPagerObstetricsUltrasound"></div>
										</div>
									</div>
								</div>
							</div>

							<div class='col-md-12'>
								<div class="panel panel-info">
									<div class="panel-heading text-center">DETAILED SCAN

										<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
											id="btn_grp_edit_ultrasound"
											style="position: absolute;
													padding: 0 0 0 0;
													right: 40px;
													top: 5px;">
											<button type="button" class="btn btn-default" id="new_ultrasound">
												<span class="fa fa-plus-square-o"></span> New
											</button>
											<button type="button" class="btn btn-default" id="edit_ultrasound">
												<span class="fa fa-edit fa-lg"></span> Edit
											</button>
											<button type="button" class="btn btn-default" data-oper='add' id="save_ultrasound">
												<span class="fa fa-save fa-lg"></span> Save
											</button>
											<button type="button" class="btn btn-default" id="cancel_ultrasound">
												<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
											</button>
										</div>

									</div>
									<div class="panel-body">

										<form class='form-horizontal' style='width:99%' id='formUltrasound'>
											<input id="mrn_ultrasound" name="mrn_ultrasound" type="hidden">
											<input id="episno_ultrasound" name="episno_ultrasound" type="hidden">
											<input name="date" type="hidden">

											<div class='col-md-12'>
												<div class='col-md-6'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">HEAD & NECK</div>
														<div class="panel-body">

															<div class='col-md-12'>
																<div class="panel panel-info">
																	<div class="panel-heading text-center">INTRACRANIAL STRUCTURES</div>
																	<div class="panel-body">

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="is_cerebrum">Cerebrum</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="is_cerebrum_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																				</div>
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="cerebrum_hydran" value="1" style="margin-right:18px;">Hydrancephaly
																					</label>
																				</div>
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input is_cerebrum" type="checkbox" name="cerebrum_holo" value="1" style="margin-right:18px;">Holoprosencephaly
																					</label>
																					<div class="form-check col-md-12" style="padding-left:30px;">
																						<input id="cerebrum_text" name="cerebrum_text" type="text" class="form-control input-sm" style="text-transform: none">
																					</div>
																				</div>
																				<!-- <div class="form-check">
																					<div class="col-md-1" style="padding-left:0px;">
																						<input class="form-check-input cerebrum" type="radio" value="4" name="cerebrum_textCheck">
																					</div>
																					<div class="col-md-11" style="padding-left:0px;">
																						<input id="cerebrum_text" name="cerebrum_text" type="text" class="form-control input-sm">
																					</div>
																				</div> -->
																			</div>
																		</div>

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="pellucidum">Cavum Septum Pellucidum</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input pellucidum" type="checkbox" name="pellucidum_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																					<div class="form-check col-md-12" style="padding-left:30px;">
																						<input id="pellucidum_text" name="pellucidum_text" type="text" class="form-control input-sm" style="text-transform: none">
																					</div>
																				</div>
																				<!-- <div class="form-check">
																					<div class="col-md-1" style="padding-left:0px;">
																						<input class="form-check-input pellucidum" type="radio" value="2" name="pellucidum_textCheck">
																					</div>
																					<div class="col-md-11" style="padding-left:0px;">
																						<input id="pellucidum_text" name="pellucidum_text" type="text" class="form-control input-sm">
																					</div>
																				</div> -->
																			</div>
																		</div>

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="falx">Falx Cerebellum</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="falx_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																				</div>
																				<div class="form-check">
																					<div class="col-md-1" style="padding-left:0px;">
																						<input class="form-check-input falx" type="checkbox" name="falx_textCheck" value="1">
																					</div>
																					<div class="col-md-11" style="padding-left:0px;">
																						<input id="falx_text" name="falx_text" type="text" class="form-control input-sm" style="text-transform: none">
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="cerebellum">Cerebellum</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="cerebellum_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																				</div>
																				<div class="form-check">
																					<div class="col-md-1" style="padding-left:0px;">
																						<input class="form-check-input cerebellum" type="checkbox" name="cerebellum_textCheck" value="1">
																					</div>
																					<div class="col-md-11" style="padding-left:0px;">
																						<input id="cerebellum_text" name="cerebellum_text" type="text" class="form-control input-sm" style="text-transform: none">
																					</div>
																				</div>
																			</div>
																		</div>	

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="ventricles">Ventricles</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="ventricles_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																				</div>
																				<div class="form-check">
																					<div class="col-md-5" style="padding-left:0px;">
																						<label class="form-check-label">
																							<input class="form-check-input" type="checkbox" name="ventricles_hydro" value="1" style="margin-right:18px;">Hydrocephlus
																						</label>
																					</div>
																					<div class="col-md-7" style="padding-left:0px;">
																						<div class="form-check">
																							<label class="form-check-label">
																								<input class="form-check-input" type="checkbox" name="ventricles_mild" value="1" style="margin-right:18px;">Mild
																							</label>
																						</div>
																						<div class="form-check">
																							<label class="form-check-label">
																								<input class="form-check-input" type="checkbox" name="ventricles_moderate" value="1" style="margin-right:18px;">Moderate
																							</label>
																						</div>
																						<div class="form-check">
																							<label class="form-check-label">
																								<input class="form-check-input" type="checkbox" name="ventricles_severe" value="1" style="margin-right:18px;">Severe
																							</label>
																						</div>
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
																			<label class="col-md-4 control-label" for="cerebrum">Cerebrum</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="cerebrum_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																				</div>
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="cerebrum_anencephly" value="1" style="margin-right:18px;">Anencephly
																					</label>
																				</div>
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="cerebrum_mening" value="1" style="margin-right:18px;">Meningomyelocoele
																					</label>
																				</div>
																			</div>
																		</div>

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="upperlip">Upper Lip</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="upperlip_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																				</div>
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="upperlip_cleftlip" value="1" style="margin-right:18px;">Cleft Lip
																					</label>
																				</div>
																			</div>
																		</div>

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="lowerlip">Lower Lip</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input lowerlip" type="checkbox" name="lowerlip_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																					<div class="form-check col-md-12" style="padding-left:30px;">
																						<input id="lowerlip_text" name="lowerlip_text" type="text" class="form-control input-sm" style="text-transform: none">
																					</div>
																				</div>
																				<!-- <div class="form-check">
																					<div class="col-md-1" style="padding-left:0px;">
																						<input class="form-check-input lowerlip" type="radio" value="2" name="lowerlip_textCheck">
																					</div>
																					<div class="col-md-11" style="padding-left:0px;">
																						<input id="lowerlip_text" name="lowerlip_text" type="text" class="form-control input-sm">
																					</div>
																				</div> -->
																			</div>
																		</div>

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="palate">Palate</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="palate_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																				</div>
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="palate_cleft" value="1" style="margin-right:18px;">Cleft Palate
																					</label>
																				</div>
																			</div>
																		</div>

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="nose">Nose</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="nose_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																				</div>
																				<div class="form-check">
																					<div class="col-md-1" style="padding-left:0px;">
																						<input class="form-check-input nose" type="checkbox" name="nose_textCheck" value="1">
																					</div>
																					<div class="col-md-11" style="padding-left:0px;">
																						<input id="nose_text" name="nose_text" type="text" class="form-control input-sm" style="text-transform: none">
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="eyes">Eyes</label>  
																			<div class="col-md-8">

																				<div class="form-group">
																					<label class="col-md-2 control-label" for="righteyes">Right</label>  
																					<div class="col-md-8" style="padding-top:6px;">
																						<div class="form-check">
																							<label class="form-check-label">
																								<input class="form-check-input righteyes" type="checkbox" name="righteyes_normal" value="1" style="margin-right:18px;">Normal
																							</label>
																							<div class="form-check col-md-12" style="padding-left:30px;">
																								<input id="righteyes_text" name="righteyes_text" type="text" class="form-control input-sm" style="text-transform: none">
																							</div>
																						</div>
																						<!-- <div class="form-check">
																							<div class="col-md-1" style="padding-left:0px;">
																								<input class="form-check-input righteyes" type="radio" value="2" name="righteyes_textCheck">
																							</div>
																							<div class="col-md-11" style="padding-left:0px;">
																								<input id="righteyes_text" name="righteyes_text" type="text" class="form-control input-sm">
																							</div>
																						</div> -->
																					</div>
																				</div>

																				<div class="form-group">
																					<label class="col-md-2 control-label" for="lefteyes">Left</label>  
																					<div class="col-md-8" style="padding-top:6px;">
																						<div class="form-check">
																							<label class="form-check-label">
																								<input class="form-check-input lefteyes" type="checkbox" name="lefteyes_normal" value="1" style="margin-right:18px;">Normal
																							</label>
																							<div class="form-check col-md-12" style="padding-left:30px;">
																								<input id="lefteyes_text" name="lefteyes_text" type="text" class="form-control input-sm" style="text-transform: none">
																							</div>
																						</div>
																						<!-- <div class="form-check">
																							<div class="col-md-1" style="padding-left:0px;">
																								<input class="form-check-input lefteyes" type="radio" value="2" name="lefteyes_textCheck">
																							</div>
																							<div class="col-md-11" style="padding-left:0px;">
																								<input id="lefteyes_text" name="lefteyes_text" type="text" class="form-control input-sm">
																							</div>
																						</div> -->
																					</div>
																				</div>
																				
																			</div>
																		</div>

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="mandible">Mandible</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="mandible_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																				</div>
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="mandible_macro" value="1" style="margin-right:18px;">Macrognathia
																					</label>
																				</div>
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="mandible_micro" value="1" style="margin-right:18px;">Micrognathia
																					</label>
																				</div>
																			</div>
																		</div>

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="neck">Neck</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="neck_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																				</div>
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="neck_cystic" value="1" style="margin-right:18px;">Cystic Hygroma
																					</label>
																				</div>
																			</div>
																		</div>

																	</div>
																</div>
															</div>											

														</div>
													</div>
												</div>

												<div class='col-md-6'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">CHEST</div>
														<div class="panel-body" style="height: 1250px;">

															<div class='col-md-12'>
																<div class="panel panel-info">
																	<div class="panel-body">

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="chestwall">Anterior Chest Wall</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input chestwall" type="checkbox" name="chestwall_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																					<div class="form-check col-md-12" style="padding-left:30px;">	
																						<input id="chestwall_text" name="chestwall_text" type="text" class="form-control input-sm" style="text-transform: none">
																					</div>																	
																				</div>
																				<!-- <div class="form-check">
																					<div class="col-md-1" style="padding-left:0px;">
																						<input class="form-check-input chestwall" type="radio" value="2" name="chestwall_textCheck">
																					</div>
																					<div class="col-md-11" style="padding-left:0px;">
																						<input id="chestwall_text" name="chestwall_text" type="text" class="form-control input-sm">
																					</div>
																				</div> -->
																			</div>
																		</div>

																	</div>
																</div>
															</div>

															<div class='col-md-12'>
																<div class="panel panel-info">
																	<div class="panel-heading text-center">HEART</div>
																	<div class="panel-body">

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="heartsize">Size</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="heartsize_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																				</div>
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="heartsize_small" value="1" style="margin-right:18px;">Small
																					</label>
																				</div>
																			</div>
																		</div>

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="fourchamber">Four chamber view</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input fourchamber" type="checkbox" name="fourchamber_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																					<div class="form-check col-md-12" style="padding-left:30px;">
																						<input id="fourchamber_text" name="fourchamber_text" type="text" class="form-control input-sm" style="text-transform: none">
																					</div>	
																				</div>
																				<!-- <div class="form-check">
																					<div class="col-md-1" style="padding-left:0px;">
																						<input class="form-check-input fourchamber" type="radio" value="2" name="fourchamber_textCheck">
																					</div>
																					<div class="col-md-11" style="padding-left:0px;">
																						<input id="fourchamber_text" name="fourchamber_text" type="text" class="form-control input-sm">
																					</div>
																				</div> -->
																			</div>
																		</div>

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="aorticarc">Aortic arch</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="aorticarc_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																				</div>
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="aorticarc_coarctation" value="1" style="margin-right:18px;">Coarctation
																					</label>
																				</div>
																			</div>
																		</div>

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="aortictrunk">Aortic trunk</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="aortictrunk_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																				</div>
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="aortictrunk_stenosis" value="1" style="margin-right:18px;">Stenosis
																					</label>
																				</div>
																			</div>
																		</div>

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="pulmonary">Pulmonary</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="pulmonary_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																				</div>
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="pulmonary_stenosis" value="1" style="margin-right:18px;">Stenosis
																					</label>
																				</div>
																			</div>
																		</div>	

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="septum">Interventricular septum</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="septum_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																				</div>
																				<div class="form-check">
																					<div class="col-md-4" style="padding-left:0px;">
																						<label class="form-check-label">
																							<input class="form-check-input" type="checkbox" name="septum_vsd" value="1" style="margin-right:18px;">VSD
																						</label>
																					</div>
																					<div class="col-md-8" style="padding-left:0px;">
																						<div class="form-check">
																							<label class="form-check-label">
																								<input class="form-check-input" type="checkbox" name="septum_membraneous" value="1" style="margin-right:18px;">Membraneous
																							</label>
																						</div>
																						<div class="form-check">
																							<label class="form-check-label">
																								<input class="form-check-input" type="checkbox" name="septum_muscular" value="1" style="margin-right:18px;">Muscular
																							</label>
																						</div>
																					</div>
																				</div>
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="septum_combined" value="1" style="margin-right:18px;">Combined
																					</label>
																				</div>
																				<div class="form-check">
																					<label class="col-md-4 control-label" for="vsdsize" style="padding-left:0px;">Size of VSD</label>  
																					<div class="col-md-6 input-group">
																						<input id="vsdsize" name="vsdsize" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69">
																						<span class="input-group-addon">mm</span>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="pericardium">Pericardium</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="pericardium_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																				</div>
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="pericardium_peri" value="1" style="margin-right:18px;">Pericardial Effusion
																					</label>
																				</div>
																			</div>
																		</div>

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="otherDefects">Other defects (spy)</label>
																			<div class="col-md-8">
																				<input id="otherDefects" name="otherDefects" type="text" class="form-control input-sm" style="text-transform: none">
																			</div>
																		</div>	

																	</div>
																</div>
															</div>

															<div class='col-md-12'>
																<div class="panel panel-info">
																	<div class="panel-body">

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="diaphragm">Diaphragm</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="diaphragm_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																				</div>
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="diaphragm_hernia" value="1" style="margin-right:18px;">Hernia
																					</label>
																				</div>
																			</div>
																		</div>

																		<div class="form-group">
																			<label class="col-md-4 control-label" for="lungs">Lungs</label>  
																			<div class="col-md-8" style="padding-top:6px;">
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="lungs_normal" value="1" style="margin-right:18px;">Normal
																					</label>
																				</div>
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="lungs_hypo" value="1" style="margin-right:18px;">Hypoplasia
																					</label>
																				</div>
																				<div class="form-check">
																					<label class="form-check-label">
																						<input class="form-check-input" type="checkbox" name="lungs_pleural" value="1" style="margin-right:18px;">Pleural Effusion
																					</label>
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
												<div class='col-md-6'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">ABDOMEN</div>
														<div class="panel-body">

															<div class="form-group">
																<label class="col-md-4 control-label" for="abdomen_chestwall">Anterior Chest Wall</label>  
																<div class="col-md-8" style="padding-top:6px;">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="checkbox" name="chestwall_intact" value="1" style="margin-right:18px;">Intact
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="checkbox" name="chestwall_ompha" value="1" style="margin-right:18px;">Omphalocoele
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="checkbox" name="chestwall_gastro" value="1" style="margin-right:18px;">Gastroschisis
																		</label>
																	</div>
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="cord">Cord</label>  
																<div class="col-md-8" style="padding-top:6px;">
																	<div class="form-check col-md-6">  
																		<div class="input-group">
																			<input id="cordA" name="cordA" type="text" class="form-control input-sm" style="text-transform: none">
																			<span class="input-group-addon">A</span>
																		</div> 
																	</div>
																	<div class="form-check col-md-6"> 
																		<div class="input-group">
																			<input id="cordV" name="cordV" type="text" class="form-control input-sm" style="text-transform: none">
																			<span class="input-group-addon">V</span>
																		</div>
																	</div>
																	<div class="form-check">
																		<label class="col-md-4 control-label" for="cordinsert">Cord insertion</label>  
																		<div class="col-md-8" style="padding-top:10px;">
																			<!-- <div class="form-check">
																				<label class="form-check-label">
																					<input class="form-check-input" type="radio" value="1" name="cordinsert" style="margin-right:20px;">Intact
																				</label>
																			</div> -->
																			<div class="form-check">
																				<div class="col-md-1" style="padding-left:0px;">
																					<input class="form-check-input cordinsert" type="checkbox" name="cordinsert_intact" value="1">
																				</div>
																				<div class="col-md-2" style="padding-left:5px;"> Intact
																				</div>
																				<div class="col-md-9" style="padding-left:10px;">
																					<input id="cordinsert_text" name="cordinsert_text" type="text" class="form-control input-sm" style="text-transform: none">
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="stomach">Stomach</label>  
																<div class="col-md-8" style="padding-top:6px;">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="checkbox" name="stomach_normal" value="1" style="margin-right:18px;">Normal single bubble
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="checkbox" name="stomach_double" value="1" style="margin-right:18px;">Double bubble
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="checkbox" name="stomach_absent" value="1" style="margin-right:18px;">Absent
																		</label>
																	</div>
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="liver">Liver</label>  
																<div class="col-md-8" style="padding-top:6px;">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="checkbox" name="liver_normal" value="1" style="margin-right:18px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="checkbox" name="liver_hepa" value="1" style="margin-right:18px;">Hepatomegaly
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="checkbox" name="liver_hypo" value="1" style="margin-right:18px;">Hypoplastic
																		</label>
																	</div>
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="kidney">Kidney</label>  
																<div class="col-md-8">

																	<div class="form-group">
																		<label class="col-md-2 control-label" for="rightkidney">Right</label>  
																		<div class="col-md-8" style="padding-top:6px;">
																			<div class="form-check">
																				<label class="form-check-label">
																					<input class="form-check-input" type="checkbox" name="rightkidney_normal" value="1" style="margin-right:18px;">Normal
																				</label>
																			</div>
																			<div class="form-check">
																				<label class="form-check-label">
																					<input class="form-check-input" type="checkbox" name="rightkidney_absent" value="1" style="margin-right:18px;">Absent
																				</label>
																			</div>
																			<div class="form-check">
																				<label class="form-check-label">
																					<input class="form-check-input rightkidney" type="checkbox" name="rightkidney_hydro" value="1" style="margin-right:18px;">Hydronephrosis
																				</label>
																				<div class="form-check col-md-12" style="padding-left:30px;">
																					<input id="rightkidney_text" name="rightkidney_text" type="text" class="form-control input-sm" style="text-transform: none">
																				</div>
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="col-md-2 control-label" for="leftkidney">Left</label>  
																		<div class="col-md-8" style="padding-top:6px;">
																			<div class="form-check">
																				<label class="form-check-label">
																					<input class="form-check-input" type="checkbox" name="leftkidney_normal" value="1" style="margin-right:18px;">Normal
																				</label>
																			</div>
																			<div class="form-check">
																				<label class="form-check-label">
																					<input class="form-check-input" type="checkbox" name="leftkidney_absent" value="1" style="margin-right:18px;">Absent
																				</label>
																			</div>
																			<div class="form-check">
																				<label class="form-check-label">
																					<input class="form-check-input leftkidney" type="checkbox" name="leftkidney_hydro" value="1" style="margin-right:18px;">Hydronephrosis
																				</label>
																				<div class="form-check col-md-12" style="padding-left:30px;">
																					<input id="leftkidney_text" name="leftkidney_text" type="text" class="form-control input-sm" style="text-transform: none">
																				</div>
																			</div>
																		</div>
																	</div>
																	
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="bladder">Bladder</label>  
																<div class="col-md-8" style="padding-top:6px;">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="checkbox" name="bladder_normal" value="1" style="margin-right:18px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input bladder" type="checkbox" name="bladder_absent" value="1" style="margin-right:18px;">Absent
																		</label>
																		<div class="form-check col-md-12" style="padding-left:30px;">
																			<input id="bladder_text" name="bladder_text" type="text" class="form-control input-sm" style="text-transform: none">
																		</div>
																	</div>
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="ascites">Ascites</label>  
																<div class="col-md-8" style="padding-top:6px;">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="checkbox" name="ascites_distended" value="1" style="margin-right:18px;">Distended
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="checkbox" name="ascites_absent" value="1" style="margin-right:18px;">Absent
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="checkbox" name="ascites_present" value="1" style="margin-right:18px;">Present
																		</label>
																	</div>
																</div>
															</div>

														</div>
													</div>
												</div>

												<div class='col-md-6'>
													<div class="panel panel-info">
														<div class="panel-body" style="height: 845px;">

															<div class="form-group">
																<label class="col-md-4 control-label" for="upperlimbs">Upper limbs</label>  
																<div class="col-md-8">

																	<div class="form-group">
																		<label class="col-md-2 control-label" for="upperlimbs_R">Right</label>  
																		<div class="col-md-8">
																			<div class="col-md-4">
																				<input id="upperlimbs_1R" name="upperlimbs_1R" type="text" class="form-control input-sm">
																			</div>
																			<div class="col-md-4">
																				<input id="upperlimbs_2R" name="upperlimbs_2R" type="text" class="form-control input-sm">
																			</div>
																			<div class="col-md-4">
																				<input id="upperlimbs_3R" name="upperlimbs_3R" type="text" class="form-control input-sm">
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="col-md-2 control-label" for="upperlimbs_L">Left</label>  
																		<div class="col-md-8">
																			<div class="col-md-4">
																				<input id="upperlimbs_1L" name="upperlimbs_1L" type="text" class="form-control input-sm">
																			</div>
																			<div class="col-md-4">
																				<input id="upperlimbs_2L" name="upperlimbs_2L" type="text" class="form-control input-sm">
																			</div>
																			<div class="col-md-4">
																				<input id="upperlimbs_3L" name="upperlimbs_3L" type="text" class="form-control input-sm">
																			</div>
																		</div>
																	</div>
																	
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="lowerlimbs">Lower limbs</label>  
																<div class="col-md-8">

																	<div class="form-group">
																		<label class="col-md-2 control-label" for="lowerlimbs_R">Right</label>  
																		<div class="col-md-8">
																			<div class="col-md-4">
																				<input id="lowerlimbs_1R" name="lowerlimbs_1R" type="text" class="form-control input-sm">
																			</div>
																			<div class="col-md-4">
																				<input id="lowerlimbs_2R" name="lowerlimbs_2R" type="text" class="form-control input-sm">
																			</div>
																			<div class="col-md-4">
																				<input id="lowerlimbs_3R" name="lowerlimbs_3R" type="text" class="form-control input-sm">
																			</div>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="col-md-2 control-label" for="lowerlimbs_L">Left</label>  
																		<div class="col-md-8">
																			<div class="col-md-4">
																				<input id="lowerlimbs_1L" name="lowerlimbs_1L" type="text" class="form-control input-sm">
																			</div>
																			<div class="col-md-4">
																				<input id="lowerlimbs_2L" name="lowerlimbs_2L" type="text" class="form-control input-sm">
																			</div>
																			<div class="col-md-4">
																				<input id="lowerlimbs_3L" name="lowerlimbs_3L" type="text" class="form-control input-sm">
																			</div>
																		</div>
																	</div>
																	
																</div>
															</div>

															<div class='col-md-1' style="margin-top:10px"></div>

															<div class='col-md-10' style="margin-top:10px">
																<div class="panel panel-info">
																	<div class="panel-body">

																		<table class="table table-borderless">
																			<tbody>
																				<tr>
																					<td>1. Normal</td>
																				</tr>
																				<tr>
																					<td>2. Absence of limb (Amelia)</td>
																				</tr>
																				<tr>
																					<td>3. Partial loss limb or limb segment (Meromelia)</td>
																				</tr>
																				<tr>
																					<td>4. Absence of individual bones with hand malformations</td>
																				</tr>
																				<tr>
																					<td>5. Forshortening of bones</td>
																				</tr>
																				<tr>
																					<td>6. Defective mineralization bones</td>
																				</tr>
																				<tr>
																					<td>7. Spontaneous fractures</td>
																				</tr>
																			</tbody>
																		</table>

																	</div>
																</div>
															</div>

															<div class='col-md-1' style="margin-top:10px"></div>

														</div>
													</div>
												</div>
											</div>
										</form>

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
	
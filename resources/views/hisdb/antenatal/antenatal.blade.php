
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
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 310px; top: 25px;">
			<h5>Antenatal</h5>
		</div>	

		<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
			id="btn_grp_edit_antenatal"
			style="position: absolute;
					padding: 0 0 0 0;
					right: 40px;
					top: 25px;" 

		>
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
	<div id="jqGridAntenatal_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<!-- <table id="jqGridTriageInfo" class="table table-striped"></table>
				<div id="jqGridPagerTriageInfo"></div> -->

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
											<label for="anr_bloodgroup">Blood Group</label>
											<div class='input-group'>
												<input id="anr_bloodgroup" name="anr_bloodgroup" type="text" class="form-control input-sm">
												<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
											</div>
											<span class="help-block"></span>
										</div>
										<div class="form-group col-md-6" style="margin-left: 2px">
											<label for="height">Height</label>  
											<div class="input-group">
												<input id="height" name="height" type="number" class="form-control input-sm floatNumberField" pattern="^\d*(\.\d{0,2})?$" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
												<span class="input-group-addon">cm</span>
											</div>
										</div>
									</div>

									<div class="form-row col-md-12">
										<div class="form-group col-md-6" style="margin-left: 2px">
											<label for="anr_rhesus">Rhesus Factor</label>
											<!-- <select class="form-control col-md-4" id='anr_rhesus' name='anr_rhesus'>
												<option value="" selected>Rhesus Factor</option>
												<option value="rh_positive">RH Positive</option>
												<option value="rh_negative">RH Negative</option>
											</select> -->
											<input class="form-control" list="anr_rhesus1" name="anr_rhesus" id="anr_rhesus">
											<datalist id="anr_rhesus1">
												<option value="RH Positive">
												<option value="RH Negative">
											</datalist>
										</div>
										<div class="form-group col-md-6" style="margin-left: 2px">
											<label for="anr_rubella">Rubella Status</label>  
											<div class="input-group">
												<input id="anr_rubella" name="anr_rubella" type="number" class="form-control input-sm floatNumberField" pattern="^\d*(\.\d{0,2})?$" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
												<span class="input-group-addon">IU/mL</span>
											</div>
										</div>
									</div>

									<div class="form-row col-md-12">
										<div class="form-group col-md-6" style="margin-left: 2px">
											<label for="anr_vdrl">VDRL</label>  
											<div class="input-group">
												<input id="anr_vdrl" name="anr_vdrl" type="number" class="form-control input-sm floatNumberField" pattern="^\d*(\.\d{0,2})?$" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
												<span class="input-group-addon">%</span>
											</div>
										</div>
										<div class="form-group col-md-6" style="margin-left: 2px">
											<label for="anr_hiv">HIV</label>  
											<div class="input-group">
												<input id="anr_hiv" name="anr_hiv" type="number" class="form-control input-sm floatNumberField" pattern="^\d*(\.\d{0,2})?$" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
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
														<label for="anr_hepaB_Ag">Ag</label>
														<!-- <select class="form-control col-md-4" id='anr_hepaB_Ag' name='anr_hepaB_Ag'>
															<option value="" selected>HBsAg</option>
															<option value="HBsAg_positive">Positive</option>
															<option value="HBsAg_reactive">Reactive</option>
														</select> -->
														<input class="form-control" list="anr_hepaB_Ag1" name="anr_hepaB_Ag" id="anr_hepaB_Ag">
														<datalist id="anr_hepaB_Ag1">
															<option value="Positive">
															<option value="Reactive">
														</datalist>
													</div>
													<div class="form-group col-md-6" style="margin-left: 2px">
														<label for="anr_hepaB_AB">AB</label>
														<!-- <select class="form-control col-md-4" id='anr_hepaB_AB' name='anr_hepaB_AB'>
															<option value="" selected>HBsAb</option>
															<option value="HBsAb_positive">Positive</option>
															<option value="HBsAb_reactive">Reactive</option>
														</select> -->
														<input class="form-control" list="anr_hepaB_AB1" name="anr_hepaB_AB" id="anr_hepaB_AB">
														<datalist id="anr_hepaB_AB1">
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
														<label for="anr_attInject_1st">1st dose</label>
														<input id="anr_attInject_1st" name="anr_attInject_1st" type="date" class="form-control input-sm">
													</div>
													<div class="form-group col-md-6" style="margin-left: 2px">
														<label for="anr_attInject_2nd">2nd dose</label>
														<input id="anr_attInject_2nd" name="anr_attInject_2nd" type="date" class="form-control input-sm">
													</div>
												</div>

												<div class="form-row">
													<div class="form-group col-md-6" style="margin-left: 2px">
														<label for="anr_attInject_boost">Booster</label>
														<input id="anr_attInject_boost" name="anr_attInject_boost" type="date" class="form-control input-sm">
													</div>
												</div>

											</div>
										</div>
									</div>

									<!-- <hr style="width: 100%;background-color: #96cdcd;height: 1px"> -->

									<div class="form-group col-md-10" style="margin-top: 10px;margin-left: 35px">
										<label for="anr_bloodTrans">Blood Transfusion</label>
										<input class="form-control" list="anr_bloodTrans1" name="anr_bloodTrans" id="anr_bloodTrans">
										<datalist id="anr_bloodTrans1">
											<option value="Red blood cell transfusions">
											<option value="Platelet transfusions">
											<option value="Plasma transfusions">
											<option value="Whole blood transfusion">
										</datalist>
									</div>

									<!-- <hr style="width: 100%;background-color: #96cdcd;height: 1px"> -->

									<div class="form-group col-md-10" style="margin-top: 10px;margin-left: 35px">
										<label for="anr_drugAllergies">Drug Allergies</label>
										<textarea id="anr_drugAllergies" name="anr_drugAllergies" type="text" class="form-control input-sm" rows="6"></textarea>
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
											<label class="control-label" for="date_systematicExam" style="padding-bottom:5px">Date</label>
											<input id="date_systematicExam" name="date_systematicExam" type="date" class="form-control input-sm">
										</div>
									</div>

									<div class="form-group">
										<div class="col-md-4">
											<label class="control-label" for="varicoseVeins" style="padding-bottom:5px">Varicose Veins</label>
											<input id="varicoseVeins" name="varicoseVeins" type="text" class="form-control input-sm">
										</div>

										<div class="col-md-4">
											<label class="control-label" for="pailor" style="padding-bottom:5px">Pailor</label>
											<input id="pailor" name="pailor" type="text" class="form-control input-sm">
										</div>

										<div class="col-md-4">
											<label class="control-label" for="jaundice" style="padding-bottom:5px">Jaundice</label>
											<input id="jaundice" name="jaundice" type="text" class="form-control input-sm">
										</div>
									</div>

									<div class="form-group">
										<div class="col-md-4">
											<label class="control-label" for="oralCavity" style="padding-bottom:5px">Oral Cavity</label>
											<input id="oralCavity" name="oralCavity" type="text" class="form-control input-sm">
										</div>

										<div class="col-md-4">
											<label class="control-label" for="thyroid" style="padding-bottom:5px">Thyroid</label>
											<input id="thyroid" name="thyroid" type="text" class="form-control input-sm">
										</div>

										<div class="col-md-4">
											<div class="col-md-12" style="padding-left:0px;">
												<label class="control-label" for="breast" style="padding-bottom:5px;">Breast</label>
											</div>
											<label class="col-md-2 control-label" for="breast_r" style="padding-left:0px;">(R)</label>
											<div class="col-md-10">
												<input id="breast_r" name="breast_r" type="text" class="form-control input-sm">
											</div>
											<label class="col-md-2 control-label" for="breast_l" style="padding-top:10px;padding-left:0px;">(L)</label>
											<div class="col-md-10" style="padding-top:5px;">
												<input id="breast_l" name="breast_l" type="text" class="form-control input-sm">
											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="col-md-4">
											<label class="control-label" for="cvs" style="padding-bottom:5px">CVS</label>
											<input id="cvs" name="cvs" type="text" class="form-control input-sm">
										</div>

										<div class="col-md-4">
											<label class="control-label" for="respSystem" style="padding-bottom:5px">Resp System</label>
											<input id="respSystem" name="respSystem" type="text" class="form-control input-sm">
										</div>

										<div class="col-md-4">
											<label class="control-label" for="abdomen" style="padding-bottom:5px">Abdomen</label>
											<input id="abdomen" name="abdomen" type="text" class="form-control input-sm">
										</div>
									</div>

									<div class="form-group">
										<div class="col-md-12">
											<label class="control-label" for="specialRemarks" style="padding-bottom:5px">Special Remarks</label>
											<textarea id="specialRemarks" name="specialRemarks" type="text" class="form-control input-sm" rows="4"></textarea>
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
										<label class="checkbox-inline" for="pgh_endometriosis" style="margin-left: 30px">
											<input type="checkbox" name="pgh_endometriosis" id="pgh_endometriosis" value="1">Endometriosis
										</label>
									</div>

									<!-- <div class="form-row"> -->
										<div class="form-group col-md-5" style="margin-top: 10px;margin-left: 35px">
											<label for="lastpapsmear">Last Pap Smear</label>
											<input id="lastpapsmear" name="lastpapsmear" type="date" class="form-control input-sm">
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
										<label class="checkbox-inline" for="pmh_renaldisease" style="margin-left: 50px">
											<input type="checkbox" name="pmh_renaldisease" id="pmh_renaldisease" value="1">Renal Disease
										</label>
										<label class="checkbox-inline" for="pmh_hypertension" style="margin-left: 30px">
											<input type="checkbox" name="pmh_hypertension" id="pmh_hypertension" value="1">Hypertension
										</label>
										<label class="checkbox-inline" for="pmh_diabetes" style="margin-left: 30px">
											<input type="checkbox" name="pmh_diabetes" id="pmh_diabetes" value="1">Diabetes
										</label>
										<label class="checkbox-inline" for="pmh_heartdisease" style="margin-left: 30px">
											<input type="checkbox" name="pmh_heartdisease" id="pmh_heartdisease" value="1">Heart Disease
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
										<label class="checkbox-inline" for="psh_appendicectomy" style="margin-left: 50px">
											<input type="checkbox" name="psh_appendicectomy" id="psh_appendicectomy" value="1">Appendicectomy
										</label>
										<label class="checkbox-inline" for="psh_hypertension" style="margin-left: 30px">
											<input type="checkbox" name="psh_hypertension" id="psh_hypertension" value="1">Hypertension
										</label>
										<label class="checkbox-inline" for="psh_laparotomy" style="margin-left: 30px">
											<input type="checkbox" name="psh_laparotomy" id="psh_laparotomy" value="1">Laparotomy
										</label>
										<label class="checkbox-inline" for="psh_thyroidsurgery" style="margin-left: 30px">
											<input type="checkbox" name="psh_thyroidsurgery" id="psh_thyroidsurgery" value="1">Thyroid Surgery
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
										<label class="checkbox-inline" for="fh_multipregnancy" style="margin-left: 30px">
											<input type="checkbox" name="fh_multipregnancy" id="fh_multipregnancy" value="1">Multiple Pregnancy
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

					<div class='col-md-12'>
						<div class="panel panel-info">
							<div class="panel-heading text-center">OBSTETRICS ULTRASOUND SCAN</div>
							<div class="panel-body">
                                <div class='col-md-12' style="padding:0 0 15px 0">
                                    <table id="jqGridObstetricsUltraScan" class="table table-striped"></table>
                                    <div id="jqGridPagerObstetricsUltraScan"></div>
                                </div>
							</div>
						</div>
					</div>

					<div class='col-md-12'>
						<div class="panel panel-info">
							<div class="panel-heading text-center">DETAILED SCAN</div>
							<div class="panel-body">

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
																<label class="col-md-4 control-label" for="cerebrum">Cerebrum</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="1" name="cerebrum" style="margin-right:20px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="2" name="cerebrum" style="margin-right:20px;">Hydrancephaly
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input cerebrum" type="radio" value="3" name="cerebrum" style="margin-right:20px;">Holoprosencephaly
																		</label>
																		<div class="form-check col-md-12" style="padding-left:30px;">
																			<input id="cerebrum_text" name="cerebrum_text" type="text" class="form-control input-sm" style="text-transform: none">
																		</div>
																	</div>
																	<!-- <div class="form-check">
																		<div class="col-md-1" style="padding-left:0px;">
																			<input class="form-check-input cerebrum" type="radio" value="4" name="cerebrum">
																		</div>
																		<div class="col-md-11" style="padding-left:0px;">
																			<input id="cerebrum_text" name="cerebrum_text" type="text" class="form-control input-sm">
																		</div>
																	</div> -->
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="cavumSeptumPellucidum">Cavum Septum Pellucidum</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input cavumSeptumPellucidum" type="radio" value="1" name="cavumSeptumPellucidum" style="margin-right:20px;">Normal
																		</label>
																		<div class="form-check col-md-12" style="padding-left:30px;">
																			<input id="cavumSeptumPellucidum_text" name="cavumSeptumPellucidum_text" type="text" class="form-control input-sm" style="text-transform: none">
																		</div>
																	</div>
																	<!-- <div class="form-check">
																		<div class="col-md-1" style="padding-left:0px;">
																			<input class="form-check-input cavumSeptumPellucidum" type="radio" value="2" name="cavumSeptumPellucidum">
																		</div>
																		<div class="col-md-11" style="padding-left:0px;">
																			<input id="cavumSeptumPellucidum_text" name="cavumSeptumPellucidum_text" type="text" class="form-control input-sm">
																		</div>
																	</div> -->
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="falxCerebellum">Falx Cerebellum</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="1" name="falxCerebellum" style="margin-right:20px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<div class="col-md-1" style="padding-left:0px;">
																			<input class="form-check-input falxCerebellum" type="radio" value="2" name="falxCerebellum">
																		</div>
																		<div class="col-md-11" style="padding-left:0px;">
																			<input id="falxCerebellum_text" name="falxCerebellum_text" type="text" class="form-control input-sm" style="text-transform: none">
																		</div>
																	</div>
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="cerebellum">Cerebellum</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="1" name="cerebellum" style="margin-right:20px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<div class="col-md-1" style="padding-left:0px;">
																			<input class="form-check-input cerebellum" type="radio" value="2" name="cerebellum">
																		</div>
																		<div class="col-md-11" style="padding-left:0px;">
																			<input id="cerebellum_text" name="cerebellum_text" type="text" class="form-control input-sm" style="text-transform: none">
																		</div>
																	</div>
																</div>
															</div>	

															<div class="form-group">
																<label class="col-md-4 control-label" for="ventricles">Ventricles</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="1" name="ventricles" style="margin-right:20px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<div class="col-md-6" style="padding-left:0px;">
																			<label class="form-check-label">
																				<input class="form-check-input" type="radio" value="2" name="ventricles" style="margin-right:20px;">Hydrocephlus
																			</label>
																		</div>
																		<div class="col-md-6" style="padding-left:0px;">
																			<div class="form-check">
																				<label class="form-check-label">
																					<input class="form-check-input" type="radio" value="1" name="hydrocephlus" style="margin-right:20px;">Mild
																				</label>
																			</div>
																			<div class="form-check">
																				<label class="form-check-label">
																					<input class="form-check-input" type="radio" value="2" name="hydrocephlus" style="margin-right:20px;">Moderate
																				</label>
																			</div>
																			<div class="form-check">
																				<label class="form-check-label">
																					<input class="form-check-input" type="radio" value="3" name="hydrocephlus" style="margin-right:20px;">Severe
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
																<label class="col-md-4 control-label" for="cerebrum1">Cerebrum</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="1" name="cerebrum1" style="margin-right:20px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="2" name="cerebrum1" style="margin-right:20px;">Anencephly
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="3" name="cerebrum1" style="margin-right:20px;">Meningomyelocoele
																		</label>
																	</div>
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="upperLip">Upper Lip</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="1" name="upperLip" style="margin-right:20px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="2" name="upperLip" style="margin-right:20px;">Cleft Lip
																		</label>
																	</div>
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="lowerLip">Lower Lip</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input lowerLip" type="radio" value="1" name="lowerLip" style="margin-right:20px;">Normal
																		</label>
																		<div class="form-check col-md-12" style="padding-left:30px;">
																			<input id="lowerLip_text" name="lowerLip_text" type="text" class="form-control input-sm" style="text-transform: none">
																		</div>
																	</div>
																	<!-- <div class="form-check">
																		<div class="col-md-1" style="padding-left:0px;">
																			<input class="form-check-input lowerLip" type="radio" value="2" name="lowerLip">
																		</div>
																		<div class="col-md-11" style="padding-left:0px;">
																			<input id="lowerLip_text" name="lowerLip_text" type="text" class="form-control input-sm">
																		</div>
																	</div> -->
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="palate">Palate</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="1" name="palate" style="margin-right:20px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="2" name="palate" style="margin-right:20px;">Cleft Palate
																		</label>
																	</div>
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="nose">Nose</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="1" name="nose" style="margin-right:20px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<div class="col-md-1" style="padding-left:0px;">
																			<input class="form-check-input nose" type="radio" value="2" name="nose">
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
																		<label class="col-md-2 control-label" for="rightEyes">Right</label>  
																		<div class="col-md-8">
																			<div class="form-check">
																				<label class="form-check-label">
																					<input class="form-check-input rightEyes" type="radio" value="1" name="rightEyes" style="margin-right:20px;">Normal
																				</label>
																				<div class="form-check col-md-12" style="padding-left:30px;">
																					<input id="rightEyes_text" name="rightEyes_text" type="text" class="form-control input-sm" style="text-transform: none">
																				</div>
																			</div>
																			<!-- <div class="form-check">
																				<div class="col-md-1" style="padding-left:0px;">
																					<input class="form-check-input rightEyes" type="radio" value="2" name="rightEyes">
																				</div>
																				<div class="col-md-11" style="padding-left:0px;">
																					<input id="rightEyes_text" name="rightEyes_text" type="text" class="form-control input-sm">
																				</div>
																			</div> -->
																		</div>
																	</div>

																	<div class="form-group">
																		<label class="col-md-2 control-label" for="leftEyes">Left</label>  
																		<div class="col-md-8">
																			<div class="form-check">
																				<label class="form-check-label">
																					<input class="form-check-input leftEyes" type="radio" value="1" name="leftEyes" style="margin-right:20px;">Normal
																				</label>
																				<div class="form-check col-md-12" style="padding-left:30px;">
																					<input id="leftEyes_text" name="leftEyes_text" type="text" class="form-control input-sm" style="text-transform: none">
																				</div>
																			</div>
																			<!-- <div class="form-check">
																				<div class="col-md-1" style="padding-left:0px;">
																					<input class="form-check-input leftEyes" type="radio" value="2" name="leftEyes">
																				</div>
																				<div class="col-md-11" style="padding-left:0px;">
																					<input id="leftEyes_text" name="leftEyes_text" type="text" class="form-control input-sm">
																				</div>
																			</div> -->
																		</div>
																	</div>
																	
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="mandible">Mandible</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="1" name="mandible" style="margin-right:20px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="2" name="mandible" style="margin-right:20px;">Macrognathia
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="3" name="mandible" style="margin-right:20px;">Micrognathia
																		</label>
																	</div>
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="neck">Neck</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="1" name="neck" style="margin-right:20px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="2" name="neck" style="margin-right:20px;">Cystic Hygroma
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
											<div class="panel-body" style="height: 1166px;">

												<div class='col-md-12'>
													<div class="panel panel-info">
														<div class="panel-body">

															<div class="form-group">
																<label class="col-md-4 control-label" for="anteriorChestWall">Anterior Chest Wall</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input anteriorChestWall" type="radio" value="1" name="anteriorChestWall" style="margin-right:20px;">Normal
																		</label>
																		<div class="form-check col-md-12" style="padding-left:30px;">	
																			<input id="anteriorChestWall_text" name="anteriorChestWall_text" type="text" class="form-control input-sm" style="text-transform: none">
																		</div>																	
																	</div>
																	<!-- <div class="form-check">
																		<div class="col-md-1" style="padding-left:0px;">
																			<input class="form-check-input anteriorChestWall" type="radio" value="2" name="anteriorChestWall">
																		</div>
																		<div class="col-md-11" style="padding-left:0px;">
																			<input id="anteriorChestWall_text" name="anteriorChestWall_text" type="text" class="form-control input-sm">
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
																<label class="col-md-4 control-label" for="size">Size</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="1" name="size" style="margin-right:20px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="2" name="size" style="margin-right:20px;">Small
																		</label>
																	</div>
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="fourChamberView">Four chamber view</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input fourChamberView" type="radio" value="1" name="fourChamberView" style="margin-right:20px;">Normal
																		</label>
																		<div class="form-check col-md-12" style="padding-left:30px;">
																			<input id="fourChamberView_text" name="fourChamberView_text" type="text" class="form-control input-sm" style="text-transform: none">
																		</div>	
																	</div>
																	<!-- <div class="form-check">
																		<div class="col-md-1" style="padding-left:0px;">
																			<input class="form-check-input fourChamberView" type="radio" value="2" name="fourChamberView">
																		</div>
																		<div class="col-md-11" style="padding-left:0px;">
																			<input id="fourChamberView_text" name="fourChamberView_text" type="text" class="form-control input-sm">
																		</div>
																	</div> -->
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="aorticArch">Aortic arch</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="1" name="aorticArch" style="margin-right:20px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="2" name="aorticArch" style="margin-right:20px;">Coarctation
																		</label>
																	</div>
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="aorticTrunk">Aortic trunk</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="1" name="aorticTrunk" style="margin-right:20px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="2" name="aorticTrunk" style="margin-right:20px;">Stenosis
																		</label>
																	</div>
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="pulmonary">Pulmonary</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="1" name="pulmonary" style="margin-right:20px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="2" name="pulmonary" style="margin-right:20px;">Stenosis
																		</label>
																	</div>
																</div>
															</div>	

															<div class="form-group">
																<label class="col-md-4 control-label" for="interventricularSeptum">Interventricular septum</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="1" name="interventricularSeptum" style="margin-right:20px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<div class="col-md-6" style="padding-left:0px;">
																			<label class="form-check-label">
																				<input class="form-check-input" type="radio" value="2" name="interventricularSeptum" style="margin-right:20px;">VSD
																			</label>
																		</div>
																		<div class="col-md-6" style="padding-left:0px;">
																			<div class="form-check">
																				<label class="form-check-label">
																					<input class="form-check-input" type="radio" value="1" name="vsd" style="margin-right:20px;">Membraneous
																				</label>
																			</div>
																			<div class="form-check">
																				<label class="form-check-label">
																					<input class="form-check-input" type="radio" value="2" name="vsd" style="margin-right:20px;">Muscular
																				</label>
																			</div>
																		</div>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="3" name="interventricularSeptum" style="margin-right:20px;">Combined
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="col-md-4 control-label" for="vsdSize" style="padding-left:0px;">Size of VSD</label>  
																		<div class="col-md-6 input-group">
																			<input id="vsdSize" name="vsdSize" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69">
																			<span class="input-group-addon">mm</span>
																		</div>
																	</div>
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="pericardium">Pericardium</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="1" name="pericardium" style="margin-right:20px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="2" name="pericardium" style="margin-right:20px;">Pericardial Effusion
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
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="1" name="diaphragm" style="margin-right:20px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="2" name="diaphragm" style="margin-right:20px;">Hernia
																		</label>
																	</div>
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label" for="lungs">Lungs</label>  
																<div class="col-md-8">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="1" name="lungs" style="margin-right:20px;">Normal
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="2" name="lungs" style="margin-right:20px;">Hypoplasia
																		</label>
																	</div>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="radio" value="3" name="lungs" style="margin-right:20px;">Pleural Effusion
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
													<label class="col-md-4 control-label" for="abdomen_anteriorChestWall">Anterior Chest Wall</label>  
													<div class="col-md-8">
														<div class="form-check">
															<label class="form-check-label">
																<input class="form-check-input" type="radio" value="1" name="abdomen_anteriorChestWall" style="margin-right:20px;">Intact
															</label>
														</div>
														<div class="form-check">
															<label class="form-check-label">
																<input class="form-check-input" type="radio" value="2" name="abdomen_anteriorChestWall" style="margin-right:20px;">Omphalocoele
															</label>
														</div>
														<div class="form-check">
															<label class="form-check-label">
																<input class="form-check-input" type="radio" value="3" name="abdomen_anteriorChestWall" style="margin-right:20px;">Gastroschisis
															</label>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-4 control-label" for="cord">Cord</label>  
													<div class="col-md-8">
														<div class="form-check col-md-6">  
															<div class="input-group">
																<input id="cord_A" name="cord_A" type="text" class="form-control input-sm" style="text-transform: none">
																<span class="input-group-addon">A</span>
															</div> 
														</div>
														<div class="form-check col-md-6"> 
															<div class="input-group">
																<input id="cord_V" name="cord_V" type="text" class="form-control input-sm" style="text-transform: none">
																<span class="input-group-addon">V</span>
															</div>
														</div>
														<div class="form-check">
															<label class="col-md-4 control-label" for="cordInsertion">Cord insertion</label>  
															<div class="col-md-8" style="padding-top:10px;">
																<!-- <div class="form-check">
																	<label class="form-check-label">
																		<input class="form-check-input" type="radio" value="1" name="cordInsertion" style="margin-right:20px;">Intact
																	</label>
																</div> -->
																<div class="form-check">
																	<div class="col-md-1" style="padding-left:0px;">
																		<input class="form-check-input cordInsertion" type="radio" value="1" name="cordInsertion">
																	</div>
																	<div class="col-md-2" style="padding-left:0px;"> Intact
																	</div>
																	<div class="col-md-9" style="padding-left:0px;">
																		<input id="cordInsertion_text" name="cordInsertion_text" type="text" class="form-control input-sm" style="text-transform: none">
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-4 control-label" for="stomach">Stomach</label>  
													<div class="col-md-8">
														<div class="form-check">
															<label class="form-check-label">
																<input class="form-check-input" type="radio" value="1" name="stomach" style="margin-right:20px;">Normal single bubble
															</label>
														</div>
														<div class="form-check">
															<label class="form-check-label">
																<input class="form-check-input" type="radio" value="2" name="stomach" style="margin-right:20px;">Double bubble
															</label>
														</div>
														<div class="form-check">
															<label class="form-check-label">
																<input class="form-check-input" type="radio" value="3" name="stomach" style="margin-right:20px;">Absent
															</label>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-4 control-label" for="liver">Liver</label>  
													<div class="col-md-8">
														<div class="form-check">
															<label class="form-check-label">
																<input class="form-check-input" type="radio" value="1" name="liver" style="margin-right:20px;">Normal
															</label>
														</div>
														<div class="form-check">
															<label class="form-check-label">
																<input class="form-check-input" type="radio" value="2" name="liver" style="margin-right:20px;">Hepatomegaly
															</label>
														</div>
														<div class="form-check">
															<label class="form-check-label">
																<input class="form-check-input" type="radio" value="3" name="liver" style="margin-right:20px;">Hypoplastic
															</label>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-4 control-label" for="kidney">Kidney</label>  
													<div class="col-md-8">

														<div class="form-group">
															<label class="col-md-2 control-label" for="rightKidney">Right</label>  
															<div class="col-md-8">
																<div class="form-check">
																	<label class="form-check-label">
																		<input class="form-check-input" type="radio" value="1" name="rightKidney" style="margin-right:20px;">Normal
																	</label>
																</div>
																<div class="form-check">
																	<label class="form-check-label">
																		<input class="form-check-input" type="radio" value="2" name="rightKidney" style="margin-right:20px;">Absent
																	</label>
																</div>
																<div class="form-check">
																	<label class="form-check-label">
																		<input class="form-check-input rightKidney" type="radio" value="3" name="rightKidney" style="margin-right:20px;">Hydronephrosis
																	</label>
																	<div class="form-check col-md-12" style="padding-left:30px;">
																		<input id="rightKidney_text" name="rightKidney_text" type="text" class="form-control input-sm" style="text-transform: none">
																	</div>
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="col-md-2 control-label" for="leftKidney">Left</label>  
															<div class="col-md-8">
																<div class="form-check">
																	<label class="form-check-label">
																		<input class="form-check-input" type="radio" value="1" name="leftKidney" style="margin-right:20px;">Normal
																	</label>
																</div>
																<div class="form-check">
																	<label class="form-check-label">
																		<input class="form-check-input" type="radio" value="2" name="leftKidney" style="margin-right:20px;">Absent
																	</label>
																</div>
																<div class="form-check">
																	<label class="form-check-label">
																		<input class="form-check-input leftKidney" type="radio" value="3" name="leftKidney" style="margin-right:20px;">Hydronephrosis
																	</label>
																	<div class="form-check col-md-12" style="padding-left:30px;">
																		<input id="leftKidney_text" name="leftKidney_text" type="text" class="form-control input-sm" style="text-transform: none">
																	</div>
																</div>
															</div>
														</div>
														
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-4 control-label" for="bladder">Bladder</label>  
													<div class="col-md-8">
														<div class="form-check">
															<label class="form-check-label">
																<input class="form-check-input" type="radio" value="1" name="bladder" style="margin-right:20px;">Normal
															</label>
														</div>
														<div class="form-check">
															<label class="form-check-label">
																<input class="form-check-input bladder" type="radio" value="2" name="bladder" style="margin-right:20px;">Absent
															</label>
															<div class="form-check col-md-12" style="padding-left:30px;">
																<input id="bladder_text" name="bladder_text" type="text" class="form-control input-sm" style="text-transform: none">
															</div>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-4 control-label" for="ascites">Ascites</label>  
													<div class="col-md-8">
														<div class="form-check">
															<label class="form-check-label">
																<input class="form-check-input" type="radio" value="1" name="ascites" style="margin-right:20px;">Distended
															</label>
														</div>
														<div class="form-check">
															<label class="form-check-label">
																<input class="form-check-input" type="radio" value="2" name="ascites" style="margin-right:20px;">Absent
															</label>
														</div>
														<div class="form-check">
															<label class="form-check-label">
																<input class="form-check-input" type="radio" value="3" name="ascites" style="margin-right:20px;">Present
															</label>
														</div>
													</div>
												</div>

											</div>
										</div>
									</div>

									<div class='col-md-6'>
										<div class="panel panel-info">
											<div class="panel-body" style="height: 795px;">

												<div class="form-group">
													<label class="col-md-4 control-label" for="upperLimbs">Upper limbs</label>  
													<div class="col-md-8">

														<div class="form-group">
															<label class="col-md-2 control-label" for="right_upperLimbs">Right</label>  
															<div class="col-md-8">
																<div class="col-md-4">
																	<input id="right_upperLimbs_1" name="right_upperLimbs_1" type="text" class="form-control input-sm">
																</div>
																<div class="col-md-4">
																	<input id="right_upperLimbs_2" name="right_upperLimbs_2" type="text" class="form-control input-sm">
																</div>
																<div class="col-md-4">
																	<input id="right_upperLimbs_3" name="right_upperLimbs_3" type="text" class="form-control input-sm">
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="col-md-2 control-label" for="left_upperLimbs">Left</label>  
															<div class="col-md-8">
																<div class="col-md-4">
																	<input id="left_upperLimbs_1" name="left_upperLimbs_1" type="text" class="form-control input-sm">
																</div>
																<div class="col-md-4">
																	<input id="left_upperLimbs_2" name="left_upperLimbs_2" type="text" class="form-control input-sm">
																</div>
																<div class="col-md-4">
																	<input id="left_upperLimbs_3" name="left_upperLimbs_3" type="text" class="form-control input-sm">
																</div>
															</div>
														</div>
														
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-4 control-label" for="lowerLimbs">Lower limbs</label>  
													<div class="col-md-8">

														<div class="form-group">
															<label class="col-md-2 control-label" for="right_lowerLimbs">Right</label>  
															<div class="col-md-8">
																<div class="col-md-4">
																	<input id="right_lowerLimbs_1" name="right_lowerLimbs_1" type="text" class="form-control input-sm">
																</div>
																<div class="col-md-4">
																	<input id="right_lowerLimbs_2" name="right_lowerLimbs_2" type="text" class="form-control input-sm">
																</div>
																<div class="col-md-4">
																	<input id="right_lowerLimbs_3" name="right_lowerLimbs_3" type="text" class="form-control input-sm">
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="col-md-2 control-label" for="left_lowerLimbs">Left</label>  
															<div class="col-md-8">
																<div class="col-md-4">
																	<input id="left_lowerLimbs_1" name="left_lowerLimbs_1" type="text" class="form-control input-sm">
																</div>
																<div class="col-md-4">
																	<input id="left_lowerLimbs_2" name="left_lowerLimbs_2" type="text" class="form-control input-sm">
																</div>
																<div class="col-md-4">
																	<input id="left_lowerLimbs_3" name="left_lowerLimbs_3" type="text" class="form-control input-sm">
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

				</form>

			</div>
		</div>
	</div>	
</div>
	
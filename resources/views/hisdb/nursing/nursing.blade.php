   
<div class="panel panel-default" style="position: relative;" id="jqGridTriageInfo_c">
	<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
		id="btn_grp_edit_ti"
		style="position: absolute;
				padding: 0 0 0 0;
				right: 30px;
				top: 10px;" 

	>
		<button type="button" class="btn btn-default" id="edit_ti">
			<span class="fa fa-edit fa-lg"></span> Edit
		</button>
		<button type="button" class="btn btn-default" id="save_ti">
			<span class="fa fa-save fa-lg"></span> Save
		</button>
		<button type="button" class="btn btn-default" id="cancel_ti" >
			<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
		</button>
	</div>
	<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGridTriageInfo_panel">
		<b><span id="name_show_ti"></span></b><br>
		<span id="newic_show_ti"></span>
		<span id="sex_show_ti"></span>
		<span id="age_show_ti"></span>
		<span id="race_show_ti"></span>

		<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
		<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 250px; top: 10px;">
			<h5>Triage Information</h5>
		</div>				
	</div>
	<div id="jqGridTriageInfo_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<!-- <table id="jqGridTriageInfo" class="table table-striped"></table>
				<div id="jqGridPagerTriageInfo"></div> -->

				<form class='form-horizontal' style='width:99%' id='formTriageInfo'>

					<div class='col-md-5'>
						<div class="panel panel-info">
							<div class="panel-heading text-center">Information</div>
							<div class="panel-body">

								<div class="form-group">
									<label class="col-md-3 control-label" for="admwardtime">Time</label>  
									<div class="col-md-5">
										<input id="admwardtime" name="admwardtime" type="time" class="form-control input-sm uppercase">
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label" for="reg_date">Date</label>  
									<div class="col-md-5">
										<input id="reg_date" name="reg_date" type="date" class="form-control input-sm uppercase" frozeOnEdit>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label" for="tricolorzone">Triage Color Zone</label>  
									<div class="col-md-5">
										<div class='input-group'>
											<input id="tricolorzone" name="tricolorzone" type="text" class="form-control input-sm uppercase">
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										</div>
										<span class="help-block"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label" for="chiefcomplain" >Chief Complain</label>  
									<div class="col-md-9">
										<textarea id="chiefcomplain" name="chiefcomplain" type="text" class="form-control input-sm uppercase" rows="4"></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label" for="medicalhistory">Medical History</label>  
									<div class="col-md-9">
										<textarea id="medicalhistory" name="medicalhistory" type="text" class="form-control input-sm uppercase" rows="4"></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label" for="surgicalhistory">Surgical History</label>  
									<div class="col-md-9">
										<textarea id="surgicalhistory" name="surgicalhistory" type="text" class="form-control input-sm uppercase" rows="4"></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label" for="familymedicalhist">Family Medical History</label>  
									<div class="col-md-9">
										<textarea id="familymedicalhist" name="familymedicalhist" type="text" class="form-control input-sm uppercase" rows="4"></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label" for="currentmedication">Current Medication</label>  
									<div class="col-md-9">
										<textarea id="currentmedication" name="currentmedication" type="text" class="form-control input-sm uppercase" rows="4"></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label" for="diagnosis">Diagnosis</label>  
									<div class="col-md-9">
										<textarea id="diagnosis" name="diagnosis" type="text" class="form-control input-sm uppercase" rows="4"></textarea>
									</div>
								</div>

								<div class="panel panel-info">
									<div class="panel-heading text-center">Allergies</div>
									<div class="panel-body">

										<div class="form-group">
											<div class="form-check form-check-inline checkbox-inline">
												<input class="form-check-input" type="checkbox" id="allergydrugs" value="allergydrugs">
												<label class="form-check-label" for="allergydrugs">Drugs</label>
											</div>

											<div class="form-check form-check-inline checkbox-inline">
												<input class="form-check-input" type="checkbox" id="allergyplaster" value="allergyplaster">
												<label class="form-check-label" for="allergyplaster">Plaster</label>
											</div>

											<div class="form-check form-check-inline checkbox-inline">
												<input class="form-check-input" type="checkbox" id="allergyfood" value="allergyfood">
												<label class="form-check-label" for="allergyfood">Food</label>
											</div>

											<div class="form-check form-check-inline checkbox-inline">
												<input class="form-check-input" type="checkbox" id="allergyenviroment" value="allergyenviroment">
												<label class="form-check-label" for="allergyenviroment">Environment</label>
											</div>

											<div class="form-check form-check-inline checkbox-inline">
												<input class="form-check-input" type="checkbox" id="allergyothers" value="allergyothers">
												<label class="form-check-label" for="allergyothers">Others</label>
											</div>
										</div>

										<div class="form-group">
											<label class="col-md-4 control-label" for="allergyremarks">If Others, Please specify:</label>  
											<div class="col-md-8">
												<textarea id="allergyremarks" name="allergyremarks" type="text" class="form-control input-sm uppercase"></textarea>
											</div>
										</div>

									</div>
								</div>

							</div>
						</div>
					</div>

					<div class='col-md-7'>
						<div class="panel panel-info">
							<div class="panel-heading text-center">Condition on Admission</div>
							<div class="panel-body">

								<div class='col-md-8'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">VITAL SIGN</div>
										<div class="panel-body">

											<div class="form-group">
												<label class="col-md-4 control-label" for="vs_temperature">Temperature</label>  
												<div class="col-md-7 input-group">
													<input id="vs_temperature" name="vs_temperature" type="text" class="form-control input-sm uppercase">
													<span class="input-group-addon">°C</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-md-4 control-label" for="vs_pulse">Pulse</label>  
												<div class="col-md-7 input-group">
													<input id="vs_pulse" name="vs_pulse" type="text" class="form-control input-sm uppercase">
													<span class="input-group-addon">/min</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-md-4 control-label" for="vs_respiration">Respiration</label>  
												<div class="col-md-7 input-group">
													<input id="vs_respiration" name="vs_respiration" type="text" class="form-control input-sm uppercase">
													<span class="input-group-addon">/min</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-md-4 control-label" for="vs_bloodpressure">Blood Pressure</label>
												<div class="col-md-7 input-group">
													<input id="vs_bloodpressure" name="vs_bloodpressure" type="text" class="form-control input-sm uppercase" style="width: 49px">
													<label class="col-md-1 control-label">/</label> 
													<input id="vs_bloodpressure" name="vs_bloodpressure" type="text" class="form-control input-sm uppercase" style="width: 49px">
													<span class="input-group-addon">/mmHg</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-md-4 control-label" for="vs_height">Height</label>  
												<div class="col-md-7 input-group">
													<input id="vs_height" name="vs_height" type="text" class="form-control input-sm uppercase">
													<span class="input-group-addon">cm</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-md-4 control-label" for="vs_weight">Weight</label>  
												<div class="col-md-7 input-group">
													<input id="vs_weight" name="vs_weight" type="text" class="form-control input-sm uppercase">
													<span class="input-group-addon">kg</span>
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-md-4 control-label" for="vs_gxt">GXT</label>  
												<div class="col-md-7 input-group">
													<input id="vs_gxt" name="vs_gxt" type="text" class="form-control input-sm uppercase">
													<span class="input-group-addon">mmOL</span>
												</div>
											</div>

											<div class="form-group">
												<label class="col-md-4 control-label" for="vs_painscore">Pain Score</label>  
												<div class="col-md-7 input-group">
													<input id="vs_painscore" name="vs_painscore" type="text" class="form-control input-sm uppercase">
													<span class="input-group-addon">/10</span>
												</div>
											</div>

										</div>
									</div>
								</div>

								<div class='col-md-4'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">MODE OF ADMISSION</div>
										<div class="panel-body">

											<div class="form-check" style="margin-left: 20px">
												<input class="form-check-input" type="checkbox" value="moa_walkin" id="moa_walkin">
												<label class="form-check-label" for="moa_walkin">Walk In</label>
											</div>

											<div class="form-check" style="margin-left: 20px">
												<input class="form-check-input" type="checkbox" value="moa_wheelchair" id="moa_wheelchair">
												<label class="form-check-label" for="moa_wheelchair">Wheel Chair</label>
											</div>

											<div class="form-check" style="margin-left: 20px">
												<input class="form-check-input" type="checkbox" value="moa_trolley" id="moa_trolley">
												<label class="form-check-label" for="moa_trolley">Trolley</label>
											</div>

											<div class="form-check" style="margin-left: 20px">
												<input class="form-check-input" type="checkbox" value="moa_others" id="moa_others">
												<label class="form-check-label" for="moa_others">Others</label>
											</div>

										</div>
									</div>
								</div>

								<div class='col-md-12'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">LEVEL OF CONSCIOUSNESS</div>
										<div class="panel-body">

											<div class="form-group">
												<div class="form-check form-check-inline checkbox-inline" style="margin-left: 100px">
													<input class="form-check-input" type="checkbox" id="loc_conscious" value="loc_conscious">
													<label class="form-check-label" for="loc_conscious">Conscious</label>
												</div>

												<div class="form-check form-check-inline checkbox-inline">
													<input class="form-check-input" type="checkbox" id="loc_semiconscious" value="loc_semiconscious">
													<label class="form-check-label" for="loc_semiconscious">SemiConscious</label>
												</div>

												<div class="form-check form-check-inline checkbox-inline">
													<input class="form-check-input" type="checkbox" id="loc_unconscious" value="loc_unconscious">
													<label class="form-check-label" for="loc_unconscious">UnConscious</label>
												</div>
											</div>

										</div>
									</div>
								</div>

								<div class='col-md-6'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">MENTAL STATUS</div>
										<div class="panel-body" style="height: 170px">

											<div class="form-check" style="margin-left: 60px">
												<input class="form-check-input" type="checkbox" value="ms_orientated" id="ms_orientated">
												<label class="form-check-label" for="ms_orientated">Orientated</label>
											</div>

											<div class="form-check" style="margin-left: 60px">
												<input class="form-check-input" type="checkbox" value="ms_confused" id="ms_confused">
												<label class="form-check-label" for="ms_confused">Confused</label>
											</div>

											<div class="form-check" style="margin-left: 60px">
												<input class="form-check-input" type="checkbox" value="ms_restless" id="ms_restless">
												<label class="form-check-label" for="ms_restless">Restless</label>
											</div>

											<div class="form-check" style="margin-left: 60px">
												<input class="form-check-input" type="checkbox" value="ms_aggressive" id="ms_aggressive">
												<label class="form-check-label" for="ms_aggressive">Aggressive</label>
											</div>

										</div>
									</div>
								</div>

								<div class='col-md-6'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">EMOTIONAL STATUS</div>
										<div class="panel-body" style="height: 170px">

											<div class="form-check" style="margin-left: 60px">
												<input class="form-check-input" type="checkbox" value="es_calm" id="es_calm">
												<label class="form-check-label" for="es_calm">Calm</label>
											</div>

											<div class="form-check" style="margin-left: 60px">
												<input class="form-check-input" type="checkbox" value="es_anxious" id="es_anxious">
												<label class="form-check-label" for="es_anxious">Anxious</label>
											</div>

											<div class="form-check" style="margin-left: 60px">
												<input class="form-check-input" type="checkbox" value="es_distress" id="es_distress">
												<label class="form-check-label" for="es_distress">Distress</label>
											</div>

											<div class="form-check" style="margin-left: 60px">
												<input class="form-check-input" type="checkbox" value="es_depressed" id="es_depressed">
												<label class="form-check-label" for="es_depressed">Depressed</label>
											</div>

											<div class="form-check" style="margin-left: 60px">
												<input class="form-check-input" type="checkbox" value="es_irritable" id="es_irritable">
												<label class="form-check-label" for="es_irritable">Irritable</label>
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

<div class="panel panel-default" style="position: relative;" id="jqGridActDaily_c">
	<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
		id="btn_grp_edit_ad"
		style="position: absolute;
				padding: 0 0 0 0;
				right: 30px;
				top: 10px;" 

	>
		<button type="button" class="btn btn-default" id="edit_ad">
			<span class="fa fa-edit fa-lg"></span> Edit
		</button>
		<button type="button" class="btn btn-default" id="save_ad">
			<span class="fa fa-save fa-lg"></span> Save
		</button>
		<button type="button" class="btn btn-default" id="cancel_ad" >
			<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
		</button>
	</div>
	<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGridActDaily_panel">
		<b><span id="name_show_ad"></span></b><br>
		<span id="newic_show_ad"></span>
		<span id="sex_show_ad"></span>
		<span id="age_show_ad"></span>
		<span id="race_show_ad"></span>

		<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
		<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 250px; top: 10px;">
			<h5>Activities of Daily Living</h5>
		</div>
	</div>
	<div id="jqGridActDaily_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<!-- <table id="jqGridActDaily" class="table table-striped"></table>
				<div id="jqGridPagerActDaily"></div> -->

				<form class='form-horizontal' style='width:99%' id='formActDaily'>

					<div class='col-md-5'>

						<div class='col-md-12'>
							<div class="panel panel-info">
								<div class="panel-heading text-center">BREATHING</div>
								<div class="panel-body">

									<div class="form-group">
										<label class="col-sm-4 control-label" for="diffbreathe" style="padding-left: 0px">Any Difficulties In Breathing?</label>  
										<label class="radio-inline">
											<input type="radio" name="diffbreathe" value="Yes">Yes
										</label>
										<label class="radio-inline">
											<input type="radio" name="diffbreathe" value="No">No
										</label>
									</div>

									<div class="form-group">
										<label class="col-md-4 control-label" for="diffbreatheyes">If Yes, Describe:</label>  
										<div class="col-md-8" style="padding-left: 0px">
											<input id="diffbreatheyes" name="diffbreatheyes" type="text" class="form-control input-sm uppercase">
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-4 control-label" for="coughing">Have Any Cough?</label>  
										<label class="radio-inline">
											<input type="radio" name="coughing" value="Yes">Yes
										</label>
										<label class="radio-inline">
											<input type="radio" name="coughing" value="No">No
										</label>
									</div>

									<div class="form-group">
										<label class="col-md-4 control-label" for="coughyes">If Yes, Describe:</label>  
										<div class="col-md-8" style="padding-left: 0px">
											<input id="coughyes" name="coughyes" type="text" class="form-control input-sm uppercase">
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-4 control-label" for="smoking">Does He/She Smoke?</label>  
										<label class="radio-inline">
											<input type="radio" name="smoking" value="Yes">Yes
										</label>
										<label class="radio-inline">
											<input type="radio" name="smoking" value="No">No
										</label>
									</div>

									<div class="form-group">
										<label class="col-md-4 control-label" for="smokeyes">If Yes, Amount:</label>  
										<div class="col-md-8" style="padding-left: 0px">
											<input id="smokeyes" name="smokeyes" type="text" class="form-control input-sm uppercase">
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
										<label class="col-md-4 control-label" for="eatdrink" style="padding-left: 0px">Any Problem with Eating/Drinking?</label>  
										<label class="radio-inline">
											<input type="radio" name="eatdrink" value="Yes">Yes
										</label>
										<label class="radio-inline">
											<input type="radio" name="eatdrink" value="No">No
										</label>
									</div>

									<div class="form-group">
										<label class="col-md-4 control-label" for="eatdrinkyes">If Yes, Describe:</label>  
										<div class="col-md-8" style="padding-left: 0px">
											<input id="eatdrinkyes" name="eatdrinkyes" type="text" class="form-control input-sm uppercase">
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
										<label class="col-md-6 control-label" for="bowelhabits" style="padding-left: 0px">Have Notice Any Changes In Bowel Habis Lately?</label>  
										<label class="radio-inline">
											<input type="radio" name="bowelhabits" value="Yes">Yes
										</label>
										<label class="radio-inline">
											<input type="radio" name="bowelhabits" value="No">No
										</label>
									</div>

									<div class="form-group">
										<label class="col-md-6 control-label" for="takemedication">Take Any Medication For Bowel Movement?</label>  
										<label class="radio-inline">
											<input type="radio" name="takemedication" value="Yes">Yes
										</label>
										<label class="radio-inline">
											<input type="radio" name="takemedication" value="No">No
										</label>
									</div>

									<div class="form-group">
										<label class="col-md-4 control-label" for="medicyes">If Yes, Describe:</label>  
										<div class="col-md-8" style="padding-left: 0px">
											<input id="medicyes" name="medicyes" type="text" class="form-control input-sm uppercase">
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
										<label class="col-md-5 control-label" for="urineprob">Have Any Problem Passing Urine?</label>  
										<label class="radio-inline">
											<input type="radio" name="urineprob" value="Yes">Yes
										</label>
										<label class="radio-inline">
											<input type="radio" name="urineprob" value="No">No
										</label>
									</div>

									<div class="form-group">
										<label class="col-md-5 control-label" for="probyes">If Yes, Describe:</label>  
										<div class="col-md-7" style="padding-left: 0px">
											<input id="probyes" name="probyes" type="text" class="form-control input-sm uppercase">
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-5 control-label" for="howoften">How Often Get Up At Night To Pass Urine?</label>  
										<div class="col-md-7" style="padding-left: 0px">
											<input id="howoften" name="howoften" type="text" class="form-control input-sm uppercase">
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
										<label class="col-md-6 control-label" for="sleepmedication" style="padding-left: 0px">Required Medication To Sleep?</label>  
										<label class="radio-inline">
											<input type="radio" name="sleepmedication" value="Yes">Yes
										</label>
										<label class="radio-inline">
											<input type="radio" name="sleepmedication" value="No">No
										</label>
									</div>

									<div class='col-md-4'>
										<div class="panel panel-info">
											<div class="panel-heading text-center">Mobility</div>
											<div class="panel-body" style="padding: 15px 0; height: 120px">
											
												<div class="form-check" style="margin-left: 10px">
													<input class="form-check-input" type="checkbox" value="ambulant" id="ambulant">
													<label class="form-check-label" for="ambulant">Ambulant</label>
												</div>

												<div class="form-check" style="margin-left: 10px">
													<input class="form-check-input" type="checkbox" value="assist" id="assist">
													<label class="form-check-label" for="assist">Assist With AIDS</label>
												</div>

												<div class="form-check" style="margin-left: 10px">
													<input class="form-check-input" type="checkbox" value="bedridden" id="bedridden">
													<label class="form-check-label" for="bedridden">Bedridden</label>
												</div>

											</div>
										</div>
									</div>

									<div class='col-md-4'>
										<div class="panel panel-info">
											<div class="panel-heading text-center">Personal Hygiene</div>
											<div class="panel-body" style="padding: 15px 0; height: 120px">

												<div class="form-check" style="margin-left: 10px">
													<input class="form-check-input" type="checkbox" value="self" id="self">
													<label class="form-check-label" for="self">Self</label>
												</div>

												<div class="form-check" style="margin-left: 10px">
													<input class="form-check-input" type="checkbox" value="needassist" id="needassist">
													<label class="form-check-label" for="needassist">Need Assistant</label>
												</div>

												<div class="form-check" style="margin-left: 10px">
													<input class="form-check-input" type="checkbox" value="totaldependant" id="totaldependant">
													<label class="form-check-label" for="totaldependant">Totally Dependant</label>
												</div>

											</div>
										</div>
									</div>

									<div class='col-md-4'>
										<div class="panel panel-info">
											<div class="panel-heading text-center">Safe Environment</div>
											<div class="panel-body" style="height: 120px">

												<div class="form-check" style="margin-left: 20px">
													<input class="form-check-input" type="checkbox" value="siderail" id="siderail">
													<label class="form-check-label" for="siderail">Siderail</label>
												</div>

												<div class="form-check" style="margin-left: 20px">
													<input class="form-check-input" type="checkbox" value="restraint" id="restraint">
													<label class="form-check-label" for="restraint">Restraint</label>
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
											<div class="panel-heading text-center">Speech</div>
											<div class="panel-body" style="height: 170px">

												<div class="form-check" style="margin-left: 20px">
													<input class="form-check-input" type="checkbox" value="normal" id="normal">
													<label class="form-check-label" for="normal">Normal</label>
												</div>

												<div class="form-check" style="margin-left: 20px">
													<input class="form-check-input" type="checkbox" value="slurred" id="slurred">
													<label class="form-check-label" for="slurred">Slurred</label>
												</div>

												<div class="form-check" style="margin-left: 20px">
													<input class="form-check-input" type="checkbox" value="impaired" id="impaired">
													<label class="form-check-label" for="impaired">Impaired</label>
												</div>

												<div class="form-check" style="margin-left: 20px">
													<input class="form-check-input" type="checkbox" value="mute" id="mute">
													<label class="form-check-label" for="mute">Mute</label>
												</div>

											</div>
										</div>
									</div>

									<div class='col-md-4'>
										<div class="panel panel-info">
											<div class="panel-heading text-center">Vision</div>
											<div class="panel-body" style="padding: 15px 0; height: 170px">

												<div class="form-check" style="margin-left: 20px">
													<input class="form-check-input" type="checkbox" value="normal" id="normal">
													<label class="form-check-label" for="normal">Normal</label>
												</div>

												<div class="form-check" style="margin-left: 20px">
													<input class="form-check-input" type="checkbox" value="blurring" id="blurring">
													<label class="form-check-label" for="blurring">Blurring</label>
												</div>

												<div class="form-check" style="margin-left: 20px">
													<input class="form-check-input" type="checkbox" value="doublevision" id="doublevision">
													<label class="form-check-label" for="doublevision">Double Vision</label>
												</div>

												<div class="form-check" style="margin-left: 20px">
													<input class="form-check-input" type="checkbox" value="blind" id="blind">
													<label class="form-check-label" for="blind">Blind</label>
												</div>

												<div class="form-check" style="margin-left: 20px">
													<input class="form-check-input" type="checkbox" value="visualaids" id="visualaids">
													<label class="form-check-label" for="visualaids">Visual Aids</label>
												</div>

											</div>
										</div>
									</div>

									<div class='col-md-4'>
										<div class="panel panel-info">
											<div class="panel-heading text-center">Hearing</div>
											<div class="panel-body" style="padding: 15px 0; height: 170px">

												<div class="form-check" style="margin-left: 20px">
													<input class="form-check-input" type="checkbox" value="normal" id="normal">
													<label class="form-check-label" for="normal">Normal</label>
												</div>

												<div class="form-check" style="margin-left: 20px">
													<input class="form-check-input" type="checkbox" value="deaf" id="deaf">
													<label class="form-check-label" for="deaf">Deaf</label>
												</div>

												<div class="form-check" style="margin-left: 20px">
													<input class="form-check-input" type="checkbox" value="hardofhearing" id="hardofhearing">
													<label class="form-check-label" for="hardofhearing">Hard of Hearing</label>
												</div>

												<div class="form-check" style="margin-left: 20px">
													<input class="form-check-input" type="checkbox" value="hearingaids" id="hearingaids">
													<label class="form-check-label" for="hearingaids">Hearing Aids</label>
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

<div class="panel panel-default" style="position: relative;" id="jqGridTriPhysical_c">
	<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
		id="btn_grp_edit_tpa"
		style="position: absolute;
				padding: 0 0 0 0;
				right: 30px;
				top: 10px;" 

	>
		<button type="button" class="btn btn-default" id="edit_tpa">
			<span class="fa fa-edit fa-lg"></span> Edit
		</button>
		<button type="button" class="btn btn-default" id="save_tpa">
			<span class="fa fa-save fa-lg"></span> Save
		</button>
		<button type="button" class="btn btn-default" id="cancel_tpa" >
			<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
		</button>
	</div>
	<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGridTriPhysical_panel">
		<b><span id="name_show_tpa"></span></b><br>
		<span id="newic_show_tpa"></span>
		<span id="sex_show_tpa"></span>
		<span id="age_show_tpa"></span>
		<span id="race_show_tpa"></span>

		<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
		<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 250px; top: 10px;">
			<h5>Triage Physical Assessment</h5>
		</div>
	</div>
	<div id="jqGridTriPhysical_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<!-- <table id="jqGridTriPhysical" class="table table-striped"></table>
				<div id="jqGridPagerTriPhysical"></div> -->

				<form class='form-horizontal' style='width:99%' id='formTriPhysical'>

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
														<div class="panel-body" style="height: 150px">

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="dry" id="dry">
																<label class="form-check-label" for="dry">Dry</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="odema" id="odema">
																<label class="form-check-label" for="odema">Odema</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="jaundice" id="jaundice">
																<label class="form-check-label" for="jaundice">Jaundice</label>
															</div>

														</div>
													</div>
												</div>

												<div class='col-md-6'>
													<div class="panel panel-info">
														<div class="panel-heading text-center">OTHERS</div>
														<div class="panel-body" style="height: 150px">

														
															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="bruises" id="bruises">
																<label class="form-check-label" for="bruises">Bruises</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="decubituesulcer" id="decubituesulcer">
																<label class="form-check-label" for="decubituesulcer">Decubitues Ulcer</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="laceration" id="laceration">
																<label class="form-check-label" for="laceration">Laceration</label>
															</div>

															<div class="form-check">
																<input class="form-check-input" type="checkbox" value="discolouration" id="discolouration">
																<label class="form-check-label" for="discolouration">Discolouration</label>
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
													<label class="col-md-1 control-label" for="notes" >Notes:</label>  
													<div class="row">
														<textarea id="notes" name="notes" type="text" class="form-control input-sm uppercase" rows="6"></textarea>
													</div>
												</div>														

											</div>
										</div>
									</div>

								</div>

								<div class='col-md-6'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">EXAMINATION</div>
										<div class="panel-body">
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

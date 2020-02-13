@extends('layouts.main')

@section('title', 'Triage Assessment')

@section('style')

.panel-heading.collapsed .fa-angle-double-up,
.panel-heading .fa-angle-double-down {
	display: none;
}

.panel-heading.collapsed .fa-angle-double-down,
.panel-heading .fa-angle-double-up {
	display: inline-block;
}

i.fa {
	cursor: pointer;
	float: right;
	<!--  margin-right: 5px; -->
}

.collapsed ~ .panel-body {
	padding: 0;
}

.clearfix {
	overflow: auto;
}

input.uppercase {
	text-transform: uppercase;
}

fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}

legend.scheduler-border {
	font-size: 1.2em !important;
	font-weight: bold !important;
	text-align: left !important;
	width:auto;
	padding:0 10px;
	border-bottom:none;
}

@endsection

@section('body')

	<!--***************************** Search + table ******************-->
	<div class='row'>
		<!-- <form id="searchForm" class="formclass" style='width:99%; position:relative'>
			<fieldset>
				<input id="getYear" name="getYear" type="hidden"  value="<?php echo date("Y") ?>">

				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
						<div class="col-md-2">
							<label class="control-label" for="Scol">Search By : </label>  
					  		<select id='Scol' name='Scol' class="form-control input-sm"></select>
		              	</div>

					  	<div class="col-md-5">
					  		<label class="control-label"></label>  
							<input  name="Stext" type="search" seltext='true' placeholder="Search here ..." class="form-control text-uppercase">

							<div  id="show_chggroup_div" style="display:none">
								<div class='input-group'>
									<input id="show_chggroup" seltext='false' name="show_chggroup" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
						</div>
		            </div>
				</div>
			</fieldset> 
		</form> -->
        <br>
        
        <div class="panel panel-default" style="position: relative;" id="jqGridTriageInfo_c">
			<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
				id="btn_grp_edit"
				style="position: absolute;
						padding: 0 0 0 0;
						right: 30px;
						top: 10px;" 

			>
				<button type="button" class="btn btn-default" id="edit_rfde">
					<span class="fa fa-edit fa-lg"></span> Edit
				</button>
				<button type="button" class="btn btn-default" id="save_rfde">
					<span class="fa fa-save fa-lg"></span> Save
				</button>
				<button type="button" class="btn btn-default" id="cancel_rfde" >
					<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
				</button>
			</div>
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGridTriageInfo_panel">
				<b><span id="name_show"></span></b><br>
				<span id="newic_show"></span>
				<span id="sex_show"></span>
				<span id="age_show"></span>
				<span id="race_show"></span>

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

						<form class='form-horizontal' style='width:99%' id='formdata2'>

							<div class='col-md-6'>
								<div class="panel panel-info">
									<div class="panel-heading text-center">Information</div>
									<div class="panel-body">

										<div class="form-group">
											<label class="col-md-1 control-label" for="time">Time</label>  
											<div class="col-md-2">
												<input id="time" name="time" type="text" class="form-control input-sm uppercase">
											</div>

											<label class="col-md-1 control-label" for="date">Date</label>  
											<div class="col-md-2">
												<input id="date" name="date" type="text" class="form-control input-sm uppercase" frozeOnEdit>
											</div>

											<label class="col-md-3 control-label" for="tricolorzone">Triage Color Zone</label>  
											<div class="col-md-3">
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
												<textarea id="chiefcomplain" name="chiefcomplain" type="text" class="form-control input-sm uppercase"></textarea>
											</div>
										</div>

										<div class="form-group">
											<label class="col-md-3 control-label" for="medichistory">Medical History</label>  
											<div class="col-md-9">
												<textarea id="medichistory" name="medichistory" type="text" class="form-control input-sm uppercase"></textarea>
											</div>
										</div>

										<div class="form-group">
											<label class="col-md-3 control-label" for="surghistory">Surgical History</label>  
											<div class="col-md-9">
												<textarea id="surghistory" name="surghistory" type="text" class="form-control input-sm uppercase"></textarea>
											</div>
										</div>

										<div class="form-group">
											<label class="col-md-3 control-label" for="fammedichistory">Family Medical History</label>  
											<div class="col-md-9">
												<textarea id="fammedichistory" name="fammedichistory" type="text" class="form-control input-sm uppercase"></textarea>
											</div>
										</div>

										<div class="form-group">
											<label class="col-md-3 control-label" for="currmedic">Current Medication</label>  
											<div class="col-md-9">
												<textarea id="currmedic" name="currmedic" type="text" class="form-control input-sm uppercase"></textarea>
											</div>
										</div>

										<div class="panel panel-info">
											<div class="panel-heading text-center">Allergies</div>
											<div class="panel-body">

												<div class="form-group">
													<div class="form-check form-check-inline checkbox-inline" style="margin-left: 60px">
														<input class="form-check-input" type="checkbox" id="drugs" value="drugs">
														<label class="form-check-label" for="drugs">Drugs</label>
													</div>

													<div class="form-check form-check-inline checkbox-inline">
														<input class="form-check-input" type="checkbox" id="plaster" value="plaster">
														<label class="form-check-label" for="plaster">Plaster</label>
													</div>

													<div class="form-check form-check-inline checkbox-inline">
														<input class="form-check-input" type="checkbox" id="food" value="food">
														<label class="form-check-label" for="food">Food</label>
													</div>

													<div class="form-check form-check-inline checkbox-inline">
														<input class="form-check-input" type="checkbox" id="environment" value="environment">
														<label class="form-check-label" for="environment">Environment</label>
													</div>

													<div class="form-check form-check-inline checkbox-inline">
														<input class="form-check-input" type="checkbox" id="others" value="others">
														<label class="form-check-label" for="others">Others</label>
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-4 control-label" for="specify">If Others, Please specify:</label>  
													<div class="col-md-8">
														<textarea id="specify" name="specify" type="text" class="form-control input-sm uppercase"></textarea>
													</div>
												</div>

											</div>
										</div>

									</div>
								</div>
							</div>

							<div class='col-md-6'>
								<div class="panel panel-info">
									<div class="panel-heading text-center">Condition on Admission</div>
									<div class="panel-body">

										<div class='col-md-6'>
											<div class="panel panel-info">
												<div class="panel-heading text-center">VITAL SIGN</div>
												<div class="panel-body">

													<div class="form-group">
														<label class="col-md-3 control-label" for="temperature">Temperature</label>  
														<div class="col-md-7 input-group" style="margin-left: 100px">
															<input id="temperature" name="temperature" type="text" class="form-control input-sm uppercase">
															<span class="input-group-addon">°C</span>
														</div>
													</div>

													<div class="form-group">
														<label class="col-md-3 control-label" for="pulse">Pulse</label>  
														<div class="col-md-7 input-group" style="margin-left: 100px">
															<input id="pulse" name="pulse" type="text" class="form-control input-sm uppercase">
															<span class="input-group-addon">/min</span>
														</div>
													</div>

													<div class="form-group">
														<label class="col-md-3 control-label" for="respiration">Respiration</label>  
														<div class="col-md-7 input-group" style="margin-left: 100px">
															<input id="respiration" name="respiration" type="text" class="form-control input-sm uppercase">
															<span class="input-group-addon">/min</span>
														</div>
													</div>

													<div class="form-group">
														<label class="col-md-3 control-label" for="bloodpressure">Blood Pressure</label>
														<div class="col-md-9 input-group" style="margin-left: 100px">
															<input id="bloodpressure" name="bloodpressure" type="text" class="form-control input-sm uppercase" style="width: 45px">
															<label class="col-md-1 control-label">/</label> 
															<input id="bloodpressure" name="bloodpressure" type="text" class="form-control input-sm uppercase" style="width: 45px">
															<span class="input-group-addon" style="float: left;width: 70px;height: 30px">/mmHg</span>
														</div>
													</div>

													<div class="form-group">
														<label class="col-md-3 control-label" for="height">Height</label>  
														<div class="col-md-7 input-group" style="margin-left: 100px">
															<input id="height" name="height" type="text" class="form-control input-sm uppercase">
															<span class="input-group-addon">cm</span>
														</div>
													</div>

													<div class="form-group">
														<label class="col-md-3 control-label" for="weight">Weight</label>  
														<div class="col-md-7 input-group" style="margin-left: 100px">
															<input id="weight" name="weight" type="text" class="form-control input-sm uppercase">
															<span class="input-group-addon">kg</span>
														</div>
													</div>
													
													<div class="form-group">
														<label class="col-md-3 control-label" for="GXT">GXT</label>  
														<div class="col-md-7 input-group" style="margin-left: 100px">
															<input id="GXT" name="GXT" type="text" class="form-control input-sm uppercase">
															<span class="input-group-addon">mmOL</span>
														</div>
													</div>

													<div class="form-group">
														<label class="col-md-4 control-label" for="painscore">Pain Score</label>  
														<div class="col-md-7 input-group" style="margin-left: 100px">
															<input id="painscore" name="painscore" type="text" class="form-control input-sm uppercase">
															<span class="input-group-addon">/10</span>
														</div>
													</div>

												</div>
											</div>
										</div>

										<div class='col-md-6'>
											<div class="panel panel-info">
												<div class="panel-heading text-center">MODE OF ADMISSION</div>
												<div class="panel-body">

													<div class="form-check" style="margin-left: 80px">
														<input class="form-check-input" type="checkbox" value="walkIn" id="walkIn">
														<label class="form-check-label" for="walkIn">Walk In</label>
													</div>

													<div class="form-check" style="margin-left: 80px">
														<input class="form-check-input" type="checkbox" value="wheelchair" id="wheelchair">
														<label class="form-check-label" for="wheelchair">Wheel Chair</label>
													</div>

													<div class="form-check" style="margin-left: 80px">
														<input class="form-check-input" type="checkbox" value="trolley" id="trolley">
														<label class="form-check-label" for="trolley">Trolley</label>
													</div>

													<div class="form-check" style="margin-left: 80px">
														<input class="form-check-input" type="checkbox" value="others" id="others">
														<label class="form-check-label" for="others">Others</label>
													</div>

												</div>
											</div>
										</div>

										<div class='col-md-12'>
											<div class="panel panel-info">
												<div class="panel-heading text-center">LEVEL OF CONSCIOUSNESS</div>
												<div class="panel-body">

													<div class="form-group">
														<div class="form-check form-check-inline checkbox-inline" style="margin-left: 150px">
															<input class="form-check-input" type="checkbox" id="conscious" value="conscious">
															<label class="form-check-label" for="conscious">Conscious</label>
														</div>

														<div class="form-check form-check-inline checkbox-inline">
															<input class="form-check-input" type="checkbox" id="semiconscious" value="semiconscious">
															<label class="form-check-label" for="semiconscious">SemiConscious</label>
														</div>

														<div class="form-check form-check-inline checkbox-inline">
															<input class="form-check-input" type="checkbox" id="unconscious" value="unconscious">
															<label class="form-check-label" for="unconscious">UnConscious</label>
														</div>
													</div>

												</div>
											</div>
										</div>

										<div class='col-md-6'>
											<div class="panel panel-info">
												<div class="panel-heading text-center">MENTAL STATUS</div>
												<div class="panel-body" style="height: 170px">

													<div class="form-check" style="margin-left: 80px">
														<input class="form-check-input" type="checkbox" value="orientated" id="orientated">
														<label class="form-check-label" for="orientated">Orientated</label>
													</div>

													<div class="form-check" style="margin-left: 80px">
														<input class="form-check-input" type="checkbox" value="confused" id="confused">
														<label class="form-check-label" for="confused">Confused</label>
													</div>

													<div class="form-check" style="margin-left: 80px">
														<input class="form-check-input" type="checkbox" value="restless" id="restless">
														<label class="form-check-label" for="restless">Restless</label>
													</div>

													<div class="form-check" style="margin-left: 80px">
														<input class="form-check-input" type="checkbox" value="aggressive" id="aggressive">
														<label class="form-check-label" for="aggressive">Aggressive</label>
													</div>

												</div>
											</div>
										</div>

										<div class='col-md-6'>
											<div class="panel panel-info">
												<div class="panel-heading text-center">EMOTIONAL STATUS</div>
												<div class="panel-body" style="height: 170px">

													<div class="form-check" style="margin-left: 80px">
														<input class="form-check-input" type="checkbox" value="calm" id="calm">
														<label class="form-check-label" for="calm">Calm</label>
													</div>

													<div class="form-check" style="margin-left: 80px">
														<input class="form-check-input" type="checkbox" value="anxious" id="anxious">
														<label class="form-check-label" for="anxious">Anxious</label>
													</div>

													<div class="form-check" style="margin-left: 80px">
														<input class="form-check-input" type="checkbox" value="distress" id="distress">
														<label class="form-check-label" for="distress">Distress</label>
													</div>

													<div class="form-check" style="margin-left: 80px">
														<input class="form-check-input" type="checkbox" value="depressed" id="depressed">
														<label class="form-check-label" for="depressed">Depressed</label>
													</div>

													<div class="form-check" style="margin-left: 80px">
														<input class="form-check-input" type="checkbox" value="irritable" id="irritable">
														<label class="form-check-label" for="irritable">Irritable</label>
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
				id="btn_grp_edit"
				style="position: absolute;
						padding: 0 0 0 0;
						right: 30px;
						top: 10px;" 

			>
				<button type="button" class="btn btn-default" id="edit_rfde">
					<span class="fa fa-edit fa-lg"></span> Edit
				</button>
				<button type="button" class="btn btn-default" id="save_rfde">
					<span class="fa fa-save fa-lg"></span> Save
				</button>
				<button type="button" class="btn btn-default" id="cancel_rfde" >
					<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
				</button>
			</div>
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGridActDaily_panel">
				<b><span id="name_show"></span></b><br>
				<span id="newic_show"></span>
				<span id="sex_show"></span>
				<span id="age_show"></span>
				<span id="race_show"></span>

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

						<form class='form-horizontal' style='width:99%' id='formdata3'>

							<div class='col-md-6'>

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

							<div class='col-md-6'>

								<div class='col-md-12'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">SLEEPING</div>
										<div class="panel-body">

											<div class="form-group">
												<label class="col-md-4 control-label" for="sleepmedication" style="padding-left: 0px">Required Medication To Sleep?</label>  
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
													<div class="panel-body" style="height: 120px">
													
														<div class="form-check" style="margin-left: 30px">
															<input class="form-check-input" type="checkbox" value="ambulant" id="ambulant">
															<label class="form-check-label" for="ambulant">Ambulant</label>
														</div>

														<div class="form-check" style="margin-left: 30px">
															<input class="form-check-input" type="checkbox" value="assist" id="assist">
															<label class="form-check-label" for="assist">Assist With AIDS</label>
														</div>

														<div class="form-check" style="margin-left: 30px">
															<input class="form-check-input" type="checkbox" value="bedridden" id="bedridden">
															<label class="form-check-label" for="bedridden">Bedridden</label>
														</div>

													</div>
												</div>
											</div>

											<div class='col-md-4'>
												<div class="panel panel-info">
													<div class="panel-heading text-center">Personal Hygiene</div>
													<div class="panel-body" style="height: 120px">

														<div class="form-check" style="margin-left: 20px">
															<input class="form-check-input" type="checkbox" value="self" id="self">
															<label class="form-check-label" for="self">Self</label>
														</div>

														<div class="form-check" style="margin-left: 20px">
															<input class="form-check-input" type="checkbox" value="needassist" id="needassist">
															<label class="form-check-label" for="needassist">Need Assistant</label>
														</div>

														<div class="form-check" style="margin-left: 20px">
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
													<div class="panel-body" style="height: 170px">

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
													<div class="panel-body" style="height: 170px">

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
				id="btn_grp_edit"
				style="position: absolute;
						padding: 0 0 0 0;
						right: 30px;
						top: 10px;" 

			>
				<button type="button" class="btn btn-default" id="edit_rfde">
					<span class="fa fa-edit fa-lg"></span> Edit
				</button>
				<button type="button" class="btn btn-default" id="save_rfde">
					<span class="fa fa-save fa-lg"></span> Save
				</button>
				<button type="button" class="btn btn-default" id="cancel_rfde" >
					<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
				</button>
			</div>
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGridTriPhysical_panel">
				<b><span id="name_show"></span></b><br>
				<span id="newic_show"></span>
				<span id="sex_show"></span>
				<span id="age_show"></span>
				<span id="race_show"></span>

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

						<form class='form-horizontal' style='width:99%' id='formdata4'>

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

																	<div class="form-check" style="margin-left: 80px">
																		<input class="form-check-input" type="checkbox" value="dry" id="dry">
																		<label class="form-check-label" for="dry">Dry</label>
																	</div>

																	<div class="form-check" style="margin-left: 80px">
																		<input class="form-check-input" type="checkbox" value="odema" id="odema">
																		<label class="form-check-label" for="odema">Odema</label>
																	</div>

																	<div class="form-check" style="margin-left: 80px">
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

																
																	<div class="form-check" style="margin-left: 70px">
																		<input class="form-check-input" type="checkbox" value="bruises" id="bruises">
																		<label class="form-check-label" for="bruises">Bruises</label>
																	</div>

																	<div class="form-check" style="margin-left: 70px">
																		<input class="form-check-input" type="checkbox" value="decubituesulcer" id="decubituesulcer">
																		<label class="form-check-label" for="decubituesulcer">Decubitues Ulcer</label>
																	</div>

																	<div class="form-check" style="margin-left: 70px">
																		<input class="form-check-input" type="checkbox" value="laceration" id="laceration">
																		<label class="form-check-label" for="laceration">Laceration</label>
																	</div>

																	<div class="form-check" style="margin-left: 70px">
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

    </div>
	<!-- ***************End Search + table ********************* -->

@endsection


@section('scripts')

	<script src="js/hisdb/nursing/nursing.js"></script>
	
@endsection
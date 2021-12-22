
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
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 310px; top: 25px;">
			<h5>Dietetic Care Notes</h5>
		</div>	

		<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
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
	</div>			
	</div>
	<div id="jqGridDieteticCareNotes_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<!-- <table id="jqGridTriageInfo" class="table table-striped"></table>
				<div id="jqGridPagerTriageInfo"></div> -->

				<form class='form-horizontal' style='width:99%' id='formDieteticCareNotes'>

					<div class='col-md-12'>
						<div class="panel panel-info">
							<!-- <div class="panel-heading text-center">DIETETIC CARE NOTES</div> -->
							<div class="panel-body">

								<input id="mrn_dieteticCareNotes" name="mrn_dieteticCareNotes" type="hidden">
								<input id="episno_dieteticCareNotes" name="episno_dieteticCareNotes" type="hidden">

								<div class='col-md-6'>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label" for="dietHistory" style="padding-bottom:5px">Diet History/Summary</label>
                                            <textarea id="dietHistory" name="dietHistory" type="text" class="form-control input-sm" rows="6"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label" for="nutriDiagnosis" style="padding-bottom:5px">Nutrition Diagnosis</label>
                                            <textarea id="nutriDiagnosis" name="nutriDiagnosis" type="text" class="form-control input-sm" rows="4"></textarea>
                                        </div>
                                    </div>
								</div>

                                <div class='col-md-6'>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label" for="nutriIntervention" style="padding-bottom:5px">Nutrition Intervention</label>
                                            <textarea id="nutriIntervention" name="nutriIntervention" type="text" class="form-control input-sm" rows="6"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class='col-md-12' style="padding-top:15px">
									<div class="panel panel-info">
                                        <div class="panel-heading text-center">FOLLOW UP DIETETIC CARE NOTES</div>
										<div class="panel-body">

											<div class='col-md-6'>
												<div class="panel panel-info">
													<div class="panel-heading text-center">MONITORING AND EVALUATION</div>
													<div class="panel-body">

														<div class="form-group">
															<div class="col-md-12">
																<label class="control-label" for="commOnProgress" style="padding-bottom:5px">Comment on Progress (Anthropometry/Biochemical/Clinical/Dietary)</label>
																<textarea id="commOnProgress" name="commOnProgress" type="text" class="form-control input-sm" rows="6"></textarea>
															</div>
														</div>

														<div class="form-group">
															<div class="col-md-12">
																<label class="control-label" for="nutriDiagnosis" style="padding-bottom:5px">Nutrition Diagnosis</label>
																<textarea id="nutriDiagnosis" name="nutriDiagnosis" type="text" class="form-control input-sm" rows="4"></textarea>
															</div>
														</div>

													</div>
												</div>
											</div>

											<div class='col-md-6'>
												<div class="form-group">
													<div class="col-md-12">
														<label class="control-label" for="nutriIntervention" style="padding-bottom:5px">Nutrition Intervention</label>
														<textarea id="nutriIntervention" name="nutriIntervention" type="text" class="form-control input-sm" rows="6"></textarea>
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
	
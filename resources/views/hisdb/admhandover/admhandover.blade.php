
<div class="panel panel-default" style="position: relative;" id="jqGridAdmHandover_c">
	<div class="panel-heading clearfix collapsed position" id="toggle_admHandover" style="position: sticky;top: 0px;z-index: 3;">
		<b>NAME: <span id="name_show_admHandover"></span></b><br>
		MRN: <span id="mrn_show_admHandover"></span>
		SEX: <span id="sex_show_admHandover"></span>
		DOB: <span id="dob_show_admHandover"></span>
		AGE: <span id="age_show_admHandover"></span>
		RACE: <span id="race_show_admHandover"></span>
		RELIGION: <span id="religion_show_admHandover"></span><br>
		OCCUPATION: <span id="occupation_show_admHandover"></span>
		CITIZENSHIP: <span id="citizenship_show_admHandover"></span>
		AREA: <span id="area_show_admHandover"></span>
		
		<i class="arrow fa fa-angle-double-up" style="font-size: 24px;margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridAdmHandover_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size: 24px;margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridAdmHandover_panel"></i>
		<div class="pull-right" style="position: absolute;padding: 0 0 0 0;right: 310px;top: 25px;">
			<h5>Admission Handover</h5>
		</div>
		<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
			id="btn_grp_edit_admHandover" 
			style="position: absolute;
					padding: 0 0 0 0;
					right: 40px;
					top: 25px;">
			<button type="button" class="btn btn-default" id="new_admHandover">
				<span class="fa fa-plus-square-o"></span> New 
			</button>
			<button type="button" class="btn btn-default" id="edit_admHandover">
				<span class="fa fa-edit fa-lg"></span> Edit 
			</button>
			<button type="button" class="btn btn-default" data-oper='add' id="save_admHandover">
				<span class="fa fa-save fa-lg"></span> Save 
			</button>
			<button type="button" class="btn btn-default" id="cancel_admHandover">
				<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
			</button>
		</div>
	</div>
	
	<div id="jqGridAdmHandover_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding: 0 0 15px 0;">
				<form class='form-horizontal' style='width: 99%;' id='formAdmHandover'>
					<div class='col-md-6'>
						<div class="panel panel-info">
							<div class="panel-body" style="height: 240px;margin-left: 50px">
								<input id="mrn_admHandover" name="mrn_admHandover" type="hidden">
								<input id="episno_admHandover" name="episno_admHandover" type="hidden">
								
								<div class="form-group">
									<label class="col-md-2 control-label" for="dateofadm">Date of Admission</label>
									<div class="col-md-4">
										<input name="dateofadm" type="date" class="form-control input-sm">
									</div>

									<label class="col-md-2 control-label" for="type">Type</label>
									<div class="col-md-4">
										<label class="radio-inline">
											<input type="radio" name="type" value="IP">Inpatient
										</label>
										<label class="radio-inline">
											<input type="radio" name="type" value="DC">Daycare
										</label>									
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-md-2 control-label" for="reasonadm">Reason Admission</label>
									<div class="col-md-8">
										<textarea id="reasonadm" name="reasonadm" type="text" class="form-control input-sm" rdonly></textarea>
									</div>
								</div>
							</div>
						</div>

						<div class="panel panel-info">
							<div class="panel-body" style="height: 240px;margin-left: 50px">
								
								<div class="form-group">
									<label class="col-md-2 control-label" for="vs_weight">Weight</label>
									<div class="col-md-8">
										<div class="input-group">
											<input id="vs_weight" name="vs_weight" type="text" class="form-control input-sm" data-sanitize="numberFormat" rdonly>
											<span class="input-group-addon">kg</span>
										</div>									
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" for="medicalhistory">Medical History</label>
									<div class="col-md-8">
										<textarea id="medicalhistory" name="medicalhistory" type="text" class="form-control input-sm" readonly></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" for="surgicalhistory">Surgical History</label>
									<div class="col-md-8">
										<textarea id="surgicalhistory" name="surgicalhistory" type="text" class="form-control input-sm" readonly></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class='col-md-6'>
						<div class="panel panel-info">
							<div class="panel-heading text-center">ALLERGIES</div>
							<div class="panel-body" style="height: 460px;margin-left: 50px">
								
								<table class="table table-sm table-hover">
									<tbody>
										<tr>
											<td><input class="form-check-input" type="checkbox" id="allergydrugs" name="allergydrugs" value="1"></td>
											<td><label class="form-check-label" for="allergydrugs">Drugs</label></td>
											<td><textarea id="drugs_remarks" name="drugs_remarks" type="text" class="form-control input-sm"></textarea></td>
										</tr>
										<tr>
											<td><input class="form-check-input" type="checkbox" id="allergyplaster" name="allergyplaster" value="1"></td>
											<td><label class="form-check-label" for="allergyplaster">Plaster</label></td>
											<td><textarea id="plaster_remarks" name="plaster_remarks" type="text" class="form-control input-sm"></textarea></td>
										</tr>
										<tr>
											<td><input class="form-check-input" type="checkbox" id="allergyfood" name="allergyfood" value="1"></td>
											<td><label class="form-check-label" for="allergyfood">Food</label></td>
											<td><textarea id="food_remarks" name="food_remarks" type="text" class="form-control input-sm"></textarea></td>
										</tr>
										<tr>
											<td><input class="form-check-input" type="checkbox" id="allergyenvironment" name="allergyenvironment" value="1"></td>
											<td><label class="form-check-label" for="allergyenvironment">Environment</label></td>
											<td><textarea id="environment_remarks" name="environment_remarks" type="text" class="form-control input-sm"></textarea></td>
										</tr>
										<tr>
											<td><input class="form-check-input" type="checkbox" id="allergyothers" name="allergyothers" value="1"></td>
											<td><label class="form-check-label" for="allergyothers">Others</label></td>
											<td><textarea id="others_remarks" name="others_remarks" type="text" class="form-control input-sm"></textarea></td>
										</tr>
										<tr>
											<td><input class="form-check-input" type="checkbox" id="allergyunknown" name="allergyunknown" value="1"></td>
											<td><label class="form-check-label" for="allergyunknown">Unknown</label></td>
											<td><textarea id="unknown_remarks" name="unknown_remarks" type="text" class="form-control input-sm"></textarea></td>
										</tr>
										<tr>
											<td><input class="form-check-input" type="checkbox" id="allergynone" name="allergynone" value="1"></td>
											<td><label class="form-check-label" for="allergynone">None</label></td>
											<td><textarea id="none_remarks" name="none_remarks" type="text" class="form-control input-sm"></textarea></td>
										</tr>
									</tbody>
								</table>
								
							</div>
						</div>
					</div>
					<div class='col-md-12'>
						<div class="panel panel-info">
						<div class="panel-heading text-center">REQUIRED</div>
						<table class="table table-striped">
							<thead>
								<tr>
								<th scope="col" class="col-md-1">No</th>
								<th scope="col" class="col-md-2">Plan</th>
								<th scope="col" class="col-md-1"></th>
								<th scope="col" class="col-md-7">Remark</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th scope="row">1</th>
									<td>RTK/PCR</td>
									<td>
										<label class="radio-inline">
											<input type="radio" name="rtkpcr" value="1">Yes
										</label>
										<label class="radio-inline">
											<input type="radio" name="rtkpcr" value="0">No
										</label>
									</td>
									<td>
										<textarea id="rtkpcr_remark" name="rtkpcr_remark" type="text" class="form-control input-sm"></textarea>
									</td>
								</tr>
								<tr>
									<th scope="row">2</th>
									<td>Blood Investigation</td>
									<td>
										<label class="radio-inline">
											<input type="radio" name="bloodinv" value="1">Yes
										</label>
										<label class="radio-inline">
											<input type="radio" name="bloodinv" value="0">No
										</label>
									</td>
									<td>
										<textarea id="bloodinv_remark" name="bloodinv_remark" type="text" class="form-control input-sm"></textarea>
									</td>
								</tr>
								<tr>
									<th scope="row">3</th>
									<td>Branula</td>
									<td>
										<label class="radio-inline">
											<input type="radio" name="branula" value="1">Yes
										</label>
										<label class="radio-inline">
											<input type="radio" name="branula" value="0">No
										</label>
									</td>
									<td>
										<textarea id="branula_remark" name="branula_remark" type="text" class="form-control input-sm"></textarea>
									</td>
								</tr>
								<tr>
									<th scope="row">4</th>
									<td>CXR/MRI/CT Scan</td>
									<td>
										<label class="radio-inline">
											<input type="radio" name="scan" value="1">Yes
										</label>
										<label class="radio-inline">
											<input type="radio" name="scan" value="0">No
										</label>
									</td>
									<td>
										<textarea id="scan_remark" name="scan_remark" type="text" class="form-control input-sm"></textarea>
									</td>
								</tr>
								<tr>
									<th scope="row">5</th>
									<td>Insurance</td>
									<td>
										<label class="radio-inline">
											<input type="radio" name="insurance" value="1">Yes
										</label>
										<label class="radio-inline">
											<input type="radio" name="insurance" value="0">No
										</label>
									</td>
									<td>
										<textarea id="insurance_remark" name="insurance_remark" type="text" class="form-control input-sm"></textarea>
									</td>
								</tr>
								<tr>
									<th scope="row">6</th>
									<td>Medication (Antiplatlet)</td>
									<td>
										<label class="radio-inline">
											<input type="radio" name="medication" value="1">Yes
										</label>
										<label class="radio-inline">
											<input type="radio" name="medication" value="0">No
										</label>
									</td>
									<td>
										<textarea id="medication_remark" name="medication_remark" type="text" class="form-control input-sm"></textarea>
									</td>
								</tr>
								<tr>
									<th scope="row">7</th>
									<td>Consent</td>
									<td>
										<label class="radio-inline">
											<input type="radio" name="consent" value="1">Yes
										</label>
										<label class="radio-inline">
											<input type="radio" name="consent" value="0">No
										</label>
									</td>
									<td>
										<textarea id="consent_remark" name="consent_remark" type="text" class="form-control input-sm"></textarea>
									</td>
								</tr>
								<tr>
									<th scope="row">8</th>
									<td>Smoking</td>
									<td>
										<label class="radio-inline">
											<input type="radio" name="smoking" value="1">Yes
										</label>
										<label class="radio-inline">
											<input type="radio" name="smoking" value="0">No
										</label>
									</td>
									<td>
										<label class="control-label" for="smoking_remark" style="padding-bottom:5px">Last Time:</label>
										<textarea id="smoking_remark" name="smoking_remark" type="text" class="form-control input-sm"></textarea>
									</td>
								</tr>
								<tr>
									<th scope="row">9</th>
									<td>NBM</td>
									<td>
										<label class="radio-inline">
											<input type="radio" name="nbm" value="1">Yes
										</label>
										<label class="radio-inline">
											<input type="radio" name="nbm" value="0">No
										</label>
									</td>
									<td>
										<label class="control-label" for="nbm_remark" style="padding-bottom:5px">Last Meal:</label>
										<textarea id="nbm_remark" name="nbm_remark" type="text" class="form-control input-sm"></textarea>
									</td>
								</tr>
							</tbody>
						</table>
						</div>
					</div>
					<div class='col-md-12'>
						<div class="panel panel-info">
							<div class="panel-heading text-center">REPORT</div>
							<div class="panel-body">
								<div class='col-md-12'>
									<div class="form-group">
										<div class="col-md-12">
											<textarea id="report" name="report" type="text" class="form-control input-sm"></textarea>
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

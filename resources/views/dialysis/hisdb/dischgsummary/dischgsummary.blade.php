
<div class="panel panel-default" style="position: relative;" id="jqGridDischgSummary_c">
	
	<div class="panel-heading clearfix collapsed position" id="toggle_dischgSummary" style="position: sticky;top: 0px;z-index: 3;">
		<b>NAME: <span id="name_show_dischgSummary"></span></b><br>
		MRN: <span id="mrn_show_dischgSummary"></span>
		SEX: <span id="sex_show_dischgSummary"></span>
		DOB: <span id="dob_show_dischgSummary"></span>
		AGE: <span id="age_show_dischgSummary"></span>
		RACE: <span id="race_show_dischgSummary"></span>
		RELIGION: <span id="religion_show_dischgSummary"></span><br>
		OCCUPATION: <span id="occupation_show_dischgSummary"></span>
		CITIZENSHIP: <span id="citizenship_show_dischgSummary"></span>
		AREA: <span id="area_show_dischgSummary"></span>

		<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridDischgSummary_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridDischgSummary_panel"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 310px; top: 25px;">
			<h5>Discharge Summary</h5>
		</div>	

		<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
			id="btn_grp_edit_dischgSummary"
			style="position: absolute;
					padding: 0 0 0 0;
					right: 40px;
					top: 25px;" 

		>
		<button type="button" class="btn btn-default" id="new_dischgSummary">
			<span class="fa fa-plus-square-o"></span> New
		</button>
		<button type="button" class="btn btn-default" id="edit_dischgSummary">
			<span class="fa fa-edit fa-lg"></span> Edit
		</button>
		<button type="button" class="btn btn-default" data-oper='add' id="save_dischgSummary">
			<span class="fa fa-save fa-lg"></span> Save
		</button>
		<button type="button" class="btn btn-default" id="cancel_dischgSummary">
			<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
		</button>
	</div>			
	</div>
	<div id="jqGridDischgSummary_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<!-- <table id="jqGridTriageInfo" class="table table-striped"></table>
				<div id="jqGridPagerTriageInfo"></div> -->

				<form class='form-horizontal' style='width:99%' id='formDischgSummary'>

					<div class='col-md-7'>
						<div class="panel panel-info">
							<div class="panel-body">

								<input id="mrn_dischgSummary" name="mrn_dischgSummary" type="hidden">
								<input id="episno_dischgSummary" name="episno_dischgSummary" type="hidden">

								<div class="form-group">
									<label class="col-md-2 control-label" for="reg_time">Admission</label>  
									<div class="col-md-4">
										<input id="reg_time" name="reg_time" type="time" class="form-control input-sm" rdonly>
									</div>

									<label class="col-md-1 control-label" for="dischargetime">Discharge</label>  
									<div class="col-md-4">
										<input id="dischargetime" name="dischargetime" type="time" class="form-control input-sm">
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="diagprov">Provisional Diagnosis</label>  
									<div class="col-md-10">
										<textarea id="diagprov" name="diagprov" type="text" class="form-control input-sm" rows="4"></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="diagfinal">Final Diagnosis</label>  
									<div class="col-md-10">
										<textarea id="diagfinal" name="diagfinal" type="text" class="form-control input-sm" rows="4"></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="procedure">Operation Procedure</label>  
									<div class="col-md-10">
										<textarea id="procedure" name="procedure" type="text" class="form-control input-sm" rows="4"></textarea>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="treatment">Summary of Treatment</label>  
									<div class="col-md-10">
										<textarea id="treatment" name="treatment" type="text" class="form-control input-sm" rows="4"></textarea>
									</div>
								</div>

							</div>
						</div>
					</div>

					<div class='col-md-5'>
						<div class="panel panel-info">
							<div class="panel-body">

								<div class='col-md-12'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">DOCTOR</div>
										<div class="panel-body">

											<table class="table table-bordered table-sm">
												<thead>
													<tr>
														<th scope="col">Name</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><input name="admdoctor" type="text" style="border: none; background: transparent;" disabled="disabled" rdonly></td>
													</tr>
												</tbody>
											</table>

										</div>
									</div>
								</div>

								<div class='col-md-12'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">STATUS ON DISCHARGE</div>
										<div class="panel-body">

											<div class="form-check">
												<input class="form-check-input" type="radio" name="dischargestatus" id="well" value="well">
												<label class="form-check-label" for="well">Well</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="radio" name="dischargestatus" id="improved" value="improved">
												<label class="form-check-label" for="improved">Improved</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="radio" name="dischargestatus" id="aor" value="aor">
												<label class="form-check-label" for="aor">AOR</label>
											</div>
											
											<div class="form-check">
												<input class="form-check-input" type="radio" name="dischargestatus" id="expired" value="expired">
												<label class="form-check-label" for="expired">Expired</label>
											</div>	
											
											<div class="form-check">
												<input class="form-check-input" type="radio" name="dischargestatus" id="absconded" value="absconded">
												<label class="form-check-label" for="absconded">Absconded</label>
											</div>
											
											<div class="form-check">
												<input class="form-check-input" type="radio" name="dischargestatus" id="transferred" value="transferred">
												<label class="form-check-label" for="transferred">Transferred</label>
											</div>																

										</div>
									</div>
								</div>

								<div class='col-md-12'>
									<div class="panel panel-info">
										<div class="panel-body">

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="medOnDischarge" id="medOnDischarge" value="1">
												<label class="form-check-label" for="medOnDischarge">Medication(s) on Discharge</label>
											</div>

											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="medCert" id="medCert" value="1">
												<label class="form-check-label" for="medCert">Medical Certificate given</label>
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
	
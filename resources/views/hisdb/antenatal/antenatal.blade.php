
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

					<div class='col-md-7'>
						<div class="panel panel-info">
							<div class="panel-body">

								<div class='col-md-12'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">PAST GYNAECOLOGICAL HISTORY</div>
										<div class="panel-body">

											<input id="mrn_antenatal" name="mrn_antenatal" type="hidden">
											<input id="episno_antenatal" name="episno_antenatal" type="hidden">

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

											<div class="form-row">
												<div class="form-group col-md-5" style="margin-top: 10px;margin-left: 35px">
													<label for="lastpapsmear">Last Pap Smear</label>
													<input id="lastpapsmear" name="lastpapsmear" type="date" class="form-control input-sm">
												</div>
												<div class="form-group col-md-6" style="margin-top: 10px;margin-left: 2px">
													<label for="pgh_others">Others</label>
													<input id="pgh_others" name="pgh_others" type="text" class="form-control input-sm">
												</div>
											</div>

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
												<input id="pmh_others" name="pmh_others" type="text" class="form-control input-sm">
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
												<input id="psh_others" name="psh_others" type="text" class="form-control input-sm">
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
												<input id="fh_congenital" name="fh_congenital" type="text" class="form-control input-sm">
											</div>

										</div>
									</div>
								</div>

							</div>
						</div>
					</div>

					<div class='col-md-5'>
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
										<input id="anr_rhesus" name="anr_rhesus" type="text" class="form-control input-sm">
									</div>
									<div class="form-group col-md-6" style="margin-left: 2px">
										<label for="anr_rubella">Rubella Status</label>
										<input id="anr_rubella" name="anr_rubella" type="text" class="form-control input-sm">
									</div>
								</div>

								<div class="form-row col-md-12">
									<div class="form-group col-md-6" style="margin-left: 2px">
										<label for="anr_vdrl">VDRL</label>
										<input id="anr_vdrl" name="anr_vdrl" type="text" class="form-control input-sm">
									</div>
									<div class="form-group col-md-6" style="margin-left: 2px">
										<label for="anr_hiv">HIV</label>
										<input id="anr_hiv" name="anr_hiv" type="text" class="form-control input-sm">
									</div>
								</div>

								<div class='col-md-12' style="margin-top: 10px;">
									<div class="panel panel-info">
										<div class="panel-heading text-center">HEPATITIS B STATUS</div>
										<div class="panel-body">
										
											<div class="form-row">
												<div class="form-group col-md-6" style="margin-left: 2px">
													<label for="anr_hepaB_Ag">Ag</label>
													<input id="anr_hepaB_Ag" name="anr_hepaB_Ag" type="text" class="form-control input-sm">
												</div>
												<div class="form-group col-md-6" style="margin-left: 2px">
													<label for="anr_hepaB_AB">AB</label>
													<input id="anr_hepaB_AB" name="anr_hepaB_AB" type="text" class="form-control input-sm">
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

								<hr style="width: 100%;background-color: #96cdcd;height: 1px">

								<div class="form-group col-md-10" style="margin-top: 10px;margin-left: 35px">
									<label for="anr_bloodTrans">Blood Transfusion</label>
									<input id="anr_bloodTrans" name="anr_bloodTrans" type="text" class="form-control input-sm">
								</div>

								<hr style="width: 100%;background-color: #96cdcd;height: 1px">

								<div class="form-group col-md-10" style="margin-top: 10px;margin-left: 35px">
									<label for="anr_drugAllergies">Drug Allergies</label>
									<textarea id="anr_drugAllergies" name="anr_drugAllergies" type="text" class="form-control input-sm" rows="4"></textarea>
								</div>

							</div>
						</div>
					</div>

				</form>

			</div>
		</div>
	</div>	
</div>
	
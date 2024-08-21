
<div class="panel panel-default" style="position: relative;" id="jqGridDietOrder_c">
	<div class="panel-heading clearfix collapsed position" id="toggle_dietOrder" style="position: sticky;top: 0px;z-index: 3;">
		<b>NAME: <span id="name_show_dietOrder"></span></b><br>
		MRN: <span id="mrn_show_dietOrder"></span>
		SEX: <span id="sex_show_dietOrder"></span>
		DOB: <span id="dob_show_dietOrder"></span>
		AGE: <span id="age_show_dietOrder"></span>
		RACE: <span id="race_show_dietOrder"></span>
		RELIGION: <span id="religion_show_dietOrder"></span><br>
		OCCUPATION: <span id="occupation_show_dietOrder"></span>
		CITIZENSHIP: <span id="citizenship_show_dietOrder"></span>
		AREA: <span id="area_show_dietOrder"></span>
		
		<i class="arrow fa fa-angle-double-up" style="font-size: 24px;margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridDietOrder_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size: 24px;margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridDietOrder_panel"></i>
		<div class="pull-right" style="position: absolute;padding: 0 0 0 0;right: 310px;top: 25px;">
			<h5>Diet Order</h5>
		</div>
		<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
			id="btn_grp_edit_dietOrder" 
			style="position: absolute;
					padding: 0 0 0 0;
					right: 40px;
					top: 25px;">
			<button type="button" class="btn btn-default" id="new_dietOrder">
				<span class="fa fa-plus-square-o"></span> New 
			</button>
			<button type="button" class="btn btn-default" id="edit_dietOrder">
				<span class="fa fa-edit fa-lg"></span> Edit 
			</button>
			<button type="button" class="btn btn-default" data-oper='add' id="save_dietOrder">
				<span class="fa fa-save fa-lg"></span> Save 
			</button>
			<button type="button" class="btn btn-default" id="cancel_dietOrder">
				<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
			</button>
		</div>
	</div>
	
	<div id="jqGridDietOrder_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding: 0 0 15px 0;">
				<form class='form-horizontal' style='width: 99%;' id='formDietOrder'>
					<div class='col-md-12'>
						<div class="panel panel-info">
							<div class="panel-body">
								<input id="mrn_dietOrder" name="mrn_dietOrder" type="hidden">
								<input id="episno_dietOrder" name="episno_dietOrder" type="hidden">
								
								<div class="form-group">
									<label class="col-md-3 control-label" for="diagprov">Provisional Diagnosis</label>
									<div class="col-md-6">
										<input name="diagprov" type="text" class="form-control input-sm" rdonly>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-md-3 control-label" for="diagfinal">Final Diagnosis</label>
									<div class="col-md-6">
										<input name="diagfinal" type="text" class="form-control input-sm" rdonly>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-md-3 control-label" for="feedingmode">Mode of Feeding</label>
									<div class="col-md-6">
										<label class="radio-inline">
											<input type="radio" name="oral" value="1" id="feedingmode">Oral
										</label>
										<label class="radio-inline">
											<input type="radio" name="nbm" value="1" id="feedingmode">Nil by Mouth
										</label>
										<label class="radio-inline">
											<input type="radio" name="rtf" value="1" id="feedingmode">Ryles Tube Feeding
										</label>
										<label class="radio-inline">
											<input type="radio" name="rof" value="1" id="feedingmode">Restriction of Fluid
										</label>
										<label class="radio-inline">
											<input type="radio" name="tpn" value="1" id="feedingmode">Total Parenteral Nutrition
										</label>
									</div>
								</div>
								
								<div class="form-group" id="ifOral" style="display: none;">
									<div class="col-sm-6 col-sm-offset-3">
										<div class="panel panel-info">
											<div class="panel-heading text-center">Diet Order List</div>
											<div class="panel-body">
												<div class="row">
													<div class="col-6 col-sm-5">
														<div class="form-check" style="margin-left: 50px;">
															<input class="form-check-input" type="checkbox" id="regular_a" name="regular_a" value="1">
															<label class="form-check-label" for="regular_a">Regular (A)</label>
														</div>
														
														<div class="form-check" style="margin-left: 50px;">
															<input class="form-check-input" type="checkbox" id="regular_b" name="regular_b" value="1">
															<label class="form-check-label" for="regular_b">Regular (B)</label>
														</div>
														
														<div class="form-check" style="margin-left: 50px;">
															<input class="form-check-input" type="checkbox" id="soft" name="soft" value="1">
															<label class="form-check-label" for="soft">Soft</label>
														</div>
														
														<div class="form-check" style="margin-left: 50px;">
															<input class="form-check-input" type="checkbox" id="vegetarian_c" name="vegetarian_c" value="1">
															<label class="form-check-label" for="vegetarian_c">Vegetarian (C)</label>
														</div>
														
														<div class="form-check" style="margin-left: 50px;">
															<input class="form-check-input" type="checkbox" id="western_d" name="western_d" value="1">
															<label class="form-check-label" for="western_d">Western (D)</label>
														</div>
														
														<div class="form-check" style="margin-left: 50px;">
															<input class="form-check-input" type="checkbox" id="highprotein" name="highprotein" value="1">
															<label class="form-check-label" for="highprotein">High Protein</label>
														</div>
														
														<div class="form-check" style="margin-left: 50px;">
															<input class="form-check-input" type="checkbox" id="highcalorie" name="highcalorie" value="1">
															<label class="form-check-label" for="highcalorie">High Calorie</label>
														</div>
														
														<div class="form-check" style="margin-left: 50px;">
															<input class="form-check-input" type="checkbox" id="highfiber" name="highfiber" value="1">
															<label class="form-check-label" for="highfiber">High Fiber</label>
														</div>
														
														<div class="form-check" style="margin-left: 50px;">
															<input class="form-check-input" type="checkbox" id="diabetic" name="diabetic" value="1">
															<label class="form-check-label" for="diabetic">Diabetic</label>
														</div>
													</div>
													<div class="col-6 col-sm-5">
														<div class="form-check" style="margin-left: 50px;">
															<input class="form-check-input" type="checkbox" id="lowprotein" name="lowprotein" value="1">
															<label class="form-check-label" for="lowprotein">Low Protein</label>
														</div>
														
														<div class="form-check" style="margin-left: 50px;">
															<input class="form-check-input" type="checkbox" id="lowfat" name="lowfat" value="1">
															<label class="form-check-label" for="lowfat">Low Fat</label>
														</div>
														
														<div class="form-check" style="margin-left: 50px;">
															<input class="form-check-input" type="checkbox" id="red1200kcal" name="red1200kcal" value="1">
															<label class="form-check-label" for="red1200kcal">Reduction 1200 Kcal</label>
														</div>
														
														<div class="form-check" style="margin-left: 50px;">
															<input class="form-check-input" type="checkbox" id="red1500kcal" name="red1500kcal" value="1">
															<label class="form-check-label" for="red1500kcal">Reduction 1500 Kcal</label>
														</div>
														
														<div class="form-check" style="margin-left: 50px;">
															<input class="form-check-input" type="checkbox" id="paed6to12mth" name="paed6to12mth" value="1">
															<label class="form-check-label" for="paed6to12mth">Paediatrics 6-12 Months</label>
														</div>
														
														<div class="form-check" style="margin-left: 50px;">
															<input class="form-check-input" type="checkbox" id="paed1to3yr" name="paed1to3yr" value="1">
															<label class="form-check-label" for="paed1to3yr">Paediatrics 1-3 Years</label>
														</div>
														
														<div class="form-check" style="margin-left: 50px;">
															<input class="form-check-input" type="checkbox" id="paed4to9yr" name="paed4to9yr" value="1">
															<label class="form-check-label" for="paed4to9yr">Paediatrics 4-9 Years</label>
														</div>
														
														<div class="form-check" style="margin-left: 50px;">
															<input class="form-check-input" type="checkbox" id="paedgt10yr" name="paedgt10yr" value="1">
															<label class="form-check-label" for="paedgt10yr">Paediatrics >10 Years</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-md-3 control-label" for="lodgerflag">Additional Meals</label>
									<div class="col-md-6">
										<label class="radio-inline">
											<input type="radio" onclick="javascript:yesnoCheck();" id="yesCheck" name="lodgerflag" value="1">Yes
										</label>
										<label class="radio-inline">
											<input type="radio" onclick="javascript:yesnoCheck();" id="noCheck" name="lodgerflag" value="0">No
										</label>
										<div class="radio-inline" id="ifYes" style="display: none;">
											<label class="col-md-5 control-label" for="lodgervalue">No of Lodger</label>
											<div class="col-md-4">
												<input name="lodgervalue" type="number" class="form-control input-sm">
											</div>
										</div>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-md-3 control-label" for="disposable">Disposable</label>
									<div class="col-md-6">
										<label class="radio-inline">
											<input type="radio" name="disposable" value="1">Yes
										</label>
										<label class="radio-inline">
											<input type="radio" name="disposable" value="0">No
										</label>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-md-3 control-label" for="remark">Remark Ward</label>
									<div class="col-md-6">
										<textarea id="remark" name="remark" type="text" class="form-control input-sm"></textarea>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-md-3 control-label" for="remarkkitchen">Remark Kitchen</label>
									<div class="col-md-6">
										<textarea id="remarkkitchen" name="remarkkitchen" type="text" class="form-control input-sm"></textarea>
										<br>
										<button type="button" class="btn btn-light" id="preview" style="float: right;">Preview</button>
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

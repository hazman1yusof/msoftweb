
<div class="panel panel-default" style="position: relative;" id="jqGridOrderComm_c">
	<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
		id="btn_grp_edit_orderComm"
		style="position: absolute;
				padding: 0 0 0 0;
				right: 40px;
				top: 15px;" 

	>
		<button type="button" class="btn btn-default" id="new_orderComm">
			<span class="fa fa-plus-square-o"></span> New
		</button>
		<button type="button" class="btn btn-default" id="edit_orderComm">
			<span class="fa fa-edit fa-lg"></span> Edit
		</button>
		<button type="button" class="btn btn-default" data-oper='add' id="save_orderComm">
			<span class="fa fa-save fa-lg"></span> Save
		</button>
		<button type="button" class="btn btn-default" id="cancel_orderComm" >
			<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
		</button>
	</div>
	<div class="panel-heading clearfix collapsed position" id="toggle_orderComm" data-toggle="collapse" data-target="#jqGridorderComm_panel">
		<b>Name: <span id="name_show_orderComm"></span></b><br>
		MRN: <span id="mrn_show_orderComm"></span>

		<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 310px; top: 15px;">
			<h5>Order Communication</h5>
		</div>				
	</div>
	<div id="jqGridorderComm_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<!-- <table id="jqGridTriageInfo" class="table table-striped"></table>
				<div id="jqGridPagerTriageInfo"></div> -->

				<form class='form-horizontal' style='width:99%' id='formOrderComm'>

					<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

					<div class="col-sm-10 col-sm-offset-1">
						<div class="panel panel-info">
							<div class="panel-heading text-center">ORDER COMMUNICATION DETAIL</div>
							<div class="panel-body">

								<input id="mrn_orderComm" name="mrn_orderComm" type="hidden">
								<input id="episno_orderComm" name="episno_orderComm" type="hidden">

								<div class="form-group">
									<label class="col-md-2 control-label" for="trxtime">Time</label>  
									<div class="col-md-2">
										<input id="trxtime" name="trxtime" type="time" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter time.">
									</div>
									<span class="help-block"></span>

									<label class="col-md-3 control-label" for="trxdate">Date</label>  
									<div class="col-md-2">
										<input id="trxdate" name="trxdate" type="date" class="form-control input-sm" rdonly>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="chgcode">Charge Code</label>
									<div class="col-md-2">
										<div class='input-group'>
											<input id="chgcode" name="chgcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg-required="Please select chargecode.">
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										</div>
										<span class="help-block"></span>
									</div>

									<label class="col-md-3 control-label" for="trxtype">Type</label>
									<div class="col-md-3">
										<div class='input-group'>
											<input id="trxtype" name="trxtype" type="text" class="form-control input-sm text-uppercase" data-validation="required">
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										</div>
										<span class="help-block"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="quantity">Qty</label>
									<div class="col-md-3">
										<div class='input-group'>
											<input id="quantity" name="quantity" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter quantity."></input>
										</div>
										<span class="help-block"></span>
									</div>

									<label class="col-md-2 control-label" for="loccode">Description</label>
									<div class="col-md-3">
										<div class='input-group'>
											<input id="loccode" name="loccode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										</div>
										<span class="help-block"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="isudept">Issue Department</label>  
									<div class="col-md-4">
										<input id="isudept" name="isudept" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information."></input>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label" for="remarks">Note</label>  
									<div class="col-md-10">
										<textarea id="remarks" name="remarks" type="text" class="form-control input-sm" rows="4" data-validation="required" data-validation-error-msg-required="Please enter information."></textarea>
									</div>
								</div>

							</div>
						</div>
					</div>

					<div class='col-md-12'>
						<div class="panel panel-default">
							<div class="panel-heading text-center panelbgcolor">ORDER COMMUNICATION PANEL</div>
							<div class="panel-body">

								<div class='col-md-12'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">SELECT ORDER COMMUNICATION</div>
										<div class="panel-body">

											<!-- <div class='col-md-6'>

												<div class='col-md-12'>
													<div class="panel panel-info">
														<div class="panel-body">

															<div class='col-md-6'>
																<div class="panel panel-info">
																	<div class="panel-heading text-center">SKIN CONDITION</div>
																	<div class="panel-body" style="height: 160px">

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_skindry" id="pa_skindry" name="pa_skindry">
																			<label class="form-check-label" for="pa_skindry">Dry</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_skinodema" id="pa_skinodema" name="pa_skinodema">
																			<label class="form-check-label" for="pa_skinodema">Odema</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_skinjaundice" id="pa_skinjaundice" name="pa_skinjaundice">
																			<label class="form-check-label" for="pa_skinjaundice">Jaundice</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_skinnil" id="pa_skinnil" name="pa_skinnil">
																			<label class="form-check-label" for="pa_skinnil">NIL</label>
																		</div>

																	</div>
																</div>
															</div>

															<div class='col-md-6'>
																<div class="panel panel-info">
																	<div class="panel-heading text-center">OTHERS</div>
																	<div class="panel-body" style="height: 160px">

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_othbruises" id="pa_othbruises" name="pa_othbruises">
																			<label class="form-check-label" for="pa_othbruises">Bruises</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_othdeculcer" id="pa_othdeculcer" name="pa_othdeculcer">
																			<label class="form-check-label" for="pa_othdeculcer">Decubitues Ulcer</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_othlaceration" id="pa_othlaceration" name="pa_othlaceration">
																			<label class="form-check-label" for="pa_othlaceration">Laceration</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_othdiscolor" id="pa_othdiscolor" name="pa_othdiscolor">
																			<label class="form-check-label" for="pa_othdiscolor">Discolouration</label>
																		</div>

																		<div class="form-check">
																			<input class="form-check-input" type="checkbox" value="pa_othnil" id="pa_othnil" name="pa_othnil">
																			<label class="form-check-label" for="pa_othnil">NIL</label>
																		</div>

																	</div>
																</div>
															</div>
															
														</div>
													</div>
												</div>

												<div class='col-md-12'>
													<div class="panel panel-info">
														<div class="panel-heading text-center" style="position: relative;"> EXAMINATION 
															<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 10px; top: 2px;">
																<button type="button" class="btn btn-info" id="ward_exam_plus"><span class="fa fa-plus"></span></button>
															</div>
														</div>
														<div class="panel-body" id="ward_exam_div">
															
														</div>
													</div>
												</div>

											</div>

											<div class='col-md-6'>
												<div class="panel panel-info">
													<div class="panel-body">

														<div class="form-group">
															<label class="col-md-1 control-label" for="pa_notes" style="margin-bottom: 10px">Notes:</label>  
															<div class="row">
																<textarea id="pa_notes" name="pa_notes" type="text" class="form-control input-sm" rows="15" data-validation="required" data-validation-error-msg-required="Please enter notes."></textarea>
															</div>
														</div>														

													</div>
												</div>
											</div> -->

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
<div class="panel panel-default" style="position: relative;" id="jqGridEnquiryDtl2_c">
	<div class="panel-heading clearfix collapsed position" id="toggle_EnquiryDtl2" style="position: sticky;top: 0px;z-index: 3;">
		<b>Asset No: <span id="category_show_enquiryAE"></span></b>
		<b> - <span id="assetno_show_enquiryAE"></span>
		<br><span id="itemcode_show_enquiryAE"></span>
		<br><span class="desc_show" id="description_show_enquiryAE"></span><a id="seemore_show_enquiryAE" style="display: none" data-show='false'>see more</a></b>

		<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridEnquiryDtl2_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridEnquiryDtl2_panel"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 310px; top: 15px;">
			<h5>Asset Enquiry Detail</h5>
		</div>
		<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
			id="btn_grp_edit_EnquiryDtl2"
			style="position: absolute;
					padding: 0 0 0 0;
					right: 40px;
					top: 15px;" 

		>
			<!-- <button type="button" class="btn btn-default" id="new_EnquiryDtl2">
				<span class="fa fa-plus-square-o"></span> New
			</button> -->
			<button type="button" class="btn btn-default" id="edit_EnquiryDtl2">
				<span class="fa fa-edit fa-lg"></span> Edit
			</button>
			<button type="button" class="btn btn-default" data-oper='add' id="save_EnquiryDtl2">
				<span class="fa fa-save fa-lg"></span> Save
			</button>
			<button type="button" class="btn btn-default" id="cancel_EnquiryDtl2">
				<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
			</button>
		</div>
	</div>

	<div id="jqGridEnquiryDtl2_panel" class="panel-collapse collapse">
		<div class="panel-body paneldiv">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<form class='form-horizontal' style='width:99%' id='formEnquiryDtl2'>
					{{ csrf_field() }}
					<input type="hidden" name="idno">
					<div class='col-md-12'>
						<div class="panel panel-info">
							<div class="panel-heading text-center">ASSET ENQUIRY DETAIL</div>
							<div class="panel-body">

								<div class="form-group row">
									<label class="col-md-1 control-label" for="assetcode">Category</label>  
									<div class="col-md-3">
										<input id="assetcode" name="assetcode" type="text" class="form-control input-sm uppercase" data-validation="required"  frozeOnEdit>
									</div>

									<label class="col-md-1 control-label" for="assettype">Type</label>  
									<div class="col-md-3">
										<input id="assettype" name="assettype" type="text" class="form-control input-sm uppercase" data-validation="required"  frozeOnEdit>
									</div>

									<label class="col-md-1 control-label" for="assetno">NO</label>  
									<div class="col-md-3">
										<input id="assetno" name="assetno" type="text" class="form-control input-sm uppercase" data-validation="required"  frozeOnEdit>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-md-1 control-label" for="description">Description</label>  
										<div class="col-md-7">
											<textarea class="form-control input-sm text-uppercase" name="description" rows="4" cols="55" id="description"></textarea>
										</div>
								</div>

								<div class="form-group row">
									<label class="col-md-1 control-label" for="deptcode">Department</label>
										<div class="col-md-3">
											<input id="deptcode" name="deptcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
										</div>
									<label class="col-md-1 control-label" for="loccode">Location</label>
										<div class="col-md-3">
											<input id="loccode" name="loccode" type="text" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
										</div>
								</div>

								<hr>
								<div class="form-group row">
									<label class="col-md-1 control-label" for="suppcode">Supplier</label>
										<div class="col-md-3">
											<input id="suppcode" name="suppcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
										</div>
									<label class="col-md-1 control-label" for="delordno">Delivery Order No.</label>
										<div class="col-md-3">
											<input id="delordno" name="delordno" type="text" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
										</div>
								</div>

								<div class="form-group row">
									<label class="col-md-1 control-label" for="invno">Invoice No.</label>
										<div class="col-md-3">
											<input id="invno" name="invno" type="text" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
										</div>
									<label class="col-md-1 control-label" for="delorddate">Delivery Order Date</label>
										<div class="col-md-3">
											<input id="delorddate" name="delorddate" type="text" class="form-control input-sm" 	data-validation="required" frozeOnEdit>
										</div>
								</div>

								<div class="form-group row">
									<label class="col-md-1 control-label" for="invdate">Invoice Date</label>  
										<div class="col-md-3">
											<input id="invdate" name="invdate" type="text" class="form-control input-sm" 	data-validation="required" placeholder="YYYY-MM-DD" frozeOnEdit>
										</div>
								</div>
							
								<div class="form-group row">
									<label class="col-md-1 control-label" for="purordno">Purchase No.</label>
										<div class="col-md-3">
											<input id="purordno" type="text" name="purordno" class="form-control input-sm text-uppercase" frozeOnEdit>
										</div>
									<label class="col-md-1 control-label" for="purdate">Purchase Date</label>
										<div class="col-md-3">
											<input id="purdate" type="text" name="purdate" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
										</div>
								</div>

								<div class="form-group row">
									<label class="col-md-1 control-label" for="purprice">Price</label>  
										<div class="col-md-3">
											<input id="purprice" name="purprice" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" data-validation="required" frozeOnEdit>  
										</div>
									<label class="col-md-1 control-label" for="qty">Quantity</label>  
										<div class="col-md-3">
											<input id="qty" name="qty" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" data-validation="required" frozeOnEdit> 
										</div>
								</div>
								
								<hr>		

								<div class="form-group row">
									<label class="col-md-1 control-label" for="serialno">Serial No</label>  
										<div class="col-md-3">
											<input id="serialno" type="text" name="serialno" maxlength="12" class="form-control input-sm text-uppercase">
										</div>
									<label class="col-md-1 control-label" for="lotno">Lot No</label>  
										<div class="col-md-3">
											<input id="lotno" type="text" name="lotno" maxlength="12" class="form-control input-sm text-uppercase">
										</div>
								</div>

								<div class="form-group row">
									<label class="col-md-1 control-label" for="casisno">Casis No</label>  
										<div class="col-md-3">
											<input id="casisno" name="casisno" type="text" class="form-control input-sm text-uppercase" data-validation="required">
										</div>
									<label class="col-md-1 control-label" for="engineno">Engine No</label>  
										<div class="col-md-3">
											<input id="engineno" name="engineno" maxlength="12" class="form-control input-sm text-uppercase" data-sanitize="required" >  
										</div>
								</div>
						
								<hr>

								<div class="form-group row">
									<label class="col-md-1 control-label" for="method">Method</label>  
										<div class="col-md-3">
											<input id="method" type="text" name="method" maxlength="12" class="form-control input-sm" rdonly frozeOnEdit>
										</div>
									<label class="col-md-1 control-label" for="rvalue">Residual Value</label>  
										<div class="col-md-3">
											<input id="rvalue" type="text" name="rvalue" maxlength="12" class="form-control input-sm" rdonly frozeOnEdit>
										</div>
								</div>

								<div class="form-group row">
									<label class="col-md-1 control-label" for="statdate">Start Date</label>  
										<div class="col-md-3">
											<input id="statdate" name="statdate" type="text" class="form-control input-sm" 	data-validation="required" frozeOnEdit>
										</div>

										<label class="col-md-1 control-label" for="rate">Rate (%p.a)</label>  
										<div class="col-md-3">
											<input id="rate" type="text" name="rate" maxlength="12" class="form-control input-sm" rdonly frozeOnEdit>
										</div>
								</div>

								<div class="form-group row">
									<label class="col-md-1 control-label" for="origcost">Cost</label>  
										<div class="col-md-3">
											<input id="origcost" name="origcost" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" frozeOnEdit>  
										</div>
								</div>

								<div class="form-group row">
									<label class="col-md-1 control-label" for="lstytddep">Accum.(Prev Year)</label>  
										<div class="col-md-3">
											<input id="lstytddep" name="lstytddep" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" data-validation="required" frozeOnEdit>
										</div>
									<label class="col-md-1 control-label" for="recstatus">Status</label>
										<div class="col-md-3">
											<label class="radio-inline"><input type="radio" name="recstatus" value='ACTIVE' checked>ACTIVE</label>
										</div>
								</div>

								<div class="form-group row">
									<label class="col-md-1 control-label" for="cuytddep">Accum.(Y-T-D)</label>  
										<div class="col-md-3">
											<input id="cuytddep" name="cuytddep" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" data-validation="required" frozeOnEdit>
										</div>
									<label class="col-md-1 control-label" for="trantype">Tran Type</label>
										<div class="col-md-3">
											<input id="trantype" type="text" name="trantype" class="form-control input-sm" value="ADDITIONAL" rdonly frozeOnEdit>
										</div>
								</div>

								<div class="form-group row">
									<label class="col-md-1 control-label" for="nbv">N-B-V</label>
										<div class="col-md-3">
											<input id="nbv" type="text" name="nbv" maxlength="12" class="form-control input-sm" frozeOnEdit>
										</div>
										
									<label class="col-md-1 control-label" for="trandate">Post Date</label>  
										<div class="col-md-3">
											<input id="trandate" name="trandate" type="text" class="form-control input-sm" 	data-validation="required" frozeOnEdit>
										</div>
								</div>

								<div class="form-group" id="ifOral" style="display:none">
									<div class="col-sm-6 col-sm-offset-3">
										<div class="panel panel-info">
											<div class="panel-heading text-center">Diet Order List</div>
											<div class="panel-body">
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


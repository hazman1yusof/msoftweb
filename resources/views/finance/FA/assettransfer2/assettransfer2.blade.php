<div class="panel panel-default" style="position: relative;" id="jqGridtransferFA_c">
	<div class="panel-heading clearfix collapsed position" id="toggle_transferFA" style="position: sticky;top: 0px;z-index: 3;">
		<b>Asset No: <span id="category_show_transferAE"></span></b>
		<b> - <span id="assetno_show_transferAE"></span>
		<br><span id="itemcode_show_transferAE"></span>
		<br><span class="desc_show" id="description_show_transferAE"></span><a id="seemore_show_transferAE" style="display: none" data-show='false'>see more</a></b>

		<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridtransferFA_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridtransferFA_panel"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 310px; top: 15px;">
			<h5>Asset Transfer</h5>
		</div>
		<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
			id="btn_grp_edit_transferFA"
			style="position: absolute;
					padding: 0 0 0 0;
					right: 40px;
					top: 15px;" 

		>

			<button type="button" class="btn btn-default" id="edit_transferFA">
				<span class="fa fa-edit fa-lg"></span> Transfer
			</button>
			<button type="button" class="btn btn-default" data-oper='add' id="save_transferFA">
				<span class="fa fa-save fa-lg"></span> Save
			</button>
			<button type="button" class="btn btn-default" id="cancel_transferFA">
				<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
			</button>
		</div>
	</div>

	<div id="jqGridtransferFA_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<form class='form-horizontal' style='width:99%' id='formtransferFA'>
					{{ csrf_field() }}
					<input type="hidden" name="idno">
					<div class='col-md-12'>
						<div class="panel panel-info">
							<div class="panel-heading text-center">ASSET TRANSFER DETAIL</div>
							<div class="panel-body">

							<input id="assetlineno" name="assetlineno" type="hidden">

								<div class="form-group row">
									<label class="col-md-2 control-label" for="assetno">Tagging No</label>  
									<div class="col-md-3">
										<input id="assetno" name="assetno" type="text" class="form-control input-sm uppercase" data-validation="required"  frozeOnEdit>
									</div>

									<label class="col-md-2 control-label" for="description">Item Code</label>  
									<div class="col-md-3">
										<input id="itemcode" name="itemcode" type="text" class="form-control input-sm uppercase" data-validation="required"  frozeOnEdit>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-md-2 control-label" for="assetcode">Category</label>
										<div class="col-md-3">
											<input id="assetcode" name="assetcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
										</div>
									<label class="col-md-2 control-label" for="assettype">Type</label>
										<div class="col-md-3">
											<input id="assettype" name="assettype" type="text" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
										</div>
								</div>

								<hr>
								<div class="form-group row">
									<label class="col-md-2 control-label" for="currdeptcode">Current Department</label>
										<div class="col-md-3">
											<input id="currdeptcode" name="currdeptcode" type="text" class="form-control input-sm text-uppercase"  frozeOnEdit>
										</div>
									<label class="col-md-2 control-label" for="currloccode">Current Location</label>
										<div class="col-md-3">
											<input id="currloccode" name="currloccode" type="text" class="form-control input-sm text-uppercase" frozeOnEdit>
										</div>
								</div>
								
								<hr>		
	                            <div class="form-group">
	                                <label class="col-md-2 control-label" for="trandate">Date</label>  
	                                <div class="col-md-2">
	                                    <input name="trandate" type="date" class="form-control input-sm" data-validation="required">
	                                </div>
	                            </div>

								<div class="form-group row">
								<label class="col-md-2 control-label" for="newdeptcode">New Department</label>  
									<div class="col-md-3">
										<div class='input-group'>
											<input id="newdeptcode" name="newdeptcode" type="text" class="form-control input-sm uppercase">
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										</div>
										<span class="help-block"></span>
									</div>

									<label class="col-md-2 control-label" for="newloccode">New Location</label>  
									<div class="col-md-3">
										<div class='input-group'>
											<input id="newloccode" name="newloccode" type="text" class="form-control input-sm uppercase">
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										</div>
										<span class="help-block"></span>
									</div>
								</div>
						
								<hr>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>	
</div>

	<div id="jqGridtransferFA_panel2" class="panel-body">
		<div class='col-md-12' style="padding:0 0 15px 0">
			<form class='form-horizontal' style='width:99%' id='formtransferFA2'>
				<div class='col-md-12'>
					<div class="panel panel-info">
						<div class="panel-heading text-center">ASSET TRANSFER DETAIL</div>
						<div class="panel-body">

							<div class="form-group row">
								<label class="col-md-2 control-label" for="assetno">Tagging No</label>  
								<div class="col-md-3">
									<input id="assetno" name="assetno" type="text" class="form-control input-sm uppercase" data-validation="required" frozeOnEdit>
								</div>

								<label class="col-md-2 control-label" for="description">Item Code</label>  
								<div class="col-md-3">
									<input id="description" name="description" type="text" class="form-control input-sm uppercase" data-validation="required"  frozeOnEdit>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-2 control-label" for="assetcode">Category</label>
									<div class="col-md-3">
										<input id="assetcode" name="assetcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
									</div>
								<label class="col-md-2 control-label" for="assettype">Type</label>
									<div class="col-md-3">
										<input id="assettype" name="assettype" type="text" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
									</div>
							</div>

							<hr>
							<div class="form-group row">
								<label class="col-md-2 control-label" for="deptcode">Current Department</label>
									<div class="col-md-3">
										<input id="deptcode" name="deptcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
									</div>
								<label class="col-md-2 control-label" for="loccode">Current Location</label>
									<div class="col-md-3">
										<input id="loccode" name="loccode" type="text" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
									</div>
							</div>
							
							<hr>		
                            <div class="form-group">
                                <label class="col-md-2 control-label" for="trandate">Date</label>  
                                <div class="col-md-2">
                                    <input name="trandate" type="date" class="form-control input-sm" data-validation="required">
                                </div>
                            </div>

							<div class="form-group row">
							<label class="col-md-2 control-label" for="newdeptcode">New Department</label>  
								<div class="col-md-3">
									<div class='input-group'>
										<input id="newdeptcode" name="newdeptcode" type="text" class="form-control input-sm uppercase">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>

								<label class="col-md-2 control-label" for="newloccode">New Location</label>  
								<div class="col-md-3">
									<div class='input-group'>
										<input id="newloccode" name="newloccode" type="text" class="form-control input-sm uppercase">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>
							</div>
					
							<hr>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>


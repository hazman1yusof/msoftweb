
@extends('layouts.main')

@section('title', 'Diet Order')

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

	div i {
		position: relative;
		line-height: 1;
		top: -10px;
	}

	.collapsed ~ .panel-body {
		padding: 0;
	}

	tr.yellow{
		background-color:yellow !important;
	}

	input.yellow{
		color: black !important;
		border-color:#9e9e00 !important;
		background-color:yellow !important;
	}

	a.yellow{
		color: #9e9e00 !important;
		background-color: #fdffe2 !important;
		border-color: #9e9e00 !important;
	}

	tr.red{
		color:white;
		background-color:red !important;
	}

	input.red{
		color: white !important;
		border-color:red !important;
		background-color:red !important;
	}

	a.red{
		color: red !important;
		background-color: #ffe5e5 !important;
		border-color: red !important;
	}

	tr.green{
		color:white;
		background-color:green !important;
	}

	input.green{
		color: white !important;
		border-color:green !important;
		background-color:green !important;
	}

	a.green{
		color: #3c763d !important;
		background-color: #dff0d8 !important;
		border-color: #3c763d !important;
	}

	.panelbgcolor {
		background-color: #C0C0C0 !important;
	}

@endsection

@section('body')

	<div class='row'>   
		<div class="panel panel-default" style="position: relative;" id="jqGridDietOrder_c">
			<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
				id="btn_grp_edit_dietOrder"
				style="position: absolute;
						padding: 0 0 0 0;
						right: 40px;
						top: 15px;" 

			>
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
			<div class="panel-heading clearfix collapsed" id="toggle_dietOrder" data-toggle="collapse" data-target="#jqGridDietOrder_panel">
				<b><span id="name_show_dietOrder"></span></b><br>
				<span id="newic_show_dietOrder"></span>
				<span id="sex_show_dietOrder"></span>
				<span id="age_show_dietOrder"></span>
				<span id="race_show_dietOrder"></span>

				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 310px; top: 15px;">
					<h5>Diet Order</h5>
				</div>				
			</div>
			<div id="jqGridDietOrder_panel" class="panel-collapse collapse">
				<div class="panel-body">
					<div class='col-md-12' style="padding:0 0 15px 0">
						<!-- <table id="jqGridTriageInfo" class="table table-striped"></table>
						<div id="jqGridPagerTriageInfo"></div> -->

						<form class='form-horizontal' style='width:99%' id='formDietOrder'>

							<div class='col-md-12'>
								<div class="panel panel-info">
									<div class="panel-heading text-center">DIET ORDER</div>
									<div class="panel-body">

										<!-- <input id="mrn_edit_dietOrder" name="mrn_edit_dietOrder" type="hidden"> -->
										<input id="episno_dietOrder" name="episno_dietOrder" type="hidden">

										<div class="form-group">
											<label for="mrn" class="col-md-3 control-label">MRN</label>
											<div class="col-md-2">
												<div class="input-group">
													<input type="text" class="form-control input-sm" id="mrn" name="mrn" maxlength="12" rdonly>
													<!-- <a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a> -->
												</div>
												<span class='help-block'></span>
											</div>
											<div class="col-md-4">
												<input type="text" class="form-control input-sm text-uppercase" data-validation="required" id="patname" name="patname">
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-md-3 control-label" for="diagnosis">Diagnosis</label>
											<div class="col-md-6">
												<input id="diagnosis" name="diagnosis" type="text" class="form-control input-sm">
											</div>
										</div>

										<div class="form-group">
											<label class="col-md-3 control-label" for="feedingmode">Mode of Feeding</label>
											<div class="col-md-5">
												<select id="feedingmode" class="form-control">
													<option selected>Options</option>
													<option>Options</option>
												</select>
											</div>
											<button type="button" class="btn btn-light">Order List</button>
										</div>

										<div class="form-group">
											<label class="col-md-3 control-label" for="addmeals">Additional Meals</label>
											<div class="col-md-6">
												<label class="radio-inline">
													<input type="radio" name="addmeals" value="1">Yes
												</label>
												<label class="radio-inline">
													<input type="radio" name="addmeals" value="0">No
												</label>
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
											<label class="col-md-3 control-label" for="remarkward">Remark Ward</label>
											<div class="col-md-6">
												<textarea id="remarkward" name="remarkward" type="text" class="form-control input-sm" rows="6"></textarea>
											</div>
										</div>

										<div class="form-group">
											<label class="col-md-3 control-label" for="remarkkitchen">Remark Kitchen</label>
											<div class="col-md-6">
												<textarea id="remarkkitchen" name="remarkkitchen" type="text" class="form-control input-sm" rows="6"></textarea>
												<br>
												<button type="button" class="btn btn-light" style="float: right;">Preview</button>
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

@endsection


@section('scripts')

	<script src="js/hisdb/dietorder/dietorder.js"></script>

@endsection

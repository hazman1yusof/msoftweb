@extends('layouts.main')

@section('title', 'Bed Management Setup')

@section('style')

	.panel-heading.collapsed .fa-angle-double-up,
	.panel-heading .fa-angle-double-down {
		display: none;
	}

	.panel-heading.collapsed .fa-angle-double-down,
	.panel-heading .fa-angle-double-up {
		display: inline-block;
	}

	.panel-heading i.fa {
		cursor: pointer;
		float: right;
		<!--  margin-right: 5px; -->
	}

	.panel-heading div i {
		position: relative;
		line-height: 1;
		top: -10px;
	}

	<!-- /* The sticky class is added to the header with JS when it reaches its scroll position */ -->
	.sticky {
		z-index: 100;
		position: fixed;
		top: 0;
		width: 100%
	}

	.clearfix {
		overflow: auto;
	}

	input.uppercase {
		text-transform: uppercase;
	}

	.justify {
		text-align: -webkit-center;
	}

	row.error td { background-color: red; }

	i.arrow {
		cursor: pointer;
		float: right;
		<!--  margin-right: 5px; -->
	}

	.position i {
		position: relative;
		line-height: 1;
		top: -10px;
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

	<!--***************************** Search + table ******************-->
	<div class='row'>
		<div class="header" id="SearchFormHeader">
			<form id="searchForm" class="formclass" style='width:99%; position:relative; min-height: 120px'>
				<fieldset>
					<input id="csrf_token" name="csrf_token" type="hidden" value="{{ csrf_token() }}">

					<div class='col-md-12' style="padding:0 0 15px 0;">
						<div class="form-group"> 
							<div class="col-md-2">
								<label class="control-label" for="Scol">Search By : </label>  
								<select id='Scol' name='Scol' class="form-control input-sm"></select>
							</div>

							<div class="col-md-4">
								<label class="control-label"></label>  
								<input  name="Stext" type="search" seltext='true' placeholder="Search here ..." class="form-control text-uppercase">
							
								<div  id="show_bedtype" style="display:none">
									<div class='input-group'>
										<input id="s_bedtype" name="s_bedtype" type="text" maxlength="12" class="form-control input-sm">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>

								<!-- <div  id="show_statistic" style="display:none">
									<div class='input-group'>
										<input id="b_statistic" name="b_statistic" type="text" maxlength="12" class="form-control input-sm">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div> -->
								
								<div  id="show_occup" style="display:none">
									<div class='input-group'>
										<input id="occup" name="occup" type="text" maxlength="12" class="form-control input-sm">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>

								<div  id="show_doc" style="display:none">
									<div class='input-group'>
										<input id="doc" name="doc" type="text" maxlength="12" class="form-control input-sm">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div> 
							</div>

							<div class="col-md-1" style="padding-left: 0px;">
								<div id="div_bedtype" style="padding-left: 0px;max-width: 45px;display:none">
									<label class="control-label"></label>
									<a class='form-control btn btn-primary' id="btn_bedtype"><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<!-- <div id="div_statistic" style="padding-left: 0px;max-width: 45px;display:none;margin: 0px">
									<label class="control-label"></label>
									<a class='form-control btn btn-primary' id="btn_statistic"><span class='fa fa-ellipsis-h'></span></a>
								</div> -->
								<div id="div_occup" style="padding-left: 0px;max-width: 45px;display:none;margin: 0px">
									<label class="control-label"></label>
									<a class='form-control btn btn-primary' id="btn_occup"><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<div id="div_doc" style="padding-left: 0px;max-width: 45px;display:none;margin: 0px">
									<label class="control-label"></label>
									<a class='form-control btn btn-primary' id="btn_doc"><span class='fa fa-ellipsis-h'></span></a>
								</div>
							</div>
						</div>

						<div class="form-group" style="position: absolute; right: 10px;top: 8px; width: 35%">
							<div class="col-md-6" style="">
								<p><img src="img/bedonly.png" height="10" width="14"></img> VACANT: <span id="stat_vacant"></span></p>
							</div>
							<div class="col-md-6" style="">
								<p><i class="fa fa-bed" aria-hidden="true"></i> OCCUPIED: <span id="stat_occupied"></span></p>
							</div>
							<div class="col-md-6" style="">
								<p><i class="fa fa-female" aria-hidden="true"></i> HOUSEKEEPING: <span id="stat_housekeeping"></span></p>
							</div>
							<div class="col-md-6" style="">
								<p><i class="fa fa-gavel" aria-hidden="true"></i> MAINTENANCE: <span id="stat_maintenance"></span></p>
							</div>
							<div class="col-md-6" style="">
								<p><i class="fa fa-bullhorn" aria-hidden="true"></i> ISOLATED: <span id="stat_isolated"></span></p>
							</div>
							<div class="col-md-6" style="">
								<p><i class="fa fa-ban" aria-hidden="true"></i> RESERVE: <span id="stat_reserve"></span></p>
							</div>
							<div class="col-md-6" style="">
								<p><i class="fa fa-times" aria-hidden="true"></i> DEACTIVE: <span id="stat_deactive"></span></p>
							</div>  
							<!-- <div class="col-md-6" style="">
								<p><i class="fa fa-check" aria-hidden="true"></i> ACTIVE: <span id="stat_active"></span></p>
							</div> -->

						</div>

					</div>
				</fieldset> 
			</form>
		</div>

        <div class="panel panel-default">
		    <div class="panel-heading">Bed Management Setup Header</div>
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
            		<table id="jqGrid" class="table table-striped"></table>
            		<div id="jqGridPager"></div>
        		</div>
		    </div>
		</div>

		<div class="panel-group">
			<div class="panel panel-default" style="position: relative;" id="jqGrid_trf_c">
				<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
					id="btn_grp_edit_trf"
					style="position: absolute;
							padding: 0 0 0 0;
							right: 40px;
							top: 15px;" 

				>
					<button type="button" class="btn btn-default" id="edit_trf">
						<span class="fa fa-edit fa-lg"></span> Transfer
					</button>
					<button type="button" class="btn btn-default" data-oper='add' id="save_trf">
						<span class="fa fa-save fa-lg"></span> Save
					</button>
					<button type="button" class="btn btn-default" id="cancel_trf" >
						<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
					</button>
				</div>
				<div class="panel-heading clearfix collapsed position" id="toggle_trf" data-toggle="collapse" data-target="#jqGrid_trf_panel">
					<b>Name: <span id="name_show"></span></b><br>
						Bed No: <span id="bednum_show"></span>
						<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 10px"></i>
						<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 10px"></i>
						<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 280px; top: 15px;">
							<h5>Bed Occupancy Detail</h5>
						</div>		
				</div>

				<div id="jqGrid_trf_panel" class="panel-collapse collapse">
					<div class="panel-body">
						<div class='col-md-12' style="padding:0 0 15px 0">
							<table id="jqGrid_trf" class="table table-striped"></table>
							<div id="jqGridPager3"></div>

								<!-- form -->

							<form id='form_trf' class='form-vertical' style='width:99%'>
								<div class='col-md-12' style="padding:15px 0 15px 0">

									<div class='col-md-6'>
										<div class="panel panel-info">
											<div class="panel-heading text-center">CURRENT BED</div>
											<div class="panel-body">

												<div class="form-group row">
													<label class="col-md-2 control-label" for="date">Date</label>  
													<div class="col-md-4">
														<input id="ba_asdate" name="ba_asdate" type="date" class="form-control input-sm uppercase">
													</div>
												
													<label class="col-md-2 control-label" for="time">Time</label>  
													<div class="col-md-4">
														<input id="ba_astime" name="ba_astime" type="time" class="form-control input-sm uppercase">
													</div>
												</div>

												<div class="form-group row">
													<label class="col-md-2 control-label" for="room">Room</label>  
													<div class="col-md-4">
														<input id="ba_room" name="ba_room" type="text" class="form-control input-sm uppercase">
													</div>

													<label class="col-md-2 control-label" for="ward">Ward</label>  
													<div class="col-md-4">
														<input id="ba_ward" name="ba_ward" type="text" class="form-control input-sm uppercase">
													</div>
												</div>

												<div class="form-group row">
													<label class="col-md-2 control-label" for="bednum">Bed No</label>  
													<div class="col-md-4">
														<input id="ba_bednum" name="ba_bednum" type="text" class="form-control input-sm uppercase">
													</div>
												
													<label class="col-md-2 control-label" for="bedtype">Bed Type</label>  
													<div class="col-md-4">
														<input id="b_bedtype" name="b_bedtype" type="text" class="form-control input-sm uppercase">
													</div>
													<span class="help-block"></span>
												</div>													
												<!-- Class form group closed -->
													
											</div>  
											<!-- Panel body close -->
										</div>
									</div>

									<div class='col-md-6'>
										<div class="panel panel-info">
											<div class="panel-heading text-center">TRANSFER TO BED</div>
											<div class="panel-body">
												<div class="form-group row">
													<label class="col-md-2 control-label" for="date">Date</label>  
													<div class="col-md-4">
														<input id="trf_aedate" name="trf_aedate" type="date" class="form-control input-sm uppercase">
													</div>

													<label class="col-md-2 control-label" for="time">Time</label>  
													<div class="col-md-4">
														<input id="trf_aetime" name="trf_aetime" type="time" class="form-control input-sm uppercase">
													</div>
												</div>

												<div class="form-group row">
													<label class="col-md-2 control-label" for="trf_bednum">Bed No</label>  
													<div class="col-md-4">
														<div class='input-group'>
															<input id="trf_bednum" name="trf_bednum" type="text" class="form-control input-sm uppercase">
															<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
														</div>
														<span class="help-block"></span>
													</div>
												

													<label class="col-md-2 control-label" for="room">Room</label>  
													<div class="col-md-4">
														<input id="trf_room" name="trf_room" type="text" class="form-control input-sm uppercase">
													</div>
												</div>


												<div class="form-group row">
													<label class="col-md-2 control-label" for="ward">Ward</label>  
													<div class="col-md-4">
														<input id="trf_ward" name="trf_ward" type="text" class="form-control input-sm uppercase">
													</div>
												
													<label class="col-md-2 control-label" for="bedtype">Bed Type</label>  
													<div class="col-md-4">
														<input id="trf_bedtype" name="trf_bedtype" type="text" class="form-control input-sm uppercase">
													</div>
												</div>


												<div class="form-group row">
													<label class="col-md-2 control-label" for="occup">Bed Status</label>  
													<div class="col-md-4">
														<select id="trf_astatus" name="trf_astatus" class="form-control input-sm uppercase">
														  <option value="Transfer">Transfer</option>
														  <option value="Reserved">Reserved</option>
														</select>
													</div>

													<label class="col-md-2 control-label" for="trf_lodger">Lodger</label>  
													<div class="col-md-4">
														<div class='input-group'>
															<input id="trf_lodger" name="trf_lodger" type="text" class="form-control input-sm uppercase">
															<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
														</div>
														<span class="help-block"></span>
													</div>
												</div>

												</div>
												<!-- Class form group closed -->
												
											</div>  
											<!-- Panel body close -->
										</div>
									</div>
								</div>

							</form>

							</div>
					</div>
				</div>	
			</div>
		</div>

		<div class='row'>
		
			@include('hisdb.nursing.nursing')
			@include('hisdb.wardpanel.wardpanel')
			@include('hisdb.dietorder.dietorder')
			@include('hisdb.dischgsummary.dischgsummary')
			
		</div>
    </div>
	<!-- ***************End Search + table ********************* -->

@endsection


@section('scripts')

	<script src="js/setup/bedmanagement/bedmanagement.js"></script>
	<script src="js/hisdb/nursing/nursing.js"></script>
	<script src="js/hisdb/wardpanel/wardpanel.js"></script>
	<script src="js/hisdb/dietorder/dietorder.js"></script>
	<script src="js/hisdb/dischgsummary/dischgsummary.js"></script>
	
@endsection
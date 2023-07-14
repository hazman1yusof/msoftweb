@extends('layouts.main')

@section('title', 'Bed Management Setup')

@section('body')

	<!--***************************** Search + table ******************-->
	<input type="hidden" name="curr_user" id="curr_user" value="{{Auth::user()->username}}">
	<div class='row'>
		<div class="header" id="SearchFormHeader" >
			<form id="searchForm" class="formclass" style='width:99%;'>
				<fieldset>
					<input id="csrf_token" name="csrf_token" type="hidden" value="{{ csrf_token() }}">
					<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

					<div class='col-md-12' style="padding:0 0 15px 0;">
						<div class="form-group"> 
							<div class="col-md-2">
								<label class="control-label" for="Scol">Search By : </label>  
								<select id='Scol' name='Scol' class="form-control input-sm"></select>
							</div>

							<div class="col-md-4" style="padding-right: 0px">
								<label class="control-label"></label>  
								<input  name="Stext" type="search" seltext='true' placeholder="Search here ..." class="form-control text-uppercase">
							
								<div  id="show_bedtype" style="display:none">
									<div class='input-group'>
										<input id="s_bedtype" name="s_bedtype" type="text" maxlength="12" class="form-control input-sm">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
								</div>
								
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
					</div>
				</fieldset> 
			</form>
		</div>

        <div class="panel panel-default" style="margin-right:10px">
		    <div class="panel-heading">Bed Management Setup Header</div>
		    <div class="panel-body" style="padding-bottom: 0px;">
		    	<div class='col-md-12' style="padding:0px">
            		<table id="jqGrid" class="table table-striped"></table>
            		<div id="jqGridPager"></div>
        		</div>
		    </div>

		    <div class="row">
			  	<table class="tableinfo"><tr>
					<td align="center"><b> TOTAL BED: <span id="stat_totalbed"></b></span>
				  	<td align="center">
				  		<img src="img/bedonly.png" height="10" width="14"></img><b> VACANT: <span id="stat_vacant"></b></span>
				  	</td>
					<td align="center">
						<i class="fa fa-bed" aria-hidden="true"></i><b> OCCUPIED: <span id="stat_occupied"></b></span>
					</td>
					<td align="center">
						<i class="fa fa-female" aria-hidden="true"></i><b> HOUSEKEEPING: <span id="stat_housekeeping"></b></span>
					</td>
					<td align="center">
						<i class="fa fa-gavel" aria-hidden="true"></i><b> MAINTENANCE: <span id="stat_maintenance"></b></span>
					</td>
					<td align="center">
						<i class="fa fa-bullhorn" aria-hidden="true"></i><b> ISOLATED: <span id="stat_isolated"></b></span>
					</td>
					<td align="center">
						<i class="fa fa-ban" aria-hidden="true"></i><b> RESERVE: <span id="stat_reserve"></b></span>
					</td>
					<td align="center">
						<i class="fa fa-times" aria-hidden="true"></i><b> DEACTIVE: <span id="stat_deactive"></b></span>
					</td>
			  	</tr></table>
		    </div>
		</div>
		
	</div>

	<div class='row'>
		<div class="panel panel-default" style="margin-right:10px;position: relative;">
			<div class="panel-heading clearfix collapsed position" id="toggle_chart" style="position: sticky;top: 0px;z-index: 3;">
				<br>
				<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 10px" data-toggle="collapse" data-target="#panel_chart"></i>
				<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 10px" data-toggle="collapse" data-target="#panel_chart"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 90px; top: 15px;">
					<h5><b>Bed Chart</b></h5>
				</div>	
			</div>
			<div id="panel_chart" class="panel-collapse collapse">
        	<div class="panel-body" style="padding-bottom: 0px;">
        		<div class="row">
        			<div class="chart_sel">
        				<label><input type="radio" name="chart_sel" value="ward" checked> Ward</label>
						<label><input type="radio" name="chart_sel" value="bedtype"> Bedtype</label>

						<label style="margin-left: 20px;">Chart Type</label>
						<select name="chart_type" class="form-control input-sm" style="display: inline-block;width: auto;">
						  <option value="bar">Bar Chart</option>
						  <option value="line">Line Chart</option>
						  <option value="pie">Pie Chart</option>
						</select>
        			</div>
        		</div>
        		<canvas id="myChart" style="display: block; width: 732px; height: 266px;" class="chartjs-render-monitor"></canvas>
        	</div>
        	</div>
		</div>
	</div>

	<div class='row'>
		<div class="panel panel-default" style="margin-right:10px;position: relative;" id="jqGrid_trf_c">
			<div class="panel-heading clearfix collapsed position" id="toggle_trf" style="position: sticky;top: 0px;z-index: 3;">
				<b>Name: <span id="name_show"></span></b><br>
					Bed No: <span id="bednum_show"></span>
					<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 10px" data-toggle="collapse" data-target="#jqGrid_trf_panel"></i>
					<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 10px" data-toggle="collapse" data-target="#jqGrid_trf_panel"></i>
					<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 280px; top: 15px;">
						<h5>Bed Allocation Detail</h5>
					</div>

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
												<label class="col-md-1 control-label" for="date">Date</label>  
												<div class="col-md-5">
													<input id="ba_asdate" name="ba_asdate" type="date" class="form-control input-sm uppercase">
												</div>
											
												<label class="col-md-1 control-label" for="time">Time</label>  
												<div class="col-md-5">
													<input id="ba_astime" name="ba_astime" type="time" class="form-control input-sm uppercase">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-md-1 control-label" for="room">Room</label>  
												<div class="col-md-5">
													<input id="ba_room" name="ba_room" type="text" class="form-control input-sm uppercase">
												</div>

												<label class="col-md-1 control-label" for="ward">Ward</label>  
												<div class="col-md-5">
													<input id="ba_ward" name="ba_ward" type="text" class="form-control input-sm uppercase">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-md-1 control-label" for="bednum">Bed No</label>  
												<div class="col-md-5">
													<input id="ba_bednum" name="ba_bednum" type="text" class="form-control input-sm uppercase">
												</div>
											
												<label class="col-md-1 control-label" for="bedtype">Bed Type</label>  
												<div class="col-md-5">
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
									<div class="panel panel-info" id="transferto_div">
										<div class="panel-heading text-center">TRANSFER TO BED</div>
										<div class="panel-body">
											<div class="form-group row">
												<label class="col-md-1 control-label" for="date">Date</label>  
												<div class="col-md-5">
													<input id="trf_aedate" name="trf_aedate" type="date" class="form-control input-sm uppercase">
												</div>

												<label class="col-md-1 control-label" for="time">Time</label>  
												<div class="col-md-5">
													<input id="trf_aetime" name="trf_aetime" type="time" class="form-control input-sm uppercase">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-md-1 control-label" for="trf_bednum">Bed No</label>  
												<div class="col-md-5">
													<div class='input-group'>
														<input id="trf_bednum" name="trf_bednum" type="text" class="form-control input-sm uppercase">
														<a class='input-group-addon btn btn-primary' ><span class='fa fa-ellipsis-h' ></span></a>
													</div>
													<span class="help-block"></span>
												</div>
											

												<label class="col-md-1 control-label" for="room">Room</label>  
												<div class="col-md-5">
													<input id="trf_room" name="trf_room" type="text" class="form-control input-sm uppercase">
												</div>
											</div>


											<div class="form-group row">
												<label class="col-md-1 control-label" for="ward">Ward</label>  
												<div class="col-md-5">
													<input id="trf_ward" name="trf_ward" type="text" class="form-control input-sm uppercase">
												</div>
											
												<label class="col-md-1 control-label" for="bedtype">Bed Type</label>  
												<div class="col-md-5">
													<input id="trf_bedtype" name="trf_bedtype" type="text" class="form-control input-sm uppercase">
												</div>
											</div>


											<div class="form-group row">
												<label class="col-md-1 control-label" for="occup">Bed Status</label>  
												<div class="col-md-5">
													<select id="trf_astatus" name="trf_astatus" class="form-control input-sm uppercase">
														<option value="Transfer">Transfer</option>
														<option value="Reserved">Reserved</option>
													</select>
												</div>

												<label class="col-md-1 control-label" for="trf_lodger">Lodger</label>  
												<div class="col-md-5">
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

	<div id="dialogReserveBedForm" title="Note for reserved bed" >
		<div class='panel panel-info'>
			<div class="panel-body" style="">
				<form class='form-horizontal' style=''>
					<small>Notes</small> 
					<input type="hidden" name="" id="reservebedHide">
					<textarea class="form-control input-sm text-uppercase" name="reservebedNote" rows="5" ></textarea>
				</form>
			</div>
		</div>
	</div>

	<!-- mdl_accomodation -->
	<div id="mdl_accomodation" class="modal fade" role="dialog" title="title" data-backdrop="static" style="display: none; z-index: 110;background-color: rgba(0, 0, 0, 0.3);">
		<div class="modal-dialog smallmodal">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Select Accomodation</h4>
				</div>
				<div class="modal-body">
					<div class="table-responsive table-no-bordered content">
						<table id="accomodation_table" class="table-hover cell-border" width="100%">
							<thead>
								<tr>
									<th>desc_bt</th>
									<th>Bed Number</th>
									<th>Ward</th>
									<th>Room</th>
									<th>Status</th>
									<th>Bed Type</th>
									<th>Ward</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn-u btn-u-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>
	<!-- ***************End Search + table ********************* -->

@endsection


@section('scripts')
	<link rel="stylesheet" href="css/bedmanagement.css">
	<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
	<script src="js/setup/bedmanagement/bedmanagement.js"></script>
@endsection
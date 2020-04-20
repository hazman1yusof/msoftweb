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

i.fa {
	cursor: pointer;
	float: right;
	<!--  margin-right: 5px; -->
}

.clearfix {
	overflow: auto;
}

input.uppercase {
	text-transform: uppercase;
}

row.error td { background-color: red; }

@endsection

@section('body')

	<!--***************************** Search + table ******************-->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%; position:relative'>
			<fieldset>
				<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
						<div class="col-md-2">
							<label class="control-label" for="Scol">Search By : </label>  
					  		<select id='Scol' name='Scol' class="form-control input-sm"></select>
		              	</div>

					  	<div class="col-md-5">
					  		<label class="control-label"></label>  
							<input  name="Stext" type="search" seltext='true' placeholder="Search here ..." class="form-control text-uppercase">
						</div>

						<div class="col-md-2" style="">
							<p><i class="fa fa-ban" aria-hidden="true"></i> VACANT: <span id="stat_vacant"></span></p>
					  	</div>
						<div class="col-md-2" style="">
							<p><i class="fa fa-bed" aria-hidden="true"></i> OCCUPIED: <span id="stat_occupied"></span></p>
					  	</div>
						<div class="col-md-2" style="">
							<p><i class="fa fa-female" aria-hidden="true"></i> HOUSEKEEPING: <span id="stat_housekeeping"></span></p>
					  	</div>
						<div class="col-md-2" style="">
							<p><i class="fa fa-gavel" aria-hidden="true"></i> MAINTENANCE: <span id="stat_maintenance"></span></p>
					  	</div>
						<div class="col-md-2  col-md-offset-7" style="">
							<p><i class="fa fa-bullhorn" aria-hidden="true"></i> ISOLATED: <span id="stat_isolated"></span></p>
					  	</div>
		            </div>
				</div>
			</fieldset> 
		</form>

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
			<div class="panel panel-default" style="position: relative;" id="jqGrid3_c">
				<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGrid3_panel">
					<b>Name: <span id="name_show"></span></b><br>
						Bed No: <span id="bednum_show"></span>
						<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
						<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
						<br>
							<button type="button" class="btn btn-info">Transfer</button>
						</br>
						<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 20px;">
							<h5>Bed Occupancy Detail</h5>
						</div>		
				</div>

				<!--***-->

				<div id="jqGridBODtl_panel" class="panel-collapse collapse">
					<div class="panel-body">
						<div class='col-md-12' style="padding:0 0 15px 0">

							<form class='form-horizontal' style='width:99%' id='formBODtl'>

								<div class='col-md-5'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">Current Record</div>
										<div class="panel-body">

											<input id="mrn_edit_BO" name="mrn_edit_BO" type="hidden">

											<div class="form-group">
												<label class="col-md-2 control-label" for="aetime">End Time</label>  
												<div class="col-md-4">
													<input id="aetime" name="aetime" type="time" class="form-control input-sm uppercase">
												</div>
											</div>

											<div class="form-group">
												<label class="col-md-2 control-label" for="aedate">End Date</label>  
												<div class="col-md-5">
													<input id="aedate" name="aedate" type="date" class="form-control input-sm uppercase" rdonly>
												</div>
											</div>

											<div class="form-group">
												<label class="col-md-2 control-label" for="admreason" >Room</label>  
												<div class="col-md-10">
													<textarea id="room" name="room" type="text" class="form-control input-sm uppercase" rows="4"></textarea>
												</div>
											</div>

											<div class="form-group">
												<label class="col-md-2 control-label" for="bednum">Bed Num</label>  
												<div class="col-md-10">
													<textarea id="bednum" name="bednum" type="text" class="form-control input-sm uppercase" rows="4"></textarea>
												</div>
											</div>

											<div class="panel panel-info">
												<div class="panel-heading text-center">Transfer To Bed</div>
												<div class="panel-body">

												<div class="form-group">
													<label class="col-md-2 control-label" for="astime">Assign Time</label>  
														<div class="col-md-4">
															<input id="astime" name="astime" type="time" class="form-control input-sm uppercase">
														</div>
													</div>

													<div class="form-group">
														<label class="col-md-2 control-label" for="asdate">Assign Date</label>  
														<div class="col-md-5">
															<input id="asdate" name="asdate" type="date" class="form-control input-sm uppercase" rdonly>
														</div>
													</div>

													<div class="form-group">
														<label class="col-md-2 control-label" for="admreason" >Room</label>  
														<div class="col-md-10">
															<textarea id="room" name="room" type="text" class="form-control input-sm uppercase" rows="4"></textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-md-2 control-label" for="bednum">Bed Num</label>  
														<div class="col-md-10">
															<textarea id="bednum" name="bednum" type="text" class="form-control input-sm uppercase" rows="4"></textarea>
														</div>
													</div>

												</div>
											</div>

										</div>
									</div>
								</div>

								<div class='col-md-7'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">Condition on Admission</div>
										<div class="panel-body">

											<div class='col-md-8'>
												<div class="panel panel-info">
													<div class="panel-heading text-center">VITAL SIGN</div>
													<div class="panel-body">

														<div class="form-group">
															<label class="col-md-4 control-label" for="vs_temperature">Temperature</label>  
															<div class="col-md-7 input-group">
																<input id="vs_temperature" name="vs_temperature" type="text" class="form-control input-sm uppercase">
																<span class="input-group-addon">°C</span>
															</div>
														</div>

														<div class="form-group">
															<label class="col-md-4 control-label" for="vs_pulse">Pulse</label>  
															<div class="col-md-7 input-group">
																<input id="vs_pulse" name="vs_pulse" type="text" class="form-control input-sm uppercase">
																<span class="input-group-addon">/min</span>
															</div>
														</div>

														<div class="form-group">
															<label class="col-md-4 control-label" for="vs_respiration">Respiration</label>  
															<div class="col-md-7 input-group">
																<input id="vs_respiration" name="vs_respiration" type="text" class="form-control input-sm uppercase">
																<span class="input-group-addon">/min</span>
															</div>
														</div>

														<div class="form-group">
															<label class="col-md-4 control-label" for="vs_bloodpressure">Blood Pressure</label>
															<div class="col-md-7 input-group">
																<input id="vs_bloodpressure_sys1" name="vs_bloodpressure_sys1" type="text" class="form-control input-sm uppercase" style="width: 49px">
																<label class="col-md-1 control-label">/</label> 
																<input id="vs_bloodpressure_dias2" name="vs_bloodpressure_dias2" type="text" class="form-control input-sm uppercase" style="width: 49px">
																<span class="input-group-addon">/mmHg</span>
															</div>
														</div>

														<div class="form-group">
															<label class="col-md-4 control-label" for="vs_height">Height</label>  
															<div class="col-md-7 input-group">
																<input id="vs_height" name="vs_height" type="text" class="form-control input-sm uppercase">
																<span class="input-group-addon">cm</span>
															</div>
														</div>

														<div class="form-group">
															<label class="col-md-4 control-label" for="vs_weight">Weight</label>  
															<div class="col-md-7 input-group">
																<input id="vs_weight" name="vs_weight" type="text" class="form-control input-sm uppercase">
																<span class="input-group-addon">kg</span>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-md-4 control-label" for="vs_gxt">GXT</label>  
															<div class="col-md-7 input-group">
																<input id="vs_gxt" name="vs_gxt" type="text" class="form-control input-sm uppercase">
																<span class="input-group-addon">mmOL</span>
															</div>
														</div>

														<div class="form-group">
															<label class="col-md-4 control-label" for="vs_painscore">Pain Score</label>  
															<div class="col-md-7 input-group">
																<input id="vs_painscore" name="vs_painscore" type="text" class="form-control input-sm uppercase">
																<span class="input-group-addon">/10</span>
															</div>
														</div>

													</div>
												</div>
											</div>

											<div class='col-md-4'>
												<div class="panel panel-info">
													<div class="panel-heading text-center">MODE OF ADMISSION</div>
													<div class="panel-body">

														<div class="form-check" style="margin-left: 20px">
															<input class="form-check-input" type="checkbox" name="moa_walkin" id="moa_walkin" value="1">
															<label class="form-check-label" for="moa_walkin">Walk In</label>
														</div>

														<div class="form-check" style="margin-left: 20px">
															<input class="form-check-input" type="checkbox" name="moa_wheelchair" id="moa_wheelchair" value="1">
															<label class="form-check-label" for="moa_wheelchair">Wheel Chair</label>
														</div>

														<div class="form-check" style="margin-left: 20px">
															<input class="form-check-input" type="checkbox" name="moa_trolley" id="moa_trolley" value="1">
															<label class="form-check-label" for="moa_trolley">Trolley</label>
														</div>

														<div class="form-check" style="margin-left: 20px">
															<input class="form-check-input" type="checkbox" name="moa_others" id="moa_others" value="1">
															<label class="form-check-label" for="moa_others">Others</label>
														</div>

													</div>
												</div>
											</div>

											<div class='col-md-12'>
												<div class="panel panel-info">
													<div class="panel-heading text-center">LEVEL OF CONSCIOUSNESS</div>
													<div class="panel-body">

														<div class="form-group">
															<div class="form-check form-check-inline checkbox-inline" style="margin-left: 100px">
																<input class="form-check-input" type="checkbox" id="loc_conscious" name="loc_conscious" value="1">
																<label class="form-check-label" for="loc_conscious">Conscious</label>
															</div>

															<div class="form-check form-check-inline checkbox-inline">
																<input class="form-check-input" type="checkbox" id="loc_semiconscious" name="loc_semiconscious" value="1">
																<label class="form-check-label" for="loc_semiconscious">SemiConscious</label>
															</div>

															<div class="form-check form-check-inline checkbox-inline">
																<input class="form-check-input" type="checkbox" id="loc_unconscious" name="loc_unconscious" value="1">
																<label class="form-check-label" for="loc_unconscious">UnConscious</label>
															</div>
														</div>

													</div>
												</div>
											</div>

											<div class='col-md-6'>
												<div class="panel panel-info">
													<div class="panel-heading text-center">MENTAL STATUS</div>
													<div class="panel-body" style="height: 170px">

														<div class="form-check" style="margin-left: 60px">
															<input class="form-check-input" type="checkbox" name="ms_orientated" id="ms_orientated" value="1">
															<label class="form-check-label" for="ms_orientated">Orientated</label>
														</div>

														<div class="form-check" style="margin-left: 60px">
															<input class="form-check-input" type="checkbox" name="ms_confused" id="ms_confused" value="1">
															<label class="form-check-label" for="ms_confused">Confused</label>
														</div>

														<div class="form-check" style="margin-left: 60px">
															<input class="form-check-input" type="checkbox" name="ms_restless" id="ms_restless" value="1">
															<label class="form-check-label" for="ms_restless">Restless</label>
														</div>

														<div class="form-check" style="margin-left: 60px">
															<input class="form-check-input" type="checkbox" name="ms_aggressive" id="ms_aggressive" value="1">
															<label class="form-check-label" for="ms_aggressive">Aggressive</label>
														</div>

													</div>
												</div>
											</div>

											<div class='col-md-6'>
												<div class="panel panel-info">
													<div class="panel-heading text-center">EMOTIONAL STATUS</div>
													<div class="panel-body" style="height: 170px">

														<div class="form-check" style="margin-left: 60px">
															<input class="form-check-input" type="checkbox" name="es_calm" id="es_calm" value="1">
															<label class="form-check-label" for="es_calm">Calm</label>
														</div>

														<div class="form-check" style="margin-left: 60px">
															<input class="form-check-input" type="checkbox" name="es_anxious" id="es_anxious" value="1">
															<label class="form-check-label" for="es_anxious">Anxious</label>
														</div>

														<div class="form-check" style="margin-left: 60px">
															<input class="form-check-input" type="checkbox" name="es_distress" id="es_distress" value="1">
															<label class="form-check-label" for="es_distress">Distress</label>
														</div>

														<div class="form-check" style="margin-left: 60px">
															<input class="form-check-input" type="checkbox" name="es_depressed" id="es_depressed" value="1">
															<label class="form-check-label" for="es_depressed">Depressed</label>
														</div>

														<div class="form-check" style="margin-left: 60px">
															<input class="form-check-input" type="checkbox" name="es_irritable" id="es_irritable" value="1">
															<label class="form-check-label" for="es_irritable">Irritable</label>
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
					
				<!-- *** -->

				<div id="jqGrid3_panel" class="panel-collapse collapse">
					<div class="panel-body">
						<form id='formdata3' class='form-vertical' style='width:99%'>
							<div class='col-md-12' style="padding:0 0 15px 0">
								<table id="jqGrid3" class="table table-striped"></table>
								<div id="jqGridPager3"></div>

								<!-- form -->

								<div class='col-md-12' style="padding:15px 0 15px 0">
									<div class='col-md-6'>
										<div class="panel panel-info">
											<div class="panel-heading text-center">LEVEL OF CONSCIOUSNESS</div>
											<div class="panel-body">

												<div class="form-group">
													<div class="form-check form-check-inline checkbox-inline" style="margin-left: 100px">
														<input class="form-check-input" type="checkbox" id="loc_conscious" name="loc_conscious" value="1">
														<label class="form-check-label" for="loc_conscious">Conscious</label>
													</div>

													<label class="col-md-2 control-label" for="admwardtime">Time</label>  
														<div class="col-md-4">
															<input id="admwardtime" name="admwardtime" type="time" class="form-control input-sm uppercase">
														</div>

													<div class="form-check form-check-inline checkbox-inline">
														<input class="form-check-input" type="checkbox" id="loc_semiconscious" name="loc_semiconscious" value="1">
														<label class="form-check-label" for="loc_semiconscious">SemiConscious</label>
													</div>

													<div class="form-check form-check-inline checkbox-inline">
														<input class="form-check-input" type="checkbox" id="loc_unconscious" name="loc_unconscious" value="1">
														<label class="form-check-label" for="loc_unconscious">UnConscious</label>
													</div>
												</div>

											</div>
										</div>
									</div>
									<div class='col-md-6'>
										<div class="panel panel-info">
											<div class="panel-heading text-center">CONSCIOUSNESS</div>
											<div class="panel-body">

												<div class="form-group">
													<div class="form-check form-check-inline checkbox-inline" style="margin-left: 100px">
														<input class="form-check-input" type="checkbox" id="loc_conscious" name="loc_conscious" value="1">
														<label class="form-check-label" for="loc_conscious">Conscious</label>
													</div>

													<div class="form-check form-check-inline checkbox-inline">
														<input class="form-check-input" type="checkbox" id="loc_semiconscious" name="loc_semiconscious" value="1">
														<label class="form-check-label" for="loc_semiconscious">SemiConscious</label>
													</div>

													<div class="form-check form-check-inline checkbox-inline">
														<input class="form-check-input" type="checkbox" id="loc_unconscious" name="loc_unconscious" value="1">
														<label class="form-check-label" for="loc_unconscious">UnConscious</label>
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
    </div>
	<!-- ***************End Search + table ********************* -->

@endsection


@section('scripts')

	<script src="js/setup/bedmanagement/bedmanagement.js"></script>
	
@endsection
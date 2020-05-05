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
							<p><img src="img/bedonly.png" height="10" width="14"></img> VACANT: <span id="stat_vacant"></span></p>
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
											<div class="panel-heading text-center">CURRENT BED</div>
											<div class="panel-body">

												<div class="form-group">
													<label class="col-md-2 control-label" for="date">Date</label>  
													<div class="col-md-4">
														<input id="admwardtime" name="date" type="date" class="form-control input-sm uppercase">
													</div>
												
													<label class="col-md-2 control-label" for="time">Time</label>  
													<div class="col-md-4">
														<input id="admwardtime" name="time" type="time" class="form-control input-sm uppercase">
													</div>

													<label class="col-md-2 control-label" for="room">Room</label>  
													<div class="col-md-4">
														<input id="room" name="room" type="text" class="form-control input-sm uppercase">
													</div>

													<!-- <label class="col-md-2 control-label" for="room">Room</label>  
													<div class="col-md-4">
														<div class='input-group'>
															<input id="room" name="room" type="text" class="form-control input-sm uppercase">
															<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
														</div>
														<span class="help-block"></span>
													</div> -->

													<label class="col-md-2 control-label" for="ward">Ward</label>  
													<div class="col-md-4">
														<input id="ward" name="ward" type="text" class="form-control input-sm uppercase">
													</div>

													<label class="col-md-2 control-label" for="bednum">Bed No</label>  
													<div class="col-md-4">
														<input id="bednum" name="bednum" type="text" class="form-control input-sm uppercase">
													</div>
												
													<label class="col-md-2 control-label" for="bedtype">Bed Type</label>  
													<div class="col-md-4">
														<input id="bedtype" name="bedtype" type="text" class="form-control input-sm uppercase">
													</div>													
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

												<div class="form-group">

													<label class="col-md-2 control-label" for="date">Date</label>  
													<div class="col-md-4">
														<input id="admwardtime" name="date" type="date" class="form-control input-sm uppercase">
													</div>
												
													<label class="col-md-2 control-label" for="time">Time</label>  
													<div class="col-md-4">
														<input id="admwardtime" name="time" type="time" class="form-control input-sm uppercase">
													</div>

													<label class="col-md-2 control-label" for="room">Room</label>  
													<div class="col-md-4">
														<input id="room" name="room" type="text" class="form-control input-sm uppercase">
													</div>

													<label class="col-md-2 control-label" for="ward">Ward</label>  
													<div class="col-md-4">
														<input id="ward" name="ward" type="text" class="form-control input-sm uppercase">
													</div>

													<label class="col-md-2 control-label" for="bednum">Bed No</label>  
													<div class="col-md-4">
														<input id="bednum" name="bednum" type="text" class="form-control input-sm uppercase">
													</div>
												
													<label class="col-md-2 control-label" for="bedtype">Bed Type</label>  
													<div class="col-md-4">
														<input id="bedtype" name="bedtype" type="text" class="form-control input-sm uppercase">
													</div>

													<label class="col-md-2 control-label" for="occup">Bed Status</label>  
													<div class="col-md-4">
														<input id="occup" name="occup" type="text" class="form-control input-sm uppercase">
													</div>
												</div>
												<!-- Class form group closed -->
												
											</div>  
											<!-- Panel body close -->
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
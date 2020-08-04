
<div class="panel panel-default" style="position: relative;" id="jqGridDoctorNote_c">
	<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
		id="btn_grp_edit_doctorNote"
		style="position: absolute;
				padding: 0 0 0 0;
				right: 40px;
				top: 15px;" 

	>
		<button type="button" class="btn btn-default" id="new_doctorNote">
			<span class="fa fa-plus-square-o"></span> New
		</button>
		<button type="button" class="btn btn-default" id="edit_doctorNote">
			<span class="fa fa-edit fa-lg"></span> Edit
		</button>
		<button type="button" class="btn btn-default" data-oper='add' id="save_doctorNote">
			<span class="fa fa-save fa-lg"></span> Save
		</button>
		<button type="button" class="btn btn-default" id="cancel_doctorNote">
			<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
		</button>
	</div>
	<div class="panel-heading clearfix collapsed position" id="toggle_doctorNote" data-toggle="collapse" data-target="#jqGridDoctorNote_panel">
		<b>Name: <span id="name_show_doctorNote"></span></b><br>
		MRN: <span id="mrn_show_doctorNote"></span>

		<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 310px; top: 15px;">
			<h5>Doctor Note</h5>
		</div>				
	</div>
	<div id="jqGridDoctorNote_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<!-- <table id="jqGridTriageInfo" class="table table-striped"></table>
				<div id="jqGridPagerTriageInfo"></div> -->

				<form class='form-horizontal' style='width:99%' id='formDoctorNote'>

					<div class='col-md-12'>
						<div class="panel panel-info">
							<div class="panel-heading text-center">DOCTOR NOTE</div>
							<div class="panel-body">

								<div class='col-md-7'>
									<div class="panel panel-info">
										<div class="panel-body">

											<input id="mrn_doctorNote" name="mrn_doctorNote" type="hidden">
											<input id="episno_doctorNote" name="episno_doctorNote" type="hidden">

											

											

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
	
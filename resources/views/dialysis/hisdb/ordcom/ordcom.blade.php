<div class="panel panel-default" style="position: relative;">
	<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
		id="btn_grp_edit_ordcom"
		style="position: absolute;
				padding: 0 0 0 0;
				right: 40px;
				top: 25px;" 

	>
		<!-- <button type="button" class="btn btn-default" id="new_ordcom">
			<span class="fa fa-plus-square-o"></span> Order
		</button> -->
	</div>
	<div class="panel-heading clearfix collapsed position" id="toggle_ordcom" data-toggle="collapse" data-target="#jqGrid_ordcom_panel">
		<b>NAME: <span id="name_show_ordcom"></span></b><br>
		MRN: <span id="mrn_show_ordcom"></span>
		SEX: <span id="sex_show_ordcom"></span>
		DOB: <span id="dob_show_ordcom"></span>
		AGE: <span id="age_show_ordcom"></span>
		RACE: <span id="race_show_ordcom"></span>
		RELIGION: <span id="religion_show_ordcom"></span><br>
		OCCUPATION: <span id="occupation_show_ordcom"></span>
		CITIZENSHIP: <span id="citizenship_show_ordcom"></span>
		AREA: <span id="area_show_ordcom"></span>
		
		<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 25px;">
			<h5>Order Entry</h5>
		</div>				
	</div>
	<div id="jqGrid_ordcom_panel" class="panel-collapse collapse">
		<div class="panel-body" id="jqGrid_ordcom_c">
			<div class='col-md-12' style="padding:0 0 15px 0" >
		    	<input type="hidden" id="mrn_ordcom" name="mrn_ordcom">
		    	<input type="hidden" id="episno_ordcom" name="episno_ordcom">
				<input id="ordcom_deptcode_hide" type="hidden" value="{{Auth::user()->deptcode}}">
				<input id="ordcom_priceview_hide" type="hidden" value="{{Auth::user()->priceview}}">
				<table id="jqGrid_ordcom" class="table table-striped"></table>
				<div id="jqGridPager_ordcom"></div>

			</div>
		</div>
	</div>	
</div>

<!-- mdl_accomodation -->
<div id="mdl_ordcom_chgcode" class="modal fade" role="dialog" title="title" style="display: none; z-index: 110;background-color: rgba(0, 0, 0, 0.3);">
	<div class="modal-dialog smallmodal">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Select Chargecode</h4>
			</div>
			<div class="modal-body">
				<div class="table-responsive table-no-bordered content">
					<table id="chgcode_table" class="table-hover cell-border" width="100%">
						<thead>
							<tr>
								<th>Code</th>
								<th>Description</th>
								<th>chggroup</th>
								<th>Group</th>
								<th>Group Desc.</th>
								<th>Amount</th>
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
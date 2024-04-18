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
		BILL TYPE: <span id="billtype_show_ordcom"></span>
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
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 100px; top: 5px;">
			<h5 style="margin-bottom: 5px;">Order Entry</h5>
			<span><b>Total Amount : </b></span>
			<span id="cyclebill_totmat"></span>
		</div>				
	</div>
	<div id="jqGrid_ordcom_panel" class="panel-collapse collapse">
		<div class="panel-body paneldiv" id="jqGrid_ordcom_c">
	    	<input type="hidden" id="mrn_ordcom" name="mrn_ordcom">
	    	<input type="hidden" id="episno_ordcom" name="episno_ordcom">
			<input type="hidden" id="ordcom_deptcode_hide" value="{{Auth::user()->deptcode}}">
			<input type="hidden" id="ordcom_priceview_hide" value="{{Auth::user()->priceview}}">
			<input type="hidden" id="ordcomtt_phar" value="{{$ordcomtt_phar ?? ''}}">
			<input type="hidden" id="ordcomtt_disp" value="{{$ordcomtt_disp ?? ''}}">
			<input type="hidden" id="ordcomtt_rad" value="{{$ordcomtt_rad ?? ''}}">
			<input type="hidden" id="ordcomtt_lab" value="{{$ordcomtt_lab ?? ''}}">
			<input type="hidden" id="ordcomtt_phys" value="{{$ordcomtt_phys ?? ''}}">
			<input type="hidden" id="ordcomtt_rehab" value="{{$ordcomtt_rehab ?? ''}}">
			<input type="hidden" id="ordcomtt_diet" value="{{$ordcomtt_diet ?? ''}}">
			<input type="hidden" id="ordcomtt_dfee" value="{{$ordcomtt_dfee ?? ''}}">
			<input type="hidden" id="ordcomtt_oth" value="{{$ordcomtt_oth ?? ''}}">
			<input type="hidden" id="ordcomtt_pkg" value="{{$ordcomtt_pkg ?? ''}}">

			<ul class="nav nav-tabs">
				<div id="ordcom_div_cyclebill">
					<span><b>Cycle Bill : </b></span>
					<a class="cyclebill" id="cyclebill_dtl" href="" target="_blank">Detail</a>
					<a class="cyclebill" id="cyclebill_summ" href="" target="_blank">Summary</a>
					<br>
				</div>

				<li class="active"><a data-toggle="tab" id="ordcom_navtab_phar" href="#tab-phar" aria-expanded="true" data-ord_chgtype='PHAR'>Pharmacy</a></li>
				<li><a data-toggle="tab" id="ordcom_navtab_disp" href="#tab-disp" data-ord_chgtype='DISP'>Disposable</a></li>
				<li><a data-toggle="tab" id="ordcom_navtab_lab" href="#tab-lab" data-ord_chgtype='LAB'>Laboratory</a></li>
				<li><a data-toggle="tab" id="ordcom_navtab_rad" href="#tab-rad" data-ord_chgtype='RAD'>Radiology</a></li>
				<li><a data-toggle="tab" id="ordcom_navtab_dfee" href="#tab-dfee" data-ord_chgtype='DFEE'>Doctor Fees</a></li>
				<li><a data-toggle="tab" id="ordcom_navtab_phys" href="#tab-phys" data-ord_chgtype='PHYS'>Physioteraphy</a></li>
				<li><a data-toggle="tab" id="ordcom_navtab_rehab" href="#tab-rehab" data-ord_chgtype='REHAB'>Rehabilitation</a></li>
				<li><a data-toggle="tab" id="ordcom_navtab_diet" href="#tab-diet" data-ord_chgtype='DIET'>Dietician</a></li>
				<li><a data-toggle="tab" id="ordcom_navtab_pkg" href="#tab-pkg" data-ord_chgtype='PKG'>Package</a></li>
				<li><a data-toggle="tab" id="ordcom_navtab_oth" href="#tab-oth" data-ord_chgtype='OTH'>Others</a></li>
			</ul>
			<div class="tab-content" style="padding: 10px 5px;">
			  <div id="tab-phar" class="active in tab-pane fade">
				<div id="qtyonhand_text_phar" style="color: cornflowerblue;"></div>
				<div id="fail_msg_phar" class="fail_msg"></div>
				<div class='col-md-12' style="padding:0px" autocomplete="off">
					<table id="jqGrid_phar" class="table table-striped"></table>
					<div id="jqGrid_phar_pager"></div>
				</div>
			  </div>
			  <div id="tab-disp" class="tab-pane fade">
				<div id="qtyonhand_text_disp" style="color: cornflowerblue;"></div>
				<div id="fail_msg_disp" class="fail_msg"></div>
				<div class='col-md-12' style="padding:0px" autocomplete="off">
					<table id="jqGrid_disp" class="table table-striped"></table>
					<div id="jqGrid_disp_pager"></div>
				</div>
			  </div>
			  <div id="tab-lab" class="tab-pane fade">
				<div id="fail_msg_lab" class="fail_msg"></div>
				<div class='col-md-12' style="padding:0px" autocomplete="off">
					<table id="jqGrid_lab" class="table table-striped"></table>
					<div id="jqGrid_lab_pager"></div>
				</div>
			  </div>
			  <div id="tab-rad" class="tab-pane fade">
				<div class='col-md-12' style="padding:0px" autocomplete="off">
					<table id="jqGrid_rad" class="table table-striped"></table>
					<div id="jqGrid_rad_pager"></div>
				</div>
			  </div>
			  <div id="tab-dfee" class="tab-pane fade">
				<div class='col-md-12' style="padding:0px" autocomplete="off">
					<table id="jqGrid_dfee" class="table table-striped"></table>
					<div id="jqGrid_dfee_pager"></div>
				</div>
			  </div>
			  <div id="tab-phys" class="tab-pane fade">
				<div class='col-md-12' style="padding:0px" autocomplete="off">
					<table id="jqGrid_phys" class="table table-striped"></table>
					<div id="jqGrid_phys_pager"></div>
				</div>
			  </div>
			  <div id="tab-rehab" class="tab-pane fade">
				<div class='col-md-12' style="padding:0px" autocomplete="off">
					<table id="jqGrid_rehab" class="table table-striped"></table>
					<div id="jqGrid_rehab_pager"></div>
				</div>
			  </div>
			  <div id="tab-diet" class="tab-pane fade">
				<div class='col-md-12' style="padding:0px" autocomplete="off">
					<table id="jqGrid_diet" class="table table-striped"></table>
					<div id="jqGrid_diet_pager"></div>
				</div>
			  </div>
			  <div id="tab-oth" class="tab-pane fade">
				<div class='col-md-12' style="padding:0px" autocomplete="off">
					<table id="jqGrid_oth" class="table table-striped"></table>
					<div id="jqGrid_oth_pager"></div>
				</div>
			  </div>
			  <div id="tab-pkg" class="tab-pane fade">
				<div class='col-md-12' style="padding:0px" autocomplete="off">
					<table id="jqGrid_pkg" class="table table-striped"></table>
					<div id="jqGrid_pkg_pager"></div>
				</div>
			  </div>

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
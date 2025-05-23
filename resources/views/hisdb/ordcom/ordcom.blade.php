<div class="panel panel-default" style="position: relative;margin-bottom: 0px;">
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
	<div class="panel-heading clearfix collapsed position" id="toggle_ordcom" @if($phase != '2') data-toggle="collapse" data-target="#jqGrid_ordcom_panel" @endif>
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
		
		@if($phase == '2')
			<input type="hidden" id="ordcom_phase" value="2">
            <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGrid_ordcom_panel"></i>
            <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGrid_ordcom_panel" ></i >
		@else
			<input type="hidden" id="ordcom_phase" value="0">
			<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
			<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
		@endif
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 100px; top: 5px;">
			<h5 style="margin-bottom: 5px;">Order Entry</h5>
			<span><b>Total Amount : </b></span>
			<span id="cyclebill_totmat"></span>
		</div>				
	</div>
	<div id="jqGrid_ordcom_panel" class="panel-collapse collapse">
		<div class="panel-body paneldiv" id="jqGrid_ordcom_c" style="padding-top: 0px;">
	    	<input type="hidden" id="mrn_ordcom" name="mrn_ordcom">
	    	<input type="hidden" id="episno_ordcom" name="episno_ordcom">
			<input type="hidden" id="ordcom_deptcode_hide" value="{{Auth::user()->deptcode}}">
			<input type="hidden" id="ordcom_priceview_hide" value="{{Auth::user()->priceview}}">
			<input type="hidden" id="ordcomtt_phar" value="{{$ordcomtt_phar ?? '25'}}">
			<input type="hidden" id="ordcomtt_disp" value="{{$ordcomtt_disp ?? '65'}}">
			<input type="hidden" id="ordcomtt_rad" value="{{$ordcomtt_rad ?? '30'}}">
			<input type="hidden" id="ordcomtt_lab" value="{{$ordcomtt_lab ?? '35'}}">
			<input type="hidden" id="ordcomtt_phys" value="{{$ordcomtt_phys ?? '38'}}">
			<input type="hidden" id="ordcomtt_rehab" value="{{$ordcomtt_rehab ?? '39'}}">
			<input type="hidden" id="ordcomtt_diet" value="{{$ordcomtt_diet ?? '92'}}">
			<input type="hidden" id="ordcomtt_dfee" value="{{$ordcomtt_dfee ?? 'DF'}}">
			<input type="hidden" id="ordcomtt_oth" value="{{$ordcomtt_oth ?? '25,38,39,65,30,35,92,DF,PK'}}">
			<input type="hidden" id="ordcomtt_pkg" value="{{$ordcomtt_pkg ?? 'PK'}}">

			@if($phase == '2')
	        <input name="phardept_dflt" id="phardept_dflt" type="hidden" value="{{$phardept_dflt ?? 'PHAR'}}">
	        <input name="dispdept_dflt" id="dispdept_dflt" type="hidden" value="{{$userdeptcode ?? 'PHAR'}}">
	        <input name="labdept_dflt" id="labdept_dflt" type="hidden" value="{{$labdept_dflt ?? 'LAB'}}">
	        <input name="raddept_dflt" id="raddept_dflt" type="hidden" value="{{$raddept_dflt ?? 'RAD'}}">
	        <input name="physdept_dflt" id="physdept_dflt" type="hidden" value="{{$physdept_dflt ?? 'PHY'}}">
	        <input name="rehabdept_dflt" id="rehabdept_dflt" type="hidden" value="{{$rehabsdept_dflt ?? 'REHAB'}}">
	        <input name="dfeedept_dflt" id="dfeedept_dflt" type="hidden" value="{{$dfeedept_dflt ?? 'PHAR'}}">
	        <input name="dietdept_dflt" id="dietdept_dflt" type="hidden" value="{{$dietdept_dflt ?? 'DIET'}}">
	        <input name="pkgdept_dflt" id="pkgdept_dflt" type="hidden" value="{{$pkgdept_dflt ?? 'PHAR'}}">
	        <input name="othdept_dflt" id="othdept_dflt" type="hidden" value="{{$othdept_dflt ?? 'PHAR'}}">
	        @endif

			<!-- coverage -->
            <div class="panel panel-default" style="position: relative;" id="div_coverage">
                <div class="panel-heading clearfix collapsed" id="toggle_tabcoverage" data-toggle="collapse" data-target="#tabcoverage" style="position: sticky;top: -16px;z-index: 3;min-height: 35px;">

                <!-- <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i > -->
                <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
                    <h5><strong>COVERAGE</strong></h5>
                </div> 
                </div>

                <div id="tabcoverage" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="col-xs-12">
                        <div id="jqGrid_epno_coverage_c">
                            <div class='col-md-12' style="padding:0 0 15px 0">
                                <table id="jqGrid_epno_coverage" class="table table-striped"></table>
                                <div id="jqGridPager_epno_coverage"></div>
                            </div>
                        </div>
                    </div>
                    <form class="col-xs-12" id="form_epno_coverage" autocomplete="off" style="padding-top: 15px;">
                        <div class="form-group">
                            <div class="col-md-5" style="position: absolute;right: 18px;top: -10px;">
                                <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                id="btn_grp_edit_epno_coverage">
                                    <button type="button" class="btn btn-default" id="add_epno_coverage">
                                        <span class="fa fa-plus-square-o fa-lg"></span> Add
                                    </button>
                                    <button type="button" class="btn btn-default" id="edit_epno_coverage">
                                        <span class="fa fa-edit fa-lg"></span> Edit
                                    </button>
                                    <button type="button" class="btn btn-default" id="save_epno_coverage">
                                        <span class="fa fa-save fa-lg"></span> Save
                                    </button>
                                    <button type="button" class="btn btn-default" id="cancel_epno_coverage" >
                                        <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
                                    </button>
                                </div>
                            </div>
                        </div>

                        <input id="idno_epno_coverage" name="idno" type="hidden">

                        <div class="form-group">
                            <div class="col-xs-7">
                                <small for="name">Name</small>
                                <input id="name_epno_coverage" name="name" type="text" class="form-control" data-validation="required" readonly>
                            </div>
                            <div class="col-xs-5">
	                            <div class="col-xs-4" >
	                                <small for="lineno_epno_coverage">Payer No</small>
	                                <input id="lineno_epno_coverage" name="lineno" type="text" class="form-control" readonly>
	                            </div>
	                            <div class="col-xs-3" style="padding:0px 5px">
	                                <small for="">MRN</small>
	                                <input id="mrn_epno_coverage" name="mrn" type="text" class="form-control" data-validation="required" readonly>
	                            </div>
	                            <div class="col-xs-3" style="padding:0px 5px">
	                                <small for="">Episode</small>
	                                <input id="episno_epno_coverage" name="episno" type="text" class="form-control" data-validation="required" readonly>
	                            </div>
	                            <div class="col-xs-2" style="padding:0px 5px">
	                                <small for="">Type</small>
	                                <input id="epistycode_epno_coverage" name="epistycode" type="text" class="form-control" data-validation="required" readonly>
	                            </div>
	                        </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-4">
                                <small for="payercode_epno_coverage">Payer Code</small>
                                <div class='input-group'>
                                    <input id="payercode_epno_coverage" name="payercode" type="text" class="form-control uppercase" data-validation="required" >
                                    <a class='input-group-addon btn btn-info'><span class='fa fa-ellipsis-h'></span></a>
                                </div>
                            </div>

                            <div class="col-xs-8">
                                <small for="billtype_epno_coverage">&nbsp;</small>
                                <input id="payercode_desc_epno_coverage" name="payercode_desc" type="text" class="form-control" readonly>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-xs-2">
                                <small for="allgroup_epno_coverage">All Group</small>
                                <select name="allgroup" id="allgroup_epno_coverage" class="form-control">
                                  <option value="1">Yes</option>
                                  <option value="0">No</option>
                                </select>
                            </div>
                            <div class="col-xs-2">
                                <small for="allgroup_epno_coverage">&nbsp;</small>
                                <button type="button" id="except_epno_coverage" class="btn btn-default" style="display: block;">Coverage</button>
                            </div>

                            <div class="col-xs-4">
                                <small for="pyrlmtamt_epno_coverage">Limit Amount</small>
                                <input id="pyrlmtamt_epno_coverage" name="pyrlmtamt" type="text" class="form-control">
                            </div>

                            <div class="col-xs-4">
                                <small for="pay_type_epno_coverage">Fin Class</small>
                                <input id="pay_type_epno_coverage" name="pay_type" type="text" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-6">
                                <small for="refno_epno_coverage">Reference No</small>
                                <div class='input-group'>
                                    <input id="refno_epno_coverage" name="refno" type="text" class="form-control uppercase">
                                    <a class='input-group-addon btn btn-info' id="refno_epno_coverage_btn"><span class='fa fa-ellipsis-h'></span></a>
                                </div>
                                <span class="help-block"></span>
                            </div>
                            <div class="col-xs-6">
                                <small for="ourrefno_epno_coverage">Our Reference</small>
                                <input id="ourrefno_epno_coverage" name="ourrefno" type="text" class="form-control" readonly>
                            </div>
                        </div>

                        <!-- <div class="form-group">
                            <div class="col-xs-4">
                                <small for="computerid_epno_coverage">Computer ID</small>
                                <input id="computerid_epno_coverage" name="computerid" type="text" class="form-control" readonly>
                            </div>
                            <div class="col-xs-4">
                                <small for="lastuser_epno_coverage">Last User</small>
                                <input id="lastuser_epno_coverage" name="lastuser" type="text" class="form-control" readonly>
                            </div>
                            <div class="col-xs-4">
                                <small for="lastupdate_epno_coverage">Last Update</small>
                                <input id="lastupdate_epno_coverage" name="lastupdate" type="text" class="form-control" readonly>
                            </div>
                        </div> -->

                    </form>
                </div>
                </div>
            </div>

			<ul class="nav nav-tabs" style="position: relative;">
				<div id="ordcom_div_cyclebill">
					<span><b>Cycle Bill : </b></span>
					<a class="cyclebill" id="cyclebill_dtl" href="" target="_blank">Detail</a> | 
					<a class="cyclebill" id="cyclebill_summ" href="" target="_blank">Summary</a>
					<br>
				</div>
				<div id="ordcom_div_label">
					<span><b>Label : </b></span>
					<a class="cyclebill" id="phar_label_link" href="" target="_blank">Pharmacy</a>
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
				<div id="qtyonhand_text_phar" style="color: #337ab7;"></div>
				<div id="fail_msg_phar" class="fail_msg"></div>
				<div class='col-md-12' style="padding:0px" autocomplete="off">
					<table id="jqGrid_phar" class="table table-striped"></table>
					<div id="jqGrid_phar_pager"></div>
				</div>
			  </div>
			  <div id="tab-disp" class="tab-pane fade">
				<div id="qtyonhand_text_disp" style="color: #337ab7;"></div>
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
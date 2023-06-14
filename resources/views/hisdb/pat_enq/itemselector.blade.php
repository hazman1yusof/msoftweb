	<!-- popup item selector -->
	<div id="mdl_item_selector" class="modal fade" role="dialog" title="title" data-backdrop="static">
		<div class="modal-dialog smallmodal">
		
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="mdl_item_selector">&times;</button>
					<h4 class="modal-title">Item Selector</h4>
				</div>
				<div class="modal-body">
					<div class="table-responsive table-no-bordered content">
						<table id="tbl_item_select" class="table-hover cell-border" width="100%">
							<thead>
								<tr>
									<th>Code</th>
									<th>Description</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn-u btn-u-default" data-dismiss="mdl_item_selector">Cancel</button>
				</div>
			</div>
			
		</div>
	</div>
	<!-- end popup item selector -->

<div id="mdl_reference" class="modal fade" role="dialog" data-backdrop="static" style="z-index: 121 !important;overflow-y: hidden;">
    <div class="modal-dialog mediummodal">
        <form class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header label-info">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <p align="center"><b>GL REFERENCE</b></p>
                </div>
                <div class="modal-body">
                    <table id="tbl_epis_reference" class="table table-striped" width="100%">
                        <thead>
                        <tr>
                            <th >Payer</th>
                            <th >Name</th>
                            <th >GL Type</th>
                            <th >Staff ID</th>
                            <th >Ref No</th>
                            <th >Date From </th>
                            <th >Date To</th>
                            <th >Our Ref No</th>
                            <th >childno</th>
                            <th >episno</th>
                            <th >medcase</th>
                            <th >mrn</th>
                            <th >relatecode</th>
                            <th >remark</th>
                            <th >startdate</th>
                            <th >enddate</th>
                        </tr>
                        </thead>

                    </table>
                </div>
                <div class="modal-footer">
                    <button id="btn_epis_view_gl" type="button" class="btn btn-info" disabled>VIEW GL</button>
                    <button id="btn_epis_detail_gl" type="button" class="btn btn-info" disabled>GL DETAIL</button>
                    <button id="btn_epis_new_gl" type="button" class="btn btn-info" >NEW GL</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="mdl_new_gl" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" >
    <div class="modal-dialog mediummodal">
        <form class="form-horizontal" id="glform">
            <div class="modal-content">
                <div class="modal-header label-info" style="height: 32px;padding:8px 30px;">
                    <b style="float: left;" id="newgl-textmrn"></b>
                    <b style="float: left;padding-left: 10px;" id="newgl-textname"></b>
                    <b style="float: right;">GURANTEE LETTER ENTRY</b>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="mycss">
                            <legend>Corporate Info:</legend>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <small for="newgl-staffid">STAFF ID</small>
                                        <input class="form-control form-mandatory" id="newgl-staffid" name="newgl-staffid" placeholder="" type="text" required>
                                    </div>
                                    <div class="col-md-7">
                                        <small for="newgl-corpcomp">Name</small>
                                        <input class="form-control form-mandatory" id="newgl-name" name="newgl-name" placeholder="" type="text" required>
                                    </div>
                                    <div class="col-md-1">
                                        <small for="newgl-childno">CHILD NO</small>
                                        <input name="newgl-childno" id="newgl-childno" class="form-control" placeholder="" type="text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <small for="newgl-corpcomp">Company Code</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-mandatory" name="txt_newgl_corpcomp" id="txt_newgl_corpcomp" required>
                                            <input type="hidden" name="hid_newgl_corpcomp" id="hid_newgl_corpcomp" value="" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_newgl_corpcomp" data-toggle="modal" onclick_xguna="pop_item_select('newgl_corpcomp');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <small for="newgl-occupcode">OCCUPATION</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="txt_newgl_occupcode" id="txt_newgl_occupcode">
                                            <input type="hidden" name="hid_newgl_occupcode" id="hid_newgl_occupcode" value="" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_newgl_occupcode" data-toggle="modal" onclick_xguna="pop_item_select('newgl_occupcode');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <small for="newgl-relatecode">RELATIONSHIP</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="txt_newgl_relatecode" id="txt_newgl_relatecode">
                                            <input type="hidden" name="hid_newgl_relatecode" id="hid_newgl_relatecode" value="" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_newgl_relatecode" data-toggle="modal" onclick_xguna="pop_item_select('newgl_relatecode');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                                <ul class="nav nav-tabs" id="select_gl_tab" style="margin-bottom: 10px;">
                                    <li class="active"><a href="#" data-toggle="tab" id="newgl_default_tab">Multi Volume</a></li>
                                    <li><a href="Multi Date" data-toggle="tab">Multi Date</a></li>
                                    <li><a href="Open" data-toggle="tab">Open</a></li>
                                    <li><a href="Single Use" data-toggle="tab">Single Use</a></li>
                                    <!-- <li><a href="Limit Amount" data-toggle="tab">Limit Amount</a></li>
                                    <li><a href="Monthly Amount" data-toggle="tab">Monthly Amount</a></li> -->
                                </ul>
                                <input type="hidden" id="newgl-gltype" name="newgl-gltype">
                                <div class="form-group">
                                    <div class="col-md-4" id="newgl-effdate_div">
                                        <small for="newgl-effdate">EFFECTIVE DATE:</small>
                                        <input class="form-control form-mandatory" id="newgl-effdate" name="newgl-effdate" placeholder="" type="date" required>
                                    </div>
                                    <div class="col-md-4" id="newgl-expdate_div">
                                        <small for="newgl-expdate">EXPIRY DATE:</small>
                                        <input class="form-control form-mandatory" id="newgl-expdate" name="newgl-expdate" placeholder="" type="Date" required>
                                    </div>
                                    <div class="col-md-4" id="newgl-visitno_div">
                                        <small for="newgl-visitno">VISIT NO</small>
                                        <input class="form-control" id="newgl-visitno" name="newgl-visitno" placeholder="" type="text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <small for="newgl-case">CASE</small>
                                        <input class="form-control form-mandatory" id="newgl-case" name="newgl-case" placeholder="" type="text" required>
                                    </div>
                                    <div class="col-md-6">
                                        <small for="newgl-refno">REFERENCE NO</small>
                                        <input class="form-control form-mandatory" id="newgl-refno" name="newgl-refno" placeholder="" type="text" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <small for="newgl-ourrefno">OUR REFERENCE</small>
                                        <input class="form-control" id="newgl-ourrefno" name="newgl-ourrefno" placeholder="" type="text" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <small for="newgl-remark">REMARK</small>
                                        <input class="form-control" id="newgl-remark" name="newgl-remark" placeholder="" type="text">
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button id="btnglclose" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="btnglsave" type="button" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>


    </div>
</div>
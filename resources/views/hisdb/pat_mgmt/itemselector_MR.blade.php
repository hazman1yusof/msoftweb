	<!-- popup item selector -->
	<div id="mdl_item_selector_MR" class="modal fade" role="dialog" title="title" style="display: none; z-index: 120;background-color: rgba(0, 0, 0, 0.3);">
		<div class="modal-dialog" style="width: 50%; height: 50%; margin: auto;">
		
			<!-- Modal content-->
			<div class="modal-content" style="border: 3px solid darkblue;margin-top:30px">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="txt_item_selector_MR"></h4>
				</div>
				<div class="modal-body">
					<div class="table-responsive table-no-bordered content">
						<table id="tbl_item_select_MR" class="table-hover cell-border" width="100%">
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
					<button type="button" class="btn-u btn-u-default" data-dismiss="modal">Cancel</button>
					<!-- <button id="add_new_adm" type="button" class="btn-u btn-u-default" style="display: none;" data-modal-target="#mdl_add_new_adm_MR">Add new</button> -->
				</div>
			</div>
			
		</div>
	</div>
	<!-- end popup item selector -->


	<!-- new adm save -->
	<div id="mdl_add_new_adm_MR" class="modal fade" role="dialog" title="title" data-backdrop="static" style="display: none; z-index: 130;background-color: rgba(0, 0, 0, 0.3);">
		<div class="modal-dialog smallmodal">
		
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add New Administration Source</h4>
				</div>
				<div class="modal-body col-md-12">
					<form id="adm_form_MR">
					<div class="col-md-3 col-md-offset-1">
	                    <small for="adm_code_MR">Code</small>
	                    <input type="text" class="form-control form-mandatory" id="adm_code_MR" name="adm_code" aria-required="true" disabled>
	                </div>
	                <div class="col-md-7">
	                    <small for="adm_desc_MR">Description</small>
	                    <input type="text" class="form-control form-mandatory uppercase" id="adm_desc_MR" name="adm_desc" required >
	                </div>

                    <div class="col-md-10 col-md-offset-1">
                        <small for="input-title">Address</small>
	                    <input class="form-control form-mandatory" id="adm_addr1_MR" name="adm_addr1" placeholder="" type="text" required><br />
	                    <input class="form-control" id="adm_addr2_MR" name="adm_addr2" placeholder="" type="text"><br />
	                    <input class="form-control" id="adm_addr3_MR" name="adm_addr3" placeholder="" type="text"><br />
	                    <input class="form-control" id="adm_addr4_MR" name="adm_addr4" placeholder="" type="text"><br />
                	</div>
                    <div class="col-md-5 col-md-offset-1">
                        <small for="input-title">TEL</small>
                        <input class="form-control" id="adm_telno_MR" name="adm_telno" placeholder="" type="text">
                    </div>
                    <div class="col-md-5">
                        <small for="input-title">E-mail</small>
                        <input class="form-control" id="adm_email_MR" name="adm_email" placeholder="" type="text">
                    </div>
                    <div class="col-md-5 col-md-offset-1">
                        <small for="adm_type_MR">Source Type</small>
                        <select id="adm_type_MR" name="adm_type" class="form-control form-mandatory" required>
                            <option value="GP">GP</option>
                            <option value="DOCTOR">DOCTOR</option>
                            <option value="HOSPITAL">HOSPITAL</option>
                        </select>
                    </div>
                    </form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn-u btn-u-default" id="adm_save_MR">Save</button>
					<button type="button" class="btn-u btn-u-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
			
		</div>
	</div>

	<!-- new occupation save -->
	<div id="mdl_add_new_occ_MR" class="modal fade" role="dialog" title="title" data-backdrop="static" style="display: none; z-index: 130;background-color: rgba(0, 0, 0, 0.3);">
		<div class="modal-dialog smallmodal">
		
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add New Occupation</h4>
				</div>
				<div class="modal-body col-md-12">
					<form id="new_occup_form_MR">
					<div class="col-md-3 col-md-offset-1">
	                    <small for="occup_code_MR">Code</small>
	                    <input type="text" class="form-control form-mandatory" id="occup_code_MR" name="occup_code" aria-required="true" disabled>
	                </div>
	                <div class="col-md-7">
	                    <small for="occup_desc_MR">Description</small>
	                    <input type="text" class="form-control form-mandatory uppercase" id="occup_desc_MR" name="occup_desc" required >
	                </div>
                    </form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn-u btn-u-default" id="new_occup_save_MR">Save</button>
					<button type="button" class="btn-u btn-u-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
			
		</div>
	</div>

	<!-- new title save -->
	<div id="mdl_add_new_title_MR" class="modal fade" role="dialog" title="title" data-backdrop="static" style="display: none; z-index: 130;background-color: rgba(0, 0, 0, 0.3);">
		<div class="modal-dialog smallmodal">
		
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add New Title</h4>
				</div>
				<div class="modal-body col-md-12">
					<form id="new_title_form_MR">
					<div class="col-md-3 col-md-offset-1">
	                    <small for="title_code_MR">Code</small>
	                    <input type="text" class="form-control form-mandatory" id="title_code_MR" name="title_code" aria-required="true">
	                </div>
	                <div class="col-md-7">
	                    <small for="title_desc_MR">Description</small>
	                    <input type="text" class="form-control form-mandatory uppercase" id="title_desc_MR" name="title_desc" required >
	                </div>
                    </form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn-u btn-u-default" id="new_title_save_MR">Save</button>
					<button type="button" class="btn-u btn-u-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
			
		</div>
	</div>

	<!-- new relationship save -->
	<div id="mdl_add_new_relationship_MR" class="modal fade" role="dialog" title="title" data-backdrop="static" style="display: none; z-index: 130;background-color: rgba(0, 0, 0, 0.3);">
		<div class="modal-dialog smallmodal">
		
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add New Relationship</h4>
				</div>
				<div class="modal-body col-md-12">
					<form id="new_relationship_form_MR">
					<div class="col-md-3 col-md-offset-1">
	                    <small for="relationship_code_MR">Code</small>
	                    <input type="text" class="form-control form-mandatory" id="relationship_code_MR" name="relationship_code" aria-required="true">
	                </div>
	                <div class="col-md-7">
	                    <small for="relationship_desc_MR">Description</small>
	                    <input type="text" class="form-control form-mandatory uppercase" id="relationship_desc_MR" name="relationship_desc" required >
	                </div>
                    </form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn-u btn-u-default" id="new_relationship_save_MR">Save</button>
					<button type="button" class="btn-u btn-u-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>

	<!-- new areacode save -->
	<div id="mdl_add_new_areacode_MR" class="modal fade" role="dialog" title="title" data-backdrop="static" style="display: none; z-index: 130;background-color: rgba(0, 0, 0, 0.3);">
		<div class="modal-dialog smallmodal">
		
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add New AreaCode</h4>
				</div>
				<div class="modal-body col-md-12">
					<form id="new_areacode_form_MR">
					<div class="col-md-3 col-md-offset-1">
	                    <small for="areacode_code_MR">Code</small>
	                    <input type="text" class="form-control form-mandatory" id="areacode_code_MR" name="areacode_code" aria-required="true" disabled>
	                </div>
	                <div class="col-md-7">
	                    <small for="areacode_desc_MR">Description</small>
	                    <input type="text" class="form-control form-mandatory uppercase" id="areacode_desc_MR" name="areacode_desc" required >
	                </div>
                    </form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn-u btn-u-default" id="new_areacode_save_MR">Save</button>
					<button type="button" class="btn-u btn-u-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
			
		</div>
	</div>

	

	<!-- mdl_accomodation -->
	<div id="mdl_accomodation_MR" class="modal fade" role="dialog" title="title" data-backdrop="static" >
		<div class="modal-dialog smallmodal">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header label-info">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Select Accomadation</h4>
				</div>
				<div class="modal-body">
					<div class="table-responsive table-no-bordered content">
						<table id="accomodation_table_MR" class="table-hover cell-border" width="100%">
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
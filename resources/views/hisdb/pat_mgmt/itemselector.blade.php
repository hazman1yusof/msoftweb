	<!-- popup item selector -->
	<div id="mdl_item_selector" class="modal fade" role="dialog" title="title" data-backdrop="static">
		<div class="modal-dialog">
		
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
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
					<button type="button" class="btn-u btn-u-default" data-dismiss="modal">Cancel</button>
					<button id="add_new_adm" type="button" class="btn-u btn-u-default" style="display: none;">Add new</button>
				</div>
			</div>
			
		</div>
	</div>
	<!-- end popup item selector -->

	<div id="mdl_add_new_adm" class="modal fade" role="dialog" title="title" data-backdrop="static">
		<div class="modal-dialog">
		
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add New Administration Source</h4>
				</div>
				<div class="modal-body col-md-12">
					<form id="adm_form">
					<div class="col-md-3 col-md-offset-1">
	                    <small for="adm_code">Code</small>
	                    <input type="text" class="form-control form-mandatory" id="adm_code" name="adm_code" aria-required="true" required>
	                </div>
	                <div class="col-md-7">
	                    <small for="adm_desc">Description</small>
	                    <input type="text" class="form-control form-mandatory" id="adm_desc" name="adm_desc" required>
	                </div>

                    <div class="col-md-10 col-md-offset-1">
                        <small for="input-title">Address</small>
	                    <input class="form-control form-mandatory" id="adm_addr1" name="adm_addr1" placeholder="" type="text" required><br />
	                    <input class="form-control" id="adm_addr2" name="adm_addr2" placeholder="" type="text"><br />
	                    <input class="form-control" id="adm_addr3" name="adm_addr3" placeholder="" type="text"><br />
	                    <input class="form-control" id="adm_addr4" name="adm_addr4" placeholder="" type="text"><br />
                	</div>
                    <div class="col-md-5 col-md-offset-1">
                        <small for="input-title">TEL</small>
                        <input class="form-control" id="adm_telno" name="adm_telno" placeholder="" type="text">
                    </div>
                    <div class="col-md-5">
                        <small for="input-title">E-mail</small>
                        <input class="form-control" id="adm_email" name="adm_email" placeholder="" type="text">
                    </div>
                    </form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn-u btn-u-default" id="adm_save">Save</button>
					<button type="button" class="btn-u btn-u-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
			
		</div>
	</div>
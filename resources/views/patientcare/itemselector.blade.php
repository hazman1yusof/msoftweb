

<div class="ui modal scrolling" id="mdl_item_selector">
  <i class="close icon" style="position: inherit;color: black;"></i>
  <div class="content">
		<table id="tbl_item_select" class="ui celled table" width="100%">
			<thead>
				<tr>
					<th>Code</th>
					<th>Description</th>
				</tr>
			</thead>
		</table>
  </div>
</div>

<div id="mdl_glet" class="modal fade" role="dialog" data-backdrop="static" style="z-index: 112 !important;">
    <div class="modal-dialog mediummodal" style="margin-top: 1% !important;">
        <form class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header label-info">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <p align="center"><b>GL Detail</b></p>
                </div>
                <div class="modal-body">
                    <div class="row" id="glet_row">
                        <div class="row" style="background: aliceblue; 
                            margin-bottom: 10px; 
                            border-radius: 5px; 
                            border: solid 1px #b2dbff; 
                            padding-top: 5px;">
                            <div class='col-md-12' style="padding: 0;">
                                <div class="col-md-3">
                                    <small for="input-title">MRN</small>
                                    <input class="form-control" id="glet_mrn" placeholder="" type="text" readonly>
                                </div>
                                <div class="col-md-6">
                                    <small for="input-title">Name</small>
                                    <input class="form-control" id="glet_name" placeholder="" type="text" readonly>
                                </div>
                                <div class="col-md-3">
                                    <small for="input-title">Episode</small>
                                    <input class="form-control" id="glet_episno" placeholder="" type="text" readonly>
                                </div>
                            </div>
                            <div class='col-md-12' style="padding: 0;">
                                <div class="col-md-3">
                                    <small for="input-title">Payer Code</small>
                                    <input class="form-control" id="glet_payercode" placeholder="" type="text" readonly>
                                </div>
                                <div class="col-md-6">
                                    <small for="input-title">Payer</small>
                                    <input class="form-control" id="glet_payercode_desc" placeholder="" type="text" readonly>
                                </div>
                                <div class="col-md-3">
                                    <small for="input-title">Total Limit</small>
                                    <input class="form-control" id="glet_totlimit" placeholder="" type="text" readonly>
                                </div>
                            </div>
                            <div class='col-md-12' style="padding: 0 0 15px 0;">
                                <div class="col-md-3">
                                    <small for="input-title">All Group</small>
                                    <input class="form-control" id="glet_allgroup" placeholder="" type="text" readonly>
                                </div>
                                <div class="col-md-9">
                                    <small for="input-title">Ref No.</small>
                                    <input class="form-control" id="glet_refno" placeholder="" type="text" readonly>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-12' style="padding: 0 0 15px 0;">
                            <table id="jqGrid_gletdept" class="table table-striped"></table>
                            <div id="jqGridPager_gletdept"></div>
                        </div>
                        <div class='col-md-12' style="padding: 0 0 15px 0;">
                            <table id="jqGrid_gletitem" class="table table-striped"></table>
                            <div id="jqGridPager_gletitem"></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
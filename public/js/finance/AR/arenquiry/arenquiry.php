<?php 
	include_once('../../../../header.php'); 
?>
<body>


<style>
</style>
	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
		<div class='col-md-12'>
			<div class="form-group">
			  <div class="col-md-2 col-md-offset-2">
			  	<select id='filter' name='filter' class="form-control input-sm">
			  		<option value="debtorcode">Debtor Code</option>
			  		<option value="payercode">Payer Code</option>
			  		<option value="mrn">MRN</option>
			  		<option value="billno">Bill No</option>
			  		<option value="recptno">Doc No</option>
			  		<option value="auditno">AuditNo</option>
			  		<option value="debtortype">Debtor Trantype</option>
			  		<option value="billdebtor">Payer Trantype</option>
			  		<option value="reference">Reference</option>
			  	</select>
              </div>
			  <div class="col-md-5">
					<input id="searchText" name="searchText" type="text" class="form-control input-sm" autocomplete="off"/>
			  </div>
			  <div class="col-md-1">
				<button type="button" id="search" class="btn btn-primary btn-sm" style="position:absolute;top:0px">
					<i class="fa fa-binoculars" aria-hidden="true"></i> Search
				</button>
              </div>
             </div>
		</div>
    	<div class='col-md-12' style="padding:0 0 15px 0">
			<hr>
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
        </div>

		<div class='row' id='ispbin'>
			<ul class="nav nav-tabs">
			    <li class="active"><a data-toggle="tab" href="#reference">Reference</a></li>
			    <li><a data-toggle="tab" href="#summary">Summary</a></li>
			    <li><a data-toggle="tab" href="#consultant">Consultant Fees</a></li>
			    <li><a data-toggle="tab" href="#comment">Comment</a></li>
			  </ul>

			  <div class="tab-content" style="padding:15px 0 15px 0">
			    <div id="reference" class="tab-pane fade in active">
			      <form class="form-horizontal" id="referenceForm">
			      	<div class="col-md-8" style="border-right: solid 1px #dddddd;">
						<div class="form-group">
							<label class="col-md-2 control-label" for="debtorname">Debtor Name</label>  
							<div class="col-md-10">
								<input id="debtorname" name="debtorname" type="text" class="form-control input-sm" readonly="readonly">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="payername">Patient Name</label>  
							<div class="col-md-10">
								<input id="payername" name="payername" type="text" class="form-control input-sm" readonly="readonly">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="reference">Reference</label>  
							<div class="col-md-10">
								<input id="reference" name="reference" type="text" class="form-control input-sm" readonly="readonly">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="remark">Remark</label>  
							<div class="col-md-10">
								<input id="remark" name="remark" type="text" class="form-control input-sm" readonly="readonly">
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="col-md-4 control-label" for="status">Status</label>
							<div class="col-md-8">  
								<input id="status" name="status" type="text" class="form-control input-sm" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label" for="lastuser">Last User</label>  
							<div class="col-md-8">  
								<input id="lastuser" name="lastuser" type="text" class="form-control input-sm" readonly="readonly">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label" for="lastupdate">Last Update</label>
							<div class="col-md-8">    
								<input id="lastupdate" name="lastupdate" type="text" class="form-control input-sm" readonly="readonly">
							</div>
						</div>
					</div>
		          </form>
			    </div>
			    <div id="summary" class="tab-pane fade" >
				    <div id="billsumGrid_c" class="col-md-10">
			            <table id="billsumGrid" class="table table-striped"></table>
            			<div id="billsumGridPager"></div>
					</div>
			    </div>
			    <div id="consultant" class="tab-pane fade">
			      <h3>Menu 2</h3>
			      <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
			    </div>
			    <div id="comment" class="tab-pane fade">
			      <textarea id="comment" class="form-control input-sm" style="min-height: 150px" readonly></textarea>
			    </div>
			  </div>
		</div>

		<div class='row' id='notpbin'>
			<ul class="nav nav-tabs">
			    <li class="active"><a data-toggle="tab" href="#document2">Document</a></li>
			    <li><a data-toggle="tab" href="#comment2">Comment</a></li>
			  </ul>

			  <div class="tab-content" style="padding:15px 0 15px 0">
			    <div id="document2" class="tab-pane fade in active">
			      <form class="form-horizontal col-md-6" id="referenceFormIN">
						<div class="form-group">
							<label class="col-md-2 control-label" for="debtorname">Debtor Name</label>  
							<div class="col-md-10">
								<input id="debtorname" name="debtorname" type="text" class="form-control input-sm" readonly="readonly">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="payername">Patient Name</label>  
							<div class="col-md-10">
								<input id="payername" name="payername" type="text" class="form-control input-sm" readonly="readonly">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="reference">Reference</label>  
							<div class="col-md-10">
								<input id="reference" name="reference" type="text" class="form-control input-sm" readonly="readonly">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="remark">Remark</label>  
							<div class="col-md-10">
								<input id="remark" name="remark" type="text" class="form-control input-sm" readonly="readonly">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-4">
								<label class="control-label" for="status">Status</label>  
								<input id="status" name="status" type="text" class="form-control input-sm" readonly="readonly">
							</div>

							<div class="col-md-4">
								<label class="control-label" for="lastuser">Last User</label>  
								<input id="lastuser" name="lastuser" type="text" class="form-control input-sm" readonly="readonly">
							</div>

							<div class="col-md-4">
								<label class="control-label" for="lastupdate">Last Update</label>  
								<input id="lastupdate" name="lastupdate" type="text" class="form-control input-sm" readonly="readonly">
							</div>
						</div>
		          </form>
		          <div class="col-md-6" style="border-left: solid 1px #dddddd;">
			        <table class="table table-hover" id='tableTran'>
						<thead>
							<tr>
								<th>Account</th>
								<th>Description</th>
								<th>Amount</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				  </div>
			    </div>
			    <div id="comment2" class="tab-pane fade">
			      <textarea id="comment2" class="form-control input-sm" style="min-height: 150px" readonly></textarea>
			    </div>
			  </div>
		</div>
    </div>
	<!-------------------------------- End Search + table ------------------>

	<div id="dialogForm" title="Viewing Detail"></div>

	<div id="dialogbox" title="Debtor Master">
	  <div class="panel panel-default">
	    <div class="panel-heading">
	      <form id="DBcheckForm" class="form-inline">
	        <div class="form-group">
	          <b>Search: </b><div id="dbcol" name='dbcol'></div>
	        </div>
	        <div class="form-group" style="width:70%">
	          <input id="dbtext" name='dbtext' type="search" style="width:100%" placeholder="Search here ..." class="form-control text-uppercase" autocomplete="off">
	        </div>
	      </form>
	    </div>
	    <div class="panel-body">
	      <div id="DBgridDialog_c" class='col-xs-12' align="center">
	        <table id="DBgridDialog" class="table table-striped"></table>
	        <div id="DBgridDialogPager"></div>
	      </div>
	    </div>
	  </div>
	</div>


	<?php 
		include_once('../../../../footer.php'); 
	?>
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="arenquiry.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>
	<script src="../../../../assets/plugins/DataTables/datatables.min.js"></script>

<script>
		
</script>
</body>
</html>
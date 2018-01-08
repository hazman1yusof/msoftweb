<?php 
	include_once('../../../../header.php'); 
?>
<body>
<style>
	.num{
		width:20px;
	}
	.mybtn{
		float: right;
		display: none;
	}
	.bg-primary .mybtn{
		display:block;
	}
	.dataTables_scroll table{
		background-color: rgba(221, 221, 221, 0.3);
	}
	#TableBankEnquiryTran{
		background-color: rgba(250, 252, 246, 1);
	}
	#TableBankEnquiryTran tr:hover{
		background-color: white !important;
	}
	#TableBankEnquiryTran_c td:first-child,.dataTables_scrollHead th:first-child{
		border-top-color:white !important;
		border-top-style:solid !important;
		border-top-width:1px !important;
		border-left-color:white;
		border-left-style:solid;
		border-left-width:1px;
		border-bottom-color:white;
		border-bottom-style:solid;
		border-bottom-width:1px;
		cursor:pointer;
		background-color: white;
	}
	#TableBankEnquiryTran_c tr:hover td:first-child{
		border-style:solid;
		border-width:1px;
		border-color:#ddd;
		background-color:#ddd;
	}
	#TableGlmasdtl td:nth-child(2),#TableGlmasdtl th:nth-child(2){
		text-align:right;padding-right: 15px;
	}
	.textalignright,#TableGlmasTran_filter { text-align:right !important; }
	.textalignright div { padding-right: 5px; }
	.numericCol{
		text-align : right;
	}
	.bg-info{
		background-color: white;
	}
	</style>
	 
	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
		<div class='col-md-12' style="padding:0 0 15px 0;">
			<div class="form-group"> 
			  <div class="col-md-5">
			  	<label class="control-label" for="bankcode">Bank Code</label>  
	  			<div class='input-group'>
					<input id="bankcode" name="bankcode" type="text" class="form-control input-sm" autocomplete="off"/>
					<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
	  			</div>
					<span class="help-block"></span>
			  </div>
			  <div class="col-md-2">
			  	<label class="control-label" for="year">Year</label>  
			  	<select id='year' name='year' class="form-control input-sm"></select>
              </div>
			  <div class="col-md-1">
				<button type="button" id="search" class="btn btn-primary" style="position:absolute;top:17px">Search</button>
              </div>
             </div>
		</div>

    	<div class="panel panel-default">
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
            				<table id="jqGrid" class="table table-striped"></table>
            					<div id="jqGridPager"></div>
        				</div>
		    		</div>
		</div>


		<div class='row'>
			<div class='col-xs-3'>
				<table class="table table-hover" id="TableBankEnquiry">
				<thead>
						
						<th>Month</th>
						<th class="numericCol">Amount</th>
					
				</thead>
				<tbody>
					<br>
					<tr>
						<td class='num'>1</td>
						<td id='fd_actamount1' align="right" period='1'><span></span>
						</td>
					</tr>
					<tr>
						<td class='num'>2</td>
						<td id='fd_actamount2' align="right" period='2'>
							<span></span>
							
						</td>
					</tr>
					<tr>
						<td class='num'>3</td>
						<td id='fd_actamount3' align="right" period='3'>
							<span></span>
							
						</td>
					</tr>
					<tr>
						<td class='num'>4</td>
						<td id='fd_actamount4' align="right" period='4'>
							<span></span>
							
						</td>
					</tr>
					<tr>
						<td class='num'>5</td>
						<td  id='fd_actamount5' align="right"  period='5'>
							<span></span>
							
						</td>
					</tr>
					<tr>
						<td class='num'>6</td>
						<td  id='fd_actamount6' align="right" period='6'>
							<span></span>
							
						</td>
					</tr>
					<tr>
						<td class='num'>7</td>
						<td  id='fd_actamount7' align="right" period='7'>
							<span></span>
							
						</td>
					</tr>
					<tr>
						<td class='num'>8</td>
						<td  id='fd_actamount8' align="right" period='8'>
							<span></span>
							
						</td>
					</tr>
					<tr>
						<td class='num'>9</td>
						<td id='fd_actamount9' align="right" period='9'>
							<span></span>
							
						</td>
					</tr>
					<tr>
						<td class='num'>10</td>
						<td id='fd_actamount10' align="right" period='10'>
							<span></span>
						
						</td>
					</tr>
					<tr>
						<td class='num'>11</td>
						<td id='fd_actamount11' align="right" period='11'>
							<span></span>
							
						</td>
					</tr>
					<tr>
						<td class='num'>12</td>
						<td id='fd_actamount12' align="right" period='12'>
							<span></span>
							
						</td>
					</tr>

					<tr>
						<th>Total:</th>
						<td id='fd_total' align="right" ></td>
					</tr>

					<tr>
						<th>Balance:</th>
						<td id='fd_balance' align="right" ></td>
					</tr>
				</tbody>
				
				</table>
			</div>
			<div class='col-xs-9 modalx' id="TableBankEnquiryTran_c">
				<table class="table table-hover table-bordered" id='TableBankEnquiryTran'>
				<thead>
					<tr>
						<th></th>
						<th>Source</th>
						<th>TranType</th>
						<th>AuditNo</th>
						<th>Date</th>
						<th>Description</th>
						<th>Cheque No</th>
						<th>Debit Amount</th>
						<th>Credit Amount</th>
						
					</tr>
				</thead>
				<tbody>
				</tbody>
				</table>
			</div>
		</div>
    </div>
	<!-------------------------------- End Search + table ------------------>
		
		<div id="dialogForm" title="Viewing Detail"></div>

	<?php 
		include_once('../../../../footer.php'); 
	?>
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="bankEnquiry.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/plugins/datatables/js/jquery.datatables.min.js"></script>
	

<script>
		
</script>
</body>
</html>
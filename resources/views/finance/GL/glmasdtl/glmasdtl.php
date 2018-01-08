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
	#TableGlmasTran{
		background-color: rgba(250, 252, 246, 1);
	}
	#TableGlmasTran tr:hover{
		background-color: white !important;
	}
	#TableGlmasTran_c td:first-child,.dataTables_scrollHead th:first-child{
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
	#TableGlmasTran_c tr:hover td:first-child{
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
			  <div class="col-md-7">
			  	<label class="control-label" for="glaccount">GL Account</label>  
	  			<div class='input-group'>
					<input id="glaccount" name="glaccount" type="text" class="form-control input-sm" autocomplete="off"/>
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
				<table class="table table-hover" id='TableGlmasdtl'>
				<thead>
					<tr>
						<th>Month</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class='num'>1</td>
						<td id='glmasdtl_actamount1' period='1'><span></span></td>
					</tr>
					<tr>
						<td class='num'>2</td>
						<td id='glmasdtl_actamount2' period='2'><span></span></td>
					</tr>
					<tr>
						<td class='num'>3</td>
						<td id='glmasdtl_actamount3' period='3'><span></span></td>
					</tr>
					<tr>
						<td class='num'>4</td>
						<td id='glmasdtl_actamount4' period='4'><span></span></td>
					</tr>
					<tr>
						<td class='num'>5</td>
						<td id='glmasdtl_actamount5' period='5'><span></span></td>
					</tr>
					<tr>
						<td class='num'>6</td>
						<td id='glmasdtl_actamount6' period='6'><span></span></td>
					</tr>
					<tr>
						<td class='num'>7</td>
						<td id='glmasdtl_actamount7' period='7'><span></span></td>
					</tr>
					<tr>
						<td class='num'>8</td>
						<td id='glmasdtl_actamount8' period='8'><span></span></td>
					</tr>
					<tr>
						<td class='num'>9</td>
						<td id='glmasdtl_actamount9' period='9'><span></span></td>
					</tr>
					<tr>
						<td class='num'>10</td>
						<td id='glmasdtl_actamount10' period='10'><span></span></td>
					</tr>
					<tr>
						<td class='num'>11</td>
						<td id='glmasdtl_actamount11' period='11'><span></span></td>
					</tr>
					<tr>
						<td class='num'>12</td>
						<td id='glmasdtl_actamount12' period='12'><span></span></td>
					</tr>

					<tr>
						<th>Total:</th>
						<td id='fd_total'></td>
					</tr>

					<tr>
						<th>Balance:</th>
						<td id='fd_balance'></td>
					</tr>
				</tbody>
				</table>
			</div>
			<div id='TableGlmasTran_c' class='col-xs-9 modalx'>
				<table class="table table-hover  table-bordered" id='TableGlmasTran'>
				<thead>
					<tr>
						<th> </th>
						<th>Source</th>
						<th>Trantype</th>
						<th>Auditno</th>
						<th>Post Date</th>
						<th>Description</th>
						<th>Reference</th>
						<th>Account Code</th>
						<th>DR Amount</th>
						<th>CR Amount</th>
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
	<script src="glmasdtl.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>
	<script src="../../../../assets/plugins/datatables/js/jquery.datatables.min.js"></script>
	

<script>
		
</script>
</body>
</html>
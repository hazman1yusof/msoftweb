@extends('layouts.main')

@section('title', 'Item Enquiry')
@section('style')
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
	#TableDetailMovement td:first-child,.dataTables_scrollHead th:first-child{
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
	#TableDetailMovement tr:hover td:first-child{
		border-style:solid;
		border-width:1px;
		border-color:#ddd;
		background-color:#ddd;
	}
	#detailMovement{
		margin: 0.2%;position: absolute;right: 50px; bottom:5px;
	}
	
@endsection

@section('body')
	
	<div class='row'>

		<input id="Class2" name="Class" type="hidden" value="{{ $_GET['Class'] }}">
		<input id="getYear" name="getYear" type="hidden"  value="{{ Carbon\Carbon::now()->year }}">
		<!-- <input id="deptcode" name="deptcode" type="hidden" value="{{Request::get('deptcode')}}">
		<input id="itemcode" name="itemcode" type="hidden" value="{{Request::get('itemcode')}}"> -->
		

		<form id="searchForm" class="formclass" style='width:99%' onkeydown="return event.key != 'Enter';">
			<fieldset>
					<div class="ScolClass"  style="padding:0 0 0 15px">
						<div name='Scol' style='font-weight:bold'>Search By : </div>
					</div>
					<div class="StextClass" >
					  <div class='col-md-12' style="padding:0 0 15px 0;">
						<div class="col-md-8">
							<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="1">
		              	</div>

						<div class="col-md-2">
					  		<select id='Syear' name='Syear' class="form-control input-sm" tabindex="1"></select>
		              	</div>

	              	  </div>
					</div>

			 </fieldset> 

		</form>

		<div class="panel panel-default">
    		<div class="panel-body">
    			<div class='col-md-12' style="padding:0 0 15px 0">
    				<table id="jqGrid" class="table table-striped"></table>
    					<div id="jqGridPager"></div>
				</div>
    		</div>
		</div>

 		<div class="panel panel-default">
		    <div class="panel-body">
		    	<input id="itemcode" name="itemcode" type="hidden">
		    	<div class='col-md-8'>
            		<table id="detail" class="table table-striped"></table>
            			<div id="jqGridPager2"></div>
        		</div>

        		<div class='col-md-4'>
            		<table id="itemExpiry" class="table table-striped"></table>
        			<div id="jqGridPager3">
        			</div>
            		<button type="button" id='detailMovement' class='btn btn-info btn-sm'>Detail Movement</button>	
        		</div>

        	</div>

		</div>
    </div>

<!---*********************************** VIEW DETAIL MOVEMENT ************************************************** -->

	<div id="detailMovementDialog" title="View Detail Movement" >
		<div id='detailMovement_c' class=''>
			<div class="panel panel-default">
				
		    	<div class="panel-heading">Detail Movement by Item</div>
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
        					<div class="form-group">
            					<label class="col-md-1 control-label" for="itemcodedtl">Item Code</label>  
					  			<div class="col-md-2">
									<input id="itemcodedtl" name="itemcodedtl" class="form-control input-sm" type="text" readonly="true"><span class="help-block" id="itemcodedtl_">sdsd</span>
					  			</div>
						  		<label class="col-md-1 control-label" for="deptcodedtl">Dept Code</label>  
					  			<div class="col-md-2">
									<input id="deptcodedtl" name="deptcodedtl" class="form-control input-sm" value = "" readonly="true"><span class="help-block" id="deptcodedtl_">sdsd</span>
					  			</div>
						  		<label class="col-md-1 control-label" for="uomcodedtl">UOM Code</label>  
					  			<div class="col-md-2">
									<input id="uomcodedtl" name="uomcodedtl" class="form-control input-sm" value = "" readonly="true"><span class="help-block" id="uomcodedtl_">sdsd</span>
					  			</div>		
					  		</div>
						</div>	 

						<div class='col-md-12' style="padding:0 0 15px 0">
							<label class="col-md-1 control-label" for="monthfrom">Date From</label>
							<div class="col-md-2">
								<input type="date" id="datefrom" class="form-control input-sm" name="datefrom" value="{{\Carbon\Carbon::now('Asia/Kuala_Lumpur')->format('Y-m-d')}}" />
							</div>


							<label class="col-md-1 control-label" for="monthfrom">Date To</label>
							<div class="col-md-2">
								<input type="date" id="dateto" class="form-control input-sm" name="dateto" value="{{\Carbon\Carbon::now('Asia/Kuala_Lumpur')->format('Y-m-d')}}" />
							</div>
						</div>


							<!-- <div class='col-md-12' style="padding:0 0 15px 0">
	        					<div class="form-group">
	        						
		            					<label class="col-md-1 control-label" for="monthfrom">Month From</label>  
		            					<div class="col-md-2">
										  	<select id="monthfrom" name="monthfrom"  class="form-control input-sm">
										      <option value="monthfrom" selected>-- MONTH FROM --</option>
										      <option value="01">January</option>
										      <option value="02">February</option>
										      <option value="03">March</option>
										      <option value="04">April</option>
										      <option value="05">May</option>
										      <option value="06">June</option>
										      <option value="07">July</option>
										      <option value="08">August</option>
										      <option value="09">September</option>
										      <option value="10">October</option>
										      <option value="11">November</option>
										      <option value="12">December</option>
										    </select>
										</div>    
									

							  		<label class="col-md-1 control-label" for="yearfrom">Year From</label>  
		            					<div class="col-md-2">
			  									<select id='yearfrom' name='yearfrom' class="form-control input-sm"></select>
										</div>    

							  		<label class="col-md-1 control-label" for="monthto">Month To</label>  
		            					<div class="col-md-2">
										  	<select id="monthto" name="monthto" class="form-control input-sm">
										      <option value="monthto" selected>-- MONTH TO --</option>
										      <option value="01">January</option>
										      <option value="02">February</option>
										      <option value="03">March</option>
										      <option value="04">April</option>
										      <option value="05">May</option>
										      <option value="06">June</option>
										      <option value="07">July</option>
										      <option value="08">August</option>
										      <option value="09">September</option>
										      <option value="10">October</option>
										      <option value="11">November</option>
										      <option value="12">December</option>
										    </select>
										</div>    
									

							  		<label class="col-md-1 control-label" for="yearto">Year To</label>  
		            					<div class="col-md-2">
										  	<select id='yearto' name='yearto' class="form-control input-sm"></select>
										</div>    			
						  		</div> 
						 
						  	</div> -->	

						  	<div class='col-md-14' style="padding:0 0 20px 0">
		        				<div class="form-group">
		            				<label class="col-md-1 control-label" for="openbalqty">Opening Balance Quantity</label>  
								  		<div class="col-md-2">
											<input id="openbalqty" name="openbalqty" type="text"  class="form-control input-sm" value = "" readonly="true">
								  		</div>
								  	<label class="col-md-1 control-label" for="openbalval">Opening Balance Value</label>  
								  		<div class="col-md-2">
											<input id="openbalval" name="openbalval" type="text"  class="form-control input-sm" value = "" readonly="true">
								  		</div>
								</div>
							</div> 
							<div class="col-md-12">
								<button type="button" id="print" class="pull-right btn btn-default" style="margin-left: 10px;">Print</button>
								<button type="button" id="search" class="pull-right btn btn-primary" >Search</button>
	              			</div>
        				</div>
		    		</div>
		</div>

			<table class="table table-hover  table-bordered" id='TableDetailMovement'>
				<thead>
					<tr>
						<th>ID</th>
						<th> </th>
						<th>Transaction Date</th>
						<th>Trantype</th>
						<th>Transaction Description</th>
						<th>Dept</th>
						<th>Qty In</th>
						<th>Qty Out</th>
						<th>Balance Quantity</th>
						<th>Unit Cost</th>
						<th>Trans Amount</th>
						<th>Balance Amount</th>
						<th>Document No</th>
						<th>MRN</th>
						<th>Episode</th>
						<th>User Id</th>
						<th>Trans Time</th>
					</tr>
				
				</thead>
				<tbody>
				</tbody>
			</table>
			<hr>
			
		</div>
	</div>

	<div id="dialogForm" title="Viewing Detail">
		@include('finance.GL.glmasdtl.SalesOrder_glmasdtl')
	</div>

	<div id="open_detail_dialog" title="Detail View" style="padding:0">
		<div class='col-md-12' style="padding:0" >
			<iframe id='open_detail_iframe' src='' style="height: calc(100vh - 100px);width: 100%; border: none;"></iframe>
			<!-- guna nama 'open_detail_iframe' dialog utk semua detail view, utk tak error dkt parent_close_disabled -->
		</div>
	</div>

@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function () {
			if(!$("table#jqGrid").is("[tabindex]")){
				$("#jqGrid").bind("jqGridGridComplete", function () {
					$("table#jqGrid").attr('tabindex', 2);
					$("td#input_jqGridPager input.ui-pg-input.form-control").attr('tabindex', 3);
					$("td#input_jqGridPager input.ui-pg-input.form-control").on('focus',onfocus_pageof);
					if($('table#jqGrid').data('enter')){
						$('td#input_jqGridPager input.ui-pg-input.form-control').focus();
						$("table#jqGrid").data('enter',false);
					}

				});
			}

			function onfocus_pageof(){
				$(this).keydown(function(e){
					var code = e.keyCode || e.which;
					if (code == '9'){
						e.preventDefault();
						$('input[name=Stext]').focus();
					}
				});

				$(this).keyup(function(e) {
					var code = e.keyCode || e.which;
					if (code == '13'){
						$("table#jqGrid").data('enter',true);
					}
				});
			}
			
		});
	</script>
	<script src="js/material/itemInquiry/itemInquiry.js"></script>
	<script src="plugins/datatables/js/jquery.datatables.min.js"></script>

@endsection
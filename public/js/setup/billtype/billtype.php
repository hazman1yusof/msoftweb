<?php 
	include_once('../../../../header.php'); 
?>
<body style="display:none">
	 
	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<div class="ScolClass">
						<div name='Scol'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
					<div style="position:absolute;bottom:0;right:0;">
						<!--<label class="checkbox-inline"><input type="checkbox" value=""><b> Repeat add</b></label>-->
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
		    	<div class='col-md-12' style="padding:0 0 15px 0">
            		<table id="jqGridsvc" class="table table-striped"></table>
            			<div id="jqGridPager2"></div>
        		</div>
		    </div>
		</div>

		<div class="panel panel-default">
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
            		<table id="jqGriditem" class="table table-striped"></table>
            			<div id="jqGridPager3"></div>
        		</div>
		    </div>
		</div>

    </div>
	<!-------------------------------- End Search + table ------------------>
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>

				<div class="prevnext btn-group pull-right">
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="billtype">Bill Type</label>  
				  <div class="col-md-4">
				  <input id="billtype" name="billtype" type="text" maxlength="5" class="form-control input-sm" data-validation="required" frozeOnEdit>
				  </div>
                </div>

                <div class="form-group">
				  <label class="col-md-2 control-label" for="a.description">Description</label>  
				  <div class="col-md-8">
				  <input id="description" name="description" type="text" maxlength="100" class="form-control input-sm" data-validation="required">
				  </div>
                </div>

                <div class="form-group">
                 	<label class="col-md-2 control-label" for="price">Price</label>  
				 			 <div class="col-md-7">
                             <table>
                             	<tr>
                                	<td><label class="radio-inline"><input type="radio" name="price" value='PRICE1' data-validation="required">Price 1</label></td>
                                    <td><label class="radio-inline"><input type="radio" name="price" value='PRICE2' data-validation="">Price 2</label></td>
                                    <td><label class="radio-inline"><input type="radio" name="price" value='PRICE3' data-validation="">Price 3</label></td>
                                    <td><label class="radio-inline"><input type="radio" name="price" value='COST PRICE' data-validation="">Cost Price</label></td>
                                </tr>
                             </table> 
							</div>
				</div> 

				<div class="form-group">
				  <label class="col-md-2 control-label" for="service">All Service</label>  
				  <div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="service" value='1' data-validation="required">Yes</label>
					<label class="radio-inline"><input type="radio" name="service" value='0' data-validation="">No</label>
				  </div>

				  <label class="col-md-1 control-label" for="opprice">OP Price</label>  
				  <div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="opprice" value='1' data-validation="required">Yes</label>
					<label class="radio-inline"><input type="radio" name="opprice" value='0' data-validation="">No</label>
				  </div>
				</div> 

				<div class="form-group">
				  <label class="col-md-2 control-label" for="percent_">Percentage</label>  
				  <div class="col-md-2">
				  	<div class='input-group'>
				  		<input id="percent_" name="percent_" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0">
						<span class="input-group-addon">%</span>
					</div>
				  </div>
				  
				  <label class="col-md-2 control-label" for="amount">Amount</label>  
					<div class="col-md-3">
				  		<input id="amount" name="amount" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00">
				  	</div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-2">
					<input id="recstatus" name="recstatus" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				  </div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="adduser">Created By</label>  
						<div class="col-md-2">
						  	<input id="adduser" name="adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

					<label class="col-md-3 control-label" for="upduser">Last Entered</label>  
						<div class="col-md-2">
							<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						</div>
				</div>  

				<div class="form-group">
					<label class="col-md-2 control-label" for="adddate">Created Date</label>  
						<div class="col-md-3">
						  	<input id="adddate" name="adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

						<label class="col-md-2 control-label" for="upddate">Last Entered Date</label>  
						  	<div class="col-md-3">
								<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						  	</div>
				</div>  

				<div class="form-group">
					<label class="col-md-2 control-label" for="computerid">Computer Id</label>  
						<div class="col-md-2">
						  	<input id="computerid" name="computerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						</div>

					<label class="col-md-3 control-label" for="lastcomputerid">Last Computer Id</label>  
						<div class="col-md-2">
							<input id="lastcomputerid" name="lastcomputerid" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						  	</div>
				</div>    

				<div class="form-group">
					<label class="col-md-2 control-label" for="ipaddress">IP Address</label>  
						<div class="col-md-3">
						  	<input id="ipaddress" name="ipaddress" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						</div>

					<label class="col-md-2 control-label" for="lastipaddress">Last IP Address</label>  
						<div class="col-md-3">
							<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						  	</div>
				</div>   

			</form>
		</div>

		<!----------------------------------Bill Type Service Form -------------------->
		<div id="Dsvc" title="Bill Type Service" >
        	<form class='form-horizontal' style='width:99%' id='Fsvc'>

        		<div class="prevnext btn-group pull-right">
				</div>

        	<div class="form-group">
				  <label class="col-md-2 control-label" for="svc_billtype">Bill Type</label>  
				  <div class="col-md-2">
				  <input id="svc_billtype" name="svc_billtype" type="text" class="form-control input-sm" rdonly>
				  </div>
				  
				  <label class="col-md-2 control-label" for="m_description">Description</label>  
					<div class="col-md-6">
				  		<input id="m_description" name="m_description" type="text" class="form-control input-sm" rdonly>
				  	</div>
			</div>

			<div class="form-group">
				  <label class="col-md-2 control-label" for="svc_chggroup">Chg. Group</label>  
				  	<div class="col-md-2">
					  <div class='input-group'>
						<input id="svc_chggroup" name="svc_chggroup" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  	</div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label" for="svc_price">Price</label>  
				 	<div class="col-md-4">
                        <table>
                            <tr>
                                <td><label class="radio-inline"><input type="radio" name="svc_price" value='PRICE1' data-validation="required">Price 1</label></td>
                                <td><label class="radio-inline"><input type="radio" name="svc_price" value='PRICE2' data-validation="">Price 2</label></td>
                                <td><label class="radio-inline"><input type="radio" name="svc_price" value='PRICE3' data-validation="">Price 3</label></td>
                                <td><label class="radio-inline"><input type="radio" name="svc_price" value='COST PRICE' data-validation="">Cost Price</label></td>
                            </tr>
                        </table> 
					</div>

					<label class="col-md-1 control-label" for="svc_allitem'">All Item</label>  
					<div class="col-md-2">
						<label class="radio-inline"><input type="radio" name="svc_allitem" value='1' data-validation="required">Yes</label>
						<label class="radio-inline"><input type="radio" name="svc_allitem" value='0' data-validation="">No</label>
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="svc_amount">Amount</label>  
				    <div class="col-md-2">
					  	<input id="svc_amount" name="svc_amount" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00">
					</div>
				  
				<label class="col-md-3 control-label" for="svc_discchgcode">Disc Chg Code</label>  
					<div class="col-md-2">
				  		<input id="svc_discchgcode" name="svc_discchgcode" type="text" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
				  	</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="svc_percent_">Percentage</label>  
				    <div class="col-md-2">
					  <div class='input-group'>
					  	<input id="svc_percent_" name="svc_percent_" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0">
					  	<!--data-validation="number" data-validation-allowing="float"-->
					  	<span class="input-group-addon">%</span>
					  </div>
					</div>

				<label class="col-md-3 control-label" for="svc_recstatus">Record Status</label>  
				  	<div class="col-md-2">
						<input id="svc_recstatus" name="svc_recstatus" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				 	 </div>
				  		
			</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="svc_adduser">Created By</label>  
						<div class="col-md-2">
						  	<input id="svc_adduser" name="svc_adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

					<label class="col-md-3 control-label" for="svc_upduser">Last Entered</label>  
						<div class="col-md-2">
							<input id="svc_upduser" name="svc_upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						</div>
				</div> 

				<div class="form-group">
					<label class="col-md-2 control-label" for="svc_adddate">Created Date</label>  
						<div class="col-md-3">
						  	<input id="svc_adddate" name="svc_adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

						<label class="col-md-2 control-label" for="svc_upddate">Last Entered Date</label>  
						  	<div class="col-md-3">
								<input id="svc_upddate" name="svc_upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						  	</div>
				</div>  

				<div class="form-group">
					<label class="col-md-2 control-label" for="svc_computerid">Computer Id</label>  
						<div class="col-md-2">
						  	<input id="svc_computerid" name="svc_computerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						</div>

					<label class="col-md-3 control-label" for="svc_lastcomputerid">Last Computer Id</label>  
						<div class="col-md-2">
							<input id="svc_lastcomputerid" name="svc_lastcomputerid" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						  	</div>
				</div>    

				<div class="form-group">
					<label class="col-md-2 control-label" for="svc_ipaddress">IP Address</label>  
						<div class="col-md-2">
						  	<input id="svc_ipaddress" name="svc_ipaddress" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						</div>

					<label class="col-md-3 control-label" for="svc_lastipaddress">Last IP Address</label>  
						<div class="col-md-2">
							<input id="svc_lastipaddress" name="svc_lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						  	</div>
				</div>

        	</form>
        </div>


		<!--------------------------------END Bill Type Service Form ------------------>

		<!----------------------------------Bill Type Item Form -------------------->
		<div id="Ditem" title="Bill Type Item" >
        	<form class='form-horizontal' style='width:99%' id='Fitem'>

        		<div class="prevnext btn-group pull-right">
				</div>

				<input id="billtype" name="i_billtype" type="hidden" class="form-control input-sm">

				<div class="form-group">
				  <label class="col-md-2 control-label" for="i_chggroup">Chg Group</label>   
				  <div class="col-md-2">
				  <input id="i_chggroup" name="i_chggroup" type="text" class="form-control input-sm" rdonly>
				  </div>
				  
				  <label class="col-md-2 control-label" for="c_description">Description</label>  
					<div class="col-md-6">
				  		<input id="c_description" name="c_description" type="text" class="form-control input-sm" rdonly>
				  	</div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="i_chgcode">Chg. Code</label>  
				  	<div class="col-md-2">
					  <div class='input-group'>
						<input id="i_chgcode" name="i_chgcode" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  	</div>

				  	<label class="col-md-2 control-label" for="i_price">Price</label>  
				 	<div class="col-md-6">
                        <table>
                            <tr>
                                <td><label class="radio-inline"><input type="radio" name="i_price" value='PRICE1' data-validation="required">Price 1</label></td>
                                <td><label class="radio-inline"><input type="radio" name="i_price" value='PRICE2' data-validation="">Price 2</label></td>
                                <td><label class="radio-inline"><input type="radio" name="i_price" value='PRICE3' data-validation="">Price 3</label></td>
                                <td><label class="radio-inline"><input type="radio" name="i_price" value='COST PRICE' data-validation="">Cost Price</label></td>
                            </tr>
                        </table> 
					</div>
            	</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="i_amount">Amount</label>  
					    <div class="col-md-2">
						  	<input id="i_amount" name="i_amount" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00">
						</div>
					  
					<label class="col-md-2 control-label" for="i_percent_">Percentage</label>  
					    <div class="col-md-2">
						  <div class='input-group'>
						  	<input id="i_percent_" name="i_percent_" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0">
						  	<span class="input-group-addon">%</span>
						  </div>
						</div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="i_recstatus">Record Status</label>  
				  <div class="col-md-2">
					<input id="i_recstatus" name="i_recstatus" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				  </div>
				</div> 

				<div class="form-group">
					<label class="col-md-2 control-label" for="i_adduser">Created By</label>  
						<div class="col-md-2">
						  	<input id="i_adduser" name="i_adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

					<label class="col-md-3 control-label" for="i_upduser">Last Entered</label>  
						<div class="col-md-2">
							<input id="i_upduser" name="i_upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						</div>
				</div> 

				<div class="form-group">
					<label class="col-md-2 control-label" for="i_adddate">Created Date</label>  
						<div class="col-md-3">
						  	<input id="i_adddate" name="i_adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

						<label class="col-md-2 control-label" for="i_upddate">Last Entered Date</label>  
						  	<div class="col-md-3">
								<input id="i_upddate" name="i_upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						  	</div>
				</div>  

				<div class="form-group">
					<label class="col-md-2 control-label" for="i_computerid">Computer Id</label>  
						<div class="col-md-2">
						  	<input id="i_computerid" name="i_computerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						</div>

					<label class="col-md-3 control-label" for="i_lastcomputerid">Last Computer Id</label>  
						<div class="col-md-2">
							<input id="i_lastcomputerid" name="i_lastcomputerid" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						  	</div>
				</div>    

				<div class="form-group">
					<label class="col-md-2 control-label" for="i_ipaddress">IP Address</label>  
						<div class="col-md-2">
						  	<input id="i_ipaddress" name="i_ipaddress" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						</div>

					<label class="col-md-3 control-label" for="i_lastipaddress">Last IP Address</label>  
						<div class="col-md-2">
							<input id="i_lastipaddress" name="i_lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						  	</div>
				</div> 

        	</form>
        </div>
        <!--------------------------------END Bill Type Item Form ------------------>

	<?php 
		include_once('../../../../footer.php'); 
	?>
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="billtype.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<!--<script src="../../../../assets/js/dialogHandler.js"></script> -->

<script>
		
</script>
</body>
</html>
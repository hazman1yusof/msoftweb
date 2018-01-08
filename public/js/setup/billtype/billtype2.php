<?php 
	include_once('../../../../header.php'); 
?>
<body>
	 
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
						<label class="checkbox-inline"><input type="checkbox" value=""><b> Repeat add</b></label>
					</div>
				</div>
			 </fieldset> 
		</form>
    	<div class='col-md-12' style="padding:0 0 15px 0">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-12' style="padding:0 0 15px 0">
            <table id="jqGridsvc" class="table table-striped"></table>
            <div id="jqGridPager2"></div>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-12' style="padding:0 0 15px 0">
            <table id="jqGriditem" class="table table-striped"></table>
            <div id="jqGridPager3"></div>
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
				  <input id="billtype" name="billtype" type="text" maxlength="5" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
				  </div>
                </div>

                <div class="form-group">
				  <label class="col-md-2 control-label" for="a.description">Description</label>  
				  <div class="col-md-8">
				  <input id="description" name="description" type="text" maxlength="100" class="form-control input-sm text-uppercase" data-validation="required">
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
                                    <td><label class="radio-inline"><input type="radio" name="price" value='CostPrice' data-validation="">Cost Price</label></td>
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

				  <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="recstatus" value='A' checked>Active</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='D'>Deactive</label>
				  </div>
				</div> 

				<div class="form-group">
				  <label class="col-md-2 control-label" for="percent_">Percentage</label>  
				  <div class="col-md-3">
				  	<div class='input-group'>
				  		<input id="percent_" name="percent_" type="text" class="form-control input-sm" data-validation="number">
						<span class="input-group-addon">%</span>
					</div>
				  </div>
				  
				  <label class="col-md-2 control-label" for="amount">Amount</label>  
					<div class="col-md-3">
				  		<input id="amount" name="amount" type="text" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
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
				  <label class="col-md-2 control-label" for="svc.svc_chggroup">Chg. Group</label>  
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
				 	<div class="col-md-7">
                        <table>
                            <tr>
                                <td><label class="radio-inline"><input type="radio" name="svc_price" value='PRICE1' data-validation="required">Price 1</label></td>
                                <td><label class="radio-inline"><input type="radio" name="svc_price" value='PRICE2' data-validation="">Price 2</label></td>
                                <td><label class="radio-inline"><input type="radio" name="svc_price" value='PRICE3' data-validation="">Price 3</label></td>
                                <td><label class="radio-inline"><input type="radio" name="svc_price" value='CostPrice' data-validation="">Cost Price</label></td>
                            </tr>
                        </table> 
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="svc_amount">Amount</label>  
				    <div class="col-md-2">
					  	<input id="svc_amount" name="svc_amount" type="text" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
					</div>
				  
				<label class="col-md-2 control-label" for="svc_discchgcode">DiscChgCode</label>  
					<div class="col-md-2">
				  		<input id="svc_discchgcode" name="svc_discchgcode" type="text" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
				  	</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="svc_percent_">Percentage</label>  
				    <div class="col-md-2">
					  <div class='input-group'>
					  	<input id="svc_percent_" name="svc_percent_" type="text" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
					  	<span class="input-group-addon">%</span>
					  </div>
					</div>
				  
				<label class="col-md-2 control-label" for="svc_allitem'">All Item</label>  
					<div class="col-md-2">
						<label class="radio-inline"><input type="radio" name="svc_allitem" value='1' data-validation="required">Yes</label>
						<label class="radio-inline"><input type="radio" name="svc_allitem" value='0' data-validation="">No</label>
					</div>

				<label class="col-md-2 control-label" for="svc_recstatus">Record Status</label>  
				  	<div class="col-md-2">
						<label class="radio-inline"><input type="radio" name="svc_recstatus" value='A' checked>Active</label>
						<label class="radio-inline"><input type="radio" name="svc_recstatus" value='D'>Deactive</label>
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
                                <td><label class="radio-inline"><input type="radio" name="i_price" value='CostPrice' data-validation="">Cost Price</label></td>
                            </tr>
                        </table> 
					</div>
            	</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="i_amount">Amount</label>  
					    <div class="col-md-2">
						  	<input id="i_amount" name="i_amount" type="text" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
						</div>
					  
					<label class="col-md-2 control-label" for="i_percent_">Percentage</label>  
					    <div class="col-md-2">
						  <div class='input-group'>
						  	<input id="i_percent_" name="i_percent_" type="text" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
						  	<span class="input-group-addon">%</span>
						  </div>
						</div>
				</div>

				<div class="form-group">
				  <label class="col-md-3 control-label" for="i_recstatus">Record Status</label>  
				  <div class="col-md-4">
					<label class="radio-inline"><input type="radio" name="i_recstatus" value='A' checked>Active</label>
					<label class="radio-inline"><input type="radio" name="i_recstatus" value='D'>Deactive</label>
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
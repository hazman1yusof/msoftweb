<?php 
	include_once('../../../../header.php'); 
?>
<body>
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<div class="ScolClass">
						<div name='Scol'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
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
            <table id="gridSuppitems" class="table table-striped"></table>
            <div id="jqGridPager2"></div>
        </div>
    </div>


	<div class='row'>
		<div class="col-md-12" style="padding:0 0 15px 0">
			<table id="gridSuppBonus" class="table table-striped"></table>
            <div id="jqGridPager3"></div>
        </div>
    </div>

     	<!--------------------------------Supplier Form------------------>
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>
				
				<div class="prevnext btn-group pull-right">
					<a class='btn btn-default' name='prev'><i class='fa fa-chevron-left'></i></a>
					<a class='btn btn-primary' name='next' style='color:white'> Next <i class='fa fa-chevron-right'></i></a>
				</div>

            	<div class="form-group">
				  		<label class="col-md-2 control-label" for="SuppCode">Supplier Code</label>  
                            <div class="col-md-2">
                                <input id="SuppCode" name="SuppCode" type="text" maxlength="6" class="form-control input-sm" data-validation="required" frozeOnEdit>
                            </div>
					</div>
                    
                    <div class="form-group">
				  		<label class="col-md-2 control-label" for="Name">Name</label>  
				  			<div class="col-md-8">
				  				<input id="Name" name="Name" type="text" maxlength="100" class="form-control input-sm" data-validation="required">
				 			</div>
					</div>
                    
                    <div class="form-group">
				  		<label class="col-md-2 control-label" for="Addr1">Address</label>  
				  			<div class="col-md-8">
				  				<input id="Addr1" name="Addr1" type="text" maxlength="40" class="form-control input-sm" data-validation="required">
				  			</div>
					</div>
				
					<div class="form-group">
				  		<div class="col-md-offset-2 col-md-8">
				  			<input id="Addr2" name="Addr2" type="text" maxlength="40" class="form-control input-sm">
				  		</div>
					</div>
				
					<div class="form-group">
				  		<div class="col-md-offset-2 col-md-8">
				  			<input id="Addr3" name="Addr3" type="text" maxlength="40" class="form-control input-sm">
				  		</div>
					</div>
                
                	<div class="form-group">
				  		<div class="col-md-offset-2 col-md-8">
				  			<input id="Addr4" name="Addr4" type="text" maxlength="40" class="form-control input-sm">
				  		</div>
					</div>
                    
                   	<div class="form-group">
				   		<label class="col-md-2 control-label" for="TelNo">Tel No</label>  
				 			<div class="col-md-3">
				  				<input id="TelNo" name="TelNo" type="text" maxlength="50" class="form-control input-sm" data-validation="number">
				  			</div>
                            
                  		<label class="col-md-2 control-label" for="Faxno">Fax No</label>  
				  			<div class="col-md-3">
				  				<input id="Faxno" name="Faxno" type="text" maxlength="30" class="form-control input-sm" data-validation="number">
				  			</div>
					</div>
                    
                    <div class="form-group">
				  		<label class="col-md-2 control-label" for="ContPers">Contact Person</label>  
				  			<div class="col-md-3">
				  				<input id="ContPers" name="ContPers" type="text" maxlength="40" class="form-control input-sm" data-validation="required">
				  			</div>
				  
				   		<label class="col-md-2 control-label" for="SuppGroup">Supplier Group</label>  
				 			<div class="col-md-3">
					  			<div class='input-group'>
									<input id="SuppGroup" name="SuppGroup" type="text" class="form-control input-sm" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  			</div>
					  			<span class="help-block"></span>
				  			</div>
					</div>
                    
                  	<div class="form-group">
				  		<label class="col-md-2 control-label" for="CostCode">Cost Code</label>  
				  			<div class="col-md-3">
					  			<div class='input-group'>
									<input id="CostCode" name="CostCode" type="text" class="form-control input-sm" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  			</div>
					  			<span class="help-block"></span>
				  			</div>
				  
				 		<label class="col-md-2 control-label" for="GlAccNo">Gl Account No</label>  
				  			<div class="col-md-3">
					  			<div class='input-group'>
									<input id="GlAccNo" name="GlAccNo" type="text" class="form-control input-sm" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  			</div>
					  			<span class="help-block"></span>
				  			</div>
					</div>
                    
                 	<div class="form-group">
				  		<label class="col-md-2 control-label" for="AccNo">Company Account No</label>  
				 			<div class="col-md-3">
				  				<input id="AccNo" name="AccNo" type="text" maxlength="15" class="form-control input-sm" data-validation="required">
				  			</div>
                            
				   	<label class="col-md-2 control-label" for="SuppFlg">Supply Goods</label>  
				  	<div class="col-md-2">
                        <label class="radio-inline"><input type="radio" name="SuppFlg" value='Yes' data-validation="">Yes</label>
                        <label class="radio-inline"><input type="radio" name="SuppFlg" value='No' data-validation="">No</label>
					</div>
				</div>
                
                <div class="form-group">
					<label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  		<div class="col-md-4">
							<label class="radio-inline"><input type="radio" name="recstatus" value='A' checked>Active</label>
							<label class="radio-inline"><input type="radio" name="recstatus" value='D'>Deactive</label>
				  		</div>
				</div>
                
                <fieldset style="border:3px; border-top:1px solid black;">
                   <legend style="text-align:center; width:17% !important; border-bottom:0px !important;     font-size:16px !important; font-weight: bold;">Payment Terms</legend>
                </fieldset>
                
                <div class="form-group">
					<label class="col-md-2 control-label" for="TermDisp">Disposable</label>  
				 		<div class="col-md-2">
				 			<input id="TermDisp" name="TermDisp" type="text" value="0" maxlength="9" class="form-control input-sm" data-validation="required,number">
				  		</div>
				  
				  	<label class="col-md-2 control-label" for="TermNonDisp">Non-Disposable</label>  
				  		<div class="col-md-2">
				  			<input id="TermNonDisp" name="TermNonDisp" type="text" value="0" maxlength="9" class="form-control input-sm" data-validation="required,number">
				  		</div>

				  	<label class="col-md-1 control-label" for="TermOthers">Other</label>    
				  		<div class="col-md-2">
				  			<input id="TermOthers" name="TermOthers" type="text" value="0" maxlength="9" class="form-control input-sm" data-validation="required,number">
				  		</div>
              	</div>
			</form>
		</div>
        
         <!--------------------------------End supplier Form------------------>
        
        
         <!--------------------------------Supplier Item Form ------------------>

         <div id="Dsuppitems" title="Supplier Item" >
        	<form class='form-horizontal' style='width:99%' id='Fsuppitems'>
        		<div class="prevnext btn-group pull-right">
					<a class='btn btn-default' name='prev'><i class='fa fa-chevron-left'></i></a>
					<a class='btn btn-primary' name='next' style='color:white'> Next <i class='fa fa-chevron-right'></i></a>
				</div>
            
            	<input id="lineno_" name="si.lineno_" type="hidden" class="form-control input-sm">
                <input id="suppcode" name="si.suppcode" type="hidden" class="form-control input-sm">
               
            	 <div class="form-group">
				  <label class="col-md-4 control-label" for="si.pricecode">Price Code</label>  
				  	<div class="col-md-3">
					  <div class='input-group'>
						<input id="si.pricecode" name="si.pricecode" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  	</div>
                 </div>
            
            	<div class="form-group">
				  <label class="col-md-4 control-label" for="si.itemcode">Item Code</label>  
				  	<div class="col-md-5">
					  <div class='input-group'>
						<input id="si.itemcode" name="si.itemcode" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  	</div>
                </div>		
                 
                 <div class="form-group">
				  <label class="col-md-4 control-label" for="si.uomcode">UOM Code</label>  
				  	<div class="col-md-5">
					  <div class='input-group'>
						<input id="si.uomcode" name="si.uomcode" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  	</div>
                 </div>	
                 
                 <div class="form-group">
				  	<label class="col-md-4 control-label" for="si.purqty">Purchase Quantity</label>  
				  		<div class="col-md-4">
				  		<input id="si.purqty" name="si.purqty" type="text" class="form-control input-sm" data-validation="required">
				  		</div>
                </div>	
                
                <div class="form-group">
				  	<label class="col-md-4 control-label" for="si.unitprice">Unit Price</label>  
				  		<div class="col-md-4">
				  		<input id="si.unitprice" name="si.unitprice" type="text" class="form-control input-sm" data-validation="required">
				  		</div>
                </div>
                
                <div class="form-group">
				  	<label class="col-md-4 control-label" for="si.perdiscount">Percentage of Discount</label>  
				  		<div class="col-md-4">
				  		<input id="si.perdiscount" name="si.perdiscount" type="text" class="form-control input-sm" data-validation="required">
				  		</div>
                </div>
                
                <div class="form-group">
				  	<label class="col-md-4 control-label" for="si.amtdisc">Amount Discount</label>  
				  		<div class="col-md-4">
				  		<input id="si.amtdisc" name="si.amtdisc" type="text" class="form-control input-sm" data-validation="required">
				  		</div>
                </div>	
                
                <div class="form-group">
				  	<label class="col-md-4 control-label" for="si.perslstax">Percentage of Sales Tax</label>  
				  		<div class="col-md-4">
				  		<input id="si.perslstax" name="si.perslstax" type="text" class="form-control input-sm" data-validation="required">
				  		</div>
                </div>
                
                <div class="form-group">
				  	<label class="col-md-4 control-label" for="si.amtslstax">Amount Sales Tax</label>  
				  		<div class="col-md-4">
				  		<input id="si.amtslstax" name="si.amtslstax" type="text" class="form-control input-sm" data-validation="required">
				  		</div>
                </div>
                
                <div class="form-group">
				  	<label class="col-md-4 control-label" for="si.expirydate">Expiry Date</label>  
				  		<div class="col-md-4">
				  		<input id="si.expirydate" name="si.expirydate" type="Date" min="<?php echo date("Y-m-d"); ?>"   class="form-control input-sm" data-validation="required">
				  		</div>
                </div>
                
                <div class="form-group">
				  	<label class="col-md-4 control-label" for="si.sitemcode">Item Code at Supplier's Site</label>  
				  		<div class="col-md-4">
				  		<input id="si.sitemcode" name="si.sitemcode" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
				  		</div>
                </div>
			</form>
		</div>
		<!------------------------------end supplier item form---------------------------------->

		<div id="Dsuppbonus" title="Bonus Item" >
        	<form id="Fsuppbonus" class='form-horizontal' style='width:99%'>
        		<div class="prevnext btn-group pull-right">
					<a class='btn btn-default' name='prev'><i class='fa fa-chevron-left'></i></a>
					<a class='btn btn-primary' name='next' style='color:white'> Next <i class='fa fa-chevron-right'></i></a>
				</div>
            
            <input name="sb.suppcode" type="hidden">
            <input name="sb.pricecode" type="hidden">
            <input name="sb.itemcode" type="hidden">
            <input name="sb.uomcode" type="hidden">
            <input name="sb.purqty" type="hidden">
            <input name="sb.lineno_" type="hidden">
            
            	<div class="form-group">
				  <label class="col-md-4 control-label" for="sb.bonpricecode">Bonus Price Code</label>  
				  	<div class="col-md-3">
					  <div class='input-group'>
						<input id="sb.bonpricecode" name="sb.bonpricecode" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  	</div>
                 </div>
                 
                 <div class="form-group">
				  <label class="col-md-4 control-label" for="sb.bonitemcode">Bonus Item Code</label>  
				  	<div class="col-md-5">
					  <div class='input-group'>
						<input id="sb.bonitemcode" name="sb.bonitemcode" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  	</div>
                </div>	
                
                <div class="form-group">
				  <label class="col-md-4 control-label" for="sb.bonuomcode">Bonus UOM Code</label>  
				  	<div class="col-md-5">
					  <div class='input-group'>
						<input id="sb.bonuomcode" name="sb.bonuomcode" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  	</div>
                 </div>
                 
                 <div class="form-group">
				  	<label class="col-md-4 control-label" for="sb.bonqty">Bonus Quantity</label>  
				  		<div class="col-md-4">
				  		<input id="sb.bonqty" name="sb.bonqty" type="text" class="form-control input-sm" data-validation="required">
				  		</div>
                </div>	
                
                <div class="form-group">
				  	<label class="col-md-4 control-label" for="sb.bonsitemcode">Bonus Item Code at Supplier Site</label>  
				  		<div class="col-md-4">
				  		<input id="sb.bonsitemcode" name="sb.bonsitemcode" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
				  		</div>
                </div>
            </form>
        </div>



	<?php 
		include_once('../../../../footer.php'); 
	?>
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="supplier.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

<script>
		
</script>
</body>
</html>
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
				</div>
			 </fieldset> 
		</form>
    	<div class='col-md-12' style="padding:0 0 15px 0">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
        </div>
    </div>
	<!-------------------------------- End Search + table ------------------>
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>

				<div class="prevnext btn-group pull-right">
				</div>
			
				<div class="form-group">
				  <label class="col-md-2 control-label" for="itemcode">Item Code</label>  
				  <div class="col-md-3">
					<input id="itemcode" name="itemcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
				  </div>
			    </div>
                
			    <div class="form-group">
                  <label class="col-md-2 control-label" for="description">Item Description</label>  
				  <div class="col-md-5">
				  <input id="description" name="description" type="text" maxlength="100" class="form-control input-sm text-uppercase text-uppercase" data-validation="required">
				  </div>
			    </div>

                <div class="form-group">                   
                  <label class="col-md-2 control-label" for="generic">Generic Name</label>  
				  <div class="col-md-4">
				  <input id="generic" name="generic" type="text" maxlength="40" class="form-control input-sm text-uppercase" data-validation="required">
				  </div>
				</div>
                
                <div class="form-group">
                   <label class="col-md-2 control-label" for="groupcode">Group Code</label>  
				  <div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="groupcode" value='Stock'>Stock</label>
					<label class="radio-inline"><input type="radio" name="groupcode" value='Asset'>Asset</label>
                    <label class="radio-inline"><input type="radio" name="groupcode" value='Other'>Other</label>
				  </div>
				  
                  <label class="col-md-2 control-label" for="productcat">Category</label>  
				  	<div class="col-md-3">
				  		<div class='input-group'>
				  			<input id="productcat" name="productcat" type="text" class="form-control input-sm text-uppercase" data-validation="required">
				  			<a class='input-group-addon btn btn-primary' id="2"><span class='fa fa-ellipsis-h' id-="3"></span></a>
					  </div>
					  <span class="help-block"></span>
                      
				  </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="subcatcode">Sub Category</label>  
				  <div class="col-md-3">
				  <input id="subcatcode" name="subcatcode" type="text" maxlength="15" class="form-control input-sm text-uppercase">
				  </div>
                  
                  <label class="col-md-2 control-label" for="uomcode">UOM Code</label>  
				  <div class="col-md-3">
					  <div class='input-group'>
						<input id="uomcode" name="uomcode" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
                      
				  </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="pouom">PO OUM</label>  
				  <div class="col-md-3">
				 <div class='input-group'>
						<input id="pouom" name="pouom" type="text" class="form-control input-sm">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
                      
				  </div>

				   <label class="col-md-2 control-label" for="itemtype">Item Type</label>  
				  <div class="col-md-4">
					<label class="radio-inline"><input type="radio" name="itemtype" value='Non-poison'>Non-poison</label>
					<label class="radio-inline"><input type="radio" name="itemtype" value='Poison'>Poison</label>
				  </div>
				  
				</div>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="suppcode">Supplier Code</label>  
				  <div class="col-md-3">
					  <div class='input-group'>
						<input id="suppcode" name="suppcode" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                  
                  <label class="col-md-2 control-label" for="mstore">Main Store</label>  
				  <div class="col-md-3">
					  <div class='input-group'>
						<input id="mstore" name="mstore" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                </div>
                
            <hr> 
            
            	<div class="form-group">
				  <label class="col-md-3 control-label" for="minqty">Min Stock Qty</label>  
				  <div class="col-md-2">
				  <input id="minqty" name="minqty" type="text" maxlength="11" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0" value="0">
				  </div>
                  
                  <label class="col-md-3 control-label" for="maxqty">Max Stock Qty</label>  
				  <div class="col-md-2">
				  <input id="maxqty" name="maxqty" type="text" maxlength="11" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0" value="0">
				  </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-3 control-label" for="reordlevel">Record Level</label>  
				  <div class="col-md-2">
				  <input id="reordlevel" name="reordlevel" type="text" maxlength="11" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0" value="0">
				  </div>
                  
                  <label class="col-md-3 control-label" for="reordqty">Reoder Qty</label>  
				  <div class="col-md-2">
				  <input id="reordqty" name="reordqty" type="text" maxlength="11" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0" value="0">
				  </div>
				</div>
                
                <hr>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="reuse">Reuse</label>  
				  <div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="reuse" value='1'>Yes</label>
					<label class="radio-inline"><input type="radio" name="reuse" value='0'>No</label>
				  </div>
				  
				  <label class="col-md-1 control-label" for="rpkitem">Repack Item</label>  
				  <div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="rpkitem" value='1'>Yes</label>
					<label class="radio-inline"><input type="radio" name="rpkitem" value='0'>No</label>
				  </div>
                  
                  <label class="col-md-2 control-label" for="tagging">Tagging</label>  
				  <div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="tagging" value='1'>Yes</label>
					<label class="radio-inline"><input type="radio" name="tagging" value='0'>No</label>
				  </div>
				</div>
                
                 <div class="form-group">
				  <label class="col-md-2 control-label" for="expdtflg">Expiry Date</label>  
				  <div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="expdtflg" value='1'>Yes</label>
					<label class="radio-inline"><input type="radio" name="expdtflg" value='0'>No</label>
				  </div>
				  
				  <label class="col-md-1 control-label" for="chgflag">Charge</label>  
				  <div class="col-md-2">
					<label class="radio-inline"><input type="radio" name="chgflag" value='1' >Yes</label>
					<label class="radio-inline"><input type="radio" name="chgflag" value='0' >No</label>
				  </div>
                  
                  <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="recstatus" value='A' checked>Active</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='D'>Deactive</label>
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
	<script src="product1.js"></script>
	<script src="../../../../assets/js/utility.js"></script>

<script>
		
</script>
</body>
</html>
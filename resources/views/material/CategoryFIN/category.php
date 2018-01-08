<?php 
	include_once('../../../../header.php'); 
?>
<body style="display:none">

	<input id="source2" name="source" type="hidden" value="<?php echo $_GET['source'];?>">
	<input id="cattype2" name="cattype" type="hidden" value="<?php echo $_GET['cattype'];?>">
	 
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
    	<div class="panel panel-default">
		    <div class="panel-body">
		    	<div class='col-md-12' style="padding:0 0 15px 0">
            		<table id="jqGrid" class="table table-striped"></table>
            			<div id="jqGridPager"></div>
        		</div>
		    </div>
		</div>
    </div>
	<!-------------------------------- End Search + table ------------------>
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>


				<input id="source2" name="source" type="hidden" value="<?php echo $_GET['source'];?>">	
				<input id="cattype" name="cattype" type="hidden" value="<?php echo $_GET['cattype'];?>">	

				<div class="prevnext btn-group pull-right">
				</div>

				<input id="stockacct" name="stockacct" type="hidden">
				<input id="cosacct" name="cosacct" type="hidden">
				<input id="adjacct" name="adjacct" type="hidden">
				<input id="woffacct" name="woffacct" type="hidden">
				<input id="loanacct" name="loanacct" type="hidden">

				<div class="form-group">
				  	<label class="col-md-2 control-label" for="catcode">Category</label>  
				  		<div class="col-md-3">
				 			<input id="catcode" name="catcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
						</div>
				                  
				 	 <label class="col-md-2 control-label" for="description">Description</label>  
				  		<div class="col-md-4">
				  			<input id="description" name="description" type="text" maxlength="100" class="form-control input-sm" data-validation="required">
				  		</div>
				</div>

				<div class="form-group">
				  	<label class="col-md-2 control-label" for="expacct">Account Code</label>  
				  		<div class="col-md-3">
					  		<div class='input-group'>
								<input id="expacct" name="expacct" type="text" class="form-control input-sm" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  		</div>
					  		<span class="help-block"></span>
				  		</div>
                </div>

                <div class="form-group">
                  	<label class="col-md-2 control-label" for="povalidate">PO Validate</label>  
					  <div class="col-md-3">
						<label class="radio-inline"><input type="radio" name="povalidate" value='1' checked>Yes</label>
						<label class="radio-inline"><input type="radio" name="povalidate" value='0'>No</label>
					  </div>

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
						<div class="col-md-3">
						  	<input id="computerid" name="computerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						</div>

					<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>  
						<div class="col-md-3">
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
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="category.js"></script>
	<script src="../../../../assets/js/utility.js"></script>

<script>
		
</script>
</body>
</html>
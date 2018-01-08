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

				<div class="prevnext btn-group pull-right">
					
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="debtortycode">Financial Class</label>  
				  <div class="col-md-3">
				  <input id="debtortycode" name="debtortycode" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit>
				  </div>
                </div>
				
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="description">Description</label>  
				  <div class="col-md-8">
				  <input id="description" name="description" type="text" maxlength="40" class="form-control input-sm" data-validation="required">
				  </div>
                </div>
                
				<div class="form-group">
					<label class="col-md-2 control-label" for="actdebccode">Actual Cost</label>  
					<div class="col-md-3">
					  <div class='input-group'>
						<input id="actdebccode" name="actdebccode" type="text" class="form-control input-sm" data-validation="required"/>
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
					</div>
					
					<label class="col-md-2 control-label" for="actdebglacc">Actual Account</label>  
					<div class="col-md-3">
					  <div class='input-group'>
						<input id="actdebglacc" name="actdebglacc" type="text" class="form-control input-sm" data-validation="required"/>
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
					</div>
				</div>
                
				<div class="form-group">
					<label class="col-md-2 control-label" for="depccode">Deposit Cost</label>  
					<div class="col-md-3">
					  <div class='input-group'>
						<input id="depccode" name="depccode" type="text" class="form-control input-sm" data-validation="required"/>
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
					</div>
					
					<label class="col-md-2 control-label" for="depglacc">Deposit Account</label>  
					<div class="col-md-3">
					  <div class='input-group'>
						<input id="depglacc" name="depglacc" type="text" class="form-control input-sm" data-validation="required"/>
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
					</div>
				</div>
				
				 <div class="form-group">
					<label class="col-md-2 control-label" for="adduser">Created By</label>  
						<div class="col-md-3">
						  	<input id="adduser" name="adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

						<label class="col-md-2 control-label" for="upduser">Last Entered</label>  
						  	<div class="col-md-3">
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
						  	<input id="computerid" name="computerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne >
						</div>

						<label class="col-md-2 control-label" for="lastcomputerid">Last Computer Id</label>  
						<div class="col-md-3">
						  	<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne >
						</div>

				</div> 

				<div class="form-group">
				<label class="col-md-2 control-label" for="ipaddress">IP Address</label>  
						  	<div class="col-md-3">
								<input id="ipaddress" name="ipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
						  	</div>
					

						<label class="col-md-2 control-label" for="lastipaddress">Last IP Address</label>  
						  	<div class="col-md-3">
								<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
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
	<script src="debtortype.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

<script>
		
</script>
</body>
</html>
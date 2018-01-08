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
				<input id='idno' name='idno' type='hidden'>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="debtorcode">Debtor Code</label>  
				  <div class="col-md-3">
				  <input id="debtorcode" name="debtorcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit >
				  </div>
				                  
				  <label class="col-md-2 control-label" for="debtortype">Financial Class</label>  
				  <div class="col-md-3">
					  <div class='input-group'>
						<input id="debtortype" name="debtortype" type="text" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                  </div>
                
				<div class="form-group">
				  <label class="col-md-2 control-label" for="name">Debtor Name</label>  
				  <div class="col-md-8">
				  <input id="name" name="name" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="address1">Address</label>  
				  <div class="col-md-8">
				  <input id="address1" name="address1" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>
				
				<div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="address2" name="address2" type="text" class="form-control input-sm">
				  </div>
				</div>
				
				<div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="address4" name="address4" type="text" class="form-control input-sm">
				  </div>
				</div>
                
                <div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="address4" name="address4" type="text" class="form-control input-sm">
				  </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="postcode">PostCode</label>  
				  <div class="col-md-3">
				  <input id="postcode" name="postcode" type="text" class="form-control input-sm">
				 </div>
				
				  <label class="col-md-2 control-label" for="payto">Payable To</label>  
				  <div class="col-md-3">
				  <input id="payto" name="payto" type="text" class="form-control input-sm">
				</div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="teloffice">Tel. Office</label>  
                  <div class="col-md-3">
				    <input id="teloffice" name="teloffice" type="text" class="form-control input-sm">
				  </div>
                 
				  <label class="col-md-2 control-label" for="fax">Fax</label>  
                  <div class="col-md-3">
				    <input id="fax" name="fax" type="text" class="form-control input-sm">
				  </div>
                </div>
                  
                <div class="form-group">
				  <label class="col-md-2 control-label" for="contact">Contact</label>  
                  <div class="col-md-3">
				  <input id="contact" name="contact" type="text" class="form-control input-sm">
				  </div>
                 
				  <label class="col-md-2 control-label" for="position">Position</label>  
                  <div class="col-md-3">
				  <input id="position" name="position" type="text" class="form-control input-sm">
				  </div>
                </div>
                  
                <div class="form-group">
				  <label class="col-md-2 control-label" for="email">Email</label>  
                  <div class="col-md-3">
				  <input id="email" name="email" type="text" class="form-control input-sm">
				  </div>
                 
				  <label class="col-md-2 control-label" for="accno">Bank Acc. No</label>  
                  <div class="col-md-3">
				  <input id="accno" name="accno" type="text" class="form-control input-sm">
				  </div>
                </div>
                  
                 <div class="form-group">
				 <label class="col-md-2 control-label" for="billtype">Bill Type IP</label>  
				  <div class="col-md-3">
					  <div class='input-group'>
						<input id="billtype" name="billtype" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                  
                
                 <label class="col-md-2 control-label" for="billtypeop">Bill Type OP</label>  
				  <div class="col-md-3">
					  <div class='input-group'>
						<input id="billtypeop" name="billtypeop" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                </div>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="coverageip">Coverage IP</label>  
				  <div class="col-md-3">
				  <input ids="coverageip" name="coverageip" type="text" class="form-control input-sm">
				  </div>
				  
				  <label class="col-md-2 control-label" for="coverageop">Coverage OP</label>  
				  <div class="col-md-3">
				  <input id="coverageop" name="coverageop" type="text" class="form-control input-sm">
				  </div>
				</div>
				  
				 <div class="form-group">
				  <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-8">
				    <label class="radio-inline"><input type="radio" name="recstatus" value='A' checked>Active</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='D'>Deactive</label>
                    <label class="radio-inline"><input type="radio" name="recstatus" value='Suspend'>Suspend</label>		
                    <label class="radio-inline"><input type="radio" name="recstatus" value='Legal'>Legal</label>		
                    <label class="radio-inline"><input type="radio" name="recstatus" value='Debt-Collector'>Debt-Collector</label>		
                    <label class="radio-inline"><input type="radio" name="recstatus" value='Yes'>Yes</label>				
                </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="creditlimit">Credit Limit</label>  
				  <div class="col-md-3">
				  <input id="creditlimit" name="creditlimit" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" data-validation="required" >
				  </div>
				  
				  <label class="col-md-2 control-label" for="creditterm">Credit Term</label>  
				  <div class="col-md-3">
				  <input id="creditterm" name="creditterm" type="text" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" data-validation="required">
				  </div>
				</div>
				
				<div class="form-group">
				 <label class="col-md-2 control-label" for="requestgl">Request GL</label>  
				  <div class="col-md-8">
				    <label class="radio-inline"><input type="radio" name="requestgl" value='1'>Yes</label>
					<label class="radio-inline"><input type="radio" name="requestgl" value='0'>No</label>				
				  </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="crgroup">Credit Control Group</label>  
                  <div class="col-md-3">
				  <input id="crgroup" name="crgroup" type="text" class="form-control input-sm">
				  </div>
                 
				  <label class="col-md-2 control-label" for="debtorgroup">Debtor Group</label>  
                  <div class="col-md-3">
				  <input id="debtorgroup" name="debtorgroup" type="text" class="form-control input-sm">
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


				  <input id="actdebccode" name="actdebccode" type="hidden" class="form-control input-sm" data-validation="required">

				  <input id="actdebglacc" name="actdebglacc" type="hidden" class="form-control input-sm" data-validation="required">

				  <input id="depccode" name="depccode" type="hidden" class="form-control input-sm" data-validation="required">

				  <input id="depglacc" name="depglacc" type="hidden" class="form-control input-sm" data-validation="required">


				 
			</form>
		</div>

	<?php 
		include_once('../../../../footer.php'); 
	?>
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->
	<script src="../../../../assets/js/utility.js"></script>

	<!-- JS Page Level -->
	<script src="debtorMaster.js"></script>

<script>
		
</script>
</body>
</html>
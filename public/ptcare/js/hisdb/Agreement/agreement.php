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
				  	
				<div class="form-group">
				  <label class="col-md-2 control-label" for="AgreementID">Agreement ID</label>    
				  <div class="col-md-4">
                  	  <div class='input-group'>
						<input id="AgreementID" name="AgreementID" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit>
					  </div>
				  </div>
                
                	<label class="col-md-2 control-label" for="AgreementNo">Agreement No</label>  
                      <div class="col-md-4">
                      <input id="AgreementNo" name="AgreementNo" type="text" class="form-control input-sm" data-validation="number, required">
                      </div>
				</div>
                
               <div class="form-group">  
				  <div class="col-md-4">
                  	  <div class='input-group'>
						<input id="Status" name="Status" type="hidden" class="form-control input-sm" data-validation="required">
					  </div>
				  </div>
                </div>
                
                <div class="form-group">
                	<label class="col-md-2 control-label" for="AgreementDate">Agreement Date</label>  
                      <div class="col-md-4">
                      <input id="AgreementDate" name="AgreementDate" type="date" class="form-control input-sm">
                      </div>
				
                	<label class="col-md-2 control-label" for="JoinDate">Join Date</label>  
                      <div class="col-md-4">
                       <input id="JoinDate" name="JoinDate" type="date" class="form-control input-sm">
                      </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-4">
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
	<script src="agreement.js"></script>	
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

<script>
		
</script>
</body>
</html>
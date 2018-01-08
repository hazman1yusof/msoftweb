<?php 
	include_once('../../../../header2.php'); 
?>
<body>
	 
	<form id="searchForm" style='width:99%'>
		<fieldset>
			<div id="searchInContainer">
					<div id="Scol">Search By : </div>
			</div>
		
			<div style="padding-left: 65px;margin-top: 25px;padding-right: 60%;">
				<input id="Stext" name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
			</div>
		 </fieldset>  
	</form>
		
	<div class='col-md-12' style="padding-left:0">
		<table id="jqGrid" class="table table-striped"></table>
		<div id="jqGridPager"></div>
	</div>
        
       <div id="dialog" title="title">
        	<form id="checkForm" class="form-horizontal col-xs-12" style="background-color:gainsboro;margin-top:5px;border-radius:5px"><br>
            	<div id="Dcol" class='col-xs-6 form-group'>
				</div>
                
				<div class='col-xs-7 form-group'>
					<input id="Dtext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
				</div>
			</form>
            
			<div class='col-xs-12' align="center">
            <br>
				<table id="gridDialog" class="table table-striped"></table>
				<div id="gridDialogPager"></div>
			</div>
		</div>
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>
				<div class="form-group">
				  <label class="col-md-2 control-label" for="tillcode">Till Code</label>  
				  <div class="col-md-4">
				  <input id="tillcode" name="tillcode" type="text" maxlength="30" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
				  </div>
                </div>
				
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="description">Description</label>  
				  <div class="col-md-10">
				  <input id="description" name="description" type="text" maxlength="40" class="form-control input-sm text-uppercase" data-validation="required">
				  </div>
                </div>
                
				<div class="form-group">
					<label class="col-md-2 control-label" for="dept">Department</label>  
					<div class="col-md-4">
					  <div class='input-group'>
						<input id="dept" name="dept" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='ion-more'></span></a>
					  </div>
					  <span class="help-block"></span>
					</div>
					
					<label class="col-md-2 control-label" for="effectdate">Effective Date</label>  
					<div class="col-md-4">
					<input id="effectdate" name="effectdate" type="date" class="form-control input-sm" data-validation="required">
					</div>
				  
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="defopenamt">Default Open Amount</label>  
				  <div class="col-md-4">
				  <input id="defopenamt" name="defopenamt" type="text" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
				  </div>
				  
				  <label class="col-md-2 control-label" for="tillstatus">Till Status</label>  
					<div class="col-md-4">
					<input id="tillstatus" name="tillstatus" type="text" class="form-control input-sm" value="C">
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="lastrcnumber">Last No. Receipt</label>  
				  <div class="col-md-4">
				  <input id="lastrcnumber" name="lastrcnumber" type="text" class="form-control input-sm" value="0000000001">
				  </div>
				  
				  <label class="col-md-2 control-label" for="lastrefundno">Last No. Refund</label>  
					<div class="col-md-4">
					<input id="lastrefundno" name="lastrefundno" type="text" class="form-control input-sm" value="0000000001">
				  </div>
				  
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="lastcrnoteno">Last No. Credit note</label>  
				  <div class="col-md-4">
				  <input id="lastcrnoteno" name="lastcrnoteno" type="text" class="form-control input-sm" value="0000000001"/>
				  </div>
				  
				  <label class="col-md-2 control-label" for="lastinnumber">Last No. In</label>  
					<div class="col-md-4">
					<input id="lastinnumber" name="lastinnumber" type="text" class="form-control input-sm" value="0000000001">
				  </div>
				</div>
			</form>
		</div>

	<?php 
		include_once('../../../../footer2.php'); 
	?>
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="till.js"></script>

<script>
		
</script>
</body>
</html>
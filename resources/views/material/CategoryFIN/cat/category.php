<?php 
	include_once('../../../../header2.php'); 
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
		<button id='sbut1' class='btn btn-default btn-xs' style=''><i></i> Search</button>
    	<div class='col-md-12' style="padding:0 0 15px 0">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
        </div>
    </div>
	<!-------------------------------- End Search + table ------------------>

	<!--------------------------------Dialog Handler ------------------------>
	   <div id="dialog" title="title">
	    	<form id="checkForm" style="width:99%">
				<fieldset>
	                <div class="ScolClass">
	                	Search By : <div id="Dcol" name='Dcol'></div>
	               	</div>
					<div class="StextClass">
	               		<input id="Dtext" name='Dtext' type="search" placeholder="Search here ..." class="form-control text-uppercase">
	               </div>
				</fieldset>
			</form>
	        
			<div class='col-xs-12' align="center">
	        <br>
				<table id="gridDialog" class="table table-striped"></table>
				<div id="gridDialogPager"></div>
			</div>
		</div>
 	<!--------------------------------End dialog handler------------------>
		
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>

				<div class="btn-group">
					<a class='btn btn-default' name='prev'><i class='fa fa-chevron-left'></i></a>
					<a class='btn btn-primary' name='next' style='color:white'> Next <i class='fa fa-chevron-right'></i></a>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="catcode">Category</label>  
				  <div class="col-md-4">
				  <input id="catcode" name="catcode" type="text" maxlength="30" class="form-control input-sm text-uppercase" data-validation="required" frozeOnEdit>
				  </div>
				                  
				  <label class="col-md-2 control-label" for="description">Description</label>  
				  <div class="col-md-4">
				  <input id="description" name="description" type="text" maxlength="100" class="form-control input-sm" data-validation="required">
				  </div>
				</div>
                
                  
                  <div class="form-group">
				  <label class="col-md-2 control-label" for="stockacct">Stock Account</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="stockacct" name="stockacct" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='ion-more'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                                  
                  <label class="col-md-2 control-label" for="woffacct">Write Off Account</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="woffacct" name="woffacct" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='ion-more'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                  </div>
                  
                  
                  <div class="form-group">
				  <label class="col-md-2 control-label" for="cosacct">COS Account</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="cosacct" name="cosacct" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='ion-more'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                       
                  <label class="col-md-2 control-label" for="expacct">Expenses Account</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="expacct" name="expacct" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='ion-more'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                  </div>
                  
                  
                  <div class="form-group">
				  <label class="col-md-2 control-label" for="adjacct">Adjustment Account</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="adjacct" name="adjacct" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='ion-more'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                       
                  <label class="col-md-2 control-label" for="loanacct">Loan Account</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="loanacct" name="loanacct" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='ion-more'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                  </div>
                  
                  
                  <div class="form-group">
                  <label class="col-md-2 control-label" for="povalidate">Record Status</label>  
				  <div class="col-md-4">
				    <label class="radio-inline"><input type="radio" name="povalidate" value='1' checked>ACTIVE</label>
					<label class="radio-inline"><input type="radio" name="povalidate" value='0'>DEACTIVE</label>				
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
	<script src="category.js"></script>

<script>
		
</script>
</body>
</html>
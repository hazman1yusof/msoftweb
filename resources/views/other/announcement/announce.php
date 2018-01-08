<?php 
	include_once('../../../header2.php'); 
?>
<link rel="stylesheet" type="text/css" href="../../../assets/plugins/simditor/styles/simditor.css" />


<body>
		<form id="searchForm" style='width:99%'>
			<fieldset>
				<div id="searchInContainer">
						<div id="Scol">Search By : </div>
				</div>
			
				<div style="padding-left: 65px;margin-top: 25px;padding-right: 60%;"><input id="Stext" name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase"></div>
			 </fieldset>  
		</form>
		
		<div class='col-md-12' style="padding-left:0">
			<table id="jqGrid" class="table table-striped"></table>
			<div id="jqGridPager"></div>
		</div>
		
		<div id="dialogForm" title="Dialog Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>
			
				<div class="form-group">
				  <label class="col-md-2 control-label" for="regioncode">Type</label>  
				  <div class="col-md-4">
					<label class='radio-inline'><input type='radio' name='type' value='announcement' >Announcement</input></label>
					<label class='radio-inline'><input type='radio' name='type' value='message'>Message</input></label>
				  </div>
				  
				  <label class="col-md-2 control-label" for="description">Send to</label>  
				  <div class="col-md-4">
					<div class='input-group'>
						<input id="msto" name="msto" type="text" class="form-control input-sm text-uppercase" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='ion-more'></span></a>
					</div>
					<span class="help-block"></span>
				  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="description">Date From</label>  
				  <div class="col-md-4">
					<input id="dateFrom" name="dateFrom" type="date" class="form-control input-sm" data-validation="required">
				  </div>
				  
				  <label class="col-md-2 control-label" for="description">Date To</label>  
				  <div class="col-md-4">
					<input id="dateTo" name="dateTo" type="date" class="form-control input-sm" data-validation="required">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="description">Title</label>  
				  <div class="col-md-10">
					<input id="title" name="title" type="text" class="form-control input-sm" data-validation="required">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="description">Image Location</label>  
				  <div class="col-md-10">
					<input id="imgLoc" name="imgLoc" type="text" class="form-control input-sm">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="description">Message Body</label>  
				  <div class="col-md-10">
					<textarea id="contains" name="contains" class="form-control input-sm" rows="5" data-validation="required"></textarea>
				  </div>
				</div>

				<input id="sysno" name="sysno" type="hidden" class="form-control input-sm" data-validation="required">
			</form>
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

    <script type="text/ecmascript" src="../../../assets/plugins/jquery.min.js"></script> 
    <script type="text/ecmascript" src="../../../assets/plugins/trirand/i18n/grid.locale-en.js"></script>
    <script type="text/ecmascript" src="../../../assets/plugins/trirand/jquery.jqGrid.min.js"></script>
    <script type="text/ecmascript" src="../../../assets/plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <script type="text/ecmascript" src="../../../assets/plugins/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
    <script type="text/ecmascript" src="../../../assets/plugins/AccordionMenu/dist/metisMenu.min.js"></script>
    <script type="text/ecmascript" src="../../../assets/plugins/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
	<script type="text/ecmascript" src="../../../assets/plugins/form-validator/jquery.form-validator.min.js"></script>
    <script type="text/ecmascript" src="../../../assets/plugins/jquery.dialogextend.js"></script>
	
	<!-- JS Implementing Plugins -->
	<script type="text/javascript" src="../../../assets/plugins/simditor/scripts/module.min.js"></script>
	<script type="text/javascript" src="../../../assets/plugins/simditor/scripts/hotkeys.min.js"></script>
	<script type="text/javascript" src="../../../assets/plugins/simditor/scripts/uploader.min.js"></script>
	<script type="text/javascript" src="../../../assets/plugins/simditor/scripts/simditor.js"></script>

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="announce.js"></script>

    
</body>
</html>
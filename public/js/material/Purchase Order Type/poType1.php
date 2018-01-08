<?php 
	include_once($_SERVER['DOCUMENT_ROOT'] . '/newms/connection/sschecker.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script type="text/ecmascript" src="../../js/jquery.min.js"></script> 
    <script type="text/ecmascript" src="../../js/trirand/i18n/grid.locale-en.js"></script>
    <script type="text/ecmascript" src="../../js/trirand/jquery.jqGrid.min.js"></script>
    <script type="text/ecmascript" src="../../js/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <script type="text/ecmascript" src="../../js/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
    <script type="text/ecmascript" src="../../js/AccordionMenu/dist/metisMenu.min.js"></script>
    <script type="text/ecmascript" src="../../js/jquery-ui.min.js"></script>
	<script type="text/ecmascript" src="../../js/form-validator/jquery.form-validator.min.js"></script>
    <script type="text/ecmascript" src="../../js/jquery.dialogextend.js"></script>
	
	
    <link rel="stylesheet" href="../../js/form-validator/theme-default.css" />
	<link rel="stylesheet" href="../../js/jquery-ui-1.11.4.custom/jquery-ui.css">
	<link rel="stylesheet" href="../../js/font-awesome-4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../js/ionicons-2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="../../js/AccordionMenu/dist/metisMenu.min.css"> 
	<link rel="stylesheet" href="../../js/bootstrap-3.3.5-dist/css/bootstrap.min.css"> 
	<link rel="stylesheet" href="../../js/jasny-bootstrap/css/jasny-bootstrap.min.css"> 
    <link rel="stylesheet" type="text/css" media="screen" href="../../js/css/trirand/ui.jqgrid-bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="../../js/searchCSS/stylesSearch.css">
    
    <style>
		.ui-dialog { z-index: 1000 !important ;}
		
		.wrap{
			word-wrap: break-word;
		}
		.ui-th-column{
			word-wrap: break-word;
			white-space: normal !important;
			vertical-align: top !important;
		}
		
		.radio-inline+.radio-inline {
			margin-left: 0;
		}
		
		.radio-inline {
			margin-right: 10px;
		}
	</style>
	
	
	 <?php include("poTypeScript.php")?>
     
    
    <meta charset="utf-8" />
    <title>Purchase Order Type</title>
</head>
<body>
	
	  
	<div class="container" style="margin-bottom:1em">
		<div class='row'>
        </div>
            
            <form id="searchForm" style='width:99%'>
				<fieldset>
                    <div id="searchInContainer">
                            <div id="Scol">Search By : </div>
                   </div>
                
					<div style="padding-left: 65px;margin-top: 25px;padding-right: 60%;"><input id="Stext" name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase"></div>
                 </fieldset>  
			</form>
		<br>
		
		<div class='row'>
			<div class='col-md-12'>
				<table id="jqGrid" class="table table-striped"></table>
				<div id="jqGridPager"></div>
			</div>
		</div>
		
		<div id="dialogForm" title="Dialog Form" >
			<form class='form-horizontal' style='width:99%' id='formdata'>
            
				<div class="form-group">
				  <label class="col-md-2 control-label" for="potype">PO Type</label>  
				  <div class="col-md-2">
				  <input id="potype" name="potype" type="text" maxlength="12" class="form-control input-sm" >
				  </div>
                  </div>
				  
                
              <div class="form-group">
				  <label class="col-md-2 control-label" for="typedec">Description</label>  
				  <div class="col-md-4">
				  <input id="typedec" name="typedec" type="text" class="form-control input-sm" >
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
        
		
	</div>	
	</div><!--/.container-->

    
</body>
</html>
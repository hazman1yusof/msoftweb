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
    
     <?php include("debtorTypeScript.php")?>
     
	
    <meta charset="utf-8" />
    <title>Debtor Type</title>
</head>
<body>
	  
	<div class="container" style="margin-bottom:1em">
		<div class='row'>
			<form class="form-horizontal">
				<fieldset>

				</fieldset>
            </form>
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
				  <label class="col-md-2 control-label" for="debtortycode">Financial Class</label>  
				  <div class="col-md-4">
					<input id="debtortycode" name="debtortycode" type="text" class="form-control input-sm" 
                    data-validation="required">
				  </div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="description">Description</label>  
				  <div class="col-md-4">
				  <input id="description" name="description" type="text" class="form-control input-sm" 
                  data-validation="required">
				  </div>
				</div>
                
                
                
                <div class="form-group">
					  <label class="col-md-2 control-label" for="actdebccode">Actual Cost Center</label>  
					  <div class="col-md-4">
						  <div class='input-group'>
							<input id="actdebccode" name="actdebccode" type="text" class="form-control input-sm" 
							data-validation="required">
							<a class='input-group-addon btn btn-primary'><span class='ion-more'></span></a>
						  </div>
						  <span class="help-block"></span>
					  </div>
					  
					  <label class="col-md-2 control-label" for="actdebglacc">Actual GL Account</label>  
					  <div class="col-md-4">
						  <div class='input-group'>
							<input id="actdebglacc" name="actdebglacc" type="text" class="form-control input-sm" 
							data-validation="required">
							<a class='input-group-addon btn btn-primary'><span class='ion-more'></span></a>
						  </div>
						  <span class="help-block"></span>
					  </div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="depccode">Deposit Cost Center</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="depccode" name="depccode" type="text" class="form-control input-sm" 
                        data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='ion-more'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
				
				
				
				  <label class="col-md-2 control-label" for="depglacc">Deposit GL Account</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="depglacc" name="depglacc" type="text" class="form-control input-sm" 
                        data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='ion-more'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                </div>
               
				
				
				<div class="form-group">
				 <label class="col-md-2 control-label" for="typegrp" ></label>  
				  <div class="col-md-5">
					<label class="radio-inline"><input type="radio" name="typegrp" value='Trade' data-validation="required">Trade</label>
					<label class="radio-inline"><input type="radio" name="typegrp" value='Related' data-validation="">Related</label>
                    <label class="radio-inline"><input type="radio" name="typegrp" value='Miscellanous' data-validation="">Miscellanous</label>
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
		
		<!--<div id="dialog" title="title">
         	 <form id="searchForm" style="width:99%">
				<fieldset>
                    <div id="searchInContainer">
                    	Search By : <div id="Dcol" style="float:right; margin-right: 80px;"></div>
                   
                   		<input  style="float:left; margin-left: 73px;" id="Dtext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
                   </div>
				</fieldset> -->
			</form>
            
			
		</div>
	</div><!--/.container-->

    
</body>
</html>
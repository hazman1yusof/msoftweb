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
	
	 <?php include("tranTypeScript.php")?>
		
	
    <meta charset="utf-8" />
    <title>Transaction Type</title>
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
				  <div class="col-md-4">
				  <input id="sysno" name="sysno" type="hidden" class="form-control input-sm" >
				  </div>
             </div>
             
				<div class="form-group">
                <label class="col-md-2 control-label" for="trantype">Transaction Type</label>  
				  <div class="col-md-4">
				  <input id="trantype" name="trantype" type="text" maxlength="12" class="form-control input-sm" 
				  data-validation="required" >
				  </div>
				  
				  <label class="col-md-2 control-label" for="description">Description</label>  
				  <div class="col-md-4">
				  <input id="description" name="description" type="text" maxlength="50" class="form-control input-sm" 
				   data-validation="required">
				  </div>
				</div>
                
              <div class="form-group">
				  <label class="col-md-2 control-label" for="isstype">Issue Type</label>  
				  <div class="col-md-4">
                  <table>
                  <tr>
				 <td><label class="radio-inline"><input type="radio" name="isstype" value='Issue'>Issue</label></td>
					<td><label class="radio-inline"><input type="radio" name="isstype" value='Transfer'>Transfer</label></td>
                    <td><label class="radio-inline"><input type="radio" name="isstype" value='Others'>Others</label></td>
                    </tr>
                    </table>
				  </div>
			
				
				  
				  <label class="col-md-2 control-label" for="trbyiv">Transaction By Inventory</label>  
				  <div class="col-md-4">
				 <label class="radio-inline"><input type="radio" name="trbyiv" value='Yes'>Yes</label>
					<label class="radio-inline"><input type="radio" name="trbyiv" value='No'>No</label>
				  </div>
				</div>
                
                  <div class="form-group">
				  <label class="col-md-2 control-label" for="updqty">Update Quantity</label>  
				  <div class="col-md-4">
					<label class="radio-inline"><input type="radio" name="updqty" value='Yes'>Yes</label>
					<label class="radio-inline"><input type="radio" name="updqty" value='No'>No</label>
				  </div>
				  
				  <label class="col-md-2 control-label" for="crdbfl">Credit/Debit</label>  
				  <div class="col-md-4">
				 	<label class="radio-inline"><input type="radio" name="crdbfl" value='I'>In</label>
					<label class="radio-inline"><input type="radio" name="crdbfl" value='O'>Out</label>
				  </div>
				</div>
                
                
                  <div class="form-group">
				  <label class="col-md-2 control-label" for="updamt">Update GL</label>  
				  <div class="col-md-4">
				 <label class="radio-inline"><input type="radio" name="updamt" value='Yes'>Yes</label>
					<label class="radio-inline"><input type="radio" name="updamt" value='No'>No</label>
				  </div>
                  </div>
                
                <div class="form-group">
				 <label class="col-md-2 control-label" for="accttype">Account Type</label>  
				  <div class="col-md-5">
                  <table>
                  <tr>
					<td><label class="radio-inline"><input type="radio" name="accttype" value='Adjustment'>Adjustment</label></td>
					<td><label class="radio-inline"><input type="radio" name="accttype" value='Stock'>Stock</label></td>
                   <td> <label class="radio-inline"><input type="radio" name="accttype" value='Accrual'>Accrual</label></td>
                   </tr>
                   
                   <tr>
                    <td><label class="radio-inline"><input type="radio" name="accttype" value='Expense'>Expense</label></td>
                    <td><label class="radio-inline"><input type="radio" name="accttype" value='Loan'>Loan</label></td>
                       <td><label class="radio-inline"><input type="radio" name="accttype" value='CostOfSale'>Cost Of Sale</label></td>
                    </tr>
                    
                    <tr>
                    <td><label class="radio-inline"><input type="radio" name="accttype" value='Write Off'>Write Off</label></td>
                   <td> <label class="radio-inline"><input type="radio" name="accttype" value='Others'>Others</label></td>
                   </tr>
                   
                   </table>
				  </div>
				</div>
                
                 <div class="form-group">
				 <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-5">
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
				
				
            
		
		
		
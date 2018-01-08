<?php 
	include_once('../../../../assets/php/sschecker.php'); 
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <link rel="stylesheet" href="../../../assets/plugins/form-validator/theme-default.css" />
	<link rel="stylesheet" href="../../../assets/plugins/jquery-ui-1.11.4.custom/jquery-ui.css">
	<link rel="stylesheet" href="../../../assets/plugins/font-awesome-4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../../assets/plugins/ionicons-2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="../../../assets/plugins/AccordionMenu/dist/metisMenu.min.css"> 
	<link rel="stylesheet" href="../../../assets/plugins/bootstrap-3.3.5-dist/css/bootstrap.min.css"> 
	<link rel="stylesheet" href="../../../assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css"> 
    <link rel="stylesheet" href="../../../assets/plugins/css/trirand/ui.jqgrid-bootstrap.css" />
    <link rel="stylesheet" href="../../../assets/plugins/searchCSS/stylesSearch.css">
     
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
		::-webkit-scrollbar{
		  width: 6px;  /* for vertical scrollbars */
		  height: 6px; /* for horizontal scrollbars */
		}
		::-webkit-scrollbar-track{
		  background: rgba(0, 0, 0, 0.1);
		}
		::-webkit-scrollbar-thumb{
		  background: rgba(0, 0, 0, 0.5);
		}
 	</style>
	
    <meta charset="utf-8" />
    <title>Finance - Debtor Master</title>
</head>
<body>
	 
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
        
       <div id="dialog" title="title">
        	<form class="form-horizontal col-xs-12" style="background-color:gainsboro;margin-top:5px;border-radius:5px"><br>
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
				  <label class="col-md-2 control-label" for="debtorcode">Debtor Code</label>  
				  <div class="col-md-4">
				  <input id="debtorcode" name="debtorcode" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
				  </div>
				                  
				  <label class="col-md-2 control-label" for="debtortype">Financial Class</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="debtortype" name="debtortype" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='ion-more'></span></a>
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
				  <input id="address3" name="address3" type="text" class="form-control input-sm">
				  </div>
				</div>
                
                <div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="address4" name="address4" type="text" class="form-control input-sm">
				  </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="postcode">PostCode</label>  
				  <div class="col-md-4">
				  <input id="postcode" name="postcode" type="text" class="form-control input-sm">
				 </div>
				
				  <label class="col-md-2 control-label" for="payto">Payable To</label>  
				  <div class="col-md-4">
				  <input id="payto" name="payto" type="text" class="form-control input-sm">
				</div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-2 control-label" for="teloffice">Tel. Office</label>  
                  <div class="col-md-4">
				    <input id="teloffice" name="teloffice" type="text" class="form-control input-sm">
				  </div>
                 
				  <label class="col-md-2 control-label" for="fax">Fax</label>  
                  <div class="col-md-4">
				    <input id="fax" name="fax" type="text" class="form-control input-sm">
				  </div>
                </div>
                  
                <div class="form-group">
				  <label class="col-md-2 control-label" for="contact">Contact</label>  
                  <div class="col-md-4">
				  <input id="contact" name="contact" type="text" class="form-control input-sm">
				  </div>
                 
				  <label class="col-md-2 control-label" for="position">Position</label>  
                  <div class="col-md-4">
				  <input id="position" name="position" type="text" class="form-control input-sm">
				  </div>
                </div>
                  
                <div class="form-group">
				  <label class="col-md-2 control-label" for="email">Email</label>  
                  <div class="col-md-4">
				  <input id="email" name="email" type="text" class="form-control input-sm">
				  </div>
                 
				  <label class="col-md-2 control-label" for="accno">Bank Acc. No</label>  
                  <div class="col-md-4">
				  <input id="accno" name="accno" type="text" class="form-control input-sm">
				  </div>
                </div>
                  
                 <div class="form-group">
				 <label class="col-md-2 control-label" for="billtype">Bill Type IP</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="billtype" name="billtype" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='ion-more'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                  
                
                 <label class="col-md-2 control-label" for="billtypeop">Bill Type OP</label>  
				  <div class="col-md-4">
					  <div class='input-group'>
						<input id="billtypeop" name="billtypeop" type="text" class="form-control input-sm" data-validation="required">
						<a class='input-group-addon btn btn-primary'><span class='ion-more'></span></a>
					  </div>
					  <span class="help-block"></span>
				  </div>
                </div>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="coverageip">Coverage IP</label>  
				  <div class="col-md-4">
				  <input ids="coverageip" name="coverageip" type="text" class="form-control input-sm">
				  </div>
				  
				  <label class="col-md-2 control-label" for="coverageop">Coverage OP</label>  
				  <div class="col-md-4">
				  <input id="coverageop" name="coverageop" type="text" class="form-control input-sm">
				  </div>
				</div>
				  
				 <div class="form-group">
				  <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-10">
				    <label class="radio-inline"><input type="radio" name="recstatus" value='A' checked>ACTIVE</label>
					<label class="radio-inline"><input type="radio" name="recstatus" value='D'>DEACTIVE</label>
                    <label class="radio-inline"><input type="radio" name="recstatus" value='Suspend'>SUSPEND</label>		
                    <label class="radio-inline"><input type="radio" name="recstatus" value='Legal'>LEGAL</label>		
                    <label class="radio-inline"><input type="radio" name="recstatus" value='Debt-Collector'>DEBT-COLLECTOR</label>		
                    <label class="radio-inline"><input type="radio" name="recstatus" value='Yes'>YES</label>				
                </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="creditlimit">Credit Limit</label>  
				  <div class="col-md-4">
				  <input ids="creditlimit" name="creditlimit" type="text" class="form-control input-sm">
				  </div>
				  
				  <label class="col-md-2 control-label" for="creditterm">Credit Term</label>  
				  <div class="col-md-4">
				  <input id="creditterm" name="creditterm" type="text" class="form-control input-sm">
				  </div>
				</div>
				
				<div class="form-group">
				 <label class="col-md-2 control-label" for="requestgl">Request GL</label>  
				  <div class="col-md-10">
				    <label class="radio-inline"><input type="radio" name="requestgl" value='Yes'>YES</label>
					<label class="radio-inline"><input type="radio" name="requestgl" value='No'>NO</label>				
				  </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="crgroup">Credit Control Group</label>  
                  <div class="col-md-4">
				  <input id="crgroup" name="crgroup" type="text" class="form-control input-sm">
				  </div>
                 
				  <label class="col-md-2 control-label" for="debtorgroup">Debtor Group</label>  
                  <div class="col-md-4">
				  <input id="debtorgroup" name="debtorgroup" type="text" class="form-control input-sm">
				  </div>
				</div>

				  <input id="actdebccode" name="actdebccode" type="hidden" class="form-control input-sm" data-validation="required">

				  <input id="actdebglacc" name="actdebglacc" type="hidden" class="form-control input-sm" data-validation="required">

				  <input id="depccode" name="depccode" type="hidden" class="form-control input-sm" data-validation="required">

				  <input id="depglacc" name="depglacc" type="hidden" class="form-control input-sm" data-validation="required">

                  
                  
		</form>
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

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="debtorMaster.js"></script>

<script>
		
</script>
</body>
</html>
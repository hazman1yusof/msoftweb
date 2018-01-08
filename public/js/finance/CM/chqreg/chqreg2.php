<?php 
	include_once($_SERVER['DOCUMENT_ROOT'] . '/newms/connection/sschecker.php'); 
?>

<!DOCTYPE html>

<html lang="en"><head>
    <script type="text/ecmascript" src="../../js/jquery.min.js"></script> 
    <script type="text/ecmascript" src="../../js/trirand/i18n/grid.locale-en.js"></script>
    <script type="text/ecmascript" src="../../js/trirand/jquery.jqGrid.min.js"></script>
    <script type="text/ecmascript" src="../../js/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <script type="text/ecmascript" src="../../js/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
    <script type="text/ecmascript" src="../../js/AccordionMenu/dist/metisMenu.min.js"></script>
	
	<link rel="stylesheet" href="../../js/css/demo.css">
	<link rel="stylesheet" href="../../js/font-awesome-4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../js/ionicons-2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="../../js/AccordionMenu/dist/metisMenu.min.css"> 
	<link rel="stylesheet" href="../../js/bootstrap-3.3.5-dist/css/bootstrap.min.css"> 
	<link rel="stylesheet" href="../../js/jasny-bootstrap/css/jasny-bootstrap.min.css"> 
    <link rel="stylesheet" type="text/css" media="screen" href="../../js/css/trirand/ui.jqgrid-bootstrap.css" />
    
    <style>
		.wrap{
			word-wrap: break-word;
		}
		.ui-th-column{
			word-wrap: break-word;
			white-space: normal !important;
			vertical-align: top !important;
		}
	</style>
    
    <?php include("chqregScript.php")?>
          
    <meta charset="utf-8" />
    <title>Finance - Cheque Register</title>
</head>
<body>
	  
	<div class="container" style="margin-bottom:1em">
		<div class='row'>
			
		</div>
		
		<div class='row'>
			<div class="col-md-12">
				<table id="jqGrid" class="table table-striped"></table>
                <div id="jqGridPager"></div>
            </div>
        </div>
        
        <br>
        
        <div class='row'>
			<div class="col-md-12">
				<table id="detail" class="table table-striped"></table>
				<div id="jqGridPager2"></div>
            </div>
        </div>
                       

	</div><!--/.container float-right-->

    
</body>
</html>
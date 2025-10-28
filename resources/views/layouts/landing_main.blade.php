<!DOCTYPE html>

<html lang="en">
<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<link rel="stylesheet" href="plugins/bootstrap-3.3.5-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="plugins/bootstrap-3.3.5-dist/css/bootstrap-theme.css">
	<link rel="stylesheet" href="plugins/jquery-ui-1.12.1/jquery-ui.min.css">
	<link rel="stylesheet" href="plugins/bootgrid/css/jquery.bootgrid.css">
	<!-- <link rel="stylesheet" href="plugins/datatables/css/jquery.dataTables.css"> -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.1.2/css/rowGroup.dataTables.min.css">
	<link rel="stylesheet" href="plugins/font-awesome-4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="plugins/form-validator/theme-default.css" />

    <link rel="stylesheet" href="plugins/css/trirand/ui.jqgrid-bootstrap.css" />
	<link rel="stylesheet" href="css/landing.css?v=1">
	@yield('css')

</head>

<body class="header-fixed">
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>

    <div class="wrapper">

		@yield('body')
		
		<script type="text/ecmascript" src="plugins/jquery-3.2.1.min.js"></script> 
		<script type="text/ecmascript" src="plugins/jquery-migrate-3.0.0.js"></script>
	    <script type="text/ecmascript" src="plugins/trirand/i18n/grid.locale-en.js"></script>
	    <script type="text/ecmascript" src="plugins/trirand/jquery.jqGrid.min.js"></script>
	    <script type="text/ecmascript" src="plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
	    <script type="text/javascript">$.fn.modal.Constructor.prototype.enforceFocus = function() {};</script>
	    <script type="text/ecmascript" src="plugins/jquery-ui-1.12.1/jquery-ui.min.js"></script>
		<script type="text/ecmascript" src="plugins/form-validator/jquery.form-validator.min.js"></script>
	    
	    <script type="text/ecmascript" src="plugins/numeral.min.js"></script>
		<script type="text/ecmascript" src="plugins/moment.js"></script>
		<script type="text/javascript" src="js/myjs/utility.js"></script>
		
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js"></script>
		<script type="text/javascript" src="plugins/jquery-validator/jquery.validate.min.js"></script>
		<script type="text/javascript" src="plugins/jquery-validator/additional-methods.min.js"></script>

		@yield('scripts')
	
	</div>

</body>
</html>
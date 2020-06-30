<!DOCTYPE html>

<html lang="en">
<head>
	
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

	<script type="text/ecmascript" src="https://code.jquery.com/jquery-3.5.1.js"></script> 
	<script type="text/ecmascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script type="text/ecmascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/ecmascript" src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.1.2/css/rowGroup.bootstrap4.min.css"/>
	@yield('css')
	
    <style>
		@yield('style')
 	</style>	
    <title>@yield('title')</title>

	@yield('js')
</head>
<body>


	@yield('body')
	<!-- <script src="js/myjs/utility.js"></script> -->

	@yield('scripts')
	<!-- <script src="religionScript.js"></script> example yielded scripts-->

	<!-- <script src="js/myjs/dialogHandler.js"></script> -->

<script>
		
</script>
</body>
</html>
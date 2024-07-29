<!DOCTYPE html>
<html lang="en">
  <head>
  	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

		<link rel="stylesheet" href="plugins/font-awesome-4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.css">

		<style type="text/css">
		</style>

  	<title>@yield('title')</title>

	  <style>
		  .preloader {
		      width: 100%;
		      height: 100%;
		      top: 0;
		      position: fixed;
		      z-index: 99999;
		      background: #fff;
		  }
		  .cssload-speeding-wheel {
		      position: absolute;
		      top: calc(50% - 3.5px);
		      left: calc(50% - 3.5px);
		      width: 31px;
		      height: 31px;
		      margin: 0 auto;
		      border: 2px solid rgba(97,100,193,0.98);
		      border-radius: 50%;
		      border-left-color: transparent;
		      border-right-color: transparent;
		      animation: cssload-spin 425ms infinite linear;
		      -o-animation: cssload-spin 425ms infinite linear;
		      -ms-animation: cssload-spin 425ms infinite linear;
		      -webkit-animation: cssload-spin 425ms infinite linear;
		      -moz-animation: cssload-spin 425ms infinite linear;
			}

			@keyframes cssload-spin {
			  100%{ transform: rotate(360deg); transform: rotate(360deg); }
			}

			@-o-keyframes cssload-spin {
			  100%{ -o-transform: rotate(360deg); transform: rotate(360deg); }
			}

			@-ms-keyframes cssload-spin {
			  100%{ -ms-transform: rotate(360deg); transform: rotate(360deg); }
			}

			@-webkit-keyframes cssload-spin {
			  100%{ -webkit-transform: rotate(360deg); transform: rotate(360deg); }
			}

			@-moz-keyframes cssload-spin {
			  100%{ -moz-transform: rotate(360deg); transform: rotate(360deg); }
			}
	  </style>
  
  	@yield('css')
  </head>

  <body>	
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
		@yield('content')
	</body>

	<!-- JS Global Compulsory -->
	<script type="text/ecmascript" src="plugins/jquery-3.2.1.min.js"></script> 
  <script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.js"></script>
	<script type="text/ecmascript" src="plugins//jquery-migrate-3.0.0.js"></script>
	<script type="text/ecmascript" src="plugins/numeral.min.js"></script>
	<script type="text/ecmascript" src="plugins/moment.js"></script>
	<script src="{{ asset('js/myjs/utility.js') }}"></script>
	<script type="text/javascript">
    $( document ).ready(function() {
        $(".preloader").fadeOut();
    });
  </script>

	@yield('js')
</html>
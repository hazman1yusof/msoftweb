<!DOCTYPE html>
<html lang="en">
  <head>
  	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

	<link rel="stylesheet" href="plugins/font-awesome-4.4.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="plugins/AccordionMenu/dist/metisMenu.min.css"> 
	<link rel="stylesheet" href="plugins/bootstrap-3.3.5-dist/css/bootstrap.min.css"> 
	<link rel="stylesheet" href="plugins/jasny-bootstrap/css/jasny-bootstrap.min.css"> 
	<link rel="stylesheet" href="plugins/jquery-ui-1.11.4.custom/jquery-ui.css">
	<link rel="stylesheet" href="css/container.css">

	<style type="text/css">
		.navmenu ul a {
	    color: rgba(44, 52, 63) !important;
	    font-weight: 600 !important;
	  }
	  .navmenu ul li {
	  	background: #ffffff;
	    margin: 4px;
    	border: 1px #565656 solid;
	  }

	</style>

  <title>Medicsoft Enterprise Edition</title>
  </head>

  <body>	
	<div class="navbar navbar-fixed-top">
	<a id="a_open_menu" type="button" class="btn btn-default btn-lg" aria-label="Left Align" style="border-color: white;color: #00608f;float: left;margin: 3px;padding: 10px;" >
	  <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
	</a>
	<a class="navbar-brand" href="#" style="padding: 10px 10px 0px 0px;float: right;">
		<img src="img/logo.jpg" alt="logo" height="30px" width="auto">
	</a>
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<div id="navbar" class="navbar-collapse collapse">

			<h4 style="margin: 20px 0px 0px 0px;float: left;color: #565656;">{{$title}}</h4>
			@if(Auth::user()->dept == '')
			<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
			<ul class="nav navbar-nav navbar-right" style="margin-top: 8px;color: #999">
				<li><h4 style="font-size: 15px">&nbsp;Unit :&nbsp;</h4></li>
				<li>
					<select class="form-control" id="session_unit">
						@foreach ($units as $unit)
					  		<option value="{{$unit->sectorcode}}">{{$unit->description}}</option>
						@endforeach
					</select>
				</li>
			</ul>
			@else
			<ul class="nav navbar-nav navbar-right" style="margin-top: 8px;color: #999">
				<li><h4 style="font-size: 15px">&nbsp;Unit :&nbsp;</h4></li>
				<li>
					<select class="form-control" id="session_unit" readonly>
					  	<option>{{$unit_user}}</option>
					</select>
				</li>
			</ul>
			@endif

			<ul class="nav navbar-nav navbar-right" style="margin-top: 8px;color: #999">
				<li><h4 style="font-size: 15px">&nbsp;Department :&nbsp;</h4></li>
				<li>
					<input type='text' class="form-control" id="session_deptcode" readonly value="{{Session::get('deptcode')}}">
				</li>
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class='dropdown-toggle active' data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<span id='username' style="font-size: 15px">{{Auth::user()->username}}</span>
						<i class="fa fa-user fa-fw"></i>
						<i class="fa fa-caret-down"></i>
					</a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#" >Profile Settings</a></li>
						<li><a href="#" >Close Till</a></li>
						<li class="divider"></li>
						<li><a href="./logout" >Log-out</a></li>
					</ul>
				</li>
			</ul>

		</div>
	</div>
	</div>

	<div id='myNavmenu' class="navmenu navmenu-fixed-left" style="width: 0%;
		background-image: url('./img/carousel/page-hospital2.jpg') !important;
    background-repeat: no-repeat !important;
    background-size: cover !important;
    background-position: left center !important;">
		<!-- <img src="./img/carousel/page-hospital.jpg" alt="Girl in a jacket" style="height: 100%;width: 100%;position: absolute;"> -->
		<ul class="metismenu" id="menu" style="height: 100%;width: 100%;background: rgb(255 255 255 / 20%);">
		{!!$menu!!}		
		</ul>
	</div>

	<div class='page-wrapper' style="width: 100%;padding-top: 55px;">
		<div id="default_page" style="width: 100%;height: 100%;">
			<div id="authdtl_alert_div" class="panel-body">
			</div>
		</div>

		<div id="url_page" style="width: 100wh;height: 100vh;display: none;">
			<iframe id='iframe_page' src='' style="width: 100wh;height: 100vh;border: none;"></iframe>
		</div>
	</div>


</body>

<!-- JS Global Compulsory -->
<script type="text/ecmascript" src="plugins/jquery-3.2.1.min.js"></script> 
<script type="text/ecmascript" src="plugins//jquery-migrate-3.0.0.js"></script>
<script type="text/ecmascript" src="plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script type="text/ecmascript" src="plugins/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
<script type="text/ecmascript" src="plugins/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
<script type="text/ecmascript" src="plugins/AccordionMenu/dist/metisMenu.min.js"></script>    
<script type="text/ecmascript" src="plugins/jquery.dialogextend.js"></script>
<script type="text/ecmascript" src="plugins/numeral.min.js"></script>
<script type="text/ecmascript" src="plugins/moment.js"></script>
<script type="text/ecmascript" src="plugins/velocity.min.js"></script>
<script type="text/ecmascript" src="js/other/authdtl_alert/authdtl_alert.js"></script>

<!-- JS Implementing Plugins -->

<!-- JS Customization -->

<!-- JS Page Level -->
<script type="text/javascript">
	$(document).ready(function () {
		$("#myNavmenu").data('open','false');
		$('#a_open_menu').click(function(){
			if($("#myNavmenu").data('open') == 'false'){
				$("#myNavmenu").data('open','true');
				$("#myNavmenu").animate({ width:"100%" }, "fast");
			}else{
				$("#myNavmenu").data('open','false');
				$("#myNavmenu").animate({ width:"0%" }, "fast");
			}
		});

		$("#myNavmenu a.clickable").click(function(){
			let src = $(this).attr('targetURL');
			open_mobile_page(src);

			$("#myNavmenu").data('open','false');
			$("#myNavmenu").animate({ width:"0%" }, "fast");
		});
	});

	function open_mobile_page(src){
		if(src == ''){
			$('#url_page').hide();
			$('#default_page').show();
			$('#iframe_page').attr('src',src);
		}else{
			$('#url_page').show();
			$('#default_page').hide();
			$('#iframe_page').attr('src',src);
		}
	}
</script>
</html>
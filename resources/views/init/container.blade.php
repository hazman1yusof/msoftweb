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
  <link rel="stylesheet" href="{{ asset('plugins/css/trirand/ui.jqgrid-bootstrap.css') }}" />
	<link rel="stylesheet" href="css/container.css?v=1.2">

	<script type="text/javascript">

    let mql = window.matchMedia("(max-width: 768px)");
    if(mql.matches){
    	window.location.replace("./mobile");
    }

		function disableCloseButton(isClose){
			if(isClose){
				$('button[role=button][title=Close]').prop('disabled',true);
			}else{
				$('button[role=button][title=Close]').prop('disabled',false);
			}
		}
		function changeParentTitle(title){
			var iframe = $('iframe[programid=apptrsc]');
			iframe.dialog( "option", "title", title );
		}
	</script>

    <title>Medicsoft Enterprise Edition</title>
  </head>

  <body>
  <input type="hidden" name="logout_timer" id="logout_timer" value="{{$logout_timer ?? '0'}}">
	<div class="navbar navbar-fixed-top">
		@if($shortcut)
		<a type="button" class="btn btn-default btn-lg" aria-label="Left Align" style="border-radius: 50%;border-color: white;color: #00608f;float: left;margin: 3px 20px;background: rgb(0 0 0 / 4%);" href="./">
		  <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
		</a>
		@endif
		<a class="navbar-brand" href="#" style="padding: 0px 10px 0px 0px;float: right;">
			<img src="img/{{$logo1}}" alt="logo" height="50px" width="auto" id="main_comp_logo">
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
				<h4 class="company_name">{{strtoupper($title)}}</h4>

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
					<li style="max-width: 200px;">
						<div class='input-group'>
							<input id="session_deptcode" name="session_deptcode" type="text" class="form-control" value="{{$dept_desc}}" readonly>
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
				  	</div>
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
							<li><a href="#" id="profilebtn">Profile Settings</a></li>
							<li class="divider"></li>
							<li><a href="./logout" >Log-out</a></li>
						</ul>
					</li>
				</ul>

			</div>
		</div>
	</div>

	<div id='myNavmenu' class="navmenu navmenu-fixed-left">
		<ul class="metismenu" id="menu">
		{!!$menu!!}		
		</ul>
	</div>

	<div class='page-wrapper'>
		<div id='announcement'>

		</div>

		<div id="authdtl_alert_div" class="row">
		</div>

		<!-- <div class='col-lg-3 col-md-6'>
			<div class='panel panel-primary'>
				<div class='panel-heading'>
					<div class='row'>
						<div class='col-xs-2'>
							<i class='fa fa-inbox fa-5x'></i>
						</div>
						<div class='col-xs-10 text-right'>
							<div class='huge'>
								<button name='refresh' class='btn btn-default btn-xs'><i class='fa fa-refresh fa-spin'></i></button>
								 Till 
							</div>
							<div><b>Status: </b><span name='tillstatus'>C</span></div>
							<div><b>Till Code: </b><span name='tillcode'>-</span></div>
							<div><b>Open On: </b><span name='opendate'>-</span></div>
						</div>
					</div>
				</div>
					<div class='panel-footer'>
						<a href="#" name='closetill' onclick="return false">
							<span class='pull-left'>Close Till</span>
							<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
							<div class='clearfix'></div>
						</a>
						<a href="#" name='opentill' onclick="return false">
							<span class='pull-left'>Open Till</span>
							<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
							<div class='clearfix'></div>
						</a>
					</div>
			</div>
		</div>

		<div class='col-lg-3 col-md-6'>
			<div class='panel panel-green'>
				<div class='panel-heading'>
					<div class='row'>
						<div class='col-xs-2'><i class='fa fa-truck fa-5x'></i></div>
						<div class='col-xs-10 text-right'>
							<div class='huge'><button class='btn btn-default btn-xs'><i class='fa fa-refresh fa-spin'></i></button> DO</div>
							<div><b>Status: </b><span>Open</span></div>
							<div><b>Till Code: </b><span>Till 1</span></div>
							<div><b>Open On: </b><span>2016-01-28 15:53:15</span></div>
						</div>
					</div>
				</div>
					<div class='panel-footer'>
						<a href="#">
							<span class='pull-left'>View Detail</span>
							<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
							<div class='clearfix'></div>
						</a>
					</div>
			</div>
		</div>

		<div class='col-lg-3 col-md-6'>
			<div class='panel panel-yellow'>
				<div class='panel-heading'>
					<div class='row'>
						<div class='col-xs-2'><i class='fa fa-shopping-cart fa-5x'></i></div>
						<div class='col-xs-10 text-right'>
							<div class='huge'><button class='btn btn-default btn-xs'><i class='fa fa-refresh fa-spin'></i></button> PO</div>
							<div><b>Status: </b><span>Open</span></div>
							<div><b>Till Code: </b><span>Till 1</span></div>
							<div><b>Open On: </b><span>2016-01-28 15:53:15</span></div>
						</div>
					</div>
				</div>
					<div class='panel-footer'>
						<a href="#">
							<span class='pull-left'>View Detail</span>
							<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
							<div class='clearfix'></div>
						</a>
					</div>
			</div>
		</div>

		<div class='col-lg-3 col-md-6'>
			<div class='panel panel-red'>
				<div class='panel-heading'>
					<div class='row'>
						<div class='col-xs-2'><i class='fa fa-exclamation-triangle fa-5x'></i></div>
						<div class='col-xs-10 text-right'>
							<div class='huge'><button class='btn btn-default btn-xs'><i class='fa fa-refresh fa-spin'></i></button> Alert</div>
							<div><b>Status: </b><span>Open</span></div>
							<div><b>Till Code: </b><span>Till 1</span></div>
							<div><b>Open On: </b><span>2016-01-28 15:53:15</span></div>
						</div>
					</div>
				</div>
					<div class='panel-footer'>
						<a href="#">
							<span class='pull-left'>View Detail</span>
							<span class='pull-right'><i class='fa fa-arrow-circle-right'></i></span>
							<div class='clearfix'></div>
						</a>
					</div>
			</div>
		</div> -->

		<!-- <div class="panel panel-info col-xs-3" id='chat-panel' style="position:fixed;bottom:0;right:0;padding: 0;width:105px;">
			<div class="panel-heading panel-primary">
				<span class='whenshow' style='display: none;'>
					<i class="fa fa-comments-o" aria-hidden="true"></i>
					<span class='chat-title'> Chat with all</span> 
					<button type="button" class="btn btn-default btn-xs pull-right chat-hide" >
						<i class="fa fa-chevron-down"></i>
					</button>
				</span>
				<span class='whenhide'>
					<i class="fa fa-comments-o" aria-hidden="true"></i> Chat 
					<button type="button" class="btn btn-default btn-xs chat-show">
						<i class="fa fa-chevron-up"></i>
					</button>
				</span>
			</div>
			<div class="panel-body">
				<ul class='chat'>
				</ul>
			</div>
			<div class="panel-footer">
				<div class="input-group">
					<input type='hidden' id='chatmsto' value="all">
					<input type="text" class="form-control input-sm" id='chattext'>
					<div class="input-group-btn">
					<button class="btn btn-warning btn-sm" type="button" id='chatsend'>Send To</button>
					<button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="caret caret-up"></span>
						<span class="sr-only">Toggle Dropdown</span>
					</button>
					<ul class="dropdown-menu drop-up">
						<li><a>all</a></li>
                    	<li class="divider"></li>
					</ul>
					</div>
				</div>
			</div>
		</div> -->

	</div>


</body>

<!-- JS Global Compulsory -->
<script type="text/ecmascript" src="plugins/jquery-3.2.1.min.js"></script> 
<script type="text/ecmascript" src="plugins//jquery-migrate-3.0.0.js"></script>
<script type="text/ecmascript" src="plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script type="text/ecmascript" src="plugins/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
<script type="text/ecmascript" src="plugins/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
<script type="text/ecmascript" src="{{ asset('plugins/trirand/i18n/grid.locale-en.js') }}"></script>
<script type="text/ecmascript" src="{{ asset('plugins/trirand/jquery.jqGrid.min.js') }}"></script>
<script type="text/ecmascript" src="plugins/AccordionMenu/dist/metisMenu.min.js"></script>    
<script type="text/ecmascript" src="plugins/jquery.dialogextend.js"></script>
<script type="text/ecmascript" src="plugins/numeral.min.js"></script>
<script type="text/ecmascript" src="plugins/moment.js"></script>
<script type="text/ecmascript" src="plugins/velocity.min.js"></script>
<script type="text/ecmascript" src="js/other/authdtl_alert/authdtl_alert.js?v=1.7"></script>
<script type="text/ecmascript" src="js/myjs/menu.js?v=1.1"></script>
<script src="{{ asset('js/myjs/utility.js?v=1.8') }}"></script>


<script>
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
	$(document).ready(function(){
    Menu.init_menu();
    Menu.init_announce();

    $('#main_comp_logo').dblclick(function(){
    	clear_storage();
    });

    // Menu.init_card();
		var session_deptcode = new ordialog(
			'session_deptcode', 'sysdb.department', '#session_deptcode', 'errorField',
			{
				colModel: [
					{ label: 'Department ID', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
					{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
					{ label: 'Sector', name: 'sector', width: 200, classes: 'pointer' },
				],
				urlParam: {
							filterCol:['compcode','recstatus'],
							filterVal:['session.compcode','ACTIVE']
						},
				ondblClickRow: function () {
					let data = selrowData('#' + session_deptcode.gridname);
					window.location.replace('./sessionUnit?deptcode='+data.deptcode);

				},
				gridComplete: function(obj){
					var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						// $('#'+obj.dialogname).dialog('close');
					}
				}
			},{
				title: "Select Creditor",
				open: function () {
					session_deptcode.urlParam.filterCol = ['compcode','recstatus'];
					session_deptcode.urlParam.filterVal = ['session.compcode','ACTIVE'];
				}
			},'urlParam','radio','tab'
		);
		session_deptcode.makedialog(true);

    // $("#session_deptcode").change(function(){
    // 	$.post( '/sessionUnit', {_token:$('#_token').val(), unit:$(this).val()}, function( data ) {
				
		// 	}).fail(function(data) {
				
		// 	}).success(function(data){
				
		// 	});
	  // });

		var timeoutId;
		$("#myNavmenu").hover(function() {
			if (!timeoutId) {
				timeoutId = window.setTimeout(function() {
					timeoutId = null; // EDIT: added this line
		        	$( "#myNavmenu" ).animate({ width:"20%" }, 'fast' ,"linear", function() {
		        		$( ".lilabel" ).show();
		        	});
		        	// $( ".navmenu" ).velocity({ width:"20%" }, 130, "linear", function() { 
		        	// 	$( ".lilabel" ).velocity("fadeIn", { duration: 130 })
		        	// });
				}, 300);
			}
		},function () {
			if (timeoutId) {
				window.clearTimeout(timeoutId);
				timeoutId = null;
			}else{
				// $( ".lilabel" ).velocity("fadeOut", { duration: 0 })
				// $( ".navmenu" ).velocity({ width:"7%" }, 130, "linear", function() { 
				// 	$( ".lilabel" ).velocity("fadeOut", { duration: 0 })
	   //      	});
	        	$( ".lilabel" ).hide();
	        	$( "#myNavmenu" ).animate({ width:"8%" }, 'fast' ,"linear", function() {
	        		$( ".lilabel" ).hide();
	        	});
			}
		});

		$('#profilebtn').click(function(){
			Menu.new_dialog('profile_setings','user_profile','User Profile');
		});	

		// var mychat = new chat();

		$('#chatsend').click(function(){
			mychat.sendmessage();
		});

		$('#chattext').keyup(function(e){
			if(e.keyCode == 13){
				mychat.sendmessage();
			}
		});

		function chat(username){
			this.date = null;
			this.interval;

			$('#chat-panel .chat-hide').click(function(){
				$("#chat-panel .panel-body, #chat-panel .panel-footer, #chat-panel .panel-heading span").hide();
				$('#chat-panel').css( "width",'105px' );
				$('#chat-panel .whenhide').show();
				stopinterval();
			});
			$('#chat-panel .chat-show').click(function(){
				$("#chat-panel .panel-body, #chat-panel .panel-footer, #chat-panel .panel-heading span").show();
				$('#chat-panel').css( "width",'' );
				$('#chat-panel .panel-heading').css( "padding",'' );
				$('#chat-panel .whenhide').hide();
				setinterval();
				focusbtm();
			});

			function setinterval(){
				mychat.getmessage();
				this.interval = setInterval(function(){
					mychat.getmessage();
				}, 3000);
			}

			function stopinterval(){
				clearInterval(this.interval);
			}

			getalluser();
			function getalluser(){
				var param={
					action:'get_value_default',
					field: ['username'],
					table_name:'sysdb.users',
					table_id:'idno'
				}
				$.get( "assets/php/entry.php?"+$.param(param), function( data ) {
						
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.rows)){
						data.rows.forEach(function(element){
							if($('#username').text()!=element.username)
							$('#chat-panel .drop-up ').append("<li><a>"+element.username+"</a></li>")
						});
						$('#chat-panel .drop-up li').click(function(){
							$('#chat-panel .whenshow .chat-title').text(' Chat with '+$(this).text());
							$('#chatmsto').val($(this).text());
						});
					}
				});
			}

			this.getmessage = function(){
				self=this;
				param={
					action:'chat',
					oper:'get',
					date:self.date
				}

				$.get( "assets/php/entry.php?"+$.param(param), function(data) {
						
				},'json').done(function(data) {
					if(!$.isEmptyObject(data.rows)){
						self.date = data.rows[0].datesend;
						data.rows.reverse();
						data.rows.forEach(function(element){
							if(element.msto == self.username){
								element.name = "<small>Whisper from</small> "+element.name;
							}
							if(element.msfrom == self.username){
								$('#chat-panel .chat ').append(
									"<li><div class='chat-body'><div class='header'>"
									+"<small class='text-muted pull-right'><i class='fa fa-clock-o fa-fw'></i>"+
										datediff(element.datesend)
									+"</small><strong class='text-primary'>"+
										element.name
									+"</strong> to "+element.msto+"</div><p>"+
										element.remark
									+"</p></div></li>");	
							}else{
								$('#chat-panel .chat ').append(
									"<li><div class='chat-body'><div class='header'>"
									+"<small class='text-muted'><i class='fa fa-clock-o fa-fw'></i>"+
										datediff(element.datesend)
									+"</small><strong class='pull-right'>"+
										element.name
									+"</strong></div><p>"+
										element.remark
									+"</p></div></li>");
							}
						});
						focusbtm();
					}
				});
			}

			function focusbtm(){
				// var btm = $('#chat-panel .panel-body')[0].scrollHeight - $('#chat-panel .panel-body')[0].clientHeight
				// $('#chat-panel .panel-body').animate({
				// 	scrollTop: btm
				// }, 'slow');
				// $( ".navmenu" ).velocity({ width:"7%" }, 130, "linear", function() { 
				// 	$( ".lilabel" ).velocity("fadeOut", { duration: 0 })
				// });
				$('#chat-panel .panel-body').velocity("scroll", { 
					container: $("#chat-panel .panel-body"),
					duration: 1500, 
					easing: "linear",
					offset: $('#chat-panel .panel-body')[0].scrollHeight
				});
				// $('#chat-panel .panel-body').velocity({scrollTop:897}, 897, "linear");
			}

			function datediff(date){
				var now = moment(new Date()); //todays date
				var end = moment(date); // another date
				var duration = moment.duration(end.diff(now));
				return duration.humanize(true);
			}

			this.sendmessage = function(){
				var param={
					action:'chat',
					oper:'add'
				}

				if($('#chattext').val().trim()!=""){
					$.post( "assets/php/entry.php?"+$.param(param),
						{msto:$('#chatmsto').val(),remark:$('#chattext').val()}, 
						function( data ) {
							
						}
					).fail(function(data) {
						alert('Error');
					}).success(function(data){
						mychat.getmessage();
						$('#chattext').val("");
					});
				}
			}
		}
	});
  function idle_logout() {
  	console.log('logging out');
  	var logout_timer = parseInt($('#logout_timer').val());
    if(logout_timer != 0){
  		window.location.replace("./logout");
    }
  }

  let idle_t; // must be declared here
  function idle_resetTimer() {
    clearTimeout(idle_t); // global function
    var logout_timer = parseInt($('#logout_timer').val());
    if(logout_timer != 0){
    	idle_t = setTimeout(idle_logout, logout_timer);
    }
  }

	function noIdlingHere() {
	    function resetTimer() {
	    	idle_resetTimer();
	    }

	    window.addEventListener('load', resetTimer, true);
	    window.addEventListener('mousemove', resetTimer, true);
	    window.addEventListener('mousedown', resetTimer, true);
	    window.addEventListener('touchstart', resetTimer, true);
	    window.addEventListener('touchmove', resetTimer, true);
	    window.addEventListener('click', resetTimer, true);
	    window.addEventListener('keydown', resetTimer, true);
	    window.addEventListener('scroll', resetTimer, true);
	    window.addEventListener('wheel', resetTimer, true);
	}
	noIdlingHere();

	function clear_storage(){
		if(confirm('Do You want to clear storage caching?')){
			localStorage.clear();
			location.reload();
		}
	}
</script>

</html>
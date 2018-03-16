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

	<script type="text/javascript">
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
	<div class="navbar navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#" style="padding-top: 0">
				<img src="img/uitmpsc.png" alt="logo" height="50px" width="auto">
			</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class='dropdown-toggle active' data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<span id='username'></span>
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

	<div id='myNavmenu' class="navmenu navmenu-fixed-left">
		<ul class="metismenu" id="menu">
		{!!$menu!!}		
		</ul>
	</div>

	<div class='page-wrapper'>
		<div id='announcement'>

		</div>

		<div id='cardTill' class='col-lg-3 col-md-6'>
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
		</div>

		<div class="panel panel-info col-xs-3" id='chat-panel' style="position:fixed;bottom:0;right:0;padding: 0;width:105px;">
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
		</div>

	</div>


</body>

<!-- JS Global Compulsory -->
<script type="text/ecmascript" src="plugins/jquery-3.2.1.min.js"></script> 
<script src="https://code.jquery.com/jquery-migrate-3.0.0.js"></script>
<script type="text/ecmascript" src="plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script type="text/ecmascript" src="plugins/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
<script type="text/ecmascript" src="plugins/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
<script type="text/ecmascript" src="plugins/AccordionMenu/dist/metisMenu.min.js"></script>    
<script type="text/ecmascript" src="plugins/jquery.dialogextend.js"></script>
<script type="text/ecmascript" src="plugins/numeral.min.js"></script>
<script type="text/ecmascript" src="plugins/moment.js"></script>
<script type="text/ecmascript" src="plugins/velocity.min.js"></script>

<!-- JS Implementing Plugins -->
<script src="js/myjs/menu.js"></script>

<!-- JS Customization -->

<!-- JS Page Level -->

<script>
	jQuery(document).ready(function() 
    {	
        Menu.init_menu();
        Menu.init_announce();
        // Menu.init_card();

        var timeoutId;
		$(".navmenu").hover(function() {
			if (!timeoutId) {
				timeoutId = window.setTimeout(function() {
					timeoutId = null; // EDIT: added this line
		        	$( ".navmenu" ).animate({ width:"20%" }, 'fast' ,"linear", function() {
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
	        	$( ".navmenu" ).animate({ width:"7%" }, 'fast' ,"linear", function() {
	        		$( ".lilabel" ).hide();
	        	});
			}
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
		
		
</script>


  
</html>
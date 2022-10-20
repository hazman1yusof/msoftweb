<!DOCTYPE html>

<html lang="en">
<head>
	
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

		<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.8/dist/semantic.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/se/dt-1.11.3/datatables.min.css"/>
 
		<script src="https://cdn.jsdelivr.net/npm/jquery@3.3.1/dist/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.8/dist/semantic.min.js"></script>
		<script src="https://cdn.datatables.net/v/se/dt-1.11.3/datatables.min.js"></script>

	@yield('css')
	
    <style>
      body{
          background: #e7fffe78 !important;
      }
      .sidemenu{
          /*background-color: #284255 !important;*/
      }
      .sidemenu a{
          padding: 10px 20px !important;
      }
      .ui.menu a.header  {
          /*background-color:;*/
      }
      .ui.menu a.header:hover  {
          background-color: rgba(181, 181, 181, 1) !important;
      }
      .sidemenu a.active{
          background: rgba(0,0,0, 0.5) !important;
      }
      .container_sem {
          padding-right:15px; 
          padding-top:15px;
          padding-bottom:15px;
          padding-left: 95px;
      }
      .ui.sidebar.inverted.vertical.menu {
          padding-top:90px;
      }
      .ui.left.fixed.vertical.icon.menu {
              display: none;
      }
      .container_sem {
          padding-left: 25px;
          padding-right: 25px;
          padding-top: 10px;
      }
      /*#sidemenu_topmenu{
          height: 32px;
      }
      @media (max-width: 768px) {
          .ui.left.fixed.vertical.icon.menu {
              display: none;
          }
          .container {
              padding-left: 15px;
              padding-top: 95px;
          }
      }
      @media (min-width: 769px) {
          .ui.fixed.top.menu{
              display: none;
          }
          .container {
          }
      }*/
      .avatar-circle {
          width: 40px;
          height: 40px;
          background-color: azure;
          text-align: center;
          border-radius: 50%;
          -webkit-border-radius: 10%;
          -moz-border-radius: 10%;
          border: solid thin #d4d4d5;
      }
      .initials {
          position: relative;
          top: 10px; /* 25% of parent */
          font-size: 20px; /* 50% of parent */
          line-height: 20px; /* 50% of parent */
          color: white;
          font-family: "Courier New", monospace;
          font-weight: bold;
      }
      .admin_color{
          background-color: rgb(242, 113, 28);
      }
      .user_color{
          background-color: rgb(0, 181, 173);
      }

      a.disabled {
          pointer-events: none !important;
          cursor: default;
      }

      div [class*="left floated"] {
        float: left;
        margin-left: 0.25em;
      }

      div [class*="right floated"] {
        float: right;
         margin-right: 0.25em;
      }
      #toTop {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 101;
        background-color: #284255;
      }
      #toTop i {
        font-size: 25px;
        color: #f7fdff;
      }
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
			@yield('style')
 		</style>	
  <title>@yield('title')</title>

	<script type="text/javascript">
      $( document ).ready(function() {
          $(".preloader").fadeOut();
      });
  </script>

	@yield('js')
</head>
<body>
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>

    <div class="pusher container_sem" id="content">
        @yield('content')
    </div>

</body>

	@yield('scripts')
</html>
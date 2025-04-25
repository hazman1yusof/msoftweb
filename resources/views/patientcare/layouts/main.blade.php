<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">

        <title>Customer Support</title>

        <link rel="stylesheet" type="text/css" href="{{ asset('patientcare/css/main.css') }}">
        @yield('stylesheet')

        <style type="text/css">
            .ui.vertical.menu .item>i.icon {
                width: 1.18em !important;
                float: left !important;
                margin: 0 .5em 0 .5em !important;
            }
            body{
                /*height: auto !important;*/
            }
            @yield('style')

            .preloader {
                width: 100%;
                height: 100%;
                top: 0;
                position: fixed;
                z-index: 99999;
                background: #fff;
            }
            .wrap{
                word-wrap: break-word;
                white-space: pre-line !important;
                vertical-align: top !important;
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
            .ui.fixed.menu, .ui[class*="top fixed"].menu {
                height: 30px;
            }
            .sidebar.inverted.icon{
                height: 20px;
                width: 10px;
            }

        </style>

        @yield('css')
        @yield('header')



    </head>
    <body>
        <div class="preloader">
            <div class="cssload-speeding-wheel"></div>
        </div>
        <div class="pusher container_sem" id="content">
            @yield('content')
        </div>
    </body>


    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="{{ asset('patientcare/assets/moment.js') }}"></script>
    <script type="text/ecmascript" src="{{ asset('plugins/numeral.min.js') }}"></script>
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.8/dist/semantic.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.8.8/dist/semantic.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.css" rel="stylesheet">
    <script src="{{ asset('patientcare/js/main.js') }}"></script>

    <script type="text/javascript">
        $( document ).ready(function() {
            $(".preloader").fadeOut();
        });
    </script>  
    
    @yield('js')
</html>
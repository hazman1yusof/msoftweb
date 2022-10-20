<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>@yield('title', 'Stisla Laravel') &mdash; {{ env('APP_NAME') }}</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- General CSS Files -->

  @yield('header')
  
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">


  <!-- CSS Libraries -->
  @yield('stylesheet')

  <!-- Template CSS -->
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/Semantic/semantic.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/Summernote 0.8.9/summernote-lite.css') }}">
  <link rel="stylesheet" href="{{ asset('assets-stisla/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets-stisla/css/components.css')}}">

  <style type="text/css">
    @yield('css')
    table.dataTable tbody tr.selected a, table.dataTable tbody th.selected a, table.dataTable tbody td.selected a {
      color: white !important;
    }
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
</head>

<body class="sidebar-mini">

      @if(!Request::is('login'))
          @if(!Request::is('upload'))
              @include('layouts.navs')
          @endif
      @endif
      <div class="pusher container_sem" id="content">
          @yield('content')
      </div>

  
</body>


<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js" integrity="sha512-3PRVLmoBYuBDbCEojg5qdmd9UhkPiyoczSFYjnLhFb2KAFsWWEMlAPt0olX1Nv7zGhDfhGEVkXsu51a55nlYmw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.4.1/chart.min.js" integrity="sha512-5vwN8yor2fFT9pgPS9p9R7AszYaNn0LkQElTXIsZFCL7ucT8zDCAqlQXDdaqgA1mZP47hdvztBMsIoFxq/FyyQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{ asset('js/app.js') }}?{{ uniqid() }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

<script src="{{ asset('assets/Semantic/semantic.min.js') }}"></script>
<script src="{{ asset('js/utility.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('assets-stisla/js/stisla.js') }}"></script>
<script src="{{ asset('assets-stisla/js/scripts.js') }}"></script>
<script src="{{ asset('assets-stisla/js/custom.js') }}"></script>
@yield('scripts')

</html>

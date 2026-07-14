<!DOCTYPE html>

<html lang="en">
<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

<!--     <link rel="stylesheet" href="{{asset('plugins/bootstrap-3.3.5-dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-3.3.5-dist/css/bootstrap-theme.css')}}"> -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <!-- <link rel="stylesheet" href="{{asset('plugins/bootgrid/css/jquery.bootgrid.css')}}"> -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/se/dt-1.11.3/datatables.min.css"/>
    <link rel="stylesheet" href="{{asset('plugins/font-awesome-4.4.0/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/form-validator/theme-default.css')}}">
    
    @yield('css')

    <title>@yield('title')</title>

</head>

<body>
    @yield('body')
</body>


<script type="text/ecmascript" src="{{asset('plugins/jquery-3.2.1.min.js')}}"></script>
<script type="text/ecmascript" src="{{asset('plugins/jquery-migrate-3.0.0.js')}}"></script>
<script type="text/ecmascript" src="{{asset('plugins/trirand/i18n/grid.locale-en.js')}}"></script>
<script type="text/ecmascript" src="{{asset('plugins/trirand/jquery.jqGrid.min.js')}}"></script>
<!-- <script type="text/ecmascript" src="{{asset('plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js')}}"></script>
<script type="text/javascript">$.fn.modal.Constructor.prototype.enforceFocus = function() {};</script> -->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.js"></script>

<script type="text/ecmascript" src="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script>
<script type="text/ecmascript" src="{{asset('plugins/form-validator/jquery.form-validator.min.js')}}"></script>

<script type="text/ecmascript" src="{{asset('plugins/numeral.min.js')}}"></script>
<script type="text/ecmascript" src="{{asset('plugins/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('js/myjs/utility.js')}}"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/v/se/dt-1.11.3/datatables.min.js"></script>
<script type="text/javascript" src="{{asset('plugins/jquery-validator/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/jquery-validator/additional-methods.min.js')}}"></script>

<script type="text/javascript" src="{{asset('js/myjs/modal-fix.js')}}"></script>

@yield('js')

<script>
    @yield('scripts')
</script>
</html>
<!DOCTYPE html>

<html lang="en">
<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" href="{{asset('plugins/bootstrap-3.3.5-dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-3.3.5-dist/css/bootstrap-theme.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/bootgrid/css/jquery.bootgrid.css')}}">
    <link rel="stylesheet" href="{{asset('https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('https://cdn.datatables.net/rowgroup/1.1.2/css/rowGroup.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/font-awesome-4.4.0/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/form-validator/theme-default.css')}}">

    <link rel="stylesheet" href="{{asset('plugins/css/trirand/ui.jqgrid-bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/landing.css?v=1')}}">
    
    <title>@yield('title')</title>

</head>


<body>
    @yield('body')
</body>


<script type="text/ecmascript" src="{{asset('plugins/jquery-3.2.1.min.js')}}"></script>
<script type="text/ecmascript" src="{{asset('plugins/jquery-migrate-3.0.0.js')}}"></script>
<script type="text/ecmascript" src="{{asset('plugins/trirand/i18n/grid.locale-en.js')}}"></script>
<script type="text/ecmascript" src="{{asset('plugins/trirand/jquery.jqGrid.min.js')}}"></script>
<script type="text/ecmascript" src="{{asset('plugins/bootstrap-3.3.5-dist/js/bootstrap.min.js')}}"></script>
<script type="text/javascript">$.fn.modal.Constructor.prototype.enforceFocus = function() {};</script>
<script type="text/ecmascript" src="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script>
<script type="text/ecmascript" src="{{asset('plugins/form-validator/jquery.form-validator.min.js')}}"></script>

<script type="text/ecmascript" src="{{asset('plugins/numeral.min.js')}}"></script>
<script type="text/ecmascript" src="{{asset('plugins/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('js/myjs/utility.js')}}"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js"></script>
<script type="text/javascript" src="{{asset('plugins/jquery-validator/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/jquery-validator/additional-methods.min.js')}}"></script>

<script type="text/javascript" src="{{asset('js/myjs/modal-fix.js')}}"></script>

@yield('js')

<script>
    @yield('scripts')
</script>
</html>
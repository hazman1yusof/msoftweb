@extends('layouts.main')

@section('title', 'Antenatal Chart ')

@section('body')
<div id="content">
	<div class="demo-container">
		<div id="placeholder" class="demo-placeholder" style="height: 500px"></div>
	</div>
</div>
@endsection


@section('scripts')
	<script language="javascript" type="text/javascript" src="js/hisdb/antenatal/antenatal_chart.js"></script>
	<script language="javascript" type="text/javascript" src="plugins/flot/jquery.flot.js"></script>
	<script language="javascript" type="text/javascript" src="plugins/flot/jquery.flot.errorbars.js"></script>
	<script language="javascript" type="text/javascript" src="plugins/flot/jquery.flot.navigate.js"></script>
	<script language="javascript" type="text/javascript" src="plugins/flot/jquery.flot.crosshair.js"></script>
	<script language="javascript" type="text/javascript" src="plugins/flot/jquery.flot.symbol.js"></script>
@endsection
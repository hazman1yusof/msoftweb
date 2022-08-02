@extends('layouts.main')

@section('title', 'Antenatal Chart ')

@section('style')
	.demo-container {
	    box-sizing: border-box;
	    width: 850px;
	    height: 450px;
	    padding: 20px 15px 15px 15px;
	    margin: 15px auto 30px auto;
	    border: 1px solid #ddd;
	    background: #fff;
	    background: linear-gradient(#f6f6f6 0, #fff 50px);
	    background: -o-linear-gradient(#f6f6f6 0, #fff 50px);
	    background: -ms-linear-gradient(#f6f6f6 0, #fff 50px);
	    background: -moz-linear-gradient(#f6f6f6 0, #fff 50px);
	    background: -webkit-linear-gradient(#f6f6f6 0, #fff 50px);
	    box-shadow: 0 3px 10px rgb(0 0 0 / 15%);
	    -o-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
	    -ms-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
	    -moz-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
	    -webkit-box-shadow: 0 3px 10px rgb(0 0 0 / 10%);
	    -webkit-tap-highlight-color: rgba(0,0,0,0);
	    -webkit-tap-highlight-color: transparent;
	    -webkit-touch-callout: none;
	    -webkit-user-select: none;
	    -khtml-user-select: none;
	    -moz-user-select: none;
	    -ms-user-select: none;
	    user-select: none;
	}

	.demo-placeholder {
	    width: 100%;
	    height: 100%;
	    font-size: 14px;
	}

@endsection

@section('body')
<div id="content">
	<div class="demo-container">
		<div id="placeholder" class="demo-placeholder"></div>
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
	<<!-- script language="javascript" type="text/javascript" src="plugins/flot/jquery.canvaswrapper.js"></script>
	<script language="javascript" type="text/javascript" src="plugins/flot/jquery.colorhelpers.js"></script>
	<script language="javascript" type="text/javascript" src="plugins/flot/jquery.flot.axislabels.js"></script> -->
@endsection
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Cardiograph</title>
	<link href="{{ asset('css/cardiograph.css') }}" rel="stylesheet" type="text/css">

	<link href="{{ asset('plugins/flot/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<script src="{{ asset('plugins/flot/source2/jquery.colorhelpers.js') }}"></script>
	<script src="{{ asset('plugins/flot/source2/jquery.flot.js') }}"></script>
	<script src="{{ asset('plugins/flot/source2/jquery.flot.errorbars.js') }}"></script>
	<script src="{{ asset('plugins/flot/source2/jquery.flot.navigate.js') }}"></script>
	<script src="{{ asset('plugins/flot/source2/jquery.flot.crosshair.js') }}"></script>
	<script src="{{ asset('plugins/flot/source2/jquery.flot.symbol.js') }}"></script>
	<script src="{{ asset('js/cardiograph.js') }}"></script>
    <script src="{{ asset('assets/moment.js') }}"></script>
	<script src="{{ asset('plugins/flot/bootstrap/js/bootstrap.min.js') }}"></script>

    <script type="text/javascript">
    	var patcardio = [];

    	@foreach ($patcardio as $obj)
		    patcardio.push({
		    	mrn:'{{$obj->mrn}}',
		    	date:'{{$obj->date}}',
		    	exercise:'{{$obj->exercise}}',
		    	speed:'{{$obj->speed}}',
		    	hr:'{{$obj->hr}}',
		    	bp_s:'{{$obj->bp_s}}',
		    	bp_d:'{{$obj->bp_d}}',
		    	rpe:'{{$obj->rpe}}',
		    	sp02:'{{$obj->sp02}}',
		    	weight:'{{$obj->weight}}',
		    	meds:'{{$obj->meds}}',
		    	remark:'{{$obj->remark}}',
		    	rpm:'{{$obj->rpm}}'
		    });
		@endforeach
    </script>


</head>
<body>

	<div id="content">

		<div class="demo-container">
			<div id="placeholder" class="demo-placeholder"></div>
		</div>

	</div>

</body>
</html>

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

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

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

		<div class="panel panel-default">
		  <div class="panel-heading" style="position: relative;">
		  	<b>NAME: {{$bio->Name}}</b><br>
	        MRN: {{$bio->MRN}}
	        SEX: {{$bio->Sex}}
	        DOB: {{$bio->DOB}}
	        AGE: 
	        RACE: {{$bio->RaceCode}}
	        RELIGION: {{$bio->Religion}}
	        OCCUPATION: {{$bio->OccupCode}}
	        CITIZENSHIP: {{$bio->Citizencode}}
	        AREA: {{$bio->AreaCode}}

	        <a class="btn btn-default" style="position:absolute;top: 10px;right: 10px;">
			  <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
			</a>
		  </div>
		</div>

		<div class="demo-container">
			<div id="placeholder" class="demo-placeholder"></div>
		</div>

	</div>

</body>
</html>

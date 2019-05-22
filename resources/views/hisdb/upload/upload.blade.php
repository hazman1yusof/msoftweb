@extends('layouts.main')

@section('title', 'Upload')

@section('body')

<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
<input type="hidden" name="mrn" id="mrn" value="{{Request::get('mrn')}}">
<input type="hidden" name="episno" id="episno" value="{{Request::get('episno')}}">
<input id="app_url" name="app_url" type="hidden" value="{{ env('APP_URL') }}">

<div class='row' style="padding-top: 15px">
	<div class="panel panel-default">
	    <div class="panel-body">
			<table class="table table-hover table-bordered" id='episodeList'>
				<thead>
					<tr>
						<th>Episode No.</th>
						<th>Episode Type</th>
						<th>Register date</th>
						<th>MRN</th>
						<th>Upload</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>

		</div>
	</div>
</div>

@endsection

@section('scripts')

	<script src="js/hisdb/upload/upload.js"></script>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
	<script type="text/javascript" src="plugins/datatables/js/jquery.datatables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

@endsection
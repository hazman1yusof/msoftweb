@extends('layouts.main')

@section('title', 'Review')

@section('style')

.imgcontainer {
  position: relative;
  width: fit-content;
}

.imgcontainer img {
}

.imgcontainer .btn {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  background-color: #55554452;
  color: white;
  font-size: 16px;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  text-align: center;
}

.imgcontainer .btn:hover {
  background-color: black;
}

#bio .col-md-1 {
  width: 58px;
} 

@endsection

@section('content')

<div class='row' style="padding-top: 15px">
	<div class="panel panel-default">
	    <div class="panel-body">
			<table class="ui celled table" id='tablePreview'>
				<thead>
					<tr>
						<th>ID</th>
						<th>Date</th>
						<th>Remark</th>
						<th>File Preview</th>
						<th>MRN</th>
						<th>Add User</th>
						<th>Add Date</th>
						<th>Download</th>
						<th>type</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

@endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/DataTables/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.semanticui.min.css">
@endsection

@section('js')
	<script src="{{ asset('js/preview.js') }}"></script>
	<script src="{{ asset('assets/DataTables/datatables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.semanticui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.js"></script>
@endsection
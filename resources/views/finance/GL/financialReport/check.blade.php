@extends('layouts.main')

@section('title', 'Check Balance Sheet')

@section('style')
@endsection('style')

@section('body')

<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
<div class="container mycontainer">
  <div class="row">
		<div class="col-md-12">
			<div class="panel panel-default" style="width: 90%;margin: auto;margin-top: 10px;">
				<div class="panel-heading">Interface Check <b>{{$rptname}}</b> Year : <b>{{Request::get('year')}}</b> Month: <b>{{$monthto}}</b></div>
				<div class="panel-body" style="padding-left: 35px !important;">
					<div class='col-md-12' style="padding:0px">
						<table id="myTable" class="display">
						    <thead>
						        <tr>
						            <th>{{$rptname}} GL Account</th>
						            <th>{{$rptname}} balance</th>
						            <th>Report GL Account</th>
						            <th>Report Balance</th>
						            <th>Different</th>
						        </tr>
						    </thead>
						    <tbody>
						    	@foreach($table_data as  $table)
						        <tr>
						            <td>{{$table->glaccount}}</td>
						            <td>{{$table->pbalance}}</td>
						            <td>{{$table->glaccount}}</td>
						            <td>{{$table->pytd}}</td>
						            <td>{{$table->diff}}</td>
						        </tr>
						    	@endforeach
						    </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
  </div> 
</div>
		
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.css" />
<script src="https://cdn.datatables.net/2.3.5/js/dataTables.js"></script>

<script>

$(document).ready(function () {
	$('#myTable').DataTable();
});
		
</script>

@endsection
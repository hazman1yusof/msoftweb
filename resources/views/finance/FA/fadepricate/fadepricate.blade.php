@extends('layouts.main')

@section('title', 'Fa Depreciation ')

@section('body')
<div class="container">
	<div class="jumbotron" style="margin-top: 50px">
	  <h2>Fixed Asset Depreciation process</h2>
	  <h4>This function perform the depreciation process. It calculates the amount of depreciation for asset into the year-to-date figures. The current period is then closed and advance the period the next period. Make sure all the asset has been registed in current period.</h4>

	 	<table class="table" width="50%">
		    <tbody>
		      <tr class="success">
		        <th width="20%">Year</th>
		        <td>{{$facontrol->year}}</td>
		      </tr>
		      <tr class="success">
		        <th width="20%">Period</th>
		        <td>{{$facontrol->period}}</td>
		      </tr> 
		    </tbody>
		</table>

	  <br>
	  <br>
	  <p><a class="btn btn-primary btn-lg" role="button">Start Depreciation</a></p>
	</div>
</div>
		
@endsection


@section('scripts')

	<script src="js/finance/FA/facontrol/facontrol.js"></script>

	
@endsection
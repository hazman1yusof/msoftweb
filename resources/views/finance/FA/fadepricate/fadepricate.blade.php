@extends('layouts.main')

@section('title', 'Fa Depreciation ')

@section('body')
<div class="container">
	<div class="jumbotron" style="margin-top: 50px;text-align: center;">
	  <h2>Fixed Asset Depreciation process</h2>
	  <h4 style="padding:3% 10% 3% 10%; letter-spacing: 1px;line-height: 1.5">This function perform the depreciation process. It calculates the amount of depreciation for asset into the year-to-date figures. The current period is then closed and advance the period the next period. Make sure all the asset has been registed in current period.</h4>

	 	<table class="table" style="width: 30%;margin: auto;">
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
	  <form method="post" id="depreciation" action="./fadepricate/form">
	  	{{ csrf_field() }}
	  	<input type="hidden" name="oper" value="depreciation">
	  	<input type="hidden" name="year" value="{{$facontrol->year}}">
	  	<input type="hidden" name="period" value="{{$facontrol->period}}">

	  	<button type="submit" class="btn btn-primary btn-lg">Start Depreciation Process</button>
	  </form>
	</div>
</div>
		
@endsection


@section('scripts')

	<script>
		
		$(document).ready(function () {
			$("body").show();

			$('#submit').click(function(){
				$('#submit').attr('disabled',true);
			});

		});
	</script>

@endsection
@extends('layouts.main')

@section('title', 'Fixed Asset Control Setup')

@section('body')

	@include('layouts.default_search_and_table')

	<div id="dialogForm" title="Add Form" >
		<form class='form-horizontal' style='width:99%' id='formdata'>
			{{ csrf_field() }}
            <input type="hidden" name="fa_idno">

			<div class="form-group">
	        	<label class="col-md-3 control-label" for="year">Year</label>  
	              <div class="col-md-3">
	              <select class="form-control"  id="year" name="fa_year">
	              	@foreach ($yearperiod as $year)
	              		<option value="{{$year->year}}">{{$year->year}}</option>
	              	@endforeach
	              </select>
	              </div>
			</div>
	        
	        <div class="form-group">
	        	<label class="col-md-3 control-label" for="period">Period</label>  
	              <div class="col-md-3">
	              <select class="form-control" id="period" name="fa_period">
		              <option value="1">1</option>
		              <option value="2">2</option>
		              <option value="3">3</option>
		              <option value="4">4</option>
		              <option value="5">5</option>
		              <option value="6">6</option>
		              <option value="7">7</option>
		              <option value="8">8</option>
		              <option value="9">9</option>
		              <option value="10">10</option>
		              <option value="11">11</option>
		              <option value="12">12</option>
	              </select>
	              </div>
			</div>

		</form>
	</div>
		
@endsection


@section('scripts')

	<script src="js/finance/FA/facontrol/facontrol.js"></script>

	
@endsection
@extends('layouts.main')

@section('title', 'Sales Order Report')

@section('body')
<div class="container">
	<div class="card">
		<p style="text-align:center;"><img src="{{url('/img/logo.jpg')}}" alt="Logo" height="75px"></p>
		<div class="d-flex flex-column"> 
            <h2 style="text-align: center; letter-spacing: 1px;line-height: 1.5"> NAMA COMPANY </h2>
		</div>
    </div>

	<div class="jumbotron" style="margin-top: 30px;text-align: center;">
		<h2>SALES ORDER REPORT</h2>
			<h4 style="padding:3% 10% 3% 10%; letter-spacing: 1px;line-height: 1.5"> </h4>
					
		<div class="form-group" >
            <label class="col-md-3 control-label" for="reqdt">Date From</label>
			<div class="col-md-3">
				<input id="dateTo" name="dateTo" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
			</div>

			<label class="col-md-3 control-label" for="reqdt">Date To</label>
			<div class="col-md-3">
				<input id="dateTo" name="dateTo" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
			</div>
		</div>			
		<br>
		<br>
        <br>
		<br>
		<form method="post" id="genreport" action="/SalesOrder_Report/form">
			{{ csrf_field() }}
			<input type="hidden" name="oper" value="genreport">
			<button type="submit" class="btn btn-primary btn-lg">Generate Report</button>
		</form>
	</div>
</div>
		
@endsection


@section('scripts')

	<script src="js/finance/SalesOrder_Report/SalesOrder_Report.js"></script>

@endsection
@extends('layouts.main')

@section('title', 'Supplier Report')

@section('body')
<div class="container">
	<div class="jumbotron" style="margin-top: 30px;text-align: center;">
		<form method="get" id="genreport" action="/supplier_Report/showExcel">
		<h2>SUPPLIER REPORT</h2>
			<h4 style="padding:3% 10% 3% 10%; letter-spacing: 1px;line-height: 1.5"> </h4>
	
        <br>
		<br>
			{{ csrf_field() }}
			<input type="hidden" name="oper" value="genreport">
			<button type="submit" class="btn btn-primary btn-lg">Generate Report</button>
		</form>
	</div>
</div>
		
@endsection


@section('scripts')

	<script src="js/material/supplier_Report/supplier_Report.js"></script>

@endsection
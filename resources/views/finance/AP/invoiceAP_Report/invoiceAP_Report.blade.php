@extends('layouts.main')

@section('title', 'Invoice AP Report')

@section('body')
<div class="container">
	<div class="card">
		<p style="text-align:center;"><img src="{{url('/img/logo.jpg')}}" alt="Logo" height="75px"></p>
		<div class="d-flex flex-column"> 
            <h2 style="text-align: center; letter-spacing: 1px; line-height: 1.5"> {{$company_name}} </h2>
		</div>
    </div>

	<div class="jumbotron" style="margin-top: 30px;text-align: center;">
		<form method="get" id="genreport" action="/invoiceAP_Report/showExcel">
		<h2>INVOICE AP REPORT</h2>
			<h4 style="padding:3% 10% 3% 10%; letter-spacing: 1px;line-height: 1.5"> </h4>
		
			<table class="table" style="width: 50%;margin: auto;">
		    <tbody>
				<tr class="success">
					<th width="50%">Date From</th>
					<td><input id="datefr" name="datefr" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}"></td>
				</tr>
				<tr class="success">
					<th width="50%">Date To</th>
					<td><input id="dateto" name="dateto" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}"></td>
				</tr> 
		    </tbody>
		</table>
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

	<script src="js/finance/AP/invoiceAP_Report/invoiceAP_Report.js"></script>

@endsection
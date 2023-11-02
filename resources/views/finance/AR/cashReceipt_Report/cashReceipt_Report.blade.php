@extends('layouts.main')

@section('title', 'Cash Receipt')

@section('body')
	<div class="container">
		<div class="jumbotron" style="margin-top: 30px;text-align: center;">
			<form method="get" id="genreport" action="/cashReceipt_Report/showExcel">
				<h2>CASH RECEIPT</h2>
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
				<button type="submit" class="btn btn-primary btn-lg">Generate Report Excel</button>
			</form>
			
			<br><br>
			
			<form method="get" id= "genreportpdf" href="" target="_blank" action="./cashReceipt_Report/showpdf?">
				<input type="hidden" name='datefr' value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				<input type="hidden" name='dateto' value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				<button type="submit" id="save" data-oper='genreportpdf' class="btn btn-primary btn-lg">Generate Report PDF</button>
			</form>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="js/finance/AR/cashReceipt_Report/casgReceipt_Report.js"></script>
@endsection
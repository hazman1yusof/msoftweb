@extends('layouts.main')

@section('title', 'Summary Receipt Listing Detail')

@section('css')
	<style>
		table.reporttable th{
			border:none;
			text-align: right;
			padding-right: 20px;
		}
		table.reporttable td{
			padding:5px;
		}
	</style>
@endsection

@section('body')
	<div class="container">
		<div class="jumbotron" style="margin-top: 30px;text-align: center;">
			<form method="get" id="genreport" action="/SummaryRcptListingDtl_Report/showExcel">
				<h2>SUMMARY RECEIPT LISTING DETAIL</h2>
				<h4 style="padding:3% 10% 3% 10%; letter-spacing: 1px;line-height: 1.5"> </h4>
				
				<table class="reporttable" style="width: 30%;margin: auto;">
					<tbody>
						<tr>
							<th width="50%">Date From :</th>
							<td><input id="datefr" name="datefr" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}"></td>
						</tr>
						<tr>
							<th width="50%">Date To :</th>
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
			
			<form method="get" id="genreportpdf" href="" target="_blank" action="./SummaryRcptListingDtl_Report/showpdf?">
				<input type="hidden" name='datefr' value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				<input type="hidden" name='dateto' value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				<button type="submit" class="btn btn-primary btn-lg">Generate Report PDF</button>
			</form>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="js/finance/AR/SummaryRcptListingDtl_Report/SummaryRcptListingDtl_Report.js"></script>
@endsection
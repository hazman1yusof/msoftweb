@extends('layouts.main')

@section('title', 'Auto Debit Listing')

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
		
		/* body{
			background: #00808024 !important;
		}
		.container.mycontainer{
			padding-top: 5%;
		}
		.mycontainer .panel-default {
			border-color: #9bb7b7 !important;
		}
		.mycontainer .panel-default > .panel-heading {
			background-image: linear-gradient(to bottom, #b4cfcf 0%, #c1dddd 100%) !important;
			font-weight: bold;
		} */
		.mycontainer .mybtnpdf{
			background-image: linear-gradient(to bottom, #ffbbbb 0%, #ffd1d1 100%) !important;
			color: #af2525;
			border-color: #af2525;
			margin-bottom: 5px;
		}
		.mycontainer .mybtnxls{
			background-image: linear-gradient(to bottom, #a0cda0 0%, #b3d1b3 100%) !important;
			color: darkgreen;
			border-color: darkgreen;
			margin-bottom: 20px;
		}
		.mycontainer .btnvl {
			border-left: 1px solid #386e6e;
			width: 0px;
			padding: 0px;
			height: 32px;
			cursor: unset;
			margin: 0px 7px;
		}
		legend{
			margin-bottom: 5px !important;
			font-size: 12px !important;
			font-weight:bold;
		}
		.btnform .btn{
			width: -webkit-fill-available !important;
		}
	</style>
@endsection

@section('body')
    <div class="container mycontainer">
        <div class="jumbotron" style="margin-top: 30px;text-align: center;height: 500px;">
            <form method="get" id="genreport" action="./bankReceipt_Report/showExcel">
                <h2>AUTO DEBIT LISTING</h2>
                <h4 style="padding:3% 10% 3% 10%; letter-spacing: 1px;line-height: 1.5"> </h4>
				
				<div style="width: 800px;margin: 0 auto;">
					<div class="col-md-4" style="margin-left: 120px;">
						<div class="col-md-12">
							<label class="control-label" for="Scol">Date From</label>
							<input id="datefr" name="datefr" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
						</div>
						
						<div class="col-md-12" style="padding-top: 30px;">
							<label class="control-label" for="Scol">Date To</label>
							<input id="dateto" name="dateto" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
						</div>
					</div>
					
					<div class="col-md-4" style="margin-left: 100px;">
						<div class="panel panel-default" style="height: 137px;">
							<div class="panel-body">
								<div class='col-md-12 btnform' style="padding: 20px 0px">
									<fieldset>
										<!-- <legend>Stock Sheet :</legend> -->
										<button name="bankReceipt_pdf" type="button" class="mybtn btn btn-sm mybtnpdf" id="pdfgen1">
											<span class="fa fa-file-pdf-o fa-lg"></span> Generate Report PDF
										</button>
										<button name="bankReceipt_xls" type="button" class="mybtn btn btn-sm mybtnxls" id="excelgen1">
											<span class="fa fa-file-excel-o fa-lg"></span> Generate Report Excel
										</button>
									</fieldset>
								</div>
							</div>
						</div>
					</div>
				</div>
            </form>
        </div>
    </div>
	
	<!-- <div class="container">
		<div class="jumbotron" style="margin-top: 30px;text-align: center;">
			<form method="get" id="genreport" action="./bankReceipt_Report/showExcel">
				<h2>AUTO DEBIT LISTING</h2>
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
			
			<form method="get" id= "genreportpdf" href="" target="_blank" action="./bankReceipt_Report/showpdf?">
				<input type="hidden" name='datefr' value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				<input type="hidden" name='dateto' value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				<button type="submit" id="save" data-oper='genreportpdf' class="btn btn-primary btn-lg">Generate Report PDF</button>
			</form>
		</div>
	</div> -->
@endsection

@section('scripts')
	<script src="js/finance/AR/bankReceipt_Report/bankReceipt_Report.js"></script>
@endsection
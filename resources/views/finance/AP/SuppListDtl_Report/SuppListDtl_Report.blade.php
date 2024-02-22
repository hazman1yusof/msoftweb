@extends('layouts.main')

@section('title', 'Supplier List Details')

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
		<div class="jumbotron" style="margin-top: 30px;text-align: center; height:500px">
			<form method="get" class='form-horizontal' style='width:99%' id="genreport" action="./SuppListDtl_Report/showExcel">
				<h2>SUPPLIER LIST DETAILS</h2>
				<h4 style="padding:3% 10% 3% 10%; letter-spacing: 1px;line-height: 1.5"> </h4>

				<div class="col-md-9">
					<div class="form-group" style="width:800px; margin-left: 200px">
						<div class="col-md-5">
							<label class="control-label" for="Scol">Supplier List</label> 
								<div class='input-group'> 
									<input id="suppgroup" name="suppgroup" type="text" class="form-control input-sm" autocomplete="off">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
						</div>
					</div>
				</div>

				<div class="col-md-3">
					<div class="panel panel-default" style="height: 137px;">
						<div class="panel-body">
							<div class='col-md-12 btnform' style="padding: 20px 0px;">
							<fieldset>
								<button name="pdfgen" type="button" class="mybtn btn btn-sm mybtnpdf" id="pdfgen">
									<span class="fa fa-file-pdf-o fa-lg"></span> Generate Report PDF
								</button>
								<button name="excel" type="button" class="mybtn btn btn-sm mybtnxls" id="excel">
									<span class="fa fa-file-excel-o fa-lg"></span> Generate Report Excel
								</button>
							</fieldset>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="js/finance/AP/SuppListDtl_Report/SuppListDtl_Report.js"></script>
@endsection
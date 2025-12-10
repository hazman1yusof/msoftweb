@extends('layouts.main')

@section('title', 'PO Listing')

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
        <div class="jumbotron" style="margin-top: 30px;text-align: center;height: 450px;">
            <form method="get" id="genreport" action="./POListing/showExcel">
                <h4>PO LISTING</h4>
                <h7 style="padding:3% 10% 3% 10%; letter-spacing: 1px;line-height: 1.5"> </h7>
				
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
						
						<div class="col-md-12" style="padding-top: 30px;">
							<label class="control-label" for="Status">Status</label>  
								<select id="Status" name="Status" class="form-control input-sm">
									<option value="ALL" selected>ALL</option>
									<option value="OPEN">OPEN</option>
									<option value="REQUEST">REQUEST</option>
									<option value="SUPPORT">SUPPORT</option>
									<option value="VERIFIED">VERIFIED</option>
									<option value="APPROVED">APPROVED</option>
									<option value="COMPLETED">COMPLETED</option>
									<option value="PARTIAL">PARTIAL</option>
									<option value="INCOMPLETED">INCOMPLETED</option>
									<option value="CANCELLED">CANCELLED</option>
								</select>
						</div>
						
						<div class="col-md-12" style="padding-top: 30px;">
							<label class="control-label" for="deptcode">Purchase Dept</label>  
								<select id="deptcode" name="deptcode" class="form-control input-sm">
									<option value="ALL" selected>ALL</option>
									@foreach($deptcode as $obj)
									<option value="{{$obj->deptcode}}">{{$obj->deptcode}}</option>
									@endforeach
								</select>
						</div>
					</div>
					
					<div class="col-md-4" style="margin-left: 100px;padding-top: 50px;">
						<div class="panel panel-default" style="height: 137px;">
							<div class="panel-body">
								<div class='col-md-12 btnform' style="padding: 20px 0px">
									<fieldset>
										<button name="pdf" type="button" class="mybtn btn btn-sm mybtnpdf" id="pdfgen">
											<span class="fa fa-file-pdf-o fa-lg"></span> Generate Report PDF
										</button>
										<button name="excel" type="button" class="mybtn btn btn-sm mybtnxls" id="excelgen">
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
@endsection

@section('scripts')
	<script src="js/material/POListing/POListing.js?v=1.1"></script>
@endsection
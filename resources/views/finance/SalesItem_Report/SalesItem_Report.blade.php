@extends('layouts.main')

@section('title', 'Sales By Item')

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
	<input type="hidden" name="scope" id="scope" value="{{request()->get('scope')}}">
	<div class="container mycontainer">
        <div class="jumbotron" style="margin-top: 30px;text-align: center;height: 350px;">
            <form method="get" id="genreport" action="./SalesItem_Report/showExcel">
                @if(request()->get('scope')=='CAT')<h4>SALES BY CATEGORY</h4>@else<h4>SALES BY ITEM</h4>@endif
                <h7 style="padding:3% 10% 3% 10%; letter-spacing: 1px;line-height: 1.5"> </h7>
				
				<div style="width: 800px;margin: 0 auto;">
					<div class="col-md-4" style="margin-left: 120px;">
						<div class="col-md-12">
							<!-- <input id="deptcode" name="deptcode" type="text" class="form-control input-sm" value=""> -->

						
							<label class="control-label" for="deptcode">Department</label> 
							<div class="col-md-12" style="padding: 0px 0px 15px 0px;">
								<div class='input-group'>
									<input id="deptcode" name="deptcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" value="{{Session::get('deptcode')}}" data-validation-error-msg="Please Enter Value">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
						</div>

						<div class="col-md-12">
							<label class="control-label" for="Scol">Date From</label>
							<input id="datefr" name="datefr" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
						</div>
						
						<div class="col-md-12" style="padding-top: 15px;">
							<label class="control-label" for="Scol">Date To</label>
							<input id="dateto" name="dateto" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
						</div>
					</div>
					
					<div class="col-md-4" style="margin-left: 100px;">
						<div class="panel panel-default" style="height: 107px;">
							<div class="panel-body">
								<div class='col-md-12 btnform' style="padding: 20px 0px">
									<fieldset>
										<!-- <legend>Stock Sheet :</legend> -->
										<!-- <button name="SummaryRcptListing_pdf" type="button" class="mybtn btn btn-sm mybtnpdf" id="pdfgen1">
											<span class="fa fa-file-pdf-o fa-lg"></span> Generate Report PDF
										</button> -->
										<button name="SummaryRcptListing_xls" type="button" class="mybtn btn btn-sm mybtnxls" id="excelgen1">
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
	<script src="js/finance/SalesItem_Report/SalesItem_Report.js?v=1.4"></script>
@endsection
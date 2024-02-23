@extends('layouts.main')

@section('title', 'AP Ageing Details')

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
		<div class="jumbotron" style="margin-top: 30px;text-align: center; height:700px">
			<form method="get" class='form-horizontal' style='width:99%' id="genreport" action="./APAgeingDtl_Report/showExcel">
				<h2>AP AGEING DETAILS</h2>
				<h4 style="padding:3% 10% 3% 10%; letter-spacing: 1px;line-height: 1.5"> </h4>

				<div style="width: 800px;margin: 0 auto;">
                    <div class="col-md-5" style="margin-left: 50px;">
                        <div class="form-group">
                            <div class="col-md-12">
								<label class="control-label" for="Scol">Date</label>  
								<input type="date" name="date_ag" id="date_ag" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-12">
								<label class="control-label" for="Scol">Creditor From</label> 
									<div class='input-group'> 
										<input id="suppcode_from" name="suppcode_from" type="text" class="form-control input-sm" autocomplete="off">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-12">
								<label class="control-label" for="Scol">Creditor To</label>  
									<div class='input-group'>
										<input id="suppcode_to" name="suppcode_to" type="text" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="ZZZ">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4" style="margin-left: 50px;padding-top: 50px;">
                        <div class="panel panel-default" style="height: 137px;">
                            <div class="panel-body">
                                <div class='col-md-12 btnform' style="padding: 20px 0px">
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

                    <div class="col-md-6" style="margin-left: 130px;padding-top: 50px;">
                        <div class="panel panel-info" style="width: 500px;">
                            <div id="printingoptn" class="panel-heading">PRINTING OPTION</div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-md-2 control-label" for="group1">Group 1</label>  
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input id="group1" name="group1" type="number" maxlength="30" class="form-control input-sm">
                                            <span class="input-group-addon" style="color:#000000 !important">days</span>
                                        </div>
                                    </div>

                                    <label class="col-md-2 control-label" for="group4">Group 4</label>  
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input id="group4" name="group4" type="number" maxlength="30" class="form-control input-sm">
                                            <span class="input-group-addon" style="color:#000000 !important">days</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label" for="group2">Group 2</label>  
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input id="group2" name="group2" type="number" maxlength="30" class="form-control input-sm">
                                            <span class="input-group-addon" style="color:#000000 !important">days</span>
                                        </div>
                                    </div>

                                    <label class="col-md-2 control-label" for="group5">Group 5</label>  
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input id="group5" name="group5" type="number" maxlength="30" class="form-control input-sm">
                                            <span class="input-group-addon" style="color:#000000 !important">days</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label" for="group3">Group 3</label>  
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input id="group3" name="group3" type="number" maxlength="30" class="form-control input-sm">
                                            <span class="input-group-addon" style="color:#000000 !important">days</span>
                                        </div>
                                    </div>

                                    <label class="col-md-2 control-label" for="group6">Group 6</label>  
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input id="group6" name="group6" type="number" maxlength="30" class="form-control input-sm">
                                            <span class="input-group-addon" style="color:#000000 !important">days</span>
                                        </div>
                                    </div>
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
	<script src="js/finance/AP/APAgeingDtl_Report/APAgeingDtl_Report.js"></script>
@endsection
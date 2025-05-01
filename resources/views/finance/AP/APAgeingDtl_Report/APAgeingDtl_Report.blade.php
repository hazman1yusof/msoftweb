@extends('layouts.main')

@section('title', 'AP Ageing')

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
        .mybtnpdf{
            background-image: linear-gradient(to bottom, #ffbbbb 0%, #ffd1d1 100%) !important;
            color: #af2525;
            border-color: #af2525;
            margin-bottom: 5px;
        }
        .mybtnxls{
            background-image: linear-gradient(to bottom, #a0cda0 0%, #b3d1b3 100%) !important;
            color: darkgreen;
            border-color: darkgreen;
            margin-bottom: 20px;
        }
        .btnvl {
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
    <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
    <h4 style="text-align: center;">AP AGEING</h4>
    <div class="col-md-6">
        <div class='panel panel-default'>
            <div class='panel-body'>
                <h4 style="margin: 0px;
    text-align: center;">Job Queue Table</h4>
                <div id='job_queue_c' class='col-xs-12 modalx'>
                    <table class="table table-hover table-bordered" id='job_queue'>
                    <thead>
                        <tr>
                            <th>idno</th>
                            <th>compcode</th>
                            <th>page</th>
                            <th>Filename</th>
                            <th>process</th>
                            <th>Status</th>
                            <th>Start User</th>
                            <th>Start Date</th>
                            <th>Finish Date</th>
                            <th>Remarks</th>
                            <th>Download</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
		<form method="get" class='form-horizontal' style='width:99%' id="genreport" action="./APAgeingDtl_Report/showExcel">
			<h7 style="padding:3% 10% 3% 10%; letter-spacing: 1px;line-height: 1.5"> </h7>

			<div style="width: 800px;margin: 0 auto;">
                <div class="col-md-5" style="margin-left: 50px;">
                    <div class="form-group">
                        <div class="col-md-12">
							<label class="control-label" for="Scol">Date</label>  
							<input type="date" name="date" id="date" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
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
                            <select name="type" id="type" class="form-control input-sm" >
                              <option value="detail">Detail</option>
                              <option value="summary">Summary</option>
                            </select>
                            <div class='col-md-12 btnform' style="padding: 20px 0px">
							<fieldset>
								<button name="excel" type="button" class="mybtn btn btn-sm mybtnxls" id="excelgen1">
									<span class="fa fa-file-excel-o fa-lg"></span> Start Report Excel Job
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
                                <label class="col-md-2 control-label" for="groupOne">Group 1</label>  
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input id="groupOne" name="groupOne" type="number" maxlength="30" class="form-control input-sm" value="30">
                                        <span class="input-group-addon" style="color:#000000 !important">days</span>
                                    </div>
                                </div>

                                <label class="col-md-2 control-label" for="groupFour">Group 4</label>  
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input id="groupFour" name="groupFour" type="number" maxlength="30" class="form-control input-sm" value="120">
                                        <span class="input-group-addon" style="color:#000000 !important">days</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label" for="groupTwo">Group 2</label>  
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input id="groupTwo" name="groupTwo" type="number" maxlength="30" class="form-control input-sm" value="60">
                                        <span class="input-group-addon" style="color:#000000 !important">days</span>
                                    </div>
                                </div>

                                <label class="col-md-2 control-label" for="groupFive">Group 5</label>  
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input id="groupFive" name="groupFive" type="number" maxlength="30" class="form-control input-sm" value="">
                                        <span class="input-group-addon" style="color:#000000 !important">days</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label" for="groupThree">Group 3</label>  
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input id="groupThree" name="groupThree" type="number" maxlength="30" class="form-control input-sm" value="90">
                                        <span class="input-group-addon" style="color:#000000 !important">days</span>
                                    </div>
                                </div>

                                <label class="col-md-2 control-label" for="groupSix">Group 6</label>  
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input id="groupSix" name="groupSix" type="number" maxlength="30" class="form-control input-sm">
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
@endsection

@section('scripts')
	<script src="js/finance/AP/APAgeingDtl_Report/APAgeingDtl_Report.js?v=1.3"></script>
@endsection
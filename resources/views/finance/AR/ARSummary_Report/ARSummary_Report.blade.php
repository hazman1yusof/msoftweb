@extends('layouts.main')

@section('title', 'AR Summary')

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
    <div class="container mycontainer">
        <div class="jumbotron" style="margin-top: 30px;text-align: center;height: 500px;">
            <form method="get" id="genreport" action="./ARSummary_Report/showExcel">
                <h2>AR SUMMARY</h2>
                <h4 style="padding:3% 10% 3% 10%; letter-spacing: 1px;line-height: 1.5"> </h4>
                
                <div style="width: 800px;margin: 0 auto;">
                    <div class="col-md-7">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label class="control-label" for="Scol">Debtor From</label>
                                    <div class='input-group'>
                                        <input id="debtorcode_from" name="debtorcode_from" type="text" class="form-control input-sm" autocomplete="off" value="">
                                        <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                    </div>
                                    <span class="help-block"></span>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="control-label" for="Scol">Debtor To</label>
                                    <div class='input-group'>
                                        <input id="debtorcode_to" name="debtorcode_to" type="text" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="ZZZ">
                                        <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                    </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12" style="padding-top: 30px;padding-bottom: 30px;">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label class="control-label" for="Scol">Date From</label>
                                    <input id="datefr" name="datefr" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="control-label" for="Scol">Date To</label>
                                    <input id="dateto" name="dateto" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    
					<div class="col-md-4" style="padding-top: 30px;">
						<div class="col-md-12">
							{{ csrf_field() }}
							<input type="hidden" name="oper" value="genreport">
							<button type="submit" class="btn btn-primary">Generate Report Excel</button>
						</div>
						<div class="col-md-12" style=" padding-top: 5px;">
							<input type="button" class="btn btn-primary" value="Generate Report PDF" id="pdfgen1">
						</div>
					</div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="js/finance/AR/ARSummary_Report/ARSummary_Report.js"></script>
@endsection
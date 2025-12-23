@extends('layouts.main')

@section('title', 'Financial Report')

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
        div.form-group,label.control-label{
            text-align: left !important;
        }
    </style>
@endsection

@section('body')
<div class="container mycontainer">
    <div class="jumbotron" style="margin-top: 30px;text-align: center;height: 630px">
        <form method="get" class='form-horizontal' style='width:99%' id="genreport" action="./SuppList_Report/showExcel">
            <h4>Financial Report</h4>
            <h7 style="padding:3% 8% 3% 8%; letter-spacing: 1px;line-height: 1.0"> </h7>
            
            <div style="width: 1000px;margin: 0 auto;">
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="form-group topradio">
                                <div class="col-md-12">
                                    <label class="radio-inline"><input type="radio" name="Class" value='All' checked>All</label>
                                    <label class="radio-inline"><input type="radio" name="Class" value='Department'>Department</label>
                                    <label class="radio-inline"><input type="radio" name="Class" value='Units'>Units</label>
                                    <label class="radio-inline"><input type="radio" name="Class" value='Variance'>Variance</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <input id="reporttype" name="reporttype" type="hidden">
                                <label class="col-md-2 control-label" for="subcatcode">Report Type</label>  
                                <div class="col-md-10">
                                    <div class='input-group'>
                                        <input id="reportname" name="reportname" type="text" class="form-control input-sm text-uppercase">
                                        <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                    </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label" for="monthfrom">Month</label>  
                                <div class="col-md-4">
                                    <select id="monthfrom" name="monthfrom"  class="form-control input-sm">
                                      <option value="monthfrom" selected>-- MONTH --</option>
                                      <option value="1">January</option>
                                      <option value="2">February</option>
                                      <option value="3">March</option>
                                      <option value="4">April</option>
                                      <option value="5">May</option>
                                      <option value="6">June</option>
                                      <option value="7">July</option>
                                      <option value="8">August</option>
                                      <option value="9">September</option>
                                      <option value="10">October</option>
                                      <option value="11">November</option>
                                      <option value="12">December</option>
                                    </select>
                                </div>       

                                <div class="divto">
                                    <label class="col-md-2 control-label" for="monthto">Month To</label>  
                                    <div class="col-md-4">
                                        <select id="monthto" name="monthto" class="form-control input-sm">
                                          <option value="monthto" selected>-- MONTH TO --</option>
                                          <option value="1">January</option>
                                          <option value="2">February</option>
                                          <option value="3">March</option>
                                          <option value="4">April</option>
                                          <option value="5">May</option>
                                          <option value="6">June</option>
                                          <option value="7">July</option>
                                          <option value="8">August</option>
                                          <option value="9">September</option>
                                          <option value="10">October</option>
                                          <option value="11">November</option>
                                          <option value="12">December</option>
                                        </select>
                                    </div> 
                                </div>              
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="yearfrom">Year</label>  
                                <div class="col-md-4">
                                    <select id='yearfrom' name='yearfrom' class="form-control input-sm"></select>
                                </div>  
                                <div class="divto">
                                    <label class="col-md-2 control-label" for="yearto">Year To</label>  
                                    <div class="col-md-4">
                                        <select id='yearto' name='yearto' class="form-control input-sm"></select>
                                    </div> 
                                </div>
                            </div>

                        </div>

                        <div class='row' id="row_sector">
                            <div class="panel panel-default" >
                                <div class="panel-body">
                                    <div class='col-md-12' style="padding:0 0 15px 0">
                                        <table id="jqGrid" class="table table-striped"></table>
                                        <div id="jqGridPager"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class='row' id="row_dept" style="display: none;">
                            <div class="panel panel-default" >
                                <div class="panel-body" id="jqGrid2_c">
                                    <div class='col-md-12' style="padding:0 0 15px 0">
                                        <table id="jqGrid2" class="table table-striped"></table>
                                        <div id="jqGrid2Pager"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2" style="margin-left: 30px;">
                    <div class="panel panel-default" style="width: 240px; height: 175px">
                        <div class="panel-body">
                            <div class='col-md-12 btnform' style="padding: 20px 0px;">
                                <fieldset>
                                    <legend>Generate :</legend>
                                    <!-- <button name="summary_pdf" type="button" class="mybtn btn btn-sm mybtnpdf" id="summary_pdf">
                                        <span class="fa fa-file-pdf-o fa-lg"></span> Generate Report PDF
                                    </button> -->
                                    <button name="summary_excel" type="button" class="mybtn btn btn-sm mybtnxls" id="summary_excel">
                                        <span class="fa fa-file-excel-o fa-lg"></span> Generate Report Excel
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                    <button name="checkbs" type="button" class="mybtn btn btn-sm btn-primary" id="checkbs" style="margin-top: 220px;">
                        Check Balance sheet
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
	<script src="js/finance/GL/financialReport/financialReport.js?v=1.2"></script>
@endsection
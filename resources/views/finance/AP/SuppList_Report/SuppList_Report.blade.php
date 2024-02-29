@extends('layouts.main')

@section('title', 'Supplier List')

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
    <div class="jumbotron" style="margin-top: 30px;text-align: center; height:600px">
        <form method="get" class='form-horizontal' style='width:99%' id="genreport" action="./SuppList_Report/showExcel">
            <h2>SUPPLIER LIST</h2>
            <h4 style="padding:3% 8% 3% 8%; letter-spacing: 1px;line-height: 1.0"> </h4>

            <div style="width: 1000px;margin: 0 auto;">
                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class='row'>
                            <div class="panel panel-default" >
                                <div class="panel-body">
                                    <div class='col-md-12' style="padding:0 0 15px 0">
                                        <table id="jqGrid" class="table table-striped"></table>
                                        <div id="jqGridPager"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" style="margin-left: 30px;">
                    <div class="panel panel-default" style="width: 240px; height: 265px">
                        <div class="panel-body">
                            <div class='col-md-12 btnform' style="padding: 20px 0px;">
                                <fieldset>
                                    <legend>Summary :</legend>
                                        <button name="SuppListSum_pdf" type="button" class="mybtn btn btn-sm mybtnpdf" id="summary_pdf">
                                            <span class="fa fa-file-pdf-o fa-lg"></span> Generate Report PDF
                                        </button>
                                        <button name="SuppListSum_excel" type="button" class="mybtn btn btn-sm mybtnxls" id="summary_excel">
                                            <span class="fa fa-file-excel-o fa-lg"></span> Generate Report Excel
                                        </button>

                                    <legend>Detail :</legend>
                                        <button name="SuppListDtl_pdf" type="button" class="mybtn btn btn-sm mybtnpdf" id="dtl_pdf">
                                            <span class="fa fa-file-pdf-o fa-lg"></span> Generate Report PDF
                                        </button>
                                        <button name="SuppListDtl_excel" type="button" class="mybtn btn btn-sm mybtnxls" id="dtl_excel">
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
	<script src="js/finance/AP/SuppList_Report/SuppList_Report.js"></script>
@endsection
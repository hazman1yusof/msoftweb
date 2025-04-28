@extends('layouts.main')

@section('title', 'AR Ageing Details')

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
        <div class="jumbotron" style="margin-top: 30px;text-align: center;height: 680px;">
            <form method="get" id="genreport" action="./ARAgeingDtl_Report/showExcel">
                <h4 style="padding-bottom: 30px;">AGEING DETAILS</h4>
                <!-- <h7 style="padding:3% 10% 3% 10%; letter-spacing: 1px;line-height: 1.5"> </h7> -->
                
                <div style="width: 800px;margin: 0 auto;">
                    <div class="col-md-5" style="margin-left: 50px;">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label" for="Scol">Date</label>
                                <input id="date" name="date" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-12" style="padding-top: 10px;">
                                <label class="control-label" for="Scol">Debtor Type</label>
                                <div class='input-group'>
                                    <input id="debtortype" name="debtortype" type="text" class="form-control input-sm" autocomplete="off" value="ALL">
                                    <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                </div>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-12" style="padding-top: 10px;">
                                <label class="control-label" for="Scol">Debtor From</label>
                                <div class='input-group'>
                                    <input id="debtorcode_from" name="debtorcode_from" type="text" class="form-control input-sm" autocomplete="off" value="">
                                    <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                </div>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-12" style="padding-top: 10px;">
                                <label class="control-label" for="Scol">Debtor To</label>
                                <div class='input-group'>
                                    <input id="debtorcode_to" name="debtorcode_to" type="text" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="ZZZ">
                                    <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                </div>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4" style="margin-left: 50px;padding-top: 30px;">
                        <div class="panel panel-default" style="height: 137px;">
                            <div class="panel-body">
                                <select name="type" id="type" class="form-control input-sm" >
                                  <option value="detail">Detail</option>
                                  <option value="summary">Summary</option>
                                </select>
                                <fieldset>
                                <div class='col-md-12 btnform' style="padding: 20px 0px">
                                    <fieldset>
                                        <!-- <legend>Stock Sheet :</legend> -->
                                        <button name="ARAgeingDtl_pdf" type="button" class="mybtn btn btn-sm mybtnpdf" id="pdfgen1" style="display: none;">
                                            <span class="fa fa-file-pdf-o fa-lg"></span> Generate Report PDF
                                        </button>
                                        <button name="ARAgeingDtl_xls" type="button" class="mybtn btn btn-sm mybtnxls" id="excelgen1">
                                            <span class="fa fa-file-excel-o fa-lg"></span> Generate Report Excel
                                        </button>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class='col-md-6' style="padding: 30px 0;margin-left: 140px;">
                        <div class="panel panel-info">
                            <div class="panel-heading text-center">PRINTING OPTION</div>
                            <div class="panel-body" style="padding: 15px 0;">
                                <div class="col-md-6" style="padding-left: 0px;padding-right: 0px;">
                                    <div class="col-md-12">
                                        <label for="groupOne">Group 1</label>
                                        <div class="input-group">
                                            <input id="groupOne" name="groupOne" type="number" class="form-control input-sm" value="30">
                                            <span class="input-group-addon" style="color:#000000 !important">days</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12" style="padding-top: 15px;">
                                        <label for="groupTwo">Group 2</label>
                                        <div class="input-group">
                                            <input id="groupTwo" name="groupTwo" type="number" class="form-control input-sm" value="60">
                                            <span class="input-group-addon" style="color:#000000 !important">days</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12" style="padding-top: 15px;">
                                        <label for="groupThree">Group 3</label>
                                        <div class="input-group">
                                            <input id="groupThree" name="groupThree" type="number" class="form-control input-sm" value="90">
                                            <span class="input-group-addon" style="color:#000000 !important">days</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6" style="padding-left: 0px;padding-right: 0px;">
                                    <div class="col-md-12">
                                        <label for="groupFour">Group 4</label>
                                        <div class="input-group">
                                            <input id="groupFour" name="groupFour" type="number" class="form-control input-sm" value="120">
                                            <span class="input-group-addon" style="color:#000000 !important">days</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12" style="padding-top: 15px;">
                                        <label for="groupFive">Group 5</label>
                                        <div class="input-group">
                                            <input id="groupFive" name="groupFive" type="number" class="form-control input-sm">
                                            <span class="input-group-addon" style="color:#000000 !important">days</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12" style="padding-top: 15px;">
                                        <label for="groupSix">Group 6</label>
                                        <div class="input-group">
                                            <input id="groupSix" name="groupSix" type="number" class="form-control input-sm">
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
    <script src="js/finance/AR/ARAgeingDtl_Report/ARAgeingDtl_Report.js?v=1.2"></script>
@endsection
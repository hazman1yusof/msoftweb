@extends('layouts.main')

@section('title', 'Unallocated Receipt')

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
    <input id="ttype" name="ttype" type="hidden" value="{{Request::get('ttype')}}">
    <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
    <h4 style="text-align: center;">Unallocated @if(Request::get('ttype') == 'RC'){{'RECEIPT'}}@else{{'DEPOSIT'}}@endif</h4>
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
        <form>
            <!-- <h7 style="padding:3% 10% 3% 10%; letter-spacing: 1px;line-height: 1.5"> </h7> -->
            
            <div style="margin: 0 auto;">
                <div class="col-md-6" style="">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label" for="Scol">As of Date</label>
                            <input id="date" name="date" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">

                            <br>
                            <label class="control-label" for="Scol">Unit</label>
                            <select name="unit" id="unit" class="form-control input-sm" >
                              <option value="ALL" selected>ALL</option>
                              <option value="IMP">IMP</option>
                              <option value="KHEALTH">KHEALTH</option>
                              <option value="W'HOUSE">FKWSTR</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6" style="padding-top: 30px;">
                    <div class="panel panel-default" style="height: 87px;">
                        <div class="panel-body">
                            <fieldset>
                            <div class='col-md-12 btnform' style="padding: 10px 0px">
                                <fieldset>
                                    <button name="ARAgeingDtl_xls" type="button" class="mybtn btn btn-sm mybtnxls" id="excelgen1">
                                        <span class="fa fa-file-excel-o fa-lg"></span> Start Report Excel Job
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="js/finance/AR/unallocated_receipt/unallocated_receipt.js?v=1.5"></script>
    <script src="plugins/datatables/js/jquery.datatables.min.js"></script>
@endsection
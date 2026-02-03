@extends('layouts.main')

@section('title', 'periodicStatement')

@section('style')
    body{
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
@endsection('style')

@section('body')
<div class="container mycontainer">
  <div class="row">
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">Statement Periodic</div>
            <div class="panel-body">
                <form class='form-horizontal' style='width:99%' id='formdata'>
                    <input type="hidden" name="action" value="process_pyserver">

                    <div class="form-group">
                            <div class="col-md-6">
                            <label class="control-label">Month From</label> 
                            <input type="month" name="fromdate" class="form-control input-sm" autocomplete="off" value="{{Carbon\Carbon::now('Asia/Kuala_Lumpur')->subMonth()->format('Y-m')}}">
                      </div>
                            <div class="col-md-6">
                            <label class="control-label">Month To</label>  
                            <input type="month" name="todate" class="form-control input-sm" autocomplete="off" value="{{Carbon\Carbon::now('Asia/Kuala_Lumpur')->format('Y-m')}}">
                      </div>
                    </div>

                    <div class="form-group">
                            <div class="col-md-6">
                              <label class="control-label">Supplier From</label> 
                                <div class='input-group'> 
                                    <input id="supp_from" name="supp_from" type="text" class="form-control input-sm" autocomplete="off" value="">
                                    <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                </div>
                                <span class="help-block"></span>
                      </div>
                            <div class="col-md-6">
                              <label class="control-label">Supplier To</label>  
                                <div class='input-group'>
                                    <input id="supp_to" name="supp_to" type="text" class="form-control input-sm" autocomplete="off" data-validation="required" data-validation-error-msg="Please Enter Value" value="ZZZ">
                                    <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                </div>
                                <span class="help-block"></span>
                      </div>
                    </div>

                </form>
            </div>
        </div> 
    </div>

    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">Downloads</div>
            <div class="panel-body">
                <div class='col-md-12 btnform' style="padding:0px">
                 <fieldset>
                    <button name="periodicStatement" id="periodicStatement" type="button" class="mybtn btn btn-sm mybtnxls" style="margin-bottom:3px">
                        <span class="fa fa-file-excel-o fa-lg"></span> Process XLS
                    </button>
                    <input type="hidden" id="job_id">
                    <span id="span_dlexcel" style="display:none">
                        <a id="download_excel" style="display: block;text-align: center;cursor: pointer;">Download Excel</a>
                    </span>
                  </fieldset>
                </div>
            </div>
        </div>
    </div>
  </div> 
</div>
        
@endsection


@section('scripts')

    <script src="js/finance/ar/statement/statement.js?v=1.1"></script>

@endsection
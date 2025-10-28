@extends('layouts.main')

@section('title', 'No Invoice GRN')

@section('css')
    <style>
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
        legend{
            margin-bottom: 5px !important;
            font-size: 12px !important;
            font-weight:bold;
        }
        .toppad{
            padding-top: 8px;
        }
        .btnform .btn{
            width: -webkit-fill-available !important;
        }
    </style>
@endsection

@section('body')
    <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
    <h4 style="text-align: center;">No Invoice GRN Report</h4>
    <div class="col-md-6">
        <div class='panel panel-default'>
            <div class='panel-body'>
                <h4 style="margin: 0px;text-align: center;">Job Queue Table</h4>
                <div id='job_queue_c' class='col-xs-12 modalx'>
                    <table class="table table-hover table-bordered" id='job_queue'>
                    <thead>
                        <tr>
                            <th>ID.</th>
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
                <div class="col-md-5" >
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label" for="dateFrom">Date From</label>
                            <input id="dateFrom" name="dateFrom" type="date" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12 toppad">
                            <label class="control-label" for="dateTo">Date To</label>
                            <input id="dateTo" name="dateTo" type="date" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6" style="padding-top: 30px;">
                    <div class="panel panel-default" style="height: 100px;">
                        <div class="panel-body">
                           <!--  <select name="type" id="type" class="form-control input-sm" >
                              <option value="detail">Detail</option>
                              <option value="summary">Summary</option>
                            </select> -->
                            <fieldset>
                            <div class='col-md-12 btnform' style="padding: 20px 0px 5px 0px">
                                <fieldset>
                                    <button type="button" class="mybtn btn btn-sm mybtnxls" id="excelgen1" style="margin-bottom: 0;">
                                        <span class="fa fa-file-excel-o fa-lg"></span> Start Report Excel Job
                                    </button>
                                </fieldset>
                            </div>
                            <!-- <input type="radio" id="sortbydt" name="groupby" value="debtortype" checked>
                            <label for="sortbydt">By Debtor Type</label>
                            <input type="radio" id="sortbyu" name="groupby" value="unit">
                            <label for="sortbyu">By Units</label> -->
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="js/finance/uninvgrn/uninvgrn.js?v=1.1"></script>
    <script src="plugins/datatables/js/jquery.datatables.min.js"></script>
@endsection
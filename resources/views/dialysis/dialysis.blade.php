@extends('dialysis.layouts.main')

@section('title', 'Dialysis')

@section('style')

@endsection

@section('content')

    <div class="ui grid">
        <div class="column" style="margin:0px;">
            <div class="ui teal segment main_table_panel" style="padding-bottom: 45px;">
                <h2 class="h2">Current Patient List</h2>
                <form class="ui form" id="SearchForm" autocomplete="off" style="margin-bottom: 10px;">
                    <div class="inline field">
                        <select class="ui dropdown" name="Scol">
                          <option value="Name">Patient Name</option>
                          <option value="MRN">MRN</option>
                          <option value="Newic">IC Number</option>
                        </select>
                        <input type="text" placeholder="Type Search here" name="Stext" style="min-width: 50%">
                        <div class="ui slider checkbox myslider showall" >
                          <input type="checkbox" class="hidden" id="arriv_cb">
                          <label style="color:black;">Show all patient</label>
                        </div>
                        <div class="ui slider checkbox myslider showcomplete" >
                          <input type="checkbox" class="hidden" id="comple_cb">
                          <label style="color:black;">Show completed</label>
                        </div>
                    </div>
                </form>
                <table id="jqGrid" class="table table-striped"></table>
                <div id="jqGridPager"></div>
                <a class="ui grey label left floated" style="margin-top: 8px;">
                    <i class="user icon"></i>
                    Patient : <span id="no_of_pat">0</span>
                </a>

                <div style="float: right;padding: 5px 4px 10px 10px;">
                    <button id="timer_refresh" class="ui circular basic mini icon button">
                        <i class="redo alternate icon"></i>
                    </button>
                    <div class="mini basic ui buttons">
                      <button id="timer_play" class="ui disabled icon button">
                        <i class="left play icon"></i>
                        Play
                      </button>
                      <button id="timer_stop" class="ui icon button">
                        <i class="right stop icon"></i>
                        Stop
                      </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input id="user_name" name="user_name" value="{{ Auth::user()->username }}" type="hidden">
    <input id="user_dept" name="user_dept" value="{{ Auth::user()->dept }}" type="hidden">
    <input id="user_groupid" name="user_groupid" value="{{ Auth::user()->groupid }}" type="hidden">
    <input id="sel_date" name="sel_date" value="{{ \Carbon\Carbon::now()->toDateString() }}" type="hidden">
    <input id="_token" name="_token" value="{{ csrf_token() }}" type="hidden">
    <input type="hidden" name="viewallcenter" id="viewallcenter" value="{{strtoupper(Auth::user()->viewallcenter)}}">


    <div class="panel panel-default" style="position: relative;margin: 10px 0px 10px 0px" id="tab_trans_panel">
        <div class="panel-heading clearfix collapsed mainpanel" id="toggle_trans" data-toggle="collapse" data-target="#tab_trans">

        <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px"></i>
        <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px"></i >
        <div>
            <h5><strong>Order Entry</strong>&nbsp;&nbsp;
                <span class="metal"></span></h5>
        </div> 
        </div>

        <div id="tab_trans" class="panel-collapse collapse">
            <div class="panel-body paneldiv">
                @include('dialysis.transaction_charges')
            </div>
        </div>
    </div>

    <div class="panel panel-default" style="position: relative;margin: 10px 0px 10px 0px" id="tab_daily_panel">
        <div class="panel-heading clearfix collapsed mainpanel" id="toggle_daily" data-toggle="collapse" data-target="#tab_daily">

        <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px"></i>
        <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px"></i >
        <div>
            <h5><strong>Daily Clinical Record</strong>&nbsp;&nbsp;
                <span class="metal"></span></h5>
        </div> 
        </div>

        <div id="tab_daily" class="panel-collapse collapse">
            <div class="panel-body paneldiv">
                @include('dialysis.daily_clinical_record')
            </div>
        </div>
    </div>

    <div class="panel panel-default" style="position: relative;margin: 10px 0px 10px 0px" id="tab_weekly_panel">
        <div class="panel-heading clearfix collapsed mainpanel" id="toggle_weekly" data-toggle="collapse" data-target="#tab_weekly">

        <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px"></i>
        <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px"></i >
        <div>
            <h5><strong>Weekly Clinical Record</strong>&nbsp;&nbsp;
                <span class="metal"></span></h5>
        </div> 
        </div>

        <div id="tab_weekly" class="panel-collapse collapse">
            <div class="panel-body paneldiv">
                @include('dialysis.weekly_clinical_record')
            </div>
        </div>
    </div>

    <div class="panel panel-default" style="position: relative;margin: 10px 0px 10px 0px" id="tab_monthly_panel">
        <div class="panel-heading clearfix collapsed mainpanel" id="toggle_monthly" data-toggle="collapse" data-target="#tab_monthly">

        <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px"></i>
        <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px"></i >
        <div>
            <h5><strong>Monthly Clinical Record</strong>&nbsp;&nbsp;
                <span class="metal"></span>
            </h5>
        </div> 
        </div>

        <div id="tab_monthly" class="panel-collapse collapse">
            <div class="panel-body paneldiv" style="position: relative;">
                @include('dialysis.monthly_clinical_record')
            </div>
        </div>
    </div>

    <div class="panel panel-default" style="position: relative;margin: 10px 0px 10px 0px" id="tab_bloodres_panel">
        <div class="panel-heading clearfix collapsed mainpanel" id="toggle_bloodres" data-toggle="collapse" data-target="#tab_bloodres">

        <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px"></i>
        <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px"></i >
        <div>
            <h5><strong>Blood Test Result</strong>&nbsp;&nbsp;
                <span class="metal"></span>
            </h5>
        </div> 
        </div>

        <div id="tab_bloodres" class="panel-collapse collapse">
            <div class="panel-body paneldiv" style="position: relative;">
                @include('dialysis.blood_test_result')
            </div>
        </div>
    </div>

@include('dialysis.itemselector_dialysis')
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="{{ asset('dialysis/assets/DataTables/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dialysis/assets/trirand/css/trirand/ui.jqgrid-bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.semanticui.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/3.2.1/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inknut+Antiqua:wght@300;500&family=Open+Sans:wght@300;700&family=Syncopate&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('dialysis/css/dialysis.css') }}?v=6">
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script type="text/ecmascript" src="{{ asset('dialysis/assets/trirand/i18n/grid.locale-en.js') }}"></script>
    <script type="text/ecmascript" src="{{ asset('dialysis/assets/trirand/jquery.jqGrid.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dialysis/assets/DataTables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.semanticui.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.semanticui.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script type="text/ecmascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script type="text/javascript" src="{{ asset('dialysis/assets/printThis.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dialysis/js/dialysis_main.js') }}?v=7"></script>
    <script type="text/javascript" src="{{ asset('dialysis/js/dialysis.js') }}?v=12"></script>
    <script type="text/javascript" src="{{ asset('dialysis/js/blood_test_result.js') }}?v=3"></script>
    <script type="text/javascript" src="{{ asset('dialysis/js/patmedication_dialysis.js') }}?v=4"></script>
    <script type="text/javascript" src="{{ asset('dialysis/js/transaction_dialysis.js') }}?v=8"></script>
@endsection



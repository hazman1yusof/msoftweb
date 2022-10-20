@extends('layouts.main')

@section('style')
    
.fc-toolbar .fc-center h2{
    color:#f2711c;
    margin-top:15px;
}

.fc-toolbar .fc-right {
    float: right;
}

.fc-unthemed td.fc-today {
    background: rgb(251 189 8 / 0.2);
}

.selected_day {
    background: rgb(251 189 8 / 1) !important;
}

.h2 {
    text-align: center;
    color: #00b5ad !important;
    font-size: large !important;
}

.fc-event {
    position: relative;
    display: block;
    font-size: .85em;
    line-height: 1!important;
    border-radius: 50px !important;
    text-align: center !important;
    border: 1px solid #3a87ad;
    width: 10px;
}

.fc-listMonth-button:before{
    font-family: "FontAwesome";  
    content: "\f03a";
    padding-right: 5px;
}

.fc-month-button:before{
    font-family: "FontAwesome";
    content: "\f073";
    padding-right: 5px;
}

.fc-button{
    height: 2.8em !important;
}

.myb{
    font-size: large;
}

.mysmall{
    font-weight: 900;
    color:#f2711c;
}

.glyphicon-chevron-up,.glyphicon-chevron-down{
    float:right;
}

.panel-heading.collapsed .glyphicon-chevron-up,
.panel-heading .glyphicon-chevron-down {
    display: none;
}

.panel-heading.collapsed .glyphicon-chevron-down,
.panel-heading .glyphicon-chevron-up {
    display: inline-block;
}

.table.diatbl{
    font-size: 11px;
    width: 85% !important;
    margin: auto;
}

.table.diatbl td{
    padding: 4px !important;
}

.ui.form.diaform {
    font-size: smaller;
    width: 85% !important;
    margin: auto;
}

.ui.form.diaform div.field{
    padding: 0px 8px !important;
}

.panel-heading#toggle_monthly, .panel-heading#toggle_weekly, .panel-heading#toggle_daily, .panel-heading#toggle_trans{
    position: sticky;
    top: 40px;
    z-index: 3;
}

#showSidebar{
    background: rgb(255 255 255 / 0%) !mportant;
}

.metal{
    font-size: 0.8em;
    color: rgba(0,0,0,.4);
}
table#jqGrid, table#jqGrid_trans{
    font-size: 11px;
}
.help-block {
    display: block;
    margin-top: 0px !important;
    margin-bottom: 0px !important;
    color: #737373;
}
.ui.checkbox.completed{
    position: absolute;
    top: 10px;
    right: 20px;
}


@endsection

@section('content')

    <div class="ui stackable two column grid">
        <div class="eight wide tablet five wide computer column"><div class="ui orange segment">
            <div id="calendar"></div>
        </div></div>

        <div class="eight wide tablet eleven wide computer right floated column" style="margin:0px;">
            <div class="ui teal segment">
                <h2 class="h2">Patient List</h2>
                <table id="jqGrid" class="table table-striped"></table>
                <div id="jqGridPager"></div>
            </div>
        </div>
    </div>

    <input type="hidden" id="doctornote_route" value="{{route('doctornote_route')}}">
    <input id="user_dept" name="user_dept" value="{{ Auth::user()->dept }}" type="hidden">
    <input id="sel_date" name="sel_date" value="{{ \Carbon\Carbon::now()->toDateString() }}" type="hidden">
    <input id="_token" name="_token" value="{{ csrf_token() }}" type="hidden">
    <div class="panel panel-default" style="position: relative;margin: 10px 0px 10px 0px">
        <div class="panel-heading clearfix collapsed" id="toggle_monthly" data-toggle="collapse" data-target="#tab_monthly">

        <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px"></i>
        <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px"></i >
        <div>
            <h5><strong>Monthly Clinical Record</strong>&nbsp;&nbsp;
                <span class="metal"></span>
            </h5>
        </div> 
        </div>

        <div id="tab_monthly" class="panel-collapse collapse">
            <div class="panel-body">
                @include('monthly_clinical_record')
            </div>
        </div>
    </div>

    <div class="panel panel-default" style="position: relative;margin: 10px 0px 10px 0px">
        <div class="panel-heading clearfix collapsed" id="toggle_weekly" data-toggle="collapse" data-target="#tab_weekly">

        <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px"></i>
        <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px"></i >
        <div>
            <h5><strong>Weekly Clinical Record</strong>&nbsp;&nbsp;
                <span class="metal"></span></h5>
        </div> 
        </div>

        <div id="tab_weekly" class="panel-collapse collapse">
            <div class="panel-body">
                @include('weekly_clinical_record')
            </div>
        </div>
    </div>

    <div class="panel panel-default" style="position: relative;margin: 10px 0px 10px 0px">
        <div class="panel-heading clearfix collapsed" id="toggle_daily" data-toggle="collapse" data-target="#tab_daily">

        <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px"></i>
        <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px"></i >
        <div>
            <h5><strong>Daily Clinical Record</strong>&nbsp;&nbsp;
                <span class="metal"></span></h5>
        </div> 
        </div>

        <div id="tab_daily" class="panel-collapse collapse">
            <div class="panel-body">
                @include('daily_clinical_record')
            </div>
        </div>
    </div>

    <div class="panel panel-default" style="position: relative;margin: 10px 0px 10px 0px" id="transaction_panel">
        <div class="panel-heading clearfix collapsed" id="toggle_trans" data-toggle="collapse" data-target="#tab_trans">

        <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px"></i>
        <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px"></i >
        <div>
            <h5><strong>Order Entry</strong>&nbsp;&nbsp;
                <span class="metal"></span></h5>
        </div> 
        </div>

        <div id="tab_trans" class="panel-collapse collapse">
            <div class="panel-body">
                @include('transaction_charges')
            </div>
        </div>
    </div>

    <!-- <div class="eight wide tablet eleven wide computer column" style="margin:0px;">
        <div class="ui teal segment" id="jqGrid_trans_c">
            <h2 class="h2">Patient List</h2>
            <table id="jqGrid_trans" class="table table-striped"></table>
            <div id="jqGrid_transPager"></div>
        </div>
    </div> -->

@include('itemselector')
@endsection

@section('css')
    <!-- Latest compiled and minified CSS -->
   <!--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" crossorigin="anonymous">

    <!-- Optional theme -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/glDatePicker/styles/glDatePicker.default.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/fullcalendar-3.7.0/fullcalendar.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/DataTables/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/trirand/css/trirand/ui.jqgrid-bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.semanticui.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/3.2.1/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inknut+Antiqua:wght@300;500&family=Open+Sans:wght@300;700&family=Syncopate&display=swap" rel="stylesheet">
@endsection

@section('js')
    <!-- Latest compiled and minified JavaScript -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    
    <script type="text/ecmascript" src="{{ asset('assets/trirand/i18n/grid.locale-en.js') }}"></script>
    <script type="text/ecmascript" src="{{ asset('assets/trirand/jquery.jqGrid.min.js') }}"></script>
    <script type="text/ecmascript" src="{{ asset('assets/fullcalendar-3.7.0/fullcalendar.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/glDatePicker/glDatePicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/glDatePicker/glDatePicker.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/DataTables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.semanticui.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script type="text/ecmascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
	<script type="text/javascript" src="{{ asset('js/dialysis_main.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/dialysis.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/transaction.js') }}"></script>
@endsection



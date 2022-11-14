@extends('dialysis.layouts.main')

@section('title', 'Patient Enquiry')
@section('style')

@endsection

@section('content')

    <div class="ui grid">
        <div class="column" style="margin:0px;">
            <div class="ui teal segment" style="padding-bottom: 45px;">
                <h2 class="h2">Enquiry Patient List</h2>
                <form class="ui form" id="SearchForm" autocomplete="off" style="margin-bottom: 10px;">
                    <div class="inline field">
                        <select class="ui dropdown" name="Scol">
                          <option value="Name">Patient Name</option>
                          <option value="MRN">MRN</option>
                          <option value="I/C">IC Number</option>
                        </select>
                        <input type="text" placeholder="Type Search here" name="Stext" style="min-width: 50%">
                    </div>
                </form>
                <table id="jqGrid" class="table table-striped"></table>
                <div id="jqGridPager"></div>
            </div>
        </div>
    </div>

    <input id="mrn" name="mrn" type="hidden">
    <input id="episno" name="episno" type="hidden">

    <input id="user_name" name="user_name" value="{{ Auth::user()->username }}" type="hidden">
    <input id="user_dept" name="user_dept" value="{{ Auth::user()->dept }}" type="hidden">
    <input id="sel_date" name="sel_date" value="{{ \Carbon\Carbon::now()->toDateString() }}" type="hidden">
    <input id="_token" name="_token" value="{{ csrf_token() }}" type="hidden">

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


    <div class="panel panel-default" style="position: relative;margin: 10px 0px 10px 0px" id="tab_yearly_panel">
        <div class="panel-heading clearfix collapsed mainpanel" id="toggle_yearly" data-toggle="collapse" data-target="#tab_yearly">

        <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px"></i>
        <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px"></i >
        <div>
            <h5><strong>Yearly Clinical Record</strong>&nbsp;&nbsp;
                <span class="metal"></span>
            </h5>
        </div> 
        </div>

        <div id="tab_yearly" class="panel-collapse collapse">
            <div class="panel-body paneldiv" style="position: relative;">
                @include('dialysis.yearly_clinical_record')
            </div>
        </div>
    </div>


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

    <link rel="stylesheet" type="text/css" href="{{ asset('dialysis/css/dialysis.css') }}">
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
	<script type="text/javascript" src="{{ asset('dialysis/js/enquiry_main.js') }}?v=1"></script>
    <script type="text/javascript" src="{{ asset('dialysis/js/enquiry.js') }}?v=2"></script>
@endsection



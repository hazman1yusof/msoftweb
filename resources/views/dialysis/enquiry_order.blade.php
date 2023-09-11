@extends('layouts.main')

@section('title', 'Patient Order Enquiry')
@section('style')

@endsection

@section('content')

    <div class="ui grid">
        <div class="column" style="margin:0px;">
            <div class="ui teal segment" style="padding-bottom: 45px;">
                <h2 class="h2">Enquiry Patient List</h2>
                <form class="ui form" id="SearchForm" autocomplete="off" style="margin-bottom: 10px;">
                    <div class="inline field">
                        <label>Select Month</label>
                        <div class="ui calendar" id="month_year_calendar">
                          <div class="ui input left icon">
                            <i class="calendar icon"></i>
                            <input type="text" placeholder="Date" id="trxdate">
                          </div>
                        </div>

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

    <div class="panel panel-default" style="position: relative;margin: 10px 0px 10px 0px" id="tab_trans_panel">
        <div class="panel-heading clearfix collapsed mainpanel" id="toggle_trans" data-toggle="collapse" data-target="#tab_trans">

        <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px"></i>
        <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px"></i >
        <div>
            <h5><strong>Order Entry History</strong>&nbsp;&nbsp;
                <span class="metal"></span></h5>
        </div> 
        </div>

        <div id="tab_trans" class="panel-collapse collapse">
            <div class="panel-body paneldiv">
                <div class="eight wide tablet eleven wide computer column" style="margin:0px;">
                    <div class="segment">
                        <div class="ui inverted dimmer" id="loader_transaction">
                           <div class="ui large text loader">Loading</div>
                        </div>
                        <div class="ui teal segment" id="jqGrid_trans_c">
                            <h2 class="h2">Item List</h5>
                            <table id="jqGrid_trans" class="table table-striped"></table>
                            <div id="jqGrid_transPager"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@include('itemselector_dialysis')

<div class="ui mini modal scrolling" id="password_mdl">
  <i class="close icon" style="position: inherit;color: black;"></i>
  <div class="content">
    <form class="ui form" id="verify_form" autocomplete="off">
      <div class="field">
        <label>Username</label>
        <input type="text" name="username_verify" id="username_verify" placeholder="Username" required autocomplete="off">
      </div>
      <div class="field">
        <label>Password</label>
        <input type="password" name="password_verify" id="password_verify" placeholder="Password" required autocomplete="off">
      </div>
      <button class="ui primary button" type="button" id="verify_btn">VERIFIED</button>
      <div class="ui red left basic label" id="verify_error" style="display: none;">Username or password wrong</div>
    </form>
  </div>
</div>

@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" crossorigin="anonymous">
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

    <link rel="stylesheet" type="text/css" href="{{ asset('css/dialysis.css') }}?v=1">
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script type="text/ecmascript" src="{{ asset('assets/trirand/i18n/grid.locale-en.js') }}"></script>
    <script type="text/ecmascript" src="{{ asset('assets/trirand/jquery.jqGrid.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/DataTables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.semanticui.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.semanticui.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script type="text/ecmascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{{ asset('js/enquiry_order_main.js') }}?v=3"></script>
    <script type="text/javascript" src="{{ asset('js/enquiry_order.js') }}?v=7"></script>
@endsection



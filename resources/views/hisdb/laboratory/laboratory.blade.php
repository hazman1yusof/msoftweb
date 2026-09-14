@extends('patientcare.layouts.main')

@section('style')
    .red.ui.right.labeled.input input{
        color: white !important;
        border-color:red !important;
        background-color:red !important;
    }
    
    .red.ui.table tr{
        color:white;
        background-color:red !important;
    }
    
    .red.ui.action.input input{
        color: white !important;
        border-color:red !important;
        background-color:red !important;
    }
    
    .yellow.ui.right.labeled.input input{
        color: black !important;
        border-color:#9e9e00 !important;
        background-color:yellow !important;
    }
    
    .yellow.ui.table tr{
        background-color:yellow !important;
    }
    
    .yellow.ui.action.input input{
        color: black !important;
        border-color:#9e9e00 !important;
        background-color:yellow !important;
    }
    
    .green.ui.right.labeled.input input{
        color: white !important;
        border-color:green !important;
        background-color:green !important;
    }
    
    .green.ui.table tr{
        color:white;
        background-color:green !important;
    }
    
    .green.ui.action.input input{
        color: white !important;
        border-color:green !important;
        background-color:green !important;
    }
@endsection

@section('content')
    <input type="hidden" id="curr_user" value="{{ Auth::user()->username }}">
    <input type="hidden" id="user_groupid" value="{{Auth::user()->groupid}}">
    
    <div class="ui stackable two column grid">
        <div class="five wide tablet five wide computer column" id="calendar_div">
            <div class="ui orange segment" style="z-index: 100;">
                <div id="calendar"></div>
            </div>
        </div>
        
        <div class="eleven wide tablet eleven wide computer right floated column" style="margin: 0px;" id="jqgrid_div">
            <div class="ui teal segment jqgridsegment" style="padding-bottom: 40px;" id="jqgrid_c">
                <div class="if_tablet left floated" style="display: none;">
                    <div class="ui calendar" id="button_calendar">
                        <button class="ui teal mini icon button">
                            <i class="calendar alternate outline icon"></i> Select date 
                        </button><span id="sel_date_span" style="margin-left: 10px; color: teal; font-weight: 700;">{{Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('d/m/Y')}}</span>
                    </div>
                </div>
                
                <h2 class="h2">Patient List</h2>
                <table id="jqGrid" class="table table-striped"></table>
                <div id="jqGridPager"></div>
                <a class="ui grey label left floated" style="margin-top: 8px;" id="refresh_main">
                    <i class="user icon"></i>
                    Patient : <span id="no_of_pat">0</span>
                </a>
                
                <div style="float: right; padding: 5px 4px 10px 10px;">
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
    
    <input id="user_dept" name="user_dept" value="{{ Auth::user()->dept }}" type="hidden">
    <input id="sel_date" name="sel_date" value="{{ \Carbon\Carbon::now()->toDateString() }}" type="hidden">
    <input id="_token" name="_token" value="{{ csrf_token() }}" type="hidden">
    <input id="csrf_token" name="csrf_token" value="{{ csrf_token() }}" type="hidden">
    
    <div class="panel panel-default" style="z-index: 100; position: relative; margin: 10px 0px 10px 0px;" id="labMain_panel">
        <div class="panel-heading clearfix collapsed" role="tab" id="toggle_labMain">
            <b>NAME: <span id="name_show_labMain"></span></b><br>
            MRN: <span id="mrn_show_labMain"></span>
            SEX: <span id="sex_show_labMain"></span>
            DOB: <span id="dob_show_labMain"></span>
            AGE: <span id="age_show_labMain"></span>
            RACE: <span id="race_show_labMain"></span>
            RELIGION: <span id="religion_show_labMain"></span><br>
            OCCUPATION: <span id="occupation_show_labMain"></span>
            CITIZENSHIP: <span id="citizenship_show_labMain"></span>
            AREA: <span id="area_show_labMain"></span>
            
            <i class="glyphicon glyphicon-chevron-up" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#tab_labMain"></i>
            <i class="glyphicon glyphicon-chevron-down" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#tab_labMain"></i>
            
            <!-- <div style="position: absolute; 
                        padding: 0 0 0 0; 
                        right: 0px; 
                        top: 0px; 
                        z-index: 1000;">
                <button class="ui icon tertiary button refreshbtn_phys">
                    <i class="sync alternate icon"></i>
                </button>
            </div>
            
            <div style="position: absolute; 
                        padding: 0 0 0 0; 
                        right: 50px; 
                        top: 48px;">
                <h5><strong>Rehabilitation</strong>&nbsp;&nbsp;
                    <span class="metal"></span></h5>
            </div> -->
        </div>
        
        <div id="tab_labMain" class="panel-collapse collapse" >
            <div class="panel-body paneldiv" id="laboratory">
                <input type="hidden" id="mrn_requestFor">
                <input type="hidden" id="episno_requestFor">
                @include('hisdb.laboratory.laboratory_inside_iframe',['laboratory_inside_iframe_phase'=>'laboratory'])
            </div>
        </div>
    </div>
    
    <div class="panel panel-default" style="z-index: 100; position: relative; margin: 10px 0px 10px 0px;" id="ordcom_panel">
        @include('hisdb.ordcom.ordcom',['phase' => '2.lab'])
    </div>

    <div class="panel panel-default" style="z-index: 100; position: relative; margin: 10px 0px 10px 0px;" id="bloodtest_panel">
        <div class="panel-heading clearfix collapsed" role="tab" id="toggle_bloodtest">
            <span id="bloodtest_panel_title" style="display: none;">
                <b>NAME: <span id="name_show_bloodtest"></span></b><br>
                MRN: <span id="mrn_show_bloodtest"></span>
                SEX: <span id="sex_show_bloodtest"></span>
                DOB: <span id="dob_show_bloodtest"></span>
                AGE: <span id="age_show_bloodtest"></span>
                RACE: <span id="race_show_bloodtest"></span>
                RELIGION: <span id="religion_show_bloodtest"></span><br>
                OCCUPATION: <span id="occupation_show_bloodtest"></span>
                CITIZENSHIP: <span id="citizenship_show_bloodtest"></span>
                AREA: <span id="area_show_bloodtest"></span>
            </span>
            
            <i class="glyphicon glyphicon-chevron-up" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#tab_bloodtest"></i>
            <i class="glyphicon glyphicon-chevron-down" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#tab_bloodtest"></i>

            <div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 100px; top: 15px;">
                <h5 style="margin-bottom: 5px;">Blood test</h5>
            </div>  
        </div>
        
        <div id="tab_bloodtest" class="panel-collapse collapse" >
            <div class="panel-body paneldiv" id="bloodtest" style="position: relative;">
                <input type="hidden" id="mrn_bloodtest">
                <input type="hidden" id="episno_bloodtest">
                @include('hisdb.laboratory.bloodtest_inside_iframe')
            </div>
        </div>
    </div>
    
    @include('hisdb.pat_mgmt.pat_mgmt_iframe',['phase' => 'semantic'])
    @include('patientcare.itemselector')
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('patientcare/css/doctornote.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ asset('patientcare/assets/fullcalendar-3.7.0/fullcalendar.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('patientcare/assets/trirand/css/trirand/ui.jqgrid-bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/se/dt-1.11.3/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inknut+Antiqua:wght@300;500&family=Open+Sans:wght@300;700&family=Syncopate&display=swap" rel="stylesheet">
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/myjs/utility.js') }}?v=1.2"></script>
    
    <script type="text/javascript" src="{{ asset('js/hisdb/pat_mgmt/pat_mgmt_main_iframe.js?v=1.1') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script type="text/ecmascript" src="{{ asset('patientcare/assets/trirand/i18n/grid.locale-en.js') }}"></script>
    <script type="text/ecmascript" src="{{ asset('patientcare/assets/trirand/jquery.jqGrid.min.js') }}"></script>
    <script type="text/ecmascript" src="{{ asset('patientcare/assets/fullcalendar-3.7.0/fullcalendar.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/se/dt-1.11.3/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script type="text/ecmascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script type="text/ecmascript" src="{{ asset('patientcare/assets/form-validator/jquery.form-validator.min.js') }}/"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script type="text/javascript" src="{{ asset('js/hisdb/laboratory/labMain.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/laboratory/bloodtest.js') }}"></script>
    <script type="text/javascript" src="{{ asset('patientcare/js/requestfor_iframe.js') }}"></script>
    <!-- <script type="text/javascript" src="{{ asset('patientcare/js/transaction.js') }}"></script> -->
    <!-- <script type="text/javascript" src="{{ asset('patientcare/js/transaction_diet.js') }}"></script> -->
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_main.js?v=1.3') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_phar.js?v=1.2') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_disp.js?v=1.2') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_lab.js?v=1.2') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_rad.js?v=1.2') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_dfee.js?v=1.2') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_phys.js?v=1.2') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_rehab.js?v=1.2') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_diet.js?v=1.2') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_oth.js?v=1.2') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_pkg.js?v=1.2') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/pat_mgmt/epis_payer.js?v=1.2') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/pat_mgmt/epis_coverage.js?v=1.2') }}"></script>
@endsection
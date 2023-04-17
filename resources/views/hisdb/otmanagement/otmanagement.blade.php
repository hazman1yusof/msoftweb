@extends('patientcare.layouts.main')

@section('style')
@endsection

@section('content')
    
    <input type="hidden" id="curr_user" value="{{ Auth::user()->username }}">
    <input type="hidden" id="user_groupid" value="{{Auth::user()->groupid}}">
    
    <div class="ui stackable two column grid">
        <div class="five wide tablet five wide computer column" id="calendar_div">
            <div class="ui orange segment" style="z-index:100">
                <div id="calendar"></div>
            </div>
        </div>
        
        <div class="eleven wide tablet eleven wide computer right floated column" style="margin:0px;" id="jqgrid_div">
            <div class="ui teal segment jqgridsegment" style="padding-bottom: 40px;">
                <div class="if_tablet left floated" style="display:none;">
                    <div class="ui calendar" id="button_calendar">
                        <button class="ui teal mini icon button">
                            <i class="calendar alternate outline icon"></i> Select date
                        </button><span id="sel_date_span" style="margin-left: 10px;color: teal;font-weight: 700;">{{Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('d/m/Y')}}</span>
                    </div>
                </div>
                
                <h2 class="h2">Operation Record List</h2>
                <table id="jqGrid" class="table table-striped"></table>
                <div id="jqGridPager"></div>
                <a class="ui grey label left floated" style="margin-top: 8px;">
                    <i class="user icon"></i>
                    Patient : <span id="no_of_pat">0</span>
                </a>
                
                <div style="float: right;padding: 5px 4px 10px 10px;">
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
    
    <div class="panel panel-default" style="z-index: 100;position: relative;margin: 10px 0px 10px 0px" id="otmgmt_div_panel">
        <div class="panel-heading clearfix collapsed" id="toggle_otmgmt_div">
            <b>NAME: <span id="name_show_otmgmt_div"></span></b><br>
            MRN: <span id="mrn_show_otmgmt_div"></span>
            SEX: <span id="sex_show_otmgmt_div"></span>
            DOB: <span id="dob_show_otmgmt_div"></span>
            AGE: <span id="age_show_otmgmt_div"></span>
            RACE: <span id="race_show_otmgmt_div"></span>
            RELIGION: <span id="religion_show_otmgmt_div"></span><br>
            OCCUPATION: <span id="occupation_show_otmgmt_div"></span>
            CITIZENSHIP: <span id="citizenship_show_otmgmt_div"></span>
            AREA: <span id="area_show_otmgmt_div"></span> 

            <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_otmgmt_div"></i>
            <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_otmgmt_div"></i >  

            <div style="position: absolute;
                            padding: 0 0 0 0;
                            right: 0px;
                            top: 0px;
                            z-index: 1000;">
                <button class="ui icon tertiary button refreshbtn_otmgmt_div">
                    <i class="sync alternate icon"></i>
                </button>
            </div> 
            <div style="position: absolute;
                            padding: 0 0 0 0;
                            right: 50px;
                            top: 48px;">
                <h5><strong>Management</strong>&nbsp;&nbsp;
                    <span class="metal"></span></h5>
            </div>
        </div>
        
        <div id="tab_otmgmt_div" class="panel-collapse collapse">
            <div class="panel-body paneldiv">
                @include('hisdb.otmanagement.otmanagement_div')
            </div>
        </div>
    </div>
    
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
    <!-- <script type="text/javascript" src="{{ asset('patientcare/js/userfile.js') }}"></script> -->
    <!-- <script type="text/javascript" src="{{ asset('patientcare/js/transaction.js') }}"></script> -->
    <!-- <script type="text/javascript" src="{{ asset('patientcare/js/transaction_diet.js') }}"></script> -->
    <!-- <script type="text/javascript" src="{{ asset('patientcare/js/transaction_phys.js') }}"></script> -->
    <!-- <script type="text/javascript" src="{{ asset('patientcare/js/doctornote.js') }}"></script> -->
    <!-- <script type="text/javascript" src="{{ asset('patientcare/js/nursing.js') }}"></script> -->
    <!-- <script type="text/javascript" src="{{ asset('patientcare/js/dieteticCareNotes.js') }}"></script> -->
    <!-- <script type="text/javascript" src="{{ asset('patientcare/js/physioterapy.js') }}"></script>
    <script type="text/javascript" src="{{ asset('patientcare/js/physioterapy_ncase.js') }}"></script> -->
    <!-- <script type="text/javascript" src="{{ asset('patientcare/js/doctornote_main.js') }}"></script> -->
    <script type="text/javascript" src="js/hisdb/otmanagement/otmanagement_main.js"></script>

@endsection
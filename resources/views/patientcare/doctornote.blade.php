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
            <div class="ui orange segment" style="z-index:100">
                <div id="calendar"></div>
            </div>
        </div>

        <div class="eleven wide tablet eleven wide computer right floated column" style="margin:0px;"  id="jqgrid_div">
            <div class="ui teal segment jqgridsegment" style="padding-bottom: 40px;" id="jqgrid_c">
                
                <div class="if_tablet left floated" style="display:none;">
                    <div class="ui calendar" id="button_calendar">
                        <button class="ui teal mini icon button">
                            <i class="calendar alternate outline icon"></i> Select date
                        </button><span id="sel_date_span" style="margin-left: 10px;color: teal;font-weight: 700;">{{Carbon\Carbon::now("Asia/Kuala_Lumpur")->format('d/m/Y')}}</span>
                    </div>
                </div>

                <h2 class="h2">Patient List</h2>
                <table id="jqGrid" class="table table-striped"></table>
                <div id="jqGridPager"></div>
                <a class="ui grey label left floated" style="margin-top: 8px;" id="refresh_main">
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
    <input id="csrf_token" name="csrf_token" value="{{ csrf_token() }}" type="hidden">

    <div class="panel panel-default" style="z-index: 100;position: relative;margin: 10px 0px 10px 0px" id="userfile_panel">
        <div class="panel-heading clearfix collapsed" id="toggle_userfile" >

        <b>NAME: <span id="name_show_userfile"></span></b><br>
        MRN: <span id="mrn_show_userfile"></span>
        SEX: <span id="sex_show_userfile"></span>
        DOB: <span id="dob_show_userfile"></span>
        AGE: <span id="age_show_userfile"></span>
        RACE: <span id="race_show_userfile"></span>
        RELIGION: <span id="religion_show_userfile"></span><br>
        OCCUPATION: <span id="occupation_show_userfile"></span>
        CITIZENSHIP: <span id="citizenship_show_userfile"></span>
        AREA: <span id="area_show_userfile"></span> 

        <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_userfile"></i>
        <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_userfile"></i >  

        <div style="position: absolute;
                        padding: 0 0 0 0;
                        right: 0px;
                        top: 0px;
                        z-index: 1000;">
            <button class="ui icon tertiary button refreshbtn_userfile">
                <i class="sync alternate icon"></i>
            </button>
        </div> 
        <div style="position: absolute;
                        padding: 0 0 0 0;
                        right: 50px;
                        top: 48px;">
            <h5><strong>Document Imaging</strong>&nbsp;&nbsp;
                <span class="metal"></span></h5>
        </div> 
        </div>

        <div id="tab_userfile" class="panel-collapse collapse">
            <div class="panel-body paneldiv">
                @include('patientcare.userfile_div')
            </div>
        </div>
    </div>

    <div class="panel panel-default" style="z-index: 100;position: relative;margin: 10px 0px 10px 0px" id="nursing_panel">
        <div class="panel-heading clearfix collapsed" id="toggle_triage">

            <b>NAME: <span id="name_show_triage"></span></b><br>
            MRN: <span id="mrn_show_triage"></span>
            SEX: <span id="sex_show_triage"></span>
            DOB: <span id="dob_show_triage"></span>
            AGE: <span id="age_show_triage"></span>
            RACE: <span id="race_show_triage"></span>
            RELIGION: <span id="religion_show_triage"></span><br>
            OCCUPATION: <span id="occupation_show_triage"></span>
            CITIZENSHIP: <span id="citizenship_show_triage"></span>
            AREA: <span id="area_show_triage"></span> 

            <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_triage"></i>
            <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_triage" ></i >
        
            <div style="position: absolute;
                            padding: 0 0 0 0;
                            right: 0px;
                            top: 0px;
                            z-index: 1000;">
                <button class="ui icon tertiary button refreshbtn_triage">
                    <i class="sync alternate icon"></i>
                </button>
            </div> 

            <div id='nursing_title' style="position: absolute;
                            padding: 0 0 0 0;
                            right: 50px;
                            top: 48px;">
                <h5><strong>Emergency Nursing Assessment</strong>&nbsp;&nbsp;
                    <span class="metal"></span></h5>
            </div> 

            
        </div>

        <div id="tab_triage" class="panel-collapse collapse">
            <div class="panel-body paneldiv">
                @include('patientcare.nursing')
            </div>
        </div>
    </div>

    <div class="panel panel-default" style="z-index: 100;position: relative;margin: 10px 0px 10px 0px" id="nursNote_panel">
        <div class="panel-heading clearfix collapsed" id="toggle_nursNote">

            <b>NAME: <span id="name_show_nursNote"></span></b><br>
            MRN: <span id="mrn_show_nursNote"></span>
            SEX: <span id="sex_show_nursNote"></span>
            DOB: <span id="dob_show_nursNote"></span>
            AGE: <span id="age_show_nursNote"></span>
            RACE: <span id="race_show_nursNote"></span>
            RELIGION: <span id="religion_show_nursNote"></span><br>
            OCCUPATION: <span id="occupation_show_nursNote"></span>
            CITIZENSHIP: <span id="citizenship_show_nursNote"></span>
            AREA: <span id="area_show_nursNote"></span> 

            <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_nursNote"></i>
            <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_nursNote" ></i >
        
            <div style="position: absolute;
                            padding: 0 0 0 0;
                            right: 0px;
                            top: 0px;
                            z-index: 1000;">
                <button class="ui icon tertiary button refreshbtn_nursNote">
                    <i class="sync alternate icon"></i>
                </button>
            </div> 

            <div id='nursing_title' style="position: absolute;
                            padding: 0 0 0 0;
                            right: 50px;
                            top: 48px;">
                <h5><strong>Nursing Note</strong>&nbsp;&nbsp;
                    <span class="metal"></span></h5>
            </div> 

            
        </div>

        <div id="tab_nursNote" class="panel-collapse collapse">
            <div class="panel-body paneldiv">
                @include('patientcare.nursingnote')
            </div>
        </div>
    </div>

    <div class="panel panel-default" style="z-index: 100;position: relative;margin: 10px 0px 10px 0px" id="ordcom_panel">
        @include('hisdb.ordcom.ordcom',['phase' => '2'])
    </div>

    <div class="panel panel-default" style="z-index: 100;position: relative;margin: 10px 0px 10px 0px" id="doctornote_panel">
        <div class="panel-heading clearfix collapsed" id="toggle_doctornote">

            <b>NAME: <span id="name_show_doctorNote"></span></b><br>
            MRN: <span id="mrn_show_doctorNote"></span>
            SEX: <span id="sex_show_doctorNote"></span>
            DOB: <span id="dob_show_doctorNote"></span>
            AGE: <span id="age_show_doctorNote"></span>
            RACE: <span id="race_show_doctorNote"></span>
            RELIGION: <span id="religion_show_doctorNote"></span><br>
            OCCUPATION: <span id="occupation_show_doctorNote"></span>
            CITIZENSHIP: <span id="citizenship_show_doctorNote"></span>
            AREA: <span id="area_show_doctorNote"></span> 

            <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_doctornote"></i>
            <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_doctornote" ></i >
        
            <div style="position: absolute;
                            padding: 0 0 0 0;
                            right: 0px;
                            top: 0px;
                            z-index: 1000;">
                <button class="ui icon tertiary button refreshbtn_doctornote">
                    <i class="sync alternate icon"></i>
                </button>
            </div> 

            <div id='doctornote_title' style="position: absolute;
                            padding: 0 0 0 0;
                            right: 50px;
                            top: 48px;">
                <h5><strong>Doctor Note</strong>&nbsp;&nbsp;
                    <span class="metal"></span></h5>
            </div> 

            
        </div>

        <div id="tab_doctornote" class="panel-collapse collapse">
            <div class="panel-body paneldiv" id="tab_doctornote_sticky">
                @include('patientcare.doctornote_div')
            </div>
        </div>
    </div>

    <div class="panel panel-default" style="z-index: 100;position: relative;margin: 10px 0px 10px 0px" id="requestFor_panel">
        <div class="panel-heading clearfix collapsed" id="toggle_requestFor">

            <b>NAME: <span id="name_show_requestFor"></span></b><br>
            MRN: <span id="mrn_show_requestFor"></span>
            SEX: <span id="sex_show_requestFor"></span>
            DOB: <span id="dob_show_requestFor"></span>
            AGE: <span id="age_show_requestFor"></span>
            RACE: <span id="race_show_requestFor"></span>
            RELIGION: <span id="religion_show_requestFor"></span><br>
            OCCUPATION: <span id="occupation_show_requestFor"></span>
            CITIZENSHIP: <span id="citizenship_show_requestFor"></span>
            AREA: <span id="area_show_requestFor"></span> 

            <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_requestFor"></i>
            <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_requestFor" ></i >
        
            <div style="position: absolute;
                            padding: 0 0 0 0;
                            right: 0px;
                            top: 0px;
                            z-index: 1000;">
                <button class="ui icon tertiary button refreshbtn_requestFor">
                    <i class="sync alternate icon"></i>
                </button>
            </div> 

            <div id='requestFor_title' style="position: absolute;
                            padding: 0 0 0 0;
                            right: 50px;
                            top: 48px;">
                <h5><strong>Request For</strong>&nbsp;&nbsp;
                    <span class="metal"></span></h5>
            </div>
            
        </div>

        <div id="tab_requestFor" class="panel-collapse collapse" data-curtype='navtab_otbookReqFor'>
            <div class="panel-body paneldiv" id="tab_requestFor_sticky">
                @include('patientcare.requestfor')
            </div>
        </div>
    </div>

    <div class="panel panel-default" style="z-index: 100;position: relative;margin: 10px 0px 10px 0px" id="admHandover_panel">
        <div class="panel-heading clearfix collapsed" id="toggle_admHandover">

            <b>NAME: <span id="name_show_admHandover"></span></b><br>
            MRN: <span id="mrn_show_admHandover"></span>
            SEX: <span id="sex_show_admHandover"></span>
            DOB: <span id="dob_show_admHandover"></span>
            AGE: <span id="age_show_admHandover"></span>
            RACE: <span id="race_show_admHandover"></span>
            RELIGION: <span id="religion_show_admHandover"></span><br>
            OCCUPATION: <span id="occupation_show_admHandover"></span>
            CITIZENSHIP: <span id="citizenship_show_admHandover"></span>
            AREA: <span id="area_show_admHandover"></span> 

            <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_admHandover"></i>
            <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_admHandover"></i >
        
            <div style="position: absolute;
                            padding: 0 0 0 0;
                            right: 0px;
                            top: 0px;
                            z-index: 1000;">
                <button class="ui icon tertiary button refreshbtn_admHandover">
                    <i class="sync alternate icon"></i>
                </button>
            </div> 

            <div id='admHandover_title' style="position: absolute;
                            padding: 0 0 0 0;
                            right: 50px;
                            top: 48px;">
                <h5><strong>Admission Handover</strong>&nbsp;&nbsp;
                    <span class="metal"></span></h5>
            </div>
            
        </div>

        <div id="tab_admHandover" class="panel-collapse collapse">
            <div class="panel-body paneldiv" id="tab_admHandover_sticky">
                @include('patientcare.admhandover')
            </div>
        </div>
    </div>

    <div class="panel panel-default" style="z-index: 100;position: relative;margin: 10px 0px 10px 0px" id="diet_panel">
        <div class="panel-heading clearfix collapsed" role="tab" id="toggle_diet">

        <b>NAME: <span id="name_show_dieteticCareNotes"></span></b><br>
        MRN: <span id="mrn_show_dieteticCareNotes"></span>
        SEX: <span id="sex_show_dieteticCareNotes"></span>
        DOB: <span id="dob_show_dieteticCareNotes"></span>
        AGE: <span id="age_show_dieteticCareNotes"></span>
        RACE: <span id="race_show_dieteticCareNotes"></span>
        RELIGION: <span id="religion_show_dieteticCareNotes"></span><br>
        OCCUPATION: <span id="occupation_show_dieteticCareNotes"></span>
        CITIZENSHIP: <span id="citizenship_show_dieteticCareNotes"></span>
        AREA: <span id="area_show_dieteticCareNotes"></span> 

        <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_diet"></i>
        <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_diet"></i>

        <div style="position: absolute;
                        padding: 0 0 0 0;
                        right: 0px;
                        top: 0px;
                        z-index: 1000;">
            <button class="ui icon tertiary button refreshbtn_diet">
                <i class="sync alternate icon"></i>
            </button>
        </div> 
        <div style="position: absolute;
                        padding: 0 0 0 0;
                        right: 50px;
                        top: 48px;">
            <h5><strong>Dietetic Care Notes</strong>&nbsp;&nbsp;
                <span class="metal"></span></h5>
        </div> 
        </div>

        <div id="tab_diet" class="panel-collapse collapse">
            <div class="panel-body paneldiv">
                @include('patientcare.dieteticCareNotes')
            </div>
        </div>
    </div>

    <div class="panel panel-default" style="z-index: 100;position: relative;margin: 10px 0px 10px 0px" id="phys_panel">
        <div class="panel-heading clearfix collapsed" role="tab" id="toggle_phys">

        <b>NAME: <span id="name_show_phys"></span></b><br>
        MRN: <span id="mrn_show_phys"></span>
        SEX: <span id="sex_show_phys"></span>
        DOB: <span id="dob_show_phys"></span>
        AGE: <span id="age_show_phys"></span>
        RACE: <span id="race_show_phys"></span>
        RELIGION: <span id="religion_show_phys"></span><br>
        OCCUPATION: <span id="occupation_show_phys"></span>
        CITIZENSHIP: <span id="citizenship_show_phys"></span>
        AREA: <span id="area_show_phys"></span> 

        <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_phys"></i>
        <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_phys"></i> 

        <div style="position: absolute;
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
        </div> 
        </div>

        <div id="tab_phys" class="panel-collapse collapse">
            <div class="panel-body paneldiv">
                @include('patientcare.physioterapy')
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
    <script type="text/javascript" src="{{ asset('js/myjs/utility.js') }}?v=1.1"></script>
    
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
    <script type="text/javascript" src="{{ asset('patientcare/js/userfile.js') }}"></script>
    <script type="text/javascript" src="{{ asset('patientcare/js/transaction.js') }}"></script>
    <script type="text/javascript" src="{{ asset('patientcare/js/transaction_diet.js') }}"></script>
    <script type="text/javascript" src="{{ asset('patientcare/js/transaction_phys.js') }}"></script>
    <script type="text/javascript" src="{{ asset('patientcare/js/doctornote.js?v=1.2') }}"></script>
    <script type="text/javascript" src="{{ asset('patientcare/js/nursing.js?v=1.1') }}"></script>
    <script type="text/javascript" src="{{ asset('patientcare/js/dieteticCareNotes.js') }}"></script>
    <script type="text/javascript" src="{{ asset('patientcare/js/physioterapy.js') }}"></script>
    <script type="text/javascript" src="{{ asset('patientcare/js/physioterapy_ncase.js') }}"></script>
    <script type="text/javascript" src="{{ asset('patientcare/js/doctornote_main.js') }}"></script>
    <script type="text/javascript" src="{{ asset('patientcare/js/requestfor.js?v=1.2') }}"></script>
    <script type="text/javascript" src="{{ asset('patientcare/js/admhandover.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_main.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_phar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_disp.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_lab.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_rad.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_dfee.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_phys.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_rehab.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_diet.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_oth.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hisdb/ordcom/ordcom_pkg.js') }}"></script>
    <script type="text/javascript" src="{{ asset('patientcare/js/nursingnote.js') }}"></script>
@endsection



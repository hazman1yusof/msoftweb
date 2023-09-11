@extends('dialysis.layouts.main')

@section('title', 'Case Note')

@section('style')
@endsection

@section('content')

    <input type="hidden" id="curr_user" value="{{ Auth::user()->username }}">
    <input type="hidden" id="user_groupid" value="{{Auth::user()->groupid}}">

    <div class="ui grid">
        <div class="column" style="margin:0px;">
            <div class="ui teal segment" style="padding-bottom: 45px;">
                <h2 class="h2">Current Patient List</h2>
                <form class="ui form" id="SearchForm" autocomplete="off" style="margin-bottom: 10px;">
                    <div class="inline field">
                        <select class="ui dropdown" name="Scol">
                          <option value="Name">Patient Name</option>
                          <option value="MRN">MRN</option>
                          <option value="I/C">IC Number</option>
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

    <!--<div class="ui stackable two column grid">
        <div class="five wide tablet five wide computer column" id="calendar_div">
            <div class="ui orange segment" style="z-index:100">
                <div id="calendar"></div>
            </div>
        </div>

        <div class="sixteen wide tablet sixteen wide computer right floated column" style="margin:0px;"  id="jqgrid_div">
            <div class="ui teal segment jqgridsegment" style="padding-bottom: 40px;">
                
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
    </div> -->

    <input id="user_dept" name="user_dept" value="{{ Auth::user()->dept }}" type="hidden">
    <input id="sel_date" name="sel_date" value="{{ \Carbon\Carbon::now()->toDateString() }}" type="hidden">
    <input id="_token" name="_token" value="{{ csrf_token() }}" type="hidden">

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

        <div id="tab_doctornote" class="panel-collapse collapse mainpanel">
            <div class="panel-body paneldiv" id="tab_doctornote_sticky">
                @include('dialysis.doctornote_div')
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
                <h5><strong>Nursing</strong>&nbsp;&nbsp;
                    <span class="metal"></span></h5>
            </div> 

            
        </div>

        <div id="tab_triage" class="panel-collapse collapse mainpanel">
            <div class="panel-body paneldiv">
                @include('dialysis.nursing')
            </div>
        </div>
    </div>

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
            <h5><strong>Electronic Document</strong>&nbsp;&nbsp;
                <span class="metal"></span></h5>
        </div> 
        </div>

        <div id="tab_userfile" class="panel-collapse collapse mainpanel">
            <div class="panel-body paneldiv">
                @include('dialysis.userfile_div')
            </div>
        </div>
    </div>

@include('dialysis.itemselector')
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('dialysis/css/doctornote.css')}}?v=2">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ asset('dialysis/assets/fullcalendar-3.7.0/fullcalendar.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dialysis/assets/trirand/css/trirand/ui.jqgrid-bootstrap.css') }}" />
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
    <script type="text/ecmascript" src="{{ asset('dialysis/assets/trirand/i18n/grid.locale-en.js') }}"></script>
    <script type="text/ecmascript" src="{{ asset('dialysis/assets/trirand/jquery.jqGrid.min.js') }}"></script>
    <script type="text/ecmascript" src="{{ asset('dialysis/assets/fullcalendar-3.7.0/fullcalendar.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/se/dt-1.11.3/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script type="text/ecmascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script type="text/ecmascript" src="{{ asset('dialysis/assets/form-validator/jquery.form-validator.min.js') }}/"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script type="text/javascript" src="{{ asset('dialysis/js/userfile.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dialysis/js/transaction.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dialysis/js/doctornote.js') }}?v=2"></script>
    <script type="text/javascript" src="{{ asset('dialysis/js/nursing.js') }}?v=3"></script>
    <script type="text/javascript" src="{{ asset('dialysis/js/doctornote_main.js') }}"></script>
@endsection



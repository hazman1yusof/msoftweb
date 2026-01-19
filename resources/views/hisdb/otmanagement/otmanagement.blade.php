@extends('patientcare.layouts.main')

@section('style')
@endsection

@section('header')
    <script>
        var otstatus_arr = [
            @foreach($otstatus as $obj)
                { desc:'{{$obj->description}}' },
            @endforeach
        ]
    </script>
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
            <div class="ui segment jqgridsegment" style="padding-bottom: 40px;">
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
    
    <div class="panel panel-default" style="z-index: 100;position: relative;margin: 10px 0px 10px 0px" id="preoperative_panel">
        <div class="panel-heading clearfix collapsed" id="toggle_preoperative">
            <b>NAME: <span id="name_show_preoperative"></span></b> <br>
            <b>MRN:</b> <span id="mrn_show_preoperative"></span> &nbsp;
            <b>IC NO./PASSPORT:</b> <span id="icpssprt_show_preoperative"></span> &nbsp;
            <b>SEX:</b> <span id="sex_show_preoperative"></span> &nbsp;
            <b>HEIGHT:</b> <span id="height_show_preoperative"></span> &nbsp;
            <b>WEIGHT:</b> <span id="weight_show_preoperative"></span> &nbsp;
            <b>DOB:</b> <span id="dob_show_preoperative"></span> &nbsp;
            <b>AGE:</b> <span id="age_show_preoperative"></span> &nbsp;
            <b>RACE:</b> <span id="race_show_preoperative"></span> &nbsp;
            <b>RELIGION:</b> <span id="religion_show_preoperative"></span> <br>
            <b>OCCUPATION:</b> <span id="occupation_show_preoperative"></span> &nbsp;
            <b>CITIZENSHIP:</b> <span id="citizenship_show_preoperative"></span> &nbsp;
            <b>AREA:</b> <span id="area_show_preoperative"></span> &nbsp;
            <b>WARD / BED:</b> <span id="ward_show_preoperative"></span> / <span id="bednum_show_preoperative"></span> &nbsp;
            <b>OP ROOM:</b> <span id="oproom_show_preoperative"></span> <br>
            <b>DIAGNOSIS:</b> <span id="diagnosis_show_preoperative"></span> &nbsp;
            <b>PLANNED PROCEDURE:</b> <span id="procedure_show_preoperative"></span> &nbsp;
            <b>UNIT/DISCIPLINE:</b> <span id="unit_show_preoperative"></span> &nbsp;
            <b>OPERATION TYPE:</b> <span id="type_show_preoperative"></span> &nbsp;
            
            <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_preoperative"></i>
            <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_preoperative"></i>
            <div style="position: absolute;
                            padding: 0 0 0 0;
                            right: 50px;
                            top: 48px;">
                <h5><strong>Pre-Operative Checklist</strong>&nbsp;&nbsp;
                    <span class="metal"></span></h5>
            </div>
        </div>
        
        <div id="tab_preoperative" class="panel-collapse collapse">
            <div class="panel-body paneldiv">
                @include('hisdb.preoperative.preoperative')
            </div>
        </div>
    </div>
    
    <div class="panel panel-default" style="z-index: 100;position: relative;margin: 10px 0px 10px 0px" id="preoperativeDC_panel">
        <div class="panel-heading clearfix collapsed" id="toggle_preoperativeDC">
            <b>NAME: <span id="name_show_preoperativeDC"></span></b> <br>
            <b>MRN:</b> <span id="mrn_show_preoperativeDC"></span> &nbsp;
            <b>IC NO./PASSPORT:</b> <span id="icpssprt_show_preoperativeDC"></span> &nbsp;
            <b>SEX:</b> <span id="sex_show_preoperativeDC"></span> &nbsp;
            <b>HEIGHT:</b> <span id="height_show_preoperativeDC"></span> &nbsp;
            <b>WEIGHT:</b> <span id="weight_show_preoperativeDC"></span> &nbsp;
            <b>DOB:</b> <span id="dob_show_preoperativeDC"></span> &nbsp;
            <b>AGE:</b> <span id="age_show_preoperativeDC"></span> &nbsp;
            <b>RACE:</b> <span id="race_show_preoperativeDC"></span> &nbsp;
            <b>RELIGION:</b> <span id="religion_show_preoperativeDC"></span> <br>
            <b>OCCUPATION:</b> <span id="occupation_show_preoperativeDC"></span> &nbsp;
            <b>CITIZENSHIP:</b> <span id="citizenship_show_preoperativeDC"></span> &nbsp;
            <b>AREA:</b> <span id="area_show_preoperativeDC"></span> &nbsp;
            <b>WARD / BED:</b> <span id="ward_show_preoperativeDC"></span> / <span id="bednum_show_preoperative"></span> &nbsp;
            <b>OP ROOM:</b> <span id="oproom_show_preoperativeDC"></span> <br>
            <b>DIAGNOSIS:</b> <span id="diagnosis_show_preoperativeDC"></span> &nbsp;
            <b>PLANNED PROCEDURE:</b> <span id="procedure_show_preoperativeDC"></span> &nbsp;
            <b>UNIT/DISCIPLINE:</b> <span id="unit_show_preoperativeDC"></span> &nbsp;
            <b>OPERATION TYPE:</b> <span id="type_show_preoperativeDC"></span> &nbsp;
            
            <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_preoperativeDC"></i>
            <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_preoperativeDC"></i>
            <div style="position: absolute;
                            padding: 0 0 0 0;
                            right: 50px;
                            top: 48px;">
                <h5><strong>Pre-Operative Checklist (Daycare)</strong>&nbsp;&nbsp;
                    <span class="metal"></span></h5>
            </div>
        </div>
        
        <div id="tab_preoperativeDC" class="panel-collapse collapse">
            <div class="panel-body paneldiv">
                @include('hisdb.preoperativeDC.preoperativeDC')
            </div>
        </div>
    </div>
    
    <div class="panel panel-default" style="z-index: 100;position: relative;margin: 10px 0px 10px 0px" id="oper_team_panel">
        <div class="panel-heading clearfix collapsed" id="toggle_oper_team">
            <b>NAME: <span id="name_show_oper_team"></span></b> <br>
            <b>MRN:</b> <span id="mrn_show_oper_team"></span> &nbsp;
            <b>IC NO./PASSPORT:</b> <span id="icpssprt_show_oper_team"></span> &nbsp;
            <b>SEX:</b> <span id="sex_show_oper_team"></span> &nbsp;
            <b>HEIGHT:</b> <span id="height_show_oper_team"></span> &nbsp;
            <b>WEIGHT:</b> <span id="weight_show_oper_team"></span> &nbsp;
            <b>DOB:</b> <span id="dob_show_oper_team"></span> &nbsp;
            <b>AGE:</b> <span id="age_show_oper_team"></span> &nbsp;
            <b>RACE:</b> <span id="race_show_oper_team"></span> &nbsp;
            <b>RELIGION:</b> <span id="religion_show_oper_team"></span> <br>
            <b>OCCUPATION:</b> <span id="occupation_show_oper_team"></span> &nbsp;
            <b>CITIZENSHIP:</b> <span id="citizenship_show_oper_team"></span> &nbsp;
            <b>AREA:</b> <span id="area_show_oper_team"></span> &nbsp;
            <b>WARD / BED:</b> <span id="ward_show_oper_team"></span> / <span id="bednum_show_oper_team"></span> &nbsp;
            <b>OP ROOM:</b> <span id="oproom_show_oper_team"></span> <br>
            <b>DIAGNOSIS:</b> <span id="diagnosis_show_oper_team"></span> &nbsp;
            <b>PLANNED PROCEDURE:</b> <span id="procedure_show_oper_team"></span> &nbsp;
            <b>UNIT/DISCIPLINE:</b> <span id="unit_show_oper_team"></span> &nbsp;
            <b>OPERATION TYPE:</b> <span id="type_show_oper_team"></span> &nbsp;
            
            <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_oper_team"></i>
            <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_oper_team"></i>
            <div style="position: absolute;
                            padding: 0 0 0 0;
                            right: 50px;
                            top: 48px;">
                <h5><strong>Operating Team Checklist</strong>&nbsp;&nbsp;
                    <span class="metal"></span></h5>
            </div>
        </div>
        
        <div id="tab_oper_team" class="panel-collapse collapse">
            <div class="panel-body paneldiv">
                @include('hisdb.oper_team.oper_team')
            </div>
        </div>
    </div>
    
    <div class="panel panel-default" style="z-index: 100;position: relative;margin: 10px 0px 10px 0px" id="otswab_panel">
        <div class="panel-heading clearfix collapsed" id="toggle_otswab">
            <b>NAME: <span id="name_show_otswab"></span></b> <br>
            <b>MRN:</b> <span id="mrn_show_otswab"></span> &nbsp;
            <b>IC NO./PASSPORT:</b> <span id="icpssprt_show_otswab"></span> &nbsp;
            <b>SEX:</b> <span id="sex_show_otswab"></span> &nbsp;
            <b>HEIGHT:</b> <span id="height_show_otswab"></span> &nbsp;
            <b>WEIGHT:</b> <span id="weight_show_otswab"></span> &nbsp;
            <b>DOB:</b> <span id="dob_show_otswab"></span> &nbsp;
            <b>AGE:</b> <span id="age_show_otswab"></span> &nbsp;
            <b>RACE:</b> <span id="race_show_otswab"></span> &nbsp;
            <b>RELIGION:</b> <span id="religion_show_otswab"></span> <br>
            <b>OCCUPATION:</b> <span id="occupation_show_otswab"></span> &nbsp;
            <b>CITIZENSHIP:</b> <span id="citizenship_show_otswab"></span> &nbsp;
            <b>AREA:</b> <span id="area_show_otswab"></span> &nbsp;
            <b>WARD / BED:</b> <span id="ward_show_otswab"></span> / <span id="bednum_show_otswab"></span> &nbsp;
            <b>OP ROOM:</b> <span id="oproom_show_otswab"></span> <br>
            <b>DIAGNOSIS:</b> <span id="diagnosis_show_otswab"></span> &nbsp;
            <b>PLANNED PROCEDURE:</b> <span id="procedure_show_otswab"></span> &nbsp;
            <b>UNIT/DISCIPLINE:</b> <span id="unit_show_otswab"></span> &nbsp;
            <b>OPERATION TYPE:</b> <span id="type_show_otswab"></span> &nbsp;
            
            <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_otswab"></i>
            <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_otswab"></i>
            <div style="position: absolute;
                            padding: 0 0 0 0;
                            right: 50px;
                            top: 48px;">
                <h5><strong>Swab & Instrument Count Form</strong>&nbsp;&nbsp;
                    <span class="metal"></span></h5>
            </div>
        </div>
        
        <div id="tab_otswab" class="panel-collapse collapse">
            <div class="panel-body paneldiv">
                @include('hisdb.otswab.otswab')
            </div>
        </div>
    </div>
    
    <div class="panel panel-default" style="z-index: 100;position: relative;margin: 10px 0px 10px 0px" id="ottime_panel">
        <div class="panel-heading clearfix collapsed" id="toggle_ottime">
            <b>NAME: <span id="name_show_ottime"></span></b> <br>
            <b>MRN:</b> <span id="mrn_show_ottime"></span> &nbsp;
            <b>IC NO./PASSPORT:</b> <span id="icpssprt_show_ottime"></span> &nbsp;
            <b>SEX:</b> <span id="sex_show_ottime"></span> &nbsp;
            <b>HEIGHT:</b> <span id="height_show_ottime"></span> &nbsp;
            <b>WEIGHT:</b> <span id="weight_show_ottime"></span> &nbsp;
            <b>DOB:</b> <span id="dob_show_ottime"></span> &nbsp;
            <b>AGE:</b> <span id="age_show_ottime"></span> &nbsp;
            <b>RACE:</b> <span id="race_show_ottime"></span> &nbsp;
            <b>RELIGION:</b> <span id="religion_show_ottime"></span> <br>
            <b>OCCUPATION:</b> <span id="occupation_show_ottime"></span> &nbsp;
            <b>CITIZENSHIP:</b> <span id="citizenship_show_ottime"></span> &nbsp;
            <b>AREA:</b> <span id="area_show_ottime"></span> &nbsp;
            <b>WARD / BED:</b> <span id="ward_show_ottime"></span> / <span id="bednum_show_ottime"></span> &nbsp;
            <b>OP ROOM:</b> <span id="oproom_show_ottime"></span> <br>
            <b>DIAGNOSIS:</b> <span id="diagnosis_show_ottime"></span> &nbsp;
            <b>PLANNED PROCEDURE:</b> <span id="procedure_show_ottime"></span> &nbsp;
            <b>UNIT/DISCIPLINE:</b> <span id="unit_show_ottime"></span> &nbsp;
            <b>OPERATION TYPE:</b> <span id="type_show_ottime"></span> &nbsp;
            
            <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_ottime"></i>
            <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_ottime"></i>
            <div style="position: absolute;
                            padding: 0 0 0 0;
                            right: 50px;
                            top: 48px;">
                <h5><strong>OT Time Record</strong>&nbsp;&nbsp;
                    <span class="metal"></span></h5>
            </div>
        </div>
        
        <div id="tab_ottime" class="panel-collapse collapse">
            <div class="panel-body paneldiv">
                @include('hisdb.ottime.ottime')
            </div>
        </div>
    </div>
    
    <div class="panel panel-default" style="z-index: 100;position: relative;margin: 10px 0px 10px 0px" id="otdischarge_panel">
        <div class="panel-heading clearfix collapsed" id="toggle_otdischarge">
            <b>NAME: <span id="name_show_otdischarge"></span></b> <br>
            <b>MRN:</b> <span id="mrn_show_otdischarge"></span> &nbsp;
            <b>IC NO./PASSPORT:</b> <span id="icpssprt_show_otdischarge"></span> &nbsp;
            <b>SEX:</b> <span id="sex_show_otdischarge"></span> &nbsp;
            <b>HEIGHT:</b> <span id="height_show_otdischarge"></span> &nbsp;
            <b>WEIGHT:</b> <span id="weight_show_otdischarge"></span> &nbsp;
            <b>DOB:</b> <span id="dob_show_otdischarge"></span> &nbsp;
            <b>AGE:</b> <span id="age_show_otdischarge"></span> &nbsp;
            <b>RACE:</b> <span id="race_show_otdischarge"></span> &nbsp;
            <b>RELIGION:</b> <span id="religion_show_otdischarge"></span> <br>
            <b>OCCUPATION:</b> <span id="occupation_show_otdischarge"></span> &nbsp;
            <b>CITIZENSHIP:</b> <span id="citizenship_show_otdischarge"></span> &nbsp;
            <b>AREA:</b> <span id="area_show_otdischarge"></span> &nbsp;
            <b>WARD / BED:</b> <span id="ward_show_otdischarge"></span> / <span id="bednum_show_otdischarge"></span> &nbsp;
            <b>OP ROOM:</b> <span id="oproom_show_otdischarge"></span> <br>
            <b>DIAGNOSIS:</b> <span id="diagnosis_show_otdischarge"></span> &nbsp;
            <b>PLANNED PROCEDURE:</b> <span id="procedure_show_otdischarge"></span> &nbsp;
            <b>UNIT/DISCIPLINE:</b> <span id="unit_show_otdischarge"></span> &nbsp;
            <b>OPERATION TYPE:</b> <span id="type_show_otdischarge"></span> &nbsp;
            
            <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_otdischarge"></i>
            <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_otdischarge"></i>
            <div style="position: absolute;
                            padding: 0 0 0 0;
                            right: 50px;
                            top: 48px;">
                <h5><strong>Pre-Discharge Check</strong>&nbsp;&nbsp;
                    <span class="metal"></span></h5>
            </div>
        </div>
        
        <div id="tab_otdischarge" class="panel-collapse collapse">
            <div class="panel-body paneldiv">
                @include('hisdb.otdischarge.otdischarge')
            </div>
        </div>
    </div>
    
    <div class="panel panel-default" style="z-index: 100;position: relative;margin: 10px 0px 10px 0px" id="endoscopyNotes_panel">
        <div class="panel-heading clearfix collapsed" id="toggle_endoscopyNotes">
            <b>NAME: <span id="name_show_endoscopyNotes"></span></b> <br>
            <b>MRN:</b> <span id="mrn_show_endoscopyNotes"></span> &nbsp;
            <b>IC NO./PASSPORT:</b> <span id="icpssprt_show_endoscopyNotes"></span> &nbsp;
            <b>SEX:</b> <span id="sex_show_endoscopyNotes"></span> &nbsp;
            <b>HEIGHT:</b> <span id="height_show_endoscopyNotes"></span> &nbsp;
            <b>WEIGHT:</b> <span id="weight_show_endoscopyNotes"></span> &nbsp;
            <b>DOB:</b> <span id="dob_show_endoscopyNotes"></span> &nbsp;
            <b>AGE:</b> <span id="age_show_endoscopyNotes"></span> &nbsp;
            <b>RACE:</b> <span id="race_show_endoscopyNotes"></span> &nbsp;
            <b>RELIGION:</b> <span id="religion_show_endoscopyNotes"></span> <br>
            <b>OCCUPATION:</b> <span id="occupation_show_endoscopyNotes"></span> &nbsp;
            <b>CITIZENSHIP:</b> <span id="citizenship_show_endoscopyNotes"></span> &nbsp;
            <b>AREA:</b> <span id="area_show_endoscopyNotes"></span> &nbsp;
            <b>WARD / BED:</b> <span id="ward_show_endoscopyNotes"></span> / <span id="bednum_show_endoscopyNotes"></span> &nbsp;
            <b>OP ROOM:</b> <span id="oproom_show_endoscopyNotes"></span> <br>
            <b>DIAGNOSIS:</b> <span id="diagnosis_show_endoscopyNotes"></span> &nbsp;
            <b>PLANNED PROCEDURE:</b> <span id="procedure_show_endoscopyNotes"></span> &nbsp;
            <b>UNIT/DISCIPLINE:</b> <span id="unit_show_endoscopyNotes"></span> &nbsp;
            <b>OPERATION TYPE:</b> <span id="type_show_endoscopyNotes"></span> &nbsp;
            
            <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_endoscopyNotes"></i>
            <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_endoscopyNotes"></i>
            <div style="position: absolute;
                            padding: 0 0 0 0;
                            right: 50px;
                            top: 48px;">
                <h5><strong>Endoscopy Notes</strong>&nbsp;&nbsp;
                    <span class="metal"></span></h5>
            </div>
        </div>
        
        <div id="tab_endoscopyNotes" class="panel-collapse collapse">
            <div class="panel-body paneldiv">
                @include('hisdb.endoscopyNotes.endoscopyNotes')
            </div>
        </div>
    </div>

    <div class="panel panel-default" style="z-index: 100;position: relative;margin: 10px 0px 10px 0px" id="otmgmt_div_panel">
        <div class="panel-heading clearfix collapsed" id="toggle_otmgmt_div">
            <b>NAME: <span id="name_show_otmgmt_div"></span></b> <br>
            <b>MRN:</b> <span id="mrn_show_otmgmt_div"></span> &nbsp;
            <b>IC NO./PASSPORT:</b> <span id="icpssprt_show_otmgmt_div"></span> &nbsp;
            <b>SEX:</b> <span id="sex_show_otmgmt_div"></span> &nbsp;
            <b>HEIGHT:</b> <span id="height_show_otmgmt_div"></span> &nbsp;
            <b>WEIGHT:</b> <span id="weight_show_otmgmt_div"></span> &nbsp;
            <b>DOB:</b> <span id="dob_show_otmgmt_div"></span> &nbsp;
            <b>AGE:</b> <span id="age_show_otmgmt_div"></span> &nbsp;
            <b>RACE:</b> <span id="race_show_otmgmt_div"></span> &nbsp;
            <b>RELIGION:</b> <span id="religion_show_otmgmt_div"></span> <br>
            <b>OCCUPATION:</b> <span id="occupation_show_otmgmt_div"></span> &nbsp;
            <b>CITIZENSHIP:</b> <span id="citizenship_show_otmgmt_div"></span> &nbsp;
            <b>AREA:</b> <span id="area_show_otmgmt_div"></span> &nbsp;
            <b>WARD / BED:</b> <span id="ward_show_otmgmt_div"></span> / <span id="bednum_show_otmgmt_div"></span> &nbsp;
            <b>OP ROOM:</b> <span id="oproom_show_otmgmt_div"></span> <br>
            <b>DIAGNOSIS:</b> <span id="diagnosis_show_otmgmt_div"></span> &nbsp;
            <b>PLANNED PROCEDURE:</b> <span id="procedure_show_otmgmt_div"></span> &nbsp;
            <b>UNIT/DISCIPLINE:</b> <span id="unit_show_otmgmt_div"></span> &nbsp;
            <b>OPERATION TYPE:</b> <span id="type_show_otmgmt_div"></span> &nbsp;
            
            <i class="glyphicon glyphicon-chevron-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_otmgmt_div"></i>
            <i class="glyphicon glyphicon-chevron-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#tab_otmgmt_div"></i>
            <div style="position: absolute;
                            padding: 0 0 0 0;
                            right: 50px;
                            top: 48px;">
                <h5><strong>Operation Record</strong>&nbsp;&nbsp;
                    <span class="metal"></span></h5>
            </div>
        </div>
        
        <div id="tab_otmgmt_div" class="panel-collapse collapse">
            <div class="panel-body paneldiv">
                @include('hisdb.otmanagement.otmanagement_div')
            </div>
        </div>
    </div>
    
    <!-- @include('patientcare.itemselector') -->
    
    <div id="mdl_item_selector2" title="Select Item" style="display: none;">
        <div class="content">
            <table id="tbl_item_select" class="ui celled table" width="100%">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Description</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    
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
    <script type="text/javascript" src="js/myjs/utility.js"></script>
    <script type="text/javascript" src="js/hisdb/otmanagement/otmanagement_main.js"></script>
    <script type="text/javascript" src="js/hisdb/otmanagement/otmanagement_div.js?v=1.2"></script>
    <script type="text/javascript" src="js/hisdb/preoperative/preoperative.js?v=1.1"></script>
    <script type="text/javascript" src="js/hisdb/preoperativeDC/preoperativeDC.js?v=1.2"></script>
    <script type="text/javascript" src="js/hisdb/oper_team/oper_team.js?v=1.2"></script>
    <script type="text/javascript" src="js/hisdb/otswab/otswab.js?v=1.1"></script>
    <script type="text/javascript" src="js/hisdb/ottime/ottime.js?v=1.2"></script>
    <script type="text/javascript" src="js/hisdb/otdischarge/otdischarge.js?v=1.1"></script>
    <script type="text/javascript" src="js/hisdb/endoscopyNotes/endoscopyStomach.js?v=1.1"></script>
    <script type="text/javascript" src="js/hisdb/endoscopyNotes/endoscopyIntestine.js?v=1.1"></script>
    <script type="text/javascript" src="js/hisdb/endoscopyNotes/endoscopyNotes.js?v=1.2"></script>
    
@endsection
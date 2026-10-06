
<div class="panel panel-default" style="margin-bottom: 7px;">
    <div class="panel-heading">
        <b>NAME: <span id="name_show_wardMain"></span></b><br>
        MRN: <span id="mrn_show_wardMain"></span>
        SEX: <span id="sex_show_wardMain"></span>
        DOB: <span id="dob_show_wardMain"></span>
        AGE: <span id="age_show_wardMain"></span>
        RACE: <span id="race_show_wardMain"></span>
        RELIGION: <span id="religion_show_wardMain"></span><br>
        OCCUPATION: <span id="occupation_show_wardMain"></span>
        CITIZENSHIP: <span id="citizenship_show_wardMain"></span>
        AREA: <span id="area_show_wardMain"></span>
    </div>
</div>
<input type="hidden" id="ward_main_phase" value="{{$phase}}">

<div class="panel panel-default" style="position: relative;margin-bottom: 0px;" id="jqGridWardMain_c">
    <input type="hidden" name="curr_user" id="curr_user" value="{{ Auth::user()->username }}">
    
    <div class="panel-heading clearfix collapsed position" id="toggle_wardMain" style="position: sticky; top: 0px; z-index: 3; height: 65px;">
        <span id="wardMain_panel_title" style="display: none;">
            <b>NAME: <span id="name_show_wardMain_2"></span></b><br>
            MRN: <span id="mrn_show_wardMain_2"></span>
            SEX: <span id="sex_show_wardMain_2"></span>
            DOB: <span id="dob_show_wardMain_2"></span>
            AGE: <span id="age_show_wardMain_2"></span>
            RACE: <span id="race_show_wardMain_2"></span>
            RELIGION: <span id="religion_show_wardMain_2"></span><br>
            OCCUPATION: <span id="occupation_show_wardMain_2"></span>
            CITIZENSHIP: <span id="citizenship_show_wardMain_2"></span>
            AREA: <span id="area_show_wardMain_2"></span>
        </span>
        
        <i class="arrow fa fa-angle-double-up" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridWardMain_panel"></i>
        <i class="arrow fa fa-angle-double-down" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridWardMain_panel"></i>
        <div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 25px;">
            <h5>Clinical</h5>
        </div>
    </div>

    @if($phase == 'IP')
        <div id="jqGridWardMain_panel" class="panel-collapse collapse" data-curtype='navtab_EDAssmtIP'>
            <div class="panel-body paneldiv" style="overflow-y: auto;">
                <div class='col-md-12' style="padding: 0 0 15px 0;">
                    <ul class="nav nav-tabs" id="jqGridWardMain_panel_tabs">
                        <li><a data-toggle="tab" id="navtab_EDAssmtIP" href="#tab-EDAssmtIP" data-type='EDAssmtIP'>Emergency<br>Nursing<br>Assessment</a></li>
                        <li><a data-toggle="tab" id="navtab_nursActionIP" href="#tab-nursActionIP" data-type='nursActionIP'>Nursing<br>Action Plan</a></li>
                        <li><a data-toggle="tab" id="navtab_nursNoteIP" href="#tab-nursNoteIP" data-type='nursNoteIP'>Nursing Note</a></li>
                        @if(Auth::user()->doctor == 1)
                        <li><a data-toggle="tab" id="navtab_antenatalIP" href="#tab-antenatalIP" data-type='antenatalIP'>Antenatal &<br>Pregnancy</a></li>
                        @endif
                        <li><a data-toggle="tab" id="navtab_docNoteIP" href="#tab-docNoteIP" data-type='docNoteIP'>Doctor Note</a></li>
                        <li><a data-toggle="tab" id="navtab_docNoteRefIP" href="#tab-docNoteRefIP" data-type='docNoteRefIP'>Doctor Note<br>(Referral)</a></li>
                        <li><a data-toggle="tab" id="navtab_reqForIP" href="#tab-reqForIP" data-type='reqForIP'>Request For</a></li>
                        <li><a data-toggle="tab" id="navtab_dietNoteIP" href="#tab-dietNoteIP" data-type='dietNoteIP'>Dietetic<br>Care Notes</a></li>
                        <li><a data-toggle="tab" id="navtab_dietOrderIP" href="#tab-dietOrderIP" data-type='dietOrderIP'>Diet Order</a></li>
                        <li><a data-toggle="tab" id="navtab_userfile_div" href="#tab-userfile_div" data-type='userfile_div'>Document Imaging</a></li>
                    </ul>
                    <div class="tab-content" style="padding: 10px 5px;">
                        <input id="mrn_wardMain" name="mrn_wardMain" type="hidden">
                        <input id="episno_wardMain" name="episno_wardMain" type="hidden">
                        <input id="doctor_wardMain" name="doctor_wardMain" type="hidden">
                        <input id="ward_wardMain" name="ward_wardMain" type="hidden">
                        <input id="bednum_wardMain" name="bednum_wardMain" type="hidden">
                        <input id="age_wardMain" name="age_wardMain" type="hidden">
                        <input id="isPregnant_wardMain" name="isPregnant_wardMain" type="hidden">
                        <input type="hidden" id="ordcomtt_phar" value="{{$ordcomtt_phar ?? ''}}">
                        
                        <div id="tab-EDAssmtIP" class="active in tab-pane fade">
                            @include('hisdb.nursingED.nursingED_tab')
                        </div>
                        <div id="tab-nursActionIP" class="tab-pane fade">
                            @include('hisdb.nursingActionPlan.nursingActionPlan_tab')
                        </div>
                        <div id="tab-nursNoteIP" class="tab-pane fade">
                            @include('hisdb.nursingnote.nursingnote_tab')
                        </div>
                        <div id="tab-antenatalIP" class="tab-pane fade">
                            @include('hisdb.antenatal.antenatal_tab')
                        </div>
                        <div id="tab-docNoteIP" class="tab-pane fade">
                            @include('hisdb.clientprogressnote.clientprogressnote_tab')
                        </div>
                        <div id="tab-docNoteRefIP" class="tab-pane fade">
                            @include('hisdb.clientprogressnote.clientprogressnoteref_tab')
                        </div>
                        <div id="tab-reqForIP" class="tab-pane fade">
                            <!-- #include('hisdb.requestfor.requestfor_tab') -->
                            <iframe id='requestfor_main_iframe' src='' style="height: calc(90vh);width: 100%; border: none;overflow-x: hidden;"></iframe>
                        </div>
                        <div id="tab-dietNoteIP" class="tab-pane fade">
                            @include('hisdb.dieteticCareNotes.dieteticCareNotes_tab')
                        </div>
                        <div id="tab-dietOrderIP" class="tab-pane fade">
                            @include('hisdb.dietorder.dietorder_tab')
                        </div>
                        <div id="tab-userfile_div" class="tab-pane fade">
                            @include('patientcare.userfile_div')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($phase == 'OP')
        <div id="jqGridWardMain_panel" class="panel-collapse collapse" data-curtype='navtab_triageOP'>
            <div class="panel-body paneldiv" style="overflow-y: auto;">
                <div class='col-md-12' style="padding: 0 0 15px 0;">
                    <ul class="nav nav-tabs" id="jqGridWardMain_panel_tabs">
                        <li><a data-toggle="tab" id="navtab_triageOP" href="#tab-triageOP" data-type='triageOP'>Triage</a></li>
                        <li><a data-toggle="tab" id="navtab_nursingnoteOP" href="#tab-nursingnoteOP" data-type='nursingnoteOP'>Nursing Note</a></li>
                        <li><a data-toggle="tab" id="navtab_antenatalOP" href="#tab-antenatalOP" data-type='antenatalOP'>Antenatal</a></li>
                        <li><a data-toggle="tab" id="navtab_paediatricOP" href="#tab-paediatricOP" data-type='paediatricOP'>Paediatric</a></li>
                        <li><a data-toggle="tab" id="navtab_docNoteOP" href="#tab-docNoteOP" data-type='docNoteOP'>Doctor Note</a></li>
                        <li><a data-toggle="tab" id="navtab_docNoteRefOP" href="#tab-docNoteRefOP" data-type='docNoteRefOP'>Doctor Note<br>(Referral)</a></li>
                        <li><a data-toggle="tab" id="navtab_reqForOP" href="#tab-reqForOP" data-type='reqForOP'>Request For</a></li>
                        <li><a data-toggle="tab" id="navtab_dietNoteOP" href="#tab-dietNoteOP" data-type='dietNoteOP'>Dietetic<br>Care Notes</a></li>
                        <li><a data-toggle="tab" id="navtab_userfile_div" href="#tab-userfile_div" data-type='userfile_div'>Document Imaging</a></li>
                    </ul>
                    <div class="tab-content" style="padding: 10px 5px;">
                        <input id="mrn_wardMain" name="mrn_wardMain" type="hidden">
                        <input id="episno_wardMain" name="episno_wardMain" type="hidden">
                        <input id="doctor_wardMain" name="doctor_wardMain" type="hidden">
                        <input id="ward_wardMain" name="ward_wardMain" type="hidden">
                        <input id="bednum_wardMain" name="bednum_wardMain" type="hidden">
                        <input id="age_wardMain" name="age_wardMain" type="hidden">
                        <input id="isPregnant_wardMain" name="isPregnant_wardMain" type="hidden">
                        <input type="hidden" id="ordcomtt_phar" value="{{$ordcomtt_phar ?? ''}}">
                        
                        <div id="tab-triageOP" class="active in tab-pane fade">
                            @include('hisdb.OutPatient.triageOP')
                        </div>
                        <div id="tab-nursingnoteOP" class="tab-pane fade">
                            @include('hisdb.nursingnote.nursingnote_tab')
                        </div>
                        <div id="tab-antenatalOP" class="tab-pane fade">
                            @include('hisdb.antenatal.antenatal_tab')
                        </div>
                        <div id="tab-paediatricOP" class="tab-pane fade">
                            @include('hisdb.OutPatient.paediatricOP')
                        </div>
                        <div id="tab-docNoteOP" class="tab-pane fade">
                            @include('hisdb.clientprogressnote.clientprogressnote_tab')
                        </div>
                        <div id="tab-docNoteRefOP" class="tab-pane fade">
                            @include('hisdb.clientprogressnote.clientprogressnoteref_tab')
                        </div>
                        <div id="tab-reqForOP" class="tab-pane fade">
                            <!-- #include('hisdb.requestfor.requestfor_tab') -->
                            <iframe id='requestfor_main_iframe' src='' style="height: calc(90vh);width: 100%; border: none;overflow-x: hidden;"></iframe>
                        </div>
                        <div id="tab-dietNoteOP" class="tab-pane fade">
                            @include('hisdb.dieteticCareNotes.dieteticCareNotes_tab')
                        </div>
                        <div id="tab-userfile_div" class="tab-pane fade">
                            @include('patientcare.userfile_div')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@if($phase == 'OP')
<div id="dialognewexamFormTriage" title="New exam">
    <div class='panel panel-info'>
        <div class="panel-body" style="">
            <form class='form-horizontal' style=''>
                <small>Exam Code</small> 
                <input type="text" name="examcodes" id="examcodes" class="form-control">
                <small>Description</small> 
                <input type="text" name="descriptions" id="descriptions" class="form-control">
            </form>
        </div>
    </div>
</div>
@endif
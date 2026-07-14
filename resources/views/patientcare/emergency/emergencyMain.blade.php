<div class="ui segments" style="position: relative;padding: 10px">    
    <form id="formEmergencyMain" class="ui form">
        <div class="ui grid">
            <input id="mrn_emergencyMain" name="mrn_emergencyMain" type="hidden">
            <input id="episno_emergencyMain" name="episno_emergencyMain" type="hidden">
            <input id="age_emergencyMain" name="age_emergencyMain" type="hidden">
            <input id="ptname_emergencyMain" name="ptname_emergencyMain" type="hidden">
            <input id="preg_emergencyMain" name="preg_emergencyMain" type="hidden">
            <input id="ic_emergencyMain" name="ic_emergencyMain" type="hidden">
            <input id="doctorname_emergencyMain" name="doctorname_emergencyMain" type="hidden">
            <input id="recorddate_emergencyMain" name="recorddate_emergencyMain" type="hidden">
            <input id="mrn_emergencyMain_past" name="mrn_emergencyMain_past" type="hidden">
            <input id="episno_emergencyMain_past" name="episno_emergencyMain_past" type="hidden">
        </div>
    </form>
    
    <div id="emergencyMain_tab" class="ui segment">
        <div class="ui top attached tabular menu">
            <a class="item" data-tab="userfile" id="navtab_userfile">Document Imaging</a>
            <a class="item active" data-tab="nursing_ed" id="navtab_nursing_ed">Emergency Nursing Assessment</a>
            <a class="item" data-tab="nursNote" id="navtab_nursNote">Nursing Note</a>
            <a class="item" data-tab="doctornote" id="navtab_doctornote">Doctor Note</a>
            <a class="item" data-tab="requestFor" id="navtab_requestFor">Request For</a>
            <a class="item" data-tab="admHandover" id="navtab_admHandover">Admission Handover</a>
            <!-- <a class="item" data-tab="diet" id="navtab_diet">Dietetic Care Notes</a> -->

        </div>

        <div class="ui bottom attached tab raised segment" data-tab="userfile">
                @include('patientcare.emergency.userfile_div')
        </div>
        
        <div class="ui bottom attached tab raised segment active" data-tab="nursing_ed">
                @include('patientcare.emergency.nursing')
        </div>

        <div class="ui bottom attached tab raised segment" data-tab="nursNote">
                @include('patientcare.nursingnote')
        </div>

        <div class="ui bottom attached tab raised segment" data-tab="doctornote">
            @include('patientcare.emergency.doctornote_ED')
        </div>

        <div class="ui bottom attached tab raised segment" data-tab="requestFor">
            @include('patientcare.emergency.requestfor')
        </div>

        <div class="ui bottom attached tab raised segment" data-tab="admHandover">
            @include('patientcare.emergency.admhandover')
        </div>

        <div class="ui bottom attached tab raised segment" data-tab="diet" style="display:none">
            @include('patientcare.emergency.dieteticCareNotes')
        </div>
    </div>
</div>
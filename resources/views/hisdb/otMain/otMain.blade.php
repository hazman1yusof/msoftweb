<div class="ui segments" style="position: relative;padding: 10px">
    
    <!-- <div class="ui segment" style=""> -->
        <form id="formOtMain" class="ui form">
            <div class="ui grid">
                <input id="mrn_otMain" name="mrn_otMain" type="hidden">
                <input id="episno_otMain" name="episno_otMain" type="hidden">
                <input id="age_otMain" name="age_otMain" type="hidden">
            </div>
        </form>
        
        <div id="otMain_tab" class="ui segment">
            <div class="ui top attached tabular menu">
                <a class="item active" data-tab="preoperative" id="navtab_preoperative">Pre-Operative<br>Checklist</a>
                <a class="item" data-tab="preoperativeDC" id="navtab_preoperativeDC">Pre-Operative<br>Checklist (Daycare)</a>
                <a class="item" data-tab="oper_team" id="navtab_oper_team">Operating Team<br>Checklist</a>
                <a class="item" data-tab="otswab" id="navtab_otswab">Swab & Instrument<br>Count Form</a>
                <a class="item" data-tab="ottime" id="navtab_ottime">OT Time Record</a>
                <a class="item" data-tab="otdischarge" id="navtab_otdischarge">Pre-Discharge Check</a>
                <a class="item" data-tab="endoscopyNotes" id="navtab_endoscopyNotes">Endoscopy Notes</a>
                <a class="item" data-tab="otmanagement_div" id="navtab_otmanagement_div">Operation Record</a>
            </div>
            
            <div class="ui bottom attached tab raised segment active" data-tab="preoperative">
                @include('hisdb.preoperative.preoperative')
            </div>
            
            <div class="ui bottom attached tab raised segment" data-tab="preoperativeDC">
                @include('hisdb.preoperativeDC.preoperativeDC')
            </div>

            <div class="ui bottom attached tab raised segment" data-tab="oper_team">
                @include('hisdb.oper_team.oper_team')
            </div>

            <div class="ui bottom attached tab raised segment" data-tab="otswab">
                @include('hisdb.otswab.otswab')
            </div>

            <div class="ui bottom attached tab raised segment" data-tab="ottime">
                @include('hisdb.ottime.ottime')
            </div>

            <div class="ui bottom attached tab raised segment" data-tab="otdischarge">
                @include('hisdb.otdischarge.otdischarge')
            </div>

            <div class="ui bottom attached tab raised segment" data-tab="endoscopyNotes">
                @include('hisdb.endoscopyNotes.endoscopyNotes')
            </div>

            <div class="ui bottom attached tab raised segment" data-tab="otmanagement_div">
                @include('hisdb.otmanagement.otmanagement_div')
            </div>
        </div>
    <!-- </div> -->
</div>
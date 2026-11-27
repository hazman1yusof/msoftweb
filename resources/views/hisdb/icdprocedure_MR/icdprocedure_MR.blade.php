<div class="panel panel-default" style="position: relative;margin-bottom: 0px;" id="icdprocedure_panel">
    <div class="panel-heading clearfix collapsed position" id="toggle_wardMain" style="position: sticky; top: 0px; z-index: 3; height: 65px;">
        <span id="icdprocedure_panel_title" style="display: none;">
            <b>NAME: <span id="name_show_icdprocedure"></span></b><br>
            MRN: <span id="mrn_show_icdprocedure"></span>
            IC: <span id="ic_show_icdprocedure"></span>
            SEX: <span id="sex_show_icdprocedure"></span>
            DOB: <span id="dob_show_icdprocedure"></span>
            AGE: <span id="age_show_icdprocedure"></span>
            RACE: <span id="race_show_icdprocedure"></span><br>
            RELIGION: <span id="religion_show_icdprocedure"></span>
            OCCUPATION: <span id="occupation_show_icdprocedure"></span>
            CITIZENSHIP: <span id="citizenship_show_icdprocedure"></span>
            AREA: <span id="area_show_icdprocedure"></span>
        </span>
        
        <i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridIcdProcedure_panel"></i>
        <i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridIcdProcedure_panel"></i>
        <div class="pull-right" style="position: absolute; padding: 0 0 0 0; right:40px; top: 25px;">
            <h5>Medical Record ICD & Procedure</h5>
        </div>		
    </div>
    
    <div id="jqGridIcdProcedure_panel" class="panel-collapse collapse" data-curtype='navtab_FinalDiag'>
        <div class="panel-body paneldiv" style="overflow-y: auto;">
            <div class='col-md-12' style="padding:0 0 15px 0">
                <form class='form-horizontal' style='width:99%' id='formIcdProcedure'>
                            <input id="mrn_icdprocedure" name="mrn_icdprocedure" type="hidden">
                            <input id="episno_icdprocedure" name="episno_icdprocedure" type="hidden">
                </form>
                <ul class="nav nav-tabs" id="jqGridIcdProcedure_panel_tabs">
                    <li><a data-toggle="tab" id="navtab_FinalDiag" href="#tab-FinalDiag" data-type='FinalDiag'>Final Diagnosis</a></li>
                    <li><a data-toggle="tab" id="navtab_ExternalCause" href="#tab-ExternalCause" data-type='ExternalCause'>External Cause</a></li>
                    <li><a data-toggle="tab" id="navtab_UnderCause" href="#tab-UnderCause" data-type='UnderCause'>Underlying Cause</a></li>
                    <li><a data-toggle="tab" id="navtab_ProcedureIcd" href="#tab-ProcedureIcd" data-type='ProcedureIcd'>Procedure</a></li>
                </ul>
                <div class="tab-content" style="padding: 10px 5px;">
                    <div id="tab-FinalDiag" class="active in tab-pane fade">
                        <div class="panel panel-info" id="jqGridFinalDiag_c">
                            <div class="panel-heading text-center">FINAL DIAGNOSIS</div>
                            <div class="panel-body">
                                <div class='col-md-12' style="padding:0 0 13px 0">
                                    <table id="jqGridFinalDiag" class="table table-striped"></table>
                                    <div id="jqGridPagerFinalDiag"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tab-ExternalCause" class="tab-pane fade">
                        <div class="panel panel-info" id="jqGridExternalCause_c">
                            <div class="panel-heading text-center">EXTERNAL CAUSE</div>
                            <div class="panel-body">
                                <div class='col-md-12' style="padding:0 0 13px 0">
                                    <table id="jqGridExternalCause" class="table table-striped"></table>
                                    <div id="jqGridPagerExternalCause"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tab-UnderCause" class="tab-pane fade">
                        <div class="panel panel-info" id="jqGridUnderCause_c">
                            <div class="panel-heading text-center">UNDERLYING CAUSE</div>
                            <div class="panel-body">
                                <div class='col-md-12' style="padding:0 0 13px 0">
                                    <table id="jqGridUnderCause" class="table table-striped"></table>
                                    <div id="jqGridPagerUnderCause"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tab-ProcedureIcd" class="tab-pane fade">
                        <div class="panel panel-info" id="jqGridProcedureIcd_c">
                            <div class="panel-heading text-center">PROCEDURE</div>
                            <div class="panel-body">
                                <div class='col-md-12' style="padding:0 0 13px 0">
                                    <table id="jqGridProcedureIcd" class="table table-striped"></table>
                                    <div id="jqGridPagerProcedureIcd"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="panel panel-default" style="position: relative;" id="jqGridRequestFor_c">
    <input type="hidden" name="curr_user_requestFor" id="curr_user_requestFor" value="{{ Auth::user()->username }}">
    
    <div class="panel-heading clearfix collapsed position" id="toggle_requestFor" style="position: sticky; top: 0px; z-index: 3;">
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
        
        <i class="arrow fa fa-angle-double-up" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridRequestFor_panel"></i>
        <i class="arrow fa fa-angle-double-down" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridRequestFor_panel"></i>
        <div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 25px;">
            <h5>Request For</h5>
        </div>
        
        <!-- <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
            id="btn_grp_edit_requestFor" 
            style="position: absolute;
                    padding: 0 0 0 0;
                    right: 40px;
                    top: 25px;">
            <button type="button" class="btn btn-default" id="new_requestFor">
                <span class="fa fa-plus-square-o"></span> New 
            </button>
            <button type="button" class="btn btn-default" id="edit_requestFor">
                <span class="fa fa-edit fa-lg"></span> Edit 
            </button>
            <button type="button" class="btn btn-default" data-oper='add' id="save_requestFor">
                <span class="fa fa-save fa-lg"></span> Save 
            </button>
            <button type="button" class="btn btn-default" id="cancel_requestFor">
                <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
            </button>
        </div> -->
    </div>
    
    <div id="jqGridRequestFor_panel" class="panel-collapse collapse" data-curtype='navtab_otbookReqFor'>
        <div class="panel-body paneldiv" style="overflow-y: auto;">
            <div class='col-md-12' style="padding: 0 0 15px 0;">
                <div class="col-md-10" style="padding: 0 0 0 5px; float: right;">
                    <form class='form-horizontal' style='width: 99%;' id='formRequestFor'>
                        <input id="mrn_requestFor" name="mrn_requestFor" type="hidden">
                        <input id="episno_requestFor" name="episno_requestFor" type="hidden">
                        <input id="age_requestFor" name="age_requestFor" type="hidden">
                        <!-- <input id="recorddate_requestFor" name="recorddate_requestFor" type="hidden"> -->
                        <input id="ptname_requestFor" name="ptname_requestFor" type="hidden">
                        <input id="preg_requestFor" name="preg_requestFor" type="hidden">
                        <input id="ic_requestFor" name="ic_requestFor" type="hidden">
                        <input id="doctorname_requestFor" name="doctorname_requestFor" type="hidden">
                    </form>
                </div>
            </div>
            
            <div class='col-md-12' style="padding: 0 0 15px 0;">
                <div class="panel panel-info">
                    <!-- <div class="panel-heading text-center">REQUEST FOR</div> -->
                    <div class="panel-body">
                        <ul class="nav nav-tabs" id="jqGridRequestFor_panel_tabs">
                            <li class="active"><a data-toggle="tab" id="navtab_otbookReqFor" href="#tab-otbookReqFor" aria-expanded="true" data-type='OTBOOK_REQFOR'>Ward / OT</a></li>
                            <li><a data-toggle="tab" id="navtab_radReqFor" href="#tab-radReqFor" data-type='RAD_REQFOR'>Radiology</a></li>
                            <li><a data-toggle="tab" id="navtab_physioReqFor" href="#tab-physioReqFor" data-type='PHYSIO_REQFOR'>Physiotherapy</a></li>
                            <li><a data-toggle="tab" id="navtab_dressingReqFor" href="#tab-dressingReqFor" data-type='DRESSING_REQFOR'>Dressing</a></li>
                            
                        </ul>
                        <div class="tab-content" style="padding: 10px 5px;">
                            <div id="tab-otbookReqFor" class="active in tab-pane fade">
                                <form class='form-horizontal' style='width: 99%;' id='formOTBookReqFor'>
                                    <div class='col-md-12'>
                                        <div class="panel panel-default">
                                            <div class="panel-heading text-center" style="position: sticky; top: 0px; z-index: 3; height: 40px;">
                                                <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                                    id="btn_grp_edit_otbookReqFor" 
                                                    style="position: absolute; 
                                                            padding: 0 0 0 0; 
                                                            right: 40px; 
                                                            top: 5px;">
                                                    <button type="button" class="btn btn-default" id="new_otbookReqFor">
                                                        <span class="fa fa-plus-square-o"></span> New 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="edit_otbookReqFor">
                                                        <span class="fa fa-edit fa-lg"></span> Edit 
                                                    </button>
                                                    <button type="button" class="btn btn-default" data-oper='add' id="save_otbookReqFor">
                                                        <span class="fa fa-save fa-lg"></span> Save 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="cancel_otbookReqFor">
                                                        <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="otbookReqFor_chart">
                                                        <span class="fa fa-print fa-lg"></span> Print 
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- <button class="btn btn-default btn-sm" type="button" id="otbookReqFor_chart" style="float: right; margin: 10px 40px 10px 0px;">Print</button> -->
                                            
                                            <div class="panel-body">
                                                @include('hisdb.requestfor.otbook_vitalsign')
                                                
                                                <div class='col-md-12'>
                                                    <div class="panel panel-info">
                                                        <div class="panel-body">
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="otReqFor_iPesakit">iPesakit</label>
                                                                <div class="col-md-2">
                                                                    <input id="otReqFor_iPesakit" name="iPesakit" type="text" class="form-control input-sm">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="req_type">Type</label>
                                                                <div class="col-md-6">
                                                                    <label class="radio-inline">
                                                                        <input type="radio" id="req_type_ward" name="req_type" value="WARD">Ward
                                                                    </label>
                                                                    <label class="radio-inline">
                                                                        <input type="radio" id="req_type_ot" name="req_type" value="OT">OT
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-row">
                                                                <label class="col-md-3 control-label" for="req_type"></label>
                                                                <div class="form-group col-md-2" style="margin-left: 2px;">
                                                                    <label for="vs_bloodpressure">Bed</label>
                                                                        <input id="ReqFor_bed" name="ReqFor_bed" type="text" class="form-control input-sm" rdonly readonly>
                                                                </div>
                                                                
                                                                <div class="form-group col-md-2" style="margin-left: 2px;">
                                                                    <label for="ReqFor_ward">Ward</label>
                                                                        <input id="ReqFor_ward" name="ReqFor_ward" type="text" class="form-control input-sm" rdonly readonly>
                                                                </div>
                                                                
                                                                <div class="form-group col-md-2" style="margin-left: 2px;">
                                                                    <label for="ReqFor_room">Room</label>
                                                                        <input id="ReqFor_room" name="ReqFor_room" type="text" class="form-control input-sm" rdonly readonly>
                                                                </div>
                                                                
                                                                <div class="form-group col-md-2" style="margin-left: 2px;">
                                                                    <label for="ReqFor_bedtype">Bed Type</label>
                                                                        <input id="ReqFor_bedtype" name="ReqFor_bedtype" type="text" class="form-control input-sm" rdonly readonly>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="ReqFor_op_date">Date for OP</label>
                                                                <div class="col-md-4">
                                                                    <input id="ReqFor_op_date" name="op_date" type="date" class="form-control input-sm">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="ReqFor_oper_type">Type of Operation / Procedure</label>
                                                                <div class="col-md-6">
                                                                    <input id="ReqFor_oper_type" name="oper_type" type="text" class="form-control input-sm" style="text-transform: none;">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="adm_type">Type of Admission</label>
                                                                <div class="col-md-6">
                                                                    <label class="radio-inline">
                                                                        <input type="radio" name="adm_type" value="DC">Day Case
                                                                    </label>
                                                                    <label class="radio-inline">
                                                                        <input type="radio" name="adm_type" value="IP">In Patient
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="anaesthetist">Anaesthetist</label>
                                                                <div class="col-md-6">
                                                                    <label class="radio-inline">
                                                                        <input type="radio" name="anaesthetist" value="1">Required
                                                                    </label>
                                                                    <label class="radio-inline">
                                                                        <input type="radio" name="anaesthetist" value="0">Not Required
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="otReqFor_diagnosis">Diagnosis</label>
                                                                <div class="col-md-6">
                                                                    <textarea id="otReqFor_diagnosis" name="ot_diagnosis" type="text" class="form-control input-sm"></textarea>
                                                                    
                                                                    <label class="col-md-4 control-label" for="otReqFor_diagnosedby" style="padding-top: 12px;">Diagnosed By</label>
                                                                    <div class="col-md-6" style="padding-top: 5px;">
                                                                        <input id="otReqFor_diagnosedby" name="ot_diagnosedby" type="text" class="form-control input-sm">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="otReqFor_remarks">Special remarks / instructions for medication or any related to case</label>
                                                                <div class="col-md-6">
                                                                    <textarea id="otReqFor_remarks" name="ot_remarks" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="otReqFor_doctorname">Doctor's Name</label>
                                                                <div class="col-md-6">
                                                                    <input id="otReqFor_doctorname" name="ot_doctorname" type="text" class="form-control input-sm">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="otReqFor_lastuser">Entered By</label>
                                                                <div class="col-md-6">
                                                                    <input id="otReqFor_lastuser" name="ot_lastuser" type="text" class="form-control input-sm">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class='col-md-12' id="ReqFor_Bed_div" style="display: none;">
                                                    <div class="panel panel-info">
                                                        <div class="panel-heading text-center">BED</div>
                                                        <div class="panel-body">
                                                            <iframe id='wardbook_iframe' src='' style="height: calc(65vh);width: 100%; border: none;overflow:auto;"></iframe>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class='col-md-12' id="ReqFor_OT_div" style="display: none;">
                                                    <div class="panel panel-info">
                                                        <div class="panel-heading text-center">OT</div>
                                                        <div class="panel-body">
                                                            <iframe id='otbook_iframe' src='' style="height: calc(95vh);width: 100%; border: none;"></iframe>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="tab-radReqFor" class="tab-pane fade">
                                <ul class="nav nav-tabs" id="jqGridRequestFor_rad_tabs">
                                    <li class="active"><a data-toggle="tab" id="navtab_radClinicReqFor" href="#tab-radClinicReqFor" aria-expanded="true" data-type='RADCLINIC_REQFOR'>Radiology Form</a></li>
                                    <li><a data-toggle="tab" id="navtab_mriReqFor" href="#tab-mriReqFor" data-type='MRI_REQFOR'>Checklist MRI</a></li>
                                    <li><a data-toggle="tab" id="navtab_preContrastReqFor" href="#tab-preContrastReqFor" data-type='PRECONTRAST_REQFOR'>Pre-Contrast Questionnaire</a></li>
                                    <li><a data-toggle="tab" id="navtab_consentReqFor" href="#tab-consentReqFor" data-type='CONSENT_REQFOR'>Consent Form</a></li>
                                </ul>
                                <div class="tab-content" style="padding: 10px 5px;">
                                    <div id="tab-radClinicReqFor" class="active in tab-pane fade">
                                        <form class='form-horizontal' style='width: 99%;' id='formRadClinicReqFor'>
                                            <div class='col-md-12'>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading text-center" style="position: sticky; top: 0px; z-index: 3; height: 40px;">
                                                        <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                                            id="btn_grp_edit_radClinicReqFor" 
                                                            style="position: absolute; 
                                                                    padding: 0 0 0 0; 
                                                                    right: 40px; 
                                                                    top: 5px;">
                                                            <button type="button" class="btn btn-default" id="new_radClinicReqFor">
                                                                <span class="fa fa-plus-square-o"></span> New 
                                                            </button>
                                                            <button type="button" class="btn btn-default" id="edit_radClinicReqFor">
                                                                <span class="fa fa-edit fa-lg"></span> Edit 
                                                            </button>
                                                            <button type="button" class="btn btn-default" data-oper='add' id="save_radClinicReqFor">
                                                                <span class="fa fa-save fa-lg"></span> Save 
                                                            </button>
                                                            <button type="button" class="btn btn-default" id="cancel_radClinicReqFor">
                                                                <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                                            </button>
                                                            <button type="button" class="btn btn-default" id="radClinicReqFor_chart">
                                                                <span class="fa fa-print fa-lg"></span> Print 
                                                            </button>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- <button class="btn btn-default btn-sm" type="button" id="radClinicReqFor_chart" style="float: right; margin: 10px 40px 10px 0px;">Print</button> -->
                                                    
                                                    <div class="panel-body">
                                                        <div class='col-md-12'>
                                                            <div class="panel panel-info">
                                                                <div class="panel-body">
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 control-label" for="radReqFor_iPesakit">iPesakit</label>
                                                                        <div class="col-md-2">
                                                                            <input id="radReqFor_iPesakit" name="iPesakit" type="text" class="form-control input-sm">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 control-label" for="radReqFor_weight">Weight</label>
                                                                        <div class="col-md-2">
                                                                            <div class="input-group">
                                                                                <input id="radReqFor_weight" name="rad_weight" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                                                <span class="input-group-addon">kg</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 control-label" for="pt_condition">Patient Condition</label>
                                                                        <div class="col-md-6">
                                                                            <label class="radio-inline">
                                                                                <input type="radio" name="pt_condition" value="walking">Walking
                                                                            </label>
                                                                            <label class="radio-inline">
                                                                                <input type="radio" name="pt_condition" value="wheelchair">On Wheelchair
                                                                            </label>
                                                                            <label class="radio-inline">
                                                                                <input type="radio" name="pt_condition" value="strecher">Strecher
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 control-label" for="radReqFor_pregnant">Pregnant</label>
                                                                        <div class="col-md-6">
                                                                            <label class="radio-inline">
                                                                                <input type="radio" id="pregnantReqFor" name="rad_pregnant" value="1">Yes
                                                                            </label>
                                                                            <label class="radio-inline">
                                                                                <input type="radio" id="not_pregnantReqFor" name="rad_pregnant" value="0">No
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 control-label" for="radReqFor_LMP">LMP</label>
                                                                        <div class="col-md-2">
                                                                            <input id="radReqFor_LMP" name="LMP" type="date" class="form-control input-sm">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group" style="padding-top: 5px;">
                                                                        <label class="col-md-2 control-label" for="radReqFor_allergy">Asthma/Allergy</label>
                                                                        <div class="col-md-6">
                                                                            <textarea id="radReqFor_allergy" name="rad_allergy" type="text" class="form-control input-sm"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 control-label" for="radReqFor_exam">Examination</label>
                                                                        <div class="col-md-8">
                                                                            <table class="table table-striped">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td><input class="form-check-input" type="checkbox" id="ReqFor_xray" name="xray" value="1"></td>
                                                                                        <td><label class="form-check-label" for="ReqFor_xray">X-ray</label></td>
                                                                                        <td><input id="ReqFor_xray_date" name="xray_date" type="date" class="form-control input-sm"></td>
                                                                                        <td><textarea id="ReqFor_xray_remark" name="xray_remark" type="text" class="form-control input-sm"></textarea></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><input class="form-check-input" type="checkbox" id="ReqFor_mri" name="mri" value="1"></td>
                                                                                        <td><label class="form-check-label" for="ReqFor_mri">M.R.I</label></td>
                                                                                        <td><input id="ReqFor_mri_date" name="mri_date" type="date" class="form-control input-sm"></td>
                                                                                        <td><textarea id="ReqFor_mri_remark" name="mri_remark" type="text" class="form-control input-sm"></textarea></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><input class="form-check-input" type="checkbox" id="ReqFor_angio" name="angio" value="1"></td>
                                                                                        <td><label class="form-check-label" for="ReqFor_angio">Angio</label></td>
                                                                                        <td><input id="ReqFor_angio_date" name="angio_date" type="date" class="form-control input-sm"></td>
                                                                                        <td><textarea id="ReqFor_angio_remark" name="angio_remark" type="text" class="form-control input-sm"></textarea></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><input class="form-check-input" type="checkbox" id="ReqFor_ultrasound" name="ultrasound" value="1"></td>
                                                                                        <td><label class="form-check-label" for="ReqFor_ultrasound">Ultrasound</label></td>
                                                                                        <td><input id="ReqFor_ultrasound_date" name="ultrasound_date" type="date" class="form-control input-sm"></td>
                                                                                        <td><textarea id="ReqFor_ultrasound_remark" name="ultrasound_remark" type="text" class="form-control input-sm"></textarea></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><input class="form-check-input" type="checkbox" id="ReqFor_ct" name="ct" value="1"></td>
                                                                                        <td><label class="form-check-label" for="ReqFor_ct">C.T</label></td>
                                                                                        <td><input id="ReqFor_ct_date" name="ct_date" type="date" class="form-control input-sm"></td>
                                                                                        <td><textarea id="ReqFor_ct_remark" name="ct_remark" type="text" class="form-control input-sm"></textarea></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><input class="form-check-input" type="checkbox" id="ReqFor_fluroscopy" name="fluroscopy" value="1"></td>
                                                                                        <td><label class="form-check-label" for="ReqFor_fluroscopy">Fluroscopy</label></td>
                                                                                        <td><input id="ReqFor_fluroscopy_date" name="fluroscopy_date" type="date" class="form-control input-sm"></td>
                                                                                        <td><textarea id="ReqFor_fluroscopy_remark" name="fluroscopy_remark" type="text" class="form-control input-sm"></textarea></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><input class="form-check-input" type="checkbox" id="ReqFor_mammogram" name="mammogram" value="1"></td>
                                                                                        <td><label class="form-check-label" for="ReqFor_mammogram">Mammogram</label></td>
                                                                                        <td><input id="ReqFor_mammogram_date" name="mammogram_date" type="date" class="form-control input-sm"></td>
                                                                                        <td><textarea id="ReqFor_mammogram_remark" name="mammogram_remark" type="text" class="form-control input-sm"></textarea></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><input class="form-check-input" type="checkbox" id="ReqFor_bmd" name="bmd" value="1"></td>
                                                                                        <td><label class="form-check-label" for="ReqFor_bmd">Bone Densitometry (BMD)</label></td>
                                                                                        <td><input id="ReqFor_bmd_date" name="bmd_date" type="date" class="form-control input-sm"></td>
                                                                                        <td><textarea id="ReqFor_bmd_remark" name="bmd_remark" type="text" class="form-control input-sm"></textarea></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            
                                                                            <!-- <label class="radio-inline">
                                                                                <input type="radio" name="rad_exam" value="xray">X-ray
                                                                            </label>
                                                                            <label class="radio-inline">
                                                                                <input type="radio" name="rad_exam" value="mri">M.R.I
                                                                            </label>
                                                                            <label class="radio-inline">
                                                                                <input type="radio" name="rad_exam" value="angio">Angio
                                                                            </label>
                                                                            <label class="radio-inline">
                                                                                <input type="radio" name="rad_exam" value="ultrasound">Ultrasound
                                                                            </label>
                                                                            <label class="radio-inline">
                                                                                <input type="radio" name="rad_exam" value="ct">C.T
                                                                            </label>
                                                                            <label class="radio-inline">
                                                                                <input type="radio" name="rad_exam" value="others">Others
                                                                            </label>
                                                                            <div class="col-md-5" style="float: right; padding-left: 0px;">
                                                                                <textarea id="others_remark" name="others_remark" type="text" class="form-control input-sm"></textarea>
                                                                            </div> -->
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 control-label" for="ReqFor_clinicaldata">Clinical Data</label>
                                                                        <div class="col-md-6">
                                                                            <textarea id="ReqFor_clinicaldata" name="clinicaldata" type="text" class="form-control input-sm"></textarea>
                                                                            
                                                                            <label class="col-md-4 control-label" for="radClinicReqFor_doctorname" style="padding-top: 12px;">Doctor's Name</label>
                                                                            <div class="col-md-6" style="padding-top: 5px;">
                                                                                <input id="radClinicReqFor_doctorname" name="radClinic_doctorname" type="text" class="form-control input-sm">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 control-label" for="ReqFor_rad_note">Radiology Note</label>
                                                                        <div class="col-md-6">
                                                                            <textarea id="ReqFor_rad_note" name="rad_note" type="text" class="form-control input-sm"></textarea>
                                                                            
                                                                            <label class="col-md-4 control-label" for="radClinicReqFor_radiologist" style="padding-top: 12px;">Radiologist's Name</label>
                                                                            <div class="col-md-6" style="padding-top: 5px;">
                                                                                <input id="radClinicReqFor_radiologist" name="radClinic_radiologist" type="text" class="form-control input-sm">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div id="tab-mriReqFor" class="tab-pane fade">
                                        <form class='form-horizontal' style='width: 99%;' id='formMRIReqFor'>
                                            <div class='col-md-12'>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading text-center" style="position: sticky; top: 0px; z-index: 3; height: 40px;">
                                                        <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                                            id="btn_grp_edit_mriReqFor" 
                                                            style="position: absolute; 
                                                                    padding: 0 0 0 0; 
                                                                    right: 40px; 
                                                                    top: 5px;">
                                                            <button type="button" class="btn btn-default" id="new_mriReqFor">
                                                                <span class="fa fa-plus-square-o"></span> New 
                                                            </button>
                                                            <button type="button" class="btn btn-default" id="edit_mriReqFor">
                                                                <span class="fa fa-edit fa-lg"></span> Edit 
                                                            </button>
                                                            <button type="button" class="btn btn-default" data-oper='add' id="save_mriReqFor">
                                                                <span class="fa fa-save fa-lg"></span> Save 
                                                            </button>
                                                            <!-- <button type="button" class="btn btn-default" id="accept_mriReqFor">
                                                                <span class="fa fa-check fa-lg"></span> Accept 
                                                            </button> -->
                                                            <button type="button" class="btn btn-default" id="cancel_mriReqFor">
                                                                <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                                            </button>
                                                            <button type="button" class="btn btn-default" id="mriReqFor_chart">
                                                                <span class="fa fa-print fa-lg"></span> Print 
                                                            </button>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- <button class="btn btn-default btn-sm" type="button" id="mriReqFor_chart" style="float: right; margin: 10px 40px 10px 0px;">Print</button> -->
                                                    
                                                    <div class="panel-body">
                                                        <div class='col-md-12'>
                                                            <div class="panel panel-info">
                                                                <div class="panel-body">
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-body">
                                                                            <div class="form-group">
                                                                                <label class="col-md-1 control-label" for="mriReqFor_weight">Weight</label>
                                                                                <div class="col-md-2">
                                                                                    <div class="input-group">
                                                                                        <input id="mriReqFor_weight" name="mri_weight" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                                                        <span class="input-group-addon">kg</span>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <label class="col-md-1 control-label" for="mriReqFor_entereddate">Date</label>
                                                                                <div class="col-md-2">
                                                                                    <input id="mriReqFor_entereddate" name="mri_entereddate" type="date" class="form-control input-sm">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <table class="table table-striped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col" colspan="2">
                                                                                    Please indicate in appropriate column, whether or not the patient has the items indicated.<br>
                                                                                    <i>Sila tandakan pada kotak yang berkenaan jika pesakit mempunyai item tersebut.</i>
                                                                                </th>
                                                                                <th scope="col"></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <th scope="row">1</th>
                                                                                <td>Cardiac pacemaker (<i>Penyelaras denyutan jantung</i>)</td>
                                                                                <td width="30%">
                                                                                    <label class="radio-inline" style="padding-left: 30px;">
                                                                                        <input type="radio" name="cardiacpacemaker" value="1">Yes
                                                                                    </label>
                                                                                    <label class="radio-inline">
                                                                                        <input type="radio" name="cardiacpacemaker" value="0">No &nbsp; &nbsp;
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th scope="row">2</th>
                                                                                <td>Prosthetics valve, if yes, please specify.<br><i>Injap jantung palsu, jika ada nyatakan.</i></td>
                                                                                <td>
                                                                                    <label class="radio-inline" style="padding-left: 30px;">
                                                                                        <input type="radio" name="pros_valve" value="1">Yes
                                                                                    </label>
                                                                                    <label class="radio-inline">
                                                                                        <input type="radio" name="pros_valve" value="0">No &nbsp; &nbsp;
                                                                                    </label>
                                                                                    <div class="col-md-7" style="float: right; padding-left: 0px;">
                                                                                        <textarea class="form-control input-sm" id="ReqFor_prosvalve_rmk" name="prosvalve_rmk"></textarea>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th scope="row">3</th>
                                                                                <td>Known intraocular foreign body or history of eye injury.<br><i>Intraocular bendasing atau sejarah cedera pada mata.</i></td>
                                                                                <td>
                                                                                    <label class="radio-inline" style="padding-left: 30px;">
                                                                                        <input type="radio" name="intraocular" value="1">Yes
                                                                                    </label>
                                                                                    <label class="radio-inline">
                                                                                        <input type="radio" name="intraocular" value="0">No &nbsp; &nbsp;
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th scope="row">4</th>
                                                                                <td>Cochlear implants (ENT.)<br><i>Implant koklea (ENT).</i></td>
                                                                                <td>
                                                                                    <label class="radio-inline" style="padding-left: 30px;">
                                                                                        <input type="radio" name="cochlear_imp" value="1">Yes
                                                                                    </label>
                                                                                    <label class="radio-inline">
                                                                                        <input type="radio" name="cochlear_imp" value="0">No &nbsp; &nbsp;
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th scope="row">5</th>
                                                                                <td>Neurotransmitter (brain/spinal cord pacemaker).<br><i>Neurotransmitter (otak/perentak saraf tunjang).</i></td>
                                                                                <td>
                                                                                    <label class="radio-inline" style="padding-left: 30px;">
                                                                                        <input type="radio" name="neurotransm" value="1">Yes
                                                                                    </label>
                                                                                    <label class="radio-inline">
                                                                                        <input type="radio" name="neurotransm" value="0">No &nbsp; &nbsp;
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th scope="row">6</th>
                                                                                <td>Bone growth stimulators.<br><i>Perangsang tumbesaran tulang.</i></td>
                                                                                <td>
                                                                                    <label class="radio-inline" style="padding-left: 30px;">
                                                                                        <input type="radio" name="bonegrowth" value="1">Yes
                                                                                    </label>
                                                                                    <label class="radio-inline">
                                                                                        <input type="radio" name="bonegrowth" value="0">No &nbsp; &nbsp;
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th scope="row">7</th>
                                                                                <td>Implantable drug infusion pumps.<br><i>Implant pam infuse ubat.</i></td>
                                                                                <td>
                                                                                    <label class="radio-inline" style="padding-left: 30px;">
                                                                                        <input type="radio" name="druginfuse" value="1">Yes
                                                                                    </label>
                                                                                    <label class="radio-inline">
                                                                                        <input type="radio" name="druginfuse" value="0">No &nbsp; &nbsp;
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th scope="row">8</th>
                                                                                <td>Cerebral surgical clips/wire.<br><i>Klip serebral.</i></td>
                                                                                <td>
                                                                                    <label class="radio-inline" style="padding-left: 30px;">
                                                                                        <input type="radio" name="surg_clips" value="1">Yes
                                                                                    </label>
                                                                                    <label class="radio-inline">
                                                                                        <input type="radio" name="surg_clips" value="0">No &nbsp; &nbsp;
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th scope="row">9</th>
                                                                                <td>Joint/limb prosthesis of metallic ferromagnetic materials.<br><i>Anggota badan palsu dari bahan feromagnetic.</i></td>
                                                                                <td>
                                                                                    <label class="radio-inline" style="padding-left: 30px;">
                                                                                        <input type="radio" name="jointlimb_pros" value="1">Yes
                                                                                    </label>
                                                                                    <label class="radio-inline">
                                                                                        <input type="radio" name="jointlimb_pros" value="0">No &nbsp; &nbsp;
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th scope="row">10</th>
                                                                                <td>Shrapnel or bullet fragment (any of the body).<br><i>Serpihan atau peluru.</i></td>
                                                                                <td>
                                                                                    <label class="radio-inline" style="padding-left: 30px;">
                                                                                        <input type="radio" name="shrapnel" value="1">Yes
                                                                                    </label>
                                                                                    <label class="radio-inline">
                                                                                        <input type="radio" name="shrapnel" value="0">No &nbsp; &nbsp;
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th scope="row">11</th>
                                                                                <td>Any operation in the last 3 month? If yes please specify.<br><i>Pembedahan dalam masa 3 bulan, jika ada nyatakan.</i></td>
                                                                                <td>
                                                                                    <label class="radio-inline" style="padding-left: 30px;">
                                                                                        <input type="radio" name="oper_3mth" value="1">Yes
                                                                                    </label>
                                                                                    <label class="radio-inline">
                                                                                        <input type="radio" name="oper_3mth" value="0">No &nbsp; &nbsp;
                                                                                    </label>
                                                                                    <div class="col-md-7" style="float: right; padding-left: 0px;">
                                                                                        <textarea class="form-control input-sm" id="ReqFor_oper3mth_remark" name="oper3mth_remark"></textarea>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th scope="row">12</th>
                                                                                <td>Any previous MRI examination?<br><i>Pemeriksaan MRI sebelum ini?</i></td>
                                                                                <td>
                                                                                    <label class="radio-inline" style="padding-left: 30px;">
                                                                                        <input type="radio" name="prev_mri" value="1">Yes
                                                                                    </label>
                                                                                    <label class="radio-inline">
                                                                                        <input type="radio" name="prev_mri" value="0">No &nbsp; &nbsp;
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th scope="row">13</th>
                                                                                <td>Have you ever experienced claustrophobia?<br><i>Anda mempunyai klaustrofobia?</i></td>
                                                                                <td>
                                                                                    <label class="radio-inline" style="padding-left: 30px;">
                                                                                        <input type="radio" name="claustrophobia" value="1">Yes
                                                                                    </label>
                                                                                    <label class="radio-inline">
                                                                                        <input type="radio" name="claustrophobia" value="0">No &nbsp; &nbsp;
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th scope="row">14</th>
                                                                                <td>Dental implant (held in place by magnet).<br><i>Implant dental, mempunyai magnet.</i></td>
                                                                                <td>
                                                                                    <label class="radio-inline" style="padding-left: 30px;">
                                                                                        <input type="radio" name="dental_imp" value="1">Yes
                                                                                    </label>
                                                                                    <label class="radio-inline">
                                                                                        <input type="radio" name="dental_imp" value="0">No &nbsp; &nbsp;
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th scope="row">15</th>
                                                                                <td>Any implanted ferromagnetic materials (susuk or etc).<br><i>Mempunyai bahan-bahan ferromagnetic seperti susuk.</i></td>
                                                                                <td>
                                                                                    <label class="radio-inline" style="padding-left: 30px;">
                                                                                        <input type="radio" name="frmgnetic_imp" value="1">Yes
                                                                                    </label>
                                                                                    <label class="radio-inline">
                                                                                        <input type="radio" name="frmgnetic_imp" value="0">No &nbsp; &nbsp;
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th scope="row">16</th>
                                                                                <td>Pregnancy (1st trimester).<br><i>Mengandung trimester pertama.</i></td>
                                                                                <td>
                                                                                    <label class="radio-inline" style="padding-left: 30px;">
                                                                                        <input type="radio" name="pregnancy" value="1">Yes
                                                                                    </label>
                                                                                    <label class="radio-inline">
                                                                                        <input type="radio" name="pregnancy" value="0">No &nbsp; &nbsp;
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th scope="row">17</th>
                                                                                <td>Allergic to drug or contrast media?<br><i>Mempunyai alahan terhadap ubat atau media kontras.</i></td>
                                                                                <td>
                                                                                    <label class="radio-inline" style="padding-left: 30px;">
                                                                                        <input type="radio" name="allergy_drug" value="1">Yes
                                                                                    </label>
                                                                                    <label class="radio-inline">
                                                                                        <input type="radio" name="allergy_drug" value="0">No &nbsp; &nbsp;
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th scope="row">18</th>
                                                                                <td colspan="2">
                                                                                    Blood urea: 
                                                                                    <div class="col-md-10" style="float: right; padding-left: 0px;">
                                                                                        <input name="bloodurea" type="text" class="form-control input-sm" style="text-transform: none;">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th scope="row">19</th>
                                                                                <td colspan="2">
                                                                                    Serum creatinine: 
                                                                                    <div class="col-md-10" style="float: right; padding-left: 0px;">
                                                                                        <input name="serum_creatinine" type="text" class="form-control input-sm" style="text-transform: none;">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-body">
                                                                            <div class="form-group">
                                                                                <label class="col-md-2 control-label" for="mriReqFor_doctorname">Name of Doctor</label>
                                                                                <div class="col-md-3">
                                                                                    <input id="mriReqFor_doctorname" name="mri_doctorname" type="text" class="form-control input-sm">
                                                                                </div>
                                                                                
                                                                                <label class="col-md-3 control-label" for="mriReqFor_patientname">Name of patient/parents/guardian</label>
                                                                                <div class="col-md-3">
                                                                                    <input id="mriReqFor_patientname" name="mri_patientname" type="text" class="form-control input-sm" rdonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-body">
                                                                            <div class="form-group">
                                                                                <div class="col-md-4">
                                                                                    <label class="control-label" for="mriReqFor_radiologist" style="padding-bottom: 5px;">Doctor / Radiologist</label>
                                                                                    <input id="mriReqFor_radiologist" name="mri_radiologist" type="text" class="form-control input-sm">
                                                                                </div>
                                                                                
                                                                                <div class="col-md-4">
                                                                                    <label class="control-label" for="ReqFor_radiographer" style="padding-bottom: 5px;">Radiographer</label>
                                                                                    <input id="ReqFor_radiographer" name="radiographer" type="text" class="form-control input-sm">
                                                                                </div>
                                                                                
                                                                                <div class="col-md-4">
                                                                                    <label class="control-label" for="mriReqFor_lastuser" style="padding-bottom: 5px;">Entered By</label>
                                                                                    <input id="mriReqFor_lastuser" name="mri_lastuser" type="text" class="form-control input-sm">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div id="tab-preContrastReqFor" class="tab-pane fade">
                                        @include('hisdb.requestfor.preContrast')
                                    </div>
                                    <div id="tab-consentReqFor" class="tab-pane fade">
                                        @include('hisdb.requestfor.consentForm')
                                    </div>
                                </div>
                            </div>
                            <div id="tab-physioReqFor" class="tab-pane fade">
                                <form class='form-horizontal' style='width: 99%;' id='formPhysioReqFor'>
                                    <div class='col-md-12'>
                                        <div class="panel panel-default">
                                            <div class="panel-heading text-center" style="position: sticky; top: 0px; z-index: 3; height: 40px;">
                                                <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                                    id="btn_grp_edit_physioReqFor" 
                                                    style="position: absolute; 
                                                            padding: 0 0 0 0; 
                                                            right: 40px; 
                                                            top: 5px;">
                                                    <button type="button" class="btn btn-default" id="new_physioReqFor">
                                                        <span class="fa fa-plus-square-o"></span> New 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="edit_physioReqFor">
                                                        <span class="fa fa-edit fa-lg"></span> Edit 
                                                    </button>
                                                    <button type="button" class="btn btn-default" data-oper='add' id="save_physioReqFor">
                                                        <span class="fa fa-save fa-lg"></span> Save 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="cancel_physioReqFor">
                                                        <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="physioReqFor_chart">
                                                        <span class="fa fa-print fa-lg"></span> Print 
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- <button class="btn btn-default btn-sm" type="button" id="physioReqFor_chart" style="float: right; margin: 10px 40px 10px 0px;">Print</button> -->
                                            
                                            <div class="panel-body">
                                                <div class='col-md-12'>
                                                    <div class="panel panel-info">
                                                        <div class="panel-body">
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="ReqFor_req_date">Date</label>
                                                                <div class="col-md-4">
                                                                    <input id="ReqFor_req_date" name="req_date" type="date" class="form-control input-sm">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="ReqFor_clinic_diag">Clinical Diagnosis</label>
                                                                <div class="col-md-6">
                                                                    <textarea id="ReqFor_clinic_diag" name="clinic_diag" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="ReqFor_findings">Relevant Finding(s)</label>
                                                                <div class="col-md-6">
                                                                    <textarea id="ReqFor_findings" name="findings" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="phyReqFor_treatment">Treatment</label>
                                                                <div class="col-md-6">
                                                                    <!-- <textarea id="phyReqFor_treatment" name="phy_treatment" type="text" class="form-control input-sm"></textarea> -->
                                                                    
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" name="tr_physio" id="ReqFor_tr_physio" value="1">
                                                                        <label class="form-check-label" for="ReqFor_tr_physio">Physiotherapy</label>
                                                                    </div>
                                                                    
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" name="tr_occuptherapy" id="ReqFor_tr_occuptherapy" value="1">
                                                                        <label class="form-check-label" for="ReqFor_tr_occuptherapy">Occupational Therapy</label>
                                                                    </div>
                                                                    
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" name="tr_respiphysio" id="ReqFor_tr_respiphysio" value="1">
                                                                        <label class="form-check-label" for="ReqFor_tr_respiphysio">Respiratory Physiotherapy</label>
                                                                    </div>
                                                                    
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" name="tr_neuro" id="ReqFor_tr_neuro" value="1">
                                                                        <label class="form-check-label" for="ReqFor_tr_neuro">Neuro Rehab</label>
                                                                    </div>
                                                                    
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" name="tr_splint" id="ReqFor_tr_splint" value="1">
                                                                        <label class="form-check-label" for="ReqFor_tr_splint">Splinting</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="ReqFor_remarks">Remarks</label>
                                                                <div class="col-md-6">
                                                                    <textarea id="ReqFor_remarks" name="remarks" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="phyReqFor_doctorname">Name of Requesting Doctor</label>
                                                                <div class="col-md-6">
                                                                    <input id="phyReqFor_doctorname" name="phy_doctorname" type="text" class="form-control input-sm">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="phyReqFor_lastuser">Entered By</label>
                                                                <div class="col-md-6">
                                                                    <input id="phyReqFor_lastuser" name="phy_lastuser" type="text" class="form-control input-sm">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="tab-dressingReqFor" class="tab-pane fade">
                                <form class='form-horizontal' style='width: 99%;' id='formDressingReqFor'>
                                    <div class='col-md-12'>
                                        <div class="panel panel-default">
                                            <div class="panel-heading text-center" style="position: sticky; top: 0px; z-index: 3; height: 40px;">
                                                <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                                    id="btn_grp_edit_dressingReqFor" 
                                                    style="position: absolute; 
                                                            padding: 0 0 0 0; 
                                                            right: 40px; 
                                                            top: 5px;">
                                                    <button type="button" class="btn btn-default" id="new_dressingReqFor">
                                                        <span class="fa fa-plus-square-o"></span> New 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="edit_dressingReqFor">
                                                        <span class="fa fa-edit fa-lg"></span> Edit 
                                                    </button>
                                                    <button type="button" class="btn btn-default" data-oper='add' id="save_dressingReqFor">
                                                        <span class="fa fa-save fa-lg"></span> Save 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="cancel_dressingReqFor">
                                                        <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="dressingReqFor_chart">
                                                        <span class="fa fa-print fa-lg"></span> Print 
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- <button class="btn btn-default btn-sm" type="button" id="dressingReqFor_chart" style="float: right; margin: 10px 40px 10px 0px;">Print</button> -->
                                            
                                            <div class="panel-body">
                                                <div class='col-md-12'>
                                                    <div class="panel panel-info">
                                                        <div class="panel-body">
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="dressingReqFor_patientname">Name</label>
                                                                <div class="col-md-6">
                                                                    <input id="dressingReqFor_patientname" name="dressing_patientname" type="text" class="form-control input-sm" rdonly>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="ReqFor_patientnric">NRIC</label>
                                                                <div class="col-md-6">
                                                                    <input id="ReqFor_patientnric" name="patientnric" type="text" class="form-control input-sm" rdonly>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class='col-md-5' style="margin-left: 320px; margin-right: 320px; padding-top: 15px;">
                                                                <div class="panel panel-info">
                                                                    <div class="panel-heading text-center">FREQUENCY</div>
                                                                    <div class="panel-body">
                                                                        <div class="form-group">
                                                                            <div class="col-md-2">
                                                                                <input id="ReqFor_od_dressing" name="od_dressing" type="number" class="form-control input-sm">
                                                                            </div>
                                                                            <label class="col-md-3 control-label" for="ReqFor_od_dressing">OD Dressing</label>
                                                                        </div>
                                                                        
                                                                        <div class="form-group">
                                                                            <div class="col-md-2">
                                                                                <input id="ReqFor_bd_dressing" name="bd_dressing" type="number" class="form-control input-sm">
                                                                            </div>
                                                                            <label class="col-md-3 control-label" for="ReqFor_bd_dressing">BD Dressing</label>
                                                                        </div>
                                                                        
                                                                        <div class="form-group">
                                                                            <div class="col-md-2">
                                                                                <input id="ReqFor_eod_dressing" name="eod_dressing" type="number" class="form-control input-sm">
                                                                            </div>
                                                                            <label class="col-md-3 control-label" for="ReqFor_eod_dressing">EOD Dressing</label>
                                                                        </div>
                                                                        
                                                                        <div class="form-group">
                                                                            <div class="col-md-2">
                                                                                <input id="ReqFor_others_dressing" name="others_dressing" type="number" class="form-control input-sm">
                                                                            </div>
                                                                            <label class="col-md-3 control-label" for="ReqFor_others_dressing">Others:</label>
                                                                            <div class="col-md-6">
                                                                                <input id="ReqFor_others_name" name="others_name" type="text" class="form-control input-sm" style="text-transform: none;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="ReqFor_solution">Solution/Method</label>
                                                                <div class="col-md-6">
                                                                    <textarea id="ReqFor_solution" name="solution" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="dressingReqFor_doctorname">Doctor's Name</label>
                                                                <div class="col-md-6">
                                                                    <input id="dressingReqFor_doctorname" name="dressing_doctorname" type="text" class="form-control input-sm">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="dressingReqFor_lastuser">Entered By</label>
                                                                <div class="col-md-6">
                                                                    <input id="dressingReqFor_lastuser" name="dressing_lastuser" type="text" class="form-control input-sm">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>
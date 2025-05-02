
<div class="panel panel-default" style="position: relative;" id="jqGridNursActionPlan_c">
    <div class="panel-heading clearfix collapsed position" id="toggle_nursActionPlan" style="position: sticky; top: 0px; z-index: 3;">
        <b>NAME: <span id="name_show_nursActionPlan"></span></b><br>
        MRN: <span id="mrn_show_nursActionPlan"></span>
        SEX: <span id="sex_show_nursActionPlan"></span>
        DOB: <span id="dob_show_nursActionPlan"></span>
        AGE: <span id="age_show_nursActionPlan"></span>
        RACE: <span id="race_show_nursActionPlan"></span>
        RELIGION: <span id="religion_show_nursActionPlan"></span><br>
        OCCUPATION: <span id="occupation_show_nursActionPlan"></span>
        CITIZENSHIP: <span id="citizenship_show_nursActionPlan"></span>
        AREA: <span id="area_show_nursActionPlan"></span>
        
        <i class="arrow fa fa-angle-double-up" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridNursActionPlan_panel"></i>
        <i class="arrow fa fa-angle-double-down" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridNursActionPlan_panel"></i>
        <div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 25px;">
            <h5>Nursing Action Plan</h5>
        </div>
    </div>
    
    <div id="jqGridNursActionPlan_panel" class="panel-collapse collapse" data-curtype='navtab_treatment'>
        <div class="panel-body paneldiv" style="overflow-y: auto;">
            <div class="panel-body">
                <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                    <div class="panel panel-info">
                        <div class="panel-heading text-center" style="height: 40px;">
                            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                id="btn_grp_edit_header"
                                style="position: absolute;
                                        padding: 0 0 0 0;
                                        right: 40px;
                                        top: 5px;">
                                <button type="button" class="btn btn-default" id="new_header">
                                    <span class="fa fa-plus-square-o"></span> New 
                                </button>
                                <button type="button" class="btn btn-default" id="edit_header">
                                    <span class="fa fa-edit fa-lg"></span> Edit 
                                </button>
                                <button type="button" class="btn btn-default" data-oper='add' id="save_header">
                                    <span class="fa fa-save fa-lg"></span> Save 
                                </button>
                                <button type="button" class="btn btn-default" id="cancel_header">
                                    <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                </button> 
                            </div>
                        </div>

                        <div class="panel-body" style="padding-right: 0px;">
                            <form class='form-horizontal' style='width: 99%;' id='formHeader'>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label" for="reg_date">Date of Admission</label>
                                        <div class="col-md-2">
                                            <input id="reg_date" name="reg_date" type="date" class="form-control input-sm" rdonly>
                                        </div>
                                        
                                        <label class="col-md-2 control-label" for="diagnosis">Diagnosis</label>
                                        <div class="col-md-4">
                                            <textarea id="diagnosis" name="diagnosis" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2 control-label" for="op_date">Date of Operation</label>
                                        <div class="col-md-2">
                                            <input id="op_date" name="op_date" type="date" class="form-control input-sm">
                                        </div>

                                        <label class="col-md-2 control-label" for="operation">Operation</label>
                                        <div class="col-md-4">
                                            <textarea id="operation" name="operation" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                    <div class='col-md-12' style="padding: 0 0 15px 0;">
                        <ul class="nav nav-tabs" id="jqGridNursActionPlan_panel_tabs">
                            <li><a data-toggle="tab" id="navtab_treatment" href="#tab-treatments" data-type='treatment'>Treatment</a></li>
                            <li><a data-toggle="tab" id="navtab_observation" href="#tab-observation" data-type='observation'>Observation</a></li>
                            <li><a data-toggle="tab" id="navtab_feeding" href="#tab-feeding" data-type='feeding'>Feeding</a></li>
                            <li><a data-toggle="tab" id="navtab_imgDiag" href="#tab-imgDiag" data-type='imgDiag'>Imaging & Diagnostic</a></li>
                            <li><a data-toggle="tab" id="navtab_bloodTrans" href="#tab-bloodTrans" data-type='bloodTrans'>Blood Transfusion</a></li>
                            <li><a data-toggle="tab" id="navtab_exams" href="#tab-exams" data-type='exams'>Exam</a></li>
                            <li><a data-toggle="tab" id="navtab_procedure" href="#tab-procedure" data-type='procedure'>Procedure</a></li>

                        </ul>
                        <div class="tab-content" style="padding: 10px 5px;">
                            <input id="mrn_nursActionPlan" name="mrn_nursActionPlan" type="hidden">
                            <input id="episno_nursActionPlan" name="episno_nursActionPlan" type="hidden">
                            <input id="doctor_nursActionPlan" name="doctor_nursActionPlan" type="hidden">
                            <input id="ward_nursActionPlan" name="ward_nursActionPlan" type="hidden">
                            <input id="bednum_nursActionPlan" name="bednum_nursActionPlan" type="hidden">
                            <input id="age_nursActionPlan" name="age_nursActionPlan" type="hidden">
                            <input type="hidden" id="ordcomtt_phar" value="{{$ordcomtt_phar ?? ''}}">
                            
                            <!-- TREATMENT -->
                            <div id="tab-treatments" class="tab-pane fade">
                                <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                                    <div class="panel panel-info">
                                        <div class="panel-heading text-center" style="height: 40px;">
                                            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                                id="btn_grp_edit_treatments"
                                                style="position: absolute;
                                                        padding: 0 0 0 0;
                                                        right: 40px;
                                                        top: 5px;">
                                                <button type="button" class="btn btn-default" id="treatment_chart">
                                                    <span class="fa fa-print fa-lg"></span> Chart 
                                                </button>
                                            </div>
                                        </div>
                                                                        
                                        <div class="panel-body" style="padding-right: 0px;">
                                            <form class='form-horizontal' style='width: 99%;' id='formTreatment'>
                                                <div class='col-md-12'>
                                                    <div class="panel panel-info" id="jqGridTreatment_c">
                                                        <div class="panel-body">
                                                            <div class='col-md-12' style="padding:0 0 15px 0">
                                                                <table id="jqGridTreatment" class="table table-striped"></table>
                                                                <div id="jqGridPagerTreatment"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- OBSERVATION -->
                            <div id="tab-observation" class="tab-pane fade">
                                <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                                    <div class="panel panel-info">
                                        <div class="panel-heading text-center" style="height: 40px;">
                                            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                                id="btn_grp_edit_observation"
                                                style="position: absolute;
                                                        padding: 0 0 0 0;
                                                        right: 40px;
                                                        top: 5px;">
                                                <button type="button" class="btn btn-default" id="observation_chart">
                                                    <span class="fa fa-print fa-lg"></span> Chart 
                                                </button>
                                            </div>
                                        </div>
                                                                        
                                        <div class="panel-body" style="padding-right: 0px;">
                                            <form class='form-horizontal' style='width: 99%;' id='formObservation'>
                                                <div class='col-md-12'>
                                                    <div class="panel panel-info" id="jqGridObservation_c">
                                                        <div class="panel-body">
                                                            <div class='col-md-12' style="padding:0 0 15px 0">
                                                                <table id="jqGridObservation" class="table table-striped"></table>
                                                                <div id="jqGridPagerObservation"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- FEEDING -->
                            <div id="tab-feeding" class="tab-pane fade">
                                <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                                    <div class="panel panel-info">
                                        <div class="panel-heading text-center" style="height: 40px;">
                                            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                                id="btn_grp_edit_feeding"
                                                style="position: absolute;
                                                        padding: 0 0 0 0;
                                                        right: 40px;
                                                        top: 5px;">
                                                <button type="button" class="btn btn-default" id="feeding_chart">
                                                    <span class="fa fa-print fa-lg"></span> Chart 
                                                </button>
                                            </div>
                                        </div>
                                                                        
                                        <div class="panel-body" style="padding-right: 0px;">
                                            <form class='form-horizontal' style='width: 99%;' id='formFeeding'>
                                                <div class='col-md-12'>
                                                    <div class="panel panel-info" id="jqGridFeeding_c">
                                                        <div class="panel-body">
                                                            <div class='col-md-12' style="padding:0 0 15px 0">
                                                                <table id="jqGridFeeding" class="table table-striped"></table>
                                                                <div id="jqGridPagerFeeding"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- IMAGING & DIAGNOSTIC -->
                            <div id="tab-imgDiag" class="tab-pane fade">
                                <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                                    <div class="panel panel-info">
                                        <div class="panel-heading text-center" style="height: 40px;">
                                            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                                id="btn_grp_edit_imgDiag"
                                                style="position: absolute;
                                                        padding: 0 0 0 0;
                                                        right: 40px;
                                                        top: 5px;">
                                                <button type="button" class="btn btn-default" id="imgDiag_chart">
                                                    <span class="fa fa-print fa-lg"></span> Chart 
                                                </button>
                                            </div>
                                        </div>
                                                                        
                                        <div class="panel-body" style="padding-right: 0px;">
                                            <form class='form-horizontal' style='width: 99%;' id='formImgDiag'>
                                                <div class='col-md-12'>
                                                    <div class="panel panel-info" id="jqGridImgDiag_c">
                                                        <div class="panel-body">
                                                            <div class='col-md-12' style="padding:0 0 15px 0">
                                                                <table id="jqGridImgDiag" class="table table-striped"></table>
                                                                <div id="jqGridPagerImgDiag"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- BLOOD TRANSFUSION -->
                            <div id="tab-bloodTrans" class="tab-pane fade">
                                <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                                    <div class="panel panel-info">
                                        <div class="panel-heading text-center" style="height: 40px;">
                                            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                                id="btn_grp_edit_bloodTrans"
                                                style="position: absolute;
                                                        padding: 0 0 0 0;
                                                        right: 40px;
                                                        top: 5px;">
                                                <button type="button" class="btn btn-default" id="bloodTrans_chart">
                                                    <span class="fa fa-print fa-lg"></span> Chart 
                                                </button>
                                            </div>
                                        </div>
                                                                        
                                        <div class="panel-body" style="padding-right: 0px;">
                                            <form class='form-horizontal' style='width: 99%;' id='formBloodTrans'>
                                                <div class='col-md-12'>
                                                    <div class="panel panel-info" id="jqGridBloodTrans_c">
                                                        <div class="panel-body">
                                                            <div class='col-md-12' style="padding:0 0 15px 0">
                                                                <table id="jqGridBloodTrans" class="table table-striped"></table>
                                                                <div id="jqGridPagerBloodTrans"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- EXAM -->
                            <div id="tab-exams" class="tab-pane fade">
                                <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                                    <div class="panel panel-info">
                                        <div class="panel-heading text-center" style="height: 40px;">
                                            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                                id="btn_grp_edit_exams"
                                                style="position: absolute;
                                                        padding: 0 0 0 0;
                                                        right: 40px;
                                                        top: 5px;">
                                                <button type="button" class="btn btn-default" id="exams_chart">
                                                    <span class="fa fa-print fa-lg"></span> Chart 
                                                </button>
                                            </div>
                                        </div>
                                                                        
                                        <div class="panel-body" style="padding-right: 0px;">
                                            <form class='form-horizontal' style='width: 99%;' id='formExams'>
                                                <div class='col-md-12'>
                                                    <div class="panel panel-info" id="jqGridExams_c">
                                                        <div class="panel-body">
                                                            <div class='col-md-12' style="padding:0 0 15px 0">
                                                                <table id="jqGridExams" class="table table-striped"></table>
                                                                <div id="jqGridPagerExams"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PROCEDURE -->
                            <div id="tab-procedure" class="tab-pane fade">
                                <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                                    <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                                id="btn_grp_edit_procedure"
                                                style="position: absolute;
                                                        padding: 0 0 0 0;
                                                        right: 40px;
                                                        top: 5px;">
                                                <button type="button" class="btn btn-default" id="procedure_chart">
                                                    <span class="fa fa-print fa-lg"></span> Chart 
                                                </button>
                                            </div>
                                    
                                    <div class="panel panel-info">
                                        <div class="panel-heading text-center" style="height: 40px;" id="prod">
                                            
                                        </div>
                                                                        
                                        <div class="panel-body" style="padding-right: 0px;">

                                            <form class='form-horizontal' style='width: 99%;' id='formProcedure'>
                                                <div class='col-md-4'>
                                                    <!-- toggle radio button -->
                                                    <div class="panel panel-info" style="margin-bottom: 0px;">
                                                        <div class="panel-body" style="padding-right: 20px; padding-left: 20px;">
                                                            <table>
                                                                <tr>
                                                                    <td>
                                                                        <label class="radio-inline" style="margin-left: 30px;">
                                                                        <input id="art" name="prodType" value="artLine" type="hidden">
                                                                            <input class="form-check-input" type="radio" name="ptype" id="artLine" value="artLine" checked>
                                                                            <label class="form-check-label" for="artLine" style="padding-right: 10px;">Arterial Line</label>
                                                                        </label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <label class="radio-inline" style="margin-left: 30px;">
                                                                        <input id="pcvp" name="prodType" value="CVP" type="hidden">
                                                                            <input class="form-check-input" type="radio" name="ptype" id="cvp" value="CVP">
                                                                            <label class="form-check-label" for="cvp">CVP</label>
                                                                        </label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <label class="radio-inline" style="margin-left: 30px;">
                                                                        <input id="ven" name="prodType" value="venLine" type="hidden">
                                                                            <input class="form-check-input" type="radio" name="ptype" id="venLine" value="venLine">
                                                                            <label class="form-check-label" for="venLine">Venous Line</label>
                                                                        </label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <label class="radio-inline" style="margin-left: 30px;">
                                                                        <input id="pett" name="prodType" value="ETT" type="hidden">
                                                                            <input class="form-check-input" type="radio" name="ptype" id="ett" value="ETT">
                                                                            <label class="form-check-label" for="ett">ETT</label>
                                                                        </label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <label class="radio-inline" style="margin-left: 30px;">
                                                                        <input id="pcbd" name="prodType" value="CBD" type="hidden">
                                                                            <input class="form-check-input" type="radio" name="ptype" id="cbd" value="CBD">
                                                                            <label class="form-check-label" for="cbd">CBD</label>
                                                                        </label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <label class="radio-inline" style="margin-left: 30px;">
                                                                        <input id="psto" name="prodType" value="STO" type="hidden">
                                                                            <input class="form-check-input" type="radio" name="ptype" id="sto" value="STO">
                                                                            <label class="form-check-label" for="sto">STO</label>
                                                                        </label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <label class="radio-inline" style="margin-left: 30px;">
                                                                        <input id="wound" name="prodType" value="woundIns" type="hidden">
                                                                            <input class="form-check-input" type="radio" name="ptype" id="woundIns" value="woundIns">
                                                                            <label class="form-check-label" for="woundIns">Wound Inspection</label>
                                                                        </label>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class='col-md-8'>
                                                    <div class="panel panel-info" id="jqGridProcedure_c">
                                                        <div class="panel-body">
                                                            <div class='col-md-12' style="padding:0 0 15px 0">
                                                                <table id="jqGridProcedure" class="table table-striped"></table>
                                                                <div id="jqGridPagerProcedure"></div>
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
                <!-- </div> -->
            </div>
        </div>
	</div>
</div>


<div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
    <div class="panel panel-info">
        <div class="panel-heading text-center" style="height: 40px;">
            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                id="btn_grp_edit_glasgow"
                style="position: absolute;
                        padding: 0 0 0 0;
                        right: 40px;
                        top: 5px;">
                <button type="button" class="btn btn-default" id="new_glasgow">
                    <span class="fa fa-plus-square-o"></span> New 
                </button>
                <button type="button" class="btn btn-default" id="edit_glasgow">
                    <span class="fa fa-edit fa-lg"></span> Edit 
                </button>
                <button type="button" class="btn btn-default" data-oper='add' id="save_glasgow">
                    <span class="fa fa-save fa-lg"></span> Save 
                </button>
                <button type="button" class="btn btn-default" id="cancel_glasgow">
                    <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                </button>
                <!-- <button type="button" class="btn btn-default" id="glasgow_chart">
                    <span class="fa fa-print fa-lg"></span> Chart 
                </button> -->
            </div>
        </div>
        
        <!-- <button class="btn btn-default btn-sm" type="button" id="glasgow_chart" style="float: right; margin: 10px 40px 10px 0px;">Chart</button> -->
        
        <div class="panel-body" style="padding-right: 0px;">                
            <form class='form-horizontal' style='width: 99%;' id='formGlasgow'>
            <input id="idno_glasgow" name="idno_glasgow" type="hidden">
                
                <div class="col-md-4" style="padding: 0 0 0 0;">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <table id="datetimegcs_tbl" class="ui celled table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="scope">idno</th>
                                        <th class="scope">mrn</th>
                                        <th class="scope">episno</th>
                                        <th class="scope">Date</th>
                                        <th class="scope">Time</th>
                                        <th class="scope">Entered By</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <div class='col-md-8' style="padding-right: 0px;">
                    <div class="panel panel-info">
                        <div class="panel-body" style="padding: 15px 0px;">
                            <div class="form-inline col-md-12" style="padding-bottom: 15px;">
                                <label class="control-label" for="gcs_date" style="padding-right: 5px;">Date</label>
                                <input id="gcs_date" name="gcs_date" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information." value="<?php echo date("Y-m-d"); ?>">
                                
                                <label class="control-label" for="gcs_time" style="padding-left: 15px; padding-right: 5px;">Time</label>
                                <input id="gcs_time" name="gcs_time" type="time" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information.">
                                
                                <!-- <button class="btn btn-default btn-sm" type="button" id="doctornote_bpgraph" style="float: right; margin-right: 20px;">Chart</button> -->
                            </div>
                            
                            <div class='col-md-12'>
                                <div class="panel panel-info">
                                    <div class="panel-body">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td colspan="2" rowspan="6" class="align-middle" style="font-weight: bold;">EYES</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcsEye" value="4">Spontaneous eye opening (4)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcsEye" value="3">Open eyes to call (3)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                                <input type="radio" name="gcsEye" value="2">Open eyes to pain (2)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcsEye" value="1">No response (1)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcsEye" value="C">Eye closed by swelling (C)
                                                        </label>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2" rowspan="7" class="align-middle" style="font-weight: bold;">BEST VERBAL RESPONSE</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcsVerbal" value="5">Orientated (5)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcsVerbal" value="4">Confused (4)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                                <input type="radio" name="gcsVerbal" value="3">Inappropriate Words (3)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcsVerbal" value="2">Incomprehensible sounds (2)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcsVerbal" value="1">No response (1)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcsVerbal" value="T">Endotracheal Tube/Tracheostomy (T)
                                                        </label>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2" rowspan="7" class="align-middle" style="font-weight: bold;">MOTOR</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcsMotor" value="6">Obey command (6)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcsMotor" value="5">Localize pain (5)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                                <input type="radio" name="gcsMotor" value="4">Withdrawal Pain (4)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcsMotor" value="3">Flexion Pain (3)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcsMotor" value="2">Extension to pain (2)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcsMotor" value="1">No response (1)
                                                        </label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        
                            <div class='col-md-12'>
                                <div class="panel panel-info">
                                    <div class="panel-heading text-center">VITAL SIGN</div>
                                    <div class="panel-body">
                                        <div class="form-group col-md-6" style="margin-left: 2px;">
                                            <label class="control-label" for="gcs_hr" style="padding-bottom: 5px;">Heart Rate</label>
                                            <div class="input-group">
                                                <input id="gcs_hr" name="gcs_hr" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                <span class="input-group-addon">Bpm</span>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6" style="margin-left: 2px;">
                                            <label class="control-label" for="gcs_rr" style="padding-bottom: 5px;">Resp</label>
                                            <div class="input-group">
                                                <input id="gcs_rr" name="gcs_rr" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                <span class="input-group-addon">Min</span>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6" style="margin-left: 2px;">
                                            <label class="control-label" for="gcs_bp" style="padding-bottom: 5px;">Blood Pressure</label>
                                            <div class="input-group">
                                                <input id="gcs_bp_sys1" name="gcs_bp_sys1" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" style="width: 50%;">
                                                <input id="gcs_bp_dias2" name="gcs_bp_dias2" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" style="width: 50%;">
                                                <span class="input-group-addon">mmHg</span>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6" style="margin-left: 2px;">
                                            <label class="control-label" for="gcs_temp" style="padding-bottom: 5px;">Temperature</label>
                                            <div class="input-group">
                                                <input id="gcs_temp" name="gcs_temp" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                <span class="input-group-addon">°C</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class='col-md-12'>
                                <div class="panel panel-info">
                                    <div class="panel-heading text-center">PUPIL</div>
                                    <div class="panel-body">
                                        <img style="width:500px" src="{{ asset('img/pupilscale/pupil_scale_full.jpg') }}" class="center">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <!-- RIGHT PUPIL -->
                                                <tr>
                                                    <td rowspan="4" width="20%" style="font-weight: bold;">RIGHT</td>
                                                    <td rowspan="2" width="20%">Size</td>
                                                </tr>
                                                <tr>
                                                    <td width="60%">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcs_pupilSize_R" value="1">1
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcs_pupilSize_R" value="2">2
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcs_pupilSize_R" value="3">3
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcs_pupilSize_R" value="4">4
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcs_pupilSize_R" value="5">5
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcs_pupilSize_R" value="6">6
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td rowspan="2" width="20%">Reaction</td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcs_pupilReact_R" value="+">Reaction (+)
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcs_pupilReact_R" value="-">No Reaction (-)
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcs_pupilReact_R" value="C">Closed (C)
                                                        </label>
                                                    </td>
                                                </tr>

                                                    <!-- LEFT PUPIL -->
                                                    <tr>
                                                    <td rowspan="4" width="20%" style="font-weight: bold;">LEFT</td>
                                                    <td rowspan="2" width="20%">Size</td>
                                                </tr>
                                                <tr>
                                                    <td width="60%">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcs_pupilSize_L" value="1">1
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcs_pupilSize_L" value="2">2
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcs_pupilSize_L" value="3">3
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcs_pupilSize_L" value="4">4
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcs_pupilSize_L" value="5">5
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcs_pupilSize_L" value="6">6
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td rowspan="2" width="20%">Reaction</td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcs_pupilReact_L" value="+">Reaction (+)
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcs_pupilReact_L" value="-">No Reaction (-)
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gcs_pupilReact_L" value="C">Closed (C)
                                                        </label>
                                                    </td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class='col-md-12'>
                                <div class="panel panel-info">
                                    <div class="panel-heading text-center">LIMBS</div>
                                    <div class="panel-body">
                                        <div class="form-group col-md-6" style="margin-left: 2px;">
                                            <table class="table;border border-white">
                                                <thead>
                                                    <tr>
                                                        <th style="margin: 0px; padding: 3px 14px 14px 14px;"></th>
                                                        <th style="margin: 0px; padding: 3px 14px 14px 14px;"></th>
                                                        <th style="margin: 0px; padding: 3px 14px 14px 14px;">R</th>
                                                        <th style="margin: 0px; padding: 3px 14px 14px 14px;">L</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px; font-weight: bold;">ARM</td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">Normal Strength</td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_armNormal_R" name="gcs_armNormal_R" value="1"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_armNormal_L" name="gcs_armNormal_L" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">Weak</td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_armWeak_R" name="gcs_armWeak_R" value="1"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_armWeak_L" name="gcs_armWeak_L" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">Very Weak</td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_armVeryweak_R" name="gcs_armVeryweak_R" value="1"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_armVeryweak_L" name="gcs_armVeryweak_L" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">Spastic Flexion</td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_armSpastic_R" name="gcs_armSpastic_R" value="1"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_armSpastic_L" name="gcs_armSpastic_L" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">Extension</td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_armExtension_R" name="gcs_armExtension_R" value="1"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_armExtension_L" name="gcs_armExtension_L" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">No Reaction</td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_armNoreaction_R" name="gcs_armNoreaction_R" value="1"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_armNoreaction_L" name="gcs_armNoreaction_L" value="1"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group col-md-6" style="margin-left: 2px;">
                                            <table class="table;border border-white">
                                                <thead>
                                                    <tr>
                                                        <th style="margin: 0px; padding: 3px 14px 14px 14px;"></th>
                                                        <th style="margin: 0px; padding: 3px 14px 14px 14px;"></th>
                                                        <th style="margin: 0px; padding: 3px 14px 14px 14px;">R</th>
                                                        <th style="margin: 0px; padding: 3px 14px 14px 14px;">L</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px; font-weight: bold;">LEG</td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">Normal Strength</td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_legNormal_R" name="gcs_legNormal_R" value="1"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_legNormal_L" name="gcs_legNormal_L" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">Weak</td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_legWeak_R" name="gcs_legWeak_R" value="1"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_legWeak_L" name="gcs_legWeak_L" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">Very Weak</td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_legVeryweak_R" name="gcs_legVeryweak_R" value="1"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_legVeryweak_L" name="gcs_legVeryweak_L" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">Extension</td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_legExtension_R" name="gcs_legExtension_R" value="1"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_legExtension_L" name="gcs_legExtension_L" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">No Reaction</td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_legNoreaction_R" name="gcs_legNoreaction_R" value="1"></td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="gcs_legNoreaction_L" name="gcs_legNoreaction_L" value="1"></td>
                                                    </tr>
                                                </tbody>
                                            </table>    

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
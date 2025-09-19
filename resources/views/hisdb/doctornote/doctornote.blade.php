
<div class="panel panel-default" style="position: relative;" id="jqGridDoctorNote_c">
    <input type="hidden" name="curr_user" id="curr_user" value="{{ Auth::user()->username }}">
    
    <div class="panel-heading clearfix collapsed position" id="toggle_doctorNote" style="position: sticky; top: 0px; z-index: 3;">
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
        
        <i class="arrow fa fa-angle-double-up" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridDoctorNote_panel"></i>
        <i class="arrow fa fa-angle-double-down" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridDoctorNote_panel"></i>
        <div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 310px; top: 25px;">
            <h5>Doctor Note (Psychiatry)</h5>
        </div>
        
        <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
            id="btn_grp_edit_doctorNote" 
            style="position: absolute;
                    padding: 0 0 0 0;
                    right: 40px;
                    top: 25px;">
            <button type="button" class="btn btn-default" id="new_doctorNote">
                <span class="fa fa-plus-square-o"></span> New 
            </button>
            <button type="button" class="btn btn-default" id="edit_doctorNote">
                <span class="fa fa-edit fa-lg"></span> Edit 
            </button>
            <button type="button" class="btn btn-default" data-oper='add' id="save_doctorNote">
                <span class="fa fa-save fa-lg"></span> Save 
            </button>
            <button type="button" class="btn btn-default" id="cancel_doctorNote">
                <span class="fa fa-ban fa-lg" aria-hidden="true"></span> Cancel 
            </button>
        </div>
    </div>
    
    <div id="jqGridDoctorNote_panel" class="panel-collapse collapse" data-curtype='navtab_otbook'>
        <div class="panel-body paneldiv" style="overflow-y: auto;">
            <div class='col-md-12' style="padding: 0 0 15px 0;">
                <div class="col-md-2" style="padding-right: 0px; padding-left: 0px;">
                    <!-- toggle radio button -->
                    <div class="panel panel-info" 
                        style="margin-bottom: 0px; 
                            @if(Request::path() == 'casenote')
                                display: none
                            @endif">
                        <div class="panel-body" style="padding-top: 5px; padding-bottom: 5px;">
                            <label class="radio-inline">
                                <input class="form-check-input" type="radio" name="toggle_type" id="current" value="current" 
                                @if(Request::path() != 'casenote')
                                    checked
                                @endif>
                                <label class="form-check-label" for="current" style="padding-right: 10px;">Current</label>
                            </label>
                            <label class="radio-inline" style="margin-left: 0px;">
                                <input class="form-check-input" type="radio" name="toggle_type" id="past" value="past" 
                                @if(Request::path() == 'casenote')
                                    checked
                                @endif>
                                <label class="form-check-label" for="past">Past History</label>
                            </label>
                        </div>
                    </div>
                    
                    <!-- table docnote_date -->
                    <div id="docnote_date_tbl_sticky" style="padding: 0 0 0 0;">
                        <div class="panel panel-info" style="margin-top: 10px;">
                            <div class="panel-body">
                                <table id="docnote_date_tbl" class="ui celled table" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th class="scope">mrn</th>
                                            <th class="scope">episno</th>
                                            <th class="scope">Date</th>
                                            <th class="scope">adduser</th>
                                            <th class="scope">Doctor</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-10" style="padding: 0 0 0 5px; float: right;">
                    <form class='form-horizontal' style='width: 99%;' id='formDoctorNote'>
                        <input id="mrn_doctorNote" name="mrn_doctorNote" type="hidden">
                        <input id="episno_doctorNote" name="episno_doctorNote" type="hidden">
                        <input id="age_doctorNote" name="age_doctorNote" type="hidden">
                        <input id="recorddate_doctorNote" name="recorddate_doctorNote" type="hidden">
                        <input id="ptname_doctorNote" name="ptname_doctorNote" type="hidden">
                        <input id="preg_doctorNote" name="preg_doctorNote" type="hidden">
                        <input id="ic_doctorNote" name="ic_doctorNote" type="hidden">
                        <input id="doctorname_doctorNote" name="doctorname_doctorNote" type="hidden">
                        
                        <div class="panel panel-info">
                            <div class="panel-body">
                                <!-- <div class="col-md-12"> -->
                                    <div class="form-group">
                                        <label class="col-md-2 control-label" for="adddate">Date</label>
                                        <div class="col-md-2">
                                            <input id="adddate" name="adddate" type="date" maxlength="12" class="form-control input-sm" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" rdonly>
                                        </div>
                                        
                                        <label class="col-md-1 control-label" for="recordtime">Time</label>
                                        <div class="col-md-2">
                                            <input id="recordtime" name="recordtime" type="time" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter time.">
                                        </div>
                                    </div>
                                <!-- </div> -->
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label" for="complain">Chief Complaint</label>
                                    <div class="col-md-5">
                                        <input id="complain" name="complain" type="text" class="form-control input-sm" style="text-transform: none;">
                                    </div>
                                    
                                    @if(Auth::user()->doctor == '1')
                                        <button class="btn btn-default btn-sm" type="button" id="referLetter" style="float: right; margin-right: 40px;">Referral Letter</button>
                                        <button class="btn btn-default btn-sm" type="button" id="doctornote_medc" style="float: right; margin-right: 20px;">MC Letter</button>
                                    @endif
                                    
                                    <!-- <span class="label label-info" style="margin-left: 30px; font-size: 100%;">Written By: <span id="doctorcode" name="doctorcode"></span></span> -->
                                </div>
                                
                                <div class="col-md-12" style="padding: 0px;">
                                    <div class='col-md-9'>
                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">CLINICAL NOTE</div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        <label class="control-label" for="clinicnote" style="padding-bottom: 5px;">History of Present Illness</label>
                                                        <textarea id="clinicnote" name="clinicnote" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <label class="control-label" for="psychiatryh" style="padding-bottom: 5px;">Past Psychiatry History</label>
                                                        <textarea id="psychiatryh" name="psychiatryh" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        <label class="control-label" for="pmh" style="padding-bottom: 5px;">Previous Medical/Surgery History</label>
                                                        <textarea id="pmh" name="pmh" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <label class="control-label" for="fmh" style="padding-bottom: 5px;">Family History</label>
                                                        <textarea id="fmh" name="fmh" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        <label class="control-label" for="personalh" style="padding-bottom: 5px;">Personal History</label>
                                                        <textarea id="personalh" name="personalh" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <label class="control-label" for="drugh" style="padding-bottom: 5px;">Drug History</label>
                                                        <textarea id="drugh" name="drugh" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        <label class="control-label" for="allergyh" style="padding-bottom: 5px;">Allergy History</label>
                                                        <textarea id="allergyh" name="allergyh" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <label class="control-label" for="socialh" style="padding-bottom: 5px;">Premorbid History</label>
                                                        <textarea id="socialh" name="socialh" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        <label class="control-label" for="genappear" style="padding-bottom: 5px;">General Appearance & Behaviour</label>
                                                        <textarea id="genappear" name="genappear" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <label class="control-label" for="speech" style="padding-bottom: 5px;">Speech</label>
                                                        <textarea id="speech" name="speech" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        <label class="control-label" for="moodaffect" style="padding-bottom: 5px;">Mood & Affect</label>
                                                        <textarea id="moodaffect" name="moodaffect" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <label class="control-label" for="perception" style="padding-bottom: 5px;">Perception</label>
                                                        <textarea id="perception" name="perception" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        <label class="control-label" for="thinking" style="padding-bottom: 5px;">Thinking</label>
                                                        <textarea id="thinking" name="thinking" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <label class="control-label" for="cognitivefunc" style="padding-bottom: 5px;">Cognitive Function</label>
                                                        <textarea id="cognitivefunc" name="cognitivefunc" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-12" style="margin-top: 10px;">
                                                    <div class="panel panel-info">
                                                        <div class="panel-heading text-center">FOLLOW UP</div>
                                                        <div class="panel-body">
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <label class="col-md-2 control-label" for="followuptime">Time</label>  
                                                                    <div class="col-md-10">
                                                                        <input id="followuptime" name="followuptime" type="time" class="form-control input-sm">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group col-md-6">
                                                                    <label class="col-md-2 control-label" for="followupdate">Date</label>  
                                                                    <div class="col-md-10">
                                                                        <input id="followupdate" name="followupdate" type="date" class="form-control input-sm">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">Physical Examination</div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <!-- <label class="col-md-3 control-label" for="examination">Physical Examination</label> -->
                                                    <div class="col-md-12">
                                                        <!-- <textarea id="examination" name="examination" type="text" class="form-control input-sm"></textarea> -->
                                                        <div class="card-group">
                                                            <div class="col-md-3 card bodydia_doctornote" data-type='BF'>
                                                                <img class="card-img-top" src="{{ asset('img/bodydia1.png') }}" width="200" height="400">
                                                            </div>
                                                            <div class="col-md-3 card bodydia_doctornote" data-type='BR'>
                                                                <img class="card-img-top" src="{{ asset('img/bodydia2.png') }}" width="200" height="400">
                                                            </div>
                                                            <div class="col-md-3 card bodydia_doctornote" data-type='BL'>
                                                                <img class="card-img-top" src="{{ asset('img/bodydia3.png') }}" width="200" height="400">
                                                            </div>
                                                            <div class="col-md-3 card bodydia_doctornote" data-type='BB'>
                                                                <img class="card-img-top" src="{{ asset('img/bodydia4.png') }}" width="200" height="400">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="icdcode" style="text-align: left;">Primary ICD</label>
                                            <div class="col-md-7">
                                                <div class='input-group'>
                                                    <input id="icdcode" name="icdcode" type="text" class="form-control input-sm">
                                                    <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        
                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">Diagnosis</div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <!-- <label class="col-md-3 control-label" for="diagfinal">Diagnosis</label> -->
                                                    <div class="col-md-12">
                                                        <textarea id="diagfinal" name="diagfinal" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">Aetiology</div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <!-- <label class="col-md-3 control-label" for="aetiology">Aetiology</label> -->
                                                    <div class="col-md-12">
                                                        <textarea id="aetiology" name="aetiology" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">Investigations</div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <!-- <label class="col-md-3 control-label" for="investigate">Investigations</label> -->
                                                    <div class="col-md-12">
                                                        <textarea id="investigate" name="investigate" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">Treatment</div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <!-- <label class="col-md-3 control-label" for="treatment">Treatment</label> -->
                                                    <div class="col-md-12">
                                                        <textarea id="treatment" name="treatment" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">Prognosis</div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <!-- <label class="col-md-3 control-label" for="prognosis">Prognosis</label> -->
                                                    <div class="col-md-12">
                                                        <textarea id="prognosis" name="prognosis" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">Plan</div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <!-- <label class="col-md-3 control-label" for="plan_">Plan</label> -->
                                                    <div class="col-md-12">
                                                        <textarea id="plan_" name="plan_" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3" style="padding: 0 0 0 0;">
                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">Vital Sign</div>
                                            <div class="panel-body" style="padding-right: 0px;">
                                                <div class="form-group col-md-12" style="padding-right: 0px;">
                                                    <label class="control-label" for="bp" style="padding-bottom: 5px;">BP</label>
                                                    <div class="input-group">
                                                        <input id="bp_sys1" name="bp_sys1" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" style="width: 50%;">
                                                        <input id="bp_dias2" name="bp_dias2" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" style="width: 50%;">
                                                        <span class="input-group-addon">mmHg</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="spo2" style="padding-bottom: 5px;">SPO2</label>
                                                    <div class="input-group">
                                                        <input id="spo2" name="spo2" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                        <span class="input-group-addon">%</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="pulse" style="padding-bottom: 5px;">Pulse</label>
                                                    <div class="input-group">
                                                        <input id="pulse" name="pulse" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                        <span class="input-group-addon">Bpm</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="gxt" style="padding-bottom: 5px;">Glucometer</label>
                                                    <div class="input-group">
                                                        <input id="gxt" name="gxt" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                        <span class="input-group-addon">mmol/L</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="temperature" style="padding-bottom: 5px;">Temperature</label>
                                                    <div class="input-group">
                                                        <input id="temperature" name="temperature" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                        <span class="input-group-addon">°C</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="height" style="padding-bottom: 5px;">Height</label>
                                                    <div class="input-group">
                                                        <input id="height" name="height" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                        <span class="input-group-addon">cm</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="weight" style="padding-bottom: 5px;">Weight</label>
                                                    <div class="input-group">
                                                        <input id="weight" name="weight" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                        <span class="input-group-addon">kg</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="bmi" style="padding-bottom: 5px;">BMI</label>
                                                    <input id="bmi" name="bmi" type="number" class="form-control input-sm" rdonly>
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="respiration" style="padding-bottom: 5px;">RR</label>
                                                    <div class="input-group">
                                                        <input id="respiration" name="respiration" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                        <span class="input-group-addon">Min</span>
                                                    </div>
                                                </div>
                                                
                                                <!-- <div class="form-group col-md-12">
                                                    <label class="control-label" for="pain_score" style="padding-bottom: 5px;">Pain Score</label>
                                                    <div class="input-group">
                                                        <input id="pain_score" name="pain_score" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                        <span class="input-group-addon">/10</span>
                                                    </div>
                                                </div> -->
                                            </div>
                                        </div>
                                        
                                        <div class="panel panel-info" style="margin-top: 1002px;">
                                            <div class="panel-body">
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="adduser" style="padding-bottom: 5px;">Added by</label>  
                                                    <input id="adduser" name="adduser" type="text" class="form-control input-sm" rdonly>
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="adddate" style="padding-bottom: 5px;">Date</label>
                                                    <input id="adddate" name="adddate" type="text" class="form-control input-sm" rdonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="panel panel-info">
                                        <div class="panel-heading text-center">MEDICATION</div>
                                        <div class="panel-body" style="overflow: auto; padding: 0px;" id="jqGrid_trans_doctornote_c">
                                            <table id="jqGrid_trans_doctornote" class="table table-striped"></table>
                                            <div id="jqGrid_trans_doctornotePager"></div>
                                            <!-- <table id="medication_tbl" class="ui selectable celled table" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Items</th>
                                                        <th>Qty</th>
                                                        <th>Remarks</th>
                                                        <th>Dosage</th>
                                                        <th>Frequency</th>
                                                        <th>Instruction</th>
                                                        <th>Indicator</th>
                                                    </tr>
                                                </thead>
                                            </table> -->
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12" id="addnotes" style="display: none;">
                                    <div class="panel panel-info">
                                        <div class="panel-heading text-center">ADDITIONAL NOTES</div>
                                        <div class="panel-body">
                                            <div class='col-md-12' style="padding: 0 0 15px 0;" id="jqGridAddNotes_c">
                                                <table id="jqGridAddNotes" class="table table-striped"></table>
                                                <div id="jqGridPagerAddNotes"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
			</div>
            
            <div class='col-md-12' style="padding: 0 0 15px 0;">
                <div class="panel panel-info">
                    <div class="panel-heading text-center">REQUEST FOR</div>
                    <div class="panel-body">
                        <ul class="nav nav-tabs" id="jqGridDoctorNote_panel_tabs">
                            <li class="active"><a data-toggle="tab" id="navtab_otbook" href="#tab-otbook" aria-expanded="true" data-type='OTBOOK'>Ward / OT</a></li>
                            <li><a data-toggle="tab" id="navtab_rad" href="#tab-rad_dn" data-type='RAD'>Radiology</a></li>
                            <li><a data-toggle="tab" id="navtab_physio" href="#tab-physio" data-type='PHYSIO'>Rehab</a></li>
                            <li><a data-toggle="tab" id="navtab_dressing" href="#tab-dressing" data-type='DRESSING'>Dressing</a></li>
                        </ul>
                        <div class="tab-content" style="padding: 10px 5px;">
                            <div id="tab-otbook" class="active in tab-pane fade">
                                <form class='form-horizontal' style='width: 99%;' id='formOTBook'>
                                    <div class='col-md-12'>
                                        <div class="panel panel-default">
                                            <div class="panel-heading text-center" style="position: sticky; top: 0px; z-index: 3; height: 40px;">
                                                <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                                    id="btn_grp_edit_otbook" 
                                                    style="position: absolute; 
                                                            padding: 0 0 0 0; 
                                                            right: 40px; 
                                                            top: 5px;">
                                                    <button type="button" class="btn btn-default" id="new_otbook">
                                                        <span class="fa fa-plus-square-o"></span> New 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="edit_otbook">
                                                        <span class="fa fa-edit fa-lg"></span> Edit 
                                                    </button>
                                                    <button type="button" class="btn btn-default" data-oper='add' id="save_otbook">
                                                        <span class="fa fa-save fa-lg"></span> Save 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="cancel_otbook">
                                                        <span class="fa fa-ban fa-lg" aria-hidden="true"></span> Cancel 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="otbook_chart">
                                                        <span class="fa fa-print fa-lg"></span> Print 
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- <button class="btn btn-default btn-sm" type="button" id="otbook_chart" style="float: right; margin: 10px 40px 10px 0px;">Print</button> -->
                                            
                                            <div class="panel-body">
                                                @include('hisdb.requestfor.otbook_vitalsign')
                                                
                                                <div class='col-md-12'>
                                                    <div class="panel panel-info">
                                                        <div class="panel-body">
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="ot_iPesakit">iPesakit</label>
                                                                <div class="col-md-2">
                                                                    <input id="ot_iPesakit" name="iPesakit" type="text" class="form-control input-sm">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="req_type">Type</label>
                                                                <div class="col-md-6">
                                                                    <label class="radio-inline">
                                                                        <input type="radio" id="type_ward" name="req_type" value="WARD">Ward
                                                                    </label>
                                                                    <label class="radio-inline">
                                                                        <input type="radio" id="type_ot" name="req_type" value="OT">OT
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="op_date">Date for OP</label>
                                                                <div class="col-md-4">
                                                                    <input id="op_date" name="op_date" type="date" class="form-control input-sm">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="oper_type">Type of Operation / Procedure</label>
                                                                <div class="col-md-6">
                                                                    <input id="oper_type" name="oper_type" type="text" class="form-control input-sm" style="text-transform: none;">
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
                                                                <label class="col-md-3 control-label" for="ot_diagnosis">Diagnosis</label>
                                                                <div class="col-md-6">
                                                                    <textarea id="ot_diagnosis" name="ot_diagnosis" type="text" class="form-control input-sm"></textarea>
                                                                    
                                                                    <label class="col-md-4 control-label" for="ot_diagnosedby" style="padding-top: 12px;">Diagnosed By</label>
                                                                    <div class="col-md-6" style="padding-top: 5px;">
                                                                        <input id="ot_diagnosedby" name="ot_diagnosedby" type="text" class="form-control input-sm">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="ot_remarks">Special remarks / instructions for medication or any related to case</label>
                                                                <div class="col-md-6">
                                                                    <textarea id="ot_remarks" name="ot_remarks" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="ot_doctorname">Doctor's Name</label>
                                                                <div class="col-md-6">
                                                                    <input id="ot_doctorname" name="ot_doctorname" type="text" class="form-control input-sm">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="ot_lastuser">Entered By</label>
                                                                <div class="col-md-6">
                                                                    <input id="ot_lastuser" name="ot_lastuser" type="text" class="form-control input-sm">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class='col-md-12' id="Bed_div" style="display: none;">
                                                    <div class="panel panel-info">
                                                        <div class="panel-heading text-center">BED</div>
                                                        <div class="panel-body">
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class='col-md-12' id="OT_div" style="display: none;">
                                                    <div class="panel panel-info">
                                                        <div class="panel-heading text-center">OT</div>
                                                        <div class="panel-body">
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="tab-rad_dn" class="tab-pane fade">
                                <ul class="nav nav-tabs" id="jqGridDoctorNote_rad_tabs">
                                    <li class="active"><a data-toggle="tab" id="navtab_radClinic" href="#tab-radClinic" aria-expanded="true" data-type='RADCLINIC'>Radiology Form</a></li>
                                    <li><a data-toggle="tab" id="navtab_mri" href="#tab-mri" data-type='MRI'>Checklist MRI</a></li>
                                    <li><a data-toggle="tab" id="navtab_preContrast" href="#tab-preContrast" data-type='PRECONTRAST'>Pre-Contrast Questionnaire</a></li>
                                    <li><a data-toggle="tab" id="navtab_consent" href="#tab-consent" data-type='CONSENT'>Consent Form</a></li>
                                </ul>
                                <div class="tab-content" style="padding: 10px 5px;">
                                    <div id="tab-radClinic" class="active in tab-pane fade">
                                        <form class='form-horizontal' style='width: 99%;' id='formRadClinic'>
                                            <div class='col-md-12'>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading text-center" style="position: sticky; top: 0px; z-index: 3; height: 40px;">
                                                        <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                                            id="btn_grp_edit_radClinic" 
                                                            style="position: absolute; 
                                                                    padding: 0 0 0 0; 
                                                                    right: 40px; 
                                                                    top: 5px;">
                                                            <button type="button" class="btn btn-default" id="new_radClinic">
                                                                <span class="fa fa-plus-square-o"></span> New 
                                                            </button>
                                                            <button type="button" class="btn btn-default" id="edit_radClinic">
                                                                <span class="fa fa-edit fa-lg"></span> Edit 
                                                            </button>
                                                            <button type="button" class="btn btn-default" data-oper='add' id="save_radClinic">
                                                                <span class="fa fa-save fa-lg"></span> Save 
                                                            </button>
                                                            <button type="button" class="btn btn-default" id="cancel_radClinic">
                                                                <span class="fa fa-ban fa-lg" aria-hidden="true"></span> Cancel 
                                                            </button>
                                                            <button type="button" class="btn btn-default" id="radClinic_chart">
                                                                <span class="fa fa-print fa-lg"></span> Print 
                                                            </button>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- <button class="btn btn-default btn-sm" type="button" id="radClinic_chart" style="float: right; margin: 10px 40px 10px 0px;">Print</button> -->
                                                    
                                                    <div class="panel-body">
                                                        <div class='col-md-12'>
                                                            <div class="panel panel-info">
                                                                <div class="panel-body">
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 control-label" for="rad_iPesakit">iPesakit</label>
                                                                        <div class="col-md-2">
                                                                            <input id="rad_iPesakit" name="iPesakit" type="text" class="form-control input-sm">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 control-label" for="rad_weight">Weight</label>
                                                                        <div class="col-md-2">
                                                                            <div class="input-group">
                                                                                <input id="rad_weight" name="rad_weight" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
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
                                                                        <label class="col-md-2 control-label" for="rad_pregnant">Pregnant</label>
                                                                        <div class="col-md-6">
                                                                            <label class="radio-inline">
                                                                                <input type="radio" id="pregnant" name="rad_pregnant" value="1">Yes
                                                                            </label>
                                                                            <label class="radio-inline">
                                                                                <input type="radio" id="not_pregnant" name="rad_pregnant" value="0">No
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 control-label" for="rad_LMP">LMP</label>
                                                                        <div class="col-md-2">
                                                                            <input id="rad_LMP" name="LMP" type="date" class="form-control input-sm">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group" style="padding-top: 5px;">
                                                                        <label class="col-md-2 control-label" for="rad_allergy">Asthma/Allergy</label>
                                                                        <div class="col-md-6">
                                                                            <textarea id="rad_allergy" name="rad_allergy" type="text" class="form-control input-sm"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 control-label" for="rad_exam">Examination</label>
                                                                        <div class="col-md-8">
                                                                            <table class="table table-striped">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td><input class="form-check-input" type="checkbox" id="xray" name="xray" value="1"></td>
                                                                                        <td><label class="form-check-label" for="xray">X-ray</label></td>
                                                                                        <td><input id="xray_date" name="xray_date" type="date" class="form-control input-sm"></td>
                                                                                        <td><textarea id="xray_remark" name="xray_remark" type="text" class="form-control input-sm"></textarea></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><input class="form-check-input" type="checkbox" id="mri" name="mri" value="1"></td>
                                                                                        <td><label class="form-check-label" for="mri">M.R.I</label></td>
                                                                                        <td><input id="mri_date" name="mri_date" type="date" class="form-control input-sm"></td>
                                                                                        <td><textarea id="mri_remark" name="mri_remark" type="text" class="form-control input-sm"></textarea></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><input class="form-check-input" type="checkbox" id="angio" name="angio" value="1"></td>
                                                                                        <td><label class="form-check-label" for="angio">Angio</label></td>
                                                                                        <td><input id="angio_date" name="angio_date" type="date" class="form-control input-sm"></td>
                                                                                        <td><textarea id="angio_remark" name="angio_remark" type="text" class="form-control input-sm"></textarea></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><input class="form-check-input" type="checkbox" id="ultrasound" name="ultrasound" value="1"></td>
                                                                                        <td><label class="form-check-label" for="ultrasound">Ultrasound</label></td>
                                                                                        <td><input id="ultrasound_date" name="ultrasound_date" type="date" class="form-control input-sm"></td>
                                                                                        <td><textarea id="ultrasound_remark" name="ultrasound_remark" type="text" class="form-control input-sm"></textarea></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><input class="form-check-input" type="checkbox" id="ct" name="ct" value="1"></td>
                                                                                        <td><label class="form-check-label" for="ct">C.T</label></td>
                                                                                        <td><input id="ct_date" name="ct_date" type="date" class="form-control input-sm"></td>
                                                                                        <td><textarea id="ct_remark" name="ct_remark" type="text" class="form-control input-sm"></textarea></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><input class="form-check-input" type="checkbox" id="fluroscopy" name="fluroscopy" value="1"></td>
                                                                                        <td><label class="form-check-label" for="fluroscopy">Fluroscopy</label></td>
                                                                                        <td><input id="fluroscopy_date" name="fluroscopy_date" type="date" class="form-control input-sm"></td>
                                                                                        <td><textarea id="fluroscopy_remark" name="fluroscopy_remark" type="text" class="form-control input-sm"></textarea></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><input class="form-check-input" type="checkbox" id="mammogram" name="mammogram" value="1"></td>
                                                                                        <td><label class="form-check-label" for="mammogram">Mammogram</label></td>
                                                                                        <td><input id="mammogram_date" name="mammogram_date" type="date" class="form-control input-sm"></td>
                                                                                        <td><textarea id="mammogram_remark" name="mammogram_remark" type="text" class="form-control input-sm"></textarea></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><input class="form-check-input" type="checkbox" id="bmd" name="bmd" value="1"></td>
                                                                                        <td><label class="form-check-label" for="bmd">Bone Densitometry (BMD)</label></td>
                                                                                        <td><input id="bmd_date" name="bmd_date" type="date" class="form-control input-sm"></td>
                                                                                        <td><textarea id="bmd_remark" name="bmd_remark" type="text" class="form-control input-sm"></textarea></td>
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
                                                                        <label class="col-md-2 control-label" for="clinicaldata">Clinical Data</label>
                                                                        <div class="col-md-6">
                                                                            <textarea id="clinicaldata" name="clinicaldata" type="text" class="form-control input-sm"></textarea>
                                                                            
                                                                            <label class="col-md-4 control-label" for="radClinic_doctorname" style="padding-top: 12px;">Doctor's Name</label>
                                                                            <div class="col-md-6" style="padding-top: 5px;">
                                                                                <input id="radClinic_doctorname" name="radClinic_doctorname" type="text" class="form-control input-sm">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 control-label" for="rad_note">Radiology Note</label>
                                                                        <div class="col-md-6">
                                                                            <textarea id="rad_note" name="rad_note" type="text" class="form-control input-sm"></textarea>
                                                                            
                                                                            <label class="col-md-4 control-label" for="radClinic_radiologist" style="padding-top: 12px;">Radiologist's Name</label>
                                                                            <div class="col-md-6" style="padding-top: 5px;">
                                                                                <input id="radClinic_radiologist" name="radClinic_radiologist" type="text" class="form-control input-sm">
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
                                    <div id="tab-mri" class="tab-pane fade">
                                        <form class='form-horizontal' style='width: 99%;' id='formMRI'>
                                            <div class='col-md-12'>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading text-center" style="position: sticky; top: 0px; z-index: 3; height: 40px;">
                                                        <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                                            id="btn_grp_edit_mri" 
                                                            style="position: absolute; 
                                                                    padding: 0 0 0 0; 
                                                                    right: 40px; 
                                                                    top: 5px;">
                                                            <button type="button" class="btn btn-default" id="new_mri">
                                                                <span class="fa fa-plus-square-o"></span> New 
                                                            </button>
                                                            <button type="button" class="btn btn-default" id="edit_mri">
                                                                <span class="fa fa-edit fa-lg"></span> Edit 
                                                            </button>
                                                            <button type="button" class="btn btn-default" data-oper='add' id="save_mri">
                                                                <span class="fa fa-save fa-lg"></span> Save 
                                                            </button>
                                                            <!-- <button type="button" class="btn btn-default" id="accept_mri">
                                                                <span class="fa fa-check fa-lg"></span> Accept 
                                                            </button> -->
                                                            <button type="button" class="btn btn-default" id="cancel_mri">
                                                                <span class="fa fa-ban fa-lg" aria-hidden="true"></span> Cancel 
                                                            </button>
                                                            <button type="button" class="btn btn-default" id="mri_chart">
                                                                <span class="fa fa-print fa-lg"></span> Print 
                                                            </button>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- <button class="btn btn-default btn-sm" type="button" id="mri_chart" style="float: right; margin: 10px 40px 10px 0px;">Print</button> -->
                                                    
                                                    <div class="panel-body">
                                                        <div class='col-md-12'>
                                                            <div class="panel panel-info">
                                                                <div class="panel-body">
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-body">
                                                                            <div class="form-group">
                                                                                <label class="col-md-1 control-label" for="mri_weight">Weight</label>
                                                                                <div class="col-md-2">
                                                                                    <div class="input-group">
                                                                                        <input id="mri_weight" name="mri_weight" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                                                        <span class="input-group-addon">kg</span>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <label class="col-md-1 control-label" for="mri_entereddate">Date</label>
                                                                                <div class="col-md-2">
                                                                                    <input id="mri_entereddate" name="mri_entereddate" type="date" class="form-control input-sm">
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
                                                                                        <textarea class="form-control input-sm" id="prosvalve_rmk" name="prosvalve_rmk"></textarea>
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
                                                                                        <textarea class="form-control input-sm" id="oper3mth_remark" name="oper3mth_remark"></textarea>
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
                                                                                <label class="col-md-2 control-label" for="mri_doctorname">Name of Doctor</label>
                                                                                <div class="col-md-3">
                                                                                    <input id="mri_doctorname" name="mri_doctorname" type="text" class="form-control input-sm">
                                                                                </div>
                                                                                
                                                                                <label class="col-md-3 control-label" for="mri_patientname">Name of patient/parents/guardian</label>
                                                                                <div class="col-md-3">
                                                                                    <input id="mri_patientname" name="mri_patientname" type="text" class="form-control input-sm" rdonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-body">
                                                                            <div class="form-group">
                                                                                <div class="col-md-4">
                                                                                    <label class="control-label" for="mri_radiologist" style="padding-bottom: 5px;">Doctor / Radiologist</label>
                                                                                    <input id="mri_radiologist" name="mri_radiologist" type="text" class="form-control input-sm">
                                                                                </div>
                                                                                
                                                                                <div class="col-md-4">
                                                                                    <label class="control-label" for="radiographer" style="padding-bottom: 5px;">Radiographer</label>
                                                                                    <input id="radiographer" name="radiographer" type="text" class="form-control input-sm">
                                                                                </div>
                                                                                
                                                                                <div class="col-md-4">
                                                                                    <label class="control-label" for="mri_lastuser" style="padding-bottom: 5px;">Entered By</label>
                                                                                    <input id="mri_lastuser" name="mri_lastuser" type="text" class="form-control input-sm">
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
                                    <div id="tab-preContrast" class="tab-pane fade">
                                        @include('hisdb.doctornote.preContrast')
                                    </div>
                                    <div id="tab-consent" class="tab-pane fade">
                                        @include('hisdb.doctornote.consentForm')
                                    </div>
                                </div>
                            </div>
                            <div id="tab-physio" class="tab-pane fade">
                                <form class='form-horizontal' style='width: 99%;' id='formPhysio'>
                                    <div class='col-md-12'>
                                        <div class="panel panel-default">
                                            <div class="panel-heading text-center" style="position: sticky; top: 0px; z-index: 3; height: 40px;">
                                                <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                                    id="btn_grp_edit_physio" 
                                                    style="position: absolute; 
                                                            padding: 0 0 0 0; 
                                                            right: 40px; 
                                                            top: 5px;">
                                                    <button type="button" class="btn btn-default" id="new_physio">
                                                        <span class="fa fa-plus-square-o"></span> New 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="edit_physio">
                                                        <span class="fa fa-edit fa-lg"></span> Edit 
                                                    </button>
                                                    <button type="button" class="btn btn-default" data-oper='add' id="save_physio">
                                                        <span class="fa fa-save fa-lg"></span> Save 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="cancel_physio">
                                                        <span class="fa fa-ban fa-lg" aria-hidden="true"></span> Cancel 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="physio_chart">
                                                        <span class="fa fa-print fa-lg"></span> Print 
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- <button class="btn btn-default btn-sm" type="button" id="physio_chart" style="float: right; margin: 10px 40px 10px 0px;">Print</button> -->
                                            
                                            <div class="panel-body">
                                                <div class='col-md-12'>
                                                    <div class="panel panel-info">
                                                        <div class="panel-body">
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="req_date">Date</label>
                                                                <div class="col-md-4">
                                                                    <input id="req_date" name="req_date" type="date" class="form-control input-sm">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="clinic_diag">Clinical Diagnosis</label>
                                                                <div class="col-md-6">
                                                                    <textarea id="clinic_diag" name="clinic_diag" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="findings">Relevant Finding(s)</label>
                                                                <div class="col-md-6">
                                                                    <textarea id="findings" name="findings" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group" id='Req_treatment'>
                                                                <label class="col-md-3 control-label" for="phy_treatment">Treatment</label>
                                                                <div class="col-md-6">
                                                                    <!-- <textarea id="phy_treatment" name="phy_treatment" type="text" class="form-control input-sm"></textarea> -->
                                                                    
                                                                    <div class="col-md-12" style="padding-top: 20px; text-align: left; color: red;">
                                                                        <p id="p_error_ReqTreatment"></p>
                                                                    </div>
                                                                    
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" name="tr_physio" id="tr_physio" value="1">
                                                                        <label class="form-check-label" for="tr_physio">Physiotherapy</label>
                                                                    </div>
                                                                    
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" name="tr_occuptherapy" id="tr_occuptherapy" value="1">
                                                                        <label class="form-check-label" for="tr_occuptherapy">Occupational Therapy</label>
                                                                    </div>
                                                                    
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" name="tr_respiphysio" id="tr_respiphysio" value="1">
                                                                        <label class="form-check-label" for="tr_respiphysio">Respiratory Physiotherapy</label>
                                                                    </div>
                                                                    
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" name="tr_neuro" id="tr_neuro" value="1">
                                                                        <label class="form-check-label" for="tr_neuro">Neuro Rehab</label>
                                                                    </div>
                                                                    
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" name="tr_splint" id="tr_splint" value="1">
                                                                        <label class="form-check-label" for="tr_splint">Splinting</label>
                                                                    </div>
                                                                    
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" name="tr_speech" id="tr_speech" value="1">
                                                                        <label class="form-check-label" for="tr_speech">Speech</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="remarks">Remarks</label>
                                                                <div class="col-md-6">
                                                                    <textarea id="remarks" name="remarks" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="phy_doctorname">Name of Requesting Doctor</label>
                                                                <div class="col-md-6">
                                                                    <input id="phy_doctorname" name="phy_doctorname" type="text" class="form-control input-sm">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="phy_lastuser">Entered By</label>
                                                                <div class="col-md-6">
                                                                    <input id="phy_lastuser" name="phy_lastuser" type="text" class="form-control input-sm">
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
                            <div id="tab-dressing" class="tab-pane fade">
                                <form class='form-horizontal' style='width: 99%;' id='formDressing'>
                                    <div class='col-md-12'>
                                        <div class="panel panel-default">
                                            <div class="panel-heading text-center" style="position: sticky; top: 0px; z-index: 3; height: 40px;">
                                                <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                                    id="btn_grp_edit_dressing" 
                                                    style="position: absolute; 
                                                            padding: 0 0 0 0; 
                                                            right: 40px; 
                                                            top: 5px;">
                                                    <button type="button" class="btn btn-default" id="new_dressing">
                                                        <span class="fa fa-plus-square-o"></span> New 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="edit_dressing">
                                                        <span class="fa fa-edit fa-lg"></span> Edit 
                                                    </button>
                                                    <button type="button" class="btn btn-default" data-oper='add' id="save_dressing">
                                                        <span class="fa fa-save fa-lg"></span> Save 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="cancel_dressing">
                                                        <span class="fa fa-ban fa-lg" aria-hidden="true"></span> Cancel 
                                                    </button>
                                                    <button type="button" class="btn btn-default" id="dressing_chart">
                                                        <span class="fa fa-print fa-lg"></span> Print 
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- <button class="btn btn-default btn-sm" type="button" id="dressing_chart" style="float: right; margin: 10px 40px 10px 0px;">Print</button> -->
                                            
                                            <div class="panel-body">
                                                <div class='col-md-12'>
                                                    <div class="panel panel-info">
                                                        <div class="panel-body">
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="dressing_patientname">Name</label>
                                                                <div class="col-md-6">
                                                                    <input id="dressing_patientname" name="dressing_patientname" type="text" class="form-control input-sm" rdonly>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="patientnric">NRIC</label>
                                                                <div class="col-md-6">
                                                                    <input id="patientnric" name="patientnric" type="text" class="form-control input-sm" rdonly>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class='col-md-5' style="margin-left: 320px; margin-right: 320px; padding-top: 15px;">
                                                                <div class="panel panel-info">
                                                                    <div class="panel-heading text-center">FREQUENCY</div>
                                                                    <div class="panel-body">
                                                                        <div class="form-group">
                                                                            <div class="col-md-2">
                                                                                <input id="od_dressing" name="od_dressing" type="number" class="form-control input-sm">
                                                                            </div>
                                                                            <label class="col-md-3 control-label" for="od_dressing">OD Dressing</label>
                                                                        </div>
                                                                        
                                                                        <div class="form-group">
                                                                            <div class="col-md-2">
                                                                                <input id="bd_dressing" name="bd_dressing" type="number" class="form-control input-sm">
                                                                            </div>
                                                                            <label class="col-md-3 control-label" for="bd_dressing">BD Dressing</label>
                                                                        </div>
                                                                        
                                                                        <div class="form-group">
                                                                            <div class="col-md-2">
                                                                                <input id="eod_dressing" name="eod_dressing" type="number" class="form-control input-sm">
                                                                            </div>
                                                                            <label class="col-md-3 control-label" for="eod_dressing">EOD Dressing</label>
                                                                        </div>
                                                                        
                                                                        <div class="form-group">
                                                                            <div class="col-md-2">
                                                                                <input id="others_dressing" name="others_dressing" type="number" class="form-control input-sm">
                                                                            </div>
                                                                            <label class="col-md-3 control-label" for="others_dressing">Others:</label>
                                                                            <div class="col-md-6">
                                                                                <input id="others_name" name="others_name" type="text" class="form-control input-sm" style="text-transform: none;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="solution">Solution/Method</label>
                                                                <div class="col-md-6">
                                                                    <textarea id="solution" name="solution" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="dressing_doctorname">Doctor's Name</label>
                                                                <div class="col-md-6">
                                                                    <input id="dressing_doctorname" name="dressing_doctorname" type="text" class="form-control input-sm">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label" for="dressing_lastuser">Entered By</label>
                                                                <div class="col-md-6">
                                                                    <input id="dressing_lastuser" name="dressing_lastuser" type="text" class="form-control input-sm">
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

<div id="dialogForm" title="Referral Letter">
    <div class='col-md-6' style="padding-left: 0px; padding-right: 10px;">
        <div class="panel panel-default">
            <div class="panel-heading text-center" style="padding-top: 20px; padding-bottom: 20px;">
                <div class="pull-left" style="position: absolute; padding: 0 0 0 0; left: 15px; top: 12px;">
                    <span style="margin-left: 0px; font-size: 100%;">
                        <span id="pt_mrn" name="pt_mrn"></span> - 
                        <span id="pt_name" name="pt_name"></span>
                    </span>
                </div>
                
                <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                    id="btn_grp_edit_refLetter" 
                    style="position: absolute; 
                            padding: 0 0 0 0; 
                            right: 40px; 
                            top: 5px;">
                    <button type="button" class="btn btn-default" id="new_refLetter">
                        <span class="fa fa-plus-square-o"></span> New 
                    </button>
                    <button type="button" class="btn btn-default" id="edit_refLetter">
                        <span class="fa fa-edit fa-lg"></span> Edit 
                    </button>
                    <button type="button" class="btn btn-default" data-oper='add' id="save_refLetter">
                        <span class="fa fa-save fa-lg"></span> Save 
                    </button>
                    <button type="button" class="btn btn-default" id="cancel_refLetter">
                        <span class="fa fa-ban fa-lg" aria-hidden="true"></span> Cancel 
                    </button>
                    <!-- <a class='pull-right pointer text-primary' id='pdfgen1' href="" target="_blank">
                        <input type="button" value="PRINT">
                    </a> -->
                    <button type="button" class="btn btn-default" id="pdfgen1">
                        <span class="fa fa-print fa-lg"></span> Print 
                    </button>
                </div>
            </div>
            
            <div class="panel-body">
                <form class='form-horizontal' style='width: 99%;' id='form_refLetter'>
                    <input id="reftype" name="reftype" type="hidden" value="Psychiatrist">
                    <!-- <input id="idno_refLetter" name="idno_refLetter" type="hidden"> -->
                    <!-- <input id="mrn_refLetter" name="mrn_refLetter" type="hidden"> -->
                    <!-- <input id="episno_refLetter" name="episno_refLetter" type="hidden"> -->
                    
                    <div class="col-md-12" style="padding-left: 0px;">
                        <div class="form-group col-md-5">
                            <label class="control-label" for="refdate" style="font-weight: normal !important; padding-bottom: 3px;">Date</label>
                            <input id="refdate" name="refdate" type="date" class="form-control input-sm" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="padding-left: 0px; padding-bottom: 5px;">
                        <!-- <div class="form-group col-md-5"> -->
                            <label class="control-label" for="refaddress" style="font-weight: normal !important; padding-bottom:3px;">Address</label>
                            <textarea id="refaddress" name="refaddress" rows="3" class="form-control input-sm"></textarea>
                        <!-- </div> -->
                    </div>
                    
                    <div class="col-md-12" style="padding-left: 0px; padding-bottom: 7px;">
                        <!-- <div class="form-inline">
                            Dear Dr. <input id="refdoc" name="refdoc" type="text" class="form-control input-sm">
                        </div> -->
                        <div class="col-md-1" style="padding: 5px 0px;"> Dear Dr. </div>
                        <div class="col-md-11" style="padding-right: 0px;">
                            <input id="refdoc" name="refdoc" type="text" class="form-control input-sm" style="text-transform: none;">
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="padding-left: 0px; padding-bottom: 7px;">
                        <textarea id="reftitle" name="reftitle" rows="3" class="form-control input-sm"></textarea>
                    </div>
                    
                    <div class="col-md-12" style="padding-left: 0px; padding-bottom: 5px;">
                        <div class="form-group">
                            <div class="col-md-1 control-label" for="refdiag">Diagnosis:</div>  
                            <div class="col-md-11" style="padding-left: 30px;">
                                <textarea id="refdiag" name="refdiag" rows="3" class="form-control input-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="padding-left: 0px; padding-bottom: 5px;">
                        <div class="form-group">
                            <div class="col-md-1 control-label" for="refplan">Plan:</div>  
                            <div class="col-md-11" style="padding-left: 3px;">
                                <textarea id="refplan" name="refplan" rows="3" class="form-control input-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="padding-left: 0px; padding-bottom: 5px;">
                        <div class="form-group">
                            <div class="col-md-1 control-label" for="refprescription">Prescription:</div>  
                            <div class="col-md-11" style="padding-left: 40px;">
                                <textarea id="refprescription" name="refprescription" rows="3" class="form-control input-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <p>If I may be of any further assistance in the care of your patient, please let me know. Thank you for providing me the opportunity to participate in the care of your patients.</p>
                    
                    <p>Sincerely,</p>
                    
                    <div class="form-inline">
                        Dr. <input id="refadduser" name="adduser" type="text" class="form-control input-sm">
                    </div> <br>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6" style="padding-left: 10px; padding-right: 0px;">
        <div class="panel panel-info">
            <div class="panel-body paneldiv2" style="overflow-y: auto; padding-left: 0px; padding-right: 0px;">
                <form class='form-horizontal' style='width: 99%;' id='form_docNoteRef'>
                    <!-- <input id="idno_docNoteRef" name="idno_docNoteRef" type="hidden"> -->
                    <!-- <input id="mrn_docNoteRef" name="mrn_docNoteRef" type="hidden"> -->
                    <!-- <input id="episno_docNoteRef" name="episno_docNoteRef" type="hidden"> -->
                    <!-- <input id="recorddate_docNoteRef" name="recorddate_docNoteRef" type="hidden"> -->
                    
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="complain_ref">Chief Complaint</label>
                        <div class="col-md-9">
                            <input id="complain_ref" name="complain_ref" type="text" class="form-control input-sm" style="text-transform: none;">
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="padding: 0px;">
                        <div class='col-md-8'>
                            <div class="panel panel-info">
                                <div class="panel-heading text-center">CLINICAL NOTE</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <label class="control-label" for="clinicnote_ref" style="padding-bottom: 5px;">History of Present Illness</label>
                                            <textarea id="clinicnote_ref" name="clinicnote_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="control-label" for="psychiatryh_ref" style="padding-bottom: 5px;">Past Psychiatry History</label>
                                            <textarea id="psychiatryh_ref" name="psychiatryh_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <label class="control-label" for="pmh_ref" style="padding-bottom: 5px;">Previous Medical/Surgery History</label>
                                            <textarea id="pmh_ref" name="pmh_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="control-label" for="fmh_ref" style="padding-bottom: 5px;">Family History</label>
                                            <textarea id="fmh_ref" name="fmh_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <label class="control-label" for="personalh_ref" style="padding-bottom: 5px;">Personal History</label>
                                            <textarea id="personalh_ref" name="personalh_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="control-label" for="drugh_ref" style="padding-bottom: 5px;">Drug History</label>
                                            <textarea id="drugh_ref" name="drugh_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <label class="control-label" for="allergyh_ref" style="padding-bottom: 5px;">Allergy History</label>
                                            <textarea id="allergyh_ref" name="allergyh_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="control-label" for="socialh_ref" style="padding-bottom: 5px;">Premorbid History</label>
                                            <textarea id="socialh_ref" name="socialh_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <label class="control-label" for="genappear_ref" style="padding-bottom: 5px;">General Appearance & Behaviour</label>
                                            <textarea id="genappear_ref" name="genappear_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="control-label" for="speech_ref" style="padding-bottom: 5px;">Speech</label>
                                            <textarea id="speech_ref" name="speech_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <label class="control-label" for="moodaffect_ref" style="padding-bottom: 5px;">Mood & Affect</label>
                                            <textarea id="moodaffect_ref" name="moodaffect_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="control-label" for="perception_ref" style="padding-bottom: 5px;">Perception</label>
                                            <textarea id="perception_ref" name="perception_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <label class="control-label" for="thinking_ref" style="padding-bottom: 5px;">Thinking</label>
                                            <textarea id="thinking_ref" name="thinking_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="control-label" for="cognitivefunc_ref" style="padding-bottom: 5px;">Cognitive Function</label>
                                            <textarea id="cognitivefunc_ref" name="cognitivefunc_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12" style="margin-top: 10px;">
                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">FOLLOW UP</div>
                                            <div class="panel-body">
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="followuptime_ref" style="padding-bottom: 5px;">Time</label>
                                                    <input id="followuptime_ref" name="followuptime_ref" type="time" class="form-control input-sm">
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="followupdate_ref" style="padding-bottom: 5px;">Date</label>
                                                    <input id="followupdate_ref" name="followupdate_ref" type="date" class="form-control input-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="panel panel-info">
                                <div class="panel-heading text-center">Physical Examination</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <!-- <label class="col-md-3 control-label" for="examination_ref">Physical Examination</label> -->
                                        <div class="col-md-12">
                                            <!-- <textarea id="examination_ref" name="examination_ref" type="text" class="form-control input-sm"></textarea> -->
                                            <div class="card-group">
                                                <div class="col-md-3 card bodydia_doctornote_ref" data-type='BF_REF'>
                                                    <img class="card-img-top" src="{{ asset('img/bodydia1.png') }}" width="90" height="200">
                                                </div>
                                                <div class="col-md-3 card bodydia_doctornote_ref" data-type='BR_REF'>
                                                    <img class="card-img-top" src="{{ asset('img/bodydia2.png') }}" width="90" height="200">
                                                </div>
                                                <div class="col-md-3 card bodydia_doctornote_ref" data-type='BL_REF'>
                                                    <img class="card-img-top" src="{{ asset('img/bodydia3.png') }}" width="90" height="200">
                                                </div>
                                                <div class="col-md-3 card bodydia_doctornote_ref" data-type='BB_REF'>
                                                    <img class="card-img-top" src="{{ asset('img/bodydia4.png') }}" width="90" height="200">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-2 control-label" for="icdcode_ref" style="text-align: left;">Primary ICD</label>
                                <div class="col-md-7">
                                    <div class='input-group'>
                                        <input id="icdcode_ref" name="icdcode_ref" type="text" class="form-control input-sm">
                                        <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                    </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            
                            <div class="panel panel-info">
                                <div class="panel-heading text-center">Diagnosis</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <!-- <label class="col-md-3 control-label" for="diagfinal_ref">Diagnosis</label> -->
                                        <div class="col-md-12">
                                            <textarea id="diagfinal_ref" name="diagfinal_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="panel panel-info">
                                <div class="panel-heading text-center">Aetiology</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <!-- <label class="col-md-3 control-label" for="aetiology_ref">Aetiology</label> -->
                                        <div class="col-md-12">
                                            <textarea id="aetiology_ref" name="aetiology_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="panel panel-info">
                                <div class="panel-heading text-center">Investigations</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <!-- <label class="col-md-3 control-label" for="investigate_ref">Investigations</label> -->
                                        <div class="col-md-12">
                                            <textarea id="investigate_ref" name="investigate_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="panel panel-info">
                                <div class="panel-heading text-center">Treatment</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <!-- <label class="col-md-3 control-label" for="treatment_ref">Treatment</label> -->
                                        <div class="col-md-12">
                                            <textarea id="treatment_ref" name="treatment_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="panel panel-info">
                                <div class="panel-heading text-center">Prognosis</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <!-- <label class="col-md-3 control-label" for="prognosis_ref">Prognosis</label> -->
                                        <div class="col-md-12">
                                            <textarea id="prognosis_ref" name="prognosis_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="panel panel-info">
                                <div class="panel-heading text-center">Plan</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <!-- <label class="col-md-3 control-label" for="plan_ref">Plan</label> -->
                                        <div class="col-md-12">
                                            <textarea id="plan_ref" name="plan_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4" style="padding: 0 0 0 0;">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center">Vital Sign</div>
                                <div class="panel-body">
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="bp_ref" style="padding-bottom: 5px;">BP</label>
                                        <div class="input-group">
                                            <input id="bp_sys1_ref" name="bp_sys1_ref" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                            <input id="bp_dias2_ref" name="bp_dias2_ref" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                            <span class="input-group-addon">mmHg</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="spo2_ref" style="padding-bottom: 5px;">SPO2</label>
                                        <div class="input-group">
                                            <input id="spo2_ref" name="spo2_ref" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="pulse_ref" style="padding-bottom: 5px;">Pulse</label>
                                        <div class="input-group">
                                            <input id="pulse_ref" name="pulse_ref" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                            <span class="input-group-addon">Bpm</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="gxt_ref" style="padding-bottom: 5px;">Glucometer</label>
                                        <div class="input-group">
                                            <input id="gxt_ref" name="gxt_ref" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                            <span class="input-group-addon">mmol/L</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="temperature_ref" style="padding-bottom: 5px;">Temperature</label>
                                        <div class="input-group">
                                            <input id="temperature_ref" name="temperature_ref" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                            <span class="input-group-addon">°C</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="height_ref" style="padding-bottom: 5px;">Height</label>
                                        <div class="input-group">
                                            <input id="height_ref" name="height_ref" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                            <span class="input-group-addon">cm</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="weight_ref" style="padding-bottom: 5px;">Weight</label>
                                        <div class="input-group">
                                            <input id="weight_ref" name="weight_ref" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                            <span class="input-group-addon">kg</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="bmi_ref" style="padding-bottom: 5px;">BMI</label>
                                        <input id="bmi_ref" name="bmi_ref" type="number" class="form-control input-sm" rdonly>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="respiration_ref" style="padding-bottom: 5px;">RR</label>
                                        <div class="input-group">
                                            <input id="respiration_ref" name="respiration_ref" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                            <span class="input-group-addon">Min</span>
                                        </div>
                                    </div>
                                    
                                    <!-- <div class="form-group col-md-12">
                                        <label class="control-label" for="pain_score_ref" style="padding-bottom: 5px;">Pain Score</label>
                                        <div class="input-group">
                                            <input id="pain_score_ref" name="pain_score_ref" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                            <span class="input-group-addon">/10</span>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                            
                            <div class="panel panel-info" style="margin-top: 1238px;">
                                <div class="panel-body">
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="adduser_ref" style="padding-bottom: 5px;">Added by</label>  
                                        <input id="adduser_ref" name="adduser_ref" type="text" class="form-control input-sm" rdonly>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="adddate_ref" style="padding-bottom: 5px;">Date</label>
                                        <input id="adddate_ref" name="adddate_ref" type="text" class="form-control input-sm" rdonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="panel panel-info">
                            <div class="panel-heading text-center">MEDICATION</div>
                            <div class="panel-body" style="overflow: auto; padding: 0px;" id="jqGrid_trans_doctornote_ref_c">
                                <table id="jqGrid_trans_doctornote_ref" class="table table-striped"></table>
                                <div id="jqGrid_trans_doctornote_refPager"></div>
                                <!-- <table id="medication_tbl" class="ui selectable celled table" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Items</th>
                                            <th>Qty</th>
                                            <th>Remarks</th>
                                            <th>Dosage</th>
                                            <th>Frequency</th>
                                            <th>Instruction</th>
                                            <th>Indicator</th>
                                        </tr>
                                    </thead>
                                </table> -->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-body">
                <textarea id="letter" name="letter" rows="30" cols="90" class="form-control input-sm">
                
                Dear Dr. 
                
                Diagnosis: 
                
                Plan: 
                
                Prescription: 
                
                If I may be of any further assistance in the care of your patient, please let me know. Thank you for providing me the opportunity to participate in the care of your patients.
                
                Sincerely,
                
                Dr. 
                
                </textarea>
            </div>
        </div>
    </div> -->
</div>

<div id="dialog_medc" title="Medical Certificate">
    <div class="row">
        <div class="col-md-12" style="padding: 10px 0px;">
            <div class="btn-group" role="group" aria-label="..." style="float: right;">
                <button type="button" class="btn btn-default" id="btn_epno_mclt">MC List</button>
                <button type="button" class="btn btn-default" id="btn_epno_gomc">MC</button>
                <button type="button" class="btn btn-default" id="btn_epno_save">Save</button>
                <button type="button" class="btn btn-default" id="btn_epno_canl">Cancel</button>
            </div>
        </div>
        
        <div class="col-md-12" style="padding: 20px; 
            background: #e5f9ff; 
            border: 0.1em solid #d6d6ff; 
            border-radius: 4px;">
            <form id="form_medc" style="padding: 10px;" autocomplete="off" class="col-md-6 col-md-offset-3">
                <p style="padding-left: 50px;">Serial No: <input type="text" name="serialno" readonly class="form-control" style="width: 70px !important;"></p>
                <p>I hereby certify that i have examined</p>
                <p>Mr/Miss/Mrs : <input type="text" name="name" readonly class="form-control" style="width: 500px !important;"></p>
                <p style="padding-left: 38px;">From : <input type="text" name="patfrom" style="width: 500px !important;" class="form-control"></p>
                <p>And find that he/she will be unfit for duty for <input type="text" name="mccnt" class="form-control" style="width: 50px !important;" required> days</p>
                <p style="padding-left: 50px;">day from 
                    <input type="date" name="datefrom" class="form-control" required> to 
                    <input type="date" name="dateto" class="form-control" required>
                </p>
                <p>Boleh bertugas semula pada / Can resume his/her duty on 
                    <input type="date" name="dateresume" class="form-control" required>
                </p>
                <p>Dikehendaki datang semula pada /</p>
                <p>Is required to come for re-examination on <input type="date" name="datereexam" class="form-control"></p>
            </form>
            
            <div id="mclist_medc" style="display: none;">
                <table id="mclist_table">
                    <thead>
                        <tr>
                            <td>Id</td>
                            <td>Date from</td>
                            <td>Date to</td>
                            <td>MRN</td>
                            <td>Episode</td>
                            <td>Added By</td>
                            <td>Added Date</td>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="dialog_bpgraph" title="BP Graph">
    <div class="container_bpgraph">
        <div id="placeholder_bpgraph" class="placeholder_bpgraph" style="height: 60%;"></div>
        <div id="placeholder2_bpgraph" class="placeholder_bpgraph" style="height: 40%; margin-top: -34px;"></div>
    </div>
</div>
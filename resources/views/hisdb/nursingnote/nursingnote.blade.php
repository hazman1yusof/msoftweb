
<div class="panel panel-default" style="position: relative;" id="jqGridNursNote_c">
    <input type="hidden" name="curr_user" id="curr_user" value="{{ Auth::user()->username }}">
    
    <div class="panel-heading clearfix collapsed position" id="toggle_nursNote" style="position: sticky; top: 0px; z-index: 3;">
        <b>NAME: <span id="name_show_nursNote"></span></b><br>
        MRN: <span id="mrn_show_nursNote"></span>
        SEX: <span id="sex_show_nursNote"></span>
        DOB: <span id="dob_show_nursNote"></span>
        AGE: <span id="age_show_nursNote"></span>
        RACE: <span id="race_show_nursNote"></span>
        RELIGION: <span id="religion_show_nursNote"></span><br>
        OCCUPATION: <span id="occupation_show_nursNote"></span>
        CITIZENSHIP: <span id="citizenship_show_nursNote"></span>
        AREA: <span id="area_show_nursNote"></span>
        
        <i class="arrow fa fa-angle-double-up" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridNursNote_panel"></i>
        <i class="arrow fa fa-angle-double-down" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridNursNote_panel"></i>
        <div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 25px;">
            <h5>Nursing Note</h5>
        </div>
    </div>
    
    <div id="jqGridNursNote_panel" class="panel-collapse collapse" data-curtype='navtab_invChart'>
        <div class="panel-body paneldiv" style="overflow-y: auto;">
            <div class='col-md-12' style="padding: 0 0 15px 0;">
                <ul class="nav nav-tabs" id="jqGridNursNote_panel_tabs">
                    @if(request()->get('epistycode') == 'IP')
                        <li><a data-toggle="tab" id="navtab_invChart" href="#tab-invChart" data-type='invChart'>Investigation Chart</a></li>
                        <li><a data-toggle="tab" id="navtab_progress" href="#tab-progress" data-type='progress'>Progress Note</a></li>
                        <li><a data-toggle="tab" id="navtab_intake" href="#tab-intake" data-type='intake'>Intake Output</a></li>
                        <li><a data-toggle="tab" id="navtab_drug" href="#tab-drug" data-type='drug'>Drug Administration</a></li>
                        <li><a data-toggle="tab" id="navtab_treatment" href="#tab-treatment" data-type='treatment'>Nursing Report</a></li>
                        <li><a data-toggle="tab" id="navtab_careplan" href="#tab-careplan" data-type='careplan'>Care Plan</a></li>
                        <li><a data-toggle="tab" id="navtab_fitchart" href="#tab-fitchart" data-type='fitchart'>Fit Chart</a></li>
                        <li><a data-toggle="tab" id="navtab_circulation" href="#tab-circulation" data-type='circulation'>Circulation Chart</a></li>
                        <li><a data-toggle="tab" id="navtab_slidingScale" href="#tab-slidingScale" data-type='slidingScale'>Sliding Scale Chart</a></li>
                        <li><a data-toggle="tab" id="navtab_othersChart1" href="#tab-othersChart1" data-type='othersChart1'>PAD Chart</a></li>
                        <li><a data-toggle="tab" id="navtab_othersChart2" href="#tab-othersChart2" data-type='othersChart2'>Drain Chart</a></li>
                        <li><a data-toggle="tab" id="navtab_bladder" href="#tab-bladder" data-type='bladder'>Bladder Irrigation</a></li>
                        <li><a data-toggle="tab" id="navtab_gcs" href="#tab-gcs" data-type='gcs'>Glasgow Coma Scale</a></li>
                        <li><a data-toggle="tab" id="navtab_pivc" href="#tab-pivc" data-type='pivc'>PIVC</a></li>
                        <li><a data-toggle="tab" id="navtab_morsefallscale" href="#tab-morsefallscale" data-type='morsefallscale'>Daily Morse Fall Scale Assessment</a></li>
                        <li><a data-toggle="tab" id="navtab_thrombo" href="#tab-thrombo" data-type='thrombo'>Thrombophlebitis</a></li>

                        @endif
                    @if(request()->get('epistycode') == 'OP')
                        <li><a data-toggle="tab" id="navtab_progress" href="#tab-progress" data-type='progress'>Progress Note</a></li>
                    @endif
                </ul>
                <div class="tab-content" style="padding: 10px 5px;">
                    <input id="mrn_nursNote" name="mrn_nursNote" type="hidden">
                    <input id="episno_nursNote" name="episno_nursNote" type="hidden">
                    <input id="doctor_nursNote" name="doctor_nursNote" type="hidden">
                    <input id="ward_nursNote" name="ward_nursNote" type="hidden">
                    <input id="bednum_nursNote" name="bednum_nursNote" type="hidden">
                    <input id="age_nursNote" name="age_nursNote" type="hidden">
                    <input type="hidden" id="ordcomtt_phar" value="{{$ordcomtt_phar ?? ''}}">
                    
                    <div id="tab-invChart" class="tab-pane fade">
                        @include('hisdb.nursingnote.nursingnote_invChart')
                    </div>
                    <div id="tab-progress" class="active in tab-pane fade">
                        <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center" style="position: sticky; top: 0px; z-index: 3; height: 40px;">
                                    <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_progress" 
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 40px;
                                                top: 5px;">
                                        <button type="button" class="btn btn-default" id="new_progress">
                                            <span class="fa fa-plus-square-o"></span> New 
                                        </button>
                                        <button type="button" class="btn btn-default" id="edit_progress">
                                            <span class="fa fa-edit fa-lg"></span> Edit 
                                        </button>
                                        <button type="button" class="btn btn-default" data-oper='add' id="save_progress">
                                            <span class="fa fa-save fa-lg"></span> Save 
                                        </button>
                                        <button type="button" class="btn btn-default" id="cancel_progress">
                                            <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="panel-body">
                                    <form class='form-horizontal' style='width: 99%;' id='formProgress'>
                                        <input id="idno_progress" name="idno_progress" type="hidden">
                                        
                                        <div class="col-md-4" style="padding: 0 0 0 0;">
                                            <div class="panel panel-info">
                                                <div class="panel-body">
                                                    <table id="datetime_tbl" class="ui celled table" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="scope">idno</th>
                                                                <th class="scope">mrn</th>
                                                                <th class="scope">episno</th>
                                                                <th class="scope">Date</th>
                                                                <th class="scope">Time</th>
                                                                <th class="scope">Entered By</th>
                                                                <th class="scope">Location</th>
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
                                                        <label class="control-label" for="datetaken" style="padding-right: 5px;">Date</label>
                                                        <input id="datetaken" name="datetaken" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information." value="<?php echo date("Y-m-d"); ?>">
                                                        
                                                        <label class="control-label" for="timetaken" style="padding-left: 15px; padding-right: 5px;">Time</label>
                                                        <input id="timetaken" name="timetaken" type="time" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information.">
                                                        
                                                        <button class="btn btn-default btn-sm" type="button" id="doctornote_bpgraph" style="float: right; margin-right: 20px;">Chart</button>
                                                    </div>
                                                    
                                                    <div class='col-md-4' style="padding-right: 0px;">
                                                        <div class="panel panel-info">
                                                            <div class="panel-heading text-center">PATIENT STATUS</div>
                                                            <div class="panel-body" style="height: 670px; padding-right: 0px;">
                                                                <div class="form-group col-md-12">
                                                                    <label class="control-label" for="ncase_bp_stand" style="padding-bottom: 5px;">BP (standing)</label>
                                                                    <div class="input-group">
                                                                        <input id="bpsys_stand" name="bpsys_stand" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" style="width: 50%;">
                                                                        <input id="bpdias_stand" name="bpdias_stand" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" style="width: 50%;">
                                                                        <span class="input-group-addon">mmHg</span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group col-md-12">
                                                                    <label class="control-label" for="ncase_bp_lieDown" style="padding-bottom: 5px;">BP (lying down)</label>
                                                                    <div class="input-group">
                                                                        <input id="bpsys_lieDown" name="bpsys_lieDown" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" style="width: 50%;">
                                                                        <input id="bpdias_lieDown" name="bpdias_lieDown" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;" style="width: 50%;">
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
                                                                    <label class="control-label" for="hr" style="padding-bottom: 5px;">HR</label>
                                                                    <div class="input-group">
                                                                        <input id="hr" name="hr" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
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
                                                                    <label class="control-label" for="temp_" style="padding-bottom: 5px;">Temperature</label>
                                                                    <div class="input-group">
                                                                        <input id="temp_" name="temp_" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                                        <span class="input-group-addon">°C</span>
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
                                                                    <label class="control-label" for="respiration" style="padding-bottom: 5px;">RR</label>
                                                                    <div class="input-group">
                                                                        <input id="respiration" name="respiration" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                                        <span class="input-group-addon">Min</span>
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
                                                                    <label class="control-label" for="painscore" style="padding-bottom: 5px;">Pain Score</label>
                                                                    <div class="input-group">
                                                                        <input id="painscore" name="painscore" type="number" class="form-control input-sm" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                                        <span class="input-group-addon">/10</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class='col-md-8'>
                                                        <div class="panel panel-info">
                                                            <div class="panel-heading text-center">ASSESSMENT</div>
                                                            <div class="panel-body" style="height: 670px;">
                                                                <div class='col-md-6' style="padding: 0px 0px;">
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label" for="airway">Airway</label>  
                                                                        <div class="col-md-8" style="padding-top: 6px;">
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="roomair" value="1" style="margin-right: 18px;">Room Air
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="oxygen" value="1" style="margin-right: 18px;">Oxygen
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">Others</label>
                                                                                <div class="form-check col-md-12" style="padding-left: 0px;">
                                                                                    <textarea id="airwayfreetext" name="airwayfreetext" type="text" class="form-control input-sm"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label" for="breathing">Breathing</label>  
                                                                        <div class="col-md-8" style="padding-top: 6px;">
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="breathnormal" value="1" style="margin-right: 18px;">Normal
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="breathdifficult" value="1" style="margin-right: 18px;">Difficult
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label" for="circulation">Circulation</label>  
                                                                        <div class="col-md-8" style="padding-top: 6px;">
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="circarrythmias" value="1" style="margin-right: 18px;">Arrhythmias
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="circlbp" value="1" style="margin-right: 18px;">Low BP
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="circhbp" value="1" style="margin-right: 18px;">High BP
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="circirregular" value="1" style="margin-right: 18px;">Irregular HR
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label" for="fallrisk">Disability Fall Risk</label>  
                                                                        <div class="col-md-8" style="padding-top: 6px;">
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="frhigh" value="1" style="margin-right: 18px;">High
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="frlow" value="1" style="margin-right: 18px;">Low
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">Others</label>
                                                                                <div class="form-check col-md-12" style="padding-left: 0px;">
                                                                                    <textarea id="frfreetext" name="frfreetext" type="text" class="form-control input-sm"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class='col-md-6' style="padding: 0px 0px;">
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label" for="exposuredrain">Exposure Drain</label>  
                                                                        <div class="col-md-8" style="padding-top: 6px;">
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="drainnone" value="1" style="margin-right: 18px;">None
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="draindrainage" value="1" style="margin-right: 18px;">Drainage
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">Others</label>
                                                                                <div class="form-check col-md-12" style="padding-left: 0px;">
                                                                                    <textarea id="drainfreetext" name="drainfreetext" type="text" class="form-control input-sm"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label" for="ivline">IV Line</label>  
                                                                        <div class="col-md-8" style="padding-top: 6px;">
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="ivlnone" value="1" style="margin-right: 18px;">None
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="ivlsite" value="1" style="margin-right: 18px;">Site
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">Others</label>
                                                                                <div class="form-check col-md-12" style="padding-left: 0px;">
                                                                                    <textarea id="ivfreetext" name="ivfreetext" type="text" class="form-control input-sm"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label" for="gu">GU</label>  
                                                                        <div class="col-md-8" style="padding-top: 6px;">
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="gucontinent" value="1" style="margin-right: 18px;">Continent
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="gufoley" value="1" style="margin-right: 18px;">Foley
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">Others</label>
                                                                                <div class="form-check col-md-12" style="padding-left: 0px;">
                                                                                    <textarea id="assesothers" name="assesothers" type="text" class="form-control input-sm"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class='col-md-12'>
                                                        <div class="panel panel-info">
                                                            <div class="panel-heading text-center">PLAN AND PROGRESS NOTE</div>
                                                            <div class="panel-body">
                                                                <textarea id="plannotes" name="plannotes" type="text" class="form-control input-sm"></textarea>
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
                    <div id="tab-intake" class="tab-pane fade">
                        @include('hisdb.nursingnote.nursingnote_intake')
                    </div>
                    <div id="tab-drug" class="tab-pane fade">
                        <form class='form-horizontal' style='width: 99%;' id='formDrug'>
                            <div class='col-md-4' style="padding-left: 0px; padding-right: 2px;">
                                <div class="panel panel-info">
                                    <div class="panel-heading text-center">PRESCRIPTION</div>
                                    <div class="panel-body">
                                        <table id="tbl_prescription" class="ui celled table" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th class="scope">auditno</th>
                                                    <th class="scope">mrn</th>
                                                    <th class="scope">episno</th>
                                                    <th class="scope">Charge Code</th>
                                                    <th class="scope">Item</th>
                                                    <th class="scope">Quantity</th>
                                                    <th class="scope">doscode</th>
                                                    <th class="scope">doscode_desc</th>
                                                    <th class="scope">frequency</th>
                                                    <th class="scope">frequency_desc</th>
                                                    <th class="scope">ftxtdosage</th>
                                                    <th class="scope">addinstruction</th>
                                                    <th class="scope">addinstruction_desc</th>
                                                    <th class="scope">drugindicator</th>
                                                    <th class="scope">drugindicator_desc</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        
                                        <button class="btn btn-default btn-sm" type="button" id="tbl_prescription_refresh" style="float: right;">
                                            <span class="icon glyphicon glyphicon-refresh"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class='col-md-8' style="padding-left: 0px; padding-right: 2px;">
                                <div class="panel panel-info">
                                    <div class="panel-body">
                                        <input id="trx_auditno" name="trx_auditno" type="hidden">
                                        <input id="trx_chgcode" name="trx_chgcode" type="hidden">
                                        <input id="trx_quantity" name="trx_quantity" type="hidden">
                                        
                                        <div class="form-group col-md-8">
                                            <input id="doc_name" name="doc_name" type="text" class="form-control input-sm" rdonly>
                                        </div>
                                        
                                        <!-- <div class="form-group col-md-8">
                                            <textarea id="ftxtdosage" name="ftxtdosage" type="text" class="form-control input-sm" rdonly></textarea>
                                        </div> -->
                                        
                                        <div class="form-group col-md-9">
                                            <label class="oe_phar_label">Dose</label>
                                            <div class="input-group oe_phar_div">
                                                <input autocomplete="off" name="dosage" id="dosage_nursNote" type="text" class="form-control input-sm" style="text-transform: uppercase;" rdonly>
                                                <a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a>
                                            </div>
                                            <input type="hidden" id="dosage_nursNote_code">
                                        </div>
                                        
                                        <div class="form-group col-md-9">
                                            <label class="oe_phar_label">Frequency</label>
                                            <div class="input-group oe_phar_div">
                                                <input autocomplete="off" name="frequency" id="frequency_nursNote" type="text" class="form-control input-sm" style="text-transform: uppercase;" rdonly>
                                                <a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a>
                                            </div>
                                            <input type="hidden" id="frequency_nursNote_code">
                                        </div>
                                        
                                        <div class="form-group col-md-9">
                                            <label class="oe_phar_label">Instruction</label>
                                            <div class="input-group oe_phar_div">
                                                <input autocomplete="off" name="instruction" id="instruction_nursNote" type="text" class="form-control input-sm" style="text-transform: uppercase;" rdonly>
                                                <a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a>
                                            </div>
                                            <input type="hidden" id="instruction_nursNote_code">
                                        </div>
                                        
                                        <div class="form-group col-md-9">
                                            <label class="oe_phar_label">Indicator</label>
                                            <div class="input-group oe_phar_div">
                                                <input autocomplete="off" name="drugindicator" id="drugindicator_nursNote" type="text" class="form-control input-sm" style="text-transform: uppercase;" rdonly>
                                                <a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a>
                                            </div>
                                            <input type="hidden" id="drugindicator_nursNote_code">
                                        </div>
                                        
                                        <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                                            <div class="panel panel-info" id="jqGridPatMedic_c">
                                                <div class="panel-body">
                                                    <div style="float: right; padding-right: 60px; padding-bottom: 15px;">
                                                        <label class="col-md-5 control-label">Total Quantity</label>
                                                        <div class="col-md-5">
                                                            <input id="tot_qty" name="tot_qty" type="text" class="form-control input-sm" rdonly>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                        <table id="jqGridPatMedic" class="table table-striped"></table>
                                                        <div id="jqGridPagerPatMedic"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="tab-treatment" class="tab-pane fade">
                        <div class='col-md-4' style="padding-left: 0px; padding-right: 3px;">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center" style="height: 60px;">
                                    <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 45px; top: 5px;">
                                        <h6>TREATMENT &</h6>
                                        <h6>PROCEDURE</h6>
                                    </div>
                                    <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_treatment"
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 10px;
                                                top: 15px;">
                                        <button type="button" class="btn btn-default" id="new_treatment">
                                            <span class="fa fa-plus-square-o"></span> New 
                                        </button>
                                        <button type="button" class="btn btn-default" id="edit_treatment">
                                            <span class="fa fa-edit fa-lg"></span> Edit 
                                        </button>
                                        <button type="button" class="btn btn-default" data-oper='add' id="save_treatment">
                                            <span class="fa fa-save fa-lg"></span> Save 
                                        </button>
                                        <button type="button" class="btn btn-default" id="cancel_treatment">
                                            <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="panel-body" style="padding: 15px 5px;">
                                    <form class='form-horizontal' style='width: 99%;' id='formTreatmentP'>
                                        <input id="tr_idno" name="tr_idno" type="hidden">
                                        <input id="tr_adduser" name="tr_adduser" type="hidden">
                                        
                                        <div class="col-md-3" style="padding: 0 0 0 0;">
                                            <div class="panel panel-info">
                                                <div class="panel-body" style="padding: 0 0 0 0;">
                                                    <table id="tbl_treatment" class="ui celled table" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="scope">idno</th>
                                                                <th class="scope">mrn</th>
                                                                <th class="scope">episno</th>
                                                                <th class="scope">Date/Time</th>
                                                                <th class="scope">adduser</th>
                                                                <th class="scope">dt</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='col-md-9' style="padding-left: 5px; padding-right: 0px;">
                                            <div class="panel panel-info">
                                                <!-- <div class="panel-heading text-center">NOTES</div> -->
                                                <div class="panel-body" style="padding: 2px;">
                                                    <div class="form-inline col-md-12" style="padding: 10px 15px 10px 0px;">
                                                        <label class="control-label" for="tr_entereddate">Date</label>
                                                        <input id="tr_entereddate" name="tr_entereddate" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information." value="<?php echo date("Y-m-d"); ?>" required>
                                                    </div>
                                                    
                                                    <div class="form-inline col-md-12" style="padding: 0px 15px 10px 0px;">
                                                        <label class="control-label" for="tr_enteredtime">Time</label>
                                                        <input id="tr_enteredtime" name="tr_enteredtime" type="time" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information." required>
                                                    </div>
                                                    
                                                    <div class="form-group" style="padding-left: 15px; padding-right: 15px;">
														<label for="treatment_remarks">Notes</label>
														<textarea id="treatment_remarks" name="treatment_remarks" type="text" class="form-control input-sm"></textarea>
													</div>
                                                    
                                                    <div class="form-group" style="padding-left: 15px; padding-right: 15px;">
                                                        <label for="treatment_adduser">Entered by</label>
                                                        <input id="treatment_adduser" name="treatment_adduser" type="text" class="form-control input-sm" rdonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-4' style="padding-left: 3px; padding-right: 3px;">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center" style="height: 60px;">
                                    <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 45px; top: 15px;">
                                        <h6>INVESTIGATION</h6>
                                    </div>
                                    <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_investigation"
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 10px;
                                                top: 15px;">
                                        <button type="button" class="btn btn-default" id="new_investigation">
                                            <span class="fa fa-plus-square-o"></span> New 
                                        </button>
                                        <button type="button" class="btn btn-default" id="edit_investigation">
                                            <span class="fa fa-edit fa-lg"></span> Edit 
                                        </button>
                                        <button type="button" class="btn btn-default" data-oper='add' id="save_investigation">
                                            <span class="fa fa-save fa-lg"></span> Save 
                                        </button>
                                        <button type="button" class="btn btn-default" id="cancel_investigation">
                                            <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="panel-body" style="padding: 15px 5px;">
                                    <form class='form-horizontal' style='width: 99%;' id='formInvestigation'>
                                        <input id="inv_idno" name="inv_idno" type="hidden">
                                        <input id="inv_adduser" name="inv_adduser" type="hidden">
                                        
                                        <div class="col-md-3" style="padding: 0 0 0 0;">
                                            <div class="panel panel-info">
                                                <div class="panel-body" style="padding: 0 0 0 0;">
                                                    <table id="tbl_investigation" class="ui celled table" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="scope">idno</th>
                                                                <th class="scope">mrn</th>
                                                                <th class="scope">episno</th>
                                                                <th class="scope">Date/Time</th>
                                                                <th class="scope">adduser</th>
                                                                <th class="scope">dt</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='col-md-9' style="padding-left: 5px; padding-right: 0px;">
                                            <div class="panel panel-info">
                                                <!-- <div class="panel-heading text-center">NOTES</div> -->
                                                <div class="panel-body" style="padding: 2px;">
                                                    <div class="form-inline col-md-12" style="padding: 10px 15px 10px 0px;">
                                                        <label class="control-label" for="inv_entereddate">Date</label>
                                                        <input id="inv_entereddate" name="inv_entereddate" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information." value="<?php echo date("Y-m-d"); ?>">
                                                    </div>
                                                    
                                                    <div class="form-inline col-md-12" style="padding: 0px 15px 10px 0px;">
                                                        <label class="control-label" for="inv_enteredtime">Time</label>
                                                        <input id="inv_enteredtime" name="inv_enteredtime" type="time" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information.">
                                                    </div>
                                                    
                                                    <div class="form-group" style="padding-left: 15px; padding-right: 15px;">
														<label for="investigation_remarks">Notes</label>
														<textarea id="investigation_remarks" name="investigation_remarks" type="text" class="form-control input-sm"></textarea>
													</div>
                                                    
                                                    <div class="form-group" style="padding-left: 15px; padding-right: 15px;">
                                                        <label for="investigation_adduser">Entered by</label>
                                                        <input id="investigation_adduser" name="investigation_adduser" type="text" class="form-control input-sm" rdonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-4' style="padding-left: 3px; padding-right: 0px;">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center" style="height: 60px;">
                                    <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 45px; top: 15px;">
                                        <h6>INJECTION</h6>
                                    </div>
                                    <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_injection"
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 10px;
                                                top: 15px;">
                                        <button type="button" class="btn btn-default" id="new_injection">
                                            <span class="fa fa-plus-square-o"></span> New 
                                        </button>
                                        <button type="button" class="btn btn-default" id="edit_injection">
                                            <span class="fa fa-edit fa-lg"></span> Edit 
                                        </button>
                                        <button type="button" class="btn btn-default" data-oper='add' id="save_injection">
                                            <span class="fa fa-save fa-lg"></span> Save 
                                        </button>
                                        <button type="button" class="btn btn-default" id="cancel_injection">
                                            <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="panel-body" style="padding: 15px 5px;">
                                    <form class='form-horizontal' style='width: 99%;' id='formInjection'>
                                        <input id="inj_idno" name="inj_idno" type="hidden">
                                        <input id="inj_adduser" name="inj_adduser" type="hidden">

                                        <div class="col-md-3" style="padding: 0 0 0 0;">
                                            <div class="panel panel-info">
                                                <div class="panel-body" style="padding: 0 0 0 0;">
                                                    <table id="tbl_injection" class="ui celled table" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="scope">idno</th>
                                                                <th class="scope">mrn</th>
                                                                <th class="scope">episno</th>
                                                                <th class="scope">Date/Time</th>
                                                                <th class="scope">adduser</th>
                                                                <th class="scope">dt</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='col-md-9' style="padding-left: 5px; padding-right: 0px;">
                                            <div class="panel panel-info">
                                                <!-- <div class="panel-heading text-center">NOTES</div> -->
                                                <div class="panel-body" style="padding: 2px;">
                                                    <div class="form-inline col-md-12" style="padding: 10px 15px 10px 0px;">
                                                        <label class="control-label" for="inj_entereddate">Date</label>
                                                        <input id="inj_entereddate" name="inj_entereddate" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information." value="<?php echo date("Y-m-d"); ?>">
                                                    </div>
                                                    
                                                    <div class="form-inline col-md-12" style="padding: 0px 15px 10px 0px;">
                                                        <label class="control-label" for="inj_enteredtime">Time</label>
                                                        <input id="inj_enteredtime" name="inj_enteredtime" type="time" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information.">
                                                    </div>
                                                    
                                                    <div class="form-group" style="padding-left: 15px; padding-right: 15px;">
														<label for="injection_remarks">Notes</label>
														<textarea id="injection_remarks" name="injection_remarks" type="text" class="form-control input-sm"></textarea>
													</div>
                                                    
                                                    <div class="form-group" style="padding-left: 15px; padding-right: 15px;">
                                                        <label for="injection_adduser">Entered by</label>
                                                        <input id="injection_adduser" name="injection_adduser" type="text" class="form-control input-sm" rdonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-careplan" class="tab-pane fade">
                        <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center" style="position: sticky; top: 0px; z-index: 3; height: 40px;">
                                    <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_careplan"
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 40px;
                                                top: 5px;">
                                        <button type="button" class="btn btn-default" id="new_careplan">
                                            <span class="fa fa-plus-square-o"></span> New 
                                        </button>
                                        <button type="button" class="btn btn-default" data-oper='add' id="save_careplan">
                                            <span class="fa fa-save fa-lg"></span> Save 
                                        </button>
                                        <button type="button" class="btn btn-default" id="cancel_careplan">
                                            <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="panel-body" style="padding-right: 0px;">
                                    <form class='form-horizontal' style='width: 99%;' id='formCarePlan'>
                                        <div class="col-md-2" style="padding: 0 0 0 0;">
                                            <div class="panel panel-info">
                                                <div class="panel-body">
                                                    <table id="tbl_careplan_date" class="ui celled table" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="scope">idno</th>
                                                                <th class="scope">mrn</th>
                                                                <th class="scope">episno</th>
                                                                <th class="scope">Date</th>
                                                                <th class="scope">Time</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='col-md-10' style="padding-right: 0px;">
                                            <div class="panel panel-info">
                                                <div class="panel-body" style="padding-left: 0px; padding-right: 0px;">
                                                    <div class='col-md-4' style="padding-left: 0px; padding-right: 0px;">
                                                        <div class='col-md-12'>
                                                            <div class="panel panel-info">
                                                                <div class="panel-heading text-center">PROBLEM</div>
                                                                <div class="panel-body">
                                                                    <textarea id="problem" name="problem" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class='col-md-12'>
                                                            <div class="panel panel-info">
                                                                <div class="panel-heading text-center">DATA</div>
                                                                <div class="panel-body">
                                                                    <textarea id="problemdata" name="problemdata" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class='col-md-12'>
                                                            <div class="panel panel-info">
                                                                <div class="panel-heading text-center">INTEND INCOME</div>
                                                                <div class="panel-body">
                                                                    <textarea id="problemintincome" name="problemintincome" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class='col-md-4' style="padding-left: 0px;">
                                                        <div class="panel panel-info">
                                                            <div class="panel-heading text-center">INTERVENTION</div>
                                                            <div class="panel-body">
                                                                <textarea id="nursintervention" name="nursintervention" type="text" class="form-control input-sm"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class='col-md-4' style="padding-left: 0px;">
                                                        <div class="panel panel-info">
                                                            <div class="panel-heading text-center">EVALUATION</div>
                                                            <div class="panel-body">
                                                                <textarea id="nursevaluation" name="nursevaluation" type="text" class="form-control input-sm"></textarea>
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
                    <div id="tab-fitchart" class="tab-pane fade">
                        <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center" style="height: 40px;">
                                    <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_fitchart"
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 40px;
                                                top: 5px;">
                                        <!-- <button type="button" class="btn btn-default" id="new_fitchart">
                                            <span class="fa fa-plus-square-o"></span> New 
                                        </button>
                                        <button type="button" class="btn btn-default" id="edit_fitchart">
                                            <span class="fa fa-edit fa-lg"></span> Edit 
                                        </button>
                                        <button type="button" class="btn btn-default" data-oper='add' id="save_fitchart">
                                            <span class="fa fa-save fa-lg"></span> Save 
                                        </button>
                                        <button type="button" class="btn btn-default" id="cancel_fitchart">
                                            <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                        </button> -->
                                        <button type="button" class="btn btn-default" id="fitchart_chart">
                                            <span class="fa fa-print fa-lg"></span> Chart 
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- <button class="btn btn-default btn-sm" type="button" id="fitchart_chart" style="float: right; margin: 10px 40px 10px 0px;">Chart</button> -->
                                
                                <div class="panel-body" style="padding-right: 0px;">
                                    <form class='form-horizontal' style='width: 99%;' id='formFitChart'>
                                        <div class='col-md-12'>
                                            <div class="panel panel-info">
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <label class="col-md-1 control-label" for="fitchart_ward">Ward</label>
                                                        <div class="col-md-2">
                                                            <input id="fitchart_ward" name="fitchart_ward" type="text" class="form-control input-sm" readonly>
                                                        </div>
                                                        
                                                        <label class="col-md-1 control-label" for="fitchart_bednum">Bed No.</label>
                                                        <div class="col-md-2">
                                                            <input id="fitchart_bednum" name="fitchart_bednum" type="text" class="form-control input-sm" readonly>
                                                        </div>
                                                        
                                                        <label class="col-md-1 control-label" for="fitchart_diag">Diagnosis</label>
                                                        <div class="col-md-4">
                                                            <textarea id="fitchart_diag" name="fitchart_diag" type="text" class="form-control input-sm" readonly></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='col-md-12'>
                                            <div class="panel panel-info" id="jqGridFitChart_c">
                                                <!-- <div class="panel-heading text-center">FIT CHART</div> -->
                                                <div class="panel-body">
                                                    <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                        <table id="jqGridFitChart" class="table table-striped"></table>
                                                        <div id="jqGridPagerFitChart"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-circulation" class="tab-pane fade">
                        <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center" style="height: 40px;">
                                    <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_circulation"
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 40px;
                                                top: 5px;">
                                        <!-- <button type="button" class="btn btn-default" id="new_circulation">
                                            <span class="fa fa-plus-square-o"></span> New 
                                        </button>
                                        <button type="button" class="btn btn-default" data-oper='add' id="save_circulation">
                                            <span class="fa fa-save fa-lg"></span> Save 
                                        </button>
                                        <button type="button" class="btn btn-default" id="cancel_circulation">
                                            <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                        </button> -->
                                        <button type="button" class="btn btn-default" id="circulation_chart">
                                            <span class="fa fa-print fa-lg"></span> Chart 
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- <button class="btn btn-default btn-sm" type="button" id="circulation_chart" style="float: right; margin: 10px 40px 10px 0px;">Chart</button> -->
                                
                                <div class="panel-body" style="padding-right: 0px;">
                                    <form class='form-horizontal' style='width: 99%;' id='formCirculation'>
                                        <div class='col-md-12'>
                                            <div class="panel panel-info">
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <label class="col-md-1 control-label" for="circulation_diag">Diagnosis</label>
                                                        <div class="col-md-4">
                                                            <textarea id="circulation_diag" name="circulation_diag" type="text" class="form-control input-sm" readonly></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='col-md-12'>
                                            <div class="panel panel-info" id="jqGridCirculation_c">
                                                <!-- <div class="panel-heading text-center">CIRCULATION CHART</div> -->
                                                <div class="panel-body">
                                                    <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                        <table id="jqGridCirculation" class="table table-striped"></table>
                                                        <div id="jqGridPagerCirculation"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-slidingScale" class="tab-pane fade">
                        <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center" style="height: 40px;">
                                    <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_slidingScale"
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 40px;
                                                top: 5px;">
                                        <!-- <button type="button" class="btn btn-default" id="new_slidingScale">
                                            <span class="fa fa-plus-square-o"></span> New 
                                        </button>
                                        <button type="button" class="btn btn-default" data-oper='add' id="save_slidingScale">
                                            <span class="fa fa-save fa-lg"></span> Save 
                                        </button>
                                        <button type="button" class="btn btn-default" id="cancel_slidingScale">
                                            <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                        </button> -->
                                        <button type="button" class="btn btn-default" id="slidingScale_chart">
                                            <span class="fa fa-print fa-lg"></span> Chart 
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- <button class="btn btn-default btn-sm" type="button" id="slidingScale_chart" style="float: right; margin: 10px 40px 10px 0px;">Chart</button> -->
                                
                                <div class="panel-body" style="padding-right: 0px;">
                                    <form class='form-horizontal' style='width: 99%;' id='formSlidingScale'>
                                        <div class='col-md-12'>
                                            <div class="panel panel-info" id="jqGridSlidingScale_c">
                                                <!-- <div class="panel-heading text-center">SLIDING SCALE CHART</div> -->
                                                <div class="panel-body">
                                                    <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                        <table id="jqGridSlidingScale" class="table table-striped"></table>
                                                        <div id="jqGridPagerSlidingScale"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-othersChart1" class="tab-pane fade">
                        <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center" style="height: 40px;">
                                    <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_othersChart1"
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 40px;
                                                top: 5px;">
                                        <!-- <button type="button" class="btn btn-default" id="new_othersChart1">
                                            <span class="fa fa-plus-square-o"></span> New 
                                        </button>
                                        <button type="button" class="btn btn-default" id="edit_othersChart1">
                                            <span class="fa fa-edit fa-lg"></span> Edit 
                                        </button>
                                        <button type="button" class="btn btn-default" data-oper='add' id="save_othersChart1">
                                            <span class="fa fa-save fa-lg"></span> Save 
                                        </button>
                                        <button type="button" class="btn btn-default" id="cancel_othersChart1">
                                            <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                        </button> -->
                                        <button type="button" class="btn btn-default" id="othersChart1_chart">
                                            <span class="fa fa-print fa-lg"></span> Chart 
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- <button class="btn btn-default btn-sm" type="button" id="othersChart1_chart" style="float: right; margin: 10px 40px 10px 0px;">Chart</button> -->
                                
                                <div class="panel-body" style="padding-right: 0px;">
                                    <form class='form-horizontal' style='width: 99%;' id='formOthersChart1'>
                                        <div class='col-md-12'>
                                            <div class="panel panel-info">
                                                <div class="panel-body">
                                                    <div class="form-group" style="padding-bottom: 5px;">
                                                        <input id="othersChart1_tabtitle" name="othersChart1_tabtitle" value="PADChart" type="hidden">
                                                        
                                                        <div class="col-md-4"></div>
                                                        
                                                        <!-- <div class="col-md-2" style="padding-left: 0px; padding-right: 0px;">
                                                            <input id="othersChart1_title" name="othersChart1_title" type="text" class="form-control input-sm" style="text-transform: none;">
                                                        </div>
                                                        <label class="col-md-1 control-label" for="othersChart1_title">Chart</label> -->
                                                        
                                                        <div class="col-md-4"></div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="col-md-1 control-label" for="othersChart1_ward">Ward</label>
                                                        <div class="col-md-2">
                                                            <input id="othersChart1_ward" name="othersChart1_ward" type="text" class="form-control input-sm" rdonly>
                                                        </div>
                                                        
                                                        <label class="col-md-1 control-label" for="othersChart1_bednum">Bed No.</label>
                                                        <div class="col-md-2">
                                                            <input id="othersChart1_bednum" name="othersChart1_bednum" type="text" class="form-control input-sm" rdonly>
                                                        </div>
                                                        
                                                        <label class="col-md-1 control-label" for="othersChart1_diag">Diagnosis</label>
                                                        <div class="col-md-4">
                                                            <textarea id="othersChart1_diag" name="othersChart1_diag" type="text" class="form-control input-sm" rdonly></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                            <div class="panel panel-info" id="jqGridOthersChart1_c">
                                <!-- <div class="panel-heading text-center">OTHERS CHART 1</div> -->
                                <div class="panel-body">
                                    <div class='col-md-12' style="padding: 0 0 15px 0;">
                                        <table id="jqGridOthersChart1" class="table table-striped"></table>
                                        <div id="jqGridPagerOthersChart1"></div>
                                    </div>
                                    
                                    <div class="col-md-5" style="padding-top: 20px; text-align: left; color: red;">
                                        <p id="p_error"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-othersChart2" class="tab-pane fade">
                        <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center" style="height: 40px;">
                                    <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_othersChart2"
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 40px;
                                                top: 5px;">
                                        <!-- <button type="button" class="btn btn-default" id="new_othersChart2">
                                            <span class="fa fa-plus-square-o"></span> New 
                                        </button>
                                        <button type="button" class="btn btn-default" id="edit_othersChart2">
                                            <span class="fa fa-edit fa-lg"></span> Edit 
                                        </button>
                                        <button type="button" class="btn btn-default" data-oper='add' id="save_othersChart2">
                                            <span class="fa fa-save fa-lg"></span> Save 
                                        </button>
                                        <button type="button" class="btn btn-default" id="cancel_othersChart2">
                                            <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                        </button> -->
                                        <button type="button" class="btn btn-default" id="othersChart2_chart">
                                            <span class="fa fa-print fa-lg"></span> Chart 
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- <button class="btn btn-default btn-sm" type="button" id="othersChart2_chart" style="float: right; margin: 10px 40px 10px 0px;">Chart</button> -->
                                
                                <div class="panel-body" style="padding-right: 0px;">
                                    <form class='form-horizontal' style='width: 99%;' id='formOthersChart2'>
                                        <div class='col-md-12'>
                                            <div class="panel panel-info">
                                                <div class="panel-body">
                                                    <div class="form-group" style="padding-bottom: 5px;">
                                                        <input id="othersChart2_tabtitle" name="othersChart2_tabtitle" value="DrainChart" type="hidden">
                                                        
                                                        <div class="col-md-4"></div>
                                                        
                                                        <!-- <div class="col-md-2" style="padding-left: 0px; padding-right: 0px;">
                                                            <input id="othersChart2_title" name="othersChart2_title" type="text" class="form-control input-sm" style="text-transform: none;">
                                                        </div>
                                                        <label class="col-md-1 control-label" for="othersChart2_title">Chart</label> -->
                                                        
                                                        <div class="col-md-4"></div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="col-md-1 control-label" for="othersChart2_ward">Ward</label>
                                                        <div class="col-md-2">
                                                            <input id="othersChart2_ward" name="othersChart2_ward" type="text" class="form-control input-sm" rdonly>
                                                        </div>
                                                        
                                                        <label class="col-md-1 control-label" for="othersChart2_bednum">Bed No.</label>
                                                        <div class="col-md-2">
                                                            <input id="othersChart2_bednum" name="othersChart2_bednum" type="text" class="form-control input-sm" rdonly>
                                                        </div>
                                                        
                                                        <label class="col-md-1 control-label" for="othersChart2_diag">Diagnosis</label>
                                                        <div class="col-md-4">
                                                            <textarea id="othersChart2_diag" name="othersChart2_diag" type="text" class="form-control input-sm" rdonly></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                            <div class="panel panel-info" id="jqGridOthersChart2_c">
                                <!-- <div class="panel-heading text-center">OTHERS CHART 2</div> -->
                                <div class="panel-body">
                                    <div class='col-md-12' style="padding: 0 0 15px 0;">
                                        <table id="jqGridOthersChart2" class="table table-striped"></table>
                                        <div id="jqGridPagerOthersChart2"></div>
                                    </div>
                                    
                                    <div class="col-md-5" style="padding-top: 20px; text-align: left; color: red;">
                                        <p id="p_error2"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-bladder" class="tab-pane fade">
                        <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center" style="height: 40px;">
                                    <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_bladder"
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 40px;
                                                top: 5px;">
                                        <button type="button" class="btn btn-default" id="bladder_chart">
                                            <span class="fa fa-print fa-lg"></span> Chart 
                                        </button>
                                    </div>
                                </div>

                                <div class="panel-body" style="padding-right: 0px;">
                                    <form class='form-horizontal' style='width: 99%;' id='formBladderHdr'>
                                        <div class='col-md-12'>
                                            <div class="panel panel-info">
                                                <div class="panel-body">
                                                    
                                                    <div class="form-group">
                                                        <label class="col-md-2 control-label" for="bladder_ward">Ward</label>
                                                        <div class="col-md-3">
                                                            <input id="bladder_ward" name="bladder_ward" type="text" class="form-control input-sm" readonly>
                                                        </div>
                                                        
                                                        <label class="col-md-2 control-label" for="bladder_bednum">Bed No.</label>
                                                        <div class="col-md-3">
                                                            <input id="bladder_bednum" name="bladder_bednum" type="text" class="form-control input-sm" readonly>
                                                        </div>
                                                    </div>

                                                    <!-- <div class="form-group">
                                                        <label class="col-md-1 control-label" for="tot_input">Total Input</label>
                                                        <div class="col-md-2">
                                                            <input id="tot_input" name="tot_input" type="text" class="form-control input-sm" readonly>
                                                        </div>
                                                        
                                                        <label class="col-md-1 control-label" for="tot_output">Total Output</label>
                                                        <div class="col-md-2">
                                                            <input id="tot_output" name="tot_output" type="text" class="form-control input-sm" readonly>
                                                        </div>

                                                        <label class="col-md-1 control-label" for="balance">Balance</label>
                                                        <div class="col-md-2">
                                                            <input id="balance" name="balance" type="text" class="form-control input-sm" readonly>
                                                        </div>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                
                                <div class="panel-body">
                                    <form class='form-horizontal' style='width: 99%;' id='formBladder'>
                                        <ul class="nav nav-tabs" id="jqGridNursNote_bladder_tabs">
                                            <li class="active"><a data-toggle="tab" id="navtab_firstShift" href="#tab-firstShift" aria-expanded="true" data-type='firstShift'>7AM - 2PM</a></li>
                                            <li><a data-toggle="tab" id="navtab_secondShift" href="#tab-secondShift" data-type='secondShift'>2PM - 9PM</a></li>
                                            <li><a data-toggle="tab" id="navtab_thirdShift" href="#tab-thirdShift" data-type='thirdShift'>9PM - 7AM</a></li>
                                        </ul>
                                        <div class="tab-content" style="padding: 10px 5px;">
                                            <!-- 1st shift (8am-2pm) -->
                                            <div id="tab-firstShift" class="active in tab-pane fade">
                                                <div class='col-md-12'>
                                                    <div class="panel panel-info" id="jqGridBladder_c_1">
                                                        <div class="panel-body">
                                                            <div style="float: right; padding-right: 60px; padding-bottom: 15px;">
                                                                <label class="col-md-1 control-label">Input:</label>
                                                                <div class="col-md-5">
                                                                    <input id="tot_input1" name="tot_input1" type="text" class="form-control input-sm" readonly>
                                                                </div>

                                                                <label class="col-md-1 control-label">Output:</label>
                                                                <div class="col-md-5">
                                                                    <input id="tot_output1" name="tot_output1" type="text" class="form-control input-sm" readonly>
                                                                </div>
                                                            </div>

                                                            <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                                <input id="firstShift" name="firstShift" value="1" type="hidden">
                                                                <table id="jqGridBladder1" class="table table-striped"></table>
                                                                <div id="jqGridPagerBladder1"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- 2nd shift (3pm-9pm) -->
                                            <div id="tab-secondShift" class="tab-pane fade">
                                                <div class='col-md-12'>
                                                    <div class="panel panel-info" id="jqGridBladder_c_2">
                                                        <div class="panel-body">
                                                            <div style="float: right; padding-right: 60px; padding-bottom: 15px;">
                                                                <label class="col-md-1 control-label">Input:</label>
                                                                <div class="col-md-5">
                                                                    <input id="tot_input2" name="tot_input2" type="text" class="form-control input-sm" readonly>
                                                                </div>

                                                                <label class="col-md-1 control-label">Output:</label>
                                                                <div class="col-md-5">
                                                                    <input id="tot_output2" name="tot_output2" type="text" class="form-control input-sm" readonly>
                                                                </div>
                                                            </div>

                                                            <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                                <input id="secondShift" name="secondShift" value="2" type="hidden">
                                                                <table id="jqGridBladder2" class="table table-striped"></table>
                                                                <div id="jqGridPagerBladder2"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- 3rd shift (10pm-7am) -->
                                            <div id="tab-thirdShift" class="tab-pane fade">
                                                <div class='col-md-12'>
                                                    <div class="panel panel-info" id="jqGridBladder_c_3">
                                                        <div class="panel-body">
                                                            <div style="float: right; padding-right: 60px; padding-bottom: 15px;">
                                                                <label class="col-md-1 control-label">Input:</label>
                                                                <div class="col-md-5">
                                                                    <input id="tot_input3" name="tot_input3" type="text" class="form-control input-sm" readonly>
                                                                </div>

                                                                <label class="col-md-1 control-label">Output:</label>
                                                                <div class="col-md-5">
                                                                    <input id="tot_output3" name="tot_output3" type="text" class="form-control input-sm" readonly>
                                                                </div>
                                                            </div>

                                                            <div class='col-md-12' style="padding: 0 0 15px 0;">
                                                                <input id="thirdShift" name="thirdShift" value="3" type="hidden">
                                                                <table id="jqGridBladder3" class="table table-striped"></table>
                                                                <div id="jqGridPagerBladder3"></div>
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
                    <div id="tab-gcs" class="tab-pane fade">
                        @include('hisdb.nursingnote.nursingnote_glasgow')
                    </div>
                    <div id="tab-pivc" class="tab-pane fade">
                        @include('hisdb.nursingnote.nursingnote_pivc')
                    </div>
                    <div id="tab-morsefallscale" class="tab-pane fade">
                        @include('hisdb.nursingnote.morsefallscale')
                    </div>
                    <div id="tab-thrombo" class="tab-pane fade">
                        @include('hisdb.nursingnote.nursingnote_thrombo')
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>

<div id="InvChartDialog" title="Investigation Chart">
    <div class="panel panel-default">
        <!-- <div class="panel-heading">Investigation Chart</div> -->
        <div class="panel-body">
            <form class='form-horizontal' style='width: 99%;' id='formInvChartDialog'>
                <input type="hidden" name="action">
                
                <div class="form-group">
                    <div class="col-md-6">
                        <label class="control-label" for="Scol">From</label>
                        <input id="datefr" name="datefr" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                    </div>
                    <div class="col-md-6">
                        <label class="control-label" for="Scol">To</label>
                        <input id="dateto" name="dateto" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="panel panel-default" style="position: relative;" id="jqGridNursNote_c">
	
	<div class="panel-heading clearfix collapsed position" id="toggle_nursNote" style="position: sticky;top: 0px;z-index: 3;">
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
		
		<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridNursNote_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridNursNote_panel"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 25px;">
			<h5>Nursing Note</h5>
		</div>
	</div>
    
	<div id="jqGridNursNote_panel" class="panel-collapse collapse">
		<div class="panel-body paneldiv" style="overflow-y: auto;">
            <div class='col-md-12' style="padding:0 0 15px 0">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" id="navtab_progress" href="#tab-progress" aria-expanded="true" data-type='progress'>Progress Note</a></li>
                    <li><a data-toggle="tab" id="navtab_intake" href="#tab-intake" data-type='intake'>Intake Output</a></li>
                    <li><a data-toggle="tab" id="navtab_drug" href="#tab-drug" data-type='drug'>Drug Administration</a></li>
                    <li><a data-toggle="tab" id="navtab_treatment" href="#tab-treatment" data-type='treatment'>Treatment</a></li>
                    <li><a data-toggle="tab" id="navtab_careplan" href="#tab-careplan" data-type='careplan'>Care Plan</a></li>
                </ul>
                <div class="tab-content" style="padding: 10px 5px;">
                    <input id="mrn_nursNote" name="mrn_nursNote" type="hidden">
                    <input id="episno_nursNote" name="episno_nursNote" type="hidden">
                    <div id="tab-progress" class="active in tab-pane fade">
                        <div class='col-md-12'>
                            <div class="panel panel-info">
                                <div class="panel-heading text-center" style="height:40px">
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
                                    <form class='form-horizontal' style='width:99%' id='formProgress'>
                                        <input id="idno_progress" name="idno_progress" type="hidden">
                                        
                                        <div class="col-md-4" style="padding:0 0 0 0">
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
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class='col-md-8' style="padding-right: 0px;">
                                            <div class="panel panel-info">
                                                <div class="panel-body">
                                                    <div class="form-inline col-md-12" style="padding-bottom: 15px;">
                                                        <label class="control-label" for="datetaken" style="padding-right: 5px;">Date</label>
                                                        <input id="datetaken" name="datetaken" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>">
                                                        
                                                        <label class="control-label" for="timetaken" style="padding-left: 15px;padding-right: 5px;">Time</label>
                                                        <input id="timetaken" name="timetaken" type="time" class="form-control input-sm">
                                                    </div>
                                                    
                                                    <div class='col-md-4'>
                                                        <div class="panel panel-info">
                                                            <div class="panel-heading text-center">PATIENT STATUS</div>
                                                            <div class="panel-body">
                                                                <div class="form-group col-md-12">
                                                                    <label class="control-label" for="temp_" style="padding-bottom:5px">Temperature</label>
                                                                    <div class="input-group">
                                                                        <input id="temp_" name="temp_" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                                        <span class="input-group-addon">°C</span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group col-md-12">
                                                                    <label class="control-label" for="hr" style="padding-bottom:5px">HR</label>
                                                                    <div class="input-group">
                                                                        <input id="hr" name="hr" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                                        <span class="input-group-addon">bpm</span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group col-md-12">
                                                                    <label class="control-label" for="spo2" style="padding-bottom:5px">SPO2</label>
                                                                    <div class="input-group">
                                                                        <input id="spo2" name="spo2" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                                        <span class="input-group-addon">%</span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group col-md-12">
                                                                    <label class="control-label" for="ncase_bp" style="padding-bottom:5px">BP</label>
                                                                    <div class="input-group">
                                                                        <input id="bphistolic" name="bphistolic" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                                        <input id="bpdiastolic" name="bpdiastolic" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                                        <span class="input-group-addon">mmHg</span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group col-md-12">
                                                                    <label class="control-label" for="dxt" style="padding-bottom:5px">DXT</label>
                                                                    <div class="input-group">
                                                                        <input id="dxt" name="dxt" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                                        <span class="input-group-addon">mmol/L</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class='col-md-8'>
                                                        <div class="panel panel-info">
                                                            <div class="panel-heading text-center">ASSESSMENT</div>
                                                            <div class="panel-body">
                                                                <div class='col-md-6' style="padding: 0px 0px;">
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label" for="airway">Airway</label>  
                                                                        <div class="col-md-8" style="padding-top:6px;">
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="roomair" value="1" style="margin-right:18px;">Room Air
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="oxygen" value="1" style="margin-right:18px;">Oxygen
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">Others</label>
                                                                                <div class="form-check col-md-12" style="padding-left:0px;">
                                                                                    <textarea id="airwayfreetext" name="airwayfreetext" type="text" class="form-control input-sm"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label" for="breathing">Breathing</label>  
                                                                        <div class="col-md-8" style="padding-top:6px;">
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="breathnormal" value="1" style="margin-right:18px;">Normal
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="breathdifficult" value="1" style="margin-right:18px;">Difficult
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label" for="circulation">Circulation</label>  
                                                                        <div class="col-md-8" style="padding-top:6px;">
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="circarrythmias" value="1" style="margin-right:18px;">Arrhythmias
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="circlbp" value="1" style="margin-right:18px;">Low BP
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="circhbp" value="1" style="margin-right:18px;">High BP
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="circirregular" value="1" style="margin-right:18px;">Irregular HR
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label" for="fallrisk">Disability Fall Risk</label>  
                                                                        <div class="col-md-8" style="padding-top:6px;">
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="frhigh" value="1" style="margin-right:18px;">High
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="frlow" value="1" style="margin-right:18px;">Low
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">Others</label>
                                                                                <div class="form-check col-md-12" style="padding-left:0px;">
                                                                                    <textarea id="frfreetext" name="frfreetext" type="text" class="form-control input-sm"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class='col-md-6' style="padding: 0px 0px;">
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label" for="exposuredrain">Exposure Drain</label>  
                                                                        <div class="col-md-8" style="padding-top:6px;">
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="drainnone" value="1" style="margin-right:18px;">None
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="draindrainage" value="1" style="margin-right:18px;">Drainage
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">Others</label>
                                                                                <div class="form-check col-md-12" style="padding-left:0px;">
                                                                                    <textarea id="drainfreetext" name="drainfreetext" type="text" class="form-control input-sm"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label" for="ivline">IV Line</label>  
                                                                        <div class="col-md-8" style="padding-top:6px;">
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="ivlnone" value="1" style="margin-right:18px;">None
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="ivlsite" value="1" style="margin-right:18px;">Site
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">Others</label>
                                                                                <div class="form-check col-md-12" style="padding-left:0px;">
                                                                                    <textarea id="ivfreetext" name="ivfreetext" type="text" class="form-control input-sm"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label" for="gu">GU</label>  
                                                                        <div class="col-md-8" style="padding-top:6px;">
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="gucontinent" value="1" style="margin-right:18px;">Continent
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">
                                                                                    <input class="form-check-input" type="checkbox" name="gufoley" value="1" style="margin-right:18px;">Foley
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <label class="form-check-label">Others</label>
                                                                                <div class="form-check col-md-12" style="padding-left:0px;">
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
                    
                    </div>
                    <div id="tab-drug" class="tab-pane fade">
                    
                    </div>
                    <div id="tab-treatment" class="tab-pane fade">
                    
                    </div>
                    <div id="tab-careplan" class="tab-pane fade">
                    
                    </div>
                </div>
            </div>
		</div>
	</div>
	
</div>


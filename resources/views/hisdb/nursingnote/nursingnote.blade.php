
<div class="panel panel-default" style="position: relative;" id="jqGridNursNote_c">
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
    
    <div id="jqGridNursNote_panel" class="panel-collapse collapse" data-curtype='navtab_progress'>
        <div class="panel-body paneldiv" style="overflow-y: auto;">
            <div class='col-md-12' style="padding: 0 0 15px 0;">
                <ul class="nav nav-tabs" id="jqGridNursNote_panel_tabs">
                    <li><a data-toggle="tab" id="navtab_progress" href="#tab-progress" data-type='progress'>Progress Note</a></li>
                    <li><a data-toggle="tab" id="navtab_intake" href="#tab-intake" data-type='intake'>Intake Output</a></li>
                    <li><a data-toggle="tab" id="navtab_drug" href="#tab-drug" data-type='drug'>Drug Administration</a></li>
                    <li><a data-toggle="tab" id="navtab_treatment" href="#tab-treatment" data-type='treatment'>Treatment</a></li>
                    <li><a data-toggle="tab" id="navtab_careplan" href="#tab-careplan" data-type='careplan'>Care Plan</a></li>
                    <li><a data-toggle="tab" id="navtab_fitchart" href="#tab-fitchart" data-type='fitchart'>Fit Chart</a></li>
                    <li><a data-toggle="tab" id="navtab_circulation" href="#tab-circulation" data-type='circulation'>Circulation Chart</a></li>
                    <li><a data-toggle="tab" id="navtab_slidScale" href="#tab-slidScale" data-type='slidScale'>Sliding Scale Chart</a></li>
                    <li><a data-toggle="tab" id="navtab_othChart1" href="#tab-othChart1" data-type='othChart1'>Others Chart 1</a></li>
                    <li><a data-toggle="tab" id="navtab_othChart2" href="#tab-othChart2" data-type='othChart2'>Others Chart 2</a></li>
                </ul>
                <div class="tab-content" style="padding: 10px 5px;">
                    <input id="mrn_nursNote" name="mrn_nursNote" type="hidden">
                    <input id="episno_nursNote" name="episno_nursNote" type="hidden">
                    <input id="doctor_nursNote" name="doctor_nursNote" type="hidden">
                    <input id="ward_nursNote" name="ward_nursNote" type="hidden">
                    <input id="bednum_nursNote" name="bednum_nursNote" type="hidden">
                    <input type="hidden" id="ordcomtt_phar" value="{{$ordcomtt_phar ?? ''}}">
                    <div id="tab-progress" class="active in tab-pane fade">
                        <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center" style="height: 40px;">
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
                                                        
                                                        <label class="control-label" for="timetaken" style="padding-left: 15px; padding-right: 5px;">Time</label>
                                                        <input id="timetaken" name="timetaken" type="time" class="form-control input-sm">
                                                        
                                                        <button class="btn btn-default btn-sm" type="button" id="doctornote_bpgraph" style="float: right; margin-right: 20px;">Chart</button>
                                                    </div>
                                                    
                                                    <div class='col-md-4'>
                                                        <div class="panel panel-info">
                                                            <div class="panel-heading text-center">PATIENT STATUS</div>
                                                            <div class="panel-body">
                                                                <div class="form-group col-md-12">
                                                                    <label class="control-label" for="temp_" style="padding-bottom: 5px;">Temperature</label>
                                                                    <div class="input-group">
                                                                        <input id="temp_" name="temp_" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                                        <span class="input-group-addon">°C</span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group col-md-12">
                                                                    <label class="control-label" for="hr" style="padding-bottom: 5px;">HR</label>
                                                                    <div class="input-group">
                                                                        <input id="hr" name="hr" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                                        <span class="input-group-addon">bpm</span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group col-md-12">
                                                                    <label class="control-label" for="spo2" style="padding-bottom: 5px;">SPO2</label>
                                                                    <div class="input-group">
                                                                        <input id="spo2" name="spo2" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                                        <span class="input-group-addon">%</span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group col-md-12">
                                                                    <label class="control-label" for="ncase_bp" style="padding-bottom: 5px;">BP</label>
                                                                    <div class="input-group">
                                                                        <input id="bphistolic" name="bphistolic" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                                        <input id="bpdiastolic" name="bpdiastolic" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                                        <span class="input-group-addon">mmHg</span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group col-md-12">
                                                                    <label class="control-label" for="dxt" style="padding-bottom: 5px;">DXT</label>
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
                        <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center" style="height: 40px;">
                                    <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_intake"
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 40px;
                                                top: 5px;">
                                        <button type="button" class="btn btn-default" id="new_intake">
                                            <span class="fa fa-plus-square-o"></span> New 
                                        </button>
                                        <button type="button" class="btn btn-default" id="edit_intake">
                                            <span class="fa fa-edit fa-lg"></span> Edit 
                                        </button>
                                        <button type="button" class="btn btn-default" data-oper='add' id="save_intake">
                                            <span class="fa fa-save fa-lg"></span> Save 
                                        </button>
                                        <button type="button" class="btn btn-default" id="cancel_intake">
                                            <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="panel-body">
                                    <form class='form-horizontal' style='width: 99%;' id='formIntake'>
                                        <button class="btn btn-default btn-sm" type="button" id="doctornote_iograph" style="float: right; margin-right: 20px;">Preview</button>
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a data-toggle="tab" id="navtab_first" href="#tab-first" aria-expanded="true" data-shift='first'>First Shift</a></li>
                                            <li><a data-toggle="tab" id="navtab_second" href="#tab-second" data-shift='second'>Second Shift</a></li>
                                            <li><a data-toggle="tab" id="navtab_third" href="#tab-third" data-shift='third'>Third Shift</a></li>
                                        </ul>
                                        <div class="tab-content" style="padding: 10px 5px;">
                                            <div id="tab-first" class="active in tab-pane fade">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col"></th>
                                                            <th scope="col">Oral (IN)</th>
                                                            <th scope="col">Intra-Vena (IN)</th>
                                                            <th scope="col">Others (IN)</th>
                                                            <th scope="col">Urine (OUT)</th>
                                                            <th scope="col">Vomit (OUT)</th>
                                                            <th scope="col">Aspirate (OUT)</th>
                                                            <th scope="col">Others (OUT)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>07:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype1" name="oraltype1" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt1" name="oralamt1" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype1" name="intratype1" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt1" name="intraamt1" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype1" name="othertype1" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt1" name="otheramt1" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt1" name="urineamt1" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt1" name="vomitamt1" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt1" name="aspamt1" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout1" name="otherout1" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>08:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype2" name="oraltype2" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt2" name="oralamt2" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype2" name="intratype2" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt2" name="intraamt2" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype2" name="othertype2" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt2" name="otheramt2" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt2" name="urineamt2" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt2" name="vomitamt2" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt2" name="aspamt2" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout2" name="otherout2" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>09:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype3" name="oraltype3" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt3" name="oralamt3" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype3" name="intratype3" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt3" name="intraamt3" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype3" name="othertype3" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt3" name="otheramt3" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt3" name="urineamt3" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt3" name="vomitamt3" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt3" name="aspamt3" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout3" name="otherout3" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>10:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype4" name="oraltype4" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt4" name="oralamt4" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype4" name="intratype4" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt4" name="intraamt4" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype4" name="othertype4" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt4" name="otheramt4" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt4" name="urineamt4" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt4" name="vomitamt4" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt4" name="aspamt4" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout4" name="otherout4" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>11:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype5" name="oraltype5" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt5" name="oralamt5" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype5" name="intratype5" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt5" name="intraamt5" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype5" name="othertype5" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt5" name="otheramt5" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt5" name="urineamt5" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt5" name="vomitamt5" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt5" name="aspamt5" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout5" name="otherout5" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>12:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype6" name="oraltype6" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt6" name="oralamt6" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype6" name="intratype6" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt6" name="intraamt6" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype6" name="othertype6" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt6" name="otheramt6" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt6" name="urineamt6" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt6" name="vomitamt6" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt6" name="aspamt6" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout6" name="otherout6" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>13:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype7" name="oraltype7" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt7" name="oralamt7" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype7" name="intratype7" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt7" name="intraamt7" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype7" name="othertype7" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt7" name="otheramt7" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt7" name="urineamt7" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt7" name="vomitamt7" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt7" name="aspamt7" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout7" name="otherout7" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>14:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype8" name="oraltype8" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt8" name="oralamt8" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype8" name="intratype8" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt8" name="intraamt8" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype8" name="othertype8" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt8" name="otheramt8" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt8" name="urineamt8" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt8" name="vomitamt8" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt8" name="aspamt8" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout8" name="otherout8" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div id="tab-second" class="tab-pane fade">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col"></th>
                                                            <th scope="col">Oral (IN)</th>
                                                            <th scope="col">Intra-Vena (IN)</th>
                                                            <th scope="col">Others (IN)</th>
                                                            <th scope="col">Urine (OUT)</th>
                                                            <th scope="col">Vomit (OUT)</th>
                                                            <th scope="col">Aspirate (OUT)</th>
                                                            <th scope="col">Others (OUT)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>15:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype9" name="oraltype9" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt9" name="oralamt9" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype9" name="intratype9" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt9" name="intraamt9" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype9" name="othertype9" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt9" name="otheramt9" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt9" name="urineamt9" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt9" name="vomitamt9" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt9" name="aspamt9" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout9" name="otherout9" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>16:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype10" name="oraltype10" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt10" name="oralamt10" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype10" name="intratype10" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt10" name="intraamt10" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype10" name="othertype10" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt10" name="otheramt10" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt10" name="urineamt10" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt10" name="vomitamt10" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt10" name="aspamt10" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout10" name="otherout10" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>17:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype11" name="oraltype11" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt11" name="oralamt11" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype11" name="intratype11" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt11" name="intraamt11" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype11" name="othertype11" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt11" name="otheramt11" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt11" name="urineamt11" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt11" name="vomitamt11" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt11" name="aspamt11" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout11" name="otherout11" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>18:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype12" name="oraltype12" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt12" name="oralamt12" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype12" name="intratype12" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt12" name="intraamt12" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype12" name="othertype12" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt12" name="otheramt12" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt12" name="urineamt12" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt12" name="vomitamt12" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt12" name="aspamt12" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout12" name="otherout12" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>19:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype13" name="oraltype13" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt13" name="oralamt13" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype13" name="intratype13" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt13" name="intraamt13" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype13" name="othertype13" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt13" name="otheramt13" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt13" name="urineamt13" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt13" name="vomitamt13" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt13" name="aspamt13" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout13" name="otherout13" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>20:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype14" name="oraltype14" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt14" name="oralamt14" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype14" name="intratype14" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt14" name="intraamt14" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype14" name="othertype14" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt14" name="otheramt14" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt14" name="urineamt14" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt14" name="vomitamt14" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt14" name="aspamt14" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout14" name="otherout14" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>21:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype15" name="oraltype15" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt15" name="oralamt15" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype15" name="intratype15" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt15" name="intraamt15" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype15" name="othertype15" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt15" name="otheramt15" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt15" name="urineamt15" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt15" name="vomitamt15" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt15" name="aspamt15" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout15" name="otherout15" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>22:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype16" name="oraltype16" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt16" name="oralamt16" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype16" name="intratype16" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt16" name="intraamt16" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype16" name="othertype16" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt16" name="otheramt16" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt16" name="urineamt16" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt16" name="vomitamt16" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt16" name="aspamt16" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout16" name="otherout16" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div id="tab-third" class="tab-pane fade">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col"></th>
                                                            <th scope="col">Oral (IN)</th>
                                                            <th scope="col">Intra-Vena (IN)</th>
                                                            <th scope="col">Others (IN)</th>
                                                            <th scope="col">Urine (OUT)</th>
                                                            <th scope="col">Vomit (OUT)</th>
                                                            <th scope="col">Aspirate (OUT)</th>
                                                            <th scope="col">Others (OUT)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>23:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype17" name="oraltype17" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt17" name="oralamt17" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype17" name="intratype17" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt17" name="intraamt17" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype17" name="othertype17" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt17" name="otheramt17" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt17" name="urineamt17" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt17" name="vomitamt17" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt17" name="aspamt17" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout17" name="otherout17" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>24:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype18" name="oraltype18" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt18" name="oralamt18" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype18" name="intratype18" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt18" name="intraamt18" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype18" name="othertype18" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt18" name="otheramt18" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt18" name="urineamt18" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt18" name="vomitamt18" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt18" name="aspamt18" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout18" name="otherout18" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>01:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype19" name="oraltype19" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt19" name="oralamt19" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype19" name="intratype19" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt19" name="intraamt19" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype19" name="othertype19" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt19" name="otheramt19" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt19" name="urineamt19" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt19" name="vomitamt19" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt19" name="aspamt19" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout19" name="otherout19" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>02:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype20" name="oraltype20" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt20" name="oralamt20" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype20" name="intratype20" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt20" name="intraamt20" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype20" name="othertype20" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt20" name="otheramt20" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt20" name="urineamt20" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt20" name="vomitamt20" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt20" name="aspamt20" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout20" name="otherout20" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>03:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype21" name="oraltype21" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt21" name="oralamt21" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype21" name="intratype21" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt21" name="intraamt21" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype21" name="othertype21" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt21" name="otheramt21" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt21" name="urineamt21" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt21" name="vomitamt21" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt21" name="aspamt21" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout21" name="otherout21" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>04:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype22" name="oraltype22" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt22" name="oralamt22" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype22" name="intratype22" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt22" name="intraamt22" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype22" name="othertype22" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt22" name="otheramt22" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt22" name="urineamt22" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt22" name="vomitamt22" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt22" name="aspamt22" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout22" name="otherout22" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>05:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype23" name="oraltype23" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt23" name="oralamt23" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype23" name="intratype23" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt23" name="intraamt23" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype23" name="othertype23" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt23" name="otheramt23" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt23" name="urineamt23" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt23" name="vomitamt23" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt23" name="aspamt23" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout23" name="otherout23" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>06:00</td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="oraltype24" name="oraltype24" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="oralamt24" name="oralamt24" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="intratype24" name="intratype24" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="intraamt24" name="intraamt24" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
                                                                    <textarea id="othertype24" name="othertype24" type="text" class="form-control input-sm"></textarea>
                                                                </div>
                                                                <div class="col-md-6" style="padding-right: 0px; padding-left: 5px;">
                                                                    <input id="otheramt24" name="otheramt24" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="urineamt24" name="urineamt24" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="vomitamt24" name="vomitamt24" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="aspamt24" name="aspamt24" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <input id="otherout24" name="otherout24" type="text" class="form-control input-sm">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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
                                                <input autocomplete="off" name="drugindicator" id="drugindicator_nursNote" type="text" class="form-control input-sm" style="text-transform:uppercase" rdonly>
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
                                <div class="panel-heading text-center" style="height: 40px;">
                                    <div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 215px; top: 5px;">
                                        <h6>TREATMENT AND PROCEDURE</h6>
                                    </div>
                                    <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_treatment"
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 10px;
                                                top: 5px;">
                                        <button type="button" class="btn btn-default" id="new_treatment">
                                            <span class="fa fa-plus-square-o"></span> New 
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
                                    <form class='form-horizontal' style='width: 99%;' id='formTreatment'>
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
                                                    <div class="form-group" style="padding-left: 15px; padding-right: 15px;">
														<label for="treatment_remarks">Notes</label>
														<textarea id="treatment_remarks" name="treatment_remarks" type="text" class="form-control input-sm"></textarea>
													</div>
                                                    
                                                    <div class="form-group" style="padding-left: 15px; padding-right: 15px;">
                                                        <label for="treatment_adduser">Entered by</label>
                                                        <input name="treatment_adduser" type="text" class="form-control input-sm" rdonly>
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
                                <div class="panel-heading text-center" style="height: 40px;">
                                    <div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 215px; top: 5px;">
                                        <h6>INVESTIGATION</h6>
                                    </div>
                                    <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_investigation"
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 10px;
                                                top: 5px;">
                                        <button type="button" class="btn btn-default" id="new_investigation">
                                            <span class="fa fa-plus-square-o"></span> New 
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
                                                    <div class="form-group" style="padding-left: 15px; padding-right: 15px;">
														<label for="investigation_remarks">Notes</label>
														<textarea id="investigation_remarks" name="investigation_remarks" type="text" class="form-control input-sm"></textarea>
													</div>
                                                    
                                                    <div class="form-group" style="padding-left: 15px; padding-right: 15px;">
                                                        <label for="investigation_adduser">Entered by</label>
                                                        <input name="investigation_adduser" type="text" class="form-control input-sm" rdonly>
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
                                <div class="panel-heading text-center" style="height: 40px;">
                                    <div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 215px; top: 5px;">
                                        <h6>INJECTION</h6>
                                    </div>
                                    <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_injection"
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 10px;
                                                top: 5px;">
                                        <button type="button" class="btn btn-default" id="new_injection">
                                            <span class="fa fa-plus-square-o"></span> New 
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
                                                    <div class="form-group" style="padding-left: 15px; padding-right: 15px;">
														<label for="injection_remarks">Notes</label>
														<textarea id="injection_remarks" name="injection_remarks" type="text" class="form-control input-sm"></textarea>
													</div>
                                                    
                                                    <div class="form-group" style="padding-left: 15px; padding-right: 15px;">
                                                        <label for="injection_adduser">Entered by</label>
                                                        <input name="injection_adduser" type="text" class="form-control input-sm" rdonly>
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
                                <div class="panel-heading text-center" style="height: 40px;">
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
                                    <!-- <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_fitchart"
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 40px;
                                                top: 5px;">
                                        <button type="button" class="btn btn-default" id="new_fitchart">
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
                                        </button>
                                    </div> -->
                                </div>
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
                                                    <div class='col-md-12' style="padding:0 0 15px 0">
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
                                    <!-- <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_circulation"
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 40px;
                                                top: 5px;">
                                        <button type="button" class="btn btn-default" id="new_circulation">
                                            <span class="fa fa-plus-square-o"></span> New 
                                        </button>
                                        <button type="button" class="btn btn-default" data-oper='add' id="save_circulation">
                                            <span class="fa fa-save fa-lg"></span> Save 
                                        </button>
                                        <button type="button" class="btn btn-default" id="cancel_circulation">
                                            <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                        </button>
                                    </div> -->
                                </div>
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
                                                    <div class='col-md-12' style="padding:0 0 15px 0">
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
                    <div id="tab-slidScale" class="tab-pane fade">
                        <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center" style="height: 40px;">
                                    <!-- <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_slidScale"
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 40px;
                                                top: 5px;">
                                        <button type="button" class="btn btn-default" id="new_slidScale">
                                            <span class="fa fa-plus-square-o"></span> New 
                                        </button>
                                        <button type="button" class="btn btn-default" data-oper='add' id="save_slidScale">
                                            <span class="fa fa-save fa-lg"></span> Save 
                                        </button>
                                        <button type="button" class="btn btn-default" id="cancel_slidScale">
                                            <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                        </button>
                                    </div> -->
                                </div>
                                <div class="panel-body" style="padding-right: 0px;">
                                    <form class='form-horizontal' style='width: 99%;' id='formSlidScale'>
                                    
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-othChart1" class="tab-pane fade">
                        <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center" style="height: 40px;">
                                    <!-- <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_othChart1"
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 40px;
                                                top: 5px;">
                                        <button type="button" class="btn btn-default" id="new_othChart1">
                                            <span class="fa fa-plus-square-o"></span> New 
                                        </button>
                                        <button type="button" class="btn btn-default" data-oper='add' id="save_othChart1">
                                            <span class="fa fa-save fa-lg"></span> Save 
                                        </button>
                                        <button type="button" class="btn btn-default" id="cancel_othChart1">
                                            <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                        </button>
                                    </div> -->
                                </div>
                                <div class="panel-body" style="padding-right: 0px;">
                                    <form class='form-horizontal' style='width: 99%;' id='formOthChart1'>
                                    
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-othChart2" class="tab-pane fade">
                        <div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center" style="height: 40px;">
                                    <!-- <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_othChart2"
                                        style="position: absolute;
                                                padding: 0 0 0 0;
                                                right: 40px;
                                                top: 5px;">
                                        <button type="button" class="btn btn-default" id="new_othChart2">
                                            <span class="fa fa-plus-square-o"></span> New 
                                        </button>
                                        <button type="button" class="btn btn-default" data-oper='add' id="save_othChart2">
                                            <span class="fa fa-save fa-lg"></span> Save 
                                        </button>
                                        <button type="button" class="btn btn-default" id="cancel_othChart2">
                                            <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                                        </button>
                                    </div> -->
                                </div>
                                <div class="panel-body" style="padding-right: 0px;">
                                    <form class='form-horizontal' style='width: 99%;' id='formOthChart2'>
                                    
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>


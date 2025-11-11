<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        <div class="ui small blue icon buttons" id="btn_grp_edit_progress_ED" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_progress_ED"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_progress_ED"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_progress_ED"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_progress_ED"><span class="fa fa-ban fa-lg"></span>Cancel</button>
        </div>
    </div>
    <div class="ui segment">
        <form class="ui form sixteen wide column" id="formProgress_ED">
            <input id="idno_progress_ED" name="idno_progress" type="hidden">
            <div class="sixteen wide column">
                <div class="ui grid">
                    <div class="five wide column" style="padding: 3px 3px 3px 3px;">
                        <div class="ui segments">
                            <div class="ui segment">
                                <div class="ui grid">
                                    <table id="datetime_ED_tbl" class="ui celled table" style="width: 100%;">
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
                    </div>
                    
                    <div class="eleven wide column" style="padding: 3px 3px 3px 3px;" >
                        <div class="ui segment">
                            <div class='ui grid' style="padding: 5px 3px 3px 2px;">
                                <div class="sixteen wide column" style="padding: 10px 0px 0px 3px;">
                                    <div class="inline fields">
                                        <div class="field">
                                            <label for="datetaken" style="padding-right: 5px;">Date</label>
                                            <input id="datetaken" name="datetaken" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" data-validation="required" data-validation-error-msg-required="Please enter information.">
                                        </div>
                                        <div class="field">
                                            <label for="timetaken" style="padding-left: 15px; padding-right: 5px;">Time</label>
                                            <input id="timetaken" name="timetaken" type="time" class="form-control input-sm"  data-validation="required" data-validation-error-msg-required="Please enter information.">
                                        </div>                
                                        <!-- <div style="position: absolute;
                                                        padding: 0 0 0 0;
                                                        right: 0px;
                                                        top: 0px;
                                                        z-index: 1000;">
                                            <button class="ui green button" type="button" id="doctornote_bpgraph">Chart</button>        
                                        </div>                                              -->
                                    </div>
                                </div>

                                <div class="six wide column" style="padding: 5px 5px 3px 3px;">
                                    <div class="ui segments">
                                        <div class="ui secondary segment">PATIENT STATUS</div>
                                        <div class="ui segment">
                                            <div class="field">
                                                <label>BP (standing)</label>
                                                <div class="ui right labeled input">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="bpsys_stand" name="bpsys_stand" style="width:35%">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="bpdias_stand" name="bpdias_stand" style="width:35%">
                                                    <div class="ui basic label">mmHg</div>
                                                </div>
                                            </div>

                                            <div class="field">
                                                <label>BP (lying down)</label>
                                                <div class="ui right labeled input">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="bpsys_lieDown" name="bpsys_lieDown" style="width:35%">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="bpdias_lieDown" name="bpdias_lieDown" style="width:35%">
                                                    <div class="ui basic label">mmHg</div>
                                                </div>
                                            </div>
                                            
                                            <div class="field">
                                                <label>SPO2</label>
                                                <div class="ui right labeled input">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="spo2" name="spo2">
                                                    <div class="ui basic label">%</div>
                                                </div>
                                            </div>
                                            
                                            <div class="field">
                                                <label>HR</label>
                                                <div class="ui right labeled input">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="hr" name="hr">
                                                    <div class="ui basic label">Bpm</div>
                                                </div>
                                            </div>
                                            
                                            <div class="field">
                                                <label>Glucometer</label>
                                                <div class="ui right labeled input">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="gxt" name="gxt">
                                                    <div class="ui basic label">mmol/L</div>
                                                </div>
                                            </div>
                                            
                                            <div class="field">
                                                <label>Temperature</label>
                                                <div class="ui right labeled input">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="temp_" name="temp_">
                                                    <div class="ui basic label">°C</div>
                                                </div>
                                            </div>
                                            
                                            <div class="field">
                                                <label>Weight</label>
                                                <div class="ui right labeled input">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="weight" name="weight">
                                                    <div class="ui basic label">KG</div>
                                                </div>
                                            </div>

                                            <div class="field">
                                                <label>Height</label>
                                                <div class="ui right labeled input">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="height" name="height">
                                                    <div class="ui basic label">CM</div>
                                                </div>
                                            </div>
                                            
                                            <div class="field">
                                                <label>RR</label>
                                                <div class="ui right labeled input">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="respiration" name="respiration">
                                                    <div class="ui basic label">Min</div>
                                                </div>
                                            </div>

                                            <div class="field">
                                                <label>Pain Score</label>
                                                <div class="ui right labeled input">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="painscore" name="painscore">
                                                    <div class="ui basic label">/10</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="ten wide column" style="padding: 5px 3px 3px 0px;">
                                    <div class="ui segments">
                                        <div class="ui secondary segment">ASSESSMENT</div>
                                        <div class="ui segment">
                                            <div class="ui grid">
                                                <div class="eight wide column">
                                                    <div class="left floated right aligned" style="padding: 0px 3px 0px 3px;">
                                                        <label for="airway">Airway</label>
                                                        <div class="right floated left aligned" style="padding: 10px 0px 0px 3px;">
                                                            <div class="field">
                                                                <label><input type="checkbox" id="roomair" name="roomair" value="1" style="margin-right: 18px;">Room Air</label>
                                                            </div>
                                                            <div class="field">
                                                                <label><input type="checkbox" id="oxygen" name="oxygen" value="1" style="margin-right: 18px;">Oxygen</label>
                                                            </div>
                                                            <div class="field">
                                                                <label>Others</label><textarea type="text" id="airwayfreetext" name="airwayfreetext" style="margin-right: 18px;" rows="4"></textarea>
                                                            </div>
                                                        </div>                                           
                                                    </div>
                                                </div>
                                                <div class="eight wide column">
                                                    <div class="left floated right aligned" style="padding: 0px 3px 0px 3px;">
                                                        <label for="exposuredrain">Exposure Drain</label>
                                                        <div class="right floated left aligned" style="padding: 0px 0px 0px 3px;">
                                                            <div class="field">
                                                                <label><input type="checkbox" id="drainnone" name="drainnone" value="1" style="margin-right: 18px;">None</label>
                                                            </div>
                                                            <div class="field">
                                                                <label><input type="checkbox" id="draindrainage" name="draindrainage" value="1" style="margin-right: 18px;">Drainage</label>
                                                            </div>
                                                            <div class="field">
                                                                <label>Others</label><textarea type="text" id="drainfreetext" name="drainfreetext" style="margin-right: 18px;" rows="4"></textarea>
                                                            </div>
                                                        </div>                                           
                                                    </div>
                                                </div>
                                                <div class="eight wide column">
                                                    <div class="left floated right aligned" style="padding: 0px 3px 0px 3px;">
                                                        <label for="breathing">Breathing</label>
                                                        <table class="table;border border-white">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="margin:0px; padding: 3px 14px 14px 14px;"> 
                                                                        <div class="field">
                                                                            <label><input type="checkbox" id="breathnormal" name="breathnormal" value="1" style="margin-right: 18px;">Normal</label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="margin:0px; padding: 3px 14px 14px 14px;"> 
                                                                        <div class="field">
                                                                            <label><input type="checkbox" id="breathdifficult" name="breathdifficult" value="1" style="margin-right: 18px;">Difficult</label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="eight wide column">
                                                    <div class="left floated right aligned" style="padding: 0px 3px 0px 3px;">
                                                        <label for="ivline">IV Line</label>
                                                        <div class="right floated left aligned" style="padding: 10px 0px 0px 3px;">
                                                            <div class="field">
                                                                <label><input type="checkbox" id="ivlnone" name="ivlnone" value="1" style="margin-right: 18px;">None</label>
                                                            </div>
                                                            <div class="field">
                                                                <label><input type="checkbox" id="ivlsite" name="ivlsite" value="1" style="margin-right: 18px;">Site</label>
                                                            </div>
                                                            <div class="field">
                                                                <label>Others</label><textarea type="text" id="ivfreetext" name="ivfreetext" style="margin-right: 18px;" rows="4"></textarea>
                                                            </div>
                                                        </div>                                           
                                                    </div>
                                                </div>
                                                <div class="eight wide column">
                                                    <div class="left floated right aligned" style="padding: 0px 3px 0px 3px;">
                                                        <label for="circulation">Circulation</label>
                                                        <table class="table;border border-white">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="margin:0px; padding: 3px 14px 14px 14px;"> 
                                                                        <div class="field">
                                                                            <label><input type="checkbox" id="circarrythmias" name="circarrythmias" value="1" style="margin-right: 18px;">Arrhythmias</label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="margin:0px; padding: 3px 14px 14px 14px;"> 
                                                                        <div class="field">
                                                                            <label><input type="checkbox" id="circlbp" name="circlbp" value="1" style="margin-right: 18px;">Low BP</label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="margin:0px; padding: 3px 14px 14px 14px;"> 
                                                                        <div class="field">
                                                                            <label><input type="checkbox" id="circhbp" name="circhbp" value="1" style="margin-right: 18px;">High BP</label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="margin:0px; padding: 3px 14px 14px 14px;"> 
                                                                        <div class="field">
                                                                            <label><input type="checkbox" id="circirregular" name="circirregular" value="1" style="margin-right: 18px;">Irregular HR</label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="eight wide column">
                                                    <div class="left floated right aligned" style="padding: 0px 3px 0px 3px;">
                                                        <label for="gu">GU</label>
                                                        <div class="right floated left aligned" style="padding: 20px 0px 0px 3px;">
                                                            <div class="field">
                                                                <label><input type="checkbox" id="gucontinent" name="gucontinent" value="1" style="margin-right: 18px;">Continent</label>
                                                            </div>
                                                            <div class="field">
                                                                <label><input type="checkbox" id="gufoley" name="gufoley" value="1" style="margin-right: 18px;">Foley</label>
                                                            </div>
                                                            <div class="field">
                                                                <label>Others</label><textarea type="text" id="assesothers" name="assesothers" style="margin-right: 18px;" rows="4"></textarea>
                                                            </div>
                                                        </div>                                           
                                                    </div>
                                                </div>
                                                <div class="eight wide column">
                                                    <div class="left floated right aligned" style="padding: 0px 3px 0px 3px;">
                                                        <label for="fallrisk">Disability Fall Risk</label>
                                                        <div class="right floated left aligned" style="padding: 10px 0px 0px 3px;">
                                                            <div class="field">
                                                                <label><input type="checkbox" id="frhigh" name="frhigh" value="1" style="margin-right: 18px;">High</label>
                                                            </div>
                                                            <div class="field">
                                                                <label><input type="checkbox" id="frlow" name="frlow" value="1" style="margin-right: 18px;">Low</label>
                                                            </div>
                                                            <div class="field">
                                                                <label>Others</label><textarea type="text" id="frfreetext" name="frfreetext" style="margin-right: 18px;" rows="4"></textarea>
                                                            </div>
                                                        </div>                                           
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="sixteen wide column" style="padding: 5px 3px 3px 0px;">
                                    <div class="ui segments">
                                        <div class="ui secondary segment">PLAN AND PROGRESS NOTE</div>
                                        <div class="ui segment">
                                            <textarea id="plannotes" name="plannotes" type="text" rows="4" ></textarea>
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
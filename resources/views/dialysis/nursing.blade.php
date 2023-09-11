<div class="ui segments" style="position: relative;">
    <div class="ui inverted dimmer" id="loader_nursing">
       <div class="ui large text loader">Loading</div>
    </div>
    <div class="ui secondary segment bluecloudsegment">
        NURSING
        <div class="ui small blue icon buttons" id="btn_grp_edit_ti" style="position: absolute;
                    padding: 0 0 0 0;
                    right: 40px;
                    top: 9px;
                    z-index: 2;">
          <button class="ui button" id="new_ti"><span class="fa fa-plus-square-o"></span> New</button>
          <button class="ui button" id="edit_ti"><span class="fa fa-edit fa-lg"></span> Edit</button>
          <button class="ui button" id="save_ti"><span class="fa fa-save fa-lg"></span> Save</button>
          <button class="ui button" id="cancel_ti"><span class="fa fa-ban fa-lg"></span> Cancel</button>
        </div>

        <div class="three wide column" style="position: absolute;
            left: 10px;
            top: 60px;
            overflow-y: auto;
            max-height: 70vh;">
            <div class="ui segment"> 
                <div class="field">
                    <div class="ui radio checkbox checked pastcurr_nurse">
                        <input type="radio" name="toggle_type_nurse" checked="" tabindex="0" class="hidden" id="current_nurse" value="current" checked>
                        <label for="current_nurse">Current Month</label>
                    </div>
                </div>
                <div class="field">
                    <div class="ui radio checkbox pastcurr_nurse">
                        <input type="radio" name="toggle_type_nurse" tabindex="0" class="hidden" id="past_nurse" value="past" >
                        <label for="past_nurse">Past History</label>
                    </div>
                </div>
            </div>
            <table id="nursing_date_tbl" class="ui celled table" style="min-width: 200px; max-height: 60vh;">
                <thead>
                    <tr>
                        <th class="scope">mrn</th>
                        <th class="scope">episno</th>
                        <th class="scope">Date</th>
                        <th class="scope">adduser</th>
                        <th class="scope">adddate</th>
                        <th class="scope">recordtime</th>
                        <th class="scope">type</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="ui segment">
      <div class="ui grid">
        <form id="formTriageInfo" class="right floated ui form thirteen wide column">
            <input id="mrn_ti" name="mrn_ti" type="hidden">
            <input id="episno_ti" name="episno_ti" type="hidden">
            <input id="arrival_date_ti" name="arrival_date_ti" type="hidden">

            <div class="ui grid">

                <div class="eight wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">INFORMATION</div>
                        <div class="ui segment">
                            <div class="inline fields">
                                <div class="field">
                                    <label>Time</label>
                                    <input id="admwardtime" name="admwardtime" type="time" required>
                                </div>
                                <div class="field">
                                    <label>Date</label>
                                    <input id="reg_date" name="reg_date" type="date" rdonly required>
                                </div>
                            </div>

                            <div class="field">
                                <label>Medical History</label>
                                <textarea id="medicalhistory" name="medicalhistory" type="text"></textarea>
                            </div>

                            <div class="field">
                                <label>Surgical History</label>
                                <textarea id="surgicalhistory" name="surgicalhistory" type="text"></textarea>
                            </div>

                            <div class="field">
                                <label>Current Medication</label>
                                <textarea id="currentmedication" name="currentmedication" type="text"></textarea>
                            </div>
                        </div>


                        <div class="ui segments">
                            <div class="ui secondary segment">ALLERGIES</div>
                            <div class="ui segment">
                                <table class="table table-sm table-hover">
                                    <tbody>
                                        <tr>
                                            <td><input type="checkbox" id="allergydrugs" name="allergydrugs" value="1"></td>
                                            <td><label for="allergydrugs">Drugs</label></td>
                                            <td><textarea id="drugs_remarks" name="drugs_remarks" type="text"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergyfood" name="allergyfood" value="1"></td>
                                            <td><label for="allergyfood">Food</label></td>
                                            <td><textarea id="food_remarks" name="food_remarks" type="text"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergyothers" name="allergyothers" value="1"></td>
                                            <td><label for="allergyothers">Others</label></td>
                                            <td><textarea id="others_remarks" name="others_remarks" type="text"></textarea></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="eight wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">CONDITION ON ADMISSION</div>
                        <div class="ui segment">
                        <div class="ui grid">
                            <div class="sixteen wide column ">
                                <div class="ui segments">
                                    <div class="ui secondary segment">VITAL SIGN</div>
                                    <div class="ui segment">

                                        <div class="ui grid">
                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Temperature</label>
                                                <div class="ui right labeled input">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_temperature" name="vs_temperature">
                                                  <div class="ui basic label mylabel">°C</div>
                                                </div>
                                            </div>

                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Pulse Rate</label>
                                                <div class="ui right labeled input">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_pulse" name="vs_pulse">
                                                  <div class="ui basic label mylabel">bpm</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ui grid">
                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Respiration</label>
                                                <div class="ui right labeled input">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_respiration" name="vs_respiration">
                                                  <div class="ui basic label mylabel">/minute</div>
                                                </div>
                                            </div>

                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Blood Pressure</label>
                                                <div class="ui right labeled input">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_bp_sys1" name="vs_bp_sys1" style="width:25%">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_bp_dias2" name="vs_bp_dias2" style="width:25%">
                                                  <div class="ui basic label mylabel">mmHg</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ui grid">
                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Height</label>
                                                <div class="ui right labeled input">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="height" name="vs_height">
                                                  <div class="ui basic label mylabel">CM</div>
                                                </div>
                                            </div>

                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Weight</label>
                                                <div class="ui right labeled input">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="weight" name="vs_weight">
                                                  <div class="ui basic label mylabel">KG</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ui grid">
                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                <label>Pain Score</label>
                                                <div class="ui right labeled input">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_painscore" name="vs_painscore">
                                                  <div class="ui basic label mylabel">/10</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="eight wide column ">
                                <div class="ui segments">
                                    <div class="ui secondary segment">MODE OF ADMISSION</div>
                                    <div class="ui segment">
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="moa_walkin" name="moa_walkin" value="1">
                                                 Walk In 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="moa_wheelchair" name="moa_wheelchair" value="1">
                                                 Wheel Chair 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="moa_trolley" name="moa_trolley" value="1">
                                                 Trolley 
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="eight wide column ">
                                <div class="ui segments">
                                    <div class="ui secondary segment">LEVEL OF CONSCIOUSNESS</div>
                                    <div class="ui segment">
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="loc_conscious" name="loc_conscious" value="1">
                                                 Conscious 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="loc_semiconscious" name="loc_semiconscious" value="1">
                                                Semi Conscious 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="loc_unconscious" name="loc_unconscious" value="1">
                                                 Unconscious 
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="eight wide column ">
                                <div class="ui segments">
                                    <div class="ui secondary segment">MENTAL STATUS</div>
                                    <div class="ui segment">
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="ms_orientated" name="ms_orientated" value="1">
                                                 Orientated 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="ms_confused" name="ms_confused" value="1">
                                                 Confused 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="ms_restless" name="ms_restless" value="1">
                                                 Restless 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="ms_aggressive" name="ms_aggressive" value="1">
                                                 Aggressive 
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">ACTIVITIES OF DAILY LIVING</div>
                        <div class="ui segment">
                            @include('nursing_aodl')
                        </div>
                    </div>
                </div>

                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">ADDITIONAL NOTES</div>
                        <div class="ui segment" id="jqGridAddNotesTriage_c">
                            <table id="jqGridAddNotesTriage" class="table table-striped"></table>
                            <div id="jqGridPagerAddNotesTriage"></div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
      </div>
    </div>
</div>
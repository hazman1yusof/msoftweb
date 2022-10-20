<!-- <div class="col-md-3" id="docnote_date_tbl_sticky" style="display: none;position: absolute;
    padding: 0 0 0 0;
    top: 98px;
    left: 5px;">
    <div class="panel panel-info">
        <div class="panel-body" style="max-height: 300px;overflow-y: scroll;">
            <table id="docnote_date_tbl" class="ui celled table" style="width: 100%;">
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
</div> -->

<div class="ui column">
    <div class="ui segments" style="position: relative;">
        <div class="ui inverted dimmer" id="loader_doctornote">
           <div class="ui large text loader">Loading</div>
        </div>
        <div class="ui secondary segment bluecloudsegment">
            <div class="ui form" >
                <div class="six wide column">
                    <div class="inline fields" style="margin-bottom: 0px;">
                        <label style="color: rgba(0,0,0,.6);">DOCTOR NOTES</label>
                    </div>
                </div>
            </div>
            <div class="ui small blue icon buttons" id="btn_grp_edit_doctorNote" style="position: absolute;
                        padding: 0 0 0 0;
                        right: 40px;
                        top: 9px;
                        z-index: 2;">
              <button class="ui button" id="new_doctorNote"><span class="fa fa-plus-square-o"></span> New</button>
              <button class="ui button" id="edit_doctorNote"><span class="fa fa-edit fa-lg"></span> Edit</button>
              <button class="ui button" id="save_doctorNote"><span class="fa fa-save fa-lg"></span> Save</button>
              <button class="ui button" id="cancel_doctorNote"><span class="fa fa-ban fa-lg"></span> Cancel</button>
            </div>

            <div class="three wide column" style="position: absolute;
                        left: 10px;
                        top: 60px;
                        overflow-y: auto;
                        max-height: 70vh;">
                <div class="ui segment"> 
                    <div class="field">
                        <div class="ui radio checkbox checked pastcurr">
                            <input type="radio" name="toggle_type" checked="" tabindex="0" class="hidden" id="current" value="current" checked>
                            <label for="current">Current Month</label>
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui radio checkbox pastcurr">
                            <input type="radio" name="toggle_type" tabindex="0" class="hidden" id="past" value="past" >
                            <label for="past">Past History</label>
                        </div>
                    </div>
                </div>
                <table id="docnote_date_tbl" class="ui celled table" style="min-width: 200px; max-height: 60vh;">
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
                <form id="formDoctorNote" class="right floated ui form thirteen wide column">
                    <input id="arrival_date" name="arrival_date" type="hidden">
                    <input id="mrn_doctorNote" name="mrn_doctorNote" type="hidden">
                    <input id="episno_doctorNote" name="episno_doctorNote" type="hidden">
                    <input id="recorddate" name="recorddate" type="hidden">
                    <input id="mrn_doctorNote_past" name="mrn_doctorNote_past" type="hidden">
                    <input id="episno_doctorNote_past" name="episno_doctorNote_past" type="hidden">

                    <div class='ui grid'>

                        <div class="ten wide column">
                            <div class="field">
                                <label>Patient Complaint</label>
                                <input id="remarks" name="remarks" type="text">
                            </div>
                        </div>

                        <div class="six wide column">
                            <div class="inline fields">
                                <div class="field">
                                    <label>Added by</label>
                                    <input id="lastuser" name="lastuser_" type="text" rdonly>
                                </div>
                                <div class="field">
                                    <label>Added Date</label>
                                    <input id="lastupdate" name="lastupdate_" type="text" rdonly>
                                </div>
                            </div>
                        </div>

                        <div class="twelve wide column">
                            <div class="ui segments">
                                <div class="ui secondary segment">CLINICAL NOTE</div>
                                <div class="ui segment">
                                    <div class="field">
                                        <label>History of Presenting Complaint</label>
                                        <textarea id="clinicnote" name="clinicnote"></textarea>
                                    </div>
                                    <div class="field">
                                        <label>Past Medical History</label>
                                        <textarea id="pmh" name="pmh"></textarea>
                                    </div>
                                    <div class="field">
                                        <label>Drug History</label>
                                        <textarea id="drugh" name="drugh"></textarea>
                                    </div>
                                    <div class="field">
                                        <label>Allergy History</label>
                                        <textarea id="allergyh" name="allergyh"></textarea>
                                    </div>
                                    <div class="field">
                                        <label>Social History</label>
                                        <textarea id="socialh" name="socialh"></textarea>
                                    </div>
                                    <div class="field">
                                        <label>Family History</label>
                                        <textarea id="fmh" name="fmh"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="ui segments">
                                <div class="ui secondary segment">Physical Examination</div>
                                <div class="ui segment">
                                    <textarea id="examination" name="examination"></textarea>
                                </div>
                            </div>

                            <div class="ui segments">
                                <div class="ui secondary segment">Diagnosis</div>
                                <div class="ui segment">
                                    <textarea id="diagfinal" name="diagfinal"></textarea>
                                    <div class='field'>
                                        <label>Primary ICD</label>
                                        <div class="ui action input">
                                            <input id="icdcode" name="icdcode" type="text">
                                            <a class="ui icon blue button"><i class="fa fa-ellipsis-h"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ui segments">
                                <div class="ui secondary segment">Plan</div>
                                <div class="ui segment">
                                    <textarea id="plan_" name="plan_"></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="four wide column">
                            <div class="ui segments">
                                <div class="ui secondary segment">Vital Sign</div>
                                <div class="ui segment">
                                    <div class="field">
                                        <label>Height</label>
                                        <div class="ui right labeled input">
                                          <input type="text" onKeyPress="if(this.value.length==6) return false;" id="height" name="height">
                                          <div class="ui basic label mylabel">CM</div>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label>Weight</label>
                                        <div class="ui right labeled input">
                                          <input type="text" onKeyPress="if(this.value.length==6) return false;" id="weight" name="weight">
                                          <div class="ui basic label mylabel">KG</div>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label>Blood Pressure</label>
                                        <div class="ui right labeled input">
                                          <input type="text" onKeyPress="if(this.value.length==6) return false;" id="bp_sys1" name="bp_sys1" style="width:25%">
                                          <input type="text" onKeyPress="if(this.value.length==6) return false;" id="bp_dias2" name="bp_dias2" style="width:25%">
                                          <div class="ui basic label mylabel">mmHg</div>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label>Pulse Rate</label>
                                        <div class="ui right labeled input">
                                            <input type="text" onKeyPress="if(this.value.length==6) return false;" id="pulse" name="pulse">
                                            <div class="ui basic label mylabel">bpm</div>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label>Temperature</label>
                                        <div class="ui right labeled input">
                                          <input type="text" onKeyPress="if(this.value.length==6) return false;" id="temperature" name="temperature">
                                          <div class="ui basic label mylabel">°C</div>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label>Respiration</label>
                                        <div class="ui right labeled input">
                                            <input type="text" onKeyPress="if(this.value.length==6) return false;" id="respiration" name="respiration">
                                            <div class="ui basic label mylabel">/minute</div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        
                            <div class="ui segments">
                                <div class="ui secondary segment">Haemodialysis</div>
                                <div class="ui segment">
                                    <div class="field">
                                        <label>Dry Weight</label>
                                        <div class="ui right labeled input">
                                            <input type="text" onKeyPress="if(this.value.length==6) return false;" id="dry_weight" name="dry_weight" data-validation="required" data-validation-error-msg-required="Please enter Dry Weight." data-validation-error-msg-container="#error-dry_weight">
                                            <div class="ui basic label mylabel">KG</div>
                                        </div>
                                        <div class="error-msg" id="error-dry_weight"></div>
                                    </div>

                                    <div class="field">
                                        <label>Duration of HD</label>
                                        <div class="ui right labeled input">
                                            <input type="text" id="duration_hd" name="duration_hd" data-validation="required" data-validation-error-msg-required="Please enter Duration of HD." data-validation-error-msg-container="#error-duration_hd">
                                            <div class="ui basic label mylabel">HRS</div>
                                        </div>
                                        <div class="error-msg" id="error-duration_hd"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="sixteen wide column" style="display:none" id="addnotes">
                            <div class="ui segments">
                                <div class="ui secondary segment">Additional Notes</div>
                                <div class="ui segment" id="jqGridAddNotes_c">
                                    <table id="jqGridAddNotes" class="table table-striped"></table>
                                    <div id="jqGridPagerAddNotes"></div>
                                </div>
                            </div>
                        </div>

                        <div class="sixteen wide column">
                            <div class="ui segments">
                                <div class="ui secondary segment">Order Entry</div>
                                <div class="ui segment" id="jqGrid_trans_c">
                                    <table id="jqGrid_trans" class="table table-striped"></table>
                                    <div id="jqGrid_transPager"></div>
                                </div>
                            </div>
                        </div>    

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
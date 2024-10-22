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
        <div class="ui secondary segment bluecloudsegment">
            <div class="ui form" >
                <div class="six wide column">
                    <div class="inline fields" style="margin-bottom: 0px;">
                        <label style="color: rgba(0,0,0,.6);">DOCTOR NOTES</label> 
                        <div class="field">
                          <div class="ui radio checkbox checked pastcurr">
                            <input type="radio" name="toggle_type" checked="" tabindex="0" class="hidden" id="current" value="current" checked>
                            <label>Current</label>
                          </div>
                        </div>
                        <div class="field">
                          <div class="ui radio checkbox pastcurr">
                            <input type="radio" name="toggle_type" tabindex="0" class="hidden" id="past" value="past" >
                            <label>Past History</label>
                          </div>
                        </div>
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
                        top: 60px;">
                <table id="docnote_date_tbl" class="ui celled table" style="min-width: 200px;">
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
                    <input id="mrn_doctorNote" name="mrn_doctorNote" type="hidden">
                    <input id="episno_doctorNote" name="episno_doctorNote" type="hidden">
                    <input id="recorddate" name="recorddate" type="hidden">
                    <input id="mrn_doctorNote_past" name="mrn_doctorNote_past" type="hidden">
                    <input id="episno_doctorNote_past" name="episno_doctorNote_past" type="hidden">

                    <div class='ui grid'>

                        <div class="ten wide column">
                            <div class="field">
                                <label>Patient Complaint</label>
                                <input id="remarks" name="remarks" type="text" data-validation="required" data-validation-error-msg-required="Please enter Patient Complaint">
                            </div>
                        </div>

                        <div class="six wide column">
                            <div class="inline fields">
                                <div class="field">
                                    <label>Added by</label>
                                    <input id="adduser" name="adduser" type="text" rdonly>
                                </div>
                                <div class="field">
                                    <label>Added Date</label>
                                    <input id="adddate" name="adddate" type="text" rdonly>
                                </div>
                            </div>
                        </div>

                        <div class="twelve wide column">
                            <div class="ui segments">
                                <div class="ui secondary segment">CLINICAL NOTE</div>
                                <div class="ui segment">
                                    <div class="field">
                                        <label>History of Presenting Complaint</label>
                                        <textarea id="clinicnote" name="clinicnote" type="text" rows="12"></textarea>
                                    </div>
                                    <div class="field">
                                        <label>Past Medical History</label>
                                        <textarea id="pmh" name="pmh" type="text" rows="3"></textarea>
                                    </div>
                                    <div class="field">
                                        <label>Drug History</label>
                                        <textarea id="drugh" name="drugh" type="text" rows="3"></textarea>
                                    </div>
                                    <div class="field">
                                        <label>Allergy History</label>
                                        <textarea id="allergyh" name="allergyh" type="text" rows="3"></textarea>
                                    </div>
                                    <div class="field">
                                        <label>Social History</label>
                                        <textarea id="socialh" name="socialh" type="text" rows="3"></textarea>
                                    </div>
                                    <div class="field">
                                        <label>Family History</label>
                                        <textarea id="fmh" name="fmh" type="text" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="ui segments">
                                <div class="ui secondary segment">FOLLOW UP</div>
                                <div class="ui grid segment">

                                    <div class="eight wide field">
                                        <label>Time</label>
                                        <input id="followuptime" name="followuptime" type="time">
                                    </div>
                                    <div class="eight wide field">
                                        <label>Date and Time</label>
                                        <input id="followupdate" name="followupdate" type="date">
                                    </div>

                                </div>
                            </div>

                            <div class="ui segments">
                                <div class="ui secondary segment">Physical Examination</div>
                                <div class="ui segment">
                                    <textarea id="examination" name="examination" type="text" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="ui segments">
                                <div class="ui secondary segment">Diagnosis</div>
                                <div class="ui segment">
                                    <textarea id="diagfinal" name="diagfinal" type="text" rows="3"></textarea>
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
                                    <textarea id="plan_" name="plan_" type="text" rows="3"></textarea>
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
                                          <div class="ui basic label">CM</div>
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
                                        <label>BMI</label>
                                        <input type="text" onKeyPress="if(this.value.length==6) return false;" id="bmi" name="bmi" rdonly>
                                    </div>

                                    <div class="field">
                                        <label>Blood Pressure</label>
                                        <div class="ui right labeled input">
                                          <input type="text" onKeyPress="if(this.value.length==6) return false;" id="bp_sys1" name="bp_sys1" style="width:25%">
                                          <input type="text" onKeyPress="if(this.value.length==6) return false;" id="bp_dias2" name="bp_dias2" style="width:25%">
                                          <div class="ui basic label">mmHg</div>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label>Pulse Rate</label>
                                        <input type="text" onKeyPress="if(this.value.length==6) return false;" id="pulse" name="pulse">
                                    </div>

                                    <div class="field">
                                        <label>Temperature</label>
                                        <div class="ui right labeled input">
                                          <input type="text" onKeyPress="if(this.value.length==6) return false;" id="temperature" name="temperature">
                                          <div class="ui basic label">°C</div>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label>Respiration</label>
                                        <input type="text" onKeyPress="if(this.value.length==6) return false;" id="respiration" name="respiration">
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
        
        <div class="ui segments">
            <div class="ui secondary segment">REQUEST FOR</div>
            <div class="ui segment">
                <div class="ui top attached tabular menu">
                    <a class="item active" data-tab="tab-otbook">Ward / OT</a>
                    <!-- <a class="item" data-tab="tab-rad">Radiology</a>
                    <a class="item" data-tab="tab-physio">Physiotherapy</a>
                    <a class="item" data-tab="tab-dressing">Dressing</a> -->
                </div>
                <div class="ui bottom attached tab segment active" data-tab="tab-otbook">
                    <div class="ui segments" style="position: relative;">
                        <div class="ui secondary segment bluecloudsegment">
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
                        </div>
                        <div class="ui segment">
                            <div class="ui grid">
                                <form id="formOTBook" class="floated ui form sixteen wide column">
                                    <div class='ui grid'>
                                        <div class="ui form">
                                            <div class="six wide column">
                                                <div class="inline field">
                                                    <label>Date for OP</label>
                                                    <input id="op_date" name="op_date" type="date">
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <label for="adm_type">Type of Admission</label>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="adm_type" checked="" tabindex="0" class="hidden" value="DC">
                                                            <label>Day Case</label>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="adm_type" tabindex="0" class="hidden" value="IP">
                                                            <label>In Patient</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <label for="anaesthetist">Anaesthetist</label>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="anaesthetist" checked="" tabindex="0" class="hidden" value="1">
                                                            <label>Required</label>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="anaesthetist" tabindex="0" class="hidden" value="0">
                                                            <label>Not Required</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline field">
                                                    <label>Company representative number for medication (if any)</label>
                                                    <input id="comp_rep_no" name="comp_rep_no" type="text">
                                                </div>
                                                
                                                <div class="inline field">
                                                    <label>Special remarks / instructions for medication or any related to case</label>
                                                    <textarea id="ot_remarks" name="ot_remarks" type="text" rows="5"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="ui bottom attached tab segment" data-tab="tab-rad">
                    <div class="ui top attached tabular menu">
                        <a class="item active" data-tab="tab-radClinic">Clinical Data</a>
                        <a class="item" data-tab="tab-mri">Checklist MRI</a>
                    </div>
                    <div class="ui bottom attached tab segment active" data-tab="tab-radClinic">
                    
                    </div>
                    <div class="ui bottom attached tab segment" data-tab="tab-mri">
                    
                    </div>
                </div>
                <div class="ui bottom attached tab segment" data-tab="tab-physio">
                </div>
                <div class="ui bottom attached tab segment" data-tab="tab-dressing">
                </div> -->
            </div>
        </div>
    </div>
</div>
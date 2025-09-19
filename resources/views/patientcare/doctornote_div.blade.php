<!-- <div class="col-md-3" id="docnote_date_tbl_sticky" style="display: none; position: absolute;
    padding: 0 0 0 0;
    top: 98px;
    left: 5px;">
    <div class="panel panel-info">
        <div class="panel-body" style="max-height: 300px; overflow-y: scroll;">
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
            <div class="ui form">
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
                                <input type="radio" name="toggle_type" tabindex="0" class="hidden" id="past" value="past">
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
                <button class="ui button" id="new_doctorNote"><span class="fa fa-plus-square-o"></span>New</button>
                <button class="ui button" id="edit_doctorNote"><span class="fa fa-edit fa-lg"></span>Edit</button>
                <button class="ui button" id="save_doctorNote"><span class="fa fa-save fa-lg"></span>Save</button>
                <button class="ui button" id="cancel_doctorNote"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            </div>
        </div>
        <div class="ui segment">
            <div class="three wide column" style="position: absolute;
                        left: 10px;
                        top: 30px;">
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
            <div class="ui grid">
                <form id="formDoctorNote" class="right floated ui form thirteen wide column">
                    <input id="mrn_doctorNote" name="mrn_doctorNote" type="hidden">
                    <input id="episno_doctorNote" name="episno_doctorNote" type="hidden">
                    <input id="age_doctorNote" name="age_doctorNote" type="hidden">
                    <!-- <input id="recorddate" name="recorddate" type="hidden"> -->
                    <input id="recorddate_doctorNote" name="recorddate_doctorNote" type="hidden">
                    <input id="mrn_doctorNote_past" name="mrn_doctorNote_past" type="hidden">
                    <input id="episno_doctorNote_past" name="episno_doctorNote_past" type="hidden">
                    <input id="ptname_doctorNote" name="ptname_doctorNote" type="hidden">
                    <input id="preg_doctorNote" name="preg_doctorNote" type="hidden">
                    <input id="ic_doctorNote" name="ic_doctorNote" type="hidden">
                    <input id="doctorname_doctorNote" name="doctorname_doctorNote" type="hidden">
                    
                    <div class='ui grid'>
                        <div class="eight wide column">
                            <div class="field">
                                <label>Chief Complaint</label>
                                <input id="complain" name="complain" type="text" data-validation="required" data-validation-error-msg-required="Please enter Chief Complaint">
                            </div>
                        </div>
                        
                        <div class="seven wide column">
                            <div class="inline fields">
                                <div class="field">
                                    <label>Added Date</label>
                                    <input id="adddate" name="adddate" type="text" rdonly>
                                </div>
                                <div class="field">
                                    <label>Added Time</label>
                                    <input id="recordtime" name="recordtime" type="time">
                                </div>
                                <div class="field">
                                    <label>Added by</label>
                                    <input id="adduser" name="adduser" type="text" rdonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="twelve wide column">
                            <div class="ui segments">
                                <div class="ui secondary segment">CLINICAL NOTE</div>
                                <div class="ui segment">
                                    <div class="ui grid">
                                        <div class="eight wide column">
                                            <div class="field">
                                                <label>History of Present Illness</label>
                                                <textarea id="clinicnote" name="clinicnote" type="text" rows="12"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="eight wide column">
                                            <div class="field">
                                                <label>Past Psychiatry History</label>
                                                <textarea id="psychiatryh" name="psychiatryh" type="text" rows="12"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="eight wide column">
                                            <div class="field">
                                                <label>Previous Medical/Surgery History</label>
                                                <textarea id="pmh" name="pmh" type="text" rows="3"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="eight wide column">
                                            <div class="field">
                                                <label>Family History</label>
                                                <textarea id="fmh" name="fmh" type="text" rows="3"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="eight wide column">
                                            <div class="field">
                                                <label>Personal History</label>
                                                <textarea id="personalh" name="personalh" type="text" rows="3"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="eight wide column">
                                            <div class="field">
                                                <label>Drug History</label>
                                                <textarea id="drugh" name="drugh" type="text" rows="3"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="eight wide column">
                                            <div class="field">
                                                <label>Allergy History</label>
                                                <textarea id="allergyh" name="allergyh" type="text" rows="3"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="eight wide column">
                                            <div class="field">
                                                <label>Premorbid History</label>
                                                <textarea id="socialh" name="socialh" type="text" rows="3"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="eight wide column">
                                            <div class="field">
                                                <label>General Appearance & Behaviour</label>
                                                <textarea id="genappear" name="genappear" type="text" rows="3"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="eight wide column">
                                            <div class="field">
                                                <label>Speech</label>
                                                <textarea id="speech" name="speech" type="text" rows="3"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="eight wide column">
                                            <div class="field">
                                                <label>Mood & Affect</label>
                                                <textarea id="moodaffect" name="moodaffect" type="text" rows="3"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="eight wide column">
                                            <div class="field">
                                                <label>Perception</label>
                                                <textarea id="perception" name="perception" type="text" rows="3"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="eight wide column">
                                            <div class="field">
                                                <label>Thinking</label>
                                                <textarea id="thinking" name="thinking" type="text" rows="3"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="eight wide column">
                                            <div class="field">
                                                <label>Cognitive Function</label>
                                                <textarea id="cognitivefunc" name="cognitivefunc" type="text" rows="3"></textarea>
                                            </div>
                                        </div>
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
                                    <!-- <textarea id="examination" name="examination" type="text" rows="3"></textarea> -->
                                    <div class="ui four cards">
                                        <a class="ui card bodydia_doctornote" data-type='DOCNOTE_BF'>
                                            <div class="image">
                                                <img src="{{ asset('img/bodydia1.png') }}">
                                            </div>
                                        </a>
                                        <a class="ui card bodydia_doctornote" data-type='DOCNOTE_BR'>
                                            <div class="image">
                                                <img src="{{ asset('img/bodydia2.png') }}">
                                            </div>
                                        </a>
                                        <a class="ui card bodydia_doctornote" data-type='DOCNOTE_BL'>
                                            <div class="image">
                                                <img src="{{ asset('img/bodydia3.png') }}">
                                            </div>
                                        </a>
                                        <a class="ui card bodydia_doctornote" data-type='DOCNOTE_BB'>
                                            <div class="image">
                                                <img src="{{ asset('img/bodydia4.png') }}">
                                            </div>
                                        </a>
                                    </div>
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
                                <div class="ui secondary segment">Aetiology</div>
                                <div class="ui segment">
                                    <textarea id="aetiology" name="aetiology" type="text" rows="3"></textarea>
                                </div>
                            </div>
                            
                            <div class="ui segments">
                                <div class="ui secondary segment">Investigations</div>
                                <div class="ui segment">
                                    <textarea id="investigate" name="investigate" type="text" rows="3"></textarea>
                                </div>
                            </div>
                            
                            <div class="ui segments">
                                <div class="ui secondary segment">Treatment</div>
                                <div class="ui segment">
                                    <textarea id="treatment" name="treatment" type="text" rows="3"></textarea>
                                </div>
                            </div>
                            
                            <div class="ui segments">
                                <div class="ui secondary segment">Prognosis</div>
                                <div class="ui segment">
                                    <textarea id="prognosis" name="prognosis" type="text" rows="3"></textarea>
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
                                        <label>BP</label>
                                        <div class="ui right labeled input">
                                            <input type="text" onKeyPress="if(this.value.length==6) return false;" id="bp_sys1" name="bp_sys1" style="width:25%">
                                            <input type="text" onKeyPress="if(this.value.length==6) return false;" id="bp_dias2" name="bp_dias2" style="width:25%">
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
                                        <label>Pulse</label>
                                        <div class="ui right labeled input">
                                            <input type="text" onKeyPress="if(this.value.length==6) return false;" id="pulse" name="pulse">
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
                                            <input type="text" onKeyPress="if(this.value.length==6) return false;" id="temperature" name="temperature">
                                            <div class="ui basic label">°C</div>
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
                                        <label>RR</label>
                                        <div class="ui right labeled input">
                                            <input type="text" onKeyPress="if(this.value.length==6) return false;" id="respiration" name="respiration">
                                            <div class="ui basic label">Min</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="sixteen wide column" style="display: none;" id="addnotes">
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
            <div id="doctor_requestFor" class="ui segment">
                <div class="ui top attached tabular menu">
                    <a class="item active" data-tab="otbook">Ward / OT</a>
                    <a class="item" data-tab="rad">Radiology</a>
                    <a class="item" data-tab="physio">Rehab</a>
                    <a class="item" data-tab="dressing">Dressing</a>
                </div>
                
                <div class="ui bottom attached tab raised segment active" data-tab="otbook">
                    <div class="ui segments" style="position: relative;">
                        <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
                            <div class="ui small blue icon buttons" id="btn_grp_edit_otbook" style="position: absolute;
                                padding: 0 0 0 0;
                                right: 40px;
                                top: 9px;
                                z-index: 2;">
                                <button class="ui button" id="new_otbook"><span class="fa fa-plus-square-o"></span>New</button>
                                <button class="ui button" id="edit_otbook"><span class="fa fa-edit fa-lg"></span>Edit</button>
                                <button class="ui button" id="save_otbook"><span class="fa fa-save fa-lg"></span>Save</button>
                                <button class="ui button" id="cancel_otbook"><span class="fa fa-ban fa-lg"></span>Cancel</button>
                                <button class="ui button" id="otbook_chart"><span class="fa fa-print fa-lg"></span>Print</button>
                            </div>
                        </div>
                        <div class="ui segment">
                            <div class="ui grid">
                                <form id="formOTBook" class="floated ui form sixteen wide column">
                                    <div class='ui grid' style="padding: 15px 30px;">
                                        @include('patientcare.otbook_vitalsign')
                                        
                                        <div class="sixteen wide column">
                                            <div class="ui segments">
                                                <!-- <div class="ui secondary segment">Ward / OT</div> -->
                                                <div class="ui segment">
                                                    <div class="ui grid">
                                                        <div class="sixteen wide column centered grid" style="padding: 14px 14px 0px 150px;">
                                                            <div class="inline field">
                                                                <label>iPesakit</label>
                                                                <input type="text" id="ot_iPesakit" name="iPesakit">
                                                            </div>
                                                            
                                                            <div class="inline fields">
                                                                <label for="req_type">Type</label>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" id="type_ward" name="req_type" value="WARD">
                                                                        <label for="type_ward">Ward</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" id="type_ot" name="req_type" value="OT">
                                                                        <label for="type_ot">OT</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="inline field">
                                                                <label>Date for OP</label>
                                                                <input id="op_date" name="op_date" type="date">
                                                            </div>
                                                            
                                                            <div class="inline field">
                                                                <label>Type of Operation / Procedure</label>
                                                                <input id="oper_type" name="oper_type" type="text" style="width: 350px;">
                                                            </div>
                                                            
                                                            <div class="inline fields">
                                                                <label for="adm_type">Type of Admission</label>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="adm_type" value="DC" id="adm_dc">
                                                                        <label for="adm_dc">Day Case</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="adm_type" value="IP" id="adm_ip">
                                                                        <label for="adm_ip">In Patient</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="inline fields">
                                                                <label for="anaesthetist">Anaesthetist</label>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="anaesthetist" value="1" id="anas_req">
                                                                        <label for="anas_req">Required</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="anaesthetist" value="0" id="anas_notreq">
                                                                        <label for="anas_notreq">Not Required</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="four wide column" style="padding: 0px 14px 14px 150px;">
                                                            <div class="field">
                                                                <label>Diagnosis</label>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="twelve wide column" style="padding-top: 0px;">
                                                            <div class="field nine wide column">
                                                                <textarea id="ot_diagnosis" name="ot_diagnosis" type="text" rows="5"></textarea>
                                                                
                                                                <div class="inline field" style="padding-top: 15px;">
                                                                    <label>Diagnosed By</label>
                                                                    <input id="ot_diagnosedby" name="ot_diagnosedby" type="text" style="width: 320px; text-transform: uppercase;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="six wide column" style="padding: 0px 14px 14px 150px;">
                                                            <div class="field">
                                                                <label>Special remarks / instructions for medication or any related to case</label>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="ten wide column" style="padding-top: 0px;">
                                                            <div class="field eight wide column">
                                                                <textarea id="ot_remarks" name="ot_remarks" type="text" rows="5"></textarea>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="sixteen wide column centered grid" style="padding: 0px 14px 14px 150px;">
                                                            <div class="inline field">
                                                                <label>Doctor's Name</label>
                                                                <input id="ot_doctorname" name="ot_doctorname" type="text" style="width: 350px; text-transform: uppercase;">
                                                            </div>
                                                            
                                                            <div class="inline field">
                                                                <label>Entered By</label>
                                                                <input id="ot_lastuser" name="ot_lastuser" type="text" style="width: 350px; text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="sixteen wide column" id="Bed_div" style="display: none;">
                                            <div class="ui segments">
                                                <div class="ui secondary segment">BED</div>
                                                <div class="ui segment">
                                                    <div class="ui grid">
                                                    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="sixteen wide column" id="OT_div" style="display: none;">
                                            <div class="ui segments">
                                                <div class="ui secondary segment">OT</div>
                                                <div class="ui segment">
                                                    <div class="ui grid">
                                                    
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
                
                <div id="doctor_radiology" class="ui bottom attached tab raised segment" data-tab="rad">
                    <div class="ui top attached tabular menu">
                        <a class="item active" data-tab="radClinic">Radiology Form</a>
                        <a class="item" data-tab="mri">Checklist MRI</a>
                        <a class="item" data-tab="preContrast">Pre-Contrast Questionnaire</a>
                        <a class="item" data-tab="consent">Consent Form</a>
                    </div>
                    
                    <div class="ui bottom attached tab raised segment active" data-tab="radClinic">
                        <div class="ui segments" style="position: relative;">
                            <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
                                <div class="ui small blue icon buttons" id="btn_grp_edit_radClinic" style="position: absolute;
                                    padding: 0 0 0 0;
                                    right: 40px;
                                    top: 9px;
                                    z-index: 2;">
                                    <button class="ui button" id="new_radClinic"><span class="fa fa-plus-square-o"></span>New</button>
                                    <button class="ui button" id="edit_radClinic"><span class="fa fa-edit fa-lg"></span>Edit</button>
                                    <button class="ui button" id="save_radClinic"><span class="fa fa-save fa-lg"></span>Save</button>
                                    <button class="ui button" id="cancel_radClinic"><span class="fa fa-ban fa-lg"></span>Cancel</button>
                                    <button class="ui button" id="radClinic_chart"><span class="fa fa-print fa-lg"></span>Print</button>
                                </div>
                            </div>
                            <div class="ui segment">
                                <div class="ui grid">
                                    <form id="formRadClinic" class="floated ui form sixteen wide column">
                                        <div class='ui grid' style="padding: 15px 30px;">
                                            <div class="sixteen wide column centered grid" style="padding: 14px 14px 0px 150px;">
                                                <div class="inline field">
                                                    <label>iPesakit</label>
                                                    <input type="text" id="rad_iPesakit" name="iPesakit">
                                                </div>
                                                
                                                <div class="inline field">
                                                    <label>Weight</label>
                                                    <div class="ui right labeled input">
                                                        <input type="text" onKeyPress="if(this.value.length==6) return false;" id="rad_weight" name="rad_weight">
                                                        <div class="ui basic label">KG</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <label for="pt_condition">Patient Condition</label>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="pt_condition" value="walking" id="ptcon_walking">
                                                            <label for="ptcon_walking">Walking</label>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="pt_condition" value="wheelchair" id="ptcon_wheelchair">
                                                            <label for="ptcon_wheelchair">On Wheelchair</label>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="pt_condition" value="strecher" id="ptcon_strecher">
                                                            <label for="ptcon_strecher">Strecher</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <label for="rad_pregnant">Pregnant</label>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" id="pregnant" name="rad_pregnant" value="1">
                                                            <label for="pregnant">Yes</label>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" id="not_pregnant" name="rad_pregnant" value="0">
                                                            <label for="not_pregnant">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline field">
                                                    <label>LMP</label>
                                                    <input type="date" id="rad_LMP" name="LMP">
                                                </div>
                                            </div>
                                            
                                            <div class="four wide column" style="padding: 14px 14px 14px 150px;">
                                                <div class="field">
                                                    <label>Asthma/Allergy</label>
                                                </div>
                                            </div>
                                            
                                            <div class="twelve wide column">
                                                <div class="field eight wide column">
                                                    <textarea id="rad_allergy" name="rad_allergy" type="text" rows="5"></textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="three wide column" style="padding: 0px 14px 14px 150px;">
                                                <div class="field">
                                                    <label>Examination</label>
                                                </div>
                                            </div>
                                            
                                            <div class="thirteen wide column" style="padding: 0px 14px 14px 30px;">
                                                <table class="ui striped table">
                                                    <tbody>
                                                        <tr>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="xray" name="xray" value="1"></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><label for="xray">X-ray</label></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input id="xray_date" name="xray_date" type="date"></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="xray_remark" name="xray_remark" type="text" rows="5"></textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="mri" name="mri" value="1"></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><label for="mri">M.R.I</label></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input id="mri_date" name="mri_date" type="date"></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="mri_remark" name="mri_remark" type="text" rows="5"></textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="angio" name="angio" value="1"></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><label for="angio">Angio</label></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input id="angio_date" name="angio_date" type="date"></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="angio_remark" name="angio_remark" type="text" rows="5"></textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="ultrasound" name="ultrasound" value="1"></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><label for="ultrasound">Ultrasound</label></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input id="ultrasound_date" name="ultrasound_date" type="date"></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="ultrasound_remark" name="ultrasound_remark" type="text" rows="5"></textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="ct" name="ct" value="1"></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><label for="ct">C.T</label></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input id="ct_date" name="ct_date" type="date"></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="ct_remark" name="ct_remark" type="text" rows="5"></textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="fluroscopy" name="fluroscopy" value="1"></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><label for="fluroscopy">Fluroscopy</label></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input id="fluroscopy_date" name="fluroscopy_date" type="date"></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="fluroscopy_remark" name="fluroscopy_remark" type="text" rows="5"></textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="mammogram" name="mammogram" value="1"></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><label for="mammogram">Mammogram</label></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input id="mammogram_date" name="mammogram_date" type="date"></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="mammogram_remark" name="mammogram_remark" type="text" rows="5"></textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="bmd" name="bmd" value="1"></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><label for="bmd">Bone Densitometry (BMD)</label></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input id="bmd_date" name="bmd_date" type="date"></td>
                                                            <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="bmd_remark" name="bmd_remark" type="text" rows="5"></textarea></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                            <div class="four wide column" style="padding: 0px 14px 14px 150px;">
                                                <div class="field">
                                                    <label>Clinical Data</label>
                                                </div>
                                            </div>
                                            
                                            <div class="twelve wide column" style="padding-top: 0px;">
                                                <div class="field nine wide column">
                                                    <textarea id="clinicaldata" name="clinicaldata" type="text" rows="5"></textarea>
                                                    
                                                    <div class="inline field" style="padding-top: 15px;">
                                                        <label>Doctor's Name</label>
                                                        <input id="radClinic_doctorname" name="radClinic_doctorname" type="text" style="width: 320px; text-transform: uppercase;">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="four wide column" style="padding: 0px 0px 14px 150px;">
                                                <div class="field">
                                                    <label>Radiology Note</label>
                                                </div>
                                            </div>
                                            
                                            <div class="twelve wide column" style="padding: 0px 14px 14px 30px;">
                                                <div class="field nine wide column">
                                                    <textarea id="rad_note" name="rad_note" type="text" rows="5"></textarea>
                                                    
                                                    <div class="inline field" style="padding-top: 15px;">
                                                        <label>Radiologist's Name</label>
                                                        <input id="radClinic_radiologist" name="radClinic_radiologist" type="text" style="width: 300px; text-transform: uppercase;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ui bottom attached tab raised segment" data-tab="mri">
                        <div class="ui segments" style="position: relative;">
                            <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
                                <div class="ui small blue icon buttons" id="btn_grp_edit_mri" style="position: absolute;
                                    padding: 0 0 0 0;
                                    right: 40px;
                                    top: 9px;
                                    z-index: 2;">
                                    <button class="ui button" id="new_mri"><span class="fa fa-plus-square-o"></span>New</button>
                                    <button class="ui button" id="edit_mri"><span class="fa fa-edit fa-lg"></span>Edit</button>
                                    <button class="ui button" id="save_mri"><span class="fa fa-save fa-lg"></span>Save</button>
                                    <!-- <button class="ui button" id="accept_mri"><span class="fa fa-check fa-lg"></span>Accept</button> -->
                                    <button class="ui button" id="cancel_mri"><span class="fa fa-ban fa-lg"></span>Cancel</button>
                                    <button class="ui button" id="mri_chart"><span class="fa fa-print fa-lg"></span>Print</button>
                                </div>
                            </div>
                            <div class="ui segment">
                                <div class="ui grid">
                                    <form id="formMRI" class="floated ui form sixteen wide column">
                                        <div class='ui grid' style="padding: 15px 30px;">
                                            <div class="sixteen wide column centered grid" style="padding: 14px 14px 0px 150px;">
                                                <div class="inline fields">
                                                    <label>Weight</label>
                                                    <div class="field">
                                                        <div class="ui right labeled input">
                                                            <input type="text" onKeyPress="if(this.value.length==6) return false;" id="mri_weight" name="mri_weight">
                                                            <div class="ui basic label">KG</div>
                                                        </div>
                                                    </div>
                                                    
                                                    <label>Date</label>
                                                    <div class="field">
                                                        <input id="mri_entereddate" name="mri_entereddate" type="date">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <table class="ui striped table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" colspan="2">
                                                            Please indicate in appropriate column, whether or not the patient has the items indicated.<br>
                                                            <i>Sila tandakan pada kotak yang berkenaan jika pesakit mempunyai item tersebut.</i>
                                                        </th>
                                                        <th scope="col"></th>
                                                        <th scope="col"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">1</th>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            Cardiac pacemaker (<i>Penyelaras denyutan jantung</i>)
                                                        </td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="cardiacpacemaker" value="1" id="cp_yes">
                                                                        <label for="cp_yes">Yes</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="cardiacpacemaker" value="0" id="cp_no">
                                                                        <label for="cp_no">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">2</th>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            Prosthetics valve, if yes, please specify.<br><i>Injap jantung palsu, jika ada nyatakan.</i>
                                                        </td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="pros_valve" value="1" id="pv_yes">
                                                                        <label for="pv_yes">Yes</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="pros_valve" value="0" id="pv_no">
                                                                        <label for="pv_no">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="prosvalve_rmk" name="prosvalve_rmk" type="text" rows="5"></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">3</th>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            Known intraocular foreign body or history of eye injury.<br><i>Intraocular bendasing atau sejarah cedera pada mata.</i>
                                                        </td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="intraocular" value="1" id="io_yes">
                                                                        <label for="io_yes">Yes</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="intraocular" value="0" id="io_no">
                                                                        <label for="io_no">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">4</th>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            Cochlear implants (ENT.)<br><i>Implant koklea (ENT).</i>
                                                        </td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="cochlear_imp" value="1" id="ci_yes">
                                                                        <label for="ci_yes">Yes</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="cochlear_imp" value="0" id="ci_no">
                                                                        <label for="ci_no">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">5</th>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            Neurotransmitter (brain/spinal cord pacemaker).<br><i>Neurotransmitter (otak/perentak saraf tunjang).</i>
                                                        </td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="neurotransm" value="1" id="nt_yes">
                                                                        <label for="nt_yes">Yes</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="neurotransm" value="0" id="nt_no">
                                                                        <label for="nt_no">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">6</th>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            Bone growth stimulators.<br><i>Perangsang tumbesaran tulang.</i>
                                                        </td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="bonegrowth" value="1" id="bg_yes">
                                                                        <label for="bg_yes">Yes</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="bonegrowth" value="0" id="bg_no">
                                                                        <label for="bg_no">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">7</th>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            Implantable drug infusion pumps.<br><i>Implant pam infuse ubat.</i>
                                                        </td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="druginfuse" value="1" id="di_yes">
                                                                        <label for="di_yes">Yes</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="druginfuse" value="0" id="di_no">
                                                                        <label for="di_no">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">8</th>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            Cerebral surgical clips/wire.<br><i>Klip serebral.</i>
                                                        </td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="surg_clips" value="1" id="sc_yes">
                                                                        <label for="sc_yes">Yes</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="surg_clips" value="0" id="sc_no">
                                                                        <label for="sc_no">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">9</th>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            Joint/limb prosthesis of metallic ferromagnetic materials.<br><i>Anggota badan palsu dari bahan feromagnetic.</i>
                                                        </td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="jointlimb_pros" value="1" id="jl_yes">
                                                                        <label for="jl_yes">Yes</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="jointlimb_pros" value="0" id="jl_no">
                                                                        <label for="jl_no">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">10</th>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            Shrapnel or bullet fragment (any of the body).<br><i>Serpihan atau peluru.</i>
                                                        </td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="shrapnel" value="1" id="shr_yes">
                                                                        <label for="shr_yes">Yes</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="shrapnel" value="0" id="shr_no">
                                                                        <label for="shr_no">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">11</th>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            Any operation in the last 3 month? If yes please specify.<br><i>Pembedahan dalam masa 3 bulan, jika ada nyatakan.</i>
                                                        </td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="oper_3mth" value="1" id="oper_yes">
                                                                        <label for="oper_yes">Yes</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="oper_3mth" value="0" id="oper_no">
                                                                        <label for="oper_no">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="oper3mth_remark" name="oper3mth_remark" type="text" rows="5"></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">12</th>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            Any previous MRI examination?<br><i>Pemeriksaan MRI sebelum ini?</i>
                                                        </td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="prev_mri" value="1" id="mri_yes">
                                                                        <label for="mri_yes">Yes</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="prev_mri" value="0" id="mri_no">
                                                                        <label for="mri_no">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">13</th>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            Have you ever experienced claustrophobia?<br><i>Anda mempunyai klaustrofobia?</i>
                                                        </td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="claustrophobia" value="1" id="claus_yes">
                                                                        <label for="claus_yes">Yes</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="claustrophobia" value="0" id="claus_no">
                                                                        <label for="claus_no">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">14</th>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            Dental implant (held in place by magnet).<br><i>Implant dental, mempunyai magnet.</i>
                                                        </td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="dental_imp" value="1" id="dental_yes">
                                                                        <label for="dental_yes">Yes</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="dental_imp" value="0" id="dental_no">
                                                                        <label for="dental_no">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">15</th>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            Any implanted ferromagnetic materials (susuk or etc).<br><i>Mempunyai bahan-bahan ferromagnetic seperti susuk.</i>
                                                        </td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="frmgnetic_imp" value="1" id="fmg_yes">
                                                                        <label for="fmg_yes">Yes</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="frmgnetic_imp" value="0" id="fmg_no">
                                                                        <label for="fmg_no">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">16</th>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            Pregnancy (1st trimester).<br><i>Mengandung trimester pertama.</i>
                                                        </td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="pregnancy" value="1" id="preg_yes">
                                                                        <label for="preg_yes">Yes</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="pregnancy" value="0" id="preg_no">
                                                                        <label for="preg_no">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">17</th>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            Allergic to drug or contrast media?<br><i>Mempunyai alahan terhadap ubat atau media kontras.</i>
                                                        </td>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                                            <div class="inline fields">
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="allergy_drug" value="1" id="allergy_yes">
                                                                        <label for="allergy_yes">Yes</label>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="allergy_drug" value="0" id="allergy_no">
                                                                        <label for="allergy_no">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">18</th>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;" colspan="3">
                                                            <div class="inline field">
                                                                Blood urea: <input id="bloodurea" name="bloodurea" type="text" style="width: 350px; margin-left: 15px;">
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">19</th>
                                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;" colspan="3">
                                                            <div class="inline field">
                                                                Serum creatinine: <input id="serum_creatinine" name="serum_creatinine" type="text" style="width: 350px; margin-left: 15px;">
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                            <div class="sixteen wide column centered grid" style="padding: 14px 14px 0px 150px;">
                                                <div class="inline fields">
                                                    <label>Name of Doctor</label>
                                                    <div class="field">
                                                        <input id="mri_doctorname" name="mri_doctorname" type="text" style="width: 320px; text-transform: uppercase;">
                                                    </div>
                                                    
                                                    <label>Name of patient/parents/guardian</label>
                                                    <div class="field">
                                                        <input id="mri_patientname" name="mri_patientname" type="text" style="width: 320px;" rdonly>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="five wide column">
                                                <div class="field">
                                                    <label>Doctor / Radiologist</label>
                                                    <input id="mri_radiologist" name="mri_radiologist" type="text" style="width: 320px; text-transform: uppercase;">
                                                </div>
                                            </div>
                                            
                                            <div class="five wide column">
                                                <div class="field">
                                                    <label>Radiographer</label>
                                                    <input id="radiographer" name="radiographer" type="text" style="width: 320px; text-transform: uppercase;">
                                                </div>
                                            </div>
                                            
                                            <div class="five wide column">
                                                <div class="field">
                                                    <label>Entered By</label>
                                                    <input id="mri_lastuser" name="mri_lastuser" type="text" style="width: 320px; text-transform: uppercase;">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ui bottom attached tab raised segment" data-tab="preContrast">
                        @include('patientcare.preContrast')
                    </div>
                    
                    <div class="ui bottom attached tab raised segment" data-tab="consent">
                        @include('patientcare.consentForm')
                    </div>
                </div>
                
                <div class="ui bottom attached tab raised segment" data-tab="physio">
                    <div class="ui segments" style="position: relative;">
                        <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
                            <div class="ui small blue icon buttons" id="btn_grp_edit_physio" style="position: absolute;
                                padding: 0 0 0 0;
                                right: 40px;
                                top: 9px;
                                z-index: 2;">
                                <button class="ui button" id="new_physio"><span class="fa fa-plus-square-o"></span>New</button>
                                <button class="ui button" id="edit_physio"><span class="fa fa-edit fa-lg"></span>Edit</button>
                                <button class="ui button" id="save_physio"><span class="fa fa-save fa-lg"></span>Save</button>
                                <button class="ui button" id="cancel_physio"><span class="fa fa-ban fa-lg"></span>Cancel</button>
                                <button class="ui button" id="physio_chart"><span class="fa fa-print fa-lg"></span>Print</button>
                            </div>
                        </div>
                        <div class="ui segment">
                            <div class="ui grid">
                                <form id="formPhysio" class="floated ui form sixteen wide column">
                                    <div class='ui grid' style="padding: 15px 30px;">
                                        <div class="four wide column" style="padding: 14px 14px 0px 150px;">
                                            <div class="field">
                                                <label>Date</label>
                                            </div>
                                        </div>
                                        
                                        <div class="twelve wide column" style="padding: 14px 14px 0px 14px;">
                                            <div class="field eight wide column">
                                                <input id="req_date" name="req_date" type="date">
                                            </div>
                                        </div>
                                        
                                        <div class="four wide column" style="padding: 14px 14px 0px 150px;">
                                            <div class="field">
                                                <label>Clinical Diagnosis</label>
                                            </div>
                                        </div>
                                        
                                        <div class="twelve wide column" style="padding: 14px 14px 0px 14px;">
                                            <div class="field eight wide column">
                                                <textarea id="clinic_diag" name="clinic_diag" type="text" rows="5"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="four wide column" style="padding: 14px 14px 0px 150px;">
                                            <div class="field">
                                                <label>Relevant Finding(s)</label>
                                            </div>
                                        </div>
                                        
                                        <div class="twelve wide column" style="padding: 14px 14px 0px 14px;">
                                            <div class="field eight wide column">
                                                <textarea id="findings" name="findings" type="text" rows="5"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="three wide column" style="padding: 14px 14px 0px 150px;">
                                            <div class="field">
                                                <label>Treatment</label>
                                            </div>
                                        </div>
                                        
                                        <div class="thirteen wide column" style="padding: 14px 14px 0px 30px;">
                                            <div class="field eight wide column">
                                                <!-- <textarea id="phy_treatment" name="phy_treatment" type="text" rows="5"></textarea> -->
                                                <div class="ui form" id='Req_treatment'>
                                                    <div class="field" style="padding-top: 20px; text-align: left; color: red;">
                                                        <p id="p_error_ReqTreatment"></p>
                                                    </div>
                                                    
                                                    <div class="grouped fields">
                                                        <div class="field">
                                                            <div class="ui checkbox">
                                                                <input type="checkbox" name="tr_physio" id="tr_physio" value="1">
                                                                <label for="tr_physio">Physiotherapy</label>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <div class="ui checkbox">
                                                                <input type="checkbox" name="tr_occuptherapy" id="tr_occuptherapy" value="1">
                                                                <label for="tr_occuptherapy">Occupational Therapy</label>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <div class="ui checkbox">
                                                                <input type="checkbox" name="tr_respiphysio" id="tr_respiphysio" value="1">
                                                                <label for="tr_respiphysio">Respiratory Physiotherapy</label>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <div class="ui checkbox">
                                                                <input type="checkbox" name="tr_neuro" id="tr_neuro" value="1">
                                                                <label for="tr_neuro">Neuro Rehab</label>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <div class="ui checkbox">
                                                                <input type="checkbox" name="tr_splint" id="tr_splint" value="1">
                                                                <label for="tr_splint">Splinting</label>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <div class="ui checkbox">
                                                                <input type="checkbox" name="tr_speech" id="tr_speech" value="1">
                                                                <label for="tr_speech">Speech</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="four wide column" style="padding: 14px 14px 0px 150px;">
                                            <div class="field">
                                                <label>Remarks</label>
                                            </div>
                                        </div>
                                        
                                        <div class="twelve wide column" style="padding: 14px 14px 0px 14px;">
                                            <div class="field eight wide column">
                                                <textarea id="remarks" name="remarks" type="text" rows="5"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="sixteen wide column centered grid" style="padding-left: 150px;">
                                            <div class="inline field">
                                                <label>Name of Requesting Doctor</label>
                                                <input id="phy_doctorname" name="phy_doctorname" type="text" style="width: 350px; text-transform: uppercase;">
                                            </div>
                                            
                                            <div class="inline field">
                                                <label>Entered By</label>
                                                <input id="phy_lastuser" name="phy_lastuser" type="text" style="width: 350px; text-transform: uppercase;">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="ui bottom attached tab raised segment" data-tab="dressing">
                    <div class="ui segments" style="position: relative;">
                        <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
                            <div class="ui small blue icon buttons" id="btn_grp_edit_dressing" style="position: absolute;
                                padding: 0 0 0 0;
                                right: 40px;
                                top: 9px;
                                z-index: 2;">
                                <button class="ui button" id="new_dressing"><span class="fa fa-plus-square-o"></span>New</button>
                                <button class="ui button" id="edit_dressing"><span class="fa fa-edit fa-lg"></span>Edit</button>
                                <button class="ui button" id="save_dressing"><span class="fa fa-save fa-lg"></span>Save</button>
                                <button class="ui button" id="cancel_dressing"><span class="fa fa-ban fa-lg"></span>Cancel</button>
                                <button class="ui button" id="dressing_chart"><span class="fa fa-print fa-lg"></span>Print</button>
                            </div>
                        </div>
                        <div class="ui segment">
                            <div class="ui grid">
                                <form id="formDressing" class="floated ui form sixteen wide column">
                                    <div class='ui grid' style="padding: 15px 30px;">
                                        <div class="sixteen wide column centered grid" style="padding: 14px 14px 0px 150px;">
                                            <div class="inline field">
                                                <label>Name</label>
                                                <input id="dressing_patientname" name="dressing_patientname" type="text" style="width: 350px;" rdonly>
                                            </div>
                                            
                                            <div class="inline field">
                                                <label>NRIC</label>
                                                <input id="patientnric" name="patientnric" type="text" style="width: 350px;" rdonly>
                                            </div>
                                        </div>
                                        
                                        <div class="thirteen wide column" style="padding: 14px 200px 14px 150px;">
                                            <div class="ui segments">
                                                <div class="ui secondary segment">FREQUENCY</div>
                                                <div class="ui segment">
                                                    <div class="inline field">
                                                        <input id="od_dressing" name="od_dressing" type="number" style="width: 80px;">
                                                        <label>OD Dressing</label>
                                                    </div>
                                                    
                                                    <div class="inline field">
                                                        <input id="bd_dressing" name="bd_dressing" type="number" style="width: 80px;">
                                                        <label>BD Dressing</label>
                                                    </div>
                                                    
                                                    <div class="inline field">
                                                        <input id="eod_dressing" name="eod_dressing" type="number" style="width: 80px;">
                                                        <label>EOD Dressing</label>
                                                    </div>
                                                    
                                                    <div class="inline field">
                                                        <input id="others_dressing" name="others_dressing" type="number" style="width: 80px;">
                                                        <label>Others:</label>
                                                        <input id="others_name" name="others_name" type="text" style="margin-left: 15px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="four wide column" style="padding: 14px 14px 0px 150px;">
                                            <div class="field">
                                                <label>Solution/Method</label>
                                            </div>
                                        </div>
                                        
                                        <div class="twelve wide column" style="padding-bottom: 0px;">
                                            <div class="field eight wide column">
                                                <textarea id="solution" name="solution" type="text" rows="5"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="sixteen wide column centered grid" style="padding-left: 150px;">
                                            <div class="inline field">
                                                <label>Doctor's Name</label>
                                                <input id="dressing_doctorname" name="dressing_doctorname" type="text" style="width: 350px; text-transform: uppercase;">
                                            </div>
                                            
                                            <div class="inline field">
                                                <label>Entered By</label>
                                                <input id="dressing_lastuser" name="dressing_lastuser" type="text" style="width: 350px; text-transform: uppercase;">
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
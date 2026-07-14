<div class="ui column">
    <div class="ui segments" style="position: relative;">
        <div class="ui secondary segment bluecloudsegment">
            <div class="ui form">
                <div class="six wide column">
                    <div class="inline fields" style="margin-bottom: 0px;">
                        <label style="color: rgba(0,0,0,.6);">DOCTOR NOTES</label>
                        <div class="field">
                            <div class="ui radio checkbox checked pastcurr">
                                <input type="radio" name="toggle_type" tabindex="0" class="hidden" id="current" value="current" checked>
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
                <!-- <button class="ui button" id="edit_doctorNote"><span class="fa fa-edit fa-lg"></span>Edit</button> -->
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
                            <th class="scope">Doctor</th>
                            <th class="scope">adddate</th>
                            <th class="scope">recordtime</th>
                            <th class="scope">type</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="ui grid">
                <form id="formDoctorNote" class="right floated ui form thirteen wide column">
                   <!-- <input id="mrn_doctorNote" name="mrn_doctorNote" type="hidden">
                    <input id="episno_doctorNote" name="episno_doctorNote" type="hidden">
                    <input id="age_doctorNote" name="age_doctorNote" type="hidden">
                     <input id="recorddate" name="recorddate" type="hidden"> 
                    <input id="recorddate_doctorNote" name="recorddate_doctorNote" type="hidden">
                    <input id="mrn_doctorNote_past" name="mrn_doctorNote_past" type="hidden">
                    <input id="episno_doctorNote_past" name="episno_doctorNote_past" type="hidden">
                    <input id="ptname_doctorNote" name="ptname_doctorNote" type="hidden">
                    <input id="preg_doctorNote" name="preg_doctorNote" type="hidden">
                    <input id="ic_doctorNote" name="ic_doctorNote" type="hidden">
                    <input id="doctorname_doctorNote" name="doctorname_doctorNote" type="hidden">-->
                    
                    <div class='ui grid'>
                        <div class="eight wide column">
                            <div class="field">
                                <label>Referred From</label>
                                <input id="referredFrom" name="referredFrom" type="text" rdonly>
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
                                                <label>Previous Medical/Surgery History</label>
                                                <textarea id="pmh" name="pmh" type="text" rows="12"></textarea>
                                            </div>
                                        </div>

                                        <div class="eight wide column">
                                            <div class="field">
                                                <label>History of Present Illness</label>
                                                <textarea id="clinicnote" name="clinicnote" type="text" rows="12"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="sixteen wide column">
                                            <div class="ui segments">
                                                <div class="ui secondary segment">ALLERGIES</div>
                                                <div class="ui segment">
                                                    <table class="table table-sm table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <td><input type="checkbox" id="allergydrugs_dn" name="allergydrugs" value="1"></td>
                                                                <td><label for="allergydrugs_dn">Meds</label></td>
                                                                <td><textarea id="drugs_remarks_dn" name="drugs_remarks" type="text" rows="3"></textarea></td>
                                                            </tr>
                                                        
                                                            <tr>
                                                                <td><input type="checkbox" id="allergyfood_dn" name="allergyfood" value="1"></td>
                                                                <td><label for="allergyfood_dn">Food</label></td>
                                                                <td><textarea id="food_remarks_dn" name="food_remarks" type="text" rows="3"></textarea></td>
                                                            </tr>
                                                        
                                                            <tr>
                                                                <td><input type="checkbox" id="allergyothers_dn" name="allergyothers" value="1"></td>
                                                                <td><label for="allergyothers_dn">Others</label></td>
                                                                <td><textarea id="others_remarks_dn" name="others_remarks" type="text" rows="3"></textarea></td>
                                                            </tr>
                                                        
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
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
                                <div class="ui segment">
                                    <textarea id="phyexam" name="phyexam" type="text" rows="5" style="padding: 14px 14px 14px 14px;"></textarea>
                                </div>

                            </div>
                            
                            <div class="ui segments">
                                <div class="ui secondary segment">Diagnosis</div>
                                <div class="ui segment">
                                    <textarea id="diagfinal" name="diagfinal" type="text" rows="5"></textarea>
                                </div>
                            </div>

                            <div class="ui segments">
                                <div class="ui secondary segment">Plans & Treatments</div>
                                <div class="ui segment">
                                    <textarea id="treatment" name="treatment" type="text" rows="3"></textarea>
                                </div>
                            </div>
                            
                            <div class="ui segments">
                                <div class="ui secondary segment">Lab Investigation</div>
                                <div class="ui segment">
                                    <textarea id="investigate" name="investigate" type="text" rows="3"></textarea>
                                </div>
                            </div>
                            
                            <div class="ui segments">
                                <div class="ui secondary segment">Diagnostic Imaging</div>
                                <div class="ui segment">
                                    <textarea id="diagImg" name="diagImg" type="text" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="ui segments">
                                <div class="ui secondary segment">ECG</div>
                                <div class="ui segment">
                                    <textarea id="ecg" name="ecg" type="text" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="ui segments">
                                <div class="ui secondary segment">DISPOSITION</div>
                                <div class="ui segment">
                                    <div class="grouped fields">
                                        <div class="field">
                                            <div class="ui checkbox">
                                                <input type="checkbox" name="dn_discharge" id="dn_discharge" value="1">
                                                <label for="dn_discharge">Discharge</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui checkbox">
                                                <input type="checkbox" name="dn_referCons" id="dn_referCons" value="1">
                                                <label for="dn_referCons">Refer Consultant</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui checkbox">
                                                <input type="checkbox" name="dn_admission" id="dn_admission" value="1">
                                                <label for="dn_admission">Admission</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui checkbox">
                                                <input type="checkbox" name="dn_aor" id="dn_aor" value="1">
                                                <label for="dn_aor">AOR Discharge</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui checkbox">
                                                <input type="checkbox" name="dn_transferOut" id="dn_transferOut" value="1">
                                                <label for="dn_transferOut">Transfer Out</label>
                                            </div>
                                        </div>
                                    </div>
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
                                            <input type="text" onKeyPress="if(this.value.length==6) return false;" id="bp_sys1" name="vs_bp_sys1" style="width:25%">
                                            <input type="text" onKeyPress="if(this.value.length==6) return false;" id="bp_dias2" name="vs_bp_dias2" style="width:25%">
                                            <div class="ui basic label">mmHg</div>
                                        </div>
                                    </div>
                                    
                                    <div class="field">
                                        <label>SPO2</label>
                                        <div class="ui right labeled input">
                                            <input type="text" onKeyPress="if(this.value.length==6) return false;" id="spo2" name="vs_spo">
                                            <div class="ui basic label">%</div>
                                        </div>
                                    </div>
                                    
                                    <div class="field">
                                        <label>Pulse</label>
                                        <div class="ui right labeled input">
                                            <input type="text" onKeyPress="if(this.value.length==6) return false;" id="pulse" name="vs_pulse">
                                            <div class="ui basic label">Bpm</div>
                                        </div>
                                    </div>
                                    
                                    <div class="field">
                                        <label>Glucometer</label>
                                        <div class="ui right labeled input">
                                            <input type="text" onKeyPress="if(this.value.length==6) return false;" id="gxt" name="vs_gxt">
                                            <div class="ui basic label">mmol/L</div>
                                        </div>
                                    </div>
                                    
                                    <div class="field">
                                        <label>Temperature</label>
                                        <div class="ui right labeled input">
                                            <input type="text" onKeyPress="if(this.value.length==6) return false;" id="temperature" name="vs_temperature">
                                            <div class="ui basic label">°C</div>
                                        </div>
                                    </div>
                                    
                                    <div class="field">
                                        <label>Height</label>
                                        <div class="ui right labeled input">
                                            <input type="text" onKeyPress="if(this.value.length==6) return false;" id="height" name="vs_height">
                                            <div class="ui basic label">CM</div>
                                        </div>
                                    </div>
                                    
                                    <div class="field">
                                        <label>Weight</label>
                                        <div class="ui right labeled input">
                                            <input type="text" onKeyPress="if(this.value.length==6) return false;" id="weight" name="vs_weight">
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
                                            <input type="text" onKeyPress="if(this.value.length==6) return false;" id="respiration" name="vs_respiration">
                                            <div class="ui basic label">Min</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="sixteen wide column" id="addnotes">
                            <div class="ui segments">
                                <div class="ui secondary segment">Additional Notes</div>
                                <div class="ui segment" id="jqGridAddNotes_c">
                                    <table id="jqGridAddNotes" class="table table-striped"></table>
                                    <div id="jqGridPagerAddNotes"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="sixteen wide column" style="display: none;">
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
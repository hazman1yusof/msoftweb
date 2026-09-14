<div class="ui top attached tabular menu" id="labMainTabs">
    <a class="item active" data-tab="labClinicReqFor" id="navtab_labClinicReqFor">Laboratory Form</a>
    @if($laboratory_inside_iframe_phase == 'laboratory') 
    <a class="item apptMainItem" data-tab="docImaging" id="navtab_docImaging">Document Imaging</a>
    @endif
</div>

<div class="ui bottom attached tab raised segment active" data-tab="labClinicReqFor">
    <input type="hidden" id="laboratory_inside_iframe_phase" value="{{$laboratory_inside_iframe_phase}}">
    <div class="ui segments" style="position: relative;">
        <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
            <div class="ui small blue icon buttons" id="btn_grp_edit_labClinicReqFor" style="position: absolute;
                padding: 0 0 0 0;
                right: 40px;
                top: 9px;
                z-index: 2;">
                <button class="ui button" id="new_labClinicReqFor"><span class="fa fa-plus-square-o"></span>New</button>
                <button class="ui button" id="edit_labClinicReqFor"><span class="fa fa-edit fa-lg"></span>Edit</button>
                <button class="ui button" id="save_labClinicReqFor"><span class="fa fa-save fa-lg"></span>Save</button>
                <button class="ui button" id="cancel_labClinicReqFor"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            </div>
        </div>

        <div class="ui segment">
            <div class="three wide column" style="position: absolute;
                        left: 10px;
                        top: 30px;">
                <table id="lab_date_tbl" class="ui celled table" style="min-width: 270px;">
                    <thead>
                        <tr>
                            <th class="scope">idno</th>
                            <th class="scope">mrn</th>
                            <th class="scope">episno</th>
                            <th class="scope">Date / Time</th>
                            <th class="scope">adddate</th>
                            <th class="scope">addtime</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="ui grid">
                <form id="formlabClinicReqFor" class="right floated ui form twelve wide column">
                    <input type="hidden" id="req_lab_idno" name="idno">
                    <div class='ui grid' style="padding: 15px 30px;">
                        <div class="sixteen wide column centered grid" style="padding: 14px 14px 0px 14px;">
                            <div class="inline field">
                                <label>Lab No.</label>
                                <input type="text" id="req_lab_labno" name="labno">
                            </div>
                            
                            <div class="inline fields">
                                <label for="pt_condition">Patient Status</label>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        <input type="radio" name="status" value="Urgent" id="req_lab_Urgent">
                                        <label for="req_lab_Urgent">Urgent</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        <input type="radio" name="status" value="Overtime" id="req_lab_Overtime">
                                        <label for="req_lab_Overtime">Overtime</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="four wide column" style="padding: 14px 14px 14px 14px;">
                            <div class="field">
                                <label>History</label>
                            </div>
                        </div>
                        
                        <div class="twelve wide column">
                            <div class="field eight wide column">
                                <textarea id="req_lab_history" name="history" rows="5"></textarea>
                            </div>
                        </div>
                        
                        <div class="four wide column" style="padding: 14px 14px 14px 14px;">
                            <div class="field">
                                <label>Clinical Findings</label>
                            </div>
                        </div>
                        
                        <div class="twelve wide column">
                            <div class="field eight wide column">
                                <textarea id="req_lab_clinicfinds" name="clinicfinds" rows="5"></textarea>
                            </div>
                        </div>
                        
                        <div class="four wide column" style="padding: 14px 14px 14px 14px;">
                            <div class="field">
                                <label>Diagnosis</label>
                            </div>
                        </div>
                        
                        <div class="twelve wide column">
                            <div class="field eight wide column">
                                <textarea id="req_lab_diagnosis" name="diagnosis" rows="5"></textarea>
                            </div>
                        </div>

                        <div class="ui stretched row">
                            <div class="four wide column centered grid" style="padding: 6px; height: stretch;">
                                <div class="ui segment">
                                    <h4 class="ui dividing header">Drug Therapy</h4>
                                    <div class="inline field">
                                        <label>Last Dose</label>
                                        <input type="date" id="req_lab_lastdosedate" name="lastdosedate">
                                    </div>
                                    <div class="inline field">
                                        <label>Collection</label>
                                        <input type="time" id="req_lab_lastdosetime" name="lastdosetime">
                                    </div>
                                </div>
                            </div>

                            <div class="eight wide column centered grid" style="padding: 6px; height: stretch;">
                                <div class="ui segment">
                                    <h4 class="ui dividing header">Specimen</h4>
                                    
                                    <label>Specimen Type</label>
                                    <div class="inline fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="specimenty" value="Blood" id="req_lab_specimenty_Blood">
                                                <label for="req_lab_specimenty_Blood">Blood</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="specimenty" value="Urine" id="req_lab_Urine">
                                                <label for="req_lab_Urine">Urine</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="specimenty" value="Faeces" id="req_lab_Faeces">
                                                <label for="req_lab_Faeces">Faeces</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="specimenty" value="Swab" id="req_lab_Swab">
                                                <label for="req_lab_Swab">Swab</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="inline fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="specimenty" value="Others" id="req_lab_Others">
                                                <label for="req_lab_Others">Others</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <input type="text" id="req_lab_specimentytxt" name="specimentytxt">
                                        </div>
                                    </div>
                                
                                    <label>Specimen Collection</label>
                                    <div class="inline field">
                                        <label>Date</label>
                                        <input type="date" id="req_lab_specimendate" name="specimendate">
                                    </div>
                                    <div class="inline field">
                                        <label>Time</label>
                                        <input type="time" id="req_lab_specimentime" name="specimentime">
                                    </div>
                                    
                                    <div class="inline field">
                                        <label>Collected By</label>
                                        <input type="text" id="req_lab_collectedby" name="collectedby">
                                    </div>
                                </div>
                            </div>

                            <div class="four wide column centered grid" style="padding: 6px; height: stretch;">
                                <div class="ui segment">
                                    <h4 class="ui dividing header">Other</h4>
                                    <div class="inline fields">
                                        <label>Fasting</label>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="fasting" value="yes" id="req_lab_fasting_yes">
                                                <label for="req_lab_fasting_yes">Yes</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="fasting" value="no" id="req_lab_fasting_no">
                                                <label for="req_lab_fasting_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="inline fields">
                                        <label>Pregnant</label>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="pregnant" value="yes" id="req_lab_pregnant_yes">
                                                <label for="req_lab_pregnant_yes">Yes</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="pregnant" value="no" id="req_lab_pregnant_no">
                                                <label for="req_lab_pregnant_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="inline field">
                                        <label>Gestation week</label>
                                        <input type="text" id="req_lab_gestationweek" name="gestationweek">
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

@if($laboratory_inside_iframe_phase == 'laboratory')     
<div class="ui bottom attached tab raised segment" data-tab="docImaging">
    @include('patientcare.userfile_div')
</div>
@endif
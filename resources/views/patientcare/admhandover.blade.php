<div class="ui segments" style="position: relative;">
    <div class="ui segment">
        <form id="formAdmhandover" class="ui form">
            <input id="mrn_admhandover" name="mrn_admhandover" type="hidden">
            <input id="episno_admhandover" name="episno_admhandover" type="hidden">
            <input id="age_admhandover" name="age_admhandover" type="hidden">
            <input id="ptname_admhandover" name="ptname_admhandover" type="hidden">
            <input id="preg_admhandover" name="preg_admhandover" type="hidden">
            <input id="ic_admhandover" name="ic_admhandover" type="hidden">
            <input id="doctorname_admhandover" name="doctorname_admhandover" type="hidden">

            <div class="ui grid">
                <div class="eight wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">INFORMATION</div>
                        <div class="ui segment" style="height: 700px;">
                            <div class="inline fields">
                                <div class="field">
                                    <label>Date of Admission</label>
                                    <input id="dateofadm" name="dateofadm" type="date">
                                </div>
                                <div class="ui grid">
                                    <label>Type</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="type" value="INPATIENT">Inpatient
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="type" value="DAYCARE">Daycare
                                    </label> 
                                </div>
                            </div>

                            <div class="field">
                                <label>Reason Admission</label>
                                <textarea id="reasonadm" name="reasonadm" type="text" rows="4"  data-validation="required" data-validation-error-msg-required="Please enter information."></textarea>
                            </div>

                            <div class="field">
                                <label>Diagnosis</label>
                                <textarea id="diagnosis" name="diagnosis" type="text" rows="4"></textarea>
                            </div>

                            <div class="field eight wide column">
                                <label>Weight</label>
                                <div class="ui right labeled input">
                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="weights" name="weights">
                                    <div class="ui basic label">kg</div>
                                </div>
                            </div>

                            <div class="field">
                                <label>Medical History</label>
                                <textarea id="medicalhistory" name="medicalhistory" type="text" rows="4" ></textarea>
                            </div>

                            <div class="field">
                                <label>Surgical History</label>
                                <textarea id="surgicalhistory" name="surgicalhistory" type="text" rows="4" ></textarea>
                            </div>
                            
                        </div>

                        
                    </div>
                </div>

                <div class="eight wide column">
                    <div class="ui segments">
                            <div class="ui secondary segment">ALLERGIES</div>
                            <div class="ui segment" style="height: 700px;">
                                <table class="table table-sm table-hover">
                                    <tbody>
                                        <tr>
                                            <td><input type="checkbox" id="allergydrugs" name="allergydrugs" value="1"></td>
                                            <td><label for="allergydrugs">Meds</label></td>
                                            <td><textarea id="drugs_remarks" name="drugs_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergyplaster" name="allergyplaster" value="1"></td>
                                            <td><label for="allergyplaster">Plaster</label></td>
                                            <td><textarea id="plaster_remarks" name="plaster_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergyfood" name="allergyfood" value="1"></td>
                                            <td><label for="allergyfood">Food</label></td>
                                            <td><textarea id="food_remarks" name="food_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergyenvironment" name="allergyenvironment" value="1"></td>
                                            <td><label for="allergyenvironment">Environment</label></td>
                                            <td><textarea id="environment_remarks" name="environment_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergyothers" name="allergyothers" value="1"></td>
                                            <td><label for="allergyothers">Others</label></td>
                                            <td><textarea id="others_remarks" name="others_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergyunknown" name="allergyunknown" value="1"></td>
                                            <td><label for="allergyunknown">Unknown</label></td>
                                            <td><textarea id="unknown_remarks" name="unknown_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergynone" name="allergynone" value="1"></td>
                                            <td><label for="allergynone">None</label></td>
                                            <td><textarea id="none_remarks" name="none_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </div>

                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">REQUIRED</div>
                        <div class="ui segment">
                            <div class="ui grid">
                                <table class="ui striped table">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Plan</th>
                                            <th scope="col">Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>RTK/PCR <br>
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="rtkpcr" value="1" id="rtkpcr_yes">
                                                            <label for="rtkpcr_yes">Yes</label>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="rtkpcr" value="0" id="rtkpcr_no">
                                                            <label for="rtkpcr_no">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea id="rtkpcr_remark" name="rtkpcr_remark" type="text" rows="4"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>Blood Investigation <br>
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="bloodinv" value="1" id="bloodinv_yes">
                                                            <label for="bloodinv_yes">Yes</label>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="bloodinv" value="0" id="bloodinv_no">
                                                            <label for="bloodinv_no">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea id="bloodinv_remark" name="bloodinv_remark" type="text" rows="4"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>Branula <br>
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="branula" value="1" id="branula_yes">
                                                            <label for="branula_yes">Yes</label>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="branula" value="0" id="branula_no">
                                                            <label for="branula_no">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea id="branula_remark" name="branula_remark" type="text" rows="4"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">4</th>
                                            <td>CXR/MRI/CT Scan <br>
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="scan" value="1" id="scan_yes">
                                                            <label for="scan_yes">Yes</label>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="scan" value="0" id="scan_no">
                                                            <label for="scan_no">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea id="scan_remark" name="scan_remark" type="text" rows="4"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">5</th>
                                            <td>Insurance <br>
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="insurance" value="1" id="insurance_yes">
                                                            <label for="insurance_yes">Yes</label>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="insurance" value="0" id="insurance_no">
                                                            <label for="insurance_no">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea id="insurance_remark" name="insurance_remark" type="text" rows="4"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">6</th>
                                            <td>Medication (Antiplatlet) <br>
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="medication" value="1" id="medication_yes">
                                                            <label for="medication_yes">Yes</label>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="medication" value="0" id="medication_no">
                                                            <label for="medication_no">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea id="medication_remark" name="medication_remark" type="text" rows="4"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">7</th>
                                            <td>Consent <br>
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="consent" value="1" id="consent_yes">
                                                            <label for="consent_yes">Yes</label>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="consent" value="0" id="consent_no">
                                                            <label for="consent_no">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea id="consent_remark" name="consent_remark" type="text" rows="4"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">8</th>
                                            <td>Smoking <br>
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="smoking" value="1" id="smoking_yes">
                                                            <label for="smoking_yes">Yes</label>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="smoking" value="0" id="smoking_no">
                                                            <label for="smoking_no">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <label for="smoking_remark" style="padding-bottom:5px">Last Time:</label>
                                                <textarea id="smoking_remark" name="smoking_remark" type="text" rows="4"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">9</th>
                                            <td>NBM <br>
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="nbm" value="1" id="nbm_yes">
                                                            <label for="nbm_yes">Yes</label>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="nbm" value="0" id="nbm_no">
                                                            <label for="nbm_no">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <label for="nbm_remark" style="padding-bottom:5px">Last Meal:</label>
                                                <textarea id="nbm_remark" name="nbm_remark" type="text" rows="4"></textarea>
                                            </td>
                                        </tr>
							        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">REPORT</div>
                        <div class="ui segment">
                            <div class="field">
                                <textarea id="report" name="report" type="text" rows="4" ></textarea>
                            </div>
                        </div>
                            
                    </div>
                </div>

            </div>

        </form>
    </div>
</div>
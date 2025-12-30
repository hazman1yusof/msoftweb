<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment">
        ADMISSION HANDOVER
        <div class="ui small blue icon buttons" id="btn_grp_edit_admHandoverAppt" style="position: absolute;
                    padding: 0 0 0 0;
                    right: 40px;
                    top: 9px;
                    z-index: 2;">
          <button class="ui button" id="new_admHandoverAppt"><span class="fa fa-plus-square-o"></span> New</button>
          <button class="ui button" id="edit_admHandoverAppt"><span class="fa fa-edit fa-lg"></span> Edit</button>
          <button class="ui button" id="save_admHandoverAppt"><span class="fa fa-save fa-lg"></span> Save</button>
          <button class="ui button" id="cancel_admHandoverAppt"><span class="fa fa-ban fa-lg"></span> Cancel</button>
          <button class="ui button" id="admhandoverAppt_report"><span class="fa fa-print fa-lg"></span>Print</button>
        </div>
    </div>

    <div class="ui segment">
        <form id="formAdmHandoverAppt" class="ui form">
            <input id="mrn_admHandover" name="mrn_admHandover" type="hidden">
            <input id="episno_admHandover" name="episno_admHandover" type="hidden">

            <div class="ui grid">
                <div class="eight wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">INFORMATION</div>
                        <div class="ui segment" style="height: 700px;">
                            <div class="inline fields">
                                <div class="field">
                                    <label>Date of Admission</label>
                                    <input id="dateofadmAppt" name="dateofadm" type="date">
                                </div>
                                <div class="ui grid">
                                    <label>Type</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="type" value="INPATIENT" id="ipAppt">Inpatient
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="type" value="DAYCARE" id="dcAppt">Daycare
                                    </label> 
                                </div>
                            </div>

                            <div class="field">
                                <label>Reason Admission</label>
                                <textarea id="reasonadmAppt" name="reasonadm" type="text" rows="4"></textarea>
                            </div>

                            <div class="field">
                                <label>Diagnosis</label>
                                <textarea id="diagnosisAppt" name="diagnosis" type="text" rows="4"></textarea>
                            </div>

                            <div class="field eight wide column">
                                <label>Weight</label>
                                <div class="ui right labeled input">
                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="weightsAppt" name="weights">
                                    <div class="ui basic label">kg</div>
                                </div>
                            </div>

                            <div class="field">
                                <label>Medical History</label>
                                <textarea id="medicalhistoryAppt" name="medicalhistory" type="text" rows="4"></textarea>
                            </div>

                            <div class="field">
                                <label>Surgical History</label>
                                <textarea id="surgicalhistoryAppt" name="surgicalhistory" type="text" rows="4"></textarea>
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
                                            <td><input type="checkbox" id="allergydrugsAppt" name="allergydrugs" value="1" class="ui checkbox" class="hidden" tabindex="0"></td>
                                            <td><label for="allergydrugs">Meds</label></td>
                                            <td><textarea id="drugs_remarksAppt" name="drugs_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergyplasterAppt" name="allergyplaster" value="1"  class="ui checkbox" class="hidden" tabindex="0"></td>
                                            <td><label for="allergyplaster">Plaster</label></td>
                                            <td><textarea id="plaster_remarksAppt" name="plaster_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergyfoodAppt" name="allergyfood" value="1"  class="ui checkbox" class="hidden" tabindex="0"></td>
                                            <td><label for="allergyfood">Food</label></td>
                                            <td><textarea id="food_remarksAppt" name="food_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergyenvironmentAppt" name="allergyenvironment" value="1"  class="ui checkbox" class="hidden" tabindex="0"></td>
                                            <td><label for="allergyenvironment">Environment</label></td>
                                            <td><textarea id="environment_remarksAppt" name="environment_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergyothersAppt" name="allergyothers" value="1"  class="ui checkbox" class="hidden" tabindex="0"></td>
                                            <td><label for="allergyothers">Others</label></td>
                                            <td><textarea id="others_remarksAppt" name="others_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergyunknownAppt" name="allergyunknown" value="1"  class="ui checkbox" class="hidden" tabindex="0"></td>
                                            <td><label for="allergyunknown">Unknown</label></td>
                                            <td><textarea id="unknown_remarksAppt" name="unknown_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergynoneAppt" name="allergynone" value="1"  class="ui checkbox" class="hidden" tabindex="0"></td>
                                            <td><label for="allergynone">None</label></td>
                                            <td><textarea id="none_remarksAppt" name="none_remarks" type="text" rows="3"></textarea></td>
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
                                                    <label class="radio-inline">
                                                            <input type="radio" name="rtkpcr" id="rtkpcrYesAppt" value="1">Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="rtkpcr" id="rtkpcrNoAppt" value="0">No
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea id="rtkpcr_remarkAppt" name="rtkpcr_remark" type="text" rows="4"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>Blood Investigation <br>
                                                <div class="inline fields">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="bloodinv" id="bloodinvYesAppt" value="1">Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="bloodinv" id="bloodinvNoAppt" value="0">No
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea id="bloodinv_remarkAppt" name="bloodinv_remark" type="text" rows="4"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>Branula <br>
                                                <div class="inline fields">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="branula" id="branulaYesAppt" value="1">Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="branula" id="branulaNoAppt" value="0">No
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea id="branula_remarkAppt" name="branula_remark" type="text" rows="4"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">4</th>
                                            <td>CXR/MRI/CT Scan <br>
                                                <div class="inline fields">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="scan" value="1">Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="scan" value="0">No
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea id="scan_remarkAppt" name="scan_remark" type="text" rows="4"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">5</th>
                                            <td>Insurance <br>
                                                <div class="inline fields">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="insurance" value="1">Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="insurance" value="0">No
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea id="insurance_remarkAppt" name="insurance_remark" type="text" rows="4"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">6</th>
                                            <td>Medication (Antiplatlet) <br>
                                                <div class="inline fields">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="medication" value="1">Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="medication" value="0">No
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea id="medication_remarkAppt" name="medication_remark" type="text" rows="4"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">7</th>
                                            <td>Consent <br>
                                                <div class="inline fields">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="consent" value="1">Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="consent" value="0">No
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea id="consent_remarkAppt" name="consent_remark" type="text" rows="4"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">8</th>
                                            <td>Smoking <br>
                                                <div class="inline fields">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="smoking" value="1">Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="smoking" value="0">No
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <label for="smoking_remark" style="padding-bottom:5px">Last Time:</label>
                                                <textarea id="smoking_remarkAppt" name="smoking_remark" type="text" rows="4"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">9</th>
                                            <td>NBM <br>
                                                <div class="inline fields">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="nbm" value="1">Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="nbm" value="0">No
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <label for="nbm_remark" style="padding-bottom:5px">Last Meal:</label>
                                                <textarea id="nbm_remarkAppt" name="nbm_remark" type="text" rows="4"></textarea>
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
                                <textarea id="reportAppt" name="report" type="text" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="four wide column">
                </div>
                <div class="four wide column">
                    <div class="ui form">
                        <div class="inline field">
                            <label>Pass Over By: </label>
                            <input type="text" name="passoverby" id="passoverbyAppt">
                        </div>
                    </div>
                </div>
                <div class="four wide column">
                    <div class="ui form">
                        <div class="inline field">
                            <label>Take Over By: </label>
                            <input type="text" name="takeoverby" id="takeoverbyAppt">
                        </div>
                    </div>
                </div>
                <div class="four wide column">
                </div>
            </div>
        </form>
    </div>
</div>
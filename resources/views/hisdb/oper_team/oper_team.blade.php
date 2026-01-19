<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment">
        OPERATING TEAM CHECKLIST
        <div class="ui small blue icon buttons" id="btn_grp_edit_oper_team" style="position: absolute;
                    padding: 0 0 0 0;
                    right: 40px;
                    top: 9px;
                    z-index: 2;">
            <button class="ui button" id="new_oper_team"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_oper_team"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_oper_team"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_oper_team"><span class="fa fa-ban fa-lg"></span>Cancel</button>
        </div>
    </div>
    
    <div class="ui segment" style="padding: 10px 10px 30px 30px;">
        <form id="form_oper_team" class="ui form">
            <div class="ui grid">
                <input id="mrn_oper_team" name="mrn_oper_team" type="hidden">
                <input id="episno_oper_team" name="episno_oper_team" type="hidden">
                
                <div class="sixteen wide column">
                    <div class="ui segment">
                        <div class="inline fields">
                            <label>iPesakit</label>
                            <div class="field"><input type="text" class="form-control" id="operteam_iPesakit" name="iPesakit"></div>
                        </div>
                    </div>
                </div>
                
                <div class="eight wide column">
                    <div class="ui segments">
                        <h5>BEFORE INDUCTION OF ANAESTHESIA</h5>
                        <div class="ui secondary segment">SIGN IN</div>
                        <div class="ui segment" style="height: 800px;">
                            <div class="ui grid">
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <tbody>
                                        <!-- <tr>
                                            <td colspan="2">SIGN IN</td>
                                            <td></td>
                                        </tr> -->
                                        <tr>
                                            <td colspan="2">
                                                <input type="checkbox" name="confirmedPt" id="operteam_confirmedPt" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box.">
                                                <label for="operteam_confirmedPt">Checked patient's</label><br>
                                                <ul>
                                                    <li>Identity</li>
                                                    <li>Site</li>
                                                    <li>Procedure</li>
                                                    <li>Consent</li>
                                                </ul>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>Site marked</b></td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="opSite_mark" value="1" data-validation="required" data-validation-error-msg-required="Please check this box." data-validation-error-msg-container="#error-opSite_mark">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="opSite_mark" value="0">No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="opSite_mark" value="NA">NA
                                                </label>
                                                <!-- <label class="radio-inline">
                                                    <input type="radio" name="opSite_mark" value="0">No &nbsp; &nbsp;
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="operteam_opSite_na" name="opSite_na" value="1">NA
                                                </label> -->
                                                <div class="error-msg" id="error-opSite_mark"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="machine_check" id="operteam_machine_check" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box.">
                                                <label for="operteam_machine_check">Checked GA machine</label><br>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="pulseoximeter" id="operteam_pulseoximeter" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box.">
                                                <label for="operteam_pulseoximeter">Pulse oximeter on patient and functioning</label><br>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding-left: 30px;">
                                                <b>Checked patient's</b><br><br>
                                                Allergy?<br>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="ptAllergy" value="0" data-validation="required" data-validation-error-msg-required="Please check this box." data-validation-error-msg-container="#error-ptAllergy">No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="ptAllergy" value="1">Yes &nbsp; &nbsp;
                                                </label>
                                                <div class="error-msg" id="error-ptAllergy"></div>
                                                <br>Airway/Aspiration risk?<br>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="difficultAirway" value="0" data-validation="required" data-validation-error-msg-required="Please check this box." data-validation-error-msg-container="#error-difficultAirway">No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="difficultAirway" value="1">Yes &nbsp; &nbsp;
                                                </label>
                                                <div class="error-msg" id="error-difficultAirway"></div>
                                                <br><br>Risk of > 500ml blood loss (adult) (> 7ml/kg in children)?<br>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="bloodloss" value="0" data-validation="required" data-validation-error-msg-required="Please check this box." data-validation-error-msg-container="#error-bloodloss">No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="bloodloss" value="1">Yes &nbsp; &nbsp;
                                                </label>
                                                <div class="error-msg" id="error-bloodloss"></div>
                                                <br>Adequate IV access?<br>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="ivAccess" value="0" data-validation="required" data-validation-error-msg-required="Please check this box." data-validation-error-msg-container="#error-ivAccess">No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="ivAccess" value="1">Yes &nbsp; &nbsp;
                                                </label>
                                                <div class="error-msg" id="error-ivAccess"></div>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="eight wide column">
                    <div class="ui segments">
                        <h5>BEFORE SKIN INCISION (OR BEFORE INDUCTION OF ANAESTHESIA)</h5>
                        <div class="ui secondary segment">SIGN OUT</div>
                        <div class="ui segment" style="height: 800px;">
                            <div class="ui grid">
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <tbody>
                                        <!-- <tr>
                                            <td>SIGN OUT</td>
                                        </tr> -->
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="whiteboard" id="operteam_whiteboard" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box.">
                                                <label for="operteam_whiteboard">"White board" written</label><br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="introTeam" id="operteam_introTeam" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box.">
                                                <label for="operteam_introTeam">Team members have introduced themselves by name and role</label><br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="bsi_confirmedPt" id="operteam_bsi_confirmedPt" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box.">
                                                <label for="operteam_bsi_confirmedPt">Surgeon, anaesthesia professional and nurse have verbally confirmed</label><br>
                                                <ul>
                                                    <li>Patient</li>
                                                    <li>Site</li>
                                                    <li>Procedure</li>
                                                    <li>Consent</li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 25px;">
                                                <b>Has antibiotic prophylaxis been given?</b><br>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="antibioProphy" value="1" data-validation="required" data-validation-error-msg-required="Please check this box." data-validation-error-msg-container="#error-antibioProphy">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="antibioProphy" value="0">No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="antibioProphy" value="NA">Not applicable
                                                </label>
                                                <!-- <label class="radio-inline">
                                                    <input type="radio" name="antibioProphy" value="0">No &nbsp; &nbsp;
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="operteam_antibioProphy_na" name="antibioProphy_na" value="1">Not applicable
                                                </label> -->
                                                <div class="error-msg" id="error-antibioProphy"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 25px;">
                                                <b>Is essential imaging displayed?</b><br>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="displayImg" value="NA" data-validation="required" data-validation-error-msg-required="Please check this box." data-validation-error-msg-container="#error-displayImg">Not applicable
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="displayImg" value="1">Yes
                                                </label>
                                                <!-- <label class="checkbox-inline">
                                                    <input type="checkbox" id="operteam_displayImg_na" name="displayImg_na" value="1">Not applicable
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="operteam_displayImg" name="displayImg" value="1">Yes
                                                </label> -->
                                                <div class="error-msg" id="error-displayImg"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="briefSurgeon" id="operteam_briefSurgeon" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box." data-validation-error-msg-container="#error-briefSurgeon">
                                                <label for="operteam_briefSurgeon">Anticipated critical events</label><br>
                                                <div class="error-msg" id="error-briefSurgeon"></div>
                                                <b>Surgeon reviews: </b>Any special steps, estimated duration, possible excessive blood loss?<br>
                                                <!-- <textarea class="form-control input-sm" id="operteam_surgeonReview_remark" name="surgeonReview_remark" data-validation="required" data-validation-error-msg-required="Please enter information." data-validation-error-msg-container="#error-surgeonReview_remark"></textarea>
                                                <div class="error-msg" id="error-surgeonReview_remark"></div> -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="anaesthReview" id="operteam_anaesthReview" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box.">
                                                <label for="operteam_anaesthReview">Anaesthesis team review: &nbsp;</label>Any patient-specific concerns?
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="scrubnurseReview" id="operteam_scrubnurseReview" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box.">
                                                <label for="operteam_scrubnurseReview">Nursing team reviews: &nbsp;</label>Instrument sterility confirmed, implants / prosthesis available / critical equipment available and functioning?
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="eight wide column">
                    <div class="ui segments">
                        <!-- <h5>DURING PROCEDURE <input type="text" class="form-control" id="operteam_procedure_hdr" name="procedure_hdr"></h5> -->
                        <div class="inline field">
                            <label>DURING PROCEDURE</label>
                            <!-- <input id="operteam_procedure_hdr" name="procedure_hdr" type="text" style="width: 380px; text-transform: uppercase;" data-validation="required" data-validation-error-msg-required="Please enter information." data-validation-error-msg-container="#error-procedure_hdr">
                            <div class="error-msg" id="error-procedure_hdr"></div> -->
                        </div>
                        <div class="ui secondary segment">INTRA-OPERATIVE COMMUNICATION</div>
                        <div class="ui segment" style="height: 600px;">
                            <div class="ui grid">
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <tbody>
                                        <!-- <tr>
                                            <td>INTRA-OPERATIVE COMMUNICATION</td>
                                        </tr> -->
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="checkin" id="operteam_checkin" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box.">
                                                <label for="operteam_checkin">Check-in</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="periodicUpdate" id="operteam_periodicUpdate" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box.">
                                                <label for="operteam_periodicUpdate">Periodic Updates</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="shoutOut" id="operteam_shoutOut" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box.">
                                                <label for="operteam_shoutOut">Shout-out</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="preclosure" id="operteam_preclosure" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box.">
                                                <label for="operteam_preclosure">Pre-closure disclosure</label>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="eight wide column">
                    <div class="ui segments">
                        <h5>BEFORE PATIENT LEAVES OPERATING ROOM</h5>
                        <div class="ui secondary segment">SIGN OUT</div>
                        <div class="ui segment" style="height: 600px;">
                            <div class="ui grid">
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <tbody>
                                        <!-- <tr>
                                            <td>SIGN OUT / Debriefing</td>
                                        </tr> -->
                                        <tr>
                                            <td><b>Nurse verbally confirms with the team:</b></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="finalProcedure" id="operteam_finalProcedure" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box.">
                                                <label for="operteam_finalProcedure">The final name of the procedure</label><br>(With proper spelling)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="finalCount" id="operteam_finalCount" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box.">
                                                <label for="operteam_finalCount">Final count of instruments, sponges and needles is correct</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="display: none;">
                                                <input type="checkbox" name="specimenlabel_lama" id="operteam_specimenlabel" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box.">
                                                <label for="operteam_specimenlabel">How specimens are labeled</label><br>(Including patient's name)
                                            </td>
                                            <td style="padding-left: 25px;">
                                                <b>How specimens are labeled</b><br>(Including patient's name)<br>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="specimenlabel" value="1" data-validation="required" data-validation-error-msg-required="Please check this box." data-validation-error-msg-container="#error-specimenlabel">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="specimenlabel" value="0">No
                                                </label>
                                                <div class="error-msg" id="error-specimenlabel"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="display: none;">
                                                <input type="checkbox" name="issuesAddressed_lama" id="operteam_issuesAddressed" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box.">
                                                <label for="operteam_issuesAddressed">Whether there are any equipment problems to be addressed</label><br>(Note in swab count form - incidents / equipment failure section)
                                            </td>
                                            <td style="padding-left: 25px;">
                                                <b>Whether there are any equipment problems to be addressed</b><br>(Note in swab count form - incidents / equipment failure section)<br>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="issuesAddressed" value="1" data-validation="required" data-validation-error-msg-required="Please check this box." data-validation-error-msg-container="#error-issuesAddressed">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="issuesAddressed" value="0">No
                                                </label>
                                                <div class="error-msg" id="error-issuesAddressed"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="display: none;">
                                                <!-- <input type="checkbox" name="specialinstruction" id="operteam_specialinstruction" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box.">
                                                <label for="operteam_specialinstruction">Any special instruction from surgeon or anaesthesia professional during recovery and management of patient</label> -->
                                                
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="specialinstruction_lama" id="operteam_specialinstruction" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box.">
                                                    <b>Any special instruction from surgeon or anaesthesia professional during recovery and management of patient</b>
                                                </label>
                                            </td>
                                            <td style="padding-left: 25px;">
                                                <b>Any special instruction from surgeon or anaesthesia professional during recovery and management of patient</b><br>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="specialinstruction" value="1" data-validation="required" data-validation-error-msg-required="Please check this box." data-validation-error-msg-container="#error-specialinstruction">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="specialinstruction" value="0">No
                                                </label>
                                                <div class="error-msg" id="error-specialinstruction"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="display: none;">
                                                <input type="checkbox" name="informRelative_lama" id="operteam_informRelative" value="1" style="margin-right: 10px;" data-validation="required" data-validation-error-msg-required="Please check this box.">
                                                <label for="operteam_informRelative">Inform the relatives</label>
                                            </td>
                                            <td style="padding-left: 25px;">
                                                <b>Inform the relatives</b><br>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="informRelative" value="1" data-validation="required" data-validation-error-msg-required="Please check this box." data-validation-error-msg-container="#error-informRelative">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="informRelative" value="0">No
                                                </label>
                                                <div class="error-msg" id="error-informRelative"></div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="eight wide column">
                    <div class="ui segments">
                        <!-- <div class="ui secondary segment">CHECKLIST CO-ORDINATOR</div> -->
                        <div class="ui segment">
                            <div class="ui grid">
                                <div class="ui form">
                                    <div class="inline field" style="margin: 10px 0;">
                                        <label>Checklist co-ordinator (Name)</label>
                                        <input id="operteam_coordinator" name="coordinator" type="text" style="width: 350px; text-transform: uppercase;" data-validation="required" data-validation-error-msg-required="Please enter information." data-validation-error-msg-container="#error-coordinator">
                                        <div class="error-msg" id="error-coordinator"></div>
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
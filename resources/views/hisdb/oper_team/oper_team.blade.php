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
                
                <div class="eight wide column">
                    <div class="ui segments">
                        <h5>BEFORE INDUCTION OF ANAESTHESIA</h5>
                        <div class="ui secondary segment">SIGN IN</div>
                        <div class="ui segment">
                            <div class="ui grid">
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <tbody>
                                        <!-- <tr>
                                            <td colspan="2">SIGN IN</td>
                                            <td></td>
                                        </tr> -->
                                        <tr>
                                            <td colspan="2">
                                                <input type="checkbox" name="confirmed_pt" id="confirmed_pt" value="1" style="margin-right: 10px;">
                                                <b>Checked patient's</b><br>
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
                                                    <input type="radio" name="op_site_mark" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="op_site_mark" value="0">No &nbsp; &nbsp;
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="op_site_na" name="op_site_na" value="1">NA
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="machine_check" id="machine_check" value="1" style="margin-right: 10px;">
                                                <b>Checked GA machine</b>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="pulseoximeter" id="pulseoximeter" value="1" style="margin-right: 10px;">
                                                <b>Pulse oximeter on patient and functioning</b>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding-left: 30px;">
                                                <b>Checked patient's</b><br><br>
                                                Allergy?<br>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="pt_allergy" value="0">No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="pt_allergy" value="1">Yes &nbsp; &nbsp;
                                                </label>
                                                <br>Airway/Aspiration risk?<br>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="diff_airway" value="0">No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="diff_airway" value="1">Yes &nbsp; &nbsp;
                                                </label>
                                                <br><br>Risk of > 500ml blood loss (adult) (> 7ml/kg in children)?<br>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="bloodloss" value="0">No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="bloodloss" value="1">Yes &nbsp; &nbsp;
                                                </label>
                                                <br>Adequate IV access?<br>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="iv_access" value="0">No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="iv_access" value="1">Yes &nbsp; &nbsp;
                                                </label>
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
                        <div class="ui segment">
                            <div class="ui grid">
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <tbody>
                                        <!-- <tr>
                                            <td>SIGN OUT</td>
                                        </tr> -->
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="whiteboard" id="whiteboard" value="1" style="margin-right: 10px;">
                                                <b>"White board" written</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="intro_team" id="intro_team" value="1" style="margin-right: 10px;">
                                                <b>Team members have introduced themselves by name and role</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="bsi_confirmed_pt" id="bsi_confirmed_pt" value="1" style="margin-right: 10px;">
                                                <b>Surgeon, anaesthesia professional and nurse have verbally confirmed</b><br>
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
                                                    <input type="radio" name="antibio_prophy" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="antibio_prophy" value="0">No &nbsp; &nbsp;
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="antibio_prophy_na" name="antibio_prophy_na" value="1">Not applicable
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 25px;">
                                                <b>Is essential imaging displayed?</b><br>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="display_img_na" name="display_img_na" value="1">Not applicable
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="display_img" name="display_img" value="1">Yes
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="brief_surgeon" id="brief_surgeon" value="1" style="margin-right: 10px;">
                                                <b>Anticipated critical events<br>Surgeon reviews: </b>Any special steps, estimated duration, possible excessive blood loss?<br>
                                                <textarea class="form-control input-sm" id="surgeon_review_remark" name="surgeon_review_remark"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="anaesth_review" id="anaesth_review" value="1" style="margin-right: 10px;">
                                                <b>Anaesthesis team review: </b>Any patient-specific concerns?
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="scrubnurse_review" id="scrubnurse_review" value="1" style="margin-right: 10px;">
                                                <b>Nursing team reviews: </b>Instrument sterility confirmed, implants / prosthesis available / critical equipment available and functioning?
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
                        <!-- <h5>DURING PROCEDURE <input type="text" class="form-control" id="procedure_hdr" name="procedure_hdr"></h5> -->
                        <div class="inline field">
                            <label>DURING PROCEDURE</label>
                            <input id="procedure_hdr" name="procedure_hdr" type="text" style="width: 500px; text-transform: uppercase;">
                        </div>
                        <div class="ui secondary segment">INTRA-OPERATIVE COMMUNICATION</div>
                        <div class="ui segment">
                            <div class="ui grid">
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <tbody>
                                        <!-- <tr>
                                            <td>INTRA-OPERATIVE COMMUNICATION</td>
                                        </tr> -->
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="checkin" id="checkin" value="1" style="margin-right: 10px;">
                                                <b>Check-in</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="periodic_upd" id="periodic_upd" value="1" style="margin-right: 10px;">
                                                <b>Periodic Updates</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="shout_out" id="shout_out" value="1" style="margin-right: 10px;">
                                                <b>Shout-out</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="pre_disclosure" id="pre_disclosure" value="1" style="margin-right: 10px;">
                                                <b>Pre-closure disclosure</b>
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
                        <div class="ui segment">
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
                                                <input type="checkbox" name="final_procedure" id="final_procedure" value="1" style="margin-right: 10px;">
                                                <b>The final name of the procedure</b><br>(With proper spelling)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="final_count" id="final_count" value="1" style="margin-right: 10px;">
                                                <b>Final count of instruments, sponges and needles is correct</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="specimenlabel" id="specimenlabel" value="1" style="margin-right: 10px;">
                                                <b>How specimens are labeled</b><br>(Including patient's name)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="issues_addressed" id="issues_addressed" value="1" style="margin-right: 10px;">
                                                <b>Whether there are any equipment problems to be addressed</b><br>(Note in swab count form - incidents / equipment failure section)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="special_instruction" id="special_instruction" value="1" style="margin-right: 10px;">
                                                <b>Any special instruction from surgeon or anaesthesia professional during recovery and management of patient</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="inform_relative" id="inform_relative" value="1" style="margin-right: 10px;">
                                                <b>Inform the relatives</b>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
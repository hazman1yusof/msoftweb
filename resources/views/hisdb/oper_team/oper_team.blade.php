<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment">
        OPERATING TEAM
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
                    <div class="ui segments">
                        <div class="ui secondary segment">BEFORE INDUCTION OF ANAESTHESIA</div>
                        <div class="ui segment">
                            <div class="ui grid">
                                
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                SIGN IN <br>
                                                <i>(By Anaesthetist & Coordinator Nurse)</i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%">
                                                Confirmed patient's<br>
                                                <ul>
                                                    <li>Name</li>
                                                    <li>Planned procedure</li>
                                                    <li>Site/Side</li>
                                                    <li>Consent</li>
                                                </ul>
                                            </td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="confirmed_pt" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="confirmed_pt" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Op site marked</td>
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
                                            <td>GA machine and defib machine checked?</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="machine_chck" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="machine_chck" value="0">No &nbsp; &nbsp;
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="machine_na" name="machine_na" value="1">NA
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Haemodynamic monitor turn on and functioning?</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="monitor_on" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="monitor_on" value="0">No &nbsp; &nbsp;
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="monitor_na" name="monitor_na" value="1">NA
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Patient has allergy?<br>
                                                <label>If yes, please specify</label>
                                                <textarea class="form-control input-sm" id="allergy_remark" name="allergy_remark"></textarea>
                                            </td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="pt_allergy" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="pt_allergy" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Difficult airway/aspiration risk?</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="diff_airway" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="diff_airway" value="0">No &nbsp; &nbsp;
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="diff_airway_na" name="diff_airway_na" value="1">NA
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Any GXM/GSH</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="gxm_gsh" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="gxm_gsh" value="0">No &nbsp; &nbsp;
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="gxm_gsh_na" name="gxm_gsh_na" value="1">NA
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Adequate IV access?</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="iv_access" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="iv_access" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Suction apparatus checked & functioning</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="apparatus_chck" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="apparatus_chck" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>OT Table checked & functioning</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="ottable_chck" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="ottable_chck" value="0">No &nbsp; &nbsp;
                                                </label>
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
                        <div class="ui secondary segment">BEFORE SKIN INCISION</div>
                        <div class="ui segment">
                            <div class="ui grid">
                                
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                TIME OUT <br>
                                                <i>(By Surgeon, Anaesthetist & Scrub Nurse)</i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>"WHITE BOARD" written by Surgeon</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="board_surgeon" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="board_surgeon" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Introduce team members</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="intro_team" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="intro_team" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%">
                                                Confirmed patient's<br>
                                                <ul>
                                                    <li>Name</li>
                                                    <li>Planned procedure</li>
                                                    <li>Site/Side</li>
                                                    <li>Consent</li>
                                                </ul>
                                            </td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="bsi_confirmed_pt" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="bsi_confirmed_pt" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Antibiotic prophylaxis given within the last 60 minutes?</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="antibio_prophy" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="antibio_prophy" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Essential imaging displayed?</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="display_img" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="display_img" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Briefing by Surgeon: incision, critical steps, estimated duration and blood loss</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="brief_surgeon" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="brief_surgeon" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Anaesthesia review: Any patient-specific concern?</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="anaesth_review" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="anaesth_review" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Scrub Nurse review: Instrument/implant available? Equipment (diathermy, suction) ready</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="scrubnrse_review" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="scrubnrse_review" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Perfusionist review: Perfusion check and regime confirmed</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="pfusion_review" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="pfusion_review" value="0">No &nbsp; &nbsp;
                                                </label>
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
                        <div class="ui secondary segment">CHECK IN</div>
                        <div class="ui segment">
                            <div class="ui grid">
                                
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <tbody>
                                        <tr>
                                            <td width="50%">Surgeon inform anaesthetist & Scrub Nurse of his/her intention to start</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="surgeon_start" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="surgeon_start" value="0">No &nbsp; &nbsp;
                                                </label>
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
                        <div class="ui secondary segment">DURING PROCEDURE</div>
                        <div class="ui segment">
                            <div class="ui grid">
                                
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                INTRA-OPERATIVE COMMUNICATION <br>
                                                <i>(By Surgeon, Anaesthetist & Scrub Nurse)</i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%">PERIODIC UPDATES</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="periodic_upd" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="periodic_upd" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SHOUT-OUT</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="shout_out" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="shout_out" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>PRE-CLOSURE DISCLOSURE</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="pre_disclsre" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="pre_disclsre" value="0">No &nbsp; &nbsp;
                                                </label>
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
                        <div class="ui secondary segment">BEFORE SURGEON LEAVES OPERATING ROOM</div>
                        <div class="ui segment">
                            <div class="ui grid">
                                
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                SIGN OUT / Debriefing <br>
                                                <i>(By Surgeon / Checklist Coordinator Nurse)</i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%">The final procedure, findings and post-op orders</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="final_procdre" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="final_procdre" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Final instrument & swab count was done</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="final_count" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="final_count" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Specimen(s) to be labelled</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="specimen_lbl" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="specimen_lbl" value="0">No &nbsp; &nbsp;
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="specimen_lbl_na" name="specimen_lbl_na" value="1">NA
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Any incidents or issues to be addressed?</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="incident_addr" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="incident_addr" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Any special post op instruction by anaesthetist or surgeon?</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="postop_instr" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="postop_instr" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Inform relative(s)<br>
                                                <label>If no, why?</label>
                                                <textarea class="form-control input-sm" id="relative_remark" name="relative_remark"></textarea>
                                            </td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="inform_relative" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="inform_relative" value="0">No &nbsp; &nbsp;
                                                </label>
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
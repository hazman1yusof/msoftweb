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
                                            <input type="radio" name="confirmedPt" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="confirmedPt" value="0">No &nbsp; &nbsp;
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Op site marked</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="opSite_mark" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="opSite_mark" value="0">No &nbsp; &nbsp;
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" id="operteam_opSite_na" name="opSite_na" value="1">NA
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>GA machine and defib machine checked?</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="machine_check" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="machine_check" value="0">No &nbsp; &nbsp;
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" id="operteam_machine_na" name="machine_na" value="1">NA
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
                                            <input type="checkbox" id="operteam_monitor_na" name="monitor_na" value="1">NA
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Patient has allergy?<br>
                                        If yes, please specify
                                        <textarea class="form-control input-sm" id="operteam_allergy_remark" name="allergy_remark"></textarea>
                                    </td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="ptAllergy" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="ptAllergy" value="0">No &nbsp; &nbsp;
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Difficult airway/aspiration risk?</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="difficultAirway" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="difficultAirway" value="0">No &nbsp; &nbsp;
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" id="operteam_difficultAirway_na" name="difficultAirway_na" value="1">NA
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Any GXM/GSH</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="gxmgsh" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="gxmgsh" value="0">No &nbsp; &nbsp;
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" id="operteam_gxmgsh_na" name="gxmgsh_na" value="1">NA
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Adequate IV access?</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="ivAccess" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="ivAccess" value="0">No &nbsp; &nbsp;
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Suction apparatus checked & functioning</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="apparatus_check" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="apparatus_check" value="0">No &nbsp; &nbsp;
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>OT Table checked & functioning</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="otTable_check" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="otTable_check" value="0">No &nbsp; &nbsp;
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
                                            <input type="radio" name="whiteboard" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="whiteboard" value="0">No &nbsp; &nbsp;
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Introduce team members</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="introTeam" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="introTeam" value="0">No &nbsp; &nbsp;
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
                                            <input type="radio" name="bsi_confirmedPt" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="bsi_confirmedPt" value="0">No &nbsp; &nbsp;
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Antibiotic prophylaxis given within the last 60 minutes?</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="antibioProphy" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="antibioProphy" value="0">No &nbsp; &nbsp;
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Essential imaging displayed?</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="displayImg" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="displayImg" value="0">No &nbsp; &nbsp;
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Briefing by Surgeon: incision, critical steps, estimated duration and blood loss</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="briefSurgeon" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="briefSurgeon" value="0">No &nbsp; &nbsp;
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Anaesthesia review: Any patient-specific concern?</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="anaesthReview" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="anaesthReview" value="0">No &nbsp; &nbsp;
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Scrub Nurse review: Instrument/implant available? Equipment (diathermy, suction) ready</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="scrubnurseReview" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="scrubnurseReview" value="0">No &nbsp; &nbsp;
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Perfusionist review: Perfusion check and regime confirmed</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="perfusionistReview" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="perfusionistReview" value="0">No &nbsp; &nbsp;
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
                                            <input type="radio" name="surgeonStart" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="surgeonStart" value="0">No &nbsp; &nbsp;
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
                                            <input type="radio" name="periodicUpdate" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="periodicUpdate" value="0">No &nbsp; &nbsp;
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>SHOUT-OUT</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="shoutOut" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="shoutOut" value="0">No &nbsp; &nbsp;
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>PRE-CLOSURE DISCLOSURE</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="preclosure" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="preclosure" value="0">No &nbsp; &nbsp;
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
                                            <input type="radio" name="finalProcedure" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="finalProcedure" value="0">No &nbsp; &nbsp;
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Final instrument & swab count was done</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="finalCount" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="finalCount" value="0">No &nbsp; &nbsp;
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Specimen(s) to be labelled</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="specimenlabel" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="specimenlabel" value="0">No &nbsp; &nbsp;
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" id="operteam_specimenlabel_na" name="specimenlabel_na" value="1">NA
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Any incidents or issues to be addressed?</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="issuesAddressed" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="issuesAddressed" value="0">No &nbsp; &nbsp;
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Any special post op instruction by anaesthetist or surgeon?</td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="specialinstruction" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="specialinstruction" value="0">No &nbsp; &nbsp;
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Inform relative(s)<br>
                                        If no, why?
                                        <textarea class="form-control input-sm" id="operteam_relative_remark" name="relative_remark"></textarea>
                                    </td>
                                    <td>
                                        <label class="radio-inline" style="padding-left: 30px;">
                                            <input type="radio" name="informRelative" value="1">Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="informRelative" value="0">No &nbsp; &nbsp;
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
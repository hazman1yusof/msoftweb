<!-- yang lama starts -->
<table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
    <thead>
        <tr>
            <th>No</th>
            <th width="60%"> </th>
            <th>Ward</th>
            <th>OT</th>
            <th width="25%">Remark</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Patient's Name/Unknown
                <label class="checkbox-inline" style="padding-left: 30px;">
                    <input type="checkbox" name="patID" value="1">Patient's ID
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" name="use2iden" value="1">(use two identifiers)
                </label>
            </td>
            <td><input type="checkbox" name="pat_ward" value="1"></td>
            <td><input type="checkbox" name="pat_ot" value="1"></td>
            <td><textarea class="form-control input-sm" id="preop_pat_remark" name="pat_remark"></textarea></td>
        </tr>
        <tr>
            <td>2</td>
            <td>
                <div>
                    <div style="width: 45%; float: left;">
                        Consent for:<br>
                        <input type="checkbox" name="consentSurgery" value="1"> &nbsp; Surgery<br>
                        <input type="checkbox" name="consentAnaesth" value="1"> &nbsp; Anaesthesia<br>
                        <input type="checkbox" name="consentTransf" value="1"> &nbsp; Transfusion<br>
                        <input type="checkbox" name="consentPhoto" value="1"> &nbsp; Photo<br>
                    </div>
                    <div style="width: 45%; float: right;">
                        Re-check procedure with:<br>
                        <input type="checkbox" name="checkForm" value="1"> &nbsp; Consent Form<br>
                        <input type="checkbox" name="checkPat" value="1"> &nbsp; Patient<br>
                        <input type="checkbox" name="checkList" value="1"> &nbsp; OT List<br>
                    </div>
                </div>
            </td>
            <td><input type="checkbox" name="consent_ward" value="1"></td>
            <td><input type="checkbox" name="consent_ot" value="1"></td>
            <td><textarea class="form-control input-sm" id="preop_consent_remark" name="consent_remark"></textarea></td>
        </tr>
        <tr>
            <td>3</td>
            <td>Check side of operation
                <label class="checkbox-inline" style="padding-left: 30px;">
                    <input type="checkbox" name="checkSide_left" value="1">Left
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" name="checkSide_right" value="1">Right
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" name="checkSide_na" value="1">NA
                </label>
            </td>
            <td><input type="checkbox" name="checkSide_ward" value="1"></td>
            <td><input type="checkbox" name="checkSide_ot" value="1"></td>
            <td><textarea class="form-control input-sm" id="preop_checkSide_remark" name="checkSide_remark"></textarea></td>
        </tr>
        <tr>
            <td>4</td>
            <td>Side of operation marked?
                <label class="radio-inline" style="padding-left: 30px;">
                    <input type="radio" name="opSite_mark" value="1">Yes
                </label>
                <label class="radio-inline">
                    <input type="radio" name="opSite_mark" value="0">No &nbsp; &nbsp;
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" id="preop_opSite_na" name="opSite_na" value="1">NA
                </label>
            </td>
            <td><input type="checkbox" name="opSite_ward" value="1"></td>
            <td><input type="checkbox" name="opSite_ot" value="1"></td>
            <td><textarea class="form-control input-sm" id="preop_opSite_remark" name="opSite_remark"></textarea></td>
        </tr>
        <tr>
            <td>5</td>
            <td>
                <div class="form-inline"> Last meal &nbsp;
                    <div class="form-group">
                        <input type="date" class="form-control" id="preop_lastmeal_date" name="lastmeal_date">
                    </div>  &nbsp;
                    <div class="form-group">
                        <input type="time" class="form-control" id="preop_lastmeal_time" name="lastmeal_time">
                    </div>
                </div>
            </td>
            <td><input type="checkbox" name="lastmeal_ward" value="1"></td>
            <td><input type="checkbox" name="lastmeal_ot" value="1"></td>
            <td><textarea class="form-control input-sm" id="preop_lastmeal_remark" name="lastmeal_remark"></textarea></td>
        </tr>
        <tr>
            <td>6</td>
            <td>Check for dentures, jewellery, contact lenses, implant/foreign body etc.<br>
                <i>(for person in charge of removing the item(s), to write their name and quantity of item in the remarks)</i><br>
                <label class="checkbox-inline" style="padding-left: 30px;">
                    <input type="checkbox" name="checkItem_na" value="1">NA
                </label>
            </td>
            <td><input type="checkbox" name="checkItem_ward" value="1"></td>
            <td><input type="checkbox" name="checkItem_ot" value="1"></td>
            <td><textarea class="form-control input-sm" id="preop_checkItem_remark" name="checkItem_remark"></textarea></td>
        </tr>
        <tr>
            <td>7</td>
            <td>Allergies?
                <label class="radio-inline" style="padding-left: 30px;">
                    <input type="radio" name="allergies" value="1">Yes
                </label>
                <label class="radio-inline">
                    <input type="radio" name="allergies" value="0">No
                </label>
            </td>
            <td><input type="checkbox" name="allergies_ward" value="1"></td>
            <td><input type="checkbox" name="allergies_ot" value="1"></td>
            <td><textarea class="form-control input-sm" id="preop_allergies_remark" name="allergies_remark"></textarea></td>
        </tr>
        <tr>
            <td>8</td>
            <td>Availability of implant/prosthesis?
                <label class="radio-inline" style="padding-left: 30px;">
                    <input type="radio" name="implantAvailable" value="1">Yes
                </label>
                <label class="radio-inline">
                    <input type="radio" name="implantAvailable" value="0">No
                </label>
            </td>
            <td><input type="checkbox" name="implant_ward" value="1"></td>
            <td><input type="checkbox" name="implant_ot" value="1"></td>
            <td><textarea class="form-control input-sm" id="preop_implant_remark" name="implant_remark"></textarea></td>
        </tr>
        <tr>
            <td>9</td>
            <td>Premedication (drug,dose,route and time given)  &nbsp;
                <label class="checkbox-inline">
                    <input type="checkbox" name="premed_na" value="1">NA
                </label>
            </td>
            <td><input type="checkbox" name="premed_ward" value="1"></td>
            <td><input type="checkbox" name="premed_ot" value="1"></td>
            <td><textarea class="form-control input-sm" id="preop_premed_remark" name="premed_remark"></textarea></td>
        </tr>
        <tr>
            <td>10</td>
            <td>Blood or Blood product availability (write what is available)  &nbsp;
                <label class="checkbox-inline">
                    <input type="checkbox" name="blood_na" value="1">NA
                </label>
            </td>
            <td><input type="checkbox" name="blood_ward" value="1"></td>
            <td><input type="checkbox" name="blood_ot" value="1"></td>
            <td><textarea class="form-control input-sm" id="preop_blood_remark" name="blood_remark"></textarea></td>
        </tr>
        <tr>
            <td>11</td>
            <td>Case notes  &nbsp;
                <label class="checkbox-inline">
                    <input type="checkbox" name="casenotes_na" value="1">NA
                </label>
            </td>
            <td><input type="checkbox" name="casenotes_ward" value="1"></td>
            <td><input type="checkbox" name="casenotes_ot" value="1"></td>
            <td><textarea class="form-control input-sm" id="preop_casenotes_remark" name="casenotes_remark"></textarea></td>
        </tr>
        <tr>
            <td>12</td>
            <td>Old notes  &nbsp;
                <label class="checkbox-inline">
                    <input type="checkbox" name="oldnotes_na" value="1">NA
                </label>
            </td>
            <td><input type="checkbox" name="oldnotes_ward" value="1"></td>
            <td><input type="checkbox" name="oldnotes_ot" value="1"></td>
            <td><textarea class="form-control input-sm" id="preop_oldnotes_remark" name="oldnotes_remark"></textarea></td>
        </tr>
        <tr>
            <td>13</td>
            <td>Imaging Study <small>(To specify other document(s) and type of imaging studies in the remarks)</small>  &nbsp;
                <label class="checkbox-inline">
                    <input type="checkbox" name="imaging_na" value="1">NA
                </label>
            </td>
            <td><input type="checkbox" name="imaging_ward" value="1"></td>
            <td><input type="checkbox" name="imaging_ot" value="1"></td>
            <td><textarea class="form-control input-sm" id="preop_imaging_remark" name="imaging_remark"></textarea></td>
        </tr>
        <tr>
            <!-- vital sign -->
            <td>14</td>
            <td>
                <div class="form-inline"> B/P:  &nbsp;
                    <div class="form-group">
                        <input type="text" class="form-control" id="preop_bpsys1" name="bpsys1" size="4">
                    </div> &nbsp; / &nbsp;
                    <div class="form-group">
                        <input type="text" class="form-control" id="preop_bpdias" name="bpdias" size="4">
                    </div> &nbsp; mmHg &nbsp; &nbsp;
                    Pulse rate:  &nbsp;
                    <div class="form-group">
                        <input type="text" class="form-control" id="preop_pulse" name="pulse" size="4">
                    </div> &nbsp; bpm &nbsp; &nbsp;
                    Temperature:  &nbsp;
                    <div class="form-group">
                        <input type="text" class="form-control" id="preop_temperature" name="temperature" size="4">
                    </div> &nbsp; °C &nbsp; &nbsp;
                </div>
            </td>
            <td><input type="checkbox" name="vs_ward" value="1"></td>
            <td><input type="checkbox" name="vs_ot" value="1"></td>
            <td><textarea class="form-control input-sm" id="preop_vs_remark" name="vs_remark"></textarea></td>
        </tr>
        <tr>
            <td>15</td>
            <td>Others:  &nbsp;
                <label class="checkbox-inline">
                    <input type="checkbox" name="others_na" value="1">NA
                </label>
            </td>
            <td><input type="checkbox" name="others_ward" value="1"></td>
            <td><input type="checkbox" name="others_ot" value="1"></td>
            <td><textarea class="form-control input-sm" id="preop_others_remark" name="others_remark"></textarea></td>
        </tr>
    </tbody>
</table>
<!-- yang lama ends -->

<div class="field ten wide column" style="margin: 0px; padding: 3px 14px 14px 14px; display: none;">
    <label>Any important issues to be highlighted</label>
    <textarea id="preop_importantIssues" name="importantIssues"></textarea>
</div>
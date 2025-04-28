<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment">
        PRE OPERATIVE CHECKLIST (DAYCARE)
        <div class="ui small blue icon buttons" id="btn_grp_edit_preoperativeDC" style="position: absolute;
                    padding: 0 0 0 0;
                    right: 40px;
                    top: 9px;
                    z-index: 2;">
            <button class="ui button" id="new_preoperativeDC"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_preoperativeDC"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_preoperativeDC"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_preoperativeDC"><span class="fa fa-ban fa-lg"></span>Cancel</button>
        </div>
    </div>
    
    <div class="ui segment" style="padding: 10px 10px 30px 30px;">
        <form id="form_preoperativeDC" class="ui form">
            <div class="ui grid">
                <input id="mrn_preoperativeDC" name="mrn_preoperativeDC" type="hidden">
                <input id="episno_preoperativeDC" name="episno_preoperativeDC" type="hidden">
                
                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">PRE OPERATIVE CHECKLIST (DAYCARE)</div>
                        <div class="ui segment">
                            <div class="ui grid">

                                <div class="sixteen wide column">
                                    <div class="ui segments">
                                        <div class="ui segment">

                                            <div class="ui grid">
                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>Surgeon</label>
                                                    <input type="text" id="surgeonDC" name="surgeonDC">
                                                </div>

                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label>Anaesthetist</label>
                                                    <input type="text" id="anaestDC" name="anaestDC">
                                                </div>
                                            </div>

                                            <div class="ui grid">
                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>Type of Operation</label>
                                                    <input type="text" id="natureoperDC" name="natureoperDC">
                                                </div>

                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label>Date</label>
                                                    <input type="date" class="form-control" id="operdateDC" name="operdateDC">
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th width="40%">Description</th>
                                            <th>Ward</th>
                                            <th>Reception</th>
                                            <th>Theatre</th>
                                            <th width="30%">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1.</td>
                                            <td>Identification Bracelet Checked</td>
                                            <td><input type="checkbox" name="idBracelet_ward" value="1"></td>
                                            <td><input type="checkbox" name="idBracelet_rec" value="1"></td>
                                            <td><input type="checkbox" name="idBracelet_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="idBracelet_remarks" name="idBracelet_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>2.</td>
                                            <td>Operation Site</td>
                                            <td><input type="checkbox" name="operSite_ward" value="1"></td>
                                            <td><input type="checkbox" name="operSite_rec" value="1"></td>
                                            <td><input type="checkbox" name="operSite_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="operSite_remarks" name="operSite_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>3.</td>
                                            <td>
                                                <div class="form-inline">Fasted from &nbsp;
                                                    <div class="form-group">
                                                        <input type="time" class="form-control" id="fasted_time_from" name="fasted_time_from">
                                                    </div>
                                                    <!-- <div class="form-group">
                                                        <input type="time" class="form-control" id="fasted_time_until" name="fasted_time_until">
                                                    </div>  &nbsp;
                                                    @ &nbsp;
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="fasted_hours" name="fasted_hours">
                                                    </div> &nbsp;hours -->
                                                </div>
                                            </td>
                                            <td><input type="checkbox" name="fasted_ward" value="1"></td>
                                            <td><input type="checkbox" name="fasted_rec" value="1"></td>
                                            <td><input type="checkbox" name="fasted_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="fasted_remarks" name="fasted_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>4.</td>
                                            <td>Consent Valid</td>
                                            <td><input type="checkbox" name="consentValid_ward" value="1"></td>
                                            <td><input type="checkbox" name="consentValid_rec" value="1"></td>
                                            <td><input type="checkbox" name="consentValid_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="consentValid_remarks" name="consentValid_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>5.</td>
                                            <td>Consent Anaest</td>
                                            <td><input type="checkbox" name="consentAnaest_ward" value="1"></td>
                                            <td><input type="checkbox" name="consentAnaest_rec" value="1"></td>
                                            <td><input type="checkbox" name="consentAnaest_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="consentAnaest_remarks" name="consentAnaest_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>6.</td>
                                            <td>OT Gowned</td>
                                            <td><input type="checkbox" name="otGown_ward" value="1"></td>
                                            <td><input type="checkbox" name="otGown_rec" value="1"></td>
                                            <td><input type="checkbox" name="otGown_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="otGown_remarks" name="otGown_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>7.</td>
                                            <td>Shaving Done</td>
                                            <td><input type="checkbox" name="shaving_ward" value="1"></td>
                                            <td><input type="checkbox" name="shaving_rec" value="1"></td>
                                            <td><input type="checkbox" name="shaving_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="shaving_remarks" name="shaving_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>8.</td>
                                            <td>Bowel Preparation Done</td>
                                            <td><input type="checkbox" name="bowelPrep_ward" value="1"></td>
                                            <td><input type="checkbox" name="bowelPrep_rec" value="1"></td>
                                            <td><input type="checkbox" name="bowelPrep_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="bowelPrep_remarks" name="bowelPrep_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>9.</td>
                                            <td>Bladder Emptied</td>
                                            <td><input type="checkbox" name="bladder_ward" value="1"></td>
                                            <td><input type="checkbox" name="bladder_rec" value="1"></td>
                                            <td><input type="checkbox" name="bladder_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="bladder_remarks" name="bladder_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>10.</td>
                                            <td>Dentures Upper/Lower Removed</td>
                                            <td><input type="checkbox" name="dentures_ward" value="1"></td>
                                            <td><input type="checkbox" name="dentures_rec" value="1"></td>
                                            <td><input type="checkbox" name="dentures_theatre" value="1"></td>
                                            <td>Keep by: <textarea class="form-control input-sm" id="dentures_remarks" name="dentures_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>11.</td>
                                            <td>Contact Lens/Implant/Spectacles Removed</td>
                                            <td><input type="checkbox" name="lensImpSpec_ward" value="1"></td>
                                            <td><input type="checkbox" name="lensImpSpec_rec" value="1"></td>
                                            <td><input type="checkbox" name="lensImpSpec_theatre" value="1"></td>
                                            <td>Keep by: <textarea class="form-control input-sm" id="lensImpSpec_remarks" name="lensImpSpec_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>12.</td>
                                            <td>Nail Varnish Removed</td>
                                            <td><input type="checkbox" name="nailVarnish_ward" value="1"></td>
                                            <td><input type="checkbox" name="nailVarnish_rec" value="1"></td>
                                            <td><input type="checkbox" name="nailVarnish_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="nailVarnish_remarks" name="nailVarnish_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>13.</td>
                                            <td>Hair Clips Removed</td>
                                            <td><input type="checkbox" name="hairClips_ward" value="1"></td>
                                            <td><input type="checkbox" name="hairClips_rec" value="1"></td>
                                            <td><input type="checkbox" name="hairClips_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="hairClips_remarks" name="hairClips_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>14.</td>
                                            <td>Valueables/Jewelry Removed</td>
                                            <td><input type="checkbox" name="valuables_ward" value="1"></td>
                                            <td><input type="checkbox" name="valuables_rec" value="1"></td>
                                            <td><input type="checkbox" name="valuables_theatre" value="1"></td>
                                            <td>Keep by: <textarea class="form-control input-sm" id="valuables_remarks" name="valuables_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>15.</td>
                                            <td>IV Fluids Infused</td>
                                            <td><input type="checkbox" name="ivFluids_ward" value="1"></td>
                                            <td><input type="checkbox" name="ivFluids_rec" value="1"></td>
                                            <td><input type="checkbox" name="ivFluids_theatre" value="1"></td>
                                            <td>Keep by: <textarea class="form-control input-sm" id="ivFluids_remarks" name="ivFluids_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>16.</td>
                                            <td>
                                                <div class="form-inline"> Pre Medication Given @  &nbsp;
                                                    <div class="form-group">
                                                        <input type="time" class="form-control" id="premedGiven_hours" name="premedGiven_hours">
                                                    </div> &nbsp;hours
                                                </div>
                                            </td>
                                            <td><input type="checkbox" name="premedGiven_ward" value="1"></td>
                                            <td><input type="checkbox" name="premedGiven_rec" value="1"></td>
                                            <td><input type="checkbox" name="premedGiven_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="premedGiven_remarks" name="premedGiven_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>17.</td>
                                            <td>Medication Chart</td>
                                            <td><input type="checkbox" name="medChart_ward" value="1"></td>
                                            <td><input type="checkbox" name="medChart_rec" value="1"></td>
                                            <td><input type="checkbox" name="medChart_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="medChart_remarks" name="medChart_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>18.</td>
                                            <td>Case Note</td>
                                            <td><input type="checkbox" name="caseNote_ward" value="1"></td>
                                            <td><input type="checkbox" name="caseNote_rec" value="1"></td>
                                            <td><input type="checkbox" name="caseNote_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="caseNote_remarks" name="caseNote_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>19.</td>
                                            <td>Old Notes/X-Ray/CT/MRI film</td>
                                            <td><input type="checkbox" name="oldNotes_ward" value="1"></td>
                                            <td><input type="checkbox" name="oldNotes_rec" value="1"></td>
                                            <td><input type="checkbox" name="oldNotes_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="oldNotes_remarks" name="oldNotes_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>20.</td>
                                            <td>Patient's belongings to</td>
                                            <td><input type="checkbox" name="ptBelongings_ward" value="1"></td>
                                            <td><input type="checkbox" name="ptBelongings_rec" value="1"></td>
                                            <td><input type="checkbox" name="ptBelongings_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="ptBelongings_remarks" name="ptBelongings_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>21.</td>
                                            <td>Allergies</td>
                                            <td><input type="checkbox" name="allergies_ward" value="1"></td>
                                            <td><input type="checkbox" name="allergies_rec" value="1"></td>
                                            <td><input type="checkbox" name="allergies_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="allergies_remarks" name="allergies_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>22.</td>
                                            <td>Medico-legal case</td>
                                            <td><input type="checkbox" name="medLegalCase_ward" value="1"></td>
                                            <td><input type="checkbox" name="medLegalCase_rec" value="1"></td>
                                            <td><input type="checkbox" name="medLegalCase_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="medLegalCase_remarks" name="medLegalCase_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Checked by <b>(Staff's Name)</b></td>
                                            <td><input type="text" name="checkedBy_ward" id="checkedBy_ward"></td>
                                            <td><input type="text" name="checkedBy_rec" id="checkedBy_rec"></td>
                                            <td><input type="text" name="checkedBy_theatre" id="checkedBy_theatre"></td>
                                            <td><textarea class="form-control input-sm" id="checkedBy_remarks" name="checkedBy_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Date</td>
                                            <td><input type="date" class="form-control" id="checkedDate_ward" name="checkedDate_ward"></td>
                                            <td><input type="date" class="form-control" id="checkedDate_rec" name="checkedDate_rec"></td>
                                            <td><input type="date" class="form-control" id="checkedDate_theatre" name="checkedDate_theatre"></td>
                                            <td><textarea class="form-control input-sm" id="checkedDate_remarks" name="checkedDate_remarks"></textarea></td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <div class="sixteen wide column" style="display:none">
                                    <div class="ui segments">
                                        <div class="ui segment">
                                            <div class="ui grid">
                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>Checked by <b>(Staff's Name)</b></label>
                                                    <input type="text" id="checkedBy" name="checkedBy">
                                                </div>

                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label>Date</label>
                                                    <input type="date" class="form-control" id="checkedDate" name="checkedDate">
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

                                <table class="ui celled table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <thead>
                                        <tr>
                                            <th width="25%"></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>With Doctor</th>
                                            <th width="30%">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><b>Blood Test</b></td>
                                            <td><input type="checkbox" name="bloodTest_1" id="bloodTest_1" value="1"></td>
                                            <td><input type="checkbox" name="bloodTest_2" id="bloodTest_2" value="1"></td>
                                            <td><input type="checkbox" name="bloodTest_3" id="bloodTest_3" value="1"></td>
                                            <td><input type="checkbox" name="bloodTest_4" id="bloodTest_4" value="1"></td>
                                            <td><input type="checkbox" name="bloodTest_doc" id="bloodTest_doc" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="bloodTest_remarks" name="bloodTest_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><b>Group And Cross Match</b></td>
                                            <td><input type="checkbox" name="grpCrossMatch_1" id="grpCrossMatch_1" value="1"></td>
                                            <td><input type="checkbox" name="grpCrossMatch_2" id="grpCrossMatch_2" value="1"></td>
                                            <td><input type="checkbox" name="grpCrossMatch_3" id="grpCrossMatch_3" value="1"></td>
                                            <td><input type="checkbox" name="grpCrossMatch_4" id="grpCrossMatch_4" value="1"></td>
                                            <td><input type="checkbox" name="grpCrossMatch_doc" id="grpCrossMatch_doc" value="1"></td>
                                            <td>Availability:<textarea class="form-control input-sm" id="grpCrossMatch_remarks" name="grpCrossMatch_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><b>ECG</b></td>
                                            <td><input type="checkbox" name="ecg_1" id="ecg_1" value="1"></td>
                                            <td><input type="checkbox" name="ecg_2" id="ecg_2" value="1"></td>
                                            <td><input type="checkbox" name="ecg_3" id="ecg_3" value="1"></td>
                                            <td><input type="checkbox" name="ecg_4" id="ecg_4" value="1"></td>
                                            <td><input type="checkbox" name="ecg_doc" id="ecg_doc" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="ecg_remarks" name="ecg_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><b>XRAY/CT/MRI/ANGIO Films</b></td>
                                            <td><input type="checkbox" name="xray_1" id="xray_1" value="1"></td>
                                            <td><input type="checkbox" name="xray_2" id="xray_2" value="1"></td>
                                            <td><input type="checkbox" name="xray_3" id="xray_3" value="1"></td>
                                            <td><input type="checkbox" name="xray_4" id="xray_4" value="1"></td>
                                            <td><input type="checkbox" name="xray_doc" id="xray_doc" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="xray_remarks" name="xray_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><b>CTG</b></td>
                                            <td><input type="checkbox" name="ctg_1" id="ctg_1" value="1"></td>
                                            <td><input type="checkbox" name="ctg_2" id="ctg_2" value="1"></td>
                                            <td><input type="checkbox" name="ctg_3" id="ctg_3" value="1"></td>
                                            <td><input type="checkbox" name="ctg_4" id="ctg_4" value="1"></td>
                                            <td><input type="checkbox" name="ctg_doc" id="ctg_doc" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="ctg_remarks" name="ctg_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>
                                                    Vital Sign<br/><br/>&nbsp;&nbsp;&nbsp;
                                                    BP:<br/><br/>&nbsp;&nbsp;&nbsp;
                                                    P:
                                                </b>
                                            </td>
                                            <td><br/>
                                                <input type="text" name="vsbp_1" id="vsbp_1">&nbsp;&nbsp;&nbsp;<input type="text" name="vsp_1" id="vsp_1">
                                            </td>
                                            <td><br/>
                                                <input type="text" name="vsbp_2" id="vsbp_2">&nbsp;&nbsp;&nbsp;<input type="text" name="vsp_2" id="vsp_2">
                                            </td>                                            
                                            <td><br/>
                                                <input type="text" name="vsbp_3" id="vsbp_3">&nbsp;&nbsp;&nbsp;<input type="text" name="vsp_3" id="vsp_3">
                                            </td>                                            
                                            <td><br/>
                                                <input type="text" name="vsbp_4" id="vsbp_4">&nbsp;&nbsp;&nbsp;<input type="text" name="vsp_4" id="vsp_4">
                                            </td>                                            
                                            <td><br/>
                                                <input type="text" name="vsbp_doc" id="vsbp_doc">&nbsp;&nbsp;&nbsp;<input type="text" name="vsp_doc" id="vsp_doc">
                                            </td>
                                            <td><br/><textarea rows="4" class="form-control input-sm" id="vs_remarks" name="vs_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><b>Others</b></td>
                                            <td><input type="text" name="others_1" id="others_1"></td>
                                            <td><input type="text" name="others_2" id="others_2"></td>
                                            <td><input type="text" name="others_3" id="others_3"></td>
                                            <td><input type="text" name="others_4" id="others_4"></td>
                                            <td><input type="text" name="others_doc" id="others_doc"></td>
                                            <td><textarea class="form-control input-sm" id="others_remarks" name="others_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><b>Completed By</b></td>
                                            <td><input type="text" name="completedBy_1" id="completedBy_1"></td>
                                            <td><input type="text" name="completedBy_2" id="completedBy_2"></td>
                                            <td><input type="text" name="completedBy_3" id="completedBy_3"></td>
                                            <td><input type="text" name="completedBy_4" id="completedBy_4"></td>
                                            <td><input type="text" name="completedBy_doc" id="completedBy_doc"></td>
                                            <td><textarea class="form-control input-sm" id="completedBy_remarks" name="completedBy_remarks"></textarea></td>
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
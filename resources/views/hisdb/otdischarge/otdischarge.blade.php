<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment">
        PRE-DISCHARGE CHECK
        <div class="ui small blue icon buttons" id="btn_grp_edit_otdischarge" style="position: absolute;
                    padding: 0 0 0 0;
                    right: 40px;
                    top: 9px;
                    z-index: 2;">
            <button class="ui button" id="new_otdischarge"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_otdischarge"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_otdischarge"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_otdischarge"><span class="fa fa-ban fa-lg"></span>Cancel</button>
        </div>
    </div>
    
    <div class="ui segment" style="padding: 10px 10px 30px 30px;">
        <form id="form_otdischarge" class="ui form">
            <div class="ui grid">
                <input id="mrn_otdischarge" name="mrn_otdischarge" type="hidden">
                <input id="episno_otdischarge" name="episno_otdischarge" type="hidden">
                
                <div class="sixteen wide column">
                    <div class="ui segment">
                        <div class="inline fields">
                            <label>iPesakit</label>
                            <div class="field"><input type="text" class="form-control" id="predischg_iPesakit" name="iPesakit"></div>
                        </div>
                    </div>
                </div>
                
                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui segment">
                            <div class="ui grid">
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th width="60%"></th>
                                            <th>Checked</th>
                                            <th width="40%">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <label for="predischg_patName" class="checkbox-inline" style="padding-left: 0px;">Patient's name</label>
                                                <input type="checkbox" name="patName" id="predischg_patName" value="1">
                                                
                                                <label for="predischg_identitytag" class="checkbox-inline">Identity tag</label>
                                                <input type="checkbox" name="identitytag" id="predischg_identitytag" value="1">
                                            </td>
                                            <td><input type="checkbox" name="pat_checked" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="predischg_pat_remark" name="pat_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Consciousness level : <br>
                                                <label class="checkbox-inline" style="padding-left: 30px; padding-top: 5px;">
                                                    <input type="checkbox" name="consciousAlert" value="1">Alert
                                                </label>
                                                <label class="checkbox-inline" style="padding-top: 5px;">
                                                    <input type="checkbox" name="consciousDrowsy" value="1">Drowsy
                                                </label>
                                                <label class="checkbox-inline" style="padding-top: 5px;">
                                                    <input type="checkbox" name="consciousIntubated" value="1">Intubated
                                                </label>
                                            </td>
                                            <td><input type="checkbox" name="consciouslvl_checked" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="predischg_consciouslvl_remark" name="consciouslvl_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Inform vital signs & pain score</td>
                                            <td><input type="checkbox" name="vitalsign_checked" value="1"></td>
                                            <td>
                                                <div class="form-inline"> B/P:  &nbsp;
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="predischg_bpsys1" name="bpsys1" size="4">
                                                    </div> &nbsp; / &nbsp;
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="predischg_bpdias" name="bpdias" size="4">
                                                    </div> &nbsp; mmHg &nbsp; &nbsp;
                                                    P/S:  &nbsp;
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="predischg_painscore" name="painscore" size="4">
                                                    </div> &nbsp; /10 &nbsp; &nbsp;
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Check operative site / dressing</td>
                                            <td><input type="checkbox" name="checksite_checked" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="predischg_checksite_remark" name="checksite_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Check drains, tubes and urinary catheter</td>
                                            <td><input type="checkbox" name="checkdrains_checked" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="predischg_checkdrains_remark" name="checkdrains_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>Check IV lines and infusions</td>
                                            <td><input type="checkbox" name="checkiv_checked" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="predischg_checkiv_remark" name="checkiv_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>Blood used and unused</td>
                                            <td><input type="checkbox" name="blood_checked" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="predischg_blood_remark" name="blood_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>Specimens</td>
                                            <td><input type="checkbox" name="specimen_checked" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="predischg_specimen_remark" name="specimen_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>
                                                <label for="predischg_casenotes" class="checkbox-inline" style="padding-left: 0px;">Case Notes</label>
                                                <input type="checkbox" name="casenotes" id="predischg_casenotes" value="1">
                                                
                                                <label for="predischg_oldnotes" class="checkbox-inline">Old Notes</label>
                                                <input type="checkbox" name="oldnotes" id="predischg_oldnotes" value="1">
                                                
                                                <label for="predischg_xrays" class="checkbox-inline">X-rays</label>
                                                <input type="checkbox" name="xrays" id="predischg_xrays" value="1">
                                                
                                                <br>
                                                
                                                <label for="predischg_opernotes" class="checkbox-inline" style="padding-left: 0px;">Operatives Notes</label>
                                                <input type="checkbox" name="opernotes" id="predischg_opernotes" value="1">
                                                
                                                <label for="predischg_gaform" class="checkbox-inline">GA Form</label>
                                                <input type="checkbox" name="gaform" id="predischg_gaform" value="1">
                                            </td>
                                            <td><input type="checkbox" name="docs_checked" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="predischg_docs_remark" name="docs_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>Check post-operative pain relief order</td>
                                            <td><input type="checkbox" name="painrelief_checked" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="predischg_painrelief_remark" name="painrelief_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>11</td>
                                            <td><textarea class="form-control input-sm" id="predischg_addmore1" name="addmore1"></textarea></td>
                                            <td><input type="checkbox" name="addmore1_checked" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="predischg_addmore1_remark" name="addmore1_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>12</td>
                                            <td><textarea class="form-control input-sm" id="predischg_addmore2" name="addmore2"></textarea></td>
                                            <td><input type="checkbox" name="addmore2_checked" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="predischg_addmore2_remark" name="addmore2_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>13</td>
                                            <td><textarea class="form-control input-sm" id="predischg_addmore3" name="addmore3"></textarea></td>
                                            <td><input type="checkbox" name="addmore3_checked" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="predischg_addmore3_remark" name="addmore3_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>14</td>
                                            <td><textarea class="form-control input-sm" id="predischg_addmore4" name="addmore4"></textarea></td>
                                            <td><input type="checkbox" name="addmore4_checked" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="predischg_addmore4_remark" name="addmore4_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>15</td>
                                            <td><textarea class="form-control input-sm" id="predischg_addmore5" name="addmore5"></textarea></td>
                                            <td><input type="checkbox" name="addmore5_checked" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="predischg_addmore5_remark" name="addmore5_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>16</td>
                                            <td><textarea class="form-control input-sm" id="predischg_addmore6" name="addmore6"></textarea></td>
                                            <td><input type="checkbox" name="addmore6_checked" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="predischg_addmore6_remark" name="addmore6_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <div class="form-inline" style="text-align: center;"> OT Nurse <span style="margin-left: 20px;"> :  &nbsp;
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="predischg_otNurse" name="otNurse" style="text-transform: uppercase;">
                                                    </div> &nbsp; &nbsp; &nbsp;
                                                    Ward Nurse &nbsp; &nbsp; &nbsp; :  &nbsp;
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="predischg_wardNurse" name="wardNurse" style="text-transform: uppercase;">
                                                    </div>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <div class="form-inline" style="text-align: center;"> Date <span style="margin-left: 20px;"> :  &nbsp;
                                                    <div class="form-group">
                                                        <input type="date" class="form-control" id="predischg_entereddate" name="entereddate">
                                                    </div> &nbsp; &nbsp; &nbsp;
                                                    Time &nbsp; &nbsp; &nbsp; :  &nbsp;
                                                    <div class="form-group">
                                                        <input type="time" class="form-control" id="predischg_enteredtime" name="enteredtime">
                                                    </div>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
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
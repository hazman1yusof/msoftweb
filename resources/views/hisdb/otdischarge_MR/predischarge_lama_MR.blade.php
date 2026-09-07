<form id="form_otdischarge" class="ui form">
    <div class="ui grid">
        <input id="mrn_otdischarge" name="mrn_otdischarge" type="hidden">
        <input id="episno_otdischarge" name="episno_otdischarge" type="hidden">
        
        <div class="sixteen wide column">
            <div class="ui segments">
                <div class="ui segment">
                    <div class="ui grid">
                        <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th width="60%"></th>
                                    <th>OT</th>
                                    <th>Ward</th>
                                    <th width="25%">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Patient's name/unknown <br>
                                        <label class="checkbox-inline" style="padding-left: 30px; padding-top: 5px;">
                                            <input type="checkbox" name="patID" value="1">Patient's ID
                                        </label>
                                        <label class="checkbox-inline" style="padding-top: 5px;">
                                            <input type="checkbox" name="use2iden" value="1">(use two identifiers)
                                        </label>
                                    </td>
                                    <td><input type="checkbox" name="pat_ot" value="1"></td>
                                    <td><input type="checkbox" name="pat_ward" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_pat_remark" name="pat_remark"></textarea></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Consciousness level: <br>
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
                                    <td><input type="checkbox" name="consciouslvl_ot" value="1"></td>
                                    <td><input type="checkbox" name="consciouslvl_ward" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_consciouslvl_remark" name="consciouslvl_remark"></textarea></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Inform vital signs (BP, PR, SpO2) & Pain Score</td>
                                    <td><input type="checkbox" name="vitalsign_ot" value="1"></td>
                                    <td><input type="checkbox" name="vitalsign_ward" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_vitalsign_remark" name="vitalsign_remark"></textarea></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Check operative site/dressing</td>
                                    <td><input type="checkbox" name="checksite_ot" value="1"></td>
                                    <td><input type="checkbox" name="checksite_ward" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_checksite_remark" name="checksite_remark"></textarea></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Check drains, tubes, urinary catheter</td>
                                    <td><input type="checkbox" name="checkdrains_ot" value="1"></td>
                                    <td><input type="checkbox" name="checkdrains_ward" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_checkdrains_remark" name="checkdrains_remark"></textarea></td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Check IV lines and infusions</td>
                                    <td><input type="checkbox" name="checkiv_ot" value="1"></td>
                                    <td><input type="checkbox" name="checkiv_ward" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_checkiv_remark" name="checkiv_remark"></textarea></td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>Blood used and unused</td>
                                    <td><input type="checkbox" name="blood_ot" value="1"></td>
                                    <td><input type="checkbox" name="blood_ward" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_blood_remark" name="blood_remark"></textarea></td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>Specimens (culture(s) etc.)</td>
                                    <td><input type="checkbox" name="specimen_ot" value="1"></td>
                                    <td><input type="checkbox" name="specimen_ward" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_specimen_remark" name="specimen_remark"></textarea></td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>
                                        <div>
                                            <div style="width: 30%;float: left;">
                                                <input type="checkbox" name="casenotes" value="1"> &nbsp; Case Notes<br>
                                                <input type="checkbox" name="otherdocs" value="1"> &nbsp; Other Document(s)<br>
                                                <input type="checkbox" name="gaform" value="1"> &nbsp; GA Form<br>
                                            </div>
                                            <div style="width: 70%;float: left;">
                                                <input type="checkbox" name="oldnotes" value="1"> &nbsp; Relevent Old Notes<br>
                                                <input type="checkbox" name="opernotes" value="1"> &nbsp; Operatives Notes<br>
                                                <br>
                                            </div>
                                        </div>
                                        <small>(To specify other document(s) and type of imaging studies in the remarks)</small>
                                    </td>
                                    <td><input type="checkbox" name="docs_ward" value="1"></td>
                                    <td><input type="checkbox" name="docs_ot" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_docs_remark" name="docs_remark"></textarea></td>
                                </tr>
                                <tr>
                                    <td>10</td>
                                    <td>Imaging studies</td>
                                    <td><input type="checkbox" name="imgstudies_ot" value="1"></td>
                                    <td><input type="checkbox" name="imgstudies_ward" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_imgstudies_remark" name="imgstudies_remark"></textarea></td>
                                </tr>
                                <tr>
                                    <td>11</td>
                                    <td>Check post-operative pain relief order</td>
                                    <td><input type="checkbox" name="painrelief_ot" value="1"></td>
                                    <td><input type="checkbox" name="painrelief_ward" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_painrelief_remark" name="painrelief_remark"></textarea></td>
                                </tr>
                                <tr>
                                    <td>12</td>
                                    <td>Others, e.g.: amputated part, placenta etc.</td>
                                    <td><input type="checkbox" name="others_ot" value="1"></td>
                                    <td><input type="checkbox" name="others_ward" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_others_remark" name="others_remark"></textarea></td>
                                </tr>
                                <tr>
                                    <td>13</td>
                                    <td>Arterial Line (should be removed if not needed)</td>
                                    <td><input type="checkbox" name="arterial_ot" value="1"></td>
                                    <td><input type="checkbox" name="arterial_ward" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_arterial_remark" name="arterial_remark"></textarea></td>
                                </tr>
                                <tr>
                                    <td>14</td>
                                    <td>PCA pump or epidural checked</td>
                                    <td><input type="checkbox" name="pcapump_ot" value="1"></td>
                                    <td><input type="checkbox" name="pcapump_ward" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_pcapump_remark" name="pcapump_remark"></textarea></td>
                                </tr>
                                <tr>
                                    <td>15</td>
                                    <td><textarea class="form-control input-sm" id="predischg_addmore1" name="addmore1"></textarea></td>
                                    <td><input type="checkbox" name="addmore1_ot" value="1"></td>
                                    <td><input type="checkbox" name="addmore1_ward" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_addmore1_remark" name="addmore1_remark"></textarea></td>
                                </tr>
                                <tr>
                                    <td>16</td>
                                    <td><textarea class="form-control input-sm" id="predischg_addmore2" name="addmore2"></textarea></td>
                                    <td><input type="checkbox" name="addmore2_ot" value="1"></td>
                                    <td><input type="checkbox" name="addmore2_ward" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_addmore2_remark" name="addmore2_remark"></textarea></td>
                                </tr>
                                <tr>
                                    <td>17</td>
                                    <td><textarea class="form-control input-sm" id="predischg_addmore3" name="addmore3"></textarea></td>
                                    <td><input type="checkbox" name="addmore3_ot" value="1"></td>
                                    <td><input type="checkbox" name="addmore3_ward" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_addmore3_remark" name="addmore3_remark"></textarea></td>
                                </tr>
                                <tr>
                                    <td>18</td>
                                    <td><textarea class="form-control input-sm" id="predischg_addmore4" name="addmore4"></textarea></td>
                                    <td><input type="checkbox" name="addmore4_ot" value="1"></td>
                                    <td><input type="checkbox" name="addmore4_ward" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_addmore4_remark" name="addmore4_remark"></textarea></td>
                                </tr>
                                <tr>
                                    <td>19</td>
                                    <td><textarea class="form-control input-sm" id="predischg_addmore5" name="addmore5"></textarea></td>
                                    <td><input type="checkbox" name="addmore5_ot" value="1"></td>
                                    <td><input type="checkbox" name="addmore5_ward" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_addmore5_remark" name="addmore5_remark"></textarea></td>
                                </tr>
                                <tr>
                                    <td>20</td>
                                    <td><textarea class="form-control input-sm" id="predischg_addmore6" name="addmore6"></textarea></td>
                                    <td><input type="checkbox" name="addmore6_ot" value="1"></td>
                                    <td><input type="checkbox" name="addmore6_ward" value="1"></td>
                                    <td><textarea class="form-control input-sm" id="predischg_addmore6_remark" name="addmore6_remark"></textarea></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
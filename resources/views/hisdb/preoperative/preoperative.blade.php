<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment">
        PRE-OPERATIVE CHECKLIST
        <div class="ui small blue icon buttons" id="btn_grp_edit_preoperative" style="position: absolute;
                    padding: 0 0 0 0;
                    right: 40px;
                    top: 9px;
                    z-index: 2;">
            <button class="ui button" id="new_preoperative"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_preoperative"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_preoperative"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_preoperative"><span class="fa fa-ban fa-lg"></span>Cancel</button>
        </div>
    </div>
    
    <div class="ui segment" style="padding: 10px 10px 30px 30px;">
        <form id="form_preoperative" class="ui form">
            <div class="ui grid">
                <input id="mrn_preoperative" name="mrn_preoperative" type="hidden">
                <input id="episno_preoperative" name="episno_preoperative" type="hidden">
                
                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">
                            PATIENT PROFILE <br> (To be filled by Ward Staff)
                        </div>
                        <div class="ui segment">
                            <div class="ui grid">
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="form-inline"> Reg. No <span style="margin-left: 41px;"> :  &nbsp;
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="pt_regno" name="regno">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-inline"> Diagnosis <span style="margin-left: 30px;"> :  &nbsp;
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="pt_diagnosis" name="diagnosis" size="120">
                                                        <!-- <textarea id="pt_diagnosis" name="diagnosis" type="text" rows="5"></textarea> -->
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-inline"> Operation <span style="margin-left: 28px;"> :  &nbsp;
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="pt_operation" name="operation" size="120">
                                                        <!-- <textarea id="pt_operation" name="operation" type="text" rows="5"></textarea> -->
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-inline"> Checked by (Ward Staff) <span style="margin-left: 20px;"> :  &nbsp;
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="pt_checkby" name="checkby" style="text-transform: uppercase;">
                                                    </div> &nbsp; &nbsp; &nbsp;
                                                    Date &nbsp; &nbsp; &nbsp; :  &nbsp;
                                                    <div class="form-group">
                                                        <input type="date" class="form-control" id="pt_date" name="date">
                                                    </div> &nbsp; &nbsp; &nbsp;
                                                    Contact person & HP No. &nbsp; &nbsp; &nbsp; :  &nbsp;
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="pt_contactperson" name="contactperson" size="40">
                                                    </div>
                                                </div>
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
                        <div class="ui secondary segment">
                            PRE-TRANSFER CHECK <br> (Is done by the Ward Nurse before sending patient to OT and at Reception Area in OT by the OT Reception Nurse)
                        </div>
                        <div class="ui segment">
                            <div class="ui grid">
                                <!-- yang baru -->
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
                                            <td>
                                                <label class="checkbox-inline" style="padding-left: 0px;">Patient's Name</label>
                                                <input type="checkbox" name="pat_name" id="preop_pat_name" value="1">
                                                
                                                <label class="checkbox-inline">Identity Tag</label>
                                                <input type="checkbox" name="identitytag" id="preop_identitytag" value="1">
                                            </td>
                                            <td><input type="checkbox" name="pat_ward" value="1"></td>
                                            <td><input type="checkbox" name="pat_ot" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="pat_remark" name="pat_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Consent for
                                                <input type="checkbox" name="cons_surgery" id="preop_cons_surgery" value="1" style="margin-left: 15px;">
                                                <label class="checkbox-inline" style="padding-left: 5px;">Surgery</label>
                                                
                                                <input type="checkbox" name="cons_anaes" id="preop_cons_anaes" value="1" style="margin-left: 15px;">
                                                <label class="checkbox-inline" style="padding-left: 5px;">Anaestesia</label>
                                                
                                                <input type="checkbox" name="cons_trans" id="preop_cons_trans" value="1" style="margin-left: 15px;">
                                                <label class="checkbox-inline" style="padding-left: 5px;">Transfusion</label>
                                            </td>
                                            <td><input type="checkbox" name="cons_ward" value="1"></td>
                                            <td><input type="checkbox" name="cons_ot" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="cons_remark" name="cons_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Check <b>side</b> of operation
                                                <label class="checkbox-inline" style="padding-left: 30px;">
                                                    <input type="checkbox" name="check_side_left" value="1">Left
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="check_side_right" value="1">Right
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="check_side_na" value="1">NA
                                                </label>
                                            </td>
                                            <td><input type="checkbox" name="check_side_ward" value="1"></td>
                                            <td><input type="checkbox" name="check_side_ot" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="check_side_remark" name="check_side_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Site (location) of operation marked?
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="side_op_mark" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="side_op_mark" value="0">No &nbsp; &nbsp;
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="side_op_na" name="side_op_na" value="1">NA
                                                </label>
                                            </td>
                                            <td><input type="checkbox" name="side_op_ward" value="1"></td>
                                            <td><input type="checkbox" name="side_op_ot" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="side_op_remark" name="side_op_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>
                                                <div class="form-inline"> Last meal : &nbsp;
                                                    Date &nbsp;
                                                    <div class="form-group">
                                                        <input type="date" class="form-control" id="lastmeal_date" name="lastmeal_date">
                                                    </div>  &nbsp;
                                                    Time &nbsp;
                                                    <div class="form-group">
                                                        <input type="time" class="form-control" id="lastmeal_time" name="lastmeal_time">
                                                    </div>
                                                </div>
                                            </td>
                                            <td><input type="checkbox" name="lastmeal_ward" value="1"></td>
                                            <td><input type="checkbox" name="lastmeal_ot" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="lastmeal_remark" name="lastmeal_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>Check for dentures, jewellery, contact lenses etc. :</td>
                                            <td><input type="checkbox" name="check_item_ward" value="1"></td>
                                            <td><input type="checkbox" name="check_item_ot" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="check_item_remark" name="check_item_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>Premedication (write drug given)</td>
                                            <td><input type="checkbox" name="premed_ward" value="1"></td>
                                            <td><input type="checkbox" name="premed_ot" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="premed_remark" name="premed_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>Blood availability (write what is available)</td>
                                            <td><input type="checkbox" name="blood_ward" value="1"></td>
                                            <td><input type="checkbox" name="blood_ot" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="blood_remark" name="blood_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>
                                                <label class="checkbox-inline" style="padding-left: 0px;">Case notes</label>
                                                <input type="checkbox" name="casenotes" id="preop_casenotes" value="1">
                                                
                                                <label class="checkbox-inline">Old notes</label>
                                                <input type="checkbox" name="oldnotes" id="preop_oldnotes" value="1">
                                                
                                                <label class="checkbox-inline">X-rays</label>
                                                <input type="checkbox" name="xrays" id="preop_xrays" value="1">
                                            </td>
                                            <td><input type="checkbox" name="casenotes_ward" value="1"></td>
                                            <td><input type="checkbox" name="casenotes_ot" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="casenotes_remark" name="casenotes_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>
                                                <div class="form-inline"> B/P:  &nbsp;
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="bp_sys1" name="bp_sys1" size="4">
                                                    </div> &nbsp; / &nbsp;
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="bp_dias" name="bp_dias" size="4">
                                                    </div> &nbsp; mmHg &nbsp; &nbsp;
                                                    Pulse rate:  &nbsp;
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="pulse" name="pulse" size="4">
                                                    </div> &nbsp; bpm &nbsp; &nbsp;
                                                </div>
                                            </td>
                                            <td><input type="checkbox" name="vs_ward" value="1"></td>
                                            <td><input type="checkbox" name="vs_ot" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="vs_remark" name="vs_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>11</td>
                                            <td>Pre-operation visit done by surgeon</td>
                                            <td><input type="checkbox" name="preopvisit_ward" value="1"></td>
                                            <td><input type="checkbox" name="preopvisit_ot" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="preopvisit_remark" name="preopvisit_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td colspan="4">
                                                <div class="form-inline">
                                                    <i>Handed over by (Ward Nurse): </i> &nbsp;
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="wardnurse" name="wardnurse" size="120" style="text-transform: uppercase;">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-inline">
                                                    <i>Received by (OT Nurse): </i> &nbsp;
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="otnurse" name="otnurse" size="120" style="text-transform: uppercase;">
                                                    </div>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>12</td>
                                            <td colspan="4">
                                                <div class="form-inline">
                                                    Last Menstrual Period (LMP) &nbsp;
                                                    <div class="form-group">
                                                        <input type="date" class="form-control" id="lmp" name="lmp">
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
                
                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">
                            INFORMATION ON OPERATING ROOM / SURGEON / TIME OF SURGERY <br> (Written in OR by Circulating Nurse)
                        </div>
                        <div class="ui segment">
                            <div class="ui grid">
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="form-inline"> Operating room no <span style="margin-left: 10px;"> :  &nbsp;
                                                    <div class="form-group">
                                                        <select name="info_otroom" id="info_otroom" class="form-control input-sm">
                                                            <option value=""></option>
                                                            @foreach($otroom as $obj)
                                                                <option value="{{$obj->resourcecode}}">{{$obj->description}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-inline"> Anaesthetist <span style="margin-left: 50px;"> :  &nbsp;
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="desc_anaesthetist" id="desc_anaesthetist" size="40" tabindex=4>
                                                        <input type="hidden" id="info_anaesthetist" name="info_anaesthetist"/>
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-info" id="btn_anaesthetist" data-toggle="modal" onclick_xguna="pop_item_select('anaesthetist');"><span class="fa fa-ellipsis-h"></span></button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-inline"> Surgeons <span style="margin-left: 71px;"> :  &nbsp;
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="desc_surgeon" id="desc_surgeon" size="40" tabindex=4>
                                                        <input type="hidden" id="info_surgeon" name="info_surgeon"/>
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-info" id="btn_surgeon" data-toggle="modal" onclick_xguna="pop_item_select('surgeon');"><span class="fa fa-ellipsis-h"></span></button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-inline"> Time start <span style="margin-left: 65px;"> :  &nbsp;
                                                    <div class="form-group">
                                                        <input type="time" class="form-control" id="info_starttime" name="starttime">
                                                    </div> &nbsp; &nbsp; &nbsp;
                                                    Time complete &nbsp; &nbsp; &nbsp; :  &nbsp;
                                                    <div class="form-group">
                                                        <input type="time" class="form-control" id="info_endtime" name="endtime">
                                                    </div>
                                                </div>
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
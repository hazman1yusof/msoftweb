<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment">
        PRE OPERATIVE
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
                        <div class="ui secondary segment">PRE-TRANSFER CHECK</div>
                        <div class="ui segment">
                            <div class="ui grid">
                                
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
                                                    <input type="checkbox" name="pat_id" value="1">Patient's ID
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="use2iden" value="1">(use two identifiers)
                                                </label>
                                            </td>
                                            <td><input type="checkbox" name="pat_ward" value="1"></td>
                                            <td><input type="checkbox" name="pat_ot" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="pat_remark" name="pat_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <div>
                                                    <div style="width: 45%;float: left;">
                                                        Consent for:<br>
                                                        <input type="checkbox" name="cons_surgery" value="1"> &nbsp; Surgery<br>
                                                        <input type="checkbox" name="cons_anaes" value="1"> &nbsp; Anaestesia<br>
                                                        <input type="checkbox" name="cons_trans" value="1"> &nbsp; Transfusion<br>
                                                        <input type="checkbox" name="cons_photo" value="1"> &nbsp; Photo<br>
                                                    </div>
                                                    <div style="width: 45%;float: right;">
                                                        Re-check procedure with:<br>
                                                        <input type="checkbox" name="check_form" value="1"> &nbsp; Consent Form<br>
                                                        <input type="checkbox" name="check_pat" value="1"> &nbsp; Patient<br>
                                                        <input type="checkbox" name="check_list" value="1"> &nbsp; OT List<br>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><input type="checkbox" name="cons_ward" value="1"></td>
                                            <td><input type="checkbox" name="cons_ot" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="cons_remark" name="cons_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Check side of operation
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
                                            <td>Side of operation marked?
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
                                                <div class="form-inline"> Last meal &nbsp;
                                                    <div class="form-group">
                                                        <input type="date" class="form-control" id="lastmeal_date" name="lastmeal_date">
                                                    </div>  &nbsp;
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
                                            <td>Check for dentures, jewellery, contact lenses, implant/foreign body etc.<br>
                                                <i>(for person in charge of removing the item(s), to write their name and quantity of item in the remarks)</i><br>
                                                <label class="checkbox-inline" style="padding-left: 30px;">
                                                    <input type="checkbox" name="check_item_na" value="1">NA
                                                </label>
                                            </td>
                                            <td><input type="checkbox" name="check_item_ward" value="1"></td>
                                            <td><input type="checkbox" name="check_item_ot" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="check_item_remark" name="check_item_remark"></textarea></td>
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
                                            <td><textarea class="form-control input-sm" id="allergies_remark" name="allergies_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>Availability of implant/prosthesis?
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="implant_avlblt" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="implant_avlblt" value="0">No
                                                </label>
                                            </td>
                                            <td><input type="checkbox" name="implant_ward" value="1"></td>
                                            <td><input type="checkbox" name="implant_ot" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="implant_remark" name="implant_remark"></textarea></td>
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
                                            <td><textarea class="form-control input-sm" id="premed_remark" name="premed_remark"></textarea></td>
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
                                            <td><textarea class="form-control input-sm" id="blood_remark" name="blood_remark"></textarea></td>
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
                                            <td><textarea class="form-control input-sm" id="casenotes_remark" name="casenotes_remark"></textarea></td>
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
                                            <td><textarea class="form-control input-sm" id="oldnotes_remark" name="oldnotes_remark"></textarea></td>
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
                                            <td><textarea class="form-control input-sm" id="imaging_remark" name="imaging_remark"></textarea></td>
                                        </tr>
                                        <tr>
                                            <!-- vital sign -->
                                            <td>14</td>
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
                                                    Temperature:  &nbsp;
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="temperature" name="temperature" size="4">
                                                    </div> &nbsp; °C &nbsp; &nbsp;
                                                </div>
                                            </td>
                                            <td><input type="checkbox" name="vs_ward" value="1"></td>
                                            <td><input type="checkbox" name="vs_ot" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="vs_remark" name="vs_remark"></textarea></td>
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
                                            <td><textarea class="form-control input-sm" id="others_remark" name="others_remark"></textarea></td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <div class="field ten wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                    <label>Any important issues to be highlighted</label>
                                    <textarea id="imprtnt_issues" name="imprtnt_issues"></textarea>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">INFORMATION ON OPERATING ROOM / SURGEON / TIME OF SURGERY</div>
                        <div class="ui segment">
                            <div class="ui grid">
                                
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="form-inline"> Temperature <span style="margin-left: 48px;"> :  &nbsp;
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="info_temperature" name="info_temperature" size="4">
                                                    </div> &nbsp; °C &nbsp; &nbsp; <span style="margin-left: 60px;">
                                                    Humidity &nbsp; &nbsp; &nbsp; :  &nbsp;
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="info_humidity" name="info_humidity" size="4">
                                                    </div> &nbsp; <span class="glyphicon glyphicon-tint"> &nbsp; &nbsp;
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-inline"> OT Room <span style="margin-left: 69px;"> :  &nbsp;
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
                                                <div class="form-inline"> Anaesthetist(s) <span style="margin-left: 34px;"> :  &nbsp;
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="desc_anaesthetist" id="desc_anaesthetist" tabindex=4>
                                                        <input type="hidden" id="info_anaesthetist" name="info_anaesthetist"/>
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-info" id="btn_anaesthetist" data-toggle="modal" onclick_xguna="pop_item_select('anaesthetist');"><span class="fa fa-ellipsis-h"></span></button>
                                                        </span>
                                                        
                                                        <!-- <div class="ui action input">
                                                            <input type="text" id="info_anaesthetist" name="info_anaesthetist">
                                                            <a class="ui icon blue button"><i class="fa fa-ellipsis-h"></i></a>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-inline"> Surgeon <span style="margin-left: 76px;"> :  &nbsp;
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="desc_surgeon" id="desc_surgeon" tabindex=4>
                                                        <input type="hidden" id="info_surgeon" name="info_surgeon"/>
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-info" id="btn_surgeon" data-toggle="modal" onclick_xguna="pop_item_select('surgeon');"><span class="fa fa-ellipsis-h"></span></button>
                                                        </span>

                                                        <!-- <div class="ui action input">
                                                            <input type="text" id="info_surgeon" name="info_surgeon">
                                                            <a class="ui icon blue button"><i class="fa fa-ellipsis-h"></i></a>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-inline"> Asst. Surgeon <span style="margin-left: 44px;"> :  &nbsp;
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="desc_asstsurgeon" id="desc_asstsurgeon" tabindex=4>
                                                        <input type="hidden" id="info_asstsurgeon" name="info_asstsurgeon"/>
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-info" id="btn_asstsurgeon" data-toggle="modal" onclick_xguna="pop_item_select('asstsurgeon');"><span class="fa fa-ellipsis-h"></span></button>
                                                        </span>

                                                        <!-- <div class="ui action input">
                                                            <input type="text" id="info_asstsurgeon" name="info_asstsurgeon">
                                                            <a class="ui icon blue button"><i class="fa fa-ellipsis-h"></i></a>
                                                        </div> -->
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
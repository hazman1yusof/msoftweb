<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment">
        PRE OPERATIVE (DAYCARE)
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
                        <div class="ui secondary segment">PRE-TRANSFER CHECK</div>
                        <div class="ui segment">
                            <div class="ui grid">
                                
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th width="60%"> </th>
                                            <th>Ward</th>
                                            <th>Reception</th>
                                            <th>Theatre</th>
                                            <th width="25%">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Identification Bracelet Checked</td>
                                            <td><input type="checkbox" name="idBracelet_ward" value="1"></td>
                                            <td><input type="checkbox" name="idBracelet_rec" value="1"></td>
                                            <td><input type="checkbox" name="idBracelet_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="idBracelet_remarks" name="idBracelet_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Operation Site</td>
                                            <td><input type="checkbox" name="operSite_ward" value="1"></td>
                                            <td><input type="checkbox" name="operSite_rec" value="1"></td>
                                            <td><input type="checkbox" name="operSite_theatre" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="operSite_remarks" name="operSite_remarks"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Fasted from
                                                <div class="form-inline"> &nbsp;
                                                    <div class="form-group">
                                                        <input type="time" class="form-control" id="lastmeal_date" name="lastmeal_date">
                                                    </div>  &nbsp;
                                                    <div class="form-group">
                                                        <input type="time" class="form-control" id="lastmeal_time" name="lastmeal_time">
                                                    </div>
                                                </div>
                                            </td>
                                            <td><input type="checkbox" name="check_side_ward" value="1"></td>
                                            <td><input type="checkbox" name="check_side_ot" value="1"></td>
                                            <td><textarea class="form-control input-sm" id="check_side_remark" name="check_side_remark"></textarea></td>
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
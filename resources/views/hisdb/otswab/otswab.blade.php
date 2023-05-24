<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment">
        SWAB & INSTRUMENT COUNT
        <div class="ui small blue icon buttons" id="btn_grp_edit_otswab" style="position: absolute;
                    padding: 0 0 0 0;
                    right: 40px;
                    top: 9px;
                    z-index: 2;">
            <button class="ui button" id="new_otswab"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_otswab"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_otswab"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_otswab"><span class="fa fa-ban fa-lg"></span>Cancel</button>
        </div>
    </div>
    
    <div class="ui segment" style="padding: 10px 10px 30px 30px;">
        <form id="form_otswab" class="ui form">
            <div class="ui grid">
                <input id="mrn_otswab" name="mrn_otswab" type="hidden">
                <input id="episno_otswab" name="episno_otswab" type="hidden">
                
                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui segment">
                            
                            <div class="ui grid">
                                <div class="field eight wide column" style="margin:0px; padding: 10px 14px;">
                                    <div class="form-inline">
                                        Date/Time Start <br>
                                        <i>(Incision/Procedure Time)</i> &nbsp;
                                        <div class="form-group">
                                            <input type="date" class="form-control" id="startdate" name="startdate">
                                        </div>  &nbsp;
                                        <div class="form-group">
                                            <input type="time" class="form-control" id="starttime" name="starttime">
                                        </div>
                                    </div>
                                </div>

                                <div class="field eight wide column" style="margin:0px; padding: 10px 14px;">
                                    <div class="form-inline">
                                        Date/Time End <br>
                                        <i>(Incision/Procedure Time)</i> &nbsp;
                                        <div class="form-group">
                                            <input type="date" class="form-control" id="enddate" name="enddate">
                                        </div>  &nbsp;
                                        <div class="form-group">
                                            <input type="time" class="form-control" id="endtime" name="endtime">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                
                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">SET & INSTRUMENTS</div>
                        <div class="ui segment">
                            <div class="ui grid">
                                
                                <div class="sixteen wide column">
                                    <div class="ui segments">
                                        <div class="ui segment">
                                            
                                            <div class="ui grid">
                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>Basic set used in procedure</label>
                                                    <textarea id="basicset" name="basicset" rows="4"></textarea>
                                                </div>

                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label>Supplementary set used in procedure</label>
                                                    <textarea id="spplmtryset" name="spplmtryset" rows="4"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="sixteen wide column">
                                    <div class="ui segments">
                                        <div class="ui segment" id="jqGrid_otswab_c">
                                            <table id="jqGrid_otswab" class="table table-striped"></table>
                                            <div id="jqGridPager_otswab"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="sixteen wide column">
                                    <div class="ui segments">
                                        <div class="ui segment">
                                            
                                            <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                                <tbody>
                                                    <tr>
                                                        <td width="23%">Issues/Incidents occured (If any)</td>
                                                        <td width="50%">
                                                            <textarea class="form-control input-sm" id="issue_occur" name="issue_occur" rows="4"></textarea>
                                                        </td>
                                                        <td width="60%"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Actual operation(s)/procedure(s) done</td>
                                                        <td>
                                                            <textarea class="form-control input-sm" id="actual_oper" name="actual_oper" rows="4"></textarea>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Specimen(s) sent</td>
                                                        <td>
                                                            <textarea class="form-control input-sm" id="specimensent" name="specimensent" rows="4" rdonly></textarea>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="sixteen wide column">
                                    <div class="ui segments">
                                        <div class="ui segment">
                                            
                                            <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                                <thead>
                                                    <tr>
                                                        <th> </th>
                                                        <th>Time Start</th>
                                                        <th>Time End</th>
                                                        <th> </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td width="35%">Name of 1st Scrub Nurse<br>
                                                            <input type="text" class="form-control" id="scrubnurse1" name="scrubnurse1">
                                                        </td>
                                                        <td width="20%" style="padding-top: 31px;">
                                                            <input type="time" class="form-control" id="scrubnrs1_start" name="scrubnrs1_start">
                                                        </td>
                                                        <td width="20%" style="padding-top: 31px;">
                                                            <input type="time" class="form-control" id="scrubnrs1_end" name="scrubnrs1_end">
                                                        </td>
                                                        <td width="25%"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name of 2nd Scrub Nurse<br>
                                                            <input type="text" class="form-control" id="scrubnurse2" name="scrubnurse2">
                                                        </td>
                                                        <td style="padding-top: 31px;">
                                                            <input type="time" class="form-control" id="scrubnrs2_start" name="scrubnrs2_start">
                                                        </td>
                                                        <td style="padding-top: 31px;">
                                                            <input type="time" class="form-control" id="scrubnrs2_end" name="scrubnrs2_end">
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name of 3rd Scrub Nurse<br>
                                                            <input type="text" class="form-control" id="scrubnurse3" name="scrubnurse3">
                                                        </td>
                                                        <td style="padding-top: 31px;">
                                                            <input type="time" class="form-control" id="scrubnrs3_start" name="scrubnrs3_start">
                                                        </td>
                                                        <td style="padding-top: 31px;">
                                                            <input type="time" class="form-control" id="scrubnrs3_end" name="scrubnrs3_end">
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name of 1st Circulating Nurse<br>
                                                            <input type="text" class="form-control" id="circltnurse1" name="circltnurse1">
                                                        </td>
                                                        <td style="padding-top: 31px;">
                                                            <input type="time" class="form-control" id="circltnrs1_start" name="circltnrs1_start">
                                                        </td>
                                                        <td style="padding-top: 31px;">
                                                            <input type="time" class="form-control" id="circltnrs1_end" name="circltnrs1_end">
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name of 2nd Circulating Nurse<br>
                                                            <input type="text" class="form-control" id="circltnurse2" name="circltnurse2">
                                                        </td>
                                                        <td style="padding-top: 31px;">
                                                            <input type="time" class="form-control" id="circltnrs2_start" name="circltnrs2_start">
                                                        </td>
                                                        <td style="padding-top: 31px;">
                                                            <input type="time" class="form-control" id="circltnrs2_end" name="circltnrs2_end">
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name of 3rd Circulating Nurse<br>
                                                            <input type="text" class="form-control" id="circltnurse3" name="circltnurse3">
                                                        </td>
                                                        <td style="padding-top: 31px;">
                                                            <input type="time" class="form-control" id="circltnrs3_start" name="circltnrs3_start">
                                                        </td>
                                                        <td style="padding-top: 31px;">
                                                            <input type="time" class="form-control" id="circltnrs3_end" name="circltnrs3_end">
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </form>
    </div>
</div>
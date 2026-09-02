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
                                <!-- <div class="form-group">
                                    <input type="time" class="form-control" id="starttime" name="starttime">
                                </div> -->
                                <div class="form-group" style="width:200px">
                                    <div class="ui calendar" id="starttime">
                                        <div class="ui input left icon">
                                            <i class="time icon"></i>
                                            <input type="text" class="form-control" name="starttime">
                                        </div>
                                    </div>
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
                                <!-- <div class="form-group">
                                    <input type="time" class="form-control" id="endtime" name="endtime">
                                </div> -->
                                <div class="form-group" style="width:200px">
                                    <div class="ui calendar" id="endtime">
                                        <div class="ui input left icon">
                                            <i class="time icon"></i>
                                            <input type="text" class="form-control" name="endtime">
                                        </div>
                                    </div>
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
                                            <textarea id="otswab_basicset" name="basicset" rows="4"></textarea>
                                        </div>
                                        
                                        <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                            <label>Supplementary set used in procedure</label>
                                            <textarea id="otswab_supplemntryset" name="supplemntryset" rows="4"></textarea>
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
                                                    <textarea class="form-control input-sm" id="otswab_issuesOccured" name="issuesOccured" rows="4"></textarea>
                                                </td>
                                                <td width="60%"></td>
                                            </tr>
                                            <tr>
                                                <td>Actual operation(s)/procedure(s) done</td>
                                                <td>
                                                    <textarea class="form-control input-sm" id="otswab_actualOper" name="actualOper" rows="4"></textarea>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Specimen(s) sent</td>
                                                <td>
                                                    <textarea class="form-control input-sm" id="otswab_specimenSent" name="specimenSent" rows="4" rdonly></textarea>
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
                                                    <input type="text" class="form-control" id="otswab_scrubNurse1" name="scrubNurse1" style="text-transform: uppercase;">
                                                </td>
                                                <td width="20%" style="padding-top: 31px;">
                                                    <input type="time" class="form-control" id="otswab_scrubNurse1Start" name="scrubNurse1Start">
                                                </td>
                                                <td width="20%" style="padding-top: 31px;">
                                                    <input type="time" class="form-control" id="otswab_scrubNurse1End" name="scrubNurse1End">
                                                </td>
                                                <td width="25%"></td>
                                            </tr>
                                            <tr>
                                                <td>Name of 2nd Scrub Nurse<br>
                                                    <input type="text" class="form-control" id="otswab_scrubNurse2" name="scrubNurse2" style="text-transform: uppercase;">
                                                </td>
                                                <td style="padding-top: 31px;">
                                                    <input type="time" class="form-control" id="otswab_scrubNurse2Start" name="scrubNurse2Start">
                                                </td>
                                                <td style="padding-top: 31px;">
                                                    <input type="time" class="form-control" id="otswab_scrubNurse2End" name="scrubNurse2End">
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Name of 3rd Scrub Nurse<br>
                                                    <input type="text" class="form-control" id="otswab_scrubNurse3" name="scrubNurse3" style="text-transform: uppercase;">
                                                </td>
                                                <td style="padding-top: 31px;">
                                                    <input type="time" class="form-control" id="otswab_scrubNurse3Start" name="scrubNurse3Start">
                                                </td>
                                                <td style="padding-top: 31px;">
                                                    <input type="time" class="form-control" id="otswab_scrubNurse3End" name="scrubNurse3End">
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Name of 1st Circulating Nurse<br>
                                                    <input type="text" class="form-control" id="otswab_circulateNurse1" name="circulateNurse1" style="text-transform: uppercase;">
                                                </td>
                                                <td style="padding-top: 31px;">
                                                    <input type="time" class="form-control" id="otswab_circulateNurse1Start" name="circulateNurse1Start">
                                                </td>
                                                <td style="padding-top: 31px;">
                                                    <input type="time" class="form-control" id="otswab_circulateNurse1End" name="circulateNurse1End">
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Name of 2nd Circulating Nurse<br>
                                                    <input type="text" class="form-control" id="otswab_circulateNurse2" name="circulateNurse2" style="text-transform: uppercase;">
                                                </td>
                                                <td style="padding-top: 31px;">
                                                    <input type="time" class="form-control" id="otswab_circulateNurse2Start" name="circulateNurse2Start">
                                                </td>
                                                <td style="padding-top: 31px;">
                                                    <input type="time" class="form-control" id="otswab_circulateNurse2End" name="circulateNurse2End">
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Name of 3rd Circulating Nurse<br>
                                                    <input type="text" class="form-control" id="otswab_circulateNurse3" name="circulateNurse3" style="text-transform: uppercase;">
                                                </td>
                                                <td style="padding-top: 31px;">
                                                    <input type="time" class="form-control" id="otswab_circulateNurse3Start" name="circulateNurse3Start">
                                                </td>
                                                <td style="padding-top: 31px;">
                                                    <input type="time" class="form-control" id="otswab_circulateNurse3End" name="circulateNurse3End">
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
<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment">
        SWAB & INSTRUMENT COUNT FORM
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
                    <div class="ui segment">
                        <div class="inline fields">
                            <label>iPesakit</label>
                            <div class="field"><input type="text" class="form-control" id="otswab_iPesakit" name="iPesakit"></div>
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
                                                    <label>Basic set : </label>
                                                    <textarea id="otswab_basicset" name="basicset" rows="4"></textarea>
                                                </div>
                                                
                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label>Supplementary : </label>
                                                    <textarea id="otswab_supplemntryset" name="supplemntryset" rows="4"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="sixteen wide column">
                                    <div class="ui segments">
                                        <div class="ui segment" id="jqGrid_otswab_c" style="padding: 14px 5px;">
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
                                                        <td width="15%">Operation(s) done : </td>
                                                        <td>
                                                            <textarea class="form-control input-sm" id="otswab_actualOper" name="actualOper" rows="4"></textarea>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="sixteen wide column">
                                    <div class="ui segments">
                                        <div class="ui segment">
                                            <div class="ui grid">
                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>SPECIMENS SENT : </label>
                                                    <textarea id="otswab_specimenSent" name="specimenSent" rows="4"></textarea>
                                                </div>
                                                
                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label>INCIDENT / EQUIPMENT FAILURE : </label>
                                                    <textarea id="otswab_issuesOccured" name="issuesOccured" rows="4"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="sixteen wide column">
                                    <div class="ui segments">
                                        <div class="ui segment">
                                            <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                                <tbody>
                                                    <tr>
                                                        <td width="15%">1st Scrub Nurse</td>
                                                        <td>
                                                            <input type="text" class="form-control" id="otswab_scrubNurse1" name="scrubNurse1" style="text-transform: uppercase;">
                                                        </td>
                                                        <td width="30%"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>2nd Scrub Nurse</td>
                                                        <td>
                                                            <input type="text" class="form-control" id="otswab_scrubNurse2" name="scrubNurse2" style="text-transform: uppercase;">
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Circulating Nurse</td>
                                                        <td>
                                                            <input type="text" class="form-control" id="otswab_circulateNurse1" name="circulateNurse1" style="text-transform: uppercase;">
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
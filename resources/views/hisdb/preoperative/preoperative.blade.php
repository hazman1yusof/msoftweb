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
                                                    <input type="checkbox" name="" value="">Patient's ID
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="" value="">(use two identifiers)
                                                </label>
                                            </td>
                                            <td><input type="checkbox" name="" value=""></td>
                                            <td><input type="checkbox" name="" value=""></td>
                                            <td><textarea type="text" class="form-control input-sm" id="pt_unknown" name="pt_unknown"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <div>
                                                    <div style="width: 45%;float: left;">
                                                        <!-- <input type="checkbox" name="vehicle1" value="Bike">
                                                        <label for="vehicle1"> I have a bike</label><br>
                                                        <input type="checkbox" name="vehicle2" value="Car">
                                                        <label for="vehicle2"> I have a car</label><br>
                                                        <input type="checkbox" name="vehicle3" value="Boat">
                                                        <label for="vehicle3"> I have a boat</label><br><br> -->
                                                    </div>
                                                    <div style="width: 45%;float: right;">Test</div>
                                                </div>
                                            </td>
                                            <td><input type="checkbox" name="" value=""></td>
                                            <td><input type="checkbox" name="" value=""></td>
                                            <td><textarea type="text" class="form-control input-sm" id="consent" name="consent"></textarea></td>
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
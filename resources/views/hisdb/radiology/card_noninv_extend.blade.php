<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        <div class="ui small blue icon buttons" id="btn_grp_edit_card_noninv" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_card_noninv"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_card_noninv"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_card_noninv"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_card_noninv"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <button class="ui button" id="card_noninv_chart"><span class="fa fa-print fa-lg"></span>Print</button>
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formcard_noninv" class="floated ui form sixteen wide column">
                <div class='ui grid'>
                    <div class="sixteen wide column" style="border-right: solid #dededf 1px;">
                        <div class='ui grid'>
                            <div class="sixteen wide column">
                                <div class="inline fields">
                                    <div class="field">
                                        <label>
                                            <input type="radio" id="card_type1" name="card_type" value="card_type1" >
                                             Transthoracic Echocardiogram
                                        </label>
                                    </div>
                                    <div class="field">
                                        <label>
                                            <input type="radio" id="card_type2" name="card_type" value="card_type2" >
                                             Trans oesophagael Echocardiogram
                                        </label>
                                    </div>
                                    <div class="field">
                                        <label>
                                            <input type="radio" id="card_type3" name="card_type" value="card_type3" >
                                             24 hours Holter Monitoring
                                        </label>
                                    </div>
                                    <div class="field">
                                        <label>
                                            <input type="radio" id="card_type4" name="card_type" value="card_type4" >
                                             Dobutamine Stress Echo / Treadmill / Bicycle
                                        </label>
                                    </div>
                                    <div class="field">
                                        <label>
                                            <input type="radio" id="card_type5" name="card_type" value="card_type5" >
                                             24 hours Ambulatory Blood Pressure Monitoring
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="sixteen wide column">
                                <div class="field">
                                    <label>Patient Name</label>
                                    <input type="text" name="card_patname" id="card_patname" data-validation="required">
                                </div>
                            </div>

                            <div class="six wide column">
                                <div class="field">
                                    <label>I/C</label>
                                    <input type="text" name="card_newic" id="card_newic" data-validation="required">
                                </div>
                            </div>

                            <div class="six wide column">
                                <div class="field">
                                    <label>Contact Number</label>
                                    <input type="text" name="card_telhp" id="card_telhp">
                                </div>
                            </div>

                            <div class="two wide column">
                                <div class="field">
                                    <label>Age</label>
                                    <input type="text" name="card_age" id="card_age" >
                                </div>
                            </div>

                            <div class="two wide column">
                                <div class="field">
                                    <label>Sex</label>
                                    <input type="text" name="card_sex" id="card_sex" >
                                </div>
                            </div>

                            <div class="sixteen wide column">
                                <div class="field">
                                    <label>Address</label>
                                    <textarea rows="3" id="card_addr" name="card_addr" ></textarea>
                                </div>

                                <div class="ui divider"></div>
                            </div>

                            <div class="sixteen wide column">
                                <div class="field">
                                    <label>Indication</label>
                                    <input type="text" name="card_ind" id="card_ind" data-validation="required">
                                </div>
                            </div>

                            <div class="sixteen wide column">
                                <div class="field">
                                    <label>Clinical Details</label>
                                    <textarea rows="5" id="card_clindet" name="card_clindet" ></textarea>
                                </div>
                            </div>

                            <div class="sixteen wide column" style="padding-top: 0px;">
                                <div class="two fields">
                                    <div class="field">
                                        <label>Doctor Name</label>
                                        <input type="text" name="card_docname" id="card_docname" data-validation="required">
                                    </div>
                                    <div class="field">
                                        <label>Ward/Clinic</label>
                                        <input type="text" name="card_wardclinic" id="card_wardclinic" data-validation="required">
                                    </div>
                                </div>
                            </div>

                            <div class="sixteen wide column" style="padding-top: 0px;">
                                <div class="three fields">
                                    <div class="field">
                                        <label>Clinic Appointment</label>
                                        <input type="date" name="card_apptdate" id="card_apptdate" data-validation="required">
                                    </div>
                                    <div class="field">
                                        <label>Date</label>
                                        <input type="date" name="card_date" id="card_date" data-validation="required">
                                    </div>
                                    <div class="field">
                                        <label>Entered By</label>
                                        <input type="text" name="card_adduser" id="card_adduser" data-validation="required" rdonly>
                                    </div>
                                </div>

                                <div class="ui divider"></div>
                            </div>

                            <div class="sixteen wide column" style="padding-bottom: 0px;">
                                <div class="inline fields">
                                    <div class="field">
                                        <label>
                                            <input type="radio" id="card_chkty1" name="card_chkty" value="card_chkty1" >
                                             Immediate
                                        </label>
                                    </div>
                                    <div class="field">
                                        <label>
                                            <input type="radio" id="card_chkty2" name="card_chkty" value="card_chkty2" >
                                             Early
                                        </label>
                                    </div>
                                    <div class="field">
                                        <label>
                                            <input type="radio" id="card_chkty3" name="card_chkty" value="card_chkty3" >
                                             Routine
                                        </label>
                                    </div>
                                    <div class="field">
                                        <label>
                                            <input type="radio" id="card_chkty4" name="card_chkty" value="card_chkty4" >
                                             Insufficient Detail
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="sixteen wide column" style="padding-top: 0px;">
                                <div class="two fields">
                                    <div class="field">
                                        <label>Checked By</label>
                                        <input type="text" name="card_chkby" id="card_chkby">
                                    </div>
                                    <div class="field">
                                        <label>Test Appointment</label>
                                        <input type="date" name="card_testapptdate" id="card_testapptdate">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
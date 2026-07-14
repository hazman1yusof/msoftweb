<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        <div class="ui small blue icon buttons" id="btn_grp_edit_referralLetterReqfor" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_referralLetterReqfor"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_referralLetterReqfor"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_referralLetterReqfor"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_referralLetterReqfor"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <button class="ui button" id="referralLetterReqfor_chart"><span class="fa fa-print fa-lg"></span>Print</button>
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formreferralLetterReqfor" class="floated ui form sixteen wide column">
                <div class='ui grid'>
                    <div class="ten wide column" style="border-right: solid #dededf 1px;">
                        <div class='ui grid'>
                            <div class="sixteen wide column" style="padding-bottom:0px">
                                <div class="inline fields">
                                    <label >Referral must be:</label>
                                    <div class="field">
                                        <label>
                                            <input type="radio" id="refto_mo" name="refto" value="MO" >
                                             Medical Officer
                                        </label>
                                    </div>
                                    <div class="field">
                                        <label>
                                            <input type="radio" id="refto_cs" name="refto" value="CS" >
                                             Clinical Specialist
                                        </label>
                                    </div>
                                    <div class="field">
                                        <label>
                                            <input type="radio" id="refto_cons" name="refto" value="CONS" >
                                             Consultant
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="sixteen wide column" style="padding-top:0px">
                                <div class="field">
                                    <label>Referral To</label>
                                    <input type="text" name="refdocname" id="refdocname" data-validation="required">
                                </div>
                            </div>

                            <div class="sixteen wide column" style="padding-top:0px">
                                <div class="field">
                                    <label>Department/Unit</label>
                                    <input type="text" name="refdocdept" id="refdocdept" data-validation="required">
                                </div>
                            </div>

                            <div class="sixteen wide column">
                                <div class="inline fields">
                                    <label >Priority:</label>
                                    <div class="field">
                                        <label>
                                            <input type="radio" id="prio_refl_ur" name="refprio" value="URG" >
                                             Urgent
                                        </label>
                                    </div>
                                    <div class="field">
                                        <label>
                                            <input type="radio" id="prio_refl_nur" name="refprio" value="NOTURG" >
                                             Not Urgent
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="sixteen wide column">
                                <div class="field">
                                    <label>Patient Name</label>
                                    <input type="text" name="refpatname" id="refpatname" data-validation="required">
                                </div>
                            </div>

                            <div class="four wide column">
                                <div class="field">
                                    <label>I/C</label>
                                    <input type="text" name="refnewic" id="refnewic" data-validation="required">
                                </div>
                            </div>

                            <div class="four wide column">
                                <div class="field">
                                    <label>Referral Number</label>
                                    <input type="text" name="reffno" id="reffno">
                                </div>
                            </div>

                            <div class="two wide column">
                                <div class="field">
                                    <label>Age</label>
                                    <input type="text" name="refage" id="refage" data-validation="required">
                                </div>
                            </div>

                            <div class="two wide column">
                                <div class="field">
                                    <label>Sex</label>
                                    <input type="text" name="refsex" id="refsex" data-validation="required">
                                </div>
                            </div>

                            <div class="two wide column" style="padding: 14px 5px;">
                                <div class="field">
                                    <label>Date</label>
                                    <input type="text" name="refdate" id="refdate" data-validation="required">
                                </div>
                            </div>

                            <div class="two wide column">
                                <div class="field">
                                    <label>Time</label>
                                    <input type="text" name="reftime" id="reftime" data-validation="required">
                                </div>
                            </div>

                            <div class="eight wide column">
                                <div class="field">
                                    <label>Patient History</label>
                                    <textarea rows="5" id="refpathist" name="refpathist" ></textarea>
                                </div>
                            </div>

                            <div class="eight wide column">
                                <div class="field">
                                    <label>Physical Findings</label>
                                    <textarea rows="5" id="refphyfin" name="refphyfin" ></textarea>
                                </div>
                            </div>

                            <div class="eight wide column">
                                <div class="field">
                                    <label>Diagnosis</label>
                                    <textarea rows="5" id="refdiag" name="refdiag" data-validation="required"></textarea>
                                </div>
                            </div>

                            <div class="eight wide column">
                                <div class="field">
                                    <label>Result of Investigation</label>
                                    <textarea rows="5" id="refinvres" name="refinvres" ></textarea>
                                </div>
                            </div>

                            <div class="eight wide column">
                                <div class="field">
                                    <label>Treatment</label>
                                    <textarea rows="5" id="reftreat" name="reftreat" ></textarea>
                                </div>
                            </div>

                            <div class="eight wide column">
                                <div class="field">
                                    <label>Purpose</label>
                                    <textarea rows="5" id="refpurpose" name="refpurpose" ></textarea>
                                </div>
                            </div>

                            <div class="sixteen wide column" style="padding-bottom: 0px;">
                                <div class="inline fields">
                                    <label >From :</label>
                                    <div class="field">
                                        <label>
                                            <input type="radio" id="from_refl_mo" name="reffro" value="MO" >
                                             Medical Officer
                                        </label>
                                    </div>
                                    <div class="field">
                                        <label>
                                            <input type="radio" id="from_refl_cs" name="reffro" value="CS" >
                                             Clinical Specialist
                                        </label>
                                    </div>
                                    <div class="field">
                                        <label>
                                            <input type="radio" id="from_refl_cons" name="reffro" value="CONS" >
                                             Consultant
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="sixteen wide column" style="padding-top: 0px;">
                                <div class="three fields">
                                    <div class="field">
                                        <label>Name</label>
                                        <input type="text" name="refname" id="refname" data-validation="required">
                                    </div>
                                    <div class="field">
                                        <label>Department</label>
                                        <input type="text" name="refdept" id="refdept" data-validation="required">
                                    </div>
                                    <div class="field">
                                        <label>Phone</label>
                                        <input type="text" name="refphone" id="refphone" data-validation="required">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="six wide column">
                        <div class="eight wide column">
                            <div class="field">
                                <label>Free Text Form</label>
                                <textarea rows="5" id="freetext_refl" name="reffreetxt" style="height:100vh"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
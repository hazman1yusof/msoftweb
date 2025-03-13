<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment">
        OT TIME RECORD
        <div class="ui small blue icon buttons" id="btn_grp_edit_ottime" style="position: absolute;
                    padding: 0 0 0 0;
                    right: 40px;
                    top: 9px;
                    z-index: 2;">
            <button class="ui button" id="new_ottime"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_ottime"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_ottime"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_ottime"><span class="fa fa-ban fa-lg"></span>Cancel</button>
        </div>
    </div>
    
    <div class="ui segment" style="padding: 10px 10px 30px 30px;">
        <form id="form_ottime" class="ui form">
            <div class="ui grid">
                <input id="mrn_ottime" name="mrn_ottime" type="hidden">
                <input id="episno_ottime" name="episno_ottime" type="hidden">
                
                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">ROOM RECORD</div>
                        <div class="ui segment">
                            <div class="ui grid">
                                <div class="twelve wide column">
                                    <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                        <tbody>
                                            <tr>
                                                <td>PCR RESULT</td>
                                                <td>
                                                    <label class="radio-inline" style="padding-left: 30px;">
                                                        <input type="radio" name="pcrResult" value="0">Negative
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="pcrResult" value="1">Positive &nbsp; &nbsp;
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>PCR DATE RESULT</td>
                                                <td>
                                                    <div class="field five wide column">
                                                        <input type="date" class="form-control" id="pcrDate" name="pcrDate">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>CALL PATIENT</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <!-- <div class="form-group">
                                                            <input type="time" class="form-control" id="callPtTime" name="callPtTime">
                                                        </div>  &nbsp; -->
                                                        <div class="form-group">
                                                            <div class="ui calendar" id="callPtTime">
                                                                <div class="ui input left icon">
                                                                    <i class="time icon"></i>
                                                                    <input type="text" class="form-control" name="callPtTime" size="10">
                                                                </div>
                                                            </div>
                                                        </div>  &nbsp;
                                                        <small>/24H</small>  &nbsp;
                                                        <div class="form-group">
                                                            <input type="date" class="form-control" id="callPtDate" name="callPtDate">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>PPK OFF TO WARD</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <!-- <div class="form-group">
                                                            <input type="time" class="form-control" id="ppkWardTime" name="ppkWardTime">
                                                        </div>  &nbsp; -->
                                                        <div class="form-group">
                                                            <div class="ui calendar" id="ppkWardTime">
                                                                <div class="ui input left icon">
                                                                    <i class="time icon"></i>
                                                                    <input type="text" class="form-control" name="ppkWardTime" size="10">
                                                                </div>
                                                            </div>
                                                        </div>  &nbsp;
                                                        <small>/24H</small>  &nbsp;
                                                        <div class="form-group">
                                                            <input type="date" class="form-control" id="ppkWardDate" name="ppkWardDate">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>RECEPTION</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <!-- <div class="form-group">
                                                            <input type="time" class="form-control" id="receptionTime" name="receptionTime">
                                                        </div>  &nbsp; -->
                                                        <div class="form-group">
                                                            <div class="ui calendar" id="receptionTime">
                                                                <div class="ui input left icon">
                                                                    <i class="time icon"></i>
                                                                    <input type="text" class="form-control" name="receptionTime" size="10">
                                                                </div>
                                                            </div>
                                                        </div>  &nbsp;
                                                        <small>/24H</small>  &nbsp;
                                                        <div class="form-group">
                                                            <input type="date" class="form-control" id="receptionDate" name="receptionDate">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>PATIENT IN OT</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <!-- <div class="form-group">
                                                            <input type="time" class="form-control" id="patientOTtime" name="patientOTtime">
                                                        </div>  &nbsp; -->
                                                        <div class="form-group">
                                                            <div class="ui calendar" id="patientOTtime">
                                                                <div class="ui input left icon">
                                                                    <i class="time icon"></i>
                                                                    <input type="text" class="form-control" name="patientOTtime" size="10">
                                                                </div>
                                                            </div>
                                                        </div>  &nbsp;
                                                        <small>/24H</small>  &nbsp;
                                                        <div class="form-group">
                                                            <input type="date" class="form-control" id="patientOTdate" name="patientOTdate">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>INCISION START</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <!-- <div class="form-group">
                                                            <input type="time" class="form-control" id="incisionstart" name="incisionstart">
                                                        </div>  &nbsp; -->
                                                        <div class="form-group">
                                                            <div class="ui calendar" id="incisionstart">
                                                                <div class="ui input left icon">
                                                                    <i class="time icon"></i>
                                                                    <input type="text" class="form-control" name="incisionstart" size="10">
                                                                </div>
                                                            </div>
                                                        </div>  &nbsp;
                                                        <small>/24H</small>  &nbsp;
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>INCISION END</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <!-- <div class="form-group">
                                                            <input type="time" class="form-control" id="incisionend" name="incisionend">
                                                        </div>  &nbsp; -->
                                                        <div class="form-group">
                                                            <div class="ui calendar" id="incisionend">
                                                                <div class="ui input left icon">
                                                                    <i class="time icon"></i>
                                                                    <input type="text" class="form-control" name="incisionend" size="10">
                                                                </div>
                                                            </div>
                                                        </div>  &nbsp;
                                                        <small>/24H</small>  &nbsp;
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>PATIENT OUT</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <!-- <div class="form-group">
                                                            <input type="time" class="form-control" id="ptOutTime" name="ptOutTime">
                                                        </div>  &nbsp; -->
                                                        <div class="form-group">
                                                            <div class="ui calendar" id="ptOutTime">
                                                                <div class="ui input left icon">
                                                                    <i class="time icon"></i>
                                                                    <input type="text" class="form-control" name="ptOutTime" size="10">
                                                                </div>
                                                            </div>
                                                        </div>  &nbsp;
                                                        <small>/24H</small>  &nbsp;
                                                        <div class="form-group">
                                                            <input type="date" class="form-control" id="ptOutDate" name="ptOutDate">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>WARD CALL</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <!-- <div class="form-group">
                                                            <input type="time" class="form-control" id="wardCallTime" name="wardCallTime">
                                                        </div>  &nbsp; -->
                                                        <div class="form-group">
                                                            <div class="ui calendar" id="wardCallTime">
                                                                <div class="ui input left icon">
                                                                    <i class="time icon"></i>
                                                                    <input type="text" class="form-control" name="wardCallTime" size="10">
                                                                </div>
                                                            </div>
                                                        </div>  &nbsp;
                                                        <small>/24H</small>  &nbsp;
                                                        <div class="form-group">
                                                            <input type="date" class="form-control" id="wardCallDate" name="wardCallDate">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>PATIENT OFF TO WARD</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <!-- <div class="form-group">
                                                            <input type="time" class="form-control" id="ptWardTime" name="ptWardTime">
                                                        </div>  &nbsp; -->
                                                        <div class="form-group">
                                                            <div class="ui calendar" id="ptWardTime">
                                                                <div class="ui input left icon">
                                                                    <i class="time icon"></i>
                                                                    <input type="text" class="form-control" name="ptWardTime" size="10">
                                                                </div>
                                                            </div>
                                                        </div>  &nbsp;
                                                        <small>/24H</small>  &nbsp;
                                                        <div class="form-group">
                                                            <input type="date" class="form-control" id="ptWardDate" name="ptWardDate">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>SCRUB PERSON</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <input type="text" class="form-control" id="scrubperson" name="scrubperson">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>GA NURSE</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <input type="text" class="form-control" id="gaNurse" name="gaNurse">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>CIRCULATING PERSON</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <input type="text" class="form-control" id="circulateperson" name="circulateperson">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>HEALTHCARE ASSISTANT</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <textarea id="hlthcareAsst" name="hlthcareAsst" rows="4"></textarea>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>OT CLEANED BY</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <textarea id="otCleanedBy" name="otCleanedBy" rows="4"></textarea>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>REMARKS</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <textarea id="remarks" name="remarks" rows="4"></textarea>
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
            </div>
        </form>
    </div>
</div>
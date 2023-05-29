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
                                                        <input type="radio" name="pcr_result" value="0">Negative
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="pcr_result" value="1">Positive &nbsp; &nbsp;
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>PCR DATE RESULT</td>
                                                <td>
                                                    <div class="field five wide column">
                                                        <input type="date" class="form-control" id="pcr_date" name="pcr_date">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>CALL PATIENT</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <!-- <div class="form-group">
                                                            <input type="time" class="form-control" id="callpt_time" name="callpt_time">
                                                        </div>  &nbsp; -->
                                                        <div class="form-group">
                                                            <div class="ui calendar" id="callpt_time">
                                                                <div class="ui input left icon">
                                                                    <i class="time icon"></i>
                                                                    <input type="text" class="form-control" name="callpt_time" size="10">
                                                                </div>
                                                            </div>
                                                        </div>  &nbsp;
                                                        <small>/24H</small>  &nbsp;
                                                        <div class="form-group">
                                                            <input type="date" class="form-control" id="callpt_date" name="callpt_date">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>PPK OFF TO WARD</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <!-- <div class="form-group">
                                                            <input type="time" class="form-control" id="ppkward_time" name="ppkward_time">
                                                        </div>  &nbsp; -->
                                                        <div class="form-group">
                                                            <div class="ui calendar" id="ppkward_time">
                                                                <div class="ui input left icon">
                                                                    <i class="time icon"></i>
                                                                    <input type="text" class="form-control" name="ppkward_time" size="10">
                                                                </div>
                                                            </div>
                                                        </div>  &nbsp;
                                                        <small>/24H</small>  &nbsp;
                                                        <div class="form-group">
                                                            <input type="date" class="form-control" id="ppkward_date" name="ppkward_date">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>RECEPTION</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <!-- <div class="form-group">
                                                            <input type="time" class="form-control" id="reception_time" name="reception_time">
                                                        </div>  &nbsp; -->
                                                        <div class="form-group">
                                                            <div class="ui calendar" id="reception_time">
                                                                <div class="ui input left icon">
                                                                    <i class="time icon"></i>
                                                                    <input type="text" class="form-control" name="reception_time" size="10">
                                                                </div>
                                                            </div>
                                                        </div>  &nbsp;
                                                        <small>/24H</small>  &nbsp;
                                                        <div class="form-group">
                                                            <input type="date" class="form-control" id="reception_date" name="reception_date">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>PATIENT IN OT</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <!-- <div class="form-group">
                                                            <input type="time" class="form-control" id="patientOT_time" name="patientOT_time">
                                                        </div>  &nbsp; -->
                                                        <div class="form-group">
                                                            <div class="ui calendar" id="patientOT_time">
                                                                <div class="ui input left icon">
                                                                    <i class="time icon"></i>
                                                                    <input type="text" class="form-control" name="patientOT_time" size="10">
                                                                </div>
                                                            </div>
                                                        </div>  &nbsp;
                                                        <small>/24H</small>  &nbsp;
                                                        <div class="form-group">
                                                            <input type="date" class="form-control" id="patientOT_date" name="patientOT_date">
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
                                                            <input type="time" class="form-control" id="ptOut_time" name="ptOut_time">
                                                        </div>  &nbsp; -->
                                                        <div class="form-group">
                                                            <div class="ui calendar" id="ptOut_time">
                                                                <div class="ui input left icon">
                                                                    <i class="time icon"></i>
                                                                    <input type="text" class="form-control" name="ptOut_time" size="10">
                                                                </div>
                                                            </div>
                                                        </div>  &nbsp;
                                                        <small>/24H</small>  &nbsp;
                                                        <div class="form-group">
                                                            <input type="date" class="form-control" id="ptOut_date" name="ptOut_date">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>WARD CALL</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <!-- <div class="form-group">
                                                            <input type="time" class="form-control" id="wardCall_time" name="wardCall_time">
                                                        </div>  &nbsp; -->
                                                        <div class="form-group">
                                                            <div class="ui calendar" id="wardCall_time">
                                                                <div class="ui input left icon">
                                                                    <i class="time icon"></i>
                                                                    <input type="text" class="form-control" name="wardCall_time" size="10">
                                                                </div>
                                                            </div>
                                                        </div>  &nbsp;
                                                        <small>/24H</small>  &nbsp;
                                                        <div class="form-group">
                                                            <input type="date" class="form-control" id="wardCall_date" name="wardCall_date">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>PATIENT OFF TO WARD</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <!-- <div class="form-group">
                                                            <input type="time" class="form-control" id="ptWard_time" name="ptWard_time">
                                                        </div>  &nbsp; -->
                                                        <div class="form-group">
                                                            <div class="ui calendar" id="ptWard_time">
                                                                <div class="ui input left icon">
                                                                    <i class="time icon"></i>
                                                                    <input type="text" class="form-control" name="ptWard_time" size="10">
                                                                </div>
                                                            </div>
                                                        </div>  &nbsp;
                                                        <small>/24H</small>  &nbsp;
                                                        <div class="form-group">
                                                            <input type="date" class="form-control" id="ptWard_date" name="ptWard_date">
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
                                                        <input type="text" class="form-control" id="ga_nurse" name="ga_nurse">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>CIRCULATING PERSON</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <input type="text" class="form-control" id="circltg_person" name="circltg_person">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>HEALTHCARE ASSISTANT</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <textarea id="hlthcare_asst" name="hlthcare_asst" rows="4"></textarea>
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
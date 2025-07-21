<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        <div class="ui small blue icon buttons" id="btn_grp_edit_cardiorespAssessment" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_cardiorespAssessment"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_cardiorespAssessment"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_cardiorespAssessment"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_cardiorespAssessment"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <!-- <button class="ui button" id="cardiorespAssessment_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formCardiorespAssessment" class="floated ui form sixteen wide column">
                <input id="idno_cardiorespAssessment" name="idno_cardiorespAssessment" type="hidden">
                <div class="ui grid">
                    <div class='four wide column'>
                        <div class="ui segments">
                            <div class="ui segment">
                                <div class="ui grid">
                                    <table id="tbl_cardiorespAssessment_date" class="ui celled table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="scope">idno</th>
                                                <th class="scope">mrn</th>
                                                <th class="scope">episno</th>
                                                <th class="scope">Date</th>
                                                <th class="scope">dt</th>
                                                <th class="scope">Entered By</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class='twelve wide column'>
                        <div class="sixteen wide column">
                            <!-- <div class="inline fields">
                                <label>Date</label>
                                <div class="field">
                                    <input id="cardiorespAssessment_entereddate" name="entereddate" type="date">
                                </div>
                            </div> -->
                            
                            <table class="ui table">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="inline fields">
                                                <label>DATE:</label>
                                                <div class="field">
                                                    <input type="date" class="form-control" id="cardiorespAssessment_entereddate" name="entereddate">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>SUBJECTIVE ASSESSMENT:</label>
                                            <div class="inline fields">
                                                <!-- <label>SUBJECTIVE ASSESSMENT:</label> -->
                                                <div class="field">
                                                    <textarea rows="5" cols="60" id="cardiorespAssessment_subjectiveAssessmt" name="subjectiveAssessmt"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>OBJECTIVE ASSESSMENT:</label>
                                            <div class="inline fields">
                                                <!-- <label>OBJECTIVE ASSESSMENT:</label> -->
                                                <div class="field">
                                                    <textarea rows="5" cols="60" id="cardiorespAssessment_objectiveAssessmt" name="objectiveAssessmt"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 11px 150px;">
                                            <div class="ui card">
                                                <a class="ui card bodydia_cardio" data-type='DIAG_CARDIO'>
                                                    <div class="image">
                                                        <img src="{{ asset('patientcare/img/bodydiagcardio.png') }}">
                                                    </div>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>ANALYSIS:</label>
                                            <div class="inline fields">
                                                <!-- <label>ANALYSIS:</label> -->
                                                <div class="field">
                                                    <textarea rows="5" cols="60" id="cardiorespAssessment_analysis" name="analysis"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>INTERVENTION:</label>
                                            <div class="inline fields">
                                                <!-- <label>INTERVENTION:</label> -->
                                                <div class="field">
                                                    <textarea rows="5" cols="60" id="cardiorespAssessment_intervention" name="intervention"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>HOME EDUCATION:</label>
                                            <div class="inline fields">
                                                <!-- <label>HOME EDUCATION:</label> -->
                                                <div class="field">
                                                    <textarea rows="5" cols="60" id="cardiorespAssessment_homeEducation" name="homeEducation"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>EVALUATION:</label>
                                            <div class="inline fields">
                                                <!-- <label>EVALUATION:</label> -->
                                                <div class="field">
                                                    <textarea rows="5" cols="60" id="cardiorespAssessment_evaluation" name="evaluation"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>REVIEW:</label>
                                            <div class="inline fields">
                                                <!-- <label>REVIEW:</label> -->
                                                <div class="field">
                                                    <textarea rows="5" cols="60" id="cardiorespAssessment_review" name="review"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <table class="ui table" style="margin-top: 30px;">
                                <tbody>
                                    <tr>
                                        <td>
                                            <label>ADDITIONAL NOTES:</label>
                                            <div class="inline fields">
                                                <!-- <label>ADDITIONAL NOTES:</label> -->
                                                <div class="field">
                                                    <textarea rows="5" cols="60" id="cardiorespAssessment_additionalNotes" name="additionalNotes"></textarea>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
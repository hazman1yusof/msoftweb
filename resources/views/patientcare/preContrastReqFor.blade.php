<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        <div class="ui small blue icon buttons" id="btn_grp_edit_preContrastReqFor" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_preContrastReqFor"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_preContrastReqFor"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_preContrastReqFor"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_preContrastReqFor"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <button class="ui button" id="preContrastReqFor_chart"><span class="fa fa-print fa-lg"></span>Print</button>
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formPreContrastReqFor" class="floated ui form sixteen wide column">
                <div class='ui grid' style="padding: 15px 30px;">
                    <div class="sixteen wide column">
                        <div class="field">
                            <label><center><i>(This form is to be filled in by the requesting doctor at the time of making the request)</i></center></label>
                        </div>
                    </div>

                    <div class="sixteen wide column">
                        <div class="field">
                            <label>Examination:</label>
                            <textarea id="req_examination" name="examination" type="text" rows="5"></textarea>
                        </div>
                    </div>

                    <div class="sixteen wide column">
                        <div class="field">
                            <label><center><b><u>ATTENTION</u></b></center></label>
                        </div>
                    </div>
                    
                    <table class="ui striped table">
                        <thead>
                            <tr>
                                <th scope="col" colspan="2">
                                    Your patient (may/will require) I.V Contast Media. Is (he/she) in the high-risk group?<br>
                                    Does (he/she) have:-<br>
                                </th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">a)</th>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    Define history of allergy?
                                </td>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    <div class="inline fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="hisAllergy" value="1" id="req_hisAllergy_Yes">
                                                <label for="req_hisAllergy_Yes">Yes</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="hisAllergy" value="0" id="req_hisAllergy_No">
                                                <label for="req_hisAllergy_No">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">b)</th>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    Have Fever/Allergic rhinitis?
                                </td>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    <div class="inline fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="feverAllergic" value="1" id="req_feverAllergic_Yes">
                                                <label for="req_feverAllergic_Yes">Yes</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="feverAllergic" value="0" id="req_feverAllergic_No">
                                                <label for="req_feverAllergic_No">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">c)</th>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    Previous reaction to contrast media?
                                </td>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    <div class="inline fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="prevReactContrast" value="1" id="req_prevReactContrast_Yes">
                                                <label for="req_prevReactContrast_Yes">Yes</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="prevReactContrast" value="0" id="req_prevReactContrast_No">
                                                <label for="req_prevReactContrast_No">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">d)</th>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    Previous reaction of drug?
                                </td>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    <div class="inline fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="prevReactDrug" value="1" id="req_prevReactDrug_Yes">
                                                <label for="req_prevReactDrug_Yes">Yes</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="prevReactDrug" value="0" id="req_prevReactDrug_No">
                                                <label for="req_prevReactDrug_No">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">e)</th>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    Asthma?
                                </td>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    <div class="inline fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="asthma" value="1" id="req_asthma_Yes">
                                                <label for="req_asthma_Yes">Yes</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="asthma" value="0" id="req_asthma_No">
                                                <label for="req_asthma_No">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">f)</th>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    Heart Disease?
                                </td>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    <div class="inline fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="heartDisease" value="1" id="req_heartDisease_Yes">
                                                <label for="req_heartDisease_Yes">Yes</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="heartDisease" value="0" id="req_heartDisease_No">
                                                <label for="req_heartDisease_No">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">g)</th>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    Very old (< 65 years) or very young (< 1 years)
                                </td>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    <div class="inline fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="veryOldYoung" value="1" id="req_veryOldYoung_Yes">
                                                <label for="req_veryOldYoung_Yes">Yes</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="veryOldYoung" value="0" id="req_veryOldYoung_No">
                                                <label for="req_veryOldYoung_No">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">h)</th>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    Poor general condition?
                                </td>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    <div class="inline fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="poorCondition" value="1" id="req_poorCondition_Yes">
                                                <label for="req_poorCondition_Yes">Yes</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="poorCondition" value="0" id="req_poorCondition_No">
                                                <label for="req_poorCondition_No">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">i)</th>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    Dehydrated?
                                </td>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    <div class="inline fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="dehydrated" value="1" id="req_dehydrated_Yes">
                                                <label for="req_dehydrated_Yes">Yes</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="dehydrated" value="0" id="req_dehydrated_No">
                                                <label for="req_dehydrated_No">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">j)</th>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    Other serious medical condition?
                                </td>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    <div class="inline fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="seriousMedCondition" value="1" id="req_seriousMedCondition_Yes">
                                                <label for="req_seriousMedCondition_Yes">Yes</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="seriousMedCondition" value="0" id="req_seriousMedCondition_No">
                                                <label for="req_seriousMedCondition_No">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="col" colspan="2">
                                    If the Answer to any of the above is <b>YES</b>, please review the indication for the examination. Will an alternative imaging modality suffice?<br>
                                    Patient In-Group <b>[a)]</b> to <b>[e)]</b> will need steroid pre-treatment.<br>
                                    Suggested regime - <i>(Adult Doses)</i><br>
                                    &nbsp; &nbsp;&nbsp; &nbsp;Tab. Prednisolone 50 mg &nbsp; &nbsp;&nbsp; &nbsp; 12 hours before the procedure <u>and</u><br>
                                    &nbsp; &nbsp;&nbsp; &nbsp;Tab. Prednisolone 50 mg &nbsp; &nbsp;&nbsp; &nbsp; 2 hours before the procedure<br><br>
                                </th>
                                <th scope="col"></th>
                            </tr>
                            <tr>
                                <th scope="row">a)</th>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    Previous contrast media examination
                                </td>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    <div class="inline fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="prevContrastExam" value="1" id="req_prevContrastExam_Yes">
                                                <label for="req_prevContrastExam_Yes">Yes</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="prevContrastExam" value="0" id="req_prevContrastExam_No">
                                                <label for="req_prevContrastExam_No">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">b)</th>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    Consent for procedure where necessary (overleaf)
                                </td>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    <div class="inline fields">
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="consentProcedure" value="1" id="req_consentProcedure_Yes">
                                                <label for="req_consentProcedure_Yes">Yes</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui radio checkbox">
                                                <input type="radio" name="consentProcedure" value="0" id="req_consentProcedure_No">
                                                <label for="req_consentProcedure_No">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">c)</th>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    LMP (in female of reproductive age group)
                                </td>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    <div class="field">
                                        <input id="req_LMP" name="LMP" type="date">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">d)</th>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    Renal function (blood Urea/serum Creatinine)
                                </td>
                                <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                    <div class="field">
                                        <input type="text" id="req_renalFunction" name="renalFunction">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="sixteen wide column centered grid" style="padding: 14px 14px 0px 400px;">
                        <div class="inline fields">
                            <label>Requesting Doctor</label>
                            <div class="field">
                                <input id="req_docName" name="docName" type="text" style="width: 320px; text-transform: uppercase;">
                            </div>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</div>
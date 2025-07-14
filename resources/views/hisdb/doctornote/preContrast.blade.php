 <form class='form-horizontal' style='width: 99%;' id='formPreContrast'>
    <div class='col-md-12'>
        <div class="panel panel-default">
            <div class="panel-heading text-center" style="position: sticky; top: 0px; z-index: 3; height: 40px;">
                <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                    id="btn_grp_edit_preContrast" 
                    style="position: absolute; 
                            padding: 0 0 0 0; 
                            right: 40px; 
                            top: 5px;">
                    <button type="button" class="btn btn-default" id="new_preContrast">
                        <span class="fa fa-plus-square-o"></span> New 
                    </button>
                    <button type="button" class="btn btn-default" id="edit_preContrast">
                        <span class="fa fa-edit fa-lg"></span> Edit 
                    </button>
                    <button type="button" class="btn btn-default" data-oper='add' id="save_preContrast">
                        <span class="fa fa-save fa-lg"></span> Save 
                    </button>
                    <button type="button" class="btn btn-default" id="cancel_preContrast">
                        <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                    </button>
                    <button type="button" class="btn btn-default" id="preContrast_chart">
                        <span class="fa fa-print fa-lg"></span> Print 
                    </button>
                </div>
            </div>
                       
            <div class="panel-body">
                <div class='col-md-12'>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="examination">Examination:</label>
                            <div class="col-md-6">
                                <textarea id="examination" name="examination" type="text" class="form-control input-sm"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='col-md-12'>
                    <div class="panel panel-info">
                        <div class="panel-body">
                           <center><i>(This form is to be filled in by the requesting doctor at the time of making the request)</i></center>
                        
                           <table class="table">
                            <br><center><b><u>ATTENTION</u></b></center><br><br>
                                <tbody>
                                    <tr>
                                        <th scope="col" colspan="2">
                                            Your patient (may/will require) I.V Contast Media. Is (he/she) in the high-risk group?<br>
                                            Does (he/she) have:-<br>
                                        </th>
                                        <th scope="col"></th>
                                    </tr>
                                    <tr>
                                        <th scope="row">a)</th>
                                        <td>Define history of allergy?</td>
                                        <td width="30%">
                                            <label class="radio-inline" style="padding-left: 30px;">
                                                <input type="radio" name="hisAllergy" value="1">Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="hisAllergy" value="0">No &nbsp; &nbsp;
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">b)</th>
                                        <td>Have Fever/Allergic rhinitis?</td>
                                        <td width="30%">
                                            <label class="radio-inline" style="padding-left: 30px;">
                                                <input type="radio" name="feverAllergic" value="1">Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="feverAllergic" value="0">No &nbsp; &nbsp;
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">c)</th>
                                        <td>Previous reaction to contrast media?</td>
                                        <td width="30%">
                                            <label class="radio-inline" style="padding-left: 30px;">
                                                <input type="radio" name="prevReactContrast" value="1">Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="prevReactContrast" value="0">No &nbsp; &nbsp;
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">d)</th>
                                        <td>Previous reaction of drug?</td>
                                        <td width="30%">
                                            <label class="radio-inline" style="padding-left: 30px;">
                                                <input type="radio" name="prevReactDrug" value="1">Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="prevReactDrug" value="0">No &nbsp; &nbsp;
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">e)</th>
                                        <td>Asthma?</td>
                                        <td width="30%">
                                            <label class="radio-inline" style="padding-left: 30px;">
                                                <input type="radio" name="asthma" value="1">Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="asthma" value="0">No &nbsp; &nbsp;
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">f)</th>
                                        <td>Heart Disease?</td>
                                        <td width="30%">
                                            <label class="radio-inline" style="padding-left: 30px;">
                                                <input type="radio" name="heartDisease" value="1">Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="heartDisease" value="0">No &nbsp; &nbsp;
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">g)</th>
                                        <td>Very old (< 65 years) or very young (< 1 years)</td>
                                        <td width="30%">
                                            <label class="radio-inline" style="padding-left: 30px;">
                                                <input type="radio" name="veryOldYoung" value="1">Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="veryOldYoung" value="0">No &nbsp; &nbsp;
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">h)</th>
                                        <td>Poor general condition?</td>
                                        <td width="30%">
                                            <label class="radio-inline" style="padding-left: 30px;">
                                                <input type="radio" name="poorCondition" value="1">Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="poorCondition" value="0">No &nbsp; &nbsp;
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">i)</th>
                                        <td>Dehydrated?</td>
                                        <td width="30%">
                                            <label class="radio-inline" style="padding-left: 30px;">
                                                <input type="radio" name="dehydrated" value="1">Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="dehydrated" value="0">No &nbsp; &nbsp;
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">j)</th>
                                        <td>Other serious medical condition?</td>
                                        <td width="30%">
                                            <label class="radio-inline" style="padding-left: 30px;">
                                                <input type="radio" name="seriousMedCondition" value="1">Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="seriousMedCondition" value="0">No &nbsp; &nbsp;
                                            </label>
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
                                        <td>Previous contrast media examination</td>
                                        <td width="30%">
                                            <label class="radio-inline" style="padding-left: 30px;">
                                                <input type="radio" name="prevContrastExam" value="1">Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="prevContrastExam" value="0">No &nbsp; &nbsp;
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">b)</th>
                                        <td>Consent for procedure where necessary (overleaf)</td>
                                        <td width="30%">
                                            <label class="radio-inline" style="padding-left: 30px;">
                                                <input type="radio" name="consentProcedure" value="1">Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="consentProcedure" value="0">No &nbsp; &nbsp;
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">c)</th>
                                        <td>LMP (in female of reproductive age group)</td>
                                        <td width="30%">
                                            <input id="LMP" name="LMP" type="date" class="form-control input-sm">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">d)</th>
                                        <td>Renal function (blood Urea/serum Creatinine)</td>
                                        <td width="30%">
                                            <input id="renalFunction" name="renalFunction" type="text" class="form-control input-sm">
                                        </td>
                                    </tr>
                                </tbody>
                           </table>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <div class="form-group" style="padding-left: 350px">
                                <label class="col-md-2 control-label" for="docName">Requesting Doctor</label>
                                <div class="col-md-6">
                                    <input id="docName" name="docName" type="text" class="form-control input-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
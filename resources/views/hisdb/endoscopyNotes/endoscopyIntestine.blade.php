<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        <div class="ui small blue icon buttons" id="btn_grp_edit_endoscopyIntestine" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_endoscopyIntestine"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_endoscopyIntestine"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_endoscopyIntestine"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_endoscopyIntestine"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <!-- <button class="ui button" id="endoscopyIntestine_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formEndoscopyIntestine" class="floated ui form sixteen wide column">
                <div class='ui grid sixteen wide column' style="padding: 15px 30px;">
                    <div class="sixteen wide column" style="padding: 0px 14px 14px 30px;">
                        <table class="ui striped table">
                            <tbody>
                                <!-- <tr>
                                    <td>
                                        <div class="field">
                                            <label>Address</label>
                                            <input type="text" class="form-control" id="endoscopyIntestine_Address1" name="Address1" rdonly>
                                            <input type="text" class="form-control" id="endoscopyIntestine_Address2" name="Address2" rdonly>
                                            <input type="text" class="form-control" id="endoscopyIntestine_Address3" name="Address3" rdonly>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="field">
                                            <label>Indication</label>
                                            <input type="text" class="form-control" id="endoscopyIntestine_indication" name="indication">
                                        </div>
                                    </td>
                                </tr> -->
                                <tr>
                                    <td colspan="2">
                                        <div class="field">
                                            <label>Indication : </label>
                                            <input type="text" class="form-control" id="endoscopyIntestine_indication" name="indication">
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="field">
                                            <label>Per Rectum : </label>
                                            <textarea id="endoscopyIntestine_perRectum" name="perRectum" type="text" rows="5"></textarea>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="field">
                                            <label>Other Illness : </label>
                                            <textarea id="endoscopyIntestine_otherIllness" name="otherIllness" type="text" rows="5"></textarea>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="2">HBsAG : 
                                        <input type="checkbox" name="HBsAGpositive" id="endoscopyIntestine_HBsAGpositive" value="1" style="margin-left: 15px;">
                                        <label class="checkbox-inline" style="padding-left: 5px;">Positive</label>
                                        
                                        <input type="checkbox" name="HBsAGnegative" id="endoscopyIntestine_HBsAGnegative" value="1" style="margin-left: 15px;">
                                        <label class="checkbox-inline" style="padding-left: 5px;">Negative</label>
                                        
                                        <input type="checkbox" name="HBsAGnotknow" id="endoscopyIntestine_HBsAGnotknow" value="1" style="margin-left: 15px;">
                                        <label class="checkbox-inline" style="padding-left: 5px;">Not Know</label>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="field">
                                            <label>Name of Referring Doctor : </label>
                                            <input type="text" class="form-control" id="endoscopyIntestine_refDoctor" name="refDoctor" style="text-transform: uppercase;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="field">
                                            <label>Date : </label>
                                            <input type="date" class="form-control" id="endoscopyIntestine_refDoctorDate" name="refDoctorDate">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="field">
                                            <label>Instruments : </label>
                                            <input type="text" class="form-control" id="endoscopyIntestine_instruments" name="instruments">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="field">
                                            <label>Serial No. : </label>
                                            <input type="text" class="form-control" id="endoscopyIntestine_serialNo" name="serialNo">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="field">
                                            <label>Medication/Sedation : </label>
                                            <input type="text" class="form-control" id="endoscopyIntestine_medication" name="medication">
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="field">
                                            <label>Endoscopic Findings : </label>
                                            <textarea id="endoscopyIntestine_endosFindings" name="endosFindings" type="text" rows="5"></textarea>
                                        </div>
                                    </td>
                                    <td rowspan="4">
                                        <div class="ui cards">
                                            <a class="ui card bodydia_endoscopyIntestine" data-type='INTESTINE'>
                                                <div class="image">
                                                    <img src="{{ asset('img/intestinediag.png') }}">
                                                </div>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="field">
                                            <label>Biopsy : </label>
                                            <textarea id="endoscopyIntestine_biopsy" name="biopsy" type="text" rows="5"></textarea>
                                        </div>
                                    </td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <td>
                                        <div class="field">
                                            <label>Other Procedures (therapeutic/diagnostic) : </label>
                                            <textarea id="endoscopyIntestine_otherProcedure" name="otherProcedure" type="text" rows="5"></textarea>
                                        </div>
                                    </td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <td>
                                        <div class="field">
                                            <label>Endoscopic Impression : </label>
                                            <textarea id="endoscopyIntestine_endosImpression" name="endosImpression" type="text" rows="5"></textarea>
                                        </div>
                                    </td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="field">
                                            <label>Recommendations/Remarks : </label>
                                            <textarea id="endoscopyIntestine_remarks" name="remarks" type="text" rows="5"></textarea>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="field">
                                            <label>Endoscopist's Name : </label>
                                            <input type="text" class="form-control" id="endoscopyIntestine_endoscopistName" name="endoscopistName" style="text-transform: uppercase;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="field">
                                            <label>Date : </label>
                                            <input type="date" class="form-control" id="endoscopyIntestine_endoscopistDate" name="endoscopistDate">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
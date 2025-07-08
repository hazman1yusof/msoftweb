<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        <div class="ui small blue icon buttons" id="btn_grp_edit_endoscopyStomach" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_endoscopyStomach"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_endoscopyStomach"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_endoscopyStomach"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_endoscopyStomach"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <!-- <button class="ui button" id="endoscopyStomach_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formEndoscopyStomach" class="floated ui form sixteen wide column">
                <div class='ui grid sixteen wide column' style="padding: 15px 30px;">
                    <div class="sixteen wide column" style="padding: 0px 14px 14px 30px;">
                        <div class="inline fields">
                            <label>iPesakit</label>
                            <div class="field">
                                <input id="endoscopyStomach_iPesakit" name="iPesakit" type="text" style="width: 650px;">
                            </div>
                        </div>
                        
                        <div class="inline fields">
                            <label>REFERRED BY</label>
                            <div class="field">
                                <input id="endoscopyStomach_referredBy" name="referredBy" type="text" style="width: 650px; text-transform: uppercase;">
                            </div>
                        </div>
                        
                        <div class="inline fields">
                            <label>ENDOSCOPIST</label>
                            <div class="field">
                                <input id="endoscopyStomach_endoscopist" name="endoscopist" type="text" style="width: 650px; text-transform: uppercase;">
                            </div>
                        </div>
                        
                        <table class="ui striped table">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="field">
                                            <label>PREVIOUS SCOPY</label>
                                            <textarea id="endoscopyStomach_previousScopy" name="previousScopy" type="text" rows="5"></textarea>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="field">
                                            <label>COMPLAINTS</label>
                                            <textarea id="endoscopyStomach_complaints" name="complaints" type="text" rows="5"></textarea>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="field">
                                            <label>OESOPHAGUS</label>
                                            <textarea id="endoscopyStomach_oesophagus" name="oesophagus" type="text" rows="5"></textarea>
                                        </div>
                                    </td>
                                    <td rowspan="3">
                                        <div class="ui cards">
                                            <a class="ui card bodydia_endoscopyStomach" data-type='STOMACH'>
                                                <div class="image">
                                                    <img src="{{ asset('img/stomachdiag.png') }}">
                                                </div>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="field">
                                            <label>STOMACH</label>
                                            <textarea id="endoscopyStomach_stomach" name="stomach" type="text" rows="5"></textarea>
                                        </div>
                                    </td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <td>
                                        <div class="field">
                                            <label>DUODENUM</label>
                                            <textarea id="endoscopyStomach_duodenum" name="duodenum" type="text" rows="5"></textarea>
                                        </div>
                                    </td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <td>
                                        <div class="field">
                                            <label>REMARKS/BIOPSY</label>
                                            <textarea id="endoscopyStomach_remarks" name="remarks" type="text" rows="5"></textarea>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="field">
                                            <label>TREATMENT</label>
                                            <textarea id="endoscopyStomach_treatment" name="treatment" type="text" rows="5"></textarea>
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
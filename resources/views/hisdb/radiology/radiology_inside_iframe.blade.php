<div class="ui top attached tabular menu">
    <a class="item active" data-tab="radClinicReqFor" id="navtab_radClinicReqFor">Radiology Form</a>
    <a class="item" data-tab="mriReqFor" id="navtab_mriReqFor">Checklist MRI</a>
    <a class="item" data-tab="preContrastReqFor" id="navtab_preContrastReqFor">Pre-Contrast<br>Questionnaire</a>
    <a class="item" data-tab="consentReqFor" id="navtab_consentReqFor">Consent Form</a>
</div>

<div class="ui bottom attached tab raised segment active" data-tab="radClinicReqFor">
    <input type="hidden" id="radiology_inside_iframe_phase" value="{{$radiology_inside_iframe_phase}}">
    <div class="ui segments" style="position: relative;">
        <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
            <div class="ui small blue icon buttons" id="btn_grp_edit_radClinicReqFor" style="position: absolute;
                padding: 0 0 0 0;
                right: 40px;
                top: 9px;
                z-index: 2;">
                <button class="ui button" id="new_radClinicReqFor"><span class="fa fa-plus-square-o"></span>New</button>
                <button class="ui button" id="edit_radClinicReqFor"><span class="fa fa-edit fa-lg"></span>Edit</button>
                <button class="ui button" id="save_radClinicReqFor"><span class="fa fa-save fa-lg"></span>Save</button>
                <button class="ui button" id="cancel_radClinicReqFor"><span class="fa fa-ban fa-lg"></span>Cancel</button>
                <button class="ui button" id="radClinicReqFor_chart"><span class="fa fa-print fa-lg"></span>Print</button>
            </div>
        </div>
        <div class="ui segment">
            <div class="ui grid">
                <form id="formRadClinicReqFor" class="floated ui form sixteen wide column">
                    <input type="hidden" name="sex_radClinic" id="sex_radClinic">
                    <div class='ui grid' style="padding: 15px 30px;">
                        <div class="sixteen wide column centered grid" style="padding: 14px 14px 0px 150px;">
                            <div class="inline field">
                                <label>iPesakit</label>
                                <input type="text" id="radReqFor_iPesakit" name="iPesakit" rdonly>
                            </div>
                            
                            <div class="inline field">
                                <label>Weight</label>
                                <div class="ui right labeled input">
                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="radReqFor_weight" name="rad_weight" data-validation="required">
                                    <div class="ui basic label">KG</div>
                                </div>
                            </div>
                            
                            <div class="inline fields">
                                <label for="pt_condition">Patient Condition</label>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        <input type="radio" name="pt_condition" value="walking" id="req_ptcon_walking">
                                        <label for="req_ptcon_walking">Walking</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        <input type="radio" name="pt_condition" value="wheelchair" id="req_ptcon_wheelchair">
                                        <label for="req_ptcon_wheelchair">On Wheelchair</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        <input type="radio" name="pt_condition" value="strecher" id="req_ptcon_strecher">
                                        <label for="req_ptcon_strecher">Strecher</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="inline fields">
                                <label for="radReqFor_pregnant">Pregnant</label>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        <input type="radio" id="pregnantReqFor" name="rad_pregnant" value="1">
                                        <label for="pregnantReqFor">Yes</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        <input type="radio" id="not_pregnantReqFor" name="rad_pregnant" value="0">
                                        <label for="not_pregnantReqFor">No</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="inline field">
                                <label>LMP</label>
                                <input type="date" id="radReqFor_LMP" name="LMP" data-validation="required">
                            </div>
                        </div>
                        
                        <div class="four wide column" style="padding: 14px 14px 14px 150px;">
                            <div class="field">
                                <label>Asthma/Allergy</label>
                            </div>
                        </div>
                        
                        <div class="twelve wide column">
                            <div class="field eight wide column">
                                <textarea id="radReqFor_allergy" name="rad_allergy" type="text" rows="5" data-validation="required"></textarea>
                            </div>
                        </div>
                        
                        <div class="three wide column" style="padding: 0px 14px 14px 150px;">
                            <div class="field">
                                <label>Examination</label>
                            </div>
                        </div>
                        
                        <div class="thirteen wide column" style="padding: 0px 14px 14px 30px;">
                            <table class="ui striped table">
                                <tbody>
                                    <tr>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="ReqFor_xray" name="xray" value="1"></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><label for="ReqFor_xray">X-ray</label></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input id="ReqFor_xray_date" name="xray_date" type="date"></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="ReqFor_xray_remark" name="xray_remark" type="text" rows="5"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="ReqFor_mri" name="mri" value="1"></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><label for="ReqFor_mri">M.R.I</label></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input id="ReqFor_mri_date" name="mri_date" type="date"></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="ReqFor_mri_remark" name="mri_remark" type="text" rows="5"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="ReqFor_angio" name="angio" value="1"></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><label for="ReqFor_angio">Angio</label></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input id="ReqFor_angio_date" name="angio_date" type="date"></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="ReqFor_angio_remark" name="angio_remark" type="text" rows="5"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="ReqFor_ultrasound" name="ultrasound" value="1"></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><label for="ReqFor_ultrasound">Ultrasound</label></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input id="ReqFor_ultrasound_date" name="ultrasound_date" type="date"></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="ReqFor_ultrasound_remark" name="ultrasound_remark" type="text" rows="5"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="ReqFor_ct" name="ct" value="1"></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><label for="ReqFor_ct">C.T</label></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input id="ReqFor_ct_date" name="ct_date" type="date"></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="ReqFor_ct_remark" name="ct_remark" type="text" rows="5"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="ReqFor_fluroscopy" name="fluroscopy" value="1"></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><label for="ReqFor_fluroscopy">Fluroscopy</label></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input id="ReqFor_fluroscopy_date" name="fluroscopy_date" type="date"></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="ReqFor_fluroscopy_remark" name="fluroscopy_remark" type="text" rows="5"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="ReqFor_mammogram" name="mammogram" value="1"></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><label for="ReqFor_mammogram">Mammogram</label></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input id="ReqFor_mammogram_date" name="mammogram_date" type="date"></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="ReqFor_mammogram_remark" name="mammogram_remark" type="text" rows="5"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input type="checkbox" id="ReqFor_bmd" name="bmd" value="1"></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><label for="ReqFor_bmd">Bone Densitometry (BMD)</label></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><input id="ReqFor_bmd_date" name="bmd_date" type="date"></td>
                                        <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="ReqFor_bmd_remark" name="bmd_remark" type="text" rows="5"></textarea></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="four wide column" style="padding: 0px 14px 14px 150px;">
                            <div class="field">
                                <label>Clinical Data</label>
                            </div>
                        </div>
                        
                        <div class="twelve wide column" style="padding-top: 0px;">
                            <div class="field nine wide column">
                                <textarea id="ReqFor_clinicaldata" name="clinicaldata" type="text" rows="5" rdonly></textarea>
                                
                                <div class="inline field" style="padding-top: 15px;">
                                    <label>Doctor's Name</label>
                                    <input id="radClinicReqFor_doctorname" name="radClinic_doctorname" type="text" style="width: 320px; text-transform: uppercase;" rdonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="four wide column" style="padding: 0px 14px 14px 150px;">
                            <div class="field">
                                <label>Radiology Note</label>
                            </div>
                        </div>
                        
                        <div class="twelve wide column" style="padding-top: 0px;">
                            <div class="field nine wide column">
                                <textarea id="ReqFor_rad_note" name="rad_note" type="text" rows="5"  @if($radiology_inside_iframe_phase !='radiology'){{"rdonly"}}@endif></textarea>
                                
                                <div class="inline field" style="padding-top: 15px;">
                                    <label>Radiologist's Name</label>
                                    <input id="radClinicReqFor_radiologist" name="radClinic_radiologist" type="text" style="width: 300px; text-transform: uppercase;" rdonly>
                                </div>
                            </div>
                        </div>
                        <div class="sixteen wide column" style="padding-top: 0px;">
                            <div class="field three wide column">
                                <div class="inline field" style="padding-top: 15px;
                                    position: absolute;
                                    right: 0px;
                                    bottom: 26px;">
                                    <label>Entered By</label>
                                    :<input id="radClinicReqFor_enterby" name="radClinic_enterby" type="text" style="width: 300px; text-transform: uppercase;border:none;" value="{{ session('username') }}" rdonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="ui bottom attached tab raised segment" data-tab="mriReqFor">
    <div class="ui segments" style="position: relative;">
        <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
            <div class="ui small blue icon buttons" id="btn_grp_edit_mriReqFor" style="position: absolute;
                padding: 0 0 0 0;
                right: 40px;
                top: 9px;
                z-index: 2;">
                <button class="ui button" id="new_mriReqFor"><span class="fa fa-plus-square-o"></span>New</button>
                <button class="ui button" id="edit_mriReqFor"><span class="fa fa-edit fa-lg"></span>Edit</button>
                <button class="ui button" id="save_mriReqFor"><span class="fa fa-save fa-lg"></span>Save</button>
                <!-- <button class="ui button" id="accept_mriReqFor"><span class="fa fa-check fa-lg"></span>Accept</button> -->
                <button class="ui button" id="cancel_mriReqFor"><span class="fa fa-ban fa-lg"></span>Cancel</button>
                <button class="ui button" id="mriReqFor_chart"><span class="fa fa-print fa-lg"></span>Print</button>
            </div>
        </div>
        <div class="ui segment">
            <div class="ui grid">
                <form id="formMRIReqFor" class="floated ui form sixteen wide column">
                    <div class='ui grid' style="padding: 15px 30px;">
                        <div class="sixteen wide column centered grid" style="padding: 14px 14px 0px 150px;">
                            <div class="inline fields">
                                <label>Weight</label>
                                <div class="field">
                                    <div class="ui right labeled input">
                                        <input type="text" onKeyPress="if(this.value.length==6) return false;" id="mriReqFor_weight" name="mri_weight" data-validation="required">
                                        <div class="ui basic label">KG</div>
                                    </div>
                                </div>
                                
                                <label>Date</label>
                                <div class="field">
                                    <input id="mriReqFor_entereddate" name="mri_entereddate" type="date" data-validation="required">
                                </div>
                            </div>
                        </div>
                        
                        <table class="ui striped table">
                            <thead>
                                <tr>
                                    <th scope="col" colspan="2">
                                        Please indicate in appropriate column, whether or not the patient has the items indicated.<br>
                                        <i>Sila tandakan pada kotak yang berkenaan jika pesakit mempunyai item tersebut.</i>
                                    </th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        Cardiac pacemaker (<i>Penyelaras denyutan jantung</i>)
                                    </td>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="cardiacpacemaker" value="1" id="req_cp_yes">
                                                    <label for="req_cp_yes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="cardiacpacemaker" value="0" id="req_cp_no">
                                                    <label for="req_cp_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        Prosthetics valve, if yes, please specify.<br><i>Injap jantung palsu, jika ada nyatakan.</i>
                                    </td>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="pros_valve" value="1" id="req_pv_yes">
                                                    <label for="req_pv_yes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="pros_valve" value="0" id="req_pv_no">
                                                    <label for="req_pv_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="ReqFor_prosvalve_rmk" name="prosvalve_rmk" type="text" rows="5" data-validation="required"></textarea></td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        Known intraocular foreign body or history of eye injury.<br><i>Intraocular bendasing atau sejarah cedera pada mata.</i>
                                    </td>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="intraocular" value="1" id="req_io_yes">
                                                    <label for="req_io_yes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="intraocular" value="0" id="req_io_no">
                                                    <label for="req_io_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">4</th>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        Cochlear implants (ENT.)<br><i>Implant koklea (ENT).</i>
                                    </td>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="cochlear_imp" value="1" id="req_ci_yes">
                                                    <label for="req_ci_yes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="cochlear_imp" value="0" id="req_ci_no">
                                                    <label for="req_ci_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">5</th>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        Neurotransmitter (brain/spinal cord pacemaker).<br><i>Neurotransmitter (otak/perentak saraf tunjang).</i>
                                    </td>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="neurotransm" value="1" id="req_nt_yes">
                                                    <label for="req_nt_yes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="neurotransm" value="0" id="req_nt_no">
                                                    <label for="req_nt_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">6</th>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        Bone growth stimulators.<br><i>Perangsang tumbesaran tulang.</i>
                                    </td>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="bonegrowth" value="1" id="req_bg_yes">
                                                    <label for="req_bg_yes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="bonegrowth" value="0" id="req_bg_no">
                                                    <label for="req_bg_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">7</th>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        Implantable drug infusion pumps.<br><i>Implant pam infuse ubat.</i>
                                    </td>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="druginfuse" value="1" id="req_di_yes">
                                                    <label for="req_di_yes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="druginfuse" value="0" id="req_di_no">
                                                    <label for="req_di_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">8</th>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        Cerebral surgical clips/wire.<br><i>Klip serebral.</i>
                                    </td>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="surg_clips" value="1" id="req_sc_yes">
                                                    <label for="req_sc_yes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="surg_clips" value="0" id="req_sc_no">
                                                    <label for="req_sc_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">9</th>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        Joint/limb prosthesis of metallic ferromagnetic materials.<br><i>Anggota badan palsu dari bahan feromagnetic.</i>
                                    </td>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="jointlimb_pros" value="1" id="req_jl_yes">
                                                    <label for="req_jl_yes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="jointlimb_pros" value="0" id="req_jl_no">
                                                    <label for="req_jl_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">10</th>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        Shrapnel or bullet fragment (any of the body).<br><i>Serpihan atau peluru.</i>
                                    </td>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="shrapnel" value="1" id="req_shr_yes">
                                                    <label for="req_shr_yes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="shrapnel" value="0" id="req_shr_no">
                                                    <label for="req_shr_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">11</th>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        Any operation in the last 3 month? If yes please specify.<br><i>Pembedahan dalam masa 3 bulan, jika ada nyatakan.</i>
                                    </td>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="oper_3mth" value="1" id="req_oper_yes">
                                                    <label for="req_oper_yes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="oper_3mth" value="0" id="req_oper_no">
                                                    <label for="req_oper_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;"><textarea id="ReqFor_oper3mth_remark" name="oper3mth_remark" type="text" rows="5" data-validation="required"></textarea></td>
                                </tr>
                                <tr>
                                    <th scope="row">12</th>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        Any previous MRI examination?<br><i>Pemeriksaan MRI sebelum ini?</i>
                                    </td>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="prev_mri" value="1" id="req_mri_yes">
                                                    <label for="req_mri_yes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="prev_mri" value="0" id="req_mri_no">
                                                    <label for="req_mri_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">13</th>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        Have you ever experienced claustrophobia?<br><i>Anda mempunyai klaustrofobia?</i>
                                    </td>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="claustrophobia" value="1" id="req_claus_yes">
                                                    <label for="req_claus_yes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="claustrophobia" value="0" id="req_claus_no">
                                                    <label for="req_claus_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">14</th>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        Dental implant (held in place by magnet).<br><i>Implant dental, mempunyai magnet.</i>
                                    </td>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="dental_imp" value="1" id="req_dental_yes">
                                                    <label for="req_dental_yes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="dental_imp" value="0" id="req_dental_no">
                                                    <label for="req_dental_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">15</th>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        Any implanted ferromagnetic materials (susuk or etc).<br><i>Mempunyai bahan-bahan ferromagnetic seperti susuk.</i>
                                    </td>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="frmgnetic_imp" value="1" id="req_fmg_yes">
                                                    <label for="req_fmg_yes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="frmgnetic_imp" value="0" id="req_fmg_no">
                                                    <label for="req_fmg_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">16</th>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        Pregnancy (1st trimester).<br><i>Mengandung trimester pertama.</i>
                                    </td>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="pregnancy" value="1" id="req_preg_yes">
                                                    <label for="req_preg_yes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="pregnancy" value="0" id="req_preg_no">
                                                    <label for="req_preg_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">17</th>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        Allergic to drug or contrast media?<br><i>Mempunyai alahan terhadap ubat atau media kontras.</i>
                                    </td>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="allergy_drug" value="1" id="req_allergy_yes">
                                                    <label for="req_allergy_yes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="allergy_drug" value="0" id="req_allergy_no">
                                                    <label for="req_allergy_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">18</th>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;" colspan="3">
                                        <div class="inline field">
                                            Blood urea: <input id="ReqFor_bloodurea" name="bloodurea" type="text" style="width: 350px; margin-left: 15px;" data-validation="required">
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">19</th>
                                    <td style="margin: 0px; padding: 3px 14px 14px 14px;" colspan="3">
                                        <div class="inline field">
                                            Serum creatinine: <input id="ReqFor_serum_creatinine" name="serum_creatinine" type="text" style="width: 350px; margin-left: 15px;" data-validation="required">
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="sixteen wide column centered grid" style="padding: 14px 14px 0px 150px;">
                            <div class="inline fields">
                                <label>Name of Doctor</label>
                                <div class="field">
                                    <input id="mriReqFor_doctorname" name="mri_doctorname" type="text" style="width: 320px; text-transform: uppercase;">
                                </div>
                                
                                <label>Name of patient/parents/guardian</label>
                                <div class="field">
                                    <input id="mriReqFor_patientname" name="mri_patientname" type="text" style="width: 320px;" rdonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="five wide column">
                            <div class="field">
                                <label>Doctor / Radiologist</label>
                                <input id="mriReqFor_radiologist" name="mri_radiologist" type="text" style="width: 320px; text-transform: uppercase;" data-validation="required">
                            </div>
                        </div>
                        
                        <div class="five wide column">
                            <div class="field">
                                <label>Radiographer</label>
                                <input id="ReqFor_radiographer" name="radiographer" type="text" style="width: 320px; text-transform: uppercase;" data-validation="required">
                            </div>
                        </div>
                        
                        <div class="five wide column">
                            <div class="inline field">
                                <label>Entered By</label>
                                :<input id="mriReqFor_lastuser" name="mri_lastuser" type="text" style="width: 320px; text-transform: uppercase; border:none;" value="{{ session('username') }}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="ui bottom attached tab raised segment" data-tab="preContrastReqFor">
    @include('patientcare.preContrastReqFor')
</div>

<div class="ui bottom attached tab raised segment" data-tab="consentReqFor">
    @include('patientcare.consentFormReqFor')
</div>
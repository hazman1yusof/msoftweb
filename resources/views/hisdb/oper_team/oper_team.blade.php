<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment">
        OPERATING TEAM
        <div class="ui small blue icon buttons" id="btn_grp_edit_oper_team" style="position: absolute;
                    padding: 0 0 0 0;
                    right: 40px;
                    top: 9px;
                    z-index: 2;">
            <button class="ui button" id="new_oper_team"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_oper_team"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_oper_team"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_oper_team"><span class="fa fa-ban fa-lg"></span>Cancel</button>
        </div>
    </div>
    
    <div class="ui segment" style="padding: 10px 10px 30px 30px;">
        <form id="form_oper_team" class="ui form">
            <div class="ui grid">
                <input id="mrn_oper_team" name="mrn_oper_team" type="hidden">
                <input id="episno_oper_team" name="episno_oper_team" type="hidden">
                
                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">BEFORE INDUCTION OF ANAESTHESIA</div>
                        <div class="ui segment">
                            <div class="ui grid">
                                
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <tbody>
                                        <tr>
                                            <td>SIGN IN (By Anaesthetist & Coordinator Nurse)</td>
                                        </tr>
                                        <tr>
                                            <td width="30%">
                                                Confirmed patient's<br>
                                                <ul>
                                                    <li>Name</li>
                                                    <li>Planned procedure</li>
                                                    <li>Site/Side</li>
                                                    <li>Consent</li>
                                                </ul>
                                            </td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="confirmed_pt" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="confirmed_pt" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Op site marked</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="op_site_mark" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="op_site_mark" value="0">No &nbsp; &nbsp;
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="op_site_mark" value="">NA &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>GA machine and defib machine checked?</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="machine_chck" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="machine_chck" value="0">No &nbsp; &nbsp;
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="machine_chck" value="">NA &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Haemodynamic monitor turn on and functioning?</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="monitor_on" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="monitor_on" value="0">No &nbsp; &nbsp;
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="monitor_on" value="">NA &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Patient has allergy?<br>
                                                <label>If yes, please specify</label>
                                                <textarea class="form-control input-sm" id="allergy_remark" name="allergy_remark"></textarea>
                                            </td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="pt_allergy" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="pt_allergy" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Difficult airway/aspiration risk?</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="diff_airway" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="diff_airway" value="0">No &nbsp; &nbsp;
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="diff_airway" value="">NA &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Any GXM/GSH</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="gxm_gsh" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="gxm_gsh" value="0">No &nbsp; &nbsp;
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="gxm_gsh" value="">NA &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Adequate IV access?</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="iv_access" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="iv_access" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Suction apparatus checked & functioning</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="apparatus_chck" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="apparatus_chck" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>OT Table checked & functioning</td>
                                            <td>
                                                <label class="radio-inline" style="padding-left: 30px;">
                                                    <input type="radio" name="ottable_chck" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="ottable_chck" value="0">No &nbsp; &nbsp;
                                                </label>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">BEFORE SKIN INCISION</div>
                        <div class="ui segment">
                            <div class="ui grid">
                                
                                <table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
                                    <tbody>
                                    
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
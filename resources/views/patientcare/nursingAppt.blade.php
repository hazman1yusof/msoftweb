<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment">
        NURSING
        <div class="ui small blue icon buttons" id="btn_grp_edit_ti" style="position: absolute;
                    padding: 0 0 0 0;
                    right: 40px;
                    top: 9px;
                    z-index: 2;">
          <button class="ui button" id="new_ti"><span class="fa fa-plus-square-o"></span> New</button>
          <button class="ui button" id="edit_ti"><span class="fa fa-edit fa-lg"></span> Edit</button>
          <button class="ui button" id="save_ti"><span class="fa fa-save fa-lg"></span> Save</button>
          <button class="ui button" id="cancel_ti"><span class="fa fa-ban fa-lg"></span> Cancel</button>
        </div>
    </div>

    <div class="ui segment">
        <form id="formTriageInfo" class="ui form">
            <input id="mrn_ti" name="mrn_ti" type="hidden">
            <input id="episno_ti" name="episno_ti" type="hidden">

            <div class="ui grid">

                <div class="eight wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">INFORMATION</div>
                        <div class="ui segment">
                            <div class="inline fields">
                                <div class="field">
                                    <label>Time</label>
                                    <input id="admwardtime" name="admwardtime" type="time">
                                </div>
                                <div class="field">
                                    <label>Date</label>
                                    <input id="reg_date" name="reg_date" type="date" rdonly>
                                </div>
                                <div class="field">
                                    <label>Triage Color Zone</label>
                                    <div class="ui action input">
                                        <input id="triagecolor" name="triagecolor" type="text">
                                        <a class="ui icon blue button"><i class="fa fa-ellipsis-h"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="field">
                                <label>Chief Complain</label>
                                <textarea id="admreason" name="admreason" type="text" rows="4"  data-validation="required" data-validation-error-msg-required="Please enter information."></textarea>
                            </div>

                            <div class="field">
                                <label>Medical History</label>
                                <textarea id="medicalhistory" name="medicalhistory" type="text" rows="4"></textarea>
                            </div>

                            <div class="field">
                                <label>Surgical History</label>
                                <textarea id="surgicalhistory" name="surgicalhistory" type="text" rows="4" ></textarea>
                            </div>

                            <div class="field">
                                <label>Family Medical History</label>
                                <textarea id="familymedicalhist" name="familymedicalhist" type="text" rows="4" ></textarea>
                            </div>

                            <div class="field">
                                <label>Current Medication</label>
                                <textarea id="currentmedication" name="currentmedication" type="text" rows="4" ></textarea>
                            </div>

                            <div class="field">
                                <label>Diagnosis</label>
                                <textarea id="diagnosis" name="diagnosis" type="text" rows="4" ></textarea>
                            </div>
                        </div>


                        <div class="ui segments">
                            <div class="ui secondary segment">ALLERGIES</div>
                            <div class="ui segment">
                                <table class="table table-sm table-hover">
                                    <tbody>
                                        <tr>
                                            <td><input type="checkbox" id="allergydrugs" name="allergydrugs" value="1"></td>
                                            <td><label for="allergydrugs">Drugs</label></td>
                                            <td><textarea id="drugs_remarks" name="drugs_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergyplaster" name="allergyplaster" value="1"></td>
                                            <td><label for="allergyplaster">Plaster</label></td>
                                            <td><textarea id="plaster_remarks" name="plaster_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergyfood" name="allergyfood" value="1"></td>
                                            <td><label for="allergyfood">Food</label></td>
                                            <td><textarea id="food_remarks" name="food_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergyenvironment" name="allergyenvironment" value="1"></td>
                                            <td><label for="allergyenvironment">Environment</label></td>
                                            <td><textarea id="environment_remarks" name="environment_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergyothers" name="allergyothers" value="1"></td>
                                            <td><label for="allergyothers">Others</label></td>
                                            <td><textarea id="others_remarks" name="others_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergyunknown" name="allergyunknown" value="1"></td>
                                            <td><label for="allergyunknown">Unknown</label></td>
                                            <td><textarea id="unknown_remarks" name="unknown_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergynone" name="allergynone" value="1"></td>
                                            <td><label for="allergynone">None</label></td>
                                            <td><textarea id="none_remarks" name="none_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="eight wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">CONDITION ON ADMISSION</div>
                        <div class="ui segment">
                        <div class="ui grid">
                            <div class="sixteen wide column ">
                                <div class="ui segments">
                                    <div class="ui secondary segment">VITAL SIGN</div>
                                    <div class="ui segment">

                                        <div class="ui grid">
                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Temperature</label>
                                                <div class="ui right labeled input">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_temperature" name="vs_temperature">
                                                  <div class="ui basic label">°C</div>
                                                </div>
                                            </div>

                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Pulse Rate</label>
                                                <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_pulse" name="vs_pulse">
                                            </div>
                                        </div>

                                        <div class="ui grid">
                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Respiration</label>
                                                <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_respiration" name="vs_respiration">
                                            </div>

                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Blood Pressure</label>
                                                <div class="ui right labeled input">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_bp_sys1" name="vs_bp_sys1" style="width:25%">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_bp_dias2" name="vs_bp_dias2" style="width:25%">
                                                  <div class="ui basic label">mmHg</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ui grid">
                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Height</label>
                                                <div class="ui right labeled input">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_height" name="vs_height">
                                                  <div class="ui basic label">CM</div>
                                                </div>
                                            </div>

                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Weight</label>
                                                <div class="ui right labeled input">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_weight" name="vs_weight">
                                                  <div class="ui basic label">KG</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ui grid">
                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>GXT</label>
                                                <div class="ui right labeled input">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_gxt" name="vs_gxt">
                                                  <div class="ui basic label">mmOL</div>
                                                </div>
                                            </div>

                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                <label>Pain Score</label>
                                                <div class="ui right labeled input">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_painscore" name="vs_painscore">
                                                  <div class="ui basic label">/10</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="eight wide column ">
                                <div class="ui segments">
                                    <div class="ui secondary segment">MODE OF ADMISSION</div>
                                    <div class="ui segment">
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="moa_walkin" name="moa_walkin" value="1">
                                                 Walk In 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="moa_wheelchair" name="moa_wheelchair" value="1">
                                                 Wheel Chair 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="moa_trolley" name="moa_trolley" value="1">
                                                 Trolley 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="moa_others" name="moa_others" value="1">
                                                 Others 
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="eight wide column ">
                                <div class="ui segments">
                                    <div class="ui secondary segment">LEVEL OF CONSCIOUSNESS</div>
                                    <div class="ui segment">
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="loc_conscious" name="loc_conscious" value="1">
                                                 Conscious 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="loc_semiconscious" name="loc_semiconscious" value="1">
                                                 SemiConscious 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="loc_unconscious" name="loc_unconscious" value="1">
                                                 UnConscious 
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="eight wide column ">
                                <div class="ui segments">
                                    <div class="ui secondary segment">MENTAL STATUS</div>
                                    <div class="ui segment">
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="ms_orientated" name="ms_orientated" value="1">
                                                 Orientated 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="ms_confused" name="ms_confused" value="1">
                                                 Confused 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="ms_restless" name="ms_restless" value="1">
                                                 Restless 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="ms_aggressive" name="ms_aggressive" value="1">
                                                 Aggressive 
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="eight wide column ">
                                <div class="ui segments">
                                    <div class="ui secondary segment">EMOTIONAL STATUS</div>
                                    <div class="ui segment">
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="es_calm" name="es_calm" value="1">
                                                 Calm 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="es_anxious" name="es_anxious" value="1">
                                                 Anxious 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="es_distress" name="es_distress" value="1">
                                                 Distress 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="es_depressed" name="es_depressed" value="1">
                                                 Depressed 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="es_irritable" name="es_irritable" value="1">
                                                 Irritable 
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sixteen wide column ">
                                <div class="ui segments">
                                    <div class="ui secondary segment">FALL RISK ASSESSMENT</div>
                                    <div class="ui segment">
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="fra_prevfalls" name="fra_prevfalls" value="1">
                                                 Previous falls 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="fra_age" name="fra_age" value="1">
                                                 Age 60 years or older 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="fra_physicalLimitation" name="fra_physicalLimitation" value="1">
                                                 Physical limitation-visual & mobility 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="fra_neurologicaldeficit" name="fra_neurologicaldeficit" value="1">
                                                 Neurological deficit-confusion & disorientation 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="fra_dizziness" name="fra_dizziness" value="1">
                                                 Dizziness associated with drugs 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="fra_cerebralaccident" name="fra_cerebralaccident" value="1">
                                                 Cerebral Vascular Accident 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="fra_notatrisk" name="fra_notatrisk" value="1">
                                                 Not at risk 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="fra_atrisk" name="fra_atrisk" value="1">
                                                 At risk
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sixteen wide column ">
                                <div class="ui segments">
                                    <div class="ui secondary segment">PRESSURE SORE RISK ASSESSMENT</div>
                                    <div class="ui segment">
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="psra_incontinent" name="psra_incontinent" value="1">
                                                 Incontinent 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="psra_immobility" name="psra_immobility" value="1">
                                                 Immobility / Restricted mobility
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="psra_poorskintype" name="psra_poorskintype" value="1">
                                                 Poor skin type
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="psra_notatrisk" name="psra_notatrisk" value="1">
                                                 Not at risk 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="psra_atrisk" name="psra_atrisk" value="1">
                                                 At risk 
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">ACTIVITIES OF DAILY LIVING</div>
                        <div class="ui segment">
                            @include('patientcare.nursing_aodl')
                        </div>
                    </div>
                </div>

                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">TRIAGE PHYSICAL ASSESSMENT</div>
                        <div class="ui segment">
                            <div class="ui grid">
                                <div class="four wide column">
                                    <div class="ui segments">
                                        <div class="ui secondary segment">SKIN CONDITION</div>
                                        <div class="ui segment">
                                            <div class="field">
                                                <label>
                                                    <input type="checkbox" id="pa_skindry" name="pa_skindry" value="1">
                                                     Dry
                                                </label>
                                            </div>
                                            <div class="field">
                                                <label>
                                                    <input type="checkbox" id="pa_skinodema" name="pa_skinodema" value="1">
                                                     Odema
                                                </label>
                                            </div>
                                            <div class="field">
                                                <label>
                                                    <input type="checkbox" id="pa_skinjaundice" name="pa_skinjaundice" value="1">
                                                     Jaundice
                                                </label>
                                            </div>
                                            <div class="field">
                                                <label>
                                                    <input type="checkbox" id="pa_skinnil" name="pa_skinnil" value="1">
                                                     NIL
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="four wide column">
                                    <div class="ui segments">
                                        <div class="ui secondary segment">OTHERS</div>
                                        <div class="ui segment">
                                            <div class="field">
                                                <label>
                                                    <input type="checkbox" id="pa_othbruises" name="pa_othbruises" value="1">
                                                     Bruises
                                                </label>
                                            </div>
                                            <div class="field">
                                                <label>
                                                    <input type="checkbox" id="pa_othdeculcer" name="pa_othdeculcer" value="1">
                                                     Decubitues Ulcer
                                                </label>
                                            </div>
                                            <div class="field">
                                                <label>
                                                    <input type="checkbox" id="pa_othlaceration" name="pa_othlaceration" value="1">
                                                     Laceration
                                                </label>
                                            </div>
                                            <div class="field">
                                                <label>
                                                    <input type="checkbox" id="pa_othdiscolor" name="pa_othdiscolor" value="1">
                                                     Discolouration
                                                </label>
                                            </div>
                                            <div class="field">
                                                <label>
                                                    <input type="checkbox" id="pa_othnil" name="pa_othnil" value="1">
                                                     NIL
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="eight wide column">
                                    <div class="ui segments">
                                        <div class="ui secondary segment">Notes:</div>
                                        <div class="ui segment">
                                            <textarea id="pa_notes" name="pa_notes" type="text" rows="4" ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">EXAMINATION</div>
                        <div class="ui segment" id="jqGridExamTriage_c">
                            <table id="jqGridExamTriage" class="table table-striped"></table>
                            <div id="jqGridPagerExamTriage"></div>
                        </div>
                    </div>
                </div>

                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">ADDITIONAL NOTES</div>
                        <div class="ui segment" id="jqGridAddNotesTriage_c">
                            <table id="jqGridAddNotesTriage" class="table table-striped"></table>
                            <div id="jqGridPagerAddNotesTriage"></div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
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
                                <label>Presenting History</label>
                                <textarea id="admreason" name="admreason" type="text" rows="4"  data-validation="required" data-validation-error-msg-required="Please enter information."></textarea>
                            </div>

                            <!-- <div class="field">
                                <label>Medical History</label>
                                <textarea id="medicalhistory" name="medicalhistory" type="text" rows="4"></textarea>
                            </div> -->

                            <div class="field">                                
                                <label>Medical History</label>
                                <div class="eight wide column">
                                    <table class="table;border border-white">
                                        <tbody>
                                            <tr>
                                                <td style="margin:0px; padding: 3px 14px 14px 14px;"> 
                                                    <label><input type="checkbox" id="medhis_heartdisease" name="medhis_heartdisease" value="1"> Heart Disease</label>
                                                </td>
                                                <td style="margin:0px; padding: 3px 14px 14px 14px;"> 
                                                    <label><input type="checkbox" id="medhis_seizures" name="medhis_seizures" value="1"> Seizures</label>
                                                </td>
                                                <td style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label><input type="checkbox" id="medhis_diabetes" name="medhis_diabetes" value="1"> Diabetes</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label><input type="checkbox" id="medhis_bloodisorder" name="medhis_bloodisorder" value="1"> Blood disorder</label>
                                                </td>
                                                <td style="margin:0px; padding: 3px 14px 14px 14px;"> 
                                                    <label><input type="checkbox" id="medhis_hypertension" name="medhis_hypertension" value="1"> Hypertension</label>
                                                </td>
                                                <td style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label><input type="checkbox" id="medhis_asthma" name="medhis_asthma" value="1"> Asthma</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label><input type="checkbox" id="medhis_cva" name="medhis_cva" value="1"> CVA</label>
                                                </td>
                                                <td style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label><input type="checkbox" id="medhis_crf" name="medhis_crf" value="1"> CRF</label>
                                                </td>
                                                <td style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label><input type="checkbox" id="medhis_cancer" name="medhis_cancer" value="1"> Cancer</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label><input type="checkbox" id="medhis_drugabuse" name="medhis_drugabuse" value="1"> Drug Abuse</label>
                                                </td>
                                                <td style="margin:0px; padding: 3px 14px 14px 14px;"> 
                                                    <label>
                                                        <input type="checkbox" id="medhis_oth" name="medhis_oth" value="1"> Others 
                                                        <input type="text" id="medhis_oth_note" name="medhis_oth_note"> 
                                                    </label>
                                                </td>
                                                <td style="margin:0px; padding: 3px 14px 14px 14px;"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- <div class="field">
                                <label>Surgical History</label>
                                <textarea id="surgicalhistory" name="surgicalhistory" type="text" rows="4" ></textarea>
                            </div> -->

                            <!-- <div class="field">
                                <label>Family Medical History</label>
                                <textarea id="familymedicalhist" name="familymedicalhist" type="text" rows="4" ></textarea>
                            </div> -->

                            <div class="field">
                                <label>Current Medication/Last Dose</label>
                                <textarea id="currentmedication" name="currentmedication" type="text" rows="4" ></textarea>
                            </div>

                            <!-- <div class="field">
                                <label>Diagnosis</label>
                                <textarea id="diagnosis" name="diagnosis" type="text" rows="4" ></textarea>
                            </div> -->
                        </div>

                        <div class="ui segments">
                            <div class="ui secondary segment">ALLERGIES</div>
                            <div class="ui segment">
                                <table class="table table-sm table-hover">
                                    <tbody>
                                        <tr>
                                            <td><input type="checkbox" id="allergydrugs" name="allergydrugs" value="1"></td>
                                            <td><label for="allergydrugs">Meds</label></td>
                                            <td><textarea id="drugs_remarks" name="drugs_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <!-- <tr>
                                            <td><input type="checkbox" id="allergyplaster" name="allergyplaster" value="1"></td>
                                            <td><label for="allergyplaster">Plaster</label></td>
                                            <td><textarea id="plaster_remarks" name="plaster_remarks" type="text" rows="3"></textarea></td>
                                        </tr> -->
                                        <tr>
                                            <td><input type="checkbox" id="allergyfood" name="allergyfood" value="1"></td>
                                            <td><label for="allergyfood">Food</label></td>
                                            <td><textarea id="food_remarks" name="food_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <!-- <tr>
                                            <td><input type="checkbox" id="allergyenvironment" name="allergyenvironment" value="1"></td>
                                            <td><label for="allergyenvironment">Environment</label></td>
                                            <td><textarea id="environment_remarks" name="environment_remarks" type="text" rows="3"></textarea></td>
                                        </tr> -->
                                        <tr>
                                            <td><input type="checkbox" id="allergyothers" name="allergyothers" value="1"></td>
                                            <td><label for="allergyothers">Others</label></td>
                                            <td><textarea id="others_remarks" name="others_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <!-- <tr>
                                            <td><input type="checkbox" id="allergyunknown" name="allergyunknown" value="1"></td>
                                            <td><label for="allergyunknown">Unknown</label></td>
                                            <td><textarea id="unknown_remarks" name="unknown_remarks" type="text" rows="3"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" id="allergynone" name="allergynone" value="1"></td>
                                            <td><label for="allergynone">None</label></td>
                                            <td><textarea id="none_remarks" name="none_remarks" type="text" rows="3"></textarea></td>
                                        </tr> -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="eight wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">CONDITION ON ARRIVAL</div>
                        <div class="ui segment">
                        <div class="ui grid">
                            <div class="sixteen wide column ">
                                <div class="ui segments">
                                    <div class="ui secondary segment">VITAL SIGN</div>
                                    <div class="ui segment">
                                        <div class="ui grid">
                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Blood Pressure</label>
                                                <div class="ui right labeled input">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_bp_sys1" name="vs_bp_sys1" style="width:25%">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_bp_dias2" name="vs_bp_dias2" style="width:25%">
                                                  <div class="ui basic label">mmHg</div>
                                                </div>
                                            </div>

                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0 14px;">
                                                <label>SpO2</label>
                                                <div class="ui right labeled input">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_spo" name="vs_spo">
                                                  <div class="ui basic label">%</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ui grid">
                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Pulse</label>
                                                <div class="ui right labeled input">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_pulse" name="vs_pulse">
                                                    <div class="ui basic label">bpm</div>
                                                </div>
                                            </div>

                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Glucometer</label>
                                                <div class="ui right labeled input">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_gxt" name="vs_gxt">
                                                  <div class="ui basic label">mmOL/L</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ui grid">
                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Temperature</label>
                                                <div class="ui right labeled input">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_temperature" name="vs_temperature">
                                                  <div class="ui basic label">°C</div>
                                                </div>
                                            </div>

                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Weight</label>
                                                <div class="ui right labeled input">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_weight" name="vs_weight">
                                                  <div class="ui basic label">kg</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ui grid">
                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                <label>RR</label>
                                                <div class="ui right labeled input">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_respiration" name="vs_respiration">
                                                    <div class="ui basic label">min</div>
                                                </div>
                                            </div>

                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                <label>Height</label>
                                                <div class="ui right labeled input">
                                                  <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vs_height" name="vs_height">
                                                  <div class="ui basic label">cm</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sixteen wide column ">
                                <div class="ui segments">
                                    <div class="ui secondary segment">MODE OF ARRIVAL</div>
                                    <div class="ui segment">
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="moa_walkin" name="moa_walkin" value="1">
                                                 Walk
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="moa_carried" name="moa_carried" value="1">
                                                 Carried 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="moa_trolley" name="moa_trolley" value="1">
                                                 Stretcher 
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
                                                 Accompanying person: 
                                            </label>
                                            <label>
                                                <input type="checkbox" id="moa_accpera" name="moa_accpera" value="1">
                                                 Alone 
                                            </label>
                                            <label>
                                                <input type="checkbox" id="moa_accperna" name="moa_accperna" value="1">
                                                No. of Person
                                                <input type="text" id="moa_accperna_note" name="moa_accperna_note"> 
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="sixteen wide column ">
                                <div class="ui segments">
                                    <div class="ui secondary segment">TREATMENT PRIOR TO ARRIVAL</div>
                                    <div class="ui segment">
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="tpa_oxygen" name="tpa_oxygen" value="1">
                                                Oxygen 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="tpa_ccollar" name="tpa_ccollar" value="1">
                                                C-collar
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="tpa_backboard" name="tpa_backboard" value="1">
                                                Backboard 
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="tpa_icepack" name="tpa_icepack" value="1">
                                                Ice pack
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="tpa_others" name="tpa_others" value="1">
                                                Others
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="tpa_medication" name="tpa_medication" value="1">
                                                Medication
                                                <textarea id="tpa_medication_note" name="tpa_medication_note" type="text" class="form-control input-sm" rows="4"></textarea>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="eight wide column ">
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
                            </div> -->

                            <!-- <div class="eight wide column ">
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
                                                Confuse
                                            </label>
                                        </div>
                                        <div class="field">
                                            <label>
                                                <input type="checkbox" id="ms_semiconscious" name="ms_semiconscious" value="1">
                                                Semiconscious 
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
                            </div> -->

                            <!-- <div class="eight wide column ">
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
                            </div> -->

                            <!-- <div class="sixteen wide column ">
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
                            </div> -->

                            <!-- <div class="sixteen wide column ">
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
                            </div> -->
                        </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">ACTIVITIES OF DAILY LIVING</div>
                        <div class="ui segment">
                            @include('patientcare.nursing_aodl')
                        </div>
                    </div>
                </div> -->

                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">PHYSICAL ASSESSMENT</div>
                        <div class="ui segment">
                            <div class="ui grid">
                                <!-- <div class="four wide column">
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
                                </div> -->

                                <div class="eight wide column">
                                    <div class="ui segments">
                                        <div class="ui secondary segment">GLASGOW COMA SCALE
                                        <label for="totgsc" style="float:right;">
                                            Total: <input type="input" name="totgsc" id="totgsc" rdonly>
                                        </label> 
                                        </div>
                                        <div class="ui segment">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td colspan="2" rowspan="5" class="align-middle">Best Eye Response (E)</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gsc_eye" value="4" class="calc">Spontaneous (4)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gsc_eye" value="3" class="calc">to speech (3)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                                <input type="radio" name="gsc_eye" value="2" class="calc">to pain (2)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gsc_eye" value="1" class="calc">NIL (1)
                                                        </label>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2" rowspan="6" class="align-middle">Best Verbal Response (V)</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gsc_verbal" value="5" class="calc">Orientated (5)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gsc_verbal" value="4" class="calc">Confused (4)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                                <input type="radio" name="gsc_verbal" value="3" class="calc">Inappropriate (3)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gsc_verbal" value="2" class="calc">Incomprehensible (2)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gsc_verbal" value="1" class="calc">NIL (1)
                                                        </label>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2" rowspan="7" class="align-middle">Best Motor Response (M)</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gsc_motor" value="6" class="calc">Obey (6)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gsc_motor" value="5" class="calc">Localize (5)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                                <input type="radio" name="gsc_motor" value="4" class="calc">Withdraws (4)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gsc_motor" value="3" class="calc">Abnormal flexion (3)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gsc_motor" value="2" class="calc">Extends (2)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="gsc_motor" value="1" class="calc">NIL (1)
                                                        </label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        </div>
                                        <div class="four wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
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
                                                        Confuse
                                                    </label>
                                                </div>
                                                <div class="field">
                                                    <label>
                                                        <input type="checkbox" id="ms_semiconscious" name="ms_semiconscious" value="1">
                                                        Semiconscious 
                                                    </label>
                                                </div>
                                                <div class="field">
                                                    <label>
                                                        <input type="checkbox" id="ms_unconscious" name="ms_unconscious" value="1">
                                                        Unconscious 
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>

                                   
                                </div>

                                <div class="eight wide column">
                                    <div class="ui segments">
                                        <div class="ui secondary segment" for="painscore">PAIN SCORE</div>
                                        <div class="ui segment">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <td colspan="2" rowspan="2" class="align-middle">No Pain</td>
                                                        <td colspan="2" rowspan="2" class="align-middle">
                                                            <img class="ui tiny circular image" src="{{ asset('patientcare/img/painscore/no-pain.png') }}">                                                    
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="painscore" value="0">0
                                                            </label>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2" rowspan="4" class="align-middle">Mild Pain</td>
                                                        <td colspan="2" rowspan="4" class="align-middle">
                                                            <img class="ui tiny circular image" src="{{ asset('patientcare/img/painscore/mild-pain.png') }}">                                                    
                                                        </td>                                                
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="painscore" value="1">1
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="painscore" value="2">2
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                    <input type="radio" name="painscore" value="3">3
                                                            </label>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2" rowspan="4" class="align-middle">Moderate Pain</td>
                                                        <td colspan="2" rowspan="4">
                                                            <img class="ui tiny circular image" src="{{ asset('patientcare/img/painscore/moderate-pain.png') }}">                                                    
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="painscore" value="4">4
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="painscore" value="5">5
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                    <input type="radio" name="painscore" value="6">6
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td colspan="2" rowspan="4" class="align-middle">Severe Pain</td>
                                                        <td colspan="2" rowspan="4">
                                                            <img class="ui tiny circular image" src="{{ asset('patientcare/img/painscore/severe-pain.png') }}">                                                    
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="painscore" value="7">7
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="painscore" value="8">8
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                    <input type="radio" name="painscore" value="9">9
                                                            </label>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2" rowspan="2" class="align-middle">Worst Pain</td>
                                                        <td colspan="2" rowspan="2">
                                                            <img class="ui tiny circular image" src="{{ asset('patientcare/img/painscore/worst-pain.png') }}">                                                    
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label class="radio-inline">
                                                                <input type="radio" name="painscore" value="10">10
                                                            </label>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="four wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                            <div class="ui segments">
                                                <div class="ui secondary segment">DESCRIPTION OF PAIN</div>
                                                <div class="ui segment">
                                                    <div class="field">
                                                        <label>
                                                            <input type="checkbox" id="dop_arching" name="dop_arching" value="1">
                                                            Arching 
                                                        </label>
                                                    </div>
                                                    <div class="field">
                                                        <label>
                                                            <input type="checkbox" id="dop_throbbing" name="dop_throbbing" value="1">
                                                            Throbbing 
                                                        </label>
                                                    </div>
                                                    <div class="field">
                                                        <label>
                                                            <input type="checkbox" id="dop_stabbing" name="dop_stabbing" value="1">
                                                            Stabbing 
                                                        </label>
                                                    </div>
                                                    <div class="field">
                                                        <label>
                                                            <input type="checkbox" id="dop_sharp" name="dop_sharp" value="1">
                                                            Sharp 
                                                        </label>
                                                    </div>
                                                    <div class="field">
                                                        <label>
                                                            <input type="checkbox" id="dop_burning" name="dop_burning" value="1">
                                                            Burning 
                                                        </label>
                                                    </div>
                                                    <div class="field">
                                                        <label>
                                                            <input type="checkbox" id="dop_numb" name="dop_numb" value="1">
                                                            Numb 
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="four wide column" style="left: 1070px;">
                                    <div class="ui form">
                                        <div class="inline field">
                                            <label>Nurse's Signature: </label>
                                            <input type="text" name="nursesign" id="nursesign">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sixteen wide column">
                    <div class="ui segments">
                        <div class="ui secondary segment">NURSE'S NOTE FORM</div>
                        <div class="ui segment">
                            <div class="ui grid">
                                <div class="eight wide column">
                                    <div class="ui segments">
                                        <div class="ui secondary segment">PLAN & INTERVENTION</div>
                                        <div class="ui segment">
                                            <table class="table table-sm table-hover">
                                                <tbody>
                                                    <tr>
                                                        <td><input type="checkbox" id="pi_labinv" name="pi_labinv" value="1"></td>
                                                        <td><label for="pi_labinv">Laboratory Investigation's</label></td>
                                                        <td><textarea id="pi_labinv_remarks" name="pi_labinv_remarks" type="text" rows="3"></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" id="pi_bloodprod" name="pi_bloodprod" value="1"></td>
                                                        <td><label for="pi_bloodprod">Blood Product</label></td>
                                                        <td><textarea id="pi_bloodprod_remarks" name="pi_bloodprod_remarks" type="text" rows="3"></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" id="pi_diaginv" name="pi_diaginv" value="1"></td>
                                                        <td><label for="pi_diaginv">Diagnostic Investigation</label></td>
                                                        <td><textarea id="pi_diaginv_remarks" name="pi_diaginv_remarks" type="text" rows="3"></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" id="pi_ecg" name="pi_ecg" value="1"></td>
                                                        <td><label for="pi_ecg">ECG</label></td>
                                                        <td></td>
                                                    </tr>
                                                    <!-- <tr>
                                                        <td><input type="checkbox" id="pi_abg" name="pi_abg" value="1"></td>
                                                        <td><label for="pi_abg">ABG</label></td>
                                                        <td></td>
                                                    </tr> -->
                                                    <tr>
                                                        <td><input type="checkbox" id="pi_codeblue" name="pi_codeblue" value="1"></td>
                                                        <td><label for="pi_codeblue">Code Blue</label></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="eight wide column">
                                    <div class="ui segments">
                                        <div class="ui secondary segment">MAINTENANCE OF SUPPORTS</div>
                                        <div class="ui segment" style="height: 410px">
                                            <table class="table table-sm table-hover">
                                                <tbody>
                                                    <tr>
                                                        <td><input type="checkbox" id="mos_ivfluids" name="mos_ivfluids" value="1"></td>
                                                        <td><label for="mos_ivfluids">IV Fluids</label></td>
                                                        <td><textarea id="mos_ivfluids_remarks" name="mos_ivfluids_remarks" type="text" rows="3"></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" id="mos_oxygen" name="mos_oxygen" value="1"></td>
                                                        <td><label for="mos_oxygen">Oxygen</label></td>
                                                        <td><textarea id="mos_oxygen_remarks" name="mos_oxygen_remarks" type="text" rows="3"></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" id="mos_woundprep" name="mos_woundprep" value="1"></td>
                                                        <td><label for="mos_woundprep">Wound Prep</label></td>
                                                        <td><textarea id="mos_woundprep_remarks" name="mos_woundprep_remarks" type="text" rows="3"></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" id="mos_sci" name="mos_sci" value="1"></td>
                                                        <td><label for="mos_sci">Splint/Crutches/Ice Pack</label></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ui segment">
                            <div class="ui grid">
                                <div class="eight wide column ">
                                    <div class="ui segments">
                                        <div class="ui secondary segment">VITAL SIGN ON DISCHARGE</div>
                                        <div class="ui segment" style="height: 380px;margin-left: 0px">
                                            <div class="ui grid">
                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 10px 14px;">
                                                    <label>BP</label>
                                                    <div class="ui right labeled input">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vsd_bp_sys1" name="vsd_bp_sys1" style="width:25%">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vsd_bp_dias2" name="vsd_bp_dias2" style="width:25%">
                                                    <div class="ui basic label">mmHg</div>
                                                    </div>
                                                </div>

                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 10px 14px;">
                                                    <label>PR</label>
                                                    <div class="ui right labeled input">
                                                        <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vsd_pulse" name="vsd_pulse">
                                                        <div class="ui basic label">min</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="ui grid">
                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>Temperature</label>
                                                    <div class="ui right labeled input">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vsd_temperature" name="vsd_temperature">
                                                    <div class="ui basic label">°C</div>
                                                    </div>
                                                </div>

                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>RR</label>
                                                    <div class="ui right labeled input">
                                                        <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vsd_respiration" name="vsd_respiration">
                                                        <div class="ui basic label">min</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="ui grid">
                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>SpO2</label>
                                                    <div class="ui right labeled input">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vsd_spo" name="vsd_spo">
                                                    <div class="ui basic label">%</div>
                                                    </div>
                                                </div>

                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>CBS</label>
                                                    <div class="ui right labeled input">
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vsd_cbs" name="vsd_cbs">
                                                    <div class="ui basic label">mmOL/L</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="ui grid">
                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>PEFR</label>
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vsd_pefr" name="vsd_pefr">
                                                </div>

                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                    <label>GCS</label>
                                                    <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vsd_gcs" name="vsd_gcs">
                                                </div>
                                            </div>

                                            <div class="ui grid">
                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label>Pain</label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="vsd_pain" value="0">No <br>
                                                            <input type="radio" name="vsd_pain" value="1">Yes, Score
                                                            <input type="text" id="vsd_painscore" name="vsd_painscore"> 
                                                        </label>                                                
                                                    </div>

                                                <div class="field eight wide column" style="margin:0px; padding: 3px 14px 14px 14px;">
                                                    <label><br></label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="vsd_painroomair" value="vsd_painroomair">on Room air<br>
                                                            <input type="radio" name="vsd_painoxygen" value="vsd_painoxygen">on O2
                                                            <div class="ui right labeled input">
                                                                <input type="text" onKeyPress="if(this.value.length==6) return false;" id="vsd_painoxygen_note" name="vsd_painoxygen_note">
                                                                <div class="ui basic label">L/min</div>
                                                                </div>               
                                                        </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="eight wide column ">
                                    <div class="ui segments">
                                        <div class="ui secondary segment" style="margin-left: 0px">MODE OF DISCHARGE</div>
                                        <div class="ui segment" style="height: 380px;margin-left: 0px">
                                            <div class="field">
                                                <label>
                                                    <input type="checkbox" id="mod_walk" name="mod_walk" value="1">
                                                    Walk
                                                </label>
                                            </div>
                                            <div class="field">
                                                <label>
                                                    <input type="checkbox" id="mod_carried" name="mod_carried" value="1">
                                                    Carried 
                                                </label>
                                            </div>
                                            <div class="field">
                                                <label>
                                                    <input type="checkbox" id="mod_trolley" name="mod_trolley" value="1">
                                                    Stretcher 
                                                </label>
                                            </div>
                                            <div class="field">
                                                <label>
                                                    <input type="checkbox" id="mod_wheelchair" name="mod_wheelchair" value="1">
                                                    Wheel Chair 
                                                </label>
                                            </div>
                                            <div class="field">
                                                <label>
                                                    <input type="checkbox" id="mod_ambulance" name="mod_ambulance" value="1">
                                                    Ambulance
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="four wide column">
                                </div>
                                <div class="four wide column">
                                    <div class="ui form">
                                        <div class="inline field">
                                            <label>A&E Staff: </label>
                                            <input type="text" name="eduser" id="eduser">
                                        </div>
                                    </div>
                                </div>
                                <div class="four wide column">
                                    <div class="ui form">
                                        <div class="inline field">
                                            <label>Ward Staff: </label>
                                            <input type="text" name="warduser" id="warduser">
                                        </div>
                                    </div>
                                </div>
                                <div class="four wide column">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="sixteen wide column">
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
                </div> -->
            </div>

        </form>
    </div>
</div>
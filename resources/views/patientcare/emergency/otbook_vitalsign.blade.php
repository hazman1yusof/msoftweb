
<div class="eight wide column">
    <div class="ui segments">
        <div class="ui secondary segment">VITAL SIGN</div>
        <div class="ui segment" style="height: 335px;">
            <div class="ui grid">
                <div class="field eight wide column" style="margin: 0px; padding: 3px 14px 0px 14px;">
                    <label>Blood Pressure</label>
                    <div class="ui right labeled input">
                        <input type="text" onKeyPress="if(this.value.length==6) return false;" name="vs_bp_sys1" style="width: 25%;" rdonly>
                        <input type="text" onKeyPress="if(this.value.length==6) return false;" name="vs_bp_dias2" style="width: 25%;" rdonly>
                        <div class="ui basic label">mmHg</div>
                    </div>
                </div>
                
                <div class="field eight wide column" style="margin: 0px; padding: 3px 14px 0 14px;">
                    <label>SpO2</label>
                    <div class="ui right labeled input">
                        <input type="text" onKeyPress="if(this.value.length==6) return false;" name="vs_spo" rdonly>
                        <div class="ui basic label">%</div>
                    </div>
                </div>
            </div>
            
            <div class="ui grid">
                <div class="field eight wide column" style="margin: 0px; padding: 3px 14px 0px 14px;">
                    <label>Pulse</label>
                    <div class="ui right labeled input">
                        <input type="text" onKeyPress="if(this.value.length==6) return false;" name="vs_pulse" rdonly>
                        <div class="ui basic label">bpm</div>
                    </div>
                </div>
                
                <div class="field eight wide column" style="margin: 0px; padding: 3px 14px 0px 14px;">
                    <label>Glucometer</label>
                    <div class="ui right labeled input">
                        <input type="text" onKeyPress="if(this.value.length==6) return false;" name="vs_gxt" rdonly>
                        <div class="ui basic label">mmOL/L</div>
                    </div>
                </div>
            </div>
            
            <div class="ui grid">
                <div class="field eight wide column" style="margin: 0px; padding: 3px 14px 0px 14px;">
                    <label>Temperature</label>
                    <div class="ui right labeled input">
                        <input type="text" onKeyPress="if(this.value.length==6) return false;" name="vs_temperature" rdonly>
                        <div class="ui basic label">°C</div>
                    </div>
                </div>
                
                <div class="field eight wide column" style="margin: 0px; padding: 3px 14px 0px 14px;">
                    <label>Weight</label>
                    <div class="ui right labeled input">
                        <input type="text" onKeyPress="if(this.value.length==6) return false;" name="vs_weight" rdonly>
                        <div class="ui basic label">kg</div>
                    </div>
                </div>
            </div>
            
            <div class="ui grid">
                <div class="field eight wide column" style="margin: 0px; padding: 3px 14px 14px 14px;">
                    <label>RR</label>
                    <div class="ui right labeled input">
                        <input type="text" onKeyPress="if(this.value.length==6) return false;" name="vs_respiration" rdonly>
                        <div class="ui basic label">min</div>
                    </div>
                </div>
                
                <div class="field eight wide column" style="margin: 0px; padding: 3px 14px 14px 14px;">
                    <label>Height</label>
                    <div class="ui right labeled input">
                        <input type="text" onKeyPress="if(this.value.length==6) return false;" name="vs_height" rdonly>
                        <div class="ui basic label">cm</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="eight wide column">
    <div class="ui segments">
        <div class="ui secondary segment">ALLERGIES</div>
        <div class="ui segment">
            <table class="table table-sm table-hover">
                <tbody>
                    <tr>
                        <td><input type="checkbox" id="ReqFor_allergydrugs" name="allergydrugs" value="1" class="ui read-only checkbox" class="hidden" readonly="" tabindex="0"></td>
                        <td><label for="ReqFor_allergydrugs1">Meds</label></td>
                        <td><textarea id="ReqFor_drugs_remarks" name="drugs_remarks" type="text" rows="3" readonly="" disabled></textarea></td>
                    </tr>
                    <!-- <tr>
                        <td><input type="checkbox" id="ReqFor_allergyplaster" name="allergyplaster" value="1" class="ui read-only checkbox" class="hidden" readonly="" tabindex="0"></td>
                        <td><label for="ReqFor_allergyplaster1">Plaster</label></td>
                        <td><textarea id="ReqFor_plaster_remarks" name="plaster_remarks" type="text" rows="3" readonly="" disabled></textarea></td>
                    </tr> -->
                    <tr>
                        <td><input type="checkbox" id="ReqFor_allergyfood" name="allergyfood" value="1" class="ui read-only checkbox" class="hidden" readonly="" tabindex="0"></td>
                        <td><label for="ReqFor_allergyfood1">Food</label></td>
                        <td><textarea id="ReqFor_food_remarks" name="food_remarks" type="text" rows="3" readonly="" disabled></textarea></td>
                    </tr>
                    <!-- <tr>
                        <td><input type="checkbox" id="ReqFor_allergyenvironment" name="allergyenvironment" value="1" class="ui read-only checkbox" class="hidden" readonly="" tabindex="0"></td>
                        <td><label for="ReqFor_allergyenvironment1">Environment</label></td>
                        <td><textarea id="ReqFor_environment_remarks" name="environment_remarks" type="text" rows="3" readonly="" disabled></textarea></td>
                    </tr> -->
                    <tr>
                        <td><input type="checkbox" id="ReqFor_allergyothers" name="allergyothers" value="1" class="ui read-only checkbox" class="hidden" readonly="" tabindex="0"></td>
                        <td><label for="ReqFor_allergyothers1">Others</label></td>
                        <td><textarea id="ReqFor_others_remarks" name="others_remarks" type="text" rows="3" readonly="" disabled></textarea></td>
                    </tr>
                    <!-- <tr>
                        <td><input type="checkbox" id="ReqFor_allergyunknown" name="allergyunknown" value="1" class="ui read-only checkbox" class="hidden" readonly="" tabindex="0"></td>
                        <td><label for="ReqFor_allergyunknown1">Unknown</label></td>
                        <td><textarea id="ReqFor_unknown_remarks" name="unknown_remarks" type="text" rows="3" readonly="" disabled></textarea></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" id="ReqFor_allergynone" name="allergynone" value="1" class="ui read-only checkbox" class="hidden" readonly="" tabindex="0"></td>
                        <td><label for="ReqFor_allergynone1">None</label></td>
                        <td><textarea id="ReqFor_none_remarks" name="none_remarks" type="text" rows="3" readonly="" disabled></textarea></td>
                    </tr> -->
                </tbody>
            </table>
        </div>
    </div>
</div>
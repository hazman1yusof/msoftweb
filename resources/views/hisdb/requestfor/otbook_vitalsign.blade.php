
<div class='col-md-6'>
    <div class="panel panel-info">
        <div class="panel-heading text-center">VITAL SIGN</div>
        <div class="panel-body">
            <div class="form-row">
                <div class="form-group col-md-6" style="margin-left: 2px;">
                    <label for="vs_bloodpressure">BP</label>
                    <div class="input-group">
                        <input name="vs_bp_sys1" type="number" class="form-control input-sm" style="width: 50%;" rdonly>
                        <!-- <label class="col-md-1 control-label">/</label> -->
                        <input name="vs_bp_dias2" type="number" class="form-control input-sm" style="width: 50%;" rdonly>
                        <span class="input-group-addon">mmHg</span>
                    </div>
                </div>
                
                <div class="form-group col-md-6" style="margin-left: 2px;">
                    <label for="vs_spo">SPO2</label>
                    <div class="input-group">
                        <input name="vs_spo" type="number" class="form-control input-sm" rdonly>
                        <span class="input-group-addon">%</span>
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-6" style="margin-left: 2px;">
                    <label for="vs_pulse">Pulse</label>
                    <div class="input-group">
                        <input name="vs_pulse" type="number" class="form-control input-sm" rdonly>
                        <span class="input-group-addon">Bpm</span>
                    </div>
                </div>
                
                <div class="form-group col-md-6" style="margin-left: 2px;">
                    <label for="vs_gxt">Glucometer</label>
                    <div class="input-group">
                        <input name="vs_gxt" type="number" class="form-control input-sm" rdonly>
                        <span class="input-group-addon">mmol/L</span>
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-6" style="margin-left: 2px;">
                    <label for="vs_temperature">Temperature</label>
                    <div class="input-group">
                        <input name="vs_temperature" type="number" class="form-control input-sm" rdonly>
                        <span class="input-group-addon">°C</span>
                    </div>
                </div>
                
                <div class="form-group col-md-6" style="margin-left: 2px;">
                    <label for="vs_weight">Weight</label> 
                    <div class="input-group">
                        <input name="vs_weight" type="number" class="form-control input-sm" rdonly>
                        <span class="input-group-addon">kg</span>
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-6" style="margin-left: 2px;">
                    <label for="vs_respiration">RR</label>
                    <div class="input-group">
                        <input name="vs_respiration" type="number" class="form-control input-sm" rdonly>
                        <span class="input-group-addon">Min</span>
                    </div>
                </div>
                
                <div class="form-group col-md-6" style="margin-left: 2px;">
                    <label for="vs_height">Height</label> 
                    <div class="input-group">
                        <input name="vs_height" type="number" class="form-control input-sm" rdonly>
                        <span class="input-group-addon">cm</span>
                    </div>
                </div>
            </div>
            
            <!-- <div class="form-row">
                <div class="form-group col-md-6" style="margin-left: 2px;">
                    <label for="vs_painscore">Pain Score</label>
                    <div class="input-group">
                        <input name="vs_painscore" type="number" class="form-control input-sm" rdonly>
                        <span class="input-group-addon">/10</span>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>

<div class='col-md-6'>
    <div class="panel panel-info">
        <div class="panel-heading text-center">ALLERGIES</div>
        <div class="panel-body" style="height: 258px;">
            <table class="table table-sm table-hover">
                <tbody>
                    <tr>
                        <td><input class="form-check-input" type="checkbox" id="ReqFor_allergydrugs" name="allergydrugs" value="1" disabled></td>
                        <td><label class="form-check-label" for="ReqFor_allergydrugs">Meds</label></td>
                        <td><textarea id="ReqFor_drugs_remarks" name="drugs_remarks" type="text" class="form-control input-sm" rdonly></textarea></td>
                    </tr>
                    <!-- <tr>
                        <td><input class="form-check-input" type="checkbox" id="ReqFor_allergyplaster" name="allergyplaster" value="1" disabled></td>
                        <td><label class="form-check-label" for="ReqFor_allergyplaster">Plaster</label></td>
                        <td><textarea id="ReqFor_plaster_remarks" name="plaster_remarks" type="text" class="form-control input-sm" rdonly></textarea></td>
                    </tr> -->
                    <tr>
                        <td><input class="form-check-input" type="checkbox" id="ReqFor_allergyfood" name="allergyfood" value="1" disabled></td>
                        <td><label class="form-check-label" for="ReqFor_allergyfood">Food</label></td>
                        <td><textarea id="ReqFor_food_remarks" name="food_remarks" type="text" class="form-control input-sm" rdonly></textarea></td>
                    </tr>
                    <!-- <tr>
                        <td><input class="form-check-input" type="checkbox" id="ReqFor_allergyenvironment" name="allergyenvironment" value="1" disabled></td>
                        <td><label class="form-check-label" for="ReqFor_allergyenvironment">Environment</label></td>
                        <td><textarea id="ReqFor_environment_remarks" name="environment_remarks" type="text" class="form-control input-sm" rdonly></textarea></td>
                    </tr> -->
                    <tr>
                        <td><input class="form-check-input" type="checkbox" id="ReqFor_allergyothers" name="allergyothers" value="1" disabled></td>
                        <td><label class="form-check-label" for="ReqFor_allergyothers">Others</label></td>
                        <td><textarea id="ReqFor_others_remarks" name="others_remarks" type="text" class="form-control input-sm" rdonly></textarea></td>
                    </tr>
                    <!-- <tr>
                        <td><input class="form-check-input" type="checkbox" id="ReqFor_allergyunknown" name="allergyunknown" value="1" disabled></td>
                        <td><label class="form-check-label" for="ReqFor_allergyunknown">Unknown</label></td>
                        <td><textarea id="ReqFor_unknown_remarks" name="unknown_remarks" type="text" class="form-control input-sm" rdonly></textarea></td>
                    </tr>
                    <tr>
                        <td><input class="form-check-input" type="checkbox" id="ReqFor_allergynone" name="allergynone" value="1" disabled></td>
                        <td><label class="form-check-label" for="ReqFor_allergynone">None</label></td>
                        <td><textarea id="ReqFor_none_remarks" name="none_remarks" type="text" class="form-control input-sm" rdonly></textarea></td>
                    </tr> -->
                </tbody>
            </table>
        </div>
    </div>
</div>
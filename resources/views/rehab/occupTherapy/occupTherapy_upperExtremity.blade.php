<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        UPPER EXTREMITY ASSESSMENT FORM
        <div class="ui small blue icon buttons" id="btn_grp_edit_upperExtremity" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_upperExtremity"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_upperExtremity"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_upperExtremity"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_upperExtremity"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <!-- <button class="ui button" id="upperExtremity_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <div class="sixteen wide column">
                <div class="ui grid">
                    <div class='three wide column' style="padding: 3px 3px 3px 3px;">
                        <div class="ui segments">
                            <div class="ui segment">
                                <div class="ui grid">
                                    <table id="datetimeUpperExtremity_tbl" class="ui celled table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="scope">idno</th>
                                                <th class="scope">mrn</th>
                                                <th class="scope">episno</th>
                                                <th class="scope">Date</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class='thirteen wide column' style="padding: 3px 3px 3px 3px;">
                        <form id="formOccupTherapyUpperExtremity" class="floated ui form sixteen wide column">
                            <input id="idno_upperExtremity" name="idno_upperExtremity" type="hidden">
                            <div class="ui segment">
                                <div class='ui grid'>
                                    <div class="sixteen wide column">

                                        <div class="ui grid">
                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Date</label>
                                                <input id="dateAssess" name="dateAssess" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" data-validation="required" data-validation-error-msg-required="Please enter information.">
                                            </div>

                                            <div class="field eight wide column" style="margin:0px; padding: 3px 20px 14px 14px;">
                                                <label>Occupational Therapist</label>
                                                <input type="text" id="occupTherapist" name="occupTherapist" rdonly>
                                            </div>
                                            
                                        </div>

                                        <div class="ui grid">
                                            <div class="field eight wide column" style="margin:0px; padding: 3px 14px 0px 14px;">
                                                <label>Right/Left Dominant</label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="handDominant" value="R" class="score">Right
                                                    </label>
                                                    <label class="radio-inline" style="padding-right: 5px;">
                                                        <input type="radio" name="handDominant" value="L" class="score">Left
                                                    </label>                                            
                                            </div>

                                            <div class="field eight wide column" style="margin:0px; padding: 3px 20px 50px 14px;">
                                                <label>Diagnosis</label>
                                                <textarea id="diagnosis" name="diagnosis" row="4"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div id="upExt" class="ui bottom attached tab raised segment active" style="padding: 10px 3px 3px 3px;">
                            <div class="ui top attached tabular menu">
                                <a class="item active" data-tab="rof"><h5>Range of Motion</h5></a>
                                <a class="item" data-tab="hand"><h5>Hand</h5></a>
                                <a class="item" data-tab="strength"><h5>Muscle<br>Strength</h5></a>
                                <a class="item" data-tab="sensation"><h5>Sensation</h5></a>
                                <a class="item" data-tab="prehensive"><h5>Prehensive Pattern</h5></a>
                                <a class="item" data-tab="skin"><h5>Skin Condition/<br>Scarring</h5></a>
                                <a class="item" data-tab="edema"><h5>Edema</h5></a>
                                <a class="item" data-tab="functional"><h5>Functional<br>Activities</h5></a>

                            </div>

                            <div class="ui bottom attached tab raised segment active" data-tab="rof">
                                <div class="ui segments" style="position: relative;">
                                    <div class="ui secondary segment" style="height: 50px;">
                                        <div class="ui small green icon buttons" id="btn_grp_edit_rof" style="position: absolute;
                                            padding: 0 0 0 0;
                                            right: 40px;
                                            top: 9px;
                                            z-index: 2;">
                                            <button class="ui button" id="new_rof"><span class="fa fa-plus-square-o"></span>New</button>
                                            <button class="ui button" id="edit_rof"><span class="fa fa-edit fa-lg"></span>Edit</button>
                                            <button class="ui button" id="save_rof"><span class="fa fa-save fa-lg"></span>Save</button>
                                            <button class="ui button" id="cancel_rof"><span class="fa fa-ban fa-lg"></span>Cancel</button>
                                            <!-- <button class="ui button" id="rof_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
                                        </div>
                                    </div>
                                    <div class="ui segment">
                                        <div class="ui grid">
                                            <form id="formROF" class="floated ui form sixteen wide column">
                                                <input id="idno_rof" name="idno_rof" type="hidden">
                                                <input id="rof_impressions" name="rof_impressions" type="hidden" value="ROF">

                                                <div class="sixteen wide column">
                                                    
                                                    <div id="jqGrid_rof_c" style="padding: 3px 3px 3px 3px;">
                                                        <table id="jqGrid_rof" class="table table-striped"></table>
                                                        <div id="jqGridPager_rof"></div>
                                                    </div>

                                                    <table class="ui celled table">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="2">IMPRESSIONS</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="sixteen wide">
                                                                    <div class="ui form">
                                                                        <textarea id="impressions" name="impressions" rows="6"></textarea>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ui bottom attached tab raised segment" data-tab="hand">
                                <div class="ui segments" style="position: relative;">
                                    <div class="ui secondary segment" style="height: 50px;">
                                        <div class="ui small green icon buttons" id="btn_grp_edit_hand" style="position: absolute;
                                            padding: 0 0 0 0;
                                            right: 40px;
                                            top: 9px;
                                            z-index: 2;">
                                            <button class="ui button" id="new_hand"><span class="fa fa-plus-square-o"></span>New</button>
                                            <button class="ui button" id="edit_hand"><span class="fa fa-edit fa-lg"></span>Edit</button>
                                            <button class="ui button" id="save_hand"><span class="fa fa-save fa-lg"></span>Save</button>
                                            <button class="ui button" id="cancel_hand"><span class="fa fa-ban fa-lg"></span>Cancel</button>
                                            <!-- <button class="ui button" id="hand_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
                                        </div>
                                    </div>
                                    <div class="ui segment">
                                        <div class="ui grid">
                                            <form id="formHand" class="floated ui form sixteen wide column">
                                                <input id="idno_hand" name="idno_hand" type="hidden">
                                                <input id="hand_impressions" name="hand_impressions" type="hidden" value="hand">

                                                <div class="sixteen wide column">
                                                    <div id="jqGrid_hand_c" style="padding: 3px 3px 3px 3px;">
                                                        <table id="jqGrid_hand" class="table table-striped"></table>
                                                        <div id="jqGridPager_hand"></div>
                                                    </div>
                                                </div>

                                                <table class="ui celled table">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="2">IMPRESSIONS</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="sixteen wide">
                                                                <div class="ui form">
                                                                    <textarea id="impressions" name="impressions" rows="6"></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ui bottom attached tab raised segment" data-tab="strength">
                                <div class="ui segments" style="position: relative;">
                                    <div class="ui secondary segment" style="height: 50px;">
                                        <div class="ui small green icon buttons" id="btn_grp_edit_strength" style="position: absolute;
                                            padding: 0 0 0 0;
                                            right: 40px;
                                            top: 9px;
                                            z-index: 2;">
                                            <button class="ui button" id="new_strength"><span class="fa fa-plus-square-o"></span>New</button>
                                            <button class="ui button" id="edit_strength"><span class="fa fa-edit fa-lg"></span>Edit</button>
                                            <button class="ui button" id="save_strength"><span class="fa fa-save fa-lg"></span>Save</button>
                                            <button class="ui button" id="cancel_strength"><span class="fa fa-ban fa-lg"></span>Cancel</button>
                                            <!-- <button class="ui button" id="strength_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
                                        </div>
                                    </div>

                                    <div class="ui segment">
                                        <div class="ui grid">
                                            <form id="formStrength" class="floated ui form sixteen wide column">
                                                <input id="idno_strength" name="idno_strength" type="hidden">
                                                <div class="sixteen wide column">
                                                    <table class="ui celled table">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="2">MUSCLE STRENGTH</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="eight wide">
                                                                    <div class="ui field">
                                                                        <label>
                                                                            <input type="checkbox" name="mmt" id="mmt" value="1">    
                                                                            Oxford Manual Muscle Testing
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td class="eight wide">
                                                                    <div class="ui field">
                                                                        <label>
                                                                            <input type="checkbox" name="jamar" id="jamar" value="1">
                                                                            Jamar Dynamometer
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            
                                                        </tbody>
                                                    </table>

                                                    <table class="ui celled table">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="2">GRIP STRENGTH</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="eight wide">
                                                                    <div class="ui form">
                                                                        <textarea id="mmt_grip" name="mmt_grip" rows="6"></textarea>
                                                                    </div>
                                                                </td>
                                                                <td class="eight wide">
                                                                    <div class="inline fields">
                                                                        <div class="field">
                                                                            <label for="jamarGripDate" style="padding-left: 10px;">Date: </label>
                                                                            <input id="jamarGripDate" name="jamarGripDate" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>">
                                                                        </div>                                         
                                                                    </div>
                                                                    <table class="table;border border-white">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <label>Rt Hand: </label>
                                                                                    <div class="ui right labeled input" style="margin:0px; padding: 0px 0px 0px 10px;">
                                                                                        <input type="text" onKeyPress="if(this.value.length==6) return false;" id="jamarGrip_rt" name="jamarGrip_rt">
                                                                                        <div class="ui basic label">kg</div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                             <tr>
                                                                                <td>
                                                                                    <label>Lt Hand: </label>
                                                                                    <div class="ui right labeled input" style="margin:0px; padding: 0px 0px 0px 10px;">
                                                                                        <input type="text" onKeyPress="if(this.value.length==6) return false;" id="jamarGrip_lt" name="jamarGrip_lt">
                                                                                        <div class="ui basic label">kg</div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <table class="ui celled table">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="2">PINCH STRENGTH</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="eight wide">
                                                                    <div class="ui form">
                                                                        <textarea id="mmt_pinch" name="mmt_pinch" rows="12"></textarea>
                                                                    </div>
                                                                </td>
                                                                <td class="eight wide">
                                                                    <div class="inline fields">
                                                                        <div class="field">
                                                                            <label for="jamarPinchDate" style="padding-left: 10px;">Date: </label>
                                                                            <input id="jamarPinchDate" name="jamarPinchDate" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>">
                                                                        </div>                                         
                                                                    </div>
                                                                    <table class="table small;border border-white">
                                                                        <thead>
                                                                            <tr>
                                                                                <th></th>
                                                                                <th>Rt Hand</th>
                                                                                <th>Lt Hand</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Lateral</td>
                                                                                <td>
                                                                                    <div class="ui mini right labeled input" style="padding-right: 5px;">
                                                                                        <input type="text" id="jamarPinch_lateral_rt" name="jamarPinch_lateral_rt" size="15">
                                                                                        <div class="ui basic label">kg</div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="ui mini right labeled input">
                                                                                        <input type="text" id="jamarPinch_lateral_lt" name="jamarPinch_lateral_lt" size="15">
                                                                                        <div class="ui basic label">kg</div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Pad</td>
                                                                                <td>
                                                                                    <div class="ui mini right labeled input" style="padding-right: 5px;">
                                                                                        <input type="text" id="jamarPinch_pad_rt" name="jamarPinch_pad_rt" size="15">
                                                                                        <div class="ui basic label">kg</div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="ui mini right labeled input">
                                                                                        <input type="text" id="jamarPinch_pad_lt" name="jamarPinch_pad_lt" size="15">
                                                                                        <div class="ui basic label">kg</div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3-Jaw Chuck</td>
                                                                                <td>
                                                                                    <div class="ui mini right labeled input" style="padding-right: 5px;">
                                                                                        <input type="text" id="jamarPinch_jaw_rt" name="jamarPinch_jaw_rt" size="15">
                                                                                        <div class="ui basic label">kg</div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="ui mini right labeled input">
                                                                                        <input type="text" id="jamarPinch_jaw_lt" name="jamarPinch_jaw_lt" size="15">
                                                                                        <div class="ui basic label">kg</div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <table class="ui celled table">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="2">IMPRESSIONS</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="sixteen wide">
                                                                    <div class="ui form">
                                                                        <textarea id="impressions" name="impressions" rows="6"></textarea>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ui bottom attached tab raised segment" data-tab="sensation">
                                <div class="ui segments" style="position: relative;">
                                    <div class="ui secondary segment" style="height: 50px;">
                                        <div class="ui small green icon buttons" id="btn_grp_edit_sensation" style="position: absolute;
                                            padding: 0 0 0 0;
                                            right: 40px;
                                            top: 9px;
                                            z-index: 2;">
                                            <button class="ui button" id="new_sensation"><span class="fa fa-plus-square-o"></span>New</button>
                                            <button class="ui button" id="edit_sensation"><span class="fa fa-edit fa-lg"></span>Edit</button>
                                            <button class="ui button" id="save_sensation"><span class="fa fa-save fa-lg"></span>Save</button>
                                            <button class="ui button" id="cancel_sensation"><span class="fa fa-ban fa-lg"></span>Cancel</button>
                                            <!-- <button class="ui button" id="sensation_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
                                        </div>
                                    </div>
                                    <div class="ui segment">
                                        <div class="ui grid">
                                            <form id="formSensation" class="floated ui form sixteen wide column">
                                                <input id="idno_sensation" name="idno_sensation" type="hidden">
                                                <div class="sixteen wide column">
                                                    <div class="ui grid">
                                                        <table class="table table-bordered" style="margin: 0px; padding: 3px 3px 3px 3px;">
                                                            <thead>
                                                                <tr>
                                                                    <td rowspan="2"></td>
                                                                    <th colspan="2"><center>Sharp</center></th>
                                                                    <th colspan="2"><center>Dull</center></th>
                                                                    <th colspan="2"><center>Light Touch</center></th>
                                                                    <th colspan="2"><center>Deep Touch</center></th>
                                                                    <th><center>Stereognosis</center></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="col"><center>Rt</center></th>
                                                                    <th scope="col"><center>Lt</center></th>
                                                                    <th scope="col"><center>Rt</center></th>
                                                                    <th scope="col"><center>Lt</center></th>
                                                                    <th scope="col"><center>Rt</center></th>
                                                                    <th scope="col"><center>Lt</center></th>
                                                                    <th scope="col"><center>Rt</center></th>
                                                                    <th scope="col"><center>Lt</center></th>
                                                                    <th scope="col"></th>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Intact</th>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_sharpIntact_rt" id="sens_sharpIntact_rt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_sharpIntact_lt" id="sens_sharpIntact_lt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_dullIntact_rt" id="sens_dullIntact_rt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_dullIntact_lt" id="sens_dullIntact_lt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_lightIntact_rt" id="sens_lightIntact_rt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_lightIntact_lt" id="sens_lightIntact_lt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_deepIntact_rt" id="sens_deepIntact_rt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_deepIntact_lt" id="sens_deepIntact_lt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="text" name="sens_stereoIntact" id="sens_stereoIntact">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <th scope="row">Impaired</th>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_sharpImpaired_rt" id="sens_sharpImpaired_rt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_sharpImpaired_lt" id="sens_sharpImpaired_lt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_dullImpaired_rt" id="sens_dullImpaired_rt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_dullImpaired_lt" id="sens_dullImpaired_lt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_lightImpaired_rt" id="sens_lightImpaired_rt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_lightImpaired_lt" id="sens_lightImpaired_lt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_deepImpaired_rt" id="sens_deepImpaired_rt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_deepImpaired_lt" id="sens_deepImpaired_lt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="text" name="sens_stereoImpaired" id="sens_stereoImpaired">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <th scope="row">Absent</th>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_sharpAbsent_rt" id="sens_sharpAbsent_rt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_sharpAbsent_lt" id="sens_sharpAbsent_lt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_dullAbsent_rt" id="sens_dullAbsent_rt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_dullAbsent_lt" id="sens_dullAbsent_lt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_lightAbsent_rt" id="sens_lightAbsent_rt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_lightAbsent_lt" id="sens_lightAbsent_lt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_deepAbsent_rt" id="sens_deepAbsent_rt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align:center; vertical-align:middle;">
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="checkbox" name="sens_deepAbsent_lt" id="sens_deepAbsent_lt" value="1">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="ui form">
                                                                            <div class="field">
                                                                                <input type="text" name="sens_stereoAbsent" id="sens_stereoAbsent">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </thead>
                                                        </table>

                                                        <table class="ui celled table">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="2">IMPRESSIONS</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="sixteen wide">
                                                                        <div class="ui form">
                                                                            <textarea id="impressions" name="impressions" rows="6"></textarea>
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
                                
                            </div>

                            <div class="ui bottom attached tab raised segment" data-tab="prehensive">
                                <div class="ui segments" style="position: relative;">
                                    <div class="ui secondary segment" style="height: 50px;">
                                        <div class="ui small green icon buttons" id="btn_grp_edit_prehensive" style="position: absolute;
                                            padding: 0 0 0 0;
                                            right: 40px;
                                            top: 9px;
                                            z-index: 2;">
                                            <button class="ui button" id="new_prehensive"><span class="fa fa-plus-square-o"></span>New</button>
                                            <button class="ui button" id="edit_prehensive"><span class="fa fa-edit fa-lg"></span>Edit</button>
                                            <button class="ui button" id="save_prehensive"><span class="fa fa-save fa-lg"></span>Save</button>
                                            <button class="ui button" id="cancel_prehensive"><span class="fa fa-ban fa-lg"></span>Cancel</button>
                                            <!-- <button class="ui button" id="prehensive_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
                                        </div>
                                    </div>
                                    <div class="ui segment">
                                        <div class="ui grid">
                                            <form id="formPrehensive" class="floated ui form sixteen wide column">
                                                <input id="idno_prehensive" name="idno_prehensive" type="hidden">
                                                <div class="sixteen wide column">
                                                    <div class="ui grid">
                                                        <table class="table;border border-white">
                                                            <thead>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 300px;" colspan="2">
                                                                        <i><center>Check which patient able to achieve (please tick at the appropriate box)</center></i>
                                                                    </td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="margin: 0px; padding: 3px 14px 14px 200px;"></th>
                                                                    <th style="margin: 0px; padding: 3px 14px 14px 80px;">Rt Hand</th>
                                                                    <th style="margin: 0px; padding: 3px 14px 14px 80px;">Lt Hand</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 200px;">Hook Grasp</td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="prehensive_hook_rt" name="prehensive_hook_rt" value="1"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="prehensive_hook_lt" name="prehensive_hook_lt" value="1"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 200px;">Lateral Pinch</td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="prehensive_lateral_rt" name="prehensive_lateral_rt" value="1"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="prehensive_lateral_lt" name="prehensive_lateral_lt" value="1"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 200px;">Tip Pinch</td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="prehensive_tip_rt" name="prehensive_tip_rt" value="1"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="prehensive_tip_lt" name="prehensive_tip_lt" value="1"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 200px;">Cylindrical Grasp</td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="prehensive_cylindrical_rt" name="prehensive_cylindrical_rt" value="1"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="prehensive_cylindrical_lt" name="prehensive_cylindrical_lt" value="1"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 200px;">Pad Pinch</td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="prehensive_pad_rt" name="prehensive_pad_rt" value="1"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="prehensive_pad_lt" name="prehensive_pad_lt" value="1"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 200px;">3-Jaw Chuck</td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="prehensive_jaw_rt" name="prehensive_jaw_rt" value="1"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="prehensive_jaw_lt" name="prehensive_jaw_lt" value="1"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 200px;">Spherical Grasp</td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="prehensive_spherical_rt" name="prehensive_spherical_rt" value="1"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="prehensive_spherical_lt" name="prehensive_spherical_lt" value="1"></td>
                                                                </tr>
                                                            </tbody>
                                                            
                                                        </table>

                                                        <table class="ui celled table">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="2">IMPRESSIONS</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="sixteen wide">
                                                                        <div class="ui form">
                                                                            <textarea id="impressions" name="impressions" rows="6"></textarea>
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
                            </div>

                            <div class="ui bottom attached tab raised segment" data-tab="skin">
                                <div class="ui segments" style="position: relative;">
                                    <div class="ui secondary segment" style="height: 50px;">
                                        <div class="ui small green icon buttons" id="btn_grp_edit_skin" style="position: absolute;
                                            padding: 0 0 0 0;
                                            right: 40px;
                                            top: 9px;
                                            z-index: 2;">
                                            <button class="ui button" id="new_skin"><span class="fa fa-plus-square-o"></span>New</button>
                                            <button class="ui button" id="edit_skin"><span class="fa fa-edit fa-lg"></span>Edit</button>
                                            <button class="ui button" id="save_skin"><span class="fa fa-save fa-lg"></span>Save</button>
                                            <button class="ui button" id="cancel_skin"><span class="fa fa-ban fa-lg"></span>Cancel</button>
                                            <!-- <button class="ui button" id="skin_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
                                        </div>
                                    </div>
                                    <div class="ui segment">
                                        <div class="ui grid">
                                            <form id="formSkin" class="floated ui form sixteen wide column">
                                                <input id="idno_skin" name="idno_skin" type="hidden">
                                                <div class="sixteen wide column">
                                                    <div class="ui form">
                                                        <div class="field" style="margin:0px; padding: 3px 3px 3px 3px;">
                                                            <label>Skin Condition/Scarring</label>
                                                            <textarea id="skinCondition" name="skinCondition" rows="6" cols="50"></textarea>
                                                        </div>
                                                    </div>

                                                    <table class="ui celled table">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="2">IMPRESSIONS</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="sixteen wide">
                                                                    <div class="ui form">
                                                                        <textarea id="impressions" name="impressions" rows="6"></textarea>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="ui bottom attached tab raised segment" data-tab="edema">
                                <div class="ui segments" style="position: relative;">
                                    <div class="ui secondary segment" style="height: 50px;">
                                        <div class="ui small green icon buttons" id="btn_grp_edit_edema" style="position: absolute;
                                            padding: 0 0 0 0;
                                            right: 40px;
                                            top: 9px;
                                            z-index: 2;">
                                            <button class="ui button" id="new_edema"><span class="fa fa-plus-square-o"></span>New</button>
                                            <button class="ui button" id="edit_edema"><span class="fa fa-edit fa-lg"></span>Edit</button>
                                            <button class="ui button" id="save_edema"><span class="fa fa-save fa-lg"></span>Save</button>
                                            <button class="ui button" id="cancel_edema"><span class="fa fa-ban fa-lg"></span>Cancel</button>
                                            <!-- <button class="ui button" id="edema_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
                                        </div>
                                    </div>
                                    <div class="ui segment">
                                        <div class="ui grid">
                                            <form id="formEdema" class="floated ui form sixteen wide column">
                                                <input id="idno_edema" name="idno_edema" type="hidden">
                                                <div class="sixteen wide column">
                                                    <div class="ui grid">
                                                        <table class="table;border border-white">
                                                            <thead>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 300px;" colspan="2">
                                                                        <i><center>Check which patient able to achieve (please tick at the appropriate box)</center></i>
                                                                    </td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="margin: 0px; padding: 3px 14px 14px 200px;"></th>
                                                                    <th style="margin: 0px; padding: 3px 14px 14px 80px;">Rt Hand</th>
                                                                    <th style="margin: 0px; padding: 3px 14px 14px 80px;">Lt Hand</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 200px;">Noted</td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="edema_noted_rt" name="edema_noted_rt" value="1"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="edema_noted_lt" name="edema_noted_lt" value="1"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 200px;"><input type="text" id="edema_new1" name="edema_new1"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="edema_new1_rt" name="edema_new1_rt" value="1"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="edema_new1_lt" name="edema_new1_lt" value="1"></td>
                                                                </tr>
                                                                <!-- <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 200px;"><input type="text" id="edema_new2" name="edema_new2"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="edema_new2_rt" name="edema_new2_rt" value="1"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="edema_new2_lt" name="edema_new2_lt" value="1"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 200px;"><input type="text" id="edema_new3" name="edema_new3"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="edema_new3_rt" name="edema_new3_rt" value="1"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="edema_new3_lt" name="edema_new3_lt" value="1"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 200px;"><input type="text" id="edema_new4" name="edema_new4"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="edema_new4_rt" name="edema_new4_rt" value="1"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="edema_new4_lt" name="edema_new4_lt" value="1"></td>
                                                                </tr> -->
                                                                
                                                            </tbody>
                                                            
                                                        </table>

                                                        <table class="ui celled table">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="2">IMPRESSIONS</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="sixteen wide">
                                                                        <div class="ui form">
                                                                            <textarea id="impressions" name="impressions" rows="6"></textarea>
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
                            </div>

                            <div class="ui bottom attached tab raised segment" data-tab="functional">
                                <div class="ui segments" style="position: relative;">
                                    <div class="ui secondary segment" style="height: 50px;">
                                        <div class="ui small green icon buttons" id="btn_grp_edit_functional" style="position: absolute;
                                            padding: 0 0 0 0;
                                            right: 40px;
                                            top: 9px;
                                            z-index: 2;">
                                            <button class="ui button" id="new_functional"><span class="fa fa-plus-square-o"></span>New</button>
                                            <button class="ui button" id="edit_functional"><span class="fa fa-edit fa-lg"></span>Edit</button>
                                            <button class="ui button" id="save_functional"><span class="fa fa-save fa-lg"></span>Save</button>
                                            <button class="ui button" id="cancel_functional"><span class="fa fa-ban fa-lg"></span>Cancel</button>
                                            <!-- <button class="ui button" id="functional_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
                                        </div>
                                    </div>
                                    <div class="ui segment">
                                        <div class="ui grid">
                                            <form id="formFunctional" class="floated ui form sixteen wide column">
                                                <input id="idno_func" name="idno_func" type="hidden">
                                                <div class="sixteen wide column">
                                                    <div class="ui grid">
                                                        <table class="table;border border-white">
                                                            <thead>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 300px;" colspan="2">
                                                                        <i><center>Check which patient able to achieve (please tick at the appropriate box)</center></i>
                                                                    </td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="margin: 0px; padding: 3px 14px 14px 200px;"></th>
                                                                    <th style="margin: 0px; padding: 3px 14px 14px 80px;">Rt Hand</th>
                                                                    <th style="margin: 0px; padding: 3px 14px 14px 80px;">Lt Hand</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 200px;">Writing</td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="func_writing_rt" name="func_writing_rt" value="1"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="func_writing_lt" name="func_writing_lt" value="1"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 200px;">Pick Up Coins</td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="func_pickCoins_rt" name="func_pickCoins_rt" value="1"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="func_pickCoins_lt" name="func_pickCoins_lt" value="1"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 200px;">Pick Up Pins</td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="func_pickPins_rt" name="func_pickPins_rt" value="1"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="func_pickPins_lt" name="func_pickPins_lt" value="1"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 200px;">Buttoning</td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="func_button_rt" name="func_button_rt" value="1"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="func_button_lt" name="func_button_lt" value="1"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 200px;">Feeding-spoon</td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="func_feedSpoon_rt" name="func_feedSpoon_rt" value="1"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="func_feedSpoon_lt" name="func_feedSpoon_lt" value="1"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 200px;">Feeding-hand</td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="func_feedHand_rt" name="func_feedHand_rt" value="1"></td>
                                                                    <td style="margin: 0px; padding: 3px 14px 14px 80px;"><input type="checkbox" id="func_feedHand_lt" name="func_feedHand_lt" value="1"></td>
                                                                </tr>
                                                            </tbody>
                                                            
                                                        </table>

                                                        <table class="ui celled table">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="2">IMPRESSIONS</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="sixteen wide">
                                                                        <div class="ui form">
                                                                            <textarea id="impressions" name="impressions" rows="6"></textarea>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        MOVEMENT SCORING SHEET
        <div class="ui small blue icon buttons" id="btn_grp_edit_motorScale" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_motorScale"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_motorScale"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_motorScale"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_motorScale"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <button class="ui button" id="motorScale_chart"><span class="fa fa-print fa-lg"></span>Print</button>
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formMotorScale" class="floated ui form sixteen wide column">
                <input id="idno_motorScale" name="idno_motorScale" type="hidden">
                <div class="ui grid">
                    <div class='four wide column'>
                        <div class="ui segments">
                            <div class="ui segment">
                                <div class="ui grid">
                                    <table id="tbl_motorScale_date" class="ui celled table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="scope">idno</th>
                                                <th class="scope">mrn</th>
                                                <th class="scope">episno</th>
                                                <th class="scope">Date</th>
                                                <th class="scope">dt</th>
                                                <th class="scope">Entered By</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class='twelve wide column'>
                        <div class="sixteen wide column" style="padding: 0px 14px 14px 30px;">
                            <div class="inline fields">
                                <label>Date</label>
                                <div class="field">
                                    <input id="motorScale_entereddate" name="entereddate" type="date" data-validation="required" data-validation-error-msg-required="Please enter information.">
                                </div>
                            </div>
                            
                            <table class="ui striped table">
                                <thead>
                                    <tr>
                                        <th width="50%"></th>
                                        <th>0</th>
                                        <th>1</th>
                                        <th>2</th>
                                        <th>3</th>
                                        <th>4</th>
                                        <th>5</th>
                                        <th>6</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1. Supine to side lying</td>
                                        <td>
                                            <input type="radio" name="sideLie" value="0" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="sideLie" value="1" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="sideLie" value="2" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="sideLie" value="3" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="sideLie" value="4" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="sideLie" value="5" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="sideLie" value="6" class="calc_movementScore">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2. Supine to sitting over side of bed</td>
                                        <td>
                                            <input type="radio" name="sitOverBed" value="0" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="sitOverBed" value="1" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="sitOverBed" value="2" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="sitOverBed" value="3" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="sitOverBed" value="4" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="sitOverBed" value="5" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="sitOverBed" value="6" class="calc_movementScore">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3. Balanced sitting</td>
                                        <td>
                                            <input type="radio" name="balancedSit" value="0" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="balancedSit" value="1" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="balancedSit" value="2" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="balancedSit" value="3" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="balancedSit" value="4" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="balancedSit" value="5" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="balancedSit" value="6" class="calc_movementScore">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4. Sitting to standing</td>
                                        <td>
                                            <input type="radio" name="sitToStand" value="0" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="sitToStand" value="1" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="sitToStand" value="2" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="sitToStand" value="3" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="sitToStand" value="4" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="sitToStand" value="5" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="sitToStand" value="6" class="calc_movementScore">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>5. Walking</td>
                                        <td>
                                            <input type="radio" name="walking" value="0" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="walking" value="1" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="walking" value="2" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="walking" value="3" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="walking" value="4" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="walking" value="5" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="walking" value="6" class="calc_movementScore">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>6. Upper-arm function</td>
                                        <td>
                                            <input type="radio" name="upperArmFunc" value="0" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="upperArmFunc" value="1" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="upperArmFunc" value="2" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="upperArmFunc" value="3" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="upperArmFunc" value="4" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="upperArmFunc" value="5" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="upperArmFunc" value="6" class="calc_movementScore">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>7. Advanced hand activities</td>
                                        <td>
                                            <input type="radio" name="advHandActvt" value="0" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="advHandActvt" value="1" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="advHandActvt" value="2" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="advHandActvt" value="3" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="advHandActvt" value="4" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="advHandActvt" value="5" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="advHandActvt" value="6" class="calc_movementScore">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>8. General tonus</td>
                                        <td>
                                            <input type="radio" name="generalTonus" value="0" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="generalTonus" value="1" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="generalTonus" value="2" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="generalTonus" value="3" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="generalTonus" value="4" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="generalTonus" value="5" class="calc_movementScore">
                                        </td>
                                        <td>
                                            <input type="radio" name="generalTonus" value="6" class="calc_movementScore">
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>Total</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <input type="text" class="form-control" id="movementScore" name="movementScore" style="width: 100px;" rdonly>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            
                            <div class="inline fields">
                                <label>Comments (if applicable)</label>
                                <div class="field eleven wide column">
                                    <textarea id="motorScale_comments" name="comments" type="text" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        <div class="ui small blue icon buttons" id="btn_grp_edit_spinalCord" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_spinalCord"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_spinalCord"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_spinalCord"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_spinalCord"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <!-- <button class="ui button" id="spinalCord_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formSpinalCord" class="floated ui form sixteen wide column">
                <input id="idno_spinalCord" name="idno_spinalCord" type="hidden">
                <div class="ui grid">
                    <div class='four wide column'>
                        <div class="ui segments">
                            <div class="ui segment">
                                <div class="ui grid">
                                    <table id="tbl_spinalCord_date" class="ui celled table" style="width: 100%;">
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
                        <div class="sixteen wide column">
                            <div class="inline fields">
                                <label>Date</label>
                                <div class="field">
                                    <input id="spinalCord_entereddate" name="entereddate" type="date">
                                </div>
                            </div>
                            
                            <div class="ui grid">
                                <div class='eleven wide column' style="padding: 14px 0px;">
                                    <div id="spinalCordTabs" class="ui segment">
                                        <div class="ui top attached tabular menu">
                                            <a class="item active" data-tab="spinalCordRight" id="navtab_spinalCordRight">RIGHT</a>
                                            <a class="item" data-tab="spinalCordLeft" id="navtab_spinalCordLeft">LEFT</a>
                                        </div>
                                        
                                        <div class="ui bottom attached tab raised segment active" data-tab="spinalCordRight">
                                            <table class="ui striped table">
                                                <thead>
                                                    <tr>
                                                        <th width="30%"></th>
                                                        <th colspan="2" style="text-align: center;">MOTOR<br>KEY MUSCLES</th>
                                                        <!-- <th></th> -->
                                                        <th colspan="2" style="text-align: center;">SENSORY<br>KEY SENSORY POINTS</th>
                                                        <!-- <th></th> -->
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th width="20%"></th>
                                                        <th width="20%">Light Touch (LTR)</th>
                                                        <th width="20%">Pin Prick (PPR)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td style="text-align: right;">C2</td>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrC2" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprC2" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrC2" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprC2" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td style="text-align: right;">C3</td>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrC3" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprC3" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrC3" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprC3" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td style="text-align: right;">C4</td>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrC4" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprC4" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrC4" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprC4" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <td rowspan="5" style="vertical-align: middle; text-align: center;">UER<br>(Upper Extremity Right)</td>
                                                        <td><i>Elbow flexors</i></td>
                                                        <!-- <td>
                                                            <div class="inline fields"> C5
                                                                <div class="field" style="padding-left: 5px; padding-right: 0px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorR uer" name="motorRC5" style="width: 100px;">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrC5" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprC5" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: right; vertical-align: middle;"> C5 &nbsp; &nbsp; <input type="checkbox" name="motorRC5" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrC5" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprC5" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td></td> -->
                                                        <td><i>Wrist extensors</i></td>
                                                        <!-- <td>
                                                            <div class="inline fields"> C6
                                                                <div class="field" style="padding-left: 5px; padding-right: 0px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorR uer" name="motorRC6" style="width: 100px;">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrC6" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprC6" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: right; vertical-align: middle;"> C6 &nbsp; &nbsp; <input type="checkbox" name="motorRC6" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrC6" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprC6" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td></td> -->
                                                        <td><i>Elbow extensors</i></td>
                                                        <!-- <td>
                                                            <div class="inline fields"> C7
                                                                <div class="field" style="padding-left: 5px; padding-right: 0px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorR uer" name="motorRC7" style="width: 100px;">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrC7" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprC7" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: right; vertical-align: middle;"> C7 &nbsp; &nbsp; <input type="checkbox" name="motorRC7" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrC7" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprC7" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td></td> -->
                                                        <td><i>Finger flexors</i></td>
                                                        <!-- <td>
                                                            <div class="inline fields"> C8
                                                                <div class="field" style="padding-left: 5px; padding-right: 0px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorR uer" name="motorRC8" style="width: 100px;">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrC8" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprC8" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: right; vertical-align: middle;"> C8 &nbsp; &nbsp; <input type="checkbox" name="motorRC8" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrC8" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprC8" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td></td> -->
                                                        <td><i>Finger abductors (little finger)</i></td>
                                                        <!-- <td>
                                                            <div class="inline fields"> T1
                                                                <div class="field" style="padding-left: 5px; padding-right: 0px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorR uer" name="motorRT1" style="width: 100px;">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrT1" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprT1" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: right; vertical-align: middle;"> T1 &nbsp; &nbsp; <input type="checkbox" name="motorRT1" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrT1" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprT1" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" rowspan="12">
                                                            <i><b>Comments</b> (Non-key Muscle? Reason for NT? Pain?):</i>
                                                            <textarea rows="30" cols="50" id="spinalCord_comments" name="comments"></textarea>
                                                        </td>
                                                        <!-- <td></td> -->
                                                        <td style="text-align: right;">T2</td>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrT2" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprT2" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrT2" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprT2" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                        <td style="text-align: right;">T3</td>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrT3" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprT3" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrT3" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprT3" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                        <td style="text-align: right;">T4</td>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrT4" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprT4" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrT4" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprT4" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                        <td style="text-align: right;">T5</td>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrT5" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprT5" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrT5" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprT5" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                        <td style="text-align: right;">T6</td>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrT6" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprT6" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrT6" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprT6" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                        <td style="text-align: right;">T7</td>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrT7" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprT7" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrT7" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprT7" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                        <td style="text-align: right;">T8</td>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrT8" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprT8" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrT8" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprT8" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                        <td style="text-align: right;">T9</td>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrT9" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprT9" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrT9" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprT9" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                        <td style="text-align: right;">T10</td>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrT10" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprT10" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrT10" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprT10" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                        <td style="text-align: right;">T11</td>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrT11" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprT11" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrT11" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprT11" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                        <td style="text-align: right;">T12</td>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrT12" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprT12" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrT12" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprT12" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                        <td style="text-align: right;">L1</td>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrL1" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprL1" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrL1" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprL1" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <td rowspan="5" style="vertical-align: middle; text-align: center;">LER<br>(Lower Extremity Right)</td>
                                                        <td><i>Hip flexors</i></td>
                                                        <!-- <td>
                                                            <div class="inline fields"> L2
                                                                <div class="field" style="padding-left: 5px; padding-right: 0px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorR ler" name="motorRL2" style="width: 100px;">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrL2" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprL2" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: right; vertical-align: middle;"> L2 &nbsp; &nbsp; <input type="checkbox" name="motorRL2" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrL2" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprL2" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td></td> -->
                                                        <td><i>Knee extensors</i></td>
                                                        <!-- <td>
                                                            <div class="inline fields"> L3
                                                                <div class="field" style="padding-left: 5px; padding-right: 0px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorR ler" name="motorRL3" style="width: 100px;">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrL3" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprL3" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: right; vertical-align: middle;"> L3 &nbsp; &nbsp; <input type="checkbox" name="motorRL3" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrL3" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprL3" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td></td> -->
                                                        <td><i>Ankle dorsiflexors</i></td>
                                                        <!-- <td>
                                                            <div class="inline fields"> L4
                                                                <div class="field" style="padding-left: 5px; padding-right: 0px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorR ler" name="motorRL4" style="width: 100px;">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrL4" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprL4" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: right; vertical-align: middle;"> L4 &nbsp; &nbsp; <input type="checkbox" name="motorRL4" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrL4" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprL4" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td></td> -->
                                                        <td><i>Long toe extensors</i></td>
                                                        <!-- <td>
                                                            <div class="inline fields"> L5
                                                                <div class="field" style="padding-left: 5px; padding-right: 0px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorR ler" name="motorRL5" style="width: 100px;">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrL5" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprL5" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: right; vertical-align: middle;"> L5 &nbsp; &nbsp; <input type="checkbox" name="motorRL5" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrL5" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprL5" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td></td> -->
                                                        <td><i>Ankle plantar flexors</i></td>
                                                        <!-- <td>
                                                            <div class="inline fields"> S1
                                                                <div class="field" style="padding-left: 5px; padding-right: 0px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorR ler" name="motorRS1" style="width: 100px;">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrS1" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprS1" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: right; vertical-align: middle;"> S1 &nbsp; &nbsp; <input type="checkbox" name="motorRS1" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrS1" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprS1" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td style="text-align: right;">S2</td>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrS2" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprS2" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrS2" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprS2" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td style="text-align: right;">S3</td>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrS3" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprS3" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrS3" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprS3" value="1"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="padding: 7px 0 7px 5px;">
                                                            <div class="inline fields">
                                                                <label style="margin-right: 5px;">(VAC) Voluntary Anal Contraction (Yes/No)</label>
                                                                <div class="field" style="padding-right: 0px;">
                                                                    <!-- <input type="text" class="form-control" name="vac" size="8" style="border: 2px solid black;"> -->
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="vac" value="1" id="vac_yes">
                                                                        <label for="vac_yes">Yes</label>
                                                                    </div>
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="vac" value="0" id="vac_no">
                                                                        <label for="vac_no">No</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <!-- <td></td> -->
                                                        <td style="text-align: right;">S4-5</td>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltr" name="ltrS4" style="width: 100px; border: 2px solid black;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppr" name="pprS4" style="width: 100px; border: 2px solid black;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltrS4" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pprS4" value="1"></td>
                                                    </tr>
                                                    <tr style="display: none;">
                                                        <td colspan="2" style="text-align: right;">
                                                            <i><b>RIGHT TOTALS</b></i><br><b>(MAXIMUM)</b>
                                                        </td>
                                                        <!-- <td></td> -->
                                                        <td>
                                                            <input type="number" class="form-control" name="motorRTotal" style="width: 100px;" rdonly>
                                                            <div style="text-align: center;"><i>(50)</i></div>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" name="ltrTotal" style="width: 100px;" rdonly>
                                                            <div style="text-align: center;"><i>(56)</i></div>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" name="pprTotal" style="width: 100px;" rdonly>
                                                            <div style="text-align: center;"><i>(56)</i></div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <div class="ui bottom attached tab raised segment" data-tab="spinalCordLeft">
                                            <table class="ui striped table">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2" style="text-align: center;">SENSORY<br>KEY SENSORY POINTS</th>
                                                        <!-- <th></th> -->
                                                        <th colspan="2" style="text-align: center;">MOTOR<br>KEY MUSCLES</th>
                                                        <!-- <th></th> -->
                                                        <th width="30%"></th>
                                                    </tr>
                                                    <tr>
                                                        <th width="20%">Light Touch (LTL)</th>
                                                        <th width="20%">Pin Prick (PPL)</th>
                                                        <th width="20%"></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlC2" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplC2" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlC2" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplC2" value="1"></td>
                                                        <td style="text-align: left;">C2</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlC3" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplC3" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlC3" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplC3" value="1"></td>
                                                        <td style="text-align: left;">C3</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlC4" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplC4" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlC4" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplC4" value="1"></td>
                                                        <td style="text-align: left;">C4</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlC5" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplC5" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <div class="inline fields">
                                                                <div class="field" style="padding-left: 0px; padding-right: 5px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorL uel" name="motorLC5" style="width: 100px;">
                                                                </div> C5
                                                            </div>
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlC5" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplC5" value="1"></td>
                                                        <td style="text-align: right; vertical-align: middle;"><input type="checkbox" name="motorLC5" value="1"> &nbsp; &nbsp; C5 </td>
                                                        <td><i>Elbow flexors</i></td>
                                                        <td rowspan="5" style="vertical-align: middle; text-align: center;">UEL<br>(Upper Extremity Left)</td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlC6" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplC6" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <div class="inline fields">
                                                                <div class="field" style="padding-left: 0px; padding-right: 5px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorL uel" name="motorLC6" style="width: 100px;">
                                                                </div> C6
                                                            </div>
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlC6" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplC6" value="1"></td>
                                                        <td style="text-align: right; vertical-align: middle;"><input type="checkbox" name="motorLC6" value="1"> &nbsp; &nbsp; C6 </td>
                                                        <td><i>Wrist extensors</i></td>
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlC7" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplC7" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <div class="inline fields">
                                                                <div class="field" style="padding-left: 0px; padding-right: 5px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorL uel" name="motorLC7" style="width: 100px;">
                                                                </div> C7
                                                            </div>
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlC7" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplC7" value="1"></td>
                                                        <td style="text-align: right; vertical-align: middle;"><input type="checkbox" name="motorLC7" value="1"> &nbsp; &nbsp; C7 </td>
                                                        <td><i>Elbow extensors</i></td>
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlC8" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplC8" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <div class="inline fields">
                                                                <div class="field" style="padding-left: 0px; padding-right: 5px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorL uel" name="motorLC8" style="width: 100px;">
                                                                </div> C8
                                                            </div>
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlC8" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplC8" value="1"></td>
                                                        <td style="text-align: right; vertical-align: middle;"><input type="checkbox" name="motorLC8" value="1"> &nbsp; &nbsp; C8 </td>
                                                        <td><i>Finger flexors</i></td>
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlT1" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplT1" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <div class="inline fields">
                                                                <div class="field" style="padding-left: 0px; padding-right: 5px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorL uel" name="motorLT1" style="width: 100px;">
                                                                </div> T1
                                                            </div>
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlT1" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplT1" value="1"></td>
                                                        <td style="text-align: right; vertical-align: middle;"><input type="checkbox" name="motorLT1" value="1"> &nbsp; &nbsp; T1 </td>
                                                        <td><i>Finger abductors (little finger)</i></td>
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlT2" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplT2" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlT2" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplT2" value="1"></td>
                                                        <td style="text-align: left;">T2</td>
                                                        <td colspan="2" rowspan="12">
                                                            <div class="ui segments">
                                                                <div class="ui secondary segment" style="padding: 5px 14px; text-align: center;">
                                                                    MOTOR <br><i>(SCORING ON REVERSE SIDE)</i>
                                                                </div>
                                                                <div class="ui segment">
                                                                    <div class="ui grid">
                                                                        <div class="sixteen wide column">
                                                                            <p><i>
                                                                                0 = total paralysis <br>
                                                                                1 = palpable or visible contraction <br>
                                                                                2 = active movement, gravity eliminated <br>
                                                                                3 = active movement, against gravity <br>
                                                                                4 = active movement, against some resistance <br>
                                                                                5 = active movement, against full resistance <br>
                                                                                5* = normal corrected for pain/disuse <br>
                                                                                NT = not testable
                                                                            </i></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="ui segments">
                                                                <div class="ui secondary segment" style="padding: 5px 14px; text-align: center;">
                                                                    SENSORY <br><i>(SCORING ON REVERSE SIDE)</i>
                                                                </div>
                                                                <div class="ui segment">
                                                                    <div class="ui grid">
                                                                        <div class="eight wide column">
                                                                            <p><i>
                                                                                0 = absent <br>
                                                                                1 = altered
                                                                            </i></p>
                                                                        </div>
                                                                        
                                                                        <div class="eight wide column">
                                                                            <p><i>
                                                                                2 = normal <br>
                                                                                NT = not testable
                                                                            </i></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlT3" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplT3" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlT3" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplT3" value="1"></td>
                                                        <td style="text-align: left;">T3</td>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlT4" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplT4" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlT4" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplT4" value="1"></td>
                                                        <td style="text-align: left;">T4</td>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlT5" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplT5" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlT5" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplT5" value="1"></td>
                                                        <td style="text-align: left;">T5</td>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlT6" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplT6" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlT6" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplT6" value="1"></td>
                                                        <td style="text-align: left;">T6</td>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlT7" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplT7" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlT7" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplT7" value="1"></td>
                                                        <td style="text-align: left;">T7</td>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlT8" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplT8" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlT8" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplT8" value="1"></td>
                                                        <td style="text-align: left;">T8</td>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlT9" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplT9" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlT9" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplT9" value="1"></td>
                                                        <td style="text-align: left;">T9</td>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlT10" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplT10" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlT10" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplT10" value="1"></td>
                                                        <td style="text-align: left;">T10</td>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlT11" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplT11" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlT11" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplT11" value="1"></td>
                                                        <td style="text-align: left;">T11</td>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlT12" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplT12" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlT12" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplT12" value="1"></td>
                                                        <td style="text-align: left;">T12</td>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlL1" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplL1" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlL1" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplL1" value="1"></td>
                                                        <td style="text-align: left;">L1</td>
                                                        <!-- <td></td> -->
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlL2" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplL2" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <div class="inline fields">
                                                                <div class="field" style="padding-left: 0px; padding-right: 5px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorL lel" name="motorLL2" style="width: 100px;">
                                                                </div> L2
                                                            </div>
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlL2" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplL2" value="1"></td>
                                                        <td style="text-align: right; vertical-align: middle;"><input type="checkbox" name="motorLL2" value="1"> &nbsp; &nbsp; L2 </td>
                                                        <td><i>Hip flexors</i></td>
                                                        <td rowspan="5" style="vertical-align: middle; text-align: center;">LEL<br>(Lower Extremity Left)</td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlL3" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplL3" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <div class="inline fields">
                                                                <div class="field" style="padding-left: 0px; padding-right: 5px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorL lel" name="motorLL3" style="width: 100px;">
                                                                </div> L3
                                                            </div>
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlL3" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplL3" value="1"></td>
                                                        <td style="text-align: right; vertical-align: middle;"><input type="checkbox" name="motorLL3" value="1"> &nbsp; &nbsp; L3 </td>
                                                        <td><i>Knee extensors</i></td>
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlL4" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplL4" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <div class="inline fields">
                                                                <div class="field" style="padding-left: 0px; padding-right: 5px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorL lel" name="motorLL4" style="width: 100px;">
                                                                </div> L4
                                                            </div>
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlL4" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplL4" value="1"></td>
                                                        <td style="text-align: right; vertical-align: middle;"><input type="checkbox" name="motorLL4" value="1"> &nbsp; &nbsp; L4 </td>
                                                        <td><i>Ankle dorsiflexors</i></td>
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlL5" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplL5" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <div class="inline fields">
                                                                <div class="field" style="padding-left: 0px; padding-right: 5px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorL lel" name="motorLL5" style="width: 100px;">
                                                                </div> L5
                                                            </div>
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlL5" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplL5" value="1"></td>
                                                        <td style="text-align: right; vertical-align: middle;"><input type="checkbox" name="motorLL5" value="1"> &nbsp; &nbsp; L5 </td>
                                                        <td><i>Long toe extensors</i></td>
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlS1" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplS1" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <div class="inline fields">
                                                                <div class="field" style="padding-left: 0px; padding-right: 5px;">
                                                                    <input type="text" class="form-control calc_spinalCord motorL lel" name="motorLS1" style="width: 100px;">
                                                                </div> S1
                                                            </div>
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlS1" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplS1" value="1"></td>
                                                        <td style="text-align: right; vertical-align: middle;"><input type="checkbox" name="motorLS1" value="1"> &nbsp; &nbsp; S1 </td>
                                                        <td><i>Ankle plantar flexors</i></td>
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlS2" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplS2" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlS2" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplS2" value="1"></td>
                                                        <td style="text-align: left;">S2</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlS3" style="width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplS3" style="width: 100px;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlS3" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplS3" value="1"></td>
                                                        <td style="text-align: left;">S3</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <!-- <td>
                                                            <input type="text" class="form-control calc_spinalCord ltl" name="ltlS4" style="width: 100px; border: 2px solid black;">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control calc_spinalCord ppl" name="pplS4" style="width: 100px; border: 2px solid black;">
                                                        </td> -->
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="ltlS4" value="1"></td>
                                                        <td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="pplS4" value="1"></td>
                                                        <td style="text-align: left;">S4-5</td>
                                                        <td colspan="2" style="padding: 7px 0;">
                                                            <div class="inline fields">
                                                                <div class="field" style="padding-left: 0px;">
                                                                    <!-- <input type="text" class="form-control" name="dap" size="8" style="border: 2px solid black;"> -->
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="dap" value="1" id="dap_yes">
                                                                        <label for="dap_yes">Yes</label>
                                                                    </div>
                                                                    <div class="ui radio checkbox">
                                                                        <input type="radio" name="dap" value="0" id="dap_no">
                                                                        <label for="dap_no">No</label>
                                                                    </div>
                                                                </div>
                                                                <label style="margin-right: 5px;">(DAP) Deep Anal Pressure (Yes/No)</label>
                                                            </div>
                                                        </td>
                                                        <!-- <td></td> -->
                                                    </tr>
                                                    <tr style="display: none;">
                                                        <td>
                                                            <input type="number" class="form-control" name="ltlTotal" style="width: 100px;" rdonly>
                                                            <div style="text-align: center;"><i>(56)</i></div>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" name="pplTotal" style="width: 100px;" rdonly>
                                                            <div style="text-align: center;"><i>(56)</i></div>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" name="motorLTotal" style="width: 100px;" rdonly>
                                                            <div style="text-align: center;"><i>(50)</i></div>
                                                        </td>
                                                        <td colspan="2" style="text-align: left;">
                                                            <i><b>LEFT TOTALS</b></i><br><b>(MAXIMUM)</b>
                                                        </td>
                                                        <!-- <td></td> -->
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class='five wide column'>
                                    <div class="ui card">
                                        <div class="image">
                                            <img src="{{ asset('patientcare/img/spinalCordDiag.jpg') }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class='sixteen wide column' style="display: none;">
                                    <div class="ui segments">
                                        <div class="ui secondary segment">MOTOR SUBSCORES</div>
                                        <div class="ui segment">
                                            <div class="ui grid">
                                                <div class="inline fields" style="padding-top: 10px;">
                                                    <label style="margin-bottom: 15px;">UER</label>
                                                    <div class="field">
                                                        <input type="number" class="form-control" name="uer">
                                                        <br><i>MAX (25)</i>
                                                    </div>
                                                    
                                                    <label style="margin-bottom: 15px;">+ UEL</label>
                                                    <div class="field">
                                                        <input type="number" class="form-control" name="uel">
                                                        <br><i>(25)</i>
                                                    </div>
                                                    
                                                    <label style="margin-bottom: 15px;">= UEMS TOTAL</label>
                                                    <div class="field">
                                                        <input type="number" class="form-control" name="uemsTotal">
                                                        <br><i>(50)</i>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields" style="padding-top: 10px;">
                                                    <label style="margin-bottom: 15px;">LER</label>
                                                    <div class="field">
                                                        <input type="number" class="form-control" name="ler">
                                                        <br><i>MAX (25)</i>
                                                    </div>
                                                    
                                                    <label style="margin-bottom: 15px;">+ LEL</label>
                                                    <div class="field">
                                                        <input type="number" class="form-control" name="lel">
                                                        <br><i>(25)</i>
                                                    </div>
                                                    
                                                    <label style="margin-bottom: 15px;">= LEMS TOTAL</label>
                                                    <div class="field">
                                                        <input type="number" class="form-control" name="lemsTotal">
                                                        <br><i>(50)</i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="ui segments">
                                        <div class="ui secondary segment">SENSORY SUBSCORES</div>
                                        <div class="ui segment">
                                            <div class="ui grid">
                                                <div class="inline fields" style="padding-top: 10px;">
                                                    <label style="margin-bottom: 15px;">LTR</label>
                                                    <div class="field">
                                                        <input type="number" class="form-control" name="ltr" rdonly>
                                                        <br><i>MAX (56)</i>
                                                    </div>
                                                    
                                                    <label style="margin-bottom: 15px;">+ LTL</label>
                                                    <div class="field">
                                                        <input type="number" class="form-control" name="ltl" rdonly>
                                                        <br><i>(56)</i>
                                                    </div>
                                                    
                                                    <label style="margin-bottom: 15px;">= LT TOTAL</label>
                                                    <div class="field">
                                                        <input type="number" class="form-control" name="ltTotal" rdonly>
                                                        <br><i>(112)</i>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields" style="padding-top: 10px;">
                                                    <label style="margin-bottom: 15px;">PPR</label>
                                                    <div class="field">
                                                        <input type="number" class="form-control" name="ppr" rdonly>
                                                        <br><i>MAX (56)</i>
                                                    </div>
                                                    
                                                    <label style="margin-bottom: 15px;">+ PPL</label>
                                                    <div class="field">
                                                        <input type="number" class="form-control" name="ppl" rdonly>
                                                        <br><i>(56)</i>
                                                    </div>
                                                    
                                                    <label style="margin-bottom: 15px;">= PP TOTAL</label>
                                                    <div class="field">
                                                        <input type="number" class="form-control" name="ppTotal" rdonly>
                                                        <br><i>(112)</i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="ui segments">
                                        <!-- <div class="ui secondary segment">NEUROLOGICAL LEVELS</div> -->
                                        <div class="ui segment">
                                            <div class="ui grid">
                                                <div class='sixteen wide column'>
                                                    <h4 style="margin-bottom: 0px;">NEUROLOGICAL LEVELS</h4>
                                                    <p><i>Steps 1-5 for classification as on reverse</i></p>
                                                    
                                                    <div class="inline fields" style="padding-top: 10px;">
                                                        <label style="margin-top: 15px;">1. SENSORY</label>
                                                        <div class="field">
                                                            <i style="margin-left: 50px;"><b>R</b></i><br>
                                                            <input type="text" class="form-control" name="sensoryRNL">
                                                        </div>
                                                        <div class="field">
                                                            <i style="margin-left: 50px;"><b>L</b></i><br>
                                                            <input type="text" class="form-control" name="sensoryLNL">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="inline fields">
                                                        <label style="margin-top: 15px; margin-right: 23px;">2. MOTOR</label>
                                                        <div class="field">
                                                            <input type="text" class="form-control" name="motorRNL">
                                                        </div>
                                                        <div class="field">
                                                            <input type="text" class="form-control" name="motorLNL">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="inline fields">
                                                        <label>3. NEUROLOGICAL LEVEL OF INJURY (NLI)</label>
                                                        <div class="field">
                                                            <input type="text" class="form-control" name="nli">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="inline fields">
                                                        <label>4. COMPLETE OR INCOMPLETE<br><i>Incomplete = Any sensory or motor function in S4-5</i></label>
                                                        <div class="field">
                                                            <input type="text" class="form-control" name="completion">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="inline fields">
                                                        <label>5. ASIA IMPAIRMENT SCALE (AIS)</label>
                                                        <div class="field">
                                                            <input type="text" class="form-control" name="ais">
                                                        </div>
                                                    </div>
                                                    
                                                    <p style="margin-bottom: 0px;"><i>(In complete injuries only)</i></p>
                                                    <h4 style="margin: 0px 0px;">ZONE OF PARTIAL PRESERVATION</h4>
                                                    <p><i>Most caudal level with any innervation</i></p>
                                                    
                                                    <div class="inline fields" style="padding-top: 10px;">
                                                        <label style="margin-top: 15px;">SENSORY</label>
                                                        <div class="field">
                                                            <i style="margin-left: 50px;"><b>R</b></i><br>
                                                            <input type="text" class="form-control" name="sensoryRPP">
                                                        </div>
                                                        <div class="field">
                                                            <i style="margin-left: 50px;"><b>L</b></i><br>
                                                            <input type="text" class="form-control" name="sensoryLPP">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="inline fields">
                                                        <label style="margin-top: 15px; margin-right: 23px;">MOTOR</label>
                                                        <div class="field">
                                                            <input type="text" class="form-control" name="motorRPP">
                                                        </div>
                                                        <div class="field">
                                                            <input type="text" class="form-control" name="motorLPP">
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
            </form>
        </div>
    </div>
</div>
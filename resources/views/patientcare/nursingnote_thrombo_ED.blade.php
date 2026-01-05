<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        THROMBOPHLEBITIS FORM
        <div class="ui small blue icon buttons" id="btn_grp_edit_thrombo_ED" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_thrombo_ED"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_thrombo_ED"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_thrombo_ED"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_thrombo_ED"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <button class="ui button" id="thrombo_ED_chart"><span class="fa fa-print fa-lg"></span>Print</button>
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formThrombo_ED" class="floated ui form sixteen wide column">
            <input id="idno_thrombo" name="idno_thrombo" type="hidden">

                <div class="sixteen wide column">
                    <div class="ui grid">
                        <div class='five wide column' style="padding: 3px 3px 3px 3px;">
                            <div class="ui segments">
                                <div class="ui segment">
                                    <div class="ui grid">
                                        <table id="datetimethrombo_ED_tbl" class="ui celled table" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th class="scope">Cannulation No</th>
                                                    <th class="scope">mrn</th>
                                                    <th class="scope">episno</th>
                                                    <th class="scope">Date</th>
                                                    <th class="scope">Time</th>
                                                    <th class="scope">Entered By</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class='eleven wide column' style="padding: 3px 3px 3px 3px;">
                            <div class="ui segments">
                                <div class="ui secondary segment">CATHETER INSERTION</div>
                                    <div class="inline fields">
                                        <div class="field" style="padding: 20px 0px 0px 20px;">
                                            <label for="dateInsert">Date</label>
                                            <input id="dateInsert_ED" name="dateInsert" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" data-validation="required" data-validation-error-msg-required="Please enter date.">

                                                <label for="timeInsert" style="padding-right: 5px;padding-left: 5px">Time</label>
                                            <input id="timeInsert_ED" name="timeInsert" type="time" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter time.">
                                        </div>
                                    </div>

                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td style="font-weight: bold;padding: 20px 0px 5px 20px;">Gauge</td>
                                            <td colspan="2">
                                                <label class="radio-inline">
                                                    <input type="radio" name="gauge" value="16">16
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="gauge" value="18">18
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="gauge" value="20">20
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="gauge" value="22">22
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="gauge" value="24">24
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;padding: 20px 0px 5px 20px;">Attempts</td>
                                            <td colspan="2">
                                                <label class="radio-inline">
                                                    <input type="radio" name="attempts" value="1">1
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="attempts" value="2">2
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="attempts" value="3">3
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="attempts" value="4">4
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td rowspan="5" style="font-weight: bold;padding: 20px 0px 5px 20px;">Sites</td>
                                        </tr>
                                        <tr>
                                            <td width="10%"><label for="sitesMetacarpal"><i>Metacarpal:</i></label></td>
                                            <td>
                                                <label class="radio-inline">
                                                    <input type="radio" name="sitesMetacarpal" value="Rt">Right
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="sitesMetacarpal" value="Lt">Left
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%"><label for="sitesBasilic"><i>Basilic:</i></label></td>
                                            <td>
                                                <label class="radio-inline">
                                                    <input type="radio" name="sitesBasilic" value="Rt">Right
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="sitesBasilic" value="Lt">Left
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%"><label for="sitesCephalic"><i>Cephalic:</i></label></td>
                                            <td>
                                                <label class="radio-inline">
                                                    <input type="radio" name="sitesCephalic" value="Rt">Right
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="sitesCephalic" value="Lt">Left
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%"><label for="sitesMCubital"><i>M. Cubital:</i></label></td>
                                            <td>
                                                <label class="radio-inline">
                                                    <input type="radio" name="sitesMCubital" value="Rt">Right
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="sitesMCubital" value="Lt">Left
                                                </label>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="image">
                                    <img src="{{ asset('img/thrombophlebitis/thrombophlebitis_full.jpg') }}" class="ui centered medium image" style="width:400px">
                                </div>
                            </div>

                            <div class="ui segment">
                                <div class="ui grid">
                                    <input id="cannulationNo" name="cannulationNo" type="hidden">
                                    <p>
                                        <br><b>Phlebitis Score</b><br><br>
                                        Grade 0: IV site appears healthy.<br>
                                        Grade 1: One of the following is evident: slight pain at the IV site or slight redness near IV site.<br>
                                        Grade 2: Two of the following are evidence: pain, erythema and swelling.<br>
                                        Grade 3: All of the following sign are evidence: pain along the path of the catheter, erythema and induration.<br>
                                        Grade 4: All of the following sign are evidence and extensive: pain along the catheter, erythema, induration and palpable venous cord.<br>
                                        Grade 5: All of the following sign are evidence and extensive: pain along the catheter, erythema, induration, palpable venous cord and pyrexia.<br>
                                    </p>
                                    <div class="sixteen wide column">
                                        <div id="jqGridThrombo_ED_c" style="padding: 3px 3px 3px 3px;">
                                            <table id="jqGridThrombo_ED" class="table table-striped"></table>
                                            <div id="jqGridPagerThrombo_ED"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ui segments">
                                <div class="ui secondary segment">CATHETER REMOVAL</div>
                                <div class="inline fields">
                                    <div class="field" style="padding: 20px 0px 0px 20px;">
                                        <label for="dateRemoval">Date</label>
                                        <input id="dateRemoval_ED" name="dateRemoval" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>">

                                        <label for="timeRemoval" style="padding-right: 5px;padding-left: 5px">Time</label>
                                        <input id="timeRemoval_ED" name="timeRemoval" type="time" class="form-control input-sm">
                                    </div>
                                </div>
                                <div class="field" style="padding: 0px 20px 20px 20px;">
                                    <label for="totIndwelling" style="padding-right: 5px;">Total dwelling time in hours</label>
                                    <input id="totIndwelling_ED" name="totIndwelling" type="text" class="form-control input-sm">

                                    <label>Remarks</label>
                                    <textarea id="remarksThrombo_ED" name="remarksThrombo" type="text" rows="4"></textarea>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
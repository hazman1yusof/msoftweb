<div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
    <div class="panel panel-info">
        <div class="panel-heading text-center" style="height: 40px;">
            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                id="btn_grp_edit_thrombo"
                style="position: absolute;
                        padding: 0 0 0 0;
                        right: 40px;
                        top: 5px;">
                <button type="button" class="btn btn-default" id="new_thrombo">
                    <span class="fa fa-plus-square-o"></span> New 
                </button>
                <button type="button" class="btn btn-default" id="edit_thrombo">
                    <span class="fa fa-edit fa-lg"></span> Edit 
                </button>
                <button type="button" class="btn btn-default" data-oper='add' id="save_thrombo">
                    <span class="fa fa-save fa-lg"></span> Save 
                </button>
                <button type="button" class="btn btn-default" id="cancel_thrombo">
                    <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                </button>
                <!-- <button type="button" class="btn btn-default" id="thrombo_chart">
                    <span class="fa fa-print fa-lg"></span> Chart 
                </button> -->
            </div>
        </div>
        
        <!-- <button class="btn btn-default btn-sm" type="button" id="thrombo_chart" style="float: right; margin: 10px 40px 10px 0px;">Chart</button> -->
        
        <div class="panel-body" style="padding-right: 0px;">               
            <form class='form-horizontal' style='width: 99%;' id='formThrombo'>
            <input id="idno_thrombo" name="idno_thrombo" type="hidden">
                
                <div class="col-md-3" style="padding: 0 0 0 0;">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <table id="datetimethrombo_tbl" class="ui celled table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="scope">Cannulation<br>No</th>
                                        <th class="scope">mrn</th>
                                        <th class="scope">episno</th>
                                        <th class="scope">Date</th>
                                        <th class="scope">Time</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <div class='col-md-9' style="padding-right: 0px;">
                    <div class="panel panel-info">
                        <div class="panel-body" style="padding: 15px 0px;">
                            
                            <div class='col-md-12'>
                                <div class="panel panel-info">
                                    <div class="panel-heading text-center">CATHETER INSERTION</div>
                                    <div class="panel-body">
                                        <div class="form-inline col-md-12" style="padding-bottom: 15px;">
                                            <label class="control-label" for="dateInsert" style="padding-right: 5px;">Date</label>
                                            <input id="dateInsert" name="dateInsert" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information." value="<?php echo date("Y-m-d"); ?>">
                                            
                                            <label class="control-label" for="timeInsert" style="padding-left: 15px; padding-right: 5px;">Time</label>
                                            <input id="timeInsert" name="timeInsert" type="time" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information.">
                                        </div>

                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td style="font-weight: bold;">Gauge</td>
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
                                                    <td style="font-weight: bold;">Attempts</td>
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
                                                    <td rowspan="5" style="font-weight: bold;">Sites</td>
                                                </tr>
                                                <tr>
                                                    <td width="10%"><label for="sitesMetacarpal"><i>Metacarpal:</i></label></td>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="sitesMetacarpal" value="R">Right
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="sitesMetacarpal" value="L">Left
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="10%"><label for="sitesBasilic"><i>Basilic:</i></label></td>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="sitesBasilic" value="R">Right
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="sitesBasilic" value="L">Left
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="10%"><label for="sitesCephalic"><i>Cephalic:</i></label></td>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="sitesCephalic" value="R">Right
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="sitesCephalic" value="L">Left
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="10%"><label for="sitesMCubital"><i>M. Cubital:</i></label></td>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="sitesMCubital" value="R">Right
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="sitesMCubital" value="L">Left
                                                        </label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <img style="width:500px" src="{{ asset('img/thrombophlebitis/thrombophlebitis_full.jpg') }}" class="center">
                                    </div>
                                </div>
                            </div>

                            <div class='col-md-12'>
                                <div class="panel panel-info" id="jqGridThrombo_c">
                                    <div class="panel-body">
                                        <div class='col-md-12'>
                                            <input id="cannulationNo" name="cannulationNo" type="hidden">
                                            <b>Phlebitis Score</b><br><br>
                                            Grade 0: IV site appears healthy.<br>
                                            Grade 1: One of the following is evident: slight pain at the IV site or slight redness near IV site.<br>
                                            Grade 2: Two of the following are evidence: pain, erythema and swelling.<br>
                                            Grade 3: All of the following sign are evidence: pain along the path of the catheter, erythema and induration.<br>
                                            Grade 4: All of the following sign are evidence and extensive: pain along the catheter, erythema, induration and palpable venous cord.<br>
                                            Grade 5: All of the following sign are evidence and extensive: pain along the catheter, erythema, induration, palpable venous cord and pyrexia.<br><br>
                                            <table id="jqGridThrombo" class="table table-striped"></table>
                                            <div id="jqGridPagerThrombo"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class='col-md-12'>
                                <div class="panel panel-info">
                                    <div class="panel-heading text-center">CATHETER REMOVAL</div>
                                    <div class="panel-body">
                                        <div class="form-inline col-md-12" style="padding-bottom: 15px;">
                                            <label class="control-label" for="dateRemoval" style="padding-right: 5px;">Date</label>
                                            <input id="dateRemoval" name="dateRemoval" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>">
                                            
                                            <label class="control-label" for="timeRemoval" style="padding-left: 15px; padding-right: 5px;">Time</label>
                                            <input id="timeRemoval" name="timeRemoval" type="time" class="form-control input-sm">
                                        </div>

                                        <div class="col-md-12" style="padding-bottom: 15px;">
                                            <label class="control-label" for="totIndwelling">Total dwelling time in hours</label>
                                            <input id="totIndwelling" name="totIndwelling" type="text" class="form-control input-sm">
                                            
                                            <label class="control-label" for="remarksThrombo">Remarks</label>
                                            <textarea id="remarksThrombo" name="remarksThrombo" type="text" class="form-control input-sm"></textarea>
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
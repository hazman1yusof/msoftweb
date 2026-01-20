<div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
    <div class="panel panel-info">
        <div class="panel-heading text-center" style="height: 40px;">
            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                id="btn_grp_edit_pivc"
                style="position: absolute;
                        padding: 0 0 0 0;
                        right: 40px;
                        top: 5px;">
                <button type="button" class="btn btn-default" id="new_pivc">
                    <span class="fa fa-plus-square-o"></span> New 
                </button>
                <button type="button" class="btn btn-default" id="edit_pivc">
                    <span class="fa fa-edit fa-lg"></span> Edit 
                </button>
                <button type="button" class="btn btn-default" data-oper='add' id="save_pivc">
                    <span class="fa fa-save fa-lg"></span> Save 
                </button>
                <button type="button" class="btn btn-default" id="cancel_pivc">
                    <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                </button>
                <button type="button" class="btn btn-default" id="pivc_chart">
                    <span class="fa fa-print fa-lg"></span> Chart 
                </button>
            </div>
        </div>
        
        <!-- <button class="btn btn-default btn-sm" type="button" id="pivc_chart" style="float: right; margin: 10px 40px 10px 0px;">Chart</button> -->
        
        <div class="panel-body" style="padding-right: 0px;">                
            <form class='form-horizontal' style='width: 99%;' id='formPivc'>
            <input id="idno_pivc" name="idno_pivc" type="hidden">
                
                <div class="col-md-4" style="padding: 0 0 0 0;">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <table id="datetimepivc_tbl" class="ui celled table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="scope">idno</th>
                                        <th class="scope">mrn</th>
                                        <th class="scope">episno</th>
                                        <th class="scope">Date</th>
                                        <th class="scope">Entered By</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <div class='col-md-8' style="padding-right: 0px;">
                    <div class="panel panel-info">
                        <div class="panel-heading text-center">PERIPHERAL LINE MAINTENANCE BUNDLE CHECKLIST</div>
                        <div class="panel-body" style="padding: 15px 0px;">
    
                            <div class="form-inline col-md-12" style="padding-bottom: 15px;">

                                <label class="control-label" for="practiceDate" style="padding-right: 5px;">Date: </label>
                                <input id="practiceDate" name="practiceDate" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter date." value="<?php echo date("Y-m-d"); ?>">

                                <label class="control-label" for="consultant" style="padding-right: 5px;padding-left: 25px">Consultant: </label>
                                <input type="text" id="consultant" name="consultant" class="form-control input-sm" size="50">

                            </div>
                            
                            <div class='col-md-12'>
                                <div class="panel panel-info">
                                    <div class="panel-body">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr> 
                                                    <th scope="col">No</th>
                                                    <th scope="col" colspan="2">PRACTICE</th>
                                                    <th scope="col">M</th>
                                                    <th scope="col">E</th>
                                                    <th scope="col">N</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="font-weight: bold;">1</td>
                                                    <td>Hand hygiene with all 7 steps before IV care tasks.</td><td></td>
                                                    <td><input type="checkbox" id="hygiene_M" name="hygiene_M" value="1"></td>
                                                    <td><input type="checkbox" id="hygiene_E" name="hygiene_E" value="1"></td>
                                                    <td><input type="checkbox" id="hygiene_N" name="hygiene_N" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;">2</td>
                                                    <td>Dressing is changed as per protocol<br>Transparent dressing every 3-4 days. If condition of dressing not good (damp, loosened, soiled) than immediately.</td><td></td>
                                                    <td><input type="checkbox" id="dressing_M" name="dressing_M" value="1"></td>
                                                    <td><input type="checkbox" id="dressing_E" name="dressing_E" value="1"></td>
                                                    <td><input type="checkbox" id="dressing_N" name="dressing_N" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;">3</td>
                                                    <td>Alcohol swab used for site prep during dressing changes.</td><td></td>
                                                    <td><input type="checkbox" id="alcoholSwab_M" name="alcoholSwab_M" value="1"></td>
                                                    <td><input type="checkbox" id="alcoholSwab_E" name="alcoholSwab_E" value="1"></td>
                                                    <td><input type="checkbox" id="alcoholSwab_N" name="alcoholSwab_N" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;">4</td>
                                                    <td>Site labelled - Date and time marked on dressing.</td><td></td>
                                                    <td><input type="checkbox" id="siteLabelled_M" name="siteLabelled_M" value="1"></td>
                                                    <td><input type="checkbox" id="siteLabelled_E" name="siteLabelled_E" value="1"></td>
                                                    <td><input type="checkbox" id="siteLabelled_N" name="siteLabelled_N" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;">5</td>
                                                    <td>Correct solution, correct drop, tubing clear of bubble or blood.</td><td></td>
                                                    <td><input type="checkbox" id="correct_M" name="correct_M" value="1"></td>
                                                    <td><input type="checkbox" id="correct_E" name="correct_E" value="1"></td>
                                                    <td><input type="checkbox" id="correct_N" name="correct_N" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;">6</td>
                                                    <td>Multi Dose Vial/bags used for single patient only<br>Labelled with patient name, date of opening - to be discarded as per protocol.</td><td></td>
                                                    <td><input type="checkbox" id="multiDoseVial_M" name="multiDoseVial_M" value="1"></td>
                                                    <td><input type="checkbox" id="multiDoseVial_E" name="multiDoseVial_E" value="1"></td>
                                                    <td><input type="checkbox" id="multiDoseVial_N" name="multiDoseVial_N" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;">7</td>
                                                    <td>Clean/wipe the top of vials/bags before withdrawing medication.</td><td></td>
                                                    <td><input type="checkbox" id="cleanVial_M" name="cleanVial_M" value="1"></td>
                                                    <td><input type="checkbox" id="cleanVial_E" name="cleanVial_E" value="1"></td>
                                                    <td><input type="checkbox" id="cleanVial_N" name="cleanVial_N" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;">8</td>
                                                    <td>Use of split septum closed connectors (Qsyte: Stand-alone/Extensions).</td><td></td>
                                                    <td><input type="checkbox" id="splitSeptum_M" name="splitSeptum_M" value="1"></td>
                                                    <td><input type="checkbox" id="splitSeptum_E" name="splitSeptum_E" value="1"></td>
                                                    <td><input type="checkbox" id="splitSeptum_N" name="splitSeptum_N" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;">9</td>
                                                    <td>Clean/wipe the site hub before each access.</td><td></td>
                                                    <td><input type="checkbox" id="cleanSite_M" name="cleanSite_M" value="1"></td>
                                                    <td><input type="checkbox" id="cleanSite_E" name="cleanSite_E" value="1"></td>
                                                    <td><input type="checkbox" id="cleanSite_N" name="cleanSite_N" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;">10</td>
                                                    <td>Change split septum closed connectors at 72-96 hours.</td><td></td>
                                                    <td><input type="checkbox" id="chgSplitSeptum_M" name="chgSplitSeptum_M" value="1"></td>
                                                    <td><input type="checkbox" id="chgSplitSeptum_E" name="chgSplitSeptum_E" value="1"></td>
                                                    <td><input type="checkbox" id="chgSplitSeptum_N" name="chgSplitSeptum_N" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;">11</td>
                                                    <td>Flushing according to ACL protocols<br>All tubing clear of blood/drugs - Single Use Prefilled 0.9% NS Flushing Device (POSIFLUSH).</td><td></td>
                                                    <td><input type="checkbox" id="flushingACL_M" name="flushingACL_M" value="1"></td>
                                                    <td><input type="checkbox" id="flushingACL_E" name="flushingACL_E" value="1"></td>
                                                    <td><input type="checkbox" id="flushingACL_N" name="flushingACL_N" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;">12</td>
                                                    <td>Clamping of unused lines.</td><td></td>
                                                    <td><input type="checkbox" id="clamping_M" name="clamping_M" value="1"></td>
                                                    <td><input type="checkbox" id="clamping_E" name="clamping_E" value="1"></td>
                                                    <td><input type="checkbox" id="clamping_N" name="clamping_N" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;">13</td>
                                                    <td>Date on admit set - Administration set change according to guidelines<br>Intermittent IV Set - 24 hours<br>Continuous IV Set - 96 hours to 7 days<br>Blood Set with single ext Site - 4 hours.</td><td></td>
                                                    <td><input type="checkbox" id="set_M" name="set_M" value="1"></td>
                                                    <td><input type="checkbox" id="set_E" name="set_E" value="1"></td>
                                                    <td><input type="checkbox" id="set_N" name="set_N" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;">14</td>
                                                    <td>Removal of PIVC when clinically indicated.</td><td></td>
                                                    <td><input type="checkbox" id="removalPIVC_M" name="removalPIVC_M" value="1"></td>
                                                    <td><input type="checkbox" id="removalPIVC_E" name="removalPIVC_E" value="1"></td>
                                                    <td><input type="checkbox" id="removalPIVC_N" name="removalPIVC_N" value="1"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;"></td>
                                                    <td>Name :</td><td></td>
                                                    <td><input type="text" id="name_M" name="name_M" class="form-control input-sm"></td>
                                                    <td><input type="text" id="name_E" name="name_E" class="form-control input-sm"></td>
                                                    <td><input type="text" id="name_N" name="name_N" class="form-control input-sm"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;"></td>
                                                    <td>Time :</td><td></td>
                                                    <td><input type="time" id="datetime_M" name="datetime_M" class="form-control input-sm"></td>
                                                    <td><input type="time" id="datetime_E" name="datetime_E" class="form-control input-sm"></td>
                                                    <td><input type="time" id="datetime_N" name="datetime_N" class="form-control input-sm"></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="font-weight: bold;">AS PER INS GUIDELINES - ACL OF FLUSHING</td><td></td>
                                                    <td colspan="3" style="font-weight: bold;">** In case of Intermittent infusion, flushing need to be done every 6 hours</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="font-weight: bold;">ASSESS: AFTER INSERTION OF LINE<br>CLEAR: BEFORE, AFTER MEDICATION, BLOOD TRANSFUSION<br>LOCK: AFTER INFUSIONS, BLOOD DRAWS, TRANSFUSIONS</td><td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>

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

<div id="PIVCDialog" title="PIVC">
    <div class="panel panel-default">
        <div class="panel-body">
            <form class='form-horizontal' style='width: 99%;' id='formdata_PIVC'>
                <input type="hidden" name="action">
                
                <div class="form-group">
                    <div class="col-md-6">
                        <label class="control-label" for="Scol">Date From</label>
                        <input id="datefr_pivc" name="datefr" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                    </div>
                    <div class="col-md-6">
                        <label class="control-label" for="Scol">Date To</label>
                        <input id="dateto_pivc" name="dateto" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
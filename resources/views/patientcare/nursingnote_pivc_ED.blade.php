<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        PERIPHERAL LINE MAINTENANCE BUNDLE CHECKLIST
        <div class="ui small blue icon buttons" id="btn_grp_edit_pivc_ED" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_pivc_ED"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_pivc_ED"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_pivc_ED"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_pivc_ED"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <!-- <button class="ui button" id="pivc_ED_chart"><span class="fa fa-print fa-lg"></span>Print</button> -->
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formPivc_ED" class="floated ui form sixteen wide column">
            <input id="idno_pivc_ED" name="idno_pivc" type="hidden">

                <div class="sixteen wide column">
                    <div class="ui grid">
                        <div class='five wide column' style="padding: 3px 3px 3px 3px;">
                            <div class="ui segments">
                                <div class="ui segment">
                                    <div class="ui grid">
                                        <table id="datetimepivc_ED_tbl" class="ui celled table" style="width: 100%;">
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
                        </div>

                        <div class='eleven wide column' style="padding: 3px 3px 3px 3px;">
                            <div class="ui segment">
                                <div class='ui grid' style="padding: 5px 3px 3px 2px;">
                                    <div class="sixteen wide column" style="padding: 10px 0px 0px 3px;">
                                        <div class="inline fields">
                                            <div class="field">
                                                <label for="practiceDate" style="padding-right: 5px;">Date</label>
                                                <input id="practiceDate_ED" name="practiceDate" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" data-validation="required" data-validation-error-msg-required="Please enter date.">
                                            </div>
                                        </div>
                                    </div>

                                    <table class="table table-bordered small">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="background-color:#dddddd">No</th>
                                                <th scope="col" style="background-color:#dddddd">PRACTICE</th>
                                                <th scope="col" style="background-color:#dddddd"><center>M</center></th>
                                                <th scope="col" style="background-color:#dddddd"><center>E</center></th>
                                                <th scope="col" style="background-color:#dddddd"><center>N</center></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           <tr>
                                                <td>1</td>
                                                <td>Hand hygiene with all 7 steps before IV care tasks.</td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="hygiene_ED_M" name="hygiene_M" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="hygiene_ED_E" name="hygiene_E" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="hygiene_ED_N" name="hygiene_N" value="1"></td>
                                            </tr>

                                            <tr>
                                                <td>2</td>
                                                <td>Dressing is changed as per protocol<br>Transparent dressing every 3-4 days. If condition of dressing not good (damp, loosened, soiled) than immediately.</td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="dressing_ED_M" name="dressing_M" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="dressing_ED_E" name="dressing_E" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="dressing_ED_N" name="dressing_N" value="1"></td>
                                            </tr>

                                            <tr>
                                                <td>3</td>
                                                <td>Alcohol swab used for site prep during dressing changes.</td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="alcoholSwab_ED_M" name="alcoholSwab_M" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="alcoholSwab_ED_E" name="alcoholSwab_E" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="alcoholSwab_ED_N" name="alcoholSwab_N" value="1"></td>
                                            </tr>

                                            <tr>
                                                <td>4</td>
                                                <td>Site labelled - Date and time marked on dressing.</td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="siteLabelled_ED_M" name="siteLabelled_M" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="siteLabelled_ED_E" name="siteLabelled_E" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="siteLabelled_ED_N" name="siteLabelled_N" value="1"></td>
                                            </tr>

                                            <tr>
                                                <td>5</td>
                                                <td>Correct solution, correct drop, tubing clear of bubble or blood.</td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="correct_ED_M" name="correct_M" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="correct_ED_E" name="correct_E" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="correct_ED_N" name="correct_N" value="1"></td>
                                            </tr>

                                            <tr>
                                                <td>6</td>
                                                <td>Multi Dose Vial/bags used for single patient only<br>Labelled with patient name, date of opening - to be discarded as per protocol.</td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="multiDoseVial_ED_M" name="multiDoseVial_M" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="multiDoseVial_ED_E" name="multiDoseVial_E" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="multiDoseVial_ED_N" name="multiDoseVial_N" value="1"></td>
                                            </tr>

                                            <tr>
                                                <td>7</td>
                                                <td>Clean/wipe the top of vials/bags before withdrawing medication.</td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="cleanVial_ED_M" name="cleanVial_M" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="cleanVial_ED_E" name="cleanVial_E" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="cleanVial_ED_N" name="cleanVial_N" value="1"></td>
                                            </tr>

                                            <tr>
                                                <td>8</td>
                                                <td>Use of split septum closed connectors (Qsyte: Stand-alone/Extensions).</td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="splitSeptum_ED_M" name="splitSeptum_M" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="splitSeptum_ED_E" name="splitSeptum_E" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="splitSeptum_ED_N" name="splitSeptum_N" value="1"></td>
                                            </tr>

                                            <tr>
                                                <td>9</td>
                                                <td>Clean/wipe the site hub before each access.</td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="cleanSite_ED_M" name="cleanSite_M" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="cleanSite_ED_E" name="cleanSite_E" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="cleanSite_ED_N" name="cleanSite_N" value="1"></td>
                                            </tr>

                                            <tr>
                                                <td>10</td>
                                                <td>Change split septum closed connectors at 72-96 hours.</td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="chgSplitSeptum_ED_M" name="chgSplitSeptum_M" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="chgSplitSeptum_ED_E" name="chgSplitSeptum_E" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="chgSplitSeptum_ED_N" name="chgSplitSeptum_N" value="1"></td>
                                            </tr>

                                            <tr>
                                                <td>11</td>
                                                <td>Flushing according to ACL protocols<br>All tubing clear of blood/drugs - Single Use Prefilled 0.9% NS Flushing Device (POSIFLUSH).</td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="flushingACL_ED_M" name="flushingACL_M" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="flushingACL_ED_E" name="flushingACL_E" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="flushingACL_ED_N" name="flushingACL_N" value="1"></td>
                                            </tr>

                                            <tr>
                                                <td>12</td>
                                                <td>Clamping of unused lines.</td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="clamping_ED_M" name="clamping_M" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="clamping_ED_E" name="clamping_E" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="clamping_ED_N" name="clamping_N" value="1"></td>
                                            </tr>

                                            <tr>
                                                <td>13</td>
                                                <td>Date on admit set - Administration set change according to guidelines<br>Intermittent IV Set - 24 hours<br>Continuous IV Set - 96 hours to 7 days<br>Blood Set with single ext Site - 4 hours.</td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="set_ED_M" name="set_M" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="set_ED_E" name="set_E" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="set_ED_N" name="set_N" value="1"></td>
                                            </tr>

                                            <tr>
                                                <td>14</td>
                                                <td>Removal of PIVC when clinically indicated.</td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="removalPIVC_ED_M" name="removalPIVC_M" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="removalPIVC_ED_E" name="removalPIVC_E" value="1"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="checkbox" id="removalPIVC_ED_N" name="removalPIVC_N" value="1"></td>
                                            </tr>

                                            <tr>
                                                <td></td>
                                                <td>Name :</td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="text" id="name_ED_M" name="name_M" class="form-control input-sm"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="text" id="name_ED_E" name="name_E" class="form-control input-sm"></td>
                                                <td style="text-align:center; vertical-align:middle;"><input type="text" id="name_ED_N" name="name_N" class="form-control input-sm"></td>
                                            </tr>

                                            <tr>
                                                <td></td>
                                                <td>Time :</td>
                                                <td><input type="time" id="datetime_ED_M" name="datetime_M" class="form-control input-sm"></td>
                                                <td><input type="time" id="datetime_ED_E" name="datetime_E" class="form-control input-sm"></td>
                                                <td><input type="time" id="datetime_ED_N" name="datetime_N" class="form-control input-sm"></td>
                                            </tr>

                                            <tr>
                                                <td></td>
                                                <td style="font-weight: bold;">AS PER INS GUIDELINES - ACL OF FLUSHING</td>
                                                <td colspan="3" style="font-weight: bold;">** In case of Intermittent infusion, flushing need to be done every 6 hours</td>
                                            </tr>

                                            <tr>
                                                <td></td>
                                                <td style="font-weight: bold;">ASSESS: AFTER INSERTION OF LINE<br>CLEAR: BEFORE, AFTER MEDICATION, BLOOD TRANSFUSION<br>LOCK: AFTER INFUSIONS, BLOOD DRAWS, TRANSFUSIONS</td>
                                                <td colspan="3"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
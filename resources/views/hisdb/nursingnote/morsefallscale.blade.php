
<div class='col-md-12' style="padding-left: 0px; padding-right: 0px;">
    <div class="panel panel-info">
        <div class="panel-heading text-center" style="height: 40px;">
            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                id="btn_grp_edit_morsefallscale" 
                style="position: absolute; 
                        padding: 0 0 0 0; 
                        right: 40px; 
                        top: 5px;">
                <button type="button" class="btn btn-default" id="new_morsefallscale">
                    <span class="fa fa-plus-square-o"></span> New 
                </button>
                <button type="button" class="btn btn-default" id="edit_morsefallscale">
                    <span class="fa fa-edit fa-lg"></span> Edit 
                </button>
                <button type="button" class="btn btn-default" data-oper='add' id="save_morsefallscale">
                    <span class="fa fa-save fa-lg"></span> Save 
                </button>
                <button type="button" class="btn btn-default" id="cancel_morsefallscale">
                    <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                </button>
                <button type="button" class="btn btn-default" id="morsefallscale_chart">
                    <span class="fa fa-print fa-lg"></span> Chart 
                </button>
            </div>
        </div>
        
        <!-- <button class="btn btn-default btn-sm" type="button" id="morsefallscale_chart" style="float: right; margin: 10px 40px 10px 0px;">Chart</button> -->
        
        <div class="panel-body" style="padding-right: 0px;">
            <form class='form-horizontal' style='width: 99%;' id='formMorseFallScale'>
                <div class='col-md-12'>
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-md-1 control-label" for="morsefallscale_ward">Ward</label>
                                <div class="col-md-2">
                                    <input id="morsefallscale_ward" name="ward" type="text" class="form-control input-sm" rdonly>
                                </div>
                                
                                <!-- <label class="col-md-1 control-label" for="morsefallscale_bednum">Bed No.</label>
                                <div class="col-md-2">
                                    <input id="morsefallscale_bednum" name="bednum" type="text" class="form-control input-sm" rdonly>
                                </div> -->
                                
                                <label class="col-md-1 control-label" for="morsefallscale_diag">Diagnosis</label>
                                <div class="col-md-3">
                                    <textarea id="morsefallscale_diag" name="diag" type="text" class="form-control input-sm"></textarea>
                                </div>
                                
                                <label class="col-md-2 control-label" for="morsefallscale_admdate">Admission Date</label>
                                <div class="col-md-2">
                                    <input id="morsefallscale_admdate" name="admdate" type="date" class="form-control input-sm" rdonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class='col-md-12'>
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <input id="idno_morsefallscale" name="idno_morsefallscale" type="hidden">
                            <input id="morsefallscale_tabtitle" name="morsefallscale_tabtitle" value="morsefallscale" type="hidden">
                            
                            <div class="col-md-4" style="padding: 0 0 0 0;">
                                <div class="panel panel-info">
                                    <div class="panel-body">
                                        <table id="tbl_morsefallscale_date" class="ui celled table" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th class="scope">idno</th>
                                                    <th class="scope">mrn</th>
                                                    <th class="scope">episno</th>
                                                    <th class="scope">Date</th>
                                                    <th class="scope">Time</th>
                                                    <th class="scope">Entered By</th>
                                                    <th class="scope">dt</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class='col-md-8' style="padding-right: 0px;">
                                <div class="panel panel-info">
                                    <div class="panel-body" style="padding: 15px 0px;">
                                        <div class="form-inline col-md-12" style="padding-bottom: 15px;">
                                            <label class="control-label" for="morsefallscale_datetaken" style="padding-right: 5px;">Date</label>
                                            <input id="morsefallscale_datetaken" name="datetaken" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information." value="<?php echo date("Y-m-d"); ?>">
                                            
                                            <label class="control-label" for="morsefallscale_timetaken" style="padding-left: 15px; padding-right: 5px;">Time</label>
                                            <input id="morsefallscale_timetaken" name="timetaken" type="time" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information.">
                                        </div>
                                        
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td>History of fall</td>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="fallHistory" value="0" class="calc_morsefallscale">
                                                            No (0)
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="fallHistory" value="25" class="calc_morsefallscale">
                                                            Yes (25)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Secondary Diagnosis</td>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input class="form-check-input calc_morsefallscale" type="radio" name="secondaryDiag" value="0" style="margin-right: 5px;">
                                                            If only 1 active medical diagnosis (0)
                                                        </label><br>
                                                        <label class="radio-inline">
                                                            <input class="form-check-input calc_morsefallscale" type="radio" name="secondaryDiag" value="15" style="margin-right: 5px;">
                                                            Secondary diagnosis ≥ 2 medical diagnosis in chart (15)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Ambulatory Aids</td>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input class="form-check-input calc_morsefallscale" type="radio" name="ambulatoryAids" value="0" style="margin-right: 5px;">
                                                            None / Bed rest / Nurse Assist (0)
                                                        </label><br>
                                                        <label class="radio-inline">
                                                            <input class="form-check-input calc_morsefallscale" type="radio" name="ambulatoryAids" value="15" style="margin-right: 5px;">
                                                            Crutches / Cane / Walker (15)
                                                        </label><br>
                                                        <label class="radio-inline">
                                                            <input class="form-check-input calc_morsefallscale" type="radio" name="ambulatoryAids" value="30" style="margin-right: 5px;">
                                                            Furniture (Patient clutched onto furniture for support) (30)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>IV therapy / Heparin Lock (IV devices)</td>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="IVtherapy" value="0" class="calc_morsefallscale">
                                                            No (0)
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="IVtherapy" value="20" class="calc_morsefallscale">
                                                            Yes (20)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Gait</td>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input class="form-check-input calc_morsefallscale" type="radio" name="gait" value="0" style="margin-right: 5px;">
                                                            Normal / bed rest / immobile (0)
                                                        </label><br>
                                                        <label class="radio-inline">
                                                            <input class="form-check-input calc_morsefallscale" type="radio" name="gait" value="10" style="margin-right: 5px;">
                                                            Weak (10)
                                                        </label><br>
                                                        <label class="radio-inline">
                                                            <input class="form-check-input calc_morsefallscale" type="radio" name="gait" value="20" style="margin-right: 5px;">
                                                            Impaired (20)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Mental Status</td>
                                                    <td>
                                                        <label class="radio-inline">
                                                            <input class="form-check-input calc_morsefallscale" type="radio" name="mentalStatus" value="0" style="margin-right: 5px;">
                                                            Oriented to own ability (0)
                                                        </label><br>
                                                        <label class="radio-inline">
                                                            <input class="form-check-input calc_morsefallscale" type="radio" name="mentalStatus" value="15" style="margin-right: 5px;">
                                                            Over estimates or forgets limitations (15)
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Total Score : </td>
                                                    <td>
                                                        <input id="morsefallscale_totalScore" name="totalScore" type="text" class="form-control input-sm" rdonly>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Name of Staff : </td>
                                                    <td>
                                                        <!-- <input id="morsefallscale_staffname" name="staffname" type="text" class="form-control input-sm"> -->
                                                        <input id="morsefallscale_adduser" name="adduser" type="text" class="form-control input-sm" rdonly>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        
                                        <table class="table table-bordered">
                                            <!-- <thead>
                                                <tr>
                                                    <th scope="col">LEVEL OF RISK</th>
                                                    <th scope="col">MFS SCORE</th>
                                                    <th scope="col">COLOUR CODE</th>
                                                    <th scope="col">ACTION</th>
                                                    <td rowspan="5">Patient assessment <b>MUST be done Daily</b> and during change of patient's status</td>
                                                </tr>
                                            </thead> -->
                                            <tbody>
                                                <tr>
                                                    <td width="25%"><b>LEVEL OF RISK</b></td>
                                                    <td width="15%"><b>MFS SCORE</b></td>
                                                    <td width="15%"><b>COLOUR CODE</b></td>
                                                    <td width="20%"><b>ACTION</b></td>
                                                    <td rowspan="5">Patient assessment <b>MUST be done Daily</b> and during change of patient's status</td>
                                                </tr>
                                                <tr>
                                                    <td>NO RISKS FOR FALL</td>
                                                    <td>0</td>
                                                    <td>None</td>
                                                    <td rowspan="2">Implement Standard Falls Risk Interventions</td>
                                                    <!-- <td></td> -->
                                                </tr>
                                                <tr>
                                                    <td>LOW RISK</td>
                                                    <td>1 - 24</td>
                                                    <td>WHITE</td>
                                                    <!-- <td></td> -->
                                                    <!-- <td></td> -->
                                                </tr>
                                                <tr>
                                                    <td>MODERATE RISK</td>
                                                    <td>25 - 45</td>
                                                    <td>YELLOW</td>
                                                    <td>Implement Moderate Falls Risk Interventions</td>
                                                    <!-- <td></td> -->
                                                </tr>
                                                <tr>
                                                    <td>HIGH RISK</td>
                                                    <td>> 45</td>
                                                    <td>RED</td>
                                                    <td>Implement High Falls Risk Interventions</td>
                                                    <!-- <td></td> -->
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

<div class='col-md-12' style="padding-left: 0px; padding-right: 0px; display: none;">
    <div class="panel panel-info" id="jqGridMorseFallScale_c">
        <!-- <div class="panel-heading text-center">DAILY MORSE FALL SCALE ASSESSMENT CHART</div> -->
        <div class="panel-body">
            <div class='col-md-12' style="padding: 0 0 15px 0;">
                <table id="jqGridMorseFallScale" class="table table-striped"></table>
                <div id="jqGridPagerMorseFallScale"></div>
            </div>
            
            <div class="col-md-5" style="padding-top: 20px; text-align: left; color: red;">
                <p id="p_error2"></p>
            </div>
        </div>
    </div>
</div>

<div id="MorseFallScaleDialog" title="Daily Morse Fall Scale Assessment">
    <div class="panel panel-default">
        <!-- <div class="panel-heading">Daily Morse Fall Scale Assessment</div> -->
        <div class="panel-body">
            <form class='form-horizontal' style='width: 99%;' id='formMorseFallScaleDialog'>
                <input type="hidden" name="action">
                
                <div class="form-group">
                    <div class="col-md-6">
                        <label class="control-label" for="Scol">From</label>
                        <input id="morsefallscale_datefr" name="datefr" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                    </div>
                    <div class="col-md-6">
                        <label class="control-label" for="Scol">To</label>
                        <input id="morsefallscale_dateto" name="dateto" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
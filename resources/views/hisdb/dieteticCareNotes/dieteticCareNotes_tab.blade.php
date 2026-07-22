<!-- <div class="panel-body paneldiv" style="overflow-y: auto;"> -->
    <div class='col-md-12'>
        <div class="panel panel-default">
            <div class="panel-heading text-center" style="position: sticky; top: 0px; z-index: 3;">DIETETIC CARE NOTES
                <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                    id="btn_grp_edit_dieteticCareNotes" 
                    style="position: absolute; 
                            padding: 0 0 0 0; 
                            right: 40px; 
                            top: 5px;">
                    <button type="button" class="btn btn-default" id="new_dieteticCareNotes">
                        <span class="fa fa-plus-square-o"></span> New 
                    </button>
                    <!-- <button type="button" class="btn btn-default" id="edit_dieteticCareNotes">
                        <span class="fa fa-edit fa-lg"></span> Edit 
                    </button> -->
                    <button type="button" class="btn btn-default" data-oper='add' id="save_dieteticCareNotes">
                        <span class="fa fa-save fa-lg"></span> Save 
                    </button>
                    <button type="button" class="btn btn-default" id="cancel_dieteticCareNotes">
                        <span class="fa fa-ban fa-lg" aria-hidden="true"></span> Cancel 
                    </button>
                </div>
            </div>
            <div class="panel-body">
                <div class='col-md-12' style="padding: 0 0 15px 0;">
                    <div class="col-md-4" style="padding-left: 0px;">
                        <!-- table dietNote_date -->
                        <div id="dietNote_date_tbl_sticky" style="padding: 0 0 0 0;">
                            <div class="panel panel-info" style="margin-top: 10px;">
                                <div class="panel-body">
                                    <table id="dietNote_date_tbl" class="ui celled table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="scope">idno</th>
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
                    
                    <div class="col-md-8" style="padding: 0 0 0 5px; float: right;">
                        <form class='form-horizontal' style='width: 99%;' id='formDieteticCareNotes'>
                            <!-- <input id="mrn_dieteticCareNotes" name="mrn_dieteticCareNotes" type="text">
                            <input id="episno_dieteticCareNotes" name="episno_dieteticCareNotes" type="text"> -->
                            <input id="idno_dieteticCareNotes" name="idno_dieteticCareNotes" type="hidden">

                            <div class="panel panel-info">
                                <div class="panel-body">
                                    <div class="form-inline col-md-12" style="padding-bottom: 15px;">
                                        <label class="control-label" for="datetaken" style="padding-right: 5px;">Date</label>
                                        <input id="dietNote_datetaken" name="datetaken" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information." value="<?php echo date("Y-m-d"); ?>">
                                        
                                        <label class="control-label" for="timetaken" style="padding-left: 15px; padding-right: 5px;">Time</label>
                                        <input id="dietNote_timetaken" name="timetaken" type="time" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information.">
                                    </div>
                                    
                                    <div class='col-md-12'>
                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">DIETETIC CARE NOTES</div>
                                            <div class="panel-body">
                                                <textarea id="dietNote_progressnote" name="progress" type="text" class="form-control input-sm" rows="20"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- </div> -->
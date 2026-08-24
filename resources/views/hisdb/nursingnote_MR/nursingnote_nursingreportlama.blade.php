<div class='col-md-4' style="padding-left: 0px; padding-right: 3px;">
    <div class="panel panel-info">
        <div class="panel-heading text-center" style="height: 60px;">
            <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 45px; top: 5px;">
                <h6>TREATMENT &</h6>
                <h6>PROCEDURE</h6>
            </div>
            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                id="btn_grp_edit_treatment"
                style="position: absolute;
                        padding: 0 0 0 0;
                        right: 10px;
                        top: 15px;">
                <button type="button" class="btn btn-default" id="new_treatment">
                    <span class="fa fa-plus-square-o"></span> New 
                </button>
                <button type="button" class="btn btn-default" id="edit_treatment">
                    <span class="fa fa-edit fa-lg"></span> Edit 
                </button>
                <button type="button" class="btn btn-default" data-oper='add' id="save_treatment">
                    <span class="fa fa-save fa-lg"></span> Save 
                </button>
                <button type="button" class="btn btn-default" id="cancel_treatment">
                    <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                </button>
            </div>
        </div>
        
        <div class="panel-body" style="padding: 15px 5px;">
            <form class='form-horizontal' style='width: 99%;' id='formTreatmentP'>
                <input id="tr_idno" name="tr_idno" type="hidden">
                <input id="tr_adduser" name="tr_adduser" type="hidden">
                
                <div class="col-md-3" style="padding: 0 0 0 0;">
                    <div class="panel panel-info">
                        <div class="panel-body" style="padding: 0 0 0 0;">
                            <table id="tbl_treatment" class="ui celled table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="scope">idno</th>
                                        <th class="scope">mrn</th>
                                        <th class="scope">episno</th>
                                        <th class="scope">Date/Time</th>
                                        <th class="scope">adduser</th>
                                        <th class="scope">dt</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class='col-md-9' style="padding-left: 5px; padding-right: 0px;">
                    <div class="panel panel-info">
                        <!-- <div class="panel-heading text-center">NOTES</div> -->
                        <div class="panel-body" style="padding: 2px;">
                            <div class="form-inline col-md-12" style="padding: 10px 15px 10px 0px;">
                                <label class="control-label" for="tr_entereddate">Date</label>
                                <input id="tr_entereddate" name="tr_entereddate" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information." value="<?php echo date("Y-m-d"); ?>" required>
                            </div>
                            
                            <div class="form-inline col-md-12" style="padding: 0px 15px 10px 0px;">
                                <label class="control-label" for="tr_enteredtime">Time</label>
                                <input id="tr_enteredtime" name="tr_enteredtime" type="time" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information." required>
                            </div>
                            
                            <div class="form-group" style="padding-left: 15px; padding-right: 15px;">
                                <label for="treatment_remarks">Notes</label>
                                <textarea id="treatment_remarks" name="treatment_remarks" type="text" class="form-control input-sm"></textarea>
                            </div>
                            
                            <div class="form-group" style="padding-left: 15px; padding-right: 15px;">
                                <label for="treatment_adduser">Entered by</label>
                                <input id="treatment_adduser" name="treatment_adduser" type="text" class="form-control input-sm" rdonly>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class='col-md-4' style="padding-left: 3px; padding-right: 3px;">
    <div class="panel panel-info">
        <div class="panel-heading text-center" style="height: 60px;">
            <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 45px; top: 15px;">
                <h6>INVESTIGATION</h6>
            </div>
            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                id="btn_grp_edit_investigation"
                style="position: absolute;
                        padding: 0 0 0 0;
                        right: 10px;
                        top: 15px;">
                <button type="button" class="btn btn-default" id="new_investigation">
                    <span class="fa fa-plus-square-o"></span> New 
                </button>
                <button type="button" class="btn btn-default" id="edit_investigation">
                    <span class="fa fa-edit fa-lg"></span> Edit 
                </button>
                <button type="button" class="btn btn-default" data-oper='add' id="save_investigation">
                    <span class="fa fa-save fa-lg"></span> Save 
                </button>
                <button type="button" class="btn btn-default" id="cancel_investigation">
                    <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                </button>
            </div>
        </div>
        
        <div class="panel-body" style="padding: 15px 5px;">
            <form class='form-horizontal' style='width: 99%;' id='formInvestigation'>
                <input id="inv_idno" name="inv_idno" type="hidden">
                <input id="inv_adduser" name="inv_adduser" type="hidden">
                
                <div class="col-md-3" style="padding: 0 0 0 0;">
                    <div class="panel panel-info">
                        <div class="panel-body" style="padding: 0 0 0 0;">
                            <table id="tbl_investigation" class="ui celled table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="scope">idno</th>
                                        <th class="scope">mrn</th>
                                        <th class="scope">episno</th>
                                        <th class="scope">Date/Time</th>
                                        <th class="scope">adduser</th>
                                        <th class="scope">dt</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class='col-md-9' style="padding-left: 5px; padding-right: 0px;">
                    <div class="panel panel-info">
                        <!-- <div class="panel-heading text-center">NOTES</div> -->
                        <div class="panel-body" style="padding: 2px;">
                            <div class="form-inline col-md-12" style="padding: 10px 15px 10px 0px;">
                                <label class="control-label" for="inv_entereddate">Date</label>
                                <input id="inv_entereddate" name="inv_entereddate" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information." value="<?php echo date("Y-m-d"); ?>">
                            </div>
                            
                            <div class="form-inline col-md-12" style="padding: 0px 15px 10px 0px;">
                                <label class="control-label" for="inv_enteredtime">Time</label>
                                <input id="inv_enteredtime" name="inv_enteredtime" type="time" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information.">
                            </div>
                            
                            <div class="form-group" style="padding-left: 15px; padding-right: 15px;">
                                <label for="investigation_remarks">Notes</label>
                                <textarea id="investigation_remarks" name="investigation_remarks" type="text" class="form-control input-sm"></textarea>
                            </div>
                            
                            <div class="form-group" style="padding-left: 15px; padding-right: 15px;">
                                <label for="investigation_adduser">Entered by</label>
                                <input id="investigation_adduser" name="investigation_adduser" type="text" class="form-control input-sm" rdonly>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class='col-md-4' style="padding-left: 3px; padding-right: 0px;">
    <div class="panel panel-info">
        <div class="panel-heading text-center" style="height: 60px;">
            <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 45px; top: 15px;">
                <h6>INJECTION</h6>
            </div>
            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                id="btn_grp_edit_injection"
                style="position: absolute;
                        padding: 0 0 0 0;
                        right: 10px;
                        top: 15px;">
                <button type="button" class="btn btn-default" id="new_injection">
                    <span class="fa fa-plus-square-o"></span> New 
                </button>
                <button type="button" class="btn btn-default" id="edit_injection">
                    <span class="fa fa-edit fa-lg"></span> Edit 
                </button>
                <button type="button" class="btn btn-default" data-oper='add' id="save_injection">
                    <span class="fa fa-save fa-lg"></span> Save 
                </button>
                <button type="button" class="btn btn-default" id="cancel_injection">
                    <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                </button>
            </div>
        </div>
        
        <div class="panel-body" style="padding: 15px 5px;">
            <form class='form-horizontal' style='width: 99%;' id='formInjection'>
                <input id="inj_idno" name="inj_idno" type="hidden">
                <input id="inj_adduser" name="inj_adduser" type="hidden">

                <div class="col-md-3" style="padding: 0 0 0 0;">
                    <div class="panel panel-info">
                        <div class="panel-body" style="padding: 0 0 0 0;">
                            <table id="tbl_injection" class="ui celled table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="scope">idno</th>
                                        <th class="scope">mrn</th>
                                        <th class="scope">episno</th>
                                        <th class="scope">Date/Time</th>
                                        <th class="scope">adduser</th>
                                        <th class="scope">dt</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class='col-md-9' style="padding-left: 5px; padding-right: 0px;">
                    <div class="panel panel-info">
                        <!-- <div class="panel-heading text-center">NOTES</div> -->
                        <div class="panel-body" style="padding: 2px;">
                            <div class="form-inline col-md-12" style="padding: 10px 15px 10px 0px;">
                                <label class="control-label" for="inj_entereddate">Date</label>
                                <input id="inj_entereddate" name="inj_entereddate" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information." value="<?php echo date("Y-m-d"); ?>">
                            </div>
                            
                            <div class="form-inline col-md-12" style="padding: 0px 15px 10px 0px;">
                                <label class="control-label" for="inj_enteredtime">Time</label>
                                <input id="inj_enteredtime" name="inj_enteredtime" type="time" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information.">
                            </div>
                            
                            <div class="form-group" style="padding-left: 15px; padding-right: 15px;">
                                <label for="injection_remarks">Notes</label>
                                <textarea id="injection_remarks" name="injection_remarks" type="text" class="form-control input-sm"></textarea>
                            </div>
                            
                            <div class="form-group" style="padding-left: 15px; padding-right: 15px;">
                                <label for="injection_adduser">Entered by</label>
                                <input id="injection_adduser" name="injection_adduser" type="text" class="form-control input-sm" rdonly>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
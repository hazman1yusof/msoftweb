
<div class="panel panel-default" style="position: relative;" id="jqGridDoctorNote_c">
	
	<div class="panel-heading clearfix collapsed position" id="toggle_doctorNote" style="position: sticky;top: 0px;z-index: 3;">
		<b>Name: <span id="name_show_doctorNote"></span></b><br>
		MRN: <span id="mrn_show_doctorNote"></span>

        <div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 800px; top: 15px;">
            <label class="radio-inline">
                <input class="form-check-input" type="radio" name="toggle_type" id="current" value="current" checked>
                <label class="form-check-label" for="current">Current</label>
            </label>
            <label class="radio-inline">
                <input class="form-check-input" type="radio" name="toggle_type" id="past" value="past">
                <label class="form-check-label" for="past">Past History</label>
            </label>
        </div>

		<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridDoctorNote_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridDoctorNote_panel"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 310px; top: 15px;">
			<h5>Doctor Note</h5>
		</div>

        <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
            id="btn_grp_edit_doctorNote"
            style="position: absolute;
                    padding: 0 0 0 0;
                    right: 40px;
                    top: 15px;" 

        >
            <button type="button" class="btn btn-default" id="new_doctorNote">
                <span class="fa fa-plus-square-o"></span> New
            </button>
            <button type="button" class="btn btn-default" id="edit_doctorNote">
                <span class="fa fa-edit fa-lg"></span> Edit
            </button>
            <button type="button" class="btn btn-default" data-oper='add' id="save_doctorNote">
                <span class="fa fa-save fa-lg"></span> Save
            </button>
            <button type="button" class="btn btn-default" id="cancel_doctorNote">
                <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
            </button>
        </div>
	</div>
	<div id="jqGridDoctorNote_panel" class="panel-collapse collapse">
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<form class='form-horizontal' style='width:99%' id='formDoctorNote'>

                    <input id="mrn_doctorNote" name="mrn_doctorNote" type="hidden">
                    <input id="episno_doctorNote" name="episno_doctorNote" type="hidden">
                    <input id="recorddate" name="recorddate" type="hidden">


                    <div class="col-md-2" id="docnote_date_tbl_sticky" style="padding:0 0 0 0">
                        <div class="panel panel-info">
                            <div class="panel-body">

                                <table id="docnote_date_tbl" class="table-hover table-bordered" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>idno</th>
                                            <th class="scope">Date</th>
                                        </tr>
                                    </thead>
                                </table>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-10" style="padding:0 0 0 5px; float: right;">
                        <div class="panel panel-info">
                            <div class="panel-body">

                                <div class="form-group">
                                    <label class="col-md-2 control-label" for="remarks">Patient Complaint</label>
                                    <div class="col-md-6">
                                        <input id="remarks" name="remarks" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter Patient Complaint">
                                    </div>
                                </div>

                                <div class="col-md-12" style="padding:0px;">
                                    <div class='col-md-9'>
                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">CLINICAL NOTE</div>
                                            <div class="panel-body">

                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="control-label" for="clinicnote" style="padding-bottom:5px">History of Presenting Complaint</label>
                                                        <textarea id="clinicnote" name="clinicnote" type="text" class="form-control input-sm" rows="6"></textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="control-label" for="pmh" style="padding-bottom:5px">Past Medical History</label>
                                                        <textarea id="pmh" name="pmh" type="text" class="form-control input-sm" rows="6"></textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="control-label" for="drugh" style="padding-bottom:5px">Drug History</label>
                                                        <textarea id="drugh" name="drugh" type="text" class="form-control input-sm" rows="6"></textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="control-label" for="allergyh" style="padding-bottom:5px">Allergy History</label>
                                                        <textarea id="allergyh" name="allergyh" type="text" class="form-control input-sm" rows="6"></textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="control-label" for="socialh" style="padding-bottom:5px">Social History</label>
                                                        <textarea id="socialh" name="socialh" type="text" class="form-control input-sm" rows="6"></textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="control-label" for="fmh" style="padding-bottom:5px">Family History</label>
                                                        <textarea id="fmh" name="fmh" type="text" class="form-control input-sm" rows="6"></textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-12" style="margin-top: 10px">
                                                    <div class="panel panel-info">
                                                        <div class="panel-heading text-center">FOLLOW UP</div>
                                                        <div class="panel-body">

                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <label class="col-md-2 control-label" for="followuptime">Time</label>  
                                                                    <div class="col-md-10">
                                                                        <input id="followuptime" name="followuptime" type="time" class="form-control input-sm">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-6">
                                                                    <label class="col-md-2 control-label" for="followupdate">Date</label>  
                                                                    <div class="col-md-10">
                                                                        <input id="followupdate" name="followupdate" type="date" class="form-control input-sm">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>

                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">Physical Examination</div>
                                            <div class="panel-body">

                                                <div class="form-group">
                                                    <!-- <label class="col-md-3 control-label" for="examination">Physical Examination</label> -->
                                                    <div class="col-md-12">
                                                        <textarea id="examination" name="examination" type="text" class="form-control input-sm" rows="6"></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">Diagnosis</div>
                                            <div class="panel-body">

                                                <div class="form-group">
                                                    <!-- <label class="col-md-3 control-label" for="diagfinal">Diagnosis</label> -->
                                                    <div class="col-md-12">
                                                        <textarea id="diagfinal" name="diagfinal" type="text" class="form-control input-sm" rows="4"></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2 control-label" for="icdcode" style="text-align: left;">Primary ICD</label>
                                            <div class="col-md-7">
                                                <div class='input-group'>
                                                    <input id="icdcode" name="icdcode" type="text" class="form-control input-sm">
                                                    <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>

                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">Plan</div>
                                            <div class="panel-body">

                                                <div class="form-group">
                                                    <!-- <label class="col-md-3 control-label" for="plan_">Plan</label> -->
                                                    <div class="col-md-12">
                                                        <textarea id="plan_" name="plan_" type="text" class="form-control input-sm" rows="6"></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                        
                                    <div class="col-md-3" style="padding:0 0 0 0">
                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">Vital Sign</div>
                                            <div class="panel-body">

                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="height" style="padding-bottom:5px">Height</label>
                                                    <div class="input-group">
                                                        <input id="height" name="height" type="number" class="form-control input-sm floatNumberField" pattern="^\d*(\.\d{0,2})?$" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                        <span class="input-group-addon">cm</span>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="weight" style="padding-bottom:5px">Weight</label>
                                                    <div class="input-group">
                                                        <input id="weight" name="weight" type="number" class="form-control input-sm floatNumberField" pattern="^\d*(\.\d{0,2})?$" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                        <span class="input-group-addon">kg</span>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="bmi" style="padding-bottom:5px">BMI</label>
                                                    <input id="bmi" name="bmi" type="number" class="form-control input-sm" rdonly>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="bp" style="padding-bottom:5px">BP</label>
                                                    <div class="input-group">
                                                        <input id="bp_sys1" name="bp_sys1" type="number" class="form-control input-sm floatNumberField" pattern="^\d*(\.\d{0,2})?$" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                        <input id="bp_dias2" name="bp_dias2" type="number" class="form-control input-sm floatNumberField" pattern="^\d*(\.\d{0,2})?$" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                        <span class="input-group-addon">mmHg</span>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="pulse" style="padding-bottom:5px">Pulse Rate</label>
                                                    <input id="pulse" name="pulse" type="number" class="form-control input-sm floatNumberField" pattern="^\d*(\.\d{0,2})?$" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="temperature" style="padding-bottom:5px">Temperature</label>
                                                    <div class="input-group">
                                                        <input id="temperature" name="temperature" type="number" class="form-control input-sm floatNumberField" pattern="^\d*(\.\d{0,2})?$" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                        <span class="input-group-addon">°C</span>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="respiration" style="padding-bottom:5px">Respiration</label>
                                                    <input id="respiration" name="respiration" type="number" class="form-control input-sm floatNumberField" pattern="^\d*(\.\d{0,2})?$" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                </div>

                                            </div>
                                        </div>

                                        <!-- to add spaces between panels -->
                                        <div class="panel panel-info" style="height: 1035px;border: 0;box-shadow: none;">
                                            <div class="panel-body">
                                            </div>
                                        </div>
                                        <!-- to add spaces between panels -->

                                        <div class="panel panel-info">
                                            <div class="panel-body">

                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="adduser" style="padding-bottom:5px">Added by</label>  
                                                    <input id="adduser" name="adduser" type="text" class="form-control input-sm" rdonly>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="adddate" style="padding-bottom:5px">Date</label>
                                                    <input id="adddate" name="adddate" type="text" class="form-control input-sm" rdonly>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12" id="addnotes" style="display:none">
                                    <div class="panel panel-info" >
                                        <div class="panel-heading text-center">ADDITIONAL NOTES</div>
                                        <div class="panel-body">
                                            <div class='col-md-12' style="padding:0 0 15px 0" id="jqGridAddNotes_c">
                                                <table id="jqGridAddNotes" class="table table-striped"></table>
                                                <div id="jqGridPagerAddNotes"></div>
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
</div>

<div class="panel panel-default" style="position: relative;" id="jqGridDoctorNote_c">
    <input type="hidden" name="curr_user" id="curr_user" value="{{ Auth::user()->username }}">
    
	<div class="panel-heading clearfix collapsed position" id="toggle_doctorNote" style="position: sticky;top: 0px;z-index: 3;">
		<b>NAME: <span id="name_show_doctorNote"></span></b><br>
		MRN: <span id="mrn_show_doctorNote"></span>
        SEX: <span id="sex_show_doctorNote"></span>
        DOB: <span id="dob_show_doctorNote"></span>
        AGE: <span id="age_show_doctorNote"></span>
        RACE: <span id="race_show_doctorNote"></span>
        RELIGION: <span id="religion_show_doctorNote"></span><br>
        OCCUPATION: <span id="occupation_show_doctorNote"></span>
        CITIZENSHIP: <span id="citizenship_show_doctorNote"></span>
        AREA: <span id="area_show_doctorNote"></span>

		<i class="arrow fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridDoctorNote_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px" data-toggle="collapse" data-target="#jqGridDoctorNote_panel"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 310px; top: 25px;">
			<h5>Doctor Note</h5>
		</div>
        
        <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
            id="btn_grp_edit_doctorNote"
            style="position: absolute;
                    padding: 0 0 0 0;
                    right: 40px;
                    top: 25px;" 

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
		<div class="panel-body paneldiv" style="overflow-y: auto;">
			<div class='col-md-12' style="padding:0 0 15px 0">
                
                <div class="col-md-2" style="padding-right: 0px; padding-left: 0px;">
                    <!-- toggle radio button -->
                    <div class="panel panel-info" 
                        style="
                            margin-bottom: 0px; 
                            @if (Request::path() == 'casenote')
                                display: none
                            @endif
                        ">
                        <div class="panel-body" style="padding-top:5px;padding-bottom:5px;">
                            <label class="radio-inline">
                                <input class="form-check-input" type="radio" name="toggle_type" id="current" value="current"
                                @if (Request::path() != 'casenote')
                                    checked
                                @endif>
                                <label class="form-check-label" for="current" style="padding-right: 10px;">Current</label>
                            </label>
                            <label class="radio-inline" style="margin-left: 0px;">
                                <input class="form-check-input" type="radio" name="toggle_type" id="past" value="past"
                                @if (Request::path() == 'casenote')
                                    checked
                                @endif>
                                <label class="form-check-label" for="past">Past History</label>
                            </label>
                        </div>
                    </div>
                    
                    <!-- table docnote_date -->
                    <div id="docnote_date_tbl_sticky" style="padding:0 0 0 0">
                        <div class="panel panel-info" style="margin-top: 10px;">
                            <div class="panel-body">
                                
                                <table id="docnote_date_tbl" class="ui celled table" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th class="scope">mrn</th>
                                            <th class="scope">episno</th>
                                            <th class="scope">Date</th>
                                            <th class="scope">adduser</th>
                                            <th class="scope">Doctor</th>
                                        </tr>
                                    </thead>
                                </table>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-10" style="padding:0 0 0 5px; float: right;">
                    <form class='form-horizontal' style='width:99%' id='formDoctorNote'>
                        
                        <input id="mrn_doctorNote" name="mrn_doctorNote" type="hidden">
                        <input id="episno_doctorNote" name="episno_doctorNote" type="hidden">
                        <input id="recorddate_doctorNote" name="recorddate_doctorNote" type="hidden">
                        <input id="ptname_doctorNote" name="ptname_doctorNote" type="hidden">
                        
                        <div class="panel panel-info">
                            <div class="panel-body">
                                
                                <div class="form-group">
                                    <label class="col-md-2 control-label" for="complain">Patient Complaint</label>
                                    <div class="col-md-5">
                                        <input id="complain" name="complain" type="text" class="form-control input-sm">
                                    </div>
                                    @if(Auth::user()->doctor == '1')
                                    <button class="btn btn-default btn-sm" type="button" id="referLetter" style="float: right; margin-right: 40px;">Referral Letter</button>
                                    <button class="btn btn-default btn-sm" type="button" id="doctornote_medc" style="float: right; margin-right: 20px;">MC Letter</button>
                                    @endif>
                                    
                                    <!-- <span class="label label-info" style="margin-left: 30px;font-size: 100%;">Written By: <span id="doctorcode" name="doctorcode"></span></span> -->
                                </div>
                                
                                <div class="col-md-12" style="padding:0px;">
                                    <div class='col-md-9'>
                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">CLINICAL NOTE</div>
                                            <div class="panel-body">
                                                
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="control-label" for="clinicnote" style="padding-bottom:5px">History of Presenting Complaint</label>
                                                        <textarea id="clinicnote" name="clinicnote" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="control-label" for="pmh" style="padding-bottom:5px">Past Medical History</label>
                                                        <textarea id="pmh" name="pmh" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="control-label" for="drugh" style="padding-bottom:5px">Drug History</label>
                                                        <textarea id="drugh" name="drugh" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="control-label" for="allergyh" style="padding-bottom:5px">Allergy History</label>
                                                        <textarea id="allergyh" name="allergyh" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="control-label" for="socialh" style="padding-bottom:5px">Social History</label>
                                                        <textarea id="socialh" name="socialh" type="text" class="form-control input-sm"></textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="control-label" for="fmh" style="padding-bottom:5px">Family History</label>
                                                        <textarea id="fmh" name="fmh" type="text" class="form-control input-sm"></textarea>
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
                                                        <textarea id="examination" name="examination" type="text" class="form-control input-sm"></textarea>
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
                                                        <textarea id="diagfinal" name="diagfinal" type="text" class="form-control input-sm"></textarea>
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
                                                        <textarea id="plan_" name="plan_" type="text" class="form-control input-sm"></textarea>
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
                                                        <input id="height" name="height" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                        <span class="input-group-addon">cm</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="weight" style="padding-bottom:5px">Weight</label>
                                                    <div class="input-group">
                                                        <input id="weight" name="weight" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
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
                                                        <input id="bp_sys1" name="bp_sys1" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                        <input id="bp_dias2" name="bp_dias2" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                        <span class="input-group-addon">mmHg</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="pulse" style="padding-bottom:5px">Pulse Rate</label>
                                                    <input id="pulse" name="pulse" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="temperature" style="padding-bottom:5px">Temperature</label>
                                                    <div class="input-group">
                                                        <input id="temperature" name="temperature" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                        <span class="input-group-addon">°C</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="respiration" style="padding-bottom:5px">Respiration</label>
                                                    <input id="respiration" name="respiration" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                                </div>
                                                
                                            </div>
                                        </div>
                                        
                                        <div class="panel panel-info" style="margin-top: 413px;">
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
                                
                                <div class="col-md-12">
                                    <div class="panel panel-info">
                                        <div class="panel-heading text-center">MEDICATION</div>
                                        <div class="panel-body" style="overflow: auto;padding: 0px;" id="jqGrid_trans_doctornote_c">
                                            <table id="jqGrid_trans_doctornote" class="table table-striped"></table>
                                            <div id="jqGrid_trans_doctornotePager"></div>
                                            <!-- <table id="medication_tbl" class="ui selectable celled table" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Items</th>
                                                        <th>Qty</th>
                                                        <th>Remarks</th>
                                                        <th>Dosage</th>
                                                        <th>Frequency</th>
                                                        <th>Instruction</th>
                                                        <th>Indicator</th>
                                                    </tr>
                                                </thead>
                                            </table> -->
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
                        
                    </form>
                </div>
                
			</div>
		</div>
	</div>	
</div>

<div id="dialogForm" title="Referral Letter">
    
    <div class='col-md-6' style="padding-left: 0px; padding-right: 10px;">
        <div class="panel panel-default">
            <div class="panel-heading text-center" style="padding-top: 20px; padding-bottom: 20px;">
                
                <div class="pull-left" style="position: absolute; padding: 0 0 0 0; left: 15px; top: 12px;">
                    <span style="margin-left: 0px;font-size: 100%;">
                        <span id="pt_mrn" name="pt_mrn"></span> - 
                        <span id="pt_name" name="pt_name"></span>
                    </span>
                </div>
                
                <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                    id="btn_grp_edit_refLetter"
                    style="position: absolute;
                            padding: 0 0 0 0;
                            right: 40px;
                            top: 5px;">
                    <button type="button" class="btn btn-default" id="new_refLetter">
                        <span class="fa fa-plus-square-o"></span> New
                    </button>
                    <button type="button" class="btn btn-default" id="edit_refLetter">
                        <span class="fa fa-edit fa-lg"></span> Edit
                    </button>
                    <button type="button" class="btn btn-default" data-oper='add' id="save_refLetter">
                        <span class="fa fa-save fa-lg"></span> Save
                    </button>
                    <button type="button" class="btn btn-default" id="cancel_refLetter">
                        <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
                    </button>
                    <!-- <a class='pull-right pointer text-primary' id='pdfgen1' href="" target="_blank">
                        <input type="button" value="PRINT">
                    </a> -->
                    <button type="button" class="btn btn-default" id="pdfgen1">
                        <span class="fa fa-print fa-lg"> </span> Print
                    </button>
                </div>
                
            </div>
            <div class="panel-body">
                
                <form class='form-horizontal' style='width:99%' id='form_refLetter'>
                    
                    <!-- <input id="idno_refLetter" name="idno_refLetter" type="hidden"> -->
                    <!-- <input id="mrn_refLetter" name="mrn_refLetter" type="hidden"> -->
                    <!-- <input id="episno_refLetter" name="episno_refLetter" type="hidden"> -->
                    
                    <div class="col-md-12" style="padding-left: 0px;">
                        <div class="form-group col-md-5">
                            <label class="control-label" for="refdate" style="font-weight: normal !important; padding-bottom: 3px;">Date</label>
                            <input id="refdate" name="refdate" type="date" class="form-control input-sm" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="padding-left: 0px; padding-bottom: 5px;">
                        <!-- <div class="form-group col-md-5"> -->
                            <label class="control-label" for="refaddress" style="font-weight: normal !important; padding-bottom:3px;">Address</label>
                            <textarea id="refaddress" name="refaddress" rows="3" class="form-control input-sm"></textarea>
                        <!-- </div> -->
                    </div>
                    
                    <div class="col-md-12" style="padding-left: 0px; padding-bottom: 7px;">
                        <!-- <div class="form-inline">
                            Dear Dr. <input id="refdoc" name="refdoc" type="text" class="form-control input-sm">
                        </div> -->
                        <div class="col-md-1" style="padding: 5px 0px"> Dear Dr. </div>
                        <div class="col-md-11" style="padding-right: 0px;">
                            <input id="refdoc" name="refdoc" type="text" class="form-control input-sm" style="text-transform:none">
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="padding-left: 0px; padding-bottom: 7px;">
                        <textarea id="reftitle" name="reftitle" rows="3" class="form-control input-sm"></textarea>
                    </div>
                    
                    <div class="col-md-12" style="padding-left: 0px; padding-bottom: 5px;">
                        <div class="form-group">
                            <div class="col-md-1 control-label" for="refdiag">Diagnosis:</div>  
                            <div class="col-md-11" style="padding-left: 30px;">
                                <textarea id="refdiag" name="refdiag" rows="3" class="form-control input-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="padding-left: 0px; padding-bottom: 5px;">
                        <div class="form-group">
                            <div class="col-md-1 control-label" for="refplan">Plan:</div>  
                            <div class="col-md-11" style="padding-left: 3px;">
                                <textarea id="refplan" name="refplan" rows="3" class="form-control input-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="padding-left: 0px; padding-bottom: 5px;">
                        <div class="form-group">
                            <div class="col-md-1 control-label" for="refprescription">Prescription:</div>  
                            <div class="col-md-11" style="padding-left: 40px;">
                                <textarea id="refprescription" name="refprescription" rows="3" class="form-control input-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <p>If I may be of any further assistance in the care of your patient, please let me know. Thank you for providing me the opportunity to participate in the care of your patients.</p>
                    
                    <p>Sincerely,</p>
                    
                    <div class="form-inline">
                        Dr. <input id="refadduser" name="adduser" type="text" class="form-control input-sm">
                    </div> <br>
                    
                </form>
                
            </div>
        </div>
    </div>
    
    <div class="col-md-6" style="padding-left: 10px; padding-right: 0px;">
        <div class="panel panel-info">
            <!-- <div class="panel-heading text-center">REFERRAL LETTER</div> -->
            <div class="panel-body paneldiv2" style="overflow-y: auto; padding-left: 0px; padding-right: 0px;">
                
                <form class='form-horizontal' style='width:99%' id='form_docNoteRef'>
                    
                    <!-- <input id="idno_docNoteRef" name="idno_docNoteRef" type="hidden"> -->
                    <!-- <input id="mrn_docNoteRef" name="mrn_docNoteRef" type="hidden"> -->
                    <!-- <input id="episno_docNoteRef" name="episno_docNoteRef" type="hidden"> -->
                    <!-- <input id="recorddate_docNoteRef" name="recorddate_docNoteRef" type="hidden"> -->
                    
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="complain_ref">Patient Complaint</label>
                        <div class="col-md-9">
                            <input id="complain_ref" name="complain_ref" type="text" class="form-control input-sm">
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="padding:0px;">
                        <div class='col-md-8'>
                            <div class="panel panel-info">
                                <div class="panel-heading text-center">CLINICAL NOTE</div>
                                <div class="panel-body">
                                    
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label" for="clinicnote_ref" style="padding-bottom:5px">History of Presenting Complaint</label>
                                            <textarea id="clinicnote_ref" name="clinicnote_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label" for="pmh_ref" style="padding-bottom:5px">Past Medical History</label>
                                            <textarea id="pmh_ref" name="pmh_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label" for="drugh_ref" style="padding-bottom:5px">Drug History</label>
                                            <textarea id="drugh_ref" name="drugh_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label" for="allergyh_ref" style="padding-bottom:5px">Allergy History</label>
                                            <textarea id="allergyh_ref" name="allergyh_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label" for="socialh_ref" style="padding-bottom:5px">Social History</label>
                                            <textarea id="socialh_ref" name="socialh_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label class="control-label" for="fmh_ref" style="padding-bottom:5px">Family History</label>
                                            <textarea id="fmh_ref" name="fmh_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12" style="margin-top: 10px">
                                        <div class="panel panel-info">
                                            <div class="panel-heading text-center">FOLLOW UP</div>
                                            <div class="panel-body">
                                                
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="followuptime_ref" style="padding-bottom:5px">Time</label>
                                                    <input id="followuptime_ref" name="followuptime_ref" type="time" class="form-control input-sm">
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label class="control-label" for="followupdate_ref" style="padding-bottom:5px">Date</label>
                                                    <input id="followupdate_ref" name="followupdate_ref" type="date" class="form-control input-sm">
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
                                        <!-- <label class="col-md-3 control-label" for="examination_ref">Physical Examination</label> -->
                                        <div class="col-md-12">
                                            <textarea id="examination_ref" name="examination_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div class="panel panel-info">
                                <div class="panel-heading text-center">Diagnosis</div>
                                <div class="panel-body">
                                    
                                    <div class="form-group">
                                        <!-- <label class="col-md-3 control-label" for="diagfinal_ref">Diagnosis</label> -->
                                        <div class="col-md-12">
                                            <textarea id="diagfinal_ref" name="diagfinal_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="icdcode_ref" style="text-align: left;">Primary ICD</label>
                                <div class="col-md-9">
                                    <div class='input-group'>
                                        <input id="icdcode_ref" name="icdcode_ref" type="text" class="form-control input-sm">
                                        <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
                                    </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            
                            <div class="panel panel-info">
                                <div class="panel-heading text-center">Plan</div>
                                <div class="panel-body">
                                    
                                    <div class="form-group">
                                        <!-- <label class="col-md-3 control-label" for="plan_ref">Plan</label> -->
                                        <div class="col-md-12">
                                            <textarea id="plan_ref" name="plan_ref" type="text" class="form-control input-sm"></textarea>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4" style="padding:0 0 0 0">
                            <div class="panel panel-info">
                                <div class="panel-heading text-center">Vital Sign</div>
                                <div class="panel-body">
                                    
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="height_ref" style="padding-bottom:5px">Height</label>
                                        <div class="input-group">
                                            <input id="height_ref" name="height_ref" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                            <span class="input-group-addon">cm</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="weight_ref" style="padding-bottom:5px">Weight</label>
                                        <div class="input-group">
                                            <input id="weight_ref" name="weight_ref" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                            <span class="input-group-addon">kg</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="bmi_ref" style="padding-bottom:5px">BMI</label>
                                        <input id="bmi_ref" name="bmi_ref" type="number" class="form-control input-sm" rdonly>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="bp_ref" style="padding-bottom:5px">BP</label>
                                        <div class="input-group">
                                            <input id="bp_sys1_ref" name="bp_sys1_ref" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                            <input id="bp_dias2_ref" name="bp_dias2_ref" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                            <span class="input-group-addon">mmHg</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="pulse_ref" style="padding-bottom:5px">Pulse Rate</label>
                                        <input id="pulse_ref" name="pulse_ref" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="temperature_ref" style="padding-bottom:5px">Temperature</label>
                                        <div class="input-group">
                                            <input id="temperature_ref" name="temperature_ref" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                            <span class="input-group-addon">°C</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="respiration_ref" style="padding-bottom:5px">Respiration</label>
                                        <input id="respiration_ref" name="respiration_ref" type="number" class="form-control input-sm floatNumberField" onkeydown="return event.keyCode !== 69" onKeyPress="if(this.value.length==6) return false;">
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div class="panel panel-info" style="margin-top: 550px;">
                                <div class="panel-body">
                                    
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="adduser_ref" style="padding-bottom:5px">Added by</label>  
                                        <input id="adduser_ref" name="adduser_ref" type="text" class="form-control input-sm" rdonly>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label class="control-label" for="adddate_ref" style="padding-bottom:5px">Date</label>
                                        <input id="adddate_ref" name="adddate_ref" type="text" class="form-control input-sm" rdonly>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="panel panel-info">
                            <div class="panel-heading text-center">MEDICATION</div>
                            <div class="panel-body" style="overflow: auto;padding: 0px;" id="jqGrid_trans_doctornote_ref_c">
                                <table id="jqGrid_trans_doctornote_ref" class="table table-striped"></table>
                                <div id="jqGrid_trans_doctornote_refPager"></div>
                                <!-- <table id="medication_tbl" class="ui selectable celled table" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Items</th>
                                            <th>Qty</th>
                                            <th>Remarks</th>
                                            <th>Dosage</th>
                                            <th>Frequency</th>
                                            <th>Instruction</th>
                                            <th>Indicator</th>
                                        </tr>
                                    </thead>
                                </table> -->
                            </div>
                        </div>
                    </div>
                    
                </form>
                
            </div>
        </div>
    </div>
    
    <!-- <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-body">
            
                <textarea id="letter" name="letter" rows="30" cols="90" class="form-control input-sm">
                
                Dear Dr. 

                Diagnosis: 

                Plan: 

                Prescription: 

                If I may be of any further assistance in the care of your patient, please let me know. Thank you for providing me the opportunity to participate in the care of your patients.

                Sincerely,

                Dr. 

                </textarea>
                
            </div>
        </div>
    </div> -->
    
</div>


<div id="dialog_medc" title="Medical Certificate">
    <div class="row">
        <div class="col-md-12" style="padding: 10px 0px;">
            <div class="btn-group" role="group" aria-label="..." style="float:right;">
              <button type="button" class="btn btn-default" id="btn_epno_mclt">MC List</button>
              <button type="button" class="btn btn-default" id="btn_epno_gomc">MC</button>
              <button type="button" class="btn btn-default" id="btn_epno_save">Save</button>
              <button type="button" class="btn btn-default" id="btn_epno_canl">Cancel</button>
            </div>
        </div>
        <div class="col-md-12" style="padding: 20px;
            background: #e5f9ff;
            border: 0.1em solid #d6d6ff;
            border-radius: 4px;">

            <form id="form_medc" style="padding: 10px" autocomplete="off" class="col-md-6 col-md-offset-3" >
                    <p style="padding-left: 50px;">Serial No: <input type="text" name="serialno" readonly class="form-control" style="width: 70px !important;"></p>
                    <p>I hereby certify that i have examined</p>
                    <p>Mr/Miss/Mrs : <input type="text" name="name" readonly class="form-control" style="width: 500px !important;"></p>
                    <p style="padding-left: 38px;">From : <input type="text" name="patfrom" style="width: 500px !important;" class="form-control"></p>
                    <p>And find that he/she will be unfit for duty for <input type="text" name="mccnt" class="form-control" style="width: 50px !important;" required> days</p>
                    <p style="padding-left: 50px;">day from 
                        <input type="date" name="datefrom" class="form-control" required> to 
                        <input type="date" name="dateto" class="form-control" required>
                    </p>
                    <p>Boleh bertugas semula pada / Can resume his/her duty on 
                        <input type="date" name="dateresume" class="form-control" required>
                    </p>
                    <p>Dikehendaki datang semula pada /</p>
                    <p>Is required to come for re-examination on <input type="date" name="datereexam" class="form-control"></p>
            </form>

            <div id="mclist_medc" style="display:none">
                <table id="mclist_table">
                    <thead>
                        <tr>
                            <td>Id</td>
                            <td>Date from</td>
                            <td>Date to</td>
                            <td>MRN</td>
                            <td>Episode</td>
                            <td>Added By</td>
                            <td>Added Date</td>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<div id="dialog_bpgraph" title="BP Graph">
    <div class="container_bpgraph">
        <div id="placeholder_bpgraph" class="placeholder_bpgraph" style="height: 60%"></div>
        <div id="placeholder2_bpgraph" class="placeholder_bpgraph" style="height: 40%; margin-top: -34px"></div>
    </div>
</div>
<div class="panel panel-default" style="position: relative;" id="jqGridClientProgNote_c">
	<input type="hidden" name="curr_user" id="curr_user_clientProgNote" value="{{ Auth::user()->username }}">
	
	<div class="panel-heading clearfix collapsed position" id="toggle_clientProgNote" style="position: sticky; top: 0px; z-index: 3;">
		<b>NAME: <span id="name_show_clientProgNote"></span></b><br>
		MRN: <span id="mrn_show_clientProgNote"></span>
		SEX: <span id="sex_show_clientProgNote"></span>
		DOB: <span id="dob_show_clientProgNote"></span>
		AGE: <span id="age_show_clientProgNote"></span>
		RACE: <span id="race_show_clientProgNote"></span>
		RELIGION: <span id="religion_show_clientProgNote"></span><br>
		OCCUPATION: <span id="occupation_show_clientProgNote"></span>
		CITIZENSHIP: <span id="citizenship_show_clientProgNote"></span>
		AREA: <span id="area_show_clientProgNote"></span>
		
		<i class="arrow fa fa-angle-double-up" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridClientProgNote_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridClientProgNote_panel"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 310px; top: 25px;">
			<h5>Doctor Note</h5>
		</div>
		
		<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
			id="btn_grp_edit_clientProgNote" 
			style="position: absolute; 
					padding: 0 0 0 0; 
					right: 40px; 
					top: 25px;">
			<button type="button" class="btn btn-default" id="new_clientProgNote">
				<span class="fa fa-plus-square-o"></span> New 
			</button>
			<button type="button" class="btn btn-default" id="edit_clientProgNote">
				<span class="fa fa-edit fa-lg"></span> Edit 
			</button>
			<button type="button" class="btn btn-default" data-oper='add' id="save_clientProgNote">
				<span class="fa fa-save fa-lg"></span> Save 
			</button>
			<button type="button" class="btn btn-default" id="cancel_clientProgNote">
				<span class="fa fa-ban fa-lg" aria-hidden="true"></span> Cancel 
			</button>
		</div>
	</div>
	
	<div id="jqGridClientProgNote_panel" class="panel-collapse collapse">
		<div class="panel-body paneldiv" style="overflow-y: auto;">
			<div class='col-md-12' style="padding: 0 0 15px 0;">
				<div class="col-md-3" style="padding-left: 0px;">
					<!-- table doctornote_date -->
					<div id="clientprognote_date_tbl_sticky" style="padding: 0 0 0 0;">
						<div class="panel panel-info" style="margin-top: 10px;">
							<div class="panel-body">
								<table id="clientprognote_date_tbl" class="ui celled table" style="width: 100%;">
									<thead>
										<tr>
											<th class="scope">mrn</th>
											<th class="scope">episno</th>
											<th class="scope">Date/Time</th>
											<th class="scope">recdatetime</th>
											<th class="scope">adduser</th>
											<th class="scope">Doctor</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-md-9" style="padding: 0 0 0 5px; float: right;">
					<form class='form-horizontal' style='width: 99%;' id='formClientProgNote'>
						<input id="mrn_clientProgNote" name="mrn_clientProgNote" type="hidden">
						<input id="episno_clientProgNote" name="episno_clientProgNote" type="hidden">
						<input id="age_clientProgNote" name="age_clientProgNote" type="hidden">
						<input id="datetime_clientProgNote" name="datetime_clientProgNote" type="hidden">
						<input id="ptname_clientProgNote" name="ptname_clientProgNote" type="hidden">
						<input id="preg_clientProgNote" name="preg_clientProgNote" type="hidden">
						<input id="ic_clientProgNote" name="ic_clientProgNote" type="hidden">
						<input id="doctorname_clientProgNote" name="doctorname_clientProgNote" type="hidden">
						<input id="epistycode_clientProgNote" name="epistycode_clientProgNote" type="hidden" value="{{request()->get('epistycode')}}">
						
						<div class="panel panel-info">
							<div class="panel-body">
								<div class="form-inline col-md-12" style="padding-bottom: 15px;">
									<label class="control-label" for="datetaken" style="padding-right: 5px;">Date</label>
									<input id="clientProgNote_datetaken" name="datetaken" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information." value="<?php echo date("Y-m-d"); ?>">
									
									<label class="control-label" for="timetaken" style="padding-left: 15px; padding-right: 5px;">Time</label>
									<input id="clientProgNote_timetaken" name="timetaken" type="time" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information.">
									
									@if(Auth::user()->doctor == '1')
                                        <button class="btn btn-default btn-sm" type="button" id="referLetterClientProgNote" style="float: right; margin-right: 40px;">Referral Letter</button>
                                    @endif
								</div>
								
								<div class='col-md-12'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">CLIENT'S PROGRESS NOTES</div>
										<div class="panel-body">
											<textarea id="clientProgNote_progressnote" name="progressnote" type="text" class="form-control input-sm"></textarea>
										</div>
									</div>
								</div>
								
								@if(request()->get('epistycode') == 'OP')
									@include('hisdb.clientprogressnote.patprescription')
								@endif
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- referral letter -->
<div id="dialogFormRefClientProgNote" title="Referral Letter">
    <div class='col-md-6' style="padding-left: 0px; padding-right: 10px;">
        <div class="panel panel-default">
            <div class="panel-heading text-center" style="padding-top: 20px; padding-bottom: 20px;">
                <div class="pull-left" style="position: absolute; padding: 0 0 0 0; left: 15px; top: 12px;">
                    <span style="margin-left: 0px; font-size: 100%;">
                        <span id="pt_mrnClientProgNote" name="pt_mrnClientProgNote"></span> - 
                        <span id="pt_nameClientProgNote" name="pt_nameClientProgNote"></span>
                    </span>
                </div>
                
                <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                    id="btn_grp_edit_refLetterclientProgNote" 
                    style="position: absolute; 
                            padding: 0 0 0 0; 
                            right: 40px; 
                            top: 5px;">
                    <button type="button" class="btn btn-default" id="new_refLetterClientProgNote">
                        <span class="fa fa-plus-square-o"></span> New 
                    </button>
                    <button type="button" class="btn btn-default" id="edit_refLetterClientProgNote">
                        <span class="fa fa-edit fa-lg"></span> Edit 
                    </button>
                    <button type="button" class="btn btn-default" data-oper='add' id="save_refLetterClientProgNote">
                        <span class="fa fa-save fa-lg"></span> Save 
                    </button>
                    <button type="button" class="btn btn-default" id="cancel_refLetterClientProgNote">
                        <span class="fa fa-ban fa-lg" aria-hidden="true"></span> Cancel 
                    </button>
                    <!-- <a class='pull-right pointer text-primary' id='pdfgen1' href="" target="_blank">
                        <input type="button" value="PRINT">
                    </a> -->
                    <button type="button" class="btn btn-default" id="refLetterClientProgNote_chart">
                        <span class="fa fa-print fa-lg"></span> Print 
                    </button>
                </div>
            </div>
            
            <div class="panel-body">
                <form class='form-horizontal' style='width: 99%;' id='form_refLetterClientProgNote'>
                    <input id="reftypeClientProgNote" name="reftype" type="hidden" value="ClientProgNote">
                    <!-- <input id="idno_refLetterClientProgNote" name="idno_refLetterClientProgNote" type="hidden"> -->
                    <!-- <input id="mrn_refLetterClientProgNote" name="mrn_refLetterClientProgNote" type="hidden"> -->
                    <!-- <input id="episno_refLetterClientProgNote" name="episno_refLetterClientProgNote" type="hidden"> -->
                    
                    <div class="col-md-12" style="padding-left: 0px;">
                        <div class="form-group col-md-5">
                            <label class="control-label" for="refdate" style="font-weight: normal !important; padding-bottom: 3px;">Date</label>
                            <input id="refdateClientProgNote" name="refdate" type="date" class="form-control input-sm" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="padding-left: 0px; padding-bottom: 5px;">
                        <!-- <div class="form-group col-md-5"> -->
                            <label class="control-label" for="refaddress" style="font-weight: normal !important; padding-bottom:3px;">Address</label>
                            <textarea id="refaddressClientProgNote" name="refaddress" rows="3" class="form-control input-sm"></textarea>
                        <!-- </div> -->
                    </div>
                    
                    <div class="col-md-12" style="padding-left: 0px; padding-bottom: 7px;">
                        <!-- <div class="form-inline">
                            Dear Dr. <input id="refdoc" name="refdoc" type="text" class="form-control input-sm">
                        </div> -->
                        <div class="col-md-1" style="padding: 5px 0px;"> Dear Dr. </div>
                        <div class="col-md-11" style="padding-right: 0px;">
                            <input id="refdocClientProgNote" name="refdoc" type="text" class="form-control input-sm" style="text-transform: none;">
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="padding-left: 0px; padding-bottom: 7px;">
                        <textarea id="reftitleClientProgNote" name="reftitle" rows="3" class="form-control input-sm"></textarea>
                    </div>
                    
                    <div class="col-md-12" style="padding-left: 0px; padding-bottom: 5px;">
                        <div class="form-group">
                            <div class="col-md-1 control-label" for="refdiag">Diagnosis:</div>  
                            <div class="col-md-11" style="padding-left: 30px;">
                                <textarea id="refdiagClientProgNote" name="refdiag" rows="3" class="form-control input-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="padding-left: 0px; padding-bottom: 5px;">
                        <div class="form-group">
                            <div class="col-md-1 control-label" for="refplan">Plan:</div>  
                            <div class="col-md-11" style="padding-left: 3px;">
                                <textarea id="refplanClientProgNote" name="refplan" rows="3" class="form-control input-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="padding-left: 0px; padding-bottom: 5px;">
                        <div class="form-group">
                            <div class="col-md-1 control-label" for="refprescription">Prescription:</div>  
                            <div class="col-md-11" style="padding-left: 40px;">
                                <textarea id="refprescriptionClientProgNote" name="refprescription" rows="3" class="form-control input-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <p>If I may be of any further assistance in the care of your patient, please let me know. Thank you for providing me the opportunity to participate in the care of your patients.</p>
                    
                    <p>Sincerely,</p>
                    
                    <div class="form-inline">
                        Dr. <input id="refadduserClientProgNote" name="adduser" type="text" class="form-control input-sm">
                    </div> <br>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6" style="padding-left: 10px; padding-right: 0px;">
        <div class="panel panel-info">
            <div class="panel-body paneldiv2" style="overflow-y: auto; padding-left: 0px; padding-right: 0px;">
                <form class='form-horizontal' style='width: 99%;' id='form_docNoteRefClientProgNote'>
                    <!-- <input id="idno_docNoteRef" name="idno_docNoteRef" type="hidden"> -->
                    <!-- <input id="mrn_docNoteRef" name="mrn_docNoteRef" type="hidden"> -->
                    <!-- <input id="episno_docNoteRef" name="episno_docNoteRef" type="hidden"> -->
                    <!-- <input id="recorddate_docNoteRef" name="recorddate_docNoteRef" type="hidden"> -->

                    <div class='col-md-12'>
                        <div class="panel panel-info">
                            <div class="panel-heading text-center">CLIENT'S PROGRESS NOTES</div>
                            <div class="panel-body">
                                <textarea id="clientProgNote_progressnoteRef" name="progressnote" type="text" class="form-control input-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    @if(request()->get('epistycode') == 'OP')
                        @include('hisdb.clientprogressnote.patprescription')
                    @endif
                </form>
            </div>
        </div>
    </div>
    
</div>
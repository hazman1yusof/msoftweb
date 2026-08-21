
<div class="panel panel-default" style="position: relative;" id="jqGridClientProgNoteRef_c">
    <input type="hidden" name="curr_user" id="curr_user_clientProgNoteRef" value="{{ Auth::user()->username }}">
    
    <div class="panel-heading clearfix collapsed position" id="toggle_clientProgNoteRef" style="position: sticky; top: 0px; z-index: 3;">
		<b>NAME: <span id="name_show_clientProgNoteRef"></span></b><br>
		MRN: <span id="mrn_show_clientProgNoteRef"></span>
		SEX: <span id="sex_show_clientProgNoteRef"></span>
		DOB: <span id="dob_show_clientProgNoteRef"></span>
		AGE: <span id="age_show_clientProgNoteRef"></span>
		RACE: <span id="race_show_clientProgNoteRef"></span>
		RELIGION: <span id="religion_show_clientProgNoteRef"></span><br>
		OCCUPATION: <span id="occupation_show_clientProgNoteRef"></span>
		CITIZENSHIP: <span id="citizenship_show_clientProgNoteRef"></span>
		AREA: <span id="area_show_clientProgNoteRef"></span>
		
		<i class="arrow fa fa-angle-double-up" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridClientProgNoteRef_panel"></i>
		<i class="arrow fa fa-angle-double-down" style="font-size: 24px; margin: 0 0 0 12px;" data-toggle="collapse" data-target="#jqGridClientProgNoteRef_panel"></i>
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 310px; top: 25px;">
			<h5>Doctor Note (Referral)</h5>
		</div>
		
		<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
			id="btn_grp_edit_clientProgNoteRef" 
			style="position: absolute; 
					padding: 0 0 0 0; 
					right: 40px; 
					top: 25px;">
			<button type="button" class="btn btn-default" id="new_clientProgNoteRef">
				<span class="fa fa-plus-square-o"></span> New 
			</button>
			<button type="button" class="btn btn-default" id="edit_clientProgNoteRef">
				<span class="fa fa-edit fa-lg"></span> Edit 
			</button>
			<button type="button" class="btn btn-default" data-oper='add' id="save_clientProgNoteRef">
				<span class="fa fa-save fa-lg"></span> Save 
			</button>
			<button type="button" class="btn btn-default" id="cancel_clientProgNoteRef">
				<span class="fa fa-ban fa-lg" aria-hidden="true"></span> Cancel 
			</button>
		</div>
	</div>
	
	<div id="jqGridClientProgNoteRef_panel" class="panel-collapse collapse">
		<div class="panel-body paneldiv" style="overflow-y: auto;">
			<div class='col-md-12' style="padding: 0 0 15px 0;">
				<div class="col-md-3" style="padding-left: 0px;">
					<!-- table referral_doctor -->
					<div id="docalloc_tbl_sticky" style="padding: 0 0 0 0;">
						<div class="panel panel-info" style="margin-top: 10px;">
							<div class="panel-body">
								<table id="docalloc_tbl" class="ui celled table" style="width: 100%;">
									<thead>
										<tr>
											<th class="scope">mrn</th>
											<th class="scope">episno</th>
											<th class="scope">AllocNo</th>
											<th class="scope">Doctor</th>
											<th class="scope">DoctorCode</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
					
					<!-- table doctornote_date -->
					<div id="clientprognoteref_date_tbl_sticky" style="padding: 0 0 0 0;">
						<div class="panel panel-info" style="margin-top: 10px;">
							<div class="panel-body">
								<table id="clientprognoteref_date_tbl" class="ui celled table" style="width: 100%;">
									<thead>
										<tr>
											<th class="scope">mrn</th>
											<th class="scope">episno</th>
											<th class="scope">Date/Time</th>
											<th class="scope">recdatetime</th>
											<th class="scope">adduser</th>
											<th class="scope">Doctor</th>
											<th class="scope">doctorcode</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-md-9" style="padding: 0 0 0 5px; float: right;">
					<form class='form-horizontal' style='width: 99%;' id='formClientProgNoteRef'>
						<input id="mrn_clientProgNoteRef" name="mrn_clientProgNoteRef" type="hidden">
						<input id="episno_clientProgNoteRef" name="episno_clientProgNoteRef" type="hidden">
						<input id="age_clientProgNoteRef" name="age_clientProgNoteRef" type="hidden">
						<input id="datetime_clientProgNoteRef" name="datetime_clientProgNoteRef" type="hidden">
						<input id="ptname_clientProgNoteRef" name="ptname_clientProgNoteRef" type="hidden">
						<input id="preg_clientProgNoteRef" name="preg_clientProgNoteRef" type="hidden">
						<input id="ic_clientProgNoteRef" name="ic_clientProgNoteRef" type="hidden">
						<input id="doctorname_clientProgNoteRef" name="doctorname_clientProgNoteRef" type="hidden">
						<input id="refdoctor_clientProgNoteRef" name="refdoctor_clientProgNoteRef" type="hidden">
						<input id="epistycode_clientProgNoteRef" name="epistycode_clientProgNoteRef" type="hidden" value="{{request()->get('epistycode')}}">
						
						<div class="panel panel-info">
							<div class="panel-body">
								<div class="form-inline col-md-12" style="padding-bottom: 15px;">
									<label class="control-label" for="datetaken" style="padding-right: 5px;">Date</label>
									<input id="clientProgNoteRef_datetaken" name="datetaken" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information." value="<?php echo date("Y-m-d"); ?>">
									
									<label class="control-label" for="timetaken" style="padding-left: 15px; padding-right: 5px;">Time</label>
									<input id="clientProgNoteRef_timetaken" name="timetaken" type="time" class="form-control input-sm" data-validation="required" data-validation-error-msg-required="Please enter information.">
								</div>
								
								<div class='col-md-12'>
									<div class="panel panel-info">
										<div class="panel-heading text-center">CLIENT'S PROGRESS NOTES</div>
										<div class="panel-body">
											<textarea id="clientProgNoteRef_progressnote" name="progressnote" type="text" class="form-control input-sm"></textarea>
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
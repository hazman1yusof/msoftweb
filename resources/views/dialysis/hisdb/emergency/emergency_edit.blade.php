
<div class="panel panel-default" style="position: relative;">
	<div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
		id="btn_grp_edit"
		style="position: absolute;
			    padding: 0 0 0 0;
			    right: 40px;
			    top: 25px;" 

	>
		<button type="button" class="btn btn-default" id="edit_rfde">
			<span class="fa fa-edit fa-lg"></span> Edit
		</button>
		<button type="button" class="btn btn-default" id="save_rfde">
			<span class="fa fa-save fa-lg"></span> Save
		</button>
	    <button type="button" class="btn btn-default" id="cancel_rfde" >
	    	<span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
	    </button>
	</div>

	<div class="panel-heading clearfix collapsed" id="toggle_rfde" data-toggle="collapse" data-target="#emergency_edit">
		<b>NAME: <span id="name_show"></span></b><br>
		MRN: <span id="mrn_show"></span>
		SEX: <span id="sex_show"></span>
		DOB: <span id="dob_show"></span>
		AGE: <span id="age_show"></span>
		RACE: <span id="race_show"></span>
		RELIGION: <span id="religion_show"></span><br>
		OCCUPATION: <span id="occupation_show"></span>
		CITIZENSHIP: <span id="citizenship_show"></span>
		AREA: <span id="area_show"></span>

		<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
		<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
		<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 250px; top: 25px;">
			<h5>Episode</h5>
		</div>
 
	</div>
	<div id="emergency_edit" class="panel-collapse collapse">
		<div class="panel-body">

			<div id="registerform_edit" title="Register Form">
				<form class='form-horizontal' style='width:99%' id='registerformdata_edit'>
					<input id="code_edit" name="code_edit" type="hidden" value="{{Session::get('code')}}">
					<input id="episno_edit" name="episno_edit" type="hidden" value="">
					<input id="apptbookidno_edit" name="apptbookidno_edit" type="hidden" value="">
					{{ csrf_field() }}
					<div class="form-group">
					<label for="title" class="col-md-2 control-label">MRN</label>
				        <div class="col-md-2">
							<div class="input-group">
								<input type="text" class="form-control input-sm" placeholder="MRN No" id="mrn_edit" name="mrn_edit" maxlength="12" rdonly >
								<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class='help-block'></span>
						</div>
			             <div class="col-md-4">
							<input type="text" class="form-control input-sm text-uppercase" data-validation="required" placeholder="Name" id="patname_edit" name="patname_edit">
						</div>
					</div>	
			        <div class="form-group">
			        	<label class="col-md-2 control-label" for="idtype_edit">ID Type</label>
						<div class="col-md-2">
								<select id='idtype_edit' name='idtype_edit' class="form-control input-sm">
									<option value="none" selected>None</option>
									<option value="Father">Father</option>
									<option value="Mother">Mother</option>
									<option value="Relative">Relative</option>
									<option value="Passport">Passport</option>
									<option value="Police">Police</option>
									<option value="Army">Army</option>
							    </select>
						</div>

					    <label class="col-md-1 control-label" for="Newic_edit">New I.C</label>
						<div class="col-md-2">
							<input type="text" name="Newic_edit" id="Newic_edit" class="form-control input-sm" maxlength="14" data-validation-optional-if-answered="Oldic" >
						</div>	

					   	<label class="col-md-1 control-label" for="Oldic_edit">Old I.C</label>
						<div class="col-md-2">
							<input type="text" name="Oldic_edit" id="Oldic_edit" class="form-control input-sm" maxlength="7" data-validation-optional-if-answered="Newic">
						</div>	

			        </div>
			        <div class="form-group">
			        	<label class="col-md-2 control-label" for="DOB_edit">D.O.B</label>
						<div class="col-md-2">
							<input type="date" name="DOB_edit" id="DOB_edit" class="form-control input-sm">
						</div>

			            <label class="col-md-1 control-label" for="idnumber_edit">Others No</label>
						<div class="col-md-2">
							<input type="text" name="idnumber_edit" id="idnumber_edit" class="form-control input-sm" >
						</div>

			          	<label class="col-md-1 control-label" for="sex_edit">Sex</label>
						<div class="col-md-2">
							<select id='sex_edit' name='sex_edit' class="form-control input-sm" data-validation="required">
							 <option value="" selected>Please Choose</option>
				      		 <option value="M">Male</option>
					         <option value="F">Female</option>
					         <option value="U">Unisex</option>
						    </select>
						</div>
					</div>

			        <div class="form-group">
						<label for="title" class="col-md-2 control-label">Race</label>
				        <div class="col-md-2">
							<div class="input-group">
							<input type="text" class="form-control input-sm" data-validation="required" placeholder="Race" id="race_edit" name="race_edit" maxlength="12">
							<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class='help-block'></span>
						</div>

			            <div class="col-md-4">
							<input type="text" class="form-control input-sm" data-validation="required" placeholder="" id="description_race_edit" name="description_race_edit" rdonly>
						</div>
					</div>

					<hr>

					<div class="form-group">

						<label for="title" class="col-md-2 control-label">Financial Class</label>
				        <div class="col-md-2">
							<div class="input-group">
								<input type="text" class="form-control input-sm" data-validation="required" placeholder="Finanncial Class" id="financeclass_edit" name="financeclass_edit" maxlength="12" rdonly>
								<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class='help-block'></span>
						</div>

			             <div class="col-md-4">
							<input type="text" class="form-control input-sm text-uppercase" data-validation="required" placeholder="" id="fName_edit" name="fName_edit">
						</div>

					</div>

					<div class="form-group">
						<label for="title" class="col-md-2 control-label">Payer</label>
					    <div class="col-md-2">
							<div class="input-group">
								<input type="text" class="form-control input-sm" data-validation="required" placeholder="Payer" id="payer_edit" name="payer_edit" maxlength="12">
								<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class='help-block'></span>
						</div>
			            <div class="col-md-4">
							<input type="text" class="form-control input-sm text-uppercase" data-validation="required" placeholder="" id="payername_edit" name="payername_edit">
							<input type="hidden" name="paytype" id="paytype">
						</div>
					</div>

					<div class="form-group">
						<label for="title" class="col-md-2 control-label">Bill Type</label>
				        <div class="col-md-2">
							<div class="input-group">
								<input type="text" class="form-control input-sm" data-validation="required" placeholder="BillType" id="billtype_edit" name="billtype_edit" maxlength="12">
								<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class='help-block'></span>
						</div>
			            <div class="col-md-4">
							<input type="text" class="form-control input-sm text-uppercase" data-validation="required" placeholder="" id="description_bt_edit" name="description_bt_edit">
						</div>
					</div>

					<div class="form-group">
						<label for="title_edit" class="col-md-2 control-label">Doctor</label>
				        <div class="col-md-2">
							<div class="input-group">
								<input type="text" class="form-control input-sm" placeholder="Doctor" data-validation="required" id="doctor_edit" name="doctor_edit" maxlength="12">
								<a class="input-group-addon btn btn-primary"><span class='fa fa-ellipsis-h'></span></a>
							</div>
							<span class='help-block'></span>
						</div>
			            <div class="col-md-4">
							<input type="text" class="form-control input-sm text-uppercase"  placeholder="Doctor Name" id="docname_edit" name="docname_edit" data-validation="required">
						</div>
					</div>

				</form>		
			</div>

		</div>
	</div>	
</div>



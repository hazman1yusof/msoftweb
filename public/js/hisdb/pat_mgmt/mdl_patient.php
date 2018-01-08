<!-- Large modal -->
<div id="mdl_patient_info" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <form id="frm_patient_info" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header label-warning">
                    <!--                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>-->
                    <div class="form-group has-error">
                        <div class="col-sm-3">
                            <small  for="mrn">PATIENT REGISTRATION (MRN)</small>
                            <input class="form-control " name="MRN" id="mrn" placeholder="" type="text" readonly>
                            <!--                                    <small class="help-block text-center">REGISTRATION MRN</small>-->
                        </div>
<!--                        <div class="col-sm-3">-->
<!--                            <small for="oldmrn">HUKM MRN</small>-->
<!--                            <input class="form-control" id="oldmrn" placeholder="" type="text"  >-->
<!--                        </div>-->
                        <div class="col-sm-3">
                            <small for="first_visit_date">FIRST VISIT</small>
                            <input class="form-control" name="first_visit_date" id="first_visit_date" placeholder="" type="text" readonly>
                        </div>
                        <div class="col-sm-3">
                            <small for="last_visit_date">LAST VISIT</small>
                            <input class="form-control" name="last_visit_date" id="last_visit_date" placeholder="" type="text" readonly>
                        </div>
                        <div class="col-sm-3">
                            <small for="episno">EPISODE NO</small>
                            <input class="form-control" name="episno" id="episno" placeholder="" type="text" readonly></div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="tabs-left" role="tabpanel">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist" id="tab_patient_info">
                                    <li role="presentation" class="active"><a href="#tab9" role="tab" data-toggle="tab" aria-expanded="true">Patient Info</a></li>
                                    <li role="presentation" class=""><a href="#tab10" role="tab" data-toggle="tab" aria-expanded="false">Corporate Info</a></li>
                                    <!--li role="presentation" class=""><a href="#tab11" role="tab" data-toggle="tab" aria-expanded="false">Address Info</a></li-->
                                    <li role="presentation" class=""><a href="#tab12" role="tab" data-toggle="tab" aria-expanded="false">Medical Info</a></li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade active in" id="tab9">
                                        <!-- Tab content begin -->
										<div class="form-group">
											<div class="col-md-2">
												<img src="../../../../assets/img/defaultprofile.png" width="120" height="140" />
											</div>
											<div class="col-md-10">
												<div class="row"><br /></div>
												<div class="row">
													<div class="col-md-3">
														<small for="titlecode">Title</small>
														<!--select id="titlecode" name="titlecode" class="form-control has-error">
															<option value="">- Select Title -</option>
														</select-->
														<div class="input-group">
															<input type="text" class="form-control" name="txt_pat_title" id="txt_pat_title">
															<input type="hidden" name="TitleCode" id="hid_pat_title" value="" />
															<span class="input-group-btn">
																<button type="button" class="btn btn-warning" id="btn_pat_title" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('pat_title');"><span class="fa fa-ellipsis-h"></span></button>
															</span>
														</div>
													</div>
													<div class="col-md-6">
														<small for="txt_pat_name">Name</small>
														<input class="form-control has-error form-mandatory" name="Name" id="txt_pat_name" placeholder="" type="text" required>
													</div>
													<div class="col-md-3">
														<small for="cmb_pat_sex">Sex</small>
														<select id="cmb_pat_sex" name="Sex" class="form-control form-mandatory">
															<option value="">- Select Sex -</option>
														</select>
													</div>
												</div>
												<div class="row">
													<div class="col-md-3">
														<small for="cmb_pat_idtype">IC Type</small>
														<select id="cmb_pat_idtype" name="ID_Type" class="form-control">
															<option value="">- Select IC Type -</option>
														</select>
													</div>
													<div class="col-md-3">
														<small for="txt_pat_newic">New IC (eg 690101086649)</small>
														<input class="form-control form-mandatory" name="Newic" id="txt_pat_newic" placeholder="" type="text">
													</div>
													<div class="col-md-3">
														<small for="txt_pat_oldic">Old IC</small>
														<input class="form-control form-mandatory" name="Oldic" id="txt_pat_oldic" placeholder="" type="text">
													</div>
													<div class="col-md-3">
														<small for="txt_pat_idno">Other (eg Passport Number)</small>
														<input class="form-control form-mandatory" name="idnumber" id="txt_pat_idno" placeholder="" type="text">
													</div>												
													
												</div>
											</div>
										</div>
                                        <div class="form-group">
                                            <div class="col-md-4">
												<small for="txt_pat_dob">DOB - Age</small>
												<div class="input-group">
													<input class="form-control form-mandatory" style="width:180px;" name="DOB" id="txt_pat_dob" placeholder="" type="date">
													<span class="input-group-addon" style="background-color:transparent; border-color: transparent" style="width:10px;">&mdash;</span>														
													<input class="form-control" style="width:40px;" name="txt_pat_age" id="txt_pat_age" placeholder="" type="text" disabled>
												</div>
											</div>											
											<div class="col-md-2">
                                                <small for="cmb_pat_racecode">Race</small>
                                                <select id="cmb_pat_racecode" name="RaceCode" class="form-control form-mandatory">
                                                    <option value="">- Select Race -</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <small for="cmb_pat_religion">Religion</small>
                                                <select id="cmb_pat_religion" name="Religion" class="form-control form-mandatory">
                                                    <option value="">- Select Religion -</option>
                                                </select>
                                            </div>											
											<div class="col-md-3">
                                                <small for="cmb_pat_langcode">Language</small>
                                                <select id="cmb_pat_langcode" name="LanguageCode" class="form-control">
                                                    <option value="">- Select Language -</option>
                                                </select>
                                            </div>
											
											<!--div class="col-md-5">
												<small for="txt_pat_occupation">Occupation</small>
												
												<div class="input-group">
													<input type="text" class="form-control form-mandatory" name="txt_pat_occupation" id="txt_pat_occupation">
													<input type="hidden" name="hid_pat_occupation" id="hid_pat_occupation" value="" />
													<span class="input-group-btn">
														<button type="button" class="btn btn-warning" id="btn_pat_occupation" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('pat_occupation');"><span class="fa fa-ellipsis-h"></span> </button>
													</span>
												</div>
											</div-->
                                        </div>
										
										<div class="form-group">                                            
                                             <div class="col-md-4">
                                                <small for="txt_pat_citizen">Citizenship</small>
                                                <!--select id="citizencode" name="citizencode" class="form-control">
                                                    <option value="">- Select Citizen -</option>
                                                </select-->
												<div class="input-group">
                                                    <input type="text" class="form-control form-mandatory" name="txt_pat_citizen" id="txt_pat_citizen">
                                                    <input type="hidden" name="Citizencode" id="hid_pat_citizen" value="" />
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-warning" id="btn_pat_citizen" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('pat_citizen');"><span class="fa fa-ellipsis-h"></span> </button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <small for="txt_pat_area">Area</small>
                                                <!--select id="areacode" name="input-area" class="form-control">
                                                    <option value="">- Select Area -</option>
                                                </select-->
												<div class="input-group">
                                                    <input type="text" class="form-control form-mandatory" name="txt_pat_area" id="txt_pat_area">
                                                    <input type="hidden" name="AreaCode" id="hid_pat_area" value="" />
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-warning" id="btn_pat_area" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('pat_area');"><span class="fa fa-ellipsis-h"></span> </button>
                                                    </span>
                                                </div>
                                            </div>
											<div class="col-md-4">
                                                <small for="txt_pat_email">Email</small>
                                                <input class="form-control" name="Email_official" id="txt_pat_email" placeholder="" type="email">
                                            </div>
										</div>						
										
                                        <div class="form-group">
											<!--div class="row"-->
												<div class="col-md-12">
													<br />
													<p><strong>ADDRESS</strong></p>														
													<div class="tab-v2">
														<ul class="nav nav-tabs">
															<li class="active"><a href="#addr_current" data-toggle="tab">Current</a></li>
															<li><a href="#addr_office" data-toggle="tab">Office</a></li>
															<li><a href="#addr_home" data-toggle="tab">Home</a></li>
														</ul>
														<div class="tab-content">
															<div class="tab-pane fade in active" id="addr_current">
																<!--div class="row"-->
																	<br />
																	<div class="col-md-4">
																		<p>Current Address</p>
																	</div>
																	<div class="col-md-8">
																		<p><input name="Address1" id="txt_pat_curradd1" class="form-control form-mandatory" type="text" required /></p>
																	</div>
																	<div class="col-md-4">
																		<p></p>
																	</div>
																	<div class="col-md-8">
																		<p><input name="Address2" id="txt_pat_curradd2" class="form-control form-mandatory" type="text" /></p>
																	</div>
																	<div class="col-md-4">
																		<p></p>
																	</div>
																	<div class="col-md-8">
																		<p><input name="Address3" id="txt_pat_curradd3" class="form-control form-mandatory" type="text" /></p>
																	</div>
																	<div class="col-md-4">
																		<p></p>
																	</div>
																	<div class="col-md-4">
																		<p>Postcode<input name="Postcode" id="txt_pat_currpostcode" class="form-control form-mandatory" type="text" required /></p>
																	</div>
																	<div class="col-md-12">
																		<p></p>
																	</div>
																	<div class="col-md-4">
																		<p>Contact No</p>
																	</div>
																	<div class="col-md-4">
																		<p>House<input name="telh" id="txt_pat_telh" class="form-control form-mandatory phone-group" type="text"/></p>
																	</div>
																	<div class="col-md-4">
																		<p>Mobile<input name="telhp" id="txt_pat_telhp" class="form-control form-mandatory phone-group" type="text"/></p>
																	</div>
																<!--/div-->
															</div>
															<div class="tab-pane fade in" id="addr_office">
																<!--div class="row"-->
																	<br />
																	<div class="col-md-4">
																		<p>Office Address</p>
																	</div>
																	<div class="col-md-8">
																		<p><input name="Offadd1" id="txt_pat_offadd1" class="form-control" type="text" /></p>
																	</div>
																	<div class="col-md-4">
																		<p></p>
																	</div>
																	<div class="col-md-8">
																		<p><input name="Offadd2" id="txt_pat_offadd2" class="form-control" type="text" /></p>
																	</div>
																	<div class="col-md-4">
																		<p></p>
																	</div>
																	<div class="col-md-8">
																		<p><input name="Offadd3" id="txt_pat_offadd3" class="form-control" type="text" /></p>
																	</div>
																	<div class="col-md-4">
																		<p></p>
																	</div>
																	<div class="col-md-4">
																		<p>Postcode<input name="OffPostcode" id="txt_pat_offpostcode" class="form-control" type="text" /></p>
																	</div>
																	<div class="col-md-12">
																		<p></p>
																	</div>
																	<div class="col-md-4">
																		<p>Contact No</p>
																	</div>
																	<div class="col-md-4">
																		<p>Office Tel<input name="txt_pat_telo" id="txt_pat_telo" class="form-control" type="text" /></p>
																	</div>
																	<div class="col-md-4">
																		<p>Office Ext<input name="txt_pat_teloext" id="txt_pat_teloext" class="form-control" type="text" /></p>
																	</div>
																<!--/div-->
															</div>
															<div class="tab-pane fade in" id="addr_home">
																<!--div class="row"-->
																	<br />
																	<div class="col-md-4">
																		<p>Home Address</p>
																	</div>
																	<div class="col-md-8">
																		<p><input name="pAdd1" id="txt_pat_padd1" class="form-control" type="text" /></p>
																	</div>
																	<div class="col-md-4">
																		<p></p>
																	</div>
																	<div class="col-md-8">
																		<p><input name="pAdd2" id="txt_pat_padd2" class="form-control" type="text" /></p>
																	</div>
																	<div class="col-md-4">
																		<p></p>
																	</div>
																	<div class="col-md-8">
																		<p><input name="pAdd3" id="txt_pat_padd3" class="form-control" type="text" /></p>
																	</div>
																	<div class="col-md-4">
																		<p></p>
																	</div>
																	<div class="col-md-4">
																		<p>Postcode<input name="pPostCode" id="txt_pat_ppostcode" class="form-control" type="text" /></p>
																	</div>
																	<div class="col-md-12">
																		<p></p>
																	</div>
																	<div class="col-md-4">
																		<p>Contact No</p>
																	</div>
																	<div class="col-md-4">
																		<p>Home Tel<input name="txt_pat_ptel" id="txt_pat_ptel" class="form-control" type="text" /></p>
																	</div>
																	<div class="col-md-4">
																		<p>Home Mobile<input name="txt_pat_ptelhp" id="txt_pat_ptelhp" class="form-control" type="text" /></p>
																	</div>
																<!--/div-->
															</div>
														</div>
													</div>
												</div>
											<!--/div-->


                                        </div>

                                        <!-- Tab content end -->
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="tab10">
                                        <!--div class="form-group">
											<div class="col-md-4">
                                                <small for="txt_pat_relation">Relationship</small>
												<div class="input-group">
                                                    <input type="text" class="form-control form-mandatory" name="txt_pat_relation" id="txt_pat_relation">
                                                    <input type="hidden" name="hid_payer_relation" id="hid_payer_relation" value="" />
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-warning" id="btn_payer_relation" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('payer_relation');"><span class="fa fa-ellipsis-h"></span> </button>
                                                    </span>
                                                </div>
                                            </div>
											<div class="col-md-2">
                                                <small for="txt_payer_childno">Child No</small>
                                                <input class="form-control" name="txt_payer_childno" id="txt_payer_childno" placeholder="" type="text">
                                            </div>
                                            <div class="col-md-6">
                                                
                                            </div>
                                        </div-->
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <small for="txt_payer_company">Company Name</small>
                                                <!--input class="form-control" id="corpcomp" placeholder="" type="text"-->
												<div class="input-group">
                                                    <input type="text" class="form-control form-mandatory" name="txt_payer_company" id="txt_payer_company">
                                                    <input type="hidden" name="hid_payer_company" id="hid_payer_company" value="" />
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-warning" id="btn_payer_company" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('payer_company');"><span class="fa fa-ellipsis-h"></span> </button>
                                                    </span>
                                                </div>
                                            </div>
											<div class="col-md-6">
                                                
                                            </div>
										</div>										
                                        <div class="form-group">
                                            <div class="col-md-3">
                                                <small for="txt_payer_staffid">Staff ID</small>
                                                <input class="form-control" name="txt_payer_staffid" id="txt_payer_staffid" placeholder="" type="text">
                                            </div>
											 <div class="col-md-3">
                                                <small for="txt_payer_occupation">Occupation</small>
                                                <!--input class="form-control" id="remarks" placeholder="" type="text"-->
												<div class="input-group">
													<input type="text" class="form-control form-mandatory" name="txt_pat_occupation" id="txt_pat_occupation">
													<input type="hidden" name="hid_pat_occupation" id="hid_pat_occupation" value="" />
													<span class="input-group-btn">
														<button type="button" class="btn btn-warning" id="btn_pat_occupation" data-toggle="modal" data-target="#mdl_item_selector" onclick="Global.pop_item_select('pat_occupation');"><span class="fa fa-ellipsis-h"></span> </button>
													</span>
												</div>
                                            </div>                                            
											<!--div class="col-md-6">
                                                <div class="col-md-4"></div>
												<div class="col-md-5">
													<small for="remarks"><i>Click here if patient issue GL</i></small><br /><br />
													<button id="btn_payer_new_gl" type="button" class="btn btn-warning" >Add New GL</button>
												</div>
												<div class="col-md-3"></div>
                                            </div-->
                                        </div>
										<div class="form-group">
											<div class="col-md-6">
                                                <small for="txt_payer_email_official">Company's Email</small>
                                                <input class="form-control" name="txt_payer_email_official" id="txt_payer_email_official" placeholder="" type="email">
                                            </div>
											<div class="col-md-6">
                                                
                                            </div>
                                        </div>
                                        <!-- end tabs -->
                                    </div>
                                    <!--div role="tabpanel" class="tab-pane fade" id="tab11">
                                        <!-- begin tabs ->
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <small for="input-title">Current Address</small>
                                                <input class="form-control" id="address1" placeholder="" type="text">
                                                <input class="form-control" id="address2" placeholder="" type="text">
                                                <input class="form-control" id="address3" placeholder="" type="text">

                                            </div>
                                            <div class="col-md-4">
                                                <small for="input-mrn">Office Address</small>
                                                <input class="form-control" id="offadd1" placeholder="" type="text">
                                                <input class="form-control" id="offadd2" placeholder="" type="text">
                                                <input class="form-control" id="offadd3" placeholder="" type="text">
                                            </div>
                                            <div class="col-md-4">
                                                <small for="input-mrn">Home Address</small>
                                                <input class="form-control" id="padd1" placeholder="" type="text">
                                                <input class="form-control" id="padd2" placeholder="" type="text">
                                                <input class="form-control" id="padd3" placeholder="" type="text">
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <small for="input-title">Current Postcode</small>
                                                <input class="form-control" id="postcode" placeholder="" type="text">


                                            </div>
                                            <div class="col-md-4">
                                                <small for="offpostcode">Office Postcode</small>
                                                <input class="form-control" id="offpostcode" placeholder="" type="text">

                                            </div>
                                            <div class="col-md-4">
                                                <small for="input-mrn">Home Postcode</small>
                                                <input class="form-control" id="ppostcode" placeholder="" type="text">

                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <small for="telhp">Phone (Mobile)</small>
                                                <input class="form-control" id="telhp" placeholder="" type="text">
                                            </div>
                                            <div class="col-md-4">
                                                <small for="telh">Phone (House)</small>
                                                <input class="form-control" id="telh" placeholder="" type="text">
                                            </div>
                                        </div>
                                        <!-- end tabs ->
                                    </div-->
                                    <div role="tabpanel" class="tab-pane fade" id="tab12">
                                        <div class="form-group">
                                            <div class="col-md-3">
                                                <small for="cmb_pat_active">Active</small>
                                                <select id="cmb_pat_active" name="cmb_pat_active" class="form-control">
                                                    <option value="">- Select Status -</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <small for="cmb_pat_confidential">Confidential</small>
                                                <select id="cmb_pat_confidential" name="cmb_pat_confidential" class="form-control">
                                                    <option value="">- Select Confidential -</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <small for="cmb_pat_mrfolder">MR Folder</small>
                                                <select id="cmb_pat_mrfolder" name="cmb_pat_mrfolder" class="form-control">
                                                    <option value="">- Select MR Folder -</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <small for="cmb_pat_patientcat">Patient Category</small>
                                                <select id="cmb_pat_patientcat" name="cmb_pat_patientcat" class="form-control">
                                                    <option value="">- Select Patient Category -</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-3">
                                                <small for="txt_pat_new_mrn">New MRN</small>
                                                <input class="form-control" name="txt_pat_new_mrn" id="txt_pat_new_mrn" placeholder="" type="text">
                                            </div>
                                            <div class="col-md-3">
                                                <small for="txt_pat_blood_grp">Blood Group</small>
                                                <input class="form-control" name="txt_pat_blood_grp" id="txt_pat_blood_grp" placeholder="" type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button id="btn_register_close" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="btn_register_patient" type="button" class="btn btn-success" data-oper="add">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="mdl_existing_record" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header label-primary">
				<p align="center"><b>EXISTING PATIENT RECORD</b></p>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						These are existing records that has similar (or almost similar) data with the one you're trying to register.<br />
						Should any of these records matched exactly (after confirmation with the patient), you may decide to merge the existing data with the new ones.<br /><br />
					</div>
				</div>
				<div class="table-responsive table-no-bordered content">
					<table id="tbl_existing_record" class="table-hover cell-border" width="100%">
						<thead>
							<tr>
								<th>Merge?</th>
								<th>MRN</th>
								<th>Name</th>
								<th>New IC</th>
								<th>Old IC</th>
								<th>Other ID</th>
								<th>DOB</th>
								<th>IDno</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-success" id="btn_reg_proceed">Proceed</button>
			</div>
		</div>
	</div>
</div>

<div id="mdl_mykad" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header label-success">
				<p align="center"><b>Mykad Identification Menu</b></p>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						Insert mykad, then press read mykad button and wait for information to appear at the bottom
						<br/><span id="mykad_reponse"></span>
						<br /><br />
					</div>
				</div>
        		<form id="frm_mykad_info" class="form-horizontal">
					<div class="form-group">
						<div class="col-md-2">
							<img id="mykad_photo" src="../../../../assets/img/defaultprofile.png" width="120" height="140" class="addressinp" />
							<button type="button" class="btn btn-primary" id="read_mykad">Read mykad</button>
						</div>
						<div class="col-md-10">
							<div class="row"><br /></div>
							<div class="row">
								<div class="col-md-6">
									<small for="txt_pat_name">Name</small>
									<input class="form-control has-error form-mandatory" name="Name" id="mykad_pat_name" placeholder="" type="text" required>
								</div>
								<div class="col-md-3">
									<small for="cmb_pat_sex">DOB</small>
										<input class="form-control has-error form-mandatory" name="Name" id="mykad_DOB" placeholder="" type="date" required>
								</div>
								<div class="col-md-3">
									<small for="txt_pat_newic">Race</small>
									<input class="form-control form-mandatory" name="Newic" id="mykad_race" placeholder="" type="text">
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<small for="txt_pat_newic">New IC (eg 690101086649)</small>
									<input class="form-control form-mandatory" name="Newic" id="mykad_newic" placeholder="" type="text">
								</div>
								<div class="col-md-3">
									<small for="txt_pat_oldic">Old IC</small>
									<input class="form-control form-mandatory" name="Oldic" id="mykad_oldic" placeholder="" type="text">
								</div>
								<div class="col-md-3">
									<small for="txt_pat_idno">Religion</small>
									<input class="form-control form-mandatory" name="idnumber" id="mykad_religion" placeholder="" type="text">
								</div>
								<div class="col-md-3">
									<small for="txt_pat_newic">Gender</small>
									<input class="form-control form-mandatory" name="Newic" id="mykad_gender" placeholder="" type="text">
								</div>
							</div>
							<div class="row">
								<div class="col-md-9">
									<br/>
									<small>ADDRESS</small>
                                    <input class="form-control form-mandatory addressinp" id="mykad_address1" placeholder="" type="text">
                                    <input class="form-control form-mandatory addressinp" id="mykad_address2" placeholder="" type="text">
                                    <input class="form-control form-mandatory addressinp" id="mykad_address3" placeholder="" type="text">
                                </div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<small for="txt_pat_idno">Postcode</small>
									<input class="form-control form-mandatory" name="mykad_postcode" id="mykad_postcode" placeholder="" type="text">
								</div>
								<div class="col-md-3">
									<small for="txt_pat_idno">City</small>
									<input class="form-control form-mandatory" name="mykad_city" id="mykad_city" placeholder="" type="text">
								</div>
								<div class="col-md-3">
									<small for="txt_pat_newic">State</small>
									<input class="form-control form-mandatory" name="mykad_state" id="mykad_state" placeholder="" type="text">
								</div>
							</div>
						</div>
					</div>
				</form>
				<!-- <div class="table-responsive table-no-bordered content">
					<table id="tbl_existing_record" class="table-hover cell-border" width="100%">
						<thead>
							<tr>
								<th>New IC</th>
								<th>Birth Place</th>
								<th>Name</th>
								<th>Old IC</th>
								<th>Religion</th>
								<th>Gender</th>
								<th>Race</th>
								<th>Address 1</th>
								<th>Address 2</th>
								<th>Address 3</th>
								<th>Postcode</th>
								<th>City</th>
								<th>State</th>
							</tr>
						</thead>
					</table>
				</div> -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-success" id="btn_reg_proceed">Proceed</button>
			</div>
		</div>
	</div>
</div>
<!-- Large modal -->
<div id="mdl_patient_info_MR" data-keyboard="false" class="modal fade ba" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true" style="display: none; z-index: 100; padding-left: 0px !important;">
    <div class="modal-dialog half modal-lg">
        <form id="frm_patient_info_MR" class="form-horizontal" autocomplete="off">
			<input type="hidden" name="idno" id="txt_pat_idno_MR">
            <input name="pat_mrn" id="pat_mrn_MR" type="hidden">
            <input name="PatClass" id="PatClass_MR" type="hidden" value="{{request()->get('PatClass')}}">
            <input name="func_after" id="func_after_pat_MR" type="hidden">
            <input name="apptidno" id="apptidno_pat_MR" type="hidden">

            <div class="modal-content">
                <div id="mdl_patient_header_MR" class="modal-header label-info" style="position: sticky;top: 0px;z-index: 3;">
                    <button type="button" class="" data-dismiss="modal" aria-label="Close" style="float: right;
                            color: white;
                            background: #d34242;
                            border-radius: 5px;">
                      <span class="glyphicon glyphicon-remove" aria-hidden="true" style="top: 3px;"></span>
                    </button>
                    <div class="form-group ">
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <small  for="mrn">PATIENT REGISTRATION (MRN)</small>
                            <input class="form-control " name="MRN" id="txt_pat_mrn_MR" placeholder="" type="text" readonly>
                        </div>
                        <div class="col-sm-3">
                            <small for="first_visit_date">FIRST VISIT</small>
                            <input class="form-control" name="first_visit_date" id="first_visit_date_MR" placeholder="" type="text" readonly>
                        </div>
                        <div class="col-sm-3">
                            <small for="last_visit_date">LAST VISIT</small>
                            <input class="form-control" name="last_visit_date" id="last_visit_date_MR" placeholder="" type="text" readonly>
                        </div>
                        <div class="col-sm-3">
                            <small for="episno">EPISODE NO</small>
                            <input class="form-control" name="Episno" id="txt_pat_episno_MR" placeholder="" type="text" readonly></div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Biodata -->

                            <div class="panel panel-default" style="position: relative;">
                                <div class="panel-heading clearfix" id="toggle_tabPatinfo_MR" data-toggle="collapse" data-target="#tabBio_MR">
                                    <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                                    <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
                                    <div class="pull-right" style="position: absolute; padding: 5px 0 0 5px; left: 10px; top: 0px;">
                                        <h5><strong>PATIENT INFO</strong></h5>
                                    </div> 
                                </div>

                                <div id="tabBio_MR" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <!-- Tab content begin -->
                                    <div class="form-group"> 
                                        <div class="col-md-2">
                                            <img id="photobase64" src="{{asset('img/defaultprofile.png')}}" width="120" height="140" defaultsrc="{{asset('img/defaultprofile.png')}}" />
                                        </div>
                                        <div class="col-md-10">
                                            <div class="row"><br /></div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <small for="titlecode_MR">Title</small>
                                                    <!-- <div class="input-group"> -->
                                                        <input type="text" class="form-control" name="txt_pat_title_MR" id="txt_pat_title_MR">
                                                        <input type="hidden" name="TitleCode" id="hid_pat_title_MR" value="" />
                                                        <!-- <span class="input-group-btn">
                                                            <button type="button" class="btn btn-info" id="btn_pat_title_MR" onclick-xguna="Global.pop_item_select('pat_title_MR');"><span class="fa fa-ellipsis-h"></span></button>
                                                        </span> 
                                                    </div>-->
                                                </div>
                                                <div class="col-md-6">
                                                    <small for="txt_pat_name_MR">Name</small>
                                                    <input class="form-control form-mandatory" name="Name" id="txt_pat_name_MR" placeholder="" type="text" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <small for="cmb_pat_sex_MR">Sex</small>
                                                    <select id="cmb_pat_sex_MR" name="Sex" class="form-control form-mandatory" required>
                                                        <option value="">- Select Sex -</option>
                                                        <option value="M">Male</option>
                                                        <option value="F">Female</option>
                                                        <option value="U">Unknwon</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <small for="cmb_pat_idtype_MR">IC Type</small>
                                                    <!-- <select id="cmb_pat_idtype_MR" name="ID_Type" class="form-control">
                                                        <option value="">- Select IC Type -</option>
                                                    </select> -->
                                                    <!-- <div class="input-group"> -->
                                                        <input type="text" class="form-control form-mandatory" name="txt_ID_Type" id="txt_ID_Type_MR" required>
                                                        <input type="hidden" name="ID_Type" id="hid_ID_Type_MR" value="" />
                                                        <!-- <span class="input-group-btn">
                                                            <button type="button" class="btn btn-info" id="btn_ID_Type_MR" onclick-xguna="Global.pop_item_select('ID_Type_MR');"><span class="fa fa-ellipsis-h"></span> </button>
                                                        </span> 
                                                    </div>-->
                                                </div>
                                                <div class="col-md-3">
                                                    <small for="txt_pat_newic_MR">New IC (eg 690101086649)</small>
                                                    <input class="form-control form-mandatory ic-group" name="Newic" id="txt_pat_newic_MR" placeholder="" type="text" onkeypress="return isNumberKey(event);">
                                                </div>
                                                <div class="col-md-3">
                                                    <small for="txt_pat_oldic_MR">Old IC</small>
                                                    <input class="form-control form-mandatory ic-group" name="Oldic" id="txt_pat_oldic_MR" placeholder="" type="text">
                                                </div>
                                                <div class="col-md-3">
                                                    <small for="txt_pat_idnumber_MR">Other (eg Passport Number)</small>
                                                    <input class="form-control form-mandatory ic-group" name="idnumber" id="txt_pat_idnumber_MR" placeholder="" type="text">
                                                </div>                                              
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <small for="txt_pat_dob_MR">DOB - Age</small>
                                            <div class="input-group">
                                                <input class="form-control form-mandatory" name="DOB" id="txt_pat_dob_MR" placeholder="" type="date" required>
                                                <span class="input-group-addon" style="background-color:transparent; border-color: transparent" style="width:10px;">&mdash;</span>
                                                <input class="form-control" style="width:50px;" name="txt_pat_age" id="txt_pat_age_MR" placeholder="" type="text" disabled>
                                            </div>
                                        </div>                                          
                                        <div class="col-md-2">
                                            <small for="cmb_pat_racecode_MR">Race</small>
                                            <!-- <select id="cmb_pat_racecode_MR" name="RaceCode" class="form-control form-mandatory">
                                                <option value="">- Select Race -</option>
                                            </select> -->
                                            <!-- <div class="input-group"> -->
                                                <input type="text" class="form-control form-mandatory" name="txt_RaceCode" id="txt_RaceCode_MR" required>
                                                <input type="hidden" name="RaceCode" id="hid_RaceCode_MR" value="" />
                                                <!-- <span class="input-group-btn">
                                                    <button type="button" class="btn btn-info" id="btn_RaceCode_MR" onclick-xguna="Global.pop_item_select('RaceCode_MR');"><span class="fa fa-ellipsis-h"></span> </button>
                                                </span>
                                            </div> -->
                                        </div>
                                        <div class="col-md-3">
                                            <small for="cmb_pat_religion_MR">Religion</small>
                                            <!-- <select id="cmb_pat_religion_MR" name="Religion" class="form-control form-mandatory">
                                                <option value="">- Select Religion -</option>
                                            </select> -->
                                            <!-- <div class="input-group"> -->
                                                <input type="text" class="form-control form-mandatory" name="txt_Religion" id="txt_Religion_MR" required>
                                                <input type="hidden" name="Religion" id="hid_Religion_MR" value="" />
                                                <!-- <span class="input-group-btn">
                                                    <button type="button" class="btn btn-info" id="btn_Religion_MR" onclick-xguna="Global.pop_item_select('Religion_MR');"><span class="fa fa-ellipsis-h"></span> </button>
                                                </span>
                                            </div> -->
                                        </div>

                                        <div class="col-md-3">
                                            <small for="cmb_pat_category_MR">Category</small>
                                            <select id="cmb_pat_category_MR" name="pat_category" class="form-control form-mandatory" required="" aria-required="true">
                                                <option value="LOCAL">LOCAL</option>
                                                <option value="EXPATRIATE">EXPATRIATE</option>
                                                <option value="TOURIST">TOURIST</option>
                                            </select>
                                        </div>

                                    </div>
                                    
                                    <div class="form-group">                                            
                                        <div class="col-md-6">
                                            <small for="txt_pat_citizen_MR">Citizenship</small>
                                            <!-- <div class="input-group"> -->
                                                <input type="text" class="form-control form-mandatory" name="txt_pat_citizen" id="txt_pat_citizen_MR" required>
                                                <input type="hidden" name="Citizencode" id="hid_pat_citizen_MR" value="" />
                                                <!-- <span class="input-group-btn">
                                                    <button type="button" class="btn btn-info" id="btn_pat_citizen_MR" onclick-xguna="Global.pop_item_select('pat_citizen_MR');"><span class="fa fa-ellipsis-h"></span> </button>
                                                </span>
                                            </div> -->
                                        </div>

                                        <div class="col-md-6">
                                            <small for="txt_pat_langcode_MR">Language</small>
                                            <!-- <select id="cmb_pat_langcode" name="LanguageCode" class="form-control">
                                                <option value="">- Select Language -</option>
                                            </select> -->
                                            <!-- <div class="input-group"> -->
                                                <input type="text" class="form-control" name="txt_LanguageCode" id="txt_LanguageCode_MR">
                                                <input type="hidden" name="LanguageCode" id="hid_LanguageCode_MR" value="" />
                                                <!-- <span class="input-group-btn">
                                                    <button type="button" class="btn btn-info" id="btn_LanguageCode_MR" onclick-xguna="Global.pop_item_select('LanguageCode_MR');"><span class="fa fa-ellipsis-h"></span> </button>
                                                </span>
                                            </div> -->
                                        </div>
                                    </div>  

                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <small for="txt_pat_email_MR">Email</small>
                                            <input class="form-control" name="Email" id="txt_pat_email_MR" placeholder="" type="email">
                                        </div>
                                        <div class="col-md-4">
                                            <small for="txt_pat_loginid_MR">login id</small>
                                            <input class="form-control" name="loginid" id="txt_pat_loginid_MR" placeholder="" type="text">
                                        </div>
                                         <div class="col-md-4">
                                            <small for="txt_pat_iPesakit_MR">iPesakit</small>
                                            <input class="form-control" name="iPesakit" id="txt_pat_iPesakit_MR" placeholder="" type="text">
                                        </div>
                                    </div> 
                                    
                                    <!-- Tab content end -->
                                </div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="panel panel-default" style="position: relative;">
                                <div class="panel-heading clearfix collapsed" id="toggle_tabAddr_MR" data-toggle="collapse" data-target="#tabAddr_MR">
                                    <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                                    <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
                                    <div class="pull-right" style="position: absolute; padding: 5px 0 0 5px; left: 10px; top: 0px;">
                                        <h5><strong>ADDRESS</strong></h5>
                                    </div> 
                                </div>

                                <div id="tabAddr_MR" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <!--div class="row"-->
                                        <div class="col-md-12">
                                            <!-- <p><strong>ADDRESS</strong></p>                                                      -->
                                            <div class="tab-v2">
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a href="#addr_current_MR" data-toggle="tab">CURRENT</a></li>
                                                    <li><a href="#addr_office_MR" data-toggle="tab">OFFICE</a></li>
                                                    <li><a href="#addr_home_MR" data-toggle="tab">HOME</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade in active" id="addr_current_MR">
                                                        <!--div class="row"-->
                                                        <br />
                                                        <div class="col-md-4">
                                                            <p>CURRENT ADDRESS</p>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p><input name="Address1" id="txt_pat_curradd1_MR" class="form-control form-mandatory" type="text" required /></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p></p>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p><input name="Address2" id="txt_pat_curradd2_MR" class="form-control form-mandatory" type="text" /></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p></p>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p><input name="Address3" id="txt_pat_curradd3_MR" class="form-control form-mandatory" type="text" /></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p>POSTCODE<input name="Postcode" id="txt_pat_currpostcode_MR" class="form-control form-mandatory" type="text" required /></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <small for="txt_pat_area_MR">AREA</small>
                                                            <!-- <div class="input-group"> -->
                                                                <input type="text" class="form-control form-mandatory" name="txt_pat_area" id="txt_pat_area_MR" required>
                                                                <input type="hidden" name="AreaCode" id="hid_pat_area_MR" value="" />
                                                                <!-- <span class="input-group-btn">
                                                                    <button type="button" class="btn btn-info" id="btn_pat_area_MR" onclick-xguna="Global.pop_item_select('pat_area_MR');"><span class="fa fa-ellipsis-h"></span> </button>
                                                                </span>
                                                            </div> -->
                                                        </div>
                                                        <div class="col-md-12">
                                                            <p></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p>CONTACT NO</p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p>HOUSE<input name="telh" id="txt_pat_telh_MR" class="form-control form-mandatory phone-group" type="text"/></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p>MOBILE<input name="telhp" id="txt_pat_telhp_MR" class="form-control form-mandatory phone-group" type="text"/></p>
                                                        </div>
                                                        <!--/div-->
                                                    </div>
                                                    <div class="tab-pane fade in" id="addr_office_MR">
                                                        <!--div class="row"-->
                                                        <br />
                                                        <div class="col-md-4">
                                                            <p>OFFICE ADDRESS</p>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p><input name="Offadd1" id="txt_pat_offadd1_MR" class="form-control" type="text" /></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p></p>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p><input name="Offadd2" id="txt_pat_offadd2_MR" class="form-control" type="text" /></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p></p>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p><input name="Offadd3" id="txt_pat_offadd3_MR" class="form-control" type="text" /></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p>POSTCODE<input name="OffPostcode" id="txt_pat_offpostcode_MR" class="form-control" type="text" /></p>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <p></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p>CONTACT NO</p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p>OFFICE TEL<input name="txt_pat_telo" id="txt_pat_telo_MR" class="form-control" type="text" /></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p>OFFFICE EXT<input name="txt_pat_teloext" id="txt_pat_teloext_MR" class="form-control" type="text" /></p>
                                                        </div>
                                                        <!--/div-->
                                                    </div>
                                                    <div class="tab-pane fade in" id="addr_home_MR">
                                                        <!--div class="row"-->
                                                        <br />
                                                        <div class="col-md-4">
                                                            <p>HOME ADDRESS</p>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p><input name="pAdd1" id="txt_pat_padd1_MR" class="form-control" type="text" /></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p></p>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p><input name="pAdd2" id="txt_pat_padd2_MR" class="form-control" type="text" /></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p></p>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p><input name="pAdd3" id="txt_pat_padd3_MR" class="form-control" type="text" /></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p>POSTCODE<input name="pPostCode" id="txt_pat_ppostcode_MR" class="form-control" type="text" /></p>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <p></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p>CONTACT NO</p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p>HOME TEL<input name="txt_pat_ptel" id="txt_pat_ptel_MR" class="form-control" type="text" /></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p>HOME MOBILE<input name="txt_pat_ptelhp" id="txt_pat_ptelhp_MR" class="form-control" type="text" /></p>
                                                        </div>
                                                        <!--/div-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/div-->
                                    </div>
                                </div>
                                </div>
                            </div>

                            <!-- Corp info -->
                            <div class="panel panel-default" style="position: relative;">
                                <div class="panel-heading clearfix collapsed" id="toggle_tabCorp_MR" data-toggle="collapse" data-target="#tabCorp_MR">
                                    <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                                    <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
                                    <div class="pull-right" style="position: absolute; padding: 5px 0 0 5px; left: 10px; top: 0px;">
                                        <h5><strong>CORPORATE INFO</strong></h5>
                                    </div> 
                                </div>

                                <div id="tabCorp_MR" class="panel-collapse collapse">
                                <div class="panel-body">
 
                                    <div class="form-group">
                                        <div class="col-md-offset-4 col-md-6">
                                            <small for="txt_payer_company_MR">Company Name</small>
                                            <!--input class="form-control" id="corpcomp" placeholder="" type="text"-->
                                            <!-- <div class="input-group"> -->
                                                <input type="text" class="form-control form-mandatory" name="txt_payer_company" id="txt_payer_company_MR">
                                                <input type="hidden" name="CorpComp" id="hid_payer_company_MR" value="" />
                                                <!-- <span class="input-group-btn">
                                                    <button type="button" class="btn btn-info" id="btn_payer_company_MR" onclick-xguna="Global.pop_item_select('payer_company_MR');"><span class="fa fa-ellipsis-h"></span> </button>
                                                </span>
                                            </div> -->
                                        </div>
                                        <div class="col-md-6">
                                            
                                        </div>
                                    </div>                                      
                                    <div class="form-group">
                                        <div class="col-md-offset-4 col-md-3">
                                            <small for="txt_payer_staffid_MR">Staff ID</small>
                                            <input class="form-control" name="Staffid" id="txt_payer_staffid_MR" placeholder="" type="text">
                                        </div>
                                         <div class="col-md-3">
                                            <small for="txt_payer_occupation_MR">Occupation</small>
                                            <!-- <div class="input-group"> -->
                                                <input type="text" class="form-control form-mandatory" name="txt_pat_occupation" id="txt_pat_occupation_MR">
                                                <input type="hidden" name="OccupCode" id="hid_pat_occupation_MR" value="" />
                                                <!-- <span class="input-group-btn">
                                                    <button type="button" class="btn btn-info" id="btn_pat_occupation_MR" onclick-xguna="Global.pop_item_select('pat_occupation_MR');"><span class="fa fa-ellipsis-h"></span> </button>
                                                </span>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-offset-4 col-md-6">
                                            <small for="txt_payer_email_official_MR">Company's Email</small>
                                            <input class="form-control" name="Email_official" id="txt_payer_email_official_MR" placeholder="" type="email">
                                        </div>
                                        <div class="col-md-6">
                                            
                                        </div>
                                    </div>
                                    <!-- end tabs -->

                                </div>
                                </div>
                            </div>

                            <!-- Nok -->
                            <div class="panel panel-default" style="position: relative;">
                                <div class="panel-heading clearfix collapsed" id="toggle_tabNok_pat_MR" data-toggle="collapse" data-target="#tabNok_pat_MR">

                                <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                                <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
                                <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
                                    <h5><strong>NEXT OF KIN</strong></h5>
                                </div> 
                                </div>

                                <div id="tabNok_pat_MR" class="panel-collapse collapse">
                                <div class="panel-body form-horizontal">
                                    <div class="col-md-6">
                                        <div id="jqGrid_nok_pat_c_MR">
                                            <div class='col-md-12' style="padding:0 0 15px 0">
                                                <table id="jqGrid_nok_pat_MR" class="table table-striped"></table>
                                                <div id="jqGridPager_nok_pat_MR"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="form_nok_pat_MR">
                                        <div class="form-group" style="display: none;">
                                            <div class="col-md-12">
                                            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                            id="btn_grp_edit_nok_pat_MR">
                                                <button type="button" class="btn btn-default" id="add_nok_pat_MR">
                                                    <span class="fa fa-plus-square-o fa-lg"></span> Add
                                                </button>
                                                <button type="button" class="btn btn-default" id="edit_nok_pat_MR">
                                                    <span class="fa fa-edit fa-lg"></span> Edit
                                                </button>
                                                <button type="button" class="btn btn-default" id="save_nok_pat_MR">
                                                    <span class="fa fa-save fa-lg"></span> Save
                                                </button>
                                                <button type="button" class="btn btn-default" id="cancel_nok_pat_MR" >
                                                    <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
                                                </button>
                                            </div></div>
                                        </div>

                                        <input id="nok_idno_pat_MR" name="nok_idno_pat" type="hidden">

                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <small for="nok_name_pat_MR">Name</small>
                                                <input id="nok_name_pat_MR" name="nok_name_pat" type="text" class="form-control" data-validation="required">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <small for="nok_addr1_pat_MR">Address</small>
                                                <input id="nok_addr1_pat_MR" name="nok_addr1_pat" type="text" class="form-control" style="margin-bottom: 2px">
                                                <input id="nok_addr2_pat_MR" name="nok_addr2_pat" type="text" class="form-control" style="margin-bottom: 2px">
                                                <input id="nok_addr3_pat_MR" name="nok_addr3_pat" type="text" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <small for="nok_postcode_pat_MR">Postcode</small>
                                                <input id="nok_postcode_pat_MR" name="nok_postcode_pat" type="text" class="form-control">
                                            </div>

                                            <div class="col-md-4">
                                                <small for="nok_telh_pat_MR">Tel (H)</small>
                                                <input id="nok_telh_pat_MR" name="nok_telh_pat" type="text" class="form-control">
                                            </div>

                                            <div class="col-md-4">
                                                <small for="nok_telo_pat_MR">Tel (O)</small>
                                                <input id="nok_telo_pat_MR" name="nok_telo_pat" type="text" class="form-control" rdonly>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <small for="nok_relate_pat_MR">Relationship</small>
                                                <!-- <div class='input-group'> -->
                                                    <input id="nok_relate_pat_MR" name="nok_relate_pat" type="text" class="form-control uppercase" data-validation="required">
                                                    <!-- <a class='input-group-addon btn btn-info'><span class='fa fa-ellipsis-h'></span></a>
                                                </div>
                                                <span class="help-block"></span> -->
                                            </div>
                                            <div class="col-md-6">
                                                <small for="nok_telhp_pat_MR">Tel (H/P)</small>
                                                <input id="nok_telhp_pat_MR" name="nok_telhp_pat" type="text" class="form-control" rdonly>
                                            </div>
                                            <div class="col-md-2">
                                                <small for="nok_ext_pat_MR">Ext</small>
                                                <input id="nok_ext_pat_MR" name="nok_ext_pat" type="text" class="form-control"  rdonly>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <small for="nok_computerid_MR">Computer ID</small>
                                                <input id="nok_computerid_MR" name="nok_computerid" type="text" class="form-control"  rdonly>
                                            </div>
                                            <div class="col-md-4">
                                                <small for="nok_lastuser_MR">Last User</small>
                                                <input id="nok_lastuser_MR" name="nok_lastuser" type="text" class="form-control"  rdonly>
                                            </div>
                                            <div class="col-md-4">
                                                <small for="nok_lastupdate_MR">Last Update</small>
                                                <input id="nok_lastupdate_MR" name="nok_lastupdate" type="text" class="form-control"  rdonly>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                </div>
                            </div>

                            <!-- Emergency -->
                            <div class="panel panel-default" style="position: relative;">
                                <div class="panel-heading clearfix collapsed" id="toggle_tabNok_emr_MR" data-toggle="collapse" data-target="#tabNok_emr_MR">

                                <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                                <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
                                <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
                                    <h5><strong>EMERGENCY</strong></h5>
                                </div> 
                                </div>

                                <div id="tabNok_emr_MR" class="panel-collapse collapse">
                                <div class="panel-body form-horizontal">
                                    <div class="col-md-6">
                                        <div id="jqGrid_nok_emr_c_MR">
                                            <div class='col-md-12' style="padding:0 0 15px 0">
                                                <table id="jqGrid_nok_emr_MR" class="table table-striped"></table>
                                                <div id="jqGridPager_nok_emr_MR"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="form_nok_emr_MR">
                                        <div class="form-group" style="display: none;">
                                            <div class="col-md-12">
                                            <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                            id="btn_grp_edit_nok_emr_MR">
                                                <button type="button" class="btn btn-default" id="add_nok_emr_MR">
                                                    <span class="fa fa-plus-square-o fa-lg"></span> Add
                                                </button>
                                                <button type="button" class="btn btn-default" id="edit_nok_emr_MR">
                                                    <span class="fa fa-edit fa-lg"></span> Edit
                                                </button>
                                                <button type="button" class="btn btn-default" id="save_nok_emr_MR">
                                                    <span class="fa fa-save fa-lg"></span> Save
                                                </button>
                                                <button type="button" class="btn btn-default" id="cancel_nok_emr_MR" >
                                                    <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
                                                </button>
                                            </div></div>
                                        </div>

                                        <input id="emr_idno_MR" name="emr_idno" type="hidden">

                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <small for="emr_name_MR">Name</small>
                                                <input id="emr_name_MR" name="emr_name" type="text" class="form-control" data-validation="required">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-8">
                                                <small for="emr_relate_MR">Relationship</small>
                                                <!-- <div class='input-group'> -->
                                                    <input id="emr_relate_MR" name="emr_relate" type="text" class="form-control uppercase">
                                                    <!-- <a class='input-group-addon btn btn-info'><span class='fa fa-ellipsis-h'></span></a>
                                                </div>
                                                <span class="help-block"></span> -->
                                            </div>

                                            <div class="col-md-4">
                                                <small for="emr_email_MR">E-mail</small>
                                                <input id="emr_email_MR" name="emr_email" type="text" class="form-control" rdonly>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <small for="emr_telhp_MR">Handphone</small>
                                                <input id="emr_telhp_MR" name="emr_telhp" type="text" class="form-control" data-validation="required">
                                            </div>

                                            <div class="col-md-offset-3 col-md-3">
                                                <small for="emr_telh_MR">Telephone</small>
                                                <input id="emr_telh_MR" name="emr_telh" type="text" class="form-control">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                </div>
                            </div>

                            <!-- pat rec -->
                            <div class="panel panel-default" style="position: relative;">
                                <div class="panel-heading clearfix collapsed" id="toggle_tabPatrec_MR" data-toggle="collapse" data-target="#tabPatrec_MR">
                                    <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                                    <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
                                    <div class="pull-right" style="position: absolute; padding: 5px 0 0 5px; left: 10px; top: 0px;">
                                        <h5><strong>PATIENT RECORD</strong></h5>
                                    </div> 
                                </div>

                                <div id="tabPatrec_MR" class="panel-collapse collapse">
                                <div class="panel-body">
 
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <small for="cmb_pat_active_MR">Active</small>
                                            <select id="cmb_pat_active_MR" name="Active" class="form-control form-mandatory" required>
                                                <option value="1" selected="selected">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <small for="cmb_pat_Confidential_MR">Confidential</small>
                                            <select id="cmb_pat_Confidential_MR" name="Confidential" class="form-control form-mandatory" required>
                                                <option value="1">Yes</option>
                                                <option value="0" selected="selected">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <small for="cmb_pat_MRFolder_MR">MR Folder</small>
                                            <select id="cmb_pat_MRFolder_MR" name="MRFolder" class="form-control form-mandatory" required>
                                                <option value="1" selected="selected">Yes</option>
                                                <option value="0" >No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <small for="txt_bloodgroup_MR">Blood Group</small>
                                            <input class="form-control" name="bloodgrp" id="txt_bloodgroup_MR" placeholder="" type="text">
                                        </div>
                                        <div class="col-md-3">
                                            <small for="txt_newmrn_MR">New Mrn</small>
                                            <input class="form-control" name="NewMrn" id="txt_newmrn_MR" placeholder="" type="text">
                                        </div>
                                    </div>
                                    <!-- end tabs -->

                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button id="btn_register_close_MR" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <!-- <button id="btn_register_patient" type="button" class="btn btn-success" data-oper="add">Save changes</button> -->
                </div>
            </div>
        </form>
    </div>
</div>

<!-- <div id="mdl_existing_record" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;background-color: rgba(0, 0, 0, 0.3);">
    <div class="modal-dialog modal-lg" style="width: 70%; height: 70%; margin: auto;">
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
				<h4 align="center" style="color: white"><b>Mykad Identification Menu</b></h4>
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
							<img id="mykad_photo" src="{{asset('img/defaultprofile.png')}}" width="120" height="140" class="addressinp" defaultsrc="{{asset('img/defaultprofile.png')}}" />
							<button type="button" class="btn btn-primary" id="read_mykad">Scan ID</button>
						</div>
						<div class="col-md-10">
							<div class="row"><br /></div>
							<div class="row">
								<div class="col-md-6">
									<small for="Name">Name</small>
									<input class="form-control form-mandatory" name="Name" id="mykad_pat_name" placeholder="" type="text" required>
								</div>
								<div class="col-md-3">
									<small for="DOB">DOB</small>
										<input class="form-control form-mandatory" name="DOB" id="mykad_DOB" placeholder="" type="date" required>
								</div>
								<div class="col-md-3">
									<small for="race">Race</small>
									<input class="form-control form-mandatory" name="Newic" id="mykad_race" placeholder="" type="text">
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<small for="Newic">New IC (eg 690101086649)</small>
									<input class="form-control form-mandatory" name="Newic" id="mykad_newic" placeholder="" type="text">
								</div>
								<div class="col-md-3">
									<small for="Oldic">Old IC</small>
									<input class="form-control form-mandatory" name="Oldic" id="mykad_oldic" placeholder="" type="text">
								</div>
								<div class="col-md-3">
									<small for="religion">Religion</small>
									<input class="form-control form-mandatory" name="religion" id="mykad_religion" placeholder="" type="text">
								</div>
								<div class="col-md-3">
									<small for="Gender">Gender</small>
									<input class="form-control form-mandatory" name="Gender" id="mykad_gender" placeholder="" type="text">
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
									<small for="mykad_postcode">Postcode</small>
									<input class="form-control form-mandatory" name="mykad_postcode" id="mykad_postcode" placeholder="" type="text">
								</div>
								<div class="col-md-3">
									<small for="mykad_city">City</small>
									<input class="form-control form-mandatory" name="mykad_city" id="mykad_city" placeholder="" type="text">
								</div>
								<div class="col-md-3">
									<small for="mykad_state">State</small>
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
			<!-- </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-success" id="btn_mykad_proceed">Proceed</button>
			</div>
		</div>
	</div>
</div>


<div id="mdl_biometric" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mymdl_biometric" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width: 100%; height: 100%; margin: auto;">
            
        <!-- Modal content-->
        <!-- <div class="modal-content">
            <div class="modal-header">
                <button onclick="mykadclosemodal()" id="mykadclosemodal" type="button" class="" data-dismiss="modal" aria-label="Close" style="float: right;
                        color: white;
                        background: #d34242;
                        border-radius: 5px;
                        padding: 5px 10px;">
                  <span class="glyphicon glyphicon-remove" aria-hidden="true" style="top: 3px;"></span><b> Close</b>
                </button>
                <h2 class="modal-title" id="txt_item_selector_MR">&nbsp;</h2>
            </div>
            <div class="modal-body">
                <iframe style="display:block; border:none; height:90vh; width:100%;" id="mykadFPiframe"></iframe>
            </div>
        </div>
        
    </div>
    
</div>  --> 
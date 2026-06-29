@extends('hisdb.pat_mgmt.patmast_iframe_main')

@section('title', 'Patmast Iframe')

@section('css')
<style>
    #allmodal{
        padding: 0px !important;
        height: 100vh;
        width: 100vw;
        overflow-x: hidden;
        margin: 0px !important;
    }
</style>

<script>
    var pat_mast_data = {
        @foreach($pat_mast_data as $key => $val) 
            '{{$key}}' : `{!!str_replace('`', '', $val)!!}`,
        @endforeach 
    };
</script>
@endsection

@section('body')
<div class="modal-dialog half modal-lg" id="allmodal">
    <form id="frm_patient_info" class="form-horizontal" autocomplete="off">
        <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
		<input type="hidden" name="idno" id="txt_pat_idno">
        <input name="pat_mrn" id="pat_mrn" type="hidden" value="{{request()->get('mrn')}}">
        <input name="PatClass" id="PatClass" type="hidden" value="{{request()->get('PatClass')}}">
        <input name="func_after" id="func_after_pat" type="hidden">
        <input name="apptidno" id="apptidno_pat" type="hidden">

        <div class="modal-content">
            <div id="mdl_patient_header" class="modal-header label-info" style="position: sticky;top: 0px;z-index: 3;">
                <button type="button" class="" data-dismiss="modal" aria-label="Close" id="close_iframe" style="float: right;
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
                        <input class="form-control " name="MRN" id="txt_pat_mrn" placeholder="" type="text" readonly>
                    </div>
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
                        <input class="form-control" name="Episno" id="txt_pat_episno" placeholder="" type="text" readonly></div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        <!-- Nav tabs -->
                        <!-- <ul class="nav nav-tabs" role="tablist" id="tab_patient_info">
                            <li role="presentation" class="active"><a href="#tab9" role="tab" data-toggle="tab" aria-expanded="true">Patient Info</a></li>
                            <li role="presentation" class=""><a href="#tab10" role="tab" data-toggle="tab" aria-expanded="false">Corporate Info</a></li>
                            <li role="presentation" class=""><a href="#tab12" role="tab" data-toggle="tab" aria-expanded="false">Medical Info</a></li>
                        </ul> -->
                        <!-- Biodata -->

                        <div class="panel panel-default" style="position: relative;">
                            <div class="panel-heading clearfix" id="toggle_tabPatinfo" data-toggle="collapse" data-target="#tabBio">
                                <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                                <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
                                <div class="pull-right" style="position: absolute; padding: 5px 0 0 5px; left: 10px; top: 0px;">
                                    <h5><strong>PATIENT INFO</strong></h5>
                                </div> 
                            </div>

                            <div id="tabBio" class="panel-collapse collapse in">
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
                                                <small for="titlecode">Title</small>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="txt_pat_title" id="txt_pat_title">
                                                    <input type="hidden" name="TitleCode" id="hid_pat_title" value="" />
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-info" id="btn_pat_title" onclick-xguna="Global.pop_item_select('pat_title');"><span class="fa fa-ellipsis-h"></span></button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <small for="txt_pat_name">Name</small>
                                                <input class="form-control form-mandatory" name="Name" id="txt_pat_name" placeholder="" type="text" required>
                                            </div>
                                            <div class="col-md-3">
                                                <small for="cmb_pat_sex">Sex</small>
                                                <select id="cmb_pat_sex" name="Sex" class="form-control form-mandatory" required>
                                                    <option value="">- Select Sex -</option>
                                                    <option value="M">Male</option>
                                                    <option value="F">Female</option>
                                                    <option value="U">Unknwon</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <small for="cmb_pat_idtype">IC Type</small>
                                                <!-- <select id="cmb_pat_idtype" name="ID_Type" class="form-control">
                                                    <option value="">- Select IC Type -</option>
                                                </select> -->
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-mandatory" name="txt_ID_Type" id="txt_ID_Type" required>
                                                    <input type="hidden" name="ID_Type" id="hid_ID_Type" value="" />
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-info" id="btn_ID_Type" onclick-xguna="Global.pop_item_select('ID_Type');"><span class="fa fa-ellipsis-h"></span> </button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <small for="txt_pat_newic">New IC (eg 690101086649)</small>
                                                <input class="form-control form-mandatory ic-group" name="Newic" id="txt_pat_newic" placeholder="" type="text" onkeypress="return isNumberKey(event);">
                                            </div>
                                            <div class="col-md-3">
                                                <small for="txt_pat_oldic">Old IC</small>
                                                <input class="form-control form-mandatory ic-group" name="Oldic" id="txt_pat_oldic" placeholder="" type="text">
                                            </div>
                                            <div class="col-md-3">
                                                <small for="txt_pat_idnumber">Other (eg Passport Number)</small>
                                                <input class="form-control form-mandatory ic-group" name="idnumber" id="txt_pat_idnumber" placeholder="" type="text">
                                            </div>                                              
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <small for="txt_pat_dob">DOB - Age</small>
                                        <div class="input-group">
                                            <input class="form-control form-mandatory" name="DOB" id="txt_pat_dob" placeholder="" type="date" required>
                                            <span class="input-group-addon" style="background-color:transparent; border-color: transparent" style="width:10px;">&mdash;</span>
                                            <input class="form-control" style="width:50px;" name="txt_pat_age" id="txt_pat_age" placeholder="" type="text" disabled>
                                        </div>
                                    </div>                                          
                                    <div class="col-md-2">
                                        <small for="cmb_pat_racecode">Race</small>
                                        <!-- <select id="cmb_pat_racecode" name="RaceCode" class="form-control form-mandatory">
                                            <option value="">- Select Race -</option>
                                        </select> -->
                                        <div class="input-group">
                                            <input type="text" class="form-control form-mandatory" name="txt_RaceCode" id="txt_RaceCode" required>
                                            <input type="hidden" name="RaceCode" id="hid_RaceCode" value="" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_RaceCode" onclick-xguna="Global.pop_item_select('RaceCode');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <small for="cmb_pat_religion">Religion</small>
                                        <!-- <select id="cmb_pat_religion" name="Religion" class="form-control form-mandatory">
                                            <option value="">- Select Religion -</option>
                                        </select> -->
                                        <div class="input-group">
                                            <input type="text" class="form-control form-mandatory" name="txt_Religion" id="txt_Religion" required>
                                            <input type="hidden" name="Religion" id="hid_Religion" value="" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_Religion" onclick-xguna="Global.pop_item_select('Religion');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <small for="cmb_pat_category">Category</small>
                                        <select id="cmb_pat_category" name="pat_category" class="form-control form-mandatory" required="" aria-required="true">
                                            <option value="LOCAL">LOCAL</option>
                                            <option value="EXPATRIATE">EXPATRIATE</option>
                                            <option value="TOURIST">TOURIST</option>
                                        </select>
                                    </div>

                                </div>
                                
                                <div class="form-group">                                            
                                    <div class="col-md-6">
                                        <small for="txt_pat_citizen">Citizenship</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-mandatory" name="txt_pat_citizen" id="txt_pat_citizen" required>
                                            <input type="hidden" name="Citizencode" id="hid_pat_citizen" value="" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_pat_citizen" onclick-xguna="Global.pop_item_select('pat_citizen');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <small for="txt_pat_langcode">Language</small>
                                        <!-- <select id="cmb_pat_langcode" name="LanguageCode" class="form-control">
                                            <option value="">- Select Language -</option>
                                        </select> -->
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="txt_LanguageCode" id="txt_LanguageCode">
                                            <input type="hidden" name="LanguageCode" id="hid_LanguageCode" value="" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_LanguageCode" onclick-xguna="Global.pop_item_select('LanguageCode');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>  

                                <div class="form-group">
                                    <div class="col-md-4">
                                        <small for="txt_pat_email">Email</small>
                                        <input class="form-control" name="Email" id="txt_pat_email" placeholder="" type="email">
                                    </div>
                                    <div class="col-md-4">
                                        <small for="txt_pat_loginid">login id</small>
                                        <input class="form-control" name="loginid" id="txt_pat_loginid" placeholder="" type="text">
                                    </div>
                                     <div class="col-md-4">
                                        <small for="txt_pat_iPesakit">iPesakit</small>
                                        <input class="form-control" name="iPesakit" id="txt_pat_iPesakit" placeholder="" type="text">
                                    </div>
                                </div> 
                                
                                <!-- Tab content end -->
                            </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="panel panel-default" style="position: relative;">
                            <div class="panel-heading clearfix collapsed" id="toggle_tabAddr" data-toggle="collapse" data-target="#tabAddr">
                                <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                                <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
                                <div class="pull-right" style="position: absolute; padding: 5px 0 0 5px; left: 10px; top: 0px;">
                                    <h5><strong>ADDRESS</strong></h5>
                                </div> 
                            </div>

                            <div id="tabAddr" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <div class="form-group">
                                    <!--div class="row"-->
                                    <div class="col-md-12">
                                        <!-- <p><strong>ADDRESS</strong></p>                                                      -->
                                        <div class="tab-v2">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a href="#addr_current" data-toggle="tab">CURRENT</a></li>
                                                <li><a href="#addr_office" data-toggle="tab">OFFICE</a></li>
                                                <li><a href="#addr_home" data-toggle="tab">HOME</a></li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane fade in active" id="addr_current">
                                                    <!--div class="row"-->
                                                    <br />
                                                    <div class="col-md-4">
                                                        <p>CURRENT ADDRESS</p>
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
                                                        <p>POSTCODE<input name="Postcode" id="txt_pat_currpostcode" class="form-control form-mandatory" type="text" required /></p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small for="txt_pat_area">AREA</small>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control form-mandatory" name="txt_pat_area" id="txt_pat_area" required>
                                                            <input type="hidden" name="AreaCode" id="hid_pat_area" value="" />
                                                            <span class="input-group-btn">
                                                                <button type="button" class="btn btn-info" id="btn_pat_area" onclick-xguna="Global.pop_item_select('pat_area');"><span class="fa fa-ellipsis-h"></span> </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <p></p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p>CONTACT NO</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p>HOUSE<input name="telh" id="txt_pat_telh" class="form-control form-mandatory phone-group" type="text"/></p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p>MOBILE<input name="telhp" id="txt_pat_telhp" class="form-control form-mandatory phone-group" type="text"/></p>
                                                    </div>
                                                    <!--/div-->
                                                </div>
                                                <div class="tab-pane fade in" id="addr_office">
                                                    <!--div class="row"-->
                                                    <br />
                                                    <div class="col-md-4">
                                                        <p>OFFICE ADDRESS</p>
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
                                                        <p>POSTCODE<input name="OffPostcode" id="txt_pat_offpostcode" class="form-control" type="text" /></p>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <p></p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p>CONTACT NO</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p>OFFICE TEL<input name="txt_pat_telo" id="txt_pat_telo" class="form-control" type="text" /></p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p>OFFFICE EXT<input name="txt_pat_teloext" id="txt_pat_teloext" class="form-control" type="text" /></p>
                                                    </div>
                                                    <!--/div-->
                                                </div>
                                                <div class="tab-pane fade in" id="addr_home">
                                                    <!--div class="row"-->
                                                    <br />
                                                    <div class="col-md-4">
                                                        <p>HOME ADDRESS</p>
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
                                                        <p>POSTCODE<input name="pPostCode" id="txt_pat_ppostcode" class="form-control" type="text" /></p>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <p></p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p>CONTACT NO</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p>HOME TEL<input name="txt_pat_ptel" id="txt_pat_ptel" class="form-control" type="text" /></p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p>HOME MOBILE<input name="txt_pat_ptelhp" id="txt_pat_ptelhp" class="form-control" type="text" /></p>
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
                            <div class="panel-heading clearfix collapsed" id="toggle_tabCorp" data-toggle="collapse" data-target="#tabCorp">
                                <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                                <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
                                <div class="pull-right" style="position: absolute; padding: 5px 0 0 5px; left: 10px; top: 0px;">
                                    <h5><strong>CORPORATE INFO</strong></h5>
                                </div> 
                            </div>

                            <div id="tabCorp" class="panel-collapse collapse">
                            <div class="panel-body">

                                <div class="form-group">
                                    <div class="col-md-offset-4 col-md-6">
                                        <small for="txt_payer_company">Company Name</small>
                                        <!--input class="form-control" id="corpcomp" placeholder="" type="text"-->
                                        <div class="input-group">
                                            <input type="text" class="form-control form-mandatory" name="txt_payer_company" id="txt_payer_company">
                                            <input type="hidden" name="CorpComp" id="hid_payer_company" value="" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_payer_company" onclick-xguna="Global.pop_item_select('payer_company');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        
                                    </div>
                                </div>                                      
                                <div class="form-group">
                                    <div class="col-md-offset-4 col-md-3">
                                        <small for="txt_payer_staffid">Staff ID</small>
                                        <input class="form-control" name="Staffid" id="txt_payer_staffid" placeholder="" type="text">
                                    </div>
                                     <div class="col-md-3">
                                        <small for="txt_payer_occupation">Occupation</small>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-mandatory" name="txt_pat_occupation" id="txt_pat_occupation">
                                            <input type="hidden" name="OccupCode" id="hid_pat_occupation" value="" />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" id="btn_pat_occupation" onclick-xguna="Global.pop_item_select('pat_occupation');"><span class="fa fa-ellipsis-h"></span> </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-4 col-md-6">
                                        <small for="txt_payer_email_official">Company's Email</small>
                                        <input class="form-control" name="Email_official" id="txt_payer_email_official" placeholder="" type="email">
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
                            <div class="panel-heading clearfix collapsed" id="toggle_tabNok_pat" data-toggle="collapse" data-target="#tabNok_pat">

                            <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                            <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
                            <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
                                <h5><strong>NEXT OF KIN</strong></h5>
                            </div> 
                            </div>

                            <div id="tabNok_pat" class="panel-collapse collapse">
                            <div class="panel-body form-horizontal">
                                <div class="col-md-6">
                                    <div id="jqGrid_nok_pat_c">
                                        <div class='col-md-12' style="padding:0 0 15px 0">
                                            <table id="jqGrid_nok_pat" class="table table-striped"></table>
                                            <div id="jqGridPager_nok_pat"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="form_nok_pat">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                        <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_nok_pat">
                                            <button type="button" class="btn btn-default" id="add_nok_pat">
                                                <span class="fa fa-plus-square-o fa-lg"></span> Add
                                            </button>
                                            <button type="button" class="btn btn-default" id="edit_nok_pat">
                                                <span class="fa fa-edit fa-lg"></span> Edit
                                            </button>
                                            <button type="button" class="btn btn-default" id="save_nok_pat">
                                                <span class="fa fa-save fa-lg"></span> Save
                                            </button>
                                            <button type="button" class="btn btn-default" id="cancel_nok_pat" >
                                                <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
                                            </button>
                                        </div></div>
                                    </div>

                                    <input id="nok_idno_pat" name="nok_idno_pat" type="hidden">

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <small for="nok_name_pat">Name</small>
                                            <input id="nok_name_pat" name="nok_name_pat" type="text" class="form-control" data-validation="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <small for="nok_addr1_pat">Address</small>
                                            <input id="nok_addr1_pat" name="nok_addr1_pat" type="text" class="form-control" style="margin-bottom: 2px">
                                            <input id="nok_addr2_pat" name="nok_addr2_pat" type="text" class="form-control" style="margin-bottom: 2px">
                                            <input id="nok_addr3_pat" name="nok_addr3_pat" type="text" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <small for="nok_postcode_pat">Postcode</small>
                                            <input id="nok_postcode_pat" name="nok_postcode_pat" type="text" class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <small for="nok_telh_pat">Tel (H)</small>
                                            <input id="nok_telh_pat" name="nok_telh_pat" type="text" class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <small for="nok_telo_pat">Tel (O)</small>
                                            <input id="nok_telo_pat" name="nok_telo_pat" type="text" class="form-control" rdonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <small for="nok_relate_pat">Relationship</small>
                                            <div class='input-group'>
                                                <input id="nok_relate_pat" name="nok_relate_pat" type="text" class="form-control uppercase" data-validation="required">
                                                <a class='input-group-addon btn btn-info'><span class='fa fa-ellipsis-h'></span></a>
                                            </div>
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <small for="nok_telhp_pat">Tel (H/P)</small>
                                            <input id="nok_telhp_pat" name="nok_telhp_pat" type="text" class="form-control" rdonly>
                                        </div>
                                        <div class="col-md-2">
                                            <small for="nok_ext_pat">Ext</small>
                                            <input id="nok_ext_pat" name="nok_ext_pat" type="text" class="form-control"  rdonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <small for="nok_computerid">Computer ID</small>
                                            <input id="nok_computerid" name="nok_computerid" type="text" class="form-control"  rdonly>
                                        </div>
                                        <div class="col-md-4">
                                            <small for="nok_lastuser">Last User</small>
                                            <input id="nok_lastuser" name="nok_lastuser" type="text" class="form-control"  rdonly>
                                        </div>
                                        <div class="col-md-4">
                                            <small for="nok_lastupdate">Last Update</small>
                                            <input id="nok_lastupdate" name="nok_lastupdate" type="text" class="form-control"  rdonly>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            </div>
                        </div>

                        <!-- Emergency -->
                        <div class="panel panel-default" style="position: relative;">
                            <div class="panel-heading clearfix collapsed" id="toggle_tabNok_emr" data-toggle="collapse" data-target="#tabNok_emr">

                            <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                            <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
                            <div class="pull-right" style="position: absolute; padding: 0 0 0 0; left: 10px; top: 0px;">
                                <h5><strong>EMERGENCY</strong></h5>
                            </div> 
                            </div>

                            <div id="tabNok_emr" class="panel-collapse collapse">
                            <div class="panel-body form-horizontal">
                                <div class="col-md-6">
                                    <div id="jqGrid_nok_emr_c">
                                        <div class='col-md-12' style="padding:0 0 15px 0">
                                            <table id="jqGrid_nok_emr" class="table table-striped"></table>
                                            <div id="jqGridPager_nok_emr"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="form_nok_emr">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                        <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                                        id="btn_grp_edit_nok_emr">
                                            <button type="button" class="btn btn-default" id="add_nok_emr">
                                                <span class="fa fa-plus-square-o fa-lg"></span> Add
                                            </button>
                                            <button type="button" class="btn btn-default" id="edit_nok_emr">
                                                <span class="fa fa-edit fa-lg"></span> Edit
                                            </button>
                                            <button type="button" class="btn btn-default" id="save_nok_emr">
                                                <span class="fa fa-save fa-lg"></span> Save
                                            </button>
                                            <button type="button" class="btn btn-default" id="cancel_nok_emr" >
                                                <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel
                                            </button>
                                        </div></div>
                                    </div>

                                    <input id="emr_idno" name="emr_idno" type="hidden">

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <small for="emr_name">Name</small>
                                            <input id="emr_name" name="emr_name" type="text" class="form-control" data-validation="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-8">
                                            <small for="emr_relate">Relationship</small>
                                            <div class='input-group'>
                                                <input id="emr_relate" name="emr_relate" type="text" class="form-control uppercase">
                                                <a class='input-group-addon btn btn-info'><span class='fa fa-ellipsis-h'></span></a>
                                            </div>
                                            <span class="help-block"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <small for="emr_email">E-mail</small>
                                            <input id="emr_email" name="emr_email" type="text" class="form-control" rdonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <small for="emr_telhp">Handphone</small>
                                            <input id="emr_telhp" name="emr_telhp" type="text" class="form-control" data-validation="required">
                                        </div>

                                        <div class="col-md-offset-3 col-md-3">
                                            <small for="emr_telh">Telephone</small>
                                            <input id="emr_telh" name="emr_telh" type="text" class="form-control">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            </div>
                        </div>

                        <!-- pat rec -->
                        <div class="panel panel-default" style="position: relative;">
                            <div class="panel-heading clearfix collapsed" id="toggle_tabPatrec" data-toggle="collapse" data-target="#tabPatrec">
                                <i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
                                <i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i >
                                <div class="pull-right" style="position: absolute; padding: 5px 0 0 5px; left: 10px; top: 0px;">
                                    <h5><strong>PATIENT RECORD</strong></h5>
                                </div> 
                            </div>

                            <div id="tabPatrec" class="panel-collapse collapse">
                            <div class="panel-body">

                                <div class="form-group">
                                    <div class="col-md-2">
                                        <small for="cmb_pat_active">Active</small>
                                        <select id="cmb_pat_active" name="Active" class="form-control form-mandatory" required>
                                            <option value="1" selected="selected">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <small for="cmb_pat_Confidential">Confidential</small>
                                        <select id="cmb_pat_Confidential" name="Confidential" class="form-control form-mandatory" required>
                                            <option value="1">Yes</option>
                                            <option value="0" selected="selected">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <small for="cmb_pat_MRFolder">MR Folder</small>
                                        <select id="cmb_pat_MRFolder" name="MRFolder" class="form-control form-mandatory" required>
                                            <option value="1" selected="selected">Yes</option>
                                            <option value="0" >No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <small for="txt_bloodgroup">Blood Group</small>
                                        <input class="form-control" name="bloodgrp" id="txt_bloodgroup" placeholder="" type="text">
                                    </div>
                                    <div class="col-md-3">
                                        <small for="txt_newmrn">New Mrn</small>
                                        <input class="form-control" name="NewMrn" id="txt_newmrn" placeholder="" type="text">
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
                <button id="btn_register_close" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="btn_register_patient" type="button" class="btn btn-success" data-oper="edit">Save changes</button>
            </div>
        </div>
    </form>
</div>

@include('hisdb.pat_mgmt.itemselector')

@endsection

@section('js')
<script type="text/javascript" src="{{asset('js/hisdb/pat_mgmt/pat_nok.js')}}"></script>
<script type="text/javascript" src="{{asset('js/hisdb/pat_mgmt/pat_emr.js')}}"></script>
<script type="text/javascript" src="{{asset('js/hisdb/pat_mgmt/textfield_modal.js')}}"></script>
<script type="text/javascript" src="{{asset('js/hisdb/pat_mgmt/biodata_iframe.js?v=1.1')}}"></script>
@endsection
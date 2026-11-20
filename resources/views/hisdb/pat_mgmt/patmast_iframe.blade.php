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
    .dimmer-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.7); /* Slightly darker for better text contrast */
        z-index: 1060; /* Higher than default navbars and modals */
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        
        /* Flexbox to center the text and spinner */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: #ffffff; /* White text */
    }

    .dimmer-overlay.active {
        opacity: 1;
        visibility: visible;
    }
</style>

<script>
    var src_from = "{{$src_from}}";
    var src_idno = "{{$src_idno}}";
    var hide_close = "{{request()->get('hide_close')}}";

    @if(empty($pat_mast_data))
        var pat_mast_data = null;
    @else
        var pat_mast_data = {
            @foreach($pat_mast_data as $key => $val) 
                '{{$key}}' : `{!!str_replace('`', '', $val)!!}`,
            @endforeach 
        };
    @endif
</script>
@endsection

@section('body')
<div id="pageDimmer" class="dimmer-overlay">
    <div class="text-center">
        <!-- Your Custom Loading Text -->
        <h3 class="fw-normal" id="textDimmer">Reading Mykad, please wait...</h3>
    </div>
</div>
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
                <button id="btn_mykad" type="button" class="btn btn-default btn-md" style="
                        float: left;
                        border-radius: 5px;
                        font-size: 11px;
                        float: left;
                        margin: 0px;
                        border-radius: 5px;
                        padding: 1px 15px;
                        @if(!empty($pat_mast_data))display: none;@endif
                        ">
                    <img src="img/mykad.png" width="35"> My Kad
                </button>
                <button type="button" class="" data-dismiss="modal" aria-label="Close" id="close_iframe" style="float: right;
                        color: white;
                        background: #d34242;
                        border-radius: 5px;">
                  <span class="glyphicon glyphicon-remove" aria-hidden="true" style="top: 3px;"></span>
                </button>
                <div class="form-group" style="margin: 0px">
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

                        

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button id="btn_register_close" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="btn_register_patient" type="button" class="btn btn-success" data-oper="edit" data-chk_duplicate="true">Save changes</button>
            </div>
        </div>
    </form>
</div>

<div id="mdl_existing_record" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;background-color: rgba(0, 0, 0, 0.3);">
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
                <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_duplicate_cancel">Cancel</button>
                <button type="button" class="btn btn-success" id="btn_duplicate_proceed">Proceed</button>
            </div>
        </div>
    </div>
</div>

@include('hisdb.pat_mgmt.itemselector')

@endsection

@section('js')
<script type="text/javascript" src="{{asset('js/hisdb/pat_mgmt/pat_nok.js')}}"></script>
<script type="text/javascript" src="{{asset('js/hisdb/pat_mgmt/pat_emr.js')}}"></script>
<script type="text/javascript" src="{{asset('js/hisdb/pat_mgmt/textfield_modal.js')}}"></script>
<script type="text/javascript" src="{{asset('js/hisdb/pat_mgmt/biodata_iframe.js?v=1.3')}}"></script>
@endsection
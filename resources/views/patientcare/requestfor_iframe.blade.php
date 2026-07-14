@extends('hisdb.pat_mgmt.semantic_iframe_main')

@section('title', 'Request for Iframe')

@section('css')

<link rel="stylesheet" type="text/css" href="{{ asset('patientcare/css/main.css') }}">
<link rel="stylesheet" href="{{asset('patientcare/css/doctornote.css') }}">
<style>
    #allmodal{
        padding: 10px ;
        height: 100vh;
        width: 100vw;
        overflow-x: hidden;
        margin: 0px ;
    }
    
    .preloader {
          width: 100%;
          height: 100%;
          top: 0;
          position: fixed;
          z-index: 99999;
          background: #fff;
    }
    .cssload-speeding-wheel {
        position: absolute;
        top: calc(50% - 3.5px);
        left: calc(50% - 3.5px);
        width: 31px;
        height: 31px;
        margin: 0 auto;
        border: 2px solid rgba(97,100,193,0.98);
        border-radius: 50%;
        border-left-color: transparent;
        border-right-color: transparent;
        animation: cssload-spin 425ms infinite linear;
        -o-animation: cssload-spin 425ms infinite linear;
        -ms-animation: cssload-spin 425ms infinite linear;
        -webkit-animation: cssload-spin 425ms infinite linear;
        -moz-animation: cssload-spin 425ms infinite linear;
    }
    @keyframes cssload-spin {
        100%{ transform: rotate(360deg); transform: rotate(360deg); }
    }

    @-o-keyframes cssload-spin {
        100%{ -o-transform: rotate(360deg); transform: rotate(360deg); }
    }

    @-ms-keyframes cssload-spin {
        100%{ -ms-transform: rotate(360deg); transform: rotate(360deg); }
    }

    @-webkit-keyframes cssload-spin {
        100%{ -webkit-transform: rotate(360deg); transform: rotate(360deg); }
    }

    @-moz-keyframes cssload-spin {
        100%{ -moz-transform: rotate(360deg); transform: rotate(360deg); }
    }

    .red.ui.right.labeled.input input{
        color: white !important;
        border-color: red !important;
        background-color: red !important;
    }
    
    .red.ui.table tr{
        color: white;
        background-color: red !important;
    }
    
    .red.ui.action.input input{
        color: white !important;
        border-color: red !important;
        background-color: red !important;
    }
    
    .yellow.ui.right.labeled.input input{
        color: black !important;
        border-color: #9e9e00 !important;
        background-color: yellow !important;
    }
    
    .yellow.ui.table tr{
        background-color: yellow !important;
    }
    
    .yellow.ui.action.input input{
        color: black !important;
        border-color: #9e9e00 !important;
        background-color: yellow !important;
    }
    
    .green.ui.right.labeled.input input{
        color: white !important;
        border-color: green !important;
        background-color: green !important;
    }
    
    .green.ui.table tr{
        color: white;
        background-color: green !important;
    }
    
    .green.ui.action.input input{
        color: white !important;
        border-color: green !important;
        background-color: green !important;
    }
</style>

<script>
    @if(empty($pat_mast_data))
        var pat_mast_data = null;
    @else
        var pat_mast_data = {
            @foreach($pat_mast_data as $key => $val) 
                '{{$key}}' : `{!!str_replace('`', '', $val)!!}`,
            @endforeach 
        };
    @endif


    @if(empty($episode_data))
        var episode_data = null;
    @else
        var episode_data = {
            @foreach($episode_data as $key => $val) 
                '{{$key}}' : `{!!str_replace('`', '', $val)!!}`,
            @endforeach 
        };
    @endif
</script>
@endsection

@section('body')
<div class="preloader">
    <div class="cssload-speeding-wheel"></div>
</div>

<input type="hidden" id="_mrn" value="{{$mrn}}">
<input type="hidden" id="_episno" value="{{$episno}}">
<input type="hidden" id="_phase" value="{{$phase}}">
<input id="age_requestFor" name="age_requestFor" type="hidden" value="{{$pat_mast_data->age}}">
<input id="ptname_requestFor" name="ptname_requestFor" type="hidden" value="{{$pat_mast_data->Name}}">
<input id="preg_requestFor" name="preg_requestFor" type="hidden" value="{{$episode_data->pregnant}}">
<input id="ic_requestFor" name="ic_requestFor" type="hidden" value="{{$pat_mast_data->Newic}}">
<input id="doctorname_requestFor" name="doctorname_requestFor" type="hidden" value="{{$episode_data->doctorname}}">

<div class="ui column">
    <form id="formRequestFor">
        <input id="mrn_requestFor" name="mrn_requestFor" type="hidden" value="{{$mrn}}">
        <input id="episno_requestFor" name="episno_requestFor" type="hidden" value="{{$episno}}">
    </form>
    
    <div id="requestFor" class="ui segments" style="margin:0px;border: none;padding-bottom: 0px;">
        <!-- <div class="ui secondary segment">REQUEST FOR</div> -->
        <div class="ui top attached tabular menu">
            <a class="item active" data-tab="otbookReqFor" id="navtab_otbookReqFor">Ward</a>
            <a class="item" data-tab="radReqFor" id="navtab_radReqFor">Radiology</a>
            <a class="item" data-tab="physioReqFor" id="navtab_physioReqFor">Rehab</a>
            <a class="item" data-tab="dressingReqFor" id="navtab_dressingReqFor">Dressing</a>
            <a class="item" data-tab="referral_letter_reqfor" id="navtab_referral_letter_reqfor">Referral Letter</a>
            <a class="item" data-tab="card_noninv_reqfor" id="navtab_card_noninv_reqfor">Cardiology<br>Non-Invasive</a>
        </div>
        
        <div class="ui bottom attached tab raised segment active" data-tab="otbookReqFor">
            <div class="ui segments" style="position: relative;">
                <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
                    <div class="ui small blue icon buttons" id="btn_grp_edit_otbookReqFor" style="position: absolute;
                        padding: 0 0 0 0;
                        right: 40px;
                        top: 9px;
                        z-index: 2;">
                        <button class="ui button" id="new_otbookReqFor"><span class="fa fa-plus-square-o"></span>New</button>
                        <button class="ui button" id="edit_otbookReqFor"><span class="fa fa-edit fa-lg"></span>Edit</button>
                        <button class="ui button" id="save_otbookReqFor"><span class="fa fa-save fa-lg"></span>Save</button>
                        <button class="ui button" id="cancel_otbookReqFor"><span class="fa fa-ban fa-lg"></span>Cancel</button>
                        <button class="ui button" id="otbookReqFor_chart"><span class="fa fa-print fa-lg"></span>Print</button>
                    </div>
                </div>
                <div class="ui segment">
                    <div class="ui grid">
                        <form id="formOTBookReqFor" class="floated ui form sixteen wide column">
                            <div class='ui grid' style="padding: 15px 30px;">
                                @include('patientcare.otbook_vitalsign')
                                
                                <div class="sixteen wide column">
                                    <div class="ui segments">
                                        <!-- <div class="ui secondary segment">Ward / OT</div> -->
                                        <div class="ui segment">
                                            <div class="ui grid">
                                                <div class="sixteen wide column centered grid" style="padding: 14px 14px 0px 150px;">
                                                    <div class="ui grid">
                                                        <div class="four wide column" style="padding-top:8px">
                                                            <div class="field">
                                                                <label>iPesakit</label>
                                                            </div>
                                                        </div>
                                                        <div class="twelve wide column">
                                                            <div class="field eight wide column">
                                                                <input type="text" id="otReqFor_iPesakit" name="iPesakit">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="ui grid">
                                                        <div class="four wide column" style="padding-top:8px">
                                                            <div class="field">
                                                                <label for="req_type">Type</label>
                                                            </div>
                                                        </div>
                                                        <div class="twelve wide column">
                                                            <div class="field eight wide column">
                                                                <div class="inline fields">
                                                                    <div class="field">
                                                                        <div class="ui radio checkbox">
                                                                            <input type="radio" id="req_type_ward" name="req_type" value="WARD">
                                                                            <label for="req_type_ward">Ward</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="field" @if($phase == "ED"){{"style=display:none"}}@endif>
                                                                        <div class="ui radio checkbox">
                                                                            <input type="radio" id="req_type_ot" name="req_type" value="OT">
                                                                            <label for="req_type_ot">OT</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="ui grid" style="display:none">
                                                        <div class="field two wide column" style="margin: 0px;padding-top:8px">
                                                            <label>Bed</label>
                                                            <input id="ReqFor_bed" name="ReqFor_bed" type="text" rdonly>
                                                        </div>
                                                        <div class="field two wide column" style="margin: 0px;padding-top:8px">
                                                            <label>Ward</label>
                                                            <input id="ReqFor_ward" name="ReqFor_ward" type="text" rdonly>
                                                        </div>
                                                        <div class="field three wide column" style="margin: 0px;padding-top:8px">
                                                            <label>Room</label>
                                                            <input id="ReqFor_room" name="ReqFor_room" type="text" rdonly>
                                                        </div>
                                                        <div class="field three wide column" style="margin: 0px;padding-top:8px">
                                                            <label>Bed Type</label>
                                                            <input id="ReqFor_bedtype" name="ReqFor_bedtype" type="text" rdonly>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="ui grid">
                                                        <div class="four wide column" style="padding-top:8px">
                                                            <div class="inline field">
                                                                <label>Date for OP</label>
                                                            </div>
                                                        </div>
                                                        <div class="twelve wide column">
                                                            <div class="inline field">
                                                                <input id="op_date" name="op_date" type="date" value="{{$episode_data->reg_date}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="ui grid">
                                                        <div class="four wide column" style="padding-top:8px">
                                                            <div class="field">
                                                                <label>Type of Operation / Procedure</label>
                                                            </div>
                                                        </div>
                                                        <div class="twelve wide column">
                                                            <div class="field eight wide column">
                                                                1.  <input id="ReqFor_oper_type" name="oper_type" type="text" style="width: 350px;">
                                                            </div>
                                                            <div class="field eight wide column">
                                                                2.  <input id="ReqFor_oper_type2" name="oper_type2" type="text" style="width: 350px;">
                                                            </div>
                                                            <div class="field eight wide column">
                                                                3.  <input id="ReqFor_oper_type3" name="oper_type3" type="text" style="width: 350px;">
                                                            </div>
                                                            <div class="field eight wide column">
                                                                4.  <input id="ReqFor_oper_type4" name="oper_type4" type="text" style="width: 350px;">
                                                            </div>
                                                            <div class="field eight wide column">
                                                                5.  <input id="ReqFor_oper_type5" name="oper_type5" type="text" style="width: 350px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="ui grid">
                                                        <div class="four wide column" style="padding-top:8px">
                                                            <div class="field">
                                                                <label for="adm_type">Type of Admission</label>
                                                            </div>
                                                        </div>
                                                        <div class="twelve wide column">
                                                            <div class="field eight wide column">
                                                                <div class="inline fields">
                                                                    <div class="field">
                                                                        <div class="ui radio checkbox">
                                                                            <input type="radio" name="adm_type" value="DC" id="req_adm_dc">
                                                                            <label for="req_adm_dc">Day Case</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="field">
                                                                        <div class="ui radio checkbox">
                                                                            <input type="radio" name="adm_type" value="IP" id="req_adm_ip">
                                                                            <label for="req_adm_ip">In Patient</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="ui grid" style="display:none">
                                                        <div class="four wide column" style="padding-top:8px">
                                                            <div class="field">
                                                                <label for="anaesthetist">Anaesthetist</label>
                                                            </div>
                                                        </div>
                                                        <div class="twelve wide column">
                                                            <div class="field eight wide column">
                                                                <div class="inline fields">
                                                                    <div class="field">
                                                                        <div class="ui radio checkbox">
                                                                            <input type="radio" name="anaesthetist" value="1" id="req_anas_req">
                                                                            <label for="req_anas_req">Required</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="field">
                                                                        <div class="ui radio checkbox">
                                                                            <input type="radio" name="anaesthetist" value="0" id="req_anas_notreq">
                                                                            <label for="req_anas_notreq">Not Required</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="ui grid">
                                                        <div class="four wide column" style="padding-top:8px">
                                                            <div class="field">
                                                                <label>Diagnosis</label>
                                                            </div>
                                                        </div>
                                                        <div class="twelve wide column">
                                                           <div class="field nine wide column">
                                                                <textarea id="otReqFor_diagnosis" name="ot_diagnosis" type="text" rows="5"></textarea>
                                                                
                                                                <div class="inline field" style="padding-top: 15px;">
                                                                    <label>Diagnosed By</label>
                                                                    <input id="otReqFor_diagnosedby" name="ot_diagnosedby" type="text" style="width: 320px; text-transform: uppercase;" value="{{ session('username') }}" rdonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="ui grid">
                                                        <div class="four wide column" style="padding-top:8px">
                                                            <div class="field">
                                                                <label>Special remarks / instructions for medication or any related to case</label>
                                                            </div>
                                                        </div>
                                                        <div class="twelve wide column">
                                                            <textarea id="otReqFor_remarks" name="ot_remarks" type="text" rows="5" data-validation="required"></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="ui grid">
                                                        <div class="equal width row">
                                                            <div class="column">
                                                                <div class="inline field">
                                                                    <label>Doctor's Name</label>
                                                                    <input id="otReqFor_doctorname" name="ot_doctorname" type="text" style="width: 350px; text-transform: uppercase;">
                                                                </div>
                                                            </div>
                                                            <div class="column">
                                                                <div class="inline field" style="padding-bottom:8px">
                                                                    <label>Entered By</label>
                                                                    <input id="otReqFor_lastuser" name="ot_lastuser" type="text" style="width: 350px; text-transform: uppercase;" value="{{ session('username') }}" rdonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="sixteen wide column" id="ReqFor_Bed_div" >
                                    <div class="ui segments">
                                        <div class="ui secondary segment">BED</div>
                                        <iframe id='wardbook_iframe' src='' style="height: calc(65vh);width: 100%; border: none;overflow:auto;"></iframe>
                                    </div>
                                </div>
                                
                                <div class="sixteen wide column" id="ReqFor_OT_div" style="display: none;">
                                    <div class="ui segments">
                                        <div class="ui secondary segment">OT</div>
                                        <div class="ui segment">
                                            <div class="ui grid">
                                                <iframe id='otbook_iframe' src='' style="height: calc(95vh);width: 100%; border: none;"></iframe>
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
        
        <div id="radiology" class="ui bottom attached tab raised segment" data-tab="radReqFor">
            @include('hisdb.radiology.radiology_inside_iframe',['radiology_inside_iframe_phase'=>'requestfor'])
        </div>
        
        <div class="ui bottom attached tab raised segment" data-tab="physioReqFor">
            <div class="ui segments" style="position: relative;">
                <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
                    <div class="ui small blue icon buttons" id="btn_grp_edit_physioReqFor" style="position: absolute;
                        padding: 0 0 0 0;
                        right: 40px;
                        top: 9px;
                        z-index: 2;">
                        <button class="ui button" id="new_physioReqFor"><span class="fa fa-plus-square-o"></span>New</button>
                        <button class="ui button" id="edit_physioReqFor"><span class="fa fa-edit fa-lg"></span>Edit</button>
                        <button class="ui button" id="save_physioReqFor"><span class="fa fa-save fa-lg"></span>Save</button>
                        <button class="ui button" id="cancel_physioReqFor"><span class="fa fa-ban fa-lg"></span>Cancel</button>
                        <button class="ui button" id="physioReqFor_chart"><span class="fa fa-print fa-lg"></span>Print</button>
                    </div>
                </div>
                <div class="ui segment">
                    <div class="ui grid">
                        <form id="formPhysioReqFor" class="floated ui form sixteen wide column">
                            <div class='ui grid' style="padding: 15px 30px;">
                                <div class="four wide column" style="padding: 14px 14px 0px 150px;">
                                    <div class="field">
                                        <label>Date</label>
                                    </div>
                                </div>
                                
                                <div class="twelve wide column" style="padding: 14px 14px 0px 14px;">
                                    <div class="field eight wide column">
                                        <input id="ReqFor_req_date" name="req_date" type="date">
                                    </div>
                                </div>
                                
                                <div class="four wide column" style="padding: 14px 14px 0px 150px;">
                                    <div class="field">
                                        <label>Clinical Diagnosis</label>
                                    </div>
                                </div>
                                
                                <div class="twelve wide column" style="padding: 14px 14px 0px 14px;">
                                    <div class="field eight wide column">
                                        <textarea id="ReqFor_clinic_diag" name="clinic_diag" type="text" rows="5"></textarea>
                                    </div>
                                </div>
                                
                                <div class="four wide column" style="padding: 14px 14px 0px 150px;">
                                    <div class="field">
                                        <label>Relevant Finding(s)</label>
                                    </div>
                                </div>
                                
                                <div class="twelve wide column" style="padding: 14px 14px 0px 14px;">
                                    <div class="field eight wide column">
                                        <textarea id="ReqFor_findings" name="findings" type="text" rows="5"></textarea>
                                    </div>
                                </div>
                                
                                <div class="three wide column" style="padding: 14px 14px 0px 150px;">
                                    <div class="field">
                                        <label>Treatment</label>
                                    </div>
                                </div>
                                
                                <div class="thirteen wide column" style="padding: 14px 14px 0px 30px;">
                                    <div class="field eight wide column">
                                        <!-- <textarea id="phyReqFor_treatment" name="phy_treatment" type="text" rows="5"></textarea> -->
                                        <div class="ui form" id='ReqFor_treatment'>
                                            <div class="field" style="padding-top: 20px; text-align: left; color: red;">
                                                <p id="p_error_ReqForTreatment"></p>
                                            </div>
                                            
                                            <div class="grouped fields">
                                                <div class="field">
                                                    <div class="ui checkbox">
                                                        <input type="checkbox" name="tr_physio" id="ReqFor_tr_physio" value="1">
                                                        <label for="ReqFor_tr_physio">Physiotherapy</label>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <div class="ui checkbox">
                                                        <input type="checkbox" name="tr_occuptherapy" id="ReqFor_tr_occuptherapy" value="1">
                                                        <label for="ReqFor_tr_occuptherapy">Occupational Therapy</label>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <div class="ui checkbox">
                                                        <input type="checkbox" name="tr_respiphysio" id="ReqFor_tr_respiphysio" value="1">
                                                        <label for="ReqFor_tr_respiphysio">Respiratory Physiotherapy</label>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <div class="ui checkbox">
                                                        <input type="checkbox" name="tr_neuro" id="ReqFor_tr_neuro" value="1">
                                                        <label for="ReqFor_tr_neuro">Neuro Rehab</label>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <div class="ui checkbox">
                                                        <input type="checkbox" name="tr_splint" id="ReqFor_tr_splint" value="1">
                                                        <label for="ReqFor_tr_splint">Splinting</label>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <div class="ui checkbox">
                                                        <input type="checkbox" name="tr_speech" id="ReqFor_tr_speech" value="1">
                                                        <label for="ReqFor_tr_speech">Speech</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="four wide column" style="padding: 14px 14px 0px 150px;">
                                    <div class="field">
                                        <label>Remarks</label>
                                    </div>
                                </div>
                                
                                <div class="twelve wide column" style="padding: 14px 14px 0px 14px;">
                                    <div class="field eight wide column">
                                        <textarea id="ReqFor_remarks" name="remarks" type="text" rows="5"></textarea>
                                    </div>
                                </div>
                                
                                <div class="sixteen wide column centered grid" style="padding-left: 150px;">
                                    <div class="inline field">
                                        <label>Name of Requesting Doctor</label>
                                        <input id="phyReqFor_doctorname" name="phy_doctorname" type="text" style="width: 350px; text-transform: uppercase;">
                                    </div>
                                    
                                    <div class="inline field">
                                        <label>Entered By</label>
                                        <input id="phyReqFor_lastuser" name="phy_lastuser" type="text" style="width: 350px; text-transform: uppercase;">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="ui bottom attached tab raised segment" data-tab="dressingReqFor">
            <div class="ui segments" style="position: relative;">
                <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
                    <div class="ui small blue icon buttons" id="btn_grp_edit_dressingReqFor" style="position: absolute;
                        padding: 0 0 0 0;
                        right: 40px;
                        top: 9px;
                        z-index: 2;">
                        <button class="ui button" id="new_dressingReqFor"><span class="fa fa-plus-square-o"></span>New</button>
                        <button class="ui button" id="edit_dressingReqFor"><span class="fa fa-edit fa-lg"></span>Edit</button>
                        <button class="ui button" id="save_dressingReqFor"><span class="fa fa-save fa-lg"></span>Save</button>
                        <button class="ui button" id="cancel_dressingReqFor"><span class="fa fa-ban fa-lg"></span>Cancel</button>
                        <button class="ui button" id="dressingReqFor_chart"><span class="fa fa-print fa-lg"></span>Print</button>
                    </div>
                </div>
                <div class="ui segment">
                    <div class="ui grid">
                        <form id="formDressingReqFor" class="floated ui form sixteen wide column">
                            <div class='ui grid' style="padding: 15px 30px;">
                                <div class="sixteen wide column centered grid" style="padding: 14px 14px 0px 150px;">
                                    <div class="inline field">
                                        <label>Name</label>
                                        <input id="dressingReqFor_patientname" name="dressing_patientname" type="text" style="width: 350px;" rdonly>
                                    </div>
                                    
                                    <div class="inline field">
                                        <label>NRIC</label>
                                        <input id="ReqFor_patientnric" name="patientnric" type="text" style="width: 350px;" rdonly>
                                    </div>
                                </div>
                                
                                <div class="thirteen wide column" style="padding: 14px 200px 14px 150px;">
                                    <div class="ui segments">
                                        <div class="ui secondary segment">FREQUENCY</div>
                                        <div class="ui segment">
                                            <div class="inline field">
                                                <input id="ReqFor_od_dressing" name="od_dressing" type="number" style="width: 80px;">
                                                <label>OD Dressing</label>
                                            </div>
                                            
                                            <div class="inline field">
                                                <input id="ReqFor_bd_dressing" name="bd_dressing" type="number" style="width: 80px;">
                                                <label>BD Dressing</label>
                                            </div>
                                            
                                            <div class="inline field">
                                                <input id="ReqFor_eod_dressing" name="eod_dressing" type="number" style="width: 80px;">
                                                <label>EOD Dressing</label>
                                            </div>
                                            
                                            <div class="inline field">
                                                <input id="ReqFor_others_dressing" name="others_dressing" type="number" style="width: 80px;">
                                                <label>Others:</label>
                                                <input id="ReqFor_others_name" name="others_name" type="text" style="margin-left: 15px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="four wide column" style="padding: 14px 14px 0px 150px;">
                                    <div class="field">
                                        <label>Solution/Method</label>
                                    </div>
                                </div>
                                
                                <div class="twelve wide column" style="padding-bottom: 0px;">
                                    <div class="field eight wide column">
                                        <textarea id="ReqFor_solution" name="solution" type="text" rows="5"></textarea>
                                    </div>
                                </div>
                                
                                <div class="sixteen wide column centered grid" style="padding-left: 150px;">
                                    <div class="inline field">
                                        <label>Doctor's Name</label>
                                        <input id="dressingReqFor_doctorname" name="dressing_doctorname" type="text" style="width: 350px; text-transform: uppercase;">
                                    </div>
                                    
                                    <div class="inline field">
                                        <label>Entered By</label>
                                        <input id="dressingReqFor_lastuser" name="dressing_lastuser" type="text" style="width: 350px; text-transform: uppercase;">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="ui bottom attached tab raised segment" data-tab="referral_letter_reqfor">
            @include('hisdb.radiology.referral_letter_iframe')
        </div>

        <div class="ui bottom attached tab raised segment" data-tab="card_noninv_reqfor">
            @include('hisdb.radiology.card_noninv_extend')
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript" src="{{asset('patientcare/js/requestfor_iframe.js')}}"></script>
@endsection
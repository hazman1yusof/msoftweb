
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function (){
    
    var fdl = new faster_detail_load();
    
    $("#jqGridWardMain_panel").on("shown.bs.collapse", function (){
        SmoothScrollTo("#jqGridWardMain_panel", 500);
        
        let curtype = $("#jqGridWardMain_panel").data('curtype');
        $('#jqGridWardMain_panel_tabs.nav-tabs a#'+curtype).tab('show');
        
        $('#jqGridWardMain_panel').find('.nav a:first').tab('show');

        $('#wardMain_panel_title').show();
    });
    
    $("#jqGridWardMain_panel").on("hidden.bs.collapse", function (){
        button_state_tiED('empty');
        
        disableForm('#formTriageInfoED');
        
        $("#jqGridWardMain_panel > div").scrollTop(0);
        $("#jqGridWardMain_panel #jqGridWardMain_panel_tabs li").removeClass('active');

        $('#wardMain_panel_title').hide();
    });
    
    $("#jqGridWardMain_panel").on("show.bs.collapse", function (){
        if($('#isPregnant_wardMain').val() == '1'){
            $('#navtab_antenatalIP').show();
        }else{
            $('#navtab_antenatalIP').hide();
        }
    });
    
    $('#jqGridWardMain_panel_tabs.nav-tabs a').on('shown.bs.tab', function (e){
        let type = $(this).data('type');
        let id = $(this).attr('id');
        $("#jqGridWardMain_panel").data('curtype',id);
        switch(type){
            case 'EDAssmtIP':
                populate_triageED_currpt_getdata();
                break;
            case 'triageIP':
                populate_triage_currpt_getdata();
                
                $("#jqGridExamTriage").jqGrid('setGridWidth', Math.floor($("#jqGridWardMain_c")[0].offsetWidth-$("#jqGridWardMain_c")[0].offsetLeft-310));
                $("#jqGridAddNotesTriage").jqGrid('setGridWidth', Math.floor($("#jqGridWardMain_c")[0].offsetWidth-$("#jqGridWardMain_c")[0].offsetLeft-310));
                
                var urlaram_nursing_date_tbl = {
                    action: 'get_table_date_past',
                    mrn: $("#mrn_wardMain").val(),
                }
                
                nursing_date_tbl.ajax.url("./doctornote/table?"+$.param(urlaram_nursing_date_tbl)).load(function (data){
                    emptyFormdata_div("#formTriageInfo",['#mrn_ti','#episno_ti','#epistycode_ti']);
                    $('#nursing_date_tbl tbody tr:eq(0)').click(); // to select first row
                });
                
                break;
            case 'nursActionIP':
                var saveParam = {
                    action: 'get_table_formHeader',
                }
                
                var postobj = {
                    _token: $('#csrf_token').val(),
                    mrn_nursActionPlan: $("#mrn_wardMain").val(),
                    episno_nursActionPlan: $("#episno_wardMain").val(),
                };
                
                $.post("nursingActionPlan/form?"+$.param(saveParam), $.param(postobj), function (data){
                    
                },'json').fail(function (data){
                    alert('there is an error');
                }).success(function (data){
                    if(!$.isEmptyObject(data.header)){
                        // autoinsert_rowdata("#formHeader",data.episode);
                        autoinsert_rowdata("#formHeader",data.header);
                        button_state_header('edit');
                        // textarea_init_nursingActionPlan();
                    }else{
                        // autoinsert_rowdata("#formHeader",data.episode);
                        // autoinsert_rowdata("#formHeader",data.header);
                        // button_state_header('add');
                        // textarea_init_nursingActionPlan();
                        emptyFormdata(errorField,'#formHeader');
                    }
                    
                    autoinsert_rowdata("#formHeader",data.episode);
                    button_state_header('edit');
                    textarea_init_nursingActionPlan();
                });
                
                // populate_header_getdata();
                let curtype_nursActionIP = $("#jqGridNursActionPlan_paneltab").data('curtype');
                $('#jqGridNursActionPlan_panel_tabs.nav-tabs a#'+curtype_nursActionIP).tab('show');
                
                // load tab treatment
                urlParam_Treatment.filterVal[0] = $("#mrn_nursActionPlan").val();
                urlParam_Treatment.filterVal[1] = $("#episno_nursActionPlan").val();
                refreshGrid('#jqGridTreatment',urlParam_Treatment,'add');
                $("#jqGridTreatment").jqGrid('setGridWidth', Math.floor($("#jqGridTreatment_c")[0].offsetWidth-$("#jqGridTreatment_c")[0].offsetLeft-30));
                break;
            case 'nursNoteIP':
                let curtype_nursNoteIP = $("#jqGridNursNote_paneltab").data('curtype');
                if($('#epistycode').val() == 'IP'){
                    $('#jqGridNursNote_panel_tabs.nav-tabs a#'+curtype_nursNoteIP).tab('show');
                }else if($('#epistycode').val() == 'OP'){
                    $('#jqGridNursNote_panel_tabs.nav-tabs a#'+'navtab_progress').tab('show');
                }
                
                // reload first tab dulu
                $('#invChart_file').DataTable().ajax.url('./nursingnote/table?action=invChart_file&mrn='+$('#mrn_nursNote').val()+'&episno='+$("#episno_nursNote").val()).load();
                populate_invHeader_getdata();
                
                var urlparam_tbl_invcat_FBC = {
                    action: 'get_invcat',
                    inv_code: 'FBC',
                }
                
                tbl_invcat_FBC.ajax.url("./nursingnote/table?"+$.param(urlparam_tbl_invcat_FBC)).load(function (data){
                    emptyFormdata_div("#formInvChart",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar','#inv_codeFBC']);
                    $('#tbl_invcat_FBC tbody tr:eq(0)').click(); // to select first row
                });
                
                // $('#tbl_invcat_FBC').DataTable().ajax.reload();
                $("#jqGridInvChart_FBC").jqGrid('setGridWidth', Math.floor($("#jqGridInvChartFBC_c")[0].offsetWidth-$("#jqGridInvChartFBC_c")[0].offsetLeft-58));
                break;
            case 'antenatalIP':
                var saveParam = {
                    action: 'get_table_antenatal',
                }
                
                var postobj = {
                    _token: $('#csrf_token').val(),
                    mrn: $('#mrn_wardMain').val(),
                    episno: $("#episno_wardMain").val(),
                };
                
                $.post("./antenatal/form?"+$.param(saveParam), $.param(postobj), function (data){
                    
                },'json').fail(function (data){
                    alert('there is an error');
                }).success(function (data){
                    if(!$.isEmptyObject(data)){
                        if(!$.isEmptyObject(data.antenatal)){
                            autoinsert_rowdata_antenatal("#formAntenatal",data.antenatal);
                            button_state_antenatal('edit_antenatal');
                        }else{
                            button_state_antenatal('add_antenatal');
                        }
                        
                        if(!$.isEmptyObject(data.pregnancy)){
                            if(!$.isEmptyObject(data.pregnancy.recstatus)){
                                button_state_antenatal('empty_pregnancy');
                                button_state_antenatal('empty_ultrasound');
                                
                                // to hide pager
                                $('#jqGridPagerCurrPregnancy_left td.ui-pg-button').hide();
                                $('#jqGridPagerObstetricsUltrasound_left td.ui-pg-button').hide();
                            }else{
                                button_state_antenatal('edit_pregnancy');
                                
                                // to show pager
                                $('#jqGridPagerCurrPregnancy_left td.ui-pg-button').show();
                                $('#jqGridPagerObstetricsUltrasound_left td.ui-pg-button').show();
                            }
                            
                            autoinsert_rowdata_antenatal("#formPregnancy",data.pregnancy);
                            preg_paginate(data.pregnancy_page);
                            
                            $('#pregnan_idno').val(data.pregnancy.idno);
                            urlParam_CurrPregnancy.filterVal[2] = data.pregnancy.idno;
                            urlParam_ObstetricsUltrasound.filterVal[1] = data.pregnancy.idno;
                            
                            refreshGrid('#jqGridCurrPregnancy',urlParam_CurrPregnancy,'add');
                            refreshGrid('#jqGridObstetricsUltrasound',urlParam_ObstetricsUltrasound,'add');
                        }else{
                            button_state_antenatal('add_pregnancy');
                        }
                    }else{
                        button_state_antenatal('add_antenatal');
                        button_state_antenatal('add_pregnancy');
                        
                        $('#preg_paginate').empty();
                        
                        refreshGrid('#jqGridCurrPregnancy',urlParam_CurrPregnancy,'kosongkan');
                        refreshGrid('#jqGridObstetricsUltrasound',urlParam_ObstetricsUltrasound,'kosongkan');
                    }
                    refreshGrid('#jqGridPrevObstetrics',urlParam_PrevObstetrics,'add');
                });
                
                $("#jqGridPrevObstetrics").jqGrid('setGridWidth', Math.floor($("#jqGridWardMain_c")[0].offsetWidth-$("#jqGridWardMain_c")[0].offsetLeft-210));
                $("#jqGridCurrPregnancy").jqGrid('setGridWidth', Math.floor($("#jqGridWardMain_c")[0].offsetWidth-$("#jqGridWardMain_c")[0].offsetLeft-210));
                $("#jqGridObstetricsUltrasound").jqGrid('setGridWidth', Math.floor($("#jqGridWardMain_c")[0].offsetWidth-$("#jqGridWardMain_c")[0].offsetLeft-210));
                break;
            case 'docNoteIP':
                sticky_clientprognotetbl(on = true);
                clientprognote_date_tbl.ajax.url("./clientprogressnote/table?"+$.param(dateParam_clientprognote)).load(function (data){
                    emptyFormdata_div("#formClientProgNote",['#mrn_clientProgNote','#episno_clientProgNote','#datetime_clientProgNote','#epistycode_clientProgNote']);
                    $('#clientprognote_date_tbl tbody tr:eq(0)').click(); // to select first row
                });
                textarea_init_clientProgNote();
                break;
            case 'docNoteRefIP':
                sticky_clientprognotereftbl(on = true);
                docalloc_tbl.ajax.url("./clientprogressnoteref/table?"+$.param(docalloc_clientprognoteref)).load(function (data){
                    emptyFormdata_div("#formClientProgNoteRef",['#mrn_clientProgNoteRef','#episno_clientProgNoteRef','#datetime_clientProgNoteRef','#epistycode_clientProgNoteRef','#refdoctor_clientProgNoteRef']);
                    $('#docalloc_tbl tbody tr:eq(0)').click(); // to select first row
                });
                textarea_init_clientProgNoteRef();
                break;
            case 'docNotePsyIP':
                sticky_docnotetbl(on = true);
                docnote_date_tbl.ajax.url("./doctornote/table?"+$.param(dateParam_docnote)).load(function (data){
                    emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote','#recorddate_doctorNote']);
                    $('#docnote_date_tbl tbody tr:eq(0)').click(); // to select first row
                });
                $("#jqGrid_trans_doctornote").jqGrid('setGridWidth', Math.floor($("#jqGrid_trans_doctornote_c")[0].offsetWidth-$("#jqGrid_trans_doctornote_c")[0].offsetLeft));
                textarea_init_doctornote();
                get_default_patdata();
                urlParam_trans.mrn = $('#mrn_wardMain').val();
                urlParam_trans.episno = $('#episno_wardMain').val();
                
                // let curtype = $(this).data('curtype');
                // $('#jqGridDoctorNote_panel_tabs.nav-tabs a#'+curtype).tab('show');
                
                $('#jqGridDoctorNote_paneltab').find('.nav a:first').tab('show');
                populate_otbook_getdata();
                populate_radClinic_getdata();
                // populate_mri_getdata();
                // populate_physio_getdata();
                // populate_dressing_getdata();
                // populate_preContrast_getdata();
                // populate_consentForm_getdata();
                break;
            case 'reqForIP':
                // let curtype_reqForIP = $('#jqGridRequestFor_paneltab').data('curtype');
                // $('#jqGridRequestFor_panel_tabs.nav-tabs a#'+curtype_reqForIP).tab('show');
                
                // $('#jqGridRequestFor_paneltab').find('.nav a:first').tab('show');
                // populate_otbookReqFor_getdata();
                // populate_radClinicReqFor_getdata();
                // populate_mriReqFor_getdata();
                // populate_physioReqFor_getdata();
                // populate_dressingReqFor_getdata();
                // populate_preContrastReqFor_getdata();
                // populate_consentFormReqFor_getdata();
                var lastrowdata = getrow_bootgrid();

                var reqForIP_newurl = "./requestfor_iframe?mrn="+lastrowdata.MRN+"&episno="+lastrowdata.Episno+"&phase=CLINICAL";
                var reqForIP_cururl = $('iframe#requestfor_main_iframe').attr('src');

                if(reqForIP_cururl != reqForIP_newurl){
                    $('iframe#requestfor_main_iframe').attr('src',reqForIP_newurl);
                }

                break;
            case 'dietNoteIP':
                $.post("./dieteticCareNotes/form?"+$.param(saveParam_dietaticCareNotes), $.param(postobj_dietaticCareNotes), function (data){
                    
                },'json').fail(function (data){
                    alert('there is an error');
                }).success(function (data){
                    // console.log('data');
                    
                    if(!$.isEmptyObject(data)){
                        // console.log(data);
                        autoinsert_rowdata_dieteticCareNotes("#formDieteticCareNotes",data.patdietncase);
                        button_state_dieteticCareNotes('disable_ncase');
                        getBMI_ncase();
                        // disableFields_dieteticCareNotes();
                        textare_init_dietetic();
                    }else{
                        button_state_dieteticCareNotes('add');
                        textare_init_dietetic();
                    }
                });
                
                var urlparam_dietetic_date_tbl = {
                    action: 'get_table_date_dietetic',
                    mrn: $("#mrn_wardMain").val(),
                    episno: $("#episno_wardMain").val(),
                }
                
                dietetic_date_tbl.ajax.url("./dieteticCareNotes/table?"+$.param(urlparam_dietetic_date_tbl)).load(function (data){
                    emptyFormdata_div("#formDieteticCareNotes_fup",['#mrn_dieteticCareNotes_fup','#episno_dieteticCareNotes_fup']);
                    // $('#dietetic_date_tbl tbody tr:eq(0)').click(); // to select first row
                });
                
                break;
            case 'dietOrderIP':
                var saveParam = {
                    action: 'get_table_dietorder',
                }
                
                var postobj = {
                    _token: $('#csrf_token').val(),
                    mrn: $("#mrn_wardMain").val(),
                    episno: $("#episno_wardMain").val(),
                };
                
                $.post("dietorder/form?"+$.param(saveParam), $.param(postobj), function (data){
                    
                },'json').fail(function (data){
                    alert('there is an error');
                }).success(function (data){
                    if(!$.isEmptyObject(data.dietorder)){
                        autoinsert_rowdata("#formDietOrder",data.dietorder);
                        autoinsert_rowdata("#formDietOrder",data.episode);
                        button_state_dietOrder('edit');
                        yesnoCheck();
                        feedingCheck();
                        textarea_init_dietorder();
                    }else{
                        autoinsert_rowdata("#formDietOrder",data.episode);
                        button_state_dietOrder('add');
                        textarea_init_dietorder();
                    }
                });
                
                break;
        }
    });
    
});

var errorField = [];
conf = {
    modules: 'logic',
    language: {
        requiredFields: 'You have not answered all required fields'
    },
    onValidate: function ($form){
        if(errorField.length > 0){
            return {
                element: $(errorField[0]),
                message: ''
            }
        }
    },
};

// screen current patient //
function populate_wardMain(obj){
    $("#jqGridWardMain_panel").collapse('hide');
    // emptyFormdata(errorField,"#formWardMain");
    
    // panel header
    $('#name_show_wardMain').text(obj.Name);
    $('#mrn_show_wardMain').text(("0000000" + obj.MRN).slice(-7));
    $('#sex_show_wardMain').text(if_none(obj.Sex).toUpperCase());
    $('#dob_show_wardMain').text(dob_chg(obj.DOB));
    $('#age_show_wardMain').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_wardMain').text(if_none(obj.raceDesc).toUpperCase());
    $('#religion_show_wardMain').text(if_none(obj.religionDesc).toUpperCase());
    $('#occupation_show_wardMain').text(if_none(obj.occupDesc).toUpperCase());
    $('#citizenship_show_wardMain').text(if_none(obj.cityDesc).toUpperCase());
    $('#area_show_wardMain').text(if_none(obj.areaDesc).toUpperCase());

    $('#name_show_wardMain_2').text(obj.Name);
    $('#mrn_show_wardMain_2').text(("0000000" + obj.MRN).slice(-7));
    $('#sex_show_wardMain_2').text(if_none(obj.Sex).toUpperCase());
    $('#dob_show_wardMain_2').text(dob_chg(obj.DOB));
    $('#age_show_wardMain_2').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_wardMain_2').text(if_none(obj.raceDesc).toUpperCase());
    $('#religion_show_wardMain_2').text(if_none(obj.religionDesc).toUpperCase());
    $('#occupation_show_wardMain_2').text(if_none(obj.occupDesc).toUpperCase());
    $('#citizenship_show_wardMain_2').text(if_none(obj.cityDesc).toUpperCase());
    $('#area_show_wardMain_2').text(if_none(obj.areaDesc).toUpperCase());
    
    $("#mrn_wardMain").val(obj.MRN);
    $("#episno_wardMain").val(obj.Episno);
    $("#doctor_wardMain").val(obj.q_doctorname);
    $("#ward_wardMain").val(obj.ward);
    $("#bednum_wardMain").val(obj.bednum);
    $("#age_wardMain").val(dob_age(obj.DOB));
    $("#isPregnant_wardMain").val(obj.pregnant);
}

function autoinsert_rowdata(form,rowData){
    $.each(rowData, function (index, value){
        var input = $(form+" [name='"+index+"']");
        if(input.is("[type=radio]")){
            $(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
        }else if(input.is("[type=checkbox]")){
            if(value == 1){
                $(form+" [name='"+index+"']").prop('checked', true);
            }
        }else{
            input.val(value);
        }
    });
}

function check_same_usr_edit(data){
    let same = true;
    var adduser = data.adduser;
    
    if(adduser == null){
        same = false;
    }else if(adduser.toUpperCase() != $('#curr_user').val().toUpperCase()){
        same = false;
    }
    
    return same;
}

// function calc_jq_height_onchange(jqgrid){
//     let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
//     if(scrollHeight < 50){
//         scrollHeight = 50;
//     }else if(scrollHeight > 300){
//         scrollHeight = 300;
//     }
//     $('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight);
// }
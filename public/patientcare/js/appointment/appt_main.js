
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    $('.menu .item').tab();
    
    var fdl = new faster_detail_load();
    // var radbuts = new checkradiobutton(['lvl_conscious','mental_stat','emotional_stat']);
    
    disableForm('#formTriageInfo');
    
    // to format number input to two decimal places (0.00)
    $(".floatNumberField").change(function (){
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });
    
    // to limit to two decimal places (onkeypress)
    $(document).on('keydown', 'input[pattern]', function (e){
        var input = $(this);
        var oldVal = input.val();
        var regex = new RegExp(input.attr('pattern'), 'g');
        
        setTimeout(function (){
            var newVal = input.val();
            if(!regex.test(newVal)){
                input.val(oldVal);
            }
        }, 0);
    });
    
    $('#apptMainTabs .top.menu .item').tab({'onVisible': function (){
        let tab = $(this).data('tab');
        // console.log(tab);
        
        switch(tab){
            case 'docImaging':
                // DataTable_preview.columns.adjust();
                // preview_load_data();

                let param_docImaging = {
                    mrn: $("#mrn_apptMain").val(),
                    episno: $("#episno_apptMain").val(),
                }

                let newurl_docImaging = './userfile_iframe'+"?"+$.param(param_docImaging);
                let cururl_docImaging = $('iframe#userfile_iframe').attr('src');

                if(newurl_docImaging != cururl_docImaging){
                    $("iframe#userfile_iframe").attr('src',newurl_docImaging);
                }

                break;
            case 'triageInfo':
                $("#jqGridExamTriage").jqGrid('setGridWidth', Math.floor($("#jqGridExamTriage_c")[0].offsetWidth-$("#jqGridExamTriage_c")[0].offsetLeft-14));
                $("#jqGridAddNotesTriage").jqGrid('setGridWidth', Math.floor($("#jqGridAddNotesTriage_c")[0].offsetWidth-$("#jqGridAddNotesTriage_c")[0].offsetLeft-14));
                radbuts.reset();
                getdata_triageAppt();
                break;
            case 'doctorNote':
                // datable_medication.columns.adjust();
                $('div#docnote_date_tbl_sticky').show();
                $("#jqGrid_trans").jqGrid('setGridWidth', Math.floor($("#jqGrid_trans_c")[0].offsetWidth-$("#jqGrid_trans_c")[0].offsetLeft-14));
                
                docnote_date_tbl.ajax.url("./ptcare_doctornote/table?"+$.param(dateParam_docnote)).load(function (data){
                    emptyFormdata_div("#formDoctorNote",['#mrn_doctorNote','#episno_doctorNote']);
                    // $('#docnote_date_tbl tbody tr:eq(0)').click(); // to select first row
                });
                refreshGrid("#jqGrid_trans", urlParam_trans);
                
                $('#doctor_requestFor .top.menu .item').tab('change tab','otbook');
                populate_otbook_getdata();
                populate_radClinic_getdata();
                // populate_mri_getdata();
                // populate_physio_getdata();
                // populate_dressing_getdata();
                // populate_preContrast_getdata();
                // populate_consentForm_getdata();
                break;
            case 'reqFor':
                // let curtype = $(this).data('curtype');
                // $('#requestFor.menu a#'+curtype).tab('show');
                
                $('#requestFor .top.menu .item').tab('change tab','otbookReqFor');
                populate_otbookReqFor_getdata();
                // populate_radClinicReqFor_getdata();
                // populate_mriReqFor_getdata();
                // populate_physioReqFor_getdata();
                // populate_dressingReqFor_getdata();
                // populate_preContrastReqFor_getdata();
                // populate_consentFormReqFor_getdata();

                // let param_reqFor = {
                //     mrn: $("#mrn_apptMain").val(),
                //     episno: $("#episno_apptMain").val(),
                // }

                // let newurl_reqFor = './requestfor_iframe'+"?"+$.param(param_reqFor);
                // let cururl_reqFor = $('iframe#wardbook_iframe').attr('src');

                // if(newurl_reqFor != cururl_reqFor){
                //     $("iframe#requestfor_iframe").attr('src',newurl_reqFor);
                // }

                break;
            case 'admHandover':
                getdata_admHandoverAppt();
                break;
            case 'dietNotes':
                getdata_dietNotes();
                break;
        }
    }});
    
    $('#doctor_requestFor .top.menu .item').tab({'onVisible': function (){
        let tab = $(this).data('tab');
        // console.log(tab);
        
        switch(tab){
            case 'otbook':
                populate_otbook_getdata();
                // textarea_init_otbook();
                break;
            case 'rad':
                break;
            case 'physio':
                populate_physio_getdata();
                // textarea_init_physio();
                break;
            case 'dressing':
                populate_dressing_getdata();
                // textarea_init_dressing();
                break;
        }
    }});
    
    $('#doctor_radiology .top.menu .item').tab({'onVisible': function (){
        let tab = $(this).data('tab');
        // console.log(tab);
        
        switch(tab){
            case 'radClinic':
                populate_radClinic_getdata();
                // textarea_init_radClinic();
                break;
            case 'mri':
                populate_mri_getdata();
                // textarea_init_mri();
                break;
            case 'preContrast':
                populate_preContrast_getdata();
                break;
            case 'consent':
                populate_consentForm_getdata();
                break;
        }
    }});
    
    $('#requestFor .top.menu .item').tab({'onVisible': function (){
        let tab = $(this).data('tab');
        // console.log(tab);
        
        switch(tab){
            case 'otbookReqFor':
                populate_otbookReqFor_getdata();
                // textarea_init_otbookReqFor();
                break;
            case 'radReqFor':
                $('#radiology .top.menu .item').tab('change tab','radClinicReqFor');
                populate_radClinicReqFor_getdata();
                break;
            case 'physioReqFor':
                populate_physioReqFor_getdata();
                // textarea_init_physioReqFor();
                break;
            case 'dressingReqFor':
                populate_dressingReqFor_getdata();
                // textarea_init_dressingReqFor();
                break;
            case 'referral_letter_reqfor':
                populate_referralLetterReqfor_getdata();
                break;
            case 'card_noninv_reqfor':
                populate_card_noninv_getdata();
                break;
        }
    }});
    
    $('#radiology .top.menu .item').tab({'onVisible': function (){
        let tab = $(this).data('tab');
        // console.log(tab);
        
        switch(tab){
            case 'radClinicReqFor':
                populate_radClinicReqFor_getdata();
                // textarea_init_radClinicReqFor();
                break;
            case 'mriReqFor':
                populate_mriReqFor_getdata();
                // textarea_init_mriReqFor();
                break;
            case 'preContrastReqFor':
                populate_preContrastReqFor_getdata();
                break;
            case 'consentReqFor':
                populate_consentFormReqFor_getdata();
                break;
        }
    }});
    
});

var radbuts = new checkradiobutton(['lvl_conscious','mental_stat','emotional_stat']);

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

function empty_apptMain(){
    emptyFormdata_div("#formApptMain");
    emptyFormdata_div("#formTriageInfo");
    button_state_ti('empty');
    
    // panel header
    $('#name_show_apptMain').text('');
    $('#mrn_show_apptMain').text('');
    $('#sex_show_apptMain').text('');
    $('#dob_show_apptMain').text('');
    $('#age_show_apptMain').text('');
    $('#race_show_apptMain').text('');
    $('#religion_show_apptMain').text('');
    $('#occupation_show_apptMain').text('');
    $('#citizenship_show_apptMain').text('');
    $('#area_show_apptMain').text('');
    
    // formApptMain
    $('#mrn_apptMain').val('');
    $("#episno_apptMain").val('');
}

function populate_apptMain(obj){
    emptyFormdata_div("#formTriageInfo",['#mrn_ti','#episno_ti']);
    
    // panel header
    $('#name_show_apptMain').text(obj.Name);
    $('#mrn_show_apptMain').text(("0000000" + obj.MRN).slice(-7));
    $('#sex_show_apptMain').text(if_none(obj.Sex).toUpperCase());
    $('#dob_show_apptMain').text(dob_chg(obj.DOB));
    $('#age_show_apptMain').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_apptMain').text(if_none(obj.raceDesc).toUpperCase());
    $('#religion_show_apptMain').text(if_none(obj.religion).toUpperCase());
    $('#occupation_show_apptMain').text(if_none(obj.OccupCode).toUpperCase());
    $('#citizenship_show_apptMain').text(if_none(obj.Citizencode).toUpperCase());
    $('#area_show_apptMain').text(if_none(obj.AreaCode).toUpperCase());
    
    // formApptMain
    $('#mrn_apptMain').val(obj.MRN);
    $("#episno_apptMain").val(obj.Episno);
    $("#age_apptMain").val(dob_age(obj.DOB));
    
    $("#tab_apptMain").collapse('hide');
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
        }else if(input.is("textarea")){
            if(value !== null){
                let newval = value.replaceAll("</br>",'\n');
                input.val(newval);
            }
        }else{
            input.val(value);
        }
    });
}

$('#tab_apptMain').on('shown.bs.collapse', function (){
    SmoothScrollTo('#tab_apptMain', 300, 114);
    $('#apptMainTabs .top.menu .item').tab('change tab','triageInfo');
    // $('#apptMainTabs .ui.menu').find('.item').tab('change tab', 'triageInfo');
    
    // $('#apptMainTabs .ui.menu').find('.apptMainItem').removeClass('active');
    // $('#apptMainTabs .ui.menu .item, .ui.tab.segment').removeClass('active');
    // $('[data-tab="triageInfo"]').addClass('active');
    
    // to load first tab
    // DataTable_preview.columns.adjust();
    // preview_load_data();
    
    $("#jqGridExamTriage").jqGrid('setGridWidth', Math.floor($("#jqGridExamTriage_c")[0].offsetWidth-$("#jqGridExamTriage_c")[0].offsetLeft-14));
    $("#jqGridAddNotesTriage").jqGrid('setGridWidth', Math.floor($("#jqGridAddNotesTriage_c")[0].offsetWidth-$("#jqGridAddNotesTriage_c")[0].offsetLeft-14));
    radbuts.reset();
    getdata_triageAppt();
    
    // if($('#mrn_apptMain').val() != ''){
    //     getdata_triageAppt();
    // }
});

$('#tab_apptMain').on('hide.bs.collapse', function (){
    emptyFormdata_div("#formApptMain",['#mrn_apptMain','#episno_apptMain']);
    button_state_ti('empty');
});

$('#tab_apptMain').on('hidden.bs.collapse', function (){
    $('#apptMainTabs .top.menu .item').tab('change tab','triageInfo');
});

function check_same_usr_edit(data){
    let same = true;
    var adduser = data.adduser;
    
    if(adduser == undefined){
        return false;
    }else if(adduser.toUpperCase() != $('#curr_user').val().toUpperCase()){
        return false;
    }
    
    return same;
}
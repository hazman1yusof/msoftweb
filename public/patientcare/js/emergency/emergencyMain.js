$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    // $('.menu .item').tab();
    
    var fdl = new faster_detail_load();

    disableForm('#formTriageInfo');
    disableForm('#formProgress_ED');
    disableForm('#formDrug');
    disableForm('#formPivc_ED');
    disableForm('#formThrombo_ED');
    disableForm('#formOTBookReqFor');
    disableForm('#formRadClinicReqFor');
    disableForm('#formMRIReqFor');
    disableForm('#formPhysioReqFor');
    disableForm('#formDressingReqFor');
    disableForm('#formPreContrastReqFor');
    disableForm('#formConsentFormReqFor');
    disableForm('#formAdmHandover');
    disableForm('#formDieteticCareNotes');
	disableForm('#formDieteticCareNotes_fup');

    // to format number input to two decimal places (0.00)
    $(".floatNumberField").change(function (){
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });

    function preview_load_data(){
    	let mrn = $('#mrn_emergencyMain').val();
        DataTable_preview.clear().draw();
        
        if(mrn.trim().length == 0){
            return false;
        }

        var urlParam={
            action:'preview value',
            url:'./ptcare_preview/data',
            mrn:parseInt(mrn)
        }

        $.get( urlParam.url+"?"+$.param(urlParam), function( data ) {
                    
        },'json').done(function(data) {
            if(!$.isEmptyObject(data.rows)){
                data.rows.forEach(function(obj,i){
                    obj.auditno = obj.auditno; 
                    obj.trxdate = formatDate_mom(obj.trxdate,'YYYY-MM-DD HH:mm:ss');
                    obj.filename = obj.resulttext;
                    obj.preview = make_preview_image(i,obj.attachmentfile,obj.type,obj.auditno);
                    obj.mrn = obj.mrn;
                    obj.type = obj.type;
                    obj.adduser = obj.adduser;
                    obj.adddate = formatDate_mom(obj.adddate,'YYYY-MM-DD HH:mm:ss');
                    obj.download = make_download_butt(i,obj.attachmentfile,obj.type,obj.resulttext);
                });

                DataTable_preview.rows.add(data.rows).draw();
                DataTable_preview.columns.adjust().draw();
            }
        });
    }
    
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

    $('#emergencyMain_tab .menu .item').tab({'onVisible': function (){
        let tab = $(this).data('tab');
        console.log(tab);

        switch(tab){
            case 'userfile':
				DataTable_preview.columns.adjust();
				// SmoothScrollTo("#tab_userfile", 300);
				preview_load_data();
                break;
            case 'nursing_ed':
				getdata_nursing();
                tri_color_set();
	            changeTextInputColor();
                break;
            case 'nursNote':
                $('#nursNote .top.menu .item').tab('change tab','pivc');
                var urlparam_datetimepivc_ED_tbl = {
                    action: 'get_table_datetimepivc_ED',
                    mrn: $("#mrn_emergencyMain").val(),
                    episno: $("#episno_emergencyMain").val()
                }
                
                datetimepivc_ED_tbl.ajax.url("./ptcare_nursingnote/table?"+$.param(urlparam_datetimepivc_ED_tbl)).load(function (data){
                    emptyFormdata_div("#formPivc_ED",['#mrn_emergencyMain','#episno_emergencyMain','#doctor_nursNote','#ordcomtt_phar']);
                    $('#datetimepivc_ED_tbl tbody tr:eq(0)').click(); // to select first row
                });
                
                populate_pivc_ED_getdata();

                $("#jqGridPatMedic").jqGrid('setGridWidth', Math.floor($("#jqGridPatMedic_c")[0].offsetWidth-$("#jqGridPatMedic_c")[0].offsetLeft-30));
                $("#jqGridThrombo_ED").jqGrid('setGridWidth', Math.floor($("#jqGridThrombo_ED_c")[0].offsetWidth-$("#jqGridThrombo_ED_c")[0].offsetLeft));
                if($('#mrn_emergencyMain').val() != ''){
                    populate_progressnote_ED_getdata();
                    populate_drugadmin_getdata();
                    populate_pivc_ED_getdata();
                    populate_thrombo_ED_getdata();
                }
                break;
			case 'doctornote':
                $('div#docnote_date_tbl_sticky').show();
                $("#jqGrid_trans").jqGrid('setGridWidth', Math.floor($("#jqGrid_trans_c")[0].offsetWidth-$("#jqGrid_trans_c")[0].offsetLeft-14));
                
                on_toggling_curr_past(null);
                docnote_date_tbl.ajax.url("./ptcare_doctornoteED/table?"+$.param(dateParam_docnote)).load(function (data){
                    emptyFormdata_div("#formDoctorNote",['#mrn_emergencyMain','#episno_emergencyMain']);
                    // $('#docnote_date_tbl tbody tr:eq(0)').click(); // to select first row
                });
                refreshGrid("#jqGrid_trans", urlParam_trans);
                
                $('#doctor_requestFor .menu .item').tab('change tab','otbook');
                populate_otbook_getdata();
                populate_radClinic_getdata();
                populate_mri_getdata();
                populate_physio_getdata();
                populate_dressing_getdata();
                populate_preContrast_getdata();
                populate_consentForm_getdata();
                break;
            case 'requestFor':
                $('#requestFor .menu .item').tab('change tab','otbookReqFor');
                populate_otbookReqFor_getdata();
                populate_radClinicReqFor_getdata();
                break;
            case 'admHandover':
                populate_admhandover_getdata();
                disableOtherField();
                rdonly('#formAdmHandover');
                textarea_init_admhandover();
                break;
			case 'diet':
                $("#jqGrid_trans_diet").jqGrid ('setGridWidth', Math.floor($("#jqGrid_trans_diet_c")[0].offsetWidth-$("#jqGrid_trans_diet_c")[0].offsetLeft-14));
                var urlParam={
                    action:'get_table_dieteticCareNotes',
                }
                var postobj={
                    _token : $('#_token').val(),
                    mrn:$("#mrn_emergencyMain").val(),
                    episno:$("#episno_emergencyMain").val()
                };

                $.post( "./ptcare_dieteticCareNotesED/form?"+$.param(urlParam), $.param(postobj), function( data ) {
                    
                },'json').fail(function(data) {
                    alert('there is an error');
                }).done(function(data){
                    if(!$.isEmptyObject(data)){
                        autoinsert_rowdata_dieteticCareNotes("#formDieteticCareNotes",data.patdietncase);
                        button_state_dieteticCareNotes('edit');
                        getBMI_ncase();
                    }else{
                        button_state_dieteticCareNotes('add');
                    }

                });

                var urlparam_dietetic_date_tbl={
                    action:'get_table_date_dietetic',
                    mrn:$("#mrn_emergencyMain").val(),
                    episno:$("#episno_emergencyMain").val(),
                }

                dietetic_date_tbl.ajax.url( "./ptcare_dieteticCareNotesED/table?"+$.param(urlparam_dietetic_date_tbl) ).load(function(data){
                    emptyFormdata_div("#formDieteticCareNotes_fup",['#mrn_emergencyMain','#episno_emergencyMain']);
                    // $('#dietetic_date_tbl tbody tr:eq(0)').click();	//to select first row
                });

                break;
        }
    }});
    
    
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

function empty_emergencyMain(){
    emptyFormdata_div("#formTriageInfo");
    emptyFormdata_div("#formProgress_ED");
    emptyFormdata_div("#formDrug");
    emptyFormdata_div("#formPivc_ED");
    emptyFormdata_div("#formThrombo_ED");
    emptyFormdata_div("#formOTBookReqFor");
    emptyFormdata_div("#formRadClinicReqFor");
    emptyFormdata_div("#formMRIReqFor");
    emptyFormdata_div("#formPhysioReqFor");
    emptyFormdata_div("#formDressingReqFor");
    emptyFormdata_div("#formPreContrastReqFor");
    emptyFormdata_div("#formConsentFormReqFor");
    emptyFormdata_div("#formAdmHandover");
    emptyFormdata_div("#formDieteticCareNotes");
	emptyFormdata_div("#formDieteticCareNotes_fup");

    button_state_ti('empty');
    button_state_progress_ED('empty');
    button_state_pivc_ED('empty');
    button_state_thrombo_ED('empty');
    button_state_otbookReqFor('empty');
    button_state_radClinicReqFor('empty');
    button_state_mriReqFor('empty');
    button_state_physioReqFor('empty');
    button_state_dressingReqFor('empty');
    button_state_preContrastReqFor('empty');
    button_state_consentFormReqFor('empty');
    button_state_admHandover('empty');
    button_state_dieteticCareNotes('empty');

    // panel header
    $('#name_show_emergency').text('');
    $('#mrn_show_emergency').text('');
    $('#sex_show_emergency').text('');
    $('#dob_show_emergency').text('');
    $('#age_show_emergency').text('');
    $('#race_show_emergency').text('');
    $('#religion_show_emergency').text('');
    $('#occupation_show_emergency').text('');
    $('#citizenship_show_emergency').text('');
    $('#area_show_emergency').text('');
    
    // formEmergencyMain
    $('#mrn_emergencyMain').val('');
    $("#episno_emergencyMain").val('');
    $("#age_emergencyMain").val('');
}

function populate_emergencyMain(obj){

    emptyFormdata_div("#formTriageInfo",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formProgress_ED",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formDrug",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formPivc_ED",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formThrombo_ED",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formOTBookReqFor",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formRadClinicReqFor",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formMRIReqFor",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formPhysioReqFor",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formDressingReqFor",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formPreContrastReqFor",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formConsentFormReqFor",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formAdmHandover",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formDieteticCareNotes",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formDieteticCareNotes_fup",['#mrn_emergencyMain','#episno_emergencyMain']);

    // panel header
    $('#name_show_emergency').text(obj.Name);
	$('#mrn_show_emergency').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_emergency').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_emergency').text(dob_chg(obj.DOB));
	$('#age_show_emergency').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_emergency').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_emergency').text(if_none(obj.religion).toUpperCase());
	$('#occupation_show_emergency').text(if_none(obj.OccupCode).toUpperCase());
	$('#citizenship_show_emergency').text(if_none(obj.Citizencode).toUpperCase());
	$('#area_show_emergency').text(if_none(obj.AreaCode).toUpperCase());
   
    // formEmergencyMain
    $('#mrn_emergencyMain').val(obj.MRN);
    $("#episno_emergencyMain").val(obj.Episno);
    $("#age_emergencyMain").val(dob_age(obj.DOB));
    $('#ptname_emergencyMain').val(obj.Name);
    $('#preg_emergencyMain').val(obj.pregnant);
    $('#ic_emergencyMain').val(obj.Newic);
    $('#doctorname_emergencyMain').val(obj.doctorname);
    $('#mrn_emergencyMain_past').val(obj.MRN);
    $('#episno_emergencyMain_past').val(obj.Episno);
    $('#recorddate_emergencyMain').val(obj.date);

    $("#tab_emergencyMain").collapse('hide');

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

$('#tab_emergencyMain').on('shown.bs.collapse', function (){
    SmoothScrollTo('#tab_emergencyMain', 500);
    $('#emergencyMain_tab .menu .item').tab('change tab','nursing_ed');
    getdata_nursing();
    tri_color_set();
	changeTextInputColor();
});

$('#tab_emergencyMain').on('hide.bs.collapse', function (){
    emptyFormdata_div("#formTriageInfo",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formProgress_ED",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formDrug",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formPivc_ED",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formThrombo_ED",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formOTBookReqFor",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formRadClinicReqFor",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formMRIReqFor",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formPhysioReqFor",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formDressingReqFor",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formPreContrastReqFor",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formConsentFormReqFor",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formAdmHandover",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formDieteticCareNotes",['#mrn_emergencyMain','#episno_emergencyMain']);
    emptyFormdata_div("#formDieteticCareNotes_fup",['#mrn_emergencyMain','#episno_emergencyMain']);
    // panel header
    $('#emergencyMain_tab .menu .item').tab('change tab','nursing_ed');

    disableForm('#formTriageInfo');
    disableForm('#formProgress_ED');
    disableForm('#formDrug');
    disableForm('#formPivc_ED');
    disableForm('#formThrombo_ED');
    disableForm('#formOTBookReqFor');
    disableForm('#formRadClinicReqFor');
    disableForm('#formMRIReqFor');
    disableForm('#formPhysioReqFor');
    disableForm('#formDressingReqFor');
    disableForm('#formPreContrastReqFor');
    disableForm('#formConsentFormReqFor');
    disableForm('#formAdmHandover');
    disableForm('#formDieteticCareNotes');
	disableForm('#formDieteticCareNotes_fup');

    button_state_ti('empty');
    button_state_progress_ED('empty');
    button_state_pivc_ED('empty');
    button_state_thrombo_ED('empty');
    button_state_otbookReqFor('empty');
    button_state_radClinicReqFor('empty');
    button_state_mriReqFor('empty');
    button_state_physioReqFor('empty');
    button_state_dressingReqFor('empty');
    button_state_preContrastReqFor('empty');
    button_state_consentFormReqFor('empty');
    button_state_admHandover('empty');
    button_state_dieteticCareNotes('empty');

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
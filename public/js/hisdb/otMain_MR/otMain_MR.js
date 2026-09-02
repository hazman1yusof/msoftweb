$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    // $('.menu .item').tab();
    
    var fdl = new faster_detail_load();

    disableForm('#form_preoperative');
    disableForm('#form_preoperativeDC');
    disableForm('#form_oper_team');
    disableForm('#form_otswab');
    disableForm('#form_ottime');
    disableForm('#form_otdischarge');
    disableForm('#formEndoscopyStomach');
    disableForm('#formEndoscopyIntestine');
    
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
    
    $('#otMain_tab .top.menu .item').tab({'onVisible': function (){
        let tab = $(this).data('tab');
        // console.log(tab);

        switch(tab){
            case 'preoperative':
                if($('#mrn_otMain').val() != ''){
                    getdata_preoperative();
                }  
                break;
            case 'preoperativeDC':
                if($('#mrn_otMain').val() != ''){
                    getdata_preoperativeDC();
                }
                break;
            case 'oper_team':
                if($('#mrn_otMain').val() != ''){
                    getdata_oper_team();
                }
                break;
            case 'otswab':
                $("#jqGrid_otswab").jqGrid('setGridWidth', Math.floor($("#jqGrid_otswab_c")[0].offsetWidth-$("#jqGrid_otswab_c")[0].offsetLeft-14));
                $("#jqGrid_specimen").jqGrid('setGridWidth', Math.floor($("#jqGrid_specimen_c")[0].offsetWidth-$("#jqGrid_specimen_c")[0].offsetLeft-14));

                if($('#mrn_otMain').val() != ''){
                    getdata_otswab();
                }
                break;
            case 'ottime':
                if($('#mrn_otMain').val() != ''){
                    getdata_ottime();
                }
                break;
            case 'otdischarge':
                if($('#mrn_otMain').val() != ''){
                    getdata_otdischarge();
                }
                break;
            case 'endoscopyNotes':
                $('#endoscopyNotes .top.menu .item').tab('change tab','endoscopyStomach');
                getdata_endoscopyStomach();
                break;
            case 'otmanagement_div':
                if($('#mrn_otMain').val() != ''){
                    getdata_otmgmt();
                }
                break;
        }
    }});
    
});

$('#otMain_tab .top.menu .item').tab('change tab','preoperative');    

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

function empty_otMain(){
    emptyFormdata_div("#formOtMain");
    emptyFormdata_div("#form_preoperative");
    emptyFormdata_div("#form_preoperativeDC");
    emptyFormdata_div("#form_oper_team");
    emptyFormdata_div("#form_otswab");
    emptyFormdata_div("#form_ottime");
    emptyFormdata_div("#form_otdischarge");
    emptyFormdata_div("#formEndoscopyNotes");
    emptyFormdata_div("#formEndoscopyStomach");
    emptyFormdata_div("#formEndoscopyIntestine");
    emptyFormdata_div("#form_otmgmt_div");
    button_state_preoperative('empty');
    button_state_preoperativeDC('empty');
    button_state_oper_team('empty');
    button_state_otswab('empty');
    button_state_ottime('empty');
    button_state_otdischarge('empty');
    button_state_endoscopyStomach('empty');
    button_state_endoscopyIntestine('empty');
    button_state_otmgmt_div('empty');

    // panel header
    $('#name_show_otMain').text('');
    $('#mrn_show_otMain').text('');
    $('#icpssprt_show_otMain').text('');
    $('#sex_show_otMain').text('');
    $('#height_show_otMain').text('');
    $('#weight_show_otMain').text('');
    $('#dob_show_otMain').text('');
    $('#age_show_otMain').text('');
    $('#race_show_otMain').text('');
    $('#religion_show_otMain').text('');
    $('#occupation_show_otMain').text('');
    $('#citizenship_show_otMain').text('');
    $('#area_show_otMain').text('');
    $('#ward_show_preoperative').text('');
    $('#bednum_show_otMain').text('');
    $('#oproom_show_otMain').text('');
    $('#diagnosis_show_otMain').text('');
    $('#procedure_show_otMain').text('');
    $('#unit_show_otMain').text('');
    $('#type_show_otMain').text('');
    
    // formOtMain
    $('#mrn_otMain').val('');
    $("#episno_otMain").val('');
}

function populate_otMain(obj){
    emptyFormdata_div("#form_preoperative",['#mrn_otMain','#episno_otMain']);
    emptyFormdata_div("#form_preoperativeDC",['#mrn_otMain','#episno_otMain']);
    emptyFormdata_div("#form_oper_team",['#mrn_otMain','#episno_otMain']);
    emptyFormdata_div("#form_otswab",['#mrn_otMain','#episno_otMain']);
    emptyFormdata_div("#form_ottime",['#mrn_otMain','#episno_otMain']);
    emptyFormdata_div("#form_otdischarge",['#mrn_otMain','#episno_otMain']);
    emptyFormdata_div("#formEndoscopyStomach",['#mrn_otMain','#episno_otMain']);
    emptyFormdata_div("#formEndoscopyIntestine",['#mrn_otMain','#episno_otMain']);
    emptyFormdata_div("#form_otmgmt_div",['#mrn_otMain','#episno_otMain']);

    // panel header
    $('#name_show_otMain').text(obj.pat_name);
    $('#mrn_show_otMain').text(("0000000" + obj.mrn).slice(-7));
    $('#icpssprt_show_otMain').text(obj.icnum);
    $('#sex_show_otMain').text(if_none(obj.Sex).toUpperCase());
    $('#height_show_otMain').text(obj.height+' (CM)');
    $('#weight_show_otMain').text(obj.weight+' (KG)');
    $('#dob_show_otMain').text(dob_chg(obj.DOB));
    $('#age_show_otMain').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_otMain').text(if_none(obj.RaceCode).toUpperCase());
    $('#religion_show_otMain').text(if_none(obj.Religion).toUpperCase());
    $('#occupation_show_otMain').text(if_none(obj.OccupCode).toUpperCase());
    $('#citizenship_show_otMain').text(if_none(obj.Citizencode).toUpperCase());
    $('#area_show_otMain').text(if_none(obj.AreaCode).toUpperCase());
    $('#ward_show_preoperative').text(obj.ward);
    $('#bednum_show_otMain').text(obj.bednum);
    $('#oproom_show_otMain').text(obj.ot_description);
    $('#diagnosis_show_otMain').text(obj.appt_diag);
    $('#procedure_show_otMain').text(obj.appt_prcdure);
    $('#unit_show_otMain').text(obj.op_unit);
    $('#type_show_otMain').text(obj.oper_type);
    
    // formOtMain
    $('#mrn_otMain').val(obj.mrn);
    $("#episno_otMain").val(obj.latest_episno);
    $("#age_otMain").val(dob_age(obj.DOB));

    ////jqGridAddNotesPreop
    urlParam_AddNotesPreop.filterVal[0] = obj.mrn;
	urlParam_AddNotesPreop.filterVal[1] = obj.latest_episno;
	urlParam_AddNotesPreop.filterVal[2] = 'PREOPERATIVE';
    // $("#tab_otMain").collapse('hide');
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

$('#tab_otMain').on('shown.bs.collapse', function (){
    SmoothScrollTo('#tab_otMain', 300, 114);
   
});

$('#tab_otMain').on('hide.bs.collapse', function (){
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
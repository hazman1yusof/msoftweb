
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // $('.menu .item').tab();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formOccupTherapyNotes');
    disableForm('#formOccupTherapyMMSE');
    disableForm('#formOccupTherapyMOCA');
    disableForm('#formOccupTherapyBarthel');
    disableForm('#formOccupTherapyUpperExtremity');
    disableForm('#formROF');
    disableForm('#formHand');
    disableForm('#formStrength');
    disableForm('#formSensation');
    disableForm('#formPrehensive');
    disableForm('#formSkin');
    disableForm('#formEdema');
    disableForm('#formFunctional');

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
    
    $('#occupTherapy .top.menu .item').tab({'onVisible': function (){
        let tab = $(this).data('tab');
        // console.log(tab);

        switch(tab){
            case 'notes':
                var urlparam_datetimeNotes_tbl = {
                    action: 'get_table_datetimeNotes',
                    mrn: $("#mrn_occupTherapy").val(),
                    episno: $("#episno_occupTherapy").val()
                }
                
                datetimeNotes_tbl.ajax.url("./occupTherapy_notes/table?"+$.param(urlparam_datetimeNotes_tbl)).load(function (data){
                    emptyFormdata_div("#occupTherapy_notes",['#mrn_occupTherapy','#episno_occupTherapy']);
                    $('#datetimeNotes_tbl tbody tr:eq(0)').click();  // to select first row
                });
                
                populate_notes_getdata();
                break;

            case 'cognitive':
                var urlparam_datetimeMMSE_tbl = {
                    action: 'get_table_datetimeMMSE',
                    mrn: $("#mrn_occupTherapy").val(),
                    episno: $("#episno_occupTherapy").val()
                }
                
                datetimeMMSE_tbl.ajax.url("./occupTherapy_cognitive/table?"+$.param(urlparam_datetimeMMSE_tbl)).load(function (data){
                    emptyFormdata_div("#formOccupTherapyMMSE",['#mrn_occupTherapy','#episno_occupTherapy']);
                    $('#datetimeMMSE_tbl tbody tr:eq(0)').click();  // to select first row
                });
                
                populate_mmse_getdata();
                break;
                
            case 'physical':
                var urlparam_datetimeUpperExtremity_tbl = {
                    action: 'get_table_datetimeUpperExtremity',
                    mrn: $("#mrn_occupTherapy").val(),
                    episno: $("#episno_occupTherapy").val()
                }
                
                datetimeUpperExtremity_tbl.ajax.url("./occupTherapy_upperExtremity/table?"+$.param(urlparam_datetimeUpperExtremity_tbl)).load(function (data){
                    emptyFormdata_div("#formOccupTherapyUpperExtremity",['#mrn_occupTherapy','#episno_occupTherapy','#idno_upperExtremity']);
                    emptyFormdata_div("#formROF",['#mrn_occupTherapy','#episno_occupTherapy','#idno_rof','#rof_impressions']);
                    emptyFormdata_div("#formHand",['#mrn_occupTherapy','#episno_occupTherapy','#idno_hand','#hand_impressions']);
                    $('#datetimeUpperExtremity_tbl tbody tr:eq(0)').click();  // to select first row
                });
                $("#jqGrid_rof").jqGrid('setGridWidth', Math.floor($("#jqGrid_rof_c")[0].offsetWidth-$("#jqGrid_rof_c")[0].offsetLeft));
                $("#jqGrid_hand").jqGrid('setGridWidth', Math.floor($("#jqGrid_hand_c")[0].offsetWidth-$("#jqGrid_hand_c")[0].offsetLeft));

                populate_upperExtremity_getdata();
                break;

            case 'adl':
                var urlparam_datetimeBarthel_tbl = {
                    action: 'get_table_datetimeBarthel',
                    mrn: $("#mrn_occupTherapy").val(),
                    episno: $("#episno_occupTherapy").val()
                }
                
                datetimeBarthel_tbl.ajax.url("./occupTherapy_barthel/table?"+$.param(urlparam_datetimeBarthel_tbl)).load(function (data){
                    emptyFormdata_div("#formOccupTherapyBarthel",['#mrn_occupTherapy','#episno_occupTherapy']);
                    $('#datetimeBarthel_tbl tbody tr:eq(0)').click();  // to select first row
                });
                
                populate_barthel_getdata();
                break;
        }
    }});

    $('#cognitives .top.menu .item').tab({'onVisible': function (){
        let tab = $(this).data('tab');
        // console.log(tab);
        
        switch(tab){
            case 'mmse':
                var urlparam_datetimeMMSE_tbl = {
                    action: 'get_table_datetimeMMSE',
                    mrn: $("#mrn_occupTherapy").val(),
                    episno: $("#episno_occupTherapy").val()
                }
                
                datetimeMMSE_tbl.ajax.url("./occupTherapy_cognitive/table?"+$.param(urlparam_datetimeMMSE_tbl)).load(function (data){
                    emptyFormdata_div("#formOccupTherapyMMSE",['#mrn_occupTherapy','#episno_occupTherapy']);
                    $('#datetimeMMSE_tbl tbody tr:eq(0)').click();  // to select first row
                });
                
                populate_mmse_getdata();
                break;

            case 'moca':
                var urlparam_datetimeMOCA_tbl = {
                    action: 'get_table_datetimeMOCA',
                    mrn: $("#mrn_occupTherapy").val(),
                    episno: $("#episno_occupTherapy").val()
                }
                
                datetimeMOCA_tbl.ajax.url("./occupTherapy_cognitive/table?"+$.param(urlparam_datetimeMOCA_tbl)).load(function (data){
                    emptyFormdata_div("#formOccupTherapyMOCA",['#mrn_occupTherapy','#episno_occupTherapy']);
                    $('#datetimeMOCA_tbl tbody tr:eq(0)').click();  // to select first row
                });
                
                populate_moca_getdata();                
                break;
        }
    }});

    $('#upExt .top.menu .item').tab({'onVisible': function (){
        let tab = $(this).data('tab');
        // console.log(tab);
        switch(tab){
            case 'rof':
                emptyFormdata_div("#formROF",['#mrn_occupTherapy','#episno_occupTherapy','#idno_rof','#rof_impressions']);
                $("#jqGrid_rof").jqGrid('setGridWidth', Math.floor($("#jqGrid_rof_c")[0].offsetWidth-$("#jqGrid_rof_c")[0].offsetLeft));
                populate_rof_getdata();
                break;

            case 'hand':
                emptyFormdata_div("#formHand",['#mrn_occupTherapy','#episno_occupTherapy','#idno_hand','#hand_impressions']);
                $("#jqGrid_hand").jqGrid('setGridWidth', Math.floor($("#jqGrid_hand_c")[0].offsetWidth-$("#jqGrid_hand_c")[0].offsetLeft));
                populate_hand_getdata();
                break;

            case 'strength':
                emptyFormdata_div("#formStrength",['#mrn_occupTherapy','#episno_occupTherapy','#idno_strength']);
                populate_strength_getdata();  
                break;

            case 'sensation':
                emptyFormdata_div("#formStrength",['#mrn_occupTherapy','#episno_occupTherapy','#idno_sensation']);
                populate_sensation_getdata();               
                break;

            case 'prehensive':
                emptyFormdata_div("#formStrength",['#mrn_occupTherapy','#episno_occupTherapy','#idno_prehensive']);
                populate_prehensive_getdata();
                break;

            case 'skin':
                emptyFormdata_div("#formStrength",['#mrn_occupTherapy','#episno_occupTherapy','#formSkin']);
                populate_skin_getdata();             
                break;

            case 'edema':
                emptyFormdata_div("#formStrength",['#mrn_occupTherapy','#episno_occupTherapy','#idno_edema']);
                populate_edema_getdata();             
                break;

            case 'functional':
                emptyFormdata_div("#formStrength",['#mrn_occupTherapy','#episno_occupTherapy','#idno_func']);
                populate_func_getdata();             
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

function empty_occupTherapy(){
    emptyFormdata_div("#formOccupTherapy");
    emptyFormdata_div("#formOccupTherapyNotes");
    emptyFormdata_div("#formOccupTherapyMMSE");
    emptyFormdata_div("#formOccupTherapyMOCA");
    emptyFormdata_div("#formOccupTherapyBarthel");
    emptyFormdata_div("#formOccupTherapyUpperExtremity");
    emptyFormdata_div("#formROF");
    emptyFormdata_div("#formHand");
    emptyFormdata_div("#formStrength");
    emptyFormdata_div("#formSensation");
    emptyFormdata_div("#formPrehensive");
    emptyFormdata_div("#formSkin");
    emptyFormdata_div("#formEdema");
    emptyFormdata_div("#formFunctional");

    button_state_notes('empty');
    button_state_mmse('empty');
    button_state_moca('empty');
    button_state_barthel('empty');
    button_state_upperExtremity('empty');
    button_state_rof('empty');
    button_state_hand('empty');
    button_state_strength('empty');
    button_state_sensation('empty');
    button_state_prehensive('empty');
    button_state_skin('empty');
    button_state_edema('empty');
    button_state_func('empty');

    // panel header
    $('#name_show_occupTherapy').text('');
    $('#mrn_show_occupTherapy').text('');
    $('#icpssprt_show_occupTherapy').text('');
    $('#sex_show_occupTherapy').text('');
    $('#height_show_occupTherapy').text('');
    $('#weight_show_occupTherapy').text('');
    $('#dob_show_occupTherapy').text('');
    $('#age_show_occupTherapy').text('');
    $('#race_show_occupTherapy').text('');
    $('#religion_show_occupTherapy').text('');
    $('#occupation_show_occupTherapy').text('');
    $('#citizenship_show_occupTherapy').text('');
    $('#area_show_occupTherapy').text('');
    $('#ward_show_occupTherapy').text('');
    
    // formOccupTherapy
    $('#mrn_occupTherapy').val('');
    $("#episno_occupTherapy").val('');
}

function populate_occupTherapy(obj){
    emptyFormdata_div("#formOccupTherapyNotes",['#mrn_occupTherapy','#episno_occupTherapy']);
    emptyFormdata_div("#formOccupTherapyMMSE",['#mrn_occupTherapy','#episno_occupTherapy']);
    emptyFormdata_div("#formOccupTherapyMOCA",['#mrn_occupTherapy','#episno_occupTherapy']);
    emptyFormdata_div("#formOccupTherapyBarthel",['#mrn_occupTherapy','#episno_occupTherapy']);
    emptyFormdata_div("#formOccupTherapyUpperExtremity",['#mrn_occupTherapy','#episno_occupTherapy','#idno_upperExtremity']);
    emptyFormdata_div("#formROF",['#mrn_occupTherapy','#episno_occupTherapy','#idno_rof','#rof_impressions']);
    emptyFormdata_div("#formHand",['#mrn_occupTherapy','#episno_occupTherapy','#idno_hand','#hand_impressions']);
    emptyFormdata_div("#formStrength",['#mrn_occupTherapy','#episno_occupTherapy','#idno_strength']);
    emptyFormdata_div("#formSensation",['#mrn_occupTherapy','#episno_occupTherapy','#idno_sensation']);
    emptyFormdata_div("#formPrehensive",['#mrn_occupTherapy','#episno_occupTherapy','#idno_prehensive']);
    emptyFormdata_div("#formSkin",['#mrn_occupTherapy','#episno_occupTherapy','#idno_skin']);
    emptyFormdata_div("#formEdema",['#mrn_occupTherapy','#episno_occupTherapy','#idno_edema']);
    emptyFormdata_div("#formFunctional",['#mrn_occupTherapy','#episno_occupTherapy','#idno_func']);

    // panel header
    $('#name_show_occupTherapy').text(obj.Name);
	$('#mrn_show_occupTherapy').text(("0000000" + obj.MRN).slice(-7));
	$('#sex_show_occupTherapy').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_occupTherapy').text(dob_chg(obj.DOB));
	$('#age_show_occupTherapy').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_occupTherapy').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_occupTherapy').text(if_none(obj.religion).toUpperCase());
	$('#occupation_show_occupTherapy').text(if_none(obj.OccupCode).toUpperCase());
	$('#citizenship_show_occupTherapy').text(if_none(obj.Citizencode).toUpperCase());
	$('#area_show_occupTherapy').text(if_none(obj.AreaCode).toUpperCase());
   
    // formOccupTherapy
    $('#mrn_occupTherapy').val(obj.MRN);
    $("#episno_occupTherapy").val(obj.Episno);

    $("#tab_occupTherapy").collapse('hide');

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

$('#tab_occupTherapy').on('shown.bs.collapse', function (){
    SmoothScrollTo('#tab_occupTherapy', 300, 114);

    $('#occupTherapy .top.menu .item').tab('change tab','notes');

    var urlparam_datetimeNotes_tbl = {
        action: 'get_table_datetimeNotes',
        mrn: $("#mrn_occupTherapy").val(),
        episno: $("#episno_occupTherapy").val()
    }
    
    datetimeNotes_tbl.ajax.url("./occupTherapy_notes/table?"+$.param(urlparam_datetimeNotes_tbl)).load(function (data){
        emptyFormdata_div("#formOccupTherapyUpperExtremity",['#mrn_occupTherapy','#episno_occupTherapy','#idno_upperExtremity','#idno_rof','#rof_impressions']);
        $('#datetimeNotes_tbl tbody tr:eq(0)').click();  // to select first row
    });
    
    populate_notes_getdata();
    
    $("#jqGrid_rof").jqGrid('setGridWidth', Math.floor($("#jqGrid_rof_c")[0].offsetWidth-$("#jqGrid_rof_c")[0].offsetLeft));
    $("#jqGrid_hand").jqGrid('setGridWidth', Math.floor($("#jqGrid_hand_c")[0].offsetWidth-$("#jqGrid_hand_c")[0].offsetLeft));
   
    if($('#mrn_occupTherapy').val() != ''){
        populate_notes_getdata();
        populate_mmse_getdata();
        populate_moca_getdata();
        populate_barthel_getdata();
        populate_upperExtremity_getdata();
        populate_rof_getdata();
        populate_hand_getdata();
        populate_strength_getdata();
        populate_sensation_getdata();
        populate_prehensive_getdata();
        populate_skin_getdata();
        populate_edema_getdata();
        populate_func_getdata();
    }
});

$('#tab_occupTherapy').on('hide.bs.collapse', function (){
    emptyFormdata_div("#formOccupTherapy",['#mrn_occupTherapy','#episno_occupTherapy','#idno_upperExtremity','#formROF :input[name="idno_rof"]','#rof_impressions']);
    
    disableForm('#formOccupTherapyNotes');
    disableForm('#formOccupTherapyMMSE');
    disableForm('#formOccupTherapyMOCA');
    disableForm('#formOccupTherapyBarthel');
    disableForm('#formOccupTherapyUpperExtremity');
    disableForm('#formROF');
    disableForm('#formHand');
    disableForm('#formStrength');
    disableForm('#formSensation');
    disableForm('#formPrehensive')
    disableForm('#formSkin')
    disableForm('#formEdema')
    disableForm('#formFunctional')

    button_state_notes('empty');
    button_state_mmse('empty');
    button_state_moca('empty');
    button_state_barthel('empty');
    button_state_upperExtremity('empty');
    button_state_rof('empty');
    button_state_hand('empty');
    button_state_strength('empty');
    button_state_sensation('empty');
    button_state_prehensive('empty');
    button_state_skin('empty');
    button_state_edema('empty');
    button_state_func('empty');
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
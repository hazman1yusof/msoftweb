
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // $('.menu .item').tab();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formOccupTherapyMMSE');
    disableForm('#formOccupTherapyMOCA');
    disableForm('#formOccupTherapyBarthel');
    disableForm('#formOccupTherapyUpperExtremity');
    disableForm('#formSensation');

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
                    emptyFormdata_div("#formOccupTherapyUpperExtremity",['#mrn_occupTherapy','#episno_occupTherapy','#idno_sensation']);
                    $('#datetimeUpperExtremity_tbl tbody tr:eq(0)').click();  // to select first row
                });
                $("#jqGrid_rof").jqGrid('setGridWidth', Math.floor($("#jqGrid_rof_c")[0].offsetWidth-$("#jqGrid_rof_c")[0].offsetLeft));

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
                $("#jqGrid_rof").jqGrid('setGridWidth', Math.floor($("#jqGrid_rof_c")[0].offsetWidth-$("#jqGrid_rof_c")[0].offsetLeft));

                break;

            case 'hand':
                $("#jqGrid_hand").jqGrid('setGridWidth', Math.floor($("#jqGrid_hand_c")[0].offsetWidth-$("#jqGrid_hand_c")[0].offsetLeft));
    
                break;

            case 'muscle':
                             
                break;

            case 'sensation':
                populate_sensation_getdata();               
                break;

            case 'prehensive':
                             
                break;

            case 'skin':
                             
                break;

            case 'edema':
                             
                break;

            case 'functional':
                             
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
    emptyFormdata_div("#formOccupTherapyMMSE");
    emptyFormdata_div("#formOccupTherapyMOCA");
    emptyFormdata_div("#formOccupTherapyBarthel");
    emptyFormdata_div("#formOccupTherapyUpperExtremity");
    emptyFormdata_div("#formSensation");

    button_state_mmse('empty');
    button_state_moca('empty');
    button_state_barthel('empty');
    button_state_upperExtremity('empty');
    button_state_sensation('empty');
    
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
    emptyFormdata_div("#formOccupTherapyMMSE",['#mrn_occupTherapy','#episno_occupTherapy']);
    emptyFormdata_div("#formOccupTherapyMOCA",['#mrn_occupTherapy','#episno_occupTherapy']);
    emptyFormdata_div("#formOccupTherapyBarthel",['#mrn_occupTherapy','#episno_occupTherapy']);
    emptyFormdata_div("#formOccupTherapyUpperExtremity",['#mrn_occupTherapy','#episno_occupTherapy','#idno_upperExtremity']);
    emptyFormdata_div("#formSensation",['#mrn_occupTherapy','#episno_occupTherapy','#idno_sensation']);
    
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

    $('#occupTherapy .top.menu .item').tab('change tab','cognitive');
    $('#cognitives .top.menu .item').tab('change tab','mmse');

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
    
    $("#jqGrid_rof").jqGrid('setGridWidth', Math.floor($("#jqGrid_rof_c")[0].offsetWidth-$("#jqGrid_rof_c")[0].offsetLeft));
    $("#jqGrid_hand").jqGrid('setGridWidth', Math.floor($("#jqGrid_hand_c")[0].offsetWidth-$("#jqGrid_hand_c")[0].offsetLeft));
   
    if($('#mrn_occupTherapy').val() != ''){
        populate_mmse_getdata();
        populate_moca_getdata();
        populate_barthel_getdata();
        populate_upperExtremity_getdata();
        populate_sensation_getdata();
    }
});

$('#tab_occupTherapy').on('hide.bs.collapse', function (){
    emptyFormdata_div("#formOccupTherapy",['#mrn_occupTherapy','#episno_occupTherapy']);

    disableForm('#formOccupTherapyMMSE');
    disableForm('#formOccupTherapyMOCA');
    disableForm('#formOccupTherapyBarthel');
    disableForm('#formOccupTherapyUpperExtremity');
    disableForm('#formSensation');

    button_state_mmse('empty');
    button_state_moca('empty');
    button_state_barthel('empty');
    button_state_upperExtremity('empty');
    button_state_sensation('empty');

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
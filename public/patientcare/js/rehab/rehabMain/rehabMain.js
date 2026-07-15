$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // $('.menu .item').tab();
    
    var fdl = new faster_detail_load();
    
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

function empty_rehabMain(){

    // panel header
    $('#name_show_rehabMain').text('');
    $('#mrn_show_rehabMain').text('');
    $('#ic_show_rehabMain').text('');
    $('#sex_show_rehabMain').text('');
    $('#dob_show_rehabMain').text('');
    $('#age_show_rehabMain').text('');
    $('#race_show_rehabMain').text('');
    $('#religion_show_rehabMain').text('');
    $('#occupation_show_rehabMain').text('');
    $('#citizenship_show_rehabMain').text('');
    $('#area_show_rehabMain').text('');
    
    // formRehabMain
    $('#mrn_rehabMain').val('');
    $("#episno_rehabMain").val('');
    $("#age_rehabMain").val('');
}

function populate_rehabMain(obj){
   

    // panel header
    $('#name_show_rehabMain').text(obj.Name);
    $('#mrn_show_rehabMain').text(("0000000" + obj.MRN).slice(-7));
    $('#ic_show_rehabMain').text(obj.Newic);
    $('#sex_show_rehabMain').text(if_none(obj.Sex).toUpperCase());
	$('#dob_show_rehabMain').text(dob_chg(obj.DOB));
	$('#age_show_rehabMain').text(dob_age(obj.DOB)+' (YRS)');
	$('#race_show_rehabMain').text(if_none(obj.raceDesc).toUpperCase());
	$('#religion_show_rehabMain').text(if_none(obj.religion).toUpperCase());
	$('#occupation_show_rehabMain').text(if_none(obj.OccupCode).toUpperCase());
	$('#citizenship_show_rehabMain').text(if_none(obj.Citizencode).toUpperCase());
	$('#area_show_rehabMain').text(if_none(obj.AreaCode).toUpperCase());
   
    // formOccupTherapy
    $('#mrn_rehabMain').val(obj.MRN);
    $("#episno_rehabMain").val(obj.Episno);
    $("#age_rehabMain").val(dob_age(obj.DOB));

    // $("#tab_occupTherapy").collapse('hide');

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
});

$('#tab_occupTherapy').on('hide.bs.collapse', function (){
    emptyFormdata_div("#formOccupTherapy",['#mrn_rehabMain','#episno_rehabMain','#idno_upperExtremity','#formROF :input[name="idno_rof"]','#rof_impressions']);
    
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

$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // $('.menu .item').tab();
    
    var fdl = new faster_detail_load();
    
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
    
    $('#endoscopyNotes .top.menu .item').tab({'onVisible': function (){
        let tab = $(this).data('tab');
        console.log(tab);
        
        switch(tab){
            case 'endoscopyStomach':
                getdata_endoscopyStomach();
                break;
            case 'endoscopyIntestine':
                getdata_endoscopyIntestine();
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

function empty_endoscopyNotes(){
    emptyFormdata_div("#formEndoscopyNotes");
    emptyFormdata_div("#formEndoscopyStomach");
    emptyFormdata_div("#formEndoscopyIntestine");
    button_state_endoscopyStomach('empty');
    button_state_endoscopyIntestine('empty');
    
    // panel header
    $('#name_show_endoscopyNotes').text('');
    $('#mrn_show_endoscopyNotes').text('');
    $('#icpssprt_show_endoscopyNotes').text('');
    $('#sex_show_endoscopyNotes').text('');
    $('#height_show_endoscopyNotes').text('');
    $('#weight_show_endoscopyNotes').text('');
    $('#dob_show_endoscopyNotes').text('');
    $('#age_show_endoscopyNotes').text('');
    $('#race_show_endoscopyNotes').text('');
    $('#religion_show_endoscopyNotes').text('');
    $('#occupation_show_endoscopyNotes').text('');
    $('#citizenship_show_endoscopyNotes').text('');
    $('#area_show_endoscopyNotes').text('');
    $('#ward_show_endoscopyNotes').text('');
    $('#bednum_show_endoscopyNotes').text('');
    $('#oproom_show_endoscopyNotes').text('');
    $('#diagnosis_show_endoscopyNotes').text('');
    $('#procedure_show_endoscopyNotes').text('');
    $('#unit_show_endoscopyNotes').text('');
    $('#type_show_endoscopyNotes').text('');
    
    // formEndoscopyNotes
    $('#mrn_endoscopyNotes').val('');
    $("#episno_endoscopyNotes").val('');
}

function populate_endoscopyNotes(obj){
    emptyFormdata_div("#formEndoscopyStomach",['#mrn_endoscopyNotes','#episno_endoscopyNotes']);
    emptyFormdata_div("#formEndoscopyIntestine",['#mrn_endoscopyNotes','#episno_endoscopyNotes']);
    
    // panel header
    $('#name_show_endoscopyNotes').text(obj.pat_name);
    $('#mrn_show_endoscopyNotes').text(("0000000" + obj.mrn).slice(-7));
    $('#icpssprt_show_endoscopyNotes').text(obj.icnum);
    $('#sex_show_endoscopyNotes').text(if_none(obj.Sex).toUpperCase());
    $('#height_show_endoscopyNotes').text(obj.height+' (CM)');
    $('#weight_show_endoscopyNotes').text(obj.weight+' (KG)');
    $('#dob_show_endoscopyNotes').text(dob_chg(obj.DOB));
    $('#age_show_endoscopyNotes').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_endoscopyNotes').text(if_none(obj.RaceCode).toUpperCase());
    $('#religion_show_endoscopyNotes').text(if_none(obj.Religion).toUpperCase());
    $('#occupation_show_endoscopyNotes').text(if_none(obj.OccupCode).toUpperCase());
    $('#citizenship_show_endoscopyNotes').text(if_none(obj.Citizencode).toUpperCase());
    $('#area_show_endoscopyNotes').text(if_none(obj.AreaCode).toUpperCase());
    $('#ward_show_endoscopyNotes').text(obj.ward);
    $('#bednum_show_endoscopyNotes').text(obj.bednum);
    $('#oproom_show_endoscopyNotes').text(obj.ot_description);
    $('#diagnosis_show_endoscopyNotes').text(obj.appt_diag);
    $('#procedure_show_endoscopyNotes').text(obj.appt_prcdure);
    $('#unit_show_endoscopyNotes').text(obj.op_unit);
    $('#type_show_endoscopyNotes').text(obj.oper_type);
    
    // formEndoscopyNotes
    $('#mrn_endoscopyNotes').val(obj.mrn);
    $("#episno_endoscopyNotes").val(obj.latest_episno);
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

$('#tab_endoscopyNotes').on('shown.bs.collapse', function (){
    SmoothScrollTo('#tab_endoscopyNotes', 300, 114);
    
    if($('#mrn_endoscopyNotes').val() != ''){
        getdata_endoscopyStomach();
        getdata_endoscopyIntestine();
    }
});

$('#tab_endoscopyNotes').on('hide.bs.collapse', function (){
    emptyFormdata_div("#formEndoscopyNotes",['#mrn_endoscopyNotes','#episno_endoscopyNotes']);
    button_state_endoscopyStomach('empty');
    button_state_endoscopyIntestine('empty');
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
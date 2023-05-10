
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {

    textare_init_preoperative();
    
    var fdl = new faster_detail_load();
    
    disableForm('#form_preoperative');
    
    $("#new_preoperative").click(function(){
        $('#cancel_preoperative').data('oper','add');
        button_state_preoperative('wait');
        enableForm('#form_preoperative');
        rdonly('#form_preoperative');
        // emptyFormdata_div("#form_preoperative",['#mrn_preoperative','#episno_preoperative']);
        // dialog_mrn_edit.on();
        
    });
    
    $("#edit_preoperative").click(function(){
        button_state_preoperative('wait');
        enableForm('#form_preoperative');
        rdonly('#form_preoperative');
        // dialog_mrn_edit.on();
        
    });
    
    $("#save_preoperative").click(function(){
        if( $('#form_preoperative').isValid({requiredFields: ''}, conf, true) ) {
            saveForm_preoperative(function(data){
                // emptyFormdata_div("#form_preoperative",['#mrn_preoperative','#episno_preoperative']);
                disableForm('#form_preoperative');
                
            });
        }else{
            enableForm('#form_preoperative');
            rdonly('#form_preoperative');
        }
        
    });
    
    $("#cancel_preoperative").click(function(){
        // emptyFormdata_div("#form_preoperative",['#mrn_preoperative','#episno_preoperative']);
        disableForm('#form_preoperative');
        button_state_preoperative($(this).data('oper'));
        getdata_preoperative();
        // dialog_mrn_edit.off();
        
    });
    
    // to format number input to two decimal places (0.00)
    $(".floatNumberField").change(function() {
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });
    
    // to limit to two decimal places (onkeypress)
    $(document).on('keydown', 'input[pattern]', function(e){
        var input = $(this);
        var oldVal = input.val();
        var regex = new RegExp(input.attr('pattern'), 'g');
        
        setTimeout(function(){
            var newVal = input.val();
            if(!regex.test(newVal)){
                input.val(oldVal);
            }
        }, 0);
    });
    
});

var errorField = [];
conf = {
    modules : 'logic',
    language: {
        requiredFields: 'You have not answered all required fields'
    },
    onValidate: function ($form) {
        if (errorField.length > 0) {
            return {
                element: $(errorField[0]),
                message: ''
            }
        }
    },
};

button_state_preoperative('empty');
function button_state_preoperative(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_preoperative").removeAttr('data-toggle');
            $('#cancel_preoperative').data('oper','add');
            $('#new_preoperative,#save_preoperative,#cancel_preoperative,#edit_preoperative').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_preoperative").attr('data-toggle','collapse');
            $('#cancel_preoperative').data('oper','add');
            $("#new_preoperative").attr('disabled',false);
            $('#save_preoperative,#cancel_preoperative,#edit_preoperative').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_preoperative").attr('data-toggle','collapse');
            $('#cancel_preoperative').data('oper','edit');
            $("#edit_preoperative").attr('disabled',false);
            $('#save_preoperative,#cancel_preoperative,#new_preoperative').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_preoperative").attr('data-toggle','collapse');
            $("#save_preoperative,#cancel_preoperative").attr('disabled',false);
            $('#edit_preoperative,#new_preoperative').attr('disabled',true);
            break;
        case 'disableAll':
            $("#toggle_preoperative").attr('data-toggle','collapse');
            $('#new_preoperative,#edit_preoperative,#save_preoperative,#cancel_preoperative').attr('disabled',true);
            break;
    }
}

function empty_preoperative(){
    emptyFormdata_div("#form_preoperative");
    button_state_preoperative('empty');
    
    // panel header
    $('#name_show_preoperative').text('');
    $('#mrn_show_preoperative').text('');
    $('#icpssprt_show_preoperative').text('');
    $('#sex_show_preoperative').text('');
    $('#height_show_preoperative').text('');
    $('#weight_show_preoperative').text('');
    $('#dob_show_preoperative').text('');
    $('#age_show_preoperative').text('');
    $('#race_show_preoperative').text('');
    $('#religion_show_preoperative').text('');
    $('#occupation_show_preoperative').text('');
    $('#citizenship_show_preoperative').text('');
    $('#area_show_preoperative').text('');
    $('#ward_show_preoperative').text('');
    $('#diagnosis_show_preoperative').text('');
    $('#procedure_show_preoperative').text('');
    $('#unit_show_preoperative').text('');
    
    // form_preoperative
    $('#mrn_preoperative').val('');
    $("#episno_preoperative").val('');
}

function populate_preoperative(obj){
    // panel header
    $('#name_show_preoperative').text(obj.Name);
    $('#mrn_show_preoperative').text(("0000000" + obj.MRN).slice(-7));
    $('#icpssprt_show_preoperative').text(obj.Newic);
    $('#sex_show_preoperative').text(if_none(obj.Sex).toUpperCase());
    $('#height_show_preoperative').text(obj.height+' (CM)');
    $('#weight_show_preoperative').text(obj.weight+' (KG)');
    $('#dob_show_preoperative').text(dob_chg(obj.DOB));
    $('#age_show_preoperative').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_preoperative').text(if_none(obj.RaceCode).toUpperCase());
    $('#religion_show_preoperative').text(if_none(obj.Religion).toUpperCase());
    $('#occupation_show_preoperative').text(if_none(obj.OccupCode).toUpperCase());
    $('#citizenship_show_preoperative').text(if_none(obj.Citizencode).toUpperCase());
    $('#area_show_preoperative').text(if_none(obj.AreaCode).toUpperCase());
    // $('#ward_show_preoperative').text(obj.ward);
    $('#diagnosis_show_preoperative').text(obj.diagnosis);
    $('#procedure_show_preoperative').text(obj.procedure);
    $('#unit_show_preoperative').text(obj.op_unit);
    
    // form_preoperative
    $('#mrn_preoperative').val(obj.MRN);
    $("#episno_preoperative").val(obj.Episno);
}

function autoinsert_rowdata(form,rowData){
    $.each(rowData, function( index, value ) {
        var input=$(form+" [name='"+index+"']");
        if(input.is("[type=radio]")){
            $(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
        }else if(input.is("[type=checkbox]")){
            if(value==1){
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

function saveForm_preoperative(callback){
    let oper = $("#cancel_preoperative").data('oper');
    var saveParam={
        action:'save_table_preoperative',
        oper:oper,
    }
    
    if(oper == 'add'){
        saveParam.sel_date = $('#sel_date').val();
    }else if(oper == 'edit'){
        // var row = docnote_date_tbl.row('.active').data();
        saveParam.sel_date = $('#sel_date').val();
        // saveParam.recordtime = row.recordtime;
    }
    
    var postobj={
        _token : $('#_token').val(),
        // sex_edit : $('#sex_edit').val(),
        // idtype_edit : $('#idtype_edit').val()
    };
    
    values = $("#form_preoperative").serializeArray();
    
    values = values.concat(
        $('#form_preoperative input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#form_preoperative input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#form_preoperative input[type=radio]:checked').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#form_preoperative select').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post( "./preoperative/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').done(function(data) {
        callback(data);
        button_state_preoperative('edit');
    }).fail(function(data){
        callback(data);
        button_state_preoperative($(this).data('oper'));
    });
}

function textare_init_preoperative(){
    $('textarea#pt_unknown,textarea#consent').each(function () {
        if(this.value.trim() == ''){
            this.setAttribute('style', 'height:' + (40) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }else{
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }
    }).off().on('input', function () {
        if(this.scrollHeight>40){
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        }else{
            this.style.height = (40) + 'px';
        }
    });
}

$('#tab_preoperative').on('shown.bs.collapse', function () {
    SmoothScrollTo('#tab_preoperative', 300);
    
    if($('#mrn_preoperative').val() != ''){
        getdata_preoperative();
    }
});

$('#tab_preoperative').on('hide.bs.collapse', function () {
    emptyFormdata_div("#form_preoperative",['#mrn_preoperative','#episno_preoperative']);
    button_state_preoperative('empty');
});

function getdata_preoperative(){
    var urlparam={
        action:'get_table_preoperative',
    }
    
    var postobj={
        _token : $('#_token').val(),
        mrn:$('#mrn_preoperative').val(),
        episno:$("#episno_preoperative").val()
    };
    
    $.post( "./preoperative/form?"+$.param(urlparam), $.param(postobj), function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).done(function(data){
        if(!$.isEmptyObject(data)){
            button_state_preoperative('edit');
            autoinsert_rowdata("#form_preoperative",data.otmanage);
            textare_init_preoperative();
        }else{
            button_state_preoperative('add');
            textare_init_preoperative();
        }
    });
}

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

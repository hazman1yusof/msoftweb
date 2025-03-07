
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {

    textare_init_preoperativeDC();
    
    var fdl = new faster_detail_load();
    
    disableForm('#form_preoperativeDC');
    
    $("#new_preoperativeDC").click(function(){
        $('#cancel_preoperativeDC').data('oper','add');
        button_state_preoperativeDC('wait');
        enableForm('#form_preoperativeDC');
        rdonly('#form_preoperativeDC');
        // emptyFormdata_div("#form_preoperativeDC",['#mrn_preoperativeDC','#episno_preoperativeDC']);
        // dialog_mrn_edit.on();
        
    });
    
    $("#edit_preoperativeDC").click(function(){
        button_state_preoperativeDC('wait');
        enableForm('#form_preoperativeDC');
        rdonly('#form_preoperativeDC');
        // dialog_mrn_edit.on();
        
    });
    
    $("#save_preoperativeDC").click(function(){
        if( $('#form_preoperativeDC').isValid({requiredFields: ''}, conf, true) ) {
            saveForm_preoperativeDC(function(data){
                // emptyFormdata_div("#form_preoperativeDC",['#mrn_preoperativeDC','#episno_preoperativeDC']);
                disableForm('#form_preoperativeDC');
                
            });
        }else{
            enableForm('#form_preoperativeDC');
            rdonly('#form_preoperativeDC');
        }
        
    });
    
    $("#cancel_preoperativeDC").click(function(){
        // emptyFormdata_div("#form_preoperativeDC",['#mrn_preoperativeDC','#episno_preoperativeDC']);
        disableForm('#form_preoperativeDC');
        button_state_preoperativeDC($(this).data('oper'));
        getdata_preoperativeDC();
        // dialog_mrn_edit.off();
        
    });
    
    // $("#side_op_na").change(function(){
    //     $('input[name="side_op_mark"]').removeAttr("checked");
    // });
    
    $("#side_op_na").click(function(){
        if($('#side_op_na').is(":checked")){
            $("input[name='side_op_mark']").each(function(){
                if(($(this).val() == "1") || ($(this).val() == "0")){
                    $(this).prop("checked",false);
                }
            });
        }
    });
    
    $("input[name='side_op_mark']").click(function(){
        if($(this).is(':checked')){
            $("#side_op_na").prop("checked", false);
        }
    })
    
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

button_state_preoperativeDC('empty');
function button_state_preoperativeDC(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_preoperativeDC").removeAttr('data-toggle');
            $('#cancel_preoperativeDC').data('oper','add');
            $('#new_preoperativeDC,#save_preoperativeDC,#cancel_preoperativeDC,#edit_preoperativeDC').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_preoperativeDC").attr('data-toggle','collapse');
            $('#cancel_preoperativeDC').data('oper','add');
            $("#new_preoperativeDC").attr('disabled',false);
            $('#save_preoperativeDC,#cancel_preoperativeDC,#edit_preoperativeDC').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_preoperativeDC").attr('data-toggle','collapse');
            $('#cancel_preoperativeDC').data('oper','edit');
            $("#edit_preoperativeDC").attr('disabled',false);
            $('#save_preoperativeDC,#cancel_preoperativeDC,#new_preoperativeDC').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_preoperativeDC").attr('data-toggle','collapse');
            $("#save_preoperativeDC,#cancel_preoperativeDC").attr('disabled',false);
            $('#edit_preoperativeDC,#new_preoperativeDC').attr('disabled',true);
            break;
        case 'disableAll':
            $("#toggle_preoperativeDC").attr('data-toggle','collapse');
            $('#new_preoperativeDC,#edit_preoperativeDC,#save_preoperativeDC,#cancel_preoperativeDC').attr('disabled',true);
            break;
    }
}

function empty_preoperativeDC(){
    emptyFormdata_div("#form_preoperativeDC");
    button_state_preoperativeDC('empty');
    
    // panel header
    $('#name_show_preoperativeDC').text('');
    $('#mrn_show_preoperativeDC').text('');
    $('#icpssprt_show_preoperativeDC').text('');
    $('#sex_show_preoperativeDC').text('');
    $('#height_show_preoperativeDC').text('');
    $('#weight_show_preoperativeDC').text('');
    $('#dob_show_preoperativeDC').text('');
    $('#age_show_preoperativeDC').text('');
    $('#race_show_preoperativeDC').text('');
    $('#religion_show_preoperativeDC').text('');
    $('#occupation_show_preoperativeDC').text('');
    $('#citizenship_show_preoperativeDC').text('');
    $('#area_show_preoperativeDC').text('');
    $('#ward_show_preoperativeDC').text('');
    $('#bednum_show_preoperativeDC').text('');
    $('#oproom_show_preoperativeDC').text('');
    $('#diagnosis_show_preoperativeDC').text('');
    $('#procedure_show_preoperativeDC').text('');
    $('#unit_show_preoperativeDC').text('');
    $('#type_show_preoperativeDC').text('');
    
    // form_preoperativeDC
    $('#mrn_preoperativeDC').val('');
    $("#episno_preoperativeDC").val('');
}

function populate_preoperativeDC(obj){
    // panel header
    $('#name_show_preoperativeDC').text(obj.pat_name);
    $('#mrn_show_preoperativeDC').text(("0000000" + obj.mrn).slice(-7));
    $('#icpssprt_show_preoperativeDC').text(obj.icnum);
    $('#sex_show_preoperativeDC').text(if_none(obj.Sex).toUpperCase());
    $('#height_show_preoperativeDC').text(obj.height+' (CM)');
    $('#weight_show_preoperativeDC').text(obj.weight+' (KG)');
    $('#dob_show_preoperativeDC').text(dob_chg(obj.DOB));
    $('#age_show_preoperativeDC').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_preoperativeDC').text(if_none(obj.RaceCode).toUpperCase());
    $('#religion_show_preoperativeDC').text(if_none(obj.Religion).toUpperCase());
    $('#occupation_show_preoperativeDC').text(if_none(obj.OccupCode).toUpperCase());
    $('#citizenship_show_preoperativeDC').text(if_none(obj.Citizencode).toUpperCase());
    $('#area_show_preoperativeDC').text(if_none(obj.AreaCode).toUpperCase());
    $('#ward_show_preoperativeDC').text(obj.ward);
    $('#bednum_show_preoperativeDC').text(obj.bednum);
    $('#oproom_show_preoperativeDC').text(obj.ot_description);
    $('#diagnosis_show_preoperativeDC').text(obj.appt_diag);
    $('#procedure_show_preoperativeDC').text(obj.appt_prcdure);
    $('#unit_show_preoperativeDC').text(obj.op_unit);
    $('#type_show_preoperativeDC').text(obj.oper_type);
    
    // form_preoperativeDC
    $('#mrn_preoperativeDC').val(obj.mrn);
    $("#episno_preoperativeDC").val(obj.latest_episno);
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

function saveForm_preoperativeDC(callback){
    let oper = $("#cancel_preoperativeDC").data('oper');
    var saveParam={
        action:'save_table_preoperativeDC',
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
    
    values = $("#form_preoperativeDC").serializeArray();
    
    values = values.concat(
        $('#form_preoperativeDC input[type=checkbox]:not(:checked)').map(
        function() {
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#form_preoperativeDC input[type=checkbox]:checked').map(
        function() {
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#form_preoperativeDC input[type=radio]:checked').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#form_preoperativeDC select').map(
        function() {
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post( "./preoperativeDC/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').done(function(data) {
        callback(data);
        button_state_preoperativeDC('edit');
    }).fail(function(data){
        callback(data);
        button_state_preoperativeDC($(this).data('oper'));
    });
}

function textare_init_preoperativeDC(){
    $('textarea#pat_remark,textarea#cons_remark,textarea#check_side_remark,textarea#side_op_remark,textarea#lastmeal_remark,textarea#check_item_remark,textarea#allergies_remark,textarea#implant_remark,textarea#premed_remark,textarea#blood_remark,textarea#casenotes_remark,textarea#oldnotes_remark,textarea#imaging_remark,textarea#vs_remark,textarea#others_remark,textarea#imprtnt_issues').each(function () {
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

$('#tab_preoperativeDC').on('shown.bs.collapse', function () {
    SmoothScrollTo('#tab_preoperativeDC', 300,114);
    
    if($('#mrn_preoperativeDC').val() != ''){
        getdata_preoperativeDC();
    }
});

$('#tab_preoperativeDC').on('hide.bs.collapse', function () {
    emptyFormdata_div("#form_preoperativeDC",['#mrn_preoperativeDC','#episno_preoperativeDC']);
    button_state_preoperativeDC('empty');
});

function getdata_preoperativeDC(){
    var urlparam={
        action:'get_table_preoperativeDC',
    }
    
    var postobj={
        _token : $('#_token').val(),
        mrn:$('#mrn_preoperativeDC').val(),
        episno:$("#episno_preoperativeDC").val()
    };
    
    $.post( "./preoperativeDC/form?"+$.param(urlparam), $.param(postobj), function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).done(function(data){
        if(!$.isEmptyObject(data)){
            button_state_preoperativeDC('edit');
            autoinsert_rowdata("#form_preoperativeDC",data.preop);
            textare_init_preoperativeDC();
        }else{
            button_state_preoperativeDC('add');
            textare_init_preoperativeDC();
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


$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    textare_init_otdischarge();
    
    var fdl = new faster_detail_load();
    
    disableForm('#form_otdischarge');
    
    $("#new_otdischarge").click(function (){
        $('#cancel_otdischarge').data('oper','add');
        button_state_otdischarge('wait');
        enableForm('#form_otdischarge');
        rdonly('#form_otdischarge');
        // $("#predischg_pat_remark,#predischg_consciouslvl_remark,#predischg_vitalsign_remark,#predischg_checksite_remark,#predischg_checkdrains_remark,#predischg_checkiv_remark,#predischg_blood_remark,#predischg_specimen_remark,#predischg_docs_remark,#predischg_imgstudies_remark,#predischg_painrelief_remark,#predischg_others_remark,#predischg_arterial_remark,#predischg_pcapump_remark,#predischg_addmore1_remark,#predischg_addmore2_remark,#predischg_addmore3_remark,#predischg_addmore4_remark,#predischg_addmore5_remark,#predischg_addmore6_remark").prop("readonly",true);
        // emptyFormdata_div("#form_otdischarge",['#mrn_otdischarge','#episno_otdischarge']);
        // dialog_mrn_edit.on();
    });
    
    $("#edit_otdischarge").click(function (){
        button_state_otdischarge('wait');
        enableForm('#form_otdischarge');
        rdonly('#form_otdischarge');
        // $("#predischg_pat_remark,#predischg_consciouslvl_remark,#predischg_vitalsign_remark,#predischg_checksite_remark,#predischg_checkdrains_remark,#predischg_checkiv_remark,#predischg_blood_remark,#predischg_specimen_remark,#predischg_docs_remark,#predischg_imgstudies_remark,#predischg_painrelief_remark,#predischg_others_remark,#predischg_arterial_remark,#predischg_pcapump_remark,#predischg_addmore1_remark,#predischg_addmore2_remark,#predischg_addmore3_remark,#predischg_addmore4_remark,#predischg_addmore5_remark,#predischg_addmore6_remark").prop("readonly",true);
        // dialog_mrn_edit.on();
    });
    
    $("#save_otdischarge").click(function (){
        if($('#form_otdischarge').isValid({requiredFields: ''}, conf, true)){
            saveForm_otdischarge(function (data){
                // emptyFormdata_div("#form_otdischarge",['#mrn_otdischarge','#episno_otdischarge']);
                disableForm('#form_otdischarge');
            });
        }else{
            enableForm('#form_otdischarge');
            rdonly('#form_otdischarge');
            // $("#predischg_pat_remark,#predischg_consciouslvl_remark,#predischg_vitalsign_remark,#predischg_checksite_remark,#predischg_checkdrains_remark,#predischg_checkiv_remark,#predischg_blood_remark,#predischg_specimen_remark,#predischg_docs_remark,#predischg_imgstudies_remark,#predischg_painrelief_remark,#predischg_others_remark,#predischg_arterial_remark,#predischg_pcapump_remark,#predischg_addmore1_remark,#predischg_addmore2_remark,#predischg_addmore3_remark,#predischg_addmore4_remark,#predischg_addmore5_remark,#predischg_addmore6_remark").prop("readonly",true);
        }
    });
    
    $("#cancel_otdischarge").click(function (){
        // emptyFormdata_div("#form_otdischarge",['#mrn_otdischarge','#episno_otdischarge']);
        disableForm('#form_otdischarge');
        button_state_otdischarge($(this).data('oper'));
        getdata_otdischarge();
        // dialog_mrn_edit.off();
    });
    
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

button_state_otdischarge('empty');
function button_state_otdischarge(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_otdischarge").removeAttr('data-toggle');
            $('#cancel_otdischarge').data('oper','add');
            $('#new_otdischarge,#save_otdischarge,#cancel_otdischarge,#edit_otdischarge').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_otdischarge").attr('data-toggle','collapse');
            $('#cancel_otdischarge').data('oper','add');
            $("#new_otdischarge").attr('disabled',false);
            $('#save_otdischarge,#cancel_otdischarge,#edit_otdischarge').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_otdischarge").attr('data-toggle','collapse');
            $('#cancel_otdischarge').data('oper','edit');
            $("#edit_otdischarge").attr('disabled',false);
            $('#save_otdischarge,#cancel_otdischarge,#new_otdischarge').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_otdischarge").attr('data-toggle','collapse');
            $("#save_otdischarge,#cancel_otdischarge").attr('disabled',false);
            $('#edit_otdischarge,#new_otdischarge').attr('disabled',true);
            break;
        case 'disableAll':
            $("#toggle_otdischarge").attr('data-toggle','collapse');
            $('#new_otdischarge,#edit_otdischarge,#save_otdischarge,#cancel_otdischarge').attr('disabled',true);
            break;
    }
}

function empty_otdischarge(){
    emptyFormdata_div("#form_otdischarge");
    button_state_otdischarge('empty');
    
    // panel header
    $('#name_show_otdischarge').text('');
    $('#mrn_show_otdischarge').text('');
    $('#icpssprt_show_otdischarge').text('');
    $('#sex_show_otdischarge').text('');
    $('#height_show_otdischarge').text('');
    $('#weight_show_otdischarge').text('');
    $('#dob_show_otdischarge').text('');
    $('#age_show_otdischarge').text('');
    $('#race_show_otdischarge').text('');
    $('#religion_show_otdischarge').text('');
    $('#occupation_show_otdischarge').text('');
    $('#citizenship_show_otdischarge').text('');
    $('#area_show_otdischarge').text('');
    $('#ward_show_otdischarge').text('');
    $('#bednum_show_otdischarge').text('');
    $('#oproom_show_otdischarge').text('');
    $('#diagnosis_show_otdischarge').text('');
    $('#procedure_show_otdischarge').text('');
    $('#unit_show_otdischarge').text('');
    $('#type_show_otdischarge').text('');
    
    // form_otdischarge
    $('#mrn_otdischarge').val('');
    $("#episno_otdischarge").val('');
}

function populate_otdischarge(obj){
    // panel header
    $('#name_show_otdischarge').text(obj.pat_name);
    $('#mrn_show_otdischarge').text(("0000000" + obj.mrn).slice(-7));
    $('#icpssprt_show_otdischarge').text(obj.icnum);
    $('#sex_show_otdischarge').text(if_none(obj.Sex).toUpperCase());
    $('#height_show_otdischarge').text(obj.height+' (CM)');
    $('#weight_show_otdischarge').text(obj.weight+' (KG)');
    $('#dob_show_otdischarge').text(dob_chg(obj.DOB));
    $('#age_show_otdischarge').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_otdischarge').text(if_none(obj.RaceCode).toUpperCase());
    $('#religion_show_otdischarge').text(if_none(obj.Religion).toUpperCase());
    $('#occupation_show_otdischarge').text(if_none(obj.OccupCode).toUpperCase());
    $('#citizenship_show_otdischarge').text(if_none(obj.Citizencode).toUpperCase());
    $('#area_show_otdischarge').text(if_none(obj.AreaCode).toUpperCase());
    $('#ward_show_otdischarge').text(obj.ward);
    $('#bednum_show_otdischarge').text(obj.bednum);
    $('#oproom_show_otdischarge').text(obj.ot_description);
    $('#diagnosis_show_otdischarge').text(obj.appt_diag);
    $('#procedure_show_otdischarge').text(obj.appt_prcdure);
    $('#unit_show_otdischarge').text(obj.op_unit);
    $('#type_show_otdischarge').text(obj.oper_type);
    
    // form_otdischarge
    $('#mrn_otdischarge').val(obj.mrn);
    $("#episno_otdischarge").val(obj.latest_episno);
    
    $("#tab_otdischarge").collapse('hide');
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

function saveForm_otdischarge(callback){
    let oper = $("#cancel_otdischarge").data('oper');
    var saveParam = {
        action: 'save_table_otdischarge',
        oper: oper,
    }
    
    if(oper == 'add'){
        saveParam.sel_date = $('#sel_date').val();
    }else if(oper == 'edit'){
        // var row = docnote_date_tbl.row('.active').data();
        saveParam.sel_date = $('#sel_date').val();
        // saveParam.recordtime = row.recordtime;
    }
    
    var postobj = {
        _token: $('#_token').val(),
        // sex_edit: $('#sex_edit').val(),
        // idtype_edit: $('#idtype_edit').val()
    };
    
    values = $("#form_otdischarge").serializeArray();
    
    values = values.concat(
        $('#form_otdischarge input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#form_otdischarge input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#form_otdischarge input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#form_otdischarge select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./otdischarge/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_otdischarge('edit');
    }).fail(function (data){
        callback(data);
        button_state_otdischarge($(this).data('oper'));
    });
}

function textare_init_otdischarge(){
    $('textarea#predischg_pat_remark,textarea#predischg_consciouslvl_remark,textarea#predischg_vitalsign_remark,textarea#predischg_checksite_remark,textarea#predischg_checkdrains_remark,textarea#predischg_checkiv_remark,textarea#predischg_blood_remark,textarea#predischg_specimen_remark,textarea#predischg_docs_remark,textarea#predischg_imgstudies_remark,textarea#predischg_painrelief_remark,textarea#predischg_others_remark,textarea#predischg_arterial_remark,textarea#predischg_pcapump_remark,textarea#predischg_addmore1,textarea#predischg_addmore1_remark,textarea#predischg_addmore2,textarea#predischg_addmore2_remark,textarea#predischg_addmore3,textarea#predischg_addmore3_remark,textarea#predischg_addmore4,textarea#predischg_addmore4_remark,textarea#predischg_addmore5,textarea#predischg_addmore5_remark,textarea#predischg_addmore6,textarea#predischg_addmore6_remark').each(function (){
        if(this.value.trim() == ''){
            this.setAttribute('style', 'height:' + (40) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }else{
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }
    }).off().on('input', function (){
        if(this.scrollHeight > 40){
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        }else{
            this.style.height = (40) + 'px';
        }
    });
}

$('#tab_otdischarge').on('shown.bs.collapse', function (){
    SmoothScrollTo('#tab_otdischarge', 300, 114);
    
    if($('#mrn_otdischarge').val() != ''){
        getdata_otdischarge();
    }
});

$('#tab_otdischarge').on('hide.bs.collapse', function (){
    emptyFormdata_div("#form_otdischarge",['#mrn_otdischarge','#episno_otdischarge']);
    button_state_otdischarge('empty');
});

function getdata_otdischarge(){
    var urlparam = {
        action: 'get_table_otdischarge',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_otdischarge').val(),
        episno: $("#episno_otdischarge").val()
    };
    
    $.post("./otdischarge/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            button_state_otdischarge('edit');
            autoinsert_rowdata("#form_otdischarge",data.otdischarge);
            textare_init_otdischarge();
        }else{
            button_state_otdischarge('add');
            textare_init_otdischarge();
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
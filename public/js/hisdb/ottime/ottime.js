
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // textarea_init_ottime();
    
    var fdl = new faster_detail_load();
    
    $('#arrive_time,#in_time,#start_time,#end_time,#recovery_time,#depart_time')
        .calendar({
            type: 'time',
            formatter: {
                time: 'HH:mm',
                cellTime: 'HH:mm'
            }
        });
    
    disableForm('#form_ottime');
    
    $("#new_ottime").click(function (){
        $('#cancel_ottime').data('oper','add');
        button_state_ottime('wait');
        enableForm('#form_ottime');
        rdonly('#form_ottime');
        // emptyFormdata_div("#form_ottime",['#mrn_ottime','#episno_ottime']);
        // dialog_mrn_edit.on();
    });
    
    $("#edit_ottime").click(function (){
        button_state_ottime('wait');
        enableForm('#form_ottime');
        rdonly('#form_ottime');
        // dialog_mrn_edit.on();
    });
    
    $("#save_ottime").click(function (){
        if($('#form_ottime').isValid({requiredFields: ''}, conf, true)){
            saveForm_ottime(function (data){
                // emptyFormdata_div("#form_ottime",['#mrn_ottime','#episno_ottime']);
                disableForm('#form_ottime');
            });
        }else{
            enableForm('#form_ottime');
            rdonly('#form_ottime');
        }
    });
    
    $("#cancel_ottime").click(function (){
        // emptyFormdata_div("#form_ottime",['#mrn_ottime','#episno_ottime']);
        disableForm('#form_ottime');
        button_state_ottime($(this).data('oper'));
        getdata_ottime();
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

button_state_ottime('empty');
function button_state_ottime(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_ottime").removeAttr('data-toggle');
            $('#cancel_ottime').data('oper','add');
            $('#new_ottime,#save_ottime,#cancel_ottime,#edit_ottime').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_ottime").attr('data-toggle','collapse');
            $('#cancel_ottime').data('oper','add');
            $("#new_ottime").attr('disabled',false);
            $('#save_ottime,#cancel_ottime,#edit_ottime').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_ottime").attr('data-toggle','collapse');
            $('#cancel_ottime').data('oper','edit');
            $("#edit_ottime").attr('disabled',false);
            $('#save_ottime,#cancel_ottime,#new_ottime').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_ottime").attr('data-toggle','collapse');
            $("#save_ottime,#cancel_ottime").attr('disabled',false);
            $('#edit_ottime,#new_ottime').attr('disabled',true);
            break;
        case 'disableAll':
            $("#toggle_ottime").attr('data-toggle','collapse');
            $('#new_ottime,#edit_ottime,#save_ottime,#cancel_ottime').attr('disabled',true);
            break;
    }
}

function empty_ottime(){
    emptyFormdata_div("#form_ottime");
    button_state_ottime('empty');
    
    // panel header
    $('#name_show_ottime').text('');
    $('#mrn_show_ottime').text('');
    $('#icpssprt_show_ottime').text('');
    $('#sex_show_ottime').text('');
    $('#height_show_ottime').text('');
    $('#weight_show_ottime').text('');
    $('#dob_show_ottime').text('');
    $('#age_show_ottime').text('');
    $('#race_show_ottime').text('');
    $('#religion_show_ottime').text('');
    $('#occupation_show_ottime').text('');
    $('#citizenship_show_ottime').text('');
    $('#area_show_ottime').text('');
    $('#ward_show_ottime').text('');
    $('#bednum_show_ottime').text('');
    $('#oproom_show_ottime').text('');
    $('#diagnosis_show_ottime').text('');
    $('#procedure_show_ottime').text('');
    $('#unit_show_ottime').text('');
    $('#type_show_ottime').text('');
    
    // form_ottime
    $('#mrn_ottime').val('');
    $("#episno_ottime").val('');
}

function populate_ottime(obj){
    // panel header
    $('#name_show_ottime').text(obj.pat_name);
    $('#mrn_show_ottime').text(("0000000" + obj.mrn).slice(-7));
    $('#icpssprt_show_ottime').text(obj.icnum);
    $('#sex_show_ottime').text(if_none(obj.Sex).toUpperCase());
    $('#height_show_ottime').text(obj.height+' (CM)');
    $('#weight_show_ottime').text(obj.weight+' (KG)');
    $('#dob_show_ottime').text(dob_chg(obj.DOB));
    $('#age_show_ottime').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_ottime').text(if_none(obj.RaceCode).toUpperCase());
    $('#religion_show_ottime').text(if_none(obj.Religion).toUpperCase());
    $('#occupation_show_ottime').text(if_none(obj.OccupCode).toUpperCase());
    $('#citizenship_show_ottime').text(if_none(obj.Citizencode).toUpperCase());
    $('#area_show_ottime').text(if_none(obj.AreaCode).toUpperCase());
    $('#ward_show_ottime').text(obj.ward);
    $('#bednum_show_ottime').text(obj.bednum);
    $('#oproom_show_ottime').text(obj.ot_description);
    $('#diagnosis_show_ottime').text(obj.appt_diag);
    $('#procedure_show_ottime').text(obj.appt_prcdure);
    $('#unit_show_ottime').text(obj.op_unit);
    $('#type_show_ottime').text(obj.oper_type);
    
    // form_ottime
    $('#mrn_ottime').val(obj.mrn);
    $("#episno_ottime").val(obj.latest_episno);
    
    $("#tab_ottime").collapse('hide');
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

function saveForm_ottime(callback){
    let oper = $("#cancel_ottime").data('oper');
    var saveParam = {
        action: 'save_table_ottime',
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
    
    values = $("#form_ottime").serializeArray();
    
    values = values.concat(
        $('#form_ottime input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#form_ottime input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#form_ottime input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#form_ottime select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./ottime/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_ottime('edit');
    }).fail(function (data){
        callback(data);
        button_state_ottime($(this).data('oper'));
    });
}

function textarea_init_ottime(){
    $('textarea#hlthcareAsst,textarea#otCleanedBy,textarea#remarks,textarea#vendor,textarea#type_anaesth,textarea#anaesth,textarea#diagnosis,textarea#procedure').each(function (){
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

$('#tab_ottime').on('shown.bs.collapse', function (){
    SmoothScrollTo('#tab_ottime', 300, 114);
    
    if($('#mrn_ottime').val() != ''){
        getdata_ottime();
    }
});

$('#tab_ottime').on('hide.bs.collapse', function (){
    emptyFormdata_div("#form_ottime",['#mrn_ottime','#episno_ottime']);
    button_state_ottime('empty');
});

function getdata_ottime(){
    var urlparam = {
        action: 'get_table_ottime',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_ottime').val(),
        episno: $("#episno_ottime").val()
    };
    
    $.post("./ottime/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data.ottime)){
            button_state_ottime('edit');
            autoinsert_rowdata("#form_ottime",data.ottime);
        }else{
            button_state_ottime('add');
        }
        
        if(!emptyobj_(data.iPesakit))$("#ottime_iPesakit").val(data.iPesakit);
        // textarea_init_ottime();
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
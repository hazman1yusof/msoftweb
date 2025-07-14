
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    var fdl = new faster_detail_load();
    
    disableForm('#form_otmgmt_div');
    
    $("#new_otmgmt_div").click(function (){
        $('#cancel_otmgmt_div').data('oper','add');
        button_state_otmgmt_div('wait');
        enableForm('#form_otmgmt_div');
        rdonly('#form_otmgmt_div');
        // emptyFormdata_div("#form_otmgmt_div",['#mrn_otmgmt_div','#episno_otmgmt_div']);
        // dialog_mrn_edit.on();
    });
    
    $("#edit_otmgmt_div").click(function (){
        button_state_otmgmt_div('wait');
        enableForm('#form_otmgmt_div');
        rdonly('#form_otmgmt_div');
        // dialog_mrn_edit.on();
    });
    
    $("#save_otmgmt_div").click(function (){
        if($('#form_otmgmt_div').isValid({requiredFields: ''}, conf, true)){
            saveForm_otmgmt_div(function (data){
                // emptyFormdata_div("#form_otmgmt_div",['#mrn_otmgmt_div','#episno_otmgmt_div']);
                disableForm('#form_otmgmt_div');
            });
        }else{
            enableForm('#form_otmgmt_div');
            rdonly('#form_otmgmt_div');
        }
    });
    
    $("#cancel_otmgmt_div").click(function (){
        // emptyFormdata_div("#form_otmgmt_div",['#mrn_otmgmt_div','#episno_otmgmt_div']);
        disableForm('#form_otmgmt_div');
        button_state_otmgmt_div($(this).data('oper'));
        getdata_otmgmt();
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
    
    // to calculate hours utilized
    $("#timestarted,#timeended").on('change',function (){
        var startTime = moment($('#timestarted').val(),'hh:mm:ss');
        var endTime = moment($('#timeended').val(),'hh:mm:ss');
        
        let duration = endTime.diff(startTime,'hours');
        $("#hoursutilized").val(duration);
    });
    
    //////////////////////////////////////////body diagram starts//////////////////////////////////////////
    $('a.ui.card.oper_rec').click(function (){
        let mrn = $('#mrn_otmgmt_div').val();
        let episno = $('#episno_otmgmt_div').val();
        let type = $(this).data('type');
        let istablet = $(window).width() <= 1024;
        
        if(mrn.trim() == '' || episno.trim() == '' || type.trim() == ''){
            alert('Please choose Patient First');
        }else if($('#save_otmgmt_div').prop('disabled')){
            alert('Edit this patient first');
        }else{
            if(istablet){
                let filename = type+'_'+mrn+'_'+episno+'.pdf';
                let url = $('#urltodiagram').val() + filename;
                var win = window.open(url, '_blank');
            }else{
                var win = window.open('http://localhost:8080/foxitweb/public/pdf?mrn='+mrn+'&episno='+episno+'&type='+type+'&from=otmgmt_div', '_blank');
            }
            
            if(win){
                win.focus();
            }else{
                alert('Please allow popups for this website');
            }
        }
    });
    ///////////////////////////////////////////body diagram ends///////////////////////////////////////////
    
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

button_state_otmgmt_div('empty');
function button_state_otmgmt_div(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_otmgmt_div").removeAttr('data-toggle');
            $('#cancel_otmgmt_div').data('oper','add');
            $('#new_otmgmt_div,#save_otmgmt_div,#cancel_otmgmt_div,#edit_otmgmt_div').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_otmgmt_div").attr('data-toggle','collapse');
            $('#cancel_otmgmt_div').data('oper','add');
            $("#new_otmgmt_div").attr('disabled',false);
            $('#save_otmgmt_div,#cancel_otmgmt_div,#edit_otmgmt_div').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_otmgmt_div").attr('data-toggle','collapse');
            $('#cancel_otmgmt_div').data('oper','edit');
            $("#edit_otmgmt_div").attr('disabled',false);
            $('#save_otmgmt_div,#cancel_otmgmt_div,#new_otmgmt_div').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_otmgmt_div").attr('data-toggle','collapse');
            $("#save_otmgmt_div,#cancel_otmgmt_div").attr('disabled',false);
            $('#edit_otmgmt_div,#new_otmgmt_div').attr('disabled',true);
            break;
        case 'disableAll':
            $("#toggle_otmgmt_div").attr('data-toggle','collapse');
            $('#new_otmgmt_div,#edit_otmgmt_div,#save_otmgmt_div,#cancel_otmgmt_div').attr('disabled',true);
            break;
    }
}

function empty_otmgmt_div(){
    emptyFormdata_div("#form_otmgmt_div");
    button_state_otmgmt_div('empty');
    
    // panel header
    $('#name_show_otmgmt_div').text('');
    $('#mrn_show_otmgmt_div').text('');
    $('#icpssprt_show_otmgmt_div').text('');
    $('#sex_show_otmgmt_div').text('');
    $('#height_show_otmgmt_div').text('');
    $('#weight_show_otmgmt_div').text('');
    $('#dob_show_otmgmt_div').text('');
    $('#age_show_otmgmt_div').text('');
    $('#race_show_otmgmt_div').text('');
    $('#religion_show_otmgmt_div').text('');
    $('#occupation_show_otmgmt_div').text('');
    $('#citizenship_show_otmgmt_div').text('');
    $('#area_show_otmgmt_div').text('');
    $('#ward_show_otmgmt_div').text('');
    $('#bednum_show_otmgmt_div').text('');
    $('#oproom_show_otmgmt_div').text('');
    $('#diagnosis_show_otmgmt_div').text('');
    $('#procedure_show_otmgmt_div').text('');
    $('#unit_show_otmgmt_div').text('');
    $('#type_show_otmgmt_div').text('');
    
    // form_otmgmt_div
    $('#mrn_otmgmt_div').val('');
    $("#episno_otmgmt_div").val('');
}

function populate_otmgmt_div(obj){
    // panel header
    $('#name_show_otmgmt_div').text(obj.pat_name);
    $('#mrn_show_otmgmt_div').text(("0000000" + obj.mrn).slice(-7));
    $('#icpssprt_show_otmgmt_div').text(obj.icnum);
    $('#sex_show_otmgmt_div').text(if_none(obj.Sex).toUpperCase());
    $('#height_show_otmgmt_div').text(obj.height+' (CM)');
    $('#weight_show_otmgmt_div').text(obj.weight+' (KG)');
    $('#dob_show_otmgmt_div').text(dob_chg(obj.DOB));
    $('#age_show_otmgmt_div').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_otmgmt_div').text(if_none(obj.RaceCode).toUpperCase());
    $('#religion_show_otmgmt_div').text(if_none(obj.Religion).toUpperCase());
    $('#occupation_show_otmgmt_div').text(if_none(obj.OccupCode).toUpperCase());
    $('#citizenship_show_otmgmt_div').text(if_none(obj.Citizencode).toUpperCase());
    $('#area_show_otmgmt_div').text(if_none(obj.AreaCode).toUpperCase());
    $('#ward_show_otmgmt_div').text(obj.ward);
    $('#bednum_show_otmgmt_div').text(obj.bednum);
    $('#oproom_show_otmgmt_div').text(obj.ot_description);
    $('#diagnosis_show_otmgmt_div').text(obj.appt_diag);
    $('#procedure_show_otmgmt_div').text(obj.appt_prcdure);
    $('#unit_show_otmgmt_div').text(obj.op_unit);
    $('#type_show_otmgmt_div').text(obj.oper_type);
    
    // form_otmgmt_div
    $('#mrn_otmgmt_div').val(obj.mrn);
    $("#episno_otmgmt_div").val(obj.latest_episno);
    $('#ward').val(obj.ward);
    
    $("#tab_otmgmt_div").collapse('hide');
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

function saveForm_otmgmt_div(callback){
    let oper = $("#cancel_otmgmt_div").data('oper');
    var saveParam = {
        action: 'save_table_otmgmt_div',
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
    
    values = $("#form_otmgmt_div").serializeArray();
    
    values = values.concat(
        $('#form_otmgmt_div input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#form_otmgmt_div input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#form_otmgmt_div input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#form_otmgmt_div select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./otmanagement_div/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_otmgmt_div('edit');
    }).fail(function (data){
        callback(data);
        button_state_otmgmt_div($(this).data('oper'));
    });
}

$('#tab_otmgmt_div').on('shown.bs.collapse', function (){
    SmoothScrollTo('#tab_otmgmt_div', 300,114);
    
    if($('#mrn_otmgmt_div').val() != ''){
        getdata_otmgmt();
    }
});

$('#tab_otmgmt_div').on('hide.bs.collapse', function (){
    emptyFormdata_div("#form_otmgmt_div",['#mrn_otmgmt_div','#episno_otmgmt_div']);
    button_state_otmgmt_div('empty');
});

function getdata_otmgmt(){
    var urlparam = {
        action: 'get_table_otmanage',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_otmgmt_div').val(),
        episno: $("#episno_otmgmt_div").val()
    };
    
    $.post("./otmanagement_div/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data.otmanage)){
            button_state_otmgmt_div('edit');
            autoinsert_rowdata("#form_otmgmt_div",data.otmanage);
            // autoinsert_rowdata("#form_otmgmt_div",data.apptbook);
            // autoinsert_rowdata("#form_otmgmt_div",data.episode);
            // $('#timestarted').val(data.start);
            // $('#timeended').val(data.end).change();
            // $('#form_otmgmt_div textarea#procedure').val(data.apptbook.procedure);
            // $('#form_otmgmt_div textarea#diagnosis').val(data.apptbook.diagnosis);
        }else{
            button_state_otmgmt_div('add');
            // $('#form_otmgmt_div textarea#procedure').val(data.apptbook.procedure);
            // $('#form_otmgmt_div textarea#diagnosis').val(data.apptbook.diagnosis);
        }
        
        autoinsert_rowdata("#form_otmgmt_div",data.apptbook);
        autoinsert_rowdata("#form_otmgmt_div",data.episode);
        $('#timestarted').val(data.start);
        $('#timeended').val(data.end).change();
        if(!emptyobj_(data.iPesakit))$("#operRec_iPesakit").val(data.iPesakit);
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

$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    textarea_init_endoscopyStomach();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formEndoscopyStomach');
    
    $("#new_endoscopyStomach").click(function (){
        $('#cancel_endoscopyStomach').data('oper','add');
        button_state_endoscopyStomach('wait');
        enableForm('#formEndoscopyStomach');
        rdonly('#formEndoscopyStomach');
        // emptyFormdata_div("#formEndoscopyStomach",['#mrn_endoscopyNotes','#episno_endoscopyNotes']);
        // dialog_mrn_edit.on();
    });
    
    $("#edit_endoscopyStomach").click(function (){
        button_state_endoscopyStomach('wait');
        enableForm('#formEndoscopyStomach');
        rdonly('#formEndoscopyStomach');
        // dialog_mrn_edit.on();
    });
    
    $("#save_endoscopyStomach").click(function (){
        if($('#formEndoscopyStomach').isValid({requiredFields: ''}, conf, true)){
            saveForm_endoscopyStomach(function (data){
                // emptyFormdata_div("#formEndoscopyStomach",['#mrn_endoscopyNotes','#episno_endoscopyNotes']);
                disableForm('#formEndoscopyStomach');
            });
        }else{
            enableForm('#formEndoscopyStomach');
            rdonly('#formEndoscopyStomach');
        }
    });
    
    $("#cancel_endoscopyStomach").click(function (){
        // emptyFormdata_div("#formEndoscopyStomach",['#mrn_endoscopyNotes','#episno_endoscopyNotes']);
        disableForm('#formEndoscopyStomach');
        button_state_endoscopyStomach($(this).data('oper'));
        getdata_endoscopyStomach();
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
    
    //////////////////////////////////////////body diagram starts//////////////////////////////////////////
    $('a.ui.card.bodydia_endoscopyStomach').click(function (){
        let mrn = $('#mrn_endoscopyNotes').val();
        let episno = $('#episno_endoscopyNotes').val();
        let type = $(this).data('type');
        let istablet = $(window).width() <= 1024;
        
        if(mrn.trim() == '' || type.trim() == ''){
            alert('Please choose Patient First');
        }else if($('#save_endoscopyStomach').prop('disabled')){
            alert('Edit this patient first');
        }else{
            if(istablet){
                let filename = type+'_'+mrn+'_.pdf';
                let url = $('#urltodiagram').val() + filename;
                var win = window.open(url, '_blank');
            }else{
                var win = window.open('http://localhost:8080/foxitweb/public/pdf?mrn='+mrn+'&episno='+episno+'&type='+type+'&from=endoscopyStomach', '_blank');
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

button_state_endoscopyStomach('empty');
function button_state_endoscopyStomach(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_endoscopyNotes").removeAttr('data-toggle');
            $('#cancel_endoscopyStomach').data('oper','add');
            $('#new_endoscopyStomach,#save_endoscopyStomach,#cancel_endoscopyStomach,#edit_endoscopyStomach').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_endoscopyNotes").attr('data-toggle','collapse');
            $('#cancel_endoscopyStomach').data('oper','add');
            $("#new_endoscopyStomach").attr('disabled',false);
            $('#save_endoscopyStomach,#cancel_endoscopyStomach,#edit_endoscopyStomach').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_endoscopyNotes").attr('data-toggle','collapse');
            $('#cancel_endoscopyStomach').data('oper','edit');
            $("#edit_endoscopyStomach").attr('disabled',false);
            $('#save_endoscopyStomach,#cancel_endoscopyStomach,#new_endoscopyStomach').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_endoscopyNotes").attr('data-toggle','collapse');
            $("#save_endoscopyStomach,#cancel_endoscopyStomach").attr('disabled',false);
            $('#edit_endoscopyStomach,#new_endoscopyStomach').attr('disabled',true);
            break;
    }
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

function saveForm_endoscopyStomach(callback){
    let oper = $("#cancel_endoscopyStomach").data('oper');
    var saveParam = {
        action: 'save_table_endoscopyStomach',
        oper: oper,
        mrn: $('#mrn_endoscopyNotes').val(),
        episno: $("#episno_endoscopyNotes").val(),
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
    
    values = $("#formEndoscopyStomach").serializeArray();
    
    values = values.concat(
        $('#formEndoscopyStomach input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formEndoscopyStomach input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formEndoscopyStomach input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formEndoscopyStomach select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./endoscopyNotes/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_endoscopyStomach('edit');
    }).fail(function (data){
        callback(data);
        button_state_endoscopyStomach($(this).data('oper'));
    });
}

function textarea_init_endoscopyStomach(){
    $('textarea#endoscopyStomach_previousScopy,textarea#endoscopyStomach_complaints,textarea#endoscopyStomach_oesophagus,textarea#endoscopyStomach_stomach,textarea#endoscopyStomach_duodenum,textarea#endoscopyStomach_remarks,textarea#endoscopyStomach_treatment').each(function (){
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

function getdata_endoscopyStomach(){
    var urlparam = {
        action: 'get_table_endoscopyStomach',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_endoscopyNotes').val(),
        episno: $("#episno_endoscopyNotes").val()
    };
    
    $.post("./endoscopyNotes/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            button_state_endoscopyStomach('edit');
            autoinsert_rowdata("#formEndoscopyStomach",data.endoscopyStomach);
            textarea_init_endoscopyStomach();
        }else{
            button_state_endoscopyStomach('add');
            textarea_init_endoscopyStomach();
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
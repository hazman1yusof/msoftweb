
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    textarea_init_endoscopyIntestine();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formEndoscopyIntestine');
    
    $("#new_endoscopyIntestine").click(function (){
        $('#cancel_endoscopyIntestine').data('oper','add');
        button_state_endoscopyIntestine('wait');
        enableForm('#formEndoscopyIntestine');
        rdonly('#formEndoscopyIntestine');
        // emptyFormdata_div("#formEndoscopyIntestine",['#mrn_endoscopyNotes','#episno_endoscopyNotes']);
        // dialog_mrn_edit.on();
    });
    
    $("#edit_endoscopyIntestine").click(function (){
        button_state_endoscopyIntestine('wait');
        enableForm('#formEndoscopyIntestine');
        rdonly('#formEndoscopyIntestine');
        // dialog_mrn_edit.on();
    });
    
    $("#save_endoscopyIntestine").click(function (){
        if($('#formEndoscopyIntestine').isValid({requiredFields: ''}, conf, true)){
            saveForm_endoscopyIntestine(function (data){
                // emptyFormdata_div("#formEndoscopyIntestine",['#mrn_endoscopyNotes','#episno_endoscopyNotes']);
                disableForm('#formEndoscopyIntestine');
            });
        }else{
            enableForm('#formEndoscopyIntestine');
            rdonly('#formEndoscopyIntestine');
        }
    });
    
    $("#cancel_endoscopyIntestine").click(function (){
        // emptyFormdata_div("#formEndoscopyIntestine",['#mrn_endoscopyNotes','#episno_endoscopyNotes']);
        disableForm('#formEndoscopyIntestine');
        button_state_endoscopyIntestine($(this).data('oper'));
        getdata_endoscopyIntestine();
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
    $('a.ui.card.bodydia_endoscopyIntestine').click(function (){
        let mrn = $('#mrn_endoscopyNotes').val();
        let episno = $('#episno_endoscopyNotes').val();
        let type = $(this).data('type');
        let istablet = $(window).width() <= 1024;
        
        if(mrn.trim() == '' || type.trim() == ''){
            alert('Please choose Patient First');
        }else if($('#save_endoscopyIntestine').prop('disabled')){
            alert('Edit this patient first');
        }else{
            if(istablet){
                let filename = type+'_'+mrn+'_.pdf';
                let url = $('#urltodiagram').val() + filename;
                var win = window.open(url, '_blank');
            }else{
                var win = window.open('http://localhost:8080/foxitweb/public/pdf?mrn='+mrn+'&episno='+episno+'&type='+type+'&from=endoscopyIntestine', '_blank');
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

button_state_endoscopyIntestine('empty');
function button_state_endoscopyIntestine(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_endoscopyNotes").removeAttr('data-toggle');
            $('#cancel_endoscopyIntestine').data('oper','add');
            $('#new_endoscopyIntestine,#save_endoscopyIntestine,#cancel_endoscopyIntestine,#edit_endoscopyIntestine').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_endoscopyNotes").attr('data-toggle','collapse');
            $('#cancel_endoscopyIntestine').data('oper','add');
            $("#new_endoscopyIntestine").attr('disabled',false);
            $('#save_endoscopyIntestine,#cancel_endoscopyIntestine,#edit_endoscopyIntestine').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_endoscopyNotes").attr('data-toggle','collapse');
            $('#cancel_endoscopyIntestine').data('oper','edit');
            $("#edit_endoscopyIntestine").attr('disabled',false);
            $('#save_endoscopyIntestine,#cancel_endoscopyIntestine,#new_endoscopyIntestine').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_endoscopyNotes").attr('data-toggle','collapse');
            $("#save_endoscopyIntestine,#cancel_endoscopyIntestine").attr('disabled',false);
            $('#edit_endoscopyIntestine,#new_endoscopyIntestine').attr('disabled',true);
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

function saveForm_endoscopyIntestine(callback){
    let oper = $("#cancel_endoscopyIntestine").data('oper');
    var saveParam = {
        action: 'save_table_endoscopyIntestine',
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
    
    values = $("#formEndoscopyIntestine").serializeArray();
    
    values = values.concat(
        $('#formEndoscopyIntestine input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formEndoscopyIntestine input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formEndoscopyIntestine input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formEndoscopyIntestine select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./endoscopyNotes/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_endoscopyIntestine('edit');
    }).fail(function (data){
        callback(data);
        button_state_endoscopyIntestine($(this).data('oper'));
    });
}

function textarea_init_endoscopyIntestine(){
    $('textarea#endoscopyIntestine_perRectum,textarea#endoscopyIntestine_otherIllness,textarea#endoscopyIntestine_endosFindings,textarea#endoscopyIntestine_biopsy,textarea#endoscopyIntestine_otherProcedure,textarea#endoscopyIntestine_endosImpression,textarea#endoscopyIntestine_remarks').each(function (){
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

function getdata_endoscopyIntestine(){
    var urlparam = {
        action: 'get_table_endoscopyIntestine',
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
            button_state_endoscopyIntestine('edit');
            autoinsert_rowdata("#formEndoscopyIntestine",data.endoscopyintestine);
            textarea_init_endoscopyIntestine();
        }else{
            button_state_endoscopyIntestine('add');
            textarea_init_endoscopyIntestine();
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
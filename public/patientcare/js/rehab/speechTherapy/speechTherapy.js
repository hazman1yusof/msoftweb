
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // textarea_init_speechTherapy();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formSpeechTherapy');
    
    $("#new_speechTherapy").click(function (){
        // get_default_speechTherapy();
        $('#cancel_speechTherapy').data('oper','add');
        button_state_speechTherapy('wait');
        enableForm('#formSpeechTherapy');
        rdonly('#formSpeechTherapy');
        emptyFormdata_div("#formSpeechTherapy",['#mrn_rehabMain','#episno_rehabMain']);
        document.getElementById("idno_speechTherapy").value = "";
        // dialog_mrn_edit.on();
    });
    
    $("#edit_speechTherapy").click(function (){
        button_state_speechTherapy('wait');
        enableForm('#formSpeechTherapy');
        rdonly('#formSpeechTherapy');
        // dialog_mrn_edit.on();
    });
    
    $("#save_speechTherapy").click(function (){
        if($('#formSpeechTherapy').isValid({requiredFields: ''}, conf, true)){
            saveForm_speechTherapy(function (data){
                $("#cancel_speechTherapy").data('oper','edit');
                $("#cancel_speechTherapy").click();
                // emptyFormdata_div("#formSpeechTherapy",['#mrn_rehabMain','#episno_rehabMain']);
                disableForm('#formSpeechTherapy');
            });
        }else{
            enableForm('#formSpeechTherapy');
            rdonly('#formSpeechTherapy');
        }
    });
    
    $("#cancel_speechTherapy").click(function (){
        // emptyFormdata_div("#formSpeechTherapy",['#mrn_rehabMain','#episno_rehabMain']);
        disableForm('#formSpeechTherapy');
        button_state_speechTherapy($(this).data('oper'));
        $('#tbl_speechTherapy_date').DataTable().ajax.reload();
        getdata_speechTherapy();
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
    
    ///////////////////////////////////////speechTherapy starts///////////////////////////////////////
    $('#tbl_speechTherapy_date tbody').on('click', 'tr', function (){
        var data = tbl_speechTherapy_date.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_speechTherapy_date.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formSpeechTherapy",['#mrn_rehabMain','#episno_rehabMain']);
        $('#tbl_speechTherapy_date tbody tr').removeClass('active');
        $(this).addClass('active');
        
        // if(check_same_usr_edit(data)){
        //     button_state_speechTherapy('edit');
        // }else{
            button_state_speechTherapy('add');
        // }
        $('#speechTherapy_chart').attr('disabled',false);
        
        // getdata_speechTherapy();
        $("#idno_speechTherapy").val(data.idno);
        
        var saveParam = {
            action: 'get_table_speechTherapy',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./speechTherapy/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data.speechtherapy)){
                autoinsert_rowdata("#formSpeechTherapy",data.speechtherapy);
                // button_state_speechTherapy('edit');
            }else{
                // button_state_speechTherapy('add');
            }
            
            // textarea_init_speechTherapy();
        });
    });
    ////////////////////////////////////////speechTherapy ends////////////////////////////////////////
    
    $("#speechTherapy_chart").click(function (){
        window.open('./speechTherapy/speechtherapy_chart?mrn='+$('#mrn_rehabMain').val()+'&episno='+$("#episno_rehabMain").val()+'&entereddate='+$("#speechTherapy_entereddate").val()+'&enteredtime='+$("#speechTherapy_enteredtime").val()+'&age='+$("#age_rehabMain").val(), '_blank');
    });
    
});

///////////////////////speechTherapy starts///////////////////////
var tbl_speechTherapy_date = $('#tbl_speechTherapy_date').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'entereddate', 'width': '25%' },
        { 'data': 'dt' },
        { 'data': 'adduser', 'width': '50%' },
    ],
    columnDefs: [
        { targets: [0, 1, 2, 4], visible: false },
    ],
    order: [[4, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
////////////////////////speechTherapy ends////////////////////////

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

button_state_speechTherapy('empty');
function button_state_speechTherapy(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_physio").removeAttr('data-toggle');
            $('#cancel_speechTherapy').data('oper','add');
            $('#new_speechTherapy,#save_speechTherapy,#cancel_speechTherapy,#edit_speechTherapy,#speechTherapy_chart').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_speechTherapy').data('oper','add');
            $("#new_speechTherapy").attr('disabled',false);
            $('#save_speechTherapy,#cancel_speechTherapy,#edit_speechTherapy').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_speechTherapy').data('oper','edit');
            $("#new_speechTherapy,#edit_speechTherapy").attr('disabled',false);
            $('#save_speechTherapy,#cancel_speechTherapy').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_physio").attr('data-toggle','collapse');
            $("#save_speechTherapy,#cancel_speechTherapy").attr('disabled',false);
            $('#edit_speechTherapy,#new_speechTherapy,#speechTherapy_chart').attr('disabled',true);
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

function saveForm_speechTherapy(callback){
    let oper = $("#cancel_speechTherapy").data('oper');
    var saveParam = {
        action: 'save_table_speechTherapy',
        oper: oper,
        mrn: $('#mrn_rehabMain').val(),
        episno: $("#episno_rehabMain").val(),
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
    
    values = $("#formSpeechTherapy").serializeArray();
    
    values = values.concat(
        $('#formSpeechTherapy input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formSpeechTherapy input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formSpeechTherapy input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formSpeechTherapy select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./speechTherapy/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        // button_state_speechTherapy('edit');
        button_state_speechTherapy('add');
    }).fail(function (data){
        if(data.responseText !== ''){
            // $('#p_error_intake').text(data.responseText);
            alert(data.responseText);
        }
        
        callback(data);
        button_state_speechTherapy($(this).data('oper'));
    });
}

function textarea_init_speechTherapy(){
    $('textarea#speechTherapy_notes').each(function (){
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

function getdata_speechTherapy(){
    var urlparam = {
        action: 'get_table_speechTherapy',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_rehabMain').val(),
        episno: $("#episno_rehabMain").val()
    };
    
    $.post("./speechTherapy/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data.speechtherapy)){
            autoinsert_rowdata("#formSpeechTherapy",data.speechtherapy);
            // button_state_speechTherapy('edit');
            $('#speechTherapy_chart').attr('disabled',false);
        }else{
            // button_state_speechTherapy('add');
            $('#speechTherapy_chart').attr('disabled',true);
        }
        
        button_state_speechTherapy('add');
        // textarea_init_speechTherapy();
    });
}

function get_default_speechTherapy(){
    var urlparam = {
        action: 'get_table_speechTherapy',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_rehabMain').val(),
        episno: $("#episno_rehabMain").val()
    };
    
    $.post("./speechTherapy/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data.speechtherapy)){
            autoinsert_rowdata("#formSpeechTherapy",data.speechtherapy);
            // button_state_speechTherapy('edit');
        }else{
            // button_state_speechTherapy('add');
        }
        
        // textarea_init_speechTherapy();
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
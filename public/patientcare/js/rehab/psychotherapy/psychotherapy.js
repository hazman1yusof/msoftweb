
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // textarea_init_psychotherapy();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formPsychotherapy');
    
    $("#new_psychotherapy").click(function (){
        // get_default_psychotherapy();
        $('#cancel_psychotherapy').data('oper','add');
        button_state_psychotherapy('wait');
        enableForm('#formPsychotherapy');
        rdonly('#formPsychotherapy');
        emptyFormdata_div("#formPsychotherapy",['#mrn_rehabMain','#episno_rehabMain']);
        document.getElementById("idno_psychotherapy").value = "";
        // dialog_mrn_edit.on();
    });
    
    $("#edit_psychotherapy").click(function (){
        button_state_psychotherapy('wait');
        enableForm('#formPsychotherapy');
        rdonly('#formPsychotherapy');
        // dialog_mrn_edit.on();
    });
    
    $("#save_psychotherapy").click(function (){
        if($('#formPsychotherapy').isValid({requiredFields: ''}, conf, true)){
            saveForm_psychotherapy(function (data){
                $("#cancel_psychotherapy").data('oper','edit');
                $("#cancel_psychotherapy").click();
                // emptyFormdata_div("#formPsychotherapy",['#mrn_rehabMain','#episno_rehabMain']);
                disableForm('#formPsychotherapy');
            });
        }else{
            enableForm('#formPsychotherapy');
            rdonly('#formPsychotherapy');
        }
    });
    
    $("#cancel_psychotherapy").click(function (){
        // emptyFormdata_div("#formPsychotherapy",['#mrn_rehabMain','#episno_rehabMain']);
        disableForm('#formPsychotherapy');
        button_state_psychotherapy($(this).data('oper'));
        $('#tbl_psychotherapy_date').DataTable().ajax.reload();
        getdata_psychotherapy();
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
    
    ///////////////////////////////////////psychotherapy starts///////////////////////////////////////
    $('#tbl_psychotherapy_date tbody').on('click', 'tr', function (){
        var data = tbl_psychotherapy_date.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_psychotherapy_date.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formPsychotherapy",['#mrn_rehabMain','#episno_rehabMain']);
        $('#tbl_psychotherapy_date tbody tr').removeClass('active');
        $(this).addClass('active');
        
        if(check_same_usr_edit(data)){
            button_state_psychotherapy('edit');
        }else{
            button_state_psychotherapy('add');
        }
        $('#psychotherapy_chart').attr('disabled',false);
        
        // getdata_psychotherapy();
        $("#idno_psychotherapy").val(data.idno);
        
        var saveParam = {
            action: 'get_table_psychotherapy',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./psychotherapy/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data.psychotherapy)){
                autoinsert_rowdata("#formPsychotherapy",data.psychotherapy);
                // button_state_psychotherapy('edit');
            }else{
                // button_state_psychotherapy('add');
            }
            
            // textarea_init_psychotherapy();
        });
    });
    ////////////////////////////////////////psychotherapy ends////////////////////////////////////////
    
    $("#psychotherapy_chart").click(function (){
        window.open('./psychotherapy/psychotherapy_chart?mrn='+$('#mrn_rehabMain').val()+'&episno='+$("#episno_rehabMain").val()+'&entereddate='+$("#psychotherapy_entereddate").val()+'&age='+$("#age_rehabMain").val(), '_blank');
    });
    
});

///////////////////////psychotherapy starts///////////////////////
var tbl_psychotherapy_date = $('#tbl_psychotherapy_date').DataTable({
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
////////////////////////psychotherapy ends////////////////////////

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

button_state_psychotherapy('empty');
function button_state_psychotherapy(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_physio").removeAttr('data-toggle');
            $('#cancel_psychotherapy').data('oper','add');
            $('#new_psychotherapy,#save_psychotherapy,#cancel_psychotherapy,#edit_psychotherapy,#psychotherapy_chart').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_psychotherapy').data('oper','add');
            $("#new_psychotherapy").attr('disabled',false);
            $('#save_psychotherapy,#cancel_psychotherapy,#edit_psychotherapy').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_psychotherapy').data('oper','edit');
            $("#new_psychotherapy,#edit_psychotherapy").attr('disabled',false);
            $('#save_psychotherapy,#cancel_psychotherapy').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_physio").attr('data-toggle','collapse');
            $("#save_psychotherapy,#cancel_psychotherapy").attr('disabled',false);
            $('#edit_psychotherapy,#new_psychotherapy,#psychotherapy_chart').attr('disabled',true);
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

function saveForm_psychotherapy(callback){
    let oper = $("#cancel_psychotherapy").data('oper');
    var saveParam = {
        action: 'save_table_psychotherapy',
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
    
    values = $("#formPsychotherapy").serializeArray();
    
    values = values.concat(
        $('#formPsychotherapy input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formPsychotherapy input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formPsychotherapy input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formPsychotherapy select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./psychotherapy/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_psychotherapy('edit');
    }).fail(function (data){
        if(data.responseText !== ''){
            // $('#p_error_intake').text(data.responseText);
            alert(data.responseText);
        }
        
        callback(data);
        button_state_psychotherapy($(this).data('oper'));
    });
}

function textarea_init_psychotherapy(){
    $('textarea#psychotherapy_notes').each(function (){
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

function getdata_psychotherapy(){
    var urlparam = {
        action: 'get_table_psychotherapy',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_rehabMain').val(),
        episno: $("#episno_rehabMain").val()
    };
    
    $.post("./psychotherapy/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data.psychotherapy)){
            autoinsert_rowdata("#formPsychotherapy",data.psychotherapy);
            button_state_psychotherapy('edit');
            $('#psychotherapy_chart').attr('disabled',false);
        }else{
            button_state_psychotherapy('add');
            $('#psychotherapy_chart').attr('disabled',true);
        }
        
        // textarea_init_psychotherapy();
    });
}

function get_default_psychotherapy(){
    var urlparam = {
        action: 'get_table_psychotherapy',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_rehabMain').val(),
        episno: $("#episno_rehabMain").val()
    };
    
    $.post("./psychotherapy/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data.psychotherapy)){
            autoinsert_rowdata("#formPsychotherapy",data.psychotherapy);
            // button_state_psychotherapy('edit');
        }else{
            // button_state_psychotherapy('add');
        }
        
        // textarea_init_psychotherapy();
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
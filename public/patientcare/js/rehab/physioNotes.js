
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // textarea_init_physioNotes();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formPhysioNotes');
    
    $("#new_physioNotes").click(function (){
        // get_default_physioNotes();
        $('#cancel_physioNotes').data('oper','add');
        button_state_physioNotes('wait');
        enableForm('#formPhysioNotes');
        rdonly('#formPhysioNotes');
        emptyFormdata_div("#formPhysioNotes",['#mrn_physio','#episno_physio']);
        document.getElementById("idno_physioNotes").value = "";
        // dialog_mrn_edit.on();
    });
    
    $("#edit_physioNotes").click(function (){
        button_state_physioNotes('wait');
        enableForm('#formPhysioNotes');
        rdonly('#formPhysioNotes');
        // dialog_mrn_edit.on();
    });
    
    $("#save_physioNotes").click(function (){
        if($('#formPhysioNotes').isValid({requiredFields: ''}, conf, true)){
            saveForm_physioNotes(function (data){
                $("#cancel_physioNotes").data('oper','edit');
                $("#cancel_physioNotes").click();
                // emptyFormdata_div("#formPhysioNotes",['#mrn_physio','#episno_physio']);
                disableForm('#formPhysioNotes');
            });
        }else{
            enableForm('#formPhysioNotes');
            rdonly('#formPhysioNotes');
        }
    });
    
    $("#cancel_physioNotes").click(function (){
        // emptyFormdata_div("#formPhysioNotes",['#mrn_physio','#episno_physio']);
        disableForm('#formPhysioNotes');
        button_state_physioNotes($(this).data('oper'));
        $('#tbl_physioNotes_date').DataTable().ajax.reload();
        getdata_physioNotes();
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
    
    ///////////////////////////////////////physioNotes starts///////////////////////////////////////
    $('#tbl_physioNotes_date tbody').on('click', 'tr', function (){
        var data = tbl_physioNotes_date.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_physioNotes_date.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formPhysioNotes",['#mrn_physio','#episno_physio']);
        $('#tbl_physioNotes_date tbody tr').removeClass('active');
        $(this).addClass('active');
        
        if(check_same_usr_edit(data)){
            button_state_physioNotes('edit');
        }else{
            button_state_physioNotes('add');
        }
        $('#physioNotes_chart').attr('disabled',false);
        
        // getdata_physioNotes();
        $("#idno_physioNotes").val(data.idno);
        
        var saveParam = {
            action: 'get_table_physioNotes',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./physioNotes/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data.notes)){
                autoinsert_rowdata("#formPhysioNotes",data.notes);
                // button_state_physioNotes('edit');
            }else{
                // button_state_physioNotes('add');
            }
            
            // textarea_init_physioNotes();
        });
    });
    ////////////////////////////////////////physioNotes ends////////////////////////////////////////
    
    $("#physioNotes_chart").click(function (){
        window.open('./physioNotes/physionotes_chart?mrn='+$('#mrn_physio').val()+'&episno='+$("#episno_physio").val()+'&entereddate='+$("#physioNotes_entereddate").val()+'&age='+$("#age_physio").val(), '_blank');
    });
    
});

///////////////////////physioNotes starts///////////////////////
var tbl_physioNotes_date = $('#tbl_physioNotes_date').DataTable({
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
////////////////////////physioNotes ends////////////////////////

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

button_state_physioNotes('empty');
function button_state_physioNotes(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_physio").removeAttr('data-toggle');
            $('#cancel_physioNotes').data('oper','add');
            $('#new_physioNotes,#save_physioNotes,#cancel_physioNotes,#edit_physioNotes,#physioNotes_chart').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_physioNotes').data('oper','add');
            $("#new_physioNotes").attr('disabled',false);
            $('#save_physioNotes,#cancel_physioNotes,#edit_physioNotes').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_physioNotes').data('oper','edit');
            $("#new_physioNotes,#edit_physioNotes").attr('disabled',false);
            $('#save_physioNotes,#cancel_physioNotes').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_physio").attr('data-toggle','collapse');
            $("#save_physioNotes,#cancel_physioNotes").attr('disabled',false);
            $('#edit_physioNotes,#new_physioNotes,#physioNotes_chart').attr('disabled',true);
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

function saveForm_physioNotes(callback){
    let oper = $("#cancel_physioNotes").data('oper');
    var saveParam = {
        action: 'save_table_physioNotes',
        oper: oper,
        mrn: $('#mrn_physio').val(),
        episno: $("#episno_physio").val(),
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
    
    values = $("#formPhysioNotes").serializeArray();
    
    values = values.concat(
        $('#formPhysioNotes input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formPhysioNotes input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formPhysioNotes input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formPhysioNotes select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./physioNotes/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_physioNotes('edit');
    }).fail(function (data){
        if(data.responseText !== ''){
            // $('#p_error_intake').text(data.responseText);
            alert(data.responseText);
        }
        
        callback(data);
        button_state_physioNotes($(this).data('oper'));
    });
}

function textarea_init_physioNotes(){
    $('textarea#physioNotes_notes').each(function (){
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

function getdata_physioNotes(){
    var urlparam = {
        action: 'get_table_physioNotes',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_physio').val(),
        episno: $("#episno_physio").val()
    };
    
    $.post("./physioNotes/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data.notes)){
            autoinsert_rowdata("#formPhysioNotes",data.notes);
            button_state_physioNotes('edit');
            $('#physioNotes_chart').attr('disabled',false);
        }else{
            button_state_physioNotes('add');
            $('#physioNotes_chart').attr('disabled',true);
        }
        
        // textarea_init_physioNotes();
    });
}

function get_default_physioNotes(){
    var urlparam = {
        action: 'get_table_physioNotes',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_physio').val(),
        episno: $("#episno_physio").val()
    };
    
    $.post("./physioNotes/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data.notes)){
            autoinsert_rowdata("#formPhysioNotes",data.notes);
            // button_state_physioNotes('edit');
        }else{
            // button_state_physioNotes('add');
        }
        
        // textarea_init_physioNotes();
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
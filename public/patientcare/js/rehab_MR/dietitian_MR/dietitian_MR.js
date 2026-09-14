
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // textarea_init_dietitian();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formDietitian');
    
    $("#new_dietitian").click(function (){
        // get_default_dietitian();
        $('#cancel_dietitian').data('oper','add');
        button_state_dietitian('wait');
        enableForm('#formDietitian');
        rdonly('#formDietitian');
        emptyFormdata_div("#formDietitian",['#mrn_rehabMain','#episno_rehabMain']);
        document.getElementById("idno_dietitian").value = "";
        // dialog_mrn_edit.on();
    });
    
    $("#edit_dietitian").click(function (){
        button_state_dietitian('wait');
        enableForm('#formDietitian');
        rdonly('#formDietitian');
        // dialog_mrn_edit.on();
    });
    
    $("#save_dietitian").click(function (){
        if($('#formDietitian').isValid({requiredFields: ''}, conf, true)){
            saveForm_dietitian(function (data){
                $("#cancel_dietitian").data('oper','edit');
                $("#cancel_dietitian").click();
                // emptyFormdata_div("#formDietitian",['#mrn_rehabMain','#episno_rehabMain']);
                disableForm('#formDietitian');
            });
        }else{
            enableForm('#formDietitian');
            rdonly('#formDietitian');
        }
    });
    
    $("#cancel_dietitian").click(function (){
        // emptyFormdata_div("#formDietitian",['#mrn_rehabMain','#episno_rehabMain']);
        disableForm('#formDietitian');
        button_state_dietitian($(this).data('oper'));
        $('#tbl_dietitian_date').DataTable().ajax.reload();
        getdata_dietitian();
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
    
    ///////////////////////////////////////dietitian starts///////////////////////////////////////
    $('#tbl_dietitian_date tbody').on('click', 'tr', function (){
        var data = tbl_dietitian_date.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_dietitian_date.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formDietitian",['#mrn_rehabMain','#episno_rehabMain']);
        $('#tbl_dietitian_date tbody tr').removeClass('active');
        $(this).addClass('active');
        
        // if(check_same_usr_edit(data)){
        //     button_state_dietitian('edit');
        // }else{
            button_state_dietitian('add');
        // }
        $('#dietitian_chart').attr('disabled',false);
        
        // getdata_dietitian();
        $("#idno_dietitian").val(data.idno);
        
        var saveParam = {
            action: 'get_table_dietitian',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./dietitian/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data.dietitian)){
                autoinsert_rowdata("#formDietitian",data.dietitian);
                // button_state_dietitian('edit');
            }else{
                // button_state_dietitian('add');
            }
            
            // textarea_init_dietitian();
        });
    });
    ////////////////////////////////////////dietitian ends////////////////////////////////////////
    
    $("#dietitian_chart").click(function (){
        window.open('./dietitian/dietitian_chart?mrn='+$('#mrn_rehabMain').val()+'&episno='+$("#episno_rehabMain").val()+'&entereddate='+$("#dietitian_entereddate").val()+'&enteredtime='+$("#dietitian_enteredtime").val()+'&age='+$("#age_rehabMain").val(), '_blank');
    });
    
});

///////////////////////dietitian starts///////////////////////
var tbl_dietitian_date = $('#tbl_dietitian_date').DataTable({
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
////////////////////////dietitian ends////////////////////////

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

button_state_dietitian('empty');
function button_state_dietitian(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_physio").removeAttr('data-toggle');
            $('#cancel_dietitian').data('oper','add');
            $('#new_dietitian,#save_dietitian,#cancel_dietitian,#edit_dietitian,#dietitian_chart').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_dietitian').data('oper','add');
            $("#new_dietitian").attr('disabled',false);
            $('#save_dietitian,#cancel_dietitian,#edit_dietitian').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_dietitian').data('oper','edit');
            $("#new_dietitian,#edit_dietitian").attr('disabled',false);
            $('#save_dietitian,#cancel_dietitian').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_physio").attr('data-toggle','collapse');
            $("#save_dietitian,#cancel_dietitian").attr('disabled',false);
            $('#edit_dietitian,#new_dietitian,#dietitian_chart').attr('disabled',true);
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

function saveForm_dietitian(callback){
    let oper = $("#cancel_dietitian").data('oper');
    var saveParam = {
        action: 'save_table_dietitian',
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
    
    values = $("#formDietitian").serializeArray();
    
    values = values.concat(
        $('#formDietitian input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formDietitian input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formDietitian input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formDietitian select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./dietitian/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        // button_state_dietitian('edit');
        button_state_dietitian('add');
    }).fail(function (data){
        if(data.responseText !== ''){
            // $('#p_error_intake').text(data.responseText);
            alert(data.responseText);
        }
        
        callback(data);
        button_state_dietitian($(this).data('oper'));
    });
}

function textarea_init_dietitian(){
    $('textarea#dietitian_notes').each(function (){
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

function getdata_dietitian(){
    var urlparam = {
        action: 'get_table_dietitian',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_rehabMain').val(),
        episno: $("#episno_rehabMain").val()
    };
    
    $.post("./dietitian/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data.dietitian)){
            autoinsert_rowdata("#formDietitian",data.dietitian);
            // button_state_dietitian('edit');
            $('#dietitian_chart').attr('disabled',false);
        }else{
            // button_state_dietitian('add');
            $('#dietitian_chart').attr('disabled',true);
        }
        
        button_state_dietitian('add');
        // textarea_init_dietitian();
    });
}

function get_default_dietitian(){
    var urlparam = {
        action: 'get_table_dietitian',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_rehabMain').val(),
        episno: $("#episno_rehabMain").val()
    };
    
    $.post("./dietitian/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data.dietitian)){
            autoinsert_rowdata("#formDietitian",data.dietitian);
            // button_state_dietitian('edit');
        }else{
            // button_state_dietitian('add');
        }
        
        // textarea_init_dietitian();
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
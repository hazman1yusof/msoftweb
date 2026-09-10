
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // textarea_init_neurorobotic();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formNeurorobotic');
    
    $("#new_neurorobotic").click(function (){
        // get_default_neurorobotic();
        $('#cancel_neurorobotic').data('oper','add');
        button_state_neurorobotic('wait');
        enableForm('#formNeurorobotic');
        rdonly('#formNeurorobotic');
        emptyFormdata_div("#formNeurorobotic",['#mrn_rehabMain','#episno_rehabMain']);
        document.getElementById("idno_neurorobotic").value = "";
        // dialog_mrn_edit.on();
    });
    
    $("#edit_neurorobotic").click(function (){
        button_state_neurorobotic('wait');
        enableForm('#formNeurorobotic');
        rdonly('#formNeurorobotic');
        // dialog_mrn_edit.on();
    });
    
    $("#save_neurorobotic").click(function (){
        if($('#formNeurorobotic').isValid({requiredFields: ''}, conf, true)){
            saveForm_neurorobotic(function (data){
                $("#cancel_neurorobotic").data('oper','edit');
                $("#cancel_neurorobotic").click();
                // emptyFormdata_div("#formNeurorobotic",['#mrn_rehabMain','#episno_rehabMain']);
                disableForm('#formNeurorobotic');
            });
        }else{
            enableForm('#formNeurorobotic');
            rdonly('#formNeurorobotic');
        }
    });
    
    $("#cancel_neurorobotic").click(function (){
        // emptyFormdata_div("#formNeurorobotic",['#mrn_rehabMain','#episno_rehabMain']);
        disableForm('#formNeurorobotic');
        button_state_neurorobotic($(this).data('oper'));
        $('#tbl_neurorobotic_date').DataTable().ajax.reload();
        getdata_neurorobotic();
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
    
    ///////////////////////////////////////neurorobotic starts///////////////////////////////////////
    $('#tbl_neurorobotic_date tbody').on('click', 'tr', function (){
        var data = tbl_neurorobotic_date.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_neurorobotic_date.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formNeurorobotic",['#mrn_rehabMain','#episno_rehabMain']);
        $('#tbl_neurorobotic_date tbody tr').removeClass('active');
        $(this).addClass('active');
        
        // if(check_same_usr_edit(data)){
        //     button_state_neurorobotic('edit');
        // }else{
            button_state_neurorobotic('add');
        // }
        $('#neurorobotic_chart').attr('disabled',false);
        
        // getdata_neurorobotic();
        $("#idno_neurorobotic").val(data.idno);
        
        var saveParam = {
            action: 'get_table_neurorobotic',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./neurorobotic/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data.neurorobotic)){
                autoinsert_rowdata("#formNeurorobotic",data.neurorobotic);
                // button_state_neurorobotic('edit');
            }else{
                // button_state_neurorobotic('add');
            }
            
            // textarea_init_neurorobotic();
        });
    });
    ////////////////////////////////////////neurorobotic ends////////////////////////////////////////
    
    $("#neurorobotic_chart").click(function (){
        window.open('./neurorobotic/neurorobotic_chart?mrn='+$('#mrn_rehabMain').val()+'&episno='+$("#episno_rehabMain").val()+'&entereddate='+$("#neurorobotic_entereddate").val()+'&enteredtime='+$("#neurorobotic_enteredtime").val()+'&age='+$("#age_rehabMain").val(), '_blank');
    });
    
});

///////////////////////neurorobotic starts///////////////////////
var tbl_neurorobotic_date = $('#tbl_neurorobotic_date').DataTable({
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
////////////////////////neurorobotic ends////////////////////////

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

button_state_neurorobotic('empty');
function button_state_neurorobotic(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_physio").removeAttr('data-toggle');
            $('#cancel_neurorobotic').data('oper','add');
            $('#new_neurorobotic,#save_neurorobotic,#cancel_neurorobotic,#edit_neurorobotic,#neurorobotic_chart').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_neurorobotic').data('oper','add');
            $("#new_neurorobotic").attr('disabled',false);
            $('#save_neurorobotic,#cancel_neurorobotic,#edit_neurorobotic').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_neurorobotic').data('oper','edit');
            $("#new_neurorobotic,#edit_neurorobotic").attr('disabled',false);
            $('#save_neurorobotic,#cancel_neurorobotic').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_physio").attr('data-toggle','collapse');
            $("#save_neurorobotic,#cancel_neurorobotic").attr('disabled',false);
            $('#edit_neurorobotic,#new_neurorobotic,#neurorobotic_chart').attr('disabled',true);
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

function saveForm_neurorobotic(callback){
    let oper = $("#cancel_neurorobotic").data('oper');
    var saveParam = {
        action: 'save_table_neurorobotic',
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
    
    values = $("#formNeurorobotic").serializeArray();
    
    values = values.concat(
        $('#formNeurorobotic input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formNeurorobotic input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formNeurorobotic input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formNeurorobotic select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./neurorobotic/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        // button_state_neurorobotic('edit');
        button_state_neurorobotic('add');
    }).fail(function (data){
        if(data.responseText !== ''){
            // $('#p_error_intake').text(data.responseText);
            alert(data.responseText);
        }
        
        callback(data);
        button_state_neurorobotic($(this).data('oper'));
    });
}

function textarea_init_neurorobotic(){
    $('textarea#neurorobotic_notes').each(function (){
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

function getdata_neurorobotic(){
    var urlparam = {
        action: 'get_table_neurorobotic',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_rehabMain').val(),
        episno: $("#episno_rehabMain").val()
    };
    
    $.post("./neurorobotic/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data.neurorobotic)){
            autoinsert_rowdata("#formNeurorobotic",data.neurorobotic);
            // button_state_neurorobotic('edit');
            $('#neurorobotic_chart').attr('disabled',false);
        }else{
            // button_state_neurorobotic('add');
            $('#neurorobotic_chart').attr('disabled',true);
        }
        
        button_state_neurorobotic('add');
        // textarea_init_neurorobotic();
    });
}

function get_default_neurorobotic(){
    var urlparam = {
        action: 'get_table_neurorobotic',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_rehabMain').val(),
        episno: $("#episno_rehabMain").val()
    };
    
    $.post("./neurorobotic/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data.neurorobotic)){
            autoinsert_rowdata("#formNeurorobotic",data.neurorobotic);
            // button_state_neurorobotic('edit');
        }else{
            // button_state_neurorobotic('add');
        }
        
        // textarea_init_neurorobotic();
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
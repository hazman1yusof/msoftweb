
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // textarea_init_sixMinWalking();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formSixMinWalking');
    
    $("#new_sixMinWalking").click(function (){
        // get_default_sixMinWalking();
        $('#cancel_sixMinWalking').data('oper','add');
        button_state_sixMinWalking('wait');
        enableForm('#formSixMinWalking');
        rdonly('#formSixMinWalking');
        emptyFormdata_div("#formSixMinWalking",['#mrn_physio','#episno_physio']);
        document.getElementById("idno_sixMinWalking").value = "";
        // dialog_mrn_edit.on();
    });
    
    $("#edit_sixMinWalking").click(function (){
        button_state_sixMinWalking('wait');
        enableForm('#formSixMinWalking');
        rdonly('#formSixMinWalking');
        // dialog_mrn_edit.on();
    });
    
    $("#save_sixMinWalking").click(function (){
        if($('#formSixMinWalking').isValid({requiredFields: ''}, conf, true)){
            saveForm_sixMinWalking(function (data){
                $("#cancel_sixMinWalking").data('oper','edit');
                $("#cancel_sixMinWalking").click();
                // emptyFormdata_div("#formSixMinWalking",['#mrn_physio','#episno_physio']);
                disableForm('#formSixMinWalking');
            });
        }else{
            enableForm('#formSixMinWalking');
            rdonly('#formSixMinWalking');
        }
    });
    
    $("#cancel_sixMinWalking").click(function (){
        // emptyFormdata_div("#formSixMinWalking",['#mrn_physio','#episno_physio']);
        disableForm('#formSixMinWalking');
        button_state_sixMinWalking($(this).data('oper'));
        $('#tbl_sixMinWalking_date').DataTable().ajax.reload();
        getdata_sixMinWalking();
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
    
    //////////////////////////////////////sixMinWalking starts//////////////////////////////////////
    $('#tbl_sixMinWalking_date tbody').on('click', 'tr', function (){
        var data = tbl_sixMinWalking_date.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_sixMinWalking_date.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formSixMinWalking",['#mrn_physio','#episno_physio']);
        $('#tbl_sixMinWalking_date tbody tr').removeClass('active');
        $(this).addClass('active');
        
        // getdata_sixMinWalking();
        $("#idno_sixMinWalking").val(data.idno);
        
        var saveParam = {
            action: 'get_table_sixMinWalking',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./sixMinWalking/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data.sixminwalk)){
                autoinsert_rowdata("#formSixMinWalking",data.sixminwalk);
                button_state_sixMinWalking('edit');
            }else{
                button_state_sixMinWalking('add');
            }
            
            // $("#sixMinWalking_patName").val(data.patName);
            // $("#sixMinWalking_age").val($("#age_physio").val());
            // $("#sixMinWalking_race").val($('#race_show_physio').text());
            
            // gender_M = document.getElementById("genderM");
            // gender_F = document.getElementById("genderF");
            // if(data.gender == 'M'){
            //     gender_M.checked = true;
            // }else if(data.gender == 'F'){
            //     gender_F.checked = true;
            // }
            
            // textarea_init_sixMinWalking();
        });
    });
    ///////////////////////////////////////sixMinWalking ends///////////////////////////////////////
    
});

//////////////////////sixMinWalking starts//////////////////////
var tbl_sixMinWalking_date = $('#tbl_sixMinWalking_date').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'entereddate', 'width': '25%' },
        { 'data': 'adduser', 'width': '50%' },
    ],
    columnDefs: [
        { targets: [0, 1, 2, 4], visible: false },
    ],
    order: [[3, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
///////////////////////sixMinWalking ends///////////////////////

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

button_state_sixMinWalking('empty');
function button_state_sixMinWalking(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_physio").removeAttr('data-toggle');
            $('#cancel_sixMinWalking').data('oper','add');
            $('#new_sixMinWalking,#save_sixMinWalking,#cancel_sixMinWalking,#edit_sixMinWalking').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_sixMinWalking').data('oper','add');
            $("#new_sixMinWalking").attr('disabled',false);
            $('#save_sixMinWalking,#cancel_sixMinWalking,#edit_sixMinWalking').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_sixMinWalking').data('oper','edit');
            $("#new_sixMinWalking,#edit_sixMinWalking").attr('disabled',false);
            $('#save_sixMinWalking,#cancel_sixMinWalking').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_physio").attr('data-toggle','collapse');
            $("#save_sixMinWalking,#cancel_sixMinWalking").attr('disabled',false);
            $('#edit_sixMinWalking,#new_sixMinWalking').attr('disabled',true);
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

function saveForm_sixMinWalking(callback){
    let oper = $("#cancel_sixMinWalking").data('oper');
    var saveParam = {
        action: 'save_table_sixMinWalking',
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
    
    values = $("#formSixMinWalking").serializeArray();
    
    values = values.concat(
        $('#formSixMinWalking input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formSixMinWalking input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formSixMinWalking input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formSixMinWalking select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./sixMinWalking/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_sixMinWalking('edit');
    }).fail(function (data){
        if(data.responseText !== ''){
            // $('#p_error_intake').text(data.responseText);
            alert(data.responseText);
        }
        
        callback(data);
        button_state_sixMinWalking($(this).data('oper'));
    });
}

function textarea_init_sixMinWalking(){
    $('textarea#sixMinWalking_comments').each(function (){
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

function getdata_sixMinWalking(){
    var urlparam = {
        action: 'get_table_sixMinWalking',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_physio').val(),
        episno: $("#episno_physio").val()
    };
    
    $.post("./sixMinWalking/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data.sixminwalk)){
            autoinsert_rowdata("#formSixMinWalking",data.sixminwalk);
            button_state_sixMinWalking('edit');
        }else{
            button_state_sixMinWalking('add');
        }
        
        // $("#sixMinWalking_patName").val(data.patName);
        // $("#sixMinWalking_age").val($("#age_physio").val());
        // $("#sixMinWalking_race").val($('#race_show_physio').text());
        
        // gender_M = document.getElementById("genderM");
        // gender_F = document.getElementById("genderF");
        // if(data.gender == 'M'){
        //     gender_M.checked = true;
        // }else if(data.gender == 'F'){
        //     gender_F.checked = true;
        // }
        
        // textarea_init_sixMinWalking();
    });
}

function get_default_sixMinWalking(){
    var urlparam = {
        action: 'get_table_sixMinWalking',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_physio').val(),
        episno: $("#episno_physio").val()
    };
    
    $.post("./sixMinWalking/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data.sixminwalk)){
            autoinsert_rowdata("#formSixMinWalking",data.sixminwalk);
            // button_state_sixMinWalking('edit');
        }else{
            // button_state_sixMinWalking('add');
        }
        
        $("#sixMinWalking_patName").val(data.patName);
        $("#sixMinWalking_age").val($("#age_physio").val());
        $("#sixMinWalking_race").val($('#race_show_physio').text());
        
        gender_M = document.getElementById("genderM");
        gender_F = document.getElementById("genderF");
        if(data.gender == 'M'){
            gender_M.checked = true;
        }else if(data.gender == 'F'){
            gender_F.checked = true;
        }
        
        // textarea_init_sixMinWalking();
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
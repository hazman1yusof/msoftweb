
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // textarea_init_cardiorespAssessment();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formCardiorespAssessment');
    
    $("#new_cardiorespAssessment").click(function (){
        $('#cancel_cardiorespAssessment').data('oper','add');
        button_state_cardiorespAssessment('wait');
        enableForm('#formCardiorespAssessment');
        rdonly('#formCardiorespAssessment');
        emptyFormdata_div("#formCardiorespAssessment",['#mrn_physio','#episno_physio']);
        document.getElementById("idno_cardiorespAssessment").value = "";
        // dialog_mrn_edit.on();
    });
    
    $("#edit_cardiorespAssessment").click(function (){
        button_state_cardiorespAssessment('wait');
        enableForm('#formCardiorespAssessment');
        rdonly('#formCardiorespAssessment');
        // dialog_mrn_edit.on();
    });
    
    $("#save_cardiorespAssessment").click(function (){
        if($('#formCardiorespAssessment').isValid({requiredFields: ''}, conf, true)){
            saveForm_cardiorespAssessment(function (data){
                $("#cancel_cardiorespAssessment").data('oper','edit');
                $("#cancel_cardiorespAssessment").click();
                // emptyFormdata_div("#formCardiorespAssessment",['#mrn_physio','#episno_physio']);
                disableForm('#formCardiorespAssessment');
            });
        }else{
            enableForm('#formCardiorespAssessment');
            rdonly('#formCardiorespAssessment');
        }
    });
    
    $("#cancel_cardiorespAssessment").click(function (){
        // emptyFormdata_div("#formCardiorespAssessment",['#mrn_physio','#episno_physio']);
        disableForm('#formCardiorespAssessment');
        button_state_cardiorespAssessment($(this).data('oper'));
        $('#tbl_cardiorespAssessment_date').DataTable().ajax.reload();
        getdata_cardiorespAssessment();
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
    
    //////////////////////////////////cardiorespAssessment starts//////////////////////////////////
    $('#tbl_cardiorespAssessment_date tbody').on('click', 'tr', function (){
        var data = tbl_cardiorespAssessment_date.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_cardiorespAssessment_date.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formCardiorespAssessment",['#mrn_physio','#episno_physio']);
        $('#tbl_cardiorespAssessment_date tbody tr').removeClass('active');
        $(this).addClass('active');
        
        if(check_same_usr_edit(data)){
            button_state_cardiorespAssessment('edit');
        }else{
            button_state_cardiorespAssessment('add');
        }
        
        // getdata_cardiorespAssessment();
        $("#idno_cardiorespAssessment").val(data.idno);
        
        var saveParam = {
            action: 'get_table_cardiorespAssessment',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./cardiorespAssessment/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data.cardiorespassessment)){
                autoinsert_rowdata("#formCardiorespAssessment",data.cardiorespassessment);
                // button_state_cardiorespAssessment('edit');
            }else{
                // button_state_cardiorespAssessment('add');
            }
            
            // textarea_init_cardiorespAssessment();
        });
    });
    ///////////////////////////////////cardiorespAssessment ends///////////////////////////////////
    
    //////////////////////////////////////body diagram starts//////////////////////////////////////
    $('a.ui.card.bodydia_cardio').click(function (){
        let mrn = $('#mrn_physio').val();
        let episno = $('#episno_physio').val();
        let type = $(this).data('type');
        let istablet = $(window).width() <= 1024;
        
        if(mrn.trim() == '' || type.trim() == ''){
            alert('Please choose Patient First');
        }else if($('#save_cardiorespAssessment').prop('disabled')){
            alert('Edit this patient first');
        }else{
            if(istablet){
                let filename = type+'_'+mrn+'_.pdf';
                let url = $('#urltodiagram').val() + filename;
                var win = window.open(url, '_blank');
            }else{
                var win = window.open('http://localhost:8443/foxitweb/public/pdf?mrn='+mrn+'&episno='+episno+'&type='+type+'&from=cardiorespAssessment', '_blank');
            }
            
            if(win){
                win.focus();
            }else{
                alert('Please allow popups for this website');
            }
        }
    });
    ///////////////////////////////////////body diagram ends///////////////////////////////////////
    
});

///////////////////cardiorespAssessment starts///////////////////
var tbl_cardiorespAssessment_date = $('#tbl_cardiorespAssessment_date').DataTable({
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
////////////////////cardiorespAssessment ends////////////////////

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

button_state_cardiorespAssessment('empty');
function button_state_cardiorespAssessment(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_physio").removeAttr('data-toggle');
            $('#cancel_cardiorespAssessment').data('oper','add');
            $('#new_cardiorespAssessment,#save_cardiorespAssessment,#cancel_cardiorespAssessment,#edit_cardiorespAssessment').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_cardiorespAssessment').data('oper','add');
            $("#new_cardiorespAssessment").attr('disabled',false);
            $('#save_cardiorespAssessment,#cancel_cardiorespAssessment,#edit_cardiorespAssessment').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_cardiorespAssessment').data('oper','edit');
            $("#new_cardiorespAssessment,#edit_cardiorespAssessment").attr('disabled',false);
            $('#save_cardiorespAssessment,#cancel_cardiorespAssessment').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_physio").attr('data-toggle','collapse');
            $("#save_cardiorespAssessment,#cancel_cardiorespAssessment").attr('disabled',false);
            $('#edit_cardiorespAssessment,#new_cardiorespAssessment').attr('disabled',true);
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

function saveForm_cardiorespAssessment(callback){
    let oper = $("#cancel_cardiorespAssessment").data('oper');
    var saveParam = {
        action: 'save_table_cardiorespAssessment',
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
    
    values = $("#formCardiorespAssessment").serializeArray();
    
    values = values.concat(
        $('#formCardiorespAssessment input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formCardiorespAssessment input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formCardiorespAssessment input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formCardiorespAssessment select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./cardiorespAssessment/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_cardiorespAssessment('edit');
    }).fail(function (data){
        if(data.responseText !== ''){
            // $('#p_error_intake').text(data.responseText);
            alert(data.responseText);
        }
        
        callback(data);
        button_state_cardiorespAssessment($(this).data('oper'));
    });
}

function textarea_init_cardiorespAssessment(){
    $('textarea#cardiorespAssessment_subjectiveAssessmt,textarea#cardiorespAssessment_objectiveAssessmt,textarea#cardiorespAssessment_analysis,textarea#cardiorespAssessment_intervention,textarea#cardiorespAssessment_homeEducation,textarea#cardiorespAssessment_evaluation,textarea#cardiorespAssessment_review,textarea#cardiorespAssessment_additionalNotes').each(function (){
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

function getdata_cardiorespAssessment(){
    var urlparam = {
        action: 'get_table_cardiorespAssessment',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_physio').val(),
        episno: $("#episno_physio").val()
    };
    
    $.post("./cardiorespAssessment/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formCardiorespAssessment",data.cardiorespassessment);
            button_state_cardiorespAssessment('edit');
        }else{
            button_state_cardiorespAssessment('add');
        }
        
        // textarea_init_cardiorespAssessment();
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
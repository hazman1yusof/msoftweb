
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // textarea_init_musculoAssessment();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formMusculoAssessment');
    
    $("#new_musculoAssessment").click(function (){
        $('#cancel_musculoAssessment').data('oper','add');
        button_state_musculoAssessment('wait');
        enableForm('#formMusculoAssessment');
        rdonly('#formMusculoAssessment');
        emptyFormdata_div("#formMusculoAssessment",['#mrn_physio','#episno_physio']);
        document.getElementById("idno_musculoAssessment").value = "";
        document.getElementById("idno_affectedside").value = "";
        document.getElementById("idno_soundside").value = "";
        document.getElementById("idno_musclepwr").value = "";
        // dialog_mrn_edit.on();
    });
    
    $("#edit_musculoAssessment").click(function (){
        button_state_musculoAssessment('wait');
        enableForm('#formMusculoAssessment');
        rdonly('#formMusculoAssessment');
        // dialog_mrn_edit.on();
    });
    
    $("#save_musculoAssessment").click(function (){
        if($('#formMusculoAssessment').isValid({requiredFields: ''}, conf, true)){
            saveForm_musculoAssessment(function (data){
                $("#cancel_musculoAssessment").data('oper','edit');
                $("#cancel_musculoAssessment").click();
                // emptyFormdata_div("#formMusculoAssessment",['#mrn_physio','#episno_physio']);
                disableForm('#formMusculoAssessment');
            });
        }else{
            enableForm('#formMusculoAssessment');
            rdonly('#formMusculoAssessment');
        }
    });
    
    $("#cancel_musculoAssessment").click(function (){
        // emptyFormdata_div("#formMusculoAssessment",['#mrn_physio','#episno_physio']);
        disableForm('#formMusculoAssessment');
        button_state_musculoAssessment($(this).data('oper'));
        $('#tbl_musculoAssessment_date').DataTable().ajax.reload();
        getdata_musculoAssessment();
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
    
    ////////////////////////////////////musculoAssessment starts////////////////////////////////////
    $('#tbl_musculoAssessment_date tbody').on('click', 'tr', function (){
        var data = tbl_musculoAssessment_date.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_musculoAssessment_date.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formMusculoAssessment",['#mrn_physio','#episno_physio']);
        $('#tbl_musculoAssessment_date tbody tr').removeClass('active');
        $(this).addClass('active');
        
        if(check_same_usr_edit(data)){
            button_state_musculoAssessment('edit');
        }else{
            button_state_musculoAssessment('add');
        }
        $('#musculoAssessment_chart').attr('disabled',false);
        
        // getdata_musculoAssessment();
        $("#idno_musculoAssessment").val(data.ma_idno);
        $("#idno_affectedside").val(data.a_idno);
        $("#idno_soundside").val(data.s_idno);
        $("#idno_musclepwr").val(data.m_idno);
        
        var saveParam = {
            action: 'get_table_musculoAssessment',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            ma_idno: data.ma_idno,
            mrn: data.mrn,
            episno: data.episno,
            a_idno: data.a_idno,
            s_idno: data.s_idno,
            m_idno: data.m_idno,
        };
        
        $.post("./musculoAssessment/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data.musculoassessment)){
                autoinsert_rowdata("#formMusculoAssessment",data.musculoassessment);
                autoinsert_rowdata("#formMusculoAssessment",data.romaffectedside);
                autoinsert_rowdata("#formMusculoAssessment",data.romsoundside);
                autoinsert_rowdata("#formMusculoAssessment",data.musclepower);
                // button_state_musculoAssessment('edit');
            }else{
                // button_state_musculoAssessment('add');
            }
            
            // textarea_init_musculoAssessment();
        });
    });
    /////////////////////////////////////musculoAssessment ends/////////////////////////////////////
    
    //////////////////////////////////////body diagram starts//////////////////////////////////////
    $('a.ui.card.bodydia_musculoskeletal').click(function (){
        let mrn = $('#mrn_physio').val();
        let episno = $('#episno_physio').val();
        let entereddate = $('#musculoAssessment_entereddate').val();
        let type = $(this).data('type');
        let istablet = $(window).width() <= 1024;
        
        if(mrn.trim() == '' || type.trim() == ''){
            alert('Please choose Patient First');
        }else if($('#save_musculoAssessment').prop('disabled')){
            alert('Edit this patient first');
        }else{
            if(istablet){
                let filename = type+'_'+mrn+'_.pdf';
                let url = $('#urltodiagram').val() + filename;
                var win = window.open(url, '_blank');
            }else{
                var win = window.open('http://localhost:8443/foxitweb/public/pdf?mrn='+mrn+'&episno='+episno+'&entereddate='+entereddate+'&type='+type+'&from=musculoAssessment', '_blank');
            }
            
            if(win){
                win.focus();
            }else{
                alert('Please allow popups for this website');
            }
        }
    });
    ///////////////////////////////////////body diagram ends///////////////////////////////////////
    
    $("#musculoAssessment_chart").click(function (){
        window.open('./musculoAssessment/musculoassessment_chart?mrn='+$('#mrn_physio').val()+'&episno='+$("#episno_physio").val()+'&entereddate='+$("#musculoAssessment_entereddate").val()+'&type=DIAG_MUSCULOSKELETAL', '_blank');
    });
    
});

/////////////////////musculoAssessment starts/////////////////////
var tbl_musculoAssessment_date = $('#tbl_musculoAssessment_date').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'ma_idno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'entereddate', 'width': '25%' },
        { 'data': 'dt' },
        { 'data': 'adduser', 'width': '50%' },
        { 'data': 'a_idno' },
        { 'data': 's_idno' },
        { 'data': 'm_idno' },
    ],
    columnDefs: [
        { targets: [0, 1, 2, 4, 6, 7, 8], visible: false },
    ],
    order: [[4, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
//////////////////////musculoAssessment ends//////////////////////

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

button_state_musculoAssessment('empty');
function button_state_musculoAssessment(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_physio").removeAttr('data-toggle');
            $('#cancel_musculoAssessment').data('oper','add');
            $('#new_musculoAssessment,#save_musculoAssessment,#cancel_musculoAssessment,#edit_musculoAssessment,#musculoAssessment_chart').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_musculoAssessment').data('oper','add');
            $("#new_musculoAssessment").attr('disabled',false);
            $('#save_musculoAssessment,#cancel_musculoAssessment,#edit_musculoAssessment').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_musculoAssessment').data('oper','edit');
            $("#new_musculoAssessment,#edit_musculoAssessment").attr('disabled',false);
            $('#save_musculoAssessment,#cancel_musculoAssessment').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_physio").attr('data-toggle','collapse');
            $("#save_musculoAssessment,#cancel_musculoAssessment").attr('disabled',false);
            $('#edit_musculoAssessment,#new_musculoAssessment,#musculoAssessment_chart').attr('disabled',true);
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

function saveForm_musculoAssessment(callback){
    let oper = $("#cancel_musculoAssessment").data('oper');
    var saveParam = {
        action: 'save_table_musculoAssessment',
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
    
    values = $("#formMusculoAssessment").serializeArray();
    
    values = values.concat(
        $('#formMusculoAssessment input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formMusculoAssessment input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formMusculoAssessment input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formMusculoAssessment select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./musculoAssessment/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_musculoAssessment('edit');
    }).fail(function (data){
        if(data.responseText !== ''){
            // $('#p_error_intake').text(data.responseText);
            alert(data.responseText);
        }
        
        callback(data);
        button_state_musculoAssessment($(this).data('oper'));
    });
}

function textarea_init_musculoAssessment(){
    $('textarea#musculoAssessment_subjectiveAssessmt,textarea#musculoAssessment_objectiveAssessmt,textarea#musculoAssessment_impressionBC,textarea#musculoAssessment_impressionSens,textarea#musculoAssessment_impressionROM,textarea#musculoAssessment_impressionAMP,textarea#musculoAssessment_impressionSMP,textarea#musculoAssessment_impressionFA,textarea#musculoAssessment_intervention,textarea#musculoAssessment_homeEducation,textarea#musculoAssessment_evaluation,textarea#musculoAssessment_review,textarea#musculoAssessment_additionalNotes').each(function (){
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

function getdata_musculoAssessment(){
    var urlparam = {
        action: 'get_table_musculoAssessment',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_physio').val(),
        episno: $("#episno_physio").val()
    };
    
    $.post("./musculoAssessment/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formMusculoAssessment",data.musculoassessment);
            autoinsert_rowdata("#formMusculoAssessment",data.romaffectedside);
            autoinsert_rowdata("#formMusculoAssessment",data.romsoundside);
            autoinsert_rowdata("#formMusculoAssessment",data.musclepower);
            button_state_musculoAssessment('edit');
            $('#musculoAssessment_chart').attr('disabled',false);
        }else{
            button_state_musculoAssessment('add');
            $('#musculoAssessment_chart').attr('disabled',true);
        }
        
        // textarea_init_musculoAssessment();
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
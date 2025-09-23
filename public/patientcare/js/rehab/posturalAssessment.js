
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // textarea_init_posturalAssessment();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formPosturalAssessment');
    
    $("#new_posturalAssessment").click(function (){
        $('#cancel_posturalAssessment').data('oper','add');
        button_state_posturalAssessment('wait');
        enableForm('#formPosturalAssessment');
        rdonly('#formPosturalAssessment');
        emptyFormdata_div("#formPosturalAssessment",['#mrn_physio','#episno_physio']);
        document.getElementById("idno_posturalAssessment").value = "";
        // dialog_mrn_edit.on();
    });
    
    $("#edit_posturalAssessment").click(function (){
        button_state_posturalAssessment('wait');
        enableForm('#formPosturalAssessment');
        rdonly('#formPosturalAssessment');
        // dialog_mrn_edit.on();
    });
    
    $("#save_posturalAssessment").click(function (){
        if($('#formPosturalAssessment').isValid({requiredFields: ''}, conf, true)){
            saveForm_posturalAssessment(function (data){
                $("#cancel_posturalAssessment").data('oper','edit');
                $("#cancel_posturalAssessment").click();
                // emptyFormdata_div("#formPosturalAssessment",['#mrn_physio','#episno_physio']);
                disableForm('#formPosturalAssessment');
            });
        }else{
            enableForm('#formPosturalAssessment');
            rdonly('#formPosturalAssessment');
        }
    });
    
    $("#cancel_posturalAssessment").click(function (){
        // emptyFormdata_div("#formPosturalAssessment",['#mrn_physio','#episno_physio']);
        disableForm('#formPosturalAssessment');
        button_state_posturalAssessment($(this).data('oper'));
        $('#tbl_posturalAssessment_date').DataTable().ajax.reload();
        getdata_posturalAssessment();
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
    
    ////////////////////////////////////posturalAssessment starts////////////////////////////////////
    $('#tbl_posturalAssessment_date tbody').on('click', 'tr', function (){
        var data = tbl_posturalAssessment_date.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_posturalAssessment_date.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formPosturalAssessment",['#mrn_physio','#episno_physio']);
        $('#tbl_posturalAssessment_date tbody tr').removeClass('active');
        $(this).addClass('active');
        
        if(check_same_usr_edit(data)){
            button_state_posturalAssessment('edit');
        }else{
            button_state_posturalAssessment('add');
        }
        $('#posturalAssessment_chart').attr('disabled',false);
        
        // getdata_posturalAssessment();
        $("#idno_posturalAssessment").val(data.idno);
        
        var saveParam = {
            action: 'get_table_posturalAssessment',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./posturalAssessment/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data.posturalassessment)){
                autoinsert_rowdata("#formPosturalAssessment",data.posturalassessment);
                // button_state_posturalAssessment('edit');
            }else{
                // button_state_posturalAssessment('add');
            }
            
            // textarea_init_posturalAssessment();
        });
    });
    /////////////////////////////////////posturalAssessment ends/////////////////////////////////////
    
    ///////////////////////////////////////body diagram starts///////////////////////////////////////
    $('a.ui.card.bodydia_physio').click(function (){
        let mrn = $('#mrn_physio').val();
        let episno = $('#episno_physio').val();
        let entereddate = $('#posturalAssessment_entereddate').val();
        let type = $(this).data('type');
        let istablet = $(window).width() <= 1024;
        
        if(mrn.trim() == '' || type.trim() == ''){
            alert('Please choose Patient First');
        }else if($('#save_posturalAssessment').prop('disabled')){
            alert('Edit this patient first');
        }else if(entereddate == ''){
            alert('Please enter date first');
        }else{
            if(istablet){
                let filename = type+'_'+mrn+'_.pdf';
                let url = $('#urltodiagram').val() + filename;
                var win = window.open(url, '_blank');
            }else{
                var win = window.open('http://localhost:8443/foxitweb/public/pdf?mrn='+mrn+'&episno='+episno+'&entereddate='+entereddate+'&type='+type+'&from=rehab', '_blank');
            }
            
            if(win){
                win.focus();
            }else{
                alert('Please allow popups for this website');
            }
        }
    });
    ////////////////////////////////////////body diagram ends////////////////////////////////////////
    
    $("#posturalAssessment_chart").click(function (){
        window.open('./posturalAssessment/posturalassessment_chart?mrn='+$('#mrn_physio').val()+'&episno='+$("#episno_physio").val()+'&entereddate='+$("#posturalAssessment_entereddate").val()+'&type1=BF_PHYSIO'+'&type2=BB_PHYSIO', '_blank');
    });
    
});

////////////////////posturalAssessment starts////////////////////
var tbl_posturalAssessment_date = $('#tbl_posturalAssessment_date').DataTable({
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
/////////////////////posturalAssessment ends/////////////////////

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

button_state_posturalAssessment('empty');
function button_state_posturalAssessment(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_physio").removeAttr('data-toggle');
            $('#cancel_posturalAssessment').data('oper','add');
            $('#new_posturalAssessment,#save_posturalAssessment,#cancel_posturalAssessment,#edit_posturalAssessment,#posturalAssessment_chart').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_posturalAssessment').data('oper','add');
            $("#new_posturalAssessment").attr('disabled',false);
            $('#save_posturalAssessment,#cancel_posturalAssessment,#edit_posturalAssessment').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_posturalAssessment').data('oper','edit');
            $("#new_posturalAssessment,#edit_posturalAssessment").attr('disabled',false);
            $('#save_posturalAssessment,#cancel_posturalAssessment').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_physio").attr('data-toggle','collapse');
            $("#save_posturalAssessment,#cancel_posturalAssessment").attr('disabled',false);
            $('#edit_posturalAssessment,#new_posturalAssessment,#posturalAssessment_chart').attr('disabled',true);
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

function saveForm_posturalAssessment(callback){
    let oper = $("#cancel_posturalAssessment").data('oper');
    var saveParam = {
        action: 'save_table_posturalAssessment',
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
    
    values = $("#formPosturalAssessment").serializeArray();
    
    values = values.concat(
        $('#formPosturalAssessment input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formPosturalAssessment input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formPosturalAssessment input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formPosturalAssessment select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./posturalAssessment/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_posturalAssessment('edit');
    }).fail(function (data){
        if(data.responseText !== ''){
            // $('#p_error_intake').text(data.responseText);
            alert(data.responseText);
        }
        
        callback(data);
        button_state_posturalAssessment($(this).data('oper'));
    });
}

function textarea_init_posturalAssessment(){
    $('textarea#anteriorPosteriorRmk,textarea#lateralRmk').each(function (){
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

function getdata_posturalAssessment(){
    var urlparam = {
        action: 'get_table_posturalAssessment',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_physio').val(),
        episno: $("#episno_physio").val()
    };
    
    $.post("./posturalAssessment/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formPosturalAssessment",data.posturalassessment);
            button_state_posturalAssessment('edit');
            $('#posturalAssessment_chart').attr('disabled',false);
        }else{
            button_state_posturalAssessment('add');
            $('#posturalAssessment_chart').attr('disabled',true);
        }
        
        // textarea_init_posturalAssessment();
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
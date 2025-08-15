
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // textarea_init_neuroAssessment();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formNeuroAssessment');
    
    $("#new_neuroAssessment").click(function (){
        $('#cancel_neuroAssessment').data('oper','add');
        button_state_neuroAssessment('wait');
        enableForm('#formNeuroAssessment');
        rdonly('#formNeuroAssessment');
        emptyFormdata_div("#formNeuroAssessment",['#mrn_physio','#episno_physio']);
        document.getElementById("idno_neuroAssessment").value = "";
        document.getElementById("idno_romaffectedside").value = "";
        document.getElementById("idno_romsoundside").value = "";
        document.getElementById("idno_musclepower").value = "";
        // dialog_mrn_edit.on();
    });
    
    $("#edit_neuroAssessment").click(function (){
        button_state_neuroAssessment('wait');
        enableForm('#formNeuroAssessment');
        rdonly('#formNeuroAssessment');
        // dialog_mrn_edit.on();
    });
    
    $("#save_neuroAssessment").click(function (){
        if($('#formNeuroAssessment').isValid({requiredFields: ''}, conf, true)){
            saveForm_neuroAssessment(function (data){
                $("#cancel_neuroAssessment").data('oper','edit');
                $("#cancel_neuroAssessment").click();
                // emptyFormdata_div("#formNeuroAssessment",['#mrn_physio','#episno_physio']);
                disableForm('#formNeuroAssessment');
            });
        }else{
            enableForm('#formNeuroAssessment');
            rdonly('#formNeuroAssessment');
        }
    });
    
    $("#cancel_neuroAssessment").click(function (){
        // emptyFormdata_div("#formNeuroAssessment",['#mrn_physio','#episno_physio']);
        disableForm('#formNeuroAssessment');
        button_state_neuroAssessment($(this).data('oper'));
        $('#tbl_neuroAssessment_date').DataTable().ajax.reload();
        getdata_neuroAssessment();
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
    
    /////////////////////////////////////neuroAssessment starts/////////////////////////////////////
    $('#tbl_neuroAssessment_date tbody').on('click', 'tr', function (){
        var data = tbl_neuroAssessment_date.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_neuroAssessment_date.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formNeuroAssessment",['#mrn_physio','#episno_physio']);
        $('#tbl_neuroAssessment_date tbody tr').removeClass('active');
        $(this).addClass('active');
        
        if(check_same_usr_edit(data)){
            button_state_neuroAssessment('edit');
        }else{
            button_state_neuroAssessment('add');
        }
        $('#neuroAssessment_chart').attr('disabled',false);
        
        // getdata_neuroAssessment();
        $("#idno_neuroAssessment").val(data.n_idno);
        $("#idno_romaffectedside").val(data.a_idno);
        $("#idno_romsoundside").val(data.s_idno);
        $("#idno_musclepower").val(data.m_idno);
        
        var saveParam = {
            action: 'get_table_neuroAssessment',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            n_idno: data.n_idno,
            mrn: data.mrn,
            episno: data.episno,
            a_idno: data.a_idno,
            s_idno: data.s_idno,
            m_idno: data.m_idno,
        };
        
        $.post("./neuroAssessment/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data.neuroassessment)){
                autoinsert_rowdata("#formNeuroAssessment",data.neuroassessment);
                autoinsert_rowdata("#formNeuroAssessment",data.romaffectedside);
                autoinsert_rowdata("#formNeuroAssessment",data.romsoundside);
                autoinsert_rowdata("#formNeuroAssessment",data.musclepower);
                // button_state_neuroAssessment('edit');
            }else{
                // button_state_neuroAssessment('add');
            }
            
            // textarea_init_neuroAssessment();
        });
    });
    //////////////////////////////////////neuroAssessment ends//////////////////////////////////////
    
    ///////////////////////////////////////body diagram starts///////////////////////////////////////
    $('a.ui.card.bodydia_neuro').click(function (){
        let mrn = $('#mrn_physio').val();
        let episno = $('#episno_physio').val();
        let entereddate = $('#neuroAssessment_entereddate').val();
        let type = $(this).data('type');
        let istablet = $(window).width() <= 1024;
        
        if(mrn.trim() == '' || type.trim() == ''){
            alert('Please choose Patient First');
        }else if($('#save_neuroAssessment').prop('disabled')){
            alert('Edit this patient first');
        }else{
            if(istablet){
                let filename = type+'_'+mrn+'_.pdf';
                let url = $('#urltodiagram').val() + filename;
                var win = window.open(url, '_blank');
            }else{
                var win = window.open('http://localhost:8443/foxitweb/public/pdf?mrn='+mrn+'&episno='+episno+'&entereddate='+entereddate+'&type='+type+'&from=neuroAssessment', '_blank');
            }
            
            if(win){
                win.focus();
            }else{
                alert('Please allow popups for this website');
            }
        }
    });
    ////////////////////////////////////////body diagram ends////////////////////////////////////////
    
    $("#neuroAssessment_chart").click(function (){
        window.open('./neuroAssessment/neuroassessment_chart?mrn='+$('#mrn_physio').val()+'&episno='+$("#episno_physio").val()+'&entereddate='+$("#neuroAssessment_entereddate").val()+'&type1=BB_NEURO'+'&type2=BF_NEURO', '_blank');
    });
    
});

/////////////////////neuroAssessment starts/////////////////////
var tbl_neuroAssessment_date = $('#tbl_neuroAssessment_date').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'n_idno' },
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
//////////////////////neuroAssessment ends//////////////////////

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

button_state_neuroAssessment('empty');
function button_state_neuroAssessment(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_physio").removeAttr('data-toggle');
            $('#cancel_neuroAssessment').data('oper','add');
            $('#new_neuroAssessment,#save_neuroAssessment,#cancel_neuroAssessment,#edit_neuroAssessment,#neuroAssessment_chart').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_neuroAssessment').data('oper','add');
            $("#new_neuroAssessment").attr('disabled',false);
            $('#save_neuroAssessment,#cancel_neuroAssessment,#edit_neuroAssessment').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_neuroAssessment').data('oper','edit');
            $("#new_neuroAssessment,#edit_neuroAssessment").attr('disabled',false);
            $('#save_neuroAssessment,#cancel_neuroAssessment').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_physio").attr('data-toggle','collapse');
            $("#save_neuroAssessment,#cancel_neuroAssessment").attr('disabled',false);
            $('#edit_neuroAssessment,#new_neuroAssessment,#neuroAssessment_chart').attr('disabled',true);
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

function saveForm_neuroAssessment(callback){
    let oper = $("#cancel_neuroAssessment").data('oper');
    var saveParam = {
        action: 'save_table_neuroAssessment',
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
    
    values = $("#formNeuroAssessment").serializeArray();
    
    values = values.concat(
        $('#formNeuroAssessment input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formNeuroAssessment input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formNeuroAssessment input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formNeuroAssessment select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./neuroAssessment/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_neuroAssessment('edit');
    }).fail(function (data){
        if(data.responseText !== ''){
            // $('#p_error_intake').text(data.responseText);
            alert(data.responseText);
        }
        
        callback(data);
        button_state_neuroAssessment($(this).data('oper'));
    });
}

function textarea_init_neuroAssessment(){
    $('textarea#neuroAssessment_objective,textarea#neuroAssessment_impressionBC,textarea#neuroAssessment_impressionSens,textarea#neuroAssessment_impressionROM,textarea#neuroAssessment_impressionMAS,textarea#neuroAssessment_impressionDTR,textarea#neuroAssessment_impressionSMP,textarea#neuroAssessment_impressionCoord,textarea#neuroAssessment_impressionFA,textarea#neuroAssessment_summary').each(function (){
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

function getdata_neuroAssessment(){
    var urlparam = {
        action: 'get_table_neuroAssessment',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_physio').val(),
        episno: $("#episno_physio").val()
    };
    
    $.post("./neuroAssessment/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formNeuroAssessment",data.neuroassessment);
            autoinsert_rowdata("#formNeuroAssessment",data.romaffectedside);
            autoinsert_rowdata("#formNeuroAssessment",data.romsoundside);
            autoinsert_rowdata("#formNeuroAssessment",data.musclepower);
            button_state_neuroAssessment('edit');
            $('#neuroAssessment_chart').attr('disabled',false);
        }else{
            button_state_neuroAssessment('add');
            $('#neuroAssessment_chart').attr('disabled',true);
        }
        
        // textarea_init_neuroAssessment();
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
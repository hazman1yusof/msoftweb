
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // textarea_init_motorScale();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formMotorScale');
    
    $("#new_motorScale").click(function (){
        $('#cancel_motorScale').data('oper','add');
        button_state_motorScale('wait');
        enableForm('#formMotorScale');
        rdonly('#formMotorScale');
        emptyFormdata_div("#formMotorScale",['#mrn_physio','#episno_physio']);
        document.getElementById("idno_motorScale").value = "";
        // dialog_mrn_edit.on();
        $('#movementScore').prop('disabled',true);
    });
    
    $("#edit_motorScale").click(function (){
        button_state_motorScale('wait');
        enableForm('#formMotorScale');
        rdonly('#formMotorScale');
        // dialog_mrn_edit.on();
        $('#movementScore').prop('disabled',true);
    });
    
    $("#save_motorScale").click(function (){
        if($('#formMotorScale').isValid({requiredFields: ''}, conf, true)){
            saveForm_motorScale(function (data){
                $("#cancel_motorScale").data('oper','edit');
                $("#cancel_motorScale").click();
                // emptyFormdata_div("#formMotorScale",['#mrn_physio','#episno_physio']);
                disableForm('#formMotorScale');
            });
        }else{
            enableForm('#formMotorScale');
            rdonly('#formMotorScale');
        }
    });
    
    $("#cancel_motorScale").click(function (){
        // emptyFormdata_div("#formMotorScale",['#mrn_physio','#episno_physio']);
        disableForm('#formMotorScale');
        button_state_motorScale($(this).data('oper'));
        $('#tbl_motorScale_date').DataTable().ajax.reload();
        getdata_motorScale();
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
    
    ///////////////////////////////////////motorScale starts///////////////////////////////////////
    $('#tbl_motorScale_date tbody').on('click', 'tr', function (){
        var data = tbl_motorScale_date.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_motorScale_date.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formMotorScale",['#mrn_physio','#episno_physio']);
        $('#tbl_motorScale_date tbody tr').removeClass('active');
        $(this).addClass('active');
        
        if(check_same_usr_edit(data)){
            button_state_motorScale('edit');
        }else{
            button_state_motorScale('add');
        }
        $('#motorScale_chart').attr('disabled',false);
        
        // getdata_motorScale();
        $("#idno_motorScale").val(data.idno);
        
        var saveParam = {
            action: 'get_table_motorScale',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./motorScale/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data.motorscale)){
                autoinsert_rowdata("#formMotorScale",data.motorscale);
                // button_state_motorScale('edit');
            }else{
                // button_state_motorScale('add');
            }
            
            // textarea_init_motorScale();
        });
    });
    ////////////////////////////////////////motorScale ends////////////////////////////////////////
    
    ////////////////////to calculate the total of movement scoring sheet starts////////////////////
    function calculate_movementScore(){
        var score = 0;
        $(".calc_movementScore:checked").each(function (){
            score+=parseInt($(this).val());
        });
        $("#formMotorScale input[name=movementScore]").val(score);
    }
    
    $(".calc_movementScore").change(function (){
        calculate_movementScore();
    });
    /////////////////////to calculate the total of movement scoring sheet ends/////////////////////
    
    $("#motorScale_chart").click(function (){
        window.open('./motorScale/motorscale_chart?mrn='+$('#mrn_physio').val()+'&episno='+$("#episno_physio").val()+'&entereddate='+$("#motorScale_entereddate").val(), '_blank');
    });
    
});

///////////////////////motorScale starts///////////////////////
var tbl_motorScale_date = $('#tbl_motorScale_date').DataTable({
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
////////////////////////motorScale ends////////////////////////

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

button_state_motorScale('empty');
function button_state_motorScale(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_physio").removeAttr('data-toggle');
            $('#cancel_motorScale').data('oper','add');
            $('#new_motorScale,#save_motorScale,#cancel_motorScale,#edit_motorScale,#motorScale_chart').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_motorScale').data('oper','add');
            $("#new_motorScale").attr('disabled',false);
            $('#save_motorScale,#cancel_motorScale,#edit_motorScale').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_motorScale').data('oper','edit');
            $("#new_motorScale,#edit_motorScale").attr('disabled',false);
            $('#save_motorScale,#cancel_motorScale').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_physio").attr('data-toggle','collapse');
            $("#save_motorScale,#cancel_motorScale").attr('disabled',false);
            $('#edit_motorScale,#new_motorScale,#motorScale_chart').attr('disabled',true);
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

function saveForm_motorScale(callback){
    let oper = $("#cancel_motorScale").data('oper');
    var saveParam = {
        action: 'save_table_motorScale',
        oper: oper,
        mrn: $('#mrn_physio').val(),
        episno: $("#episno_physio").val(),
        movementScore: $("#movementScore").val(),
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
    
    values = $("#formMotorScale").serializeArray();
    
    values = values.concat(
        $('#formMotorScale input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formMotorScale input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formMotorScale input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formMotorScale select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./motorScale/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_motorScale('edit');
    }).fail(function (data){
        if(data.responseText !== ''){
            // $('#p_error_intake').text(data.responseText);
            alert(data.responseText);
        }
        
        callback(data);
        button_state_motorScale($(this).data('oper'));
    });
}

function textarea_init_motorScale(){
    $('textarea#motorScale_comments').each(function (){
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

function getdata_motorScale(){
    var urlparam = {
        action: 'get_table_motorScale',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_physio').val(),
        episno: $("#episno_physio").val()
    };
    
    $.post("./motorScale/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formMotorScale",data.motorscale);
            button_state_motorScale('edit');
            $('#motorScale_chart').attr('disabled',false);
        }else{
            button_state_motorScale('add');
            $('#motorScale_chart').attr('disabled',true);
        }
        
        // textarea_init_motorScale();
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
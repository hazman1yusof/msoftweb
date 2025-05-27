
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // textarea_init_bergBalanceTest();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formBergBalanceTest');
    
    $("#new_bergBalanceTest").click(function (){
        $('#cancel_bergBalanceTest').data('oper','add');
        button_state_bergBalanceTest('wait');
        enableForm('#formBergBalanceTest');
        rdonly('#formBergBalanceTest');
        emptyFormdata_div("#formBergBalanceTest",['#mrn_physio','#episno_physio']);
        document.getElementById("idno_bergBalanceTest").value = "";
        // dialog_mrn_edit.on();
        $('#bergBalanceTest_totalScore').prop('disabled',true);
    });
    
    $("#edit_bergBalanceTest").click(function (){
        button_state_bergBalanceTest('wait');
        enableForm('#formBergBalanceTest');
        rdonly('#formBergBalanceTest');
        // dialog_mrn_edit.on();
        $('#bergBalanceTest_totalScore').prop('disabled',true);
    });
    
    $("#save_bergBalanceTest").click(function (){
        if($('#formBergBalanceTest').isValid({requiredFields: ''}, conf, true)){
            saveForm_bergBalanceTest(function (data){
                $("#cancel_bergBalanceTest").data('oper','edit');
                $("#cancel_bergBalanceTest").click();
                // emptyFormdata_div("#formBergBalanceTest",['#mrn_physio','#episno_physio']);
                disableForm('#formBergBalanceTest');
            });
        }else{
            enableForm('#formBergBalanceTest');
            rdonly('#formBergBalanceTest');
        }
    });
    
    $("#cancel_bergBalanceTest").click(function (){
        // emptyFormdata_div("#formBergBalanceTest",['#mrn_physio','#episno_physio']);
        disableForm('#formBergBalanceTest');
        button_state_bergBalanceTest($(this).data('oper'));
        $('#tbl_bergBalanceTest_date').DataTable().ajax.reload();
        getdata_bergBalanceTest();
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
    
    /////////////////////////////////////bergBalanceTest starts/////////////////////////////////////
    $('#tbl_bergBalanceTest_date tbody').on('click', 'tr', function (){
        var data = tbl_bergBalanceTest_date.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_bergBalanceTest_date.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formBergBalanceTest",['#mrn_physio','#episno_physio']);
        $('#tbl_bergBalanceTest_date tbody tr').removeClass('active');
        $(this).addClass('active');
        
        // getdata_bergBalanceTest();
        $("#idno_bergBalanceTest").val(data.idno);
        
        var saveParam = {
            action: 'get_table_bergBalanceTest',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./bergBalanceTest/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data.bergtest)){
                autoinsert_rowdata("#formBergBalanceTest",data.bergtest);
                button_state_bergBalanceTest('edit');
            }else{
                button_state_bergBalanceTest('add');
            }
            
            // textarea_init_bergBalanceTest();
        });
    });
    //////////////////////////////////////bergBalanceTest ends//////////////////////////////////////
    
    //////////////////////////////to calculate the total score starts//////////////////////////////
    function calculate_bergBalanceTest(){
        var score = 0;
        $(".calc_bergBalanceTest:checked").each(function (){
            score+=parseInt($(this).val());
        });
        $("#formBergBalanceTest input[name=totalScore]").val(score);
    }
    
    $(".calc_bergBalanceTest").change(function (){
        calculate_bergBalanceTest();
    });
    ///////////////////////////////to calculate the total score ends///////////////////////////////
    
});

/////////////////////bergBalanceTest starts/////////////////////
var tbl_bergBalanceTest_date = $('#tbl_bergBalanceTest_date').DataTable({
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
//////////////////////bergBalanceTest ends//////////////////////

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

button_state_bergBalanceTest('empty');
function button_state_bergBalanceTest(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_physio").removeAttr('data-toggle');
            $('#cancel_bergBalanceTest').data('oper','add');
            $('#new_bergBalanceTest,#save_bergBalanceTest,#cancel_bergBalanceTest,#edit_bergBalanceTest').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_bergBalanceTest').data('oper','add');
            $("#new_bergBalanceTest").attr('disabled',false);
            $('#save_bergBalanceTest,#cancel_bergBalanceTest,#edit_bergBalanceTest').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_bergBalanceTest').data('oper','edit');
            $("#new_bergBalanceTest,#edit_bergBalanceTest").attr('disabled',false);
            $('#save_bergBalanceTest,#cancel_bergBalanceTest').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_physio").attr('data-toggle','collapse');
            $("#save_bergBalanceTest,#cancel_bergBalanceTest").attr('disabled',false);
            $('#edit_bergBalanceTest,#new_bergBalanceTest').attr('disabled',true);
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

function saveForm_bergBalanceTest(callback){
    let oper = $("#cancel_bergBalanceTest").data('oper');
    var saveParam = {
        action: 'save_table_bergBalanceTest',
        oper: oper,
        mrn: $('#mrn_physio').val(),
        episno: $("#episno_physio").val(),
        totalScore: $("#bergBalanceTest_totalScore").val(),
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
    
    values = $("#formBergBalanceTest").serializeArray();
    
    values = values.concat(
        $('#formBergBalanceTest input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formBergBalanceTest input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formBergBalanceTest input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formBergBalanceTest select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./bergBalanceTest/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_bergBalanceTest('edit');
    }).fail(function (data){
        if(data.responseText !== ''){
            // $('#p_error_intake').text(data.responseText);
            alert(data.responseText);
        }
        
        callback(data);
        button_state_bergBalanceTest($(this).data('oper'));
    });
}

function textarea_init_bergBalanceTest(){
    $('textarea#bergBalanceTest_totalScore').each(function (){
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

function getdata_bergBalanceTest(){
    var urlparam = {
        action: 'get_table_bergBalanceTest',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_physio').val(),
        episno: $("#episno_physio").val()
    };
    
    $.post("./bergBalanceTest/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formBergBalanceTest",data.bergtest);
            button_state_bergBalanceTest('edit');
        }else{
            button_state_bergBalanceTest('add');
        }
        
        // textarea_init_bergBalanceTest();
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
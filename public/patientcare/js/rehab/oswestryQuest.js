
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // textarea_init_oswestryQuest();
    
    var fdl = new faster_detail_load();
    
    disableForm('#formOswestryQuest');
    
    $("#new_oswestryQuest").click(function (){
        $('#cancel_oswestryQuest').data('oper','add');
        button_state_oswestryQuest('wait');
        enableForm('#formOswestryQuest');
        rdonly('#formOswestryQuest');
        emptyFormdata_div("#formOswestryQuest",['#mrn_physio','#episno_physio']);
        document.getElementById("idno_oswestryQuest").value = "";
        // dialog_mrn_edit.on();
        $('#oswestryQuest_totalScore').prop('disabled',true);
        $('#formOswestryQuest span#oswestryQuest_disabilityLevel').text('');
    });
    
    $("#edit_oswestryQuest").click(function (){
        button_state_oswestryQuest('wait');
        enableForm('#formOswestryQuest');
        rdonly('#formOswestryQuest');
        // dialog_mrn_edit.on();
        $('#oswestryQuest_totalScore').prop('disabled',true);
    });
    
    $("#save_oswestryQuest").click(function (){
        if($('#formOswestryQuest').isValid({requiredFields: ''}, conf, true)){
            saveForm_oswestryQuest(function (data){
                $("#cancel_oswestryQuest").data('oper','edit');
                $("#cancel_oswestryQuest").click();
                // emptyFormdata_div("#formOswestryQuest",['#mrn_physio','#episno_physio']);
                disableForm('#formOswestryQuest');
            });
        }else{
            enableForm('#formOswestryQuest');
            rdonly('#formOswestryQuest');
        }
    });
    
    $("#cancel_oswestryQuest").click(function (){
        // emptyFormdata_div("#formOswestryQuest",['#mrn_physio','#episno_physio']);
        disableForm('#formOswestryQuest');
        button_state_oswestryQuest($(this).data('oper'));
        $('#tbl_oswestryQuest_date').DataTable().ajax.reload();
        getdata_oswestryQuest();
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
    
    //////////////////////////////////////oswestryQuest starts//////////////////////////////////////
    $('#tbl_oswestryQuest_date tbody').on('click', 'tr', function (){
        var data = tbl_oswestryQuest_date.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_oswestryQuest_date.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formOswestryQuest",['#mrn_physio','#episno_physio']);
        $('#tbl_oswestryQuest_date tbody tr').removeClass('active');
        $(this).addClass('active');
        
        if(check_same_usr_edit(data)){
            button_state_oswestryQuest('edit');
        }else{
            button_state_oswestryQuest('add');
        }
        
        $('#formOswestryQuest span#oswestryQuest_disabilityLevel').text('');
        
        // getdata_oswestryQuest();
        $("#idno_oswestryQuest").val(data.idno);
        
        var saveParam = {
            action: 'get_table_oswestryQuest',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./oswestryQuest/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data.oswestryquest)){
                autoinsert_rowdata("#formOswestryQuest",data.oswestryquest);
                $('#formOswestryQuest span#oswestryQuest_disabilityLevel').text(data.oswestryquest.disabilityLevel);
                // button_state_oswestryQuest('edit');
            }else{
                // button_state_oswestryQuest('add');
            }
            
            // textarea_init_oswestryQuest();
        });
    });
    ///////////////////////////////////////oswestryQuest ends///////////////////////////////////////
    
    //////////////////////////////to calculate the total score starts//////////////////////////////
    function calculate_oswestryQuest(){
        var score = 0;
        $(".calc_oswestryQuest:checked").each(function (){
            score+=parseInt($(this).val());
        });
        
        var level = '';
        if(score <= 4){
            level = 'No disability.';
        }else if(score >= 5 && score <= 14){
            level = 'Mild disability.';
        }else if(score >= 15 && score <= 24){
            level = 'Moderate disability.';
        }else if(score >= 25 && score <= 34){
            level = 'Severe disability.';
        }else if(score >= 35){
            level = 'Severe disability.';
        }
        
        $("#formOswestryQuest input[name=totalScore]").val(score);
        $('#formOswestryQuest span#oswestryQuest_disabilityLevel').text(level);
    }
    
    $(".calc_oswestryQuest").change(function (){
        calculate_oswestryQuest();
    });
    ///////////////////////////////to calculate the total score ends///////////////////////////////
    
});

//////////////////////oswestryQuest starts//////////////////////
var tbl_oswestryQuest_date = $('#tbl_oswestryQuest_date').DataTable({
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
///////////////////////oswestryQuest ends///////////////////////

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

button_state_oswestryQuest('empty');
function button_state_oswestryQuest(state){
    // empty_transaction('add');
    switch(state){
        case 'empty':
            $("#toggle_physio").removeAttr('data-toggle');
            $('#cancel_oswestryQuest').data('oper','add');
            $('#new_oswestryQuest,#save_oswestryQuest,#cancel_oswestryQuest,#edit_oswestryQuest').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_oswestryQuest').data('oper','add');
            $("#new_oswestryQuest").attr('disabled',false);
            $('#save_oswestryQuest,#cancel_oswestryQuest,#edit_oswestryQuest').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_physio").attr('data-toggle','collapse');
            $('#cancel_oswestryQuest').data('oper','edit');
            $("#new_oswestryQuest,#edit_oswestryQuest").attr('disabled',false);
            $('#save_oswestryQuest,#cancel_oswestryQuest').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_physio").attr('data-toggle','collapse');
            $("#save_oswestryQuest,#cancel_oswestryQuest").attr('disabled',false);
            $('#edit_oswestryQuest,#new_oswestryQuest').attr('disabled',true);
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

function saveForm_oswestryQuest(callback){
    let oper = $("#cancel_oswestryQuest").data('oper');
    var saveParam = {
        action: 'save_table_oswestryQuest',
        oper: oper,
        mrn: $('#mrn_physio').val(),
        episno: $("#episno_physio").val(),
        totalScore: $("#oswestryQuest_totalScore").val(),
        disabilityLevel: $("#oswestryQuest_disabilityLevel").text(),
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
    
    values = $("#formOswestryQuest").serializeArray();
    
    values = values.concat(
        $('#formOswestryQuest input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formOswestryQuest input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formOswestryQuest input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formOswestryQuest select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./oswestryQuest/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
        button_state_oswestryQuest('edit');
    }).fail(function (data){
        if(data.responseText !== ''){
            // $('#p_error_intake').text(data.responseText);
            alert(data.responseText);
        }
        
        callback(data);
        button_state_oswestryQuest($(this).data('oper'));
    });
}

function textarea_init_oswestryQuest(){
    $('textarea#oswestryQuest_totalScore').each(function (){
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

function getdata_oswestryQuest(){
    var urlparam = {
        action: 'get_table_oswestryQuest',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_physio').val(),
        episno: $("#episno_physio").val()
    };
    
    $.post("./oswestryQuest/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formOswestryQuest",data.oswestryquest);
            $('#formOswestryQuest span#oswestryQuest_disabilityLevel').text(data.oswestryquest.disabilityLevel);
            button_state_oswestryQuest('edit');
        }else{
            $('#formOswestryQuest span#oswestryQuest_disabilityLevel').text('');
            button_state_oswestryQuest('add');
        }
        
        // textarea_init_oswestryQuest();
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
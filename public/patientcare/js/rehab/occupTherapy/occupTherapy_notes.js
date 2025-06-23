$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    //////////////////////////////////////notes starts//////////////////////////////////////

    disableForm('#formOccupTherapyNotes');
    
    $("#new_notes").click(function (){
        button_state_notes('wait');
        enableForm('#formOccupTherapyNotes');
        rdonly('#formOccupTherapyNotes');
        emptyFormdata_div("#formOccupTherapyNotes",['#mrn_occupTherapy','#episno_occupTherapy']);

        document.getElementById("idno_notes").value = "";
    });
    
    $("#edit_notes").click(function (){
        button_state_notes('wait');
        enableForm('#formOccupTherapyNotes');
        rdonly('#formOccupTherapyNotes');
        $("#dateNotes").attr("readonly", true);

    });
    
    $("#save_notes").click(function (){
        disableForm('#formOccupTherapyNotes');
        if($('#formOccupTherapyNotes').isValid({requiredFields: ''}, conf, true)){
            saveForm_notes(function (data){
                $("#cancel_notes").data('oper','edit');
                $("#cancel_notes").click();
            });
        }else{
            enableForm('#formOccupTherapyNotes');
            rdonly('#formOccupTherapyNotes');
        }
    });
    
    $("#cancel_notes").click(function (){
        disableForm('#formOccupTherapyNotes');
        button_state_notes($(this).data('oper'));
        $('#datetimeNotes_tbl').DataTable().ajax.reload();            
    });

    //////////////////////////////////////notes ends//////////////////////////////////////
    
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

    ////////////////////////////////////////notes starts////////////////////////////////////////
    $('#datetimeNotes_tbl tbody').on('click', 'tr', function (){
        var data = datetimeNotes_tbl.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            datetimeNotes_tbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formOccupTherapyNotes",['#mrn_occupTherapy','#episno_occupTherapy']);
        $('#datetimeNotes_tbl tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#idno_notes").val(data.idno);
        
        var saveParam={
            action: 'get_table_notes',
        }
        
        var postobj={
            _token: $('#_token').val(),
            idno: data.idno,
            // mrn: data.mrn,
            // episno: data.episno,
            // date:data.date

        };
        
        $.post("./occupTherapy_notes/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formOccupTherapyNotes",data.notes);

                button_state_notes('edit');
            }else{
                button_state_notes('add');
            }
        });
    });
	
});

/////////////////////notes starts/////////////////////
var datetimeNotes_tbl = $('#datetimeNotes_tbl').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno', 'width': '5%' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'dateNotes', 'width': '10%' },

    ],
    columnDefs: [
        { targets: [0, 1, 2], visible: false },
    ],
    order: [[0, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
//////////////////////notes ends//////////////////////

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

button_state_notes('empty');
function button_state_notes(state){
    switch(state){
        case 'empty':
            $("#toggle_occupTherapy").removeAttr('data-toggle');
            $('#cancel_notes').data('oper','add');
            $('#new_notes,#save_notes,#cancel_notes,#edit_notes').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_notes').data('oper','add');
            $("#new_notes").attr('disabled',false);
            $('#save_notes,#cancel_notes,#edit_notes').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $('#cancel_notes').data('oper','edit');
            $("#edit_notes,#new_notes").attr('disabled',false);
            $('#save_notes,#cancel_notes').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_occupTherapy").attr('data-toggle','collapse');
            $("#save_notes,#cancel_notes").attr('disabled',false);
            $('#edit_notes,#new_notes').attr('disabled',true);
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

function saveForm_notes(callback){
    let oper = $("#cancel_notes").data('oper');
    var saveParam = {
        action: 'save_table_notes',
        oper: oper,
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
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val(),
    };
    
    values = $("#formOccupTherapyNotes").serializeArray();
    
    values = values.concat(
        $('#formOccupTherapyNotes input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formOccupTherapyNotes input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formOccupTherapyNotes input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formOccupTherapyNotes select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./occupTherapy_notes/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
    }).fail(function (data){
        alert(data.responseText);
        callback(data);
    });
}

function populate_notes_getdata(){
    // console.log('populate');
    disableForm('#formOccupTherapyNotes');
    emptyFormdata(errorField,"#formOccupTherapyNotes",["#mrn_occupTherapy","#episno_occupTherapy"]);

    var saveParam = {
        action: 'get_table_notes',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val()
    };
    
    $.post("./occupTherapy_notes/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formOccupTherapyNotes",data.notes);
            button_state_notes('edit');
        }else{
            button_state_notes('add');
        }
    });
}

function getdata_notes(){
    // console.log('populate');
    emptyFormdata(errorField,"#formOccupTherapyNotes",["#mrn_occupTherapy","#episno_occupTherapy"]);

    var urlparam = {
        action: 'get_table_notes',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_occupTherapy').val(),
        episno: $("#episno_occupTherapy").val()
    };
    
    $.post("./occupTherapy_notes/form?"+$.param(urlparam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            button_state_notes('edit');
            autoinsert_rowdata("#formOccupTherapyNotes",data.notes);
        }else{
            button_state_notes('add');
        }
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
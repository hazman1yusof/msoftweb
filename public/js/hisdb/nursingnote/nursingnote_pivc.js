
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function (){
    
    var fdl = new faster_detail_load();
        
    /////////////////////////////////////pivc starts/////////////////////////////////////
    disableForm('#formPivc');
    
    $("#new_pivc").click(function (){
        button_state_pivc('wait');
        enableForm('#formPivc');
        rdonly('#formPivc');
        emptyFormdata_div("#formPivc",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        document.getElementById("idno_pivc").value = "";
    });
    
    $("#edit_pivc").click(function (){
        button_state_pivc('wait');
        enableForm('#formPivc');
        rdonly('#formPivc');
        $("#practiceDate").attr("readonly", true);
    });
    
    $("#save_pivc").click(function (){
        disableForm('#formPivc');
        if($('#formPivc').isValid({requiredFields: ''}, conf, true)){
            saveForm_pivc(function (){
                $("#cancel_pivc").data('oper','edit');
                $("#cancel_pivc").click();
                // $('#datetimepivc_tbl').DataTable().ajax.reload();
            });
        }else{
            enableForm('#formPivc');
            rdonly('#formPivc');
        }
    });
    
    $("#cancel_pivc").click(function (){
        disableForm('#formPivc');
        button_state_pivc($(this).data('oper'));
        $('#datetimepivc_tbl').DataTable().ajax.reload();
    });
    //////////////////////////////////////pivc ends//////////////////////////////////////
    
    // to format number input to two decimal places (0.00)
    $(".floatNumberField").change(function (){
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });
    
    ////////////////////////////////////////pivc starts////////////////////////////////////////
    $('#datetimepivc_tbl tbody').on('click', 'tr', function (){
        var data = datetimepivc_tbl.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            datetimepivc_tbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formPivc",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        $('#datetimepivc_tbl tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#idno_pivc").val(data.idno);
        
        var saveParam = {
            action: 'get_table_pivc',
        }
        
        var postobj = {
            _token: $('#csrf_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).success(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formPivc",data.pivc);
                
                button_state_pivc('edit');
            }else{
                button_state_pivc('add');
            }
        });
    });
    /////////////////////////////////////////pivc ends/////////////////////////////////////////
    
});

/////////////////////pivc starts/////////////////////
var datetimepivc_tbl = $('#datetimepivc_tbl').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'practiceDate', 'width': '25%' },
    ],
    columnDefs: [
        { targets: [0, 1, 2], visible: false },
    ],
    order: [[0, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
//////////////////////pivc ends//////////////////////

var errorField = [];
conf = {
    modules : 'logic',
    language: {
        requiredFields: 'You have not answered all required fields'
    },
    onValidate: function ($form){
        if (errorField.length > 0) {
            return {
                element: $(errorField[0]),
                message: ''
            }
        }
    },
};

button_state_pivc('empty');
function button_state_pivc(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_pivc').data('oper','add');
            $('#new_pivc,#save_pivc,#cancel_pivc,#edit_pivc').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_pivc').data('oper','add');
            $("#new_pivc").attr('disabled',false);
            $('#save_pivc,#cancel_pivc,#edit_pivc').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_pivc').data('oper','edit');
            $("#new_pivc,#edit_pivc").attr('disabled',false);
            $('#save_pivc,#cancel_pivc').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_pivc,#cancel_pivc").attr('disabled',false);
            $('#edit_pivc,#new_pivc').attr('disabled',true);
            break;
    }
}

function populate_pivc_getdata(){
    disableForm('#formPivc');
    emptyFormdata(errorField,"#formPivc",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
    
    var saveParam = {
        action: 'get_table_pivc',
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val()
    };
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).success(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formPivc",data.pivc);
           
            button_state_pivc('edit');
        }else{
            button_state_pivc('add');
        }
    });
}

function autoinsert_rowdata(form,rowData){
    $.each(rowData, function (index, value){
        var input=$(form+" [name='"+index+"']");
        if(input.is("[type=radio]")){
            $(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
        }else if(input.is("[type=checkbox]")){
            if(value==1){
                $(form+" [name='"+index+"']").prop('checked', true);
            }
        }else{
            input.val(value);
        }
    });
}

/////////////////////////////////////////////////////pivc starts/////////////////////////////////////////////////////

function saveForm_pivc(callback){
    var saveParam = {
        action: 'save_table_pivc',
        oper: $("#cancel_pivc").data('oper')
    }
    
    var postobj = {
        _token: $('#csrf_token').val(),
        mrn_nursNote: $('#mrn_nursNote').val(),
        episno_nursNote: $('#episno_nursNote').val(),
    };
    
    values = $("#formPivc").serializeArray();
    
    values = values.concat(
        $('#formPivc input[type=checkbox]:not(:checked)').map(
            function (){
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formPivc input[type=checkbox]:checked').map(
            function (){
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formPivc input[type=radio]:checked').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formPivc select').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formPivc input[type=radio]:checked').map(
    //         function (){
    //             return {"name": this.name, "value": this.value}
    //         }).get()
    // );
    
    $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').fail(function (data){
        alert(data.responseText);

        callback();
    }).success(function (data){
        callback();
    });
}
/////////////////////////////////////////////////////pivc ends/////////////////////////////////////////////////////

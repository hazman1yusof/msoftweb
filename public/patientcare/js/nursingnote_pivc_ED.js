
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function (){
    
    var fdl = new faster_detail_load();
        
    /////////////////////////////////////pivc starts/////////////////////////////////////
    disableForm('#formPivc_ED');
    
    $("#new_pivc_ED").click(function (){
        button_state_pivc_ED('wait');
        enableForm('#formPivc_ED');
        rdonly('#formPivc_ED');
        emptyFormdata_div("#formPivc_ED",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        document.getElementById("idno_pivc").value = "";
    });
    
    $("#edit_pivc_ED").click(function (){
        button_state_pivc_ED('wait');
        enableForm('#formPivc_ED');
        rdonly('#formPivc_ED');
        $("#formPivc_ED :input[name='practiceDate']").attr("readonly", true);
    });
    
    $("#save_pivc_ED").click(function (){
        disableForm('#formPivc_ED');
        if($('#formPivc_ED').isValid({requiredFields: ''}, conf, true)){
            saveForm_pivc_ED(function (){
                $("#cancel_pivc_ED").data('oper','edit');
                $("#cancel_pivc_ED").click();
                // $('#datetimepivc_ED_tbl').DataTable().ajax.reload();
            });
        }else{
            enableForm('#formPivc_ED');
            rdonly('#formPivc_ED');
        }
    });
    
    $("#cancel_pivc_ED").click(function (){
        disableForm('#formPivc_ED');
        button_state_pivc_ED($(this).data('oper'));
        $('#datetimepivc_ED_tbl').DataTable().ajax.reload();
    });
    //////////////////////////////////////pivc ends//////////////////////////////////////
    
    // to format number input to two decimal places (0.00)
    $(".floatNumberField").change(function (){
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });

     ////////////////////////////////////print button starts////////////////////////////////////

    $("#PIVCDialog_ED").dialog({
        autoOpen: false,
        width: 5/10 * $(window).width(),
        modal: true,
        open: function (){
            parent_close_disabled(true);
        },
        close: function (event, ui){
            parent_close_disabled(false);
            emptyFormdata(errorField,'#formdata_PIVC_ED');
        },
        buttons: [{
            text: "Print", click: function (){
                window.open('./pivc/pivc_chart?mrn='+$('#mrn_nursNote').val()+'&episno='+$("#episno_nursNote").val()+'&datefr='+$("#datefr_pivc").val()+'&dateto='+$("#dateto_pivc").val(), '_blank');
            }
        },{
            text: "Cancel", click: function (){
                $(this).dialog('close');
                emptyFormdata(errorField,'#formdata_PIVC_ED');
            }
        }],
    });

    $('#pivc_ED_chart').click(function(){
		$( "#PIVCDialog_ED" ).dialog( "open" );
	});
    /////////////////////////////////////print button ends/////////////////////////////////////
    
    ////////////////////////////////////////pivc starts////////////////////////////////////////
    $('#datetimepivc_ED_tbl tbody').on('click', 'tr', function (){
        var data = datetimepivc_ED_tbl.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            datetimepivc_ED_tbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formPivc_ED",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        $('#datetimepivc_ED_tbl tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#formPivc_ED :input[name='idno_pivc']").val(data.idno);
        
        var saveParam = {
            action: 'get_table_pivc_ED',
        }
        
        var postobj = {
            _token: $('#_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno
        };
        
        $.post("./ptcare_nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formPivc_ED",data.pivc);
                
                button_state_pivc_ED('edit');
            }else{
                button_state_pivc_ED('add');
            }
        });
    });
    /////////////////////////////////////////pivc ends/////////////////////////////////////////
    
});

/////////////////////pivc starts/////////////////////
var datetimepivc_ED_tbl = $('#datetimepivc_ED_tbl').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'practiceDate', 'width': '25%' },
        { 'data': 'adduser', 'width': '25%' },
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

button_state_pivc_ED('empty');
function button_state_pivc_ED(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_pivc_ED').data('oper','add');
            $('#new_pivc_ED,#save_pivc_ED,#cancel_pivc_ED,#edit_pivc_ED').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_pivc_ED').data('oper','add');
            $("#new_pivc_ED").attr('disabled',false);
            $('#save_pivc_ED,#cancel_pivc_ED,#edit_pivc_ED').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_pivc_ED').data('oper','edit');
            $("#new_pivc_ED,#edit_pivc_ED").attr('disabled',false);
            $('#save_pivc_ED,#cancel_pivc_ED').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_pivc_ED,#cancel_pivc_ED").attr('disabled',false);
            $('#edit_pivc_ED,#new_pivc_ED').attr('disabled',true);
            break;
    }
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

function saveForm_pivc_ED(callback){
    let oper = $("#cancel_pivc_ED").data('oper');

    var saveParam = {
        action: 'save_table_pivc_ED',
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
        mrn_nursNote: $('#mrn_nursNote').val(),
        episno_nursNote: $('#episno_nursNote').val(),
    };
    
    values = $("#formPivc_ED").serializeArray();
    
    values = values.concat(
        $('#formPivc_ED input[type=checkbox]:not(:checked)').map(
            function (){
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formPivc_ED input[type=checkbox]:checked').map(
            function (){
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formPivc_ED input[type=radio]:checked').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formPivc_ED select').map(
            function (){
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formPivc_ED input[type=radio]:checked').map(
    //         function (){
    //             return {"name": this.name, "value": this.value}
    //         }).get()
    // );
    
    $.post("./ptcare_nursingnote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
    }).fail(function (data){
        if(data.responseText !== ''){
            alert(data.responseText);
        }
        callback(data);
    });
}

function populate_pivc_ED_getdata(){
    disableForm('#formPivc_ED');
    emptyFormdata(errorField,"#formPivc_ED",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
    
    var saveParam = {
        action: 'get_table_pivc_ED',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val()
    };
    
    $.post("./ptcare_nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').fail(function (data){
        alert('there is an error');
    }).done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formPivc_ED",data.pivc);
           
            button_state_pivc_ED('edit');
        }else{
            button_state_pivc_ED('add');
        }
    });
}
/////////////////////////////////////////////////////pivc ends/////////////////////////////////////////////////////

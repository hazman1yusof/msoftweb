
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function (){
    
    var fdl = new faster_detail_load();
        
    ////////////////////////////////////////////progressnote starts////////////////////////////////////////////
    disableForm('#formProgress_ED');
    
    $("#new_progress_ED").click(function (){
        button_state_progress_ED('wait');
        enableForm('#formProgress_ED');
        rdonly('#formProgress_ED');
        emptyFormdata_div("#formProgress_ED",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        document.getElementById("idno_progress").value = "";
    });
    
    $("#edit_progress_ED").click(function (){
        button_state_progress_ED('wait');
        enableForm('#formProgress_ED');
        rdonly('#formProgress_ED');
        $("#formProgress_ED :input[name='datetaken'],#formProgress_ED :input[name='timetaken']").attr("readonly", true);
    });

    $("#save_progress_ED").click(function (){
        disableForm('#formProgress_ED');
        if($('#formProgress_ED').isValid({requiredFields: ''}, conf, true)){
            saveForm_progress_ED(function (){
                $("#cancel_progress_ED").data('oper','edit');
                $("#cancel_progress_ED").click();
                // $("#jqGridPagerRefresh").click();
                // $('#datetime_ED_tbl').DataTable().ajax.reload();
            });
        }else{
            enableForm('#formProgress_ED');
            rdonly('#formProgress_ED');
        }
    });
    
    $("#cancel_progress_ED").click(function (){
        disableForm('#formProgress_ED');
        button_state_progress_ED($(this).data('oper'));
        $('#datetime_ED_tbl').DataTable().ajax.reload();
    });
    //////////////////////////////////////////////progressnote ends//////////////////////////////////////////////  

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
    
    ////////////////////////////////////////progressnote starts////////////////////////////////////////
    $('#datetime_ED_tbl tbody').on('click', 'tr', function (){
        var data = datetime_ED_tbl.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            datetime_ED_tbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formProgress_ED",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        $('#datetime_ED_tbl tbody tr').removeClass('active');
        $(this).addClass('active');
        
        $("#formProgress_ED :input[name='idno_progress']").val(data.idno);

        var saveParam={
            action: 'get_table_progress_ED',
        }
        
        var postobj={
            _token: $('#_token').val(),
            idno: data.idno,
            mrn: data.mrn,
            episno: data.episno,
            // date:data.date

        };
        
        $.post("./ptcare_nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).done(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formProgress_ED",data.nurshandover);
                $("#formProgress_ED :input[name='datetaken']").val(data.date);
                $("#formProgress_ED :input[name='timetaken']").val(data.time);
                
                button_state_progress_ED('edit');
                textarea_init_nursingnote();
            }else{
                button_state_progress_ED('add');
                textarea_init_nursingnote();
            }
        });
    });
    
});

/////////////////////progressnote starts/////////////////////
var datetime_ED_tbl = $('#datetime_ED_tbl').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'datetaken', 'width': '25%' },
        { 'data': 'timetaken', 'width': '25%' },
        { 'data': 'adduser', 'width': '50%' },
        { 'data': 'location', 'width': '25%' },
    ],
    columnDefs: [
        { targets: [0, 1, 2], visible: false },
    ],
    order: [[0, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
//////////////////////progressnote ends//////////////////////

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

button_state_progress_ED('empty');
function button_state_progress_ED(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_progress_ED').data('oper','add');
            $('#new_progress_ED,#save_progress_ED,#cancel_progress_ED,#edit_progress_ED').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_progress_ED').data('oper','add');
            $("#new_progress_ED").attr('disabled',false);
            $('#save_progress_ED,#cancel_progress_ED,#edit_progress_ED').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_progress_ED').data('oper','edit');
            $("#edit_progress_ED,#new_progress_ED").attr('disabled',false);
            $('#save_progress_ED,#cancel_progress_ED').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_progress_ED,#cancel_progress_ED").attr('disabled',false);
            $('#edit_progress_ED,#new_progress_ED').attr('disabled',true);
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

function saveForm_progress_ED(callback){
    let oper = $("#cancel_progress_ED").data('oper');

    var saveParam = {
        action: 'save_table_progress_ED',
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
        episno_nursNote: $("#episno_nursNote").val(),
        epistycode: 'OP'
    };
    
    values = $("#formProgress_ED").serializeArray();
    
    values = values.concat(
        $('#formProgress_ED input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formProgress_ED input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formProgress_ED input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formProgress_ED select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./ptcare_nursingnote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
    }).fail(function (data){
        callback(data);
    });
}

function populate_progressnote_ED_getdata(){
    disableForm('#formProgress_ED');
    emptyFormdata(errorField,"#formProgress_ED",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);

    var saveParam = {
        action: 'get_table_progress_ED',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val(),
        // idno: $("#idno_progress").val(),

    };
    
    $.get("./ptcare_nursingnote/table?"+$.param(saveParam), $.param(postobj), function (data){
    },'json').done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formProgress_ED",data.nurshandover);
            $("#formProgress_ED :input[name='datetaken']").val(data.date);
            $("#formProgress_ED :input[name='timetaken']").val(data.time);
            
            button_state_progress_ED('edit');
            textarea_init_nursingnote();
        }else{
            button_state_progress_ED('add');
            textarea_init_nursingnote();
        }
        
    });

    // var urlparam_datetime_ED_tbl = {
    //     action: 'get_table_datetime_ED',
    //     mrn: $("#mrn_nursNote").val(),
    //     episno: $("#episno_nursNote").val()
    // }
    
    // datetime_ED_tbl.ajax.url("./ptcare_nursingnote/table?"+$.param(urlparam_datetime_ED_tbl)).load(function (data){
    //     emptyFormdata_div("#formProgress_ED",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
    //     $('#datetime_ED_tbl tbody tr:eq(0)').click();  // to select first row
    // });
}

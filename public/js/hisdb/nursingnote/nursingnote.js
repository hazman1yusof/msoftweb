
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
    
    var fdl = new faster_detail_load();
    
    textare_init_nursingnote();
    
    /////////////////////////////////////progressnote starts/////////////////////////////////////
    disableForm('#formProgress');
    
    $("#new_progress").click(function(){
        button_state_progress('wait');
        enableForm('#formProgress');
        rdonly('#formProgress');
        emptyFormdata_div("#formProgress",['#mrn_nursNote','#episno_nursNote']);
        document.getElementById("idno_progress").value = "";
        // dialog_mrn_edit.on();
    });
    
    $("#edit_progress").click(function(){
        button_state_progress('wait');
        enableForm('#formProgress');
        rdonly('#formProgress');
        $("#datetaken,#timetaken").attr("readonly", true);
        // dialog_mrn_edit.on();
    });
    
    $("#save_progress").click(function(){
        disableForm('#formProgress');
        if( $('#formProgress').isValid({requiredFields: ''}, conf, true) ) {
            saveForm_progress(function(){
                $("#cancel_progress").data('oper','edit');
                $("#cancel_progress").click();
                // $("#jqGridPagerRefresh").click();
                $('#datetime_tbl').DataTable().ajax.reload();
            });
        }else{
            enableForm('#formProgress');
            rdonly('#formProgress');
        }
    });
    
    $("#cancel_progress").click(function(){
        disableForm('#formProgress');
        button_state_progress($(this).data('oper'));
        // dialog_mrn_edit.off();
    });
    //////////////////////////////////////progressnote ends//////////////////////////////////////
    
    /////////////////////////////////////intakeoutput starts/////////////////////////////////////
    disableForm('#formIntake');
    
    $("#new_intake").click(function(){
        button_state_intake('wait');
        enableForm('#formIntake');
        rdonly('#formIntake');
        emptyFormdata_div("#formIntake",['#mrn_nursNote','#episno_nursNote']);
        // dialog_mrn_edit.on();
    });
    
    $("#edit_intake").click(function(){
        button_state_intake('wait');
        enableForm('#formIntake');
        rdonly('#formIntake');
        // dialog_mrn_edit.on();
    });
    
    $("#save_intake").click(function(){
        disableForm('#formIntake');
        if( $('#formIntake').isValid({requiredFields: ''}, conf, true) ) {
            saveForm_intake(function(){
                $("#cancel_intake").data('oper','edit');
                $("#cancel_intake").click();
                // $("#jqGridPagerRefresh").click();
            });
        }else{
            enableForm('#formIntake');
            rdonly('#formIntake');
        }
    });
    
    $("#cancel_intake").click(function(){
        disableForm('#formIntake');
        button_state_intake($(this).data('oper'));
        // dialog_mrn_edit.off();
    });
    //////////////////////////////////////intakeoutput ends//////////////////////////////////////
    
    // to format number input to two decimal places (0.00)
    $(".floatNumberField").change(function() {
        $(this).val(parseFloat($(this).val()).toFixed(2));
    });
    
    $("#jqGridNursNote_panel").on("show.bs.collapse", function(){
        var urlparam_datetime_tbl={
            action: 'get_table_datetime',
            mrn: $("#mrn_nursNote").val(),
            episno: $("#episno_nursNote").val()
        }
        
        datetime_tbl.ajax.url( "./nursingnote/table?"+$.param(urlparam_datetime_tbl) ).load(function(data){
            emptyFormdata_div("#formProgress",['#mrn_nursNote','#episno_nursNote']);
            $('#datetime_tbl tbody tr:eq(0)').click();  // to select first row
        });
        
        populate_intakeoutput_getdata();
    });
    
    $("#jqGridNursNote_panel").on("hide.bs.collapse", function(){
        button_state_progress('empty');
        button_state_intake('empty');
        disableForm('#formProgress');
        disableForm('#formIntake');
        $("#jqGridNursNote_panel > div").scrollTop(0);
    });
    
    $('#jqGridNursNote_panel').on('shown.bs.collapse', function () {
        SmoothScrollTo("#jqGridNursNote_panel", 500);
        populate_progressnote_getdata();
        populate_intakeoutput_getdata();
    });
    
    $('#jqGridNursNote_panel').on('hidden.bs.collapse', function () {
        // button_state_progress('empty');
        // button_state_intake('empty');
    });
    
    $('.nav-tabs a').on('shown.bs.tab', function(e){
        let type = $(this).data('type');
        switch(type){
            case 'progress':
                populate_progressnote_getdata();
                break;
            case 'intake':
                populate_intakeoutput_getdata();
                break;
            case 'drug':
            
                break;
            case 'treatment':
            
                break;
            case 'careplan':
            
                break;
        }
    });
    
    $('#datetime_tbl tbody').on('click', 'tr', function () {
        var data = datetime_tbl.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            datetime_tbl.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formProgress",['#mrn_nursNote','#episno_nursNote']);
        $('#datetime_tbl tbody tr').removeClass('active');
        $(this).addClass('active');
        
        // populate_progressnote_getdata();
        $("#idno_progress").val(data.idno);
        
        var saveParam={
            action: 'get_table_progress',
        }
        
        var postobj={
            _token: $('#csrf_token').val(),
            idno: data.idno,
            // mrn: data.mrn,
            // episno: data.episno
        };
        
        $.post( "./nursingnote/form?"+$.param(saveParam), $.param(postobj), function( data ) {
            
        },'json').fail(function(data) {
            alert('there is an error');
        }).success(function(data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formProgress",data.nurshandover);
                $("#datetaken").val(data.date);
                
                button_state_progress('edit');
                textare_init_nursingnote();
            }else{
                button_state_progress('add');
                textare_init_nursingnote();
            }
        });
    });
    
});

var datetime_tbl = $('#datetime_tbl').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'idno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'datetaken', 'width': '25%' },
        { 'data': 'timetaken', 'width': '25%' },
        { 'data': 'lastuser', 'width': '50%' },
    ],
    columnDefs: [
        { targets: [0, 1, 2], visible: false },
    ],
    order: [[0, 'desc']],
    "drawCallback": function( settings ) {
        $(this).find('tbody tr')[0].click();
    }
});

var errorField = [];
conf = {
    modules : 'logic',
    language: {
        requiredFields: 'You have not answered all required fields'
    },
    onValidate: function ($form) {
        if (errorField.length > 0) {
            return {
                element: $(errorField[0]),
                message: ''
            }
        }
    },
};

button_state_progress('empty');
function button_state_progress(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_progress').data('oper','add');
            $('#new_progress,#save_progress,#cancel_progress,#edit_progress').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_progress').data('oper','add');
            $("#new_progress").attr('disabled',false);
            $('#save_progress,#cancel_progress,#edit_progress').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_progress').data('oper','edit');
            $("#new_progress,#edit_progress").attr('disabled',false);
            $('#save_progress,#cancel_progress').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_progress,#cancel_progress").attr('disabled',false);
            $('#edit_progress,#new_progress').attr('disabled',true);
            break;
    }
}

button_state_intake('empty');
function button_state_intake(state){
    switch(state){
        case 'empty':
            $("#toggle_nursNote").removeAttr('data-toggle');
            $('#cancel_intake').data('oper','add');
            $('#new_intake,#save_intake,#cancel_intake,#edit_intake').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_intake').data('oper','add');
            $("#new_intake").attr('disabled',false);
            $('#save_intake,#cancel_intake,#edit_intake').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $('#cancel_intake').data('oper','edit');
            $("#edit_intake").attr('disabled',false);
            $('#save_intake,#cancel_intake,#new_intake').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_intake,#cancel_intake").attr('disabled',false);
            $('#edit_intake,#new_intake').attr('disabled',true);
            break;
    }
}

// screen current patient //
function populate_nursingnote(obj){
    $("#jqGridNursNote_panel").collapse('hide');
    emptyFormdata(errorField,"#formProgress");
    emptyFormdata(errorField,"#formIntake");
    
    // panel header
    $('#name_show_nursNote').text(obj.Name);
    $('#mrn_show_nursNote').text(("0000000" + obj.MRN).slice(-7));
    $('#sex_show_nursNote').text(if_none(obj.Sex).toUpperCase());
    $('#dob_show_nursNote').text(dob_chg(obj.DOB));
    $('#age_show_nursNote').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_nursNote').text(if_none(obj.raceDesc).toUpperCase());
    $('#religion_show_nursNote').text(if_none(obj.religionDesc).toUpperCase());
    $('#occupation_show_nursNote').text(if_none(obj.occupDesc).toUpperCase());
    $('#citizenship_show_nursNote').text(if_none(obj.cityDesc).toUpperCase());
    $('#area_show_nursNote').text(if_none(obj.areaDesc).toUpperCase());
    
    $("#mrn_nursNote").val(obj.MRN);
    $("#episno_nursNote").val(obj.Episno);
    
    // var urlparam_datetime_tbl={
    //     action: 'get_table_datetime',
    //     mrn: $("#mrn_nursNote").val(),
    //     episno: $("#episno_nursNote").val()
    // }
    
    // datetime_tbl.ajax.url( "./nursingnote/table?"+$.param(urlparam_datetime_tbl) ).load(function(data){
    //     emptyFormdata_div("#formProgress",['#mrn_nursNote','#episno_nursNote']);
    //     $('#datetime_tbl tbody tr:eq(0)').click();  // to select first row
    // });
}

function populate_progressnote_getdata(){
    emptyFormdata(errorField,"#formProgress",["#mrn_nursNote","#episno_nursNote"]);
    
    var saveParam={
        action: 'get_table_progress',
    }
    
    var postobj={
        _token: $('#csrf_token').val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val()
    };
    
    $.post( "./nursingnote/form?"+$.param(saveParam), $.param(postobj), function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).success(function(data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formProgress",data.nurshandover);
            $("#datetaken").val(data.date);
            
            button_state_progress('edit');
            textare_init_nursingnote();
        }else{
            button_state_progress('add');
            textare_init_nursingnote();
        }
    });
}

function populate_intakeoutput_getdata(){
    emptyFormdata(errorField,"#formIntake",["#mrn_nursNote","#episno_nursNote"]);
    
    var saveParam={
        action: 'get_table_intake',
    }
    
    var postobj={
        _token: $('#csrf_token').val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val()
    };
    
    $.post( "./nursingnote/form?"+$.param(saveParam), $.param(postobj), function( data ) {
        
    },'json').fail(function(data) {
        alert('there is an error');
    }).success(function(data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formIntake",data.intakeoutput);
            
            button_state_intake('edit');
            textare_init_nursingnote();
        }else{
            button_state_intake('add');
            textare_init_nursingnote();
        }
    });
}

function autoinsert_rowdata(form,rowData){
    $.each(rowData, function( index, value ) {
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

function saveForm_progress(callback){
    var saveParam={
        action: 'save_table_progress',
        oper: $("#cancel_progress").data('oper')
    }
    
    var postobj={
        _token: $('#csrf_token').val(),
        mrn_nursNote: $('#mrn_nursNote').val(),
        episno_nursNote: $('#episno_nursNote').val()
    };
    
    values = $("#formProgress").serializeArray();
    
    values = values.concat(
        $('#formProgress input[type=checkbox]:not(:checked)').map(
            function() {
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formProgress input[type=checkbox]:checked').map(
            function() {
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formProgress input[type=radio]:checked').map(
            function() {
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formProgress select').map(
            function() {
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formProgress input[type=radio]:checked').map(
    //         function() {
    //             return {"name": this.name, "value": this.value}
    //         }).get()
    // );
    
    $.post( "./nursingnote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}

function saveForm_intake(callback){
    var saveParam={
        action: 'save_table_intake',
        oper: $("#cancel_intake").data('oper')
    }
    
    var postobj={
        _token: $('#csrf_token').val(),
        mrn_nursNote: $('#mrn_nursNote').val(),
        episno_nursNote: $('#episno_nursNote').val()
    };
    
    values = $("#formIntake").serializeArray();
    
    values = values.concat(
        $('#formIntake input[type=checkbox]:not(:checked)').map(
            function() {
                return {"name": this.name, "value": 0}
            }).get()
    );
    
    values = values.concat(
        $('#formIntake input[type=checkbox]:checked').map(
            function() {
                return {"name": this.name, "value": 1}
            }).get()
    );
    
    values = values.concat(
        $('#formIntake input[type=radio]:checked').map(
            function() {
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    values = values.concat(
        $('#formIntake select').map(
            function() {
                return {"name": this.name, "value": this.value}
            }).get()
    );
    
    // values = values.concat(
    //     $('#formIntake input[type=radio]:checked').map(
    //         function() {
    //             return {"name": this.name, "value": this.value}
    //         }).get()
    // );
    
    $.post( "./nursingnote/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values) , function( data ) {
        
    },'json').fail(function(data) {
        // alert('there is an error');
        callback();
    }).success(function(data){
        callback();
    });
}

function textare_init_nursingnote(){
    $('textarea#airwayfreetext,textarea#frfreetext,textarea#drainfreetext,textarea#ivfreetext,textarea#assesothers,textarea#plannotes,textarea#oraltype1,textarea#oraltype2,textarea#oraltype3,textarea#oraltype4,textarea#oraltype5,textarea#oraltype6,textarea#oraltype7,textarea#oraltype8,textarea#oraltype9,textarea#oraltype10,textarea#oraltype11,textarea#oraltype12,textarea#oraltype13,textarea#oraltype14,textarea#oraltype15,textarea#oraltype16,textarea#oraltype17,textarea#oraltype18,textarea#oraltype19,textarea#oraltype20,textarea#oraltype21,textarea#oraltype22,textarea#oraltype23,textarea#oraltype24,textarea#intratype1,textarea#intratype2,textarea#intratype3,textarea#intratype4,textarea#intratype5,textarea#intratype6,textarea#intratype7,textarea#intratype8,textarea#intratype9,textarea#intratype10,textarea#intratype11,textarea#intratype12,textarea#intratype13,textarea#intratype14,textarea#intratype15,textarea#intratype16,textarea#intratype17,textarea#intratype18,textarea#intratype19,textarea#intratype20,textarea#intratype21,textarea#intratype22,textarea#intratype23,textarea#intratype24,textarea#othertype1,textarea#othertype2,textarea#othertype3,textarea#othertype4,textarea#othertype5,textarea#othertype6,textarea#othertype7,textarea#othertype8,textarea#othertype9,textarea#othertype10,textarea#othertype11,textarea#othertype12,textarea#othertype13,textarea#othertype14,textarea#othertype15,textarea#othertype16,textarea#othertype17,textarea#othertype18,textarea#othertype19,textarea#othertype20,textarea#othertype21,textarea#othertype22,textarea#othertype23,textarea#othertype24').each(function () {
        if(this.value.trim() == ''){
            this.setAttribute('style', 'height:' + (40) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }else{
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }
    }).off().on('input', function () {
        if(this.scrollHeight>40){
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        }else{
            this.style.height = (40) + 'px';
        }
    });
}

// function calc_jq_height_onchange(jqgrid){
//     let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
//     if(scrollHeight<50){
//         scrollHeight = 50;
//     }else if(scrollHeight>300){
//         scrollHeight = 300;
//     }
//     $('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight);
// }

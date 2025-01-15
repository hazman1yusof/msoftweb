
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function (){
    
    // $("button.refreshbtn_nursNote").click(function (){
    //     empty_nursingnote_ptcare();
    //     populate_nursingnote_ptcare(selrowData('#jqGrid'));
    // });
    
    $('.menu .item').tab(
        // {'onLoad':
        //     function(){
        //         alert("Called");
        //         populate_progressnote_getdata();
        //     }
        // }    
    );
    
    var fdl = new faster_detail_load();

    textarea_init_nursingnote();
    
    disableForm('#formNursNote');
    
    ////////////////////////////////////////////progressnote starts////////////////////////////////////////////
    disableForm('#formProgress');
    
    $("#new_progress").click(function (){
        get_default_progressnote();
        // $('#cancel_progress').data('oper','add');
        button_state_progress('wait');
        enableForm('#formProgress');
        rdonly('#formProgress');
        emptyFormdata_div("#formProgress",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        document.getElementById("idno_progress").value = "";
    });
    
    $("#edit_progress").click(function (){
        button_state_progress('wait');
        enableForm('#formProgress');
        rdonly('#formProgress');
        $("#datetaken,#timetaken").attr("readonly", true);
    });

    $("#save_progress").click(function (){
        var urlparam_datetime_tbl={
			action:'get_table_datetime',
			mrn:$("#mrn_nursNote").val(),
			episno:$("#episno_nursNote").val(),
		}

        // disableForm('#formProgress');
        if($('#formProgress').isValid({requiredFields: ''}, conf, true)){
            saveForm_progress(function (data){
                $("#cancel_progress").data('oper','edit');
                $("#cancel_progress").click();
                datetime_tbl.ajax.url( "./ptcare_nursingnote/table?"+$.param(urlparam_datetime_tbl) ).load(function(data){
                    emptyFormdata_div("#formProgress",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
					// $('#datetime_tbl tbody tr:eq(0)').click();	//to select first row
			    });
                // $("#jqGridPagerRefresh").click();
                // $('#datetime_tbl').DataTable().ajax.reload();
            });
        }else{
            enableForm('#formProgress');
            rdonly('#formProgress');
        }
    });
    
    $("#cancel_progress").click(function (){
        disableForm('#formProgress');
        button_state_progress($(this).data('oper'));
        // $('#datetime_tbl').DataTable().ajax.reload();
    });
    //////////////////////////////////////////////progressnote ends//////////////////////////////////////////////  
    
    // $tabs = $('#requestFor .menu .item');
    
    // $tabs.tab({
    //     onVisible: function (tabPath){
    //         console.log("test");
    //     }
    // });
    
    // $tabs.first().tab('change tab', 'otbookReqFor');

    /////////////////////////////////////////progressnote ends/////////////////////////////////////////
    

    ////////////////////////////////////////progressnote starts////////////////////////////////////////
    $('#datetime_tbl tbody').on('click', 'tr', function (){
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
        
        emptyFormdata_div("#formProgress",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        $('#datetime_tbl tbody tr').removeClass('active');
        $(this).addClass('active');
        
        // populate_progressnote_getdata();
        $("#idno_progress").val(data.idno);
        
        var saveParam={
            action: 'get_table_progress',
        }
        
        var postobj={
            _token: $('#_token').val(),
            idno: data.idno,
            // mrn: data.mrn,
            // episno: data.episno,
            // date:data.date

        };
        
        $.post("./ptcare_nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        },'json').fail(function (data){
            alert('there is an error');
        }).success(function (data){
            if(!$.isEmptyObject(data)){
                autoinsert_rowdata("#formProgress",data.nurshandover);
                $("#datetaken").val(data.date);
                
                button_state_progress('edit');
                textarea_init_nursingnote();
            }else{
                button_state_progress('add');
                textarea_init_nursingnote();
            }
        });
    });

    /////////////////////////////////////////drug admin starts/////////////////////////////////////////
    $('#tbl_prescription tbody').on('click', 'tr', function (){
        var data = tbl_prescription.row( this ).data();
        
        if(data == undefined){
            return;
        }
        
        // to highlight selected row
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        }else {
            tbl_prescription.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        
        emptyFormdata_div("#formDrug",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        $('#tbl_prescription tbody tr').removeClass('active');
        $(this).addClass('active');
        
        populate_drugadmin_getdata();
        $("#trx_auditno").val(data.auditno);
        $("#trx_chgcode").val(data.chgcode);
        $("#trx_quantity").val(data.quantity);
        // $("#ftxtdosage").val(data.ftxtdosage);
        $("#dosage_nursNote").val(data.doscode_desc);
        $("#frequency_nursNote").val(data.frequency_desc);
        $("#instruction_nursNote").val(data.addinstruction_desc);
        $("#drugindicator_nursNote").val(data.drugindicator_desc);
        $("#doc_name").val($("#doctor_nursNote").val());
        textarea_init_nursingnote();
        get_total_qty();
        
        // jqGridPatMedic
        urlParam_PatMedic.filterVal[0] = data.mrn;
        urlParam_PatMedic.filterVal[1] = data.episno;
        urlParam_PatMedic.filterVal[2] = data.auditno;
        urlParam_PatMedic.filterVal[3] = data.chgcode;
        refreshGrid('#jqGridPatMedic',urlParam_PatMedic,'add');
        
        // var saveParam={
        //     action: 'get_table_drug',
        // }
        
        // var postobj={
        //     _token: $('#csrf_token').val(),
        //     mrn: $("#mrn_nursNote").val(),
        //     episno: $("#episno_nursNote").val(),
        //     auditno: $("#trx_auditno").val(),
        //     chgcode: $("#trx_chgcode").val()
        // };
        
        // $.post("./nursingnote/form?"+$.param(saveParam), $.param(postobj), function (data){
            
        // },'json').fail(function (data){
        //     alert('there is an error');
        // }).success(function (data){
        //     if(!$.isEmptyObject(data)){
        //         // autoinsert_rowdata("#formDrug",data.patmedication);
        //         $("#tot_qty").val(data.total_qty);
        //     }else{
                
        //     }
        // });
    });
    
    $("#tbl_prescription_refresh").click(function (){
        $('#tbl_prescription').DataTable().ajax.reload();
    });
    
});

/////////////////////progressnote starts/////////////////////
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
        { 'data': 'adduser', 'width': '50%' },
        { 'data': 'epistycode', 'width': '25%' },
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

//////////////////////drug admin starts//////////////////////
var tbl_prescription = $('#tbl_prescription').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        { 'data': 'auditno' },
        { 'data': 'mrn' },
        { 'data': 'episno' },
        { 'data': 'chgcode', 'width': '25%' },
        { 'data': 'description', 'width': '65%' },
        { 'data': 'quantity', 'width': '10%' },
        { 'data': 'doscode' },
        { 'data': 'doscode_desc' },
        { 'data': 'frequency' },
        { 'data': 'frequency_desc' },
        { 'data': 'ftxtdosage' },
        { 'data': 'addinstruction' },
        { 'data': 'addinstruction_desc' },
        { 'data': 'drugindicator' },
        { 'data': 'drugindicator_desc' },
    ],
    columnDefs: [
        { targets: [0, 1, 2, 6, 7, 8, 9, 10, 11, 12, 13, 14], visible: false },
    ],
    order: [[0, 'desc']],
    "drawCallback": function (settings){
        $(this).find('tbody tr')[0].click();
    }
});
///////////////////////drug admin ends///////////////////////

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
            $("#edit_progress").attr('disabled',false);
            $('#save_progress,#cancel_progress,#new_progress').attr('disabled',true);
            break;
        case 'wait':
            $("#toggle_nursNote").attr('data-toggle','collapse');
            $("#save_progress,#cancel_progress").attr('disabled',false);
            $('#edit_progress,#new_progress').attr('disabled',true);
            break;
    }
}

function empty_nursingnote_ptcare(obj){
    emptyFormdata(errorField,"#formNursNote");
    
    // panel header
    $('#name_show_nursNote').text('');
    $('#mrn_show_nursNote').text('');
    $('#sex_show_nursNote').text('');
    $('#dob_show_nursNote').text('');
    $('#age_show_nursNote').text('');
    $('#race_show_nursNote').text('');
    $('#religion_show_nursNote').text('');
    $('#occupation_show_nursNote').text('');
    $('#citizenship_show_nursNote').text('');
    $('#area_show_nursNote').text('');
    
    // formNursNote
    $('#mrn_nursNote').val('');
    $("#episno_nursNote").val('');
    $('#ptname_nursNote').val('');
    $('#preg_nursNote').val('');
    $('#ic_nursNote').val('');
    $('#doctorname_nursNote').val('');

    datetime_tbl.clear().draw();
}

function populate_nursingnote_ptcare(obj){
    $("#tab_nursNote").collapse('hide');
    emptyFormdata(errorField,"#formProgress");
    
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
    
    // formNursNote
    $('#mrn_nursNote').val(obj.MRN);
    $("#episno_nursNote").val(obj.Episno);
    $("#doctor_nursNote").val(dob_age(obj.DOB));
    $('#ptname_nursNote').val(obj.Name);
    $('#preg_nursNote').val(obj.pregnant);
    $('#ic_nursNote').val(obj.Newic);
    $('#doctorname_nursNote').val(obj.doctorname);
}

function populate_progressnote_getdata(){
    emptyFormdata(errorField,"#formProgress",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);

    var saveParam = {
        action: 'get_table_progress',
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
            autoinsert_rowdata("#formProgress",data.nurshandover);
            $("#datetaken").val(data.date);
            
            button_state_progress('edit');
            textarea_init_nursingnote();
        }else{
            button_state_progress('add');
            textarea_init_nursingnote();
        }
        
    });

    var urlparam_datetime_tbl = {
        action: 'get_table_datetime',
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val()
    }
    
    datetime_tbl.ajax.url("./ptcare_nursingnote/table?"+$.param(urlparam_datetime_tbl)).load(function (data){
        emptyFormdata_div("#formProgress",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
        $('#datetime_tbl tbody tr:eq(0)').click();  // to select first row
    });
}

function get_default_progressnote(){
    emptyFormdata(errorField,"#formProgress",["#mrn_nursNote","#episno_nursNote","#doctor_nursNote","#ordcomtt_phar"]);
    
    var saveParam = {
        action: 'get_table_progress',
    }
    
    var postobj = {
        _token: $('#_token').val(),
        // idno: $("#idno_radClinic").val(),
        mrn: $("#mrn_nursNote").val(),
        episno: $("#episno_nursNote").val(),
    };
    
    $.get("./ptcare_nursingnote/table?"+$.param(saveParam), $.param(postobj), function (data){
        
    },'json').done(function (data){
        if(!$.isEmptyObject(data)){
            autoinsert_rowdata("#formProgress",data.nurshandover);
            $("#datetaken").val(data.date);

        }else{
            
        }
        
    });
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

function saveForm_progress(callback){
    var saveParam = {
        action: 'save_table_progress',
        oper: $("#cancel_progress").data('oper'),
    }
    
    var postobj = {
        _token: $('#_token').val(),
        mrn: $('#mrn_nursNote').val(),
        episno: $("#episno_nursNote").val(),
        epistycode: $("#epistycode").val()
    };
    
    values = $("#formProgress").serializeArray();
    
    values = values.concat(
        $('#formProgress input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formProgress input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formProgress input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formProgress select').map(
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

function textarea_init_nursingnote(){
    $('textarea#airwayfreetext,textarea#frfreetext,textarea#drainfreetext,textarea#ivfreetext,textarea#assesothers,textarea#plannotes,textarea#oraltype1,textarea#oraltype2,textarea#oraltype3,textarea#oraltype4,textarea#oraltype5,textarea#oraltype6,textarea#oraltype7,textarea#oraltype8,textarea#oraltype9,textarea#oraltype10,textarea#oraltype11,textarea#oraltype12,textarea#oraltype13,textarea#oraltype14,textarea#oraltype15,textarea#oraltype16,textarea#oraltype17,textarea#oraltype18,textarea#oraltype19,textarea#oraltype20,textarea#oraltype21,textarea#oraltype22,textarea#oraltype23,textarea#oraltype24,textarea#intratype1,textarea#intratype2,textarea#intratype3,textarea#intratype4,textarea#intratype5,textarea#intratype6,textarea#intratype7,textarea#intratype8,textarea#intratype9,textarea#intratype10,textarea#intratype11,textarea#intratype12,textarea#intratype13,textarea#intratype14,textarea#intratype15,textarea#intratype16,textarea#intratype17,textarea#intratype18,textarea#intratype19,textarea#intratype20,textarea#intratype21,textarea#intratype22,textarea#intratype23,textarea#intratype24,textarea#othertype1,textarea#othertype2,textarea#othertype3,textarea#othertype4,textarea#othertype5,textarea#othertype6,textarea#othertype7,textarea#othertype8,textarea#othertype9,textarea#othertype10,textarea#othertype11,textarea#othertype12,textarea#othertype13,textarea#othertype14,textarea#othertype15,textarea#othertype16,textarea#othertype17,textarea#othertype18,textarea#othertype19,textarea#othertype20,textarea#othertype21,textarea#othertype22,textarea#othertype23,textarea#othertype24,textarea#ftxtdosage,textarea#treatment_remarks,textarea#investigation_remarks,textarea#injection_remarks,textarea#problem,textarea#problemdata,textarea#problemintincome,textarea#nursintervention,textarea#nursevaluation,textarea#fitchart_diag,textarea#circulation_diag,textarea#othersChart1_diag,textarea#othersChart2_diag').each(function () {
        if(this.value.trim() == ''){
            this.setAttribute('style', 'height:' + (40) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }else{
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;min-height:'+ (40) +'px;overflow-y:hidden;');
        }
    }).off().on('input', function (){
        if(this.scrollHeight>40){
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        }else{
            this.style.height = (40) + 'px';
        }
    });
}

$('#tab_nursNote').on('shown.bs.collapse', function (){
    SmoothScrollTo("#tab_nursNote", 500);
        // var urlParam={
        //     action:'get_table_progress',
        // }
        // var postobj={
        //     _token : $('#_token').val(),
        //     mrn:$("#mrn_nursNote").val(),
        //     episno:$("#episno_nursNote").val(),
        // };

        // $.post( "./ptcare_nursingnote/form?"+$.param(urlParam), $.param(postobj), function( data ) {
            
        // },'json').fail(function(data) {
        //     alert('there is an error');
        // }).done(function(data){
        //     if(!$.isEmptyObject(data)){
        //         autoinsert_rowdata("#formProgress",data.nurshandover);
        //         $("#datetaken").val(data.date);
        //         button_state_progress('edit');
        //     }else{
        //         button_state_progress('add');
        //     }

        // });

        // var urlparam_datetime_tbl={
        //     action:'get_table_datetime',
        //     mrn:$("#mrn_nursNote").val(),
        //     episno:$("#episno_nursNote").val(),
        // }

        // datetime_tbl.ajax.url( "./ptcare_nursingnote/table?"+$.param(urlparam_datetime_tbl) ).load(function(data){
        //     emptyFormdata_div("#formDieteticCareNotes_fup",['#mrn_nursNote','#episno_nursNote']);
        //     // $('#datetime_tbl tbody tr:eq(0)').click();	//to select first row
        // });

    // let tab = $(this).data('id');
    // let id = $(this).attr('id');
    // $("#nursNote").data('tab',id); console.log(tab);
    // switch(tab){
    //     case 'progress':   
    //         var urlparam_datetime_tbl = {
    //             action: 'get_table_datetime',
    //             mrn: $("#mrn_nursNote").val(),
    //             episno: $("#episno_nursNote").val()
    //         }
            
    //         datetime_tbl.ajax.url("./ptcare_nursingnote/table?"+$.param(urlparam_datetime_tbl)).load(function (data){
    //             emptyFormdata_div("#formProgress",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
    //             $('#datetime_tbl tbody tr:eq(0)').click();  // to select first row
    //         });
            
    //         // $('#datetime_tbl').DataTable().ajax.reload();
    //         populate_progressnote_getdata();
    //         break;
    // }
    //     // case 'drug':
    //     //     var urlparam_tbl_prescription = {
    //     //         action: 'get_prescription',
    //     //         mrn: $("#mrn_nursNote").val(),
    //     //         episno: $("#episno_nursNote").val(),
    //     //         chggroup: $('#ordcomtt_phar').val(),
    //     //     }
            
    //     //     tbl_prescription.ajax.url("./ptcare_nursingnote/table?"+$.param(urlparam_tbl_prescription)).load(function (data){
    //     //         emptyFormdata_div("#formDrug",['#mrn_nursNote','#episno_nursNote','#doctor_nursNote','#ordcomtt_phar']);
    //     //         $('#tbl_prescription tbody tr:eq(0)').click();  // to select first row
    //     //     });
            
    //     //     // $('#tbl_prescription').DataTable().ajax.reload();
    //     //     $("#jqGridPatMedic").jqGrid('setGridWidth', Math.floor($("#jqGridPatMedic_c")[0].offsetWidth-$("#jqGridPatMedic_c")[0].offsetLeft-30));
    //     //     populate_drugadmin_getdata();
    //     //     break;
        
    // }
    
    populate_progressnote_getdata();

    
});

$('#tab_nursNote .menu .item').on('shown.bs.tab', function (e){
    let tab = $(this).data('tab'); console.log(tab);
    switch(tab){
        case 'progress':
            populate_progressnote_getdata();
            break;
        
    }
});

$("#tab_nursNote").on("hide.bs.collapse", function (){
    button_state_progress('empty');
    disableForm('#formNursNote');
    disableForm('#formProgress');
});



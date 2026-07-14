
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;
var dateParam_docNoteAppt,doctornote_docNoteAppt,curr_obj_docNoteAppt;

///////////////////////////////////parameter for jqGridAddNotesAppt url///////////////////////////////////
// var urlParam_AddNotesAppt = {
//     action: 'get_table_default',
//     url: './util/get_table_default',
//     field: '',
//     table_name: 'hisdb.pathealthadd',
//     table_id: 'idno',
//     filterCol: ['mrn','episno'],
//     filterVal: ['',''],
// }

var urlParam_AddNotesAppt = {
    action: 'get_table_default',
    url: 'util/get_table_default',
    field: '',
    table_name: 'nursing.nursaddnote',
    table_id: 'idno',
    filterCol: ['mrn','episno','type'],
    filterVal: ['','','DOCTOR NOTE APPT'],
}

$(document).ready(function (){
    
    var fdl = new faster_detail_load();
    
    disableForm('#formDoctorNoteAppt',['toggle_type_docNoteAppt']);
    
    $("button.refreshbtn_doctornote").click(function (){
        populate_currDoctorNoteAppt(selrowData('#jqGrid'));
    });
    
    $("#new_doctorNoteAppt").click(function (){
        $('#docNoteAppt_date_tbl tbody tr').removeClass('active');
        $('#cancel_doctorNoteAppt').data('oper','add');
        button_state_doctorNoteAppt('wait');
        enableForm('#formDoctorNoteAppt');
        rdonly('#formDoctorNoteAppt');
        emptyFormdata_div("#formDoctorNoteAppt",['#mrn_doctorNoteAppt','#episno_doctorNoteAppt']);
    });
    
    $("#edit_doctorNoteAppt").click(function (){
        button_state_doctorNoteAppt('wait');
        enableForm('#formDoctorNoteAppt');
        rdonly('#formDoctorNoteAppt');
    });
    
    $("#save_doctorNoteAppt").click(function (){
        if($('#formDoctorNoteAppt').isValid({requiredFields: ''}, conf, true)){
            saveForm_doctorNoteAppt(function (data){
                emptyFormdata_div("#formDoctorNoteAppt",['#mrn_doctorNoteAppt','#episno_doctorNoteAppt']);
                disableForm('#formDoctorNoteAppt',['toggle_type_docNoteAppt']);
                docNoteAppt_date_tbl.ajax.url("./doctorNoteAppt/table?"+$.param(dateParam_docNoteAppt)).load(function (){
                    
                });
            });
        }else{
            enableForm('#formDoctorNoteAppt');
            rdonly('#formDoctorNoteAppt');
        }
    });
    
    $("#cancel_doctorNoteAppt").click(function (){
        emptyFormdata_div("#formDoctorNoteAppt",['#mrn_doctorNoteAppt','#episno_doctorNoteAppt']);
        disableForm('#formDoctorNoteAppt',['toggle_type_docNoteAppt']);
        button_state_doctorNoteAppt($(this).data('oper'));
        $('#docNoteAppt_date_tbl tbody tr:eq(0)').click(); // to select first row
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
    
    ////////////////////////////////////////parameter for saving url////////////////////////////////////////
    var addmore_jqgrid = {more:false,state:false,edit:false}
    
    ///////////////////////////////////////////jqGridAddNotesAppt///////////////////////////////////////////
    $("#jqGridAddNotesAppt").jqGrid({
        datatype: "local",
        editurl: "/doctorNoteAppt/form",
        colModel: [
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'id', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'Note', name: 'note', classes: 'wrap', width: 120, editable: true, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
            { label: 'Entered by', name: 'adduser', width: 50, hidden: false },
            { label: 'Date', name: 'adddate', width: 50, hidden: false },
        ],
        autowidth: true,
        multiSort: true,
        sortname: 'idno',
        sortorder: 'desc',
        viewrecords: true,
        loadonce: false,
        scroll: true,
        width: 900,
        height: 200,
        rowNum: 30,
        pager: "#jqGridPagerAddNotesAppt",
        loadComplete: function (){
            if(addmore_jqgrid.more == true){$('#jqGridAddNotesAppt_iladd').click();}
            else{
                $('#jqGrid2').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid.edit = addmore_jqgrid.more = false; // reset
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridAddNotesAppt_iledit").click();
        },
    });
    
    //////////////////////////////////////////////myEditOptions//////////////////////////////////////////////
    var myEditOptions_add = {
        keys: true,
        extraparam: {
            "_token": $("#_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_addNotesAppt,#jqGridPagerRefresh_addNotesAppt").hide();
            
            $("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridAddNotesAppt_ilsave').click();
                // addmore_jqgrid.state = true;
                // $('#jqGridAddNotesAppt_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // addmore_jqgrid.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridAddNotesAppt',urlParam_AddNotesAppt,'addNotes_docNoteAppt');
            errorField.length = 0;
            $("#jqGridPagerDelete_addNotesAppt,#jqGridPagerRefresh_addNotesAppt").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridAddNotesAppt',urlParam_AddNotesAppt,'addNotes_docNoteAppt');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            if(errorField.length > 0)return false;
            
            let data = $('#jqGridAddNotesAppt').jqGrid('getRowData', rowid);
            
            let editurl = "/doctorNoteAppt/form?"+
                $.param({
                    _token: $('#_token').val(),
                    episno: $('#episno_doctorNoteAppt_past').val(),
                    mrn: $('#mrn_doctorNoteAppt_past').val(),
                    action: 'doctorNoteAppt_save',
                });
            $("#jqGridAddNotesAppt").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_addNotesAppt,#jqGridPagerRefresh_addNotesAppt").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    /////////////////////////////////////////jqGridPagerAddNotesAppt/////////////////////////////////////////
    $("#jqGridAddNotesAppt").inlineNav('#jqGridPagerAddNotesAppt', {
        add: true,
        edit: false,
        cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add
        },
        // editParams: myEditOptions_edit
    })
    // .jqGrid('navButtonAdd', "#jqGridPagerAddNotesAppt", {
    // 	id: "jqGridPagerDelete_addNotesAppt",
    // 	caption: "", cursor: "pointer", position: "last",
    // 	buttonicon: "glyphicon glyphicon-trash",
    // 	title: "Delete Selected Row",
    // 	onClickButton: function (){
    // 		selRowId = $("#jqGridAddNotesAppt").jqGrid('getGridParam', 'selrow');
    // 		if(!selRowId){
    // 			alert('Please select row');
    // 		}else{
    // 			var result = confirm("Are you sure you want to delete this row?");
    // 			if(result == true){
    // 				param = {
    // 					_token: $("#_token").val(),
    // 					action: 'doctorNoteAppt_save',
    // 					idno: selrowData('#jqGridAddNotesAppt').idno,
    // 				}
                    
    // 				$.post("/doctorNoteAppt/form?"+$.param(param), {oper:'del'}, function (data){
                        
    // 				}).fail(function (data){
    // 					//////////////////errorText(dialog,data.responseText);
    // 				}).done(function (data){
    // 					refreshGrid("#jqGridAddNotesAppt", urlParam_AddNotesAppt);
    // 				});
    // 			}else{
    // 				$("#jqGridPagerDelete_addNotesAppt,#jqGridPagerRefresh_addNotesAppt").show();
    // 			}
    // 		}
    // 	},
    // })
    .jqGrid('navButtonAdd', "#jqGridPagerAddNotesAppt", {
        id: "jqGridPagerRefresh_addNotesAppt",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridAddNotesAppt", urlParam_AddNotesAppt);
        },
    });
    /////////////////////////////////////////////////end grid/////////////////////////////////////////////////
    
});

var errorField = [];
conf = {
    modules: 'logic',
    language: {
        requiredFields: 'You have not answered all required fields'
    },
    onValidate: function ($form){
        if(errorField.length > 0){
            return{
                element: $(errorField[0]),
                message: ''
            }
        }
    },
};

button_state_doctorNoteAppt('empty');
function button_state_doctorNoteAppt(state){
    empty_transaction('add');
    
    if($('#isdoctor').val() != '1'){
        $("#toggle_doctorNoteAppt").removeAttr('data-toggle');
        $('#cancel_doctorNoteAppt').data('oper','add');
        $('#new_doctorNoteAppt,#save_doctorNoteAppt,#cancel_doctorNoteAppt,#edit_doctorNoteAppt').attr('disabled',true);
        $('#jqGridAddNotesAppt_iladd').addClass('ui-state-disabled');
        return 0;
    }
    
    switch(state){
        case 'empty':
            $("#toggle_doctorNoteAppt").removeAttr('data-toggle');
            $('#cancel_doctorNoteAppt').data('oper','add');
            $('#new_doctorNoteAppt,#save_doctorNoteAppt,#cancel_doctorNoteAppt,#edit_doctorNoteAppt').attr('disabled',true);
            break;
        case 'add':
            $("#toggle_doctorNoteAppt").attr('data-toggle','collapse');
            $('#cancel_doctorNoteAppt').data('oper','add');
            $("#new_doctorNoteAppt,#current_doctorNoteAppt,#past_doctorNoteAppt").attr('disabled',false);
            $('#save_doctorNoteAppt,#cancel_doctorNoteAppt,#edit_doctorNoteAppt').attr('disabled',true);
            break;
        case 'edit':
            $("#toggle_doctorNoteAppt").attr('data-toggle','collapse');
            $('#cancel_doctorNoteAppt').data('oper','edit');
            $("#edit_doctorNoteAppt,#new_doctorNoteAppt").attr('disabled',false);
            $('#save_doctorNoteAppt,#cancel_doctorNoteAppt').attr('disabled',true);
            break;
        case 'wait':
            hide_tran_button(false);
            $("#toggle_doctorNoteAppt").attr('data-toggle','collapse');
            $("#save_doctorNoteAppt,#cancel_doctorNoteAppt").attr('disabled',false);
            $('#edit_doctorNoteAppt,#new_doctorNoteAppt').attr('disabled',true);
            break;
        // case 'docnote':
        //     $("#toggle_doctorNoteAppt").attr('data-toggle','collapse');
        //     $('#cancel_doctorNoteAppt').data('oper','add');
        //     $("#new_doctorNoteAppt").attr('disabled',false);
        //     $('#save_doctorNoteAppt,#cancel_doctorNoteAppt').attr('disabled',true);
        //     break;
    }
}

function empty_currDoctorNoteAppt(){
    emptyFormdata_div("#formDoctorNoteAppt",['#mrn_doctorNoteAppt','#episno_doctorNoteAppt']);
    button_state_doctorNoteAppt('empty');
    
    // panel header
    $('#name_show_doctorNoteAppt').text('');
    $('#mrn_show_doctorNoteAppt').text('');
    $('#sex_show_doctorNoteAppt').text('');
    $('#dob_show_doctorNoteAppt').text('');
    $('#age_show_doctorNoteAppt').text('');
    $('#race_show_doctorNoteAppt').text('');
    $('#religion_show_doctorNoteAppt').text('');
    $('#occupation_show_doctorNoteAppt').text('');
    $('#citizenship_show_doctorNoteAppt').text('');
    $('#area_show_doctorNoteAppt').text('');
    
    // formDoctorNoteAppt
    $('#mrn_doctorNoteAppt').val('');
    $("#episno_doctorNoteAppt").val('');
    
    docNoteAppt_date_tbl.clear().draw();
}

//screen current patient//
function populate_currDoctorNoteAppt(obj){
    curr_obj_docNoteAppt = obj;
    
    emptyFormdata_div("#formDoctorNoteAppt",['#mrn_doctorNoteAppt','#episno_doctorNoteAppt']);
    
    // panel header
    $('#name_show_doctorNoteAppt').text(obj.Name);
    $('#mrn_show_doctorNoteAppt').text(("0000000" + obj.MRN).slice(-7));
    $('#sex_show_doctorNoteAppt').text(if_none(obj.Sex).toUpperCase());
    $('#dob_show_doctorNoteAppt').text(dob_chg(obj.DOB));
    $('#age_show_doctorNoteAppt').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_doctorNoteAppt').text(if_none(obj.raceDesc).toUpperCase());
    $('#religion_show_doctorNoteAppt').text(if_none(obj.religion).toUpperCase());
    $('#occupation_show_doctorNoteAppt').text(if_none(obj.OccupCode).toUpperCase());
    $('#citizenship_show_doctorNoteAppt').text(if_none(obj.Citizencode).toUpperCase());
    $('#area_show_doctorNoteAppt').text(if_none(obj.AreaCode).toUpperCase());
    
    // formDoctorNoteAppt
    $('#mrn_doctorNoteAppt').val(obj.MRN);
    $("#episno_doctorNoteAppt").val(obj.Episno);
    $("#age_doctorNoteAppt").val(dob_age(obj.DOB));
    $('#ptname_doctorNoteAppt').val(obj.Name);
    // $('#preg_doctorNoteAppt').val(obj.pregnant);
    $('#ic_doctorNoteAppt').val(obj.Newic);
    $('#doctorname_doctorNoteAppt').val(obj.doctorname);
    
    on_toggling_curr_past_doctorNoteAppt(obj);
    
    urlParam_AddNotesAppt.filterVal[0] = obj.MRN;
    urlParam_AddNotesAppt.filterVal[1] = obj.Episno;
    urlParam_AddNotesAppt.filterVal[2] = 'DOCTOR NOTE APPT';
    
    doctornote_docNoteAppt = {
        action: 'get_table_doctorNoteAppt',
        mrn: obj.MRN,
        episno: obj.Episno,
        recorddate: ''
    };
    
    $("#tab_doctornote").collapse('hide');
    button_state_doctorNoteAppt('empty');
}

function on_toggling_curr_past_doctorNoteAppt(obj = curr_obj_docNoteAppt){
    var addNotesAppt = document.getElementById("addNotesAppt");
    
    if($('.pastcurr_docNoteAppt').find('[name="toggle_type_docNoteAppt"]:checked').val() == 'current'){
        dateParam_docNoteAppt = {
            action: 'get_table_date_curr',
            mrn: obj.MRN,
            episno: obj.Episno,
            date: $('#sel_date').val()
        }
        
        // addNotesAppt.style.display = "none";
        addNotesAppt.style.display = "block";
        button_state_doctorNoteAppt('add'); // enable balik button
    }else if($('.pastcurr_docNoteAppt').find('[name="toggle_type_docNoteAppt"]:checked').val() == 'past'){
        dateParam_docNoteAppt = {
            action: 'get_table_date_past',
            mrn: obj.MRN,
        }
        
        addNotesAppt.style.display = "block";
        button_state_doctorNoteAppt('empty'); // disable all buttons
        $('#jqGridPagerRefresh_addNotesAppt').click();
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

function saveForm_doctorNoteAppt(callback){
    let oper = $("#cancel_doctorNoteAppt").data('oper');
    var saveParam = {
        action: 'save_table_doctorNoteAppt',
        oper: oper,
    }
    
    if(oper == 'add'){
        saveParam.sel_date = $('#sel_date').val();
    }else if(oper == 'edit'){
        var row = docNoteAppt_date_tbl.row('.active').data();
        saveParam.sel_date = $('#sel_date').val();
        saveParam.timetaken = row.timetaken;
    }
    
    var postobj = {
        _token: $('#_token').val(),
        // sex_edit: $('#sex_edit').val(),
        // idtype_edit: $('#idtype_edit').val()
    };
    
    values = $("#formDoctorNoteAppt").serializeArray();
    
    values = values.concat(
        $('#formDoctorNoteAppt input[type=checkbox]:not(:checked)').map(
        function (){
            return {"name": this.name, "value": 0}
        }).get()
    );
    
    values = values.concat(
        $('#formDoctorNoteAppt input[type=checkbox]:checked').map(
        function (){
            return {"name": this.name, "value": 1}
        }).get()
    );
    
    values = values.concat(
        $('#formDoctorNoteAppt input[type=radio]:checked').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    values = values.concat(
        $('#formDoctorNoteAppt select').map(
        function (){
            return {"name": this.name, "value": this.value}
        }).get()
    );
    
    $.post("./doctorNoteAppt/form?"+$.param(saveParam), $.param(postobj)+'&'+$.param(values), function (data){
        
    },'json').done(function (data){
        callback(data);
    }).fail(function (data){
        callback(data);
    });
}

var docNoteAppt_date_tbl = $('#docNoteAppt_date_tbl').DataTable({
    "ajax": "",
    "sDom": "",
    "paging": false,
    "columns": [
        {'data': 'mrn'},
        {'data': 'episno'},
        {'data': 'date', 'width': '50%'},
        {'data': 'adduser'},
        {'data': 'datetaken'},
        {'data': 'timetaken'},
        {'data': 'doctorname', 'width': '50%'},
    ],
    columnDefs: [
        { targets: [0, 1, 3, 4, 5], visible: false },
    ],
    "drawCallback": function (settings){
        if(settings.aoData.length > 0){
            $(this).find('tbody tr')[0].click();
        }else{
            button_state_doctorNoteAppt('add');
        }
    }
});

$('#tab_doctornote').on('show.bs.collapse', function (){
    return check_if_user_selected();
});

$('#tab_doctornote').on('shown.bs.collapse', function (){
    SmoothScrollTo('#tab_doctornote', 200);
    $('div#docNoteAppt_date_tbl_sticky').show();
    
    docNoteAppt_date_tbl.ajax.url("./doctorNoteAppt/table?"+$.param(dateParam_docNoteAppt)).load(function (data){
        emptyFormdata_div("#formDoctorNoteAppt",['#mrn_doctorNoteAppt','#episno_doctorNoteAppt']);
        // $('#docNoteAppt_date_tbl tbody tr:eq(0)').click(); // to select first row
    });
});

$('#tab_doctornote').on('hide.bs.collapse', function (){
    $('div#docNoteAppt_date_tbl_sticky').hide();
});

// to reload date table on radio btn click
$("input[name=toggle_type_docNoteAppt]").on('change', function (){
    event.stopPropagation();
    on_toggling_curr_past_doctorNoteAppt(curr_obj_docNoteAppt);
    docNoteAppt_date_tbl.ajax.url("./doctorNoteAppt/table?"+$.param(dateParam_docNoteAppt)).load(function (data){
        emptyFormdata_div("#formDoctorNoteAppt",['#mrn_doctorNoteAppt','#episno_doctorNoteAppt']);
        // $('#docNoteAppt_date_tbl tbody tr:eq(0)').click(); // to select first row
    });
    $("#jqGridAddNotesAppt").jqGrid('setGridWidth', Math.floor($("#jqGridAddNotesAppt_c")[0].offsetWidth-$("#jqGridAddNotesAppt_c")[0].offsetLeft-14));
});

$('#docNoteAppt_date_tbl tbody').on('click', 'tr', function (){
    var data = docNoteAppt_date_tbl.row(this).data();
    
    emptyFormdata_div("#formDoctorNoteAppt",['#mrn_doctorNoteAppt','#episno_doctorNoteAppt']);
    $('#docNoteAppt_date_tbl tbody tr').removeClass('active');
    $(this).addClass('active');
    
    doctornote_docNoteAppt.datetaken = data.datetaken;
    doctornote_docNoteAppt.timetaken = data.timetaken;
    doctornote_docNoteAppt.mrn = data.mrn;
    doctornote_docNoteAppt.episno = data.episno;
    
    urlParam_AddNotesAppt.filterVal[0] = data.mrn;
    urlParam_AddNotesAppt.filterVal[1] = data.episno;
    urlParam_AddNotesAppt.filterVal[2] = 'DOCTOR NOTE APPT';
    
    $('#mrn_doctorNoteAppt_past').val(data.mrn);
    $('#episno_doctorNoteAppt_past').val(data.episno);
    $('#recorddate_doctorNoteAppt').val(data.date);
    
    $.get("./doctorNoteAppt/table?"+$.param(doctornote_docNoteAppt), function (data){
        
    },'json').done(function (data){
        if(!$.isEmptyObject(data)){
            // autoinsert_rowdata("#formDoctorNoteAppt",data.episode);
            autoinsert_rowdata("#formDoctorNoteAppt",data.patprogressnote);
            refreshGrid('#jqGridAddNotesAppt',urlParam_AddNotesAppt,'addNotes_docNoteAppt');
            button_state_doctorNoteAppt('add');
            
            // if(data.patprogressnote == undefined){
            //     button_state_doctorNoteAppt('add');
            // }else{
            //     button_state_doctorNoteAppt('edit');
            // }
        }
        
        if($('.pastcurr_docNoteAppt').find('[name="toggle_type_docNoteAppt"]:checked').val() == 'past'){
            button_state_doctorNoteAppt('empty');
        }
    });
});

function check_same_usr_edit(data){
    let same = true;
    var adduser = data.adduser;
    
    if(adduser == undefined){
        return false
    }else if(adduser.toUpperCase() != $('#curr_user').val().toUpperCase()){
        return false;
    }
    
    return same;
}
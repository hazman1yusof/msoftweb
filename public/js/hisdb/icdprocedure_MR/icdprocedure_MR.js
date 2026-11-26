$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

////////////////////////////////////parameter for jqGridFinalDiag url////////////////////////////////////
// var urlParam_FinalDiag = {
//     action: 'get_table_default',
//     url: 'util/get_table_default',
//     field: '',
//     table_name: 'hisdb.episdiag',
//     table_id: 'seq',
//     filterCol: ['mrn','episno'],
//     filterVal: ['',''],
// }

var urlParam_FinalDiag = {
    action: 'get_table_finalDiag',
    url: './icdprocedure_MR/table',
    mrn: $('#mrn_icdprocedure').val(),
    episno: $('#episno_icdprocedure').val(),
}

///////////////////////////////////parameter for jqGridExternalCause url///////////////////////////////////
// var urlParam_ExternalCause = {
//     action: 'get_table_default',
//     url: 'util/get_table_default',
//     field: '',
//     table_name: 'hisdb.episdiag',
//     table_id: 'seq',
//     filterCol: ['mrn','episno'],
//     filterVal: ['',''],
// }

var urlParam_ExternalCause = {
    action: 'get_table_externalCause',
    url: './icdprocedure_MR/table',
    mrn: $('#mrn_icdprocedure').val(),
    episno: $('#episno_icdprocedure').val(),
}

$(document).ready(function (){
    
    // $('.menu .item').tab();
    
    var fdl = new faster_detail_load();
    
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
    
    ///////////////////////////////////////////jqGridFinalDiag///////////////////////////////////////////
    $("#jqGridFinalDiag").jqGrid({
        datatype: "local",
        editurl: "./icdprocedure_MR/form",
        colModel: [
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'seq', name: 'seq', width: 10, hidden: true, key: true },
            { label: 'ICD Code', name: 'icdcode', width: 50, editable: true },
            { label: 'Description', name: 'description', classes: 'wrap', width: 120, editable: false, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
            { label: 'Entered by', name: 'adduser', width: 50, hidden: true },
            { label: 'Date', name: 'adddate', width: 50, hidden: true },
        ],
        autowidth: true,
        multiSort: true,
        sortname: 'seq',
        sortorder: 'desc',
        viewrecords: true,
        loadonce: false,
        scroll: true,
        width: 900,
        height: 200,
        rowNum: 30,
        pager: "#jqGridPagerFinalDiag",
        loadComplete: function (){
            if(addmore_jqgrid.more == true){$('#jqGridFinalDiag_iladd').click();}
            else{
                $('#jqGrid2').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid.edit = addmore_jqgrid.more = false; // reset
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridFinalDiag_iledit").click();
        },
    });
    
    //////////////////////////////////////////////myEditOptions//////////////////////////////////////////////
    var myEditOptions_addFinalDiag = {
        keys: true,
        extraparam: {
            "_token": $("#_token").val()
        },
        oneditfunc: function (rowid){
            window.open('./icd11?callback_param=icdprocedure', '_blank');

            $("#jqGridPagerDelete_FinalDiag,#jqGridPagerRefresh_FinalDiag").hide();
            
            $("textarea[name='description']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridFinalDiag_ilsave').click();
                // addmore_jqgrid.state = true;
                // $('#jqGridFinalDiag_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // addmore_jqgrid.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridFinalDiag',urlParam_FinalDiag,'add_FinalDiag');
            errorField.length = 0;
            $("#jqGridPagerDelete_FinalDiag,#jqGridPagerRefresh_FinalDiag").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridFinalDiag',urlParam_FinalDiag,'add_FinalDiag');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            if(errorField.length > 0)return false;
            
            let data = $('#jqGridFinalDiag').jqGrid('getRowData', rowid);
            
            let editurl = "./icdprocedure_MR/form?"+
                $.param({
                    _token: $('#_token').val(),
                    mrn: $('#mrn_icdprocedure').val(),
                    episno: $('#episno_icdprocedure').val(),
                    action: 'finalDiag_save',
                });
            $("#jqGridFinalDiag").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_FinalDiag,#jqGridPagerRefresh_FinalDiag").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    ///////////////////////////////////////////jqGridPagerFinalDiag///////////////////////////////////////////
    $("#jqGridFinalDiag").inlineNav('#jqGridPagerFinalDiag', {
        add: true,
        edit: false,
        cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_addFinalDiag
        },
        // editParams: myEditOptions_editFinalDiag
    }).jqGrid('navButtonAdd', "#jqGridPagerFinalDiag", {
        id: "jqGridPagerDelete_FinalDiag",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridFinalDiag").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#_token").val(),
                        action: 'finalDiag_save',
                        seq: selrowData('#jqGridFinalDiag').seq,
                    }
                    
                    $.post("./icdprocedure_MR/form?"+$.param(param), {oper:'del'}, function (data){
                        
                    }).fail(function (data){
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridFinalDiag", urlParam_FinalDiag);
                    });
                }else{
                    $("#jqGridPagerDelete_FinalDiag,#jqGridPagerRefresh_FinalDiag").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerFinalDiag", {
        id: "jqGridPagerRefresh_FinalDiag",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridFinalDiag", urlParam_FinalDiag);
        },
    });
    /////////////////////////////////////////////////end grid/////////////////////////////////////////////////
    
    ///////////////////////////////////////////jqGridExternalCause///////////////////////////////////////////
    $("#jqGridExternalCause").jqGrid({
        datatype: "local",
        editurl: "./icdprocedure_MR/form",
        colModel: [
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'seq', name: 'seq', width: 10, hidden: true, key: true },
            { label: 'ICD Code', name: 'icdcode', width: 50, editable: true },
            { label: 'Description', name: 'description', classes: 'wrap', width: 120, editable: true, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
            { label: 'Entered by', name: 'adduser', width: 50, hidden: true },
            { label: 'Date', name: 'adddate', width: 50, hidden: true },
        ],
        autowidth: true,
        multiSort: true,
        sortname: 'seq',
        sortorder: 'desc',
        viewrecords: true,
        loadonce: false,
        scroll: true,
        width: 900,
        height: 200,
        rowNum: 30,
        pager: "#jqGridPagerExternalCause",
        loadComplete: function (){
            if(addmore_jqgrid.more == true){$('#jqGridExternalCause_iladd').click();}
            else{
                $('#jqGrid2').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid.edit = addmore_jqgrid.more = false; // reset
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridExternalCause_iledit").click();
        },
    });
    
    //////////////////////////////////////////////myEditOptions//////////////////////////////////////////////
    var myEditOptions_addExternalCause = {
        keys: true,
        extraparam: {
            "_token": $("#_token").val()
        },
        oneditfunc: function (rowid){
            $("#jqGridPagerDelete_ExternalCause,#jqGridPagerRefresh_ExternalCause").hide();
            
            $("textarea[name='description']").keydown(function (e){ // when click tab at last column in header, auto save
                var code = e.keyCode || e.which;
                if (code == '9')$('#jqGridExternalCause_ilsave').click();
                // addmore_jqgrid.state = true;
                // $('#jqGridExternalCause_ilsave').click();
            });
        },
        aftersavefunc: function (rowid, response, options){
            // addmore_jqgrid.more = true; // only addmore after save inline
            // state true maksudnyer ada isi, tak kosong
            refreshGrid('#jqGridExternalCause',urlParam_ExternalCause,'add_ExternalCause');
            errorField.length = 0;
            $("#jqGridPagerDelete_ExternalCause,#jqGridPagerRefresh_ExternalCause").show();
        },
        errorfunc: function (rowid,response){
            $('#p_error').text(response.responseText);
            refreshGrid('#jqGridExternalCause',urlParam_ExternalCause,'add_ExternalCause');
        },
        beforeSaveRow: function (options, rowid){
            $('#p_error').text('');
            if(errorField.length > 0)return false;
            
            let data = $('#jqGridExternalCause').jqGrid('getRowData', rowid);
            
            let editurl = "./icdprocedure_MR/form?"+
                $.param({
                    _token: $('#_token').val(),
                    mrn: $('#mrn_icdprocedure').val(),
                    episno: $('#episno_icdprocedure').val(),
                    action: 'externalCause_save',
                });
            $("#jqGridExternalCause").jqGrid('setGridParam', { editurl: editurl });
        },
        afterrestorefunc : function (response){
            $("#jqGridPagerDelete_ExternalCause,#jqGridPagerRefresh_ExternalCause").show();
        },
        errorTextFormat: function (data){
            alert(data);
        }
    };
    
    /////////////////////////////////////////jqGridPagerExternalCause/////////////////////////////////////////
    $("#jqGridExternalCause").inlineNav('#jqGridPagerExternalCause', {
        add: true,
        edit: false,
        cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_addExternalCause
        },
        // editParams: myEditOptions_editExternalCause
    }).jqGrid('navButtonAdd', "#jqGridPagerExternalCause", {
        id: "jqGridPagerDelete_ExternalCause",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridExternalCause").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                var result = confirm("Are you sure you want to delete this row?");
                if(result == true){
                    param = {
                        _token: $("#_token").val(),
                        action: 'externalCause_save',
                        seq: selrowData('#jqGridExternalCause').seq,
                    }
                    
                    $.post("./icdprocedure_MR/form?"+$.param(param), {oper:'del'}, function (data){
                        
                    }).fail(function (data){
                        //////////////////errorText(dialog,data.responseText);
                    }).done(function (data){
                        refreshGrid("#jqGridExternalCause", urlParam_ExternalCause);
                    });
                }else{
                    $("#jqGridPagerDelete_ExternalCause,#jqGridPagerRefresh_ExternalCause").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerExternalCause", {
        id: "jqGridPagerRefresh_ExternalCause",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridExternalCause", urlParam_ExternalCause);
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
            return {
                element: $(errorField[0]),
                message: ''
            }
        }
    },
};

function empty_icdprocedure(){
    // panel header
    $('#name_show_icdprocedure').text('');
    $('#mrn_show_icdprocedure').text('');
    $('#ic_show_icdprocedure').text('');
    $('#sex_show_icdprocedure').text('');
    $('#dob_show_icdprocedure').text('');
    $('#age_show_icdprocedure').text('');
    $('#race_show_icdprocedure').text('');
    $('#religion_show_icdprocedure').text('');
    $('#occupation_show_icdprocedure').text('');
    $('#citizenship_show_icdprocedure').text('');
    $('#area_show_icdprocedure').text('');
    
    // formIcdProcedure
    $('#mrn_icdprocedure').val('');
    $("#episno_icdprocedure").val('');
}

function populate_icdprocedure(obj){
    // panel header
    $('#name_show_icdprocedure').text(obj.Name);
    $('#mrn_show_icdprocedure').text(("0000000" + obj.MRN).slice(-7));
    $('#ic_show_icdprocedure').text(obj.Newic);
    $('#sex_show_icdprocedure').text(if_none(obj.Sex).toUpperCase());
    $('#dob_show_icdprocedure').text(dob_chg(obj.DOB));
    $('#age_show_icdprocedure').text(dob_age(obj.DOB)+' (YRS)');
    $('#race_show_icdprocedure').text(if_none(obj.raceDesc).toUpperCase());
    $('#religion_show_icdprocedure').text(if_none(obj.religion).toUpperCase());
    $('#occupation_show_icdprocedure').text(if_none(obj.OccupCode).toUpperCase());
    $('#citizenship_show_icdprocedure').text(if_none(obj.Citizencode).toUpperCase());
    $('#area_show_icdprocedure').text(if_none(obj.AreaCode).toUpperCase());
    
    // formIcdProcedure
    $('#mrn_icdprocedure').val(obj.MRN);
    $("#episno_icdprocedure").val(obj.Episno);
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

$('#tab_icdprocedure').on('shown.bs.collapse', function (){
    SmoothScrollTo('#tab_icdprocedure', 300, 114);
    $("#jqGridFinalDiag").jqGrid('setGridWidth', Math.floor($("#jqGridFinalDiag_c")[0].offsetWidth-$("#jqGridFinalDiag_c")[0].offsetLeft-14));
    $("#jqGridExternalCause").jqGrid('setGridWidth', Math.floor($("#jqGridExternalCause_c")[0].offsetWidth-$("#jqGridExternalCause_c")[0].offsetLeft-14));
    
    refreshGrid("#jqGridFinalDiag", urlParam_FinalDiag);
    refreshGrid("#jqGridExternalCause", urlParam_ExternalCause);
    
    $('#icdprocedure_panel_title').show();
    $('#icdprocedure_title').hide();
    $("#icdprocedure_div").css("top", "48px");
});

$('#tab_icdprocedure').on('hidden.bs.collapse', function (){
    $('#icdprocedure_panel_title').hide();
    $('#icdprocedure_title').show();
    $("#icdprocedure_div").css("top", "18px");
});

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

function icd11_callback(callback_param,icdobj){
    if(callback_param == 'icdprocedure'){
        $('input[type=text][name=icdcode]').val(icdobj.code+' - '+icdobj.desc);
    }
}
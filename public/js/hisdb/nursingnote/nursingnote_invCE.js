
var urlParam_CE = {
    action: 'get_table_default',
    url: 'util/get_table_default',
    field: '',
    table_name: 'nursing.nurs_investigation',
    table_id: 'idno',
    filterCol: ['mrn','episno','inv_code','inv_cat'],
    filterVal: ['','','',''],
};

$(document).ready(function(){
    
    var fdl = new faster_detail_load();
    
    // var addmore_jqgrid_CE = { more:false,state:false,edit:false }
    
    $("#jqGridInvChart_CE").jqGrid({
        datatype: "local",
        editurl: "nursingnote/form",
        colModel: [
            { label: 'inv_code', name: 'inv_code', width: 30, classes: 'wrap', hidden: true },
            { label: 'inv_cat', name: 'inv_cat', width: 30, classes: 'wrap', hidden: true },
            { label: 'Date', name: 'entereddate', width: 50, classes: 'wrap', editable: true, 
                formatter: "date", formatoptions: { srcformat: 'Y-m-d', newformat: 'd-m-Y' }, 
                editoptions: {
                    dataInit: function (element){
                        $(element).datepicker({
                            id: 'startdate_datePicker',
                            dateFormat: 'yy-mm-dd',
                            minDate: "dateToday",
                            showOn: 'focus',
                            changeMonth: true,
                            changeYear: true,
                            onSelect : function (){
                                $(this).focus();
                            }
                        });
                    }
                }
            },
            { label: 'Time', name: 'enteredtime', width: 50, classes: 'wrap', editable: true, 
                editrules: { required: false, custom: true, custom_func: cust_rules_CE }, edittype: 'custom', 
                editoptions: { 
                    custom_element: enteredtimeCustomEdit_CE, 
                    custom_value: galGridCustomValue_CE 
                }
            },
            { label: 'Value', name: 'values', width: 35, editable: true, editrules: { required: true }, 
                editoptions: { 
                    style: "text-transform: none;", 
                } 
            },
            { label: 'Entered By', name: 'enteredby', width: 35, editable: false },
            { label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
            { label: 'compcode', name: 'compcode', hidden: true },
            { label: 'mrn', name: 'mrn', hidden: true },
            { label: 'episno', name: 'episno', hidden: true },
            { label: 'adduser', name: 'adduser', hidden: true },
            { label: 'adddate', name: 'adddate', hidden: true },
            { label: 'upduser', name: 'upduser', hidden: true },
            { label: 'upddate', name: 'upddate', hidden: true },
            { label: 'computerid', name: 'computerid', hidden: true },
        ],
        autowidth: true,
        multiSort: true,
        sortname: 'idno',
        sortorder: 'desc',
        viewrecords: true,
        loadonce: false,
        width: 900,
        height: 200,
        rowNum: 30,
        pager: "#jqGridPagerInvChart_CE",
        loadComplete: function (){
            if(addmore_jqgrid_CE.more == true){$('#jqGridInvChart_CE_iladd').click();}
            else{
                $('#jqGridInvChart_CE').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid_CE.edit = addmore_jqgrid_CE.more = false; // reset
            
            // calc_jq_height_onchange("jqGridInvChart_CE");
            
            if($("#jqGridInvChart_CE").data('lastselrow') == undefined){
                $("#jqGridInvChart_CE").setSelection($("#jqGridInvChart_CE").getDataIDs()[0]);
            }else{
                $("#jqGridInvChart_CE").setSelection($("#jqGridInvChart_CE").data('lastselrow'));
                delay(function (){
                    $('#jqGridInvChart_CE tr#'+$("#jqGridInvChart_CE").data('lastselrow')).focus();
                }, 300);
            }
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridInvChart_CE_iledit").click();
        },
        gridComplete: function (){
            fdl.set_array().reset();
            if($('#jqGridPagerInvChart_CE').jqGrid('getGridParam', 'reccount') > 0){
                $("#jqGridPagerInvChart_CE").setSelection($("#jqGridPagerInvChart_CE").getDataIDs()[0]);
            }
        },
    });
    
    $("#jqGridInvChart_CE").inlineNav('#jqGridPagerInvChart_CE', {
        add: true, edit: true, cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_CE
        },
        editParams: myEditOptions_edit_CE,
    }).jqGrid('navButtonAdd', "#jqGridPagerInvChart_CE", {
        id: "jqGridPagerDeleteInvChart_CE",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridInvChart_CE").jqGrid('getGridParam', 'selrow');
            if(!selRowId){
                alert('Please select row');
            }else{
                if(confirm("Are you sure you want to delete this row?") == true){
                    let urlparam = {
                        action: 'save_grid_invChart',
                        oper: 'del',
                    };
                    
                    let urlobj = {
                        oper: 'del',
                        _token: $("#csrf_token").val(),
                        idno: selrowData('#jqGridInvChart_CE').idno
                    };
                    
                    $.post("./nursingnote/form?"+$.param(urlparam), urlobj, function (data){
                        
                    }).fail(function (data){
                        refreshGrid("#jqGridInvChart_CE", urlParam_CE);
                    }).done(function (data){
                        refreshGrid("#jqGridInvChart_CE", urlParam_CE);
                    });
                }else{
                    $("#jqGridPagerDelete,#jqGridPagerRefresh").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerInvChart_CE", {
        id: "jqGridPagerRefreshInvChart_CE",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridInvChart_CE", urlParam_CE);
        },
    });
    
});

var addmore_jqgrid_CE = { more:false,state:false,edit:false }

var myEditOptions_add_CE = {
    keys: true,
    extraparam: {
        "_token": $("#csrf_token").val()
    },
    oneditfunc: function (rowid){
        $("#jqGridPagerDeleteInvChart_CE,#jqGridPagerRefreshInvChart_CE").hide();
        
        $("#jqGridInvChart_CE input[name='values']").keydown(function (e){ // when click tab at last column in header, auto save
            var code = e.keyCode || e.which;
            if (code == '9')$('#jqGridInvChart_CE_ilsave').click();
            // addmore_jqgrid_CE.state = true;
            // $('#jqGridInvChart_CE_ilsave').click();
        });
    },
    aftersavefunc: function (rowid, response, options){
        // if(addmore_jqgrid_CE.state == true)addmore_jqgrid_CE.more = true; // only addmore after save inline
        addmore_jqgrid_CE.more = true; // state true maksudnyer ada isi, tak kosong
        refreshGrid('#jqGridInvChart_CE',urlParam_CE,'add');
        errorField.length = 0;
        $("#jqGridPagerDeleteInvChart_CE,#jqGridPagerRefreshInvChart_CE").show();
    },
    errorfunc: function (rowid,response){
        $('#p_error').text(response.responseText);
        refreshGrid('#jqGridInvChart_CE',urlParam_CE,'add');
    },
    beforeSaveRow: function (options, rowid){
        $('#p_error').text('');
        
        let data = $('#jqGridInvChart_CE').jqGrid ('getRowData', rowid);
        
        let editurl = "./nursingnote/form?"+
            $.param({
                mrn: $('#mrn_nursNote').val(),
                episno: $('#episno_nursNote').val(),
                inv_code: $('#inv_codeCE').val(),
                inv_cat: $('#inv_catCE').val(),
                action: 'save_grid_invChart',
            });
        $("#jqGridInvChart_CE").jqGrid('setGridParam', { editurl: editurl });
    },
    afterrestorefunc : function (response){
        $("#jqGridPagerDeleteInvChart_CE,#jqGridPagerRefreshInvChart_CE").show();
    },
    errorTextFormat: function (data){
        alert(data);
    }
};

var myEditOptions_edit_CE = {
    keys: true,
    extraparam: {
        "_token": $("#csrf_token").val()
    },
    oneditfunc: function (rowid){
        $("#jqGridPagerDeleteInvChart_CE,#jqGridPagerRefreshInvChart_CE").hide();
        
        $("#jqGridInvChart_CE input[name='values']").keydown(function (e){ // when click tab at last column in header, auto save
            var code = e.keyCode || e.which;
            if (code == '9')$('#jqGridInvChart_CE_ilsave').click();
            // addmore_jqgrid_CE.state = true;
            // $('#jqGridInvChart_CE_ilsave').click();
        });
    },
    aftersavefunc: function (rowid, response, options){
        if(addmore_jqgrid_CE.state == true)addmore_jqgrid_CE.more = true; // only addmore after save inline
        // state true maksudnyer ada isi, tak kosong
        refreshGrid('#jqGridInvChart_CE',urlParam_CE,'edit');
        errorField.length = 0;
        $("#jqGridPagerDeleteInvChart_CE,#jqGridPagerRefreshInvChart_CE").show();
    },
    errorfunc: function (rowid,response){
        $('#p_error').text(response.responseText);
        refreshGrid('#jqGridInvChart_CE',urlParam_CE,'edit');
    },
    beforeSaveRow: function (options, rowid){
        $('#p_error').text('');
        // if(errorField.length > 0){console.log(errorField);return false;}
        
        let data = $('#jqGridInvChart_CE').jqGrid ('getRowData', rowid);
        // console.log(data);
        
        let editurl = "./nursingnote/form?"+
            $.param({
                mrn: $('#mrn_nursNote').val(),
                episno: $('#episno_nursNote').val(),
                inv_code: $('#inv_codeCE').val(),
                inv_cat: $('#inv_catCE').val(),
                action: 'save_grid_invChart',
                _token: $("#csrf_token").val()
            });
        $("#jqGridInvChart_CE").jqGrid('setGridParam', { editurl: editurl });
    },
    afterrestorefunc : function (response){
        $("#jqGridPagerDeleteInvChart_CE,#jqGridPagerRefreshInvChart_CE").show();
    },
    errorTextFormat: function (data){
        alert(data);
    }
};

function enteredtimeCustomEdit_CE(val,opt,rowObject){
    return $(`<div class="input-group"><input autocomplete="off" name="CE_time" type="time" class="form-control input-sm" style="text-transform: uppercase;" value="`+val+`" style="z-index: 0"></div>`);
}

function galGridCustomValue_CE(elem, operation, value){
    if(operation == 'get'){
        return $(elem).find("input").val();
    } 
    else if(operation == 'set'){
        $('input',elem).val(value);
    }
}

function cust_rules_CE(value, name){
    var temp = null;
    switch(name){
        case 'CE_time': temp = $("#jqGridInvChart_CE input[name='enteredtime']"); break;
    }
    if(temp == null) return [true,''];
    return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
}
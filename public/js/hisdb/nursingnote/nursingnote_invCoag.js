
var urlParam_Coag = {
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
    
    // var addmore_jqgrid_Coag = { more:false,state:false,edit:false }
    
    $("#jqGridInvChart_Coag").jqGrid({
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
                            dateFormat: 'dd-mm-yy',
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
                editrules: { required: false, custom: true, custom_func: cust_rules_Coag }, edittype: 'custom', 
                editoptions: { 
                    custom_element: enteredtimeCustomEdit_Coag, 
                    custom_value: galGridCustomValue_Coag 
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
        pager: "#jqGridPagerInvChart_Coag",
        loadComplete: function (){
            if(addmore_jqgrid_Coag.more == true){$('#jqGridInvChart_Coag_iladd').click();}
            else{
                $('#jqGridInvChart_Coag').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid_Coag.edit = addmore_jqgrid_Coag.more = false; // reset
            
            // calc_jq_height_onchange("jqGridInvChart_Coag");
            
            if($("#jqGridInvChart_Coag").data('lastselrow') == undefined){
                $("#jqGridInvChart_Coag").setSelection($("#jqGridInvChart_Coag").getDataIDs()[0]);
            }else{
                $("#jqGridInvChart_Coag").setSelection($("#jqGridInvChart_Coag").data('lastselrow'));
                delay(function (){
                    $('#jqGridInvChart_Coag tr#'+$("#jqGridInvChart_Coag").data('lastselrow')).focus();
                }, 300);
            }
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridInvChart_Coag_iledit").click();
        },
        gridComplete: function (){
            fdl.set_array().reset();
            if($('#jqGridPagerInvChart_Coag').jqGrid('getGridParam', 'reccount') > 0){
                $("#jqGridPagerInvChart_Coag").setSelection($("#jqGridPagerInvChart_Coag").getDataIDs()[0]);
            }
        },
    });
    
    $("#jqGridInvChart_Coag").inlineNav('#jqGridPagerInvChart_Coag', {
        add: true, edit: true, cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_Coag
        },
        editParams: myEditOptions_edit_Coag,
    }).jqGrid('navButtonAdd', "#jqGridPagerInvChart_Coag", {
        id: "jqGridPagerDeleteInvChart_Coag",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridInvChart_Coag").jqGrid('getGridParam', 'selrow');
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
                        idno: selrowData('#jqGridInvChart_Coag').idno
                    };
                    
                    $.post("./nursingnote/form?"+$.param(urlparam), urlobj, function (data){
                        
                    }).fail(function (data){
                        refreshGrid("#jqGridInvChart_Coag", urlParam_Coag);
                    }).done(function (data){
                        refreshGrid("#jqGridInvChart_Coag", urlParam_Coag);
                    });
                }else{
                    $("#jqGridPagerDelete,#jqGridPagerRefresh").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerInvChart_Coag", {
        id: "jqGridPagerRefreshInvChart_Coag",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridInvChart_Coag", urlParam_Coag);
        },
    });
    
});

var addmore_jqgrid_Coag = { more:false,state:false,edit:false }

var myEditOptions_add_Coag = {
    keys: true,
    extraparam: {
        "_token": $("#csrf_token").val()
    },
    oneditfunc: function (rowid){
        $("#jqGridPagerDeleteInvChart_Coag,#jqGridPagerRefreshInvChart_Coag").hide();
        
        $("#jqGridInvChart_Coag input[name='values']").keydown(function (e){ // when click tab at last column in header, auto save
            var code = e.keyCode || e.which;
            if (code == '9')$('#jqGridInvChart_Coag_ilsave').click();
            // addmore_jqgrid_Coag.state = true;
            // $('#jqGridInvChart_Coag_ilsave').click();
        });
    },
    aftersavefunc: function (rowid, response, options){
        // if(addmore_jqgrid_Coag.state == true)addmore_jqgrid_Coag.more = true; // only addmore after save inline
        addmore_jqgrid_Coag.more = true; // state true maksudnyer ada isi, tak kosong
        refreshGrid('#jqGridInvChart_Coag',urlParam_Coag,'add');
        errorField.length = 0;
        $("#jqGridPagerDeleteInvChart_Coag,#jqGridPagerRefreshInvChart_Coag").show();
    },
    errorfunc: function (rowid,response){
        $('#p_error').text(response.responseText);
        refreshGrid('#jqGridInvChart_Coag',urlParam_Coag,'add');
    },
    beforeSaveRow: function (options, rowid){
        $('#p_error').text('');
        
        let data = $('#jqGridInvChart_Coag').jqGrid ('getRowData', rowid);
        
        let editurl = "./nursingnote/form?"+
            $.param({
                mrn: $('#mrn_nursNote').val(),
                episno: $('#episno_nursNote').val(),
                inv_code: $('#inv_codeCoag').val(),
                inv_cat: $('#inv_catCoag').val(),
                action: 'save_grid_invChart',
            });
        $("#jqGridInvChart_Coag").jqGrid('setGridParam', { editurl: editurl });
    },
    afterrestorefunc : function (response){
        $("#jqGridPagerDeleteInvChart_Coag,#jqGridPagerRefreshInvChart_Coag").show();
    },
    errorTextFormat: function (data){
        alert(data);
    }
};

var myEditOptions_edit_Coag = {
    keys: true,
    extraparam: {
        "_token": $("#csrf_token").val()
    },
    oneditfunc: function (rowid){
        $("#jqGridPagerDeleteInvChart_Coag,#jqGridPagerRefreshInvChart_Coag").hide();
        
        $("#jqGridInvChart_Coag input[name='values']").keydown(function (e){ // when click tab at last column in header, auto save
            var code = e.keyCode || e.which;
            if (code == '9')$('#jqGridInvChart_Coag_ilsave').click();
            // addmore_jqgrid_Coag.state = true;
            // $('#jqGridInvChart_Coag_ilsave').click();
        });
    },
    aftersavefunc: function (rowid, response, options){
        if(addmore_jqgrid_Coag.state == true)addmore_jqgrid_Coag.more = true; // only addmore after save inline
        // state true maksudnyer ada isi, tak kosong
        refreshGrid('#jqGridInvChart_Coag',urlParam_Coag,'edit');
        errorField.length = 0;
        $("#jqGridPagerDeleteInvChart_Coag,#jqGridPagerRefreshInvChart_Coag").show();
    },
    errorfunc: function (rowid,response){
        $('#p_error').text(response.responseText);
        refreshGrid('#jqGridInvChart_Coag',urlParam_Coag,'edit');
    },
    beforeSaveRow: function (options, rowid){
        $('#p_error').text('');
        // if(errorField.length > 0){console.log(errorField);return false;}
        
        let data = $('#jqGridInvChart_Coag').jqGrid ('getRowData', rowid);
        // console.log(data);
        
        let editurl = "./nursingnote/form?"+
            $.param({
                mrn: $('#mrn_nursNote').val(),
                episno: $('#episno_nursNote').val(),
                inv_code: $('#inv_codeCoag').val(),
                inv_cat: $('#inv_catCoag').val(),
                action: 'save_grid_invChart',
                _token: $("#csrf_token").val()
            });
        $("#jqGridInvChart_Coag").jqGrid('setGridParam', { editurl: editurl });
    },
    afterrestorefunc : function (response){
        $("#jqGridPagerDeleteInvChart_Coag,#jqGridPagerRefreshInvChart_Coag").show();
    },
    errorTextFormat: function (data){
        alert(data);
    }
};

function enteredtimeCustomEdit_Coag(val,opt,rowObject){
    return $(`<div class="input-group"><input autocomplete="off" name="Coag_time" type="time" class="form-control input-sm" style="text-transform: uppercase;" value="`+val+`" style="z-index: 0"></div>`);
}

function galGridCustomValue_Coag(elem, operation, value){
    if(operation == 'get'){
        return $(elem).find("input").val();
    } 
    else if(operation == 'set'){
        $('input',elem).val(value);
    }
}

function cust_rules_Coag(value, name){
    var temp = null;
    switch(name){
        case 'Coag_time': temp = $("#jqGridInvChart_Coag input[name='enteredtime']"); break;
    }
    if(temp == null) return [true,''];
    return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
}
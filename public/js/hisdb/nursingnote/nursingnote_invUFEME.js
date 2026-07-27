
var urlParam_UFEME = {
    action: 'get_table_default',
    url: 'util/get_table_default',
    field: '',
    table_name: 'nursing.nurs_investigation',
    table_id: 'idno',
    filterCol: ['mrn','episno','inv_code','inv_cat'],
    filterVal: ['','','',''],
};

/////////////////////////////parameter for jqGridAddNotesInvChartUFEME url/////////////////////////////
var urlParam_AddNotesInvChartUFEME = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','INVCHART_UFEME'],
}

$(document).ready(function(){
    
    var fdl = new faster_detail_load();
    
    // var addmore_jqgrid_UFEME = { more:false,state:false,edit:false }
    
    $("#jqGridInvChart_UFEME").jqGrid({
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
                editrules: { required: false, custom: true, custom_func: cust_rules_UFEME }, edittype: 'custom', 
                editoptions: { 
                    custom_element: enteredtimeCustomEdit_UFEME, 
                    custom_value: galGridCustomValue_UFEME 
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
        pager: "#jqGridPagerInvChart_UFEME",
        loadComplete: function (){
            if(addmore_jqgrid_UFEME.more == true){$('#jqGridInvChart_UFEME_iladd').click();}
            else{
                $('#jqGridInvChart_UFEME').jqGrid ('setSelection', "1");
            }
            $('.ui-pg-button').prop('disabled',true);
            addmore_jqgrid_UFEME.edit = addmore_jqgrid_UFEME.more = false; // reset
            
            // calc_jq_height_onchange("jqGridInvChart_UFEME");
            
            if($("#jqGridInvChart_UFEME").data('lastselrow') == undefined){
                $("#jqGridInvChart_UFEME").setSelection($("#jqGridInvChart_UFEME").getDataIDs()[0]);
            }else{
                $("#jqGridInvChart_UFEME").setSelection($("#jqGridInvChart_UFEME").data('lastselrow'));
                delay(function (){
                    $('#jqGridInvChart_UFEME tr#'+$("#jqGridInvChart_UFEME").data('lastselrow')).focus();
                }, 300);
            }
        },
        ondblClickRow: function (rowid, iRow, iCol, e){
            $("#jqGridInvChart_UFEME_iledit").click();
        },
        gridComplete: function (){
            fdl.set_array().reset();
            if($('#jqGridPagerInvChart_UFEME').jqGrid('getGridParam', 'reccount') > 0){
                $("#jqGridPagerInvChart_UFEME").setSelection($("#jqGridPagerInvChart_UFEME").getDataIDs()[0]);
            }
        },
    });
    
    $("#jqGridInvChart_UFEME").inlineNav('#jqGridPagerInvChart_UFEME', {
        add: true, edit: true, cancel: true,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
        addParams: {
            addRowParams: myEditOptions_add_UFEME
        },
        editParams: myEditOptions_edit_UFEME,
    }).jqGrid('navButtonAdd', "#jqGridPagerInvChart_UFEME", {
        id: "jqGridPagerDeleteInvChart_UFEME",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-trash",
        title: "Delete Selected Row",
        onClickButton: function (){
            selRowId = $("#jqGridInvChart_UFEME").jqGrid('getGridParam', 'selrow');
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
                        idno: selrowData('#jqGridInvChart_UFEME').idno
                    };
                    
                    $.post("./nursingnote/form?"+$.param(urlparam), urlobj, function (data){
                        
                    }).fail(function (data){
                        refreshGrid("#jqGridInvChart_UFEME", urlParam_UFEME);
                    }).done(function (data){
                        refreshGrid("#jqGridInvChart_UFEME", urlParam_UFEME);
                    });
                }else{
                    $("#jqGridPagerDelete,#jqGridPagerRefresh").show();
                }
            }
        },
    }).jqGrid('navButtonAdd', "#jqGridPagerInvChart_UFEME", {
        id: "jqGridPagerRefreshInvChart_UFEME",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridInvChart_UFEME", urlParam_UFEME);
        },
    });
    
    //////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridInvChartUFEME = {more:false,state:false,edit:false}

	///////////////////////////////////////jqGridAddNotesInvChartUFEME///////////////////////////////////////
	$("#jqGridAddNotesInvChartUFEME").jqGrid({
		datatype: "local",
		editurl: "./nursingnote/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'mrn', name: 'mrn', hidden: true },
			{ label: 'episno', name: 'episno', hidden: true },
			{ label: 'id', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'type', name: 'type', hidden: true },
			{ label: 'Note', name: 'note', classes: 'wrap', width: 100, editable: true, edittype: "textarea", editoptions: { style: "width: -webkit-fill-available;", rows: 5 } },
			{ label: 'Entered by', name: 'adduser', width: 50, hidden: false },
			{ label: 'Date', name: 'adddate', width: 50, hidden: false },
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
		pager: "#jqGridPagerAddNotesInvChartUFEME",
		loadComplete: function (){
			if(addmore_jqgridInvChartUFEME.more == true){$('#jqGridAddNotesInvChartUFEME_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridInvChartUFEME.edit = addmore_jqgridInvChartUFEME.more = false; // reset
			
			// calc_jq_height_onchange("jqGridAddNotesInvChartUFEME");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesInvChartUFEME_iledit").click();
		},
	});
	
	/////////////////////////////////myEditOptions/////////////////////////////////
	var myEditOptions_addInvChartUFEME = {
		keys: true,
		extraparam: {
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGridPagerDelete_addnotesInvChartUFEME,#jqGridPagerRefresh_addnoteInvChartUFEME").hide();
			
			$("textarea[name='note']").keydown(function (e){ // when click tab at last column in header, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGridAddNotesInvChartUFEME_ilsave').click();
				// addmore_jqgridInvChartUFEME.state = true;
				// $('#jqGrid_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options){
			// addmore_jqgridInvChartUFEME.more = true; // only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGridAddNotesInvChartUFEME',urlParam_AddNotesInvChartUFEME,'add_notesInvChartUFEME');
			errorField.length = 0;
			$("#jqGridPagerDelete_addnotesInvChartUFEME,#jqGridPagerRefresh_addnoteInvChartUFEME").show();
		},
		errorfunc: function (rowid,response){
			$('#p_error').text(response.responseText);
			refreshGrid('#jqGridAddNotesInvChartUFEME',urlParam_AddNotesInvChartUFEME,'add_notesInvChartUFEME');
		},
		beforeSaveRow: function (options, rowid){
			$('#p_error').text('');
			
			let data = $('#jqGridAddNotesInvChartUFEME').jqGrid ('getRowData', rowid);
			
			let editurl = "./nursingnote/form?"+
				$.param({
					episno: $('#episno_nursNote').val(),
					mrn: $('#mrn_nursNote').val(),
					action: 'addNotesInvChartUFEME_save',
				});
			$("#jqGridAddNotesInvChartUFEME").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			$("#jqGridPagerDelete_addnotesInvChartUFEME,#jqGridPagerRefresh_addnoteInvChartUFEME").show();
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	/////////////////////////////////////jqGridPagerAddNotesInvChartUFEME/////////////////////////////////////
	$("#jqGridAddNotesInvChartUFEME").inlineNav('#jqGridPagerAddNotesInvChartUFEME', {
		add: true, edit: false, cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_addInvChartUFEME
		},
		// editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesInvChartUFEME", {
		id: "jqGridPagerRefresh_addnoteInvChartUFEME",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesInvChartUFEME", urlParam_AddNotesInvChartUFEME);
		},
	});
	//////////////////////////////////////////////end grid//////////////////////////////////////////////
	
    
});

var addmore_jqgrid_UFEME = { more:false,state:false,edit:false }

var myEditOptions_add_UFEME = {
    keys: true,
    extraparam: {
        "_token": $("#csrf_token").val()
    },
    oneditfunc: function (rowid){
        $("#jqGridPagerDeleteInvChart_UFEME,#jqGridPagerRefreshInvChart_UFEME").hide();
        
        $("#jqGridInvChart_UFEME input[name='values']").keydown(function (e){ // when click tab at last column in header, auto save
            var code = e.keyCode || e.which;
            if (code == '9')$('#jqGridInvChart_UFEME_ilsave').click();
            // addmore_jqgrid_UFEME.state = true;
            // $('#jqGridInvChart_UFEME_ilsave').click();
        });
    },
    aftersavefunc: function (rowid, response, options){
        // if(addmore_jqgrid_UFEME.state == true)addmore_jqgrid_UFEME.more = true; // only addmore after save inline
        addmore_jqgrid_UFEME.more = true; // state true maksudnyer ada isi, tak kosong
        refreshGrid('#jqGridInvChart_UFEME',urlParam_UFEME,'add');
        errorField.length = 0;
        $("#jqGridPagerDeleteInvChart_UFEME,#jqGridPagerRefreshInvChart_UFEME").show();
    },
    errorfunc: function (rowid,response){
        $('#p_error').text(response.responseText);
        refreshGrid('#jqGridInvChart_UFEME',urlParam_UFEME,'add');
    },
    beforeSaveRow: function (options, rowid){
        $('#p_error').text('');
        
        let data = $('#jqGridInvChart_UFEME').jqGrid ('getRowData', rowid);
        
        let editurl = "./nursingnote/form?"+
            $.param({
                mrn: $('#mrn_nursNote').val(),
                episno: $('#episno_nursNote').val(),
                inv_code: $('#inv_codeUFEME').val(),
                inv_cat: $('#inv_catUFEME').val(),
                action: 'save_grid_invChart',
            });
        $("#jqGridInvChart_UFEME").jqGrid('setGridParam', { editurl: editurl });
    },
    afterrestorefunc : function (response){
        $("#jqGridPagerDeleteInvChart_UFEME,#jqGridPagerRefreshInvChart_UFEME").show();
    },
    errorTextFormat: function (data){
        alert(data);
    }
};

var myEditOptions_edit_UFEME = {
    keys: true,
    extraparam: {
        "_token": $("#csrf_token").val()
    },
    oneditfunc: function (rowid){
        $("#jqGridPagerDeleteInvChart_UFEME,#jqGridPagerRefreshInvChart_UFEME").hide();
        
        $("#jqGridInvChart_UFEME input[name='values']").keydown(function (e){ // when click tab at last column in header, auto save
            var code = e.keyCode || e.which;
            if (code == '9')$('#jqGridInvChart_UFEME_ilsave').click();
            // addmore_jqgrid_UFEME.state = true;
            // $('#jqGridInvChart_UFEME_ilsave').click();
        });
    },
    aftersavefunc: function (rowid, response, options){
        if(addmore_jqgrid_UFEME.state == true)addmore_jqgrid_UFEME.more = true; // only addmore after save inline
        // state true maksudnyer ada isi, tak kosong
        refreshGrid('#jqGridInvChart_UFEME',urlParam_UFEME,'edit');
        errorField.length = 0;
        $("#jqGridPagerDeleteInvChart_UFEME,#jqGridPagerRefreshInvChart_UFEME").show();
    },
    errorfunc: function (rowid,response){
        $('#p_error').text(response.responseText);
        refreshGrid('#jqGridInvChart_UFEME',urlParam_UFEME,'edit');
    },
    beforeSaveRow: function (options, rowid){
        $('#p_error').text('');
        // if(errorField.length > 0){console.log(errorField);return false;}
        
        let data = $('#jqGridInvChart_UFEME').jqGrid ('getRowData', rowid);
        // console.log(data);
        
        let editurl = "./nursingnote/form?"+
            $.param({
                mrn: $('#mrn_nursNote').val(),
                episno: $('#episno_nursNote').val(),
                inv_code: $('#inv_codeUFEME').val(),
                inv_cat: $('#inv_catUFEME').val(),
                action: 'save_grid_invChart',
                _token: $("#csrf_token").val()
            });
        $("#jqGridInvChart_UFEME").jqGrid('setGridParam', { editurl: editurl });
    },
    afterrestorefunc : function (response){
        $("#jqGridPagerDeleteInvChart_UFEME,#jqGridPagerRefreshInvChart_UFEME").show();
    },
    errorTextFormat: function (data){
        alert(data);
    }
};

function enteredtimeCustomEdit_UFEME(val,opt,rowObject){
    return $(`<div class="input-group"><input autocomplete="off" name="UFEME_time" type="time" class="form-control input-sm" style="text-transform: uppercase;" value="`+val+`" style="z-index: 0"></div>`);
}

function galGridCustomValue_UFEME(elem, operation, value){
    if(operation == 'get'){
        return $(elem).find("input").val();
    } 
    else if(operation == 'set'){
        $('input',elem).val(value);
    }
}

function cust_rules_UFEME(value, name){
    var temp = null;
    switch(name){
        case 'UFEME_time': temp = $("#jqGridInvChart_UFEME input[name='enteredtime']"); break;
    }
    if(temp == null) return [true,''];
    return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
}

var urlParam_CE = {
    action: 'get_table_default',
    url: 'util/get_table_default',
    field: '',
    table_name: 'nursing.nurs_investigation',
    table_id: 'idno',
    filterCol: ['mrn','episno','inv_code','inv_cat'],
    filterVal: ['','','',''],
};

/////////////////////////////parameter for jqGridAddNotesInvChartCE url/////////////////////////////
var urlParam_AddNotesInvChartCE = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','INVCHART_CE'],
}

$(document).ready(function(){
    
    var fdl = new faster_detail_load();
    
    // var addmore_jqgrid_CE = { more:false,state:false,edit:false }
    
    $("#jqGridInvChart_CE").jqGrid({
        datatype: "local",
        editurl: "./nursingnote_MR/form",
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
        add: false, edit: false, cancel: false, save: false,
        // to prevent the row being edited/added from being automatically cancelled once the user clicks another row
        restoreAfterSelect: false,
    }).jqGrid('navButtonAdd', "#jqGridPagerInvChart_CE", {
        id: "jqGridPagerRefreshInvChart_CE",
        caption: "", cursor: "pointer", position: "last",
        buttonicon: "glyphicon glyphicon-refresh",
        title: "Refresh Table",
        onClickButton: function (){
            refreshGrid("#jqGridInvChart_CE", urlParam_CE);
        },
    });

    //////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridInvChartCE = {more:false,state:false,edit:false}

	///////////////////////////////////////jqGridAddNotesInvChartCE///////////////////////////////////////
	$("#jqGridAddNotesInvChartCE").jqGrid({
		datatype: "local",
		editurl: "./nursingnote_MR/form",
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
		pager: "#jqGridPagerAddNotesInvChartCE",
		loadComplete: function (){
			if(addmore_jqgridInvChartCE.more == true){$('#jqGridAddNotesInvChartCE_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridInvChartCE.edit = addmore_jqgridInvChartCE.more = false; // reset
			
			// calc_jq_height_onchange("jqGridAddNotesInvChartCE");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesInvChartCE_iledit").click();
		},
	});
	
	/////////////////////////////////////jqGridPagerAddNotesInvChartCE/////////////////////////////////////
	$("#jqGridAddNotesInvChartCE").inlineNav('#jqGridPagerAddNotesInvChartCE', {
		add: false, edit: false, cancel: false, save: false,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesInvChartCE", {
		id: "jqGridPagerRefresh_addnoteInvChartCE",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesInvChartCE", urlParam_AddNotesInvChartCE);
		},
	});
	//////////////////////////////////////////////end grid//////////////////////////////////////////////
	
});

var addmore_jqgrid_CE = { more:false,state:false,edit:false }

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
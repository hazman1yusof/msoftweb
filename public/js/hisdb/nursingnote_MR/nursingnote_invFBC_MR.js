
var urlParam_FBC = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nurs_investigation',
	table_id: 'idno',
	filterCol: ['mrn','episno','inv_code','inv_cat'],
	filterVal: ['','','',''],
};

/////////////////////////////parameter for jqGridAddNotesInvChartFBC url/////////////////////////////
var urlParam_AddNotesInvChartFBC = {
	action: 'get_table_default',
	url: 'util/get_table_default',
	field: '',
	table_name: 'nursing.nursaddnote',
	table_id: 'idno',
	filterCol: ['mrn','episno','type'],
	filterVal: ['','','INVCHART_FBC'],
}

$(document).ready(function(){
	
	var fdl = new faster_detail_load();
	
	// var addmore_jqgrid_FBC = { more:false,state:false,edit:false }
	
	$("#jqGridInvChart_FBC").jqGrid({
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
				editrules: { required: false, custom: true, custom_func: cust_rules_FBC }, edittype: 'custom', 
				editoptions: { 
					custom_element: enteredtimeCustomEdit_FBC, 
					custom_value: galGridCustomValue_FBC 
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
		pager: "#jqGridPagerInvChart_FBC",
		loadComplete: function (){
			if(addmore_jqgrid_FBC.more == true){$('#jqGridInvChart_FBC_iladd').click();}
			else{
				$('#jqGridInvChart_FBC').jqGrid ('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgrid_FBC.edit = addmore_jqgrid_FBC.more = false; // reset
			
			// calc_jq_height_onchange("jqGridInvChart_FBC");
			
			if($("#jqGridInvChart_FBC").data('lastselrow') == undefined){
				$("#jqGridInvChart_FBC").setSelection($("#jqGridInvChart_FBC").getDataIDs()[0]);
			}else{
				$("#jqGridInvChart_FBC").setSelection($("#jqGridInvChart_FBC").data('lastselrow'));
				delay(function (){
					$('#jqGridInvChart_FBC tr#'+$("#jqGridInvChart_FBC").data('lastselrow')).focus();
				}, 300);
			}
		},
		ondblClickRow: function (rowid, iRow, iCol, e){
			$("#jqGridInvChart_FBC_iledit").click();
		},
		gridComplete: function (){
			fdl.set_array().reset();
			if($('#jqGridPagerInvChart_FBC').jqGrid('getGridParam', 'reccount') > 0){
				$("#jqGridPagerInvChart_FBC").setSelection($("#jqGridPagerInvChart_FBC").getDataIDs()[0]);
			}
		},
	});
	
	$("#jqGridInvChart_FBC").inlineNav('#jqGridPagerInvChart_FBC', {
		add: false, edit: false, cancel: false, save: false,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
	}).jqGrid('navButtonAdd', "#jqGridPagerInvChart_FBC", {
		id: "jqGridPagerRefreshInvChart_FBC",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridInvChart_FBC", urlParam_FBC);
		},
	});

	//////////////////////////////////////parameter for saving url//////////////////////////////////////
	var addmore_jqgridInvChartFBC = {more:false,state:false,edit:false}

	///////////////////////////////////////jqGridAddNotesInvChartFBC///////////////////////////////////////
	$("#jqGridAddNotesInvChartFBC").jqGrid({
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
		pager: "#jqGridPagerAddNotesInvChartFBC",
		loadComplete: function (){
			if(addmore_jqgridInvChartFBC.more == true){$('#jqGridAddNotesInvChartFBC_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			$('.ui-pg-button').prop('disabled',true);
			addmore_jqgridInvChartFBC.edit = addmore_jqgridInvChartFBC.more = false; // reset
			
			// calc_jq_height_onchange("jqGridAddNotesInvChartFBC");
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridAddNotesInvChartFBC_iledit").click();
		},
	});
	
	/////////////////////////////////////jqGridPagerAddNotesInvChartFBC/////////////////////////////////////
	$("#jqGridAddNotesInvChartFBC").inlineNav('#jqGridPagerAddNotesInvChartFBC', {
		add: false, edit: false, cancel: false, save: false,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
	}).jqGrid('navButtonAdd', "#jqGridPagerAddNotesInvChartFBC", {
		id: "jqGridPagerRefresh_addnoteInvChartFBC",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			refreshGrid("#jqGridAddNotesInvChartFBC", urlParam_AddNotesInvChartFBC);
		},
	});
	//////////////////////////////////////////////end grid//////////////////////////////////////////////
	
});

var addmore_jqgrid_FBC = { more:false,state:false,edit:false }

function enteredtimeCustomEdit_FBC(val,opt,rowObject){
	return $(`<div class="input-group"><input autocomplete="off" name="FBC_time" type="time" class="form-control input-sm" style="text-transform: uppercase;" value="`+val+`" style="z-index: 0"></div>`);
}

function galGridCustomValue_FBC(elem, operation, value){
	if(operation == 'get'){
		return $(elem).find("input").val();
	} 
	else if(operation == 'set'){
		$('input',elem).val(value);
	}
}

function cust_rules_FBC(value, name){
	var temp = null;
	switch(name){
		case 'FBC_time': temp = $("#jqGridInvChart_FBC input[name='enteredtime']"); break;
	}
	if(temp == null) return [true,''];
	return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
}
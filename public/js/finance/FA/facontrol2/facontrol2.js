$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function () {

	$("body").show();
	check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']");
	/////////////////////////validation//////////////////////////
	$.validate({
		language: {
			requiredFields: ''
		},
	});

	var errorField = [];
	conf = {
		onValidate: function ($form) {
			if (errorField.length > 0) {
				return {
					element: $('#' + errorField[0]),
					message: ' '
				}
			}
		},
	};
	//////////////////////////////////////////////////////////////


	////////////////////object for dialog handler//////////////////
	

	////////////////////////////////////start dialog///////////////////////////////////////
	// var dialog_compcode= new ordialog(
	// 	'compcode',['finance.facontrol as fac','sysdb.company as com'],'#compcode',errorField,
	// 	{	colModel:[
	// 		  	{label: 'compcode', name: 'fac_compcode', width:200, classes:'pointer', canSearch:true, checked:true, or_search:true},
	// 			{label: 'Name', name:'com_name', width:300, classes:'pointer', canSearch:true,or_search:true},
	// 			{label: 'Year', name: 'fac_year', width: 30, hidden: false ,canSearch: true, checked: true},
	// 			{label: 'Period', name: 'fac_period', width: 30, hidden: false,canSearch: true },
	// 		],
	// 		ondblClickRow:function(){
	// 			let data=selrowData('#'+dialog_compcode.gridname);
	// 			$('#compcode').val(data['fac_compcode']);		
	// 			$('#name').val(data['com_name']);
	// 			// $('#year').val(data['fac_year']);
	// 			// $('#period').val(data['fac_period']);
	// 		},
	// 		gridComplete: function(obj){
	// 			var gridname = '#'+obj.gridname;
	// 			if($(gridname).jqGrid('getDataIDs').length == 1){
	// 				$(gridname+' tr#1').click();
	// 				$(gridname+' tr#1').dblclick();
	// 				$('#itemcode').focus();
	// 			}
	// 		}
	// 	},
	// 	{
	// 		title:"Select compcode",
	// 		open: function(){
	// 			dialog_compcode.urlParam.filterCol=['fac.compcode','com.name'];
	// 			dialog_compcode.urlParam.filterVal=['session.compcode','<>.NULL', $("#name").val()];
	// 			dialog_compcode.urlParam.fixPost = "true";
	// 			dialog_compcode.urlParam.table_id = "none_";
	// 			dialog_compcode.urlParam.join_type = ['LEFT JOIN'];
	// 			dialog_compcode.urlParam.join_onCol = ['fac.compcode'];
	// 			dialog_compcode.urlParam.join_onVal = ['com.name'];
	// 		}
	// 	},'urlParam','radio','tab'
	// );
	// dialog_compcode.makedialog();
	
	var butt1 = [{
		text: "Save", click: function () {
			if ($('#formdata').isValid({ requiredFields: '' }, conf, true)) {
				saveFormdata("#jqGrid", "#dialogForm", "#formdata", oper, saveParam, urlParam);
			}
		}
	}, {
		text: "Cancel", click: function () {
			$(this).dialog('close');
		}
	}];

	var butt2 = [{
		text: "Close", click: function () {
			$(this).dialog('close');
		}
	}];

	var oper;
	$("#dialogForm")
		.dialog({
			width: 5 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				switch (oper) {
					case state = 'add':
						$(this).dialog("option", "title", "Add");
						enableForm('#formdata');
						rdonly("#formdata");
						rdonly("#dialogForm");
						hideOne("#formdata");
						break;
					case state = 'edit':
						$(this).dialog("option", "title", "Edit");
						enableForm('#formdata');
						frozeOnEdit("#dialogForm");
						rdonly("#formdata");
						rdonly("#dialogForm");
						$('#formdata :input[hideOne]').show();
						break;
					case state = 'view':
						$(this).dialog("option", "title", "View");
						disableForm('#formdata');
						$(this).dialog("option", "buttons", butt2);
						$('#formdata :input[hideOne]').show();
						break;
				}
				if(oper!='view'){
						set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']");
					}
			},
			close: function (event, ui) {
				parent_close_disabled(false);
				emptyFormdata(errorField, '#formdata');
				$("#formdata a").off();
				if (oper == 'view') {
					$(this).dialog("option", "buttons", butt1);
				}
			},
			buttons: butt1,
		});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam = {
		// action: 'get_table_default',
		// url:'util/get_table_default',
		// field:'',
		// table_name:'finance.facontrol',
		// table_id:'idno',

		action:'get_table_default',
		url:'/util/get_table_default',
		field:'',
		fixPost:'true',
		table_name:['finance.facontrol AS fa', 'sysdb.company AS sc'],
		table_id:'fa_idno',
		join_type:['LEFT JOIN'],
		join_onCol:['fa.compcode'],
		join_onVal:['sc.compcode'],
		filterCol:['fa.compcode'],
		filterVal:['session.compcode']
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam = {
		action: 'save_table_default',
		url:'facontrol/form',
		field:'',
		oper:oper,
		table_name:'finance.facontrol',
		table_id:'fa_idno'
	};

	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Compcode', name: 'fa_compcode', width: 15, hidden: false,canSearch: false },
			{ label: 'Company Name', name: 'sc_name', width: 50, hidden: false,canSearch: false },
			{ label: 'idno', name: 'fa_idno', width: 5, hidden: true },
			{ label: 'Year', name: 'fa_year', width: 30, hidden: false ,canSearch: true, checked: true},
			{ label: 'Period', name: 'fa_period', width: 30, hidden: false,canSearch: true },
			{ label: 'Status', name:'fa_recstatus', width:30, classes:'wrap', hidden:false,
            formatter: formatter, unformat: unformat, cellattr: function (rowid, cellvalue)
            { return cellvalue == 'Deactive' ? 'class="alert alert-danger"' : '' },},
			{ label: 'adduser', name: 'fa_adduser', width: 90, hidden: true },
			{ label: 'adddate', name: 'fa_adddate', width: 90, hidden: true },
			{ label: 'upduser', name: 'fa_upduser', width: 90, hidden: true },
			{ label: 'upddate', name: 'fa_upddate', width: 90, hidden: true }
			
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 350,
		rowNum: 30,
		sortname: 'fa_idno',
		sortorder: 'desc',
		pager: "#jqGridPager",
		ondblClickRow: function (rowid, iRow, iCol, e) {
			$("#jqGridPager td[title='Edit Selected Row']").click();
		},
		gridComplete: function () {
			if (oper == 'add') {
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
		},

    });
    
    //////////////////////////// STATUS FORMATTER /////////////////////////////////////////////////
			
			function formatter(cellvalue, options, rowObject) {
				if (cellvalue == 'A') {
					return "Active";
				}
				if (cellvalue == 'D') {
					return "Deactive";
				}
			}
	
			function unformat(cellvalue, options) {
				if (cellvalue == 'Active') {
					return "A";
				}
				if (cellvalue == 'Deactive') {
					return "D";
				}
			}

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		title: "Add New Row",
		onClickButton: function () {
			oper = 'add';
			$("#dialogForm").dialog("open");
		},
    });
    
    // .jqGrid('navButtonAdd', "#jqGridPager", {
	// 	caption: "", cursor: "pointer", position: "first",
	// 	buttonicon: "glyphicon glyphicon-trash",
	// 	title: "Delete Selected Row",
	// 	onClickButton: function () {
	// 		oper = 'del';
	// 		selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
	// 		if (!selRowId) {
	// 			alert('Please select row');
	// 			return emptyFormdata(errorField, '#formdata');
	// 		} else {
	// 			saveFormdata("#jqGrid", "#dialogForm", "#formdata", 'del', saveParam, urlParam, null, { 'Code': selRowId });
	// 		}
	// 	},
	// }).jqGrid('navButtonAdd', "#jqGridPager", {
	// 	caption: "", cursor: "pointer", position: "first",
	// 	buttonicon: "glyphicon glyphicon-info-sign",
	// 	title: "View Selected Row",
	// 	onClickButton: function () {
	// 		oper = 'view';
	// 		selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
	// 		populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'view');
	// 	},
	// }).jqGrid('navButtonAdd', "#jqGridPager", {
	// 	caption: "", cursor: "pointer", position: "first",
	// 	buttonicon: "glyphicon glyphicon-edit",
	// 	title: "Edit Selected Row",
	// 	onClickButton: function () {
	// 		oper = 'edit';
	// 		selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
	// 		populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit');
	// 	},
	// })

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////

	populateSelect('#jqGrid','#searchForm');
	searchClick('#jqGrid', '#searchForm', urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['fa_recstatus','fa_adduser','fa_adddate','fa_upduser','fa_upddate']);


});

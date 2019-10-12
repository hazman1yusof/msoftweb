
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']","input[name='computerid']","input[name='ipaddress']");
	/////////////////////////validation//////////////////////////
	$.validate({
		modules: 'sanitize',
		language: {
			requiredFields: ''
		},
	});

	var errorField = [];
	conf = {
		onValidate: function ($form) {
			if (errorField.length > 0) {
				return {
					element: $(errorField[0]),
					message: ' '
				}
			}
		},
	};
	//////////////////////////////////////////////////////////////
	var mycurrency = new currencymode(['#percent_', '#amount', '#svc_amount', '#svc_percent_', '#i_amount', '#i_percent_']);

	////////////////////////////////////start dialog///////////////////////////////////////
	var butt1 = [{
		text: "Save", click: function () {
			mycurrency.formatOff();
			mycurrency.check0value(errorField);
			if ($('#formdata').isValid({ requiredFields: '' }, conf, true)) {
				saveFormdata("#jqGrid", "#dialogForm", "#formdata", oper, saveParam, urlParam);
			} else {
				mycurrency.formatOn();
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
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				switch (oper) {
					case state = 'add':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "Add");
						enableForm('#formdata');
						hideOne('#formdata');
						rdonly("#formdata");
						rdonly("#dialogForm");
						break;
					case state = 'edit':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "Edit");
						enableForm('#formdata');
						frozeOnEdit("#dialogForm");
						$('#formdata :input[hideOne]').show();
						rdonly("#formdata");
						rdonly("#dialogForm");
						break;
					case state = 'view':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "View");
						disableForm('#formdata');
						$('#formdata :input[hideOne]').show();
						$(this).dialog("option", "buttons", butt2);
						break;
				}
				if (oper != 'view') {
					set_compid_from_storage("input[name='lastcomputerid']","input[name='lastipaddress']","input[name='computerid']","input[name='ipaddress']");
				}
				if (oper != 'add') {

				}
			},
			close: function (event, ui) {
				emptyFormdata(errorField, '#formdata');
				parent_close_disabled(false);
				$('#formdata .alert').detach();
				//$('.alert').detach();
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
		action: 'get_table_default',
		url: '/util/get_table_default',
		field: '',
		table_name: 'hisdb.billtymst',
		table_id: 'billtype',
		filterCol: ['compcode'],
		filterVal: ['session.compcode'],
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam = {
		action: 'save_table_default',
		url: '/billtype/form',
		field: '',
		oper: oper,
		table_name: 'hisdb.billtymst',
		table_id: 'billtype',
		saveip:'true',
		checkduplicate:'true'
	};

	//////////////////////////start grid/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Bill Type', name: 'billtype', width: 40, classes: 'wrap', canSearch: true},
			{ label: 'opprice', name: 'opprice', width: 90, hidden: true },
			{ label: 'Description', name: 'description', width: 90, classes: 'wrap', canSearch: true, checked: true  },
			{ label: 'Price', name: 'price', width: 40, classes: 'wrap' },
			{ label: 'Amount', name: 'amount', width: 40, classes: 'wrap' },
			{ label: 'Percentage', name: 'percent_', width: 40, classes: 'wrap', formatter: formatter1, unformat: unformat1 },
			{ label: 'All Service', name: 'service', width: 40, classes: 'wrap', formatter: formatter, unformat: unformat },
			{ label: 'discchgcode', name: 'discchgcode', width: 90, hidden: true },
			{ label: 'ttacode', name: 'ttacode', width: 90, hidden: true },
			{ label: 'discrate', name: 'discrate', width: 90, hidden: true },
			{ label: 'adduser', name: 'adduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'adddate', name: 'adddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upduser', name: 'upduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upddate', name: 'upddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'Record Status', name: 'recstatus', width: 30, classes: 'wrap', formatter:formatterstatus, unformat:unformatstatus, cellattr: function(rowid, cellvalue)
							{return cellvalue == 'Deactive' ? 'class="alert alert-danger"': ''}, 
			},
			{ label: 'idno', name: 'idno', hidden: true },
			{ label: 'computerid', name: 'computerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'ipaddress', name: 'ipaddress', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden: true, classes: 'wrap' },
		],
		autowidth: true,
		viewrecords: true,
		multiSort: true,
		sortname: 'idno',
		sortorder: 'desc',
		loadonce: false,
		width: 900,
		height: 100,
		rowNum: 30,
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
		onSelectRow: function (rowid, selected) {
			if (rowid != null) {
				$("#Fsvc a").off();
				urlParam_svc.filterVal[0] = selrowData("#jqGrid").billtype;
				saveParam_svc.filterVal[0] = selrowData("#jqGrid").billtype;
				urlParam_item.filterVal[0] = selrowData("#jqGrid").billtype;
				saveParam_item.filterVal[0] = selrowData("#jqGrid").billtype;
				$("#Fsvc :input[name='billtype']").val(selrowData("#jqGrid").billtype);
				$("#Fsvc :input[name='description']").val(selrowData("#jqGrid").description);
				refreshGrid('#jqGridsvc', urlParam_svc);
				$('#jqGriditem').jqGrid('clearGridData');
				$("#pg_jqGridPager3 table").hide();
				$("#pg_jqGridPager2 table").show();

				if (selrowData("#jqGrid").service == 1) {
					refreshGrid('#jqGridsvc', urlParam_svc);
					$("#pg_jqGridPager2 table").hide();
				}
			}
		},

	});

	/////////////////////////formatter & unformat/////////////////////////////////////////////////////////

	function formatter(cellvalue, option, rowObject) {
		return parseInt(cellvalue) ? "Yes" : "No";
	}

	function unformat(cellvalue, options) {
		if ((cellvalue) == 'Yes') {
			return "1";
		} else {
			return "0";
		}
	}

	function formatter1(cellvalue, option, rowObject) {
		return cellvalue + "%";
	}

	function unformat1(cellvalue, options) {
		return cellvalue.replace("%", "");
	}

	/*function formatterRecord(cellvalue, options, rowObject) {
		if (cellvalue == 'A') {
			return "Active";
		}
		if (cellvalue == 'D') {
			return "Deactive";
		}
	}

	function unformatterRecord(cellvalue, options) {
		if (cellvalue == 'Active') {
			return "Active";
		}
		if (cellvalue == 'Deactive') {
			return "Deactive";
		}
	}*/

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-trash",
		id: 'jqGridglyphicon-trash',
		title: "Delete Selected Row",
		onClickButton: function () {
			oper = 'del';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				alert('Please select row');
				return emptyFormdata(errorField, '#formdata');
			} else {
				saveFormdata("#jqGrid", "#dialogForm", "#formdata", 'del', saveParam, urlParam, { 'idno': selrowData('#jqGrid').idno });
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-info-sign",
		title: "View Selected Row",
		onClickButton: function () {
			oper = 'view';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'view');
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit');
			recstatusDisable();

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
	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////

	populateSelect('#jqGrid', '#searchForm');
	searchClick('#jqGrid', '#searchForm', urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid', false, saveParam, ['idno','recstatus','adduser','adddate','upduser','upddate']);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////// billtysvc //////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	var dialog_ChgGroup = new ordialog(
		'chggroup','hisdb.chggroup',"#Fsvc :input[name='svc_chggroup']",errorField,
		{	colModel:[
				{label:'Group Code',name:'grpcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
			],
				urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','A']
				},
					ondblClickRow: function () {
							$('#svc_price').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#svc_price').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}		
		},{
			title:"Select Charge Group",
			open: function(){
				dialog_ChgGroup.urlParam.filterCol=['compcode','recstatus'],
				dialog_ChgGroup.urlParam.filterVal=['session.compcode','A']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_ChgGroup.makedialog();

	var buttsvc1 = [{
		text: "Save", click: function () {
			mycurrency.formatOff();
			mycurrency.check0value(errorField);
			if ($('#Fsvc').isValid({ requiredFields: '' }, {}, true)) {
				saveFormdata("#jqGridsvc", "#Dsvc", "#Fsvc", oper_svc, saveParam_svc, urlParam_svc, '#searchForm2');
			} else {
				mycurrency.formatOn();
			}
		}
	}, {
		text: "Cancel", click: function () {
			$(this).dialog('close');
		}
	}];

	var oper_svc;
	$("#Dsvc")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				//inputCtrl("#Dsvc","#Fsvc",oper_svc);
				parent_close_disabled(true);
				switch (oper_svc) {
					case state = 'add':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "Add");
						enableForm('#Fsvc');
						rdonly('#Fsvc');
						hideOne('#Fsvc');
						break;
					case state = 'edit':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "Edit");
						enableForm('#Fsvc');
						frozeOnEdit("#Dsvc");
						rdonly('#Fsvc');
						$('#Fsvc :input[hideOne]').show();
						break;
					case state = 'view':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "View");
						disableForm('#Fsvc');
						$('#Fsvc :input[hideOne]').show();
						$(this).dialog("option", "buttons", butt2);
						break;
				}
				if (oper_svc == 'add') {
					dialog_ChgGroup.on();
				}
				if (oper_svc == 'edit' && $('#jqGriditem').jqGrid('getGridParam', 'reccount') < 1) {
					dialog_ChgGroup.on();

				}
				if (oper_svc == 'edit' && $('#jqGriditem').jqGrid('getGridParam', 'reccount') >= 1) {
					$("#Fsvc :input[name='svc_chggroup']").prop("readonly", true);
				}
				if (oper_svc != 'add') {
					dialog_ChgGroup.check(errorField);
				}
				if (oper_svc != 'view') {
					set_compid_from_storage("input[name='svc_lastcomputerid']","input[name='svc_lastipaddress']","input[name='svc_computerid']","input[name='svc_ipaddress']");
				}

			},
			close: function (event, ui) {
				parent_close_disabled(false);
				emptyFormdata(errorField, '#Fsvc');
				$('#Fsvc .alert').detach();
				//$('.alert').detach();
				dialog_ChgGroup.off();
				// $("#Fsvc a").off();
				if (oper == 'view') {
					$(this).dialog("option", "buttons", buttsvc1);
				}
			},
			buttons: buttsvc1,
		});

	/////////////////////parameter for jqgrid url SVC/////////////////////////////////////////////////
	var urlParam_svc = {
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		fixPost: 'true',//replace underscore with dot
		table_name: ['hisdb.billtysvc AS svc', 'hisdb.billtymst AS m', 'hisdb.chggroup AS cc'],
		table_id: 'svc_chggroup',
		join_type: ['JOIN', 'JOIN'],
		join_onCol: ['svc.billtype', 'cc.grpcode'],
		join_onVal: ['m.billtype', 'svc.chggroup'],
		filterCol: ['svc.billtype', 'svc.compcode', 'm.compcode', 'm.service'],
		filterVal: ['', 'session.company', 'session.company', '0'],
		sort_idno: true,
	}

	var saveParam_svc = {
		action: 'save_table_default',
		url:'/billtype/form',
		field: '',
		oper: oper_svc,
		table_name: 'hisdb.billtysvc',
		fixPost: 'true',//throw out dot in the field name
		table_id: 'svc_chggroup',//ni utk tgk duplicate
		idnoUse: 'svc_idno',
		filterCol: ['billtype'],
		filterVal: [''],
		saveip:'true',
		checkduplicate:'true'
	};

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$("#jqGridsvc").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Bill Type', name: 'svc_billtype', width: 50, hidden: true },
			{ label: 'Description', name: 'm_description', width: 90, hidden: true },
			{ label: 'Chg. Group', name: 'svc_chggroup', width: 50, classes: 'wrap', canSearch: true },
			{ label: 'Description', name: 'cc_description', width: 90, classes: 'wrap', canSearch: true , checked: true},
			{ label: 'Price', name: 'svc_price', width: 90, classes: 'wrap', checked: true },
			{ label: 'Amount', name: 'svc_amount', width: 90, classes: 'wrap', },
			{ label: 'Percentage', name: 'svc_percent_', width: 50, classes: 'wrap', formatter: formatter1, unformat: unformat1 },
			{ label: 'All Item', name: 'svc_allitem', width: 50, classes: 'wrap', formatter: formatter, unformat: unformat },
			{ label: 'Discount Charge Code', name: 'svc_discchgcode', width: 50, classes: 'wrap', },
			{ label: 'discrate', name: 'svc_discrate', width: 60, hidden: true },
			{ label: 'adduser', name: 'svc_adduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'adddate', name: 'svc_adddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upduser', name: 'svc_upduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upddate', name: 'svc_upddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'Record Status', name: 'svc_recstatus', width: 30, classes: 'wrap', formatter:formatterstatus, unformat:unformatstatus, cellattr: function(rowid, cellvalue)
							{return cellvalue == 'Deactive' ? 'class="alert alert-danger"': ''}, 
			},
			{ label: 'No', name: 'svc_idno', width: 50, hidden: true },
			{ label: 'computerid', name: 'svc_computerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'ipaddress', name: 'svc_ipaddress', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastcomputerid', name: 'svc_lastcomputerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastipaddress', name: 'svc_lastipaddress', width: 90, hidden: true, classes: 'wrap' },
		],
		viewrecords: true,
		autowidth: true,
		multiSort: true,
		loadonce: false,
		width: 900,
		height: 100,
		rowNum: 30,
		hidegrid: false,
		caption: caption('searchForm2', 'Bill Type Service'),
		pager: "#jqGridPager2",
		onPaging: function (pgButton) {
		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
			$("#jqGridPager2 td[title='Edit Selected Row']").click();
		},
		gridComplete: function () {
			if (oper == 'add') {
				$("#jqGridsvc").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			$('#' + $("#jqGridsvc").jqGrid('getGridParam', 'selrow')).focus();

			/////////////////////////////// reccount ////////////////////////////
			if ($("#jqGridsvc").getGridParam("reccount") >= 1) {
				$("#jqGridglyphicon-trash").hide();
			}

			if ($("#jqGridsvc").getGridParam("reccount") < 1) {
				$("#jqGridglyphicon-trash").show()
			}

		},
		onSelectRow: function (rowid, selected) {
			if (rowid != null) {
				rowData = $('#jqGridsvc').jqGrid('getRowData', rowid);
				$("#Fitem a").off();
				//console.log(rowData.svc_billtype);
				urlParam_item.filterVal[0] = selrowData("#jqGridsvc").svc_billtype;
				urlParam_item.filterVal[2] = selrowData("#jqGridsvc").svc_chggroup;
				$("#Fitem :input[name*='i_billtype']").val(selrowData("#jqGridsvc").svc_billtype);
				$("#Fitem :input[name*='i_chggroup']").val(selrowData("#jqGridsvc").svc_chggroup);
				$("#Fitem :input[name*='c_description']").val(selrowData("#jqGridsvc").cc_description);
				//urlParam_item.filterVal[0]=rowData['svc_billtype'];
				//urlParam_item.filterVal[2]=rowData['svc_chggroup'];  
				//$("#Fitem :input[name*='i_billtype']").val(rowData['svc_billtype']);
				//$("#Fitem :input[name*='i_chggroup']").val(rowData['svc_chggroup']);
				//$("#Fitem :input[name*='c_description']").val(rowData['cc_description']);
				refreshGrid('#jqGriditem', urlParam_item);
				$("#pg_jqGridPager3 table").show();


				if (rowData['svc_allitem'] == '1') {
					refreshGrid('#jqGriditem', urlParam_item);
					$("#pg_jqGridPager3 table").hide();
				}


			}
		},

	});

	$("#jqGridsvc").jqGrid('navGrid', '#jqGridPager2', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGridsvc", urlParam_svc);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		caption: "",
		buttonicon: "glyphicon glyphicon-trash",
		id: "jqGridPager2glyphicon-trash",
		onClickButton: function () {
			oper_svc = 'del';
			var selRowId = $("#jqGridsvc").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				alert('Please select row');
				return emptyFormdata(errorField, '#Fsvc');
			} else {
				saveFormdata("#jqGridsvc", "#Dsvc", "#Fsvc", 'del', saveParam_svc, urlParam_svc, null, { 'idno': selrowData('#jqGridsvc').svc_idno });
				//saveFormdata("#jqGridsvc","#Dsvc","#Fsvc",'del',saveParam_svc,{'svc_chggroup':selRowId});
			}
		},
		position: "first",
		title: "Delete Selected Row",
		cursor: "pointer"
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		caption: "",
		buttonicon: "glyphicon glyphicon-info-sign",
		onClickButton: function () {
			oper_svc = 'view';
			selRowId = $("#jqGridsvc").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGridsvc", "#Dsvc", "#Fsvc", selRowId, 'view');
		},
		position: "first",
		title: "View Selected Row",
		cursor: "pointer"
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		caption: "",
		buttonicon: "glyphicon glyphicon-edit",
		onClickButton: function () {
			oper_svc = 'edit';
			selRowId = $("#jqGridsvc").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGridsvc", "#Dsvc", "#Fsvc", selRowId, 'edit');
			recstatusDisable();
		},
		position: "first",
		title: "Edit Selected Row",
		cursor: "pointer"
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		caption: "",
		buttonicon: "glyphicon glyphicon-plus",
		onClickButton: function () {
			oper_svc = 'add';
			$("#Dsvc").dialog("open");
			$("#Fsvc :input[name*='billtype']").val(selrowData('#jqGrid').billtype);
			$("#Fsvc :input[name*='description']").val(selrowData('#jqGrid').description);
		},
		position: "first",
		title: "Add New Row",
		cursor: "pointer"
	});

	/*$("#jqGridsvc").jqGrid('setGroupHeaders', {
	  useColSpanStyle: false, 
	  groupHeaders:[
		{startColumnName: 'svc.billtype', numberOfColumns: 8, titleText: 'Bill Type Service'},
	  ]
	});*/

	addParamField('#jqGridsvc', false, urlParam_svc);
	//addParamField('#jqGridsvc',false,saveParam_svc,['cc_description','m_description','svc_discrate',"svc_recstatus"]);
	addParamField('#jqGridsvc', false, saveParam_svc, ['cc_description', 'm_description', 'svc_discrate', 'svc_idno', 'svc_adduser', 'svc_adddate', 'svc_adduser', 'svc_upduser', 'svc_upddate', 'svc_recstatus']);

	//populateSelect('#jqGridsvc');
	populateSelect('#jqGridsvc', '#searchForm2');
	searchClick('#jqGridsvc', '#searchForm2', urlParam_svc);
	//toogleSearch('#sbut2','#searchForm2','off');


	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////// billtype Item //////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	var dialog_chgcode = new ordialog(
		'chgmast','hisdb.chgmast',"#Fitem :input[name*='i_chgcode']",errorField,
		{	colModel:[
				{label:'Charge Code',name:'chgcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
			],
			urlParam: {
						filterCol:['compcode','recstatus','chggroup'],
						filterVal:['session.compcode','A',$("#Fitem :input[name*='i_chggroup']").val()]
					},
					ondblClickRow: function () {
						$('#i_price').focus();
					},
					gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#i_price').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Charge Code",
			open: function(){
				dialog_chgcode.urlParam.filterCol=['chggroup'],
				dialog_chgcode.urlParam.filterVal=[$("#Fitem :input[name*='i_chggroup']").val()]
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_chgcode.makedialog();

	var buttitem1 = [{
		text: "Save", click: function () {
			mycurrency.formatOff();
			mycurrency.check0value(errorField);
			if ($('#Fitem').isValid({ requiredFields: '' }, {}, true)) {
				saveFormdata("#jqGriditem", "#Ditem", "#Fitem", oper_item, saveParam_item, urlParam_item);
			} else {
				mycurrency.formatOn();
			}
		}
	}, {
		text: "Cancel", click: function () {
			$(this).dialog('close');
		}
	}];

	var oper_item;
	$("#Ditem")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				switch (oper_item) {
					case state = 'add':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "Add");
						enableForm('#Fitem');
						('#Fitem');
						hideOne('#Fitem');
						break;
					case state = 'edit':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "Edit");
						$("#Fitem a").off();
						enableForm('#Fitem');
						frozeOnEdit("#Ditem");
						rdonly('#Fitem');
						$('#Fitem :input[hideOne]').show();
						break;
					case state = 'view':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "View");
						disableForm('#Fitem');
						$('#Fitem :input[hideOne]').show();
						$(this).dialog("option", "buttons", butt2);
						break;
				}
				if (oper_item == 'add') {
					dialog_chgcode.on();
				}
				if (oper_item != 'add') {
					dialog_chgcode.check(errorField);
				}
				if(oper_item!='view'){
					set_compid_from_storage("input[name='i_lastcomputerid']","input[name='i_lastipaddress']","input[name='i_computerid']","input[name='i_ipaddress']");
				}
				/*if(oper_item!='add'){
					dialog_chgcode.check(errorField);
				}*/
			},
			close: function (event, ui) {
				emptyFormdata(errorField, '#Fitem');
				parent_close_disabled(false);
				$('#Fitem .alert').detach();
				//$('.alert').detach();
				dialog_chgcode.off();
				if (oper == 'view') {
					$(this).dialog("option", "buttons", buttitem1);
				}
			},
			buttons: buttitem1,
		});
	/////////////////////parameter for jqgrid url Item/////////////////////////////////////////////////
	var urlParam_item = {
		action: 'get_table_default',
		url: '/util/get_table_default',
		field: '',
		fixPost: 'true',//replace underscore with dot
		table_name: ['hisdb.billtyitem AS i', 'hisdb.billtysvc AS svc', 'hisdb.chggroup AS c'],
		table_id: 'i_chgcode',
		join_type: ['JOIN', 'JOIN'],
		join_onCol: ['i.chggroup', 'c.grpcode'],
		join_onVal: ['svc.chggroup', 'i.chggroup'],
		filterCol: ['i.billtype', 'i.compcode', 'i.chggroup', 'svc.allitem', 'svc.compcode'],
		filterVal: ['', 'session.company', '', '0', 'session.company'],
		sort_idno: true,
	}

	var saveParam_item = {
		action: 'save_table_default',
		url: '/billtype/form',
		field: '',
		oper: oper_item,
		table_name: 'hisdb.billtyitem ',
		fixPost: 'true',//throw out dot in the field name
		table_id: 'i_chgcode',
		idnoUse: 'i_idno',
		filterCol: ['billtype'],
		filterVal: [''],
		saveip:'true',
		checkduplicate:'true'
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////

	$("#jqGriditem").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Bill Type', name: 'i_billtype', width: 50, hidden: true },
			{ label: 'Chg. Group', name: 'i_chggroup', width: 90, hidden: true },
			{ label: 'Description', name: 'c_description', width: 90, hidden: true },
			{ label: 'Chg Code', name: 'i_chgcode', width: 90, classes: 'wrap', canSearch: true, checked: true },
			{ label: 'Price', name: 'i_price', width: 50, classes: 'wrap', },
			{ label: 'Amount', name: 'i_amount', width: 50, classes: 'wrap', },
			{ label: 'discrate', name: 'i_discrate', width: 50, hidden: true },
			{ label: 'Percentage', name: 'i_percent_', width: 50, classes: 'wrap', formatter: formatter1, unformat: unformat1 },
			{ label: 'adduser', name: 'i_adduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'adddate', name: 'i_adddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upduser', name: 'i_upduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upddate', name: 'i_upddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'Record Status', name: 'i_recstatus', width: 10, classes: 'wrap', formatter:formatterstatus, unformat:unformatstatus, cellattr: function(rowid, cellvalue)
							{return cellvalue == 'Deactive' ? 'class="alert alert-danger"': ''}, 
			},
			{ label: 'No', name: 'i_idno', width: 50, hidden: true },
			{ label: 'computerid', name: 'i_computerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'ipaddress', name: 'i_ipaddress', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastcomputerid', name: 'i_lastcomputerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastipaddress', name: 'i_lastipaddress', width: 90, hidden: true, classes: 'wrap' },
		],
		viewrecords: true,
		autowidth: true,
		multiSort: true,
		loadonce: false,
		width: 900,
		height: 100,
		rowNum: 30,
		hidegrid: false,
		caption: caption('searchForm3', 'Bill Type Item'),
		pager: "#jqGridPager3",
		ondblClickRow: function (rowid, iRow, iCol, e) {
			$("#jqGridPager3 td[title='Edit Selected Row']").click();
		},
		gridComplete: function () {
			if (oper == 'add') {
				$("#jqGriditem").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			$('#' + $("#jqGriditem").jqGrid('getGridParam', 'selrow')).focus();

			/////////////////////////////// reccount ////////////////////////////
			if ($("#jqGriditem").getGridParam("reccount") >= 1) {
				$("#jqGridPager2glyphicon-trash").hide();
			}

			if ($("#jqGriditem").getGridParam("reccount") < 1) {
				$("#jqGridPager2glyphicon-trash").show()
			}


		},
		onSelectRow: function (rowid, selected) {
			if (rowid != null) {
				rowData = $('#jqGridsvc').jqGrid('getRowData', rowid);
				//console.log(rowData['svc.billtype']);
			}
		},
	});

	$("#jqGriditem").jqGrid('navGrid', '#jqGridPager3', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGriditem", urlParam_item);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager3", {
		caption: "",
		buttonicon: "glyphicon glyphicon-trash",
		onClickButton: function () {
			oper_suppitems = 'del';
			var selRowId = $("#jqGriditem").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				alert('Please select row');
				return emptyFormdata(errorField, '#Fitem');
			} else {
				saveFormdata("#jqGriditem", "#Ditem", "#Fitem", 'del', saveParam_item, urlParam_item, null, { 'idno': selrowData('#jqGriditem').i_idno });
				//saveFormdata("#jqGriditem","#Ditem","#Fitem",'del',saveParam_item,{"chgcode":selRowId});
			}
		},
		position: "first",
		title: "Delete Selected Row",
		cursor: "pointer"
	}).jqGrid('navButtonAdd', "#jqGridPager3", {
		caption: "",
		buttonicon: "glyphicon glyphicon-info-sign",
		onClickButton: function () {
			oper_item = 'view';
			selRowId = $("#jqGriditem").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGriditem", "#Ditem", "#Fitem", selRowId, 'view');
		},
		position: "first",
		title: "View Selected Row",
		cursor: "pointer"
	}).jqGrid('navButtonAdd', "#jqGridPager3", {
		caption: "",
		buttonicon: "glyphicon glyphicon-edit",
		onClickButton: function () {
			oper_item = 'edit';
			selRowId = $("#jqGriditem").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGriditem", "#Ditem", "#Fitem", selRowId, 'edit');
			recstatusDisable();
		},
		position: "first",
		title: "Edit Selected Row",
		cursor: "pointer"
	}).jqGrid('navButtonAdd', "#jqGridPager3", {
		caption: "",
		buttonicon: "glyphicon glyphicon-plus",
		onClickButton: function (rowid, selected) {
			oper_item = 'add';
			//rowData = $('#jqGridsvc').jqGrid ('getRowData', rowid);
			$("#Ditem").dialog("open");
			//var xx= 
			$("#Fitem :input[name*='i_billtype']").val(selrowData('#jqGridsvc').svc_billtype);
			$("#Fitem :input[name*='i_chggroup']").val(selrowData('#jqGridsvc').svc_chggroup);
			$("#Fitem :input[name*='c_description']").val(selrowData('#jqGridsvc').cc_description);
		},
		position: "first",
		title: "Add New Row",
		cursor: "pointer"
	});

	addParamField('#jqGriditem', false, urlParam_item);
	addParamField('#jqGriditem', false, saveParam_item, ["c_description", "i_discrate", "i_idno",'i_adduser', 'i_adddate', 'i_upduser', 'i_upddate', 'i_recstatus']);

	//populateSelect('#jqGriditem');
	populateSelect('#jqGriditem', '#searchForm3');
	searchClick('#jqGriditem', '#searchForm3', urlParam_item);
	//searchClick('#gridSuppBonus','#searchForm3',urlParam_item);
	//toogleSearch('#sbut3','#searchForm3','off');


	///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/////////////////Pager Hide/////////////////////////////////////////////////////////////////////////////////////////
	$("#pg_jqGridPager2 table").hide();
	$("#pg_jqGridPager3 table").hide();

	$("#jqGrid3_panel1").on("show.bs.collapse", function(){
		$("#jqGridsvc").jqGrid ('setGridWidth', Math.floor($("#jqGridsvc_c")[0].offsetWidth-$("#jqGridsvc_c")[0].offsetLeft-28));
	});

	$("#jqGrid3_panel2").on("show.bs.collapse", function(){
		$("#jqGriditem").jqGrid ('setGridWidth', Math.floor($("#jqGriditem_c")[0].offsetWidth-$("#jqGriditem_c")[0].offsetLeft-28));
	});
});


$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function () {
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
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				toggleFormData('#jqGrid', '#formdata');
				switch (oper) {
					case state = 'add':
						$(this).dialog("option", "title", "Add");
						enableForm('#formdata');
						rdonly("#formdata");
						hideOne("#formdata");
						rdonly("#dialogForm");
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
						$('#formdata :input[hideOne]').show();
						$(this).dialog("option", "buttons", butt2);
						break;
				}
				if(oper!='view'){
						set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']");
						//dialog_dept.handler(errorField);
					}
			},
			close: function (event, ui) {
				parent_close_disabled(false);
				emptyFormdata(errorField, '#formdata');
				$('.alert').detach();
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
		field: '',
		table_name: 'hisdb.relationship',
		table_id: 'RelationShipCode',
		sort_idno: true
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam = {
		action: 'save_table_default',
		field: '',
		oper: oper,
		table_name: 'hisdb.relationship',
		table_id: 'RelationShipCode',
		saveip:'true'
	};

	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [

			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'Code', name: 'RelationShipCode', width: 20, classes: 'wrap', canSearch: true, checked: true },
			{ label: 'Description', name: 'Description', classes: 'wrap', canSearch: true, width: 80 },

			{
				label: 'Record Status', name: 'recstatus', width: 80, formatter: formatterstatus, unformat: unformat,
				cellattr: function (rowid, cellvalue) {
					return cellvalue == 'Deactive' ? 'class="alert alert-danger"' : ''
				},
			},
			{ label: 'idno', name: 'idno', width: 5, hidden: true },
			{ label: 'adduser', name: 'adduser', width: 90, hidden: true },
			{ label: 'adddate', name: 'adddate', width: 90, hidden: true },
			{ label: 'upduser', name: 'upduser', width: 90, hidden: true },
			{ label: 'upddate', name: 'upddate', width: 90, hidden: true },
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
			{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true},

		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 350,
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


	});

	////////////////////formatter status////////////////////////////////////////
	function formatterstatus(cellvalue, option, rowObject) {
		if (cellvalue == 'A') {
			return 'Active';
		}

		if (cellvalue == 'D') {
			return 'Deactive';
		}

	}

	////////////////////unformatter status////////////////////////////////////////
	function unformat(cellvalue, option, rowObject) {
		if (cellvalue == 'Active') {
			return 'Active';
		}

		if (cellvalue == 'Deactive') {
			return 'Deactive';
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
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			oper = 'del';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				alert('Please select row');
				return emptyFormdata(errorField, '#formdata');
			} else {
				saveFormdata("#jqGrid", "#dialogForm", "#formdata", 'del', saveParam, urlParam, null, { 'RelationShipCode': selRowId });
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

	toogleSearch('#sbut1', '#searchForm', 'on');
	populateSelect('#jqGrid', '#searchForm');
	searchClick('#jqGrid', '#searchForm', urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid', false, saveParam);
	addParamField('#jqGrid', false, saveParam, ['idno']);
});

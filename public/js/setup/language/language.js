
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
					element: $(errorField[0]),
					message: ' '
				}
			}
		},
	};

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
				switch (oper) {
					case state = 'add':
						$(this).dialog("option", "title", "Add");
						enableForm('#formdata');
						hideOne('#formdata');
						rdonly("#dialogForm");
						break;
					case state = 'edit':
						$(this).dialog("option", "title", "Edit");
						enableForm('#formdata');
						frozeOnEdit("#dialogForm");
						rdonly("#dialogForm");
						$('#formdata :input[hideOne]').show();
						break;
					case state = 'view':
						$(this).dialog("option", "title", "View");
						disableForm('#formdata');
						$(this).dialog("option", "buttons", butt2);
						break;
				}
				if (oper != 'view') {
					set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']");
					//dialog_dept.handler(errorField);
				}
				if (oper != 'add') {
					toggleFormData('#jqGrid', '#formdata');
					//dialog_dept.check(errorField);
				}
			},
			close: function (event, ui) {
				parent_close_disabled(false);
				emptyFormdata(errorField, '#formdata');
				//$('.alert').detach();
				$('#formdata .alert').detach();
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
		table_name: 'hisdb.languagecode',
		table_id: 'Code',
		sort_idno: true,
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam = {
		action: 'save_table_default',
		field: '',
		oper: oper,
		table_name: 'hisdb.languagecode',
		table_id: 'Code',
		saveip:'true'
	};

	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Language Code', name: 'Code', width: 25, classes: 'wrap', editable: true, checked: true, canSearch: true },
			{ label: 'Description', name: 'Description', width: 90, classes: 'wrap', canSearch: true, editable: true },
			{ label: 'adduser', name: 'adduser', width: 90, hidden: true },
			{ label: 'upduser', name: 'upduser', width: 90, hidden: true },
			{
				label: 'Record Status', name: 'recstatus', width: 13, classes: 'wrap',
				formatter: formatter, unformat: unformat, cellattr: function (rowid, cellvalue)
				{ return cellvalue == 'Deactive' ? 'class="alert alert-danger"' : '' },
			},
			{ label: 'idno', name: 'idno', hidden: true },
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

	////////////////////////////formatter//////////////////////////////////////////////////////////
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
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			oper = 'del';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				alert('Please select row');
				return emptyFormdata(errorField, '#formdata');
			} else {
				saveFormdata("#jqGrid", "#dialogForm", "#formdata", 'del', saveParam, urlParam, null, { 'Code': selRowId });
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
	addParamField('#jqGrid', false, saveParam, ['idno']);
});

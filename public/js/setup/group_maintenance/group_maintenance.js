
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function () {
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
	//////////////////////////////////////////////////////////////


	////////////////////object for dialog handler//////////////////

	////////////////////////////////////start dialog///////////////////////////////////////
	grppage = 'grpmaintenance';
	var butt1 = [{
		text: "Save", click: function () {
			if ($('#formdata').isValid({ requiredFields: '' }, conf, true)) {
				if ($('.nav li.active a').attr('href') == '#grp_main') {
					saveFormdata("#jqGrid_grpmaintenance", "#dialogForm", "#formdata", oper, saveParam, urlParam, null, {});
				} else {
					saveFormdata("#jqGrid_grpaccess", "#dialogForm", "#formdata", oper, saveParam2, urlParam2, null,
						{
							yesallold: selrowData('#jqGrid_grpaccess').groupacc_yesall,
							canrunold: selrowData('#jqGrid_grpaccess').groupacc_canrun,
							programid: selrowData('#jqGrid_grpaccess').programtab_programid,
							lineno: selrowData('#jqGrid_grpaccess').programtab_lineno,
							groupid: selrowData('#jqGrid_grpmaintenance').groupid,
							programmenu: arraybtngrp[arraybtngrp.length - 1]
						});

				}
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
			width: 4 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				switch (oper) {
					case state = 'add':
						$(this).dialog("option", "title", "Add");
						enableForm('#formdata');
						break;
					case state = 'edit':
						$(this).dialog("option", "title", "Edit");
						enableForm('#formdata');
						frozeOnEdit("#dialogForm");
						break;
					case state = 'view':
						$(this).dialog("option", "title", "View");
						disableForm('#formdata');
						$(this).dialog("option", "buttons", butt2);
						break;
				}
				if (oper != 'view') {
				}
				if (oper != 'add') {
				}
			},
			close: function (event, ui) {
				// resetwhereatID();
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
		table_name: 'sysdb.groups',
		table_id: 'groupid'
	}

	var saveParam = {
		action: 'save_table_default',
		field: '',
		oper: oper,
		table_name: 'sysdb.groups',
		table_id: 'groupid'
	};

	/////////////////////parameter for saving url////////////////////////////////////////////////


	$("#jqGrid_grpmaintenance").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Group Id', name: 'groupid', width: 100 },
			{ label: 'Description', name: 'description', width: 300 },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager_grpmaintenance",
		ondblClickRow: function (rowid, iRow, iCol, e) {
			$("#jqGridPager_grpmaintenance td[title='Edit Selected Row']").click();
		},
		onSelectRow: function (rowid) {
			enable_grpaccessPill(true, selrowData('#jqGrid_grpmaintenance').description);
		},

	});

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid_grpmaintenance").jqGrid('navGrid', '#jqGridPager_grpmaintenance', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid_grpmaintenance", urlParam);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager_grpmaintenance", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			oper = 'del';
			selRowId = $("#jqGrid_grpmaintenance").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				alert('Please select row');
				return emptyFormdata(errorField, '#formdata');
			} else {
				saveFormdata("#jqGrid_grpmaintenance", "#dialogForm", "#formdata", oper, saveParam, urlParam, null, { groupid: selRowId });
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager_grpmaintenance", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-info-sign",
		title: "View Selected Row",
		onClickButton: function () {
			oper = 'view';
			selRowId = $("#jqGrid_grpmaintenance").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid_grpmaintenance", "#dialogForm", "#formdata", selRowId, 'view');
		},
	}).jqGrid('navButtonAdd', "#jqGridPager_grpmaintenance", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#jqGrid_grpmaintenance").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid_grpmaintenance", "#dialogForm", "#formdata", selRowId, 'edit');
		},
	}).jqGrid('navButtonAdd', "#jqGridPager_grpmaintenance", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		title: "Add New Row",
		onClickButton: function () {
			oper = 'add';
			$("#dialogForm").dialog("open");
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid_grpmaintenance', true, urlParam);
	addParamField('#jqGrid_grpmaintenance', false, saveParam);

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam2 = {
		action: 'get_table_default',
		field: '',
		table_name: ['sysdb.programtab', 'sysdb.groupacc'],
		table_id: 'programtab_idno',
		join_type: ['LEFT JOIN'],
		join_onCol: ['programtab.programmenu'],
		join_onVal: ['groupacc.programmenu'],
		join_filterCol: [['programtab.lineno', 'programtab.compcode', 'groupacc.groupid']],
		join_filterVal: [['skip.groupacc.lineno', 'skip.groupacc.compcode', '']],
		fixPost: true,
		filterCol: ['programtab.programmenu'],
		filterVal: ['main']
	}

	var saveParam2 = {
		action: 'group_maintenance_save',
		field: '',
		oper: oper,
		table_name: 'sysdb.programtab',
		table_id: 'idno'
	};

	/////////////////////parameter for saving url////////////////////////////////////////////////


	$("#jqGrid_grpaccess").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Line No', name: 'programtab_lineno', width: 50, hidden: true },
			{ label: 'Program Name', name: 'programtab_programname', width: 200 },
			{ label: 'Program Type', name: 'programtab_programtype', width: 50, formatter: programtype, unformat: de_programtype },
			{ label: 'Program Id', name: 'programtab_programid', width: 100 },
			{ label: 'Can Run', name: 'groupacc_canrun', formatter: zero_one, width: 50 },
			{ label: 'Yes all', name: 'groupacc_yesall', formatter: zero_one, width: 50 },
			{ label: 'idno', name: 'programtab_idno', width: 50, hidden: true },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 250,
		rowNum: 30,
		sortname: 'programtab_lineno',
		sortorder: 'asc',
		pager: "#jqGridPager_grpaccess",
		ondblClickRow: function (rowid, iRow, iCol, e) {
			if ($("#jqGrid_grpaccess").jqGrid('getRowData', rowid).programtab_programtype != 'P') {
				apenddbtngroup($("#jqGrid_grpaccess").jqGrid('getRowData', rowid));
			} else {
				$("#jqGridPager_grpaccess td[title='Edit Selected Row']").click();
			}
		},

	});

	function zero_one(cellvalue, options, rowObject) {
		if (cellvalue == 1) { return 'Yes'; } else { return 'No'; }
	}

	function programtype(cellvalue, options, rowObject) {
		if (cellvalue == 'M') { return 'Menu'; } else { return 'Program'; }
	}

	function de_programtype(cellvalue, options, rowObject) {
		if (cellvalue == 'Menu') { return 'M'; } else { return 'P'; }
	}

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid_grpaccess").jqGrid('navGrid', '#jqGridPager_grpaccess', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid_grpaccess", urlParam2);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager_grpaccess", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#jqGrid_grpaccess").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid_grpaccess", "#dialogForm", "#formdata", selRowId, 'edit');
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid_grpaccess', false, urlParam2);
	addParamField('#jqGrid_grpaccess', false, saveParam2);

	show_grpaccess(false);
	enable_grpaccessPill(false);

	function show_grpaccess(show) {
		if (show) {
			$('div[grpaccess]').show();
			$('div[grpmaintenance]').hide();
		} else {
			$('div[grpaccess]').hide();
			$('div[grpmaintenance]').show();
		}
	}

	$('.nav-pills a').on('shown.bs.tab', function (event) {
		if ($(event.target).attr('href') == "#grp_access") {
			show_grpaccess(true);
			urlParam2.join_filterVal[0][2] = selrowData('#jqGrid_grpmaintenance').groupid
			refreshGrid("#jqGrid_grpaccess", urlParam2);
			$("#jqGrid_grpaccess").jqGrid('setGridWidth', $("#jqGrid_grpaccess_c")[0].clientWidth);
		} else {
			show_grpaccess(false);
			$("#jqGrid_grpmaintenance").jqGrid('setGridWidth', $("#jqGrid_grpmaintenance_c")[0].clientWidth);
		}
	});

	function enable_grpaccessPill(enable, text) {
		if (enable) {
			$(".nav li a[href='#grp_access']").closest('li').removeClass('disabled');
			$(".nav li a[href='#grp_access']").attr("data-toggle", "tab");
		} else {
			$(".nav li a[href='#grp_access']").closest('li').addClass('disabled');
			$(".nav li a[href='#grp_access']").removeAttr("data-toggle");
		}
		if (text) {
			$(".nav li a[href='#grp_access']").html("<i class='fa fa-lock'></i>  Set Security | " + text);
		}
	}

	var arraybtngrp = ['main'];
	function apenddbtngroup(rowdata) {
		urlParam2.filterVal[0] = rowdata.programtab_programid;
		$("<div class='btn-group' role='group'><button type='button' class='btn btn-default' programid='" + rowdata.programtab_programid + "'>" + rowdata.programtab_programname + "</button></div>").hide().appendTo('#btngroup').fadeIn(500);

		$("button[programid = '" + rowdata.programtab_programid + "']").on("click", gotobreadcrumb);
		arraybtngrp.push(rowdata.programtab_programid);

		refreshGrid("#jqGrid_grpaccess", urlParam2);
	}

	$("button[programid = 'main'").on("click", gotobreadcrumb);
	function gotobreadcrumb() {
		urlParam2.filterVal[0] = $(this).attr('programid');
		refreshGrid("#jqGrid_grpaccess", urlParam2);
		var arrytodel = arraybtngrp.splice(arraybtngrp.indexOf($(this).attr('programid')) + 1, arraybtngrp.length);
		arrytodel.forEach(function (element) {
			$("button[programid = '" + element + "']").closest('div').fadeOut(300, function () { $(this).remove(); });
		});
	}

});


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
				toggleFormData('#jqGrid', '#formdata');
				switch (oper) {
					case state = 'add':
						$(this).dialog("option", "title", "Add");
						enableForm('#formdata');
						rdonly("#dialogForm");
						break;
					case state = 'edit':
						$(this).dialog("option", "title", "Edit");
						enableForm('#formdata');
						rdonly("#dialogForm");
						frozeOnEdit("#dialogForm");
						break;
					case state = 'view':
						$(this).dialog("option", "title", "View");
						disableForm('#formdata');
						$(this).dialog("option", "buttons", butt2);
						break;
				}
				if (oper != 'view') {
					set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']");
				}
				if (oper != 'add') {

				}
			},
			close: function (event, ui) {
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
		table_name: ['sysdb.company'],
		table_id: 'compcode',
		filterCol: ['1'],
		filterVal: ['skip.1']
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam = {
		action: 'save_table_default',
		field: '',
		oper: oper,
		table_name: ['sysdb.company'],
		table_id: 'compcode',
		saveip:'true'
	};

	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Comp Code', name: 'compcode', width: 100, canSearch: true },
			{ label: 'Company Name', name: 'name', width: 200, canSearch: true },
			{ label: 'Address', name: 'address1', width: 80, hidden: true },
			{ label: 'Address 2', name: 'address2', hidden: true },
			{ label: 'Address 3', name: 'address3', hidden: true },
			{ label: 'Address 4', name: 'address4', hidden: true },
			{ label: 'Bmppath', name: 'bmppath1', width: 90, hidden: true },
			{ label: 'Bmppath', name: 'bmppath2', hidden: true },
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden:true},
			{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden:true},
			{ label: 'Logo', name: 'logo1', width: 90, hidden: true },
			{ label: 'Record Status', name: 'recstatus', width: 90, hidden: true },
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
			/*if(editedRow!=0){
				$("#jqGrid").jqGrid('setSelection',editedRow,false);
			}*/
		},

	});

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
				saveFormdata("#jqGrid", "#dialogForm", "#formdata", 'del', saveParam, urlParam, { 'compcode': selRowId });
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

	//////////////////////////////////////end grid//////////////////////

	//////////handle searching, its radio button and toggle ////////////

	toogleSearch('#sbut1', '#searchForm', 'on');
	populateSelect('#jqGrid', '#searchForm');
	searchClick('#jqGrid', '#searchForm', urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid', false, saveParam, ['depamt']);

	///////////////////////start utility function/////////////////////

	function toogleSearch(butID, formID, statenow) {
		this.state = false;
		$(butID + ' i').attr('class', 'fa fa-chevron-down');
		$(butID).on("click", function () {
			$(formID).toggle("fast");
			$(butID + ' i').toggleClass('fa fa-chevron-down', this.state);
			$(butID + ' i').toggleClass('fa fa-chevron-up', !this.state);
		});
		if (statenow == 'off') {
			this.state = true;
			$(formID).toggle();
			$(butID + ' i').attr('class', 'fa fa-chevron-up');
		}
	}

	function toggleFormData(grid, formName) {
		if (oper == 'add') {
			$(formName + " .btn-group").hide();
		} else {
			$(formName + " .btn-group").show();
		}
		$(formName + " a[name='next']").on("click", function () {
			var selrow = $(grid).jqGrid('getGridParam', 'selrow');
			if (selrow == null) return;

			var ids = $(grid).jqGrid('getDataIDs');
			if (ids.length < 2) return;

			console.log(ids);

			var index = $(grid).jqGrid('getInd', selrow); index++;
			if (index > ids.length) index = 1;

			$(grid).jqGrid('setSelection', ids[index - 1]);
			console.log(selrow)
			populateFormdata(grid, null, formName, ids[index - 1], oper);
		});
		$(formName + " a[name='prev']").on("click", function () {
			var selrow = $(grid).jqGrid('getGridParam', 'selrow');
			if (selrow == null) return;

			var ids = $(grid).jqGrid('getDataIDs');
			if (ids.length < 2) return;

			var index = $(grid).jqGrid('getInd', selrow); index--;
			console.log(index);
			if (index == 0) index = ids.length;

			$(grid).jqGrid('setSelection', ids[index - 1]);
			console.log(selrow)
			populateFormdata(grid, '', formName, ids[index - 1], oper);
		});
	}

	function addParamField(grid, needRefresh, param, except) {
		var temp = [];
		$.each($(grid).jqGrid('getGridParam', 'colModel'), function (index, value) {
			if (except != undefined && except.indexOf(value['name']) === -1) {
				temp.push(value['name']);
			} else if (except == undefined) {
				temp.push(value['name']);
			}
		});
		param.field = temp;
		if (needRefresh) {
			refreshGrid(grid, param);
		}
	}

	function refreshGrid(grid, urlParam) {
		$(grid).jqGrid('setGridParam', { datatype: 'json', url: '../../../../assets/php/entry.php?' + $.param(urlParam) }).trigger('reloadGrid');
	}

	function disableForm(formName) {
		$(formName + ' texarea').prop("readonly", true);
		$(formName + ' input').prop("readonly", true);
		$(formName + ' input[type=radio]').prop("disabled", true);
	}

	function enableForm(formName) {
		$(formName + ' textarea').prop("readonly", false);
		$(formName + ' input').prop("readonly", false);
		$(formName + ' input[type=radio]').prop("disabled", false);
	}

	function populateFormdata(grid, dialog, form, selRowId, state) {
		if (!selRowId) {
			alert('Please select row');
			return emptyFormdata(form);
		}
		rowData = $(grid).jqGrid('getRowData', selRowId);
		$.each(rowData, function (index, value) {
			var input = $("[name='" + index + "']");
			if (input.is("[type=radio]")) {
				$("[name='" + index + "'][value='" + value + "']").prop('checked', true);
			} else {
				input.val(value);
			}
		});
		if (dialog != '') {
			$(dialog).dialog("open");
		}
	}

	function frozeOnEdit(form) {
		$(form + ' input[frozeOnEdit]').prop("readonly", true);
	}

	function emptyFormdata(form) {
		errorField.length = 0;
		$(form).trigger('reset');
		$(form + ' .help-block').html('');
	}

	function saveFormdata(grid, dialog, form, oper, saveParam, urlParam, obj) {
		if (obj == null) {
			obj = { null: 'null' };
		}
		saveParam.oper = oper;
		$.post("../../../../assets/php/entry.php?" + $.param(saveParam), $(form).serialize() + '&' + $.param(obj), function (data) {

		}).fail(function (data) {
			errorText(dialog, data.responseText);
		}).success(function (data) {
			$(dialog).dialog('close');
			editedRow = $(grid).jqGrid('getGridParam', 'selrow');
			refreshGrid(grid, urlParam);
		});
	}

	function errorText(dialog, text) {
		$(".ui-dialog-buttonpane").prepend("<div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> " + text + "</div>");
	}

	var delay = (function () {
		var timer = 0;
		return function (callback, ms) {
			clearTimeout(timer);
			timer = setTimeout(callback, ms);
		};
	})();

	function populateSelect(grid, form) {
		$.each($(grid).jqGrid('getGridParam', 'colModel'), function (index, value) {
			if (value['canSearch']) {
				if (value['checked']) {
					$(form + " [name=Scol]").append("<label class='radio-inline'><input type='radio' name='dcolr' value='" + value['name'] + "' checked>" + value['label'] + "</input></label>");
				}
				else {
					$(form + " [name=Scol]").append("<label class='radio-inline'><input type='radio' name='dcolr' value='" + value['name'] + "'>" + value['label'] + "</input></label>");
				}
			}
		});
	}

	function searchClick(grid, form, urlParam) {
		$(form + ' [name=Stext]').on("keyup", function () {
			delay(function () {
				search(grid, $(form + ' [name=Stext]').val(), $(form + ' input:radio[name=dcolr]:checked').val(), urlParam);
			}, 500);
		});

		$(form + ' [name=Stext]').on("change", function () {
			search(grid, $(form + ' [name=Stext]').val(), $(form + ' input:radio[name=dcolr]:checked').val(), urlParam);
		});
	}

	function search(grid, Stext, Scol, urlParam) {
		urlParam.searchCol = null;
		urlParam.searchVal = null;
		if (Stext.trim() != '') {
			var split = Stext.split(" "), searchCol = [], searchVal = [];
			$.each(split, function (index, value) {
				searchCol.push(Scol);
				searchVal.push('%' + value + '%');
			});
			urlParam.searchCol = searchCol;
			urlParam.searchVal = searchVal;
		}
		refreshGrid(grid, urlParam);
	}
	/////////////////////////////////End utility function////////////////////////////////////////////////

	///////////////////////////////start->dialogHandler part/////////////////////////////////////////////
	function makeDialog(table, id, cols, title) {
		this.table = table;
		this.id = id;
		this.cols = cols;
		this.title = title;
		this.handler = dialogHandler;
		this.check = checkInput;
	}

	$("#dialog").dialog({
		autoOpen: false,
		width: 7 / 10 * $(window).width(),
		modal: true,
		close: function (event, ui) {
			paramD.searchCol = null;
			paramD.searchVal = null;
		},
	});

	var selText, Dtable, Dcols;
	$("#gridDialog").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Comp Code', name: 'compcode', width: 40, classes: 'pointer', canSearch: true, checked: true },
			{ label: 'Name', name: 'name', width: 70, canSearch: true, classes: 'pointer' },

		],
		width: 680,
		viewrecords: true,
		loadonce: false,
		multiSort: true,
		rowNum: 30,
		pager: "#gridDialogPager",
		ondblClickRow: function (rowid, iRow, iCol, e) {
			var data = $("#gridDialog").jqGrid('getRowData', rowid);
			$("#gridDialog").jqGrid("clearGridData", true);
			$("#dialog").dialog("close");
			if (selText == 'compcode') {

			}
			$(selText).val(rowid);
			$(selText).focus();
			$(selText).parent().next().html(data['desc']);
		},

	});

	var paramD = { action: 'get_table_default', table_name: '', field: '', table_id: '', filter: '' };
	function dialogHandler() {
		var table = this.table, id = this.id, cols = this.cols, title = this.title, self = this;
		$(id + " ~ a").on("click", function () {
			selText = id, Dtable = table, Dcols = cols,
				$("#dialog").dialog("open");
			$("#dialog").dialog("option", "title", title);
			paramD.table_name = table;
			paramD.field = cols;
			paramD.table_id = cols[0];

			$("#gridDialog").jqGrid('setGridParam', { datatype: 'json', url: '../../../../assets/php/entry.php?' + $.param(paramD) }).trigger('reloadGrid');
			$('#Dtext').val(''); $('#Dcol').html('');

			$.each($("#gridDialog").jqGrid('getGridParam', 'colModel'), function (index, value) {
				if (value['canSearch']) {
					if (value['checked']) {
						$("#Dcol").append("<label class='radio-inline'><input type='radio' name='dcolr' value='" + cols[index] + "' checked>" + value['label'] + "</input></label>");
					} else {
						$("#Dcol").append("<label class='radio-inline'><input type='radio' name='dcolr' value='" + cols[index] + "' >" + value['label'] + "</input></label>");
					}
				}
			});
		});
		$(id).on("blur", function () {
			self.check();
		});
	}

	function checkInput() {
		var table = this.table, id = this.id, field = this.cols, value = $(this.id).val()
		var param = { action: 'input_check', table: table, field: field, value: value };
		$.get("../../../../assets/php/entry.php?" + $.param(param), function (data) {

		}, 'json').done(function (data) {
			if (data.msg == 'success') {
				var index = errorField.indexOf(id);
				if (index > -1) {
					errorField.splice(index, 1);
				}
				$(id).parent().siblings(".help-block").html(data.row[field[1]]);
			} else if (data.msg == 'fail') {
				errorField.push(id);
				$(id).parent().removeClass("has-success").addClass("has-error");
				$(id).removeClass("valid").addClass("error");
				$(id).parent().siblings(".help-block").html("Invalid Code ( " + field[0] + " )");
			}
		});
	}

	$('#Dtext').keyup(function () {
		delay(function () {
			Dsearch($('#Dtext').val(), $('#checkForm input:radio[name=dcolr]:checked').val());
		}, 500);
	});

	$('#Dcol').change(function () {
		Dsearch($('#Dtext').val(), $('#checkForm input:radio[name=dcolr]:checked').val());
	});

	function Dsearch(Dtext, Dcol) {
		paramD.searchCol = null;
		paramD.searchVal = null;
		Dtext = Dtext.trim();
		if (Dtext != '') {
			var split = Dtext.split(" "), searchCol = [], searchVal = [];
			$.each(split, function (index, value) {
				searchCol.push(Dcol);
				searchVal.push('%' + value + '%');
			});
			paramD.searchCol = searchCol;
			paramD.searchVal = searchVal;
		}
		refreshGrid("#gridDialog", paramD);
	}
	///////////////////////////////finish->dialogHandler///part////////////////////////////////////////////
});

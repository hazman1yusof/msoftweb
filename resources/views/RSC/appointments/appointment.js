
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	/////////////////////////////////////////validation//////////////////////////
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

	var Type2 = $('#Type2').val();

	$("$Scol").val("#Type2");
	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency = new currencymode(['#amount']);

	////////////////////////////////////start dialog//////////////////////////////////////
	var oper = null;
	var unsaved = false;

	$("#dialogForm")
		.dialog({
			width: 9.5 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				$("#jqGrid2").jqGrid('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth - $("#jqGrid2_c")[0].offsetLeft));
				mycurrency.formatOnBlur();
				switch (oper) {
					case state = 'add':
						$("#jqGrid2").jqGrid("clearGridData", true);
						$("#pg_jqGridPager2 table").show();
						hideatdialogForm(true);
						enableForm('#formdata');
						rdonly('#formdata');
						$("#reqdept").val($("#x").val());
						break;
					case state = 'edit':
						$("#pg_jqGridPager2 table").show();
						hideatdialogForm(true);
						enableForm('#formdata');
						rdonly('#formdata');
						break;
					case state = 'view':
						disableForm('#formdata');
						$("#pg_jqGridPager2 table").hide();
						break;
				}if (oper != 'add') {
					dialog_reqdept.check(errorField);
					dialog_reqtodept.check(errorField);
				} if (oper != 'view') {
					dialog_reqdept.on();
					dialog_reqtodept.on();
				}
			},
			beforeClose: function (event, ui) {
				if (unsaved) {
					event.preventDefault();
					bootbox.confirm("Are you sure want to leave without save?", function (result) {
						if (result == true) {
							unsaved = false
							$("#dialogForm").dialog('close');
						}
					});
				}
			},
			close: function (event, ui) {
				addmore_jqgrid2.state = false;//reset balik
				parent_close_disabled(false);
				emptyFormdata(errorField, '#formdata');
				emptyFormdata(errorField, '#formdata2');
				$('.alert').detach();
				$("#formdata a").off();
				$(".noti").empty();
				$("#refresh_jqGrid").click();
			},
		});
	////////////////////////////////////////end dialog///////////////////////////////////////////////////

	/////////////////////parameter for jqgrid url////////////////////////////////////////////////////////
	var urlParam = {
		action: 'get_table_default',
		field: '',
		table_name: 'hisdb.apptresrc',
		table_id: 'idno',
		sort_idno: true,
		filterCol: ['TYPE'],
		filterVal: [$('#Type2').val()],
	}
	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam = {
		action: 'stockReq_header_save',
		field: '',
		oper: oper,
		table_name: 'material.ivreqhd',
		table_id: 'recno',
		returnVal: true,
	};

	/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Record No', name: 'recno', width: 10, canSearch: true, selected: true, formatter: padzero, unformat: unpadzero },
			{ label: 'Request Department', name: 'reqdept', width: 30, canSearch: true },
			{ label: 'Request No', name: 'ivreqno', width: 10, canSearch: true, formatter: padzero, unformat: unpadzero },
			{ label: 'Request To Department', name: 'reqtodept', width: 30, classes: 'wrap' },
			{ label: 'Request Date', name: 'reqdt', width: 20, canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'Amount', name: 'amount', width: 20, align: 'right', formatter: 'currency' },
			{ label: 'Status', name: 'recstatus', width: 20 },
			{ label: 'adduser', name: 'adduser', width: 90, hidden: true },
			{ label: 'Remarks', name: 'remarks', width: 50, classes: 'wrap' },
			{ label: 'Request Type', name: 'reqtype', width: 50, hidden: 'true' },
			{ label: 'authpersonid', name: 'authpersonid', width: 90, hidden: true },
			{ label: 'authdate', name: 'authdate', width: 40, hidden: 'true' },
			{ label: 'reqpersonid', name: 'reqpersonid', width: 50, hidden: 'true' },
			{ label: 'adddate', name: 'adddate', width: 90, hidden: true },
			{ label: 'upduser', name: 'upduser', width: 90, hidden: true },
			{ label: 'upddate', name: 'upddate', width: 90, hidden: true },
			{ label: 'idno', name: 'idno', width: 90, hidden: true },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 200,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow: function (rowid, selected) {
			let stat = selrowData("#jqGrid").recstatus;
			switch ($("#scope").val()) {
				case "dataentry":
					break;
				case "cancel":
					if (stat == 'POSTED') {
						$('#but_cancel_jq').show();
						$('#but_post_jq,#but_reopen_jq').hide();
					} else if (stat == "CANCELLED") {
						$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
					} else {
						$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
					}
					break;
				case "all":
					if (stat == 'POSTED') {
						$('#but_reopen_jq').show();
						$('#but_post_jq,#but_cancel_jq').hide();
						$("#jqGridPager td[title='Edit Selected Row']").hide();
					} else if (stat == "CANCELLED") {
						$('#but_reopen_jq').show();
						$('#but_post_jq,#but_cancel_jq').hide();
						$("#jqGridPager td[title='Edit Selected Row']").show();
					} else {
						$('#but_cancel_jq,#but_post_jq').show();
						$('#but_reopen_jq').hide();
						$("#jqGridPager td[title='Edit Selected Row']").show();
					}
					break;
			}
			urlParam2.filterVal[0] = selrowData("#jqGrid").recno;
			$('#reqdeptdepan').text(selrowData("#jqGrid").reqdept);//tukar kat depan tu
			$('#ivreqnodepan').text(selrowData("#jqGrid").recno);

			urlParam2.filterVal[0] = selrowData("#jqGrid").recno;
			urlParam2.join_filterCol = [['ivdt.uomcode', 's.deptcode', 's.year'], []];
			urlParam2.join_filterVal = [['skip.s.uomcode', "skip.'" + selrowData("#jqGrid").reqdept + "'", "skip.'" + moment(selrowData("#jqGrid").reqdt).year() + "'"], []];
			refreshGrid("#jqGrid3", urlParam2);
		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
			let stat = selrowData("#jqGrid").recstatus;
			if(stat != 'POSTED'){
				$("#jqGridPager td[title='Edit Selected Row']").click();
			}
		},
		gridComplete: function () {
			$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
			if (oper == 'add' || oper == null) {
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
		},

	});

	////////////////////// set label jqGrid right ////////////////////////////////////////////////
	$("#jqGrid").jqGrid('setLabel', 'amount', 'Amount', { 'text-align': 'right' });

	/////////////////////////start grid pager/////////////////////////////////////////////////////////

	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid", urlParam, oper);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-info-sign",
		title: "View Selected Row",
		onClickButton: function () {
			oper = 'view';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'view');
			refreshGrid("#jqGrid2", urlParam2);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", id: "glyphicon-edit", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit');

			refreshGrid("#jqGrid2", urlParam2);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		id: 'glyphicon-plus',
		title: "Add New Row",
		onClickButton: function () {
			oper = 'add';
			$("#dialogForm").dialog("open");
		},
	});

	//////////handle searching, its radio button and toggle /////////////////////////////////////////////
	populateSelect('#jqGrid', '#searchForm');

	//////////add field into param, refresh grid if needed///////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid', false, saveParam, ['adduser', 'adddate', 'idno']);

	////////////////////////////////hide at dialogForm///////////////////////////////////////////////////
	function hideatdialogForm(hide) {
		if (hide) {
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete").hide();
			$("#saveDetailLabel").show();
		} else {
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete").show();
			$("#saveDetailLabel").hide();
		}
	}

	///////////////////////////////// reqdt check date validate from period////////// ////////////////
	// var actdateObj = new setactdate(["#reqdt"]);
	// actdateObj.getdata().set();

	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
	$("#but_cancel_jq,#but_post_jq,#but_reopen_jq").click(function () {
		saveParam.oper = $(this).data("oper");
		let obj = { recno: selrowData('#jqGrid').recno };
		$.post("../../../../assets/php/entry.php?" + $.param(saveParam), obj, function (data) {
			refreshGrid("#jqGrid", urlParam);
		}).fail(function (data) {
			alert(data.responseText);
		}).done(function (data) {
			//2nd successs?
		});
	});

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form, selfoper, saveParam, obj) {
		if (obj == null) {
			obj = {};
		}
		saveParam.oper = selfoper;

		$.post("../../../../assets/php/entry.php?" + $.param(saveParam), $(form).serialize() + '&' + $.param(obj), function (data) {
			unsaved = false;
			hideatdialogForm(false);
			if ($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1) {
				addmore_jqgrid2.state = true;
				$('#jqGrid2_iladd').click();
			}

			if (selfoper == 'add') {
				oper = 'edit';//sekali dia add terus jadi edit lepas tu
				$('#recno').val(data.recno);
				$('#ivreqno').val(data.ivreqno);
				$('#idno').val(data.idno);//just save idno for edit later

				urlParam2.filterVal[0] = data.recno;
				urlParam2.join_filterCol = [['ivt.uomcode', 's.deptcode', 's.year'], []];
				urlParam2.join_filterVal = [['skip.s.uomcode', $('#reqdept').val(), moment($("#reqdt").val()).year()], []];
			} else if (selfoper == 'edit') {
				//doesnt need to do anything
			}
			disableForm('#formdata');
			hideatdialogForm(false);

		}, 'json').fail(function (data) {
			alert(data.responseText);
		}).done(function (data) {
			//2nd successs?
		});
	}

	$("#dialogForm").on('change keypress', '#formdata :input', '#formdata :textarea', function () {
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	///////////////////utk dropdown search By/////////////////////////////////////////////////
	searchBy();
	function searchBy() {
		$.each($("#jqGrid").jqGrid('getGridParam', 'colModel'), function (index, value) {
			if (value['canSearch']) {
				if (value['selected']) {
					$("#searchForm [id=Scol]").append(" <option selected value='" + value['name'] + "'>" + value['label'] + "</option>");
				} else {
					$("#searchForm [id=Scol]").append(" <option value='" + value['name'] + "'>" + value['label'] + "</option>");
				}
			}
			searchClick2('#jqGrid', '#searchForm', urlParam);
		});
	}
	////////////////////////////////////////////////////////////////////////////
	trandept()
	function trandept() {
		var param = {
			action: 'get_value_default',
			field: ['deptcode'],
			table_name: 'sysdb.department',
			filterCol: ['storedept'],
			filterVal: ['1']
		}
		$.get("../../../../assets/php/entry.php?" + $.param(param), function (data) {

		}, 'json').done(function (data) {
			if (!$.isEmptyObject(data)) {
				$.each(data.rows, function (index, value) {
					if (value.deptcode.toUpperCase() == $("#x").val().toUpperCase()) {
						$("#searchForm [id=trandept]").append("<option selected value='" + value.deptcode + "'>" + value.deptcode + "</option>");
					} else {
						$("#searchForm [id=trandept]").append(" <option value='" + value.deptcode + "'>" + value.deptcode + "</option>");
					}
				});
			}
		});
	}

	// trandept_excel()
	function trandept_excel() {
		var param = {
			action: 'get_excel_default',
			field: ['deptcode'],
			table_name: 'sysdb.department',
			filterCol: ['storedept'],
			filterVal: ['1']
		}
		$.get("../../../../assets/php/entry.php?" + $.param(param), function (data) {

		}).done(function (data) {
			var myWindow = window.open("", "MsgWindow", "width=200,height=100");
			myWindow.document.open("text/html", "replace");
		    myWindow.document.write("<html><body><p>Hello World!sdsdsd</p></body></html>");
    		// myWindow.document.write(data);
		});
	}


	$('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	$('#trandept').on('change', searchChange);

	function whenchangetodate() {
		if ($('#Scol').val() == 'reqdt') {
			$("input[name='Stext']").show("fast");
			$("#tunjukname").hide("fast");
			$("input[name='Stext']").attr('type', 'date');
			$("input[name='Stext']").velocity({ width: "250px" });
			$("input[name='Stext']").on('change', searchbydate);
		} else if ($('#Scol').val() == 'supplier_name') {
			$("input[name='Stext']").hide("fast");
			$("#tunjukname").show("fast");
		} else {
			$("input[name='Stext']").show("fast");
			$("#tunjukname").hide("fast");
			$("input[name='Stext']").attr('type', 'text');
			$("input[name='Stext']").velocity({ width: "100%" });
			$("input[name='Stext']").off('change', searchbydate);
		}
	}

	function searchbydate() {
		search('#jqGrid', $('#searchForm [name=Stext]').val(), $('#searchForm [name=Scol] option:selected').val(), urlParam);
	}

	function searchChange() {
		var arrtemp = [$('#Status option:selected').val(), $('#trandept option:selected').val()];
		var filter = arrtemp.reduce(function (a, b, c) {
			if (b == 'All') {
				return a;
			} else {
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		}, { fct: ['recstatus', 'reqtodept'], fv: [], fc: [] });

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		refreshGrid('#jqGrid', urlParam);
	}

	/////////////////////parameter for jqgrid2 url///////////////////////////////////////////////////////
	var urlParam2 = {
		action: 'get_table_default',
		field: ['ivdt.compcode', 'ivdt.recno', 'ivdt.lineno_', 'ivdt.itemcode', 'p.description', 'ivdt.uomcode', 'ivdt.pouom',
			's.maxqty', 's.qtyonhand', 'NULL AS recvqtyonhand', 'ivdt.qtyrequest', 'ivdt.qtytxn',
			'ivdt.recstatus'],
		table_name: ['material.ivreqdt ivdt ', 'material.stockloc s', 'material.productmaster p'],
		table_id: 'lineno_',
		join_type: ['LEFT JOIN', 'LEFT JOIN'],
		join_onCol: ['ivdt.itemcode', 'ivdt.itemcode'],
		join_onVal: ['s.itemcode', 'p.itemcode'],
		filterCol: ['ivdt.recno', 'ivdt.compcode','ivdt.recstatus'],
		filterVal: ['', 'session.company','<>.DELETE']
	};
	var addmore_jqgrid2 = { more: false, state: false } // if addmore is true, add after refresh jqgrid2, state true kalu kosong

	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "../../../../assets/php/entry.php?action=stockReq_detail_save",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden: true },
			{ label: 'recno', name: 'recno', width: 50, classes: 'wrap', editable: true, hidden: true },
			{ label: 'Line No', name: 'lineno_', width: 70, classes: 'wrap', editable: true, hidden: true },
			{
				label: 'Item Code', name: 'itemcode', width: 150, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: itemcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Item Description', name: 'description', width: 200, classes: 'wrap', editable: true, editoptions: { readonly: "readonly" } },
			{
				label: 'Uom Code ReqDept', name: 'uomcode', width: 110, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: uomcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			// {
			// 	label: 'Uom Code ReqMadeTo', name: 'UomReqMadeTO', width: 90, classes: 'wrap', editable: true,
			// 	editrules: { required: true, custom: true, custom_func: cust_rules },
			// 	formatter: showdetail,
			// 	edittype: 'custom', editoptions:
			// 	{
			// 		custom_element: uomcodeCustomEdit,
			// 		custom_value: galGridCustomValue
			// 	},
			// },
			{
				label: 'Uom Code ReqMadeTo', name: 'pouom', width: 110, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: pouomCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{
				label: 'Max Qty', name: 'maxqty', width: 80, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true }, editoptions: { readonly: "readonly" },
			},
			{
				label: 'Qty on Hand at Req Dept', name: 'qtyonhand', width: 100, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true }, editoptions: { readonly: "readonly" },
			},
			{
				label: 'Qty on Hand at Req To Dept', name: 'reqmadeqtyonhand', width: 100, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true }, editoptions: { readonly: "readonly" },
			},
			// {
			// 	label: 'Qty on Hand at Req To Dept', name: 'reqmadeqtyonhand', width: 100, align: 'right', classes: 'wrap',
			// 	editable: true,
			// 	formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
			// 	editrules: { required: true, custom: true, custom_func: cust_rules }, editoptions: { readonly: "readonly" },
			// },
			{
				label: 'Qty Requested', name: 'qtyrequest', width: 100, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true, custom: true, custom_func: cust_rules }, edittype: "text",
				editoptions: {
					maxlength: 11,
					dataInit: function (element) {
						element.style.textAlign = 'right';
						$(element).keypress(function (e) {
							if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
								//if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
								return false;
							}
						});
					},
				},

			},
			{
				label: 'Qty Supplied', name: 'qtytxn', width: 100, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true }, editoptions: { readonly: "readonly" },
			},
			{
				label: 'Type', name: 'recstatus', width: 100, classes: 'wrap', hidden: false, editable: true,
				editoptions: { readonly: "readonly" },
			},
			{ label: 'Remarks', name: 'remarks_button', width: 140, formatter: formatterRemarks, unformat: unformatRemarks, hidden: true },
			{ label: 'Remarks', name: 'remarks', width: 100, classes: 'wrap', hidden: true },

		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 1150,
		height: 200,
		rowNum: 30,
		//sortname: 'lineno_',
		//sortorder: "desc",
		pager: "#jqGridPager2",
		loadComplete: function () {
			if (addmore_jqgrid2) $('#jqGrid2_iladd').click();
			addmore_jqgrid2 = false; //only addmore after save inline
		},
		gridComplete: function () {

			$("#jqGrid2_ilcancel").off();
			$("#jqGrid2_ilcancel").on("click", function (event) {
				event.preventDefault();
				event.stopPropagation();
				bootbox.confirm({
					message: "Are you sure want to cancel?",
					buttons: {
						confirm: { label: 'Yes', className: 'btn-success' },
						cancel: { label: 'No', className: 'btn-danger' }
					},
					callback: function (result) {
						if (result == true) {
							$(".noti").empty();
							refreshGrid("#jqGrid2", urlParam2);
						}
						linenotoedit = null;
					}
				});
			});

			$("#jqGrid2").find(".remarks_button").on("click", function (e) {
				$("#remarks2").data('lineno_', $(this).data('lineno_'));
				$("#remarks2").data('grid', "#jqGrid2");
				$("#dialog_remarks").dialog("open");
			});
		},
		afterShowForm: function (rowid) {
			$("#expdate").datepicker();
		},
		beforeSubmit: function (postdata, rowid) {
			dialog_itemcode.check(errorField);
			dialog_uomcode.check(errorField);
			dialog_pouom.check(errorField);
		}
	});

	////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	/////////////////////////all function for remarks//////////////////////////////////////////////////
	var linenotoedit = null;
	function formatterRemarks(cellvalue, options, rowObject) {
		return "<button class='remarks_button btn btn-success btn-xs' type='button' data-lineno='" + rowObject[2] + "' data-remarks='" + rowObject[12] + "'><i class='fa fa-file-text-o'></i> remark</button>";
	}

	function unformatRemarks(cellvalue, options, rowObject) {
		return null;
	}

	var butt1_rem =
		[{
			text: "Save", click: function () {
				let newval = $("#remarks2").val();
				$("#jqGrid2").jqGrid('setRowData', linenotoedit, { remarks: newval });
				$(this).dialog('close');
			}
		}, {
			text: "Cancel", click: function () {
				$(this).dialog('close');
			}
		}];

	var butt2_rem =
		[{
			text: "Close", click: function () {
				$(this).dialog('close');
			}
		}];

	$("#dialog_remarks").dialog({
		autoOpen: false,
		width: 4 / 10 * $(window).width(),
		modal: true,
		open: function (event, ui) {
			let lineno_use = ($('#remarks2').data('lineno_') != 'undefined') ? $('#remarks2').data('lineno_') : linenotoedit;
			$('#remarks2').val($($('#remarks2').data('grid')).jqGrid('getRowData', lineno_use).remarks);

			if (linenotoedit == lineno_use) {
				$("#remarks2").prop('disabled', false);
				$("#dialog_remarks").dialog("option", "buttons", butt1_rem);
			} else {
				$("#remarks2").prop('disabled', true);
				$("#dialog_remarks").dialog("option", "buttons", butt2_rem);
			}
		},
		buttons: butt2_rem
	});

	//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
	var addmore_jqgrid2 = false // if addmore is true, add after refresh jqgrid2
	var myEditOptions = {
		keys: true,
		oneditfunc: function (rowid) {
			linenotoedit = rowid;
			$("#jqGrid2").find(".remarks_button[data-lineno_!='" + linenotoedit + "']").prop("disabled", true);
			$("#jqGrid2").find(".remarks_button[data-lineno_='undefined']").prop("disabled", false);
		},
		aftersavefunc: function (rowid, response, options) {
			// $('#purreqhd_totamount').val(response.responseText);
			// $('#purreqhd_subamount').val(response.responseText);
			if (addmore_jqgrid2.state == true) addmore_jqgrid2.more = true; //only addmore after save inline
			refreshGrid('#jqGrid2', urlParam2, 'add');
			$("#jqGridPager2Delete").show();
		},
		beforeSaveRow: function (options, rowid) {
			mycurrency2.formatOff();
			let editurl = "../../../../assets/php/entry.php?" +
				$.param({
					action: 'stockReq_detail_save',
					//docno:$('#delordhd_docno').val(),
					recno: $('#recno').val(),
					ivreqno: $('#ivreqno').val(),
					reqdept: $('#reqdept').val(),
					//remarks: selrowData('#jqGrid2').remarks//bug will happen later because we use selected row

				});

				// calculate_conversion_factor();

			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
		},
	};

	//////////////////////////////////////////pager jqgrid2/////////////////////////////////////////////
	$("#jqGrid2").inlineNav('#jqGridPager2', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions
		},
		editParams: myEditOptions
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "jqGridPager2Delete",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			selRowId = $("#jqGrid2").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				bootbox.alert('Please select row');
			} else {
				bootbox.confirm({
					message: "Are you sure you want to delete this row?",
					buttons: {
						confirm: { label: 'Yes', className: 'btn-success', }, cancel: { label: 'No', className: 'btn-danger' }
					},
					callback: function (result) {
						if (result == true) {
							param = {
								action: 'stockReq_detail_save',
								recno: $('#recno').val(),
								lineno_: selrowData('#jqGrid2').lineno_,
							}
							$.post("../../../../assets/php/entry.php?" + $.param(param), { oper: 'del' }, function (data) {
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
								$('#amount').val(data);
								refreshGrid("#jqGrid2", urlParam2);
							});
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "saveHeaderLabel",
		caption: "Header", cursor: "pointer", position: "last",
		buttonicon: "",
		title: "Header"
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "saveDetailLabel",
		caption: "Detail", cursor: "pointer", position: "last",
		buttonicon: "",
		title: "Detail"
	});

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject) {
		var field, table;
		switch (options.colModel.name) {
			//case 'itemcode':field=['itemcode','description'];table="material.product";break;
			case 'uomcode': field = ['uomcode', 'description']; table = "material.uom"; break;
			case 'pouom': field = ['uomcode', 'description']; table = "material.uom"; break;
			
		}
		var param = { action: 'input_check', table: table, field: field, value: cellvalue };
		$.get("../../../../assets/php/entry.php?" + $.param(param), function (data) {

		}, 'json').done(function (data) {
			if (!$.isEmptyObject(data.row)) {
				$("#" + options.gid + " #" + options.rowId + " td:nth-child(" + (options.pos + 1) + ")").append("<span class='help-block'>" + data.row.description + "</span>");
			}
		});
		return cellvalue;
	}



	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value, name) {
		var temp;
		var error;
		console.log(name);
		switch (name) {
			case 'Item Code': temp = $('#itemcode'); break;
			case 'Uom Code ReqDept': temp = $('#uomcode'); break;
			case 'Uom Code ReqMadeTo': temp = $('#pouom'); break;
			case 'Qty on Hand at Req To Dept': temp = $("#jqGrid2 input[name='reqmadeqtyonhand']"); 
				$("#jqGrid2 input[name='reqmadeqtyonhand']").hasClass("error");
				break;
			case 'Qty Requested': temp = $("#jqGrid2 input[name='qtyrequest']"); 
				$("#jqGrid2 input[name='qtyrequest']").hasClass("error");
				break;
		}
		return (temp.hasClass("error")) ? [false, "Please enter valid " + name + " value"] : [true, ''];
	}
	

	/////////////////////////////////////////////custom input////////////////////////////////////////////

	function itemcodeCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val; //kalu takde desc kat bawah buang slice
		return $('<div class="input-group"><input id="itemcode" name="itemcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');
	}

	function uomcodeCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input id="uomcode" name="uomcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function pouomCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input id="pouom" name="pouom" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function remarkCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<span class="fa fa-book">val</span>');
	}

	function galGridCustomValue(elem, operation, value) {
		if (operation == 'get') {
			return $(elem).find("input").val();
		}
		else if (operation == 'set') {
			$('input', elem).val(value);
		}
	}

	//////////////////////////////////////////saveDetailLabel////////////////////////////////////////////
	$("#saveDetailLabel").click(function () {
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = false;
		dialog_reqdept.off();
		dialog_reqtodept.off();
		if ($('#formdata').isValid({ requiredFields: '' }, conf, true)) {
			//getQOHreqdept();
			saveHeader("#formdata", oper, saveParam);

		} else {
			mycurrency.formatOn();
		}

	});

	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////

	var mycurrency2 = new currencymode(["#jqGrid2 input[name='amtdisc']", "#jqGrid2 input[name='unitprice']", "#jqGrid2 input[name='amount']", "#jqGrid2 input[name='tot_gst']", "#jqGrid2 input[name='qtyrequest]"]);

	$("#saveHeaderLabel").click(function () {
		emptyFormdata(errorField, '#formdata2');
		hideatdialogForm(true);
		dialog_reqdept.on();
		dialog_reqtodept.on();
		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		refreshGrid("#jqGrid2", urlParam2);
	});

	////////////////////////////// jqGrid2_iladd + jqGrid2_iledit /////////////////////////////
	$("#jqGrid2_iladd, #jqGrid2_iledit").click(function () {
		unsaved = false;
		$("#jqGridPager2Delete").hide();
		dialog_itemcode.on();
		dialog_uomcode.on();
		dialog_pouom.on();

		mycurrency2.formatOnBlur();

		$("#jqGrid2 input[name='qtyrequest']").on('blur', { currency: mycurrency2 }, calculate_conversion_factor);

		$("input[name='qtyrequest']").keydown(function (e) {//when click tab at batchno, auto save
			var code = e.keyCode || e.which;
			if (code == '9'){
				var temp_bool = calculate_conversion_factor();
				if(temp_bool){
					console.log(temp_bool);
					$('#jqGrid2_ilsave').click();
				}
			} 
		});
	});

	////////////////////////////////////// QtyOnHand Request Department/////////////////////////////////////
	function getQOHreqdept() {
		var param = {
			func: 'getQOHreqdept',
			action: 'get_value_default',
			field: ['qtyonhand'],
			table_name: 'material.stockloc'
		}
		var id="#jqGrid2 input[name='reqdeptqtyonhand']"
		var fail_msg = "No Stock Location at request department"

		param.filterCol = ['year', 'deptcode','itemcode'];
		param.filterVal = [moment($('#reqdt').val()).year(), $('#reqdept').val(), $("#jqGrid2 input[name='itemcode']").val()];

		$.get("../../../../assets/php/entry.php?" + $.param(param), function (data) {

		}, 'json').done(function (data) {
			if (!$.isEmptyObject(data.rows)) {
				if($.inArray(id,errorField)!==-1){
					errorField.splice($.inArray(id,errorField), 1);
				}
				$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
				$( id ).removeClass( "error" ).addClass( "valid" );
				$('.noti ol').find("li[data-errorid='"+name+"']").detach();
			} else {
				$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
				$( id ).removeClass( "valid" ).addClass( "error" );
				$('.noti ol').prepend("<li data-errorid='"+name+"'>"+fail_msg+"</li>");
				if($.inArray(id,errorField)===-1){
					errorField.push( id );
				}
			}
		});
	}

	function getQOHreqtodept() {
		var param = {
			func: 'getQOHreqdept',
			action: 'get_value_default',
			field: ['qtyonhand'],
			table_name: 'material.stockloc'
		}
		var id="#jqGrid2 input[name='reqmadeqtyonhand']"
		var fail_msg = "No Stock Location at request to department"
		var name = "getQOHreqtodept";

		param.filterCol = ['year', 'deptcode','itemcode'];
		param.filterVal = [moment($('#reqdt').val()).year(), $('#reqtodept').val(), $("#jqGrid2 input[name='itemcode']").val()];

		$.get("../../../../assets/php/entry.php?" + $.param(param), function (data) {

		}, 'json').done(function (data) {
			if (!$.isEmptyObject(data.rows)) {
				if($.inArray(id,errorField)!==-1){
					errorField.splice($.inArray(id,errorField), 1);
				}
				$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
				$( id ).removeClass( "error" ).addClass( "valid" );
				$('.noti').find("li[data-errorid='"+name+"']").detach();
			} else {
				$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
				$( id ).removeClass( "valid" ).addClass( "error" );
				$('.noti').prepend("<li data-errorid='"+name+"'>"+fail_msg+"</li>");
				if($.inArray(id,errorField)===-1){
					errorField.push( id );
				}
			}
		});
	}

	/////////////////////////////////////////convension factor //////////////////////////////////////////

	function calculate_conversion_factor(event) {

		console.log("balconv");


		var id="#jqGrid2 input[name='qtyrequest']"
		var fail_msg = "Please Choose Suitable UOMCode & POUOMCode"
		var name = "calculate_conversion_factor";

		let convfactor_bool = false;
		let convfactor_uom = parseFloat($("#convfactor_uom").val());
		let convfactor_pouom = parseFloat($("#convfactor_pouom").val());

		let qtyrequest = parseFloat($("#jqGrid2 input[name='qtyrequest']").val());

		console.log(convfactor_uom);
		console.log(convfactor_pouom);

		var balconv = convfactor_pouom*qtyrequest%convfactor_uom;

		if (balconv  == 0) {
			if($.inArray(id,errorField)!==-1){
				errorField.splice($.inArray(id,errorField), 1);
			}
			$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
			$( id ).removeClass( "error" ).addClass( "valid" );
			$('.noti').find("li[data-errorid='"+name+"']").detach();
		} else {
			$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
			$( id ).removeClass( "valid" ).addClass( "error" );
			$('.noti').prepend("<li data-errorid='"+name+"'>"+fail_msg+"</li>");
			if($.inArray(id,errorField)===-1){
				errorField.push( id );
			}
		}

		// console.log(balconv);

		// switch (balconv  != 0 ) {
		// 	case true:
		// 	unsaved = true;
		// 	bootbox.alert('Please Choose Suitable UOMCode & POUOMCode');
		// 		break;
		// 	default:
		// 		break;
		// }

		// if(balconv !=0){
		// 	bootbox.alert('Please Chosse Suitable UOMCode & POUOMCode');
		// 	convfactor_bool = true;
		// 	return convfactor_bool;
		// }else{
		// 	return convfactor_bool;
		// }

		
			
		//event.data.currency.formatOn();//change format to currency on each calculation
		
	}

	////////////////////////////////////////Check Conversion Factor Tab Insert////////////////////////////


	////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////
	$("#jqGrid3").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2").jqGrid('getGridParam', 'colModel'),
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		// sortname: 'lineno_',
		// sortorder: "desc",
		pager: "#jqGridPager3",
		gridComplete: function () {
			$("#jqGrid3").find(".remarks_button").on("click", function (e) {
				$("#remarks2").data('lineno_', $(this).data('lineno_'));
				$("#remarks2").data('grid', "#jqGrid3");
				$("#dialog_remarks").dialog("open");
			});
		},
	});
	jqgrid_label_align_right("#jqGrid3");

	////////////////////////////////////////////////////ordialog////////////////////////////////////////
	var dialog_reqtodept = new ordialog(
		'reqtodept', 'sysdb.department', '#reqtodept', errorField,
		{
			colModel: [
				{ label: 'Department', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			]
		}, {
			title: "Select Request Made To Department",
			open: function () {
				dialog_reqtodept.urlParam.filterCol = ['storedept'];
				dialog_reqtodept.urlParam.filterVal = ['1'];
			}
		}
	);
	dialog_reqtodept.makedialog();

	var dialog_reqdept = new ordialog(
		'reqdept', 'sysdb.department', '#reqdept', errorField,
		{
			colModel: [
				{ label: 'Department', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			]
		}, {
			title: "Select Request Department",
			open: function () {
				dialog_reqdept.urlParam.filterCol = ['storedept'];
				dialog_reqdept.urlParam.filterVal = ['1'];
			}
		}, 'urlParam'
	);
	dialog_reqdept.makedialog();

	var dialog_suppcode = new ordialog(
		'suppcode', 'material.supplier', '#suppcode', errorField,
		{
			colModel: [
				{ label: 'Supplier Code', name: 'suppcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			]
		}, {
			title: "Select Purchase Department",
			open: function () {
				dialog_suppcode.urlParam.filterCol = ['recstatus'];
				dialog_suppcode.urlParam.filterVal = ['A'];
			}
		}, 'urlParam'
	);
	dialog_suppcode.makedialog();

	var dialog_pricecode = new ordialog(
		'pricecode', ['material.pricesource'], "#jqGrid2 input[name='pricecode']", errorField,
		{
			colModel:
			[
				{ label: 'Price code', name: 'pricecode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			]
		}, {
			title: "Select Price Code For Item",
			open: function () {
				dialog_pricecode.urlParam.filterCol = ['compcode', 'recstatus'];
				dialog_pricecode.urlParam.filterVal = ['session.company', 'A'];
			},
			close: function () {
				$(dialog_pricecode.textfield)			//lepas close dialog focus on next textfield 
					.closest('td')						//utk dialog dalam jqgrid jer
					.next()
					.find("input[type=text]").focus();
			}
		}, 'urlParam'
	);
	dialog_pricecode.makedialog(false);

	var dialog_itemcode = new ordialog(
		'itemcode', ['material.stockloc s', 'material.product p', 'material.uom u'], "#jqGrid2 input[name='itemcode']", errorField,
		{
			colModel:
			[
				{ label: 'Item Code', name: 's.itemcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'p.description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'UOM Code', name: 's.uomcode', width: 100, classes: 'pointer' },
				{ label: 'Quantity On Hand', name: 's.qtyonhand', width: 100, classes: 'pointer', },
				{ label: 'Conversion', name: 'u.convfactor', width: 50, classes: 'pointer', hidden:true },
				{ label: 'stocktxntype', name: 's.stocktxntype', width: 50, classes: 'pointer', hidden:true }

			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_itemcode.gridname);
				$("#jqGrid2 input[name='itemcode']").val(data['s.itemcode']);
				$("#jqGrid2 input[name='description']").val(data['p.description']);
				$("#jqGrid2 input[name='uomcode']").val(data['s.uomcode']);
				$("#convfactor_uom").val(data['u.convfactor']);
				$("#jqGrid2 input[name='recstatus'").val(data['s.stocktxntype']);
				getQOHreqtodept();
			}
		}, {
			title: "Select Item For Purchase Request",
			open: function () {
				dialog_itemcode.urlParam.table_id = "none_";
				dialog_itemcode.urlParam.filterCol = ['s.compcode', 's.year', 's.deptcode']; 
				dialog_itemcode.urlParam.filterVal = ['session.company', moment($('#reqdt').val()).year(), $('#reqdept').val()];
				dialog_itemcode.urlParam.join_type = ['LEFT JOIN', 'LEFT JOIN'];
				dialog_itemcode.urlParam.join_onCol = ['s.itemcode', 's.uomcode'];
				dialog_itemcode.urlParam.join_onVal = ['p.itemcode', 'u.uomcode'];
				dialog_itemcode.urlParam.join_filterCol = [['s.compcode', 's.uomcode'], ['s.uomcode']];
				dialog_itemcode.urlParam.join_filterVal = [['skip.p.compcode', 'skip.p.uomcode'], ['skip.p.uomcode']];
			}
		}, 'urlParam'
	);
	dialog_itemcode.makedialog(false);

	var dialog_uomcode = new ordialog(
		'uom', ['material.stockloc s', 'material.uom u'], "#jqGrid2 input[name='uomcode']", errorField,
		{
			colModel:
			[
				{ label: 'UOM code', name: 's.uomcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'u.description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Conversion', name: 'u.convfactor', width: 90, classes: 'pointer' },
				{ label: 'Department code', name: 's.deptcode', width: 150, classes: 'pointer' },
				{ label: 'Item code', name: 's.itemcode', width: 150, classes: 'pointer' },
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_uomcode.gridname);
				$("#jqGrid2 input[name='uomcode']").val(data['s.uomcode']);
				$("#convfactor_uom").val(data['u.convfactor']);
			}

		}, {
			title: "Select UOM Code For Item",
			open: function () {
				dialog_uomcode.urlParam.table_id = "none_";
				dialog_uomcode.urlParam.filterCol = ['s.compcode', 's.deptcode', 's.itemcode', 's.year'];
				dialog_uomcode.urlParam.filterVal = ['session.company', $('#reqdept').val(), $("#jqGrid2 input[name='itemcode']").val(), moment($('#purreqhd_purdate').val()).year()];
				dialog_uomcode.urlParam.join_type = ['LEFT JOIN'];
				dialog_uomcode.urlParam.join_onCol = ['s.uomcode'];
				dialog_uomcode.urlParam.join_onVal = ['u.uomcode'];
				dialog_uomcode.urlParam.join_filterCol = [['s.compcode']];
				dialog_uomcode.urlParam.join_filterVal = [['skip.u.compcode']];
			},
			close: function () {
				$(dialog_uomcode.textfield)			//lepas close dialog focus on next textfield 
					.closest('td')						//utk dialog dalam jqgrid jer
					.next()
					.find("input[type=text]").focus();
			}
		}, 'urlParam'
	);
	dialog_uomcode.makedialog(false);

	var dialog_pouom = new ordialog(
		'pouom', ['material.stockloc s', 'material.uom u'], "#jqGrid2 input[name='pouom']", errorField,
		{
			colModel:
			[
				{ label: 'UOM code', name: 's.uomcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'u.description', width: 300, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Conversion', name: 'u.convfactor', width: 90, classes: 'pointer' }
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_pouom.gridname);
				$("#jqGrid2 input[name='pouom']").val(data['s.uomcode']);
				$("#convfactor_pouom").val(data['u.convfactor']);
			}

		}, {
			title: "Select PO UOM Code For Item",
			open: function () {
				dialog_pouom.urlParam.table_id = "none_";
				dialog_pouom.urlParam.filterCol = ['s.compcode', 's.year', 's.deptcode', 's.itemcode', 's.recstatus'];
				dialog_pouom.urlParam.filterVal = ['session.company', moment($('#reqdt').val()).year(), $('#reqtodept').val(), $("#jqGrid2 input[name='itemcode']").val(), 'A'];
				dialog_pouom.urlParam.join_type = ['LEFT JOIN'];
				dialog_pouom.urlParam.join_onCol = ['s.uomcode'];
				dialog_pouom.urlParam.join_onVal = ['u.uomcode'];
				dialog_pouom.urlParam.join_filterCol = [['s.compcode']];
				dialog_pouom.urlParam.join_filterVal = [['skip.u.compcode']];
			},
			close: function () {
				$(dialog_pouom.textfield)			//lepas close dialog focus on next textfield 
					.closest('td')						//utk dialog dalam jqgrid jer
					.next()
					.find("input[type=text]").focus();
			}
		}, 'urlParam'
	);
	dialog_pouom.makedialog(false);

});
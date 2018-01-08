
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

	$('.sajanktry').click(function () {
		if ($(this).css('width') == '400px') {
			$('.sajanktry div').hide();
			$('.sajanktry').velocity("reverse");
		} else {
			$('.sajanktry').velocity({
				width: "400px",
				height: "120px",
			}, {
					display: "block",
					duration: 400,
					easing: "swing",
					complete: function () { $('.sajanktry div').show(); },
				});
		}
	});

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency = new currencymode(['#amount']);

	////////////////////////////////////start dialog//////////////////////////////////////
	var oper;
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
					dialog_prdept.check(errorField);
					dialog_deldept.check(errorField);
					// dialog_suppcode.check(errorField);
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
		action:'get_table_default',
		field:'',
		table_name:['material.purordhd', 'material.supplier'],
		table_id:'delordhd_idno',
		sort_idno:true,
		join_type:['LEFT JOIN'],
		join_onCol:['supplier.suppcode'],
		join_onVal:['purordhd.suppcode'],
		//filterCol:['source'],
		//filterVal:['GRN'],
	}
	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam = {
		action: 'purOrder_header_save',
		field: '',
		oper: oper,
		table_name: 'material.purordhd',
		table_id: 'recno',
		returnVal: true,
	};

	/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Record No', name: 'purordhd_recno', width: 10, canSearch: true, selected: true },
			{ label: 'Purchase Dept', name: 'purordhd_prdept', width: 30, classes: 'wrap' },
			{ label: 'Purchase Order No', name: 'purordhd_purordno', width: 20, classes: 'wrap', canSearch: true},
			{ label: 'Request Department', name: 'purordhd_reqdept', width: 30,hidden: true },
			{ label: 'deldept', name: 'purordhd_deldept', width: 30,  hidden: true },
			{ label: 'Purchase Order Date', name: 'purordhd_purdate', width: 20, canSearch: true, formatter: "date", formatter: dateUNFormatter },
			{ label: 'expecteddate', name: 'purordhd_expecteddate', width: 20,formatter: "date", formatter: dateUNFormatter, hidden: true },
			{ label: 'expirydate', name: 'purordhd_expirydate', width: 20,formatter: "date", formatter: dateUNFormatter, hidden: true },
			{ label: 'Supplier Code', name: 'purordhd_suppcode', width: 20, classes: 'wrap', canSearch: true},
			{ label: 'Supplier Name', name: 'supplier_name', width: 25, classes: 'wrap'},	
			{ label: 'credcode', name: 'purordhd_credcode', width: 20, classes: 'wrap',hidden:true},
			{ label: 'termsdays', name: 'purordhd_termsdays', width: 20,formatter: "date", formatter: dateUNFormatter, hidden: true },
			{ label: 'subamount', name: 'purordhd_subamount', width: 30,  hidden: true },
			{ label: 'amtdisc', name: 'purordhd_amtdisc', width: 30,  hidden: true },
			{ label: 'perdisc', name: 'purordhd_perdisc', width: 30,  hidden: true },
            { label: 'Total Amount', name: 'purordhd_totamount', width: 30 },
            { label: 'isspersonid', name: 'delordhd_isspersonid', width: 90, hidden:true, classes: 'wrap'},
            { label: 'issdate', name: 'delordhd_issdate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'authpersonid', name: 'delordhd_authpersonid', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'authdate', name: 'delordhd_authdate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'Remark', name: 'remarks', width: 50, classes: 'wrap' },
			{ label: 'Status', name: 'recstatus', width: 20 , canSearch: true},
			{ label: 'adduser', name: 'adduser', width: 90, hidden: true },
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
			(selrowData("#jqGrid").recstatus != 'POSTED') ? $('#div_for_but_post').show() : $('#div_for_but_post').hide();
			urlParam2.filterVal[0] = selrowData("#jqGrid").recno;
			urlParam2.join_filterCol = [['ivt.uomcode', 's.deptcode', 's.year'], []];
			urlParam2.join_filterVal = [['skip.s.uomcode', "skip.'" + selrowData("#jqGrid").reqdept + "'", "skip.'" + moment(selrowData("#jqGrid").reqdt).year() + "'"], []];

			refreshGrid("#jqGrid3", urlParam2);
		},
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

			urlParam2.filterVal[0] = selrowData("#jqGrid").recno;
			urlParam2.join_filterCol = [['ivt.uomcode', 's.deptcode', 's.year'], []];
			urlParam2.join_filterVal = [['skip.s.uomcode', "skip.'" + selrowData("#jqGrid").reqdept + "'", "skip.'" + moment(selrowData("#jqGrid").reqdt).year() + "'"], []];

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

			urlParam2.filterVal[0] = selrowData("#jqGrid").recno;
			urlParam2.join_filterCol = [['ivt.uomcode', 's.deptcode', 's.year'], []];
			urlParam2.join_filterVal = [['skip.s.uomcode', selrowData("#jqGrid").reqdept, moment(selrowData("#jqGrid").reqdt).year()], []];

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

	///////////////////////////////// trandate check date validate from period////////// ////////////////
	var actdateObj = new setactdate(["#trandate"]);
	actdateObj.getdata().set();

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form, selfoper, saveParam, obj) {
		if (obj == null) {
			obj = {};
		}
		saveParam.oper = selfoper;

		$.post("../../../../assets/php/entry.php?" + $.param(saveParam), $(form).serialize() + '&' + $.param(obj), function (data) {
		}, 'json').fail(function (data) {
			//////////////////errorText(dialog,data.responseText);
		}).done(function (data) {
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
	///////////////////////////////////utk dropdown tran dept/////////////////////////////////////////
	trandept(urlParam)
	function trandept(urlParam) {
		var param = {
			action: 'get_value_default',
			field: ['deptcode'],
			table_name: 'sysdb.department',
			filterCol: ['purdept'],
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

	$('#Status').on('change', searchChange);
	$('#trandept').on('change', searchChange);

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
		}, { fct: ['recstatus', 'prdept'], fv: [], fc: [] });

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		refreshGrid('#jqGrid', urlParam);
	}


	/////////////////////parameter for jqgrid2 url///////////////////////////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		field:['podt.compcode','podt.recno','podt.lineno_','podt.pricecode','podt.itemcode','p.description','podt.uomcode', 'podt.qtyorder','podt.qtydelivered','podt.unitprice','podt.taxcode','podt.amtdisc','podt.perdisc','podt.amtslstax','podt.amount','podt.expdate','podt.batchno','podt.polineno'],
		table_name:['material.purorddt podt','material.productmaster p'],
		table_id:'lineno_',
		join_type:['LEFT JOIN'],
		join_onCol:['podt.itemcode'],
		join_onVal:['p.itemcode'],
		filterCol:['podt.recno','podt.compcode','podt.recstatus'],
		filterVal:['','session.company','A']
	};

	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "../../../../assets/php/entry.php?action=purOrderDetail_save",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden: true },
			{ label: 'recno', name: 'recno', width: 50, classes: 'wrap', editable: true, hidden: true },
			{ label: 'Line No', name: 'lineno_', width: 80, classes: 'wrap', editable: true },

			{
				label: 'Price Code', name: 'pricecode', width: 110, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				//formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					//custom_element: pricecodeCustomEdit,
					//custom_value: galGridCustomValue
				},
			},
			{
				label: 'Item Code', name: 'itemcode', width: 110, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				//formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: itemcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Item Description', name: 'description', width: 200, classes: 'wrap', editable: true, editoptions: { readonly: "readonly" } },
			{
				label: 'Uom Code', name: 'uomcode', width: 110, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: uomcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{
				label: 'Quantity Ordered', name: 'qtyorder', width: 80, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true }, editoptions: { readonly: "readonly" },
			},

			{ label: 'Unit Price', name: 'unitprice', width: 80, classes: 'wrap', editable: true , align: 'right'},
            { label: 'Percentage of Discount', name: 'perdisc', width: 100, classes: 'wrap', editable: true , align: 'right'},
            { label: 'Discount per Unit', name: 'disperunit', width: 80, classes: 'wrap', editable: true , align: 'right'},

            {
				label: 'Tax Code', name: 'taxcode', width: 100, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					//custom_element: uomcodeCustomEdit,
					//custom_value: galGridCustomValue
				},
			},
            
            { label: 'Total GST Amount', name: 'totalGST', width: 80, classes: 'wrap', editable: true , align: 'right'},

            {
				label: 'Quantity Delivered', name: 'qtydelivered', width: 80, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true }, editoptions: { readonly: "readonly" },
			},

			{
				label: 'Quantity Outstanding', name: 'qtyout', width: 80, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true }, editoptions: { readonly: "readonly" },
			},
            { label: 'Total Line Amount', name: 'totalLine', width: 80, classes: 'wrap', editable: true , align: 'right'},

            {
				label: 'Supplier Item Code', name: 'uomcode', width: 110, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					//custom_element: uomcodeCustomEdit,
					//custom_value: galGridCustomValue
				},
			},
            { label: 'Cancel Back Order', name: 'cancelorder', width: 80, classes: 'wrap', editable: true , align: 'right'},
			/*{
				label: 'Qty on Hand at Req Dept', name: 'deptqtyonhand', width: 100, align: 'right', classes: 'wrap', editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true }, editoptions: { readonly: "readonly" },
			},
			{
				label: 'Qty on Hand at Req To Dept', name: 'recvqtyonhand', width: 100, align: 'right', classes: 'wrap', editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true }, editoptions: { readonly: "readonly" },
				formatter: formatter_recvqtyonhand,
			},
			{
				label: 'Qty Requested', name: 'qtyrequest', width: 80, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true }, edittype: "text",
				editoptions: {
					maxlength: 12,
					dataInit: function (element) {
						element.style.textAlign = 'right';
						$(element).keypress(function (e) {
							if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
								return false;
							}
						});
					}
				},
			},
			{ label: 'Qty Supplied', name: 'qtytxn', width: 100, align: 'right', classes: 'wrap', editable: true, editoptions: { readonly: "readonly" } },
			*/
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
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
					}
				});
			});
		},
		afterShowForm: function (rowid) {
			$("#expdate").datepicker();
		},
		beforeSubmit: function (postdata, rowid) {
			dialog_itemcode.check(errorField);
			dialog_uomcode.check(errorField);
		}
	});

	////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
	var addmore_jqgrid2 = false // if addmore is true, add after refresh jqgrid2
	var myEditOptions = {
		keys: true,
		oneditfunc: function (rowid) {
		},
		aftersavefunc: function (rowid, response, options) {
			addmore_jqgrid2 = true; //only addmore after save inline
			refreshGrid('#jqGrid2', urlParam2, 'add');
			$("#jqGridPager2Delete").show();
		},
		beforeSaveRow: function (options, rowid) {
			var qtyrequest = parseInt($("input[id*='qtyrequest']").val());
			var recvqtyonhand = parseInt($("input[id*='recvqtyonhand']").val());

			if (qtyrequest > recvqtyonhand) {
				bootbox.alert("Quantity request cannot be greater than quanntity on hand")
				return false;
			}

			let editurl = "../../../../assets/php/entry.php?" +
				$.param({
					action: 'stockReq_detail_save',
					ivreqno: $('#ivreqno').val(),
					recno: $('#recno').val(),
					reqdept: $('#reqdept').val()
				});
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
			case 'pricecode':field=['pricecode','description'];table="material.pricesource";break;
			case 'taxcode':field=['taxcode','description'];table="hisdb.taxmast";break;
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

	function formatter_recvqtyonhand(cellvalue, options, rowObject) {
		var prdept = $('#prdept').val();
		var datetrandate = new Date($('#reqdt').val());
		var getyearinput = datetrandate.getFullYear();

		var param = { action: 'get_value_default', field: ['qtyonhand'], table_name: 'material.stockloc' }

		param.filterCol = ['year', 'itemcode', 'deptcode', 'uomcode'];
		param.filterVal = [getyearinput, rowObject[3], prdept, rowObject[5]];

		$.get("../../../../assets/php/entry.php?" + $.param(param), function (data) {

		}, 'json').done(function (data) {
			if (!$.isEmptyObject(data.rows)) {
				$("#" + options.gid + " #" + options.rowId + " td:nth-child(" + (options.pos + 1) + ")").text(data.rows[0].qtyonhand);
			}
		});
		return "";
	}

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value, name) {
		var temp;
		switch (name) {
			case 'Item Code':temp=$('#itemcode');break;
			case 'Uom Code':temp=$('#uomcode');break;
			case 'Price Code':temp=$('#pricecode');break;
			case 'Tax Code':temp=$('#taxcode');break;
		}
		return (temp.parent().hasClass("has-error")) ? [false, "Please enter valid " + name + " value"] : [true, ''];
	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function itemcodeCustomEdit(val,opt){
		// val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	<--nak buang decription kat bawah 
		return $('<div class="input-group"><input id="itemcode" name="itemcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');
	}
	function pricecodeCustomEdit(val,opt){
		val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input id="pricecode" name="pricecode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function uomcodeCustomEdit(val,opt){  	
		val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input id="uomcode" name="uomcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function taxcodeCustomEdit(val,opt){
		val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input id="taxcode" name="taxcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
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
		dialog_prdept.off();
		dialog_suppcode.off();
		dialog_deldept.off();
		dialog_crecode.off();
		if ($('#formdata').isValid({ requiredFields: '' }, conf, true)) {
			saveHeader("#formdata", oper, saveParam);
			unsaved = false;
			hideatdialogForm(false);
			$('#jqGrid2_iladd').click();
		} else {
			mycurrency.formatOn();
		}
	});

	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function () {
		emptyFormdata(errorField, '#formdata2');
		hideatdialogForm(true);
		dialog_reqdept.on();
		dialog_prdept.on();
		dialog_suppcode.on();
		dialog_deldept.on();
		dialog_crecode.on();
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

		$("input[id*='_qtyrequest']").keydown(function (e) {
			var code = e.keyCode || e.which;
			if (code == '9') $('#jqGrid2_ilsave').click();
		});
	});

	////////////////////////////// jqGrid2_iladd + jqGrid2_iledit /////////////////////////////

	var mycurrency2 =new currencymode(["#jqGrid2 input[name='amtdisc']","#jqGrid2 input[name='unitprice']","#jqGrid2 input[name='amount']","#jqGrid2 input[name='tot_gst']"]);

	$("#jqGrid2_iladd, #jqGrid2_iledit").click(function(){
		unsaved = false;
		$("#jqGridPager2Delete").hide();
		dialog_pricecode.on();//start binding event on jqgrid2
		dialog_itemcode.on();
		dialog_uomcode.on();
		dialog_taxcode.on();
		$("input[name='gstpercent']").val('0')//reset gst to 0

		
		mycurrency2.formatOnBlur();//make field to currency on leave cursor

		$("#jqGrid2 input[name='qtydelivered']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='unitprice']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='amtdisc']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='taxcode']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);

		$("input[name='batchno']").keydown(function(e) {//when click tab at batchno, auto save
			var code = e.keyCode || e.which;
			if (code == '9')$('#jqGrid2_ilsave').click();
		});
	});

	////////////////////////////////////////calculate_line_totgst_and_totamt////////////////////////////
	function calculate_line_totgst_and_totamt(event){
		let qtydelivered=parseInt($("#jqGrid2 input[name='qtydelivered']").val());
		let unitprice=parseFloat($("input[name='unitprice']").val());
		let amtdisc=parseFloat($("input[name='amtdisc']").val());
		let gstpercent=parseFloat($("input[name='gstpercent']").val());

		var tot_gst = gstpercent / 100 * (unitprice-amtdisc) * qtydelivered;
		var amount = ((unitprice-amtdisc) * qtydelivered) + tot_gst;

		var netunitprice = (unitprice-amtdisc) + ((unitprice-amtdisc) *gstpercent/100);//?

		$("input[name='tot_gst']").val(tot_gst);
		$("input[name='amount']").val(amount);
		event.data.currency.formatOn();//change format to currency on each calculation
	}


	///////////////////////////////////////// QtyOnHand Dept ////////////////////////////////////////////
	function getQOHReqDept(from_selecting_uomcode) {
		var reqdept = $('#reqdept').val();
		var datetrandate = new Date($('#reqdt').val());
		var getyearinput = datetrandate.getFullYear();

		var param = {
			func: 'getQOHReqDept',
			action: 'get_value_default',
			field: ['qtyonhand', 'maxqty'],
			table_name: 'material.stockloc'
		}

		param.filterCol = ['year', 'itemcode', 'deptcode', 'uomcode',];
		param.filterVal = [getyearinput, $("#jqGrid2 input[name='itemcode']").val(), reqdept, $("#jqGrid2 input[name='uomcode']").val()];

		$.get("../../../../assets/php/entry.php?" + $.param(param), function (data) {
			$("#jqGrid2 input[name='deptqtyonhand'],#jqGrid2 input[name='maxqty']").val('');
		}, 'json').done(function (data) {
			if (!$.isEmptyObject(data.rows) && data.rows[0].qtyonhand != null && data.rows[0].maxqty != null) {
				$("#jqGrid2 input[name='deptqtyonhand']").val(data.rows[0].qtyonhand);
				$("#jqGrid2 input[name='maxqty']").val(data.rows[0].maxqty);
				if (from_selecting_uomcode) getQOHprdept();
			} else {
				bootbox.confirm({
					message: "No stock location at department code: " + $('#reqdept').val() + "... Proceed? ",
					buttons: {
						confirm: { label: 'Yes', className: 'btn-success', }, cancel: { label: 'No', className: 'btn-danger' }
					},
					callback: function (result) {
						if (!result) {
							$("#jqGrid2_ilcancel").click();
						} else {
							if (from_selecting_uomcode) getQOHprdept();
						}
					}
				});
			}
		});
	}

	///////////////////////////////////////// QtyOnHand Recv/////////////////////////////////////////////
	function getQOHprdept() {
		var prdept = $('#prdept').val();
		var datetrandate = new Date($('#reqdt').val());
		var getyearinput = datetrandate.getFullYear();

		var param = {
			func: 'getQOHprdept',
			action: 'get_value_default',
			field: ['qtyonhand'],
			table_name: 'material.stockloc'
		}

		param.filterCol = ['year', 'itemcode', 'deptcode', 'uomcode'];
		param.filterVal = [getyearinput, $("#jqGrid2 input[name='itemcode']").val(), prdept, $("#jqGrid2 input[name='uomcode']").val()];

		$.get("../../../../assets/php/entry.php?" + $.param(param), function (data) {
			$("#jqGrid2 input[name='recvqtyonhand']").val('');
		}, 'json').done(function (data) {
			if (!$.isEmptyObject(data.rows) && data.rows[0].qtyonhand != null) {
				$("#jqGrid2 input[name='recvqtyonhand']").val(data.rows[0].qtyonhand);
			} else {
				bootbox.confirm({
					message: "No stock location at department code: " + $('#prdept').val() + "... Proceed? ",
					buttons: {
						confirm: { label: 'Yes', className: 'btn-success', }, cancel: { label: 'No', className: 'btn-danger' }
					},
					callback: function (result) {
						if (!result) {
							$("#jqGrid2_ilcancel").click();
						} else {

						}
					}
				});
			}
		});
	}

	////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////
	$("#jqGrid3").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2").jqGrid('getGridParam', 'colModel'),
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3",
	});
	jqgrid_label_align_right("#jqGrid3");


	////////////////////////////////////////////////////ordialog////////////////////////////////////////
	var dialog_reqdept = new ordialog(
		'reqdept', 'sysdb.department', '#reqdept', errorField,
		{
			colModel: [
				{ label: 'Department', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			]
		}, {
			title: "Select Request Department",
		}
	);
	dialog_reqdept.makedialog();

	var dialog_prdept = new ordialog(
		'prdept', 'sysdb.department', '#prdept', errorField,
		{
			colModel: [
				{ label: 'Department', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			]
		}, {
			title: "Select Purchase Made To Department",
			open: function () {
				dialog_prdept.urlParam.filterCol = ['purdept'];
				dialog_prdept.urlParam.filterVal = ['1'];
			}
		}
	);
	dialog_prdept.makedialog();

	var dialog_deldept = new ordialog(
		'deldept', 'material.deldept', '#deldept', errorField,
		{
			colModel: [
				{ label: 'Department', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			]
		}, {
			title: "Select Delivery Department",
			open: function () {
				//dialog_deldept.urlParam.filterCol = ['deldept'];
				//dialog_deldept.urlParam.filterVal = ['1'];
			}
		}
	);
	dialog_deldept.makedialog();

	
	var dialog_suppcode = new ordialog(
		'suppcode', 'material.supplier', '#suppcode', errorField,

		{
			colModel: [
				{ label: 'Supplier Code', name: 'suppcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			]
		}, {
			title: "Select Supplier",
			open: function () {
				dialog_suppcode.urlParam.filterCol = ['recstatus'];
				dialog_suppcode.urlParam.filterVal = ['A'];
			}
		}
	);
	dialog_suppcode.makedialog();

	var dialog_crecode = new ordialog(
		'credcode', 'material.supplier', '#suppcode', errorField,

		{
			colModel: [
				{ label: 'Supplier Code', name: 'suppcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			]
		}, {
			title: "Select Supplier",
			open: function () {
				dialog_crecode.urlParam.filterCol = ['recstatus'];
				dialog_crecode.urlParam.filterVal = ['A'];
			}
		}
	);
	dialog_crecode.makedialog();

	

	var dialog_itemcode = new ordialog(
		'itemcode', ['material.stockloc s', 'material.product p'], "#jqGrid2 input[name='itemcode']", errorField,
		{
			colModel:
			[
				{ label: 'Item Code', name: 's.itemcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'p.description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Quantity On Hand', name: 's.qtyonhand', width: 100, classes: 'pointer', },
				{ label: 'UOM Code', name: 's.uomcode', width: 100, classes: 'pointer' },
				{ label: 'Max Quantity', name: 's.maxqty', width: 100, classes: 'pointer' },
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_itemcode.gridname);
				$("#jqGrid2 input[name='itemcode']").val(data['s.itemcode']);
				$("#jqGrid2 input[name='description']").val(data['p.description']);
				$("#jqGrid2 input[name='uomcode']").val(data['s.uomcode']);
				$("#jqGrid2 input[name='maxqty']").val(data['s.maxqty']);
				$("#jqGrid2 input[name='deptqtyonhand']").val(data['s.qtyonhand']);
				dialog_uomcode.check(errorField);
				getQOHReqDept(true);
			}
		}, {
			title: "Select Item For Stock Request",
			open: function () {
				dialog_itemcode.urlParam.table_id = "none_";
				dialog_itemcode.urlParam.filterCol = ['s.compcode', 's.year', 'deptcode'];
				dialog_itemcode.urlParam.filterVal = ['session.company', moment($('#reqdt').val()).year(), $('#reqdept').val()];
				dialog_itemcode.urlParam.join_type = ['LEFT JOIN'];
				dialog_itemcode.urlParam.join_onCol = ['s.itemcode'];
				dialog_itemcode.urlParam.join_onVal = ['p.itemcode'];
				dialog_itemcode.urlParam.join_filterCol = [['s.compcode']];
				dialog_itemcode.urlParam.join_filterVal = [['skip.p.compcode']];
			}
		}
	);
	dialog_itemcode.makedialog(false);
	//false means not binding event on jqgrid2 yet, after jqgrid2 add, event will be bind

	var dialog_uomcode = new ordialog(
		'uom',['material.stockloc s','material.uom u'],"#jqGrid2 input[name='uomcode']",errorField,
		{	colModel:
			[
				{label:'UOM code',name:'s.uomcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'u.description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Department code',name:'s.deptcode',width:150,classes:'pointer'},
				{label:'Item code',name:'s.itemcode',width:150,classes:'pointer'},
			],
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_uomcode.gridname);
				$("#jqGrid2 input[name='uomcode']").val(data['s.uomcode']);
			}
		},{
			title:"Select UOM Code For Item",
			open:function(){
				dialog_uomcode.urlParam.table_id="none_";
				dialog_uomcode.urlParam.filterCol=['s.compcode','s.deptcode','s.itemcode','s.year'];
				dialog_uomcode.urlParam.filterVal=['session.company',$('#delordhd_deldept').val(),$("#jqGrid2 input[name='itemcode']").val(),moment($('#delordhd_trandate').val()).year()];
				dialog_uomcode.urlParam.join_type=['LEFT JOIN'];
				dialog_uomcode.urlParam.join_onCol=['s.uomcode'];
				dialog_uomcode.urlParam.join_onVal=['u.uomcode'];
				dialog_uomcode.urlParam.join_filterCol=[['s.compcode']];
				dialog_uomcode.urlParam.join_filterVal=[['skip.u.compcode']];
			},
			close: function(){
				$(dialog_uomcode.textfield)			//lepas close dialog focus on next textfield 
					.closest('td')						//utk dialog dalam jqgrid jer
					.next()
					.find("input[type=text]").focus();
			}
		},'urlParam'
	);
	dialog_uomcode.makedialog(false);

	var dialog_taxcode = new ordialog(
		'taxcode',['hisdb.taxmast'],"#jqGrid2 input[name='taxcode']",errorField,
		{	colModel:
			[
				{label:'Tax code',name:'taxcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'rate',name:'rate',width:400,classes:'pointer',hidden:true},
			],
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_taxcode.gridname);
				$('#gstpercent').val(data['rate']);
				$(dialog_taxcode.textfield).closest('td').next().has("input[type=text]").focus();
			}
		},{
			title:"Select Tax Code For Item",
			open: function(){
				dialog_taxcode.urlParam.filterCol=['compcode','recstatus'];
				dialog_taxcode.urlParam.filterVal=['session.company','A'];
			},
			close: function(){
				$(dialog_taxcode.textfield)			//lepas close dialog focus on next textfield 
					.closest('td')						//utk dialog dalam jqgrid jer
					.next()
					.find("input[type=text]").focus();
			}
		},'urlParam'
	);
	dialog_taxcode.makedialog(false);

	/*var genpdf = new generatePDF('#pdfgen1','#formdata','#jqGrid2');
	genpdf.printEvent(); */

});
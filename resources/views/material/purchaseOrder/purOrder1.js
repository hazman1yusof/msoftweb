
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
						$("#purordhd_prdept").val($("#x").val());
						backdated();
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
					dialog_credcode.check(errorField);
					//dialog_reqdept.check(errorField);
					//dialog_purreqno.check(errorField);
					dialog_prdept.check(errorField);

					dialog_suppcode.check(errorField);
					dialog_deldept.check(errorField);
				} if (oper != 'view') {
					dialog_reqdept.on();
					dialog_purreqno.on();
					dialog_prdept.on();
					dialog_suppcode.on();
					dialog_deldept.on();
					dialog_credcode.on();
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
				//dialog_authorise.off();
				//dialog_reqdept.off();
				//dialog_prdept.off();
				//dialog_suppcode.off();
				//dialog_deldept.off();
				$(".noti").empty();
				$("#refresh_jqGrid").click();
			},
		});
	////////////////////////////////////////end dialog///////////////////////////////////////////////////

	/////////////////////parameter for jqgrid url////////////////////////////////////////////////////////
	var urlParam = {
		action: 'get_table_default',
		fixPost: 'true',
		field: ['purordhd.recno', 'purordhd.prdept', 'purordhd.purordno'],
		table_name: ['material.purordhd', 'material.supplier'],
		table_id: 'purordhd_idno',
		sort_idno: true,
		join_type: ['LEFT JOIN'],
		join_onCol: ['supplier.SuppCode'],
		join_onVal: ['purordhd.suppcode'],
		filterCol: ['purordhd.compcode','purordhd.prdept'],
		filterVal: ['skip.supplier.CompCode',$('#x').val()],
		
				
	}
	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam = {
		action: 'purOrder_header_save',
		field: '',
		oper: oper,
		table_name: 'material.purordhd',
		table_id: 'recno',
		fixPost: 'true',
	};

	function padzero(cellvalue, options, rowObject){
		let padzero = 5, str="";
		while(padzero>0){
			str=str.concat("0");
			padzero--;
		}
		return pad(str, cellvalue, true);
	}

	function unpadzero(cellvalue, options, rowObject){
		return cellvalue.substring(cellvalue.search(/[1-9]/));
	}

	/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Record No', name: 'purordhd_recno', width: 10, canSearch: true, selected: true },
			{ label: 'Purchase Department', name: 'purordhd_prdept', width: 15, classes: 'wrap' },
			{ label: 'Purchase Order No', name: 'purordhd_purordno', width: 10, classes: 'wrap', canSearch: true, formatter: padzero, unformat: unpadzero },
			{ label: 'Req No', name: 'purordhd_purreqno', width: 20, hidden: true },
			{ label: 'DelordNo', name: 'purordhd_delordno', width: 20, width: 10, classes: 'wrap' },
			{ label: 'Request Department', name: 'purordhd_reqdept', width: 30, hidden: true },
			{ label: 'deldept', name: 'purordhd_deldept', width: 30, hidden: true },
			{ label: 'Purchase Order Date', name: 'purordhd_purdate', width: 15, canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'expecteddate', name: 'purordhd_expecteddate', width: 20, formatter: dateFormatter, unformat: dateUNFormatter, hidden: true },
			{ label: 'expirydate', name: 'purordhd_expirydate', width: 20, formatter: "date", hidden: true },
			{ label: 'Supplier Code', name: 'purordhd_suppcode', width: 15, classes: 'wrap' },
			{ label: 'Supplier Name', name: 'supplier_name', width: 35, classes: 'wrap', canSearch: true },
			{ label: 'credcode', name: 'purordhd_credcode', width: 20, classes: 'wrap', hidden: true },
			{ label: 'termsdays', name: 'purordhd_termdays', width: 20, hidden: true },
			{ label: 'subamount', name: 'purordhd_subamount', width: 30, hidden: true },
			{ label: 'amtdisc', name: 'purordhd_amtdisc', width: 30, hidden: true },
			{ label: 'perdisc', name: 'purordhd_perdisc', width: 30, hidden: true },
			{ label: 'Total Amount', name: 'purordhd_totamount', width: 15, align: 'right', formatter: 'currency' },
			{ label: 'isspersonid', name: 'purordhd_isspersonid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'issdate', name: 'purordhd_issdate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'authpersonid', name: 'purordhd_authpersonid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'authdate', name: 'purordhd_authdate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'Remark', name: 'purordhd_remarks', width: 50, classes: 'wrap', hidden: true },
			{ label: 'Status', name: 'purordhd_recstatus', width: 10 },
			{ label: 'taxclaimable', name: 'purordhd_taxclaimable', width: 40, hidden:'true'},
			{ label: 'adduser', name: 'purordhd_adduser', width: 90, hidden: true },
			{ label: 'adddate', name: 'purordhd_adddate', width: 90, hidden: true },
			{ label: 'upduser', name: 'purordhd_upduser', width: 90, hidden: true },
			{ label: 'upddate', name: 'purordhd_upddate', width: 90, hidden: true },
			{ label: 'idno', name: 'purordhd_idno', width: 90, hidden: true },

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
			let stat = selrowData("#jqGrid").purordhd_recstatus;
			switch($("#scope").val()){
				case "dataentry":
					break;
				case "cancel": 
					if(stat=='POSTED'){
						$('#but_cancel_jq').show();
						$('#but_post_jq,#but_reopen_jq').hide();
					}else if(stat=="CANCELLED"){
						$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
					}else{
						$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
					}
					break;
				case "all": 
					if(stat=='POSTED'){
						$('#but_reopen_jq').show();
						$('#but_post_jq,#but_cancel_jq').hide();
					}else if(stat=="CANCELLED"){
						$('#but_reopen_jq').show();
						$('#but_post_jq,#but_cancel_jq').hide();
					}else{
						$('#but_cancel_jq,#but_post_jq').show();
						$('#but_reopen_jq').hide();
					}
					break;
			}

			urlParam2.filterVal[0] = selrowData("#jqGrid").purordhd_recno;
			$('#ponodepan').text(selrowData("#jqGrid").purordhd_purordno);//tukar kat depan tu
			$('#prdeptdepan').text(selrowData("#jqGrid").purordhd_prdept);
            refreshGrid("#jqGrid3", urlParam2);
		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
			$("#jqGridPager td[title='Edit Selected Row']").click();
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
	$("#jqGrid").jqGrid('setLabel', 'purordhd_totamount', 'Total Amount', { 'text-align': 'right' });

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
	addParamField('#jqGrid', false, saveParam, ['purordhd_adduser', 'purordhd_adddate', 'purordhd_idno', 'supplier_name']);

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
	// var actdateObj = new setactdate(["#purordhd_purdate"]);
	// actdateObj.getdata().set();

	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
	$("#but_cancel_jq,#but_post_jq,#but_reopen_jq").click(function(){
		saveParam.oper = $(this).data("oper");
		let obj={recno:selrowData('#jqGrid').purordhd_recno};
		$.post("../../../../assets/php/entry.php?" + $.param(saveParam),obj, function (data) {
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
			if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				addmore_jqgrid2.state = true;
				$('#jqGrid2_iladd').click();
			}
			////////////// $('#jqGrid2_iladd').click();

			if (selfoper == 'add') {
				$('#jqGrid2_iladd').click();
				oper = 'edit';//sekali dia add terus jadi edit lepas tu
				$('#purordhd_recno').val(data.recno);
				$('#purordhd_purordno').val(data.purordno);
				$('#purordhd_idno').val(data.idno);//just save idno for edit later

				urlParam2.filterVal[0] = data.recno;
			/*	urlParam2.join_filterCol = [['ivt.uomcode', 's.deptcode', 's.year'], []];
				urlParam2.join_filterVal = [['skip.s.uomcode', $('#reqdept').val(), moment($("#reqdt").val()).year()], []];*/
			} else if (selfoper == 'edit') {
				//doesnt need to do anything
			}
			disableForm('#formdata');
			hideatdialogForm(false);

		}, 'json').fail(function (data) {
			//alert(data.responseText);
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

	$('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	$('#trandept').on('change', searchChange);

	function whenchangetodate() {
		if ($('#Scol').val() == 'purordhd_purdate') {
			$("input[name='Stext']").show("fast");
			$("#tunjukname").hide("fast");
			$("input[name='Stext']").attr('type', 'date');
			$("input[name='Stext']").velocity({ width: "250px" });
			$("input[name='Stext']").on('change', searchbydate);
		} else if($('#Scol').val() == 'supplier_name'){
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

	var supplierkatdepan = new ordialog(
		'supplierkatdepan', 'material.supplier', '#supplierkatdepan', 'errorField',
		{
			colModel: [
				{ label: 'Supplier Code', name: 'suppcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			ondblClickRow: function () {
				let data = selrowData('#' + supplierkatdepan.gridname).suppcode;

				urlParam.searchCol=["purordhd_suppcode"];
				urlParam.searchVal=[data];
				refreshGrid('#jqGrid', urlParam);
			}
		},{
			title: "Select Purchase Department",
			open: function () {
				dialog_suppcode.urlParam.filterCol = ['recstatus'];
				dialog_suppcode.urlParam.filterVal = ['A'];
			}
		}
	);
	supplierkatdepan.makedialog();

	function searchbydate() {
		search('#jqGrid', $('#searchForm [name=Stext]').val(), $('#searchForm [name=Scol] option:selected').val(), urlParam);
	}

	function searchChange() {
		var arrtemp = ['skip.supplier.CompCode', $('#Status option:selected').val(), $('#trandept option:selected').val()];
		var filter = arrtemp.reduce(function (a, b, c) {
			if (b == 'All') {
				return a;
			} else {
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		}, { fct: ['purordhd.compcode', 'purordhd.recstatus', 'purordhd.prdept'], fv: [], fc: [] });

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		refreshGrid('#jqGrid', urlParam);
	}

	function searchbydate() {
		search('#jqGrid', $('#searchForm [name=Stext]').val(), $('#searchForm [name=Scol] option:selected').val(), urlParam);
	}


	function searchChange() {
		var arrtemp = ['skip.supplier.CompCode', $('#Status option:selected').val(), $('#trandept option:selected').val()];
		var filter = arrtemp.reduce(function (a, b, c) {
			if (b == 'All') {
				return a;
			} else {
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		}, { fct: ['purordhd.compcode', 'purordhd.recstatus', 'purordhd.prdept'], fv: [], fc: [] });

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		refreshGrid('#jqGrid', urlParam);

	}


	/////////////////////parameter for jqgrid2 url///////////////////////////////////////////////////////
	var urlParam2 = {
		action: 'get_table_default',
		field: ['podt.compcode', 'podt.recno', 'podt.lineno_', 'podt.suppcode', 'podt.purdate','podt.pricecode', 'podt.itemcode', 'p.description','podt.uomcode','podt.pouom','podt.qtyorder', 'podt.qtydelivered', 'podt.perslstax', 'podt.unitprice', 'podt.taxcode', 'podt.perdisc', 'podt.amtdisc','podt.amtslstax', 'podt.amount','NULL AS remarks_button','podt.remarks', 't.rate'],
		table_name: ['material.purorddt podt', 'material.productmaster p', 'hisdb.taxmast t'],
		table_id: 'lineno_',
		join_type: ['LEFT JOIN', 'LEFT JOIN'],
		join_onCol: ['podt.itemcode','podt.taxcode'],
		join_onVal: ['p.itemcode', 't.taxcode'],
		filterCol: ['podt.recno', 'podt.compcode', 'podt.recstatus'],
		filterVal: ['', 'session.company', '<>.DELETE']
	};
	var addmore_jqgrid2={more:false,state:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong

	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "../../../../assets/php/entry.php?action=purOrder_detail_save",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden: true },
			{ label: 'recno', name: 'recno', width: 50, classes: 'wrap', editable: true, hidden: true },
			{ label: 'Line No', name: 'lineno_', width: 80, classes: 'wrap', editable: true, hidden: true },
			{ label: 'suppcode', name: 'suppcode', width: 50, classes: 'wrap', editable: true, hidden: true },
			{ label: 'purdate', name: 'purdate', width: 80, classes: 'wrap', editable: true, hidden: true },

			{
				label: 'Price Code', name: 'pricecode', width: 110, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: pricecodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
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
			{ label: 'Item Description', name: 'description', width: 220, classes: 'wrap', editable: true, editoptions: { readonly: "readonly" } },
			
			{
				label: 'Uom Code', name: 'uomcode', width: 150, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: uomcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{
				label: 'PO UOM', name: 'pouom', width: 150, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: pouomCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{
				label: 'Quantity Ordered', name: 'qtyorder', width: 100, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true, custom: true, custom_func: cust_rules }, edittype: "text",
						editoptions:{
						maxlength: 12,
						dataInit: function(element) {
							element.style.textAlign = 'right';
							$(element).keypress(function(e){
								if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
								//if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
								return false;
								 }
							});
						}
					},
			},

			{
				label: 'Quantity Delivered', name: 'qtydelivered', width: 100, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true }, editoptions: { readonly: "readonly"}
			},

			{
				label: 'Quantity Outstanding', name: 'qtyOutstand', width: 130, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
				editrules: { required: true }, editoptions: { readonly: "readonly"}
			},


			{
				label: 'Unit Price', name: 'unitprice', width: 90, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 4, },
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
			{
				label: 'Tax Code', name: 'taxcode', width: 150, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: taxcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},

			{
				label: 'Percentage Discount', name: 'perdisc', width: 120, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 4, },
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

			{
				label: 'Discount Per Unit', name: 'amtdisc', width: 100, align: 'right', classes: 'wrap',
				editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 4, },
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
			{
				label: 'Total GST Amount', name: 'tot_gst', width: 100, align: 'right', classes: 'wrap', editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 4, },
				editrules:{required: true},edittype:"text",
						editoptions:{
						maxlength: 12,
						dataInit: function(element) {
							element.style.textAlign = 'right';
							$(element).keypress(function(e){
								if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
									return false;
								 }
							});
						}
					},
			},
			{
				label: 'Total Line Amount', name: 'amount', width: 100, align: 'right', classes: 'wrap', editable: true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 4, },
				editrules: { required: true }, editoptions: { readonly: "readonly" },
			},
			{ label: 'Remarks', name: 'remarks_button', width: 100, formatter: formatterRemarks,unformat: unformatRemarks},
			{ label: 'Remarks', name: 'remarks', width: 100, classes: 'wrap',hidden:true},
			{ label: 'rate', name: 'rate', width: 60, classes: 'wrap',editable: true,hidden:true},


			
		],
		autowidth: false,
		shrinkToFit: false,
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
			if(addmore_jqgrid2.more == true)$('#jqGrid2_iladd').click();
			addmore_jqgrid2.more = false; //only addmore after save inline
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
							$("#jqGrid2").jqGrid("clearGridData", true);
							refreshGrid("#jqGrid2", urlParam2);
						}
						linenotoedit = null;
					}
				});
			});
		
			$("#jqGrid2").find(".remarks_button").on("click", function(e){
				$("#remarks2").data('lineno',$(this).data('lineno'));
				$("#remarks2").data('grid',"#jqGrid2");
				$("#dialog_remarks").dialog( "open" );
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

	var linenotoedit=null;
	function formatterRemarks(cellvalue, options, rowObject){
		return "<button class='remarks_button btn btn-success btn-xs' type='button' data-lineno='"+rowObject[2]+"' data-remarks='"+rowObject[12]+"'><i class='fa fa-file-text-o'></i> remark</button>";
	}

	function unformatRemarks(cellvalue, options, rowObject){
		return null;
	}

	var butt1_rem = 
		[{
			text: "Save",click: function() {
				let newval = $("#remarks2").val();
				$("#jqGrid2").jqGrid('setRowData', linenotoedit ,{remarks:newval});
				$(this).dialog('close');
			}
		},{
			text: "Cancel",click: function() {
				$(this).dialog('close');
			}
		}];

	var butt2_rem = 
		[{
			text: "Close",click: function() {
				$(this).dialog('close');
			}
		}];

	$("#dialog_remarks").dialog({
		autoOpen: false,
		width: 4/10 * $(window).width(),
		modal: true,
		open: function( event, ui ) {
			let lineno_use = ($('#remarks2').data('lineno')!='undefined')?$('#remarks2').data('lineno'):linenotoedit;
			$('#remarks2').val($($('#remarks2').data('grid')).jqGrid ('getRowData', lineno_use).remarks);

			if(linenotoedit == lineno_use){
				$("#remarks2").prop('disabled',false);
				$( "#dialog_remarks" ).dialog( "option", "buttons", butt1_rem);
			}else{
				$("#remarks2").prop('disabled',true);
				$( "#dialog_remarks" ).dialog( "option", "buttons", butt2_rem);
			}
		},
		buttons : butt2_rem
	});

	//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
	//var addmore_jqgrid2 = false // if addmore is true, add after refresh jqgrid2
	var myEditOptions = {
		keys: true,
		oneditfunc: function (rowid) {

			linenotoedit = rowid;
        	$("#jqGrid2").find(".remarks_button[data-lineno!='"+linenotoedit+"']").prop("disabled", true);
        	$("#jqGrid2").find(".remarks_button[data-lineno='undefined']").prop("disabled", false);
		},
		aftersavefunc: function (rowid, response, options) {
			$('#purordhd_totamount').val(response.responseText);
			$('#purordhd_subamount').val(response.responseText);
			if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
			refreshGrid('#jqGrid2', urlParam2, 'add');
			$("#jqGridPager2Delete").show();
		},
		beforeSaveRow: function (options, rowid) {
			mycurrency2.formatOff();
			let editurl = "../../../../assets/php/entry.php?" +
				$.param({
					action: 'purOrder_detail_save',
					//docno:$('#delordhd_docno').val(),
					recno: $('#purordhd_recno').val(),
					reqdept: $('#purordhd_reqdept').val(),
					purreqno: $('#purordhd_purreqno').val(),
					suppcode: $('#purordhd_suppcode').val(),
					purdate: $('#purordhd_purdate').val(),
					remarks:selrowData('#jqGrid2').remarks//bug will happen later because we use selected row
				});


			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
			//calculate_conversion_factor();	
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
				    	if(result == true){
				    		param={
				    			action: 'purOrder_detail_save',
								recno: $('#purordhd_recno').val(),
								lineno_: selrowData('#jqGrid2').lineno_,
				    		}
				    		$.post( "../../../../assets/php/entry.php?"+$.param(param),{oper:'del'}, function( data ){
							}).fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
								$('#amount').val(data);
								refreshGrid("#jqGrid2",urlParam2);
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
			//case 'itemcode':field=['itemcode'];table="material.product";break;
			case 'uomcode': field = ['uomcode', 'description']; table = "material.uom"; break;
			case 'pricecode': field = ['pricecode', 'description']; table = "material.pricesource"; break;
			case 'taxcode': field = ['taxcode', 'description']; table = "hisdb.taxmast"; break;
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

	// function formatter_recvqtyonhand(cellvalue, options, rowObject) {
	// 	var prdept = $('#prdept').val();
	// 	var datetrandate = new Date($('#reqdt').val());
	// 	var getyearinput = datetrandate.getFullYear();

	// 	var param = { action: 'get_value_default', field: ['qtyonhand'], table_name: 'material.stockloc' }

	// 	param.filterCol = ['year', 'itemcode', 'deptcode', 'uomcode'];
	// 	param.filterVal = [getyearinput, rowObject[3], prdept, rowObject[5]];

	// 	$.get("../../../../assets/php/entry.php?" + $.param(param), function (data) {

	// 	}, 'json').done(function (data) {
	// 		if (!$.isEmptyObject(data.rows)) {
	// 			$("#" + options.gid + " #" + options.rowId + " td:nth-child(" + (options.pos + 1) + ")").text(data.rows[0].qtyonhand);
	// 		}
	// 	});
	// 	return "";
	// }

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value, name) {
		var temp;
		switch (name) {
			case 'Item Code': temp = $('#itemcode'); break;
			case 'Uom Code': temp = $('#uomcode'); break;
			case 'PO UOM': temp = $('#pouom'); break;
			case 'Price Code': temp = $('#pricecode'); break;
			case 'Tax Code': temp = $('#taxcode'); break;
			// case 'Qty on Hand at Req To Dept': temp = $("#jqGrid2 input[name='reqmadeqtyonhand']"); 
			// 	$("#jqGrid2 input[name='reqmadeqtyonhand']").hasClass("error");
			// 	break;
			case 'Quantity Ordered': temp = $("#jqGrid2 input[name='qtyorder']"); 
				$("#jqGrid2 input[name='qtyorder']").hasClass("error");
				break;
		}
		return (temp.parent().hasClass("has-error")) ? [false, "Please enter valid " + name + " value"] : [true, ''];

	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function itemcodeCustomEdit(val, opt) {
			val = (val == "undefined") ? "" : val
		return $('<div class="input-group"><input id="itemcode" name="itemcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function pricecodeCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input id="pricecode" name="pricecode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function uomcodeCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input id="uomcode" name="uomcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function pouomCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input id="pouom" name="pouom" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function taxcodeCustomEdit(val, opt) {
		val = (val == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input id="taxcode" name="taxcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
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
		dialog_purreqno.off();
		dialog_prdept.off();
		dialog_suppcode.off();
		dialog_credcode.off();
		dialog_deldept.off();
		//dialog_crecode.off();
		if ($('#formdata').isValid({ requiredFields: '' }, conf, true)) {
			saveHeader("#formdata", oper, saveParam);
		} else {
			mycurrency.formatOn();
			dialog_prdept.on();
			dialog_reqdept.on();
			dialog_purreqno.on();
		    dialog_suppcode.on();
		    dialog_credcode.on();
		    dialog_deldept.on();

		}
	});


	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function () {
		emptyFormdata(errorField, '#formdata2');
		hideatdialogForm(true);
		dialog_reqdept.on();
		dialog_purreqno.on();
		dialog_prdept.on();
		dialog_suppcode.on();
		dialog_credcode.on();
		dialog_deldept.on();

		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		refreshGrid("#jqGrid2", urlParam2);
	});

	////////////////////////////// jqGrid2_iladd + jqGrid2_iledit /////////////////////////////

	var mycurrency2 = new currencymode(["#jqGrid2 input[name='amtdisc']", "#jqGrid2 input[name='unitprice']", "#jqGrid2 input[name='amount']", "#jqGrid2 input[name='tot_gst']"]);

	$("#jqGrid2_iladd, #jqGrid2_iledit").click(function () {
		unsaved = false;
		$("#jqGridPager2Delete").hide();
		dialog_pricecode.on();//start binding event on jqgrid2
		dialog_itemcode.on();
		dialog_uomcode.on();
		dialog_pouom.on();
		dialog_taxcode.on();
		dialog_pouom.on();
		//$("input[name='rate']").val('0')//reset gst to 0


		mycurrency2.formatOnBlur();//make field to currency on leave cursor

        $("#jqGrid2 input[name='qtyorder']").on('blur', { currency: mycurrency2 },calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='qtydelivered']").on('blur', { currency: mycurrency2 }, calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='qtyOutstand']").on('blur', { currency: mycurrency2 }, 
			calculate_quantity_outstanding);
		$("#jqGrid2 input[name='unitprice']").on('blur', { currency: mycurrency2 }, calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='amtdisc']").on('blur', { currency: mycurrency2 }, calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='taxcode']").on('blur', { currency: mycurrency2 }, calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='itemcode']").on('blur', { currency: mycurrency2 }, calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='qtyorder']").on('blur',  calculate_conversion_factor);
		
		
		


		$("input[name='tot_gst']").keydown(function (e) {//when click tab at tot_gst, auto save
			var code = e.keyCode || e.which;
			if (code == '9') $('#jqGrid2_ilsave').click();
			//calculate_conversion_factor();
			//alert("Tab pressed");

		});
	});

    function getQOHPrDept(){
		var param={
			func:'getQOHPrDept',
			action:'get_value_default',
			field:['qtyonhand'],
			table_name:'material.stockloc'
		}

		param.filterCol = ['year','itemcode', 'deptcode','uomcode'];
		param.filterVal = [moment($('#purdate').val()).year(), $("#jqGrid2 input[name='itemcode']").val(),$('#purordhd_deldept').val(), $("#jqGrid2 input[name='uomcode']").val()];

		$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
			//$("#jqGrid2 input[name='recvqtyonhand']").val('');
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows) && data.rows[0].qtyonhand!=null){
				//$("#jqGrid2 input[name='recvqtyonhand']").val(data.rows[0].qtyonhand);
			}else if($("#purordhd_deldept").val()!=''){
				bootbox.confirm({
				    message: "No stock location at department code: "+$('#purordhd_deldept').val()+"... Proceed? ",
				    buttons: {confirm: {label: 'Yes', className: 'btn-success',},cancel: {label: 'No', className: 'btn-danger' }
				    },
				    callback: function (result) {
				    	if(!result){
				    		$("#jqGrid2_ilcancel").click();
				    	}else{
							
				    	}
				    }
				});
			}else{
				
			}
		});
	}

	/////////////////////////////// test get days for backdated //////////////////////////////////////////////
	function backdated(){
		var param={
			func:'backdated',
			action:'get_value_default',
			field:['backday'],
			table_name:'material.sequence'
		}

		param.filterCol = ['dept','trantype'];
		param.filterVal = [$("#purordhd_prdept").val(),'PO'];

		$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
			//$("#jqGrid2 input[name='recvqtyonhand']").val('');
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows)){
				$("#backday").val(data.rows[0].backday);

				//$("#jqGrid2 input[name='recvqtyonhand']").val(data.rows[0].qtyonhand);
			}else{
				
			}
		});
	}

	/*function getQOHReqDept(from_selecting_uomcode) {
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
*/

    function calculate_conversion_factor(event) {

		console.log("balconv");


		var id="#jqGrid2 input[name='qtyorder']"
		var fail_msg = "Please Choose Suitable UOMCode & POUOMCode"
		var name = "calculate_conversion_factor";

		let convfactor_bool = false;
		let convfactor_uom = parseFloat($("#convfactor_uom").val());
		let convfactor_pouom = parseFloat($("#convfactor_pouom").val());

		let qtyorder = parseFloat($("#jqGrid2 input[name='qtyorder']").val());

		console.log(convfactor_uom);
		console.log(convfactor_pouom);

		var balconv = convfactor_pouom*qtyorder%convfactor_uom;

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

		
			
		//event.data.currency.formatOn();//change format to currency on each calculation
		
	}



	////////////////////////////////////////calculate_line_totgst_and_totamt////////////////////////////
	function calculate_line_totgst_and_totamt(event) {

		let qtyorder = parseFloat($("#jqGrid2 input[name='qtyorder']").val());
		let unitprice = parseFloat($("#jqGrid2 input[name='unitprice']").val());
		let amtdisc = parseFloat($("#jqGrid2 input[name='amtdisc']").val());
		let perdisc = parseFloat($("#jqGrid2 input[name='perdisc']").val());
		let rate = parseFloat($("#jqGrid2 input[name='rate']").val());

  

		var amount = ((unitprice*qtyorder) - (amtdisc*qtyorder) );
		var getDis = amount - (amount*perdisc/100);
		var tot_gst = getDis * (rate / 100);
		var totalAmount = getDis + tot_gst;
		
		$("input[name='tot_gst']").val(tot_gst);
		$("input[name='amount']").val(totalAmount);
		event.data.currency.formatOn();//change format to currency on each calculation

	}
	
	function calculate_quantity_outstanding(event){
        let qtyorder = parseFloat($("#jqGrid2 input[name='qtyorder']").val());
        let qtydelivered = parseFloat($("#jqGrid2 input[name='qtydelivered']").val());

        var qtyOutstand = (qtyorder - qtydelivered);

        $("input[name='qtyOutstand']").val(qtyOutstand);

	}

	function searchClick2(grid,form,urlParam){
		$(form+' [name=Stext]').on( "keyup", function() {
			delay(function(){
				search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				$('#ponodepan').text("");//tukar kat depan tu
				$('#prdeptdepan').text("");
				refreshGrid("#jqGrid3",null,"kosongkan");
			}, 500 );
		});

		$(form+' [name=Scol]').on( "change", function() {
			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
			$('#ponodepan').text("");//tukar kat depan tu
			$('#prdeptdepan').text("");
			refreshGrid("#jqGrid3",null,"kosongkan");
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
		gridComplete: function(){
			$("#jqGrid3").find(".remarks_button").on("click", function(e){
				$('#remarks2').val($('#jqGrid3').jqGrid ('getRowData', $(this).data('lineno')).remarks);
				$("#remarks2").data('lineno',$(this).data('lineno'))
				$("#dialog_remarks").dialog( "open" );
			});
		},
	});
	jqgrid_label_align_right("#jqGrid3");


	////////////////////////////////////////////////////ordialog////////////////////////////////////////
	var dialog_reqdept = new ordialog(
		'reqdept', 'sysdb.department', '#purordhd_reqdept', errorField,
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

	var dialog_purreqno = new ordialog(
		'purreqno',['material.purreqhd h'],'#purordhd_purreqno',errorField,
		{	colModel:[
				{label:'Request No',name:'h.purreqno',width:10,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Request Department', name: 'h.reqdept', width: 100, classes: 'pointer',hidden:true },
				{label:'Supplier Code',name:'h.suppcode',width:400,classes:'pointer',hidden:true},
				{label:'Purchase Department',name:'h.prdept',width:400,classes:'pointer',hidden:true},
				{label:'PerDisc',name:'h.perdisc',width:400,classes:'pointer',hidden:true},
				{label:'AmtDisc',name:'h.amtdisc',width:400,classes:'pointer',hidden:true},
				{label:'Total Amount',name:'h.totamount',width:400,classes:'pointer',hidden:true},
				{label:'Sub Amount',name:'h.subamount',width:400,classes:'pointer',hidden:true},
				{label:'Status',name:'h.recstatus',width:400,classes:'pointer',hidden:true},
				{label:'Remark',name:'h.remarks',width:400,classes:'pointer',hidden:true},
				{label:'recno',name:'h.recno',width:400,classes:'pointer',hidden:true}
				


				],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_purreqno.gridname);
				$("#purordhd_purreqno").val(data['h.purreqno']);
				$("#purordhd_reqdept").val(data['h.reqdept']);
				$("#purordhd_suppcode").val(data['h.suppcode']);
				$("#purordhd_credcode").val(data['h.suppcode']);
				$("#purordhd_prdept").val(data['h.prdept']);
				$("#purordhd_perdisc").val(data['h.perdisc']);
				$("#purordhd_amtdisc").val(data['h.amtdisc']);
				$("#purordhd_totamount").val(data['h.totamount']);
				$("#purordhd_subamount").val(data['h.subamount']);
				$("#purordhd_recstatus").val(data['h.recstatus']);
				$("#purordhd_remarks").val(data['h.remarks']);

				$('#referral').val(data['h.recno']);

				var urlParam2 = {
					action: 'get_value_default',
					field: ['prdt.compcode', 'prdt.recno', 'prdt.lineno_', 'prdt.pricecode', 'prdt.itemcode', 'p.description', 'prdt.uomcode','prdt.pouom', 'prdt.qtyrequest', 'prdt.unitprice', 'prdt.taxcode', 'prdt.perdisc', 'prdt.amtdisc', 'prdt.amtslstax', 'prdt.amount','NULL AS remarks_button','prdt.remarks','prdt.recstatus','t.rate'],
					table_name: ['material.purreqdt prdt', 'material.productmaster p', 'hisdb.taxmast t'],
					table_id: 'lineno_',
					join_type: ['LEFT JOIN', 'LEFT JOIN'],
					join_onCol: ['prdt.itemcode','prdt.taxcode'],
					join_onVal: ['p.itemcode', 't.taxcode'],
					filterCol: ['prdt.recno', 'prdt.compcode', 'prdt.recstatus'],
					filterVal: [data['h.recno'], 'session.company', '<>.DELETE']
				};

				$.get("../../../../assets/php/entry.php?" + $.param(urlParam2), function (data) {
				}, 'json').done(function (data) {
					if (!$.isEmptyObject(data.rows)) {
						data.rows.forEach(function(elem) {
							$("#jqGrid2").jqGrid('addRowData', elem['lineno_'] ,
								{
									compcode:elem['compcode'],
									recno:elem['recno'],
									lineno_:elem['lineno_'],
									pricecode:elem['pricecode'],
									itemcode:elem['itemcode'],
									description:elem['description'],
									uomcode:elem['uomcode'],
									pouom:elem['pouom'],
									qtyorder:elem['qtyrequest'],
									qtydelivered:0,
									qtyOutstand:0,
									unitprice:elem['unitprice'],
									taxcode:elem['taxcode'],
									perdisc:elem['perdisc'],
									amtdisc:elem['amtdisc'],
									tot_gst:0,
									amount:elem['amount'],
									remarks_button:null,
									remarks:elem['remarks'],
									//rate:elem['rate']
								}
							);
						});

					} else {

					}
				});


				
			}

		},{
			title:"Select Request No",
			open: function(){
				$("#jqGrid2").jqGrid("clearGridData", true);
				//dialog_purreqno.urlParam.filterCol = ['reqdept'];
				//dialog_purreqno.urlParam.filterVal = [$("#purordhd_reqdept").val()];

				dialog_purreqno.urlParam.table_id = "none_";
				dialog_purreqno.urlParam.filterCol = ['h.reqdept','h.recstatus', 'h.purordno'];
				dialog_purreqno.urlParam.filterVal = [$("#purordhd_reqdept").val(),'POSTED', '0'];
				dialog_purreqno.urlParam.join_type = ['LEFT JOIN'];
				dialog_purreqno.urlParam.join_onCol = ['h.recno'];
				dialog_purreqno.urlParam.join_onVal = ['d.recno'];
				// dialog_purreqno.urlParam.join_filterCol = [['h.reqdept'],[]];
				// dialog_purreqno.urlParam.join_filterVal = [['skip.d.reqdept'],[]];

				
			}
		},'urlParam'
	);
	dialog_purreqno.makedialog();

	var dialog_prdept = new ordialog(
		'prdept', 'sysdb.department', '#purordhd_prdept', errorField,
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
		'deldept','sysdb.department','#purordhd_deldept',errorField,
		{	colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				]
		},{
			title:"Select Receiver Department",
			open: function(){
				dialog_deldept.urlParam.filterCol=['storedept', 'recstatus'];
				dialog_deldept.urlParam.filterVal=['1', 'A'];
			}
		},'urlParam'
	);
	dialog_deldept.makedialog();



	var dialog_suppcode = new ordialog(
		'suppcode', 'material.supplier', '#purordhd_suppcode', errorField,
		{
			colModel: [
				{ label: 'Supplier Code', name: 'suppcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Supplier Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_suppcode.gridname);
				$("#purordhd_credcode").val(data['suppcode']);
			}
		}, {
			title: "Select Transaction Type",
			open: function () {
				dialog_suppcode.urlParam.filterCol = ['recstatus'];
				dialog_suppcode.urlParam.filterVal = ['A'];
			}
		}
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
		'itemcode', ['material.stockloc s', 'material.product p','hisdb.taxmast t', 'material.uom u'], "#jqGrid2 input[name='itemcode']", errorField,
		{
			colModel:
			[
				{ label: 'Item Code', name: 's.itemcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'p.description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Quantity On Hand', name: 's.qtyonhand', width: 100, classes: 'pointer', },
				{ label: 'UOM Code', name: 's.uomcode', width: 100, classes: 'pointer' },
				{ label: 'Tax Code', name: 'p.TaxCode', width: 100, classes: 'pointer' },
				{ label: 'Conversion', name: 'u.convfactor', width: 50, classes: 'pointer', hidden:true },
				{ label: 'rate', name: 't.rate', width: 100, classes: 'pointer',hidden:true },

			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_itemcode.gridname);
				$("#jqGrid2 input[name='itemcode']").val(data['s.itemcode']);
				$("#jqGrid2 input[name='description']").val(data['p.description']);
				$("#jqGrid2 input[name='uomcode']").val(data['s.uomcode']);
				$("#jqGrid2 input[name='taxcode']").val(data['p.TaxCode']);
				$("#jqGrid2 input[name='rate']").val(data['t.rate']);
				$("#convfactor_uom").val(data['u.convfactor']);
				
				//$('#gstpercent').val(data['t.rate']);

				//dialog_uomcode.check(errorField);
				getQOHPrDept(true);
			}
		}, {
			title: "Select Item For Stock Request",
			open: function () {
				dialog_itemcode.urlParam.table_id = "none_";
				dialog_itemcode.urlParam.filterCol = ['s.compcode', 's.year', 's.deptcode'];
				dialog_itemcode.urlParam.filterVal = ['session.company', moment($('#purordhd_purdate').val()).year(), $('#purordhd_deldept').val()];
				dialog_itemcode.urlParam.join_type = ['LEFT JOIN','LEFT JOIN','LEFT JOIN'];
				dialog_itemcode.urlParam.join_onCol = ['s.itemcode','p.taxcode','u.uomcode'];
				dialog_itemcode.urlParam.join_onVal = ['p.itemcode','t.taxcode','s.uomcode'];
				dialog_itemcode.urlParam.join_filterCol = [['s.compcode','s.uomcode'],[]];
				dialog_itemcode.urlParam.join_filterVal = [['skip.p.compcode','skip.p.uomcode'],[]];
			}
		}
	);
	dialog_itemcode.makedialog(false);
	//false means not binding event on jqgrid2 yet, after jqgrid2 add, event will be bind

	var dialog_uomcode = new ordialog(
		'uom', ['material.stockloc s', 'material.uom u'], "#jqGrid2 input[name='uomcode']", errorField,
		{
			colModel:
			[
				{ label: 'UOM code', name: 's.uomcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'u.description', width: 300, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Department code', name: 's.deptcode', width: 100, classes: 'pointer' },
				{ label: 'Item code', name: 's.itemcode', width: 150, classes: 'pointer' },
				{ label: 'Conversion', name: 'u.convfactor', width: 100, classes: 'pointer' }
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
				dialog_uomcode.urlParam.filterVal = ['session.company', $('#purordhd_deldept').val(), $("#jqGrid2 input[name='itemcode']").val(), moment($('#purordhd_purdate').val()).year()];
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
		'pouom', ['material.uom'], "#jqGrid2 input[name='pouom']", errorField,
		{
			colModel:
			[
				{ label: 'UOM code', name: 'uomcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Conversion', name: 'convfactor', width: 100, classes: 'pointer' }
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_pouom.gridname);
				$("#jqGrid2 input[name='pouom']").val(data['uomcode']);
				$("#convfactor_pouom").val(data['convfactor']);
			}

		}, {
			title: "Select PO UOM Code For Item",
			open: function () {
				dialog_pouom.urlParam.filterCol = ['compcode', 'recstatus'];
				dialog_pouom.urlParam.filterVal = ['session.company', 'A'];
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

	var dialog_taxcode = new ordialog(
		'taxcode', ['hisdb.taxmast'], "#jqGrid2 input[name='taxcode']", errorField,
		{
			colModel:
			[
				{ label: 'Tax code', name: 'taxcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'rate', name: 'rate', width: 200, classes: 'pointer' },
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_taxcode.gridname);
				$("#jqGrid2 input[name='rate']").val(data['rate']);
				//$('#gstpercent').val(data['rate']);
				$(dialog_taxcode.textfield).closest('td').next().has("input[type=text]").focus();
			}
		}, {
			title: "Select Tax Code For Item",
			open: function () {
				dialog_taxcode.urlParam.filterCol = ['taxtype', 'compcode', 'recstatus'];
				dialog_taxcode.urlParam.filterVal = ['input', 'session.company', 'A'];
			},
			close: function () {
				$(dialog_taxcode.textfield)			//lepas close dialog focus on next textfield 
					.closest('td')						//utk dialog dalam jqgrid jer
					.next()
					.find("input[type=text]").focus();
			}
		}, 'urlParam'
	);
	dialog_taxcode.makedialog(false);

	var dialog_credcode = new ordialog(
		'credcode', 'material.supplier', '#purordhd_credcode', errorField,
		{
			colModel: [
				{ label: 'Creditor Code', name: 'suppcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Creditor Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			]
		}, {
			title: "Select Creditor",
			open: function () {
				dialog_credcode.urlParam.filterCol = ['recstatus'];
				dialog_credcode.urlParam.filterVal = ['A'];
			}
		}, 'urlParam'
	);
	dialog_credcode.makedialog();


	var genpdf = new generatePDF('#pdfgen1','#formdata','#jqGrid2');
	genpdf.printEvent(); 
});
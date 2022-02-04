
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']","input[name='computerid']","input[name='ipaddress']");
	/////////////////////////validation//////////////////////////
	$.validate({
		modules: 'sanitize,logic',
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
	var radbuts = new checkradiobutton(['price','service','opprice']);
	var radbuts_svc = new checkradiobutton(['svc_price','svc_allitem','svc_alltype']);
	var radbuts_item = new checkradiobutton(['i_price']);
	var radbuts_type = new checkradiobutton(['t_price']);
	$("#jqGriditem_c, #jqGridtype_c, #click_row2, #click_row3").hide();

	////////////////////////////////////start dialog///////////////////////////////////////
	var butt1 = [{
		text: "Save", click: function () {
			mycurrency.formatOff();
			mycurrency.check0value(errorField);
			radbuts.check();
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

	var oper = 'add';
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
						$(this).dialog("option", "title", "Add Bill Type Master");
						enableForm('#formdata');
						hideOne('#formdata');
						rdonly("#formdata");
						rdonly("#dialogForm");
						break;
					case state = 'edit':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "Edit Bill Type Master");
						enableForm('#formdata');
						frozeOnEdit("#dialogForm");
						$('#formdata :input[hideOne]').show();
						rdonly("#formdata");
						rdonly("#dialogForm");
						break;
					case state = 'view':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "View Bill Type Master");
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
				$('.my-alert').detach();
				radbuts.reset();
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
		url: 'util/get_table_default',
		field: '',
		table_name: 'hisdb.billtymst',
		table_id: 'billtype',
		filterCol: ['compcode'],
		filterVal: ['session.compcode'],
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam = {
		action: 'save_table_default',
		url: './billtype/form',
		field: '',
		oper: oper,
		table_name: 'hisdb.billtymst',
		table_id: 'billtype',
		saveip:'true',
		checkduplicate:'true'
	};

	function searchClick2(grid,form,urlParam){
		$(form+' [name=Stext]').on( "keyup", function() {
			delay(function(){
				search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				$('#showbilltype').text("");//tukar kat depan tu
				$('#showbilldesc').text("");
				refreshGrid("#jqGridsvc",null,"kosongkan");
			}, 500 );
		});

		$(form+' [name=Scol]').on( "change", function() {
			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
			$('#showbilltype').text("");//tukar kat depan tu
			$('#showbilldesc').text("");
			refreshGrid("#jqGridsvc",null,"kosongkan");
		});
	}

	//////////////////////////start grid/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Bill Type', name: 'billtype', width: 40, classes: 'wrap', canSearch: true},
			{ label: 'Description', name: 'description', width: 90, classes: 'wrap', canSearch: true, checked: true},
			{ label: 'Price', name: 'price', width: 40, classes: 'wrap' },
			{ label: 'OP Price', name: 'opprice', width: 40 ,formatter: formatter, unformat: unformat},
			{ label: 'Amount', name: 'amount', width: 40, classes: 'wrap', align: 'right', formatter: 'currency'  },
			{ label: 'Percentage', name: 'percent_', width: 40, classes: 'wrap', align: 'right', formatter: formatter1, unformat: unformat1 },
			{ label: 'All Service', name: 'service', width: 40, classes: 'wrap', formatter: formatter, unformat: unformat },
			{ label: 'discchgcode', name: 'discchgcode', width: 90, hidden: true },
			{ label: 'ttacode', name: 'ttacode', width: 90, hidden: true },
			{ label: 'discrate', name: 'discrate', width: 90, hidden: true },
			{ label: 'adduser', name: 'adduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'adddate', name: 'adddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upduser', name: 'upduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upddate', name: 'upddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'Record Status', name: 'recstatus', width: 30, classes: 'wrap', cellattr: function(rowid, cellvalue)
			{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''}, },
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
			$("#searchForm input[name=Stext]").focus();
		},
		onSelectRow: function (rowid, selected) {
			if (rowid != null) {
				$("#Fsvc a").off();
				urlParam_svc.filterVal[0] = selrowData("#jqGrid").billtype;
				// saveParam_svc.filterVal[0] = selrowData("#jqGrid").billtype;
				urlParam_item.filterVal[0] = selrowData("#jqGrid").billtype;
				// saveParam_item.filterVal[0] = selrowData("#jqGrid").billtype;
				$("#Fsvc :input[name='billtype']").val(selrowData("#jqGrid").billtype);
				$("#Fsvc :input[name='description']").val(selrowData("#jqGrid").description);
				refreshGrid('#jqGridsvc', urlParam_svc);
				$('#jqGriditem').jqGrid('clearGridData');
				$("#pg_jqGridPager3 table").hide();
				$("#pg_jqGridPager2 table").show();

				$("#jqGriditem_c, #jqGridtype_c, #click_row2, #click_row3").hide();

				if (selrowData("#jqGrid").service == 1) {
					refreshGrid('#jqGridsvc', urlParam_svc);
					$("#pg_jqGridPager2 table").hide();
				}
			}

			$('#showbilltype').text(selrowData("#jqGrid").billtype);//tukar kat depan tu
			$('#showbilldesc').text(selrowData("#jqGrid").description);
			refreshGrid("#jqGridsvc", urlParam_svc);
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
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'view', '');
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit', '');
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

	populateSelect2('#jqGrid', '#searchForm');
	searchClick2('#jqGrid', '#searchForm', urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	addParamField('#jqGrid', false, saveParam, ['idno','recstatus','adduser','adddate','upduser','upddate']);

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////// billtysvc //////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	var dialog_ChgGroup = new ordialog(
		'chggroup','hisdb.chggroup',"#Fsvc :input[name='svc_chggroup']",errorField,
		{	colModel:[
				{label:'Group Code',name:'grpcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
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
				dialog_ChgGroup.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_ChgGroup.makedialog();

	var dialog_discChargeCode = new ordialog(
		'svc_discchgcode','hisdb.chgmast',"#Fsvc :input[name='svc_discchgcode']",errorField,
		{	colModel:[
				{label:'Charge Code',name:'chgcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				$('#svc_percent_').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#svc_percent_').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}		
		},{
			title:"Select Charge Code",
			open: function(){
				dialog_discChargeCode.urlParam.filterCol=['compcode','recstatus'],
				dialog_discChargeCode.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam', 'radio', 'tab', false
	);
	dialog_discChargeCode.makedialog();

	var buttsvc1 = [{
		text: "Save", click: function () {
			mycurrency.formatOff();
			radbuts_svc.check();
			mycurrency.check0value(errorField);
			if ($('#Fsvc').isValid({ requiredFields: '' }, {}, true)) {
				// saveFormdata("#jqGridsvc", "#Dsvc", "#Fsvc", oper_svc, saveParam_svc, urlParam_svc, '#searchForm2');
				saveFormdata("#jqGridsvc", "#Dsvc", "#Fsvc", oper_svc, saveParam_svc, urlParam_svc);
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
						$(this).dialog("option", "title", "Add Bill Type Service");
						enableForm('#Fsvc');
						rdonly('#Fsvc');
						hideOne('#Fsvc');
						break;
					case state = 'edit':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "Edit Bill Type Service");
						enableForm('#Fsvc');
						frozeOnEdit("#Dsvc");
						rdonly('#Fsvc');
						$('#Fsvc :input[hideOne]').show();
						break;
					case state = 'view':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "View Bill Type Service");
						disableForm('#Fsvc');
						$('#Fsvc :input[hideOne]').show();
						$(this).dialog("option", "buttons", butt2);
						break;
				}
				if (oper_svc == 'add') {
					dialog_ChgGroup.on();
					dialog_discChargeCode.on();
					let priceval = selrowData("#jqGrid").price
					$("#Fsvc [name='svc_price'][value='"+priceval+"']").prop('checked', true);
				}
				if (oper_svc == 'edit' && $('#jqGriditem').jqGrid('getGridParam', 'reccount') < 1) {
					dialog_ChgGroup.on();
					dialog_discChargeCode.on();

				}
				if (oper_svc == 'edit' && $('#jqGriditem').jqGrid('getGridParam', 'reccount') >= 1) {
					$("#Fsvc :input[name='svc_chggroup']").prop("readonly", true);
				}
				if (oper_svc != 'add') {
					dialog_ChgGroup.check(errorField);
					dialog_discChargeCode.check(errorField);
				}
				if (oper_svc != 'view') {
					set_compid_from_storage("input[name='svc_lastcomputerid']","input[name='svc_lastipaddress']","input[name='svc_computerid']","input[name='svc_ipaddress']");
				}

			},
			close: function (event, ui) {
				parent_close_disabled(false);
				emptyFormdata(errorField, '#Fsvc');
				$('.my-alert').detach();
				radbuts_svc.reset();
				//$('.alert').detach();
				dialog_ChgGroup.off();
				dialog_discChargeCode.off();
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
		join_onCol: ['svc.billtype', 'svc.chggroup'],
		join_onVal: ['m.billtype', 'cc.grpcode'],
		filterCol: ['svc.billtype', 'svc.compcode', 'm.compcode', 'm.service'],
		filterVal: ['', 'session.compcode', 'session.compcode', '0'],
		sort_idno: true,
	}

	var saveParam_svc = {
		action: 'save_table_default',
		url:'./billtype/form',
		field: '',
		oper: oper_svc,
		table_name: 'hisdb.billtysvc',
		fixPost: 'true',//throw out dot in the field name
		table_id: 'svc_chggroup',//ni utk tgk duplicate
		idnoUse: 'svc_idno',
		// filterCol: ['billtype'],
		// filterVal: [''],
		saveip:'true',
		// checkduplicate:'true'
	};

	function searchClick2(grid,form,urlParam_svc){
		$(form+' [name=Stext]').on( "keyup", function() {
			delay(function(){
				search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam_svc);
				$('#showbilltype2').text("");//tukar kat depan tu
				$('#showbilldesc2').text("");
				$('#showchggroup2').text("");
				$('#showgroupdesc2').text("");
				$('#showbilltype3').text("");
				$('#showbilldesc3').text("");
				$('#showchggroup3').text("");
				$('#showgroupdesc3').text("");
				refreshGrid("#jqGriditem, #jqGridtype",null,"kosongkan");
			}, 500 );
		});

		$(form+' [name=Scol]').on( "change", function() {
			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam_svc);
			$('#showbilltype2').text("");//tukar kat depan tu
			$('#showbilldesc2').text("");
			$('#showchggroup2').text("");
			$('#showgroupdesc2').text("");
			$('#showbilltype3').text("");
			$('#showbilldesc3').text("");
			$('#showchggroup3').text("");
			$('#showgroupdesc3').text("");
			refreshGrid("#jqGriditem, #jqGridtype",null,"kosongkan");
		});
	}

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$("#jqGridsvc").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Bill Type', name: 'svc_billtype', width: 50, hidden: true },
			{ label: 'Description', name: 'm_description', width: 90, hidden: true },
			{ label: 'Chg. Group', name: 'svc_chggroup', width: 50, classes: 'wrap', canSearch: true },
			{ label: 'Description', name: 'cc_description', width: 90, classes: 'wrap', canSearch: true , checked: true},
			{ label: 'Price', name: 'svc_price', width: 90, classes: 'wrap', checked: true },
			{ label: 'Amount', name: 'svc_amount', width: 90, classes: 'wrap', align: 'right', formatter: 'currency' },
			{ label: 'Percentage', name: 'svc_percent_', width: 50, classes: 'wrap', align: 'right', formatter: formatter1, unformat: unformat1 },
			{ label: 'All Item', name: 'svc_allitem', width: 50, classes: 'wrap', formatter: formatter, unformat: unformat },
			{ label: 'All Type', name: 'svc_alltype', width: 50, classes: 'wrap', formatter: formatter, unformat: unformat },
			{ label: 'Discount Charge Code', name: 'svc_discchgcode', width: 50, classes: 'wrap', },
			{ label: 'discrate', name: 'svc_discrate', width: 60, hidden: true },
			{ label: 'adduser', name: 'svc_adduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'adddate', name: 'svc_adddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upduser', name: 'svc_upduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upddate', name: 'svc_upddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'Record Status', name: 'svc_recstatus', width: 30, classes: 'wrap', cellattr: function(rowid, cellvalue)
			{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''}, },
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
				refreshGrid('#jqGriditem', urlParam_item,'kosongkan');
				$("#pg_jqGridPager3 table, #jqGriditem_c, #click_row2").hide();


				refreshGrid('#jqGridtype', urlParam_type,'kosongkan');
				$("#pg_jqGridPager4 table, #jqGridtype_c, #click_row3").hide();

				if (rowData['svc_allitem'] == '0') {
					$("#Fitem a").off();
					//console.log(rowData.svc_billtype);
					urlParam_item.filterVal[0] = selrowData("#jqGridsvc").svc_billtype;
					urlParam_item.filterVal[2] = selrowData("#jqGridsvc").svc_chggroup;
					refreshGrid('#jqGriditem', urlParam_item);
					$("#pg_jqGridPager3 table, #jqGriditem_c, #click_row2").show();
				}

				if (rowData['svc_alltype'] == '0') {
					urlParam_type.filterVal[0] = selrowData("#jqGridsvc").svc_billtype;
					urlParam_type.filterVal[2] = selrowData("#jqGridsvc").svc_chggroup;
					refreshGrid('#jqGridtype', urlParam_type);
					$("#pg_jqGridPager4 table, #jqGridtype_c, #click_row3").show();
				}


				dialog_chgtype.urlParam.filterCol=['chggroup'];
				dialog_chgtype.urlParam.filterVal=[selrowData("#jqGridsvc").svc_chggroup];

				dialog_chgcode.urlParam.filterCol=['chggroup'];
				dialog_chgcode.urlParam.filterVal=[selrowData("#jqGridsvc").svc_chggroup];

			}

			$('#showbilltype2').text(selrowData("#jqGridsvc").svc_billtype);//tukar kat depan tu
			$('#showbilldesc2').text(selrowData("#jqGridsvc").m_description);
			$('#showchggroup2').text(selrowData("#jqGridsvc").svc_chggroup);
			$('#showgroupdesc2').text(selrowData("#jqGridsvc").cc_description);
			$('#showbilltype3').text(selrowData("#jqGridsvc").svc_billtype);//tukar kat depan tu
			$('#showbilldesc3').text(selrowData("#jqGridsvc").m_description);
			$('#showchggroup3').text(selrowData("#jqGridsvc").svc_chggroup);
			$('#showgroupdesc3').text(selrowData("#jqGridsvc").cc_description);
			// refreshGrid("#jqGridsvc", urlParam_svc);
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
				saveFormdata("#jqGridsvc", "#Dsvc", "#Fsvc", 'del', saveParam_svc, urlParam_svc, { 'idno': selrowData('#jqGridsvc').svc_idno });
				// saveFormdata("#jqGridsvc", "#Dsvc", "#Fsvc", 'del', saveParam_svc, urlParam_svc, null, { 'idno': selrowData('#jqGridsvc').svc_idno });
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
			populateFormdata("#jqGridsvc", "#Dsvc", "#Fsvc", selRowId, 'view', '');
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
			populateFormdata("#jqGridsvc", "#Dsvc", "#Fsvc", selRowId, 'edit', '');
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
				{label:'Charge Code',name:'chgcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus','chggroup'],
				filterVal:['session.compcode','ACTIVE',$("#Fitem :input[name*='i_chggroup']").val()]
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
			radbuts_item.check();
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
						$(this).dialog("option", "title", "Add Bill Type Item");
						enableForm('#Fitem');
						rdonly('#Fitem');
						hideOne('#Fitem');
						break;
					case state = 'edit':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "Edit Bill Type Item");
						$("#Fitem a").off();
						enableForm('#Fitem');
						frozeOnEdit("#Ditem");
						rdonly('#Fitem');
						$('#Fitem :input[hideOne]').show();
						break;
					case state = 'view':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "View Bill Type Item");
						disableForm('#Fitem');
						$('#Fitem :input[hideOne]').show();
						$(this).dialog("option", "buttons", butt2);
						break;
				}
				if (oper_item == 'add') {
					dialog_chgcode.on();
					let priceval = selrowData("#jqGrid").price
					$("#Fitem [name='i_price'][value='"+priceval+"']").prop('checked', true);
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
				$('.my-alert').detach();
				radbuts_item.reset();
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
		url: 'util/get_table_default',
		field: '',
		fixPost: 'true',//replace underscore with dot
		table_name: ['hisdb.billtyitem AS i', 'hisdb.billtysvc AS svc', 'hisdb.chggroup AS c', 'hisdb.chgmast as m'],
		table_id: 'i_chgcode',
		join_type: ['JOIN', 'JOIN', 'JOIN'],
		join_onCol: ['i.chggroup', 'c.grpcode', 'i.chgcode'],
		join_onVal: ['svc.chggroup', 'i.chggroup', 'm.chgcode'],
		join_filterCol:[['i.billtype on =']],
		join_filterVal:[['svc.billtype']],
		filterCol: ['i.billtype', 'i.compcode', 'i.chggroup', 'svc.allitem', 'svc.compcode'],
		filterVal: ['', 'session.compcode', '', '0', 'session.compcode'],
		sort_idno: true,
	}

	var saveParam_item = {
		action: 'save_table_default',
		url: './billtype/form',
		field: '',
		oper: oper_item,
		table_name: 'hisdb.billtyitem ',
		fixPost: 'true',//throw out dot in the field name
		table_id: 'i_chgcode',
		idnoUse: 'i_idno',
		// filterCol: ['billtype'],
		// filterVal: [''],
		saveip:'true',
		// checkduplicate:'true'
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////

	$("#jqGriditem").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Bill Type', name: 'i_billtype', width: 50, hidden: true },
			{ label: 'Chg. Group', name: 'i_chggroup', width: 90, hidden: true },
			{ label: 'Description', name: 'c_description', width: 90, hidden: true },
			{ label: 'Chg Code', name: 'i_chgcode', width: 90, classes: 'wrap', canSearch: true },
			{ label: 'Description', name: 'm_description', width: 90, classes: 'wrap', canSearch: true, checked: true },
			{ label: 'Price', name: 'i_price', width: 50, classes: 'wrap', },
			{ label: 'Amount', name: 'i_amount', width: 50, classes: 'wrap', align: 'right', formatter: 'currency' },
			{ label: 'discrate', name: 'i_discrate', width: 50, hidden: true },
			{ label: 'Percentage', name: 'i_percent_', width: 50, classes: 'wrap', align: 'right', formatter: formatter1, unformat: unformat1 },
			{ label: 'adduser', name: 'i_adduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'adddate', name: 'i_adddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upduser', name: 'i_upduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upddate', name: 'i_upddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'Record Status', name: 'i_recstatus', width: 90, classes: 'wrap', cellattr: function(rowid, cellvalue)
			{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''}, },
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
				saveFormdata("#jqGriditem", "#Ditem", "#Fitem", 'del', saveParam_item, urlParam_item, { 'idno': selrowData('#jqGriditem').i_idno });
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
			populateFormdata("#jqGriditem", "#Ditem", "#Fitem", selRowId, 'view', '');
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
			populateFormdata("#jqGriditem", "#Ditem", "#Fitem", selRowId, 'edit', '');
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
	addParamField('#jqGriditem', false, saveParam_item, ["c_description", "m_description", "i_discrate", "i_idno",'i_adduser', 'i_adddate', 'i_upduser', 'i_upddate', 'i_recstatus']);

	//populateSelect('#jqGriditem');
	populateSelect('#jqGriditem', '#searchForm3');
	searchClick('#jqGriditem', '#searchForm3', urlParam_item);
	//searchClick('#gridSuppBonus','#searchForm3',urlParam_item);
	//toogleSearch('#sbut3','#searchForm3','off');

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////// billtytype /////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	var dialog_chgtype = new ordialog(
		'chgtype','hisdb.chgtype',"#Ftype :input[name*='t_chgtype']",errorField,
		{	colModel:[
				{label:'Charge Type',name:'chgtype',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus','chggroup'],
				filterVal:['session.compcode','ACTIVE',$("#Ftype :input[name*='t_chggroup']").val()]
			},
			ondblClickRow: function () {
				$('#t_price').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#t_price').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Charge Type",
			open: function(){
				dialog_chgtype.urlParam.filterCol=['chggroup'],
				dialog_chgtype.urlParam.filterVal=[$("#Ftype :input[name*='t_chggroup']").val()]
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_chgtype.makedialog();
	
	var butttype1 = [{
		text: "Save", click: function () {
			mycurrency.formatOff();
			radbuts_type.check();
			mycurrency.check0value(errorField);
			if ($('#Ftype').isValid({ requiredFields: '' }, {}, true)) {
				saveFormdata("#jqGridtype", "#Dtype", "#Ftype", oper_type, saveParam_type, urlParam_type);
			} else {
				mycurrency.formatOn();
			}
		}
	}, {
		text: "Cancel", click: function () {
			$(this).dialog('close');
		}
	}];

	var oper_type;
	$("#Dtype")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				switch (oper_type) {
					case state = 'add':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "Add Bill Charge Type");
						enableForm('#Ftype');
						rdonly('#Ftype');
						hideOne('#Ftype');
						break;
					case state = 'edit':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "Edit Bill Charge Type");
						$("#Ftype a").off();
						enableForm('#Ftype');
						frozeOnEdit("#Dtype");
						rdonly('#Ftype');
						$('#Ftype :input[hideOne]').show();
						break;
					case state = 'view':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "View Bill Charge Type");
						disableForm('#Ftype');
						$('#Ftype :input[hideOne]').show();
						$(this).dialog("option", "buttons", butt2);
						break;
				}
				if (oper_type == 'add') {
					dialog_chgtype.on();
					let priceval = selrowData("#jqGrid").price
					$("#Ftype [name='t_price'][value='"+priceval+"']").prop('checked', true);
				}
				if (oper_type != 'add') {
					dialog_chgtype.check(errorField);
				}
				if(oper_type!='view'){
					set_compid_from_storage("input[name='t_lastcomputerid']","input[name='t_lastipaddress']","input[name='t_computerid']","input[name='t_ipaddress']");
					// dialog_chgtype.on();
				}
				/*if(oper_item!='add'){
					dialog_chgtype.check(errorField);
				}*/
			},
			close: function (event, ui) {
				emptyFormdata(errorField, '#Ftype');
				parent_close_disabled(false);
				$('.my-alert').detach();
				radbuts_type.reset();
				//$('.alert').detach();
				dialog_chgtype.off();
				if (oper == 'view') {
					$(this).dialog("option", "buttons", butttype1);
				}
			},
			buttons: butttype1,
		});

	/////////////////////parameter for jqgrid url Type/////////////////////////////////////////////////
	var urlParam_type = {
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		fixPost: 'true',//replace underscore with dot
		table_name: ['hisdb.billtytype AS t', 'hisdb.billtysvc AS svc', 'hisdb.chgtype AS ct', 'hisdb.chggroup as cg'],
		table_id: 't_chgtype',
		join_type: ['JOIN', 'JOIN', 'JOIN'],
		join_onCol: ['t.chggroup', 'ct.chgtype', 'cg.grpcode'],
		join_onVal: ['svc.chggroup', 't.chgtype', 't.chggroup'],
		join_filterCol:[['t.billtype on =']],
		join_filterVal:[['svc.billtype']],
		filterCol: ['t.billtype', 't.compcode', 't.chggroup', 'svc.compcode'],//, 'svc.alltype'
		filterVal: ['', 'session.compcode', '', 'session.compcode'],//, '0'
		sort_idno: true,
	}

	var saveParam_type = {
		action: 'save_table_default',
		url: './billtype/form',
		field: '',
		oper: oper_type,
		table_name: 'hisdb.billtytype ',
		fixPost: 'true',//throw out dot in the field name
		table_id: 't_chgtype',
		idnoUse: 't_idno',
		// filterCol: ['billtype'],
		// filterVal: [''],
		saveip:'true',
		// checkduplicate:'true'
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////

	$("#jqGridtype").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Bill Type', name: 't_billtype', width: 50, hidden: true },
			{ label: 'Chg. Group', name: 't_chggroup', width: 90, hidden: true},
			{ label: 'Description', name: 'cg_description', width: 90, hidden: true },
			// { label: 'Chg Code', name: 'i_chgcode', width: 90, classes: 'wrap', canSearch: true, checked: true },
			{ label: 'Chg Type', name: 't_chgtype', width: 90, classes: 'wrap', canSearch: true },
			{ label: 'Description', name: 'ct_description', width: 90, classes: 'wrap', canSearch: true, checked: true },
			{ label: 'Price', name: 't_price', width: 50, classes: 'wrap'},
			{ label: 'Percentage', name: 't_percent_', width: 50, align: 'right', classes: 'wrap', formatter: formatter1, unformat: unformat1 },
			{ label: 'Amount', name: 't_amount', width: 50, classes: 'wrap', align: 'right', formatter: 'currency'},
			{ label: 'All Item', name: 't_allitem', width: 50, classes: 'wrap', hidden: true},
			{ label: 'Discount Charge Code', name: 't_discchgcode', width: 50, classes: 'wrap'},
			{ label: 'discrate', name: 't_discrate', width: 50, hidden: true },
			{ label: 'Record Status', name: 't_recstatus', width: 90, classes: 'wrap', cellattr: function(rowid, cellvalue)
			{return cellvalue == 'DEACTIVE' ? 'class="alert alert-danger"': ''}, },
			{ label: 'adduser', name: 't_adduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'adddate', name: 't_adddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upduser', name: 't_upduser', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'upddate', name: 't_upddate', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'No', name: 't_idno', width: 50, hidden: true },
			{ label: 'computerid', name: 't_computerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'ipaddress', name: 't_ipaddress', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastcomputerid', name: 't_lastcomputerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastipaddress', name: 't_lastipaddress', width: 90, hidden: true, classes: 'wrap' },
		],
		viewrecords: true,
		autowidth: true,
		multiSort: true,
		loadonce: false,
		width: 900,
		height: 100,
		rowNum: 30,
		hidegrid: false,
		caption: caption('searchForm4', 'Bill Charge Type'),
		pager: "#jqGridPager4",
		ondblClickRow: function (rowid, iRow, iCol, e) {
			$("#jqGridPager4 td[title='Edit Selected Row']").click();
		},
		gridComplete: function () {
			if (oper == 'add') {
				$("#jqGridtype").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			$('#' + $("#jqGridtype").jqGrid('getGridParam', 'selrow')).focus();

			/////////////////////////////// reccount ////////////////////////////
			if ($("#jqGridtype").getGridParam("reccount") >= 1) {
				$("#jqGridPager2glyphicon-trash").hide();
			}

			if ($("#jqGridtype").getGridParam("reccount") < 1) {
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

	$("#jqGridtype").jqGrid('navGrid', '#jqGridPager4', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGridtype", urlParam_type);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager4", {
		caption: "",
		buttonicon: "glyphicon glyphicon-trash",
		onClickButton: function () {
			// oper_suppitems = 'del';
			var selRowId = $("#jqGridtype").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				alert('Please select row');
				return emptyFormdata(errorField, '#Ftype');
			} else {
				saveFormdata("#jqGridtype", "#Dtype", "#Ftype", 'del', saveParam_type, urlParam_type, { 'idno': selrowData('#jqGridtype').t_idno });
				//saveFormdata("#jqGriditem","#Ditem","#Fitem",'del',saveParam_item,{"chgcode":selRowId});
			}
		},
		position: "first",
		title: "Delete Selected Row",
		cursor: "pointer"
	}).jqGrid('navButtonAdd', "#jqGridPager4", {
		caption: "",
		buttonicon: "glyphicon glyphicon-info-sign",
		onClickButton: function () {
			oper_type = 'view';
			selRowId = $("#jqGridtype").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGridtype", "#Dtype", "#Ftype", selRowId, 'view', '');
		},
		position: "first",
		title: "View Selected Row",
		cursor: "pointer"
	}).jqGrid('navButtonAdd', "#jqGridPager4", {
		caption: "",
		buttonicon: "glyphicon glyphicon-edit",
		onClickButton: function () {
			oper_type = 'edit';
			selRowId = $("#jqGridtype").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGridtype", "#Dtype", "#Ftype", selRowId, 'edit', '');
			// recstatusDisable();
		},
		position: "first",
		title: "Edit Selected Row",
		cursor: "pointer"
	}).jqGrid('navButtonAdd', "#jqGridPager4", {
		caption: "",
		buttonicon: "glyphicon glyphicon-plus",
		onClickButton: function (rowid, selected) {
			oper_type = 'add';
			//rowData = $('#jqGridsvc').jqGrid ('getRowData', rowid);
			$("#Dtype").dialog("open");
			//var xx= 
			$("#Ftype :input[name*='t_billtype']").val(selrowData('#jqGridsvc').svc_billtype);
			$("#Ftype :input[name*='t_chggroup']").val(selrowData('#jqGridsvc').svc_chggroup);
			$("#Ftype :input[name*='cg_description']").val(selrowData('#jqGridsvc').cc_description);
		},
		position: "first",
		title: "Add New Row",
		cursor: "pointer"
	});

	addParamField('#jqGridtype', false, urlParam_type);
	addParamField('#jqGridtype', false, saveParam_type, ["cg_description","ct_description", "t_discrate", "t_idno",'t_adduser', 't_adddate', 't_upduser', 't_upddate', 't_recstatus']);

	//populateSelect('#jqGriditem');
	populateSelect('#jqGridtype', '#searchForm4');
	searchClick('#jqGridtype', '#searchForm4', urlParam_type);


	///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/////////////////Pager Hide/////////////////////////////////////////////////////////////////////////////////////////
	$("#pg_jqGridPager2 table").hide();
	$("#pg_jqGridPager3 table").hide();
	$("#pg_jqGridPager4 table").hide();

	jqgrid_label_align_right("#jqGrid");
	jqgrid_label_align_right("#jqGridsvc");
	jqgrid_label_align_right("#jqGriditem");
	jqgrid_label_align_right("#jqGridtype");


	$("#jqGrid3_panel1").on("show.bs.collapse", function(){
		$("#jqGridsvc").jqGrid ('setGridWidth', Math.floor($("#jqGridsvc_c")[0].offsetWidth-$("#jqGridsvc_c")[0].offsetLeft-28));
	});

	$("#jqGrid3_panel2").on("show.bs.collapse", function(){
		$("#jqGriditem").jqGrid ('setGridWidth', Math.floor($("#jqGriditem_c")[0].offsetWidth-$("#jqGriditem_c")[0].offsetLeft-28));
	});

	$("#jqGrid3_panel3").on("show.bs.collapse", function(){
		$("#jqGridtype").jqGrid ('setGridWidth', Math.floor($("#jqGridtype_c")[0].offsetWidth-$("#jqGridtype_c")[0].offsetLeft-28));
	});
});

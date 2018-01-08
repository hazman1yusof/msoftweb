
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;

$(document).ready(function () {
	$("body").show();
	check_compid_exist("input[name='lastcomputerid']", "input[name='lastipaddress']","input[name='computerid']", "input[name='ipaddress']");
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

	var mycurrency = new currencymode(['#minqty', '#maxqty', '#reordlevel', '#reordqty']);


	/////////////////////////////////////////////////////////object for dialog handler//////////////////

	// dialog_itemcode = new makeDialog('material.product', '#itemcodeS', ['itemcode', 'description', 'Class', 'uomcode'], 'Item');
	// dialog_itemcode.handler(errorField);
	// dialog_uomcode = new makeDialog('material.product', '#uomcodeS', ['uomcode'], 'UOM');
	// dialog_uomcode.handler(errorField);

	// dialog_deptcode = new makeDialog('sysdb.department', '#deptcode', ['deptcode', 'description'], 'Department');

	////////////////////////////////////start dialog///////////////////////////////////////
	var butt1 = [{
		text: "Save", click: function () {
			mycurrency.formatOff();
			// mycurrency.check0value(errorField);
			if ($('#formdata').isValid({ requiredFields: '' }, conf, true)) {
				enableRadioButton();
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
						disableRadioButton();
						rdonly("#dialogForm");
						hideOne('#formdata');
						break;
					case state = 'edit':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "Edit");
						enableForm('#formdata');
						frozeOnEdit("#dialogForm");
						rdonly("#dialogForm");
						$('#formdata :input[hideOne]').show();
						break;
					case state = 'view':
						mycurrency.formatOnBlur();
						$(this).dialog("option", "title", "View");
						disableForm('#formdata');
						$(this).dialog("option", "buttons", butt2);
						break;
				}
				if (oper != 'view') {
					set_compid_from_storage("input[name='lastcomputerid']", "input[name='lastipaddress']", "input[name='computerid']", "input[name='ipaddress']");
					mycurrency.formatOn();
					dialog_deptcode.on();
				}
				if (oper != 'add') {
					dialog_deptcode.check(errorField);
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
		table_name: 'material.stockloc',
		field: '',
		table_id: 'idno',
		sort_idno: true,
		//filterCol:['itemcode', 'uomcode'],
		//filterVal:[itemcode, ],
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam = {
		action: 'save_table_default',
		field: '',
		oper: oper,
		table_name: 'material.stockloc',
		table_id: 'idno',
		saveip:'true'
	};

	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'idno', hidden: true },
			{ label: 'compcode', name: 'compcode', width: 10, hidden: true },
			{ label: 'Department Code', name: 'deptcode', width: 80, classes: 'wrap' },
			{ label: 'year', name: 'year', width: 80, classes: 'wrap' },
			{ label: 'Item Code', name: 'itemcode', width: 60, classes: 'wrap', hidden: true, },
			{ label: 'UOM Code', name: 'uomcode', width: 60, classes: 'wrap' },
			{ label: 'Bin Code', name: 'bincode', width: 50, classes: 'wrap', hidden: true },
			{ label: 'Rack No', name: 'rackno', width: 50, classes: 'wrap', hidden: true },
			{ label: 'Tran Type', name: 'stocktxntype', width: 50, classes: 'wrap', },
			{ label: 'Disp Type', name: 'disptype', width: 50, classes: 'wrap', hidden: true },
			{ label: 'Qty On Hand', name: 'qtyonhand', width: 50, classes: 'wrap', align: 'right' },
			{ label: 'Min Stock Qty', name: 'minqty', width: 40, classes: 'wrap' },
			{ label: 'Max Stock Qty', name: 'maxqty', width: 40, classes: 'wrap', },
			{ label: 'Reorder Level', name: 'reordlevel', width: 60, classes: 'wrap', },
			{ label: 'Reorder Quantity', name: 'reordqty', width: 60, classes: 'wrap', },
			{ label: 'adduser', name: 'adduser', width: 90, hidden: true, },
			{ label: 'adddate', name: 'adddate', width: 90, hidden: true, },
			{ label: 'upduser', name: 'upduser', width: 90, hidden: true, },
			{ label: 'upddate', name: 'upddate', width: 90, hidden: true, },
			//{ label: 'Record Status', name: 'recstatus', width: 20, classes: 'wrap',  
			//cellattr: function(rowid, cellvalue){return cellvalue == 'D' ? 'class="alert alert-danger"': ''}, },
			{ label: 'computerid', name: 'computerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'ipaddress', name: 'ipaddress', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastcomputerid', name: 'lastcomputerid', width: 90, hidden: true, classes: 'wrap' },
			{ label: 'lastipaddress', name: 'lastipaddress', width: 90, hidden: true, classes: 'wrap' },
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

	////////////////////// set label jqGrid right ////////////////////////////////////////////////
	$("#jqGrid").jqGrid('setLabel', 'qtyonhand', 'Qty On Hand', { 'text-align': 'right' });
	/////////////////////////////////////////////////////

	$("#pg_jqGridPager table").hide();
	$("#itemcodeS").focus();
	//$('#formdataSearch :input').prop("readonly",true);

	function disableRadioButton() {
		$('#formdata input[name=disptype]:radio').prop("disabled", true);
	}

	function enableRadioButton() {
		$('#formdata input[name=disptype]:radio').prop("disabled", false);
	}

	$("input[name=stocktxntype]:radio").on('change click', function () {
		stocktxntype = $("input[name=stocktxntype]:checked").val();
		console.log(stocktxntype)

		if (stocktxntype == 'TR') {
			$("#TRDS").prop("checked", true);
		}
		if (stocktxntype == 'IS') {
			$("#ISDS1").prop("checked", true);
		}
	});

	$("#search").click(function () {
		//$("#uomcodeS").focus();
		dialog_itemcode.check(errorField);
		dialog_uomcode.check(errorField);		

		if ($('#formdataSearch').isValid({ requiredFields: '' }, conf, true)) {
			$("#search").prop("disabled", true);
			$('#formdataSearch :input').prop("readonly", true);
			dialog_itemcode.off();
			dialog_uomcode.off();

			var currentDate = $("#datetoday").val();

			urlParam.filterCol = ['itemcode', 'uomcode', 'year'];
			urlParam.filterVal = [$('#itemcodeS').val(), $('#uomcodeS').val(), currentDate];

			refreshGrid('#jqGrid', urlParam);
			$("#pg_jqGridPager table").show();
		}
	});

	$("#cancel").click(function () {
		$("#pg_jqGridPager table").hide();
		emptyFormdata(errorField, '#formdataSearch');
		$("#search").prop("disabled", false);
		$('#formdataSearch :input').prop("readonly", false);
		dialog_itemcode.on();
		dialog_uomcode.on();
		$("#itemcodeS").focus();
		$('#jqGrid').jqGrid('setGridParam', { datatype: 'local', url: '' }).trigger('reloadGrid');
	});


	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid", urlParam);
		},
		/*}).jqGrid('navButtonAdd',"#jqGridPager",{
			caption:"",cursor: "pointer",position: "first", 
			buttonicon:"glyphicon glyphicon-trash",
			title:"Delete Selected Row",
			onClickButton: function(){
				oper='del';
				selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
				if(!selRowId){
					alert('Please select row');
					return emptyFormdata(errorField,'#formdata');
				}else{
					saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam, null, {'idno':selRowId});
				}
			},*/
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
			$("#formdata :input[name='itemcode']").val($("#itemcodeS").val());
			$("#formdata :input[name='uomcode']").val($("#uomcodeS").val());
			$("#formdata :input[name='year']").val($("#year").val());
		},
	});

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', false, urlParam);
	//addParamField('#jqGrid',false,saveParam);
	addParamField('#jqGrid', false, saveParam, ['idno', 'adduser', 'adddate', 'computerid', 'ipaddress']);

	////////////////////////////////////////////////////ordialog////////////////////////////////////////

	var dialog_itemcode = new ordialog(
		'itemcodeS','material.product','#itemcodeS',errorField,
		{	colModel:[
				{label:'Item Code',name:'itemcode',width:200,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Uom Code',name:'uomcode',width:100,classes:'pointer'},
				{label:'Class',name:'Class',width:400,classes:'pointer',hidden:true},
				],
				ondblClickRow:function(){
				let data=selrowData('#'+dialog_itemcode.gridname);
				$("#uomcodeS").val(data['uomcode']);
				}
		},{
			title:"Select Item",
			open: function(){
				console.log($('#Class2').val());
				if ($('#Class2').val().trim()  == 'Pharmacy') { 
					dialog_itemcode.urlParam.filterCol=['Class', 'recstatus'];
					dialog_itemcode.urlParam.filterVal=['Pharmacy', 'A'];
				}else if ($('#Class2').val().trim()  == 'Non-Pharmacy'){
					dialog_itemcode.urlParam.filterCol=['Class', 'recstatus'];
					dialog_itemcode.urlParam.filterVal=['Non-Pharmacy', 'A'];
				}else if ($('#Class2').val().trim()  == 'Others'){
					dialog_itemcode.urlParam.filterCol=['Class', 'recstatus'];
					dialog_itemcode.urlParam.filterVal=['Others', 'A'];
				}else if ($('#Class2').val().trim()  == 'Asset'){
					dialog_itemcode.urlParam.filterCol=['Class', 'recstatus'];
					dialog_itemcode.urlParam.filterVal=['Asset', 'A'];
				}else if ($('#Class2').val().trim()  == 'All'){
					dialog_itemcode.urlParam.filterCol=['recstatus'];
					dialog_itemcode.urlParam.filterVal=['A'];
				}
			}
		},'urlParam'
	);
	dialog_itemcode.makedialog();

	var dialog_uomcode = new ordialog(
		'uomcodeS', ['material.product p', 'material.uom u'], "#uomcodeS", errorField,
		{
			colModel:
			[
				{ label: 'UOM code', name: 'p.uomcode', width: 200, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Description', name: 'u.description', width: 400, classes: 'pointer', canSearch: true, or_search: true },
			],
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_uomcode.gridname);
				$("#uomcodeS").val(data['p.uomcode']);
			}

		}, {
			title: "Select Uom",
			open: function () {
				dialog_uomcode.urlParam.table_id = "uomcode";
				dialog_uomcode.urlParam.filterCol = ['p.itemcode'];
				dialog_uomcode.urlParam.filterVal = [$("#itemcodeS").val()];
				dialog_uomcode.urlParam.join_type = ['LEFT JOIN'];
				dialog_uomcode.urlParam.join_onCol = ['p.uomcode'];
				dialog_uomcode.urlParam.join_onVal = ['u.uomcode'];
				dialog_uomcode.urlParam.join_filterCol = [['p.compcode']];
				dialog_uomcode.urlParam.join_filterVal = [['skip.u.compcode']];
			},
		}, 'urlParam'
	);
	dialog_uomcode.makedialog();

	var dialog_deptcode = new ordialog(
		'deptcode','sysdb.department','#deptcode',errorField,
		{	colModel:[
				{label:'Dept Code',name:'deptcode',width:100,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true},
				],
			ondblClickRow:function(){
			}	
		},{
			title:"Select Dept",
			open: function(){
				dialog_deptcode.urlParam.filterCol = ['recstatus'];
				dialog_deptcode.urlParam.filterVal = [ 'A'];	
			}
		},'urlParam'
	);
	dialog_deptcode.makedialog();


	///////////////////////////////start->dialogHandler part////////////////////////////////////////////
	// function makeDialog(table, id, cols, title) {
	// 	this.table = table;
	// 	this.id = id;
	// 	this.cols = cols;
	// 	this.title = title;
	// 	this.handler = dialogHandler;
	// 	this.offHandler = function () {
	// 		$(this.id + " ~ a").off();
	// 	}
	// 	this.check = checkInput;
	// 	this.updateField = function (table, id, cols, title) {
	// 		this.table = table;
	// 		this.id = id;
	// 		this.cols = cols;
	// 		this.title = title;
	// 		//console.log(this);
	// 	}
	// }

	// $("#dialog").dialog({
	// 	autoOpen: false,
	// 	width: 7 / 10 * $(window).width(),
	// 	modal: true,
	// 	open: function () {
	// 		$("#gridDialog").jqGrid('setGridWidth', Math.floor($("#gridDialog_c")[0].offsetWidth - $("#gridDialog_c")[0].offsetLeft));
	// 		if (selText == '#itemcodeS') {
	// 			var getClass = $('#Class2').val();
	// 			if (getClass == 'Pharmacy') {
	// 				paramD.filterCol = ['Class', 'recstatus'];
	// 				paramD.filterVal = ['Pharmacy', 'A'];
	// 			} else if (getClass == 'Non-Pharmacy') {
	// 				paramD.filterCol = ['Class', 'recstatus'];
	// 				paramD.filterVal = ['Non-Pharmacy', 'A'];
	// 			}
	// 			else if (getClass == 'Others') {
	// 				paramD.filterCol = ['Class', 'recstatus'];
	// 				paramD.filterVal = ['Others', 'A'];
	// 			}
	// 			else if (getClass == 'Asset') {
	// 				paramD.filterCol = ['Class', 'recstatus'];
	// 				paramD.filterVal = ['Asset', 'A'];
	// 			} else if (getClass == 'All') {
	// 				paramD.filterCol = ['recstatus'];
	// 				paramD.filterVal = ['A'];
	// 			}
	// 		} else if (selText == '#uomcodeS') {
	// 			paramD.table_name = ['material.product']; //'material.uom'
	// 			paramD.field = ['product.uomcode']; //'uom.uomcode'
	// 			//paramD.join_type=['JOIN'];
	// 			//paramD.join_onCol=['product.uomcode'];
	// 			//paramD.join_onVal=['uom.uomcode'];
	// 			paramD.filterCol = ['product.itemcode', 'product.recstatus'];
	// 			paramD.filterVal = [$('#itemcodeS').val(), 'A'];
	// 		} else {
	// 			paramD.filterCol = ['recstatus'];
	// 			paramD.filterVal = ['A'];
	// 		}
	// 	},
	// 	close: function (event, ui) {
	// 		paramD.searchCol = ['recstatus'];
	// 		paramD.searchVal = ['A'];
	// 	},
	// });

	// var selText, Dtable, Dcols, fromdblclick = false;
	// $("#gridDialog").jqGrid({
	// 	datatype: "local",
	// 	colModel: [
	// 		{ label: 'Code', name: 'code', width: 200, classes: 'pointer', canSearch: true, checked: true },
	// 		{ label: 'Description', name: 'desc', width: 400, canSearch: true, classes: 'pointer' },
	// 		{ label: 'Class', name: 'Class', width: 400, classes: 'pointer', hidden: true },
	// 		{ label: 'uomcode', name: 'uomcode', width: 400, classes: 'pointer', hidden: true },
	// 	],
	// 	width: 500,
	// 	autowidth: true,
	// 	viewrecords: true,
	// 	loadonce: false,
	// 	multiSort: true,
	// 	rowNum: 30,
	// 	pager: "#gridDialogPager",
	// 	ondblClickRow: function (rowid, iRow, iCol, e) {
	// 		var data = $("#gridDialog").jqGrid('getRowData', rowid);
	// 		$("#gridDialog").jqGrid("clearGridData", true);
	// 		$("#dialog").dialog("close");
	// 		$(selText).val(rowid);
	// 		$(selText).focus();
	// 		$(selText).parent().next().html(data['desc']);

	// 		if (selText == "#itemcodeS") {
	// 			fromdblclick = true;
	// 			itemcode = data.itemcode;
	// 			uomcode = data.uomcode;
	// 			Class = data.Class;
	// 			console.log(uomcode);
	// 			$("#uomcodeS").focus();
	// 			//$("#uomcodeS").val(uomcode);
	// 		}
	// 	},

	// });

	// var paramD = { action: 'get_table_default', table_name: '', field: '', table_id: '', filter: '' };
	// function dialogHandler(errorField) {
	// 	var table = this.table, id = this.id, cols = this.cols, title = this.title, self = this;
	// 	$(id + " ~ a").on("click", function () {
	// 		selText = id, Dtable = table, Dcols = cols,
	// 			$("#gridDialog").jqGrid("clearGridData", true);


	// 		paramD.table_name = table;
	// 		paramD.field = cols;
	// 		paramD.table_id = cols[0];
	// 		paramD.join_type = null;
	// 		paramD.join_onCol = null;
	// 		paramD.join_onVal = null;
	// 		paramD.filterCol = null;
	// 		paramD.filterVal = null;



	// 		switch(id){
	// 			case "#uomcodeS":
	// 				var itemcodeS = $('#itemcodeS').val();

	// 				paramD.table_name=['material.product'];
	// 				paramD.field=['product.uomcode'];
	// 				//paramD.join_type=['JOIN'];
	// 				//paramD.join_onCol=['product.uomcode'];
	// 				//paramD.join_onVal=['uom.uomcode'];
	// 				//paramD.table_id = 'none_';
	// 				paramD.filterCol=['product.itemcode', 'product.recstatus'];
	// 				paramD.filterVal=[itemcodeS, 'A'];
	// 				break;
	// 			default:
	// 				paramD.filterCol=['recstatus'];
	// 				paramD.filterVal=['A'];
	// 				break;
	// 		}


	// 		$("#dialog").dialog("open");
	// 		$("#dialog").dialog("option", "title", title);

	// 		$("#gridDialog").jqGrid('setGridParam', { datatype: 'json', url: '../../../../assets/php/entry.php?' + $.param(paramD) }).trigger('reloadGrid');
	// 		$('#Dtext').val(''); $('#Dcol').html('');

	// 		$.each($("#gridDialog").jqGrid('getGridParam', 'colModel'), function (index, value) {
	// 			if (value['canSearch']) {
	// 				if (value['checked']) {
	// 					$("#Dcol").append("<label class='radio-inline'><input type='radio' name='dcolr' value='" + cols[index] + "' checked>" + value['label'] + "</input></label>");
	// 				} else {
	// 					$("#Dcol").append("<label class='radio-inline'><input type='radio' name='dcolr' value='" + cols[index] + "' >" + value['label'] + "</input></label>");
	// 				}
	// 			}
	// 		});
	// 	});
	// 	$(id).on("blur", function () {
	// 		self.check(errorField);
	// 	});
	// }

	// function checkInput(errorField) {
	// 	var table = this.table, id = this.id, field = this.cols, value = $(this.id).val()
	// 	var param = { action: 'input_check', table: table, field: field, value: value };
	// 	$.get("../../../../assets/php/entry.php?" + $.param(param), function (data) {

	// 	}, 'json').done(function (data) {
	// 		if (data.msg == 'success') {
	// 			if ($.inArray(id, errorField) !== -1) {
	// 				errorField.splice($.inArray(id, errorField), 1);
	// 			}
	// 			$(id).parent().removeClass("has-error").addClass("has-success");
	// 			$(id).removeClass("error").addClass("valid");
	// 			$(id).parent().siblings(".help-block").html(data.row[field[1]]);

	// 			if (id == "#itemcodeS") {
	// 				dialog_uomcode.handler(errorField);
	// 				//$('#uomcodeS').prop("readonly",false);
	// 				itemcode = data.row.itemcode;
	// 				Class = data.row.Class;
	// 				uomcode = data.row.uomcode;
	// 				// $("#uomcodeS").val(uomcode);
	// 				var getClass = $('#Class2').val();

	// 				if (getClass == Class) {
	// 					console.log("same")
	// 					dialog_uomcode.handler(errorField);
	// 					//$('#uomcodeS').prop("readonly",false);
	// 					$(id).parent().removeClass("has-error").addClass("has-success");
	// 					$(id).removeClass("error").addClass("valid");
	// 					$(id).parent().siblings(".help-block").html(data.row[field[1]]);
	// 				} else if (getClass != Class) {
	// 					console.log("not");
	// 					dialog_uomcode.offHandler();
	// 					//$('#uomcodeS').prop("readonly",true);
	// 					$(id).parent().removeClass("has-success").addClass("has-error");
	// 					$(id).removeClass("valid").addClass("error");
	// 					$(id).parent().siblings(".help-block").html("Invalid Code ( " + field[0] + " )");
	// 				}

	// 				if (getClass == 'All') {
	// 					console.log("for All");
	// 					dialog_uomcode.handler(errorField);
	// 					//$('#uomcodeS').prop("readonly",false);
	// 					$(id).parent().removeClass("has-error").addClass("has-success");
	// 					$(id).removeClass("error").addClass("valid");
	// 					$(id).parent().siblings(".help-block").html(data.row[field[1]]);
	// 				}

	// 			}
	// 		} else if (data.msg == 'fail') {
	// 			$(id).parent().removeClass("has-success").addClass("has-error");
	// 			$(id).removeClass("valid").addClass("error");
	// 			$(id).parent().siblings(".help-block").html("Invalid Code ( " + field[0] + " )");
	// 			if ($.inArray(id, errorField) === -1) {
	// 				errorField.push(id);
	// 			}
	// 			if (id == "#itemcode") {
	// 				dialog_uomcode.offHandler();
	// 				//$('#uomcodeS').prop("readonly",true);

	// 			}
	// 		}
	// 	});
	// }

	// $('#Dtext').keyup(function () {
	// 	delay(function () {
	// 		Dsearch($('#Dtext').val(), $('#checkForm input:radio[name=dcolr]:checked').val());
	// 	}, 500);
	// });

	// $('#Dcol').change(function () {
	// 	Dsearch($('#Dtext').val(), $('#checkForm input:radio[name=dcolr]:checked').val());
	// });

	// function Dsearch(Dtext, Dcol) {
	// 	paramD.searchCol = null;
	// 	paramD.searchVal = null;
	// 	Dtext = Dtext.trim();
	// 	if (Dtext != '') {
	// 		var split = Dtext.split(" "), searchCol = [], searchVal = [];
	// 		$.each(split, function (index, value) {
	// 			searchCol.push(Dcol);
	// 			searchVal.push('%' + value + '%');
	// 		});
	// 		paramD.searchCol = searchCol;
	// 		paramD.searchVal = searchVal;
	// 	}
	// 	refreshGrid("#gridDialog", paramD);
	// }
	///////////////////////////////finish->dialogHandler///part////////////////////////////////////////////

});

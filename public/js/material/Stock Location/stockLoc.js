
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

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
	var fdl = new faster_detail_load();


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
					delay(function(){
						dialog_itemcode_2.check(errorField);
					}, 500 );
				}
				if (oper != 'add') {
					dialog_deptcode.check(errorField);
				}
			},
			close: function (event, ui) {
				parent_close_disabled(false);
				emptyFormdata(errorField, '#formdata',['#year']);
				//$('.alert').detach();
				$('.my-alert').detach();
				dialog_deptcode.off();
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
		url:'util/get_table_default',
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
		url:'stockloc/form',
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
			{ label: 'Unit', name: 'unit', width: 50 },
			{ label: 'Department Code', name: 'deptcode', width: 50, classes: 'wrap',formatter: showdetail,unformat:un_showdetail},
			{ label: 'Year', name: 'year', width: 50, classes: 'wrap' },
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
		sortname: 'idno',
		sortorder: 'desc',
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

			fdl.set_array().reset();
		},

	});

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

		if (stocktxntype == 'TR') {
			$("#TRDS").prop("checked", true);
		}
		if (stocktxntype == 'IS') {
			$("#ISDS1").prop("checked", true);
		}
	});

	$("#search").click(function () {
		//$("#uomcodeS").focus();
		// dialog_itemcode.check(errorField);
		// dialog_uomcode.check(errorField);		

		if ($('#formdataSearch').isValid({ requiredFields: '' }, conf, true)) {
			$("#search").prop("disabled", true);
			$('#formdataSearch :input').prop("readonly", true);
			dialog_itemcode.off();
			dialog_uomcode.off();

			var currentDate = $("#select_year").val();

			urlParam.filterCol = ['itemcode', 'uomcode', 'year', 'unit', 'compcode'];
			urlParam.filterVal = [$('#itemcodeS').val(), $('#uomcodeS').val(), currentDate, 'session.unit', 'session.compcode'];

			refreshGrid('#jqGrid', urlParam);
			$("#pg_jqGridPager table").show();
		}
	});

	$("#cancel").click(function () {
		$("#pg_jqGridPager table").hide();
		emptyFormdata(errorField, '#formdataSearch',['#year']);
		$("#search").prop("disabled", false);
		$('#formdataSearch :input').prop("readonly", false);
		dialog_itemcode.on();
		dialog_uomcode.on();
		$("#itemcodeS").focus();
		$('#jqGrid').jqGrid('setGridParam', { datatype: 'local', url: '' }).trigger('reloadGrid');
	});

	function showdetail(cellvalue, options, rowObject){
		var field,table, case_;
		switch(options.colModel.name){
			case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='chggroup';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('stockLoc',options,param,case_,cellvalue);
		
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}


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
	addParamField('#jqGrid', false, saveParam, ['idno','compcode','adduser','adddate','upduser','upddate','recstatus','computerid','ipaddress']);

	////////////////////////////////////////////////////ordialog////////////////////////////////////////

	var dialog_itemcode = new ordialog(
		'itemcodeS','material.product','#itemcodeS',errorField,
		{	colModel:[
				{label:'Item Code',name:'itemcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
				{label:'Uom Code',name:'uomcode',width:100,classes:'pointer'},
				{label:'Class',name:'Class',width:400,classes:'pointer',hidden:true},
				{label:'Unit',name:'unit',classes:'pointer'},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_itemcode.gridname);
				$("#uomcodeS").val(data['uomcode']);
				$('#uomcodeS').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#uomcodeS').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}
		},{
			title:"Select Item",
			open: function(){
				console.log($('#Class2').val());
				if ($('#Class2').val().trim()  == 'Pharmacy') { 
					dialog_itemcode.urlParam.filterCol=['Class', 'recstatus','compcode','unit'];
					dialog_itemcode.urlParam.filterVal=['Pharmacy', 'ACTIVE','session.compcode','session.unit'];
				}else if ($('#Class2').val().trim()  == 'Non-Pharmacy'){
					dialog_itemcode.urlParam.filterCol=['Class', 'recstatus','compcode','unit'];
					dialog_itemcode.urlParam.filterVal=['Non-Pharmacy', 'ACTIVE','session.compcode','session.unit'];
				}else if ($('#Class2').val().trim()  == 'Others'){
					dialog_itemcode.urlParam.filterCol=['Class', 'recstatus','compcode','unit'];
					dialog_itemcode.urlParam.filterVal=['Others', 'ACTIVE','session.compcode','session.unit'];
				}else if ($('#Class2').val().trim()  == 'Asset'){
					dialog_itemcode.urlParam.filterCol=['Class', 'recstatus','compcode','unit'];
					dialog_itemcode.urlParam.filterVal=['Asset', 'ACTIVE','session.compcode','session.unit'];
				}else if ($('#Class2').val().trim()  == 'All'){
					dialog_itemcode.urlParam.filterCol=['recstatus','compcode','unit'];
					dialog_itemcode.urlParam.filterVal=['ACTIVE','session.compcode','session.unit'];
				}
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_itemcode.makedialog();
	dialog_itemcode.on();


	var dialog_uomcode = new ordialog(
		'uomcodeS', ['material.product as p', 'material.uom as u'], "#uomcodeS", errorField,
		{
			colModel:
			[
				{ label: 'UOM code', name: 'p_uomcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'u_description', width: 400, classes: 'pointer', checked:true, canSearch: true, or_search: true },
				{label:'Unit',name:'p_unit',classes:'pointer'},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow: function () {
				let data = selrowData('#' + dialog_uomcode.gridname);
				$("#uomcodeS").val(data['p_uomcode']);
				$('#select_year').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#select_year').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}

		}, {
			title: "Select Uom",
			open: function () {
				dialog_uomcode.urlParam.fixPost = true;
				dialog_uomcode.urlParam.table_id = "uomcode";
				dialog_uomcode.urlParam.filterCol = ['p.itemcode','p.compcode','p.unit'];
				dialog_uomcode.urlParam.filterVal = [$("#itemcodeS").val(),'session.compcode','session.unit'];
				dialog_uomcode.urlParam.join_type = ['LEFT JOIN'];
				dialog_uomcode.urlParam.join_onCol = ['p.uomcode'];
				dialog_uomcode.urlParam.join_onVal = ['u.uomcode'];
				dialog_uomcode.urlParam.join_filterCol = [['p.compcode on =']];
				dialog_uomcode.urlParam.join_filterVal = [['u.compcode']];
			},
		}, 'urlParam', 'radio', 'tab'
	);
	dialog_uomcode.makedialog();
	dialog_uomcode.on();

	var dialog_deptcode = new ordialog(
		'deptcode','sysdb.department','#deptcode',errorField,
		{	colModel:[
				{label:'Dept Code',name:'deptcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode','sector'],
				filterVal:['ACTIVE','session.compcode','session.unit']
			},
			ondblClickRow:function(){
				$('#stocktxntype').focus();
			},
			gridComplete: function(obj){
						var gridname = '#'+obj.gridname;
						if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
							$(gridname+' tr#1').click();
							$(gridname+' tr#1').dblclick();
							$('#stocktxntype').focus();
						}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
							$('#'+obj.dialogname).dialog('close');
						}
					}	
		},{
			title:"Select Dept",
			open: function(){
				dialog_deptcode.urlParam.filterCol = ['recstatus','compcode','sector'];
				dialog_deptcode.urlParam.filterVal = [ 'ACTIVE','session.compcode','session.unit'];
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_deptcode.makedialog();

	var dialog_itemcode_2 = new ordialog(
		'itemcode','material.productmaster','#itemcode',errorField,
		{	colModel:[
				{label:'Item Code',name:'itemcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],
				filterVal:['ACTIVE','session.compcode']
			},
			ondblClickRow:function(){
			},
			gridComplete: function(obj){
			}	
		},{
			title:"Select Dept",
			open: function(){
				dialog_itemcode_2.urlParam.filterCol = ['recstatus','compcode'];
				dialog_itemcode_2.urlParam.filterVal = [ 'ACTIVE','session.compcode'];
			}
		},'urlParam', 'radio', 'tab'
	);
	dialog_itemcode_2.makedialog();

});

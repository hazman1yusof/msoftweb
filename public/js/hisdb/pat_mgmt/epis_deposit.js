var saveParam_dep={	
	action:'receipt_save',
	url: 'receipt/form',
	oper:'add',
	field:'',
	table_name:'debtor.dbacthdr',
	table_id:'auditno',
	sysparam:{source:'PB',trantype:'RD',useOn:'auditno'}
};

var urlParam_dep={
	action:'maintable',
	url: './receipt/table',
	field:'',
	mrn:'',
	episno:'',
};
var oper_dep = 'add';
var tabform_dep="#f_tab-cash";
var errorField_dep=[];
var	conf_deposit = {
		onValidate : function($form) {
			if(errorField_dep.length>0){
				return [{
					element : $('#'+$form.attr('id')+' input[name='+errorField_dep[0]+']'),
					message : ''
				}];
			}
		},
	};

var fdl_dep = new faster_detail_load();

var mycurrency_dep =new currencymode(['#f_tab-cash input[name=dbacthdr_amount]','#f_tab-cash input[name=dbacthdr_outamount]','#f_tab-cash input[name=dbacthdr_RCCASHbalance]','#f_tab-cash input[name=dbacthdr_RCFinalbalance]','#f_tab-card input[name=dbacthdr_amount]','#f_tab-card input[name=dbacthdr_outamount]','#f_tab-card input[name=dbacthdr_RCFinalbalance]','#f_tab-cheque input[name=dbacthdr_amount]','#f_tab-cheque input[name=dbacthdr_outamount]','#f_tab-cheque input[name=dbacthdr_RCFinalbalance]','#f_tab-debit input[name=dbacthdr_amount]','#f_tab-debit input[name=dbacthdr_outamount]','#f_tab-debit input[name=dbacthdr_RCFinalbalance]','#f_tab-debit input[name=dbacthdr_bankcharges]','#f_tab-forex input[name=dbacthdr_amount]','#f_tab-forex input[name=dbacthdr_amount2]','#f_tab-forex input[name=dbacthdr_RCFinalbalance]','#f_tab-forex input[name=dbacthdr_outamount]']);

var def_tillcode,def_tillno;
$(document).ready(function () {
	var dialog_till = new ordialog(
		'till','debtor.till','#tilldetTillcode','errorField',
		{	colModel:[
				{label:'Till Code',name:'tillcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Till Name',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Till Status',name:'tillstatus',hidden:true},
				{label:'defopenamt',name:'defopenamt',hidden:true}
			],
			urlParam: {
						filterCol:['compcode','recstatus', 'tillstatus'],
						filterVal:['session.compcode','ACTIVE','C']
					},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_till.gridname);
				$( "#tilldetCheck" ).button( "option", "disabled", false );
				$( "#openamt" ).val(data.defopenamt);
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Till",
			open: function(){
				dialog_till.urlParam.filterCol=['recstatus', 'compcode', 'tillstatus'],
				dialog_till.urlParam.filterVal=['ACTIVE', 'session.compcode','C']
				}
			},'urlParam','radio','tab'
		);
	dialog_till.makedialog(true);

	function updateTillUsage(){
		var param={
				action:'use_till',
				url:'till/form',
			};

		var obj = {
			_token:$('#csrf_token').val(),
			cashier:$('#cashier').val(),
			tillcode:$('#tilldetTillcode').val(),
			openamt:$('#openamt').val()
		}
		$.post( param.url+"?"+$.param(param),obj, 
			function( data ) {
				
			}
		).fail(function(data) {
			alert('Error');
		}).done(function(data){
			$('#cashier').val('1');
			$("#tilldet").dialog('close');
			addnew_deposit();
		});
	}

	$( "#tilldet" ).dialog({
		autoOpen: false,
		width: 5/10 * $(window).width(),
		modal: true,
		open: function() { 
			$('div[aria-describedby="tilldet"]').css("z-index", "1100");
			$('div.ui-widget-overlay.ui-front').css("z-index", "1099");
			// $(this).parent().find(".ui-dialog-titlebar-close").hide();                       
		},
		buttons: [
			{
				text:'Open Till',
				disabled: true,
				id: "tilldetCheck",
				click:function(){
					updateTillUsage();
				}
			},{
				text:'Reset',
				click:function(){
					emptyFormdata([],'#formTillDet');
					$( "#tilldetCheck" ).button( "option", "disabled", true );
				}
			},
		],
		closeOnEscape: false,
	});

	///////////////////////////////////////////trantype//////////////////////
	var urlParam_sys={
		action:'get_table_default',
		url: 'util/get_table_default',
		field:['source','trantype','description','hdrtype','updpayername','depccode','depglacc','updepisode','manualalloc'],
		table_name:'debtor.hdrtypmst',
		table_id:'hdrtype',
		filterCol:['compcode','recstatus'],
		filterVal:['session.compcode','ACTIVE']
	}

	$("#sysparam_dep").jqGrid({
		datatype: "local",
		 colModel: [
			{label: 'source', name: 'source', width: 60, hidden:true},
			{label: 'Tran type', name: 'trantype', width: 60, hidden:true},
			{label: 'Description', name: 'description', width: 150 },
			{label: 'hdrtype', name: 'hdrtype', width: 150, hidden:true},
			{label: 'updpayername', name: 'updpayername', width: 150, hidden:true},
			{label: 'depccode', name: 'depccode', width: 150, hidden:true},
			{label: 'depglacc', name: 'depglacc', width: 150, hidden:true},
			{label: 'updepisode', name: 'updepisode', width: 150, hidden:true},
			{label: 'manualalloc', name: 'manualalloc', width: 10, hidden:true},
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		width: 300,
		height: 80,
		rowNum: 10,
		gridComplete: function(rowid){
			$("#sysparam_dep").setSelection($("#sysparam_dep").getDataIDs()[0]);
		},
		onSelectRow:function(rowid, selected){
			if(rowid != null) {
				var rowData = $('#sysparam_dep').jqGrid ('getRowData', rowid);
				$('#dbacthdr_trantype').val(rowData['trantype']);
				saveParam_dep.sysparam.trantype=rowData['trantype'];
				$('#dbacthdr_PymtDescription').val(rowData['description']);
				if($("#formdata_deposit input:radio[name='optradio'][value='deposit']").is(':checked')){
					$("#formdata_deposit input:hidden[name='dbacthdr_hdrtype']").val(rowData['hdrtype']);
					$("#formdata_deposit input:hidden[name='updepisode']").val(rowData['updepisode']);
					$("#formdata_deposit input:hidden[name='updpayername']").val(rowData['updpayername']);
					$("#formdata_deposit input[name='dbacthdr_crcostcode']").val(rowData['depccode']);
					$("#formdata_deposit input[name='dbacthdr_cracc']").val(rowData['depglacc']);

					if(rowData['updpayername'] == 1){
						$('#dbacthdr_payername').prop('readonly',false);
					}else{
						$('#dbacthdr_payername').prop('readonly',true);
					}

					// if(rowData['updepisode'] == 1){
					// 	$('#dbacthdr_episno_div').show();
					// }else{
					// 	$('#dbacthdr_episno_div').hide();
					// }
				}
			}
		},
		beforeSelectRow: function(rowid, e) {
			// if(oper=='view'){
			// 	//$('#'+$("#sysparam").jqGrid ('getGridParam', 'selrow')).focus();
			// 	return false;
			// }
		}
	});

	/////////////////////////////////////////End Transaction typr////////////////////////////

	///////////////////////////////////////////Bank Paytype/////////////////////////////////
	var urlParam_bank={
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		table_name: 'debtor.paymode',
		table_id: 'paymode',
		filterCol: ['source','paytype','compcode','paymode'],
		filterVal: ['AR','BANK','session.compcode',''],
	}
	
	$("#g_paymodebank_dep").jqGrid({
		datatype: "local",
		 colModel: [
			{label: 'Pay Mode', name: 'paymode', width: 60},
			{label: 'Description', name: 'description', width: 150 },
			{label: 'ccode', name: 'ccode', hidden: true },
			{label: 'glaccno', name: 'glaccno', hidden: true },
		],
		autowidth:true,
		multiSort: true,
		loadonce:true,
		width: 300,
		height: 150,
		rowNum: 2000,
		onSelectRow:function(rowid, selected){
			if(rowid != null) {
				var rowData = $('#g_paymodebank_dep').jqGrid ('getRowData', rowid);
				$("#f_tab-debit .form-group input[name='dbacthdr_paymode']").val(rowData['paymode']);
				$("#formdata_deposit input[name='dbacthdr_drcostcode']").val(rowData['ccode']);
				$("#formdata_deposit input[name='dbacthdr_dracc']").val(rowData['glaccno']);
			}
		},
		beforeSelectRow: function(rowid, e) {
			if(oper_dep=='view'){
				$('#'+$("#g_paymodebank_dep").jqGrid ('getGridParam', 'selrow')).focus();
				return false;
			}
		}
	});

	$("#g_paymodebank_dep").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
	addParamField('#g_paymodebank_dep',false,urlParam_bank);
	////////////////////////////////////////////End Bank Paytype//////////////////////////////////////

	///////////////////////////////////////////Card paytype//////////////////////////////////////////////
	var urlParam_card={
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		table_name: 'debtor.paymode',
		table_id: 'paymode',
		filterCol: ['source','paytype','compcode','paymode'],
		filterVal: ['AR','CARD','session.compcode',''],
	}
	
	$("#g_paymodecard_dep").jqGrid({
		datatype: "local",
		 colModel: [
			{label: 'Pay Mode', name: 'paymode', width: 60},
			{label: 'Description', name: 'description', width: 150 },
			{label: 'ccode', name: 'ccode', hidden: true },
			{label: 'glaccno', name: 'glaccno', hidden: true },
			{label: 'cardflag', name: 'cardflag', hidden: true },
			{label: 'valexpdate', name: 'valexpdate', hidden: true },
		],
		autowidth:true,
		multiSort: true,
		loadonce:true,
		width: 300,
		height: 150,
		rowNum: 2000,
		onSelectRow:function(rowid, selected){
			if(rowid != null) {
				var rowData = $('#g_paymodecard_dep').jqGrid ('getRowData', rowid);
				$("#f_tab-card .form-group input[name='dbacthdr_paymode']").val(rowData['paymode']);
				if(rowData['cardflag'] == '1'){
					$("#f_tab-card .form-group input[name='dbacthdr_reference']").attr("data-validation","required");
				}else{
					$("#f_tab-card .form-group input[name='dbacthdr_reference']").attr("data-validation","");

				}

				if(rowData['valexpdate'] == '1'){
					$("#f_tab-card .form-group input[name='dbacthdr_expdate']").attr("data-validation","required");
				}else{
					$("#f_tab-card .form-group input[name='dbacthdr_expdate']").attr("data-validation","");
				}

				$("#formdata_deposit input[name='dbacthdr_drcostcode']").val(rowData['ccode']);
				$("#formdata_deposit input[name='dbacthdr_dracc']").val(rowData['glaccno']);
			}
		},
		beforeSelectRow: function(rowid, e) {
			if(oper_dep=='view'){
				$('#'+$("#g_paymodecard_dep").jqGrid ('getGridParam', 'selrow')).focus();
				return false;
			}
		}
	});

	$("#g_paymodecard_dep").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
	addParamField('#g_paymodecard_dep',false,urlParam_card);
	///////////////////////////////////end card////////////////////////////////////////////


	////////////////////////////////forex////////////////////////////////////////////////
	var urlParam_forex={
		action:'get_effdate',
		type:'forex'
	}
	$("#g_forex_dep").jqGrid({
		datatype: "local",
		 colModel: [
			{label: 'Forex Code', name: 'forexcode', width: 60},
			{label: 'Description', name: 'description', width: 150 },
			{label: 'costcode', name: 'costcode', hidden: true },
			{label: 'glaccount', name: 'glaccount' , hidden: true},
			{label: 'Rate', name: 'rate', width: 50 },
			{label: 'effdate', name: 'effdate', width: 50  , hidden: true},
		],
		autowidth:true,
		multiSort: true,
		loadonce:true,
		width: 300,
		height: 150,
		rowNum: 2000,
		onSelectRow:function(rowid, selected){
			if(rowid != null) {
				rowData = $('#g_forex_dep').jqGrid ('getRowData', rowid);
				$("#f_tab-forex input[name='dbacthdr_paymode']").val("forex");
				$("#f_tab-forex input[name='curroth']").val(rowData['forexcode']);
				$("#f_tab-forex input[name='dbacthdr_rate']").val(rowData['rate']);
				$("#f_tab-forex input[name='dbacthdr_currency']").val(rowData['forexcode']);
				$("#formdata_deposit input[name='dbacthdr_drcostcode']").val(rowData['costcode']);
				$("#formdata_deposit input[name='dbacthdr_dracc']").val(rowData['glaccount']);

				$("#f_tab-forex input[name='dbacthdr_amount']").on('blur',{data:rowData,type:'RM'},currencyChg_dep);

				$("#f_tab-forex input[name='dbacthdr_amount2']").on('blur',{data:rowData,type:'oth'},currencyChg_dep);
			}
		},
		beforeSelectRow: function(rowid, e) {
			if(oper_dep=='view'){
				$('#'+$("#g_forex_dep").jqGrid ('getGridParam', 'selrow')).focus();
				return false;
			}
		}
	});

	function currencyChg_dep(event){
		var curval;
		mycurrency_dep.formatOff();
		if(event.data.type == 'RM'){
			curval = $("#f_tab-forex input[name='dbacthdr_amount']").val();
			$("#f_tab-forex input[name='dbacthdr_amount2']").val(parseFloat(curval)*parseFloat(event.data.data.rate));
		}else if(event.data.type == 'oth'){
			curval = $("#f_tab-forex input[name='dbacthdr_amount2']").val();
			$("#f_tab-forex input[name='dbacthdr_amount']").val(parseFloat(curval)/parseFloat(event.data.data.rate));
		}
		mycurrency_dep.formatOn();
	}

	$("#g_forex_dep").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
	addParamField('#g_forex_dep',false,urlParam_forex);
	//////////////////////////////// end forex////////////////////////////////////////////////////////

	var butt1_dep=[{
		text: "Save",click: function() {
			mycurrency_dep.formatOff();
			mycurrency_dep.check0value(errorField);
			if( $('#formdata_deposit').isValid({requiredFields: ''}, conf_deposit, true) && $(tabform_dep).isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata_receipt("#jqGrid_deposit","#dialogForm_deposit","#formdata_deposit",oper_dep,saveParam_dep,urlParam_dep);
			}else{
				mycurrency_dep.formatOn();
			}
		}
	},{
		text: "Cancel",click: function() {
			$(this).dialog('close');
		}
	}];
	
	var butt2_dep=[{
		text: "Close",click: function() {
			$(this).dialog('close');
		}
	}];

	$('#dialogForm_deposit .nav-tabs a').on('shown.bs.tab', function(e){
		tabform_dep=$(this).attr('form');
		rdonly(tabform_dep);
		// handleAmount();
		// mycurrency.formatOnBlur();
		$('#dbacthdr_paytype').val(tabform_dep);
		switch(tabform_dep) {
			case '#f_tab-cash':
				getcr('CASH');
				break;
			case '#f_tab-card':
				if(oper_dep=="view"){
					urlParam_card.filterCol = ['source','paytype','compcode','paymode'];
					urlParam_card.filterVal = ['AR','CARD','session.compcode',selrowData('#jqGrid_deposit').dbacthdr_paymode];
					refreshGrid("#g_paymodecard_dep",urlParam_card);
				}else{
					urlParam_card.filterCol = ['source','paytype','compcode'];
					urlParam_card.filterVal = ['AR','CARD','session.compcode'];
					refreshGrid("#g_paymodecard_dep",urlParam_card);
				}
				break;
			case '#f_tab-cheque':
				getcr('cheque');
				break;
			case '#f_tab-debit':
				if(oper_dep=="view"){
					urlParam_bank.filterCol = ['source','paytype','compcode','paymode'];
					urlParam_bank.filterVal = ['AR','BANK','session.compcode',selrowData('#jqGrid_deposit').dbacthdr_paymode];
					refreshGrid("#g_paymodebank_dep",urlParam_bank);
				}else{
					urlParam_bank.filterCol = ['source','paytype','compcode'];
					urlParam_bank.filterVal = ['AR','BANK','session.compcode'];
					refreshGrid("#g_paymodebank_dep",urlParam_bank);
				}
				break;
			case '#f_tab-forex':
				refreshGrid("#g_forex_dep",urlParam_forex);
				break;
		}
		$("#g_paymodecard_dep").jqGrid ('setGridWidth', $("#g_paymodecard_dep_c")[0].clientWidth);
		$("#g_paymodebank_dep").jqGrid ('setGridWidth', $("#g_paymodebank_dep_c")[0].clientWidth);
		$("#g_forex_dep").jqGrid ('setGridWidth', $("#g_forex_dep_c")[0].clientWidth);
	});

	$("#dialogForm_deposit")
		.dialog({
			width: 9/10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function( event, ui ) {
				$('div[aria-describedby="dialogForm_deposit"]').css("z-index", "1100");
				$('div.ui-widget-overlay.ui-front').css("z-index", "1099");

				$("#sysparam_dep").jqGrid ('setGridWidth', Math.floor($("#sysparam_dep_c")[0].offsetWidth));
				$("#g_paymodecard_dep").jqGrid ('setGridWidth', $("#g_paymodecard_dep_c")[0].clientWidth);
				$("#g_paymodebank_dep").jqGrid ('setGridWidth', $("#g_paymodebank_dep_c")[0].clientWidth);
				$("#g_forex_dep").jqGrid ('setGridWidth', $("#g_forex_dep_c")[0].clientWidth);

				switch(oper_dep) {
					case 'add':
						// mycurrency_dep.formatOnBlur();
						$('#dbacthdr_paytype').val(tabform_dep);
						$( this ).dialog( "option", "title", "Add" );
						enableForm('#formdata_deposit');
						enableForm('.tab-content');
						rdonly('#formdata_deposit');
						rdonly(tabform_dep);
						$('#dbacthdr_mrn').val($("#mrn_episode").val());
						$('#dbacthdr_episno').val($("#txt_epis_no").val());
						break;
					case 'view':
						mycurrency_dep.formatOn();
						$( this ).dialog( "option", "title", "View" );
						disableForm('#formdata_deposit');
						disableForm('.tab-content');
						rdonly('#formdata_deposit');
						disableForm(selrowData('#jqGrid').dbacthdr_paytype);
						$(this).dialog("option", "buttons",butt2_dep);
				}
				if(oper_dep!='view'){
					dialog_payercode_dep.on();
				}
				if(oper_dep!='add'){
					dialog_payercode_dep.check(errorField_dep);
					showingForCash(selrowData("#jqGrid").dbacthdr_amount,selrowData("#jqGrid").dbacthdr_outamount,selrowData("#jqGrid").dbacthdr_RCCASHbalance,selrowData("#jqGrid").dbacthdr_RCFinalbalance,selrowData("#jqGrid").dbacthdr_paytype);
				}
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField_dep,'#formdata_deposit');
				emptyFormdata(errorField_dep, "#f_tab-cash");
				emptyFormdata(errorField_dep, "#f_tab-card");
				emptyFormdata(errorField_dep, "#f_tab-cheque");
				emptyFormdata(errorField_dep, "#f_tab-debit");
				emptyFormdata(errorField_dep, '#f_tab-forex');
				$("#formdata_deposit a").off();
				if(oper_dep=='view'){
					$(this).dialog("option", "buttons",butt1_dep);
				}
			},
			buttons :butt1_dep,
		});

	$("#jqGrid_deposit").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'compcode', name: 'dbacthdr_compcode', width: 90, hidden: true },
			{ label: 'auditno', name: 'dbacthdr_auditno', width: 90, hidden: true },
			{ label: 'lineno_', name: 'dbacthdr_lineno_', width: 90, hidden: true },
			{ label: 'billdebtor', name: 'dbacthdr_billdebtor', hidden: true },
			{ label: 'conversion', name: 'dbacthdr_conversion', hidden: true },
			{ label: 'currency', name: 'dbacthdr_currency', hidden: true },
			{ label: 'tillcode', name: 'dbacthdr_tillcode', hidden: true },
			{ label: 'tillno', name: 'dbacthdr_tillno', hidden: true },
			{ label: 'debtortype', name: 'dbacthdr_debtortype', hidden: true },
			{ label: 'Date', name: 'dbacthdr_adddate',width: 50, formatter: dateFormatter, unformat: dateUNFormatter, hidden:true }, //tunjuk
			{ label: 'Posted Date', name: 'dbacthdr_posteddate',width: 50, formatter: dateFormatter, unformat: dateUNFormatter }, 
			{ label: 'Trantype', name: 'dbacthdr_trantype', width: 45, formatter: showdetail_deposit, unformat:un_showdetail },
			{ label: 'Type', name: 'dbacthdr_PymtDescription', classes: 'wrap', width: 50, hidden:true}, //tunjuk
			{ label: 'Receipt No.', name: 'dbacthdr_recptno', classes: 'wrap',width: 60, canSearch:true }, //tunjuk
			{ label: 'Date', name: 'dbacthdr_entrydate',width: 40,formatter: dateFormatter, unformat: dateUNFormatter, hidden:true },
			{ label: 'entrydate', name: 'dbacthdr_entrytime', hidden: true },
			{ label: 'entrydate', name: 'dbacthdr_entryuser', hidden: true },
			{ label: 'Payer Code', name: 'dbacthdr_payercode', width: 100, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail_deposit, unformat:un_showdetail },
			{ label: 'Payer Name', name: 'dbacthdr_payername', width: 150, classes: 'wrap text-uppercase', hidden: true },//tunjuk
			// { label: 'Debtor Code', name: 'dbacthdr_debtorcode', width: 400, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail_deposit, unformat:un_showdetail },
			{ label: 'MRN', name: 'dbacthdr_mrn',align:'right', width: 50 }, //tunjuk
			{ label: 'Epis', name: 'dbacthdr_episno',align:'right', width: 40 }, //tunjuk
			{ label: 'Patient Name', name: 'name', width: 150, classes: 'wrap', hidden: true },
			{ label: 'remark', name: 'dbacthdr_remark', hidden: true },
			{ label: 'epistype', name: 'dbacthdr_epistype', hidden: true },
			{ label: 'cbflag', name: 'dbacthdr_cbflag', hidden: true },
			{ label: 'reference', name: 'dbacthdr_reference', hidden: true },
			{ label: 'Payment Mode', name: 'dbacthdr_paymode', width: 70, classes: 'wrap text-uppercase', formatter: showdetail_deposit, unformat:un_showdetail },	//tunjuk
			{ label: 'Expiry Date', name: 'dbacthdr_expdate', width: 50, align:'right',
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'m/Y'},
			},
			{ label: 'Authorization<br>No', name: 'dbacthdr_authno', width: 50, align:'right' },
			{ label: 'Amount', name: 'dbacthdr_amount', width: 50, align:'right',formatter:'currency',formatoptions:{prefix: ""} }, //tunjuk
			{ label: 'O/S Amount', name: 'dbacthdr_outamount', width: 50,align:'right',formatter:'currency',formatoptions:{prefix: ""} }, //tunjuk
			{ label: 'source', name: 'dbacthdr_source', hidden: true, checked:true },
			{ label: 'Status', name: 'dbacthdr_recstatus',width: 50 }, //tunjuk
			{ label: 'Header', name: 'dbacthdr_hdrtype', width:50},
			{ label: 'bankchg', name: 'dbacthdr_bankcharges', hidden: true },
			{ label: 'rate', name: 'dbacthdr_rate', hidden: true },
			{ label: 'units', name: 'dbacthdr_unit', hidden: true },
			{ label: 'invno', name: 'dbacthdr_invno', hidden: true },
			{ label: 'paytype', name: 'dbacthdr_paytype', hidden: true },
			{ label: 'RCcashbalance', name: 'dbacthdr_RCCASHbalance', hidden: true },
			{ label: 'RCFinalbalance', name: 'dbacthdr_RCFinalbalance', hidden: true },
			{ label: 'RCOSbalance', name: 'dbacthdr_RCOSbalance', hidden: true },
			{ label: 'idno', name: 'dbacthdr_idno', hidden: true },
			{ label: 'paycard_description', name: 'paycard_description', hidden: true },
			{ label: 'paybank_description', name: 'paybank_description', hidden: true },
		],
		autowidth:true,
		//multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'dbacthdr_idno',
		sortorder:'desc',
		width: 900,
		height: 300,
		rowNum: 30,
		pager: "#jqGridPager_deposit",
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager_deposit td[title='View Selected Row']").click();
		},
		onSelectRow: function(rowid){
		},
		gridComplete: function(){
			fdl_dep.set_array().reset();
		},
		loadComplete:function(data){
		}
	});
	addParamField('#jqGrid_deposit',false,urlParam_dep);

	$("#jqGrid_deposit").jqGrid('navGrid','#jqGridPager_deposit',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid_deposit",urlParam_dep);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager_deposit",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-info-sign",
		title:"View Selected Row",  
		onClickButton: function(){
			delay(function(){
				$("#sysparam").jqGrid('setSelection', selrowData('#jqGrid').dbacthdr_hdrtype, true);
				$("#g_paymodebank").jqGrid('setSelection', selrowData('#jqGrid').dbacthdr_paymode, true);
				$("#g_paymodecard").jqGrid('setSelection', selrowData('#jqGrid').dbacthdr_paymode, true);
				$("#g_forex").jqGrid('setSelection', selrowData('#jqGrid').dbacthdr_paymode, true);
			}, 500 );

			resetpill();

			var selform=selrowData('#jqGrid_deposit').dbacthdr_paytype;
			var selRowId = $("#jqGrid_deposit").jqGrid ('getGridParam', 'selrow');
			$("#dialogForm_deposit .nav-tabs a[form='"+selform.toLowerCase()+"']").tab('show');
			disabledPill();
			populateFormdata("#jqGrid_deposit","",selform.toLowerCase(),selRowId,'view',['dbacthdr_expdate']);
			populateFormdata("#jqGrid_deposit","","#formdata_deposit",selRowId,'view');
			$("#dialogForm_deposit").dialog( "open" );
		},
	}).jqGrid('navButtonAdd',"#jqGridPager_deposit",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-plus", 
		title:"Add New Row", 
		onClickButton: function(){
			if($('#cashier').val() == '1'){
				addnew_deposit();
			}else{
				$("#tilldet").dialog('open');
			}
		},
	});

	function addnew_deposit(){
		oper_dep='add';
		resetpill();
		$('#dialogForm_deposit #dbacthdr_recptno').hide();
		$("#dialogForm_deposit .nav-tabs a[form='#f_tab-cash']").tab('show');
		enabledPill();
		$("#dialogForm_deposit").dialog( "open" );
	}

	$("#tabDeposit").on("shown.bs.collapse", function(){
		$("#jqGrid_deposit").jqGrid ('setGridWidth', Math.floor($("#jqGrid_deposit_c")[0].offsetWidth-$("#jqGrid_deposit_c")[0].offsetLeft-0));
		urlParam_dep.mrn = $("#mrn_episode").val();
		urlParam_dep.episno = $("#txt_epis_no").val();
		refreshGrid("#jqGrid_deposit", urlParam_dep);
		refreshGrid("#sysparam_dep", urlParam_sys);
		refreshGrid("#g_paymodebank_dep", urlParam_bank);
		refreshGrid("#g_paymodecard_dep", urlParam_card);
		// refreshGrid("#g_forex_dep", urlParam_forex);

	});

	var dialog_payercode_dep = new ordialog(
		'payercode','debtor.debtormast','#dbacthdr_payercode',errorField_dep,
		{	colModel:[
				{label:'Debtor Code',name:'debtorcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Debtor Name',name:'name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'debtortype',name:'debtortype',hidden:true},
				{label:'actdebccode',name:'actdebccode',hidden:true},
				{label:'actdebglacc',name:'actdebglacc',hidden:true},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_payercode_dep.gridname);
				//$('#apacthdr_actdate').focus();
				$('#dbacthdr_payername').val(data.name);
				$('#dbacthdr_debtortype').val(data.debtortype);
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					//$('#apacthdr_actdate').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Payer",
			open: function(){
				dialog_payercode_dep.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_payercode_dep.urlParam.filterVal=['ACTIVE', 'session.compcode']
			},
			close: function(){
				let data=selrowData('#'+dialog_payercode_dep.gridname);
				get_debtorcode_outamount(data.debtorcode);
				$('#dbacthdr_remark').focus();
			}
		  },'urlParam','radio','tab'
		);
	dialog_payercode_dep.makedialog(true);

});

function showdetail_deposit(cellvalue, options, rowObject){
	var field,table, case_;
	switch(options.colModel.name){
		case 'dbacthdr_payercode':field=['debtorcode','name'];table="debtor.debtormast";case_='dbacthdr_payercode';break;
		case 'dbacthdr_debtorcode':field=['debtorcode','name'];table="debtor.debtormast";case_='dbacthdr_debtorcode';break;
		case 'dbacthdr_paymode':field=['paymode','description'];table="debtor.paymode";case_='dbacthdr_paymode';break;
		case 'dbacthdr_trantype':field=['trantype','description'];table="sysdb.sysparam";case_='dbacthdr_trantype';break;
	}
	var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	fdl_dep.get_array('receipt',options,param,case_,cellvalue);
	
	if(cellvalue == null)cellvalue = " ";
	return cellvalue;
}

function resetpill(){
	$('#dialogForm_deposit ul.nav-tabs li').removeClass('active');
	$('#dialogForm_deposit ul.nav-tabs li a').attr('aria-expanded',false);
}

function disabledPill(){
	$('#dialogForm_deposit .nav li').not('.active').addClass('disabled');
	$('#dialogForm_deposit .nav li').not('.active').find('a').removeAttr("data-toggle");
	$('#dialogForm_deposit .nav li').not('.active').hide();
}

function enabledPill(){
	$('#dialogForm_deposit .nav li').removeClass('disabled');
	$('#dialogForm_deposit .nav li').find('a').attr("data-toggle","tab");
	$('#dialogForm_deposit .nav li').show();
}

function get_debtorcode_outamount(payercode){
	var param={
		url: './receipt/table',
		action:'get_debtorcode_outamount',
		payercode:payercode
	}

	$.get( param.url+"?"+$.param(param), function( data ) {
		
	},'json').done(function(data) {
		if(data.result == 'true'){
			$('#formdata_deposit input[name="dbacthdr_outamount"]').val(data.outamount);
		}else{
			// alert('Payer doesnt have outstanding amount');
		}
		mycurrency_dep.formatOn();
	});
}

function getcr(paytype){
	var param={
		action:'get_value_default',
		field:['glaccno','ccode'],
		url: 'util/get_value_default',
		table_name:'debtor.paymode',
		table_id:'paymode',
		filterCol:['paytype','source','compcode'],
		filterVal:[paytype,'AR','session.compcode'],
	}

	$.get( param.url+"?"+$.param(param), function( data ) {
		
	},'json').done(function(data) {
			$("#formdata_deposit input[name='dbacthdr_drcostcode']").val(data.rows[0].ccode);
			$("#formdata_deposit input[name='dbacthdr_dracc']").val(data.rows[0].glaccno);
	});
}

function saveFormdata_receipt(grid,dialog,form,oper,saveParam,urlParam,callback,uppercase=true){
	var formname = $(dialog+" a[aria-expanded='true']").attr('form')
	
	var paymentform =  $( formname ).serializeArray();
	
	$(dialog+' .ui-dialog-buttonset button[role=button]').prop('disabled',true);
	saveParam.oper=oper;
	
	let serializedForm = trimmall(form,uppercase);
	$.post( saveParam.url+'?'+$.param(saveParam), serializedForm+'&'+$.param(paymentform) , function( data ) {
		
	}).fail(function(data) {
		errorText(dialog.substr(1),data.responseText);
		$('.ui-dialog-buttonset button[role=button]').prop('disabled',false);
	}).success(function(data){
		if(grid!=null){
			refreshGrid(grid,urlParam,oper);
			$(dialog+' .ui-dialog-buttonset button[role=button]').prop('disabled',false);
			$(dialog).dialog('close');
		}
	});
}
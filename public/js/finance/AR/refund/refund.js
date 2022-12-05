$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {

	function setDateToNow(){
		$('input[name=dbacthdr_entrydate]').val(moment().format('YYYY-MM-DD'));
	}

	var mycurrency =new currencymode(['#f_tab-cash input[name=dbacthdr_amount]','#f_tab-cash input[name=dbacthdr_outamount]','#f_tab-cash input[name=dbacthdr_RCCASHbalance]','#f_tab-cash input[name=dbacthdr_RCFinalbalance]','#f_tab-card input[name=dbacthdr_amount]','#f_tab-card input[name=dbacthdr_outamount]','#f_tab-card input[name=dbacthdr_RCFinalbalance]','#f_tab-cheque input[name=dbacthdr_amount]','#f_tab-cheque input[name=dbacthdr_outamount]','#f_tab-cheque input[name=dbacthdr_RCFinalbalance]','#f_tab-debit input[name=dbacthdr_amount]','#f_tab-debit input[name=dbacthdr_outamount]','#f_tab-debit input[name=dbacthdr_RCFinalbalance]','#f_tab-debit input[name=dbacthdr_bankcharges]']);
	
	function disabledPill(){
		$('.nav li').not('.active').addClass('disabled');
		$('.nav li').not('.active').find('a').removeAttr("data-toggle");
	}

	function enabledPill(){
		$('.nav li').removeClass('disabled');
		$('.nav li').find('a').attr("data-toggle","tab");
	}

	function amountchgOn(fromtab){
		$("input[name='dbacthdr_outamount']").prop( "disabled", false );
		$("input[name='dbacthdr_RCCASHbalance']").prop( "disabled", false );
		$("input[name='dbacthdr_RCFinalbalance']").prop( "disabled", false );
		$("input[name='dbacthdr_amount']").off('blur',amountFunction);
		$("input[name='dbacthdr_outamount']").off('blur',amountFunction);
		$(tabform+" input[name='dbacthdr_amount']").on('blur',amountFunction);
		$(tabform+" input[name='dbacthdr_outamount']").on('blur',amountFunction);
	}

	function amountchgOff(fromtab){
		$("input[name='dbacthdr_amount']").off('blur',amountFunction);
		$("input[name='dbacthdr_outamount']").off('blur',amountFunction);
		$("input[name='dbacthdr_outamount']").prop( "disabled", true );
		$("input[name='dbacthdr_RCCASHbalance']").prop( "disabled", true );
		$("input[name='dbacthdr_RCFinalbalance']").prop( "disabled", true );
	}

	function getCashBal(){
		var pay=parseFloat(numeral().unformat($(tabform+" input[name='dbacthdr_amount']").val()));
		var out=parseFloat(numeral().unformat($(tabform+" input[name='dbacthdr_outamount']").val()));
		var RCCASHbalance=(pay-out>0) ? pay-out : 0;

		$(tabform+" input[name='dbacthdr_RCCASHbalance']").val(RCCASHbalance);
		mycurrency.formatOn();
	}

	function getOutBal(iscash,bc){
		var pay=parseFloat(numeral().unformat($(tabform+" input[name='dbacthdr_amount']").val()));
		var out=parseFloat(numeral().unformat($(tabform+" input[name='dbacthdr_outamount']").val()));
		var RCFinalbalance = 0;
		if(iscash){
			RCFinalbalance =(out-pay>0) ? out-pay : 0;
		}else{
			RCFinalbalance = out-pay;
		}

		if(bc==null)bc=0;
		$(tabform+" input[name='dbacthdr_RCFinalbalance']").val(parseFloat(RCFinalbalance)-parseFloat(bc));
		mycurrency.formatOn();
	}

	function showingForCash(pay,os,cashbal,finalbal,tabform){//amount,outamount,RCCASHbalance,RCFinalbalance
		var pay = parseFloat(pay);
		var os = parseFloat(os);
		var cashbal = parseFloat(cashbal);
		var finalbal = parseFloat(finalbal);

		if(cashbal>0 && finalbal==0){
			pay = os + cashbal;
			$(tabform+' #dbacthdr_amount').val(pay);
		}else if(finalbal>0){
			os = pay + finalbal;
			$(tabform+' #dbacthdr_outamount').val(os);
		}else if(finalbal<0){
			pay = os - finalbal;
			$(tabform+' #dbacthdr_amount').val(pay);
		}
		mycurrency.formatOn();
	}

	////////////////////////////////////transaction minimum date///////////////////////

	var actdateObj = new setactdate(["input[name='dbacthdr_entrydate']"]);
	actdateObj.getdata().set();
	
	////////////////////////////end transaction minimum date////////////////////////////////
	
	////////////////////////////////////////////////////ordialog////////////////////////////////////////
	var dialog_payercode = new ordialog(
		'payercode','debtor.debtormast','#dbacthdr_payercode',errorField,
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
				let data=selrowData('#'+dialog_payercode.gridname);
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
			title:"Select Payer code",
			open: function(){
				dialog_payercode.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_payercode.urlParam.filterVal=['ACTIVE', 'session.compcode']
			},
			close: function(){
				let data=selrowData('#'+dialog_payercode.gridname);
				get_debtorcode_outamount(data.debtorcode);
				$('#dbacthdr_remark').focus();
			}
			},'urlParam','radio','tab'
		);
	dialog_payercode.makedialog(true);

	///////////////////////////////////////////trantype//////////////////////
	var urlParam_sys={
		action:'get_table_default',
		url: 'util/get_table_default',
		field:'',
		table_name:'sysdb.sysparam',
		table_id:'trantype',
		filterCol:['source','trantype','compcode'],
		filterVal:['PB','RF','session.compcode']
	}

	$("#sysparam").jqGrid({
		datatype: "local",
			colModel: [
			{label: 'source', name: 'source', width: 60, hidden:true},
			{label: 'Trantype', name: 'trantype', width: 60, hidden:true},
			{label: 'Description', name: 'description', width: 150 },
			{label: 'hdrtype', name: 'hdrtype', width: 150, hidden:true},
			{label: 'updpayername', name: 'updpayername', width: 150, hidden:true},
			{label: 'depccode', name: 'depccode', width: 150, hidden:true},
			{label: 'depglacc', name: 'depglacc', width: 150, hidden:true},
			{label: 'updepisode', name: 'updepisode', width: 150, hidden:true},
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		width: 300,
		height: 150,
		rowNum: 30,
		gridComplete: function(rowid){
			$("#sysparam").setSelection($("#sysparam").getDataIDs()[0]);
		},
		onSelectRow:function(rowid, selected){
			if(rowid != null) {
				rowData = $('#sysparam').jqGrid ('getRowData', rowid);
				$('#dbacthdr_trantype').val(rowData['trantype']);
				saveParam.sysparam.trantype=rowData['trantype'];
				$('#dbacthdr_PymtDescription').val(rowData['description']);
				if($("input:radio[name='optradio'][value='deposit']").is(':checked')){
					$("input:hidden[name='dbacthdr_hdrtype']").val(rowData['hdrtype']);
					$("input:hidden[name='updepisode']").val(rowData['updepisode']);
					$("input:hidden[name='updpayername']").val(rowData['updpayername']);
					$("#formdata input[name='dbacthdr_crcostcode']").val(rowData['depccode']);
					$("#formdata input[name='dbacthdr_cracc']").val(rowData['depglacc']);
					if(oper!='view'){
						dialog_mrn.on();
						// dialog_episode.handler(errorField);
					}
					if(rowData['updpayername'] == 1){
						$('#dbacthdr_payername').prop('readonly',false);
					}else{
						$('#dbacthdr_payername').prop('readonly',true);
					}
				}else{
					$('#dbacthdr_payername').prop('readonly',true);
					$("input:hidden[name='dbacthdr_hdrtype']").val('RC');
					$("input:hidden[name='updpayername'],input:hidden[name='updepisode']").val('');
					dialog_mrn.off();
				}
			}
		},
		beforeSelectRow: function(rowid, e) {
			if(oper=='view'){
				//$('#'+$("#sysparam").jqGrid ('getGridParam', 'selrow')).focus();
				return false;
			}
		}
	});

	addParamField('#sysparam',true,urlParam_sys,['hdrtype','updpayername','depccode','depglacc','updepisode']);
	/////////////////////////////////////////End Transaction typr////////////////////////////

	/////////////  PAYMODE ////////////////
		///////////////////////////////////////////Bank Paytype/////////////////////////////////
		var urlParam2={
			action:'get_table_default',
			url: 'util/get_table_default',
			field:'',
			table_name:'debtor.paymode',
			table_id:'paymode',
			filterCol:['source','paytype','compcode'],
			filterVal:['AR','BANK','session.compcode'],
		}
		$("#g_paymodebank").jqGrid({
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
					rowData = $('#g_paymodebank').jqGrid ('getRowData', rowid);
					$("#f_tab-debit .form-group input[name='dbacthdr_paymode']").val(rowData['paymode']);
					$("#formdata input[name='dbacthdr_drcostcode']").val(rowData['ccode']);
					$("#formdata input[name='dbacthdr_dracc']").val(rowData['glaccno']);
				}
			},
			beforeSelectRow: function(rowid, e) {
				if(oper=='view'){
					$('#'+$("#g_paymodebank").jqGrid ('getGridParam', 'selrow')).focus();
					return false;
				}
			}
		});
	
		$("#g_paymodebank").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
		addParamField('#g_paymodebank',false,urlParam2);
		////////////////////////////////////////////End Bank Paytype//////////////////////////////////////
	
		///////////////////////////////////////////Card paytype//////////////////////////////////////////////
		var urlParam3={
			action:'get_table_default',
			url: 'util/get_table_default',
			field:'',
			table_name:'debtor.paymode',
			table_id:'paymode',
			filterCol:['source','paytype','compcode'],
			filterVal:['AR','CARD','session.compcode'],
		}
		$("#g_paymodecard").jqGrid({
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
					rowData = $('#g_paymodecard').jqGrid ('getRowData', rowid);
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
	
					$("#formdata input[name='dbacthdr_drcostcode']").val(rowData['ccode']);
					$("#formdata input[name='dbacthdr_dracc']").val(rowData['glaccno']);
				}
			},
			beforeSelectRow: function(rowid, e) {
				if(oper=='view'){
					$('#'+$("#g_paymodecard").jqGrid ('getGridParam', 'selrow')).focus();
					return false;
				}
			}
		});
	
		$("#g_paymodecard").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
		addParamField('#g_paymodecard',false,urlParam3);
		///////////////////////////////////end card////////////////////////////////////////////
	
		/////////////////////////validation//////////////////////////
		$.validate({
			decimalSeparator : ',',
			language : {
				requiredFields: ''
			},
		});
		
		var errorField=[];
		conf = {
			onValidate : function($form) {
				if(errorField.length>0){
					return {
						element : $(errorField[0]),
						message : ' '
					}
				}
			},
		};
	
		var fdl = new faster_detail_load();
		//////////////////////////////////////////////////////////////	
	///////////// END PAYMODE ////////////////

	////////////////////////////////////start dialog//////////////////////////////////////

	function saveFormdata_refund(grid,dialog,form,oper,saveParam,urlParam,obj,callback,uppercase=true){

		var formname = $("a[aria-expanded='true']").attr('form')

		var paymentform =  $( formname ).serializeArray();

		$('.ui-dialog-buttonset button[role=button]').prop('disabled',true);
		saveParam.oper=oper;

		let serializedForm = trimmall(form,uppercase);
		$.post( saveParam.url+'?'+$.param(saveParam), serializedForm+'&'+$.param(paymentform) , function( data ) {
			
		}).fail(function(data) {
			errorText(dialog.substr(1),data.responseText);
			$('.ui-dialog-buttonset button[role=button]').prop('disabled',false);
		}).success(function(data){
			if(grid!=null){
				refreshGrid(grid,urlParam,oper);
				$('.ui-dialog-buttonset button[role=button]').prop('disabled',false);
				$(dialog).dialog('close');
				if (callback !== undefined) {
					callback();
				}
			}
		});
	}

	var butt1=[{
		text: "Save",click: function() {
			mycurrency.formatOff();
			mycurrency.check0value(errorField);
			if( $('#formdata').isValid({requiredFields: ''}, conf, true) && $(tabform).isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata_refund("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
			}else{
				mycurrency.formatOn();
			}
		}
	},{
		text: "Cancel",click: function() {
			$(this).dialog('close');
		}
	}];

	var butt2=[{
		text: "Close",click: function() {
			$(this).dialog('close');
		}
	}];

	$("input[name=dbacthdr_entrydate]").keydown(false);
	
	////////////////////////////////////start dialog////////////////////////////////////
	var oper = 'add';

	$("#dialogForm")
	  .dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {			
			////// Popup login //////
			// var bootboxHtml = $('#LoginDiv').html().replace('LoginForm', 'LoginBootboxForm');

			// bootbox.confirm(bootboxHtml, function(result) {
			// 	console.log($('#ex1', '.LoginBootboxForm').val());
			// 	console.log($('#till_tillcode','#description','#till_dept','#tillstatus','#defopenamt', '.LoginBootboxForm').val()); 

			// });
			////// End Popup login //////

			parent_close_disabled(true);

			$('.nav-tabs a').on('shown.bs.tab', function(e){
				tabform=$(this).attr('form');
				rdonly(tabform);
				handleAmount();
				$('#dbacthdr_paytype').val(tabform);
				switch(tabform) {
					case state = '#f_tab-cash':
						getcr('CASH');
						break;
					case state = '#f_tab-card':
						refreshGrid("#g_paymodecard",urlParam3);
						break;
					case state = '#f_tab-cheque':
						getcr('cheque');
						break;
					case state = '#f_tab-debit':
						refreshGrid("#g_paymodebank",urlParam2);
						break;
				}
				$("#g_paymodecard").jqGrid ('setGridWidth', $("#g_paymodecard_c")[0].clientWidth);
				$("#g_paymodebank").jqGrid ('setGridWidth', $("#g_paymodebank_c")[0].clientWidth);

			});
			$("#sysparam").jqGrid ('setGridWidth', Math.floor($("#sysparam_c")[0].offsetWidth));
			$("#g_paymodecard").jqGrid ('setGridWidth', $("#g_paymodecard_c")[0].clientWidth);
			$("#g_paymodebank").jqGrid ('setGridWidth', $("#g_paymodebank_c")[0].clientWidth);
			switch(oper) {
				case state = 'add':
					mycurrency.formatOnBlur();
					$('#dbacthdr_paytype').val(tabform);
					$( this ).dialog( "option", "title", "Add" );
					enableForm('#formdata');
					enableForm('.tab-content');
					rdonly('#formdata');
					rdonly(tabform);
					break;
				case state = 'edit':
					$( this ).dialog( "option", "title", "Edit" );
					enableForm('#formdata');
					frozeOnEdit("#dialogForm");
					rdonly('#formdata');
					break;
				case state = 'view':
					mycurrency.formatOn();
					$( this ).dialog( "option", "title", "View" );
					disableForm('#formdata');
					disableForm(selrowData('#jqGrid').dbacthdr_paytype);
					$(this).dialog("option", "buttons",butt2);

					switch(selrowData('#jqGrid').dbacthdr_paytype) {
						case state = '#f_tab-card':
							refreshGrid("#g_paymodecard",urlParam3);
							break;
						case state = '#f_tab-debit':
							refreshGrid("#g_paymodebank",urlParam2);
							break;
					}
				
					break;
			}
			if(oper!='view'){
				dialog_payercode.on();
				dialog_logindeptcode.on();
				// dialog_logintillcode.on();
			}
			if(oper!='add'){
				dialog_logindeptcode.check(errorField);
				// dialog_logintillcode.check(errorField);
				dialog_payercode.check(errorField);
				showingForCash(selrowData("#jqGrid").dbacthdr_amount,selrowData("#jqGrid").dbacthdr_outamount,selrowData("#jqGrid").dbacthdr_RCCASHbalance,selrowData("#jqGrid").dbacthdr_RCFinalbalance,selrowData("#jqGrid").dbacthdr_paytype);
			}
		},
		close: function( event, ui ) {
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			emptyFormdata(errorField, "#f_tab-cash");
			emptyFormdata(errorField, "#f_tab-card");
			emptyFormdata(errorField, "#f_tab-cheque");
			emptyFormdata(errorField, "#f_tab-debit");
			$('.alert').detach();
			dialog_logindeptcode.off();
			// dialog_logintillcode.off();
			$("#formdata a").off();
			$("#refresh_jqGrid").click();
			if(oper=='view'){
				$(this).dialog("option", "buttons",butt1);
			}
		},
		buttons :butt1,
	  });
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'maintable',
		url: './receipt/table',
		field:'',
		// fixPost:'true',
		// table_name:['debtor.dbacthdr','hisdb.pat_mast'],
		// table_id:'dbacthdr_idno',
		// join_type:['LEFT JOIN'],
		// join_onCol:['dbacthdr.mrn'],
		// join_onVal:['pat_mast.mrn'],
		// filterCol:['dbacthdr.trantype',''],
		// filterVal:['RC']
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	var saveParam={	
		action:'refund_save',
		url: 'refund/form',
		oper:'add',
		field:'',
		table_name:'debtor.dbacthdr',
		table_id:'auditno',
		fixPost:true,
		skipduplicate: true,
		returnVal:true,
		sysparam:{source:'PB',trantype:'RF',useOn:'auditno'}  /////PB, RF, pValue +1
	};

	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{label: 'Audit No', name: 'dbacthdr_auditno', width: 30 },
			{label: 'lineno_', name: 'dbacthdr_lineno_', width: 30, hidden: true},
			{label: 'source', name: 'dbacthdr_source', hidden: true, checked:true},
			{label: 'Trantype', name: 'dbacthdr_trantype', width: 45, formatter: showdetail, unformat:un_showdetail},
			{label: 'Type', name: 'dbacthdr_PymtDescription', classes: 'wrap', width: 50, hidden: true},
			{label: 'MRN', name: 'dbacthdr_mrn',align:'right', width: 30}, //tunjuk
			{label: 'Epis', name: 'dbacthdr_episno',align:'right', width: 30}, //tunjuk
			{label: 'billdebtor', name: 'dbacthdr_billdebtor', hidden: true},
			{label: 'conversion', name: 'dbacthdr_conversion', hidden: true},
			{label: 'hdrtype', name: 'dbacthdr_hdrtype', hidden: true},
			{label: 'currency', name: 'dbacthdr_currency', hidden: true},
			{label: 'tillcode', name: 'dbacthdr_tillcode', hidden: true},
			{label: 'tillno', name: 'dbacthdr_tillno', hidden: true},
			{label: 'debtortype', name: 'dbacthdr_debtortype', hidden: true},
			{label: 'Date', name: 'dbacthdr_adddate',width: 50, formatter: dateFormatter, unformat: dateUNFormatter, hidden: true},
			{label: 'Receipt No.', name: 'dbacthdr_recptno', classes: 'wrap',width: 60, hidden: true},
			{label: 'entrydate', name: 'dbacthdr_entrydate', hidden: true},
			{label: 'entrydate', name: 'dbacthdr_entrytime', hidden: true},
			{label: 'entrydate', name: 'dbacthdr_entryuser', hidden: true},
			{label: 'Payer', name: 'dbacthdr_payercode', width: 150, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail},
			{label: 'Payer Name', name: 'dbacthdr_payername', width: 150, classes: 'wrap text-uppercase', canSearch:true, hidden: true},
			{label: 'Patient Name', name: 'name', width: 150, classes: 'wrap', hidden: true},
			{label: 'remark', name: 'dbacthdr_remark', hidden: true},
			{label: 'authno', name: 'dbacthdr_authno', hidden: true},
			{label: 'epistype', name: 'dbacthdr_epistype', hidden: true},
			{label: 'cbflag', name: 'dbacthdr_cbflag', hidden: true},
			{label: 'reference', name: 'dbacthdr_reference', hidden: true},
			{label: 'Payment Mode', name: 'dbacthdr_paymode',width: 70, hidden: true}, //tunjuk
			{label: 'Amount', name: 'dbacthdr_amount', width: 60,align:'right',formatter:'currency',formatoptions:{prefix: ""} }, //tunjuk
			{label: 'O/S Amount', name: 'dbacthdr_outamount', width: 60,align:'right',formatter:'currency',formatoptions:{prefix: ""} }, //tunjuk
			{label: 'bankchg', name: 'dbacthdr_bankcharges', hidden: true},
			{label: 'expdate', name: 'dbacthdr_expdate', hidden: true},
			{label: 'rate', name: 'dbacthdr_rate', hidden: true},
			{label: 'units', name: 'dbacthdr_unit', hidden: true},
			{label: 'invno', name: 'dbacthdr_invno', hidden: true},
			{label: 'paytype', name: 'dbacthdr_paytype', hidden: true},
			{label: 'RCcashbalance', name: 'dbacthdr_RCCASHbalance', hidden: true},
			{label: 'RCFinalbalance', name: 'dbacthdr_RCFinalbalance', hidden: true},
			{label: 'RCOSbalance', name: 'dbacthdr_RCOSbalance', width: 70},
			{label: 'Status', name: 'dbacthdr_recstatus',width: 50}, //tunjuk
			{label: 'idno', name: 'dbacthdr_idno', hidden: true},
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'dbacthdr_idno',
		sortorder:'desc',
		width: 900,
		height: 300,
		rowNum: 30,
		pager: "#jqGridPager",
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='View Selected Row']").click();
		},
		onSelectRow: function(rowid){
		},
		gridComplete: function(){
			fdl.set_array().reset();
			if(oper == 'add'){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
		},
		loadComplete:function(data){
			calc_jq_height_onchange("jqGrid");
		}	
	});

	///// jqGrid pager check balik RC RD RF etcccc
	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-info-sign",
		title:"View Selected Row",  
		onClickButton: function(){
			if(selrowData('#jqGrid').dbacthdr_trantype == 'RD'){
				$( "input:radio[name='optradio'][value='deposit']" ).prop( "checked", true );
				$( "input:radio[name='optradio'][value='deposit']" ).change();
				delay(function(){
					$("#sysparam").jqGrid('setSelection', selrowData('#jqGrid').dbacthdr_hdrtype, true);
					$("#g_paymodebank").jqGrid('setSelection', selrowData('#jqGrid').dbacthdr_paymode, true);
					$("#g_paymodecard").jqGrid('setSelection', selrowData('#jqGrid').dbacthdr_paymode, true);
					$("#g_forex").jqGrid('setSelection', selrowData('#jqGrid').dbacthdr_paymode, true);
				}, 500 );
			}else{
				$( "input:radio[name='optradio'][value='receipt']" ).prop( "checked", true );
				$( "input:radio[name='optradio'][value='receipt']" ).change();
				delay(function(){
					$("#sysparam").jqGrid('setSelection', 'RC');
					$("#g_paymodebank").jqGrid('setSelection', selrowData('#jqGrid').dbacthdr_paymode, true);
					$("#g_paymodecard").jqGrid('setSelection', selrowData('#jqGrid').dbacthdr_paymode, true);
					$("#g_forex").jqGrid('setSelection', selrowData('#jqGrid').dbacthdr_paymode, true);
				}, 500 );
			}
			oper='view';
			$('#dbacthdr_recptno').show();
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			var selform=selrowData('#jqGrid').dbacthdr_paytype;
			$("#dialogForm").dialog( "open" );
			if(selform!=''){
				$(".nav-tabs a[form='"+selform.toLowerCase()+"']").tab('show');
				// disabledPill();
				populateFormdata("#jqGrid","",selform.toLowerCase(),selRowId,'view');
			}else{
				$(".nav-tabs a[form='#f_tab-cash']").tab('show');
			}
			populateFormdata("#jqGrid","","#formdata",selRowId,'view');
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-plus", 
		title:"Add New Row", 
		onClickButton: function(){
			oper='add';
			$('#dbacthdr_recptno').hide();
			$( "input:radio[name='optradio'][value='receipt']" ).prop( "checked", true );
			$( "input:radio[name='optradio'][value='receipt']" ).change();
			// $("#formdata input[name='dbacthdr_tillcode']").val(def_tillcode);	
			// $("#formdata input[name='dbacthdr_tillno']").val(def_tillno);
			$(".nav-tabs a[form='#f_tab-cash']").tab('show');
			enabledPill();
			$( "#dialogForm" ).dialog( "open" );
		},
	});
	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');
	searchClick2('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['patmast_name','dbacthdr_idno','dbacthdr_amount']);

	/////////get debtor outamount function /////////////////
	function get_debtorcode_outamount(payercode){
		var param={
			url: './receipt/table',
			action:'get_debtorcode_outamount',
			payercode:payercode
		}

		$.get( param.url+"?"+$.param(param), function( data ) {
			
		},'json').done(function(data) {
			if(data.result == 'true'){
				$('input[name="dbacthdr_outamount"]').val(data.outamount);
			}else{
				// alert('Payer doesnt have outstanding amount');
			}
		});
	}

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field,table, case_;
		switch(options.colModel.name){
			case 'dbacthdr_debtorcode':field=['debtorcode','name'];table="debtor.debtormast";case_='dbacthdr_debtorcode';break;
			case 'dbacthdr_payercode':field=['debtorcode','name'];table="debtor.debtormast";case_='dbacthdr_payercode';break;
			case 'dbacthdr_trantype':field=['trantype','description'];table="sysdb.sysparam";case_='dbacthdr_trantype';break;		
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
		fdl.get_array('receipt',options,param,case_,cellvalue);
		
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

	////////////////////////////populate data for dropdown search By////////////////////////////
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

	$('#Status').on('change', searchChange);

	function searchChange(){
		var arrtemp = [$('#Status option:selected').val()];
		var filter = arrtemp.reduce(function(a,b,c){
			if(b=='All'){
				return a;
			}else{
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		},{fct:['ap.recstatus'],fv:[],fc:[]});

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		refreshGrid('#jqGrid',urlParam);
	}

	var payer_search = new ordialog(
		'payer_search', 'debtor.debtormast', '#payer_search', 'errorField',
		{
			colModel: [
				{ label: 'Debtor Code', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow: function () {
				let data = selrowData('#' + payer_search.gridname).debtorcode;

				if($('#Scol').val() == 'db_debtorcode'){
					urlParam.searchCol=["db.debtorcode"];
					urlParam.searchVal=[data];
				}else if($('#Scol').val() == 'db_payercode'){
					urlParam.searchCol=["db.payercode"];
					urlParam.searchVal=[data];
				}
				refreshGrid('#jqGrid', urlParam);
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					// $('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title: "Select Payer",
			open: function () {
				payer_search.urlParam.filterCol = ['recstatus'];
				payer_search.urlParam.filterVal = ['ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	payer_search.makedialog(true);
	$('#payer_search').on('keyup',ifnullsearch);

	function ifnullsearch(){
		if($('#payer_search').val() == ''){
			urlParam.searchCol=[];
			urlParam.searchVal=[];
			$('#jqGrid').data('inputfocus','payer_search');
			refreshGrid('#jqGrid', urlParam);
		}
	}
});

function calc_jq_height_onchange(jqgrid){
	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
	if(scrollHeight<80){
		scrollHeight = 80;
	}else if(scrollHeight>300){
		scrollHeight = 300;
	}
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight);
}	
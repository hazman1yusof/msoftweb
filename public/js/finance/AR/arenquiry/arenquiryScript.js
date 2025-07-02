$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow = 0;
var errorField = [];
var fdl = new faster_detail_load();

$(document).ready(function (){
	$("body").show();
	
	var tabform = "#f_tab-cash";
	
	/////////////////////////////////////validation/////////////////////////////////////
	$.validate({
		modules: 'sanitize',
		language: {
			requiredFields: 'Please Enter Value'
		},
	});
	
	conf = {
		onValidate: function ($form){
			if(errorField.length > 0){
				show_errors(errorField,'#formdata');
				return [{
					element: $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
					message: ''
				}];
			}
		},
	};
	
	//////////////////////////////////////currency//////////////////////////////////////
	var mycurrency = new currencymode(['#db_outamount', '#db_amount', "#formdata_DN input[name='db_amount']", "#formdata_CN input[name='db_amount']", "#formdata_CN input[name='tot_alloc']"]);
	var mycurrency2 = new currencymode(['#db_outamount', '#db_amount']);
	
	////////////////////////for handling amount based on trantype////////////////////////
	//////////////////////////////////////RC STARTS//////////////////////////////////////
	function handleAmount(){
		if($("input:radio[name='optradio'][value='receipt']").is(':checked')){
			amountchgOn(true);
		}else if($("input:radio[name='optradio'][value='deposit']").is(':checked')){
			amountchgOff(true);
		}
	}
	
	function amountFunction(){
		if(tabform == '#f_tab-cash'){
			getCashBal();
			getOutBal(true);
		}else if(tabform == '#f_tab-card'||tabform == '#f_tab-cheque'||tabform == '#f_tab-forex'){
			getOutBal(false);
		}else if(tabform == '#f_tab-debit'){
			getOutBal(false,$(tabform+" input[name='dbacthdr_bankcharges']").val());
		}
	}
	
	function amountchgOn(fromtab){
		$("input[name='dbacthdr_outamount']").prop( "disabled", false );
		$("input[name='dbacthdr_RCCASHbalance']").prop( "disabled", false );
		$("input[name='dbacthdr_RCFinalbalance']").prop( "disabled", false );
		$("input[name='dbacthdr_amount']").off('blur',amountFunction);
		$("input[name='dbacthdr_outamount']").off('blur',amountFunction);
		$(tabform+"input[name='dbacthdr_amount']").on('blur',amountFunction);
		$(tabform+"input[name='dbacthdr_outamount']").on('blur',amountFunction);
	}
	
	function amountchgOff(fromtab){
		$("input[name='dbacthdr_amount']").off('blur',amountFunction);
		$("input[name='dbacthdr_outamount']").off('blur',amountFunction);
		$("input[name='dbacthdr_outamount']").prop( "disabled", true );
		$("input[name='dbacthdr_RCCASHbalance']").prop( "disabled", true );
		$("input[name='dbacthdr_RCFinalbalance']").prop( "disabled", true );
	}
	
	function getCashBal(){
		var pay = parseFloat(numeral().unformat($(tabform+" input[name='dbacthdr_amount']").val()));
		var out = parseFloat(numeral().unformat($(tabform+" input[name='dbacthdr_outamount']").val()));
		var RCCASHbalance = (pay-out>0) ? pay-out : 0;
		
		$(tabform+" input[name='dbacthdr_RCCASHbalance']").val(RCCASHbalance);
		mycurrency.formatOn();
	}
	
	function getOutBal(iscash,bc){
		var pay = parseFloat(numeral().unformat($(tabform+" input[name='dbacthdr_amount']").val()));
		var out = parseFloat(numeral().unformat($(tabform+" input[name='dbacthdr_outamount']").val()));
		var RCFinalbalance = 0;
		if(iscash){
			RCFinalbalance = (out-pay>0) ? out-pay : 0;
		}else{
			RCFinalbalance = out-pay;
		}
		
		if(bc == null)bc = 0;
		$(tabform+" input[name='dbacthdr_RCFinalbalance']").val(parseFloat(RCFinalbalance)-parseFloat(bc));
		mycurrency.formatOn();
	}
	
	function showingForCash(pay,os,cashbal,finalbal,tabform){ // amount,outamount,RCCASHbalance,RCFinalbalance
		var pay = parseFloat(pay);
		var os = parseFloat(os);
		var cashbal = parseFloat(cashbal);
		var finalbal = parseFloat(finalbal);
		
		if(cashbal > 0 && finalbal == 0){
			pay = os + cashbal;
			$(tabform+' #dbacthdr_amount').val(pay);
		}else if(finalbal > 0){
			os = pay + finalbal;
			$(tabform+' #dbacthdr_outamount').val(os);
		}else if(finalbal < 0){
			pay = os - finalbal;
			$(tabform+' #dbacthdr_amount').val(pay);
		}
		mycurrency.formatOn();
	}
	///////////////////////////////////////RC ENDS///////////////////////////////////////
	
	//////////////////////////////////////RF STARTS//////////////////////////////////////
	function amountchgOnRF(){
		$("input[name='dbacthdr_amount']").on('blur',amountFunctionRF);
	}
	
	function amountchgOffRF(){
		$("input[name='dbacthdr_amount']").off('blur',amountFunctionRF);
	}
	
	function amountFunctionRF(event){
		let outamount = $(event.currentTarget).val();
		myallocation.outamt = outamount;
	}
	///////////////////////////////////////RF ENDS///////////////////////////////////////
	////////////////////////end handling amount based on trantype////////////////////////
	
	/////////////////////////////////////saveFormdata/////////////////////////////////////
	//////////////////////////////////////RC STARTS//////////////////////////////////////
	function saveFormdata_receipt(grid,dialog,form,oper,saveParam,urlParam,obj,callback,uppercase=true){
		var formname = $("a[aria-expanded='true']").attr('form')
		
		var paymentform = $( formname ).serializeArray();
		
		$('.ui-dialog-buttonset button[role=button]').prop('disabled',true);
		saveParam.oper = oper;
		
		let serializedForm = trimmall(form,uppercase);
		$.post(saveParam.url+'?'+$.param(saveParam), serializedForm+'&'+$.param(paymentform), function (data){
			
		}).fail(function (data){
			errorText(dialog.substr(1),data.responseText);
			$('.ui-dialog-buttonset button[role=button]').prop('disabled',false);
		}).success(function (data){
			if(grid != null){
				refreshGrid(grid,urlParam,oper);
				$('.ui-dialog-buttonset button[role=button]').prop('disabled',false);
				$(dialog).dialog('close');
				if(callback !== undefined){
					callback();
				}
			}
		});
	}
	
	var butt1 = [{
		text: "Save", click: function (){
			mycurrency.formatOff();
			mycurrency.check0value(errorField);
			if( $('#formdata_RC').isValid({requiredFields: ''}, conf, true) && $(tabform).isValid({requiredFields: ''}, conf, true) ){
				saveFormdata_receipt("#jqGrid2_RC","#dialogForm_RC","#formdata_RC",oper,saveParam2_RC,urlParam2_RC);
			}else{
				mycurrency.formatOn();
			}
		}
	},{
		text: "Cancel", click: function (){
			$(this).dialog('close');
		}
	}];
	
	var butt2 = [{
		text: "Close", click: function (){
			$(this).dialog('close');
		}
	}];
	///////////////////////////////////////RC ENDS///////////////////////////////////////
	
	//////////////////////////////////////RF STARTS//////////////////////////////////////
	function saveFormdata_refund(grid,dialog,form,oper,saveParam,urlParam,obj,callback,uppercase=true){
		var myallocation_obj = {
			allo: myallocation.arrayAllo
		}
		
		var formname = $("a[aria-expanded='true']").attr('form')
		
		var paymentform = $( formname ).serializeArray();
		
		$('.ui-dialog-buttonset button[role=button]').prop('disabled',true);
		saveParam.oper = oper;
		
		let serializedForm = trimmall(form,uppercase);
		$.post(saveParam.url+'?'+$.param(saveParam), serializedForm+'&'+$.param(paymentform)+'&'+$.param(myallocation_obj), function (data){
			
		}).fail(function (data){
			errorText(dialog.substr(1),data.responseText);
			$('.ui-dialog-buttonset button[role=button]').prop('disabled',false);
		}).success(function (data){
			if(grid != null){
				refreshGrid(grid,urlParam,oper);
				$('.ui-dialog-buttonset button[role=button]').prop('disabled',false);
				$(dialog).dialog('close');
				if(callback !== undefined){
					callback();
				}
			}
		});
	}
	
	var butt1 = [{
		text: "Save", click: function (){
			mycurrency.formatOff();
			mycurrency.check0value(errorField);
			if( $('#formdata_RF').isValid({requiredFields: ''}, conf, true) && $(tabform).isValid({requiredFields: ''}, conf, true) ){
				saveFormdata_refund("#jqGrid_RF","#dialogForm_RF","#formdata_RF",oper,saveParam_rf,urlParam_rf);
			}else{
				mycurrency.formatOn();
			}
		}
	},{
		text: "Cancel", click: function (){
			$(this).dialog('close');
		}
	}];
	
	var butt2 = [{
		text: "Close", click: function (){
			$(this).dialog('close');
		}
	}];
	///////////////////////////////////////RF ENDS///////////////////////////////////////
	
	function getcr(paytype){
		var param = {
			action: 'get_value_default',
			field: ['glaccno','ccode'],
			url: 'util/get_value_default',
			table_name: 'debtor.paymode',
			table_id: 'paymode',
			filterCol: ['paytype','source','compcode'],
			filterVal: [paytype,'AR','session.compcode'],
		}
		
		$.get(param.url+"?"+$.param(param), function (data){
			
		},'json').done(function (data){
			$("#formdata_RC input[name='dbacthdr_drcostcode']").val(data.rows[0].ccode);
			$("#formdata_RC input[name='dbacthdr_dracc']").val(data.rows[0].glaccno);
			
			$("#formdata_RF input[name='dbacthdr_drcostcode']").val(data.rows[0].ccode);
			$("#formdata_RF input[name='dbacthdr_dracc']").val(data.rows[0].glaccno);
		});
	}
	
	function setDateToNow(){
		$('input[name=dbacthdr_entrydate]').val(moment().format('YYYY-MM-DD'));
	}
	
	function get_debtorcode_outamount(payercode){
		var param = {
			url: './receipt/table',
			action: 'get_debtorcode_outamount',
			payercode: payercode
		}
		
		$.get(param.url+"?"+$.param(param), function (data){
			
		},'json').done(function (data){
			if(data.result == 'true'){
				$('input[name="dbacthdr_outamount"]').val(data.outamount);
			}else{
				// alert('Payer doesnt have outstanding amount');
			}
		});
	}
	
	///////////////////////////////////start dialogForm///////////////////////////////////
	$("#dialogForm_CN")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui){
				errorField.length = 0;
				$("#jqGrid2_CN").jqGrid('setGridWidth', Math.floor($("#jqGrid2_CN_c")[0].offsetWidth - $("#jqGrid2_CN_c")[0].offsetLeft));
				$("#jqGrid2_Alloc").jqGrid('setGridWidth', Math.floor($("#jqGrid2_Alloc_c")[0].offsetWidth - $("#jqGrid2_Alloc_c")[0].offsetLeft));
				refreshGrid("#jqGrid2_CN",urlParam2_CN);
				mycurrency.formatOn();
				mycurrency.formatOnBlur();
				disableForm('#formdata_CN');
				$("#pg_jqGridPager2 table").hide();
				$("#pg_jqGridPager2_Alloc table").hide();
				dialog_CustomerCN.check(errorField);
				dialog_paymodeCN.check(errorField);
				init_jq2('view');
			},
			close: function (event, ui){
			}
		});
	
	$("#dialogForm_DN")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui){
				parent_close_disabled(true);
				$("#jqGrid2_DN").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_DN_c")[0].offsetWidth-$("#jqGrid2_DN_c")[0].offsetLeft));
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				disableForm('#formdata_DN');
				refreshGrid("#jqGrid2_DN",urlParam2_DN);
				$("#pg_jqGridPager2_DN table").hide();
				dialog_CustomerDN.check(errorField);
				dialog_paymodeDN.check(errorField);
			},
			close: function (event, ui){
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_DN');
				$('.my-alert').detach();
				$("#formdata_DN a").off();
				$(".noti, .noti2 ol").empty();
				refreshGrid("#jqGrid2_DN",null,"kosongkan");
				errorField.length = 0;
			},
		});
	
	$("#dialogForm_IN")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui){
				parent_close_disabled(true);
				$("#jqGrid2_IN").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_IN_c")[0].offsetWidth-$("#jqGrid2_IN_c")[0].offsetLeft));
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				disableForm('#formdata_IN');
				refreshGrid("#jqGrid2_IN",urlParam2_IN);
				$("#pg_jqGridPager2_IN table").hide();
				dialog_CustomerSO.check(errorField);
				dialog_deptcode.check(errorField);
				dialog_billtypeSO.check(errorField);
				dialog_mrn.check(errorField);
			},
			close: function (event, ui){
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_IN');
				$('.my-alert').detach();
				$("#formdata_IN a").off();
				$(".noti, .noti2 ol").empty();
				refreshGrid("#jqGrid2_IN",null,"kosongkan");
				errorField.length = 0;
			},
		});
	
	var butt1_rem = [{
		text: "Save", click: function (){
			let newval = $("#comment_2").val();
			let rowid = $('#comment_2').data('rowid');
			$("#jqGrid_Tracking").jqGrid('setRowData', rowid, {comment_:newval});
			// $("#jqGrid_Tracking").jqGrid('setRowData', rowid, {comment_show:newval});
			// if($("#jqGridPagerTracking_SaveAll").css('display') == 'none'){
			// 	$("#jqGrid_Tracking_ilsave").click();
			// }
			$(this).dialog('close');
		}
	},{
		text: "Cancel", click: function (){
			$(this).dialog('close');
		}
	}];
	
	var butt2_rem = [{
		text: "Close", click: function (){
			$(this).dialog('close');
		}
	}];
	
	$("#dialog_comment").dialog({
		autoOpen: false,
		width: 4/10 * $(window).width(),
		modal: true,
		open: function (event, ui){
			let rowid = $('#comment_2').data('rowid');
			let grid = $('#comment_2').data('grid');
			$('#comment_2').val($(grid).jqGrid('getRowData', rowid).comment_);
			let exist = $("#jqGrid_Tracking #"+rowid+"_trxdate").length;
			if(exist == 0){ // lepas ni letak or not edit mode
				$("#comment_2").prop('disabled',true);
				$( "#dialog_comment" ).dialog("option", "buttons", butt2_rem);
			}else{
				$("#comment_2").prop('disabled',false);
				$( "#dialog_comment" ).dialog("option", "buttons", butt1_rem);
			}
		},
		close: function (){
			// fixPositionsOfFrozenDivs.call($('#jqGrid_Tracking')[0]);
		},
		buttons: butt2_rem
	});
	
	$('.nav-tabs a').on('shown.bs.tab', function (e){
		tabform = $(this).attr('form');
		rdonly(tabform);
		handleAmount();
		$('#dbacthdr_paytype').val(tabform);
		switch(tabform) {
			case '#f_tab-cash':
				getcr('CASH');
				break;
			case '#f_tab-card':
				urlParam_card.filterVal[3] = selrowData('#jqGrid').db_paymode;
				refreshGrid("#g_paymodecard",urlParam_card);
				break;
			case '#f_tab-cheque':
				getcr('cheque');
				break;
			case '#f_tab-debit':
				urlParam_bank.filterVal[3] = selrowData('#jqGrid').db_paymode;
				refreshGrid("#g_paymodebank",urlParam_bank);
				break;
			case '#f_tab-forex':
				refreshGrid("#g_forex",urlParam4_rc);
				break;
		}
		$("#g_paymodecard").jqGrid ('setGridWidth', $("#g_paymodecard_c")[0].clientWidth);
		$("#g_paymodebank").jqGrid ('setGridWidth', $("#g_paymodebank_c")[0].clientWidth);
		$("#g_forex").jqGrid ('setGridWidth', $("#g_forex_c")[0].clientWidth);
	});
	
	$("#dialogForm_RC")
		.dialog({
			width: 9/10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui){
				////// Popup login //////
				// var bootboxHtml = $('#LoginDiv').html().replace('LoginForm', 'LoginBootboxForm');
				
				// bootbox.confirm(bootboxHtml, function (result){
				//     console.log($('#ex1', '.LoginBootboxForm').val());
				//     console.log($('#till_tillcode','#description','#till_dept','#tillstatus','#defopenamt', '.LoginBootboxForm').val());
				// });
				////// End Popup login //////
				
				parent_close_disabled(true);
				
				$("#sysparam").jqGrid ('setGridWidth', Math.floor($("#sysparam_c")[0].offsetWidth));
				$("#g_paymodecard").jqGrid ('setGridWidth', $("#g_paymodecard_c")[0].clientWidth);
				$("#g_paymodebank").jqGrid ('setGridWidth', $("#g_paymodebank_c")[0].clientWidth);
				$("#g_forex").jqGrid ('setGridWidth', $("#g_forex_c")[0].clientWidth);
				switch(oper) {
					case state = 'add':
						mycurrency.formatOnBlur();
						$('#dbacthdr_paytype').val(tabform);
						$( this ).dialog( "option", "title", "Add" );
						enableForm('#formdata_RC');
						enableForm('.tab-content');
						rdonly('#formdata_RC');
						rdonly(tabform);
						break;
					case state = 'edit':
						$( this ).dialog( "option", "title", "Edit" );
						enableForm('#formdata_RC');
						frozeOnEdit("#dialogForm_RC");
						rdonly('#formdata_RC');
						break;
					case state = 'view':
						mycurrency.formatOn();
						$( this ).dialog( "option", "title", "View" );
						disableForm('#formdata_RC');
						disableForm(selrowData('#jqGrid').db_paytype);
						$( this ).dialog("option", "buttons", butt2);
						
						// switch(selrowData('#jqGrid').db_paytype) {
						// 	case state = '#f_tab-card':
						// 		urlParam_card.filterVal[3] = selrowData("#jqGrid").db_paymode;
						// 		refreshGrid("#g_paymodecard",urlParam_card);
						// 		// $('#g_paymodecard').trigger( 'reloadGrid' );
						// 		break;
						// 	case state = '#f_tab-debit':
						// 		urlParam_bank.filterVal[3] = selrowData("#jqGrid").db_paymode;
						// 		refreshGrid("#g_paymodebank",urlParam_bank);
						// 		// $('#g_paymodebank').trigger( 'reloadGrid' );
						// 		break;
						// 	case state = '#f_tab-forex':
						// 		refreshGrid("#g_forex",urlParam4_rc);
						// 		break;
						// }
						// break;
				}
				if(oper != 'view'){
					dialog_payercode.on();
					dialog_logindeptcode.on();
					// dialog_logintillcode.on();
				}
				if(oper != 'add'){
					// dialog_logintillcode.check(errorField);
					// dialog_payercode.check(errorField);
					showingForCash(selrowData("#jqGrid").db_amount,selrowData("#jqGrid").db_outamount,selrowData("#jqGrid").db_RCCASHbalance,selrowData("#jqGrid").db_RCFinalbalance,selrowData("#jqGrid").db_paytype);
				}
			},
			close: function (event, ui){
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_RC');
				emptyFormdata(errorField, "#f_tab-cash");
				emptyFormdata(errorField, "#f_tab-card");
				emptyFormdata(errorField, "#f_tab-cheque");
				emptyFormdata(errorField, "#f_tab-debit");
				emptyFormdata(errorField, '#f_tab-forex');
				$('.alert').detach();
				dialog_logindeptcode.off();
				// dialog_logintillcode.off();
				$("#formdata_RC a").off();
				$("#refresh_jqGrid").click();
				if(oper == 'view'){
					$( this ).dialog("option", "buttons", butt1);
				}
			},
			buttons: butt1,
		});
	
	$("#dialogForm_RF")
		.dialog({
			width: 9/10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui){
				$("#gridAllo").jqGrid ('setGridWidth', $("#gridAllo_c")[0].clientWidth);
				$("#g_paymodebank").jqGrid ('setGridWidth', $("#g_paymodebank_c")[0].clientWidth);
				$("#g_paymodecard").jqGrid ('setGridWidth', $("#g_paymodecard_c")[0].clientWidth);
				parent_close_disabled(true);
				dialog_payercodeRF.off();
				amountchgOnRF();
				urlParamAllo.oper = oper
				urlParamAllo.auditno = selrowData('#jqGrid').db_auditno;
				
				switch(oper) {
					case 'add':
						mycurrency.formatOnBlur();
						$('#dbacthdr_paytype').val(tabform);
						$( this ).dialog( "option", "title", "Add" );
						enableForm('#formdata_RF');
						enableForm('.tab-content');
						rdonly('#formdata_RF');
						rdonly(tabform);
						break;
					case 'edit':
						$( this ).dialog( "option", "title", "Edit" );
						enableForm('#formdata_RF');
						frozeOnEdit("#dialogForm_RF");
						rdonly('#formdata_RF');
						break;
					case 'view':
						mycurrency.formatOn();
						$( this ).dialog( "option", "title", "View" );
						disableForm('#formdata_RF');
						disableForm(selrowData('#jqGrid').db_paytype);
						$( this ).dialog("option", "buttons", butt2);
						
						// switch(selrowData('#jqGrid').db_paytype) {
						// 	case '#f_tab-card':
						// 		urlParam_card.filterVal[3] = selrowData("#jqGrid").db_paymode;
						// 		refreshGrid("#g_paymodecard",urlParam_card);
						// 		break;
						// 	case '#f_tab-debit':
						// 		urlParam_bank.filterVal[3] = selrowData("#jqGrid").db_paymode;
						// 		refreshGrid("#g_paymodebank",urlParam_bank);
						// 		break;
						// }
						urlParamAllo.payercode = selrowData('#jqGrid').db_payercode;
						refreshGrid("#gridAllo",urlParamAllo);
				}
				if(oper != 'view'){
					dialog_payercodeRF.off();
					myallocation.renewAllo(0);
				}
				if(oper != 'add'){
					// dialog_payercodeRF.check(errorField);
					showingForCash(selrowData("#jqGrid").db_amount,selrowData("#jqGrid").db_outamount,selrowData("#jqGrid").db_RCCASHbalance,selrowData("#jqGrid").db_RCFinalbalance,selrowData("#jqGrid").db_paytype);
				}
			},
			close: function (event, ui){
				amountchgOffRF();
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_RF');
				emptyFormdata(errorField, "#f_tab-cash");
				emptyFormdata(errorField, "#f_tab-card");
				emptyFormdata(errorField, "#f_tab-cheque");
				emptyFormdata(errorField, "#f_tab-debit");
				$('.alert').detach();
				$("#formdata_RF a").off();
				$("#refresh_jqGrid").click();
				if(oper == 'view'){
					$( this ).dialog("option", "buttons", butt1);
				}
			},
			buttons: butt1,
		});
	
	//////////////////////////////////allocation inside RF//////////////////////////////////
	var urlParamAllo_lama = {
		action: 'refund_allo_table',
		url: 'refund/table',
		payercode: ''
	}
	
	var urlParamAllo = {
		action: 'refund_allo_table',
		oper: 'add',
		auditno: 0,
		url: 'refund/table',
		payercode: ''
	}
	
	$("#gridAllo").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'idno', width: 40, hidden: true, key: true },
			{ label: 'Audit No', name: 'auditno', width: 40, hidden: true },
			{ label: 'Trantype', name: 'trantype', width: 40 },
			{ label: 'Receipt No', name: 'recptno', width: 40 },
			{ label: 'Document Date', name: 'entrydate', width: 50 },
			{ label: 'MRN', name: 'mrn', width: 50, formatter: padzero, unformat: unpadzero },
			{ label: 'EpisNo', name: 'episno', width: 50 },
			{ label: 'Src', name: 'source', width: 20, hidden: true },
			{ label: 'Type', name: 'trantype', width: 20 , hidden: true },
			{ label: 'Line No', name: 'lineno_', width: 20 , hidden: true },
			// { label: 'Batchno', name: 'NULL', width: 40 },
			{ label: 'Amount', name: 'amount', formatter: 'currency', width: 50 },
			{ label: 'O/S Amount', name: 'outamount', formatter: 'currency', width: 50 },
			{ label: 'Amount Paid', name: 'amtpaid', width: 50, editable: true },
			{ label: 'Balance', name: 'amtbal', width: 50, formatter: 'currency', formatoptions: { prefix: "" } },
		],
		autowidth: true,
		viewrecords: true,
		multiSort: true,
		height: 400,
		scroll: true,
		rowNum: 30,
		pager: "#pagerAllo",
		onSelectRow: function (rowid){
		},
		onPaging: function (button){
		},
		gridComplete: function (rowid){
			// startEdit();
			// $("#gridAllo_c input[type='checkbox']").on('click', function (){
			// 	var idno = $(this).attr("rowid");
			// 	var rowdata = $("#gridAllo").jqGrid ('getRowData', idno);
			// 	if($(this).prop("checked") == true){
			// 		$("#"+idno+"_amtpaid").val(rowdata.outamount).addClass( "valid" ).removeClass( "error" );
			// 		setbal(idno,0);
			// 		if(!myallocation.alloInArray(idno)){
			// 			myallocation.addAllo(idno,rowdata.outamount,0);
			// 		}else{
			// 			$("#"+idno+"_amtpaid").trigger("change");
			// 		}
			// 	}else{
			// 		$("#"+idno+"_amtpaid").val(0).addClass( "valid" ).removeClass( "error" );
			// 		setbal(idno,rowdata.outamount);
			// 		$("#"+idno+"_amtpaid").trigger("change");
			// 	}
			// });
			$("#gridAllo_c input[type='text'][rowid]").on('click', function (){
				var idno = $(this).attr("rowid");
				if(!myallocation.alloInArray(idno)){
					myallocation.addAllo(idno,' ',0);
				}
			});
			
			// delay(function (){
			// 	// $("#alloText").focus(); // AlloTotal
			// 	myallocation.retickallotogrid();
			// }, 100);
		},
	});
	
	$("#gridAllo").jqGrid('navGrid', '#pagerAllo', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function (){
			refreshGrid("#gridAllo",urlParamAllo);
		},
	})
	
	function get_debtorcode_outamountRF(payercode){
		var param = {
			url: './refund/table',
			action: 'get_debtorcode_outamountRF',
			payercode: payercode
		}
		
		$.get(param.url+"?"+$.param(param), function (data){
			
		},'json').done(function (data) {
			if(data.result == 'true'){
				$('input[name="dbacthdr_outamount"]').val(data.outamount);
			}else{
				// alert('Payer doesnt have outstanding amount');
			}
		});
	}
	/////////////////////////////////////end dialogForm/////////////////////////////////////
	
	/////////////////////////////////////////padzero/////////////////////////////////////////
	function padzero(cellvalue, options, rowObject){
		let padzero = 7, str = "";
		while(padzero > 0){
			str = str.concat("0");
			padzero--;
		}
		return pad(str, cellvalue, true);
	}
	
	function unpadzero(cellvalue, options, rowObject){
		return cellvalue.substring(cellvalue.search(/[1-9]/));
	}
	
	////////////////////////////////parameter for jqgrid url////////////////////////////////
	var urlParam = {
		action: 'maintable',
		url: './arenquiry/table',
		// source: $('#db_source').val(),
		// trantype: $('#db_trantype').val(),
	}
	
	/////////////////////////////////////////jqgrid/////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'compcode', name: 'db_compcode', hidden: true },
			// { label: 'Debtor Code', name: 'db_debtorcode', width: 30, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat: un_showdetail },
			{ label: 'Debtor Code', name: 'db_debtorcode', width: 10, classes: 'wrap text-uppercase', canSearch: true },
			{ label: 'Debtor Name', name: 'dm_name', width: 28, classes: 'wrap text-uppercase', canSearch: true, selected: true },
			{ label: 'Payer Code', name: 'db_payercode', width: 20, hidden: true },
			{ label: 'Audit No', name: 'db_auditno', width: 10, align: 'right', classes: 'wrap', canSearch: true, formatter: padzero, unformat: unpadzero },
			{ label: 'Invoice No', name: 'db_invno', hidden: true, canSearch: true },
			{ label: 'Sector', name: 'db_unit', width: 10, hidden: true, classes: 'wrap' },
			{ label: 'PO No', name: 'db_ponum', width: 8, formatter: padzero5, unformat: unpadzero, hidden: true },
			{ label: 'Document No', name: 'db_recptno', width: 18, align: 'right', canSearch: true },
			{ label: 'Date', name: 'db_posteddate', width: 12, canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'Amount', name: 'db_amount', width: 12, classes: 'wrap', align: 'right', formatter: 'currency' },
			{ label: 'Outamount', name: 'db_outamount', width: 12, classes: 'wrap', align: 'right', formatter: 'currency' },
			{ label: 'Status', name: 'db_recstatus', width: 12 },
			{ label: 'source', name: 'db_source', width: 10, hidden: true },
			{ label: 'Type', name: 'db_trantype', width: 5, canSearch: true, },
			{ label: 'lineno_', name: 'db_lineno_', width: 10, hidden: true },
			{ label: 'db_orderno', name: 'db_orderno', width: 10, hidden: true },
			{ label: 'debtortype', name: 'db_debtortype', width: 20, hidden: true },
			{ label: 'billdebtor', name: 'db_billdebtor', width: 20, hidden: true },
			{ label: 'approvedby', name: 'db_approvedby', width: 20, hidden: true },
			{ label: 'MRN', name: 'db_mrn', width: 10, align: 'right', canSearch: true, classes: 'wrap text-uppercase', formatter: showdetail, unformat: un_showdetail },
			{ label: 'episno', name: 'db_episno', width: 10, hidden: true },
			{ label: 'unit', name: 'db_unit', width: 10, hidden: true },
			{ label: 'termmode', name: 'db_termmode', width: 10, hidden: true },
			{ label: 'hdrtype', name: 'db_hdrtype', width: 10, hidden: true },
			{ label: 'paytype', name: 'db_paytype', width: 10, hidden: true },
			{ label: 'RCCASHbalance', name: 'db_RCCASHbalance', width: 10, hidden: true },
			{ label: 'RCFinalbalance', name: 'db_RCFinalbalance', width: 10, hidden: true },
			{ label: 'db_entrydate', name: 'db_entrydate', hidden: true },
			{ label: 'Department', name: 'db_deptcode', hidden: true },
			{ label: 'Date Send', name: 'db_datesend', width: 12, formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'Paymode', name: 'db_paymode', width: 18, classes: 'wrap text-uppercase', hidden: false },
			{ label: 'idno', name: 'db_idno', width: 10, hidden: true, key: true },
			{ label: 'adduser', name: 'db_adduser', width: 10, hidden: true },
			{ label: 'adddate', name: 'db_adddate', width: 10, hidden: true },
			{ label: 'upduser', name: 'db_upduser', width: 10, hidden: true },
			{ label: 'upddate', name: 'db_upddate', width: 10, hidden: true },
			{ label: 'Remark', name: 'db_remark', width: 20, classes: 'wrap', hidden: true },
			// { label: 'unallocated', name: 'unallocated', width: 50, classes: 'wrap', hidden: true },
			{ label: 'db_unallocated', name: 'db_unallocated', width: 50, classes: 'wrap', hidden: true },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 900,
		height: 400,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow: function (rowid, selected){
			if(selrowData("#jqGrid").db_trantype == 'RC' || selrowData("#jqGrid").db_trantype == 'RD' || selrowData("#jqGrid").db_trantype == 'RF'){
				$("#reprint_receipt").attr('href','./receipt/showpdf?auditno='+selrowData("#jqGrid").db_idno);
				$('#reprint_receipt').show();
			}else{
				$('#reprint_receipt').hide();
			}

			if(((selrowData("#jqGrid").db_trantype == 'RC') || (selrowData("#jqGrid").db_trantype == 'RD') || (selrowData("#jqGrid").db_trantype == 'CN') ) && ((selrowData("#jqGrid").db_outamount > 0))){
				$('#allocate').show();
			}else{
				$('#allocate').hide();
			}

			if(((selrowData("#jqGrid").db_trantype == 'RC') || (selrowData("#jqGrid").db_trantype == 'RD') || (selrowData("#jqGrid").db_trantype == 'CN') ) && ((selrowData("#jqGrid").db_outamount != selrowData("#jqGrid").db_amount))){
				$('#allocate_cancel').show();
			}else{
				$('#allocate_cancel').hide();
			}

			$('#CN_debtorcode_show, #DN_debtorcode_show, #IN_debtorcode_show, #alloc_debtorcode_show, #track_debtorcode_show,#DF_debtorcode_show').text(selrowData("#jqGrid").db_debtorcode);
			$('#CN_debtorname_show, #DN_debtorname_show, #IN_debtorname_show, #alloc_debtorname_show, #track_debtorname_show,#df_debtorname_show').text(selrowData("#jqGrid").dm_name);
			$('#CN_docno_show, #DN_docno_show, #IN_docno_show, #alloc_docno_show, #track_docno_show,#DF_docno_show').text(selrowData("#jqGrid").db_recptno);
			$('#CN_amount_show, #DN_amount_show, #IN_amount_show, #alloc_amount_show, #track_amount_show,#DF_amount_show').text(selrowData("#jqGrid").db_amount);
			$('#CN_outamount_show, #DN_outamount_show, #IN_outamount_show, #alloc_outamount_show, #track_outamount_show,#DF_outamount_show').text(selrowData("#jqGrid").db_outamount);
			
			$('#jqGrid3_CN_c,#jqGrid3_DN_c,#jqGrid3_IN_c,#jqGrid_Tracking_c,#jqGrid_df_c').hide();
			
			if(selrowData("#jqGrid").db_trantype == 'CN'){ // CN
				urlParam2_CN.source = selrowData("#jqGrid").db_source;
				urlParam2_CN.trantype = selrowData("#jqGrid").db_trantype;
				urlParam2_CN.auditno = selrowData("#jqGrid").db_auditno;
				// urlParam2_CN.filterVal[1] = selrowData("#jqGrid").db_auditno;
				
				$('#jqGrid3_CN_c').show();
				refreshGrid("#jqGrid3_CN",urlParam2_CN);
			}else if(selrowData("#jqGrid").db_trantype == 'DN'){ // DN
				urlParam2_DN.source = selrowData("#jqGrid").db_source;
				urlParam2_DN.trantype = selrowData("#jqGrid").db_trantype;
				urlParam2_DN.auditno = selrowData("#jqGrid").db_auditno;
				// urlParam2_DN.filterVal[1] = selrowData("#jqGrid").db_auditno;
				
				$('#jqGrid3_DN_c').show();
				refreshGrid("#jqGrid3_DN",urlParam2_DN);
			}else if(selrowData("#jqGrid").db_trantype == 'IN'){ // IN
				urlParam2_IN.source = selrowData("#jqGrid").db_source;
				urlParam2_IN.trantype = selrowData("#jqGrid").db_trantype;
				urlParam2_IN.billno = selrowData("#jqGrid").db_auditno;
				urlParam2_IN.deptcode = selrowData("#jqGrid").db_deptcode;
				refreshGrid("#jqGrid3_IN",urlParam2_IN);
				
				urlParam_Tracking.filterVal[1] = selrowData("#jqGrid").db_source;
				urlParam_Tracking.filterVal[2] = selrowData("#jqGrid").db_trantype;
				urlParam_Tracking.filterVal[3] = selrowData("#jqGrid").db_auditno;
				urlParam_Tracking.filterVal[4] = selrowData("#jqGrid").db_lineno_;
				refreshGrid("#jqGrid_Tracking",urlParam_Tracking);

				urlParam2_df.idno = selrowData("#jqGrid").db_idno;
				refreshGrid("#jqGrid_df",urlParam2_df);

				urlParam2_da.idno = selrowData("#jqGrid").db_idno;
				refreshGrid("#jqGrid_da",urlParam2_da);
				
				$('#jqGrid3_IN_c,#jqGrid_Tracking_c,#jqGrid_df_c').show();
			}else if(selrowData("#jqGrid").db_trantype == 'RF'){ // RF
				urlParamAllo.payercode = selrowData("#jqGrid").db_payercode;
				// refreshGrid("#gridAllo",urlParamAllo);
			}else if(selrowData("#jqGrid").db_trantype == 'RC'){ // RC
				// urlParam2_RC.source = selrowData("#jqGrid").db_source;
				// urlParam2_RC.trantype = selrowData("#jqGrid").db_trantype;
				// urlParam2_RC.billno = selrowData("#jqGrid").db_auditno;
				// urlParam2_RC.deptcode = selrowData("#jqGrid").db_deptcode;
			}else if(selrowData("#jqGrid").db_trantype == 'RD'){ // RD
				
			}
			
			if(rowid != null){
				var rowData = $('#jqGrid').jqGrid('getRowData', rowid);
				refreshGrid('#jqGridAlloc', urlParamAlloc,'kosongkan');
				// $("#pg_jqGridPager3 table").hide();
				// $("#pg_jqGridPager2 table").show();
			}
			urlParamAlloc.idno = selrowData("#jqGrid").db_idno;
			urlParamAlloc.auditno = selrowData("#jqGrid").db_auditno;
			refreshGrid("#jqGridAlloc",urlParamAlloc);
			
			if(selrowData("#jqGrid").db_trantype == 'RC' || selrowData("#jqGrid").db_trantype == 'RD'){
				$('#pdf_RCRD').show();
			}else{
				$('#pdf_RCRD').hide();
			}
			
			$("#pdf_CN").attr('href','./CreditNoteAR/showpdf?auditno='+selrowData("#jqGrid").db_auditno);
			$("#pdf_DN").attr('href','./DebitNote/showpdf?auditno='+selrowData("#jqGrid").db_auditno);
			if(selrowData("#jqGrid").db_episno == '0' || selrowData("#jqGrid").db_episno == ''){
				$("#pdf_IN").attr('href','./SalesOrder/showpdf?idno='+selrowData("#jqGrid").db_idno);
			}else{
				$("#pdf_IN").attr('href','./SalesOrder/showpdf?idno='+selrowData("#jqGrid").db_idno+'&idno_billsum='+selrowData("#jqGrid3_IN").idno);
			}
			$("#pdf_RCRD").attr('href','./receipt/showpdf?auditno='+selrowData("#jqGrid").db_auditno);
			
			$("#jqGrid").data('lastselrow',rowid);
		},
		ondblClickRow: function (rowid, iRow, iCol, e){
			$("#jqGridPager td[title='View Selected Row']").click();
		},
		gridComplete: function (){
			enabledPill();
			
			if($('#jqGrid').data('inputfocus') == 'customer_search'){
				$("#customer_search").focus();
				$('#jqGrid').data('inputfocus','');
				$('#customer_search_hb').text('');
				removeValidationClass(['#customer_search']);
			}else if($('#jqGrid').data('inputfocus') == 'department_search'){
				$("#department_search").focus();
				$('#jqGrid').data('inputfocus','');
				$('#department_search_hb').text('');
				removeValidationClass(['#department_search']);
			}else{
				$("#searchForm input[name=Stext]").focus();
			}
			fdl.set_array().reset();
		},
		loadComplete: function (data){
			// if((selrowData("#jqGrid").db_trantype == 'RC')){
			// 	$('#allocate').show();
			// } else if((selrowData("#jqGrid").db_trantype == 'RD')){
			// 	$('#allocate').hide();
			// }
			
			if($("#jqGrid").data('lastselrow') == undefined){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}else{
				$("#jqGrid").setSelection($("#jqGrid").data('lastselrow'));
				delay(function (){
					$('#jqGrid tr#'+$("#jqGrid").data('lastselrow')).focus();
				}, 300);
			}
			
			calc_jq_height_onchange("jqGrid");
			calc_jq_height_onchange("jqGridAlloc");
		},
	});
	
	/////////////////////////////////set label jqGrid right/////////////////////////////////
	jqgrid_label_align_right("#jqGrid");
	
	////////////////////////////////////start grid pager////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function (){
			refreshGrid("#jqGrid", urlParam);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-info-sign",
		title: "View Selected Row",
		onClickButton: function (){
			oper = 'view';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			if(selrowData("#jqGrid").db_trantype == 'CN'){ // CN
				populateFormdata("#jqGrid","#dialogForm_CN","#formdata_CN",selRowId,'view');
				$('#tot_alloc').val(parseFloat(selrowData("#jqGrid").db_amount) - parseFloat(selrowData("#jqGrid").db_outamount));
				mycurrency.formatOn();
				refreshGrid("#jqGrid2_CN",urlParam2_CN,'add');
				
				urlParam2_Alloc.source = selrowData("#jqGrid").db_source;
				urlParam2_Alloc.trantype = selrowData("#jqGrid").db_trantype;
				urlParam2_Alloc.auditno = selrowData("#jqGrid").db_auditno;
				refreshGrid("#jqGrid2_Alloc",urlParam2_Alloc,'add');
			}else if(selrowData("#jqGrid").db_trantype == 'DN'){ // DN
				populateFormdata("#jqGrid", "#dialogForm_DN", "#formdata_DN", selRowId, 'view', '');
				refreshGrid("#jqGrid2_DN",urlParam2_DN,'add');
			}else if(selrowData("#jqGrid").db_trantype == 'IN'){ // IN
				populateFormdata("#jqGrid", "#dialogForm_IN", "#formdata_IN", selRowId, 'view', '');
				refreshGrid("#jqGrid2_IN",urlParam2_IN,'add');
			}else if(selrowData("#jqGrid").db_trantype == 'RC'){ // RC
				$( "input:radio[name='optradio'][value='receipt']" ).prop( "checked", true );
				$( "input:radio[name='optradio'][value='receipt']" ).change();
				populateFormdata("#jqGrid", "#dialogForm_RC", "#formdata_RC", selRowId, 'view', '');
				getdata('RC',selrowData("#jqGrid").db_idno);
				refreshGrid("#sysparam",urlParam_sys);
			}else if(selrowData("#jqGrid").db_trantype == 'RD'){ // RD
				$( "input:radio[name='optradio'][value='deposit']" ).prop( "checked", true );
				$( "input:radio[name='optradio'][value='deposit']" ).change();
				populateFormdata("#jqGrid", "#dialogForm_RC", "#formdata_RC", selRowId, 'view', '');
				getdata('RC',selrowData("#jqGrid").db_idno);
				refreshGrid("#sysparam",urlParam_sys);
			}else if(selrowData("#jqGrid").db_trantype == 'RF'){ // RF
				populateFormdata("#jqGrid", "#dialogForm_RF", "#formdata_RF", selRowId, 'view', '');
				getdata('RF',selrowData("#jqGrid").db_idno);
				// console.log(selrowData("#jqGrid").db_idno);
				
				urlParamAllo.payercode = selrowData("#jqGrid").db_payercode;
				refreshGrid("#gridAllo",urlParamAllo);
			}
		},
	});
	////////////////////////////////////////end grid////////////////////////////////////////
	
	///////////////////////////////////////Allocation///////////////////////////////////////
	/////////////////////////////parameter for jqGridAlloc url/////////////////////////////
	////allocation for jgridAlloc////
	var urlParamAlloc = {
		action: 'get_alloc',
		url: './arenquiry/table',
		auditno: ''
	};
	
	///////////////////////////////////////jqGridAlloc///////////////////////////////////////
	$("#jqGridAlloc").jqGrid({
		datatype: "local",
		editurl: "./arenquiry/form",
		colModel: [
			// { label: 'compcode', name: 'compcode', width: 20, hidden: true },
			// { label: 'lineno_', name: 'lineno_', width: 20, hidden: true },
			// { label: 'idno', name: 'idno', width: 20, hidden: true },
			{ label: 'System Auto No.', name: 'sysAutoNo', width: 50, classes: 'wrap' },
			{ label: 'Source', name: 'source', width: 10, classes: 'wrap', hidden: true },
			{ label: 'TT', name: 'trantype', width: 10, classes: 'wrap', hidden: true },
			{ label: 'Audit No', name: 'auditno', width: 10, classes: 'wrap', formatter: padzero, unformat: unpadzero, hidden: true },
			{ label: 'Debtor', name: 'debtorcode', width: 50, classes: 'wrap text-uppercase', formatter: showdetail, unformat: un_showdetail },
			{ label: 'Payer', name: 'payercode', width: 50, classes: 'wrap text-uppercase', formatter: showdetail, unformat: un_showdetail },
			{ label: 'Amount', name: 'amount', width: 40, classes: 'wrap', align: 'right', formatter: 'currency' },
			{ label: 'Document No', name: 'recptno', width: 50, align: 'right' },
			{ label: 'Paymode', name: 'paymode', width: 50, classes: 'wrap text-uppercase', formatter: showdetail, unformat: un_showdetail },
			{ label: 'Alloc Date', name: 'allocdate', width: 50, formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'MRN', name: 'mrn', width: 50, align: 'right', classes: 'wrap text-uppercase', formatter: showdetail, unformat: un_showdetail },
			{ label: 'Episno', name: 'episno', width: 20, align: 'right' },
		],
		shrinkToFit: true,
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		pager: "#jqGridPagerAlloc",
		loadComplete: function (data){
			calc_jq_height_onchange("jqGridAlloc");
			
			urlParamAlloc.idno = selrowData("#jqGrid").db_idno;
			refreshGrid("#jqGridAlloc",urlParamAlloc,'add');
		},
		gridComplete: function (){
			fdl.set_array().reset();
		},
	});
	jqgrid_label_align_right("#jqGridAlloc");
	
	$("#jqGridAlloc_panel").on("show.bs.collapse", function (){
		$("#jqGridAlloc").jqGrid ('setGridWidth', Math.floor($("#jqGridAlloc_c")[0].offsetWidth-$("#jqGridAlloc_c")[0].offsetLeft-18));
	});
	
	/////////////////////////////////parameter for jqgrid url/////////////////////////////////
	////////////////////////////////////////////CN////////////////////////////////////////////
	var urlParam2_CN = {
		action: 'get_table_dtl',
		url: 'CreditNoteARDetail/table',
		source: '',
		trantype: '',
		auditno: '',
		// field: ['dbactdtl.compcode','dbactdtl.source','dbactdtl.trantype','dbactdtl.auditno','dbactdtl.lineno_','dbactdtl.deptcode','dbactdtl.category','dbactdtl.document','dbactdtl.AmtB4GST','dbactdtl.GSTCode','dbactdtl.amount','dbactdtl.grnno','dbactdtl.amtslstax as tot_gst'],
		// table_name: ['debtor.dbactdtl AS dbactdtl'],
		// table_id: 'lineno_',
		// filterCol: ['dbactdtl.compcode','dbactdtl.auditno','dbactdtl.recstatus','dbactdtl.source','dbactdtl.trantype'],
		// filterVal: ['session.compcode','','<>.DELETE','PB','CN']
	};
	
	////////////////////////////////////////jqGrid2_CN////////////////////////////////////////
	$("#jqGrid2_CN").jqGrid({
		datatype: "local",
		editurl: "./CreditNoteARDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'AuditNo', name: 'auditno', hidden: true },
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden: true, editable: false },
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden: true, editable: false },
			{ label: 'Department', name: 'deptcode', width: 150, classes: 'wrap', canSearch: true, editable: false,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail, edittype: 'custom',
				editoptions: {
					custom_element: deptcodeCNCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Category', name: 'category', width: 250, edittype: 'text', classes: 'wrap', hidden: true, editable: false,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail, edittype: 'custom',
				editoptions: {
					custom_element: categoryCNCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Document', name: 'document', width: 230, classes: 'wrap', hidden: true, editable: false },
			{ label: 'GST Code', name: 'GSTCode', width: 90, classes: 'wrap', editable: false,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail, edittype: 'custom',
				editoptions: {
					custom_element: GSTCodeCNCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Amount Before GST', name: 'AmtB4GST', width: 90, classes: 'wrap', editable: false, align: "right", formatter: 'currency',
				formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 }
			},
			{ label: 'Total Tax Amount', name: 'tot_gst', width: 90, align: 'right', classes: 'wrap', editable: false },
			{ label: 'Amount', name: 'amount', width: 90, classes: 'wrap', editable: false, align: "right", formatter: 'currency',
				formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 }
			},
			{ label: 'rate', name: 'rate', width: 50, classes: 'wrap', hidden: true },
			{ label: 'idno', name: 'idno', editable: false, hidden: true },
			{ label: 'No', name: 'lineno_', editable: false, hidden: true },
			{ label: 'recstatus', name: 'recstatus', hidden: true },
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 1150,
		height: 200,
		rowNum: 10,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager2",
		loadComplete: function (data){
			calc_jq_height_onchange("jqGrid2_CN");
		},
		gridComplete: function (){
			fdl.set_array().reset();
		},
		beforeSubmit: function (postdata, rowid){
		}
	});
	
	////////////////////////////////////////jqGrid3_CN////////////////////////////////////////
	$("#jqGrid3_CN").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2_CN").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3_CN",
		loadComplete: function (data){
			calc_jq_height_onchange("jqGrid3_CN");
		},
		gridComplete: function (){
			fdl.set_array().reset();
		}
	});
	jqgrid_label_align_right("#jqGrid3_CN");
	
	$("#jqGrid3_CN_panel").on("show.bs.collapse", function (){
		$("#jqGrid3_CN").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_CN_c")[0].offsetWidth-$("#jqGrid3_CN_c")[0].offsetLeft-18));
	});
	
	////////////////////////////parameter for jqGrid2_Alloc CN url////////////////////////////
	var urlParam2_Alloc = {
		action: 'get_alloc_table',
		url: 'CreditNoteAR/table',
		source: '',
		trantype: '',
		auditno: '',
	};
	
	/////////////////////////////////////jqGrid2_Alloc CN/////////////////////////////////////
	$("#jqGrid2_Alloc").jqGrid({
		datatype: "local",
		editurl: "./CreditNoteARDetail/form",
		colModel: [
			{ label: ' ', name: 'checkbox', width: 15, formatter: checkbox_jqgAlloc },
			{ label: 'Debtor', name: 'debtorcode', width: 100, classes: 'wrap', formatter: showdetail, unformat: un_showdetail },
			{ label: 'Document Date', name: 'entrydate', width: 100, classes: 'wrap',
				formatter: "date", formatoptions: { srcformat: 'Y-m-d', newformat: 'd/m/Y' }
			},
			{ label: 'Posted Date', name: 'posteddate', width: 100, classes: 'wrap',
				formatter: "date", formatoptions: { srcformat: 'Y-m-d', newformat: 'd/m/Y' }
			},
			{ label: 'Document No', name: 'recptno', width: 100, classes: 'wrap' },
			{ label: 'Amount', name: 'refamount', width: 100, classes: 'wrap',
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 },
				editable: false,
				align: "right",
				editrules: { required: true }, edittype: "text",
				editoptions: {
					readonly: "readonly",
					maxlength: 12,
					dataInit: function (element){
						element.style.textAlign = 'right';
						$(element).keypress(function (e){
							if((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)){
								return false;
							}
						});
					}
				},
			},
			{ label: 'O/S Amount', name: 'outamount', width: 100, align: 'right', classes: 'wrap', editable: false,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 },
				editrules: { required: false }, editoptions: { readonly: "readonly" },
			},
			{ label: 'Amount Paid', name: 'amount', width: 100, classes: 'wrap',
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 },
				editable: true,
				align: "right",
				editrules: { required: true }, edittype: "text",
				editoptions: {
					maxlength: 12,
					dataInit: function (element){
						element.style.textAlign = 'right';
						$(element).keypress(function (e){
							if((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)){
								return false;
							}
						});
					}
				},
			},
			{ label: 'Balance', name: 'balance', width: 100, classes: 'wrap', hidden: false,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 },
				editable: false,
				align: "right",
				editrules: { required: true }, edittype: "text",
				editoptions: {
					readonly: "readonly",
					maxlength: 12,
					dataInit: function (element){
						element.style.textAlign = 'right';
						$(element).keypress(function (e){
							if((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)){
								return false;
							}
						});
					}
				},
			},
			{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden: true },
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden: true },
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden: true },
			{ label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden: true },
			{ label: 'Line No', name: 'lineno_', width: 20, classes: 'wrap', hidden: true },
			{ label: 'docsource', name: 'docsource', width: 20, classes: 'wrap', hidden: true },
			{ label: 'doctrantype', name: 'doctrantype', width: 20, classes: 'wrap', hidden: true },
			{ label: 'docauditno', name: 'docauditno', width: 20, classes: 'wrap', hidden: true },
			{ label: 'refsource', name: 'refsource', width: 20, classes: 'wrap', hidden: true },
			{ label: 'reftrantype', name: 'reftrantype', width: 20, classes: 'wrap', hidden: true },
			{ label: 'refauditno', name: 'refauditno', width: 20, classes: 'wrap', hidden: true },
			{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden: true },
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'entrydate',
		sortorder: "asc",
		pager: "#jqGridPager2_Alloc",
		loadComplete: function (data){
			calc_jq_height_onchange("jqGrid2_Alloc");
		},
		gridComplete: function (){
			fdl.set_array().reset();
		},
		beforeSubmit: function (postdata, rowid){
		}
	});
	
	//////////////////////////////////////////////////////////////////////////////////////////
	function checkbox_jqgAlloc(cellvalue, options, rowObject){
		if(options.gid == "jqGrid2_Alloc"){
			return '';
		}else{
			return `<input class='checkbox_jqgAlloc' type="checkbox" name="checkbox" data-rowid="`+options.rowId+`">`;
		}
	}
	
	////////////////////////////////////////////DN////////////////////////////////////////////
	var urlParam2_DN = {
		action: 'get_table_dtl',
		url: 'DebitNoteDetail/table',
		source: '',
		trantype: '',
		auditno: '',
		// field: ['dbactdtl.compcode','dbactdtl.source','dbactdtl.trantype','dbactdtl.auditno','dbactdtl.lineno_','dbactdtl.deptcode','dbactdtl.category','dbactdtl.document','dbactdtl.AmtB4GST','dbactdtl.GSTCode','dbactdtl.amount','dbactdtl.grnno','dbactdtl.amtslstax as tot_gst'],
		// table_name: ['debtor.dbactdtl AS dbactdtl'],
		// table_id: 'lineno_',
		// filterCol: ['dbactdtl.compcode','dbactdtl.auditno','dbactdtl.recstatus','dbactdtl.source','dbactdtl.trantype'],
		// filterVal: ['session.compcode','','<>.DELETE','PB','DN']
	};
	
	////////////////////////////////////////jqGrid2_DN////////////////////////////////////////
	$("#jqGrid2_DN").jqGrid({
		datatype: "local",
		editurl: "./DebitNoteDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'AuditNo', name: 'auditno', hidden: true },
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden: true, editable: false },
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden: true, editable: false },
			// { label: 'Department', name: 'deptcode', width: 250, classes: 'wrap', canSearch: true, editable: false },
			// { label: 'Category', name: 'category', width: 250, edittype: 'text', classes: 'wrap', editable: false },
			{ label: 'Department', name: 'deptcode', width: 150, classes: 'wrap', canSearch: true, editable: false,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail, edittype: 'custom',
				editoptions: {
					custom_element: deptcodeDNCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Category', name: 'category', width: 250, edittype: 'text', classes: 'wrap', hidden: true, editable: false,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail, edittype: 'custom',
				editoptions: {
					custom_element: categoryDNCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Document', name: 'document', width: 120, classes: 'wrap', editable: false },
			// { label: 'GST Code', name: 'GSTCode', width: 150, classes: 'wrap', editable: false },
			{ label: 'GST Code', name: 'GSTCode', width: 90, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail, edittype: 'custom',
				editoptions: {
					custom_element: GSTCodeDNCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Amount Before GST', name: 'AmtB4GST', width: 90, classes: 'wrap', editable: false, align: "right", formatter: 'currency',
				formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 }
			},
			{ label: 'Total Tax Amount', name: 'tot_gst', width: 90, align: 'right', classes: 'wrap', editable: false, formatter: 'currency',
				formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 }
			},
			{ label: 'Amount', name: 'amount', width: 90, classes: 'wrap', editable: false, align: "right", formatter: 'currency',
				formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 }
			},
			{ label: 'rate', name: 'rate', width: 50, classes: 'wrap', hidden: true },
			{ label: 'idno', name: 'idno', editable: false, hidden: true },
			{ label: 'No', name: 'lineno_', editable: false, hidden: true },
			{ label: 'recstatus', name: 'recstatus', hidden: true },
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 1150,
		height: 200,
		rowNum: 10,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager2",
		loadComplete: function (data){
			calc_jq_height_onchange("jqGrid2_DN");
		},
		gridComplete: function (){
			fdl.set_array().reset();
		},
		beforeSubmit: function (postdata, rowid){
		}
	});
	
	////////////////////////////////////////jqGrid3_DN////////////////////////////////////////
	$("#jqGrid3_DN").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2_DN").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3_DN",
		loadComplete: function (data){
			calc_jq_height_onchange("jqGrid3_DN");
		},
		gridComplete: function (){
			fdl.set_array().reset();
		}
	});
	jqgrid_label_align_right("#jqGrid3_DN");
	
	$("#jqGrid3_DN_panel").on("show.bs.collapse", function (){
		$("#jqGrid3_DN").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_DN_c")[0].offsetWidth-$("#jqGrid3_DN_c")[0].offsetLeft-18));
	});
	
	////////////////////////////////////////////IN////////////////////////////////////////////
	var urlParam2_IN = {
		action: 'get_table_dtl',
		url: 'SalesOrderDetail/table',
		source: '',
		trantype: '',
		auditno: '',
		deptcode: ''
	};
	
	////////////////////////////////////////jqGrid2_IN////////////////////////////////////////
	$("#jqGrid2_IN").jqGrid({
		datatype: "local",
		editurl: "SalesOrderDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'No', name: 'lineno_', width: 50, classes: 'wrap', editable: false, hidden: true },
			// { label: 'Item Code', name: 'chggroup', width: 100, classes: 'wrap', editable: false },
			{ label: 'Item Code', name: 'chggroup', width: 280, classes: 'wrap', editable: false,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail, edittype: 'custom',
				editoptions: {
					custom_element: itemcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Item Description', name: 'description', width: 150, classes: 'wrap', editable: false, editoptions: { readonly: "readonly" }, hidden: true },
			{ label: 'UOM Code', name: 'uom', width: 120, classes: 'wrap', editable: false,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail, edittype: 'custom',
				editoptions: {
					custom_element: uomcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Tax', name: 'taxcode', width: 120, classes: 'wrap', editable: false,
				editrules: { custom: true, custom_func: cust_rules },
				formatter: showdetail, edittype: 'custom',
				editoptions: {
					custom_element: taxcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Unit Price', name: 'unitprice', width: 80, classes: 'wrap txnum', align: 'right', editable: false, formatter: 'currency',
				formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 }
			},
			{ label: 'Quantity', name: 'quantity', width: 80, align: 'right', classes: 'wrap txnum', editable: false, formatter: 'integer',
				formatoptions: { thousandsSeparator: "," }
			},
			{ label: 'Quantity on Hand', name: 'qtyonhand', width: 80, align: 'right', classes: 'wrap txnum', editable: false, formatter: 'integer',
				formatoptions: { thousandsSeparator: "," }
			},
			{ label: 'Bill Type <br>%', name: 'billtypeperct', width: 80, align: 'right', classes: 'wrap txnum', editable: false, formatter: 'currency',
				formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 }
			},
			{ label: 'Bill Type <br>Amount ', name: 'billtypeamt', width: 80, align: 'right', classes: 'wrap txnum', editable: false, formatter: 'currency',
				formatoptions: { thousandsSeparator: "," }
			},
			{ label: 'Total Amount <br>Before Tax', name: 'amtb4tax', width: 100, align: 'right', classes: 'wrap txnum', editable: false, formatter: 'currency',
				formatoptions: { thousandsSeparator: "," }
			},
			{ label: 'Tax Amount', name: 'taxamt', width: 80, align: 'right', classes: 'wrap txnum', editable: false, formatter: 'currency',
				formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 }
			},
			{ label: 'Total Amount', name: 'amount', width: 80, align: 'right', classes: 'wrap txnum', editable: false, formatter: 'currency',
				formatoptions: { thousandsSeparator: "," }
			},
			{ label: 'recstatus', name: 'recstatus', width: 80, classes: 'wrap', hidden: true },
			{ label: 'id', name: 'id', width: 10, hidden: true, key: true },
			{ label: 'idno', name: 'idno', width: 100, hidden: false },
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 1150,
		height: 200,
		rowNum: 10,
		sortname: 'id',
		sortorder: "desc",
		pager: "#jqGridPager2",
		loadComplete: function (data){
			calc_jq_height_onchange("jqGrid2_IN");
		},
		gridComplete: function (){
			fdl.set_array().reset();
		},
		afterShowForm: function (rowid){
		},
		beforeSubmit: function (postdata, rowid){
		}
	});
	
	////////////////////////////////////////jqGrid3_IN////////////////////////////////////////
	$("#jqGrid3_IN").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2_IN").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3_IN",
		loadComplete: function (data){
			calc_jq_height_onchange("jqGrid3_IN");
			$("#jqGrid3_IN").setSelection($("#jqGrid3_IN").getDataIDs()[0]);
		},
		onSelectRow: function (data){
			if(selrowData("#jqGrid").db_episno == '0' || selrowData("#jqGrid").db_episno == ''){
				$("#pdf_IN").attr('href','./SalesOrder/showpdf?idno='+selrowData("#jqGrid").db_idno);
			}else{
				$("#pdf_IN").attr('href','./SalesOrder/showpdf?idno='+selrowData("#jqGrid").db_idno+'&idno_billsum='+selrowData("#jqGrid3_IN").idno);
			}
		},
		gridComplete: function (){
			fdl.set_array().reset();
		}
	});
	jqgrid_label_align_right("#jqGrid3_IN");
	
	$("#jqGrid3_IN_panel").on("show.bs.collapse", function (){
		$("#jqGrid3_IN").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_IN_c")[0].offsetWidth-$("#jqGrid3_IN_c")[0].offsetLeft-18));
	});

	var urlParam2_df = {
		action: 'get_table_drcontrib',
		url: 'drcontrib/table',
		idno: ''
	};

	$("#jqGrid_df").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'No', name: 'lineno_', width: 50, classes: 'wrap', editable: false, hidden: true },
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'idno', name: 'idno', hidden: true, key: true },
			{ label: 'Doctor', name: 'drcode', width: 200, classes: 'wrap', formatter: showdetail},
			{ label: 'Bill Date', name: 'billdate', width: 100, classes: 'wrap',formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Chg Code', name: 'chgcode', width: 100, classes: 'wrap', formatter: showdetail},
			{ label: 'Gross Amount', name: 'chgamount', width: 100, classes: 'wrap txnum', align: 'right', editable: false, formatter: 'currency',
				formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 }
			},
			{ label: 'App Amount', name: 'drappamt', width: 100, classes: 'wrap txnum', align: 'right', editable: false, formatter: 'currency',
				formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 }
			},
		],
		shrinkToFit: true,
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridpager_df",
		loadComplete: function (data){
			calc_jq_height_onchange("jqGrid_df");
			$("#jqGrid_df").setSelection($("#jqGrid_df").getDataIDs()[0]);
		},
		onSelectRow: function (data){
		},
		gridComplete: function (){
			fdl.set_array().reset();
		}
	});
	jqgrid_label_align_right("#jqGrid_df");
	
	$("#jqGrid3_df_panel").on("show.bs.collapse", function (){
		$("#jqGrid_df").jqGrid ('setGridWidth', Math.floor(($("#jqGrid_df_c")[0].offsetWidth / 2) - 10));
	});

	var urlParam2_da = {
		action: 'get_table_dralloc',
		url: 'drcontrib/table',
		idno: ''
	};

	$("#jqGrid_da").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'No', name: 'lineno_', width: 50, classes: 'wrap', editable: false, hidden: true },
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'idno', name: 'idno', hidden: true, key: true },
			{ label: 'Alloc Date', name: 'allocdate', width: 100, classes: 'wrap',formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Paymode', name: 'paymode', width: 100 },
			{ label: 'Alloc Amount', name: 'drallocamt', width: 100, classes: 'wrap txnum', align: 'right', editable: false, formatter: 'currency',
				formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 }
			},
			{ label: 'App Amount', name: 'drappamt', width: 100, classes: 'wrap txnum', align: 'right', editable: false, formatter: 'currency',
				formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 }
			},
			{ label: 'Comm Amount', name: 'cccomamt', width: 100, classes: 'wrap txnum', align: 'right', editable: false, formatter: 'currency',
				formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2 }
			},
		],
		shrinkToFit: true,
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridpager_da",
		loadComplete: function (data){
			calc_jq_height_onchange("jqGrid_da");
			$("#jqGrid_da").setSelection($("#jqGrid_da").getDataIDs()[0]);
		},
		onSelectRow: function (data){
		},
		gridComplete: function (){
			fdl.set_array().reset();
		}
	});
	jqgrid_label_align_right("#jqGrid_da");
	
	$("#jqGrid3_df_panel").on("shown.bs.collapse", function (){
		$("#jqGrid_df").jqGrid ('setGridWidth', Math.floor(($("#jqGrid_df_c")[0].offsetWidth / 2) - 30));
		$("#jqGrid_da").jqGrid ('setGridWidth', Math.floor(($("#jqGrid_df_c")[0].offsetWidth / 2) - 30));
	});
	
	///////////////////////////////////Bill Tracking for IN///////////////////////////////////
	////////////////////////////////////hide at dialogForm////////////////////////////////////
	function hideatdialogForm(hide,saveallrow){
		if(saveallrow == 'saveallrow'){
			$("#jqGrid_Tracking_iledit,#jqGrid_Tracking_iladd,#jqGrid_Tracking_ilcancel,#jqGrid_Tracking_ilsave,#jqGridPagerTracking_Delete,#jqGridPagerTracking_EditAll,#jqGridPagerTracking_Refresh").hide();
			$("#jqGridPagerTracking_SaveAll,#jqGridPagerTracking_CancelAll").show();
		}else if(hide){
			$("#jqGrid_Tracking_iledit,#jqGrid_Tracking_iladd,#jqGrid_Tracking_ilcancel,#jqGrid_Tracking_ilsave,#jqGridPagerTracking_Delete,#jqGridPagerTracking_EditAll,#jqGridPagerTracking_SaveAll,#jqGridPagerTracking_CancelAll,#jqGridPagerTracking_Refresh").hide();
		}else{
			$("#jqGrid_Tracking_iladd,#jqGrid_Tracking_ilcancel,#jqGrid_Tracking_ilsave,#jqGridPagerTracking_Delete,#jqGridPagerTracking_EditAll,#jqGridPagerTracking_Refresh").show();
			$("#jqGridPagerTracking_SaveAll,#jqGrid_Tracking_iledit,#jqGridPagerTracking_CancelAll").hide();
		}
	}
	
	////////////////////////////////////////edit all////////////////////////////////////////
	function onall_editfunc(){
		errorField.length = 0;
		// dialog_code.on();
		
		// mycurrency2.formatOnBlur(); // make field to currency on leave cursor
		// mycurrency_np.formatOnBlur(); // make field to currency on leave cursor
	}
	
	/////////////////////////////////////jqGrid_Tracking/////////////////////////////////////
	var urlParam_Tracking = {
		action: 'tracking',
		url: './arenquiry/table',
		field: '',
		table_name: 'debtor.billtrack',
		table_id: 'idno',
		filterCol: ['compcode','source','trantype','auditno','lineno_'],
		filterVal: ['session.compcode','','','',''],
		sort_idno: true,
	}
	
	/////////////////////////////////parameter for saving url/////////////////////////////////
	var addmore_jqgrid2 = {more:false,state:false,edit:false}
	
	$("#jqGrid_Tracking").jqGrid({
		datatype: "local",
		editurl: "./arenquiry/form",
		colModel: [
			{ label: 'idno', name: 'idno', width: 10, hidden: true, key: true },
			{ label: 'lineno_', name: 'lineno_', hidden: true },
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'source', name: 'source', hidden: true },
			{ label: 'trantype', name: 'trantype', hidden: true },
			{ label: 'auditno', name: 'auditno', hidden: true },
			// { label: 'Seq No', name: 'seqno', width: 20, editable: true },
			// { label: 'Trx Code', name: 'trxcode', width: 50, classes: 'wrap', editable: true, edittype: "select", formatter: 'select',
			// 	editoptions: {
			// 		value: "Send to Debtor:Send to Debtor;Send to Consultant:Send to Consultant;Receive from Consultant:Receive from Consultant;Follow-up with Consultant:Follow-up with Consultant;Receive by Debtor:Receive by Debtor;Others:Others"
			// 	},
			// },
			{ label: 'Trx Code', name: 'trxcode', width: 50, classes: 'wrap', editable: true,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail, edittype: 'custom',
				editoptions: {
					custom_element: trxcodeCustomEdit,
					custom_value: galGridCustomValue2
				},
			},
			{ label: 'Trx Date', name: 'trxdate', width: 40, classes: 'wrap', editable: true, formatter: "date",
				formatoptions: { srcformat: 'Y-m-d', newformat: 'd/m/Y' },
				editoptions: {
					dataInit: function (element){
						$(element).datepicker({
							id: 'trxdate_datePicker',
							dateFormat: 'dd/mm/yy',
							minDate: "dateToday",
							showOn: 'focus',
							changeMonth: true,
							changeYear: true,
							onSelect: function (){
								$(this).focus();
							}
						});
					}
				}
			},
			{ label: 'Entered by', name: 'adduser', width: 30 },
			{ label: 'Entered date/time', name: 'adddate', width: 50 },
			{ label: 'Location', name: 'computerid', width: 20, hidden: true },
			{ label: 'Comment', name: 'comment_button', width: 100, formatter: formatterComment, unformat: unformatComment },
			{ label: 'Comment', name: 'comment_', width: 150, classes: 'wrap' },
			{ label: 'Status', name: 'recstatus', width: 40 },
			{ label: 'adddate', name: 'adddate', width: 10, hidden: true },
			{ label: 'upduser', name: 'upduser', width: 10, hidden: true },
			{ label: 'upddate', name: 'upddate', width: 10, hidden: true },
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 1150,
		height: 200,
		rowNum: 10,
		sortname: 'idno',
		sortorder: "asc",
		pager: "#jqGridPager_Tracking",
		loadComplete: function (data){
			if(addmore_jqgrid2.more == true){$('#jqGrid_Tracking_iladd').click();}
			else{
				$('#jqGrid_Tracking').jqGrid('setSelection', "1");
			}
			
			setjqgridHeight(data,'jqGrid_Tracking');
			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; // reset
			// calc_jq_height_onchange("jqGrid_Tracking");
		},
		gridComplete: function (){
			$("#jqGrid_Tracking").find(".comment_button").on("click", function (e){
				$("#comment_2").data('rowid',$(this).data('rowid'));
				$("#comment_2").data('grid',$(this).data('grid'));
				$("#dialog_comment").dialog( "open" );
			});
			fdl.set_array().reset();
			if(!hide_init){
				hide_init = 1;
				hideatdialogForm(false);
			}
		},
		beforeSubmit: function (postdata, rowid){
			// dialog_paymode.check(errorField);
		},
		ondblClickRow: function (rowid, iRow, iCol, e){
			// $("#jqGrid_Tracking_iledit").click();
			// $('#p_error').text(''); // hilangkan duplicate error msj after save
		},
	});
	var hide_init = 0;
	
	/////////////////////////////set label jqGrid_Tracking right/////////////////////////////
	jqgrid_label_align_right("#jqGrid_Tracking");
	
	//////////////////////////////////myEditOptions_Tracking//////////////////////////////////
	var myEditOptions_Tracking = {
		keys: true,
		extraparam: {
			"_token": $("#csrf_token").val()
		},
		oneditfunc: function (rowid){
			$("#jqGrid_Tracking").setSelection($("#jqGrid_Tracking").getDataIDs()[0]);
			errorField.length = 0;
			// $("#jqGrid2 input[name='deptcode']").focus().select();
			$("#jqGridPagerTracking_EditAll,#jqGridPagerTracking_Delete,#jqGridPagerTracking_Refresh").hide();
			
			// dialog_code.on();
			
			unsaved = false;
			
			$("input[name='comment_']").keydown(function (e){ // when click tab at comment_, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid_Tracking_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options){
			// $('#db_amount').val(response.responseText);
			// if(addmore_jqgrid2.state == true)addmore_jqgrid2.more = true; // only addmore after save inline
			addmore_jqgrid2.more = true; // state true maksudnyer ada isi, tak kosong
			
			urlParam_Tracking.filterVal[1] = selrowData("#jqGrid").db_source;
			urlParam_Tracking.filterVal[2] = selrowData("#jqGrid").db_trantype;
			urlParam_Tracking.filterVal[3] = selrowData("#jqGrid").db_auditno;
			urlParam_Tracking.filterVal[4] = selrowData("#jqGrid").db_lineno_;
			refreshGrid('#jqGrid_Tracking',urlParam_Tracking,'add');
			refreshGrid("#jqGrid", urlParam);
			
			$("#jqGridPagerTracking_EditAll,#jqGridPagerTracking_Delete,#jqGridPagerTracking_Refresh").show();
			errorField.length = 0;
		},
		errorfunc: function (rowid,response){
			alert(response.responseText);
			urlParam_Tracking.filterVal[1] = selrowData("#jqGrid").db_source;
			urlParam_Tracking.filterVal[2] = selrowData("#jqGrid").db_trantype;
			urlParam_Tracking.filterVal[3] = selrowData("#jqGrid").db_auditno;
			urlParam_Tracking.filterVal[4] = selrowData("#jqGrid").db_lineno_;
			refreshGrid('#jqGrid_Tracking',urlParam_Tracking,'add');
			$("#jqGridPagerTracking_Delete,#jqGridPagerTracking_Refresh").show();
		},
		beforeSaveRow: function (options, rowid){
			if(errorField.length > 0)return false;
			
			let data = $('#jqGrid_Tracking').jqGrid ('getRowData', rowid);
			// console.log(data);
			
			let editurl = "./arenquiry/form?"+
				$.param({
					action: 'add_Tracking',
					source: selrowData("#jqGrid").db_source,
					trantype: selrowData("#jqGrid").db_trantype,
					auditno: selrowData("#jqGrid").db_auditno,
					lineno_: selrowData("#jqGrid").db_lineno_,
					comment_: selrowData("#jqGrid_Tracking").comment_,
				});
			$("#jqGrid_Tracking").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc: function (response){
			errorField.length = 0;
			hideatdialogForm(false);
		},
		errorTextFormat: function (data){
			alert(data);
		}
	};
	
	////////////////////////////////////start grid pager////////////////////////////////////
	$("#jqGrid_Tracking").inlineNav('#jqGridPager_Tracking', {
		add: true,
		edit: true,
		cancel: true,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_Tracking
		},
		editParams: myEditOptions_Tracking
	}).jqGrid('navButtonAdd', "#jqGridPager_Tracking", {
		id: "jqGridPagerTracking_Delete",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function (){
			selRowId = $("#jqGrid_Tracking").jqGrid('getGridParam', 'selrow');
			if(!selRowId){
				bootbox.alert('Please select row');
			}else{
				bootbox.confirm({
					message: "Are you sure you want to delete this row?",
					buttons: {
						confirm: { label: 'Yes', className: 'btn-success' }, cancel: { label: 'No', className: 'btn-danger' }
					},
					callback: function (result){
						if(result == true){
							param = {
								_token: $("#csrf_token").val(),
								action: 'del_Tracking',
								idno: selrowData('#jqGrid_Tracking').idno,
								trxcode: selrowData('#jqGrid_Tracking').trxcode,
								source: selrowData("#jqGrid").db_source,
								trantype: selrowData("#jqGrid").db_trantype,
								auditno: selrowData("#jqGrid").db_auditno,
								lineno_: selrowData("#jqGrid").db_lineno_,
							}
							$.post("./arenquiry/form?"+$.param(param), {oper: 'del', "_token": $("#_token").val()}, function (data){
							}).fail(function (data){
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data){
								urlParam_Tracking.filterVal[1] = selrowData("#jqGrid").db_source;
								urlParam_Tracking.filterVal[2] = selrowData("#jqGrid").db_trantype;
								urlParam_Tracking.filterVal[3] = selrowData("#jqGrid").db_auditno;
								urlParam_Tracking.filterVal[4] = selrowData("#jqGrid").db_lineno_;
								refreshGrid("#jqGrid_Tracking", urlParam_Tracking);
								refreshGrid("#jqGrid", urlParam);
							});
						}else{
							$("#jqGridPagerTracking_EditAll").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager_Tracking", {
		id: "jqGridPagerTracking_EditAll",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-th-list",
		title: "Edit All Row",
		onClickButton: function (){
			errorField.length = 0;
			var ids = $("#jqGrid_Tracking").jqGrid('getDataIDs');
			for(var i = 0; i < ids.length; i++){
				var seldata = $('#jqGrid_Tracking').jqGrid('getRowData',ids[i]);
				
				if(seldata.recstatus != 'DEACTIVE'){
					$("#jqGrid_Tracking").jqGrid('editRow',ids[i]);
				}
				
				$("#jqGrid_Tracking select#"+ids[i]+"_trxcode").attr('disabled','disabled');
				
				// if($(".input-group#"+ids[i]+"_code").is(":visible")){
				// 	dialog_code.id_optid = ids[i];
				// 	dialog_code.check(errorField,ids[i]+"_code","jqGrid2",null,
				// 		function (self){
				// 			if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
				// 		}
				// 	);
				// }
			}
			onall_editfunc();
			hideatdialogForm(true,'saveallrow');
		},
	}).jqGrid('navButtonAdd', "#jqGridPager_Tracking", {
		id: "jqGridPagerTracking_SaveAll",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-download-alt",
		title: "Save All Row",
		onClickButton: function (){
			var ids = $("#jqGrid_Tracking").jqGrid('getDataIDs');
			
			var jqGrid_Tracking_data = [];
			
			// if(errorField.length > 0){
			// 	console.log(errorField)
			// 	return false;
			// }
			
			for(var i = 0; i < ids.length; i++){
				// if(parseInt($('#'+ids[i]+"_quantity").val()) <= 0)return false;
				var data = $('#jqGrid_Tracking').jqGrid('getRowData',ids[i]);
				let retval = check_cust_rules("#jqGrid_Tracking",data);
				// console.log(retval);
				if(retval[0] != true){
					alert(retval[1]);
					// mycurrency2.formatOn();
					return false;
				}
				
				// cust_rules()
				
				if(data.recstatus != 'DEACTIVE'){
					var obj =
					{
						// 'lineno_' : ids[i],
						'idno' : data.idno,
						'lineno_' : $("#jqGrid_Tracking input#"+ids[i]+"_lineno_").val(),
						// 'source' : $("#jqGrid_Tracking input#"+ids[i]+"_source").val(),
						// 'trantype' : $("#jqGrid_Tracking input#"+ids[i]+"_trantype").val(),
						// 'auditno' : $("#jqGrid_Tracking input#"+ids[i]+"_auditno").val(),
						'trxcode' : $("#jqGrid_Tracking select#"+ids[i]+"_trxcode").val(),
						'trxdate' : $("#jqGrid_Tracking input#"+ids[i]+"_trxdate").val(),
						'comment_' : data.comment_,
					}
					
					jqGrid_Tracking_data.push(obj);
				}
			}
			
			var param = {
				action: 'edit_all_Tracking',
				_token: $("#csrf_token").val(),
				idno: selrowData('#jqGrid_Tracking').idno,
				source: selrowData("#jqGrid").db_source,
				trantype: selrowData("#jqGrid").db_trantype,
				auditno: selrowData("#jqGrid").db_auditno,
				lineno_: selrowData("#jqGrid").db_lineno_,
			}
			
			$.post("/arenquiry/form?"+$.param(param), {oper: 'edit_all', dataobj: jqGrid_Tracking_data}, function (data){
			}).fail(function (data){
				// alert(dialog,data.responseText);
			}).done(function (data){
				// mycurrency.formatOn();
				hideatdialogForm(false);
				urlParam_Tracking.filterVal[1] = selrowData("#jqGrid").db_source;
				urlParam_Tracking.filterVal[2] = selrowData("#jqGrid").db_trantype;
				urlParam_Tracking.filterVal[3] = selrowData("#jqGrid").db_auditno;
				urlParam_Tracking.filterVal[4] = selrowData("#jqGrid").db_lineno_;
				refreshGrid("#jqGrid_Tracking", urlParam_Tracking);
				refreshGrid("#jqGrid", urlParam);
			});
		},
	}).jqGrid('navButtonAdd', "#jqGridPager_Tracking", {
		id: "jqGridPagerTracking_CancelAll",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-remove-circle",
		title: "Cancel",
		onClickButton: function (){
			hideatdialogForm(false);
			urlParam_Tracking.filterVal[1] = selrowData("#jqGrid").db_source;
			urlParam_Tracking.filterVal[2] = selrowData("#jqGrid").db_trantype;
			urlParam_Tracking.filterVal[3] = selrowData("#jqGrid").db_auditno;
			urlParam_Tracking.filterVal[4] = selrowData("#jqGrid").db_lineno_;
			refreshGrid("#jqGrid_Tracking", urlParam_Tracking);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager_Tracking", {
		id: "jqGridPagerTracking_Refresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function (){
			urlParam_Tracking.filterVal[1] = selrowData("#jqGrid").db_source;
			urlParam_Tracking.filterVal[2] = selrowData("#jqGrid").db_trantype;
			urlParam_Tracking.filterVal[3] = selrowData("#jqGrid").db_auditno;
			urlParam_Tracking.filterVal[4] = selrowData("#jqGrid").db_lineno_;
			refreshGrid("#jqGrid_Tracking", urlParam_Tracking);
		},
	});
	
	$('#jqGrid_Tracking_ilcancel').click(function (){
		refreshGrid("#jqGrid", urlParam);
	});
	
	$("#jqGrid_Tracking_panel").on("show.bs.collapse", function (){
		$("#jqGrid_Tracking").jqGrid ('setGridWidth', Math.floor($("#jqGrid_Tracking_c")[0].offsetWidth-$("#jqGrid_Tracking_c")[0].offsetLeft-18));
	});
	
	///////////////////////////////////////////RC///////////////////////////////////////////
	var urlParam2_RC = {
		action: 'maintable',
		url: './receipt/table',
		field: '',
	}
	
	var saveParam2_RC = {
		action: 'receipt_save',
		url: 'receipt/form',
		oper: 'add',
		field: '',
		table_name: 'debtor.dbacthdr',
		table_id: 'auditno',
		fixPost: true,
		skipduplicate: true,
		returnVal: true,
		sysparam: { source: 'PB', trantype: 'RC', useOn: 'auditno' }
	};
	
	///////////////////////////////////////jqGrid2_RC///////////////////////////////////////
	$("#jqGrid2_RC").jqGrid({
		datatype: "local",
		editurl: "receipt/form",
		colModel: [
			{ label: 'auditno', name: 'dbacthdr_auditno', width: 90, hidden: true },
			{ label: 'lineno_', name: 'dbacthdr_lineno_', width: 90, hidden: true },
			{ label: 'billdebtor', name: 'dbacthdr_billdebtor', hidden: true },
			{ label: 'conversion', name: 'dbacthdr_conversion', hidden: true },
			{ label: 'hdrtype', name: 'dbacthdr_hdrtype', hidden: true },
			{ label: 'currency', name: 'dbacthdr_currency', hidden: true },
			{ label: 'tillcode', name: 'dbacthdr_tillcode', hidden: true },
			{ label: 'tillno', name: 'dbacthdr_tillno', hidden: true },
			{ label: 'debtortype', name: 'dbacthdr_debtortype', hidden: true },
			{ label: 'Date', name: 'dbacthdr_adddate', width: 50, formatter: dateFormatter, unformat: dateUNFormatter }, // tunjuk
			{ label: 'Type', name: 'dbacthdr_PymtDescription', classes: 'wrap', width: 50 }, // tunjuk
			{ label: 'Receipt No.', name: 'dbacthdr_recptno', classes: 'wrap', width: 60, canSearch: true }, // tunjuk
			{ label: 'entrydate', name: 'dbacthdr_entrydate', hidden: true },
			{ label: 'entrydate', name: 'dbacthdr_entrytime', hidden: true },
			{ label: 'entrydate', name: 'dbacthdr_entryuser', hidden: true },
			{ label: 'Payer Code', name: 'dbacthdr_payercode', width: 150, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat: un_showdetail },
			{ label: 'Payer Name', name: 'dbacthdr_payername', width: 150, classes: 'wrap text-uppercase', canSearch: true, hidden: true }, // tunjuk
			// { label: 'Debtor Code', name: 'dbacthdr_debtorcode', width: 400, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat: un_showdetail },
			{ label: 'MRN', name: 'dbacthdr_mrn', align: 'right', width: 50 }, // tunjuk
			{ label: 'Epis', name: 'dbacthdr_episno', align: 'right', width: 40 }, // tunjuk
			{ label: 'Patient Name', name: 'name', width: 150, classes: 'wrap', hidden: true },
			{ label: 'remark', name: 'dbacthdr_remark', hidden: true },
			{ label: 'authno', name: 'dbacthdr_authno', hidden: true },
			{ label: 'epistype', name: 'dbacthdr_epistype', hidden: true },
			{ label: 'cbflag', name: 'dbacthdr_cbflag', hidden: true },
			{ label: 'reference', name: 'dbacthdr_reference', hidden: true },
			{ label: 'Payment Mode', name: 'dbacthdr_paymode', width: 70 }, // tunjuk
			{ label: 'Amount', name: 'dbacthdr_amount', width: 60, align: 'right', formatter: 'currency', formatoptions: { prefix: "" } }, // tunjuk
			{ label: 'O/S Amount', name: 'dbacthdr_outamount', width: 60, align: 'right', formatter: 'currency', formatoptions: { prefix: "" } }, // tunjuk
			{ label: 'source', name: 'dbacthdr_source', hidden: true, checked: true },
			{ label: 'Trantype', name: 'dbacthdr_trantype', width: 45, formatter: showdetail, unformat:un_showdetail },
			{ label: 'Status', name: 'dbacthdr_recstatus', width: 50 }, // tunjuk
			{ label: 'bankchg', name: 'dbacthdr_bankcharges', hidden: true },
			{ label: 'expdate', name: 'dbacthdr_expdate', hidden: true },
			{ label: 'rate', name: 'dbacthdr_rate', hidden: true },
			{ label: 'units', name: 'dbacthdr_unit', hidden: true },
			{ label: 'invno', name: 'dbacthdr_invno', hidden: true },
			{ label: 'paytype', name: 'dbacthdr_paytype', hidden: true },
			{ label: 'RCcashbalance', name: 'dbacthdr_RCCASHbalance', hidden: true },
			{ label: 'RCFinalbalance', name: 'dbacthdr_RCFinalbalance', hidden: true },
			{ label: 'RCOSbalance', name: 'dbacthdr_RCOSbalance', hidden: true },
			{ label: 'idno', name: 'dbacthdr_idno', hidden: true },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		sortname: 'dbacthdr_idno',
		sortorder: 'desc',
		width: 900,
		height: 300,
		rowNum: 30,
		pager: "#jqGridPager",
		ondblClickRow: function (rowid, iRow, iCol, e){
			$("#jqGridPager td[title='View Selected Row']").click();
		},
		onSelectRow: function (rowid){
			// allocate("#jqGrid");
		},
		gridComplete: function (){
			// $('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
			fdl.set_array().reset();
			if(oper == 'add'){
				$("#jqGrid2_RC").setSelection($("#jqGrid2_RC").getDataIDs()[0]);
			}
			
			$('#'+$("#jqGrid2_RC").jqGrid ('getGridParam', 'selrow')).focus();
		},
		loadComplete: function (data){
			calc_jq_height_onchange("jqGrid2_RC");
		}
	});
	
	var urlParam_sys = {
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		table_name: 'sysdb.sysparam',
		table_id: 'trantype',
		filterCol: ['source','trantype','compcode'],
		filterVal: ['PB','RC','session.compcode']
	}
	
	$("#sysparam").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'source', name: 'source', width: 60, hidden: true },
			{ label: 'Tran type', name: 'trantype', width: 60, hidden: true },
			{ label: 'Description', name: 'description', width: 150 },
			{ label: 'hdrtype', name: 'hdrtype', width: 150, hidden: true },
			{ label: 'updpayername', name: 'updpayername', width: 150, hidden: true },
			{ label: 'depccode', name: 'depccode', width: 150, hidden: true },
			{ label: 'depglacc', name: 'depglacc', width: 150, hidden: true },
			{ label: 'updepisode', name: 'updepisode', width: 150, hidden: true },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		width: 300,
		height: 150,
		rowNum: 30,
		gridComplete: function (rowid){
			$("#sysparam").setSelection($("#sysparam").getDataIDs()[0]);
		},
		onSelectRow: function (rowid, selected){
			if(rowid != null){
				rowData = $('#sysparam').jqGrid ('getRowData', rowid);
				$('#dbacthdr_trantype').val(rowData['trantype']);
				saveParam2_RC.sysparam.trantype = rowData['trantype'];
				$('#dbacthdr_PymtDescription').val(rowData['description']);
				if($("input:radio[name='optradio'][value='deposit']").is(':checked')){
					$("input:hidden[name='dbacthdr_hdrtype']").val(rowData['hdrtype']);
					$("input:hidden[name='updepisode']").val(rowData['updepisode']);
					$("input:hidden[name='updpayername']").val(rowData['updpayername']);
					$("#formdata_RC input[name='dbacthdr_crcostcode']").val(rowData['depccode']);
					$("#formdata_RC input[name='dbacthdr_cracc']").val(rowData['depglacc']);
					if(oper != 'view'){
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
		beforeSelectRow: function (rowid, e){
			if(oper == 'view'){
				// $('#'+$("#sysparam").jqGrid ('getGridParam', 'selrow')).focus();
				return false;
			}
		}
	});
	
	var urlParam2_rc = {
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		table_name: 'debtor.paymode',
		table_id: 'paymode',
		filterCol: ['source','paytype','compcode'],
		filterVal: ['AR','BANK','session.compcode'],
	}
	
	var urlParam_bank = {
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		table_name: 'debtor.paymode',
		table_id: 'paymode',
		filterCol: ['source','paytype','compcode','paymode'],
		filterVal: ['AR','BANK','session.compcode',''],
	}
	
	$("#g_paymodebank").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Pay Mode', name: 'paymode', width: 60 },
			{ label: 'Description', name: 'description', width: 150 },
			{ label: 'ccode', name: 'ccode', hidden: true },
			{ label: 'glaccno', name: 'glaccno', hidden: true },
		],
		autowidth: true,
		multiSort: true,
		loadonce: true,
		width: 300,
		height: 150,
		rowNum: 2000,
		onSelectRow: function (rowid, selected){
			if(rowid != null){
				rowData = $('#g_paymodebank').jqGrid ('getRowData', rowid);
				$("#f_tab-debit .form-group input[name='dbacthdr_paymode']").val(rowData['paymode']);
				$("#formdata_RC input[name='dbacthdr_drcostcode']").val(rowData['ccode']);
				$("#formdata_RC input[name='dbacthdr_dracc']").val(rowData['glaccno']);
				
				$("#formdata_RF input[name='dbacthdr_drcostcode']").val(rowData['ccode']);
				$("#formdata_RF input[name='dbacthdr_dracc']").val(rowData['glaccno']);
			}
		},
		beforeSelectRow: function (rowid, e){
			if(oper == 'view'){
				$('#'+$("#g_paymodebank").jqGrid ('getGridParam', 'selrow')).focus();
				return false;
			}
		}
	});
	
	var urlParam3_rc = {
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		table_name: 'debtor.paymode',
		table_id: 'paymode',
		filterCol: ['source','paytype','compcode'],
		filterVal: ['AR','CARD','session.compcode'],
	}
	
	var urlParam_card = {
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		table_name: 'debtor.paymode',
		table_id: 'paymode',
		filterCol: ['source','paytype','compcode','paymode'],
		filterVal: ['AR','CARD','session.compcode',''],
	}
	
	$("#g_paymodecard").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Pay Mode', name: 'paymode', width: 60 },
			{ label: 'Description', name: 'description', width: 150 },
			{ label: 'ccode', name: 'ccode', hidden: true },
			{ label: 'glaccno', name: 'glaccno', hidden: true },
			{ label: 'cardflag', name: 'cardflag', hidden: true },
			{ label: 'valexpdate', name: 'valexpdate', hidden: true },
		],
		autowidth: true,
		multiSort: true,
		loadonce: true,
		width: 300,
		height: 150,
		rowNum: 2000,
		onSelectRow: function (rowid, selected){
			if(rowid != null){
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
				
				$("#formdata_RC input[name='dbacthdr_drcostcode']").val(rowData['ccode']);
				$("#formdata_RC input[name='dbacthdr_dracc']").val(rowData['glaccno']);
				
				$("#formdata_RF input[name='dbacthdr_drcostcode']").val(rowData['ccode']);
				$("#formdata_RF input[name='dbacthdr_dracc']").val(rowData['glaccno']);
			}
		},
		beforeSelectRow: function (rowid, e){
			if(oper == 'view'){
				$('#'+$("#g_paymodecard").jqGrid ('getGridParam', 'selrow')).focus();
				return false;
			}
		}
	});
	
	var urlParam4_rc = {
		action: 'get_effdate',
		type: 'forex'
	}
	
	$("#g_forex").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Forex Code', name: 'forexcode', width: 60 },
			{ label: 'Description', name: 'description', width: 150 },
			{ label: 'costcode', name: 'costcode', hidden: true },
			{ label: 'glaccount', name: 'glaccount', hidden: true },
			{ label: 'Rate', name: 'rate', width: 50 },
			{ label: 'effdate', name: 'effdate', width: 50, hidden: true },
		],
		autowidth: true,
		multiSort: true,
		loadonce: true,
		width: 300,
		height: 150,
		rowNum: 2000,
		onSelectRow: function (rowid, selected){
			if(rowid != null){
				rowData = $('#g_forex').jqGrid ('getRowData', rowid);
				$("#f_tab-forex input[name='dbacthdr_paymode']").val("forex");
				$("#f_tab-forex input[name='curroth']").val(rowData['forexcode']);
				$("#f_tab-forex input[name='dbacthdr_rate']").val(rowData['rate']);
				$("#f_tab-forex input[name='dbacthdr_currency']").val(rowData['forexcode']);
				$("#formdata_RC input[name='dbacthdr_drcostcode']").val(rowData['costcode']);
				$("#formdata_RC input[name='dbacthdr_dracc']").val(rowData['glaccount']);
				
				$("#formdata_RF input[name='dbacthdr_drcostcode']").val(rowData['costcode']);
				$("#formdata_RF input[name='dbacthdr_dracc']").val(rowData['glaccount']);
				
				$("#f_tab-forex input[name='dbacthdr_amount']").on('blur',{data:rowData,type:'RM'},currencyChg);
				
				$("#f_tab-forex input[name='dbacthdr_amount2']").on('blur',{data:rowData,type:'oth'},currencyChg);
			}
		},
		beforeSelectRow: function (rowid, e){
			if(oper == 'view'){
				$('#'+$("#g_forex").jqGrid ('getGridParam', 'selrow')).focus();
				return false;
			}
		}
	});
	
	function currencyChg(event){
		var curval;
		mycurrency.formatOff();
		if(event.data.type == 'RM'){
			curval = $("#f_tab-forex input[name='dbacthdr_amount']").val();
			$("#f_tab-forex input[name='dbacthdr_amount2']").val(parseFloat(curval)*parseFloat(event.data.data.rate));
		}else if(event.data.type == 'oth'){
			curval = $("#f_tab-forex input[name='dbacthdr_amount2']").val();
			$("#f_tab-forex input[name='dbacthdr_amount']").val(parseFloat(curval)/parseFloat(event.data.data.rate));
		}
		mycurrency.formatOn();
	}
	
	///////////////////////////////////////////RF///////////////////////////////////////////
	var urlParam_rf = {
		action: 'maintable',
		url: './refund/table',
		field: '',
		// fixPost: true
	}
	
	var saveParam_rf = {
		action: 'refund_save',
		url: 'refund/form',
		oper: 'add',
		field: '',
		table_name: 'debtor.dbacthdr',
		table_id: 'auditno',
		fixPost: true,
		skipduplicate: true,
		returnVal: true,
		sysparam: { source: 'PB', trantype: 'RF', useOn: 'auditno' } // PB, RF, pValue +1
	};
	
	////////////////////////////////////////jqGrid_RF////////////////////////////////////////
	$("#jqGrid_RF").jqGrid({
		datatype: "local",
		// editurl: "refund/form",
		colModel: [
			{ label: 'Audit No', name: 'dbacthdr_auditno', width: 30 },
			{ label: 'lineno_', name: 'dbacthdr_lineno_', width: 30, hidden: true },
			{ label: 'source', name: 'dbacthdr_source', hidden: true, checked: true },
			{ label: 'Trantype', name: 'dbacthdr_trantype', width: 45 },
			{ label: 'Type', name: 'dbacthdr_PymtDescription', classes: 'wrap', width: 50, hidden: true },
			{ label: 'MRN', name: 'dbacthdr_mrn', align: 'right', width: 30 }, // tunjuk
			{ label: 'Epis', name: 'dbacthdr_episno', align: 'right', width: 30 }, // tunjuk
			{ label: 'billdebtor', name: 'dbacthdr_billdebtor', hidden: true },
			{ label: 'conversion', name: 'dbacthdr_conversion', hidden: true },
			{ label: 'hdrtype', name: 'dbacthdr_hdrtype', hidden: true },
			{ label: 'currency', name: 'dbacthdr_currency', hidden: true },
			{ label: 'tillcode', name: 'dbacthdr_tillcode', hidden: true },
			{ label: 'tillno', name: 'dbacthdr_tillno', hidden: true },
			{ label: 'debtortype', name: 'dbacthdr_debtortype', hidden: true },
			{ label: 'Date', name: 'dbacthdr_adddate', width: 50, formatter: dateFormatter, unformat: dateUNFormatter, hidden: true },
			{ label: 'Receipt No.', name: 'dbacthdr_recptno', classes: 'wrap', width: 60, hidden: true },
			{ label: 'entrydate', name: 'dbacthdr_entrydate', hidden: true },
			{ label: 'entrydate', name: 'dbacthdr_entrytime', hidden: true },
			{ label: 'entrydate', name: 'dbacthdr_entryuser', hidden: true },
			{ label: 'Payer', name: 'dbacthdr_payercode', width: 150, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat: un_showdetail },
			{ label: 'Payer Name', name: 'dbacthdr_payername', width: 150, classes: 'wrap text-uppercase', canSearch: true, hidden: true },
			{ label: 'Patient Name', name: 'name', width: 150, classes: 'wrap', hidden: true },
			{ label: 'remark', name: 'dbacthdr_remark', hidden: true },
			{ label: 'authno', name: 'dbacthdr_authno', hidden: true },
			{ label: 'epistype', name: 'dbacthdr_epistype', hidden: true },
			{ label: 'cbflag', name: 'dbacthdr_cbflag', hidden: true },
			{ label: 'reference', name: 'dbacthdr_reference', hidden: true },
			{ label: 'Payment Mode', name: 'dbacthdr_paymode', width: 70, hidden: true }, // tunjuk
			{ label: 'Amount', name: 'dbacthdr_amount', width: 60, align: 'right', formatter: 'currency', formatoptions: { prefix: "" } }, // tunjuk
			{ label: 'O/S Amount', name: 'dbacthdr_outamount', width: 60, align: 'right', formatter: 'currency', formatoptions: { prefix: "" } }, // tunjuk
			{ label: 'bankchg', name: 'dbacthdr_bankcharges', hidden: true },
			{ label: 'expdate', name: 'dbacthdr_expdate', hidden: true },
			{ label: 'rate', name: 'dbacthdr_rate', hidden: true },
			{ label: 'units', name: 'dbacthdr_unit', hidden: true },
			{ label: 'invno', name: 'dbacthdr_invno', hidden: true },
			{ label: 'paytype', name: 'dbacthdr_paytype', hidden: true },
			{ label: 'RCcashbalance', name: 'dbacthdr_RCCASHbalance', hidden: true },
			{ label: 'RCFinalbalance', name: 'dbacthdr_RCFinalbalance', hidden: true },
			{ label: 'Status', name: 'dbacthdr_recstatus', width: 50 }, // tunjuk
			{ label: 'idno', name: 'dbacthdr_idno', hidden: true },
			{ label: 'paycard_description', name: 'paycard_description', hidden: true },
			{ label: 'paybank_description', name: 'paybank_description', hidden: true },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		sortname: 'dbacthdr_idno',
		sortorder: 'desc',
		width: 900,
		height: 300,
		rowNum: 30,
		pager: "#jqGridPager_RF",
		ondblClickRow: function (rowid, iRow, iCol, e){
			// $("#jqGridPager_RF td[title='View Selected Row']").click();
			// $("#gridAllo input[name='tick']").hide();
		},
		onSelectRow: function (rowid){
			// urlParamAllo.payercode = selrowData("#jqGrid_RF").dbacthdr_payercode;
			// refreshGrid("#gridAllo",urlParamAllo);
			// $("#gridAllo input[name='tick']").hide();
		},
		gridComplete: function (){
			// fdl.set_array().reset();
			// $("#jqGrid_RF").setSelection($("#jqGrid_RF").getDataIDs()[0]);
			
			// $('#'+$("#jqGrid_RF").jqGrid ('getGridParam', 'selrow')).focus();
			// enabledPill();
			// refreshGrid("#jqGrid_RF",urlParam_rf);
			
			fdl.set_array().reset();
			if(oper == 'add'){
				$("#jqGrid_RF").setSelection($("#jqGrid_RF").getDataIDs()[0]);
			}
			
			$('#'+$("#jqGrid_RF").jqGrid ('getGridParam', 'selrow')).focus();
		},
		loadComplete: function (data){
			refreshGrid("#jqGrid_RF",urlParam_rf);
			calc_jq_height_onchange("jqGrid_RF");
		}
	});
	///////////////////////////////////////////end///////////////////////////////////////////
	
	//////////////////////handle searching, its radio button and toggle//////////////////////
	populateSelect('#jqGrid','#searchForm');
	
	///////////////////////add field into param, refresh grid if needed///////////////////////
	addParamField('#jqGrid', true, urlParam);
	
	
	////////////////////////////////////////cust_rules////////////////////////////////////////
	function cust_rules(value, name){
		var temp = null;
		switch(name){
			// CN
			case 'Department CN': temp = $("#jqGrid2_CN input[name='deptcode']");break;
			case 'Category CN': temp = $("#jqGrid2_CN input[name='category']");break;
			case 'GST Code CN': temp = $("#jqGrid2 input[name='GSTCode']");break;
			
			// DN
			case 'GST Code DN': temp = $("#jqGrid2_DN input[name='GSTCode']");break;
			case 'Department DN': temp = $("#jqGrid2_CN input[name='deptcode']");break;
			case 'Category DN': temp = $("#jqGrid2_CN input[name='category']");break;
			
			// IN
			case 'Item Code': temp = $("#jqGrid2_IN input[name='chggroup']");break;
			case 'UOM Code': temp = $("#jqGrid2_IN input[name='uom']");break;
			case 'Tax Code': temp = $("#jqGrid2_IN input[name='taxcode']");break;
			
			// RC
			
			case 'Category': temp = $('#category');break;
			case 'UOM Code': temp = $("#jqGrid2 input[name='uom']");break;
			case 'GSTCode': temp = $("#jqGrid2 input[name='GSTCode']");break;
			case 'PO UOM': temp = $("#jqGrid2 input[name='pouom']");
					var text = $( temp ).parent().siblings( ".help-block" ).text();
					if(text == 'Invalid Code'){
						return [false,"Please enter valid "+name+" value"];
					}
					break;
			case 'Price Code': temp = $("#jqGrid2 input[name='pricecode']");break;
			case 'Tax Code': temp = $("#jqGrid2 input[name='taxcode']");break;
			case 'Quantity Request': temp = $("#jqGrid2 input[name='quantity']");break;
		}
		if(temp == null) return [true,''];
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}
	
	///////////////////////////////////////custom input///////////////////////////////////////
	// CN
	function deptcodeCNCustomEdit(val, opt){
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2_CN" optid="'+opt.id+'" id="'+opt.id+'" name="deptcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	
	function categoryCNCustomEdit(val, opt){
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2_CN" optid="'+opt.id+'" id="'+opt.id+'" name="category" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	
	function GSTCodeCNCustomEdit(val, opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid2_CN" optid="'+opt.id+'" id="'+opt.id+'" name="GSTCode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a><input id="'+opt.id+'_gstpercent" name="gstpercent" type="hidden"></div><span class="help-block"></span>');
	}
	
	// DN
	function deptcodeDNCustomEdit(val, opt){
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2_DN" optid="'+opt.id+'" id="'+opt.id+'" name="deptcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	
	function categoryDNCustomEdit(val, opt){
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2_DN" optid="'+opt.id+'" id="'+opt.id+'" name="category" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	
	function GSTCodeDNCustomEdit(val, opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid2_DN" optid="'+opt.id+'" id="'+opt.id+'" name="GSTCode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a><input id="'+opt.id+'_gstpercent" name="gstpercent" type="hidden"></div><span class="help-block"></span>');
	}
	
	// IN
	function itemcodeCustomEdit(val, opt){
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2_IN" optid="'+opt.id+'" id="'+opt.id+'" name="chggroup" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	
	function uomcodeCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $(`<div class="input-group"><input jqgrid="jqGrid2_IN" optid="`+opt.id+`" id="`+opt.id+`" name="uom" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>
			<span><input id="`+opt.id+`_discamt" name="discamt" type="hidden"></span>
			<span><input id="`+opt.id+`_rate" name="rate" type="hidden"></span>`);
	}
	
	function taxcodeCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $(`<div class="input-group"><input jqgrid="jqGrid2_IN" optid="`+opt.id+`" id="`+opt.id+`" name="taxcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>`);
	}
	
	// jqGrid_Tracking for IN
	function trxcodeCustomEdit(val,opt){
		val = getEditVal(val);
		if(val != ''){
			var result_str = '<option role="option" value="'+val+'">'+val+'</option>';
			return $(`<select role="select" optid="`+opt.id+`" id="`+opt.id+`" name="trxcode" size="1" class="editable inline-edit-cell form-control">`+result_str+`</select>`);
		}
		
		var array_track = [
			"Send to Debtor","Send to Consultant","Receive from Consultant","Follow-up with Consultant","Receive by Debtor","Others"
		];
		var result = [
			"Send to Debtor","Send to Consultant","Receive from Consultant","Follow-up with Consultant","Receive by Debtor","Others"
		];
		
		var getRowData = $('#jqGrid_Tracking').jqGrid('getRowData');
		getRowData.forEach(function (e,i){
			if(i !== 0 && e.recstatus != 'DEACTIVE'){
				if(array_track.find((element) => e.trxcode)){
					result = result.filter((element) => element != e.trxcode);
				}
			}
		});
		
		var result_str = '';
		result.forEach(function (e,i){
			result_str = result_str+'<option role="option" value="'+e+'">'+e+'</option>';
		});
		
		return $(`<select role="select" optid="`+opt.id+`" id="`+opt.id+`" name="trxcode" size="1" class="editable inline-edit-cell form-control">`+result_str+`</select>`);
	}
	
	function formatterComment(cellvalue, options, rowObject){
		return "<button class='comment_button btn btn-success btn-xs' type='button' data-rowid='"+options.rowId+"' data-idno='"+rowObject.idno+"' data-grid='#"+options.gid+"' data-comment_='"+rowObject.comment_+"'><i class='fa fa-file-text-o'></i> Comment</button>";
	}
	
	function unformatComment(cellvalue, options, rowObject){
		return null;
	}
	
	function galGridCustomValue(elem, operation, value){
		if(operation == 'get'){
			return $(elem).find("input").val();
		}
		else if(operation == 'set'){
			$('input',elem).val(value);
		}
	}
	
	function galGridCustomValue2(elem, operation, value){
		if(operation == 'get'){
			return $(elem).val();
		}
		else if(operation == 'set'){
			$(elem).val(value);
		}
	}
	
	////////////////////////////changing status and trigger search////////////////////////////
	$('#Scol').on('change', whenchangetodate);
	$('#Trantype').on('change', searchTrantype);
	$('#Status').on('change', searchChange);
	$('#docuDate_search').on('click', searchDate);
	
	function whenchangetodate(){
		customer_search.off();
		department_search.off();
		$('#customer_search,#docuDate_from,#docuDate_to,#department_search').val('');
		$('#customer_search_hb').text('');
		$('#department_search_hb').text('');
		removeValidationClass(['#customer_search,#department_search']);
		if($('#Scol').val() == 'db_posteddate'){
			urlParam.searchCol = urlParam.searchVal = null;
			$("input[name='Stext'], #customer_text, #department_text, #debtor_outamount").hide("fast");
			$("#docuDate_text").show("fast");
		}else if($('#Scol').val() == 'db_debtorcode'){
			$("input[name='Stext'], #docuDate_text, #department_text").hide("fast");
			$("#customer_text, #debtor_outamount").show("fast");
			customer_search.on();
		}else if($('#Scol').val() == 'db_deptcode'){
			$("input[name='Stext'], #docuDate_text, #customer_text, #debtor_outamount").hide("fast");
			$("#department_text").show("fast");
			department_search.on();
		}else{
			$("#customer_text, #docuDate_text, #department_text, #debtor_outamount").hide("fast");
			$("input[name='Stext']").show("fast");
			$("input[name='Stext']").velocity({ width: "100%" });
		}
		
		if($('#Scol').val() == 'db_posteddate'){
			refreshGrid('#jqGrid', urlParam);
		}else{
			search('#jqGrid',$('#searchForm [name=Stext]').val(),$('#searchForm [name=Scol] option:selected').val(),urlParam);
		}
	}
	
	///////////////////////////populate data for dropdown search By///////////////////////////
	searchBy();
	function searchBy(){
		$.each($("#jqGrid").jqGrid('getGridParam', 'colModel'), function (index, value){
			if(value['canSearch']){
				if(value['selected']){
					$("#searchForm [id=Scol]").append(" <option selected value='" + value['name'] + "'>" + value['label'] + "</option>");
				}else{
					$("#searchForm [id=Scol]").append(" <option value='" + value['name'] + "'>" + value['label'] + "</option>");
				}
			}
		});
		searchClick2('#jqGrid', '#searchForm', urlParam, false);
	}
	
	/////////////////////////////////Showdetail Header Dialog/////////////////////////////////
	// var dialog_Customer = new ordialog(
	// 	'customer', 'debtor.debtormast', '#db_debtorcode', errorField,
	// 	{
	// 		colModel: [
	// 			{ label: 'DebtorCode', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
	// 			{ label: 'Description', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true, checked: true },
	// 		],
	// 		urlParam: {
	// 			filterCol: ['compcode','recstatus'],
	// 			filterVal: ['session.compcode','ACTIVE']
	// 		},
	// 	},{
	// 		title: "Select Customer",
	// 		open: function (){
	// 			dialog_Customer.urlParam.filterCol = ['recstatus','compcode'];
	// 			dialog_Customer.urlParam.filterVal = ['ACTIVE','session.compcode'];
	// 		}
	// 	},'urlParam','radio','tab'
	// );
	// dialog_Customer.makedialog();
	
	var dialog_mrnHDR = new ordialog(
		'dialog_mrnHDR', 'hisdb.pat_mast', "#jqGrid input[name='db_mrn']", errorField,
		{
			colModel: [
				{ label: 'MRN', name: 'MRN', width: 200, classes: 'pointer', canSearch: true, or_search: true , formatter: padzero, unformat: unpadzero },
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true, checked: true },
			],
			urlParam: {
				filterCol: ['compcode','ACTIVE'],
				filterVal: ['session.compcode','1']
			},
		},{
			title: "Select MRN",
			open: function (){
				dialog_mrnHDR.urlParam.filterCol = ['recstatus','ACTIVE'];
				dialog_mrnHDR.urlParam.filterVal = ['ACTIVE','1'];
			}
		},'none','radio','tab'
	);
	dialog_mrnHDR.makedialog(false);
	
	// CN
	var dialog_CustomerCN = new ordialog(
		'db_debtorcodeCN', 'debtor.debtormast', '#formdata_CN input[name=db_debtorcode]', errorField,
		{
			colModel: [
				{ label: 'DebtorCode', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true, checked: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus'],
				filterVal: ['session.compcode','ACTIVE']
			},
		},{
			title: "Select Customer",
			open: function (){
				dialog_CustomerCN.urlParam.filterCol = ['recstatus','compcode'];
				dialog_CustomerCN.urlParam.filterVal = ['ACTIVE','session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_CustomerCN.makedialog(false);
	
	var dialog_paymodeCN = new ordialog(
		'db_paymodeCN', 'debtor.paymode', "#formdata_CN input[name='db_paymode']", errorField,
		{
			colModel: [
				{ label: 'Paymode', name: 'paymode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Paytype', name: 'paytype', width: 200, classes: 'pointer', hidden: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus','source','paytype'],
				filterVal: ['session.compcode','ACTIVE','AR','Credit Note']
			},
			ondblClickRow: function (){
				// $('#db_remark').focus();
			},
			gridComplete: function (obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					// $('#db_remark').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title: "Select Paymode",
			open: function (){
				dialog_paymodeCN.urlParam.filterCol = ['recstatus','compcode','source','paytype'],
				dialog_paymodeCN.urlParam.filterVal = ['ACTIVE','session.compcode','AR','Credit Note'];
			}
		},'urlParam','radio','tab'
	);
	dialog_paymodeCN.makedialog(true);
	
	// DN
	var dialog_CustomerDN = new ordialog(
		'db_debtorcodeDN', 'debtor.debtormast', '#formdata_DN input[name=db_debtorcode]', errorField,
		{
			colModel: [
				{ label: 'DebtorCode', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true, checked: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus'],
				filterVal: ['session.compcode','ACTIVE']
			},
		},{
			title: "Select Customer",
			open: function (){
				dialog_CustomerDN.urlParam.filterCol = ['recstatus','compcode'];
				dialog_CustomerDN.urlParam.filterVal = ['ACTIVE','session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_CustomerDN.makedialog(false);
	
	var dialog_paymodeDN = new ordialog(
		'db_paymodeDN', 'debtor.paymode', "#formdata_DN input[name='db_paymode']", errorField,
		{
			colModel: [
				{ label: 'Paymode', name: 'paymode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Paytype', name: 'paytype', width: 200, classes: 'pointer', hidden: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus','source','paytype'],
				filterVal: ['session.compcode','ACTIVE','AR','Debit Note']
			},
			ondblClickRow: function (){
				// $('#db_remark').focus();
			},
			gridComplete: function (obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					// $('#db_remark').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title: "Select Paymode",
			open: function (){
				dialog_paymodeDN.urlParam.filterCol = ['recstatus','compcode','source','paytype'],
				dialog_paymodeDN.urlParam.filterVal = ['ACTIVE','session.compcode','AR','Debit Note'];
			}
		},'urlParam','radio','tab'
	);
	dialog_paymodeDN.makedialog(true);
	
	// IN-SalesOrder
	var dialog_deptcode = new ordialog(
		'db_deptcode', 'sysdb.department', '#formdata_IN input[name=db_deptcode]', errorField,
		{
			colModel: [
				{ label: 'SectorCode', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true, checked: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus','chgdept','storedept'],
				filterVal: ['session.compcode','ACTIVE','1','1']
			},
		},{
			title: "Select Unit",
			open: function (){
				dialog_deptcode.urlParam.filterCol = ['recstatus','compcode','chgdept','storedept'];
				dialog_deptcode.urlParam.filterVal = ['ACTIVE','session.compcode','1','1'];
			}
		},'urlParam','radio','tab'
	);
	dialog_deptcode.makedialog(false);
	
	var dialog_CustomerSO = new ordialog(
		'db_debtorcodeSO', 'debtor.debtormast', '#formdata_IN input[name=db_debtorcode]', errorField,
		{
			colModel: [
				{ label: 'DebtorCode', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true, checked: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus'],
				filterVal: ['session.compcode','ACTIVE']
			},
		},{
			title: "Select Customer",
			open: function (){
				dialog_CustomerSO.urlParam.filterCol = ['recstatus','compcode'];
				dialog_CustomerSO.urlParam.filterVal = ['ACTIVE','session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_CustomerSO.makedialog(false);
	
	var dialog_billtypeSO = new ordialog(
		'billtype', 'hisdb.billtymst', '#formdata_IN input[name=db_hdrtype]', errorField,
		{
			colModel: [
				{ label: 'Billtype', name: 'billtype', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true, checked: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus','opprice'],
				filterVal: ['session.compcode','ACTIVE','1']
			},
		},{
			title: "Select Billtype",
			open: function (){
				dialog_billtypeSO.urlParam.filterCol = ['recstatus','compcode','opprice'];
				dialog_billtypeSO.urlParam.filterVal = ['ACTIVE','session.compcode','1'];
			}
		},'urlParam','radio','tab'
	);
	dialog_billtypeSO.makedialog(false);
	
	var dialog_mrn = new ordialog(
		'dialog_mrn', 'hisdb.pat_mast', '#formdata_IN input[name=db_mrn]', errorField,
		{
			colModel: [
				{ label: 'MRN', name: 'MRN', width: 200, classes: 'pointer', canSearch: true, or_search: true , formatter: padzero, unformat: unpadzero },
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true, checked: true },
			],
			urlParam: {
				filterCol: ['compcode','ACTIVE'],
				filterVal: ['session.compcode','1']
			},
		},{
			title: "Select MRN",
			open: function (){
				dialog_mrn.urlParam.filterCol = ['recstatus','ACTIVE'];
				dialog_mrn.urlParam.filterVal = ['ACTIVE','1'];
			}
		},'none','radio','tab'
	);
	dialog_mrn.makedialog(false);
	
	// RC
	var dialog_logindeptcode = new ordialog(
		'till_dept', 'sysdb.department', '#till_dept', errorField,
		{
			colModel: [
				{ label: 'Department', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true, checked: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus'],
				filterVal: ['session.compcode','ACTIVE']
			},
			ondblClickRow: function (event){
				$('#tillstatus').focus();
				
				let data = selrowData('#'+dialog_logindeptcode.gridname);
				
				// sequence.set(data['deptcode']).get();
			},
			gridComplete: function (obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#tillstatus').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title: "Select Department",
			open: function (){
				dialog_logindeptcode.urlParam.filterCol = ['recstatus','compcode'];
				dialog_logindeptcode.urlParam.filterVal = ['ACTIVE','session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_logindeptcode.makedialog();
	
	var dialog_mrn = new ordialog(
		'mrn', 'hisdb.pat_mast', '#dbacthdr_mrn', errorField,
		{
			colModel: [
				{ label: 'MRN', name: 'MRN', width: 100, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Name', name: 'Name', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
				{ label: 'Last Episode', name: 'Episno', width: 100, classes: 'pointer' },
			],
			urlParam: {
				filterCol: ['compcode'],
				filterVal: ['session.compcode']
			},
			ondblClickRow: function (){
				let data = selrowData('#'+dialog_mrn.gridname);
				// $('#apacthdr_actdate').focus();
				$('#dbacthdr_mrn').val(data.MRN);
				$('#dbacthdr_episno').val(data.Episno);
			},
			gridComplete: function (obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					// $('#apacthdr_actdate').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title: "Select MRN",
			open: function (){
				dialog_mrn.urlParam.filterCol = ['compcode'],
				dialog_mrn.urlParam.filterVal = ['session.compcode']
			}
		},'urlParam','radio','tab'
	);
	dialog_mrn.makedialog(true);
	
	////////////////////////////////////////////RC////////////////////////////////////////////
	$( "#divMrnEpisode" ).hide();
	amountchgOn(true);
	$("input:radio[name='optradio']").change(function (){
		if($("input:radio[name='optradio'][value='receipt']").is(':checked')){
			amountchgOn(false);
			$( "#divMrnEpisode" ).hide();
			urlParam_sys.table_name = 'sysdb.sysparam';
			urlParam_sys.table_id = 'trantype';
			urlParam_sys.field = ['source','trantype','description'];
			urlParam_sys.filterCol = ['source','trantype','compcode'];
			urlParam_sys.filterVal = ['PB','RC','session.compcode'];
			refreshGrid('#sysparam',urlParam_sys);
			$('#dbacthdr_trantype').val('');
			$('#dbacthdr_PymtDescription').val('');
		}else if($("input:radio[name='optradio'][value='deposit']").is(':checked')){
			amountchgOff(false);
			$( "#divMrnEpisode" ).show();
			urlParam_sys.table_name = 'debtor.hdrtypmst';
			urlParam_sys.table_id = 'hdrtype';
			urlParam_sys.field = ['source','trantype','description','hdrtype','updpayername','depccode','depglacc','updepisode'];
			urlParam_sys.filterCol = ['compcode'];
			urlParam_sys.filterVal = ['session.compcode'];
			refreshGrid('#sysparam',urlParam_sys);
			$('#dbacthdr_trantype').val('');
			$('#dbacthdr_PymtDescription').val('');
		}
	});
	//////////////////////////////////////////end RC//////////////////////////////////////////
	
	//////////////////////////////////////////////////////////////////////////////////////////
	function searchDate(){
		urlParam.filterdate = [$('#docuDate_from').val(),$('#docuDate_to').val()];
		refreshGrid('#jqGrid',urlParam);
		urlParam.filterdate = null;
	}
	
	function searchTrantype(){
		var arrtemp = [$('#Trantype option:selected').val()];
		var filter = arrtemp.reduce(function (a,b,c){
			if(b == 'All'){
				return a;
			}else{
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		},{fct:['db.trantype'],fv:[],fc:[]});
		
		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		refreshGrid('#jqGrid',urlParam);
	}
	
	function searchChange(){
		var arrtemp = [$('#Status option:selected').val()];
		var filter = arrtemp.reduce(function (a,b,c){
			if(b == 'All'){
				return a;
			}else{
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		},{fct:['db.recstatus'],fv:[],fc:[]});
		
		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		refreshGrid('#jqGrid',urlParam);
	}
	
	var customer_search = new ordialog(
		'customer_search', 'debtor.debtormast', '#customer_search', 'errorField',
		{
			colModel: [
				{ label: 'Debtor Code', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true, checked: true },
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: false, checked: false },
			],
			urlParam: {
				filterCol: ['compcode','recstatus'],
				filterVal: ['session.compcode','ACTIVE']
			},
			ondblClickRow: function (){
				let data = selrowData('#' + customer_search.gridname).debtorcode;
				
				if($('#Scol').val() == 'db_debtorcode'){
					urlParam.searchCol = ["db.debtorcode"];
					urlParam.searchVal = [data];
				}
				// }else if($('#Scol').val() == 'db_payercode'){
				// 	urlParam.searchCol = ["db.payercode"];
				// 	urlParam.searchVal = [data];
				// }
				refreshGrid('#jqGrid', urlParam);
				
				var mycurrency3 = new currencymode(["#debtor_outamt"]);
				
				var param = {
					action: 'get_outamount',
					url: './arenquiry/table',
					debtorcode: data,
				};
				
				$.get("./arenquiry/table?" + $.param(param), function (data){
					
				}, 'json').done(function (data){
					if(!$.isEmptyObject(data)){
						$('#debtor_outamt').val(data.outamount);
						mycurrency3.formatOn();
					}
				});
			},
			gridComplete: function (obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					// $('#'+obj.dialogname).dialog('close');
				}
				// $('#db_debtorcode').val(data['debtorcode']);
			}
		},{
			title: "Select Customer",
			open: function (){
				customer_search.urlParam.filterCol = ['recstatus'];
				customer_search.urlParam.filterVal = ['ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	customer_search.makedialog(true);
	$('#customer_search').on('keyup',ifnullsearch);
	
	var department_search = new ordialog(
		'department_search', 'sysdb.department', '#department_search', 'errorField',
		{
			colModel: [
				{ label: 'Department Code', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus'],
				filterVal: ['session.compcode','ACTIVE']
			},
			ondblClickRow: function (){
				let data = selrowData('#' + department_search.gridname).deptcode;
				
				if($('#Scol').val() == 'db_deptcode'){
					urlParam.searchCol = ["db.deptcode"];
					urlParam.searchVal = [data];
				}
				refreshGrid('#jqGrid', urlParam);
			},
			gridComplete: function (obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					// $('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title: "Select Creditor",
			open: function (){
				department_search.urlParam.filterCol = ['recstatus'];
				department_search.urlParam.filterVal = ['ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	department_search.makedialog(true);
	$('#department_search').on('keyup',ifnullsearch);
	
	function ifnullsearch(){
		if($(this).val() == ''){
			urlParam.searchCol = [];
			urlParam.searchVal = [];
			$('#jqGrid').data('inputfocus',$(this).attr('id'));
			refreshGrid('#jqGrid', urlParam);
		}
	}
	
	resizeColumnHeader = function (){
		var rowHight, resizeSpanHeight,
		// get the header row which contains
		headerRow = $(this).closest("div.ui-jqgrid-view")
			.find("table.ui-jqgrid-htable>thead>tr.ui-jqgrid-labels");
		
		// reset column height
		headerRow.find("span.ui-jqgrid-resize").each(function (){
			this.style.height = "";
		});
		
		// increase the height of the resizing span
		resizeSpanHeight = "height: " + headerRow.height() + "px !important; cursor: col-resize;";
		headerRow.find("span.ui-jqgrid-resize").each(function (){
			this.style.cssText = resizeSpanHeight;
		});
		
		// set position of the dive with the column header text to the middle
		rowHight = headerRow.height();
		headerRow.find("div.ui-jqgrid-sortable").each(function (){
			var ts = $(this);
			ts.css("top", (rowHight - ts.outerHeight()) / 2 + "px");
		});
	},
	fixPositionsOfFrozenDivs = function (){
		var $rows;
		if(typeof this.grid.fbDiv !== "undefined"){
			$rows = $('>div>table.ui-jqgrid-btable>tbody>tr', this.grid.bDiv);
			$('>table.ui-jqgrid-btable>tbody>tr', this.grid.fbDiv).each(function (i){
				var rowHight = $($rows[i]).height(), rowHightFrozen = $(this).height();
				if($(this).hasClass("jqgrow")){
					$(this).height(rowHight);
					rowHightFrozen = $(this).height();
					if(rowHight !== rowHightFrozen){
						$(this).height(rowHight + (rowHight - rowHightFrozen));
					}
				}
			});
			$(this.grid.fbDiv).height(this.grid.bDiv.clientHeight);
			$(this.grid.fbDiv).css($(this.grid.bDiv).position());
		}
		if(typeof this.grid.fhDiv !== "undefined"){
			$rows = $('>div>table.ui-jqgrid-htable>thead>tr', this.grid.hDiv);
			$('>table.ui-jqgrid-htable>thead>tr', this.grid.fhDiv).each(function (i){
				var rowHight = $($rows[i]).height(), rowHightFrozen = $(this).height();
				$(this).height(rowHight);
				rowHightFrozen = $(this).height();
				if(rowHight !== rowHightFrozen){
					$(this).height(rowHight + (rowHight - rowHightFrozen));
				}
			});
			$(this.grid.fhDiv).height(this.grid.hDiv.clientHeight);
			$(this.grid.fhDiv).css($(this.grid.hDiv).position());
		}
	},
	fixGboxHeight = function (){
		var gviewHeight = $("#gview_" + $.jgrid.jqID(this.id)).outerHeight(),
			pagerHeight = $(this.p.pager).outerHeight();
		
		$("#gbox_" + $.jgrid.jqID(this.id)).height(gviewHeight + pagerHeight);
		gviewHeight = $("#gview_" + $.jgrid.jqID(this.id)).outerHeight();
		pagerHeight = $(this.p.pager).outerHeight();
		$("#gbox_" + $.jgrid.jqID(this.id)).height(gviewHeight + pagerHeight);
	}
	
	/////////////////////////////////start allocation RC & RD/////////////////////////////////
	var dialog_allodebtor = new ordialog(
		'AlloDebtor', 'debtor.debtormast', '#AlloDebtor', errorField,
		{
			colModel: [
				{ label: 'Code', name: 'debtorcode', width: 100, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus'],
				filterVal: ['session.compcode','ACTIVE']
			},
			ondblClickRow: function (){
				let data = selrowData('#'+dialog_allodebtor.gridname);
				$('#AlloDebtor').val(data.debtorcode);
				myallocation.renewAllo($('#AlloOutamt').val());
				urlParamManAlloc.filterVal[0] = data.debtorcode;
				refreshGrid("#gridManAlloc",urlParamManAlloc);
			},
			gridComplete: function (obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					// $('#apacthdr_actdate').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title: "Select MRN",
			open: function (){
				dialog_allodebtor.urlParam.filterCol = ['compcode','recstatus'],
				dialog_allodebtor.urlParam.filterVal = ['session.compcode','ACTIVE']
			},
			close:function(){
			}
		},'urlParam','radio','tab'
	);
	dialog_allodebtor.makedialog(true);
	
	var myallocation = new Allocation();
	var allocurrency = new currencymode(["#AlloBalance","#AlloTotal"]);
	
	$( "#allocateDialog" ).dialog({
		autoOpen: false,
		width: 9/10 * $(window).width(),
		modal: true,
		open: function (){
			$('button[classes=allocateDialog_save_btn]').show();
			dialog_allodebtor.on();
			$("#gridManAlloc").jqGrid ('setGridWidth', Math.floor($("#gridManAlloc_c")[0].offsetWidth-$("#gridManAlloc_c")[0].offsetLeft));
			grid = '#jqGrid';
			$('#AlloDtype').val(selrowData(grid).db_trantype);
			$('#AlloDtype2').html(selrowData(grid).db_PymtDescription);
			$('#AlloDno').val(selrowData(grid).db_recptno);
			$('#AlloDebtor').val(selrowData(grid).db_payercode);
			$('#AlloDebtor2').html(selrowData(grid).db_payername);
			$('#AlloPayer').val(selrowData(grid).db_payercode);
			$('#AlloPayer2').html(selrowData(grid).db_payername);
			$('#AlloAmt').val(selrowData(grid).db_amount);
			$('#AlloOutamt').val(selrowData(grid).db_outamount);
			$('#AlloBalance').val(selrowData(grid).db_outamount);
			$('#AlloTotal').val(0);
			$('#AlloAuditno').val(selrowData(grid).db_auditno);
			urlParamManAlloc.filterVal[0] = selrowData(grid).db_payercode;
			refreshGrid("#gridManAlloc",urlParamManAlloc);
			parent_close_disabled(true);
			myallocation.renewAllo(selrowData(grid).db_outamount);
		},
		close: function (event, ui){
			dialog_allodebtor.off();
			parent_close_disabled(false);
		},
		buttons:
			[{
				text: "Save", click: function (){
					$('button[classes=allocateDialog_save_btn],button[classes=allocateDialog_save_btn]').hide();
					if(parseFloat($("#AlloBalance").val()) < 0){
						alert("Balance cannot in negative values");
					}else if(myallocation.allo_error.length > 0){
						alert("Amount paid exceed O/S amount");
					}else{
						var obj = {
							allo: myallocation.arrayAllo
						}
						
						var saveParam = {
							action: 'receipt_save',
							url: 'receipt/form',
							oper: 'allocate',
							debtorcode: $('#AlloDebtor').val(),
							payercode: $('#AlloPayer').val(),
							_token: $('#csrf_token').val(),
							auditno: $('#AlloAuditno').val(),
							trantype: $('#AlloDtype').val(),
						}
						
						$.post(saveParam.url+'?'+$.param(saveParam), obj, function (data){
							
						}).fail(function (data){
							alert('error');
							// $('button[classes=allocateDialog_save_btn]').show();
						}).success(function (data){
							// $('button[classes=allocateDialog_save_btn]').show();
							refreshGrid('#jqGrid', urlParam);
							$('#allocateDialog').dialog('close');
						});
					}
				},classes: "allocateDialog_save_btn"
			},{
				text: "Cancel", click: function (){
					$(this).dialog('close');
				},classes: "allocateDialog_cancel_btn"
			}],
	});
	
	var urlParamManAlloc = {
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		table_name: 'debtor.dbacthdr',
		table_id: 'idno',
		sort_idno: true,
		filterCol: ['payercode','source','recstatus','outamount'],
		filterVal: ['','PB','POSTED','>.0'],
		WhereInCol: ['trantype'],
		WhereInVal: [['DN','IN']]
	}
	
	$("#gridManAlloc").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'idno', width: 40, hidden: true, key:true },
			{ label: 'Document No', name: 'auditno', width: 40 },
			{ label: 'Document Date', name: 'entrydate', width: 50 },
			{ label: 'MRN', name: 'mrn', width: 50 },
			{ label: 'EpisNo', name: 'episno', width: 50 },
			{ label: 'Src', name: 'source', width: 20, hidden: true },
			{ label: 'Type', name: 'trantype', width: 20, hidden: true },
			{ label: 'Line No', name: 'lineno_', width: 20, hidden: true },
			// { label: 'Batchno', name: 'NULL', width: 40 },
			{ label: 'Amount', name: 'amount', formatter: 'currency', width: 50 },
			{ label: 'O/S Amount', name: 'outamount', formatter: 'currency', width: 50 },
			{ label: ' ', name: 'tick', width: 20, editable: true, edittype: "checkbox", align: 'center' },
			{ label: 'Amount Paid', name: 'amtpaid', width: 50, editable: true },
			{ label: 'Balance', name: 'amtbal', width: 50, formatter: 'currency', formatoptions: { prefix: "" } },
		],
		autowidth: true,
		viewrecords: true,
		multiSort: true,
		loadonce: false,
		height: 400,
		scroll: false,
		rowNum: 100,
		pager: "#pagerManAlloc",
		onSelectRow: function (rowid){
		},
		onPaging: function (button){
		},
		gridComplete: function (rowid){
			startEdit();
			$("#gridManAlloc_c input[type='checkbox']").off('click');
			$("#gridAlloc_c input[type='text'][rowid]").off('click');
			
			$("#gridManAlloc_c input[type='checkbox']").on('click', function (){
				var idno = $(this).attr("rowid");
				var rowdata = $("#gridManAlloc").jqGrid ('getRowData', idno);
				if($(this).prop("checked") == true){
					$("#"+idno+"_amtpaid").val(rowdata.outamount).addClass( "valid" ).removeClass( "error" );
					setbal(idno,0);
					if(!myallocation.alloInArray(idno)){
						myallocation.addAllo(idno,rowdata.outamount,0);
					}else{
						$("#"+idno+"_amtpaid").trigger("change");
					}
				}else{
					$("#"+idno+"_amtpaid").val(0).addClass( "valid" ).removeClass( "error" );
					setbal(idno,rowdata.outamount);
					$("#"+idno+"_amtpaid").trigger("change");
				}
			});
			$("#gridAlloc_c input[type='text'][rowid]").on('click', function (){
				var idno = $(this).attr("rowid");
				if(!myallocation.alloInArray(idno)){
					myallocation.addAllo(idno,' ',0);
				}
			});
			
			delay(function (){
				// $("#alloText").focus(); // AlloTotal
				myallocation.retickallotogrid();
			}, 100);
			
			calc_jq_height_onchange("gridManAlloc");
		},
	});
	
	AlloSearch("#gridManAlloc",urlParamManAlloc);
	function AlloSearch(grid,urlParam){
		$("#alloText").on("keyup", function (){
			delay(function (){
				search(grid,$("#alloText").val(),$("#alloCol").val(),urlParam);
			}, 500);
		});
		
		$("#alloCol").on("change", function (){
			search(grid,$("#alloText").val(),$("#alloCol").val(),urlParam);
		});
	}
	
	function startEdit(){
		var ids = $("#gridManAlloc").jqGrid('getDataIDs');
		
		for(var i = 0; i < ids.length; i++){
			var entrydate = $("#gridManAlloc").jqGrid('getRowData', ids[i]).entrydate;
			$("#gridManAlloc").jqGrid('setCell', ids[i], 'NULL', moment(entrydate).format("DD-MMM"));
			$("#gridManAlloc").jqGrid('editRow', ids[i]);
		}
	};
	
	addParamField('#gridManAlloc',false,urlParamManAlloc,['tick','amtpaid','amtbal']);
	
	function Allocation(){
		this.arrayAllo = [];
		this.alloBalance = 0;
		this.alloTotal = 0;
		this.outamt = 0;
		this.allo_error = [];
		
		this.renewAllo = function (os){
			this.arrayAllo.length = 0;
			this.alloTotal = 0;
			this.alloBalance = parseFloat(os);
			this.outamt = parseFloat(os);
			
			this.updateAlloField();
		}
		this.addAllo = function (idno,paid,bal){
			var obj = getlAlloFromGrid(idno);
			obj.amtpaid = paid;
			obj.amtbal = bal;
			var fieldID = "#"+idno+"_amtpaid";
			var self = this;
			
			this.arrayAllo.push({idno:idno,obj:obj});
			
			$(fieldID).on('change',[idno,self.arrayAllo,self.allo_error],onchangeField);
			
			this.updateAlloField();
		}
		function onchangeField(obj){
			var idno = obj.handleObj.data[0];
			var arrayAllo = obj.handleObj.data[1];
			var allo_error = obj.handleObj.data[2];
			
			var alloIndex = getIndex(arrayAllo,idno);
			var outamt = $("#gridManAlloc").jqGrid('getRowData', idno).outamount;
			var newamtpaid = parseFloat(obj.target.value);
			newamtpaid = isNaN(Number(newamtpaid)) ? 0 : parseFloat(obj.target.value);
			if(parseFloat(newamtpaid) > parseFloat(outamt)){
				alert("Amount paid exceed O/S amount");
				$("#"+idno+"_amtpaid").addClass( "error" ).removeClass( "valid" );
				adderror_allo(allo_error,idno);
				obj.target.focus();
				return false;
			}
			$("#"+idno+"_amtpaid").removeClass( "error" ).addClass( "valid" );
			delerror_allo(allo_error,idno);
			var balance = outamt - newamtpaid;
			
			obj.target.value = numeral(newamtpaid).format('0,0.00');;
			arrayAllo[alloIndex].obj.amtpaid = newamtpaid;
			arrayAllo[alloIndex].obj.amtbal = balance;
			setbal(idno,balance);
			
			myallocation.updateAlloField();
		}
		function getIndex(array,idno){
			var retval = 0;
			$.each(array, function (index, obj){
				if(obj.idno == idno){
					retval = index;
					return false; // bila return false, skip .each terus pegi return retval
				}
			});
			return retval;
		}
		this.deleteAllo = function (idno){
			var self = this;
			$.each(self.arrayAllo, function (index, obj){
				if(obj.idno == idno){
					self.arrayAllo.splice(index, 1);
					return false;
				}
			});
		}
		this.alloInArray = function (idno){
			var retval = false;
			$.each(this.arrayAllo, function (index, obj){
				if(obj.idno == idno){
					retval = true;
					return false; // bila return false, skip .each terus pegi return retval
				}
			});
			return retval;
		}
		this.retickallotogrid = function (){
			var self = this;
			$.each(this.arrayAllo, function (index, obj){
				$("#"+obj.idno+"_amtpaid").on('change',[obj.idno,self.arrayAllo],onchangeField);
				if(obj.obj.amtpaid != " "){
					$("#"+obj.idno+"_amtpaid").val(obj.obj.amtpaid).removeClass( "error" ).addClass( "valid" );
					setbal(obj.idno,obj.obj.amtbal);
				}
			});
		}
		this.updateAlloField = function (){
			var self = this;
			this.alloTotal = 0;
			$.each(this.arrayAllo, function (index, obj){
				if(obj.obj.amtpaid != " "){
					self.alloTotal += parseFloat(obj.obj.amtpaid);
				}
			});
			this.alloBalance = this.outamt - this.alloTotal;
			
			$("#AlloTotal").val(this.alloTotal);
			$("#AlloBalance").val(this.alloBalance);
			if(this.alloBalance < 0){
				$("#AlloBalance").addClass( "error" ).removeClass( "valid" );
				// alert("Balance cannot in negative values");
			}else{
				$("#AlloBalance").addClass( "valid" ).removeClass( "error" );
			}
			allocurrency.formatOn();
		}
		function updateAllo(idno,amtpaid,arrayAllo){
			$.each(arrayAllo, function (index, obj){
				if(obj.idno == idno){
					obj.obj.amtpaid = amtpaid;
					return false; // bila return false, skip .each terus pegi return retval
				}
			});
		}
		function getlAlloFromGrid(idno){
			var temp = $("#gridManAlloc").jqGrid('getRowData', idno);
			return {idno:temp.idno,auditno:temp.auditno,amtbal:temp.amtbal,amtpaid:temp.amount};
		}
		function adderror_allo(array,idno){
			if($.inArray(idno,array) === -1){ // xjumpa
				array.push(idno);
			}
		}
		function delerror_allo(array,idno){
			if($.inArray(idno,array) !== -1){ // jumpa
				array.splice($.inArray(idno,array), 1);
			}
		}
	}
	
	function setbal(idno,balance){
		$("#gridManAlloc").jqGrid('setCell', idno, 'amtbal', balance);
	}
	
	$("#gridManAlloc").jqGrid('navGrid', '#pagerManAlloc', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function (){
			refreshGrid("#gridManAlloc",urlParamManAlloc);
		},
	})
	
	$('#allocate').click(function (){
		$( "#allocateDialog" ).dialog( "open" );
	});
	///////////////////////////////////end allocation part///////////////////////////////////
	
	///////////////////////////////AR STATEMENT LISTING STARTS///////////////////////////////
	$("#ARStatementDialog").dialog({
		autoOpen: false,
		width: 5/10 * $(window).width(),
		modal: true,
		open: function (){
			dialog_debtorFrom.on();
			dialog_debtorTo.on();
			parent_close_disabled(true);
		},
		close: function (event, ui){
			dialog_debtorFrom.off();
			dialog_debtorTo.off();
			parent_close_disabled(false);
		},
		buttons:
		[
		// 	{
		// 	text: "Generate PDF", click: function (){
		// 		window.open('./arenquiry/showpdf?debtorcode_from='+$('#debtorcode_from').val()+'&debtorcode_to='+$("#debtorcode_to").val()+'&datefr='+$("#datefr").val()+'&dateto='+$("#dateto").val(), '_blank');
		// 		// window.location='./arenquiry/showpdf?debtorcode_from='+$('#debtorcode_from').val()+'&debtorcode_to='+$("#debtorcode_to").val()+'&datefr='+$("#datefr").val()+'&dateto='+$("#dateto").val();
		// 	}
		// },
		{
			text: "Generate Excel", click: function (){

				// $('button[classes=ARStatementDialog_xls_btn]').hide();
				// $('button[classes=ARStatementDialog_xls_btn]').parent().prepend(`<i class="fa fa-circle-o-notch fa-spin fa-fw"></i>`);

				window.open('./arenquiry/showExcel?debtorcode_from='+$('#debtorcode_from').val()+'&debtorcode_to='+$("#debtorcode_to").val()+'&datefr='+$("#datefr").val()+'&dateto='+$("#dateto").val(), '_self');
				// window.location='./arenquiry/showExcel?debtorcode_from='+$('#debtorcode_from').val()+'&debtorcode_to='+$("#debtorcode_to").val()+'&datefr='+$("#datefr").val()+'&dateto='+$("#dateto").val();
			},classes: "ARStatementDialog_xls_btn"
		},{
			text: "Cancel", click: function (){
				$(this).dialog('close');
			}
		}],
	});
	
	$('#ar_statement').click(function (){
		$("#ARStatementDialog").dialog("open");
	});
	
	var dialog_debtorFrom = new ordialog(
		'debtorcode_from', 'debtor.debtormast', '#formARStatement input[name = debtorcode_from]', 'errorField',
		{
			colModel: [
				{ label: 'Debtor Code', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Debtor Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus'],
				filterVal: ['session.compcode','ACTIVE']
			},
			ondblClickRow: function (){
			},
			gridComplete: function (obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title: "Select Debtor Code",
			open: function (){
				dialog_debtorFrom.urlParam.filterCol = ['recstatus','compcode'],
				dialog_debtorFrom.urlParam.filterVal = ['ACTIVE','session.compcode']
			},
			close: function (obj_){
			},
			after_check: function (data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('debtorcode_to',errorField) !== -1){
						errorField.splice($.inArray('debtorcode_to',errorField), 1);
					}
				}
			},
			justb4refresh: function (obj_){
				obj_.urlParam.searchCol2 = [];
				obj_.urlParam.searchVal2 = [];
			},
			justaftrefresh: function (obj_){
				$("#Dtext_"+obj_.unique).val('');
			}
		},'urlParam','radio','tab'
	);
	dialog_debtorFrom.makedialog(true);
	
	var dialog_debtorTo = new ordialog(
		'debtorcode_to', 'debtor.debtormast', '#formARStatement input[name = debtorcode_to]', errorField,
		{
			colModel: [
				{ label: 'Debtor Code', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Debtor Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus'],
				filterVal: ['session.compcode','ACTIVE']
			},
			ondblClickRow: function (){
			},
			gridComplete: function (obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title: "Select Debtor Code",
			open: function (){
				dialog_debtorTo.urlParam.filterCol = ['recstatus','compcode'],
				dialog_debtorTo.urlParam.filterVal = ['ACTIVE','session.compcode']
			},
			close: function (obj_){
			},
			after_check: function (data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('debtorcode_to',errorField) !== -1){
						errorField.splice($.inArray('debtorcode_to',errorField), 1);
					}
				}
			},
			justb4refresh: function (obj_){
				obj_.urlParam.searchCol2 = [];
				obj_.urlParam.searchVal2 = [];
			},
			justaftrefresh: function (obj_){
				$("#Dtext_"+obj_.unique).val('');
			}
		},'urlParam','radio','tab'
	);
	dialog_debtorTo.makedialog(true);
	////////////////////////////////AR STATEMENT LISTING ENDS////////////////////////////////
	
	function err_reroll(jqgridname,data_array){
		this.jqgridname = jqgridname;
		this.data_array = data_array;
		this.error = false;
		this.errormsg = 'asdsds';
		this.old_data;
		this.reroll = function (){
			$('#p_error').text(this.errormsg);
			var self = this;
			$(this.jqgridname+"_iladd").click();
			
			this.data_array.forEach(function (item,i){
				$(self.jqgridname+' input[name="'+item+'"]').val(self.old_data[item]);
			});
			this.error = false;
		}
	}
	
	function setjqgridHeight(data,grid){
		if(data.rows.length >= 6){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(500);
		}else if(data.rows.length>=3){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(300);
		}else{
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(200);
		}
	}
	
	function check_cust_rules(grid,data){
		var cust_val = true;
		Object.keys(data).every(function (v,i){
			cust_val = cust_rules('', $(grid).jqGrid('getGridParam','colNames')[i]);
			if(cust_val[0] == false){
				return false;
			}return true
		});
		return cust_val;
	}
});

function calc_jq_height_onchange(jqgrid){
	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
	if(scrollHeight < 80){
		scrollHeight = 80;
	}else if(scrollHeight > 300){
		scrollHeight = 300;
	}
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight+30);
}

function getdata(mode,idno){
	switch(mode){
		case 'RC':
			populateform_rc(idno);
			break;
		case 'RF':
			populateform_rf(idno);
			break;
	}
}

// RC
var dialog_payercode = new ordialog(
	'payercode', 'debtor.debtormast', '#dbacthdr_payercode', 'errorField',
	{
		colModel: [
			{ label: 'Debtor Code', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
			{ label: 'Debtor Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			{ label: 'debtortype', name: 'debtortype', hidden: true },
			{ label: 'actdebccode', name: 'actdebccode', hidden: true },
			{ label: 'actdebglacc', name: 'actdebglacc', hidden: true },
		],
		urlParam: {
			filterCol: ['compcode','recstatus'],
			filterVal: ['session.compcode','ACTIVE']
		},
		ondblClickRow: function (){
			let data = selrowData('#'+dialog_payercode.gridname);
			// $('#apacthdr_actdate').focus();
			$('#dbacthdr_payername').val(data.name);
			$('#dbacthdr_debtortype').val(data.debtortype);
		},
		gridComplete: function (obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				// $('#apacthdr_actdate').focus();
			}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
				$('#'+obj.dialogname).dialog('close');
			}
		}
	},{
		title: "Select Payer code",
		open: function (){
			dialog_payercode.urlParam.filterCol = ['recstatus','compcode'],
			dialog_payercode.urlParam.filterVal = ['ACTIVE','session.compcode']
		},
		close: function (){
			let data = selrowData('#'+dialog_payercode.gridname);
			get_debtorcode_outamount(data.debtorcode);
			$('#dbacthdr_remark').focus();
		}
	},'urlParam','radio','tab'
);
dialog_payercode.makedialog(true);

function populateform_rc(idno){
	var param = {
		action: 'populate_rc',
		url: './arenquiry/table',
		field: ['dbacthdr_compcode','dbacthdr_auditno','dbacthdr_lineno_','dbacthdr_billdebtor','dbacthdr_conversion','dbacthdr_hdrtype','dbacthdr_currency','dbacthdr_tillcode','dbacthdr_tillno','dbacthdr_debtortype','dbacthdr_adddate','dbacthdr_PymtDescription','dbacthdr_recptno','dbacthdr_entrydate','dbacthdr_entrytime','dbacthdr_entryuser','dbacthdr_payercode','dbacthdr_payername','dbacthdr_mrn','dbacthdr_episno','dbacthdr_remark','dbacthdr_authno','dbacthdr_epistype','dbacthdr_cbflag','dbacthdr_reference','dbacthdr_paymode','dbacthdr_amount','dbacthdr_outamount','dbacthdr_source','dbacthdr_trantype','dbacthdr_recstatus','dbacthdr_bankcharges','dbacthdr_expdate','dbacthdr_rate','dbacthdr_unit','dbacthdr_invno','dbacthdr_paytype','dbacthdr_RCCASHbalance','dbacthdr_RCFinalbalance','dbacthdr_RCOSbalance','dbacthdr_idno','paycard_description','paybank_description'],
		idno: idno,
	}
	
	$.get(param.url+"?"+$.param(param), function (data){
		
	},'json').done(function (data){
		if(!$.isEmptyObject(data.rows)){
			$.each(data.rows, function (index, value){
				var input = $("#dialogForm_RC [name='"+index+"']");
				if(input.is("[type=radio]")){
					$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
				}else{
					input.val(value);
				}
			});
			resetpill();
			$("#dialogForm_RC .nav-tabs a[form='"+data.rows.dbacthdr_paytype.toLowerCase()+"']").tab('show');
			dialog_payercode.check('errorField');
			disabledPill();
		}
	});
}

// RF
var dialog_payercodeRF = new ordialog(
	'payercodeRF', 'debtor.debtormast', '#formdata_RF input[name = dbacthdr_payercode]', 'errorField',
	{
		colModel: [
			{ label: 'Debtor Code', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
			{ label: 'Debtor Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			{ label: 'debtortype', name: 'debtortype', hidden: true },
			{ label: 'actdebccode', name: 'actdebccode', hidden: true },
			{ label: 'actdebglacc', name: 'actdebglacc', hidden: true },
		],
		urlParam: {
			filterCol: ['compcode','recstatus'],
			filterVal: ['session.compcode','ACTIVE']
		},
		ondblClickRow:function (){
			let data = selrowData('#'+dialog_payercodeRF.gridname);
			// $('#apacthdr_actdate').focus();
			$('#dbacthdr_payername').val(data.name);
			$('#dbacthdr_debtortype').val(data.debtortype);
		},
		gridComplete: function (obj){
			var gridname = '#'+obj.gridname;
			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
				$(gridname+' tr#1').click();
				$(gridname+' tr#1').dblclick();
				// $('#apacthdr_actdate').focus();
			}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
				$('#'+obj.dialogname).dialog('close');
			}
		}
	},{
		title: "Select Payer code",
		open: function (){
			dialog_payercodeRF.urlParam.filterCol = ['recstatus','compcode'],
			dialog_payercodeRF.urlParam.filterVal = ['ACTIVE','session.compcode']
		},
		close: function (){
			let data = selrowData('#'+dialog_payercodeRF.gridname);
			get_debtorcode_outamount(data.debtorcode);
			// $('#dbacthdr_remark').focus();
		}
	},
	'urlParam','radio','tab'
);
dialog_payercodeRF.makedialog();

// var dialog_payercodeRF = new ordialog(
// 	'payercodeRF', 'debtor.debtormast', '#formdata_RF input[name = dbacthdr_payercode]', 'errorField',
// 	{
// 		colModel: [
// 			{ label: 'Debtor Code', name: 'debtorcode', width: 200, classes: 'pointer' },
// 			{ label: 'Debtor Name', name: 'name', width: 400, classes: 'pointer' },
// 			{ label: 'debtortype', name: 'debtortype', hidden: true },
// 			{ label: 'actdebccode', name: 'actdebccode', hidden: true },
// 			{ label: 'actdebglacc', name: 'actdebglacc', hidden: true },
// 		],
// 		urlParam: {
// 			filterCol: ['compcode','recstatus'],
// 			filterVal: ['session.compcode','ACTIVE']
// 		},
// 	},{
// 		title: "Select Payer code",
// 		open: function (){
// 			dialog_payercodeRF.urlParam.filterCol = ['recstatus','compcode'],
// 			dialog_payercodeRF.urlParam.filterVal = ['ACTIVE','session.compcode']
// 		},
// 		close: function (){
// 			let data = selrowData('#'+dialog_payercodeRF.gridname);
// 			get_debtorcode_outamountRF(data.debtorcode);
// 		}
// 	},'urlParam','radio','tab'
// );
// dialog_payercodeRF.makedialog();

function populateform_rf(idno){
	var param = {
		action: 'populate_rf',
		url: './arenquiry/table',
		field: ['dbacthdr_compcode','dbacthdr_auditno','dbacthdr_lineno_','dbacthdr_billdebtor','dbacthdr_conversion','dbacthdr_hdrtype','dbacthdr_currency','dbacthdr_tillcode','dbacthdr_tillno','dbacthdr_debtortype','dbacthdr_adddate','dbacthdr_PymtDescription','dbacthdr_recptno','dbacthdr_entrydate','dbacthdr_entrytime','dbacthdr_entryuser','dbacthdr_payercode','dbacthdr_payername','dbacthdr_mrn','dbacthdr_episno','dbacthdr_remark','dbacthdr_authno','dbacthdr_epistype','dbacthdr_cbflag','dbacthdr_reference','dbacthdr_paymode','dbacthdr_amount','dbacthdr_outamount','dbacthdr_source','dbacthdr_trantype','dbacthdr_recstatus','dbacthdr_bankcharges','dbacthdr_expdate','dbacthdr_rate','dbacthdr_unit','dbacthdr_invno','dbacthdr_paytype','dbacthdr_RCCASHbalance','dbacthdr_RCFinalbalance','dbacthdr_RCOSbalance','dbacthdr_idno','paycard_description','paybank_description'],
		idno: idno
	}
	
	$.get(param.url+"?"+$.param(param), function (data){
		
	},'json').done(function (data){
		if(!$.isEmptyObject(data.rows)){
			$.each(data.rows, function (index, value){
				var input = $("#dialogForm_RF [name='"+index+"']");
				if(input.is("[type=radio]")){
					$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
				}else{
					input.val(value);
				}
			});
			resetpill();
			$("#dialogForm_RF .nav-tabs a[form='"+data.rows.dbacthdr_paytype.toLowerCase()+"']").tab('show');
			dialog_payercodeRF.check('errorField');
			disabledPill();
		}
	});
}

function resetpill(){
	$('#dialogForm_RC ul.nav-tabs li').removeClass('active');
	$('#dialogForm_RC ul.nav-tabs li a').attr('aria-expanded',false);
	
	$('#dialogForm_RF ul.nav-tabs li').removeClass('active');
	$('#dialogForm_RF ul.nav-tabs li a').attr('aria-expanded',false);
}

function disabledPill(){
	$('#dialogForm_RC .nav li').not('.active').addClass('disabled');
	$('#dialogForm_RC .nav li').not('.active').find('a').removeAttr("data-toggle");
	$('#dialogForm_RC .nav li').not('.active').hide();
	
	$('#dialogForm_RF .nav li').not('.active').addClass('disabled');
	$('#dialogForm_RF .nav li').not('.active').find('a').removeAttr("data-toggle");
	$('#dialogForm_RF .nav li').not('.active').hide();
}

function enabledPill(){
	$('#dialogForm_RC .nav li').removeClass('disabled');
	$('#dialogForm_RC .nav li').find('a').attr("data-toggle","tab");
	$('#dialogForm_RC .nav li').show();
	
	$('#dialogForm_RF .nav li').removeClass('disabled');
	$('#dialogForm_RF .nav li').find('a').attr("data-toggle","tab");
	$('#dialogForm_RF .nav li').show();
}

function init_jq2(oper){
	// if(oper != 'add'){
	// 	var unallocated = selrowData('#jqGrid').unallocated;
	// 	if(unallocated == 'true'){
	// 		$("#formdata_CN select[name='db_unallocated']").val('0');
	// 	}else{
	// 		$("#formdata_CN select[name='db_unallocated']").val('1');
	// 	}
	// }
	
	if(($("#formdata_CN select[name='db_unallocated']").find(":selected").text() == 'Credit Note')){
		// $('#save').hide();
		$('#grid_alloc').show();
		$('#grid_dtl').show();
		$('#jqGridPager2_Alloc').hide();
		$("#jqGrid2_Alloc").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_Alloc_c")[0].offsetWidth-$("#jqGrid2_Alloc_c")[0].offsetLeft-28));
		$("#jqGrid2_CN").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_CN_c")[0].offsetWidth-$("#jqGrid2_CN_c")[0].offsetLeft));
	}else if(($("#formdata_CN select[name='db_unallocated']").find(":selected").text() == 'Credit Note Unallocated')){
		// $('#save').hide();
		$('#grid_alloc').hide();
		$('#grid_dtl').show();
		$('#jqGridPager2_Alloc').hide();
		// $("#jqGrid2_Alloc input[name='allocamount']").attr('readonly','readonly');
	}
}

//////////////////////////////////formatter checkdetail//////////////////////////////////
function showdetail(cellvalue, options, rowObject){
	var field, table, case_;
	switch(options.colModel.name){
		// jqgrid depan
		case 'db_debtorcode': field = ['debtorcode','name'];table = "debtor.debtormast";case_ = 'db_debtorcode';break;
		case 'db_mrn': field = ['MRN','name'];table = "hisdb.pat_mast";case_ = 'db_mrn';break;
		case 'db_deptcode': field = ['deptcode','description'];table = "sysdb.department";case_ = 'db_deptcode';break;
		
		// jqGridAlloc
		case 'debtorcode': field = ['debtorcode','name'];table = "debtor.debtormast";case_ = 'debtorcode';break;
		case 'payercode': field = ['debtorcode','name'];table = "debtor.debtormast";case_ = 'payercode';break;
		case 'paymode': field = ['paymode','description'];table = "debtor.paymode";case_ = 'paymode';break;
		case 'mrn': field = ['MRN','name'];table = "hisdb.pat_mast";case_ = 'mrn';break;
		
		// CN
		case 'deptcode': field = ['deptcode','description'];table = "sysdb.department";case_ = 'Department CN';break;
		case 'category': field = ['catcode','description'];table = "material.category";case_ = 'Category CN';break;
		case 'GSTCode': field = ['taxcode','description'];table = "hisdb.taxmast";case_ = 'GST Code CN';break;
		
		// DN
		case 'deptcode': field = ['deptcode','description'];table = "sysdb.department";case_ = 'Department DN';break;
		case 'category': field = ['catcode','description'];table = "material.category";case_ = 'Category DN';break;
		case 'GSTCode': field = ['taxcode','description'];table = "hisdb.taxmast";case_ = 'GST Code DN';break;
		
		// IN
		case 'chggroup': 
				if(cellvalue.length <= 3){
					field = ['grpcode','description'];table = "hisdb.chggroup";case_ = 'chggroup';break;
				}else{
					field = ['chgcode','description'];table = "hisdb.chgmast";case_ = 'chggroup';break;
				}
		case 'uom': field = ['uomcode','description'];table = "material.uom";case_ = 'uom';break;
		case 'taxcode': field = ['taxcode','description'];table = "hisdb.taxmast";case_ = 'taxcode';break;
		
		// RC RF
		case 'dbacthdr_payercode': field = ['debtorcode','name'];table = "debtor.debtormast";case_ = 'dbacthdr_payercode';break;
		case 'dbacthdr_trantype': field = ['trantype','description'];table = "sysdb.sysparam";case_ = 'dbacthdr_trantype';break;
		
		// tracking
		case 'trxcode': return cellvalue;

		//DF
		case 'chgcode': field = ['chgcode','description'];table = "hisdb.chgmast";case_ = 'chgmast';break;
		case 'drcode': field = ['doctorcode','doctorname'];table = "hisdb.doctor";case_ = 'doctor';break;

	}
	var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
	fdl.get_array('arenquiry',options,param,case_,cellvalue);
	if(cellvalue == null)cellvalue = " ";
	return cellvalue;
}

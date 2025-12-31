$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {

	/////////////////////////////////////////validation//////////////////////////
	$.validate({
		decimalSeparator : ',',
		modules : 'sanitize',
		language : {
			requiredFields: 'Please Enter Value'
		},
	});

	var errorField=[];
	conf = {
		onValidate : function($form) {
			if(errorField.length>0){
				show_errors(errorField,'#formdata');
				return [{
					element : $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
					message : ''
				}];
			}
		},
	};

	var fdl = new faster_detail_load();
	var tabform="#f_tab-cash";
	checkifuserlogin();

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
				$("#formdata input[name='dbacthdr_drcostcode']").val(data.rows[0].ccode);
				$("#formdata input[name='dbacthdr_dracc']").val(data.rows[0].glaccno);
		});
	}

	function setDateToNow(){
		$('input[name=dbacthdr_entrydate]').val(moment().format('YYYY-MM-DD'));
	}

	var mycurrency =new currencymode(['input[name=dbacthdr_amount]','input[name=dbacthdr_outamount]','#f_tab-cash input[name=dbacthdr_RCCASHbalance]','input[name=dbacthdr_RCFinalbalance]','input[name=dbacthdr_bankcharges]']);
	
	function disabledPill(){
		$('.nav li').not('.active').addClass('disabled');
		$('.nav li').not('.active').find('a').removeAttr("data-toggle");
		$('.nav li').not('.active').hide();
	}

	function enabledPill(){
		$('.nav li').removeClass('disabled');
		$('.nav li').find('a').attr("data-toggle","tab");
		$('.nav li').show();
	}

	function amountchgOn(){
		$("input[name='dbacthdr_amount']").on('blur',amountFunction);
	}

	function amountchgOff(){
		$("input[name='dbacthdr_amount']").off('blur',amountFunction);
	}

	function amountFunction(event){
		let outamount = $(event.currentTarget).val();
		myallocation.outamt = outamount;
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
				urlParamAllo.payercode = data.debtorcode;
				myallocation.renewAllo(0);
				refreshGrid("#gridAllo",urlParamAllo);
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
				// $('#dbacthdr_remark').focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_payercode.makedialog();

	/////////////  PAYMODE //////////////////Bank Paytype/////////////////////////////////
		var urlParam2={
			action:'get_table_default',
			url: 'util/get_table_default',
			field:'',
			table_name:'debtor.paymode',
			table_id:'paymode',
			filterCol:['source','paytype','compcode'],
			filterVal:['AR','BANK','session.compcode'],
		}

		var urlParam_bank={
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
				{label: 'Pay Mode', name: 'paymode', width: 60, key:true},
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
			loadComplete:function(data){
				if(oper!='add'){
					$("#g_paymodebank").jqGrid('setSelection', selrowData('#jqGrid').dbacthdr_paymode);
				}
			},
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

		var urlParam_card={
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
				{label: 'Pay Mode', name: 'paymode', width: 60, key:true},
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
			loadComplete:function(data){
				if(oper!='add'){
					$("#g_paymodecard").jqGrid('setSelection', selrowData('#jqGrid').dbacthdr_paymode);
				}
			},
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

	////////////////////////////////////start dialog//////////////////////////////////////

	function saveFormdata_refund(grid,dialog,form,oper,saveParam,urlParam,obj,callback,uppercase=true){
		var myallocation_obj={
			allo:myallocation.arrayAllo
		}

		var formname = $("a[aria-expanded='true']").attr('form')

		var paymentform =  $( formname ).serializeArray();

		$('.ui-dialog-buttonset button[role=button]').prop('disabled',true);
		saveParam.oper=oper;

		let serializedForm = trimmall(form,uppercase);
		$.post( saveParam.url+'?'+$.param(saveParam), serializedForm+'&'+$.param(paymentform)+'&'+$.param(myallocation_obj) , function( data ) {
			
		}).fail(function(data) {
			errorText(dialog.substr(1),data.responseText);
			$('.ui-dialog-buttonset button[role=burefund_allo_tabletton]').prop('disabled',false);

			$('button[classes=allocateDialog_save_btn]').show();
		}).success(function(data){
			if(grid!=null){
				refreshGrid(grid,urlParam,oper);
				$('.ui-dialog-buttonset button[role=button]').prop('disabled',false);
				$(dialog).dialog('close');
				if (callback !== undefined) {
					callback();
				}
			}
			$('button[classes=allocateDialog_save_btn]').show();
		});
	}

	var butt1=[{
		text: "Save",click: function() {
			$('button[classes=allocateDialog_save_btn]').hide();
			mycurrency.formatOff();
			mycurrency.check0value(errorField);
			if( $('#formdata').isValid({requiredFields: ''}, conf, true) && $(tabform).isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata_refund("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
			}else{
				mycurrency.formatOn();
			}
		},classes: "allocateDialog_save_btn"
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
			$("#gridAllo").jqGrid ('setGridWidth', $("#gridAllo_c")[0].clientWidth);
			parent_close_disabled(true);
			amountchgOn();
			urlParamAllo.oper=oper
			urlParamAllo.auditno=selrowData('#jqGrid').dbacthdr_auditno;
			switch(oper) {
				case 'add':
					mycurrency.formatOnBlur();
					$('#dbacthdr_paytype').val(tabform);
					$( this ).dialog( "option", "title", "Add" );
					enableForm('#formdata');
					enableForm('.tab-content');
					rdonly('#formdata');
					rdonly(tabform);
					break;
				case 'view':
					mycurrency.formatOn();
					$( this ).dialog( "option", "title", "View" );
					disableForm('#formdata');
					disableForm('.tab-content');
					rdonly('#formdata');
					disableForm(selrowData('#jqGrid').dbacthdr_paytype);
					$(this).dialog("option", "buttons",butt2);

					switch(selrowData('#jqGrid').dbacthdr_paytype.toUpperCase()) {
						case '#F_TAB-CARD':
							urlParam_card.filterVal[3]=selrowData('#jqGrid').dbacthdr_paymode;
							refreshGrid("#g_paymodecard",urlParam_card);
							break;
						case '#F_TAB-DEBIT':
							urlParam_bank.filterVal[3]=selrowData('#jqGrid').dbacthdr_paymode;
							refreshGrid("#g_paymodebank",urlParam_bank);
							break;
					}
					urlParamAllo.payercode=selrowData('#jqGrid').dbacthdr_payercode;
					refreshGrid("#gridAllo",urlParamAllo);
			}
			if(oper!='view'){
				dialog_payercode.on();
				myallocation.renewAllo(0);
			}
			if(oper!='add'){
				dialog_payercode.check(errorField);
				showingForCash(selrowData("#jqGrid").dbacthdr_amount,selrowData("#jqGrid").dbacthdr_outamount,selrowData("#jqGrid").dbacthdr_RCCASHbalance,selrowData("#jqGrid").dbacthdr_RCFinalbalance,selrowData("#jqGrid").dbacthdr_paytype);
			}

			$('.nav-tabs a').on('shown.bs.tab', function(e){
				tabform=$(this).attr('form');
				rdonly(tabform);
				$('#dbacthdr_paytype').val(tabform);
				switch(tabform) {
					case '#f_tab-cash':
						getcr('CASH');
						break;
					case '#f_tab-card':
						if(oper=="view"){
							urlParam_card.filterVal[3]=selrowData('#jqGrid').dbacthdr_paymode;
							refreshGrid("#g_paymodecard",urlParam_card);
						}else{
							refreshGrid("#g_paymodecard",urlParam3);
						}
						break;
					case '#f_tab-cheque':
						getcr('cheque');
						break;
					case '#f_tab-debit':
						if(oper=="view"){
							urlParam_bank.filterVal[3]=selrowData('#jqGrid').dbacthdr_paymode;
							refreshGrid("#g_paymodebank",urlParam_bank);
						}else{
							refreshGrid("#g_paymodebank",urlParam2);
						}
						break;
				}
				$("#g_paymodecard").jqGrid ('setGridWidth', $("#g_paymodecard_c")[0].clientWidth);
				$("#g_paymodebank").jqGrid ('setGridWidth', $("#g_paymodebank_c")[0].clientWidth);
			});
		},
		close: function( event, ui ) {
			amountchgOff();
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			emptyFormdata(errorField, "#f_tab-cash");
			emptyFormdata(errorField, "#f_tab-card");
			emptyFormdata(errorField, "#f_tab-cheque");
			emptyFormdata(errorField, "#f_tab-debit");
			$('.alert').detach();
			$("#formdata a").off();
			$("#refresh_jqGrid").click();
			refreshGrid('#gridAllo',urlParamAllo,'kosongkan');
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
		url: './refund/table',
		field:'',
		fixPost: true
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
			{label: 'compcode', name: 'dbacthdr_compcode', width: 90, hidden: true },
			{label: 'Audit No', name: 'dbacthdr_auditno', width: 30 },
			{label: 'lineno_', name: 'dbacthdr_lineno_', width: 30, hidden: true},
			{label: 'source', name: 'dbacthdr_source', hidden: true, checked:true},
			{label: 'Trantype', name: 'dbacthdr_trantype', width: 45, formatter: showdetail, unformat:un_showdetail},
			{label: 'Type', name: 'dbacthdr_PymtDescription', classes: 'wrap', width: 50, hidden: true},
			{label: 'MRN', name: 'dbacthdr_mrn',align:'right', width: 30, formatter: padzero, unformat: unpadzero}, //tunjuk
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
			{ label: 'Payment Mode', name: 'dbacthdr_paymode', width: 70, classes: 'wrap text-uppercase', formatter: showdetail, unformat:un_showdetail },	//tunjuk
			{label: 'Amount', name: 'dbacthdr_amount', width: 60,align:'right',formatter:'currency',formatoptions:{prefix: ""} }, //tunjuk
			{label: 'O/S Amount', name: 'dbacthdr_outamount', width: 60,align:'right',formatter:'currency',formatoptions:{prefix: ""}, hidden:true }, 
			{label: 'bankchg', name: 'dbacthdr_bankcharges', hidden: true},
			{label: 'Expiry Date', name: 'dbacthdr_expdate', width: 50, align:'right', hidden:true,
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'m/Y'},
				editoptions: {
					dataInit: function (element) {
						$(element).datepicker({
							id: 'expdate_datePicker',
							dateFormat: 'MM/YYYY',
							min: now,
							max: until,
							changeMonth: true,
							changeYear: true,
						});
					}
				}
			},			
			{label: 'rate', name: 'dbacthdr_rate', hidden: true},
			{label: 'units', name: 'dbacthdr_unit', hidden: true},
			{label: 'invno', name: 'dbacthdr_invno', hidden: true},
			{label: 'paytype', name: 'dbacthdr_paytype', hidden: true},
			{label: 'RCcashbalance', name: 'dbacthdr_RCCASHbalance', hidden: true},
			{label: 'RCFinalbalance', name: 'dbacthdr_RCFinalbalance', hidden: true},
			{label: 'Status', name: 'dbacthdr_recstatus',width: 50}, //tunjuk
			{label: 'idno', name: 'dbacthdr_idno', hidden: true},
			{label: 'paycard_description', name: 'paycard_description', hidden: true },
			{label: 'paybank_description', name: 'paybank_description', hidden: true },
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
			// urlParamAllo.payercode = selrowData("#jqGrid").dbacthdr_payercode;
			// refreshGrid("#gridAllo",urlParamAllo);
			// $("#gridAllo input[name='tick']").hide();
			$("#pdfgen1").attr('href','./receipt/showpdf?auditno='+selrowData("#jqGrid").dbacthdr_idno);
		},
		gridComplete: function(){
			fdl.set_array().reset();
			if(oper == 'add' || oper == null || $("#jqGrid").jqGrid('getGridParam', 'selrow') == null){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
			enabledPill();
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
			
			oper='view';
			$('#dbacthdr_recptno').show();
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			var selform=selrowData('#jqGrid').dbacthdr_paytype;
			resetpill();
			if(selform!=''){
				$(".nav-tabs a[form='"+selform.toLowerCase()+"']").tab('show');
				disabledPill();
				populateFormdata("#jqGrid","",selform.toLowerCase(),selRowId,'view', ['dbacthdr_expdate']);
			}else{
				$(".nav-tabs a[form='#f_tab-cash']").tab('show');
			}
			
			var expdate = selrowData("#jqGrid").dbacthdr_expdate;
			var datearray = expdate.split("/");
			
			var newexpdate = datearray[1] + '-' + datearray[0];
			$("#dbacthdr_expdate").val(newexpdate);

			populateFormdata("#jqGrid","","#formdata",selRowId,'view', ['dbacthdr_expdate']);
			$("#dialogForm").dialog( "open" );
			// $("#g_paycard_c, #g_paybank_c").show();
			// $("#g_paymodecard_c, #g_paymodebank_c").hide();
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-plus", 
		title:"Add Selected Row", 
		onClickButton: function(){
			oper='add';
			resetpill();
			$(".nav-tabs a[form='#f_tab-cash']").tab('show');
			enabledPill();
			$( "#dialogForm" ).dialog( "open" );
			// $("#g_paymodecard_c, #g_paymodebank_c").show();
			// $("#g_paycard_c, #g_paybank_c").hide();

		},
	});
	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');
	searchClick2('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['patmast_name','dbacthdr_idno','dbacthdr_amount']);

	function get_debtorcode_outamount(payercode){
		var param={
			url: './refund/table',
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
			case 'dbacthdr_paymode':field=['paymode','description'];table="debtor.paymode";case_='dbacthdr_paymode';break;
	
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

	///allocation///
	var urlParamAllo={
		action:'refund_allo_table',
		oper: 'add',
		auditno: 0,
		url: 'refund/table',
		payercode:''
	}
	
	$("#gridAllo").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'idno', width: 40, hidden: true, key:true}, 
			{ label: 'Audit No', name: 'auditno', width: 40, hidden: true},
			{ label: 'Trantype', name: 'trantype', width: 40},
			{ label: 'Receipt No', name: 'recptno', width: 40},
			{ label: 'Document Date', name: 'entrydate', width: 50},
			{ label: 'MRN', name: 'mrn', width: 50, formatter: padzero, unformat: unpadzero },
			{ label: 'EpisNo', name: 'episno', width: 50},
			{ label: 'Src', name: 'source', width: 20, hidden: true}, 
			{ label: 'Type', name: 'trantype', width: 20 , hidden: true},
			{ label: 'Line No', name: 'lineno_', width: 20 , hidden: true},
			// { label: 'Batchno', name: 'NULL', width: 40},
			{ label: 'Amount', name: 'amount',formatter:'currency', width: 50},
			{ label: 'O/S Amount', name: 'outamount',formatter:'currency', width: 50},
			{ label: ' ', name: 'tick', width: 20, editable: true, edittype:"checkbox", align:'center', formatter: checkbox_jqg2},
			{ label: 'Amount Paid', name: 'amtpaid', width: 50, editable: true},
			{ label: 'Balance', name: 'amtbal', width: 50,formatter:'currency',formatoptions:{prefix: ""} },
		],
		autowidth: true,
		viewrecords: true,
		multiSort: true,
		height: 400,
		scroll:true,
		rowNum: 9,
		pager: "#pagerAllo",
		onSelectRow: function(rowid){
		},
		onPaging: function(button){
		},
		gridComplete: function(rowid){
			startEdit();
			$("#gridAllo_c input[type='checkbox']").on('click',function(){
				var idno = $(this).attr("rowid");
				var rowdata = $("#gridAllo").jqGrid ('getRowData', idno);
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
			$("#gridAllo_c input[type='text'][rowid]").on('click',function(){
				var idno = $(this).attr("rowid");
				if(!myallocation.alloInArray(idno)){
					myallocation.addAllo(idno,' ',0);
				}
			});

			delay(function(){
	        	//$("#alloText").focus();//AlloTotal
	        	myallocation.retickallotogrid();
			}, 100 );
		},
	});

	function checkbox_jqg2(cellvalue, options, rowObject){
		if(options.gid == "gridAllo"){
			return '';
		}else{
			if(parseFloat(rowObject.amtpaid) > 0){
				return '';
			}else{
				return `<input class='checkbox_jqg2' type="checkbox" name="checkbox" data-rowid="`+options.rowId+`">`;	
			}
		}
	}

	////////////////////////////////////////////padzero////////////////////////////////////////////
	function padzero(cellvalue, options, rowObject){
		let padzero = 7, str="";
		while(padzero>0){
			str=str.concat("0");
			padzero--;
		}
		return pad(str, cellvalue, true);
	}
	
	function unpadzero(cellvalue, options, rowObject){
		return cellvalue.substring(cellvalue.search(/[1-9]/));
	}
	
	AlloSearch("#gridAllo",urlParamAllo);
	function AlloSearch(grid,urlParam){
		$("#alloText").on( "keyup", function() {
			delay(function(){
				search(grid,$("#alloText").val(),$("#alloCol").val(),urlParam);
			}, 500 );
		});

		$("#alloCol").on( "change", function() {
			search(grid,$("#alloText").val(),$("#alloCol").val(),urlParam);
		});
	}

	function startEdit() {
        var ids = $("#gridAllo").jqGrid('getDataIDs');

        for (var i = 0; i < ids.length; i++) {
        	if(oper=='add'){
	        	var entrydate = $("#gridAllo").jqGrid ('getRowData', ids[i]).entrydate;
	        	$("#gridAllo").jqGrid('setCell', ids[i], 'NULL', moment(entrydate).format("DD-MMM"));
	            $("#gridAllo").jqGrid('editRow',ids[i]);
        	}
        }
    };

	$("#gridAllo").jqGrid('navGrid','#pagerAllo',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#gridAllo",urlParamAllo);
		},
	})


});

var myallocation = new Allocation();
var allocurrency = new currencymode(["input[name=dbacthdr_amount]"]);

function Allocation(){
	this.arrayAllo=[];
	this.alloBalance=0;
	this.alloTotal=0;
	this.outamt=0;

	this.renewAllo = function(os){
		this.arrayAllo.length = 0;
		this.alloTotal=0;
		this.alloBalance=parseFloat(os);
		this.outamt=parseFloat(os);

		this.updateAlloField();
	}
	this.addAllo = function(idno,paid,bal){
		var obj=getlAlloFromGrid(idno);
		obj.amtpaid = paid;
		obj.amtbal = bal;
		var fieldID="#"+idno+"_amtpaid";
		var self=this;

		this.arrayAllo.push({idno:idno,obj:obj});
		
		$(fieldID).on('change',[idno,self.arrayAllo],onchangeField);

		this.updateAlloField();
	}
	this.deleteAllo = function(idno){
		var self=this;
		$.each(self.arrayAllo, function( index, obj ) {
			if(obj.idno==idno){
				self.arrayAllo.splice(index, 1);
				return false;
			}
		});
	}
	this.alloInArray = function(idno){
		var retval=false;
		$.each(this.arrayAllo, function( index, obj ) {
			if(obj.idno==idno){
				retval=true;
				return false;//bila return false, skip .each terus pegi return retval
			}
		});
		return retval;
	}
	this.retickallotogrid = function(){
		var self=this;
		$.each(this.arrayAllo, function( index, obj ) {
			$("#"+obj.idno+"_amtpaid").on('change',[obj.idno,self.arrayAllo],onchangeField);
			if(obj.obj.amtpaid != " "){
				$("#"+obj.idno+"_amtpaid").val(obj.obj.amtpaid).removeClass( "error" ).addClass( "valid" );
				setbal(obj.idno,obj.obj.amtbal);
			}
		});
	}
	this.updateAlloField = function(){
		var self=this;
		this.alloTotal = 0;
		$.each(this.arrayAllo, function( index, obj ) {
			if(obj.obj.amtpaid != " "){
				self.alloTotal += parseFloat(obj.obj.amtpaid);
			}
		});
		// this.alloBalance = this.outamt - this.alloTotal;

		$("input[name=dbacthdr_amount]").val(this.alloTotal);
		// $("#AlloBalance").val(this.alloBalance);
		// if(this.alloBalance<0){
		// 	$("#AlloBalance").addClass( "error" ).removeClass( "valid" );
		// 	alert("Balance cannot in negative values");
		// }else{
		// 	$("#AlloBalance").addClass( "valid" ).removeClass( "error" );
		// }
		// console.log(this.outamt);
		allocurrency.formatOn();
	}
}

function setbal(idno,balance){
	$("#gridAllo").jqGrid('setCell', idno, 'amtbal', balance);
}

function updateAllo(idno,amtpaid,arrayAllo){
	$.each(arrayAllo, function( index, obj ) {
		if(obj.idno==idno){
			obj.obj.amtpaid=amtpaid;
			return false;//bila return false, skip .each terus pegi return retval
		}
	});
}

function getlAlloFromGrid(idno){
	var temp=$("#gridAllo").jqGrid ('getRowData', idno);
	return {idno:temp.idno,auditno:temp.auditno,amtbal:temp.amtbal,amtpaid:temp.amount};
}

function onchangeField(obj){
	var idno = obj.handleObj.data[0];
	var arrayAllo = obj.handleObj.data[1];
	var alloIndex = getIndex(arrayAllo,idno);
	var outamt = $("#gridAllo").jqGrid('getRowData', idno).outamount;
	var newamtpaid = parseFloat(obj.target.value);
	newamtpaid = isNaN(Number(newamtpaid)) ? 0 : parseFloat(obj.target.value);
	if(parseFloat(newamtpaid)>parseFloat(outamt)){
		alert("Amount paid exceed O/S amount");
		$("#"+idno+"_amtpaid").addClass( "error" ).removeClass( "valid" );
		obj.target.focus();
		return false;
	}
	$("#"+idno+"_amtpaid").removeClass( "error" ).addClass( "valid" );
	var balance = outamt - newamtpaid;

	obj.target.value = numeral(newamtpaid).format('0,0.00');;
	arrayAllo[alloIndex].obj.amtpaid = newamtpaid;
	arrayAllo[alloIndex].obj.amtbal = balance;
	setbal(idno,balance);

	myallocation.updateAlloField();
}

function getIndex(array,idno){
	var retval=0;
	$.each(array, function( index, obj ) {
		if(obj.idno==idno){
			retval=index;
			return false;//bila return false, skip .each terus pegi return retval
		}
	});
	return retval;
}

function calc_jq_height_onchange(jqgrid){
	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
	if(scrollHeight<80){
		scrollHeight = 80;
	}else if(scrollHeight>300){
		scrollHeight = 300;
	}
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight);
}	

function resetpill(){
	$('#dialogForm ul.nav-tabs li').removeClass('active');
	$('#dialogForm ul.nav-tabs li a').attr('aria-expanded',false);
}


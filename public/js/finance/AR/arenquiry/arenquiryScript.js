$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	$("body").show();

	var tabform="#f_tab-cash";
	/////////////////////////////////////////validation//////////////////////////
	$.validate({
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

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency =new currencymode(['#db_outamount', '#db_amount', "#formdata_DN input[name='db_amount']", "#formdata_CN input[name='db_amount']"]);
	var mycurrency2 =new currencymode(['#db_outamount', '#db_amount']);
	var fdl = new faster_detail_load();
	
	////////////////////////for handling amount based on trantype////////////////////////
	//RC
	function handleAmount(){
		if($("input:radio[name='optradio'][value='receipt']").is(':checked')){
			amountchgOn(true);
		}else if($("input:radio[name='optradio'][value='deposit']").is(':checked')){
			amountchgOff(true);
		}
	}

	function amountFunction(){
		if(tabform=='#f_tab-cash'){
			getCashBal();
			getOutBal(true);
		}else if(tabform=='#f_tab-card'||tabform=='#f_tab-cheque'||tabform=='#f_tab-forex'){
			getOutBal(false);
		}else if(tabform=='#f_tab-debit'){
			getOutBal(false,$(tabform+" input[name='dbacthdr_bankcharges']").val());
		}
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
	//end RC
	////////////////////////end handling amount based on trantype////////////////////////

	//RC
	function saveFormdata_receipt(grid,dialog,form,oper,saveParam,urlParam,obj,callback,uppercase=true){

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
			if( $('#formdata_RC').isValid({requiredFields: ''}, conf, true) && $(tabform).isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata_receipt("#jqGrid2_RC","#dialogForm_RC","#formdata_RC",oper,saveParam2_RC,urlParam2_RC);
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
				$("#formdata_RC input[name='dbacthdr_drcostcode']").val(data.rows[0].ccode);
				$("#formdata_RC input[name='dbacthdr_dracc']").val(data.rows[0].glaccno);
		});
	}
	
	function setDateToNow(){
		$('input[name=dbacthdr_entrydate]').val(moment().format('YYYY-MM-DD'));
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
				$('input[name="dbacthdr_outamount"]').val(data.outamount);
			}else{
				// alert('Payer doesnt have outstanding amount');
			}
		});
	}
	//end RC

	///////////////////////////////////////////start dialogForm///////////////////////////////////////////
	$("#dialogForm_CN")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				errorField.length=0;
				$("#jqGrid2_CN").jqGrid('setGridWidth', Math.floor($("#jqGrid2_CN_c")[0].offsetWidth - $("#jqGrid2_CN_c")[0].offsetLeft));
				$("#jqGrid2_Alloc").jqGrid('setGridWidth', Math.floor($("#jqGrid2_Alloc_c")[0].offsetWidth - $("#jqGrid2_Alloc_c")[0].offsetLeft));
				refreshGrid("#jqGrid2_CN",urlParam2_CN);
				mycurrency.formatOnBlur();
				disableForm('#formdata_CN');
				$("#pg_jqGridPager2 table").hide();
				$("#pg_jqGridPager2_Alloc table").hide();
				dialog_CustomerCN.check(errorField);
				dialog_paymodeCN.check(errorField);
				init_jq2('view');
			},
			close: function( event, ui ) {
			}
		});

	$("#dialogForm_DN")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
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
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_DN');
				$('.my-alert').detach();
				$("#formdata_DN a").off();
				$(".noti, .noti2 ol").empty();
				refreshGrid("#jqGrid2_DN",null,"kosongkan");
				errorField.length=0;
			},
		});	

	$("#dialogForm_IN")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
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
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_IN');
				$('.my-alert').detach();
				$("#formdata_IN a").off();
				$(".noti, .noti2 ol").empty();
				refreshGrid("#jqGrid2_IN",null,"kosongkan");
				errorField.length=0;
			},
		});

	$("#dialogForm_RC")
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
							refreshGrid("#g_paymodecard",urlParam3_rc);
							break;
						case state = '#f_tab-cheque':
							getcr('cheque');
							break;
						case state = '#f_tab-debit':
							refreshGrid("#g_paymodebank",urlParam2_rc);
							break;
						case state = '#f_tab-forex':
							refreshGrid("#g_forex",urlParam4_rc);
							break;
					}
					$("#g_paymodecard").jqGrid ('setGridWidth', $("#g_paymodecard_c")[0].clientWidth);
					$("#g_paymodebank").jqGrid ('setGridWidth', $("#g_paymodebank_c")[0].clientWidth);
					$("#g_forex").jqGrid ('setGridWidth', $("#g_forex_c")[0].clientWidth);
	
				});
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
						disableForm(selrowData('#jqGrid2_RC').dbacthdr_paytype);
						$(this).dialog("option", "buttons",butt2);
	
						switch(selrowData('#jqGrid2_RC').dbacthdr_paytype) {
							case state = '#f_tab-card':
								refreshGrid("#g_paymodecard",urlParam3_rc);
								break;
							case state = '#f_tab-debit':
								refreshGrid("#g_paymodebank",urlParam2_rc);
								break;
							case state = '#f_tab-forex':
								refreshGrid("#g_forex",urlParam4_rc);
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
					// dialog_logintillcode.check(errorField);
					// dialog_payercode.check(errorField);
					showingForCash(selrowData("#jqGrid2_RC").dbacthdr_amount,selrowData("#jqGrid2_RC").dbacthdr_outamount,selrowData("#jqGrid2_RC").dbacthdr_RCCASHbalance,selrowData("#jqGrid2_RC").dbacthdr_RCFinalbalance,selrowData("#jqGrid2_RC").dbacthdr_paytype);
				}
			},
			close: function( event, ui ) {
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
		url:'./arenquiry/table',
		//source:$('#db_source').val(),
		//trantype:$('#db_trantype').val(),
	}

	/////////////////////parameter for saving url///////////////////////////////////////////////////////

	/////////////////////////padzero/////////////////////////
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

	///////////////////////////////////////////////jqgrid///////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'compcode', name: 'db_compcode', hidden: true },
			{ label: 'Debtor Code', name: 'db_debtorcode', width: 35, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail },
			{ label: 'Payer Code', name: 'db_payercode', width: 20, hidden: true },
			{ label: 'Audit No', name: 'db_auditno', width: 8, align: 'right', classes: 'wrap', canSearch: true },
			{ label: 'Sector', name: 'db_unit', width: 10, hidden: true, classes: 'wrap' },
			{ label: 'PO No', name: 'db_ponum', width: 8, formatter: padzero5, unformat: unpadzero, hidden: true },
			{ label: 'Document No', name: 'db_recptno', width: 15, align: 'right' },
			{ label: 'Document Date', name: 'db_entrydate', width: 12, canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'Amount', name: 'db_amount', width: 12, classes: 'wrap', align: 'right', formatter:'currency' },
			{ label: 'Outamount', name: 'db_outamount', width: 12, classes: 'wrap', align: 'right', formatter:'currency' },
			{ label: 'Status', name: 'db_recstatus', width: 12 },
			{ label: 'source', name: 'db_source', width: 10, hidden: true },
			{ label: 'Trantype', name: 'db_trantype', width: 8, canSearch: true, },
			{ label: 'lineno_', name: 'db_lineno_', width: 10, hidden: true },
			{ label: 'db_orderno', name: 'db_orderno', width: 10, hidden: true },
			{ label: 'debtortype', name: 'db_debtortype', width: 20, hidden: true },
			{ label: 'billdebtor', name: 'db_billdebtor', width: 20, hidden: true },
			{ label: 'approvedby', name: 'db_approvedby', width: 20, hidden: true },
			{ label: 'MRN', name: 'db_mrn', width: 20, align: 'right', canSearch: true, classes: 'wrap text-uppercase', formatter: showdetail, unformat:un_showdetail },
			{ label: 'unit', name: 'db_unit', width: 10, hidden: true },
			{ label: 'termmode', name: 'db_termmode', width: 10, hidden: true },
			{ label: 'paytype', name: 'db_hdrtype', width: 10, hidden: true },
			{ label: 'db_posteddate', name: 'db_posteddate',hidden: true },
			{ label: 'Department', name: 'db_deptcode', width: 15, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail },
			{ label: 'Paymode', name: 'db_paymode', width: 25, classes: 'wrap text-uppercase', hidden: true },
			{ label: 'idno', name: 'db_idno', width: 10, hidden: true, key:true },
			{ label: 'adduser', name: 'db_adduser', width: 10, hidden: true },
			{ label: 'adddate', name: 'db_adddate', width: 10, hidden: true },
			{ label: 'upduser', name: 'db_upduser', width: 10, hidden: true },
			{ label: 'upddate', name: 'db_upddate', width: 10, hidden: true },
			{ label: 'Remark', name: 'db_remark', width: 20, classes: 'wrap', hidden: true },
			{ label: 'unallocated', name: 'unallocated', width: 50, classes: 'wrap', hidden:true },
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 900,
		height: 400,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			$('#jqGrid3_CN_c,#jqGrid3_DN_c,#jqGrid3_IN_c').hide();

			if(selrowData("#jqGrid").db_trantype=='CN'){	//CN
				urlParam2_CN.source = selrowData("#jqGrid").db_source;
				urlParam2_CN.trantype = selrowData("#jqGrid").db_trantype;
				urlParam2_CN.auditno = selrowData("#jqGrid").db_auditno;
				// urlParam2_CN.filterVal[1]=selrowData("#jqGrid").db_auditno;
				
				$('#jqGrid3_CN_c').show();
				refreshGrid("#jqGrid3_CN",urlParam2_CN);
			}else if(selrowData("#jqGrid").db_trantype=='DN'){	//DN
				urlParam2_DN.source = selrowData("#jqGrid").db_source;
				urlParam2_DN.trantype = selrowData("#jqGrid").db_trantype;
				urlParam2_DN.auditno = selrowData("#jqGrid").db_auditno;
				// urlParam2_DN.filterVal[1]=selrowData("#jqGrid").db_auditno;
				
				$('#jqGrid3_DN_c').show();
				refreshGrid("#jqGrid3_DN",urlParam2_DN);
			}else if(selrowData("#jqGrid").db_trantype=='IN'){	//IN
				urlParam2_IN.source = selrowData("#jqGrid").db_source;
				urlParam2_IN.trantype = selrowData("#jqGrid").db_trantype;
				urlParam2_IN.billno = selrowData("#jqGrid").db_auditno;
				urlParam2_IN.deptcode = selrowData("#jqGrid").db_deptcode;
				
				$('#jqGrid3_IN_c').show();
				refreshGrid("#jqGrid3_IN",urlParam2_IN);
			}else if(selrowData("#jqGrid").db_trantype=='RC'){	//RC
				// urlParam2_RC.source = selrowData("#jqGrid").db_source;
				// urlParam2_RC.trantype = selrowData("#jqGrid").db_trantype;
				// urlParam2_RC.billno = selrowData("#jqGrid").db_auditno;
				// urlParam2_RC.deptcode = selrowData("#jqGrid").db_deptcode;
			}
			if(rowid != null) {
				var rowData = $('#jqGrid').jqGrid('getRowData', rowid);
				refreshGrid('#jqGridAlloc', urlParamAlloc,'kosongkan');
				// $("#pg_jqGridPager3 table").hide();
				// $("#pg_jqGridPager2 table").show();
			}
			urlParamAlloc.idno=selrowData("#jqGrid").db_idno;
			urlParamAlloc.auditno=selrowData("#jqGrid").db_auditno;
			refreshGrid("#jqGridAlloc",urlParamAlloc);
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='View Selected Row']").click();
		},
		gridComplete: function () {
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
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid");
			calc_jq_height_onchange("jqGridAlloc");
		},
		
	});

	////////////////////// set label jqGrid right ///////////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-info-sign",
		title:"View Selected Row",  
		onClickButton: function(){
			oper='view';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			if(selrowData("#jqGrid").db_trantype=='CN'){ //CN
				populateFormdata("#jqGrid","#dialogForm_CN","#formdata_CN",selRowId,'view');
				refreshGrid("#jqGrid2_CN",urlParam2_CN,'add');

				urlParam2_Alloc.filterVal[1]=selrowData("#jqGrid").db_auditno;
				refreshGrid("#jqGrid2_Alloc",urlParam2_Alloc,'add');
			}else if(selrowData("#jqGrid").db_trantype=='DN'){ //DN
				populateFormdata("#jqGrid", "#dialogForm_DN", "#formdata_DN", selRowId, 'view', '');
				refreshGrid("#jqGrid2_DN",urlParam2_DN,'add');
			}else if(selrowData("#jqGrid").db_trantype=='IN'){ //IN
				populateFormdata("#jqGrid", "#dialogForm_IN", "#formdata_IN", selRowId, 'view', '');
				refreshGrid("#jqGrid2_IN",urlParam2_IN,'add');
			}else if(selrowData("#jqGrid").db_trantype=='RC'){ //RC
				populateFormdata("#jqGrid", "#dialogForm_RC", "#formdata_RC", selRowId, 'view', '');
				getdata('RC',selrowData("#jqGrid").db_idno);
				// refreshGrid("#jqGrid2_RC",urlParam2_RC,'add');
				refreshGrid("#sysparam",urlParam_sys);

				// refreshGrid("#g_paymodecard",urlParam3);
				// refreshGrid("#g_paymodebank",urlParam2);
				// refreshGrid("#g_forex",urlParam4);
				
				// populateFormdata("#jqGrid2_RC","","#formdata_RC",selRowId,'view');
				// $("#dialogForm_RC").dialog( "open" );
			}
		},
	});
	//////////////////////////////////////end grid/////////////////////////////////////////////////////////
	
	/////////////////////////////////////////////////Allocation/////////////////////////////////////////////////
	///////////////////////////////////////parameter for jqGridAlloc url///////////////////////////////////////
	var urlParamAlloc={
		action:'get_alloc',
		url:'./arenquiry/table',
		auditno:''
	};

	////////////////////////////////////////////////jqGridAlloc////////////////////////////////////////////////
	$("#jqGridAlloc").jqGrid({
		datatype: "local",
		editurl: "./arenquiry/form",
		colModel: [
			// { label: 'compcode', name: 'compcode', width: 20, hidden:true },
			// { label: 'lineno_', name: 'lineno_', width: 20, hidden:true },
			// { label: 'idno', name: 'idno', width: 20, hidden:true },
			{ label: 'System Auto No.', name: 'sysAutoNo', width: 50, classes: 'wrap' },
			{ label: 'Source', name: 'source', width: 10, classes: 'wrap', hidden: true },
			{ label: 'TT', name: 'trantype', width: 10, classes: 'wrap', hidden: true },
			{ label: 'Audit No', name: 'auditno', width: 10, classes: 'wrap',formatter: padzero, unformat: unpadzero, hidden: true },
			{ label: 'Debtor', name: 'debtorcode', width: 50, classes: 'wrap text-uppercase', formatter: showdetail, unformat:un_showdetail },
			{ label: 'Payer', name: 'payercode', width: 50, classes: 'wrap text-uppercase', formatter: showdetail, unformat:un_showdetail },
			{ label: 'Amount', name: 'amount', width: 40, classes: 'wrap', align: 'right', formatter:'currency' },
			{ label: 'Document No', name: 'recptno', width: 50, align: 'right' },
			{ label: 'Paymode', name: 'paymode', width: 50, classes: 'wrap text-uppercase', formatter: showdetail, unformat:un_showdetail },
			{ label: 'Alloc Date', name: 'allocdate', width: 50, formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'MRN', name: 'mrn', width: 50, align: 'right', classes: 'wrap text-uppercase', formatter: showdetail, unformat:un_showdetail },
			{ label: 'Episno', name: 'episno', width: 20, align: 'right' },
		],
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		pager: "#jqGridPagerAlloc",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGridAlloc");
			urlParamAlloc.idno=selrowData("#jqGrid").db_idno;
			
			refreshGrid("#jqGridAlloc",urlParamAlloc,'add');
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
	});
	jqgrid_label_align_right("#jqGridAlloc");

	$("#jqGridAlloc_panel").on("show.bs.collapse", function(){
		$("#jqGridAlloc").jqGrid ('setGridWidth', Math.floor($("#jqGridAlloc_c")[0].offsetWidth-$("#jqGridAlloc_c")[0].offsetLeft-18));
	});

	/////////////////////////////////////////parameter for jqgrid url/////////////////////////////////////////

	////////////////////////////////////////////////////CN////////////////////////////////////////////////////
	var urlParam2_CN = {
		action: 'get_table_dtl',
		url:'CreditNoteARDetail/table',
		source:'',
		trantype:'',
		auditno:'',
		// field:['dbactdtl.compcode','dbactdtl.source','dbactdtl.trantype','dbactdtl.auditno','dbactdtl.lineno_','dbactdtl.deptcode','dbactdtl.category','dbactdtl.document', 'dbactdtl.AmtB4GST', 'dbactdtl.GSTCode', 'dbactdtl.amount', 'dbactdtl.grnno', 'dbactdtl.amtslstax as tot_gst'],
		// table_name:['debtor.dbactdtl AS dbactdtl'],
		// table_id:'lineno_',
		// filterCol:['dbactdtl.compcode','dbactdtl.auditno', 'dbactdtl.recstatus','dbactdtl.source','dbactdtl.trantype'],
		// filterVal:['session.compcode', '', '<>.DELETE', 'PB', 'CN']
	};

	////////////////////////////////////////////////jqGrid2_CN////////////////////////////////////////////////
	$("#jqGrid2_CN").jqGrid({
		datatype: "local",
		editurl: "./CreditNoteARDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'AuditNo', name: 'auditno', hidden: true },
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true, editable:false },
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true, editable:false },
            { label: 'Department', name: 'deptcode', width: 150, classes: 'wrap', canSearch: true, editable: false,
                editrules:{required: true,custom:true, custom_func:cust_rules},
                formatter: showdetail,
                edittype:'custom',	editoptions:
                    {  
                        custom_element:deptcodeCNCustomEdit,
                        custom_value:galGridCustomValue 	
                    },
            },
            { label: 'Category', name: 'category', width: 250, edittype:'text', classes: 'wrap', hidden:true, editable: false,
                editrules:{required: true,custom:true, custom_func:cust_rules},
                formatter: showdetail,
                edittype:'custom',	editoptions:
                    {  
                        custom_element:categoryCNCustomEdit,
                        custom_value:galGridCustomValue 	
                    },
            },
			{ label: 'Document', name: 'document', width: 230, classes: 'wrap', hidden:true, editable: false },
			{ label: 'GST Code', name: 'GSTCode', width: 100, classes: 'wrap', editable: false,
				editrules:{required: true,custom:true, custom_func:cust_rules},
				formatter: showdetail,
				edittype:'custom',	editoptions:
					{
						custom_element:GSTCodeCNCustomEdit,
						custom_value:galGridCustomValue 	
					},
			},
			{ label: 'Amount Before GST', name: 'AmtB4GST', width: 90, classes: 'wrap', editable: false, align: "right", formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,} },
			{ label: 'Total Tax Amount', name: 'tot_gst', width: 90, align: 'right', classes: 'wrap', editable:false },
			{ label: 'Amount', name: 'amount', width: 90, classes: 'wrap', editable: false, align: "right", formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,} },
			{ label: 'rate', name: 'rate', width: 50, classes: 'wrap', hidden:true },
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
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid2_CN");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
		beforeSubmit: function (postdata, rowid) {
		}
	});

	////////////////////////////////////////////////jqGrid3_CN////////////////////////////////////////////////
	$("#jqGrid3_CN").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2_CN").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3_CN",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid3_CN");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		}
	});
	jqgrid_label_align_right("#jqGrid3_CN");

	$("#jqGrid3_CN_panel").on("show.bs.collapse", function(){
		$("#jqGrid3_CN").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_CN_c")[0].offsetWidth-$("#jqGrid3_CN_c")[0].offsetLeft-18));
	});

	////////////////////////////////////////////////parameter for jqGrid2_Alloc url////////////////////////////////////////////////
	var urlParam2_Alloc={
		action:'get_table_default',
		url:'util/get_table_default',
		field:['alloc.compcode','alloc.source','alloc.trantype','alloc.auditno','alloc.lineno_','alloc.debtorcode','alloc.allocdate','alloc.recptno','alloc.refamount','alloc.amount','alloc.balance','alloc.docsource','alloc.doctrantype','alloc.docauditno','alloc.refsource','alloc.reftrantype','alloc.refauditno','alloc.idno'],
		table_name:['debtor.dballoc AS alloc'],
		table_id:'lineno_',
		filterCol:['alloc.compcode','alloc.auditno','alloc.source','alloc.trantype'],
		filterVal:['session.compcode', '', 'AR','CN']
	};

	////////////////////////////////////////////////jqGrid2_Alloc////////////////////////////////////////////////
	$("#jqGrid2_Alloc").jqGrid({
		datatype: "local",
		editurl: "./CreditNoteARDetail/form",
		colModel: [
			{ label: ' ', name: 'checkbox', width: 15, formatter: checkbox_jqgAlloc, hidden:true },
			{ label: 'Debtor', name: 'debtorcode', width: 100, classes: 'wrap', formatter: showdetail,unformat:un_showdetail },
			{ label: 'Document Date', name: 'allocdate', width: 100, classes: 'wrap',
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}
			},
			{ label: 'Document No', name: 'recptno', width: 100, classes: 'wrap' },
			{ label: 'Amount', name: 'refamount', width: 100, classes: 'wrap',
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: false,
				align: "right",
				editrules:{required: true},edittype:"text",
				editoptions:{
					readonly: "readonly",
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
			{ label: 'O/S Amount', name: 'outamount', width: 100, align: 'right', classes: 'wrap', editable:false,	
				formatter: 'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editrules:{required: false},editoptions:{readonly: "readonly"},
			},
			{ label: 'Amount Paid', name: 'amount', width: 100, classes: 'wrap', 
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: true,
				align: "right",
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
			{ label: 'Balance', name: 'balance', width: 100, classes: 'wrap', hidden:false, 
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: false,
				align: "right",
				editrules:{required: true},edittype:"text",
				editoptions:{
					readonly: "readonly",
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
			{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true },
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true },
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true },
			{ label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden:true },
			{ label: 'Line No', name: 'lineno_', width: 20, classes: 'wrap', hidden:true }, 
			{ label: 'docsource', name: 'docsource', width: 20, classes: 'wrap', hidden:true },
			{ label: 'doctrantype', name: 'doctrantype', width: 20, classes: 'wrap', hidden:true },
			{ label: 'docauditno', name: 'docauditno', width: 20, classes: 'wrap', hidden:true },
			{ label: 'refsource', name: 'refsource', width: 20, classes: 'wrap', hidden:true },
			{ label: 'reftrantype', name: 'reftrantype', width: 20, classes: 'wrap', hidden:true },
			{ label: 'refauditno', name: 'refauditno', width: 20, classes: 'wrap', hidden:true },
			{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true },
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager2_Alloc",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid2_Alloc");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
		beforeSubmit: function(postdata, rowid){
	 	}
	});

	////////////////////////////////////////////////////////////////////////////////////////////////////////
	function checkbox_jqgAlloc(cellvalue, options, rowObject){
		return `<input class='checkbox_jqgAlloc' type="checkbox" name="checkbox" data-rowid="`+options.rowId+`">`;
	}

	////////////////////////////////////////////////////DN////////////////////////////////////////////////////
	var urlParam2_DN = {
		action: 'get_table_dtl',
		url:'DebitNoteDetail/table',
		source:'',
		trantype:'',
		auditno:'',
		// field:['dbactdtl.compcode','dbactdtl.source','dbactdtl.trantype','dbactdtl.auditno','dbactdtl.lineno_','dbactdtl.deptcode','dbactdtl.category','dbactdtl.document', 'dbactdtl.AmtB4GST', 'dbactdtl.GSTCode', 'dbactdtl.amount', 'dbactdtl.grnno', 'dbactdtl.amtslstax as tot_gst'],
		// table_name:['debtor.dbactdtl AS dbactdtl'],
		// table_id:'lineno_',
		// filterCol:['dbactdtl.compcode','dbactdtl.auditno', 'dbactdtl.recstatus','dbactdtl.source','dbactdtl.trantype'],
		// filterVal:['session.compcode', '', '<>.DELETE', 'PB', 'DN']
	};

	////////////////////////////////////////////////jqGrid2_DN////////////////////////////////////////////////
	$("#jqGrid2_DN").jqGrid({
		datatype: "local",
		editurl: "./DebitNoteDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'AuditNo', name: 'auditno', hidden: true },
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true, editable:false },
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true, editable:false },
			//{ label: 'Department', name: 'deptcode', width: 250, classes: 'wrap', canSearch: true, editable: false },
			//{ label: 'Category', name: 'category', width: 250, edittype:'text', classes: 'wrap', editable: false },
			{ label: 'Department', name: 'deptcode', width: 150, classes: 'wrap', canSearch: true, editable: false,
				editrules:{required: true,custom:true, custom_func:cust_rules},
				formatter: showdetail,
				edittype:'custom',	editoptions:
					{  
						custom_element:deptcodeDNCustomEdit,
						custom_value:galGridCustomValue 	
					},
			},
			{ label: 'Category', name: 'category', width: 250, edittype:'text', classes: 'wrap', hidden:true, editable: false,
				editrules:{required: true,custom:true, custom_func:cust_rules},
				formatter: showdetail,
				edittype:'custom',	editoptions:
					{  
						custom_element:categoryDNCustomEdit,
						custom_value:galGridCustomValue 	
					},
			},
			{ label: 'Document', name: 'document', width: 150, classes: 'wrap', editable: false },
			//{ label: 'GST Code', name: 'GSTCode', width: 150, classes: 'wrap', editable: false },
			{ label: 'GST Code', name: 'GSTCode', width: 100, classes: 'wrap', editable: true,
				editrules:{required: true,custom:true, custom_func:cust_rules},
				formatter: showdetail,
				edittype:'custom',	editoptions:
					{
						custom_element:GSTCodeDNCustomEdit,
						custom_value:galGridCustomValue 	
					},
			},
			{ label: 'Amount Before GST', name: 'AmtB4GST', width: 90, classes: 'wrap', editable: false, align: "right", formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,} },
			{ label: 'Total Tax Amount', name: 'tot_gst', width: 90, align: 'right', classes: 'wrap', editable:false, formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, } },
			{ label: 'Amount', name: 'amount', width: 90, classes: 'wrap', editable: false, align: "right", formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,} },
			{ label: 'rate', name: 'rate', width: 50, classes: 'wrap', hidden:true },
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
		loadComplete: function(data){	
			calc_jq_height_onchange("jqGrid2_DN");
		},		
		gridComplete: function(){
			fdl.set_array().reset();
		},
		beforeSubmit: function (postdata, rowid) {
		}

	});
		
	////////////////////////////////////////////////jqGrid3_DN////////////////////////////////////////////////
	$("#jqGrid3_DN").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2_DN").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3_DN",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid3_DN");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		}
	});
	jqgrid_label_align_right("#jqGrid3_DN");

	$("#jqGrid3_DN_panel").on("show.bs.collapse", function(){
		$("#jqGrid3_DN").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_DN_c")[0].offsetWidth-$("#jqGrid3_DN_c")[0].offsetLeft-18));
	});
		
	////////////////////////////////////////////////////IN////////////////////////////////////////////////////
	var urlParam2_IN={
		action: 'get_table_dtl',
		url:'SalesOrderDetail/table',
		source:'',
		trantype:'',
		auditno:'',
		deptcode:''
	};

	////////////////////////////////////////////////jqGrid2_IN////////////////////////////////////////////////
	$("#jqGrid2_IN").jqGrid({
		datatype: "local",
		editurl: "SalesOrderDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'No', name: 'lineno_', width: 50, classes: 'wrap', editable: false, hidden: true },
			// { label: 'Item Code', name: 'chggroup', width: 100, classes: 'wrap', editable: false },
			{ label: 'Item Code', name: 'chggroup', width: 280, classes: 'wrap', editable: false,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: itemcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Item Description', name: 'description', width: 150, classes: 'wrap', editable: false, editoptions: { readonly: "readonly" }, hidden:true },
			{ label: 'UOM Code', name: 'uom', width: 120, classes: 'wrap', editable: false,
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: uomcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Tax', name: 'taxcode', width: 120, classes: 'wrap', editable: false,
				editrules: { custom: true, custom_func: cust_rules },
				formatter: showdetail,
				edittype: 'custom', editoptions:
				{
					custom_element: taxcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			{ label: 'Unit Price', name: 'unitprice', width: 80, classes: 'wrap txnum', align: 'right', editable: false, formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, } },
			{ label: 'Quantity', name: 'quantity', width: 80, align: 'right', classes: 'wrap txnum', editable: false, formatter: 'integer', formatoptions: { thousandsSeparator: ",", } },
			{ label: 'Quantity on Hand', name: 'qtyonhand', width: 80, align: 'right', classes: 'wrap txnum', editable: false, formatter: 'integer', formatoptions: { thousandsSeparator: ",", } },
			{ label: 'Bill Type <br>%', name: 'billtypeperct', width: 80, align: 'right', classes: 'wrap txnum', editable: false, formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, } },
			{ label: 'Bill Type <br>Amount ', name: 'billtypeamt', width: 80, align: 'right', classes: 'wrap txnum', editable: false, formatter: 'currency', formatoptions: { thousandsSeparator: ",", } },
			{ label: 'Total Amount <br>Before Tax', name: 'amtb4tax', width: 100, align: 'right', classes: 'wrap txnum', editable:false, formatter:'currency',formatoptions:{thousandsSeparator: ",",} },
			{ label: 'Tax Amount', name: 'taxamt', width: 80, align: 'right', classes: 'wrap txnum', editable: false, formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, } },
			{ label: 'Total Amount', name: 'amount', width: 80, align: 'right', classes: 'wrap txnum', editable:false, formatter:'currency',formatoptions:{thousandsSeparator: ",",} },
			{ label: 'recstatus', name: 'recstatus', width: 80, classes: 'wrap', hidden: true },
			{ label: 'id', name: 'id', width: 10, hidden: true, key:true },
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
		loadComplete: function(data){			
			calc_jq_height_onchange("jqGrid2_IN");
		},		
		gridComplete: function(){
			fdl.set_array().reset();
		},
		afterShowForm: function (rowid) {
		},
		beforeSubmit: function (postdata, rowid) {
		}
    });

	////////////////////////////////////////////////jqGrid3_IN////////////////////////////////////////////////
	$("#jqGrid3_IN").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2_IN").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3_IN",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid3_IN");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		}
	});
	jqgrid_label_align_right("#jqGrid3_IN");

	$("#jqGrid3_IN_panel").on("show.bs.collapse", function(){
		$("#jqGrid3_IN").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_IN_c")[0].offsetWidth-$("#jqGrid3_IN_c")[0].offsetLeft-18));
	});

	////////////////////////////////////////////////////RC////////////////////////////////////////////////////
	var urlParam2_RC={
		action:'maintable',
		url: './receipt/table',
		field:'',
	}

	var saveParam2_RC={	
		action:'receipt_save',
		url: 'receipt/form',
		oper:'add',
		field:'',
		table_name:'debtor.dbacthdr',
		table_id:'auditno',
		fixPost:true,
		skipduplicate: true,
		returnVal:true,
		sysparam:{source:'PB',trantype:'RC',useOn:'auditno'}
	};

	////////////////////////////////////////////////jqGrid2_RC////////////////////////////////////////////////
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
			{ label: 'Date', name: 'dbacthdr_adddate',width: 50, formatter: dateFormatter, unformat: dateUNFormatter }, //tunjuk
			{ label: 'Type', name: 'dbacthdr_PymtDescription', classes: 'wrap', width: 50 }, //tunjuk
			{ label: 'Receipt No.', name: 'dbacthdr_recptno', classes: 'wrap',width: 60, canSearch:true }, //tunjuk
			{ label: 'entrydate', name: 'dbacthdr_entrydate', hidden: true },
			{ label: 'entrydate', name: 'dbacthdr_entrytime', hidden: true },
			{ label: 'entrydate', name: 'dbacthdr_entryuser', hidden: true },
			{ label: 'Payer Code', name: 'dbacthdr_payercode', width: 150, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail },
			{ label: 'Payer Name', name: 'dbacthdr_payername', width: 150, classes: 'wrap text-uppercase', canSearch:true, hidden: true },//tunjuk
			// { label: 'Debtor Code', name: 'dbacthdr_debtorcode', width: 400, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail },
			{ label: 'MRN', name: 'dbacthdr_mrn',align:'right', width: 50 }, //tunjuk
			{ label: 'Epis', name: 'dbacthdr_episno',align:'right', width: 40 }, //tunjuk
			{ label: 'Patient Name', name: 'name', width: 150, classes: 'wrap', hidden: true },
			{ label: 'remark', name: 'dbacthdr_remark', hidden: true },
			{ label: 'authno', name: 'dbacthdr_authno', hidden: true },
			{ label: 'epistype', name: 'dbacthdr_epistype', hidden: true },
			{ label: 'cbflag', name: 'dbacthdr_cbflag', hidden: true },
			{ label: 'reference', name: 'dbacthdr_reference', hidden: true },
			{ label: 'Payment Mode', name: 'dbacthdr_paymode',width: 70 }, //tunjuk
			{ label: 'Amount', name: 'dbacthdr_amount', width: 60,align:'right',formatter:'currency',formatoptions:{prefix: ""} }, //tunjuk
			{ label: 'O/S Amount', name: 'dbacthdr_outamount', width: 60,align:'right',formatter:'currency',formatoptions:{prefix: ""} }, //tunjuk
			{ label: 'source', name: 'dbacthdr_source', hidden: true, checked:true },
			{ label: 'Trantype', name: 'dbacthdr_trantype', width: 45, formatter: showdetail, unformat:un_showdetail },
			{ label: 'Status', name: 'dbacthdr_recstatus',width: 50 }, //tunjuk
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
			// allocate("#jqGrid");
		},
		gridComplete: function(){
			// $('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
			fdl.set_array().reset();
			if(oper == 'add'){
				$("#jqGrid2_RC").setSelection($("#jqGrid2_RC").getDataIDs()[0]);
			}

			$('#'+$("#jqGrid2_RC").jqGrid ('getGridParam', 'selrow')).focus();
		},
		loadComplete:function(data){
			calc_jq_height_onchange("jqGrid2_RC");
		}
		
	});
	
	var urlParam_sys={
		action:'get_table_default',
		url: 'util/get_table_default',
		field:'',
		table_name:'sysdb.sysparam',
		table_id:'trantype',
		filterCol:['source','trantype','compcode'],
		filterVal:['PB','RC','session.compcode']
	}

	$("#sysparam").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'source', name: 'source', width: 60, hidden:true },
			{ label: 'Tran type', name: 'trantype', width: 60, hidden:true },
			{ label: 'Description', name: 'description', width: 150 },
			{ label: 'hdrtype', name: 'hdrtype', width: 150, hidden:true },
			{ label: 'updpayername', name: 'updpayername', width: 150, hidden:true },
			{ label: 'depccode', name: 'depccode', width: 150, hidden:true },
			{ label: 'depglacc', name: 'depglacc', width: 150, hidden:true },
			{ label: 'updepisode', name: 'updepisode', width: 150, hidden:true },
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
				saveParam2_RC.sysparam.trantype=rowData['trantype'];
				$('#dbacthdr_PymtDescription').val(rowData['description']);
				if($("input:radio[name='optradio'][value='deposit']").is(':checked')){
					$("input:hidden[name='dbacthdr_hdrtype']").val(rowData['hdrtype']);
					$("input:hidden[name='updepisode']").val(rowData['updepisode']);
					$("input:hidden[name='updpayername']").val(rowData['updpayername']);
					$("#formdata_RC input[name='dbacthdr_crcostcode']").val(rowData['depccode']);
					$("#formdata_RC input[name='dbacthdr_cracc']").val(rowData['depglacc']);
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

	var urlParam2_rc={
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
			{ label: 'Pay Mode', name: 'paymode', width: 60 },
			{ label: 'Description', name: 'description', width: 150 },
			{ label: 'ccode', name: 'ccode', hidden: true },
			{ label: 'glaccno', name: 'glaccno', hidden: true },
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
				$("#formdata_RC input[name='dbacthdr_drcostcode']").val(rowData['ccode']);
				$("#formdata_RC input[name='dbacthdr_dracc']").val(rowData['glaccno']);
			}
		},
		beforeSelectRow: function(rowid, e) {
			if(oper=='view'){
				$('#'+$("#g_paymodebank").jqGrid ('getGridParam', 'selrow')).focus();
				return false;
			}
		}
	});

	var urlParam3_rc={
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
			{ label: 'Pay Mode', name: 'paymode', width: 60 },
			{ label: 'Description', name: 'description', width: 150 },
			{ label: 'ccode', name: 'ccode', hidden: true },
			{ label: 'glaccno', name: 'glaccno', hidden: true },
			{ label: 'cardflag', name: 'cardflag', hidden: true },
			{ label: 'valexpdate', name: 'valexpdate', hidden: true },
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

				$("#formdata_RC input[name='dbacthdr_drcostcode']").val(rowData['ccode']);
				$("#formdata_RC input[name='dbacthdr_dracc']").val(rowData['glaccno']);
			}
		},
		beforeSelectRow: function(rowid, e) {
			if(oper=='view'){
				$('#'+$("#g_paymodecard").jqGrid ('getGridParam', 'selrow')).focus();
				return false;
			}
		}
	});

	var urlParam4_rc={
		action:'get_effdate',
		type:'forex'
	}

	$("#g_forex").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Forex Code', name: 'forexcode', width: 60 },
			{ label: 'Description', name: 'description', width: 150 },
			{ label: 'costcode', name: 'costcode', hidden: true },
			{ label: 'glaccount', name: 'glaccount' , hidden: true },
			{ label: 'Rate', name: 'rate', width: 50 },
			{ label: 'effdate', name: 'effdate', width: 50  , hidden: true },
		],
		autowidth:true,
		multiSort: true,
		loadonce:true,
		width: 300,
		height: 150,
		rowNum: 2000,
		onSelectRow:function(rowid, selected){
			if(rowid != null) {
				rowData = $('#g_forex').jqGrid ('getRowData', rowid);
				$("#f_tab-forex input[name='dbacthdr_paymode']").val("forex");
				$("#f_tab-forex input[name='curroth']").val(rowData['forexcode']);
				$("#f_tab-forex input[name='dbacthdr_rate']").val(rowData['rate']);
				$("#f_tab-forex input[name='dbacthdr_currency']").val(rowData['forexcode']);
				$("#formdata_RC input[name='dbacthdr_drcostcode']").val(rowData['costcode']);
				$("#formdata_RC input[name='dbacthdr_dracc']").val(rowData['glaccount']);

				$("#f_tab-forex input[name='dbacthdr_amount']").on('blur',{data:rowData,type:'RM'},currencyChg);

				$("#f_tab-forex input[name='dbacthdr_amount2']").on('blur',{data:rowData,type:'oth'},currencyChg);
			}
		},
		beforeSelectRow: function(rowid, e) {
			if(oper=='view'){
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

	///RF
	
	////////////////////////////////////////////////end//////////////////////////////////////////////
	
	//////////handle searching, its radio button and toggle /////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field, table, case_;
		switch(options.colModel.name){
			case 'db_mrn':field=['MRN','name'];table="hisdb.pat_mast";case_='db_mrn';break;
			
			//CN
            case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='Department CN';break;
			case 'category':field=['catcode','description'];table="material.category";case_='Category CN';break;
			case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";case_='GST Code CN';break;

			//DN
            case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='Department DN';break;
			case 'category':field=['catcode','description'];table="material.category";case_='Category DN';break;
			case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";case_='GST Code DN';break;

			//IN
			case 'chggroup':field=['chgcode','description'];table="hisdb.chgmast";case_='chggroup';break;
			case 'uom':field=['uomcode','description'];table="material.uom";case_='uom';break;
			case 'taxcode':field=['taxcode','description'];table="hisdb.taxmast";case_='taxcode';break;

			//RC

			//jqgrid depan
			case 'db_deptcode':field=['deptcode','description'];table="sysdb.department";case_='db_deptcode';break;
			case 'db_debtorcode':field=['debtorcode','name'];table="debtor.debtormast";case_='db_debtorcode';break;

			//jqGridAlloc
			case 'debtorcode':field=['debtorcode','name'];table="debtor.debtormast";case_='debtorcode';break;
			case 'payercode':field=['debtorcode','name'];table="debtor.debtormast";case_='payercode';break;
			case 'paymode':field=['paymode','description'];table="debtor.paymode";case_='paymode';break;
			case 'mrn':field=['MRN','name'];table="hisdb.pat_mast";case_='mrn';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('arenquiry',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value, name) {
		var temp=null;
		switch (name) {
			//CN
			case 'Department CN': temp = $("#jqGrid2_CN input[name='deptcode']"); break;
			case 'Category CN': temp = $("#jqGrid2_CN input[name='category']"); break;
			case 'GST Code CN': temp = $("#jqGrid2 input[name='GSTCode']"); break;

			//DN
			case 'GST Code DN': temp = $("#jqGrid2_DN input[name='GSTCode']"); break;
			case 'Department DN': temp = $("#jqGrid2_CN input[name='deptcode']"); break;
			case 'Category DN': temp = $("#jqGrid2_CN input[name='category']"); break;

			//IN
			case 'Item Code': temp = $("#jqGrid2_IN input[name='chggroup']"); break;
			case 'UOM Code': temp = $("#jqGrid2_IN input[name='uom']"); break;
			case 'Tax Code': temp = $("#jqGrid2_IN input[name='taxcode']"); break;

			//RC

			case 'Category':temp=$('#category');break;
			case 'UOM Code': temp = $("#jqGrid2 input[name='uom']"); break;
			case 'GSTCode': temp = $("#jqGrid2 input[name='GSTCode']"); break;
			case 'PO UOM': temp = $("#jqGrid2 input[name='pouom']"); 
					var text = $( temp ).parent().siblings( ".help-block" ).text();
					if(text == 'Invalid Code'){
						return [false,"Please enter valid "+name+" value"];
					}
					break;
			case 'Price Code': temp = $("#jqGrid2 input[name='pricecode']"); break;
			case 'Tax Code': temp = $("#jqGrid2 input[name='taxcode']"); break;
			case 'Quantity Request': temp = $("#jqGrid2 input[name='quantity']");break;
		}
		if(temp == null) return [true,''];
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];

	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	//CN
	function deptcodeCNCustomEdit(val, opt) {
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2_CN" optid="'+opt.id+'" id="'+opt.id+'" name="deptcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function categoryCNCustomEdit(val, opt) {
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2_CN" optid="'+opt.id+'" id="'+opt.id+'" name="category" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function GSTCodeCNCustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid2_CN" optid="'+opt.id+'" id="'+opt.id+'" name="GSTCode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a><input id="'+opt.id+'_gstpercent" name="gstpercent" type="hidden"></div><span class="help-block"></span>');
	}

	//DN
	function deptcodeDNCustomEdit(val, opt) {
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2_DN" optid="'+opt.id+'" id="'+opt.id+'" name="deptcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function categoryDNCustomEdit(val, opt) {
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2_DN" optid="'+opt.id+'" id="'+opt.id+'" name="category" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}
	function GSTCodeDNCustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		return $('<div class="input-group"><input jqgrid="jqGrid2_DN" optid="'+opt.id+'" id="'+opt.id+'" name="GSTCode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a><input id="'+opt.id+'_gstpercent" name="gstpercent" type="hidden"></div><span class="help-block"></span>');
	}

	//IN
	function itemcodeCustomEdit(val, opt) {
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

	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	////////////////////////////changing status and trigger search////////////////////////////
	$('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	$('#docuDate_search').on('click', searchDate);

	function whenchangetodate() {
		customer_search.off();
		department_search.off();
		$('#customer_search,#docuDate_from,#docuDate_to,#department_search').val('');
		$('#customer_search_hb').text('');
		$('#department_search_hb').text('');
		removeValidationClass(['#customer_search,#department_search']);
		if($('#Scol').val()=='db_entrydate'){
			urlParam.searchCol=urlParam.searchVal=null;
			$("input[name='Stext'], #customer_text, #department_text").hide("fast");
			$("#docuDate_text").show("fast");
		} else if($('#Scol').val() == 'db_debtorcode'){
			$("input[name='Stext'],#docuDate_text,#department_text").hide("fast");
			$("#customer_text").show("fast");
			customer_search.on();
		} else if($('#Scol').val() == 'db_deptcode'){
			$("input[name='Stext'],#docuDate_text,#customer_text").hide("fast");
			$("#department_text").show("fast");
			department_search.on();
		} else {
			$("#customer_text,#docuDate_text,#department_text").hide("fast");
			$("input[name='Stext']").show("fast");
			$("input[name='Stext']").velocity({ width: "100%" });
		}
		
		if($('#Scol').val()=='db_entrydate'){
			refreshGrid('#jqGrid', urlParam);
		}else{
			search('#jqGrid',$('#searchForm [name=Stext]').val(),$('#searchForm [name=Scol] option:selected').val(),urlParam);
		}
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
		});
		searchClick2('#jqGrid', '#searchForm', urlParam,false);
	}

	//////////////////Showdetail Header Dialog///////////////
	// var dialog_Customer = new ordialog(
	// 	'customer', 'debtor.debtormast', '#db_debtorcode', errorField,
	// 	{
	// 		colModel: [
	// 			{ label: 'DebtorCode', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
	// 			{ label: 'Description', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true,},
	// 		],
	// 		urlParam: {
	// 			filterCol:['compcode','recstatus'],
	// 			filterVal:['session.compcode','ACTIVE']
	// 		},
	// 	}, {
	// 		title: "Select Customer",
	// 		open: function(){
	// 			dialog_Customer.urlParam.filterCol=['recstatus', 'compcode'];
	// 			dialog_Customer.urlParam.filterVal=['ACTIVE', 'session.compcode'];
	// 		}
	// 	},'urlParam','radio','tab'
	// );
	// dialog_Customer.makedialog();
	
	var dialog_mrnHDR = new ordialog(
		'dialog_mrnHDR', 'hisdb.pat_mast', "#jqGrid input[name='db_mrn']", errorField,
		{
			colModel: [
				{ label: 'MRN', name: 'MRN', width: 200, classes: 'pointer', canSearch: true, or_search: true , formatter: padzero, unformat: unpadzero },
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true },
			],
			urlParam: {
				filterCol:['compcode','ACTIVE'],
				filterVal:['session.compcode','1']
			},
		}, {
			title: "Select MRN",
			open: function(){
				dialog_mrnHDR.urlParam.filterCol=['recstatus', 'ACTIVE'];
				dialog_mrnHDR.urlParam.filterVal=['ACTIVE', '1'];
			}
		},'none','radio','tab'
	);
	dialog_mrnHDR.makedialog(false);

	//CN
	var dialog_CustomerCN = new ordialog(
		'db_debtorcodeCN', 'debtor.debtormast', '#formdata_CN input[name=db_debtorcode]', errorField,
		{
			colModel: [
				{ label: 'DebtorCode', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		}, {
			title: "Select Customer",
			open: function(){
				dialog_CustomerCN.urlParam.filterCol=['recstatus', 'compcode'];
				dialog_CustomerCN.urlParam.filterVal=['ACTIVE', 'session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_CustomerCN.makedialog(false);

	var dialog_paymodeCN = new ordialog(
		'db_paymodeCN','debtor.paymode',"#formdata_CN input[name='db_paymode']",errorField,
		{	colModel:[
				{ label:'Paymode',name:'paymode',width:200,classes:'pointer',canSearch:true,or_search:true },
				{ label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true },
				{ label:'Paytype',name:'paytype',width:200,classes:'pointer',hidden:true },
			],
			urlParam: {
				filterCol:['compcode','recstatus', 'source', 'paytype'],
				filterVal:['session.compcode','ACTIVE', 'AR', 'Credit Note']
			},
			ondblClickRow:function(){
				// $('#db_remark').focus();
			},
			gridComplete: function(obj){
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
			title:"Select Paymode",
			open: function(){
				dialog_paymodeCN.urlParam.filterCol=['recstatus', 'compcode', 'source', 'paytype'],
				dialog_paymodeCN.urlParam.filterVal=['ACTIVE', 'session.compcode', 'AR', 'Credit Note'];
				}
			},'urlParam','radio','tab'
		);
	dialog_paymodeCN.makedialog(true);
	
	//DN
	var dialog_CustomerDN = new ordialog(
		'db_debtorcodeDN', 'debtor.debtormast', '#formdata_DN input[name=db_debtorcode]', errorField,
		{
			colModel: [
				{ label: 'DebtorCode', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		}, {
			title: "Select Customer",
			open: function(){
				dialog_CustomerDN.urlParam.filterCol=['recstatus', 'compcode'];
				dialog_CustomerDN.urlParam.filterVal=['ACTIVE', 'session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_CustomerDN.makedialog(false);

	var dialog_paymodeDN = new ordialog(
		'db_paymodeDN','debtor.paymode',"#formdata_DN input[name='db_paymode']",errorField,
		{	colModel:[
				{ label:'Paymode',name:'paymode',width:200,classes:'pointer',canSearch:true,or_search:true },
				{ label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true },
				{ label:'Paytype',name:'paytype',width:200,classes:'pointer',hidden:true },
			],
			urlParam: {
				filterCol:['compcode','recstatus', 'source', 'paytype'],
				filterVal:['session.compcode','ACTIVE', 'AR', 'Debit Note']
			},
			ondblClickRow:function(){
				// $('#db_remark').focus();
			},
			gridComplete: function(obj){
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
			title:"Select Paymode",
			open: function(){
				dialog_paymodeDN.urlParam.filterCol=['recstatus', 'compcode', 'source', 'paytype'],
				dialog_paymodeDN.urlParam.filterVal=['ACTIVE', 'session.compcode', 'AR', 'Debit Note'];
				}
			},'urlParam','radio','tab'
		);
	dialog_paymodeDN.makedialog(true);
	
	//IN-SalesOrder
	var dialog_deptcode = new ordialog(
		'db_deptcode', 'sysdb.department', '#db_deptcode', errorField,
		{
			colModel: [
				{ label: 'SectorCode', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus','chgdept','storedept'],
				filterVal:['session.compcode','ACTIVE','1','1']
			},
		}, {
			title: "Select Unit",
			open: function(){
				dialog_deptcode.urlParam.filterCol=['recstatus', 'compcode','chgdept','storedept'];
				dialog_deptcode.urlParam.filterVal=['ACTIVE', 'session.compcode','1','1'];
			}
		},'urlParam','radio','tab'
	);
	dialog_deptcode.makedialog(false);
	
	var dialog_CustomerSO = new ordialog(
		'db_debtorcodeSO', 'debtor.debtormast', 'formdata_IN input[name=db_debtorcode', errorField,
		{
			colModel: [
				{ label: 'DebtorCode', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		}, {
			title: "Select Customer",
			open: function(){
				dialog_CustomerSO.urlParam.filterCol=['recstatus', 'compcode'];
				dialog_CustomerSO.urlParam.filterVal=['ACTIVE', 'session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_CustomerSO.makedialog(false);

	var dialog_billtypeSO = new ordialog(
		'billtype', 'hisdb.billtymst', '#db_hdrtype', errorField,
		{
			colModel: [
				{ label: 'Billtype', name: 'billtype', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus','opprice'],
				filterVal:['session.compcode','ACTIVE','1']
			},
		}, {
			title: "Select Billtype",
			open: function(){
				dialog_billtypeSO.urlParam.filterCol=['recstatus', 'compcode','opprice'];
				dialog_billtypeSO.urlParam.filterVal=['ACTIVE', 'session.compcode','1'];
			}
		},'urlParam','radio','tab'
	);
	dialog_billtypeSO.makedialog(false);
	
	var dialog_mrn = new ordialog(
		'dialog_mrn', 'hisdb.pat_mast', '#db_mrn', errorField,
		{
			colModel: [
				{ label: 'MRN', name: 'MRN', width: 200, classes: 'pointer', canSearch: true, or_search: true , formatter: padzero, unformat: unpadzero },
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true },
			],
			urlParam: {
				filterCol:['compcode','ACTIVE'],
				filterVal:['session.compcode','1']
			},
		}, {
			title: "Select MRN",
			open: function(){
				dialog_mrn.urlParam.filterCol=['recstatus', 'ACTIVE'];
				dialog_mrn.urlParam.filterVal=['ACTIVE', '1'];
			}
		},'none','radio','tab'
	);
	dialog_mrn.makedialog(false);

	//RC
	var dialog_logindeptcode = new ordialog(
		'till_dept', 'sysdb.department', '#till_dept', errorField,
		{
			colModel: [
				{ label: 'Department', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function (event) {
				$('#tillstatus').focus();

				let data=selrowData('#'+dialog_logindeptcode.gridname);
				
				// sequence.set(data['deptcode']).get();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#tillstatus').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		}, {
			title: "Select Department",
			open: function(){
				dialog_logindeptcode.urlParam.filterCol=['recstatus', 'compcode'];
				dialog_logindeptcode.urlParam.filterVal=['ACTIVE', 'session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_logindeptcode.makedialog();

	var dialog_mrn = new ordialog(
		'mrn','hisdb.pat_mast','#dbacthdr_mrn',errorField,
		{	colModel:[
				{ label:'MRN',name:'MRN',width:100,classes:'pointer',canSearch:true,or_search:true },
				{ label:'Name',name:'Name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true },
				{ label:'Last Episode',name:'Episno',width:100,classes:'pointer' },
			],
			urlParam: {
					filterCol:['compcode'],
					filterVal:['session.compcode']
				},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_mrn.gridname);
				//$('#apacthdr_actdate').focus();
				$('#dbacthdr_mrn').val(data.MRN);
				$('#dbacthdr_episno').val(data.Episno);
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
			title:"Select MRN",
			open: function(){
				dialog_payercode.urlParam.filterCol=['compcode'],
				dialog_payercode.urlParam.filterVal=['session.compcode']
				}
			},'urlParam','radio','tab'
		);
	dialog_mrn.makedialog(true);

	//RF

	///////////////////////////////////////////RC///////////////////////////////////////////
	$( "#divMrnEpisode" ).hide();
	amountchgOn(true);
	$("input:radio[name='optradio']").change(function(){
		if($("input:radio[name='optradio'][value='receipt']").is(':checked')){
			amountchgOn(false);
			$( "#divMrnEpisode" ).hide();
			urlParam_sys.table_name='sysdb.sysparam';
			urlParam_sys.table_id='trantype';
			urlParam_sys.field=['source','trantype','description'];
			urlParam_sys.filterCol=['source','trantype','compcode'];
			urlParam_sys.filterVal=['PB','RC','session.compcode'];
			refreshGrid('#sysparam',urlParam_sys);
			$('#dbacthdr_trantype').val('');
			$('#dbacthdr_PymtDescription').val('');

		}else if($("input:radio[name='optradio'][value='deposit']").is(':checked')){
			amountchgOff(false);
			$( "#divMrnEpisode" ).show();
			urlParam_sys.table_name='debtor.hdrtypmst';
			urlParam_sys.table_id='hdrtype';
			urlParam_sys.field=['source','trantype','description','hdrtype','updpayername','depccode','depglacc','updepisode'];
			urlParam_sys.filterCol=['compcode'];
			urlParam_sys.filterVal=['session.compcode'];
			refreshGrid('#sysparam',urlParam_sys);
			$('#dbacthdr_trantype').val('');
			$('#dbacthdr_PymtDescription').val('');
		}
	});
	///////////////////////////////////////////end RC///////////////////////////////////////////

	////////////SearchBy/////////////////

	function searchDate(){
		urlParam.filterdate = [$('#docuDate_from').val(),$('#docuDate_to').val()];
		refreshGrid('#jqGrid',urlParam);
		urlParam.filterdate = null;
	}

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
		},{fct:['db.recstatus'],fv:[],fc:[]});

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		refreshGrid('#jqGrid',urlParam);
	}

	var customer_search = new ordialog(
		'customer_search', 'debtor.debtormast', '#customer_search', 'errorField',
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
				let data = selrowData('#' + customer_search.gridname).debtorcode;

				if($('#Scol').val() == 'db_debtorcode'){
					urlParam.searchCol=["db.debtorcode"];
					urlParam.searchVal=[data];
				}
				// }else if($('#Scol').val() == 'db_payercode'){
				// 	urlParam.searchCol=["db.payercode"];
				// 	urlParam.searchVal=[data];
				// }
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
				//$('#db_debtorcode').val(data['debtorcode']);
			}
		},{
			title: "Select Customer",
			open: function () {
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
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow: function () {
				let data = selrowData('#' + department_search.gridname).deptcode;

				if($('#Scol').val() == 'db_deptcode'){
					urlParam.searchCol=["db.deptcode"];
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
			title: "Select Creditor",
			open: function () {
				department_search.urlParam.filterCol = ['recstatus'];
				department_search.urlParam.filterVal = ['ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	department_search.makedialog(true);
	$('#department_search').on('keyup',ifnullsearch);	
	////////////End SerarchBy/////////////////

	function ifnullsearch(){
		if($(this).val() == ''){
			urlParam.searchCol=[];
			urlParam.searchVal=[];
			$('#jqGrid').data('inputfocus',$(this).attr('id'));
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
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight+30);
}

function getdata(mode,idno){
	switch(mode){
	case 'RC':
		populateform_rc(idno);
		break;
	}
}

//RC
var dialog_payercode = new ordialog(
	'payercode','debtor.debtormast','#dbacthdr_payercode','errorField',
	{	colModel:[
			{ label:'Debtor Code',name:'debtorcode',width:200,classes:'pointer',canSearch:true,or_search:true },
			{ label:'Debtor Name',name:'name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true },
			{ label:'debtortype',name:'debtortype',hidden:true },
			{ label:'actdebccode',name:'actdebccode',hidden:true },
			{ label:'actdebglacc',name:'actdebglacc',hidden:true },
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

function populateform_rc(idno){
	var param={
			action:'populate_rc',
			url:'./arenquiry/table',
			field:['dbacthdr_compcode','dbacthdr_auditno','dbacthdr_lineno_','dbacthdr_billdebtor','dbacthdr_conversion','dbacthdr_hdrtype','dbacthdr_currency','dbacthdr_tillcode','dbacthdr_tillno','dbacthdr_debtortype','dbacthdr_adddate','dbacthdr_PymtDescription','dbacthdr_recptno','dbacthdr_entrydate','dbacthdr_entrytime','dbacthdr_entryuser','dbacthdr_payercode','dbacthdr_payername','dbacthdr_mrn','dbacthdr_episno','dbacthdr_remark','dbacthdr_authno','dbacthdr_epistype','dbacthdr_cbflag','dbacthdr_reference','dbacthdr_paymode','dbacthdr_amount','dbacthdr_outamount','dbacthdr_source','dbacthdr_trantype','dbacthdr_recstatus','dbacthdr_bankcharges','dbacthdr_expdate','dbacthdr_rate','dbacthdr_unit','dbacthdr_invno','dbacthdr_paytype','dbacthdr_RCCASHbalance','dbacthdr_RCFinalbalance','dbacthdr_RCOSbalance','dbacthdr_idno'],
			idno:idno,
		}

		$.get( param.url+"?"+$.param(param), function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows)){
				$.each(data.rows, function( index, value ) {
					var input=$("#dialogForm_RC [name='"+index+"']");
					if(input.is("[type=radio]")){
						$(form+" [name='"+index+"'][value='"+value+"']").prop('checked', true);
					}else{
						input.val(value);
					}
				});
				$(".nav-tabs a[form='"+data.rows.dbacthdr_paytype.toLowerCase()+"']").tab('show');
				dialog_payercode.check('errorField');
				disabledPill();
			}
		});
}

function disabledPill(){
	$('#dialogForm_RC .nav li').not('.active').addClass('disabled');
	$('#dialogForm_RC .nav li').not('.active').find('a').removeAttr("data-toggle");
}

function enabledPill(){
	$('#dialogForm_RC .nav li').removeClass('disabled');
	$('#dialogForm_RC .nav li').find('a').attr("data-toggle","tab");
}

function init_jq2(oper){
	if(oper != 'add'){
		var unallocated = selrowData('#jqGrid').unallocated;
		if(unallocated == 'true'){
			$("#formdata_CN select[name='db_trantype2']").val('CNU');
		}else{
			$("#formdata_CN select[name='db_trantype2']").val('CN');
		}
	}

	if(($("#formdata_CN select[name='db_trantype2']").find(":selected").text() == 'Credit Note')) {
		// $('#save').hide();
		$('#grid_alloc').show();
		$('#grid_dtl').show();
		$('#jqGridPager2_Alloc').hide();
		$("#jqGrid2_Alloc").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_Alloc_c")[0].offsetWidth-$("#jqGrid2_Alloc_c")[0].offsetLeft-28));
		$("#jqGrid2_CN").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_CN_c")[0].offsetWidth-$("#jqGrid2_CN_c")[0].offsetLeft));
	} else if (($("#formdata_CN select[name='db_trantype2']").find(":selected").text() == 'Credit Note Unallocated')) { 
		// $('#save').hide();
		$('#grid_alloc').hide();
		$('#grid_dtl').show();
		$('#jqGridPager2_Alloc').hide();
 		//$("#jqGrid2_Alloc input[name='allocamount']").attr('readonly','readonly');
	}
}
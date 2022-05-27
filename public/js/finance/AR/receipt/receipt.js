$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	var tabform="#f_tab-cash";

	checkifuserlogin();
	
	function getcr(paytype){
		var param={
			action:'get_value_default',
			field:['glaccno','ccode'],
			url: 'util/get_value_default',
			table_name:'debtor.paymode',
			table_id:'paymode',
			filterCol:['paytype','source'],
			filterVal:[paytype,'AR'],
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

	var mycurrency =new currencymode(['#f_tab-cash input[name=dbacthdr_amount]','#f_tab-cash input[name=dbacthdr_outamount]','#f_tab-cash input[name=dbacthdr_RCCASHbalance]','#f_tab-cash input[name=dbacthdr_RCFinalbalance]','#f_tab-card input[name=dbacthdr_amount]','#f_tab-card input[name=dbacthdr_outamount]','#f_tab-card input[name=dbacthdr_RCFinalbalance]','#f_tab-cheque input[name=dbacthdr_amount]','#f_tab-cheque input[name=dbacthdr_outamount]','#f_tab-cheque input[name=dbacthdr_RCFinalbalance]','#f_tab-debit input[name=dbacthdr_amount]','#f_tab-debit input[name=dbacthdr_outamount]','#f_tab-debit input[name=dbacthdr_RCFinalbalance]','#f_tab-debit input[name=dbacthdr_bankcharges]','#f_tab-forex input[name=dbacthdr_amount]','#f_tab-forex input[name=dbacthdr_amount2]','#f_tab-forex input[name=dbacthdr_RCFinalbalance]','#f_tab-forex input[name=dbacthdr_outamount]']);

	function disabledPill(){
		$('.nav li').not('.active').addClass('disabled');
		$('.nav li').not('.active').find('a').removeAttr("data-toggle");
	}

	function enabledPill(){
		$('.nav li').removeClass('disabled');
		$('.nav li').find('a').attr("data-toggle","tab");
	}

	function getLastrcnumber(){//lastrcnummber for till or receipt number
		var param={
			action:'get_value_default',
			url: 'util/get_value_default',
			field:['lastrcnumber'],
			table_name:'debtor.till',
			filterCol:['tillcode'],
			filterVal:[]
		}
		param.filterVal=[$("#formdata input[name='dbacthdr_tillcode']").val()];

		$.get( param.url+"?"+$.param(param), function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data)){
				$("#formdata input[name='dbacthdr_lastrcnumber']").val(parseInt(data.rows[0].lastrcnumber) + 1);
				$("#formdata input[name='dbacthdr_recptno']").val($("#formdata input[name='dbacthdr_tillcode']").val()+"-"+pad('000000000',parseInt(data.rows[0].lastrcnumber) + 1,true));
			}else{

			}
		});
	}

	function getRcpOutAmt(payercode){////// get outstanding amount for recepit trantype
		var param={
			action:'get_value_default',
			url: 'util/get_value_default',
			field:['SUM(outamount) as sum'],
			table_name:['debtor.dbacthdr','sysdb.sysparam'],
			join_type:['JOIN'],
			join_onCol:['dbacthdr.source'],
			join_onVal:['sysparam.source'],
			filterCol:['dbacthdr.payercode','sysparam.source','sysparam.pvalue2','dbacthdr.recstatus','dbacthdr.trantype','dbacthdr.compcode'],
			filterVal:[payercode,'PB','DR','A','skip.sysparam.trantype','skip.sysparam.compcode'],
		};
		$.get( param.url+"?"+$.param(param), function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data)){
				$("input[name='dbacthdr_outamount']").val(data.rows[0].sum);
				mycurrency.formatOn();
			}else{

			}
		});
	}

	// function getOut(payercode){ //tak guna
	// 	var param={
	// 		action:'get_value_default',
	// 		url: 'util/get_value_default',
	// 		field:['SUM(outamount)'],
	// 		table_name:'debtor.dbacthdr',
	// 		filterCol:['payercode','source','recstatus'],
	// 		filterVal:[payercode,'PB','A'],
	// 		filterInCol:['trantype'],
	// 		filterInType:['IN'],
	// 		filterInVal:[['DN','IN']]
	// 	};
	// 	$.get( param.url+"?"+$.param(param), function( data ) {
			
	// 	},'json').done(function(data) {
	// 		if(!$.isEmptyObject(data)){
	// 			$(tabform+" input[name='dbacthdr_outamount']").val(data['SUM(outamount)']);
	// 		}else{

	// 		}
	// 	});
	// }

	///////////////////  for handling amount based on trantype/////////////////////////
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
	///////////////////  end hadling amount based on trantype/////////////////////////

	////////////////////////////////////transaction minimum date///////////////////////

	var actdateObj = new setactdate(["input[name='dbacthdr_entrydate']"]);
	actdateObj.getdata().set();

	////////////////////////////end transaction minimum date////////////////////////////////

	////////////////////////////////////////////////////ordialog////////////////////////////////////////
	// dialog_dbmast=new makeDialog('debtor.debtormast','#dbacthdr_payercode',['debtorcode','name','debtortype','actdebccode','actdebglacc'], 'Payer code');
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
				}
			},'urlParam','radio','tab'
		);
	dialog_payercode.makedialog(true);

	var dialog_logindeptcode = new ordialog(
		'till_dept', 'sysdb.department', '#till_dept', errorField,
		{
			colModel: [
				{ label: 'Department', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true,},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function (event) {
				$('#tillstatus').focus();

				let data=selrowData('#'+dialog_logindeptcode.gridname);
				
				sequence.set(data['deptcode']).get();
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

	// var dialog_logintillcode = new ordialog(
	// 	'till_tillcode', 'debtor.till', '#till_tillcode', errorField,
	// 	{
	// 		colModel: [
	// 			{ label: 'Till', name: 'tillcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
	// 			{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true,},
	// 		],
	// 		urlParam: {
	// 			filterCol:['compcode','recstatus','tillstatus'],
	// 			filterVal:['session.compcode','A','C']
	// 		},
	// 		ondblClickRow: function (event) {
	// 			$('#till_dept').focus();

	// 			let data=selrowData('#'+dialog_logintillcode.gridname);
				
	// 			sequence.set(data['tillcode']).get();
	// 		},
	// 		gridComplete: function(obj){
	// 			var gridname = '#'+obj.gridname;
	// 			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
	// 				$(gridname+' tr#1').click();
	// 				$(gridname+' tr#1').dblclick();
	// 				$('#tillcode').focus();
	// 			}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
	// 				$('#'+obj.dialogname).dialog('close');
	// 			}
	// 		}
	// 	}, {
	// 		title: "Select Till Code",
	// 		open: function(){
	// 			dialog_logintillcode.urlParam.filterCol=['recstatus', 'compcode','tillstatus'];
	// 			dialog_logintillcode.urlParam.filterVal=['A', 'session.compcode','C'];
	// 		}
	// 	},'urlParam','radio','tab'
	// );
	// dialog_logintillcode.makedialog();
	////////////////////////////////////////////////////END ordialog////////////////////////////////////////


	$( "#divMrnEpisode" ).hide();
	amountchgOn(true);
	$("input:radio[name='optradio']").change(function(){
		if($("input:radio[name='optradio'][value='receipt']").is(':checked')){
			amountchgOn(false);
			$( "#divMrnEpisode" ).hide();
			urlParam_sys.table_name='sysdb.sysparam';
			urlParam_sys.table_id='trantype';
			urlParam_sys.field=['source','trantype','description'];
			urlParam_sys.filterCol=['source','trantype'];
			urlParam_sys.filterVal=['PB','RC'];
			refreshGrid('#sysparam',urlParam_sys);
			$('#dbacthdr_trantype').val('');
			$('#dbacthdr_PymtDescription').val('');

		}else if($("input:radio[name='optradio'][value='deposit']").is(':checked')){
			amountchgOff(false);
			$( "#divMrnEpisode" ).show();
			urlParam_sys.table_name='debtor.hdrtypmst';
			urlParam_sys.table_id='hdrtype';
			urlParam_sys.field=['source','trantype','description','hdrtype','updpayername','depccode','depglacc','updepisode'];
			urlParam_sys.filterCol=null;
			urlParam_sys.filterVal=null;
			refreshGrid('#sysparam',urlParam_sys);
			$('#dbacthdr_trantype').val('');
			$('#dbacthdr_PymtDescription').val('');
		}
	});


	///////////////////////////////////////////trantype//////////////////////
	var urlParam_sys={
		action:'get_table_default',
		url: 'util/get_table_default',
		field:'',
		table_name:'sysdb.sysparam',
		table_id:'trantype',
		filterCol:['source','trantype'],
		filterVal:['PB','RC']
	}

	$("#sysparam").jqGrid({
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
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		width: 300,
		height: 150,
		rowNum: 30,
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
						dialog_episode.handler(errorField);
					}
					if(rowData['updpayername'] == 1){
						$('#dbacthdr_payername').prop('readonly',false);
					}else{
						$('#dbacthdr_payername').prop('readonly',true);
					}
				}else{
					$('#dbacthdr_payername').prop('readonly',true);
					$("input:hidden[name='dbacthdr_hdrtype']").val('');
					$("input:hidden[name='updpayername']").val('');
					$("#mrn ~a").off();
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

	///////////////////////////////////////////Bank Paytype/////////////////////////////////
	var urlParam2={
		action:'get_table_default',
		url: 'util/get_table_default',
		field:'',
		table_name:'debtor.paymode',
		table_id:'paymode',
		filterCol:['source','paytype'],
		filterVal:['AR','BANK'],
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
		filterCol:['source','paytype'],
		filterVal:['AR','CARD'],
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


	////////////////////////////////forex////////////////////////////////////////////////
	var urlParam4={
		action:'get_effdate',
		type:'forex'
	}
	$("#g_forex").jqGrid({
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
				rowData = $('#g_forex').jqGrid ('getRowData', rowid);
				$("#f_tab-forex input[name='dbacthdr_paymode']").val("forex");
				$("#f_tab-forex input[name='curroth']").val(rowData['forexcode']);
				$("#f_tab-forex input[name='dbacthdr_rate']").val(rowData['rate']);
				$("#f_tab-forex input[name='dbacthdr_currency']").val(rowData['forexcode']);
				$("#formdata input[name='dbacthdr_drcostcode']").val(rowData['costcode']);
				$("#formdata input[name='dbacthdr_dracc']").val(rowData['glaccount']);

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

	$("#g_forex").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
	addParamField('#g_forex',false,urlParam4);
	//////////////////////////////// end forex////////////////////////////////////////////////////////


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


	////////////////////object for dialog handler//////////////////
	//dialog_dept=new makeDialog('sysdb.department','#dept',['deptcode','description'], 'Department');

	////////////////////////////////////start dialog//////////////////////////////////////
	
	var butt1=[{
		text: "Save",click: function() {
			mycurrency.formatOff();
			mycurrency.check0value(errorField);
			if( $('#formdata').isValid({requiredFields: ''}, conf, true) && $(tabform).isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
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
			// getcr('CASH');
			// getLastrcnumber();
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
					case state = '#f_tab-forex':
						refreshGrid("#g_forex",urlParam4);
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
						case state = '#f_tab-forex':
							refreshGrid("#g_forex",urlParam4);
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
			emptyFormdata(errorField, '#f_tab-forex');
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
		action:'get_table_default',
		url: 'util/get_table_default',
		field:'',
		fixPost:'true',
		table_name:['debtor.dbacthdr','hisdb.pat_mast'],
		table_id:'dbacthdr_idno',
		join_type:['LEFT JOIN'],
		join_onCol:['dbacthdr.mrn'],
		join_onVal:['pat_mast.mrn'],
		filterCol:['dbacthdr.trantype'],
		filterVal:['RC']
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	
	var saveParam={	
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
	
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{label: 'source', name: 'dbacthdr_source', hidden: true, checked:true},
			{label: 'trantype', name: 'dbacthdr_trantype', width: 90, hidden: true },
			{label: 'auditno', name: 'dbacthdr_auditno', width: 90, hidden: true  },
			{label: 'lineno_', name: 'dbacthdr_lineno_', width: 90, hidden: true },
			{label: 'outamount', name: 'dbacthdr_outamount', hidden: true},
			{label: 'recstatus', name: 'dbacthdr_recstatus', hidden: true},
			{label: 'billdebtor', name: 'dbacthdr_billdebtor', hidden: true},
			{label: 'conversion', name: 'dbacthdr_conversion', hidden: true},
			{label: 'hdrtype', name: 'dbacthdr_hdrtype', hidden: true},
			{label: 'currency', name: 'dbacthdr_currency', hidden: true},
			{label: 'tillcode', name: 'dbacthdr_tillcode', hidden: true},
			{label: 'tillno', name: 'dbacthdr_tillno', hidden: true},
			{label: 'debtortype', name: 'dbacthdr_debtortype', hidden: true},
			{label: 'debtorcode', name: 'dbacthdr_debtorcode', hidden: true},
			{label: 'Date', name: 'dbacthdr_adddate',width: 70}, //tunjuk
			{label: 'Type', name: 'dbacthdr_PymtDescription', classes: 'wrap', width: 100}, //tunjuk
			{label: 'Receipt No.', name: 'dbacthdr_recptno', classes: 'wrap',width: 120, canSearch:true}, //tunjuk
			{label: 'entrydate', name: 'dbacthdr_entrydate', hidden: true},
			{label: 'entrydate', name: 'dbacthdr_entrytime', hidden: true},
			{label: 'entrydate', name: 'dbacthdr_entryuser', hidden: true},
			{label: 'Payer Code', name: 'dbacthdr_payercode',width: 90}, //tunjuk
			{label: 'Payer Name', name: 'dbacthdr_payername', width: 200, classes: 'wrap', canSearch:true},//tunjuk
			{label: 'MRN', name: 'dbacthdr_mrn',align:'right', width: 60}, //tunjuk
			{label: 'Epis', name: 'dbacthdr_episno',align:'right', width: 40}, //tunjuk
			{label: 'Patient Name', name: 'name', width: 150, classes: 'wrap'}, //tunjuk
			{label: 'remark', name: 'dbacthdr_remark', hidden: true},
			{label: 'authno', name: 'dbacthdr_authno', hidden: true},
			{label: 'epistype', name: 'dbacthdr_epistype', hidden: true},
			{label: 'cbflag', name: 'dbacthdr_cbflag', hidden: true},
			{label: 'reference', name: 'dbacthdr_reference', hidden: true},
			{label: 'Pay Mode', name: 'dbacthdr_paymode',width: 50}, //tunjuk
			{label: 'Amount', name: 'dbacthdr_amount',width: 90,align:'right',formatter:'currency',formatoptions:{prefix: ""} }, //tunjuk
			{label: 'bankchg', name: 'dbacthdr_bankcharges', hidden: true},
			{label: 'expdate', name: 'dbacthdr_expdate', hidden: true},
			{label: 'rate', name: 'dbacthdr_rate', hidden: true},
			{label: 'units', name: 'dbacthdr_unit', hidden: true},
			{label: 'invno', name: 'dbacthdr_invno', hidden: true},
			{label: 'paytype', name: 'dbacthdr_paytype', hidden: true},
			{label: 'RCcashbalance', name: 'dbacthdr_RCCASHbalance', hidden: true},
			{label: 'RCFinalbalance', name: 'dbacthdr_RCFinalbalance', hidden: true},
			{label: 'RCOSbalance', name: 'dbacthdr_RCOSbalance', hidden: true},
			{label: 'idno', name: 'dbacthdr_idno', hidden: true},
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortorder:'desc',
		width: 900,
		height: 350,
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
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}

			$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
		},
		
	});

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
			if(selform!=''){
				$(".nav-tabs a[form='"+selform+"']").tab('show');
				disabledPill();
				populateFormdata("#jqGrid",'',selform,selRowId,'view');
			}else{
				$(".nav-tabs a[form='#f_tab-cash']").tab('show');
			}
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view');
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

	///////////////////////////////start->dialogHandler part////////////////////////////////////////////

	////////////////////////////////start allocation part///////////////////////////////////

	// $('#allocate').hide();
	// function allocate(grid){
	// 	if(selrowData(grid).dbacthdr_outamount>0){
	// 		$('#allocate').show();
	// 	}else{
	// 		$('#allocate').hide();
	// 	}
	// }

	// var myallocation = new Allocation();
	// var allocurrency = new currencymode(["#AlloBalance","#AlloTotal"]);

	// $( "#allocateDialog" ).dialog({
	// 	autoOpen: false,
	// 	width: 9/10 * $(window).width(),
	// 	modal: true,
	// 	open: function(){
	// 		$("#gridAllo").jqGrid ('setGridWidth', Math.floor($("#gridAllo_c")[0].offsetWidth-$("#gridAllo_c")[0].offsetLeft));
	// 		grid='#jqGrid';
	// 		$('#AlloDtype').val(selrowData(grid).dbacthdr_trantype);
	// 		$('#AlloDno').val(selrowData(grid).dbacthdr_recptno);
	// 		$('#AlloDebtor').val(selrowData(grid).dbacthdr_payercode);
	// 		$('#AlloDebtor2').html(selrowData(grid).dbacthdr_payername);
	// 		$('#AlloPayer').val(selrowData(grid).dbacthdr_payercode);
	// 		$('#AlloPayer2').html(selrowData(grid).dbacthdr_payername);
	// 		$('#AlloAmt').val(selrowData(grid).dbacthdr_amount);
	// 		$('#AlloOutamt').val(selrowData(grid).dbacthdr_outamount);
	// 		$('#AlloBalance').val(selrowData(grid).dbacthdr_outamount);
	// 		$('#AlloTotal').val(0);
	// 		urlParamAllo.filterVal[0]=selrowData(grid).dbacthdr_payercode;
	// 		refreshGrid("#gridAllo",urlParamAllo);
	// 		parent_close_disabled(true);
	// 		myallocation.renewAllo(selrowData(grid).dbacthdr_outamount);
	// 	},
	// 	close: function( event, ui ){
	// 		parent_close_disabled(false);

	// 	},
	// 	buttons:
	// 		[{
	// 			text: "Save",click: function() {
					
	// 			}
	// 		},{
	// 			text: "Cancel",click: function() {
	// 				$(this).dialog('close');
	// 			}
	// 		}],
	// });

	// var urlParamAllo={
	// 	action:'get_table_default',
	// 	field:'',
	// 	table_name:'debtor.dbacthdr',
	// 	table_id:'idno',
	// 	sort_idno:true,
	// 	filterCol:['payercode','source','recstatus','outamount'],
	// 	filterVal:['','PB','A','>.0'],
	// 	filterInCol:['trantype'],
	// 	filterInType:['IN'],
	// 	filterInVal:[['DN','IN']]
	// }

	// $("#gridAllo").jqGrid({
	// 	datatype: "local",
	// 	colModel: [
	// 		{ label: 'idno', name: 'idno', width: 40, hidden: true}, 
	// 		{ label: 'Auditno', name: 'auditno', width: 40},
	// 		{ label: 'Src', name: 'source', width: 20, hidden: true}, 
	// 		{ label: 'Type', name: 'trantype', width: 20 , hidden: true},
	// 		{ label: 'Line No', name: 'lineno_', width: 20 , hidden: true},
	// 		{ label: 'Batchno', name: 'NULL', width: 40},
	// 		{ label: 'Document Date', name: 'entrydate', width: 50},
	// 		{ label: 'MRN', name: 'mrn', width: 50},
	// 		{ label: 'EpisNo', name: 'episno', width: 50},
	// 		{ label: 'Amount', name: 'amount',formatter:'currency', width: 50},
	// 		{ label: 'O/S Amount', name: 'outamount',formatter:'currency', width: 50},
	// 		{ label: ' ', name: 'tick', width: 20, editable: true, edittype:"checkbox", align:'center'},
	// 		{ label: 'Amount Paid', name: 'amtpaid', width: 50, editable: true},
	// 		{ label: 'Balance', name: 'amtbal', width: 50,formatter:'currency',formatoptions:{prefix: ""} },
	// 	],
	// 	autowidth: true,
	// 	viewrecords: true,
	// 	multiSort: true,
	// 	height: 400,
	// 	rowNum: 9,
	// 	pager: "#pagerAllo",
	// 	onSelectRow: function(rowid){
	// 	},
	// 	onPaging: function(button){
	// 	},
	// 	gridComplete: function(rowid){
	// 		startEdit();
	// 		$("#gridAllo_c input[type='checkbox']").on('click',function(){
	// 			var idno = $(this).attr("rowid");
	// 			var rowdata = $("#gridAllo").jqGrid ('getRowData', idno);
	// 			if($(this).prop("checked") == true){
	// 				$("#"+idno+"_amtpaid").val(rowdata.outamount).addClass( "valid" ).removeClass( "error" );
	// 				setbal(idno,0);
	// 				if(!myallocation.alloInArray(idno)){
	// 					myallocation.addAllo(idno,rowdata.outamount,0);
	// 				}else{
	// 					$("#"+idno+"_amtpaid").trigger("change");
	// 				}
	// 			}
	// 		});
	// 		$("#gridAllo_c input[type='text'][rowid]").on('click',function(){
	// 			var idno = $(this).attr("rowid");
	// 			if(!myallocation.alloInArray(idno)){
	// 				myallocation.addAllo(idno,' ',0);
	// 			}
	// 			console.log(myallocation.arrayAllo);
	// 		});

	// 		delay(function(){
	//         	//$("#alloText").focus();//AlloTotal
	//         	myallocation.retickallotogrid();
	// 		}, 100 );
	// 	},
	// });
	
	// AlloSearch("#gridAllo",urlParamAllo);
	// function AlloSearch(grid,urlParam){
	// 	$("#alloText").on( "keyup", function() {
	// 		delay(function(){
	// 			search(grid,$("#alloText").val(),$("#alloCol").val(),urlParam);
	// 		}, 500 );
	// 	});

	// 	$("#alloCol").on( "change", function() {
	// 		search(grid,$("#alloText").val(),$("#alloCol").val(),urlParam);
	// 	});
	// }

	// function startEdit() {
 //        var ids = $("#gridAllo").jqGrid('getDataIDs');

 //        for (var i = 0; i < ids.length; i++) {
 //        	var entrydate = $("#gridAllo").jqGrid ('getRowData', ids[i]).entrydate;
 //        	$("#gridAllo").jqGrid('setCell', ids[i], 'NULL', moment(entrydate).format("DD-MMM"));
 //            $("#gridAllo").jqGrid('editRow',ids[i]);
 //        }
 //    };

	// addParamField('#gridAllo',false,urlParamAllo,['tick','amtpaid','amtbal']);

	// function Allocation(){
	// 	this.arrayAllo=[];
	// 	this.alloBalance=0;
	// 	this.alloTotal=0;
	// 	this.outamt=0;

	// 	this.renewAllo = function(os){
	// 		this.arrayAllo.length = 0;
	// 		this.alloTotal=0;
	// 		this.alloBalance=parseFloat(os);
	// 		this.outamt=parseFloat(os);

	// 		this.updateAlloField();
	// 	}
	// 	this.addAllo = function(idno,paid,bal){
	// 		var obj=getlAlloFromGrid(idno);
	// 		obj.amtpaid = paid;
	// 		obj.amtbal = bal;
	// 		var fieldID="#"+idno+"_amtpaid";
	// 		var self=this;

	// 		this.arrayAllo.push({idno:idno,obj:obj});
			
	// 		$(fieldID).on('change',[idno,self.arrayAllo],onchangeField);

	// 		this.updateAlloField();
	// 	}
	// 	function onchangeField(obj){
	// 		var idno = obj.handleObj.data[0];
	// 		var arrayAllo = obj.handleObj.data[1];
	// 		var alloIndex = getIndex(arrayAllo,idno);
	// 		var outamt = $("#gridAllo").jqGrid('getRowData', idno).outamount;
	// 		var newamtpaid = parseFloat(obj.target.value);
	// 		newamtpaid = isNaN(Number(newamtpaid)) ? 0 : parseFloat(obj.target.value);
	// 		if(parseFloat(newamtpaid)>parseFloat(outamt)){
	// 			alert("Amount paid exceed O/S amount");
	// 			$("#"+idno+"_amtpaid").addClass( "error" ).removeClass( "valid" );
	// 			obj.target.focus();
	// 			return false;
	// 		}
	// 		$("#"+idno+"_amtpaid").removeClass( "error" ).addClass( "valid" );
	// 		var balance = outamt - newamtpaid;

	// 		obj.target.value = numeral(newamtpaid).format('0,0.00');;
	// 		arrayAllo[alloIndex].obj.amtpaid = newamtpaid;
	// 		arrayAllo[alloIndex].obj.amtbal = balance;
	// 		setbal(idno,balance);

	// 		myallocation.updateAlloField();
	// 	}
	// 	function getIndex(array,idno){
	// 		var retval=0;
	// 		$.each(array, function( index, obj ) {
	// 			if(obj.idno==idno){
	// 				retval=index;
	// 				return false;//bila return false, skip .each terus pegi return retval
	// 			}
	// 		});
	// 		return retval;
	// 	}
	// 	this.deleteAllo = function(idno){
	// 		var self=this;
	// 		$.each(self.arrayAllo, function( index, obj ) {
	// 			if(obj.idno==idno){
	// 				self.arrayAllo.splice(index, 1);
	// 				return false;
	// 			}
	// 		});
	// 	}
	// 	this.alloInArray = function(idno){
	// 		var retval=false;
	// 		$.each(this.arrayAllo, function( index, obj ) {
	// 			if(obj.idno==idno){
	// 				retval=true;
	// 				return false;//bila return false, skip .each terus pegi return retval
	// 			}
	// 		});
	// 		return retval;
	// 	}
	// 	this.retickallotogrid = function(){
	// 		var self=this;
	// 		$.each(this.arrayAllo, function( index, obj ) {
	// 			$("#"+obj.idno+"_amtpaid").on('change',[obj.idno,self.arrayAllo],onchangeField);
	// 			if(obj.obj.amtpaid != " "){
	// 				$("#"+obj.idno+"_amtpaid").val(obj.obj.amtpaid).removeClass( "error" ).addClass( "valid" );
	// 				setbal(obj.idno,obj.obj.amtbal);
	// 			}
	// 		});
	// 	}
	// 	this.updateAlloField = function(){
	// 		var self=this;
	// 		this.alloTotal = 0;
	// 		$.each(this.arrayAllo, function( index, obj ) {
	// 			if(obj.obj.amtpaid != " "){
	// 				self.alloTotal += parseFloat(obj.obj.amtpaid);
	// 			}
	// 		});
	// 		this.alloBalance = this.outamt - this.alloTotal;

	// 		$("#AlloTotal").val(this.alloTotal);
	// 		$("#AlloBalance").val(this.alloBalance);
	// 		if(this.alloBalance<0){
	// 			$("#AlloBalance").addClass( "error" ).removeClass( "valid" );
	// 			alert("Balance cannot in negative values");
	// 		}else{
	// 			$("#AlloBalance").addClass( "valid" ).removeClass( "error" );
	// 		}
	// 		allocurrency.formatOn();
	// 	}

	// 	function updateAllo(idno,amtpaid,arrayAllo){
	// 		$.each(arrayAllo, function( index, obj ) {
	// 			if(obj.idno==idno){
	// 				obj.obj.amtpaid=amtpaid;
	// 				return false;//bila return false, skip .each terus pegi return retval
	// 			}
	// 		});
	// 	}

	// 	function getlAlloFromGrid(idno){
	// 		var temp=$("#gridAllo").jqGrid ('getRowData', idno);
	// 		return {idno:temp.idno,auditno:temp.auditno,amtbal:temp.amtbal,amtpaid:temp.amount};
	// 	}
	// }
	
	// function setbal(idno,balance){
	// 	$("#gridAllo").jqGrid('setCell', idno, 'amtbal', balance);
	// }
	
	// $("#gridAllo").jqGrid('navGrid','#pagerAllo',{	
	// 	view:false,edit:false,add:false,del:false,search:false,
	// 	beforeRefresh: function(){
	// 		refreshGrid("#gridAllo",urlParamAllo);
	// 	},
	// })

	// $('#allocate').click(function(){
	// 	$( "#allocateDialog" ).dialog( "open" );
	// });
	////////////////////////////////end allocation part/////////////////////////////////////

});
		
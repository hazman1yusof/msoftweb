$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	
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

	var mycurrency =new currencymode(['#f_tab-cash input[name=dbacthdr_amount]','#f_tab-cash input[name=dbacthdr_outamount]','#f_tab-cash input[name=dbacthdr_RCCASHbalance]','#f_tab-cash input[name=dbacthdr_RCFinalbalance]','#f_tab-card input[name=dbacthdr_amount]','#f_tab-card input[name=dbacthdr_outamount]','#f_tab-card input[name=dbacthdr_RCFinalbalance]','#f_tab-cheque input[name=dbacthdr_amount]','#f_tab-cheque input[name=dbacthdr_outamount]','#f_tab-cheque input[name=dbacthdr_RCFinalbalance]','#f_tab-debit input[name=dbacthdr_amount]','#f_tab-debit input[name=dbacthdr_outamount]','#f_tab-debit input[name=dbacthdr_RCFinalbalance]','#f_tab-debit input[name=dbacthdr_bankcharges]','#f_tab-forex input[name=dbacthdr_amount]','#f_tab-forex input[name=dbacthdr_amount2]','#f_tab-forex input[name=dbacthdr_RCFinalbalance]','#f_tab-forex input[name=dbacthdr_outamount]']);

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
			getCashBal(tabform);
			getOutBal(true,null,tabform);
		}else if(tabform=='#f_tab-card'||tabform=='#f_tab-cheque'||tabform=='#f_tab-forex'){
			getOutBal(false,null,tabform);
		}else if(tabform=='#f_tab-debit'){
			getOutBal(false,$(tabform+" input[name='dbacthdr_bankcharges']").val(),tabform);
		}
	}

	function amountchgOn(fromtab){
		$("input[name='dbacthdr_outamount']").prop( "readonly", false );
		$("input[name='dbacthdr_RCCASHbalance']").prop( "readonly", false );
		$("input[name='dbacthdr_RCFinalbalance']").prop( "readonly", false );
		$("input[name='dbacthdr_amount']").off('blur',amountFunction);
		// $("input[name='dbacthdr_outamount']").off('blur',amountFunction);
		$(tabform+" input[name='dbacthdr_amount']").on('blur',amountFunction);
		// $(tabform+" input[name='dbacthdr_outamount']").on('blur',amountFunction);
	}

	function amountchgOff(fromtab){
		mycurrency.formatOnBlur();
		$("input[name='dbacthdr_amount']").off('blur',amountFunction);
		// $("input[name='dbacthdr_outamount']").off('blur',amountFunction);
		$("input[name='dbacthdr_outamount']").prop( "readonly", true );
		$("input[name='dbacthdr_RCCASHbalance']").prop( "readonly", true );
		$("input[name='dbacthdr_RCFinalbalance']").prop( "readonly", true );
		$(tabform+" input[name='dbacthdr_amount']").on('blur',amountFunction);
	}

	function getCashBal(tabform){
		mycurrency.formatOff();
		var pay=parseFloat(numeral().unformat($(tabform+" input[name='dbacthdr_amount']").val()));
		var out=parseFloat(numeral().unformat($(tabform+" input[name='dbacthdr_outamount']").val()));
		var RCCASHbalance=(pay-out>0) ? pay-out : 0;

		$(tabform+" input[name='dbacthdr_RCCASHbalance']").val(RCCASHbalance);
		mycurrency.formatOn();
	}

	function getOutBal(iscash,bc,tabform){
		mycurrency.formatOff();
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
		mycurrency.formatOff();
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
			title:"Select Payer",
			open: function(){
				dialog_payercode.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_payercode.urlParam.filterVal=['ACTIVE', 'session.compcode']
			},
			close: function(){
				let data=selrowData('#'+dialog_payercode.gridname);
				get_debtorcode_outamount(data.debtorcode);

				if($('#dbacthdr_episno_div').is(":visible")){
					$('#dbacthdr_mrn').focus();
				}else{
					$('#dbacthdr_remark').focus();
				}

				if(data.debtorcode == 'ND0001'){
					$('#ND0001_case').show();
					dialog_category.on();
					dialog_categorydept.on();
				}else{
					$('#ND0001_case').hide();
					dialog_category.off();
					dialog_categorydept.off();
					$('#dbacthdr_categorydept').val('');
					$('#dbacthdr_category').val('');
				}
			}
		  },'urlParam','radio','tab'
		);
	dialog_payercode.makedialog(true);

	var dialog_mrn = new ordialog(
		'mrn','hisdb.pat_mast','#dbacthdr_mrn',errorField,
		{	colModel:[
				{label:'MRN',name:'MRN',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'HUKM MRN',name:'newmrn',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Name',name:'Name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Last Episode',name:'Episno',width:100,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','active'],
				filterVal:['session.compcode','1']
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
				dialog_mrn.urlParam.filterCol=['compcode','active'];
				dialog_mrn.urlParam.filterVal=['session.compcode','1'];
			}
		},'urlParam','radio','tab'
	);
	dialog_mrn.makedialog(true);

	var dbacthdr_quoteno = new ordialog(
		'quoteno','finance.salehdr','#dbacthdr_quoteno',errorField,
		{	colModel:[
				{label:'Auto No.',name:'idno',width:50,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'MRN',name:'mrn',width:100,classes:'pointer',canSearch:true},
				{label:'Quoteno',name:'quoteno',width:50,classes:'pointer',canSearch:true},
				{label:'Remark',name:'remark',width:400},
				{label:'Amount',name:'amount',width:200},
				{label:'Outstanding',name:'outamount',width:200},
			],
			urlParam: {
				url : "./receipt/table",
				action : 'get_quoteno',
				url_chk : "./receipt/table",
				action_chk : "get_quoteno_check",
				mrn : $('#dbacthdr_mrn').val(),
				filterCol:[],
				filterVal:[],
			},
			ondblClickRow:function(){
				let data=selrowData('#'+dbacthdr_quoteno.gridname);

				$('input[name="dbacthdr_outamount"]').val(data.outamount);
				// $('#apacthdr_actdate').focus();
				// $('#dbacthdr_mrn').val(data.MRN);
				// $('#dbacthdr_episno').val(data.Episno);
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
				dbacthdr_quoteno.urlParam.url = "./receipt/table";
				dbacthdr_quoteno.urlParam.action = 'get_quoteno';
				dbacthdr_quoteno.urlParam.url_chk = "./receipt/table";
				dbacthdr_quoteno.urlParam.action_chk = "get_quoteno_check";
				dbacthdr_quoteno.urlParam.mrn = $('#dbacthdr_mrn').val();
				dbacthdr_quoteno.urlParam.filterCol=[];
				dbacthdr_quoteno.urlParam.filterVal=[];
			}
		},'urlParam','radio','tab'
	);
	dbacthdr_quoteno.makedialog(true);

	var dialog_categorydept = new ordialog(
		'deptcode','sysdb.department','#dbacthdr_categorydept',errorField,
		{	colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_categorydept.gridname);
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
				dialog_categorydept.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_categorydept.urlParam.filterVal=['ACTIVE', 'session.compcode']
			},
			close: function(){
				$('#dbacthdr_category').focus();
			}
		  },'urlParam','radio','tab'
		);
	dialog_categorydept.makedialog(true);

	var dialog_category = new ordialog(
		'category','material.category','#dbacthdr_category',errorField,
		{	colModel:[
				{label:'category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus','source'],
				filterVal:['session.compcode','ACTIVE','RC']
			},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_category.gridname);
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
				dialog_category.urlParam.filterCol=['recstatus', 'compcode','source'],
				dialog_category.urlParam.filterVal=['ACTIVE', 'session.compcode','RC']
			},
			close: function(){
				$('#dbacthdr_remark').focus();
			}
		  },'urlParam','radio','tab'
		);
	dialog_category.makedialog(true);

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

	var dialog_allodebtor = new ordialog(
		'AlloDebtor','debtor.debtormast','#AlloDebtor',errorField,
		{	colModel:[
				{label:'Code',name:'debtorcode',width:100,classes:'pointer',canSearch:true,or_search:true},
				{label:'Name',name:'name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
				},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_allodebtor.gridname);
				$('#AlloDebtor').val(data.debtorcode);
				urlParamAllo.filterVal[0]=data.debtorcode;
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
			title:"Select MRN",
			open: function(){
				dialog_payercode.urlParam.filterCol=['compcode','recstatus'];
				dialog_payercode.urlParam.filterVal=['session.compcode','ACTIVE'];
				}
			},'urlParam','radio','tab'
		);
	dialog_allodebtor.makedialog(true);


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
			urlParam_sys.field=['source','trantype','description','hdrtype','updpayername','depccode','depglacc','updepisode','manualalloc'];
			urlParam_sys.filterCol=['compcode','recstatus'];
			urlParam_sys.filterVal=['session.compcode','ACTIVE'];
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
		filterCol:['source','trantype','compcode'],
		filterVal:['PB','RC','session.compcode']
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
			{label: 'manualalloc', name: 'manualalloc', width: 10, hidden:true},
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		width: 300,
		height: 80,
		rowNum: 10,
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
						dbacthdr_quoteno.on();
						// dialog_episode.handler(errorField);
					}
					if(rowData['updpayername'] == 1){
						$('#dbacthdr_payername').prop('readonly',false);
					}else{
						$('#dbacthdr_payername').prop('readonly',true);
					}

					if(rowData['updepisode'] == 1){
						$('#dbacthdr_episno_div').show();
					}else{
						$('#dbacthdr_episno_div').hide();
					}
				}else{
					$('#dbacthdr_payername').prop('readonly',true);
					$("input:hidden[name='dbacthdr_hdrtype']").val('RC');
					$("input:hidden[name='updpayername'],input:hidden[name='updepisode']").val('');
					dialog_mrn.off();
					dbacthdr_quoteno.off();
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

	addParamField('#sysparam',true,urlParam_sys,['hdrtype','updpayername','depccode','depglacc','updepisode','manualalloc']);
	/////////////////////////////////////////End Transaction typr////////////////////////////

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
	function saveFormdata_receipt(grid,dialog,form,oper,saveParam,urlParam,callback,uppercase=true){
		var formname = $("a[aria-expanded='true']").attr('form')
		
		var paymentform =  $( formname ).serializeArray();
		$("#main_save_btn").button("option", "disabled", true );
		saveParam.oper=oper;
		
		let serializedForm = trimmall(form,uppercase);
		$.post( saveParam.url+'?'+$.param(saveParam), serializedForm+'&'+$.param(paymentform) , function( data ) {
			
		}).fail(function(data) {
			errorText(dialog.substr(1),data.responseText);
			$("#main_save_btn").button("option", "disabled", false );
		}).success(function(data){
			if(grid!=null){
				if($("#dbacthdr_trantype").val() == 'RC'){
					$("#jqGrid").data('need_allocate','1');
				}
				refreshGrid(grid,urlParam,oper);
				$("#main_save_btn").button("option", "disabled", false );
				$(dialog).dialog('close');
				if (callback !== undefined) {
					callback();
				}
			}
		});
	}
	
	var butt1=[{
		text: "Save",id: "main_save_btn",click: function() {
			mycurrency.formatOff();
			mycurrency.check0value(errorField);
			if( $('#formdata').isValid({requiredFields: ''}, conf, true) && $(tabform).isValid({requiredFields: ''}, conf, true) ) {
				saveFormdata_receipt("#jqGrid","#dialogForm","#formdata",oper,saveParam,urlParam);
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
	
	$('.nav-tabs a').on('shown.bs.tab', function(e){
		tabform=$(this).attr('form');
		rdonly(tabform);
		handleAmount();
		// mycurrency.formatOnBlur();
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
			case '#f_tab-forex':
				refreshGrid("#g_forex",urlParam4);
				break;
		}
		$("#g_paymodecard").jqGrid ('setGridWidth', $("#g_paymodecard_c")[0].clientWidth);
		$("#g_paymodebank").jqGrid ('setGridWidth', $("#g_paymodebank_c")[0].clientWidth);
		$("#g_forex").jqGrid ('setGridWidth', $("#g_forex_c")[0].clientWidth);
	});
	
	$("#dialogForm")
		.dialog({
			width: 9/10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function( event, ui ) {
				////// Popup login //////
				// var bootboxHtml = $('#LoginDiv').html().replace('LoginForm', 'LoginBootboxForm');
				
				// bootbox.confirm(bootboxHtml, function(result) {
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
					case 'add':
						// mycurrency.formatOnBlur();
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
						if(selrowData('#jqGrid').dbacthdr_payercode == 'ND0001'){
							$('#ND0001_case').show();
							dialog_category.check();
							dialog_categorydept.check();
						}else{
							$('#ND0001_case').hide();
							dialog_category.off();
							dialog_categorydept.off();
							$('#dbacthdr_categorydept').val('');
							$('#dbacthdr_category').val('');
						}
						
						// switch(selrowData('#jqGrid').dbacthdr_paytype) {
						// 	case '#f_tab-card':
						// 		urlParam_card.filterVal[3]=selrowData('#jqGrid').dbacthdr_paymode;
						// 		refreshGrid("#g_paymodecard",urlParam_card);
						// 		break;
						// 	case '#f_tab-debit':
						// 		urlParam_bank.filterVal[3]=selrowData('#jqGrid').dbacthdr_paymode;
						// 		refreshGrid("#g_paymodebank",urlParam_bank);
						// 		break;
						// 	case '#f_tab-forex':
						// 		refreshGrid("#g_forex",urlParam4);
						// 		break;
						// }
						// break;
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

				$('#ND0001_case').hide();
				dialog_category.off();
				dialog_categorydept.off();
				$('#dbacthdr_categorydept').val('');
				$('#dbacthdr_category').val('');
							
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
		fixPost: true
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
			{ label: 'Trantype', name: 'dbacthdr_trantype', width: 45, formatter: showdetail, unformat:un_showdetail },
			{ label: 'Type', name: 'dbacthdr_PymtDescription', classes: 'wrap', width: 50, hidden:true}, //tunjuk
			{ label: 'Receipt No.', name: 'dbacthdr_recptno', classes: 'wrap',width: 60, canSearch:true }, //tunjuk
			{ label: 'Date', name: 'dbacthdr_entrydate',width: 40,formatter: dateFormatter, unformat: dateUNFormatter, hidden:true },
			{ label: 'entrydate', name: 'dbacthdr_entrytime', hidden: true },
			{ label: 'entrydate', name: 'dbacthdr_entryuser', hidden: true },
			{ label: 'Payer Code', name: 'dbacthdr_payercode', width: 100, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail },
			{ label: 'Payer Name', name: 'dbacthdr_payername', width: 150, classes: 'wrap text-uppercase', hidden: true },//tunjuk
			// { label: 'Debtor Code', name: 'dbacthdr_debtorcode', width: 400, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail },
			{ label: 'MRN', name: 'dbacthdr_mrn',align:'right', width: 50 }, //tunjuk
			{ label: 'Epis', name: 'dbacthdr_episno',align:'right', width: 40 }, //tunjuk
			{ label: 'Patient Name', name: 'name', width: 150, classes: 'wrap', hidden: true },
			{ label: 'remark', name: 'dbacthdr_remark', hidden: true },
			{ label: 'epistype', name: 'dbacthdr_epistype', hidden: true },
			{ label: 'cbflag', name: 'dbacthdr_cbflag', hidden: true },
			{ label: 'reference', name: 'dbacthdr_reference', hidden: true },
			{ label: 'Payment Mode', name: 'dbacthdr_paymode', width: 70, classes: 'wrap text-uppercase', formatter: showdetail, unformat:un_showdetail },	//tunjuk
			{ label: 'Expiry Date', name: 'dbacthdr_expdate', width: 50, align:'right',
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
			{ label: 'quoteno', name: 'dbacthdr_quoteno', hidden: true },
			{ label: 'paytype', name: 'dbacthdr_paytype', hidden: true },
			{ label: 'RCcashbalance', name: 'dbacthdr_RCCASHbalance', hidden: true },
			{ label: 'RCFinalbalance', name: 'dbacthdr_RCFinalbalance', hidden: true },
			{ label: 'RCOSbalance', name: 'dbacthdr_RCOSbalance', hidden: true },
			{ label: 'idno', name: 'dbacthdr_idno', key:true, hidden: true },
			{ label: 'paycard_description', name: 'paycard_description', hidden: true },
			{ label: 'paybank_description', name: 'paybank_description', hidden: true },
			{ label: 'dbacthdr_category', name: 'dbacthdr_category', hidden: true },
			{ label: 'dbacthdr_categorydept', name: 'dbacthdr_categorydept', hidden: true },
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
		pager: "#jqGridPager",
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='View Selected Row']").click();
		},
		onSelectRow: function(rowid){
			// allocate("#jqGrid");
			
			$("#pdfgen1").attr('href','./receipt/showpdf?auditno='+selrowData("#jqGrid").dbacthdr_idno);
		},
		gridComplete: function(){
			// $('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
			fdl.set_array().reset();
			if(oper == 'add' || oper == null || $("#jqGrid").jqGrid('getGridParam', 'selrow') == null){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			
			$('#'+$("#jqGrid").jqGrid ('getGridParam', 'selrow')).focus();
			enabledPill();
			
			let allocate = $("#jqGrid").data('need_allocate');
			if(allocate!=undefined && allocate=='1'){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
				$('#allocate').click();
				$("#jqGrid").data('need_allocate','0');
			}
		},
		loadComplete:function(data){
			calc_jq_height_onchange("jqGrid");
		}
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
				// var expdate = selrowData("#jqGrid").dbacthdr_expdate;
				// var datearray = expdate.split("/");
				
				// var newexpdate = datearray[1] + '-' + datearray[0];
				// $("#dbacthdr_expdate").val(newexpdate);
				
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
			resetpill();
			if(selform!=''){
				$(".nav-tabs a[form='"+selform.toLowerCase()+"']").tab('show');
				disabledPill();
				populateFormdata("#jqGrid","",selform.toLowerCase(),selRowId,'view',['dbacthdr_expdate']);
			}else{
				$(".nav-tabs a[form='#f_tab-cash']").tab('show');
			}
			var expdate = selrowData("#jqGrid").dbacthdr_expdate;
			var datearray = expdate.split("/");
			
			var newexpdate = datearray[1] + '-' + datearray[0];
			$("#dbacthdr_expdate").val(newexpdate);
			
			// if(selrowData('#jqGrid').dbacthdr_paytype == "#F_TAB-DEBIT"){
			// 	urlParam_bank.filterVal[3]=selrowData('#jqGrid').dbacthdr_paymode;
			// 	refreshGrid("#g_paymodebank",urlParam_bank);
			// }else if(selrowData('#jqGrid').dbacthdr_paytype == "#F_TAB-CARD"){
			// 	urlParam_card.filterVal[3]=selrowData('#jqGrid').dbacthdr_paymode;
			// 	refreshGrid("#g_paymodecard",urlParam_card);
			// }
			
			populateFormdata("#jqGrid","","#formdata",selRowId,'view');
			$("#dialogForm").dialog( "open" );
			// $("#g_paycard_c, #g_paybank_c").show();
			// $("#g_paymodecard_c, #g_paymodebank_c").hide();
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-plus", 
		title:"Add New Row", 
		onClickButton: function(){
			oper='add';
			resetpill();
			$('#dbacthdr_recptno').hide();
			$( "input:radio[name='optradio'][value='receipt']" ).prop( "checked", true );
			$( "input:radio[name='optradio'][value='receipt']" ).change();
			// $("#formdata input[name='dbacthdr_tillcode']").val(def_tillcode);	
			// $("#formdata input[name='dbacthdr_tillno']").val(def_tillno);
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
			mycurrency.formatOn();
		});
	}

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field,table, case_;
		switch(options.colModel.name){
			case 'dbacthdr_payercode':field=['debtorcode','name'];table="debtor.debtormast";case_='dbacthdr_payercode';break;
			case 'dbacthdr_debtorcode':field=['debtorcode','name'];table="debtor.debtormast";case_='dbacthdr_debtorcode';break;
			case 'dbacthdr_paymode':field=['paymode','description'];table="debtor.paymode";case_='dbacthdr_paymode';break;
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
		});
		searchClick2('#jqGrid', '#searchForm', urlParam);
	}

	$('#Scol').on('change', whenchangetodate);
	$('#docudate_search').on('click', searchDate);

	function whenchangetodate() {
		urlParam.fromdate=urlParam.todate=null;
		payer_search.off();
		$('#payer_search, #docuDate_from, #docuDate_to').val('');
		$('#payer_search_hb').text('');
		$("input[name='Stext'],#actdate_text,#payer_text").hide();
		removeValidationClass(['#payer_search']);
		if ($('#Scol').val() == 'dbacthdr_entrydate'){
			$("#actdate_text").show();
		}else if($('#Scol').val() == 'dbacthdr_payercode'){
			$("#payer_text").show("fast");
			payer_search.on();
		}else{
			$("input[name='Stext']").show("fast");
		}
	}
	function searchDate(){
		urlParam.fromdate = $('#docudate_from').val();
		urlParam.todate = $('#docudate_to').val();
		refreshGrid('#jqGrid',urlParam);
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

				if($('#Scol').val() == 'dbacthdr_payercode'){
					urlParam.searchCol=["dbacthdr_payercode"];
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
				payer_search.urlParam.filterCol = ['compcode','recstatus'];
				payer_search.urlParam.filterVal = ['session.compcode','ACTIVE'];
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
	
	var myallocation = new Allocation();
	var allocurrency = new currencymode(["#AlloBalance","#AlloTotal"]);
	
	$( "#allocateDialog" ).dialog({
		autoOpen: false,
		width: 9/10 * $(window).width(),
		modal: true,
		open: function(){
			dialog_allodebtor.on();
			$("#gridAllo").jqGrid ('setGridWidth', Math.floor($("#gridAllo_c")[0].offsetWidth-$("#gridAllo_c")[0].offsetLeft));
			grid='#jqGrid';
			$('#AlloDtype').val(selrowData(grid).dbacthdr_trantype);
			$('#AlloDtype2').html(selrowData(grid).dbacthdr_PymtDescription);
			$('#AlloDno').val(selrowData(grid).dbacthdr_recptno);
			$('#AlloDebtor').val(selrowData(grid).dbacthdr_payercode);
			$('#AlloDebtor2').html(selrowData(grid).dbacthdr_payername);
			$('#AlloPayer').val(selrowData(grid).dbacthdr_payercode);
			$('#AlloPayer2').html(selrowData(grid).dbacthdr_payername);
			$('#AlloAmt').val(selrowData(grid).dbacthdr_amount);
			$('#AlloOutamt').val(selrowData(grid).dbacthdr_outamount);
			$('#AlloBalance').val(selrowData(grid).dbacthdr_outamount);
			$('#AlloTotal').val(0);
			$('#AlloAuditno').val(selrowData(grid).dbacthdr_auditno);
			urlParamAllo.filterVal[0]=selrowData(grid).dbacthdr_payercode;
			refreshGrid("#gridAllo",urlParamAllo);
			parent_close_disabled(true);
			myallocation.renewAllo(selrowData(grid).dbacthdr_outamount);
		},
		close: function( event, ui ){
			dialog_allodebtor.off();
			parent_close_disabled(false);
		},
		buttons:
			[{
				text: "Save",id: "allocate_save_btn",click: function() {
					if( parseFloat($("#AlloBalance").val())<0){
						alert("Balance cannot in negative values");
					}else if(myallocation.allo_error.length>0){
						alert("Amount paid exceed O/S amount");
					}else{
						$( "#allocate_save_btn" ).button( "option", "disabled", true );
						var obj={
							allo: myallocation.arrayAllo
						}
						
						var saveParam={
							action: 'receipt_save',
							url: 'receipt/form',
							oper: 'allocate',
							debtorcode: $('#AlloDebtor').val(),
							payercode: $('#AlloPayer').val(),
							_token: $('#csrf_token').val(),
							auditno: $('#AlloAuditno').val(),
							trantype: $('#AlloDtype').val(),
						}
						
						$.post( saveParam.url+'?'+$.param(saveParam), obj , function( data ) {
							
						}).fail(function(data) {
							$( "#allocate_save_btn" ).button( "option", "disabled", false );
						}).success(function(data){
							refreshGrid('#jqGrid', urlParam);
							$( "#allocate_save_btn" ).button( "option", "disabled", false );
							$('#allocateDialog').dialog('close');
						});
					}
				}
			},{
				text: "Cancel",click: function() {
					$(this).dialog('close');
				}
			}],
	});
	
	var urlParamAllo={
		action:'get_table_default',
		url: 'util/get_table_default',
		field:'',
		table_name:'debtor.dbacthdr',
		table_id:'idno',
		sort_idno:true,
		filterCol:['payercode','source','recstatus','outamount'],
		filterVal:['','PB','POSTED','>.0'],
		WhereInCol:['trantype'],
        WhereInVal:[['DN','IN']]
	}

	$("#gridAllo").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'idno', width: 40, hidden: true}, 
			{ label: 'Document No', name: 'auditno', width: 40},
			{ label: 'Document Date', name: 'entrydate', width: 50},
			{ label: 'MRN', name: 'mrn', width: 50},
			{ label: 'EpisNo', name: 'episno', width: 50},
			{ label: 'Src', name: 'source', width: 20, hidden: true}, 
			{ label: 'Type', name: 'trantype', width: 20 , hidden: true},
			{ label: 'Line No', name: 'lineno_', width: 20 , hidden: true},
			// { label: 'Batchno', name: 'NULL', width: 40},
			{ label: 'Amount', name: 'amount',formatter:'currency', width: 50},
			{ label: 'O/S Amount', name: 'outamount',formatter:'currency', width: 50},
			{ label: ' ', name: 'tick', width: 20, editable: true, edittype:"checkbox", align:'center'},
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

			calc_jq_height_onchange("gridAllo");
		},
	});

	AlloSearch("#gridAllo",urlParam2);
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
        	var entrydate = $("#gridAllo").jqGrid ('getRowData', ids[i]).entrydate;
        	$("#gridAllo").jqGrid('setCell', ids[i], 'NULL', moment(entrydate).format("DD-MMM"));
            $("#gridAllo").jqGrid('editRow',ids[i]);
        }
    };

	addParamField('#gridAllo',false,urlParamAllo,['tick','amtpaid','amtbal']);

	function Allocation(){
		this.arrayAllo=[];
		this.alloBalance=0;
		this.alloTotal=0;
		this.outamt=0;
		this.allo_error=[];

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
			
			$(fieldID).on('change',[idno,self.arrayAllo,self.allo_error],onchangeField);

			this.updateAlloField();
		}
		function onchangeField(obj){
			var idno = obj.handleObj.data[0];
			var arrayAllo = obj.handleObj.data[1];
			var allo_error = obj.handleObj.data[2];

			var alloIndex = getIndex(arrayAllo,idno);
			var outamt = $("#gridAllo").jqGrid('getRowData', idno).outamount;
			var newamtpaid = parseFloat(obj.target.value);
			newamtpaid = isNaN(Number(newamtpaid)) ? 0 : parseFloat(obj.target.value);
			if(parseFloat(newamtpaid)>parseFloat(outamt)){
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
			var retval=0;
			$.each(array, function( index, obj ) {
				if(obj.idno==idno){
					retval=index;
					return false;//bila return false, skip .each terus pegi return retval
				}
			});
			return retval;
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
			this.alloBalance = this.outamt - this.alloTotal;

			$("#AlloTotal").val(this.alloTotal);
			$("#AlloBalance").val(this.alloBalance);
			if(this.alloBalance<0){
				$("#AlloBalance").addClass( "error" ).removeClass( "valid" );
				alert("Balance cannot in negative values");
			}else{
				$("#AlloBalance").addClass( "valid" ).removeClass( "error" );
			}
			allocurrency.formatOn();
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

		function adderror_allo(array,idno){
			if($.inArray(idno,array)===-1){//xjumpa
				array.push(idno);
			}
		}

		function delerror_allo(array,idno){
			if($.inArray(idno,array)!==-1){//jumpa
				array.splice($.inArray(idno,array), 1);
			}
		}
	}
	
	function setbal(idno,balance){
		$("#gridAllo").jqGrid('setCell', idno, 'amtbal', balance);
	}
	
	$("#gridAllo").jqGrid('navGrid','#pagerAllo',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#gridAllo",urlParamAllo);
		},
	})

	
	$('#allocate').click(function(){
		var outamt = parseFloat(selrowData("#jqGrid").dbacthdr_outamount);
		if(outamt > 0){
			$( "#allocateDialog" ).dialog( "open" );
		}
	});
	////////////////////////////////end allocation part/////////////////////////////////////

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

function resetpill(){
	$('#dialogForm ul.nav-tabs li').removeClass('active');
	$('#dialogForm ul.nav-tabs li a').attr('aria-expanded',false);
}
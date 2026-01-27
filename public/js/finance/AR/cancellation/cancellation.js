$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
    $("body").show();
    
    var tabform="#f_tab-cash";
    /////////////////////////////////validation/////////////////////////////////
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
	
	$('#jqGrid_cancel_c .nav-tabs a').on('shown.bs.tab', function(e){
		let trantype = $(this).data('trantype');
		switch(trantype){
			case 'RC':
				refreshGrid('#jqGrid_rc', urlParam_rcpt);
				$("#jqGrid_rc").jqGrid ('setGridWidth', Math.floor($("#tab-rc")[0].offsetWidth-$("#tab-rc")[0].offsetLeft));
				break;
			case 'RD':
				refreshGrid("#jqGrid_rd", urlParam_rd);
				$("#jqGrid_rd").jqGrid ('setGridWidth', Math.floor($("#tab-rd")[0].offsetWidth-$("#tab-rd")[0].offsetLeft));
				break;
			case 'RF':
				refreshGrid("#jqGrid_rf",urlParam_rf);
				$("#jqGrid_rf").jqGrid ('setGridWidth', Math.floor($("#tab-rf")[0].offsetWidth-$("#tab-rf")[0].offsetLeft));
				break;
		}
	});
	
	/////////////////////////////////currency/////////////////////////////////
	var mycurrency = new currencymode(['#db_outamount', '#db_amount']);
	var mycurrency2 = new currencymode(['#db_outamount', '#db_amount']);
	var myallocation = new Allocation();	//Refund
	var fdl = new faster_detail_load();
	
	///////////////////for handling amount based on trantype///////////////////
    /////////////////////////////////RC STARTS/////////////////////////////////
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
	//////////////////////////////////RC ENDS//////////////////////////////////
	
	//////////////////////////////////RF STARTS//////////////////////////////////
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
	//////////////////////////////////RF ENDS//////////////////////////////////
    ///////////////////end handling amount based on trantype///////////////////
    
	////////////////////////////////saveFormdata////////////////////////////////
	function saveFormdata_receipt(grid,dialog,form,oper,saveParam,urlParam,obj,callback,uppercase=true){
		var formname = $("a[aria-expanded='true']").attr('form');
		
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
			
			$("#formdata_RF input[name='dbacthdr_drcostcode']").val(data.rows[0].ccode);
			$("#formdata_RF input[name='dbacthdr_dracc']").val(data.rows[0].glaccno);
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
    
    //////////////////////////////start dialogForm//////////////////////////////
	/////////////////////////////////RC STARTS/////////////////////////////////
	$('#dialogForm_RC .nav-tabs a').on('shown.bs.tab', function(e){
		tabform=$(this).attr('form');
		rdonly(tabform);
		handleAmount();
		$('#dbacthdr_paytype').val(tabform);
		switch(tabform) {
			case '#f_tab-cash':
				getcr('CASH');
				break;
			case '#f_tab-card':
				urlParam_card.filterVal[3]=selrowData('#jqGrid_rc').db_paymode;
				refreshGrid("#g_paymodecard",urlParam_card);
				break;
			case '#f_tab-cheque':
				getcr('cheque');
				break;
			case '#f_tab-debit':
				urlParam_bank.filterVal[3]=selrowData('#jqGrid_rc').db_paymode;
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
			open: function( event, ui ) {
				////// Popup login //////
				// var bootboxHtml = $('#LoginDiv').html().replace('LoginForm', 'LoginBootboxForm');
				
				// bootbox.confirm(bootboxHtml, function(result) {
				//     console.log($('#ex1', '.LoginBootboxForm').val());
				//     console.log($('#till_tillcode','#description','#till_dept','#tillstatus','#defopenamt', '.LoginBootboxForm').val());
				// });
				////// End Popup login //////
				
				parent_close_disabled(true);
				dialog_payercode.off();
				
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
						disableForm('#formdata_RC',['cancel_remark_RC']);
						disableForm(selrowData('#jqGrid_rc').db_paytype);
						// $(this).dialog("option", "buttons",butt2);
						
						// switch(selrowData('#jqGrid_rc').db_paytype) {
						// 	case state = '#f_tab-card':
						// 		urlParam_card.filterVal[3]=selrowData('#jqGrid_rc').db_paymode;
						// 		refreshGrid("#g_paymodecard",urlParam_card);
						// 		break;
						// 	case state = '#f_tab-debit':
						// 		urlParam_bank.filterVal[3]=selrowData('#jqGrid_rc').db_paymode;
						// 		refreshGrid("#g_paymodebank",urlParam_bank);
						// 		break;
						// 	case state = '#f_tab-forex':
						// 		refreshGrid("#g_forex",urlParam4_rc);
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
					// dialog_logintillcode.check(errorField);
					// dialog_payercode.check(errorField);
					showingForCash(selrowData("#jqGrid_rc").db_amount,selrowData("#jqGrid_rc").db_outamount,selrowData("#jqGrid_rc").db_RCCASHbalance,selrowData("#jqGrid_rc").db_RCFinalbalance,selrowData("#jqGrid_rc").db_paytype);
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
					// $(this).dialog("option", "buttons",butt1);
				}
			},
			buttons: [
				{
					text: "Cancel", click: function() {
						if($("#trantype").val() == 'RC'){
							var idno = selrowData("#jqGrid_rc").db_idno;
						}else if($("#trantype").val() == 'RD'){
							var idno = selrowData("#jqGrid_rd").db_idno;
						}
						
						bootbox.confirm({
							message: "Are you sure you want to cancel?",
							buttons: { confirm: { label: 'Yes', className: 'btn-success' }, cancel: { label: 'No', className: 'btn-danger' } },
							callback: function (result) {
								if(result == true){

									if($('#cancel_remark_RC').val() == ''){
										alert('Please fill on the cancel remark!');
										$('#cancel_remark_RC').focus();
									}else{
										var urlparam={
											oper: 'cancel_receipt',
											idno: idno,
										}
										
										var postobj={
											_token: $('#csrf_token').val(),
											cancelled_remark: $('#cancel_remark_RC').val()
										};
										
										$.post( "./cancellation/form?"+$.param(urlparam), $.param(postobj), function( data ) {
											
										},'json').fail(function(data) {
											if(data.responseText = 'recon'){
												bootbox.alert({
												    size: 'small',
												    title: 'Cannot cancel Receipt',
												    message: `
												    	<ul>
														  <li>Receipt has been done in Bank Recon</li>
														  <li>Please do unrecon</li>
														</ul>
												    	`,
												    callback: function () { 
												        /* your callback code */ 
												    }
												});
											}else{
												alert(data.responseText);
											}

											console.log(data);
										}).success(function(data){
											$("#dialogForm_RC").dialog('close');
											
											if($("#trantype").val() == 'RC'){
												refreshGrid('#jqGrid_rc', urlParam_rcpt);
											}else if($("#trantype").val() == 'RD'){
												refreshGrid('#jqGrid_rd', urlParam_rd);
											}
										});
									}

									
								}else{
									// refreshGrid('#jqGrid_rc', urlParam_rcpt);
								}
							}
						});
					}
				},{
					text: "Close",click: function() {
						$(this).dialog('close');
					}
				}
			],
		});
	
	$("#dialog_allocation").dialog({
		autoOpen: false,
		width: 9/10 * $(window).width(),
		modal: true,
		open: function(){
			$("#jqGridAlloc").jqGrid ('setGridWidth', Math.floor($("#gridAlloc_c")[0].offsetWidth-$("#gridAlloc_c")[0].offsetLeft));
			if($("#trantype").val() == 'RC'){
				grid='#jqGrid_rc';
			}else if($("#trantype").val() == 'RD'){
				grid='#jqGrid_rd';
			}
			urlParamAlloc.idno=selrowData(grid).db_idno;
			urlParamAlloc.auditno=selrowData(grid).db_auditno;
			refreshGrid("#jqGridAlloc",urlParamAlloc);
			parent_close_disabled(true);
		},
		close: function( event, ui ){
			parent_close_disabled(false);
		},
		buttons: [
			// {
			// 	text: "Save",click: function() {
			// 		var obj={
			// 			allo:myallocation.arrayAllo
			// 		}
					
			// 		var saveParam={
			// 			action: 'receipt_save',
			// 			url: 'receipt/form',
			// 			oper: 'allocate',
			// 			debtorcode: $('#AlloDebtor').val(),
			// 			payercode: $('#AlloPayer').val(),
			// 			_token: $('#csrf_token').val(),
			// 			auditno: $('#AlloAuditno').val()
			// 		}
					
			// 		$.post( saveParam.url+'?'+$.param(saveParam), obj , function( data ) {
						
			// 		}).fail(function(data) {
			// 		}).success(function(data){
			// 			refreshGrid('#jqGrid', urlParam);
			// 			$('#dialog_allocation').dialog('close');
			// 		});
			// 	}
			// },
			{
				text: "Close",click: function() {
					$(this).dialog('close');
				}
			}
		],
	});
	//////////////////////////////////RC ENDS//////////////////////////////////
	
	/////////////////////////////////RF STARTS/////////////////////////////////
	$("#dialogForm_RF")
		.dialog({
			width: 9/10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function( event, ui ) {
				$("#gridAllo").jqGrid ('setGridWidth', $("#gridAllo_c")[0].clientWidth);
				$("#g_paymodebank").jqGrid ('setGridWidth', $("#g_paymodebank_c")[0].clientWidth);
				$("#g_paymodecard").jqGrid ('setGridWidth', $("#g_paymodecard_c")[0].clientWidth);
				parent_close_disabled(true);
				dialog_payercode.off();
				// amountchgOnRF();
				
				// $('.nav-tabs a').on('shown.bs.tab', function(e){
				// 	tabform=$(this).attr('form');
				// 	rdonly(tabform);
				// 	$('#db_paytype').val(tabform);
				// 	switch(tabform) {
				// 		case state = '#f_tab-cash':
				// 			getcr('CASH');
				// 			break;
				// 		case state = '#f_tab-card':
				// 			$("#g_paymodecard").jqGrid ('setGridWidth', $("#g_paymodecard_c")[0].clientWidth);
				// 			refreshGrid("#g_paymodecard",urlParam3_rc);
				// 			break;
				// 		case state = '#f_tab-cheque':
				// 			getcr('cheque');
				// 			break;
				// 		case state = '#f_tab-debit':
				// 			$("#g_paymodebank").jqGrid ('setGridWidth', $("#g_paymodebank_c")[0].clientWidth);
				// 			refreshGrid("#g_paymodebank",urlParam2_rc);
				// 			break;
				// 	}
				// });
				// switch(oper) {
				// 	case state = 'add':
				// 		mycurrency.formatOnBlur();
				// 		$('#dbacthdr_paytype').val(tabform);
				// 		$( this ).dialog( "option", "title", "Add" );
				// 		enableForm('#formdata_RF');
				// 		enableForm('.tab-content');
				// 		rdonly('#formdata_RF');
				// 		rdonly(tabform);
				// 		break;
				// 	case state = 'edit':
				// 		$( this ).dialog( "option", "title", "Edit" );
				// 		enableForm('#formdata_RF');
				// 		frozeOnEdit("#dialogForm_RF");
				// 		rdonly('#formdata_RF');
				// 		break;
				// 	case state = 'view':
						mycurrency.formatOn();
						$( this ).dialog( "option", "title", "View" );
						disableForm('#formdata_RF');
						disableForm(selrowData('#jqGrid_rf').db_paytype);
						// $(this).dialog("option", "buttons",butt2);
						
						resetpill('#dialogForm_RF');
						$("#dialogForm_RF .nav-tabs a[form='"+selrowData('#jqGrid_rf').db_paytype.toLowerCase()+"']").tab('show');
						dialog_payercode.check('errorField');
						disabledPill();
						
						switch(selrowData('#jqGrid_rf').db_paytype.toLowerCase()){
							case '#f_tab-card':
								refreshGrid("#g_paymodecard",urlParam3_rc);
								break;
							case '#f_tab-debit':
								refreshGrid("#g_paymodebank",urlParam2_rc);
								break;
						}
				// }
				// if(oper!='view'){
				// 	dialog_payercode.off();
				// 	myallocation.renewAllo(0);
				// }
				// if(oper!='add'){
				// 	dialog_payercode.check(errorField);
				// 	showingForCash(selrowData("#jqGrid_rf").dbacthdr_amount,selrowData("#jqGrid_rf").dbacthdr_outamount,selrowData("#jqGrid_rf").dbacthdr_RCCASHbalance,selrowData("#jqGrid_rf").dbacthdr_RCFinalbalance,selrowData("#jqGrid_rf").dbacthdr_paytype);
				// }
			},
			close: function( event, ui ) {
				// amountchgOffRF();
				// parent_close_disabled(false);
				// emptyFormdata(errorField,'#formdata_RF');
				// emptyFormdata(errorField, "#f_tab-cash");
				// emptyFormdata(errorField, "#f_tab-card");
				// emptyFormdata(errorField, "#f_tab-cheque");
				// emptyFormdata(errorField, "#f_tab-debit");
				// $('.alert').detach();
				// $("#formdata_RF a").off();
				// $("#refresh_jqGrid").click();
				if(oper=='view'){
					// $(this).dialog("option", "buttons",butt1);
				}
			},
			buttons: [
				{
					text: "Cancel", click: function() {
						var idno = selrowData("#jqGrid_rf").db_idno;
						// var idno_alloc = selrowData("#gridAllo").idno_alloc;
						
						bootbox.confirm({
							message: "Are you sure you want to cancel?",
							buttons: { confirm: { label: 'Yes', className: 'btn-success' }, cancel: { label: 'No', className: 'btn-danger' } },
							callback: function (result) {
								if(result == true){
									var urlparam={
										oper: 'cancel_refund',
										idno: idno,
										// idno_alloc: idno_alloc,
									}
									
									var postobj={
										_token: $('#csrf_token').val(),
									};
									
									$.post( "./cancellation/form?"+$.param(urlparam), $.param(postobj), function( data ) {
										
									},'json').fail(function(data) {
										// alert('there is an error');
										console.log(data);
										alert(data.responseText);
									}).success(function(data){
										$("#dialogForm_RF").dialog('close');
										refreshGrid("#jqGrid_rf",urlParam_rf);
									});
								}else{
									// refreshGrid("#jqGrid_rf",urlParam_rf);
								}
							}
						});
					}
				},{
					text: "Close",click: function() {
						$(this).dialog('close');
					}
				}
			],
		});
	
	///allocation///
	var urlParamAllo={
		action: 'refund_allo_table',
		oper: 'view',
		auditno: 0,
		url: 'refund/table',
		payercode: ''
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
			// startEdit();
			// $("#gridAllo_c input[type='checkbox']").on('click',function(){
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
			// $("#gridAllo_c input[type='text'][rowid]").on('click',function(){
			// 	var idno = $(this).attr("rowid");
			// 	if(!myallocation.alloInArray(idno)){
			// 		myallocation.addAllo(idno,' ',0);
			// 	}
			// });
			
			// delay(function(){
			// 	//$("#alloText").focus();//AlloTotal
			// 	myallocation.retickallotogrid();
			// }, 100 );
		},
	});
	
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
			var entrydate = $("#gridAllo").jqGrid ('getRowData', ids[i]).entrydate;
			$("#gridAllo").jqGrid('setCell', ids[i], 'NULL', moment(entrydate).format("DD-MMM"));
			$("#gridAllo").jqGrid('editRow',ids[i]);
		}
	};
	
	$("#gridAllo").jqGrid('navGrid','#pagerAllo',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#gridAllo",urlParamAllo);
		},
	})
	
	function get_debtorcode_outamountRF(payercode){
		var param={
			url: './refund/table',
			action: 'get_debtorcode_outamount',
			payercode: payercode
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
	//////////////////////////////////RF ENDS//////////////////////////////////
    /////////////////////////////////end dialog/////////////////////////////////
    
    ///////////////////////////////////padzero///////////////////////////////////
	function padzero(cellvalue, options, rowObject){
		let padzero = 8, str="";
		while(padzero>0){
			str=str.concat("0");
			padzero--;
		}
		return pad(str, cellvalue, true);
	}
	
	function unpadzero(cellvalue, options, rowObject){
		return cellvalue.substring(cellvalue.search(/[1-9]/));
	}
	
	/////////////////////////////////////parameter for jqGrid_rc url/////////////////////////////////////
	var urlParam_rcpt={
		action: 'get_jqGrid_rc',
		url: './cancellation/table',
		// source: $('#db_source').val(),
		// trantype: $('#db_trantype').val(),
	}
	
	var saveParam2_RC={	
		action: 'receipt_save',
		url: 'receipt/form',
		oper: 'add',
		field: '',
		table_name: 'debtor.dbacthdr',
		table_id: 'auditno',
		fixPost: true,
		skipduplicate: true,
		returnVal: true,
		sysparam: {source:'PB',trantype:'RC',useOn:'auditno'}
	};
	
	//////////////////////////////////////////////jqGrid_rc//////////////////////////////////////////////
	$("#jqGrid_rc").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'compcode', name: 'db_compcode', hidden: true },
			{ label: 'Debtor Code', name: 'db_debtorcode', width: 25, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat: un_showdetail },
			{ label: 'Payer Code', name: 'db_payercode', width: 20, hidden: true },
			{ label: 'Audit No', name: 'db_auditno', width: 10, align: 'right', classes: 'wrap', canSearch: true, formatter: padzero, unformat: unpadzero },
			{ label: 'Sector', name: 'db_unit', width: 10, hidden: true, classes: 'wrap' },
			{ label: 'PO No', name: 'db_ponum', width: 8, formatter: padzero5, unformat: unpadzero, hidden: true },
			{ label: 'Document No', name: 'db_recptno', width: 15, align: 'right', canSearch: true,checked:true},
			{ label: 'Date', name: 'db_entrydate', width: 12, canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'Paymode', name: 'db_paymode', width: 20, classes: 'wrap text-uppercase', formatter: showdetail, unformat: un_showdetail },
			{ label: 'Amount', name: 'db_amount', width: 10, classes: 'wrap', align: 'right', formatter: 'currency' },
			{ label: 'Outamount', name: 'db_outamount', width: 10, classes: 'wrap', align: 'right', formatter: 'currency' },
			{ label: 'Status', name: 'db_recstatus', width: 10, hidden: true },
			{ label: 'source', name: 'db_source', width: 10, hidden: true },
			{ label: 'Trantype', name: 'db_trantype', width: 8, hidden: true },
			{ label: 'lineno_', name: 'db_lineno_', width: 10, hidden: true },
			{ label: 'db_orderno', name: 'db_orderno', width: 10, hidden: true },
			{ label: 'debtortype', name: 'db_debtortype', width: 20, hidden: true },
			{ label: 'billdebtor', name: 'db_billdebtor', width: 20, hidden: true },
			{ label: 'approvedby', name: 'db_approvedby', width: 20, hidden: true },
			{ label: 'MRN', name: 'db_mrn', width: 10, align: 'right', canSearch: true, classes: 'wrap text-uppercase', formatter: showdetail, unformat: un_showdetail },
			{ label: 'unit', name: 'db_unit', width: 10, hidden: true },
			{ label: 'termmode', name: 'db_termmode', width: 10, hidden: true },
			{ label: 'hdrtype', name: 'db_hdrtype', width: 10, hidden: true },
			{ label: 'paytype', name: 'db_paytype', width: 10, hidden: true },
			{ label: 'db_posteddate', name: 'db_posteddate', hidden: true },
			{ label: 'Department', name: 'db_deptcode', width: 15, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat: un_showdetail },
			{ label: ' ', width: 20, classes: 'wrap', formatter: buttonformatter },
			{ label: 'idno', name: 'db_idno', width: 10, hidden: true, key:true },
			{ label: 'adduser', name: 'db_adduser', width: 10, hidden: true },
			{ label: 'adddate', name: 'db_adddate', width: 10, hidden: true },
			{ label: 'upduser', name: 'db_upduser', width: 10, hidden: true },
			{ label: 'upddate', name: 'db_upddate', width: 10, hidden: true },
			{ label: 'db_payername', name: 'db_payername', width: 10, hidden: true },
			{ label: 'db_PymtDescription', name: 'db_PymtDescription', width: 10, hidden: true },
			{ label: 'Remark', name: 'db_remark', width: 20, classes: 'wrap', hidden: true },
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 900,
		height: 400,
		rowNum: 30,
		pager: "#jqGridPager_rc",
		onSelectRow:function(rowid, selected){
			if(rowid != null) {
				var rowData = $('#jqGrid_rc').jqGrid('getRowData', rowid);
				// $("#pg_jqGridPager3 table").hide();
				// $("#pg_jqGridPager2 table").show();
			}
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager_rc td[title='View Selected Row']").click();
		},
		gridComplete: function () {
			$("#jqGrid_rc").setSelection($("#jqGrid_rc").getDataIDs()[0]); // highlight 1st record
			init_btn();
			$("#trantype").val(selrowData("#jqGrid_rc").db_trantype);
			
			if($('#jqGrid_rc').data('inputfocus') == 'customer_search'){
				$("#customer_search").focus();
				$('#jqGrid_rc').data('inputfocus','');
				$('#customer_search_hb').text('');
				removeValidationClass(['#customer_search']);
			}else if($('#jqGrid_rc').data('inputfocus') == 'department_search'){
				$("#department_search").focus();
				$('#jqGrid_rc').data('inputfocus','');
				$('#department_search_hb').text('');
				removeValidationClass(['#department_search']);
			}else{
				$("#searchForm input[name=Stext]").focus();
			}
			fdl.set_array().reset();
		},
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid_rc");
		},
	});
	
	//////////////////////////////////////////////jqGridPager_rc//////////////////////////////////////////////
	$("#jqGrid_rc").jqGrid('navGrid', '#jqGridPager_rc', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid_rc", urlParam_rcpt);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager_rc",{
		caption:"",cursor: "pointer",position: "first",
		buttonicon:"glyphicon glyphicon-info-sign",
		title:"View Selected Row",
		onClickButton: function(){
			oper='view';
			let selRowId = $("#jqGrid_rc").jqGrid ('getGridParam', 'selrow');
			let rowData = $('#jqGrid_rc').jqGrid('getRowData', selRowId);
			if(parseFloat(rowData.db_amount) != parseFloat(rowData.db_outamount)){
				return false;
			}
			enabledPill();
			
			$( "input:radio[name='optradio'][value='receipt']" ).prop( "checked", true );
			$( "input:radio[name='optradio'][value='receipt']" ).change();
			
			populateFormdata("#jqGrid_rc", "#dialogForm_RC", "#formdata_RC", selRowId, 'view', '');
			getdata('RC',selrowData("#jqGrid_rc").db_idno);
			refreshGrid("#sysparam",urlParam_sys);
		},
	});
	//////////////////////////////////////////////end jqGrid_rc//////////////////////////////////////////////
	
	/////////////////////////////////////parameter for jqGrid_rd url/////////////////////////////////////
	var urlParam_rd={
		action: 'get_jqGrid_rd',
		url: './cancellation/table',
		// source: $('#db_source').val(),
		// trantype: $('#db_trantype').val(),
	}
	
	//////////////////////////////////////////////jqGrid_rd//////////////////////////////////////////////
	$("#jqGrid_rd").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid_rc").jqGrid('getGridParam','colModel'),
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 900,
		height: 400,
		rowNum: 30,
		pager: "#jqGridPager_rd",
		onSelectRow:function(rowid, selected){
			if(rowid != null) {
				var rowData = $('#jqGrid_rd').jqGrid('getRowData', rowid);
				// $("#pg_jqGridPager3 table").hide();
				// $("#pg_jqGridPager2 table").show();
			}
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager_rd td[title='View Selected Row']").click();
		},
		gridComplete: function () {
			$("#jqGrid_rd").setSelection($("#jqGrid_rd").getDataIDs()[0]); // highlight 1st record
			init_btn();
			$("#trantype").val(selrowData("#jqGrid_rd").db_trantype);
			
			if($('#jqGrid_rd').data('inputfocus') == 'customer_search'){
				$("#customer_search").focus();
				$('#jqGrid_rd').data('inputfocus','');
				$('#customer_search_hb').text('');
				removeValidationClass(['#customer_search']);
			}else if($('#jqGrid_rd').data('inputfocus') == 'department_search'){
				$("#department_search").focus();
				$('#jqGrid_rd').data('inputfocus','');
				$('#department_search_hb').text('');
				removeValidationClass(['#department_search']);
			}else{
				$("#searchForm input[name=Stext]").focus();
			}
			fdl.set_array().reset();
		},
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid_rd");
		},
	});
	
	//////////////////////////////////////////////jqGridPager_rd//////////////////////////////////////////////
	$("#jqGrid_rd").jqGrid('navGrid', '#jqGridPager_rd', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid_rd", urlParam_rd);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager_rd",{
		caption:"",cursor: "pointer",position: "first",
		buttonicon:"glyphicon glyphicon-info-sign",
		title:"View Selected Row",
		onClickButton: function(){
			oper='view';
			let selRowId = $("#jqGrid_rd").jqGrid ('getGridParam', 'selrow');
			let rowData = $('#jqGrid_rd').jqGrid('getRowData', selRowId);
			if(parseFloat(rowData.db_amount) != parseFloat(rowData.db_outamount)){
				return false;
			}
			enabledPill();
			
			$( "input:radio[name='optradio'][value='deposit']" ).prop( "checked", true );
			$( "input:radio[name='optradio'][value='deposit']" ).change();
			
			populateFormdata("#jqGrid_rd", "#dialogForm_RC", "#formdata_RC", selRowId, 'view', '');
			getdata('RC',selrowData("#jqGrid_rd").db_idno);
			refreshGrid("#sysparam",urlParam_sys);
		},
	});
	//////////////////////////////////////////////end jqGrid_rd//////////////////////////////////////////////
	
	/////////////////////////////////////parameter for jqGrid_rf url///////////////////////////////////////
	var urlParam_rf={
		action: 'get_jqGrid_rf',
		url: './cancellation/table',
		field: ''
	}
	
	var saveParam_RF={
		action: 'refund_save',
		url: 'refund/form',
		oper: 'add',
		field: '',
		table_name: 'debtor.dbacthdr',
		table_id: 'auditno',
		fixPost: true,
		skipduplicate: true,
		returnVal: true,
		sysparam: {source:'PB',trantype:'RF',useOn:'auditno'}	/////PB, RF, pValue +1
	};
	
	//////////////////////////////////////////////jqGrid_rf/////////////////////////////////////////////////
	$("#jqGrid_rf").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'compcode', name: 'db_compcode', hidden: true },
			{ label: 'Debtor Code', name: 'db_debtorcode', width: 25, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat: un_showdetail },
			{ label: 'Payer Code', name: 'db_payercode', width: 20, hidden: true },
			{ label: 'Audit No', name: 'db_auditno', width: 10, align: 'right', classes: 'wrap', canSearch: true, formatter: padzero, unformat: unpadzero },
			{ label: 'Sector', name: 'db_unit', width: 10, hidden: true, classes: 'wrap' },
			{ label: 'PO No', name: 'db_ponum', width: 8, formatter: padzero5, unformat: unpadzero, hidden: true },
			{ label: 'Document No', name: 'db_recptno', width: 15, align: 'right' },
			{ label: 'Date', name: 'db_entrydate', width: 12, canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'Paymode', name: 'db_paymode', width: 20, classes: 'wrap text-uppercase', formatter: showdetail, unformat: un_showdetail },
			{ label: 'Amount', name: 'db_amount', width: 10, classes: 'wrap', align: 'right', formatter: 'currency' },
			{ label: 'Outamount', name: 'db_outamount', width: 10, classes: 'wrap', align: 'right', formatter: 'currency' },
			{ label: 'Status', name: 'db_recstatus', width: 10, hidden: true },
			{ label: 'source', name: 'db_source', width: 10, hidden: true },
			{ label: 'Trantype', name: 'db_trantype', width: 8, hidden: true },
			{ label: 'lineno_', name: 'db_lineno_', width: 10, hidden: true },
			{ label: 'db_orderno', name: 'db_orderno', width: 10, hidden: true },
			{ label: 'debtortype', name: 'db_debtortype', width: 20, hidden: true },
			{ label: 'billdebtor', name: 'db_billdebtor', width: 20, hidden: true },
			{ label: 'approvedby', name: 'db_approvedby', width: 20, hidden: true },
			{ label: 'MRN', name: 'db_mrn', width: 10, align: 'right', canSearch: true, classes: 'wrap text-uppercase', formatter: showdetail, unformat: un_showdetail },
			{ label: 'unit', name: 'db_unit', width: 10, hidden: true },
			{ label: 'termmode', name: 'db_termmode', width: 10, hidden: true },
			{ label: 'hdrtype', name: 'db_hdrtype', width: 10, hidden: true },
			{ label: 'paytype', name: 'db_paytype', width: 10, hidden: true },
			{ label: 'db_posteddate', name: 'db_posteddate', hidden: true },
			{ label: 'Department', name: 'db_deptcode', width: 15, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat: un_showdetail },
			{ label: ' ', width: 20, classes: 'wrap', formatter: buttonformatter_rf },
			{ label: 'idno', name: 'db_idno', width: 10, hidden: true, key:true },
			{ label: 'adduser', name: 'db_adduser', width: 10, hidden: true },
			{ label: 'adddate', name: 'db_adddate', width: 10, hidden: true },
			{ label: 'upduser', name: 'db_upduser', width: 10, hidden: true },
			{ label: 'upddate', name: 'db_upddate', width: 10, hidden: true },
			{ label: 'db_payername', name: 'db_payername', width: 10, hidden: true },
			{ label: 'db_PymtDescription', name: 'db_PymtDescription', width: 10, hidden: true },
			{ label: 'Remark', name: 'db_remark', width: 20, classes: 'wrap', hidden: true },
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'db_idno',
		sortorder:'desc',
		width: 900,
		height: 300,
		rowNum: 30,
		pager: "#jqGridPager_rf",
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager_rf td[title='View Selected Row']").click();
			// $("#gridAllo input[name='tick']").hide();
		},
		onSelectRow: function(rowid){
			// urlParamAllo.payercode = selrowData("#jqGrid_rf").db_payercode;
			// refreshGrid("#gridAllo",urlParamAllo);
			// $("#gridAllo input[name='tick']").hide();
		},
		gridComplete: function(){
			fdl.set_array().reset();
			$("#jqGrid_rf").setSelection($("#jqGrid_rf").getDataIDs()[0]);
			init_btn_rf();
			
			$('#'+$("#jqGrid_rf").jqGrid ('getGridParam', 'selrow')).focus();
			// enabledPill();
			// refreshGrid("#jqGrid_rf",urlParam_rf);
		},
		loadComplete:function(data){
			// refreshGrid("#jqGrid_rf",urlParam_rf);
			calc_jq_height_onchange("jqGrid_rf");
		}	
	});
	
	//////////////////////////////////////////////jqGridPager_rd//////////////////////////////////////////////
	$("#jqGrid_rf").jqGrid('navGrid','#jqGridPager_rf',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid_rf",urlParam_rf);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager_rf",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-info-sign",
		title:"View Selected Row",  
		onClickButton: function(){
			oper = 'view';
			var selRowId = $("#jqGrid_rf").jqGrid ('getGridParam', 'selrow');
			var selrowData = $("#jqGrid_rf").jqGrid ('getRowData', selRowId);
			
			populateFormdata("#jqGrid_rf", "#dialogForm_RF", "#formdata_RF", selRowId, 'view', '');
			
			urlParamAllo.auditno = selrowData.db_auditno;
			refreshGrid("#gridAllo",urlParamAllo);
			// getdata('RF',selrowData("#jqGrid_rf").db_idno);
			// refreshGrid("#jqGrid_rf",urlParam_rf);
		},
	});
	
	////////////////////////////////////////////////btn_alloc////////////////////////////////////////////////
	var urlParamAlloc={
		action: 'get_alloc',
		url: './arenquiry/table',
		idno: '',
		auditno: '',
		// trantype: ''
	};
	
	$("#jqGridAlloc").jqGrid({
		datatype: "local",
		editurl: "./arenquiry/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', width: 20, hidden:true },
			{ label: 'lineno_', name: 'lineno_', width: 20, hidden:true },
			{ label: 'idno', name: 'idno', width: 20, hidden:true },
			{ label: 'System Auto No.', name: 'sysAutoNo', width: 35, classes: 'wrap' },
			{ label: 'Source', name: 'source', width: 10, classes: 'wrap', hidden: true },
			{ label: 'trantype', name: 'trantype', width: 10, classes: 'wrap', hidden: true },
			{ label: 'doctrantype', name: 'doctrantype', width: 10, classes: 'wrap', hidden: true },
			{ label: 'Audit No', name: 'auditno', width: 10, classes: 'wrap',formatter: padzero, unformat: unpadzero, hidden: true },
			{ label: 'Debtor', name: 'debtorcode', width: 40, classes: 'wrap text-uppercase', formatter: showdetail, unformat:un_showdetail },
			{ label: 'Payer', name: 'payercode', width: 40, classes: 'wrap text-uppercase', formatter: showdetail, unformat:un_showdetail },
			{ label: 'Amount', name: 'amount', width: 25, classes: 'wrap', align: 'right', formatter:'currency' },
			{ label: 'Document No', name: 'recptno', width: 35, align: 'right' },
			{ label: 'Paymode', name: 'paymode', width: 30, classes: 'wrap text-uppercase', formatter: showdetail, unformat:un_showdetail },
			{ label: 'Alloc Date', name: 'allocdate', width: 30, formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'MRN', name: 'mrn', width: 15, align: 'right', classes: 'wrap text-uppercase', formatter: showdetail, unformat:un_showdetail },
			{ label: 'Episno', name: 'episno', width: 15, align: 'right' },
			{ label: ' ', width: 35, classes: 'wrap', formatter: btncancelformatter },
		],
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		pager: "#jqGridPagerAlloc",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGridAlloc");
			if($('#trantype').val() == "RC"){
				urlParamAlloc.idno=selrowData("#jqGrid_rc").db_idno;
			}else if($('#trantype').val() == "RD"){
				urlParamAlloc.idno=selrowData("#jqGrid_rd").db_idno;
			}
			refreshGrid("#jqGridAlloc",urlParamAlloc,'add');
		},
		gridComplete: function(){
			$("#jqGridAlloc").setSelection($("#jqGridAlloc").getDataIDs()[0]); // highlight 1st record
			init_btncancel();
			fdl.set_array().reset();
		},
	});
	jqgrid_label_align_right("#jqGridAlloc");
	//////////////////////////////////////////////end btn_alloc//////////////////////////////////////////////
	
	//////////////////////////////////////////////////start//////////////////////////////////////////////////
	var urlParam_sys={
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
				// $('#'+$("#sysparam").jqGrid ('getGridParam', 'selrow')).focus();
				return false;
			}
		}
	});
	
	var urlParam2_rc={
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		table_name: 'debtor.paymode',
		table_id: 'paymode',
		filterCol: ['source','paytype','compcode'],
		filterVal: ['AR','BANK','session.compcode'],
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
		action: 'get_table_default',
		url: 'util/get_table_default',
		field: '',
		table_name: 'debtor.paymode',
		table_id: 'paymode',
		filterCol: ['source','paytype','compcode'],
		filterVal: ['AR','CARD','session.compcode'],
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
		action: 'get_effdate',
		type: 'forex'
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
	
	//////////////////////////////////////////////jqGrid_rc//////////////////////////////////////////////
	function buttonformatter(cellvalue, options, rowObject){
		var retbut = `<div class="mini ui icon buttons"`+rowObject.db_idno+`>`
		if(parseFloat(rowObject.db_amount) != parseFloat(rowObject.db_outamount)){
			retbut += 	  `<button type='button' class="btn btn-primary btn-sm btn_detail" data-idno='`+rowObject.db_idno+`' data-trantype='`+rowObject.db_trantype+`' data-amount='`+rowObject.db_amount+`' data-outamount='`+rowObject.db_outamount+`' disabled>`
		}else{
			retbut += 	  `<button type='button' class="btn btn-primary btn-sm btn_detail" data-idno='`+rowObject.db_idno+`' data-trantype='`+rowObject.db_trantype+`' data-amount='`+rowObject.db_amount+`' data-outamount='`+rowObject.db_outamount+`'>`
		}
			retbut += 	    `Detail`
			retbut += 	  `</button>&nbsp;`
			retbut += 	  `<button type='button' class="btn btn-primary btn-sm btn_alloc" data-idno='`+rowObject.db_idno+`' data-trantype='`+rowObject.db_trantype+`'>`
			retbut += 	    `Allocation`
			retbut += 	  `</button></div>`;
		return retbut;
	}
	
	function init_btn(){
		// if($($('button.btn_detail')).data('amount') !== $($('button.btn_detail')).data('outamount')){
		// 	$('button.btn_detail').prop('disabled', true);
		// }else{
		// 	$('button.btn_detail').prop('disabled', false);
		// }
		
		$('button.btn_detail').on('click',function(e){
			oper='view';
			var idno = $(this).data('idno');
			var trantype = $(this).data('trantype');
			enabledPill();
			
			if(trantype == 'RC'){
				$( "input:radio[name='optradio'][value='receipt']" ).prop( "checked", true );
				$( "input:radio[name='optradio'][value='receipt']" ).change();
				
				populateFormdata("#jqGrid_rc", "#dialogForm_RC", "#formdata_RC", idno, 'view', '');
			}else if(trantype == 'RD'){
				$( "input:radio[name='optradio'][value='deposit']" ).prop( "checked", true );
				$( "input:radio[name='optradio'][value='deposit']" ).change();
				
				populateFormdata("#jqGrid_rd", "#dialogForm_RC", "#formdata_RC", idno, 'view', '');
			}
			
			getdata('RC',idno);
			refreshGrid("#sysparam",urlParam_sys);
		});
		
		$('button.btn_alloc').on('click',function(e){
			var idno = $(this).data('idno');
			var trantype = $(this).data('trantype');
			
			if(trantype == 'RC'){
				$("#jqGrid_rc").jqGrid('setSelection', idno);
			}else if(trantype == 'RD'){
				$("#jqGrid_rd").jqGrid('setSelection', idno);
			}
				
			$('#trantype').val(trantype);
			$("#dialog_allocation").dialog("open");
		});
	}
	
	//////////////////////////////////////////////jqGrid_rf//////////////////////////////////////////////
	function buttonformatter_rf(cellvalue, options, rowObject){
		var retbut = `<div class="mini ui icon buttons"`+rowObject.db_idno+`>`
			retbut += 	  `<button type='button' class="btn btn-primary btn-sm btn_detail" data-idno='`+rowObject.db_idno+`' data-auditno='`+rowObject.db_auditno+`'>`
			retbut += 	    `Detail`
			retbut += 	  `</button></div>`;
		return retbut;
	}
	
	function init_btn_rf(){
		$('button.btn_detail').off('click');
		$('button.btn_detail').on('click',function(e){
			oper='view';
			var idno = $(this).data('idno');
			var auditno = $(this).data('auditno');
			enabledPill();
			
			populateFormdata("#jqGrid_rf", "#dialogForm_RF", "#formdata_RF", idno, 'view', '');
			// getdata('RF',idno);
			// refreshGrid("#jqGrid_rf",urlParam_rf);
			urlParamAllo.auditno = auditno;
			refreshGrid("#gridAllo",urlParamAllo);
		});
	}
	
	//////////////////////////////////////////////jqGridAlloc//////////////////////////////////////////////
	function btncancelformatter(cellvalue, options, rowObject){
		var retbut = `<div class="mini ui icon buttons"`+rowObject.idno+`>`
			retbut += 	  `<button type='button' class="btn btn-danger btn-sm btn_cancel" data-idno='`+rowObject.idno+`'>`
			retbut += 	    `Cancel Allocation`
			retbut += 	  `</button></div>`;
		return retbut;
	}
	
	function init_btncancel(){
		$('button.btn_cancel').on('click',function(e){
			var idno = $(this).data('idno');
			
			bootbox.confirm({
				message: "Are you sure you want to cancel this allocation?",
				buttons: { confirm: { label: 'Yes', className: 'btn-success' }, cancel: { label: 'No', className: 'btn-danger' } },
				callback: function (result) {
					if(result == true){
						var urlparam={
							oper: 'cancel_alloc',
							idno: idno,
						}
						
						var postobj={
							_token : $('#csrf_token').val(),
						};
						
						$.post( "./cancellation/form?"+$.param(urlparam), $.param(postobj), function( data ) {
							
						},'json').fail(function(data) {
							// alert('there is an error');
							console.log(data);
							alert(data.responseText);
						}).success(function(data){
							if($('#trantype').val() == "RC"){
								urlParamAlloc.idno=selrowData("#jqGrid_rc").db_idno;
								refreshGrid('#jqGrid_rc', urlParam_rcpt);
							}else if($('#trantype').val() == "RD"){
								urlParamAlloc.idno=selrowData("#jqGrid_rd").db_idno;
								refreshGrid('#jqGrid_rd', urlParam_rd);
							}
							refreshGrid("#jqGridAlloc",urlParamAlloc);
						});
					}else{
						if($('#trantype').val() == "RC"){
							urlParamAlloc.idno=selrowData("#jqGrid_rc").db_idno;
							// refreshGrid('#jqGrid_rc', urlParam_rcpt);
						}
						else if($('#trantype').val() == "RD"){
							urlParamAlloc.idno=selrowData("#jqGrid_rd").db_idno;
							// refreshGrid('#jqGrid_rd', urlParam_rd);
						}
						refreshGrid("#jqGridAlloc",urlParamAlloc);
					}
				}
			});
		});
	}
	//////////////////////////////////////////////////end//////////////////////////////////////////////////
	
	/////////////////////////////handle searching, its radio button and toggle/////////////////////////////
	populateSelect2('#jqGrid_rc','#searchForm');
	
	//////////////////////////////add field into param, refresh grid if needed//////////////////////////////
	addParamField('#jqGrid_rc', true, urlParam_rcpt);
	
	//////////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field, table, case_;
		switch(options.colModel.name){
			case 'db_debtorcode':field=['debtorcode','name'];table="debtor.debtormast";case_='db_debtorcode';break;
			case 'db_paymode':field=['paymode','description'];table="debtor.paymode";case_='db_paymode';break;
			case 'db_mrn':field=['MRN','name'];table="hisdb.pat_mast";case_='db_mrn';break;
			case 'db_deptcode':field=['deptcode','description'];table="sysdb.department";case_='db_deptcode';break;
			case 'db_payercode':field=['debtorcode','name'];table="debtor.debtormast";case_='db_payercode';break;
			
			// jqGridAlloc
			case 'debtorcode':field=['debtorcode','name'];table="debtor.debtormast";case_='debtorcode';break;
			case 'payercode':field=['debtorcode','name'];table="debtor.debtormast";case_='payercode';break;
			case 'paymode':field=['paymode','description'];table="debtor.paymode";case_='paymode';break;
			case 'mrn':field=['MRN','name'];table="hisdb.pat_mast";case_='mrn';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
		
		fdl.get_array('cancellation',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}
	
	/////////////////////////////////////////////////cust_rules/////////////////////////////////////////////////
	function cust_rules(value, name) {
		var temp=null;
		switch (name) {
			// case 'Category':temp=$('#category');break;
		}
		if(temp == null) return [true,''];
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}
	
	////////////////////////////////////////////////custom input////////////////////////////////////////////////
	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}
	
	/////////////////////////////////////changing status and trigger search/////////////////////////////////////
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
			urlParam_rcpt.searchCol=urlParam_rcpt.searchVal=null;
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
			refreshGrid('#jqGrid_rc', urlParam_rcpt);
		}else{
			search('#jqGrid_rc',$('#searchForm [name=Stext]').val(),$('#searchForm [name=Scol] option:selected').val(),urlParam_rcpt);
		}
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
		
		urlParam_rcpt.filterCol = filter.fc;
		urlParam_rcpt.filterVal = filter.fv;
		refreshGrid('#jqGrid_rc',urlParam_rcpt);
	}
	
	function searchDate(){
		urlParam_rcpt.filterdate = [$('#docuDate_from').val(),$('#docuDate_to').val()];
		refreshGrid('#jqGrid_rc',urlParam_rcpt);
		urlParam_rcpt.filterdate = null;
	}
	
	////////////////////////////////////populate data for dropdown search By////////////////////////////////////
	searchBy();
	function searchBy() {
		// $.each($("#jqGrid_rc").jqGrid('getGridParam', 'colModel'), function (index, value) {
		// 	if (value['canSearch']) {
		// 		if (value['selected']) {
		// 			$("#searchForm [id=Scol]").append(" <option selected value='" + value['name'] + "'>" + value['label'] + "</option>");
		// 		} else {
		// 			$("#searchForm [id=Scol]").append(" <option value='" + value['name'] + "'>" + value['label'] + "</option>");
		// 		}
		// 	}
		// });
		searchClick2_('#jqGrid_rc', '#searchForm', urlParam_rcpt,false);
	}

	function searchClick2_(grid,form,urlParam,withscol=true){

		$(form+' [name=Stext]').off("keyup");
		$(form+' [name=Stext]').on( "keyup", function(e) {
			let activeTab = $('#jqGrid_cancel_c .nav-tabs .active a').data('trantype');

			if(activeTab == 'RF'){
				grid = '#jqGrid_rf';
				urlParam = urlParam_rf;
			}else if(activeTab == 'RD'){
				grid = '#jqGrid_rd';
				urlParam = urlParam_rd;
			}else{
				grid = '#jqGrid_rc';
				urlParam = urlParam_rcpt;
			}

			var code = e.keyCode || e.which;
			if(code != '9'){
				delay(function(){
					search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
					$('#recnodepan').text("");//tukar kat depan tu
					$('#reqdeptdepan').text("");
					refreshGrid("#jqGrid3",null,"kosongkan");
				}, 1100 );
			}
		});
		if(withscol){
			$(form+' [name=Stext]').off("change");
			$(form+' [name=Scol]').on( "change", function() {
				let activeTab = $('#jqGrid_cancel_c .nav-tabs .active a').data('trantype');

				if(activeTab == 'RF'){
					grid = '#jqGrid_rf';
					urlParam = urlParam_rf;
				}else if(activeTab == 'RD'){
					grid = '#jqGrid_rd';
					urlParam = urlParam_rd;
				}else{
					grid = '#jqGrid_rc';
					urlParam = urlParam_rcpt;
				}
				search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				$('#recnodepan').text("");//tukar kat depan tu
				$('#reqdeptdepan').text("");
				refreshGrid("#jqGrid3",null,"kosongkan");
			});
		}
	}
	
	///////////////////////////////////////////////////Dialog///////////////////////////////////////////////////
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
					urlParam_rcpt.searchCol=["db.debtorcode"];
					urlParam_rcpt.searchVal=[data];
					urlParam_rd.searchCol=["db.debtorcode"];
					urlParam_rd.searchVal=[data];
					urlParam_rf.searchCol=["db.debtorcode"];
					urlParam_rf.searchVal=[data];
				}
				// else if($('#Scol').val() == 'db_payercode'){
				// 	urlParam_rcpt.searchCol=["db.payercode"];
				// 	urlParam_rcpt.searchVal=[data];
				// }
				if($('#cancel_navtab_rc').attr('aria-expanded') == 'true'){
					refreshGrid('#jqGrid_rc', urlParam_rcpt);
				}else if($('#cancel_navtab_rd').attr('aria-expanded') == 'true'){
					refreshGrid('#jqGrid_rd', urlParam_rcpt);
				}else if($('#cancel_navtab_rf').attr('aria-expanded') == 'true'){
					refreshGrid('#jqGrid_rf', urlParam_rcpt);
				}
			},
			gridComplete: function(obj){
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
			open: function () {
				customer_search.urlParam.filterCol = ['compcode','recstatus'];
				customer_search.urlParam.filterVal = ['session.compcode','ACTIVE'];
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
					urlParam_rcpt.searchCol=["db.deptcode"];
					urlParam_rcpt.searchVal=[data];
				}
				refreshGrid('#jqGrid_rc', urlParam_rcpt);
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
				department_search.urlParam.filterCol = ['compcode','recstatus'];
				department_search.urlParam.filterVal = ['session.compcode','ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	department_search.makedialog(true);
	$('#department_search').on('keyup',ifnullsearch);
	
	function ifnullsearch(){
		if($(this).val() == ''){
			urlParam_rcpt.searchCol=[];
			urlParam_rcpt.searchVal=[];
			$('#jqGrid_rc').data('inputfocus',$(this).attr('id'));
			refreshGrid('#jqGrid_rc', urlParam_rcpt);
		}
	}
	
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
		{
			colModel: [
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
				// $('#apacthdr_actdate').focus();
				$('#dbacthdr_mrn').val(data.MRN);
				$('#dbacthdr_episno').val(data.Episno);
			},
			gridComplete: function(obj){
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
			title:"Select MRN",
			open: function(){
				dialog_mrn.urlParam.filterCol=['compcode'],
				dialog_mrn.urlParam.filterVal=['session.compcode']
			}
		},'urlParam','radio','tab'
	);
	dialog_mrn.makedialog(true);
	
	//////////////////////////////////RC//////////////////////////////////
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
	//////////////////////////////////end RC//////////////////////////////////
	
	$("#jqGrid_rc").jqGrid ('setGridWidth', Math.floor($("#jqGrid_cancel_c")[0].offsetWidth-$("#jqGrid_cancel_c")[0].offsetLeft-30));
	$("#jqGrid_rd").jqGrid ('setGridWidth', Math.floor($("#jqGrid_cancel_c")[0].offsetWidth-$("#jqGrid_cancel_c")[0].offsetLeft-30));
	$("#jqGrid_rf").jqGrid ('setGridWidth', Math.floor($("#jqGrid_cancel_c")[0].offsetWidth-$("#jqGrid_cancel_c")[0].offsetLeft-30));
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
		case 'RF':
			populateform_rf(idno);
			break;
	}
}

///////////////////////////////////////////////////////RC///////////////////////////////////////////////////////
var dialog_payercode = new ordialog(
	'payercode','debtor.debtormast','#dbacthdr_payercode','errorField',
	{
		colModel: [
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
			// $('#apacthdr_actdate').focus();
			$('#dbacthdr_payername').val(data.name);
			$('#dbacthdr_debtortype').val(data.debtortype);
		},
		gridComplete: function(obj){
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

///////////////////////////////////////////////////////RF///////////////////////////////////////////////////////
// var dialog_payercode = new ordialog(
// 	'payercode','debtor.debtormast',"#jqGrid input[name='db_payercode']"','errorField',
// 	{
// 		colModel:[
// 			{ label:'Debtor Code',name:'debtorcode',width:200,classes:'pointer',canSearch:true,or_search:true },
// 			{ label:'Debtor Name',name:'name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true },
// 			{ label:'debtortype',name:'debtortype',hidden:true },
// 			{ label:'actdebccode',name:'actdebccode',hidden:true },
// 			{ label:'actdebglacc',name:'actdebglacc',hidden:true },
// 		],
// 		urlParam: {
// 			filterCol:['compcode','recstatus'],
// 			filterVal:['session.compcode','ACTIVE']
// 		},
// 		ondblClickRow:function(){
// 			let data=selrowData('#'+dialog_payercode.gridname);
// 			// $('#apacthdr_actdate').focus();
// 			$('#dbacthdr_payername').val(data.name);
// 			$('#dbacthdr_debtortype').val(data.debtortype);
// 			urlParamAllo.payercode = data.debtorcode;
// 			myallocation.renewAllo(0);
// 			refreshGrid("#gridAllo",urlParamAllo);
// 		},
// 		gridComplete: function(obj){
// 			var gridname = '#'+obj.gridname;
// 			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
// 				$(gridname+' tr#1').click();
// 				$(gridname+' tr#1').dblclick();
// 				// $('#apacthdr_actdate').focus();
// 			}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
// 				$('#'+obj.dialogname).dialog('close');
// 			}
// 		}
// 	},{
// 		title:"Select Payer code",
// 		open: function(){
// 			dialog_payercode.urlParam.filterCol=['recstatus', 'compcode'],
// 			dialog_payercode.urlParam.filterVal=['ACTIVE', 'session.compcode']
// 		},
// 		close: function(){
// 			let data=selrowData('#'+dialog_payercode.gridname);
// 			get_debtorcode_outamountRF(data.debtorcode);
// 			// $('#dbacthdr_remark').focus();
// 		}
// 	},'urlParam','radio','tab'
// );
// dialog_payercode.makedialog();

function populateform_rc(idno){
	var param={
		action: 'populate_rc',
		url: './arenquiry/table',
		field: ['dbacthdr_compcode','dbacthdr_auditno','dbacthdr_lineno_','dbacthdr_billdebtor','dbacthdr_conversion','dbacthdr_hdrtype','dbacthdr_currency','dbacthdr_tillcode','dbacthdr_tillno','dbacthdr_debtortype','dbacthdr_adddate','dbacthdr_PymtDescription','dbacthdr_recptno','dbacthdr_entrydate','dbacthdr_entrytime','dbacthdr_entryuser','dbacthdr_payercode','dbacthdr_payername','dbacthdr_mrn','dbacthdr_episno','dbacthdr_remark','dbacthdr_authno','dbacthdr_epistype','dbacthdr_cbflag','dbacthdr_reference','dbacthdr_paymode','dbacthdr_amount','dbacthdr_outamount','dbacthdr_source','dbacthdr_trantype','dbacthdr_recstatus','dbacthdr_bankcharges','dbacthdr_expdate','dbacthdr_rate','dbacthdr_unit','dbacthdr_invno','dbacthdr_paytype','dbacthdr_RCCASHbalance','dbacthdr_RCFinalbalance','dbacthdr_RCOSbalance','dbacthdr_idno','paycard_description','paybank_description'],
		idno: idno,
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
			resetpill();
			$(".nav-tabs a[form='"+data.rows.dbacthdr_paytype.toLowerCase()+"']").tab('show');
			dialog_payercode.check('errorField');
			disabledPill();
		}
	});
}

function populateform_rf(idno){
	var param={
		action: 'populate_rf',
		url: './refund/table',
		field: ['dbacthdr_compcode','dbacthdr_auditno','dbacthdr_lineno_','dbacthdr_billdebtor','dbacthdr_conversion','dbacthdr_hdrtype','dbacthdr_currency','dbacthdr_tillcode','dbacthdr_tillno','dbacthdr_debtortype','dbacthdr_adddate','dbacthdr_PymtDescription','dbacthdr_recptno','dbacthdr_entrydate','dbacthdr_entrytime','dbacthdr_entryuser','dbacthdr_payercode','dbacthdr_payername','dbacthdr_mrn','dbacthdr_episno','dbacthdr_remark','dbacthdr_authno','dbacthdr_epistype','dbacthdr_cbflag','dbacthdr_reference','dbacthdr_paymode','dbacthdr_amount','dbacthdr_outamount','dbacthdr_source','dbacthdr_trantype','dbacthdr_recstatus','dbacthdr_bankcharges','dbacthdr_expdate','dbacthdr_rate','dbacthdr_unit','dbacthdr_invno','dbacthdr_paytype','dbacthdr_RCCASHbalance','dbacthdr_RCFinalbalance','dbacthdr_RCOSbalance','dbacthdr_idno','paycard_description','paybank_description'],
		idno: idno,
	}
	
	$.get( param.url+"?"+$.param(param), function( data ) {
		
	},'json').done(function(data) {
		$(".nav-tabs a[form='"+data.rows.dbacthdr_paytype.toLowerCase()+"']").tab('show');
		dialog_payercode.check('errorField');
		disabledPill();
	});
}

var myallocation = new Allocation();
var allocurrency = new currencymode(["input[name=dbacthdr_allocamt]"]);

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
		this.alloBalance = this.outamt - this.alloTotal;
		
		$("input[name=dbacthdr_allocamt]").val(this.alloTotal);
		$("#AlloBalance").val(this.alloBalance);
		if(this.alloBalance<0){
			$("#AlloBalance").addClass( "error" ).removeClass( "valid" );
			alert("Balance cannot in negative values");
		}else{
			$("#AlloBalance").addClass( "valid" ).removeClass( "error" );
		}
		allocurrency.formatOn();
	}
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

function resetpill(dialog='#dialogForm_RC'){
	$(dialog+' ul.nav-tabs li').removeClass('active');
	$(dialog+' ul.nav-tabs li a').attr('aria-expanded',false);
}
/////////////////////////////////////////////////////end RC/////////////////////////////////////////////////////

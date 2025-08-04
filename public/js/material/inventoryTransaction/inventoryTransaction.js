
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	$('body').click(function(){
		$('#error_infront').text('');
	});
	/////////////////////////////////////////validation//////////////////////////
	$.validate({
		modules : 'sanitize',
		language : {
			requiredFields: ''
		},
	});
	
	var errorField=[];
	conf = {
		onValidate : function($form) {
			if(errorField.length>0){
				show_errors(errorField,'#formdata');
				return [{
					element : $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
					message : ' '
				}]
			}
		},
	};

	/////////////////////////////////// currency /////////////////////////////////////////
	var mycurrency =new currencymode(['#amount', "#jqGrid2 input[name='netprice']"]);
	var fdl = new faster_detail_load();
	var myfail_msg = new fail_msg_func();
	var my_remark_button = new remark_button_class('#jqgrid');

	///////////////////////////////// trandate check date validate from period////////////
	var actdateObj = new setactdate(["#trandate"]);
	actdateObj.getdata().set();

	////////////////////////////////////start dialog/////////////////////////////////////
	var oper;
	var unsaved = false;
	scrollto_topbtm();

	$("#dialogForm")
	  .dialog({ 
		width: 9.5/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			$('#txndept').focus();
			$('#jqGridPager2EditAll').data('click',false);
			unsaved = false;
			errorField.length=0;
			parent_close_disabled(true);
			my_remark_button.remark_btn_init(selrowData("#jqGrid"));
			$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
			mycurrency.formatOnBlur();
			switch(oper) {
				case state = 'add':
					$("#jqGrid2").jqGrid("clearGridData", true);
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					enableForm('#formdata');
					$('#trantime').val(moment().format('HH:mm:ss'));
					rdonly('#formdata');
					$("#txndept").val($("#deptcode").val());
					dialog_txndept.check(errorField);

					//default TUO
					if($('#ttype').val() == 'TUO' || $('#ttype').val() == 'TUI'){
	            		$(dialog_trantype.textfield).val('TUO');
						dialog_trantype.check(errorField);
						case_tui_tuo('TUO');
					}
					break;
				case state = 'edit':
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					enableForm('#formdata');
					rdonly('#formdata');
					inputTrantypeValue(selrowData('#jqGrid').isstype,selrowData('#jqGrid').crdbfl);
					break;
				case state = 'view':
					disableForm('#formdata');
					$("#pg_jqGridPager2 table").hide();
					inputTrantypeValue(selrowData('#jqGrid').isstype,selrowData('#jqGrid').crdbfl);
					break;
			}if(oper!='add'){
				dialog_trantype.check(errorField);
				dialog_txndept.check(errorField);
				// dialog_requestRecNo.check(errorField);
			}if(oper!='view'){
				dialog_trantype.on();
				dialog_txndept.on();
				dialog_sndrcv.on();
				dialog_requestRecNo.on();
			}
		},
		beforeClose: function(event, ui){
			// if(unsaved){
			// 	event.preventDefault();

			// 	var bootbox1 = bootbox.dialog({
			// 		message: 'Are you sure want to leave without save?',
			// 		buttons: {
			// 		    cancel: {
			// 		        label: '<i class="fa fa-times"></i> Cancel'
			// 		    },
			// 		    noclose: {
			// 		        label: '<i class="fa fa-save"></i> Save',
			// 		        className: 'btn-warning',
			// 		        callback: function(){
			// 		        	saveDetailLabel(function(){
			// 		        		bootbox1.modal('hide');
			// 						$("#dialogForm").dialog('close');
			// 		        	});
			// 		            return false;
			// 		        }
			// 		    },
			// 		    confirm: {
			// 		        label: '<i class="fa fa-check"></i> Confirm',
			// 		        className: 'btn-primary',
			// 		        callback: function(){
			// 					unsaved = false
			// 					$("#dialogForm").dialog('close');
			// 		        }
			// 		    }
			// 		}
			// 	});
			// }
			if(unsaved){
				event.preventDefault();
				bootbox.confirm("Are you sure want to leave without save?", function(result){
					if (result == true) {
						unsaved = false;
						delete_dd($('#idno').val());
						$("#dialogForm").dialog('close');
					}
				});
			}
		},
		close: function( event, ui ) {
			addmore_jqgrid2.state = false;//reset balik
			addmore_jqgrid2.more = false;
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata');
			emptyFormdata(errorField,'#formdata2');
			dialog_trantype.off();
			dialog_txndept.off();
			dialog_sndrcv.off();
			dialog_requestRecNo.off();
			errorField.length=0;
			$(".noti").empty();
			$("#refresh_jqGrid").click();
			refreshGrid("#jqGrid2",null,"kosongkan");
		},
	});
	////////////////////////////////////////end dialog///////////////////////////////////////////////////

	/////////////////////parameter for jqgrid url////////////////////////////////////////////////////////



	var cbselect = new checkbox_selection("#jqGrid","Checkbox","idno","delordhd_recstatus");

	var urlParam={
		// action:'get_table_default',
		// url:'util/get_table_default',
		// field: ['ivt.recno','ivt.txndept','ivt.trantype','ivt.docno','ivt.trandate','ivt.trantime','ivt.sndrcv','ivt.sndrcvtype','ivt.amount','ivt.recstatus','ivt.srcdocno','ivt.remarks','ivt.adduser','ivt.adddate','ivt.upduser','ivt.upddate','ivt.source','ivt.idno','itt.isstype','itt.crdbfl'],
		// table_name:['material.ivtmphd as ivt','material.ivtxntype as itt'],
		// join_type:['LEFT JOIN'],
		// join_onCol:['ivt.trantype'],
		// join_onVal:['itt.trantype'],
		// filterCol:['ivt.compcode'],
		// filterVal:['session.compcode']
		action:'maintable',
		url:'./inventoryTransaction/table',
		// source:$('#db_source').val(),
		// trantype:$('#db_trantype').val(),
		
	}
	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam={
		action:'invTran_save',
		url:'./inventoryTransaction/form',
		field:'',
		oper:oper,
		table_name:'material.ivtmphd',
		table_id:'recno'
	};
	function padzero(cellvalue, options, rowObject){
		let padzero = 7, str="";
		while(padzero>0){
			str=str.concat("0");
			padzero--;
		}
		return pad(str, cellvalue, true);
	}

	function searchClick2(grid,form,urlParam){
		$(form+' [name=Stext]').on( "keyup", function() {
			delay(function(){
				search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				$('#recnodepan').text("");//tukar kat depan tu
				$('#prdeptdepan').text("");
				refreshGrid("#jqGrid3",null,"kosongkan");
			}, 500 );
		});

		$(form+' [name=Scol]').on( "change", function() {
			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
			$('#recnodepan').text("");//tukar kat depan tu
			$('#prdeptdepan').text("");
			refreshGrid("#jqGrid3",null,"kosongkan");
		});
	}

	/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'Record No', name: 'recno', width: 20, classes: 'wrap', canSearch: true,selected:true, formatter: padzero, unformat: unpadzero},
			{ label: 'Supplier Department', name: 'txndept', width: 30, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'Transaction Type', name: 'trantype', width: 25, classes: 'wrap', canSearch: true, formatter: showdetail,unformat:un_showdetail},
			{ label: 'Document No', name: 'docno', width: 20, classes: 'wrap', canSearch: true, align: 'right', formatter: padzero, unformat: unpadzero},
			{ label: 'Transaction Date', name: 'trandate', width: 27, classes: 'wrap', canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Transaction Time', name: 'trantime',hidden:true},
			{ label: 'Sender/Receiver', name: 'sndrcv', width: 30, classes: 'wrap', canSearch: true, formatter: showdetail,unformat:un_showdetail},
			{ label: 'SndRcvType', name: 'sndrcvtype', width: 30, classes: 'wrap', hidden:true},
			{ label: 'Amount', name: 'amount', width: 20, align: 'right', classes: 'wrap', formatter:'currency'},
			{ label: 'Status', name: 'recstatus', width: 20, classes: 'wrap',},	
			{ label: ' ', name: 'Checkbox',sortable:false, width: 10,align: "center", formatter: formatterCheckbox },
			{ label: 'Request RecNo', name: 'srcdocno', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'remarks', name: 'remarks', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adduser', name: 'adduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adddate', name: 'adddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upduser', name: 'upduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upddate', name: 'upddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'requestby', name: 'requestby', width: 90, hidden: true },
			{ label: 'requestdate', name: 'requestdate', width: 90, hidden: true },
			{ label: 'supportby', name: 'supportby', width: 90, hidden: true },
			{ label: 'supportdate', name: 'supportdate', width: 40, hidden: true},
			{ label: 'verifiedby', name: 'verifiedby', width: 90, hidden: true },
			{ label: 'verifieddate', name: 'verifieddate', width: 90, hidden: true },
			{ label: 'approvedby', name: 'approvedby', width: 90, hidden: true },
			{ label: 'approveddate', name: 'approveddate', width: 40, hidden: true},
			{ label: 'support_remark', name: 'support_remark', width: 40, hidden:true},
			{ label: 'verified_remark', name: 'verified_remark', width: 40, hidden:true},
			{ label: 'approved_remark', name: 'approved_remark', width: 40, hidden:true},
			{ label: 'cancelled_remark', name: 'cancelled_remark', width: 40, hidden:true},
			{ label: 'source', name: 'source', width: 40, hidden:'true'},
			{ label: 'idno', name: 'idno', width: 90, hidden:true},
			{ label: 'isstype', name: 'isstype', width: 90, hidden:true},
			{ label: 'crdbfl', name: 'crdbfl', width: 90, hidden:true},
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'idno',
		sortorder:'desc',
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			$('#error_infront').text('');
			$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
			// let stat = selrowData("#jqGrid").recstatus;
			// if($('#jqGrid_selection').jqGrid('getGridParam', 'reccount') <= 0 && stat=='OPEN'){
			// 	$('#but_cancel_jq').show();
			// }else if(stat=='OPEN'){
			// 	$('#but_post_jq').show();
			// }

			urlParam2.filterVal[0]=selrowData("#jqGrid").recno; 
			urlParam2.join_filterCol = [['ivt.uomcode on =', 's.deptcode = ','s.year =','s.compcode =','s.unit ='],['p.compcode =']];
			urlParam2.join_filterVal = [['s.uomcode',selrowData("#jqGrid").txndept,moment(selrowData("#jqGrid").trandate).year(),'session.compcode','session.unit'],['session.compcode']];
			
			populate_form(selrowData("#jqGrid"));

			$('#txndeptdepan').text(selrowData("#jqGrid").txndept);//tukar kat depan tu
			$('#trantypedepan').text(selrowData("#jqGrid").trantype);
			$('#docnodepan').text(selrowData("#jqGrid").docno);

			$('#crdbfl').val(selrowData("#jqGrid").crdbfl);
			$('#isstype').val(selrowData("#jqGrid").isstype);

			refreshGrid("#jqGrid3",urlParam2);

			$("#pdfgen1").attr('href','./inventoryTransaction/showpdf?recno='+selrowData("#jqGrid").recno);

			$("#pdfgen2").attr('href','./inventoryTransaction/showpdf?recno='+selrowData("#jqGrid").recno);

		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			let stat = selrowData("#jqGrid").recstatus;
			if(stat=='POSTED'){
				$("#jqGridPager td[title='View Selected Row']").click();
			}else{
				$("#jqGridPager td[title='Edit Selected Row']").click();
			}
		},
		gridComplete: function () {
			$('#but_cancel_jq,#but_post_jq,#but_reopen_jq').hide();
			if (oper == 'add' || oper == null || $("#jqGrid").jqGrid('getGridParam', 'selrow') == null) {
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			$('#' + $("#jqGrid").jqGrid('getGridParam', 'selrow')).focus();
			$("#searchForm input[name=Stext]").focus();
			fdl.set_array().reset();
			populate_form(selrowData("#jqGrid"));

			cbselect.checkbox_function_on();
			cbselect.refresh_seltbl();
			//empty_form();
		},
		loadComplete: function(){
			//calc_jq_height_onchange("jqGrid");
		},
		
	});

	////////////////////// set label jqGrid right ////////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid");

	/////////////////////////start grid pager/////////////////////////////////////////////////////////

	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam,oper);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-info-sign",
		title:"View Selected Row",  
		onClickButton: function(){
			oper='view';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view', '');
			// reqRecNo();
			refreshGrid("#jqGrid2",urlParam2);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer", id:"glyphicon-edit", position: "first",  
		buttonicon:"glyphicon glyphicon-edit",
		title:"Edit Selected Row",  
		onClickButton: function(){
			oper='edit';
			selRowId=$("#jqGrid").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit', '');
			// reqRecNo();
			refreshGrid("#jqGrid2",urlParam2);
		}, 
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-plus", 
		id: 'glyphicon-plus',
		title:"Add New Row", 
		onClickButton: function(){
			oper='add';
			$( "#dialogForm" ).dialog( "open" );
		},
	});

	//////////handle searching, its radio button and toggle /////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');

	//////////add field into param, refresh grid if needed///////////////////////////////////////////////
	addParamField('#jqGrid',false,saveParam,['upddate','upduser','source','crdbfl','isstype','adduser','adddate','idno','docno','recno','compcode','recstatus','Checkbox']);

	////////////////////////////////hide at dialogForm///////////////////////////////////////////////////
	function hideatdialogForm(hide,saveallrow){
		if(saveallrow == 'saveallrow'){
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#saveDetailLabel").hide();
			$("#jqGridPager2SaveAll,#jqGridPager2CancelAll").show();
		}else if(hide){
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#jqGridPager2SaveAll,#jqGridPager2CancelAll").hide();
			$("#saveDetailLabel").show();
		}else{
			$("#jqGrid2_iladd,#jqGrid2_iledit,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll").show();
			$("#saveDetailLabel,#jqGridPager2SaveAll,#jqGridPager2CancelAll").hide();
		}
	}

	/////////////////////////////////trantype////////////////////////////////////////////////////////////

	function inputTrantypeValue(isstype,crdbfl){
		switch(isstype.toUpperCase()){
			case 'TRANSFER':
				caseTransfer();
				showReqRecNo();
				break;
			case 'ADJUSTMENT':
				caseAdjustment(crdbfl);
				hideReqRecNo();
				break;
			case 'LOAN':
				caseAdjustment(crdbfl);
				hideReqRecNo();
				break;
			case 'ISSUE':
				caseAdjustment(crdbfl);
				showReqRecNo();
				break;
			case 'OTHERS':
				hideReqRecNo();
				break;
		}

		case_tui_tuo($('#trantype').val());

		function caseTransfer(){
			$("#jqGrid2").jqGrid('showCol', 'qtyonhandrecv');
			$("#jqGrid2").jqGrid('showCol', 'uomcoderecv');
			$("#jqGrid2").jqGrid('setColProp', 'netprice', 
				{formatter:'currency', 
				formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
				 editrules:{required:true}, editable:true});

			$("#jqGrid2").jqGrid('setColProp', 'expdate', 
				{ width: 130, classes: 'wrap', editable:true,
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
						editrules:{required: false,custom:true, custom_func:cust_rules},
							edittype:'custom',	editoptions:
							    {  custom_element:expdateCustomEdit,
							       custom_value:galGridCustomValue 	
							    },editoptions:{dataInit:null}
				});

			$("#jqGrid2").jqGrid('setColProp', 'uomcoderecv', 
				{ label: 'UOM Code Recv Dept', name: 'uomcoderecv', width: 130, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},
					formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:uomcoderecvCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
				});

			$("#sndrcv_parent, label[for=sndrcv]").show();
			$("#sndrcvtype_parent, label[for=sndrcvtype]").show();

			$("label[for=srcdocno]").show();
			$("#srcdocno_parent").show();
			$("#srcdocno").attr('required',true);

			$("#sndrcvtype option[value='DEPARTMENT']").show();
			$("#sndrcvtype option[value='Supplier'], #sndrcvtype option[value='Other']").hide();

			$("#sndrcv").attr('data-validation', 'required');
			$("#sndrcvtype").attr('data-validation', 'required');

			$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));			
		}

		function caseAdjustment(crdbfl){
			$("#jqGrid2").jqGrid('hideCol', 'qtyonhandrecv');
			$("#jqGrid2").jqGrid('hideCol', 'uomcoderecv');
			$("#jqGrid2").jqGrid('setColProp', 'netprice', 
				{formatter:'currency', 
				formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
				 editrules:{required:true}, editable:true});
			if(crdbfl.toUpperCase()=='IN'){
				$("#jqGrid2").jqGrid('setColProp', 'expdate', 
					{width: 100,editable:true,formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},edittype: 'text',editrules:{required: false,custom:false, custom_func:null},
						editoptions: {
		                    dataInit: function (element) {
		                        $(element).datepicker({
		                            id: 'expdate_datePicker',
		                            dateFormat: 'dd/mm/yy',
		                            minDate: 1,
		                            showOn: 'focus',
		                            changeMonth: true,
				  					changeYear: true,
									onSelect : function(){
										$(this).focus();
									}
		                        });
		                    }
		                } 
					});
			}else if(crdbfl.toUpperCase()=='OUT'){
				$("#jqGrid2").jqGrid('setColProp', 'expdate', 
					{ width: 130, classes: 'wrap', editable:true,
					formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
							editrules:{required: false,custom:true, custom_func:cust_rules},
								edittype:'custom',	editoptions:
								    {  custom_element:expdateCustomEdit,
								       custom_value:galGridCustomValue ,
								       dataInit:null
								    }
					});
			}

			$("#jqGrid2").jqGrid('setColProp', 'uomcoderecv', 
				{ label: 'UOM Code Recv Dept', name: 'uomcoderecv', width: 130, classes: 'wrap', editable:true,
					editrules:{required: false,custom:true, custom_func:cust_rules},
					formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:uomcoderecvCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
				});
			
			$("label[for=sndrcv],label[for=sndrcvtype],#sndrcvtype_parent, #sndrcv_parent").hide();
				
			$("#sndrcv").removeAttr('data-validation');
			$("#sndrcvtype").removeAttr('data-validation');
			$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
		}
	}

	function case_tui_tuo(ttype){
		if(ttype.toUpperCase() == 'TUI'){
			
			$('#sndrcv_label').text("Sender");
			$('#txndept_label').text("Receiver Department");
			$("#jqGrid2").jqGrid('hideCol', 'qtyonhandrecv');
			$("#jqGrid2").jqGrid('hideCol', 'uomcoderecv');
			$("#jqGrid2").jqGrid('setColProp', 'netprice', 
				{formatter:'currency', 
				formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
				 editrules:{required:true}, editable:true});

			$("#jqGrid2").jqGrid('setColProp', 'uomcoderecv', 
				{ label: 'UOM Code Recv Dept', name: 'uomcoderecv', width: 130, classes: 'wrap', editable:true,
					editrules:{required: false,custom:true, custom_func:cust_rules},
					formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:uomcoderecvCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
				});

			$("#jqGrid2").jqGrid('setColProp', 'expdate', 
				{width: 100,editable:true,formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},edittype: 'text',editrules:{required: false,custom:false, custom_func:null},
					editoptions: {
	                    dataInit: function (element) {
	                        $(element).datepicker({
	                            id: 'expdate_datePicker',
	                            dateFormat: 'dd/mm/yy',
	                            minDate: 1,
	                            showOn: 'focus',
	                            changeMonth: true,
			  					changeYear: true,
								onSelect : function(){
									$(this).focus();
								}
	                        });
	                    }
	                } 
				});

			$("#sndrcv_parent, label[for=sndrcv],#sndrcvtype_parent, label[for=sndrcvtype]").show();
			hideReqRecNo();
				
			$("#sndrcvtype option[value='DEPARTMENT']").show();
			$("#sndrcvtype option[value='Supplier'], #sndrcvtype option[value='Other']").hide();

			$("#sndrcv").attr('data-validation', 'required');
			$("#sndrcvtype").attr('data-validation', 'required');

			$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
		}else if(ttype.toUpperCase() == 'TUO'){
			
			$('#sndrcv_label').text("Receiver");
			$('#txndept_label').text("Sender Department");
			$("#jqGrid2").jqGrid('hideCol', 'qtyonhandrecv');
			$("#jqGrid2").jqGrid('hideCol', 'uomcoderecv');
			$("#jqGrid2").jqGrid('setColProp', 'netprice', 
				{formatter:'currency', 
				formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
				 editrules:{required:true}, editable:true});

			$("#jqGrid2").jqGrid('setColProp', 'uomcoderecv', 
				{ label: 'UOM Code Recv Dept', name: 'uomcoderecv', width: 130, classes: 'wrap', editable:true,
					editrules:{required: false,custom:true, custom_func:cust_rules},
					formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:uomcoderecvCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
				});

			$("#jqGrid2").jqGrid('setColProp', 'expdate', 
				{ width: 130, classes: 'wrap', editable:true,
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
						editrules:{required: false,custom:true, custom_func:cust_rules},
							edittype:'custom',	editoptions:
							    {  custom_element:expdateCustomEdit,
							       custom_value:galGridCustomValue ,
							       dataInit:null
							    }
				});

			$("#sndrcv_parent, label[for=sndrcv],#sndrcvtype_parent, label[for=sndrcvtype]").show();
			showReqRecNo();

			$("#sndrcvtype option[value='DEPARTMENT']").show();
			$("#sndrcvtype option[value='Supplier'], #sndrcvtype option[value='Other']").hide();

			$("#sndrcv").attr('data-validation', 'required');
			$("#sndrcvtype").attr('data-validation', 'required');
		}
	}

	function expdate_stat(isstype,crdbfl){
		dialog_expdate.off();
		switch(isstype.toUpperCase()){
			case 'TRANSFER':
				dialog_expdate.on();
				break;
			case 'ADJUSTMENT':
				caseCrdbfl(crdbfl);
				break;
			case 'LOAN':
				caseCrdbfl(crdbfl);
				break;
		}

		function caseCrdbfl(crdbfl){
			if(crdbfl.toUpperCase()=='IN'){
				dialog_expdate.off()
			}else{
				dialog_expdate.on();
			}
		}
	}

	/////////////////////////////////REQ REC NO////////////////////////////////////////////////////////////
	function reqRecNo(isstype){
		
		switch(isstype){
			case "Issue":
			case "Transfer":
				showReqRecNo();
				break;
			case "Others":
				hideReqRecNo();
				break;
			default:
				hideReqRecNo();
				break;
		}
	}

	function hideReqRecNo(){
		$("label[for=srcdocno]").hide();
		$("#srcdocno_parent").hide();
		$("#srcdocno").removeAttr('required');
	}

	function showReqRecNo(){
		$("label[for=srcdocno]").show();
		$("#srcdocno_parent").show();
		$("#srcdocno").attr('required',true);
	}

	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
	$("#dialog_remarks_oper").dialog({
		autoOpen: false,
		width: 4/10 * $(window).width(),
		modal: true,
		open: function( event, ui ) {
			$('#remarks_oper').val('');
		},
		close: function( event, ui ) {
			$("#but_cancel_jq").attr('disabled',false);
		},
		buttons : [{
			text: "Submit",click: function() {
				$("#but_cancel_jq").attr('disabled',true);
				if($('#remarks_oper').val() == ''){
					alert('Remarks for rejection is required!');
				}else{
					$(this).attr('disabled',true);
					var idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');
					var obj={};
					
					obj.idno_array = idno_array;
					obj.oper = 'reject';
					obj.remarks = $("#remarks_oper").val();
					obj._token = $('#_token').val();
					oper=null;
					
					$.post( './inventoryTransaction/form', obj , function( data ) {
						refreshGrid('#jqGrid', urlParam);
						$(this).attr('disabled',true);
						cbselect.empty_sel_tbl();
					}).fail(function(data) {
						$('#error_infront').text(data.responseText);
						$(this).attr('disabled',true);
					}).success(function(data){
						$(this).attr('disabled',true);
					});
					$(this).dialog('close');
				}
			}
			},{
			text: "Cancel",click: function() {
				$(this).dialog('close');
			}
		}]
	});

	$("#but_cancel_jq").click(function(){
		$("#but_cancel_jq").attr('disabled',true);
		if($(this).data('oper') == 'cancel'){
			if (confirm("Are you sure to reject this purchase request?") == true) {
				$("#dialog_remarks_oper").dialog( "open" );
			}
		}
	});

	$("#but_cancel_from_reject_jq").click(function(){
		$(this).attr('disabled',true);
		var self_ = this;
		var idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');
		var obj={};
		
		obj.idno_array = idno_array;
		obj.oper = $(self_).data('oper');//cancel_from_reject
		obj._token = $('#_token').val();
		oper=null;

		if(confirm("Are you sure you want to cancel this Document?") == true) {
			obj.idno_array = [selrowData('#jqGrid').purreqhd_idno];
		}else{
			return false
		}
		
		$.post( './inventoryTransaction/form', obj , function( data ) {
			refreshGrid('#jqGrid', urlParam);
			$(self_).attr('disabled',false);
			cbselect.empty_sel_tbl();
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
			$(self_).attr('disabled',false);
		}).success(function(data){
			$(self_).attr('disabled',false);
		});
	});
	
	$("#but_post_jq").click(function(){
		$("#but_post_jq").attr('disabled',true);
		var idno_array = [];
	
		idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');
		var obj={};
		obj.idno_array = idno_array;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		oper=null;

		$.post('inventoryTransaction/form',obj,function (data) {
			cbselect.empty_sel_tbl();
			refreshGrid("#jqGrid", urlParam);
			$("#but_post_jq").attr('disabled',false);
		}).fail(function (data) {
			$('#error_infront').text(data.responseText);
			// alert(data.responseText);
			$("#but_post_jq").attr('disabled',false);
		}).done(function (data) {
			//2nd successs?
		});
	});

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form,selfoper,saveParam,obj){
		if(obj==null){
			obj={};
		}
		saveParam.oper=selfoper;

		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
			
		},'json').fail(function (data) {
			// alert(data.responseJSON.message);
			myfail_msg.add_fail({
				id:'response',
				textfld:"",
				msg:data.responseText,
			});
			dialog_txndept.on();
			dialog_trantype.on();
			dialog_sndrcv.on();
			dialog_requestRecNo.on();
			$("#saveDetailLabel").show();
		}).done(function (data) {
			hideatdialogForm(false);
			
			addmore_jqgrid2.state = true;
			
			if(selfoper=='add'){

				oper='edit';//sekali dia add terus jadi edit lepas tu
				$('#recno').val(data.recno);
				$('#docno').val(data.docno);
				$('#amount').val(data.totalAmount);
				$('#idno').val(data.idno);//just save idno for edit later
				$('#adduser').val(data.adduser);
				$('#adddate').val(data.adddate);
				
				urlParam2.filterVal[0] = data.recno; 
				urlParam2.join_filterCol = [['ivt.uomcode on =', 's.deptcode = ','s.year =','s.compcode =','s.unit ='],['p.compcode =']];
				urlParam2.join_filterVal = [['s.uomcode',$('#txndept').val(),moment($('#trandate').val()).year(),'session.compcode','session.unit'],['session.compcode']];
			}else if(selfoper=='edit'){
				//doesnt need to do anything
				$('#upduser').val(data.upduser);
				$('#upddate').val(data.upddate);
			}
			// refreshGrid('#jqGrid2', urlParam2);
			disableForm('#formdata');
		});
	}
	
	$("#dialogForm").on('change keypress','#formdata :input','#formdata :textarea',function(){
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	$("#dialogForm").on('click','#formdata a.input-group-addon',function(){
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	/////////////////////////////populate data for dropdown search By////////////////////////////
	searchBy();
	function searchBy(){
		$.each($("#jqGrid").jqGrid('getGridParam','colModel'), function( index, value ) {
			if(value['canSearch']){
				if(value['selected']){
					$( "#searchForm [id=Scol]" ).append(" <option selected value='"+value['name']+"'>"+value['label']+"</option>");
				}else{
					$( "#searchForm [id=Scol]" ).append(" <option value='"+value['name']+"'>"+value['label']+"</option>");
				}
			}
			searchClick2('#jqGrid','#searchForm',urlParam);
		});
	}

	///////////////////////////populate data for dropdown tran dept/////////////////////////////
	// trandept();
	// function trandept(){
	// 	var param={
	// 		action:'get_value_default',
	// 		url: 'util/get_value_default',
	// 		field:['deptcode'],
	// 		table_name:'sysdb.department',
	// 		filterCol:['storedept'],
	// 		filterVal:['1']
	// 	}
	// 	$.get( param.url+"?"+$.param(param), function( data ) {
			
	// 	},'json').done(function(data) {
	// 		if(!$.isEmptyObject(data)){
	// 			$.each(data.rows, function(index, value ) {
	// 				if(value.deptcode.toUpperCase()== $("#deptcode").val().toUpperCase()){
	// 					$( "#searchForm [id=trandept]" ).append("<option selected value='"+value.deptcode+"'>"+value.deptcode+"</option>");
	// 				}else{
	// 					$( "#searchForm [id=trandept]" ).append(" <option value='"+value.deptcode+"'>"+value.deptcode+"</option>");
	// 				}
	// 			});
	// 		}
	// 	});
	// }

	////////////////////////////changing status and trandept trigger search/////////////////////////
	$('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	$('#trandept').on('change', searchChange);

	function whenchangetodate() {
		if($('#Scol').val()=='delordhd_trandate'){
			$("input[name='Stext']").show("fast");
			$("#tunjukname").hide("fast");
			$("input[name='Stext']").attr('type', 'date');
			$("input[name='Stext']").velocity({ width: "250px" });
			$("input[name='Stext']").on('change', searchbydate);
		} else if($('#Scol').val() == 'supplier_name'){
			$("input[name='Stext']").hide("fast");
			$("#tunjukname").show("fast");
		} else {
			$("input[name='Stext']").show("fast");
			$("#tunjukname").hide("fast");
			$("input[name='Stext']").attr('type', 'text');
			$("input[name='Stext']").velocity({ width: "100%" });
			$("input[name='Stext']").off('change', searchbydate);
		}
	}

	var supplierkatdepan = new ordialog(
		'supplierkatdepan', 'material.supplier', '#supplierkatdepan', 'errorField',
		{
			colModel: [
				{ label: 'Supplier Code', name: 'suppcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Name', name: 'name', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				let data = selrowData('#' + supplierkatdepan.gridname).suppcode;

				urlParam.searchCol=["suppcode"];
				urlParam.searchVal=[data];
				refreshGrid('#jqGrid', urlParam);
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
			title: "Select Transaction Department",
			open: function () {
				dialog_suppcode.urlParam.filterCol = ['recstatus'];
				dialog_suppcode.urlParam.filterVal = ['ACTIVE'];
			}
		}
	);
	supplierkatdepan.makedialog();

	function searchbydate() {
		search('#jqGrid', $('#searchForm [name=Stext]').val(), $('#searchForm [name=Scol] option:selected').val(), urlParam);
	}
	
	searchChange(true);
	function searchChange(once=false){
		var arrtemp = [$('#Status option:selected').val(), $('#trandept option:selected').val()];
		var filter = arrtemp.reduce(function(a,b,c){
			if(b=='All'){
				return a;
			}else{
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		},{fct:['ivt.recstatus','ivt.txndept'],fv:[],fc:[]});//tukar kat sini utk searching purreqhd.compcode','purreqhd.recstatus','purreqhd.prdept'

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		urlParam.WhereInCol = null;
		urlParam.WhereInVal = null;

		if(once){
			urlParam.searchCol=null;
			urlParam.searchVal=null;
			if($('#searchForm [name=Stext]').val().trim() != ''){
				let searchCol = ['ivt.recno'];
				let searchVal = ['%'+$('#searchForm [name=Stext]').val().trim()+'%'];
				urlParam.searchCol=searchCol;
				urlParam.searchVal=searchVal;
			}

			if($('#ttype').val() == 'TUO' || $('#ttype').val() == 'TUI'){
				urlParam.WhereInCol = ['ivt.trantype'];
				urlParam.WhereInVal = [['TUO', 'TUI']];
			}else if($('#ttype').val() == 'AI' || $('#ttype').val() == 'AO'){
				urlParam.WhereInCol = ['ivt.trantype'];
				urlParam.WhereInVal = [['AI','AO']];
			}else if($('#ttype').val() == 'GRI'){
				urlParam.WhereInCol = ['ivt.trantype'];
				urlParam.WhereInVal = [['GRI']];
			}else{
				urlParam.WhereNotInCol = ['ivt.trantype'];
				urlParam.WhereNotInVal = [['DS1', 'DS','TUO', 'TUI','AI','AO','GRI']];
			}

			once=false;
		}

		refreshGrid('#jqGrid',urlParam);
	}

	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'get_table_dtl',
		url:'./inventoryTransaction/table',
		field:['ivt.compcode','ivt.recno','ivt.lineno_','ivt.itemcode','p.description', 'ivt.qtyonhand','ivt.uomcode', 'ivt.qtyonhandrecv','ivt.uomcoderecv','s.maxqty',
		'ivt.txnqty','ivt.qtyrequest','ivt.netprice','ivt.amount','ivt.expdate','ivt.batchno'],
		table_name:['material.ivtmpdt AS ivt', 'material.stockloc AS s', 'material.productmaster AS p'],
		table_id:'lineno_',
		join_type:['LEFT JOIN', 'LEFT JOIN'],
		join_onCol:['ivt.itemcode', 'ivt.itemcode'],
		join_onVal:['s.itemcode','p.itemcode'],
		join_filterCol : [[],['p.compcode =']],
		join_filterVal : [[],['session.compcode']],
		filterCol:['ivt.recno', 'ivt.compcode', 'ivt.recstatus'],
		filterVal:['', 'session.compcode','<>.DELETE']
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./inventoryTransactionDetail/form",
		colModel: [
		 	{ label: 'idno', name: 'idno',key:true, hidden:true,editable:false},
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
		 	{ label: 'recno', name: 'recno', width: 50, classes: 'wrap',editable:false, hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 40, classes: 'wrap', editable:false, hidden:true},
			{ label: 'Item Code', name: 'itemcode', width: 100, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},
						edittype:'custom',	editoptions:
						    {  custom_element:itemcodeCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Item Description', name: 'description', width: 200, classes: 'wrap', editable:false},
			{ label: 'UOM Code\nSupplier Dept', name: 'uomcode', width: 100, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},
						edittype:'custom',	editoptions:
						    {  custom_element:uomcodetrdeptCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			
			{ label: 'Qty on Hand\nat Supplier Dept', name: 'qtyonhand', width: 100, align: 'right', classes: 'wrap', editable:true,	
				formatter:'integer',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'UOM Code Recv Dept', name: 'uomcoderecv', width: 130, classes: 'wrap', editable:true,
					editrules:{required: true,custom:true, custom_func:cust_rules},
					formatter: showdetail,
						edittype:'custom',	editoptions:
						    {  custom_element:uomcoderecvCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Qty on Hand at Recv Dept', name: 'qtyonhandrecv', width: 100, align: 'right', classes: 'wrap', editable:true,
				formatter:'integer',formatoptions:{thousandsSeparator: ",",},
				editoptions:{readonly: "readonly"},
				// formatter: formatter_recvqtyonhand,
			},
			
			{ label: 'Max Qty', name: 'maxqty', width: 80, align: 'right', classes: 'wrap',  
				editable:true,
				formatter:'integer',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: false},editoptions:{readonly: "readonly"},
			},
			{ label: 'Tran Qty', name: 'txnqty', width: 80, align: 'right', classes: 'wrap', 
					editable:true,
					formatter:'integer', formatoptions:{thousandsSeparator: ",",},
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

			{ label: 'Qty Request', name: 'qtyrequest', width: 80, align: 'right', classes: 'wrap',  
				editable:true,
				formatter:'integer',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: false},editoptions:{readonly: "readonly"},
			},
			{ label: 'Avg Cost', name: 'netprice', width: 90, align: 'right', classes: 'wrap', 
				editable:true,
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 4,},
					editrules:{required: true},edittype:"text",
						editoptions:{
						maxlength: 12,
						dataInit: function(element) {
							// if($('#isstype').val().toUpperCase() == 'ADJUSTMENT'){
							// 	$(element).prop('readonly',true);
							// }
							// if($('#trantype').val().toUpperCase() == 'TUI'){
							// 	$(element).prop('readonly',false);
							// }else{
							// 	$(element).prop('readonly',true);
							// }
							element.style.textAlign = 'right';  
							$(element).keypress(function(e){
								if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
									return false;
								 }
							});
						}
					},
			},
			{ label: 'Amount', name: 'amount', width: 100, align: 'right', classes: 'wrap', editable:true,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
				editrules:{required: true},editoptions:{readonly: "readonly"},
			},
			{ label: 'Expiry Date', name: 'expdate', width: 110, classes: 'wrap', editable:true,
			formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'},
					editrules:{required: false,custom:true, custom_func:cust_rules},
						edittype:'custom',	editoptions:
						    {  custom_element:expdateCustomEdit,
						       custom_value:galGridCustomValue 	
						    },
			},
			{ label: 'Batch No', name: 'batchno', width: 110, classes: 'wrap', editable:true,
					maxlength: 30,
			},
			{ label: 'Remarks', name: 'remarks_button', width: 100, formatter: formatterRemarks,unformat: unformatRemarks},
			{ label: 'Remarks', name: 'remarks', hidden:true},
			{ label: 'Remarks', name: 'remarks_show', width: 320, classes: 'wrap', hidden: false },
		],
		autowidth: false,
		shrinkToFit: false,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 100,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager2",
		loadComplete: function(){
			if(addmore_jqgrid2.edit == true){
			var linenotoedit_new = parseInt(linenotoedit)+1;
				if($.inArray(String(linenotoedit_new),$('#jqGrid2').jqGrid ('getDataIDs')) != -1){
					$('#jqGrid2').jqGrid ('setSelection', String(linenotoedit_new));
					$('#jqGrid2_iledit').click();
				}
			}
			else if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else if(addmore_jqgrid2.state == true && $('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				$('#jqGrid2_iladd').click();
			}else{
				let lastselrow = $('#jqGrid2').data('lastselrow');
				if(lastselrow == null || lastselrow == undefined){
					$('#jqGrid2').jqGrid ('setSelection', "1");
				}else{
					$('#jqGrid2 tr#'+lastselrow).focus().click();
					$('#jqGrid2').data('lastselrow',null);
				}
			}

			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
			//calc_jq_height_onchange("jqGrid2");
		},
		gridComplete: function(){
			$("#jqGrid2").find(".remarks_button").on("click", function(e){
				$("#remarks2").data('rowid',$(this).data('rowid'));
				$("#remarks2").data('grid',$(this).data('grid'));
				$("#dialog_remarks").dialog( "open" );
			});
			fdl.set_array().reset();
			// $( "#jqGrid2_ilcancel" ).off();
			// $( "#jqGrid2_ilcancel" ).on( "click", function(event) {
			// 	event.preventDefault();
			// 	event.stopPropagation();
			// 	bootbox.confirm({
			// 	    message: "Are you sure want to cancel?",
			// 	    buttons: {
			// 	        confirm: { label: 'Yes',className: 'btn-success'},
			// 	        cancel: {label: 'No',className: 'btn-danger'}
			// 		},
			// 		callback: function (result) {
			// 			if (result == true) {
			// 				$(".noti").empty();
			// 				$("#jqGrid2").jqGrid("clearGridData", true);
			// 				refreshGrid("#jqGrid2",urlParam2);
			// 			}
			// 			linenotoedit = null;
			// 	    }
			// 	});
			// });

			unsaved = false;
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			var result = ids.filter(function(text){
								if(text.search("jqg") != -1)return false;return true;
							});
			if(result.length == 0 && oper=='edit')unsaved = true;

			
		},
		ondblClickRow:function(rowid){
			if($('#jqGrid2_iledit').is(":visible")){
				$('#jqGrid2_iledit').click();
				$('#jqGrid2').data('lastselrow',rowid);
			}
		},
		onSelectRow:function(rowid, selected){
			myfail_msg.clear_fail();
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
		},
		afterShowForm: function (rowid) {
		},
		beforeSubmit: function(postdata, rowid){ 
			// dialog_itemcode.check(errorField);
			// dialog_expdate.check(errorField);
			// dialog_uomcoderecv.check(errorField);
			// dialog_uomcodetrdept.check(errorField);
	 	}
	});

	////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
	var myEditOptions = {
		keys: true,
		extraparam:{
		    "_token": $("#_token").val()
        },
		oneditfunc: function (rowid) {
			myfail_msg.clear_fail();
			errorField.length=0;
        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

			dialog_itemcode.on();
			dialog_expdate.on();
			dialog_uomcoderecv.on();
			dialog_uomcodetrdept.on();

			mycurrency2.array.length = 0;
			mycurrency_np.array.length = 0;
			// Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amount']", "#jqGrid2 input[name='netprice']"]);
			// Array.prototype.push.apply(mycurrency_np.array, ["#jqGrid2 input[name='qtyrequest']", "#jqGrid2 input[name='txnqty']", "#jqGrid2 input[name='maxqty']", "#jqGrid2 input[name='qtyonhandrecv']", "#jqGrid2 input[name='qtyonhand']"]);
			
			// $("input[name='gstpercent']").val('0')//reset gst to 0
			// mycurrency2.formatOnBlur();//make field to currency on leave cursor
			// mycurrency_np.formatOnBlur();//make field to currency on leave cursor
	
			$("#jqGrid2 input[name='uomcode'],#jqGrid2 input[name='uomcoderecv'],#jqGrid2 input[name='itemcode']").on('focus',remove_noti);

			$("input[name='batchno']").keydown(function(e) {//when click tab at qtyrequest, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
				// addmore_jqgrid2.state = true;
				// $('#jqGrid2_ilsave').click();
			});
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);

		},
		aftersavefunc: function (rowid, response, options) {
			myfail_msg.clear_fail();
			var resobj = JSON.parse(response.responseText);
			$('#recno').val(resobj.recno);
			$('#docno').val(resobj.docno);
			$('#amount').val(resobj.totalAmount);
			// $('#totamount').val(response.responseText);
			// $('#subamount').val(response.responseText);
			if(addmore_jqgrid2.state == true)addmore_jqgrid2.more=true; //only addmore after save inline
	    	//state true maksudnyer ada isi, tak kosong
			urlParam2.filterVal[0] = resobj.recno;
			refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
			errorField.length=0;
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
			$(".noti").empty();
		},
		errorfunc: function(rowid,response){
			errorField.length=0;
        	// alert(response.responseText);
        	myfail_msg.add_fail({
				id:'response',
				textfld:"",
				msg:response.responseText,
			});
        	// refreshGrid('#jqGrid2',urlParam2,'add');
	    	// $("#jqGridPager2Delete").show();
        },
        restoreAfterError : false,
		beforeSaveRow: function (options, rowid) {
			// if($("label[for=srcdocno]").is(":hidden")){
			// 	if($.inArray(rowid+"_uomcoderecv",errorField)!==-1){
			// 		errorField.splice($.inArray(rowid+"_uomcoderecv",errorField), 1);
			// 	}
			// 	if($.inArray(rowid+"_qtyonhand",errorField)!==-1){
			// 		errorField.splice($.inArray(rowid+"_qtyonhand",errorField), 1);
			// 	}
			// 	if(errorField.length>0)return false;
			// }else{
        	// 	if(errorField.length>0)return false;
			// }
			mycurrency2.formatOff();
			mycurrency_np.formatOff();

			// if(parseInt($('#jqGrid2 input[name="qtyrequest"]').val()) <= 0)return false;

			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);

			let editurl = "./inventoryTransactionDetail/form?"+
				$.param({					
					action: 'invTranDetail_save',
					h_idno: $('#idno').val(),
					docno:$('#docno').val(),
					recno:$('#recno').val(),
					sndrcv:$('#sndrcv').val(),
					txndept:$('#txndept').val(),
					trandate:$('#trandate').val(),
					trantype:$('#trantype').val(),
					remarks:data.remarks,
					lineno_:data.lineno_,
					amount:data.amount,
				});
			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
		},
		 afterrestorefunc : function( response ) {
			errorField.length=0;
			delay(function(){
				fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			}, 500 );
			hideatdialogForm(false);

			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
	    },
	    errorTextFormat: function (data) {
	    	alert(data);
	    }
	};

	var myEditOptions_edit = {
		keys: true,
		extraparam:{
		    "_token": $("#_token").val()
        },
		oneditfunc: function (rowid) {
			console.log(rowid);
			myfail_msg.clear_fail();
			errorField.length=0;
        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

			dialog_itemcode.off();
			$(dialog_itemcode.textfield).attr('disabled',true);
			dialog_expdate.on();
			dialog_uomcoderecv.off();
			$(dialog_uomcoderecv.textfield).attr('disabled',true);
			dialog_uomcodetrdept.off();
			$(dialog_uomcodetrdept.textfield).attr('disabled',true);

			mycurrency2.array.length = 0;
			mycurrency_np.array.length = 0;
			// Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amount']", "#jqGrid2 input[name='netprice']"]);
			// Array.prototype.push.apply(mycurrency_np.array, ["#jqGrid2 input[name='qtyrequest']", "#jqGrid2 input[name='txnqty']", "#jqGrid2 input[name='maxqty']", "#jqGrid2 input[name='qtyonhandrecv']", "#jqGrid2 input[name='qtyonhand']"]);
			
			// $("input[name='gstpercent']").val('0')//reset gst to 0
			mycurrency2.formatOff();//make field to currency on leave cursor
			mycurrency_np.formatOff();//make field to currency on leave cursor
	
			$("#jqGrid2 input[name='uomcode'],#jqGrid2 input[name='uomcoderecv'],#jqGrid2 input[name='itemcode']").on('focus',remove_noti);

			$("input[name='batchno']").keydown(function(e) {//when click tab at qtyrequest, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
				// addmore_jqgrid2.state = true;
				// $('#jqGrid2_ilsave').click();
			});

			$("#jqGrid2 input[name='txnqty'],#jqGrid2 input[name='netprice']").on('blur',errorField,calculate_amount_and_other);
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);

		},
		aftersavefunc: function (rowid, response, options) {
			myfail_msg.clear_fail();
			var resobj = JSON.parse(response.responseText);
			$('#recno').val(resobj.recno);
			$('#docno').val(resobj.docno);
			$('#amount').val(resobj.totalAmount);
			// $('#totamount').val(response.responseText);
			// $('#subamount').val(response.responseText);
			// if(addmore_jqgrid2.state == true)addmore_jqgrid2.more=true; //only addmore after save inline
	    	//state true maksudnyer ada isi, tak kosong
			// urlParam2.filterVal[0] = resobj.recno;
			refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
			errorField.length=0;
			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
			$(".noti").empty();
		},
		errorfunc: function(rowid,response){
			errorField.length=0;
        	// alert(response.responseText);
        	myfail_msg.add_fail({
				id:'response',
				textfld:"",
				msg:response.responseText,
			});
        	// refreshGrid('#jqGrid2',urlParam2,'add');
	    	// $("#jqGridPager2Delete").show();
        },
        restoreAfterError : false,
		beforeSaveRow: function (options, rowid) {
			// if($("label[for=srcdocno]").is(":hidden")){
			// 	if($.inArray(rowid+"_uomcoderecv",errorField)!==-1){
			// 		errorField.splice($.inArray(rowid+"_uomcoderecv",errorField), 1);
			// 	}
			// 	if($.inArray(rowid+"_qtyonhand",errorField)!==-1){
			// 		errorField.splice($.inArray(rowid+"_qtyonhand",errorField), 1);
			// 	}
			// 	if(errorField.length>0)return false;
			// }else{
        	// 	if(errorField.length>0)return false;
			// }
			mycurrency2.formatOff();
			mycurrency_np.formatOff();

			// if(parseInt($('#jqGrid2 input[name="qtyrequest"]').val()) <= 0)return false;

			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);

			let editurl = "./inventoryTransactionDetail/form?"+
				$.param({					
					action: 'invTranDetail_save',
					h_idno: $('#idno').val(),
					docno:$('#docno').val(),
					recno:$('#recno').val(),
					sndrcv:$('#sndrcv').val(),
					txndept:$('#txndept').val(),
					trandate:$('#trandate').val(),
					trantype:$('#trantype').val(),
					remarks:data.remarks,
					lineno_:data.lineno_,
					amount:data.amount,
				});
			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
		},
		 afterrestorefunc : function( response ) {
			errorField.length=0;
			delay(function(){
				fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			}, 500 );
			hideatdialogForm(false);

			calc_jq_height_onchange("jqGrid2",false,parseInt($('#jqGrid2_c').prop('clientHeight'))-150);
	    },
	    errorTextFormat: function (data) {
	    	alert(data);
	    }
	};

    //////////////////////////////////////////pager jqgrid2/////////////////////////////////////////////
	$("#jqGrid2").inlineNav('#jqGridPager2',{	
		add:true,
		edit:true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptions
		},
		editParams: myEditOptions_edit
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPager2Delete",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-trash",
		title:"Delete Selected Row",
		onClickButton: function(){
			selRowId = $("#jqGrid2").jqGrid ('getGridParam', 'selrow');
			if(!selRowId){
				bootbox.alert('Please select row');
			}else{
				bootbox.confirm({
				    message: "Are you sure you want to delete this row?",
				    buttons: {confirm: {label: 'Yes', className: 'btn-success',},cancel: {label: 'No', className: 'btn-danger' }
				    },
				    callback: function (result) {
				    	if(result == true){
				    		param={
				    			action: 'inventoryTransactionDetail_save',
								recno: $('#recno').val(),
								lineno_: selrowData('#jqGrid2').lineno_,

				    		}
				    		$.post( "./inventoryTransactionDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, function( data ){
							}).fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
								$('#amount').val(data);
								refreshGrid("#jqGrid2",urlParam2);
							});

						}else{
        					$("#jqGridPager2EditAll").show();
				    	}
				    	
				    }
				});
			}
		},
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPager2EditAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-th-list",
		title:"Edit All Row",
		onClickButton: function(){
			errorField.length=0;
			mycurrency2.array.length = 0;
			mycurrency_np.array.length = 0;
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
		    for (var i = 0; i < ids.length; i++) {

		        $("#jqGrid2").jqGrid('editRow',ids[i]);

		        // Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_amount"]);

		        //Array.prototype.push.apply(mycurrency_np.array, ["#"+ids[i]+"_qtyrequest"]);

		        dialog_itemcode.id_optid = ids[i];
		        dialog_itemcode.check(errorField,ids[i]+"_itemcode","jqGrid2",null,
		        	function(self){
		        		if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        },function(data,self){
			        	if(data.rows.length > 0){
							$("#jqGrid2 #"+self.id_optid+"_maxqty").val(data.rows[0].s_maxqty);
							$("#jqGrid2 #"+self.id_optid+"_netprice").val(data.rows[0].p_avgcost);
							$("#jqGrid2 #"+self.id_optid+"_convfactoruomcodetrdept").val(data.rows[0].u_convfactor);
							$("#jqGrid2 #"+self.id_optid+"_qtyonhand").val(data.rows[0].s_qtyonhand);
			        	}
			        }
			    );

				dialog_expdate.id_optid = ids[i];
		        // dialog_expdate.check('errorField',ids[i]+"_expdate","jqGrid2",null,
		        // 	function(self){
		        // 		if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			    //     }
			    // );

		        // dialog_uomcoderecv.id_optid = ids[i];
		        // dialog_uomcoderecv.check(errorField,ids[i]+"_uomcoderecv","jqGrid2",null,
		        // 	function(self){
			    //     	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			    //     }
			    // );

				// dialog_uomcodetrdept.id_optid = ids[i];
		        // dialog_uomcodetrdept.check(errorField,ids[i]+"_uomcode","jqGrid2",null,
		        // 	function(self){
			    //     	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			    //     }
			    // );

		       // cari_gstpercent(ids[i]);
		    }
		    onall_editfunc();
			hideatdialogForm(true,'saveallrow');
		},
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPager2SaveAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-download-alt",
		title:"Save All Row",
		onClickButton: function(){
			var ids = $("#jqGrid2").jqGrid('getDataIDs');

			var jqgrid2_data = [];
			mycurrency2.formatOff();
			mycurrency_np.formatOff();

			// if(errorField.length>0){
			// 	return false;
			// }

		    for (var i = 0; i < ids.length; i++) {
			//	if(parseInt($('#'+ids[i]+"_qtyrequest").val()) <= 0)return false;
				var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);
				let retval = check_cust_rules("#jqGrid2",data);
				if(retval[0]!= true){
					alert(retval[1]);
					return false;
				}

				// cust_rules()

		    	var obj = 
		    	{
		    		'lineno_' : data.lineno_,
		    		'itemcode' : $("#jqGrid2 input#"+ids[i]+"_itemcode").val(),
		    		'uomcode' : $("#jqGrid2 input#"+ids[i]+"_uomcode").val(),
		    		'uomcoderecv' : $("#jqGrid2 input#"+ids[i]+"_uomcoderecv").val(),
		    		'netprice' : $('#'+ids[i]+"_netprice").val(),
		    		'qtyonhand' : $('#'+ids[i]+"_qtyonhand").val(),
		    		'qtyonhandrecv' : $('#'+ids[i]+"_qtyonhandrecv").val(),
		    		'txnqty' : $('#'+ids[i]+"_txnqty").val(),
		    		'qtyrequest' : $('#'+ids[i]+"_qtyrequest").val(),
                    'unit' : $("#"+ids[i]+"_unit").val(),
		    		'amount' : $('#'+ids[i]+"_amount").val(),
		    		'batchno' : $('#'+ids[i]+"_batchno").val(),
		    		'remarks' : data.remarks,
		    		'expdate' : $('#jqGrid2 input#'+ids[i]+"_expdate").val(),

		    	}

		    	jqgrid2_data.push(obj);
		    }

			var param={
    			action: 'invTranDetail_save',
				_token: $("#_token").val(),
				recno: $('#recno').val(),
				docno:$('#docno').val(),
				sndrcv:$('#sndrcv').val(),
				txndept:$('#txndept').val(),
				trandate:$('#trandate').val(),
				
    		}

    		$('#jqGridPager2SaveAll,#jqGridPager2CancelAll').hide();
    		

    		$.post( "./inventoryTransactionDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				myfail_msg.add_fail({
					id:'response',
					textfld:"",
					msg:data.responseText,
				});
    			$('#jqGridPager2SaveAll,#jqGridPager2CancelAll').show();
			}).done(function(data){
				myfail_msg.clear_fail();
				// $('#totamount').val(data);
				// $('#subamount').val(data);
				mycurrency.formatOn();
				hideatdialogForm(false);
				refreshGrid("#jqGrid2",urlParam2);
			});
		},
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "jqGridPager2CancelAll",
		caption:"",cursor: "pointer",position: "last", 
		buttonicon:"glyphicon glyphicon-remove-circle",
		title:"Cancel",
		onClickButton: function(){
			hideatdialogForm(false);
			refreshGrid("#jqGrid2",urlParam2);
		},				
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "saveHeaderLabel",
		caption:"Header",cursor: "pointer",position: "last", 
		buttonicon:"",
		title:"Header"
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "saveDetailLabel",
		caption:"Detail",cursor: "pointer",position: "last", 
		buttonicon:"",
		title:"Detail"
	});

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field,table,  case_;
		switch(options.colModel.name){
			case 'itemcode':field=['itemcode','description'];table="material.productmaster";case_='itemcode';break;
			case 'uomcode':field=['uomcode','description'];table="material.uom";case_='uomcode';break;
			case 'uomcoderecv':field=['uomcode','description'];table="material.uom";case_='uomcoderecv';break;
			case 'txndept':field=['deptcode','description'];table="sysdb.department";case_='txndept';break;
			case 'trantype':field=['trantype','description'];table="material.ivtxntype";case_='trantype';break;
			case 'sndrcv':field=['deptcode','description'];table="sysdb.department";case_='sndrcv';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('inventoryTransaction',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

	function formatterCheckbox(cellvalue, options, rowObject){
		let idno = cbselect.idno;
		let recstatus = rowObject.recstatus;

		if(options.gid != "jqGrid" && rowObject.recstatus == 'OPEN'){
			return "<button class='btn btn-xs btn-danger btn-md' id='delete_"+rowObject.idno+"' ><i class='fa fa-trash' aria-hidden='true'></i></button>";
		}

		if($('#scope').val() == 'ALL'){
			if(recstatus == "OPEN"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#scope').val() == 'SUPPORT'){
			if(recstatus == "PREPARED"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#scope').val() == 'VERIFIED'){
			if(recstatus == "SUPPORT"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#scope').val() == 'APPROVED'){
			if(recstatus == "VERIFIED"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#scope').val() == 'CANCEL'){
			if(recstatus == "OPEN"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#scope').val() == 'CANCEL_POSTED'){
			if(recstatus == "POSTED"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#scope').val() == 'REOPEN_TUO'){
			if(recstatus == "POSTED"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}else if($('#scope').val() == 'REOPEN'){
			if(recstatus == "CANCELLED"){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}
		}

		return ' ';
	}

	function formatter_recvqtyonhand(cellvalue, options, rowObject){
		let year=($('#trandate').val().trim()!='')?moment($('#trandate').val()).year():selrowData('#jqGrid').trandate;
		let txndept=($('#txndept').val().trim()!='')?$('#txndept').val():selrowData('#jqGrid').txndept;
		var param={action:'get_value_default',
			url: 'util/get_value_default',field:['qtyonhand'],table_name:'material.stockloc'}

		param.filterCol = ['year','itemcode', 'deptcode','uomcode'];
		param.filterVal = [year,rowObject[3], txndept,rowObject[4]];

		$.get( param.url+"?"+$.param(param), function( data ) {

		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows)){
				$("#"+options.gid+" #"+options.rowId+" td:nth-child("+(options.pos+1)+")").text(data.rows[0].qtyonhand);
			}
		});
		return "";
	}

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp=null;
		switch(name){
			case 'Item Code':temp=$('#itemcode');break;
			case 'UOM Code Supplier Dept':temp=$('#uomcode');break;
			case 'UOM Code Recv Dept':temp=$('#uomcoderecv');break;
			// case 'Expiry Date':temp=$('#expdate');break;
		}
		if(temp == null) return [true,''];
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function itemcodeCustomEdit(val,opt){
		// val = (val=="undefined")? "" : val.slice(0, val.search("[<]"));	
		val = getEditVal(val);
		
		// if(/[a-zA-Z]/g.test(val)){
		// 	return $('<span>'+val+'</span>')
		// }
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="itemcode" type="text" class="form-control input-sm" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function uomcodetrdeptCustomEdit(val,opt){
		// val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		val = getEditVal(val);
		// if(/[a-zA-Z]/g.test(val)){
		// 	return $('<span>'+val+'</span>')
		// }
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="uomcode" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}


	function uomcoderecvCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="uomcoderecv" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function expdateCustomEdit(val,opt){
		val = getEditVal(val);
		 return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'"  name="expdate" type="text" class="form-control input-sm" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');
		
	}
	function galGridCustomValue (elem, operation, value){
		console.log(elem);
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	//////////////////////////////////////////saveDetailLabel////////////////////////////////////////////
	$("#saveDetailLabel").click(function(){
		hideatdialogForm(true);
		$("#saveDetailLabel").hide();
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = false;
		if($('#formdata').isValid({requiredFields:''},conf,true)){
			dialog_txndept.off();
			dialog_trantype.off();
			dialog_sndrcv.off();
			dialog_requestRecNo.off();
			saveHeader("#formdata",oper,saveParam);
			unsaved = false;
		}else{
			$("#saveDetailLabel").show();
			mycurrency.formatOn();
		}
	});

	function saveDetailLabel(callback=null){
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = false;
		if($('#formdata').isValid({requiredFields:''},conf,true)){
			dialog_txndept.off();
			dialog_trantype.off();
			dialog_sndrcv.off();
			dialog_requestRecNo.off();
			saveHeader("#formdata",oper,saveParam);
			errorField.length=0;
		}else{
			mycurrency.formatOn();
		}
		if(callback!=null)callback();
	}

	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function(){
		emptyFormdata(errorField,'#formdata2');
		hideatdialogForm(true);
		addmore_jqgrid2.state = false;
		dialog_txndept.on();
		dialog_trantype.on();
		dialog_sndrcv.on();
		dialog_requestRecNo.on();
		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		refreshGrid("#jqGrid2",urlParam2);
		errorField.length=0;
	});


	////////////////////////////// jqGrid2_iladd + jqGrid2_iledit /////////////////////////////
	$("#jqGrid2_iladd, #jqGrid2_iledit").click(function(){
		// $("#jqGridPager2Delete,#saveHeaderLabel").hide();
		// dialog_itemcode.on();//start binding event on jqgrid2
		// dialog_uomcodetrdept.on();
		// dialog_uomcoderecv.on();
		expdate_stat($('#isstype').val(),$('#crdbfl').val());
		$("#jqGrid2 input[name='txnqty'],#jqGrid2 input[name='netprice']").on('blur',errorField,calculate_amount_and_other);
		$("#jqGrid2 input[name='qtyonhandrecv']").on('blur',calculate_conversion_factor);
		$("#jqGrid2 input[name='qtyonhand']").on('blur',checkQOH);
		$("input[name='batchno']").keydown(function(e) {//when click tab at batchno, auto save
			var code = e.keyCode || e.which;
			if (code == '9')$('#jqGrid2_ilsave').click();
		});
	});

	///////////////////////////////////////// QtyOnHand Recv/////////////////////////////////////////////
	function getQOHsndrcv(){
		var param={
			func:'getQOHsndrcv',
			action:'get_value_default',
			url: 'util/get_value_default',
			field:['qtyonhand'],
			table_name:'material.stockloc'
		}

		param.filterCol = ['year','itemcode', 'deptcode','uomcode'];
		param.filterVal = [moment($('#trandate').val()).year(), $("#jqGrid2 input[name='itemcode']").val(),$('#sndrcv').val(), $("#jqGrid2 input[name='uomcoderecv']").val()];

		$.get( param.url+"?"+$.param(param), function( data ) {
			
			$("#jqGrid2 input[name='qtyonhandrecv']").val('');
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows) && data.rows[0].qtyonhand!=null){
				$("#jqGrid2 input[name='qtyonhandrecv']").val(data.rows[0].qtyonhand);
			}else if($("#sndrcv").val()!=''){
				bootbox.alert({
				    message: "No stock location at department code: "+$('#sndrcv').val(),
				    callback: function (result) {
				    	if(!result){
				    		$("#jqGrid2_ilcancel").click();
				    	}else{
							
				    	}
				    }
				});
			}else{
				
			}
		});
	}

	///////////////////////////////////////// QtyOnHand Recv/////////////////////////////////////////////
	function getQOHtxndept(){
		var param={
			func:'getQOHtxndept',
			action:'get_value_default',
			url: 'util/get_value_default',
			field:['qtyonhand'],
			table_name:'material.stockloc'
		}

		param.filterCol = ['year','itemcode', 'deptcode','uomcode'];
		param.filterVal = [moment($('#trandate').val()).year(), $("#jqGrid2 input[name='itemcode']").val(),$('#txndept').val(), $("#jqGrid2 input[name='uomcode']").val()];

		
			$.get( param.url+"?"+$.param(param), function( data ) {
			
			$("#jqGrid2 input[name='qtyonhand']").val('');
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows) && data.rows[0].qtyonhand!=null){
				$("#jqGrid2 input[name='qtyonhand']").val(data.rows[0].qtyonhand);
			}else if($("#txndept").val()!=''){
				bootbox.alert({
				    message: "No stock location at department code: "+$('#txndept').val(),
				    callback: function (result) {
				    	if(!result){
				    		$("#jqGrid2_ilcancel").click();
				    	}else{
							
				    	}
				    }
				});
			}else{
				
			}
		});
	}

	/////////////calculate conv fac/////////////////////////////////
	 function calculate_conversion_factor(event) {
		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		var id="#jqGrid2 #"+id_optid+"_qtyonhand";
		var fail_msg = "Please Choose Suitable UOMCode & POUOMCode";
		var name = "calculate_conversion_factor";

		let convfactor_bool = false;
		let convfactor_uomcodetrdept = parseFloat($("#jqGrid2 #"+id_optid+"_convfactoruomcodetrdept").val());
		let convfactor_uomcoderecv = parseFloat($("#jqGrid2 #"+id_optid+"_convfactoruomcoderecv").val());
		let qtyonhand = parseFloat($("#jqGrid2 #"+id_optid+"_qtyonhand").val());

		var balconv = convfactor_uomcodetrdept*qtyonhand%convfactor_uomcoderecv;
		if (balconv  == 0) {
			if($.inArray(id,errorField)!==-1){
				errorField.splice($.inArray(id,errorField), 1);
			}
			$('.noti').find("li[data-errorid='"+name+"']").detach();
		} else {
			$('.noti').prepend("<li data-errorid='"+name+"'>"+fail_msg+"</li>");
			if($.inArray(id,errorField)===-1){
				errorField.push( id );
			}
		}
	}

	/////////////checkQOH//////////////////////////////////
	function checkQOH(event) {
		var fail = false;
		var id="#jqGrid2 input[name='qtyonhand']"
		var fail_msg = "Qty on Hand cant be 0"
		var name = "checkQOH";
		let crdbfl=$('#crdbfl').val();
		let isstype=$('#isstype').val();

		let qtyonhand = parseInt($("#jqGrid2 input[name='qtyonhand']").val());
		if(qtyonhand<=0 && isstype.toUpperCase()=='ADJUSTMENT' && crdbfl.toUpperCase() == 'IN'){
			fail=false;
		}else if(qtyonhand<=0){
			fail=true;
		}
		errorIt('qtyonhand',errorField,fail,fail_msg);
	}

	///////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////calculate amount////////////////////////////
	function calculate_amount_and_other(event){

		// if(event.target.value == 0){
		// 	errorIt(event.target.name,errorField,true,"Transaction Quantity Cannot Be Zero");
		// 	return false;
		// }

		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		var fail=false,fail_msg="";
		let qtyonhand=parseInt($("#jqGrid2 #"+id_optid+"_qtyonhand").val());
		let txnqty=parseInt($("#jqGrid2 #"+id_optid+"_txnqty").val());
		let netprice=parseFloat($("#jqGrid2 #"+id_optid+"_netprice").val());

		let crdbfl=$('#crdbfl').val();
		let isstype=$('#isstype').val();
		if(event.target.name=='txnqty'){
			switch(crdbfl.toUpperCase()){
				case "OUT":
					// if(event.target.value >= qtyonhand && isstype=='Others'){
					// 	fail_msg = "Transaction Quantity Cannot be greater than Quantity On Hand";
					// 	event.target.value=$("#jqGrid2 #"+id_optid+"_txnqty").val();fail=true;
					// }else if(qtyonhand<event.target.value){
					// 	fail_msg = "Transaction quantity exceed quantity on hand";
					// 	event.target.value=$("#jqGrid2 #"+id_optid+"_txnqty").val();fail=true;
					// }else if(qtyonhand<event.target.value && isstype=='Transfer'){
					// 	fail_msg = "Transaction quantity exceed quantity on hand";
					// 	event.target.value=$("#jqGrid2 #"+id_optid+"_txnqty").val();fail=true;
					// }
					break;
				case "IN":
					// if(event.target.name == 0 && isstype=='Others'){
					// 	fail_msg = "Transaction Quantity Cannot Be Zero";
					// 	event.target.value=$("#jqGrid2 #"+id_optid+"_txnqty").val();fail=true;
					// }
					break;
				default:
					break;
			}
		}else{
			if(crdbfl=='OUT'&&event.target.value==0){
				fail_msg = "Net Price Cannot Be Zero";
				event.target.value='0.00';fail=true;
			}
		}
		errorIt(event.target.name,errorField,fail,fail_msg);
		let amount=txnqty*netprice;
		$("#jqGrid2 #"+id_optid+"_amount").val(amount.toFixed(4));
	}

	
	function remove_noti(event){
		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));

		remove_error("#jqGrid2 #"+id_optid+"_uomcoderecv");
		remove_error("#jqGrid2 #"+id_optid+"_txnqty");
		delay(function(){
			remove_error("#jqGrid2 #"+id_optid+"_uomcoderecv");
		}, 500 );


		$(".noti").empty();

	}

	/////////////////////////////edit all//////////////////////////////////////////////////

	function onall_editfunc(){
	
		errorField.length=0;
		//start binding event on jqgrid2
		dialog_itemcode.off();
		$(dialog_itemcode.textfield).attr('disabled',true);
		dialog_expdate.on();
		dialog_uomcoderecv.off();
		$(dialog_uomcoderecv.textfield).attr('disabled',true);
		dialog_uomcodetrdept.off();
		$(dialog_uomcodetrdept.textfield).attr('disabled',true);
		
		// mycurrency2.formatOnBlur();//make field to currency on leave cursor
		// mycurrency_np.formatOnBlur();//make field to currency on leave cursor


		$("#jqGrid2 input[name='txnqty'],#jqGrid2 input[name='netprice']").on('blur',errorField,calculate_amount_and_other);
	
		// $("#jqGrid2 input[name='uomcode'],#jqGrid2 input[name='uomcoderecv'],#jqGrid2 input[name='itemcode']").on('focus',remove_noti);
	}

	var butt1_rem = 
		[{
			text: "Save",click: function() {
				let newval = $("#remarks2").val();
				let rowid = $('#remarks2').data('rowid');
				$("#jqGrid2").jqGrid('setRowData', rowid ,{remarks:newval});
				$("#jqGrid2").jqGrid('setRowData', rowid ,{remarks_show:newval});
				$(this).dialog('close');
			}
		},{
			text: "Cancel",click: function() {
				$(this).dialog('close');
			}
		}];

	var butt2_rem = 
		[{
			text: "Close",click: function() {
				$(this).dialog('close');
			}
		}];

	$("#dialog_remarks").dialog({
		autoOpen: false,
		width: 4/10 * $(window).width(),
		modal: true,
		open: function( event, ui ) {
			let rowid = $('#remarks2').data('rowid');
			let grid = $('#remarks2').data('grid');
			$('#remarks2').val($(grid).jqGrid('getRowData', rowid).remarks);
			let exist = $("#jqGrid2 #"+rowid+"_txnqty").length;
			if(grid == '#jqGrid3' || exist==0){ // lepas ni letak or not edit mode
				$("#remarks2").prop('disabled',true);
				$( "#dialog_remarks" ).dialog( "option", "buttons", butt2_rem);
			}else{
				$("#remarks2").prop('disabled',false);
				$( "#dialog_remarks" ).dialog( "option", "buttons", butt1_rem);
			}
		},
		close: function(){
		},
		buttons : butt2_rem
	});

	////////////////////////////////////////calculate_line_totgst_and_totamt////////////////////////////
	var mycurrency2 =new currencymode([]);
	var mycurrency_np =new currencymode([],true);

	////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////
	$("#jqGrid3").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 100,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3",

		onSelectRow:function(rowid, selected){
			// inputTrantypeValue();
		},

		gridComplete:function(){
			$("#jqGrid3").find(".remarks_button").on("click", function(e){
				$("#remarks2").data('rowid',$(this).data('rowid'))
				$("#remarks2").data('grid',$(this).data('grid'))
				$("#dialog_remarks").dialog( "open" );
			});
			fdl.set_array().reset();
		},
		loadComplete: function(){
			//calc_jq_height_onchange("jqGrid3");
		},
	});
	
	jqgrid_label_align_right("#jqGrid3");

	////////////////////////////////////////////////////ordialog////////////////////////////////////////
	var dialog_txndept = new ordialog(
		'txndept','sysdb.department','#txndept',errorField,
		{	colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
				{label:'Unit',name:'sector', hidden:true},
			],
			urlParam: {
				filterCol:['storedept', 'recstatus','compcode'],//,'sector'
				filterVal:['1', 'ACTIVE','session.compcode'],//, 'session.unit'
				WhereInCol:['deptcode'],
				WhereInVal:[['FKWSTR','IMP','KHEALTH']]
			},
			ondblClickRow: function () {
				$('#trantype').focus();
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
			title:"Select Transaction Department",
			open: function(){
				dialog_txndept.urlParam.filterCol=['storedept', 'recstatus','compcode'];//,'sector'
				dialog_txndept.urlParam.filterVal=['1', 'ACTIVE','session.compcode'];//, 'session.unit'
				dialog_txndept.urlParam.WhereInCol=['deptcode'];
				dialog_txndept.urlParam.WhereInVal=[['FKWSTR','IMP','KHEALTH']];
			},
			close: function(obj_){
				$('#trantype').focus();
			}
		},'urlParam','radio','tab'
	);
	dialog_txndept.makedialog(true);

	var dialog_trantype = new ordialog(
		'trantype','material.ivtxntype','#trantype',errorField,
		{	colModel:[
				{label:'Transaction Type',name:'trantype',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'isstype',name:'isstype',width:100,classes:'pointer',hidden:false},
				{label:'crdbfl',name:'crdbfl',width:100,classes:'pointer',hidden:false},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_trantype.gridname);
				if($('#ttype').val() == 'TUO' || $('#ttype').val() == 'TUI'){
					case_tui_tuo(data['trantype']);
				}else{
					inputTrantypeValue(data['isstype'],data['crdbfl']);
				}

					$('#crdbfl').val(data['crdbfl']);
					$('#isstype').val(data['isstype']);
					// reqRecNo(data['isstype']);
					
					$("#sndrcvtype").val("");
					$('#trandate').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();

					$('#trandate').focus();

				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Transaction Type",
			open: function(){
				if($('#ttype').val() == 'TUO' || $('#ttype').val() == 'TUI'){
					dialog_trantype.urlParam.WhereInCol=['trantype'];
					dialog_trantype.urlParam.WhereInVal=[['TUO', 'TUI']];
					dialog_trantype.urlParam.filterCol=['recstatus'];
					dialog_trantype.urlParam.filterVal=['ACTIVE'];
				}else if($('#ttype').val() == 'GRI'){
					dialog_trantype.urlParam.WhereInCol=['trantype'];
					dialog_trantype.urlParam.WhereInVal=[['GRI']];
					dialog_trantype.urlParam.filterCol=['recstatus'];
					dialog_trantype.urlParam.filterVal=['ACTIVE'];
				}else if($('#ttype').val() == 'AI' || $('#ttype').val() == 'AO'){
					dialog_trantype.urlParam.WhereInCol=['trantype'];
					dialog_trantype.urlParam.WhereInVal=[['AI','AO']];
					dialog_trantype.urlParam.filterCol=['recstatus'];
					dialog_trantype.urlParam.filterVal=['ACTIVE'];
				}else{
					dialog_trantype.urlParam.whereNotInCol=['trantype'];
					dialog_trantype.urlParam.whereNotInVal=[['DS1', 'DS','TUO', 'TUI','AI','AO','GRI']];
					dialog_trantype.urlParam.filterCol=['recstatus'];
					dialog_trantype.urlParam.filterVal=['ACTIVE'];
				}
			},
			close: function(obj_){
				$("#trandate").focus().select();
			}
		},'urlParam','radio','tab'
	);
	dialog_trantype.makedialog();

	var dialog_sndrcv = new ordialog(
		'sndrcv','sysdb.department','#sndrcv',errorField,
		{	colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['recstatus'],
				filterVal:['ACTIVE'],
				// open: function(){
				// 	if($('#trantype').val().trim() == 'TR') {
				// 		filterCol:['storedept', 'recstatus'];
				// 		filterVal:['1', '<>.DELETE'];
				// 		// filterInCol:['deptcode'];
				// 		// filterInType:['NOT IN'];
				// 		// filterInVal:[[$('#txndept').val()]];
				// 	}else {
				// 		filterCol:['recstatus'];
				// 		filterVal:['ACTIVE'];
				// 		whereNotIn:['deptcode'];
				// 		whereNotInVal:[[$('#txndept').val()]];
				// 	}
				// }
			},
			ondblClickRow:function(){
				$('#trantime').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#trantime').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
			}
		},{
			title:"Select Receiver Department",
			open: function(){
				if($('#trantype').val().trim() == 'TR') {
					dialog_sndrcv.urlParam.filterCol=['storedept', 'recstatus'];
					dialog_sndrcv.urlParam.filterVal=['1', '<>.DELETE'];
					// dialog_sndrcv.urlParam.filterInCol=['deptcode'];
					// dialog_sndrcv.urlParam.filterInType=['NOT IN'];
					// dialog_sndrcv.urlParam.filterInVal=[[$('#txndept').val()]];
				}else {
					dialog_sndrcv.urlParam.filterCol=['recstatus'];
					dialog_sndrcv.urlParam.filterVal=['ACTIVE'];
					dialog_sndrcv.urlParam.whereNotInCol=['deptcode'];
					dialog_sndrcv.urlParam.whereNotInVal=[[$('#txndept').val()]];
				}
			},
			close: function(obj_){
				$("#remarks").focus().select();
			}
		},'urlParam','radio','tab'
	);
	dialog_sndrcv.makedialog();

	var dialog_itemcode = new ordialog(
		'itemcode',['material.stockloc AS s','material.product AS p', 'material.uom AS u'],"#jqGrid2 input[name='itemcode']",errorField,
		{	colModel:
			[
				{label:'Item Code',name:'s_itemcode',width:150,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'p_description',width:300,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Quantity On Hand',name:'s_qtyonhand',width:100,classes:'pointer'},
				{label:'UOM Code',name:'s_uomcode',width:100,classes:'pointer'},
				{label:'Max Quantity',name:'s_maxqty',width:100,classes:'pointer'},
				{label:'Average Cost', name: 'p_avgcost', width: 100, classes: 'pointer', hidden:false },
				{label:'Conversion', name: 'u_convfactor', width: 50, classes: 'pointer', hidden:true },
			],
			urlParam: {
				filterCol:['s.compcode','s.year','s.deptcode'],
				filterVal:['session.compcode', moment($('#trandate').val()).year(),$('#txndept').val()]
			},
			ondblClickRow:function(event){
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}

				let data=selrowData('#'+dialog_itemcode.gridname);
				//$("#jqGrid2 input[name='itemcode']").val(data['s_itemcode']);
				$("#jqGrid2 input[name='description']").val(data['p_description']);
				$("#jqGrid2 input[name='uomcode']").val(data['s_uomcode']);
				$("#jqGrid2 input[name='maxqty']").val(data['s_maxqty']);
				$("#jqGrid2 input[name='netprice']").val(data['p_avgcost']);
				$("#jqGrid2 input[name='convfactoruomcodetrdept']").val(data['u_convfactor']);
				$("#jqGrid2 input[name='qtyonhand']").val(data['s_qtyonhand']);
				$("#jqGrid2 input[name='uomcoderecv']").val(data['s_uomcode']);

			    dialog_uomcodetrdept.id_optid = id_optid;
				dialog_uomcodetrdept.urlParam.filterVal=['session.compcode',$('#txndept').val(),data['s_itemcode'],moment($('#trandate').val()).year()];
				dialog_uomcodetrdept.check(errorField,id_optid+"_uomcode","jqGrid2",null,
		        	function(self){
			        	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        }
			    );

			    dialog_uomcoderecv.id_optid = id_optid;
				dialog_uomcoderecv.urlParam.filterVal=['session.compcode',$('#sndrcv').val(),data['s_itemcode'],moment($('#trandate').val()).year()];
				dialog_uomcoderecv.check(errorField,id_optid+"_uomcoderecv","jqGrid2",null,
		        	function(self){
			        	if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
			        }
			    );

			    $("#jqGrid2").jqGrid('setRowData', id_optid ,{description:data['p_description']});
				
				getQOHtxndept();
				checkQOH();
				
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
		},{
			title:"Select Item For Stock Transaction",
			open:function(){
				dialog_itemcode.urlParam.fixPost="true";
				dialog_itemcode.urlParam.table_id="none_";
				dialog_itemcode.urlParam.filterCol=['s.compcode','s.year','s.deptcode','s.unit'];
				dialog_itemcode.urlParam.filterVal=['session.compcode',moment($('#trandate').val()).year(),$('#txndept').val(),'notnull'];
				dialog_itemcode.urlParam.join_type=['LEFT JOIN', 'LEFT JOIN'];
				dialog_itemcode.urlParam.join_onCol=['s.itemcode','u.uomcode'];
				dialog_itemcode.urlParam.join_onVal=['p.itemcode', 's.uomcode'];
				dialog_itemcode.urlParam.join_filterCol=[['p.compcode on =', 'p.uomcode on =','p.recstatus =','p.unit on ='], []];
				dialog_itemcode.urlParam.join_filterVal=[['s.compcode','s.uomcode','ACTIVE','s.unit'], []];
			},
			close:function(obj_){
				$("#jqGrid2 #"+obj_.id_optid+"_itemcode").parent().siblings( ".help-block" ).html("");
				$("#jqGrid2 #"+obj_.id_optid+"_txnqty").focus().select();
			},
			after_check:function(data,self,id,fail,errorField){
				$( id ).parent().siblings( ".help-block" ).html('');
			}
		},'urlParam','radio','tab'
	);
	dialog_itemcode.makedialog(false);
	//false means not binding event on jqgrid2 yet, after jqgrid2 add, event will be bind

	var dialog_uomcodetrdept = new ordialog(
		'uomcode',['material.stockloc AS s','material.product AS p','material.uom AS u'],"#jqGrid2 input[name='uomcode']",errorField,
		{	colModel:
			[
				{label:'UOM code',name:'s_uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'u_description',width:450,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Department code',name:'s_deptcode',width:200,classes:'pointer'},
				{label:'Item code',name:'s_itemcode',width:200,classes:'pointer'},
				{label:'Conversion', name: 'u_convfactor', width: 100, classes: 'pointer', hidden:true},
				{label:'Average Cost', name: 'p_avgcost', width: 200, classes: 'pointer'},
				{label:'Quantity On Hand',name:'s_qtyonhand',width:200,classes:'pointer'},
			],
			urlParam: {
				fixPost:"true",
				table_id:"none_",
				join_type:['LEFT JOIN','LEFT JOIN'],
				join_onCol:['s.itemcode','s.uomcode'],
				join_onVal:['p.itemcode','u.uomcode'],
				join_filterCol:[['s.compcode on =', 's.uomcode on =']],
				join_filterVal:[['p.compcode','p.uomcode']],
				filterCol:['s.compcode','s.deptcode','s.itemcode','s.year'],
				filterVal:['session.compcode',$('#txndept').val(),$("#jqGrid2 input[name='itemcode']").val(),moment($('#trandate').val()).year()]
			},
			ondblClickRow:function(event){
				if(event.type == 'keydown'){

					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));

					$(event.currentTarget).parent().next().html('');
				}else{

					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));

					$(event.currentTarget).parent().next().html('');
				}

				let data=selrowData('#'+dialog_uomcodetrdept.gridname);

				$("#jqGrid2 #"+id_optid+"_convfactoruomcodetrdept").val(data['u_convfactor']);

				$("#jqGrid2 #"+id_optid+"_uomcode").val(data['s_uomcode']);
				$("#jqGrid2 #"+id_optid+"_qtyonhand").val(data['s_qtyonhand']);
				$("#jqGrid2 #"+id_optid+"_netprice").val(data['p_avgcost']);
				$("#jqGrid2 #"+id_optid+"_uomcoderecv").val(data['s_uomcode']);
				checkQOH(event);
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
			
		},{
			title:"Select UOM Code For Item",
			open:function(obj){
				dialog_uomcodetrdept.urlParam.fixPost="true";
				dialog_uomcodetrdept.urlParam.table_id="none_";
				dialog_uomcodetrdept.urlParam.filterCol=['s.compcode','s.deptcode','s.itemcode','s.year'];
				dialog_uomcodetrdept.urlParam.filterVal=['session.compcode',$('#txndept').val(),$("#jqGrid2 input#"+obj.id_optid+"_itemcode").val(),moment($('#trandate').val()).year()];
				dialog_uomcodetrdept.urlParam.join_type=['LEFT JOIN','LEFT JOIN'];
				dialog_uomcodetrdept.urlParam.join_onCol=['s.itemcode','s.uomcode'];
				dialog_uomcodetrdept.urlParam.join_onVal=['p.itemcode','u.uomcode'];
				dialog_uomcodetrdept.urlParam.join_filterCol=[['s.compcode on =', 's.uomcode on =']];
				dialog_uomcodetrdept.urlParam.join_filterVal=[['p.compcode','p.uomcode']];
			}
		},'urlParam','radio','tab'
	);
	dialog_uomcodetrdept.makedialog(false);

	var dialog_uomcoderecv = new ordialog(
		'uomcoderecv', ['material.stockloc AS s','material.product AS p','material.uom AS u'], "#jqGrid2 input[name='uomcoderecv']", errorField,
		{
			colModel:
			[
				{label:'UOM code',name:'s_uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'u_description',width:450,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Department code',name:'s_deptcode',width:200,classes:'pointer'},
				{label:'Item code',name:'s_itemcode',width:200,classes:'pointer'},
				{label:'Conversion', name: 'u_convfactor', width: 200, classes: 'pointer', hidden:true},
				{label:'Average Cost', name: 'p_avgcost', width: 200, classes: 'pointer'},
				{label:'Quantity On Hand',name:'s_qtyonhand',width:200,classes:'pointer'},
			],
			urlParam: {
						fixPost:"true",
						table_id:"none_",
						join_type:['LEFT JOIN','LEFT JOIN'],
						join_onCol:['s.itemcode','s.uomcode'],
						join_onVal:['p.itemcode','u.uomcode'],
						join_filterCol:[['s.compcode on =', 's.uomcode on =']],
						join_filterVal:[['p.compcode','p.uomcode']],
						filterCol:['s.compcode','s.deptcode','s.itemcode','s.year'],
						filterVal:['session.compcode',$('#sndrcv').val(),$("#jqGrid2 input[name='itemcode']").val(),moment($('#trandate').val()).year()]
					},
			ondblClickRow: function (event) {
				if(event.type == 'keydown'){

					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));

					$(event.currentTarget).parent().next().html('');
				}else{

					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));

					$(event.currentTarget).parent().next().html('');
				}

				let data=selrowData('#'+dialog_uomcoderecv.gridname);

				$("#jqGrid2 #"+id_optid+"_convfactoruomcoderecv").val(data['convfactor']);
				$("#jqGrid2  #"+id_optid+"_txnqty").focus().select();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					// $(obj.textfield).closest('td').next().find("input[type=text]").focus();
					$("#jqGrid2 input#"+obj.id_optid+"_txnqty").focus().select();
				}
			}

		}, {
			title: "Select PO UOM Code For Item",
			open: function (obj) {
				dialog_uomcoderecv.urlParam.fixPost="true";
				dialog_uomcoderecv.urlParam.table_id="none_";
				dialog_uomcoderecv.urlParam.filterCol=['s.compcode','s.deptcode','s.itemcode','s.year'];
				dialog_uomcoderecv.urlParam.filterVal=['session.compcode',$('#sndrcv').val(),$("#jqGrid2 input#"+obj.id_optid+"_itemcode").val(),moment($('#trandate').val()).year()];
				dialog_uomcoderecv.urlParam.join_type=['LEFT JOIN','LEFT JOIN'];
				dialog_uomcoderecv.urlParam.join_onCol=['s.itemcode','s.uomcode'];
				dialog_uomcoderecv.urlParam.join_onVal=['p.itemcode','u.uomcode'];
				dialog_uomcoderecv.urlParam.join_filterCol=[['s.compcode on =', 's.uomcode on =']];
				dialog_uomcoderecv.urlParam.join_filterVal=[['p.compcode','p.uomcode']];

			},
			close: function () {
				// $(dialog_pouom.textfield)			//lepas close dialog focus on next textfield 
				// 	.closest('td')						//utk dialog dalam jqgrid jer
				// 	.next()
				// 	.find("input[type=text]").focus();
			}
		}, 'urlParam','radio','tab'
	);
	dialog_uomcoderecv.makedialog(false);

	/*var dialog_uomcoderecv = new ordialog(
		'uomcoderecv',['material.stockloc AS s','material.uom AS u'],"#jqGrid2 input[name='uomcoderecv']",errorField,
		{	colModel:
			[
				{label:'UOM code',name:'s_uomcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'u_description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Department code',name:'s_deptcode',width:150,classes:'pointer'},
				{label:'Conversion', name: 'u_convfactor', width: 100, classes: 'pointer'},
				{label:'Item code',name:'s_itemcode',width:150,classes:'pointer'},
			],
			urlParam: {
				filterCol:['s.compcode','s.deptcode','s.itemcode','s.year'],
				filterVal:['session.compcode',$('#sndrcv').val(),$("#jqGrid2 input[name='itemcode']").val(),moment($('#trandate').val()).year(), $("#jqGrid2 input[name='uomcode']").val()]
				
			},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_uomcoderecv.gridname);
				$("#convfactoruomcoderecv").val(data['u_convfactor']);
				
				getQOHsndrcv();
				$("#jqGrid2 input[name='txnqty']").focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}
			
		},{
			title:"Select UOM Code For Item",
			open:function(){
				dialog_uomcoderecv.urlParam.fixPost="true";
				dialog_uomcoderecv.urlParam.table_id="none_";
				dialog_uomcoderecv.urlParam.filterCol=['s.compcode','s.deptcode','s.itemcode','s.year', 'u.uomcode'];
				dialog_uomcoderecv.urlParam.filterVal=['session.compcode',$('#sndrcv').val(),$("#jqGrid2 input[name='itemcode']").val(),moment($('#trandate').val()).year(), $("#jqGrid2 input[name='uomcode']").val()];
				dialog_uomcoderecv.urlParam.join_type=['LEFT JOIN'];
				dialog_uomcoderecv.urlParam.join_onCol=['s.uomcode'];
				dialog_uomcoderecv.urlParam.join_onVal=['u.uomcode'];
				dialog_uomcoderecv.urlParam.join_filterCol=[['s.compcode on =']];
				dialog_uomcoderecv.urlParam.join_filterVal=[['u.compcode']];
			}
		},'urlParam','radio','tab'
	);
	dialog_uomcoderecv.makedialog(true);
*/
	var dialog_expdate = new ordialog(
		'expdate',['material.stockexp'],"#jqGrid2 input[name='expdate']",'errorField',
		{	colModel:
			[
				{label:'Expiry Date',name:'expdate',width:200,classes:'pointer',canSearch:true,or_search:true,checked:true,},
				{label:'Batch No',name:'batchno',width:400,classes:'pointer',canSearch:true,or_search:true},
				{label:'Quantity',name:'balqty',width:400,classes:'pointer'},
				{label:'itemcode', name: 'itemcode', width: 50, classes: 'pointer', hidden:true },
				{label:'uomcode', name: 'uomcode', width: 50, classes: 'pointer', hidden:true },
				{label:'deptcode', name: 'deptcode', width: 50, classes: 'pointer', hidden:true },
			],
			urlParam: {
						filterCol:['compcode','year','deptcode', 'uomcode', 'itemcode'],
						filterVal:['session.compcode',moment($('#trandate').val()).year(),$("#txndept").val(), $("#jqGrid2 input[name='uomcode']").val(), $("#jqGrid2 input[name='itemcode']").val()]
			},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_expdate.gridname);
				$("#jqGrid2 input[name='batchno']").val(data['batchno']);
			},
		/*	gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$(obj.textfield).closest('td').next().find("input[type=text]").focus();
				}
			}*/
		},{
			title:"Select Expiry Date",
			open: function(obj){
				dialog_expdate.urlParam.filterCol=['compcode','year','deptcode', 'uomcode', 'itemcode'];
				dialog_expdate.urlParam.filterVal=['session.compcode',moment($('#trandate').val()).year(),$("#txndept").val(),$("#jqGrid2 input#"+obj.id_optid+"_uomcode").val(),$("#jqGrid2 input#"+obj.id_optid+"_itemcode").val()];
			}
		},'urlParam','radio','tab'
	);
	dialog_expdate.makedialog(false);

	var dialog_requestRecNo = new ordialog(
		'srcdocno','material.ivreqhd','#srcdocno','errorField',
		{	colModel:[
				{label:'Request RecNo',name:'recno',width:50,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Record Status',name:'recstatus',width:50,classes:'pointer'},
				{label:'Request Department',width:100,name:'reqdept',canSearch:true},
				{label:'Request Date',width:50,name:'reqdt'},
				{label:'Remarks',name:'remarks',width:100,classes:'pointer'},
			],
			sortname: 'recno',
			sortorder: "desc",
			urlParam: {
				filterCol:['compcode', 'reqtodept'],
				filterVal:['session.compcode',$("#txndept").val()],
				WhereInCol:['recstatus'],
				WhereInVal:[['PARTIAL','POSTED']]
			},	
			ondblClickRow:function(){
				refreshGrid("#jqGrid2",null,"kosongkan");
				let data = selrowData('#' + dialog_requestRecNo.gridname);
				
				$("#recstatus").val('ACTIVE');
				$("#remarks").val(data['remarks']);
				$('#referral').val(data['recno']);
				$('#sndrcv').val(data['reqdept']);
				$('#sndrcvtype').val('DEPARTMENT');
				$('#sndrcvtype').focus();

				if($('#ttype').val() == 'TUO' || $('#ttype').val() == 'TUI'){
					$('#trantype').val('TUO');
					case_tui_tuo('TUO');
					$('#crdbfl').val('TRANSFER');
					$('#isstype').val('OUT');

				}else{
					$('#trantype').val('TR');
					inputTrantypeValue('TRANSFER','IN');
					$('#crdbfl').val('TRANSFER');
					$('#isstype').val('IN');
				}

				var urlParam2 = {
					action: 'get_value_default',
					url: 'util/get_value_default',
					field: ['ivdt.compcode', 'ivdt.recno', 'ivdt.lineno_', 'ivdt.itemcode', 'p.description', 'ivdt.uomcode', 'ivdt.pouom',
					's.maxqty', 's.qtyonhand', 'ivdt.qtyrequest', 'ivdt.qtytxn', 'ivdt.qohconfirm', 'ivdt.reqdept', 'ivdt.ivreqno',
					'ivdt.recstatus','ivdt.netprice','ivdt.expdate','ivdt.batchno'],
					table_name: ['material.ivreqdt AS ivdt ', 'material.stockloc AS s', 'material.productmaster AS p'],
					table_id: 'lineno_',
					join_type: ['LEFT JOIN', 'LEFT JOIN'],
					join_onCol: ['ivdt.itemcode', 'ivdt.itemcode'],
					join_onVal: ['s.itemcode', 'p.itemcode'],
					join_filterCol : [['ivdt.reqdept on =','ivdt.uomcode on =','s.year =','s.unit =','s.compcode ='],['p.compcode =']],
					join_filterVal : [['s.deptcode','s.uomcode',moment().year(),'session.unit','session.compcode'],['session.compcode']],
					filterCol: ['ivdt.recno', 'ivdt.compcode', 'ivdt.recstatus'],
					filterVal: [data['recno'], 'session.compcode', '<>.DELETE'],
					sortby:['lineno_ desc']
				};

				$.get("util/get_value_default?" + $.param(urlParam2), function (data) {
				}, 'json').done(function (data) {
					if (!$.isEmptyObject(data.rows)) {
						$("#jqGrid2").jqGrid("clearGridData", true);
						data.rows.forEach(function(elem) {
								$("#jqGrid2").jqGrid('addRowData', elem['lineno_'] ,
									{
										compcode:elem['compcode'],
										recno:elem['recno'],
										reqdept:elem['reqdept'],
										ivreqno:elem['ivreqno'],
										reqlineno:elem['lineno_'],
										itemcode:elem['itemcode'],
										description:elem['description'],
										uomcode:elem['uomcode'],
										qtyonhand:elem['qtyonhand'],
										uomcoderecv:elem['pouom'],
										qtyonhandrecv:elem['qtyonhand'],
										txnqty:elem['qtytxn'],
										maxqty:elem['maxqty'],
										qtyrequest:elem['qtybalance'],
										netprice:elem['netprice'],
										expdate:elem['expdate'],
										batchno:elem['batchno']
									
									}
								);
							
						});

					} else {

					}
				});
			
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#sndrcvtype').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
			}
		},{
			title:"Select Request RecNo",
			open: function(){
				dialog_requestRecNo.urlParam.filterCol=['compcode', 'reqtodept'];
				dialog_requestRecNo.urlParam.filterVal=['session.compcode', $("#txndept").val()];
				dialog_requestRecNo.urlParam.WhereInCol = ['recstatus'];
				dialog_requestRecNo.urlParam.WhereInVal = [['PARTIAL','POSTED']];
			}
		}, 'none','radio','tab'
	);
	dialog_requestRecNo.makedialog(false);

	$("#jqGrid_selection").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid").jqGrid('getGridParam','colModel'),
		shrinkToFit: false,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		sortname: 'idno',
		sortorder: "desc",
		onSelectRow: function (rowid, selected) {
			let rowdata = $('#jqGrid_selection').jqGrid ('getRowData');
		},
		loadComplete: function(){
			calc_jq_height_onchange("jqGrid_selection");
		},
	})
	jqgrid_label_align_right("#jqGrid_selection");
	cbselect.on();

	function errorIt(name,errorField,fail,fail_msg){
		let id = "#jqGrid2 input[name='"+name+"']";
		if(!fail){
			if($.inArray(id,errorField)!==-1){
				errorField.splice($.inArray(id,errorField), 1);
			}
			$( id ).parent().removeClass( "has-error" ).addClass( "has-success" );
			$( id ).removeClass( "error" ).addClass( "valid" );
			$('.noti').find("li[data-errorid='"+name+"']").detach();
		}else{
			if($.inArray(id,errorField)===-1){
				errorField.push( id );
				$( id ).parent().removeClass( "has-success" ).addClass( "has-error" );
				$( id ).removeClass( "valid" ).addClass( "error" );
				$('.noti').prepend("<li data-errorid='"+name+"'>"+fail_msg+"</li>");
			}
		}
	}

	$("#jqGrid3_panel").on("show.bs.collapse", function(){
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
	});
	
	// var genpdf = new generatePDF('#pdfgen1','#formdata','#jqGrid2');
	// genpdf.printEvent();

	function check_cust_rules(grid,data){
		var cust_val =  true;
		Object.keys(data).every(function(v,i){
			cust_val = cust_rules('', $(grid).jqGrid('getGridParam','colNames')[i]);
			if(cust_val[0] == false){
				return false;
			}return true
		});
		return cust_val;
	}

	function delete_dd(idno){
		var obj = {
			'oper':'delete_dd',
			'idno':idno,
			'_token':$('#_token').val()
		}
		if(idno != null || idno !=undefined || idno != ''){
			$.post( './inventoryTransactionDetail/form',obj,function( data ) {
					
			});
		}
	}

});

function formatterRemarks(cellvalue, options, rowObject){
	return "<button class='remarks_button btn btn-success btn-xs' type='button' data-rowid='"+options.rowId+"' data-lineno_='"+rowObject.lineno_+"' data-grid='#"+options.gid+"' data-remarks='"+rowObject.remarks+"'><i class='fa fa-file-text-o'></i> remark</button>";
}

function unformatRemarks(cellvalue, options, rowObject){
	return null;
}

function populate_form(obj){
	//panel header
	$('#txndept_show').text(obj.txndept);
	$('#trantype_show').text(obj.trantype);
	$('#docno_show').text(padzero(obj.docno));
	
}

function empty_form(){
	$('#txndept_show').text('');
	$('#trantype_show').text('');
	$('#docno_show').text('');

}

function remark_button_class(grid){
	$("#dialog_remarks_view").dialog({
		autoOpen: false,
		width: 4/10 * $(window).width(),
		modal: true,
		open: function( event, ui ) {
		},
		close: function( event, ui ) {
			$('#remarks_view').val('');
		},
		buttons : [{
			text: "Cancel",click: function() {
				$(this).dialog('close');
			}
		}]
	});

	this.grid=grid;
	this.selrowdata;

	this.remark_btn_init = function(selrowdata){
		this.selrowdata = selrowdata;
		$('i.my_remark').hide();
		$('i.my_remark').off('click');
		if(this.selrowdata.support_remark != ''){
			$('i#support_remark_i').show();
			$('i#support_remark_i').data('remark',this.selrowdata.support_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Support Remark');
		}
		if(this.selrowdata.verified_remark != ''){
			$('i#verified_remark_i').show();
			$('i#verified_remark_i').data('remark',this.selrowdata.verified_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Verified Remark');
		}
		if(this.selrowdata.approved_remark != ''){
			$('i#approved_remark_i').show();
			$('i#approved_remark_i').data('remark',this.selrowdata.approved_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Approved Remark');
		}
		if(this.selrowdata.cancelled_remark != ''){
			$('i#cancelled_remark_i').show();
			$('i#cancelled_remark_i').data('remark',this.selrowdata.cancelled_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Cancelled Remark');
		}
		$('i.my_remark').on('click',function(){
			$('#remarks_view').val($(this).data('remark'));
			$("#dialog_remarks_view").dialog( "open" );
		});
	}
}

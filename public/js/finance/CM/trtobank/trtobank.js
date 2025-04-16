
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {

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
					message : ''
				}];
			}
		},
	};

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency =new currencymode(['#outamount', '#amount']);
	var mycurrency2 =new currencymode(['#outamount', '#amount']);
	var fdl = new faster_detail_load();
	var myfail_msg = new fail_msg_func();
	
	///////////////////////////////// trandate check date validate from period////////// ////////////////
	// var actdateObj = new setactdate(["#apacthdr_postdate"]);
	// actdateObj.getdata().set();

	////////////////////////////////////start dialog//////////////////////////////////////
	var oper=null;
	var unsaved = false;
	$("#dialogForm")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				myfail_msg.clear_fail();
				duplicate_documentno = false;
				parent_close_disabled(true);
				unsaved = false;
				errorField.length=0;
				mycurrency.formatOnBlur();
				switch (oper) {
					case state = 'add':
					$("#jqGrid2").jqGrid("clearGridData", false);
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					enableForm('#formdata');
					rdonly('#formdata');
					break;
				case state = 'edit':
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					enableForm('#formdata');
					rdonly('#formdata');
					break;
				case state = 'view':
					disableForm('#formdata');
					$("#pg_jqGridPager2 table").hide();
					break;
				}
				if(oper!='view'){
					// backdated.set_backdate($('#apacthdr_actdate').val());
					// dialog_bankcode.on();
					// dialog_paymode.on();
					// dialog_cheqno.on();
					// dialog_suppcode.on();
					// dialog_payto.on();
				}
				if(oper!='add'){
					// dialog_bankcode.check(errorField);
					// dialog_paymode.check(errorField);
					// dialog_suppcode.check(errorField);
					// dialog_payto.check(errorField);
				}
				// init_jq2(oper, urlParam2);
				// init_paymode(oper,dialog_cheqno);
			},
			beforeClose: function(event, ui){
				if(unsaved){
					event.preventDefault();
					bootbox.confirm("Are you sure want to leave without save?", function(result){
						if (result == true) {
							unsaved = false;
							$("#dialogForm").dialog('close');
						}
					});
				}
				
			},
			close: function( event, ui ) {
				// addmore_jqgrid2.state = false;
				// addmore_jqgrid2.more = false;
				//reset balik
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata',['#apacthdr_source','#apacthdr_trantype']);
				emptyFormdata(errorField,'#formdata2');
				$('.my-alert').detach();
				$("#formdata a").off();
				// dialog_bankcode.off();
				// dialog_paymode.off();
				// dialog_cheqno.off();
				// dialog_suppcode.off();
				// dialog_payto.off();
				$(".notiH").empty();
				$("#refresh_jqGrid").click();
				refreshGrid("#jqGrid2",null,"kosongkan");
				errorField.length=0;
			},
	});

	$('#trtobank_form').dialog({
		width: 9 / 10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function (event, ui) {
			errorField.length=0;
			$('#trtobank_form [name=postdate]').val('').focus();
		},
		close: function(){
			emptyFormdata(errorField,'#trtobank_formdata');
		},
		buttons : [{
			text: "Submit",id: "submit_post",click: function() {
				if($('#trtobank_formdata').isValid({requiredFields:''},conf,true)){
					$('button#submit_post').attr('disabled',true);
					var idno = selrowData('#jqGrid').idno;
					var obj={};
					obj.idno = idno;
					obj._token = $('#_token').val();
					obj.oper = 'posted';
					obj.reason = $('#reason').val();
					obj.postdate = $('#postdate').val();

					$.post( './trtobank/form', obj , function( data ) {
					}).fail(function(data) {
						alert(data.responseText);
						$('button#submit_post').attr('disabled',false);
					}).done(function(data){
						$('button#submit_post').attr('disabled',false);
						$('#trtobank_form').dialog('close');
						refreshGrid('#jqGrid', urlParam);
					});
				}
			}},{
			text: "Cancel",click: function() {
				$(this).dialog('close');
			}
		}]
	});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	///////////////////////////////////backdated////////////////////////////////////////////////

	var backdated = new func_backdated('#apacthdr_actdate');
	backdated.getdata();

	function func_backdated(target){
		this.sequence_data;
		this.target=target;
		this.param={
			action:'get_value_default',
			url:"util/get_value_default",
			field: ['*'],
			table_name:'material.sequence',
			table_id:'idno',
			filterCol:['trantype', 'trantype'],
			filterVal:['PV', 'PD'],
		}

		this.getdata = function(){
			var self=this;
			$.get( this.param.url+"?"+$.param(this.param), function( data ) {
				
			},'json').done(function(data) {
				if(!$.isEmptyObject(data.rows)){
					self.sequence_data = data.rows;
				}
			});
			return this;
		}

		this.set_backdate = function(dept){
			$.each(this.sequence_data, function( index, value ) {
				if(value.dept == dept){
					var backday =  value.backday;
					var backdate = moment().subtract(backday, 'days').format('YYYY-MM-DD');
					$('#apacthdr_actdate').attr('min',backdate);
				}
			});
		}
	}

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var recstatus_filter = [['OPEN','POSTED']];
	if($("#recstatus_use").val() == 'ALL'){
		recstatus_filter = [['OPEN','PREPARED','SUPPORT','VERIFIED','APPROVED','CANCELLED']];
		filterCol_urlParam = ['purreqhd.compcode'];
		filterVal_urlParam = ['session.compcode'];
	}else if($("#recstatus_use").val() == 'SUPPORT'){
		recstatus_filter = [['PREPARED']];
		filterCol_urlParam = ['purreqhd.compcode','queuepr.AuthorisedID'];
		filterVal_urlParam = ['session.compcode','session.username'];
	}else if($("#recstatus_use").val() == 'VERIFIED'){
		recstatus_filter = [['SUPPORT','PREPARED']];
		filterCol_urlParam = ['purreqhd.compcode','queuepr.AuthorisedID'];
		filterVal_urlParam = ['session.compcode','session.username'];
	}else if($("#recstatus_use").val() == 'APPROVED'){
		recstatus_filter = [['VERIFIED']];
		filterCol_urlParam = ['purreqhd.compcode','queuepr.AuthorisedID'];
		filterVal_urlParam = ['session.compcode','session.username'];
	}

	var cbselect = new checkbox_selection("#jqGrid","Checkbox","apacthdr_idno","apacthdr_recstatus");

	var urlParam={
		action:'maintable',
		url:'./trtobank/table',
		source:$('#apacthdr_source').val(),
		scope: $("#recstatus_use").val(),
	
	}

	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam={
		action:'paymentVoucher_save',
		url:'./trtobank/form',
		field:'',
		fixPost:'true',
		oper:oper,
		table_name:'finance.apacthdr',
		table_id:'apacthdr_auditno',
		filterCol: ['source'],
		filterVal: [$('#apacthdr_source').val()],
	};

	function padzero(cellvalue, options, rowObject){
		let padzero = 7, str="";
		if(cellvalue == '' || cellvalue == null ){
			return '';
		}

		while(padzero>0){
			str=str.concat("0");
			padzero--;
		}
		return pad(str, cellvalue, true);
	}

	function unpadzero(cellvalue, options, rowObject){
		return cellvalue.substring(cellvalue.search(/[1-9]/));
	}

	function searchClick2(grid,form,urlParam){
		$(form+' [name=Stext]').on( "keyup", function() {
			delay(function(){
				search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				refreshGrid("#jqGrid3",null,"kosongkan");
			}, 500 );
		});

		$(form+' [name=Scol]').on( "change", function() {
			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
			refreshGrid("#jqGrid3",null,"kosongkan");
		});
	}

	/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////

	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
		
			{ label: 'Audit No', name: 'auditno', width: 20, classes: 'wrap', formatter: padzero, unformat: unpadzero, canSearch: true},
			{ label: 'PD No', name: 'pvno', width: 20, classes: 'wrap', hidden:false, formatter: padzero, unformat: unpadzero, canSearch: true},
			{ label: 'TT', name: 'trantype', width: 10, classes: 'wrap text-uppercase'},
			{ label: 'doctype', name: 'doctype', width: 10, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'Creditor', name: 'suppcode', width: 25, classes: 'wrap text-uppercase', canSearch: true},
			{ label: 'Creditor Name', name: 'name', width: 50, classes: 'wrap text-uppercase', canSearch: true},
			{ label: 'Document Date', name: 'actdate', width: 25, classes: 'wrap text-uppercase', canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Document No', name: 'document', width: 50, classes: 'wrap text-uppercase', hidden: true},
			{ label: 'Cheque No', name: 'cheqno', width: 35, classes: 'wrap text-uppercase'},
			{ label: 'Department', name: 'deptcode', width: 25, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'Amount', name: 'amount', width: 25, classes: 'wrap',align: 'right', formatter:'currency'},
			{ label: 'Out Amount', name: 'outamount', width: 25 , classes: 'wrap'},
			{ label: 'Status', name: 'recstatus', width: 25, classes: 'wrap text-uppercase',},
			{ label: 'Post Date', name: 'recdate', width: 35,hidden:true},
			{ label: 'Post Date', name: 'postdate', width: 25, classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Remarks', name: 'remarks', width: 90, hidden:false, classes: 'wrap text-uppercase'},
			// { label: ' ', name: 'Checkbox',sortable:false, width: 20,align: "center", formatter: formatterCheckbox },
			{ label: 'cheqdate', name: 'cheqdate', width: 40, hidden:true},
			{ label: 'Pay To', name: 'payto', width: 50, classes: 'wrap text-uppercase', hidden:true, canSearch: true},	
			{ label: 'category', name: 'category', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adduser', name: 'adduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adddate', name: 'adddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upduser', name: 'upduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upddate', name: 'upddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'reopenby', name: 'reopenby', width: 40, hidden: true},
			{ label: 'requestby', name: 'requestby', width: 90, hidden: true },
			{ label: 'requestdate', name: 'requestdate', width: 90, hidden: true },
			{ label: 'cancelby', name: 'cancelby', width: 90, hidden: true },
			{ label: 'canceldate', name: 'canceldate', width: 90, hidden: true },
			{ label: 'recommended1by', name: 'recommended1by', width: 90, hidden: true },
			{ label: 'recommended1date', name: 'recommended1date', width: 90, hidden: true },
			{ label: 'recommended2by', name: 'recommended2by', width: 90, hidden: true },
			{ label: 'recommended2date', name: 'recommended2date', width: 90, hidden: true },
			{ label: 'supportby', name: 'supportby', width: 90, hidden: true },
			{ label: 'supportdate', name: 'supportdate', width: 40, hidden: true},
			{ label: 'verifiedby', name: 'verifiedby', width: 90, hidden: true },
			{ label: 'verifieddate', name: 'verifieddate', width: 90, hidden: true },
			{ label: 'approvedby', name: 'approvedby', width: 90, hidden: true },
			{ label: 'approveddate', name: 'approveddate', width: 40, hidden: true},
			{ label: 'reopendate', name: 'reopendate', width: 40, hidden:true},
			{ label: 'support_remark', name: 'support_remark', width: 40, hidden:true},
			{ label: 'verified_remark', name: 'verified_remark', width: 40, hidden:true},
			{ label: 'approved_remark', name: 'approved_remark', width: 40, hidden:true},
			{ label: 'cancelled_remark', name: 'cancelled_remark', width: 40, hidden:true},
			{ label: 'source', name: 'source', width: 40, hidden:true},
			{ label: 'idno', name: 'idno', width: 40, hidden:true, key:true},
			{ label: 'unit', name: 'unit', width: 40, hidden:true},
			{ label: 'compcode', name: 'compcode', width: 40, hidden:'true'},
			{ label: 'paymode', name: 'paymode', width: 50, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'bankcode', name: 'bankcode', width: 50, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'bankname', name: 'bankname', width: 50, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'bankaccno', name: 'bankaccno', width: 40, hidden:'true'},
			{ label: 'suppcode_desc', name: 'suppcode_desc', width: 40, hidden:'true'},
			{ label: 'payto_desc', name: 'payto_desc', width: 40, hidden:'true'},

		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		// sortname:'apacthdr_idno',
		// sortorder:'desc',
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			$('#error_infront').text('');
			$("#pdfgen1").attr('href','./paymentVoucher/showpdf?auditno='+selrowData("#jqGrid").auditno+'&trantype='+selrowData("#jqGrid").trantype);

			let rowdata = selrowData("#jqGrid");
			$('#allocTrantype_show').text('PD');
			$('#allocDocument_show').text(rowdata.pvno);
			$('#allocSuppcode_show').text(rowdata.suppcode);
			$('#allocSuppname_show').text(rowdata.name);

			urlParam2_alloc.idno=selrowData("#jqGrid").idno;
			refreshGrid("#gridAlloc",urlParam2_alloc);
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
				$("#jqGridPager td[title='View Selected Row']").click();
		},
		gridComplete: function () {
			$('#but_cancel_jq,#but_post_jq').hide();
			if (oper == 'add' || oper == null || $("#jqGrid").data('lastselrow') == undefined) { 
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}else{
				$("#jqGrid").setSelection($("#jqGrid").data('lastselrow'));
				delay(function(){
					$('#jqGrid tr#'+$("#jqGrid").data('lastselrow')).focus();
				}, 300 );
			}
			$("#searchForm input[name=Stext]").focus();

				if($('#jqGrid').data('inputfocus') == 'creditor_search'){
				$("#creditor_search").focus();
				$('#jqGrid').data('inputfocus','');
				$('#creditor_search_hb').text('');
				removeValidationClass(['#creditor_search']);
			}else{
				$("#searchForm input[name=Stext]").focus();
			}
			
		},
		loadComplete: function(){
			// let stat = selrowData("#jqGrid").apacthdr_recstatus;
			// if(stat=='APPROVED' || stat=='PREPARED' || stat=='VERIFIED' || stat=='SUPPORT'){
			// 	$("#jqGridPager td[title='Edit Selected Row']").hide();
			// }else{
			// 	$("#jqGridPager td[title='Edit Selected Row']").show();
			// }
			//calc_jq_height_onchange("jqGrid");
		},
		
	});

	////////////////////// set label jqGrid right ///////////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid");

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-info-sign",
		title: "View Selected Row",
		onClickButton: function () {
			oper = 'view';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			$("#jqGrid").data('lastselrow',selRowId);
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'view', '');

			$('#save').hide();
			disableForm('#formdata');
			$("#pg_jqGridPager2 table").hide();
			// dialog_bankcode.off();
			// dialog_paymode.off();
			// dialog_cheqno.off();
			// dialog_suppcode.off();
			// dialog_payto.off();
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		id: 'glyphicon-plus',
		title: "Add New Row",
		onClickButton: function () {
			oper = 'add';
			let selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			populateFormdata("#jqGrid", "#trtobank_form", "#trtobank_formdata", selRowId, 'view', '');
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle /////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', false, urlParam);
	// addParamField('#jqGrid', false, saveParam, ['apacthdr_idno','apacthdr_auditno','apacthdr_adduser','apacthdr_adddate','apacthdr_upduser','apacthdr_upddate','apacthdr_recstatus','supplier_name', 'apacthdr_unit', 'Checkbox']);

	////////////////////////////////hide at dialogForm///////////////////////////////////////////////////

	function hideatdialogForm(hide,saveallrow){
		if(saveallrow == 'saveallrow'){
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#saveDetailLabel").hide();
			$("#jqGridPager2SaveAll,#jqGridPager2CancelAll").show();
		}else if(hide){
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#jqGridPager2SaveAll,#jqGridPager2CancelAll").hide();
			$("#saveDetailLabel").show();
		}else{
			$("#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll").show();
			$("#saveDetailLabel,#jqGridPager2SaveAll,#jqGrid2_iledit,#jqGridPager2CancelAll").hide();
		}
	}

	var urlParam2_alloc={
		action:'get_alloc_detail',
		url:'./apenquiry/table',
		auditno:''
	};

	$("#gridAlloc").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'Src', name: 'source', width: 10, classes: 'wrap'},
			{ label: 'TT', name: 'trantype', width: 10, classes: 'wrap'},
			{ label: 'Audit No', name: 'auditno', width: 20, classes: 'wrap',formatter: padzero, unformat: unpadzero},
			{ label: 'PV No', name: 'pvno', width: 25, classes: 'wrap'},
			{ label: 'Document No', name: 'document', width: 40, classes: 'wrap', },
			{ label: 'Supplier Code', name: 'suppcode', width: 70, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},
			{ label: 'Alloc Date', name: 'allocdate', width: 30, classes: 'wrap',  formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Alloc Amount', name: 'allocamount', width: 30, classes: 'wrap',align: 'right', formatter:'currency'},
			{ label: 'Invoice Amount', name: 'amount', width: 30, classes: 'wrap',align: 'right', formatter:'currency'},
			{ label: 'Bank Code', name: 'bankcode', width: 60, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},
			{ label: 'Status', name: 'recstatus', width: 25, classes: 'wrap',},
			{ label: 'Post Date', name: 'postdate', width: 30, classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter},
		
		],
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		pager: "#jqGridPagerAlloc",
		loadComplete: function(data){
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
	});
	jqgrid_label_align_right("#gridAlloc");

	// panel grid Alloc
	$("#gridAlloc_panel").on("show.bs.collapse", function(){
		$("#gridAlloc").jqGrid ('setGridWidth', Math.floor($("#gridAlloc_c")[0].offsetWidth-$("#gridAlloc_c")[0].offsetLeft-28));
	});

	////////////////////selected///////////////

	// $('#apacthdr_trantype').on('change', function() {
	// 	let trantype = $("#apacthdr_trantype option:selected").val();
	// 	init_jq2(oper,urlParam2);
	// });
	
	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
	$("#but_reopen_jq").click(function(){

		var idno = selrowData('#jqGrid').apacthdr_idno;
		var obj={};
		obj.idno = idno;
		obj._token = $('#_token').val();
		obj.oper = $(this).data('oper')+'_single';

		$.post( './paymentVoucher/form', obj , function( data ) {
			refreshGrid('#jqGrid', urlParam);
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
		}).success(function(data){
			
		});
	});

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
					obj.oper = $("#but_cancel_jq").data('oper');
					obj.recstatus_use = $("#recstatus_use").val();
					obj.remarks = $("#remarks_oper").val();
					obj._token = $('#_token').val();
					oper=null;
					
					$.post( './paymentVoucher/form', obj , function( data ) {
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
		if($(this).data('oper') == 'reject'){
			if (confirm("Are you sure to reject this purchase request?") == true) {
				$("#dialog_remarks_oper").dialog( "open" );
			}else{
				$("#but_cancel_jq").attr('disabled',false);
			}
		}
	});

	$("#but_post_jq").click(function(){
		var idno_array = [];

		let ids = $('#jqGrid_selection').jqGrid ('getDataIDs');

		for (var i = 0; i < ids.length; i++) {
			var data = $('#jqGrid_selection').jqGrid('getRowData',ids[i]);
			if(data.apacthdr_postdate == ''){
				alert("Please insert Post Date");
				return false;
			}
	    	idno_array.push({
	    		idno:data.apacthdr_idno,
	    		date:data.apacthdr_postdate
	    	});
	    }
	    
		var obj={};
		obj.idno_array = idno_array;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		oper=null;
		
		$.post( './paymentVoucher/form', obj , function( data ) {
			cbselect.empty_sel_tbl();
			refreshGrid('#jqGrid', urlParam);
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
		}).success(function(data){
			
		});

	});

	$("#but_post2_jq").click(function(){
		if (confirm("Are you sure to cancel this document?") == true) {

			var idno_array = [];
			idno_array.push({
	    		idno:selrowData('#jqGrid').apacthdr_idno
	    	});
		
			var obj={};
			obj.idno_array = idno_array;
			obj.oper = $(this).data('oper');
			obj._token = $('#_token').val();
			oper=null;
				
			$.post(  './paymentVoucher/form', obj , function( data ) {
				cbselect.empty_sel_tbl();
				refreshGrid('#jqGrid', urlParam);
			}).fail(function(data) {
				$('#error_infront').text(data.responseText);
			}).success(function(data){
				
			});

		}
	});

	///////////check postdate & docdate///////////////////
	$('#apacthdr_actdate').on('changeDate', function (ev) {
        $('#apacthdr_cheqdate').change(apacthdr_actdate);
    }); 

	$("#apacthdr_postdate,#apacthdr_actdate").blur(checkdate);

	function checkdate(nkreturn=false){
		var apacthdr_postdate = $('#apacthdr_postdate').val();
		var apacthdr_actdate = $('#apacthdr_actdate').val();

		text_success1('#apacthdr_postdate')
		text_success1('#apacthdr_actdate')
		$("#dialogForm .notiH ol").empty();
		var failmsg=[];

		if(moment(apacthdr_postdate).isBefore(apacthdr_actdate)){
			failmsg.push("Post Date cannot be lower than Doc date");
			text_error1('#apacthdr_postdate')
			text_error1('#apacthdr_actdate')
		}

		// if(moment(apacthdr_postdate).isAfter(moment())){
		// 	failmsg.push("Post Date cannot be higher than today");
		// 	text_error1('#apacthdr_postdate')
		// }

		// if(moment(apacthdr_actdate).isAfter(moment())){
		// 	failmsg.push("Doc Date cannot be higher than today");
		// 	text_error1('#apacthdr_actdate')
		// }

		if(failmsg.length){
			failmsg.forEach(function(element){
				$('#dialogForm .notiH ol').prepend('<li>'+element+'</li>');
			});
			if(nkreturn)return false;
		}else{
			if(nkreturn)return true;
		}

	}
	//////////////////////////////////////////////////////

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form,selfoper,saveParam,obj,needrefresh){
		if(obj==null){
			obj={};
		}
		saveParam.oper=selfoper;

		let data_detail = $('#jqGrid2').jqGrid ('getRowData');
		obj.data_detail = data_detail;

		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
			
		},'json').fail(function (data) {
        	myfail_msg.add_fail({
				id:'response',
				textfld:"",
				msg:data.responseText,
			});
		}).success(function (data) {
			hideatdialogForm(false);
			
			// if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
			// 	addmore_jqgrid2.state = true;
			// 	$('#jqGrid2_iladd').click();
			// }
			if(selfoper=='add'){

				oper='edit';//sekali dia add terus jadi edit lepas tu
				
				$('#apacthdr_auditno,#auditno').val(data.auditno);
				$('#apacthdr_amount').val(data.totalAmount);
				$('#idno').val(data.idno);
				
				urlParam2.apacthdr_auditno=data.auditno;
				
			}else if(selfoper=='edit'){
				urlParam2.apacthdr_auditno=$('#apacthdr_auditno').val();
				//doesnt need to do anything
			}
			disableForm('#formdata');

			if(needrefresh == 'refreshGrid'){
				refreshGrid("#jqGrid", urlParam);
				$("#dialogForm").dialog('close');
			}
			
		});
	}
	
	$("#dialogForm").on('change keypress','#formdata :input','#formdata :textarea',function(){
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	$("#dialogForm").on('click','#formdata a.input-group-addon',function(){
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	////////////////////////////populate data for dropdown search By////////////////////////////
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

	// $('#Scol').on('change', whenchangetodate);
	// $('#Status').on('change', searchChange);
	// $('#ttype').on('change', searchChange);
	// $('#actdate_search').on('click', searchDate);

	function whenchangetodate() {
		creditor_search.off();
		$('#creditor_search, #actdate_from, #actdate_to').val('');
		$('#creditor_search_hb').text('');
		removeValidationClass(['#creditor_search']);
		if($('#Scol').val()=='apacthdr_actdate'){
			$("input[name='Stext'], #creditor_text").hide("fast");
			$("#actdate_text").show("fast");
		} else if($('#Scol').val() == 'apacthdr_suppcode' || $('#Scol').val() == 'apacthdr_payto'){
			$("input[name='Stext'],#actdate_text").hide("fast");
			$("#creditor_text").show("fast");
			creditor_search.on();
		} else {
			$("#creditor_text,#actdate_text").hide("fast");
			$("input[name='Stext']").show("fast");
			$("input[name='Stext']").velocity({ width: "100%" });
		}
	}

	function searchDate(){
		urlParam.filterdate = [$('#actdate_from').val(),$('#actdate_to').val()];
		refreshGrid('#jqGrid',urlParam);
	}

	searchChange(true);
	function searchChange(once=false){
		cbselect.empty_sel_tbl();
		// var arrtemp = [$('#Status option:selected').val(),$('#ttype option:selected').val()];
		// var filter = arrtemp.reduce(function(a,b,c){
		// 	if(b.toUpperCase() == 'ALL'){
		// 		return a;
		// 	}else{
		// 		a.fc = a.fc.concat(a.fct[c]);
		// 		a.fv = a.fv.concat(b);
		// 		return a;
		// 	}
		// },{fct:['ap.recstatus','ap.trantype'],fv:[],fc:[]});

		// urlParam.filterCol = filter.fc;
		// urlParam.filterVal = filter.fv;

		if(once){
			urlParam.searchCol=null;
			urlParam.searchVal=null;
			if($('#searchForm [name=Stext]').val().trim() != ''){
				let searchCol = ['ap.auditno'];
				let searchVal = ['%'+$('#searchForm [name=Stext]').val().trim()+'%'];
				urlParam.searchCol=searchCol;
				urlParam.searchVal=searchVal;
			}
			once=false;
		}

		refreshGrid('#jqGrid',urlParam);
	}

	var creditor_search = new ordialog(
		'creditor_search', 'material.supplier', '#creditor_search', 'errorField',
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
				let data = selrowData('#' + creditor_search.gridname).suppcode;

				if($('#Scol').val() == 'apacthdr_suppcode'){
					urlParam.searchCol=["ap.suppcode"];
					urlParam.searchVal=[data];
				}else if($('#Scol').val() == 'apacthdr_payto'){
					urlParam.searchCol=["ap.payto"];
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
				creditor_search.urlParam.filterCol = ['recstatus'];
				creditor_search.urlParam.filterVal = ['ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	creditor_search.makedialog(true);
	$('#creditor_search').on('keyup',ifnullsearch);

	function ifnullsearch(){
		if($('#creditor_search').val() == ''){
			urlParam.searchCol=[];
			urlParam.searchVal=[];
			$('#jqGrid').data('inputfocus','creditor_search');
			refreshGrid('#jqGrid', urlParam);
		}
	}

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field, table, case_;
		switch(options.colModel.name){
			case 'suppcode':field=['suppcode','name'];table="material.supplier";case_='suppcode';break;
			case 'bankcode':field=['bankcode','bankname'];table="finance.bank";case_='bankcode';break;
			case 'apacthdr_suppcode':field=['suppcode','name'];table="material.supplier";case_='suppcode';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('paymentVoucher',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value,name){
		var temp;
		switch(name){
			case 'Delivery Order Number':temp=$('#document');break;
		}
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}

	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	////////////////////////////////////////// object for dialog handler//////////////////////////////////////////
});

function init_jq2(oper,urlParam2){
	if(oper == 'add'){
		if($('#apacthdr_trantype').val() == 'PV'){
			$('#save').hide();
			$('#apacthdr_amount').prop('readonly',true);
			$('#pvpd_detail').show();
			$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft-28));
			refreshGrid("#jqGrid2",urlParam2,'kosongkan');
		}else if($('#apacthdr_trantype').val() == 'PD') {
			$('#save').show();
			$('#pvpd_detail').hide();
			$('#apacthdr_amount').prop('readonly',false);
		}
	} else if(oper == 'edit'){
		if($('#apacthdr_trantype').val() == 'PV'){
			$('#save').hide();
			$('#apacthdr_amount').prop('readonly',true);
			$('#pvpd_detail').show();
			$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft-28));
			refreshGrid("#jqGrid2",urlParam2,'kosongkan');
		}else if($('#apacthdr_trantype').val() == 'PD') {
			$('#save').show();
			$('#pvpd_detail').hide();
			$('#apacthdr_amount').prop('readonly',false);
		}
		$('#apacthdr_trantype').prop('disabled',true);
	} else if(oper == 'view'){
		if($('#apacthdr_trantype').val() == 'PV'){
			$('#save').hide();
			$('#apacthdr_amount').prop('readonly',true);
			$('#pvpd_detail').show();
			$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft-28));
			refreshGrid("#jqGrid2",urlParam2,'kosongkan');
		}else if($('#apacthdr_trantype').val() == 'PD') {
			$('#save').hide();
			$('#pvpd_detail').hide();
			$('#apacthdr_amount').prop('readonly',false);
		}
	} 
}

function init_paymode(oper,dialog_cheqno){
	let paymode = $('#apacthdr_paymode').val();
	$('#cheqno_parent').text('Cheque No');

	if(oper=='add'){
		$('#apacthdr_cheqno').prop('readonly',false);
		switch(paymode){
			case 'BD':
			case 'DRAFT':
						$('#cheqno_parent').text('BD No');
						dialog_cheqno.off();
						break;
			case 'CHEQUE':
						$('#cheqno_parent').text('Cheque No');
						dialog_cheqno.on();
						// dialog_cheqno.check(errorField);
						$("label[for='cheqdate_parent'], input#cheqdate_parent").show();
						$("label[for='cheqno_parent'], input#cheqno_parent").show();
						break;
			case 'CASH':
						$('#apacthdr_cheqno').prop('readonly',true);
						dialog_cheqno.off();
						$("label[for='cheqdate_parent'], input#cheqdate_parent").hide();
						$("label[for='cheqno_parent'], input#cheqno_parent").hide();
						break;
			case 'TT':
						$('#cheqno_parent').text('TT No');
						dialog_cheqno.off();
						break;
		}
	}else if(oper=='edit'){
		$('#apacthdr_cheqno').prop('readonly',false);
		switch(paymode){
			case 'BD':
			case 'DRAFT':
						$('#cheqno_parent').text('BD No');
						dialog_cheqno.off();
						$('#cheqno_parent').show();
						$('#apacthdr_cheqno').show();
						$('#cheqno_dh').show();
						$('#cheqdate_parent').show();
						$('#apacthdr_cheqdate').show();
						break;
			case 'CHEQUE':
						$('#cheqno_parent').text('Cheque No');
						dialog_cheqno.on();
						// dialog_cheqno.check(errorField);
						$('#cheqno_parent').show();
						$('#apacthdr_cheqno').show();
						$('#cheqno_dh').show();
						$('#cheqdate_parent').show();
						$('#apacthdr_cheqdate').show();
						break;
			case 'CASH':
						dialog_cheqno.off();
						$('#cheqno_parent').hide();
						$('#apacthdr_cheqno').hide();
						$('#cheqno_dh').hide();
						$('#cheqdate_parent').hide();
						$('#apacthdr_cheqdate').hide();
						break;
			case 'TT':
						$('#cheqno_parent').text('TT No');
						dialog_cheqno.off();
						$('#cheqno_parent').show();
						$('#apacthdr_cheqno').show();
						$('#cheqno_dh').show();
						$('#cheqdate_parent').show();
						$('#apacthdr_cheqdate').show();
						break;
		}
	}else if(oper=='view'){
		switch(paymode){
			case 'BD':
			case 'DRAFT':
						$('#cheqno_parent').text('BD No');
						$('#cheqno_parent').show();
						$('#apacthdr_cheqno').show();
						$('#cheqno_dh').show();
						$('#cheqdate_parent').show();
						$('#apacthdr_cheqdate').show();
						break;
			case 'CHEQUE':
						$('#cheqno_parent').text('Cheque No');
						// dialog_cheqno.check(errorField);
						$('#cheqno_parent').show();
						$('#apacthdr_cheqno').show();
						$('#cheqno_dh').show();
						$('#cheqdate_parent').show();
						$('#apacthdr_cheqdate').show();
						break;
			case 'CASH':
						dialog_cheqno.off();
						$('#cheqno_parent').hide();
						$('#apacthdr_cheqno').hide();
						$('#cheqno_dh').hide();
						$('#cheqdate_parent').hide();
						$('#apacthdr_cheqdate').hide();
						break;
			case 'TT':
						$('#cheqno_parent').text('TT No');
						$('#cheqno_parent').show();
						$('#apacthdr_cheqno').show();
						$('#cheqno_dh').show();
						$('#cheqdate_parent').show();
						$('#apacthdr_cheqdate').show();
						break;
		}
	}
	
}

function populate_form(obj){

	//panel header
	$('#trantype_show').text(obj.apacthdr_trantype);
	$('#pvno_show').text(padzero(obj.apacthdr_pvno));
	$('#auditno_show').text(padzero(obj.apacthdr_auditno));
	$('#suppcode_show').text(obj.supplier_name);

	if($('#scope').val().trim().toUpperCase() == 'CANCEL'){
		$('td#glyphicon-plus,td#glyphicon-edit').hide();
	}else{
		$('td#glyphicon-plus,td#glyphicon-edit').show();
	}
}

function empty_form(){

	$('#trantype_show').text('');
	$('#pvno_show').text('');
	$('#auditno_show').text('');
	$('#suppcode_show').text('');

}
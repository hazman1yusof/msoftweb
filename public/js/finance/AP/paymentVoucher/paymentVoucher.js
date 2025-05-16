
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

var mycurrency =new currencymode(['#apacthdr_outamount', '#apacthdr_amount']);
$(document).ready(function () {
    $('#apacthdr_actdate').attr('disabled', 'disabled');

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
	var mycurrency =new currencymode(['#apacthdr_outamount', '#apacthdr_amount']);
	var mycurrency2 =new currencymode(['#apacthdr_outamount', '#apacthdr_amount']);
	var fdl = new faster_detail_load();
	var my_remark_button = new remark_button_class('#jqgrid');
	var myfail_msg = new fail_msg_func();
	
	///////////////////////////////// trandate check date validate from period////////// ////////////////
	var actdateObj = new setactdate(["#apacthdr_postdate"]);
	actdateObj.getdata().set();

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
				my_remark_button.remark_btn_init(selrowData("#jqGrid"));
				$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
				mycurrency.formatOnBlur();
				switch (oper) {
					case state = 'add':
					$("#jqGrid2").jqGrid("clearGridData", false);
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					enableForm('#formdata');
					rdonly('#formdata');
					alloc_button_status('wait');
					break;
				case state = 'edit':
					$("#pg_jqGridPager2 table").show();
					hideatdialogForm(true);
					enableForm('#formdata');
					rdonly('#formdata');
					alloc_button_status('add');
					break;
				case state = 'view':
					disableForm('#formdata');
					$("#pg_jqGridPager2 table").hide();
					alloc_button_status('initial');
					break;
				}
				if(oper!='view'){
					backdated.set_backdate($('#apacthdr_actdate').val());
					dialog_bankcode.on();
					dialog_paymode.on();
					dialog_cheqno.on();
					dialog_suppcode.on();
					dialog_payto.on();
				}
				if(oper!='add'){
					dialog_bankcode.check(errorField);
					dialog_paymode.check(errorField);
					dialog_suppcode.check(errorField);
					dialog_payto.check(errorField);
				}
				init_jq2(oper, urlParam2);
				init_paymode(oper,dialog_cheqno);
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
				addmore_jqgrid2.state = false;
				addmore_jqgrid2.more = false;
				//reset balik
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata',['#apacthdr_source','#apacthdr_trantype']);
				emptyFormdata(errorField,'#formdata2');
				$('.my-alert').detach();
				$("#formdata a").off();
				dialog_bankcode.off();
				dialog_paymode.off();
				dialog_cheqno.off();
				dialog_suppcode.off();
				dialog_payto.off();
				$(".notiH").empty();
				$("#refresh_jqGrid").click();
				refreshGrid("#jqGrid2",null,"kosongkan");
				errorField.length=0;
			},
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
		url:'./paymentVoucher/table',
		source:$('#apacthdr_source').val(),
		scope: $("#recstatus_use").val(),
	
	}

	/////////////////////parameter for saving url///////////////////////////////////////////////////////
	var saveParam={
		action:'paymentVoucher_save',
		url:'./paymentVoucher/form',
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
		
			{ label: 'Audit No', name: 'apacthdr_auditno', width: 20, classes: 'wrap', formatter: padzero, unformat: unpadzero},
			{ label: 'PV No', name: 'apacthdr_pvno', width: 20, classes: 'wrap', hidden:false, formatter: padzero, unformat: unpadzero},
			{ label: 'TT', name: 'apacthdr_trantype', width: 10, classes: 'wrap text-uppercase'},
			{ label: 'doctype', name: 'apacthdr_doctype', width: 10, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'Creditor', name: 'apacthdr_suppcode', width: 60, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Creditor Name', name: 'supplier_name', width: 50, classes: 'wrap text-uppercase', checked: true, hidden: true},
			{ label: 'Document Date', name: 'apacthdr_actdate', width: 25, classes: 'wrap text-uppercase', canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Document No', name: 'apacthdr_document', width: 50, classes: 'wrap text-uppercase', canSearch: true},
			{ label: 'Cheque No', name: 'apacthdr_cheqno', width: 35, classes: 'wrap text-uppercase'},
			{ label: 'Department', name: 'apacthdr_deptcode', width: 25, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'Amount', name: 'apacthdr_amount', width: 25, classes: 'wrap',align: 'right', formatter:'currency'},
			{ label: 'Outamount', name: 'apacthdr_outamount', width: 25 ,hidden:true, classes: 'wrap'},
			{ label: 'Status', name: 'apacthdr_recstatus', width: 25, classes: 'wrap text-uppercase',},
			{ label: 'Post Date', name: 'apacthdr_recdate', width: 35,hidden:true},
			{ label: 'Post Date', name: 'apacthdr_postdate', width: 25, classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Remarks', name: 'apacthdr_remarks', width: 90, hidden:false, classes: 'wrap text-uppercase'},
			{ label: ' ', name: 'Checkbox',sortable:false, width: 20,align: "center", formatter: formatterCheckbox },
			{ label: 'apacthdr_cheqdate', name: 'apacthdr_cheqdate', width: 40, hidden:true},
			{ label: 'Pay To', name: 'apacthdr_payto', width: 50, classes: 'wrap text-uppercase', hidden:true, canSearch: true},	
			{ label: 'category', name: 'apacthdr_category', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adduser', name: 'apacthdr_adduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adddate', name: 'apacthdr_adddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upduser', name: 'apacthdr_upduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upddate', name: 'apacthdr_upddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'reopenby', name: 'apacthdr_reopenby', width: 40, hidden: true},
			{ label: 'requestby', name: 'apacthdr_requestby', width: 90, hidden: true },
			{ label: 'requestdate', name: 'apacthdr_requestdate', width: 90, hidden: true },
			{ label: 'cancelby', name: 'apacthdr_cancelby', width: 90, hidden: true },
			{ label: 'canceldate', name: 'apacthdr_canceldate', width: 90, hidden: true },
			{ label: 'recommended1by', name: 'apacthdr_recommended1by', width: 90, hidden: true },
			{ label: 'recommended1date', name: 'apacthdr_recommended1date', width: 90, hidden: true },
			{ label: 'recommended2by', name: 'apacthdr_recommended2by', width: 90, hidden: true },
			{ label: 'recommended2date', name: 'apacthdr_recommended2date', width: 90, hidden: true },
			{ label: 'supportby', name: 'apacthdr_supportby', width: 90, hidden: true },
			{ label: 'supportdate', name: 'apacthdr_supportdate', width: 40, hidden: true},
			{ label: 'verifiedby', name: 'apacthdr_verifiedby', width: 90, hidden: true },
			{ label: 'verifieddate', name: 'apacthdr_verifieddate', width: 90, hidden: true },
			{ label: 'approvedby', name: 'apacthdr_approvedby', width: 90, hidden: true },
			{ label: 'approveddate', name: 'apacthdr_approveddate', width: 40, hidden: true},
			{ label: 'reopendate', name: 'apacthdr_reopendate', width: 40, hidden:true},
			{ label: 'support_remark', name: 'apacthdr_support_remark', width: 40, hidden:true},
			{ label: 'verified_remark', name: 'apacthdr_verified_remark', width: 40, hidden:true},
			{ label: 'approved_remark', name: 'apacthdr_approved_remark', width: 40, hidden:true},
			{ label: 'cancelled_remark', name: 'apacthdr_cancelled_remark', width: 40, hidden:true},
			{ label: 'source', name: 'apacthdr_source', width: 40, hidden:true},
			{ label: 'idno', name: 'apacthdr_idno', width: 40, hidden:true, key:true},
			{ label: 'unit', name: 'apacthdr_unit', width: 40, hidden:true},
			{ label: 'compcode', name: 'compcode', width: 40, hidden:'true'},
			{ label: 'paymode', name: 'apacthdr_paymode', width: 50, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'bankcode', name: 'apacthdr_bankcode', width: 50, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'bankaccno', name: 'apacthdr_bankaccno', width: 40, hidden:'true'},

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
			
			let stat = selrowData("#jqGrid").apacthdr_recstatus;
			let scope = $("#recstatus_use").val();

			if(rowid != null) {
				var rowData = $('#jqGrid').jqGrid('getRowData', rowid);
				refreshGrid('#jqGrid2', urlParam2,'kosongkan');
				$("#pg_jqGridPager3 table").hide();
				$("#pg_jqGridPager2 table").show();
			}

			if(selrowData("#jqGrid").apacthdr_trantype=='PV'){
				$('#jqGrid3_c').show();
				urlParam2.apacthdr_auditno=selrowData("#jqGrid").apacthdr_auditno;
				$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft));
				refreshGrid("#jqGrid3",urlParam2);

			} else {
				$('#jqGrid3_c').hide();
			}

			$('#auditnodepan').text(selrowData("#jqGrid").apacthdr_auditno);//tukar kat depan tu
			$('#trantypedepan').text(selrowData("#jqGrid").apacthdr_trantype);
			$('#docnodepan').text(selrowData("#jqGrid").apacthdr_document);
			$('#idno').val(selrowData("#jqGrid").apacthdr_idno);


			refreshGrid("#jqGrid3",urlParam2);
			$("#pdfgen1").attr('href','./paymentVoucher/showpdf?auditno='+selrowData("#jqGrid").apacthdr_auditno+'&trantype='+selrowData("#jqGrid").apacthdr_trantype);
			$("#link_do").attr('href','./paymentVoucher/table?action=link_pv&type=do&idno='+selrowData("#jqGrid").apacthdr_idno);
			$("#link_po").attr('href','./paymentVoucher/table?action=link_pv&type=do&idno='+selrowData("#jqGrid").apacthdr_idno);
			$("#link_invoice").attr('href','./paymentVoucher/table?action=link_pv&type=invoice&idno='+selrowData("#jqGrid").apacthdr_idno);
			if_cancel_hide();

			populate_form(selrowData("#jqGrid"));
			
			if(stat=='APPROVED' || stat=='PREPARED' || stat=='VERIFIED' || stat=='SUPPORT'){
				$("#jqGridPager td[title='Edit Selected Row']").hide();
			}else{
				$("#jqGridPager td[title='Edit Selected Row']").show();
			}
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			let stat = selrowData("#jqGrid").apacthdr_recstatus;
			if(stat=='APPROVED' || stat=='PREPARED' || stat=='VERIFIED' || stat=='SUPPORT'){
				$("#jqGridPager td[title='View Selected Row']").click();
				if(selrowData("#jqGrid").apacthdr_trantype=='PV'){
					$('#save').hide()
				} else {
					$('#save').hide();
				}
				$("#jqGridPager td[title='Edit Selected Row']").hide();

			}else{
				$("#jqGridPager td[title='Edit Selected Row']").click();
				if(selrowData("#jqGrid").apacthdr_trantype=='PV'){
					$('#save').hide()
				} else {
					$('#save').show();
				}
				if (rowid != null) {
					rowData = $('#jqGrid').jqGrid('getRowData', rowid);
				}
			}
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
			empty_form();

			populate_form(selrowData("#jqGrid"));
			$("#searchForm input[name=Stext]").focus();
			fdl.set_array().reset();

			cbselect.checkbox_function_on(function(rowdata){
				// delete rowdata.apacthdr_recdate
			});
			cbselect.refresh_seltbl();
			cbselect.show_hide_table();
			
		},
		loadComplete: function(){
			let stat = selrowData("#jqGrid").apacthdr_recstatus;
			if(stat=='APPROVED' || stat=='PREPARED' || stat=='VERIFIED' || stat=='SUPPORT'){
				$("#jqGridPager td[title='Edit Selected Row']").hide();
			}else{
				$("#jqGridPager td[title='Edit Selected Row']").show();
			}
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
			refreshGrid("#jqGrid2",urlParam2);

			if(selrowData("#jqGrid").apacthdr_recstatus != 'OPEN'){
				disableForm('#formdata');
				$("#pg_jqGridPager2 table").hide();
				$('#apacthdr_amount').prop('readonly',true);
				dialog_bankcode.off();
				dialog_paymode.off();
				dialog_cheqno.off();
				dialog_suppcode.off();
				dialog_payto.off();
			} else if(selrowData("#jqGrid").apacthdr_recstatus == 'CANCELLED'){
				$('#save').hide();
				disableForm('#formdata');
				$("#pg_jqGridPager2 table").hide();
				dialog_bankcode.off();
				dialog_paymode.off();
				dialog_cheqno.off();
				dialog_suppcode.off();
				dialog_payto.off();
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", id:"glyphicon-edit", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			$("#jqGrid").data('lastselrow',selRowId);
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit', '');
			refreshGrid("#jqGrid2",urlParam2);

			if(selrowData("#jqGrid").apacthdr_recstatus != 'OPEN'){
				disableForm('#formdata');
				$("#pg_jqGridPager2 table").hide();
				$('#apacthdr_amount').prop('readonly',true);
				dialog_bankcode.off();
				dialog_paymode.off();
				dialog_cheqno.off();
				dialog_suppcode.off();
				dialog_payto.off();
			} else if(selrowData("#jqGrid").apacthdr_recstatus == 'CANCELLED'){
				$('#save').hide();
				disableForm('#formdata');
				$("#pg_jqGridPager2 table").hide();
				dialog_bankcode.off();
				dialog_paymode.off();
				dialog_cheqno.off();
				dialog_suppcode.off();
				dialog_payto.off();
			}
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-plus",
		id: 'glyphicon-plus',
		title: "Add New Row",
		onClickButton: function () {
			oper = 'add';
			$("#dialogForm").dialog("open");
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle /////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', false, urlParam);
	addParamField('#jqGrid', false, saveParam, ['apacthdr_idno','apacthdr_auditno','apacthdr_adduser','apacthdr_adddate','apacthdr_upduser','apacthdr_upddate','apacthdr_recstatus','supplier_name', 'apacthdr_unit', 'Checkbox']);

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

	////////////////////selected///////////////

	$('#apacthdr_trantype').on('change', function() {
		let trantype = $("#apacthdr_trantype option:selected").val();
		init_jq2(oper,urlParam2);
	});
	
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
		$("#save,#saveDetailLabel").attr('disabled',true);
		if(obj==null){
			obj={};
		}
		saveParam.oper=selfoper;

		let data_detail = $('#jqGrid2').jqGrid ('getRowData');
		obj.data_detail = data_detail;

		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
			
		},'json').fail(function (data) {
			$("#save,#saveDetailLabel").attr('disabled',false);
        	myfail_msg.add_fail({
				id:'response',
				textfld:"",
				msg:data.responseText,
			});
		}).success(function (data) {
			$("#save,#saveDetailLabel").attr('disabled',false);
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

	$('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	$('#ttype').on('change', searchChange);
	$('#actdate_search').on('click', searchDate);

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
		var arrtemp = [$('#Status option:selected').val(),$('#ttype option:selected').val()];
		var filter = arrtemp.reduce(function(a,b,c){
			if(b.toUpperCase() == 'ALL'){
				return a;
			}else{
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		},{fct:['ap.recstatus','ap.trantype'],fv:[],fc:[]});

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;

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

	/////////////////////////////parameter for jqgrid2 url///////////////////////////////////////////////
	var urlParam2={
		action:'get_alloc_table',
		url:'paymentVoucher/table',
		apacthdr_auditno:'',
	};

	var addmore_jqgrid2={more:false,state:false,edit:true} // if addmore is true, add after refresh jqgrid2, state true kalu kosong
	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./paymentVoucherDetail/form",
		colModel: [
			{ label: ' ', name: 'checkbox', width: 15, formatter: checkbox_jqg2},
			{ label: 'Creditor', name: 'suppcode', width: 100, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{ label: 'Invoice Date', name: 'allocdate', width: 100, classes: 'wrap',
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}
			},
			{ label: 'Invoice No', name: 'reference', width: 100, classes: 'wrap text-uppercase',},
			{ label: 'Remarks', name: 'remarks', width: 100, classes: 'wrap text-uppercase', hidden:true},
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
			{ label: 'Amount Paid', name: 'allocamount', width: 100, classes: 'wrap', 
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
			{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true},
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true},
			{ label: 'docsource', name: 'docsource', width: 20, classes: 'wrap', hidden:true},
			{ label: 'doctrantype', name: 'doctrantype', width: 20, classes: 'wrap', hidden:true},
			{ label: 'docauditno', name: 'docauditno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'reftrantype', name: 'reftrantype', width: 20, classes: 'wrap', hidden:true},
			{ label: 'refsource', name: 'refsource', width: 20, classes: 'wrap', hidden:true},
			{ label: 'refauditno', name: 'refauditno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 80, classes: 'wrap', hidden:true}, 
			{ label: 'can_alloc', name: 'can_alloc', width: 20, classes: 'wrap', hidden:true},
			{ label: 'idno', name: 'idno', width: 80, classes: 'wrap', hidden:true}, 
		
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 250,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager2",
		loadComplete: function(data){
			if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else{
				$('#jqGrid2').jqGrid ('setSelection', "1");
			}

			//setjqgridHeight(data,'jqGrid2');

			addmore_jqgrid2.edit = true;
			addmore_jqgrid2.more = false; //reset
		
			//calc_jq_height_onchange("jqGrid2");
			
		},
		onSelectRow: function (rowid, selected) {
			myfail_msg.clear_fail();
			calc_jq_height_onchange("jqGrid2");
		},
		gridComplete: function(){
			
		
			fdl.set_array().reset();
			if($('#apacthdr_recstatus').val() == 'POSTED' && oper == 'add'){
				//calc bal
				var ids = $("#jqGrid2").jqGrid('getDataIDs');
				for (var i = 0; i < ids.length; i++) {
					let data = $("#jqGrid2").jqGrid ('getRowData', ids[i]);
					let balance = parseFloat(data.outamount) - parseFloat(data.allocamount);
					$("#jqGrid2").jqGrid('setCell', ids[i], 'balance', balance);
				}

				calc_amtpaid_bal();
						
				var ids = $("#jqGrid2").jqGrid('getDataIDs');
				for (var i = 0; i < ids.length; i++) {
					$("#jqGrid2").jqGrid('editRow',ids[i]);

					$('#jqGrid2 input#'+ids[i]+'_allocamount').on('keyup',{rowid:ids[i]},calc_amtpaid);
				}

			}

			unsaved = false;
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			var result = ids.filter(function(text){
								if(text.search("jqg") != -1)return false;return true;
							});
			if(result.length == 0 && oper=='edit')unsaved = true;

			
		},
		beforeSubmit: function(postdata, rowid){ 
	 	}
	});

	////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
	addParamField('#jqGrid2',false,urlParam2,['checkbox','entrydate','can_alloc'])
	jqgrid_label_align_right("#jqGrid2");

	function checkbox_jqg2(cellvalue, options, rowObject){
		if(options.gid == "jqGridAPAlloc"){
			return '';
		}else{
			if(parseFloat(rowObject.allocamount) > 0){
				return '';
			}else{
				return `<input class='checkbox_jqg2' type="checkbox" name="checkbox" data-rowid="`+options.rowId+`">`;	
			}
		}
	}
	
	function formatterCheckbox(cellvalue, options, rowObject){
		let lineno_ = cbselect.lineno_;
		let recstatus = cbselect.recstatus;

		if($('#recstatus_use').val() == 'CANCEL' && ['OPEN','PREPARED','SUPPORT','VERIFIED','REJECTED','APPROVED'].includes(rowObject.apacthdr_recstatus)){
			return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject['apacthdr_idno']+"' data-idno='"+rowObject['apacthdr_idno']+"' data-rowid='"+options.rowId+"' onclick='click_selection(checkbox_selection_"+rowObject['apacthdr_idno']+");'>";
		}

		//pv skip support
		if($('#recstatus_use').val() == 'SUPPORT'){
			return ' ';
		}else if($('#recstatus_use').val() == 'VERIFIED'){
			return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject['apacthdr_idno']+"' data-idno='"+rowObject['apacthdr_idno']+"' data-rowid='"+options.rowId+"' onclick='click_selection(checkbox_selection_"+rowObject['apacthdr_idno']+");'>";
		}else if($('#recstatus_use').val() == 'REOPEN' && rowObject['apacthdr_recstatus'] == 'CANCELLED'){
			return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject['apacthdr_idno']+"' data-idno='"+rowObject['apacthdr_idno']+"' data-rowid='"+options.rowId+"' onclick='click_selection(checkbox_selection_"+rowObject['apacthdr_idno']+");'>";
		}
		
		if(options.gid == "jqGrid" && rowObject[recstatus] == recstatus_filter[0][0]){
			return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject['apacthdr_idno']+"' data-idno='"+rowObject['apacthdr_idno']+"' data-rowid='"+options.rowId+"' onclick='click_selection(checkbox_selection_"+rowObject['apacthdr_idno']+");'>";
		}else if(options.gid != "jqGrid" && rowObject[recstatus] == recstatus_filter[0][0]){
			return "<button class='btn btn-xs btn-danger btn-md' id='delete_"+rowObject['apacthdr_idno']+"' ><i class='fa fa-trash' aria-hidden='true'></i></button>";
		}else{
			return ' ';
		}
	}

	//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
	
	var myEditOptions = {
        keys: true,
        extraparam:{
		    "_token": $("#_token").val()
        },
        oneditfunc: function (rowid) {

        	$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();

        	mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='amount']"]);

        	$("input[name='document']").keydown(function(e) {//when click tab at document, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
			})
        },
        aftersavefunc: function (rowid, response, options) {
        	$('#apacthdr_outamount').val(response.responseText);
        	if(addmore_jqgrid2.state==true)addmore_jqgrid2.more=true; //only addmore after save inline
        	refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
        }, 
        errorfunc: function(rowid,response){
        	alert(response.responseText);
        	refreshGrid('#jqGrid2',urlParam2,'add');
	    	$("#jqGridPager2Delete").show();
        },
        beforeSaveRow: function(options, rowid) {

        	//if(errorField.length>0)return false;
        	mycurrency2.formatOff();
			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			let editurl = "./paymentVoucherDetail/form?"+
				$.param({
					action: 'paymentVoucherDetail_save',
					idno: $('#apacthdr_idno').val(),
					auditno:$('#apacthdr_auditno').val(),
					amount:data.amount,
				});
			$("#jqGrid2").jqGrid('setGridParam',{editurl:editurl});
        },
        afterrestorefunc : function( response ) {
			hideatdialogForm(false);
	    }
    };

    //////////////////////////////////////////pager jqgrid2/////////////////////////////////////////////
	$("#jqGrid2").inlineNav('#jqGridPager2',{	
		add:false,
		edit:false,
		cancel: false,
		save:false,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: { 
			addRowParams: myEditOptions
		},
		editParams: myEditOptions
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "del_alloc",
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
				    			action: 'del_alloc',
								idno: selrowData('#jqGrid2').idno,
								lineno_: selrowData('#jqGrid2').lineno_,
								auditno: selrowData('#jqGrid2').auditno,
								source: selrowData('#jqGrid2').source,
								trantype: selrowData('#jqGrid2').trantype,
								//apacthdr_outamount: selrowData('#jqGrid2').apacthdr_outamount,
				    		}
				    		$.post( "./paymentVoucher/form?"+$.param(param),{oper:'del_alloc',"_token": $("#_token").val()}, 
							function( data ){
							},'json').fail(function(data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function(data){
								mycurrency.formatOff();
								$('#apacthdr_amount').val(data.newoutamthdr);
								// $('#tot_Alloc').val(parseFloat($('#apacthdr_amount').val()) - parseFloat($('#apacthdr_outamount').val()));
								mycurrency.formatOn();
								refreshGrid("#jqGrid2",urlParam2);
							});
				    	}else{
        					//$("#jqGridPager2EditAll").show();
				    	}
				    }
				});
			}
		},
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "add_Alloc",
		caption:"Add",cursor: "pointer",position: "last", 
		buttonicon:"",
		title:"Add Alloc",
		onClickButton: function(){
			alloc_button_status('wait')
			populate_alloc_table();
		}
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "saveDetailLabel",
		caption:"Save",cursor: "pointer",position: "last", 
		buttonicon:"",
		title:"Save"
	}).jqGrid('navButtonAdd',"#jqGridPager2",{
		id: "cancel_Alloc",
		caption:"Cancel",cursor: "pointer",position: "last", 
		buttonicon:"",
		title:"Cancel",
		onClickButton: function(){
			refreshGrid("#jqGrid2",urlParam2);
			alloc_button_status('add');
		}
	// }).jqGrid('navButtonAdd',"#jqGridPager2",{
	// 	id: "saveDetailLabel",
	// 	caption:"Save",cursor: "pointer",position: "last", 
	// 	buttonicon:"",
	// 	title:"Detail"
	});

	alloc_button_status('wait');
	function alloc_button_status(status){
		switch(status){
			case 'wait':
					$('#jqGridPager2 #del_alloc,#jqGridPager2 #add_Alloc').hide();
					$('#jqGridPager2 #saveDetailLabel,#jqGridPager2 #cancel_Alloc').show();
					break;
			case 'add':
					$('#jqGridPager2 #saveDetailLabel,#jqGridPager2 #cancel_Alloc').hide();
					$('#jqGridPager2 #del_alloc,#jqGridPager2 #add_Alloc').show();
					break;
		}
	}

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field, table, case_;
		switch(options.colModel.name){
			case 'suppcode':field=['suppcode','name'];table="material.supplier";case_='suppcode';break;
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

	/////////////////////////////////////////////custom input////////////////////////////////////////////
	function documentCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
		return $('<div class="input-group"><input id="document" name="document" type="text" class="form-control input-sm" data-validation="required" value="'+val+'" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div>');
	}

	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	///////////Validation for document number////////////////////////////////////////////////////////
	
	$("#apacthdr_document").blur(function(){
		// check_suppcode_duplicate();
	});

	var duplicate_documentno = false;
	function check_suppcode_duplicate(){
		if(oper != 'view'){
			var id = "#apacthdr_document";
			var id2 = "apacthdr_document";
			var param={
				func:'getDocNo',
				action:'get_value_default',
				url: 'util/get_value_default',
				field:['document'],
				table_name:'finance.apacthdr'
			}

			param.filterCol = ['document', 'recstatus', 'idno'];
			param.filterVal = [$("#apacthdr_document").val(), '<>.CANCELLED','<>.'+$('#idno').val()];

			$.get( param.url+"?"+$.param(param), function( data ) {
			
			},'json').done(function(data) {
				if ($.isEmptyObject(data.rows)) {
					if($.inArray(id2,errorField)!==-1){
						errorField.splice($.inArray(id2,errorField), 1);
					}
					myerrorIt_only2(id,false);
					duplicate_documentno = false;
				} else {
					bootbox.alert("Duplicate Document No");
					$( id ).removeClass( "valid" ).addClass( "error" );
					if($.inArray(id2,errorField)===-1){
						errorField.push( id2 );
					}
					myerrorIt_only2(id,true);
					$(id).data('show_error','Duplicate Document No');
					duplicate_documentno = true;
				}
			});
		}
	}
	
	function checkduplicate(){
		var id = "#apacthdr_document";
		if(duplicate_documentno){
			bootbox.alert("Duplicate Document No");
			return false;
		}
		return true;
	}
	//////////////////////////////////////////saveDetailLabel & save////////////////////////////////////////////
	$("#save").click(function(){
		unsaved = false;
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		if(checkdate(true) && $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
			saveHeader("#formdata", oper,saveParam,{idno:$('#idno').val()},'refreshGrid');
			unsaved = false;
			// $("#dialogForm").dialog('close');
		}else{
			mycurrency.formatOn();
		}
	});

	$("#saveDetailLabel").click(function(){
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = false;
		alloc_button_status('wait')
		
		if(checkdate(true) && $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
			saveHeader("#formdata",oper,saveParam,{idno:$('#idno').val()},'refreshGrid');
			unsaved = false;
			errorField.length=0;
			// $("#dialogForm").dialog('close');
		}else{
			mycurrency.formatOn();
		}
	});

	
	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function(){
		emptyFormdata(errorField,'#formdata2');
		hideatdialogForm(true);
		dialog_bankcode.on();
		dialog_paymode.on();
		dialog_cheqno.on();
		dialog_suppcode.on();
		dialog_payto.on();
		enableForm('#formdata');
		rdonly('#formdata');
		$(".notiH").empty();
		refreshGrid("#jqGrid2",urlParam2);
		errorField.length=0;
	});


	////////////////////////////// jqGrid2_iladd + jqGrid2_iledit /////////////////////////////
	$("#jqGrid2_iladd, #jqGrid2_iledit").click(function(){

		$("#jqGridPager2Delete,#saveHeaderLabel").hide();
		//dialog_document.on();//start binding event on jqgrid2

		$("input[name='grnno']").keydown(function(e) {//when click tab at batchno, auto save
			var code = e.keyCode || e.which;
			if (code == '9')$('#jqGrid2_ilsave').click();
			
		});

	});	

	function onall_editfunc(){
		
		mycurrency2.formatOnBlur();//make field to currency on leave cursor
		
	}
	
	////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////

	// function setcolmodelforjqg3(){
	// 	var clone = $.extend({}, $("#jqGrid2").jqGrid('getGridParam','colModel'));
	// 	var arrayclone = Object.values(clone);
	// 	arrayclone.splice(0,1);

	// 	return arrayclone;
	// }

	$("#jqGrid3").jqGrid({
		datatype: "local",
		colModel: [
		{ label: 'Creditor', name: 'suppcode', width: 100, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
		{ label: 'Invoice Date', name: 'allocdate', width: 100, classes: 'wrap',
			formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}
		},
		{ label: 'Invoice No', name: 'reference', width: 100, classes: 'wrap text-uppercase',},
		{ label: 'Remarks', name: 'remarks', width: 100, classes: 'wrap', hidden:true},
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
		{ label: 'Amount Paid', name: 'allocamount', width: 100, classes: 'wrap', 
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
		{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
		{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true},
		{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true},
		{ label: 'docsource', name: 'docsource', width: 20, classes: 'wrap', hidden:true},
		{ label: 'doctrantype', name: 'doctrantype', width: 20, classes: 'wrap', hidden:true},
		{ label: 'docauditno', name: 'docauditno', width: 20, classes: 'wrap', hidden:true},
		{ label: 'reftrantype', name: 'reftrantype', width: 20, classes: 'wrap', hidden:true},
		{ label: 'refsource', name: 'refsource', width: 20, classes: 'wrap', hidden:true},
		{ label: 'refauditno', name: 'refauditno', width: 20, classes: 'wrap', hidden:true},
		{ label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden:true},
		{ label: 'Line No', name: 'lineno_', width: 80, classes: 'wrap', hidden:true}, 
		{ label: 'can_alloc', name: 'can_alloc', width: 20, classes: 'wrap', hidden:true},
		{ label: 'idno', name: 'idno', width: 80, classes: 'wrap', hidden:true}, ],
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3",
		loadComplete: function(data){
			$("#jqGrid3").setSelection($("#jqGrid3").getDataIDs()[0]);
			setjqgridHeight(data,'jqGrid3');
			calc_jq_height_onchange("jqGrid3");
			
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
		onSelectRow:function(rowid, selected){
			let refauditno = selrowData("#jqGrid3").refauditno;
			let idno = selrowData("#jqGrid3").idno;
			
			$("#link_do").attr('href','./paymentVoucher/table?action=link_pv&type=do&auditno='+refauditno);
			$("#link_po").attr('href','./paymentVoucher/table?action=link_pv&type=po&auditno='+refauditno);
			$("#link_invoice").attr('href','./paymentVoucher/table?action=link_pv&type=invoice&auditno='+refauditno);
		},
	});
	jqgrid_label_align_right("#jqGrid3");

	////////////////////////////////////////// object for dialog handler//////////////////////////////////////////

	var dialog_paymode = new ordialog(
		'paymode','debtor.paymode','#apacthdr_paymode',errorField,
		{	colModel:[
				{label:'Paymode',name:'paymode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Paytype',name:'paytype',width:200,classes:'pointer',hidden:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(){
				ordialog_buang_error_shj('apacthdr_cheqno',errorField);

				let data=selrowData('#'+dialog_paymode.gridname);
				$('#apacthdr_cheqno').prop('readonly',false);
				switch(data.paymode){
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
								$('#cheqno_parent').show();
								$('#apacthdr_cheqno').show();
								$('#cheqno_dh').show();
								$('#cheqdate_parent').show();
								$('#apacthdr_cheqdate').show();
								break;

					case 'CASH':
								$('#apacthdr_cheqno').prop('readonly',true);
								dialog_cheqno.off();
								$('#cheqno_parent').hide();
								$('#apacthdr_cheqno').hide();
								$('#cheqno_dh').hide();
								$('#cheqdate_parent').hide();
								$('#apacthdr_cheqdate').hide();
								// $("label[for='cheqdate_parent'], input#cheqdate_parent").hide();
								//$("label[for='cheqno_parent'], input#cheqno_parent").hide();
								break;

					case 'TT':
								$('#apacthdr_cheqno').prop('readonly',true);
								$('#cheqno_parent').text('TT No');
								dialog_cheqno.off();
								$('#cheqno_parent').show();
								$('#apacthdr_cheqno').show();
								$('#cheqno_dh').show();
								$('#cheqdate_parent').show();
								$('#apacthdr_cheqdate').show();
								break;

				}

				$('#apacthdr_bankcode').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#apacthdr_bankcode').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
			}
		},{
			title:"Select Paymode",
			open: function(){
				//let data=selrowData('#'+dialog_paymode.gridname);
				dialog_paymode.urlParam.filterCol=['recstatus', 'compcode', 'source'],
				dialog_paymode.urlParam.filterVal=['ACTIVE', 'session.compcode', $('#apacthdr_source').val()],
				dialog_paymode.urlParam.WhereInCol=['paytype'];
        		dialog_paymode.urlParam.WhereInVal=[['Bank Draft', 'Cheque', 'Cash', 'Tele Transfer']];
				}
			},'urlParam','radio','tab'
		);
	dialog_paymode.makedialog(true);

	var dialog_bankcode = new ordialog(
		'bankcode','finance.bank','#apacthdr_bankcode',errorField,
		{	colModel:[
				{label:'Bank Code',name:'bankcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'bankname',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
					filterCol:['compcode','recstatus'],
					filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(){
				$('#apacthdr_cheqno').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
						$(gridname+' tr#1').click();
						$(gridname+' tr#1').dblclick();
						$('#apacthdr_cheqno').focus();
					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
						$('#'+obj.dialogname).dialog('close');
					}
			}
		},{
			title:"Select Paymode",
			open: function(){
				dialog_bankcode.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_bankcode.urlParam.filterVal=['ACTIVE', 'session.compcode']
				}
			},'urlParam','radio','tab'
		);
	dialog_bankcode.makedialog(true);

	var dialog_cheqno = new ordialog(
		'cheqno','finance.chqtran','#apacthdr_cheqno',errorField,
		{	colModel:[
				{label:'Cheque No',name:'cheqno',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
				{label:'Bankcode',name:'bankcode',width:200,classes:'pointer',hidden:true},
				
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','OPEN']
			},
			ondblClickRow: function () {
				$('#apacthdr_cheqdate').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#apacthdr_cheqdate').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Cheque No",
			open: function(){
				dialog_cheqno.urlParam.filterCol=['compcode','recstatus', 'bankcode'],
				dialog_cheqno.urlParam.filterVal=['session.compcode','OPEN', $('#apacthdr_bankcode').val()]
			},
			width:4/10 * $(window).width()
		},'urlParam','radio','tab'
	);
	dialog_cheqno.makedialog(true);

	var dialog_suppcode = new ordialog(
		'suppcode','material.supplier','#apacthdr_suppcode',errorField,
		{	colModel:[
				{label:'Supplier Code',name:'suppcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Supplier Name',name:'name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'bankaccno',name:'AccNo',width:400,classes:'pointer',hidden:true},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(){
				if (($('#apacthdr_trantype').val()=="PV")) {
					$("#jqGrid2").jqGrid("clearGridData", true);

					let data=selrowData('#'+dialog_suppcode.gridname);
					$("#apacthdr_payto").val(data['suppcode']);
					$("#apacthdr_bankaccno").val(data['AccNo']);
					$("#jqGrid2 input[name='document']").val(data['suppcode']);
					$("#jqGrid2 input[name='entrydate']").val(data['recdate']); 
					$("#jqGrid2 input[name='reference']").val(data['document']);
					$("#jqGrid2 input[name='refamount']").val(data['amount']);
					$("#jqGrid2 input[name='outamount']").val(data['outamount']);
					$("#jqGrid2 input[name='remarks']").val(data['remarks']);

					var urlParam_ = {
						action: 'get_value_default',
						url: 'util/get_value_default',
						field: [],
						table_name: ['finance.apacthdr'],
						filterCol: ['apacthdr.payto', 'apacthdr.compcode', 'apacthdr.recstatus', 'apacthdr.outamount'],
						filterVal: [$("#apacthdr_suppcode").val(), 'session.compcode', 'POSTED', '>.0'],
						WhereInCol: ['apacthdr.source', 'apacthdr.trantype'],
						WhereInVal: [['AP','DF','CF','TX'],['IN','DN']],
						table_id: 'idno',
					};

					$.get("util/get_value_default?" + $.param(urlParam_), function (data) {
					}, 'json').done(function (data) {
						if (!$.isEmptyObject(data.rows)) {
							myerrorIt_only(dialog_suppcode.textfield,false);

							data.rows.forEach(function(elem) {
								$("#jqGrid2").jqGrid('addRowData', elem['idno'] ,
									{	
										idno:elem['idno'],
										suppcode:elem['suppcode'],
										allocdate:elem['recdate'],
										reference:elem['document'],
										refamount:elem['amount'],
										outamount:elem['outamount'],
										remarks:elem['remarks'],
										allocamount: 0,
										balance:elem['outamount'],
									
									}
								);
							});

							calc_amtpaid_bal();
							
							var ids = $("#jqGrid2").jqGrid('getDataIDs');
							for (var i = 0; i < ids.length; i++) {
								$("#jqGrid2").jqGrid('editRow',ids[i]);

								$('#jqGrid2 input#'+ids[i]+'_allocamount').on('change',{rowid:ids[i]},calc_amtpaid);
							}

						} else {
							alert("This supplier doesnt have any invoice!");
							$(dialog_suppcode.textfield).val('');
							myerrorIt_only(dialog_suppcode.textfield,true);
						}
					});
				} else {
					$("#jqGrid2").jqGrid("clearGridData", true);

					let data=selrowData('#'+dialog_suppcode.gridname);
					$("#apacthdr_payto").val(data['suppcode']);
					$("#apacthdr_bankaccno").val(data['AccNo']);
					$('#apacthdr_payto').focus();
				}
			},
		
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#apacthdr_payto').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_suppcode.urlParam.filterCol=['recstatus','compcode'];
				dialog_suppcode.urlParam.filterVal=['ACTIVE','session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_suppcode.makedialog();

	var dialog_payto = new ordialog(
		'payto','material.supplier','#apacthdr_payto',errorField,
		{	colModel:[
				{label:'Supplier Code',name:'SuppCode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'Name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow:function(){
				//$('#apacthdr_actdate').focus();
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
			title:"Select Supplier Code",
			open: function(){
				dialog_payto.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_payto.urlParam.filterVal=['ACTIVE', 'session.compcode']
				}
			},'urlParam','radio','tab'
		);
	dialog_payto.makedialog(true);

	$("#jqGrid_selection").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid").jqGrid('getGridParam','colModel'),
		shrinkToFit: false,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		sortname: 'apacthdr_idno',
		sortorder: "desc",
		onSelectRow: function (rowid, selected) {
			let rowdata = $('#jqGrid_selection').jqGrid ('getRowData');
		},
		gridComplete: function(){
			
		},
	})
	jqgrid_label_align_right("#jqGrid_selection");
	cbselect.on();

	function setjqgridHeight(data,grid){
		if(data.rows.length>=6){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(500);
		}else if(data.rows.length>=3){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(300);
		}else{
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(200);
		}
	}

	function if_cancel_hide(){
		if(selrowData('#jqGrid').apacthdr_recstatus.trim().toUpperCase() == 'CANCELLED'){
			$('#jqGrid3_panel').collapse('hide');
			$('#ifcancel_show').text(' - CANCELLED');
			$('#panel_jqGrid3').attr('data-target','-');
		}else{
			$('#jqGrid3_panel').collapse('show');
			$('#ifcancel_show').text('');
			$('#panel_jqGrid3').attr('data-target','#jqGrid3_panel');
		}
	}

	$("#jqGrid3_panel").on("show.bs.collapse", function(){
		$("#jqGrid3").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_c")[0].offsetWidth-$("#jqGrid3_c")[0].offsetLeft-28));
	});

});

function calc_amtpaid_bal(){
	$('input.checkbox_jqg2[type="checkbox"]').on('click',function(){
		let rowid = $(this).data('rowid');
		let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
		if($(this).prop('checked')){
			$('#jqGrid2 input#'+rowid+'_allocamount').val(data.outamount);
			$("#jqGrid2").jqGrid('setCell', rowid, 'balance', 0);
		}else{
			$('#jqGrid2 input#'+rowid+'_allocamount').val(0);
			$("#jqGrid2").jqGrid('setCell', rowid, 'balance', data.outamount);
		}

		recalc_totamt();
	});
}

function calc_amtpaid(event){
	let rowid = event.data.rowid;
	let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
	var val = $(this).val();
	if(parseFloat(val) > parseFloat(data.outamount)){
		$(this).val(data.outamount);
		event.preventDefault();
	}
	if(val.match(/[^0-9\.]/)){
		event.preventDefault();
		$(this).val(val.slice(0,val.length-1));
	}

	var balance = parseFloat(data.outamount) - parseFloat($(this).val());
	$("#jqGrid2").jqGrid('setCell', rowid, 'balance', balance);

	recalc_totamt();
}

function recalc_totamt(){
	var ids = $("#jqGrid2").jqGrid('getDataIDs');
	var totamt = 0;
	for (var i = 0; i < ids.length; i++) {
		let amt = currencyRealval('#jqGrid2 input#'+ids[i]+'_allocamount');

		totamt = totamt + amt;
	}

	$('#apacthdr_amount').val(totamt);
	mycurrency.formatOn();
}

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

function click_selection(id){
	// var date_id = 'date_injqgrid_'+$(id).data('idno');
	// var date_idno = $(id).data('idno');
	// var date_now = moment().format('YYYY-MM-DD');

	// var rowdata_copy = JSON.parse(JSON.stringify($('#jqGrid').jqGrid('getRowData', date_idno)));

	// if(rowdata_copy.apacthdr_recdate != ""){
	// 	date_now = moment().format(rowdata_copy.apacthdr_recdate);
	// }

	// if($(id).is(':checked')){
	// 	$(id).parent().prev().html( `<input class='form-control input-sm' 
	// 										type='date' 
	// 										id='`+date_id+`' 
	// 										value='`+date_now+`'
	// 										data-idno='`+date_idno+`'>` )

	// 	$('#'+date_id).change(function () {
	// 		let this_idno = $(this).data('idno');
	// 		$("#jqGrid_selection").jqGrid('setCell', this_idno, 'apacthdr_recdate', $(this).val());
	// 	});

	// }else{
	// 	// $("#jqGrid").jqGrid('setCell', this_idno, 'apacthdr_recdate', $(this).val());
	// 	// console.log(rowdata_copy);
	// 	$(id).parent().prev().html("");
	// 	// $("#jqGrid").jqGrid('restoreRow');
		
	// }
}

function calc_jq_height_onchange(jqgrid){
	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
	if(scrollHeight<50){
		scrollHeight = 50;
	}else if(scrollHeight>300){
		scrollHeight = 300;
	}
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight+30);
}

function populate_alloc_table(){
		$("#jqGrid2").jqGrid("clearGridData", true);

		var urlParam_ = {
			action: 'get_alloc_when_edit',
			url: 'paymentVoucher/table',
			field: [],
			table_name: ['finance.apacthdr'],
			filterCol: ['apacthdr.payto', 'apacthdr.compcode', 'apacthdr.recstatus', 'apacthdr.outamount'],
			filterVal: [$("#apacthdr_suppcode").val(), 'session.compcode', 'POSTED', '>.0'],
			WhereInCol: ['apacthdr.source', 'apacthdr.trantype'],
			WhereInVal: [['AP','DF','CF','TX'],['IN','DN']],
			table_id: 'idno',
			payto:$("#apacthdr_suppcode").val(),
			auditno:$("#apacthdr_auditno").val()
		};

		$.get("paymentVoucher/table?" + $.param(urlParam_), function (data) {
		}, 'json').done(function (data) {
			if (!$.isEmptyObject(data.rows)) {
				data.rows.forEach(function(elem) {
					let allocamount = 0;
					if(elem['can_alloc'] == false){
						allocamount = elem['allocamount'];
					}

					$("#jqGrid2").jqGrid('addRowData', elem['idno'] ,
						{	
							idno:elem['idno'],
							suppcode:elem['suppcode'],
							allocdate:elem['recdate'],
							reference:elem['document'],
							refamount:elem['amount'],
							outamount:elem['outamount'],
							allocamount: allocamount,
							balance:parseFloat(elem['outamount']) - parseFloat(allocamount),
							can_alloc:elem['can_alloc']
						}
					);
				});
				
				var ids = $("#jqGrid2").jqGrid('getDataIDs');
				for (var i = 0; i < ids.length; i++) {
					var rowdata = $("#jqGrid2").jqGrid('getRowData',ids[i]);
					if(rowdata.can_alloc == 'false'){
						continue;
					}
					$("#jqGrid2").jqGrid('editRow',ids[i]);

					$('#jqGrid2 input#'+ids[i]+'_allocamount').on('change',{rowid:ids[i]},calc_amtpaid);
				}

				calc_amtpaid_bal();

			}
		});
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
		if(this.selrowdata.apacthdr_support_remark != ''){
			$('i#support_remark_i').show();
			$('i#support_remark_i').data('remark',this.selrowdata.apacthdr_support_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Support Remark');
		}
		if(this.selrowdata.apacthdr_verified_remark != ''){
			$('i#verified_remark_i').show();
			$('i#verified_remark_i').data('remark',this.selrowdata.apacthdr_verified_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Verified Remark');
		}
		if(this.selrowdata.apacthdr_recommended1_remark != ''){
			$('i#recommended1_remark_i').show();
			$('i#recommended1_remark_i').data('remark',this.selrowdata.apacthdr_recommended1_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Recommended 1 Remark');
		}
		if(this.selrowdata.apacthdr_recommended2_remark != ''){
			$('i#recommended2_remark_i').show();
			$('i#recommended2_remark_i').data('remark',this.selrowdata.apacthdr_recommended2_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Recommended 2 Remark');
		}
		if(this.selrowdata.apacthdr_approved_remark != ''){
			$('i#approved_remark_i').show();
			$('i#approved_remark_i').data('remark',this.selrowdata.apacthdr_approved_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Approved Remark');
		}
		if(this.selrowdata.apacthdr_cancelled_remark != ''){
			$('i#cancelled_remark_i').show();
			$('i#cancelled_remark_i').data('remark',this.selrowdata.apacthdr_cancelled_remark);
			$('#dialog_remarks_view').dialog('option', 'title', 'Rejected Remark');
		}
		$('i.my_remark').on('click',function(){
			$('#remarks_view').val($(this).data('remark'));
			$("#dialog_remarks_view").dialog( "open" );
		});
	}
}
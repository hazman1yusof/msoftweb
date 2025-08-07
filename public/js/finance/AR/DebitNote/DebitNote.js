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

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency = new currencymode(['#db_outamount', '#db_amount']);
	var fdl = new faster_detail_load();
	var myfail_msg = new fail_msg_func();

	///////////////////////////////// trandate check date validate from period////////// ////////////////
	var actdateObj = new setactdate(["#db_entrydate"],true);
	actdateObj.getdata().set();

	////////////////////////////////////start dialog//////////////////////////////////////
	var oper = null;
	var unsaved = false;

	$("#dialogForm")
		.dialog({
			width: 9.5 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				actdateObj.getdata().set();
				$("#jqGrid2").jqGrid('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth - $("#jqGrid2_c")[0].offsetLeft));
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				switch (oper) {
					case state = 'add':
						$("#jqGrid2").jqGrid("clearGridData", true);
						$("#pg_jqGridPager2 table").show();
						hideatdialogForm(true);
						enableForm('#formdata');
						rdonly('#formdata');
						//$("#purreqhd_reqdept").val($("#x").val());
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
				} if (oper != 'add') {
					dialog_CustomerDN.check(errorField);
					dialog_paymode.check(errorField);
				} if (oper != 'view') {
					dialog_CustomerDN.on();
					dialog_paymode.on();
				}
			},
			beforeClose: function (event, ui) {
				if (unsaved) {
					event.preventDefault();
					bootbox.confirm("Are you sure want to leave without save?", function (result) {
						if (result == true) {
							unsaved = false
							$("#dialogForm").dialog('close');
						}
					});
				}
			},
			close: function (event, ui) {
				addmore_jqgrid2.state = false;//reset balik
			    addmore_jqgrid2.more = false;
			    //reset balik
			    parent_close_disabled(false);
				emptyFormdata(errorField, '#formdata');
				emptyFormdata(errorField, '#formdata2');
				$('.my-alert').detach();
				$("#formdata a").off();
				dialog_CustomerDN.off();
				dialog_paymode.off();
				$(".noti").empty();
				$("#refresh_jqGrid").click();
				refreshGrid("#jqGrid2",null,"kosongkan");
				errorField.length=0;
			},
		});
	////////////////////////////////////////end dialog///////////////////////////////////////////////////

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	var recstatus_filter = [['OPEN','REQUEST']];
	if($("#recstatus_use").val() == 'ALL'){
		recstatus_filter = [['OPEN','REQUEST','SUPPORT','INCOMPLETED','VERIFIED','APPROVED','CANCELLED']];
		filterCol_urlParam = ['purreqhd.compcode'];
		filterVal_urlParam = ['session.compcode'];
	}else if($("#recstatus_use").val() == 'SUPPORT'){
		recstatus_filter = [['REQUEST']];
		filterCol_urlParam = ['purreqhd.compcode','queuepr.AuthorisedID'];
		filterVal_urlParam = ['session.compcode','session.username'];
	}else if($("#recstatus_use").val() == 'VERIFIED'){
		recstatus_filter = [['SUPPORT']];
		filterCol_urlParam = ['purreqhd.compcode','queuepr.AuthorisedID'];
		filterVal_urlParam = ['session.compcode','session.username'];
	}else if($("#recstatus_use").val() == 'APPROVED'){
		recstatus_filter = [['VERIFIED']];
		filterCol_urlParam = ['purreqhd.compcode','queuepr.AuthorisedID'];
		filterVal_urlParam = ['session.compcode','session.username'];
	}

	var cbselect = new checkbox_selection("#jqGrid","Checkbox","db_idno","recstatus");
	
	////////////////////////////////////////////padzero////////////////////////////////////////////
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
	
	////////////////////////////////////////////searchClick2////////////////////////////////////////////
	function searchClick2(grid,form,urlParam){
		$(form+' [name=Stext]').on( "keyup", function() {
			delay(function(){
				search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
				$('#reqnodepan').text("");//tukar kat depan tu
				$('#reqdeptdepan').text("");
				refreshGrid("#jqGrid3",null,"kosongkan");
			}, 500 );
		});
		
		$(form+' [name=Scol]').on( "change", function() {
			search(grid,$(form+' [name=Stext]').val(),$(form+' [name=Scol] option:selected').val(),urlParam);
			$('#reqnodepan').text("");//tukar kat depan tu
			$('#reqdeptdepan').text("");
			refreshGrid("#jqGrid3",null,"kosongkan");
		});
	}

	////////////////////////////////////////////////jqGrid////////////////////////////////////////////////
	var urlParam = {
		action:'maintable',
		url:'DebitNote/table',
		field:'',
		table_name: ['debtor.dbacthdr as db','debtor.debtormast as dm'],
		table_id: 'idno',
		join_type: ['LEFT JOIN'],
		join_onCol: ['db.debtorcode'],
		join_onVal: ['dm.debtorcode'],
		filterCol: ['source','trantype'],
		filterVal: ['PB','DN'],
		// WhereInCol:['purreqhd.recstatus'],
		// WhereInVal: recstatus_filter,
		fixPost: true,
	}

	///////////////////////////////////////parameter for saving url///////////////////////////////////////
	var saveParam = {
		action: 'DebitNote_header_save',
		url:'./DebitNote/form',
		field: '',
		oper: oper,
		table_name: 'debtor.dbacthdr',
		table_id: 'idno',
		fixPost: true,
		//returnVal: true,
	}

	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'compcode', name: 'db_compcode', hidden: true },
			{ label: 'Payer Code', name: 'db_payercode', hidden: true },
			// { label: 'Payer Code', name: 'db_payercode', width: 15, canSearch: true },
			{ label: 'Debtor Code', name: 'db_debtorcode', width: 15, classes: 'wrap text-uppercase', canSearch: true },
			{ label: 'Name', name: 'dm_name', width: 50, classes: 'wrap text-uppercase', checked: true },
			{ label: 'Date', name: 'db_entrydate', width: 15, classes: 'wrap text-uppercase', canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter },
			// { label: 'Debit No', name: 'db_auditno', width: 12, align: 'right', canSearch: true },
			{ label: 'Debit No', name: 'db_auditno', width: 12, align: 'right', classes: 'wrap text-uppercase', canSearch: true, },			
			// { label: 'Debit No', name: 'db_invno', width: 15, canSearch: true, formatter: padzero5, unformat: unpadzero },
			{ label: 'Sector', name: 'db_unit', width: 15, hidden: true, classes: 'wrap' },
			{ label: 'PO No', name: 'db_ponum', width: 10, formatter: padzero5, unformat: unpadzero, hidden: true },
			{ label: 'Amount', name: 'db_amount', width: 15, align: 'right', formatter: 'currency' },
			{ label: 'Outstanding Amount', name: 'db_outamount', width: 15, align: 'right', formatter: 'currency' },
			{ label: 'Paymode', name: 'db_paymode', width: 25, classes: 'wrap text-uppercase', formatter: showdetail, unformat:un_showdetail },
			{ label: 'Status', name: 'db_recstatus', width: 15, classes: 'wrap text-uppercase' },
			{ label: 'Remark', name: 'db_remark', width: 20, classes: 'wrap', hidden: true },
			{ label: 'source', name: 'db_source', width: 10, hidden: true },
			{ label: 'Trantype', name: 'db_trantype', width: 10, hidden: true },
			{ label: 'lineno_', name: 'db_lineno_', width: 20, hidden: true },
			{ label: 'db_orderno', name: 'db_orderno', width: 10, hidden: true },
			{ label: 'outamount', name: 'db_outamount', width: 20, hidden: true },
			{ label: 'debtortype', name: 'db_debtortype', width: 20, hidden: true },
			{ label: 'billdebtor', name: 'db_billdebtor', width: 20, hidden: true },
			{ label: 'approvedby', name: 'db_approvedby', width: 20, hidden: true },
			{ label: 'db_paymode', name: 'db_paymode', width: 20, hidden: true },
			{ label: 'db_reference', name: 'db_reference', width: 20, hidden: true },
			{ label: 'mrn', name: 'db_mrn', width: 10, hidden: true },
			{ label: 'unit', name: 'db_unit', width: 10, hidden: true },
			{ label: 'termmode', name: 'db_termmode', width: 10, hidden: true },
			{ label: 'paytype', name: 'db_hdrtype', width: 10, hidden: true },
			{ label: 'source', name: 'db_source', width: 10, hidden: true },
			{ label: 'Posted Date', name: 'db_posteddate',hidden: true },
			{ label: 'Department Code', name: 'db_deptcode', width: 15, hidden: true },
			{ label: 'idno', name: 'db_idno', width: 10, hidden: true, key:true },
			{ label: 'adduser', name: 'db_adduser', width: 10, hidden: true },
			{ label: 'adddate', name: 'db_adddate', width: 10, hidden: true },
			{ label: 'upduser', name: 'db_upduser', width: 10, hidden: true },
			{ label: 'upddate', name: 'db_upddate', width: 10, hidden: true },
			// { label: 'remarks', name: 'db_remark', width: 10, hidden: true },
			{ label: ' ', name: 'Checkbox',sortable:false, width: 10,align: "center", formatter: formatterCheckbox },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		// sortname:'db_idno',
		// sortorder:'desc',
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow: function (rowid, selected) {
			$('#error_infront').text('');
			let stat = selrowData("#jqGrid").db_recstatus;
			let scope = $("#recstatus_use").val();
			
			urlParam2.source = selrowData("#jqGrid").db_source;
			urlParam2.trantype = selrowData("#jqGrid").db_trantype;
			urlParam2.auditno = selrowData("#jqGrid").db_auditno;
			
			$('#reqnodepan').text(selrowData("#jqGrid").purreqhd_purreqno);//tukar kat depan tu
			$('#reqdeptdepan').text(selrowData("#jqGrid").purreqhd_reqdept);
			refreshGrid("#jqGrid3", urlParam2);
			populate_form(selrowData("#jqGrid"));
			
			$("#pdfgen1").attr('href','./DebitNote/showpdf?auditno='+selrowData("#jqGrid").db_auditno);
			if_cancel_hide();
			
			urlParamAlloc.source=selrowData("#jqGrid").db_source;
			urlParamAlloc.trantype=selrowData("#jqGrid").db_trantype;
			urlParamAlloc.auditno=selrowData("#jqGrid").db_auditno;
			refreshGrid("#jqGridArAlloc",urlParamAlloc);
		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
			let stat = selrowData("#jqGrid").db_recstatus;
			if(stat=='OPEN' || stat=='INCOMPLETED'){
				$("#jqGridPager td[title='Edit Selected Row']").click();
			}else{
				$("#jqGridPager td[title='View Selected Row']").click();
			}
		},
		gridComplete: function () {
			refreshGrid("#jqGrid3",null,"kosongkan");
			$('#but_cancel_jq,#but_post_jq').hide();
			
			if (oper == 'add' || oper == null || $("#jqGrid").data('lastselrow') == undefined) {
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}else{
				$("#jqGrid").setSelection($("#jqGrid").data('lastselrow'));
				delay(function(){
					$('#jqGrid tr#'+$("#jqGrid").data('lastselrow')).focus();
				}, 300 );
			}
			
			if($('#jqGrid').data('inputfocus') == 'payer_search'){
				$("#payer_search").focus();
				$('#jqGrid').data('inputfocus','');
				$('#payer_search_hb').text('');
				removeValidationClass(['#payer_search']);
			}else{
				$("#searchForm input[name=Stext]").focus();
			}
			empty_form();
			
			populate_form(selrowData("#jqGrid"));
			fdl.set_array().reset();
			
			cbselect.refresh_seltbl();
			cbselect.show_hide_table();
			cbselect.checkbox_function_on();
		},
		loadComplete:function(data){
			// calc_jq_height_onchange("jqGrid");
		}
	});

	////////////////////// set label jqGrid right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid");

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid", urlParam, oper);
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
			refreshGrid("#jqGrid2", urlParam2);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", id: "glyphicon-edit", position: "first",
		buttonicon: "glyphicon glyphicon-edit",
		title: "Edit Selected Row",
		onClickButton: function () {
			oper = 'edit';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			$("#jqGrid").data('lastselrow',selRowId);
			populateFormdata("#jqGrid", "#dialogForm", "#formdata", selRowId, 'edit', '');
			refreshGrid("#jqGrid2", urlParam2);
			
			if(selrowData('#jqGrid').db_recstatus == 'POSTED'){
				disableForm('#formdata');
				$("#pg_jqGridPager2 table").hide();
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

	//////////handle searching, its radio button and toggle /////////////////////////////////////////////
	populateSelect('#jqGrid', '#searchForm');

	//////////add field into param, refresh grid if needed///////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam,['Checkbox']);
	addParamField('#jqGrid', false, saveParam, ['purreqhd_recno','purreqhd_purordno','purreqhd_adduser', 'purreqhd_adddate', 'db_mrn', 'supplier_name','purreqhd_purreqno','purreqhd_upduser','purreqhd_upddate','purreqhd_deluser', 'purreqhd_recstatus','purreqhd_unit','Checkbox','queuepr_AuthorisedID']);

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

	///////////////////////////////// trandate check date validate from period////////// ////////////////
	var actdateObj = new setactdate(["#trandate"]);
	actdateObj.getdata().set();

	///////////////////////////////////////save POSTED,CANCEL,REOPEN/////////////////////////////////////
	$('#jqGrid2_ilcancel').click(function(){
		$(".noti").empty();
	});

	$("#but_post_jq,#but_reopen_jq,#but_post_single_jq,#but_cancel_jq").click(function(){
		$(this).attr('disabled',true);
		var self_ = this;
		var idno_array = [];
	
		idno_array = $('#jqGrid_selection').jqGrid ('getDataIDs');
		var obj={};
		obj.idno_array = idno_array;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		
		$.post( 'DebitNote/form', obj , function( data ) {
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

	$("#but_post2_jq").click(function(){
		var obj={};
		obj.idno = selrowData('#jqGrid').db_idno;
		obj.oper = $(this).data('oper');
		obj._token = $('#_token').val();
		oper=null;
		
		$.post( './DebitNote/form', obj , function( data ) {
			cbselect.empty_sel_tbl();
			refreshGrid('#jqGrid', urlParam);
		}).fail(function(data) {
			$('#error_infront').text(data.responseText);
		}).success(function(data){
			
		});
	});
	
	//////////////////////////////check docdate//////////////////////////////
	$("#db_entrydate").blur(checkdate);

	function checkdate(nkreturn=false){
		var db_entrydate = $('#db_entrydate').val();
		
		text_success1('#db_entrydate')
		$("#dialogForm .noti ol").empty();
		var failmsg=[];
		
		if(moment(db_entrydate).isAfter(moment())){
			failmsg.push("Date cannot be higher than today");
			text_error1('#db_entrydate')
		}
		
		if(failmsg.length){
			failmsg.forEach(function(element){
				$('#dialogForm .noti ol').prepend('<li>'+element+'</li>');
			});
			if(nkreturn)return false;
		}else{
			if(nkreturn)return true;
		}
	}

	/////////////////////////////////saveHeader//////////////////////////////////////////////////////////
	function saveHeader(form, selfoper, saveParam, obj) {
		myfail_msg.clear_fail();
		if (obj == null) {
			obj = {};
		}
		saveParam.oper = selfoper;
		
		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
			
		},'json').fail(function (data) {
			console.log(data);
			// alert(data.responseJSON.message);
			// $('.noti').text(data.responseJSON.message);

			myfail_msg.add_fail({
				id:'response',
				textfld:"",
				msg:data.responseText,
			});
			$("#saveDetailLabel").attr('disabled',false);
		}).done(function (data) {
			$("#saveDetailLabel").attr('disabled',false);
			unsaved = false;
			hideatdialogForm(false);
			addmore_jqgrid2.state = true;
			
			if($('#jqGrid2').jqGrid('getGridParam', 'reccount') < 1){
				$('#jqGrid2_iladd').click();
			}
			
			if (selfoper == 'add') {
				oper = 'edit';//sekali dia add terus jadi edit lepas tu
				
				$('#db_auditno').val(data.db_auditno);
				$('#db_idno').val(data.idno);//just save idno for edit later
				$('#db_amount').val(data.totalAmount);
				
				urlParam2.auditno = data.db_auditno;
			} else if (selfoper == 'edit') {
				urlParam2.auditno = $('#db_auditno').val();
				//doesnt need to do anything
			}
			disableForm('#formdata');
		})
	}

	$("#dialogForm").on('change keypress', '#formdata :input', '#formdata :textarea', function () {
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	$("#dialogForm").on('click','#formdata a.input-group-addon',function(){
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	////////////////////////////changing status and trandept trigger search/////////////////////////
	$('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	//$('#trandept').on('change', searchChange);
	$('#docudate_search').on('click', searchDate);

	function whenchangetodate() {
		urlParam.fromdate=urlParam.todate=null;
		payer_search.off();
		$('#payer_search, #docudate_from, #docudate_to').val('');
		$('#payer_search_hb').text('');
		$("input[name='Stext'],#docudate_text,#creditor_text").hide();
		removeValidationClass(['#payer_search']);
		if ($('#Scol').val() == 'db_entrydate'){
			$("#docudate_text").show();
		}else if($('#Scol').val() == 'db_debtorcode'){
			$("#creditor_text").show("fast");
			payer_search.on();
		}else{
			$("input[name='Stext']").show("fast");
		}
	}

	///////////////////utk dropdown search By/////////////////////////////////////////////////
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
					urlParam.searchCol=["db_debtorcode"];
					urlParam.searchVal=[data];
				}else if($('#Scol').val() == 'db_payercode'){
					urlParam.searchCol=["db_payercode"];
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
			title: "Select Customer",
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
				
				urlParam.searchCol=["purreqhd_suppcode"];
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
			title: "Select Purchase Department",
			open: function () {
				dialog_suppcode.urlParam.filterCol = ['compcode','recstatus'];
				dialog_suppcode.urlParam.filterVal = ['session.compcode','ACTIVE'];
			}
		}
	);
	supplierkatdepan.makedialog();

	function searchDate(){
		urlParam.fromdate = $('#docudate_from').val();
		urlParam.todate = $('#docudate_to').val();
		refreshGrid('#jqGrid',urlParam);
	}

	function searchbydate() {
		search('#jqGrid', $('#searchForm [name=Stext]').val(), $('#searchForm [name=Scol] option:selected').val(), urlParam);
	}

	function searchChange() {
		cbselect.empty_sel_tbl();
		var arrtemp = ['session.compcode', $('#Status option:selected').val()];
		
		var filter = arrtemp.reduce(function (a, b, c) {
			if (b.toUpperCase() == 'ALL') {
				return a;
			} else {
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		}, { fct: ['db.compcode', 'db.recstatus'], fv: [], fc: [] });
		
		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		urlParam.WhereInCol = null;
		urlParam.WhereInVal = null;
		refreshGrid('#jqGrid', urlParam);
	}

	/////////////////////parameter for jqgrid2 url///////////////////////////////////////////////////////
	var urlParam2 = {
		action: 'get_table_dtl',
		url:'DebitNoteDetail/table',
		source:'',
		trantype:'',
		auditno:'',
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong

	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./DebitNoteDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'AuditNo', name: 'auditno', hidden: true },
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden: true, editable: true },
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden: true, editable: true },
			{ label: 'Department', name: 'deptcode', width: 150, classes: 'wrap', canSearch: true, editable: true, 
				editrules: { required: true, custom: true, custom_func: cust_rules },
				formatter: showdetail, edittype: 'custom', 
				editoptions: {
					custom_element: deptcodeCustomEdit,
					custom_value: galGridCustomValue
				},
			},
			// { label: 'Category', name: 'category', width: 150, edittype:'text', classes: 'wrap', editable: true,
			// 	editrules:{required: true,custom:true, custom_func:cust_rules},
			// 	formatter: showdetail,
			// 	edittype:'custom',	editoptions:
			// 		{
			// 			custom_element:categoryCustomEdit,
			// 			custom_value:galGridCustomValue
			// 		},
			// },
			{ label: 'Document', name: 'document', width: 120, classes: 'wrap', editable: true, hidden: true, 
				// editrules: { required: true },
				edittype: "text",
				editoptions: { style: "text-transform: uppercase" },
			},
			{ label: 'GST Code', name: 'GSTCode', width: 100, classes: 'wrap', editable: true,
				editrules:{required: true,custom:true, custom_func:cust_rules},
				formatter: showdetail,
				edittype:'custom',	editoptions:
					{
						custom_element:GSTCodeCustomEdit,
						custom_value:galGridCustomValue
					},
			},
			{ label: 'Amount Before GST', name: 'AmtB4GST', width: 90, classes: 'wrap',
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: true,
				align: "right",
				editrules:{required: true},edittype:"text",
				editoptions:{
					maxlength: 12,
					dataInit: function(element) {
						element.style.textAlign = 'right';
					}
				},
			},
			{ label: 'Total Tax Amount', name: 'tot_gst', width: 90, align: 'right', classes: 'wrap', editable:true,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, },
				editrules:{required: true},
				editoptions:{
					readonly: "readonly",
					maxlength: 12,
					dataInit: function(element) {
						element.style.textAlign = 'right';
					}
				},
			},
			{ label: 'Amount', name: 'amount', width: 90, classes: 'wrap',
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: true,
				align: "right",
				editrules:{required: true},edittype:"text",
				editoptions:{
					readonly: "readonly",
					maxlength: 12,
					dataInit: function(element) {
						element.style.textAlign = 'right';
					},
				}
			},
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
			/// console.log(addmore_jqgrid2); ///
			if(addmore_jqgrid2.more == true){$('#jqGrid2_iladd').click();}
			else{
				$('#jqGrid2').jqGrid('setSelection', "1");
			}
			
			setjqgridHeight(data,'jqGrid2');			
			addmore_jqgrid2.edit = addmore_jqgrid2.more = false; //reset
			calc_jq_height_onchange("jqGrid2");
		},		
		gridComplete: function(){
			fdl.set_array().reset();
			//fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
		},
		beforeSubmit: function (postdata, rowid) {
			dialog_billtypeSO.check(errorField);
			//dialog_mrn.check(errorField);
			dialog_CustomerDN.check(errorField);
			dialog_approvedbySO.check(errorField);
			dialog_paymode.check(errorField);
		}
	})

	////////////////////// set label jqGrid2 right ////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	/////////////////////////all function for remarks//////////////////////////////////////////////////
	function formatterRemarks(cellvalue, options, rowObject){
		return "<button class='remarks_button btn btn-success btn-xs' type='button' data-rowid='"+options.rowId+"' data-lineno_='"+rowObject.lineno_+"' data-grid='#"+options.gid+"' data-remarks='"+rowObject.remarks+"'><i class='fa fa-file-text-o'></i> remark</button>";
	}

	function unformatRemarks(cellvalue, options, rowObject) {
		return null;
	}

	function formatterCheckbox(cellvalue, options, rowObject){
		let idno = cbselect.idno;
		let recstatus = rowObject.db_recstatus;
		
		if(options.gid == "jqGrid"){
			if(recstatus == 'OPEN'){
				return "<input type='checkbox' name='checkbox_selection' id='checkbox_selection_"+rowObject[idno]+"' data-idno='"+rowObject[idno]+"' data-rowid='"+options.rowId+"'>";
			}else{
				return ' ';
			}
		}else if(options.gid != "jqGrid"){
			return "<button class='btn btn-xs btn-danger btn-md' id='delete_"+rowObject[idno]+"' ><i class='fa fa-trash' aria-hidden='true'></i></button>";
		}else{
			return ' ';
		}
	}

	var butt1_rem = 
		[{
			text: "Save",click: function() {
				let newval = $("#remarks2").val();
				let rowid = $('#remarks2').data('rowid');
				$("#jqGrid2").jqGrid('setRowData', rowid ,{remarks:newval});
				if($("#jqGridPager2SaveAll").css('display') == 'none'){
					$("#jqGrid2_ilsave").click();
				}
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
			let exist = $("#jqGrid2 #"+rowid+"_pouom_convfactor_uom").length;
			if(grid == '#jqGrid3' || exist==0){ // lepas ni letak or not edit mode
				$("#remarks2").prop('disabled',true);
				$( "#dialog_remarks").dialog("option", "buttons", butt2_rem);
			}else{
				$("#remarks2").prop('disabled',false);
				$( "#dialog_remarks").dialog("option", "buttons", butt1_rem);
			}
		},
		buttons : butt2_rem
	});

	//////////////////////////////////////////myEditOptions/////////////////////////////////////////////
	var myEditOptions = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGrid2").setSelection($("#jqGrid2").getDataIDs()[0]);
			errorField.length=0;
			$("#jqGrid2 input[name='deptcode']").focus().select();
			$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();
			
			dialog_deptcode.on();
			// dialog_category.on();
			dialog_GSTCode.on();
			
			unsaved = false;
			mycurrency2.array.length = 0;
			// mycurrency_np.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='AmtB4GST']","#jqGrid2 input[name='tot_gst']","#jqGrid2 input[name='amount']"]);
			// Array.prototype.push.apply(mycurrency_np.array, ["#jqGrid2 input[name='qtyonhand']","#jqGrid2 input[name='quantity']"]);
			
			mycurrency2.formatOnBlur();//make field to currency on leave cursor
			// mycurrency_np.formatOnBlur();//make field to currency on leave cursor
			
			$("#jqGrid2 input[name='amount'],#jqGrid2 input[name='AmtB4GST']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);
			
			$("input[name='amount']").keydown(function(e) {//when click tab at amount, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options) {
			$('#db_amount').val(response.responseText);
			if(addmore_jqgrid2.state == true)addmore_jqgrid2.more=true; //only addmore after save inline
			// state true maksudnyer ada isi, tak kosong
			refreshGrid('#jqGrid2',urlParam2,'add');
			$("#jqGridPager2EditAll,#jqGridPager2Delete").show();
			errorField.length=0;
		},
		errorfunc: function(rowid,response){
			alert(response.responseText);
			refreshGrid('#jqGrid2',urlParam2,'add');
			$("#jqGridPager2Delete").show();
		},
		beforeSaveRow: function (options, rowid) {
			if(errorField.length>0)return false;
			mycurrency2.formatOff();
			// mycurrency_np.formatOff();
			
			if(parseInt($('#jqGrid2 input[name="amount"]').val()) == 0){
				myerrorIt_only('#jqGrid2 input[name="amount"]');
				alert('Amount cant be 0');
				return false;
			}
			
			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			// console.log(data);
			
			let editurl = "./DebitNoteDetail/form?"+
				$.param({
					action: 'debitnote_detail_save',
					idno: $('#db_idno').val(),
					lineno_:data.lineno_,
					source: $('#db_source').val(),
					trantype: $('#db_trantype').val(),
					auditno: $('#db_auditno').val(),
					amount:data.amount,
				});
			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			errorField.length=0;
			hideatdialogForm(false);
		},
	    errorTextFormat: function (data) {
	    	alert(data);
	    }
	};

	//////////////////////////////////////////pager jqgrid2/////////////////////////////////////////////
	$("#jqGrid2").inlineNav('#jqGridPager2', {
		add: true,
		edit: true,
		cancel: true,
		//to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions
		},
		editParams: myEditOptions
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "jqGridPager2Delete",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function () {
			selRowId = $("#jqGrid2").jqGrid('getGridParam', 'selrow');
			if (!selRowId) {
				bootbox.alert('Please select row');
			} else {
				bootbox.confirm({
					message: "Are you sure you want to delete this row?",
					buttons: {
						confirm: { label: 'Yes', className: 'btn-success', }, cancel: { label: 'No', className: 'btn-danger' }
					},
					callback: function (result) {
						if (result == true) {
							param = {
								_token: $("#_token").val(),
								action: 'debitnote_detail_save',
								source: $('#db_source').val(),
								trantype: $('#db_trantype').val(),
								auditno: $('#db_auditno').val(),
								lineno_: selrowData('#jqGrid2').lineno_,
								idno: selrowData('#jqGrid2').idno,
							}
							$.post( "./DebitNoteDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, 
							function( data ){
							}).fail(function (data){
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data){
								$('#db_amount').val(data);
								// $('#amount').val(data);
								refreshGrid("#jqGrid2", urlParam2);
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
			// mycurrency_np.array.length = 0;
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			for (var i = 0; i < ids.length; i++) {
				$("#jqGrid2").jqGrid('editRow',ids[i]);
				
				Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_AmtB4GST","#"+ids[i]+"_tot_gst","#"+ids[i]+"_amount"]);
				
				// Array.prototype.push.apply(mycurrency_np.array, ["#"+ids[i]+"_quantity"]);
				
				cari_gstpercent2(ids[i]);
				
				dialog_deptcode.id_optid = ids[i];
				dialog_deptcode.check(errorField,ids[i]+"_deptcode","jqGrid2",null,
					function(self){
						if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
					}
				);
				
				dialog_GSTCode.id_optid = ids[i];
				dialog_GSTCode.check(errorField,ids[i]+"_GSTCode","jqGrid2",null,
					function(self){
						if(self.dialog_.hasOwnProperty('open'))self.dialog_.open(self);
					}
				);
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
			// mycurrency_np.formatOff();
			
			// if(errorField.length>0){
			// 	console.log(errorField)
			// 	return false;
			// }
			
			for (var i = 0; i < ids.length; i++) {
				// if(parseInt($('#'+ids[i]+"_quantity").val()) <= 0)return false;
				var data = $('#jqGrid2').jqGrid('getRowData',ids[i]);
				let retval = check_cust_rules("#jqGrid2",data);
				// console.log(retval);
				if(retval[0]!= true){
					alert(retval[1]);
					mycurrency2.formatOn();
					return false;
				}
				
				if(parseInt($("#jqGrid2 input#"+ids[i]+"_amount").val()) == 0){
					alert('Amount cant be 0');
					mycurrency2.formatOn();
					return false;
				}
				
				// cust_rules()
				
				var obj = 
				{
					'lineno_' : ids[i],
					'idno' : data.idno,
					'deptcode' : $("#jqGrid2 input#"+ids[i]+"_deptcode").val(),
					// 'category' : $("#jqGrid2 input#"+ids[i]+"_category").val(),
					// 'document' : $("#jqGrid2 input#"+ids[i]+"_document").val(),
					'GSTCode' : $("#jqGrid2 input#"+ids[i]+"_GSTCode").val(),
					'AmtB4GST' : $("#jqGrid2 input#"+ids[i]+"_AmtB4GST").val(),
					'tot_gst' : $("#jqGrid2 input#"+ids[i]+"_tot_gst").val(),
					'amount' : $("#jqGrid2 input#"+ids[i]+"_amount").val(),
					'unit' : $("#jqGrid2 input#"+ids[i]+"_unit").val(),
				}
				
				jqgrid2_data.push(obj);
			}
			
			var param={
				action: 'debitnote_detail_save',
				_token: $("#_token").val(),
				auditno: $('#db_auditno').val(),
				source: $('#db_source').val(),
				trantype: $('#db_trantype').val(),
				idno: $('#db_idno').val()
			}
			
			$.post( "/DebitNoteDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				// alert(dialog,data.responseText);
			}).done(function(data){
				$('#db_amount').val(data);
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
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "saveHeaderLabel",
		caption: "Header", cursor: "pointer", position: "last",
		buttonicon: "",
		title: "Header"
	}).jqGrid('navButtonAdd', "#jqGridPager2", {
		id: "saveDetailLabel",
		caption: "Detail", cursor: "pointer", position: "last",
		buttonicon: "",
		title: "Detail"
	});

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field,table, case_;
		switch(options.colModel.name){
			case 'db_deptcode':field=['deptcode','description'];table="sysdb.department";case_='db_deptcode';break;
			case 'deptcode':field=['deptcode','description'];table="sysdb.department";break;
			// case 'category':field=['catcode','description'];table="material.category";break;
			case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";case_='GSTCode';break;
			
			// jqGrid
			case 'db_paymode':field=['paymode','description'];table="debtor.paymode";case_='db_paymode';break;
			
			case 'chggroup':field=['chgcode','description'];table="hisdb.chgmast";case_='chggroup';break;
			case 'uom':field=['uomcode','description'];table="material.uom";case_='uom';break;
			// case 'db_payercode':field=['debtorcode','name'];table="debtor.debtormast";case_='db_payercode';break;
			
			// jqGridArAlloc
			case 'debtorcode':field=['debtorcode','name'];table="debtor.debtormast";case_='debtorcode';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
		
		fdl.get_array('DebitNote',options,param,case_,cellvalue);
		
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value, name) {
		var temp=null;
		switch (name) {
			case 'Department':temp=$('#deptcode');break;
			// case 'Category':temp=$('#category');break;
			
			case 'Item Code': temp = $("#jqGrid2 input[name='chggroup']"); break;
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
	function uomcodeCustomEdit(val,opt){
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));	
		return $(`<div class="input-group"><input jqgrid="jqGrid2" optid="`+opt.id+`" id="`+opt.id+`" name="uom" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="`+val+`" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>
			<span><input id="`+opt.id+`_discamt" name="discamt" type="hidden"></span>
			<span><input id="`+opt.id+`_rate" name="rate" type="hidden"></span>`);
	}

	function deptcodeCustomEdit(val, opt) {
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="deptcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	// function categoryCustomEdit(val, opt) {
	// 	val = getEditVal(val);
	// 	return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="category" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	// }

	function GSTCodeCustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		
		var id_optid = opt.id.substring(0,opt.id.search("_"));
		return $(`<div class="input-group"><input jqgrid="jqGrid2" optid="`+opt.id+`" id="`+opt.id+`" name="GSTCode" type="text" class="form-control input-sm text-uppercase" data-validation="required" value="` + val + `"style="z-index: 0" ><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span><div class="input-group"><input id="`+id_optid+`_gstpercent" name="gstpercent" type="hidden"></div>`);
	}

	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}

	//////////////////////////////////////////saveDetailLabel////////////////////////////////////////////
	$("#saveDetailLabel").click(function () {
		$("#saveDetailLabel").attr('disabled',true)
		mycurrency.formatOff();
		mycurrency.check0value(errorField);
		unsaved = false;
		
		errorField.length = 0;
		if(checkdate(true) && $('#formdata').isValid({requiredFields:''},conf,true)){
			saveHeader("#formdata",oper,saveParam);
			mycurrency.formatOn();
			unsaved = false;
		} else {
			mycurrency.formatOn();
			dialog_CustomerDN.on();
			dialog_paymode.on();
			//dialog_mrn.on();
		}
	});

	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function () {
		emptyFormdata(errorField, '#formdata2');
		hideatdialogForm(true);
		dialog_CustomerDN.on();
		dialog_paymode.on();
		
		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		refreshGrid("#jqGrid2", urlParam2);
	});

	/////////////calculate conv fac//////////////////////////////////
	function remove_noti(event){
		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));
		
		remove_error("#jqGrid2 #"+id_optid+"_pouom");
		remove_error("#jqGrid2 #"+id_optid+"_quantity");
		delay(function(){
			remove_error("#jqGrid2 #"+id_optid+"_pouom");
		}, 500 );
		
		$(".noti").empty();
	}

	/////////////////////////////edit all//////////////////////////////////////////////////
	function onall_editfunc(){
		errorField.length=0;
		dialog_deptcode.on();
		// dialog_category.on();
		dialog_GSTCode.on();
		
		mycurrency2.formatOnBlur();//make field to currency on leave cursor
		// mycurrency_np.formatOnBlur();//make field to currency on leave cursor
		
		// $("#jqGrid2 input[name='amount'],#jqGrid2 input[name='AmtB4GST']").on('blur',{currency: [mycurrency2,mycurrency_np]},calculate_line_totgst_and_totamt);
		// $("#jqGrid2 input[name='tot_gst'],#jqGrid2 input[name='tot_gst']").on('blur',{currency: [mycurrency2,mycurrency_np]},calculate_line_totgst_and_totamt);
		$("#jqGrid2 input[name='amount'],#jqGrid2 input[name='AmtB4GST']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);
		// $("#jqGrid2 input[name='uomcode'],#jqGrid2 input[name='pouom'],#jqGrid2 input[name='pricecode'],#jqGrid2 input[name='chggroup']").on('focus',remove_noti);
	}

	////////////////////////////////////////calculate_line_totgst_and_totamt////////////////////////////
	function cari_gstpercent2(id){
		let data = $('#jqGrid2').jqGrid ('getRowData', id);
		let gstpercent = 0.00;
		if(data.tot_gst != ''){
			let tot_gst = data.tot_gst;
			let amntb4gst = data.AmtB4GST;
			gstpercent = (tot_gst / amntb4gst) * 100;
		}
		
		$("#jqGrid2 #"+id+"_gstpercent").val(gstpercent);
	}

	var mycurrency2 =new currencymode([]);
	//var mycurrency_np =new currencymode([],true);
	function calculate_line_totgst_and_totamt(event) {
		mycurrency2.formatOff();
		var optid = event.currentTarget.id;
		var id_optid = optid.substring(0,optid.search("_"));
		
		let amntb4gst = parseFloat($("#"+id_optid+"_AmtB4GST").val());
		let gstpercent = parseFloat($("#jqGrid2 #"+id_optid+"_gstpercent").val());
		
		var tot_gst = amntb4gst * (gstpercent / 100);
		var amount = amntb4gst + tot_gst;
		
		$("#"+id_optid+"_tot_gst").val(tot_gst);
		
		$("#jqGrid2 #"+id_optid+"_amount").val(amount);
		
		calculate_total_header();
		
		if(event.data != undefined){
			event.data.currency.formatOn();//change format to currency on each calculation
		}
	}

	function calculate_line_totgst_and_totamt2(id_optid) {
		mycurrency.formatOff();
		mycurrency2.formatOff();
		
		let amntb4gst = parseFloat($(id_optid+"_AmtB4GST").val());
		let gstpercent = parseFloat($(id_optid+"_gstpercent").val());
		
		var tot_gst = amntb4gst * (gstpercent / 100);
		var amount = amntb4gst + tot_gst;
		
		$(id_optid+"_tot_gst").val(tot_gst);
		
		$(id_optid+"_amount").val(amount);
		
		calculate_total_header();
		
		mycurrency.formatOn();
		mycurrency2.formatOn();
	}

	function calculate_total_header(){
		var rowids = $('#jqGrid2').jqGrid('getDataIDs');
		var totamt = 0
		
		rowids.forEach(function(e,i){
			let amt = $('#jqGrid2 input#'+e+'_amount').val();
			if(amt != undefined){
				totamt = parseFloat(totamt)+parseFloat(amt);
			}else{
				let rowdata = $('#jqGrid2').jqGrid ('getRowData',e);
				totamt = parseFloat(totamt)+parseFloat(rowdata.amount);
			}
		});
		
		if(!isNaN(totamt)){
			$('#db_amount').val(numeral(totamt).format('0,0.00'));
		}
	}

	////////////////////////////////////////////////jqgrid3//////////////////////////////////////////////
	$("#jqGrid3").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3",
		loadComplete: function(data){
			data.rows.forEach(function(element){
				if(element.callback_param != null){//ini baru
					$("#"+element.callback_param[2]).on('click', function() {
						seemoreFunction(
							element.callback_param[0],
							element.callback_param[1],
							element.callback_param[2]
						)
					});
				}
			});
			
			setjqgridHeight(data,'jqGrid3');
			calc_jq_height_onchange("jqGrid3");
		},
		gridComplete: function(){
			$("#jqGrid3").find(".remarks_button").on("click", function(e){
				$("#remarks2").data('rowid',$(this).data('rowid'))
				$("#remarks2").data('grid',$(this).data('grid'))
				$("#dialog_remarks").dialog( "open" );
			});
			fdl.set_array().reset();
			
			// calculate_quantity_outstanding('#jqGrid3');
		},
	})
	jqgrid_label_align_right("#jqGrid3");

	////////////////////////////////////////////////parameter for jqGridArAlloc url////////////////////////////////////////////////
	var urlParamAlloc={
		action: 'get_alloc_table',
		url:'DebitNote/table',
		source:'',
		trantype:'',
		auditno:'',
	};

	////////////////////////////////////////////////jqGridArAlloc////////////////////////////////////////////////
	$("#jqGridArAlloc").jqGrid({
		datatype: "local",
		colModel: [
			{ label: ' ', name: 'checkbox', width: 15, formatter: checkbox_jqgAlloc, hidden:true},
			{ label: 'Debtor', name: 'debtorcode', width: 100, classes: 'wrap', formatter: showdetail,unformat:un_showdetail },
			{ label: 'Document Date', name: 'entrydate', width: 100, classes: 'wrap',
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}
			},
			{ label: 'Posted Date', name: 'posteddate', width: 100, classes: 'wrap',
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}
			},
			{ label: 'Alloc Date', name: 'allocdate', width: 100, classes: 'wrap',
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}
			},
			{ label: 'Document No', name: 'recptno', width: 100, classes: 'wrap' },
			{ label: 'Type', name: 'trantype', width: 30, classes: 'wrap' },
			{ label: 'Amount', name: 'refamount', width: 90, classes: 'wrap',
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
			{ label: 'O/S Amount', name: 'outamount', width: 90, align: 'right', classes: 'wrap', editable:false,	
				formatter: 'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editrules:{required: false},editoptions:{readonly: "readonly"},
			},
			{ label: 'Amount Paid', name: 'amount', width: 90, classes: 'wrap', 
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
			{ label: 'Balance', name: 'balance', width: 90, classes: 'wrap', hidden:false, 
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
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'entrydate',
		sortorder: "asc",
		pager: "#jqGridPagerArAlloc",
		loadComplete: function(data){
			// setjqgridHeight(data,'jqGridArAlloc');
			// calc_jq_height_onchange("jqGridArAlloc");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
	});
	jqgrid_label_align_right("#jqGridArAlloc");

	$("#jqGridArAlloc_panel").on("show.bs.collapse", function(){
		$("#jqGridArAlloc").jqGrid ('setGridWidth', Math.floor($("#jqGridArAlloc_c")[0].offsetWidth-$("#jqGridArAlloc_c")[0].offsetLeft-28));
	});

	//////////////////////////////////////////checkbox_jqgAlloc//////////////////////////////////////////
	function checkbox_jqgAlloc(cellvalue, options, rowObject){
		if(options.gid == "jqGridArAlloc"){
			return '';
		}else{
			return `<input class='checkbox_jqgAlloc' type="checkbox" name="checkbox" data-rowid="`+options.rowId+`">`;		
		}
	}

	////////////////////////////////////////////////////ordialog////////////////////////////////////////
	var dialog_deptcode = new ordialog(
		'db_deptcode', 'sysdb.department', "#jqGrid2 input[name='deptcode']", errorField,
		{
			colModel: [
				{ label: 'Sector Code', name: 'deptcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function(event){
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}
				$("#jqGrid2 #"+id_optid+"_GSTCode").focus().select();
			},
			loadComplete: function(data,obj){
				var searchfor = $("#jqGrid2 input#"+obj.id_optid+"_deptcode").val()
				var rows = data.rows;
				var gridname = '#'+obj.gridname;
				
				if(searchfor != undefined && rows.length > 1 && obj.ontabbing){
					rows.forEach(function(e,i){
						if(e.deptcode.toUpperCase() == searchfor.toUpperCase().trim()){
							let id = parseInt(i)+1;
							$(gridname+' tr#'+id).click();
							$(gridname+' tr#'+id).dblclick();
						}
					});
				}
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
		}, {
			title: "Select Department Code",
			open: function(){
				dialog_deptcode.urlParam.filterCol=['compcode','recstatus'];
				dialog_deptcode.urlParam.filterVal=['session.compcode','ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	dialog_deptcode.makedialog(false);

	var dialog_paymode = new ordialog(
		'paymodeAR','debtor.paymode',"#formdata input[name='db_paymode']",errorField,
		{
			colModel: [
				{ label:'Paymode',name:'paymode',width:200,classes:'pointer',canSearch:true,or_search:true },
				{ label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true },
				{ label:'Paytype',name:'paytype',width:200,classes:'pointer',hidden:true },
			],
			urlParam: {
				filterCol:['compcode','recstatus', 'source', 'paytype'],
				filterVal:['session.compcode','ACTIVE', 'AR', 'Debit Note']
			},
			ondblClickRow:function(){
				$('#db_reference').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#db_reference').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Paymode",
			open: function(){
				dialog_paymode.urlParam.filterCol=['compcode','recstatus', 'source', 'paytype'],
				dialog_paymode.urlParam.filterVal=['session.compcode','ACTIVE', 'AR', 'Debit Note'];
				}
			},'urlParam','radio','tab'
		);
	dialog_paymode.makedialog(true);

	// var dialog_category = new ordialog(
	// 	'category','material.category',"#jqGrid2 input[name='category']",errorField,
	// 	{
	// 		colModel: [
	// 			{ label:'Category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,or_search:true },
	// 			{ label:'Description',name:'description',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true },
	// 		],
	// 		urlParam: {
	// 			filterCol:['compcode','source', 'cattype', 'recstatus'],
	// 			filterVal:['session.compcode','PBDN', 'Other', 'ACTIVE']
	// 		},
	// 		ondblClickRow: function () {
	// 			$("#jqGrid2 input[name='document']").focus();
	// 		},
	// 		gridComplete: function(obj){
	// 			var gridname = '#'+obj.gridname;
	// 			if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
	// 				$(gridname+' tr#1').click();
	// 				$(gridname+' tr#1').dblclick();
	// 				$("#jqGrid2 input[name='document']").focus();
	// 			}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
	// 				$('#'+obj.dialogname).dialog('close');
	// 			}
	// 		}
	// 	},{
	// 		title:"Select Category",
	// 		open: function(){
	// 			dialog_category.urlParam.filterCol=['compcode','source', 'cattype', 'recstatus'],
	// 			dialog_category.urlParam.filterVal=['session.compcode','PBDN', 'Other', 'ACTIVE']
	// 		}
	// 	},'urlParam','radio','tab'
	// );
	// dialog_category.makedialog(true);

	var dialog_GSTCode = new ordialog(
		'GSTCode',['hisdb.taxmast'],"#jqGrid2 input[name='GSTCode']",errorField,
		{
			colModel: [
				{ label:'Tax code',name:'taxcode',width:200,classes:'pointer',canSearch:true,or_search:true },
				{ label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true },
				{ label:'Tax Rate',name:'rate',width:200,classes:'pointer' },
			],
			urlParam: {
				filterCol:['compcode','recstatus','taxtype'],
				filterVal:['session.compcode','ACTIVE','OUTPUT']
			},
			ondblClickRow:function(event){
				if(event.type == 'keydown'){
					var optid = $(event.currentTarget).get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}else{
					var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
					var id_optid = optid.substring(0,optid.search("_"));
				}
				let data=selrowData('#'+dialog_GSTCode.gridname);
				$("#jqGrid2 #"+id_optid+"_gstpercent").val(data['rate']);
				$("#jqGrid2 #"+id_optid+"_AmtB4GST").focus().select();
			},
			loadComplete: function(data,obj){
				var searchfor = $("#jqGrid2 input#"+obj.id_optid+"_GSTCode").val()
				var rows = data.rows;
				var gridname = '#'+obj.gridname;
				
				if(searchfor != undefined && rows.length > 1 && obj.ontabbing){
					rows.forEach(function(e,i){
						if(e.taxcode.toUpperCase() == searchfor.toUpperCase().trim()){
							let id = parseInt(i)+1;
							$(gridname+' tr#'+id).click();
							$(gridname+' tr#'+id).dblclick();
						}
					});
				}
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
			title: "Select Tax Code For Item",
			open: function(){
				dialog_GSTCode.urlParam.filterCol=['compcode','recstatus','taxtype'];
				dialog_GSTCode.urlParam.filterVal=['session.compcode','ACTIVE','OUTPUT'];
			},
			check_take_all_field:true,
			after_check: function(data,obj,id){
				var id_optid = id.substring(0,id.search("_"));
				if(data.rows.length>0 && !obj.ontabbing){
					$(id_optid+'_gstpercent').val(data.rows[0].rate);
					calculate_line_totgst_and_totamt2(id_optid);
					calc_jq_height_onchange("jqGrid2");
					$(id_optid+"_AmtB4GST").focus().select();
				}
			}
		},'urlParam','radio','tab'
	);
	dialog_GSTCode.makedialog(false);

	var dialog_CustomerDN = new ordialog(
		'customer', 'debtor.debtormast', '#db_debtorcode', errorField,
		{
			colModel: [
				{ label: 'Debtor Code', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true, checked: true },
			],
			urlParam: {
				filterCol: ['compcode','recstatus'],
				filterVal: ['session.compcode','ACTIVE']
			},
			ondblClickRow: function (){
				$('#db_entrydate').focus();
			},
			gridComplete: function (obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#db_entrydate').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		}, {
			title: "Select Customer",
			open: function (){
				dialog_CustomerDN.urlParam.filterCol = ['compcode','recstatus'];
				dialog_CustomerDN.urlParam.filterVal = ['session.compcode','ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	dialog_CustomerDN.makedialog();

	// function cari_gstpercent(id){
	// 	let data = $('#jqGrid2').jqGrid ('getRowData', id);
	// 	$("#jqGrid2 #"+id+"_pouom_gstpercent").val(data.rate);
	// }

	$("#jqGrid_selection").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid").jqGrid('getGridParam','colModel'),
		shrinkToFit: false,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		sortname: 'db_idno',
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
		if(selrowData('#jqGrid').db_recstatus.trim().toUpperCase() == 'CANCELLED'){
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

});

function populate_form(obj){
	//panel header
	$('#DebitNo_show').text(obj.db_auditno);
	$('#CustName_show').text(obj.dm_name);
}

if($('#scope').val().trim().toUpperCase() == 'CANCEL'){
	$('td#glyphicon-plus,td#glyphicon-edit').hide();
}else{
	$('td#glyphicon-plus,td#glyphicon-edit').show();
}

function empty_form(){
	$('#DebitNo_show').text('');
	$('#CustName_show').text('');
}

function reset_all_error(){

}

function get_billtype(){
	this.param={
		action:'get_value_default',
		url:"util/get_value_default",
		field: ['*'],
		filterCol:['compcode','billtype'],
		filterVal:['session.compcode',$("#formdata input[name='db_hdrtype']").val()],
		table_name:'hisdb.billtymst',
		table_id:'idno'
	}

	$.get( this.param.url+"?"+$.param(this.param), function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows)){
				let data_ = data.rows[0];
				var rowids = $('#jqGrid2').jqGrid('getDataIDs');
				rowids.forEach(function(e,i){
					$('#jqGrid2').jqGrid('setRowData', e, {amtbilltype:data_.percent_,percbilltype:data_.amount});
				});

				$('#pricebilltype').val(data_.price);
				$("#jqGrid2 input[name='percbilltype']").val(data_.percent_);
				$("#jqGrid2 input[name='amtbilltype']").val(data_.amount);
			}
		});
}

function calc_jq_height_onchange(jqgrid){
	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
	if(scrollHeight<80){
		scrollHeight = 80;
	}else if(scrollHeight>300){
		scrollHeight = 300;
	}
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight+1);
}
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

var mycurrency = new currencymode(['#amount', '#db_amount','#tot_alloc']);
var mycurrency2 =new currencymode(['#db_amount', '#db_outamount']);

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
				show_post_button(false);
				parent_close_disabled(true);
				actdateObj.getdata().set();
				$("#jqGrid2").jqGrid('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth - $("#jqGrid2_c")[0].offsetLeft));
				$("#jqGridAlloc").jqGrid('setGridWidth', Math.floor($("#jqGridAlloc_c")[0].offsetWidth - $("#jqGridAlloc_c")[0].offsetLeft));
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				switch (oper) {
					case state = 'add':
						$("#jqGridAlloc").jqGrid("clearGridData", false);
						$("#jqGrid2").jqGrid("clearGridData", true);
						$("#pg_jqGridPager2 table").show();
						hideatdialogForm(true);
						enableForm('#formdata');
						rdonly('#formdata');
						alloc_button_status('initial');
						//$("#purreqhd_reqdept").val($("#x").val());
						break;
					case state = 'edit':
						$("#pg_jqGridPager2 table").show();
						// $("#save_alloc").hide();
						hideatdialogForm(true);
						enableForm('#formdata');
						rdonly('#formdata');
						alloc_button_status('add');
						break;
					case state = 'view':
						disableForm('#formdata');
						$("#pg_jqGridPager2 table").hide();
						$("#jqGridPagerAlloc").hide();
						alloc_button_status('add');
						break;
				} if (oper != 'add') {
					refreshGrid("#jqGridAlloc",urlParamAlloc);
					dialog_CustomerSO.check(errorField);
					dialog_paymodeAR.check(errorField);
				} if (oper != 'view') {
					dialog_CustomerSO.on();
					dialog_paymodeAR.on();
				}
				init_jq(oper);
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
				show_post_button(false);
				addmore_jqgrid2.state = false;//reset balik
				addmore_jqgrid2.more = false;
				addmore_jqGrid3.state = false;
				addmore_jqGrid3.more = false;
			    //reset balik
			    parent_close_disabled(false);
				emptyFormdata(errorField, '#formdata');
				emptyFormdata(errorField, '#formdata2');
				$('.my-alert').detach();
				$("#formdata a").off();
				dialog_CustomerSO.off();
				dialog_paymodeAR.off();
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
		action: 'maintable',
		url:'CreditNoteAR/table',
		field:'',
		table_name: ['debtor.dbacthdr as db','debtor.debtormast as dm'],
		table_id: 'idno',
		join_type: ['LEFT JOIN'],
		join_onCol: ['db.debtorcode'],
		join_onVal: ['dm.debtorcode'],
		filterCol: ['source','trantype'],
		filterVal: ['PB','CN'],
		// WhereInCol:['purreqhd.recstatus'],
		// WhereInVal: recstatus_filter,
		fixPost: true,
	}

	var saveParam = {
		action: 'CreditNoteAR_header_save',
		url:'./CreditNoteAR/form',
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
			// { label: 'Payer Code', name: 'db_payercode', width: 15, canSearch: true, classes: 'wrap', formatter: showdetail,unformat: unformat_showdetail },
			{ label: 'Debtor Code', name: 'db_debtorcode', width: 15, canSearch: true, classes: 'wrap' },
			{ label: 'Name', name: 'dm_name', width: 50, classes: 'wrap' },
			{ label: 'Date', name: 'db_entrydate', width: 15, canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'Credit No', name: 'db_auditno', width: 12, align: 'right', canSearch: true },
			{ label: 'Sector', name: 'db_unit', width: 15, hidden: true, classes: 'wrap' },
			{ label: 'PO No', name: 'db_ponum', width: 10, hidden: true },
			{ label: 'Amount', name: 'db_amount', width: 15, align: 'right', formatter: 'currency' },
			{ label: 'Outstanding Amount', name: 'db_outamount', width: 15, align: 'right', formatter: 'currency' },
			{ label: 'Paymode', name: 'db_paymode', width: 25, classes: 'wrap text-uppercase', formatter: showdetail, unformat:un_showdetail },
			{ label: 'Status', name: 'db_recstatus', width: 15 },
			{ label: 'Remark', name: 'db_remark', width: 20, classes: 'wrap', hidden: true },
			{ label: 'source', name: 'db_source', width: 10, hidden: true },
			{ label: 'Trantype', name: 'db_trantype', width: 10, hidden: true },
			{ label: 'lineno_', name: 'db_lineno_', width: 20, hidden: true },
			{ label: 'db_orderno', name: 'db_orderno', width: 10, hidden: true },
			{ label: 'outamount', name: 'db_outamount', width: 20, hidden: true },
			{ label: 'debtortype', name: 'db_debtortype', width: 20, hidden: true },
			{ label: 'billdebtor', name: 'db_billdebtor', width: 20, hidden: true },
			{ label: 'approvedby', name: 'db_approvedby', width: 20, hidden: true },
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
			{ label: 'remarks', name: 'db_remark', width: 10, hidden: true },
			{ label: ' ', name: 'Checkbox',sortable:false, width: 10,align: "center", formatter: formatterCheckbox },
			{ label: 'Reference', name: 'db_reference', width: 10, hidden: true },
			{ label: 'Pay Mode', name: 'db_paymode', width: 10, hidden: true },
			// { label: 'unallocated', name: 'unallocated', width: 50, classes: 'wrap', hidden: true },
			{ label: 'db_unallocated', name: 'db_unallocated', width: 50, classes: 'wrap', hidden: true },
		],
		autowidth: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		sortname:'db_idno',
		sortorder:'desc',
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow: function (rowid, selected) {
			$('#posted_button').hide();
			$('#error_infront').text('');
			let stat = selrowData("#jqGrid").db_recstatus;
			let scope = $("#recstatus_use").val();
			
			if(rowid != null) {
				var rowData = $('#jqGrid').jqGrid('getRowData', rowid);
				refreshGrid('#jqGridAlloc', urlParamAlloc,'kosongkan');
				// $("#pg_jqGridPager3 table").hide();
				// $("#pg_jqGridPager2 table").show();
			}
			
			urlParam2.source = selrowData("#jqGrid").db_source;
			urlParam2.trantype = selrowData("#jqGrid").db_trantype;
			urlParam2.auditno = selrowData("#jqGrid").db_auditno;
			
			urlParamAlloc.source=selrowData("#jqGrid").db_source;
			urlParamAlloc.trantype=selrowData("#jqGrid").db_trantype;
			urlParamAlloc.auditno=selrowData("#jqGrid").db_auditno;
			refreshGrid("#jqGridArAlloc",urlParamAlloc);
			
			$('#reqnodepan').text(selrowData("#jqGrid").purreqhd_purreqno);//tukar kat depan tu
			$('#reqdeptdepan').text(selrowData("#jqGrid").purreqhd_reqdept);
			refreshGrid("#jqGrid3", urlParam2);
			populate_form(selrowData("#jqGrid"));
			
			$("#pdfgen1").attr('href','./CreditNoteAR/showpdf?auditno='+selrowData("#jqGrid").db_auditno);
			if_cancel_hide();
		},
		ondblClickRow: function (rowid, iRow, iCol, e) {
			let stat = selrowData("#jqGrid").db_recstatus;
			$('#tot_alloc').val(parseFloat(selrowData("#jqGrid").db_amount) - parseFloat(selrowData("#jqGrid").db_outamount));
			mycurrency.formatOn();
			if(stat=='OPEN' || stat=='INCOMPLETED'){
				$("#jqGridPager td[title='Edit Selected Row']").click();
			}else{
				$("#jqGridPager td[title='View Selected Row']").click();
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
			
			if($('#jqGrid').data('inputfocus') == 'customer_search'){
				$("#customer_search").focus();
				$('#jqGrid').data('inputfocus','');
				$('#customer_search_hb').text('');
				removeValidationClass(['#customer_search']);
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
			refreshGrid("#jqGridAlloc",urlParamAlloc);
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
			refreshGrid("#jqGridAlloc",urlParamAlloc);
			
			// if(selrowData('#jqGrid').db_unallocated == '1'){
			// 	populate_alloc_table();
			// }
			
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
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#saveDetailLabel,#save_alloc").hide();
			$("#jqGridPager2SaveAll,#jqGridPager2CancelAll").show();
		}else if(hide){
			$("#jqGrid2_iledit,#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll,#jqGridPager2SaveAll,#jqGridPager2CancelAll").hide();
			$("#saveDetailLabel,#save_alloc").show();
		}else{
			$("#jqGrid2_iladd,#jqGrid2_ilcancel,#jqGrid2_ilsave,#saveHeaderLabel,#jqGridPager2Delete,#jqGridPager2EditAll").show();
			$("#saveDetailLabel,#jqGridPager2SaveAll,#jqGrid2_iledit,#jqGridPager2CancelAll,#save_alloc").hide();
		}
	}

	$('#db_unallocated').on('change', function() {
		init_jq2(oper);
	});

	///////////////////////////////// trandate check date validate from period////////// ////////////////
	// var actdateObj = new setactdate(["#trandate"]);
	// actdateObj.getdata().set();

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
		
		$.post( 'CreditNoteAR/form', obj , function( data ) {
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
		
		$.post(  './CreditNoteAR/form', obj , function( data ) {
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
	function saveHeader(form, selfoper, saveParam, obj, needrefresh){
		myfail_msg.clear_fail();
		if (obj == null) {
			obj = {};
		}
		saveParam.oper = selfoper;
		
		if($('#db_unallocated').val() == '0'){
			obj.unallocated = true;
		}else{
			let data_detail = $('#jqGridAlloc').jqGrid('getRowData');
			obj.data_detail = data_detail;
			obj.unallocated = false;
		}
		
		$.post( saveParam.url+"?"+$.param(saveParam), $( form ).serialize()+'&'+ $.param(obj) , function( data ) {
			
		},'json').fail(function (data) {
			// alert(data.responseText);
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
			}else{
				show_post_button();
			}
			
			if (selfoper == 'add') {
				oper = 'edit';//sekali dia add terus jadi edit lepas tu
				
				$('#db_idno').val(data.idno);
				$('#db_auditno').val(data.auditno);
				$('#db_amount').val(data.totalAmount);
				
				urlParam2.auditno = data.auditno;
			} else if (selfoper == 'edit') {
				urlParam2.auditno = $('#db_auditno').val();
				//doesnt need to do anything
			}
			
			disableForm('#formdata');
			
			if(needrefresh === 'refreshGrid'){
				refreshGrid("#jqGrid", urlParam);
			}
			populate_alloc_table_save();
		})
	}

	$("#dialogForm").on('change keypress', '#formdata :input', '#formdata :textarea', function () {
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	$("#dialogForm").on('click','#formdata a.input-group-addon',function(){
		unsaved = true; //kalu dia change apa2 bagi prompt
	});

	////////////////////////////changing status and trigger search////////////////////////////
	$('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	$('#docudate_search').on('click', searchDate);

	function whenchangetodate() {
		urlParam.fromdate=urlParam.todate=null;
		customer_search.off();
		$('#customer_search, #docudate_from, #docudate_to').val('');
		$('#customer_search_hb').text('');
		$("input[name='Stext'],#docudate_text,#customer_text").hide();
		removeValidationClass(['#customer_search']);
		if ($('#Scol').val() == 'db_entrydate'){
			$("#docudate_text").show();
		}else if($('#Scol').val() == 'db_debtorcode'){
			$("#customer_text").show("fast");
			customer_search.on();
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
			searchClick2('#jqGrid', '#searchForm', urlParam);
		});
	}

	function searchDate(){
		urlParam.fromdate = $('#docudate_from').val();
		urlParam.todate = $('#docudate_to').val();
		refreshGrid('#jqGrid',urlParam);
	}

	function searchChange(){
		cbselect.empty_sel_tbl();
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
		urlParam.WhereInCol = null;
		urlParam.WhereInVal = null;
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
				customer_search.urlParam.filterCol = ['compcode','recstatus'];
				customer_search.urlParam.filterVal = ['session.compcode','ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	customer_search.makedialog(true);
	$('#customer_search').on('keyup',ifnullsearch);
	
	function ifnullsearch(){
		if($('#customer_search').val() == ''){
			urlParam.searchCol=[];
			urlParam.searchVal=[];
			$('#jqGrid').data('inputfocus','customer_search');
			refreshGrid('#jqGrid', urlParam);
		}
	}
	
	resizeColumnHeader = function () {
        var rowHight, resizeSpanHeight,
        // get the header row which contains
        headerRow = $(this).closest("div.ui-jqgrid-view")
            .find("table.ui-jqgrid-htable>thead>tr.ui-jqgrid-labels");

        // reset column height
        headerRow.find("span.ui-jqgrid-resize").each(function () {
            this.style.height = "";
        });

        // increase the height of the resizing span
        resizeSpanHeight = "height: " + headerRow.height() + "px !important; cursor: col-resize;";
        headerRow.find("span.ui-jqgrid-resize").each(function () {
            this.style.cssText = resizeSpanHeight;
        });

        // set position of the dive with the column header text to the middle
        rowHight = headerRow.height();
        headerRow.find("div.ui-jqgrid-sortable").each(function () {
            var ts = $(this);
            ts.css("top", (rowHight - ts.outerHeight()) / 2 + "px");
        });
    },
    fixPositionsOfFrozenDivs = function () {
        var $rows;
        if (typeof this.grid.fbDiv !== "undefined") {
            $rows = $(">div>table.ui-jqgrid-btable>tbody>tr", this.grid.bDiv);
            $(">table.ui-jqgrid-btable>tbody>tr", this.grid.fbDiv).each(function (i) {
                var rowHight = $($rows[i]).height(), rowHightFrozen = $(this).height();
                if ($(this).hasClass("jqgrow")) {
                    $(this).height(rowHight);
                    rowHightFrozen = $(this).height();
                    if (rowHight !== rowHightFrozen) {
                        $(this).height(rowHight + (rowHight - rowHightFrozen));
                    }
                }
            });
            $(this.grid.fbDiv).height(this.grid.bDiv.clientHeight);
            $(this.grid.fbDiv).css($(this.grid.bDiv).position());
        }
        if (typeof this.grid.fhDiv !== "undefined") {
            $rows = $(">div>table.ui-jqgrid-htable>thead>tr", this.grid.hDiv);
            $(">table.ui-jqgrid-htable>thead>tr", this.grid.fhDiv).each(function (i) {
                var rowHight = $($rows[i]).height(), rowHightFrozen = $(this).height();
                $(this).height(rowHight);
                rowHightFrozen = $(this).height();
                if (rowHight !== rowHightFrozen) {
                    $(this).height(rowHight + (rowHight - rowHightFrozen));
                }
            });
            $(this.grid.fhDiv).height(this.grid.hDiv.clientHeight);
            $(this.grid.fhDiv).css($(this.grid.hDiv).position());
        }
    },
    fixGboxHeight = function () {
        var gviewHeight = $("#gview_" + $.jgrid.jqID(this.id)).outerHeight(),
            pagerHeight = $(this.p.pager).outerHeight();

        $("#gbox_" + $.jgrid.jqID(this.id)).height(gviewHeight + pagerHeight);
        gviewHeight = $("#gview_" + $.jgrid.jqID(this.id)).outerHeight();
        pagerHeight = $(this.p.pager).outerHeight();
        $("#gbox_" + $.jgrid.jqID(this.id)).height(gviewHeight + pagerHeight);
    }

	/////////////////////parameter for jqgrid2 url///////////////////////////////////////////////////////
	var urlParam2 = {
		action: 'get_table_dtl',
		url:'CreditNoteARDetail/table',
		source:'PB',
		trantype:'CN',
		auditno:'',
	};

	var addmore_jqgrid2={more:false,state:false,edit:false} // if addmore is true, add after refresh jqgrid2, state true kalu kosong

	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2").jqGrid({
		datatype: "local",
		editurl: "./CreditNoteARDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'AuditNo', name: 'auditno', hidden: true },
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true, editable:true },
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true, editable:true },
			{ label: 'Department', name: 'deptcode', width: 150, classes: 'wrap', canSearch: true, editable: true,
				editrules:{required: true,custom:true, custom_func:cust_rules},
				formatter: showdetail,
				edittype:'custom',	editoptions:
					{
						custom_element:deptcodeCustomEdit,
						custom_value:galGridCustomValue
					},
			},
			{ label: 'GST Code', name: 'GSTCode', width: 90, classes: 'wrap', editable: true,
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
			dialog_CustomerSO.check(errorField);
			dialog_approvedbySO.check(errorField);
		}
		
		}).bind("jqGridLoadComplete jqGridInlineEditRow jqGridAfterEditCell jqGridAfterRestoreCell jqGridInlineAfterRestoreRow jqGridAfterSaveCell jqGridInlineAfterSaveRow", function () {
        fixPositionsOfFrozenDivs.call(this);
    });
	fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);

	$("#jqGrid2").jqGrid('bindKeys');
	var updwnkey_fld;
	function updwnkey_func(event){
		var optid = event.currentTarget.id;
		var fieldname = optid.substring(optid.search("_"));
		updwnkey_fld = fieldname;
	}

	$("#jqGrid2").keydown(function(e) {
		switch (e.which) {
			case 40: // down
				var $grid = $(this);
				var selectedRowId = $grid.jqGrid('getGridParam', 'selrow');
				$("#"+selectedRowId+updwnkey_fld).focus();
				
				e.preventDefault();
				break;
			
			case 38: // up
				var $grid = $(this);
				var selectedRowId = $grid.jqGrid('getGridParam', 'selrow');
				$("#"+selectedRowId+updwnkey_fld).focus();
				
				e.preventDefault();
				break;
			
			default:
				return;
		}
	});

	// $("#jqGrid2").jqGrid('setGroupHeaders', {
	// 	useColSpanStyle: false,
	// 	groupHeaders: [
	// 		{ startColumnName: 'description', numberOfColumns: 1, titleText: 'Item' },
	// 		// { startColumnName: 'pricecode', numberOfColumns: 2, titleText: 'Item' },
	//   	]
	// });

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
			if(recstatus != 'POSTED'){
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
		extraparam: {
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			$("#jqGrid2").setSelection($("#jqGrid2").getDataIDs()[0]);
			errorField.length=0;
			$("#jqGrid2 input[name='deptcode']").focus().select();
			$("#jqGridPager2EditAll,#saveHeaderLabel,#jqGridPager2Delete").hide();
			
			dialog_deptcode.on();
			dialog_category.on();
			dialog_GSTCode.on();
			
			unsaved = false;
			mycurrency2.array.length = 0;
			Array.prototype.push.apply(mycurrency2.array, ["#jqGrid2 input[name='AmtB4GST']","#jqGrid2 input[name='tot_gst']","#jqGrid2 input[name='amount']"]);
			
			mycurrency2.formatOnBlur();//make field to currency on leave cursor
			
			$("#jqGrid2 input[name='amount'],#jqGrid2 input[name='AmtB4GST']").on('blur',{currency: mycurrency2},calculate_line_totgst_and_totamt);
			
			$("input[name='amount']").keydown(function(e) {//when click tab at amount, auto save
				var code = e.keyCode || e.which;
				if (code == '9')$('#jqGrid2_ilsave').click();
			});
		},
		aftersavefunc: function (rowid, response, options) {
			$('#db_amount, #db_outamount').val(response.responseText);
			show_post_button();
			// $('#db_unit').val(response.responseText);
			if(addmore_jqgrid2.state == true)addmore_jqgrid2.more=true; //only addmore after save inline
			//state true maksudnyer ada isi, tak kosong
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
			
			if(parseFloat($('#jqGrid2 input[name="amount"]').val()) == 0){
				myerrorIt_only('#jqGrid2 input[name="amount"]');
				alert('Amount cant be 0');
				return false;
			}
			
			let data = $('#jqGrid2').jqGrid ('getRowData', rowid);
			
			let editurl = "./CreditNoteARDetail/form?"+
				$.param({
					action: 'CreditNoteAR_detail_save',
					idno: $('#db_idno').val(),
					auditno:$('#db_auditno').val(),
					amount:data.amount,
					lineno_:data.lineno_,
				});
			$("#jqGrid2").jqGrid('setGridParam', { editurl: editurl });
		},
		afterrestorefunc : function( response ) {
			errorField.length=0;
			delay(function(){
				fixPositionsOfFrozenDivs.call($('#jqGrid2')[0]);
			}, 500 );
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
								action: 'CreditNoteAR_detail_save',
								source: $('#db_source').val(),
								trantype: $('#db_trantype').val(),
								auditno: $('#db_auditno').val(),
								lineno_: selrowData('#jqGrid2').lineno_,
								idno: selrowData('#jqGrid2').idno,
							}
							$.post( "./CreditNoteARDetail/form?"+$.param(param),{oper:'del',"_token": $("#_token").val()}, 
							function( data ){
							}).fail(function (data) {
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data) {
								$('#db_amount, #db_outamount').val(data);
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
			
			var ids = $("#jqGrid2").jqGrid('getDataIDs');
			for (var i = 0; i < ids.length; i++) {
				$("#jqGrid2").jqGrid('editRow',ids[i]);
				
				Array.prototype.push.apply(mycurrency2.array, ["#"+ids[i]+"_AmtB4GST","#"+ids[i]+"_tot_gst","#"+ids[i]+"_amount"]);
				
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
				
				if(parseFloat($("#jqGrid2 input#"+ids[i]+"_amount").val()) == 0){
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
				action: 'CreditNoteAR_detail_save',
				_token: $("#_token").val(),
				auditno: $('#db_auditno').val(),
				source: $('#db_source').val(),
				trantype: $('#db_trantype').val(),
				idno: $('#db_idno').val()
			}
			
			$.post( "/CreditNoteARDetail/form?"+$.param(param),{oper:'edit_all',dataobj:jqgrid2_data}, function( data ){
			}).fail(function(data) {
				// alert(dialog,data.responseText);
			}).done(function(data){
				if($("#db_unallocated").find(":selected").text() == 'Credit Note'){
					show_post_button();
				}
				$('#db_amount, #db_outamount').val(data);
				
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
			case 'deptcode':field=['deptcode','description'];table="sysdb.department";break;
			case 'category':field=['catcode','description'];table="material.category";break;
			case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";case_='GSTCode';break;
			
			// jqGrid
			case 'db_paymode':field=['paymode','description'];table="debtor.paymode";case_='db_paymode';break;
			
			case 'debtorcode':field=['debtorcode','name'];table="debtor.debtormast";case_='debtorcode';break;
			case 'chggroup':field=['chgcode','description'];table="hisdb.chgmast";case_='chggroup';break;
			case 'uom':field=['uomcode','description'];table="material.uom";case_='uom';break;
			// case 'db_payercode':field=['debtormast','name'];table="debtor.debtormast";case_='db_payercode';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
		
		fdl.get_array('CreditNoteAR',options,param,case_,cellvalue);
		
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

	///////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value, name) {
		var temp=null;
		switch (name) {
			case 'Department':temp=$('#deptcode');break;
			case 'Category':temp=$('#category');break;
			
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

	function categoryCustomEdit(val, opt) {
		val = getEditVal(val);
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="category" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	}

	function GSTCodeCustomEdit(val, opt) {
		val = (val.slice(0, val.search("[<]")) == "undefined") ? "" : val.slice(0, val.search("[<]"));
		var id_optid = opt.id.substring(0,opt.id.search("_"));
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="GSTCode" type="text" class="form-control input-sm text-uppercase" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a><input id="'+id_optid+'_gstpercent" name="gstpercent" type="hidden"></div><span class="help-block"></span>');
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
			dialog_CustomerSO.on();
			dialog_paymodeAR.on();
			//dialog_mrn.on();
		}
	});

	//////////////////////////////////////////saveHeaderLabel////////////////////////////////////////////
	$("#saveHeaderLabel").click(function () {
		show_post_button(false);
		emptyFormdata(errorField, '#formdata2');
		hideatdialogForm(true);
		dialog_CustomerSO.on();
		dialog_paymodeAR.on();
		
		enableForm('#formdata');
		rdonly('#formdata');
		$(".noti").empty();
		refreshGrid("#jqGrid2", urlParam2);
		refreshGrid("#jqGridAlloc",urlParamAlloc);
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
	function calculate_line_totgst_and_totamt(event){
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
			$('#db_amount, #db_outamount').val(numeral(totamt).format('0,0.00'));
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
	}).bind("jqGridLoadComplete jqGridInlineEditRow jqGridAfterEditCell jqGridAfterRestoreCell jqGridInlineAfterRestoreRow jqGridAfterSaveCell jqGridInlineAfterSaveRow", function () {
        fixPositionsOfFrozenDivs.call(this);
    });
	fixPositionsOfFrozenDivs.call($('#jqGrid3')[0]);
	$("#jqGrid3").jqGrid("setFrozenColumns");
	jqgrid_label_align_right("#jqGrid3");

	////////////////////////////////////////////////parameter for jqGridAlloc url////////////////////////////////////////////////
	var urlParamAlloc={
		action: 'get_alloc_table',
		url:'CreditNoteAR/table',
		source:'',
		trantype:'',
		auditno:'',
	};

	var addmore_jqGrid3={more:false,state:false,edit:true} // if addmore is true, add after refresh jqGridAlloc, state true kalau kosong

	////////////////////////////////////////////////jqGridAlloc////////////////////////////////////////////////
	$("#jqGridAlloc").jqGrid({
		datatype: "local",
		editurl: "./CreditNoteARDetail/form",
		colModel: [
			{ label: ' ', name: 'checkbox', width: 15, formatter: checkbox_jqgAlloc},
			{ label: 'Debtor', name: 'debtorcode', width: 100, classes: 'wrap', formatter: showdetail,unformat:un_showdetail },
			{ label: 'Document Date', name: 'entrydate', width: 100, classes: 'wrap',
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}
			},
			{ label: 'Posted Date', name: 'posteddate', width: 100, classes: 'wrap',
				formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}
			},
			{ label: 'Document No', name: 'recptno', width: 100, classes: 'wrap' },
			{ label: 'Type', name: 'trantype', width: 50, classes: 'wrap' },
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
			{ label: 'can_alloc', name: 'can_alloc', width: 20, classes: 'wrap', hidden:true },
			{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true, key: true },
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'entrydate',
		sortorder: "asc",
		pager: "#jqGridPagerAlloc",
		loadComplete: function(data){
			if(addmore_jqGrid3.more == true){$('#jqGridAlloc_iladd').click();}
			else{
				$('#jqGridAlloc').jqGrid('setSelection', "1");
			}
			
			addmore_jqGrid3.edit = true;
			addmore_jqGrid3.more = false; //reset
			
			// calc_jq_height_onchange("jqGridAlloc");
			// setjqgridHeight(data,'jqGridAlloc');
		},
		gridComplete: function(){
			fdl.set_array().reset();
			if($('#db_recstatus').val() == 'POSTED' && oper == 'edit'){
				calc_amtpaid_bal();
			}
			
			unsaved = false;
			var ids = $("#jqGridAlloc").jqGrid('getDataIDs');
			var result = ids.filter(function(text){
								if(text.search("jqg") != -1)return false;return true;
							});
			if(result.length == 0 && oper=='edit')unsaved = true;
		},
		beforeSubmit: function(postdata, rowid){
			/*dialog_suppcode.check(errorField);
			dialog_payto.check(errorField);
			dialog_category.check(errorField);*/
		}
	});

	////////////////////////////////////////////////set label jqGrid2 right////////////////////////////////////////////////
	addParamField('#jqGridAlloc',false,urlParam2,['checkbox','balance'])
	jqgrid_label_align_right("#jqGridAlloc");

	function checkbox_jqgAlloc(cellvalue, options, rowObject){
		if(options.gid == "jqGridArAlloc"){
			return '';
		}else{
			if(parseFloat(rowObject.amount) > 0){
				return '';
			}else{
				return `<input class='checkbox_jqgAlloc' type="checkbox" name="checkbox" data-rowid="`+options.rowId+`">`;	
			}	
		}
	}

	////////////////////////////////////////////////myEditOptions_alloc////////////////////////////////////////////////
	var myEditOptions_alloc = {
		keys: true,
		extraparam:{
			"_token": $("#_token").val()
		},
		oneditfunc: function (rowid) {
			// $("#jqGridPagerAllocEditAll,#saveHeaderLabel,#jqGridPagerAllocDelete").hide();
		},
		aftersavefunc: function (rowid, response, options) {
			// $('#apacthdr_outamount').val(response.responseText);
			if(addmore_jqGrid3.state==true)addmore_jqGrid3.more=true; //only addmore after save inline
			refreshGrid('#jqGridAlloc',urlParamAlloc,'save_alloc');
			$("#jqGridPagerAllocEditAll,#jqGridPagerAllocDelete").show();
		},
		errorfunc: function(rowid,response){
			alert(response.responseText);
			refreshGrid('#jqGridAlloc',urlParamAlloc,'save_alloc');
			$("#jqGridPagerAllocDelete").show();
		},
		beforeSaveRow: function(options, rowid) {
			// if(errorField.length>0)return false;
			mycurrency2.formatOff();
			let data = $('#jqGridAlloc').jqGrid ('getRowData', rowid);
			let editurl = "./CreditNoteARDetail/form?"+
				$.param({
					action: 'CreditNoteAR_detail_save',
					idno: $('#db_idno').val(),
					auditno:$('#db_auditno').val(),
					amount:data.amount,
				});
			$("#jqGridAlloc").jqGrid('setGridParam',{editurl:editurl});
		},
		afterrestorefunc : function( response ) {
			hideatdialogForm(false);
		}
	};

    ////////////////////////////////////////////////pager jqGridAlloc////////////////////////////////////////////////
	$("#jqGridAlloc").inlineNav('#jqGridPagerAlloc',{
		add: false, edit: false, cancel: false, save: false,
		// to prevent the row being edited/added from being automatically cancelled once the user clicks another row
		restoreAfterSelect: false,
		addParams: {
			addRowParams: myEditOptions_alloc
		},
		editParams: myEditOptions_alloc
	}).jqGrid('navButtonAdd', "#jqGridPagerAlloc", {
		id: "delete_alloc",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-trash",
		title: "Delete Selected Row",
		onClickButton: function (){
			selRowId = $("#jqGridAlloc").jqGrid('getGridParam', 'selrow');
			if(!selRowId){
				bootbox.alert('Please select row');
			}else{
				bootbox.confirm({
					message: "Are you sure you want to delete this row?",
					buttons: {
						confirm: { label: 'Yes', className: 'btn-success', }, cancel: { label: 'No', className: 'btn-danger' }
					},
					callback: function (result){
						if(result == true){
							param = {
								_token: $("#_token").val(),
								action: 'CreditNoteAR_save',
								source: selrowData('#jqGridAlloc').source,
								trantype: selrowData('#jqGridAlloc').trantype,
								auditno: selrowData('#jqGridAlloc').auditno,
								lineno_: selrowData('#jqGridAlloc').lineno_,
								idno: selrowData('#jqGridAlloc').idno,
								db_outamount: selrowData('#jqGrid').db_outamount,
							}
							$.post("./CreditNoteAR/form?"+$.param(param),{oper:'del_alloc',"_token": $("#_token").val()},
							function (data){
							},'json').fail(function (data){
								//////////////////errorText(dialog,data.responseText);
							}).done(function (data){
								mycurrency.formatOff();
								$('#db_outamount').val(data.outamount_hdr);
								$('#tot_alloc').val(parseFloat($('#db_amount').val()) - parseFloat($('#db_outamount').val()));
								refreshGrid("#jqGridAlloc",urlParamAlloc);
							});
						}else{
							// $("#jqGridPagerAllocEditAll").show();
						}
					}
				});
			}
		},
	}).jqGrid('navButtonAdd',"#jqGridPagerAlloc",{
		id: "add_alloc",
		caption:"Add",cursor: "pointer",position: "last",
		buttonicon:"",
		title:"Add Alloc"
	}).jqGrid('navButtonAdd',"#jqGridPagerAlloc",{
		id: "save_alloc",
		caption:"Save",cursor: "pointer",position: "last",
		buttonicon:"",
		title:"Save Alloc"
	}).jqGrid('navButtonAdd',"#jqGridPagerAlloc",{
		id: "cancel_alloc",
		caption:"Cancel",cursor: "pointer",position: "last", 
		buttonicon:"",
		title:"Cancel",
		onClickButton: function(){
			refreshGrid("#jqGridAlloc",urlParamAlloc);
			alloc_button_status('add');
		}
	});
	
	alloc_button_status('initial');
	function alloc_button_status(status){
		switch(status){
			case 'initial':
					$('#jqGridPagerAlloc #delete_alloc,#jqGridPagerAlloc #add_alloc,#jqGridPagerAlloc #save_alloc').hide();
					break;
			case 'wait':
					$('#jqGridPagerAlloc #delete_alloc,#jqGridPagerAlloc #add_alloc').hide();
					$('#jqGridPagerAlloc #save_alloc,#jqGridPagerAlloc #cancel_alloc').show();
					break;
			case 'add':
					$('#jqGridPagerAlloc #save_alloc,#jqGridPagerAlloc #cancel_alloc').hide();
					$('#jqGridPagerAlloc #delete_alloc,#jqGridPagerAlloc #add_alloc').show();
					break;
		}
	}

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
			{ label: 'can_alloc', name: 'can_alloc', width: 20, classes: 'wrap', hidden:true },
			{ label: 'idno', name: 'idno', width: 20, classes: 'wrap', hidden:true, key: true },
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
	
	////////////////////////////////////////////////add_alloc////////////////////////////////////////////////
	$("#add_alloc").click(function(){
		alloc_button_status('wait');
		populate_alloc_table();
	});
	
	////////////////////////////////////////////////save_alloc////////////////////////////////////////////////
	$("#save_alloc").click(function(){
		mycurrency.formatOff();
		mycurrency2.formatOff();
		// mycurrency.check0value(errorField);
		// unsaved = false;
		
		// if(checkdate(true) && $('#formdata').isValid({requiredFields: ''}, conf, true) ) {
		// 	saveHeader("#formdata",oper,saveParam,{idno:$('#idno').val()});
		// 	unsaved = false;
		// 	errorField.length=0;
		// 	// $("#dialogForm").dialog('close');
		// }else{
		// 	mycurrency.formatOn();
		// }
		
		// var totamt = $('#db_amount').val();
		// var allocamt = 0;
		// $('#jqGridAlloc input[name=amount]').each(function(i, obj) {
		// 	var thisamt = $(this).val().trim();
		// 	allocamt = parseFloat(allocamt) + parseFloat(thisamt);
		// });
		
		if(parseFloat($('#db_outamount').val().trim()) < 0){
			alert('Allocate amount cant exceed total amount');
		}else{
			var param={
				url: './CreditNoteAR/form',
				oper: 'save_alloc',
				idno: $('#db_idno').val()
			}
			var obj={
				_token : $('#_token').val(),
				data_detail: $('#jqGridAlloc').jqGrid('getRowData')
			}
			
			$.post( param.url+"?"+$.param(param),obj, function( data ) {
			
			},'json').fail(function(data) {
			}).success(function(data){
				$("#dialogForm").dialog('close');
			});
		}
	});
	
	////////////////////////////////////////////////posted_button////////////////////////////////////////////////
	$("#posted_button").click(function(){
		var param={
			url: './CreditNoteAR/form',
			oper: 'posted_single',
			idno: $('#db_idno').val()
		}
		
		$.get( param.url+"?"+$.param(param), function( data ) {
		
		},'json').done(function(data) {
			$('#db_recstatus').val('POSTED');
			disableForm('#formdata');
			disable_gridpager('#jqGridPager2');
			show_post_button(false);
			$("#jqGrid2_ilcancel").click();
			$("#jqGridAlloc input[name='checkbox']").show();
			populate_alloc_table();
			
			// var ids = $("#jqGridAlloc").jqGrid('getDataIDs');
			// for (var i = 0; i < ids.length; i++) {
			// 	$("#jqGridAlloc").jqGrid('editRow',ids[i]);
				
			// 	$('#jqGridAlloc input#'+ids[i]+'_amount').on('keyup',{rowid:ids[i]},calc_amtpaid);
			// }
			alloc_button_status('wait');
		});
	});

	////////////////////////////////////////////////////ordialog////////////////////////////////////////
	var dialog_paymodeAR = new ordialog(
		'paymodeAR','debtor.paymode',"#formdata input[name='db_paymode']",errorField,
		{
			colModel: [
				{ label:'Paymode',name:'paymode',width:200,classes:'pointer',canSearch:true,or_search:true },
				{ label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true },
				{ label:'Paytype',name:'paytype',width:200,classes:'pointer',hidden:true },
			],
			urlParam: {
				filterCol:['compcode','recstatus', 'source', 'paytype'],
				filterVal:['session.compcode','ACTIVE', 'AR', 'Credit Note']
			},
			ondblClickRow:function(){
				$('#db_remark').focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$('#db_remark').focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Paymode",
			open: function(){
				dialog_paymodeAR.urlParam.filterCol=['compcode','recstatus', 'source', 'paytype'],
				dialog_paymodeAR.urlParam.filterVal=['session.compcode','ACTIVE', 'AR', 'Credit Note'];
				}
			},'urlParam','radio','tab'
		);
	dialog_paymodeAR.makedialog(true);

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
			ondblClickRow: function(event) {
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

	var dialog_category = new ordialog(
		'category','material.category',"#jqGrid2 input[name='category']",errorField,
		{
			colModel: [
				{ label:'Category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,or_search:true },
				{ label:'Description',name:'description',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true },
			],
			urlParam: {
				filterCol:['compcode','source', 'cattype', 'recstatus'],
				filterVal:['session.compcode','PBCN', 'Other', 'ACTIVE']
			},
			ondblClickRow: function () {
				$("#jqGrid2 input[name='document']").focus();
			},
			gridComplete: function(obj){
				var gridname = '#'+obj.gridname;
				if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
					$(gridname+' tr#1').click();
					$(gridname+' tr#1').dblclick();
					$("#jqGrid2 input[name='document']").focus();
				}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
					$('#'+obj.dialogname).dialog('close');
				}
			}
		},{
			title:"Select Category",
			open: function(){
				dialog_category.urlParam.filterCol=['compcode','source', 'cattype', 'recstatus'],
				dialog_category.urlParam.filterVal=['session.compcode','PBCN', 'Other', 'ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_category.makedialog(true);

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
			title:"Select Tax Code For Item",
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

	// var dialog_GSTCode = new ordialog(
	// 	'GSTCode','hisdb.taxmast',"#jqGrid2 input[name='GSTCode']",errorField,
	// 	{
	// 		colModel: [
	// 			{ label:'Taxcode',name:'taxcode',width:200,classes:'pointer',canSearch:true,or_search:true },
	// 			{ label:'Description',name:'description',width:400,classes:'pointer',checked:true,canSearch:true,or_search:true },
	// 			{ label:'Tax Type',name:'taxtype',width:200,classes:'pointer', hidden:true },
	// 			{ label:'Rate',name:'rate',width:200,classes:'pointer' },
	// 		],
	// 		urlParam: {
	// 			filterCol:['recstatus','compcode','taxtype'],
	// 			filterVal:['ACTIVE', 'session.compcode','OUTPUT']
	// 		},
	// 		ondblClickRow:function(event){
	// 			if(event.type == 'keydown'){
	// 				var optid = $(event.currentTarget).get(0).getAttribute("optid");
	// 				var id_optid = optid.substring(0,optid.search("_"));

	// 				$(event.currentTarget).parent().next().html('');
	// 			}else{
	// 				var optid = $(event.currentTarget).siblings("input[type='text']").get(0).getAttribute("optid");
	// 				var id_optid = optid.substring(0,optid.search("_"));

	// 				$(event.currentTarget).parent().next().html('');
	// 			}
	// 			console.log(optid)
	// 			let data=selrowData('#'+dialog_GSTCode.gridname);

	// 			$("#jqGrid2 #"+id_optid+"_gstpercent").val(data['rate']);
	// 			$(dialog_GSTCode.textfield).closest('td').next().has("input[type=text]").focus();
	// 		},
	// 		gridComplete: function(obj){
	// 					var gridname = '#'+obj.gridname;
	// 					if($(gridname).jqGrid('getDataIDs').length == 1 && obj.ontabbing){
	// 						$(gridname+' tr#1').click();
	// 						$(gridname+' tr#1').dblclick();
	// 						$("#jqGrid2 input[name='AmtB4GST']").focus();
	// 					}else if($(gridname).jqGrid('getDataIDs').length == 0 && obj.ontabbing){
	// 						$('#'+obj.dialogname).dialog('close');
	// 					}
	// 				}
	// 		},{
	// 			title:"Select Tax Code For Item",
	// 			open: function(){
	// 				dialog_iptax.urlParam.filterCol = ['recstatus','compcode','taxtype'];
	// 				dialog_iptax.urlParam.filterVal = ['ACTIVE', 'session.compcode','OUTPUT'];
	// 			}
	// 		},'urlParam','radio','tab'
	// 	);
	// dialog_GSTCode.makedialog();

	var dialog_CustomerSO = new ordialog(
		'customer', 'debtor.debtormast', "#formdata input[name='db_debtorcode']", errorField,
		{
			colModel: [
				{ label: 'Debtor Code', name: 'debtorcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'name', width: 400, classes: 'pointer', canSearch: true, or_search: true,checked: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow:function(){
				let data=selrowData('#'+dialog_CustomerSO.gridname);
				$("#db_debtorcode").val(data['debtorcode']);
				
				// if($("#db_unallocated").find(":selected").text() == 'Credit Note') {
					
				// 	$("#jqGridAlloc").jqGrid("clearGridData", true);
					
				// 	var param = {
				// 		action: 'get_alloc_when_edit',
				// 		url:'CreditNoteAR/table',
				// 		field: [],
				// 		table_name: ['debtor.dbacthdr'],
				// 		filterCol: ['dbacthdr.debtorcode', 'dbacthdr.compcode', 'dbacthdr.recstatus', 'dbacthdr.outamount','dbacthdr.source'],
				// 		filterVal: [$("#db_debtorcode").val(), 'session.compcode', 'POSTED', '>.0','PB'],
				// 		WhereInCol: ['dbacthdr.trantype'],
				// 		WhereInVal: [['IN','DN']],
				// 		table_id: 'idno',
				// 		auditno:$('#db_auditno').val(),
				// 		posteddate:$('#db_entrydate').val(),
				// 	};
					
				// 	$.get("./CreditNoteAR/table?" + $.param(param), function (data) {
						
				// 	}, 'json').done(function (data) {
				// 		if (!$.isEmptyObject(data.rows)) {
				// 			myerrorIt_only(dialog_CustomerSO.textfield,false);
				// 			data.rows.forEach(function(elem) {
				// 				$("#jqGridAlloc").jqGrid('addRowData', elem['idno'] ,
				// 					{
				// 						idno:elem['idno'],
				// 						source:elem['source'],
				// 						trantype:elem['trantype'],
				// 						auditno:elem['auditno'],
				// 						lineno_:elem['lineno_'],
				// 						can_alloc:elem['can_alloc'],
				// 						debtorcode:elem['debtorcode'],
				// 						entrydate:elem['entrydate'],
				// 						posteddate:elem['posteddate'],
				// 						recptno:elem['recptno'],
				// 						refamount:elem['refamount'],
				// 						outamount:elem['outamount'],
				// 					}
				// 				);
				// 			});
							
				// 			calc_amtpaid_bal();
				// 			$('#db_reference').focus();
				// 			$("#jqGridAlloc input[name='checkbox']").hide();
							
				// 		} else {
				// 			alert("This debtor doesnt have any invoice until date: "+$('#db_entrydate').val());
				// 			$(dialog_CustomerSO.textfield).val('');
				// 			myerrorIt_only(dialog_CustomerSO.textfield,true);
				// 		}
				// 	});
				// } else if (($("#db_unallocated").find(":selected").text() == 'Credit Note Unallocated')) {
				// 	$("#jqGridAlloc").jqGrid("clearGridData", true);
				// 	$('#db_reference').focus();
				// }
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
		}, {
			title: "Select Customer",
			open: function(){
				dialog_CustomerSO.urlParam.filterCol=['compcode','recstatus'];
				dialog_CustomerSO.urlParam.filterVal=['session.compcode','ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	dialog_CustomerSO.makedialog();

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
	$('#CreditNo_show').text(obj.db_auditno);
	$('#CustName_show').text(obj.dm_name);
}

if($('#scope').val().trim().toUpperCase() == 'CANCEL'){
	$('td#glyphicon-plus,td#glyphicon-edit').hide();
}else{
	$('td#glyphicon-plus,td#glyphicon-edit').show();
}

function empty_form(){
	$('#CreditNo_show').text('');
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

function init_jq(oper){
	// if(oper != 'add'){
	// 	var unallocated = selrowData('#jqGrid').unallocated;
	// 	if(unallocated == 'true'){
	// 		$("#db_unallocated").val('0');
	// 	}else{
	// 		$("#db_unallocated").val('1');
	// 	}
	// }

	if(($("#db_unallocated").find(":selected").text() == 'Credit Note')) {
		// $('#save').hide();
		$('#grid_alloc').show();
		$('#grid_dtl').show();
		$('#jqGridPagerAlloc').show();
		$("#jqGridAlloc").jqGrid ('setGridWidth', Math.floor($("#jqGridAlloc_c")[0].offsetWidth-$("#jqGridAlloc_c")[0].offsetLeft-28));
		$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
	} else if (($("#db_unallocated").find(":selected").text() == 'Credit Note Unallocated')) {
		// $('#save').hide();
		$('#grid_alloc').hide();
		$('#grid_dtl').show();
		$('#jqGridPagerAlloc').hide();
		// $("#jqGridAlloc input[name='allocamount']").attr('readonly','readonly');
	}
}

function init_jq2(oper){
	if(($("#db_unallocated").find(":selected").text() == 'Credit Note')) {
		// $('#save').hide();
		$('#grid_alloc').show();
		$('#grid_dtl').show();
		$("#jqGridAlloc").jqGrid ('setGridWidth', Math.floor($("#jqGridAlloc_c")[0].offsetWidth-$("#jqGridAlloc_c")[0].offsetLeft-28));
		$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_c")[0].offsetWidth-$("#jqGrid2_c")[0].offsetLeft));
		
		if($('#db_debtorcode').val() != ''){
			// populate_alloc_table();
		}
	} else if (($("#db_unallocated").find(":selected").text() == 'Credit Note Unallocated')) { 
		// $('#save').hide();
		$('#grid_alloc').hide();
		$('#grid_dtl').show();
	}
}

function populate_alloc_table(){
	$("#jqGridAlloc").jqGrid("clearGridData", true);

	var urlParam_alloc = {
		action: 'get_alloc_when_edit',
		url:'CreditNoteAR/table',
		field: [],
		table_name: ['debtor.dbacthdr'],
		filterCol: ['dbacthdr.debtorcode', 'dbacthdr.compcode', 'dbacthdr.recstatus', 'dbacthdr.outamount','dbacthdr.source'],
		filterVal: [$("#db_debtorcode").val(), 'session.compcode', 'POSTED', '>.0','PB'],
		WhereInCol: ['dbacthdr.trantype'],
		WhereInVal: [['IN','DN']],
		table_id: 'idno',
		auditno:$('#db_auditno').val(),
		posteddate:$('#db_entrydate').val(),
	};

	$.get("./CreditNoteAR/table?" + $.param(urlParam_alloc), function (data) {
	}, 'json').done(function (data) {
		if (!$.isEmptyObject(data.rows)) {
			myerrorIt_only($("#db_debtorcode").val(),false);
			
			data.rows.forEach(function(elem) {
				
				let amount = 0;
				if(elem['can_alloc'] == false){
					amount = elem['amount'];
				}
				
				$("#jqGridAlloc").jqGrid('addRowData', elem['idno'] ,
					{
						idno:elem['idno'],
						source:elem['source'],
						trantype:elem['trantype'],
						auditno:elem['auditno'],
						lineno_:elem['lineno_'],
						can_alloc:elem['can_alloc'],
						debtorcode:elem['debtorcode'],
						entrydate:elem['entrydate'],
						posteddate:elem['posteddate'],
						recptno:elem['recptno'],
						refamount:elem['refamount'],
						outamount:elem['outamount'],
						amount: amount,
						balance: parseFloat(elem['outamount']) - parseFloat(amount),
					}
				);
			});
			
			var ids = $("#jqGridAlloc").jqGrid('getDataIDs');
			for (var i = 0; i < ids.length; i++) {
				var rowdata = $("#jqGridAlloc").jqGrid('getRowData',ids[i]);
				if(rowdata.can_alloc == 'false'){
					continue;
				}
				
				$("#jqGridAlloc").jqGrid('editRow',ids[i]);
				
				$('#jqGridAlloc input#'+ids[i]+'_amount').on('keyup',{rowid:ids[i]},calc_amtpaid);
			}
			
			calc_amtpaid_bal();
			
		} else {
			// alert("This debtor doesnt have any invoice!");
			// $(dialog_CustomerSO.textfield).val('');
			// myerrorIt_only($("#db_debtorcode").val(),false);
		}
	});
}

function populate_alloc_table_save(){
	$("#jqGridAlloc").jqGrid("clearGridData", true);

	var urlParam_alloc = {
		action: 'get_alloc_when_edit',
		url:'CreditNoteAR/table',
		field: [],
		table_name: ['debtor.dbacthdr'],
		filterCol: ['dbacthdr.debtorcode', 'dbacthdr.compcode', 'dbacthdr.recstatus', 'dbacthdr.outamount','dbacthdr.source'],
		filterVal: [$("#db_debtorcode").val(), 'session.compcode', 'POSTED', '>.0','PB'],
		WhereInCol: ['dbacthdr.trantype'],
		WhereInVal: [['IN','DN']],
		table_id: 'idno',
		auditno:$('#db_auditno').val(),
		posteddate:$('#db_entrydate').val(),
	};

	$.get("./CreditNoteAR/table?" + $.param(urlParam_alloc), function (data) {
	}, 'json').done(function (data) {
		if (!$.isEmptyObject(data.rows)) {
			myerrorIt_only($("#db_debtorcode").val(),false);
			
			data.rows.forEach(function(elem) {
				
				let amount = 0;
				if(elem['can_alloc'] == false){
					amount = elem['amount'];
				}
				
				$("#jqGridAlloc").jqGrid('addRowData', elem['idno'] ,
					{
						idno:elem['idno'],
						source:elem['source'],
						trantype:elem['trantype'],
						auditno:elem['auditno'],
						lineno_:elem['lineno_'],
						can_alloc:elem['can_alloc'],
						debtorcode:elem['debtorcode'],
						entrydate:elem['entrydate'],
						posteddate:elem['posteddate'],
						recptno:elem['recptno'],
						refamount:elem['refamount'],
						outamount:elem['outamount'],
						amount: amount,
						balance: parseFloat(elem['outamount']) - parseFloat(amount),
					}
				);
			});
			
			calc_amtpaid_bal();
			$("#jqGridAlloc input[name='checkbox']").hide();
			
		} else {
			// alert("This debtor doesnt have any invoice!");
			// $(dialog_CustomerSO.textfield).val('');
			// myerrorIt_only($("#db_debtorcode").val(),false);
		}
	});
}

function disable_gridpager(pager,hide=true){
	if(hide){
		$(pager+'_left > table').hide();
	}else{
		$(pager+'_left > table').show();
	}
}

function show_post_button(show=true){
	if(show){
		$('#posted_button').show();
	}else{
		$('#posted_button').hide();
	}
}

function calculate_total_alloc(rowid){
	mycurrency2.formatOff();
	mycurrency.formatOff();

	var tot_detail = $('#db_amount').val();
	var rowids = $('#jqGridAlloc').jqGrid('getDataIDs');
	var tot_alloc = 0;

	rowids.forEach(function(e,i){
		let amt = $('input#'+e+'_amount').val();
		if(amt != undefined){
			tot_alloc = parseFloat(tot_alloc)+parseFloat(amt);
		}else{
			let rowdata = $('#jqGridAlloc').jqGrid ('getRowData',e);
			tot_alloc = parseFloat(tot_alloc)+parseFloat(rowdata.amount);
		}
	});

	if(!isNaN(tot_alloc)){
		$('#tot_alloc').val(numeral(tot_alloc).format('0,0.00'));

		outamount = parseFloat(tot_detail) - parseFloat(tot_alloc);
		if(!isNaN(outamount)){
			$('#db_outamount').val(numeral(outamount).format('0,0.00'));

			if(outamount < 0){
				alert('Allocate amount cant exceed total amount');
				if(rowid != undefined){
					text_error1('input#'+rowid+'_amount');
				}
			}else{
				if(rowid != undefined){
					text_success1('input#'+rowid+'_amount');
				}
			}
		}
	}

	mycurrency2.formatOn();
	mycurrency.formatOn();
}

function calc_amtpaid(event){
	let rowid = event.data.rowid;
	let data = $('#jqGridAlloc').jqGrid ('getRowData', rowid);
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
	$("#jqGridAlloc").jqGrid('setCell', rowid, 'balance', balance);
	calculate_total_alloc(rowid);
}

function calc_amtpaid_bal(){
	$('input.checkbox_jqgAlloc[type="checkbox"]').on('click',function(){
		let rowid = $(this).data('rowid');
		let data = $('#jqGridAlloc').jqGrid ('getRowData', rowid);
		if($(this).prop('checked')){
			$('#jqGridAlloc input#'+rowid+'_amount').val(data.outamount);
			$("#jqGridAlloc").jqGrid('setCell', rowid, 'balance', 0);
		}else{
			$('#jqGridAlloc input#'+rowid+'_amount').val(0);
			$("#jqGridAlloc").jqGrid('setCell', rowid, 'balance', data.outamount);
		}
		calculate_total_alloc();
	});
}
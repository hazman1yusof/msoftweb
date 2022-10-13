$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	$('body').show();
	/////////////////////////validation//////////////////////////
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
				console.log(errorField);
				return {
					element : $(errorField[0]),
					message : ' '
				}
			}
		},
	};
	/////////////////////////////////// currency ///////////////////////////////
	var fdl = new faster_detail_load();
	var mycurrency =new currencymode(['#apacthdr_outamount', '#apacthdr_amount']);
	var mycurrency2 =new currencymode(['#apacthdr_outamount', '#apacthdr_amount']);
	////////////////////////////////////start dialog///////////////////////////////////////
	var oper=null;
	var unsaved = false,counter_save=0;

	$("#dialogForm_pv")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				$("#jqGrid2_pv").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_pv_c")[0].offsetWidth-$("#jqGrid2_pv_c")[0].offsetLeft));
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				disableForm('#formdata_pv');
				$("#pg_jqGridPager2 table").hide();
			},
			close: function( event, ui ) {
				//reset balik
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_pv');
				$('.my-alert').detach();
				$("#formdata_pv a").off();
				$(".noti, .noti2 ol").empty();
				refreshGrid("#jqGrid2_pv",null,"kosongkan");
				//radbuts.reset();
				errorField.length=0;
			},
		});

	$("#dialogForm_pd")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				disableForm('#formdata_pd');
				$("#pg_jqGridPager2 table").hide();
			},
			close: function( event, ui ) {
				//reset balik
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_pd');
				$('.my-alert').detach();
				$("#formdata_pd a").off();
				$(".noti, .noti2 ol").empty();
				errorField.length=0;
			},
		});

	$("#dialogForm_in")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				$("#jqGrid2_in").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_in_c")[0].offsetWidth-$("#jqGrid2_in_c")[0].offsetLeft));
				$("#jqGrid2_pv").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_pv_c")[0].offsetWidth-$("#jqGrid2_pv_c")[0].offsetLeft));
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				disableForm('#formdata_in');
				refreshGrid("#jqGrid2_in",urlParam2_in);
				refreshGrid("#jqGrid2_pv",urlParam2_pv);
				$("#pg_jqGridPager2_in table").hide();
				init_jq2();
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_in');
				$('.my-alert').detach();
				$("#formdata_in a").off();
				$(".noti, .noti2 ol").empty();
				refreshGrid("#jqGrid2_in",null,"kosongkan");
				refreshGrid("#jqGrid2_pv",null,"kosongkan");
				errorField.length=0;
			},
	});

	$("#dialogForm_cn")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				unsaved = false;
				errorField.length=0;
				$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_cn_c")[0].offsetWidth-$("#jqGrid2_cn_c")[0].offsetLeft));
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				disableForm('#formdata_cn');
				refreshGrid("#jqGrid2_cn",urlParam2_cn);
				$("#pg_jqGridPager2_cn table").hide();
				init_jq2_cn(oper);
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_cn');
				$('.my-alert').detach();
				$("#formdata_cn a").off();
				$(".noti").empty();
				refreshGrid("#jqGrid2_cn",null,"kosongkan");
				errorField.length=0;
			},
	});

	$("#dialogForm_cna")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				unsaved = false;
				errorField.length=0;
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				disableForm('#formdata_cna');
				$("#pg_jqGridPager2_cn table").hide();
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_cna');
				$('.my-alert').detach();
				$("#formdata_cna a").off();
				$(".noti").empty();
				errorField.length=0;
			},
	});

	$("#dialogForm_dn")
	.dialog({
		width: 9 / 10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function (event, ui) {
			unsaved = false
			counter_save=0;
			parent_close_disabled(true);
			mycurrency.formatOnBlur();
			disableForm('#formdata_dn');
		
		},
		close: function( event, ui ) {
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata_dn');
			$('.my-alert').detach();
			$("#formdata_dn a").off();
			$(".noti").empty();
			errorField.length=0;
		},
});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'maintable',
		url:'./apenquiry/table',
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'Supplier Code', name: 'apacthdr_suppcode', width: 70, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Audit No', name: 'apacthdr_auditno', width: 18, classes: 'wrap',formatter: padzero, unformat: unpadzero, canSearch: true},
			{ label: 'Transaction <br>Type', name: 'apacthdr_trantype', width: 25, classes: 'wrap text-uppercase', canSearch: true},
			{ label: 'Cheque No', name: 'apacthdr_cheqno', width: 30, classes: 'wrap text-uppercase', canSearch: true},
			{ label: 'Bank Code', name: 'apacthdr_bankcode', width: 30, classes: 'wrap text-uppercase', hidden:false},
			{ label: 'PV No', name: 'apacthdr_pvno', width: 50, classes: 'wrap', hidden:true, canSearch: true},
			{ label: 'Document No', name: 'apacthdr_document', width: 50, classes: 'wrap text-uppercase', canSearch: true},
			{ label: 'Unit', name: 'apacthdr_unit', width: 30, hidden:false},
			{ label: 'Pay To', name: 'apacthdr_payto', width: 50, classes: 'wrap text-uppercase', hidden:true, canSearch: true},
			{ label: 'Category Code', name: 'apacthdr_category', width: 40, hidden:false, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},		
			{ label: 'Document Date', name: 'apacthdr_actdate', width: 25, classes: 'wrap text-uppercase', canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Amount', name: 'apacthdr_amount', width: 25, classes: 'wrap', align: 'right', formatter:'currency'},
			{ label: 'Outamount', name: 'apacthdr_outamount', width: 25, hidden:false, classes: 'wrap', align: 'right', formatter:'currency'},
			{ label: 'doctype', name: 'apacthdr_doctype', width: 10, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'Creditor Name', name: 'supplier_name', width: 50, classes: 'wrap text-uppercase', checked: true, hidden: true},
			{ label: 'Department', name: 'apacthdr_deptcode', width: 25, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'Status', name: 'apacthdr_recstatus', width: 25, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'Post Date', name: 'apacthdr_recdate', width: 35, classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter, hidden:true},
			{ label: 'remarks', name: 'apacthdr_remarks', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adduser', name: 'apacthdr_adduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adddate', name: 'apacthdr_adddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upduser', name: 'apacthdr_upduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upddate', name: 'apacthdr_upddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'source', name: 'apacthdr_source', width: 40, hidden:true},
			{ label: 'idno', name: 'apacthdr_idno', width: 40, hidden:true, key:true},
			{ label: 'paymode', name: 'apacthdr_paymode', width: 50, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'trantype2', name: 'apacthdr_trantype2', width: 50, classes: 'wrap', hidden:true},
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
			if(selrowData("#jqGrid").apacthdr_trantype=='PV'){
				urlParam2_pv.apacthdr_auditno=selrowData("#jqGrid").apacthdr_auditno;
			}else if(selrowData("#jqGrid").apacthdr_trantype=='IN'){
				urlParam2_in.filterVal[1]=selrowData("#jqGrid").apacthdr_auditno;
				urlParam2_pv.apacthdr_auditno=selrowData("#jqGrid").apacthdr_auditno;
			}else if(selrowData("#jqGrid").apacthdr_trantype2=='Credit Note'){
				urlParam2_cn.filterVal[1]=selrowData("#jqGrid").apacthdr_auditno;
			}
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			let stat = selrowData("#jqGrid").apacthdr_recstatus;
			$("#jqGridPager td[title='View Selected Row']").click();
		},
		gridComplete: function(){
			if($('#jqGrid').data('inputfocus') == 'creditor_search'){
				$("#creditor_search").focus();
				$('#jqGrid').data('inputfocus','');
				$('#creditor_search_hb').text('');
				removeValidationClass(['#creditor_search']);
			}else{
				$("#searchForm input[name=Stext]").focus();
			}
			fdl.set_array().reset();
		},
		loadComplete: function(){
			calc_jq_height_onchange("jqGrid");
		},
		
	});

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

	searchClick2('#jqGrid','#searchForm',urlParam);
	////////////////////////////////////////////////////////

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam);
		},
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		caption: "", cursor: "pointer", position: "first",
		buttonicon: "glyphicon glyphicon-info-sign",
		title: "View Selected Row",
		onClickButton: function () {
			oper = 'view';
			selRowId = $("#jqGrid").jqGrid('getGridParam', 'selrow');
			if(selrowData("#jqGrid").apacthdr_trantype=='PV'){
				populateFormdata("#jqGrid", "#dialogForm_pv", "#formdata_pv", selRowId, 'view', '');
				refreshGrid("#jqGrid2_pv",urlParam2_pv,'add');
			}else if(selrowData("#jqGrid").apacthdr_trantype=='PD'){
				populateFormdata("#jqGrid", "#dialogForm_pd", "#formdata_pd", selRowId, 'view', '');
			}else if(selrowData("#jqGrid").apacthdr_trantype=='IN'){
				populateFormdata("#jqGrid", "#dialogForm_in", "#formdata_in", selRowId, 'view', '');
				refreshGrid("#jqGrid2_in",urlParam2_in,'add');
				refreshGrid("#jqGrid2_pv",urlParam2_pv,'add');
			}else if(selrowData("#jqGrid").apacthdr_trantype2=='Credit Note'){
				populateFormdata("#jqGrid", "#dialogForm_cn", "#formdata_cn", selRowId, 'view', '');
				refreshGrid("#jqGrid2_cn",urlParam2_cn,'add');
			}else if(selrowData("#jqGrid").apacthdr_trantype2=='Credit Note Unallocated'){
				populateFormdata("#jqGrid", "#dialogForm_cna", "#formdata_cna", selRowId, 'view', '');
			}else if(selrowData("#jqGrid").apacthdr_trantype=='DN'){
				populateFormdata("#jqGrid", "#dialogForm_dn", "#formdata_dn", selRowId, 'view', '');
			}
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field, table, case_;
		switch(options.colModel.name){
			case 'apacthdr_suppcode':field=['suppcode','name'];table="material.supplier";case_='apacthdr_suppcode';break;
			case 'apacthdr_category':field=['catcode','description'];table="material.category";case_='apacthdr_category';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('apenquiry',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

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

	////PV
	var urlParam2_pv={
		action:'get_alloc_table',
		url:'paymentVoucher/table',
		apacthdr_auditno:'',
	};

	$("#jqGrid2_pv").jqGrid({
		datatype: "local",
		editurl: "./paymentVoucherDetail/form",
		colModel: [
			{ label: ' ', name: 'checkbox', width: 15,},
			{ label: 'Creditor', name: 'suppcode', width: 100, classes: 'wrap'},
			{ label: 'Invoice Date', name: 'allocdate', width: 100, classes: 'wrap',formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}},
			{ label: 'Invoice No', name: 'reference', width: 100, classes: 'wrap',},
			{ label: 'Amount', name: 'refamount', width: 100, classes: 'wrap', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}, editable: false, align: "right"},
			{ label: 'O/S Amount', name: 'outamount', width: 100, align: 'right', classes: 'wrap', editable:false, formatter: 'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
			{ label: 'Amount Paid', name: 'allocamount', width: 100, classes: 'wrap', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}, editable: false},
			{ label: 'Balance', name: 'balance', width: 100, classes: 'wrap', hidden:false, formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}, editable: false},
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
			{ label: 'idno', name: 'idno', width: 80, classes: 'wrap', hidden:true}, 
		
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
		loadComplete: function(data){
		},
		gridComplete: function(){
		},
		beforeSubmit: function(postdata, rowid){ 
	 	}
	});

	///IN
	var urlParam2_in={
		action:'get_table_default',
		url:'util/get_table_default',
		field:['apdt.compcode','apdt.source','apdt.reference','apdt.trantype','apdt.auditno','apdt.lineno_','apdt.deptcode','apdt.category','apdt.document', 'apdt.AmtB4GST', 'apdt.GSTCode', 'apdt.amount', 'apdt.dorecno', 'apdt.grnno'],
		table_name:['finance.apactdtl AS apdt'],
		table_id:'lineno_',
		filterCol:['apdt.compcode','apdt.auditno', 'apdt.recstatus','apdt.source'],
		filterVal:['session.compcode', '', '<>.DELETE', 'AP']
	};

	$("#jqGrid2_in").jqGrid({
		datatype: "local",
		editurl: "./invoiceAPDetail/form",
		colModel: [
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true},
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true},
			{ label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Line No', name: 'lineno_', width: 80, classes: 'wrap', hidden:true, editable:false, key:true}, //canSearch: true, checked: true},
			{ label: 'Delivery Order Number', name: 'document', width: 200, classes: 'wrap', canSearch: true, editable: false},
			{ label: 'Purchase Order Number', name: 'reference', width: 200, classes: 'wrap', editable: false},
			{ label: 'Amount', name: 'amount', width: 100, classes: 'wrap', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}, editable: false, align: "right"},
			{ label: 'Tax Claim', name: 'GSTCode', width: 200, edittype:'text', hidden:true, classes: 'wrap', editable:false,},
			{ label: 'Tax Amount', name: 'AmtB4GST', width: 100, classes: 'wrap', hidden:true, formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}, editable: false},
			{ label: 'Record No', name: 'dorecno', width: 100, classes: 'wrap', editable: false},
			{ label: 'GRN No', name: 'grnno', width: 100, classes: 'wrap', editable: false},
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
		pager: "#jqGridPager2",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid2_in");
			urlParam2_pv.apacthdr_auditno=selrowData("#jqGrid").apacthdr_auditno;
		},
		gridComplete: function(){
			
		},
		beforeSubmit: function(postdata, rowid){
	 	}
	});

});

//CN
var urlParam2_cn={
	action:'get_table_default',
	url:'util/get_table_default',
	field:['apdt.compcode','apdt.source','apdt.reference','apdt.trantype','apdt.auditno','apdt.lineno_','apdt.deptcode','apdt.category','apdt.document', 'apdt.AmtB4GST', 'apdt.GSTCode', 'apdt.amount', 'apdt.dorecno', 'apdt.grnno'],
	table_name:['finance.apalloc AS apdt'],
	table_id:'lineno_',
	filterCol:['apdt.compcode','apdt.auditno','apdt.source','apdt.trantype'],
	filterVal:['session.compcode', '', 'AP','CN']
};
$("#jqGrid2_cn").jqGrid({
	datatype: "local",
	editurl: "./creditNoteDetail/form",
	colModel: [
		{ label: 'Creditor', name: 'suppcode', width: 100, classes: 'wrap', },//formatter: showdetail,unformat:un_showdetail
		{ label: 'Invoice Date', name: 'allocdate', width: 100, classes: 'wrap', formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}},
		{ label: 'Invoice No', name: 'reference', width: 100, classes: 'wrap',},
		{ label: 'Amount', name: 'refamount', width: 100, classes: 'wrap', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}, editable: false, align: "right"},
		{ label: 'O/S Amount', name: 'outamount', width: 100, align: 'right', classes: 'wrap', editable:false, formatter: 'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}},
		{ label: 'Amount Paid', name: 'allocamount', width: 100, classes: 'wrap', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}, editable: false,align: "right"},
		{ label: 'Balance', name: 'balance', width: 100, classes: 'wrap', hidden:false, formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}, editable: false, align: "right"},
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
		{ label: 'idno', name: 'idno', width: 80, classes: 'wrap', hidden:true}, 
	
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
	pager: "#jqGridPager2",
	loadComplete: function(data){
		calc_jq_height_onchange("jqGrid2_cn");
	},
	gridComplete: function(){	
	},
	beforeSubmit: function(postdata, rowid){ 
	}
});

function calc_jq_height_onchange(jqgrid){
	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
	if(scrollHeight<50){
		scrollHeight = 50;
	}else if(scrollHeight>300){
		scrollHeight = 300;
	}
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight);
}
		
function init_jq2(){
	if($('#apacthdr_doctype').val() == 'Supplier'){
		$('#save').hide();
		$('#ap_detail').show();
		$('#pv_detail').show();
		$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_in_c")[0].offsetWidth-$("#jqGrid2_in_c")[0].offsetLeft-28));
		$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_pv_c")[0].offsetWidth-$("#jqGrid2_pv_c")[0].offsetLeft-28));
		$("label[for='apactdtl_outamt'], input#apactdtl_outamt").show();
	}else{
		$('#save').hide();
		$('#ap_detail').hide();
		$('#pv_detail').hide();
		$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_in_c")[0].offsetWidth-$("#jqGrid2_in_c")[0].offsetLeft-28));
		$("label[for='apactdtl_outamt'], input#apactdtl_outamt").hide();
	}
}

function init_jq2_cn(oper){

	if(($("#apacthdr_trantype2").find(":selected").text() == 'Credit Note')) {
		$('#save').hide();
		$('#cn_detail').show();
		$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_cn_c")[0].offsetWidth-$("#jqGrid2_cn_c")[0].offsetLeft-28));
	} else if (($("#apacthdr_trantype2").find(":selected").text() == 'Credit Note Unallocated')) { 
		$('#save').show();
		$('#cn_detail').hide();
	}
	
}
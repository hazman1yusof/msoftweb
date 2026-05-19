
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;
var fdl = new faster_detail_load();

$(document).ready(function () {
	$("body").show();
	/////////////////////////validation//////////////////////////
	$.validate({
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

	page_to_view_only($('#viewonly').val());

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		url:'./reprintBill/table',
		action:'maintable',
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'idno', name: 'idno', hidden: true, key:true},	
			{ label: 'compcode', name: 'compcode', hidden:true},					
			{ label: 'source', name: 'source', hidden:true},
			{ label: 'TT', name: 'trantype', width: 25, classes: 'wrap'},
			{ label: 'Invoice No', name: 'invno', width: 50, classes: 'wrap', canSearch: true},
			{ label: 'Line No', name: 'lineno_', width: 30, classes: 'wrap'},
			{ label: 'MRN', name: 'mrn', width: 30, classes: 'wrap', canSearch: true, checked:true},
			{ label: 'Episno', name: 'episno', width: 25, classes: 'wrap'},
			{ label: 'Epistype', name: 'epistype', width: 25, classes: 'wrap'},
			{ label: 'Patient Name', name: 'Name', width: 110, classes: 'wrap', canSearch: true},
			{ label: 'Debtor Code', name: 'debtorcode', width: 50, classes: 'wrap'},
			{ label: 'Debtor Name', name: 'dbname', width: 110, classes: 'wrap', canSearch: true},
			{ label: 'Amount', name: 'amount', width: 50, align: 'right',formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}},
			{ label: 'Bill Date', name: 'entrydate', width: 40},
			{ label: 'auditno', name: 'auditno', hidden:true},
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'idno',
		sortorder:'desc',
		width: 900,
		height: 350,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			urlParam_acctent.invno = selrowData('#jqGrid').invno;
			urlParam_acctent.lineno_ = selrowData('#jqGrid').lineno_;
			urlParam_acctent.auditno = selrowData('#jqGrid').auditno;
			urlParam_acctent.dbname = selrowData('#jqGrid').dbname;
			urlParamGridDetail.idnoIN = selrowData('#jqGrid').idno;
			refreshGrid("#gridacctent", urlParam_acctent);
			refreshGrid("#gridDetail_bill", urlParamGridDetail);
		},
		loadComplete: function(){
			if($('#jqGrid').data('lastselrow') == 'none'){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}else{
				$("#jqGrid").setSelection($('#jqGrid').data('lastselrow'));
				$('#jqGrid tr#' + $('#jqGrid').data('lastselrow')).focus();
			}
			$("#jqGridPager td.ui-disabled").hide();
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			// $("#jqGrid_iledit").click();
		},
		gridComplete: function () {
			fdl.set_array().reset();
			if($('#jqGrid').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			$("#jqGridPager td.ui-disabled").hide();
		},
	});

	jqgrid_label_align_right("#jqGrid");

	function check_cust_rules(rowid){
		var chk = ['costcode','description'];
		chk.forEach(function(e,i){
			var val = $("#jqGrid input[name='"+e+"']").val();
			if(val.trim().length <= 0){
				myerrorIt_only("#jqGrid input[name='"+e+"']",true);
			}else{
				myerrorIt_only("#jqGrid input[name='"+e+"']",false);
			}
		})
	}

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").inlineNav('#jqGridPager', {
		add: false,
		edit: false,
		cancel: false,
	}).jqGrid('navButtonAdd', "#jqGridPager", {
		id: "jqGridPagerRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	});

	$('#reprint_bill').click(function(){
		var mrn = selrowData('#jqGrid').mrn;
		var episno = selrowData('#jqGrid').episno;
		var lineno_ = selrowData('#jqGrid').lineno_;
		window.open('./ordcom/table?action=final_bill_invoice&mrn='+mrn+'&episno='+episno+'&lineno_='+lineno_, '_blank');
	});

	$('#reprint__summbill').click(function(){
		var mrn = selrowData('#jqGrid').mrn;
		var episno = selrowData('#jqGrid').episno;
		var lineno_ = selrowData('#jqGrid').lineno_;
		window.open('./ordcom/table?action=showpdf_summ_final&mrn='+mrn+'&episno='+episno+'&lineno_='+lineno_, '_blank');
	});


	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	//toogleSearch('#sbut1','#searchForm','on');
	populateSelect2('#jqGrid','#searchForm');
	searchClick2('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	if($('#viewonly').val() == 'viewonly'){
		addParamField('#jqGrid',false,urlParam);
		urlParam.viewonly = 'viewonly';
		urlParam.auditno = $('#viewonly_auditno').val();
		urlParam.lineno_ = $('#viewonly_lineno_').val();
	}else{
		addParamField('#jqGrid',true,urlParam);
	}

	$("#acctent_panel").on("shown.bs.collapse", function(){
        SmoothScrollTo("#acctent_panel",100);
		$("#gridacctent").jqGrid ('setGridWidth', Math.floor($("#acctent_c")[0].offsetWidth-$("#acctent_c")[0].offsetLeft-28));
		calc_jq_height_onchange("gridacctent",false,parseInt($('#acctent_c').prop('clientHeight'))-150);
		refreshGrid("#gridacctent", urlParam_acctent);
	});

	var urlParam_acctent ={
		url:'./reprintBill/table',
		action:'acctent_sales',
		invno:'',
		lineno_:'',
	}

	$("#gridacctent").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'idno', name: 'idno', hidden: true, key:true},	
			{ label: 'compcode', name: 'compcode', hidden:true},
			{ label: 'Date', name: 'date', width: 30, classes: 'wrap',formatter:dateFormatter_},
			{ label: 'Description', name: 'description', width: 150, classes: 'wrap', canSearch: true},
			{ label: 'Account', name: 'account', width: 50, classes: 'wrap', hidden:true},
			{ label: 'Account Name', name: 'accountname', width: 100, classes: 'wrap'},
			{ label: 'Debit', name: 'debit', width: 50, align: 'right',formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}},
			{ label: 'Credit', name: 'credit', width: 50, align: 'right',formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}},
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		loadonce:true,
		paging:false,
		sortname:'idno',
		sortorder:'desc',
		width: 900,
		height: 350,
		rowNum: 3000,
		pager: "#jqGridPageracctent",
		onSelectRow:function(rowid, selected){
		},
		loadComplete: function(){
			if($('#gridacctent').data('lastselrow') == 'none'){
				$("#gridacctent").setSelection($("#gridacctent").getDataIDs()[0]);
			}else{
				$("#gridacctent").setSelection($('#gridacctent').data('lastselrow'));
				$('#gridacctent tr#' + $('#gridacctent').data('lastselrow')).focus();
			}
			$("#jqGridPageracctent td.ui-disabled").hide();
			calc_jq_height_onchange("gridacctent",false,parseInt($('#acctent_c').prop('clientHeight'))-150);
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			// $("#jqGrid_iledit").click();
		},
		gridComplete: function () {
			fdl.set_array().reset();
			if($('#gridacctent').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#gridacctent").setSelection($("#gridacctent").getDataIDs()[0]);
			}
			$("#jqGridPageracctent td.ui-disabled").hide();
		},
	});

	jqgrid_label_align_right("#gridacctent");

	$("#gridacctent").inlineNav('#jqGridPageracctent', {
		add: false,
		edit: false,
		cancel: false,
	}).jqGrid('navButtonAdd', "#jqGridPageracctent", {
		id: "jqGridPageracctentRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#gridacctent", urlParam_acctent);
		},
	});

	$('#a_sales_acctent,#a_cost_acctent').click(function(){
		if($(this).data('type') == 'sales'){
			$('#acctent_title_span').text('Sales');
			urlParam_acctent.action = 'acctent_sales';
			refreshGrid("#gridacctent", urlParam_acctent);
		}else{
			$('#acctent_title_span').text('Cost');
			urlParam_acctent.action = 'acctent_cost';
			refreshGrid("#gridacctent", urlParam_acctent);
		}
	});

	/////start detail bill/////

	$("#detailBill_panel").on("shown.bs.collapse", function(){
        SmoothScrollTo("#detailBill_panel",100);
		$("#gridDetail_bill").jqGrid ('setGridWidth', Math.floor($("#detailBill_c")[0].offsetWidth-$("#detailBill_c")[0].offsetLeft-28));
		calc_jq_height_onchange("gridDetail_bill",false,parseInt($('#detailBill_c').prop('clientHeight'))-150);
		refreshGrid("#gridDetail_bill", urlParamGridDetail);
	});

	var urlParamGridDetail = {
		action: 'specific_allocate',
		url: './arenquiry/table',
	}

	$("#gridDetail_bill").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'idno', width: 40, hidden: true, key:true },
			{ label: 'Chg Class', name: 'chgclass', width: 20, classes: 'wrap' },
			{ label: 'Invoice Code', name: 'invcode', width: 20, classes: 'wrap' },
			{ label: 'Description', name: 'description', width: 100 },
			{ label: 'Doctor Code', name: 'doctorcode', width: 100, classes: 'wrap', formatter: showdetail},
			{ label: 'Amount', name: 'amount', formatter: 'currency', width: 40 },
			{ label: 'O/S Amount', name: 'outamt', formatter: 'currency', width: 40  },
		],
		autowidth: true,
		viewrecords: true,
		multiSort: true,
		loadonce: false,
		height: 400,
		scroll: false,
		rowNum: 100,
		pager: "#jqGridPagerDetail_bill",
		onSelectRow: function (rowid){
			calc_jq_height_onchange("gridDetail_bill",false,parseInt($('#detailBill_c').prop('clientHeight'))-150);
		},
		onPaging: function (button){
		},
		gridComplete: function (rowid){
			fdl.set_array().reset();
			calc_jq_height_onchange("gridDetail_bill",false,parseInt($('#detailBill_c').prop('clientHeight'))-150);
			if($('#gridDetail_bill').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#gridDetail_bill").setSelection($("#gridDetail_bill").getDataIDs()[0]);
			}
			$("#jqGridPagerDetail_bill td.ui-disabled").hide();
		},
	});

	$("#gridDetail_bill").inlineNav('#jqGridPagerDetail_bill', {
		add: false,
		edit: false,
		cancel: false,
	}).jqGrid('navButtonAdd', "#jqGridPagerDetail_bill", {
		id: "jqGridPagerDetailRefresh",
		caption: "", cursor: "pointer", position: "last",
		buttonicon: "glyphicon glyphicon-refresh",
		title: "Refresh Table",
		onClickButton: function () {
			refreshGrid("#gridDetail_bill", urlParamGridDetail);
		},
	});

	$('#a_detail_bill').click(function(){
		var mrn = selrowData('#jqGrid').mrn;
		var episno = selrowData('#jqGrid').episno;
		var lineno_ = selrowData('#jqGrid').lineno_;
		var invcode = selrowData('#gridDetail_bill').invcode;
		window.open('./ordcom/table?action=final_bill_invoice&mrn='+mrn+'&episno='+episno+'&lineno_='+lineno_+'&invcode='+invcode, '_blank');
	});

	$('#a_detail_pre').click(function(){
		var mrn = selrowData('#jqGrid').mrn;
		var episno = selrowData('#jqGrid').episno;
		var lineno_ = selrowData('#jqGrid').lineno_;
		var invcode = $('#phar_invcode').val();
		window.open('./ordcom/table?action=final_bill_invoice&mrn='+mrn+'&episno='+episno+'&lineno_='+lineno_+'&invcode='+invcode+'&pres_=1', '_blank');
	});

});

function dateFormatter_(cellvalue, options, rowObject){
	return moment(cellvalue, 'YYYY-MM-DD HH:mm:ss').format("DD-MM-YYYY");
}

function showdetail(cellvalue, options, rowObject){
	var field, table, case_;
	switch(options.colModel.name){
		//allo_spec
		case 'doctorcode': field = ['doctorcode','doctorname'];table = "hisdb.doctor";case_ = 'doctor';break;

	}
	var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
	fdl.get_array('reprintbill',options,param,case_,cellvalue);
	if(cellvalue == null)cellvalue = " ";
	return cellvalue;
}
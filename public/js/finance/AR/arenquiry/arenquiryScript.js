$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	$("body").show();
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
				console.log(errorField);
				return {
					element : $(errorField[0]),
					message : ' '
				}
			}
		},
	};

	/////////////////////////////////// currency ///////////////////////////////
	var mycurrency =new currencymode(['#db_outamount', '#db_amount']);
	var mycurrency2 =new currencymode(['#db_outamount', '#db_amount']);
	var fdl = new faster_detail_load();

	////////////////////////////////////start dialog//////////////////////////////////////
	$("#dialogForm_CN")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				errorField.length=0;
				$("#jqGrid2").jqGrid('setGridWidth', Math.floor($("#jqGrid2_CN_c")[0].offsetWidth - $("#jqGrid2_CN_c")[0].offsetLeft));
				mycurrency.formatOnBlur();
				disableForm('#formdata_CN');
				$("#pg_jqGridPager2 table").hide();
			},
			close: function( event, ui ) {
			}
		});

	$("#dialogForm_IN")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_IN_c")[0].offsetWidth-$("#jqGrid2_IN_c")[0].offsetLeft));
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				disableForm('#formdata_IN');
				refreshGrid("#jqGrid2_IN",urlParam2_IN);
				$("#pg_jqGridPager2_IN table").hide();
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_IN');
				$('.my-alert').detach();
				$("#formdata_IN a").off();
				$(".noti, .noti2 ol").empty();
				refreshGrid("#jqGrid2_IN",null,"kosongkan");
				errorField.length=0;
			},
	});

	$("#dialogForm_DN")
	.dialog({
		width: 9 / 10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function (event, ui) {
			parent_close_disabled(true);
			$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_DN_c")[0].offsetWidth-$("#jqGrid2_DN_c")[0].offsetLeft));
			mycurrency.formatOnBlur();
			mycurrency.formatOn();
			disableForm('#formdata_DN');
			refreshGrid("#jqGrid2_DN",urlParam2_DN);
			$("#pg_jqGridPager2_DN table").hide();
		},
		close: function( event, ui ) {
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata_DN');
			$('.my-alert').detach();
			$("#formdata_DN a").off();
			$(".noti, .noti2 ol").empty();
			refreshGrid("#jqGrid2_DN",null,"kosongkan");
			errorField.length=0;
		},
	});
	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////

	var urlParam={
		action:'maintable',
		url:'./arenquiry/table',
		//source:$('#db_source').val(),
		//trantype:$('#db_trantype').val(),
	}

	/////////////////////parameter for saving url///////////////////////////////////////////////////////
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
	/////////////////////////////////// jqgrid //////////////////////////////////////////////////////////

	$("#jqGrid").jqGrid({
	datatype: "local",
	colModel: [
		{ label: 'compcode', name: 'db_compcode', hidden: true },
		{ label: 'Debtor Code', name: 'db_debtorcode', width: 35, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail},
		{ label: 'Payer Code', name: 'db_payercode', width: 20, hidden: true},
		{ label: 'Customer', name: 'dm_name', width: 35, checked: true, classes: 'wrap' },
		{ label: 'Audit No', name: 'db_auditno', width: 8, align: 'right', classes: 'wrap',formatter: padzero, unformat: unpadzero, canSearch: true},
		{ label: 'Sector', name: 'db_unit', width: 10, hidden: true, classes: 'wrap' },
		{ label: 'PO No', name: 'db_ponum', width: 8, formatter: padzero5, unformat: unpadzero },
		{ label: 'Invoice No', name: 'db_invno', width: 8, align: 'right',},
		{ label: 'Document Date', name: 'db_entrydate', width: 12, canSearch: true},
		{ label: 'Amount', name: 'db_amount', width: 12, classes: 'wrap', align: 'right', formatter:'currency'},
		{ label: 'Outamount', name: 'db_outamount', width: 12, classes: 'wrap', align: 'right', formatter:'currency'},
		{ label: 'Status', name: 'db_recstatus', width: 12 },
		{ label: 'source', name: 'db_source', width: 10, hidden: true },
		{ label: 'Trantype', name: 'db_trantype', width: 8, canSearch: true, },
		{ label: 'lineno_', name: 'db_lineno_', width: 10, hidden: true },
		{ label: 'db_orderno', name: 'db_orderno', width: 10, hidden: true },
		{ label: 'debtortype', name: 'db_debtortype', width: 20, hidden: true },
		{ label: 'billdebtor', name: 'db_billdebtor', width: 20, hidden: true },
		{ label: 'approvedby', name: 'db_approvedby', width: 20, hidden: true },
		{ label: 'MRN', name: 'db_mrn', width: 7, align: 'right', canSearch: true,},
		{ label: 'unit', name: 'db_unit', width: 10, hidden: true },
		{ label: 'termmode', name: 'db_termmode', width: 10, hidden: true },
		{ label: 'paytype', name: 'db_hdrtype', width: 10, hidden: true },
		{ label: 'db_posteddate', name: 'db_posteddate',hidden: true,},
		{ label: 'Department Code', name: 'db_deptcode', width: 15, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail },
		{ label: 'idno', name: 'db_idno', width: 10, hidden: true, key:true },
		{ label: 'adduser', name: 'db_adduser', width: 10, hidden: true },
		{ label: 'adddate', name: 'db_adddate', width: 10, hidden: true },
		{ label: 'upduser', name: 'db_upduser', width: 10, hidden: true },
		{ label: 'upddate', name: 'db_upddate', width: 10, hidden: true },
		{ label: 'Remark', name: 'db_remark', width: 20, classes: 'wrap', hidden: true },
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
			if(selrowData("#jqGrid").db_trantype=='CN'){
				urlParam2_CN.source = selrowData("#jqGrid").db_source;
				urlParam2_CN.trantype = selrowData("#jqGrid").db_trantype;
				urlParam2_CN.auditno = selrowData("#jqGrid").db_auditno;
				urlParam2_CN.filterVal[1]=selrowData("#jqGrid").db_auditno;
			}else if(selrowData("#jqGrid").db_trantype=='IN'){
				urlParam2_IN.source = selrowData("#jqGrid").db_source;
				urlParam2_IN.trantype = selrowData("#jqGrid").db_trantype;
				urlParam2_IN.billno = selrowData("#jqGrid").db_auditno;
				urlParam2_IN.deptcode = selrowData("#jqGrid").db_deptcode;
				urlParam2_IN.filterVal[1]=selrowData("#jqGrid").db_auditno;
			}else if(selrowData("#jqGrid").db_trantype=='DN'){
				urlParam2_DN.source = selrowData("#jqGrid").db_source;
				urlParam2_DN.trantype = selrowData("#jqGrid").db_trantype;
				urlParam2_DN.auditno = selrowData("#jqGrid").db_auditno;
				urlParam2_DN.filterVal[1]=selrowData("#jqGrid").db_auditno;
			}
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='View Selected Row']").click();
		},
		gridComplete: function () {
			if($('#jqGrid').data('inputfocus') == 'customer_search'){
				$("#customer_search").focus();
				$('#jqGrid').data('inputfocus','');
				$('#customer_search_hb').text('');
				removeValidationClass(['#customer_search']);
			}else if($('#jqGrid').data('inputfocus') == 'department_search'){
				$("#department_search").focus();
				$('#jqGrid').data('inputfocus','');
				$('#department_search_hb').text('');
				removeValidationClass(['#department_search']);
			}else{
				$("#searchForm input[name=Stext]").focus();
			}
			
			//$("#searchForm input[name=Stext]").focus();
			fdl.set_array().reset();
		},
		
	});

	////////////////////// set label jqGrid right ///////////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid2");

	
	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid', '#jqGridPager', {
		view: false, edit: false, add: false, del: false, search: false,
		beforeRefresh: function () {
			refreshGrid("#jqGrid", urlParam);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-info-sign",
		title:"View Selected Row",  
		onClickButton: function(){
			oper='view';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			if(selrowData("#jqGrid").db_trantype=='CN'){
				populateFormdata("#jqGrid","#dialogForm_CN","#formdata_CN",selRowId,'view');
				refreshGrid("#jqGrid2_CN", urlParam2_CN);
			}else if(selrowData("#jqGrid").db_trantype=='IN'){
				populateFormdata("#jqGrid", "#dialogForm_IN", "#formdata_IN", selRowId, 'view', '');
				refreshGrid("#jqGrid2_IN",urlParam2_IN,'add');
			}else if(selrowData("#jqGrid").db_trantype=='DN'){
				populateFormdata("#jqGrid", "#dialogForm_DN", "#formdata_DN", selRowId, 'view', '');
				refreshGrid("#jqGrid2_DN",urlParam2_DN,'add');
			}
		},
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	///////////////////// parameter for jqgrid url ///////////////////////////////////////////////////////

	//// CN
	var urlParam2_CN = {
		action: 'get_table_dtl',
		url:'CreditNoteARDetail/table',
		source:'',
		trantype:'',
		auditno:'',
		field:['dbactdtl.compcode','dbactdtl.source','dbactdtl.trantype','dbactdtl.auditno','dbactdtl.lineno_','dbactdtl.deptcode','dbactdtl.category','dbactdtl.document', 'dbactdtl.AmtB4GST', 'dbactdtl.GSTCode', 'dbactdtl.amount', 'dbactdtl.grnno', 'dbactdtl.amtslstax as tot_gst'],
		table_name:['debtor.dbactdtl AS dbactdtl'],
		table_id:'lineno_',
		filterCol:['dbactdtl.compcode','dbactdtl.auditno', 'dbactdtl.recstatus','dbactdtl.source','dbactdtl.trantype'],
		filterVal:['session.compcode', '', '<>.DELETE', 'PB', 'CN']
	};

	////////////////////////////////////////////////jqgrid2//////////////////////////////////////////////
	$("#jqGrid2_CN").jqGrid({
		datatype: "local",
		editurl: "./CreditNoteARDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'AuditNo', name: 'auditno', hidden: true},
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true, editable:false},
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true, editable:false},
			{ label: 'Department', name: 'deptcode', width: 150, classes: 'wrap', canSearch: true, editable: false
			},
			{ label: 'Category', name: 'category', width: 150, edittype:'text', classes: 'wrap', editable: false,
			},
			{ label: 'Document', name: 'document', width: 150, classes: 'wrap', editable: false
			},
			{ label: 'GST Code', name: 'GSTCode', width: 100, classes: 'wrap', editable: false
			},
			{ label: 'Amount', name: 'amount', width: 90, classes: 'wrap', 
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: false,
				align: "right",
			},
			{ label: 'Amount Before GST', name: 'AmtB4GST', width: 90, classes: 'wrap',
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: false,
				align: "right",
			},
			{ label: 'Total Tax Amount', name: 'tot_gst', width: 90, align: 'right', classes: 'wrap', editable:false},
			{ label: 'rate', name: 'rate', width: 50, classes: 'wrap', hidden:true},
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
			calc_jq_height_onchange("jqGrid2_CN");
		},
		gridComplete: function(){
		},
		beforeSubmit: function (postdata, rowid) {
		}
	});

	///IN
	var urlParam2_IN={
		action: 'get_table_dtl',
		url:'SalesOrderDetail/table',
		source:'',
		trantype:'',
		auditno:'',
		deptcode:''
	};

	$("#jqGrid2_IN").jqGrid({
		datatype: "local",
		editurl: "SalesOrderDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'No', name: 'lineno_', width: 50, classes: 'wrap', editable: false, hidden: true },
			{
				label: 'Item Code', name: 'chggroup', width: 200, classes: 'wrap', editable: false,
			},
			{ label: 'Item Description', name: 'description', width: 180, classes: 'wrap', editable: false, hidden:true },
			{
				label: 'UOM Code', name: 'uom', width: 150, classes: 'wrap', editable: false,
			},{
				label: 'Tax', name: 'taxcode', width: 100, classes: 'wrap', editable: false,
			},
			{
				label: 'Unit Price', name: 'unitprice', width: 100, classes: 'wrap txnum', align: 'right',
				editable: false,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, },
			},
			{
				label: 'Quantity', name: 'quantity', width: 100, align: 'right', classes: 'wrap txnum',
				editable: false,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
			},
			{
				label: 'Quantity on Hand', name: 'qtyonhand', width: 100, align: 'right', classes: 'wrap txnum',
				editable: false,
				formatter: 'integer', formatoptions: { thousandsSeparator: ",", },
			},
			{
				label: 'Bill Type <br>%', name: 'billtypeperct', width: 100, align: 'right', classes: 'wrap txnum',
				editable: false,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, },
			},
			{
				label: 'Bill Type <br>Amount ', name: 'billtypeamt', width: 100, align: 'right', classes: 'wrap txnum', editable: false,
				formatter: 'currency', formatoptions: { thousandsSeparator: ",", },
			},
			{ label: 'Total Amount <br>Before Tax', name: 'amtb4tax', width: 100, align: 'right', classes: 'wrap txnum', editable:false,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
			},
			{
				label: 'Tax Amount', name: 'taxamt', width: 100, align: 'right', classes: 'wrap txnum',
				editable: false,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, },
			},
			{ label: 'Total Amount', name: 'amount', width: 100, align: 'right', classes: 'wrap txnum', editable:false,
				formatter:'currency',formatoptions:{thousandsSeparator: ",",},
			},
			{ label: 'recstatus', name: 'recstatus', width: 80, classes: 'wrap', hidden: true },
			{ label: 'id', name: 'id', width: 10, hidden: true, key:true },
		],
		autowidth: true,
		shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce: false,
		width: 1150,
		height: 200,
		rowNum: 10,
		sortname: 'id',
		sortorder: "desc",
		pager: "#jqGridPager2",
		loadComplete: function(data){
			setjqgridHeight(data,'jqGrid2_IN');					
			calc_jq_height_onchange("jqGrid2_IN");
		},
		
		gridComplete: function(){

		},
		afterShowForm: function (rowid) {

		},
		beforeSubmit: function (postdata, rowid) {

		}
    });

	//////// DN
	var urlParam2_DN = {
		action: 'get_table_dtl',
		url:'DebitNoteDetail/table',
		source:'',
		trantype:'',
		auditno:'',
		field:['dbactdtl.compcode','dbactdtl.source','dbactdtl.trantype','dbactdtl.auditno','dbactdtl.lineno_','dbactdtl.deptcode','dbactdtl.category','dbactdtl.document', 'dbactdtl.AmtB4GST', 'dbactdtl.GSTCode', 'dbactdtl.amount', 'dbactdtl.grnno', 'dbactdtl.amtslstax as tot_gst'],
		table_name:['debtor.dbactdtl AS dbactdtl'],
		table_id:'lineno_',
		filterCol:['dbactdtl.compcode','dbactdtl.auditno', 'dbactdtl.recstatus','dbactdtl.source','dbactdtl.trantype'],
		filterVal:['session.compcode', '', '<>.DELETE', 'PB', 'DN']
	};

	$("#jqGrid2_DN").jqGrid({
		datatype: "local",
		editurl: "./DebitNoteDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'AuditNo', name: 'auditno', hidden: true},
            { label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true, editable:false},
            { label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true, editable:false},
            { label: 'Department', name: 'deptcode', width: 150, classes: 'wrap', canSearch: true, editable: false },
            { label: 'Category', name: 'category', width: 150, edittype:'text', classes: 'wrap', editable: false},
            { label: 'Document', name: 'document', width: 150, classes: 'wrap', editable: false },
            { label: 'GST Code', name: 'GSTCode', width: 100, classes: 'wrap', editable: false},
			{ label: 'Amount', name: 'amount', width: 90, classes: 'wrap', 
				formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
				editable: false,
				align: "right",
			},
            { label: 'Amount Before GST', name: 'AmtB4GST', width: 90, classes: 'wrap',
                formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},
                editable: false,
                align: "right",
            },
			{ label: 'Total Tax Amount', name: 'tot_gst', width: 90, align: 'right', classes: 'wrap', editable:false,
				formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, },},
            { label: 'rate', name: 'rate', width: 50, classes: 'wrap', hidden:true},
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
			setjqgridHeight(data,'jqGrid2_DN');			
			calc_jq_height_onchange("jqGrid2_DN");
		},		
		gridComplete: function(){
		},
		beforeSubmit: function (postdata, rowid) {
		}

		}).bind("jqGridLoadComplete jqGridInlineEditRow jqGridAfterEditCell jqGridAfterRestoreCell jqGridInlineAfterRestoreRow jqGridAfterSaveCell jqGridInlineAfterSaveRow", function () {
        fixPositionsOfFrozenDivs.call(this);
    });

	//////////handle searching, its radio button and toggle /////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field, table, case_;
		switch(options.colModel.name){
			case 'db_debtorcode':field=['debtorcode','name'];table="debtor.debtormast";case_='db_debtorcode';break;
			case 'db_deptcode':field=['deptcode','description'];table="sysdb.department";case_='db_deptcode';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('arenquiry',options,param,case_,cellvalue);
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
		return $('<div class="input-group"><input jqgrid="jqGrid2" optid="'+opt.id+'" id="'+opt.id+'" name="GSTCode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a><input id="'+opt.id+'_gstpercent" name="gstpercent" type="hidden"></div><span class="help-block"></span>');
	}
	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
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
			searchClick2('#jqGrid', '#searchForm', urlParam);
		});
	}

	$('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	$('#docuDate_search').on('click', searchDate);

	function whenchangetodate() {
		customer_search.off();
		department_search.off();
		$('#customer_search,#docuDate_from,#docuDate_to,#department_search').val('');
		$('#customer_search_hb').text('');
		$('#department_search_hb').text('');
		urlParam.filterdate = null;
		removeValidationClass(['#customer_search,#department_search']);
		if($('#Scol').val()=='db_entrydate'){
			$("input[name='Stext'], #customer_text, #department_text").hide("fast");
			$("#docuDate_text").show("fast");
		} else if($('#Scol').val() == 'dm_name'){
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
	}

	function searchDate(){
		urlParam.filterdate = [$('#docuDate_from').val(),$('#docuDate_to').val()];
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
		},{fct:['db.recstatus'],fv:[],fc:[]});

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
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

				if($('#Scol').val() == 'dm_name'){
					urlParam.searchCol=["db.debtorcode"];
					urlParam.searchVal=[data];
				}
				// }else if($('#Scol').val() == 'db_payercode'){
				// 	urlParam.searchCol=["db.payercode"];
				// 	urlParam.searchVal=[data];
				// }
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
				customer_search.urlParam.filterCol = ['recstatus'];
				customer_search.urlParam.filterVal = ['ACTIVE'];
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
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow: function () {
				let data = selrowData('#' + department_search.gridname).deptcode;

				if($('#Scol').val() == 'db_deptcode'){
					urlParam.searchCol=["db.deptcode"];
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
				department_search.urlParam.filterCol = ['recstatus'];
				department_search.urlParam.filterVal = ['ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	department_search.makedialog(true);
	$('#department_search').on('keyup',ifnullsearch);
	
	function setjqgridHeight(data,grid){
		if(data.rows.length>=6){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(500);
		}else if(data.rows.length>=3){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(300);
		}else{
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(200);
		}
	}

	function ifnullsearch(){
		if($(this).val() == ''){
			urlParam.searchCol=[];
			urlParam.searchVal=[];
			$('#jqGrid').data('inputfocus',$(this).attr('id'));
			refreshGrid('#jqGrid', urlParam);
		}
	}
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
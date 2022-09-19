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
	
	// ///////////////////////////////// trandate check date validate from period////////// ////////////////
	// var entrydateObj = new setentrydate(["#db_entrydate"]);  ///(from actdateObj -> entrydateObj), setactdate->setentrydate, apacthdr_actdate->db_entrydate
	// entrydateObj.getdata().set();

	////////////////////////////////////start dialog//////////////////////////////////////
	// var oper=null;
	// var unsaved = false;
	$("#dialogForm")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				errorField.length=0;
				mycurrency.formatOnBlur();
			},
			close: function( event, ui ) {
				$("#refresh_jqGrid").click();
			},
				buttons :[{
					text: "Close",click: function() {
						$(this).dialog('close');
				}
			}],
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
		{ label: 'Debtor Code', name: 'db_debtorcode', width: 15, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail},
		{ label: 'Payer Code', name: 'db_payercode', width: 15, hidden: true},
		{ label: 'Customer', name: 'dm_name', width: 50, checked: true, classes: 'wrap' },
		{ label: 'Audit No', name: 'db_auditno', width: 12, align: 'right', classes: 'wrap',formatter: padzero, unformat: unpadzero, canSearch: true},
		{ label: 'Sector', name: 'db_unit', width: 15, hidden: true, classes: 'wrap' },
		{ label: 'PO No', name: 'db_ponum', width: 10, formatter: padzero5, unformat: unpadzero },
		{ label: 'Invoice No', name: 'db_invno', width: 10},
		{ label: 'Document Date', name: 'db_entrydate', width: 15, canSearch: true},
		{ label: 'Amount', name: 'db_amount', width: 15, classes: 'wrap', align: 'right', formatter:'currency'},
		{ label: 'Outamount', name: 'db_outamount', width: 15, classes: 'wrap', align: 'right', formatter:'currency'},
		{ label: 'Status', name: 'db_recstatus', width: 15 },
		{ label: 'source', name: 'db_source', width: 10, hidden: true },
		{ label: 'Trantype', name: 'db_trantype', width: 20, canSearch: true, },
		{ label: 'lineno_', name: 'db_lineno_', width: 20, hidden: true },
		{ label: 'db_orderno', name: 'db_orderno', width: 10, hidden: true },
		{ label: 'debtortype', name: 'db_debtortype', width: 20, hidden: true },
		{ label: 'billdebtor', name: 'db_billdebtor', width: 20, hidden: true },
		{ label: 'approvedby', name: 'db_approvedby', width: 20, hidden: true },
		{ label: 'MRN', name: 'db_mrn', width: 10, canSearch: true,},
		{ label: 'unit', name: 'db_unit', width: 10, hidden: true },
		{ label: 'termmode', name: 'db_termmode', width: 10, hidden: true },
		{ label: 'paytype', name: 'db_hdrtype', width: 10, hidden: true },
		{ label: 'source', name: 'db_source', width: 10, hidden: true },
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

		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='View Selected Row']").click();
		},
		gridComplete: function () {
			if($('#jqGrid').data('inputfocus') == 'customer_search'){
				$("#customer_search").focus();
				$('#jqGrid').data('inputfocus','');
				$('#customer_search_hb').text('');
				removeValidationClass(['#creditor_search']);
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
	})

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle /////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid', true, urlParam);

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field, table, case_;
		switch(options.colModel.name){
			case 'db_debtorcode':field=['debtorcode','name'];table="debtor.debtormast";case_='debtorcode';break;
			case 'db_deptcode':field=['deptcode','description'];table="sysdb.department";case_='db_deptcode';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('arenquiry',options,param,case_,cellvalue);
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
	
	function ifnullsearch(){
		if($(this).val() == ''){
			urlParam.searchCol=[];
			urlParam.searchVal=[];
			$('#jqGrid').data('inputfocus',$(this).attr('id'));
			refreshGrid('#jqGrid', urlParam);
		}
	}
});

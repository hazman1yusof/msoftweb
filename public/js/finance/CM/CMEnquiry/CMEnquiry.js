$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

var errorField=[];
$(document).ready(function () {
	$('body').show();
	/////////////////////////validation//////////////////////////
	$.validate({
		modules : 'sanitize',
		language : {
			requiredFields: ''
		},
	});
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
	/////////////////////////////////// currency ///////////////////////////////
	var fdl = new faster_detail_load();
	var mycurrency =new currencymode(['#apacthdr_outamount', '#apacthdr_amount']);
	var mycurrency2 =new currencymode(['#apacthdr_outamount', '#apacthdr_amount']);
	////////////////////////////////////start dialog///////////////////////////////////////
	var oper=null;

	$("#dialogForm_ft")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				errorField.length=0;
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				disableForm('#formdata_ft');
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_ft');
				$('.my-alert').detach();
				$("#formdata_ft a").off();
				$(".noti").empty();
				errorField.length=0;
			},
	});

	$("#dialogForm_dp")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				$("#jqGrid2_dp").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_dp_c")[0].offsetWidth-$("#jqGrid2_dp_c")[0].offsetLeft));
				errorField.length=0;
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				disableForm('#formdata_dp');
				$("#pg_jqGridPager2 table").hide();
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_dp');
				$('.my-alert').detach();
				$("#formdata_dp a").off();
				$(".noti, .noti2 ol").empty();
				refreshGrid("#jqGrid2_dp",null,"kosongkan");
				errorField.length=0;
			},
	});

	$("#dialogForm_ca")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				$("#jqGrid2_ca").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_ca_c")[0].offsetWidth-$("#jqGrid2_ca_c")[0].offsetLeft));
				errorField.length=0;
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				disableForm('#formdata_ca');
				$("#pg_jqGridPager2 table").hide();
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_ca');
				$('.my-alert').detach();
				$("#formdata_ca a").off();
				$(".noti, .noti2 ol").empty();
				refreshGrid("#jqGrid2_ca",null,"kosongkan");
				errorField.length=0;
			},
	});

	$("#dialogForm_da")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				$("#jqGrid2_da").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_da_c")[0].offsetWidth-$("#jqGrid2_da_c")[0].offsetLeft));
				errorField.length=0;
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				disableForm('#formdata_da');
				$("#pg_jqGridPager2 table").hide();
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_da');
				$('.my-alert').detach();
				$("#formdata_da a").off();
				$(".noti, .noti2 ol").empty();
				refreshGrid("#jqGrid2_da",null,"kosongkan");
				errorField.length=0;
			},
	});

	////////////////////////////////////////end dialog///////////////////////////////////////////

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'maintable',
		url:'./CMEnquiry/table',
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'Bank Code', name: 'apacthdr_bankcode', width: 80, classes: 'wrap text-uppercase',  canSearch: true, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Audit No', name: 'apacthdr_auditno', width: 18, classes: 'wrap',formatter: padzero, unformat: unpadzero, canSearch: true, align:'right'},
			{ label: 'Transaction <br>Type', name: 'apacthdr_trantype', width: 25, classes: 'wrap text-uppercase', canSearch: true},
            { label: 'PV No', name: 'apacthdr_pvno', width: 18, classes: 'wrap', formatter: padzero, unformat: unpadzero,hidden:false, canSearch: true, align:'right'},
            { label: 'Cheque No', name: 'apacthdr_cheqno', width: 30, classes: 'wrap text-uppercase', hidden:false},
            { label: 'Paymode', name: 'apacthdr_paymode', width: 30, classes: 'wrap text-uppercase', hidden:false},
            { label: 'Document Date', name: 'apacthdr_actdate', width: 25, classes: 'wrap text-uppercase', canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter},
            { label: 'Amount', name: 'apacthdr_amount', width: 25, classes: 'wrap', align: 'right', formatter:'currency'},
			{ label: 'Pay To', name: 'apacthdr_payto', width: 50, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'Outstanding', name: 'apacthdr_outamount', width: 25, hidden:true, classes: 'wrap', align: 'right', formatter:'currency'},
            { label: 'Status', name: 'apacthdr_recstatus', width: 25, classes: 'wrap text-uppercase', hidden:true},
            { label: 'Document No', name: 'apacthdr_document', width: 50, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'Unit', name: 'apacthdr_unit', width: 30, hidden:true},
			{ label: 'Supplier Code', name: 'apacthdr_suppcode', width: 70, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'Category Code', name: 'apacthdr_category', width: 40, hidden:true, classes: 'wrap'},		
			{ label: 'doctype', name: 'apacthdr_doctype', width: 10, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'Creditor Name', name: 'supplier_name', width: 50, classes: 'wrap text-uppercase', checked: true, hidden: true},
			{ label: 'Department', name: 'apacthdr_deptcode', width: 25, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'Post Date', name: 'apacthdr_recdate', width: 35, classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter, hidden:true},
			{ label: 'remarks', name: 'apacthdr_remarks', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adduser', name: 'apacthdr_adduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adddate', name: 'apacthdr_adddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upduser', name: 'apacthdr_upduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upddate', name: 'apacthdr_upddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'compcode', name: 'apacthdr_compcode', width: 40, hidden:true},
			{ label: 'source', name: 'apacthdr_source', width: 40, hidden:true},
			{ label: 'idno', name: 'apacthdr_idno', width: 40, hidden:true, key:true},
			{ label: 'unallocated', name: 'unallocated', width: 50, classes: 'wrap', hidden:true},
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 900,
		height: 250,
		rowNum: 30,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			$('#jqGrid3_div_ft,#jqGrid3_div_dp, #jqGrid3_div_ca, #jqGrid3_div_da').hide();
			
			if(selrowData("#jqGrid").apacthdr_trantype=='DP'){
				$('#jqGrid3_div_dp').show();
				urlParam2_dp.filterVal[1]=selrowData("#jqGrid").apacthdr_auditno;
				$("#jqGrid3_dp").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_div_dp")[0].offsetWidth-$("#jqGrid3_div_dp")[0].offsetLeft));
				refreshGrid("#jqGrid3_dp",urlParam2_dp);
				
			}else if(selrowData("#jqGrid").apacthdr_trantype=='FT'){
				$('#jqGrid3_div_ft').show();
				urlParam3_ft.filterVal[3] = selrowData("#jqGrid").apacthdr_idno;
				$("#jqGrid3_ft").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_div_ft")[0].offsetWidth-$("#jqGrid3_div_ft")[0].offsetLeft));
				refreshGrid("#jqGrid3_ft",urlParam3_ft);

			}else if(selrowData("#jqGrid").apacthdr_trantype=='CA'){
				$('#jqGrid3_div_ca').show();
				urlParam2_ca.filterVal[1]=selrowData("#jqGrid").apacthdr_auditno;
				$("#jqGrid3_ca").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_div_ca")[0].offsetWidth-$("#jqGrid3_div_ca")[0].offsetLeft));
				refreshGrid("#jqGrid3_ca",urlParam2_ca);

			}else if(selrowData("#jqGrid").apacthdr_trantype=='DA'){
				$('#jqGrid3_div_da').show();
				urlParam2_da.filterVal[1]=selrowData("#jqGrid").apacthdr_auditno;
				$("#jqGrid3_da").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_div_da")[0].offsetWidth-$("#jqGrid3_div_da")[0].offsetLeft));
				refreshGrid("#jqGrid3_da",urlParam2_da);
			}
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			let stat = selrowData("#jqGrid").apacthdr_recstatus;
			$("#jqGridPager td[title='View Selected Row']").click();
		},
		gridComplete: function(){
			if (oper == 'add' || oper == null || $("#jqGrid").data('lastselrow') == undefined) {
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}else{
				$("#jqGrid").setSelection($("#jqGrid").data('lastselrow'));
				delay(function(){
					$('#jqGrid tr#'+$("#jqGrid").data('lastselrow')).focus();
				}, 300 );
			}
			
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
			//calc_jq_height_onchange("jqGrid");
		},
		
	});

	/////////////////////////padzero/////////////////////////
	function padzero(cellvalue, options, rowObject){
		let padzero = 7, str="";
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
			$("#jqGrid").data('lastselrow',selRowId);
			if(selrowData("#jqGrid").apacthdr_trantype=='FT'){
				// populateFormdata("#jqGrid", "#dialogForm_ft", "#formdata_ft", selRowId, 'view', '');
				getdata('FT',selrowData("#jqGrid").apacthdr_idno);
			}else if(selrowData("#jqGrid").apacthdr_trantype=='DP'){
			 	populateFormdata("#jqGrid", "#dialogForm_dp", "#formdata_dp", selRowId, 'view', '');
				getdata('DP',selrowData("#jqGrid").apacthdr_idno);
				refreshGrid("#jqGrid2_dp",urlParam2_dp,'add');
			}else if(selrowData("#jqGrid").apacthdr_trantype=='CA'){
				populateFormdata("#jqGrid", "#dialogForm_ca", "#formdata_ca", selRowId, 'view', '');
				getdata('CA',selrowData("#jqGrid").apacthdr_idno);
				refreshGrid("#jqGrid2_ca",urlParam2_ca,'add');
			}else if(selrowData("#jqGrid").apacthdr_trantype=='DA'){
				populateFormdata("#jqGrid", "#dialogForm_da", "#formdata_da", selRowId, 'view', '');
				getdata('DA',selrowData("#jqGrid").apacthdr_idno);
				refreshGrid("#jqGrid2_da",urlParam2_da,'add');
			}
		}, 
	});

	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);

	////////////////////// set label jqGrid right ///////////////////////////////////////////////////////
	jqgrid_label_align_right("#jqGrid");
	/////////////////////////////////////////////////////////////////////////////////////////////////////

	//////////////////////////////////////formatter checkdetail//////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field, table, case_;
		switch(options.colModel.name){
			case 'apacthdr_bankcode':field=['bankcode','bankname'];table="finance.bank";case_='apacthdr_bankcode';break;

			//FT
			case 'paymode':field=['paymode','description'];table="debtor.paymode";case_='paymode';break;
			case 'payto':field=['bankcode','bankname'];table="finance.bank";case_='payto';break;
			case 'bankcode':field=['bankcode','bankname'];table="finance.bank";case_='bankcode';break;
			
			//DP
			case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
			case 'category':field=['catcode','description'];table="material.category";case_='category';break;
			case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";case_='GSTCode';break;
			case 'payto':field=['suppcode','name'];table="material.supplier";case_='payto';break;
			case 'bankcode':field=['bankcode','bankname'];table="finance.bank";case_='bankcode';break;

			//CA
			case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
			case 'category':field=['catcode','description'];table="material.category";case_='category';break;
			case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";case_='GSTCode';break;
			case 'bankcode':field=['bankcode','bankname'];table="finance.bank";case_='bankcode';break;

			//DA
			case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
			case 'category':field=['catcode','description'];table="material.category";case_='category';break;
			case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";case_='GSTCode';break;
			case 'bankcode':field=['bankcode','bankname'];table="finance.bank";case_='bankcode';break;
		}
		var param={
			action:'input_check',
			url:'util/get_value_default',
			table_name:table,
			field:field,
			value:cellvalue,
			filterCol:[field[0]],
			filterVal:[cellvalue]
		};
	
		fdl.get_array('CMEnquiry',options,param,case_,cellvalue);
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
		} else if($('#Scol').val() == 'apacthdr_bankcode'){
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
		'creditor_search', 'finance.bank', '#creditor_search', 'errorField',
		{
			colModel: [
				{ label: 'Bank Code', name: 'bankcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Bank Name', name: 'bankname', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			ondblClickRow: function () {
				let data = selrowData('#' + creditor_search.gridname).suppcode;

				if($('#Scol').val() == 'apacthdr_bankcode'){
					urlParam.searchCol=["ap.bankcode"];
					urlParam.searchVal=[data];
				// }else if($('#Scol').val() == 'apacthdr_payto'){
				// 	urlParam.searchCol=["ap.payto"];
				// 	urlParam.searchVal=[data];
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
			title: "Select Bank Code",
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

	var urlParam3_ft={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		table_name:'finance.apacthdr',
		table_id:'idno',
		filterCol: ['source', 'trantype', 'compcode','idno'],
		filterVal: ['CM', 'FT', 'session.compcode',''],
	}

	$("#jqGrid3_ft").jqGrid({
		datatype: "local",
		colModel: [
		 	{label: 'compcode', name: 'compcode', width: 10 , hidden: true,  classes: 'wrap'},
		 	{label: 'idno', name: 'idno', width: 10 , hidden: true,  classes: 'wrap'},
		 	{label: 'source', name: 'source', width: 10, hidden: true, classes: 'wrap'},
		 	{label: 'trantype', name: 'trantype', width: 10, hidden: true, classes: 'wrap'},
			{label: 'Audit No', name: 'auditno', width: 5, checked: true, classes: 'wrap' },
			{label: 'Payment No', name: 'pvno', width: 20, classes: 'wrap'},
			{label: 'Transfer Date', name: 'actdate', width: 20, classes: 'wrap'},
			{label: 'Bank Code To', name: 'payto', width: 80, classes: 'wrap', formatter: showdetail,unformat:un_showdetail},
			{label: 'Cheque Date', name: 'cheqdate', width: 90, classes: 'wrap', hidden:true},
			{label: 'Amount', name: 'amount', width: 30, classes: 'wrap', formatter:'currency'},
		],
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 900,
		height: 300,
		rowNum: 30,
		pager: "#jqGridPager3",
		onSelectRow:function(rowid, selected){
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
		loadComplete:function(data){
			calc_jq_height_onchange("jqGrid3_ft");
		}			
	});

	var urlParam2_dp={
		action:'get_table_default',
		url:'/util/get_table_default',
		field:['apactdtl.compcode','apactdtl.source','apactdtl.trantype','apactdtl.auditno','apactdtl.lineno_','apactdtl.deptcode','apactdtl.category','apactdtl.document', 'apactdtl.AmtB4GST', 'apactdtl.GSTCode', 'apactdtl.amount', 'apactdtl.dorecno', 'apactdtl.grnno'],
		table_name:['finance.apactdtl AS apactdtl'],
		table_id:'lineno_',
		filterCol:['apactdtl.compcode','apactdtl.auditno', 'apactdtl.recstatus','apactdtl.source','apactdtl.trantype'],
		filterVal:['session.compcode', '', '<>.DELETE', 'CM', 'DP']
	};

	$("#jqGrid2_dp").jqGrid({
		datatype: "local",
		editurl: "/directPaymentDetail/form",
		colModel: [
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true, editable:false},
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true, editable:false},
			{ label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden:true, editable:false},
			{ label: 'Line No', name: 'lineno_', width: 80, classes: 'wrap', hidden:true, editable:false}, 
			{ label: 'Department', name: 'deptcode', width: 100, classes: 'wrap', canSearch: true, editable: false, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Category', name: 'category', width: 100, edittype:'text', classes: 'wrap', editable: false, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Document', name: 'document', width: 100, classes: 'wrap', editable: false},
			{ label: 'GST Code', name: 'GSTCode', width: 100, edittype:'text', classes: 'wrap', editable: false, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Amount Before GST', name: 'AmtB4GST', width: 80, classes: 'wrap', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},editable: false,align: "right",},
			{ label: 'Total GST Amount', name: 'tot_gst', width: 80, align: 'right', classes: 'wrap', editable:false,formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, },},
			{ label: 'rate', name: 'rate', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Amount', name: 'amount', width: 80, classes: 'wrap', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}, editable: false, align:"right"},
		],
		autowidth: true,
		//shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager2_dp",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid2_dp");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
		beforeSubmit: function(postdata, rowid){ 
	 	}
	});

	$("#jqGrid3_dp").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2_dp").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3_dp",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid3_dp");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		}
	});
	jqgrid_label_align_right("#jqGrid3_dp");

    ////CREDIT

	var urlParam2_ca={
		action:'get_table_default',
		url:'/util/get_table_default',
		field:['apactdtl.compcode','apactdtl.source','apactdtl.trantype','apactdtl.auditno','apactdtl.lineno_','apactdtl.deptcode','apactdtl.category','apactdtl.document', 'apactdtl.AmtB4GST', 'apactdtl.GSTCode', 'apactdtl.taxamt AS tot_gst', 'apactdtl.amount', 'apactdtl.dorecno', 'apactdtl.grnno', 'apactdtl.idno'],
		table_name:['finance.apactdtl AS apactdtl'],
		table_id:'lineno_',
		filterCol:['apactdtl.compcode','apactdtl.auditno', 'apactdtl.recstatus','apactdtl.source','apactdtl.trantype'],
		filterVal:['session.compcode', '', '<>.DELETE', 'CM', 'CA']
	};

	$("#jqGrid2_ca").jqGrid({
		datatype: "local",
		editurl: "/creditDebitTransDetail/form",
		colModel: [
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true, editable:false},
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true, editable:false},
			{ label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden:true, editable:false},
			{ label: 'Line No', name: 'lineno_', width: 80, classes: 'wrap', hidden:true, editable:false}, 
			{ label: 'Department', name: 'deptcode', width: 100, classes: 'wrap', canSearch: true, editable: false, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Category', name: 'category', width: 100, edittype:'text', classes: 'wrap', editable: false, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Document', name: 'document', width: 100, classes: 'wrap', editable: false},
			{ label: 'GST Code', name: 'GSTCode', width: 100, edittype:'text', classes: 'wrap', editable: false, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Amount Before GST', name: 'AmtB4GST', width: 80, classes: 'wrap', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},editable: false,align: "right",},
			{ label: 'Total GST Amount', name: 'tot_gst', width: 80, align: 'right', classes: 'wrap', editable:false,formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, },},
			{ label: 'rate', name: 'rate', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Amount', name: 'amount', width: 80, classes: 'wrap', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}, editable: false, align:"right"},
		],
		autowidth: true,
		//shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager2_ca",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid2_ca");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
		beforeSubmit: function(postdata, rowid){ 
	 	}
	});

	$("#jqGrid3_ca").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2_ca").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3_ca",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid3_ca");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		}
	});
	jqgrid_label_align_right("#jqGrid3_ca");

	////DEBIT

	var urlParam2_da={
		action:'get_table_default',
		url:'/util/get_table_default',
		field:['apactdtl.compcode','apactdtl.source','apactdtl.trantype','apactdtl.auditno','apactdtl.lineno_','apactdtl.deptcode','apactdtl.category','apactdtl.document', 'apactdtl.AmtB4GST', 'apactdtl.GSTCode', 'apactdtl.taxamt AS tot_gst', 'apactdtl.amount', 'apactdtl.dorecno', 'apactdtl.grnno', 'apactdtl.idno'],
		table_name:['finance.apactdtl AS apactdtl'],
		table_id:'lineno_',
		filterCol:['apactdtl.compcode','apactdtl.auditno', 'apactdtl.recstatus','apactdtl.source','apactdtl.trantype'],
		filterVal:['session.compcode', '', '<>.DELETE', 'CM', 'DA']
	};

	$("#jqGrid2_da").jqGrid({
		datatype: "local",
		editurl: "/creditDebitTransDetail/form",
		colModel: [
		 	{ label: 'compcode', name: 'compcode', width: 20, classes: 'wrap', hidden:true},
			{ label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true, editable:false},
			{ label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true, editable:false},
			{ label: 'auditno', name: 'auditno', width: 20, classes: 'wrap', hidden:true, editable:false},
			{ label: 'Line No', name: 'lineno_', width: 80, classes: 'wrap', hidden:true, editable:false}, 
			{ label: 'Department', name: 'deptcode', width: 100, classes: 'wrap', canSearch: true, editable: false, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Category', name: 'category', width: 100, edittype:'text', classes: 'wrap', editable: false, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Document', name: 'document', width: 100, classes: 'wrap', editable: false},
			{ label: 'GST Code', name: 'GSTCode', width: 100, edittype:'text', classes: 'wrap', editable: false, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Amount Before GST', name: 'AmtB4GST', width: 80, classes: 'wrap', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,},editable: false,align: "right",},
			{ label: 'Total GST Amount', name: 'tot_gst', width: 80, align: 'right', classes: 'wrap', editable:false,formatter: 'currency', formatoptions: { decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2, },},
			{ label: 'rate', name: 'rate', width: 20, classes: 'wrap', hidden:true},
			{ label: 'Amount', name: 'amount', width: 80, classes: 'wrap', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}, editable: false, align:"right"},
		],
		autowidth: true,
		//shrinkToFit: true,
		multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 1150,
		height: 200,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager2_da",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid2_da");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
		beforeSubmit: function(postdata, rowid){ 
	 	}
	});

	$("#jqGrid3_da").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2_da").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3_da",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid3_da");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		}
	});
	jqgrid_label_align_right("#jqGrid3_da");
});

	function calc_jq_height_onchange(jqgrid){
		let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
		if(scrollHeight<50){
			scrollHeight = 50;
		}else if(scrollHeight>300){
			scrollHeight = 300;
		}
		$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight+30);
	}
	
	///////dialog handler FT///////
	
	var dialog_paymodeFT = new ordialog(
		'paymodeFT','debtor.paymode',"#formdata_ft :input[name='paymode']",'errorField',
		{	colModel:[
				{label:'Pay Mode',name:'paymode',width:200,classes:'pointer'},
				{label:'Description',name:'description',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus', 'source'],
				filterVal:['session.compcode','ACTIVE', 'CM']
			},
		},{
			title:"Select Payment Mode",
			open: function(){
				dialog_paymodeFT.urlParam.filterCol=['compcode','recstatus', 'source'],
				dialog_paymodeFT.urlParam.filterVal=['session.compcode','ACTIVE', 'CM']
			}
		},'urlParam','radio','tab'
	);
	dialog_paymodeFT.makedialog(false);	

	var dialog_bankcodefromFT = new ordialog(
		'bankcodeFT','finance.bank',"#formdata_ft :input[name='bankcode']",'errorField',
		{	colModel:[
				{label:'Bank Code',name:'bankcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'bankname',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Bank Code",
			open: function(){
				dialog_bankcodefromFT.urlParam.filterCol=['compcode','recstatus'],
				dialog_bankcodefromFT.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_bankcodefromFT.makedialog(false);

	var dialog_bankcodetoFT = new ordialog(
		'paytoFT','finance.bank',"#formdata_ft :input[name='payto']",'errorField',
		{	colModel:[
				{label:'Bank Code',name:'bankcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'bankname',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Bank Code Pay To",
			open: function(){
				dialog_bankcodetoFT.urlParam.filterCol=['compcode','recstatus'],
				dialog_bankcodetoFT.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_bankcodetoFT.makedialog(false);

	var dialog_cheqnoFT = new ordialog(
		'cheqnoFT','finance.chqtran',"#formdata_ft :input[name='cheqno']",'errorField',
		{	colModel:[
				{label:'Cheque No',name:'cheqno',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
				{label:'bankcode',name:'bankcode',width:200,classes:'pointer',hidden:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','OPEN']
			},
		},{
			title:"Select Cheque No",
			open: function(){
				dialog_cheqnoFT.urlParam.filterCol=['compcode','recstatus', 'bankcode'],
				dialog_cheqnoFT.urlParam.filterVal=['session.compcode','OPEN', $('#bankcode').val()]
			}
		},'urlParam','radio','tab'
	);
	dialog_cheqnoFT.makedialog(false);

	//DIALOG DP
	var dialog_paymodeDP = new ordialog(
		'paymodeDP','debtor.paymode',"#formdata_dp :input[name='paymode']",'errorField',
		{	colModel:[
				{label:'Pay Mode',name:'paymode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus', 'source'],
				filterVal:['session.compcode','ACTIVE', 'CM']
			},
		},{
			title:"Select Payment Mode",
			open: function(){
				dialog_paymodeDP.urlParam.filterCol=['compcode','recstatus', 'source'],
				dialog_paymodeDP.urlParam.filterVal=['session.compcode','ACTIVE', 'CM']
			}
		},'urlParam','radio','tab'
	);
	dialog_paymodeDP.makedialog(false);

	var dialog_bankcodeDP = new ordialog(
		'bankcodeDP','finance.bank',"#formdata_dp :input[name='bankcode']",'errorField',
		{	colModel:[
				{label:'Bank Code',name:'bankcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'bankname',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Bank Code",
			open: function(){
				dialog_bankcodeDP.urlParam.filterCol=['compcode','recstatus'],
				dialog_bankcodeDP.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_bankcodeDP.makedialog(false);

	var dialog_paytoDP = new ordialog(
		'paytoDP','material.supplier',"#formdata_dp :input[name='payto']",'errorField',
		{	colModel:[
				{label:'Pay To',name:'SuppCode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'Name',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Bank Code Pay To",
			open: function(){
				dialog_paytoDP.urlParam.filterCol=['compcode','recstatus'],
				dialog_paytoDP.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_paytoDP.makedialog(false);

	var dialog_cheqnoDP = new ordialog(
		'cheqnoDP','finance.chqtran',"#formdata_dp :input[name='cheqno']",'errorField',
		{	colModel:[
				{label:'Cheque No',name:'cheqno',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
				{label:'Bank Code',name:'bankcode',width:200,classes:'pointer', hidden:true},
				
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','OPEN']
			},
		},{
			title:"Select Cheque No",
			open: function(){
				dialog_cheqnoDP.urlParam.filterCol=['compcode','recstatus', 'bankcode'],
				dialog_cheqnoDP.urlParam.filterVal=['session.compcode','OPEN', $('#bankcode').val()]
			},
			width:4/10 * $(window).width()
		},'urlParam','radio','tab'
	);
	dialog_cheqnoDP.makedialog(false);

	var dialog_deptcodeDP = new ordialog(
		'deptcodeDP','sysdb.department',"#jqGrid2_dp input[name='deptcode']",'errorField',
		{	colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
				
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Department Code",
			open: function(){
				dialog_deptcodeDP.urlParam.filterCol=['compcode','recstatus'],
				dialog_deptcodeDP.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_deptcodeDP.makedialog(false);

	var dialog_categoryDP = new ordialog(
		'categoryDP','material.category',"#jqGrid2_dp input[name='category']",'errorField',
		{	colModel:[
				{label:'Category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
				
			],
			urlParam: {
				filterCol:['compcode','source', 'cattype', 'recstatus'],
				filterVal:['session.compcode','CR', 'Other', 'ACTIVE']
			},
		},{
			title:"Select Category",
			open: function(){
				dialog_categoryDP.urlParam.filterCol=['compcode','source', 'cattype', 'recstatus'],
				dialog_categoryDP.urlParam.filterVal=['session.compcode','CR', 'Other', 'ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_categoryDP.makedialog(false);

	var dialog_GSTCodeDP = new ordialog(
		'GSTCodeDP',['hisdb.taxmast'],"#jqGrid2_dp input[name='GSTCode']",'errorField',
		{	colModel:
			[
				{label:'Tax code',name:'taxcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label:'Tax Rate',name:'rate',width:200,classes:'pointer'},
			],
			urlParam: {
						filterCol:['compcode','recstatus'],
						filterVal:['session.compcode','ACTIVE']
					},
			
		},{
			title:"Select Tax Code For Item",
			open: function(){
				dialog_GSTCodeDP.urlParam.filterCol=['compcode','recstatus', 'taxtype'];
				dialog_GSTCodeDP.urlParam.filterVal=['session.compcode','ACTIVE', 'Input'];
			},
			close: function(){
				if($('#jqGridPager2SaveAll').css("display") == "none"){
					$(dialog_GSTCodeDP.textfield)			//lepas close dialog focus on next textfield 
					.closest('td')						//utk dialog dalam jqgrid jer
					.next()
					.find("input[type=text]").focus();
				}
				
			}
		},'urlParam','radio','tab'
	);
	dialog_GSTCodeDP.makedialog(false);

	//DIALOG CA
	var dialog_bankcodeCA = new ordialog(
		'bankcodeCA',['finance.bank'],"#formdata_ca input[name='bankcode']",'errorField',
		{	colModel:[
				{label:'Bank Code',name:'bankcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'bankname',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Bank Code",
			open: function(){
				dialog_bankcodeCA.urlParam.filterCol=['compcode','recstatus'],
				dialog_bankcodeCA.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_bankcodeCA.makedialog(false);

	var dialog_deptcodeCA = new ordialog(
		'deptcodeCA','sysdb.department',"#jqGrid2_ca input[name='deptcode']",'errorField',
		{	colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
				
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Department Code",
			open: function(){
				dialog_deptcodeCA.urlParam.filterCol=['compcode','recstatus'],
				dialog_deptcodeCA.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam','radio','tab');
		dialog_deptcodeCA.makedialog(false);

	var dialog_categoryCA = new ordialog(
		'categoryCA','material.category',"#jqGrid2_ca input[name='category']",'errorField',
		{	colModel:[
				{label:'Category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
			],
			urlParam: {
				filterCol:['compcode','source', 'cattype', 'recstatus'],
				filterVal:['session.compcode','CR', 'Other', 'ACTIVE']
			},
		},{
			title:"Select Category",
			open: function(){
				dialog_categoryCA.urlParam.filterCol=['compcode','source', 'cattype', 'recstatus'],
				dialog_categoryCA.urlParam.filterVal=['session.compcode','CR', 'Other', 'ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_categoryCA.makedialog(false);

	var dialog_GSTCodeCA = new ordialog(
		'GSTCodeCA',['hisdb.taxmast'],"#jqGrid2_ca input[name='GSTCode']",'errorField',
		{	colModel:
			[
				{label:'Tax code',name:'taxcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true, or_search:true},
				{label:'Tax Rate',name:'rate',width:200,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus', 'taxtype'],
				filterVal:['session.compcode','ACTIVE', 'Input']
			},
		},{
			title:"Select Tax Code For Item",
			open: function(){
				dialog_GSTCodeCA.urlParam.filterCol=['compcode','recstatus', 'taxtype'];
				dialog_GSTCodeCA.urlParam.filterVal=['session.compcode','ACTIVE', 'Input'];
			},
		},'urlParam','radio','tab'
	);
	dialog_GSTCodeCA.makedialog(false);

	//DIALOG CA
	var dialog_bankcodeDA = new ordialog(
		'bankcodeDA',['finance.bank'],"#formdata_da input[name='bankcode']",'errorField',
		{	colModel:[
				{label:'Bank Code',name:'bankcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'bankname',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Bank Code",
			open: function(){
				dialog_bankcodeDA.urlParam.filterCol=['compcode','recstatus'],
				dialog_bankcodeDA.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_bankcodeDA.makedialog(false);

	var dialog_deptcodeDA = new ordialog(
		'deptcodeDA','sysdb.department',"#jqGrid2_da input[name='deptcode']",'errorField',
		{	colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
				
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Department Code",
			open: function(){
				dialog_deptcodeDA.urlParam.filterCol=['compcode','recstatus'],
				dialog_deptcodeDA.urlParam.filterVal=['session.compcode','ACTIVE']
			}
		},'urlParam','radio','tab');
		dialog_deptcodeDA.makedialog(false);

	var dialog_categoryDA = new ordialog(
		'categoryDA','material.category',"#jqGrid2_da input[name='category']",'errorField',
		{	colModel:[
				{label:'Category Code',name:'catcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:200,classes:'pointer',canSearch:true,or_search:true, checked:true},
			],
			urlParam: {
				filterCol:['compcode','source', 'cattype', 'recstatus'],
				filterVal:['session.compcode','CR', 'Other', 'ACTIVE']
			},
		},{
			title:"Select Category",
			open: function(){
				dialog_categoryDA.urlParam.filterCol=['compcode','source', 'cattype', 'recstatus'],
				dialog_categoryDA.urlParam.filterVal=['session.compcode','CR', 'Other', 'ACTIVE']
			}
		},'urlParam','radio','tab'
	);
	dialog_categoryDA.makedialog(false);

	var dialog_GSTCodeDA = new ordialog(
		'GSTCodeDA',['hisdb.taxmast'],"#jqGrid2_da input[name='GSTCode']",'errorField',
		{	colModel:
			[
				{label:'Tax code',name:'taxcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true, or_search:true},
				{label:'Tax Rate',name:'rate',width:200,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus', 'taxtype'],
				filterVal:['session.compcode','ACTIVE', 'Input']
			},
		},{
			title:"Select Tax Code For Item",
			open: function(){
				dialog_GSTCodeDA.urlParam.filterCol=['compcode','recstatus', 'taxtype'];
				dialog_GSTCodeDA.urlParam.filterVal=['session.compcode','ACTIVE', 'Input'];
			},
		},'urlParam','radio','tab'
	);
	dialog_GSTCodeDA.makedialog(false);
	
	function getdata(mode,idno){
		switch(mode){
		case 'FT':
			populateform_ft(idno);
			break;
		case 'DP':
			populateform_dp(idno);
			break;
		case 'CA':
			populateform_ca(idno);
			break;
		case 'DA':
			populateform_da(idno);
			break;
		}
	}

	function populateform_ft(idno){
		var param={
				action:'populate_ft',
				url:'./CMEnquiry/table',
				field:['apacthdr_compcode','apacthdr_bankcode', 'apacthdr_auditno', 'apacthdr_trantype',  'apacthdr_pvno', 'apacthdr_actdate','apacthdr_amount', 'apacthdr_payto', 'apacthdr_outamount', 'apacthdr_recstatus', 'apacthdr_cheqno', 'apacthdr_document', 'apacthdr_unit', 'apacthdr_deptcode' ,'apacthdr_remarks', 'apacthdr_adduser', 'apacthdr_adddate', 'apacthdr_upduser', 'apacthdr_upddate', 'apacthdr_source', 'apacthdr_idno', 'apacthdr_paymode'],
				idno:idno,
			}
	
			$.get( param.url+"?"+$.param(param), function( data ) {
				
			},'json').done(function(data) {
				if(!$.isEmptyObject(data.rows)){
					$('#dialogForm_ft').dialog( "open" );	
					$.each(data.rows, function( index, value ) {
						var input=$("#dialogForm_ft [name='"+index+"']");
						if(input.is("[type=radio]")){
							$("#dialogForm_ft [name='"+index+"'][value='"+value+"']").prop('checked', true);
						}else{
							input.val(value);
						}
					});

					//setpaymodeused
					var paymode = $("#paymode").val();

					dialog_paymodeFT.check(errorField);
					dialog_bankcodefromFT.check(errorField);
					dialog_bankcodetoFT.check(errorField);
					$('#chg_div,#cheqno_a').hide();

					if(paymode == "CHEQUE"){
						$('#chg_div,#cheqno_a').show();
						$('#chg_label').text('CHEQUE No.');
						dialog_cheqnoFT.check(errorField);
					}else if (paymode == "CASH"){
						$('#chg_label').text('');
					}else if (paymode == "BD"){
						$('#chg_div').show();
						$('#chg_label').text('DD No');
					}else if (paymode == "TT"){
						$('#chg_div').show();
						$('#chg_label').text('TT No.');
					}

				}
			});
	}

	function populateform_dp(idno){
		var param={
				action:'populate_dp',
				url:'./CMEnquiry/table',
				field:['apacthdr_compcode','apacthdr_bankcode', 'apacthdr_auditno', 'apacthdr_trantype',  'apacthdr_pvno', 'apacthdr_actdate','apacthdr_amount', 'apacthdr_payto', 'apacthdr_outamount', 'apacthdr_recstatus', 'apacthdr_cheqno', 'apacthdr_document', 'apacthdr_unit', 'apacthdr_deptcode' ,'apacthdr_remarks', 'apacthdr_adduser', 'apacthdr_adddate', 'apacthdr_upduser', 'apacthdr_upddate', 'apacthdr_source', 'apacthdr_idno', 'apacthdr_paymode'],
				idno:idno,
			}
	
			$.get( param.url+"?"+$.param(param), function( data ) {
				
			},'json').done(function(data) {
				if(!$.isEmptyObject(data.rows)){
					$.each(data.rows, function( index, value ) {
						var input=$("#dialogForm_dp [name='"+index+"']");
						if(input.is("[type=radio]")){
							$("#dialogForm_dp [name='"+index+"'][value='"+value+"']").prop('checked', true);
						}else{
							input.val(value);
						}
					});
					dialog_paymodeDP.check(errorField);
					dialog_bankcodeDP.check(errorField);
					dialog_paytoDP.check(errorField);
					dialog_cheqnoDP.check(errorField);
					dialog_deptcodeDP.check(errorField);
					dialog_categoryDP.check(errorField);
					dialog_GSTCodeDP.check(errorField);

					refreshGrid("#jqGrid2_dp",urlParam2_dp,'add');
				}
			});
	}

	function populateform_ca(idno){
		var param={
				action:'populate_ca',
				url:'./CMEnquiry/table',
				field:['apacthdr_compcode','apacthdr_bankcode', 'apacthdr_auditno', 'apacthdr_trantype',  'apacthdr_pvno', 'apacthdr_actdate','apacthdr_amount', 'apacthdr_payto', 'apacthdr_outamount', 'apacthdr_recstatus', 'apacthdr_cheqno', 'apacthdr_document', 'apacthdr_unit', 'apacthdr_deptcode' ,'apacthdr_remarks', 'apacthdr_adduser', 'apacthdr_adddate', 'apacthdr_upduser', 'apacthdr_upddate', 'apacthdr_source', 'apacthdr_idno', 'apacthdr_paymode'],
				idno:idno,
			}
	
			$.get( param.url+"?"+$.param(param), function( data ) {
				
			},'json').done(function(data) {
				if(!$.isEmptyObject(data.rows)){
					$.each(data.rows, function( index, value ) {
						var input=$("#dialogForm_ca [name='"+index+"']");
						if(input.is("[type=radio]")){
							$("#dialogForm_ca [name='"+index+"'][value='"+value+"']").prop('checked', true);
						}else{
							input.val(value);
						}
					});
					dialog_bankcodeCA.check(errorField);
					dialog_deptcodeCA.check(errorField);
					dialog_categoryCA.check(errorField);
					dialog_GSTCodeCA.check(errorField);

					refreshGrid("#jqGrid2_ca",urlParam2_ca,'add');
				}
			});
	}

	function populateform_da(idno){
		var param={
				action:'populate_ca',
				url:'./CMEnquiry/table',
				field:['apacthdr_compcode','apacthdr_bankcode', 'apacthdr_auditno', 'apacthdr_trantype',  'apacthdr_pvno', 'apacthdr_actdate','apacthdr_amount', 'apacthdr_payto', 'apacthdr_outamount', 'apacthdr_recstatus', 'apacthdr_cheqno', 'apacthdr_document', 'apacthdr_unit', 'apacthdr_deptcode' ,'apacthdr_remarks', 'apacthdr_adduser', 'apacthdr_adddate', 'apacthdr_upduser', 'apacthdr_upddate', 'apacthdr_source', 'apacthdr_idno', 'apacthdr_paymode'],
				idno:idno,
			}
	
			$.get( param.url+"?"+$.param(param), function( data ) {
				
			},'json').done(function(data) {
				if(!$.isEmptyObject(data.rows)){
					$.each(data.rows, function( index, value ) {
						var input=$("#dialogForm_da [name='"+index+"']");
						if(input.is("[type=radio]")){
							$("#dialogForm_da [name='"+index+"'][value='"+value+"']").prop('checked', true);
						}else{
							input.val(value);
						}
					});
					dialog_bankcodeDA.check(errorField);
					dialog_deptcodeDA.check(errorField);
					dialog_categoryDA.check(errorField);
					dialog_GSTCodeDA.check(errorField);

					refreshGrid("#jqGrid2_da",urlParam2_da,'add');
				}
			});
	}

	function init_jq2_cn(oper){
		var unallocated = selrowData('#jqGrid').unallocated;
		if(unallocated == 'true'){
			$("#dialogForm_cn [name=apacthdr_trantype]").val('CNU');
		}

		if(($("#dialogForm_cn [name=apacthdr_trantype]").val() == 'CN')) {
			$('#cn_detail').show();
			$("#jqGrid2_cn").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_cn_c")[0].offsetWidth-$("#jqGrid2_cn_c")[0].offsetLeft-28));
		} else if (($("#dialogForm_cn [name=apacthdr_trantype]").val() == 'CNU')) {
			$('#cn_detail').hide();
		}
		
	}
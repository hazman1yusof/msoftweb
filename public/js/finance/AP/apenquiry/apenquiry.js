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
	var mycurrency =new currencymode(["#formdata_cn :input[name='apacthdr_amount']", "#formdata_cn :input[name='apacthdr_outamount']", "#formdata_cn :input[name='tot_Alloc']", "#formdata_dn :input[name='apacthdr_amount']", "#formdata_dn :input[name='apacthdr_outamount']", "#formdata_cna :input[name='apacthdr_amount']", "#formdata_pv :input[name='apacthdr_amount']", "#formdata_pd :input[name='apacthdr_amount']", "#formdata_in :input[name='apacthdr_amount']", "#formdata_in :input[name='apactdtl_outamt']"]);
	var mycurrency2 =new currencymode(['#apacthdr_outamount', '#apacthdr_amount']);
	////////////////////////////////////start dialog///////////////////////////////////////
	var oper=null;

	$("#dialogForm_cna")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				unsaved = false;
				counter_save=0;
				errorField.length=0;
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				disableForm('#formdata_cna');
				$("#pg_jqGridPager2_cna table").hide();
				dialog_departmentCNA.check(errorField);
				dialog_paymodeCNA.check(errorField);
				dialog_suppcodeCNA.check(errorField);
				dialog_paytoCNA.check(errorField);
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

	$("#dialogForm_pv")
		.dialog({
			width: 9 / 10 * $(window).width(),
			modal: true,
			autoOpen: false,
			open: function (event, ui) {
				parent_close_disabled(true);
				$("#jqGrid2_pv").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_pv_c")[0].offsetWidth-$("#jqGrid2_pv_c")[0].offsetLeft));
				unsaved = false;
				counter_save=0;
				errorField.length=0;
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				disableForm('#formdata_pv');
				$("#pg_jqGridPager2 table").hide();
				dialog_paymodePV.check(errorField);
				dialog_bankcodePV.check(errorField);
				dialog_suppcodePV.check(errorField);
				dialog_paytoPV.check(errorField);
			},
			close: function( event, ui ) {
				//reset balik
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_pv');
				$('#formdata_pv .alert').detach();
				$("#formdata_pv a").off();
				$(".noti, .noti2 ol").empty();
				refreshGrid("#jqGrid2_pv",null,"kosongkan");
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
				dialog_paymodePD.check(errorField);
				dialog_bankcodePD.check(errorField);
				dialog_suppcodePD.check(errorField);
				dialog_paytoPD.check(errorField);
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
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				disableForm('#formdata_in');
				refreshGrid("#jqGrid2_in",urlParam2_in);
				$("#pg_jqGridPager2_in table").hide();
				dialog_categoryIN.check(errorField);
				dialog_departmentIN.check(errorField);
				dialog_suppcodeIN.check(errorField);
				dialog_paytoIN.check(errorField);
				init_jq2();
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_in');
				$('.my-alert').detach();
				$("#formdata_in a").off();
				$(".noti, .noti2 ol").empty();
				refreshGrid("#jqGrid2_in",null,"kosongkan");
				refreshGrid("#jqGrid2_in_detail",null,"kosongkan");
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
				$("#jqGrid2_cn").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_cn_c")[0].offsetWidth-$("#jqGrid2_cn_c")[0].offsetLeft));
				$("#jqGridAlloc").jqGrid('setGridWidth', Math.floor($("#jqGrid_Alloc")[0].offsetWidth-$("#jqGrid_Alloc")[0].offsetLeft));
				mycurrency.formatOnBlur();
				mycurrency.formatOn();
				disableForm('#formdata_cn');
				refreshGrid("#jqGrid2_cn",urlParam2_cn);
				refreshGrid("#jqGridAlloc",urlParam2_allocdtl);
				$("#pg_jqGridPager2_cn table").hide();
				//dialog_departmentCN.check(errorField);
				dialog_suppcodeCN.check(errorField);
				dialog_paytoCN.check(errorField);
				dialog_categoryCN.check(errorField);
				init_jq2_cn();
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_cn');
				$('.my-alert').detach();
				$("#formdata_cn a").off();
				$(".noti").empty();
				refreshGrid("#jqGrid2_cn",null,"kosongkan");
				refreshGrid("#jqGridAlloc",null,"kosongkan");
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
				mycurrency.formatOn();
				$("#jqGrid2_dn").jqGrid('setGridWidth', Math.floor($("#jqGrid2_dn_c")[0].offsetWidth-$("#jqGrid2_dn_c")[0].offsetLeft));
				disableForm('#formdata_dn');
				refreshGrid("#jqGrid2_dn",urlParam2_dn);
				//refreshGrid("#jqGridAllocdn",urlParam2_allocdn);
				dialog_suppcodeDN.check(errorField);
				dialog_paytoDN.check(errorField);
			},
			close: function( event, ui ) {
				parent_close_disabled(false);
				emptyFormdata(errorField,'#formdata_dn');
				$('.my-alert').detach();
				$("#formdata_dn a").off();
				$(".noti").empty();
				refreshGrid("#jqGrid2_dn",null,"kosongkan");
				//refreshGrid("#jqGridAllocdn",null, urlParam2_allocdn,'kosongkan');
				errorField.length=0;
			},
	});

	$( "#statementDialog" ).dialog({
		autoOpen: false,
		width: 5/10 * $(window).width(),
		modal: true,
		open: function(){
		
		},
		close: function( event, ui ){
			parent_close_disabled(false);
			emptyFormdata(errorField,'#formdata_statement');
		},
		buttons:
		[
		{
			text: "Generate Excel",click: function() {
				
				window.open('./apenquiry/showExcel?suppcode_from='+$('#suppcode_from').val()+'&suppcode_to='+$("#suppcode_to").val()+'&datefrom='+$("#datefrom").val()+'&dateto='+$("#dateto").val(), '_blank');
				// window.location='./apenquiry/showExcel?suppcode_from='+$('#suppcode_from').val()+'&suppcode_to='+$("#suppcode_to").val()+'&datefrom='+$("#datefrom").val()+'&dateto='+$("#dateto").val();
			}
		},{
			text: "Close",click: function() {
				$(this).dialog('close');
				emptyFormdata(errorField,'#formdata_statement');
			}
		}],
	});

	$('#pdfgen_excel').click(function(){
		$( "#statementDialog" ).dialog( "open" );
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
			{ label: 'Supplier Code', name: 'apacthdr_suppcode', width: 60, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Type', name: 'apacthdr_trantype', width: 18, classes: 'wrap text-uppercase', canSearch: true},
			{ label: 'Audit No', name: 'apacthdr_auditno', width: 22, classes: 'wrap',formatter: padzero, unformat: unpadzero, canSearch: true},
			{ label: 'PV No', name: 'apacthdr_pvno', width: 22, classes: 'wrap',formatter: padzero, unformat: unpadzero, canSearch: true},
			{ label: 'Cheque No', name: 'apacthdr_cheqno', width: 30, classes: 'wrap text-uppercase', canSearch: true},
			{ label: 'Bank Code', name: 'apacthdr_bankcode', width: 28, classes: 'wrap text-uppercase', hidden:false},
			{ label: 'Document No', name: 'apacthdr_document', width: 50, classes: 'wrap text-uppercase', canSearch: true},
			{ label: 'Unit', name: 'apacthdr_unit', width: 22, hidden:false},
			{ label: 'Pay To', name: 'apacthdr_payto', width: 50, classes: 'wrap text-uppercase', hidden:true, canSearch: false},
			{ label: 'Category Code', name: 'apacthdr_category', width: 40, hidden:false, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},		
			{ label: 'Document Date', name: 'apacthdr_actdate', width: 25, classes: 'wrap text-uppercase', canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Post Date', name: 'apacthdr_postdate', width: 25, classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Amount', name: 'apacthdr_amount', width: 25, classes: 'wrap', align: 'right', formatter:'currency'},
			{ label: 'Outstanding', name: 'apacthdr_outamount', width: 28, hidden:false, classes: 'wrap', align: 'right', formatter:'currency'},
			{ label: 'Status', name: 'apacthdr_recstatus', width: 25, classes: 'wrap text-uppercase', hidden:false},
			{ label: 'doctype', name: 'apacthdr_doctype', width: 10, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'Creditor Name', name: 'supplier_name', width: 50, classes: 'wrap text-uppercase', checked: true, hidden: true},
			{ label: 'Department', name: 'apacthdr_deptcode', width: 25, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'remarks', name: 'apacthdr_remarks', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adduser', name: 'apacthdr_adduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'adddate', name: 'apacthdr_adddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upduser', name: 'apacthdr_upduser', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'upddate', name: 'apacthdr_upddate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'recdate', name: 'apacthdr_recdate', width: 90, hidden:true, classes: 'wrap'},
			{ label: 'compcode', name: 'apacthdr_compcode', width: 40, hidden:true},
			{ label: 'source', name: 'apacthdr_source', width: 40, hidden:true},
			{ label: 'idno', name: 'apacthdr_idno', width: 40, hidden:true, key:true},
			{ label: 'paymode', name: 'apacthdr_paymode', width: 50, classes: 'wrap text-uppercase', hidden:true},
			{ label: 'unallocated', name: 'apacthdr_unallocated', width: 50, classes: 'wrap', hidden:true},
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
			$('#jqGrid3_div_in,#jqGrid3_div_pv, #jqGrid3_div_dn, #jqGrid3_div_cn').hide();

			if(selrowData("#jqGrid").apacthdr_trantype=='IN'){
				$('#jqGrid3_div_in').show();
				urlParam2_in.filterVal[1]=selrowData("#jqGrid").apacthdr_auditno;
				$("#jqGrid3_in").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_div_in")[0].offsetWidth-$("#jqGrid3_div_in")[0].offsetLeft));
				refreshGrid("#jqGrid3_in",urlParam2_in);

			}else if(selrowData("#jqGrid").apacthdr_trantype=='PV'){
				$('#jqGrid3_div_pv').show();
				urlParam2_pv.apacthdr_auditno=selrowData("#jqGrid").apacthdr_auditno;
				$("#jqGrid3_pv").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_div_pv")[0].offsetWidth-$("#jqGrid3_div_pv")[0].offsetLeft));
				refreshGrid("#jqGrid3_pv",urlParam2_pv);

			}else if(selrowData("#jqGrid").apacthdr_trantype=='DN'){
				$('#jqGrid3_div_dn').show();
				urlParam2_dn.source = selrowData("#jqGrid").apacthdr_source;
				urlParam2_dn.trantype = selrowData("#jqGrid").apacthdr_trantype;
				urlParam2_dn.auditno = selrowData("#jqGrid").apacthdr_auditno;
				$("#jqGrid3_dn").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_div_dn")[0].offsetWidth-$("#jqGrid3_div_dn")[0].offsetLeft));
				refreshGrid("#jqGrid3_dn",urlParam2_dn);

			}else if(selrowData("#jqGrid").apacthdr_trantype=='CN'){
				$('#jqGrid3_div_cn').show();
				$('#tot_Alloc').val(parseFloat(selrowData("#jqGrid").apacthdr_amount) - parseFloat(selrowData("#jqGrid").apacthdr_outamount));
				urlParam2_cn.filterVal[1]=selrowData("#jqGrid").apacthdr_auditno;
				urlParam2_allocdtl.source = selrowData("#jqGrid").apacthdr_source;
				urlParam2_allocdtl.trantype = selrowData("#jqGrid").apacthdr_trantype;
				urlParam2_allocdtl.auditno = selrowData("#jqGrid").apacthdr_auditno;
				//urlParam2_allocdtl.filterVal[1]=selrowData("#jqGrid").apacthdr_auditno;
				$("#jqGrid3_cn").jqGrid ('setGridWidth', Math.floor($("#jqGrid3_div_cn")[0].offsetWidth-$("#jqGrid3_div_cn")[0].offsetLeft));
				refreshGrid("#jqGrid3_cn",urlParam2_cn);
			}

			if(rowid != null) {
				var rowData = $('#jqGrid').jqGrid('getRowData', rowid);
				refreshGrid('#gridAlloc', urlParam2_alloc,'kosongkan');
				$("#pg_jqGridPager3 table").hide();
				$("#pg_jqGridPager2 table").show();
			}
			urlParam2_alloc.idno=selrowData("#jqGrid").apacthdr_idno;
			refreshGrid("#gridAlloc",urlParam2_alloc);
			populate_form(selrowData("#jqGrid"));

		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			let stat = selrowData("#jqGrid").apacthdr_recstatus;
			$("#jqGridPager td[title='View Selected Row']").click();
		},
		gridComplete: function(){
			$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			if($('#jqGrid').data('inputfocus') == 'creditor_search'){
				$("#creditor_search").focus();
				$('#jqGrid').data('inputfocus','');
				$('#creditor_search_hb').text('');
				removeValidationClass(['#creditor_search']);
			}else{
				$("#searchForm input[name=Stext]").focus();
			}
			fdl.set_array().reset();
			populate_form(selrowData("#jqGrid"));

		},
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid");
			// setjqgridHeight(data,'gridAlloc');
			// calc_jq_height_onchange("gridAlloc");
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
			if(selrowData("#jqGrid").apacthdr_trantype=='PV'){
				populateFormdata("#jqGrid", "#dialogForm_pv", "#formdata_pv", selRowId, 'view', '');
				refreshGrid("#jqGrid2_pv",urlParam2_pv,'add');
			}else if(selrowData("#jqGrid").apacthdr_trantype=='PD'){
				populateFormdata("#jqGrid", "#dialogForm_pd", "#formdata_pd", selRowId, 'view', '');
			}else if(selrowData("#jqGrid").apacthdr_trantype=='IN'){
				populateFormdata("#jqGrid", "#dialogForm_in", "#formdata_in", selRowId, 'view', '');
				refreshGrid("#jqGrid2_in",urlParam2_in,'add');
			}else if(selrowData("#jqGrid").apacthdr_trantype=='CN'){
				populateFormdata("#jqGrid", "#dialogForm_cn", "#formdata_cn", selRowId, 'view', '');
				refreshGrid("#jqGrid2_cn",urlParam2_cn,'add');
				refreshGrid("#jqGridAlloc",urlParam2_allocdtl,'add');
			}else if(selrowData("#jqGrid").apacthdr_trantype=='DN'){
				populateFormdata("#jqGrid", "#dialogForm_dn", "#formdata_dn", selRowId, 'view', '');
				refreshGrid("#jqGrid2_dn",urlParam2_dn,'add');
				//refreshGrid("#jqGridAllocdn",urlParam2_allocdn,'add');
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
			case 'apacthdr_suppcode':field=['suppcode','name'];table="material.supplier";case_='apacthdr_suppcode';break;
			case 'apacthdr_category':field=['catcode','description'];table="material.category";case_='apacthdr_category';break;

			//PV
			case 'suppcode':field=['suppcode','name'];table="material.supplier";case_='suppcode';break;

			//CN DTL 
			case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
			case 'category':field=['catcode','description'];table="material.category";case_='category';break;
			case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";case_='GSTCode';break;
			
			//ALLOC CN
			case 'suppcode':field=['suppcode','name'];table="material.supplier";case_='suppcode';break;

			//DN
			case 'deptcode':field=['deptcode','description'];table="sysdb.department";case_='deptcode';break;
			case 'category':field=['catcode','description'];table="material.category";case_='category';break;
			case 'GSTCode':field=['taxcode','description'];table="hisdb.taxmast";case_='GSTCode';break;

			//gridAlloc
			case 'suppcode':field=['suppcode','name'];table="material.supplier";case_='suppcode';break;
			case 'bankcode':field=['bankcode','bankname'];table="finance.bank";case_='bankcode';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
	
		fdl.get_array('apenquiry',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}

	////////////////////////////////searching/////////////////////////////////
	$('#Scol').on('change', whenchangetodate);
	$('#Status').on('change', searchChange);
	$('#actdate_search').on('click', searchDate);

	function whenchangetodate() {
		urlParam.fromdate=urlParam.todate=null;
		creditor_search.off();
		$('#creditor_search, #actdate_from, #actdate_to').val('');
		$('#creditor_search_hb').text('');
		removeValidationClass(['#creditor_search']);
		if($('#Scol').val()=='apacthdr_actdate'){
			$("input[name='Stext'], #creditor_text").hide("fast");
			$("#actdate_text").show("fast");
		} else if($('#Scol').val() == 'apacthdr_suppcode'){
			$("input[name='Stext'],#actdate_text").hide("fast");
			$("#creditor_text").show("fast");
			creditor_search.on();
		} else {
			$("#creditor_text,#actdate_text").hide("fast");
			$("input[name='Stext']").show("fast");
			$("input[name='Stext']").velocity({ width: "100%" });
		}
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

	function searchDate(){
		urlParam.fromdate = $('#actdate_from').val();
		urlParam.todate = $('#actdate_to').val();
		refreshGrid('#jqGrid',urlParam);
	}

	function searchChange(){
		cbselect.empty_sel_tbl();
		var arrtemp = ['session.compcode', $('#Status option:selected').val()];  

		var filter = arrtemp.reduce(function(a,b,c){
			if (b.toUpperCase() == 'ALL') {
				return a;
			}else{
				a.fc = a.fc.concat(a.fct[c]);
				a.fv = a.fv.concat(b);
				return a;
			}
		},{fct:['ap.compcode','ap.recstatus'],fv:[],fc:[]});

		urlParam.filterCol = filter.fc;
		urlParam.filterVal = filter.fv;
		urlParam.WhereInCol = null;
		urlParam.WhereInVal = null;
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
			{ label: 'Creditor', name: 'suppcode', width: 100, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},
			{ label: 'Invoice Date', name: 'allocdate', width: 100, classes: 'wrap',formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}},
			{ label: 'Invoice No', name: 'reference', width: 100, classes: 'wrap',},
			{ label: 'Amount', name: 'refamount', width: 100, classes: 'wrap', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}, editable: false, align: "right"},
			{ label: 'O/S Amount', name: 'outamount', width: 100, align: 'right', classes: 'wrap', editable:false, formatter: 'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}, align: "right"},
			{ label: 'Amount Paid', name: 'allocamount', width: 100, classes: 'wrap', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}, editable: false, align: "right"},
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
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid2_pv");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
		beforeSubmit: function(postdata, rowid){ 
	 	}
	});
	jqgrid_label_align_right("#jqGrid2_pv");

	$("#jqGrid3_pv").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2_pv").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3_pv",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid3_pv");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		}
	});
	jqgrid_label_align_right("#jqGrid3_pv");

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
			{ label: 'Purchase Order Number', name: 'reference', width: 100, classes: 'wrap', editable: false},
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
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
		beforeSubmit: function(postdata, rowid){
	 	}
	});
	jqgrid_label_align_right("#jqGrid2_in");

	$("#jqGrid3_in").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2_in").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3_in",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid3_in");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		}
	});
	jqgrid_label_align_right("#jqGrid2_in");

	//CN
	var urlParam2_cn={
		action:'get_table_default',
		url:'util/get_table_default',
		field:['apdt.compcode','apdt.source','apdt.reference','apdt.trantype','apdt.auditno','apdt.lineno_','apdt.deptcode','apdt.category','apdt.document', 'apdt.AmtB4GST', 'apdt.GSTCode', 'apdt.taxamt as tot_gst','apdt.amount', 'apdt.dorecno', 'apdt.grnno'],
		table_name:['finance.apactdtl AS apdt'],
		table_id:'lineno_',
		filterCol:['apdt.compcode','apdt.auditno', 'apdt.recstatus','apdt.source', 'apdt.trantype'],
		filterVal:['session.compcode', '', '<>.DELETE', 'AP', 'CN']
		
	};

	$("#jqGrid2_cn").jqGrid({
		datatype: "local",
		editurl: "./CreditNoteAPDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'AuditNo', name: 'auditno', hidden: true},
            { label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true, editable:true},
            { label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true, editable:true},
            { label: 'Department', name: 'deptcode', width: 150, classes: 'wrap',formatter: showdetail, unformat:un_showdetail},
			{ label: 'Category', name: 'category', width: 100, classes: 'wrap', canSearch: true, formatter: showdetail, unformat:un_showdetail},
            { label: 'GST Code', name: 'GSTCode', width: 100, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},
            { label: 'Amount Before GST', name: 'AmtB4GST', width: 90, classes: 'wrap', align: 'right', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}, editable: false },
			{ label: 'Total Tax Amount', name: 'tot_gst', width: 90, align: 'right', classes: 'wrap', editable:true,formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}, editable: false},
            { label: 'Amount', name: 'amount', width: 90, classes: 'wrap', align: 'right', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}, editable: false},
            { label: 'rate', name: 'rate', width: 50, classes: 'wrap', hidden:true},
			{ label: 'idno', name: 'idno', editable: false, hidden: true },
			{ label: 'No', name: 'lineno_', editable: false, hidden: true },
			{ label: 'recstatus', name: 'recstatus', hidden: true },
		
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
		pager: "#jqGridPager2_cn",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid2_cn");
		},
		gridComplete: function(){	
			fdl.set_array().reset();
		},
		beforeSubmit: function(postdata, rowid){ 
		}

	});
	jqgrid_label_align_right("#jqGrid2_cn");

	$("#jqGrid3_cn").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2_cn").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3_cn",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid3_cn");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		}
	});
	jqgrid_label_align_right("#jqGrid3_cn");

	//CN ALLOC

	var urlParam2_allocdtl={
		action:'get_alloc_table',
		url:'./creditNote/table',
		source:'',
		trantype:'',
		auditno: ''
	};

	$("#jqGridAlloc").jqGrid({
		datatype: "local",
		editurl: "./creditNoteDetail/form",
		colModel: [
			{ label: 'Creditor', name: 'suppcode', width: 100, classes: 'wrap',formatter: showdetail, unformat:un_showdetail },
			{ label: 'Document Date', name: 'actdate', width: 100, classes: 'wrap', formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}},
			{ label: 'Post Date', name: 'postdate', width: 100, classes: 'wrap', formatter: "date", formatoptions: {srcformat: 'Y-m-d', newformat:'d/m/Y'}},
			{ label: 'Document No', name: 'reference', width: 100, classes: 'wrap',},
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
			{ label: 'recstatus', name: 'recstatus', width: 80, classes: 'wrap', hidden:true}, 
		
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
		pager: "#jqGridPagerAllocdtl",
		loadComplete: function(data){
			//calc_jq_height_onchange("jqGridAlloc");
		},
		gridComplete: function(){	
			fdl.set_array().reset();
		},
		beforeSubmit: function(postdata, rowid){ 
		}

	});
	jqgrid_label_align_right("#jqGridAlloc");

	//DN
	var urlParam2_dn={
		action: 'get_table_dtl',
		url:'DebitNoteAPDetail/table',
		
	};

	$("#jqGrid2_dn").jqGrid({
		datatype: "local",
		editurl: "./DebitNoteAPDetail/form",
		colModel: [
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'AuditNo', name: 'auditno', hidden: true},
            { label: 'source', name: 'source', width: 20, classes: 'wrap', hidden:true},
            { label: 'trantype', name: 'trantype', width: 20, classes: 'wrap', hidden:true},
            { label: 'Department', name: 'deptcode', width: 150, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},
			{ label: 'Category', name: 'category', width: 150, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},
            { label: 'GST Code', name: 'GSTCode', width: 100, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},
            { label: 'Amount Before GST', name: 'AmtB4GST', width: 90, classes: 'wrap', formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}, editable: false, align: "right"},
			{ label: 'Total Tax Amount', name: 'tot_gst', width: 90, align: 'right', classes: 'wrap',formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}, editable: false, align: "right"},
            { label: 'Amount', name: 'amount', width: 90, classes: 'wrap',formatter:'currency', formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2,}, editable: false, align: "right"},
            { label: 'rate', name: 'rate', width: 50, classes: 'wrap', hidden:true},
			{ label: 'idno', name: 'idno', editable: false, hidden: true },
			{ label: 'No', name: 'lineno_', editable: false, hidden: true },
			{ label: 'recstatus', name: 'recstatus', hidden: true },
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
		pager: "#jqGridPager2_dn",
		loadComplete: function(data){
		
			calc_jq_height_onchange("jqGrid2_dn");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
		beforeSubmit: function(postdata, rowid){ 
	 	}
	});
	jqgrid_label_align_right("#jqGrid2_dn");

	$("#jqGrid3_dn").jqGrid({
		datatype: "local",
		colModel: $("#jqGrid2_dn").jqGrid('getGridParam','colModel'),
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'lineno_',
		sortorder: "desc",
		pager: "#jqGridPager3_dn",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid3_dn");
		},
		gridComplete: function(){
			fdl.set_array().reset();
		}
	});
	jqgrid_label_align_right("#jqGrid3_dn");

	//////////////////////////////////////////////// Alloc Detail Luar////////////////////////////////////////////
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
			{ label: 'Post Date', name: 'postdate', width: 30, classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter,hidden:true},
		
		],
		shrinkToFit: true,
		autowidth:true,
		multiSort: true,
		viewrecords: true,
		rowNum: 30,
		pager: "#jqGridPagerAlloc",
		loadComplete: function(data){
			calc_jq_height_onchange("gridAlloc");
			setjqgridHeight(data,'gridAlloc');
			urlParam2_alloc.idno=selrowData("#jqGrid").apacthdr_idno;
			
			refreshGrid("#gridAlloc",urlParam2_alloc,'add');
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

	////////dialog handler statement/////
	var suppcode_from = new ordialog(
		'suppcode_from','material.supplier','#suppcode_from','errorField',
		{	
			colModel:[
				{label:'Supplier Code',name:'suppcode',width:200,classes:'pointer', canSearch: true, or_search: true },
				{label:'Supplier Name',name:'name',width:400,classes:'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				let data_suppcode = selrowData('#' + suppcode_from.gridname).suppcode;

				$('#suppcode_to').val(data_suppcode);
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
			title:"Select Creditor",
			open: function(){
				suppcode_from.urlParam.filterCol=['compcode','recstatus'];
				suppcode_from.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(obj_){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('suppcode_to',errorField)!==-1){
						errorField.splice($.inArray('suppcode_to',errorField), 1);
					}
				}
			},
			justb4refresh: function(obj_){
				obj_.urlParam.searchCol2=[];
				obj_.urlParam.searchVal2=[];
			},
			justaftrefresh: function(obj_){
				$("#Dtext_"+obj_.unique).val('');
			}
		},'urlParam','radio','tab'
	);
	suppcode_from.makedialog(true);

	var suppcode_to = new ordialog(
		'suppcode_to','material.supplier','#suppcode_to',errorField,
		{	
			colModel:[
				{label:'Supplier Code',name:'suppcode',width:200,classes:'pointer', canSearch: true, or_search: true },
				{label:'Supplier Name',name:'name',width:400,classes:'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
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
			title:"Select Creditor",
			open: function(){
				suppcode_to.urlParam.filterCol=['compcode','recstatus'];
				suppcode_to.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(obj_){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('suppcode_to',errorField)!==-1){
						errorField.splice($.inArray('suppcode_to',errorField), 1);
					}
				}
			},
			justb4refresh: function(obj_){
				obj_.urlParam.searchCol2=[];
				obj_.urlParam.searchVal2=[];
			},
			justaftrefresh: function(obj_){
				$("#Dtext_"+obj_.unique).val('');
			}
		},'urlParam','radio','tab'
	);
	suppcode_to.makedialog(true);

	///////dialog handler PV///////
	
	var dialog_paymodePV = new ordialog(
		'paymodePV','debtor.paymode',"#formdata_pv :input[name='apacthdr_paymode']", errorField,
		{colModel:[
				{label:'Paymode',name:'paymode',width:200,classes:'pointer'},
				{label:'Description',name:'description',width:400,classes:'pointer'},
				{label:'Paytype',name:'paytype',width:200,classes:'pointer',hidden:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Paymode",
			open: function(){
				dialog_paymodePV.urlParam.filterCol=['recstatus', 'compcode', 'source'],
				dialog_paymodePV.urlParam.filterVal=['ACTIVE', 'session.compcode', $('#apacthdr_source').val()],
				dialog_paymodePV.urlParam.WhereInCol=['paytype'];
				dialog_paymodePV.urlParam.WhereInVal=[['Bank Draft', 'Cheque', 'Cash', 'Bank', 'Tele Transfer']];
			}
		},'urlParam','radio','tab'
	);
	dialog_paymodePV.makedialog(false);
	
	var dialog_bankcodePV = new ordialog(
		'bankcodePV','finance.bank',"#formdata_pv :input[name='apacthdr_bankcode']", errorField,
		{colModel:[
				{label:'Bank Code',name:'bankcode',width:200,classes:'pointer'},
				{label:'Description',name:'bankname',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Paymode",
			open: function(){
				dialog_bankcodePV.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_bankcodePV.urlParam.filterVal=['ACTIVE', 'session.compcode']
			}
			},'urlParam','radio','tab'
	);
	dialog_bankcodePV.makedialog(false);

	var dialog_suppcodePV = new ordialog(
		'suppcodePV','material.supplier',"#formdata_pv :input[name='apacthdr_suppcode']", errorField,
		{colModel:[
				{label:'Supplier Code',name:'suppcode',width:200,classes:'pointer'},
				{label:'Supplier Name',name:'name',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_suppcodePV.urlParam.filterCol=['recstatus','compcode'];
				dialog_suppcodePV.urlParam.filterVal=['ACTIVE','session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_suppcodePV.makedialog(false);

	var dialog_paytoPV = new ordialog(
		'paytoPV','material.supplier',"#formdata_pv :input[name='apacthdr_payto']",errorField,
		{colModel:[
				{label:'Supplier Code',name:'SuppCode',width:200,classes:'pointer'},
				{label:'Description',name:'Name',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_paytoPV.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_paytoPV.urlParam.filterVal=['ACTIVE', 'session.compcode']
				}
			},'urlParam','radio','tab'
	);
	dialog_paytoPV.makedialog(false);
	
	///////dialog handler PD///////
	
	var dialog_paymodePD = new ordialog(
		'paymodePD','debtor.paymode',"#formdata_pd :input[name='apacthdr_paymode']", errorField,
		{colModel:[
				{label:'Paymode',name:'paymode',width:200,classes:'pointer'},
				{label:'Description',name:'description',width:400,classes:'pointer'},
				{label:'Paytype',name:'paytype',width:200,classes:'pointer',hidden:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Paymode",
			open: function(){
				dialog_paymodePD.urlParam.filterCol=['recstatus', 'compcode', 'source'],
				dialog_paymodePD.urlParam.filterVal=['ACTIVE', 'session.compcode', $('#apacthdr_source').val()],
				dialog_paymodePD.urlParam.WhereInCol=['paytype'];
				dialog_paymodePD.urlParam.WhereInVal=[['Bank Draft', 'Cheque', 'Cash', 'Bank', 'Tele Transfer']];
			}
			},'urlParam','radio','tab'
	);
	dialog_paymodePD.makedialog(false);
	
	var dialog_bankcodePD = new ordialog(
		'bankcodePD','finance.bank',"#formdata_pd :input[name='apacthdr_bankcode']",errorField,
		{colModel:[
				{label:'Bank Code',name:'bankcode',width:200,classes:'pointer'},
				{label:'Description',name:'bankname',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Paymode",
			open: function(){
				dialog_bankcodePD.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_bankcodePD.urlParam.filterVal=['ACTIVE', 'session.compcode']
			}
			},'urlParam','radio','tab'
	);
	dialog_bankcodePD.makedialog(false);

	var dialog_suppcodePD = new ordialog(
		'suppcodePD','material.supplier',"#formdata_pd :input[name='apacthdr_suppcode']",errorField,
		{colModel:[
				{label:'Supplier Code',name:'suppcode',width:200,classes:'pointer'},
				{label:'Supplier Name',name:'name',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_suppcodePD.urlParam.filterCol=['recstatus','compcode'];
				dialog_suppcodePD.urlParam.filterVal=['ACTIVE','session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_suppcodePD.makedialog(false);

	var dialog_paytoPD = new ordialog(
		'paytoPD','material.supplier',"#formdata_pd :input[name='apacthdr_payto']",errorField,
		{colModel:[
				{label:'Supplier Code',name:'SuppCode',width:200,classes:'pointer'},
				{label:'Description',name:'Name',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_paytoPD.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_paytoPD.urlParam.filterVal=['ACTIVE', 'session.compcode']
			}
			},'urlParam','radio','tab'
	);
	dialog_paytoPD.makedialog(false);
	
	///////dialog handler IN///////
	var dialog_suppcodeIN = new ordialog(
		'suppcodeIN','material.supplier',"#formdata_in :input[name='apacthdr_suppcode']",errorField,
		{colModel:[
				{label:'Supplier Code',name:'suppcode',width:200,classes:'pointer'},
				{label:'Supplier Name',name:'name',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_suppcodeIN.urlParam.filterCol=['recstatus','compcode'];
				dialog_suppcodeIN.urlParam.filterVal=['ACTIVE','session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_suppcodeIN.makedialog(false);

	var dialog_paytoIN = new ordialog(
		'paytoIN','material.supplier',"#formdata_in :input[name='apacthdr_payto']",errorField,
		{colModel:[
				{label:'Supplier Code',name:'SuppCode',width:200,classes:'pointer'},
				{label:'Description',name:'Name',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_paytoIN.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_paytoIN.urlParam.filterVal=['ACTIVE', 'session.compcode']
			}
			},'urlParam','radio','tab'
	);
	dialog_paytoIN.makedialog(false);

	var dialog_categoryIN = new ordialog(
		'categoryIN','material.category',"#formdata_in :input[name='apacthdr_category']",errorField,
		{colModel:[
			{label:'Category Code',name:'catcode',width:200,classes:'pointer'},
			{label:'Description',name:'description',width:400,classes:'pointer'},
			{label:'povalidate',name:'povalidate',width:400,classes:'pointer', hidden:true},
			{label:'source',name:'source',width:400,classes:'pointer', hidden:true},
		],
			urlParam: {
				filterCol:['recstatus', 'compcode'],
				filterVal:['ACTIVE', 'session.compcode']
			},
		},{	
			title:"Select Category Code",
			open: function(){
				if (($('#apacthdr_doctype').val()=="Supplier")) {
					dialog_categoryIN.urlParam.filterCol=['recstatus', 'compcode', 'source', 'povalidate'];
					dialog_categoryIN.urlParam.filterVal=['ACTIVE', 'session.compcode', 'CR', '1'];
				}else {
					dialog_categoryIN.urlParam.filterCol=['recstatus', 'compcode', 'source', 'povalidate'];
					dialog_categoryIN.urlParam.filterVal=['ACTIVE', 'session.compcode', 'CR', '0'];
				}
			}
		},'urlParam','radio','tab'
	);
	dialog_categoryIN.makedialog(false);
	
	var dialog_departmentIN = new ordialog(
		'departmentIN','sysdb.department',"#formdata_in :input[name='apacthdr_deptcode']",errorField,
		{colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer'},
				{label:'Description',name:'description',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Department Code",
			open: function(){
				dialog_departmentIN.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_departmentIN.urlParam.filterVal=['ACTIVE', 'session.compcode']
			}
		},'urlParam','radio','tab'
	);
	dialog_departmentIN.makedialog(false);

	///////dialog handler DN///////
	var dialog_suppcodeDN = new ordialog(
		'suppcodeDN','material.supplier',"#formdata_dn :input[name='apacthdr_suppcode']",errorField,
		{colModel:[
				{label:'Supplier Code',name:'suppcode',width:200,classes:'pointer'},
				{label:'Supplier Name',name:'name',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_suppcodeDN.urlParam.filterCol=['recstatus','compcode'];
				dialog_suppcodeDN.urlParam.filterVal=['ACTIVE','session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_suppcodeDN.makedialog(false);

	var dialog_paytoDN = new ordialog(
		'paytoDN','material.supplier',"#formdata_dn :input[name='apacthdr_payto']",errorField,
		{colModel:[
				{label:'Supplier Code',name:'SuppCode',width:200,classes:'pointer'},
				{label:'Description',name:'Name',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_paytoDN.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_paytoDN.urlParam.filterVal=['ACTIVE', 'session.compcode']
			}
		},'urlParam','radio','tab'
	);
	dialog_paytoDN.makedialog(false);

	var dialog_categoryDN = new ordialog(
		'categoryDN','material.category',"#formdata_dn :input[name='apacthdr_category']",errorField,
		{colModel:[
				{label:'Category Code',name:'catcode',width:200,classes:'pointer'},
				{label:'Description',name:'description',width:400,classes:'pointer'},
				{label:'povalidate',name:'povalidate',width:400,classes:'pointer', hidden:true},
				{label:'source',name:'source',width:400,classes:'pointer', hidden:true},
			],
			urlParam: {
				filterCol:['recstatus', 'compcode'],
				filterVal:['ACTIVE', 'session.compcode']
			},
		},{	
			title:"Select Category Code",
			open: function(){
				if (($('#apacthdr_doctype').val()=="Supplier")) {
					dialog_categoryDN.urlParam.filterCol=['recstatus', 'compcode', 'source', 'povalidate'];
					dialog_categoryDN.urlParam.filterVal=['ACTIVE', 'session.compcode', 'CR', '1'];
				}else {
					dialog_categoryDN.urlParam.filterCol=['recstatus', 'compcode', 'source', 'povalidate'];
					dialog_categoryDN.urlParam.filterVal=['ACTIVE', 'session.compcode', 'CR', '0'];
				}
			}
		},'urlParam','radio','tab'
	);
	dialog_categoryDN.makedialog(false);
	
	var dialog_departmentDN = new ordialog(
		'departmentDN','sysdb.department',"#formdata_dn :input[name='apacthdr_deptcode']",errorField,
		{colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer'},
				{label:'Description',name:'description',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Department Code",
			open: function(){
				dialog_departmentDN.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_departmentDN.urlParam.filterVal=['ACTIVE', 'session.compcode']
			}
		},'urlParam','radio','tab'
	);
	dialog_departmentDN.makedialog(false);

	///////dialog handler CN///////
	
	var dialog_suppcodeCN = new ordialog(
		'suppcodeCN','material.supplier',"#formdata_cn :input[name='apacthdr_suppcode']",errorField,
		{colModel:[
				{label:'Supplier Code',name:'suppcode',width:200,classes:'pointer'},
				{label:'Supplier Name',name:'name',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_suppcodeCN.urlParam.filterCol=['recstatus','compcode'];
				dialog_suppcodeCN.urlParam.filterVal=['ACTIVE','session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_suppcodeCN.makedialog(false);

	var dialog_paytoCN = new ordialog(
		'paytoCN','material.supplier',"#formdata_cn :input[name='apacthdr_payto']",errorField,
		{colModel:[
				{label:'Supplier Code',name:'SuppCode',width:200,classes:'pointer'},
				{label:'Description',name:'Name',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_paytoCN.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_paytoCN.urlParam.filterVal=['ACTIVE', 'session.compcode']
			}
		},'urlParam','radio','tab'
	);
	dialog_paytoCN.makedialog(false);

	var dialog_categoryCN = new ordialog(
		'categoryCN','material.category',"#jqGrid2_cn :input[name='category']",'errorField',
		{colModel:[
				{label:'Category Code',name:'catcode',width:200,classes:'pointer'},
				{label:'Description',name:'description',width:400,classes:'pointer'},
				{label:'povalidate',name:'povalidate',width:400,classes:'pointer', hidden:true},
				{label:'source',name:'source',width:400,classes:'pointer', hidden:true},
			],
			urlParam: {
				filterCol:['recstatus', 'compcode'],
				filterVal:['ACTIVE', 'session.compcode']
			},
		},{	
			title:"Select Category Code",
			open: function(){
				if (($('#apacthdr_doctype').val()=="Supplier")) {
					dialog_categoryCN.urlParam.filterCol=['recstatus', 'compcode', 'source', 'povalidate'];
					dialog_categoryCN.urlParam.filterVal=['ACTIVE', 'session.compcode', 'CR', '1'];
				}else {
					dialog_categoryCN.urlParam.filterCol=['recstatus', 'compcode', 'source', 'povalidate'];
					dialog_categoryCN.urlParam.filterVal=['ACTIVE', 'session.compcode', 'CR', '0'];
				}
			}
		},'urlParam','radio','tab'
	);
	dialog_categoryCN.makedialog(false);
	
	///////dialog handler CNA///////
	var dialog_suppcodeCNA = new ordialog(
		'suppcodeCNA','material.supplier',"#formdata_cna :input[name='apacthdr_suppcode']",errorField,
		{colModel:[
				{label:'Supplier Code',name:'suppcode',width:200,classes:'pointer'},
				{label:'Supplier Name',name:'name',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_suppcodeCNA.urlParam.filterCol=['recstatus','compcode'];
				dialog_suppcodeCNA.urlParam.filterVal=['ACTIVE','session.compcode'];
			}
		},'urlParam','radio','tab'
	);
	dialog_suppcodeCNA.makedialog(false);

	var dialog_paytoCNA = new ordialog(
		'paytoCNA','material.supplier',"#formdata_cna :input[name='apacthdr_payto']",errorField,
		{colModel:[
				{label:'Supplier Code',name:'SuppCode',width:200,classes:'pointer'},
				{label:'Description',name:'Name',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Supplier Code",
			open: function(){
				dialog_paytoCNA.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_paytoCNA.urlParam.filterVal=['ACTIVE', 'session.compcode']
			}
		},'urlParam','radio','tab'
	);
	dialog_paytoCNA.makedialog(false);
	
	var dialog_paymodeCNA = new ordialog(
		'paymodeCNA','debtor.paymode',"#formdata_cna :input[name='apacthdr_paymode']", errorField,
		{colModel:[
				{label:'Paymode',name:'paymode',width:200,classes:'pointer'},
				{label:'Description',name:'description',width:400,classes:'pointer'},
				{label:'Paytype',name:'paytype',width:200,classes:'pointer',hidden:true},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Paymode",
			open: function(){
				dialog_paymodeCNA.urlParam.filterCol=['recstatus', 'compcode', 'source'],
				dialog_paymodeCNA.urlParam.filterVal=['ACTIVE', 'session.compcode', $('#apacthdr_source').val()],
				dialog_paymodeCNA.urlParam.WhereInCol=['paytype'];
				dialog_paymodeCNA.urlParam.WhereInVal=[['Bank Draft', 'Cheque', 'Cash', 'Bank', 'Tele Transfer']];
			}
		},'urlParam','radio','tab'
	);
	dialog_paymodeCNA.makedialog(false);

	var dialog_departmentCNA = new ordialog(
		'departmentCNA','sysdb.department',"#formdata_cna :input[name='apacthdr_deptcode']",errorField,
		{colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer'},
				{label:'Description',name:'description',width:400,classes:'pointer'},
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
		},{
			title:"Select Department Code",
			open: function(){
				dialog_departmentCNA.urlParam.filterCol=['recstatus', 'compcode'],
				dialog_departmentCNA.urlParam.filterVal=['ACTIVE', 'session.compcode']
			}
		},'urlParam','radio','tab'
	);
	dialog_departmentCNA.makedialog(false);

	function setjqgridHeight(data,grid){
		if(data.rows.length>=6){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(500);
		}else if(data.rows.length>=3){
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(300);
		}else{
			$('#gbox_'+grid+' div.ui-jqgrid-bdiv').height(200);
		}
	}
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
			
	function init_jq2(){
		if($('#apacthdr_doctype').val() == 'Supplier'){
			$('#save').hide();
			$('#ap_detail').show();
			$('#pv_detail').show();
			$('#cn_in_detail').show();
			$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_in_c")[0].offsetWidth-$("#jqGrid2_in_c")[0].offsetLeft-28));
			$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_pv_c")[0].offsetWidth-$("#jqGrid2_pv_c")[0].offsetLeft-28));
			$("label[for='apactdtl_outamt'], input#apactdtl_outamt").show();
		}else{
			$('#save').hide();
			$('#ap_detail').hide();
			$('#pv_detail').hide();
			$('#cn_in_detail').hide();
			$("#jqGrid2").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_in_c")[0].offsetWidth-$("#jqGrid2_in_c")[0].offsetLeft-28));
			$("label[for='apactdtl_outamt'], input#apactdtl_outamt").hide();
		}
	}

	function init_jq2_cn(oper){
		if(oper != 'view'){
			var unallocated = selrowData('#jqGrid').unallocated;
			if(unallocated == 'true'){
				$("#apacthdr_unallocated").val('0');
			}
		}

		if(($("#apacthdr_unallocated").find(":selected").text() == 'Credit Note')) {
			$('#save').hide();
			$('#alloc_detail').show();
			$('#grid_detail').show();
			$("#jqGridAlloc").jqGrid ('setGridWidth', Math.floor($("#jqGrid_Alloc")[0].offsetWidth-$("#jqGrid_Alloc")[0].offsetLeft-28));
			$("#jqGrid2_cn").jqGrid ('setGridWidth', Math.floor($("#jqGrid2_cn")[0].offsetWidth-$("#jqGrid2_cn")[0].offsetLeft));
		} else if (($("#apacthdr_unallocated").find(":selected").text() == 'Credit Note Unallocated')) { 
			$('#save').hide();
			$('#alloc_detail').hide();
			$('#grid_detail').show();
		}

	}

	function populate_form(obj){

		//panel header
		$('#inTrantype_show').text(obj.apacthdr_trantype);
		$('#inDocument_show').text(padzero(obj.apacthdr_auditno));
		$('#inSuppcode_show').text(obj.apacthdr_suppcode);
		$('#inSuppname_show').text(obj.supplier_name);

		$('#pvTrantype_show').text(obj.apacthdr_trantype);
		$('#pvDocument_show').text(padzero(obj.apacthdr_auditno));
		$('#pvSuppcode_show').text(obj.apacthdr_suppcode);
		$('#pvSuppname_show').text(obj.supplier_name);

		$('#dnTrantype_show').text(obj.apacthdr_trantype);
		$('#dnDocument_show').text(padzero(obj.apacthdr_auditno));
		$('#dnSuppcode_show').text(obj.apacthdr_suppcode);
		$('#dnSuppname_show').text(obj.supplier_name);

		$('#cnTrantype_show').text(obj.apacthdr_trantype);
		$('#cnDocument_show').text(padzero(obj.apacthdr_auditno));
		$('#cnSuppcode_show').text(obj.apacthdr_suppcode);
		$('#cnSuppname_show').text(obj.supplier_name);

		$('#allocTrantype_show').text(obj.apacthdr_trantype);
		$('#allocDocument_show').text(padzero(obj.apacthdr_auditno));
		$('#allocSuppcode_show').text(obj.apacthdr_suppcode);
		$('#allocSuppname_show').text(obj.supplier_name);
	
	}
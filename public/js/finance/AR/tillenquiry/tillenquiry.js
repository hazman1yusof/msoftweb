$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
var editedRow=0;

$(document).ready(function () {
	$("body").show();
	
	////////////////////////////////////validation////////////////////////////////////
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
	
	/////////////////////////////////////currency/////////////////////////////////////
	// var mycurrency =new currencymode(['#db_outamount', '#db_amount']);
	// var mycurrency2 =new currencymode(['#db_outamount', '#db_amount']);
	var fdl = new faster_detail_load();
	
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
	
	////////////////////////////////////parameter for jqgrid url////////////////////////////////////
	var urlParam={
		action:'maintable',
		url:'./tillenquiry/table',
		// source:$('#db_source').val(),
		// trantype:$('#db_trantype').val(),
	}
	
	///////////////////////////////////////////////jqgrid///////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label: 'idno', name: 'idno', hidden: true },
			{ label: 'compcode', name: 'compcode', hidden: true },
			{ label: 'Till Code', name: 'tillcode', width: 30, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail },
			{ label: 'Till No', name: 'tillno', width: 15, align: 'right', classes: 'wrap', canSearch: true },
			{ label: 'Cashier', name: 'cashier', width: 20, classes: 'wrap' },
			{ label: 'Open Date', name: 'opendate', width: 25, classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'Open Time', name: 'opentime', width: 25, classes: 'wrap' },
			{ label: 'Close Date', name: 'closedate', width: 25, classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter },
			{ label: 'Close Time', name: 'closetime', width: 25, classes: 'wrap' },
			{ label: 'openamt', name: 'openamt', width: 10, hidden: true },
			{ label: 'cashamt', name: 'cashamt', width: 10, hidden: true },
			{ label: 'cardamt', name: 'cardamt', width: 10, hidden: true },
			{ label: 'cheqamt', name: 'cheqamt', width: 10, hidden: true },
			{ label: 'cnamt', name: 'cnamt', width: 10, hidden: true },
			{ label: 'otheramt', name: 'otheramt', width: 10, hidden: true },
			{ label: 'refcashamt', name: 'refcashamt', width: 10, hidden: true },
			{ label: 'refcardamt', name: 'refcardamt', width: 10, hidden: true },
			{ label: 'refchqamt', name: 'refchqamt', width: 10, hidden: true },
			{ label: 'actclosebal', name: 'actclosebal', width: 10, hidden: true },
			{ label: 'reason', name: 'reason', width: 10, hidden: true },
			{ label: 'upddate', name: 'upddate', width: 10, hidden: true },
			{ label: 'upduser', name: 'upduser', width: 10, hidden: true },
			{ label: 'adddate', name: 'adddate', width: 10, hidden: true },
			{ label: 'adduser', name: 'adduser', width: 10, hidden: true },
			{ label: 'deldate', name: 'deldate', width: 10, hidden: true },
			{ label: 'deluser', name: 'deluser', width: 10, hidden: true },
			{ label: 'recstatus', name: 'recstatus', width: 10, hidden: true },
		],
		autowidth:true,
		// multiSort: true,
		viewrecords: true,
		loadonce:false,
		width: 900,
		height: 400,
		rowNum: 30,
		sortname: 'idno',
		sortorder: "desc",
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			$('#tillcode1_show, #tillcode2_show').text(selrowData("#jqGrid").tillcode);
			$('#cashier1_show, #cashier2_show').text(selrowData("#jqGrid").cashier);
			$('#opendate1_show, #opendate2_show').text(selrowData("#jqGrid").opendate);
			$('#opentime1_show, #opentime2_show').text(selrowData("#jqGrid").opentime);
			
			if(rowid != null) {
				var rowData = $('#jqGrid').jqGrid('getRowData', rowid);
				// $("#pg_jqGridPager3 table").hide();
				// $("#pg_jqGridPager2 table").show();
			}
			
			urlParamTillDetl.tillno=selrowData("#jqGrid").tillno;
			refreshGrid("#jqGridTillDetl",urlParamTillDetl);
			populate_summary();
			$("#pdfgen1").attr('href','./tillenquiry/showpdf?tillno='+selrowData("#jqGrid").tillno+'&tillcode='+selrowData("#jqGrid").tillcode);
			$("#print_excel").attr('href','./tillenquiry/table?action=showExcel&tillno='+selrowData("#jqGrid").tillno+'&tillcode='+selrowData("#jqGrid").tillcode);
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGridPager td[title='View Selected Row']").click();
		},
		gridComplete: function () {
			$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);	// highlight 1st record
			
			if($('#jqGrid').data('inputfocus') == 'tillcode_search'){
				$("#tillcode_search").focus();
				$('#jqGrid').data('inputfocus','');
				$('#tillcode_search_hb').text('');
				removeValidationClass(['#tillcode_search']);
			}else{
				$("#searchForm input[name=Stext]").focus();
			}
			fdl.set_array().reset();
		},
		loadComplete: function(data){
			calc_jq_height_onchange("jqGrid");
			// calc_jq_height_onchange("jqGridAlloc");
		},
	});
	
	///////////////////////////////////////////jqGridPager///////////////////////////////////////////
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
		},
	});
	///////////////////////////////////////////end jqGridPager///////////////////////////////////////////
	
	///////////////////////////////////////////jqGridTillDetl///////////////////////////////////////////
	var urlParamTillDetl={
		action:'get_tilldetl',
		url:'./tillenquiry/table',
		tillno:''
	};
	
	$("#jqGridTillDetl").jqGrid({
		datatype: "local",
		editurl: "./tillenquiry/form",
		colModel: [
			{ label: 'idno', name: 'idno', width: 20, hidden: true },
			{ label: 'compcode', name: 'compcode', width: 20, hidden: true },
			{ label: 'Debtor Code', name: 'debtorcode', width: 50, classes: 'wrap text-uppercase', hidden: true },
			{ label: 'Payer Code', name: 'payercode', width: 50, classes: 'wrap text-uppercase', formatter: showdetail, unformat:un_showdetail },
			{ label: 'FC', name: 'debtortype', width: 30, classes: 'wrap', formatter: showdetail, unformat:un_showdetail },
			{ label: 'Amount', name: 'amount', width: 25, classes: 'wrap', align: 'right', formatter:'currency' },
			{ label: 'Payer', name: 'payername', width: 60, classes: 'wrap text-uppercase', hidden: true },
			{ label: 'Mode', name: 'paymode', width: 30, classes: 'wrap text-uppercase', formatter: showdetail, unformat:un_showdetail },
			{ label: 'Reference', name: 'reference', width: 30, classes: 'wrap' },
			{ label: 'Receipt No', name: 'recptno', width: 50, classes: 'wrap' },
			{ label: 'Receipt Date', name: 'posteddate', width: 25, classes: 'wrap', formatter: dateFormatter, unformat: dateUNFormatter },
		],
		shrinkToFit: true,
		autowidth:true,
		// multiSort: true,
		viewrecords: true,
		rowNum: 30,
		sortname: 'idno',
		sortorder: "desc",
		pager: "#jqGridPagerTillDetl",
		loadComplete: function(data){
			calc_jq_height_onchange("jqGridTillDetl");
			
			refreshGrid("#jqGridTillDetl",urlParamTillDetl,'add');
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
	});
	jqgrid_label_align_right("#jqGridTillDetl");
	
	$("#jqGridTillDetl_panel").on("show.bs.collapse", function(){
		$("#jqGridTillDetl").jqGrid ('setGridWidth', Math.floor($("#jqGridTillDetl_c")[0].offsetWidth-$("#jqGridTillDetl_c")[0].offsetLeft-18));
	});
	/////////////////////////////////////////end jqGridTillDetl/////////////////////////////////////////
	
	////////////////////////////handle searching, its radio button and toggle////////////////////////////
	populateSelect('#jqGrid','#searchForm');
	
	////////////////////////////add field into param, refresh grid if needed////////////////////////////
	addParamField('#jqGrid', true, urlParam);
	
	////////////////////////////////////////formatter checkdetail////////////////////////////////////////
	function showdetail(cellvalue, options, rowObject){
		var field, table, case_;
		switch(options.colModel.name){
			// jqGrid
			case 'tillcode':field=['tillcode','description'];table="debtor.till";case_='tillcode';break;
			
			// jqGridTillDetl
			case 'payercode':field=['debtorcode','name'];table="debtor.debtormast";case_='payercode';break;
			case 'debtortype':field=['debtortycode','description'];table="debtor.debtortype";case_='debtortype';break;
			case 'paymode':field=['paymode','description'];table="debtor.paymode";case_='paymode';break;
		}
		var param={action:'input_check',url:'util/get_value_default',table_name:table,field:field,value:cellvalue,filterCol:[field[0]],filterVal:[cellvalue]};
		
		fdl.get_array('tillenquiry',options,param,case_,cellvalue);
		if(cellvalue == null)cellvalue = " ";
		return cellvalue;
	}
	
	//////////////////////////////////////////////cust_rules//////////////////////////////////////////////
	function cust_rules(value, name) {
		var temp=null;
		switch (name) {
			// case 'Till Code': temp = $("#jqGrid input[name='tillcode']"); break;
		}
		if(temp == null) return [true,''];
		return(temp.hasClass("error"))?[false,"Please enter valid "+name+" value"]:[true,''];
	}
	
	//////////////////////////////////////////////custom input//////////////////////////////////////////////
	// function tillcodeCustomEdit(val, opt) {
	// 	val = getEditVal(val);
	// 	return $('<div class="input-group"><input jqgrid="jqGrid" optid="'+opt.id+'" id="'+opt.id+'" name="tillcode" type="text" class="form-control input-sm" style="text-transform:uppercase" data-validation="required" value="' + val + '" style="z-index: 0"><a class="input-group-addon btn btn-primary"><span class="fa fa-ellipsis-h"></span></a></div><span class="help-block"></span>');
	// }
	
	function galGridCustomValue (elem, operation, value){
		if(operation == 'get') {
			return $(elem).find("input").val();
		} 
		else if(operation == 'set') {
			$('input',elem).val(value);
		}
	}
	
	////////////////////////////////////changing status and trigger search////////////////////////////////////
	$('#Scol').on('change', whenchangetodate);
	
	function whenchangetodate() {
		tillcode_search.off();
		$('#tillcode_search').val('');
		$('#tillcode_search_hb').text('');
		removeValidationClass(['#tillcode_search']);
		if($('#Scol').val() == 'tillcode'){
			$("input[name='Stext']").hide("fast");
			$("#tillcode_text").show("fast");
			tillcode_search.on();
		} else {
			$("#tillcode_text").hide("fast");
			$("input[name='Stext']").show("fast");
			$("input[name='Stext']").velocity({ width: "100%" });
		}
	}
	
	///////////////////////////////////populate data for dropdown search By///////////////////////////////////
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
		searchClick2('#jqGrid', '#searchForm', urlParam,false);
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////
	// function searchDate(){
	// 	urlParam.filterdate = [$('#docuDate_from').val(),$('#docuDate_to').val()];
	// 	refreshGrid('#jqGrid',urlParam);
	// 	urlParam.filterdate = null;
	// }
	
	// function searchChange(){
	// 	var arrtemp = [$('#Status option:selected').val()];
	// 	var filter = arrtemp.reduce(function(a,b,c){
	// 		if(b=='All'){
	// 			return a;
	// 		}else{
	// 			a.fc = a.fc.concat(a.fct[c]);
	// 			a.fv = a.fv.concat(b);
	// 			return a;
	// 		}
	// 	},{fct:['db.recstatus'],fv:[],fc:[]});
		
	// 	urlParam.filterCol = filter.fc;
	// 	urlParam.filterVal = filter.fv;
	// 	refreshGrid('#jqGrid',urlParam);
	// }
	
	var tillcode_search = new ordialog(
		'tillcode_search', 'debtor.till', '#tillcode_search', 'errorField',
		{
			colModel: [
				{ label: 'Till Code', name: 'tillcode', width: 200, classes: 'pointer', canSearch: true, or_search: true },
				{ label: 'Description', name: 'description', width: 400, classes: 'pointer', canSearch: true, checked: true, or_search: true },
			],
			urlParam: {
				filterCol:['compcode','recstatus'],
				filterVal:['session.compcode','ACTIVE']
			},
			ondblClickRow: function () {
				let data = selrowData('#' + tillcode_search.gridname).tillcode;
				
				if($('#Scol').val() == 'tillcode'){
					urlParam.searchCol=["tillcode"];
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
				// $('#db_debtorcode').val(data['debtorcode']);
			}
		},{
			title: "Select Till",
			open: function () {
				tillcode_search.urlParam.filterCol = ['compcode','recstatus'];
				tillcode_search.urlParam.filterVal = ['session.compcode','ACTIVE'];
			}
		},'urlParam','radio','tab'
	);
	tillcode_search.makedialog(true);
	$('#tillcode_search').on('keyup',ifnullsearch);
	
	function ifnullsearch(){
		if($(this).val() == ''){
			urlParam.searchCol=[];
			urlParam.searchVal=[];
			$('#jqGrid').data('inputfocus',$(this).attr('id'));
			refreshGrid('#jqGrid', urlParam);
		}
	}
	
	function populate_summary(){
		var param={
			action: 'get_tillclose',
			url: './tillenquiry/table',
			tillcode: selrowData('#jqGrid').tillcode,
			tillno: selrowData('#jqGrid').tillno,
		}
		$.get( param.url+"?"+$.param(param), function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.dbacthdr)){
				$("#CashCollected").val(data.sum_cash);
				$("#ChequeCollected").val(data.sum_chq);
				$("#CardCollected").val(data.sum_card);
				$("#DebitCollected").val(data.sum_bank);
			}
		});
	}
});

function calc_jq_height_onchange(jqgrid){
	let scrollHeight = $('#'+jqgrid+'>tbody').prop('scrollHeight');
	if(scrollHeight<80){
		scrollHeight = 80;
	}else if(scrollHeight>300){
		scrollHeight = 300;
	}
	$('#gview_'+jqgrid+' > div.ui-jqgrid-bdiv').css('height',scrollHeight+30);
}


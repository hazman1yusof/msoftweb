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

	////////////////////////////////////start dialog///////////////////////////////////////
	$("#dialogForm").dialog({ 
		width: 9/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {
			
		},
		close: function( event, ui ) {
			
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
		url:'./apenquiry/table',
	}

	/////////////////////parameter for saving url////////////////////////////////////////////////
	$("#jqGrid").jqGrid({
		datatype: "local",
		 colModel: [
			{ label: 'Supplier Code', name: 'apacthdr_suppcode', width: 70, classes: 'wrap text-uppercase', canSearch: true, formatter: showdetail, unformat:un_showdetail},
			{ label: 'Audit No', name: 'apacthdr_auditno', width: 18, classes: 'wrap',formatter: padzero, unformat: unpadzero, canSearch: true},
			{ label: 'Cheque No', name: 'apacthdr_cheqno', width: 30, classes: 'wrap text-uppercase', canSearch: true},
			{ label: 'PV No', name: 'apacthdr_pvno', width: 50, classes: 'wrap', hidden:true, canSearch: true},
			{ label: 'Document No', name: 'apacthdr_document', width: 50, classes: 'wrap text-uppercase', canSearch: true},
			{ label: 'Unit', name: 'apacthdr_unit', width: 30, hidden:false},
			{ label: 'Pay To', name: 'apacthdr_payto', width: 50, classes: 'wrap text-uppercase', hidden:true, canSearch: true},
			{ label: 'Category Code', name: 'apacthdr_category', width: 40, hidden:false, classes: 'wrap', formatter: showdetail, unformat:un_showdetail},		
			{ label: 'Document Date', name: 'apacthdr_actdate', width: 25, classes: 'wrap text-uppercase', canSearch: true, formatter: dateFormatter, unformat: dateUNFormatter},
			{ label: 'Amount', name: 'apacthdr_amount', width: 25, classes: 'wrap', align: 'right', formatter:'currency'},
			{ label: 'Outamount', name: 'apacthdr_outamount', width: 25, hidden:false, classes: 'wrap', align: 'right', formatter:'currency'},
			{ label: 'Transaction Type', name: 'apacthdr_trantype', width: 10, classes: 'wrap text-uppercase', canSearch: true, hidden:true},
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
			{ label: 'bankcode', name: 'apacthdr_bankcode', width: 50, classes: 'wrap text-uppercase', hidden:true},
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
		gridComplete: function(){
			if($('#jqGrid').data('inputfocus') == 'creditor_search'){
				$("#creditor_search").focus();
				$('#jqGrid').data('inputfocus','');
				$('#creditor_search_hb').text('');
				removeValidationClass(['#creditor_search']);
			}else{
				$("#searchForm input[name=Stext]").focus();
			}

			//$("#searchForm input[name=Stext]").focus();
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
	})

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
		
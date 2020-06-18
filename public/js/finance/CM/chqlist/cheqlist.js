
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();
	check_compid_exist("input[name='lastcomputerid']","input[name='lastipaddress']", "input[name='computerid']","input[name='ipaddress']","input[name='si_lastcomputerid']","input[name='si_lastipaddress']","input[name='si_computerid']","input[name='si_ipaddress']","input[name='sb_lastcomputerid']","input[name='sb_lastipaddress']","input[name='sb_computerid']","input[name='sb_ipaddress']");
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
				return {
					element : $(errorField[0]),
					message : ' '
				}
			}
		},
	};

	var fdl = new faster_detail_load();

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		table_name:'finance.bank',
		table_id:'bankcode',
		sort_idno:true,
	}
	
	//////////////////////////start grid/////////////////////////////////////////////////////////

	$("#jqGrid").jqGrid({
		datatype: "local",
		editurl: "/cheqlist/form",
		 colModel: [
			{ label: 'Bank Code', name: 'bankcode', width: 8,canSearch:true,checked:true},
			{ label: 'Bank Name', name: 'bankname', width: 20, canSearch:true},
			{ label: 'Address', name: 'address1', width: 17},
			{ label: 'Tel No', name: 'telno', width: 10},	
			{ label: 'idno', name: 'idno', hidden: true},
		],
		autowidth:true,
		viewrecords: true,
		multiSort: true,
		loadonce: false,
		sortname:'idno',
		sortorder:'desc',
		width: 900,
		height: 100,
		//rowNum: 30,
		pager: "#jqGridPager",
		gridComplete: function () {
			if($('#jqGrid').jqGrid('getGridParam', 'reccount') > 0 ){
				$("#jqGrid").setSelection($("#jqGrid").getDataIDs()[0]);
			}
			fdl.set_array().reset();

		},
		onSelectRow:function(rowid, selected){
			if(rowid != null) {
				urlParam_cheqlistdtl.filterVal[0]=selrowData("#jqGrid").bankcode;
				refreshGrid('#gridCheqListDetail',urlParam_cheqlistdtl);
				$("#pg_jqGridPager2 table").show();
			}
		},

		
	});

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	populateSelect('#jqGrid','#searchForm');
	searchClick('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);

	////////////////////////////cheq register detail/////////////////////////////////////////////////
	
	var urlParam_cheqlistdtl={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		table_name:'finance.chqtran',
		table_id:'idno',
		filterCol:['bankcode'],
		filterVal:[$("#bankcode").val()],
		sort_idno: true,
	}
	
	$("#gridCheqListDetail").jqGrid({
		datatype: "local",
		editurl: "/cheqlistDetail/form",
		colModel: [
			{ label: 'Comp Code', name: 'compcode', width: 50, hidden:true},	
			{ label: 'Bank Code', name: 'bankcode', width: 30, hidden: true,},
			{ label: 'Cheque No', name: 'cheqno', width: 20, classes: 'wrap', checked:true,canSearch: true},
			{ label: 'Remarks', name: 'remarks', width: 30, hidden:false,},
			{ label: 'Recstatus', name: 'recstatus', width: 30, hidden:false,checked:true,canSearch: true},
			{ label: 'idno', name: 'idno', hidden: true,editable: true, key:true},
			 
		],
		autowidth:true,
		viewrecords: true,
        multiSort: true,
		loadonce: false,
		rownumbers: true,
		sortname: 'idno',
		sortorder:'desc',
		width: 900,
		height: 200,
		//rowNum: 30,
		sord: "desc",
		hidegrid: false,
		caption: caption('searchForm2','Search By'),
		pager: "#jqGridPager2",
		onPaging: function (pgButton) {
		},
		onSelectRow:function(rowid, selected){
		
		},
		loadComplete: function(){
			
				$("#gridCheqListDetail").setSelection($("#gridCheqListDetail").getDataIDs()[0]);
			
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
	
		},
		gridComplete: function () {
			fdl.set_array().reset();

		},
	});

	
	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	//addParamField('#gridCheqListDetail',true,urlParam_cheqlistdtl);

	populateSelect('#gridCheqListDetail','#searchForm2');
	searchClick('#gridCheqListDetail','#searchForm2',urlParam_cheqlistdtl);

	/////////////////Pager Hide/////////////////////////////////////////////////////////////////////////////////////////
	$("#pg_jqGridPager2 table").hide();
	$("#pg_jqGridPager3 table").hide();

	$("#jqGrid3_panel1").on("show.bs.collapse", function(){
		$("#gridCheqListDetail").jqGrid ('setGridWidth', Math.floor($("#gridCheqListDetail_c")[0].offsetWidth-$("#gridCheqListDetail_c")[0].offsetLeft-28));
		refreshGrid('#gridCheqListDetail',urlParam_cheqlistdtl);
	});

	
});
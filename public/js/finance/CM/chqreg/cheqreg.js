
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
	var addmore_jqgrid={more:false,state:false,edit:false}	
	$("#jqGrid").jqGrid({
		datatype: "local",
		editurl: "/cheqreg/form",
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
		rowNum: 30,
		pager: "#jqGridPager",
		loadComplete: function(){
			if(addmore_jqgrid.more == true){$('#jqGrid_iladd').click();}
				else{
						$('#jqGrid2').jqGrid ('setSelection', "1");
					}

				addmore_jqgrid.edit = addmore_jqgrid.more = false; //reset
		},
		ondblClickRow: function(rowid, iRow, iCol, e){
			$("#jqGrid_iledit").click();
		},
		gridComplete: function () {
			fdl.set_array().reset();
		},
		onSelectRow:function(rowid, selected){
			if(rowid != null) {
				urlParam_cheqregdtl.filterVal[0]=selrowData("#jqGrid").bankcode;
				refreshGrid('#gridCheqRegDetail',urlParam_cheqregdtl);
				$("#pg_jqGridPager2 table").show();
			}

		},
		
	});


	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
			var urlParam_cheqregdtl={
				action:'get_table_default',
				url:'util/get_table_default',
				field:'',
				table_name:'finance.chqreg',
				table_id:'startno',
				filterCol:['bankcode'],
				filterVal:[''],
				sort_idno: true,
			}

			$("#gridCheqRegDetail").jqGrid({
				editurl: "/cheqreg/form",
				datatype: "local",
				colModel: [
				 	{ label: 'Comp Code', name: 'compcode', width: 50, hidden:true},	
					{ label: 'Bank Code', name: 'bankcode', width: 30, hidden: true, editable: true,},
					{ label: 'Start Number', name: 'startno', width: 20, classes: 'wrap', sorttype: 'number', editable: true,
							editrules:{required: true},edittype:"text",canSearch:true,checked:true,
							editoptions:{
								maxlength: 11,
								dataInit: function(element) {
									$(element).keypress(function(e){
										 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								}
							},
					},
					{ label: 'End Number', name: 'endno', width: 20, classes: 'wrap', editable: true,
							editrules:{required: true},edittype:"text",canSearch:true,
							editoptions:{
								maxlength: 11,
								dataInit: function(element) {
									$(element).keypress(function(e){
										 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
											return false;
										 }
									});
								}
							},
					},
					{ label: 'Cheq Qty', name: 'cheqqty', width: 30, hidden:true,},
					{ label: 'Stat', name: 'stat', width: 30, hidden:true,},
					{ label: 'Action', name: 'action', width :10,  formatoptions: { keys: false, editbutton: true, delbutton: true }, formatter: 'actions'},
					{label: 'idno', name: 'idno', hidden: true},
				],
				autowidth:true,
				viewrecords: true,
                multiSort: true,
				loadonce: false,
				rownumbers: true,
				//sortname: 'startno',
				//sortorder:'desc',
				height: 124,
				rowNum: 30,
				sord: "desc",
				pager: "#jqGridPager2",
			});

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam);
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first", 
		id: "jqGridPagerglyphicon-trash",
		buttonicon:"glyphicon glyphicon-trash",
		title:"Delete Selected Row",
		onClickButton: function(){
			oper='del';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			if(!selRowId){
				alert('Please select row');
				return emptyFormdata(errorField,'#formdata');
			}else{
				saveFormdata("#jqGrid","#dialogForm","#formdata",'del',saveParam,urlParam,{'idno':selrowData('#jqGrid').idno});
			}
		}, 
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first", 
		buttonicon:"glyphicon glyphicon-info-sign",
		title:"View Selected Row",  
		onClickButton: function(){
			oper='view';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'view');
		},
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-edit",
		title:"Edit Selected Row",  
		onClickButton: function(){
			oper='edit';
			selRowId = $("#jqGrid").jqGrid ('getGridParam', 'selrow');
			populateFormdata("#jqGrid","#dialogForm","#formdata",selRowId,'edit');
			recstatusDisable();
			
		}, 
	}).jqGrid('navButtonAdd',"#jqGridPager",{
		caption:"",cursor: "pointer",position: "first",  
		buttonicon:"glyphicon glyphicon-plus", 
		title:"Add New Row", 
		onClickButton: function(){
			oper='add';
			$( "#dialogForm" ).dialog( "open" );
		},
	});
	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////handle searching, its radio button and toggle ///////////////////////////////////////////////
	
	//toogleSearch('#sbut1','#searchForm','on');
	populateSelect('#jqGrid','#searchForm');
	searchClick('#jqGrid','#searchForm',urlParam);

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	addParamField('#jqGrid',false,saveParam,['idno','compcode','adduser','adddate','upduser','upddate','recstatus','computerid','ipaddress']);

	/////////////////Pager Hide/////////////////////////////////////////////////////////////////////////////////////////
	$("#pg_jqGridPager2 table").hide();
	$("#pg_jqGridPager3 table").hide();

	$("#jqGrid3_panel1").on("show.bs.collapse", function(){
		$("#gridCheqRegDetail").jqGrid ('setGridWidth', Math.floor($("#gridCheqRegDetail_c")[0].offsetWidth-$("#gridCheqRegDetail_c")[0].offsetLeft-28));
	});

});
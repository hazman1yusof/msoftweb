$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

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

	var fdl = new faster_detail_load();

	/////////////////////parameter for jqgrid url/////////////////////////////////////////////////
	var urlParam={
		action:'get_table_default',
		url:'util/get_table_default',
		field:'',
		filterCol:['compcode', 'recstatus'],
		filterVal:['session.compcode', 'ACTIVE'],
		table_name:'material.suppgroup',
		table_id:'idno',
		sort_idno:true,
	}

	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label:'idno', name: 'idno', sorttype: 'number', hidden:true },
			{ label:'compcode', name: 'compcode', hidden:true},
			{ label:'Supplier Group',name:'suppgroup',width:200,classes:'pointer', canSearch: true, or_search: true },
			{ label:'Description',name:'description',width:500,classes:'pointer', canSearch: true, checked: true, or_search: true },
			{ label:'GL Account',name:'glaccno',width:200,classes:'pointer'},
		],
		autowidth:true,
        multiSort: true,
		viewrecords: true,
		loadonce:false,
		sortname:'idno',
		sortorder:'desc',
		width: 550,
		height: 150,
		rowNum: 50,
		pager: "#jqGridPager",
		onSelectRow:function(rowid, selected){
			$("#jqGrid input[name='suppgroup']").change(function(){
				$("#jqGrid input[name='suppgroup']").val($(this).val());
			});
		   
			///summary
			$('#summary_pdf').click(function(){
				window.open('./SuppList_Report/summarypdf?suppgroup='+selrowData("#jqGrid").suppgroup, '_blank'); 
			});
		
			$('#summary_excel').click(function(){
				window.location='./SuppList_Report/summaryExcel?suppgroup='+selrowData("#jqGrid").suppgroup;
			});
		
			///detail
			$('#dtl_pdf').click(function(){
				window.open('./SuppList_Report/dtlpdf?suppgroup='+selrowData("#jqGrid").suppgroup, '_blank'); 
			});
		
			$('#dtl_excel').click(function(){
				window.location='./SuppList_Report/dtlExcel?suppgroup='+selrowData("#jqGrid").suppgroup;
			});
		},
		gridComplete: function(){
			fdl.set_array().reset();
		},
		
	});

	/////////////////////////start grid pager/////////////////////////////////////////////////////////
	$("#jqGrid").jqGrid('navGrid','#jqGridPager',{	
		view:false,edit:false,add:false,del:false,search:false,
		beforeRefresh: function(){
			refreshGrid("#jqGrid",urlParam);
		},
	});
	//////////////////////////////////////end grid/////////////////////////////////////////////////////////

	//////////add field into param, refresh grid if needed////////////////////////////////////////////////
	addParamField('#jqGrid',true,urlParam);
	
    /////////////////////////////////////dialog handler///////////////////////////////
	var suppgroup = new ordialog(
		'suppgroup','material.suppgroup','#suppgroup','errorField',
		{	
			colModel:[
				{label:'Supplier Group',name:'suppgroup',width:200,classes:'pointer', canSearch: true, or_search: true },
				{label:'Description',name:'description',width:400,classes:'pointer', canSearch: true, checked: true, or_search: true },
				{label:'GL Account',name:'glaccno',width:400,classes:'pointer'},
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
			title:"Select Supplier Group",
			open: function(){
				suppgroup.urlParam.filterCol=['compcode','recstatus'];
				suppgroup.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(obj_){
			},
		},'urlParam','radio','tab'
	);
	suppgroup.makedialog(true);
});
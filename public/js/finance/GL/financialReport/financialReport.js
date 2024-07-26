$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	set_yearperiod();
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
		table_name:'sysdb.sector',
		table_id:'idno',
		sort_idno:true,
	}

	$('input[type=radio][name=Class]').change(function(){
		var thisval = $(this).val();
		console.log(thisval);
		switch(thisval){
			case 'All':
				$('div.divto').show();
				break;
			case 'Department':
				$('div.divto').hide();
				break;
			case 'Units':
				$('div.divto').show();
				break;
			case 'Variance':
				$('div.divto').hide();
				break;
		}
	});

	$("#jqGrid").jqGrid({
		datatype: "local",
		colModel: [
			{ label:'idno', name: 'idno', sorttype: 'number', hidden:true },
			{ label:'compcode', name: 'compcode', hidden:true},
			{ label:'Unit',name:'sectorcode',width:200,classes:'pointer', canSearch: true, or_search: true },
			{ label:'Description',name:'description',width:500,classes:'pointer', canSearch: true, checked: true, or_search: true },
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
			// $("#jqGrid input[name='suppgroup']").change(function(){
			// 	$("#jqGrid input[name='suppgroup']").val($(this).val());
			// });
		   
			// ///summary
			// $('#summary_pdf').click(function(){
			// 	window.open('./SuppList_Report/summarypdf?suppgroup='+selrowData("#jqGrid").suppgroup, '_blank'); 
			// });
		
			// $('#summary_excel').click(function(){
			// 	window.location='./SuppList_Report/summaryExcel?suppgroup='+selrowData("#jqGrid").suppgroup;
			// });
		
			// ///detail
			// $('#dtl_pdf').click(function(){
			// 	window.open('./SuppList_Report/dtlpdf?suppgroup='+selrowData("#jqGrid").suppgroup, '_blank'); 
			// });
		
			// $('#dtl_excel').click(function(){
			// 	window.location='./SuppList_Report/dtlExcel?suppgroup='+selrowData("#jqGrid").suppgroup;
			// });
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
	var reporttype = new ordialog(
		'reporttype','finance.glrpthdr','#reporttype','errorField',
		{	
			colModel:[
				{label:'Report Name',name:'rptname',width:200,classes:'pointer', canSearch: true, or_search: true, checked: true },
				{label:'Report Type',name:'rpttype',width:200,classes:'pointer', canSearch: true, or_search: true },
				{label:'Report Description',name:'description',width:400,classes:'pointer', canSearch: true},
			],
			urlParam: {
				filterCol:['compcode'],
				filterVal:['session.compcode']
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
				reporttype.urlParam.filterCol=['compcode'];
				reporttype.urlParam.filterVal=['session.compcode'];
			},
			close: function(obj_){
			},
		},'urlParam','radio','tab'
	);
	reporttype.makedialog(true);

	var department = new ordialog(
		'department','sysdb.department','#department','errorField',
		{	
			colModel:[
				{label:'Department Code',name:'deptcode',width:200,classes:'pointer', canSearch: true, or_search: true, checked: true },
				{label:'Department Description',name:'description',width:400,classes:'pointer', canSearch: true},
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
				reporttype.urlParam.filterCol=['compcode','recstatus'];
				reporttype.urlParam.filterVal=['session.compcode','ACTIVE'];
			},
			close: function(obj_){
			},
		},'urlParam','radio','tab'
	);
	department.makedialog(true);

});

function set_yearperiod(){
	param={
		action:'get_value_default',
		field: ['year'],
		table_name:'sysdb.period',
		table_id:'idno',
		sortby:['year desc']
	}
	$.get( "util/get_value_default?"+$.param(this.param), function( data ) {
			
	},'json').done(function(data) {
		if(!$.isEmptyObject(data.rows)){
			data.rows.forEach(function(element){	
				$('#yearfrom').append("<option>"+element.year+"</option>")
				$('#yearto').append("<option>"+element.year+"</option>")
			});
		}
	});

	$('select#monthfrom').val(moment().format('MM'));
	$('select#monthto').val(moment().format('MM'));
}
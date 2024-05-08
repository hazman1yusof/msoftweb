
$.validate({
	modules: 'sanitize',
	language: {
		requiredFields: ''
	},
});

var errorField = [];
conf = {
	onValidate: function ($form) {
		if (errorField.length > 0) {
			console.log(errorField);
			return [{
				element: $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
				message: ' '
			}]
		}
	},
};

$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {

    $("#genreport input[name='item_from']").change(function(){
		$("#genreportpdf input[name='item_from']").val($(this).val());
	});
	$("#genreport input[name='item_to']").change(function(){
		$("#genreportpdf input[name='item_to']").val($(this).val());
	});
   
	$('#pdfgen').click(function(){
		window.open('./avgcost_vs_currcost/showpdf?item_from='+$('#item_from').val()+'&item_to='+$("#item_to").val(),  '_blank'); 
	});

	$('#excelgen').click(function(){
		window.location='./avgcost_vs_currcost/showExcel?item_from='+$('#item_from').val()+'&item_to='+$("#item_to").val();
	});

    /////////////////////////////////////dialog handler///////////////////////////////
	var dialog_itemcodefrom = new ordialog(
		'item_from','material.product AS p',"#item_from",'errorField',
		{	
			colModel:[
				{label: 'Item Code',name:'itemcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label: 'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label: 'UOM Code',name:'uomcode',width:100,classes:'pointer', hidden:false},
				{label: 'unitcost',name:'currprice',width:100,classes:'pointer', hidden:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode','unit'],
				filterVal:['ACTIVE','session.compcode','session.unit'],
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
			title:"Select Item Code From",
			open: function(){
				dialog_itemcodefrom.urlParam.filterCol=['recstatus','compcode','unit'];
				dialog_itemcodefrom.urlParam.filterVal=['ACTIVE','session.compcode','session.unit'];
			},
			close: function(obj_){
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
	dialog_itemcodefrom.makedialog(true);
	
	var dialog_itemcodeto = new ordialog(
		'item_to','material.product AS p',"#item_to",errorField,
		{	
			colModel:[
				{label: 'Item Code',name:'itemcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label: 'Description',name:'description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
				{label: 'UOM Code',name:'uomcode',width:100,classes:'pointer', hidden:false},
				{label: 'unitcost',name:'currprice',width:100,classes:'pointer', hidden:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode','unit'],
				filterVal:['ACTIVE','session.compcode','session.unit'],
			},
			
			ondblClickRow: function () {
				$("#jqGrid2").jqGrid("clearGridData", true);
				let item_from = $('#item_from').val();
				let item_to = $('#item_to').val();
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
			title:"Select Item Code To",
			open: function(){
				dialog_itemcodeto.urlParam.filterCol=['recstatus','compcode','unit'];
				dialog_itemcodeto.urlParam.filterVal=['ACTIVE','session.compcode','session.unit'];
			},
			close: function(obj_){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('item_to',errorField)!==-1){
						errorField.splice($.inArray('item_to',errorField), 1);
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
	dialog_itemcodeto.makedialog(true);

});


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

    $("#genreport input[name='dept_from']").change(function(){
		$("#genreportpdf input[name='dept_from']").val($(this).val());
	});
	$("#genreport input[name='dept_to']").change(function(){
		$("#genreportpdf input[name='dept_to']").val($(this).val());
	});
   
	$('#pdfgen').click(function(){
		window.open('./deptItemList/showpdf?dept_from='+$('#dept_from').val()+'&dept_to='+$("#dept_to").val(),  '_blank'); 
	});

	$('#excelgen').click(function(){
		window.location='./deptItemList/showExcel?dept_from='+$('#dept_from').val()+'&dept_to='+$("#dept_to").val();
	});

    /////////////////////////////////////dialog handler///////////////////////////////
	var dialog_deptcodefrom = new ordialog(
		'dept_from','sysdb.department',"#dept_from",'errorField',
		{	
			colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
				{label:'Unit',name:'sector', hidden:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode','sector'],
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
			title:"Select Dept Code From",
			open: function(){
				dialog_deptcodefrom.urlParam.filterCol=['recstatus','compcode','sector'];
				dialog_deptcodefrom.urlParam.filterVal=['ACTIVE','session.compcode','session.unit'];
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
	dialog_deptcodefrom.makedialog(true);
	
	var dialog_deptcodeto = new ordialog(
		'dept_to','sysdb.department',"#dept_to",errorField,
		{	
			colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
				{label:'Unit',name:'sector', hidden:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode','sector'],
				filterVal:['ACTIVE','session.compcode','session.unit'],
			},
			
			ondblClickRow: function () {
				$("#jqGrid2").jqGrid("clearGridData", true);
				let dept_from = $('#dept_from').val();
				let dept_to = $('#dept_to').val();
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
			title:"Select Dept Code To",
			open: function(){
				dialog_deptcodeto.urlParam.filterCol=['recstatus','compcode','sector'];
				dialog_deptcodeto.urlParam.filterVal=['ACTIVE','session.compcode','session.unit'];
			},
			close: function(obj_){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('dept_to',errorField)!==-1){
						errorField.splice($.inArray('dept_to',errorField), 1);
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
	dialog_deptcodeto.makedialog(true);

});

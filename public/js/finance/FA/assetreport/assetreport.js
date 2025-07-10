
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
	var catfr_dialog = new ordialog(
		'catfr','finance.facode','#catfr',errorField,
		{	
			colModel:[
				{label:'Code',name:'assetcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],//,'sector'
				filterVal:['ACTIVE','session.compcode']//, 'session.unit'
			},
			sortname:'assetcode',
			sortorder:'asc',
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
			title:"Select Department",
			open: function(){
				catfr_dialog.urlParam.filterCol=[ 'recstatus','compcode'];//,'sector'
				catfr_dialog.urlParam.filterVal=['ACTIVE','session.compcode'];//, 'session.unit'
			},
			close: function(obj_){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('catfr',errorField)!==-1){
						errorField.splice($.inArray('catfr',errorField), 1);
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
	catfr_dialog.makedialog(true);

	var catto_dialog = new ordialog(
		'catto','finance.facode','#catto','errorField',
		{	
			colModel:[
				{label:'Code',name:'assetcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
				{label:'Type',name:'assettype',width:100,},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],//,'sector'
				filterVal:['ACTIVE','session.compcode']//, 'session.unit'
			},
			sortname:'assetcode',
			sortorder:'asc',
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
			title:"Select Department",
			open: function(){
				catto_dialog.urlParam.filterCol=['recstatus','compcode'];//,'sector'
				catto_dialog.urlParam.filterVal=['ACTIVE','session.compcode'];//, 'session.unit'
			},
			close: function(obj_){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('catto',errorField)!==-1){
						errorField.splice($.inArray('catto',errorField), 1);
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
	catto_dialog.makedialog(true);

	$('button.mybtn').click(function(){
		$('input[name=action]').val($(this).attr('name'));
		var btntype = $(this).data('btntype');

		if($('#formdata').isValid({requiredFields:''},conf,true)){
			var serializedForm =  $('#formdata').serializeArray()

			// var param={
			// 	dept_from:'get_value_default',
			// 	dept_to: './util/get_value_default',
			// 	item_from:['backday'],
			// 	item_to:'material.sequence',
			// 	year:['compcode','dept','trantype'],
			// 	period:['session.compcode',this.deptcode,this.trxtype]
			// }

			window.open('./assetreport/table?'+$.param(serializedForm), '_blank');
		}

		
	});
});
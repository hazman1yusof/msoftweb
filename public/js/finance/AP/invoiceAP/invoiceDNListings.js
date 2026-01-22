
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

	var supp_from = new ordialog(
		'supp_from','material.supplier','#supp_from','errorField',
		{	
			colModel:[
				{label:'Supplier code',name:'suppcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Supplier name',name:'Name',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],//,'sector'
				filterVal:['ACTIVE','session.compcode']//, 'session.unit'
			},
			sortname:'suppcode',
			sortorder:'asc',
			ondblClickRow: function () {
				let data=selrowData('#'+supp_from.gridname);

				$("#supp_to").val(data['suppcode']);
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
				supp_from.urlParam.filterCol=['recstatus','compcode'];//,'sector'
				supp_from.urlParam.filterVal=['ACTIVE','session.compcode'];//, 'session.unit'
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
	supp_from.makedialog(true);

	var supp_to = new ordialog(
		'supp_to','sysdb.department','#supp_to','errorField',
		{	
			colModel:[
				{label:'Supplier code',name:'suppcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Supplier name',name:'Name',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
			],
			urlParam: {
				filterCol:['recstatus','compcode'],//,'sector'
				filterVal:['ACTIVE','session.compcode']//, 'session.unit'
			},
			sortname:'suppcode',
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
				supp_to.urlParam.filterCol=['recstatus','compcode'];//,'sector'
				supp_to.urlParam.filterVal=['ACTIVE','session.compcode'];//, 'session.unit'
			},
			close: function(obj_){
			},
			after_check: function(data,self,id,fail,errorField){
				let value = $(id).val();
				if(value.toUpperCase() == 'ZZZ'){
					ordialog_buang_error_shj(id,errorField);
					if($.inArray('supp_to',errorField)!==-1){
						errorField.splice($.inArray('supp_to',errorField), 1);
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
	supp_to.makedialog(true);

	$('button.mybtn').click(function(){
		$('input[name=action]').val($(this).attr('name'));

		if($('#formdata').isValid({requiredFields:''},conf,true)){
			var serializedForm =  $('#formdata').serializeArray()

			// var param={
			// 	supp_from:'get_value_default',
			// 	supp_to: './util/get_value_default',
			// 	item_from:['backday'],
			// 	item_to:'material.sequence',
			// 	year:['compcode','dept','trantype'],
			// 	period:['session.compcode',this.deptcode,this.trxtype]
			// }

			window.open('./invoiceAP/table?'+$.param(serializedForm), '_blank');
		}

		
	});
});
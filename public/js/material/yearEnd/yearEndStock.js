
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
	var dept_from = new ordialog(
		'dept_from','sysdb.department','#dept_from',errorField,
		{	
			colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
				{label:'Unit',name:'sector', hidden:true},
			],
			urlParam: {
				filterCol:['storedept', 'recstatus','compcode'],//,'sector'
				filterVal:['1', 'ACTIVE','session.compcode']//, 'session.unit'
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
			title:"Select Department",
			open: function(){
				dept_from.urlParam.filterCol=['storedept', 'recstatus','compcode'];//,'sector'
				dept_from.urlParam.filterVal=['1', 'ACTIVE','session.compcode'];//, 'session.unit'
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
		},'urlParam','radio','tab'
	);
	dept_from.makedialog(true);

	var dept_to = new ordialog(
		'dept_to','sysdb.department','#dept_to',errorField,
		{	
			colModel:[
				{label:'Department',name:'deptcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label:'Description',name:'description',width:400,classes:'pointer',canSearch:true,or_search:true,checked:true},
				{label:'Unit',name:'sector', hidden:true},
			],
			urlParam: {
				filterCol:['storedept', 'recstatus','compcode'],//,'sector'
				filterVal:['1', 'ACTIVE','session.compcode']//, 'session.unit'
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
			title:"Select Department",
			open: function(){
				dept_to.urlParam.filterCol=['storedept', 'recstatus','compcode'];//,'sector'
				dept_to.urlParam.filterVal=['1', 'ACTIVE','session.compcode'];//, 'session.unit'
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
		},'urlParam','radio','tab'
	);
	dept_to.makedialog(true);

	var dialog_itemcodefrom = new ordialog(
		'item_from',['material.product AS p'],"#item_from",errorField,
		{	
			colModel:[
				{label: 'Item Code',name:'p_itemcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label: 'Description',name:'p_description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},

			],
			urlParam: {
				fixPost : "true",
				filterCol:['p.recstatus','p.compcode'],//,'sector'
				filterVal:['ACTIVE','session.compcode'],
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
			title:"Select Department",
			open: function(){
				dialog_itemcodefrom.urlParam.table_name = ['material.product AS p'];
				dialog_itemcodefrom.urlParam.fixPost = "true";
				dialog_itemcodefrom.urlParam.table_id = "none_";
				dialog_itemcodefrom.urlParam.filterCol=['p.recstatus','p.compcode'];//,'sector'
				dialog_itemcodefrom.urlParam.filterVal=['ACTIVE','session.compcode'];//, 'session.unit'
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
		},'urlParam','radio','tab'
	);
	dialog_itemcodefrom.makedialog(true);
	
	var dialog_itemcodeto = new ordialog(
		'item_to',['material.product AS p'],"#item_to",errorField,
		{	
			colModel:[
				{label: 'Item Code',name:'p_itemcode',width:200,classes:'pointer',canSearch:true,or_search:true},
				{label: 'Description',name:'p_description',width:400,classes:'pointer',canSearch:true,checked:true,or_search:true},
			],
			urlParam: {
				fixPost : "true",
				filterCol:['p.recstatus','p.compcode'],//,'sector'
				filterVal:['ACTIVE','session.compcode']
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
			title:"Select Department",
			open: function(){
				dialog_itemcodeto.urlParam.table_name = ['material.product AS p'];
				dialog_itemcodeto.urlParam.fixPost = "true";
				dialog_itemcodeto.urlParam.table_id = "none_";
				dialog_itemcodeto.urlParam.filterCol=['p.recstatus','p.compcode'];//,'sector'
				dialog_itemcodeto.urlParam.filterVal=['ACTIVE','session.compcode'];//, 'session.unit'
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
		},'urlParam','radio','tab'
	);
	dialog_itemcodeto.makedialog(true);

	$('#generate').click(function(){
		if($('#formdata').isValid({requiredFields:''},conf,true)){
			$('#ops_success').hide();
			$('#ops_wait').show();
			$('#generate').prop('disabled',true);
			var serializedForm =  $('#formdata').serializeArray()

			var param={
				url:'./yearEnd/form',
			}

			$.post( param.url, serializedForm , function( data ) {
		
			},'json').fail(function(data) {
				$('#generate').prop('disabled',false);
				alert('fail');
				$('#ops_success').hide();
				$('#ops_wait').hide();
			}).done(function(data){
				$('#generate').prop('disabled',false);
				var counter = data.counter;
				var year = $('#year').val();

				$('#span_counter').text(counter);
				$('#span_year').text(year);
				$('#ops_success').show();
				$('#ops_wait').hide();
			});
		}

		
	});
});